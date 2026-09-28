<?php

//--include_once "MdlHistoriData.php";

class MdlCashFlowBuilder extends MdlMother
{
//    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "jenis='payment'",
        "status='1'",
        "trash='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "name" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "name",
            "inputType" => "text",
        ),
        "defPosition" => array(
            "label" => "default position",
            "type" => "int", "length" => "24", "kolom" => "defPosition",
            "inputType" => "text",
        ),
        "opPosition" => array(
            "label" => "opposite position",
            "type" => "int", "length" => "24", "kolom" => "opPosition",
            "inputType" => "text",
        ),

    );
    protected $staticData = array(
//        array(
//            "id"=>"credit",
//            "name"=>"credit",
//        ),//tak hidupin buat belanja
//        array(
//            "id"=>"cash",
//            "name"=>"cash",
//        ),
//        array(
//            "id"=>"cia",
//            "name"=>"cash in advance",
//        ),
//        array(
//            "id"=>"credit_card",
//            "name"=>"credit card",
//        ),
//        array(
//            "id"=>"debit_card",
//            "name"=>"debit card",
//        ),
    );


    protected $listedFields = array(
        "name" => "name",
//        "due_days" => "due days",
//        "status"   => "status",

    );

    public function __construct()
    {
        $this->load->helper("he_mass_table");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Mdls/MdlCashFlow");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("MdlTransaksi");

        $this->masterConfigUi = $this->config->item("heTransaksi_ui");

    }

    //region gs

//    public function getTableName()
//    {
//        return $this->tableName;
//    }
//
//    public function setTableName($tableName)
//    {
//        $this->tableName = $tableName;
//    }

    public function getIndexFields()
    {
        return $this->indexFields;
    }

    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    public function getListedFieldsForm()
    {
        return $this->listedFieldsForm;
    }

    public function setListedFieldsForm($listedFieldsForm)
    {
        $this->listedFieldsForm = $listedFieldsForm;
    }

    public function getListedFieldsHidden()
    {
        return $this->listedFieldsHidden;
    }

    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function getListedFieldsView()
    {
        return $this->listedFieldsView;
    }

    public function setListedFieldsView($listedFieldsView)
    {
        $this->listedFieldsView = $listedFieldsView;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }

    //endregion

    public function getCashflow($getDate = "")
    {
        if (strlen($getDate) == 4) {//tahunan
            $date1 = $getDate . "-01-01";
            $date2 = $getDate . "-12-31";
        }
        elseif (strlen($getDate) == 7) {//bulanan
            $date1 = isset($getDate) ? $getDate . "-01" : date("Y-m") . "-01";
            $date2 = isset($getDate) ? $getDate . "-31" : date("Y-m") . "-31";
        }
        else {
            $date1 = isset($getDate) ? $getDate . "-01" : date("Y-m") . "-01";
            $date2 = isset($getDate) ? $getDate . "-31" : date("Y-m") . "-31";
        }

//        $rekening = "010101";
        $rekening = "kas";


        //region setting cashflow
        $cf = New MdlCashFlow();
        $cf->addFilter("is_active=1");
        $cfTmp = $cf->lookupAll()->result();
        $topHeader = array();
        $topHeaderSummary = array();
        $midHeader = array();
        $midHeaderHeadCode = array();
        $midHeaderHeadCodeFlip = array();
        $isiData = array();
        foreach ($cfTmp as $cfTmpSpec) {
            if ($cfTmpSpec->head_level == 1) {
                $topHeader[$cfTmpSpec->head_code] = $cfTmpSpec->head_name;
                $topHeaderSummary[$cfTmpSpec->head_code] = "";
            }
            if ($cfTmpSpec->head_level == 2) {
                $midHeader[$cfTmpSpec->p_head_code][$cfTmpSpec->head_code] = $cfTmpSpec->head_name;
                $midHeaderHeadCode[$cfTmpSpec->head_code] = $cfTmpSpec->p_head_code;
                $midHeaderHeadCodeFlip[$cfTmpSpec->p_head_code] = $cfTmpSpec->head_code;
            }
            if ($cfTmpSpec->head_level == 3) {
                $isiData[$cfTmpSpec->rekening] = $cfTmpSpec->p_head_code;
            }

        }
        $topHeader[71] = "kenaikan (penurunan) bersih kas dan setara kas";
        $topHeader[72] = "kas dan setara kas awal periode";
        $topHeader[73] = "kas dan setara kas akhir periode";
        //endregion

        //region data cabang
        $cb = New MdlCabang();
        $cbTmp = $cb->lookupAll()->result();
        $saldoAwal = 0;
        foreach ($cbTmp as $cbSpec) {
            $cbID = $cbSpec->id;
            $cr = New ComRekening();
            $rekening = "1010010010";
            $cr->addFilter("cabang_id=$cbID");
            $cr->addFilter("fulldate>=$date1");
            $cr->addFilter("fulldate<=$date2");
            $cr->setSortBy(array("mode" => "ASC", "kolom" => "id"));
            $crCbTmp = $cr->fetchMoves($rekening);
//            showLast_query("biru");
//            cekHere($crCbTmp[0]->debet_awal . " :: " . $crCbTmp[0]->cabang_id);
            $debet_awal = isset($crCbTmp[0]->debet_awal) ? $crCbTmp[0]->debet_awal : 0;
            $saldoAwal += $debet_awal;

        }
        //endregion

        //region mutasi kas
        $cr = New ComRekening();
        $rekening = "1010010010";
        $cr->addFilter("fulldate>=$date1");
        $cr->addFilter("fulldate<=$date2");
        $crTmp = $cr->fetchMoves($rekening);
//        showLast_query("biru");
//        $saldoAwal = 0;
        $totalDebet = 0;
        $totalKredit = 0;
        $data_rekening = array();
        $data_rekening_jenisTr = array();
        $noGroup = array();
        $trInGroup = array();
        $detailTransaksiMutasi = array();

        foreach ($crTmp as $crTmpSpec) {
            $jenis = $crTmpSpec->jenis;
            $trID = $crTmpSpec->transaksi_id;
            //----------------
            $subFolder = isset($isiData[$jenis]) ? $isiData[$jenis] : 0;
            $totalDebet += $crTmpSpec->debet;
            $totalKredit += $crTmpSpec->kredit;
            //----------------
            if (!isset($data_rekening[$subFolder]["debet"])) {
                $data_rekening[$subFolder]["debet"] = 0;
            }
            if (!isset($data_rekening[$subFolder]["kredit"])) {
                $data_rekening[$subFolder]["kredit"] = 0;
            }
            $data_rekening[$subFolder]["debet"] += $crTmpSpec->debet;
            $data_rekening[$subFolder]["kredit"] += ($crTmpSpec->kredit);
            //----------------
            if (!isset($data_rekening_jenisTr[$jenis]["debet"])) {
                $data_rekening_jenisTr[$jenis]["debet"] = 0;
            }
            if (!isset($data_rekening_jenisTr[$jenis]["kredit"])) {
                $data_rekening_jenisTr[$jenis]["kredit"] = 0;
            }
            $data_rekening_jenisTr[$jenis]["debet"] += $crTmpSpec->debet;
            $data_rekening_jenisTr[$jenis]["kredit"] += ($crTmpSpec->kredit);
            //----------------
            // pembatalan 9911, 9912, mendeteksi transaksi yang dibatalkan
            switch ($jenis) {
                case "9911":
                case "9912":
//                    cekHitam(":: $trID ::");
                    $tr = New MdlTransaksi();
                    $tr->setJointSelectFields("main");
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id=$trID");
                    $regTmp = $tr->lookupDataRegistries()->result();
                    $main = blobDecode($regTmp[0]->main);
                    $jenisTr_reference = $main["jenisTr_reference"];
                    $rek_p_head_code = $isiData[$jenisTr_reference];
                    $master_head_code = $midHeaderHeadCode[$rek_p_head_code];
                    $last_p_head_code = $midHeaderHeadCodeFlip[$master_head_code];
                    $next_p_head_code = $last_p_head_code + 1;
                    $midHeader[$master_head_code][$next_p_head_code] = "Pembatalan";
//cekHere("$rek_p_head_code :: $master_head_code :: $next_p_head_code");
                    if (!isset($data_rekening[$next_p_head_code]["debet"])) {
                        $data_rekening[$next_p_head_code]["debet"] = 0;
                    }
                    if (!isset($data_rekening[$next_p_head_code]["kredit"])) {
                        $data_rekening[$next_p_head_code]["kredit"] = 0;
                    }
                    $data_rekening[$next_p_head_code]["debet"] += $crTmpSpec->debet;
                    $data_rekening[$next_p_head_code]["kredit"] += ($crTmpSpec->kredit);
                    break;
                case "999":
                    // tembakan dulu karena tidak bisa relatif, jenis tr adjustment
                    $last_p_head_code = $midHeaderHeadCodeFlip['01'];
                    $next_p_head_code = $last_p_head_code + 2;
                    $midHeader['01'][$next_p_head_code] = "Adjustment";
                    if (!isset($data_rekening[$next_p_head_code]["debet"])) {
                        $data_rekening[$next_p_head_code]["debet"] = 0;
                    }
                    if (!isset($data_rekening[$next_p_head_code]["kredit"])) {
                        $data_rekening[$next_p_head_code]["kredit"] = 0;
                    }
                    $data_rekening[$next_p_head_code]["debet"] += $crTmpSpec->debet;
                    $data_rekening[$next_p_head_code]["kredit"] += ($crTmpSpec->kredit);
                    break;
            }
            //----------------
            if ($subFolder == 0) {
                $noGroup[$jenis] = $jenis;
            }
            else {
                $trInGroup[$trID] = $trID;
            }
            //----------------
            $arrTrID_sub[$subFolder][$trID] = $trID;
            $netto = $crTmpSpec->debet - $crTmpSpec->kredit;
            $crTmpSpec->netto = $netto;
            foreach ($crTmpSpec as $key => $val) {
                $new_key = "mt_" . $key;
                switch ($new_key) {
                    case "mt_debet":
                    case "mt_kredit":
                    case "mt_netto":
//                        cekHitam(":: [$trID] $new_key [$val] :: " . $crTmpSpec->transaksi_no);
                        if (!isset($detailTransaksiMutasi[$trID][$new_key])) {
                            $detailTransaksiMutasi[$trID][$new_key] = 0;
                        }
                        $detailTransaksiMutasi[$trID][$new_key] += $val;
                        break;
                    default:
                        $detailTransaksiMutasi[$trID][$new_key] = $val;
                        break;
                }
//                $detailTransaksiMutasi[$trID][$new_key] = $val;
            }
            //----------------

        }
//arrPrintPink($detailTransaksiMutasi);
        $kenaikanKas = $totalDebet - $totalKredit;
        $topHeaderIsi[71] = $kenaikanKas;
        $topHeaderIsi[72] = $saldoAwal;
        $topHeaderIsi[73] = $saldoAwal + $totalDebet - $totalKredit;
        //endregion

        // region ke transaksi
        $tr = New MdlTransaksi();
        $tr->addFilter("id in ('" . implode("','", $trInGroup) . "')");
        $trTmp = $tr->lookupAll()->result();
        $dataTransaksiByID = array();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $modul = isset($this->masterConfigUi[$trSpec->jenis_master]['modul']) ? $this->masterConfigUi[$trSpec->jenis_master]['modul'] : "";
                $modul_path = base_url() . "$modul/";
                $trSpec->modul_path = $modul_path;
                foreach ($trSpec as $key => $val) {
//                    $new_key = "tr_".$key;
                    $new_key = $key;
                    $dataTransaksiByID[$trSpec->id][$new_key] = $val;
                }
            }
        }
        // endregion

        // region ke registry main
        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $tr->setJointSelectFields("transaksi_id, main");
        $tr->addFilter("transaksi_id in ('" . implode("','", $trInGroup) . "')");
        $regTmp = $tr->lookupDataRegistries()->result();
        $dataMainRegByID = array();
        if (sizeof($regTmp) > 0) {
            foreach ($regTmp as $regSpec) {
                $regTrID = $regSpec->transaksi_id;
                $main = blobDecode($regSpec->main);
                foreach ($main as $key => $val) {
                    $new_key = "m_" . $key;
                    $dataMainRegByID[$regTrID][$new_key] = $val;
                }
            }
        }
        // endregion

        // region menggabungkan detail mutasi, transaksi, registry main
        $detailData = array();
        foreach ($arrTrID_sub as $subGroup => $subSpec) {
            foreach ($subSpec as $tr_id) {
                $detailMutasi = isset($detailTransaksiMutasi[$tr_id]) ? $detailTransaksiMutasi[$tr_id] : array();
                $detailMainReg = isset($dataMainRegByID[$tr_id]) ? $dataMainRegByID[$tr_id] : array();
                $detailTransaksi = isset($dataTransaksiByID[$tr_id]) ? $dataTransaksiByID[$tr_id] : array();

                $detailData[$subGroup][$tr_id] = $detailTransaksi + $detailMutasi + $detailMainReg;
            }
        }
        // endregion menggabungkan detail mutasi, transaksi, registry main

        $data_rekening_new = array();
        foreach ($data_rekening as $ii => $spec) {
            $netto = $spec["debet"] - $spec["kredit"];
            $data_rekening_new[$ii]["values"] = $netto;
        }
        //------------------------------
        $topHeaderSummary["01"] = "Kas Bersih Diperoleh dari (digunakan untuk) dari Aktivitas Operasi";
        $topHeaderSummary["02"] = "Kas bersih digunakan untuk aktivitas investasi";
        $topHeaderSummary["03"] = "Kas bersih yang diperoleh (digunakan untuk) aktivitas pendanaan";
        //------------------------------


        $result = array(
            "topHeader" => $topHeader,
            "midHeader" => $midHeader,
            "dataRekening" => isset($data_rekening_new) ? $data_rekening_new : array(),
            "topHeaderIsi" => $topHeaderIsi,
            "topHeaderSummary" => $topHeaderSummary,
            "saldoAwal" => $saldoAwal,
            "selisihKas" => $kenaikanKas,
            "detailData" => $detailData,
        );
        return $result;
    }
}