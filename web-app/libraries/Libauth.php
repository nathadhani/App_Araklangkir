<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Libauth {

    public $expired = 3600; // 1 hour

    function __construct() {
        $this->load->library('encryption');
    }

    function __get($var) {
        $get = (isset($this->$var) ? $this->$var : get_instance()->$var);
        return $get;
    }

    function login($user, $pass) {
        $pass_input = $pass;
        $pass = $this->encryption->encrypt($pass);
        $ses = $this->_login($user, $pass);
        if (is_array($ses)) {
            $ses['expired'] = time() + $this->expired;
            $this->session->set_userdata('auth', $ses);
            $sess['sta'] = true;
            $sess['msg'] = 'OK';
            return $sess;            
        } else {
            $sess['sta'] = false;
            $sess['msg'] = $ses;
            return $sess;
        }
    }

    function _login($user, $pass) {
        $this->db->where('username', $user)->limit(1);
        $get = $this->db->get('users')->row_array();
        if (!isset($get['password'])) {
            return 'U';  // Wrong User
        }
        if ( $this->encryption->decrypt($get['password']) == $this->encryption->decrypt($pass)) {        
            if ($get['status'] == 0) {
                return 'S'; // Status Not Active
            }
            return $get; // VALID USER !!
        } else {
            return 'P'; // Wrong Pass
        }
    }

    function getExpiredTime() {
        $session = $this->session->userdata('auth');
        return isset($session['expired']) ? $session['expired'] : 0;
    }

    function update_expire() {
        $session = $this->session->userdata('auth');
        $session['expired'] = time() + $this->expired;
        $this->session->set_userdata('auth', $session);
    }

}
