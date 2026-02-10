<?php

class App_Controller extends MX_Controller {

    function __construct($config = array()) {
        $def_config = array(
            'modules' => '',
            'jsfiles' => array(),
            'cssfiles' => array(),
            'ignore_login' => false
        );
        date_default_timezone_set('Asia/Jakarta');
        $opt = array_merge($def_config, $config);
        parent::__construct();
        $expiredLogin = $this->libauth->getExpiredTime();
        $sess = $this->session->userdata('auth');
        
        if (empty($sess) || (time() > $expiredLogin)) {
            if ($opt['ignore_login'] == false) {
                $next = $this->uri->uri_string();
                $next = urlencode($next);
                $next = strlen($next) > 0 ? '?n=' . $next : '';
                redirect(base_url('auth/' . $next), 'refresh');
            } elseif ($opt['ignore_login'] === 'ajax') {
                echo json_encode(array('success' => false, 'message' => 'Login Time Expired'));
                die();
            }
        }
        $this->libauth->update_expire();
        $this->template->set_partial('header', 'parts/header', array('modules' => $opt['modules'], 'cssfiles' => $opt['cssfiles']));
        $this->template->set_partial('sidebar', 'parts/sidebar');
        $this->template->set_partial('footer', 'parts/footer', array('modules' => $opt['modules'], 'jsfiles' => $opt['jsfiles']));
        $this->template->title('Application');
    }

}
