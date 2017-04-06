<?php
namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\SchemaValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ToolController extends Controller
{
    public function __construct()
    {

    }

    /**
     * @Route("/tool", name="app_tool")
     */
    public function toolAction(Request $request)
    {
        if(isset($_POST['action']))
        {
            switch ($_POST['action'])
            {
                case 'reset':
                    $this->resetDB();
                    $this->rebuildDB();
                    //sleep(1);
                    $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
                    echo "Process Time: {$time}";
                    break;
                case 'fill':
                    ini_set('max_execution_time', 0); //300 seconds = 5 minutes //300.000 8 uur
                    //ini_set('memory_limit', '-1');
                    $this->fillAppBundle();
                    //$this->fillAccounts();
                    $time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];
                    echo "Process Time: {$time}";
                    break;
                case 'validate':
                    $this->validateModel();
                    break;
            }
        }

        return $this->render('app/tool.html.twig', []);
    }

    /**
     * Drop het schema van de database.
     * Alle tabellen worden verwijderd inclusief de data
     */
    private function resetDB()
    {
        $em = $this->getDoctrine()->getManager('klantB');
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropSchema($em->getMetadataFactory()->getAllMetadata());

        echo "schema dropped <br/>";
    }

    /**
     * Maak een nieuw database schema.
     * Alle tabellen worden aangemaakt door de metadata --> Doctrine annotations van iedere entity.
     */
    private function rebuildDB()
    {
        $em = $this->getDoctrine()->getManager('klantB');
        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());

        echo "schema rebuild <br/>";

    }

    /**
     * controleer de mappings/relaties van iedere entity of die valide zijn.
     *
     */
    private function validateModel()
    {
        $em = $this->getDoctrine()->getManager();
        $validator = new SchemaValidator($em);
        $errors = $validator->validateMapping();
        if(count($errors) > 0)
        {
            echo "Oops... some errors occurred. Please see the profiler to fix these errors.";
            dump($errors);
        }else
        {
            echo "Validation complete, no errors. =)";
        }

        return;
    }

    /**
     * Database aanvullen met 'fake' data van de entities binnen de src/AppBundle map
     */
    private function fillAppBundle()
    {
        $batchSize = 10;
        $em = $this->getDoctrine()->getManager('klantB');
        $password = $this->get('security.password_encoder');
        for($i=1; $i< 11; $i++)
        {
            $user = new User();
            $user->setEmail("user{$i}@mail.com");
            $user->setUsername("user{$i}@mail.com");
            $user->setPlainPassword("user{$i}");
            $cleanpassword = $password->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($cleanpassword);
            $em->persist($user);
            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear(); // Detaches all objects from Doctrine!
            }
        }
        $em->flush(); //Persist objects that did not make up an entire batch
        $em->clear();
        return;
    }

    private function save($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
    }

    private function delete($entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
    }
}