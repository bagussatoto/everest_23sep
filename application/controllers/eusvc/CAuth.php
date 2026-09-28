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

            $this->load->model("Mdls/MdlCustomer");
            $this->load->model("Mdls/MdlCabang");
            $o = new MdlCustomer();



            $dataValid = $o->lookupByCondition(array(
                "nama_login" => $nama_login,
                "password" => ($post_password),
            ))->result();

//            print($this->db->last_query());

            $jmlValid = sizeof($dataValid);


            if (sizeof($dataValid) > 0) {
                $userProp = $dataValid[0];
                foreach ($userProp as $field => $item) {
                    $$field = $item;
                }




                $arrSessLogin = array(
                    "id",
                    "nama_login",
                    "nama",
                    "cabang_id",

                );
                $arrLogin = array_intersect_key(((array)$userProp), array_flip($arrSessLogin));
                $loginProp = array_merge($arrLogin);
//                $loginProp['membership'] = unserialize(base64_decode($dataValid[0]->membership));


                $this->response($loginProp, 200);


                $validCounter++;
                // </editor-fold>
            } else {

                $this->response(0, 200);

            }
        }


    }

}

?>