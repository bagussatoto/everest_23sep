<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Customers extends REST_Controller
{
    private $model;

    private $validationRules=array(
        "nama",
        "nama_login",
        "email",

    );

    private $masterInits=array();


    function __construct($config = 'rest')
    {

        parent::__construct($config);

//        $this->model = "Mdl".ucfirst($this->uri->segment(4));
        $this->model = "MdlCustomer";

        $this->load->database();

        $this->load->model("Mdls/".$this->model);
        $this->masterInits = array(
            "npwp" => "------",
            "due_days"=>"0000-00-00",
        );
    }





    function lookupByID_get()
    {

        $id=$this->uri->segment(4);
        $mdlName = $this->model;
        $o = new $mdlName();
        $tmp = $o->lookupByID($id)->result();
        
        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }


    function lookupByName_get()
    {

        $id=$this->uri->segment(4);
        $mdlName = $this->model;
        $o = new $mdlName();
        $o->addFilter("nama_login='$id'");
        $tmp = $o->lookupAll($id)->result();

//        die($this->db->last_query());
        $result = 0;
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->id;
            }
        }
        $this->response($result, 200);
    }




    function addItem_post()
    {
        $mdlName = $this->model;
        $o = new $mdlName();

        $tmpInput=$_POST;
        foreach ($this->validationRules as $key) {
            if (!isset($tmpInput[$key]) || strlen($tmpInput[$key]) < 1) {
                die("we did not receive an input post variable named <b>" . $key . "</b><br>please make sure you set it before re-making this request ");
            }
        }


        foreach($this->masterInits as $key=>$src){
            $tmpInput[$key]=$src;
        }


        foreach ($o->getFields() as $fName => $fSpec) {

            $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $data[$realFieldName] = isset($tmpInput[$fName])?$tmpInput[$fName]:"";
//            echo "iterating $fName tobe $realFieldName\n";
        }
        foreach($_POST as $key=>$val){
            $data[$key]=$val;
        }

//        print_r($data);

        $this->db->trans_start();
        $insert = $o->addData($data);
        $this->db->trans_complete();
//        cekmerah($this->db->last_query());
        if ($insert>0) {
//            $this->response($data, 200);
            $this->response($insert, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function editItem_put()
    {
        $id = $this->uri->segment(5);
        $mdlName = $this->model;
        $o = new $mdlName();
        foreach ($o->getFields() as $fName => $fSpec) {
            $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $data[$realFieldName] = $this->put($fName);
            $dbFname[$fName] = $fName;
        }
        // $this->db->where('id', $id);

        // $update = $this->db->update('telepon', $data);
        $update = $o->updateData(array("id" => $id), $data);
        //print_r($this->db->last_query());
        if ($update) {
            return $this->response($data, 200);
        } else {
            return $this->response(array('status' => 'fail', 'code' => 502, 'debug $Data' => $data, '$dbFname' => $dbFname, 'Debug put' => $this->put()));
        }
    }
    function editItem_get(){
        $this->response(array('status' => 'fail', 502));
    }


}

?>