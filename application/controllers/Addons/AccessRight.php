<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccessRight extends CI_Controller
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
            "MdlEmployee" => "center",
            "MdlEmployeeCabang" => "branch",
            "MdlEmployeeKirim" => "branch",
            "MdlEmployeeGudang" => "center",
//            "MdlEmployeeGudang" => "branch",
            "MdlEmployeeGudangFase" => "branch",
        );

        $this->selectedPlaceException = array(
            // "MdlEmployeeGudang",
        );
    }

    public function index()
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
                'base_url' => base_url() . "Addons/" . get_class($this) . '/' . __FUNCTION__ . "/",
                'total_rows' => $total_records,
                'per_page' => $limit_per_page,
                "uri_segment" => 4,
                // custom paging configuration
                'num_links' => 5,
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
                    $tmpItem['access'] = "<a class='btn btn-default ' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\">custom access</a>";
                }
                $arrayItem[] = $tmpItem;
            }
        }

        $data = array(
            "mode" => $this->uri->segment(3),
            "errMsg" => $this->session->errMsg,
            "title" => "$subtitle" . "",
            "subTitle" => " $subtitle",
            "strActiveDataTitle" => "<span class='glyphicon glyphicon-th-list'></span> List of $title",
            "linkStr" => isset($params['links']) ? $params['links'] : "",
            "arrayHistory" => $arrayItem,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "alternateLink" => $alternateLink,
            "thisPage" => base_url() . "Addons/" . get_class($this) . "/" . $this->uri->segment(3) . "/" . "?trashed=$objState",
            "faddLink" => isset($faddLink) ? $faddLink : "",
            "feditLink" => isset($fupdateLink) ? $fupdateLink : "",
            "fdeleteLink" => isset($fdeleteLink) ? $fdeleteLink : "",
            "fmdlName" => isset($fmdlName) ? $fmdlName : "",
            "fmdlTarget" => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
            "header" => $header,
        );
        $this->load->view('access', $data);

    }

    public function edit()
    {
        $backlink = isset($_GET['backLink']) ? "?backLink=" . $_GET['backLink'] . "&attatch=1" : "";
        $action_link = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "/doEdit/" . $this->selectedId . $backlink;
        $className = isset($_GET["ctrl"]) ? $_GET["ctrl"] : "MdlEmployee";
//        arrPrintPink($_GET);
        $ctrlName = "Mdl" . $this->uri->segment(2);
//        $this->load->helper('he_access_right');
        $arrGroupAccessRight = availGroupAccess();
        $transactionLabel = transactionStepAlias();
        $parentLabel = transactionJenisAlias();
        $subPlaceStep = subPlaceStep();
        $factoryAccessRight = factoryAccess();
        $stepAliasing = menuTitleAliasing();
//arrPrint($stepAliasing);
//matiHere();
        $selectedId = $this->selectedId;

//        cekHere("cetak transaksi step alias");
//        arrPrint($transactionLabel);
//        cekHere("cetak jenis alias");
//        arrPrint($parentLabel);
//        cekHere("cetak subplace");
//        arrPrint($subPlaceStep);
//        arrPrint($factoryAccessRight);


        $this->load->model("Mdls/$ctrlName");

//        cekMerah("selected ID: $selectedId :: $className :: $ctrlName ::");

        //------------------------------------------
            $this->load->model("Mdls/$className");

            $em = New $className();
            $em->setFilters(array());
            $em->addFilter("id='$selectedId'");
            $emTmp = $em->lookupAll()->result();
        if (in_array($className, $this->selectedPlaceException)) {
//            arrPrint($emTmp);
            $cabangID = $emTmp[0]->cabang_id;
            $gudangID = $emTmp[0]->gudang_id;
//            cekHitam("== $cabangID ==");

            if ($cabangID > 0) {
                // pasti cabang/branch
                $this->selectedPlace["MdlEmployeeGudang"] = "branch";
            }
            else {
                // pasti pusat/center
                $this->selectedPlace["MdlEmployeeGudang"] = "center";
            }

            if ($gudangID > 0) {
                // $subplace = "warehouse_ng";
                $subplace = "warehouse";
            }
            else {
                $subplace = "warehouse";
            }
        }
        else {
//            cekHitam("HAHAHA");
            $subplace = NULL;
        }
//        cekKuning("=== subplace: $subplace ===");

        $transaksiUI = $this->config->item("heTransaksi_ui");


        $am = new $ctrlName();

        $am->addFilter("employee_id='$selectedId'");
        $availStepTemp_0 = $this->selectedPlace[$className];

        $tempMnSelected = $am->lookupAll()->result();//cek ke table set_menu

        $mnSelected = array();
        if (sizeof($tempMnSelected) > 0) {
            foreach ($tempMnSelected as $data) {
                $steps = $data->steps;
                $mnSelected[$data->menu_category][] = $steps;
            }
        }


        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $availGroupData = $arrGroupAccessRight;

        $availTrans = array();
        $blacklisted = availCreatorCenter("center");
        if ($this->selectedPlace[$className] == "center") {
            foreach ($availGroupData as $place => $placeData) {
                foreach ($placeData as $jnParent => $allowStep) {
                    if ($subplace != NULL) {

                    }
                    else {

                        $temp = array();
                        foreach ($allowStep as $step => $memberGroub) {
                            $temp[$step] = $transactionLabel[$jnParent][$step];
                            $availTrans[$place][$jnParent] = $temp;
                        }

                    }


                }
            }
        }
        else {

            foreach ($availGroupData[$this->selectedPlace[$className]] as $jnParent => $allowStep) {
                if ($subplace != NULL) {
                    $temp = array();
                    foreach ($allowStep as $step => $memberGroub) {
                        if (isset($subPlaceStep) && sizeof($subPlaceStep) > 0) {
                            $subplaceTrans = isset($subPlaceStep[$jnParent][$step]) ? $subPlaceStep[$jnParent][$step] : NULL;
                            if ($subplaceTrans == $subplace) {
                                $temp[$step] = $transactionLabel[$jnParent][$step];
                            }
                        }
                    }
                    $availTrans[$this->selectedPlace[$className]][$jnParent] = $temp;
                }
                else {
//                    arrPrintWebs($subPlaceStep);
                    $temp = array();
                    foreach ($allowStep as $step => $memberGroub) {
                        if (array_key_exists($jnParent, $subPlaceStep)) {
                            if ($subPlaceStep[$jnParent][$step] != "warehouse_ng") {
                                $temp[$step] = $transactionLabel[$jnParent][$step];
                            }
                        }
                        else {
                            $temp[$step] = $transactionLabel[$jnParent][$step];
                        }
                    }
                    $availTrans[$this->selectedPlace[$className]][$jnParent] = $temp;
                }


            }
        }
//         arrPrint($availTrans);
// matiHere($this->selectedPlace[$className]);
        $arrAvailNew = array();
        if ($this->selectedPlace[$className] == "center") {

            foreach ($availTrans as $place => $temp) {
                foreach ($temp as $jn => $stepAvails) {
                    if (isset($blacklisted[$jn])) {
                        $tempRemoved = array_diff_key($stepAvails, $blacklisted[$jn]);
                    }
                    else {
                        $tempRemoved = $stepAvails;
                    }
                    if (sizeof($tempRemoved) > 0) {
                        $arrAvailNew[$place][$jn] = $tempRemoved;
                    }
                }

            }

        }
        else {
//            cekBiru("======");
//            arrPrintWebs($availTrans);
            $this->load->model("Mdls/MdlCabang");
            $cb = New MdlCabang();
            $cb->addFilter("id='" . $emTmp[0]->cabang_id . "'");
            $cbTmp = $cb->lookupAll()->result();
            if ($cbTmp[0]->tipe != "produksi") {

                foreach ($availTrans as $placeNama => $spec) {
                    foreach ($spec as $jenisTr => $trSpec) {
                        if (array_key_exists($jenisTr, $factoryAccessRight[$placeNama])) {
//                            cekHitam(":: $jenisTr ::");
                            $availTrans[$placeNama][$jenisTr] = null;
                            unset($availTrans[$placeNama][$jenisTr]);
                        }
                    }
                }

            }
            $arrAvailNew = $availTrans;
        }

//         arrPrint($arrAvailNew);
        // matiHEre();

        $arrKey = array();
        foreach ($arrAvailNew as $place => $temp) {
            foreach ($temp as $key => $tempAvail) {
                $arrKey[$place][] = $key;
            }

        }

        $arrRow = array();
        foreach ($arrAvailNew as $plID => $placeData) {
            $jml_kolom = "3";
            $isi_array = sizeof($placeData);
            $jml_baris = ceil($isi_array / $jml_kolom);
            $max_baris_perkolom = floor($isi_array / $jml_kolom);
            $sisa_baris_ = $isi_array % $jml_kolom;
            $row = 0;
            $y2 = 0;

            for ($br = 1; $br <= $jml_baris; $br++) {
                $yy = -1 + $y2;
                $test = array();
                for ($x = 1; $x <= 3; $x++) {
                    $yy++;
                    if (isset($arrKey[$plID][$yy])) {
                        $test[] = array(
                            "id" => $arrKey[$plID][$yy]
                        );
                    }
                    $y2++;
                }
                $arrRow[$plID][$br] = $test;
            }
        }

        $tempAvail = array();
        foreach ($arrAvailNew as $place => $placeDataTemp) {
            foreach ($placeDataTemp as $jnTmp => $stepTmpVal) {
                if (sizeof($stepTmpVal) > 0) {
                    $tempAvail[$jnTmp] = $stepTmpVal;
                }
            }
        }
        //endregion

//arrPrint($tempAvail);
//        matiHere(__LINE__);

        // region hak akses manufactur
        $cab_id = $emTmp[0]->cabang_id;
        cekUngu("cabangID: $cab_id, employeeID: $selectedId");
        $menuManufactur = produksiMenu_he_menu($cab_id);
//        showLast_query("kuning");
//        arrPrintPink($anu);

        $ctrlNameManufactur = $ctrlName . "Manufactur";
        $this->load->model("Mdls/$ctrlNameManufactur");
        $am_manufactur = new $ctrlNameManufactur();
        $am_manufactur->addFilter("employee_id='$selectedId'");
        $tempMnSelectedManufactur = $am_manufactur->lookupAll()->result();//cek ke table set_menu
        showLast_query("biru");
        $mnSelectedManufactur = array();
        if (sizeof($tempMnSelectedManufactur) > 0) {
            foreach ($tempMnSelectedManufactur as $data) {
                $steps = $data->steps;
                $mnSelectedManufactur[$data->menu_category][] = $steps;
            }
        }


        // endregion hak akses manufactur


//        arrPrintKuning($arrAvailNew);
        $listed = "<div style='display: none;margin-bottom:45px;border:4px solid red;' id=show_parent>";
        $listed .= "<form name='form1' target='result' method='post' action='$action_link'>";
        foreach ($arrAvailNew as $place => $placeAvail) {
            $listed .= "<div class='panel panel-danger'>";
            $listed .= "<div class='panel-heading text-bold' >$place</div>";
            foreach ($arrRow[$place] as $k => $tmpX) {
//                arrPrintPink($tmpX);
                $listed .= "<div class='row row-eq-height' style='margin-right: 5px; margin-left: 5px;'>";
                foreach (array_filter($tmpX) as $y => $x) {
                    $xID = $x['id'];
                    $label = isset($tempAvail[$xID]) ? $parentLabel[$xID] : "";
//                    $tempAvail = isset($transactionLabel[$xID]) ? $transactionLabel[$xID] : array();
                    if ($xID > 0) {
//                    $listed .= "<td>";
                        if (isset($tempAvail[$xID])) {
                            $xidLabel =$stepAliasing[$xID];
                            $listed .= "<div class='text-center col-xs-12 col-sm-4 col-md-4 col-lg-4' style='margin-bottom: 4px;padding: 2px!important;' >";
                            $listed .= "<div style='min-height: -webkit-fill-available;' class='panel panel-info'>";
                            $listed .= "<div class='panel-heading text-bold text-capitalize' title='$xidLabel'><span >$label</span></div>";
                            $listed .= "<div class='panel-body'>";
                            $listed .= "<div class='funkyradio'>";
                            foreach ($tempAvail[$xID] as $steps => $labelSteps) {

                                $ids = $xID . "" . $steps;
                                $val = $steps;
                                if (isset($mnSelected[$xID])) {
                                    $checkedStep = in_array($val, $mnSelected[$xID]) ? "checked" : "";
                                }
                                else {
                                    $checkedStep = "";
                                }

                                $valX[$xID] = $val;
                                $listed .= "<div class='funkyradio-success' style='padding-bottom: 5px;'>
                                    <input type='checkbox' name='acc_type_child[$xID][]' id='checkbox_$ids' value='$val' $checkedStep/>
                                    <label for='checkbox_$ids' class='no-margin no-padding text-capitalize text-left' title='$labelSteps'>$labelSteps</label>
                                </div>";

                                $listed .= "<input type='hidden' name='step_child_label[$ids]' value='$labelSteps'>";
                            }
                            $listed .= "</div>";
                            $listed .= "</div>";
                            $listed .= "</div>";
                            $listed .= "</div>";
                        }
//                    $listed .= "</td>";
                    }
                }
                $listed .= "</div>";
            }
            $listed .= "</div>";
        }

//        arrPrintHijau($menuManufactur);
        if (sizeof($menuManufactur) > 0) {
            foreach ($menuManufactur['produk'] as $menuSpec) {
                $produk_id = $menuSpec['id'];
                $produk_nama = $menuSpec['nama'];

                $listed .= "<div class='panel panel-danger'>";
                $listed .= "<div class='panel-heading text-bold' >$produk_nama</div>";
                foreach ($menuManufactur['menu_fase'][$produk_id] as $stepcode => $mSpec) {
                    $stepcode_label = $menuManufactur['menu_fase_label'][$produk_id][$stepcode]['aktivitas'];

                    $listed .= "<div class='text-center col-xs-12 col-sm-4 col-md-4 col-lg-4' style='margin-bottom: 4px;padding: 2px!important;' >";
                    $listed .= "<div style='min-height: -webkit-fill-available;' class='panel panel-info'>";
                    $listed .= "<div class='panel-heading text-bold text-capitalize'><span >$stepcode_label</span></div>";
                    $listed .= "<div class='panel-body'>";
                    $listed .= "<div class='funkyradio'>";
//                    cekKuning(":: $stepcode ::");
//                    arrPrintKuning($mSpec);
                    foreach ($mSpec as $faseID => $faseNama) {
//                        $ids = $xID . "" . $steps;
//                        $val = $steps;
//                        $valX[$xID] = $val;
//
                        $ids = $produk_id . "" . $faseID;
                        $val = $faseID;
                        $xID = $produk_id;
                        $labelSteps = $faseNama;

                        if (isset($mnSelectedManufactur[$xID])) {
                            $checkedStep = in_array($val, $mnSelectedManufactur[$xID]) ? "checked" : "";
                        }
                        else {
                            $checkedStep = "";
                        }
                        $listed .= "<div class='funkyradio-success' style='padding-bottom: 5px;'>
                                    <input type='checkbox' name='mn_acc_type_child[$xID][]' id='checkbox_$ids' value='$val' $checkedStep/>
                                    <label for='checkbox_$ids' class='no-margin no-padding text-capitalize text-left' title='$labelSteps'>$labelSteps</label>
                                </div>";

                        $listed .= "<input type='hidden' name='mn_step_child_label[$ids]' value='$labelSteps'>";
                        $listed .= "<input type='hidden' name='mn_step_child_code[$ids]' value='$stepcode'>";
                        $listed .= "<input type='hidden' name='mn_step_child_code_master[$produk_id]' value='$produk_nama'>";
                    }
                    $listed .= "</div>";
                    $listed .= "</div>";
            $listed .= "</div>";
                    $listed .= "</div>";
                }

                $listed .= "</div class='panel panel-danger'>";
            }
        }


        //region tombol simpan hak akses
        $listed .= "<div class='row' style='margin-top: 10px;'></div>";
        $listed .= "<input type='hidden' name='class_name' value='$className'>";
        $listed .= " <div class='modal-footer' style='margin-top: 0px; bottom: 0px; position: fixed; width: 100%; right: 3px; background: white;'>
                        <div class='row'>
                            <span class='col-xs-12 col-sm-9'>
                                <div style='margin-bottom: 3px !important;padding: 7px!important;' class='alert bg-warning text-bold text-red text-center'>JANGAN LUPA DI SAVE YA....
                                    <i class='hidden-lg glyphicon glyphicon-arrow-down pull-right blink'></i>
                                    <i class='hidden-lg glyphicon glyphicon-arrow-down pull-right blink'></i>
                                    <i class='hidden-lg glyphicon glyphicon-arrow-down pull-right blink'></i>
                                    <i class='hidden-xs glyphicon glyphicon-arrow-right pull-right blink'></i>
                                </div>
                            </span>
                            <span class='col-xs-12 col-sm-3'>
                                <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                                <button type='submit' class='btn btn-primary'>Save changes</button>
                            </span>
                        </div>
                    </div>";
        $listed .= "</div>";
        //endregion


        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Edit $title", "application/template/lte/index.html");
        $content .= "<div class='panel-body' style='border:3px solid blue;' id='show_id'>";

        $content .= "<input style='margin-bottom: 5px;' onclick='show_parent();changetext();' type='button' value='Customize access rights' id='myButton1' class='btn btn-warning'></input>";


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

        $content .= "</div class='panel-body'>";

        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content" => $content,
        );
        echo $content;
    }

    public function doEdit()
    {
        $groupBName = isset($_POST["group_name"]) ? blobDecode($_POST["group_name"]) : array();
        $transaksiUI = $this->config->item("heTransaksi_ui");
        $selectedEmployee = $this->uri->segment(4);
        $ctrlName = $this->uri->segment(2);
        $className = isset($_POST["class_name"]) ? $_POST["class_name"] : "";

        $this->load->helpers('he_access_right');
        $this->load->model("Mdls/MdlAccessRight");
        $this->load->model("Mdls/MdlAccessRightManufactur");
        $m = new MdlAccessRight();
        $mmf = new MdlAccessRightManufactur();

        //region transaksional
        $m->addFilter("employee_id='$selectedEmployee'");
        $existMenu = $m->lookupAll();

        $hak_akses_lama = $existMenu->result();
        //endregion

        //region transaksional
        $mmf->addFilter("employee_id='$selectedEmployee'");
        $existMenuManufactur = $mmf->lookupAll();
        $hak_akses_lama_manufactur = $existMenuManufactur->result();
        //endregion


        $this->db->trans_start();


        if (sizeof($existMenu) > 0) {
            $where = "employee_id='$selectedEmployee'";
            $m->deleteData($where);
//            cekMerah($this->db->last_query());
        }

        if (sizeof($existMenuManufactur) > 0) {
            $where = "employee_id='$selectedEmployee'";
            $mmf->deleteData($where);
//            cekMerah($this->db->last_query());
        }


        $selectedData = isset($_POST["acc_type_child"]) ? $_POST["acc_type_child"] : array();
        $availTrans = callAvailTransaction();
        if (sizeof($selectedData) > 0) {
            foreach ($selectedData as $mnCategory => $selectedChild) {
                foreach ($selectedChild as $acc_steps) {
                    $xlabel = $mnCategory . "" . $acc_steps;
                    $acc_label = isset($_POST["step_child_label"][$xlabel]) ? $_POST["step_child_label"][$xlabel] : "";
                    $tr_label = $transaksiUI[$mnCategory]["label"];
                    $group_name = isset($selectedGroupName[$mnCategory]) ? $selectedGroupName[$mnCategory] : "";
                    $dataChild = array(
                        "menu_category" => $mnCategory,
                        "menu_label" => $tr_label,
                        "employee_id" => $selectedEmployee,
                        "author" => $this->session->login['id'],
                        "steps" => $acc_steps,
                        "steps_code" => $availTrans[$mnCategory][$acc_steps]['target'],
                        "steps_label" => $acc_label,
                    );
                    arrPrintHijau($dataChild);
                    $insertID = $m->addData($dataChild, $m->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                    cekHijau($this->db->last_query());
                    $this->session->errMsg = "Data contents have been saved";
                }
                }
            }


        //region manufactur
        $selectedData_mn = isset($_POST["mn_acc_type_child"]) ? $_POST["mn_acc_type_child"] : array();
        $selectedData_mn_label = isset($_POST["mn_step_child_label"]) ? $_POST["mn_step_child_label"] : array();
        $selectedData_mn_code = isset($_POST["mn_step_child_code"]) ? $_POST["mn_step_child_code"] : array();
        $selectedData_mn_master = isset($_POST["mn_step_child_code_master"]) ? $_POST["mn_step_child_code_master"] : array();
        if (sizeof($selectedData_mn) > 0) {
            foreach ($selectedData_mn as $mnCategory => $selectedChild) {
                foreach ($selectedChild as $acc_steps) {
                    $mn_key = $mnCategory . "" . $acc_steps;
                    $dataChild = array(
                        "menu_category" => $mnCategory,
                        "menu_label" => isset($selectedData_mn_master[$mnCategory]) ? $selectedData_mn_master[$mnCategory] : "",
                        "employee_id" => $selectedEmployee,
                        "author" => $this->session->login['id'],
                        "steps" => $acc_steps,
                        "steps_code" => isset($selectedData_mn_code[$mn_key]) ? $selectedData_mn_code[$mn_key] : "",
                        "steps_label" => isset($selectedData_mn_label[$mn_key]) ? $selectedData_mn_label[$mn_key] : "",
                    );
                    arrPrintKuning($dataChild);
                    $insertID = $mmf->addData($dataChild, $mmf->getTableName()) or die(lgShowError("Gagal menulis data* ", __FILE__));
                    cekHijau($this->db->last_query());
                }
            }
        }
        //endregion


        // region menulis ke tabel set_menu__history...
            $m = new MdlAccessRight();
            $m->addFilter("employee_id='$selectedEmployee'");
            $existMenu = $m->lookupAll();
            $hak_akses_baru = $existMenu->result();

            $hak_akses_lama_blob = blobEncode($hak_akses_lama);
            $hak_akses_baru_blob = blobEncode($hak_akses_baru);


            $mdlHist = "MdlAccessRightHistory";
            $this->load->model("Mdls/$mdlHist");
            $m = new $mdlHist();
            $arrHistory = array(
                "orig_id" => $selectedEmployee,
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
                "mdl_name" => $mdlHist,
                "old_content" => $hak_akses_lama_blob,
                "new_content" => $hak_akses_baru_blob,
            );
            $insertID = $m->addData($arrHistory, $m->getTableName()) or die(lgShowError("Gagal menulis history hak akses transaksi ", __FILE__));
//            showLast_query("hijau");
        // endregion


        //region menulis ke tabel histiry hak akses manufactur
        $mmf = new MdlAccessRightManufactur();
        $mmf->addFilter("employee_id='$selectedEmployee'");
        $existMenuManufactur = $mmf->lookupAll();
        $hak_akses_baru_manufactur = $existMenuManufactur->result();
        $hak_akses_lama_blob_manufactur = blobEncode($hak_akses_lama_manufactur);
        $hak_akses_baru_blob_manufactur = blobEncode($hak_akses_baru_manufactur);

        $mdlHist = "MdlAccessRightManufacturHistory";
        $this->load->model("Mdls/$mdlHist");
        $mhmf = new $mdlHist();
        $arrHistory = array(
            "orig_id" => $selectedEmployee,
            "oleh_id" => $this->session->login['id'],
            "oleh_name" => $this->session->login['nama'],
            "mdl_name" => $mdlHist,
            "old_content" => $hak_akses_lama_blob_manufactur,
            "new_content" => $hak_akses_baru_blob_manufactur,
        );
        $insertID = $mhmf->addData($arrHistory, $mhmf->getTableName()) or die(lgShowError("Gagal menulis history hak akses transaksi ", __FILE__));
//            showLast_query("hijau");
        //endregion

//        mati_disini(":: under maintenance ::");

        $this->db->trans_complete();
        if (isset($_GET['attached']) && $_GET['attached'] == '1') {

            $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(                                   {
                                       title:'Modify entry..',
                                        message: " . '$' . "('<div></div>').load('" . $backLink . "'),
                                        draggable:false,
                                        size:top.BootstrapDialog.SIZE_WIDE,                                        
                                        closable:true,
                                        }
                                        );";

            echo "<html>";
            echo "<head>";
            echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
            echo "</head>";
            echo "<body onload=\"$actionTarget\">";
            echo "</body>";

        }
        else {
            $arrAlert = array(
                "type" => "success",
                "title" => "Access rights saved",
                //                        "html" => "your order has been saved and ready to process",
                "html" => $this->session->errMsg,
                "timer" => "1500",
                "showConfirmButton" => false,
                "allowOutsideClick" => false,
            );
            echo swalAlert($arrAlert);
            echo "<script>top.location.reload(3000);</script>";
        }

    }


}