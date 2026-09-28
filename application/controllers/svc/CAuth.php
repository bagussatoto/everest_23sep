<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class CAuth extends REST_Controller
{


    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->database();
    }


    public function authCheck_post()
    {
        $validCounter = 0;

        $nama_login = $this->input->post('nama');
        $post_password = $this->input->post('password');

        if ($validCounter < 1) {

            $this->load->model("MdlEmployee");
            $this->load->model("MdlCabang");
            $o = new MdlEmployee();



            $dataValid = $o->lookupByCondition(array(
                "nama_login" => $nama_login,
                "password" => md5($post_password),
            ))->result();


            $jmlValid = sizeof($dataValid);


            if (sizeof($dataValid) > 0) {
                $userProp = $dataValid[0];
                foreach ($userProp as $field => $item) {
                    $$field = $item;
                }

//                if ($userProp->cabang_id > -1) {
//                    $c = new MdlCabang();
//                    $tmpC = $c->lookupByID($userProp->cabang_id)->result();
//                    if (sizeof($tmpC) > 0) {
//                        $cabangName = $tmpC[0]->nama;
//                    } else {
//                        $cabangName = "unknown";
//                    }
//                } else {
//                    $cabangName = "pusat";
//                }


                $arrSessLogin = array(
                    "id",
                    "nama_login",
                    "nama",
//                    "phpsess_dtime",
                    "cabang_id",
//                    "phpsessid",
//                    "status",
//                    "jenis",
                );
                $arrLogin = array_intersect_key(((array)$userProp), array_flip($arrSessLogin));
//                $loginProp = array_merge($arrLogin, $arrGudangRelasi_employee);
                $loginProp = array_merge($arrLogin);
//                $loginProp['cabang_nama'] = $cabangName;
                $loginProp['membership'] = unserialize(base64_decode($dataValid[0]->membership));


                $this->response($loginProp, 200);


                $validCounter++;
                // </editor-fold>
            } else {

                $this->response(null, 200);

            }
        }


    }

}

?>