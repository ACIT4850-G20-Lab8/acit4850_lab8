<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Quotes
 *
 * @author w7-Will_lap
 /**using quotes to set up search terms for query due to lack of db */
class Quotes extends CI_Model {

    var $timedata = array(
                            array('time' => '1032') ,
                            array('time' => '1230')
                        );
    var $daydata = array(
                            array('day' => 'Monday'),
                            array('day' => 'Tuesday'),
                            array('day' => 'Wednesday'),
                            array('day' => 'Thursday'),
                            array('day' => 'Friday')
            );
    
    public function __construct() {
        
        parent::__construct();
    }
    
public function getTime(){
        return $this->timedata;
    }
    
    public function getDay(){
        return $this->daydata;
    }
    
    
    
    
 
}
