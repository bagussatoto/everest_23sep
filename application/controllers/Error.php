<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        // $this->load->model("Mdls/MdlProduk");
        // $pr = new MdlProduk();
        // $srcProduk = $pr->lookupProduk()->result();
        // $srcFixed = $la->lookupNews()->result();

        $data = array(
            "mode"             => "default",
            "title"            => "error",
            "subTitle"         => "",
            "meta_description" => "Tempat ngopi dengan suasana yang rilex, menjadikan acara santai, nugas maupun bisnis bisa dilakukan selama 24 jam, kopi vietname, single origin, aceh gayo jogja",
            "isi"              => "test",
        );


        $this->load->view("error", $data);

    }

    public function err_404()
    {
        // arrPrint($_SERVER);
        $info = info_debuger();
        $btn_back = "";
        if (isset($_SERVER['HTTP_REFERER'])) {

            $link_back = $_SERVER['HTTP_REFERER'];
            $btn_back = "<button type='button' class='btn btn-info' onclick=\"location.href='$link_back'\">Kembali ke Halaman Sebelumnya</button><br>";
        }
        $data = array(
            "mode"             => "default",
            "title"            => "404 error",
            "subTitle"         => "",
            "meta_description" => "",
            "judul"            => "404",
            "isi"              => "Halaman yang Anda cari tidak ditemukan <br>$btn_back" . dtimeNow("Y M D H:i:s"),
        );


        $this->load->view("error", $data);
    }

    public function err_500()
    {

        $data = array(
            "mode"             => "default",
            "title"            => "500 error",
            "subTitle"         => "",
            "meta_description" => "Tempat ngopi dengan suasana yang rilex, menjadikan acara santai, nugas maupun bisnis bisa dilakukan selama 24 jam, kopi vietname, single origin, aceh gayo jogja",
            "judul"            => "500",
            "isi"              => "Halaman yang Anda cari tidak ditemukan",
        );


        $this->load->view("error", $data);
    }

}
