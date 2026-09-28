<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(0);
ini_set('display_errors', 0);

class Redirect extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $direction = $this->uri->segment(2);
        $allowAccessWithoutLogin = array(
            'mbIndex', 'mbdata','mobileUpload', 'mobileUploadManual', 'checkQR', 'clearSessionCheckQR'
        );
        if(!in_array($direction,$allowAccessWithoutLogin)){

            if (!isset($this->session->login['id'])) {
//                if(isset($_GET['forceMobile'])){
                    $xxx = blobEncode(base_url() . "pembelian/Transaksi/index/461");
//                }
                redirect(base_url() . "Login?xxx=$xxx");
            }
            else{
                redirect(base_url() . "pembelian/Transaksi/index/461");
            }
        }
        else{

            if(!isset($this->session->login['id'])){
                redirect(base_url() . "Login?xxx=");
            }
            else{
                redirect(base_url() . "pembelian/Transaksi/index/461");
            }

        }
    }
}