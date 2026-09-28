<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller
{

    protected $searchString;

    private $allowView = false;
    private $allowCreate = false;
    private $allowEdit = false;
    private $allowDelete = false;
    private $allowViewHistory = false;
    //
    private $creatorUsingApproval = false;
    private $updaterUsingApproval = false;
    private $deleterUsingApproval = false;

    //
    private $relations = array();
    private $relationPairs = array();

    // <editor-fold defaultstate="collapsed" desc="getter-setter">

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $className = "Mdl" . $this->uri->segment(3);

        //region relation translator
        $this->relations = array();
        $this->relationPairs = array();
        if (file_exists(APPPATH . "models/Mdls/$className.php")) {
            $this->load->model("Mdls/" . $className);
            $o = new $className();
            $fields = $o->getFields();
            foreach ($fields as $f2Spec) {
                //                echo $f2Spec["label"]."-".$f2Spec["reference"]."<br>";
                if (isset($f2Spec['reference'])) {
                    //if (array_key_exists($f2Spec['kolom'], $o->getListedFields())) {
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
                            $name = isset($row3->nama) ? $row3->nama : "unknown";
                            $this->relationPairs[$mdlName][$id] = $name;
                        }
                    }
                    //}

                }
            }
        }

        //endregion

        //        arrprint($this->relationPairs);die();
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
        $f->openForm(base_url() . get_class($this) . "/addProcess/$ctrlName");
        $f->fillForm($className);
        $f->closeForm();

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : get_class($this);
        $p = new Layout($title, "Penambahan Data $title", "application/template/lte/index.html");

        $content .= ($f->getContent());

        if ($this->creatorUsingApproval) {
            $content .= ("<div class='panel-body'>");
            $content .= ("<div class='alert alert-warning-dot text-center'>");
            $content .= ("This action will require approval");
            $content .= ("</div>");
            $content .= ("</div class='panel-body'>");
        }
        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content" => $content,
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
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        //        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        //$tmp = $o->lookupByCondition(array($indexFieldName=> "'" . $selectedID . "'"))->result();
        $tmp = $o->lookupByCondition(array(/*$indexFieldName =>*/
            "id" => $selectedID,
        ))->result();
        //        print_r($tmp);die();
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

        $title = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
        $p = new Layout($title, "Ubah Data $title", "application/template/lte/index.html");

        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]) ? $this->config->item('dataExtRelation')[$className] : array();

        $content .= "<div class='row'>";
        $content .= "<div class='col-md-6'>";
        $content .= ($f->getContent());

        if ($this->updaterUsingApproval) {
            $content .= "<div class='alert alert-warning-dot text-center'>";
            $content .= ("This modification requires approval and this entry will be deactivated until being approved<br>");
            $content .= ("</div class='panel-body'>");
        }

        $content .= "</div class='col-md-6'>";

        $content .= "<div class='col-md-6'>";
        if (sizeof($dataRel) > 0) {
            $content .= "<div class='row'>";
            $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
            foreach ($dataRel as $mdlName => $mSpec) {
                //                cekBiru($mdlName);
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


                //region relation translator
                $relations = array();
                $relationPairs = array();
                if (file_exists(APPPATH . "models/$mdlName.php")) {
                    //                    cekKuning("b4 ".$mdlName);
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
                                        $name = isset($row3->nama) ? $row3->nama : "unknown";
                                        $relationPairs[$mdlName2][$id] = $name;
                                    }
                                }
                            }

                        }
                    }
                    //                    cekKuning("af ".$mdlName);
                }

                //endregion

                $mdlLink = base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $mdlName) . "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID;
                $content .= "<h4>";
                $content .= "<a href='$mdlLink'>";
                $content .= "<span class='fa fa-folder-open'></span> " . $mSpec['label'];
                $content .= "</a>";
                if ($allowCreate) {
                    $addLink = base_url() . get_class($this) . "/add/" . str_replace("Mdl", "", $mdlName);
                    $addLink .= "?reqField=" . $mSpec['targetField'] . "&reqVal=" . $selectedID;

                    $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $mSpec['label'] . "',
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                    $content .= "<span class='pull-right'>";
                    $content .= "<button class=\" btn btn-default\" onClick=\"$addClick\" data-toggle-tip='tooltip' data-placement='top' title='Add new " . $mSpec['label'] . "' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";
                    $content .= "</span>";
                }
                $content .= "</h4>";
                //                cekmerah($mdlName);
                $this->load->model("Mdls/" . $mdlName);

                $o2 = new $mdlName();
                //                $tmpo2=$o2->lookupLimitedData(10, 1);
                $o2->addFilter($mSpec['targetField'] . "='$selectedID'");
                $tmpo2 = $o2->lookupAll()->result();
                //                cekBiru($this->db->last_query());
                $content .= "<table class='table table-condensed'>";
                if (sizeof($tmpo2) > 0) {
                    //                    arrPrint($tmpo2);

                    $content .= "<tr bgcolor='#f0f0f0'>";
                    foreach ($o2->getListedFields() as $fName => $label) {
                        $content .= "<td>$label</td>";

                    }


                    $content .= "</tr>";

                    foreach ($tmpo2 as $row) {
                        $content .= "<tr>";
                        foreach ($o2->getListedFields() as $fName => $label) {
                            $content .= "<td>";

                            //===if related
                            if (array_key_exists($fName, $relations)) {
                                $fieldLabel = isset($relationPairs[$fName][$row->$fName]) ? $relationPairs[$fName][$row->$fName] : "unknown rel";
                            }
                            else {
                                $fieldLabel = $row->$fName;
                            }
                            //                            $content .= formatField($fName, $fieldLabel);
                            $content .= $fieldLabel;
                            $content .= "</td>";
                        }
                        $content .= "</tr>";
                    }


                }

                //region need approval
                //                $this->load->model("Mdls/"."MdlDataTmp");
                //                $tData = new MdlDataTmp();
                //                $tData->addFilter("mdl_name='$mdlName'");
                //                $tData->addFilter($mSpec['targetField']."='$selectedID'");
                //                $tmpTmp = $tData->lookupAll()->result();
                //                if(sizeof($tmpTmp)>0){
                //                    $content .= "<tr>";
                //                    $content .= "<td colspan='".sizeof($o2->getListedFields())."' align=center>";
                //                    $content .= "<a href='$mdlLink' class='text-danger'>";
                //                    $content.=sizeof($tmpTmp)." unapproved entri[es]";
                //                    $content .= "</a>";
                //                    $content .= "</td>";
                //                    $content .= "</tr>";
                //                }
                //endregion


                $content .= "</table class='table table-condensed'>";
            }

            $content .= "</div class='row'>";
        }

        if (sizeof($dataExtRel) > 0) {
            //            arrprint($dataExtRel);

            foreach ($dataExtRel as $mSpec) {
                $content .= "<div class='row'>";
                $content .= "<h4><span class='fa fa-folder-open'></span> " . $mSpec['label'] . "</h4>";
                $mSpec['target'];
                //                $mSpec['srcKey'];
                $backLink = base64_encode(serialize(current_url()));
                $content .= "<iframe frameborder='0' width=100% height=100% style='width:100%;height:500px;position:relative;top:0px;left:0px;right:0px;bottom:0px;overflow:hidden;' src='" . base_url() . $mSpec['target'] . "&attached=1&sID=" . $selectedID . "&backLink=$backLink\'>";
                $content .= "</iframe>";

                $content .= "</div class='row'>";
            }


        }

        $content .= "</div class='col-md-6'>";


        $content .= "</div class='row'>";


        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => "Data $ctrlName",
            "subTitle" => "Create new $ctrlName",
            "content" => $content,
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
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        //$indexFieldName = $o->getIndexFieldName();$indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);


        $this->load->model("Mdls/" . "MdlDataTmp");
        $oTmp = new MdlDataTmp();
        $oTmp->addFilter("mdl_name='$className'");
        $oTmp->addFilter("_id='$selectedID'");


        //$tmp = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $selectedID))->result();
        $tmp = $oTmp->lookupAll()->result();
        $tmpContent = (object)unserialize(base64_decode($tmp[0]->content));
        //print_r($tmpContent);die();
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
        //$f->fillForm($tmpContent);
        $content .= ("<table class='table table-condensed'>");
        $content .= ("<tr><td colspan='2' class='text-muted text-uppercase'><h4>data yang diajukan</h4></td></tr>");
        foreach ($o->getFields() as $fName => $fSpec) {

            // arrPrint($fSpec);

            $fType = $fSpec['type'];
            $fInputType = $fSpec['inputType'];
            $fDataSource = isset($fSpec['dataSource']) ? $fSpec['dataSource'] : "";
            $fColName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
            $fLabel = isset($fSpec['label']) ? $fSpec['label'] : $fName;
            $content .= ("<tr>");
            $content .= ("<td class='text-muted'>$fLabel");
            $content .= ("</td>");
            //
            $fieldLabel = isset($tmpContent->$fColName) ? $tmpContent->$fColName : "";

            //region terjemahan isi berdasat type data
            switch ($fType) {
                case "blob":
                case "mediumblob":
                    $isiBlop = unserialize(base64_decode($fieldLabel));
                    if (is_array($isiBlop)) {
                        // $var = "";
                        $hasil = "";
                        foreach ($isiBlop as $kBlop) {
                            $var = $fDataSource[$kBlop];
                            if ($hasil == "") {
                                $hasil .= "$var";
                            }
                            else {
                                $hasil = "$hasil, " . "$var";
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
            // cekMerah();
            if (array_key_exists($fColName, $this->relations)) {
                // cekHijau();
                $fieldLabel = isset($this->relationPairs[$fColName][$fieldLabel]) ? "<span class='fa fa-folder-o' style='color:#ff7700;'></span> " . $this->relationPairs[$fColName][$fieldLabel] : "unknown rel";
            }
            $fContent = $fieldLabel;
            $disabled = isset($tmpContent->$fColName) ? "readonly" : "disabled";
            $content .= ("<td>");
            //            $content .= ("<input type='text' class='form-control' $disabled value='$fContent'>");
            $content .= ("$conten_f");
            // $content .= ("$fContent");
            $content .= ("</td>");
            $content .= ("</tr>");
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

                break;
            case "delete":
                $yesAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doApproveDeleteFrom/$ctrlName/$selectedID/$origID');";
                $noAction = "top.$('#result').load('" . base_url() . get_class($this) . "/doRejectDeleteFrom/$ctrlName/$selectedID/$origID');";
                $rejectAlertMsg = "this deletion proposal will be ignored and related data will be set as active";
                $approveAlertMsg = "data entry related to this proposal will be DELETED";
                $yesLabel = "delete anyway";
                $noLabel = "dont delete";
                break;
        }


        $content .= ("<div class='row'>");
        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-danger btn-block' href='javascript:void(0)' onClick =\"if(confirm('$rejectAlertMsg \\nContinue?')==1){$noAction}\">$noLabel</a>");
        $content .= ("</div class='col-sm-6'>");

        $content .= ("<div class='col-sm-6'>");
        $content .= ("<a class='btn btn-success btn-block' href='javascript:void(0)' onClick =\"if(confirm('$approveAlertMsg \\nContinue?')==1){$yesAction}\">$yesLabel</a>");
        $content .= ("</div class='col-sm-6'>");
        $content .= ("</div class='row'>");

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
        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $indexFieldName = "id";
        $selectedID = $this->uri->segment(4);
        $origID = $this->uri->segment(5);


        $this->load->model("Mdls/" . "MdlDataTmp");
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
            "id" => "f1",
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/editProcessFrom/$ctrlName/" . $selectedID . "/$origID",
            "target" => "result",
            "class" => "form-horizontal",
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
        $this->load->model("Mdls/" . $className);
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
            //</editor-fold>
            if ($this->creatorUsingApproval) {
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


                $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $insertID . "";
                $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $ctrlName ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:true,
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
                $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
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
        $this->load->model("Mdls/" . $className);
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
                    "old_content_intext" => print_r($tmpOrig, true),
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
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content" => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label" => "applied",
                    "oleh_id" => $this->session->login['id'],
                    "oleh_name" => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                //</editor-fold>
            }
            //matiHEre();

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
                $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
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
        $content = "";
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
            die();
        }
        $className = "Mdl" . $this->uri->segment(3);

        $ctrlName = $this->uri->segment(3);
        $dataRel = isset($this->config->item('dataRelation')[$className]) ? $this->config->item('dataRelation')[$className] : array();
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

        $realObjName = isset($this->config->item('heDataBehaviour')[$className]['label']) ? $this->config->item('heDataBehaviour')[$className]['label'] : $ctrlName;
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


        //<editor-fold desc="tampilan approval data">
        $arrItemTmp = array();
        if (sizeof($dataProposals) > 0) {


            foreach ($dataProposals as $mdlName => $pSpec) {
                $this->load->model("Mdls/" . $mdlName);
                $o2 = new $mdlName();
                $listedFields = $o2->getListedFields();
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
        $limit_per_page = 10;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $o->lookupDataCount($key);

//        cekHijau("$page || $total_records");
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
            // cekHijau($this->db->last_query());
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
            foreach ($tmp as $m => $rowSpec) {
                if ($this->allowEdit) {
                    $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $rowSpec->id . "";
                    $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $title ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:true,
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
                                        title:'$ctrlName histories ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                //foreach ($rowSpec as $n => $nx) {
                $tmpItem = array();
                //                arrprint($this->relationPairs);
                foreach ($o->getListedFields() as $ofName => $label) {
                    $fName = $ofName;

                    //===if related
                    if (array_key_exists($ofName, $this->relations)) {
                        $fieldLabel = isset($this->relationPairs[$ofName][$rowSpec->$ofName]) ? "<span class='fa fa-folder-o' style='color:#0056cd;'></span> " . $this->relationPairs[$ofName][$rowSpec->$ofName] : "-unknown rel-";
                    }
                    else {
                        $fieldLabel = $rowSpec->$ofName;
                    }


                    $tmpItem['action'] = "<span class='btn-block text-center'>";
                    //                    $tmpItem[$ofName] = str_replace(" ", "&nbsp;", $fieldLabel) . "&nbsp;";
                    $tmpItem[$ofName] = $fieldLabel;
                    if ($this->allowEdit) {
                        $addNumber = $colCounter == 0 ? "<a href='javascript:void(0)' onclick =\"$historyClick\"><span class='badge' style='background:#c0c0c0;color:#656564;'>$rowCounter</span></a>" : "";
                        $tmpItem['action'] .= "<a class='btn btn-default' href='javascript:void(0)' data-toggle-tip='tooltip' data-placement='left' title='modify this entry' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";

                    }

                    $colCounter++;
                }


                if ($this->allowDelete) {
                    $tmpItem['action'] .= "<button class='btn btn-danger' data-toggle-tip='tooltip' data-placement='left' title='delete entry' onClick=\"if(confirm('Remove entry?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                }

                if ($this->allowViewHistory) {
                    $tmpItem['action'] .= "<a class='btn btn-default' href='javascript:void(0)' data-toggle-tip='tooltip' data-placement='left' title='view histories of this entry' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span></a>";
                }

                $tmpItem['action'] .= "</span class='btn-block'>";

                //                $tmpItem['history'] = "<a class='btn btn-default' href='javascript:void(0)' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span> history</a>";
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
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
            $strAddLink = "";
            $strAddLink .= "<div class='btn-group'>";
            $strAddLink .= "<button href='javascript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle-tip='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";

            $strAddLink .= "<button href='javascript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle-tip='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";

            $strAddLink .= "</div class='btn-group'>";


        }
        else {
            $strAddLink = "";
        }
        if ($this->allowEdit) {
            $strEditLink = "<button href='javascript:void(0)' class=\" btn btn-default\" onClick=\"location.href='" . base_url() . get_class($this) . "/editMany/$ctrlName/" . $this->uri->segment(4) . "'\" data-toggle-tip='tooltip' data-placement='top' title='Modify all $title in this page' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-pencil'></button>";
        }
        else {
            $strEditLink = "";
        }

        $arrayHeader = $o->getListedFields();
        $arrayHeader["action"] = "action";
        //        $arrayHeader["history"] = "histories";
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
                foreach ($tmpO->getListedFields() as $fName => $label) {

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

        //        arrprint($folders);die();
        $data = array(
            "mode" => $this->uri->segment(2),
            "errMsg" => $this->session->errMsg,
            "title" => $realObjName . $titleSuffix,
            "subTitle" => "Registered $realObjName" . $titleSuffix,
            "strActiveDataTitle" => "<span class='glyphicon glyphicon-th-list'></span> List of $title" . $titleSuffix,
            "linkStr" => isset($params['links']) ? $params['links'] : "",
            "arrayHistoryLabels" => $arrayHeader,
            "arrayHistory" => $arrayItem,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert'></span> approval needed",
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

    public function viewHistories_()
    {
        $content = "";
        $className = "Mdl" . $this->uri->segment(3);
        $ctrlName = $this->uri->segment(3);
        $selectedID = $this->uri->segment(4);
        $this->load->model("Mdls/" . $className);

        $o = new $className();
        $listedFields = $o->getListedFields();
        $fields = $o->getFields();

        $p = new Layout("", "", "application/template/lte/index.html");
        $this->load->model("Mdls/" . "MdlDataHistory");
        $h = new MdlDataHistory();
        $h->addFilter("mdl_name='$className'");
        $h->addFilter("orig_id='$selectedID'");
        $tmpH = $h->lookupAll()->result();
        if (sizeof($tmpH) > 0) {
            $content .= ("<div class='table-responsive'>");
            $content .= ("<table class='table table-condensed table-bordered'>");
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
            $content .= ("<div class='alert alert-warning text-center'>");
            $content .= ("this item has no history entry");
            $content .= ("</div class='alert alert-warning'>");
        }
        echo $content;
    }

    public function viewHistories()
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
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            //            $tmpOrig = $o->lookupByCondition(array(/*$indexFieldName =>*/ "id" => $origID))->result();
            $tmpOrig = $o->lookupByCondition(array("id" => $origID))->result();
            $o->setFilters(array());
            $o->updateData($where, $tmpContent, $o->getTableName());
            cekMerah($this->db->last_query());
            $this->session->errMsg = "Data has been updated";

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
//            cekMerah($this->db->last_query());
            //</editor-fold>
        }
        else {//===new data
            $tmpContent["status"] = 1;
            $tmpContent["trash"] = 0;
            unset($tmpContent["id"]);
            $insertID = $o->addData($tmpContent, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
//            cekMerah($this->db->last_query());
            $this->session->errMsg = "Data has been saved";

            //<editor-fold desc="data history / approve">
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
            //</editor-fold>
        }
//die("clear blm comit bro");
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
                    if (file_exists(APPPATH . "models/$tmpModelName.php")) {
                        $this->load->model("Mdls/" . $tmpModelName);
                        $o2 = new $tmpModelName();
                        $fields2 = $o2->getFields();
                        $tmp3 = $o2->lookupAll()->result();

                        if (!in_array($tmpModelName, $refModels)) {
                            $refModels[] = $tmpModelName;
                        }
                        if (sizeof($tmp3) > 0) {
                            $refOptions[$tmpModelName][''] = "- select -";
                            foreach ($tmp3 as $row3) {
                                $id = isset($row3->id) ? $row3->id : 0;
                                $name = isset($row3->nama) ? $row3->nama : "unknown";
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
                                        if (isset($refOptions[$tmpMdlName]) && sizeof($refOptions[$tmpMdlName]) > 0) {
                                            foreach ($refOptions[$tmpMdlName] as $key => $val) {
                                                $content .= "<option value='$key'>$val</option>";
                                            }
                                        }
                                    }
                                    $content .= "</select class='form-control'>";
                                }
                                else {
                                    $content .= "<input type=password class='form-control' disabled>";
                                }
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

    public function doAddMany()
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
                                        draggable:true,
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
            //arrPrint($inValidRows);
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

    private function lineValidate($o, $lineNumber, $mode = "add")
    {
        $invalidCounter = 0;
        $valResults = array();
        if (count($o->getValidationRules()) > 0) {
            //==do some validation
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
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
                $listedFields = $o->getListedFields();
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
                    $jCtr++;
                    $ofName = $fSpec['kolom'];
                    $fName = $ofName;


                    if (array_key_exists($fieldSpec['kolom'], $validRules)) {
                        $suffix = "*";
                        $fStyle = "font-weight:bold;";
                    }
                    else {
                        $suffix = "";
                        $fStyle = "";
                    }
                    $arrayHeader[$ofName] = "<span style='$fStyle'>" . $fSpec['label'] . $suffix . "</span>";
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
                }


                //                $tmpItem['history'] = "<a class='btn btn-default' href='javascript:void(0)' onclick=\"$historyClick\"><span class='glyphicon glyphicon-time'></span> history</a>";
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
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
            $strAddLink = "";
            $strAddLink .= "<div class='btn-group'>";
            $strAddLink .= "<button href='javascript:void(0)' class=\" btn btn-primary\" onClick=\"$addClick\" data-toggle-tip='tooltip' data-placement='top' title='Add new $title' class='btn btn-circle btn-xs btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";

            $strAddLink .= "<button href='javascript:void(0)' class='btn btn-success' onclick=\"location.href = '$addManyLink';\"  data-toggle-tip='tooltip' data-placement='top' title='Add many entries of $title'><span class='glyphicon glyphicon-plus-sign'></span></button>";

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
                foreach ($tmpO->getListedFields() as $fName => $label) {

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

        $alternateLink = "<button class='btn btn-success' id='btnSave' name='btnSave' onclick=\"this.disabled=true;document.getElementById('fmany').submit();\">save entries</button>";

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
        //arrPrint($fields);
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

    public function LogData()
    {

        $className = "Mdl" . $this->uri->segment(3);
        cekHEre($className);
    }

    public function myProfile()
    {
        $className = "Mdl" . $this->uri->segment(3);
        // cekHEre($className);
        $pro = new MdlEmployee();
        $log = new MdlActivityLog();
        $arrProfile = $pro->lookupByID(my_id())->result()[0];
        // arrPrint($ss);
        // $arrProfile = array(
        //     "nama" => "Nina"
        // );
        // $a
        $arrActivitylog = "";
        $condite = "uid='" . my_id() . "' order by id desc limit 10";
        $arrActivitylog = $log->lookupByCondition($condite)->result();
        $rightCtn = "";

        $data = array(
            "mode" => $this->uri->segment(2),
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "title" => "Profile",
            "subTitle" => "",
            "arrProfile" => $arrProfile,
            "arrActivitylog" => $arrActivitylog,
            "headTpl" => headTpl(),
            "footTpl" => footTpl(),
        );


        //endregion

        $this->load->view("data", $data);
    }

    public function editone()
    {
        $data = array(
            "mode" => "modal",
            // "template"       => $this->config->item("heTransaksi_layout")[$jenisTr]["receiptTemplate"][$currentStepNum],
            "template" => "application/template/profile.html",
            "heading" => $this->uri->segment(3),
            "forms" => "",
            "footer" => "",
            "target" => "",
            "actions" => "",
            // "action"     => "",
            // "arrActivitylog" => $arrActivitylog,
            "headTpl" => headTpl(),
            "footTpl" => footTpl(),
        );
        $this->load->view("data", $data);
    }

}
