<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Appmdl extends App_Model {

    public $fillable = array();
    public $table = '';
    public $primary_key = 'id';
    public $searchable = array();
    public $select2fields = array('id' => 'id', 'text' => 'nama', 'icons' => 'icons');

    function __construct() {
        parent::__construct();
    }

    public function setTableName($tableName) {
        $this->table = $tableName;
    }

    public function setSearchable($search) {
        $this->searchable = $search;
    }

    function insert($data, $show_last_id = false) {
        $s = date('Y-m-d H:i:s', time());
        $data['created'] = $s;
        $auth = $this->session->userdata('auth');
        $data['createdby'] = $auth['id'];
        $result = $this->db->insert($this->table, $data);
        if (false == $result) {
            $err = $this->db->error();
            $result = $err['code'] . "<br>" . $err['message'];
        }
        return $result;
    }

    function update($dataUpdate, $where) {
        $s = date('Y-m-d H:i:s', time());
        $user_session = $this->session->userdata('auth');
        $dataUpdate['updated'] = $s;
        $dataUpdate['updatedby'] = $user_session['id'];
        $result = $this->db->update($this->table, $dataUpdate, $where);
        // echo $this->db->last_query(); exit;
        if (false == $result) {
            $err = $this->db->error();
            $result = $err['code'] . "<br>" . $err['message'];
        }
        return $result;
    }

    public function delete($col, $where) {
        $this->db->where_in($col, $where);
        $result = $this->db->delete($this->table);
        if (false == $result) {
            $err = $this->db->error();
            $result = $err['code'] . "<br>" . $err['message'];
        }
        return $result;
    }

    function getDatatable($where = array()) {
        $postData = $this->input->post();
        $index = 0;
        $select = array();
        $searchable = array();
        $orderable = array();
        foreach ($postData['columns'] as $key => $columns) {
            if (strlen($columns['data'])) {
                if ($columns['data'] == "#")
                    continue;
                $select[] = $columns['data'];
                if ($columns['orderable'] == "true") {
                    $orderable[$key] = $columns['data'];
                }
                if ($columns['searchable'] == "true") {
                    $searchable[$key]['column'] = $columns['data'];
                    $searchable[$key]['query'] = $columns['search']['value'];
                }
            }
        };
        $this->db->select('count(*) as nrow');
        $total = $this->db->get($this->table)->first_row();
        $this->db->start_cache();
        $arrOrLike = array();
        foreach ($searchable as $whatToSearch) {
            if (strlen($whatToSearch['query']) > 0) {
                $qu = $whatToSearch['column'] . " LIKE '%" . $whatToSearch['query'] . "%'";
                $this->db->where($qu);
            } else {
                $arrOrLike[] = $whatToSearch['column'] . " LIKE '%" . $postData['search']['value'] . "%'";
            }
        }
        if (!empty($arrOrLike)) {
            $this->db->where('(' . implode(' OR ', $arrOrLike) . ')');
        }
        //--- ADDITIONAL WHERE 
        if (!empty($where)) {
            foreach ($where as $k => $v) {
                $s = $v['sql'];
                $f = $v['field'];
                $d = $v['data'];
                $this->db->$s($f, $d);
            }
        };
        $this->db->stop_cache();
        $select = array_unique($select);
        $select = count($select) > 0 ? implode(',', $select) : '*';
        $this->db->select($select);
        if ($postData['length'] != -1) {
            $this->db->limit($postData['length'], $postData['start']);
        }
        foreach ($postData['order'] as $orderBy) {
            $this->db->order_by($orderable[$orderBy['column']], $orderBy['dir']);
        }
        $get = $this->db->get($this->table);
        $result = $get->result_array();
        $start = $postData['start']; // Penomeran
        $start++;
        foreach ($result as $key => $val) {
            $result[$key]['#'] = (string) $start;
            $start++;
        }
        $this->db->select('count(*) AS num_row');
        $totalFiltered = $this->db->get($this->table)->first_row();
        $this->db->flush_cache();
        return array('draw' => $postData['draw'],
            'data' => $result,
            'recordsFiltered' => $totalFiltered->num_row,
            'recordsTotal' => $total->nrow,
        );
    }

    function getSelect2($where = array()) {
        $postData = $this->input->post();
        $this->db->where($where, false);
        $this->db->select($this->select2fields['id'] . ' as id');
        $this->db->select($this->select2fields['text'] . ' as text', false);
        if (isset($postData['action']) && $postData['action'] == 'initSelection') {
            $getByID = $this->getById($postData['id']);
            $data = new stdClass();
            if (count($getByID) > 0) {
                $data->id = $postData['id'];
                $data->text = $getByID['text'];
            } else {
                $data->id = '';
                $data->text = '';
            }
            echo '[' . json_encode($data) . ']';
            exit;
        }
        $this->db->limit($postData['limit']);
        $last = end($this->searchable);
        if (isset($postData['q'])) {
            $sql = "(";
            foreach ($this->searchable as $field) {
                $sql .= $field . " LIKE '%" . $postData['q'] . "%'";
                if ($field != $last) {
                    $sql .= " OR ";
                }
            };
            $sql .= ")";
            if ($sql != '()') {
                $this->db->where($sql);
            }
        }
        $get = $this->db->get($this->table);
        return $get->result_array();
    }

    function getSelect2bit($select, $search, $where = array(), $addwhere = array()) {
        $arrsearch = array();
        $postData = $this->input->post();
        $this->db->select($select);
        $last = end($search);
        $sql = "(";
        foreach ($search as $val) {
            $sql .= $val . " LIKE '%" . $postData['q'] . "%'";
            if ($val != $last) {
                $sql .= " OR ";
            }
        };
        $sql .= ")";
        $this->db->where($sql);
        $this->db->limit($postData['limit']);
        if (isset($postData['action']) && $postData['action'] == 'initSelection') {
            $getByID = $this->getById($postData['id']);
            $data = new stdClass();
            if (count($getByID) > 0) {
                $data->id = $postData['id'];
                $data->text = $getByID['text'];
            } else {
                $data->id = '';
                $data->text = '';
            }
            echo '[' . json_encode($data) . ']';
            exit;
        }
        //--- ADDITIONAL WHERE 
        if (!empty($addwhere)) {
            foreach ($addwhere as $k => $v) {
                $sql = $v['sql'];
                $field = $v['field'];
                $data = $v['data'];
                $this->db->$sql($field, $data);
            }
        };
        $hsl = $this->db->get_where($this->table, $where);
        return $hsl->result_array();
    }

    function getById($id) {
        $this->db->where($this->primary_key, $id);
        $get = $this->db->get($this->table);
        return $get->row_array();
    }

    function outputToJson($DataOutput, $stringOrArray = 'array') {
        $this->output->set_content_type('application/json');
        $DataOutput = ( $stringOrArray == 'array' ) ? json_encode($DataOutput) : $DataOutput;
        $this->output->set_output($DataOutput);
    }

    function generate_stock($tahun, $bulan, $product_id)
    {
        $auth = $this->session->userdata('auth');
        $tahun = $tahun;
        $bulan = $bulan;

        $tahun1 = $tahun;
        $bulan1 = $bulan - 1;
        if($bulan == 1){
            $tahun1 = $tahun - 1;
            $bulan1 = 12;
        }

        $tahun2 = $tahun;
        $bulan2 = $bulan + 1;
        if($bulan == 12){
            $tahun2 = $tahun + 1;
            $bulan2 = 1;
        }
        $tgl1 = $tahun.'-'.sprintf("%02d", $bulan).'-01';

        // insert table stock
        /******************************************************************************************************/
        $this->db->trans_begin();
        $qinsert = $this->db->query("SELECT product_id FROM stock
                                    WHERE stock_year = $tahun
                                    AND stock_month = $bulan
                                    AND product_id = $product_id")->result();
        // echo $this->db->last_query();exit;
        if(count($qinsert) < 1){
            $data = array(                        
                        'stock_year' => $tahun,
                        'stock_month' => $bulan,
                        'product_id' => $product_id,
                        'ending_stock' => 0,
                        'created' => date('Y-m-d H:i:s', time()),
                        'createdby' => $auth['id']
                    );
            $this->db->insert('stock', $data);
            // echo $this->db->last_query();exit;
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $err = $this->db->error();
            echo '<br> Insert table stock : ' . $product_id . ' | ' . $err['code'] . ' ' . $err['message'];
        } else {
            $this->db->trans_commit();
        }

        // update saldo awal table stock
        /******************************************************************************************************/
        $this->db->trans_begin();
        $qlast_stock = $this->db->query("SELECT COALESCE(ending_stock,0) AS ending_stock
                                    FROM stock
                                    WHERE stock_year = $tahun1
                                    AND stock_month = $bulan1
                                    AND product_id = $product_id")->result();
        // echo $this->db->last_query();exit;        
        $ending_stock = $qlast_stock[0]->ending_stock;  
        
        // harga rata2 pembelian bulan berjalan
        /**********************************************************************************************************************************/
        $tanggal_awal = date($tgl1,time());
        $tanggal_akhir = date('Y-m-t',mktime(0, 0, 0, $bulan + 1, 0, $tahun));
        $maxday = date('d', strtotime($tanggal_akhir));
        // var_dump($tanggal_awal . ' s/d ' .$tanggal_akhir . ' maxday ' . $maxday);exit;
        for ($tgl = 1; $tgl <= $maxday; $tgl++) {
            $tr_date = $tahun.'-'.sprintf("%02d", $bulan).'-'. sprintf("%02d", $tgl);
            $tr_date_before = date('Y-m-d', strtotime('-1 days', strtotime( $tr_date )));
            
            echo $tr_date . ' : ' . $ending_stock. '<br>'; 

            /** Get Transaction IN/OUT*/
            /***************************************************************************************************************** */
            $qty_in = 0;
            $qty_out = 0;
            $gettrx = $this->db->select('tr_header.tr_date,                                                  
                                            tr_detail.product_id,
                                            SUM(IF((tr_header.status = 3 AND ( tr_header.tr_id = 1 )),(tr_detail.qty),0)) AS qty_in,
                                            SUM(IF((tr_header.status = 3 AND ( tr_header.tr_id = 2 )),(tr_detail.qty),0)) AS qty_out')
                        ->from('tr_detail')
                        ->join('tr_header', 'tr_header.id=tr_detail.header_id', 'left')
                        ->where(array('tr_header.tr_date' => $tr_date, 'tr_detail.product_id' => $product_id))
                        ->where_in('tr_detail.status', ['3'])
                        ->group_by('tr_header.tr_date, tr_detail.product_id')->get();
            if($gettrx->num_rows()){
                foreach($gettrx->result_array() as $row) {                    
                    $qty_in = intval($row['qty_in']); 
                    $qty_out = intval($row['qty_out']); 
                    $ending_stock = intval(ROUND(($ending_stock + $qty_in - $qty_out),0));                            
                }                
            }        
        }

        $data = array(
                    'ending_stock' => $ending_stock,
                    'updated' => date('Y-m-d H:i:s', time()),
                    'updatedby' => $auth['id']
                );            
        $where = array(
                'stock_year' => $tahun,
                'stock_month' => $bulan,
                'product_id' => $product_id
        );
        $this->db->update('stock', $data, $where);
        // echo $this->db->last_query();exit;        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $err = $this->db->error();
            echo '<br> Update tabel stock : ' . $product_id . ' | ' . $err['code'] . ' ' . $err['message'];
        } else {
            $this->db->trans_commit();           
        }

        // delete stok kosong
        /******************************************************************************************************/
        $this->db->trans_begin();
        $this->db->query("DELETE FROM stock WHERE product_id = $product_id AND (ending_stock = 0 OR ending_stock IS NULL) ");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $err = $this->db->error();
            echo '<br> Delete Stok Kosong : ' . $product_id . ' | ' . $err['code'] . ' ' . $err['message'];
        } else {
            $this->db->trans_commit();
        }
    }

}
