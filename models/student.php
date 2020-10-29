<?php

class student{

    private $first_name;
    private $last_name;
    private $status;
    private $career;

    function __construct($first_name, $last_name, $status, $career)
    {   
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->status = $status;
        $this->career = $career;
    }

    
}