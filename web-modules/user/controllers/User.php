<?php

class User extends App_Controller {

    function __construct() {
        $config = array('modules' => 'user', 'jsfiles' => array('user'));
        parent::__construct($config);
        $this->Appmdl->table = 'users';
        $this->auth = $this->session->userdata( 'auth' );
    }

    function index() {
        $this->template->title('User Account');
        $this->template->build('user/user_v');
    }

    function insert() {
        checkIfNotAjax();
        $postData = $this->input->post();
        $postData['username'] = trim(trim(strtolower($postData['username'])));
        $postData['password'] = $this->encryption->encrypt($postData['password']);
        $postData['fullname'] = trim($postData['fullname']);
        $postData['status'] = cekStatus($postData);
        $status = $this->Appmdl->insert($postData);
        if ($status == 'true') {
            $json['msg'] = '1';
            echo json_encode($json);
        } else {
            $json['msg'] = $status;
            echo json_encode($json);
        }        
    }

    function update() {
        checkIfNotAjax();
        $postData = $this->input->post();
        $postData['username'] = strtolower($postData['username']);
        $postData['fullname'] = trim($postData['fullname']);
        if (strlen($postData['password']) > 0) {
            $postData['password'] = $this->encrypt->hash($postData['password']);
        } else {
            unset($postData['password']);
        }
        $postData['status'] = cekStatus($postData);
        $id = $postData['id'];
        unset($postData['id']);
        $status = $this->Appmdl->update($postData, 'id=' . $id);
        if ($status == 'true') {
            $json['msg'] = '1';
            echo json_encode($json);
        } else {
            $json['msg'] = $status;
            echo json_encode($json);
        }
    }

    function delete() {
        checkIfNotAjax();
        $postData = $this->input->post();
        $id = json_decode($postData['id']);
        $status = $this->Appmdl->delete('id', $id);
        if ($status == 'true') {
            $json['msg'] = '1';
            echo json_encode($json);
        } else {
            $json['msg'] = $status;
            echo json_encode($json);
        }
    }

    function getData() {
        checkIfNotAjax();
        $cpData = $this->db->query("SELECT * FROM users")->result();
        echo json_encode($cpData, true);
    }

    function getDataUser() {
        checkIfNotAjax();
        $select = 'id,username,fullname';
        $searchfield = array('username','fullname');
        $where = array('status' => '1');
        $addwhere = array();
        $result['results'] = $this->Appmdl->getSelect2bit($select, $searchfield, $where, $addwhere);
        $result['more'] = true;
        echo json_encode($result);
    }
}
