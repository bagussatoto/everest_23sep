<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Data extends REST_Controller
{
    private $model;

    function __construct($config = 'rest')
    {

        parent::__construct($config);

        $this->model = "Mdl".ucfirst($this->uri->segment(4));
//        $this->model = "Mdl".ucfirst($this->uri->segment(3));

        $this->load->database();

        $this->load->model("Mdls/".$this->model);
//        die("constructor");
    }




    //Menampilkan data $mdlName
//    function lookup_get()
//    {
//
//        $id = $this->get('id');
//        $mdlName = $this->model;
//        $o = new $mdlName();
//        if ($id == '') {
//            $tmp = $o->lookupAll()->result();
//        } else {
//            $tmp = $o->lookupByID($id)->result();
//        }
//        $result = array();
////        print_r($tmp);die();
//        if (sizeof($tmp) > 0) {
//            foreach ($tmp as $row) {
//                $tmpData = array();
//                foreach ($o->getFields() as $fName => $fSpec) {
////                    echo "fName: $fName, kolom: ".$fSpec['kolom']."<br>";
//                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
//                    $tmpData[$fName] = $row->$realFieldName;
//                }
//                $result[] = $tmpData;
//            }
//        }
//        $this->response($result, 200);
//    }


    function index_get()
    {


        $this->response(array("str"=>"ok"), 200);
    }

    function lookupByID_get()
    {

        $id=$this->uri->segment(5);
        $mdlName = $this->model;
        $o = new $mdlName();
        $tmp = $o->lookupByID($id)->result();
        $result = array();
//        print_r($tmp);die();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
//                    echo "fName: $fName, kolom: ".$fSpec['kolom']."<br>";
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function lookupDataCount_get()
    {

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();

        $result = $o->lookupDataCount();
        $this->response($result, 200);
    }

    function lookupLimitedData_get()
    {


        $limit_per_page=$this->uri->segment(5);
        $page=$this->uri->segment(6);
        $key=$this->uri->segment(7);

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();

        $tmp = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);

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

    function lookupRecentHistories_get()
    {
        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();

        $this->load->model("MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$mdlName'");
        $tmp = $h->lookupRecentHistories()->result();

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array(
                    "label"=>$row->label,
                    "oleh_id"=>$row->oleh_id,
                    "oleh_nama"=>$row->oleh_name,
                );
                $content=unserialize(base64_decode($row->new_content));
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = isset($content[$realFieldName])?$content[$realFieldName]:"";
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function lookupDataProposal_get()
    {

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();


        $this->load->model("MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='$mdlName'");
        $tmp = $tData->lookupAll()->result();
        $result = array();
//        print_r($tmp);die();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array(
                    "label"=>$row->propose_type,
                    "oleh_id"=>$row->proposed_by,
                    "oleh_nama"=>$row->proposed_by_name,
                );
                $content=unserialize(base64_decode($row->content));
                foreach ($o->getFields() as $fName => $fSpec) {
//                    echo "fName: $fName, kolom: ".$fSpec['kolom']."<br>";
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = isset($content[$realFieldName])?$content[$realFieldName]:"";
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    //Mengirim atau menambah data $mdlName baru


    function index_post()
    {
        $mdlName = $this->model;
        $o = new $mdlName();
        foreach ($o->getFields() as $fName => $fSpec) {
            $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $data[$realFieldName] = $this->post($fName);
        }
        //$insert = $this->db->insert('telepon', $data);
        $insert = $o->addData($data);
        if ($insert) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function addItem_post()
    {
        $mdlName = $this->model;
        $o = new $mdlName();
        foreach ($o->getFields() as $fName => $fSpec) {
            $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $data[$realFieldName] = $this->post($fName);
        }
        //$insert = $this->db->insert('telepon', $data);
        $insert = $o->addData($data);
        if ($insert>0) {
            $this->response($data, 200);
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

    function deleteItem_put()
    {
//        $id = $this->delete('id');
//        $id = $this->put('id');
        $id=$this->uri->segment(5);

        $mdlName = $this->model;
        $o = new $mdlName();
        $this->db->where('id', $id);
        //$delete = $this->db->delete('telepon');
        $delete = $o->updateData(array("id" => $id), array("trash" => "1"));
        print_r($this->db->last_query());
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function index_put()
    {
        $id = $this->put('id');
        $mdlName = $this->model;
        $o = new $mdlName();
        foreach ($o->getFields() as $fName => $fSpec) {
            $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $data[$realFieldName] = $this->post($fName);
        }
        // $this->db->where('id', $id);

        // $update = $this->db->update('telepon', $data);
        $update = $o->updateData(array("id" => $id), $data);
        if ($update) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function index_delete()
    {
        $id = $this->delete('id');
        $mdlName = $this->model;
        $o = new $mdlName();
        $this->db->where('id', $id);
        //$delete = $this->db->delete('telepon');
        $delete = $o->updateData(array("id" => $id), array("trash" => "1"));
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }

    function getFields_get()
    {
        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();
        $result = $o->getFields();
//        print_r($tmp);die();
        $this->response($result, 200);
    }

    function getListedFields_get()
    {
        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();
        $result = $o->getListedFields();
//        print_r($tmp);die();
        $this->response($result, 200);
    }
}

?>