<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */


class ValueGate
{

    protected $configUiJenis;
    // protected $configLayout;
    protected $configCoreJenis;
    protected $ppnFactor;
    protected $configValuesJenis;
    protected $sessionLogin;


    public function getSessionLogin()
    {
        return $this->sessionLogin;
    }

    public function setSessionLogin($sessionLogin)
    {
        $this->sessionLogin = $sessionLogin;
    }

    public function getConfigValuesJenis()
    {
        return $this->configValuesJenis;
    }

    public function setConfigValuesJenis($configValuesJenis)
    {
        $this->configValuesJenis = $configValuesJenis;
    }

    public function getPpnFactor()
    {
        return $this->ppnFactor;
    }

    public function setPpnFactor($ppnFactor)
    {
        $this->ppnFactor = $ppnFactor;
    }

    public function getConfigUiJenis()
    {
        return $this->configUiJenis;
    }

    public function setConfigUiJenis($configUiJenis)
    {
        $this->configUiJenis = $configUiJenis;
    }

    public function getConfigCoreJenis()
    {
        return $this->configCoreJenis;
    }

    public function setConfigCoreJenis($configCoreJenis)
    {
        $this->configCoreJenis = $configCoreJenis;
    }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();
    }

    public function buildValue($jenisTr, $id = 0, $initMasterValues, $modul = "")
    {
        cekMErah(__LINE__);
        $ppnFactors = array();
        if (isset($this->ppnFactor)) {

        }
        else {
            switch ($jenisTr) {
                case "583":
                case "9999":
                case "999":
                case "749":
                    break;
                case "582":
                case "982":
                case "466":
                case "467":

                    matiHEre(" $jenisTr ppn factor belum diset oleh selector vg->setppnFactor() pada selector yang digunakan");
                    break;
            }
            // matiHEre(" $jenisTr ppn factor belum diset oleh selector vg->setppnFactor() pada selector yang digunakan");
        }
        $cCode = cCodeBuilderMisc($jenisTr);
        $configCoreJenis = isset($this->configCoreJenis) ? $this->configCoreJenis : matiHere("configCoreJenis silahkan di set untuk " . __METHOD__ . " " . __LINE__);
        $configUiJenis = isset($this->configUiJenis) ? $this->configUiJenis : matiHere("configUiJenis silahkan di set untuk " . __METHOD__ . " " . __LINE__);
        $configValuesJenis = isset($this->configValuesJenis) ? $this->configValuesJenis : matiHere("configValuesJenis silahkan di set untuk " . __METHOD__ . " " . __LINE__);
        $modul = $modul != "" ? $modul . "/" : "";

        // region DTP Auto-selection
        $shopingCartFakturInsentif = isset($configUiJenis['addMainSourceInsentif'][1]) ? $configUiJenis['addMainSourceInsentif'][1] : array();
        if (isset($shopingCartFakturInsentif['defaultKey']) && !isset($_SESSION[$cCode]['main']['dtp_active'])) {
            $defaultKey = $shopingCartFakturInsentif['defaultKey'];
            if (isset($_SESSION[$cCode]['main'][$defaultKey]) && $_SESSION[$cCode]['main'][$defaultKey] != "") {
                $_SESSION[$cCode]['main']['dtp_active'] = 1;
                $_SESSION[$cCode]['main']['ppn_dpt'] = 0; // set 0 for manual input
                $_SESSION[$cCode]['main']['pajak_ditanggung_pemerintah'] = 0; // set 0 to match rate
            }
        }
        // endregion
        $keyMasters = array(
            "olehID" => array(
                "required" => true,
            ),
            "olehName" => array(
                "required" => true,
            ),
            "placeID" => array(),
            "placeName" => array(),
            "cabangID" => array(),
            "cabangName" => array(),
            "gudangID" => array(),
            "gudangName" => array(),
            //"ppnFactor" => array(),
            "divID" => array(),
            "divName" => array(),
            "tokoID" => array(),
            "tokoNama" => array(),
            "jenisTr" => array(),
            "jenisTrMaster" => array(),
            "jenisTrTop" => array(),
            "jenisTrName" => array(),
            "stepNumber" => array(),
            "stepCode" => array(),
            "dtime" => array(),
            "fulldate" => array(),
        );
        $initMaster = array();
        foreach ($keyMasters as $keyMaster => $params) {
            $valMaster = isset($initMasterValues[$keyMaster]) ? $initMasterValues[$keyMaster] : "";
            if (isset($params['required']) && ($params['required'] == true)) {

            }

            $initMaster[$keyMaster] = $valMaster;
        }

        if (isset($this->ppnFactor)) {
//            $initMaster['ppnFactor'] = $this->ppnFactor;
        }
        // $initMaster = array(
        //     "olehID"        => $this->session->login['id'],
        //     "olehName"      => $this->session->login['nama'],
        //     "placeID"       => $this->session->login['cabang_id'],
        //     "placeName"     => $this->session->login['cabang_nama'],
        //     "divID"         => isset($this->session->login['div_id']) ? $this->session->login['div_id'] : 0,
        //     "divName"       => isset($this->session->login['div_nama']) ? $this->session->login['div_nama'] : 0,
        //     "cabangID"      => $this->session->login['cabang_id'],
        //     "cabangName"    => $this->session->login['cabang_nama'],
        //     "gudangID"      => $this->session->login['gudang_id'],
        //     "gudangName"    => $this->session->login['gudang_nama'],
        //     "jenis_usaha"   => isset($this->session->login['jenis_usaha']) ? $this->session->login['jenis_usaha'] : '-',
        //     "jenisTr"       => $this->jenisTr,
        //     "jenisTrMaster" => $this->jenisTr,
        //     "jenisTrTop"    => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][1]['target'],
        //     "jenisTrName"   => $this->jenisTrName,
        //     "stepNumber"    => $stepNum,
        //     "stepCode"      => $this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'][$stepNum]['target'],
        //     "dtime"         => date("Y-m-d H:i:s"),
        //     "fulldate"      => date("Y-m-d"),
        //     // "jenis_pajak"=>$this->session->login['jenis_usaha'],
        //     "tokoID"        => $this->session->login['toko_id'],
        //     "tokoNama"      => $this->session->login['toko_nama'],
        // );

        foreach ($initMaster as $key => $val) {
            $_SESSION[$cCode]['main'][$key] = $val;
        }

        if (!isset($_SESSION[$cCode]['tableIn_master'])) {
            $_SESSION[$cCode]['tableIn_master'] = array();
        }
        $_SESSION[$cCode]['tableIn_detail'] = array();
        $_SESSION[$cCode]['tableIn_detail2'] = array();

        /* ----------------------------------------------------------------------------
         * he_value_builder
         * ----------------------------------------------------------------------------*/
        $this->CI->load->helper("he_value_builder");

        cekBiru(":: sebelum fillValue @" . __LINE__ . " | " . __FILE__);
        fillValues_he_value_builder($jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $this->ppnFactor);
        cekLime(":: setelah fillValue @" . __LINE__ . " | " . __FILE__);
// arrPrintWebs($_SESSION[$cCode]['items']);

//         mati_disini($modul . $jenisTr . "@". __LINE__);
        /* ----------------------------------------------------------------------------
         * load methode yg lain
         * ----------------------------------------------------------------------------*/
        $this->populateValuesToItems($jenisTr);

        if (!isset($_GET['stopHere'])) {
            $select = "";
            if (isset($_GET['selector'])) {
                $select = "1";
            }
            $this->populateValues($id, $select, $jenisTr, $configCoreJenis);
        }
        else {
            //            cekPink("lewat saja, tidak masuk populateValues");
        }

//        mati_disini($jenisTr ." ". __METHOD__ ." ". $modul);
        switch ($jenisTr) {
            case "461":
//                cekMerah(base_url() . $modul . "_shoppingCart/viewCart/" . $jenisTr . "?selID=$id'");
                // if (isset($_GET['selector'])) {
//                cekLime($id);
                if ($id > 0) {
                    echo "hei";
                    // mati_disini(__FILE__);
                    echo "<script>";
                    // echo "  if(top.document.getElementById('shopping_cart')){";
//                    echo "  top.$('#shopping_cart').load('". base_url() . $modul . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
                    echo "  top.$('#shopping_cart').load('" . base_url() . $modul . "_shoppingCart/viewCart/" . $jenisTr . "?selID=$id');";
                    // echo "  }";
                    echo "</script>";
                }
                else {
                    // topReload();
                }

//                echo "<script>";
//                // echo "  if(top.document.getElementById('shopping_cart')){";
//                echo "  top.$('#shopping_cart').load('../../" . $modul . "_shoppingCart/viewCart/" . $jenisTr . "?selID=$id');";
//                // echo "  }";
//                echo "</script>";


                echo "
                <script>
                    var noid = top.$(top.$('input[id_jml=$id]')[0]).attr('noid');
                    var jml = top.$('input#jml_'+noid).val();
                    var harga = top.$('input#harga_'+noid).val();
                    top.$('input#harga_'+noid).removeAttr('style')
                    top.$('input#jml_'+noid).removeAttr('style')
                    var subTotal = (jml*1)*(harga*1);
                    top.$('span#subtotal_'+noid).html(top.addCommas(subTotal));
                    var grandTotal = 0;
                    var arrSubtotal = top.$('span[keyid=subtotal]');
                    top.jQuery.each(arrSubtotal, function(i, b){
                        grandTotal += top.removeCommas(top.$(b).html())*1;
                    });
                    top.$('input#harga').val( top.addCommas(grandTotal) );
                    
                    if(top.$('#itmCheck_$id')){
                        top.$('#itmCheck_$id').html('<i class=\"fa fa-check-square text-success text-bold\"></i>')
                    }
                </script>";

                //                topReload(100);
                break;
            default:
//                mati_disini($jenisTr ." ". __METHOD__ ." ". $modul);
//                echo "<script>";
//                 echo "  if(top.document.getElementById('shopping_cart')){";
//                echo "  top.$('#shopping_cart').load('" . base_url() . $modul . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=0');";
                // echo "  top.$('#shopping_cart').load('../../" . $modul . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id');";
//                 echo "  }";
//                echo "</script>";
                break;
        }
//        cekHijau("[selesai.....]");
    }

    public function populateValues($id = 0, $selector = '', $jenisTr)
    {
        $cCode = cCodeBuilderMisc($jenisTr);
        $configCoreJenis = isset($this->configCoreJenis) ? $this->configCoreJenis : matiHere("configCoreJenis silahkan di set untuk " . __METHOD__);
        // $configUiJenis = isset($this->configUiJenis) ? $this->configUiJenis : matiHere("configUiJenis silahkan di set untuk " . __METHOD__);

        $populatorConfig = isset($configCoreJenis['valuePopulator']) ? $configCoreJenis['valuePopulator'] : array();
        if (sizeof($populatorConfig) > 0) {
            foreach ($populatorConfig as $key => $val) {
                $newVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                $$key = $newVal;
                cekUngu(":: $key diisi dengan $newVal");
            }

            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                $nilaiAsal = $valueSrc;
//                echo "nilai sekarang: $nilaiAsal<br>";

                foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                    if ($nilaiAsal >= $iSpec[$acuanSrc]) {
                        $diambil = $iSpec[$acuanSrc];
//                        echo "[$acuanSrc] nilai diambil: $diambil ::: lebih besar samadengan yang diminta<br>";
                    }
                    else {
                        $diambil = $nilaiAsal;
//                        echo "[$acuanSrc] nilai diambil: $diambil ::: lebih kecil dari yang diminta<br>";
                    }
                    $diambil = reformatExponent($diambil);
                    if($diambil < 0){
//                        cekHitam("masuk disini: $diambil");
                        $diambil = 0;
                    }

                    $_SESSION[$cCode]['items'][$id]['nilai_bayar'] = $diambil;
                    $_SESSION[$cCode]['items'][$id]['new_sisa'] = ($iSpec['sisa'] - $diambil);
                    $nilaiAsal -= $diambil;

//                    cekHere("[lanjutkan...] [$nilaiAsal] [] ||| " . $iSpec['sisa'] . "  - $diambil ---- " . $iSpec[$acuanSrc]);
                }
            }
        }

        $select = "";
        if ($selector != '') {
            $select = "selector&";
        }

        // echo "<script>";
        // echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $this->jenisTr . "?" . $select . "selID=$id&stopHere=1');";
        // echo "</script>";
    }

    public function populateValuesToItems($jenisTr)
    {
        $cCode = cCodeBuilderMisc($jenisTr);
        $configCoreJenis = isset($this->configCoreJenis) ? $this->configCoreJenis : matiHere("configCoreJenis silahkan di set untuk " . __METHOD__);
        // $configUiJenis = isset($this->configUiJenis) ? $this->configUiJenis : matiHere("configUiJenis silahkan di set untuk " . __METHOD__);

        $populatorToItemsConfig = isset($configCoreJenis['valuePopulatorToItems']) ? $configCoreJenis['valuePopulatorToItems'] : array();
        if ((isset($populatorToItemsConfig['source'])) && (sizeof($populatorToItemsConfig['source']) > 0)) {
            foreach ($populatorToItemsConfig['source'] as $key => $val) {
                $newVal = makeValue($val, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], $static = 0);
                $$key = $newVal;
            }
        }

        if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
            if ((isset($populatorToItemsConfig['target'])) && (sizeof($populatorToItemsConfig['target']) > 0)) {
                foreach ($populatorToItemsConfig['target'] as $key => $val) {
                    foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                        foreach ($iSpec as $iKey => $iVal) {
                            $_SESSION[$cCode]['items'][$id][$iKey] = $iVal;
                            if (isset($$val) && ($$val > 0)) {
                                $_SESSION[$cCode]['items'][$id][$key] = $$val;
                            }
                        }
                    }
                }
            }
        }
    }
}
