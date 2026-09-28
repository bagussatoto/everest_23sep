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
        $this->selectedId = isset($_GET["attached"]) == "0" ? $this->uri->segment(5) : $_GET["sID"];
        $this->selectedPlace = array(
            "MdlEmployee" => "userGroup",
            "MdlEmployeeCabang" => "userGroup_cabang",
            "MdlEmployeeGudang" => "userGroup_gudang",
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
        } else {
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

            } else {

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
        $ctrlName = "Mdl" . $this->uri->segment(2);
        $selectedId = $this->selectedId;

        $transaksiUI = $this->config->item("heTransaksi_ui");

        $this->load->model("Mdls/$ctrlName");

        $am = new $ctrlName();

        $am->addFilter("employee_id='$selectedId'");

        $selectedGroup = $this->selectedPlace[$className];

        $tempMnSelected = $am->lookupAll()->result();

        $mnSelected = array();
        foreach ($tempMnSelected as $data) {
            $steps = $data->steps;
            $mnSelected[] = $steps;
//            arrPrint($data);
        }

        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }

        $availStepTemp = array();
        foreach ($transaksiUI as $jenis => $details) {
            $steps = $details["steps"];
            $parentLabels = $details["label"];
            $tempAvail = array();
            foreach ($steps as $steps => $stepDetails) {
                $steps_label = $stepDetails["label"];
                $access_group = $stepDetails["userGroup"];
                $tempAvail[$steps] = $steps_label;

            }
            $availStepTemp[$access_group][$jenis] = $tempAvail;
        }

        $availMember = $this->config->item($selectedGroup);
        $availMenu = array();
        $availGroupData = array();
        foreach ($availMember as $mId => $mLabel) {
            if (isset($availStepTemp[$mId])) {
                foreach ($availStepTemp[$mId] as $jenis => $dataTemp) {
                    $availMenu[$jenis] = $dataTemp;
                    $availGroupData[$jenis] = $mId;
                }
            }
        }
        $availGroupData_f = blobEncode($availGroupData);
        $jml_kolom = "3";
        $isi_array = sizeof($availMenu);
        $jml_baris = ceil($isi_array / $jml_kolom);
        $max_baris_perkolom = floor($isi_array / $jml_kolom);
        $sisa_baris_ = $isi_array % $jml_kolom;
        $row = 0;
        $arrRow = array();
        $arrKey = array();
        foreach ($availMenu as $key => $temp) {
            $arrKey[] = $key;
        }
        $y2 = 0;
        for ($br = 1; $br <= $jml_baris; $br++) {
            $yy = -1 + $y2;
            $test = array();
            for ($x = 1; $x <= 3; $x++) {
                $yy++;
//                cekHitam("$yy, $br");
                $test[] = array(
                    "id" => isset($arrKey[$yy]) ? $arrKey[$yy] : null,
                );
                $y2++;
            }
            $arrRow[$br] = $test;
        }

        $listed = "<div style='display: none;' id=show_parent>";
        $listed .= "<form name='form1' target='result' method='post' action='$action_link'>";
        $listed .= "<table class='table table-borderless' cellspacing='0' cellpadding='0'>";
        foreach ($arrRow as $k => $tmpX) {
            $listed .= "<div class='col-md-4 col-xs-4' style='padding: 2px;display:nonfe;' >";
            $listed .= "<tr>";
            foreach ($tmpX as $y => $x) {
                $xID = $x['id'];
                $label = isset($transaksiUI[$xID]["label"]) ? $transaksiUI[$xID]["label"] : "";
                $tempAvail = isset($availMenu[$xID]) ? $availMenu[$xID] : array();
                $arrAvailGroup = isset($transaksiUI[$xID]["steps"]) ? $transaksiUI[$xID]["steps"] : array();
//                arrPrint($arrAvailGroup);
                if ($xID > 0) {
                    $listed .= "<td>";
                    $listed .= "<div class='text-center ' style='margin-bottom: 4px;' >";
                    $listed .= "<div style='' class='panel panel-info'>";
                    $listed .= "<div class='panel-heading text-bold'><span >$label</span></div>";
                    $listed .= "<div class='panel-body'>";
                    $listed .= "<div class='funkyradio'>";
                    foreach ($tempAvail as $steps => $labelSteps) {
                        $ids = $xID . "" . $steps;
                        $val = $xID . "_" . $steps;

                        $checkedStep = in_array($val, $mnSelected) ? "checked" : "";
                        $valX[$xID] = $val;
                        $listed .= "<div class='funkyradio-success' style='padding-bottom: 5px;'>
                                    <input type='checkbox' name='acc_type_child[$xID][]' id='checkbox_$ids' value='$val' $checkedStep/>
                                    <label for='checkbox_$ids' class='no-margin no-padding' title='$labelSteps'>$labelSteps</label>
                                </div>";

                        $listed .= "<input type='hidden' name='step_child_label[$val]' value='$labelSteps'>";
                    }
                    $listed .= "</div>";
                    $listed .= "</div>";
                    $listed .= "</div>";
                    $listed .= "</div>";
                    $listed .= "</td>";
                }


            }
            $listed .= "</tr>";
            $listed .= "</div>";
        }
        $listed .= "</table>";
        $listed .= "<div class='row' style='margin-top: 10px;'></div>";
        $listed .= "<input type='hidden' name='group_name' value='$availGroupData_f'>";
        $listed .= "<input type='hidden' name='class_name' value='$className'>";
        $listed .= " <div class=\"modal-footer\" style='margin-top: 10px;'>
        <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
        <button type=\"submit\" class=\"btn btn-primary\">Save changes</button>
      </div>";
        $listed .= "</div>";

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Edit $title", "application/template/lte/index.html");
        $content .= "<div class='panel-body' id='show_id'>";
        $content .= "<div class='row panel panel-default' style='background:#f0f0f0;'>";

        $content .= "<div class='col-lg-12 col-md-12 col-sm-12'>";
        $content .= "<input onclick='show_parent();changetext();' type='button' value='Customize access rights' id='myButton1' class='btn btn-warning'></input>";

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

        $content .= "</div class='col-lg-12 col-md-12 col-sm-12'>";
        $content .= "</div class='row'>";
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

        $this->load->model("Mdls/MdlAccessRight");
        $m = new MdlAccessRight();

        $m->addFilter("employee_id='$selectedEmployee'");
        $existMenu = $m->lookupAll();
        $this->db->trans_start();

        if (sizeof($existMenu) > 0) {
            $where = "employee_id='$selectedEmployee'";
            $m->deleteData($where);
            cekHijau($this->db->last_query());
        }

        $selectedData = isset($_POST["acc_type_child"]) ? $_POST["acc_type_child"] : array();
        $selectedGroupName = isset($_POST["group_name"]) ? blobDecode($_POST["group_name"]) : array();
        $aliasGroupAlias = $this->selectedPlace[$className];
        $aliasGroups = $this->config->item("$aliasGroupAlias");
        if (sizeof($selectedData) > 0) {
            foreach ($selectedData as $mnCategory => $selectedChild) {

                foreach ($selectedChild as $acc_steps) {
                    $acc_label = isset($_POST["step_child_label"][$acc_steps]) ? $_POST["step_child_label"][$acc_steps] : "";
                    $tr_label = $transaksiUI[$mnCategory]["label"];
                    $group_name = isset($selectedGroupName[$mnCategory]) ? $selectedGroupName[$mnCategory] : "";
                    $group_label = isset($aliasGroups[$group_name]) ? $aliasGroups[$group_name] : "";
                    $dataChild = array(
                        "menu_category" => $mnCategory,
                        "menu_label" => $tr_label,
                        "employee_id" => $selectedEmployee,
                        "author" => $this->session->login['id'],
                        "cabang_id" => $this->session->login['cabang_id'],
                        "steps" => $acc_steps,
                        "steps_label" => $acc_label,
                        "group_name" => $group_name,
                        "group_label" => $group_label,
                    );

                    $insertID = $m->addData($dataChild, $m->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                    $this->session->errMsg = "Data contents have been saved";
                    cekHijau($this->db->last_query());
                }
            }
        }

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
            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
            echo "</head>";
            echo "<body onload=\"$actionTarget\">";
            echo "</body>";

        } else {
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

//        echo topReload();
//        echo "</script>";

    }


}