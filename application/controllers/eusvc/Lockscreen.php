<?php

 require APPPATH . '/libraries/REST_Controller.php';
 use Restserver\Libraries\REST_Controller;

class Lockscreen extends CI_Controller
{
    function __construct($config = 'rest'){
        parent::__construct($config);
        $this->load->database();
        $this->load->library("Curl");

    }
    function index(){

        $this->db->select("id,nama");
        $this->db->where("id=1");
        $tmp    = $this->db->get("company_profile");
        $comp   = $tmp->result();
        $company_profile = $comp[0]->nama;

        $tahun = date("Y");
        $base_url = base_url();
        $cdn = "https://cdn.mayagrahakencana.com/assets/suport";
        $html = "
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset='utf-8'>
                    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                    <title>$company_profile | Lockscreen</title>
                    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
                    <link rel='stylesheet' href='$cdn/bootstrap-3.3.7-dist/css/bootstrap.min.css'>
                    <link rel='stylesheet' href='$cdn/Font-Awesome-master/css/font-awesome.min.css'>
                    <link rel='stylesheet' href='$cdn/ionicons-master/css/ionicons.min.css'>
                    <link rel='stylesheet' href='$cdn/AdminLTE-2.3.11/dist/css/AdminLTE.css'>
                    <style>
                        body{
                            overflow-y:hidden!important;
                        }
                        .lockscreen-wrapper {
                            max-width:65vw!important;
                            height:70vh!important;
                            margin-top:5%!important;
                        }
                        .lockscreen-footer {
                            margin-top:10vh!important;
                        }
                    </style>
                </head>
                <body class='hold-transition lockscreen'>
                    <div class='lockscreen-wrapper'>
                        <div class='lockscreen-logo text-bold'>
                            <div class=''>
                                <img src='../public/images/profiles/logo_header.png' alt='$company_profile'>
                            </div>
                        </div>
                        <div class='lockscreen-name'><i class='fa fa-lock text-orange fa-2x'></i> HALAMAN INI TELAH DI KUNCI <i class='fa fa-lock text-orange fa-2x'></i></div>
                        <div class='help-block text-center fa-2x'>
                            Aplikasi sedang terbuka di jendela lain.<br>Klik <b>`Gunakan di Sini`</b> untuk menggunakan pada jendela ini.
                        </div>
                        <div class='text-center'>
                            <div onclick=\"javascript: genNewTab(1,'$base_url');\" class='btn btn-sm btn-flat btn-primary'>Gunakan di Sini</div>
                            <div class='btn btn-xs bg-gray'>TUTUP</div>
                        </div>
                        <div class='lockscreen-footer text-center'>
                            Copyright &copy; $tahun <b><a href='https://google.com/' class='text-black'>$company_profile</a></b><br>
                            All rights reserved
                        </div>
                    </div>
                    <script src='$cdn/AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js'></script>
                    <script src='$cdn/bootstrap-3.3.7-dist/js/bootstrap.min.js'></script>
                    <script src='../assets/custom/custom.js'></script>
                </body>
            </html>";

        echo $html;
    }
}

?>


