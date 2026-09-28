<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller
{
    var $API = "";
    protected $searchString;

    private $allowView = false;
    private $allowCreate = false;
    private $allowEdit = false;
    private $allowDelete = false;
    //
    private $creatorUsingApproval = false;
    private $updaterUsingApproval = false;
    private $deleterUsingApproval = false;

    // <editor-fold defaultstate="collapsed" desc="getter-setter">

    public function __construct()
    {
        parent::__construct();

        $this->API = "http://demo.mayagrahakencana.com/debug/sansvc/index.php";
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->helper('form');
        $this->load->helper('url');

        $this->load->library('pagination');
        $className = "Mdl" . $this->uri->segment(3);

        $dataAccess = isset($this->config->item('heDataBehaviour')[$className]) ? $this->config->item('heDataBehaviour')[$className] : array(
            "viewers"       => array(),
            "creators"      => array(),
            "creatorAdmins" => array(),
            "updaters"      => array(),
            "updaterAdmins" => array(),
            "deleters"      => array(),
            "deleterAdmins" => array(),
        );

        $ctrlName = $this->uri->segment(3);
        $menus = isset($this->config->item('menuConfig')['data']) ? $this->config->item('menuConfig')['data'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        if (isset($dataAccess['view'])) {
            if (sizeof($menus) > 0) {
                foreach ($menus as $m => $rowSpec) {
                    if (!in_array($dataAccess['view'], $mems)) {
                        $this->pageMenu .= "<li><a href='" . base_url() . "$m'><span class='glyphicon glyphicon-hdd'></span>$rowSpec</a> </li>";
                    }
                }
                $this->pageMenu .= "<li><a href='authLogout'><span class='glyphicon glyphicon-off'>Keluar</a></li>";
            }
        }


        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $this->allowView = false;
        $this->allowCreate = false;
        $this->allowEdit = false;
        $this->allowDelete = false;
        foreach ($mems as $mID) {
            if (in_array($mID, $dataAccess['viewers'])) {
                $this->allowView = true;
            }
            if (in_array($mID, $dataAccess['creators'])) {
                $this->allowCreate = true;
            }
            if (in_array($mID, $dataAccess['updaters'])) {
                $this->allowEdit = true;
            }
            if (in_array($mID, $dataAccess['deleters'])) {
                $this->allowDelete = true;
            }
        }

        if (sizeof($dataAccess['creatorAdmins']) > 0) {
            $this->creatorUsingApproval = true;
        }
        else {
            $this->creatorUsingApproval = false;
        }
        if (sizeof($dataAccess['updaterAdmins']) > 0) {
            $this->updaterUsingApproval = true;
        }
        else {
            $this->updaterUsingApproval = false;
        }
        if (sizeof($dataAccess['deleterAdmins']) > 0) {
            $this->deleterUsingApproval = true;
        }
        else {
            $this->deleterUsingApproval = false;
        }

    }

    public function getSearchString()
    {
        return $this->searchString;
    }

    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
    }

    public function isAllowView()
    {
        return $this->allowView;
    }

    public function setAllowView($allowView)
    {
        $this->allowView = $allowView;
    }

    public function isAllowCreate()
    {
        return $this->allowCreate;
    }

    public function setAllowCreate($allowCreate)
    {
        $this->allowCreate = $allowCreate;
    }

    public function isAllowEdit()
    {
        return $this->allowEdit;
    }

    public function setAllowEdit($allowEdit)
    {
        $this->allowEdit = $allowEdit;
    }

    public function isAllowDelete()
    {
        return $this->allowDelete;
    }

    public function setAllowDelete($allowDelete)
    {
        $this->allowDelete = $allowDelete;
    }

    public function isCreatorUsingApproval()
    {
        return $this->creatorUsingApproval;
    }

    public function setCreatorUsingApproval($creatorUsingApproval)
    {
        $this->creatorUsingApproval = $creatorUsingApproval;
    }

    public function isUpdaterUsingApproval()
    {
        return $this->updaterUsingApproval;
    }

    public function setUpdaterUsingApproval($updaterUsingApproval)
    {
        $this->updaterUsingApproval = $updaterUsingApproval;
    }

    public function isDeleterUsingApproval()
    {
        return $this->deleterUsingApproval;
    }

    // </editor-fold>

    public function setDeleterUsingApproval($deleterUsingApproval)
    {
        $this->deleterUsingApproval = $deleterUsingApproval;
    }

    public function add()
    {
        $content = "";
        //        include_once 'leftMenu.php';
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        //==menampilkan form penambahan data berdasarkan datamodel (kelas data) yang bersesuaian
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (!$this->allowCreate) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }
        $this->load->model($className);
        $o = new $className;
        $f = new MyForm($o, "add", array(
            "id"      => "f1",
            "method"  => "post",
            "enctype" => "multipart/form-data",
            "action"  => base_url() . get_class($this) . "/addProcess/$ctrlName",
            "target"  => "result",
            "class"   => "form-inline",
        ));

        $f->openForm(base_url() . get_class($this) . "/addProcess/$ctrlName");
        $f->fillForm($className);
        $f->closeForm();

        $title = isset($this->config->item('menuLabel')[get_class($this)]) ? $this->config->item('menuLabel')[get_class($this)] : get_class($this);
        $p = new Layout($title, "Penambahan Data $title", "application/template/lte/index.html");

        $content .= ($f->getContent());

        if ($this->creatorUsingApproval) {
            $content .= ("<div class='panel-body'>");
            $content .= ("<div style='background:#cccccc;border:1px #ababab solid;'>");
            $content .= ("<div class='panel-body'>");
            $content .= ("Penambahan data baru akan memerlukan persetujuan sebelum menjadi data aktual");
            $content .= ("</div class='panel-body'>");
            $content .= ("</div>");
            $content .= ("</div class='panel-body'>");
        }

        $data = array(
            "mode"     => $this->uri->segment(2),
            "title"    => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content"  => $content,
        );
        echo $content;
        die();
        $this->load->view('data', $data);

    }

    public function edit()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $content = "";
        if (!$this->allowEdit) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }
        $this->load->model($className);
        $o = new $className;
        //        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        //$tmp = $o->lookupByCondition(array($indexFieldName=> "'" . $selectedID . "'"))->result();
        $tmp = (array)json_decode($this->curl->simple_get($this->API . "/data/lookupByID/$ctrlName/$selectedID"));
        //        print_r($tmp);die();
        $f = new MyForm($o, "edit", array(
            "id"      => "f1",
            "method"  => "post",
            "enctype" => "multipart/form-data",
            "action"  => base_url() . get_class($this) . "/editProcess/$ctrlName/" . $selectedID,
            "target"  => "result",
            "class"   => "form-horizontal",
        ));
        $f->openForm(base_url() . get_class($this) . "/editProcess/$ctrlName/" . $selectedID);
        $f->fillForm($className, $tmp);
        $f->closeForm();

        $title = isset($this->config->item('menuLabel')[get_class($this)]) ? $this->config->item('menuLabel')[get_class($this)] : get_class($this);
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");
        //$content .=("<div class='panel panel-default'>");
        //$content .=("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;'>");
        $content .= ($f->getContent());
        //$content .=("</div>");
        if ($this->updaterUsingApproval) {
            $content .= ("<div class='panel-body'>");
            $content .= ("<div style='background:#cccccc;border:1px #ababab solid;'>");
            $content .= ("<div class='panel-body'>");
            $content .= ("Pengubahan data akan memerlukan persetujuan sebelum menjadi data aktual<br>");
            //            $content .= ("Data NONAKTIF selama menunggu persetujuan<br>");
            $content .= ("</div class='panel-body'>");
            $content .= ("</div>");
            $content .= ("</div class='panel-body'>");
        }

        $data = array(
            "mode"     => $this->uri->segment(2),
            "title"    => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content"  => $content,
        );
        echo $content;
        die();
        $this->load->view('data', $data);
    }

    public function editFrom()
    {

        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (!$this->allowEdit) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }
        $this->load->model($className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);


        $this->load->model("MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");


        //$tmp = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $selectedID))->result();
        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = (object)unserialize(base64_decode($tmp[0]->content));
        //print_r($tmpContent);die();
        $title = isset($this->config->item('menuLabel')[get_class($this)]) ? $this->config->item('menuLabel')[get_class($this)] : get_class($this);
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");
        $f = new MyForm($o, "edit", array(
            "id"      => "f1",
            "method"  => "post",
            "enctype" => "multipart/form-data",
            "action"  => base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target"  => "result",
            "class"   => "form-horizontal",
        ));
        $f->openForm(base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID");
        //$f->fillForm($tmpContent);
        $content .= ("<table width=100%>");
        $content .= ("<tr><td colspan='2' class='text-muted'><h4>data yang diajukan</h4></td></tr>");
        foreach ($o->getFields() as $fName => $fSpec) {
            $fColName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $fLabel = isset($fSpec['label']) ? $fSpec['label'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$fLabel");
            $content .= ("</td>");
            $fContent = isset($tmpContent->$fColName) ? $tmpContent->$fColName : "";
            $disabled = isset($tmpContent->$fColName) ? "readonly" : "disabled";
            $content .= ("<td>");
            $content .= ("<input type='text' class='form-control' $disabled value='$fContent'>");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        $addRows = array(
            "tgl. diajukan" => $tmp[0]->proposed_date,
            "oleh"          => $tmp[0]->proposed_by_name,
            "ID data asli"  => $tmp[0]->orig_id,
        );
        $content .= ("<tr><td colspan='2' class='text-muted'>&nbsp;</td></tr>");
        $content .= ("<tr><td colspan='2' class='text-muted'><h4>informasi pengajuan</h4></td></tr>");
        foreach ($addRows as $key => $val) {
            $fColName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$key");
            $content .= ("</td>");
            $content .= ("<td>");
            $content .= ("<input type='text' class='form-control' $disabled value='$val'>");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        $content .= ("</table width=100%>");

        $yesAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doApproveFrom/$ctrlName/$selectedID/$origID');";
        $noAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doRejectFrom/$ctrlName/$selectedID/$origID');";
        if ($origID > 0) {
            $rejectAlertMsg = "jika pengajuan ini anda tolak, data aktual akan dikembalikan ke data sebelumnya";
            $approveAlertMsg = "jika pengajuan ini anda setujui, data ini akan merubah data yang aktif";
        }
        else {
            $rejectAlertMsg = "pengajuan ini akan dihapus permanen";
            $approveAlertMsg = "pengajuan ini akan diteruskan menjadi data aktif";
        }

        $content .= ("<div class='row'>");
        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-danger btn-block' href='javascript:void(0)' onClick =\"if(confirm('$rejectAlertMsg \\nLanjutkan?')==1){$noAction}\">tolak</a>");
        $content .= ("</div class='col-sm-6'>");
        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-success btn-block' href='javascript:void(0)' onClick =\"if(confirm('$approveAlertMsg \\nLanjutkan?')==1){$yesAction}\">setujui</a>");
        $content .= ("</div class='col-sm-6'>");
        $content .= ("</div class='row'>");

        $f->closeForm();

        //$content .=("<div class='panel panel-default'>");
        //$content .=("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;'>");
        $content .= ($f->getContent());
        //$content .=("</div>");
        $data = array(
            "mode"     => $this->uri->segment(2),
            "title"    => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content"  => $content,
        );
        echo $content;
        die();
        $this->load->view('data', $data);
    }

    public function deleteFrom()
    {

        $pageMode = isset($_GET['mode']) ? $_GET['mode'] : "view";
        $pageTemplate = (isset($_GET['mode']) && $_GET['mode'] == 'print') ? "application/template/blank.html" : "application/template/lte/index.html";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $content = "";
        if (!$this->allowDelete) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }
        $this->load->model($className);
        $o = new $className;

        $indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);


        $this->load->model("MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");


        //$tmp = $o->lookupByCondition(array("id" => $selectedID))->result();
        $tmp = $oTmp->lookupAll()->result();
        cekMerah($this->db->last_query());
        print_r($tmp);
        die();
        $tmpContent = (object)unserialize(base64_decode($tmp[0]->content));
        //print_r($tmpContent);die();
        $title = isset($this->config->item('lgMenuLabel')[get_class($this)]) ? $this->config->item('lgMenuLabel')[get_class($this)] : get_class($this);
        $p = new Page($title, "Ubah Data $title", $pageTemplate);
        $f = new MyForm($o, "edit", array(
            "id"      => "f1",
            "method"  => "post",
            "enctype" => "multipart/form-data",
            "action"  => base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target"  => "result",
            "class"   => "form-horizontal",
        ));
        $f->openForm(base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID");
        //$f->fillForm($tmpContent);
        $content .= ("<table width=100%>");
        $content .= ("<tr><td colspan='2' class='text-muted'><h4>data yang diajukan</h4></td></tr>");
        foreach ($o->getFields() as $fName => $fSpec) {
            $fColName = isset($fSpec['fieldName']) ? $fSpec['fieldName'] : $fName;
            $fLabel = isset($fSpec['label']) ? $fSpec['label'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$fLabel");
            $content .= ("</td>");
            $fContent = isset($tmpContent->$fColName) ? $tmpContent->$fColName : "";
            $disabled = isset($tmpContent->$fColName) ? "readonly" : "disabled";
            $content .= ("<td>");
            $content .= ("<input type='text' class='form-control' $disabled value='$fContent'>");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        $addRows = array(
            "tgl. diajukan" => $tmp[0]->proposed_date,
            "oleh"          => $tmp[0]->proposed_by_name,
            "ID data asli"  => $tmp[0]->orig_id,
        );
        $content .= ("<tr><td colspan='2' class='text-muted'>&nbsp;</td></tr>");
        $content .= ("<tr><td colspan='2' class='text-muted'><h4>informasi pengajuan</h4></td></tr>");
        foreach ($addRows as $key => $val) {
            $fColName = isset($fSpec['fieldName']) ? $fSpec['fieldName'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$key");
            $content .= ("</td>");

            $content .= ("<td>");
            $content .= ("<input type='text' class='form-control' $disabled value='$val'>");
            $content .= ("</td>");
            $content .= ("</tr>");
        }
        $content .= ("</table width=100%>");

        $yesAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID');";
        $noAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID');";
        if ($origID > 0) {
            $rejectAlertMsg = "jika pengajuan ini anda tolak, data tidak akan jadi dihapus";
            $approveAlertMsg = "jika pengajuan ini anda setujui, data akan benar-benar TERHAPUS";
        }
        else {
            $rejectAlertMsg = "pengajuan ini akan dihapus permanen";
            $approveAlertMsg = "pengajuan ini akan diteruskan menjadi data aktif";
        }

        $content .= ("<div class='row'>");
        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-danger btn-block' href='javascript:void(0)' onClick =\"if(confirm('$rejectAlertMsg \\nContinue?')==1){$noAction}\">tolak penghapusan</a>");
        $content .= ("</div class='col-sm-6'>");

        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-success btn-block' href='javascript:void(0)' onClick =\"if(confirm('$approveAlertMsg \\nContinue?')==1){$yesAction}\">setujui penghapusan</a>");
        $content .= ("</div class='col-sm-6'>");
        $content .= ("</div class='row'>");

        $f->closeForm();


        //$content.=("<div class='panel panel-default'>");
        //$content.=("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;'>");
        $content .= ($f->getContent());
        //$content.=("</div>");
        echo $content;
        die();

    }

    public function addProcess()
    {
        $content = "";
        //==menyimpan inputan data baru ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model($className);
        $o = new $className;
        $f = new MyForm($o, "addProcess");
        if ($f->isInputValid()) { //==jika validasi lengkap
            $this->db->trans_start();
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (isset($spec['inputType'])) {
                    switch ($spec['inputType']) {
                        case "checkbox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            //$data[$fName] = date("Y-m-d H:i:s");
                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "password":
                            $data[$fName] = md5($this->input->post($fName));
                            break;
                        case "file":
                            $data[$fName] = base64_encode(file_get_contents($this->input->post($fName)));
                            break;
                        case "hidden":
                            //                            switch ($spec['type']) {
                            //                                case "date":
                            //                                    $data[$fName] = date("Y-m-d");
                            //                                    break;
                            //                                case "datetime":
                            //                                    $data[$fName] = date("Y-m-d H:i:s");
                            //                                    break;
                            //                                case "timestamp":
                            //                                    $data[$fName] = date("Y-m-d H:i:s");
                            //                                    break;
                            //                                default:
                            //                                    $data[$fName] = $this->input->post($fName);
                            //                                    break;
                            //                            }

                            break;

                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }
                else {
                    //                    switch ($spec['type']) {
                    //                        case "varchar":
                    //                            $data[$fName] = $this->input->post($fName);
                    //                            break;
                    //                        case "int":
                    //                            $data[$fName] = $this->input->post($fName);
                    //                            break;
                    //                        case "date":
                    //                            $data[$fName] = date("Y-m-d");
                    //                            break;
                    //                        case "datetime":
                    //                            $data[$fName] = date("Y-m-d H:i:s");
                    //                            break;
                    //                        case "timestamp":
                    //                            $data[$fName] = date("Y-m-d H:i:s");
                    //                            break;
                    //                        default:
                    //                            $data[$fName] = $this->input->post($fName);
                    //                            break;
                    //                    }
                }
            }
            if (sizeof($o->getFilters()) > 0) {
                foreach ($o->getFilters() as $k => $v) {

                    $condPair = explode("=", $v);
                    if (sizeof($condPair) > 1) {
                        $data[$condPair[0]] = trim($condPair[1], "'");
                    }
                }
            }

            //<editor-fold desc="data temporer, jika pakai approval">
            $this->load->model("MdlDataTmp");
            $dTmp = new MdlDataTmp();
            $tmpData = array(
                "mdl_name"         => $className,
                "mdl_label"        => $ctrlName,
                "proposed_by"      => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date"    => date("Y-m-d H:i:s"),
                "content"          => base64_encode(serialize($data)),
            );
            //</editor-fold>
            if ($this->creatorUsingApproval) {
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                $this->session->errMsg = "Pengajuan data anda telah disimpan<br>Setelah disetujui, maka data barulah dapat digunakan";

                //<editor-fold desc="data history / propose">
                $this->load->model("MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"     => 0,
                    "mdl_name"    => $className,
                    "mdl_label"   => get_class($this),
                    "old_content" => "",
                    "new_content" => base64_encode(serialize($data)),
                    "label"       => "proposed",
                    "oleh_id"     => $this->session->login['id'],
                    "oleh_name"   => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                //</editor-fold>

            }
            else {
                $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                $this->session->errMsg = "Data telah disimpan";

                //<editor-fold desc="data history / commited">
                $this->load->model("MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"     => 0,
                    "mdl_name"    => $className,
                    "mdl_label"   => get_class($this),
                    "old_content" => "",
                    "new_content" => base64_encode(serialize($data)),
                    "label"       => "applied",
                    "oleh_id"     => $this->session->login['id'],
                    "oleh_name"   => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                //</editor-fold>
            }

            //===writing history

            $this->db->trans_complete();

            //===redirectnya harus diatur
            //            if ((null != $o->getCustomLink()) && is_array($o->getCustomLink())) {
            //                $strCustomDetail = "";
            //                $lSpec = $o->getCustomLink()['detail'];
            //
            //                $key = $lSpec['key'];
            //                $targetKey = $lSpec['targetKey'];
            //                //redirect(base_url() . $lSpec['link'] . "/index/1/$targetKey/" . $insertID);
            //                echo "<script>top.location.reload();</script>";
            //            } else {
            //
            //                //redirect(base_url() . get_class($this) . "/view");
            //                echo "<script>top.location.reload();</script>";
            //            }
            echo "<script>top.location.reload();</script>";

        }
        else {
            //===jika tidak lolos validasi
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Kesalahan pada inputan <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));

        }
    }

    public function editProcess()
    {
        $content = "";
        //==menyimpan inputan perubahan data ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model($className);
        $o = new $className;
        //$indexFieldName = $o->getIndexFieldName();
        $indexFieldName = "id";
        $f = new MyForm($o, "editProcess");
        if ($f->isInputValid()) { //==jika validasi lengkap
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (isset($spec['inputType'])) {
                    switch ($spec['inputType']) {
                        case "checkbox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            //$data[$fName] = date("Y-m-d H:i:s");
                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "password":
                            $data[$fName] = md5($this->input->post($fName));
                            break;
                        case "file":
                            $data[$fName] = base64_encode(file_get_contents($this->input->post($fName)));
                            break;
                        case "hidden":
                            //                            switch ($spec['type']) {
                            //                                case "date":
                            //                                    $data[$fName] = date("Y-m-d");
                            //                                    break;
                            //                                case "datetime":
                            //                                    $data[$fName] = date("Y-m-d H:i:s");
                            //                                    break;
                            //                                case "timestamp":
                            //                                    $data[$fName] = date("Y-m-d H:i:s");
                            //                                    break;
                            //                                default:
                            //                                    $data[$fName] = $this->input->post($fName);
                            //                                    break;
                            //                            }

                            $data[$fName] = $this->input->post($fName);
                            break;

                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "int":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");
                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }
            }
            $where = array(
                /*$indexFieldName =>*/
                "id" => $data['id'],
                //                "id"=> $data['id'],
            );

            //<editor-fold desc="data temporer, jika pakai approval">
            $this->load->model("MdlDataTmp");
            $dTmp = new MdlDataTmp();
            $tmpData = array(
                "orig_id"          => $data['id'],
                "mdl_name"         => $className,
                "mdl_label"        => $ctrlName,
                "proposed_by"      => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date"    => date("Y-m-d H:i:s"),
                "content"          => base64_encode(serialize($data)),
            );


            if ($this->updaterUsingApproval) {
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                cekHijau($this->db->last_query());
                $this->session->errMsg = "Pengajuan data anda telah disimpan<br>Setelah disetujui, maka data barulah dapat digunakan";

                $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/
                    "id" => $data['id'],
                ))->result();
                $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
                cekMerah($this->db->last_query());

                //<editor-fold desc="data history / propose">
                $this->load->model("MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"     => $data['id'],
                    "mdl_name"    => $className,
                    "mdl_label"   => get_class($this),
                    "old_content" => base64_encode(serialize((array)$tmpOrig)),
                    "new_content" => base64_encode(serialize($data)),
                    "label"       => "proposed",
                    "oleh_id"     => $this->session->login['id'],
                    "oleh_name"   => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                //</editor-fold>

            }
            else {
                $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/
                    "id" => $data['id'],
                ))->result();
                $o->updateData($where, $data, $o->getTableName());
                $this->session->errMsg = "Data telah diperbarui";

                //<editor-fold desc="data history / approve">
                $this->load->model("MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"     => $data['id'],
                    "mdl_name"    => $className,
                    "mdl_label"   => get_class($this),
                    "old_content" => base64_encode(serialize((array)$tmpOrig)),
                    "new_content" => base64_encode(serialize($data)),
                    "label"       => "applied",
                    "oleh_id"     => $this->session->login['id'],
                    "oleh_name"   => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                //</editor-fold>
            }


            $this->db->trans_complete();

            //redirect(base_url() . get_class($this) . "/view");
            //            if ((null != $o->getCustomLink()) && is_array($o->getCustomLink())) {
            //                $strCustomDetail = "";
            //                $lSpec = $o->getCustomLink()['detail'];
            //
            //                $key = $lSpec['key'];
            //                $targetKey = $lSpec['targetKey'];
            //                //redirect(base_url() . $lSpec['link'] . "/index/1/$targetKey/" . $data[$targetKey]);
            //                echo "<script>top.location.reload();</script>";
            //            } else {
            //
            //                //redirect(base_url() . get_class($this) . "/view");
            //                echo "<script>top.location.reload();</script>";
            //            }
            echo "<script>top.location.reload();</script>";
        }
        else {
            //===jika tidak lolos validasi
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Kesalahan pada inputan <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function delete()
    {
        $content = "";
        //==menghapus (aslinya mendisable) data sesuai datamodel dan id-nya yang bersesuaian
        $ctrlName = $this->uri->segment(3);
        $className = "Mdl" . $this->uri->segment(3);
        //$ctrlName = $this->uri->segment(3);
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (!$this->allowDelete) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= ("<div class='alert alert-danger'>");
            $content .= ("Anda tidak punya wewenang pada halaman ini<br>");
            $content .= ("<a href='" . base_url() . "'>Ke depan</a>");
            $content .= ("</div>");
            $p->render();
            die();
        }
        $this->load->model($className);
        $o = new $className;
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $where = array("id" => $selectedID);

        $oldDataTmp = $o->lookupById($selectedID)->result();


        $this->load->model("MdlDataTmp");
        $dTmp = new MdlDataTmp();
        $tmpData = array(
            "orig_id"          => $selectedID,
            "mdl_name"         => $className,
            "mdl_label"        => $ctrlName,
            "proposed_by"      => $this->session->login['id'],
            "proposed_by_name" => $this->session->login['nama'],
            "proposed_date"    => date("Y-m-d H:i:s"),
            "propose_type"     => "delete",
            "content"          => base64_encode(serialize((array)$oldDataTmp[0])),
        );
        if ($this->deleterUsingApproval) {
            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
            $this->session->errMsg = "Your deletion proposal has been saved and pending approval";

            $tmpOrig = $o->lookupByCondition(array("id" => $selectedID))->result();
            $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
            $tmpNew = (array)$tmpOrig;
            $tmpNew["status"] = 0;
            $tmpNew["trash"] = 1;

            //<editor-fold desc="data history / propose">
            $this->load->model("MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"     => $selectedID,
                "mdl_name"    => $className,
                "mdl_label"   => $ctrlName,
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "new_content" => base64_encode(serialize($tmpNew)),
                "label"       => "delete_proposed",
                "oleh_id"     => $this->session->login['id'],
                "oleh_name"   => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>

        }
        else {
            $tmpOrig = $o->lookupByCondition(array("id" => $selectedID))->result();

            //<editor-fold desc="really hapus">
            $o->lookupByCondition(array("id" => $selectedID));
            $data['trash'] = "1";
            //$o->deleteData($where, $o->getTableName());
            $o->updateData($where, $data, $o->getTableName());
            //</editor-fold>

            //<editor-fold desc="data history / approve">
            $this->load->model("MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"     => $selectedID,
                "mdl_name"    => $className,
                "mdl_label"   => $ctrlName,
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "new_content" => base64_encode(serialize($data)),
                "label"       => "deleted",
                "oleh_id"     => $this->session->login['id'],
                "oleh_name"   => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }

        $key = isset($_GET['k']) ? $_GET['k'] : "";
        redirect(base_url() . get_class($this) . "/view/$ctrlName/?k=$key");
    }

    public function index()
    {


        $content = "";
        //==aksi default, yaitu dibawa ke mode "view"
        //==sebelumnya dicek dulu, user buka halaman pakai slash atau enggak
        $splitStr = explode("/", __FILE__);
        if (get_class($this) . ".php" != $splitStr[sizeof($splitStr) - 1]) {
            redirect(base_url() . get_class($this) . "/view");
        }
        else {
            die("DiRECT access to this file is N.O.T. allowed!");
        }

        //        if (sizeof($this->configPath) > 0) {
        //            $availMenus = array();
        //            $availNewMenus = array();
        //            $loginType = $this->session->login['jenis'];
        //            foreach ($this->configPath as $mdlName => $mSpec) {
        //                if (isset($mSpec['viewers'])) {
        //                    if (sizeof($mSpec['viewers']) > 0) {
        //                        if (in_array($loginType, $mSpec['viewers'])) {
        //                            $availMenus[$mdlName] = str_replace("Mdl", "", $mdlName);
        //                        }
        //                    }
        //                    if (sizeof($mSpec['creators']) > 0) {
        //                        if (in_array($loginType, $mSpec['creators'])) {
        //                            $availNewMenus[$mdlName] = str_replace("Mdl", "", $mdlName);
        //                        }
        //                    }
        //                }
        //            }
        //
        //        } else {
        //            die("No data config found!");
        //        }
        //
        //
        //        //region yuk gas tambah
        //        if (!isset($this->session->login)) {
        //            redirect(base_url() . "Login");
        //            die();
        //        }
        //        $className = "Mdl" . $this->uri->segment(3);
        //        $ctrlName = $this->uri->segment(3);
        //
        //
        //        //region data proposal
        //        $this->load->model("MdlDataTmp");
        //        $tData = new MdlDataTmp();
        //        $tData->addFilter("mdl_name='$className'");
        //        $tmpTmp = $tData->lookupAll()->result();
        //        $dataProposals = array();
        //        if (sizeof($tmpTmp) > 0) {
        //            foreach ($tmpTmp as $row) {
        //                $mdlName = $row->mdl_name;
        //
        //
        //                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
        //                    "viewers" => array(),
        //                    "creators" => array(),
        //                    "creatorAdmins" => array(),
        //                    "updaters" => array(),
        //                    "updaterAdmins" => array(),
        //                    "deleters" => array(),
        //                    "deleterAdmins" => array(),
        //                );
        //                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        //
        //                arrPrint($dataAccess);
        //                $allowView = false;
        //                $allowCreate = false;
        //                $allowEdit = false;
        //                $allowDelete = false;
        //                foreach ($mems as $mID) {
        //                    if (in_array($mID, $dataAccess['viewers'])) {
        //                        $allowView = true;
        //                    }
        //                    if (in_array($mID, $dataAccess['creators'])) {
        //                        $allowCreate = true;
        //                    }
        //                    if (in_array($mID, $dataAccess['updaters'])) {
        //                        $allowEdit = true;
        //                    }
        //                    if (in_array($mID, $dataAccess['deleters'])) {
        //                        $allowDelete = true;
        //                    }
        //                }
        //
        //                if ($allowView || $allowCreate) {
        //                    if (!isset($dataProposals[$mdlName])) {
        //                        $dataProposals[$mdlName] = array();
        //                    }
        //                    $dataProposals[$mdlName][] = array(
        //                        "id" => $row->_id,
        //                        "label" => $row->mdl_label,
        //                        "origID" => $row->orig_id,
        //                        "proposer" => $row->proposed_by_name,
        //                        "date" => $row->proposed_date,
        //                        "content" => unserialize(base64_decode($row->content)),
        //                    );
        //                }
        //
        //
        //            }
        //        }
        //
        //        //endregion
        //        //endregion
        //
        //
        //        $data = array(
        ////            "mode" => $this->uri->segment(2),
        //            "mode" => "index",
        //            "availMenus" => $availMenus,
        //            "availNewMenus" => $availNewMenus,
        //        );
        //        $this->load->view("pages", $data);
    }

    public function view()
    {


        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        //<editor-fold desc="data proposal data">
        $this->load->model("MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='$className'");
        $tmpTmp = (array)json_decode($this->curl->simple_get($this->API . "/data/lookupDataProposal/$ctrlName"));
        cekhere("tmpTmp_1 == " . json_encode($tmpTmp[0]));
        cekhere("tmpTmp_2 == " . sizeof($tmpTmp));
        cekhere("tmpTmp_3 == " . sizeof($tmpTmp));

        $dataProposals = array();
        if (sizeof($tmpTmp) > 0) {
            foreach ($tmpTmp as $k => $row) {
                $mdlName = $className;
                $row = (object)$row;
                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers"       => array(),
                    "creators"      => array(),
                    "creatorAdmins" => array(),
                    "updaters"      => array(),
                    "updaterAdmins" => array(),
                    "deleters"      => array(),
                    "deleterAdmins" => array(),
                );
                //                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $dataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $dataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $dataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $dataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }

                if ($allowView || $allowCreate) {
                    if (!isset($dataProposals[$mdlName])) {
                        $dataProposals[$mdlName] = array();
                    }
                    $dataProposals[$mdlName][] = array(
                        "id"           => $row->_id,
                        "label"        => $row->mdl_label,
                        "origID"       => $row->orig_id,
                        "proposer"     => $row->proposed_by_name,
                        "date"         => $row->proposed_date,
                        "content"      => unserialize(base64_decode($row->content)),
                        "propose_type" => $row->propose_type,
                    );
                }
            }
        }


        cekhere("dataProposals: " . json_encode($dataProposals));

        //</editor-fold>
        $menuLabel = ($this->config->item('menuLabel') != NULL) ? $this->config->item('menuLabel') : array();
        $title = isset($this->config->item('menuLabel')[$className]) ? $this->config->item('menuLabel')[$className] : $ctrlName;
        $this->load->model($className);
        $o = new $className();
        $indexFieldName = "id";

        if (isset($_GET['trashed']) && $_GET['trashed'] > 0) {
            $objState = $_GET['trashed'];
            if ($objState == "1") {
                $title = "Deleted " . $title;
            }
            else {
                $objState = "0";
            }
        }
        else {
            $objState = "0";
        }

        switch ($objState) {
            case "0":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName?trashed=1'>view inactive $ctrlName</a>";
                break;
            case "1":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName'>view active $ctrlName</a>";
                break;
        }

        $o->addFilter("trash='$objState'");
        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";
        }
        else {
            $key = "";
            $subtitle = "Daftar $title";
        }

        $p = new Layout ($title, $subtitle, "application/template/lte/index.html");
        $t = new Table();
        //<editor-fold desc="tampilan approval data">

        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {

            $content .= ("<div class='panel-body'>");
            $content .= ("<ul class='list-group' style='background:#ffffff;border:1px #005689 solid;'>");

            foreach ($dataProposals as $mdlName => $pSpec) {

                $this->load->model("Mdls/" . $mdlName);
                $o = new $mdlName();
                $listedFields = $getListedFields;

                $content .= ("<li class='list-group-item' style='background:#005689;color:#ffffff;'>");
                $content .= ("<div class='row'>");
                $content .= ("<div class='col-sm-4'>");
                $content .= ("pengajuan data " . str_replace("Mdl", "", $mdlName));
                $content .= ("</div class='col-sm-4'>");

                foreach ($listedFields as $fName => $fLabel) {
                    $content .= ("<div class='col-sm-2'>$fLabel");
                    $content .= ("</div class='col-sm-2'>");
                }

                $content .= ("</div class='row'>");
                $content .= ("</li class='list-group-item'>");
                foreach ($pSpec as $dSpec) {
                    //                    echo "mulai mengiterasi kolom .. <br>";
                    $tmpItemTmp = array();
                    $content .= ("<li class='list-group-item'>");
                    $content .= ("<div class='row'>");
                    $content .= ("<div class='col-sm-2'><small>" . lgSimpleTime($dSpec['date']) . "</small><br>oleh " . $dSpec['proposer']);
                    $content .= ("</div class='col-sm-2'>");
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";
                    $content .= ("<div class='col-sm-2'><i>" . $dataStatus . "</i>");
                    $content .= ("</div class='col-sm-2'>");

                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        $content .= ("<div class='col-sm-2'>");
                        $content .= $dSpec['content'][$fRealName];
                        $content .= ("</div class='col-sm-2'>");
                        $tmpItemTmp[$fName] = $dSpec['content'][$fRealName];
                    }


                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";


                    $content .= ("<div class='col-sm-1'>");
                    $content .= ("<a class='btn btn-primary btn-block' href='javascript:void(0);' onclick =\"$approvalClick;\">");
                    $content .= ("<span class='glyphicon glyphicon-chevron-right'></span>");
                    $content .= ("</a>");
                    $content .= ("</div class='col-sm-1'>");

                    $content .= ("</div class='row'>");
                    $content .= ("</li class='list-group-item'>");
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='javascript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }

            }


            $content .= ("</ul class='list-group' style='background:#ffffff;border:1px #bcbcbc solid;'>");
            $content .= ("</div class='panel-body'>");

        }
        //</editor-fold>
        $content .= ("<div class='panel-body'>");
        $addLink = base_url() . get_class($this) . "/add/$ctrlName";
        $params = array();
        $limit_per_page = 12;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;
        $subitle = $subtitle . " hal. " . ($page + 1);

        $lookupLimitedDataUrl = $this->API . "/data/lookupLimitedData/" . $ctrlName . "/" . $limit_per_page . "/" . $page;
        $total_records = json_decode($this->curl->simple_get($this->API . "/data/lookupDataCount/$ctrlName"));
        $getListedFields = (array)json_decode($this->curl->simple_get($this->API . "/data/getListedFields/$ctrlName"));

        if ($total_records > 0) {
            // get current page records
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }

            $params["results"] = json_decode($this->curl->simple_get($lookupLimitedDataUrl));

            $config = array(
                'base_url'           => base_url() . get_class($this) . '/' . __FUNCTION__ . "/$ctrlName/",
                'total_rows'         => $total_records,
                'per_page'           => $limit_per_page,
                "uri_segment"        => 4,
                // custom paging configuration
                'num_links'          => 6,
                'use_page_numbers'   => TRUE,
                'reuse_query_string' => TRUE,
                'full_tag_open'      => '<div class="text-center">',
                'full_tag_close'     => '</div>',
                'first_link'         => "<span class='fa fa-home'></span>",
                'first_tag_open'     => '<span style="padding:1px;">',
                'first_tag_close'    => '</span>',
                'last_link'          => "<span class='fa fa-gg'></span>",
                'last_tag_open'      => '<span style="padding:1px;">',
                'last_tag_close'     => '</span>',
                'next_link'          => "<span class='fa fa-angle-right'></span>",
                'next_tag_open'      => '<span style="padding:1px;">',
                'next_tag_close'     => '</span>',
                'prev_link'          => "<span class='fa fa-angle-left'></span>",
                'prev_tag_open'      => '<span style="padding:1px;">',
                'prev_tag_close'     => '</span>',
                'cur_tag_open'       => '<span class="btn btn-primary disabled">',
                'cur_tag_close'      => '</span>',
                'num_tag_open'       => '<span style="padding:1px;">',
                'num_tag_close'      => '</span>',
            );
            $this->pagination->initialize($config);
            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination
        $dataRow = array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (sizeof($tmp) > 0) {
            $content .= ($t->addSpanRow(array($params['links']), sizeof($getListedFields), "center"));
        }
        $defaultKey = $key != "" ? $key : "cari " . strtolower($title);
        $content .= ($t->addSpanRow(array(
            "<div class='input-group'>" . "<input type=text placeholder='$defaultKey' class='form-control text-center' onkeyup=\"if(detectEnter()==1){location.href='" . base_url() . get_class($this) . "/view/$ctrlName/?k='+this.value}\">" . "<span class='input-group-addon'>" . "<i class='glyphicon glyphicon-search'></i></span>" . "</div class='input-group'>",
        )));
        $arrayItem = array();
        if (sizeof($tmp) > 0) {//===ada data
            $getListedFields = (array)json_decode($this->curl->simple_get($this->API . "/data/getListedFields/$ctrlName"));
            $fields = (array)json_decode($this->curl->simple_get($this->API . "/data/getFields/$ctrlName"));

            cekHere("getListedFields " . json_encode($getListedFields));
            cekHere("fields " . json_encode($fields));

            if ($this->uri->segment(3) > 0) {
                $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
            }
            else {
                $rowCounter = 0;
            }// </editor-fold>
            foreach ($tmp as $m => $rowSpec) {
                if ($this->allowEdit) {
                    $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $rowSpec->id . "";
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Ubah data ',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
                    $updateCommentStr = "Klik untuk mengubah entri";
                }
                else {
                    $updateLink = "#";
                    $updateCommentStr = "Anda tidak berhak mengubah entri";
                    $editClick = "return false;";
                }
                $deleteLink = base_url() . get_class($this) . "/delete/$ctrlName/" . $rowSpec->$indexFieldName . "";
                $colCounter = 0;
                $rowCounter++;
                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $idxName = "nama";
                $linkHist = base_url() . get_class($this) . "/viewHistories/$ctrlName/" . $rowSpec->id;
                $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                            {
                                                title:'Riwayat data ',
                                                message: $('<div></div>').load('" . $linkHist . "'),
                                                draggable:true,
                                                closable:true,
                                            }
                                        );";
                $tmpItem = array();
                foreach ($getListedFields as $ofName => $label) {
                    $fName = $ofName;
                    $fieldLabel = $rowSpec->$ofName;

                    $tmpItem['action'] = "";
                    $tmpItem[$ofName] = str_replace(" ", "&nbsp;", $fieldLabel) . "&nbsp;";
                    if ($this->allowEdit) {
                        $addNumber = $colCounter == 0 ? "<a href='javascript:void(0)' onclick =\"$historyClick\"><span class='badge' style='background:#c0c0c0;color:#656564;'>$rowCounter</span></a>" : "";
                        $dataRow[] = "$addNumber&nbsp;<a style='color:#343434;text-decoration:none;' href='javascript:void(0)' onClick =\"$editClick\" data-toggle='tooltip' data-placement='right' rel='tooltip' title='$updateCommentStr'>" . str_replace(" ", "&nbsp;", $fieldLabel) . "</a>&nbsp;";
                        $tmpItem['action'] .= "  <a class='btn btn-default' href='javascript:void(0)' onclick=\"$editClick\"><i class='fa fa-pencil'></i></a>";
                    }
                    else {
                        $dataRow[] = str_replace(" ", "&nbsp;", $fieldLabel) . "&nbsp;";
                    }
                    $colCounter++;
                }
                if ($this->allowDelete) {
                    $dataRow[] = "<button class='btn btn-danger btn-circle bg-orange-gradient' data-toggle='tooltip' data-placement='left' title='Hapus entri' onClick=\"if(confirm('Hapus data?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                    $tmpItem['action'] .= "    <a class='btn btn-default' href='javascript:void(0)' onclick=\"location.href='$deleteLink'\"><i class='fa fa-trash'></i></a>";
                }
                $tmpItem['history'] = "<a class='btn btn-default' href='javascript:void(0)' onclick=\"$historyClick\">history</a>";
                $content .= ($t->addRow($dataRow));
                $arrayItem[] = $tmpItem;
            }
        }
        else {
            $content .= ($t->addRow(array("-- tidak ada data --")));
        }
        $content .= ("</div>");
        $content .= ($t->closeTable());
        if (sizeof($tmp) > 0) {
            $content .= ($t->addSpanRow(array($params['links']), sizeof($getListedFields), "center"));
        }
        $dataRow = array();
        $strAddLink = "";
        if ($this->allowCreate) {
            $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'Tambahkan data baru',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";

            $strAddLink .= "<button onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Tambah entri' class='btn btn-circle btn-xl btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";
        }

        $arrayHeader = $getListedFields;
        $arrayHeader["action"] = "edit";
        $arrayHeader["history"] = "histories";
        $tmpH = (object)json_decode($this->curl->simple_get($this->API . "/data/lookupRecentHistories/$ctrlName"));
        $arrayRecap = array();
        if (sizeof((array)$tmpH) > 0) {
            foreach ((array)$tmpH as $k => $row) {
                $tmpRecap = array();
                $content = (array)$row;
                foreach ($arrayHeader as $fName => $label) {
                    $tmpRecap[$fName] = isset($content[$fName]) ? $content[$fName] : "";
                }
                $arrayRecap[] = $tmpRecap;
            }
        }
        $arrayProgressLabel['date'] = "date";
        $arrayProgressLabel['propose_type'] = "proposal type";
        $arrayProgressLabel = $arrayProgressLabel + $arrayHeader;
        $arrayRecapLabel = $arrayHeader;
        $arrayProgressLabel['action'] = "action";
        unset($arrayProgressLabel['history']);
        unset($arrayRecapLabel['action']);
        unset($arrayRecapLabel['history']);
        $titleSuffix = substr($ctrlName, strlen($ctrlName) - 1) == "s" ? "es" : "s";
        $data = array(
            "mode"                => $this->uri->segment(2),
            "errMsg"              => $this->session->errMsg,
            "title"               => $title . $titleSuffix,
            "subTitle"            => "List of $title" . $titleSuffix,
            "historyTitle"        => "<span class='glyphicon glyphicon-th-list'></span> List of $title" . $titleSuffix,
            "linkStr"             => isset($params['links']) ? $params['links'] : "",
            "arrayHistoryLabels"  => $arrayHeader,
            "arrayHistory"        => $arrayItem,
            "onprogressTitle"     => "<span class='glyphicon glyphicon-alert'></span> approval needed",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress"     => $arrItemTmp,
            //            "entities" => $entities,
            "recapTitle"          => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "arrayRecapLabels"    => $arrayRecapLabel,
            "arrayRecap"          => $arrayRecap,
            "strAddLink"          => $strAddLink,
            "alternateLink"       => $alternateLink,
        );
        $this->load->view('data', $data);
        $this->session->errMsg = "";
    }

    public function viewHistories()
    {
        $content = "";
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $selectedID = $this->uri->segment(4);
        $this->load->model($className);

        $o = new $className();
        $listedFields = $o->getListedFields();
        $fields = $o->getFields();

        $p = new Layout("", "", "application/template/lte/index.html");
        $this->load->model("MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        $h->addFilter("orig_id='$selectedID'");
        $tmpH = $h->lookupAll()->result();
        if (sizeof($tmpH) > 0) {
            $content .= ("<div class='table-responsive'>");
            $content .= ("<table class='table-bordered'>");
            $content .= ("<tr bgcolor='#dedede'>");
            $content .= ("<td>waktu</td>");
            foreach ($listedFields as $fName => $label) {
                $content .= ("<td>");
                $content .= ($label);
                $content .= ("</td>");
            }
            $content .= ("<td>state</td>");
            $content .= ("<td>person</td>");
            $content .= ("</tr>");
            foreach ($tmpH as $row) {
                $oldContents = unserialize(base64_decode($row->old_content));
                $newContents = unserialize(base64_decode($row->new_content));
                $content .= ("<tr>");
                $content .= ("<td>" . $row->dtime . "</td>");
                foreach ($listedFields as $fName => $label) {
                    //                    $fColName = $fields[$fName]['kolom'];
                    $fColName = $fName;
                    $strOldContent = isset($oldContents[$fColName]) ? $oldContents[$fColName] : "-";
                    $strContent = isset($newContents[$fColName]) ? $newContents[$fColName] : "-";
                    $content .= ("<td>");
                    $content .= ($strContent);
                    $content .= ("</td>");
                }
                $content .= ("<td>");
                $content .= ($row->dtime);
                $content .= ("</td>");
                $content .= ("<td>");
                $content .= ($row->oleh_name);
                $content .= ("</td>");
                $content .= ("</tr>");
            }

            $content .= ("</table>");
            $content .= ("</div class='table-responsive'>");
        }
        else {
            $content .= ("<div class='alert alert-warning'>");
            $content .= ("entri ini tidak memiliki riwayat");
            $content .= ("</div class='alert alert-warning'>");
        }
        echo $content;
    }

    public function doApproveFrom()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model($className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->db->trans_start();

        $this->load->model("MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));
        // print_r($tmpContent);
        // die();
        if ($origID > 0) { //===edit
            $where = array(
                //                /*$indexFieldName =>*/ "id" => $origID,
                "id" => $origID,
            );
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->updateData($where, $tmpContent, $o->getTableName());
            $this->session->errMsg = "Data telah diperbarui";

            //<editor-fold desc="data history / approve">
            $this->load->model("MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"     => $origID,
                "mdl_name"    => $className,
                "mdl_label"   => get_class($this),
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "new_content" => base64_encode(serialize($tmpContent)),
                "label"       => "approved",
                "oleh_id"     => $this->session->login['id'],
                "oleh_name"   => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }
        else { //===new data
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            unset($tmpContent["id"]);
            $insertID = $o->addData($tmpContent, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            $this->session->errMsg = "Data telah disimpan";

            //<editor-fold desc="data history / approve">
            $this->load->model("MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"     => $origID,
                "mdl_name"    => $className,
                "mdl_label"   => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($tmpContent)),
                "label"       => "approved",
                "oleh_id"     => $this->session->login['id'],
                "oleh_name"   => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }

        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

    public function doRejectFrom()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model($className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        //        die($selectedID."-".$origID);
        $this->db->trans_start();

        $this->load->model("MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        //$tmpContent = unserialize(base64_decode($tmp[0]->content));
        $rejectedContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));
        // print_r($tmpContent);
        // die();
        if ($origID > 0) { //===edit


            //===ambil data sebelumnya
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();

            $where = array(
                //                /*$indexFieldName =>*/ "id" => $origID,
                "id" => $origID,
            );
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->updateData($where, $tmpContent, $o->getTableName());
            $this->session->errMsg = "Pembaruan data telah ditolak dan dikembalikan ke data semula";

            //<editor-fold desc="data history / reject">
            $this->load->model("MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"     => $origID,
                "mdl_name"    => $className,
                "mdl_label"   => get_class($this),
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "new_content" => base64_encode(serialize($rejectedContent)),
                "label"       => "rejected",
                "oleh_id"     => $this->session->login['id'],
                "oleh_name"   => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>

        }
        else { //===new data
            // $tmpContent["status"]=1;
            // $tmpContent["trash"]=0;
            // unset($tmpContent["id"]);
            // $insertID = $o->addData($tmpContent, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            $this->session->errMsg = "Data baru telah ditolak";
            //<editor-fold desc="data history / reject">
            $this->load->model("MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"     => $origID,
                "mdl_name"    => $className,
                "mdl_label"   => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($rejectedContent)),
                "label"       => "rejected",
                "oleh_id"     => $this->session->login['id'],
                "oleh_name"   => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }

        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

}
