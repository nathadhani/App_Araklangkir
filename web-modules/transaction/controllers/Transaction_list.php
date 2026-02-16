<?php

class Transaction_list extends App_Controller {

    function __construct() {
        $config = array('modules' => 'transaction', 'jsfiles' => array('transaction_list'));
        parent::__construct($config);        
        $this->auth = $this->session->userdata( 'auth' );        
    }
    
    function index() {
        $this->template->title('Transaction List');
        $this->template->build('transaction/transaction_list_v');
    }

    function dbquery($tr_date){
        return $this->db->query("SELECT
                                tr_header.id AS id,
                                tr_header.tr_id AS tr_id,
                                tr_header.tr_date AS tr_date,
                                tr_header.tr_number AS tr_number,
                                (
                                    SELECT sum(tr_detail.qty * tr_detail.price) 
                                    FROM tr_detail 
                                    WHERE tr_detail.header_id = tr_header.id                                    
                                ) AS total,
                                tr_header.description AS description,
                                tr_header.status AS status,
                                tr_header.created AS created,
                                tr_header.updated AS updated,
                                tr_header.createdby AS createdby,
                                tr_header.updatedby AS updatedby,
                                (
                                    SELECT
                                    (
                                        CASE
                                            
                                            WHEN ( tr_header.tr_id = 1 ) THEN
                                            'IN'
                                            WHEN ( tr_header.tr_id = 2 ) THEN
                                            'OUT'
                                        END 
                                    )
                                ) AS tr_name,
                                usr1.fullname AS createdby_name,
                                usr2.fullname AS updatedby_name,
                                (
                                    SELECT
                                    (
                                        CASE
                                            WHEN ( tr_header.status = 1 ) THEN
                                            'Task' 
                                            WHEN ( tr_header.status = 2 ) THEN
                                            'Canceled' 
                                            WHEN ( tr_header.status = 3 ) THEN
                                            'Confirm' 
                                        END 
                                    )
                                ) AS status_name
                            FROM tr_header
                            LEFT JOIN users usr1 ON tr_header.createdby = usr1.id						
                            LEFT JOIN users usr2 ON tr_header.updatedby = usr2.id 
                            WHERE tr_header.tr_date = '$tr_date'
                            ORDER BY tr_header.tr_id ASC, tr_header.tr_number ASC");        
    }
    
    function getdata() {
        checkIfNotAjax();
        $postData = $this->input->post();
        $tr_date = revDate($postData['tr_date']);
        echo json_encode($this->dbquery($tr_date)->result(), true);
    }    

}
