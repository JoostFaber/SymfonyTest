<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="app_persons")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class Person
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $prefixes;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $givenname;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $gender;

    /**
     * @ORM\Column(type="date", length=25)
     */
    private $birthdate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * @param mixed $prefixes
     */
    public function setPrefixes($prefixes)
    {
        $this->prefixes = $prefixes;
    }

    /**
     * @return mixed
     */
    public function getGivenname()
    {
        return $this->givenname;
    }

    /**
     * @param mixed $givenname
     */
    public function setGivenname($givenname)
    {
        $this->givenname = $givenname;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * @param mixed $birthdate
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
    }

    //Mappings
    private $address;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="person")
     * @var ArrayCollection
     */
    private $user;
    private $profilePicture;
}