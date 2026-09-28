<?php

error_reporting(-1);
ini_set('display_errors', 1);

class AutoDepresiasiChecker extends CI_Controller
{
    protected $diff = 45; //dalam menit

    public function getDiff()
    {
        return $this->diff;
    }

    public function setDiff($diff)
    {
        $this->diff = $diff;
    }

    public function __construct()
    {
        parent::__construct();

        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->helper("he_lib_route");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->helper('he_angka');
        $this->load->config("heAccounting");

        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlAsetDetail");
        $this->load->model("Mdls/MdlSewaDetail");
        $this->load->model("Mdls/MdlFolderAset");
        $this->load->model("Mdls/MdlMongoMother");
        $this->mongoTableList = array(
            "main" => "transaksi",
            "mainValues" => "transaksi_values",
            "detail" => "transaksi_data",
            "detailValues" => "transaksi_data_values",
            "sign" => "transaksi_sign",
            "extras" => "transaksi_extstep",
            "registry" => "transaksi_registry",
        );
//        $this->tableInConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn'] : array();
//        $this->tableInConfig_static = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static'] : array();
    }

    public function midValidate()
    {
        $fieldMidValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidators"] : array();
        $fieldMidPairedItemValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsPairedItem"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsPairedItem"] : array();
        $rowMidValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartRowMidValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartRowMidValidators"] : array();
        $fieldMidComparisonValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsComparison"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsComparison"] : array();

        $cCode = "_TR_" . $this->jenisTr;
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();
        if (sizeof($rowMidValidatorRules) > 0) {
            foreach ($rowMidValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$label is required";
                    $errRows[] = $field;
                }
            }
        }

        if (sizeof($fieldMidPairedItemValidatorRules) > 0) {
            $result = array();
            foreach ($fieldMidPairedItemValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                $result[$field] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }

            if (($result['hpp_sumber'] - $result['hpp_target']) > 0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];
                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
            elseif (($result['hpp_sumber'] - $result['hpp_target']) < -0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];
                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
        }

        //        if (sizeof($rowOptValidatorRules) > 0) {
        //            foreach ($rowOptValidatorRules as $srcName => $srcSpec) {
        //                foreach ($srcSpec as $value => $pair) {
        //                    if (isset($_SESSION[$cCode]['main'][$srcName]) && $_SESSION[$cCode]['main'][$srcName] == $value) {
        //                        foreach ($pair as $k => $v) {
        //                            if (!isset($_SESSION[$cCode]['main'][$k])) {
        //                                $errMsgs[] = "$k is required";
        //                                $errRows[] = $k;
        //                            }
        //                        }
        //                    }
        //                }
        //            }
        //        }

        if (sizeof($fieldMidValidatorRules) > 0) {
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }
                }
            }

            if (isset($_SESSION[$cCode]['rsltItems']) && sizeof($_SESSION[$cCode]['rsltItems']) > 0) {
                foreach ($_SESSION[$cCode]['rsltItems'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $name = $iSpec['nama'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label $name is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label $name must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label $name must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }
                }
            }
        }

        if (sizeof($fieldMidComparisonValidatorRules) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldMidComparisonValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                if ($_SESSION[$cCode]['main'][$field] < 0) {
                    $errMsgs[] = "$field must be >= 0";
                    $errRows[] = $field;
                }
                $labels[$label] = $field;
                $result[$label] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }
            if ($result["sumber"] > $result["target"]) {
                $labelSrc = isset($labels["sumber"]) ? $labels["sumber"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai $labelSrc lebih besar dari nilai $labelTarget.";
                $errRows[] = "test";
            }
        }
        //arrPrint($result);
        //arrPrint($labels);
        //mati_disini();
        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);
            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }
            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
        //        else {
        //
        //            $actionTarget = "top.BootstrapDialog.show(                                   {
        //                                       title:'preview',
        //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
        //                                        draggable:false,
        //                                        size:top.BootstrapDialog.SIZE_WIDE,
        //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
        //                                        closable:true,
        //                                        }
        //                                        );";
        //
        //            echo "<html>";
        //            echo "<head>";
        //            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //            echo "</head>";
        //            echo "<body onload=\"$actionTarget\">";
        //            echo "</body>";
        //
        //        }
    }

    public function unionValidate()
    {
        $unionValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartUnionValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartUnionValidators"] : array();
        $cCode = "_TR_" . $this->jenisTr;
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();

        if (sizeof($unionValidatorRules) > 0) {
            $result = array();
            foreach ($unionValidatorRules as $uSpec) {
                foreach ($uSpec as $field => $label) {
                    if (!isset($_SESSION[$cCode]['main'][$field])) {
                        //                        $errMsgs[] = "$field value is required";
                        //                        $errRows[] = $field;
                        $result[$field] = "$label value is required";
                    }
                }
                //                $result[$field] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }
            if (sizeof($result) > 1) {
                foreach ($result as $field => $label) {
                    $errMsgs[] = $label;
                    $errRows[] = $field;
                }
            }

        }


        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);
            //            print_r($_SESSION['errMsg']);
            //            echo "<script>";
            //            echo "top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id','shopping_cart');";
            //            echo "</script>";
            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }

            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
        //        else {
        //
        //            $actionTarget = "top.BootstrapDialog.show(                                   {
        //                                       title:'preview',
        //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
        //                                        draggable:false,
        //                                        size:top.BootstrapDialog.SIZE_WIDE,
        //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
        //                                        closable:true,
        //                                        }
        //                                        );";
        //
        //            echo "<html>";
        //            echo "<head>";
        //            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //            echo "</head>";
        //            echo "<body onload=\"$actionTarget\">";
        //            echo "</body>";
        //
        //        }
    }

    public function index_ori()
    {

        //region batas aset
        $this->load->model("Mdls/MdlSetupDepresiasiAssetsProduction");
        $this->load->model("Mdls/MdlSetupDepresiasiSewaProduction");
        $d = new MdlSetupDepresiasiAssetsProduction();
        $d2 = new MdlSetupDepresiasiSewaProduction();

        $f = new MdlFolderAset(); //aset wujud dan sewa sama2 pakai ini

        $folders = $f->lookupAll()->result();
        $data = $d->lookupAll()->result();
        $data2 = $d2->lookupAll()->result();

        $data = array_merge($data,$data2);

        $a = new MdlAsetDetail(); //aset wujud dan sewa sudah termasuk
        $detailItems = $a->lookupAll()->result();

        $listedCabang = array();
        $finalDataItems = array();
        foreach ($data as $dataTmp) {
            $cabang_id = $dataTmp->cabang_id;
            $gudang_id = $dataTmp->gudang_id;
            $listedCabang[$cabang_id] = $gudang_id;
        }
        $this->load->model("Mdls/MdlGudangDefault");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/MdlMongoMother");
        $c = new MdlCabang();
        $g = new MdlGudangDefault();
        $cabangData = array();
        $branchData = array();
        foreach ($listedCabang as $cID => $bID) {
            $c->addFilter("id='$cID'");
            $temCabang = $c->lookupAll()->result();
            $g->addFilter("cabang_id='$cID'");
            $tempBranch = $g->lookupAll()->result();
            foreach ($temCabang as $cabData) {
                $cabangData[$cabData->id] = $cabData->nama;
            }
            foreach ($tempBranch as $tempBranchData) {
                $branchData[$cID][$tempBranchData->id] = array(
                    "gudang_nama" => $tempBranchData->name,
                );
            }
        }

        //region cek pre value locker value
        $this->load->model("Mdls/MdlLockerValue");
        $l = new MdlLockerValue();
        $l->addFilter("jenis in ('aktiva','sewa')");
        $l->addFilter("state=active");
        $lockerValue = $l->lookupAll()->result();

        $lockerSource = array();
        if (sizeof($lockerValue) > 0) {
            foreach ($lockerValue as $lockerTmp) {
                $lockerSource[$lockerTmp->cabang_id][$lockerTmp->produk_id] = $lockerTmp;
            }
        }

        if(count($lockerSource)>0){
            foreach($lockerSource as $idCabang => $lockTmp){
                if($idCabang<>0){
                    cekHere($idCabang);
                    cekHijau($cabangData[$idCabang]);
                    arrPrintWebs($lockerSource[$idCabang]);
                }
            }
        }

        die("mati sini " . $_SERVER['REMOTE_ADDR']);


    }

    public function getLog($jenis, $t=1){
        $result = array();

        $currYear = date("Y");
        $currMonth = date("m");

        $this->load->model("Mdls/" . "MdlActivityLog");
        $hTmp = new MdlActivityLog();

        $hTmp->addFilter("category='$jenis'");
        $hTmp->addFilter("uid=-100");
        $hTmp->addFilter("uname='sys'");

        $this->db->where("month(dtime)='$currMonth'");
        $this->db->where("year(dtime)='$currYear'");

        $data = $hTmp->lookupAll()->result();

        $arrLog=array(
            "var" => "",
            "last_dtime" => "",
        );

        if( !empty($data) ){
            $logData = reset($data);
            $diff_dtime_in_hour = number_format((strtotime(date('Y-m-d H:i:s')) - strtotime($logData->dtime))/60/60, 2);
            $diff_dtime_in_minute = number_format((strtotime(date('Y-m-d H:i:s')) - strtotime($logData->dtime))/60, 0);
            $diff = $this->getDiff(); //dalam menit
            $arrLog['log_id'] = $logData->id;
            $arrLog['var'] = count($data);
            $arrLog['diff'] = $diff . " menit";
            $arrLog['dtime'] = $logData->dtime;
            $arrLog['dtime_now'] = date('Y-m-d H:i:s');
            $arrLog['last_dtime'] = strtotime($logData->dtime);
            $arrLog['now_dtime'] = strtotime(date('Y-m-d H:i:s'));
            $arrLog['diff_dtime_menit'] = $diff_dtime_in_minute;
            $arrLog['diff_dtime_jam'] = $diff_dtime_in_hour;
            $arrLog['logic'] = $diff_dtime_in_minute>$diff ? "kirim ulang" : "akan di kirim ulang dalam ".number_format((($diff-$diff_dtime_in_minute))*1,0)." menit";
        }

        $notif = count($data);
        if($t==1){
        return $notif;
    }
        else{
            return $arrLog;
        }

    }

    public function setLog($jenis){
        $this->load->model("Mdls/" . "MdlActivityLog");
        $hTmp = new MdlActivityLog();
        $tmpHData = array(
            "title" => "depresiasi checker",
            "sub_title" => "depre checker",
            "uid" => "-100",
            "uname" => "sys",
            "dtime" => date("Y-m-d H:i:s"),
            "transaksi_id" => "",
            "deskripsi_old" => "",
            "deskripsi_new" => "",
            "jenis" => "",
            "ipadd" => $_SERVER['REMOTE_ADDR'],
            "devices" => $_SERVER['HTTP_USER_AGENT'],
            "category" => "$jenis",
            "controller" => "",
            "method" => "",
            "url" => "",
        );
        $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

        return $logID;
    }

    public function amortisasi($cd=""){

        $currYear = date("Y");
        $currMonth = date("m");
        $currDay = $cd == "" ? date("d") : $cd;


        //get count amortisasi bulan ini

        $f = new MdlFolderAset(); //aset wujud dan sewa sama2 pakai ini
        $folders = $f->lookupAll()->result();
        $pihakMain = array();
        foreach ($folders as $foldersTmp) {
            $pihakMain[$foldersTmp->id] = $foldersTmp->nama;
        }

        $this->load->model("Mdls/MdlSetupDepresiasiSewaProduction");
        $d2 = new MdlSetupDepresiasiSewaProduction();
        $d2->addFilter("depresiasi=1");
        $this->db->where("repeat <= $currDay");
        $data = $d2->lookupAll()->result();
//        cekBiru($this->db->last_query());
//        cekBiru("total data Amortisasi: " . count($data) . "<br>Hingga saat ini ==========");

        $depreDatas=array();
        if(count($data)>0){
            foreach($data as $tmpDepre){
                $depreDatas[$tmpDepre->cabang_id][$tmpDepre->extern_id] = $tmpDepre;
            }
        }
//=================================================================================

        $a = new MdlAsetDetail(); //aset wujud dan sewa sudah termasuk
        $detailItems = $a->lookupAll()->result();

        $listedCabang = array();
        $finalDataItems = array();
        foreach ($data as $dataTmp) {
            $cabang_id = $dataTmp->cabang_id;
            $gudang_id = $dataTmp->gudang_id;
            $listedCabang[$cabang_id] = $gudang_id;
        }
        $this->load->model("Mdls/MdlGudangDefault");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/MdlMongoMother");
        $c = new MdlCabang();
        $g = new MdlGudangDefault();
        $cabangData = array();
        $branchData = array();
        foreach ($listedCabang as $cID => $bID) {
            $c->addFilter("id='$cID'");
            $temCabang = $c->lookupAll()->result();
            $g->addFilter("cabang_id='$cID'");
            $tempBranch = $g->lookupAll()->result();
            foreach ($temCabang as $cabData) {
                $cabangData[$cabData->id] = $cabData->nama;
            }
            foreach ($tempBranch as $tempBranchData) {
                $branchData[$cID][$tempBranchData->id] = array(
                    "gudang_nama" => $tempBranchData->name,
                );
            }
        }

        //region cek pre value locker value
        $this->load->model("Mdls/MdlLockerValue");
        $l = new MdlLockerValue();
        $l->addFilter("jenis in ('sewa')");
        $l->addFilter("state=active");
        $lockerValue = $l->lookupAll()->result();

        $lockerSource = array();
        if (sizeof($lockerValue) > 0) {
            foreach ($lockerValue as $lockerTmp) {
                $lockerSource[$lockerTmp->cabang_id][$lockerTmp->produk_id] = $lockerTmp;
            }
        }

        $lockerDepre = array();
        if(count($lockerSource)>0){
            foreach($lockerSource as $idCabang => $lockTmp){
                if($idCabang<>0){
                    if(isset($lockerSource[$idCabang]) && count($lockerSource[$idCabang])>0){
                        foreach($lockerSource[$idCabang] as $prd_id => $tmpLock){
                            if($tmpLock->nilai*1 > 1){

                                if(isset($depreDatas[$tmpLock->cabang_id][$tmpLock->produk_id])){
                                    //data dari setingan
                                    $setDepre = $depreDatas[$tmpLock->cabang_id][$tmpLock->produk_id];

                                    //data dari locker vs setingan
                                    $nilaiDepre = $setDepre->harga_perolehan / $setDepre->economic_life_time;
                                    $sisaDepre = $tmpLock->nilai;
                                    $sisaBulanDepre = $sisaDepre / $nilaiDepre;
                                    $sudahDepreBulan = $setDepre->economic_life_time - $sisaBulanDepre;
                                    $sudahDepreNilai = $setDepre->harga_perolehan - $sisaDepre;

                                    $lockerDepre[$setDepre->cabang_id][$setDepre->rekening_main][$setDepre->asset_account][$setDepre->rekening_details][$setDepre->repeat][] = array_merge((array)$tmpLock,(array)$setDepre);
                                }

                            }
                            else{
                                //arrPrint($lockerSource[$idCabang][$prd_id]);
                                //arrPrint($depreDatas[$tmpLock->cabang_id][$tmpLock->produk_id]);
                            }
                        }
                    }
                }
            }
        }

        $tbl  = "";

        $depreCounter=0;
        if(count($lockerDepre)>0){
            foreach($lockerDepre as $cab => $trD){
                foreach($trD as $mainID => $arrDepre_2){
                    foreach($arrDepre_2 as $asset_account => $arrDepre_1){
                        foreach($arrDepre_1 as $rek_details => $arrDepre_0){
                            foreach($arrDepre_0 as $tglDepre => $arrDepre){
                    $tbl  .= "<table>";
                    $tbl .= "<thead>";

                                $tbl .= "<tr style='background: #98faf6;text-transform: uppercase;white-space: nowrap;'>";
                                $tbl .= "<th colspan='13'>".$mainID . " - " . $cabangData[$cab]." - (tgl " . $tglDepre.")</th>";
                    $tbl .= "</tr>";

                                $tbl .= "<tr style='background: #98faf6;text-transform: uppercase;white-space: nowrap;'>";
                                $tbl .= "<th>urut</th>";
                    $tbl .= "<th>nama asset</th>";
                    $tbl .= "<th>main rek</th>";
                    $tbl .= "<th>asset account</th>";
                    $tbl .= "<th>asset details</th>";
                    $tbl .= "<th>nilai perolehan</th>";
                    $tbl .= "<th>life time</th>";
                    $tbl .= "<th>nilai depre</th>";
                    $tbl .= "<th>sudah depre bulan</th>";
                    $tbl .= "<th>sudah depre nilai</th>";
                    $tbl .= "<th>nilai sisa depre bulan</th>";
                    $tbl .= "<th>nilai sisa depre nilai</th>";
                                $tbl .= "<th>tgl depre</th>";
                    $tbl .= "</tr>";

                    $tbl .= "</thead>";
                    $tbl .= "<tbody>";

                                $total_nilai_depre = 0;
                                $total_perolehan = 0;
                                $total_sudah_depre = 0;
                                $total_sisa_depre = 0;
                                $urut=1;
                    foreach($arrDepre as $tmpDep){
                        $nilaiDepre = $tmpDep['harga_perolehan'] / $tmpDep['economic_life_time'];
                        $sisaDepre = $tmpDep['nilai'];
                        $sisaBulanDepre = $sisaDepre / $nilaiDepre;
                        $sudahDepreBulan = $tmpDep['economic_life_time'] - $sisaBulanDepre;
                        $sudahDepreNilai = $tmpDep['harga_perolehan'] - $sisaDepre;

                                    $tbl .= "<tr style='white-space: nowrap;'>";
                                    $tbl .= "<td>$urut</td>";
                        $tbl .= "<td>".$tmpDep['nama']."</td>";
                                    $tbl .= "<td align='center'>".$tmpDep['rekening_main']."</td>";
                                    $tbl .= "<td align='center'>".$tmpDep['asset_account']."</td>";
                                    $tbl .= "<td align='center'>".$tmpDep['rekening_details']."</td>";
                                    $tbl .= "<td style='background: #48ff90' align='right'><span style='float: left;'>Rp.</span>".number_format($tmpDep['harga_perolehan'])."</td>";
                                    $tbl .= "<td align='center'>".number_format($tmpDep['economic_life_time'])."</td>";
                                    $tbl .= "<td style='background: #48ff90' align='right'><span style='float: left;'>Rp.</span>".number_format($nilaiDepre)."</td>";
                                    $tbl .= "<td align='center'>".number_format($sudahDepreBulan)."</td>";
                                    $tbl .= "<td style='background: #48ff90' align='right'><span style='float: left;'>Rp.</span>".number_format($sudahDepreNilai)."</td>";
                                    $tbl .= "<td align='center'>".number_format($sisaBulanDepre)."</td>";
                                    $tbl .= "<td style='background: #48ff90' align='right'><span style='float: left;'>Rp.</span>".number_format($sisaDepre)."</td>";
                                    $tbl .= "<td align='center'>".$tmpDep['repeat']."</td>";
                        $tbl .= "</tr>";

                                    $total_nilai_depre += $nilaiDepre*1;
                                    $total_perolehan += $tmpDep['harga_perolehan']*1;
                                    $total_sudah_depre += $sudahDepreNilai*1;
                                    $total_sisa_depre += $sisaDepre*1;

                                    $urut++;
                    }

                                $tbl .= "<tfoot>";

                                $tbl .= "<tr style='background: #fff73c'>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th align='right'><span style='float: left;'>Rp.</span>".number_format($total_perolehan)."</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th align='right'><span style='float: left;'>Rp.</span>".number_format($total_nilai_depre)."</th>";
                                $tbl .= "<th align='center'>-</th>";
                                $tbl .= "<th align='right'><span style='float: left;'>Rp.</span>".number_format($total_sudah_depre)."</th>";
                                $tbl .= "<th align='center'>-</th>";
                                $tbl .= "<th align='right'><span style='float: left;'>Rp.</span>".number_format($total_sisa_depre)."</th>";
                                $tbl .= "<th align='center'>-</th>";
                                $tbl .= "</tr>";

                                $tbl .= "</tfoot>";

                    $tbl .= "<tbody>";
                    $tbl .= "</table>";
                    $tbl .= "<div>&nbsp;</div>";
                    $tbl .= "<div>&nbsp;</div>";

                                $depreCounter++;
                }
                        }
                    }
                }
            }
        }

        $tbl .= "
<style>
    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    td, th {
      border: 1px solid #dddddd;
      padding: 8px;
    }
</style>";

//        echo($tbl);

        $amor = count($lockerDepre);
        return $amor;
    }

    public function depresiasi($cd=""){

        $currYear = date("Y");
        $currMonth = date("m");
        $currDay = $cd == "" ? date("d") : $cd;

        //get count depresiasi bulan ini

        $f = new MdlFolderAset(); //aset wujud dan sewa sama2 pakai ini
        $folders = $f->lookupAll()->result();
        $pihakMain = array();
        foreach ($folders as $foldersTmp) {
            $pihakMain[$foldersTmp->id] = $foldersTmp->nama;
        }

        $this->load->model("Mdls/MdlSetupDepresiasiAssetsProduction");
        $d = new MdlSetupDepresiasiAssetsProduction();
        $d->addFilter("depresiasi=1");
        $this->db->where("repeat <= $currDay");
        $data = $d->lookupAll()->result();

//        cekUngu($this->db->last_query());
//        cekUngu("total data Depresiasi: " . count($data) . "<br>Hingga saat ini ==========");

        $depreDatas=array();
        if(count($data)>0){
            foreach($data as $tmpDepre){
                $depreDatas[$tmpDepre->cabang_id][$tmpDepre->extern_id] = $tmpDepre;
            }
        }
//=================================================================================

        $a = new MdlAsetDetail(); //aset wujud dan sewa sudah termasuk
        $detailItems = $a->lookupAll()->result();

        $listedCabang = array();
        $finalDataItems = array();
        foreach ($data as $dataTmp) {
            $cabang_id = $dataTmp->cabang_id;
            $gudang_id = $dataTmp->gudang_id;
            $listedCabang[$cabang_id] = $gudang_id;
        }
        $this->load->model("Mdls/MdlGudangDefault");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/MdlMongoMother");
        $c = new MdlCabang();
        $g = new MdlGudangDefault();
        $cabangData = array();
        $branchData = array();
        foreach ($listedCabang as $cID => $bID) {
            $c->addFilter("id='$cID'");
            $temCabang = $c->lookupAll()->result();
            $g->addFilter("cabang_id='$cID'");
            $tempBranch = $g->lookupAll()->result();
            foreach ($temCabang as $cabData) {
                $cabangData[$cabData->id] = $cabData->nama;
            }
            foreach ($tempBranch as $tempBranchData) {
                $branchData[$cID][$tempBranchData->id] = array(
                    "gudang_nama" => $tempBranchData->name,
                );
            }
        }

        //region cek pre value locker value
        $this->load->model("Mdls/MdlLockerValue");
        $l = new MdlLockerValue();
        $l->addFilter("jenis in ('aktiva')");
        $l->addFilter("state=active");
        $lockerValue = $l->lookupAll()->result();

        $lockerSource = array();
        if (sizeof($lockerValue) > 0) {
            foreach ($lockerValue as $lockerTmp) {
                $lockerSource[$lockerTmp->cabang_id][$lockerTmp->produk_id] = $lockerTmp;
            }
        }

        $lockerDepre = array();
        if(count($lockerSource)>0){
            foreach($lockerSource as $idCabang => $lockTmp){
                if($idCabang<>0){
                    if(isset($lockerSource[$idCabang]) && count($lockerSource[$idCabang])>0){
                        foreach($lockerSource[$idCabang] as $prd_id => $tmpLock){
                            if($tmpLock->nilai*1 > 1){

                                if(isset($depreDatas[$tmpLock->cabang_id][$tmpLock->produk_id])){
                                    //data dari setingan
                                    $setDepre = $depreDatas[$tmpLock->cabang_id][$tmpLock->produk_id];

                                    //data dari locker vs setingan
                                    $nilaiDepre = $setDepre->harga_perolehan / $setDepre->economic_life_time;
                                    $sisaDepre = $tmpLock->nilai;
                                    $sisaBulanDepre = $sisaDepre / $nilaiDepre;
                                    $sudahDepreBulan = $setDepre->economic_life_time - $sisaBulanDepre;
                                    $sudahDepreNilai = $setDepre->harga_perolehan - $sisaDepre;

                                    $lockerDepre[$setDepre->cabang_id][$setDepre->rekening_main][$setDepre->asset_account][$setDepre->rekening_details][$setDepre->repeat][] = array_merge((array)$tmpLock,(array)$setDepre);
                                }

                            }
                            else{
                                //arrPrint($lockerSource[$idCabang][$prd_id]);
                                //arrPrint($depreDatas[$tmpLock->cabang_id][$tmpLock->produk_id]);
                            }
                        }
                    }
                }
            }
        }
        
        $tbl  = "";

        $depreCounter=0;
        if(count($lockerDepre)>0){
            foreach($lockerDepre as $cab => $trD){
                foreach($trD as $mainID => $arrDepre_2){
                    foreach($arrDepre_2 as $asset_account => $arrDepre_1){
                        foreach($arrDepre_1 as $rek_details => $arrDepre_0){
                            foreach($arrDepre_0 as $tglDepre => $arrDepre){
                                $tbl  .= "<table>";
                                $tbl .= "<thead>";

                                $tbl .= "<tr style='background: #98faf6;text-transform: uppercase;white-space: nowrap;'>";
                                $tbl .= "<th colspan='13'>".$pihakMain[$asset_account] . " - " . $cabangData[$cab]." - (tgl " . $tglDepre.")</th>";
                                $tbl .= "</tr>";

                                $tbl .= "<tr style='background: #98faf6;text-transform: uppercase;white-space: nowrap;'>";
                                $tbl .= "<th>urut</th>";
                                $tbl .= "<th>nama asset</th>";
                                $tbl .= "<th>main rek</th>";
                                $tbl .= "<th>asset account</th>";
                                $tbl .= "<th>asset details</th>";
                                $tbl .= "<th>nilai perolehan</th>";
                                $tbl .= "<th>life time</th>";
                                $tbl .= "<th>nilai depre</th>";
                                $tbl .= "<th>sudah depre bulan</th>";
                                $tbl .= "<th>sudah depre nilai</th>";
                                $tbl .= "<th>nilai sisa depre bulan</th>";
                                $tbl .= "<th>nilai sisa depre nilai</th>";
                                $tbl .= "<th>tgl depre</th>";
                                $tbl .= "</tr>";

                                $tbl .= "</thead>";
                                $tbl .= "<tbody>";

                                $total_nilai_depre = 0;
                                $total_perolehan = 0;
                                $total_sudah_depre = 0;
                                $total_sisa_depre = 0;
                                $urut=1;
                                foreach($arrDepre as $tmpDep){
                                    $nilaiDepre = $tmpDep['harga_perolehan'] / $tmpDep['economic_life_time'];
                                    $sisaDepre = $tmpDep['nilai'];
                                    $sisaBulanDepre = $sisaDepre / $nilaiDepre;
                                    $sudahDepreBulan = $tmpDep['economic_life_time'] - $sisaBulanDepre;
                                    $sudahDepreNilai = $tmpDep['harga_perolehan'] - $sisaDepre;

                                    $tbl .= "<tr style='white-space: nowrap;'>";
                                    $tbl .= "<td>$urut</td>";
                                    $tbl .= "<td>".$tmpDep['nama']."</td>";
                                    $tbl .= "<td align='center'>".$tmpDep['rekening_main']."<br>".""."</td>";
                                    $tbl .= "<td align='center'>".$tmpDep['asset_account']."<br>".$pihakMain[$tmpDep['asset_account']]."</td>";
                                    $tbl .= "<td align='center'>".$tmpDep['rekening_details']."</td>";
                                    $tbl .= "<td style='background: #48ff90' align='right'><span style='float: left;'>Rp.</span>".number_format($tmpDep['harga_perolehan'])."</td>";
                                    $tbl .= "<td align='center'>".number_format($tmpDep['economic_life_time'])."</td>";
                                    $tbl .= "<td style='background: #48ff90' align='right'><span style='float: left;'>Rp.</span>".number_format($nilaiDepre)."</td>";
                                    $tbl .= "<td align='center'>".number_format($sudahDepreBulan)."</td>";
                                    $tbl .= "<td style='background: #48ff90' align='right'><span style='float: left;'>Rp.</span>".number_format($sudahDepreNilai)."</td>";
                                    $tbl .= "<td align='center'>".number_format($sisaBulanDepre)."</td>";
                                    $tbl .= "<td style='background: #48ff90' align='right'><span style='float: left;'>Rp.</span>".number_format($sisaDepre)."</td>";
                                    $tbl .= "<td align='center'>".$tmpDep['repeat']."</td>";
                                    $tbl .= "</tr>";

                                    $total_nilai_depre += $nilaiDepre*1;
                                    $total_perolehan += $tmpDep['harga_perolehan']*1;
                                    $total_sudah_depre += $sudahDepreNilai*1;
                                    $total_sisa_depre += $sisaDepre*1;

                                    $urut++;
                                }

                                $tbl .= "<tfoot>";

                                $tbl .= "<tr style='background: #fff73c'>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th align='right'><span style='float: left;'>Rp.</span>".number_format($total_perolehan)."</th>";
                                $tbl .= "<th>-</th>";
                                $tbl .= "<th align='right'><span style='float: left;'>Rp.</span>".number_format($total_nilai_depre)."</th>";
                                $tbl .= "<th align='center'>-</th>";
                                $tbl .= "<th align='right'><span style='float: left;'>Rp.</span>".number_format($total_sudah_depre)."</th>";
                                $tbl .= "<th align='center'>-</th>";
                                $tbl .= "<th align='right'><span style='float: left;'>Rp.</span>".number_format($total_sisa_depre)."</th>";
                                $tbl .= "<th align='center'>-</th>";
                                $tbl .= "</tr>";

                                $tbl .= "</tfoot>";

                                $tbl .= "<tbody>";
                                $tbl .= "</table>";
                                $tbl .= "<div>&nbsp;</div>";
                                $tbl .= "<div>&nbsp;</div>";

                                $depreCounter++;
                            }
                        }
                    }
                }
            }
        }

        $tbl .= "
        <style>
            table {
              font-family: arial, sans-serif;
              border-collapse: collapse;
              width: 100%;
            }
            td, th {
              border: 1px solid #dddddd;
              padding: 8px;
            }
        </style>";

        echo($tbl);
        
        $depre = $depreCounter;
        return $depre;
    }

    public function index()
    {

        $getDate = isset($_GET['date']) ? $_GET['date'] : date('d');
        $getMonth = isset($_GET['month']) ? $_GET['month'] : date('m');
        $getYear = isset($_GET['year']) ? $_GET['year'] : date('Y');

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $currYear = $getYear;
        $currMonth = $getMonth;
        $tr->setFilters(array());
        $tr->addFilter("jenis_master in ('8788','8786','8787')");
        $tr->addFilter("id_top>0");

        $this->db->where("month(transaksi.dtime)='$currMonth'");
        $this->db->where("year(transaksi.dtime)='$currYear'");
        $trData = $tr->lookupAll()->result();

        $classJenis = array(
            '8788' => 'amortisasi',
            '8786' => 'depresiasi', //pusat
            '8787' => 'depresiasi', //cabang

            '8788r' => 'req_amortisasi',
            '8786r' => 'req_depresiasi', //pusat
            '8787r' => 'req_depresiasi', //cabang
        );

        $result=array();
        if(count($trData)>0){
            foreach($trData as $tData){
                $result[$classJenis[$tData->jenis_master]][$tData->cabang_id][$tData->jenis][$tData->id] = $tData->transaksi_nilai;
            }
        }

        $this->jadwal_penyusutan();

        //jml amortisasi / depresiasi sesuai tgl saat ini
        $jml_amortisasi = $this->amortisasi($getDate);
        $jml_depresiasi = $this->depresiasi($getDate)*1 - 1;

        $jml_trx_amortisasi = 0;

        if(isset($result['amortisasi'])){
            foreach($result['amortisasi'] as $cabID => $ad){
                foreach($ad as $jtr => $trj){
                    $jml_trx_amortisasi += count($trj);
                }
            }
        }

        $jml_trx_depresiasi = 0;

        if(isset($result['depresiasi'])){
            foreach($result['depresiasi'] as $cabID => $ad){
                foreach($ad as $jtr => $trj){
                    $jml_trx_depresiasi += count($trj);
                }
            }
        }

        if( $jml_trx_amortisasi != $jml_amortisasi ){
            //send notif jika amortisasi belum terbit
            if( $this->getLog('amortisasi') == 0 ){
//                $sendTele = kirim_tele("amortisasi $currYear-$currMonth belum terbit. " . date('Y-m-d H:i:s'), '-1001457771609','2006804072:AAF1qUtWoF88THjnMdDkXmPAhY0XnRYaGPs'); //bot token mayagrahakencana, chat_id Group teknis
                cekMerah("belum ada amortisasi, notifikasi warning telah dikirim...");
                cekUngu( $this->getLog('amortisasi', 'array') );
                $this->setLog('amortisasi check');
            }
            else{
                $arrLog = $this->getLog('amortisasi', 'array');
                if(!empty($arrLog)){
                    $diff = $this->getDiff(); //dalam menit
                    if($arrLog['diff_dtime_menit']>=$diff){
                        //kirim ulang
                        cekHitam("kirim ulang");
//                $sendTele = kirim_tele("amortisasi $currYear-$currMonth belum terbit. " . date('Y-m-d H:i:s'), '-1001457771609','2006804072:AAF1qUtWoF88THjnMdDkXmPAhY0XnRYaGPs'); //bot token mayagrahakencana, chat_id Group teknis
                        $this->setLog('amortisasi check');
                    }
                    else{
                        cekMerah("belum ada amortisasi diterbitkan, sudah di notifikasi");
                        cekUngu( $this->getLog('amortisasi', 'array') );
                    }
                }
            }
        }
        else{

            cekHere("Jml amortisasi yg telah terbit: $jml_trx_amortisasi");
            cekHijau("Jml amortisasi yg telah terbit: $jml_trx_amortisasi");
            cekOrange("jml amortisasi dr setting: $jml_amortisasi");

        }

        if( $jml_trx_depresiasi != $jml_depresiasi ){
            //send notif jika depresiasi belum terbit
            if( $this->getLog('depresiasi') == 0 ){
//                $sendTele = kirim_tele("depresiasi $currYear-$currMonth belum terbit. " . date('Y-m-d H:i:s'), '-1001457771609','2006804072:AAF1qUtWoF88THjnMdDkXmPAhY0XnRYaGPs'); //bot token mayagrahakencana, chat_id Group teknis
                cekMerah("depresiasi terbit $jml_trx_depresiasi, seharusnya ada $jml_depresiasi, notifikasi warning telah dikirim... LINE: " . __LINE__);
//                cekUngu( $this->getLog('depresiasi', 'array') );
                $this->setLog('depresiasi');
            }
            else{
                $arrLog = $this->getLog('depresiasi', 'array');
                if(!empty($arrLog)){
                    $diff = $this->getDiff(); //dalam menit
                    if($arrLog['diff_dtime_menit']>=$diff){
                        //kirim ulang
                        cekHitam("kirim ulang");
    //                $sendTele = kirim_tele("depresiasi $currYear-$currMonth belum terbit. " . date('Y-m-d H:i:s'), '-1001457771609','2006804072:AAF1qUtWoF88THjnMdDkXmPAhY0XnRYaGPs'); //bot token mayagrahakencana, chat_id Group teknis
                        $this->setLog('depresiasi');
                    }
                    else{
                        cekMerah("depresiasi terbit $jml_trx_depresiasi, seharusnya ada $jml_depresiasi, notifikasi warning telah dikirim... LINE: " . __LINE__);
                        foreach($trData as $i => $dtr){
                            cekHijau($dtr->jenis_master . " || " . $dtr->id . " || " . $dtr->nomer);
                            cekUngu($dtr->jenis . " || " . $dtr->id . " || " . $dtr->nomer);
                        }
                        $this->setLog('depresiasi');
                    }
                }
            }
        }
        else{
            cekHijau("Jml depresiasi yg telah terbit: $jml_trx_depresiasi");
            cekOrange("jml depresiasi dr setting: $jml_depresiasi");
//            arrPrint($result);
        }

//            clear, amortisasi dan depresiasi sudah di terbitkan
//            arrPrint($result);
        // $counterAmortisasi = isset($result['amortisasi']) ? count($result['amortisasi']) : 0;
        // $counterDepresiasi = isset($result['depresiasi']) ? count($result['depresiasi']) : 0;
        // cekKuning( "TRX Amortisasi: $counterAmortisasi|| Total: " . $this->amortisasi($getDate) );
        // cekMerah( "TRX Depresiasi: $counterDepresiasi|| Total: " . $this->depresiasi($getDate) );
        // cekHijau( "Log Status depresiasi: " . $this->getLog('depresiasi') );
        // cekHijau( "Log Status amortisasi: " . $this->getLog('amortisasi') );
        // if(count($result)>0){
        //     arrPrint($result);
        // }
        // else{
        //     cekMerah("tidak ada terbit depresiasi pada tanggal " . date('d/M (H:i)'));
        // }
//            cekHijau( $this->setLog() );

            //$this->setLog();
            //kirim_tele('message', 'chat_id','bot_token');

//            web_notification();
        }

    public function jadwal_penyusutan(){

        $this->load->model("Mdls/MdlCabang");
        $c = new MdlCabang();
        $cabangData = array();

        $c->addFilter("id<>0");
        $temCabang = $c->lookupAll()->result();

        foreach ($temCabang as $cabData) {
            $cabangData[$cabData->id] = $cabData->nama;
        }

        $this->load->model("Mdls/MdlSetupDepresiasiAssetsProduction");
        $d = new MdlSetupDepresiasiAssetsProduction();
        $d->addFilter("depresiasi=1");
        $d->addFilter("cabang_id<>0");
        $depresiasi = $d->lookupAll()->result();

        $this->load->model("Mdls/MdlSetupDepresiasiSewaProduction");
        $d2 = new MdlSetupDepresiasiSewaProduction();
        $d2->addFilter("depresiasi=1");
        $d2->addFilter("cabang_id<>0");
        $amortisasi = $d2->lookupAll()->result();

        $result = array(
            "amortisasi" => array(),
            "depresiasi" => array(),
        );

        $header = array(
            "extern_nama" => "nama aset",
            "cabang_id" => "ID Cabang",
            "repeat" => "Tgl Penyusutan",
            "economic_life_time" => "Bln Penyusutan",
        );

        echo "
        <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
        ";

        $dataDepre=array();
        if(count($depresiasi)>0){
            echo "<table style='font-family: arial, sans-serif; border-collapse: collapse; width: 100%;'>";
            echo "<thead>";
            echo "<tr>";
            echo "<td>cabang</td>";
            echo "<td>tgl depre</td>";
            echo "<td>jml asset</td>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $tmpSetting=array();
            foreach($depresiasi as $datas){
                $tmpSetting[$datas->cabang_id][] = $datas->id;
                $dataDepre[$datas->cabang_id][$datas->repeat] = $datas->repeat;
            }



            foreach($dataDepre as $idCab => $rep){
                sort($rep);
                $totalSetting = count($tmpSetting[$idCab]);
                echo "<tr>";
                echo "<td>" . $cabangData[$idCab] . "</td>";
                echo "<td>" . implode(',', $rep) . "</td>";
                echo "<td>" . $totalSetting . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "<hr>";
        }

        $dataAmor=array();
        if(count($amortisasi)>0){
            echo "<table border=1>";
            echo "<thead>";
            echo "<tr>";
            echo "<td>cabang</td>";
            echo "<td>tgl depre</td>";
            echo "<td>jml sewa</td>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $tmpSetting=array();
            foreach($amortisasi as $datas){
                $tmpSetting[$datas->cabang_id][] = $datas->id;
                $dataAmor[$datas->cabang_id][$datas->repeat] = $datas->repeat;
            }
            foreach($dataAmor as $idCab => $rep){
                sort($rep);
                $totalSetting = count($tmpSetting[$idCab]);
                echo "<tr>";
                echo "<td>" . $cabangData[$idCab] . "</td>";
                echo "<td>" . implode(',', $rep) . "</td>";
                echo "<td>" . $totalSetting . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "<hr>";
        }
    }
}

