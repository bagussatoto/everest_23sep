<?php

class _placeCheck extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function kahsjHFhst765674hsgdhhgghgh()
    {
        $param = isset($_GET['param']) ? $_GET['param'] : null;
        //die($param);
        if ($param != null) {
            $this->load->model("Mdls/MdlWorkstation");
            $ws = new MdlWorkstation();
            $ws->addFilter("status='1'");
            $ws->addFilter("param='$param'");
            $tmp = $ws->lookupAll()->result();
//            print_r($tmp);
            if (sizeof($tmp) > 0) {//==this is known place


                $gudSpec_cabang = getDefaultWarehouseID($tmp[0]->cabang_id, $tmp[0]->id);

                echo "<script>";
                echo "top.document.getElementById('dev_id').innerHTML='" . $tmp[0]->id . "';";
                echo "top.document.getElementById('dev_name').innerHTML='" . $tmp[0]->nama . "';";
                echo "top.document.getElementById('or_c_id').innerHTML='" . $tmp[0]->cabang_id . "';";
                echo "top.document.getElementById('or_c_name').innerHTML='" . $tmp[0]->cabang_name . "';";
                echo "top.document.getElementById('btnLogin').innerHTML='Check into " . $tmp[0]->cabang_name . " <span class=\'glyphicon glyphicon-ok\'></span>';";
                echo "top.document.getElementById('btnLogin').disabled=false;";
                echo "top.document.getElementById('btnLogin').className='btn btn-success btn-block btn-lg';";
                echo "top.document.getElementById('cab').value='" . $tmp[0]->cabang_id . "';";
                echo "top.document.getElementById('cabN').value='" . $tmp[0]->cabang_name . "';";
                echo "top.document.getElementById('fLogin').action='" . base_url() . "Login/authCheck_branch?dev='+top.document.getElementById('dev_id').innerHTML+'&dev_name='+top.document.getElementById('dev_name').innerHTML;";
                echo "top.document.getElementById('divAlt').innerHTML=\"" . $gudSpec_cabang['gudang_nama'] . " (" . $tmp[0]->nama . ") <br/><a href=# onclick='switchToCenter();'>switch to admin</a>\";";
                echo "top.document.getElementById('uid').focus();";
                echo "top.document.getElementById('uid').select();";
                echo "</script>";
            }
            else {//===this is an unknown place
                echo "<script>";
                echo "top.document.getElementById('btnLogin').innerHTML='admin login <span class=\'glyphicon glyphicon-ok\'></span>';";
                echo "top.document.getElementById('btnLogin').disabled=false;";
                echo "top.document.getElementById('btnLogin').className='btn btn-warning btn-block btn-lg';";
                echo "top.document.getElementById('fLogin').action='" . base_url() . "Login/authCheck';";
                echo "top.document.getElementById('divAlt').innerHTML=\"This is an unregistered device. <a href='" . base_url() . get_class($this) . "/register/?id=kjahghghGHSghhsg7876678&param=$param' style='text-decoration:underline;'>register</a>\";";
                echo "top.document.getElementById('uid').focus();";
                echo "top.document.getElementById('uid').select();";
                echo "</script>";
            }
        }


    }

    public function register()
    {
        $this->load->model("Mdls/MdlCabang");
        $p = new Layout("device registration", "device registration", "application/template/blank.html");

        $content = "";
        $content .= ("<div class='panel-body'>");
//        $content.=(form_open(base_url() . get_class($this) . "/doRegister/?id=kjasGGFGHGsg76676686&param=" . $_GET['param'], array("id" => "freg", "name" => "freg", "method" => "post")));
        $content .= ("<form id='freg' name='freg' method='post' action='" . base_url() . get_class($this) . "/doRegister/?id=kjasGGFGHGsg76676686&param=" . $_GET['param'] . "'>");
        $content .= ("<ul class='list-group'>");

        $content .= ("<li class='list-group-item' style='background:#0056cd;color:#f5f5f5;'><span class='glyphicon glyphicon-record'></span> you're trying to register this device as a sales point</li>");

        $o = new MdlCabang();
        $tmp = $o->lookupAll()->result();
        if (sizeof($tmp) > 0) {

            $content .= ("<li class='list-group-item'>");

            $content .= ("<div class='row'>");
            $content .= ("<div class='col-sm-3'>branch</div>");

            $content .= ("<div class='col-sm-9'>");
            $content .= ("<select class='form-control' id='cab' name='cab' onchange=\"if(document.getElementById('cab').value>0 && document.getElementById('name').value.length>2){document.getElementById('btnReg').disabled=false;}else{document.getElementById('btnReg').disabled=true;}\">");
            $content .= ("<option value='0'>");
            $content .= ("--select branch--");
            $content .= ("</option>");
            foreach ($tmp as $row) {
                $content .= ("<option value='" . $row->id . "'>");
                $content .= ($row->nama);
                $content .= ("</option>");
            }
            $content .= ("</select>");

            $content .= ("</div>");

            $content .= ("</div class='row'>");

            $content .= ("</li class='list-group-item'>");


            $content .= ("<li class='list-group-item'>");

            $content .= ("<div class='row'>");
            $content .= ("<div class='col-sm-3'>name of device</div>");

            $content .= ("<div class='col-sm-9'>");
            $content .= ("<input type='text' class='form-control' id='name' name='name' placeholder='eq: john-android' onkeyup=\"if(document.getElementById('cab').value>0 && document.getElementById('name').value.length>2){document.getElementById('btnReg').disabled=false;}else{document.getElementById('btnReg').disabled=true;}\">");


            $content .= ("</div>");

            $content .= ("</div class='row'>");

            $content .= ("</li class='list-group-item'>");

            $content .= ("<li class='list-group-item' styles='background: #e5e5e5;'>");

            $content .= ("<div class='row'>");
            $content .= ("<div class='col-sm-6'><a class='btn btn-warning btn-block' href='" . base_url() . "'>cancel</a></div>");


            $content .= ("<div class='col-sm-6'><input type='button' id='btnReg' disabled class='btn btn-success btn-block' value='register device' onclick =\"this.disabled=true;document.getElementById('freg').submit();\"></div>");
            $content .= ("</div>");

            $content .= ("</div class='row'>");

            $content .= ("</li class='list-group-item'>");


        }
        else {
            $content .= ("<li class='list-group-item'>");
            $content .= ("unable to obtain registration parameters<br><a href='" . base_url() . "'>go back</a>");
            $content .= ("</li class='list-group-item'>");
        }
        $content .= ("</ul class='list-group'>");
        $content .= ("</form>");
        $content .= ("</div class='panel-body'>");


        $content .= "<div class=''>";
        $content .= "<div class='panel-body text-center'>";
        $content .= "<strong>note: </strong> registering this device means you will be able to make any sales transaction using this device<br>";
        $content .= "remember that you should contact your system administrator to authorize this device based on your request.<br>";
        $content .= "</div class='panel-body'>";
        $content .= "</div class='panel panel-default'>";

        $p->addTags(array(
            "content" => $content,
            "errMsg" => ""
        ));
        $p->render();


    }

    public function doRegister()
    {
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/MdlWorkstation");
        $cab = new MdlCabang();
        $tmpCab = $cab->lookupByID($this->input->post('cab'))->result();
        $o = new MdlWorkstation();
        $param = array(
            "cabang_id" => $this->input->post('cab'),
            "cabang_name" => sizeof($tmpCab > 0) ? $tmpCab[0]->nama : "Unknown",
            "param" => $_GET['param'],
            "browser" => $_SERVER['HTTP_USER_AGENT'],
            "ipaddr" => $_SERVER['REMOTE_ADDR'],
            "registered" => date("Y-m-d H:i:s"),
            "registered_by" => $this->input->post('name'),
            "nama" => $this->input->post('name'),
            "estabilished" => "0000-00-00 00:00:00",
            "active" => 0,
        );
        //$o->addData($param) or die(lgShowError("Gagal menyimpan registrasi", "Ini adalah kesalahan sistem. Sebaiknya anda laporakan hal ini pada developer dengan menyertakan tangkapan layar"));

        $this->db->trans_start();
        $this->load->model("Mdls/MdlDataTmp");
        $dTmp = new MdlDataTmp();
        $tmpData = array(
            //"orig_id"=>$data['id'],
            "mdl_name" => "MdlWorkstation",
            "mdl_label" => "Workstation",
            "proposed_by" => -1,
            "proposed_by_name" => $this->input->post('name'),
            "proposed_date" => date("Y-m-d H:i:s"),
            "content" => base64_encode(serialize($param)),
        );
        $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));


        //<editor-fold desc="data history / propose">
        $this->load->model("Mdls/MdlDataHistory");
        $hTmp = new MdlDataHistory();
        $tmpHData = array(
            "orig_id" => 0,
            "mdl_name" => "MdlWorkstation",
            "mdl_label" => "Workstation",
            "old_content" => "",
            "new_content" => base64_encode(serialize($param)),
            "label" => "proposed",
            "oleh_id" => -1,
            "oleh_name" => $this->input->post('name'),
        );
        $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        //</editor-fold>
        $this->db->trans_complete();
        redirect(base_url() . get_class($this) . "/doneRegister");
    }

    public function doneRegister()
    {
        $p = new Layout("device registration", "device registration", "application/template/login.html");
        $content = "";
        $content .= ("<div class='panel panel-default'>");
        $content .= ("<div class='panel-body'>");
        $content .= ("<h3><span class='glyphicon glyphicon-info-sign'></span> device registered</h3>");
//        $content.=("<div class='alert alert-info'>");

        $content .= ("<div class='panel-body'>");
        $content .= ("your device has been succesfully registered and <strong class='text-danger'>awaiting for approval</strong><br><br>");
        $content .= ("Please contact your branch administrator so you can start using this device.<br><br>");

        $content .= ("<span class='pull-right'>");
        $content .= ("<a class='btn btn-primary' href='" . base_url() . "'><span class='glyphicon glyphicon-arrow-left'></span> back</a><br>");
        $content .= ("</span>");
        $content .= ("</div class='panel-body'>");
//        $content.=("</div class='alert alert-info'>");
        $content .= ("</div class='panel-body'>");
        $content .= ("</div class='panel panel-default'>");
        $p->addTags(array(
            "content" => $content,
            "errMsg" => "",
        ));
        $p->render();
    }
}