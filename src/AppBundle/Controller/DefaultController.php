<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
//    /**
//     * @Route("/", name="homepage")
//     */
//    public function indexAction(Request $request)
//    {
//        // replace this example code with whatever you need
//        return $this->render('default/index.html.twig', [
//            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
//        ]);
//    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        //$em = $this->getDoctrine()->getManager('klantB');
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('default/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

        //return $this->render('default/login.html.twig', []);
    }

    /**
     * @Route("/register", name="registerpage")
     */
    public function registerAction(Request $request)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            //$this->sendRegistrationMail();

            return $this->redirectToRoute('login');
        }

        return $this->render('default/register.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/logout", name="logoutpage")
     */
    public function logoutAction()
    {
        /*
         * The Symfony Security Component only needs a method header.
         * The method body can be empty unless custom logout logic is needed.
         * The Security component removes the session of the current logged in user.
        */
    }

    /**
     * @Route("/base", name="basepage")
     */
    public function baseAction(Request $request)
    {
        return $this->render('default/backofficeview.html.twig', []);
    }

    /**
     * @Route("/firstpage", name="firstpage")
     */
    public function firstAction(Request $request)
    {

        return $this->render('default/firstpage.html.twig', []);
    }

    /**
     * @Route("/viewcourses", name="viewcoursespage")
     */
    public function viewcoursesAction(Request $request)
    {
        return $this->render('courses/viewcourses.html.twig', []);
    }

    /**
     * @Route("/viewaccounts", name="viewaccountspage")
     */
    public function viewAccountsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entries = $em->getRepository("AppBundle:User")->findAll();

        return $this->render('accounts/viewaccounts.html.twig', array('users'=>$entries));
    }

    /**
     * @Route("/tojson", name="tojson")
     */
    public function toJsonData()
    {
        $em = $this->getDoctrine()->getManager();
        $entries = $em->getRepository("AppBundle:User")->findAll();


    }

    /**
     * @Route("/viewcreateaccount", name="viewcreateaccountspage")
     */
    public function viewcreateaccountAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //$this->sendRegistrationMail();

            return $this->redirectToRoute('viewaccountspage');
        }

        return $this->render('accounts/viewcreate.html.twig', array('form' => $form->createView()));
    }
    /**
     * @Route("/vieweditaccount/{id}", name="vieweditaccountspage")
     */
    public function viewEditAccounAction(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('viewaccountspage', array('id' => $user->getId()));
        }

        return $this->render('accounts/viewcreate.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/viewremoveaccount/{id}", name="viewremoveaccountspage")
     */
    public function viewRemoveAccountAction(Request $request, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($user->getId());
        $em->remove($user);
        $em->flush($user);

        return $this->redirectToRoute('viewaccountspage');
    }

    /**
     * @Route("/resetpassword", name="resetpasswordpage")
     */
    public function resetPasswordAction(Request $request)
    {
//        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        die("".$token);
    }

    private function sendRegistrationMail($subject = "", $from = "", $to = "", $body = "")
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo('JoostFaber@hotmail.com')
//                ->setBody(
//                    $this->renderView(
//                    // app/Resources/views/Emails/registration.html.twig
//                        'Emails/registration.html.twig',
//                        array('name' => $name)
//                    ),
//                    'text/html'
//                )
            ->setBody("registered, it works!");
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'Emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */
        ;
        $this->get('mailer')->send($message);
    }
}
