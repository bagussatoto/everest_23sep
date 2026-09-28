<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/26/2018
 * Time: 5:01 PM
 */
require_once "Modul_Controller.php";

class _processPihakMain extends Modul_Controller
{

//    private $jenisTr;

    public function __construct()
    {
        parent::__construct();
//        $this->jenisTr = $this->uri->segment(4);
        $cCode = "_TR_" . $this->jenisTr;

    }

    public function select()
    {

        $shoppingCartSessionDeleter = isset($this->configUi[$this->jenisTr]['shoppingCartSessionDeleter']) ? $this->configUi[$this->jenisTr]['shoppingCartSessionDeleter'] : array();
        $pihakMainValueSrc = isset($this->configUi[$this->jenisTr]['pihakMainValueSrc2']) ? $this->configUi[$this->jenisTr]['pihakMainValueSrc2'] : array();
        $staticAccountComRekening = isset($this->configCore[$this->jenisTr]['staticAccountComRekening']) ? $this->configCore[$this->jenisTr]['staticAccountComRekening'] : array();
        $recomsValidate = isset($this->configUi[$this->jenisTr]['pihakMainRecoms']) ? $this->configUi[$this->jenisTr]['pihakMainRecoms'] : array();

        $cCode = "_TR_" . $this->jenisTr;
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $mdlName = $this->uri->segment(5);


        // region pembersih session items...
        if (sizeof($shoppingCartSessionDeleter) > 0) {
            foreach ($shoppingCartSessionDeleter as $gateName => $gSpec) {
                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                    foreach ($gSpec as $uSpec) {
                        $_SESSION[$cCode][$gateName][$uSpec] = NULL;
                        unset($_SESSION[$cCode][$gateName][$uSpec]);
                    }
                }
                else {
                    if (isset($_SESSION[$cCode][$gateName])) {
                        $_SESSION[$cCode][$gateName] = NULL;
                        unset($_SESSION[$cCode][$gateName]);
                    }
                }
            }
        }


        // endregion pembersih session items...

        $this->load->model("Mdls/" . $mdlName);
        $b = new $mdlName();
        $tmpB = $b->lookupByID($id)->result();
//        arrPrint($tmpB);


        $selectColumn = "nama";
        $arrCekKolomPihak = array(
            "pihakMainNota" => "nomer",
            "pihakMainNotaReference" => "nomer",
        );
        foreach ($arrCekKolomPihak as $keyCek => $valueCek) {
            if (isset($this->configUi[$this->jenisTr][$keyCek]) && $this->configUi[$this->jenisTr][$keyCek] == true) {
                $selectColumn = $valueCek;
                break;
            }
        }


        //-----------------------------------------------------
        $dtime_now = dtimeNow();
        $dtime_now_ex = explode(" ", $dtime_now);
        $date_now = str_replace("-", "", $dtime_now_ex[0]);
        $time_now = str_replace(":", "", $dtime_now_ex[1]);
        $bookingNumber = "$date_now" . "$time_now";
        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"]) || ($_SESSION[$cCode]["main"]["bookingNumber"] == null)) {
            $_SESSION[$cCode]["main"]["bookingNumber"] = $bookingNumber;
        }
        //-----------------------------------------------------

        $this->load->library("ValueGate");
        $vg = new ValueGate();
        $vg->setConfigUiJenis($this->configUiJenis);
        $vg->setConfigCoreJenis($this->configCoreJenis);
        $vg->setConfigValuesJenis($this->configValuesJenis);
        $vg->setPpnFactor(my_ppn_factor());

        //------
        $autoSelectPPh = isset($this->configUi[$this->jenisTr]['autoSelectPPh']) ? $this->configUi[$this->jenisTr]['autoSelectPPh'] : array();
        if (sizeof($autoSelectPPh) > 0) {
            if (isset($autoSelectPPh["enabled"]) && ($autoSelectPPh["enabled"] == true)) {
                if (isset($autoSelectPPh["resetor"]) && (sizeof($autoSelectPPh["resetor"]) > 0)) {
                    foreach ($autoSelectPPh["resetor"] as $reset) {
                        $_SESSION[$cCode]["main"][$reset] = 0;
                    }
                }
                $key_cek = $autoSelectPPh["key"];
                if (isset($_SESSION[$cCode]["main"][$key_cek])) {
                    $npwp_cek = ($_SESSION[$cCode]["main"][$key_cek] != NULL) ? "npwp" : "non_npwp";
                    //------
                    $subElName = $autoSelectPPh["tipe_biaya"][$id]["subElName"];
                    $subMdlName = $autoSelectPPh["tipe_biaya"][$id]["subMdlName"];
                    $subKey = $autoSelectPPh["tipe_biaya"][$id]["subKey"];
                    echo "<script>";
                    echo "  top.$('#result').load('" . MODUL_PATH . "_shoppingCart/fetchElement/" . $this->jenisTr . "/$subElName/$subMdlName/?key=$subKey');";
                    echo "</script>";
                    //------
                    $elName = $autoSelectPPh["tipe_biaya"][$id]["elName"];
                    $elMdlName = $autoSelectPPh["tipe_biaya"][$id]["mdlName"];
                    echo "<script>";
                    echo "  top.$('#result').load('" . MODUL_PATH . "_shoppingCart/fetchElement/" . $this->jenisTr . "/$elName/$elMdlName/?key=$npwp_cek');";
                    echo "</script>";

                    //------
//                    arrPrint($autoSelectPPh["tipe_biaya"][$id]);
//                    cekHere("[$id] [$subElName] [$subMdlName] [$subKey] || [$elName] [$elMdlName]");
                    //------
                }
                else {
                    $msg = "Konsumen wajib dipilih dahulu. code: " . __LINE__;
                    mati_disini($msg);
                }

            }
            if (isset($_SESSION[$cCode]["items"]) && (sizeof($_SESSION[$cCode]["items"]) > 0)) {
                foreach ($_SESSION[$cCode]["items"] as $ii => $spec) {
                    $_SESSION[$cCode]["items"][$ii]["harga"] = 0;
                    $_SESSION[$cCode]["items"][$ii]["nilai_kas_cn"] = 0;
                }
            }
        }
        //------

        if (sizeof($tmpB) > 0) {
            $_SESSION[$cCode]['main']['pihakMainID'] = $id;
            $_SESSION[$cCode]['main']['pihakMainCoa'] = isset($tmpB[0]->coa_code) ? $tmpB[0]->coa_code : "";
            $_SESSION[$cCode]['main']['pihakMainName'] = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";

            $stat = isset($tmpB[0]->$selectColumn) ? $tmpB[0]->$selectColumn : "";
            if (sizeof($staticAccountComRekening) > 0) {
                $_SESSION[$cCode]['main']['pihakMainAkum'] = $staticAccountComRekening[$stat];
                $_SESSION[$cCode]['main']['pihakMainAkumDetails'] = "akum penyu " . $stat;
            }


            $tmpPihakName = isset($tmpB[0]->$selectColumn) ? formatNota($selectColumn, $tmpB[0]->$selectColumn) : "";

            if (isset($tmpB[0]->name)) {
                $tmpPihakName = $tmpB[0]->name;
            }


            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            if (sizeof($recomsValidate) > 0) {
                arrPrint($recomsValidate);
                cekLIme($id);
                $mdlName = $recomsValidate['mdlName'];
                $pihakFilters = $recomsValidate['filters'];
                $selectMethod = isset($recomsValidate['selectMethod'][$id]) ? $recomsValidate['selectMethod'][$id] : false;
//                matiHEre($selectMethod." ".$id);
                $validateField = isset($recomsValidate['usedFields']) ? $recomsValidate['usedFields'] : "";
                $replaceTarget = $recomsValidate['targetField'];
                $this->load->model("Mdls/" . $mdlName);
                $m = new $mdlName();
                if (sizeof($pihakFilters) > 0) {
                    foreach ($pihakFilters as $f) {
                        $f_ex = explode("=", $f);
                        if (!isset($f_ex[1])) {
                            $f_ey = explode(">", $f_ex[0]);
                            if (substr($f_ey[1], 0, 1) == ".") {
                                $m->addFilter($f_ey[0] . ">'" . ltrim($f_ey[1], ".") . "'");
                            }
                            else {
                                $m->addFilter($f_ey[0] . ">'" . $this->session->login[$f_ey[1]] . "'");
                            }
                        }
                        else {
                            if (substr($f_ex[1], 0, 1) == ".") {
                                $m->addFilter($f_ex[0] . "='" . ltrim($f_ex[1], ".") . "'");
                            }
                            else {
//                                        matiHEre("ini".$f_ex[1]);
                                $m->addFilter($f_ex[0] . "='" . $_SESSION[$cCode]['main'][$f_ex[1]] . "'");
                            }
                        }
                    }
                }
                $temp2 = $m->lookUpAll()->result();
                if (sizeof($temp2) > 0) {
                    unset($_SESSION[$cCode]['main'][$replaceTarget]);
                    unset($_SESSION[$cCode]['main']["pphGate"]);
                    if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                        foreach ($_SESSION[$cCode]['items'] as $keyID => $items) {
                            unset($_SESSION[$cCode]['items'][$keyID][$replaceTarget]);
                        }
                    }

                    if (strlen($temp2[0]->$validateField) == 0) {
                        if ($selectMethod) {
                            $_SESSION[$cCode]['main'][$replaceTarget] = 4;
                            $_SESSION[$cCode]['main']['pphGate'] = "hutang pph23";
                        }
                        else {
                            $_SESSION[$cCode]['main'][$replaceTarget] = 10;
                            $_SESSION[$cCode]['main']['pphGate'] = "hutang pph4 ayat 2";
                        }
                    }
                    else {
                        if ($selectMethod) {
//                            matiHere($selectMethod);
                            $_SESSION[$cCode]['main'][$replaceTarget] = 2;
                            $_SESSION[$cCode]['main']['pphGate'] = "hutang pph23";
                        }
                        else {
                            $_SESSION[$cCode]['main'][$replaceTarget] = 10;
                            $_SESSION[$cCode]['main']['pphGate'] = "hutang pph4 ayat 2";
                        }
                    }

                }

            }
//            mati_disini();


            $initMasterValues = array(
                "olehID" => my_id(),
                "olehName" => my_name(),
                "sellerID" => my_id(),
                "sellerName" => my_name(),
                "placeID" => my_cabang_id(),
                "placeName" => my_cabang_nama(),
                "divID" => my_div_id(),
                "divName" => my_div_nama(),
                "cabangID" => my_cabang_id(),
                "cabangName" => my_cabang_nama(),
                "gudangID" => my_gudang_id(),
                "gudangName" => my_gudang_nama(),
                "jenis_usaha" => my_jenis_usaha(),
                "tokoID" => my_toko_id(),
                "tokoNama" => my_toko_nama(),
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $this->configUiJenis['steps'][1]['target'],
                "jenisTrName" => $this->configUiJenis['steps'][1]['label'],
//                "stepNumber" => isset($stepNum) ? $stepNum : ($stepNum = 0),
//                "stepCode" => isset($this->configUiJenis['steps'][$stepNum]['target']) ? $this->configUiJenis['steps'][$stepNum]['target'] : 0,
                "stepNumber" => 1,
                "stepCode" => isset($this->configUiJenis['steps'][1]['target']) ? $this->configUiJenis['steps'][1]['target'] : 0,
                "dtime" => dtimeNow(),
                "fulldate" => dtimeNow("Y-m-d"),
                "ppnFactor" => my_ppn_factor(),
                // "jenis_pajak"=>$this->session->login['jenis_usaha'],
            );
            $vg->buildValue($this->jenisTr, $id, $initMasterValues, $this->modul);
            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakMainName').value='" . $tmpPihakName . "';";
            echo "top.document.getElementById('pilihan_main').innerHTML='';";
            echo "</script>";
        }
        else {
            $_SESSION[$cCode]['main']['pihakMainID'] = $id;
            $_SESSION[$cCode]['main']['pihakMainName'] = "";
            if (sizeof($pihakMainValueSrc) > 0) {
                foreach ($pihakMainValueSrc as $key => $src) {
                    $_SESSION[$cCode]['main'][$key] = $tmpB[0]->$src;
                }
            }

            echo "<script>";
            echo "  top.$('#shopping_cart').load('" . MODUL_PATH . "/_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
            echo "top.document.getElementById('pihakMain').value='" . $_SESSION[$cCode]['main']['pihakMainName'] . "';";
            echo "top.document.getElementById('pilihan_main').innerHTML='';";
            echo "</script>";
        }


        /** ---------------------------------
         * untuk toggle freelance
         * ------------------------------------*/
        $this->toggleFreelance($id, $cCode);

        // region opsi freelancer
//        $optionFreelancerShow = isset($this->configUi[$this->jenisTr]['optionFreelancerShow']) ? $this->configUi[$this->jenisTr]['optionFreelancerShow'] : array();
//        if (sizeof($optionFreelancerShow) > 0) {
//            $optionFreelancerShowKey = $optionFreelancerShow["key"];
//            $optionFreelancerShowPajakOption = $optionFreelancerShow["pajakOption"];
//        }
//        if (in_array($_SESSION[$cCode]["main"][$optionFreelancerShowKey], $optionFreelancerShowPajakOption)) {
//            echo "<script>
//                top.$('#data_cabang_pos').removeClass('hidden');
//                console.log('hapus class hidden data_cabang_pos');
//            </script>";
//        }
//        else {
//            echo "<script>
//                top.$('#data_cabang_pos').addClass('hidden');
//                console.log('menambahkan class hidden data_cabang_pos');
//            </script>";
//        }
        // endregion opsi freelancer


        //------
        $autoLoadSelecttorItem = isset($this->configUi[$this->jenisTr]['autoLoadSelectorItem']) ? $this->configUi[$this->jenisTr]['autoLoadSelectorItem'] : false;
        if ($autoLoadSelecttorItem == true) {
            $pihakSelectorCaller = isset($this->configUi[$this->jenisTr]['selectorCaller']) ? $this->configUi[$this->jenisTr]['selectorCaller'] : "";
            $pihakSelectorMain = isset($this->configUi[$this->jenisTr]['selectorModel']) ? $this->configUi[$this->jenisTr]['selectorModel'] : "";
            echo "<script>";
            echo "top.getData('" . MODUL_PATH . "$pihakSelectorCaller/" . $this->jenisTr . "/$pihakSelectorMain?search=', 'pilihan_item')";
            echo "</script>";
        }
        //------

    }

    public function remove()
    {
        $cCode = "_TR_" . $this->jenisTr;
        $_SESSION[$cCode]['main']['pihakMainID'] = null;
        $_SESSION[$cCode]['main']['pihakMainName'] = null;
        $_SESSION[$cCode]['main']['pihakMdlName'] = null;
        unset($_SESSION[$cCode]['main']['pihakMainID']);
        unset($_SESSION[$cCode]['main']['pihakMainName']);
        unset($_SESSION[$cCode]['main']['pihakMdlName']);
        unset($_SESSION[$cCode]['items']);

    }

    public function toggleFreelance($id, $cCode)
    {
//        matiHEre($this->jenisTr);
        switch ($id) {
            case "5":
            case "72":
                echo "<script>            
                top.$('#pihakRulesIdDiv').addClass('hidden');               
                top.$('#pihakRules').val('');               
                
                top.$('#data_cabang_pos').removeClass('hidden');
                
        </script>";
                // clear session freelance
                $mainKeys = [
                    "pihakMainRulesID",
                    "pihakMainRulesName",
                    "freelancerDetails",
                    "freelancerDetails__label",
                    "freelancerDetails__nama",
                ];

                foreach ($mainKeys as $mainKey) {
                    unset($_SESSION[$cCode]["main"][$mainKey]);
                }
                // cekAlert("clear");
                break;
            case "7";
            case "74":
                echo "<script>            
                top.$('#pihakRulesIdDiv').addClass('hidden');               
                top.$('#pihakRules').val('');               
                
                top.$('#data_cabang_pos').removeClass('hidden');
                
        </script>";
                // clear session freelance
                $mainKeys = [
                    "pihakMainRulesID",
                    "pihakMainRulesName",
                    "freelancerDetails",
                    "freelancerDetails__label",
                    "freelancerDetails__nama",
                ];

                foreach ($mainKeys as $mainKey) {
                    unset($_SESSION[$cCode]["main"][$mainKey]);
                }
                // cekAlert("clear");
                break;
            case "6":
            case "73":
            default:
                // auto select freelance
                $this->load->model("Mdls/MdlEmployeeFreelanceCabang");
                $fl = new MdlEmployeeFreelanceCabang();
                $dd = $fl->lookupAll()->row();
                // showLast_query("merah");
                // arrPrint($dd);
                $id = $dd->id;
                $nama = $dd->nama;

//                $urlpihak = MODUL_PATH . "_processPihakMainRules/select/6677/MdlEmployeeFreelanceCabang?";
                $urlpihak = MODUL_PATH . "_processPihakMainRules/select/" . $this->jenisTr . "/MdlEmployeeFreelanceCabang?";
                echo "<script>
                top.$('#pihakRulesIdDiv').removeClass('hidden');    
                top.$('#pihakRules').val('$nama');   
                top.$('#data_cabang_pos').addClass('hidden');
                top.$.ajax({
                    url: '$urlpihak',
                    type: 'GET',
                    data: {id: '$id'},
                    
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });

        </script>";
                break;
        }
//        if ($id == 72) {
//
//        }
//        elseif ($id == 74) {
//
//        }
//        else {//73
//
//
//        }


    }
}