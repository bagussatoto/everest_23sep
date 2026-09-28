<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataAccessRight extends CI_Controller
{

    private $selectedId;


    public function getSelectedId()
    {
        return $this->selectedId;
    }

    public function setSelectedId($selectedId)
    {
        $this->selectedId = $selectedId;
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->helper("he_access_right");
        $this->selectedId = isset($_GET["attached"]) == "0" ? $this->uri->segment(5) : $_GET["sID"];
        //        $this->selectedPlace = array(
        //            "MdlEmployee" => "userGroup",
        //            "MdlEmployeeCabang" => "userGroup_cabang",
        //            "MdlEmployeeGudang" => "userGroup_gudang",
        //        );
        $this->selectedPlace = array(
            "MdlEmployee"       => "branch",
            "MdlEmployeeCabang" => "branch",
            //            "MdlEmployeeGudang" => "warehouse",
            "MdlEmployeeGudang" => "branch",
        );


    }

    public function index_()
    {
        $ctrlName = $this->uri->segment(2);
        $className = "MdlAccessRight";
        $employeeClassName = "MdlEmployee";
        $this->load->model("Mdls/" . $className);
        $this->load->model("Mdls/$employeeClassName");

        $o = new $className();
        $e = new $employeeClassName();


        $objState = "0";
        $alternateLink = "";
        $title = "Access Rights";
        $e->addFilter("trash='$objState'");
        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $e->addFilter("folders='" . $_GET['fID'] . "'");
        }

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $e->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }


        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";
        }
        else {
            $key = "";
            $subtitle = "Daftar $title";
        }

        $params = array();
        $limit_per_page = 30;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $e->lookupDataCount($key);

        //        cekHijau("$page || $total_records");
        if ($total_records > 0) {
            // get current page records
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $e->setSortby($_GET['sort']);
            }
            $params["results"] = $e->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);

            $config = array(
                'base_url'           => base_url() . "Addons/" . get_class($this) . '/' . __FUNCTION__ . "/",
                'total_rows'         => $total_records,
                'per_page'           => $limit_per_page,
                "uri_segment"        => 4,
                // custom paging configuration
                'num_links'          => 5,
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

        if (sizeof($tmp) > 0) {
            $i = 0;

            $header = $e->getListedFields();
            $header["access"] = "access";
            //            arrPrint($header);
            $listedId = "(";
            foreach ($tmp as $k => $allData) {
                $employee_id = $allData->id;
                $listedId .= "'$employee_id',";

            }
            $listedId = rtrim($listedId, ",");
            $listedId .= ")";

            $o->addFilter("employee_id in $listedId");
            $accessData = $o->lookupAll()->result();
            //            cekHijau($this->db->last_query());
            //            arrPrint($accessData);
            if (sizeof($accessData) > 0) {

            }
            else {

            }
            //            arrPrint($o->getListedFields());
            $arrayItem = array();
            foreach ($tmp as $item) {
                $tmpItem = array();
                foreach ($e->getListedFields() as $ofName => $label) {
                    $employeeID = $item->id;
                    $employeeName = $item->nama_login;
                    $linkUpdate = "";
                    //                if ($this->allowEdit && $objState != "1") {
                    $updateLink = base_url() . "Addons/" . get_class($this) . "/edit/$ctrlName/$employeeID";
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $title',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

                    //                    $updateCommentStr = "Klik untuk mengubah entri";
                    //                } else {
                    //                    $updateCommentStr = "Anda tidak berhak mengubah entri";
                    //                    $editClick = "return false;";
                    //                }
                    $fieldLabel = isset($item->$ofName) ? $item->$ofName : "unknown";
                    $tmpItem[$ofName] = $fieldLabel;
                    $tmpItem['access'] = "<a class='btn btn-default ' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\">custom access</a>";
                }
                $arrayItem[] = $tmpItem;
            }
        }

        $data = array(
            "mode"                => $this->uri->segment(3),
            "errMsg"              => $this->session->errMsg,
            "title"               => "$subtitle" . "",
            "subTitle"            => " $subtitle",
            "strActiveDataTitle"  => "<span class='glyphicon glyphicon-th-list'></span> List of $title",
            "linkStr"             => isset($params['links']) ? $params['links'] : "",
            "arrayHistory"        => $arrayItem,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "alternateLink"       => $alternateLink,
            "thisPage"            => base_url() . "Addons/" . get_class($this) . "/" . $this->uri->segment(3) . "/" . "?trashed=$objState",
            "faddLink"            => isset($faddLink) ? $faddLink : "",
            "feditLink"           => isset($fupdateLink) ? $fupdateLink : "",
            "fdeleteLink"         => isset($fdeleteLink) ? $fdeleteLink : "",
            "fmdlName"            => isset($fmdlName) ? $fmdlName : "",
            "fmdlTarget"          => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
            "header"              => $header,
        );
        $this->load->view('access', $data);

    }

    public function index()
    {

        $ctrlName = $this->uri->segment(2);
        $className = "MdlDataAccessRight";
        $employeeClassName = "MdlEmployeeAll";
        $this->load->model("Mdls/" . $className);
        $this->load->model("Mdls/$employeeClassName");

        $availTranstmp = callAvailTransaction();
        $availTransPlace = availGroupPlace();
        $childPlaceGrup = groupPlaceMaster();
        $childGrup = groupMaster();
        $availTrans = array();
        foreach ($availTranstmp as $tr => $stepTmp) {
            foreach ($stepTmp as $st => $tmpSt) {
                $availTrans[$tr][] = $st;
            }
        }
        // arrPrint($dataModel);
        //        matiHEre();

        $o = new $className();
        $e = new $employeeClassName();


        $objState = "0";
        $alternateLink = "";
        $title = "Access Rights";
        $e->addFilter("trash='$objState'");
        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $e->addFilter("folders='" . $_GET['fID'] . "'");
        }

        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $e->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }


        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";
        }
        else {
            $key = "";
            $subtitle = "Daftar $title";
        }


        $tmp = $e->lookUpAll()->result();
        //        arrPrint($tmp);

        if (sizeof($tmp) > 0) {
            $i = 0;

            //            $header =  $this->selectedFields;
            //            $header[] = "access";
            //            $header[] = "action";
            //            arrPrint($header);
            $header = array(
                //                "nama" =>"nama",
                //                "cabang_nama"=>"branch",
            );
            $extHeader = array(
                "access" => "access",
                //                "action"=>"action"
            );
            $listedId = "(";
            foreach ($tmp as $k => $allData) {
                $employee_id = $allData->id;
                $listedId .= "'$employee_id',";

            }
            $listedId = rtrim($listedId, ",");
            $listedId .= ")";

            $o->addFilter("employee_id in $listedId");
            $accessData = $o->lookupAll()->result();
            //            cekHijau($this->db->last_query());
            //            arrPrint($accessData);
            $listAcess = array();
            if (sizeof($accessData) > 0) {
                foreach ($accessData as $tempAcc) {
                    $listAcess[$tempAcc->employee_id][$tempAcc->menu_category][] = $tempAcc->steps;
                }
            }

            $arrayItem = array();
            if (sizeof($tmp) > 0) {
                //               $selectedField = array("nama","cabang_id","cabang_nama");
                foreach ($tmp as $tmp2) {
                    $tmpD = array();
                    foreach ($this->selectedFields as $col) {
                        $tmpD[$col] = $tmp2->$col;
                    }
                    //                   cekLime($tmp2->cabang_id);
                    if ($tmp2->cabang_id < 0) {
                        $listedTrans = $availTransPlace['center'];
                        //                       matiHEre("ini pusat brooo");
                    }
                    else {
                        $listedTrans = $availTransPlace['branch'];
                    }
                    //                   foreach($childGrup as $mID =>$mChild){
                    //                       arrPrint($mChild);
                    //                   }

                    $arrayItem[$tmp2->id] = $tmpD;
                    $arrayItem[$tmp2->id]['access'] = $listedTrans;
                }
                //               matiHEre();
            }
            //            foreach ($tmp as $item) {
            //                $tmpItem = array();
            //                $this->selectedFields = array("nama","cabang_id","cabang_nama");
            //                foreach ($this->selectedFields as $ofName) {
            //                    $linkUpdate = "";
            ////                if ($this->allowEdit && $objState != "1") {
            //                    $updateLink = base_url() . "Addons/" . get_class($this) . "/edit/$ctrlName/.".$item->id;
            //                    $editClick = "BootstrapDialog.show(
            //                                   {
            //                                        title:'Modify $title',
            ////                                        size: BootstrapDialog.SIZE_WIDE,
            //                                        cssClass: 'edit-dialog',
            //                                        message: $('<div></div>').load('" . $updateLink . "'),
            //                                        draggable:false,
            //                                        closable:true,
            //                                        });";
            //
            //                    $fieldLabel = isset($item->$ofName) ? $item->$ofName : "unknown";
            //                    $tmpItem[$ofName] = $fieldLabel;
            //                    $tmpItem['access'] = "<a class='btn btn-default ' href='JavaScript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\">custom access</a>";
            //                }
            //                $arrayItem[] = $tmpItem;
            //            }
        }
        //        arrPrint($arrayItem);
        //matiHEre();
        $data = array(
            "mode"                => $this->uri->segment(3),
            "errMsg"              => $this->session->errMsg,
            "title"               => "$subtitle" . "",
            "subTitle"            => " $subtitle",
            "strActiveDataTitle"  => "<span class='glyphicon glyphicon-th-list'></span> List of $title",
            "linkStr"             => isset($params['links']) ? $params['links'] : "",
            "arrayHistory"        => $arrayItem,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "alternateLink"       => $alternateLink,
            "thisPage"            => base_url() . "Addons/" . get_class($this) . "/" . $this->uri->segment(3) . "/" . "?trashed=$objState",
            "header"              => $header,
            "extHeader"           => $extHeader,
            "item"                => $arrayItem,
            "access"              => $listAcess,
            "grupAccess"          => $this->config->item("grupAccess"),
            "availTrans"          => $availTrans,
            "childGrup"           => $childPlaceGrup,
            "transJenisAlias"     => transactionJenisAlias(),
            "transStepAlias"      => transactionStepAlias(),
            "linkUpdate"          => base_url() . "Addons/ProsesSelectAcces/select",

        );
        $this->load->view('access', $data);

    }

    public function edit()
    {
//        matiHere(__LINE__);
        $backlink = isset($_GET['backLink']) ? "?backLink=" . $_GET['backLink'] . "&attatch=1" : "";
        $action_link = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/doEdit/" . $this->selectedId . $backlink;
        $className = isset($_GET["ctrl"]) ? $_GET["ctrl"] : "MdlEmployee";
        $dataModel = availMenuConfigData();
        $menuAlias = dataLabel();
        // arrPrint($this->session->login);
        // $otherMEnuConfig= availMenuAdditional("o_holding");//of dulu belum kelar untuk ngeluarin menu laporan
//         arrPrint($dataModel);
        // matiHere();
        $ctrlName = "Mdl" . $this->uri->segment(2);
        $mainMembership = $this->session->login["membership"];
        $selectedId = $this->selectedId;
        $this->load->model("Mdls/$ctrlName");
        $am = new $ctrlName();
        $am->addFilter("employee_id='$selectedId'");
        // cekHitam($className);
        $availStepTemp_0 = $this->selectedPlace[$className];
        $tempMnSelected = $am->lookupAll()->result();//cek ke table set_menu_data
        // cekBiru($this->db->last_query());
        // arrPrint($tempMnSelected);
        $mnSelected = array();
        if (sizeof($tempMnSelected) > 0) {
            foreach ($tempMnSelected as $data) {
                $steps = $data->steps;
                $mnSelected[$data->mdl_name][] = $steps;
            }
        }

        // arrprintPink($mnSelected);
        // arrPrint($this->selectedPlace);
        $content = "";
        $content .= "<script src=\"https://cdn.mayagrahakencana.com/assets/suport/AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js?v=1.0.6-trial\"></script>";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        //        $availGroupData = $this->selectedPlace[$className] == "center" ? $arrGroupAccessRight : $arrGroupAccessRight[$this->selectedPlace[$className]];
        $availGroupData = $dataModel;


        $listed = "<div style='display: none;margin-bottom: 45px;' id=show_parent>";
        $listed .= "<div id='datatmp'></div>";
        // $listed .= "<form name='form1' target='result' method='post' action='$action_link'>";
        $targetLink = base_url() . $this->uri->segment(1) . "/" . get_class() . "/doEdit";
        // arrPrint($dataModel);
        foreach ($dataModel as $mdlName => $placeAvailData) {

            $limitView = $placeAvailData["restriction"];
            $defaultView = sizeof($placeAvailData["default"]) > 0 ? $placeAvailData["default"] : "";
            $allowedDefault = $placeAvailData["allowedGroup"];

            // arrPrintWebs($allowedDefault);
            $showData = false;
            $hideMenu = false;
            if($limitView){
                foreach ($allowedDefault as $ii => $alowedDef) {
                    if (in_array($alowedDef, $mainMembership)) {
                        $showData = true;
                    }else{
                        $hideMenu = true;
                    }
                }
            }
            else{

            }

            $prevDataCek = isset($mnSelected[$mdlName]) ? $mnSelected[$mdlName] : array();


            // arrprint($menuAlias);
            if($showData){
                $listed .= "<div class='text-center col-sm-4 col-md-4 col-lg-4' style='margin-bottom: 4px;padding: 2px!important;' >";
                $listed .= "<div style='min-height: -webkit-fill-available;' class='panel panel-info'>";
                $listed .= "<div class='panel-heading text-bold text-capitalize'><span >" . $placeAvailData["label"] . "</span></div>";
                $listed .= "<div class='panel-body'>";
                $listed .= "<div class='funkyradio'>";
                $ix = 0;
                $emID = $_GET['sID'];
                foreach ($menuAlias as $key => $alias) {
                    if($key ==$defaultView){
                        $ix++;
                        $ids = $ix;
                        $xID = $mdlName . "_" . $ix;
                        $valuestmp = array("mdlName" => $mdlName, "key" => $key, "emID" => $_GET['sID']);
                        $values = blobEncode($valuestmp);
                        $checked = sizeof($prevDataCek) > 0 && in_array($key, $prevDataCek) ? "checked" : "";
                        $listed .= "<div class='funkyradio-success' style='padding-bottom: 5px;'>
                                         
                                       <input type='checkbox' $checked class='tmp_data' name='data_type_child[$xID]'  id='checkbox_$xID'  onclick=\"$('#datatmp').load('$targetLink?mdlName=$mdlName&key=$key&emID=$emID&state='+this.checked);\" >
                                        <label for='checkbox_$xID' class='no-margin no-padding text-capitalize text-left' title=''>$alias</label>
                                    </div>";
                    }

                }

                $listed .= "</div>";
                $listed .= "</div>";
                $listed .= "</div>";
                $listed .= "</div>";
            }
            else{
                if($hideMenu){

                }
                else{
                    $listed .= "<div class='text-center col-sm-4 col-md-4 col-lg-4' style='margin-bottom: 4px;padding: 2px!important;' >";
                    $listed .= "<div style='min-height: -webkit-fill-available;' class='panel panel-info'>";
                    $listed .= "<div class='panel-heading text-bold text-capitalize n-ppost'><span >" . $placeAvailData["label"] . "</span></div>";
                    if(isset($placeAvailData["aliasLabel"])){
                        $listed .= "<div class='text-capiltalize n-ppost-name text-danger text-bold blink' style='font-size: medium;'>".$placeAvailData["aliasLabel"]."</div>";
                    }
                    $listed .= "<div class='panel-body'>";
                    $listed .= "<div class='funkyradio'>";
                    $ix = 0;
                    $emID = $_GET['sID'];
                    foreach ($menuAlias as $key => $alias) {
                        $ix++;
                        $ids = $ix;
                        $xID = $mdlName . "_" . $ix;
                        $valuestmp = array("mdlName" => $mdlName, "key" => $key, "emID" => $_GET['sID']);
                        $values = blobEncode($valuestmp);
                        $checked = sizeof($prevDataCek) > 0 && in_array($key, $prevDataCek) ? "checked" : "";
                        $listed .= "<div class='funkyradio-success' style='padding-bottom: 5px;'>
                                         
                                       <input type='checkbox' $checked class='tmp_data' name='data_type_child[$xID]'  id='checkbox_$xID'  onclick=\"$('#datatmp').load('$targetLink?mdlName=$mdlName&key=$key&emID=$emID&state='+this.checked);\" >
                                        <label for='checkbox_$xID' class='no-margin no-padding text-capitalize text-left' title=''>$alias</label>
                                    </div>";
                    }

                    $listed .= "</div>";
                    $listed .= "</div>";
                    $listed .= "</div>";
                    $listed .= "</div>";
                }

            }


        }

        // matiHEre(__LINE__);
        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Edit $title", "application/template/lte/index.html");
        $content .= "<div class='panel-body' id='show_id'>";
        //        $content .= "<div class='row panel panel-default' style='background:#f0f0f0;'>";

        //        $content .= "<div style='padding: 5px;' class='col-lg-12 col-md-12 col-sm-12'>";

        $content .= "<input onclick='show_parent();changetext();' type='button' value='Customize access rights' id='myButton1' class='btn btn-warning'></input>";

        //        $content .= "<div class='clearfix'>&nbsp;</div>";

        $content .= $listed;
        $content .= "<script>

        function show_parent() {
                      var x = document.getElementById(\"show_parent\");
                      if (x.style.display === \"none\") {
                        x.style.display = \"block\";
                      } else {
                        x.style.display = \"none\";
                      }
                    }

        show_parent();

        function changetext() {
                 var elem = document.getElementById(\"myButton1\");
                if (elem.value==\"Customize access rights\")
                    { 
                        elem.value = \"Hide Customize\";
                    }
                else
                 {
                     elem.value = \"Customize access rights\";
                 }
            }
changetext();
                        if (parent) {
                            var oHead = document.getElementsByTagName(\"head\")[0];
                            var arrStyleSheets = parent.document.getElementsByTagName(\"style\");
                            for (var i = 0; i < arrStyleSheets.length; i++)
                                oHead.appendChild(arrStyleSheets[i].cloneNode(true));
                        }
                        top.$('head>link[rel=stylesheet]').each(function(){
                            var cssLink = document.createElement('link');
                            cssLink.href = top.$(this).attr('href');
                            cssLink .rel = 'stylesheet';
                            cssLink .type = 'text/css';
                            document.body.appendChild(cssLink);
                        });

                    </script>";

        //        $content .= "</div class='col-lg-12 col-md-12 col-sm-12'>";
        //        $content .= "</div class='row'>";
        $content .= "</div class='panel-body'>";


        $data = array(
            "mode"     => $this->uri->segment(2),
            "title"    => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content"  => $content,
        );
        echo $content;
    }

    public function doEdit()
    {


        // arrPrint($_GET);
        // matiHere("ennnd");


        $selectedEmployee = $_GET["emID"];
        $mdl_access = $_GET["mdlName"];
        $access = $_GET["key"];
        $state = $_GET["state"];
        $this->load->helpers('he_access_right');
        $this->load->model("Mdls/MdlDataAccessRight");
        $m = new MdlDataAccessRight();

        $m->addFilter("employee_id='$selectedEmployee'");
        $m->addFilter("mdl_name='$mdl_access'");
        $m->addFilter("steps='$access'");

        $existMenu = $m->lookupAll();
        $this->db->trans_start();
        // cekHitam($this->db->last_query());

        switch ($state) {
            case "true":
                $insertData = array(
                    "employee_id" => $selectedEmployee,
                    "mdl_name"    => $mdl_access,
                    "steps"       => $access,
                    "status"      => "1",
                    "trash"       => "0",
                    "oleh_id"     => $this->session->login['id'],
                    "oleh_nama"   => $this->session->login['nama'],
                );
                $m->setFilters(array());
                $m->addData($insertData) or matiHEre("Upss, gagal memberikan hak akses, silahkan coba sekali lagi");
                // ceklIme($this->db->last_query());
                break;
            case "false":
                //cabut hak akses set trash 1
                $where = array(
                    "employee_id" => $selectedEmployee,
                    "mdl_name"    => $mdl_access,
                    "steps"       => $access,
                );
                $udpate = array(
                    "status"          => "0",
                    "trash"           => "1",
                    "trash_oleh_id"   => $this->session->login['id'],
                    "trash_oleh_nama" => $this->session->login['nama'],
                    "trash_dtime"     => dtimeNow("Y-m-d H:i"),
                );
                // arrPrint($udpate);
                $m->setFilters(array());
                $m->updateData($where, $udpate) or matiHere("UUPS, Gagl mencabut hak akses silahkan coba beberapa saat lagi, jika masih berlanjut hubungi Web  master untuk melakukan pengecekan");
                // cekHitam($this->db->last_query());
                // $m->addFilter("mdl_name='$mdl_access'");
                // $m->addFilter("steps='$access'");
                // $m->addFilter("steps='$access'");
                break;
        }


        // matiHere("hoppp");
        $this->db->trans_complete();
        // if (isset($_GET['attached']) && $_GET['attached'] == '1') {
        //
        //     $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(                                   {
        //                                title:'Modify entry..',
        //                                 message: " . '$' . "('<div></div>').load('" . $backLink . "'),
        //                                 draggable:false,
        //                                 size:top.BootstrapDialog.SIZE_WIDE,
        //                                 closable:true,
        //                                 }
        //                                 );";
        //
        //     echo "<html>";
        //     echo "<head>";
        //     echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //     echo "</head>";
        //     echo "<body onload=\"$actionTarget\">";
        //     echo "</body>";
        //
        // }
        // else {
        //     $arrAlert = array(
        //         "type"              => "success",
        //         "title"             => "Access rights saved",
        //         //                        "html" => "your order has been saved and ready to process",
        //         "html"              => $this->session->errMsg,
        //         "timer"             => "1500",
        //         "showConfirmButton" => false,
        //         "allowOutsideClick" => false,
        //     );
        //     echo swalAlert($arrAlert);
        //     echo "<script>top.location.reload(3000);</script>";
        // }

        //        echo topReload();
        //        echo "</script>";

    }


}