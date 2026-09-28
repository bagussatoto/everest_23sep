<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//ini_set('display_errors', 1);
//error_reporting(E_ALL);


class Data extends CI_Controller
{

    protected $searchString;

    private $allowView = false;
    private $allowCreate = false;
    private $allowEdit = false;
    private $allowDelete = false;
    private $allowViewHistory = false;

    private $creatorUsingApproval = false;
    private $updaterUsingApproval = false;
    private $deleterUsingApproval = false;

    private $relations = array();
    private $relationPairs = array();
    private $listedFields = array();

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

    public function setDeleterUsingApproval($deleterUsingApproval)
    {
        $this->deleterUsingApproval = $deleterUsingApproval;
    }

    public function __construct()
    {
        // cekMerah(url_segment());
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
        $this->load->library('pagination');
        $className = "Mdl" . $this->uri->segment(3);

        $this->load->library("MobileDetect");


        //region relation translator
        $this->relations = array();
        $this->relationPairs = array();
        if (file_exists(APPPATH . "models/Mdls/$className.php")) {
            $this->load->model("Mdls/" . $className);
            $o = new $className();
            $fields = $o->getFields();
            foreach ($fields as $fName => $f2Spec) {
                //                echo $f2Spec["label"]."-".$f2Spec["reference"]."<br>";
                if (isset($f2Spec['reference'])) {
                    //                    cekbiru("mendeteksi relasi milik $fName");
                    //if (array_key_exists($f2Spec['kolom'], $o->getListedFields())) {
                    $this->relations[$f2Spec['kolom']] = $f2Spec['reference'];
                    $this->load->model("Mdls/" . $f2Spec['reference']);
                    $o3 = new $f2Spec['reference']();
                    $tmp3 = $o3->lookupAll()->result();
                    //                    cekkuning($this->db->last_query());

                    if (sizeof($tmp3) > 0) {
                        //                        cekbiru("$fName ketemu data relasinya");
                        $mdlName = $f2Spec['kolom'];
                        $this->relationPairs[$mdlName] = array();
                        foreach ($tmp3 as $row3) {
                            $idxField = (null != $o3->getIndexFields()) ? $o3->getIndexFields() : "id";
                            $id = isset($row3->$idxField) ? $row3->$idxField : 0;
                            $name = isset($row3->nama) ? $row3->nama : "";
                            if (isset($row3->name)) {
                                $name = $row3->name;
                            }
                            $this->relationPairs[$mdlName][$id] = $name;
                        }
                    }
                    else {
                        //                        cekmerah("$fName TIDAK ketemu data relasinya");
                    }
                    //}

                }
            }
        }

        //endregion

        //                arrprint($this->relationPairs);die();
        $dataAccess = isset($this->config->item('heDataBehaviour')[$className]) ? $this->config->item('heDataBehaviour')[$className] : array(
            "viewers" => array(),
            "creators" => array(),
            "creatorAdmins" => array(),
            "updaters" => array(),
            "updaterAdmins" => array(),
            "deleters" => array(),
            "deleterAdmins" => array(),
            "historyViewers" => array(),
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

        $this->allowCreateApproval = false;
        $this->allowEditApproval = false;
        $this->allowDeleteApproval = false;
        foreach ($mems as $mID) {
            if (in_array($mID, $dataAccess['viewers'])) {
                $this->allowView = true;
            }
            if (in_array($mID, $dataAccess['historyViewers'])) {
                $this->allowViewHistory = true;
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

            if (in_array($mID, $dataAccess['creatorAdmins'])) {
                $this->allowCreateApproval = true;
            }
            if (in_array($mID, $dataAccess['updaterAdmins'])) {
                $this->allowEditApproval = true;
            }
            if (in_array($mID, $dataAccess['deleterAdmins'])) {
                $this->allowDeleteApproval = true;
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


        // arrprint($this->relationPairs);

        //---init listed-fields
        $className = "Mdl" . $this->uri->segment(3);
        // $className = $this->uri->segment(3);
        // cekHijau("$className");
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $this->listedFields = $o->getListedFields();

        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $this->listedFields = $o->getCompactListedFields();
        }

        //        arrprint($this->relationPairs);


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

        //        cekBiru($className);
//        arrPrint($_SESSION);
        $ctrlName = $this->uri->segment(3);

        /* -------------------------------------
         * auto select saat sudah memilih pihak
         * --------------------------------*/
        $cCode = isset($_GET['cCode']) ? $_GET['cCode'] : "";
//        $pihakId = isset($_GET['pihakId']) ? $_GET['pihakId'] : "";
        $pihakId = isset($_GET['reqVal']) ? $_GET['reqVal'] : "";
        if ($pihakId > 0) {
            $pihakID = $pihakId;
        }
        else {
            $pihakID = isset($_SESSION[$cCode]['main']['pihakID']) ? $_SESSION[$cCode]['main']['pihakID'] : "";
        }
        //---------------------------------------------
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
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $f = new MyForm($o, "add", array(
            "id" => "f1add_" . $className,
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/addProcess/$ctrlName",
            "target" => "result",
            "class" => "form-inline",
        ));
        /* -----------------------------------------------
      * selected value pada comco
      * ------ --------------------------------------*/
        // cekKuning($className);
        $fields = $o->getFields();
        // arrPrint($fields);
        $xxx = array();
        foreach ($fields as $keyField => $field2) {
            $defaultValue = isset($field2['defaultValue']) ? $field2['defaultValue'] : "";
            if (isset($field2['defaultValue'])) {
                $refe = isset($field2['reference']) ? $field2['reference'] : "";
                if (strlen($refe) > 3) {

                    $xxx[$refe][0] = (object)array($field2['defaultValue'] => $pihakID);
                }
            }
            // else{
            //     $xxx = array();
            // }
        }
        // $xxx[$refe][0] = (object)array($field2['defaultValue'] => $pihakID);
        // arrPrint($xxx);
        // ----------------------------------------

        $f->openForm(base_url() . get_class($this) . "/addProcess/$ctrlName");
        $f->fillForm($className, $xxx);
        $f->closeForm();

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Penambahan Data $title", "application/template/lte/index.html");

        //        $content .= ($f->getContent());


        $content .= "<div class='panel panel-success'>";
        $content .= "<div class='panel-heading'>";

        $content .= "<span class='text-blue text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";

        $content .= "<div class='panel-body'>";

        $content .= ($f->getContent());

        if ($this->creatorUsingApproval) {
            $content .= ("<div class='panel-body'>");
            $content .= ("<div class='alert alert-warning-dot text-center'>");
            $content .= ("This action will require approval");
            $content .= ("</div>");
            $content .= ("</div class='panel-body'>");
        }
        $content .= "</div>";


        // $content .= "</div>";
        // $content .= "</div>";
        $content .= "</div>";


        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content" => $content,
        );
        echo $content;
        die();
        //        $this->load->view('data', $data);

    }

    public function edit()
    {

        $jsBottom = "";
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
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
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        if ($selectedID > 0) {
            $tmp = $o->lookupByIdOnly($selectedID)->result();
        }
        else {
            $tmp = $o->lookupByCondition(array(
                "id" => $selectedID,
            ))->result();
        }
        // showLast_query("lime");
        $f = new MyForm($o, "edit", array(
            "id" => "f1ed_" . $className,
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/editProcess/$ctrlName/" . $selectedID,
            "target" => "result",
            "class" => "form-horizontal",
        ));
        $f->openForm(base_url() . get_class($this) . "/editProcess/$ctrlName/" . $selectedID);
        $f->fillForm($className, $tmp);
        $f->closeForm();
//        cekHere($selectedID);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");

        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]) ? $this->config->item('dataExtRelation')[$className] : array();
        //arrPrint($dataExtRel);
        //cekHitam($className);

        $content .= "<div class='panel panel-danger'>";
        $content .= "<div class='panel-heading'>";
        $content .= "<span class='text-blue no-padding text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";

        $content .= "<div class='panel-body'>";
        if ($this->updaterUsingApproval) {
            $content .= "<div class='alert alert-warning-dot text-center'>";
            $content .= ("This modification requires approval and this entry will be deactivated until being approved<br>");
            $content .= ("</div class='panel-body'>");
        }
        $content .= ($f->getContent());
        $content .= "</div>";
        $content .= "</div>";

        // $content .= "<div class='row'>";
        // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";

        if (sizeof($dataRel) > 0) {
            $content .= "<div class='panel panel-info'>";

            // $content .= "<div class='row panel panel-default' style='background:#f0f0f0;'>";
            // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";
            $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
            foreach ($dataRel as $mdlName => $mSpec) {
                $tmpDataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers" => array(),
                    "creators" => array(),
                    "creatorAdmins" => array(),
                    "updaters" => array(),
                    "updaterAdmins" => array(),
                    "deleters" => array(),
                    "deleterAdmins" => array(),
                );
                $allowView = false;
                $allowCreate = false;
                $allowEdit = false;
                $allowDelete = false;
                foreach ($mems as $mID) {
                    if (in_array($mID, $tmpDataAccess['viewers'])) {
                        $allowView = true;
                    }
                    if (in_array($mID, $tmpDataAccess['creators'])) {
                        $allowCreate = true;
                    }
                    if (in_array($mID, $tmpDataAccess['updaters'])) {
                        $allowEdit = true;
                    }
                    if (in_array($mID, $tmpDataAccess['deleters'])) {
                        $allowDelete = true;
                    }
                }


                $relations = array();
                $relationPairs = array();
                if (file_exists(APPPATH . "models/Mdls/$mdlName.php")) {
                    $this->load->model("Mdls/" . $mdlName);
                    $o = new $mdlName();
                    $fields = $o->getFields();
                    foreach ($fields as $f2Spec) {
                        if (isset($f2Spec['reference'])) {
                            if (array_key_exists($f2Spec['kolom'], $o->getListedFields())) {
                                $relations[$f2Spec['kolom']] = $f2Spec['reference'];
                                $this->load->model("Mdls/" . $f2Spec['reference']);
                                $o3 = new $f2Spec['reference']();
                                $tmp3 = $o3->lookupAll()->result();

                                if (sizeof($tmp3) > 0) {
                                    $mdlName2 = $f2Spec['kolom'];
                                    $relationPairs[$mdlName2] = array();
                                    foreach ($tmp3 as $row3) {
                                        $id = isset($row3->id) ? $row3->id : 0;
                                        $name = isset($row3->nama) ? $row3->nama : "";
                                        $relationPairs[$mdlName2][$id] = $name;
                                    }
                                }
                            }
                        }
                    }
                }


                $mdlLink = base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $mdlName) . "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID;
                $content .= "<div class='panel-heading'>";
                $content .= "<span class='text-blue text-uppercase'>";
                $content .= "<a href='$mdlLink'>";
                $content .= "<span class='fa fa-folder-open'></span> " . $mSpec['label'] . " <span class='meta'>selengkapnya klik disini</span>";
                $content .= "</a>";

                if ($allowCreate) {
//                    $relPihak = "&cCode=_TR_466&pId=yes";
                    $relPihak = "&pihakId=$selectedID&pId=yes";
                    $addLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $mdlName);
                    $addLink .= "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID . $relPihak;

                    $addClick = "
                                BootstrapDialog.show(
                                    {
                                        title:'New " . $mSpec['label'] . "',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                    }
                                );";
                    $content .= "<span class='pull-right'>";
                    $content .= "<a class=\" btn btn-default btn-xs\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new " . $mSpec['label'] . "' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></a>";
                    $content .= "</span>";
                }
                $content .= "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body'>";
                $this->load->model("Mdls/" . $mdlName);

                $o2 = new $mdlName();
                $o2->addFilter($mSpec['targetField'] . "='$selectedID'");
                $tmpo2 = $o2->lookupAll()->result();
                $content .= "<table class='table table-condensed'>";
                if (sizeof($tmpo2) > 0) {
                    $content .= "<tr bgcolor='#f0f0f0'>";
                    foreach ($o2->getListedFields() as $fName => $label) {
                        $content .= "<td>$label</td>";
                    }
//                    $content .= "<td>*</td>";
                    $content .= "</tr>";
                    foreach ($tmpo2 as $row) {
                        $content .= "<tr>";
                        foreach ($o2->getListedFields() as $fName => $label) {
                            $content .= "<td>";
                            if (array_key_exists($fName, $relations)) {
                                $fieldLabel = isset($relationPairs[$fName][$row->$fName]) ? $relationPairs[$fName][$row->$fName] : "unknown rel";
                            }
                            else {
                                $fieldLabel = $row->$fName;
                            }
                            $content .= $fieldLabel;
                            $content .= "</td>";
                        }
//                        $content .= "<td>-</td>";
                        $content .= "</tr>";
                    }

                }


                $content .= "</table class='table table-condensed'>";
            }

            // $content .= "</div>";
            $content .= "</div>";
            $content .= "</div>";
        }
        // $content .= "</div>";

        /*-------------------------------------
         * editor dalam iframe
         * -----------------------------------*/
        if (sizeof($dataExtRel) > 0) {
            $num = 0;
            foreach ($dataExtRel as $mSpec) {
                $num++;
                $content .= "<div class='panel panel-default' style='background:#f0f0f0;'>";
                // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";
                $content .= "<div class='panel-heading'>";
                // $content .= "<h5 class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</h5>";
                $content .= "<span class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body'>";
                $mSpec['target'];
                $backLink = blobEncode(current_url());
                $iframeLink = base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink";
                // cekHere("$iframeLink");
                //                $content .= "<div id='$selectedID$num' frameborder='0'  style='width:100%;height:350px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:scroll;'>";
                //                $content .= "</div>";
                //                $content .= "<script> $('#$selectedID$num').load('" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink'); </script>";

                $frameID_target = "result2_$num";
                $content .= "<iframe id='$frameID_target' frameborder='0' width=100% height=100% style='width:100%;height:500px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:hidden;' src='" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink&show=1&iframe=$frameID_target'>";
                $content .= "</iframe>";
                if (show_debuger() == 1) {
                    $content .= "<a href='javaScript:void(0);' onclick=\"window.open('$iframeLink&dock=1','mywin','width=1000,height=600');\">open New Window</a>";
                }

                $content .= "</div>"; // body
                $content .= "</div>"; // panel
            }
        }

        // $content .= "</div class='col-lg-12 col-md-12 col-sm-12'>";
        // $content .= "</div class='row'>";

        $arrSpecs = array(
            "mdlName" => "$className",
            "mainLabel" => ucwords($ctrlName),
            "images" => array(),
            "parent_id" => $selectedID,
        );

        $jsBottom .= "

        function createQr(container, value, w='80',h='80'){
            var qrcode = new QRCode(container, {
                text: value, width: w, height: h,
                colorDark : '#000000',
                colorLight : '#ffffff',
                correctLevel : QRCode.CorrectLevel.H
            });
        }

        function testuing(barangam){ top.console.log('" . json_encode($arrSpecs) . "'); top.console.log(barangam)}

        var fname;
        var label;

        function tutorialQrCode(fname,label){
            Sweetalert2({
                title: 'CARA MENGGUNAKAN',
                html: `<div><img height='200' class='thumbnail' id='bc_tutorial'></div>`,
                confirmButtonText: 'Saya Mengerti',
                onOpen: ()=>{
                    $('#bc_tutorial').attr('src', 'https://s27389.pcdn.co/wp-content/uploads/2019/10/retail-innovation-changing-tech-consumer-employee-demands-1024x440.jpeg');
                }
            }).then( (result) => {
                if(result){
                    uploadFromSmartphone(fname,label);
                }
            });


        }

        function uploadFromSmartphone(fname,label){

            var arr_label = JSON.parse('{ \"key\":\"'+fname+'\", \"label\":\"'+label+'\"}');
            var arr_specs = " . json_encode($arrSpecs) . ";
                arr_specs = Object.assign(arr_label, arr_specs);
            var dateGenerator = new Date();
            var validQrBarcode = btoa(dateGenerator)+'_sanQR';
            Sweetalert2({
                title: 'upload your '+label+' from smartphone',
                html: `<div class='image-container' id='qrcode_container'></div><div class='text-green text-center text-bold' id='connection'></div>`,
                onOpen: ()=>{
                    createQr('qrcode_container',validQrBarcode,200,200);
                    var callback = `doLoadImagesFromQR('`+validQrBarcode+`','`+fname+`','`+label+`')`;
                    registerNewQrCode(validQrBarcode,arr_specs, callback);
                }
            })
            .then( (result) => {
                if(result){
                    stopQRChecker(validQrBarcode);
                }
            });;
        }

        function removeSessionQR(code=''){
            $.ajax({
              url: \"" . base_url() . "Images/clearSessionCheckQR/\"+code,
              beforeSend: function( xhr ) {
                xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
              }
            })
              .done(function( data ) {
                    var parse = JSON.parse(data);
                    console.log(parse.description);
              });
        }

        function stopQRChecker(code=''){
            clearInterval(loadImagesFromQR);
            if(code!==''){
                 removeSessionQR(code);
            }
        }

        function registerNewQrCode(code='', arr_specs, callback){
            var specs = arr_specs;
            $.ajax({
              url: \"" . base_url() . "Images/registerNewQrCode/$selectedID/\"+code,
              method: 'post',
              data: specs,
            })
            .done( function(keluaran) {
                eval(callback)
            });
        }

        var loadImagesFromQR;
        var reloadLimit=0;
        var loadMS = 2000;

        function doLoadImagesFromQR(code='',fname,label) {
            clearInterval(loadImagesFromQR);
            loadImagesFromQR = setInterval( function(){
                console.log(loadMS + 'ms ' + code)
                console.log('fname: ' + fname)
                console.log('label: ' + label)
                $.ajax({
                  url: \"" . base_url() . "Images/checkQR/\"+code,
                  beforeSend: function( xhr ) {
                    xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
                  }
                })
                  .done(function( data ) {

                    if ( console && console.log ) {
                        var parseData = JSON.parse(data);
                        if(parseData.limit < 1){
                            stopQRChecker(code)

                            var append = '';
                                append += `<div class='after'>`;
                                append += `<span onclick='uploadFromSmartphone(\"`+fname+`\",\"`+label+`\")'><i class='fa fa-refresh'></i><div style='font-size: 12px'>expired<br>click here to reload</div></span>`;
                                append += '</div>';

                            $('#qrcode_container').append(append);
                        }
                        else{
                            console.log( data );
                            console.log( parseData.limit );
                            if( parseData.image_url == 0 ){

//                                                        if(parseData.connection==0){
//                                                            $('#connection').html('belum ada device terhubung');
//                                                        }
//                                                        else{
//                                                            $('#connection').html(parseData.connection);
//                                                        }

                            }
                            else{
                                Sweetalert2({
                                    title: 'image <b>'+label+'</b> siap diupload',
                                    html: `<img height='260' src='`+parseData.image_url+`'>`,
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Simpan Image'
                                }).then((result)=>{
                                    if(result){
                                        $.ajax({
                                          url: \"" . base_url() . "Images/saveMobile/\"+parseData.qrcode,
                                          beforeSend: function( xhr ) {
                                            xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
                                          }
                                        })
                                          .done(function( data ) {
                                                var ret = JSON.parse(data);
                                                if(ret.status == 'success'){
                                                    Sweetalert2('sukses', 'Image berhasil disimpan', 'success');
                                                    setTimeout( function(){ eval(ret.redirect) }, 1000);
                                                }
                                                else{
                                                    Sweetalert2('error', 'Image gagal disimpan, silahkan ulangi', 'error');
                                                    setTimeout( function(){ eval(ret.redirect) }, 1000);
                                                }
                                          });
                                    }
                                });

                                console.log(parseData.image_url);
                                stopQRChecker(code)
                            }
                        }
                    }
                  });
            }, loadMS*1 );
        }
        ";

        $data = array(
            "mode" => "barcodeView",
            "title" => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content" => $content,
            "jsBottom" => $jsBottom,
        );

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

        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
//arrPrint($dataExtRel);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = (object)unserialize(base64_decode($tmp[0]->content));

//arrPrint($tmpContent);

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");
        $f = new MyForm($o, "edit", array(
            "id" => "f1",
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target" => "result",
            "class" => "form-horizontal",
        ));
        $f->openForm(base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID");

        $content .= ("<table class='table table-condensed'>");
        $content .= ("<tr><td colspan='2' class='text-muted text-uppercase'><h4>data yang diajukan</h4></td></tr>");
        $ii = 0;
        foreach ($o->getFields() as $fName => $fSpec) {
            $fType = $fSpec['type'];
            $fInputType = $fSpec['inputType'];
            $fDataSource = isset($fSpec['dataSource']) ? $fSpec['dataSource'] : "";
            $fColName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $fLabel = isset($fSpec['label']) ? $fSpec['label'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$fLabel");
            $content .= ("</td>");
            $fieldLabel = isset($tmpContent->$fColName) ? $tmpContent->$fColName : "";
            //region terjemahan isi berdasat type data
            switch ($fType) {
                case "image":
                    $hasil = "<div class='thumbnail'>";
                    $styleImage = $fieldLabel !== '' ? "style='width: 35em'" : "style='width: 10em'";
                    $fieldLabel = $fieldLabel !== '' ? $fieldLabel : base_url() . "assets/images/img_blank.gif";
                    $hasil .= "<img src='$fieldLabel' class='img-responsive ($fieldLabel)' $styleImage >";
                    $hasil .= "<div class='caption'>";
                    $hasil .= "</div>";
                    $hasil .= "</div>";
                    $fieldLabel = $hasil;
                    $conten_f = "$fieldLabel";
                    break;
                case "blob":
                case "longbloob":
                case "mediumblob":
                    $isiBlop = $fieldLabel != null ? blobEncode($fieldLabel) : "";
                    if (is_array($isiBlop)) {
                        $hasil = "";
                        if (array_key_exists("image", $isiBlop)) {
                            $images = base64_encode($isiBlop["image"]);
                            $hasil = "<div class='thumbnail'>";
                            $hasil .= "<img src='$images' class='img-responsive' width='150px'>";
                            $hasil .= "<div class='caption'>";
                            $hasil .= "</div>";
                            $hasil .= "</div>";
                        }
                        else {
                            foreach ($isiBlop as $kBlop) {
                                $var = $fDataSource[$kBlop];
                                if ($hasil == "") {
                                    $hasil .= "$var";
                                }
                                else {
                                    $hasil = "$hasil, " . "$var";
                                }
                            }
                        }
                        $fieldLabel = $hasil;
                    }
                    $conten_f = "$fieldLabel";
                    break;
                case "password":
                    $fieldLabel = "*********";
                    $conten_f = "<span class='form-control'>$fieldLabel</span>";
                    break;
                default:
                    $conten_f = "<span class='form-control'>$fieldLabel</span>";
                    break;
            }
            //endregion
            //===if related
            if (array_key_exists($fColName, $this->relations)) {
                $fieldLabel = isset($this->relationPairs[$fColName][$fieldLabel]) ? "<span class='fa fa-folder-o' style='color:#ff7700;'></span> " . $this->relationPairs[$fColName][$fieldLabel] : "unknown rel";
            }
            $fContent = $fieldLabel;
            $disabled = isset($tmpContent->$fColName) ? "readonly" : "disabled";
            $content .= ("<td>");
            $content .= ("$conten_f");
            $content .= ("</td>");
            $content .= ("</tr>");
        }

        if (sizeof($dataExtRel) > 0) {

            if (isset($tmpContent->images)) {
                $content .= ("<tr>");
                $content .= ("<td class='text-muted'>Add Images");
                $content .= ("</td>");
                $fieldLabel = isset($tmpContent->images) ? $tmpContent->images : "";
                $hasil = "<div class='thumbnail'>";
                $styleImage = $fieldLabel !== '' ? "style='width: 35em'" : "style='width: 10em'";
                $fieldLabel = $fieldLabel !== '' ? $fieldLabel : base_url() . "assets/images/img_blank.gif";
                $hasil .= "<img src='$fieldLabel' class='img-responsive ($fieldLabel)' $styleImage >";
                $hasil .= "<div class='caption'>";
                $hasil .= "</div>";
                $hasil .= "</div>";
                $fieldLabel = $hasil;
                $conten_f = "$fieldLabel";

                $content .= ("<td>");
                $content .= ("$conten_f");
                $content .= ("</td>");
                $content .= ("</tr>");

            }

        }


        $addRows = array(
            "proposal type" => $tmp[0]->propose_type,
            "tgl. diajukan" => formatTanggal($tmp[0]->proposed_date),
            "oleh" => $tmp[0]->proposed_by_name,
            "ID data asli" => $tmp[0]->orig_id,
        );
        $content .= ("<tr><td colspan='2' class='text-muted'>&nbsp;</td></tr>");
        $content .= ("<tr><td colspan='2' class='text-muted text-uppercase'><h4>informasi pengajuan</h4></td></tr>");
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

        $viewButton = false;
        switch ($tmp[0]->propose_type) {
            case "add":
            case "edit":

                $yesAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doApproveFrom/$ctrlName/$selectedID/$origID');";
                $noAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doRejectFrom/$ctrlName/$selectedID/$origID');";
                if ($origID > 0) {
                    $rejectAlertMsg = "rejecting this proposal will activate the previous data entry";
                    $approveAlertMsg = "approving this proposal will turn the contents in this proposal into current active data";
                    $yesLabel = "proceed to modify this entry";
                    $noLabel = "reject data modification";
                }
                else {
                    $rejectAlertMsg = "this proposal will be deleted (instead of the original data)";
                    $approveAlertMsg = "contents within this proposal will be set as new active data";
                    $yesLabel = "proceed to add this entry";
                    $noLabel = "reject data addition";
                }
                $viewButton = (($this->allowEditApproval == true) || ($this->allowCreateApproval == true)) ? true : false;
                break;
            case "delete":
                $yesAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID');";
                $noAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID');";
                $rejectAlertMsg = "this deletion proposal will be ignored and related data will be set as active";
                $approveAlertMsg = "data entry related to this proposal will be DELETED";
                $yesLabel = "delete anyway";
                $noLabel = "dont delete";
                $viewButton = $this->allowDeleteApproval == true ? true : false;
                break;
        }

        $content .= ("<div class='row'>");
        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-danger btn-block' href='javascript:void(0)' onClick =\"if(confirm('$rejectAlertMsg \\nContinue?')==1){$noAction}\">$noLabel</a>");
        $content .= ("</div class='col-sm-6'>");
        if ($viewButton == true) {

            $content .= ("<div class='col-sm-6'>");
            $content .= ("<a class='btn btn-success btn-block' href='javascript:void(0)' onClick =\"if(confirm('$approveAlertMsg \\nContinue?')==1){$yesAction}\">$yesLabel</a>");
            $content .= ("</div class='col-sm-6'>");
            $content .= ("</div class='row'>");
        }

        $f->closeForm();

        //$content .=("<div class='panel panel-default'>");
        //$content .=("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;'>");
        $content .= ($f->getContent());
        //$content .=("</div>");

        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content" => $content,
        );

        echo $content;
        die();
        //        $this->load->view('data', $data);
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
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = (object)unserialize(base64_decode($tmp[0]->content));
        $title = isset($this->config->item('lgMenuLabel')[get_class($this)]) ? $this->config->item('lgMenuLabel')[get_class($this)] : get_class($this);
        $p = new Page($title, "Ubah Data $title", $pageTemplate);
        $f = new MyForm($o, "edit", array(
            "id" => "f1",
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target" => "result",
            "class" => "form-horizontal",
        ));
        $f->openForm(base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID");

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
            "oleh" => $tmp[0]->proposed_by_name,
            "ID data asli" => $tmp[0]->orig_id,
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

        $content .= ($f->getContent());

        echo $content;
        die();

    }

    public function addProcess()
    {

        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>saving data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );

        echo swalAlert($arrAlert);

        $content = "";
        //==menyimpan inputan data baru ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)

        $className = "Mdl" . $this->uri->segment(3);
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);

        $mainObj = $o = new $className;
        $f = new MyForm($o, "addProcess");

        $inserted = array();
        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                    die(lgShowAlert($errMsg));
                }
            }

            $this->db->trans_start();
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (isset($spec['inputType'])) {
                    // cekMerah($spec['inputType']);
                    switch ($spec['inputType']) {
                        case "checkbox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
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
                            if ($_FILES[$fName]['size'] > 0) {
                                //                                $image["image"] = file_get_contents($_FILES[$fName]['tmp_name']);
                                //                                $data[$fName] = blobEncode($image);
                                //
                                //                                                                    arrPrint($data);
                                //                                    die();

                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
//                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'file' => "@" . $realpath . ";filename=" . $_FILES[$fName]['name'] . ";type=" . $_FILES[$fName]['type'],
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));

                                curl_close($request);


                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    //                                    $imagesBlob["files"] = $cUrl_result->full_url;
                                    //                                    $dataLast = array_replace($data, $imagesBlob);
                                    $data[$fName] = $cUrl_result->full_url;
//
//                                                                        arrPrint($data);
//                                                                        die();
                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }

                            }
                            else {
                                cekHEre("$fName no image");
                                $data[$fName] = "";
                            }
                            break;
                        case "image":
                            if ($_FILES[$fName]['size'] > 0) {
// arrPrint($_FILES[$fName]);
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                // cekMErah(cdn_upload_images());
                                $fields = [
                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                // echo ($cUrl_result);
                                curl_close($request);


                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                }
                                else {
                                    echo "<script>top.Sweetalert2('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die(__LINE__);
                                }

                            }
                            else {
                                cekHEre("$fName no image");
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":

                            break;
                        case "textarea":
//                            $data[$fName] = nl2br($this->input->post($fName));
                            $data[$fName] = $this->input->post($fName);
//                            print_r($data);
//                            matiHere("hiksss");
                            break;
                        default:
                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
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

                if (isset($spec['strField'])) {
                    if (isset($spec["reference"])) {

                        $this->load->model("Mdls/" . $spec["reference"]);

                        $idnya = $this->input->post($spec["kolom"]);

                        $tmpRe = new $spec["reference"]();

                        // arrPrint($idnya . " " . $spec["reference"]);
                        // cekHitam();
                        $tmpFields = $tmpRe->lookupByID($idnya)->result();
                        $strField = $tmpFields[0]->$spec["strField"];
                        // showLast_query("lime");
                        arrPrint($tmpFields);
                        // arrPrint($spec);
                        arrPrint($spec['strField']);
                        // cekHere();
                        $data[$spec["kolom_nama"]] = $strField;
                    }
                }
            }
//
//            cekHere(__LINE__);
//            arrPrintPink($_POST);
//            arrPrintWebs($data);
//            matiHEre("LALALA");
            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
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
            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
            $tmpData = array(
                "mdl_name" => $className,
                "mdl_label" => $ctrlName,
                "proposed_by" => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date" => dtimeNow(),
                "content" => blobEncode($data),
            );
//mati_disini(sizeof($o->getValidateData()));
            $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
            $tmpOrig = array();
            if (sizeof($validateDataFields) > 0) {
                $where = array();
                foreach ($validateDataFields as $fieldsValidate) {
                    $where[$fieldsValidate] = $data[$fieldsValidate];
                }
                $tmpOrig = $o->lookupByCondition($where)->result();
                showLast_query("lime");
                arrPrint($tmpOrig);
                $bNama = $tmpOrig[0]->biaya_nama;
                $bProduk = $tmpOrig[0]->produk_nama;
                $bProdukId = $tmpOrig[0]->produk_id;
            }


            if (sizeof($tmpOrig) > 0) {
                cekHere(":: HAHAHA ");
                if ($bProdukId > 0) {
                    $where2 = array("produk_id" => $bProdukId);
                }
                else {
                    $where2 = array();
                }
                $tmpOrig2 = $o->lookupByCondition($where2)->result();
                showLast_query("biru");
                arrPrint($tmpOrig2);

                $hasil = "";
                $hasil .= "$bNama  already set up<br>";
                foreach ($tmpOrig2 as $itemOrigs) {
                    $bNama2 = $itemOrigs->biaya_nama;
                    $bNilai2 = formatField("harga", $itemOrigs->nilai);

                    foreach ($o->getListedFieldsView() as $val) {
                        $bNama2 = $itemOrigs->$val;
                        $bNilai2 = isset($itemOrigs->nilai) ? formatField("harga", $itemOrigs->nilai) : "";
                        $var = "$bNama2 <span>$bNilai2</span>";
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }


                }

                $bJudul = "$bProduk";
                $alerts = array(
                    "type" => "warning",
                    "title" => $bJudul,
                    "html" => $hasil,
                );
                echo swalAlert($alerts);
                echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                die();
                matiHere("data $bNama  already exist on $bProduk, no data change<hr>");
                //udah ada data ngapain ditambah lagi dengan id sama.....
            }

            if ($this->creatorUsingApproval) {
                cekHere("approval");
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                $this->session->errMsg = "Data proposal has been saved and pending approval";
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id" => 0,
                    "mdl_name" => $className,
                    "mdl_label" => get_class($this),
                    "old_content" => "",
                    "new_content" => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label" => "proposed",
                    "oleh_id" => $this->session->login['id'],
                    "oleh_name" => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                cekHitam($this->db->last_query());
            }
            else {

                $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
                arrPrint($validateDataFields);
                cekHijau("validasi");
                $tmpOrig = array();
                if (sizeof($validateDataFields) > 0) {
                    $where = array();
                    foreach ($validateDataFields as $fieldsValidate) {
                        $where[$fieldsValidate] = $data[$fieldsValidate];
                    }
                    $tmpOrig = $o->lookupByCondition($where)->result();
                }


                if (sizeof($tmpOrig) > 0) {
                    matiHere("data already exist, no data change");
                    //udah ada data ngapain ditambah lagi dengan id sama.....
                }
                $mainInsertId = $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError(__LINE__ . " Gagal menulis data", __FILE__));
//                $mainInsertId = $insertID = $o->addData($data, $o->getTableName());
//showLast_query("biru");
//cekBiru("-- $mainInsertId = $insertID --");

                $this->session->errMsg = "Data contents have been saved";
                $inserted["id"] = $insertID;
                if (method_exists($o, "paramSyncNamaNama")) {
                    cekHitam("ada pram nama nama");
                    $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                    foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                        $id_ygdisync = isset($data[$syncNamaNamaParams['id']]) ? $data[$syncNamaNamaParams['id']] : "";
                        // $o->setTokoId(my_toko_id());
                        if ($id_ygdisync > 0) {
                            $o->syncNamaNama($id_ygdisync);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            if ($syncNamaNamaMdl == "MdlCountry") {
                                $o->syncNamaNama($id_ygdisync, $insertID);
                                cekBiru($this->db->last_query());
                            }
                            cekBiru("fale $syncNamaNamaMdl");
                        }
                    }
                    // matiHere(__LINE__);
// cekBiru($id_ygdisync);
                    // $o->syncNamaNama();
                }
                else {
                    cekHitam("gak aada pram nama nama");
                }
                // matiHEre();
                // cekHitam($this->db->last_query());
                //                cekHitam($insertID);
                $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $insertID . "";
                $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $ctrlName ',
                                            size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

                $this->session->errMsg .= "<br><a href='javascript:void(0)' onclick=\"$editClick\">view entry</a>";

                if (isset($this->config->item("dataExtended")[$className])) {
                    createAccessData($this->input->post('membership'), $insertID);
                }


                //region takbahan Dcom
                if (sizeof($dcomConf) > 0) {
                    $inParam = array_merge($inserted, $data);
                    $className = "DCom" . $dcomConf;
                    $this->load->Model("DComs/" . $className);
                    $d = new $className();
                    $d->setWriteMode("insert");
                    //                $d->pair($inParam);
                    $d->pair($inParam) or die("Tidak berhasil memasang  values pada dcom-processor: $className/" . __FUNCTION__ . "/" . __LINE__);
                    $gotParams = $d->exec();
                    //                cekMerah("ayok dcom");
                }
                //endregion

                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id" => 0,
                    "mdl_name" => $className,
                    "mdl_label" => get_class($this),
                    "old_content" => "",
                    "new_content" => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label" => "applied",
                    "oleh_id" => $this->session->login['id'],
                    "oleh_name" => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

                /* ---------------------------------------------------------------
                 * tested pada auto COA yg pakai aaproval masuk di doApproveFrom
                 * --------------------------------------------------------------*/
                if (method_exists($mainObj, "getConnectingData")) {
                    $nama = ucwords($data['nama']);
                    $negara = isset($data['country']) ? $data['country'] : "";
                    $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                    $my_name = my_name();

                    // cekBiru($negara . " $extern_tipe");
                    $connectings = $mainObj->getConnectingData();
                    foreach ($connectings as $model => $param_connecting) {
                        $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                        $this->load->model($param_connecting['path'] . "/$model");
                        $connObj = new $model();
                        // $strHead_code = isset($param_connecting['staticOptions'][$extern_tipe]) ? $param_connecting['staticOptions'][$extern_tipe] : matiHere("parameter");
                        if (isset($param_connecting['staticOptions'])) {

                            $strHead_code = is_array($param_connecting['staticOptions']) ? $param_connecting['staticOptions'][$extern_tipe] : $param_connecting['staticOptions'];
                        }
                        else {
                            mati_disini("static optionnya tolong dikasih");
                        }
                        $datas = array();

                        foreach ($fields as $field => $cfParams) {

                            if (isset($cfParams['var_main'])) {
                                $cNilai = $$cfParams['var_main'];
                            }
                            else {
                                $cNilai = $cfParams['str'];
                            }

                            $datas[$field] = $cNilai;
                        }


                        /* -------------------------------------------------
                         * menulis ke table connecting
                         * -------------------------------------------------*/
                        $lastInset_code = $connObj->$param_connecting['fungsi']($strHead_code, $datas);
                        showLast_query("merah");
                        //                        mati_disini("hahaha -- $strHead_code -- ");

                        /* -------------------------------------------------
                         * ngupdate ke data utama
                         * -------------------------------------------------*/
                        if (isset($param_connecting['updateMain'])) {

                            foreach ($param_connecting['updateMain']['condites'] as $key => $condite) {
                                $mainCondites[$key] = $$condite;
                            }
                            foreach ($param_connecting['updateMain']['datas'] as $key => $val) {
                                $mainUpdate[$key] = $$val;
                            }

                            $mainObj->updateData($mainCondites, $mainUpdate);
                            showLast_query("orange");
                        }

                        cekHitam($lastInset_code);
                    }

                    // arrPrint($connecting);
                }
                //    -------------------------------------------------------------
            }

//            matiHere("hoop ----DONE---- belom commit");
            $this->db->trans_complete();
            echo "<script>top.location.reload();</script>";

        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function editProcess()
    {

        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Saving your data, please wait..<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,

        );

        $content = "";
        //==menyimpan inputan perubahan data ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $this->db->trans_start();

        $postProcs = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className] : array();
        $indexFieldName = "id";
        $f = new MyForm($o, "editProcess");

        arrPrint($this->input->post());

        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                    //lolos
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                    die(lgShowAlert($errMsg));
                }
            }

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
//                            $data[$fName] = md5($this->input->post($fName));
                            $data[$fName] = strlen($this->input->post($fName)) > 24 ? $this->input->post($fName) : md5($this->input->post($fName));
                            break;
                        case "file":

                            if ($_FILES[$fName]['size'] > 0) {
                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
//                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'file' => "@" . $realpath . ";filename=" . $_FILES[$fName]['name'] . ";type=" . $_FILES[$fName]['type'],
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));
                                curl_close($request);
                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;


                                    arrPrint($data);
                                    die();

                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }
                            }
                            else {
                                if ($this->input->post($fName)) {
                                    //                                    $image["image"] = base64_decode($this->input->post($fName));
                                    //                                    $newFile = blobEncode($image);
                                    $newFile = $this->input->post($fName);
                                }
                                else {
                                    $newFile = "";
                                }
                                $data[$fName] = $newFile;
                            }

                            break;
                        case "image":
                            if ($_FILES[$fName]['size'] > 0) {

                                $request = curl_init(cdn_upload_images());
                                $realpath = realpath($_FILES[$fName]['tmp_name']);
                                curl_setopt($request, CURLOPT_POST, true);
                                $fields = [
                                    'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                                    'server_source' => $_SERVER['HTTP_HOST'],
                                ];
                                curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
                                curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
                                $cUrl_result = json_decode(curl_exec($request));

                                curl_close($request);

                                if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                                    $data[$fName] = $cUrl_result->full_url;
                                }
                                else {
                                    echo "<script>top.Swal.fire('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }

                            }
                            else {
                                cekHEre("$fName no image");
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        default:
                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
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
                "id" => $data['id'],
            );

            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
            if ($this->updaterUsingApproval) {
                $data['trash'] = 0;
            }
            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
                }
            }

            if (method_exists($o, "getListedUnsetFields")) {
                if (sizeof($o->getListedUnsetFields())) {
                    foreach ($o->getListedUnsetFields() as $val) {
                        if (array_key_exists($val, $this->input->post())) {
                            cekHere("meng NULL kan -> $val");
                            $data[$val] = NULL;
                        }
                    }
                }
            }


            $tmpData = array(
                "orig_id" => $data['id'],
                "mdl_name" => $className,
                "mdl_label" => $ctrlName,
                "proposed_by" => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date" => date("Y-m-d H:i:s"),
                "content" => blobEncode($data),
            );

            if ($this->updaterUsingApproval) {
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                $this->session->errMsg = "Data proposal has been saved and pending approval";
                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $data['id'],
                ))->result();
                $o->setFilters(array());
                $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
                $this->load->model("Mdls/" . "MdlDataHistory");
                //                arrPrint($data);
                //                die();
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id" => $data['id'],
                    "mdl_name" => $className,
                    "mdl_label" => get_class($this),
                    "old_content" => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content" => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label" => "proposed",
                    "oleh_id" => $this->session->login['id'],
                    "oleh_name" => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            }
            else {
                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $data['id'],
                ))->result();
                $o->setFilters(array());
                $o->updateData($where, $data, $o->getTableName());
                cekOrange($this->db->last_query() . " @" . __LINE__);

                $this->session->errMsg = "Data has been updated";
                //arrPrint($this->config->item("dataExtended"));
                if (isset($this->config->item("dataExtended")[$className])) {
                    createAccessData($this->input->post('membership'), $data['id'], "false");
                }

                if (method_exists($o, "paramSyncNamaNama")) {
                    $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                    foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                        $id_ygdisync = isset($data[$syncNamaNamaParams['id']]) ? $data[$syncNamaNamaParams['id']] : "";
                        // $o->setTokoId(my_toko_id());
                        if ($id_ygdisync > 0) {
                            $o->syncNamaNama($id_ygdisync);
                        }
                    }
                    // matiHere(__LINE__);

                    // $o->syncNamaNama();
                }

//                arrPrint($postProcs);
                if (sizeof($postProcs) > 0) {
                    cekmerah("ada post-processors " . __FILE__ . " " . __LINE__);
                    foreach ($postProcs as $pp) {
                        $comName = "DCom" . $pp;
                        cekmerah("post-proc name: $pp / $comName");
                        $this->load->model("DComs/" . $comName);

                        $o2 = new $comName();
                        $o2->pair($data) or die(lgShowError($comName, "failed to pair the params of DCom"));
                        $o2->exec() or die(lgShowError($comName, "failed to execute DCom"));
                    }
                }
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id" => $data['id'],
                    "mdl_name" => $className,
                    "mdl_label" => get_class($this),
                    "old_content" => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content" => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label" => "applied",
                    "oleh_id" => $this->session->login['id'],
                    "oleh_name" => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            }
// matiHere("complittt $className");
            $this->db->trans_complete();
            echo "<script>top.location.reload();</script>";
        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in $err[fieldLabel]:  $err[errMsg]";
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
        $ctrlName = $this->uri->segment(3);
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
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $where = array("id" => $selectedID);

        $oldDataTmp = $o->lookupByID($selectedID)->result();

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $dTmp = new MdlDataTmp();
        $tmpData = array(
            "orig_id" => $selectedID,
            "mdl_name" => $className,
            "mdl_label" => $ctrlName,
            "proposed_by" => $this->session->login['id'],
            "proposed_by_name" => $this->session->login['nama'],
            "proposed_date" => date("Y-m-d H:i:s"),
            "propose_type" => "delete",
            "content" => base64_encode(serialize((array)$oldDataTmp[0])),
        );
        if ($this->deleterUsingApproval) {
            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
            $this->session->errMsg = "Your deletion proposal has been saved and pending approval";

            $tmpOrig = $o->lookupByCondition(array("id" => $selectedID))->result();
            $o->setFilters(array());
            $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
            $tmpNew = (array)$tmpOrig;
            $tmpNew["status"] = 0;
            $tmpNew["trash"] = 1;

            //<editor-fold desc="data history / propose">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $selectedID,
                "mdl_name" => $className,
                "mdl_label" => $ctrlName,
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($tmpNew)),
                "new_content_intext" => print_r($tmpNew, true),
                "label" => "delete_proposed",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
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
            $o->setFilters(array());
            $o->updateData($where, $data, $o->getTableName());
//            cekMerah($this->db->last_query());
            //</editor-fold>

            //<editor-fold desc="data history / approve">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $selectedID,
                "mdl_name" => $className,
                "mdl_label" => $ctrlName,
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($data)),
                "new_content_intext" => print_r($data, true),
                "label" => "deleted",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }

//                    matiHere("complittt $className");
        $this->db->trans_complete();


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
        //        $this->load->model("Mdls/"."MdlDataTmp");
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
        // arrPrint($this->uri->segment_array());
        $content = "";
        if (!isset($this->session->login['id'])) {
            gotoLogin();//remember last login
        }
        $mdlName = $className = "Mdl" . $this->uri->segment(3);

        $ctrlName = $this->uri->segment(3);
        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='$className'");
        $tmpTmp = $tData->lookupAll()->result();
//                cekHitam($this->db->last_query());
        $dataProposals = array();
        if (sizeof($tmpTmp) > 0) {
            foreach ($tmpTmp as $row) {
                $mdlName = $row->mdl_name;
                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers" => array(),
                    "creators" => array(),
                    "creatorAdmins" => array(),
                    "updaters" => array(),
                    "updaterAdmins" => array(),
                    "deleters" => array(),
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
                        "id" => $row->_id,
                        "label" => $row->mdl_label,
                        "origID" => $row->orig_id,
                        "proposer" => $row->proposed_by_name,
                        "date" => $row->proposed_date,
                        "content" => unserialize(base64_decode($row->content)),
                        "propose_type" => $row->propose_type,
                    );
                }
            }
        }

        //</editor-fold>

        /* ---------------------------------------------------------------
        * mendisable button delete direlasikan dengan data transaksi
        * pengaturan ditaruh di dataBehavior contoh penerapan ada pada key MdlProduk dan MdlCustomer
        * ---------------------------------------------------------------*/
        $dataBehavior = $this->config->item('heDataBehaviour');
        $paramMutasi = isset($dataBehavior[$mdlName]['rel_deleters']) ? $dataBehavior[$mdlName]['rel_deleters'] : array();
        $data_aktif = array();
        if (sizeof($paramMutasi) > 0) {
            $relDir = $paramMutasi['dirModel'];
            $relMdl = $paramMutasi['baseModel'];
            $relCondites = $paramMutasi['condites'];
            $relGrouping = $paramMutasi['grouping'];
            $relSelected = $paramMutasi['selecteds'];
            $relStrukture = $paramMutasi['data_strukture'];

            $this->load->model($relDir . $relMdl);
            $pc = new $relMdl();

            $this->db->select($relSelected);
            $this->db->group_by($relGrouping);
            $data_cache = $pc->lookupByCondition($relCondites)->result();
            // showLast_query("lime");

            // $data_aktif = array();
            foreach ($data_cache as $item) {
                foreach ($relStrukture as $itemKey => $itemValues) {
                    if (!is_array($itemValues)) {
                        $data_aktif[$item->$itemKey] = $item->$itemValues;
                    }
                    else {
                        $data_aktif[$item->$itemKey][$itemValues] = $item->$itemValues;
                    }
                }
            }

            // arrPrintPink($data_aktif);
            // cekHijau();
        }
        else {
            // cekMerah();
        }
        // ---------------------------------------------------------------

        // cekHitam(sizeof($dataProposals));
        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $subLabel = isset($this->config->item('heDataBehaviour')[$className]['sublabel']) ? $this->config->item('heDataBehaviour')[$className]['sublabel'] : "";
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();
        $arrExtImg = array();
        $badgeData = array();

        if (sizeof($dataExtRel) > 0) {
            $this->load->model("Mdls/MdlImages");
            $im = new MdlImages();
            $imgBlob = $im->lookupAll()->result();
            $countData = 0;
            foreach ($imgBlob as $rowImg) {
                $countData++;
                $arrExtImg[$rowImg->parent_id] = $rowImg->files;
                $badgeData[$rowImg->parent_id][] = $countData;
            }
        }

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $limitedEditor = $o->getLimiteEditor();


        $objState = "0";
        $objStateNonActive = "0";

        if (isset($_GET['trashed']) && $_GET['trashed'] > 0) {
            $objState = $_GET['trashed'];
            $o->setFilters(array());
            if ($objState == "1") {
                $title = "Deleted " . $title;
                $objStateStatus = "0";
            }
            $o->addFilter("trash='$objState'");
            $o->addFilter("status='$objStateStatus'");
        }
        elseif ((isset($_GET['trashed']) && $_GET['trashed'] == 0) && (isset($_GET['status']) && $_GET['status'] == 0)) {
            $objState = $_GET['trashed'];
            $o->setFilters(array());
            if ($objState == 0) {
                $title = "Nonaktif " . $title;
                $objStateStatus = 0;
                $objStateNonActive = 1;
            }
            $o->addFilter("trash='$objState'");
            $o->addFilter("status='$objStateStatus'");

        }
        else {
            //            $o->addFilter("trash='0'");
            //            $o->addFilter("status='1'");
        }
        switch ($objState) {
            case "0":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName?trashed=1'><span class='glyphicon glyphicon-ban-circle'></span> view deleted $realObjName</a>";
                if ($objStateNonActive == 1) {
                    $nonaktifLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName'><span class='glyphicon glyphicon-ok-sign'></span> view active $realObjName</a>";
                }
                else {
                    $nonaktifLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName?trashed=0&status=0'><span class='glyphicon glyphicon-ban-circle'></span> view non active $realObjName</a>";
                }
                break;
            case "1":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName'><span class='glyphicon glyphicon-ok-sign'></span> view active $realObjName</a>";
                $nonaktifLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName'><span class='glyphicon glyphicon-ok-sign'></span> view active $realObjName</a>";
                break;
        }

        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $o->addFilter("folders='" . $_GET['fID'] . "'");
            //            $title.=" on ".$_GET['fName'];
        }

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }

        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Contains '$key'";
        }
        else {
            $key = "";
            $subtitle = "List of $title $subLabel";
        }

        $t = new Table();

        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {

            foreach ($dataProposals as $mdlName => $pSpec) {
                $this->load->model("Mdls/" . $mdlName);
                $o2 = new $mdlName();
                $listedFields = $this->listedFields;
                $fields = $o2->getFields();

                foreach ($pSpec as $dSpec) {
                    $tmpItemTmp = array();
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";
                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        $fieldLabel = isset($dSpec['content'][$fRealName]) ? $dSpec['content'][$fRealName] : "";

                        //===if related
                        if (array_key_exists($fName, $this->relations)) {
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }

                        if (isset($fields[$fName]['inputType'])) {
                            switch ($fields[$fName]['inputType']) {
                                case "image":
                                    $fieldLabel = sizeof($fieldLabel) > 0 ? "<img style='width: 40px;' src='$fieldLabel'>" : "<img src=''>";
                                    $tmpItemTmp[$fName] = $fieldLabel;
                                    break;
                                default:
                                    $tmpItemTmp[$fName] = $fieldLabel;
                                    break;
                            }
                        }
                    }

                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        }
                                        );";

                    if (sizeof($dataExtRel) > 0) {
                        $fieldLabel = isset($dSpec['content']['images']) ? "<img style='width: 40px;' src='" . $dSpec['content']['images'] . "'>" : "";
                        $tmpItemTmp["images"] = $fieldLabel;
                    }

                    $tmpItemTmp["date"] = $dSpec['date'];
                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='javascript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }
            }
        }

        $addLink = base_url() . get_class($this) . "/add/$ctrlName";
        $addManyLink = base_url() . get_class($this) . "/addMany/$ctrlName";

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $addLink .= "?reqField=" . $_GET['reqField'] . "&reqVal=" . $_GET['reqVal'];
        }

        $params = array();
        $limit_per_page = 0;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $o->lookupDataCount($key);
//        showLast_query("merah");
        if ($total_records > 0) {
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }

            $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);

            $config = array(
                'base_url' => base_url() . get_class($this) . '/' . __FUNCTION__ . "/$ctrlName/",
                'total_rows' => $total_records,
                'per_page' => $limit_per_page,
                "uri_segment" => 4,
                'num_links' => 6,
                'use_page_numbers' => TRUE,
                'reuse_query_string' => TRUE,
                'full_tag_open' => '<div class="text-center">',
                'full_tag_close' => '</div>',
                'first_link' => "<span class='fa fa-home'></span>",
                'first_tag_open' => '<span style="padding:1px;">',
                'first_tag_close' => '</span>',
                'last_link' => "<span class='fa fa-gg'></span>",
                'last_tag_open' => '<span style="padding:1px;">',
                'last_tag_close' => '</span>',
                'next_link' => "<span class='fa fa-angle-right'></span>",
                'next_tag_open' => '<span style="padding:1px;">',
                'next_tag_close' => '</span>',
                'prev_link' => "<span class='fa fa-angle-left'></span>",
                'prev_tag_open' => '<span style="padding:1px;">',
                'prev_tag_close' => '</span>',
                'cur_tag_open' => '<span class="btn btn-primary disabled">',
                'cur_tag_close' => '</span>',
                'num_tag_open' => '<span style="padding:1px;">',
                'num_tag_close' => '</span>',
            );
            $this->pagination->initialize($config);
            $params["links"] = $this->pagination->create_links();
        }

        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination

        $dataRow = array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $defaultKey = $key != "" ? $key : "cari " . strtolower($title);
        $content .= ($t->addSpanRow(array(
            "<div class='input-group'>" . "<input type=text placeholder='$defaultKey' class='form-control text-center' onkeyup=\"if(detectEnter()==1){location.href='" . base_url() . get_class($this) . "/view/$ctrlName/?k='+this.value}\">" . "<span class='input-group-addon'>" . "<i class='glyphicon glyphicon-search'></i></span>" . "</div class='input-group'>",
        )));

        /*--data histori terakhir*/
        $this->load->model("Mdls/" . "MdlDataHistory");
        $hi = new MdlDataHistory();
        $hi->addFilter("mdl_name='$className'");
        // $hi->addFilter("orig_id='9'");
        $this->db->order_by("id", "asc");
        $tmpHi = $hi->lookupAll()->result();
        // showLast_query("biru");
        $last_data = array();
        foreach ($tmpHi as $item) {
            // arrPrint($item);
            $orig_id = $item->orig_id;

            $old_content = blobDecode($item->old_content);
            $old_data = isset($old_content[0]) ? $old_content[0] : "";
            $new_content = blobDecode($item->new_content);
            // arrPrintKuning($new_content);
            $new_data = $new_content;

            $datas["dtime"] = $item->dtime;
            $datas["orig_id"] = $item->orig_id;
            $datas["oleh_id"] = $item->oleh_id;
            $datas["oleh_nama"] = $item->oleh_name;
            $datas["old_dt"] = $old_data;
            $datas["new_dt"] = $new_data;
            $last_data[$orig_id] = $datas;
        }
        // arrPrintHijau($last_data);

        $selectID = 0;
        $arrayItem = array();
        if (sizeof($tmp) > 0) {//===ada data

            if ($this->uri->segment(3) > 0) {
                $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
            }
            else {
                $rowCounter = 0;
            }

            foreach ($tmp as $m => $rowSpec) {
                // arrPrintKuning($rowSpec);
                $id = $rowSpec->id;
                $nama = isset($rowSpec->nama) ? $rowSpec->nama : "";
                $status = $rowSpec->status;
                $hist_last_data = isset($last_data[$id]) ? $last_data[$id] : array();


                // $tmpItem['htrash'] = $hist_last_data['new_dt']['status'];


                if ($this->allowEdit && $objState != "1") {
                    $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $rowSpec->id . "";
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $title',
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

                    $updateCommentStr = "Klik untuk mengubah entri";
                }
                else {
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
                                        title:'$ctrlName change histories ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";

                $tmpItem = array();
                /*data dari history*/
                $tmpItem['oleh_nama'] = isset($hist_last_data['oleh_nama']) ? $hist_last_data['oleh_nama'] : "";
                $tmpItem['hdtime'] = isset($hist_last_data['dtime']) ? formatField_he_format("fulldate", $hist_last_data['dtime']) : "-";
                $tmpItem['hstatus'] = isset($hist_last_data['new_dt']['status']) ? $hist_last_data['new_dt']['status'] : "";
                /*data utama*/
                foreach ($o->getListedFields() as $ofName => $label) {
                    $fName = $ofName;
                    if (array_key_exists($ofName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$ofName][$rowSpec->$ofName]) ? $this->relationPairs[$ofName][$rowSpec->$ofName] : "-unknown rel-";
                    }
                    else {
                        if (sizeof($arrExtImg) > 0) {
                            $srcKey = $dataExtRel["srcKey"];
                            $selectID = $rowSpec->$srcKey;
                            if (isset($arrExtImg[$selectID])) {
                                $valData = $arrExtImg[$selectID];
                                $img_src = "src='$valData'";
                                $badge = sizeof($badgeData[$selectID]) > 1 ? sizeof($badgeData[$selectID]) : "";
                                $notifBadge = $badge > 1 ? "<span class='notify-badge' style=''>$badge</span>" : "";
                            }
                            else {
                                $valData = base_url() . "public/images/img_blank.gif";
                                $img_src = "src='$valData'";
                                $notifBadge = "";
                            }
                            $fieldsImages = "<div class=''>";
                            $fieldsImages .= "<div class='item'>$notifBadge";
                            $fieldsImages .= "<img $img_src class='img-responsive' width='65px'>";
                            $fieldsImages .= "</div>";
                            $fieldsImages .= "</div>";
                            $tmpItem['images'] = $fieldsImages;
                        }

                        if (isset($fields[$ofName]["transformValue"])) {
                            $function = $fields[$ofName]["transformValue"];
                            $dataValue = strlen($rowSpec->$ofName) > 0 ? $rowSpec->$ofName : "-unregistered-";
                            $listed = "<div class='text-center bottom-borderss' style='margin-bottom: 1px;'>";
                            $listed .= "<svg class='thumbnail' id='r_$selectID' style='width:120px;height:50px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                            $listed .= "</div>";
                            if (validate_EAN13Barcode($dataValue)) {
                                $listed .= "<script>$function('#r_$selectID', '$dataValue', {format: 'ean13'});</script>";
                            }
                            else {
                                if ($dataValue == "-unregistered-") {
                                    $listed .= "<script>$function('#r_$selectID', '$dataValue', {format: 'code39', lineColor: '#e02907'});</script>";
                                }
                                else {
                                    $listed .= "<script>$function('#r_$selectID', '$dataValue', {format: 'code39'});</script>";
                                }
                            }
                            $fieldLabel = $listed;
                        }
                        else {
                            if (($ofName == "image_ktp") || ($ofName == "image_npwp")) { // harus diganti supaya dinamis, tidak nembak kayak gini...
                                $img_src = "src='" . $rowSpec->$ofName . "'";
                                $arrImg = array(
                                    "title" => $rowSpec->nama,
                                    "body" => array(
                                        $rowSpec->$ofName,
                                    ),
                                );
                                $modalImage = base_url() . "Katalog/modal/" . str_replace("=", "", blobEncode($arrImg)) . "";

                                $fieldsImages = "<div class=''>";
                                $fieldsImages .= "<div class='item'>";
                                $fieldsImages .= "<a href='" . $modalImage . "' data-toggle=\"modal\" data-target=\"#myModal\">";
                                $fieldsImages .= "<img $img_src class='img-responsive' width='65px'>";
                                $fieldsImages .= "</a>";
                                $fieldsImages .= "</div>";
                                $fieldsImages .= "</div>";

                                $fieldLabel = $fieldsImages;
                            }
                            else {
                                $fieldLabel = isset($rowSpec->$ofName) ? nl2br($rowSpec->$ofName) : "";
                            }
                        }
                    }

                    $tmpItem['action'] = "<span class='btn-block text-center'>";
                    $tmpItem[$ofName] = $fieldLabel;

                    if ($this->allowEdit && $objState != "1") {
                        $addNumber = $colCounter == 0 ? "<a href='javascript:void(0)' onclick =\"$historyClick\"><span class='badge' style='background:#c0c0c0;color:#656564;'>$rowCounter</span></a>" : "";
                        $tmpItem['action'] .= "<a class='btn btn-sm btn-default ' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                    }
                    $colCounter++;
                }
                if ($this->allowDelete && $objState != "1") {
                    if (array_key_exists($rowSpec->$indexFieldName, $data_aktif)) {
                        // $tmpItem['action'] .= "<button class='btn btn-danger btn-sm hidden-print' disabled data-toggle='tooltip' data-placement='left' title='delete button disabled' onClick=\"\"><span class='glyphicon glyphicon-remove'></button>";
                        if ($status == 1) {
                            $newStatus = 0;
                            $status_warning = "$nama akan di non-aktifkan";
                            $btn_warna = "btn-danger";
                            $btn_title = "menon aktifkan data";
                            $btn_icon = "fa-remove";
                        }
                        else {
                            $newStatus = 1;
                            $status_warning = "$nama akan kembali diaktifkan ";
                            $btn_warna = "btn-success";
                            $btn_title = "mengaktifkan data";
                            $btn_icon = "fa-check";
                        }
                        $statusLink = base_url() . get_class($this) . "/doUpdStatus/$ctrlName/$id/$newStatus/$className";
                        $tmpItem['action'] .= "<button class='btn $btn_warna btn-sm hidden-print' data-toggle='tooltip' data-placement='left' title='$btn_title' onClick=\"confirm_alert_result('Opss..','$status_warning','$statusLink');\"><span class='fa $btn_icon'></button>";
                    }
                    else {

                        // $tmpItem['action'] .= "<button class='btn btn-danger btn-sm hidden-print' data-toggle='tooltip' data-placement='left' title='permanen delete entry' onClick=\"if(confirm('Remove entry?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                        // if($_SERVER["REMOTE_ADDR"] == "202.65.117.72"){
                            $deletet_warning = "$nama akan dihapus secara permanen";
                        $tmpItem['action'] .= "<button class='btn btn-danger btn-sm hidden-print' data-toggle='tooltip' data-placement='left' title='permanen delete entry' onClick=\"confirm_alert_result('WARNING!','$deletet_warning','$deleteLink');\"><span class='glyphicon glyphicon-remove'></button>";
                    // }
                    }

                    // cekBiru($ctrlName);

                    // }
                }
                if ($this->allowViewHistory) {
                    $tmpItem['action'] .= "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span></a>";
                }
                /*data button tombol*/
                $tmpItem['action'] .= "</span class='btn-block'>";
                $content .= ($t->addRow($dataRow));
                if (sizeof($dataRel) > 0) {
                    // $optClick = "BootstrapDialog.closeAll();
                    // BootstrapDialog.show(
                    //                {
                    //                     title:'$title options',
                    //                     message: $('<div></div>').load('" . base_url() . get_class($this) . "/showRelOptions/$className/" . $rowSpec->id . "'),
                    //                     size: BootstrapDialog.SIZE_WIDE,
                    //                     draggable:true,
                    //                     closable:true,
                    //                     }
                    //                     );";

                    // $tmpItem['option'] = "<a href='javascript:void(0)' onclick=\"$optClick\">" . "<span class='glyphicon glyphicon-option-vertical'></span>" . "</a>";
                }

                // arrPrintPink($tmpItem);
                $arrayItem[] = $tmpItem;
            }
        }
        $titleLimited = "";
        if ($this->allowCreate) {
            $limitlessModel = $this->config->item("limitBrach");
            //            arrPRint($limitlessModel);
            //             cekmerah(":: Mdl -> $className");
            $curentLimit = "";
            if (isset($limitlessModel[$className])) {
                $l = new $className();
                $tmpLimit = $l->lookUpAll()->result();
                showLast_query("biru");

                if (sizeof($tmpLimit) + sizeof($dataProposals) >= $limitlessModel[$className]['limit']) {
                    $title = $titleLimited = "not allowed";
                    $titleLimited = $limitlessModel[$className]['limitNotif'];
                    $curentLimit = "disabled";
                }
                else {
                    //                    $title = $titleLimited = "not allowed";
                    $titleLimited = "";
                }
                ceklime($limitlessModel[$className]['limit'] . " vd " . sizeof($tmpLimit));

                //                cekLime(sizeof($tmpLimit));
            }
            //            matiHere();
            $addClick = "
                        BootstrapDialog.show(
                           {
                                title:'New $title',
                                message: $('<div></div>').load('" . $addLink . "'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );";
            $strAddLink = "";
            $strAddLink .= "<div class='btn-group'>";
            $strAddLink .= "<button href='javascript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' $curentLimit class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus' ></button>";
            $strAddLink .= in_array("addMany", $limitedEditor) ? "" : "<button href='javascript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title' $curentLimit><span class='glyphicon glyphicon-plus-sign'></span></button>";
            $strAddLink .= "</div class='btn-group'>";

        }
        else {
            $strAddLink = "";
        }
        if ($this->allowEdit) {
            $strEditLink = in_array("addMany", $limitedEditor) ? "" : "<button href='javascript:void(0)' class=\"btn btn-sm btn-default\" onClick=\"location.href='" . base_url() . get_class($this) . "/editMany/$ctrlName/" . $this->uri->segment(4) . "'\" data-toggle='tooltip' data-placement='top' title='Modify all $title in this page' $curentLimit class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-pencil'></button>";
        }
        else {
            $strEditLink = "";
        }

        // arrPrint(get_class_methods($o));
        // arrPrint($this->excelWriters);
        if (method_exists($o, "getExcelWriters")) {
            $className_e = str_replace("=", "", blobEncode($className));
            $xlsLink = base_url() . "ExcelWriter/data/$className_e";
            // onclick=\"location.href='".base_url()."ExcelWriter/data/$className_e'\"
            $strEditLink .= "<button name='download' type='button' class='btn btn-warning'
            
         
         onclick=\"btn_result('$xlsLink');\"
         
        title='export data ke xlsx'><i class='fa fa-download'></i> EXCEL</button>";
        }
        else {
            // cekOrange($className);
        }


        $arrayHeader = $this->listedFields;
        if (sizeof($dataExtRel) > 0) {
            $arrayHeader["images"] = "images";
        }

        $arrayHeader["hdtime"] = "last edit";
        $arrayHeader["oleh_nama"] = "pic";
        $arrayHeader["action"] = "action";

        if (sizeof($dataRel) > 0) {
            $arrayHeader["option"] = "<span class='glyphicon glyphicon-th-list'></span>";
        }

        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        $tmpH = $h->lookupRecentHistories()->result();
        // showLast_query("biru");
        $arrayRecap = array();
        if (sizeof($tmpH) > 0) {
            $tmpO = new $className();
            foreach ($tmpH as $row) {
                $hist_id = $row->id;
                $hist_link = "?mdl_name=$className&id=$hist_id";
                $tmpRecap = array();
                $content = unserialize(base64_decode($row->new_content));
                foreach ($this->listedFields as $fName => $label) {
                    $fieldLabel = isset($content[$fName]) ? $content[$fName] : "";
                    if (array_key_exists($fName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                    }
                    else {
                        $fieldLabel = isset($row->$fName) ? $row->$fName : (isset($content[$fName]) ? $content[$fName] : "unknown rel#");
                    }
                    $type_data = isset($fields[$fName]['type']) ? $fields[$fName]['type'] : "varchar";
                    switch ($type_data) {
                        default:
                            $tmpRecap[$fName] = nl2br($fieldLabel);
                            break;
                        case "blob":
                            die(__LINE__);
                            $imageDecode = blobDecode($fieldLabel);
                            $imageAvail = base64_encode($imageDecode['image']);
                            $img_scr = "src='data:image/jpeg;base64,$imageAvail'";
                            $fblob_data = "<div><img $img_scr class='img-responsive' width='150px' ></div>";
                            $tmpRecap[$fName] = $fblob_data;
                            break;
                        case "image":
                            $img_scr = "src='$fieldLabel'";
                            $fblob_data = "<div><img $img_scr class='img-responsive' width='150px' ></div>";
                            $tmpRecap[$fName] = $fblob_data;
                            break;
                    }
                }
                $tmpRecap['oleh_nama'] = $row->oleh_name;
                $tmpRecap['dtime'] = $row->dtime;
                $arrayRecap[] = $tmpRecap;
            }
        }

        $arrayProgressLabel['date'] = "date";
        $arrayProgressLabel['propose_type'] = "proposal type";
        $arrayProgressLabel = $arrayProgressLabel + $arrayHeader;
        $arrayRecapLabel = $arrayHeader;

        $arrayProgressLabel['action'] = "action";

        //        arrPrint($arrayProgressLabel);

        unset($arrayProgressLabel['history']);
        unset($arrayRecapLabel['action']);
        unset($arrayRecapLabel['history']);

        $titleSuffix = createObjectSuffix($realObjName);

        if (isset($this->relationPairs) && array_key_exists("folders", $this->relationPairs)) {
            $folders = array("" => "HOME") + $this->relationPairs['folders'];
            $fmdlName = $this->relations['folders'];
            $fdataAccess = isset($this->config->item('heDataBehaviour')[$fmdlName]) ? $this->config->item('heDataBehaviour')[$fmdlName] : array(
                "viewers" => array(),
                "creators" => array(),
                "creatorAdmins" => array(),
                "updaters" => array(),
                "updaterAdmins" => array(),
                "deleters" => array(),
                "deleterAdmins" => array(),
                "historyViewers" => array(),
            );

            $allowCreateFolder = false;
            $allowEditFolder = false;
            $allowDeleteFolder = false;
            if (sizeof($mems) > 0 && sizeof($fdataAccess['creators']) > 0) {
                $allowCreateFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['updaters']) > 0) {
                $allowEditFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['deleters']) > 0) {
                $allowDeleteFolder = true;
            }

            if ($allowCreateFolder) {
                $faddLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $fmdlName);
            }
            if ($allowEditFolder) {
                $fupdateLink = base_url() . get_class($this) . "/edit/" . str_replace("Mdl", "", $fmdlName) . "/";
            }
            if ($allowDeleteFolder) {
                $fdeleteLink = base_url() . get_class($this) . "/delete/" . str_replace("Mdl", "", $fmdlName) . "/";
                $fdeleteLinkBack = current_url() . "?" . $_SERVER['QUERY_STRING'];
                //cekMerah($fdeleteLinkBack);
            }

        }
        else {
            $folders = array();
        }
        //        cekMerah(current_url() ."?". $_SERVER['QUERY_STRING']);
        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "title" => $realObjName . $titleSuffix,
            "subTitle" => $subtitle,
            "strActiveDataTitle" => "<span class='glyphicon glyphicon-th-list'></span> List of $title" . $titleSuffix,
            "linkStr" => isset($params['links']) ? $params['links'] : "",
            "arrayHistoryLabels" => $arrayHeader,
            "arrayHistory" => $arrayItem,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress" => $arrItemTmp,
            "strDataHistTitle" => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "arrayRecapLabels" => $arrayRecapLabel,
            "arrayRecap" => $arrayRecap,
            "strEditLink" => $strEditLink,
            "strAddLink" => $strAddLink,
            "alternateLink" => $alternateLink,
            "nonaktifLink" => $nonaktifLink,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?trashed=$objState",
            "folders" => $folders,
            "faddLink" => isset($faddLink) ? $faddLink : "",
            "feditLink" => isset($fupdateLink) ? $fupdateLink : "",
            "fdeleteLink" => isset($fdeleteLink) ? $fdeleteLink : "",
            "fdeleteLinkBack" => isset($fdeleteLinkBack) ? $fdeleteLinkBack : "",
            "fmdlName" => isset($fmdlName) ? $fmdlName : "",
            "fmdlTarget" => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
            "topNotifLimit" => $titleLimited,
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
        $this->load->model("Mdls/" . $className);

        $o = new $className();
        $listedFields = $this->listedFields;
        $fields = $o->getFields();

        $p = new Layout("", "", "application/template/lte/index.html");
        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        $h->addFilter("orig_id='$selectedID'");
        $tmpH = $h->lookupAll()->result();
        //        cekBiru($this->db->last_query());
        //        matiHere();
        if (sizeof($tmpH) > 0) {
            $content .= ("<div class='table-responsive'>");
            $content .= ("<table class='table table-condensed table-bordered'>");

            //ASLI
            //            $content .= ("<tr bgcolor='#dedede'>");
            //            $content .= ("<td>date</td>");
            //            foreach ($listedFields as $fName => $label) {
            //                $content .= ("<td>");
            //                $content .= ($label);
            //                $content .= ("</td>");
            //            }
            //            $content .= ("<td>person</td>");
            //            $content .= ("</tr>");

            //MODIF to thead

            $content .= ("<thead>");
            $content .= ("<tr bgcolor='#dedede'>");
            $content .= ("<td>date</td>");
            foreach ($listedFields as $fName => $label) {
                $content .= ("<td>");
                $content .= ($label);
                $content .= ("</td>");
            }
            $content .= ("<td>person</td>");
            $content .= ("</tr>");
            $content .= ("</thead>");
            //$anu = produk_spec_webs();

            foreach ($tmpH as $row) {
                $oldContents = unserialize(base64_decode($row->old_content));
                $newContents = unserialize(base64_decode($row->new_content));
                //                arrPrint($newContents);
                $content .= ("<tr>");
                $content .= ("<td>" . $row->dtime . "</td>");
                foreach ($listedFields as $fName => $label) {
                    $type_conten = isset($fields[$fName]['type']) ? $fields[$fName]['type'] : "";
                    $fColName = $fName;
                    switch ($type_conten) {
                        default:
                            $strContent = isset($newContents[$fColName]) ? $newContents[$fColName] : "-";
                            if ($fColName == "status") {
                                $strContent = ($strContent == 1) ? "aktif" : "non aktif";
                            }
                            break;
                        case "blob":
                            $existConten = isset($newContents[$fColName]) ? $newContents[$fColName] : "-";
                            $strContent = "<img src='$existConten' class='img-responsive' width='85px'>";
                            break;
                        case "image":
                            $existConten = isset($newContents[$fColName]) ? $newContents[$fColName] : "-";
                            $strContent = "<img src='$existConten' class='img-responsive' width='85px'>";
                            break;
                    }
                    $strOldContent = isset($oldContents[$fColName]) ? $oldContents[$fColName] : "-";
                    $content .= ("<td>");
                    $content .= ($strContent);
                    $content .= ("</td>");
                }
                $content .= ("<td>");
                $content .= ($row->oleh_name);
                $content .= ("</td>");
                $content .= ("</tr>");
            }
            $content .= ("</table>");
            $content .= ("</div class='table-responsive'>");
        }
        else {
            $content .= ("<div class='alert alert-warning text-center'>");
            $content .= ("this item has no history entry");
            $content .= ("</div class='alert alert-warning'>");
        }

        $content .= "
        <script>
            var thisTable = $('.modal').find('.table');
            $(thisTable).DataTable();
        </script>";
        $content .= "<script>$('.modal-dialog').removeClass('modal-lg').addClass('modal-xl');</script>";
        echo $content;
    }

    public function viewHistories_()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        $className = "Mdl" . $this->uri->segment(3);

        $ctrlName = $this->uri->segment(3);
        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        //<editor-fold desc="data proposal data">
        $steps = $this->config->item("heDataBehaviour")["MdlDataHistory"]["MdlChilds"];
        if (sizeof($steps) > 1) {
            $stepCodes = array();
            foreach ($steps as $mdlName => $stepSpec) {
                //                arrPrint($stepSpec);
                $stepLabels[$stepSpec] = $mdlName;
                $stepLinks[$mdlName] = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $mdlName;
            }
            $currentState = strlen($this->uri->segment(4)) > 0 ? $this->uri->segment(4) : "HargaProduk";

            //             $mdlLabel = str_ireplace(" ","",$currentState);


        }
        //cekhijau($currentState);
        //</editor-fold>

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        //cekBiru($currentState);
        $this->load->model("MdlDataHistory");
        $o = new MdlDataHistory();
        $o->addFilter("mdl_name='Mdl$currentState'");

        $tmpHist = $o->lookupAll()->result();
        $selectedTopID = isset($_GET['topID']) ? $_GET['topID'] : 0;

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

        $o->addFilter("trash='$objState'");

        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $o->addFilter("folders='" . $_GET['fID'] . "'");
            //            $title.=" on ".$_GET['fName'];
        }

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }


        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";
        }
        else {
            $key = "";
            $subtitle = "Daftar $title";
        }

        //        $p = new Layout ($title, $subtitle, "application/template/lte/index.html");
        $t = new Table();
        // <editor-fold defaultstate="collapsed" desc="pagination">

        $params = array();
        $limit_per_page = 10;
        $page = ($this->uri->segment(5)) ? ($this->uri->segment(5) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $o->lookupDataCount($key);

        cekHijau("$total_records || $page");
        if ($total_records > 0) {
            // get current page records
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }

            $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
            cekMerah($this->db->last_query());
            //            die();
            $config = array(
                'base_url' => base_url() . get_class($this) . '/' . __FUNCTION__ . "/$ctrlName/$currentState",
                'total_rows' => $total_records,
                'per_page' => $limit_per_page,
                "uri_segment" => 5,
                // custom paging configuration
                'num_links' => 6,
                'use_page_numbers' => TRUE,
                'reuse_query_string' => TRUE,
                'full_tag_open' => '<div class="text-center">',
                'full_tag_close' => '</div>',
                'first_link' => "<span class='fa fa-home'></span>",
                'first_tag_open' => '<span style="padding:1px;">',
                'first_tag_close' => '</span>',
                'last_link' => "<span class='fa fa-gg'></span>",
                'last_tag_open' => '<span style="padding:1px;">',
                'last_tag_close' => '</span>',
                'next_link' => "<span class='fa fa-angle-right'></span>",
                'next_tag_open' => '<span style="padding:1px;">',
                'next_tag_close' => '</span>',
                'prev_link' => "<span class='fa fa-angle-left'></span>",
                'prev_tag_open' => '<span style="padding:1px;">',
                'prev_tag_close' => '</span>',
                'cur_tag_open' => '<span class="btn btn-primary disabled">',
                'cur_tag_close' => '</span>',
                'num_tag_open' => '<span style="padding:1px;">',
                'num_tag_close' => '</span>',
            );
            $this->pagination->initialize($config);
            // cekHijau($this->db->last_query());
            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
        // </editor-fold>
        //        arrPrint($config);
        //        die();

        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination
        $dataRow = array();
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();


        $defaultKey = $key != "" ? $key : "cari " . strtolower($title);
        $content .= ($t->addSpanRow(array(
            "<div class='input-group'>" . "<input type=text placeholder='$defaultKey' class='form-control text-center' onkeyup=\"if(detectEnter()==1){location.href='" . base_url() . get_class($this) . "/view/$ctrlName/?k='+this.value}\">" . "<span class='input-group-addon'>" . "<i class='glyphicon glyphicon-search'></i></span>" . "</div class='input-group'>",
        )));

        //        arrPrint($tmp);
        $arrayItem = array();
        if (sizeof($tmp) > 0) {//===ada data

            //region nomor baris di masing2 halaman
            if ($this->uri->segment(3) > 0) {
                $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
            }
            else {
                $rowCounter = 0;
            }
            //endregion
            //arrPrint($o->getListedFields());

            // <editor-fold defaultstate="collapsed" desc="iterasi tampilan, jika bukan berupa selfCategory">
            foreach ($tmp as $m => $rowSpec) {
                $colCounter = 0;
                $rowCounter++;
                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                $typeData = $o->getFields();
                //                arrPrint($typeData);
                $tmpItemTmp = array();
                foreach ($o->getListedFields() as $ofName => $label) {
                    $fName = $ofName;
                    //===if related
                    if (array_key_exists($ofName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$ofName][$rowSpec->$ofName]) ? "<span class='fa fa-folder-o' style='color:#0056cd;'></span> " . $this->relationPairs[$ofName][$rowSpec->$ofName] : "-unknown rel-";
                    }
                    else {
                        $fieldLabelTmp = $rowSpec->$ofName;
                        $fieldLabel_cek = $typeData[$ofName]["type"] == "mediumblob" ? blobDecode($fieldLabelTmp) : $fieldLabelTmp;
                        $cek_array = is_array($fieldLabel_cek) || is_object($fieldLabel_cek);
                        if ($cek_array) {
                            //                            arrPrint($fieldLabel_cek);
                            $data_blob = "";
                            if (sizeof($fieldLabel_cek) > 0) {
                                foreach ($fieldLabel_cek as $label => $value) {
                                    $data_blob .= "<div>";
                                    $data_blob .= "<span class='col-xs-4 pull-left'>$label</span><span>: $value</span>";
                                    $data_blob .= "</div>";
                                }
                            }
                            else {
                                $data_blob = "";
                            }

                        }
                        else {
                            $data_blob = $fieldLabel_cek;
                        }
                        $fieldLabel = $data_blob;
                        //                        arrPRint($fieldLabel_cek);
                        //                        arrPrint($type_data);
                    }
                    //                    $newLabel = normalizaBase64($fieldLabel);
                    $tmpItemTmp[$fName] = $fieldLabel;
                }

                $content .= ($t->addRow($dataRow));
                if (sizeof($dataRel) > 0) {

                    $optClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$title options',
                                        message: $('<div></div>').load('" . base_url() . get_class($this) . "/showRelOptions/$className/" . $rowSpec->id . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                    $tmpItemTmp['option'] = "<a href='javascript:void(0)' onclick=\"$optClick\">" . "<span class='glyphicon glyphicon-option-vertical'></span>" . "</a>";
                }
                $arrayItem[] = $tmpItemTmp;

            }//

            // </editor-fold>
            //endregion datacontent
        }
        //arrPRint($arrayItem);
        //        die();
        $strEditLink = "";


        $arrayHeader = $o->getListedFields();
        //        $arrayHeader["action"] = "action";
        //        $arrayHeader["history"] = "histories";
        //        if (sizeof($dataRel) > 0) {
        //            $arrayHeader["option"] = "<span class='glyphicon glyphicon-th-list'></span>";
        //        }
        $titleSuffix = createObjectSuffix($realObjName);

        //        arrprint($this->relationPairs);
        if (isset($this->relationPairs) && array_key_exists("folders", $this->relationPairs)) {
            $folders = array("" => "HOME") + $this->relationPairs['folders'];
            $fmdlName = $this->relations['folders'];
            $fdataAccess = isset($this->config->item('heDataBehaviour')[$fmdlName]) ? $this->config->item('heDataBehaviour')[$fmdlName] : array(
                "viewers" => array(),
                "creators" => array(),
                "creatorAdmins" => array(),
                "updaters" => array(),
                "updaterAdmins" => array(),
                "deleters" => array(),
                "deleterAdmins" => array(),
                "historyViewers" => array(),
            );

            $allowCreateFolder = false;
            $allowEditFolder = false;
            $allowDeleteFolder = false;
            if (sizeof($mems) > 0 && sizeof($fdataAccess['creators']) > 0) {
                $allowCreateFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['updaters']) > 0) {
                $allowEditFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['deleters']) > 0) {
                $allowDeleteFolder = true;
            }

            if ($allowCreateFolder) {
                $faddLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $fmdlName);
            }
            if ($allowEditFolder) {
                $fupdateLink = base_url() . get_class($this) . "/edit/" . str_replace("Mdl", "", $fmdlName) . "/";
            }
            if ($allowDeleteFolder) {
                $fdeleteLink = base_url() . get_class($this) . "/delete/" . str_replace("Mdl", "", $fmdlName) . "/";
            }

        }
        else {
            $folders = array();
        }

        //                arrprint($this->uri->segment(2));die();

        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "title" => $realObjName . $titleSuffix,
            //            "subTitle"            => "History " . $this->uri->segment(4),
            "stepLabels" => $stepLabels,
            "stepLinks" => $stepLinks,
            "header" => $arrayHeader,
            "linkStr" => isset($params['links']) ? $params['links'] : "",
            "strActiveDataTitle" => "<span class='glyphicon glyphicon-th-list'></span> History " . $this->uri->segment(4),
            "items" => $arrayItem,
            "subTitle" => $this->uri->segment(4),
            //            "linkStr"             => isset($params['links']) ? $params['links'] : "",
            //            "arrayHistoryLabels"  => $arrayHeader,
            //            "arrayHistory"        => $arrayItem,
            //            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert'></span> approval needed",
            //            "arrayProgressLabels" => $arrayProgressLabel,
            //            "arrayOnProgress"     => $arrItemTmp,
            //            //            "entities" => $entities,
            //            "strDataHistTitle"    => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            //            "arrayRecapLabels"    => $arrayRecapLabel,
            //            "arrayRecap"          => $arrayRecap,
            //            "strEditLink"         => $strEditLink,
            //            "strAddLink"          => $strAddLink,
            //            "alternateLink"       => $alternateLink,
            //            "thisPage"            => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?trashed=$objState",
            //            "folders"             => $folders,
            //            "faddLink"            => isset($faddLink) ? $faddLink : "",
            //            "feditLink"           => isset($fupdateLink) ? $fupdateLink : "",
            //            "fdeleteLink"         => isset($fdeleteLink) ? $fdeleteLink : "",
            //            "fmdlName"            => isset($fmdlName) ? $fmdlName : "",
            //            "fmdlTarget"          => isset($fmdlName) ? base_url() . get_class($this) . "/viewHistories/" . str_replace("Mdl", "", $fmdlName) : "",
        );

        $this->load->view('data', $data);
        $this->session->errMsg = "";
    }

    public function doApproveFrom()
    {
        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Saving your data, please wait..<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,

        );
        echo swalAlert($arrAlert);
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();

        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();

        $tmpContent = unserialize(base64_decode($tmp[0]->content));

        $oTmp->deleteData(array("_id" => $selectedID));

        if (sizeof($dataExtRel) > 0) {
            if (isset($tmpContent['images']) && $tmpContent['images'] != "") {

                $this->load->model("Mdls/MdlImages");
                $i = new MdlImages();
                $insertID = $i->addData(
                    array(
                        'parent_id' => $tmpContent['id'],
                        'jenis' => ucwords($ctrlName),
                        'files' => $tmpContent['images'],
                        'status' => 1
                    )
                );
                unset($tmpContent['images']);
            }
            else {
                unset($tmpContent['images']);
            }
        }

        if ($origID != 0) {//===edit
            $where = array(
                "id" => $origID,
            );
            $tmpContent["trash"] = 0;// ditembak trash=0 karena approve/disetujui perubahannya
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            cekMerah($this->db->last_query());
            if (sizeof($dcomConf) > 0) {
                cekmerah("ada post-processors " . __FILE__ . " " . __LINE__);
                $comName = "DCom" . $dcomConf;
                cekmerah("post-proc name:  $comName");
                $this->load->model("DComs/" . $comName);
                $o2 = new $comName();
                $o2->pair($tmpContent) or die(lgShowError($comName, "failed to pair the params of DCom"));
                $o2->exec() or die(lgShowError($comName, "failed to execute DCom"));
            }

            $this->session->errMsg = "Data has been updated";
            if (method_exists($o, "paramSyncNamaNama")) {
                $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                    $id_ygdisync = isset($tmpContent[$syncNamaNamaParams['id']]) ? $tmpContent[$syncNamaNamaParams['id']] : "";
                    // $o->setTokoId(my_toko_id());
                    if ($id_ygdisync > 0) {
                        $o->syncNamaNama($id_ygdisync);
                    }
                }
                // $o->syncNamaNama();
            }
            // matiHere(__LINE__);

            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($tmpContent)),
                "new_content_intext" => print_r($tmpContent, true),
                "label" => "approved",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            cekLime($this->db->last_query());
        }
        else {//===new data
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            unset($tmpContent["id"]);
            cekKuning("$className " . $o->getTableName());
            $insertID = $o->addData($tmpContent, $o->getTableName());
            cekMerah($this->db->last_query() . " == $insertID");
            if (sizeof($dcomConf) > 0) {
                $inParam = array_merge(array("id" => "$insertID"), $tmpContent);
                $className = "DCom" . $dcomConf;
                $this->load->Model("DComs/" . $className);
                $d = new $className();
                $d->setWriteMode("insert");
                $d->pair($inParam) or die("Tidak berhasil memasang  values pada dcom-processor: $className/" . __FUNCTION__ . "/" . __LINE__);
                $gotParams = $d->exec();
            }
            $this->session->errMsg = "Data has been saved";

            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($tmpContent)),
                "new_content_intext" => print_r($tmpContent, true),
                "label" => "approved",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        }

               // matiHere("clear blm comit bro <h3>Dalam Pemantauan</h3>");
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
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        //        die($selectedID."-".$origID);
        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        //$tmpContent = unserialize(base64_decode($tmp[0]->content));
        $rejectedContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));
        // print_r($tmpContent);
        // die();
        if ($origID > 0) {//===edit


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
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            $this->session->errMsg = "Data proposal has been rejected dan being reverted back";

            //<editor-fold desc="data history / reject">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label" => "rejected",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>

        }
        else {//===new data
            // $tmpContent["status"]=1;
            // $tmpContent["trash"]=0;
            // unset($tmpContent["id"]);
            // $insertID = $o->addData($tmpContent, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            $this->session->errMsg = "Data proposal has been rejected";
            //<editor-fold desc="data history / reject">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label" => "rejected",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }

        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

    public function doApproveDeleteFrom()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));
        // print_r($tmpContent);
        // die();
        if ($origID > 0) {//===edit
            $where = array(
                //                /*$indexFieldName =>*/ "id" => $origID,
                "id" => $origID,
            );
            $tmpContent["status"] = 0;
            $tmpContent["trash"] = 1;
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            $this->session->errMsg = "Data has been deleted";

            //<editor-fold desc="data history / approve">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($tmpContent)),
                "new_content_intext" => print_r($tmpContent, true),
                "label" => "approved",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }
        else {//===new data
            die("unable to determine which data to be deleted");
        }

        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

    public function doRejectDeleteFrom()
    {
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);

        //        die($selectedID."-".$origID);
        $this->db->trans_start();

        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");

        $tmp = $oTmp->lookupAll()->result();
        //$tmpContent = unserialize(base64_decode($tmp[0]->content));
        $rejectedContent = unserialize(base64_decode($tmp[0]->content));
        $oTmp->deleteData(array("_id" => $selectedID));
        // print_r($tmpContent);
        // die();
        if ($origID > 0) {//===edit


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
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            $this->session->errMsg = "Data proposal has been rejected dan being reverted back";

            //<editor-fold desc="data history / reject">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label" => "rejected",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>

        }
        else {//===new data
            // $tmpContent["status"]=1;
            // $tmpContent["trash"]=0;
            // unset($tmpContent["id"]);
            // $insertID = $o->addData($tmpContent, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            $this->session->errMsg = "Data proposal has been rejected";
            //<editor-fold desc="data history / reject">
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($rejectedContent)),
                "new_content_intext" => print_r($rejectedContent, true),
                "label" => "rejected",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //</editor-fold>
        }

        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";
    }

    public function showRelOptions()
    {
        //==required: model-name, model-ID
        $mdlName = $this->uri->segment(3);
        $id = $this->uri->segment(4);
        $dataRel = isset($this->config->item('dataRelation')[$mdlName]) ? $this->config->item('dataRelation')[$mdlName] : array();
        // arrPrint($dataRel);
        // mati_disini(__LINE__ . "Mdl".$mdlName);
        $content = "";
        if (sizeof($dataRel) > 0) {
            $content .= "<ul class='list-group'>";
            foreach ($dataRel as $tMdlName => $tSpec) {
                $content .= "<li class='list-group-item'>";
                $targetUrl = base_url() . "Data/view/" . str_replace("Mdl", "", $tMdlName) . "?reqField=" . $tSpec['targetField'] . "&reqVal=$id";
                $content .= "<a href='$targetUrl'>";
                $content .= $tSpec['label'];
                $content .= "</a>";
                $content .= "</li class='list-group-item'>";
            }
            $content .= "</ul class='list-group'>";
        }
        echo $content;
    }

    public function addMany()
    {
        //        arrPrint($this->uri->segment_array());
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $validRules = $o->getValidationRules();
        //arrPrint($fields);
        $content .= "<table class='table table-condensed no-padding'>";
        $refModels = array();
        $refOptions = array();
        if (sizeof($fields) > 0) {
            $content .= "<tr>";
            foreach ($fields as $fieldName => $fieldSpec) {
                if ($fieldSpec['inputType'] != "hidden") {

                    if (array_key_exists($fieldSpec['kolom'], $validRules)) {
                        $suffix = "*";
                        $fStyle = "font-weight:bold;";
                    }
                    else {
                        $suffix = "";
                        $fStyle = "";
                    }
                    $content .= "<th style='$fStyle' align='center'>";
                    $content .= str_replace(" ", "&nbsp;", $fieldSpec['label']) . $suffix;
                    $content .= "</th>";
                }

                if (isset($fieldSpec['reference'])) {
                    $tmpModelName = $fieldSpec['reference'];
                    //                    cekHijau(APPPATH."models/$tmpModelName.php");
                    if (file_exists(APPPATH . "models/Mdls/$tmpModelName.php")) {
                        $this->load->model("Mdls/" . $tmpModelName);
                        $o2 = new $tmpModelName();
                        $fields2 = $o2->getFields();
                        $tmp3 = $o2->lookupAll()->result();
                        //cekBiru($this->db->last_query());
                        if (!in_array($tmpModelName, $refModels)) {
                            $refModels[] = $tmpModelName;
                        }
                        //                        arrPrint($refModels);
                        if (sizeof($tmp3) > 0) {
                            $refOptions[$tmpModelName][''] = "- select -";
                            foreach ($tmp3 as $row3) {
                                $id = isset($row3->id) ? $row3->id : 0;
                                $name = isset($row3->nama) ? $row3->nama : "";
                                if (isset($row3->name)) {
                                    $name = $row3->name;
                                }
                                $refOptions[$tmpModelName][$id] = $name;
                            }
                        }
                    }

                }
            }
            $content .= "</tr>";
            $iCtr = 0;
            for ($i = 0; $i <= 10; $i++) {
                $iCtr++;
                $content .= "<tr>";
                $jCtr = 0;
                foreach ($fields as $fieldName => $fieldSpec) {
                    //arrPrint($fieldSpec);
                    if ($fieldSpec['inputType'] != "hidden") {
                        $jCtr++;
                        $content .= "<td style='padding:0px;margin:0px;'>";
                        if ($jCtr == 1) {
                            $content .= "<input type='hidden' name='ctr[]' value='$iCtr'>";
                        }
                        switch ($fieldSpec['inputType']) {
                            //                        case "hidden":
                            //                            $content.="<input type=hidden>";
                            //                            break;
                            case "text":
                                $content .= "<input type=text class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                break;
                            case "number":
                                $content .= "<input type=number class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                break;
                            case "password":
                                $content .= "<input type=password class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                break;
                            case "combo":
                                if (isset($fieldSpec['dataSource']) || isset($fieldSpec['reference'])) {
                                    $content .= "<select class='form-control' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                    if (isset($fieldSpec['dataSource'])) {
                                        foreach ($fieldSpec['dataSource'] as $key => $val) {
                                            $selected = isset($fieldSpec['defaultValue']) && $key == $fieldSpec['defaultValue'] ? "selected" : "";
                                            $content .= "<option value='$key' $selected>$val</option>";
                                        }
                                    }
                                    if (isset($fieldSpec['reference'])) {
                                        $tmpMdlName = $fieldSpec['reference'];
                                        //arrPrint($tmpMdlName);
                                        if (isset($refOptions[$tmpMdlName]) && sizeof($refOptions[$tmpMdlName]) > 0) {
                                            foreach ($refOptions[$tmpMdlName] as $key => $val) {

                                                $content .= "<option value='$key'>$val</option>";
                                                //                                                    $content .= "<input name='$key' type='hidden' value='$key[$val]'></input>";

                                            }
                                        }
                                    }
                                    $content .= "</select class='form-control'>";
                                }
                                else {
                                    $content .= "<input type=password class='form-control' disabled>";
                                }
                                break;
                            case "file" :

                                //                                    $imageAvail = base_url()."public/images/img_blank.gif";
                                //                                    $img_scr = "src='$imageAvail'";

                                $length = isset($fieldSpec['length']) ? $fieldSpec['length'] : "8";
                                //                                $content .= "<div class='thumbnail'>";
                                $content .= "<img  class='img-responsive' width='85px'>";
                                $content .= "<div class='caption'>";
                                $content .= "<input id='input-1a' type='" . "file" . "'  maxlength='" . $length . "' name='$fieldName' id='_$fieldName' placeholder='" . $fieldSpec['label'] . "'  class='form-control' autocomplete='off' data-show-preview='TRUE'  multiple data-show-upload='false'>";
                                //                                $content .="</div>";
                                $content .= "</div>";
                                break;
                            case "image" :

                                $length = isset($fieldSpec['length']) ? $fieldSpec['length'] : "8";
                                $content .= "<img class='img-responsive' width='85px'>";
                                //                                $content .= "<div class='caption'>";
                                //                                $content .= "<input id='input-1a' type='" . "file" . "'  maxlength='" . $length . "' name='$fieldName' id='_$fieldName' placeholder='" . $fieldSpec['label'] . "'  class='form-control' autocomplete='off' data-show-preview='TRUE'  multiple data-show-upload='false'>";
                                //                                $content .= "</div>";
                                break;
                            default:
                                $content .= "-unknown-";
                                break;
                        }
                        $content .= "</td>";
                    }

                }
                $content .= "</tr>";
            }
        }
        else {
            die("Fields required as a data-model primary property");
        }

        $content .= "</table class='table table-condensed'>";
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;

        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "title" => "Add $title",
            "subTitle" => "Add $title",
            "historyTitle" => "<span class='glyphicon glyphicon-th-list'></span> List of $title",
            "content" => $content,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?",
            "formTarget" => base_url() . get_class($this) . "/doAddMany/" . $this->uri->segment(3),
        );
        $this->load->view('data', $data);
        $this->session->errMsg = "";

    }

    public function doAddMany_()
    {
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $validRules = $o->getValidationRules();

        //        $inputTypeWhitelist = array("combo", "radio");
        $inputTypeWhitelist = array();
        $validRows = array();
        $inValidRows = array();
        if (isset($_POST['ctr']) && sizeof($_POST['ctr']) > 0) {
            $this->db->trans_start();
            foreach ($_POST['ctr'] as $ctr => $ctrx) {
                $filledCols = array();
                foreach ($fields as $fieldName => $fieldSpec) {
                    //                    cekHitam($fieldSpec['inputType']);
                    if (!in_array($fieldSpec['inputType'], $inputTypeWhitelist)) {
                        $inputName = $fieldSpec['kolom'];
                        if (isset($_POST[$inputName][$ctr]) && strlen($_POST[$inputName][$ctr]) > 0) {
                            cekKuning($inputName . " ada, yaitu " . $_POST[$inputName][$ctr]);
                            $filledCols[] = $inputName;
                        }
                    }

                }
                arrPrint($filledCols);

                if (sizeof($filledCols) > 0) {
                    $diisi = true;
                }
                else {
                    $diisi = false;
                }
                if ($diisi) {//==barulah divalidasi
                    cekHijau("$ctr diisi");
                    $valResult = $this->lineValidate($o, $ctr);
                    if (is_array($valResult)) {
                        arrPrint($valResult);
                        cekMerah("$ctr TIDAK VALID");
                        $inValidRows[] = $ctr;

                        echo "<script>";
                        foreach ($valResult as $f => $fff) {
                            echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='#ffff00';";
                        }
                        echo "</script>";

                    }
                    else {
                        cekHijau("$ctr VALID");
                        $validRows[] = $ctr;
                        $data = array();
                        foreach ($fields as $fieldName => $fieldSpec) {
                            echo "<script>";
                            //                            foreach ($valResult as $f => $fff) {
                            //                                echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='transparent';";
                            //                            }
                            echo "</script>";
                            $inputName = $fieldSpec['kolom'];
                            if (isset($_POST[$inputName][$ctr])) {
                                $data[$inputName] = $_POST[$inputName][$ctr];
                            }

                            //                            cekHijau("$f berisi ".$_POST[$inputName][$ctr]);
                        }

                        //                        arrPrint($data);die();
                        if ($this->creatorUsingApproval) {


                            $this->load->model("Mdls/" . "MdlDataTmp");
                            $dTmp = new MdlDataTmp();
                            $tmpData = array(
                                "mdl_name" => $className,
                                "mdl_label" => $ctrlName,
                                "proposed_by" => $this->session->login['id'],
                                "proposed_by_name" => $this->session->login['nama'],
                                "proposed_date" => date("Y-m-d H:i:s"),
                                "content" => base64_encode(serialize($data)),
                            );

                            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                            $this->session->errMsg = "Data proposal has been saved and pending approval";

                            //<editor-fold desc="data history / propose">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id" => 0,
                                "mdl_name" => $className,
                                "mdl_label" => get_class($this),
                                "old_content" => "",
                                "new_content" => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label" => "proposed",
                                "oleh_id" => $this->session->login['id'],
                                "oleh_name" => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>

                        }
                        else {


                            $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                            $this->session->errMsg = "Data contents have been saved";
                            cekHijau($this->db->last_query());

                            $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $insertID . "";
                            $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $ctrlName ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

                            $this->session->errMsg .= "<br><a href='javascript:void(0)' onclick=\"$editClick\">view entry</a>";


                            //<editor-fold desc="data history / commited">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id" => 0,
                                "mdl_name" => $className,
                                "mdl_label" => get_class($this),
                                "old_content" => "",
                                "new_content" => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label" => "applied",
                                "oleh_id" => $this->session->login['id'],
                                "oleh_name" => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>
                        }


                    }
                }
                else {
                    cekMerah("$ctr tidak diisi");

                }
            }
            //            arrPrint($inValidRows);
            //mati_disini();
            if (sizeof($inValidRows) > 0) {
                echo "<script>";
                echo "top.document.getElementById('btnSave').disabled=false;";
                echo "</script>";
            }
            else {
                $this->db->trans_complete();
                echo "<script>";
                echo "top.location.reload();";
                echo "</script>";
            }

        }
        else {
            die("items required");
        }
    }

    public function doAddMany()
    {

        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $validRules = $o->getValidationRules();

        //region balik id satuan
        if (isset($_POST["satuan"])) {
            $this->load->model("Mdls/MdlSatuan");
            $sa = new MdlSatuan();
            $spectData = $sa->lookupAll()->result();
            $arraySatuan = array();
            foreach ($spectData as $spectData_0) {
                $arraySatuan[$spectData_0->id] = $spectData_0->nama;
            }
        }
        //endregion

        //endregion
        //        $inputTypeWhitelist = array("combo", "radio");
        $inputTypeWhitelist = array("radio", "combo", "checkbox", "password");
        $validRows = array();
        $inValidRows = array();
        if (isset($_POST['ctr']) && sizeof($_POST['ctr']) > 0) {
            $this->db->trans_start();
            foreach ($_POST['ctr'] as $ctr => $ctrx) {
                $filledCols = array();
                foreach ($fields as $fieldName => $fieldSpec) {
                    //                    cekHitam($fieldName);
                    //                    cekHitam($fieldSpec['inputType']);
                    if (!in_array($fieldSpec['inputType'], $inputTypeWhitelist)) {
                        $inputName = $fieldSpec['kolom'];
                        if (isset($_POST[$inputName][$ctr]) && strlen($_POST[$inputName][$ctr]) > 0) {
                            //                            cekKuning($inputName . " ada, yaitu " . $_POST[$inputName][$ctr]);
                            $filledCols[] = $inputName;
                        }
                    }
                }
                cekkuning("$ctr diisi: ");
                arrPrint($filledCols);

                if (sizeof($filledCols) > 0) {
                    $diisi = true;
                }
                else {
                    $diisi = false;
                }
                if ($diisi) {//==barulah divalidasi
                    //                    cekHijau("$ctr diisi");
                    $valResult = $this->lineValidate($o, $ctr);
                    if (is_array($valResult)) {
                        //                        arrPrint($valResult);
                        //                        cekMerah("$ctr TIDAK VALID");
                        $inValidRows[] = $ctr;

                        echo "<script>";
                        foreach ($valResult as $f => $fff) {
                            echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='#ffff00';";
                        }
                        echo "</script>";

                    }
                    else {
                        //                        cekHijau("$ctr VALID");
                        //                        arrPrint($_POST);
                        $validRows[] = $ctr;
                        $data = array();
                        foreach ($fields as $fieldName => $fieldSpec) {
                            echo "<script>";
                            //                            foreach ($valResult as $f => $fff) {
                            //                                echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='transparent';";
                            //                            }
                            echo "</script>";

                            $inputName = $fieldSpec['kolom'];

                            if ($inputName == "satuan") {
                                $index = $_POST["satuan"][$ctr];
                                $_POST[$inputName][$ctr] = $arraySatuan[$index];
                                //                                $val = $arraySatuan[];
                                //                                $_POST["satuan"] = array("$ctr"=>$val);
                            }
                            //                            arrPrint($_POST);
                            //                            die();
                            if (isset($_POST[$inputName][$ctr])) {
                                $data[$inputName] = $_POST[$inputName][$ctr];
                            }

                            //                            cekHijau("$f berisi ".$_POST[$inputName][$ctr]);
                        }

                        //                                                arrPrint($data);die();
                        if ($this->creatorUsingApproval) {


                            $this->load->model("Mdls/" . "MdlDataTmp");
                            $dTmp = new MdlDataTmp();
                            $tmpData = array(
                                "mdl_name" => $className,
                                "mdl_label" => $ctrlName,
                                "proposed_by" => $this->session->login['id'],
                                "proposed_by_name" => $this->session->login['nama'],
                                "proposed_date" => date("Y-m-d H:i:s"),
                                "content" => base64_encode(serialize($data)),
                            );

                            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                            $this->session->errMsg = "Data proposal has been saved and pending approval";

                            //<editor-fold desc="data history / propose">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id" => 0,
                                "mdl_name" => $className,
                                "mdl_label" => get_class($this),
                                "old_content" => "",
                                "new_content" => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label" => "proposed",
                                "oleh_id" => $this->session->login['id'],
                                "oleh_name" => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>

                        }
                        else {


                            $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                            $this->session->errMsg = "Data contents have been saved";
                            cekHijau($this->db->last_query());

                            $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $insertID . "";
                            $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $ctrlName ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

                            $this->session->errMsg .= "<br><a href='javascript:void(0)' onclick=\"$editClick\">view entry</a>";


                            //<editor-fold desc="data history / commited">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id" => 0,
                                "mdl_name" => $className,
                                "mdl_label" => get_class($this),
                                "old_content" => "",
                                "new_content" => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label" => "applied",
                                "oleh_id" => $this->session->login['id'],
                                "oleh_name" => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>
                            cekmerah("done saving");
                        }


                    }
                }
                else {
                    cekMerah("$ctr tidak diisi");

                }
            }
            arrPrint($inValidRows);
            arrPrint($validRows);
            //            mati_disini();

            if (sizeof($validRows) > 0 || sizeof($inValidRows) > 0) {
                if (sizeof($inValidRows) > 0) {
                    arrprint($inValidRows);
                    cekmerah("ada yang invalid");
                    //                echo lgShowAlert("you must fill in at least one line of entry");
                    echo "<script>";
                    //                    echo ("alert('you must fill in at least one line of entry)");
                    echo "top.document.getElementById('btnSave').disabled=false;";
                    echo "</script>";
                }
                else {
                    cekmerah("LANCAAR");
                    $this->db->trans_complete();
                    echo "<script>";
                    echo "top.location.reload();";
                    echo "</script>";
                }
            }
            else {

                echo "<script>";
                echo "top.document.getElementById('btnSave').disabled=false;";
                echo "</script>";
                echo lgShowAlert("you must fill in at least one line of entry");
                die();
            }


        }
        else {
            die("items required");
        }
    }

    private function lineValidate($o, $lineNumber, $mode = "add")
    {
        $invalidCounter = 0;
        $valResults = array();
        if (count($o->getValidationRules()) > 0) {
            //==do some validation
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;

                if (!in_array($spec['inputType'], array("radio", "combo", "checkbox", "password"))) {
                    cekhitam("$fieldName to be validated");;
                    if (array_key_exists($fName, $o->getValidationRules())) {
                        //echo "$fName to be validated.<br>";
                        // <editor-fold defaultstate="collapsed" desc="validasi kolom wajib/required">
                        if (in_array("required", $o->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (strlen($o->input->post($fName . "_" . $param)) < 1) {
                                        //echo "$fName can not be empty!<br>";
                                        $invalidCounter++;
                                        $valResults[$fName . "_" . $param] = array(
                                            "fieldName" => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg" => $spec['label'] . " " . $param . " can not be empty!",
                                        );
                                    }
                                }
                            }
                            else {
                                if (strlen($o->input->post($fName)[$lineNumber]) < 1) {
                                    //echo "$fName can not be empty!<br>";
                                    $invalidCounter++;
                                    $valResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => $spec['label'] . " can not be empty!",
                                    );
                                }
                            }
                        }
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="validasi numbers only">
                        if (in_array("numberOnly", $o->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (!is_numeric($o->input->post($fName . "_" . $param))) {
                                        //echo "$fName can not be empty!<br>";
                                        $invalidCounter++;
                                        $valResults[$fName . "_" . $param] = array(
                                            "fieldName" => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg" => $spec['label'] . " " . $param . " only accept numbers!",
                                        );
                                    }
                                }
                            }
                            else {
                                if (!is_numeric($o->input->post($fName)[$lineNumber])) {
                                    //echo "$fName can not be empty!<br>";
                                    $invalidCounter++;
                                    $valResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => $spec['label'] . " only accept numbers!",
                                    );
                                }
                            }
                        }
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="validasi unique">
                        if (in_array("unique", $o->getValidationRules()[$fName])) {

                            //$tmpEvalQuery = $o->getByCondition(array($fName => $o->input->post($fName)[$lineNumber]))->result();
                            if ($mode == "edit") {
                                $o->addFilter("id<>'" . $o->input->post("id")[$lineNumber] . "'");
                            }
                            $o->addFilter($fName . "='" . $o->input->post($fName)[$lineNumber] . "'");
                            $tmpEvalQuery = $o->lookupAll()->result();
                            //==validasi unique hanya dikenakan pada penambahan data


                            if ($this->mode == "addProcess") {
                                //if ($tmpEvalQuery > 0) {
                                if (sizeof($tmpEvalQuery) > 0) {

                                    //echo "entri sudah ada <br>";
                                    $invalidCounter++;
                                    $valResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => " $fName with value " . $o->input->post($fName)[$lineNumber] . " already exist!",
                                    );
                                }
                            }
                        }// </editor-fold>


                        if (in_array("alphanumeric", $o->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $o->input->post($fName . "_" . $param))) {
                                        //echo "$fName can not be empty!<br>";
                                        $invalidCounter++;
                                        $valResults[$fName . "_" . $param] = array(
                                            "fieldName" => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg" => $spec['label'] . " " . $param . " only alphanumeric accepted and must be started with letter!",
                                        );
                                    }
                                }
                            }
                            else {
                                if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $o->input->post($fName)[$lineNumber])) {
                                    //echo "$fName can not be empty!<br>";
                                    $invalidCounter++;
                                    $valResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => $spec['label'] . " only alphanumeric accepted and must be started with letter!",
                                    );
                                }
                            }
                        }
                        //preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $str);
                    }
                }
                else {
                    cekbiru("skipped validating $fieldName");;
                }


            }
            if ($invalidCounter > 0) {//==ada yang tidak valid===
                return $valResults;
            }
            else {
                return true;
            }
        }
        else {
            //die("Nothing to validate");
            return true;
        }
    }

    public function editMany()
    {
        //        echo "9999";
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        if (!$this->allowEdit) {
            die("Sorry, you are now allowed to modifiy any of these data entries");
        }
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);

        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='$className'");
        $tmpTmp = $tData->lookupAll()->result();
        $dataProposals = array();
        if (sizeof($tmpTmp) > 0) {
            foreach ($tmpTmp as $row) {
                $mdlName = $row->mdl_name;


                $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                    "viewers" => array(),
                    "creators" => array(),
                    "creatorAdmins" => array(),
                    "updaters" => array(),
                    "updaterAdmins" => array(),
                    "deleters" => array(),
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
                        "id" => $row->_id,
                        "label" => $row->mdl_label,
                        "origID" => $row->orig_id,
                        "proposer" => $row->proposed_by_name,
                        "date" => $row->proposed_date,
                        "content" => unserialize(base64_decode($row->content)),
                        "propose_type" => $row->propose_type,
                    );
                }
            }
        }

        //</editor-fold>

        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;

        $this->load->model("Mdls/" . $className);
        $o = new $className;
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
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName?trashed=1'><span class='glyphicon glyphicon-ban-circle'></span> view deleted $ctrlName</a>";
                break;
            case "1":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName'><span class='glyphicon glyphicon-ok-sign'></span> view active $ctrlName</a>";
                break;
        }
        $o->addFilter("trash='$objState'");

        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $o->addFilter("folders='" . $_GET['fID'] . "'");
            //            $title.=" on ".$_GET['fName'];
        }

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }

        //        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
        //            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        //        }


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


            foreach ($dataProposals as $mdlName => $pSpec) {
                $this->load->model("Mdls/" . $mdlName);
                $o = new $mdlName();
                $listedFields = $this->listedFields;
                foreach ($pSpec as $dSpec) {
                    //                    echo "mulai mengiterasi kolom .. <br>";
                    $tmpItemTmp = array();
                    $dataStatus = $dSpec['origID'] > 0 ? "pembaruan" : "data baru";

                    foreach ($listedFields as $fName => $fLabel) {
                        $fRealName = $fName;
                        //                        $tmpItemTmp[$fName] = $dSpec['content'][$fRealName];
                        $fieldLabel = isset($dSpec['content'][$fRealName]) ? $dSpec['content'][$fRealName] : "";
                        //===if related
                        if (array_key_exists($fName, $this->relations)) {
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }
                        $tmpItemTmp[$fName] = $fieldLabel;
                    }


                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";

                    $tmpItemTmp["date"] = $dSpec['date'];
                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='javascript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }

            }

        }
        //</editor-fold>

        $addLink = base_url() . get_class($this) . "/add/$ctrlName";
        $addManyLink = base_url() . get_class($this) . "/addMany/$ctrlName";
        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $addLink .= "?reqField=" . $_GET['reqField'] . "&reqVal=" . $_GET['reqVal'];
        }


        // <editor-fold defaultstate="collapsed" desc="pagination">

        $params = array();
        $limit_per_page = 9;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $o->lookupDataCount($key);
        if ($total_records > 0) {
            // get current page records
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }
            $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);

            $config = array(
                'base_url' => base_url() . get_class($this) . '/' . __FUNCTION__ . "/$ctrlName/",
                'total_rows' => $total_records,
                'per_page' => $limit_per_page,
                "uri_segment" => 4,
                // custom paging configuration
                'num_links' => 6,
                'use_page_numbers' => TRUE,
                'reuse_query_string' => TRUE,
                'full_tag_open' => '<div class="text-center">',
                'full_tag_close' => '</div>',
                'first_link' => "<span class='fa fa-home'></span>",
                'first_tag_open' => '<span style="padding:1px;">',
                'first_tag_close' => '</span>',
                'last_link' => "<span class='fa fa-gg'></span>",
                'last_tag_open' => '<span style="padding:1px;">',
                'last_tag_close' => '</span>',
                'next_link' => "<span class='fa fa-angle-right'></span>",
                'next_tag_open' => '<span style="padding:1px;">',
                'next_tag_close' => '</span>',
                'prev_link' => "<span class='fa fa-angle-left'></span>",
                'prev_tag_open' => '<span style="padding:1px;">',
                'prev_tag_close' => '</span>',
                'cur_tag_open' => '<span class="btn btn-primary disabled">',
                'cur_tag_close' => '</span>',
                'num_tag_open' => '<span style="padding:1px;">',
                'num_tag_close' => '</span>',
            );
            $this->pagination->initialize($config);

            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
        // </editor-fold>

        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination
        $dataRow = array();
        //        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();


        $defaultKey = $key != "" ? $key : "cari " . strtolower($title);
        $content .= ($t->addSpanRow(array(
            "<div class='input-group'>" . "<input type=text placeholder='$defaultKey' class='form-control text-center' onkeyup=\"if(detectEnter()==1){location.href='" . base_url() . get_class($this) . "/view/$ctrlName/?k='+this.value}\">" . "<span class='input-group-addon'>" . "<i class='glyphicon glyphicon-search'></i></span>" . "</div class='input-group'>",
        )));


        $arrayHeader = array();
        $arrayItem = array();

        if (sizeof($tmp) > 0) {//===ada data


            //            arrPrint($this->relationPairs);die();

            // <editor-fold defaultstate="collapsed" desc="nomor baris di masing2 halaman">
            //$rowCounter = 0;
            if ($this->uri->segment(3) > 0) {
                $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
            }
            else {
                $rowCounter = 0;
            }// </editor-fold>


            // <editor-fold defaultstate="collapsed" desc="iterasi tampilan, jika bukan berupa selfCategory">
            $iCtr = 0;
            foreach ($tmp as $m => $rowSpec) {
                $iCtr++;

                $colCounter = 0;
                $rowCounter++;

                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

                $idxName = "nama";
                $linkHist = base_url() . get_class($this) . "/viewHistories/$ctrlName/" . $rowSpec->id;
                $historyClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$ctrlName histories ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                //foreach ($rowSpec as $n => $nx) {
                $tmpItem = array();
                //                foreach ($o->getListedFields() as $ofName => $label) {
                $jCtr = 0;
                $validRules = $o->getValidationRules();
                foreach ($o->getFields() as $asasas => $fSpec) {
                    $fieldSpec = $fSpec;

                    $ofName = $fSpec['kolom'];
                    $ofLenght = isset($fSpec['width']) ? "width:" . $fSpec['width'] . ";'" : "";
                    $fName = $ofName;

                    //                    if(array_key_exists($ofName,$o->getListedFields())){
                    $jCtr++;
                    if (array_key_exists($fieldSpec['kolom'], $validRules)) {
                        $suffix = "*";
                        $fStyle = "font-weight:bold;";
                    }
                    else {
                        $suffix = "";
                        $fStyle = "";
                    }

                    $arrayHeader[$ofName] = "<div class='' style='$ofLenght'><span style='$fStyle'>" . $fSpec['label'] . $suffix . "</span></div>";
                    //===if related
                    if (array_key_exists($ofName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$ofName][$rowSpec->$ofName]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$ofName][$rowSpec->$ofName] : "unknown rel";
                    }
                    else {
                        $fieldLabel = $rowSpec->$ofName;
                    }


                    switch ($fSpec['inputType']) {
                        case "hidden":
                            $fContent = "<input type=hidden name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "text":
                            $fContent = "<input type=text class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "number":
                            $fContent = "<input type=number class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "password":
                            $fContent = "<input type=password class='form-control' placeholder='" . $fieldSpec['label'] . "' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$fieldLabel'>";
                            break;
                        case "combo":

                            if (isset($fieldSpec['dataSource']) || isset($fieldSpec['reference'])) {
                                $fContent = "<select class='form-control' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "'>";
                                if (isset($fieldSpec['dataSource'])) {
                                    foreach ($fieldSpec['dataSource'] as $key => $val) {
                                        $selected = isset($rowSpec->$fName) && $key == $rowSpec->$fName ? "selected" : "";
                                        $fContent .= "<option value='$key' $selected>$val</option>";
                                    }
                                }
                                if (isset($fieldSpec['reference'])) {
                                    //                                    cekHitam($fieldSpec['reference']);
                                    //                                    arrprint($this->relations);
                                    $tmpMdlName = $fieldSpec['reference'];
                                    if (in_array($tmpMdlName, $this->relations)) {

                                        $rmdl = array_search($tmpMdlName, $this->relations);
                                        if (isset($this->relationPairs[$rmdl]) && sizeof($this->relationPairs[$rmdl])) {
                                            foreach ($this->relationPairs[$rmdl] as $key => $val) {
                                                $selected = isset($rowSpec->$fName) && $key == $rowSpec->$fName ? "selected" : "";
                                                $fContent .= "<option value='$key' $selected>$val</option>";
                                            }
                                        }

                                    }
                                }
                                $fContent .= "</select class='form-control'>";
                            }
                            else {
                                $fContent = "<input type=password class='form-control' disabled>";
                            }
                            break;
                        default:
                            $fContent = "-unknown-";
                            break;
                    }
                    //                    $tmpItem[$ofName] = str_replace(" ", "&nbsp;", $fieldLabel) . "&nbsp;";
                    if ($jCtr == 1) {
                        $fContent .= "<input type='hidden' name='ctr[]' value='$iCtr'>";
                    }
                    $tmpItem[$ofName] = $fContent;
                    $colCounter++;
                    //                    }

                }


                //                $tmpItem['history'] = "<a class='btn btn-default' href='javascript:void(0)' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span> history</a>";
                $content .= ($t->addRow($dataRow));
                if (sizeof($dataRel) > 0) {

                    $optClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'$title options',
                                        message: $('<div></div>').load('" . base_url() . get_class($this) . "/showRelOptions/$className/" . $rowSpec->id . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                    $tmpItem['option'] = "<a href='javascript:void(0)' onclick=\"$optClick\">" . "<span class='glyphicon glyphicon-option-vertical'></span>" . "</a>";
                }
                $arrayItem[] = $tmpItem;

            }//

            // </editor-fold>
            //endregion datacontent
        }


        if ($this->allowCreate) {
            $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New $title',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
            $strAddLink = "";
            $strAddLink .= "<div class='btn-group'>";
            $strAddLink .= "<button href='javascript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";

            $strAddLink .= "<button href='javascript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";

            $strAddLink .= "</div class='btn-group'>";


        }
        else {
            $strAddLink = "";
        }

        //        $arrayHeader = $o->getListedFields();

        if (sizeof($dataRel) > 0) {
            $arrayHeader["option"] = "<span class='glyphicon glyphicon-th-list'></span>";
        }


        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        //        $h->addFilter("orig_id='$selectedID'");
        $tmpH = $h->lookupRecentHistories()->result();

        $arrayRecap = array();
        if (sizeof($tmpH) > 0) {
            $tmpO = new $className();
            //            cekHere(json_encode($tmpO->getListedFields()));
            foreach ($tmpH as $row) {
                $tmpRecap = array();
                $content = unserialize(base64_decode($row->new_content));
                //cekHere(json_encode($content));
                foreach ($this->listedFields as $fName => $label) {

                    //                    $tmpRecap[$fName] = isset($content[$fName]) ? $content[$fName] : "";
                    //                    echo $content[$fName];

                    $fieldLabel = isset($content[$fName]) ? $content[$fName] : "";
                    //===if related
                    if (array_key_exists($fName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? "<span class='fa fa-folder-o'></span> " . $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                    }
                    $tmpRecap[$fName] = $fieldLabel;

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


        //        die(substr($ctrlName,strlen($ctrlName)-1));
        $titleSuffix = createObjectSuffix($realObjName);

        $alternateLink = "<button class='btn btn-success' id='btnSave' name='btnSave' onclick=\"this.disabled=true;document.getElementById('fmany').submit();\"><span class='glyphicon glyphicon-ok'></span> save entries</button>";

        if (isset($this->relationPairs) && array_key_exists("folders", $this->relationPairs)) {
            $folders = array("" => "HOME") + $this->relationPairs['folders'];
            $fmdlName = $this->relations['folders'];
            $fdataAccess = isset($this->config->item('heDataBehaviour')[$fmdlName]) ? $this->config->item('heDataBehaviour')[$fmdlName] : array(
                "viewers" => array(),
                "creators" => array(),
                "creatorAdmins" => array(),
                "updaters" => array(),
                "updaterAdmins" => array(),
                "deleters" => array(),
                "deleterAdmins" => array(),
                "historyViewers" => array(),
            );

            $allowCreateFolder = false;
            $allowEditFolder = false;
            $allowDeleteFolder = false;
            if (sizeof($mems) > 0 && sizeof($fdataAccess['creators']) > 0) {
                $allowCreateFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['updaters']) > 0) {
                $allowEditFolder = true;
            }
            if (sizeof($mems) > 0 && sizeof($fdataAccess['deleters']) > 0) {
                $allowDeleteFolder = true;
            }

            if ($allowCreateFolder) {
                $faddLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $fmdlName);
            }
            if ($allowEditFolder) {
                $fupdateLink = base_url() . get_class($this) . "/edit/" . str_replace("Mdl", "", $fmdlName) . "/";
            }
            if ($allowDeleteFolder) {
                $fdeleteLink = base_url() . get_class($this) . "/delete/" . str_replace("Mdl", "", $fmdlName) . "/";
            }

        }
        else {
            $folders = array();
        }

        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "title" => $realObjName . $titleSuffix,
            "subTitle" => "Modify $realObjName" . $titleSuffix,
            "historyTitle" => "<span class='glyphicon glyphicon-th-list'></span> Directly modify $title" . $titleSuffix . " below",
            "linkStr" => isset($params['links']) ? $params['links'] : "",
            "arrayHistoryLabels" => $arrayHeader,
            "arrayHistory" => $arrayItem,
            "onprogressTitle" => "<span class='glyphicon glyphicon-alert'></span> approval needed",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress" => $arrItemTmp,
            //            "entities" => $entities,
            "recapTitle" => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "arrayRecapLabels" => $arrayRecapLabel,
            "arrayRecap" => $arrayRecap,
            "strAddLink" => $strAddLink,
            "alternateLink" => $alternateLink,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?trashed=$objState",
            "formTarget" => base_url() . get_class($this) . "/doEditMany/" . $this->uri->segment(3),
            "folders" => $folders,
            "faddLink" => isset($faddLink) ? $faddLink : "",
            "feditLink" => isset($fupdateLink) ? $fupdateLink : "",
            "fdeleteLink" => isset($fdeleteLink) ? $fdeleteLink : "",
            "fmdlName" => isset($fmdlName) ? $fmdlName : "",
            "fmdlTarget" => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
        );
        $this->load->view('data', $data);
        $this->session->errMsg = "";
    }

    public function doEditMany()
    {
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);

        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $validRules = $o->getValidationRules();

        $inputTypeWhitelist = array("combo", "radio");
        $validRows = array();
        $inValidRows = array();
        //        arrPrint($_POST);
        if (isset($_POST['ctr']) && sizeof($_POST['ctr']) > 0) {
            $this->db->trans_start();
            foreach ($_POST['ctr'] as $ctr => $ctrx) {
                $filledCols = array();
                foreach ($fields as $fieldName => $fieldSpec) {
                    //                    cekHijau($fieldSpec['inputType']);
                    if (!in_array($fieldSpec['inputType'], $inputTypeWhitelist)) {
                        $inputName = $fieldSpec['kolom'];
                        if (isset($_POST[$inputName][$ctr]) && strlen($_POST[$inputName][$ctr]) > 0) {
                            cekKuning($inputName . " ada, yaitu " . $_POST[$inputName][$ctr]);
                            $filledCols[] = $inputName;
                        }
                    }

                }
                if (sizeof($filledCols) > 0) {
                    $diisi = true;
                }
                else {
                    $diisi = false;
                }
                if ($diisi) {//==barulah divalidasi
                    cekHijau("$ctr diisi");
                    $valResult = $this->lineValidate($o, $ctr, "edit");
                    if (is_array($valResult)) {
                        arrPrint($valResult);
                        cekMerah("$ctr TIDAK VALID");
                        $inValidRows[] = $ctr;

                        echo "<script>";
                        foreach ($valResult as $f => $fff) {
                            echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='#ffff00';";
                        }
                        echo "</script>";

                    }
                    else {
                        cekHijau("$ctr VALID");
                        $validRows[] = $ctr;
                        $data = array();
                        foreach ($fields as $fieldName => $fieldSpec) {
                            echo "<script>";
                            //                            foreach ($valResult as $f => $fff) {
                            //                                echo "top.document.getElementById('$f" . "_" . "$ctrx').style.backgroundColor='transparent';";
                            //                            }
                            echo "</script>";
                            $inputName = $fieldSpec['kolom'];
                            if (isset($_POST[$inputName][$ctr])) {
                                $data[$inputName] = $_POST[$inputName][$ctr];
                            }

                            //                            cekHijau("$f berisi ".$_POST[$inputName][$ctr]);
                        }

                        //                        arrPrint($data);die();
                        //<editor-fold desc="data temporer, jika pakai approval">
                        $where = array(
                            /*$indexFieldName =>*/
                            "id" => $data['id'],
                            //                "id"=> $data['id'],
                        );
                        $this->load->model("Mdls/" . "MdlDataTmp");
                        $dTmp = new MdlDataTmp();
                        $tmpData = array(
                            "orig_id" => $data['id'],
                            "mdl_name" => $className,
                            "mdl_label" => $ctrlName,
                            "proposed_by" => $this->session->login['id'],
                            "proposed_by_name" => $this->session->login['nama'],
                            "proposed_date" => date("Y-m-d H:i:s"),
                            "content" => base64_encode(serialize($data)),
                        );


                        if ($this->updaterUsingApproval) {
                            $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                            cekHijau($this->db->last_query());
                            $this->session->errMsg = "Data proposal has been saved and pending approval";

                            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/
                                "id" => $data['id'],
                            ))->result();
                            $o->setFilters(array());
                            $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());
                            cekMerah($this->db->last_query());

                            //<editor-fold desc="data history / propose">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id" => $data['id'],
                                "mdl_name" => $className,
                                "mdl_label" => get_class($this),
                                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                                "old_content_intext" => print_r((array)$tmpOrig, true),
                                "new_content" => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label" => "proposed",
                                "oleh_id" => $this->session->login['id'],
                                "oleh_name" => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>

                        }
                        else {
                            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/
                                "id" => $data['id'],
                            ))->result();
                            $o->setFilters(array());
                            $o->updateData($where, $data, $o->getTableName());
                            $this->session->errMsg = "Data has been updated";

                            //<editor-fold desc="data history / approve">
                            $this->load->model("Mdls/" . "MdlDataHistory");
                            $hTmp = new MdlDataHistory();
                            $tmpHData = array(
                                "orig_id" => $data['id'],
                                "mdl_name" => $className,
                                "mdl_label" => get_class($this),
                                "old_content" => base64_encode(serialize((array)$tmpOrig)),
                                "old_content_intext" => print_r((array)$tmpOrig, true),
                                "new_content" => base64_encode(serialize($data)),
                                "new_content_intext" => print_r($data, true),
                                "label" => "applied",
                                "oleh_id" => $this->session->login['id'],
                                "oleh_name" => $this->session->login['nama'],
                            );
                            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                            //</editor-fold>
                        }


                    }
                }
                else {
                    cekMerah("$ctr tidak diisi");

                }
            }

            if (sizeof($inValidRows) > 0) {
                echo "<script>";
                echo "top.document.getElementById('btnSave').disabled=false;";
                echo "</script>";
            }
            else {
                $this->db->trans_complete();
                echo "<script>";
                echo "top.location.reload();";
                echo "</script>";
            }

        }
        else {
            die("items required");
        }
    }

    public function myProfile()
    {
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        // cekHEre($className);
        $pro = new MdlUser();
        $log = new MdlActivityLog();
        $pro->setFilters(array());
        $arrProfile = $pro->lookupByID(my_id())->result()[0];
        // cekHere($this->db->last_query());
        // arrPrint($ss);
        // $arrProfile = array(
        //     "nama" => "Nina"
        // );
        // $a

        $updateFields = $pro->getListedUpdateFields();
        // arrPrint($updateFields);

        // matiHere();
        $condite = "uid='" . my_id() . "' order by id desc limit 10";
        $arrActivitylog = $log->lookupByCondition($condite)->result();
        $arrayListed = $log->getListedFields();
        //        arrPrint($arrayListed);
        $blackList = array("uname", "title", "sub_title", "method", "url");
        $arrHeader = array_diff_key($arrayListed, array_flip($blackList));


        //        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        //        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");
        //
        //        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        //        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]) ? $this->config->item('dataExtRelation')[$className] : array();

        $data = array(
            "mode" => $this->uri->segment(2),
            "template" => "application/template/profile.html",
            "title" => "Profile",
            "subTitle" => "",
            "updateFields" => $updateFields,
            "arrProfile" => $arrProfile,
            "arrActivitylog" => $arrActivitylog,
            "arrayHeader" => $arrHeader,
            "headTpl" => "",
            "footTpl" => footTpl(),
        );


        //endregion

        $this->load->view("data", $data);
    }

    public function editone()
    {
        $pro = new MdlUser();
        $pro->setFilters(array());
        $arrProfile = $pro->lookupByID(my_id())->result()[0];
        // cekHere($this->db->last_query());
        $arrField = $pro->getFields();
        $arrKolom = array();
        $arrKolom_alias = array();
        foreach ($arrField as $arrItem) {
            $arrKolom[] = $arrItem['kolom'];
            $arrKolom_alias[$arrItem['kolom']] = $arrItem['label'];
        }
        foreach ($arrKolom as $kolom) {
            $$kolom = $arrProfile->$kolom;
        }
        // arrPrint($arrField);
        $segment_3 = $this->uri->segment(4);
        switch ($segment_3) {
            case "password":
                $forms = array(
                    "Old" => form_password("old_$segment_3", "", "class='form-control' placeholder='old $segment_3' autocomplete='off' required"),
                    "New" => form_password("new_$segment_3", "", "class='form-control' placeholder='new $segment_3' autocomplete='off' required"),
                    "Retype" => form_password("re_$segment_3", "", "class='form-control' placeholder='retype $segment_3' autocomplete='off' required"),
                );
                break;
            case "email":
                $forms = array(
                    "Old" => form_input("old_$segment_3", "$email", "class='form-control' placeholder='old $segment_3' required disabled"),
                    "New" => "<input type='email' name='new_$segment_3' class='form-control' placeholder='new $segment_3' autocomplete='off' required>",
                );
                break;
            case "tlp_1":
                $forms = array(
                    "Old" => "<input type='text' disabled class='form-control' name='old_$segment_3' placeholder='old $segment_3' value='$tlp_1'>",
                    "New" => "<input required class='form-control' name='new_$segment_3' placeholder='new $segment_3'>",
                );
                break;
            default:
                $forms = "";
                mati_disini(__LINE__ . " " . __FILE__);
                break;
        }

        $data = array(
            "mode" => "modal",
            "field" => $segment_3,
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "heading" => "Edit " . $arrKolom_alias[$segment_3],
            "forms" => $forms,
            "footer" => form_submit("submit", "Save", "class='btn btn-primary pull-right'"),
            "target" => "result",
            "actions" => "/Data/editoneProcess/User",
            // "arrActivitylog" => $arrActivitylog,
            "headTpl" => headTpl(),
            "footTpl" => footTpl(),
        );
        $this->load->view("data", $data);
    }

    // edit account data per satu (one) data
    public function editoneProcess()
    {
        // arrPrint($_REQUEST);
        $id = my_id();
        arrPrint($this->input->post());
        $new_tlp_1 = $this->input->post('new_tlp_1');
        $old_password = $this->input->post('old_password');
        $new_password = $this->input->post('new_password');
        $new_email = $this->input->post('new_email');
        $re_password = $this->input->post('re_password');
        $field = $this->input->post('field');

        // cekHijau("$password != md5($old_password)");
        $pro = new MdlUser();
        $arrProfile = $pro->lookupByID(my_id())->result()[0];
        $arrField = $pro->getFields();
        foreach ($arrField as $kolom => $property) {

            $arrKolom[] = $kolom;
        }
        arrPrint($arrKolom);
        $arrKolom_alias = array();
        foreach ($arrField as $arrItem) {
            $arrKolom[] = $arrItem['kolom'];
            $arrKolom_alias[$arrItem['kolom']] = $arrItem['label'];
        }
        foreach ($arrKolom as $kolom) {
            $$kolom = $arrProfile->$kolom;
        }

        $this->db->trans_start();

        switch ($field) {
            case "password":
                if ($password != md5($old_password)) {
                    cekBiru($password . " " . md5($old_password));
                    $msg = "You not enter the right password<br>please re enter your <b>current password</b>";
                    echo lgShowAlert("", $msg);
                    matiHere($msg);

                }
                elseif ($new_password != $re_password) {
                    $msg = "your confirmation password is not match<br>please retype your new password";
                    echo lgShowError("", $msg);
                    matiHere($msg);
                }
                else {
                    echo lgShowSuccess("sip", "processing your request");

                    $this->db->trans_start();
                    $arrData = array(
                        "password" => md5($re_password),
                    );
                    $pro->updateData(array('id' => $id), $arrData);

                    $this->db->trans_complete();

                    // echo lgShowSuccess("success", "your password has been changed successfully done");
                    // echo lgShowSuccess("success", "your " . $arrKolom_alias[$field] . " has been changed successfully done");
                    // topReload(700);
                }
                break;
            case "email":
                $this->db->trans_start();
                $arrData = array(
                    "email" => $new_email,
                );
                $pro->updateData(array('id' => $id), $arrData);

                $this->db->trans_complete();

                // echo lgShowSuccess("success", "your " . $arrKolom_alias[$field] . " has been changed successfully done");
                // topReload(700);
                break;
            case "tlp_1":
                $this->db->trans_start();
                $arrData = array(
                    $field => $new_tlp_1,
                );
                $pro->updateData(array('id' => $id), $arrData);

                $this->db->trans_complete();

                // echo lgShowSuccess("success", "your " . $arrKolom_alias[$field] . " has been changed successfully done");
                // topReload(700);
                break;
            default:
                mati_disini(__LINE__ . " " . __FILE__ . " field::" . $field);
                break;
        }
        cekHere($this->db->last_query());

        // matiHere(__METHOD__ . " @" . __LINE__);
        $this->db->trans_complete();
        echo lgShowSuccess("success", "your " . $arrKolom_alias[$field] . " has been changed successfully done");
        topReload(500);


    }

    //region inporter folders
    public function importFolder()
    {
        arrPrint($this->uri->segment_array());
        $content = "";
        //        if (!isset($this->session->login['id'])) {
        //            gotoLogin();//remember last login
        //        }
        $className = "MdlFolderProduk";

        //        $ctrlName = $this->uri->segment(1);
        $this->load->helper("he_misc");
        $this->load->model("Mdls/" . $className);

        $f = new MdlFolderProduk();
        $tmp = $f->lookupAll()->result();
        //        cekHijau($this->db->last_query());
        $selectedFields = $f->getFields();
        //        arrPrint($tmp);
        $arrFolder = array();
        foreach ($tmp as $tmp_0) {
            $arrTemp = array();
            foreach ($selectedFields as $kolom => $arrFields_0) {
                $val = isset($tmp_0->$kolom) ? $tmp_0->$kolom : "";
                $arrTemp[$kolom] = $val;
            }
            //            $newTemp = rename_array_key($arrTemp,"id","used_id");
            $arrFolder[] = $arrTemp;

        }
        arrPrint($arrFolder);
        $this->db->trans_start();
        $this->load->model("Mdls/MdlFolder");
        $fo = new MdlFolder();
        foreach ($arrFolder as $data) {
            $fo->addData($data, $fo->getTableName());
            //            cekMerah("nginsert");
            cekHijau($this->db->last_query());
        }

        //arrPrint($arrFolder);
        matiHEre("kill udah selesai");
        $this->db->trans_complete();


    }
    //endregion

    public function doUpdStatus()
    {
        arrPrintKuning(url_segment());
        $id = url_segment(4);
        $new_status = url_segment(5);
        $segmen = url_segment(3);
        $mdl = "Mdl" . $segmen;
        cekBiru($mdl);

        $dt = new $mdl();
        $dt = new MdlEmployee();
        $dt->setFilters(array());

        $speks = $dt->lookupByID($id)->result();
        // arrPrint($speks);
        $cbId = $speks[0]->cabang_id;
        $nama = $speks[0]->nama;

        $new_datas = array(
            "status" => $new_status,
        );
        $wheres = array(
            "id" => $id
        );
        $this->db->trans_start();

        $dt->updateData($wheres, $new_datas);
        // showLast_query("biru");

        /*history*/
        $this->load->model("Mdls/" . "MdlDataHistory");
        $hi = new MdlDataHistory();
        $hisDatas = array(
            "dtime"       => dtimeNow(),
            "orig_id"     => $id,
            "mdl_name"    => $mdl,
            "mdl_label"   => "$segmen",
            "old_content" => blobEncode($speks),
            // "old_content_intext" =>
            "new_content" => blobEncode($new_datas),
            // "new_content_intext" =>
            // "data_id" =>
            "label"       => "status",
            "oleh_id"     => my_id(),
            "oleh_name"   => my_name(),
            // "trash" =>
            // "cabang_id " =>
        );
        $hi->addData($hisDatas);
        // showLast_query("kuning");

        $this->db->trans_commit();

        $msg = $cbId > 0 ? "$nama sebagai $segmen cabang" : "";
        echo lgShowSuccess("Berhasil diupdate", $msg);
        // topReload();

    }

    public function viewNonAktif()
    {

        // $this->className
        // cekBiru($this->className);
        $this->load->model("Mdls/" . $this->className);
        $o = new $this->className();
        // $sources = $o->lookupNonAktif();
        $sources = $o->lookupNonAktif()->result();
        // showLast_query("merah");
        // arrPrintPink($sources);
        $field = array(
            "kode" => array(),
            "nama" => array(),
            "no_part" => array(),
            // "btn" => array(),
        );
        $field_tambahan = array(
            "btn" => array(
                "label" => "action",
                "tipe" => "button",
                "value" => "aktifasi",
                "class" => "btn btn-success btn-xs",
                "link" => base_url() . "Data/status/" . $this->segment_3,
            ),
        );
        $forms = sizeof($sources) > 0 ? $sources : array();
        $data = array(
            "heading" => $this->segment_3 . " Non Aktif",
            'mode' => "modalView",
            "lebar_modal" => "lg-modal",
            "forms" => $forms,
            "field" => $field,
            "field_nomer" => array("label" => "no", "start" => 0),
            "field_tambahan" => $field_tambahan,
        );
        $this->load->view('data', $data);
    }

    public function saveSignature(){

        $fName = "img";

        if ($_FILES[$fName]['size'] > 0) {

            $request = curl_init(cdn_upload_images());
            $realpath = realpath($_FILES[$fName]['tmp_name']);
            curl_setopt($request, CURLOPT_POST, true);
            $fields = [
                'file' => new \CurlFile($realpath, $_FILES[$fName]['type'], $_FILES[$fName]['name']),
                'server_source' => $_SERVER['HTTP_HOST'],
            ];
            curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            $cUrl_result = json_decode(curl_exec($request));

            curl_close($request);

            if (isset($cUrl_result->status) && $cUrl_result->status == 'success') {
                $data[$fName] = $cUrl_result;
            }
            else {
                echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                die();
            }
        }
        else {
            echo "<script>top.swal('anda belum pilih photo/images', 'silahkan pilih images/photo lalu klik upload', 'error');</script>";
            die();
        }

        $data['post'] = $_POST;

        $referal_url = $_POST['referal'];

        if($data['img']->status == 'success'){

            $this->load->model("Mdls/" . "MdlUser");
            $o = new MdlUser();
            $where = array(
                "id" => $_POST['id']
            );
            $o->setFilters(array());
            $o->updateData($where, array("esignature_img" => $data['img']->full_url), $o->getTableName());

            echo "<script>top.swal('berhasil upload', 'E-Signature berhasil diupload dan diterapkan pada Account Anda.', 'success');</script>";
            echo "<script> top.setTimeout(function(){
                var thisUrl = '$referal_url&reload=' + Date.now();
                top.window.location.href = thisUrl;
            }, 1500)  </script>";
            die();
        }

    }
}
