<?php

class Stock extends App_Controller {
    

    function __construct() 
    {
        $config = array('modules' => 'stock', 'jsfiles' => array('stock'));
        parent::__construct($config);        
        $this->auth = $this->session->userdata( 'auth' ); 
    }
    
    function index()
    {
        $this->template->title('Stock');
        $this->template->build('stock/stock_v');
    }

    function parameter_data($postData){        
        if(isset($postData['period'])){ 
            $this->tr_year = intval(SUBSTR($postData['period'],3,4));
            $this->tr_month = intval(SUBSTR($postData['period'],0,2));
        }
        if(isset($postData['tr_date'])){            
            $this->tr_year = intval(SUBSTR(revDate($postData['tr_date']),0,4));
            $this->tr_month = intval(SUBSTR(revDate($postData['tr_date']),5,2));
        }
        $this->tr_year0 = $this->tr_year;
        $this->tr_month0 = intval(SUBSTR($postData['period'],0,2)) - 1;
        if($this->tr_month == 1){
            $this->tr_year0 = $this->tr_year - 1;
            $this->tr_month0 = 12;
        }

        $this->product_id = $postData['product_id'];
    }
        
    function getsaldoawal() {
        checkIfNotAjax();
        $postData = $this->input->post();
        $this->parameter_data($postData);
        $string = "SELECT COALESCE(ending_stock,0) AS ending_stock
                        FROM stock                                        
                        WHERE stock.product_id = $this->product_id
                        AND stock.stock_year = $this->tr_year0
                        AND stock.stock_month = $this->tr_month0                    
                        LIMIT 1";
        $query = $this->db->query($string)->result();     
        echo json_encode($query, true);
    }

    function gettrx() {
        checkIfNotAjax();
        $postData = $this->input->post();
        $this->parameter_data($postData);        
        
        $query = $this->db->query("SELECT
                                    tr_header.tr_date,
                                    tr_header.tr_number,
                                    IF(tr_header.tr_id = 1, tr_detail.qty, 0) AS qty_in,
                                    IF(tr_header.tr_id = 2, tr_detail.qty, 0) AS qty_out,
                                    tr_header.description AS description,
                                    tr_detail.status
                                    FROM tr_detail
                                    JOIN tr_header ON tr_detail.header_id = tr_header.id
                                    WHERE tr_detail.product_id = $this->product_id
                                    AND YEAR(tr_header.tr_date) = $this->tr_year
                                    AND MONTH(tr_header.tr_date) = $this->tr_month
                                    ORDER by tr_header.tr_date,tr_header.tr_id,tr_detail.id ASC")->result();
        echo json_encode($query, true);
    }
}
