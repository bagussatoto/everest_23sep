<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/30/2018
 * Time: 3:02 PM
 */
class MdlMailNotif extends CI_Model
{
    private $tableName = "message";
    private $fields = array(
        "type",
        "author_name",
        "author_id",
        "recipient_name",
        "recipient_id",
        "subject",
        "body",
    );

    private $botParams = array(
        "type"           => "email",
        "author_id"      => "mgk.mailer.daemon@gmail.com",
        "recipient_name" => "em-ji-key",
        // "recipient_id" => "maya_graha_kencana@googlegroups.com",
        // "recipient_id"   => "mr.azes@gmail.com",
        // "recipient_id"   => "thomas.jogja@gmail.com",
    );


    function send($params)
    {
        $efg = "";
        $data = explode('.', $_SERVER['SERVER_NAME']);
        if (!empty($data[0])) {
            $efg = $data[0];
        }
        // arrPrint($params);
        $active_group = $efg; // this will choose from subdomain1/subdomain2 settings below. Add as many as you need
        $active_record = TRUE;


        // $htmlContent = $params['body'];
        //
        // $htmlContent .="<br>";
        // $htmlContent .= "<small style='color:#787878;'>Email ini dikirim secara otomatis oleh " . $this->config->item('appConfig')['appName'] . " sehingga anda tak perlu membalasnya</small><br>";
        // $htmlContent .= "<small style='color:#787878;'><a href='" . base_url() . "'>Sentuh di sini</a> untuk menuju ke " . $this->config->item('appConfig')['appName'] . " menggunakan browser anda</small>";


        $dataT = array();
        foreach ($this->fields as $fName) {
            if (isset($this->botParams[$fName])) {
                $val = $this->botParams[$fName];
            }
            else {

                // print_r($params);

                if (isset($params[$fName])) {
                    $val = $params[$fName];
                }
                else {
                    $val = "*";
                }
            }
            $dataT[$fName] = $val;

        }
        // arrPrint($dataT);

        $dataT['author_name'] = $this->session->login['name'] . "@" . $this->config->item('appConfig')['appName'];
        $dataT['body'] .= "<br>";
        // background-image: linear-gradient(to right, rgba(0,0,0,0), rgba(80,170,255,1));
        $dataT['body'] .= "<div style='padding:4px 10px;color:#003377;
                            font-family:Georgia;font-size:10px;line-height:24px;
                            background: (rgba(80,170,255,1));
                            color:#005689;
                            border:1px #4488ab solid;'>";
        $dataT['body'] .= "Pesan ini adalah pemberitahuan otomatis sehingga tidak memerlukan balasan / <i>reply</i>.<br>";
        // $dataT['body'] .= "<a href='" . base_url() . "' style='font-weight:bold;font-size:14px;padding:2px;background:#0056cd;color:#f0f0f7;border:1px #005689 solid;border-radius:6px;text-decoration:none;'>Sentuh di sini</a> untuk menuju ke " . $this->config->item('appConfig')['appName'] . " menggunakan browser anda";
        $dataT['body'] .= "</div>";

        // mati_disini();

        // $dataT['body'] .= "<div style='padding:4px;color:#565656;font-family:Sans-serif;font-size:13px;line-height:24px;'>";
        // $dataT['body'] .= "<a href='http://apps.mayagrahakencana.com/vitocafe.apk' style='font-weight:bold;font-size:14px;padding:2px;color:#0077ff;'>Unduh " . $this->config->item('appConfig')['appName'] . " untuk Android</a> | ";
        //
        // $dataT['body'] .= "<a href='http://apps.mayagrahakencana.com/vitodemo.apk' style='font-weight:bold;font-size:14px;padding:2px;color:#777777;'>Unduh " . $this->config->item('appConfig')['appName'] . "-BETA untuk berpartisipasi sebagai TESTER</a>";
        // $dataT['body'] .= "</div>";


        $dbMongo = $this->load->database("postman", TRUE);
        // $dbRita=$this->db;
        $dbMongo->insert($this->tableName, $dataT);

        return $dbMongo->insert_id();
    }
}