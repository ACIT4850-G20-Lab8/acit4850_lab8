<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Application {

    function __construct() {
        parent::__construct();
        $this->load->model('timetable');
    }

    public function index() {

        //quotes implementation
        $this->load->model('quotes');

        $timeitem = $this->input->post('selectedtime');
        $dayitem = $this->input->post('selectedday');
        $this->session->userdata['timeinput'] = $timeitem;
        $this->session->userdata['dayinput'] = $dayitem;
        $this->data['sessionTime'] = $this->session->userdata['timeinput'];
        $this->data['sessionDay'] = $this->session->userdata['dayinput'];
        $this->getTimeValue();
        $this->getDayValue();
        
        // Build a list of orders
        $this->load->helper('directory');
        $candidates = directory_map(DATAPATH);
        sort($candidates);
        foreach ($candidates as $file) {
            if (substr_compare($file, XMLSUFFIX, strlen($file) - strlen(XMLSUFFIX), strlen(XMLSUFFIX)) === 0)
            // exclude our menu
                if ($file != 'menu.xml')
                // trim the suffix
                    $orders[] = array('filename' => substr($file, 0, -4));
        }
        $this->data['orders'] = $orders;
        $this->data['pagebody'] = 'homepage';
        $this->render();
    }

    function getTimeValue() {
        $this->load->model('quotes');
        $source = $this->quotes->getTime();

        $times = array();
        foreach ($source as $record) {
            $times[] = array('time' => $record['time'],
                  'selected' => (($record['time'] == $this->session->userdata['timeinput']) ?"selected":""
                    )
            );
        }
        $this->data['times'] = $times;
    }
    
     function getDayValue() {
        $this->load->model('quotes');
        $source = $this->quotes->getDay();

        $days = array();
        foreach ($source as $record) {
            $days[] = array('day' => $record['day'],
                  'selected' => (($record['day'] == $this->session->userdata['dayinput']) ?"selected":""
                    )
            );
        }
        $this->data['days'] = $days;
    }
    

    function order($filename) {

        // Build a receipt for the chosen order
        $table = new Timetable($filename);

        $this->data['filename'] = $filename;
        // $this->data['customer'] = $order->getCustomer();
        // $this->data['ordertype'] = $order->getType();
        // handle the burgers in an order
        // $count = 1;
        // $this->bigbucks = 0.0;

        $details = '';
        if (strcmp($this->data['filename'], 'course') == 0) {
            foreach ($table->getStarttime() as $element)
                $details .= $this->courseStart($element);
        } else if (strcmp($this->data['filename'], 'day') == 0) {
            foreach ($table->getCourse() as $element)
                $details .= $this->courseStart($element);
        } else if (strcmp($this->data['filename'], 'time') == 0) {
            foreach ($table->getByTime() as $element)
                $details .= $this->courseStart($element);
        }
        $this->data['courseid'] = $table->getCourseId();
        $this->data['coursetype'] = $table->getCourseType();
        $this->data['daytype'] = $table->getDayType();
        $this->data['starttimetype'] = $table->getStarttimeType();
        $this->data['details'] = $details;
        $this->data['pagebody'] = 'dayofcourse';

        //$this->data['timeselection'] = 
        $timeitem = $this->input->post('selectedtime');
        $this->session->userdata['time'] = $timeitem;
        $this->data['sessionTime'] = $this->session->userdata['time'];
        //$this->getTime();

        $this->render();
    }

    function courseStart($starttime) {
        $parms['id'] = (isset($starttime->id)) ? $starttime->id : '';
        $parms['day'] = (isset($starttime->day)) ? $starttime->day : '';
        $parms['name'] = (isset($starttime->name)) ? $starttime->name : '';
        $parms['crn'] = (isset($starttime->crn)) ? $starttime->crn : '';
        $parms['type'] = (isset($starttime->type)) ? $starttime->type : '';
        $parms['building'] = (isset($starttime->building)) ? $starttime->building : '';
        $parms['room'] = (isset($starttime->room)) ? $starttime->room : '';
        $parms['start'] = (isset($starttime->start)) ? $starttime->start : '';
        $parms['end'] = (isset($starttime->end)) ? $starttime->end : '';
        $parms['instructor'] = (isset($starttime->instructor)) ? $starttime->instructor : '';

        return $this->parser->parse('astarttime', $parms, true);
    }

}
