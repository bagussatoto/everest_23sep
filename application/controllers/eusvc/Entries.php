<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Entries extends REST_Controller
{
    private $jenisTr;

    private $validationRules = array(
        "main" => array("olehID", "olehName", "pihakID", "pihakName", "placeID", "placeName", "cabangID", "cabangName", "gudangID", "gudangName",),
        "items" => array("id", "nama", "jml", "harga", ),
    );

    private $cloners = array("olehID", "olehName", "pihakID", "pihakName", "placeID", "placeName", "cabangID", "cabangName", "gudangID", "gudangName",);

    private $masterInits = array();
    private $tableMasterInits = array();

    function __construct($config = 'rest')
    {

        parent::__construct($config);
        $this->load->database();
        $this->load->model("MdlTransaksi");
//        $this->load->helper("uri");
        $this->jenisTr = $this->uri->segment(4);

        $_SESSION['debuger']=0;

    }

    public function askLastEntries_get()
    {

        $olehID = $this->uri->segment(5);
        $tr=new MdlTransaksi();
        $tr->addFilter("jenis_master='".$this->jenisTr."'");
        $tr->addFilter("transaksi.oleh_id='".$olehID."'");
        $tmp=$tr->lookupHistories_joined(10, 10, 1)->result();
//        cekmerah($this->db->last_query());
//        cekmerah(sizeof($tmp));
        if(sizeof($tmp)>0){
            $result=$tmp;
        }else{
            $result=array();
        }
//        arrprint($result);
        $this->response($result, 200);
    }

    public function askEntryDetail_get()
    {

        $id = $this->uri->segment(5);
        $tr=new MdlTransaksi();


        $tmp=$tr->lookupJoinedById($id)->result();

        if(sizeof($tmp)>0){
            $result=$tmp[0];
        }else{
            $result=array();
        }
        $this->response($result, 200);
    }

    public function postEntry_post()
    {


        $this->masterInits = array(
            "step_number" => 1,
            "stepCode"=>$this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],

        );
        $this->tableMasterInits = array(
            "jenis_master" => $this->jenisTr,
            "jenis_top"=>$this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
            "jenis_label"=>$this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'],

        );
        $this->tableDetailInits = array(
            "jenis_master" => $this->jenisTr,
            "jenis_top"=>$this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
            "jenis_label"=>$this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['label'],

        );
        $tmpInput = $_POST;

        $result = 0;
        if (array_key_exists($this->jenisTr, $this->config->item("heTransaksi_ui"))) {
            $cCode = "_TR_" . $this->jenisTr;
            if (isset($tmpInput['main']) && isset($tmpInput['items']) && sizeof($tmpInput['main']) > 0 && sizeof($tmpInput['items']) > 0) {

                //validate
                foreach ($this->validationRules['main'] as $key) {
                    if (!isset($tmpInput['main'][$key]) || strlen($tmpInput['main'][$key]) < 1) {
                        die("we did not receive a main post variable named <b>" . $key . "</b><br>please make sure you set it before re-making this request ");
                    }
                }
                foreach ($tmpInput['items'] as $i => $iSpec) {
                    foreach ($this->validationRules['items'] as $key) {
                        if (!isset($tmpInput['items'][$i][$key]) || strlen($tmpInput['items'][$i][$key]) < 1) {
                            die("we did not receive a detail post variable named <b>" . $key . "</b> in sequence # $i<br>please make sure you set it before re-making this request ");
                        }
                    }
                    foreach ($this->cloners as $key) {
                        $tmpInput['items'][$i][$key] = $tmpInput['main'][$key];
                    }
                }
                $this->db->trans_start();
                $_SESSION[$cCode]['main'] = $tmpInput['main'];
                $_SESSION[$cCode]['items'] = $tmpInput['items'];


                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);

                foreach($this->masterInits as $key=>$src){
                    $_SESSION[$cCode]['main'][$key]=$src;
                }
                foreach($this->tableMasterInits as $key=>$src){
                    $_SESSION[$cCode]['tableIn_master'][$key]=$src;
                }


//                arrprint($_SESSION[$cCode]['main']);
//                die();

                //
                //region penomoran receipt
                //<editor-fold desc="==========penomoran">
                $this->load->model("CustomCounter");
                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");

                $counterForNumber = array($this->config->item('heTransaksi_core')[$this->jenisTr]['formatNota']);
                if (!in_array($counterForNumber[0], $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'])) {
                    die("Used number should be registered in 'counters' config as well");
                }

                foreach ($counterForNumber as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    foreach ($cParams as $param) {

                        $cValues[$i][$param] = isset($_SESSION[$cCode]['main'][$param])?$_SESSION[$cCode]['main'][$param]:"*";

                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                }


                $stepNumber = 1;

                $tmpNomorNota = $paramSpec['paramString'];

//            $_SESSION[$cCode]['tableIn_master']['nomer'] = $tmpNomorNota;


                if (isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2])) {
                    $nextProp = array(
                        "num" => 2,
                        "code" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['target'],
                        "label" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['label'],
                        "groupID" => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][2]['userGroup'],
                    );
                } else {
                    $nextProp = array(
                        "num" => 0,
                        "code" => "",
                        "label" => "",
                        "groupID" => "",
                    );
                }

                //</editor-fold>
                //endregion

                //
                //region dynamic counters



                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");
                $configCustomParams = $this->config->item('heTransaksi_core')[$this->jenisTr]['counters'];
                if (sizeof($configCustomParams) > 0) {
                    $cContent = array();
                    foreach ($configCustomParams as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        foreach ($cParams as $param) {
                            $cValues[$i][$param] = $_SESSION[$cCode]['main'][$param];
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                        $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                        switch ($paramSpec['id']) {
                            case 0: //===counter type is new
                                $paramKeyRaw = print_r($cParams, true);
                                $paramValuesRaw = print_r($cValues[$i], true);
                                $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                                break;
                            default: //===counter to be updated
                                $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                                break;
                        }
                        //echo "<hr>";
                    }
                }
                $appliedCounters = base64_encode(serialize($cContent));
                $appliedCounters_inText = print_r($cContent, true);
                //endregion

                //
                //region addition on master
                $addValues = array(
                    'counters' => $appliedCounters,
                    'counters_intext' => $appliedCounters_inText,
                    'nomer' => $tmpNomorNota,
                    'dtime' => date("Y-m-d H:i:s"),
                    'fulldate' => date("Y-m-d"),
                    "step_avail" => sizeof($this->config->item('heTransaksi_ui')[$this->jenisTr]['steps']),
                    "step_number" => 1,
                    "step_current" => 1,
                    "next_step_num" => $nextProp['num'],
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],


                );
                foreach ($addValues as $key => $val) {
                    $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                }
                //endregion

                //
                //region addition on detail
                $addSubValues = array(
                    "sub_step_number" => 1,
                    "sub_step_current" => 1,
                    "sub_step_avail" => sizeof($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps']),
                    "next_substep_num" => $nextProp['num'],
                    "next_substep_code" => $nextProp['code'],
                    "next_substep_label" => $nextProp['label'],
                    "next_subgroup_code" => $nextProp['groupID'],


                );
                foreach ($_SESSION[$cCode]['tableIn_detail'] as $id => $dSpec) {
                    foreach ($addSubValues as $key => $val) {
                        $_SESSION[$cCode]['tableIn_detail'][$id][$key] = $val;
                    }
                }
                //endregion

                //
                //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
                if (isset($_SESSION[$cCode]['tableIn_master']) && sizeof($_SESSION[$cCode]['tableIn_master']) > 0) {
                    $tr = new MdlTransaksi();
                    $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
                    $insertID = $tr->writeMainEntries($_SESSION[$cCode]['tableIn_master']);
                    if ($insertID < 1) {
                        die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                    }
                    //==transaksi_id dan nomor nota diinject kan ke gate utama
                    $injectors = array(
                        "transaksi_id" => $insertID,
                        "nomer" => $tmpNomorNota,
                    );
                    foreach ($injectors as $key => $val) {
                        $_SESSION[$cCode]['main'][$key] = $val;
                        foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                            $id = $iSpec['id'];
                            $_SESSION[$cCode]['items'][$id][$key] = $val;
                        }
                    }

                    //===signature
                    $dwsign = $tr->writeSignature($insertID, array(
                            "nomer" => $_SESSION[$cCode]['main']['nomer'],
                            "step_number" => 1,
                            "step_code" => $this->jenisTr,
                            "step_name" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'],
                            "group_code" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['userGroup'],
                            "oleh_id" => $_SESSION[$cCode]['main']['olehID'],
                            "oleh_nama" => $_SESSION[$cCode]['main']['olehName'],
                            "keterangan" => $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'],
                        )
                    ) or die("Failed to write signature");

                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $insertID), array(
                            "next_step_num" => $nextProp['num'],
                            "next_step_code" => $nextProp['code'],
                            "next_step_label" => $nextProp['label'],
                            "next_group_code" => $nextProp['groupID'],

                            //===references
                            "id_master" => $insertID,
                            "id_top" => $insertID,
                            "ids_prev" => "",
                            "ids_prev_intext" => "",
                            "nomer_top" => $_SESSION[$cCode]['main']['nomer'],
                            "nomers_prev" => "",
                            "nomers_prev_intext" => "",
                            //                    "jenis_top"           => $this->jenisTr,
                            "jenises_prev" => "",
                            "jenises_prev_intext" => "",

                        )
                    ) or die("Failed to update tr next-state!");
                    //cekHijau($this->db->last_query());

                    $addValues = array(

                        //===references
                        "id_master" => $insertID,
                        "id_top" => $insertID,
                        "ids_prev" => "",
                        "ids_prev_intext" => "",
                        "nomer_top" => $_SESSION[$cCode]['main']['nomer'],
                        "nomers_prev" => "",
                        "nomers_prev_intext" => "",
                        //                    "jenis_top"           => $this->jenisTr,
                        "jenises_prev" => "",
                        "jenises_prev_intext" => "",
                        //

                    );
                    foreach ($addValues as $key => $val) {
                        $_SESSION[$cCode]['tableIn_master'][$key] = $val;
                    }

                }
                if (isset($_SESSION[$cCode]['tableIn_master_values']) && sizeof($_SESSION[$cCode]['tableIn_master_values']) > 0) {
                    if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'])) {
                        foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                            if(isset($_SESSION[$cCode]['tableIn_master_values'][$key])){
                                $tr->writeMainValues($insertID, array("key" => $key, "value" => $_SESSION[$cCode]['tableIn_master_values'][$key]));
                            }
                        }
                    }
                }
                if (isset($_SESSION[$cCode]['main_add_values']) && sizeof($_SESSION[$cCode]['main_add_values']) > 0) {
                    foreach ($_SESSION[$cCode]['main_add_values'] as $key => $val) {
                        $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    }
                }


                if (isset($_SESSION[$cCode]['main_inputs']) && sizeof($_SESSION[$cCode]['main_inputs']) > 0) {
                    foreach ($_SESSION[$cCode]['main_inputs'] as $key => $val) {
                        $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
//                    cekkuning("making a clone for input key $key / $val");
//                    $tmpTableIn=$_SESSION[$cCode]['tableIn_master'];
//                    $replacers=array(
//                        "nomer"=>$_SESSION[$cCode]['tableIn_master']['nomer']."_$key",
//                    );
//                    foreach($replacers as $key=>$val){
//                        $tmpTableIn[$key]=$val;
//                    }
//                    $subInputInsertID = $tr->writeMainEntries($tmpTableIn);
                    }
                }

                if (isset($_SESSION[$cCode]['main_add_fields']) && sizeof($_SESSION[$cCode]['main_add_fields']) > 0) {
                    foreach ($_SESSION[$cCode]['main_add_fields'] as $key => $val) {
                        $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                    }
                }

                if (isset($_SESSION[$cCode]['main_applets']) && sizeof($_SESSION[$cCode]['main_applets']) > 0) {
                    foreach ($_SESSION[$cCode]['main_applets'] as $amdl => $aSpec) {
                        $tr->writeMainApplets($insertID, array(
                                "mdl_name" => $amdl,
                                "key" => $aSpec['key'],
                                "label" => $aSpec['labelValue'],
                                "description" => $aSpec['description'],
                            )
                        );
                    }
                }

                if (isset($_SESSION[$cCode]['main_elements']) && sizeof($_SESSION[$cCode]['main_elements']) > 0) {
                    foreach ($_SESSION[$cCode]['main_elements'] as $elName => $aSpec) {
                        $tr->writeMainElements($insertID, array(
                                "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                                "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                                "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                                "name" => $aSpec['name'],
                                "label" => $aSpec['label'],
                                "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                                "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                            )
                        );


                        //==nebeng bikin inputLabels
                        $currentValue = "";
                        switch ($aSpec['elementType']) {
                            case "dataModel":
                                $currentValue = $aSpec['key'];
                                break;
                            case "dataField":
                                $currentValue = $aSpec['value'];
                                break;
                        }
                        if (array_key_exists($elName, $relOptionConfigs)) {
//					cekhijau("$eName terdaftar pada relInputs");


                            if (isset($relOptionConfigs[$elName][$currentValue])) {
                                if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                    foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                        $inputLabels[$oValueName] = $oValSpec['label'];
                                        if (isset($oValSpec['auth'])) {
                                            if (isset($oValSpec['auth']['groupID'])) {
                                                $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
                                            }
                                        }
                                    }
                                }
                            } else {
//						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                            }

                        }

                    }
                }


//            cekMerah("inputLabels");
//            arrprint($inputLabels);
//            cekMerah("inputAuths");
//            arrprint($inputAuthConfigs);


                if (isset($_SESSION[$cCode]['tableIn_detail']) && sizeof($_SESSION[$cCode]['tableIn_detail']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail'] as $dSpec) {
                        $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    }
                }
                if (isset($_SESSION[$cCode]['tableIn_detail2']) && sizeof($_SESSION[$cCode]['tableIn_detail2']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail2'] as $dSpec) {
                        $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    }
                }
                if (isset($_SESSION[$cCode]['tableIn_detail2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail2_sum']) > 0) {
                    $insertIDs = array();
                    foreach ($_SESSION[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                        $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    }
                }

                if (isset($_SESSION[$cCode]['tableIn_detail_values']) && sizeof($_SESSION[$cCode]['tableIn_detail_values']) > 0) {
                    foreach ($_SESSION[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'])) {
                            foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                if(isset($dSpec[$src])){

                                    $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));
                                }
                            }
                        }


                    }
                }

                if (isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) && sizeof($_SESSION[$cCode]['tableIn_detail_values2_sum']) > 0) {
                    foreach ($_SESSION[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                        if (isset($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                            foreach ($this->config->item('heTransaksi_core')[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                if(isset($dSpec[$src])){

                                    $insertIDs[] = $tr->writeDetailValues($insertID, array("produk_jenis" => $_SESSION[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'], "produk_id" => $pID, "key" => $key, "value" => $dSpec[$src]));
                                }
                            }
                        }


                    }
                }
                //endregion


                $baseRegistries = array(
                    'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                    'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
//                    'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
//                    'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
//                    'rsltItems' => isset($_SESSION[$cCode]['rsltItems']) ? $_SESSION[$cCode]['rsltItems'] : array(),
//                    'rsltItems2' => isset($_SESSION[$cCode]['rsltItems2']) ? $_SESSION[$cCode]['rsltItems2'] : array(),
                    'main' => isset($_SESSION[$cCode]['main']) ? $_SESSION[$cCode]['main'] : array(),
                    'items' => isset($_SESSION[$cCode]['items']) ? $_SESSION[$cCode]['items'] : array(),
//                    'items2' => isset($_SESSION[$cCode]['items2']) ? $_SESSION[$cCode]['items2'] : array(),
//                    'items2_sum' => isset($_SESSION[$cCode]['items2_sum']) ? $_SESSION[$cCode]['items2_sum'] : array(),
//                    'items_rsltItems' => isset($_SESSION[$cCode]['items_rsltItems']) ? $_SESSION[$cCode]['items_rsltItems'] : array(),
//                    'items_rsltItems2' => isset($_SESSION[$cCode]['items_rsltItems2']) ? $_SESSION[$cCode]['items_rsltItems2'] : array(),
                    'tableIn_master' => isset($_SESSION[$cCode]['tableIn_master']) ? $_SESSION[$cCode]['tableIn_master'] : array(),
                    'tableIn_detail' => isset($_SESSION[$cCode]['tableIn_detail']) ? $_SESSION[$cCode]['tableIn_detail'] : array(),
                    'tableIn_detail2_sum' => isset($_SESSION[$cCode]['tableIn_detail2_sum']) ? $_SESSION[$cCode]['tableIn_detail2_sum'] : array(),
                    'tableIn_detail_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems'] : array(),
                    'tableIn_detail_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_rsltItems2'] : array(),
                    'tableIn_master_values' => isset($_SESSION[$cCode]['tableIn_master_values']) ? $_SESSION[$cCode]['tableIn_master_values'] : array(),
                    'tableIn_detail_values' => isset($_SESSION[$cCode]['tableIn_detail_values']) ? $_SESSION[$cCode]['tableIn_detail_values'] : array(),
                    'tableIn_detail_values_rsltItems' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                    'tableIn_detail_values_rsltItems2' => isset($_SESSION[$cCode]['tableIn_detail_values_rsltItems2']) ? $_SESSION[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                    'tableIn_detail_values2_sum' => isset($_SESSION[$cCode]['tableIn_detail_values2_sum']) ? $_SESSION[$cCode]['tableIn_detail_values2_sum'] : array(),
                    'main_add_values' => isset($_SESSION[$cCode]['main_add_values']) ? $_SESSION[$cCode]['main_add_values'] : array(),
                    'main_add_fields' => isset($_SESSION[$cCode]['main_add_fields']) ? $_SESSION[$cCode]['main_add_fields'] : array(),
//                    'main_elements' => isset($_SESSION[$cCode]['main_elements']) ? $_SESSION[$cCode]['main_elements'] : array(),
//                    'main_inputs' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
//                    'main_inputs_orig' => isset($_SESSION[$cCode]['main_inputs']) ? $_SESSION[$cCode]['main_inputs'] : array(),
                );
//                cekHitam("cetak transaksi $cCode");
                $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));



                $this->db->trans_complete();
                $result = $insertID;

            } else {
                die("you did not give us a decent input for this tCode");
            }
        } else {
            die("you are asking an unknown tCode");
        }

//        print_r($_POST);
//        print_r($tmpInput);
//        print_r($_SESSION[$cCode]);
        $_SESSION[$cCode]=null;
        unset($_SESSION[$cCode]);
        $this->response($result, 200);
    }


    //==today acts
    public function askTodayEntryCounts_get()
    {

        $cabID = $this->uri->segment(4);
        $tr=new MdlTransaksi();
//        $tr->addFilter("jenis_master='".$this->jenisTr."'");
//        $tr->addFilter("transaksi.oleh_id='".$olehID."'");
        $tr->addFilter("transaksi.fulldate='".date("Y-m-d")."'");
        $tr->addFilter("transaksi.cabang_id='".$cabID."'");
//        $this->db->select("oleh_id,oleh_nama,jenis,jenis_label,transaksi_nilai,customers_nama,customers_id,dtime,fulldate,next_step_num");
        $tmp=$tr->lookupHistories(200, 200, 1)->result();
//        cekmerah($this->db->last_query());
//        cekmerah(sizeof($tmp));
        $jenises=array();
        $recaps=array();
        $result=array();
        if(sizeof($tmp)>0){
            foreach($tmp as $row){
                if(!in_array($row->jenis,$jenises)){
                    $jenises[]=$row->jenis;
                }
                if(!array_key_exists($row->jenis,$recaps)){
                    $recaps[$row->jenis]=array(
                        "label"=>$row->jenis_label,
                        "qty"=>0,
                        "value"=>0,
                        "jenis"=>$row->jenis,
                    );
                }
                $recaps[$row->jenis]['qty']++;
                $recaps[$row->jenis]['value']+=$row->transaksi_nilai;
            }

            $result=$recaps;
        }
//        arrprint($result);
        $this->response($result, 200);
    }

    public function askTodayEntries_get()
    {

        $cabID = $this->uri->segment(4);
        $jenis = $this->uri->segment(5);
        $tr=new MdlTransaksi();
        $tr->addFilter("jenis='".$jenis."'");

        $tr->addFilter("transaksi.fulldate='".date("Y-m-d")."'");
        $tr->addFilter("transaksi.cabang_id='".$cabID."'");

        $tmp=$tr->lookupHistories(200, 200, 1)->result();

        $jenises=array();
        $recaps=array();
        $result=array();
        if(sizeof($tmp)>0){
            foreach($tmp as $row){
                $recaps[]=array(
                    "number"=>($row->nomer),
                    "dtime"=>heSimpleTime($row->dtime),
                    "amount"=>number_format($row->transaksi_nilai),
                    "oleh"=>$row->oleh_nama,
                );
            }
            $result=$recaps;
        }
//        arrprint($result);
        $this->response($result, 200);
    }

    public function viewNeraca_get()
    {

        $cabID = $this->uri->segment(4);
        $defaultDate=isset($_GET['date'])?$_GET['date']:date("Y-m-d");
        $accountChilds = $this->config->item("accountChilds");
        $this->load->model("Mdls/"."MdlNeraca");
        $ner = new MdlNeraca();

        $ner->addFilter("cabang_id='".$cabID."'");
        $tmp = $ner->fetchBalances($defaultDate);

        $dates=$ner->fetchDates();

        $oldDate=date("Y-m-d");

        $categories = array();
        $rekenings = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if(strlen($row->kategori)>1){

                    if (!in_array($row->kategori, $categories)) {
                        $categories[] = $row->kategori;
                    }
                    if (!isset($rekenings[$row->kategori])) {
                        $rekenings[$row->kategori] = array();
                    }
                    $tmpCol = array(
                        "rekening" => $row->rekening,
                        "debet"    => $row->debet+0,
                        "kredit"   => $row->kredit+0,
                        "link"=>"",
                    );
//                    if(isset($accountChilds[$row->rekening])){
//                        $tmpCol['link'].="<a href='".base_url() . "Ledger/viewBalances_l1/".$accountChilds[$row->rekening]."/".$row->rekening."'><span class='fa fa-clone'></span></a>";
//                    }
//                    $tmpCol['link'].="<span class='pull-right'><a href='".base_url() . "Ledger/viewMoves_l1/Rekening/".$row->rekening."'><span class='glyphicon glyphicon-time'></span></a></span>";

                    $rekenings[$row->kategori][]=$tmpCol;
                }


            }
            reset($dates);
            $oldDate=key($dates);
        }

        $this->response($rekenings, 200);

    }

    public function seeBranches_get()
    {

        $this->load->model("Mdls/MdlCabang");
        $tr=new MdlCabang();

        $tmp=$tr->lookupAll()->result();

        if(sizeof($tmp)>0){
            foreach($tmp as $row){
                $result[$row->id]=$row->nama;
            }
        }else{
            $result=array();
        }

//        $result="";
        $this->response($result, 200);
    }


}