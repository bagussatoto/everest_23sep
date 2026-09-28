<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 1/30/2019
 * Time: 8:57 PM
 */
class _followupLiveEdit extends CI_Controller
{

    private $jenisTr;

    //region gs

    public function __construct()
    {
        parent::__construct();
    }

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    //endregion

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    public function removeItem()
    {
        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $id = $_GET['id'];

        //----------------------------
        $closedRequest = isset($this->config->item("heTransaksi_core")[$this->jenisTr]['closedRequest'][$intoStep]) ? $this->config->item("heTransaksi_core")[$this->jenisTr]['closedRequest'][$intoStep] : array();

        if (isset($_SESSION[$cCode]['items'][$id])) {
            if (isset($closedRequest['enabled']) && ($closedRequest['enabled'] == true)) {
                // menjadi items no approve
                $_SESSION[$cCode]['items_noapprove'][$id] = $_SESSION[$cCode]['items'][$id];
            }
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);
        }

        if (isset($_SESSION[$cCode]['tableIn_detail'][$id])) {
            $_SESSION[$cCode]['tableIn_detail'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail'][$id]);
        }
        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
        }
        //==beberapa bagian di MAIN harus di-reset, sesuai ....
        if (isset($_SESSION[$cCode]['main']['harga'])) {
//            unset($_SESSION[$cCode]['main']['harga']);
            $_SESSION[$cCode]['main']['harga'] = 0;
        }


        if (isset($_SESSION[$cCode]['main']['status_4'])) {
            $_SESSION[$cCode]['main']['status_4'] = 5;
        }
        if (isset($_SESSION[$cCode]['main']['trash_4'])) {
            $_SESSION[$cCode]['main']['trash_4'] = 0;
        }


        //==recover nilai HARGA master
        $_SESSION[$cCode]['main']['harga'] = 0;
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
            }
        }

        //----------------------------------------------------------------
        //==bila ada yang dihapus, maka menjadi partial
        $_SESSION[$cCode]['main']['partial'] = 1;
//        $_SESSION[$cCode]['tableIn_master']['partial'] = 1;
//        $_SESSION[$cCode]['tableIn_master_values']['partial'] = 1;
        //----------------------------------------------------------------
        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";


//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";
    }

    public function updateItemField()
    {

        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep] : null;
        $items_child = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields'] : array();
        $detailForceMain = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['detailForceMain'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['detailForceMain'][$intoStep] : array();

        //----------------------------
        $closedRequest = isset($this->config->item("heTransaksi_core")[$this->jenisTr]['closedRequest'][$intoStep]) ? $this->config->item("heTransaksi_core")[$this->jenisTr]['closedRequest'][$intoStep] : array();

        //----------------------------
        $arrPartialKey = array("jml", "qty");
        $id = $_GET['id'];
        $key = $_GET['key'];
        $val = is_numeric($_GET['val']) ? $_GET['val'] : htmlspecialchars($_GET['val']);
        if (isset($_SESSION[$cCode]['items'][$id][$key])) {
            //--------------------------------------------
            if (isset($closedRequest['enabled']) && ($closedRequest['enabled'] == true)) {
                if (in_array($key, $arrPartialKey)) {
                    foreach ($arrPartialKey as $val_orig) {
                        if (!isset($_SESSION[$cCode]['items'][$id][$val_orig . "_original"])) {
                            $_SESSION[$cCode]['items'][$id][$val_orig . "_original"] = $_SESSION[$cCode]['items'][$id][$val_orig];
                        }
                    }
                    $jml_no_approve = ($_SESSION[$cCode]['items'][$id]["jml_original"] - $val) > 0 ? $_SESSION[$cCode]['items'][$id]["jml_original"] - $val : 0;
                    $_SESSION[$cCode]['items'][$id]["jml_no_approve"] = $jml_no_approve;
                    $_SESSION[$cCode]['items'][$id]["qty_no_approve"] = $jml_no_approve;

                    //-------------------------------------------------
                    // membuat session items no approve
                    if (!isset($_SESSION[$cCode]['items_noapprove'][$id])) {
                        $_SESSION[$cCode]['items_noapprove'][$id] = $_SESSION[$cCode]['items'][$id];
                    }
                    foreach ($arrPartialKey as $val_orig) {
                        $_SESSION[$cCode]['items_noapprove'][$id][$val_orig] = $jml_no_approve;
                    }
                    if ($jml_no_approve == 0) {
                        $_SESSION[$cCode]['items_noapprove'][$id] = NULL;
                        unset($_SESSION[$cCode]['items_noapprove'][$id]);
                    }
                }
            }
            //--------------------------------------------

            $_SESSION[$cCode]['items'][$id][$key] = $val;

            //--------------------------------------------
            foreach ($itemNumLabels as $key => $label) {
                if (isset($_SESSION[$cCode]['items'][$id][$key])) {
                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                }

            }
            if (isset($_SESSION[$cCode]['items'][$id]['nett'])) {
                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
            }

            if ($subAmountConfig != null) {
                $items = $_SESSION[$cCode]['items'];
                $subtotal = makeValue($subAmountConfig, $items[$id], $items[$id], 0);
            }
            else {
                $subtotal = 0;
            }
            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($subtotal);

            if ($val < $_SESSION[$cCode]['items'][$id][$key]) {
                if (isset($_SESSION[$cCode]['main']['status_4'])) {
                    $_SESSION[$cCode]['main']['status_4'] = 5;
                }
                if (isset($_SESSION[$cCode]['main']['trash_4'])) {
                    $_SESSION[$cCode]['main']['trash_4'] = 0;
                }
                //----------------------------------------------------------------
                //==bila ada yang dirubah/edit menjadi lebih kecil, maka menjadi partial
                if (in_array($key, $arrPartialKey)) {
                    $_SESSION[$cCode]['main']['partial'] = 1;
                }
                //----------------------------------------------------------------
            }


        }
        else {
//            echo(lgShowAlert("NOT replacing $key with $val"));
        }


        //==recover nilai HARGA master
        $_SESSION[$cCode]['main']['harga'] = 0;
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id_ => $iSpec) {
                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
            }
        }

//        cekHere($id);

        //update child detail(aset)
        if (sizeof($_SESSION[$cCode]['items_child'][$id])) {

            arrPrint($_SESSION[$cCode]['items_child'][$id]);
            unset($_SESSION[$cCode]['items_child'][$id]);
            arrPrint($_SESSION[$cCode]['items_child'][$id]);
            for ($i = 1; $i <= $val; $i++) {
                foreach ($items_child as $col => $alias) {
                    $_SESSION[$cCode]['items_child'][$id][$i][$col] = isset($_SESSION[$cCode]['items'][$id][$col]) ? $_SESSION[$cCode]['items'][$id][$col] : "";
                    cekOrange($id);
                }
                cekOrange($i);
            }
            arrPrint($_SESSION[$cCode]['items_child'][$id]);

        }


        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";


//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";
    }

    public function updateMainField()
    {

        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
//        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep] : array();
//        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep] : null;

//        $id = $_GET['id'];
        $key = $_GET['key'];
//        $val = $_GET['val'];
        $val = is_numeric($_GET['val']) ? $_GET['val'] : htmlspecialchars_decode($_GET['val']);


//        if (isset($_SESSION[$cCode]['items'][$id][$key])) {
//
//            $_SESSION[$cCode]['items'][$id][$key] = $val;
//
//            foreach ($itemNumLabels as $key => $label) {
//                if (isset($_SESSION[$cCode]['items'][$id][$key])) {
//                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
//                }
//
//            }
//            if (isset($_SESSION[$cCode]['items'][$id]['nett'])) {
//                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
//            }
//
//            if ($subAmountConfig != null) {
//                $items = $_SESSION[$cCode]['items'];
//                $subtotal = makeValue($subAmountConfig, $items[$id], $items[$id], 0);
//            }
//            else {
//                $subtotal = 0;
//            }
//            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($subtotal);
//        }
//        else {
////            echo(lgShowAlert("NOT replacing $key with $val"));
//        }


        //==recover nilai HARGA master
//        $_SESSION[$cCode]['main']['harga'] = 0;
//        if (sizeof($_SESSION[$cCode]['items']) > 0) {
//            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
//                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
//            }
//        }
        if (sizeof($_SESSION[$cCode]['main']) > 0) {
            $_SESSION[$cCode]['main'][$key] = isset($val) ? $val : "";
        }

        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);

//        die();

        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";


//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";
    }

    public function updateMainFieldByStep()
    {

        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;


        $key = $_GET['key'];

        $val = is_numeric($_GET['val']) ? $_GET['val'] : htmlspecialchars_decode($_GET['val']);


        if (sizeof($_SESSION[$cCode]['main']) > 0) {
            $_SESSION[$cCode]['main'][$key][$intoStep] = isset($val) ? $val : "";
        }

        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";


//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";
    }

    public function patchElement()
    {
//        $jenisTr = $this->uri->segment(3);
//        $cCode = "_TR_" . $jenisTr;
//        $elName = $this->uri->segment(4);
//        $mdlName = $this->uri->segment(5);
//        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements'] : array();
////        arrprint($_GET);
////        echo($elName . "/" . $mdlName);
////        arrprint($elementConfigs[$elName]);
//        $id = $this->uri->segment(5);
//        $key = $_GET['key'];
//
//        $this->load->model("Mdls/" . $mdlName);
//        $oo = new $mdlName();
//        $oo->addFilter("id='$key'");
//        $tmp = $oo->lookupAll()->result();
////        arrprint($tmp);die();
//        $contents = array();
//        if (sizeof($tmp) > 0) {
//            foreach ($tmp as $row) {
//                if (isset($elementConfigs[$elName]['usedFields']) && sizeof($elementConfigs[$elName]['usedFields']) > 0) {
//                    foreach ($elementConfigs[$elName]['usedFields'] as $src => $label) {
//                        $contents[$src] = $row->$src;
//                    }
//                }
//            }
//
//
//        }
//
//        if (!isset($_SESSION[$cCode]['main_elements'])) {
//            $_SESSION[$cCode]['main_elements'] = array();
//        }
//        $_SESSION[$cCode]['main_elements'][$elName] = array(
//            "name"            => $elName,
//            "label"           => $elementConfigs[$elName]['label'],
//            "key"             => $key,
//            "mdl_name"        => $mdlName,
//            "contents"        => base64_encode(serialize($contents)),
//            "contents_intext" => print_r($contents, true),
//        );


        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $jenisTr;
        $elName = $this->uri->segment(4);
        $mdlName = $this->uri->segment(5);
        $fromStep = $this->uri->segment(7);
        $intoStep = $this->uri->segment(8);


        $key = isset($_GET['key']) ? $_GET['key'] : "";

        heFetchElement($jenisTr, $elName, $mdlName, $key);

//        cekHijau("element: $elName, model: $mdlName, key: $key");

        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $elementRelConfig = isset($this->config->item('heTransaksi_ui')[$jenisTr]['relativeElements']) ? $this->config->item('heTransaksi_ui')[$jenisTr]['relativeElements'] : array();
            if (sizeof($elementRelConfig) > 0) {
                if (isset($elementRelConfig[$elName])) {
//                arrPrint($elementRelConfig[$elName]);
                    foreach ($elementRelConfig[$elName] as $firstKey => $relSpec) {
                        if ($firstKey == $key) {
                            foreach ($relSpec as $relName => $spec) {
                                cekHijau(":: $firstKey :: $relName ::");
                                arrPrint($spec);

                                if (sizeof($spec['usedFields']) > 0) {
                                    foreach ($spec['usedFields'] as $key => $val) {
                                        $_SESSION[$cCode]["main"][$relName . "__" . $key] = NULL;
                                        unset($_SESSION[$cCode]["main"][$relName . "__" . $key]);
                                    }
                                }
                                if (isset($_SESSION[$cCode]["main"][$relName])) {
                                    $_SESSION[$cCode]["main"][$relName] = NULL;
                                    unset($_SESSION[$cCode]["main"][$relName]);
                                }
                                if (isset($_SESSION[$cCode]["main"][$relName . "__label"])) {
                                    $_SESSION[$cCode]["main"][$relName . "__label"] = NULL;
                                    unset($_SESSION[$cCode]["main"][$relName . "__label"]);
                                }

                                // membuang contents di gerbang mainElements.... sesuai dengan relasi element
                                if (isset($_SESSION[$cCode]["main_elements"][$relName])) {
                                    if (isset($_SESSION[$cCode]["main_elements"][$relName]['contents'])) {
                                        $_SESSION[$cCode]["main_elements"][$relName]['contents'] = blobEncode(array());
                                        $_SESSION[$cCode]["main_elements"][$relName]['contents_intext'] = print_r(blobEncode(array()), true);
//                                        unset($_SESSION[$cCode]["main_elements"][$relName]['contents']);
//                                        unset($_SESSION[$cCode]["main_elements"][$relName]['contents_intext']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

//
//        echo "<script>";
//        echo "top.$('#result').load('" . base_url() . "ValueGate/buildValues/" . $jenisTr . "?epreketek=yes&populate=1');";
//        echo "</script>";


        $this->load->helper("he_value_builder");
        fillValues($jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = isset($_GET['rawBuilderURL']) ? $_GET['rawBuilderURL'] : "";
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->jenisTr . "/" . $this->uri->segment(6) . "/" . $this->uri->segment(7) . "/" . $this->uri->segment(8) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
//        echo "</script>";

//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";

    }

    public function patchFieldElement()
    {

//        $jenisTr = $this->uri->segment(3);
//        $cCode = "_TR_" . $jenisTr;
//        $elName = $this->uri->segment(4);
//        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements'] : array();
//
//        $val = ($_GET['val']);
//
//        if (!isset($_SESSION[$cCode]['main_elements'])) {
//            $_SESSION[$cCode]['main_elements'] = array();
//        }
//        $_SESSION[$cCode]['main_elements'][$elName] = array(
//            "name"     => $elName,
//            "label"    => $elementConfigs[$elName]['label'],
//            "mdl_name" => "",
//            "value"    => $val,
//        );


        $jenisTr = $this->uri->segment(3);
        $cCode = "_TR_" . $jenisTr;
        $elName = $this->uri->segment(4);
        $val = ($_GET['val']);
        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$jenisTr]['receiptElements'] : array();
        $relElementConfigs = isset($this->config->item('heTransaksi_ui')[$jenisTr]['relativeElements']) ? $this->config->item('heTransaksi_ui')[$jenisTr]['relativeElements'] : array();


        heRecordElement($jenisTr, $elName, $val);

        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->jenisTr . "/" . $this->uri->segment(6) . "/" . $this->uri->segment(7) . "/" . $this->uri->segment(8) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";


//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";
    }

    public function updateChildField()
    {
        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep] : null;
        $items_child = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields'] : array();
        $id = $_GET['id'];
        $key = $_GET['key'];
        $val = $_GET['val'];
        $key2 = $_GET['x'];

        if (isset($_SESSION[$cCode]['items_child'][$id][$key2][$key])) {
            $_SESSION[$cCode]['items_child'][$id][$key2][$key] = $val;


        }
        else {
//            echo(lgShowAlert("NOT replacing $key with $val"));
        }
        $this->load->helper("he_value_builder");
//        resetValues($this->jenisTr);
//        fillValues($this->jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";

//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";
    }

    public function selectElement()
    {
        $this->jenisTr = $this->uri->segment(3);
        $elID = $this->uri->segment(7);
        $cCode = "_TR_" . $this->jenisTr;
        $elementConfigs = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['receiptElements'] : array();
        $elementRelConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['relativeElements'] : array();

        $rawBuilderURL = isset($_GET['rawBuilderURL']) ? $_GET['rawBuilderURL'] : "";

        //region joint array main config dengan relative element
        $relConfig = array();
        foreach ($elementConfigs as $elName => $elMain) {

            if (isset($elementRelConfig[$elName])) {
                foreach ($elementRelConfig[$elName] as $relName => $relMain) {
                    foreach ($relMain as $relCode => $relData) {
//                        cekHere(":: $elName -- $relName -- $relCode ::");
//                        arrPrint($relData);
                        if ($relName == $_SESSION[$cCode]['main'][$elName]) {
                            $relConfig[$relCode] = $relData;
                        }

                        if (isset($elementRelConfig[$relCode])) {
                            foreach ($elementRelConfig[$relCode] as $ownRelName => $ownRelMain) {
                                foreach ($ownRelMain as $ownRelCode => $ownRelData) {
                                    if ($ownRelName == $_SESSION[$cCode]['main'][$relCode]) {

                                        $relConfig[$ownRelCode] = $ownRelData;
                                    }
                                }
                            }
                        }
                    }

                }

            }
        }
//        arrPrint($elementRelConfig);
//        arrPrint($relConfig);

        //endregion

        $elementConfigs = $elementConfigs + $relConfig;
//        arrPrint($elementConfigs);

        //region elements

        $elStr = array();
        $elements = array();

        if (isset($elementConfigs[$elID])) {
            $eName = $elID;
            $eSpec = $elementConfigs[$elID];
            switch ($eSpec['elementType']) {
                case "dataModel":
                    $addStr = "";
                    $editStr = "";
                    $amdlName = $eSpec['mdlName'];
                    $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();

                    $elStr[$eName] = "";
                    $this->load->model("Mdls/" . $amdlName);
                    $labelSrc = $eSpec['labelSrc'];
                    $keySrc = $eSpec['key'];
                    $oo = new $amdlName();
                    $addLink = base_url() . "Data/add/" . str_replace("Mdl", "", $amdlName);
                    if (sizeof($aFilter) > 0) {
//                        arrPrint($aFilter);
                        foreach ($aFilter as $filter) {
                            $exFilter = explode("=", $filter);
                            if (sizeof($exFilter) > 1) {
                                if (substr($exFilter[1], 0, 1) == ".") {
                                    $oo->addFilter($exFilter[0] . "='" . ltrim($exFilter[1], ".") . "'");

                                }
                                else {

                                    if (isset($_SESSION[$cCode]['main'][$exFilter[1]])) {
                                        $oo->addFilter($exFilter[0] . "='" . $_SESSION[$cCode]['main'][$exFilter[1]] . "'");
                                        $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $_SESSION[$cCode]['main'][$exFilter[1]];
                                    }
                                }
                            }
                            else {
                                $exFilter = explode("<>", $filter);
                                if (sizeof($exFilter) > 1) {
                                    if (substr($exFilter[1], 0, 1) == ".") {
                                        $oo->addFilter($exFilter[0] . "!='" . ltrim($exFilter[1], ".") . "'");

                                    }
                                    else {

                                        if (isset($_SESSION[$cCode]['main'][$exFilter[1]])) {
                                            $oo->addFilter($exFilter[0] . "!='" . $_SESSION[$cCode]['main'][$exFilter[1]] . "'");
                                            $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $_SESSION[$cCode]['main'][$exFilter[1]];
                                        }
                                    }
                                }
                                else {
                                    $exFilter = explode("<", $filter);
                                    if (sizeof($exFilter) > 1) {
                                        if (substr($exFilter[1], 0, 1) == ".") {
                                            $oo->addFilter($exFilter[0] . "<'" . ltrim($exFilter[1], ".") . "'");

                                        }
                                        else {

                                            if (isset($_SESSION[$cCode]['main'][$exFilter[1]])) {
                                                $oo->addFilter($exFilter[0] . "<'" . $_SESSION[$cCode]['main'][$exFilter[1]] . "'");
                                                $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $_SESSION[$cCode]['main'][$exFilter[1]];
                                            }
                                        }
                                    }
                                    else {
                                        $exFilter = explode(">", $filter);
                                        if (sizeof($exFilter) > 1) {
                                            if (substr($exFilter[1], 0, 1) == ".") {
                                                $oo->addFilter($exFilter[0] . ">'" . ltrim($exFilter[1], ".") . "'");

                                            }
                                            else {

                                                if (isset($_SESSION[$cCode]['main'][$exFilter[1]])) {
                                                    $oo->addFilter($exFilter[0] . ">'" . $_SESSION[$cCode]['main'][$exFilter[1]] . "'");
                                                    $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $_SESSION[$cCode]['main'][$exFilter[1]];
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }


                    $addClick = "";
                    $dataAccess = isset($this->config->item('heDataBehaviour')[$amdlName]) ? $this->config->item('heDataBehaviour')[$amdlName] : array(
                        "viewers" => array(),
                        "creators" => array(),
                        "creatorAdmins" => array(),
                        "updaters" => array(),
                        "updaterAdmins" => array(),
                        "deleters" => array(),
                        "deleterAdmins" => array(),
                        "historyViewers" => array(),
                    );
                    $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                    if (sizeof($mems) > 0 && sizeof($dataAccess['creators']) > 0) {
                        if (sizeof(array_intersect($mems, $dataAccess['creators'])) > 0) {
                            $addClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: $('<div></div>').load('" . $addLink . "?rawBuilderURL=$rawBuilderURL'),
                                        draggable:true,
                                        closable:true,
                                        }
                                        );";
                            $addStr = "<a href='javascript:void(0)' class='btn btn-default' onclick=\"$addClick\"><span class='glyphicon glyphicon-plus'></span></a>";
                        }
                    }


                    $tmpo = $oo->lookupAll()->result();
//                    showLast_query("biru");
                    $elPair[$amdlName] = array();
                    $selectorTarget = "'" . base_url() . get_class($this) . "/patchElement/" . $this->jenisTr . "/$eName/$amdlName/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?key='+this.value";
//cekBiru($selectorTarget);

                    $elStr[$eName] .= "<div class='box-body'>";
                    $elStr[$eName] .= "<select class='form-control' onchange=\"top.$('#result').load($selectorTarget);\">";
                    $elStr[$eName] .= "<option value=''>-select-</option>";
                    if (sizeof($tmpo) > 0) {
                        foreach ($tmpo as $row) {
                            $elPair[$amdlName][$row->id] = $row->$labelSrc;
                            $selected = isset($_SESSION[$cCode]['main_elements'][$eName]) && $_SESSION[$cCode]['main_elements'][$eName]['key'] == $row->id ? "selected" : "";
                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                        }
                    }
                    $elStr[$eName] .= "</select>";
                    $elStr[$eName] .= "</div class='box-header'>";

                    $defKey = isset($_SESSION[$cCode]['main_elements'][$eName]['key']) ? $_SESSION[$cCode]['main_elements'][$eName]['key'] : 0;
                    $defValue = "";
                    if (isset($_SESSION[$cCode]['main_elements'][$eName]['key']) && $_SESSION[$cCode]['main_elements'][$eName]['contents']) {
                        if (isset($elementConfigs[$eName]['usedFields']) && sizeof($elementConfigs[$eName]['usedFields']) > 0) {
                            $defValue .= "<table class='table table-condensed no-padding' style='padding:0px;margin:0px;'>";
                            $contents[$eName] = unserialize(base64_decode($_SESSION[$cCode]['main_elements'][$eName]['contents']));
                            foreach ($elementConfigs[$eName]['usedFields'] as $src => $label) {
                                $fieldLabel = isset($contents[$eName][$src]) ? $contents[$eName][$src] : "-";
                                $defValue .= "<tr>";
                                $defValue .= "<td align='left'>$label";
                                $defValue .= "</td>";
                                $defValue .= "<td align='left'>" . $fieldLabel;
                                $defValue .= "</td>";
                                $defValue .= "</tr>";
                            }
                            $defValue .= "</table>";
                        }
                    }

                    $rawBuilderURL = $_GET['rawBuilderURL'];
                    if ($defKey > 0) {
                        if (sizeof($mems) > 0 && sizeof($dataAccess['updaters']) > 0) {
                            $editLink = base_url() . "Data/edit/" . str_replace("Mdl", "", $amdlName) . "/$defKey";
                            if (sizeof(array_intersect($mems, $dataAccess['updaters'])) > 0) {
                                $editClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: $('<div></div>').load('" . $editLink . "?rawBuilderURL=$rawBuilderURL'),
                                        draggable:true,
                                        size:BootstrapDialog.SIZE_WIDE,
                                        closable:true,
                                        }
                                        );";

                                $editStr = "<a href='javascript:void(0)' class='btn btn-default' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                            }
                        }
                    }

                    $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;font-size:smaller;'>$defValue";
                    $elStr[$eName] .= "</div id='el$amdlName'>";
                    $elStr[$eName] .= "<div class='box-footer'>";

                    $elStr[$eName] .= "<span class='pull-right'>$editStr $addStr</span>";
                    $elStr[$eName] .= "</div class='box-footer'>";

                    $elements[] = array(
                        "mdlName" => $eSpec['mdlName'],
                        "label" => $eSpec['label'],
                        "string" => $elStr[$eName],
                    );


                    break;
                case "dataField":
                    $elStr[$eName] = "";
                    $defaultValue = isset($eSpec['defaultValue']) ? $eSpec['defaultValue'] : "";

                    $selectorTarget = "'" . base_url() . get_class($this) . "/patchFieldElement/" . $this->jenisTr . "/$eName/amdlName/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?val='+this.value";


                    $elStr[$eName] .= "<div class='box-body'>";
                    switch ($eSpec['inputType']) {
                        case "text":
                            $elStr[$eName] .= "<input type=text class='form-control' value='$defaultValue' onblur=\"top.$('#result').load($selectorTarget);\">";
                            break;
                        case "date":
                            $elStr[$eName] .= "<input type=date class='form-control' value='$defaultValue' onblur=\"top.$('#result').load($selectorTarget);\">";
                            break;
                    }
                    $elStr[$eName] .= "</div class='box-body'>";


                    $elements[] = array(
                        "mdlName" => null,
                        "label" => $eSpec['label'],
                        "string" => $elStr[$eName],
                    );
                    break;
            }
            echo $elStr[$eName];


        }

        //endregion
//        mati_disini();
    }


    public function updateItemFieldOpname()
    {

        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep] : null;

        $id = $_GET['id'];
        $key = $_GET['key'];
        $val = $_GET['val'];
        if (isset($_SESSION[$cCode]['items'][$id][$key])) {

            $_SESSION[$cCode]['items'][$id][$key] = $val;

            $stok = $_SESSION[$cCode]['items'][$id]['stok'];
            $selisih = $val - $stok;
            if ($selisih > 0) {
                $_SESSION[$cCode]['items'][$id]['qty_debet'] = $selisih;
                $_SESSION[$cCode]['items'][$id]['qty_kredit'] = 0;
            }
            elseif ($selisih < 0) {
                $_SESSION[$cCode]['items'][$id]['qty_debet'] = 0;
                $_SESSION[$cCode]['items'][$id]['qty_kredit'] = ($selisih * -1);
            }
            else {
                $_SESSION[$cCode]['items'][$id]['qty_debet'] = 0;
                $_SESSION[$cCode]['items'][$id]['qty_kredit'] = 0;
            }
            $_SESSION[$cCode]['items'][$id]['qty_selisih'] = $selisih;

            foreach ($itemNumLabels as $key => $label) {
                if (isset($_SESSION[$cCode]['items'][$id][$key])) {
                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                }

            }

            if (isset($_SESSION[$cCode]['items'][$id]['nett'])) {
                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
            }

            if ($subAmountConfig != null) {
                $items = $_SESSION[$cCode]['items'];
                $subtotal = makeValue($subAmountConfig, $items[$id], $items[$id], 0);
            }
            else {
                $subtotal = 0;
            }

            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($subtotal);

//            if ($val < $_SESSION[$cCode]['items'][$id][$key]) {
//                if (isset($_SESSION[$cCode]['main']['status_4'])) {
//                    $_SESSION[$cCode]['main']['status_4'] = 5;
//                }
//                if (isset($_SESSION[$cCode]['main']['trash_4'])) {
//                    $_SESSION[$cCode]['main']['trash_4'] = 0;
//                }
//            }


        }
        else {
//            echo(lgShowAlert("NOT replacing $key with $val"));
        }


        //==recover nilai HARGA master
        $_SESSION[$cCode]['main']['harga'] = 0;
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
            }
        }

        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);


        //region kembalikan ke followupPreview
        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";


//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";
        //endregion

        echo "<script>$actionTarget</script>";
    }

    public function updateSourceField()
    {
//        arrPrint($this->uri->segment_array());
        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep] : null;
        $items_child = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields'] : array();
        $id = $_GET['id'];
        $val = $_GET['val'];

//matiHere($val);
//        if (!isset($_SESSION[$cCode]['main'][$id])) {
        $_SESSION[$cCode]['main'][$id] = $val;
//        }
//        if(!isset($_SESSION[$cCode]['main']['efaktur_source'])){
        $_SESSION[$cCode]['main']["efakturSource"] = isset($_SESSION[$cCode]['main']["efaktur_source"]) ? $_SESSION[$cCode]['main']["efaktur_source"] : $_SESSION[$cCode]['main']["nomer"];
//        }


        $this->load->helper("he_value_builder");
//        resetValues($this->jenisTr);
//        fillValues($this->jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";

//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";
    }

    public function editSourceField()
    {
//        arrPrint($this->uri->segment_array());
        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep] : null;
        $items_child = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields'] : array();
        $id = $_GET['id'];
        $val = $_GET['val'];

//matiHere($val);
//        if (!isset($_SESSION[$cCode]['main'][$id])) {
        $_SESSION[$cCode]['main'][$id] = $val;
//        }
//        if(!isset($_SESSION[$cCode]['main']['efaktur_source'])){
        $_SESSION[$cCode]['main']["efakturSource"] = isset($_SESSION[$cCode]['main']["efaktur_source"]) ? $_SESSION[$cCode]['main']["efaktur_source"] : $_SESSION[$cCode]['main']["nomer"];
//        }


        $this->load->helper("he_value_builder");
//        resetValues($this->jenisTr);
//        fillValues($this->jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/editMainFaktur/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";

//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";
    }


    public function updateItemFieldProduksi()
    {

        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep] : null;
        $items_child = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields'] : array();
        $id = $_GET['id']; // ini adalah id produk hasil bom
        $key = $_GET['key']; // ini adalah key yang diedit
        $val = is_numeric($_GET['val']) ? $_GET['val'] : htmlspecialchars($_GET['val']); // ini adalah nilai yang diisikan


        arrPrint($_GET);

        // edit gerbang ITEMS
        if (isset($_SESSION[$cCode]['items'][$id][$key])) {

            $_SESSION[$cCode]['items'][$id][$key] = $val;

            foreach ($itemNumLabels as $key => $label) {
                if (isset($_SESSION[$cCode]['items'][$id][$key])) {
                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                }

            }
            if (isset($_SESSION[$cCode]['items'][$id]['nett'])) {
                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
            }

            if ($subAmountConfig != null) {
                $items = $_SESSION[$cCode]['items'];
                $subtotal = makeValue($subAmountConfig, $items[$id], $items[$id], 0);
            }
            else {
                $subtotal = 0;
            }
            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($subtotal);

            if ($val < $_SESSION[$cCode]['items'][$id][$key]) {
                if (isset($_SESSION[$cCode]['main']['status_4'])) {
                    $_SESSION[$cCode]['main']['status_4'] = 5;
                }
                if (isset($_SESSION[$cCode]['main']['trash_4'])) {
                    $_SESSION[$cCode]['main']['trash_4'] = 0;
                }
            }


        }
        else {
//            echo(lgShowAlert("NOT replacing $key with $val"));
        }


        //==recover nilai HARGA master
        $_SESSION[$cCode]['main']['harga'] = 0;
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id_ => $iSpec) {
                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
            }
        }


        //
        cekHitam(":: cetakm ITEMS_KOMPOSISI");
        arrprint($_SESSION[$cCode]['items_komposisi']);

        $arrComponentsOriginal = array();
        if (isset($_SESSION[$cCode]['items_komposisi']) && (sizeof($_SESSION[$cCode]['items_komposisi']) > 0)) {
            $arrComponentsOriginal = $_SESSION[$cCode]['items_komposisi'];
            arrPrint($arrComponentsOriginal);
            foreach ($arrComponentsOriginal[$id]['produk'] as $e => $eSpec) {
                $_SESSION[$cCode]['items2'][$id]['produk'][$e]['jml'] = isset($arrComponentsOriginal[$id]['produk'][$e]->jml) ? ($arrComponentsOriginal[$id]['produk'][$e]->jml * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
            }
            foreach ($arrComponentsOriginal[$id]['biaya'] as $e => $eSpec) {
                $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['jml'] = isset($arrComponentsOriginal[$id]['biaya'][$e]->jml) ? ($arrComponentsOriginal[$id]['biaya'][$e]->jml * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['sub_nilai'] = isset($arrComponentsOriginal[$id]['biaya'][$e]->jml) ? ($arrComponentsOriginal[$id]['biaya'][$e]->nilai * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
            }
        }
        else {
            $msg = "Belum ada data komposisi produk, harap di setup terlebih dahulu via login holding.";
            die(lgShowAlert($msg));
        }

//        mati_disini(get_class($this) . " -- " . __FUNCTION__ . " :: " . __LINE__);


        if (sizeof($_SESSION[$cCode]['items2']) > 0) {
            $_SESSION[$cCode]['items2_sum'] = array();// supplies-nya...
            $_SESSION[$cCode]['items3_sum'] = array();// biaya-nya...
            foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                foreach ($pSpec as $jenis => $jSpec) {
                    foreach ($jSpec as $eSpec) {
                        if ($jenis == "produk") {
                            if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();
                            }
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                        }
                        if ($jenis == "biaya") {
                            if (!isset($_SESSION[$cCode]['items3_sum'][$eSpec['id']])) {
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']] = $eSpec;
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] = 0;
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] = 0;
                                $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['produk_ids'] = array();
                            }
                            $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                            $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] += $eSpec['sub_nilai'];
                            $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                        }
                    }
                }
            }
        }
        if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
            foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
            }
        }


        cekHitam(":: cetak gerbang ITEMS2");
        arrPrint($_SESSION[$cCode]['items2']);

        cekHitam(":: cetak gerbang ITEMS2_SUM");
        arrPrint($_SESSION[$cCode]['items2_sum']);

        cekHitam(":: cetak gerbang ITEMS3_SUM");
        arrPrint($_SESSION[$cCode]['items3_sum']);

//        mati_disini(get_class($this) . " -- " . __FUNCTION__);

        //region bagian kalkulasi/hitung ulang via value builder
        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);
        //endregion


        $rawBuilderURL = isset($_GET['rawBuilderURL']) ? $_GET['rawBuilderURL'] : NULL;
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";
    }

    public function updateItemExpense()
    {
        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep] : null;
        $items_child = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields'] : array();
        $detailForceMain = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['detailForceMain'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['detailForceMain'][$intoStep] : array();

        //----------------------------
        $closedRequest = isset($this->config->item("heTransaksi_core")[$this->jenisTr]['closedRequest'][$intoStep]) ? $this->config->item("heTransaksi_core")[$this->jenisTr]['closedRequest'][$intoStep] : array();

        //----------------------------
        $arrPartialKey = array("jml", "qty");
        $id = $_GET['id'];
        $key = $_GET['key'];
        $val = is_numeric($_GET['val']) ? $_GET['val'] : htmlspecialchars($_GET['val']);
        if (isset($_SESSION[$cCode]['items'][$id][$key])) {
            $_SESSION[$cCode]['items'][$id][$key] = $val;


            //--------------------------------------------
            foreach ($itemNumLabels as $key => $label) {
                if (isset($_SESSION[$cCode]['items'][$id][$key])) {
                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                }

            }
            if (isset($_SESSION[$cCode]['items'][$id]['nett'])) {
                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
            }

            if ($subAmountConfig != null) {
                $items = $_SESSION[$cCode]['items'];
                $subtotal = makeValue($subAmountConfig, $items[$id], $items[$id], 0);
            }
            else {
                $subtotal = 0;
            }
            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($subtotal);

            if ($val < $_SESSION[$cCode]['items'][$id][$key]) {
                if (isset($_SESSION[$cCode]['main']['status_4'])) {
                    $_SESSION[$cCode]['main']['status_4'] = 5;
                }
                if (isset($_SESSION[$cCode]['main']['trash_4'])) {
                    $_SESSION[$cCode]['main']['trash_4'] = 0;
                }
                //----------------------------------------------------------------
                //==bila ada yang dirubah/edit menjadi lebih kecil, maka menjadi partial
                if (in_array($key, $arrPartialKey)) {
                    $_SESSION[$cCode]['main']['partial'] = 1;
                }
                //----------------------------------------------------------------
            }


        }
        else {
            //            echo(lgShowAlert("NOT replacing $key with $val"));
        }


        //==recover nilai HARGA master
        $_SESSION[$cCode]['main']['harga'] = 0;
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id_ => $iSpec) {
                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                $_SESSION[$cCode]['main'][str_replace(' ', '_', $iSpec['rekening'])] = $iSpec['harga'];
            }
        }

        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = $_GET['rawBuilderURL'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
        echo "<script>$actionTarget</script>";
    }

    public function removeItemProduksi()
    {
        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $id = $_GET['id'];


        if (isset($_SESSION[$cCode]['items'][$id])) {
            // menghapus gerbang items sesuai produkID-nya
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);

            if (isset($_SESSION[$cCode]['items_komposisi'][$id]) && (sizeof($_SESSION[$cCode]['items_komposisi'][$id]) > 0)) {

                // menghapus komposisi produk yang dipilih (komposisi standatr)
                $_SESSION[$cCode]['items_komposisi'][$id] = null;
                unset($_SESSION[$cCode]['items_komposisi'][$id]);

                // menghapus komposisi produk yang dipilih (komposisi sudah dikalikan dengan jumlah produksi)
                $_SESSION[$cCode]['items2'][$id] = null;
                unset($_SESSION[$cCode]['items2'][$id]);

                if (sizeof($_SESSION[$cCode]['items2']) > 0) {
                    $_SESSION[$cCode]['items2_sum'] = array();// supplies-nya...
                    $_SESSION[$cCode]['items3_sum'] = array();// biaya-nya...
                    foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                        foreach ($pSpec as $jenis => $jSpec) {
                            foreach ($jSpec as $eSpec) {
                                if ($jenis == "produk") {
                                    if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();
                                    }
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                                }
                                if ($jenis == "biaya") {
                                    if (!isset($_SESSION[$cCode]['items3_sum'][$eSpec['id']])) {
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['id']] = $eSpec;
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] = 0;
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] = 0;
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['produk_ids'] = array();
                                    }
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] += $eSpec['sub_nilai'];
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                                }
                            }
                        }
                    }
                }
                if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                    foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                        $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
                    }
                }

            }

        }


        if (isset($_SESSION[$cCode]['tableIn_detail'][$id])) {
            $_SESSION[$cCode]['tableIn_detail'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail'][$id]);
        }
        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
        }
        //==beberapa bagian di MAIN harus di-reset, sesuai ....
        if (isset($_SESSION[$cCode]['main']['harga'])) {
//            unset($_SESSION[$cCode]['main']['harga']);
            $_SESSION[$cCode]['main']['harga'] = 0;
        }
        //==recover nilai HARGA master
        $_SESSION[$cCode]['main']['harga'] = 0;
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
            }
        }


        if (isset($_SESSION[$cCode]['main']['status_4'])) {
            $_SESSION[$cCode]['main']['status_4'] = 5;
        }
        if (isset($_SESSION[$cCode]['main']['trash_4'])) {
            $_SESSION[$cCode]['main']['trash_4'] = 0;
        }


        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = isset($_GET['rawBuilderURL']) ? $_GET['rawBuilderURL'] : NULL;
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "</html>";

        echo "<script>$actionTarget</script>";

    }

    //produk komposit
    public function updateItemFieldKomposit()
    {

        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $itemNumLabels = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartNumFields'][$intoStep] : array();
        $subAmountConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep]) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shoppingCartAmountValue'][$intoStep] : null;
        $items_child = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['shopingCartDetailFields'][$intoStep]['fields'] : array();
        $id = $_GET['id']; // ini adalah id produk hasil bom
        $key = $_GET['key']; // ini adalah key yang diedit
        $val = is_numeric($_GET['val']) ? $_GET['val'] : htmlspecialchars($_GET['val']); // ini adalah nilai yang diisikan


        arrPrint($_GET);

        // edit gerbang ITEMS
        if (isset($_SESSION[$cCode]['items'][$id][$key])) {

            $_SESSION[$cCode]['items'][$id][$key] = $val;

            foreach ($itemNumLabels as $key => $label) {
                if (isset($_SESSION[$cCode]['items'][$id][$key])) {
                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                }

            }
            if (isset($_SESSION[$cCode]['items'][$id]['nett'])) {
                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);
            }

            if ($subAmountConfig != null) {
                $items = $_SESSION[$cCode]['items'];
                $subtotal = makeValue($subAmountConfig, $items[$id], $items[$id], 0);
            }
            else {
                $subtotal = 0;
            }
            $_SESSION[$cCode]['items'][$id]['subtotal'] = ($subtotal);

            if ($val < $_SESSION[$cCode]['items'][$id][$key]) {
                if (isset($_SESSION[$cCode]['main']['status_4'])) {
                    $_SESSION[$cCode]['main']['status_4'] = 5;
                }
                if (isset($_SESSION[$cCode]['main']['trash_4'])) {
                    $_SESSION[$cCode]['main']['trash_4'] = 0;
                }
            }


        }
        else {
            //            echo(lgShowAlert("NOT replacing $key with $val"));
        }


        //==recover nilai HARGA master
        $_SESSION[$cCode]['main']['harga'] = 0;
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id_ => $iSpec) {
                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
            }
        }


        //
        cekHitam(":: cetakm ITEMS_KOMPOSISI");
        arrprint($_SESSION[$cCode]['items_komposisi']);

        $arrComponentsOriginal = array();
        if (isset($_SESSION[$cCode]['items_komposisi']) && (sizeof($_SESSION[$cCode]['items_komposisi']) > 0)) {
            $arrComponentsOriginal = $_SESSION[$cCode]['items_komposisi'];
            arrPrint($arrComponentsOriginal);
            foreach ($arrComponentsOriginal[$id]['produk'] as $e => $eSpec) {
                $_SESSION[$cCode]['items2'][$id]['produk'][$e]['jml'] = isset($arrComponentsOriginal[$id]['produk'][$e]->jml) ? ($arrComponentsOriginal[$id]['produk'][$e]->jml * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
            }
            foreach ($arrComponentsOriginal[$id]['biaya'] as $e => $eSpec) {
                $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['jml'] = isset($arrComponentsOriginal[$id]['biaya'][$e]->jml) ? ($arrComponentsOriginal[$id]['biaya'][$e]->jml * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
                $_SESSION[$cCode]['items2'][$id]['biaya'][$e]['sub_nilai'] = isset($arrComponentsOriginal[$id]['biaya'][$e]->jml) ? ($arrComponentsOriginal[$id]['biaya'][$e]->nilai * $_SESSION[$cCode]['items'][$id]['jml']) : 0;
            }
        }
        else {
            $msg = "Belum ada data komposisi produk, harap di setup terlebih dahulu via login holding.";
            die(lgShowAlert($msg));
        }

        //        mati_disini(get_class($this) . " -- " . __FUNCTION__ . " :: " . __LINE__);


        if (sizeof($_SESSION[$cCode]['items2']) > 0) {
            $_SESSION[$cCode]['items2_sum'] = array();// supplies-nya...
            foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                foreach ($pSpec as $jenis => $jSpec) {
                    foreach ($jSpec as $eSpec) {
                        if ($jenis == "produk") {
                            if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                                $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();
                            }
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                            $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                        }
                    }
                }
            }
        }
        if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
            foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
            }
        }


        cekHitam(":: cetak gerbang ITEMS2");
        arrPrint($_SESSION[$cCode]['items2']);

        cekHitam(":: cetak gerbang ITEMS2_SUM");
        arrPrint($_SESSION[$cCode]['items2_sum']);


        //        mati_disini(get_class($this) . " -- " . __FUNCTION__);

        //region bagian kalkulasi/hitung ulang via value builder
        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);
        //endregion


        $rawBuilderURL = isset($_GET['rawBuilderURL']) ? $_GET['rawBuilderURL'] : NULL;
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
        //        echo "<html>";
        //        echo "<head>";
        //        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //        echo "</head>";
        //        echo "<body onload=\"$actionTarget\">";
        //        echo "</body>";
        //        echo "</html>";

        echo "<script>$actionTarget</script>";
    }
    public function removeItemKomposit()
    {
        $this->jenisTr = $this->uri->segment(3);
        $fromStep = $this->uri->segment(6);
        $intoStep = $this->uri->segment(5);
        $cCode = "_TR_" . $this->jenisTr;
        $id = $_GET['id'];


        if (isset($_SESSION[$cCode]['items'][$id])) {
            // menghapus gerbang items sesuai produkID-nya
            $_SESSION[$cCode]['items'][$id] = null;
            unset($_SESSION[$cCode]['items'][$id]);

            if (isset($_SESSION[$cCode]['items_komposisi'][$id]) && (sizeof($_SESSION[$cCode]['items_komposisi'][$id]) > 0)) {

                // menghapus komposisi produk yang dipilih (komposisi standatr)
                $_SESSION[$cCode]['items_komposisi'][$id] = null;
                unset($_SESSION[$cCode]['items_komposisi'][$id]);

                // menghapus komposisi produk yang dipilih (komposisi sudah dikalikan dengan jumlah produksi)
                $_SESSION[$cCode]['items2'][$id] = null;
                unset($_SESSION[$cCode]['items2'][$id]);

                if (sizeof($_SESSION[$cCode]['items2']) > 0) {
                    $_SESSION[$cCode]['items2_sum'] = array();// supplies-nya...
                    $_SESSION[$cCode]['items3_sum'] = array();// biaya-nya...
                    foreach ($_SESSION[$cCode]['items2'] as $pID => $pSpec) {
                        foreach ($pSpec as $jenis => $jSpec) {
                            foreach ($jSpec as $eSpec) {
                                if ($jenis == "produk") {
                                    if (!isset($_SESSION[$cCode]['items2_sum'][$eSpec['id']])) {
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']] = $eSpec;
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] = 0;
                                        $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'] = array();
                                    }
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                                    $_SESSION[$cCode]['items2_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                                }
                                if ($jenis == "biaya") {
                                    if (!isset($_SESSION[$cCode]['items3_sum'][$eSpec['id']])) {
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['id']] = $eSpec;
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] = 0;
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] = 0;
                                        $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['produk_ids'] = array();
                                    }
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['jml'] += $eSpec['jml'];
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['sub_nilai'] += $eSpec['sub_nilai'];
                                    $_SESSION[$cCode]['items3_sum'][$eSpec['id']]['produk_ids'][$pID] = $pID;
                                }
                            }
                        }
                    }
                }
                if (sizeof($_SESSION[$cCode]['items2_sum']) > 0) {
                    foreach ($_SESSION[$cCode]['items2_sum'] as $bID => $pSpec) {
                        $_SESSION[$cCode]['items2_sum'][$bID]['produk_ids'] = serialize(base64_encode($pSpec['produk_ids']));
                    }
                }

            }

        }


        if (isset($_SESSION[$cCode]['tableIn_detail'][$id])) {
            $_SESSION[$cCode]['tableIn_detail'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail'][$id]);
        }
        if (isset($_SESSION[$cCode]['tableIn_detail_values'][$id])) {
            $_SESSION[$cCode]['tableIn_detail_values'][$id] = null;
            unset($_SESSION[$cCode]['tableIn_detail_values'][$id]);
        }
        //==beberapa bagian di MAIN harus di-reset, sesuai ....
        if (isset($_SESSION[$cCode]['main']['harga'])) {
            //            unset($_SESSION[$cCode]['main']['harga']);
            $_SESSION[$cCode]['main']['harga'] = 0;
        }
        //==recover nilai HARGA master
        $_SESSION[$cCode]['main']['harga'] = 0;
        if (sizeof($_SESSION[$cCode]['items']) > 0) {
            foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
            }
        }


        if (isset($_SESSION[$cCode]['main']['status_4'])) {
            $_SESSION[$cCode]['main']['status_4'] = 5;
        }
        if (isset($_SESSION[$cCode]['main']['trash_4'])) {
            $_SESSION[$cCode]['main']['trash_4'] = 0;
        }


        $this->load->helper("he_value_builder");
        resetValues($this->jenisTr);
        fillValues($this->jenisTr, $fromStep, $intoStep);


        $rawBuilderURL = isset($_GET['rawBuilderURL']) ? $_GET['rawBuilderURL'] : NULL;
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                       title:'Followup preview',
                                       message: " . 'top.$' . "('<div></div>').load('" . base_url() . "Transaksi/followupPreview/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?rawBuilderURL=$rawBuilderURL'),
                                        size:top.BootstrapDialog.SIZE_WIDE,
                                        draggable:false,
                                        animate:false,
                                        closable:false,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
        //        echo "<html>";
        //        echo "<head>";
        //        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //        echo "</head>";
        //        echo "<body onload=\"$actionTarget\">";
        //        echo "</body>";
        //        echo "</html>";

        echo "<script>$actionTarget</script>";

    }

    //--------
    public function editEfaktur()
    {
        cekHijau("MASUK SINI EDIT...");
        arrPrintPink($_GET);
        arrPrintWebs($this->uri->segment_array());

        //-----------------------
        $key = $_GET['key'];
        $val = $_GET['val'];
        //-----------------------
        $jenisTr = $this->uri->segment(3);
        $pymSrcID = $this->uri->segment(4);
        $transaksiID = $this->uri->segment(5);
        $masterID = $this->uri->segment(6);
        //-----------------------
        $this->load->model("MdlTransaksi");

        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $tmp = $tr->lookupPaymentSrcByID($pymSrcID)->result();
        showLast_query("biru");

        $dataOld = (array)$tmp[0];
        $blobDataOld = blobEncode($dataOld);

        $dataOld[$key] = $val;
        $blobDataNew = blobEncode($dataOld);
        //-----------------------


        if($dataOld[$key] == $val){
            $msg = "Tidak ada perubahan data.";
            die(lgShowAlert($msg));
        }



        $this->db->trans_start();

        //---UPDATE PAYMENT SOURCE-----------------------
        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $where = array(
            "id" => $pymSrcID,
        );
        $data = array(
            "$key" => $val,
        );
        $tr->updatePaymentSrc($where, $data);
        showLast_query("orange");

        //---TRANSAKSI EFAKTUR-----------------------
        $dataEfaktur = array(
            "transaksi_id" => $transaksiID,
            "id_master" => $masterID,
            "dtime" => date("Y-m-d H:i:s"),
            "data_lama" => $blobDataOld,
            "data_baru" => $blobDataNew,
            "oleh_id" => $this->session->login['id'],
            "oleh_nama" => $this->session->login['nama'],
            "jenis" => $jenisTr,
            "jenis_reference" => $dataOld['jenis'],
        );

        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $tbl = $tr->getTableNames()['efaktur'];
        $tr->setTableName($tbl);
        $tr->addData($dataEfaktur);
        showLast_query("hijau");


//        mati_disini("-- MAINTENANCE --");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


        $msg = "Perubahan berhasil disimpan.";
        echo lgShowAlert($msg);

//        echo "\n<script>top.location.reload();</script>";
    }
}