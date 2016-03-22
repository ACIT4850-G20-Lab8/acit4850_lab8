<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Timetable
 *
 * @author ziki
 */
class Timetable extends CI_Model {

    protected $xml = null;
    protected $courses = array();
    protected $coursetype = '';
    protected $id = '';
    protected $day = array();
    protected $daytype = '';
    protected $starttimes = array();
    protected $starttimetype = '';
    protected $name = '';
    protected $crn = '';
    protected $type = '';
    protected $building = '';
    protected $room = '';
    protected $end = '';
    protected $instructor = '';
    protected $course = '';
    protected $start = '';
    protected $bytime= array();
    public function __construct($filename = null) {
        parent::__construct();
        if ($filename == null)
            return;

        $this->xml = simplexml_load_file(DATAPATH . $filename . XMLSUFFIX);
        if (strcmp($filename, 'course') == 0) {
            $this->facetCourse();
        }else if((strcmp($filename, 'day') == 0)){
            $this->facetDay();
        }else {
            $this->facetTime();
        }
    }

    function facetCourse() {
        //extract basics
        $this->id = (string) $this->xml->courses->id;
        $this->coursetype = (string) $this->xml->courses['type'];

        foreach ($this->xml->courses->day as $oneday) {
            $this->daytype = (string) $oneday['type'];
            $this->starttimetype = (string) $oneday->starttime['type'];
            foreach ($this->xml->courses->day->starttime as $one) {

                $this->starttimes[] = new Booking($one, $this->daytype, $this->starttimetype,$this->id); //$this->getstarttimes($one);
            }
        }
    }

    function facetDay() {
        //$this->id = (string) $this->xml->
        foreach ($this->xml->days as $oneday) {
            $this->daytype = (string) $oneday['type'];
            foreach ($this->xml->days->course as $onecourse) {
                $this->course[] = new Booking($onecourse, $this->daytype, $onecourse->start,  $this->id);
            }
        }
    }

    function facetTime(){
        foreach ($this->xml->times as $onetime){
            $this->daytype = (string) $onetime->daysd['type'];
            foreach ($this->xml->times->days->course as $onecourse) {
                $this->bytime[] = new Booking($onecourse, $this->daytype, $onecourse->start,  $this->id);
            }
        }
    }
    function getStarttime() {
        return $this->starttimes;
    }
    function getCourse(){
        return $this->course;
    }
    function getCourseType() {
        return $this->coursetype;
    }

    function getByTime(){
        return $this->bytime;
    }
    function getCourseId() {
        return $this->id;
    }

    function getStarttimeType() {
        return $this->starttimetype;
    }

    function getDayType() {
        return $this->daytype;
    }

}

class Booking {

    public function __construct($element, $currentday, $starttime,$id) {
        
        $this->id = (isset($element->id)) ? $element->id : $id; 
        $this->day = (string) $currentday;
        $this->name = (string) $element->name;
        $this->crn = (string) $element->crn;
        $this->type = (string) $element->type;
        $this->building = (string) $element->building;
        $this->room = (string) $element->room;
        $this->start = (isset($element->start)) ? $element->start : $starttime; //(string) $starttime;
        $this->end = (string) $element->end;
        $this->instructor = (string) $element->instructor;
    }
    

}
