<?php

namespace AppBundle\Entity;

class Course
{
    private $id;
    private $name;
    private $description;
    private $startDate;
    private $endDate;
    private $courseType; //onderwijsvorm voltijd,deeltijd,duaal

    //Mappings
    private $courseBlocks;
    private $coretaks;
}