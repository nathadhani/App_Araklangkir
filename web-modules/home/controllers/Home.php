<?php

class Home extends App_Controller {

    function __construct() {
        $config = array('modules' => 'home', 'jsfiles' => array('c3/d3.v3.min', 'c3/c3', 'home'), 'cssfiles' => array('c3/c3.min'));
        parent::__construct($config);
        $this->auth = $this->session->userdata('auth');
        $this->userid = $this->auth['id'];
    }

    Public function index() {
        $user_id =  $this->auth['id'];
        $this->template->title('Dashboard');
        $this->template->build('home/home_v');
    }

    function getUser() {
        checkIfNotAjax();       
        $this->Appmdl->table = 'users';
        $select = 'id,fullname';
        $searchfield = array('fullname');
        $where = array('status' => '1', 'id!=' => '1');
        
        $addwhere[0]['field'] = 'id';
        $addwhere[0]['data'] = $userid;
        $addwhere[0]['sql'] = 'where';
        
        $result['results'] = $this->Appmdl->getSelect2bit($select, $searchfield, $where, $addwhere);
        $result['more'] = true;
        echo json_encode($result);
    }
}
