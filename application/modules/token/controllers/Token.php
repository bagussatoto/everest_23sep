<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class Token extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->cabang_id = CB_ID_PUSAT;
        $this->harga_jenis = "jual_reseller";
        $this->pph23 = 15;

        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
    }
    public function index()
    {
        /* ----------------------------------------------------------------
         * default tab yg aktif diatur di viewer pada array isi tab
         * ----------------------------------------------------------------*/

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $this->load->library("Diskon");
        $dk = new Diskon();

        $this->load->model("Mdls/MdlEmployee_all");
        $e = new MdlEmployee_all();
        $tempEmply = $e->lookUpAll()->result();

//        arrPrint($tempEmply);

        $data = array(
            "mode"           => "index",
            "isMobile"       => $isMob,
            "user_id"        => $this->session->login['id'],
            "user_login"     => $this->session->login['nama'],
            "employee"       => $tempEmply,
        );
        $this->load->view("token", $data);

    }
}
