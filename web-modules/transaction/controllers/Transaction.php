<?php

class Transaction extends App_Controller {
    
    function __construct() {
        $config = array('modules' => 'transaction', 'jsfiles' => array('transaction'));
        parent::__construct($config);
        $this->auth = $this->session->userdata( 'auth' );
        $this->userId = $this->auth['id'];
    }
    
    function index(){
        $this->template->title('New');
        $data['auth'] = $this->auth;
        $this->template->build('transaction/transaction_v', $data);
    }

    function generate_nomor($tr_id, $tr_date) {
        $Number = 0;
        $thn = SUBSTR($tr_date,0,4);
        $bln = SUBSTR($tr_date,5,2);
        $day = SUBSTR($tr_date,8,2);
        $trcode = sprintf("%02d", $tr_id);
        $sql = $this->db->query("SELECT max(right(tr_number,4)) AS tr_number
                                 FROM tr_header
                                 WHERE tr_id = $tr_id
                                 AND tr_date = '$tr_date'
                                 ")->result();
        if (count($sql) > 0) {
            $Number = intval($sql[0]->tr_number) + 1;
        }
        return SUBSTR($thn,2,2) . $bln . $day . $trcode . sprintf("%04d", $Number);
    }
    
    function insert(){
        checkIfNotAjax();        
        /** Insert Header */
        /** -------------------------------------------------------------------------------- */
        $postData = $this->input->post();      
        if( isset($postData['header_id']) ){
            if(($postData['header_id'] == 'null' || $postData['header_id'] == '')) {            
                unset($postData['header_id']);
                unset($postData['product_id']);
                unset($postData['qty']);
                unset($postData['price']);
                if(isset($postData['tr_date'])){
                    $postData['tr_date'] = revDate($postData['tr_date']);
                    /**----------------------------------------------------- */
                    $datetime = date('Y-m-d H:i:s');
                    $new_date = $postData['tr_date'];
                    $new_datetime = date('Y-m-d H:i:s', strtotime($new_date . ' ' . date('H:i:s', strtotime($datetime))));
                    $postData['created'] = $new_datetime;
                } else {
                    $postData['tr_date'] = Date('Y-m-d');
                }
                        
                $postData['status'] = '1';
                
                $this->db->trans_begin();
                $this->Appmdl->table = 'tr_header';
                $response = $this->Appmdl->insert($postData);
                $id_header = $this->db->insert_id();                
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $err = $this->db->error();
                    $json['msg'] = $err['code'] . '<br>' . $err['message'];
                    echo json_encode($json);
                } else {
                    $this->db->trans_commit();
                }
            } else {
                $id_header = (int) $postData['header_id'];
                unset($postData['header_id']);
                unset($postData['product_id']);
                unset($postData['qty']);
                unset($postData['price']);
                if(isset($postData['tr_date'])){
                    $postData['tr_date'] = revDate($postData['tr_date']);
                    /**----------------------------------------------------- */
                    $datetime = date('Y-m-d H:i:s');
                    $new_date = $postData['tr_date'];
                    $new_datetime = date('Y-m-d H:i:s', strtotime($new_date . ' ' . date('H:i:s', strtotime($datetime))));
                    $postData['created'] = $new_datetime;
                } else {
                    $postData['tr_date'] = Date('Y-m-d');
                }
                $postData['description'] = ucwords(strtolower(trim($postData['description'])));
                $this->db->trans_begin();
                $this->Appmdl->table = 'tr_header';
                $response = $this->Appmdl->update($postData, 'id=' . $id);
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $err = $this->db->error();
                    $json['msg'] = $err['code'] . '<br>' . $err['message'];
                    echo json_encode($json);
                } else {
                    $this->db->trans_commit();
                    $json['msg'] = '1';
                    echo json_encode($json);
                }
            }
        }        
        /** End of Inser Header -------------------------------------------------------------------------------- */

        /** Insert Detail */
        /** -------------------------------------------------------------------------------- */
        $postDetail = $this->input->post();
        if( isset($id_header) && $id_header > 0 && ( $id_header != 'null' || $id_header !== '') ){
            $this->db->trans_begin();

            unset($postDetail['tr_id']);
            unset($postDetail['tr_date']);
            unset($postDetail['description']);

            $postDetail['header_id'] = $id_header;
            if (strpos($postDetail['price'], ',') !== false) {
                $postDetail['price'] = str_replace(',','.',$postDetail['price']);
            }        
            $postDetail['status'] = '1';          

            $this->Appmdl->table = 'tr_detail';
            $product_id = $postDetail['product_id'];
            $qty = $postDetail['qty'];
            $price = $postDetail['price'];
            $qstr = "SELECT id, header_id, product_id, qty
                                          FROM tr_detail
                                          WHERE header_id = $id_header 
                                          AND product_id = $product_id
                                          AND qty = $qty
                                          AND price = $price";
            $cek_detail = $this->db->query($qstr)->result();
            if( empty($cek_detail)){
                $response = $this->Appmdl->insert($postDetail);
            } else {
                if($cek_detail[0]->id != null){
                    $id = $cek_detail[0]->id;
                    $postDetailupdate['qty'] = $postDetail['qty'];
                    $response = $this->Appmdl->update($postDetailupdate, 'id=' . $id);
                }
            }            
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $err = $this->db->error();
                $json['msg'] = $err['code'] . '<br>' . $err['message'];
                echo json_encode($json);
            } else {
                $this->db->trans_commit();
                $json['msg'] = '1';
                $json['id_header'] = $id_header;
                echo json_encode($json);
            }
        }
        /** End of Inser Detail -------------------------------------------------------------------------------- */
    }
    
    function delete_detail(){
        checkIfNotAjax();
        $postData = $this->input->post();
        $id = json_decode($postData['id']);        
        $this->db->trans_begin();
        $this->Appmdl->table = 'tr_detail';
        $status = $this->Appmdl->delete('id', $id);        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $err = $this->db->error();
            $json['msg'] = $err['code'] . '<br>' . $err['message'];
            echo json_encode($json);
        } else {
            $this->db->trans_commit();
            $json['msg'] = '1';
            echo json_encode($json);
        }
    }

    function dbquery_tr_header($id){
        return $this->db->query("SELECT
                                    tr_header.id AS id,
                                    tr_header.tr_id AS tr_id,
                                    tr_header.tr_date AS tr_date,
                                    tr_header.tr_number AS tr_number,
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
                                    ) AS status_name,
                                    (
                                        SELECT COALESCE(sum(tr_detail.qty * tr_detail.price),0) FROM tr_detail WHERE tr_detail.header_id = tr_header.id
                                    ) AS total

                                FROM tr_header
                                LEFT JOIN users usr1 ON tr_header.createdby = usr1.id						
                                LEFT JOIN users usr2 ON tr_header.updatedby = usr2.id	
                                WHERE tr_header.id = $id
                                ORDER BY tr_header.tr_id ASC, tr_header.tr_number ASC LIMIT 1");
    }
    
    function getheader(){
        checkIfNotAjax();
        $postData = $this->input->post();
        $id = json_decode($postData['id']);
        $query = $this->dbquery_tr_header($id)->result();
        // echo $this->db->last_query();exit;
        echo json_encode($query, true);        
    }
    
    function getdetail(){
        checkIfNotAjax();
        $postData = $this->input->post();
        $header_id = json_decode($postData['header_id']);
        $query = $this->db->query("SELECT tr_detail.id,
                                        product.product_code,
                                        product.product_name,
                                        product.uom,
                                        tr_detail.qty,
                                        tr_detail.price,
                                        (tr_detail.qty * tr_detail.price) AS subtotal
                                   FROM tr_detail 
                                   JOIN product ON tr_detail.product_id = product.id
                                   WHERE tr_detail.header_id= " . $header_id ." 
                                   ORDER BY tr_detail.product_id, tr_detail.price ASC")->result();

        // echo $this->db->last_query();exit;
        echo json_encode($query, true);                
    }

    function getstockbyid(){
        checkIfNotAjax();
        $postData = $this->input->post();
        $tahun    = (int) Date('Y');
        $bulan    = (int) Date('m');
        if(isset($postData['period'])){
            $tahun = intval(SUBSTR(revDate($postData['period']),0,4));
            $bulan = intval(SUBSTR(revDate($postData['period']),5,2));
        }
        $tahun1 = $tahun;
        $bulan1 = $bulan - 1;
        if($bulan == 1){
            $tahun1 = $tahun - 1;
            $bulan1 = 12;
        }
        $product_id = $postData['product_id'];
        $query = $this->db->query("SELECT 
                                   COALESCE((
                                        (
                                          COALESCE((
                                                SELECT x.ending_stock
                                                FROM stock x
                                                WHERE x.stock_year = $tahun1
                                                AND x.stock_month = $bulan1
                                                AND x.product_id = $product_id
                                                limit 1
                                            ),0)  
                                        )
                                        +
                                        (
                                            COALESCE((
                                                SELECT SUM(IF((x.status NOT IN (2) AND ( y.tr_id = 1 )),(x.qty),0)) AS qty_in
                                                FROM tr_detail AS x
                                                JOIN tr_header AS y ON x.header_id = y.id
                                                WHERE YEAR(y.tr_date) = $tahun
                                                AND MONTH(y.tr_date) = $bulan
                                                AND x.product_id = $product_id
                                                LIMIT 1
                                            ),0)                                              
                                        )
                                        -
                                        (
                                            COALESCE((
                                                SELECT SUM(IF((x.status NOT IN (2) AND ( y.tr_id = 2 )),(x.qty),0)) AS qty_out
                                                FROM tr_detail AS x
                                                JOIN tr_header AS y ON x.header_id = y.id
                                                WHERE YEAR(y.tr_date) = $tahun
                                                AND MONTH(y.tr_date) = $bulan
                                                AND x.product_id = $product_id
                                                LIMIT 1
                                            ),0)                                              
                                        )
                                        -
                                        (
                                            COALESCE((
                                                SELECT SUM(IF((x.status NOT IN (1) AND ( y.tr_id = 2 )),(x.qty),0)) AS qty_alocation
                                                FROM tr_detail AS x
                                                JOIN tr_header AS y ON x.header_id = y.id
                                                WHERE YEAR(y.tr_date) = $tahun
                                                AND MONTH(y.tr_date) = $bulan
                                                AND x.product_id = $product_id
                                                LIMIT 1
                                            ),0)                                              
                                        )
                                   ),0) AS ending_stock
                                   FROM stock
                                   WHERE stock.stock_year = $tahun
                                   AND stock.stock_month = $bulan
                                   AND stock.product_id = $product_id
                                   LIMIT 1")->result();
        // echo $this->db->last_query();exit;
        echo json_encode($query, true);
    }    
        
    function confirm_task() {
        checkIfNotAjax();
        $postData = $this->input->post();
        $header_id = $postData['id'];
        $tr_id = $postData['tr_id'];
        $description = $postData['description'];
        $this->db->trans_begin();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $err = $this->db->error();
            $json['msg'] = $err['code'] . '<br>' . $err['message'];
            echo json_encode($json);
        } else {
            $get_header = $this->db->query("SELECT id, tr_id, tr_date, tr_number FROM tr_header WHERE id = $header_id")->result();
            if(count($get_header > 0)){
                $tr_date = $get_header[0]->tr_date;
                $tr_number = ($get_header[0]->tr_number !== '' &&  $get_header[0]->tr_number !== null ? $get_header[0]->tr_number : $this->generate_nomor($tr_id, $tr_date));

                $this->db->where(array('id' => $header_id));
                $this->db->update('tr_header', array('tr_id' => $tr_id,'tr_number' => $tr_number, 'status' => 3, 'description' => $description, 'updated' => date('Y-m-d H:i:s', time()), 'updatedby' => $this->userId) );

                $this->db->where(array('header_id' => $header_id));
                $this->db->update('tr_detail', array('status' => 3, 'updated' => date('Y-m-d H:i:s', time()), 'updatedby' => $this->userId) );

                $select = $this->db->select('product_id')->where('header_id', $header_id)->get('tr_detail');
                if($select->num_rows()){                           
                    $tahun = (int) SUBSTR($get_header[0]->tr_date,0,4);
                    $bulan = (int) SUBSTR($get_header[0]->tr_date,5,2);
                    foreach($select->result_array() as $row) {
                        $product_id = $row['product_id'];
                        $this->Appmdl->generate_stock($tahun, $bulan, $product_id);
                    }
                }
                $this->db->trans_commit();
                $json['msg'] = '1';
                echo json_encode($json);
            }            
        }
    }

    function cancel_trx(){
        checkIfNotAjax();
        $postData = $this->input->post();
        $header_id = json_decode($postData['id']);
        $this->db->trans_begin();
        $this->db->where(array('id' => $header_id));
        $this->db->update('tr_header', array('status' => 2, 'updated' => date('Y-m-d H:i:s', time()), 'updatedby' => $this->userId) );
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $err = $this->db->error();
            $json['msg'] = $err['code'] . '<br>' . $err['message'];
            echo json_encode($json);
        } else {
            $this->db->where(array('header_id' => $header_id));
            $this->db->update('tr_detail', array('status' => 2, 'updated' => date('Y-m-d H:i:s', time()), 'updatedby' => $this->userId) );

            $this->db->trans_commit();
            $json['msg'] = '1';
            echo json_encode($json);
        }            
    }

}
