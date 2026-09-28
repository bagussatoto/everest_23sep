<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/30/2018
 * Time: 3:02 PM
 */
class MdlMailNotif_rita extends CI_Model
{
    private $tableName = "terima";
    private $fields = array(
        "jenis_sender",
        "em_nama_pengirim",
        "em_email_pengirim",
        "em_nama_tujuan",
        "em_email_tujuan",
        "em_judul",
        "em_isi_html",
    );

    private $botParams = array(
        "jenis_sender"=>"email",
        "em_email_pengirim"=>"mgk.mailer.daemon@gmail.com",
        "em_nama_tujuan"=>"em-ji-key",
        // "em_email_tujuan"=>"maya_graha_kencana@googlegroups.com",
        "em_email_tujuan"=>"mr.azes@gmail.com",
    );


    function send($params)
    {
        $efg = "";
        $data = explode('.', $_SERVER['SERVER_NAME']);
        if (!empty($data[0])) {
            $efg = $data[0];
        }

        $active_group = $efg; // this will choose from subdomain1/subdomain2 settings below. Add as many as you need
        $active_record = TRUE;


        // $htmlContent = $params['em_isi_html'];
        //
        // $htmlContent .="<br>";
        // $htmlContent .= "<small style='color:#787878;'>Email ini dikirim secara otomatis oleh " . $this->config->item('appConfig')['appName'] . " sehingga anda tak perlu membalasnya</small><br>";
        // $htmlContent .= "<small style='color:#787878;'><a href='" . base_url() . "'>Sentuh di sini</a> untuk menuju ke " . $this->config->item('appConfig')['appName'] . " menggunakan browser anda</small>";


        $dataT = array();
        foreach ($this->fields as $fName) {
            if(isset($this->botParams[$fName])){
                $val=$this->botParams[$fName];
            }else{
                if(isset($params[$fName])){
                    $val=$params[$fName];
                }else{
                    $val="*";
                }
            }
            $dataT[$fName] = $val;

        }

        $dataT['em_nama_pengirim']=$this->session->login['name']."@".$this->config->item('appConfig')['appName'];
        $dataT['em_isi_html'] .="<br>";
        $dataT['em_isi_html'] .="<div style='padding:4px;color:#003377;font-size:13px;background-image: linear-gradient(to right, rgba(0,0,0,0), rgba(0,112,255,1));color:#005689;border:1px #4488ab solid;'>";
        $dataT['em_isi_html'] .= "Pemberitahuan ini dikirim otomatis pada jam ".date("H:i").", anda tak perlu memberi balasan.<br>";
        $dataT['em_isi_html'] .= "<a href='" . base_url() . "' style='font-size:16px;'>Sentuh di sini</a> untuk menuju ke " . $this->config->item('appConfig')['appName'] . " menggunakan browser anda";
        $dataT['em_isi_html'] .="</div>";







        $dbRita=$this->load->database("rita",TRUE);
        $dbRita->insert($this->tableName, $dataT);
        return $dbRita->insert_id();
    }
}