<?php

class Product extends App_Controller {

    function __construct() {
        $config = array('modules' => 'product', 'jsfiles' => array('product'));
        parent::__construct($config);
        $this->Appmdl->table = 'product';
    }

    function index() {
        $this->template->title('Product');
        $this->template->build('product/product_v');
    }

    function insert() {
        checkIfNotAjax();
        $postData = $this->input->post();
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
        $cpData = $this->db->query("SELECT * FROM Product")->result();
        echo json_encode($cpData, true);
    }

    function getproduct() {
        checkIfNotAjax();
        $this->Appmdl->searchable = array('product_code', 'product_name');
        $this->Appmdl->select2fields = array('id' => 'id', 'text' => "concat(product_code,' - ',product_name)");
        $result['results'] = $this->Appmdl->getSelect2(array('status' => '1'));
        $result['more'] = true;
        echo json_encode($result);
    }

}
