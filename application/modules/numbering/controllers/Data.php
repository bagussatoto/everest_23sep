<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/* --------------------------------------------------
 * error php dihidden untuk memunculkanya matikan saja
 * ----------------------------------------------*/
if (!isset($_GET['debug_index']) || $_GET['debug_index'] != 1) {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(-1);
}
else {
    ini_set('display_errors', 1);
}

// --------------------------------------------------

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
    protected $sessionDatas;

    public function getSessionDatas()
    {
        return $this->sessionDatas;
    }

    public function setSessionDatas($sessionDatas)
    {
        $this->sessionDatas = $sessionDatas;
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

    public function setDeleterUsingApproval($deleterUsingApproval)
    {
        $this->deleterUsingApproval = $deleterUsingApproval;
    }

    public function __construct()
    {
        parent::__construct();
        // arrPrintPink(url_segment());
        $this->segment_2 = ucwords($this->uri->segment(2));
        $this->segment_3 = ucwords($this->uri->segment(3));
        $this->segment_4 = ucwords($this->uri->segment(4));
        $this->segment_5 = ucwords($this->uri->segment(5));
        $this->className = $className = "Mdl" . $this->segment_4;
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");

        //region relation translator
        $this->relations = array();
        $this->relationPairs = array();
        if (file_exists(APPPATH . "models/Mdls/$className.php")) {
            $this->load->model("Mdls/" . $className);
            $o = new $className();
            $fields = $o->getFields();
            foreach ($fields as $fName => $f2Spec) {
                if (isset($f2Spec['reference'])) {
                    $this->relations[$f2Spec['kolom']] = $f2Spec['reference'];
                    $this->load->model("Mdls/" . $f2Spec['reference']);
                    $o3 = new $f2Spec['reference']();
                    $tmp3 = $o3->lookupAll()->result();
                    if (sizeof($tmp3) > 0) {
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
                }
            }
        }
        //endregion

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

        $dataAccess = isset($this->config->item('heDataBehaviour')[$className]) ? $this->config->item('heDataBehaviour')[$className] : array(
            "viewers" => array("c_data", "c_holding", "c_owner", "root"),
            "creators" => array("c_holding"),
            "creatorAdmins" => array(),
            "updaters" => array("c_holding"),
//            "updaterAdmins" => array("root", "c_holding"),
            "updaterAdmins" => array(),
            "deleters" => array("c_holding"),
            "deleterAdmins" => array("root", "c_holding"),
            "historyViewers" => array("c_data", "c_holding", "c_owner"),
        );

        //    "MdlNumbering" => array(
//        "label" => "Setting Numbering",
//        "viewers" => array("c_data", "c_holding", "c_owner", "root"),
//        "creators" => array("c_holding"),
//        "creatorAdmins" => array(),
//        "updaters" => array("c_holding"),
//        "updaterAdmins" => array("root", "c_holding"),
//        "deleters" => array("c_holding"),
//        "deleterAdmins" => array("root", "c_holding"),
//        "historyViewers" => array("c_data", "c_holding", "c_owner"),
//    ),

        //region custom hak akses data
        $customDataAkses = availMenuData($this->session->login["id"]);
        $dataMemberAllowed = isset($customDataAkses["fungsi"]) ? $customDataAkses["fungsi"] : array();
        if (isset($dataMemberAllowed[$className])) {
            foreach ($dataMemberAllowed[$className] as $fs => $fsMember) {
                $dataAccess[$fs] = $fsMember;
            }
        }
        //endregion

        $ctrlName = $this->uri->segment(4);
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

        //---init listed-fields
        $className = "Mdl" . $this->uri->segment(4);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $this->listedFields = $o->getListedFields();
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        if ($isMob) {
            $this->listedFields = $o->getCompactListedFields();
        }

    }

    public function add()
    {
        // arrPrintHijau($_GET);
        $get_main = isset($_GET['main']) ? "?main=" . $_GET['main'] : "";
        $get_kval = isset($_GET['kval']) ? "&kval=" . $_GET['kval'] : "";
        $content = "";
        //        include_once 'leftMenu.php';
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        //==menampilkan form penambahan data berdasarkan datamodel (kelas data) yang bersesuaian
        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        //                cekBiru($className);
        //                arrPrint($_SESSION);
        $ctrlName = $segment_4;
        /* ----------------------------------------
         * untuk wizard persiapan data
         * ditambahakan di action form
         * ----------------------------------------*/
        $strSegment4 = "";
        if (isset($segment_4)) {
            $strSegment4 = "/$segment_4";
        }
        // -----------------------------------------

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
        // cekHere($className);
        $maximumData = $o->getMaximumData();

        // cekBiru("$maximumData");
        $f = new MyForm($o, "add", array(
            "id" => "f1add_" . $className,
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/addProcess/$ctrlName",
            "target" => "result",
            "class" => "form-horizontal",
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
        // ----------------------------------------
        $pf = isset($_GET['pfid']) ? trim($_GET['pfid']) : "";

        $f->openForm(MODUL_PATH . get_class($this) . "/addProcess/$ctrlName" . $strSegment4 . $get_main . $get_kval);
        $f->fillForm($className, $xxx, $pf);
        $f->closeForm();
        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Penambahan Data $title", "application/template/lte/index.html");

        $content .= "<div class='panel panel-success'>";
        $content .= "<div class='panel-heading'>";

        $content .= "<span class='text-blue text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";

        $content .= "<div class='panel-body'>";

        if ($this->creatorUsingApproval) {
            $content .= "<div class='panel-body'>";
            $content .= "<div class='alert alert-warning-dot text-center'>";
            $content .= "This action will require approval";
            $content .= "</div>";
            $content .= "</div class='panel-body'>";
        }

        $content .= $f->getContent();

        $content .= "</div>";
        $content .= "</div>";

//        $content .= "\n
//
//<script>
//
//            var table = top.$('#table_editor_number').DataTable({
//                info: false,
//                paging: false,
//                searching: false,
//                ordering: false,
//                bAutoWidth: true,
//                colReorder: true
//            });
//            $( table.table().container() ).removeClass( 'form-inline' );
//            table.on( 'column-reorder', delay_v2( function ( e, settings, details ) {
//                showNumbering();
//            }, 2500) );
//            top.$('.urut_numbering').off();
//            top.$('.urut_numbering').on('keyup', function(){
//                showNumbering();
//            })
//
//            function showNumbering(){
//                var dtime       = $(\"input[name='show_dtime']:checked\").val();
//                var format_dtime     = $(\"input[name='format_dtime']:checked\").val();
//                var arrUrutNumber = $('.urut_numbering');
//                var hasil_number = '';
//                top.jQuery.each(arrUrutNumber, function(a, b){
//                    hasil_number += $(b).val();
//                    console.log(b);
//                })
//
//                $('.hasil_sample_numbering').html('<h3>'+hasil_number+'</h3>')
//                console.log(hasil_number);
//            }
//
//            showNumbering();
//    </script>
//
//
//        ";

        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content" => $content,
        );
        echo $content;
        die();
    }

    //INI KATEGORI UTAMA
    public function addProduk()
    {

        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        // $kval = $_GET['kval'];
        // $this->load->model("Mdls/" . $className);
        // $o = new $className;
        $this->load->model("Mdls/MdlProduk");
        $o = new MdlProduk();

        $xclass = $this->segment_4;

        $kat = "";
        $kat .= "<r><b>PILIH KATEGORI UTAMA: &nbsp;&nbsp;&nbsp;&nbsp;</b></r>";

        $kategories = $o->getMasterFields();
        $no = 0;
        foreach ($kategories as $item => $item_val) {
            $no++;
            $item_jml_serial = isset($item_val['jml_serial']) ? $item_val['jml_serial'] : 0;
            $item_label = isset($item_val['label']) ? $item_val['label'] : $item;
            $item_mdl = isset($item_val['mdl']) ? $item_val['mdl'] : "";
            $item_kategori = isset($item_val['kategori']) ? $item_val['kategori'] : "none";

            if (empty($item_val['anakan'])) {
                //mode radio
                $kat .= "<label style='padding-right: 10px;' class='text-uppercase'><input class='flat-redx' type='radio' name='$item_kategori' id='$item' onclick=\"loadSubMain('$item','$item_mdl');\">&nbsp;$item_label</label>";
                //mode button
                //                $kat .= "<button type='button' id='$item' class='btn btn-info text-uppercase' onclick=\"loadSubMain('$item','$item_mdl');\">$item_label</button> ";
            }
            else {
                //$kat .= "<button type='button' id='$item' class='btn btn-primary text-uppercase' onclick=\"loadAnakan('$item','$item_jml_serial');\">$item_label</button> ";
            }
        }
        $kat .= "<div style='margin-top: 5px;' id='wadah_sub'></div>";
        $kat .= "<hr>";

        $var = "";
        $var .= $kat;
        $var .= "<div id='wadah_1'>";
        $var .= "</div>";

        $kval = isset($_GET['kval']) ? $_GET['kval'] : "";
        $kn = isset($kategories[$kval]['jml_serial']) ? $kategories[$kval]['jml_serial'] : "";
        $link_form = MODUL_PATH . "Data/addProdukForm/$xclass";
        $link_sub = MODUL_PATH . "Data/addProdukSub/$xclass";
        $var .= "<script>
            var kval = '$kval', kn = '$kn';
            if(kval != ''){
                $('#wadah_1').load('$link_form?kval='+ kval +'&n=' + kn);
            };
            function loadAnakan(x,n) {
              $('#wadah_sub').html('');
              $('#wadah_1').load('$link_form?kval='+ x +'&n=' + n);
            }
            function loadSubMain(x,n) {
              $('#wadah_sub').load('$link_sub?kval='+ x +'&n=' + n);
              $('#wadah_1').html('');
            }
        </script>";

        echo $var;
    }

    public function addProdukForm()
    {

        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-2 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-10");

        $get_main = isset($_GET['main']) ? "?main=" . $_GET['main'] : "";
        $segment_4 = $this->uri->segment(4);
        $ctrlName = $segment_4;
        $className_main = $className = "Mdl" . $segment_4;
        $kval = $_GET['kval'];
        $kn = $_GET['n'];

        $this->load->model("Mdls/MdlProduk");
        $o = new $className;
        $fields = $o->getFields();
        $masterFields = $o->getMasterFields();

        $anakans = $masterFields[$kval]['anakan'];
        $field_2 = array_intersect_key($fields, array_flip($anakans));

        $fvar = "";
        $varForm = "";
        $strForm = "";
        foreach ($anakans as $anakan_ky) {
            $coSpeks = $fields[$anakan_ky];
            $inputType = $coSpeks['inputType'];
            $label = $coSpeks['label'];
            $defaultValue = isset($coSpeks['defaultValue']) ? $coSpeks['defaultValue'] : "-";
            $fValue = $defaultValue;
            $kolom = isset($coSpeks['kolom']) ? $coSpeks['kolom'] : "";
            $kolom_nama = isset($coSpeks['kolom_nama']) ? $coSpeks['kolom_nama'] : "";
            $fName = $kolom_nama != "" ? $kolom_nama : $kolom;

            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference;
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                $dataSrcs = $o2->lookupAll()->result();
                $dataSources = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->id) ? $label_src->id : "";
                    $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                    $dataSources[$relId] = $relLabel;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }

            $fvar .= "$anakan_ky";

            switch ($inputType) {
                case "combo":
                    $reference_label = strtoupper($label);
                    $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    $btn_add = isset($coSpeks['add_btn']) ? "<span class='input-group-btn'><button type='button' class='btn btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></span>" : "<span></span>";
                    $optionals = "<option value=''>----$label------</option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option value='$key_src' $fSelected>$label_src</option>";
                    }
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select class='form-control' name='$kolom'>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "radio":
                    $varForm = "";
                    foreach ($dataSources as $key_src => $label_src) {
                        $varForm .= "<label><input type='radio' id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src'> $label_src</label>";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' class='form-control' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "number":
                    $varForm = "<input type='number' id='$anakan_ky' name='$kolom' class='form-control' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden":
                    $fValue = $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
            }
        }

        /* -------------------------------------------------------------------------------------
         * button sumit
         * -------------------------------------------------------------------------------------*/
        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= "<button type='submit' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "</div>";

        /* -------------------------------------------------------------------------------------
         * penampil form di UI
         * -------------------------------------------------------------------------------------*/
        $link_action = MODUL_PATH . "Data/addProcess/Produk/Produk";
        $var = "";
        $var .= "<style type='text/css'>
            .form-group{
                margin-bottom: 2px !important;
            }
        </style>";
        $var .= "<div class='row'>";
        $var .= "<form method='post' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";

        echo $var;
    }

    // INI SUB KATEGORI
    public function addProdukSub()
    {
        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        $kval = isset($_GET['kval']) ? $_GET['kval'] : "";
        $this->load->model("Mdls/MdlProduk");
        $o = new MdlProduk();

        $xclass = $this->segment_4;
        $kat = "";
        $kat .= "<r><b>PILIH SUB KATEGORI: &nbsp;&nbsp;&nbsp;&nbsp;</b></r>";

        $masterKategory = $o->getMasterFields();
        $kategories = $o->getMasterSubs();

        foreach ($masterKategory as $item => $item_val) {
            $label = $item_val['label'];
            if ($kval == $item) {
                //                $kat .= "<span id='$item' style='font-size: 14px;' class='text-uppercase text-bold text-red'><u>$label&nbsp;&nbsp;&nbsp;</u></span> ";
            }
        }

        $no = 0;
        foreach ($kategories as $item => $item_val) {
            $no++;
            $item_jml_serial = isset($item_val['jml_serial']) ? $item_val['jml_serial'] : 0;
            $item_kategori = isset($item_val['kategori']) ? $item_val['kategori'] : 0;
            $item_label = isset($item_val['label']) ? $item_val['label'] : $item;
            $type = isset($item_val['type']) ? $item_val['type'] : "";
            $id = isset($item_val['id']) ? $item_val['id'] : "";
            $sub_kategori_id = isset($item_val['sub_kategori_id']) ? $item_val['sub_kategori_id'] : "";
            $title = isset($item_val['title']) ? $item_val['title'] : "";
            if ($type == $kval) {
                $kat .= "<label style='padding-right: 10px;' class='text-uppercase'><input style='margin-top: 6px;' type='radio' name='$type' id='$id' title='$title' class='flat-redx btn-sub' onclick=\"loadAnakanSub('$id','$sub_kategori_id', '$item_jml_serial','$item_kategori');\">&nbsp;<b>$item_label</b></label>";
                //mode button
                //                $kat .= "<button style='margin-top: 6px;' type='button' id='$item' title='$title' class='btn btn-info text-uppercase btn-sub' onclick=\"loadAnakanSub('$item','$item_jml_serial','$item_kategori');\"><b>$item_label</b> <div class='small text-white'><i>$title</i></div></button> ";
            }
        }

        //$kat .= "<span style='' type='button' id='0' title='add menu' class='btn btn-xs btn-flat btn-warning text-uppercase' onclick=\"addMenuForm('$kval','','');\"><i class='fa fa-plus'></i></span>";

        $kat .= "<div id='' class='sub_title'><div style='margin-top: 25px;' class='text-bold text-red box box-solid box-header box-danger text-center text-uppercase fa-2x'><span class=' blink'>Silahkan Pilih Sub Menu</span></div></div>";

        $var = "";
        $var .= $kat;

        $kn = isset($kategories[$kval]['jml_serial']) ? $kategories[$kval]['jml_serial'] : "";
        $kk = isset($kategories[$kval]['kategori']) ? $kategories[$kval]['kategori'] : "";
        $link_form = MODUL_PATH . "Data/addProdukFormSub/$xclass";
        $link_addmenu = MODUL_PATH . "Data/addMenuFormSub/$xclass";
        $var .= "<script>
                    function loadAnakanSub(x,s,n,k) {
                      $('#wadah_1').load('$link_form?kval='+ x +'&n=' + n + '&k='+k+'&s='+s);
                      $('.btn-sub').removeClass('on');
                      $('#'+x).addClass('on');
                      $('.sub_title').html('');
                    }
                    function addMenuForm(x,n,k) {
                        top.BootstrapDialog.show(
                           {
                                title:'Add Menu',
                                message: $('<div></div>').load('$link_addmenu?kval='+ x +'&n=' + n + '&k='+k),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );
                    }
                </script>";

        echo $var;
    }

    /* ------------------------------------
     * form tambah produk
     * ------------------------------------*/
    public function addProdukFormSub()
    {
        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-2 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-10");
        $get_main = isset($_GET['main']) ? "?main=" . $_GET['main'] : "";
        $segment_4 = $this->uri->segment(4);
        $ctrlName = $segment_4;
        $className_main = $className = "Mdl" . $segment_4;
        $kval = $_GET['kval'];
        $sid = $_GET['s'];
        $kn = $_GET['n'];
        $kk = $_GET['k'];
        $this->load->model("Mdls/MdlProduk");
        $o = new $className;
        $fields = $o->getFields();
        $masterFields = $o->getMasterSubs();

        //        echo json_encode($masterFields);

        $anakans = $masterFields[$kval]['anakan'];
        $sub_kategori = isset($masterFields[$kval]['sub_kategori']) ? $masterFields[$kval]['sub_kategori'] : "";
        $validationRules = $o->getValidationRules();
        $requireds = array();
        foreach ($validationRules as $kolom => $validationRule) {
            if (in_array("required", $validationRule)) {
                $requireds[] = $kolom;
            }
        }

        $field_2 = array_intersect_key($fields, array_flip($anakans));
        $fvar = "";
        $varForm = "";
        $strForm = "";
        foreach ($anakans as $anakan_ky) {
            // arrPrintKuning($anakan_ky);
            $coSpeks = isset($fields[$anakan_ky]) ? $fields[$anakan_ky] : array();
            // ----------------------------------------------
            $mdlChilds = isset($coSpeks['mdlChild']) ? $coSpeks['mdlChild'] : "";
            // $mdlChild = is_array($mdlChilds) && in_array($anakan_ky, $mdlChilds) ? $anakan_ky : "";
            $mdlChild_json = is_array($mdlChilds) ? base64_encode(json_encode($mdlChilds)) : $mdlChilds;
            // arrPrintPink($mdlChilds);
            // arrPrintHijau($coSpeks);
            $inputType = isset($coSpeks['inputType']) ? $coSpeks['inputType'] : "";
            $editable = isset($coSpeks['editable']) && $coSpeks['editable'] == false ? "readonly" : "";
            // $show = $label = isset($coSpeks['show']) && $coSpeks['show'] == true ? "" : "style='display:none;'";
            $show = isset($coSpeks['show']) ? "1" : "0";
            $str_label = $label = isset($coSpeks['label']) ? $coSpeks['label'] : "";
            $required_field = "";
            $onChange = "";
            if (in_array($anakan_ky, $requireds)) {
                $label .= "&nbsp; <r>*</r>";
                $required_field = "required";
                // $onChange = "onchange=\"createSession(this, '$mdlChild_json');\"";
            }
            else {
                $required_field = "";
            }
            $onChange = "onchange=\"createSession(this, '$mdlChild_json');\"";
            // cekMerah("$anakan_ky ==== $required_field");

            $defaultValue = isset($coSpeks['defaultValue']) ? $coSpeks['defaultValue'] : "";
            $fValue = $defaultValue;
            // ----------------------------------------------
            $kolom = isset($coSpeks['kolom']) ? $coSpeks['kolom'] : "";
            $kolom_nama = isset($coSpeks['kolom_nama']) ? $coSpeks['kolom_nama'] : "";
            $fName = $kolom_nama != "" ? $kolom_nama : $kolom;

            $strField = isset($coSpeks['strField']) ? $coSpeks['strField'] : "nama";
            $indexFields = isset($coSpeks['indexFields']) ? $coSpeks['indexFields'] : "id";
            $referenceClass = "";
            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference;
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                if (isset($_SESSION['data']['supplier_id'])) {
                    $condites = array(
                        "supplier_id" => $_SESSION['data']['supplier_id'],
                    );
                }
                $dataSrcs = $o2->lookupAll()->result();
                $dataSources = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->$indexFields) ? $label_src->$indexFields : "";
                    $relLabel = isset($label_src->$strField) ? $label_src->$strField : "";
                    $dataSources[$relId] = $relLabel;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }

            $btn_add = "<div></div>";
            if (isset($coSpeks['add_btn'])) {
                $reference_label = strtoupper($label);
                $link_editor_act = base_url() . "statik/Data/viewdt/$referenceClass";
                $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                $btn_add = "<div class='input-group-append'>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button>";
                $btn_add .= "<button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"btn_confirm_alert('Upss..!','Anda akan meninggalkan halaman ini? <br><r>Semua data yang belum disimpan akan hilang!</r>','$link_editor_act')\"><i class='fa fa-pencil'></i></button>";
                $btn_add .= "</div>";
            }

            $fvar .= "$anakan_ky";

            switch ($inputType) {
                case "combo-blank":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> silahkan pilih data sebelumnya </option>";
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "combo-hidden":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";

                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
                case "combo":
                    // $reference_label = strtoupper($label);
                    // $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    // $link_editor_act = base_url() . "statik/Data/viewdt/$referenceClass";
                    // $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    // $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button><button type='button' class='btn btn-sm btn-flat btn-info' onclick=\"location.href='$link_editor_act'\"><i class='fa fa-pencil'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";

                    if (count($dataSources) == 0) {
                        $optionals = "<option value=''> SILAHKAN TAMBAHKAN DATA </option>";
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-danger' data-live-search='false' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }
                    else {
                        $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-placeholder='cari data' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field ky='$anakan_ky' name='$kolom' $onChange>";
                    }

                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "radio":
                    $varForm = "";
                    foreach ($dataSources as $key_src => $label_src) {
                        $varForm .= "<label><input class='flat-redx' type='radio' id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src'> $label_src</label>";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<div class='form-group row'>";
                    $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='text' $required_field $editable id='$anakan_ky' autocomplete='off' placeholder='$str_label' name='$kolom' class='form-control' value='$fValue'>";
                    $varForm .= "</div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm, $show);
                    break;
                case "number":
                    $varForm = "<input type='number' id='$anakan_ky' name='$kolom' class='form-control' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden":
                    $fValue = $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
            }
        }

        if ($kval != "") {
            $varForm = "<input type='text' id='kval' name='kval' value='$kval'>";
            $strForm .= $ly->form_group("", $varForm, 1);
        }

        /* -------------------------------------------------------------------------------------
         * button sumit
         * -------------------------------------------------------------------------------------*/
        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= "<button type='submit' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "</div>";

        /* -------------------------------------------------------------------------------------
         * penampil form di UI
         * -------------------------------------------------------------------------------------*/
        $link_action = MODUL_PATH . "Data/addProcess/Produk/Produk";
        $createSession_link = MODUL_PATH . "Data/createSessionData/Produk";
        $var = "";
        $var .= "<script>
                    function createSession(x,y) {
                        var name = x.name;
                        var val  = x.value;
                        var mode = $(x).attr('kval')
                        var ky = $(x).attr('ky')
                        $.ajax({
                            url: '$createSession_link/'+mode+'/'+name+'?val='+val+'&target='+y+'&ky='+ky,
                            success: function(a){
                                var arrData = JSON.parse(a);
                                var target_id = arrData.datas.target;
                                if(arrData.arrSelect){
                                    jQuery.each(arrData.arrSelect, function(a, b){
                                        $('select[name='+a+']').parent().html(b);
                                        setTimeout(function(){
                                            $('.select2').selectpicker('restart');
                                        },300);
                                        if(arrData.arrSelectJs){
                                            setTimeout( function(){ eval(arrData.arrSelectJs[a]) },200)
                                        }
                                    });
                                }
                            }
                        });
                    }
                </script>";
        $var .= "<style type='text/css'>.form-group{ margin-bottom: 2px !important; }</style>";
        $var .= "<div class='row'>";
        $var .= "<div id='loading'></div>";
        $var .= "<form method='post' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";

        if ($sub_kategori != "") {
            $var .= "<script>
                    top.$('select[name=sub_kategori_id]').val($sub_kategori).change();
                 </script>";
        }

        //khusus untuk nembak value jml serial
        $var .= "<script>
                    top.$('#jml_serial').val($kn);
                    top.$('select[name=kategori_id]').val($kk).change();
                    top.$('select[name=sub_kategori_id]').val($sid).change();
                    setTimeout(function(){
                        top.$('.select2').selectpicker({dropdownParent:$('body')}).selectpicker();
                        $('input[type=checkbox].flat-red, input[type=radio].flat-red').iCheck({
                            checkboxClass: 'icheckbox_flat-green',
                            radioClass: 'iradio_flat-green'
                        });
                        $('#loading').hide();
                    }, 250);
                 </script>";
        echo $var;
    }

    public function createSessionData()
    {
        $kategori = url_segment(5);
        $kolom = url_segment(6);
        $anakan_ky = isset($_GET['ky']) ? $_GET['ky'] : "";
        $datas = $_GET;

        $this->load->model("Mdls/MdlProduk");
        $o = new MdlProduk();
        $fields = $o->getFields();
        // matiHere();
        $validationRules = $o->getValidationRules();
        // arrPrintPink($validationRules);

        $requireds = array();
        if (sizeof($validationRules) > 0) {
            foreach ($validationRules as $kolomRule => $validationRule) {
                if (in_array("required", $validationRule)) {
                    $requireds[] = $kolomRule;
                }
            }
        }

        $required_field = "";
        // $onChange = "";
        // if (in_array($anakan_ky, $requireds)) {
        //     $required_field = "required";
        //     // $onChange = "onchange=\"createSession(this, '$mdlChild_json');\"";
        // }
        // else {
        //     $required_field = "none";
        // }
        // cekHere($anakan_ky);
        // arrPrintHijau($requireds);
        // cekMerah("$required_field");

        $arrSelect = array();
        $arrSelectJs = array();
        $strSelect = "";

        switch ($kolom) {
            case "supplier_id":
                $arrFields = $fields['supplier'];
                $arrSelector = json_decode(base64_decode($datas['target']));
                if (!empty($arrSelector)) {
                    foreach ($arrSelector as $k) {
                        $strSelect = "";
                        $kol = $fields[$k]['kolom'];
                        //membuat selector MEREK
                        $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='120' data-container='body' class='selectpicker form-controlx select2 show-tick' required='' ky='$anakan_ky' name='merek_id' onchange=\"createSession(this, '$kol');\">";
                        $strSelect .= "<option value=''> --- pilih merek --- </option>";
                        $this->load->model("Mdls/MdlMerek");
                        $o2 = new MdlMerek();
                        $o2->addFilter("supplier_id='" . $datas['val'] . "'");
                        $dataSrcs = $o2->lookupAll()->result();
                        $lastQuery = $this->db->last_query();
                        foreach ($dataSrcs as $key_src => $label_src) {
                            $relId = isset($label_src->id) ? $label_src->id : "";
                            $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                            $strSelect .= "<option supplier_id='" . $datas['val'] . "' value='$relId'>$relLabel</option>";
                        }
                        $strSelect .= "</select>";
                        $arrSelect[$kol] = $strSelect;

                        if (count($dataSrcs) == 1) {
                            $idSelect = $dataSrcs[0]->id;
                            $arrSelectJs['merek_id'] = "$('select[name=merek_id]').val($idSelect).trigger('change');";
                            // $arrSelectJs['outdoor_id'] = "$('select[name=outdoor_id]').val($idSelect).trigger('change');";
                        }
                    }
                }

                $_SESSION['data']['supplier_id'] = $datas['val'];
                // $_SESSION['data']['supplier_nama'] = $relLabel;
                // unset($_SESSION['data']);
                break;
            case "merek_id":
                $arrSelector = $fields['merek_nama']['mdlChild'];
                foreach ($arrSelector as $k) {
                    $strSelect = "";
                    $kol = $fields[$k]['kolom'];
                    $label = $fields[$k]['label'];
                    $reference = $fields[$k]['reference'];
                    //membuat selector MEREK
                    $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='100' $required_field data-container='body' class='selectpicker form-controlx select2 show-tick' ky='$anakan_ky' name='$kol'>";
                    $strSelect .= "<option value=''> --- pilih $label --- </option>";
                    $this->load->model("Mdls/$reference");
                    $o2 = new $reference;
                    $o2->addFilter("merek_id='" . $datas['val'] . "'");
                    $dataSrcs = $o2->lookupAll()->result();
                    foreach ($dataSrcs as $key_src => $label_src) {
                        $relId = isset($label_src->id) ? $label_src->id : "";
                        $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                        $strSelect .= "<option merk_id='" . $datas['val'] . "' value='$relId'>$relLabel</option>";
                    }
                    $strSelect .= "</select>";
                    $arrSelect[$kol] = $strSelect;
                    // if (count($dataSrcs) == 1) {
                    //     $idSelect = $dataSrcs[0]->id;
                    //     $arrSelectJs[$kol] = "$('select[name=$kol]').val($idSelect).trigger('change');";
                    // }

                    $_SESSION['data'][$kolom] = $datas['val'];
                }
                break;
            case "size_id":
                $arrFields = $fields['skala'];
                $arrSelector = json_decode(base64_decode($datas['target']));
                if (!empty($arrSelector)) {
                    foreach ($arrSelector as $k) {
                        $strSelect = "";
                        $kol = $fields[$k]['kolom'];
                        //membuat selector MEREK
                        $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='130' data-container='body' class='selectpicker form-controlx select2 show-tick' name='tipe_id' onchange=\"createSession(this, '$kol');\">";
                        $strSelect .= "<option value=''> --- pilih size/skala --- </option>";
                        $this->load->model("Mdls/MdlTipe");
                        $o2 = new MdlTipe();
                        $o2->addFilter("size_id='" . $datas['val'] . "'");
                        $dataSrcs = $o2->lookupAll()->result();
                        $lastQuery = $this->db->last_query();
                        // showLast_query("merah");
                        foreach ($dataSrcs as $key_src => $label_src) {
                            $relId = isset($label_src->id) ? $label_src->id : "";
                            $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                            $strSelect .= "<option size_id='" . $datas['val'] . "' value='$relId'>$relLabel</option>";
                        }
                        $strSelect .= "</select>";
                        $arrSelect[$kol] = $strSelect;

                        if (count($dataSrcs) == 1) {
                            $idSelect = $dataSrcs[0]->id;
                            $arrSelectJs['tipe_id'] = "$('select[name=tipe_id]').val($idSelect).trigger('change');";
                            // $arrSelectJs['outdoor_id'] = "$('select[name=outdoor_id]').val($idSelect).trigger('change');";
                        }
                    }
                }

                $_SESSION['data']['size_id'] = $datas['val'];
                // unset($_SESSION['data']);
                break;
            case "produk_part_kategori_id":
                $arrSelector = $fields['part_kategori_id']['mdlChild'];
                foreach ($arrSelector as $k) {
                    $strSelect = "";
                    $kol = $fields[$k]['kolom'];
                    $label = $fields[$k]['label'];
                    $reference = $fields[$k]['reference'];
                    //membuat selector .....
                    $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='130' data-container='body' class='selectpicker form-controlx select2 show-tick' name=$kol onchange=\"createSession(this, '$kol');\">";
                    // $strSelect .= "<select kval='$kategori' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='100' $required_field data-container='body' class='selectpicker form-controlx select2 show-tick' ky='$anakan_ky' name='$kol'>";
                    $strSelect .= "<option value=''> --- pilih $label --- </option>";
                    $this->load->model("Mdls/$reference");
                    $o2 = new $reference;
                    $o2->addFilter("kategori_id='" . $datas['val'] . "'");
                    $dataSrcs = $o2->lookupAll()->result();
                    foreach ($dataSrcs as $key_src => $label_src) {
                        $relId = isset($label_src->id) ? $label_src->id : "";
                        $relLabel = isset($label_src->nama) ? $label_src->nama : "";
                        $strSelect .= "<option kategori_id='" . $datas['val'] . "' value='$relId'>$relLabel</option>";
                    }
                    $strSelect .= "</select>";
                    $arrSelect[$kol] = $strSelect;
                    if (count($dataSrcs) == 1) {
                        $idSelect = $dataSrcs[0]->id;
                        $arrSelectJs[$kol] = "$('select[name=$kol]').val($idSelect).trigger('change');";
                    }

                    $_SESSION['data'][$kolom] = $datas['val'];
                }
                break;
        }

        $result = array(
            "datas" => $datas,
            "segment" => $this->uri->segment_array(),
            "select" => $strSelect,
            "arrSelect" => $arrSelect,
            "arrSelectJs" => $arrSelectJs,
        );

        echo json_encode($result);
    }

    public function addMenuFormSub()
    {

        $ly = new Layout();
        $ly->setFormGroupLeftClass("col-sm-2 text-uppercase");
        $ly->setFormGroupRightClass("col-sm-10");

        $kval = $_GET['kval'];
        $kn = $_GET['n'];
        $kk = $_GET['k'];

        $segment_4 = $this->uri->segment(4);
        $ctrlName = $segment_4;
        $className_main = $className = "Mdl" . $segment_4;
        $this->load->model("Mdls/MdlMenuSubStatik");
        $o = new MdlMenuSubStatik();
        $fields = $o->getFields();

        $anakans = array(
            "label",
            "jml_serial",
            "id",
            "kategori",
            "sub_kategori",
        );

        $validationRules = $o->getValidationRules();
        $requireds = array();
        foreach ($validationRules as $kolom => $validationRule) {
            if (in_array("required", $validationRule)) {
                $requireds[] = $kolom;
            }
        }

        $field_2 = array_intersect_key($fields, array_flip($anakans));
        $fvar = "";
        $varForm = "";
        $strForm = "";

        foreach ($anakans as $anakan_ky) {
            $coSpeks = $fields[$anakan_ky];
            $inputType = $coSpeks['inputType'];
            $str_label = $label = $coSpeks['label'];
            $required_field = "";
            $onChange = "";
            if (in_array($anakan_ky, $requireds)) {
                $label .= "&nbsp; <r>*</r>";
                $required_field = "required";
            }
            $defaultValue = isset($coSpeks['defaultValue']) ? $coSpeks['defaultValue'] : "";
            $fValue = $defaultValue;
            // ----------------------------------------------
            $kolom = isset($coSpeks['kolom']) ? $coSpeks['kolom'] : "";
            $kolom_nama = isset($coSpeks['kolom_nama']) ? $coSpeks['kolom_nama'] : "";
            $fName = $kolom_nama != "" ? $kolom_nama : $kolom;

            $strField = isset($coSpeks['strField']) ? $coSpeks['strField'] : "nama";

            if (isset($coSpeks['reference'])) {
                $reference = $coSpeks['reference'];
                $referenceClass = $reference ? substr($reference, 3) : "";
                $this->load->model("Mdls/" . $reference);
                $o2 = new $reference;
                $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                if (isset($_SESSION['data']['supplier_id'])) {
                    $condites = array(
                        "supplier_id" => $_SESSION['data']['supplier_id'],
                    );
                }
                $dataSrcs = $o2->lookupAll()->result();
                $dataSources = array();
                foreach ($dataSrcs as $key_src => $label_src) {
                    $relId = isset($label_src->id) ? $label_src->id : "";
                    $relLabel = isset($label_src->$strField) ? $label_src->$strField : "";
                    $dataSources[$relId] = $relLabel;
                }
            }
            else {
                $dataSources = isset($coSpeks['dataSource']) ? $coSpeks['dataSource'] : "";
            }

            $fvar .= "$anakan_ky";

            switch ($inputType) {
                case "combo":
                    $reference_label = strtoupper($label);
                    $link_add = base_url() . "statik/Data/add/$referenceClass?main=$className_main&kval=$kval";
                    $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0);
                    $btn_add = isset($coSpeks['add_btn']) ? "<div class='input-group-append'><button type='button' class='btn btn-sm btn-flat btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button></div>" : "<div></div>";
                    $optionals = "<option value=''> Pilih $str_label </option>";
                    foreach ($dataSources as $key_src => $label_src) {
                        $fSelected = $fValue == $key_src ? "selected" : "";
                        $optionals .= "<option class='text-uppercase' value='$key_src' $fSelected>$label_src</option>";
                    }
                    // $eventSession = $this->createSessionData();
                    $varForm = "<div class='input-group input-group-sm'>";
                    $varForm .= "<select kval='$kval' data-style='btn btn-sm btn-primary' data-live-search='true' data-headers='' data-size='10' data-container='body' class='selectpicker form-controlx select2 show-tick' $required_field name='$kolom' $onChange>";
                    $varForm .= $optionals;
                    $varForm .= "</select>";
                    $varForm .= $btn_add;
                    $varForm .= "</div>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "radio":
                    $varForm = "";
                    foreach ($dataSources as $key_src => $label_src) {
                        $varForm .= "<label><input class='flat-redx' type='radio' id='$anakan_ky" . "_" . "$key_src' name='$kolom_nama' value='$key_src'> $label_src</label>";
                    }
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "text":
                    $varForm = "<div class='form-group row'>";
                    $varForm .= "<div class='col-md-5'>";
                    $varForm .= "<input type='text' $required_field id='$anakan_ky' placeholder='$str_label' name='$kolom' class='form-control' value='$fValue'>";
                    $varForm .= "</div>";
                    $varForm .= "</div>";

                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "number":
                    $varForm = "<input type='number' id='$anakan_ky' name='$kolom' class='form-control' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm);
                    break;
                case "hidden":
                    $fValue = $kn;
                    $varForm = "<input type='text' id='$anakan_ky' name='$kolom' value='$fValue'>";
                    $strForm .= $ly->form_group($label, $varForm, 1);
                    break;
            }
        }

        if ($kval != "") {
            $varForm = "<input type='text' id='kval' name='kval' value='$kval'>";
            $strForm .= $ly->form_group("", $varForm, 1);
        }

        $strButton = "";
        $strButton .= "<div class='col-md-12' style='margin-top: 25px;border-top: #f9f7f7 solid 1px;padding-top: 10px;'>";
        $strButton .= "<button type='button' class='btn btn-default text-uppercase pull-left' data-dismiss='modal'>close</button>";
        $strButton .= "<button type='submit' class='btn btn-danger text-uppercase pull-right'>simpan</button>";
        $strButton .= "</div>";

        $link_action = MODUL_PATH . "Data/addProcess/MdlMenuSubStatik/Produk";

        $var = "";
        $var .= "<style type='text/css'>.form-group{ margin-bottom: 2px !important; }</style>";
        $var .= "<div class='row'>";
        $var .= "<div id='loading'></div>";
        $var .= "<form method='post' action='$link_action' target='result' style='margin-top: 0px;'>";
        $var .= $strForm;
        $var .= $strButton;
        $var .= "</form>";
        $var .= "</div>";

        //khusus untuk nembak value jml serial
        $var .= "<script>
                    top.$('#jml_serial').val(1);
                    top.$('select[name=kategori_id]').val(1).change();
                    setTimeout(function(){
                        top.$('.select2').selectpicker({dropdownParent:$('body')}).selectpicker();
                        $('input[type=checkbox].flat-red, input[type=radio].flat-red').iCheck({
                            checkboxClass: 'icheckbox_flat-green',
                            radioClass: 'iradio_flat-green'
                        });
                        $('#loading').hide();
                    }, 250);
                 </script>";
        echo $var;

    }

    public function edit()
    {

        $jsBottom = "";
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "auth/Login");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian

        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        $ctrlName = $segment_4;
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $content = "";

        if (!$this->allowEdit) {
            $p = new Layout(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $content .= "<div class='alert alert-danger'>";
            $content .= "Anda tidak punya wewenang pada halaman ini<br>";
            $content .= "<a href='" . base_url() . "'>Ke depan</a>";
            $content .= "</div>";
            $p->render();
            die();
        }

        /*----------------------------------------------------------------------------------------------------------*/
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(5);

        $tmp = $o->lookupByCondition(array(
            "id" => $selectedID,
        ))->result();

        $f = new MyForm($o, "edit", array(
            "id" => "f1ed_" . $className,
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => MODUL_PATH . get_class($this) . "/editProcess/$ctrlName/" . $selectedID,
            "target" => "result",
            "class" => "form-horizontal",
        ));

        $pf = isset($_GET['pfid']) ? trim($_GET['pfid']) : "";

        $f->openForm(MODUL_PATH . get_class($this) . "/editProcess/$ctrlName/" . $selectedID);
        $f->fillForm($className, $tmp, $pf);
        $f->closeForm();
        $fields = $o->getFields();

        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");

        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]) ? $this->config->item('dataExtRelation')[$className] : array();

        $content .= "<div class='panel panel-danger'>";
        $content .= "<div class='panel-heading'>";
        $content .= "<span class='text-blue no-padding text-uppercase'><span class='fa fa-folder-open'> main editor</span>";
        $content .= "</div>";

        $content .= "<div class='panel-body ini-body'>";
        if ($this->updaterUsingApproval) {
            $content .= "<div class='alert alert-warning-dot text-center'>";
            $content .= "This modification requires approval and this entry will be deactivated until being approved<br>";
            $content .= "</div class='panel-body'>";
        }
        $content .= $f->getContent();


        $content .= "</div>";
        $content .= "</div>";

        if (sizeof($dataRel) > 0) {
            $content .= "<div class='panel panel-info'>";
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

                $mdlLink = MODUL_PATH . get_class($this) . "/view/" . str_replace("Mdl", "", $mdlName) . "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID;
                $content .= "<div class='panel-heading'>";
                $content .= "<span class='text-blue text-uppercase'>";
                $content .= "<a href='$mdlLink'>";
                $content .= "<span class='fa fa-folder-open'></span> " . $mSpec['label'];
                $content .= "</a>";

                if ($allowCreate) {
                    //                    $relPihak = "&cCode=_TR_466&pId=yes";
                    $relPihak = "&pihakId=$selectedID&pId=yes";
                    $addLink = MODUL_PATH . get_class($this) . "/add/" . str_replace("Mdl", "", $mdlName);
                    $addLink .= "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID . $relPihak;

                    $addClick = "
                                BootstrapDialog.show(
                                    {
                                        title:'New " . $mSpec['label'] . "',
                                        message: $('<div id=xxx></div>').load('" . $addLink . "&pfid=xxx'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                    }
                                );";
                    $content .= " <span class='pull-right'>";
                    $content .= "<a class=\" btn btn-default btn-xs\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new " . $mSpec['label'] . "' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></a>";
                    $content .= "</span> ";
                }
                $content .= "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body'>";
                $this->load->model("Mdls/" . $mdlName);

                $o2 = new $mdlName();
                $o2->addFilter($mSpec['targetField'] . "='$selectedID'");
                $tmpo2 = $o2->lookupAll()->result();

                /*---data produk*/
                $konversi_fname = array(
                    "produk_nama" => "nama",
                );
                switch ($mdlName) {
                    case "MdlProdukPerSupplier":
                        // arrPrint($tmpo2);
                        $this->load->model("Mdls/MdlProduk");
                        $pr = new MdlProduk();
                        $prspeks = $pr->callSpecs();

                        $pluss = "";
                        $link_pencarian = MODUL_PATH . "Data/pencarian/Supplier/produk";
                        $content .= "<div>";
                        $content .= "<input type='text' class='form-control' onkeyup=\"$('#pencarian').load('$link_pencarian?mid=$selectedID&key='+encodeURI(this.value));\">";
                        $content .= "</div>";
                        $content .= "<div id='pencarian'></div>";
                        $content .= "<div id='insert'></div>";

                        break;
                }
                // awalnya ada disini.... digeser ke atas 13 april 2023 (relasi produk dengan supplier)
                //                $pluss = "";
                //                $link_pencarian = base_url(). "Data/pencarian/Supplier/produk";
                //                $content .= "<div>";
                //                $content .= "<input type='text' class='form-control' onkeyup=\"$('#pencarian').load('$link_pencarian?mid=$selectedID&key='+encodeURI(this.value));\">";
                //                $content .= "</div>";
                //                $content .= "<div id='pencarian'></div>";
                //                $content .= "<div id='insert'></div>";
                // ----------------------------------------------------------------------------------------------

                $content .= "<table class='table table-condensed'>";
                if (sizeof($tmpo2) > 0) {
                    $content .= "<tr bgcolor='#f0f0f0'>";
                    $content .= "<td>No</td>";
                    foreach ($o2->getListedFields() as $fName => $label) {
                        $content .= "<td>$label</td>";
                    }
                    $content .= "</tr>";
                    $no = 0;
                    foreach ($tmpo2 as $row) {
                        $produk_id = $row->produk_id;
                        $produk_speks = isset($prspeks[$produk_id]) ? $prspeks[$produk_id] : array();
                        $no++;
                        $content .= "<tr>";
                        $content .= "<td>$no</td>";
                        foreach ($o2->getListedFields() as $fName => $label) {
                            $fvalue = isset($row->$fName) ? $row->$fName : "";

                            $content .= "<td title='$fName'>";
                            if (array_key_exists($fName, $relations)) {
                                $fieldLabel = isset($relationPairs[$fName][$fvalue]) ? $relationPairs[$fName][$fvalue] : "unknown rel";
                            }
                            else {
                                $fieldLabel_db_0 = isset($fvalue) ? $fvalue : "noname";
                                $fname_2 = array_key_exists($fName, $konversi_fname) ? $konversi_fname[$fName] : $fName;
                                $fieldLabel_db = strlen($fieldLabel_db_0) < 1 ? (isset($produk_speks->$fname_2) ? $produk_speks->$fname_2 : "") : $fieldLabel_db_0;
                                $fieldLabel = $fieldLabel_db;
                            }
                            $content .= $fieldLabel;
                            $content .= "</td>";
                        }
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
                $content .= "<div class='panel panel-default' style='background:#f0f0f0;resize: vertical;'>";
                // $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";
                $content .= "<div class='panel-heading'>";
                // $content .= "<h5 class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</h5>";
                $content .= "<span class='text-blue text-uppercase no-padding'><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</span>";
                $content .= "</div>";

                $content .= "<div class='panel-body' style='resize: vertical;'>";
                $mSpec['target'];
                $backLink = blobEncode(current_url());
                $iframeLink = base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink";
                //                $content .= "<div id='$selectedID$num' frameborder='0'  style='width:100%;height:350px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:scroll;'>";
                //                $content .= "</div>";
                //                $content .= "<script> $('#$selectedID$num').load('" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink'); </script>";

                $content .= "<iframe id='result2' frameborder='0' width='100%' heights='100%' style='max-height:500px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:hidden;resize: vertical;' src='" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink\' onloadsss='javascript:resizeIframe(this);'>";
                $content .= "</iframe>";
                if (show_debuger() == 1) {
                    $content .= "<a href='javaScript:void(0);' onclick=\"window.open('$iframeLink&dock=1','mywin','width=1000,height=600');\">open New Window</a>";
                }

                $content .= "</div>"; // body
                $content .= "</div>"; // panel
                $content .= "<script>
//                  function resizeIframe(obj) {
//                    obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
//                  }
//                  $('iframe#result2').on('load', function(){
//                    resizeIframe(this)
//                    console.log( $(this).height() );
//                  })

                        function resizeIFrameToFitContent( iFrame ) {
//                            iFrame.width  = iFrame.contentWindow.outerWeight;
                            iFrame.height = iFrame.contentWindow.outerHeight;

//                            $(iFrame).css('min-height', iFrame.height);
                                
//                            console.log('iFrame.id', $(iFrame).attr('id'));
//                            console.log('iFrame.width', iFrame.width);
//                            console.log('iFrame.height', iFrame.height);
                  }

                    $('iframe#result2').on('load', function(){

                        setTimeout(function(){
                            var iFrame = document.getElementById('result2');
                            resizeIFrameToFitContent( iFrame );
                            var iframes = document.querySelectorAll('iframe');
                            for( var i = 0; i < iframes.length; i++) {
                                resizeIFrameToFitContent( iframes[i] );
                            }
                        }
                        , 1000)

                    })
                            


                </script>";
            }
        }

        $arrSpecs = array(
            "mdlName" => "$className",
            "mainLabel" => ucwords($ctrlName),
            "images" => array(),
            "parent_id" => $selectedID,
        );

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
            redirect(base_url() . "auth/Login");
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

        /* -----------------------------------------------------------------------------------------------
         * jml maksimal data yg diperbolehkan, setting ada dimaisng2 model protected $maximumData = ...;
         * model yg tidak ada limit berarti unlimited
         * -----------------------------------------------------------------------------------------------
         */
        $maximumData = $o->getMaximumData();
        $o->setTokoId(my_toko_id());
        $jmlDataNow = $o->lookupJmlActive();
        $sisa_quota_data = $maximumData - $jmlDataNow;
        // cekOrange("$jmlDataNow sisa $sisa_quota_data");

        //arrPrint($tmpContent);

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");
        $f = new MyForm($o, "edit", array(
            "id" => "f1",
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => MODUL_PATH . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target" => "result",
            "class" => "form-horizontal",
        ));
        $f->openForm(MODUL_PATH . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID");

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
        // arrPrint(dataExtRelation);
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

        // $sisa_quota_data =0;
        $viewButton = false;
        switch ($tmp[0]->propose_type) {
            case "add":
            case "edit":

                // $yesAction = "document.getElementById('result').src='" . base_url() . get_class($this) . "/doApproveFrom/$ctrlName/$selectedID/$origID';";
                // $noAction = "document.getElementById('result').src='" . base_url() . get_class($this) . "/doRejectFrom/$ctrlName/$selectedID/$origID';";
                $yesAction = MODUL_PATH . get_class($this) . "/doApproveFrom/$ctrlName/$selectedID/$origID";
                $noAction = MODUL_PATH . get_class($this) . "/doRejectFrom/$ctrlName/$selectedID/$origID";
                $btn_disabled = "";
                if ($origID > 0) {
                    $rejectAlertMsg = "pengajuan data ditolak, dan akan mengembalikan data keversi sebelumnya";
                    $approveAlertMsg = "persetujuan pengajuan perubahan data, akan langsung mengatifkan data";
                    $yesLabel = "terima pengajuan perubahan data baru";
                    $noLabel = "reject/tolak pengajuan";
                }
                else {
                    $rejectAlertMsg = "Data yang diajukan akan dihapus secara permanen";
                    $approveAlertMsg = "Seluruh isi dari konten, akan langsung aktif";
                    $yesLabel = "terima pengajuan data baru";
                    $noLabel = "reject/tolak pengajuan";
                    $btn_disabled = (($maximumData > 0) && ($sisa_quota_data == 0)) ? "disabled" : "";
                }
                $viewButton = (($this->allowEditApproval == true) || ($this->allowCreateApproval == true)) ? true : false;
                break;
            case "delete":
                // $yesAction = "document.getElementById('result').src='" . base_url() . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID';";
                // $noAction = "document.getElementById('result').src='" . base_url() . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID';";
                $yesAction = MODUL_PATH . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID";
                $noAction = MODUL_PATH . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID";
                $rejectAlertMsg = "Data akan kembali aktif";
                $approveAlertMsg = "Data akan dihapus secara permanen";
                $yesLabel = "delete data";
                $noLabel = "pengajuan delete ditolak";
                $viewButton = $this->allowDeleteApproval == true ? true : false;
                break;
        }

        $content .= ("<div class='row'>");
        $content .= ("<div class='col-sm-6'>");
        // $content .= ("<button type='button' class='btn btn-danger btn-block text-uppercase' href='JavaScript:void(0)' onClick =\"if(confirm('$rejectAlertMsg \\nContinue?')==1){$noAction}\">$noLabel</button>");
        $content .= ("<button type='button' class='btn btn-danger btn-block text-uppercase' href='JavaScript:void(0)' onClick =\"btn_alert_result('PERHATIAN!','$rejectAlertMsg','$noAction');\">$noLabel</button>");
        $content .= ("</div class='col-sm-6'>");
        if ($viewButton == true) {

            $content .= ("<div class='col-sm-6'>");
            // $content .= ("<button type='button' class='btn btn-success btn-block text-uppercase' href='JavaScript:void(0)' onClick =\"if(confirm('$approveAlertMsg \\nContinue?')==1){$yesAction}\">$yesLabel</button>");
            $content .= ("<button type='button' $btn_disabled class='btn btn-success btn-block text-uppercase' href='JavaScript:void(0)' onClick =\"btn_alert_result('PERHATIAN!','$approveAlertMsg','$yesAction');\">$yesLabel</button>");
            $content .= ("</div class='col-sm-6'>");
            $content .= ("</div class='row'>");

            if (($maximumData > 0) && ($sisa_quota_data == 0)) {
                $content .= "<div class='alert alert-warning text-uppercase text-center font-size-1-5' style='padding: 5px;margin-top: 10px;'>";
                $content .= "<span class='blink'>info :: </span> quota sudah digunakan seluruhnya $maximumData, <r>penambahan tidak diizinkan !</r>";
                $content .= "</div>";
            }
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
            redirect(base_url() . "auth/Login");
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
            "action" => MODUL_PATH . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target" => "result",
            "class" => "form-horizontal",
        ));
        $f->openForm(MODUL_PATH . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID");

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

        $yesAction = "document.getElementById('result').src='" . MODUL_PATH . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID';";
        $noAction = "document.getElementById('result').src='" . MODUL_PATH . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID';";

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
        $content .= ("<a class='btn btn-danger btn-block' href='JavaScript:void(0)' onClick =\"if(confirm('$rejectAlertMsg \\nContinue?')==1){$noAction}\">tolak penghapusan</a>");
        $content .= ("</div class='col-sm-6'>");

        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-success btn-block' href='JavaScript:void(0)' onClick =\"if(confirm('$approveAlertMsg \\nContinue?')==1){$yesAction}\">setujui penghapusan</a>");
        $content .= ("</div class='col-sm-6'>");
        $content .= ("</div class='row'>");

        $f->closeForm();

        $content .= ($f->getContent());

        echo $content;
        die();

    }

    public function addProcess__()
    {

        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>saving data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );

        echo swalAlert($arrAlert);

        $content = "";
        //==menyimpan inputan data baru ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)

        $segment_5 = $this->uri->segment(5);
        $segment_4 = $this->uri->segment(4);
        $className = "Mdl" . $segment_4;
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $ctrlName = $segment_4;
        $this->load->model("Mdls/" . $className);

        // cekHere("$className");
        $mainObj = $o = new $className;
        $pairValidate = $o->getPairValidate();
        $maximumData = $o->getMaximumData();
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
            //echo json_encode($this->input->post);
            // matiHere(__LINE__);
            $getFields = array();
            foreach ($o->getFields() as $fieldName => $spec) {
                if (isset($spec['arrayVar'])) {
                    foreach ($spec['arrayVar'] as $iif => $sSpec) {
                        $getFields[$iif] = $sSpec;
                    }
                }
                else {
                    $getFields[$fieldName] = $spec;
                }
            }
            // matiHere(__LINE__);
            foreach ($getFields as $fieldName => $spec) {
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
                                }
                                else {
                                    echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }
                            }
                            else {
                                $data[$fName] = "";
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
                                    echo "<script>top.Sweetalert2('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
                                    die();
                                }
                            }
                            else {
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":
                            // cekHere($fName . "||||" .$this->input->post($fName));
                            if (isset($spec['defaultValue'])) {
                                $data[$fName] = $this->input->post($fName);
                            }

                            break;
                        case "hidden_ref":
                            if ($spec['type'] == "mediumblob") {
                                $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            }
                            else {
                                $data[$fName] = $this->input->post($fName);
                            }
                            break;
                        case "textarea":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        default:
                            //                            cekHere(":: $fName");
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
                        $tmpFields = $tmpRe->lookupByID($idnya)->result();
                        $strField = $tmpFields[0]->$spec["strField"];
                        $data[$spec["kolom_nama"]] = $strField;
                    }
                }
            }
            // mati_disini(__LINE__);
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
            // matiHere(__LINE__);

            $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
            $tmpOrig = array();
            if (sizeof($validateDataFields) > 0) {
                $where = array();
                foreach ($validateDataFields as $fieldsValidate) {
                    $where[$fieldsValidate] = $data[$fieldsValidate];
                }
                $tmpOrig = $o->lookupByCondition($where)->result();
                // showLast_query("lime");
                // arrPrint($tmpOrig);
                $bNama = $tmpOrig[0]->biaya_nama;
                $bProduk = $tmpOrig[0]->produk_nama;
                $bProdukId = $tmpOrig[0]->produk_id;
            }
            if (sizeof($tmpOrig) > 0) {
                // cekHere(":: HAHAHA ");
                if ($bProdukId > 0) {
                    $where2 = array("produk_id" => $bProdukId);
                }
                else {
                    $where2 = array();
                }
                $tmpOrig2 = $o->lookupByCondition($where2)->result();
                // showLast_query("biru");
                // arrPrint($tmpOrig2);

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
            if (sizeof($pairValidate) > 0) {
                $valPair = array();
                foreach ($pairValidate as $k) {
                    // cekHitam($k);
                    // $valPair[$k] = $this->input->post($k);
                    $o->addFilter("$k='" . $this->input->post($k) . "'");
                }
                if (sizeof($valPair) > 0) {

                    foreach ($valPair as $k => $v) {

                    }
                }
                $pairValidateData = $o->lookUpAll()->result();
                if (sizeof($pairValidateData) > 0) {
                    $alerts = array(
                        "type" => "warning",
                        "title" => "Duplikasi data",
                        "html" => $pairValidateData['0']->nama . " sudah ada disistem",
                    );
                    echo swalAlert($alerts);
                    die();
                }
            }
            if ($this->creatorUsingApproval) {
                // cekHere("approval");
                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
                // showLast_query("kuning");
                $this->session->errMsg = "Data proposal has been saved and pending approval";
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id" => 0,
                    "mdl_name" => $className,
                    "mdl_label" => get_class($this),
                    "old_content" => "",
                    "new_content" => base64_encode(serialize($data)),
                    // "new_content_intext" => print_r($data, true),
                    "label" => "proposed",
                    "oleh_id" => $this->session->login['id'],
                    "oleh_name" => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                // cekHitam($this->db->last_query());
            }
            else {

                $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
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
                //                mati_disini(__LINE__);
                $this->session->errMsg = "Data contents have been saved";
                $inserted["id"] = $insertID;
                $updateLink = MODUL_PATH . get_class($this) . "/edit/$ctrlName/" . $insertID . "";

                matiHere($updateLink);
                $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $ctrlName ',
                                            size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

                $this->session->errMsg .= "<br><a href='JavaScript:void(0)' onclick=\"$editClick\">view entry</a>";

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
                    $d->pair($inParam) or die("Tidak berhasil memasang  values pada dcom-processor: $className/" . __FUNCTION__ . "/" . __LINE__);
                    $gotParams = $d->exec();
                }
                //endregion


                if (method_exists($o, "paramSyncNamaNama")) {
                    $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                    foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                        $id_ygdisync = isset($data[$syncNamaNamaParams['id']]) ? $data[$syncNamaNamaParams['id']] : "";
                        $o->setTokoId(my_toko_id());
                        if ($id_ygdisync > 0) {
                            $o->syncNamaNama($id_ygdisync);
                        }

                    }
                }


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
                    "toko_id" => $this->session->login['toko_id'],
                    "oleh_id" => $this->session->login['id'],
                    "oleh_name" => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));


                /* -------------------------------------
                 * tested pada auto COA yg pakai aaproval masuk di do
                 * -------------------------------------*/
                if (method_exists($mainObj, "getConnectingData")) {
                    $nama = ucwords($data['nama']);
                    $negara = isset($data['country']) ? $data['country'] : "";
                    $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                    $my_name = my_name();
                    $connectings = $mainObj->getConnectingData();
                    foreach ($connectings as $model => $param_connecting) {
                        foreach ($param_connecting as $paramConnecting) {
                            $fields = isset($paramConnecting['fields']) ? $paramConnecting['fields'] : $paramConnecting;
                            $this->load->model($paramConnecting['path'] . "/$model");
                            $connObj = new $model();
                            if (isset($paramConnecting['staticOptions'])) {
                                $strHead_code = is_array($paramConnecting['staticOptions']) ? $paramConnecting['staticOptions'][$extern_tipe] : $paramConnecting['staticOptions'];
                            }
                            else {
                                matiHere("static optionnya tolong dikasih");
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
                            $lastInset_code = $connObj->$paramConnecting['fungsi']($strHead_code, my_toko_id(), $datas);
                            showLast_query("merah");
                            /* -------------------------------------------------
                             * ngupdate ke data utama
                             * -------------------------------------------------*/
                            if (isset($paramConnecting['updateMain'])) {
                                foreach ($paramConnecting['updateMain']['condites'] as $key => $condite) {
                                    $mainCondites[$key] = $$condite;
                                }
                                foreach ($paramConnecting['updateMain']['datas'] as $key => $val) {
                                    $mainUpdate[$key] = $$val;
                                }
                                $mainObj->updateData($mainCondites, $mainUpdate);
                                showLast_query("orange");
                            }
                        }
                    }
                }
            }

            matiHere(__LINE__);
            /* ------------------------------------------------
             * menandai todolist wizart yg sudah dikerjakan
             * ------------------------------------------------*/
            if (isset($segment_5)) {
                if (method_exists($o, "addDefaultData")) {
                    cekBiru(my_toko_id());
                    $o->setTokoId(my_toko_id());
                    $o->addDefaultData();
                }
                $this->load->model("Mdls/MdlCompany");
                $cp = new MdlCompany();
                $cp->setTokoId(my_toko_id());
                $cp->updateDataPreparation($segment_5);
            }

            /* --------------------------------------------------------------------------------
             * api ke aplikasi POS untuk menambahkan data employee
             * --------------------------------------------------------------------------------*/
            //            if ($className == "MdlEmployeeCabang") {
            //                $this->load->config("heApi");
            //                $apiWebs = $this->config->item("heApi");
            //                $urlApi = $apiWebs["webs"]["add_employee"];
            //                //cekHere("$urlApi");
            //                // if(ipadd() == "202.65.117.72"){
            //                // }
            //                // $this->load->
            //                $this->load->library('curl');
            //                //arrPrintPink($data);
            //                $data["id"] = $mainInsertId;
            //////                matiHere(json_encode($data));
            //                $preOrderRequest = $this->curl->simple_post($urlApi, $data);
            ////                matiHere( __LINE__ );
            ////                matiHere( json_encode($preOrderRequest) );
            //                $apirespons = json_decode($preOrderRequest);
            //                //arrPrintHijau($apirespons);
            //                $apistatus = $apirespons->status;
            //            }
            //            else {
            //                $apistatus = "success";
            //            }
            //$apistatus = "success";
            //             matiHere("under maintenance ----DONE---- belom commit :: ". __METHOD__ . "|" . ipadd() );
            //             matiHere("under maintenance ----DONE---- belom commit :: ". __METHOD__ . "|" . ipadd() );
            //            if ($apistatus == "success") {

            //cekMerah(__DIR__ . '/eusvc/sync/' . $className."_".$this->session->login['toko_id'].".txt");
            /* --------------------------------------------------------------------------------------------------------
             * update ke file untuk sync ke APK
             * -------------------------------------------------------------------------------------------------------*/
            //dimatiin KJA belum ada APK
            // $file = fopen(__DIR__ . '/eusvc/sync/' . $className . "_" . my_toko_id() . ".txt", "w");
            // echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
            // fclose($file);
            // --------------------------------------------------------------------------------------------------------
            matiHere("sudah comit");

            $this->db->trans_complete();
            echo "<script>top.location.reload();</script>";
            //            }
            //            else {
            //                echo lgShowWarning("Opss...", "gagal menyimpan data, cobalah beberapa saat lagi, atau hubungi webadmin");
            //            }
        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "<div class='text-left'><r>**</r><strong>$err[fieldLabel]</strong>:  $err[errMsg]</div>";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function addProcess()
    {

        //        arrPrint($_POST);
        //        matiHere();
        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>saving data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );

        // echo swalAlert($arrAlert);

        $content = "";
        //==menyimpan inputan data baru ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)

        $className = "Mdl" . $this->uri->segment(4);
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $ctrlName = $this->uri->segment(4);
        $this->load->model("Mdls/" . $className);
        $cabang_id = my_cabang_id();
        switch ($className) {
            case "MdlProduk":
                arrPrintKuning($_POST);
                if (!isset($_POST["jml_serial"])) {
                    matiDisini("serial tidak terdeteksi");
                }
                break;
        }

        $kval_ = isset($_POST["kval"]) ? $_POST["kval"] : "";
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
                // echo __LINE__;
                // arrPrintHijau($data);
                /* --------------------------------------------------------
                 * langsung ngisi kolom di main table dari kolom erference (pendukung)
                 * --------------------------------------------------------*/
                if (isset($spec['strField']) || isset($spec["referenceDatas"])) {
                    if (isset($spec["reference"])) {
                        $this->load->model("Mdls/" . $spec["reference"]);
                        $idnya = $this->input->post($spec["kolom"]);
                        if ($idnya > 0) {
                            $tmpRe = new $spec["reference"]();
                            $tmpFields = $tmpRe->lookupByID($idnya)->result();
                            // showLast_query("biru");
                            // cekHere(count($tmpFields));
                            if (isset($spec['strField'])) {
                                $strField = isset($tmpFields[0]->$spec["strField"]) ? $tmpFields[0]->$spec["strField"] : "";
                                $data[$spec["kolom_nama"]] = $strField;
                            }

                            if (isset($spec["referenceDatas"])) {
                                foreach ($spec["referenceDatas"] as $kolomSrc => $kolomTarget) {
                                    $data[$kolomTarget] = $tmpFields[0]->$kolomSrc;
                                }
                            }
                        }
                    }
                }
            }
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

            $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
            $tmpOrig = array();
            if (sizeof($validateDataFields) > 0) {
                $where = array();
                foreach ($validateDataFields as $fieldsValidate) {
                    $where[$fieldsValidate] = $data[$fieldsValidate];
                }
                $tmpOrig = $o->lookupByCondition($where)->result();
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

                $mainDatas = $data;
                $mainInsertId = $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError(__LINE__ . " Gagal menulis data", __FILE__));
                $supplier_id = isset($data['supplier_id']) ? $data['supplier_id'] : null;
                //                showLast_query("orange");
                //                arrPrintPink($mainDatas);

                //region takbahan Dcom
                if (sizeof($dcomConf) > 0) {
                    $inParam = array_merge($inserted, $data);
                    $className = "DCom" . $dcomConf;
                    $this->load->Model("DComs/" . $className);
                    $d = new $className();
                    $d->setWriteMode("insert");
                    $d->pair($inParam) or die("Tidak berhasil memasang  values pada dcom-processor: $className/" . __FUNCTION__ . "/" . __LINE__);
                    $gotParams = $d->exec();
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
                    $merek_id = isset($mainDatas['merek_id']) ? $mainDatas['merek_id'] : null;
                    $merek_nama = isset($mainDatas['merek_nama']) ? $mainDatas['merek_nama'] : null;
                    $supplier_nama = isset($mainDatas['supplier_nama']) ? $mainDatas['supplier_nama'] : null;
                    $supplier_id = isset($mainDatas['supplier_id']) ? $mainDatas['supplier_id'] : null;
                    $nama = ucwords($mainDatas['nama']);
                    $negara = isset($data['country']) ? $data['country'] : "";
                    $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                    $my_name = my_name();
                    $connectings = $mainObj->getConnectingData();
                    foreach ($connectings as $model => $param_connecting) {
                        $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                        $this->load->model($param_connecting['path'] . "/$model");
                        $connObj = new $model();
                        if (isset($param_connecting['staticOptions'])) {
                            $strHead_code = "";
                            if (is_array($param_connecting['staticOptions'])) {
                                $strHead_code_array = $param_connecting['staticOptions'];
                                foreach ($strHead_code_array as $field => $cfParams) {
                                    if (isset($cfParams['var_main'])) {
                                        $cNilai = $$cfParams['var_main'];
                                    }
                                    else {
                                        $cNilai = $cfParams['str'];
                                    }
                                    $strHead_code[$field] = $cNilai;
                                }
                            }
                            else {
                                $strHead_code = $param_connecting['staticOptions'];
                            }
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
                        //                        showLast_query("merah");

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
                            //                            showLast_query("orange");
                        }
                        //                        cekHitam($lastInset_code);
                    }
                }
                //    ---------------memasukan pendukung sebagau produk----------------------------------------------
                if ($kval_ != "") {
                    $masterSubs = $mainObj->getMasterSubs();
                    $subFields = array();
                    $getMasterSubs = isset($getMasterSubs) ? $getMasterSubs : array();
                    foreach ($getMasterSubs as $ky => $subRow) {
                        $subFields[$subRow['sub_kategori_id']] = $subRow;
                    }
                    cekBiru($subFields);
                    $fields_ = $mainObj->getFields();
                    if (isset($masterSubs[$kval_]['add_produk'])) {
                        $add_produk = $masterSubs[$kval_]['add_produk'];
                        foreach ($add_produk as $row) {
                            $ssc = explode("_", $row);
                            $sub_cat_id = 4;
                            $sub_cat_nama = isset($subFields[4]) ? $subFields[4]['sub_kategori_nama'] : "";

                            $ref = $fields_[$row]['reference'];
                            $kol = $fields_[$row]['kolom'];
                            $strF = isset($fields_[$row]['strField']) ? $fields_[$row]['strField'] : null;
                            $selectedID = $_POST[$kol];

                            if ($selectedID * 1 > 0) {
                                $this->load->model("Mdls/" . "$ref");
                                $hAdd = new $ref;
                                $hAdd->addFilter("id='" . $selectedID . "'");
                                $dataSrcs = $hAdd->lookupAll()->result();

                                $newData = array(
                                    'kategori_id' => 3,
                                    'kategori_nama' => "non unit",
                                    'sub_kategori_id' => $sub_cat_id,
                                    'sub_kategori_nama' => $sub_cat_nama,
                                    'kode' => $dataSrcs[0]->sku,
                                    'barcode' => $dataSrcs[0]->barcode,
                                    'nama' => $dataSrcs[0]->nama,
                                    'supplier_id' => $data['supplier_id'],
                                    'kapasitas_id' => $data['kapasitas_id'],
                                    'folders' => $data['folders'],
                                    'size_id' => $data['size_id'],
                                    'size_nama' => $data['size_nama'],
                                    'tipe_id' => $data['tipe_id'],
                                    'tipe_nama' => isset($data['tipe_nama']) ? $data['tipe_nama'] : null,
                                    'merek_id' => $data['merek_id'],
                                    'merek_nama' => isset($data['merek_nama']) ? $data['merek_nama'] : null,
                                    'phase_id' => $data['phase_id'],
                                    'phase_nama' => isset($data['phase_nama']) ? $data['phase_nama'] : null,
                                    'series_id' => isset($data['series_id']) ? $data['series_id'] : null,
                                    'series_nama' => isset($data['series_nama']) ? $data['series_nama'] : null,
                                    'jml_serial' => 1,
                                    'status' => 1,
                                    'trash' => 0,
                                    'jenis' => "item",
                                );

                                $o->setFilters(array());
                                $tmpD = $o->lookupByCondition(array('kode' => $dataSrcs[0]->sku, 'status' => 1))->result();

                                if (!$tmpD) {
                                    $mainInsertId = $insertID = $o->addData($newData, $o->getTableName()) or die(lgShowError(__LINE__ . " Gagal menulis data", __FILE__));
                                    //                                    /* ---------------------------------------------------------------
                                    //                                     * tested pada auto COA yg pakai aaproval masuk di doApproveFrom
                                    //                                     * --------------------------------------------------------------*/
                                    if (method_exists($mainObj, "getConnectingData")) {
                                        $merek_id = isset($mainDatas['merek_id']) ? $mainDatas['merek_id'] : null;
                                        $merek_nama = isset($mainDatas['merek_nama']) ? $mainDatas['merek_nama'] : null;
                                        $supplier_nama = isset($mainDatas['supplier_nama']) ? $mainDatas['supplier_nama'] : null;
                                        $supplier_id = isset($mainDatas['supplier_id']) ? $mainDatas['supplier_id'] : null;
                                        $nama = ucwords($mainDatas['nama']);
                                        $negara = isset($data['country']) ? $data['country'] : "";
                                        $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                                        $my_name = my_name();

                                        $connectings = $mainObj->getConnectingData();
                                        foreach ($connectings as $model => $param_connecting) {
                                            $fields = isset($param_connecting['fields']) ? $param_connecting['fields'] : $param_connecting;
                                            $this->load->model($param_connecting['path'] . "/$model");
                                            $connObj = new $model();
                                            if (isset($param_connecting['staticOptions'])) {
                                                $strHead_code = "";
                                                if (is_array($param_connecting['staticOptions'])) {
                                                    $strHead_code_array = $param_connecting['staticOptions'];
                                                    foreach ($strHead_code_array as $field => $cfParams) {
                                                        if (isset($cfParams['var_main'])) {
                                                            $cNilai = $$cfParams['var_main'];
                                                        }
                                                        else {
                                                            $cNilai = $cfParams['str'];
                                                        }
                                                        $strHead_code[$field] = $cNilai;
                                                    }
                                                }
                                                else {
                                                    $strHead_code = $param_connecting['staticOptions'];
                                                }
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
                                            //                                            cekHitam($lastInset_code);
                                        }
                                    }
                                    //                                    //    -------------------------------------------------------------
                                }
                            }

                            //                            if (method_exists($o, "paramSyncNamaNama")) {
                            //                                $syncNamaNamaMdls = method_exists($o, "paramSyncNamaNama") ? $o->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                            //                                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                            //                                    $id_ygdisync = isset($data[$syncNamaNamaParams['id']]) ? $data[$syncNamaNamaParams['id']] : "";
                            //                                    $o->setTokoId(my_toko_id());
                            //                                    if ($id_ygdisync > 0) {
                            //                                        $o->syncNamaNama($id_ygdisync);
                            //                                    }
                            //                                }
                            //                            }

                        }
                    }
                }
            }

//            matiHere("data berhasil disimpan. under maintenance @" . __LINE__);
//            $this->db->trans_complete();
            $this->db->trans_complete() or mati_disini("Data baru gagal disimpan. Silahkan segera hubungi admin.");
            if (isset($_GET['main'])) {
                /*-------kembali ke main dialog------*/
                // cekHere(__LINE__);
                $reference = substr($_GET['main'], 3);
                if ($reference != "Produk") {
                    $link_add = base_url() . "statik/Data/add/$reference";
                }
                else {
                    $kval = $_GET['kval'];
                    $link_add = base_url() . "statik/Data/addProduk/$reference?kval=$kval&pfid=l2";
                }
                //untuk auto pilih sat add
                $id_target = "none";
                switch ($className) {
                    case "MdlTipe":
                        $id_target = "tipe_id";
                        break;
                    case "MdlModelOutdoor":
                        $id_target = "outdoor_id";
                        break;
                    case "MdlModelIndoor_1":
                        $id_target = "indoor_id_1";
                        break;
                    case "MdlProdukPart_1":
                        $id_target = "produk_part_id_1";
                        break;
                    case "MdlProdukPart_2":
                        $id_target = "produk_part_id_2";
                        break;
                    default:

                        break;
                }

                if ($id_target == "none") {
                    $actionTarget = "
                        top.BootstrapDialog.closeAll();
                        top.BootstrapDialog.show({
                            title:'id_target: $id_target ($className) New $reference',
                            message: " . 'top.$' . "('<div id=l2></div>').load(\"$link_add\"),
                            size:top.BootstrapDialog.SIZE_WIDE,
                            draggable:true,
                            animate:false,
                            closable:true,
                            type:top.BootstrapDialog.TYPE_SUCCESS,
                        });";

                    echo "<script>$actionTarget</script>";
                    die();
                }
                else {
                    $this->load->model("Mdls/" . $className);
                    $d = new $className;
                    $label = $d->getFields();
                    $tmpD = $d->lookupAll()->result();
                    $arrOption = "";
                    $arrOption .= "<option value=''>Pilih " . $label[$id_target]['label'] . "</option>";
                    if (!empty($tmpD)) {
                        foreach ($tmpD as $row) {
                            $id = $row->id;
                            $nama = $row->nama;
                            $selected = $mainInsertId == $id ? "selected" : "";
                            $arrOption .= "<option $selected value='$id'>$nama</option>";
                        }
                    }
                    echo "
                        <script>
                            top.$('select[name=$id_target]').html(\"$arrOption\");
                            top.$('select[name=$id_target]').selectpicker('refresh');
                            var close = top.$('button.close').length;
                            top.$(top.$('button.close')[close-1]).trigger('click')
                        </script>
                    ";
                }
            }
            else {
                echo "<script>top.location.reload();</script>";
            }
        }
        else {
            cekMerah("tidak valid @" . __LINE__);
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
        // arrPrint($_POST);
        $content = "";
        //==menyimpan inputan perubahan data ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = "Mdl" . $this->uri->segment(4);
        $ctrlName = $this->uri->segment(4);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $pairValidate = $o->getPairValidate();
        $this->db->trans_start();

        $postProcs = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className] : array();
        $indexFieldName = "id";
        $f = new MyForm($o, "editProcess");
        //        matiHere('$f->isInputValid(): ' . $f->isInputValid());
        //         arrPrint($this->input->post());
        //         arrPrint($pairValidate);
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
                        case "address":
                            if (isset($spec['arrayVar'])) {
                                foreach ($spec['arrayVar'] as $fieldName_ => $spec_) {
                                    $fName_ = isset($spec_['kolom']) ? $spec_['kolom'] : $fieldName_;
                                    $data[$fName_] = heTrimAvoidedChars($this->input->post($fName_));
                                }
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
                                //                                cekHEre("$fName no image");
                                $data[$fName] = $this->input->post("tmp_" . $fName) != "" ? $this->input->post("tmp_" . $fName) : "";
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
                            //                            cekHere("meng NULL kan -> $val");
                            $data[$val] = NULL;
                        }
                    }
                }
            }

            //            arrprint($data);
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
                        $o->setTokoId(my_toko_id());
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
                        //                        cekmerah("post-proc name: $pp / $comName");
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

            if (sizeof($pairValidate) > 0) {
                // arrPrint($pairValidate);
                $valPair = array();
                $o->setFilters(array());
                foreach ($pairValidate as $k) {
                    cekHitam($k);
                    // $valPair[$k] = $this->input->post($k);
                    $o->addFilter("$k='" . $this->input->post($k) . "'");
                    $o->addFilter("status='.1'");
                }
                $pairValidateData = $o->lookUpAll()->result();
                cekLime($this->db->last_query());
                if (sizeof($pairValidateData) > 1) {
                    $alerts = array(
                        "type" => "warning",
                        "title" => "Duplikasi data***",
                        "html" => "<b class='text-uppercase'>" . $pairValidateData['0']->nama . "</b> sudah ada disistem. silahkan pilih nama lain",
                    );

                    echo swalAlert($alerts);
                    die();
                }

            }

            // if(($ipadd = ipadd()) == "202.65.117.72"){
            // }

            /* --------------------------------------------------------------------------------
             * api ke aplikasi POS untuk merubah data employee
             * --------------------------------------------------------------------------------*/
            //            $apistatus = "success";
            //            if ($className == "MdlEmployeeCabang") {
            //                $em_id = $data["id"];
            //                $this->load->config("heApi");
            //                $apiWebs = $this->config->item("heApi");
            //                $urlApi = $apiWebs["webs"]["edit_employee"] . "/$em_id";
            ////                cekHere("$urlApi");
            //
            //                $this->load->library('curl');
            //                $curl = New Curl();
            //
            ////                arrPrintPink($data);
            //                // $data["id"] = $mainInsertId;
            //
            ////                arrPrintKuning($data);
            //                // $data
            ////                $preOrderRequest = $curl->sendPost($urlApi, $data);
            //                $preOrderRequest = $this->curl->simple_post($urlApi, $data);
            ////                matiHere( json_encode($preOrderRequest) );
            ////                arrPrintHijau($preOrderRequest);
            //                $apirespons = json_decode($preOrderRequest);
            //                // arrPrintHijau($apirespons);
            //                $apistatus = $apirespons->status;
            //                // matiHere("belum complittt $ipadd $className $apistatus @" . __LINE__ );
            //            }

            // matiHere("mode monitoring belum complittt $className @" . __LINE__);
            // if ($apistatus == "success") {
            // matiHere("sudah complittt $className @" . __LINE__);
            $this->db->trans_complete();

            // matiHere("sudah complittt $className @" . __LINE__);

            $file = fopen(__DIR__ . '/eusvc/sync/' . $className . "_" . $this->session->login['toko_id'] . ".txt", "w");
            echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
            fclose($file);

            //
            //            if ($className == "MdlEmployeeCabang") {
            //                $this->load->library("Curl");
            //                $curl = New Curl();
            //                $url = ADM_DOMAIN . "eusvc/Entries/syncEmployeeToApk";
            //                $toko_id = $this->session->login['toko_id'];
            //                $this->load->model("Mdls/MdlEmployeeCabang");
            //                $pr = new MdlEmployeeCabang();
            //                $pr->addFilter("toko_id=$toko_id");
            //                $prdTmp = $pr->lookupAll()->result();
            //                $arrEmployee = array();
            //                foreach ($prdTmp as $k => $dEmployee) {
            //                    $arrEmployee[$dEmployee->id] = $dEmployee;
            //                }
            //                $writer = $curl->syncEmployeeToApk($url, $arrEmployee);
            //            }

            echo "<script>top.location.reload();</script>";
            //            }
            //            else {
            //                echo lgShowWarning("Opss..." . __LINE__ , "gagal menyimpan data, cobalah beberapa saat lagi, atau hubungi webadmin");
            //            }
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

    public function updateProcess()
    {
        arrPrintHijau(url_segment());
        $id = url_segment(4);
        $className = "Mdl" . url_segment(3);
        $kolom = $_GET['k'];
        $nilai = $_GET['n'];
        arrPrintHijau($_GET);
        $this->db->trans_begin();

        $this->load->model("Mdls/" . $className);
        $mainObj = $o = new $className();

        $o->setTokoId(my_toko_id());
        $condites = array(
            "id" => $id
        );
        $newdatas = array(
            $kolom => $nilai
        );
        $o->updateData($condites, $newdatas);
        showLast_query("pink");

        // matiDisini("belum commit");
        $this->db->trans_complete();
        $link_setting = base_url() . "setting/Cabang/ViewSetting";
        echo "<script>
            $('#show_anakan_1').load('$link_setting')
        </script>";
    }

    public function delete()
    {
        $content = "";
        //==menghapus (aslinya mendisable) data sesuai datamodel dan id-nya yang bersesuaian
        $ctrlName = $this->segment_4;
        $className = "Mdl" . $ctrlName;

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
        $selectedID = $this->uri->segment(5);
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
        redirect(MODUL_PATH . get_class($this) . "/viewdt/$ctrlName/?k=$key");
    }

    public function status()
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
        $statusDb = $this->uri->segment(5);
        $statusNew = $statusDb == 1 ? 0 : 1;
        $where = array(
            "id" => $selectedID
        );

        $o->setFilters(array());
        $o->addFilter("toko_id=" . $this->session->login['toko_id']);
        $oldDataTmp = $o->lookupByID($selectedID)->result();

        // showLast_query("lime");
        // arrPrintPink($oldDataTmp);

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
            "propose_type" => "status",
            "content" => base64_encode(serialize((array)$oldDataTmp[0])),
        );
        // arrPrintPink($tmpData);
        // mati_disini(__LINE__);
        if ($this->deleterUsingApproval) {
            // $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
            showLast_query("biru");
            $this->session->errMsg = "Permintaan perubahan status sudah disimpan";

            $tmpOrig = $o->lookupByCondition(
                array("id" => $selectedID))->result();
            $o->setFilters(array());
            $o->updateData($where, array("status" => $statusNew), $o->getTableName());
            //            showLast_query("kuning");
            $tmpNew = (array)$tmpOrig;
            $tmpNew["status"] = $statusNew;
            // $tmpNew["trash"] = 1;

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
                "label" => "status_proposed",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            showLast_query("hijau");
            //</editor-fold>

        }
        else {
            $tmpOrig = $o->lookupByCondition(array("id" => $selectedID))->result();

            //<editor-fold desc="really hapus">
            $o->lookupByCondition(array("id" => $selectedID));
            $data['status'] = "$statusNew";
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
                "label" => "status",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            showLast_query("hijau");
            //</editor-fold>
        }

        // matiHere("complittt $className");
        $this->db->trans_complete();

        echo lgShowSuccess("Berhasil", "untuk membarui tampilan Anda bisa melakukan refresh browser");


        $key = isset($_GET['k']) ? $_GET['k'] : "";
        redirecResult(base_url() . get_class($this) . "/viewdt/$ctrlName/?k=$key");
    }

    public function index()
    {

        $content = "";
        //==aksi default, yaitu dibawa ke mode "view"
        //==sebelumnya dicek dulu, user buka halaman pakai slash atau enggak

        // $splitStr = explode("/", __FILE__);
        // if (get_class($this) . ".php" != $splitStr[sizeof($splitStr) - 1]) {
        //     redirect(base_url() . get_class($this) . "/view");
        // } else {
        //     die("DiRECT access to this file is N.O.T. allowed!");
        // }

        $data = array(
            "title" => $this->segment_4,
            'mode' => $mode_switch,
            'segment_3' => $this->segment_4,
            'mdl' => $this->className,
            // --
            "error" => $this->session->errMsg,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress" => $arrItemTmp,
            // ---
            "strDataHistTitle" => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "linkStr" => "",
            "add_link" => $strAddLink,
            "edit_link" => $strEditLink,
            "bulk_edit_link" => $strBulkEditLink,
            "folder_link" => isset($folder_link) ? $folder_link : "",
            "filterId" => $folderId,
            "filterId_str" => $folderId > 0 ? "?fid=$folderId" : "",
            // -----
            "arrayRecapLabels" => $arrayRecapLabel,
            "arrayRecap" => $arrayRecap,
            "maximumData" => $maximumData,
            "jmlDataNow" => $jmlDataNow,

        );
        // arrPrint($data);
        $this->load->view("data", $data);
    }

    public function view()
    {
        //        arrPrint($this->uri->segment_array());
        $content = "";
        if (!isset($this->session->login['id'])) {
            gotoLogin();//remember last login
        }
        $ctrlName = $this->uri->segment(4);
        $className = "Mdl" . $ctrlName;

        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='$className'");
        $tmpTmp = $tData->lookupAll()->result();
        // cekHitam($this->db->last_query());
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

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
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

        if (isset($_GET['trashed']) && $_GET['trashed'] > 0) {
            $objState = $_GET['trashed'];
            if ($objState == "1") {
                $title = "Deleted " . $title;
                $objStateStatus = "0";
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
                break;
            case "1":
                $alternateLink = "<a href='" . base_url() . get_class($this) . "/view/$ctrlName'><span class='glyphicon glyphicon-ok-sign'></span> view active $realObjName</a>";
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
            $subtitle = "List of $title";
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
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='JavaScript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }

            }

        }

        $addLink = base_url() . get_class($this) . "/add99/$ctrlName";
        $addManyLink = base_url() . get_class($this) . "/addMany/$ctrlName";

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $addLink .= "?reqField=" . $_GET['reqField'] . "&reqVal=" . $_GET['reqVal'];
        }


        $params = array();
        $limit_per_page = 10;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $o->lookupDataCount($key);
        showLast_query("kuning");

        if ($total_records > 0) {
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }

            $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
            showLast_query("merah");
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

        cekHere(__LINE__);
        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination

        $dataRow = array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        $defaultKey = $key != "" ? $key : "cari " . strtolower($title);
        $content .= ($t->addSpanRow(array(
            "<div class='input-group'>" . "<input type=text placeholder='$defaultKey' class='form-control text-center' onkeyup=\"if(detectEnter()==1){location.href='" . base_url() . get_class($this) . "/view/$ctrlName/?k='+this.value}\">" . "<span class='input-group-addon'>" . "<i class='glyphicon glyphicon-search'></i></span>" . "</div class='input-group'>",
        )));

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
                if ($this->allowEdit && $objState != "1") {
                    $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $rowSpec->id . "";
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify " . addslashes($title) . "',
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
                        $addNumber = $colCounter == 0 ? "<a href='JavaScript:void(0)' onclick =\"$historyClick\"><span class='badge' style='background:#c0c0c0;color:#656564;'>$rowCounter</span></a>" : "";
                        $tmpItem['action'] .= "<a class='btn btn-default ' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                    }
                    $colCounter++;
                }
                if ($this->allowDelete && $objState != "1") {
                    $tmpItem['action'] .= "<button class='btn btn-danger hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"if(confirm('Remove entry?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                }
                if ($this->allowViewHistory) {
                    $tmpItem['action'] .= "<a class='btn btn-default' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span></a>";
                }

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

                    // $tmpItem['option'] = "<a href='JavaScript:void(0)' onclick=\"$optClick\">" . "<span class='glyphicon glyphicon-option-vertical'></span>" . "</a>";
                }
                $arrayItem[] = $tmpItem;
            }
        }

        if ($this->allowCreate) {
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
            $strAddLink .= "<button href='JavaScript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";
            $strAddLink .= in_array("addMany", $limitedEditor) ? "" : "<button href='JavaScript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";
            $strAddLink .= "</div class='btn-group'>";

        }
        else {
            $strAddLink = "";
        }
        if ($this->allowEdit) {
            $strEditLink = in_array("addMany", $limitedEditor) ? "" : "<button href='JavaScript:void(0)' class=\" btn btn-default\" onClick=\"location.href='" . base_url() . get_class($this) . "/editMany/$ctrlName/" . $this->uri->segment(4) . "'\" data-toggle='tooltip' data-placement='top' title='Modify all $title in this page' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-pencil'></button>";
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
            cekOrange($className);
        }


        $arrayHeader = $this->listedFields;
        if (sizeof($dataExtRel) > 0) {
            $arrayHeader["images"] = "images";
        }
        $arrayHeader["action"] = "action";
        if (sizeof($dataRel) > 0) {
            $arrayHeader["option"] = "<span class='glyphicon glyphicon-th-list'></span>";
        }


        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        $tmpH = $h->lookupRecentHistories()->result();

        $arrayRecap = array();
        if (sizeof($tmpH) > 0) {
            $tmpO = new $className();
            foreach ($tmpH as $row) {
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
            }

        }
        else {
            $folders = array();
        }

        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "title" => $realObjName . $titleSuffix,
            //            "subTitle" => "Registered $realObjName" . $titleSuffix,
            "subTitle" => $subtitle,
            "strActiveDataTitle" => "<span class='glyphicon glyphicon-th-list'></span> List of $title" . $titleSuffix,
            "linkStr" => isset($params['links']) ? $params['links'] : "",
            "arrayHistoryLabels" => $arrayHeader,
            "arrayHistory" => $arrayItem,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress" => $arrItemTmp,
            //            "entities" => $entities,
            "strDataHistTitle" => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "arrayRecapLabels" => $arrayRecapLabel,
            "arrayRecap" => $arrayRecap,
            "strEditLink" => $strEditLink,
            "strAddLink" => $strAddLink,
            "alternateLink" => $alternateLink,
            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "?trashed=$objState",
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

    public function viewHistories()
    {
        $content = "";
        $className = "Mdl" . $this->segment_4;
        $ctrlName = $this->segment_4;
        $selectedID = $this->segment_5;
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
        // cekBiru($this->db->last_query());
        if (sizeof($tmpH) > 0) {
            // arrPrint($tmpH);
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

            $content .= ("<thead class='text-uppercase'>");
            $content .= ("<tr bgcolor='#dedede'>");
            $content .= ("<td>no</td>");
            $content .= ("<td>date</td>");
            foreach ($listedFields as $fName => $label) {
                $content .= ("<td>");
                $content .= ($label);
                $content .= ("</td>");
            }
            $content .= ("<td>person</td>");
            $content .= ("</tr>");
            $content .= ("</thead>");
            // arrPrintKuning($listedFields);
            $no = 0;
            foreach ($tmpH as $row) {
                $no++;
                //                arrPrint($row);
                $oldContents_0 = blobDecode($row->old_content);
                $newContents_0 = blobDecode($row->new_content);
                //                arrPrintWebs($newContents);
                $oldContents = (array)$oldContents_0[0];
                $newContents = (array)$newContents_0[0];
                // arrPrintHijau($oldContents);
                $content .= ("<tr>");
                $content .= ("<td>" . $no . "</td>");
                $content .= ("<td>" . $row->dtime . "</td>");
                foreach ($listedFields as $fName => $label) {
                    $type_conten = isset($fields[$fName]['type']) ? $fields[$fName]['type'] : "";
                    $fColName = $fName;
                    switch ($type_conten) {
                        default:
                            $strContent = isset($newContents[$fColName]) ? $newContents[$fColName] : "-";
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
                    $content .= ($strOldContent);
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
            $(thisTable).dataTable();
        </script>";

        echo $content;
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
        $mainObj = $o = new $className;

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
            $mainInsertId = $insertID = $o->addData($tmpContent, $o->getTableName());
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

            /* ------------------------------------------------
             * menulis data history perubahan
             * ------------------------------------------------*/
            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $origID,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($tmpContent)),
                // "new_content_intext" => print_r($tmpContent, true),
                "label" => "approved",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            showLast_query("kuning");

            /* -------------------------------------
             * tested pada auto COA yg pakai aaproval masuk di do sm dg yg di addProcess
             * -------------------------------------*/
            if (method_exists($mainObj, "getConnectingData")) {

                $data = $tmpContent;
                $nama = ucwords($data['nama']);
                $negara = $data['country'];
                $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                $my_name = my_name();

                cekBiru($negara . " $extern_tipe");
                $connectings = $mainObj->getConnectingData();
                foreach ($connectings as $model => $param_connecting) {

                    foreach ($param_connecting as $paramConnecting) {
                        $fields = isset($paramConnecting['fields']) ? $paramConnecting['fields'] : $paramConnecting;
                        $this->load->model($paramConnecting['path'] . "/$model");
                        $connObj = new $model();
                        if (isset($paramConnecting['staticOptions'])) {

                            $strHead_code = is_array($paramConnecting['staticOptions']) ? $paramConnecting['staticOptions'][$extern_tipe] : $paramConnecting['staticOptions'];
                        }
                        else {
                            matiHere("static optionnya tolong dikasih");
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

                        arrPrint($datas);
                        // cekLime();
                        /* -------------------------------------------------
                         * menulis ke table connecting
                         * -------------------------------------------------*/
                        $lastInset_code = $connObj->$paramConnecting['fungsi']($strHead_code, $datas);
                        showLast_query("merah");

                        /* -------------------------------------------------
                         * ngupdate ke data utama
                         * -------------------------------------------------*/
                        if (isset($paramConnecting['updateMain'])) {

                            foreach ($paramConnecting['updateMain']['condites'] as $key => $condite) {
                                $mainCondites[$key] = $$condite;
                            }
                            foreach ($paramConnecting['updateMain']['datas'] as $key => $val) {
                                $mainUpdate[$key] = $$val;
                            }

                            $mainObj->updateData($mainCondites, $mainUpdate);
                            showLast_query("orange");
                        }
                    }


                    // cekHitam($lastInset_code);
                }


                // arrPrint($connecting);
            }
        }


        // matiHere("hoop ----DONE---- belom commit :: ". __METHOD__);
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

        $file = fopen(__DIR__ . '/eusvc/sync/' . $className . "_" . $this->session->login['toko_id'] . ".txt", "w");
        echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
        fclose($file);

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
                    if ($fieldSpec['inputType'] == "hidden_ref") {

                    }
                    else {
                        $content .= "<th style='$fStyle' align='center'>";
                        $content .= str_replace(" ", "&nbsp;", $fieldSpec['label']) . $suffix;
                        $content .= "</th>";
                    }

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
                        if ($jCtr == 1) {
                            $content .= "<input type='hidden' name='ctr[]' value='$iCtr'>";
                        }
                        if ($fieldSpec['inputType'] == "hidden_ref") {
                            if (isset($fieldSpec['reference'])) {
                                $className = $fieldSpec['reference'];
                                $this->load->model("Mdls/" . $className);
                                $o2 = new $className;
                                if (isset($fieldSpec['referenceFilter']) && sizeof($fieldSpec['referenceFilter']) > 0) {
                                    $aFilter = $fieldSpec['referenceFilter'];
                                    $o2 = makeFilter($aFilter, $this->session->login, $o2);
                                }
                                $dataSource = $o2->lookupAll()->result();
                                // cekHitam($this->db->last_query());
                                $defaultValue = $dataSource[0]->$fieldSpec['referenceSrc'];
                                // matiHEre($defaultValue);
                                $content .= "<input type='hidden' name='" . $fieldSpec['kolom'] . "[]' id='" . $fieldSpec['kolom'] . "_" . $iCtr . "' value='$defaultValue'>";
                            }

                        }
                        else {
                            $content .= "<td style='padding:0px;margin:0px;'>";
                            switch ($fieldSpec['inputType']) {
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
            //            "mode" => $this->uri->segment(2),
            "mode" => "addMany",
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

                            $this->session->errMsg .= "<br><a href='JavaScript:void(0)' onclick=\"$editClick\">view entry</a>";


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
        $mainObj = $o = new $className;
        $indexFieldName = "id";
        $fields = $o->getFields();
        $validRules = $o->getValidationRules();

        //         arrPrint($_POST);
        //         matiHEre();
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
                    cekHitam($fieldName);
                    //                    cekHitam($fieldSpec['inputType']);
                    cekHijau($fieldSpec);
                    if (!in_array($fieldSpec['inputType'], $inputTypeWhitelist)) {
                        $inputName = $fieldSpec['kolom'];
                        if (isset($_POST[$inputName][$ctr]) && strlen($_POST[$inputName][$ctr]) > 0) {
                            //                            cekKuning($inputName . " ada, yaitu " . $_POST[$inputName][$ctr]);
                            $filledCols[] = $inputName;
                        }
                    }
                }
                cekkuning("$ctr diisi: ");
                //                arrPrint($filledCols);

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
                        cekMerah("$ctr TIDAK VALID, jadi TIDAK MENULIS");
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


                            $mainInsertId = $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                            //                            cekHijau($this->db->last_query());
                            $this->session->errMsg = "Data contents have been saved";


                            /* -------------------------------------
                             * tested pada auto COA yg pakai aaproval masuk di do
                             * -------------------------------------*/
                            if (method_exists($mainObj, "getConnectingData")) {
                                $nama = ucwords($data['nama']);
                                $negara = isset($data['country']) ? $data['country'] : "";
                                $extern_tipe = $negara == "ID" ? "lokal" : "non_lokal";
                                $my_name = my_name();
                                // cekBiru($negara . " $extern_tipe");
                                // cekHEre($toko_id);
                                $connectings = $mainObj->getConnectingData();
                                //                                arrPrintWebs($connectings);
                                foreach ($connectings as $model => $param_connecting) {
                                    foreach ($param_connecting as $ii => $paramConnecting) {
                                        //                                        cekKuning($ii);
                                        //                                        arrPrint($paramConnecting);
                                        $fields = isset($paramConnecting['fields']) ? $paramConnecting['fields'] : $paramConnecting;
                                        //                                        $this->load->model($paramConnecting['path'] . "/$model");
                                        $this->load->model($paramConnecting['path'] . "/$model");
                                        $connObj = new $model();
                                        // $strHead_code = isset($param_connecting['staticOptions'][$extern_tipe]) ? $param_connecting['staticOptions'][$extern_tipe] : matiHere("parameter");


                                        if (isset($paramConnecting['staticOptions'])) {
                                            $strHead_code = is_array($paramConnecting['staticOptions']) ? $paramConnecting['staticOptions'][$extern_tipe] : $paramConnecting['staticOptions'];
                                        }
                                        else {
                                            matiHere("static optionnya tolong dikasih");
                                        }
                                        $datas = array();

                                        foreach ($fields as $field => $cfParams) {
                                            //                                            cekHere(": $field : ");
                                            //                                            cekHere($cfParams);

                                            if (isset($cfParams['var_main'])) {
                                                $cNilai = $$cfParams['var_main'];
                                                cekHitam($cNilai);

                                            }
                                            else {
                                                $cNilai = $cfParams['str'];
                                            }

                                            $datas[$field] = $cNilai;
                                        }


                                        $lastInset_code = $connObj->$paramConnecting['fungsi']($strHead_code, my_toko_id(), $datas);
                                        showLast_query("merah");

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
                                    }
                                    // matiHere();
                                    // cekHitam($lastInset_code);
                                }
                                // arrPrint($connecting);
                            }


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

                            $this->session->errMsg .= "<br><a href='JavaScript:void(0)' onclick=\"$editClick\">view entry</a>";


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
            //             arrPrint($inValidRows);
            //             arrPrint($validRows);
            //            mati_disini();

            if (sizeof($validRows) > 0 || sizeof($inValidRows) > 0) {
                $inValidRows = array();
                if (sizeof($inValidRows) > 0) {
                    // arrprint($inValidRows);
                    // cekmerah("ada yang invalid");
                    //                echo lgShowAlert("you must fill in at least one line of entry");
                    echo "<script>";
                    //                    echo ("alert('you must fill in at least one line of entry)");
                    echo "top.document.getElementById('btnSave').disabled=false;";
                    echo "</script>";
                }
                else {
                    cekmerah("LANCAAR");
                    //                     matiHere();
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

    public function editMany_ori()
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
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='JavaScript:void(0);' onclick =\"$approvalClick;\">review</a>";
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


                //                $tmpItem['history'] = "<a class='btn btn-default' href='JavaScript:void(0)' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span> history</a>";
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
                    $tmpItem['option'] = "<a href='JavaScript:void(0)' onclick=\"$optClick\">" . "<span class='glyphicon glyphicon-option-vertical'></span>" . "</a>";
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
            $strAddLink .= "<button href='JavaScript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";

            $strAddLink .= "<button href='JavaScript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";

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
            "subTitle" => "Modify $realObjName" . addslashes($titleSuffix),
            "historyTitle" => "<span class='glyphicon glyphicon-th-list'></span> Directly modify $title" . addslashes($titleSuffix) . " below",
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
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='JavaScript:void(0);' onclick =\"$approvalClick;\">review</a>";
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


                //                $tmpItem['history'] = "<a class='btn btn-default' href='JavaScript:void(0)' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span> history</a>";
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
                    $tmpItem['option'] = "<a href='JavaScript:void(0)' onclick=\"$optClick\">" . "<span class='glyphicon glyphicon-option-vertical'></span>" . "</a>";
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
            $strAddLink .= "<button href='JavaScript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";

            $strAddLink .= "<button href='JavaScript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";

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
            "subTitle" => "Modify $realObjName" . addslashes($titleSuffix),
            "historyTitle" => "<span class='glyphicon glyphicon-th-list'></span> Directly modify $title" . addslashes($titleSuffix) . " below",
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
        //        arrPrint($arrKolom);
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
        //        cekHere($this->db->last_query());

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

    // -------------------------begin server side data table ---------------------
    /* ----------------------------------------
     * Pemanggil dat
     * ----------------------------------------*/
    function viewdt()
    {
        // if (my_cabang_id() > 0) {
        //     $folderId = my_cabang_id();
        // }
        // else {
        $folderId = isset($_GET['id']) ? $_GET['id'] : "";
        // }
        $ctrlName = $this->segment_4;
        $title = $ctrlName;
        $this->load->model("Mdls/" . $this->className);
        $o = new $this->className;
        $indexFieldName = "id";
        $maximumData = $o->getMaximumData();
        $buttonDatas = $o->getBtnActions();
        $buttonnActionAll = $o->getBtnActionAll();
        $fields = $o->getFields();
        /* -----------------------------------------------------------------------------------------------
         * jml maksimal data yg diperbolehkan, setting ada dimaisng2 model protected $maximumData = ...;
         * model yg tidak ada limit berarti unlimited
         * -----------------------------------------------------------------------------------------------
         */
        $o->setTokoId(my_toko_id());
        $jmlDataNow = $o->lookupJmlActive();
        // showLast_query("kuning");
        // $limitedEditor = $o->getLimiteEditor();

        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Contains '$key'";
        }
        else {
            $key = "";
            $subtitle = "List of $title";
        }
        $dataExtRel = isset($this->config->item('dataExtRelation')[$this->className]["images"]) ? $this->config->item('dataExtRelation')[$this->className]["images"] : array();
        $arrayItem = array();
        // if (sizeof($tmp) > 0) {//===ada data

        // if ($this->uri->segment(3) > 0) {
        //     $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
        // }
        // else {
        //     $rowCounter = 0;
        // }

        // -----begin----------
        $dataRel = isset($this->config->item('dataRelation')[$this->className]) ? $this->config->item('dataRelation')[$this->className] : array();
        //<editor-fold desc="data proposal data">
        $this->load->model("Mdls/" . "MdlDataTmp");
        $tData = new MdlDataTmp();
        $tData->addFilter("mdl_name='" . $this->className . "'");
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

        $arrayHeader = $this->listedFields;
        if (sizeof($dataExtRel) > 0) {
            $arrayHeader["images"] = "images";
        }
        $arrayHeader["action"] = "action";
        if (sizeof($dataRel) > 0) {
            $arrayHeader["option"] = "<span class='glyphicon glyphicon-th-list'></span>";
        }

        // ==== region
        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='" . $this->className . "'");
        // $h->addFilter("toko_id='" . $this->session->login['toko_id'] . "'");
        $tmpH = $h->lookupRecentHistories()->result();
        //         arrPrint($this->session->login);
        // showLast_query("biru");

        $arrayRecap = array();
        if (sizeof($tmpH) > 0) {
            $tmpO = new $this->className();
            foreach ($tmpH as $row) {
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
                            //                            $img_scr = "src='$fieldLabel'";
                            $fblob_data = "<div><img $img_scr class='img-responsive' width='150px' ></div>";
                            $tmpRecap[$fName] = $fblob_data;
                            //                            $tmpRecap
                            break;
                        case "image":
                            $img_scr = "src='$fieldLabel'";
                            $fblob_data = "<div><img $img_scr class='img-responsive' width='150px' ></div>";
                            $tmpRecap[$fName] = $fblob_data;
                            break;
                    }


                }
                $arrayRecap[] = $tmpRecap;
            }
        }
        // ==== endregion


        $arrayProgressLabel['date'] = "date";
        $arrayProgressLabel['propose_type'] = "proposal type";
        $arrayProgressLabel = $arrayProgressLabel + $arrayHeader;
        $arrayRecapLabel = $arrayHeader;

        $arrayProgressLabel['action'] = "action";

        //<editor-fold desc="tampilan approval data">
        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {
            foreach ($dataProposals as $mdlName => $pSpec) {
                $this->load->model("Mdls/" . $mdlName);
                $o2 = new $mdlName();
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
                            $fieldLabel = isset($this->relationPairs[$fName][$fieldLabel]) ? $this->relationPairs[$fName][$fieldLabel] : "unknown rel";
                        }
                        $tmpItemTmp[$fName] = $fieldLabel;
                    }


                    $approvalClick = "BootstrapDialog.closeAll();
                    BootstrapDialog.show(
                                   {
                                        title:'Data " . $dSpec['label'] . " &raquo; Setujui $dataStatus ',
                                        message: $('<div></div>').load('" . base_url() . "Data/editFrom/" . $dSpec['label'] . "/" . $dSpec['id'] . "/" . $dSpec['origID'] . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        cssClass: 'edit-dialog',
                                        }
                                        );";

                    $tmpItemTmp["date"] = $dSpec['date'];
                    $tmpItemTmp["propose_type"] = $dSpec['propose_type'];
                    $tmpItemTmp["action"] = "<a class='btn btn-primary btn-block' href='JavaScript:void(0);' onclick =\"$approvalClick;\">review</a>";
                    $tmpItemTmp["history"] = "";
                    $arrItemTmp[] = $tmpItemTmp;
                }

            }
        }
        //</editor-fold>

        unset($arrayProgressLabel['history']);
        unset($arrayRecapLabel['action']);
        unset($arrayRecapLabel['history']);
        // -----end-----------

        $addLink_mdl = method_exists($o, "linkAddData") ? MODUL_PATH . $o->linkAddData() : "hdf";
        // cekHere($this->className);
        // cekHijau("$addLink_mdl");
        $addLink_local = MODUL_PATH . get_class($this) . "/add/$ctrlName";
        $addLink = method_exists($o, "linkAddData") ? $addLink_mdl : $addLink_local;
        $addManyLink = MODUL_PATH . get_class($this) . "/addMany/$ctrlName";
        $addManyLink = "";
        if ($this->allowCreate) {
            // $jmlDataNow = 3;
            $btn_disabled = ($maximumData > 0) && ($maximumData <= $jmlDataNow) ? "disabled" : "";
            $addClick = "
                        BootstrapDialog.show(
                           {
                                title:'New $title',
                                message: $('<div></div>').load('" . $addLink . "'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                cssClass: 'edit-dialog',
                                closable:true,
                            }
                        );";
            $strAddLink = "";
            $strAddLink .= "<div class='btn-group'>";
            $strAddLink .= "<button type='button' $btn_disabled href='JavaScript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";
            //            $strAddLink .= in_array("addMany", $limitedEditor) ? "" : "<button href='JavaScript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";
            if (isset($buttonnActionAll)) {
                $paramSett = $buttonnActionAll['setting'];
                $btn_label = $paramSett['label'];
                $btn_mdl = $paramSett['mdl'];
                $btn_events = str_replace("{base_url}", base_url(), $paramSett['events']);

                $strAddLink .= "<button type='button' $btn_disabled href='javascript:void(0)' class=\" btn btn-warning\" data-toggle='tooltip' data-placement='top' title='$btn_label' $btn_events><span class='fa fa-gear'></button>";
            }
            $strAddLink .= "</div class='btn-group'>";

        }
        else {
            $strAddLink = "";
        }
        if ($this->allowEdit) {
            // $strEditLink = in_array("addMany", $limitedEditor) ? "" : "<button href='JavaScript:void(0)' class=\" btn btn-default\" onClick=\"location.href='" . base_url() . get_class($this) . "/editMany/$ctrlName/" . $this->uri->segment(4) . "'\" data-toggle='tooltip' data-placement='top' title='Modify all $title in this page' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-pencil'></button>";
            $strEditLink = "";
            $strBulkEditLink = "";
        }
        else {
            $strEditLink = "";
            $strBulkEditLink = "";
        }


        // cekBiru($this->className);
        // switch ($this->className) {
        //     case "MdlRakCabang":
        //     case "MdlProduk":
        if (method_exists($o, "getNavFilters")) {
            $navFilter = $o->getNavFilters();
            $strCase = $navFilter['mdlFilter'];
            $strLabel = $navFilter['label'];
            $strKolom = $navFilter['kolomKey'];

            // $this->load->model("Mdls/MdlFolderProduk");
            $this->load->model("Mdls/$strCase");
            // $fo = new MdlFolderProduk();
            // cekBiru($strCase);
            if (my_cabang_id() > 0) {
                // $disabled = "disabled";
                // $folderId = my_cabang_id();
                // $this->db->where("id",my_cabang_id());

                switch ($strCase) {
                    case "MdlRakCabang":
                        $this->db->where("cabang_id", my_cabang_id());
                        break;
                    case "MdlCabang":
                        $this->db->where("id", my_cabang_id());
                        break;
                    default:
                        $this->db->where("cabang_id", my_cabang_id());
                        break;
                }
            }
            $fo = new $strCase();
            $this->db->where("toko_id", my_toko_id());
            $ftmp = $fo->lookupAll()->result();
            // showLast_query("kuning");
            // cekBiru($strCase ." ". my_cabang_id());
            //             arrPrint($ftmp);
            $link_self = MODUL_PATH . "" . $this->segment_2 . "/" . $this->segment_3 . "/" . $this->segment_4;
            $disabled = "";
            switch ($strCase) {
                case "MdlRakCabang":

                    break;
                default:
                    if (my_cabang_id() > 0) {
                        // $disabled = "disabled";
                        $folderId = my_cabang_id();
                    }
                    break;
            }

            $folder_link = "&nbsp; Terapkan filter $strLabel <select class='btn btn-info' $disabled onchange=\"location.href='$link_self?id='+this.value\">";
            $folder_link .= "<option value=''>--pilih $strLabel--</option>";
            // $folder_link .= "<option>$link_self</option>";
            foreach ($ftmp as $fitem) {
                $fSelected = $fitem->id == $folderId ? "selected" : "";
                $namanya = isset($fitem->nama) ? $fitem->nama : $fitem->name;
                $folder_link .= "<option value='" . $fitem->id . "' $fSelected>" . $namanya . "</option>";
            }
            $folder_link .= "</select>&nbsp;";

            /* ------------------------------------
             * btn sync nama-nama harus ada methodnya dulu di tiap modelnya
             * -----------------------------------*/
            if (method_exists($o, "paramSyncNamaNama")) {
                $link_sync = MODUL_PATH . "Data/doSyncNamaNama/" . $this->segment_3 . "/" . $this->segment_4;
                $folder_link .= "<button type='button' class='btn btn-warning' onclick=\"btn_result('$link_sync');\" data-toggle='tooltip' title='syncron'><i class='fa fa-refresh'></i></button>&nbsp;";
            }

        }
        else {
            $folder_link = "";
        }

        if (method_exists($o, 'lookupNonAktif')) {
            $link_non_aktif = base_url() . "Data/viewNonAktif/" . $this->segment_3;
            $folder_link .= "<a class='btn btn-grey bg-grey-1' href='$link_non_aktif' data-toggle='modal' data-target='#myModal' onclick=\"#\" data-toggle='tooltip' title='syncron'><span class='fa fa-eye'> $ctrlName Non Aktif</span></a>&nbsp;";
        }
        //        arrPrint($arrayRecapLabel);

        //redirect template

        // if ($ctrlName == "Meja") {
        //     $mode_switch = "pengaturan_meja";
        //     $view_switch = "pengaturan_meja";
        //
        //     $toko_id = $this->session->login['toko_id'];
        //
        //     $this->load->model("Mdls/MdlMeja");
        //     $mj = new MdlMeja();
        //
        //     $id_meja = isset($_GET['m']) ? $_GET['m'] : "";
        //     $baris_meja = isset($_GET['b']) ? $_GET['b'] : "";
        //     $kolom_meja = isset($_GET['k']) ? $_GET['k'] : "";
        //
        //     if (isset($_GET['m'])) {
        //
        //         $datas = array(
        //             "baris_meja" => $baris_meja,
        //             "kolom_meja" => $kolom_meja,
        //         );
        //         $up = $mj->updateData(array("id" => $id_meja), $datas);
        //
        //         $file = fopen(__DIR__ . "/eusvc/sync/MdlMeja_" . $this->session->login['toko_id'] . ".txt", "w");
        //         echo fwrite($file, json_encode(array("datetime" => date("Y-m-d H:i:s"))));
        //         fclose($file);
        //
        //     }
        //     else {
        //         $mj->addFilter("toko_id='$toko_id'");
        //         $mjTmp = $mj->lookupAll()->result();
        //         $arrMeja = array();
        //         foreach ($mjTmp as $k => $mj) {
        //             $arrMeja[$mj->id] = $mj;
        //         }
        //         $data = array(
        //             "title"     => $this->segment_3,
        //             "subTitle"  => "Management Table",
        //             'mode'      => "pengaturan_meja",
        //             'segment_3' => $this->segment_3,
        //             'mdl'       => $this->className,
        //             "error"     => $this->session->errMsg,
        //             "arrMeja"   => $arrMeja,
        //         );
        //         $this->load->view('pengaturan_meja', $data);
        //     }
        //
        // }
        // elseif ($ctrlName == "Struk") {
        //
        //     $mode_switch = "pengaturan_struk";
        //     $view_switch = "pengaturan_struk";
        //
        //     $toko_id = $this->session->login['toko_id'];
        //
        //     $this->load->model("Mdls/Mdl" . $ctrlName);
        //     $mj = new MdlStruk();
        //
        //     $mj->addFilter("toko_id='$toko_id'");
        //     $mjTmp = $mj->lookupAll()->result();
        //     $arrStruk = array();
        //     foreach ($mjTmp as $k => $mj) {
        //         $arrStruk[$mj->id] = $mj;
        //     }
        //     $data = array(
        //         "title"     => $this->segment_3,
        //         "subTitle"  => "PENGATURAN STRUK",
        //         'mode'      => $mode_switch,
        //         'segment_3' => $this->segment_3,
        //         'mdl'       => $this->className,
        //         "error"     => $this->session->errMsg,
        //         "arrStruk"  => $arrStruk,
        //     );
        //     $this->load->view($view_switch, $data);
        //
        // }
        // else {
        $mode_switch = "data_table";
        $view_switch = "data";

        //cekHere($this->className);
        $data = array(
            "title" => $this->segment_4,
            'mode' => $mode_switch,
            'segment_3' => $this->segment_4,
            'mdl' => $this->className,
            // --
            "error" => $this->session->errMsg,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "arrayProgressLabels" => $arrayProgressLabel,
            "arrayOnProgress" => $arrItemTmp,
            // ---
            "strDataHistTitle" => "<span class='glyphicon glyphicon-time'></span> recent data updates",
            "linkStr" => "",
            "add_link" => $strAddLink,
            "edit_link" => $strEditLink,
            "bulk_edit_link" => $strBulkEditLink,
            "folder_link" => isset($folder_link) ? $folder_link : "",
            "filterId" => $folderId,
            "filterId_str" => $folderId > 0 ? "?fid=$folderId" : "",
            // -----
            "arrayRecapLabels" => $arrayRecapLabel,
            "arrayRecap" => $arrayRecap,
            "maximumData" => $maximumData,
            "jmlDataNow" => $jmlDataNow,

        );
        // arrPrint($data);

        $this->load->view($view_switch, $data);
        // }

    }

    /* -------------------------------
     * data dipangil oleh ajax
     * ------------------------------*/
    function fetch_data()
    {
        //         arrPrint($_POST);

        //        ini_set('display_errors', 0);
        //        if (version_compare(PHP_VERSION, '5.3', '>=')) {
        //            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        //        }
        //        else {
        //            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        //        }
        if (ipadd() == "202.65.117.72") {
            // $this->db->limit(5);
            // $_POST['length'] = 10;
        }
        $mdlName = isset($_POST['mdl']) ? $_POST['mdl'] : $_GET['mdl'];
        $foldId = isset($_POST['fid']) ? $_POST['fid'] : isset($_GET['fid']) ? $_GET['fid'] : "";
        $ctrlName = $this->segment_4;
        $tokoID = isset($this->session->login['toko_id']) ? $this->session->login['toko_id'] : null;
        // $this->load->model("Mdls/MdlProduk");
        // cekHijau($mdlName);

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
        // ---------------------------------------------------------------
        // mati_disini(__LINE__);

        $this->load->model("Mdls/" . $mdlName);
        $buttonDatas = $this->$mdlName->getBtnActions();
        if ($mdlName != "MdlMenuGroup") {
            // $this->db->where_in("toko_id", "$tokoID");
        }

        if ($foldId > 0) {
            if (method_exists($this->$mdlName, "getNavFilters")) {
                $navFilter = $this->$mdlName->getNavFilters();
                $strCase = $navFilter['mdlFilter'];
                $strLabel = $navFilter['label'];
                $strKolom = $navFilter['kolomKey'];

                $this->db->where($strKolom, "$foldId");
            }
        }

        /* ---------------------------------------
         * #custom aliasing controler untuk title pada modal editor
         * ---------------------------*/
        $namaAlias = array(
            "Supplier" => "Vendor",
            "LokasiIndex" => "Lokasi Rak",
        );

        $tdPengenal = isset($namaAlias[$ctrlName]) ? $namaAlias[$ctrlName] : $ctrlName;

        $fields = $this->$mdlName->getFields();
        $listedFields = $this->$mdlName->getListedFields();

        //handle order by chpy
        //order di MdlMother di matikan karena error
        $arrListed = array();
        $nn = 1;
        foreach ($listedFields as $key => $title) {
            $arrListed[$nn] = $key;
            $nn++;
        }
        $ord = "";
        $dir = "";
        if (isset($_REQUEST['order'][0])) {
            $ord_column = $_REQUEST['order'][0]['column'];
            $ord_dir = $_REQUEST['order'][0]['dir'];
            $ord = isset($arrListed[$ord_column]) ? $arrListed[$ord_column] : "id";
            $dir = isset($ord_dir) ? $ord_dir : "ASC";
            $this->db->order_by($ord, $dir);
        }
        else {
            // $this->db->order_by("id", "DESC");
            $this->db->order_by("last_update", "DESC");
        }
        //handle order by chpy

        $fetch_data_0 = $this->$mdlName->make_datatables();
        // ceklime($this->db->last_query());
        // arrPrintHijau($_POST);
        $indexFieldName = "id";
        // showLast_query("lime");;
        // arrPrint(sizeof($fetch_data_0));
        // arrPrint($fetch_data_0);
        // matiHere();
        $dataIds = array();
        foreach ($fetch_data_0 as $fetch_datum) {
            $dataIds[] = isset($fetch_datum->id) ? $fetch_datum->id : "";
        }
        // arrPrintPink($mdlName);
        $paireds = array();
        if (method_exists($this->$mdlName, "getPairedData")) {
            $paireds = $this->$mdlName->getPairedData();
            if (count($paireds) > 0) {
                cekOrange(__LINE__ . " $mdlName");
                arrPrintPink($paireds);
                $pairedKolom = array();
                foreach ($paireds as $mdl_nama => $pairAttr) {
                    $this->load->model("Mdls/$mdl_nama");
                    $im = new $mdl_nama();
                    // $images = $im->callSpecs();
                    $im->setTokoId(my_toko_id());
                    cekBiru($pairAttr['methode']);
                    // $extDatas_0 = $images = $im->$pairAttr['methode']()->result_array();
                    $extDatas = $images = $im->$pairAttr['methode']();

                    showLast_query("lime");
                    // arrPrintPink($extDatas_0);
                    // $extDatas = isset($extDatas_0[$pairAttr['methode_key']]) ? $extDatas_0[$pairAttr['methode_key']] : $extDatas_0;
                    arrPrintHijau($extDatas);

                    if (is_array($pairAttr['kolom'])) {
                        foreach ($pairAttr['kolom'] as $ext_kolom => $ext_label) {
                            $pairedKolom[$ext_kolom]['label'] = $ext_label;
                            $pairedKolom[$ext_kolom]['default_nilai'] = $pairAttr['default_nilai'][$ext_kolom];
                        }
                    }
                    else {
                        $pairedKolom[$pairAttr['kolom']]['label'] = $pairAttr['label'];
                        // $pairedKolom[$pairAttr['kolom']]['default_nilai'] = $pairAttr['default_nilai'];
                        // $files = $pairAttr['kolom'];
                        // cekHijau("$files");
                        // $img = isset($pairAttr['kolom']) ? "<img src='$files'>" : "no";
                        // $pairedKolom[$pairAttr['kolom']]['default_nilai'] = $img;
                        // $pairedKolom[$pairAttr['kolom']]['images'][]['files'] = $img;
                        foreach ($extDatas as $im_produk_id => $extData) {
                            $pairedKolom[$pairAttr['kolom']]['images'][$im_produk_id]['files'] = $extData->files;

                        }
                    }
                }

                // arrPrintWebs($extDatas);
                // arrPrintPink($pairedKolom);
                /* ------------------------------------------------
                 * ijektor array uatama fetch_data
                 * ------------------------------------------------*/
                foreach ($fetch_data_0 as $fetch_datum) {
                    $fetch_datum_2 = isset($images[$fetch_datum->id]) ? $images[$fetch_datum->id] : array();
                    // $fetch_datum_3['images'] = $fetch_datum_2;
                    // $fetch_datum_3['qty_debet'] = $fetch_datum_2;
                    $fetch_datum_3['images'] = isset($extDatas[$fetch_datum->id]) ? $extDatas[$fetch_datum->id]->files : "";

                    // arrPrint($fetch_datum_2);
                    // arrPrintPink($fetch_datum_3);
                    // arrPrint($fetch_datum);

                    $fetch_data[] = (object)(((array)$fetch_datum) + (array)$fetch_datum_3);

                    // arrPrintWebs($fetch_data);
                    // matiHere();
                    // $fetch_data[] = 1;
                }
            }
            else {
                $pairedKolom = array();
                $fetch_data = $fetch_data_0;
            }
        }
        else {
            $pairedKolom = array();
            $fetch_data = $fetch_data_0;
        }

        // showLast_query("lime");
        // arrPrint($listedFields);
        // arrPrint($fetch_data);
        // cekAlert($_POST['anu']);
        //         echo $_POST['anu'];

        /* --------------------------------------------- ---------------------------------------------
         * apabila ada kolom yg tidak nongol perhatikan pemakaian config kolomAlt pada modelnya ya
         *     protected $kolomAlt = true;
         * ---------------------------------------------------*/
        if (isset($_GET['mdl'])) {
            // arrPrintPink($paireds);
            // arrPrint($fetch_data);
            // matiHere();
        }
        $tmpZ = array();
        if (sizeof($dataIds) > 0) {

            $this->load->model("Mdls/MdlHargaProduk");
            $zo = new MdlHargaProduk();
            $zo->addFilter("produk_id in (" . implode(',', $dataIds) . ")");
            $tmpZ = $zo->lookupAll()->result();

        }

        $priceList = array();
        if (sizeof($tmpZ) > 0) {
            foreach ($tmpZ as $row) {
                $priceList[$row->produk_id][$row->jenis_value] = $row->nilai;
            }
        }

        /* LIST DEVICE REGISTER */
        if ($buttonDatas) {

            $this->db->where("mdl_name", "MdlCabangDevice");
            $tmpDevRegis = $this->db->get("data__tmp")->result();
            $regDev = array();
            if (!empty($tmpDevRegis)) {
                foreach ($tmpDevRegis as $k => $deviceID) {
                    $regDev[$deviceID->cabang_id][] = $deviceID;
                }
            }
        }

        $objState = "0";
        $draw = isset($_POST['draw']) ? $_POST['draw'] : "";
        $no = isset($_POST['start']) ? $_POST['start'] : "";
        $data = array();
        $sub_array = array();
        // $no = 0;
        arrPrint($fetch_data);
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $title = isset($row->nama) ? $row->nama : "";
            $title_f = htmlspecialchars($title);
            $rowId = isset($row->id) ? $row->id : "";
            $statusDb = isset($row->status) ? $row->status : "0";

            $hpp = isset($priceList[$row->id]['harga_list']) ? $priceList[$row->id]['harga_list'] : 0;
            $images = isset($row->images) ? $row->images : array();
            // $image = sizeof($images) > 0 && isset($images[$rowId]['files']) ? $images[$rowId]['files'] : img_blank();
            $image = strlen($images) > 0 ? $images : img_blank();

            //            $hpp = isset($priceList[$row->id]['jual']) ? $priceList[$row->id]['jual'] : 0;
            $sub_array[] = "$no";
            foreach ($listedFields as $kolom => $label) {
                if (isset($fields[$kolom]['transformValue'])) {
                    $nilai_kolom = isset($row->$kolom) && $row->$kolom != "" ? $row->$kolom : "";
                    if (strlen($nilai_kolom) > 0) {
                        $sub_array[] = barcode($nilai_kolom, $fields[$kolom]['transformValue']);
                    }
                    else {
                        $sub_array[] = isset($row->$kolom) ? $row->$kolom : "-";
                    }
                }
                else {
                    if (isset($fields[$kolom]['linkModal'])) {
                        $prefTitleModal = isset($fields[$kolom]['prefTitleModal']) ? $fields[$kolom]['prefTitleModal'] : "";
                        $linkDetile = MODUL_PATH . $fields[$kolom]['linkModal'] . "/$rowId";
                        $linkModal = modalDialogBtn("$prefTitleModal $title", $linkDetile, 0);
                        $viewNilai = formatField($kolom, $row->$kolom);
                        $sub_array[] = isset($row->$kolom) ? "<a href='javascript:void(0);' onclick=\"$linkModal\">$viewNilai</a>" : "-";
                    }
                    else {
                        if (isset($fields[$kolom]['dataSource'])) {
                            $sub_array[] = isset($row->$kolom) ? $fields[$kolom]['dataSource'][$row->$kolom] : "-";
                        }
                        else {
                            $sub_array[] = isset($row->$kolom) ? formatField($kolom, $row->$kolom) : "-";
                        }
                    }
                }
            }

            if (sizeof($pairedKolom) > 0) {
                $modal_datas['title'] = "$title";
                $modal_datas['body'] = array();
                // foreach ($images as $imagee) {
                // }
                $modal_datas['body'] = $image;
                $images_e = str_replace("=", "", blobEncode($modal_datas));
                foreach ($pairedKolom as $pKolom => $pK_attr) {
                    switch ($pKolom) {
                        case "image":
                            if (sizeof($images) > 0) {
                                $modal = MODUL_PATH . "Data/modal/$ctrlName/" . $images_e;
                                $img_link = "<a href='$modal' data-toggle='modal' data-target='#myModal'><img src='$image' width='20px'></a>";
                            }
                            else {
                                $img_link = "<img src='$image' width='20px'>";
                            }
                            $sub_array[] = "$img_link";
                            break;
                        default:
                            $sub_array[] = isset($row->$pKolom) ? $row->$pKolom : $pK_attr['default_nilai'];
                            break;
                    }
                }
            }

            //<editor-fold desc="Description button actions">
            if ($this->allowEdit && $objState != "1") {
                $updateLink = MODUL_PATH . get_class($this) . "/edit/$ctrlName/" . $rowId . "";
                $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $tdPengenal " . addslashes($title_f) . "',
                                        cssClass: 'edit-dialog',
                                        message: $('<div id=l1></div>').load('" . $updateLink . "?1=1&pfid=l1'),
                                        draggable:false,
                                        closable:true,
                                        onhidden: function(dialogRef){
                                            // window.location.reload();
                                        }
                                        });";

                $updateCommentStr = "Klik untuk mengubah entri";
            }
            else {
                $updateCommentStr = "Anda tidak berhak mengubah entri";
                $editClick = "return false;";
            }
            $btn_actions = "";
            $idStatus = $idDeleted = isset($row->$indexFieldName) ? $row->$indexFieldName : "";
            $deleteLink = MODUL_PATH . get_class($this) . "/delete/$ctrlName/" . $idDeleted . "";
            $statusLink = MODUL_PATH . get_class($this) . "/status/$ctrlName/" . $idStatus . "/$statusDb";
            $linkHist = MODUL_PATH . get_class($this) . "/viewHistories/$ctrlName/" . $rowId;
            $historyClick = "   BootstrapDialog.closeAll();
                                BootstrapDialog.show({
                                    title:'$ctrlName change histories ',
                                    message: $('<div></div>').load('" . $linkHist . "'),
                                    size: BootstrapDialog.SIZE_WIDE,
                                    draggable:true,
                                    closable:true,
                                });";

            if ($this->allowEdit && $objState != "1") {
                // $addNumber = $colCounter == 0 ? "<a href='JavaScript:void(0)' onclick =\"$historyClick\"><span class='badge' style='background:#c0c0c0;color:#656564;'>$rowCounter</span></a>" : "";
                $btn_actions .= "<a class='btn bg-gradient-success btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
            }

            if ($this->allowDelete && $objState != "1") {
                if (array_key_exists($idDeleted, $data_aktif)) {
                    $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' disabled data-toggle='tooltip' data-placement='left' title='delete button disabled' onClick=\"\"><span class='glyphicon glyphicon-remove'></button>";
                }
                else {
                    // $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"if(confirm('Remove entry?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                    $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"delete_confirm('Peringatan','Data $title akan dihapus permanen','$deleteLink');\"><span class='glyphicon glyphicon-remove'></button>";
                }
                if (method_exists($this->$mdlName, 'lookupNonAktif')) {
                    // $btn_actions .= "<button class='btn btn-success btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='produk aktif' onClick=\"delete_confirm('Peringatan','Data $title akan dinon aktifkan','$statusLink');\"><span class='glyphicon glyphicon-check'></button>";
                    $btn_actions .= "<button class='btn btn-success btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='produk aktif' onClick=\"confirm_alert_result('Peringatan','Data $title akan dinon aktifkan','$statusLink');\"><span class='glyphicon glyphicon-check'></button>";
                }
            }

            if ($this->allowViewHistory) {
                $btn_actions .= "<a class='btn btn-default btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span></a>";
            }
            if (isset($buttonDatas)) {
                foreach ($buttonDatas as $btn_ky => $buttonParams) {
                    $btn_icon = isset($buttonParams['icon']) ? $buttonParams['icon'] : "";
                    $btn_label = $buttonParams['label'];
                    $btn_mdl = $buttonParams['mdl'];
                    $btn_events = str_replace("{base_url}", MODUL_PATH, $buttonParams['events']);
                    $btn_events = str_replace("{rowID}", $rowId, $btn_events);
                    // $btn_events = kurawalConvert_he_setting($buttonParams['events']).$rowId;
                    $label_icon = $btn_icon != "" ? "<i class='fa $btn_icon'></i>" : $btn_label;

                    $eyeBadge = isset($regDev[$rowId]) ? count($regDev[$rowId]) : "";

                    $btn_actions .= "<button type='button' id='$btn_ky" . "_$rowId' class='btn btn_anakan btn_$btn_ky" . "_$rowId btn-xs' data-toggle='tooltips' data-placement='lefts' title='$btn_label' $btn_events>$label_icon &nbsp; <span class='badge bg-red'>$eyeBadge</span></button>";
                }
            }
            //</editor-fold>

            $sub_array[] = $btn_actions;
            $sub_array[] = $hpp;

            $data[] = $sub_array;
        }

        $this->$mdlName->setTokoId(my_toko_id());
        $output = array(
            "draw" => intval($draw),
            "recordsTotal" => $this->$mdlName->get_all_data(),
            "recordsFiltered" => $this->$mdlName->get_filtered_data(),
            "data" => $data,
            //            "dummy" => $mdlName,
            //            "fields" => "$ord, $dir",
            //            "post" => isset($_REQUEST['order'][0]) ? $_REQUEST['order'][0] : "tidak ada ordering",
            //            "tmpZ" => isset($tmpZ) ? $tmpZ : "",
        );

        echo json_encode($output);
    }

    function fetch_data_produk()
    {

        $mdlName = isset($_POST['mdl']) ? $_POST['mdl'] : $_GET['mdl'];
        $foldId = isset($_POST['fid']) ? $_POST['fid'] : isset($_GET['fid']) ? $_GET['fid'] : "";
        $ctrlName = $this->segment_3;
        $tokoID = $this->session->login['toko_id'];

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
        }
        // ---------------------------------------------------------------

        $this->load->model("Mdls/" . $mdlName);

        if ($mdlName != "MdlMenuGroup") {
            $this->db->where_in("toko_id", "$tokoID");
        }

        if ($foldId > 0) {
            if (method_exists($this->$mdlName, "getNavFilters")) {
                $navFilter = $this->$mdlName->getNavFilters();
                $strCase = $navFilter['mdlFilter'];
                $strLabel = $navFilter['label'];
                $strKolom = $navFilter['kolomKey'];
                $this->db->where($strKolom, "$foldId");
            }
        }

        /* ---------------------------------------
         * #custom aliasing controler untuk title pada modal editor
         * ---------------------------*/
        $namaAlias = array(
            "Supplier" => "Vendor",
            "LokasiIndex" => "Lokasi Rak",
        );

        $tdPengenal = isset($namaAlias[$ctrlName]) ? $namaAlias[$ctrlName] : $ctrlName;

        $fields = $this->$mdlName->getFields();
        $listedFields = $this->$mdlName->getListedFields();

        //handle order by chpy
        //order di MdlMother di matikan karena error
        $arrListed = array();
        $nn = 1;
        foreach ($listedFields as $key => $title) {
            $arrListed[$nn] = $key;
            $nn++;
        }
        $ord = "";
        $dir = "";
        if (isset($_REQUEST['order'][0])) {
            $ord_column = $_REQUEST['order'][0]['column'];
            $ord_dir = $_REQUEST['order'][0]['dir'];
            $ord = isset($arrListed[$ord_column]) ? $arrListed[$ord_column] : "nama";
            $dir = isset($ord_dir) ? $ord_dir : "ASC";
            $this->db->order_by($ord, $dir);
        }
        else {
            $this->db->order_by("nama", "ASC");
        }
        //handle order by chpy

        $fetch_data_0 = $this->$mdlName->make_datatables();

        $indexFieldName = "id";

        $dataIds = array();
        foreach ($fetch_data_0 as $fetch_datum) {
            $dataIds[] = isset($fetch_datum->id) ? $fetch_datum->id : "";
        }

        $paireds = array();
        if (method_exists($this->$mdlName, "getPairedData")) {
            $paireds = $this->$mdlName->getPairedData();
            $pairedKolom = array();
            foreach ($paireds as $mdl_nama => $pairAttr) {
                $this->load->model("Mdls/$mdl_nama");
                $im = new $mdl_nama();
                $im->setTokoId(my_toko_id());
                $extDatas_0 = $images = $im->$pairAttr['methode']();
                $extDatas = isset($extDatas_0[$pairAttr['methode_key']]) ? $extDatas_0[$pairAttr['methode_key']] : $extDatas_0;
                if (is_array($pairAttr['kolom'])) {
                    foreach ($pairAttr['kolom'] as $ext_kolom => $ext_label) {
                        $pairedKolom[$ext_kolom]['label'] = $ext_label;
                        $pairedKolom[$ext_kolom]['default_nilai'] = $pairAttr['default_nilai'][$ext_kolom];
                    }
                }
                else {
                    $pairedKolom[$pairAttr['kolom']]['label'] = $pairAttr['label'];
                    $pairedKolom[$pairAttr['kolom']]['default_nilai'] = $pairAttr['default_nilai'];
                }
            }

            /* ------------------------------------------------
             * ijektor array uatama fetch_data
             * ------------------------------------------------*/
            foreach ($fetch_data_0 as $fetch_datum) {
                $fetch_datum_2 = isset($images[$fetch_datum->id]) ? $images[$fetch_datum->id] : array();
                $fetch_datum_3 = isset($extDatas[$fetch_datum->id]) ? $extDatas[$fetch_datum->id] : array();
                $fetch_data[] = (object)(((array)$fetch_datum) + $fetch_datum_3);
            }
        }
        else {
            $pairedKolom = array();
            $fetch_data = $fetch_data_0;
        }

        /* --------------------------------------------- ---------------------------------------------
         * apabila ada kolom yg tidak nongol perhatikan pemakaian config kolomAlt pada modelnya ya
         *     protected $kolomAlt = true;
         * ---------------------------------------------------*/
        if (isset($_GET['mdl'])) {
            // arrPrintPink($paireds);
            // arrPrint($fetch_data);
            // matiHere();
        }
        $tmpZ = array();
        if (sizeof($dataIds) > 0) {

            $this->load->model("Mdls/MdlHargaProduk");
            $zo = new MdlHargaProduk();
            $zo->addFilter("produk_id in (" . implode(',', $dataIds) . ")");
            $tmpZ = $zo->lookupAll()->result();

        }

        $priceList = array();
        if (sizeof($tmpZ) > 0) {
            foreach ($tmpZ as $row) {
                $priceList[$row->produk_id][$row->jenis_value] = $row->nilai;
            }
        }

        $objState = "0";
        $draw = isset($_POST['draw']) ? $_POST['draw'] : "";
        $data = array();
        $sub_array = array();
        $no = 0;
        foreach ($fetch_data as $row) {
            $no++;
            $sub_array = array();
            $title = isset($row->nama) ? $row->nama : "";
            $title_f = htmlspecialchars($title);
            $statusDb = isset($row->status) ? $row->status : "0";
            $images = isset($row->images) ? $row->images : array();
            $image = sizeof($images) > 0 && isset($images[0]->files) ? $images[0]->files : img_blank();
            $hpp = isset($priceList[$row->id]['harga_list']) ? $priceList[$row->id]['harga_list'] : 0;
            //            $hpp = isset($priceList[$row->id]['jual']) ? $priceList[$row->id]['jual'] : 0;
            $rowId = isset($row->id) ? $row->id : "";
            $readyStock = isset($row->readyStock) ? $row->readyStock : "";
            $sub_array[] = "$no";

            foreach ($listedFields as $kolom => $label) {
                if (isset($fields[$kolom]['transformValue'])) {
                    $nilai_kolom = isset($row->$kolom) && $row->$kolom != "" ? $row->$kolom : "";
                    if (strlen($nilai_kolom) > 0) {
                        $sub_array[] = barcode($nilai_kolom, $fields[$kolom]['transformValue']);
                    }
                    else {
                        $sub_array[] = isset($row->$kolom) ? $row->$kolom : "-";
                    }
                }
                else {
                    if (isset($fields[$kolom]['linkModal'])) {
                        $linkDetile = MODUL_PATH . $fields[$kolom]['linkModal'] . "/$rowId";
                        $linkModal = modalDialogBtn("'$title'", $linkDetile, 0);
                        $viewNilai = formatField($kolom, $row->$kolom);
                        $sub_array[] = isset($row->$kolom) ? "<a href='JavaScript:Void(0);' onclick=\"$linkModal\">$viewNilai</a>" : "-";
                    }
                    else {
                        if (isset($fields[$kolom]['dataSource'])) {
                            $sub_array[] = isset($row->$kolom) ? $fields[$kolom]['dataSource'][$row->$kolom] : "-";
                        }
                        else {
                            $sub_array[] = isset($row->$kolom) ? formatField($kolom, $row->$kolom) : "-";
                        }
                    }
                }
            }

            if (sizeof($pairedKolom) > 0) {
                $modal_datas['title'] = "$title";
                $modal_datas['body'] = array();
                foreach ($images as $imagee) {
                    $modal_datas['body'][] = $imagee->files;
                }
                $images_e = str_replace("=", "", blobEncode($modal_datas));
                foreach ($pairedKolom as $pKolom => $pK_attr) {
                    switch ($pKolom) {
                        case "image":
                            if (sizeof($images) > 0) {
                                $modal = MODUL_PATH . "Katalog/modal/" . $images_e;
                                $img_link = "<a href='$modal' data-toggle='modal' data-target='#myModal'><img src='$image' width='50px'></a>";
                            }
                            else {
                                $img_link = "<img src='$image' width='50px'>";
                            }
                            $sub_array[] = "$img_link";
                            break;
                        default:
                            $sub_array[] = isset($row->$pKolom) ? $row->$pKolom : $pK_attr['default_nilai'];
                            break;
                    }
                }
            }

            if ($this->allowEdit && $objState != "1") {
                $updateLink = MODUL_PATH . get_class($this) . "/edit/$ctrlName/" . $rowId . "";
                $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $tdPengenal " . addslashes($title_f) . "',
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        onhidden: function(dialogRef){
                                            // window.location.reload();
                                        }
                                        });";

                $updateCommentStr = "Klik untuk mengubah entri";
            }
            else {
                $updateCommentStr = "Anda tidak berhak mengubah entri";
                $editClick = "return false;";
            }

            $btn_actions = "";
            $idStatus = $idDeleted = isset($row->$indexFieldName) ? $row->$indexFieldName : "";
            $deleteLink = MODUL_PATH . get_class($this) . "/delete/$ctrlName/" . $idDeleted . "";
            $statusLink = MODUL_PATH . get_class($this) . "/status/$ctrlName/" . $idStatus . "/$statusDb";
            $linkHist = MODUL_PATH . get_class($this) . "/viewHistories/$ctrlName/" . $rowId;

            $historyClick = "   BootstrapDialog.closeAll();
                                BootstrapDialog.show({
                                    title:'$ctrlName change histories ',
                                    message: $('<div></div>').load('" . $linkHist . "'),
                                    size: BootstrapDialog.SIZE_WIDE,
                                    draggable:true,
                                    closable:true,
                                });";

            if ($this->allowEdit && $objState != "1") {
                // $addNumber = $colCounter == 0 ? "<a href='JavaScript:void(0)' onclick =\"$historyClick\"><span class='badge' style='background:#c0c0c0;color:#656564;'>$rowCounter</span></a>" : "";
                $btn_actions .= "<a class='btn bg-gradient-success btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
            }
            if ($this->allowDelete && $objState != "1") {
                if (array_key_exists($idDeleted, $data_aktif)) {
                    $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' disabled data-toggle='tooltip' data-placement='left' title='delete button disabled' onClick=\"\"><span class='glyphicon glyphicon-remove'></button>";
                }
                else {
                    // $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"if(confirm('Remove entry?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                    $btn_actions .= "<button class='btn btn-danger btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='delete entry' onClick=\"delete_confirm('Peringatan','Data $title akan dihapus permanen','$deleteLink');\"><span class='glyphicon glyphicon-remove'></button>";
                }
                if (method_exists($this->$mdlName, 'lookupNonAktif')) {
                    // $btn_actions .= "<button class='btn btn-success btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='produk aktif' onClick=\"delete_confirm('Peringatan','Data $title akan dinon aktifkan','$statusLink');\"><span class='glyphicon glyphicon-check'></button>";
                    $btn_actions .= "<button class='btn btn-success btn-xs hidden-print' data-toggle='tooltip' data-placement='left' title='produk aktif' onClick=\"confirm_alert_result('Peringatan','Data $title akan dinon aktifkan','$statusLink');\"><span class='glyphicon glyphicon-check'></button>";
                }
            }
            if ($this->allowViewHistory) {
                $btn_actions .= "<a class='btn btn-default btn-xs' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='view histories of this entry' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span></a>";
            }

            $sub_array[] = $hpp;
            $sub_array[] = $readyStock;
            $sub_array[] = $rowId;

            $data[] = $sub_array;
        }

        //        sorting array tapi blm di gunakan jangan di hapus
        //        usort($data, function($a, $b) {
        //            return $b['5'] - $a['5'];
        //        });

        $this->$mdlName->setTokoId(my_toko_id());
        $output = array(
            "draw" => intval($draw),
            "recordsTotal" => $this->$mdlName->get_all_data(),
            "recordsFiltered" => $this->$mdlName->get_filtered_data(),
            "data" => $data,
            //            "dummy" => $mdlName,
            //            "fetch_data_0" => $fetch_data_0,
            //            "fields" => "$ord, $dir",
            //            "post" => isset($_REQUEST['order'][0]) ? $_REQUEST['order'][0] : "tidak ada ordering",
            //            "tmpZ" => isset($tmpZ) ? $tmpZ : "",
        );

        echo json_encode($output);
    }

    function doSyncNamaNama()
    {
        // cekHitam($this->className);
        // cekHitam($this->segment_3);
        $this->load->model("Mdls/" . $this->className);
        $tm = new $this->className();
        if (method_exists($tm, "syncNamaNama")) {
            // matiHere();
            $cek = $tm->syncNamaNama();
        }
        else {
            matiHere(__LINE__ . " syncNamaNama " . $this->className);
        }

        // arrPrint($cek);
        // cekHitam();
        topReload(100);
    }

    // -------------------------ending server side data table ---------------------

    public function mutasi()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("MdlTransaksi");
        $produk_id = $this->uri->segment(4);
        $cabangs = blobDecode($this->uri->segment(5));
        $cabang_id = $cabangs['id'];
        $cabang_nama = $cabangs['nama'];
        $co = new ComRekeningPembantuProduk();
        $pr = new MdlProduk();
        $tr = new MdlTransaksi();

        $proTmps = $pr->callSpecs($produk_id)[$produk_id];
        $produk_nama = $proTmps->kode . " " . $proTmps->nama;
        // showLast_query("orange");
        // arrPrint($proTmps);
        // cekBiru($proTmps);
        // persediaan produk
        // cekBiru();
        $this->db->where("cabang_id", $cabang_id);
        $tmps = $co->fetchMoves("persediaan produk", $produk_id);
        // showLast_query("hijau");
        // cekHijau($tmps);
        $transaksiIds = array();
        foreach ($tmps as $tmp) {
            $transaksiIds[] = $tmp->transaksi_id;
        }
        // arrPrint($transaksiIds);
        $trTmps = $tr->callSpecs($transaksiIds);
        // arrPrint($trTmps);
        $jenis_alias = array(
            "585" => "distribusi",
            "582" => "jual",
            "467" => "beli",
        );

        $datas = array();
        foreach ($tmps as $tmp) {
            // cekHijau($tmp->jenis);
            $tmp_1 = $trTmps[$tmp->transaksi_id];
            // $tmp_4['fff'] = "kk";
            //$tmp->jenis, $jenis_alias
            $tmp_4['jenis_alias'] = key_exists($tmp->jenis, $jenis_alias) ? $jenis_alias[$tmp->jenis] : $tmp->jenis;
            // arrPrintPink($tmp_1);
            $tmp_3 = (array)$tmp + (array)$tmp_1 + $tmp_4;

            $datas[] = (object)$tmp_3;
        }
        // arrPrint($datas);
        $fields = array(
            "fulldate" => array(
                "label" => "tanggal"
            ),
            "jenis_alias" => array(
                "label" => "jenis"
            ),
            "oleh_nama" => array(
                "label" => "oleh"
            ),
            "customers_nama" => array(
                "label" => "konsumen"
            ),
            "qty_debet_awal" => array(
                "label" => "awal"
            ),
            "qty_debet" => array(
                "label" => "masuk"
            ),
            "qty_kredit" => array(
                "label" => "keluar"
            ),
            "qty_debet_akhir" => array(
                "label" => "akhir"
            ),
        );

        // mati_disini();
        $data = array(
            "mode" => "modalView",
            "field" => $fields,
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "heading" => "Mutasi $produk_nama :: $cabang_nama",
            "forms" => $datas,
            "footer" => "",
            "target" => "result",
            "actions" => "/Data/editoneProcess/User",
            // "arrActivitylog" => $arrActivitylog,
            "headTpl" => headTpl(),
            "footTpl" => footTpl(),
        );

        $this->load->view("data", $data);
    }

    public function fmHarga()
    {
        $this->load->helper("he_mass_table");
        // $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlHargaProduk");

        $produk_id = $this->uri->segment(4);
        $cabangs = blobDecode($this->uri->segment(5));
        $cabang_id = $cabangs['id'];
        $cabang_nama = $cabangs['nama'];
        // $co = new ComRekeningPembantuProduk();
        $pr = new MdlProduk();
        $tr = new MdlHargaProduk();

        $proTmps = $pr->callSpecs($produk_id)[$produk_id];
        $produk_nama = $proTmps->kode . " " . $proTmps->nama;

        $condites = array(
            "produk_id" => $produk_id,
            "cabang_id" => $cabang_id,
            "jenis_value" => "jual",
        );
        $tmps = $tr->lookupByCondition($condites)->result();
        // showLast_query("lime");
        // arrPrintPink($tmps);
        $harga = $tmps[0]->nilai * 1;
        $data_id = $tmps[0]->id;
        $condites_e = blobEncode($condites);
        $forms = array(
            "Harga" => "<input type='number' name='harga' class='form-control' value='$harga'>
                        <input type='hidden' name='data_id' value='$data_id'>
                        <input type='hidden' name='cabang_id' value='$cabang_id'>
                        <input type='hidden' name='produk_id' value='$produk_id'>
                        <input type='hidden' name='condites' value='$condites_e'>
                        <input type='hidden' name='harga_ori' value='$harga'>",
        );
        // mati_disini();
        $data = array(
            "mode" => "modal",
            "field" => "",
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "heading" => "Edit $produk_nama :: $cabang_nama",
            "forms" => $forms,
            "footer" => form_submit("submit", "Save", "class='btn btn-primary pull-right'"),
            "target" => "result",
            "actions" => "/Data/editHarga/produk/$produk_id/$cabang_id",
            // "arrActivitylog" => $arrActivitylog,
            "headTpl" => headTpl(),
            "footTpl" => footTpl(),
        );

        $this->load->view("data", $data);
    }

    public function editHarga()
    {
        $cabang_id = $_POST['cabang_id'];
        $produk_id = $_POST['cabang_id'];
        $data_id = $_POST['data_id'];
        $harga = $_POST['harga'];
        $harga_ori = $_POST['harga_ori'];
        $condites_add = blobDecode($_POST['condites']);

        $this->load->model("Mdls/MdlHargaProduk");
        $tr = new MdlHargaProduk();
        $condites = array(
            "id" => $data_id
        );
        $datas = array(
            "trash" => 1
        );
        $datas_harga = array(
            "nilai" => $harga
        );

        $datas_new = $condites_add + $datas_harga;

        $this->db->trans_begin();
        $up = $tr->updateData($condites, $datas);
        $tr->addData($datas_new);

        $this->db->trans_complete();
        $alerts = array(
            "type" => "success",
        );
        echo swalAlert($alerts);
        topReload(500);

        // arrPrintPink($_POST);
        // arrPrintPink($_REQUEST);
    }

    public function viewNonAktif()
    {

        // $this->className
        //         cekBiru($this->className);
        $this->load->model("Mdls/" . $this->className);
        $o = new $this->className();
        // $sources = $o->lookupNonAktif();
        $o->setFilters(array());
        $o->addFilter("toko_id=" . $this->session->login['toko_id']);
        $sources = $o->lookupNonAktif()->result();
        //         showLast_query("merah");
        //         arrPrintPink($sources);
        $field = array(
            "folders_nama" => array(),
            "merek_nama" => array(),
            "kode" => array(),
            "nama" => array(),
            "no_part" => array(),
            "satuan" => array(),
            "barcode" => array(),
            // "btn" => array(),
        );
        $field_tambahan = array(
            "btn" => array(
                "label" => "action",
                "tipe" => "button",
                "value" => "aktifasi",
                "class" => "btn btn-success btn-xs",
                "link" => MODUL_PATH . "Data/status/" . $this->segment_3,
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

    public function pengaturan_meja()
    {

        $toko_id = $this->session->login['toko_id'];

        $this->load->model("Mdls/MdlMeja");
        $mj = new MdlMeja();

        $id_meja = isset($_GET['m']) ? $_GET['m'] : "";
        $baris_meja = isset($_GET['b']) ? $_GET['b'] : "";
        $kolom_meja = isset($_GET['k']) ? $_GET['k'] : "";

        if (isset($_GET['m'])) {

            $datas = array(
                "baris_meja" => $baris_meja,
                "kolom_meja" => $kolom_meja,
            );
            $up = $mj->updateData(array("id" => $id_meja), $datas);

        }
        else {
            $mj->addFilter("toko_id='$toko_id'");
            $mjTmp = $mj->lookupAll()->result();
            $arrMeja = array();
            foreach ($mjTmp as $k => $mj) {
                $arrMeja[$mj->id] = $mj;
            }
            $data = array(
                "title" => $this->segment_3,
                "subTitle" => "Management Table",
                'mode' => "pengaturan_meja",
                'segment_3' => $this->segment_3,
                'mdl' => $this->className,
                "error" => $this->session->errMsg,
                "arrMeja" => $arrMeja,
            );
            $this->load->view('pengaturan_meja', $data);
        }


    }

    public function pencarian()
    {
        $mdl = "Mdl" . ucfirst(url_segment(4));

        // arrPrint(url_segment());
        // cekMerah("$mdl");

        $kword_0 = $_GET['key'];

        $kword_00 = explode(" ", $kword_0);
        $input_id = isset($_GET['mid']) ? $_GET['mid'] : "";
        $this->load->model("Mdls/$mdl");
        $pr = new $mdl();
        $count_kword = strlen($kword_0);
        /*--keyword hasil explode */


        $src_pr_obj = array();
        $produk_ids = array();
        $jml_data = 0;
        if ($count_kword > 0) {
            foreach ($kword_00 as $kword) {
                $condites = array(
                    "nama like" => "%$kword%",
                    "kode like" => "%$kword%",
                    "barcode like" => "%$kword%",
                );
                $this->db->group_start();
                $this->db->or_where($condites);
                $this->db->group_end();
            }

            $this->db->limit(10);
            $src_pr_obj = $pr->callSpecs();

            // showLast_query("kuning");
            $jml_data = sizeof($src_pr_obj);
            // cekBiru(sizeof($src_pr_obj));
            $produk_ids = array_keys($src_pr_obj);
            /*-----------produk harga------------*/
            $prod_hargas = array();
            if (sizeof($produk_ids) > 0) {

                $this->load->model("Mdls/MdlHargaProduk");
                $hp = new MdlHargaProduk();
                $hp->setTokoId(my_toko_id());
                $hp->setCabangId(my_cabang_id());
                // $this->db->where("jenis_value", "harga_list");
                $this->db->where("jenis_value", "hpp");
                $prod_hargas = $hp->callSpecs($produk_ids);
            }
            // showLast_query("kuning");
            // arrPrintKuning($prod_hargas);
            $harga_list = array();
            foreach ($prod_hargas as $prod_id => $prod_harga_00s) {
                foreach ($prod_harga_00s as $prod_harga) {
                    $nilai = $prod_harga->nilai * 1;
                    $harga_list[$prod_id] = $nilai;
                }
            }
            // arrPrintHijau($harga_list);
        }
        $var = "";
        $var_isi = "";
        $btn_hidde = "";
        // $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        if (sizeof($src_pr_obj) > 0) {
            $var = $jml_data;
            $link_insert = MODUL_PATH . "Data/addRelasi/ProdukPerSupplier";
            $var_isi .= "<ol class='todo-list ui-sortable'>";
            foreach ($src_pr_obj as $item) {
                // arrPrint($item);
                $id = $item->id;
                // $input_id = "";
                $nama = $item->nama;
                $satuan = isset($item->satuan) ? $item->satuan : "-";
                $harga_jual = isset($harga_list[$id]) ? $harga_list[$id] : 0;
                $harga_jual_f = formatField_he_format("harga", $harga_jual);

                $nama_f = highlight_he_format($nama, $kword_0);
                // $nama_ff = highlight_2($nama,$kword_0);

                // $var_isi .= "<li style='padding: 3px 5px;' title='$id'><a href='javascript:void(0)' onclick=\"$('#$input_id').val('$nama');$('#harga_$input_id').val('$harga_jual');$('#harga_text_$input_id').val('$harga_jual');\">$nama_f ($satuan) $harga_jual_f</a></li>";
                $var_isi .= "<li style='padding: 3px 5px;' title='$id'><a href='javascript:void(0)' onclick=\"$('#insert').load('$link_insert?id=$id&mid=$input_id');\">$nama_f ($satuan) $harga_jual_f</a></li>";
            }
            $var_isi .= "</ol>";
        }
        $display = "display:block;";
        if ($jml_data > 10) {
            echo "<div style='wwidth: 100px'>ditemukan <span style='font-size: 1.3em;color: red;'>$var</span> item yang berkaitan dengan <span class='text-red'>$kword_0</span>";
            // echo "<button type='button' onclick=\"$('#hasil_$input_id').fadeIn();\">tampilkan</button> <button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
            echo "</div>";
            // $display = "display:none;";
            // echo $var_isi;
            // $btn_hidde = "tulisakan nama produk";
        }
        elseif ($jml_data == 0) {
            $var_isi = "tulisakan nama produk";
            if ($count_kword > 0) {

                $var_isi = "tidak ditemukan data yang berhubunga dengan <span class='font-size-1-2 text-red'>$kword_0</span>";
            }
        }
        // else{
        //     $btn_hidde = "<button type='button' onclick=\"$('#hasil_$input_id').fadeOut();\">hidde</button>";
        // }
        echo "<div style='widthh: 150px; $display'  id='hasil_$input_id'>$var_isi <hr style='padding: 0;margin: 10px 0 0;'> $btn_hidde</div>";


    }

    public function addRelasi()
    {

        $mdl = "Mdl" . ucfirst(url_segment(3));
        $parent_id = $_GET['mid'];
        $child_id = $_GET['id'];

        $pps = new MdlProdukPerSupplier();

        $this->db->trans_start();
        $datas = array(
            "produk_id" => $child_id,
            "suppliers_id" => $parent_id,
            "toko_id" => my_toko_id(),
            "cabang_id" => my_cabang_id(),
        );
        $add = $pps->addData($datas);
        // showLast_query("biru");
        // arrPrint(url_segment());
        // arrPrint($_GET);

        // matiHere(__LINE__);
        $this->db->trans_complete();

        $link_member = MODUL_PATH . "Data/viewRelasi/produkPerSupplier";
        // echo "<script>
        //         // top.$('#$div').load('$link_member');
        //     </script>";

    }

    public function cekNik()
    {

        $ktp = $this->uri->segment(4);

        $this->db->select('id, mid, tlp_1, nama, coa_code, no_ktp, npwp, status, trash');
        $this->db->where(array('no_ktp' => $ktp));
        $tmpCust = $this->db->get('per_customers');
        $cust = $tmpCust->result();

        if (!empty($cust)) {
            echo json_encode(array('status' => 0, 'data' => $cust));
        }
        else {
            echo json_encode(array('status' => 1));
        }

    }

    public function cekPhone()
    {
        $ktp = $this->uri->segment(4);
        $this->db->select('id, mid, tlp_1, nama, coa_code, no_ktp, npwp, status, trash');
        $this->db->where(array('tlp_1' => $ktp));
        $tmpCust = $this->db->get('per_customers');
        $cust = $tmpCust->result();
        if (!empty($cust)) {
            echo json_encode(array('status' => 0, 'data' => $cust));
        }
        else {
            echo json_encode(array('status' => 1));
        }

    }

    public function modal()
    {
        arrPrint(url_segment());
        $ly = new Layout();
        $datas = blobDecode($this->uri->segment(5));
        arrPrint($datas);
        $judul = $datas['title'];
        $action_form = isset($datas['action_form']) ? $datas['action_form'] : "";
        $att_form = isset($datas['attribute_form']) ? $datas['attribute_form'] : "";

        // echo "wewewew";
        $bd = "";
        //region prepare image ke carousel
        $arrImages = array();
        if (sizeof($datas['body']) > 0) {
            if (is_array($datas['body'])) {

                foreach ($datas['body'] as $file) {
                    $pic = $file;
                    $arrImages[] = $pic;
                }
            }
            else {
                $fileName = $datas['body'];
                cekHijau($datas['body']);
                $bd .= "<div class='text-center'>";
                // $bd .= "<img src='$fileName' class='img-bordered img-rounded text-center'>";
                $bd .= "<img src='$fileName' class='img-bordered img-thumbnail text-center'>";
                $bd .= "</div>";
            }
        }
        else {
            $arrImages[] = img_blank();
        }
        //endregion

        // arrPrint($arrImages);
        // $bd .= $ly->carousel($arrImages);
        // if (isset($datas['caption'])) {
        //     $bd .= "<div class='text-center panel panel-body margin-bottom-none margin-top-10' >";
        //     $bd .= $datas['caption'];
        //     $bd .= "</div>";
        // }
        //
        // $footer = form_button("close", "Close", "class='btn pull-left' data-dismiss='modal'");
        // if (isset($datas['action_form'])) {
        //     $footer .= form_submit("Submit", "Submit", "class='btn pull-right btn-primary'");
        // }

        $data = array(
            "mode" => "modal",
            "heading" => $judul,
            "forms" => $bd,
            "actios" => $action_form,
            "att" => $att_form,
            "footer" => $footer,
        );

        $this->load->view("data", $data);
    }

    public function viewSerial()
    {
        arrPrint(url_segment());
        $produk_id = url_segment(5);
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $crs_prs = $pr->callSpecs($produk_id);
        $data_produk = $crs_prs[$produk_id];
        arrPrintHijau($data_produk);


        $this->load->model("Mdls/MdlProdukPerSerialNumber");
        $psn = new MdlProdukPerSerialNumber();

        $this->db->where("produk_id", $produk_id);
        // $srcs = $psn->lookupJoinProduk()->result();
        $srcs = $psn->lookupAll()->result();
        // showLast_query("kuning");
        cekHere(count($srcs));
        // arrPrint($srcs);
        $data_serials = array();
        foreach ($srcs as $src) {
            $produk_sku_part_nama = $src->produk_sku_part_nama;

            $data_serials[$produk_sku_part_nama][] = (array)$src;
        }

        // arrPrint($data_serials);
        /* ----------------------------------------
         * mengatur header/data yg perlu tampil
         * ----------------------------------------*/
        if ($data_produk->jml_serial > 1) {
            $arrHeaders = array(
                "outdoor_nama" => array(
                    "label" => "out door",
                    "label_2" => "out door"
                ),
                "indoor_nama_1" => array(
                    "label" => "in door",
                ),
                // "indoor_nama_2" => array(),
                // "indoor_nama_3" => array(),
                // "indoor_nama_4" => array(),
            );
        }
        else {
            $arrHeaders = array(
                "produk_serial_number" => array(
                    "label" => "serial number (SN)",
                    "label_2" => "out door"
                ),
            );
        }

        /* -------------------------------------
         * data produk
         * -------------------------------------*/
        $tprod = "";
        $tprod .= $data_produk->id;
        $tprod .= " - " . $data_produk->nama;
        $tprod .= " - " . $data_produk->barcode;
        $tprod .= " - " . $data_produk->kode;
        $tprod .= " - " . $data_produk->jml_serial;

        /* -------------------------------------
         * header
         * -------------------------------------*/
        $thead = "<tr class='text-uppercase' style='background-color: cadetblue;'>";
        $thead .= "<th>no</th>";
        foreach ($arrHeaders as $kolom => $kolom_params) {
            $label = isset($kolom_params['label']) ? $kolom_params['label'] : $kolom;
            // arrPrintKuning($crs_prs);
            $label_2 = $data_produk->$kolom;

            foreach ($data_serials as $inoutdoor => $items) {
                $jmlRow[$inoutdoor] = count($items);
            }

            $thead .= "<th title='$kolom' class='text-uppercase text-center'>$label<br>$label_2</th>";
        }
        $thead .= "</tr>";

        /* -------------------------------------
         * body
         * -------------------------------------*/
        $tbody = "";
        // cekBiru(max($jmlRow));
        $jmlBaris = max($jmlRow);
        $no = 0;
        for ($xx = 1; $xx <= $jmlBaris; $xx++) {
            $no++;
            $tbody .= "<tr>";
            $tbody .= "<td>$no</td>";
            foreach ($arrHeaders as $kolom => $kolom_params) {
                $label_2 = $data_produk->$kolom;

                $serial = $data_serials[$label_2][($xx - 1)]["produk_serial_number"];
                // $tbody .= "<td>$xx $kolom $label_2 $serial</td>";
                $tbody .= "<td class='text-center'>$serial</td>";
            }
            $tbody .= "</tr>";
        }

        /* -------------------------------------
         * rendering data // penampil data
         * -------------------------------------*/
        $strData = "";
        $strData .= "<h2 style='margin-top: 0;'>$tprod</h2>";
        $strData .= "<div class='bg-putih'>";
        $strData .= "<table class='table table-striped'>";
        $strData .= $thead;
        $strData .= $tbody;
        $strData .= "</table>";
        $strData .= "</div>";

        echo $strData;
    }
}
