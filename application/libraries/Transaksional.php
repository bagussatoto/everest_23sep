<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */

class Transaksional
{

    /**
     * @role lead_architect_agent & software_engineer_agent
     * Helper method untuk meredam echo debug saat transaksi dipanggil via Service/JSON mode (Rule 3.6 - PHP 5.6)
     */
    protected function tEcho($str)
    {
        if (defined("SILENT_TRANSAKSIONAL") && SILENT_TRANSAKSIONAL) {
            return;
        }
        if (isset($_GET["return_json"]) || isset($_GET["json"])) {
            return;
        }
        if (!isset($_GET["debug_transaksional"])) {
            return;
        }
        echo $str;
    }

    protected $toko_id;
    protected $transaksi_id;
    protected $master_id;
    protected $jenisTrReference;
    protected $jenisTrPembatalan;
    protected $param = array();

    /**
     * @return mixed
     */
    public function getJenisTrReference()
    {
        return $this->jenisTrReference;
    }

    /**
     * @param mixed $jenisTrReference
     */
    public function setJenisTrReference($jenisTrReference)
    {
        $this->jenisTrReference = $jenisTrReference;
    }

    /**
     * @return mixed
     */
    public function getJenisTrPembatalan()
    {
        return $this->jenisTrPembatalan;
    }

    /**
     * @param mixed $jenisTrPembatalan
     */
    public function setJenisTrPembatalan($jenisTrPembatalan)
    {
        $this->jenisTrPembatalan = $jenisTrPembatalan;
    }

    /**
     * @return array
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * @param array $param
     */
    public function setParam($param)
    {
        $this->param = $param;
    }

    /**
     * @return mixed
     */
    public function getMasterId()
    {
        return $this->master_id;
    }

    /**
     * @param mixed $master_id
     */
    public function setMasterId($master_id)
    {
        $this->master_id = $master_id;
    }

    /**
     * @return mixed
     */
    public function getTransaksiId()
    {
        return $this->transaksi_id;
    }

    /**
     * @param mixed $transaksi_id
     */
    public function setTransaksiId($transaksi_id)
    {
        $this->transaksi_id = $transaksi_id;
    }

    //-------------
    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }

    public function gerbang_transaksi($toko_id, $transaksi_jenis, $array_data)
    {
        $CI =& get_instance();
        $CI->load->library("FieldCalculator");
        $cal = new FieldCalculator();

        $array_datas = blobDecode($array_data);

        cekBiru("$toko_id ***** $transaksi_jenis");
        arrPrintPink($array_datas);
        // $items = $array_datas['items'];

        $arrItems = isset($array_datas['items']) ? $array_datas['items'] : array();
        // id_produk => qty

        $arrTrID = isset($array_datas['trs']) ? $array_datas['trs'] : array();

        $arrMain = isset($array_datas['main']) ? $array_datas['main'] : array();

        $cCode = "_TR_" . $transaksi_jenis;
        // $toko_id = my_toko_id();

        // matiHere(__LINE__);

        $selectorModel = $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['selectorModel'];
        $selectorSrcModel = $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['selectorSrcModel'];

        $CI->load->model("Mdls/" . $selectorSrcModel);
        $b = new $selectorSrcModel();


        $itemNumLabels = isset($CI->config->item('heTransaksi_ui')[$transaksi_jenis]['shoppingCartNumFields'][1]) ? $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['shoppingCartNumFields'][1] : array();
        $priceConfig = isset($CI->config->item('heTransaksi_ui')[$transaksi_jenis]['selectedPrice']) ? $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['selectedPrice'] : array();
        $lockerConfig = isset($CI->config->item('heTransaksi_ui')[$transaksi_jenis]['lockerCheck']) ? $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['lockerCheck'] : array();
        $subAmountConfig = isset($CI->config->item('heTransaksi_ui')[$transaksi_jenis]['shoppingCartAmountValue'][1]) ? $CI->config->item('heTransaksi_ui')[$transaksi_jenis]['shoppingCartAmountValue'][1] : null;

        if (sizeof($arrItems) > 0) {
            arrPrintWebs($arrItems);
            foreach ($arrItems as $id => $jmlParam) {

                $tmpB = $b->lookupByID($id)->result();
//                cekHere($CI->db->last_query());
                arrPrint($tmpB);

                $jml = $jmlParam;
                if (sizeof($tmpB) > 0) {
                    foreach ($tmpB as $row) {
                        $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                        $tmpJml = $jmlParam;
                        if (isset($lockerConfig['enabled']) && $lockerConfig['enabled'] == true) {
//                            cekMerah("masuk locker config");

                            $mdlName = $lockerConfig['mdlName'];
                            $this->load->model("Mdls/" . $mdlName);
                            $c = new $mdlName();
                            $c->addFilter("produk_id='$id'");
                            $c->addFilter("state='active'");
                            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
                            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
                            $tmpC = $c->lookupAll($id)->result();
//                            cekHere($this->db->last_query() . " " . __LINE__);


                            if (sizeof($tmpC) > 0) {
                                arrPrint($tmpC);
                                foreach ($tmpC as $row) {
                                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                                    $nama = $row->nama;

                                    $jml_now = $row->jumlah;
                                    if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                                        $jml_sudah_diambil = 0;
                                        $jml_diperlukan = 1;
                                        $jml_nambah = 1;
                                    }
                                    else {
                                        if (isset($_GET['newQty'])) {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $_GET['newQty'];
                                            $jml_nambah = $jml_diperlukan - $jml_sudah_diambil;
                                        }
                                        else {
                                            $jml_sudah_diambil = $_SESSION[$cCode]['items'][$id]['jml'];
                                            $jml_diperlukan = $jml_sudah_diambil + $jml;
                                            $jml_nambah = $jml;
                                        }
                                    }
                                    //  region validasi stok
                                    if ($jml_nambah > $jml_now) {
                                        $this->tEcho("<script>top.alert('stok $nama tidak cukup. (perlu $jml_diperlukan, nambah $jml_nambah stok $jml_now)')");
                                        $this->tEcho("</script>");
                                        die();
                                    }
                                    //  endregion validasi stok


                                    $this->db->trans_start();

                                    //  region update locker active
                                    $where = array(
                                        "id" => $row->id,
                                    );
                                    $data_active = array(
                                        "jumlah" => $jml_now - $jml_nambah,
                                        "state" => "active",
                                    );
                                    $c->updateData($where, $data_active);
//                                    cekHere($this->db->last_query());
                                    //  endregion update locker active


                                    //  region locker hold
                                    $array_hold_sebelumnya = $c->cekLoker($this->session->login['cabang_id'], $id, "hold", $this->session->login['id'], "0", $this->session->login['gudang_id']);
                                    if (sizeof($array_hold_sebelumnya) > 0) {
                                        $where = array(
                                            "id" => $array_hold_sebelumnya['id'],
                                        );
                                        $data_hold = array(
                                            "jumlah" => $array_hold_sebelumnya['jumlah'] + $jml_nambah,
                                        );
                                        $c->updateData($where, $data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    else {
                                        $data_hold = array(
                                            "jenis" => "produk",
                                            "cabang_id" => $this->session->login['cabang_id'],
                                            "produk_id" => $id,
                                            "nama" => $nama,
                                            "satuan" => $row->satuan,
                                            "state" => "hold",
                                            "jumlah" => $jml_nambah,
                                            "oleh_id" => $this->session->login['id'],
                                            "oleh_nama" => $this->session->login['nama'],
                                            "gudang_id" => $this->session->login['gudang_id'],
                                        );
                                        $c->addData($data_hold);
                                        cekHere($this->db->last_query());
                                    }
                                    //  endregion locker hold


                                    $this->db->trans_complete() or die("Gagal bro");

                                    $tmpJml = $jml_diperlukan;

                                }
                            }
                            else {
                                mati_disini("tidak ditemukan item " . $row->nama . " di locker stock.");
                            }

                        }

                        /* ----------------------------------------------------------------------------------------------
                         * memasukan session items
                         * ----------------------------------------------------------------------------------------------*/
                        $fieldSrcs = isset($CI->config->item("heTransaksi_ui")[$CI->jenisTr]['shoppingCartFieldSrc']) ? $CI->config->item("heTransaksi_ui")[$transaksi_jenis]['shoppingCartFieldSrc'] : array("nama" => "nama");
                        if (!array_key_exists($id, $_SESSION[$cCode]['items'])) {
                            $tmp = array(
                                "handler" => $CI->uri->segment(1) . "/" . $CI->uri->segment(2),
                                "id" => $id,
                                "jml" => $tmpJml,
                                "harga" => 0,
                                "subtotal" => 0,
                            );

                            if (sizeof($priceConfig) > 0) {
                                $mdlName = $priceConfig['model'];
                                $CI->load->model("Mdls/" . $mdlName);
                                $h = new $mdlName();
                                $h->addFilter("produk_id='$id'");
                                $h->addFilter("status='1'");
                                //                                $h->addFilter("jenis_value='" . $priceConfig['label'] . "'");
                                $h->addFilter("jenis_value in ('" . implode("','", $priceConfig['label']) . "')");
                                $h->addFilter("toko_id=" . $toko_id);
                                $tmpH = $h->lookupAll($id)->result();
//                                cekMerah($CI->db->last_query());

                                if (sizeof($tmpH) > 0) {
                                    $rawPrices = array();
                                    foreach ($tmpH as $hSpec) {
                                        foreach ($priceConfig['key_label'] as $key => $val) {
                                            if ($key == $hSpec->jenis_value) {
                                                $rawPrices[$key] = isset($hSpec->nilai) ? $hSpec->nilai : 0;
                                            }
                                        }
                                    }
                                    $prices = normalizePrices("produk", $rawPrices);
                                    if (sizeof($prices) > 0) {
                                        foreach ($prices as $k => $v) {
                                            $tmp[$k] = $v;
                                        }
                                        $tmp['harga'] = isset($tmp[$priceConfig['mainSrc']]) ? $tmp[$priceConfig['mainSrc']] : 0;
                                    }
                                }

                            }

                            foreach ($fieldSrcs as $key => $src) {
                                $tmpEx = $cal->multiExplode($src);
                                arrPrint($tmpEx);
                                if (sizeof($tmpEx) > 1) {//===berarti mengandung karakter simbol perhitungan
                                    cekBiru("$key perhitungan");
                                    $newSrc = $src;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        $this->tEcho("$key2 - $val2 <br>");
                                        if (!is_numeric($val2)) {
                                            if (isset($tmp[$val2]) && $tmp[$val2] > 0) {
                                                $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            }
                                            else {
                                                $newSrc = str_replace($val2, 0, $newSrc);
                                            }
                                        }

                                    }
                                    cekBiru("$$src -> $newSrc -> " . $cal->calculate($newSrc));
                                    $tmp[$key] = $cal->calculate($newSrc);
                                }
                                else {
                                    cekBiru("$key BUKAN perhitungan");
                                    $tmp[$key] = $row->$src;
                                }


                            }

                            //===perhitungan subtotal
                            $cal = new FieldCalculator();


                            if (sizeof($arrMain) > 0) {
                                foreach ($arrMain as $key => $val) {
                                    $_SESSION[$cCode][$key] = $val;
                                }
                            }

                            if ($subAmountConfig != null) {
                                $tmpEx = $cal->multiExplode($subAmountConfig);
                                if (sizeof($tmpEx) > 1) {
                                    $newSrc = $subAmountConfig;
                                    foreach ($tmpEx as $key2 => $val2) {
                                        if (isset($tmp[$val2])) {
                                            $newSrc = str_replace($val2, $tmp[$val2], $newSrc);
                                            cekKuning("$val2 direplace dengan " . $tmp[$val2]);
                                        }
                                        else {
                                            $newSrc = str_replace($val2, "0", $newSrc);
                                            cekKuning("$val2 direplace dengan NOL");
                                        }

                                    }
                                    $subtotal = $cal->calculate($newSrc);
                                    cekHijau("subtotal dari perhitungan $subAmountConfig $newSrc");

                                }
                                else {
                                    $subtotal = 0;
                                    cekHijau("subtotal dari perhitungan yang gak ada");
                                }
                            }
                            else {
                                $subtotal = 0;
                                cekHijau("subtotal NOL");
                            }
                            $tmp["subtotal"] = $subtotal;
                            $_SESSION[$cCode]['items'][$id] = $tmp;

                            //                    die();
                        }
                        else {
                            if (isset($_GET['newQty'])) {
                                $_SESSION[$cCode]['items'][$id]['jml'] = $_GET['newQty'];
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }
                            else {
                                $_SESSION[$cCode]['items'][$id]['jml'] += $jml;
                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }

                            if (sizeof($itemNumLabels) > 0) {
                                $this->tEcho("iterating subNums.. @" . __LINE__);
                                foreach ($itemNumLabels as $key => $label) {
                                    if (isset($_GET[$key]) && $_GET[$key] > 0) {
                                        $newValue = $_GET[$key];
                                        $tmp[$key] = $newValue;
                                        $_SESSION[$cCode]['items'][$id][$key] = $newValue;
                                        $this->tEcho("replacing value for $key with " . $newValue . "<br>");
                                    }

                                }

                                foreach ($itemNumLabels as $key => $label) {
                                    $_SESSION[$cCode]['items'][$id]["sub_" . $key] = ($_SESSION[$cCode]['items'][$id][$key] * $_SESSION[$cCode]['items'][$id]["jml"]);
                                }
                                $_SESSION[$cCode]['items'][$id]['sub_nett'] = ($_SESSION[$cCode]['items'][$id]['nett'] * $_SESSION[$cCode]['items'][$id]['jml']);

                                $_SESSION[$cCode]['items'][$id]['subtotal'] = ($_SESSION[$cCode]['items'][$id]['jml'] * $_SESSION[$cCode]['items'][$id]['harga']);
                            }


                        }
                    }

                    if (sizeof($_SESSION[$cCode]['items']) > 0) {
                        $_SESSION[$cCode]['main']['harga'] = 0;
                        $_SESSION[$cCode]['out_master']['harga'] = 0;

                        /*
                         * akumulasi item ke main
                         * */
                        foreach ($_SESSION[$cCode]['items'] as $id => $iSpec) {
                            $_SESSION[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                            $_SESSION[$cCode]['out_master']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                        }
                    }

                }
                else {
                    cekMerah("tidak ada itemnya!");
                    die();
                }

            }
        }

        if (sizeof($arrTrID) > 0) {
            $_SESSION[$cCode]['main']['references'] = $arrTrID;
            $_SESSION[$cCode]['out_master']['references'] = $arrTrID;
        }
        if (isset($_GET['singleRefID']) && strlen($_GET['singleRefID']) > 0) {
            $_SESSION[$cCode]['main']['singleReference'] = $_GET['singleRefID'];
            $_SESSION[$cCode]['out_master']['singleReference'] = $_GET['singleRefID'];
        }

    }

    public function wizard_startup()
    {
        /* ------------------------------------------------
        *  companu profile cek
        * ------------------------------------------------*/
        $this->CI->load->model("Mdls/MdlCompany");
        $cp = new MdlCompany();
        $cp->setTokoId(my_toko_id());

        $cpSrc = $cp->callDatas();
        $neracaStatus = $cpSrc->neraca_ok;

        $cp_koloms = array(
//            "supplies_ok"  => array(
//                "label" => "bahan",
//                "link"  => "Converter/index/formSupplies"
//            ),
//            "produk_ok"    => array(
//                "label" => "produk",
//                "link"  => "Converter/index/formProduk"
//            ),
//            "komposisi_ok" => array(
//                "label" => "komposisi",
//                "link"  => "Converter/index/formProdukKomposisi",
//            ),
//            "stok_ok"      => array(
//                "label" => "persediaan",
//                "link"  => "Converter/index/formSuppliesRek",
//            ),
            "neraca_ok" => array(
                "label" => "neraca",
                "icon" => "fa-balance-scale",
                "link" => "TransaksiPindahBuku/index",
            ),
        );

        $link_now = "";
        $vas_ok = array();
        $strFree = "";
        $nom = 0;
        $next_on = 1;
        foreach ($cp_koloms as $cp_kolom => $cp_datum) {
            $nom++;
            $ok = $cpSrc->$cp_kolom;
            $badge_done = $ok == 1 ? "badge-green" : "";
            $var_ok[$cp_kolom] = $ok;

            $link_data = isset($cp_datum['link']) ? base_url() . $cp_datum['link'] : "#";

            if ($nom == 1 && $ok == 1) {
                $next_ok = "text-red";
            }
            else {
                $next_ok = "text-red";
            }

            $next_ok = ($nom + $ok) == ($next_on + 1) ? "" : "text-red";
            // if(($nom + $ok) == ($next_on + 1)){
            if ($ok == 0 && (($nom + $ok) == ($next_on + 1))) {
                $text_color = "text-grey";
                $link_data_f = "#";
            }
            else {
                $text_color = "";
                $link_now = $link_data_f = $link_data;
            }

            $next_on = $nom + $ok;

            // $text_color = $ok == 0 ? "text-grey" : "";

            $cp_label = $cp_datum['label'];
            $cp_icon = isset($cp_datum['icon']) ? $cp_datum['icon'] : 'fa-database';
            $strFree .= "<a href='$link_data_f' title='go to $cp_label' data-toggle='tooltip' class='btn btn-app text-uppercase active $next_ok $text_color'><span class='badge $badge_done'>$nom</span><i class='fa $cp_icon'></i>$cp_label</a>";
        }

        $vars['html'] = $strFree;
        $vars['link_now'] = $link_now;
        $vars['step_status'] = $var_ok;

        return $vars;

    }

    public function undoneItem()
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harap diset");

        $this->CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $registryFields = $tr->getRegistryFields();


    }

    public function callJmlTransakional($TAHUN)
    {
        $code_aliasing = arrCodeAliasing(-1);
        $this->CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $condites = array(
            "year(dtime)" => $TAHUN,
            "link_id" => 0,
        );
        $src_0 = $tr->lookupByCondition($condites)->result();
        // showLast_query("kuning");
        /* -----------------------------------------------------------
         * raw data transaksi
         * -----------------------------------------------------------*/
        foreach ($src_0 as $item) {
            $bln = formatTanggal($item->dtime, 'm');
            $thn = formatTanggal($item->dtime, 'Y');
            $thn_bln = "$thn-$bln";

            $jenis[$thn_bln][$item->jenis][] = $item->id;
            $jenis_ytd[$item->jenis][] = $item->id;
        }
        // cekKuning(sizeof($jenis['2022-08']['582']));
        /* -----------------------------------------------------------
         * jml data per bulan
         * -----------------------------------------------------------*/
        $jml_bulan = (sizeof($jenis));
        foreach ($jenis as $tahun => $jenis_data) {
            foreach ($jenis_data as $jenisTr => $jenis_datum) {
                $avg_harian = sizeof($jenis_datum) / formatTanggal($tahun, 'y');
                $bulanan[$tahun][$jenisTr]['total'] = sizeof($jenis_datum);
                $bulanan[$tahun][$jenisTr]['avg_harian'] = $avg_harian;

                if (!isset($sum_avg_bulanan[$jenisTr]['avg_harian'])) {
                    $sum_avg_bulanan[$jenisTr]['avg_harian'] = 0;
                }
                $sum_avg_bulanan[$jenisTr]['avg_harian'] += $avg_harian;
            }
        }
        /* -----------------------------------------------------------
         * jml data YTD
         * -----------------------------------------------------------*/
        foreach ($jenis_ytd as $jenisTr => $item) {
            $ytd[$jenisTr]['total'] = sizeof($item);
            $ytd[$jenisTr]['avg_harian'] = $sum_avg_bulanan[$jenisTr]['avg_harian'] / $jml_bulan;
        }

        $jml_transaksi['total'] = sizeof($src_0);
        /* -----------------------------------------------------------
         * omset YTD
         * -----------------------------------------------------------*/
        $koloms = array(
            "DATE_FORMAT(dtime,'%Y-%m') as 'thn_bln'",
            "sum(debet) as sum_debet",
            "sum(kredit) as sum_kredit",
        );
        $condites = array(
            "year(dtime)" => $TAHUN,
            "cabang_id>" => 0,
            "transaksi_id>" => 0,
        );

        $this->CI->db->select($koloms);
        $this->CI->db->where($condites);
        // $this->CI->db->group_by("month(dtime),year(dtime)");
        $this->CI->db->group_by("thn_bln");
        $tableName = "__rek_master__penjualan";
        $juals = $this->CI->db->get($tableName)->result_array();
        // showLast_query("kuning");
        $omset_bulanan = array();
        foreach ($juals as $jual_data) {
            $omset_bulanan[$jual_data['thn_bln']] = $jual_data['sum_kredit'] * 1;

            if (!isset($jual["sum_debet_ytd"])) {
                $jual["sum_debet_ytd"] = 0;
            }
            $jual["sum_debet_ytd"] += $jual_data['sum_debet'];

            if (!isset($jual["sum_kredit_ytd"])) {
                $jual["sum_kredit_ytd"] = 0;
            }
            $jual["sum_kredit_ytd"] += $jual_data['sum_kredit'];
        }

        // arrPrint($jual);
        $omset_ytd = ($jual["sum_kredit_ytd"] * 1) - ($jual["sum_debet_ytd"] * 1);

        /* -----------------------------------------------------------
         * return YTD
         * -----------------------------------------------------------*/
        $this->CI->db->select($koloms);
        $this->CI->db->where($condites);
        $this->CI->db->group_by("thn_bln");
        $tableName = "__rek_master__return_penjualan";
        $returns = $this->CI->db->get($tableName)->result_array();
        // showLast_query("here");
        $return = array();
        $return_bulanan = array();
        foreach ($returns as $return_data) {
            $return_bulanan[$return_data['thn_bln']] = $return_data['sum_debet'] * 1;

            if (!isset($return["sum_debet_ytd"])) {
                $return["sum_debet_ytd"] = 0;
            }
            $return["sum_debet_ytd"] += $return_data['sum_debet'];
        }
        $return_ytd = isset($return["sum_debet_ytd"]) ? $return["sum_debet_ytd"] * 1 : 0;
        // $jual_netto = $omset_ytd - $return_ytd;

        // cekHijau($jual_netto);
        // arrPrintHijau($code_aliasing);
        $datas = array();
        $datas = array(
            "trcode" => $code_aliasing,
            "omset_bulanan" => $omset_bulanan,
            "omset_ytd" => $omset_ytd,
            "return_ytd" => $return_ytd,
            "return_bulanan" => $return_bulanan,
            // "penjualan_netto_ytd" => $jual_netto,
            "jml_transaksi" => $jml_transaksi,
            "jml_jenistr_ytd" => $ytd,
            "jml_jenistr_bulanan" => $bulanan,
        );
        // arrPrintHijau($jenis);
        return $datas;
    }

    public function callTransaksiBeforeOpname()
    {
        $this->CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $jenis_gantung = $tr->callGantunganTransaksi(true);

        $jml = sizeof($jenis_gantung);

        $var = array();
        $var["jml"] = $jml;
        $var["datas"] = $jenis_gantung;
        $var["link"] = "opname/Opname/cekTransaksiGantung";

        return $var;
    }

    public function cekOpnameAktive($cabang_id)
    {
        $this->CI->load->model("Mdls/MdlDashboardOpname");
        $do = new MdlDashboardOpname();

        $do->setCabangId($cabang_id);
        $src_do = $do->cekOpnameAktive();

        $var = array();
        $var["jml"] = sizeof($src_do);
        $var["data"] = $src_do;
        // $var["link"] = "opname/Opname/cekTransaksiGantung";
//arrPrintCyan($var);

        return $var;
    }

    //------------------------------------
    public function cekSalesOrder($shortRequestFields2Config, $items = array(), $main = array(), $linkSelect = "")
    {
//        arrPrintWebs($items);
        $historyFields = $shortRequestFields2Config["fields"];
        $historyFieldsDetail = $shortRequestFields2Config["fieldsDetail"];
//        $linkSelect = $shortRequestFields2Config["linkSelect"];
//        cekHere("$linkSelect");

        $this->CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tmpHist = array();
        $link_swap = "";
        $tr->addFilter("transaksi.div_id='" . $this->CI->session->login['div_id'] . "'");
        $tr->addFilter("transaksi_data.valid_qty>0");
        if (sizeof($items) > 0) {
            $itemsKey = array_keys($items);
            $tr->addFilter("transaksi_data.produk_id in ('" . implode("','", $itemsKey) . "')");
        }
        if (isset($shortRequestFields2Config["filter"]) && (sizeof($shortRequestFields2Config["filter"] > 0))) {
            foreach ($shortRequestFields2Config["filter"] as $ff) {
                $tr->addFilter($ff);
            }
        }
        $tmpHistJoined = $tr->lookupJoined_OLD()->result();
//        showLast_query("lime");
        $tmpHist = $tr->lookupRecentUndoneEntries_joined(array())->result();
//        showLast_query("lime");
//        cekHijau(sizeof($tmpHist));
        $strOnprog = "";
        if (sizeof($tmpHist) > 0) {
            if (sizeof($tmpHistJoined) > 0) {
                $arrJoinedData = array();
                $arrJoinedDataSimple = array();
                $arrJoinedHasil = array();
                foreach ($tmpHistJoined as $joinedSpec) {
//                    arrPrintHijau($joinedSpec);
                    $arrJoinedData[$joinedSpec->transaksi_id][] = $joinedSpec;
                    $arrJoinedDataSimple[$joinedSpec->transaksi_id][$joinedSpec->produk_id] = array(
                        "produk_id" => $joinedSpec->produk_id,
                        "produk_nama" => $joinedSpec->produk_nama,
                        "produk_jml" => $joinedSpec->produk_ord_jml,
//                        "valid_qty" => $joinedSpec->valid_qty,
                        "reference_id_top" => $joinedSpec->id_top,
                        "reference_nomer_top" => $joinedSpec->nomer_top,
                        "reference_id" => $joinedSpec->transaksi_id,
                        "reference_nomer" => $joinedSpec->nomer,
                        "reference_customers_id" => $joinedSpec->customers_id,
                        "reference_customers_nama" => $joinedSpec->customers_nama,
                        "reference_cabang_id" => $joinedSpec->cabang_id,
                        "reference_cabang_nama" => $joinedSpec->cabang_nama,
                        "reference_gudang_id" => $joinedSpec->gudang_id,
                        "reference_gudang_nama" => $joinedSpec->gudang_nama,
                        "reference_salesman_id" => $joinedSpec->salesman_id,
                        "reference_salesman_nama" => $joinedSpec->salesman_nama,
                        "reference_gudang_status_id" => $joinedSpec->gudang_status_id,
                        "reference_gudang_status_nama" => $joinedSpec->gudang_status_nama,
                        "reference_gudang_status_jenis" => $joinedSpec->gudang_status_jenis,
                        "gudang_status_id" => $joinedSpec->gudang_status_id,
                        "gudang_status_nama" => $joinedSpec->gudang_status_nama,
                        "gudang_status_jenis" => $joinedSpec->gudang_status_jenis,
                    );
                }
                foreach ($arrJoinedData as $trID => $joinedSpec) {
                    $strJoined = "<div class='table-responsive '>";
                    $strJoined .= "<table id='arrayOnProgress_step' class='table datatables stripe compact nowarp order-column table-condensed table-bordered no-padding' 
                        style='border:solid red 0px;margin:0px;'>";
                    $strJoined .= "<thead>";
                    $strJoined .= "<tr class='text-uppercase' line=" . __LINE__ . ">";
                    if (sizeof($historyFieldsDetail) > 0) {
                        $strJoined .= "<th class=''>No.</th>";
                        foreach ($historyFieldsDetail as $key => $label) {
                            $strJoined .= "<th class=''>";
                            if (is_array($label)) {
                                $strJoined .= isset($label['label']) ? $label['label'] : "-";
                            }
                            else {
                                $strJoined .= $label;
                            }
                            $strJoined .= "</th>";
                        }
                    }
                    $strJoined .= "</tr>";
                    $strJoined .= "</thead>";

                    $strJoined .= "<tbody>";
                    $no = 0;
                    foreach ($joinedSpec as $iii => $val) {
                        $no++;
                        $strJoined .= "<tr line=" . __LINE__ . ">";
                        $strJoined .= "<td>$no</td>";
                        if (sizeof($historyFieldsDetail) > 0) {
                            foreach ($historyFieldsDetail as $key => $label) {
                                $strJoined .= "<td>";
                                $strJoined .= $val->$key;
                                $strJoined .= "</td>";
                            }
                        }
                        $strJoined .= "</tr>";
                    }
                    $strJoined .= "</tbody>";
                    $strJoined .= "</table>";
                    $strJoined .= "</div>";
                    $arrJoinedHasil[$trID] = $strJoined;
                }
            }
            //------------------------------------
//            arrPrintHijau($arrJoinedHasil);
            //------------------------------------
            $arrayOnProgress = array();
            $numb = 0;
            foreach ($tmpHist as $row) {
//                cekHere("pID: " . $row->produk_id . ", nama: " . $row->produk_nama . ", trID: " . $row->transaksi_id);
                $numb++;
                $tmp = array();
                foreach ($historyFields as $fName => $fLabel) {
                    if (isset($row->$fName)) {
                        if (is_numeric($row->$fName)) {
                            if (!isset($sumFooter[$fName])) {
                                $sumFooter[$fName] = 0;
                            }
                            $sumFooter[$fName] += $row->$fName;
                        }
                    }
                    if (is_array($fLabel)) {
                        $hisStep = $fLabel['step'];
                        $hisKey = $fLabel['key'];
                        if (isset($row->ids_his)) {
                            if ($hisKey == "nomer") {
                                $returnVal = showHistoriGlobalNumbers($row->ids_his, $hisStep, true, $row->jenis_master);
                                if ($returnVal == "") {
                                    $tmp[$fName] = "-";
                                }
                                else {
                                    $tmp[$fName] = $returnVal;
                                }
                            }
                            else {
                                $ids_his_decode = blobDecode($row->ids_his);
                                if (isset($ids_his_decode[$hisStep][$hisKey])) {
                                    $tmp[$fName] = $ids_his_decode[$hisStep][$hisKey];
                                }
                                else {
                                    $tmp[$fName] = "-";
                                }
                            }
                        }
                        else {
                            $tmp[$fName] = "-";
                        }
                    }
                    else {
                        $tmp[$fName] = isset($row->$fName) ? formatField_he_format($fName, $row->$fName) : formatField_he_format($fName, 0);
                    }
                    if ($fName == "no") {
                        $tmp[$fName] = formatField_he_format($fName, $numb);
                    }
                    if ($fName == "radio") {
                        $trID = $row->transaksi_id;
                        $transaksiKey = "transaksiIDSelected";
                        $dataSelected = isset($arrJoinedDataSimple[$trID]) ? $arrJoinedDataSimple[$trID] : array();
                        $dataSelectedBlob = blobEncode($dataSelected);
                        $checked = ($trID == $main[$transaksiKey]) ? "checked" : "";
                        // onclick=\"document.getElementById('result').src='$linkSelect?key=$transaksiKey&data=$dataSelectedBlob&val='+this.value\"
                        $tmp[$fName] = "<input type='radio' name='nota' value='$trID' $checked 
                            onclick=\"document.getElementById('result').src='$linkSelect?key=$transaksiKey&data=$dataSelectedBlob&val='+this.value\"
                            >";
                    }
                }
                if (isset($arrJoinedHasil[$row->transaksi_id])) {
                    $tmp["detail_fields"] = $arrJoinedHasil[$row->transaksi_id];
                }
                $arrayOnProgress[] = $tmp;
            }
            //------------------------------------


            if (sizeof($arrayOnProgress) > 0) {
                $strOnprog .= "<div class='table-responsive '>";
                $strOnprog .= "<table id='arrayOnProgress_step' class='table datatables stripe compact nowarp order-column table-condensed table-bordered no-padding' style='border:solid red 0px;'>";
                $strOnprog .= "<thead>";
                $strOnprog .= "<tr class='text-uppercase' line=" . __LINE__ . ">";
                if (sizeof($historyFields) > 0) {
                    $strOnprog .= "<th class=''>No.</th>";
                    foreach ($historyFields as $key => $label) {
                        $strOnprog .= "<th class=''>";
                        if (is_array($label)) {
                            $strOnprog .= isset($label['label']) ? $label['label'] : "-";
                        }
                        else {
                            $strOnprog .= $label;
                        }
                        $strOnprog .= "</th>";
                    }
                }
                $strOnprog .= "</tr>";
                $strOnprog .= "</thead>";

                $strOnprog .= "<tbody>";
                $no = 0;
                foreach ($arrayOnProgress as $key => $val) {
                    //----------------------
                    $background_color = isset($arrayOnprogressMarking[$key]['style']) ? $arrayOnprogressMarking[$key]['style'] : "";
                    $no++;
                    $strOnprog .= "<tr line=" . __LINE__ . " style='$background_color'>";
                    $strOnprog .= "<td>$no</td>";
                    if (sizeof($historyFields) > 0) {
                        foreach ($historyFields as $key => $label) {
                            $strOnprog .= "<td>";
                            $strOnprog .= $val[$key];
                            $strOnprog .= "</td>";
                        }
                    }
                    $strOnprog .= "</tr>";
                }
                $strOnprog .= "</tbody>";

                if (isset($sumFooter) && sizeof($sumFooter) > 0) {
                    $strOnprog .= "<tfoot>";
                    $strOnprog .= "<tr line=" . __LINE__ . ">";
                    if (sizeof($historyFields) > 0) {
                        foreach ($historyFields as $key => $label) {
                            $strOnprog .= "<th>";
                            $strOnprog .= "-";
                            $strOnprog .= "</th>";
                        }
                        $strOnprog .= "<th>-</th>";
                    }
                    $strOnprog .= "</tr>";
                    $strOnprog .= "</tfoot>";
                }
                $strOnprog .= "</table>";
                $strOnprog .= "</div>";
            }
        }
        return $strOnprog;

    }

    //------------------------------------
    public function cekTransaksiActiveRejectByID($transaksi_id, $stepNumber, $configUi)
    {
        switch ($stepNumber) {
            case "1":
                cekMerah("mereject request, maka lanjut saja, tidak perlu cek aktif/non aktif");
                break;
            default:
                $this->CI->load->model("MdlTransaksi");
                $tr = New MdlTransaksi();
                $tr->addFilter($tr->getTableNames()["main"] . ".id=$transaksi_id");
                $tr->addFilter($tr->getTableNames()["detail"] . ".next_substep_code!=''");
                $tr->addFilter($tr->getTableNames()["detail"] . ".next_subgroup_code!=''");
                $tr->addFilter($tr->getTableNames()["detail"] . ".sub_step_number>0");
                $tr->addFilter($tr->getTableNames()["detail"] . ".valid_qty>0");
                $tr->addFilter($tr->getTableNames()["detail"] . ".trash=0");
                $tmpHist = $tr->lookupRecentUndoneEntries_joined(array())->result();

//                showLast_query("biru");
//                cekBiru(count($tmpHist));

                if (sizeof($tmpHist) > 0) {
                    $hasil = true;
                }
                else {
                    $hasil = false;
                }

                if ($hasil == false) {
                    $msg = "REJECT 1 STEP gagal disimpan. <br>Sesi anda habis/kadaluarsa. Silahkan login ulang. ";
                    $msg .= "<br>Bila pesan ini masih muncul, silahkan hubungi admin. code: " . __LINE__ . " | tr: $transaksi_id | step: $stepNumber";
                    mati_disini($msg);
                }
                else {
                    cekHijau("transaksi sebelumnya berhasil aktif, silahkan lanjut...");
                }
                break;
        }
    }

    //------------------------------------
    public function rejectInvoice()
    {
        if ($this->param["tr_id_dibatalkan"] == NULL) {
            $msg = "Pembatalan gagal disimpan. Transaksi yang anda batalkan kadaluarsa/sudah dibatalkan. Silahkan refresh halaman ini. ";
            $msg .= "Bila notif ini masih tampil, segera hubungi admin. code: " . __LINE__;
            mati_disini($msg);
        }

        $jenisTrReference = $this->jenisTrReference;
        $jenisTrPembatalan = $this->jenisTrPembatalan;
        $tr_id_dibatalkan = $this->param["tr_id_dibatalkan"];
        $insertID_pembatalan = $this->param["insert_id"];
        $tmpNomorNota_pembatalan = $this->param["insert_num"];
        $oleh_id_pembatalan = $this->param["oleh_id"];
        $oleh_nama_pembatalan = $this->param["oleh_nama"];
        $cabang_id_pembatalan = $this->param["cabang_id"];
        $cabang_nama_pembatalan = $this->param["cabang_nama"];
        $pakai_ini = 0;

        $this->CI->load->model("MdlTransaksi");
        $t = new MdlTransaksi();
        $t->setfilters(array());
        $t->addFilter("jenis='$jenisTrReference'");// 4822
        $t->addFilter("reference_id='$tr_id_dibatalkan'");// transaksi_id yang dibatalkan
        $tTmp = $t->lookUpAll()->result();
        showLast_query("biru");
        cekBiru(count($tTmp));
        if (sizeof($tTmp) > 0) {
            if ($pakai_ini == 1) {
                $data_update = array(
                    "trash_4" => 1,
                    "deskripsi" => "Pembatalan transaksi",
                    "cancel_dtime" => date("Y-m-d H:i:s"),
                    "cancel_name" => my_name(),
                    "cancel_id" => my_id(),
                    "cancel_transaksi_id" => $insertID_pembatalan,
                    "cancel_transaksi_nomer" => $tmpNomorNota_pembatalan,
                    "cancel_transaksi_jenis" => $jenisTrPembatalan,
                );
                $data_where = array(
                    "id" => $tTmp[0]->id,// transaksi_id invoice
                );
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->updateData($data_where, $data_update);
                showLast_query("orange");
            }
            else {
                $data_update = array(
                    "trash_4" => 1,
                    "deskripsi" => "Pembatalan transaksi",
                    "cancel_dtime" => date("Y-m-d H:i:s"),
                    "cancel_name" => my_name(),
                    "cancel_id" => my_id(),
                    "cancel_transaksi_id" => $insertID_pembatalan,
                    "cancel_transaksi_nomer" => $tmpNomorNota_pembatalan,
                    "cancel_transaksi_jenis" => $jenisTrPembatalan,
                );
                $data_where = array(
                    "id" => $tTmp[0]->id,// transaksi_id invoice
                );
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->updateData($data_where, $data_update);
                showLast_query("orange");

                $configUiMasterModulJenis = loadConfigModulJenis_he_misc($jenisTrReference, "coTransaksiUi");
                $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($jenisTrReference, "coTransaksiCore");
                $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($jenisTrReference, "coTransaksiLayout");
                $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($jenisTrReference, "coTransaksiValues");
                $modul_transaksi = $this->CI->config->item("heTransaksi_ui")[$jenisTrReference]["modul"];

                $tmpTr = $tTmp;
                $no = $currentInsertID = $insertID = $tmpTr[0]->id;
                $currentMasterID_step_1 = $currentMasterID = $tmpTr[0]->id_master;
                $transaksiNomer_reference = $currentNomer = $insertNum = $tmpTr[0]->nomer;
                $transaksiJenisLabel_reference = $tmpTr[0]->jenis_label;
                $stepNumCurrent = $thisStepNumber = $tmpTr[0]->step_number;
                $pembayaran = $tmpTr[0]->pembayaran;
                $jenisTr_selected = $tmpTr[0]->jenis;
                $jenisTr_master_selected = $tmpTr[0]->jenis_master;

                //region swap from registry, transaksi yang dibuka saat ini
                $tr = new MdlTransaksi();
                $tmpReg = $tr->lookupDataRegistriesByMasterID($no)->result();
                cekmerah($this->CI->db->last_query());
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $row) {
                        foreach ($row as $key_reg => $val_reg) {
                            if ($key_reg != "transaksi_id") {
                                $var = $key_reg;
                                $$var = unserialize(base64_decode($val_reg));
                            }
                        }
                    }
                }
                //endregion


                if (isset($tableIn_master) && sizeof($tableIn_master) > 0) {
                    if (isset($configUiMasterModulJenis["connectToReject"][$stepNumCurrent])) {
                        if ($configUiMasterModulJenis["connectToReject"][$stepNumCurrent]["enabled"] == true) {
                            $masterID = $currentMasterID;
                            $connecTo = $configUiMasterModulJenis["connectToReject"][$stepNumCurrent]["connectTo"];
                            $tCodeTargetJenisTransaksi = $configUiMasterModulJenis["connectToReject"][$stepNumCurrent]["connectTo"];
//                            $modul_transaksi = $this->modul;
                            cekHijau("START menjalankan REJECT... [$masterID] [$connecTo]");
                            if ($connecTo == NULL) {
                                mati_disini("kode reject transaksi tidak dikenali. segera hubungi admin.");
                            }
//                            $oldCode = $cCode;
                            $cCode = "_TR_" . $connecTo;
                            $session[$cCode] = array(
                                'main' => isset($main) ? $main : array(),
                                'items' => isset($items) ? $items : array(),
                                'items2' => isset($items2) ? $items2 : array(),
                                'items2_sum' => isset($items2_sum) ? $items2_sum : array(),
                                'itemSrc' => isset($itemSrc) ? $itemSrc : array(),
                                'itemSrc_sum' => isset($itemSrc_sum) ? $itemSrc_sum : array(),
                                'items3' => isset($items3) ? $items3 : array(),
                                'items3_sum' => isset($items3_sum) ? $items3_sum : array(),
                                'items4' => isset($items4) ? $items4 : array(),
                                'items4_sum' => isset($items4_sum) ? $items4_sum : array(),
                                'items5_sum' => isset($items5_sum) ? $items5_sum : array(),
                                'items6_sum' => isset($items6_sum) ? $items6_sum : array(),
                                'items7_sum' => isset($items7_sum) ? $items7_sum : array(),
                                'items8_sum' => isset($items8_sum) ? $items8_sum : array(),
                                'items9_sum' => isset($items9_sum) ? $items9_sum : array(),
                                'items10_sum' => isset($items10_sum) ? $items10_sum : array(),
                                'rsltItems' => isset($rsltItems) ? $rsltItems : array(),
                                'rsltItems2' => isset($rsltItems2) ? $rsltItems2 : array(),
                                'rsltItems3' => isset($rsltItems3) ? $rsltItems3 : array(),
                                'tableIn_master' => isset($tableIn_master) ? $tableIn_master : array(),
                                'tableIn_detail' => isset($tableIn_detail) ? $tableIn_detail : array(),
                                'tableIn_detail2_sum' => isset($tableIn_detail2_sum) ? $tableIn_detail2_sum : array(),
                                'tableIn_detail_rsltItems' => isset($tableIn_detail_rsltItems) ? $tableIn_detail_rsltItems : array(),
                                'tableIn_detail_rsltItems2' => isset($tableIn_detail_rsltItems2) ? $tableIn_detail_rsltItems2 : array(),
                                'tableIn_master_values' => isset($tableIn_master_values) ? $tableIn_master_values : array(),
                                'tableIn_detail_values' => isset($tableIn_detail_values) ? $tableIn_detail_values : array(),
                                'tableIn_detail_values_rsltItems' => isset($tableIn_detail_values_rsltItems) ? $tableIn_detail_values_rsltItems : array(),
                                'tableIn_detail_values_rsltItems2' => isset($tableIn_detail_values_rsltItems2) ? $tableIn_detail_values_rsltItems2 : array(),
                                'tableIn_detail_values2_sum' => isset($tableIn_detail_values2_sum) ? $tableIn_detail_values2_sum : array(),
                                'main_add_values' => isset($main_add_values) ? $main_add_values : array(),
                                'main_add_fields' => isset($main_add_fields) ? $main_add_fields : array(),
                                'main_elements' => isset($main_elements) ? $main_elements : array(),
                                'main_inputs' => isset($main_inputs) ? $main_inputs : array(),
                                'main_inputs_orig' => isset($main_inputs) ? $main_inputs : array(),
                                "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields'][1] : array(),
                                "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][1]) ? $configLayoutMasterModulJenis['receiptSumFields'][1] : array(),
                                "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][1] : array(),
                                "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][1] : array(),
                                "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][1]) ? $configLayoutMasterModulJenis['receiptSumFields2'][1] : array(),
                                "jurnal_index" => isset($jurnalIndex) ? $jurnalIndex : array(),
                                "postProcessor" => isset($jurnalPostProc) ? $jurnalPostProc : array(),
                                "preProcessor" => isset($jurnalPreProc) ? $jurnalPreProc : array(),
                                "revert" => isset($revert) ? $revert : array(),
                                "items_komposisi" => isset($items_komposisi) ? $items_komposisi : array(),
                                "items_noapprove" => isset($items_noapprove) ? $items_noapprove : array(),
                                "jurnalItems" => isset($jurnalItems) ? $jurnalItems : array(),
                                "componentsBuilder" => isset($componentsBuilder) ? $componentsBuilder : array(),
                            );
                            // masuk ke gerbang TableInMaster, tabel transaksi
                            $masterReplacers = array(
                                "jenis_master" => $jenisTrReference,
                                "jenis_top" => $connecTo,
                                "jenis" => $connecTo,
                                "jenis_label" => $configUiMasterModulJenis["connectToReject"][$stepNumCurrent]["label"],
                                "transaksi_jenis" => $connecTo,
                                "step_avail" => 1,
                                "step_current" => 1,
                                "step_number" => 1,
                                "next_step_code" => "",
                                "next_step_label" => "",
                                "next_group_code" => "",
                                "next_step_num" => "0",
                                "trash_4" => 1,
                                "cancel_dtime" => date("Y-m-d H:i:s"),
                                "cancel_name" => $oleh_nama_pembatalan,
                                "cancel_id" => $oleh_id_pembatalan,
                                "deskripsi" => "rejection",
                                "oleh_id" => my_id(),
                                "oleh_nama" => my_name()
                            );
                            // masuk ke gerbang main
                            $masterReplacersO = array(
                                "stepCode" => $connecTo,
                                "jenisTrTop" => $connecTo,
                                "jenisTrName" => $configUiMasterModulJenis["connectToReject"][$stepNumCurrent]["label"],
                                "jenis_master" => $jenisTrReference,
                                "jenis_top" => $connecTo,
                                "jenis" => $connecTo,
                                "jenis_label" => $configUiMasterModulJenis["connectToReject"][$stepNumCurrent]["label"],
                                "transaksi_jenis" => $connecTo,
                                "stepCode" => $connecTo,
                                "jenisTrTop" => $connecTo,
                                "jenisTrName" => $configUiMasterModulJenis["connectToReject"][$stepNumCurrent]["label"],
                                "step_avail" => 1,
                                "step_current" => 1,
                                "step_number" => 1,
                                "next_step_code" => "",
                                "next_step_label" => "",
                                "next_group_code" => "",
                                "next_step_num" => "0",
                                "olehID" => my_id(),
                                "olehName" => my_name(),
                                "masterID" => $masterID,
                            );
                            foreach ($masterReplacersO as $key => $val) {
                                $session[$cCode]['main'][$key] = $val;
                            }
                            foreach ($masterReplacers as $key => $val) {
                                $session[$cCode]['tableIn_master'][$key] = $val;
                            }

                            //region penomoran receipt
                            $this->CI->load->model("CustomCounter");
                            $cn = new CustomCounter("transaksi");
                            $cn->setType("transaksi");
                            $cn->setModul($modul_transaksi);
                            $cn->setStepCode($tCodeTargetJenisTransaksi);
                            $counterForNumber = array($configCoreMasterModulJenis['formatNotaReject']);
                            if (!in_array($counterForNumber[0], $configCoreMasterModulJenis['countersReject'])) {
                                die(__LINE__ . " Used number should be registered in 'counters' config as well");
                            }
                            $this->tEcho("<div style='background:#ff7766;'>");
                            foreach ($counterForNumber as $i => $cRawParams) {
                                $cParams = explode("|", $cRawParams);
                                $cValues = array();
                                foreach ($cParams as $param) {
                                    $cValues[$i][$param] = $session[$cCode]['main'][$param];
                                }
                                $cRawValues = implode("|", $cValues[$i]);
                                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
                            }
                            $this->tEcho("</div style='background:#ff7766;'>");

                            $stepNumber = 1;
                            $tmpNomorNota = $paramSpec['paramString'];
                            $tmpNomorNotaAlias = formatNota("nomer_nolink", $tmpNomorNota);
                            $nextProp = array(
                                "num" => 0,
                                "code" => "",
                                "label" => "",
                                "groupID" => "",
                            );
                            //endregion

                            //region dynamic counters
                            // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                            $cn = new CustomCounter("transaksi");
                            $cn->setType("transaksi");
                            $cn->setModul($modul_transaksi);
                            $cn->setStepCode($tCodeTargetJenisTransaksi);
                            $configCustomParams = $configCoreMasterModulJenis['countersReject'];
                            arrPrintHijau($configCustomParams);
                            $configCustomParams[] = "stepCode";
                            //arrPrint($configCustomParams);
                            if (sizeof($configCustomParams) > 0) {
                                $cContent = array();
                                foreach ($configCustomParams as $i => $cRawParams) {
                                    $cParams = explode("|", $cRawParams);
                                    $cValues = array();
                                    foreach ($cParams as $param) {
                                        $cValues[$i][$param] = $session[$cCode]['main'][$param];
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
                            //mati_disini();
                            //arrPrintPink($cContent);
                            //
                            //region addition on master


                            $addValues = array(
                                'counters' => $appliedCounters,
                                'counters_intext' => $appliedCounters_inText,
                                'nomer' => $tmpNomorNota,
                                'nomer2' => $tmpNomorNotaAlias,
                                'dtime' => date("Y-m-d H:i:s"),
                                'fulldate' => date("Y-m-d"),
                                "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                                "step_number" => 1,
                                "step_current" => 1,
                                "next_step_num" => $nextProp['num'],
                                "next_step_code" => $nextProp['code'],
                                "next_step_label" => $nextProp['label'],
                                "next_group_code" => $nextProp['groupID'],
                                "tail_number" => 1,
                                "tail_code" => $configUiMasterModulJenis['steps'][1]['target'],


                            );
                            foreach ($addValues as $key => $val) {
                                $session[$cCode]['tableIn_master'][$key] = $val;
                            }
                            //endregion

                            //
                            //region addition on detail
                            $addSubValues = array(
                                "sub_step_number" => 1,
                                "sub_step_current" => 1,
                                "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                                "next_substep_num" => $nextProp['num'],
                                "next_substep_code" => $nextProp['code'],
                                "next_substep_label" => $nextProp['label'],
                                "next_subgroup_code" => $nextProp['groupID'],
                                "sub_tail_number" => 1,
                                "sub_tail_code" => $configUiMasterModulJenis['steps'][1]['target'],


                            );
                            foreach ($session[$cCode]['tableIn_detail'] as $id => $dSpec) {
                                foreach ($addSubValues as $key => $val) {
                                    $session[$cCode]['tableIn_detail'][$id][$key] = $val;
                                }
                            }
                            //endregion
                            // </editor-fold>
                            //endregion

                            $addValues = array(
                                'counters' => $appliedCounters,
                                'counters_intext' => $appliedCounters_inText,
                                'nomer' => $tmpNomorNota,
                                'nomer2' => $tmpNomorNotaAlias,
                                'dtime' => date("Y-m-d H:i:s"),
                                'fulldate' => date("Y-m-d"),
                            );
                            foreach ($addValues as $key => $val) {
                                $session[$cCode]['tableIn_master'][$key] = $val;
                            }
                            $masterReplacers = array(
                                "nomer" => $tmpNomorNota,
                                "nomer2" => $tmpNomorNotaAlias,
                                "counters" => $appliedCounters,
                                "counters_intext" => $appliedCounters_inText,
                            );
                            foreach ($masterReplacers as $key => $val) {
                                $session[$cCode]['tableIn_master'][$key] = $val;
                            }
                            $detailReplacers = array(
                                "sub_step_avail" => 1,
                                "sub_step_current" => 1,
                                "sub_step_number" => 1,
                                "next_substep_num" => "",
                                "next_substep_code" => "",
                                "next_substep_label" => "",
                                "next_subgroup_code" => "",
                            );
                            if (isset($session[$cCode]['tableIn_detail']) && sizeof($session[$cCode]['tableIn_detail']) > 0) {
                                foreach ($session[$cCode]['tableIn_detail'] as $k => $dSpec) {
                                    foreach ($dSpec as $key => $val) {
                                        $session[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                                    }
                                }
                            }
                            $itemsReplacers = array(
                                "next_substep_num" => "",
                                "next_substep_code" => "",
                                "next_substep_label" => "",
                                "next_subgroup_code" => "",
                            );
                            if (isset($session[$cCode]['items']) && sizeof($session[$cCode]['items']) > 0) {
                                foreach ($session[$cCode]['items'] as $k => $dSpec) {
                                    foreach ($dSpec as $key => $val) {
                                        $session[$cCode]['items'][$k][$key] = isset($itemsReplacers[$key]) ? $itemsReplacers[$key] : $val;
                                    }
                                }
                            }

                            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
                            if (isset($session[$cCode]['tableIn_master']) && sizeof($session[$cCode]['tableIn_master']) > 0) {
                                $session[$cCode]['tableIn_master']['status_4'] = 11;
                                $session[$cCode]['tableIn_master']['trash_4'] = 1;// dibatalkan
                                $session[$cCode]['tableIn_master']['cli'] = 1;

                                $tr = new MdlTransaksi();
                                $insertID = $tr->writeMainEntries($session[$cCode]['tableIn_master']);
                                cekHitam("[$insertID] :: " . $this->CI->db->last_query());
                                $epID = $tr->writeMainEntries_entryPoint($insertID, $masterID, $session[$cCode]['tableIn_master']);
                                cekPink("[$insertID] :: " . $this->CI->db->last_query());
                                $insertNum = $session[$cCode]['tableIn_master']['nomer'];
                                $session[$cCode]['main']['nomer'] = $insertNum;
                                if ($insertID < 1) {
                                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                                }
                                $mongoList['main'] = array($insertID, $epID);
                                //==transaksi_id dan nomor nota diinject kan ke gate utama
                                $injectors = array(
                                    "transaksi_id" => $insertID,
                                    "nomer" => $tmpNomorNota,
                                    "nomer2" => $tmpNomorNotaAlias,
                                );
                                $arrInjectorsTarget = array(
                                    "items",
                                    "items2_sum",
                                    "rsltItems",
                                );
                                foreach ($injectors as $key => $val) {
                                    $session[$cCode]['main'][$key] = $val;
                                    foreach ($arrInjectorsTarget as $target) {
                                        if (isset($session[$cCode][$target])) {
                                            foreach ($session[$cCode][$target] as $xid => $iSpec) {
                                                $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                                                if (isset($session[$cCode][$target][$id])) {
                                                    $session[$cCode][$target][$id][$key] = $val;
                                                }
                                            }
                                        }
                                    }
                                }

                                //===signature
                                $dwsign = $tr->writeSignature($insertID, array(
                                    "nomer" => $session[$cCode]['main']['nomer'],
                                    "step_number" => 1,
                                    "step_code" => $connecTo,
                                    "step_name" => $configUiMasterModulJenis["connectToReject"][1]["label"],
                                    "group_code" => "",
                                    "oleh_id" => $oleh_id_pembatalan,
                                    "oleh_nama" => $oleh_nama_pembatalan,
                                    "keterangan" => $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $oleh_nama_pembatalan,
                                    "transaksi_id" => $masterID,
                                    "deskripsi" => "edit transaksi",
                                    "cabang_id" => $cabang_id_pembatalan,
                                    "cabang_nama" => $cabang_nama_pembatalan,
                                    "current_transaksi_id" => $masterID,
                                    "current_nomer" => $session[$cCode]['main']['nomer'],
                                )) or die("Failed to write signature");
                                showLast_query("kuning");
                                $mongoList['sign'][] = $dwsign;
                                $idHis = array(
                                    $stepNumber => array(
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),
                                        "olehID" => $session[$cCode]['main']['olehID'],
                                        "olehName" => $session[$cCode]['main']['olehName'],
                                        "step" => $stepNumber,
                                        "trID" => $insertID,
                                        "nomer" => $tmpNomorNota,
                                        "nomer2" => $tmpNomorNotaAlias,
                                        "counters" => $appliedCounters,
                                        "counters_intext" => $appliedCounters_inText,
                                    ),
                                );
                                $idHis_blob = blobEncode($idHis);
                                $idHis_intext = print_r($idHis, true);
                                $tr = new MdlTransaksi();
                                $dupState = $tr->updateData(array("id" => $insertID), array(
                                    "next_step_num" => $nextProp['num'],
                                    "next_step_code" => $nextProp['code'],
                                    "next_step_label" => $nextProp['label'],
                                    "next_group_code" => $nextProp['groupID'],

                                    //===references
                                    //                            "id_master" => $insertID,
                                    "id_master" => $masterID,// milik transkasiID yang diedit
                                    "id_top" => $insertID,
                                    "ids_prev" => "",
                                    "ids_prev_intext" => "",
                                    "nomer_top" => $session[$cCode]['main']['nomer'],
                                    "nomers_prev" => "",
                                    "nomers_prev_intext" => "",
                                    "jenises_prev" => "",
                                    "jenises_prev_intext" => "",
                                    "ids_his" => $idHis_blob,
                                    "ids_his_intext" => $idHis_intext,

                                )) or die("Failed to update tr next-state!");
                                cekHijau($this->CI->db->last_query());

                                $addValues = array(
                                    //===references
                                    "id_master" => $insertID,
                                    "id_top" => $insertID,
                                    "ids_prev" => "",
                                    "ids_prev_intext" => "",
                                    "nomer_top" => $session[$cCode]['main']['nomer'],
                                    "nomers_prev" => "",
                                    "nomers_prev_intext" => "",
                                    "jenises_prev" => "",
                                    "jenises_prev_intext" => "",
                                    "ids_his" => $idHis_blob,
                                    "ids_his_intext" => $idHis_intext,
                                );
                                foreach ($addValues as $key => $val) {
                                    $session[$cCode]['tableIn_master'][$key] = $val;
                                }

                            }
                            if (isset($session[$cCode]['tableIn_master_values']) && sizeof($session[$cCode]['tableIn_master_values']) > 0) {
                                $inserMainValues = array();
                                if (isset($configCoreMasterModulJenis['tableIn']['mainValues'])) {
                                    //matiHEre("hooppp");
                                    $inserMainValues = array();
                                    foreach ($configCoreMasterModulJenis['tableIn']['mainValues'] as $key => $src) {
                                        if (isset($session[$cCode]['tableIn_master_values'][$key])) {
                                            $dd = $tr->writeMainValues($insertID, array(
                                                "key" => $key,
                                                "value" => $session[$cCode]['tableIn_master_values'][$key],
                                            ));

                                            $inserMainValues[] = $dd;
                                            $mongoList['mainValues'][] = $dd;
                                        }
                                    }
                                }

                                if (sizeof($inserMainValues) > 0) {
                                    $arrBlob = blobEncode($inserMainValues);
                                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                                }

                            }
                            if (isset($session[$cCode]['main_add_values']) && sizeof($session[$cCode]['main_add_values']) > 0) {
                                $inserMainValues = array();
                                foreach ($session[$cCode]['main_add_values'] as $key => $val) {
                                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                    $inserMainValues[] = $dd;
                                    $mongoList['mainValues'][] = $dd;
                                }

                                if (sizeof($inserMainValues) > 0) {
                                    $arrBlob = blobEncode($inserMainValues);
                                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                                }
                            }
                            if (isset($session[$cCode]['main_inputs']) && sizeof($session[$cCode]['main_inputs']) > 0) {
                                foreach ($session[$cCode]['main_inputs'] as $key => $val) {
                                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                                }
                            }
                            if (isset($session[$cCode]['main_add_fields']) && sizeof($session[$cCode]['main_add_fields']) > 0) {
                                foreach ($session[$cCode]['main_add_fields'] as $key => $val) {
                                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                                }
                            }
                            if (isset($session[$cCode]['main_applets']) && sizeof($session[$cCode]['main_applets']) > 0) {
                                foreach ($session[$cCode]['main_applets'] as $amdl => $aSpec) {
                                    $tr->writeMainApplets($insertID, array(
                                        "mdl_name" => $amdl,
                                        "key" => $aSpec['key'],
                                        "label" => $aSpec['labelValue'],
                                        "description" => $aSpec['description'],
                                    ));
                                }
                            }
                            if (isset($session[$cCode]['main_elements']) && sizeof($session[$cCode]['main_elements']) > 0) {
                                foreach ($session[$cCode]['main_elements'] as $elName => $aSpec) {
                                    $tr->writeMainElements($insertID, array(
                                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                                        "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                                        "name" => $aSpec['name'],
                                        "label" => $aSpec['label'],
                                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                                    ));
                                    //==nebeng bikin inputLabels
                                    //                                $currentValue = "";
                                    //                                switch ($aSpec['elementType']) {
                                    //                                    case "dataModel":
                                    //                                        $currentValue = $aSpec['key'];
                                    //                                        break;
                                    //                                    case "dataField":
                                    //                                        $currentValue = $aSpec['value'];
                                    //                                        break;
                                    //                                }
                                    //                                if (array_key_exists($elName, $relOptionConfigs)) {
                                    //                                    //					cekhijau("$eName terdaftar pada relInputs");
                                    //
                                    //
                                    //                                    if (isset($relOptionConfigs[$elName][$currentValue])) {
                                    //                                        if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                    //                                            foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                    //                                                $inputLabels[$oValueName] = $oValSpec['label'];
                                    //                                                if (isset($oValSpec['auth'])) {
                                    //                                                    if (isset($oValSpec['auth']['groupID'])) {
                                    //                                                        $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
                                    //                                                    }
                                    //                                                }
                                    //                                            }
                                    //                                        }
                                    //                                    }
                                    //                                    else {
                                    //                                        //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                                    //                                    }
                                    //
                                    //                                }

                                }
                            }
                            if (isset($session[$cCode]['tableIn_detail']) && sizeof($session[$cCode]['tableIn_detail']) > 0) {

                                $insertIDs = array();
                                $insertDeIDs = array();
                                foreach ($session[$cCode]['tableIn_detail'] as $dSpec) {
                                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                    if ($insertDetailID < 1) {
                                        die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                                    }
                                    else {
                                        $insertIDs[] = $insertDetailID;
                                        $insertDeIDs[$insertID][] = $insertDetailID;
                                        $mongoList['detail'][] = $insertDetailID;
                                    }
                                    if ($epID != 999) {
                                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                                        if ($insertEpID < 1) {
                                            die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                                        }
                                        else {
                                            $insertIDs[] = $insertEpID;
                                            $insertDeIDs[$epID][] = $insertEpID;
                                            $mongoList['detail'][] = $insertEpID;
                                        }
                                    }
                                    cekUngu($this->CI->db->last_query());
                                }
                                if (sizeof($insertIDs) == 0) {
                                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                                }
                                else {
                                    $indexing_details = array();
                                    foreach ($insertDeIDs as $key => $numb) {
                                        $indexing_details[$key] = $numb;
                                    }

                                }
                            }
                            else {
                                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                            }
                            if (isset($session[$cCode]['tableIn_detail2']) && sizeof($session[$cCode]['tableIn_detail2']) > 0) {
                                $insertIDs = array();
                                foreach ($session[$cCode]['tableIn_detail2'] as $dSpec) {
                                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                                    $mongoList['detail'] = $insertIDs;
                                    if ($epID != 999) {
                                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                        $mongoList['detail'] = $insertIDs;
                                    }
                                    cekUngu($this->CI->db->last_query());
                                }
                            }
                            if (isset($session[$cCode]['tableIn_detail2_sum']) && sizeof($session[$cCode]['tableIn_detail2_sum']) > 0) {
                                $insertIDs = array();
                                foreach ($session[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                                    $insertIDs[] = $insertDetailID;
                                    $mongoList['detail'][] = $insertDetailID;
                                    if ($epID != 999) {
                                        $dd = $tr->writeDetailEntries($epID, $dSpec);
                                        $insertIDs[] = $dd;
                                        $mongoList['detail'][] = $dd;
                                    }
                                }
                            }
                            if (isset($session[$cCode]['tableIn_detail_rsltItems']) && sizeof($session[$cCode]['tableIn_detail_rsltItems']) > 0) {
                                $insertIDs = array();
                                foreach ($session[$cCode]['tableIn_detail_rsltItems'] as $dSpec) {
                                    $dd = $tr->writeDetailEntries($insertID, $dSpec);
                                    $insertIDs[] = $dd;
                                    $mongoList['detil'][] = $dd;
                                    if ($epID != 999) {
                                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                        $mongoList['detil'] = $insertIDs;
                                    }
                                    cekUngu($this->CI->db->last_query());
                                }
                            }
                            if (isset($session[$cCode]['tableIn_detail_values']) && sizeof($session[$cCode]['tableIn_detail_values']) > 0) {
                                $insertIDs = array();
                                foreach ($session[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                                    if (isset($configCoreMasterModulJenis['tableIn']['detailValues'])) {
                                        foreach ($configCoreMasterModulJenis['tableIn']['detailValues'] as $key => $src) {
                                            if (isset($session[$cCode]['tableIn_detail'][$pID])) {
                                                $dd = $tr->writeDetailValues($insertID, array(
                                                    "produk_jenis" => $session[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                                    "produk_id" => $pID,
                                                    "key" => $key,
                                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : "0",
                                                ));
                                                $insertIDs[$pID][] = $dd;
                                                $mongoList['detailValues'][] = $dd;
                                            }
                                        }
                                    }
                                }
                                if (sizeof($insertIDs) > 0) {
                                    $arrBlob = blobEncode($insertIDs);
                                    $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                                }
                            }
                            if (isset($session[$cCode]['tableIn_detail_values2_sum']) && sizeof($session[$cCode]['tableIn_detail_values2_sum']) > 0) {
                                foreach ($session[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                                    if (isset($configCoreMasterModulJenis['tableIn']['detailValues2_sum'])) {
                                        $insertIDs = array();
                                        foreach ($configCoreMasterModulJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                                            $dd = $tr->writeDetailValues($insertID, array(
                                                "produk_jenis" => $session[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                                "produk_id" => $pID,
                                                "key" => $key,
                                                "value" => $dSpec[$src],
                                            ));
                                            $insertIDs[] = $dd;
                                            $mongoList['detailValues'][] = $dd;
                                        }
                                    }
                                }
                            }
                            //endregion

                            $baseRegistries = array(
                                'main' => isset($session[$cCode]['main']) ? $session[$cCode]['main'] : array(),
                                'items' => isset($session[$cCode]['items']) ? $session[$cCode]['items'] : array(),
                                'items2' => isset($session[$cCode]['items2']) ? $session[$cCode]['items2'] : array(),
                                'items2_sum' => isset($session[$cCode]['items2_sum']) ? $session[$cCode]['items2_sum'] : array(),
                                'itemSrc' => isset($session[$cCode]['itemSrc']) ? $session[$cCode]['itemSrc'] : array(),
                                'itemSrc_sum' => isset($session[$cCode]['itemSrc_sum']) ? $session[$cCode]['itemSrc_sum'] : array(),
                                'items3' => isset($session[$cCode]['items3']) ? $session[$cCode]['items3'] : array(),
                                'items3_sum' => isset($session[$cCode]['items3_sum']) ? $session[$cCode]['items3_sum'] : array(),
                                'items4' => isset($session[$cCode]['items4']) ? $session[$cCode]['items4'] : array(),
                                'items4_sum' => isset($session[$cCode]['items4_sum']) ? $session[$cCode]['items4_sum'] : array(),
                                'items5_sum' => isset($session[$cCode]['items5_sum']) ? $session[$cCode]['items5_sum'] : array(),
                                'items6' => isset($session[$cCode]['items6']) ? $session[$cCode]['items6'] : array(),
                                'items6_sum' => isset($session[$cCode]['items6_sum']) ? $session[$cCode]['items6_sum'] : array(),
                                'items7' => isset($session[$cCode]['items7']) ? $session[$cCode]['items7'] : array(),
                                'items7_sum' => isset($session[$cCode]['items7_sum']) ? $session[$cCode]['items7_sum'] : array(),
                                'items8_sum' => isset($session[$cCode]['items8_sum']) ? $session[$cCode]['items8_sum'] : array(),
                                'items9_sum' => isset($session[$cCode]['items9_sum']) ? $session[$cCode]['items9_sum'] : array(),
                                'items10_sum' => isset($session[$cCode]['items10_sum']) ? $session[$cCode]['items10_sum'] : array(),
                                'rsltItems' => isset($session[$cCode]['rsltItems']) ? $session[$cCode]['rsltItems'] : array(),
                                'rsltItems2' => isset($session[$cCode]['rsltItems2']) ? $session[$cCode]['rsltItems2'] : array(),
                                'rsltItems3' => isset($session[$cCode]['rsltItems3']) ? $session[$cCode]['rsltItems3'] : array(),
                                'tableIn_master' => isset($session[$cCode]['tableIn_master']) ? $session[$cCode]['tableIn_master'] : array(),
                                'tableIn_detail' => isset($session[$cCode]['tableIn_detail']) ? $session[$cCode]['tableIn_detail'] : array(),
                                'tableIn_detail2_sum' => isset($session[$cCode]['tableIn_detail2_sum']) ? $session[$cCode]['tableIn_detail2_sum'] : array(),
                                'tableIn_detail_rsltItems' => isset($session[$cCode]['tableIn_detail_rsltItems']) ? $session[$cCode]['tableIn_detail_rsltItems'] : array(),
                                'tableIn_detail_rsltItems2' => isset($session[$cCode]['tableIn_detail_rsltItems2']) ? $session[$cCode]['tableIn_detail_rsltItems2'] : array(),
                                'tableIn_master_values' => isset($session[$cCode]['tableIn_master_values']) ? $session[$cCode]['tableIn_master_values'] : array(),
                                'tableIn_detail_values' => isset($session[$cCode]['tableIn_detail_values']) ? $session[$cCode]['tableIn_detail_values'] : array(),
                                'tableIn_detail_values_rsltItems' => isset($session[$cCode]['tableIn_detail_values_rsltItems']) ? $session[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                                'tableIn_detail_values_rsltItems2' => isset($session[$cCode]['tableIn_detail_values_rsltItems2']) ? $session[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                                'tableIn_detail_values2_sum' => isset($session[$cCode]['tableIn_detail_values2_sum']) ? $session[$cCode]['tableIn_detail_values2_sum'] : array(),
                                'main_add_values' => isset($session[$cCode]['main_add_values']) ? $session[$cCode]['main_add_values'] : array(),
                                'main_add_fields' => isset($session[$cCode]['main_add_fields']) ? $session[$cCode]['main_add_fields'] : array(),
                                'main_elements' => isset($session[$cCode]['main_elements']) ? $session[$cCode]['main_elements'] : array(),
                                'main_inputs' => isset($session[$cCode]['main_inputs']) ? $session[$cCode]['main_inputs'] : array(),
                                'main_inputs_orig' => isset($session[$cCode]['main_inputs']) ? $session[$cCode]['main_inputs'] : array(),
                                "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields'][1] : array(),
                                "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][1]) ? $configLayoutMasterModulJenis['receiptSumFields'][1] : array(),
                                "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][1] : array(),
                                "receiptDetailSrcFields" => isset($configLayoutMasterModulJenis['receiptDetailSrcFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailSrcFields'][1] : array(),
                                "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][1]) ? $configLayoutMasterModulJenis['receiptSumFields2'][1] : array(),
                                "jurnal_index" => isset($jurnalIndex) ? $jurnalIndex : array(),
                                "postProcessor" => isset($jurnalPostProc) ? $jurnalPostProc : array(),
                                "preProcessor" => isset($jurnalPreProc) ? $jurnalPreProc : array(),
                                "revert" => isset($session[$cCode]['revert']) ? $session[$cCode]['revert'] : array(),
                                "items_komposisi" => isset($session[$cCode]['items_komposisi']) ? $session[$cCode]['items_komposisi'] : array(),
                                "items_noapprove" => isset($session[$cCode]['items_noapprove']) ? $session[$cCode]['items_noapprove'] : array(),
                                "jurnalItems" => isset($session[$cCode]['jurnalItems']) ? $session[$cCode]['jurnalItems'] : array(),
                                "componentsBuilder" => isset($session[$cCode]['componentsBuilder']) ? $session[$cCode]['componentsBuilder'] : array(),
                                //----
                                'mainOriginal' => isset($session[$cCode]['mainOriginal']) ? $session[$cCode]['mainOriginal'] : array(),
                                'itemsOriginal' => isset($session[$cCode]['itemsOriginal']) ? $session[$cCode]['itemsOriginal'] : array(),
                                //----
                            );
                            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                            showLast_query("hijau");
                            cekHijau("SELESAI menjalankan EDITOR...");
                        }
                    }
                    else {
                        $tableIn_master['trash_4'] = 1;
                        $tableIn_master['cancel_id'] = my_id();
                        $tableIn_master['cancel_name'] = my_name();
                        $tableIn_master['cancel_dtime'] = date("Y-m-d H:i:s");
                        $tableIn_master['deskripsi'] = "rejection";
                        $epID = $tr->writeMainEntries_entryPoint($no, $tableIn_master['id_master'], $tableIn_master);
                        $mongoList['main'][] = $epID;
                        if (isset($tableIn_detail) && sizeof($tableIn_detail) > 0) {
                            $insertIDs = array();
                            foreach ($tableIn_detail as $dSpec) {
                                if ($epID != 999) {
                                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                                    $mongoList['detail'] = $insertIDs;
                                }
                            }
                        }
                    }
                }

            }


        }
        else {
            cekMerah("tidak ada invoice-nya");
        }


    }

    public function buildInvoice()
    {

        $start = microtime(true);
        $this->CI->load->helper("he_session_replacer");
        $this->CI->load->model("MdlTransaksi");
        $jenisTr = $this->jenisTr = "4822";// kode invoice ditentukan disini dulu
//        $batas_tanggal = "2024-02-18";
        $cCode = "_TR_" . $this->jenisTr;
        $sessionData = array();
        if (isset($sessionData[$cCode])) {
            $sessionData[$cCode] = null;
            unset($sessionData[$cCode]);
        }

        if ($this->transaksi_id == NULL) {
            $msg = "transaksiID belum disett";
            mati_disini($msg . " code: " . __LINE__);
        }
        if ($this->master_id == NULL) {
            $msg = "masterID belum disett";
            mati_disini($msg . " code: " . __LINE__);
        }
        $transaksi_id = $this->transaksi_id;
        $master_id = $this->master_id;

        $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
        $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
        $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
        $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");


        $tr = new MdlTransaksi();
        $tr->addFilter("transaksi.id='$transaksi_id'");
        $tr->addFilter("transaksi.status_inv='0'");
//        $tr->addFilter("transaksi.fulldate>='$batas_tanggal'");
        $tr->addFilter("transaksi.cabang_id>'0'");
        $this->CI->db->limit(1);
        $this->CI->db->order_by("transaksi.id", "ASC");
        $this->CI->db->group_start();

        $where_1 = "transaksi.jenis='5822spd' and transaksi.pembayaran in ('credit','cod')";// penjualan credit
        $this->CI->db->group_start();
        $this->CI->db->where($where_1);
        $this->CI->db->group_end();

        $where_2 = "transaksi.jenis='4464'";// penerimaan penjualan tunai
        $where_3 = "transaksi.jenis='7499'";// termin
        $this->CI->db->or_where($where_2);
        $this->CI->db->or_where($where_3);
        $this->CI->db->group_end();

        $sesionReplacer = array(//            "cabang_id" => ">0",
        );
        $tmpHist = $tr->lookupRecentUndoneEntries_joined($sesionReplacer)->result();
        showLast_query("biru");
        cekBiru(count($tmpHist));
//        arrPrintPink($tmpHist);
//        mati_disini(__LINE__);


        if (sizeof($tmpHist) > 0) {
            $transaksi_id = $tmpHist[0]->transaksi_id;
            $dtime = $tmpHist[0]->dtime;
            $jenisTransaksi = $tmpHist[0]->jenis;
            $cabang_id = $tmpHist[0]->cabang_id;
            $gudang_id = $tmpHist[0]->gudang_id;
            cekHitam("[trid: $transaksi_id] [dtime: $dtime] [jenisTr: $jenisTransaksi] [cb: $cabang_id] [gd: $gudang_id]");


            if ($cabang_id == CB_ID_PUSAT) {
                mati_disini("SETOP... HARUS LOGIN CABANG " . __LINE__);
            }
            if ($cabang_id == 0) {
                mati_disini("SETOP... HARUS LOGIN CABANG " . __LINE__);
            }


            $oleh_nama = str_replace("by system", "", $tmpHist[0]->oleh_nama);
            $oleh_nama = trim($oleh_nama);
            $oleh_nama_system = $oleh_nama . " by system";

            $arrKiriman = array(
                "master_id" => $master_id,
                "transaksi_id" => $transaksi_id,
                "master_id_reference" => $master_id,
                "transaksi_id_reference" => $transaksi_id,
                "step_number" => 1,
                "currentStepNumber" => 1,
                "jenisTr" => $jenisTr,
                "uiJenis" => $configUiMasterModulJenis,
                "coreJenis" => $configCoreMasterModulJenis,
                "layoutJenis" => $configLayoutMasterModulJenis,
                "valuesJenis" => $configValuesMasterModulJenis,
                "dtime" => $tmpHist[0]->dtime,
                "fulldate" => $tmpHist[0]->fulldate,
                "oleh_id" => $tmpHist[0]->oleh_id,
                "oleh_nama" => $oleh_nama_system,
            );
            $sessionData = $this->followupPrePreviewInvoicing($arrKiriman);
//            arrPrint($sessionData);


            cekHijau("MULAI EKSEKUTOR SAVE");
            $this->save($sessionData, $arrKiriman);
//            mati_disini(__LINE__);

            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "id" => $transaksi_id,
            );
            $data = array(
                "status_inv" => 1,
            );
            $tr->updateData($where, $data);
            showLast_query("orange");

        }


        $end = microtime(true);
        $selisih = $end - $start;
//        mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID:.... [$selisih]");
//

        if (isset($sessionData[$cCode])) {
            unset($sessionData[$cCode]);
        }
        if (isset($oldCode)) {
            if (isset($sessionData[$oldCode])) {
                unset($sessionData[$oldCode]);
            }
        }


    }

    public function followupPrePreviewInvoicing($arrKiriman)
    {

        $no = $arrKiriman["transaksi_id"];
        $stepNumber = $arrKiriman["step_number"];
        $currentStepNum = $arrKiriman["currentStepNumber"];
        $this->jenisTr = $arrKiriman["jenisTr"];
        $this->configUiJenis = $arrKiriman["uiJenis"];
        $this->configCoreJenis = $arrKiriman["coreJenis"];
        $this->configLayoutJenis = $arrKiriman["layoutJenis"];
        $this->configValuesJenis = $arrKiriman["valuesJenis"];


        //region read items from existing model
        $this->CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("id in (" . implode(",", explode("-", $no)) . ")");
        $tmpTr = $tr->lookupJoined();
        cekBiru($this->CI->db->last_query());
        //endregion


        $cancelPackingId = isset($tmpTr[0]->cancel_packing_source_id) ? $tmpTr[0]->cancel_packing_source_id : 0;
        $tmpTrCancelPacking = array();
        $id_top_source_cancel_packing = array();
        if ($cancelPackingId > 0) {
            $tr->setFilters(array());
            $tr->addFilter("id in (" . implode(",", explode("-", $cancelPackingId)) . ")");
            $tmpTrCancelPacking = $tr->lookupJoined();
            $id_top_source_cancel_packing = $tmpTrCancelPacking[0]->id_top;
        }

        $signNumbers = array();
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $tmpSign = $trs->lookupSignaturesByMasterID($no)->result();
        if (sizeof($tmpSign) > 0) {
            $sCtr = 0;
            foreach ($tmpSign as $row) {
                $signNumbers[$sCtr] = "" . $row->step_number;
                $sCtr++;
            }
        }

        $rawItems = array();
        if (sizeof($tmpTr) > 0) {
            $cCode = "_TR_" . $this->jenisTr;
            if (isset($sessionData[$cCode])) {
                $sessionData[$cCode] = null;
                unset($sessionData[$cCode]);
            }

            $pakai_ini = 0;
            if ($pakai_ini == 1) {
                $initMasterValues = heInitMasterValues_he_cart($this->jenisTr, 1, $this->configUiJenis);
                heInitGates_he_cart($this->jenisTr, $initMasterValues);
            }
            else {
                $this->CI->load->Model("Mdls/MdlCompany");
                $comPro = new MdlCompany();
                $tmpCompanyProfile = $comPro->lookupAll()->result();
                $jn_usaha = $tmpCompanyProfile[0]->jenis_usaha;
                $masterPpnData = $this->CI->config->item("pairPajak");
                $masterPPN = $masterPpnData[$jn_usaha]["value"]["default"];
                $initMasterValues = array(
//                    "olehID" => $tmpTr[0]->oleh_id,
//                    "olehName" => $tmpTr[0]->oleh_nama,
                    "olehID" => $arrKiriman["oleh_id"],
                    "olehName" => $arrKiriman["oleh_nama"],
                    "sellerID" => $tmpTr[0]->seller_id,
                    "sellerName" => $tmpTr[0]->seller_nama,
                    "placeID" => $tmpTr[0]->cabang_id,
                    "placeName" => $tmpTr[0]->cabang_nama,
                    "divID" => $tmpTr[0]->div_id,
                    "divName" => $tmpTr[0]->div_nama,
                    "cabangID" => $tmpTr[0]->cabang_id,
                    "cabangName" => $tmpTr[0]->cabang_nama,
                    "gudangID" => $tmpTr[0]->gudang_id,
                    "gudangName" => $tmpTr[0]->gudang_nama,
                    "jenis_usaha" => $jn_usaha,
                    "tokoID" => $tmpTr[0]->toko_id,
                    "tokoNama" => $tmpTr[0]->toko_nama,
                    "jenisTr" => $this->jenisTr,
                    "jenisTrMaster" => $this->jenisTr,
                    "jenisTrTop" => $this->configUiJenis['steps'][$stepNumber]['target'],
                    "jenisTrName" => $this->configUiJenis['steps'][$stepNumber]['label'],
                    "stepNumber" => $stepNumber,
                    "stepCode" => isset($this->configUiJenis['steps'][$stepNumber]['target']) ? $this->configUiJenis['steps'][$stepNumber]['target'] : 0,
                    "dtime" => dtimeNow(),
                    "fulldate" => dtimeNow("Y-m-d"),
                    "ppnFactor" => $masterPPN["ppnFactor"],
                );
                $sessionData = heInitGates_ns_he_cart($this->jenisTr, $initMasterValues);
            }


            //region session init
            if (!isset($sessionData[$cCode])) {
                $sessionData[$cCode] = array(
                    "items" => array(),
                    "main" => array(),
                );
            }
            if (!isset($sessionData[$cCode]['main'])) {
                $sessionData[$cCode]['main'] = array();
            }
            if (!isset($sessionData[$cCode]['items'])) {
                $sessionData[$cCode]['items'] = array();
            }
            //endregion

            $trID = $tmpTr[0]->transaksi_id;
            $itemLabels = isset($this->configLayoutJenis['receiptDetailFields'][$stepNumber]) ? $this->configLayoutJenis['receiptDetailFields'][$stepNumber] : array();
            $itemNumLabels = isset($this->configUiJenis['shoppingCartNumFields'][$stepNumber]) ? $this->configUiJenis['shoppingCartNumFields'][$stepNumber] : array();
            $subAmountConfig = isset($this->configUiJenis['shoppingCartAmountValue'][$stepNumber]) ? $this->configUiJenis['shoppingCartAmountValue'][$stepNumber] : null;
            $measurementDetails = isset($this->configUiJenis["receiptMesurementRows"]) ? $this->configUiJenis["receiptMesurementRows"] : array();
            $validatePaymentLocker = isset($this->configUiJenis["validatePaymentSource"][$stepNumber]) ? $this->configUiJenis["validatePaymentSource"][$stepNumber] : array();
            $itemsChild = isset($this->configUiJenis["shopingCartDetailFields"][$stepNumber]['fields']) ? $this->configUiJenis["shopingCartDetailFields"][$stepNumber]['fields'] : array();//dipake detil pembelian aset
            $itemsChildGate = isset($this->configUiJenis["shopingCartDetailFields"][$stepNumber]['gate']) ? $this->configUiJenis["shopingCartDetailFields"][$stepNumber]['gate'] : array();//dipake detil pembelian aset/penambahan aset dari supplies sebagai switcer baca item atau main

            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;
            $afterTargetStepNum = ($currentStepNum + 1);
            $pengirimID = $tmpTr[0]->pengirim_id;
            $pengirimName = $tmpTr[0]->pengirim_nama;
            //--------------------------------
            $gudangStatusJenis = $tmpTr[0]->gudang_status_jenis;
            $idsHis = ($tmpTr[0]->ids_his != null) ? blobDecode($tmpTr[0]->ids_his) : array();


            //region take from registries
            $trr = new MdlTransaksi();
            $trr->setFilters(array());
            $trr->addFilter("transaksi_id in (" . implode(",", explode("-", $no)) . ")");
            $tmpReg = $trr->lookupDataRegistries()->result();
            cekKuning($this->CI->db->last_query());

            $main = array();
            $items = array();
            $items2 = array();
            $items2_sum = array();
            $items3 = array();
            $items3_sum = array();
            $items4 = array();
            $items4_sum = array();
            $items5_sum = array();
            $items6 = array();
            $items6_sum = array();
            $items7 = array();
            $items7_sum = array();
            $items8_sum = array();
            $items9_sum = array();
            $items10_sum = array();
            $rsltItems = array();
            $rsltItems2 = array();

            $masterGates = array();
            $childGates = array();
            $childGates2 = array();
            $childGates2_sum = array();
            $childGatesRsltItems = array();
            $childGatesRsltItems2 = array();
            $masterTableInParams = array();
            $childTableInParams = array();
            $childTableInParamsRsltItems = array();
            $childTableInParamsRsltItems2 = array();
            $masterTableInValueParams = array();
            $childTableInValueParams = array();
            $childTableInValueParamsRsltItems = array();
            $childTableInValueParamsRsltItems2 = array();
            $masterAddValues = array();
            $masterAddFields = array();
            $mainElements = array();
            $mainInputs = array();
            $itemsKomposisi = array();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($row as $key_reg => $val_reg) {
                        if ($val_reg == NULL) {
                            $val_reg = blobEncode(array());
                        }
                        switch ($key_reg) {
                            case "main"://
                                $main = $main + unserialize(base64_decode($val_reg));
                                break;
                            case "items"://
                                $items = $items + unserialize(base64_decode($val_reg));
                                break;
                            case "items2"://
                                $items2 = $items2 + unserialize(base64_decode($val_reg));
                                break;
                            case "rsltItems"://
                                $rsltItems = $rsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "rsltItems2"://
                                $rsltItems2 = $rsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "items2_sum"://
                                $items2_sum = $items2_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items3"://
                                $items3 = $items3 + unserialize(base64_decode($val_reg));
                                break;
                            case "items3_sum"://
                                $items3_sum = $items3_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items4"://
                                $items4 = $items4 + unserialize(base64_decode($val_reg));
                                break;
                            case "items4_sum"://
                                $items4_sum = $items4_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items5_sum"://
                                $items5_sum = $items5_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items6"://
//                                arrPrint($items6);
//                                arrPrint(unserialize(base64_decode($val_reg)));
//                                cekHere($val_reg);
                                $items6 = $items6 + unserialize(base64_decode($val_reg));
                                break;
                            case "items6_sum"://
                                $items6_sum = $items6_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items7"://
                                $items7 = $items7 + unserialize(base64_decode($val_reg));
                                break;
                            case "items7_sum"://
                                $items7_sum = $items7_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items8_sum"://
                                $items8_sum = $items8_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items9_sum"://
                                $items9_sum = $items9_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items10_sum"://
                                $items10_sum = $items10_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_master"://
                                $masterTableInParams = $masterTableInParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail"://
                                $childTableInParams = $childTableInParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_rsltItems"://
                                $childTableInParamsRsltItems = $childTableInParamsRsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_rsltItems2"://
                                $childTableInParamsRsltItems2 = $childTableInParamsRsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_master_values"://
                                $masterTableInValueParams = $masterTableInValueParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values"://
                                $childTableInValueParams = $childTableInValueParams + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values_rsltItems"://
                                $childTableInValueParamsRsltItems = $childTableInValueParamsRsltItems + unserialize(base64_decode($val_reg));
                                break;
                            case "tableIn_detail_values_rsltItems2"://
                                $childTableInValueParamsRsltItems2 = $childTableInValueParamsRsltItems2 + unserialize(base64_decode($val_reg));
                                break;
                            case "main_add_values"://
                                $masterAddValues = $masterAddValues + unserialize(base64_decode($val_reg));
                                break;
                            case "main_add_fields"://
                                $masterAddFields = $masterAddFields + unserialize(base64_decode($val_reg));
                                break;
                            case "main_elements"://
                                $mainElements = unserialize(base64_decode($val_reg));
                                break;
                            case "main_inputs"://
                                $mainInputs = unserialize(base64_decode($val_reg));
                                break;
                            case "items_komposisi"://
                                $itemsKomposisi = unserialize(base64_decode($val_reg));
                                break;
                        }
                    }
                }
            }
            else {
                die("Cannot read the registry entries from $masterID!");
            }
            //endregion


            //==revalidate items
            $this->CI->load->library("FieldCalculator");
            $this->CI->load->helper("he_angka");
            $cal = new FieldCalculator();


            //region session-swapper
            unset($main["nilai_pembulatan"]);
            $main["pengirimID"] = $pengirimID;
            $main["pengirimName"] = $pengirimName;


            if (isset($sessionData[$cCode]["main"])) {
                $sessionData[$cCode]["main"]["olehID"] = $arrKiriman["oleh_id"];
                $sessionData[$cCode]["main"]["olehName"] = $arrKiriman["oleh_nama"];
                foreach ($sessionData[$cCode]["main"] as $mkey => $mval) {
                    $main[$mkey] = $mval;
                }
            }

            $swappers = array(
                "main" => $main,
                "items" => $items,
                "items2" => $items2,
                "items2_sum" => $items2_sum,
                "items3" => $items3,
                "items3_sum" => $items3_sum,
                "items4" => $items4,
                "items4_sum" => $items4_sum,
                "items5_sum" => $items5_sum,
                "items6" => $items6,
                "items6_sum" => $items6_sum,
                "items7" => $items7,
                "items7_sum" => $items7_sum,
                "items8_sum" => $items8_sum,
                "items9_sum" => $items9_sum,
                "items10_sum" => $items10_sum,
                "items_child" => $itemChildData,
                "rsltItems" => $rsltItems,
                "rsltItems2" => $rsltItems2,
                "extractedItems" => $extractedItems,


                "tableIn_master" => $masterTableInParams,
                "tableIn_detail" => $childTableInParams,
                "tableIn_detail_rsltItems" => $childTableInParamsRsltItems,
                "tableIn_detail_rsltItems2" => $childTableInParamsRsltItems2,
                "tableIn_master_values" => $masterTableInValueParams,
                "tableIn_detail_values" => $childTableInValueParams,
                "tableIn_detail_values_rsltItems" => $childTableInValueParamsRsltItems,
                "tableIn_detail_values_rsltItems2" => $childTableInValueParamsRsltItems2,
                "main_add_values" => $masterAddValues,
                "main_add_fields" => $masterAddFields,
                "main_elements" => $mainElements,
                "main_inputs" => $mainInputs,
                "extSteps" => $extSteps,
                "paySrcs" => $paySrcs,
                "lockerPayment" => $tempBtnUndo,
                "items_komposisi" => $itemsKomposisi,
            );
            foreach ($swappers as $targetVar => $src) {
                $sessionData[$cCode][$targetVar] = $src;
            }
            //endregion

            if (sizeof($idsHis) > 0) {
                foreach ($idsHis as $step_his => $data_his) {
                    $sessionData[$cCode]['main']['referenceID_' . $step_his] = $data_his["trID"];
                    $sessionData[$cCode]['main']['referenceNumber' . $step_his] = $data_his["nomer"];
                }
            }
            $sessionData[$cCode]['main']['referenceID_current'] = $tmpTr[0]->id;
            $sessionData[$cCode]['main']['referenceNumber_current'] = $tmpTr[0]->nomer;

            $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) && $sessionData[$cCode]["main"]["ppnFactor"] == "11" ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("error on build values on PrePrev " . __LINE__ . " silahkan relogin");

            $this->CI->load->helper("he_value_builder");

            //-------------------------------------------
            $receiptElementsInjector = isset($this->configUiJenis["receiptElementsInjector"]) ? $this->configUiJenis["receiptElementsInjector"] : array();
            if (sizeof($receiptElementsInjector) > 0) {
                foreach ($receiptElementsInjector as $eName => $eSpec) {
                    if ((!isset($main[$eName])) || (!isset($mainElements[$eName]))) {
                        if (isset($eSpec['defaultValue'])) {//==cek apakah ada seting defaultValue
                            $defValueSrc = $eSpec['defaultValue'];
                            switch ($eSpec['elementType']) {
                                case "dataModel":
                                    heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUiJenis);
                                    break;
                                case "dataField":
                                    heRecordElement_modul($this->jenisTr, $eName, $defValueSrc, $this->configUiJenis);
                                    break;
                            }
                            $sessionData[$cCode]['main_elements'][$eName]['autoSelect'] = true;
                        }
                        else {//==cek apakah pilihannya cuma satu
                            if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {

                            }
                            else {
                                switch ($eSpec['elementType']) {
                                    case "dataModel":
                                        $amdlName = $eSpec['mdlName'];
                                        $this->CI->load->model("Mdls/" . $amdlName);
                                        $labelSrc = $eSpec['labelSrc'];
                                        $keySrc = $eSpec['key'];
                                        $oo = new $amdlName();
                                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                        if (sizeof($aFilter) > 0) {
                                            $oo = makeFilter($aFilter, $sessionData[$cCode]['main'], $oo);
                                        }
                                        $tmpo = $oo->lookupAll()->result();
                                        if (sizeof($tmpo) == 1) {
                                            $usedKey = $eSpec['key'];
                                            $defValueSrc = $tmpo[0]->$usedKey;
                                            heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $this->configUiJenis);
                                        }
                                        break;
                                    case "dataField":
                                        break;
                                }
                            }
                        }

                        resetValues($this->jenisTr);
                        $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $this->uri->segment(7), $this->uri->segment(6), $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor, $sessionData[$cCode]);

                    }
                }
            }

            //==init replacer
            //==recover nilai HARGA master
            $sessionData[$cCode]['main']['harga'] = 0;
            $sessionData[$cCode]['main']['currentID'] = $no;

            //==default load dari nota, maka dianggap langsung done
            $sessionData[$cCode]['main']['status_4'] = 1;
            $sessionData[$cCode]['main']['trash_4'] = 0;
            if (sizeof($sessionData[$cCode]['items']) > 0) {
                foreach ($sessionData[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $sessionData[$cCode]['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);
                    /*---untuk keperluan mobile view---*/
                    $sessionData[$cCode]['items'][$xid]['jml_target_scan'] = $iSpec['jml'];
                }
            }


            cekHitam("PPN_FACTOR: " . $sessionData[$cCode]["main"]["ppnFactor"]);
            $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) && $sessionData[$cCode]["main"]["ppnFactor"] == "11" ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("error on build values on PrePrev " . __LINE__ . " silahkan relogin");

//            resetValues($this->jenisTr);
            $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, $currentStepNum, $stepNumber, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor, $sessionData[$cCode]);

            return $sessionData;
        }
        else {
            mati_disini(("Tidak ada transaksi yang akan diterbitkan invoice. Silahkan refresh halaman ini. code: " . __LINE__));
        }

    }

    public function save($sessionData, $arrKiriman)
    {


        $no = $arrKiriman["transaksi_id"];
        $stepNumber = $arrKiriman["step_number"];
        $currentStepNum = $arrKiriman["currentStepNumber"];
        $this->jenisTr = $arrKiriman["jenisTr"];
        $this->configUiJenis = $arrKiriman["uiJenis"];
        $this->configCoreJenis = $arrKiriman["coreJenis"];
        $this->configLayoutJenis = $arrKiriman["layoutJenis"];
        $this->configValuesJenis = $arrKiriman["valuesJenis"];
        $transaksi_current_dtime = $arrKiriman["dtime"];
        $transaksi_current_fulldate = $arrKiriman["fulldate"];
        $transaksi_current_cabang_id = $arrKiriman["cabang_id"];
        $transaksi_current_cabang_nama = $arrKiriman["cabang_nama"];
        $transaksi_current_gudang_id = $arrKiriman["gudang_id"];
        $transaksi_current_gudang_nama = $arrKiriman["gudang_nama"];
        //-----
        $transaksi_reference_id = $arrKiriman["transaksi_id_reference"];
        $transaksi_reference_master_id = $arrKiriman["master_id_reference"];
        $transaksi_oleh_id = $arrKiriman["oleh_id"];
        $transaksi_oleh_nama = $arrKiriman["oleh_nama"];
        //-----
        $cCode = "_TR_" . $this->jenisTr;
        cekHitam(":::: jenisTR : " . $this->jenisTr);
        $modul_transaksi = $this->CI->config->item("heTransaksi_ui")[$this->jenisTr]["modul"];
        $tCodeTargetJenisTransaksi = $jenisTrTarget = isset($this->configUiJenis["steps"][1]["target"]) ? $this->configUiJenis["steps"][1]["target"] : NULL;
        $relOptionConfigs = isset($this->configUiJenis['relativeOptions']) ? $this->configUiJenis['relativeOptions'] : array();
        $ppnFactor = isset($sessionData[$cCode]["main"]["ppnFactor"]) ? $sessionData[$cCode]["main"]["ppnFactor"] : matiHere("gagal menghitung ppn silahkan refresh atau relogin. code: " . __LINE__);
        $inputLabels = array();
        $inputAuthConfigs = array();

        $this->CI->load->model("MdlTransaksi");
        $this->CI->load->library("FieldCalculator");
        $cal = new FieldCalculator();


        $mongoList = array();
        $mongRegID = array();
        if (isset($sessionData[$cCode])) {

            if (!isset($sessionData[$cCode]['items'])) {
                mati_disini("belum ada item yang dipilih. code: " . __LINE__);
            }
            else {
                if (sizeof($sessionData[$cCode]['items']) < 1) {
                    mati_disini("belum ada item yang dipilih. code: " . __LINE__);
                }
            }
            $this->tEcho("now processing your transaction..<br>");


//
//            $this->db->trans_start();

            cekMerah("start pre-processor...");

            //region pre-processors (item)
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['preProc']['detail']) ? $sessionData[$cCode]['revert']['preProc']['detail'] : array();
                cekMerah(":: iterator preprocc dari gerbang revert ::");
                arrPrintWebs($iterator);
            }
            else {
                $iterator = isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
            }

            if (sizeof($iterator) > 0) {
                $itemNumLabels = isset($this->configUiJenis['shoppingCartNumFields']) ? $this->configUiJenis['shoppingCartNumFields'] : array();
                $this->tEcho("ITEM NUM LABELS");

                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];

                        $this->tEcho("sub-preproc: $comName, initializing values <br>");

                        foreach ($sessionData[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();

                            //                            $id = $dSpec['id'];
                            $id = $xid;
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }

                                if (!isset($subParams['static']["transaksi_id"])) {
                                    //									$subParams['static']["transaksi_id"] = $masterID;
                                }


                                $subParams['static']["fulldate"] = $transaksi_current_fulldate;
                                $subParams['static']["dtime"] = $transaksi_current_dtime;
                                $subParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " oleh ";
                            }
                            //                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                            //                            arrPrint($subParams);
                            //mati_disini();
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                $mdlName = "Pre" . ucfirst($comName);
                                $this->CI->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);
                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }
                                if ($tobeExecuted) {
                                    $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $gotParams = $m->exec();

                                    cekmerah("gotparams dari pre-proc $comName");
                                    arrprint($gotParams);


                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                        foreach ($gotParams as $gateName => $paramSpec) {
                                            cekBiru(":: getParams inject ke $gateName ::");
                                            if (!isset($sessionData[$cCode][$gateName])) {
                                                $sessionData[$cCode][$gateName] = array();
                                                //                                    cekhijau("building the session: $gateName");
                                            }
                                            else {
                                                //                                    cekhijau("NOT building the session: $gateName");
                                            }

                                            foreach ($paramSpec as $id => $gSpec) {
                                                //										$id=$gSpec['id'];


                                                if (!isset($sessionData[$cCode][$gateName][$id])) {
                                                    $sessionData[$cCode][$gateName][$id] = array();
                                                }


                                                if (isset($sessionData[$cCode][$gateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                            $sessionData[$cCode][$gateName][$id][$key] = $val;
                                                        }

                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                if (isset($sessionData[$cCode][$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $sessionData[$cCode][$srcGateName][$id][$key] = $val;
                                                        }

                                                    }
                                                }

                                                //cekMerah("REBUILDING VALUES..");
                                                if (sizeof($itemNumLabels) > 0) {
                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        //cekHere("$id === $key => $label");
                                                        if (isset($sessionData[$cCode][$gateName][$id][$key])) {
                                                            $sessionData[$cCode][$gateName][$id]['sub_' . $key] = ($sessionData[$cCode][$gateName][$id]['jml'] * $sessionData[$cCode][$gateName][$id][$key]);
                                                        }
                                                    }
                                                }
                                            }
                                            //                                    arrPrint($items);die();
                                        }


                                    }

                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }
                            }
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->CI->load->helper("he_value_builder");
                $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor, $sessionData[$cCode]);


                //region injector gerbang value untuk pembatalan ppv dan selisih
                if (isset($sessionData[$cCode]["revert"]["preProc"]["replacer"])) {
                    $replace = $sessionData[$cCode]["revert"]["preProc"]["replacer"];
                    $jenisTrReference = $sessionData[$cCode]["main"]["jenisTr_reference"];
                    switch ($jenisTrReference) {
                        case "460":
                            $tempCalculate = array(
                                //                                "selisih" => ($sessionData[$cCode]["main"]["hpp_riil"] + $sessionData[$cCode]["main"]["exchange__nilai_tambah_ppn_in"]) - ($sessionData[$cCode]["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                                //                                "exchange__harga" => $sessionData[$cCode]["main"]["hpp_riil"],//riil
                                //                                "exchange__hpp_nppv" => $sessionData[$cCode]["main"]["hpp_nppv"],//riil+ppv
                                //                                "exchange__ppv" => $sessionData[$cCode]["main"]["ppv_riil"],//riil+ppv
                            );
                            break;
                        default:
                            $tempCalculate = array(
                                "selisih" => ($sessionData[$cCode]["main"]["hpp"] + $sessionData[$cCode]["main"]["ppn"]) - ($sessionData[$cCode]["main"]["nett"] + $sessionData[$cCode]["main"]["ppv"]),
                                "hpp_nppv" => $sessionData[$cCode]["main"]["hpp"],
                                "hpp_nppn" => $sessionData[$cCode]["main"]["hpp"] + $sessionData[$cCode]["main"]["ppn"],
                            );
                            break;
                    }
                    //                    $tempCalculate = array(
                    //                        "selisih" => ($sessionData[$cCode]["main"]["hpp"] + $sessionData[$cCode]["main"]["ppn"]) - ($sessionData[$cCode]["main"]["nett"] + $sessionData[$cCode]["main"]["ppv"]),
                    //                        "hpp_nppv" => $sessionData[$cCode]["main"]["hpp"],
                    //                        "hpp_nppn" => $sessionData[$cCode]["main"]["hpp"] + $sessionData[$cCode]["main"]["ppn"],
                    //                    );

                    //arrPrintWebs($tempCalculate);
                    foreach ($replace['recalculate'] as $iKey => $gate) {
                        $sessionData[$cCode]["main"][$gate] = $tempCalculate[$gate];
                    }

                    cekLime($sessionData[$cCode]["main"]["hpp"] . "+" . $sessionData[$cCode]["main"]["ppn"] . "-" . $sessionData[$cCode]["main"]["nett"]);

                }

                //endregion


            }
            else {
                $this->tEcho("no processor defined. skipping preprocessor..<br>");
            }
            //endregion


            //region pre-processors (master)
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['preProc']['master']) ? $sessionData[$cCode]['revert']['preProc']['master'] : array();
            }
            else {
                $iterator = isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]['master']) ? $this->configCoreJenis['preProcessor'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {
                $itemNumLabels = isset($this->configUiJenis['shoppingCartNumFields']) ? $this->configUiJenis['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;
                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $sessionData[$cCode]['main'], $sessionData[$cCode]['main'], 0);
                                $subParams['static'][$key] = $realValue;
                            }

                            if (!isset($subParams['static']["transaksi_id"])) {
                                //									$subParams['static']["transaksi_id"] = $masterID;
                            }

                            $subParams['static']["fulldate"] = $transaksi_current_fulldate;
                            $subParams['static']["dtime"] = $transaksi_current_dtime;
                            $subParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " oleh ";
                        }
                        $tmpOutParams[$cCtr] = $subParams;

                        $mdlName = "Pre" . ucfirst($comName);
                        $this->CI->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);
                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
                            cekbiru("gotparams dari pre-proc $comName");
                            arrprint($gotParams);
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                foreach ($gotParams as $gateName => $gSpec) {
                                    //										$id=$gSpec['id'];

                                    if ($switchResultParams == true) {

                                        foreach ($gSpec as $id => $ggSpec) {
                                            if (!isset($sessionData[$cCode][$gateName][$id])) {
                                                $sessionData[$cCode][$gateName][$id] = array();
                                            }

                                            if (isset($sessionData[$cCode][$gateName][$id])) {
                                                if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                    foreach ($ggSpec as $key => $val) {
                                                        $sessionData[$cCode][$gateName][$id][$key] = $val;
                                                    }
                                                }
                                            }

                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    if (isset($sessionData[$cCode][$gateName][$id][$key])) {
                                                        $sessionData[$cCode][$gateName][$id]['sub_' . $key] = ($sessionData[$cCode][$gateName][$id]['jml'] * $sessionData[$cCode][$gateName][$id][$key]);
                                                    }
                                                }
                                            }

                                        }
                                    }
                                    else {
                                        if (isset($sessionData[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $sessionData[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                        //==inject gotParams to child gate
                                        if (isset($sessionData[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $sessionData[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                        //cekMerah("REBUILDING VALUES..");
                                        if (sizeof($itemNumLabels) > 0) {
                                            //cekHijau("REBUILDING SUBS FOR ITEMS");
                                            foreach ($itemNumLabels as $key => $label) {
                                                cekHere("$id === $key => $label");
                                                if (isset($sessionData[$cCode]['main'][$key])) {
                                                    $sessionData[$cCode]['main']['sub_' . $key] = ($sessionData[$cCode]['main']['jml'] * $sessionData[$cCode]['main'][$key]);
                                                }
                                            }
                                        }
                                    }

                                }
                            }
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }

                        cekPink2("fillvalue setelah $comName");
                        $this->CI->load->helper("he_value_builder");
                        $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor, $sessionData[$cCode]);
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->CI->load->helper("he_value_builder");
                $sessionData[$cCode] = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $this->configCoreJenis, $this->configUiJenis, $this->configValuesJenis, $ppnFactor, $sessionData[$cCode]);
            }
            else {
                $this->tEcho("no processor defined. skipping preprocessor..<br>");
            }
            //endregion


            $this->CI->load->library("Validator");
            $vd = new Validator();
            $vd->setCCode($cCode);
            $vd->setConfigUiJenis($this->configUiJenis);
            $step = $sessionData[$cCode]['main']['step_number'];
            $vd->midValidate_ns($sessionData, $step);
            $vd->unionValidate_ns($sessionData);

            //===finalisasi sebelum masuk tabel beneran
            //===isinya ada pembentukan nomor nota dll


            //region penomoran receipt
            $this->CI->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);
            $counterForNumber = array($this->configCoreJenis['formatNota']);
            $this->tEcho("format_nota");
            arrPrintPink($counterForNumber);
            if (!in_array($counterForNumber[0], $this->configCoreJenis['counters'])) {
                mati_disini(__LINE__ . " Used number should be registered in 'counters' config as well");
            }
            $this->tEcho("<div style='background:#ff7766;'>");
            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
            }
            $this->tEcho("</div style='background:#ff7766;'>");
            arrPrintWebs($paramSpec);
            // mati_disini("hahaha " . __LINE__);

            $stepNumber = 1;
            $tmpNomorNota = $paramSpec['paramString'];
            $tmpNomorNotaAlias = formatNota("nomer_nolink", $tmpNomorNota);
            if (isset($this->configUiJenis['steps'][2])) {
                $nextProp = array(
                    "num" => 2,
                    "code" => $this->configUiJenis['steps'][2]['target'],
                    "label" => $this->configUiJenis['steps'][2]['label'],
                    "groupID" => $this->configUiJenis['steps'][2]['userGroup'],
                );
            }
            else {
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion


            //region dynamic counters
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);
            $configCustomParams = $this->configCoreJenis['counters'];
            $configCustomParams[] = "stepCode";

            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
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
                    showLast_query("orange");
                }
            }
            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);

            //region addition on master
            $addValues = array(
                'counters' => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'nomer2' => $tmpNomorNotaAlias,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => sizeof($this->configUiJenis['steps']),
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "tail_number" => 1,
                "tail_code" => $this->configUiJenis['steps'][1]['target'],


            );
            foreach ($addValues as $key => $val) {
                $sessionData[$cCode]['tableIn_master'][$key] = $val;
            }
            //endregion

            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => sizeof($this->configUiJenis['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
                "sub_tail_number" => 1,
                "sub_tail_code" => $this->configUiJenis['steps'][1]['target'],
            );
            foreach ($sessionData[$cCode]['tableIn_detail'] as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $sessionData[$cCode]['tableIn_detail'][$id][$key] = $val;
                }
            }
            //endregion
            //endregion


            //region numbering tambahan
            $this->CI->load->library("CounterNumber");
            $ccn = new CounterNumber();
            $ccn->setCCode($cCode);
            $ccn->setJenisTr($this->jenisTr);
            $ccn->setTransaksiGate($sessionData[$cCode]['tableIn_master']);
            $ccn->setMainGate($sessionData[$cCode]['main']);
            $ccn->setItemsGate($sessionData[$cCode]['items']);
            $ccn->setItems2SumGate($sessionData[$cCode]['items2_sum']);
            $new_counter = $ccn->getCounterNumber();
            cekHitam("jenistr yang disett dari create " . $this->jenisTr);
            $this->tEcho("___counter");
            arrPrintHijau($new_counter);


            $costum_counter = array(
                "_dtime",
                "_company",
                "_company_stepCode"
            );

            foreach ($costum_counter as $item) {
                $counter_nilai = $new_counter['main'][$item];

                $var = $counter_nilai;
                if ($hasil == "") {
                    $hasil .= "$var";
                }
                else {
                    $hasil = "$hasil" . "-" . "$var";
                }

            }
            // cekBiru("hasil $hasil");
            // matiHere(__LINE__);
            if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                foreach ($new_counter['main'] as $ckey => $cval) {
                    $sessionData[$cCode]['tableIn_master'][$ckey] = $cval;
                    $sessionData[$cCode]['main'][$ckey] = $cval;
                }
            }
            if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                foreach ($new_counter['items'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $sessionData[$cCode]['items'][$ikey][$iikey] = $iival;
                    }
                }
            }
            if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $sessionData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                    }
                }
            }
            //endregion

            /** ----------------------------------------------------
             * modul counter global
             * ----------------------------------------------------*/
            if (isset($this->configUiJenis["counter_global"])) {

                $sessionMainCounter = $sessionData[$cCode]['main'] + $new_counter['main'];
                $counter_global_part = $this->configUiJenis["counter_global_part"];
                $counter_global_part[] = $this->configUiJenis["counter_global"];
                $nomer_new = build_global_counter($sessionMainCounter, $counter_global_part);

                cekMerah($nomer_new);
                //matiHere(__LINE__);
                $sessionData[$cCode]['tableIn_master']['nomer_new'] = $nomer_new;
            }
            // ---------------------------------------------------------------------------------------


            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
            if (isset($sessionData[$cCode]['tableIn_master']) && sizeof($sessionData[$cCode]['tableIn_master']) > 0) {

                $sessionData[$cCode]['tableIn_master']['status_4'] = 11;
                $sessionData[$cCode]['tableIn_master']['trash_4'] = 0;
                $sessionData[$cCode]['tableIn_master']['cli'] = 1;
                $sessionData[$cCode]['tableIn_master']['dtime'] = $transaksi_current_dtime;
                $sessionData[$cCode]['tableIn_master']['fulldate'] = $transaksi_current_fulldate;

                arrPrintCyan($sessionData[$cCode]['tableIn_master']);

                $tr = new MdlTransaksi();
                $insertID = $tr->writeMainEntries($sessionData[$cCode]['tableIn_master']);
                cekHitam($this->CI->db->last_query());
//                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $sessionData[$cCode]['tableIn_master']);
                $epID = $tr->writeMainEntries_entryPoint($insertID, $transaksi_reference_master_id, $sessionData[$cCode]['tableIn_master']);
                $insertNum = $sessionData[$cCode]['tableIn_master']['nomer'];
                $sessionData[$cCode]['main']['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }
                $mongoList['main'] = array($insertID, $epID);
                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                    "nomer2" => $tmpNomorNotaAlias,
                );
                $arrInjectorsTarget = array(
                    "items",
                    "items2_sum",
                    "rsltItems",
                );
                foreach ($injectors as $key => $val) {
                    $sessionData[$cCode]['main'][$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        if (isset($sessionData[$cCode][$target])) {
                            foreach ($sessionData[$cCode][$target] as $xid => $iSpec) {
                                $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                                if (isset($sessionData[$cCode][$target][$id])) {
                                    $sessionData[$cCode][$target][$id][$key] = $val;
                                }
                            }
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $sessionData[$cCode]['main']['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
                    "step_name" => $this->configUiJenis['steps'][1]['label'],
                    "group_code" => $this->configUiJenis['steps'][1]['userGroup'],
                    "oleh_id" => $transaksi_oleh_id,
                    "oleh_nama" => $transaksi_oleh_nama,
                    "keterangan" => $this->configUiJenis['steps'][1]['label'] . " oleh system",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");
                $mongoList['sign'][] = $dwsign;
                $idHis = array(
                    $stepNumber => array(
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                        "olehID" => $sessionData[$cCode]['main']['olehID'],
                        "olehName" => $sessionData[$cCode]['main']['olehName'],
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "nomer2" => $tmpNomorNotaAlias,
                        "counters" => $appliedCounters,
                        "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                    "next_step_num" => $nextProp['num'],
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],

                    //===references
//                    "id_master" => $insertID,
                    "id_master" => $transaksi_reference_master_id,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $sessionData[$cCode]['main']['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,

                )) or die("Failed to update tr next-state!");
                cekHijau($this->CI->db->last_query());

//                arrPrintWebs($sessionData[$cCode]['tableIn_master']);

                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $sessionData[$cCode]['main']['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                );
                foreach ($addValues as $key => $val) {
                    $sessionData[$cCode]['tableIn_master'][$key] = $val;
                }

            }
            if (isset($sessionData[$cCode]['tableIn_master_values']) && sizeof($sessionData[$cCode]['tableIn_master_values']) > 0) {
                $inserMainValues = array();
                if (isset($this->configCoreJenis['tableIn']['mainValues'])) {
                    //matiHEre("hooppp");
                    $inserMainValues = array();
                    foreach ($this->configCoreJenis['tableIn']['mainValues'] as $key => $src) {
                        if (isset($sessionData[$cCode]['tableIn_master_values'][$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $sessionData[$cCode]['tableIn_master_values'][$key],
                            ));

                            $inserMainValues[] = $dd;
                            $mongoList['mainValues'][] = $dd;
                        }
                    }
                }

                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->CI->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }

            }
            if (isset($sessionData[$cCode]['main_add_values']) && sizeof($sessionData[$cCode]['main_add_values']) > 0) {
                $inserMainValues = array();
                foreach ($sessionData[$cCode]['main_add_values'] as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }

                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->CI->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                foreach ($sessionData[$cCode]['main_inputs'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));

                }
            }
            if (isset($sessionData[$cCode]['main_add_fields']) && sizeof($sessionData[$cCode]['main_add_fields']) > 0) {
                foreach ($sessionData[$cCode]['main_add_fields'] as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($sessionData[$cCode]['main_applets']) && sizeof($sessionData[$cCode]['main_applets']) > 0) {
                foreach ($sessionData[$cCode]['main_applets'] as $amdl => $aSpec) {
                    $tr->writeMainApplets($insertID, array(
                        "mdl_name" => $amdl,
                        "key" => $aSpec['key'],
                        "label" => $aSpec['labelValue'],
                        "description" => $aSpec['description'],
                    ));
                }
            }
            if (isset($sessionData[$cCode]['main_elements']) && sizeof($sessionData[$cCode]['main_elements']) > 0) {
                foreach ($sessionData[$cCode]['main_elements'] as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                        "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                        "name" => $aSpec['name'],
                        "label" => $aSpec['label'],
                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
//                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                    ));


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
                        }
                        else {
                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }

                    }

                }
            }
            if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {

                $insertIDs = array();
                $insertDeIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail'] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    if ($insertDetailID < 1) {
                        die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                    }
                    else {
                        $insertIDs[] = $insertDetailID;
                        $insertDeIDs[$insertID][] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                    if ($epID != 999) {
                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                        if ($insertEpID < 1) {
                            die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                        }
                        else {
                            $insertIDs[] = $insertEpID;
                            $insertDeIDs[$epID][] = $insertEpID;
                            $mongoList['detail'][] = $insertEpID;
                        }
                    }
                    cekUngu($this->CI->db->last_query());
                }


                if (sizeof($insertIDs) == 0) {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }
                else {
                    $indexing_details = array();
                    foreach ($insertDeIDs as $key => $numb) {
                        $indexing_details[$key] = $numb;
                    }

                    foreach ($indexing_details as $k => $arrID) {
                        $arrBlob = blobEncode($arrID);
                        $this->CI->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                        cekOrange($this->CI->db->last_query());
                    }
                }
            }
            else {
                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
            }
            if (isset($sessionData[$cCode]['tableIn_detail2']) && sizeof($sessionData[$cCode]['tableIn_detail2']) > 0) {
                $insertIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail2'] as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    $mongoList['detail'] = $insertIDs;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
                    }
                    cekUngu($this->CI->db->last_query());
                }
            }
            if (isset($sessionData[$cCode]['tableIn_detail2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail2_sum']) > 0) {
                $insertIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $dd = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                    }
                }
            }
            if (isset($sessionData[$cCode]['tableIn_detail_rsltItems']) && sizeof($sessionData[$cCode]['tableIn_detail_rsltItems']) > 0) {
                $insertIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail_rsltItems'] as $dSpec) {
                    $dd = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $dd;
                    $mongoList['detil'][] = $dd;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                        $mongoList['detil'] = $insertIDs;
                    }
                    cekUngu($this->CI->db->last_query());
                }
            }
            if (isset($sessionData[$cCode]['tableIn_detail_values']) && sizeof($sessionData[$cCode]['tableIn_detail_values']) > 0) {
                $insertIDs = array();
                foreach ($sessionData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                    if (isset($this->configCoreJenis['tableIn']['detailValues'])) {
                        foreach ($this->configCoreJenis['tableIn']['detailValues'] as $key => $src) {
                            if (isset($sessionData[$cCode]['tableIn_detail'][$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $sessionData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : "0",
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;

                            }
                        }
                    }
                }
                if (sizeof($insertIDs) > 0) {
                    $arrBlob = blobEncode($insertIDs);
                    $this->CI->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($sessionData[$cCode]['tableIn_detail_values2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                foreach ($sessionData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                    if (isset($this->configCoreJenis['tableIn']['detailValues2_sum'])) {
                        $insertIDs = array();
                        foreach ($this->configCoreJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $sessionData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => $dSpec[$src],
                            ));
                            $insertIDs[] = $dd;
                            $mongoList['detailValues'][] = $dd;
                        }
                    }
                }
            }
            //endregion


            //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
            $steps = $this->configUiJenis['steps'];

            //region processing sub-components, if in single step geser ke CLI

            $componentGate['detail'] = array();
            $componentConfig['detail'] = array();
            //            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
            $filterNeeded = false;
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['jurnal']['detail']) ? $sessionData[$cCode]['revert']['jurnal']['detail'] : array();
                $revertedTarget = $sessionData[$cCode]['main']['pihakExternID'];
            }
            else {
                $iterator = isset($this->configCoreJenis['components'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['components'][$jenisTrTarget]['detail'] : array();
                $revertedTarget = "";
            }
            $componentConfig['detail'] = $iterator;
            //            //region processing sub-components
            //            if (sizeof($iterator) > 0) {
            //                foreach ($iterator as $cCtr => $tComSpec) {
            //                    $tmpOutParams[$cCtr] = array();
            //                    $gg = 0;
            //                    $srcGateName = $tComSpec['srcGateName'];
            //                    foreach ($sessionData[$cCode][$srcGateName] as $id => $dSpec) {
            //                        $srcRawGateName = $tComSpec['srcRawGateName'];
            //                        $comName = $tComSpec['comName'];
            //                        if (substr($comName, 0, 1) == "{") {
            //                            $comName = trim($comName, "{");
            //                            $comName = trim($comName, "}");
            //                            //                            $comName = str_replace($comName, $sessionData[$cCode]['main'][$comName], $comName);
            //                            cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
            //                            $comName = str_replace($comName, $sessionData[$cCode][$srcGateName][$id][$comName], $comName);
            //                        }
            //                        cekHitam(":: $comName ::");
            //                        $mdlName = "Com" . ucfirst($comName);
            //                        if (in_array($mdlName, $compValidators)) {//perlu validasi filter
            ////cekLime($mdlName. "line");
            //                            $filterNeeded = true;
            //                        }
            //                        else {
            //                            cekLime($mdlName . "like");
            //                            $filterNeeded = false;
            //                        }
            //                        echo "sub-component: $comName, initializing values <br>";
            //                        //                        cekHitam(__LINE__);
            //                        //                        $tmpOutParams[$cCtr] = array();
            //
            //                        //                        cekhitam("$comName filterneeded: $filterNeeded");
            //                        //                        cekhitam("mau mengiterasi $srcGateName");
            //                        //                        cekhitam("telah mengiterasi $srcGateName");
            //                        //
            //                        $subParams = array();
            ////arrPrint($tComSpec);
            //                        if (isset($tComSpec['loop'])) {
            //                            foreach ($tComSpec['loop'] as $key => $value) {
            //                                cekMerah(":: $key => $value ::");
            //                                if (substr($key, 0, 1) == "{") {
            //                                    $key = trim($key, "{");
            //                                    $key = trim($key, "}");
            //                                    //                                    $key = str_replace($key, $sessionData[$cCode]['main'][$key], $key);
            //                                    $key = str_replace($key, $sessionData[$cCode][$srcGateName][$id][$key], $key);
            //                                }
            //
            //                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
            //                                $subParams['loop'][$key] = $realValue;
            //
            //                                if ($filterNeeded) {
            //                                    if ($subParams['loop'][$key] == 0) {
            //                                        unset($subParams['loop'][$key]);
            //                                    }
            //                                }
            //                            }
            //                        }
            //                        if (isset($tComSpec['static'])) {
            //                            foreach ($tComSpec['static'] as $key => $value) {
            //
            //                                $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
            //                                $subParams['static'][$key] = $realValue;
            //
            //                            }
            //                            if (!isset($subParams['static']["transaksi_id"])) {
            //                                $subParams['static']["transaksi_id"] = $insertID;
            //                            }
            //                            if (!isset($subParams['static']["transaksi_no"])) {
            //                                $subParams['static']["transaksi_no"] = $insertNum;
            //                            }
            //
            //                            $subParams['static']["fulldate"] = $transaksi_current_fulldate;
            //                            $subParams['static']["dtime"] = $transaksi_current_dtime;
            //                            $subParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";
            //                            if (strlen($revertedTarget) > 1) {
            //                                $subParams['static']['reverted_target'] = $revertedTarget;
            //                            }
            //                        }
            //                        //arrPrint($subParams);
            //                        if (sizeof($subParams) > 0) {
            ////                            arrprint($subParams);
            //                            cekhitam("subparam ada isinya");
            //                            if ($filterNeeded) {
            //                                if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
            //                                    $tmpOutParams[$cCtr][] = $subParams;
            //                                }
            //                            }
            //                            else {
            //                                $tmpOutParams[$cCtr][] = $subParams;
            ////                                CekHijiau("asem" .$gg++);
            //                            }
            //                        }
            //                        else {
            //                            cekhitam("subparam TIDAK ada isinya");
            //                        }
            //                    }
            //
            //                    $componentGate['detail'][$cCtr] = $subParams;
            //                }
            //                //cekHitam("cetak tmpOutParams");
            //
            //                foreach ($iterator as $cCtr => $tComSpec) {
            //                    $srcGateName = $tComSpec['srcGateName'];
            //                    foreach ($sessionData[$cCode][$srcGateName] as $id => $dSpec) {
            //
            //                        $srcRawGateName = $tComSpec['srcRawGateName'];
            //                        $comName = $tComSpec['comName'];
            //                        if (substr($comName, 0, 1) == "{") {
            //                            $comName = trim($comName, "{");
            //                            $comName = trim($comName, "}");
            //                            $comName = str_replace($comName, $sessionData[$cCode][$srcGateName][$id][$comName], $comName);
            //                            //                        $comName = str_replace($comName, $sessionData[$cCode]['main'][$comName], $comName);
            //                        }
            //                    }
            //                    echo "sub component: $comName, sending values <br>";
            //
            //                    $mdlName = "Com" . ucfirst($comName);
            //                    $this->CI->load->model("Coms/" . $mdlName);
            //                    $m = new $mdlName();
            //                    //===filter value nol, jika harus difilter
            ////                    arrPrint($tmpOutParams[$cCtr]);
            //                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
            //                        $tobeExecuted = true;
            //                    }
            //                    else {
            //                        $tobeExecuted = false;
            //                    }
            //
            //                    if ($tobeExecuted) {
            //                        cekMerah("$comName dieksekusiii");
            //                        arrPrint($tmpOutParams[$cCtr]);
            //                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
            //                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
            //                        cekBiru($this->CI->db->last_query());
            //                    }
            //                    else {
            //                        cekMerah("$comName tidak eksekusi");
            //                    }
            //
            //                }
            //            }
            //            else {
            //                //cekKuning("subcomponents is not set");
            //            }
            //            //endregion


            //region processing main components, if in single step
            $componentJurnal = array();
            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            //==filter nilai, jika NOL tidak dikirim, sesuai config==
            $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['jurnal']['master']) ? $sessionData[$cCode]['revert']['jurnal']['master'] : array();
            }
            else {
                $iterator = isset($this->configCoreJenis['components'][$jenisTrTarget]['master']) ? $this->configCoreJenis['components'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {
                $componentConfig['master'] = $iterator;
                $cCtr = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $cCtr++;
                    $comName = $tComSpec['comName'];
                    if (substr($comName, 0, 1) == "{") {
                        $comName = trim($comName, "{");
                        $comName = trim($comName, "}");
                        $comName = str_replace($comName, $sessionData[$cCode]['main'][$comName], $comName);
                    }
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $this->tEcho("component # $cCtr: $comName<br>");

                    $dSpec = $sessionData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $sessionData[$cCode]['main'][$key], $key);
                            }
                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = $transaksi_current_fulldate;
                        $tmpOutParams['static']["dtime"] = $transaksi_current_dtime;
                        $tmpOutParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";


                    }

                    if (isset($tComSpec['static2'])) {
                        //cekHere("DISINI OIII");
                        foreach ($tComSpec['static2'] as $key => $value) {
                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cCtr], $sessionData[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";
                    }

                    $mdlName = "Com" . ucfirst($comName);
                    $this->CI->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    //===filter value nol, jika harus difilter
                    $tobeExecuted = true;
                    if (in_array($mdlName, $compValidators)) {
                        $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                        if (sizeof($loopParams) > 0) {
                            foreach ($loopParams as $key => $val) {
                                cekmerah("$comName : $key = $val ");
                                if ($val == 0) {
                                    unset($tmpOutParams['loop'][$key]);
                                }
                            }
                        }
                        if (sizeof($tmpOutParams['loop']) < 1) {
                            $tobeExecuted = false;
                        }
                    }
                    if ($tobeExecuted) {
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }
                    $componentGate['master'][$cCtr] = $tmpOutParams;
                    if ($comName == "Jurnal") {
                        $componentJurnal[] = $tmpOutParams;
                    }
                }
            }
            else {
                //cekKuning("components is not set");
            }


            //endregion


            cekHitam(":: START POST PROCC DETAIL... ::");

            //region processing sub-post-processors, always
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['postProc']['detail']) ? $sessionData[$cCode]['revert']['postProc']['detail'] : array();
                cekHitam("post procc pakai revert");
            }
            else {
                $iterator = isset($this->configCoreJenis['postProcessor'][$jenisTrTarget]['detail']) ? $this->configCoreJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
                cekHitam("post procc pakai config core");
            }
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $this->tEcho("[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>");
                    $tmpOutParams[$cCtr] = array();
                    if (isset($sessionData[$cCode][$srcGateName]) && (sizeof($sessionData[$cCode][$srcGateName]) > 0)) {
                        arrPrint($sessionData[$cCode][$srcGateName]);
                        foreach ($sessionData[$cCode][$srcGateName] as $xid => $dSpec) {
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    cekHitam("gate: $srcGateName, dengan key $id");
                                    $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$id], $sessionData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }
                                $subParams['static']["fulldate"] = $transaksi_current_fulldate;
                                $subParams['static']["dtime"] = $transaksi_current_dtime;
                                if (isset($sessionData[$cCode]['revert']['postProc']['detail'])) {
                                    $subParams['static']["reverted_target"] = $sessionData[$cCode]['main']['pihakExternID'];
                                }
                                $subParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";
                            }
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                            }
                        }
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (isset($sessionData[$cCode][$srcGateName])) {
                        $this->tEcho("[$cCtr] sub-postProcessor: $comName, sending values <br>");
                        $mdlName = "Com" . ucfirst($comName);
                        $this->CI->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekPink($this->CI->db->last_query());
                    }

                }
            }
            //endregion


            //region relesaese connected payment source dan marking trash transaksi
            if (isset($sessionData[$cCode]["revert"]["connectedPaymentsource"]) && $sessionData[$cCode]["revert"]["connectedPaymentsource"] == true) {
                $keyRel = $sessionData[$cCode]["main"]["referenceID"];
                $keyRelRef = $sessionData[$cCode]["main"]["pihakExternID"];
                $relPaymentSrc = isset($this->CI->config->item("payment_source")[$keyRelRef]) ? $this->CI->config->item("payment_source")[$keyRelRef] : array();
                if (sizeof($relPaymentSrc) > 0) {
                    $this->CI->load->model("Mdls/MdlPaymentSource");
                    $m = new MdlPaymentSource();
                    $m->addFilter("transaksi_id='$keyRel'");
                    $tmpRelPay = $m->lookupAll()->result();
                    $paymentRelUsed = array(
                        "cabang_id" => "placeID",
                        "extern_id" => "id",
                        "extern_nama" => "name",
                        "label" => ".hutang biaya",
                        "target_jenis" => "jenisTr",
                        "transaksi_id" => "refID",
                        "terbayar" => "nilai_bayar",
                        "sisa" => "new_sisa",
                        "ppn" => "valid_ppn",
                        "extern_nilai2" => "valid_dpp",
                    );
                    if (sizeof($tmpRelPay) > 0) {
                        $tmpOutParams = array();
                        $iterator = array();
                        foreach ($tmpRelPay as $indexKey => $relData) {
                            $tmp = array();
                            foreach ($paymentRelUsed as $key => $keyGate) {
                                if ($key == "terbayar") {
                                    $val = $relData->sisa;
                                }
                                else {
                                    if ($key == "sisa") {
                                        $val = "-" . $relData->sisa;
                                    }
                                    else {
                                        $val = $relData->$key;
                                    }
                                }
                                $tmp["static"][$key] = $val;
                                $tmp["static"]["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";
                            }
                            $iterator[$indexKey]["loop"] = array();
                            $iterator[$indexKey]["comName"] = "PaymentSrcItem";
                            if (sizeof($tmp) > 0) {
                                $tmpOutParams[$indexKey][] = $tmp;
                            }
                        }
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $this->tEcho("sub-postProcessor: $comName, sending values <br>");
                            $mdlName = "Com" . ucfirst($comName);
                            $this->CI->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            //                            cekHitam($this->CI->db->last_query());
                        }
                    }
                    //                    foreach()
                    cekLime("yuk direset paymentsource**");
                }
            }
            //endregion


            //region processing main-post-processors, always
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $iterator = isset($sessionData[$cCode]['revert']['postProc']['detail']) ? $sessionData[$cCode]['revert']['postProc']['master'] : array();
            }
            else {
                $iterator = isset($this->configCoreJenis['postProcessor'][$jenisTrTarget]['master']) ? $this->configCoreJenis['postProcessor'][$jenisTrTarget]['master'] : array();
            }

            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $this->tEcho("post-processor: $comName<br>LINE: " . __LINE__);
                    $dSpec = $sessionData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName], $sessionData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["fulldate"] = $transaksi_current_fulldate;
                        $tmpOutParams['static']["dtime"] = $transaksi_current_dtime;
                        $tmpOutParams['static']["keterangan"] = $this->configUiJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh ";
                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {
                            $realValue = makeValue($value, $sessionData[$cCode][$srcGateName][$cCtr], $sessionData[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $this->configUiJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh ";
                    }
                    $mdlName = "Com" . ucfirst($comName);
                    $this->CI->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    cekBiru("kiriman komponem $comName");
                    arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    //cekHitam($this->CI->db->last_query());
                }
            }
            else {

            }
            //endregion

            //region updater main transaksi rejection jurnal next step exist
            $mongUpdateList = array();
            if (isset($this->configCoreJenis['relativeComponets']) && $this->configCoreJenis['relativeComponets'] == true) {
                $tr->setFilters(array());
                $tr->addFilter("id='" . $sessionData[$cCode]["main"]["referenceID"] . "'");
                $tempData = $tr->lookupAll()->result();
                $refMasterID = $tempData[0]->id_master;
                $refMasterJenis = $tempData[0]->jenis_master;
                $nextStepCode = $tempData[0]->next_step_code;
                $mainStepCode = $tempData[0]->jenis;
                $stepnum = $tempData[0]->step_number;
                $stepnumAvail = $tempData[0]->step_avail;
                if (($stepnumAvail - $stepnum) > 0) {
                    $this->CI->load->model("Coms/ComTransaksi_jurnal_revert");
                    $r = new ComTransaksi_jurnal_revert();
                    $outParams = array(
                        "refID" => $refMasterID,
                        "main_code" => $mainStepCode,
                        "next_code" => $nextStepCode,
                        "step_num" => $stepnum,
                    );
                    $r->pair($outParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $r->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);

                    //marking main transaksi trash4
                    $udpate = array(
                        "trash_4" => "1",
                    );
                    $tr->setFilters(array());
                    $dupState = $tr->updateData(array(
                        "id" => $sessionData[$cCode]["main"]["referenceID"],
                    ), $udpate) or die("Failed to update tr next-state!");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array("id" => $sessionData[$cCode]["main"]["referenceID"]),
                        "value" => array(
                            "trash_4" => "1",
                        ),
                    );
                    cekHijau("UPDATE transaksi step sebelumnya...");
                    cekHijau($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");

                    //update validqty 0 supaya gak bisa difollowup
                    $td = new MdlTransaksi();
                    $td->setFilters(array());
                    $rslt = $td->lookupJoinedByID($sessionData[$cCode]["main"]["referenceID"])->result();
                    if (sizeof($rslt) > 0) {
                        foreach ($rslt as $rsltSpec) {
                            if (array_key_exists($rsltSpec->produk_id, $sessionData[$cCode]["items"])) {
                                $arrData_detail["valid_qty"] = 0;
                                $tr = new MdlTransaksi();
                                $tr->setFilters(array());
                                $tr->setTableName($tr->getTableNames()['detail']);
                                $dupState = $tr->updateData(array(
                                    "transaksi_id" => $sessionData[$cCode]["main"]["referenceID"],
                                    "produk_id" => $rsltSpec->produk_id,
                                ), $arrData_detail) or die("Failed to update tr next-state!");
                                $mongUpdateList['update']['detail'][] = array(
                                    "where" => array(
                                        "transaksi_id" => $sessionData[$cCode]["main"]["referenceID"],
                                        "produk_id" => $rsltSpec->produk_id,
                                    ),
                                    "value" => $arrData_detail,
                                );
                                cekKuning("UPDATE transaksi data...");
                                cekKuning($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");
                            }
                        }
                    }

                    $dwsign = $tr->writeSignature($refMasterID, array(
                        "prev_id" => "",
                        "nomer" => "pembatalan jurnal",
                        "step_number" => "-" . $stepnum, // ini minus step number
                        "step_code" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['target'],
                        "step_name" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'],
                        "group_code" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['userGroup'],
                        "oleh_id" => $transaksi_oleh_id,
                        "oleh_nama" => $transaksi_oleh_nama,
                        "keterangan" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'] . " oleh ",
                        //            "transaksi_id" => $no,
                    )) or die("Failed to write signature");
                    $mongoList['sign'][] = $dwsign;
                    cekKuning($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");
                    //                    matiHere();
                }
                else {
                    //marking main transaksi trash4
                    $udpate = array(
                        "trash_4" => "1",
                    );
                    $tr->setFilters(array());
                    //                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array(
                        //                "id" => $no,
                        "id" => $sessionData[$cCode]["main"]["referenceID"],
                    ), $udpate) or die("Failed to update tr next-state!");
                    cekHijau("UPDATE transaksi step sebelumnya...");
                    cekHijau($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array("id" => $sessionData[$cCode]["main"]["referenceID"]),
                        "value" => array(
                            "trash_4" => "1",
                        ),
                    );

                    //update validqty 0 supaya gak bisa difollowup
                    $arrData_detail["valid_qty"] = $new_valid_qty;
                    $td = new MdlTransaksi();
                    $td->setFilters(array());
                    $rslt = $td->lookupJoinedByID($sessionData[$cCode]["main"]["referenceID"])->result();
                    if (sizeof($rslt) > 0) {
                        foreach ($rslt as $rsltSpec) {
                            if (array_key_exists($rsltSpec->produk_id, $sessionData[$cCode]["items"])) {
                                $arrData_detail["valid_qty"] = 0;
                                $tr = new MdlTransaksi();
                                $tr->setFilters(array());
                                $tr->setTableName($tr->getTableNames()['detail']);
                                $dupState = $tr->updateData(array(
                                    "transaksi_id" => $sessionData[$cCode]["main"]["referenceID"],
                                    "produk_id" => $rsltSpec->produk_id,
                                ), $arrData_detail) or die("Failed to update tr next-state!");
                                cekKuning("UPDATE transaksi data...");
                                cekKuning($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");
                                $mongUpdateList['update']['detail'][] = array(
                                    "where" => array(
                                        "transaksi_id" => $sessionData[$cCode]["main"]["referenceID"],
                                        "produk_id" => $rsltSpec->produk_id,
                                    ),
                                    "value" => $arrData_detail,
                                );
                            }
                        }
                    }

                    $dwsign = $tr->writeSignature($refMasterID, array(
                        "prev_id" => "",
                        "nomer" => "pembatalan jurnal",
                        "step_number" => "-" . $stepnum, // ini minus step number
                        "step_code" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['target'],
                        "step_name" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'],
                        "group_code" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['userGroup'],
                        "oleh_id" => $transaksi_oleh_id,
                        "oleh_nama" => $transaksi_oleh_nama,
                        "keterangan" => $this->configUi[$refMasterJenis]['steps'][abs($stepnum)]['label'] . " oleh ",
                        //            "transaksi_id" => $no,
                    )) or die("Failed to write signature");
                    $mongoList['sign'][] = $dwsign;
                    cekKuning($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");
                }
            }
            //endregion

            // region berlaku pembatalan transaksi bila ada config revertStep di model MdlRevertJurnal (true)
            if (isset($sessionData[$cCode]['main']['pihakExternRevertStep']) && ($sessionData[$cCode]['main']['pihakExternRevertStep'] == true)) {
                $referenceNextProp = (isset($sessionData[$cCode]['main']['referenceNextProp']) && (sizeof($sessionData[$cCode]['main']['referenceNextProp']) > 0)) ? $sessionData[$cCode]['main']['referenceNextProp'] : array();
                if (sizeof($referenceNextProp) > 0) {
                    // update transaksi reference, step sebelumnya menjadi aktif lagi
                    $tr = new MdlTransaksi();
                    $tr->setFilters(array());
                    $dupState = $tr->updateData(array("id" => $referenceNextProp['trID']), array(
                        "next_step_code" => $referenceNextProp['code'],
                        "next_step_label" => $referenceNextProp['label'],
                        "next_group_code" => $referenceNextProp['groupID'],
                        "next_step_num" => $referenceNextProp['num'],
                        "step_current" => $referenceNextProp['step_num'],
                    )) or die("Failed to update tr next-state!");
                    cekHijau("BATAL :: " . $this->CI->db->last_query() . " -- " . $this->CI->db->affected_rows());
                    $mongUpdateList['update']['main'][] = array(
                        "where" => array("id" => $referenceNextProp['trID']),
                        "value" => array(
                            "next_step_code" => $referenceNextProp['code'],
                            "next_step_label" => $referenceNextProp['label'],
                            "next_group_code" => $referenceNextProp['groupID'],
                            "next_step_num" => $referenceNextProp['num'],
                            "step_current" => $referenceNextProp['step_num'],
                        ),
                    );

                    // update transaksi data reference, step sebelumnya menjadi aktif lagi
                    $tr = new MdlTransaksi();
                    $tr->setFilters(array());
                    $tr->addFilter("trash='0'");
                    $tr->addFilter("transaksi_id='" . $referenceNextProp['trID'] . "'");
                    $tr->setTableName($tr->getTableNames()['detail']);
                    $detailTmp = $tr->lookupAll()->result();
                    $detailData = array();
                    foreach ($detailTmp as $dTmpSpec) {
                        $detailData[$dTmpSpec->produk_id] = array(
                            "valid_qty" => $dTmpSpec->valid_qty,
                        );
                    }
                    cekOrange($referenceNextProp['detailGate']);
                    if (isset($sessionData[$cCode][$referenceNextProp['detailGate']]) && ($sessionData[$cCode][$referenceNextProp['detailGate']] != NULL)) {
                        foreach ($sessionData[$cCode][$referenceNextProp['detailGate']] as $itemsSpec) {
                            $valid_qty = isset($detailData[$itemsSpec['id']]['valid_qty']) ? $detailData[$itemsSpec['id']]['valid_qty'] : 0;
                            $valid_qty_new = $valid_qty + $itemsSpec['qty'];
                            $tr = new MdlTransaksi();
                            $tr->setFilters(array());
                            $tr->setTableName($tr->getTableNames()['detail']);
                            $ddupState = $tr->updateData(
                                array(
                                    "transaksi_id" => $referenceNextProp['trID'],
                                    "trash" => 0,
                                    "produk_id" => $itemsSpec['id'],
                                ), array(
                                "next_substep_code" => $referenceNextProp['code'],
                                "next_substep_label" => $referenceNextProp['label'],
                                "next_subgroup_code" => $referenceNextProp['groupID'],
                                "next_substep_num" => $referenceNextProp['num'],
                                "sub_step_current" => $referenceNextProp['step_num'],
                                "valid_qty" => $valid_qty_new,

                            )) or die("Failed to update tr next-state!");
                            cekHijau("BATAL :: " . $this->CI->db->last_query() . " -- " . $this->CI->db->affected_rows());
                            $mongUpdateList['update']['detail'][] = array(
                                "where" => array(
                                    "transaksi_id" => $referenceNextProp['trID'],
                                    "trash" => 0,
                                    "produk_id" => $itemsSpec['id'],
                                ),
                                "value" => array(
                                    "next_substep_code" => $referenceNextProp['code'],
                                    "next_substep_label" => $referenceNextProp['label'],
                                    "next_subgroup_code" => $referenceNextProp['groupID'],
                                    "next_substep_num" => $referenceNextProp['num'],
                                    "sub_step_current" => $referenceNextProp['step_num'],
                                    "valid_qty" => $valid_qty_new,
                                ),
                            );
                        }
                    }
                }
            }
            // endregion

            //region nulis paymentSource
            $stepCode = $this->configUiJenis['steps'][1]['target'];
            $paymentSources = $this->CI->config->item("payment_source");
            if (array_key_exists($stepCode, $paymentSources)) {
                $payConfigs = $paymentSources[$stepCode];
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs[1] as $paymentSrcConfig) {
                        $valueLabel = isset($paymentSrcConfig['label_key']) ? $paymentSrcConfig['label_key'] : $paymentSrcConfig['label'];
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $paymentMethod = isset($paymentSrcConfig['method']) ? $paymentSrcConfig['method'] : "insert";
                        if ($paymentMethod == "update") {
                            $filters = array(
                                "extern_id" => ""
                            );
                            $tr->setFilters(array());
                            $tmpData = $tr->lookupPaymentSrcByJenis($paymentSrcConfig['jenisTarget'])->result();
                            if (sizeof($tmpData) > 0) {
                                //sudah ada update aja gak perlu insert
                                $prevID = $tmpData[0]->id;
                                $preValue = $tmpData[0]->sisa;
                                $currValue = isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0;
                                $newValue = $preValue + $currValue;
                                $where = array(
                                    "id" => $prevID,
                                    //                                    "transaksi_id" => $pSpec->transaksi_id,
                                );
                                $data = array(
                                    "tagihan" => $newValue,
                                    "sisa" => $newValue,
                                );
                                $tr->updatePaymentSrc($where, $data);
                                //                                cekHitam($this->CI->db->last_query());
                            }
                            else {
                                //di insert baru
                                $tr->writePaymentSrc($insertID, array(
                                    "jenis" => $stepCode,
                                    "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                    "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                    "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                    "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                    "nomer" => $sessionData[$cCode]['main']['nomer'],
                                    "label" => $paymentSrcConfig['label'],
                                    "tagihan" => isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0,
                                    "terbayar" => 0,
                                    "sisa" => isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0,
                                    "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                    "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                    "oleh_id" => $transaksi_oleh_id,
                                    "oleh_nama" => $transaksi_oleh_nama,
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "fulldate" => date("Y-m-d"),
                                    "valas_id" => (isset($externSrc['valasId']) && isset($sessionData[$cCode]['main'][$externSrc['valasId']])) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                    "valas_nama" => (isset($externSrc['valasLabel']) && isset($sessionData[$cCode]['main'][$externSrc['valasLabel']])) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                    "valas_nilai" => (isset($externSrc['valasValue']) && isset($sessionData[$cCode]['main'][$externSrc['valasValue']])) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : 0,
                                    "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']])) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : 0,
                                    "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($sessionData[$cCode]['main'][$externSrc['valasTerbayar']])) ? $sessionData[$cCode]['main'][$externSrc['valasTerbayar']] : 0,
                                    "sisa_valas" => (isset($externSrc['valasSisa']) && isset($sessionData[$cCode]['main'][$externSrc['valasSisa']])) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : 0,
                                    "extern_label2" => (isset($externSrc['extern_label2']) && ($sessionData[$cCode]['main'][$externSrc['extern_label2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_label2']] : "",
                                    "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                ));
                            }
                        }
                        else {
                            //region cek duplikasi paymentsource
                            $tr->setFilters(array());
                            $tr->addFilter("transaksi_id='$insertID'");
                            $tr->addFilter("target_jenis='" . $paymentSrcConfig['jenisTarget'] . "'");
                            // $tr->addFilter("target_jenis='759'");
                            $validateIsInserted = $tr->lookUpAllPaymentSrc()->result();
                            if (sizeof($validateIsInserted) > 0) {
                                matiHEre("Gagal menulis transaksi. Silahkan relogin untuk membersihkan sesi demi menghindari duplikasi data, dan coba kembali transaksi yang gagal");
                            }
                            //endregion

                            //-----------------------
                            cekHitam("valuelabel: $valueLabel, valueSrc: $valueSrc");
                            $this->CI->load->helper("he_payment_source");
                            paymentSource($this->jenisTr, $componentJurnal, $sessionData[$cCode]['main'], $valueLabel, $valueSrc);
                            //-----------------------
                            $arrDataPym = array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                "nomer" => $sessionData[$cCode]['main']['nomer'],
                                "label" => $paymentSrcConfig['label'],
                                "tagihan" => isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0,
                                "terbayar" => 0,
                                "sisa" => isset($sessionData[$cCode]['main'][$valueSrc]) ? $sessionData[$cCode]['main'][$valueSrc] : 0,
                                "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                "oleh_id" => $transaksi_oleh_id,
                                "oleh_nama" => $transaksi_oleh_nama,
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "valas_id" => (isset($externSrc['valasId']) && isset($sessionData[$cCode]['main'][$externSrc['valasId']])) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                "valas_nama" => (isset($externSrc['valasLabel']) && isset($sessionData[$cCode]['main'][$externSrc['valasLabel']])) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                "valas_nilai" => (isset($externSrc['valasValue']) && isset($sessionData[$cCode]['main'][$externSrc['valasValue']])) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : 0,
                                "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']])) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : 0,
                                "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($sessionData[$cCode]['main'][$externSrc['valasTerbayar']])) ? $sessionData[$cCode]['main'][$externSrc['valasTerbayar']] : 0,
                                "sisa_valas" => (isset($externSrc['valasSisa']) && isset($sessionData[$cCode]['main'][$externSrc['valasSisa']])) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : 0,
                                "extern_label2" => (isset($externSrc['extern_label2']) && ($sessionData[$cCode]['main'][$externSrc['extern_label2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_label2']] : "",
                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData[$cCode]['main'][$externSrc['extern_nilai2']])) ? $sessionData[$cCode]['main'][$externSrc['extern_nilai2']] : 0,
                                "payment_locked" => (isset($externSrc['payment_locked']) && ($sessionData[$cCode]['main'][$externSrc['payment_locked']])) ? $sessionData[$cCode]['main'][$externSrc['payment_locked']] : 0,
                                "cash_account" => (isset($externSrc['cash_account']) && ($sessionData[$cCode]['main'][$externSrc['cash_account']])) ? $sessionData[$cCode]['main'][$externSrc['cash_account']] : 0,
                                "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($sessionData[$cCode]['main'][$externSrc['cash_account_nama']])) ? $sessionData[$cCode]['main'][$externSrc['cash_account_nama']] : 0,
                                "extern2_id" => (isset($externSrc['extern2_id']) && ($sessionData[$cCode]['main'][$externSrc['extern2_id']])) ? $sessionData[$cCode]['main'][$externSrc['extern2_id']] : 0,
                                "extern2_nama" => (isset($externSrc['extern2_nama']) && ($sessionData[$cCode]['main'][$externSrc['extern2_nama']])) ? $sessionData[$cCode]['main'][$externSrc['extern2_nama']] : 0,
                            );
                            arrPrintWebs($arrDataPym);
                            $tr->writePaymentSrc($insertID, $arrDataPym);
                        }
                        cekMerah($this->CI->db->last_query());
                    }
                }
            }
            else {
                //cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion

            //region nulis paymentAntiSource
            $stepCode = $this->configUiJenis['steps'][1]['target'];
            $paymentSources = $this->CI->config->item("payment_antiSource") != null ? $this->CI->config->item("payment_antiSource") : array();
            if (array_key_exists($stepCode, $paymentSources)) {
                cekHitam(":: starting PAYMENT ANTI SOURCE");
                $payConfigs = $paymentSources[$stepCode];
                if (sizeof($payConfigs) > 0) {
                    foreach ($payConfigs as $paymentSrcConfig) {
                        $valueSrc = $paymentSrcConfig['valueSrc'];
                        $externSrc = $paymentSrcConfig['externSrc'];
                        $tr->writePaymentAntiSrc($insertID, array(
                            "jenis" => $stepCode,
                            "target_jenis" => $paymentSrcConfig['jenisTarget'],
                            "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                            "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                            "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                            "nomer" => $sessionData[$cCode]['main']['nomer'],
                            "label" => $paymentSrcConfig['label'],
                            "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                            "terbayar" => 0,
                            "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                            "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                            "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                            "oleh_id" => $transaksi_oleh_id,
                            "oleh_nama" => $transaksi_oleh_nama,
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                            "valas_id" => isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                            "valas_nama" => isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                            "valas_nilai" => isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',
                            "tagihan_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                            "terbayar_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTerbayar']]) ? $sessionData[$cCode]['main'][$externSrc['valasTerbayar']] : '',
                            "sisa_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',
                        ));
                        //cekMerah($this->CI->db->last_query());
                    }
                }
            }
            else {
                //cekMerah("TIDAK nulis paymentSrc");
            }
            //endregion


            //====registri value-gate
            if (isset($this->configCoreJenis['components'][$jenisTrTarget]) && sizeof($this->configCoreJenis['components'][$jenisTrTarget])) {
                $jurnalIndex = $this->configCoreJenis['components'][$jenisTrTarget];
            }
            else {
                if (isset($sessionData[$cCode]["revert"]["jurnal"]) && sizeof($sessionData[$cCode]["revert"]["jurnal"]) > 0) {
                    $jurnalIndex = $sessionData[$cCode]["revert"]["jurnal"];
                }
                else {
                    $jurnalIndex = array();
                }
            }
            //---------------------------------------------------
            if (isset($this->configCoreJenis['postProcessor'][$jenisTrTarget]) && sizeof($this->configCoreJenis['postProcessor'][$jenisTrTarget])) {
                $jurnalPostProc = $this->configCoreJenis['postProcessor'][$jenisTrTarget];
            }
            else {
                if (isset($sessionData[$cCode]["revert"]["postProc"]) && sizeof($sessionData[$cCode]["revert"]["postProc"]) > 0) {
                    $jurnalPostProc = $sessionData[$cCode]["revert"]["postProc"];
                }
                else {
                    $jurnalPostProc = array();
                }
            }
            //---------------------------------------------------
            if (isset($this->configCoreJenis['preProcessor'][$jenisTrTarget]) && sizeof($this->configCoreJenis['preProcessor'][$jenisTrTarget])) {
                $jurnalPreProc = $this->configCoreJenis['preProcessor'][$jenisTrTarget];
            }
            else {
                if (isset($sessionData[$cCode]["revert"]["preProc"]) && sizeof($sessionData[$cCode]["revert"]["preProc"]) > 0) {
                    $jurnalPreProc = $sessionData[$cCode]["revert"]["preProc"];
                }
                else {
                    $jurnalPreProc = array();
                }
            }
            //---------------------------------------------------

            $baseRegistries = array(
                'main' => isset($sessionData[$cCode]['main']) ? $sessionData[$cCode]['main'] : array(),
                'items' => isset($sessionData[$cCode]['items']) ? $sessionData[$cCode]['items'] : array(),
                'items2' => isset($sessionData[$cCode]['items2']) ? $sessionData[$cCode]['items2'] : array(),
                'items2_sum' => isset($sessionData[$cCode]['items2_sum']) ? $sessionData[$cCode]['items2_sum'] : array(),
                'itemSrc' => isset($sessionData[$cCode]['itemSrc']) ? $sessionData[$cCode]['itemSrc'] : array(),
                'itemSrc_sum' => isset($sessionData[$cCode]['itemSrc_sum']) ? $sessionData[$cCode]['itemSrc_sum'] : array(),
                'items3' => isset($sessionData[$cCode]['items3']) ? $sessionData[$cCode]['items3'] : array(),
                'items3_sum' => isset($sessionData[$cCode]['items3_sum']) ? $sessionData[$cCode]['items3_sum'] : array(),
                'items4' => isset($sessionData[$cCode]['items4']) ? $sessionData[$cCode]['items4'] : array(),
                'items4_sum' => isset($sessionData[$cCode]['items4_sum']) ? $sessionData[$cCode]['items4_sum'] : array(),
                'items5_sum' => isset($sessionData[$cCode]['items5_sum']) ? $sessionData[$cCode]['items5_sum'] : array(),
                'items6_sum' => isset($sessionData[$cCode]['items6_sum']) ? $sessionData[$cCode]['items6_sum'] : array(),
                'items7_sum' => isset($sessionData[$cCode]['items7_sum']) ? $sessionData[$cCode]['items7_sum'] : array(),
                'items8_sum' => isset($sessionData[$cCode]['items8_sum']) ? $sessionData[$cCode]['items8_sum'] : array(),
                'items9_sum' => isset($sessionData[$cCode]['items9_sum']) ? $sessionData[$cCode]['items9_sum'] : array(),
                'items10_sum' => isset($sessionData[$cCode]['items10_sum']) ? $sessionData[$cCode]['items10_sum'] : array(),
                'rsltItems' => isset($sessionData[$cCode]['rsltItems']) ? $sessionData[$cCode]['rsltItems'] : array(),
                'rsltItems2' => isset($sessionData[$cCode]['rsltItems2']) ? $sessionData[$cCode]['rsltItems2'] : array(),
                'rsltItems3' => isset($sessionData[$cCode]['rsltItems3']) ? $sessionData[$cCode]['rsltItems3'] : array(),
                'tableIn_master' => isset($sessionData[$cCode]['tableIn_master']) ? $sessionData[$cCode]['tableIn_master'] : array(),
                'tableIn_detail' => isset($sessionData[$cCode]['tableIn_detail']) ? $sessionData[$cCode]['tableIn_detail'] : array(),
                'tableIn_detail2_sum' => isset($sessionData[$cCode]['tableIn_detail2_sum']) ? $sessionData[$cCode]['tableIn_detail2_sum'] : array(),
                'tableIn_detail_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_rsltItems'] : array(),
                'tableIn_detail_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                'tableIn_master_values' => isset($sessionData[$cCode]['tableIn_master_values']) ? $sessionData[$cCode]['tableIn_master_values'] : array(),
                'tableIn_detail_values' => isset($sessionData[$cCode]['tableIn_detail_values']) ? $sessionData[$cCode]['tableIn_detail_values'] : array(),
                'tableIn_detail_values_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                'tableIn_detail_values_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                'tableIn_detail_values2_sum' => isset($sessionData[$cCode]['tableIn_detail_values2_sum']) ? $sessionData[$cCode]['tableIn_detail_values2_sum'] : array(),
                'main_add_values' => isset($sessionData[$cCode]['main_add_values']) ? $sessionData[$cCode]['main_add_values'] : array(),
                'main_add_fields' => isset($sessionData[$cCode]['main_add_fields']) ? $sessionData[$cCode]['main_add_fields'] : array(),
                'main_elements' => isset($sessionData[$cCode]['main_elements']) ? $sessionData[$cCode]['main_elements'] : array(),
                'main_inputs' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                'main_inputs_orig' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                "receiptDetailFields" => isset($this->configLayoutJenis['receiptDetailFields'][1]) ? $this->configLayoutJenis['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->configLayoutJenis['receiptSumFields'][1]) ? $this->configLayoutJenis['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->configLayoutJenis['receiptDetailFields2'][1]) ? $this->configLayoutJenis['receiptDetailFields2'][1] : array(),
                "receiptDetailSrcFields" => isset($this->configLayoutJenis['receiptDetailSrcFields'][1]) ? $this->configLayoutJenis['receiptDetailSrcFields'][1] : array(),
                "receiptSumFields2" => isset($this->configLayoutJenis['receiptSumFields2'][1]) ? $this->configLayoutJenis['receiptSumFields2'][1] : array(),
                "jurnal_index" => $jurnalIndex,
                "postProcessor" => $jurnalPostProc,
                "preProcessor" => $jurnalPreProc,
                "revert" => isset($sessionData[$cCode]['revert']) ? $sessionData[$cCode]['revert'] : array(),
                "items_komposisi" => isset($sessionData[$cCode]['items_komposisi']) ? $sessionData[$cCode]['items_komposisi'] : array(),
                "items_noapprove" => isset($sessionData[$cCode]['items_noapprove']) ? $sessionData[$cCode]['items_noapprove'] : array(),
                "jurnalItems" => isset($sessionData[$cCode]['jurnalItems']) ? $sessionData[$cCode]['jurnalItems'] : array(),
                "componentsBuilder" => isset($sessionData[$cCode]['componentsBuilder']) ? $sessionData[$cCode]['componentsBuilder'] : array(),
            );
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            $mongRegID = $doWriteReg;
            cekHitam($this->CI->db->last_query());

            //========extended steps (if any)
            //region extended steps
            if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                foreach ($sessionData[$cCode]['main_inputs'] as $iKey => $iVal) {
                    if ($iVal > 0) {
                        cekbiru("evaluating $iKey ($iVal) for paymentSrc..");
                        $stepCode = $this->jenisTr . "_";
                        $paymentSources = $this->CI->config->item("payment_source");
                        if (array_key_exists($stepCode, $paymentSources)) {
                            $payConfigs = $paymentSources[$stepCode];
                            cekbiru("$stepCode registered");
                            //===kalau melibatkan payment-source
                            if (sizeof($payConfigs) > 0) {
                                foreach ($payConfigs as $paymentSrcConfig) {
                                    if ($paymentSrcConfig['valueSrc'] == $iKey) {
                                        cekhijau($paymentSrcConfig['valueSrc'] . "/$iKey akan dieksekusi");
                                        $valueSrc = $paymentSrcConfig['valueSrc'];
                                        $externSrc = $paymentSrcConfig['externSrc'];
                                        if ($tr->paymentSrcExistsInMaster($insertID, $stepCode, $paymentSrcConfig['label'])) {
                                            cekhijau($paymentSrcConfig['label'] . " pada $stepCode $insertID sudah ada, tidak perlu ditulis");
                                        }
                                        else {
                                            cekhijau($paymentSrcConfig['label'] . " pada $stepCode $insertID BELUM ada, ditulis sekarang");
                                            $tr->writePaymentSrc($insertID, array(
                                                "_key" => $iKey,
                                                "jenis" => $stepCode,
                                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                                "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                                "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                                "nomer" => $sessionData[$cCode]['main']['nomer'],
                                                "label" => $paymentSrcConfig['label'],
                                                "tagihan" => $sessionData[$cCode]['main_inputs'][$valueSrc],
                                                "terbayar" => 0,
                                                "sisa" => $sessionData[$cCode]['main_inputs'][$valueSrc],
                                                "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                                "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                                "oleh_id" => $transaksi_oleh_id,
                                                "oleh_nama" => $transaksi_oleh_nama,
                                                "dtime" => date("Y-m-d H:i:s"),
                                                "fulldate" => date("Y-m-d"),
                                                "valas_id" => isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                                "valas_nama" => isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                                "valas_nilai" => isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',
                                                "tagihan_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                                "terbayar_valas" => 0,
                                                "sisa_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',
                                            ));
                                        }
                                    }
                                    else {
                                        cekmerah($paymentSrcConfig['valueSrc'] . "/$iKey tidak untuk dieksekusi");
                                    }
                                }
                            }
                        }
                        else {
                            cekbiru("$stepCode NOT registered");
                        }

                        //==periksa apakah mainInput memerlukan auth
                        if (array_key_exists($iKey, $inputAuthConfigs)) {
                            $gID = $inputAuthConfigs[$iKey];
                            if (strlen($gID) > 0) {
                                cekhijau("input $iKey bernilai $iVal memerlukan auth dari $gID");
                                $trA = new MdlTransaksi();
                                if ($trA->extStepExistsInMaster($insertID, $iKey)) {
                                    cekhijau("extStep SUDAH terdaftar, sekarang nggak akan ditulis");
                                }
                                else {
                                    cekhijau("extStep belum terdaftar, sekarang hendak ditulis");
                                    $insertNew = $trA->writeExtStep($insertID, array(
                                        "master_id" => $insertID,
                                        "transaksi_id" => $insertID,
                                        "_key" => $iKey,
                                        "_label" => $inputLabels[$iKey],
                                        "_value" => $iVal,
                                        "group_id" => $gID,
                                        "state" => "0",
                                        "proposed_by" => $transaksi_oleh_id,
                                        "proposed_dtime" => date("Y-m-d H:i:s"),
                                        "done_by",
                                        "done_dtime",
                                    ));
                                    $mongoList['extras'][] = $insertNew;
                                    cekhijau($this->CI->db->last_query());
                                }
                            }
                        }
                    }
                }
            }
            //endregion


            //==================================================================================================
            //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
            // bila step lebih dari 1
            if ($nextProp['num'] > 1) {
                $this->CI->load->model("Mdls/MdlLockerTransaksi");
                $lt = New MdlLockerTransaksi();
                $lt->execLocker($sessionData[$cCode]['main'], $nextProp['num'], NULL, $insertID);
            }

            //==========================================================================================================

            $masterID = $insertID;

            //region writelog
            $this->CI->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $tmpHData = array(
                "title" => $sessionData[$cCode]['main']['jenisTrName'],
                "sub_title" => "Saving new transaction",
                "uid" => $transaksi_oleh_id,
                "uname" => $transaksi_oleh_nama,
                "dtime" => date("Y-m-d H:i:s"),
                "transaksi_id" => $insertID,
                "deskripsi_old" => "",
                "deskripsi_new" => base64_encode(serialize($sessionData[$cCode])),
                "jenis" => $this->jenisTr,
                "ipadd" => $_SERVER['REMOTE_ADDR'],
                "devices" => $_SERVER['HTTP_USER_AGENT'],
                "category" => "transaksi",
//                "controller" => $this->uri->segment(1),
//                "method" => $this->uri->segment(2),
                "controller" => "",
                "method" => "",
                "url" => current_url(),
            );
            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //endregion


//            cekKuning(":: mulai cek rek besar dan rek pembantu");
            $cabangID_validate = $transaksi_current_cabang_id;
//            validateAllBalances();


            return true;
        }
        else {
            mati_disini("Sesi anda habis/kadaluarsa. Silahkan refresh halaman ini. code: " . __LINE__);
        }
    }

    //------------------------------------
    public function autoPembatalan($jenisTr, $referenceTransaksiID, $kiriman)
    {
        $sessionData = array();
        $this->jenisTr = $jenisTr;
        $cCode = "_TR_" . $this->jenisTr;
        $this->cCode = $cCode;
        $id = $referenceTransaksiID;
        $stepNumber = 1;
        $configUiMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiUi");
        $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiCore");
        $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiLayout");
        $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($this->jenisTr, "coTransaksiValues");
        $selectorModel = $configUiMasterModulJenis['selectorModel'];
        $selectorSrcModel = $configUiMasterModulJenis['selectorSrcModel'];
        $ppnFactor = 11;


        $initMasterValues = array(
            "olehID" => $kiriman["olehID"],
            "olehName" => $kiriman["olehName"] . " by system",
            "sellerID" => $kiriman["sellerID"],
            "sellerName" => $kiriman["sellerName"],
            "placeID" => $kiriman["placeID"],
            "placeName" => $kiriman["placeName"],
            "divID" => $kiriman["divID"],
            "divName" => $kiriman["divName"],
            "cabangID" => $kiriman["cabangID"],
            "cabangName" => $kiriman["cabangName"],
            "gudangID" => $kiriman["gudangID"],
            "gudangName" => $kiriman["gudangName"],
            "jenis_usaha" => $kiriman["jenis_usaha"],
            "tokoID" => $kiriman["tokoID"],
            "tokoNama" => $kiriman["tokoNama"],
            "jenisTr" => $kiriman["jenisTr"],
            "jenisTrMaster" => $kiriman["jenisTrMaster"],
            "jenisTrTop" => $kiriman["jenisTrTop"],
            "jenisTrName" => $kiriman["jenisTrName"],
            "stepNumber" => $kiriman["stepNumber"],
            "stepCode" => $kiriman["stepCode"],
            "dtime" => dtimeNow(),
            "fulldate" => dtimeNow("Y-m-d"),

            "ppnFactor" => $kiriman["ppnFactor"],

            "pihakExternID" => $kiriman["pihakExternID"],
            "pihakExternMasterID" => $kiriman["pihakExternMasterID"],
            "pihakExternName" => $kiriman["pihakExternName"],
            "pihakExternValueSrc" => $kiriman["pihakExternValueSrc"],
            "pihakExternRevertStep" => $kiriman["pihakExternRevertStep"],
            "pihakExternDetailGate" => $kiriman["pihakExternDetailGate"],
            "pihakExternRevertRequest" => $kiriman["pihakExternRevertRequest"],
        );
        foreach ($initMasterValues as $ka => $va){
            $sessionData["main"][$ka] = $va;
        }

        $configCoreJenis = $configCoreMasterModulJenis;
        $configUiJenis = $configUiMasterModulJenis;
        $configValuesJenis = $configValuesMasterModulJenis;
        $modul_transaksi = $this->CI->config->item("heTransaksi_ui")[$this->jenisTr]["modul"];
        $tCodeTargetJenisTransaksi = $jenisTrTarget = isset($configUiMasterModulJenis["steps"][1]["target"]) ? $configUiMasterModulJenis["steps"][1]["target"] : NULL;
        $relOptionConfigs = isset($configUiMasterModulJenis['relativeOptions']) ? $configUiMasterModulJenis['relativeOptions'] : array();

        $this->CI->load->model("Mdls/" . $selectorSrcModel);
        $this->CI->load->model("MdlTransaksi");
        $trs = new MdlTransaksi();
        $b = new $selectorSrcModel();

        //  membaca isi nota
        $tmpB = $b->lookupByID($id)->result(); // ini membaca isi dari transaksi data
//        showLast_query("hitam");

        //  membaca registry shipment (582spd)
        $tmpRegistry = $trs->lookupDataRegistriesByMasterID($id)->result();
//        showLast_query("biru");


        $masterAddFields = array();
        $itemsFields = array();
        $items2Fields = array();
        $rsltItems = array();
        $rsltItems2 = array();
        $masterAddValues = array();
        $tmpMasterInValues = array();
        $tmpDetailValues = array();
        $postProcessor = array();
        $preProcessor = array();
        $revert = array();
        $component = array();
        $main_elements = array();
        if (sizeof($tmpRegistry) > 0) {
            foreach ($tmpRegistry as $row) {
                foreach ($row as $key_reg => $val_reg) {
                    switch ($key_reg) {
                        case "main":
                            $masterMainFields = unserialize(base64_decode($val_reg));
                            break;
                        case "master_add_fields":
                            $masterAddFields = unserialize(base64_decode($val_reg));
                            break;
                        case "master_add_values":
                            $masterAddValues = unserialize(base64_decode($val_reg));
                            break;
                        case "tableIn_detail_values":
                            $tmpDetailValues = unserialize(base64_decode($val_reg));
                            break;
                        case "tableIn_master_values":
                            $tmpMasterInValues = unserialize(base64_decode($val_reg));
                            break;
                        case "items":
                            $itemsFields = unserialize(base64_decode($val_reg));
                            break;
                        case "items2":
                            $items2Fields = unserialize(base64_decode($val_reg));
                            break;
                        case "items2_sum":
                            $items2_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items3":
                            $items3 = unserialize(base64_decode($val_reg));
                            break;
                        case "items3_sum":
                            $items3_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items4":
                            $items4 = unserialize(base64_decode($val_reg));
                            break;
                        case "items4_sum":
                            $items4_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items5_sum":
                            $items5_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items6":
                            $items6 = unserialize(base64_decode($val_reg));
                            break;
                        case "items6_sum":
                            $items6_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items7":
                            $items7 = unserialize(base64_decode($val_reg));
                            break;
                        case "items7_sum":
                            $items7_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items8_sum":
                            $items8_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items9_sum":
                            $items9_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "items10_sum":
                            $items10_sum = unserialize(base64_decode($val_reg));
                            break;
                        case "rsltItems":
                            $rsltItems = unserialize(base64_decode($val_reg));
                            break;
                        case "rsltItems2":
                            $rsltItems2 = unserialize(base64_decode($val_reg));
                            break;

                        case "preProcessor":
                            $preProcessor = isset($val_reg) ? unserialize(base64_decode($val_reg)) : array();
                            break;
                        case "postProcessor":
                            $postProcessor = isset($val_reg) ? unserialize(base64_decode($val_reg)) : array();
                            break;
                        case "jurnal_index":
                            $component = isset($val_reg) ? unserialize(base64_decode($val_reg)) : array();
                            break;
                        case "main_elements":
                            $main_elements = isset($val_reg) ? unserialize(base64_decode($val_reg)) : array();
                            break;
                    }
                }
            }
        }

        if (sizeof($tmpB) > 0) {
            $jenisMasterRef = $tmpB[0]->jenis_master;
            if (isset($fifoValidate[$jenisMasterRef])) {
                $mdlLoc = $fifoValidate[$jenisMasterRef]['mdlNameLoc'];
                $mdlName = $fifoValidate[$jenisMasterRef]['mdlName'];
                $mdlMethod = $fifoValidate[$jenisMasterRef]['method'];
                $label = isset($fifoValidate[$jenisMasterRef]['label']) ? $fifoValidate[$jenisMasterRef]['label'] : "";

                $this->CI->load->model("$mdlLoc/$mdlName");
                $mdd = New $mdlName();
                $resultTmp = array();
                if (method_exists($mdd, $mdlMethod)) {
                    $resultTmp = $mdd->$mdlMethod($transID_ref);
                    showLast_query("orange");
                }
                else {
                    cekHitam("TIDAK ADA method");
                }
                foreach ($itemsFields as $iSpec) {
                    $i_qty = $iSpec['qty'];
                    $i_nama = htmlspecialchars($iSpec['name']);
                    $f_qty = isset($resultTmp[$iSpec['id']]) ? $resultTmp[$iSpec['id']] : 0;
                    if ($f_qty != $i_qty) {
                        $msg = "Jumlah stok $i_nama tidak cukup/sudah digunakan. Pembatalan transaksi tidak bisa dilanjutkan. $label";
                        die(lgShowAlertBiru($msg));
                    }
                }
            }
            // ================== tambahan membaca activity
            $tr_no_dibatalkan = $tmpB[0]->nomer;
            $tr_jenis_dibatalkan = $tmpB[0]->jenis;
            $tr_cabang_dibatalkan = $tmpB[0]->cabang_id;
            $referenceNumberSO = $masterMainFields["referenceNumberSO"];
            $referenceNumberPrepack = $masterMainFields["referenceNumber__3"];
            $tr_id_dibatalkan = $tmpB[0]->transaksi_id;
            $tr_id_master_dibatalkan = $tmpB[0]->id_master;
            $status_grn = $tmpB[0]->status_grn;
            $jenis_master = $tmpB[0]->jenis_master;
            $jenisTr_reference = $tmpB[0]->jenis;
            $tr_dtime_dibatalkan = $tmpB[0]->dtime;
            $tr_fulldate_dibatalkan = $tmpB[0]->fulldate;
            $tr_idshis = blobDecode($tmpB[0]->ids_his);
            $tr_pembayaran = $tmpB[0]->pembayaran;
            $arrIdsHis = array();
            if (sizeof($tr_idshis) > 0) {
                foreach ($tr_idshis as $step_his => $data_his) {
                    $arrIdsHis['referenceID_dibatalkan__' . $step_his] = $data_his["trID"];
                    $arrIdsHis['referenceNumber_dibatalkan__' . $step_his] = $data_his["nomer"];
                    $arrIdsHis['referenceNomer_dibatalkan__' . $step_his] = $data_his["nomer"];
                    $arrIdsHis['referenceDtime_dibatalkan__' . $step_his] = $data_his["dtime"];
                    $arrIdsHis['referenceFulldate_dibatalkan__' . $step_his] = $data_his["fulldate"];
                }
            }

            $tmpB[0]->referensi_so__id = isset($masterMainFields["referensi_so__id"]) ? $masterMainFields["referensi_so__id"] : 0;

            $pembatalanValidateConfig = isset($this->CI->config->item('heTransaksi_pembatalanValidate')[$jenis_master]) ? $this->CI->config->item('heTransaksi_pembatalanValidate')[$jenis_master] : array();
            if (sizeof($pembatalanValidateConfig) > 0) {
                foreach ($pembatalanValidateConfig as $pembatalanSpec) {
                    $mdlNameValidate = $pembatalanSpec['mdlName'];
                    $mdlFilterValidate = isset($pembatalanSpec['mdlFilter']) ? $pembatalanSpec['mdlFilter'] : array();

                    $this->CI->load->model("Mdls/$mdlNameValidate");
                    $mdl_v = New $mdlNameValidate();
                    $mdl_v->setFilters(array());
                    if (sizeof($mdlFilterValidate) > 0) {
                        $rslt = makeFilter($mdlFilterValidate, (array)$tmpB[0], $mdl_v);
                    }
                    $validateTmp = $mdl_v->lookupAll()->result();
                    if (sizeof($validateTmp) > 0) {
                        if ($mdlNameValidate == "MdlPaymentSource") {
                            $this->CI->load->model("Mdls/MdlRevertJurnalCabang");
                            $this->CI->load->model("Mdls/MdlRevertJurnal");
                            $pp = new MdlRevertJurnal();
                            $pc = new MdlRevertJurnalCabang();
                            $dataTmpAliasPusat = $pp->lookUpAll()->result();
                            $dataTmpAliascabang = $pc->lookUpAll()->result();
                            $aliasData = array();
                            foreach ($dataTmpAliasPusat as $dataTmpAliasPusat_0) {
                                $aliasData[$dataTmpAliasPusat_0->id] = $dataTmpAliasPusat_0->nama;
                            }
                            foreach ($dataTmpAliascabang as $dataTmpAliascabang_0) {
                                $aliasData[$dataTmpAliascabang_0->id] = $dataTmpAliascabang_0->nama;
                            }

                            switch ($tr_jenis_dibatalkan) {
                                case "677":
                                case "675":
                                case "2677":
                                case "2675":
                                    $filter_produk_jenis = "expense";
                                    break;
                                default:
                                    $filter_produk_jenis = "invoice";
                                    break;
                            }

                            $produk_id = $validateTmp[0]->transaksi_id;
                            $trs->setFilters(array());
                            $trs->addFilter("transaksi_data.produk_id='$produk_id'");
                            $trs->addFilter("transaksi_data.produk_jenis='$filter_produk_jenis'");
                            $trs->addFilter("transaksi.link_id='0'");
                            $tempPayment = $trs->lookupJoined_OLD()->result();
                            if (sizeof($tempPayment) > 1) {
                                $arrPayment_no = array();
                                $arrPayment_alias = array();
                                foreach ($tempPayment as $ix => $tempSpec) {
                                    $arrPayment_no[$ix] = $tempSpec->nomer;
                                    $arrPayment_alias[$tempSpec->jenis] = $aliasData[$tempSpec->jenis];
                                }
                                $payment_no = implode(", ", $arrPayment_no);
                                $aliasDataTransaksi = implode(", ", $arrPayment_alias);
                            }
                            elseif (sizeof($tempPayment == 1)) {
                                $payment_no = $tempPayment[0]->nomer;
                                $payment_jenis = $tempPayment[0]->jenis;
                                $aliasDataTransaksi = $aliasData[$payment_jenis];
                            }
                            else {
                                $payment_no = "";
                                $payment_jenis = "";
                                $aliasDataTransaksi = "";
                            }
                            $cabang_id = $tempPayment[0]->cabang_id;
                            $title_cabang = $cabang_id < 0 ? "di DC/Pusat" : "di cabang";
                            $tr_cabang_dibatalkan2 = $cabang_id < 0 ? "di DC/Pusat" : "di cabang";
                            $payment_no_f = formatField_he_format("nomer_nolink", $payment_no);
                            $tr_no_dibatalkan_f = formatField_he_format("nomer_nolink", $tr_no_dibatalkan);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= $pembatalanSpec['label'] . "<br>";
                            $msg2 .= "<div class='text-bold text-red'>Nomer  " . $aliasDataTransaksi . " ($payment_no_f) </div>";
                            $msg2 .= "<div class='text-bold text-red'>Berhubung akan direject ($tr_no_dibatalkan_f) maka yang harus dilakukan:</div>";

                            //region untuk dinamis pake tempalte ini setiap row
                            $msg2 .= "<ol>";
                            $msg2 .= "<li>Batalkan " . $aliasData[$payment_jenis] . " Nomer <strong>$payment_no_f</strong><br> " . $title_cabang . " dari menu pembatalan transaksi.</li>";
                            $msg2 .= "<li>Batalkan " . $aliasData[$tr_jenis_dibatalkan] . " Nomer <strong>$tr_no_dibatalkan_f</strong>" . $tr_cabang_dibatalkan2 . " dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
                            $msg2 .= "</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
                            matiWhiteboard($sw_title, $msg2);

                        }
                        else {
                            $msg = $pembatalanSpec['label'];
                            die(lgShowAlertMerah($msg));
                            mati_disini(($msg));
                        }

                    }
                    if (isset($pembatalanSpec['detailCekQty']) && ($pembatalanSpec['detailCekQty'] == true)) {
                        $trs->setFilters(array());
                        $dTr = $trs->lookupDetailTransaksi($id);
                        $totalOrdJml = 0;
                        $totalValidQty = 0;
                        foreach ($dTr[$id] as $dTrSpec) {
                            $totalOrdJml += $dTrSpec->produk_ord_jml;
                            $totalValidQty += $dTrSpec->valid_qty;
                        }
                        if ($totalOrdJml != $totalValidQty) {
                            die(lgShowAlertMerah($pembatalanSpec['label']));
                            mati_disini($pembatalanSpec['label']);
                        }
                    }
                }

            }

            if (isset($pembatalanChecker[$jenisTr_reference]) && sizeof($pembatalanChecker[$jenisTr_reference]) > 0) {
                foreach ($pembatalanChecker[$jenisTr_reference] as $mode_cek => $cekSpec) {
                    switch ($mode_cek) {
                        case "serial":
                            $mdlNameLoc = $cekSpec["mdlNameLoc"];
                            $mdlName = $cekSpec["mdlName"];
                            $mdlFilter = $cekSpec["mdlFilter"];
                            if (isset($cekSpec["pairedModel"])) {
                                $pairedMdlNameLoc = $cekSpec["pairedModel"]["mdlNameLoc"];
                                $pairedMdlName = $cekSpec["pairedModel"]["mdlName"];
                                $pairedMdlFilter = $cekSpec["pairedModel"]["mdlFilter"];
                                $pairedMdlFilterIn = $cekSpec["pairedModel"]["mdlFilterIn"];
                                $mdlFilterInSrc = $cekSpec["pairedModel"]["mdlFilterInSrc"];
                            }
                            $mdlFilterInSrcData = array();
                            $mdlFilterInTargetData = array();
                            $mdlFilterInSrcDataProduk = array();
                            $mdlFilterInTargetDataProduk = array();
                            $arrResultDiffProduk = array();
                            $arrResultAllProduk = array();
                            $mdlResultAllProduk = array();

                            $this->CI->load->model("$mdlNameLoc/$mdlName");
                            $md = New $mdlName();
                            if (sizeof($mdlFilter) > 0) {
                                makeFilter($mdlFilter, $masterMainFields, $md);
                                arrPrint($mdlFilter);
                                arrPrint($masterMainFields);
                                $mdTmp = $md->lookupAll()->result();
                                showLast_query("hitam");
                                if (sizeof($mdTmp) > 0) {
                                    foreach ($mdTmp as $mdSpec) {
                                        $mdlFilterInSrcData[] = $mdSpec->$mdlFilterInSrc;
                                        $mdlFilterInSrcDataProduk[$mdSpec->produk_id][$mdSpec->produk_sku_part_nama][] = $mdSpec->$mdlFilterInSrc;
                                        $mdlResultAllProduk[$mdSpec->produk_id][$mdSpec->produk_sku_part_nama][] = $mdSpec;
                                    }
                                }

                                if (isset($cekSpec["pairedModel"])) {
                                    $this->CI->load->model("$pairedMdlNameLoc/$pairedMdlName");
                                    $mdp = New $pairedMdlName();
                                    if (sizeof($pairedMdlFilter) > 0) {
                                        if (sizeof($mdlFilterInSrcData) > 0) {
                                            $mdp->addFilter("$pairedMdlFilterIn in ('" . implode("','", $mdlFilterInSrcData) . "')");
                                        }
                                        makeFilter($pairedMdlFilter, $masterMainFields, $mdp);
                                        $mdpTmp = $mdp->lookupAll()->result();
//                                        showLast_query("biru");
//                                    cekHere(count($mdpTmp));
                                        if (sizeof($mdpTmp) > 0) {
                                            foreach ($mdpTmp as $mdpSpec) {
//                                                arrPrint($mdpSpec);
                                                $mdlFilterInTargetData[] = $mdpSpec->$pairedMdlFilterIn;
                                                $mdlFilterInTargetDataProduk[$mdpSpec->produk_id][$mdpSpec->extern2_nama][] = $mdpSpec->$pairedMdlFilterIn;
                                            }
                                        }
                                    }
                                }
                            }

                            $arrResultDiff = array_diff($mdlFilterInSrcData, $mdlFilterInTargetData);
                            foreach ($mdlFilterInSrcDataProduk as $pid => $pSpec) {
                                foreach ($pSpec as $psku => $skuSpec) {
                                    $avaliSerial = isset($mdlFilterInTargetDataProduk[$pid][$psku]) ? $mdlFilterInTargetDataProduk[$pid][$psku] : array();
                                    $avaliSerialDiff = array_diff($skuSpec, $avaliSerial);
                                    if (sizeof($avaliSerialDiff) > 0) {
                                        $arrResultDiffProduk[$pid][$psku] = $avaliSerialDiff;
                                    }
                                    $arrResultAllProduk[$pid][$psku] = $skuSpec;
                                }
                            }
                            break;
                        case "packinglist":
                            $arrRefMasterIDs = array();
                            $arrCekTrIDs = array();
                            if (isset($masterMainFields["referensi_order"]) && ($masterMainFields["referensi_order"] > 0)) {
                                $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                            }
                            else {
                                foreach ($itemsFields as $sspec) {
                                    if (isset($sspec["refID"]) && ($sspec["refID"] > 0)) {
                                        $arrCekTrIDs[] = $sspec["refID"];
                                    }
                                    else {
                                        $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                                    }
                                }
                            }
                            $this->CI->load->model("MdlTransaksi");
                            $trc = New MdlTransaksi();
                            $trc->addFilter("id in ('" . implode("','", $arrCekTrIDs) . "')");
                            $trcTmp = $trc->lookupAll()->result();
                            showLast_query("biru");
                            if (sizeof($trcTmp) > 0) {
                                foreach ($trcTmp as $specc) {
                                    $arrRefMasterIDs[] = $specc->id_master;
                                }

                                $trc = New MdlTransaksi();
                                $trc->addFilter("id_master in ('" . implode("','", $arrRefMasterIDs) . "')");
                                $trc->addFilter("trash_4='0'");
                                $this->CI->db->where_in('jenis', array("5822spd", "5823spd"));
                                $trcTmp = $trc->lookupAll()->result();
                                if (in_array("91399", $arrRefMasterIDs)) {
                                    //untuk lolosin transaki lama (mei 2024) yang kena sabotase
                                    if ($tr_id_dibatalkan == "91441") {
                                        $trcTmp = array();
                                    }

                                }

                                if (sizeof($trcTmp) > 0) {
                                    $arrdatas = array();
                                    foreach ($trcTmp as $speccc) {
                                        $arrdatas[] = $speccc->nomer;
                                        $customer_nama = $speccc->customers_nama;
                                    }
                                    $nomerss = implode(",", $arrdatas);
//                                    $msg = "Pembatalan tidak bisa dilanjutkan karena Sales Order sudah dikirim ke konsumen $customer_nama dengan nomer $nomerss. Silahkan diperiksa kembali. code: " . __LINE__;
//                                    mati_disini($msg);
                                    $sw_title = "Transaksi Tidak dapat dibatalkan";
                                    $msg2 = "<div class='text-left'>";
                                    $msg2 .= "Pembatalan tidak bisa dilanjutkan karena Sales Order sudah dikirim ke konsumen $customer_nama, nomer kirim: $nomerss <br><br>";
                                    $msg2 .= "<div class='text-bold text-red'>sehingga transaksi  ($tr_no_dibatalkan) tidak dapat dibatalkan</div>";

                                    //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                                    //endregion untuk dinamis pake tempalte ini setiap row

                                    $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                                    matiWhiteboard($sw_title, $msg2);
                                }
                            }

                            break;
                        case "salesorder":
                            $arrRefMasterIDs = array();
                            $arrCekTrIDs = array();
                            if (isset($masterMainFields["referensi_order"]) && ($masterMainFields["referensi_order"] > 0)) {
                                $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                            }
                            else {
                                foreach ($itemsFields as $sspec) {
                                    if (isset($sspec["refID"]) && ($sspec["refID"] > 0)) {
                                        $arrCekTrIDs[] = $sspec["refID"];
                                    }
                                    else {
                                        $arrCekTrIDs[0] = $masterMainFields["referensi_order"];
                                    }
                                }
                            }
                            $this->CI->load->model("MdlTransaksi");
                            $trc = New MdlTransaksi();
                            $trc->addFilter("id in ('" . implode("','", $arrCekTrIDs) . "')");
                            $trc->addFilter("trash_4='1'");
                            $trcTmp = $trc->lookupAll()->result();
                            if (sizeof($trcTmp) > 0) {
                                $arrdataslunas = array();
                                foreach ($trcTmp as $speccc) {
//                                    arrPrint($speccc);
                                    $arrdataslunas[$speccc->id] = $speccc->nomer;
                                    $customer_nama = $speccc->customers_nama;
                                    $cancel_nama = $speccc->cancel_name;
                                    $trid = $speccc->id;
                                    //------
                                    $jenis = "4464";// penerimaan penjualan tunai
                                    $trcc = New MdlTransaksi();
                                    $trcc->setFilters(array());
                                    $trcc->addFilter("transaksi_id='$trid'");
//                                    $trcc->addFilter("target_jenis='4464'");
                                    $trccTmp = $trcc->lookupPaymentSrcByJenis($jenis)->result();
                                    showLast_query("biru");
                                    cekHere(count($trccTmp));
                                    $total_terbayar_lunas = 0;
                                    if (sizeof($trccTmp) > 0) {
                                        foreach ($trccTmp as $trccSpec) {
//                                            arrPrintWebs($trccSpec);
                                            $total_terbayar_lunas += $trccSpec->terbayar;// jumlah yang sudah dibayar, masing-masing nota
                                        }
                                    }
                                }
//                                $nomerss = implode(",", $arrdataslunas);
//                                $msg = "Pembatalan tidak bisa dilanjutkan karena Sales Order nomer $nomerss konsumen $customer_nama sudah di REJECT/CANCEL oleh $cancel_nama. Silahkan diperiksa kembali. code: " . __LINE__;
//                                mati_disini($msg);

                            }
                            break;
                    }
                }
            }

            switch ($jenis_master) {
                case "7499":
                case "5822":
                case "5823":
                case "5822spd":
                case "5823spd":
                    $jenis_master_return = array(
                        array("next_step_code" => "9822r"),
                        array("next_step_code" => "9822g"),
                        array("next_step_code" => "9822"),
                    );
                    // region cek input faktur PPN KELUARAN
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("reference_id='$transID_ref'");
                    $t->addFilter("link_id='0'");
                    $t->addFilter("trash_4='0'");
                    $t->addFilter("jenis in ('110e', '110')");
//                $t->addFilter("next_substep_code<>''");
//                $t->addFilter("sub_step_number>0");
                    $t->addFilter("transaksi_data.valid_qty>'0'");
//                    $tTmp = $t->lookUpAll()->result();
                    $tTmp = $t->lookupJoined_OLD()->result();
                    showLast_query("kuning");
                    cekKuning(count($tTmp));
                    if (sizeof($tTmp) > 0) {
                        $next_step = $tTmp[0]->next_step_num;
//                        if ($next_step > 2) {
                        $pic_nama_implode = callNextPICName($jenis_master_return);
//                            arrPrintCyan($arrNextPIC);
//                            $pic_nama = array();
//                            if(sizeof($arrNextPIC)>0){
//                                foreach ($arrNextPIC as $nSpec){
//                                    foreach ($nSpec as $nnSpec){
//                                        foreach($nnSpec as $nnnSpec){
//                                            $pic_nama[$nnnSpec["nama"]] = $nnnSpec["nama"];
//                                        }
//                                    }
//                                }
//                            }
//                            $pic_nama_implode = (sizeof($pic_nama)>0) ? "PIC retur penjualan: " . implode(", ", $pic_nama) : "";
                        $nomer_alias = $tTmp[0]->nomer2;
//                            $msg = "Transaksi Penjualan Nomer <strong>$referenceNumberSO</strong>  sudah dikirim ke konsumen, nomer kirim: 5822spd.1.2601.2 dan sudah dibuat faktur ppn keluaran dengan otorisasi . ";
//                            $msg .= "Bila akan dibatalkan dimulai dari Finance (batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong> di DC/Pusat) <br>";
//                            die(lgShowAlertMerah($msg));
//
//                            mati_disini($msg);
                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        if ($jenis_master != "7499") {
                            $msg2 .= "Transaksi Penjualan Nomer <strong>$referenceNumberSO</strong> sudah dikirim ke konsumen, nomer kirim: $tr_no_dibatalkan dan sudah dibuat faktur ppn keluaran dengan otorisasi <strong>$nomer_alias</strong> <br><br>";
                            $msg2 .= "<div class='text-bold text-red'>jika faktur sudah dientry, maka hanya dapat melakukan retur penjualan (lihat tutorial return penjualan)</div>";
                            $msg2 .= "<br><strong>PIC retur penjualan: $pic_nama_implode</strong>";
                        }
                        else {
                            $tr_no_dibatalkan_f = formatField_he_format("nomer_nolink", $tr_no_dibatalkan);
                            $nomer_alias_f = formatField_he_format("nomer_nolink", $nomer_alias);
                            $jenis_master_return = array(
                                array("next_step_code" => "9911"),
                            );
                            $pic_nama_implode = callNextPICName($jenis_master_return);
                            $msg2 .= "Transaksi Penerbitan Termin Nomer <strong>$referenceNumberSO</strong>, sudah dibuat faktur ppn keluaran dengan otorisasi <strong>$nomer_alias</strong> <br><br>";
                            $msg2 .= "<div class='text-bold text-red'>Jika perlu pembatalan ($tr_no_dibatalkan_f) maka yang harus dilakukan:</div>";
                            $msg2 .= "<ol>";
                            $msg2 .= "<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias_f</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
                            $msg2 .= "<br><strong>PIC pembatalan di DC/Pusat: $pic_nama_implode</strong>";
                            $msg2 .= "</ol>";
                        }

                        cekMerah($msg2);

                        //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .= "<ol>";
//                            $msg2 .= "<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .= "<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .= "<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                        matiWhiteboard($sw_title, $msg2);
//                        }
                    }
                    // endregion cek input faktur PPN KELUARAN

                    // region cek detail return penjualan items
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $tTmp = $t->lookupDetailTransaksiNoJenis($tr_id_dibatalkan)->result();
//                    showLast_query("biru");
                    if (sizeof($tTmp) > 0) {
                        $arrDataReturn = array();
                        foreach ($tTmp as $ii => $tSpec) {
                            if ($tSpec->produk_ord_jml_return > 0) {
                                $arrDataReturn[$ii] = $tSpec->produk_ord_jml_return;
                            }
                        }
                        if (sizeof($arrDataReturn) > 0) {
                            $nomer_so_dibatalkan = $masterMainFields["referenceNumberSO"];

//                            $msg = "Penjualan ini $tr_no_dibatalkan telah diretur, sehingga transaksi apapun atas SO Nomer $nomer_so_dibatalkan tidak bisa dilakukan.";
//                            mati_disini($msg);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= "Transaksi Penjualan Nomer <strong>$referenceNumberSO</strong> sudah dikirim ke konsumen, nomer kirim: $tr_no_dibatalkan dan sudah dilakukan return  <br><br>";
                            $msg2 .= "<div class='text-bold text-red'>sehingga transaksi  ($tr_no_dibatalkan) tidak dapat dibatalkan</div>";

                            //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
//                            matiHere(__LINE__);
//                            die(lgShowAlertMerah($msg));
                            matiWhiteboard($sw_title, $msg2);
                        }
                    }
                    // endregion cek detail return penjualan items

                    // region cek 4464, jika SO tunai
                    if ($tr_pembayaran == "cash") {
                        $trr = New MdlTransaksi();
                        $trr->addFilter("jenis='4464'");
                        $trr->addFilter("trash_4='0'");
                        $trr->addFilterJoin("produk_id=" . $arrIdsHis["referenceID_dibatalkan__2"]);
                        $trrTmp = $trr->lookupJoined();
//                        showLast_query("biru");
//                        arrPrintWebs($trrTmp);
                        $arrReferencePenjualanTunai = array();
                        if (sizeof($trrTmp) > 0) {
                            $arrReferencePenjualanTunai["referenceID_penjualan_tunai"] = $trrTmp[0]->transaksi_id;
                            $arrReferencePenjualanTunai["referenceNomer_penjualan_tunai"] = $trrTmp[0]->nomer;
                            $arrReferencePenjualanTunai["referenceNumber_penjualan_tunai"] = $trrTmp[0]->nomer;
                        }
                    }
                    // endregion cek 4464, jika SO tunai


                    break;
                case "466":
                    $lockerAvail = array();
                    $arrPIDs_items = array_keys($itemsFields);
                    $this->CI->load->model("Mdls/MdlLockerStock");
                    $ls = New MdlLockerStock();
                    $ls->addFilter("jenis='produk'");
                    $ls->addFilter("state='active'");
                    $ls->addFilter("cabang_id=" . $masterMainFields["placeID"]);
                    $ls->addFilter("gudang_id=" . $masterMainFields["gudangID"]);
                    $ls->addFilter("produk_id in ('" . implode("','", $arrPIDs_items) . "')");
                    $lsTmp = $ls->lookupAll()->result();
                    showLast_query("biru");
                    if (sizeof($lsTmp) > 0) {
                        foreach ($lsTmp as $lsSpec) {
                            $lockerAvail[$lsSpec->produk_id] = array(
                                "last_stock_avail" => $lsSpec->jumlah,
                            );
                        }
                        foreach ($lockerAvail as $ls_id => $ls_spec) {
                            if (isset($itemsFields[$ls_id])) {
                                foreach ($ls_spec as $ls_key => $ls_val) {
                                    $itemsFields[$ls_id][$ls_key] = $ls_val;
                                }
                            }
                        }
                    }
                    $sessionData['main']['showLastStock'] = true;

                    // region cek detail return pembelian items
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $tTmp = $t->lookupDetailTransaksiNoJenis($tr_id_dibatalkan)->result();
                    showLast_query("biru");
                    if (sizeof($tTmp) > 0) {
                        $arrDataReturn = array();
                        foreach ($tTmp as $ii => $tSpec) {
                            if ($tSpec->produk_ord_jml_return > 0) {
                                $arrDataReturn[$ii] = $tSpec->produk_ord_jml_return;
                            }
                        }
                        if (sizeof($arrDataReturn) > 0) {
//                            $msg = "Transaksi gagal dibatalkan karena sudah dilakukan return pembelian fg reguler.";
//                            mati_disini($msg);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= "Karena sudah dilakukan return pembelian fg reguler. <br><br>";
//                            $msg2 .= "<div class='text-bold text-red'>berhubung akan direject ($tr_no_dibatalkan) maka yang harus dilakukan:</div>";

                            //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                            matiWhiteboard($sw_title, $msg2);

                        }
                    }
                    // endregion cek detail return pembelian items
                    break;
                case "1466":
                    $lockerAvail = array();
                    $arrPIDs_items = array_keys($itemsFields);
                    $this->CI->load->model("Mdls/MdlLockerStock");
                    $ls = New MdlLockerStock();
                    $ls->addFilter("jenis='produk'");
                    $ls->addFilter("state='active'");
                    $ls->addFilter("cabang_id=" . $masterMainFields["placeID"]);
                    $ls->addFilter("gudang_id=" . $masterMainFields["gudangProjectID"]);
                    $ls->addFilter("produk_id in ('" . implode("','", $arrPIDs_items) . "')");
                    $lsTmp = $ls->lookupAll()->result();
                    showLast_query("biru");
                    if (sizeof($lsTmp) > 0) {
                        foreach ($lsTmp as $lsSpec) {
                            $lockerAvail[$lsSpec->produk_id] = array(
                                "last_stock_avail" => $lsSpec->jumlah,
                            );
                        }
                        foreach ($lockerAvail as $ls_id => $ls_spec) {
                            if (isset($itemsFields[$ls_id])) {
                                foreach ($ls_spec as $ls_key => $ls_val) {
                                    $itemsFields[$ls_id][$ls_key] = $ls_val;
                                }
                            }
                        }
                    }
                    $sessionData['main']['showLastStock'] = true;

                    // region cek detail return pembelian items
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $tTmp = $t->lookupDetailTransaksiNoJenis($tr_id_dibatalkan)->result();
                    showLast_query("biru");
                    if (sizeof($tTmp) > 0) {
                        $arrDataReturn = array();
                        foreach ($tTmp as $ii => $tSpec) {
                            if ($tSpec->produk_ord_jml_return > 0) {
                                $arrDataReturn[$ii] = $tSpec->produk_ord_jml_return;
                            }
                        }
                        if (sizeof($arrDataReturn) > 0) {
//                            $msg = "Transaksi gagal dibatalkan karena sudah dilakukan return pembelian fg project.";
//                            mati_disini($msg);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= "Karena sudah dilakukan return pembelian fg project. <br><br>";
//                            $msg2 .= "<div class='text-bold text-red'>berhubung akan direject ($tr_no_dibatalkan) maka yang harus dilakukan:</div>";

                            //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                            matiWhiteboard($sw_title, $msg2);
                        }
                    }
                    // endregion cek detail return pembelian items
                    break;
                case "461":
                    // region cek detail return pembelian items
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $tTmp = $t->lookupDetailTransaksiNoJenis($tr_id_dibatalkan)->result();
                    showLast_query("biru");
                    if (sizeof($tTmp) > 0) {
                        $arrDataReturn = array();
                        foreach ($tTmp as $ii => $tSpec) {
                            if ($tSpec->produk_ord_jml_return > 0) {
                                $arrDataReturn[$ii] = $tSpec->produk_ord_jml_return;
                            }
                        }
                        if (sizeof($arrDataReturn) > 0) {
//                            $msg = "Transaksi gagal dibatalkan karena sudah dilakukan return pembelian supplies.";
//                            mati_disini($msg);
                            $sw_title = "Transaksi Tidak dapat dibatalkan";
                            $msg2 = "<div class='text-left'>";
                            $msg2 .= "Karena sudah dilakukan return pembelian pembelian supplies. <br><br>";
//                            $msg2 .= "<div class='text-bold text-red'>berhubung akan direject ($tr_no_dibatalkan) maka yang harus dilakukan:</div>";

                            //region untuk dinamis pake tempalte ini setiap row
//                            $msg2 .="<ol>";
//                            $msg2 .="<li>Batalkan Otorisasi Faktur PPN Keluaran Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Batalkan Packinglist Nomer <strong>$tr_no_dibatalkan</strong> di cabang dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
//                            $msg2 .="</ol>";
                            //endregion untuk dinamis pake tempalte ini setiap row

                            $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                            matiWhiteboard($sw_title, $msg2);
                        }
                    }
                    // endregion cek detail return pembelian items
                    break;
                case "3463":
                    // deteksi tgl SRN Service project, bila sebelum 30 nov 2024 maka ppn dan hutang dagang dikurangi sebesar ppn.
                    // 30 nov 2024 dan selanjutnya maka apa adanya.
//                    arrPrintCyan($masterMainFields);
                    if ($tr_fulldate_dibatalkan < "2024-11-30") {
                        $masterMainFields["nilai_tambah_piutang_pembelian"] = $masterMainFields["harga_disc"];
                        $masterMainFields["nilai_tambah_ppn_in"] = 0;

                        $tmpMasterInValues["nilai_tambah_piutang_pembelian"] = $tmpMasterInValues["harga_disc"];
                        $tmpMasterInValues["nilai_tambah_ppn_in"] = 0;
                    }
                    break;
                case "489":
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("id_master='$tr_id_dibatalkan'");
                    $t->addFilter("jenis='111'");
                    $t->addFilter("trash_4='0'");
                    $tTmp = $t->lookupAll()->result();
                    if (sizeof($tTmp) > 0) {
                        $nomer = $tTmp[0]->nomer;
                        $nomer_alias = $tTmp[0]->nomer2;
//                        $msg = "Transaksi Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput. ";
//                        $msg .= "Bila akan dibatalkan dimulai dari Finance (batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> di DC/Pusat). ";
//                        mati_disini($msg);

                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        $msg2 .= "Transaksi Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput.  <br><br>";
                        $msg2 .= "<div class='text-bold text-red'Bila akan dibatalkan maka yang harus dilakukan:</div>";

                        //region untuk dinamis pake tempalte ini setiap row
                        $msg2 .= "<ol>";
                        $msg2 .= "<li>Batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi</li>";
                        $msg2 .= "<li>Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> di DC/Pusat dari menu pembatalan transaksi.</li>";
//                            $msg2 .="<li>Reject Prepackinglist Nomer <strong>$referenceNumberPrepack</strong>  dari menu sales.</li>";
                        $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
                        matiWhiteboard($sw_title, $msg2);
                    }
                    break;
                case "487":
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("id_master='$tr_id_dibatalkan'");
                    $t->addFilter("jenis='111'");
                    $t->addFilter("trash_4='0'");
                    $tTmp = $t->lookupAll()->result();
                    if (sizeof($tTmp) > 0) {
                        $nomer = $tTmp[0]->nomer;
                        $nomer_alias = $tTmp[0]->nomer2;
                        $msg = "Transaksi Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput. ";
                        $msg .= "Bila akan dibatalkan dimulai dari Finance (batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> di DC/Pusat). ";
                        mati_disini($msg);
                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        $msg2 .= "Karena Nomer Faktur PPN Masukan sudah diinput <br><br>";
                        $msg2 .= "<div class='text-bold text-red'Bila akan dibatalkan maka yang harus dilakukan:</div>";

                        //region untuk dinamis pake tempalte ini setiap row
                        $msg2 .= "<ol>";
                        $msg2 .= "<li>Batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi</li>";
                        $msg2 .= "<li>Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> di DC/Pusat dari menu pembatalan transaksi.</li>";

                        $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                        matiWhiteboard($sw_title, $msg2);
                    }
                    break;
                case "462":
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("id_master='$tr_id_dibatalkan'");
                    $t->addFilter("jenis='111'");
                    $t->addFilter("trash_4='0'");
                    $tTmp = $t->lookupAll()->result();
                    if (sizeof($tTmp) > 0) {
                        $nomer = $tTmp[0]->nomer;
                        $nomer_alias = $tTmp[0]->nomer2;
//                        $msg = "Transaksi Pembayaran Hutang Biaya Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput. ";
//                        $msg .= "Bila akan dibatalkan dimulai dari Finance (batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> di DC/Pusat). ";
//                        mati_disini($msg);
                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        $msg2 .= "Karena Nomer Faktur PPN Masukan sudah diinput <br><br>";
                        $msg2 .= "<div class='text-bold text-red'Bila akan dibatalkan maka yang harus dilakukan:</div>";

                        //region untuk dinamis pake tempalte ini setiap row
                        $msg2 .= "<ol>";
                        $msg2 .= "<li>Batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong><br> di DC/Pusat dari menu pembatalan transaksi</li>";
                        $msg2 .= "<li>Pembayaran Hutang Usaha Nomer <strong>$tr_no_dibatalkan</strong> di DC/Pusat dari menu pembatalan transaksi.</li>";

                        $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                        matiWhiteboard($sw_title, $msg2);
                    }
                    break;
                case "483":
                    $t = new MdlTransaksi();
                    $t->setfilters(array());
                    $t->addFilter("id_master='$tr_id_dibatalkan'");
                    $t->addFilter("jenis='111'");
                    $t->addFilter("trash_4='0'");
                    $tTmp = $t->lookupAll()->result();
                    if (sizeof($tTmp) > 0) {
                        $nomer = $tTmp[0]->nomer;
                        $nomer_alias = $tTmp[0]->nomer2;
//                        $msg = "Transaksi Pembayaran Hutang Biaya Nomer <strong>$tr_no_dibatalkan</strong> tidak bisa dibatalkan karena Nomer Faktur PPN Masukan sudah diinput. ";
//                        $msg .= "Bila akan dibatalkan dimulai dari Finance (batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> di DC/Pusat). ";
//                        mati_disini($msg);
                        $sw_title = "Transaksi Tidak dapat dibatalkan";
                        $msg2 = "<div class='text-left'>";
                        $msg2 .= "Karena Nomer Faktur PPN Masukan sudah diinput <br><br>";
                        $msg2 .= "<div class='text-bold text-red'Bila akan dibatalkan maka yang harus dilakukan:</div>";

                        //region untuk dinamis pake tempalte ini setiap row
                        $msg2 .= "<ol>";
                        $msg2 .= "<li>Batalkan Realisasi PPN Masukan Nomer <strong>$nomer_alias</strong> <br> di DC/Pusat dari menu pembatalan transaksi</li>";
                        $msg2 .= "<li>Pembayaran Hutang Biaya Nomer <strong>$tr_no_dibatalkan</strong> di DC/Pusat dari menu pembatalan transaksi.</li>";

                        $msg2 .= "</ol>";
                        //endregion untuk dinamis pake tempalte ini setiap row

                        $msg2 .= "</div>";
//                            die(lgShowAlertMerah($msg));
                        matiWhiteboard($sw_title, $msg2);
                    }
                    break;
                case "4464":

                    break;
            }

            if (sizeof($arrIdsHis) > 0) {
                foreach ($arrIdsHis as $key_his => $val_his) {
                    $sessionData['main'][$key_his] = $val_his;
                }
            }
            if (isset($arrReferencePenjualanTunai) && sizeof($arrReferencePenjualanTunai) > 0) {
                foreach ($arrReferencePenjualanTunai as $key_his => $val_his) {
                    $sessionData['main'][$key_his] = $val_his;
                }
            }

            $sessionData['main']['seluruhnya'] = true;
            $sessionData['main']['referenceID'] = $id;
            $sessionData['main']['referenceNomer'] = $masterMainFields['nomer'];
            $sessionData['main']['referenceNomer_top'] = $tmpB[0]->nomer_top;
            $sessionData['main']['jenisTr_reference'] = $tmpB[0]->jenis;
            $sessionData['main']['referenceID_master'] = $tr_id_master_dibatalkan;
            $sessionData['main']['referenceStepNumber'] = $tmpB[0]->step_number;
            $sessionData['main']['referenceID_top'] = $tmpB[0]->id_top;
            $sessionData['main']['reference_dtime'] = $tmpB[0]->dtime;
            $sessionData['main']['reference_fulldate'] = $tmpB[0]->fulldate;
            $sessionData['main']['reference_pembayaran'] = $tr_pembayaran;


            if (isset($arrResultDiff) && (sizeof($arrResultDiff) > 0)) {
                $sessionData['extracted_serial_diff']['all_serial'] = isset($mdlFilterInSrcData) ? $mdlFilterInSrcData : array();
                $sessionData['extracted_serial_diff']['avail_serial'] = isset($mdlFilterInTargetData) ? $mdlFilterInTargetData : array();
                $sessionData['extracted_serial_diff']['diff_serial'] = isset($arrResultDiff) ? $arrResultDiff : array();
                $sessionData['extracted_serial_diff']['diff_serial_produk'] = isset($arrResultDiffProduk) ? $arrResultDiffProduk : array();
                $sessionData['extracted_serial_diff']['all_serial_produk'] = isset($arrResultAllProduk) ? $arrResultAllProduk : array();
            }
            else {
                $sessionData['extracted_serial_diff']['all_serial'] = isset($mdlFilterInSrcData) ? $mdlFilterInSrcData : array();
                $sessionData['extracted_serial_diff']['avail_serial'] = isset($mdlFilterInTargetData) ? $mdlFilterInTargetData : array();
                $sessionData['extracted_serial_diff']['diff_serial'] = isset($arrResultDiff) ? $arrResultDiff : array();
                $sessionData['extracted_serial_diff']['diff_serial_produk'] = isset($arrResultDiffProduk) ? $arrResultDiffProduk : array();
                $sessionData['extracted_serial_diff']['all_serial_produk'] = isset($arrResultAllProduk) ? $arrResultAllProduk : array();
            }

            foreach ($itemsFields as $row) {
                $rows = (object)$row;
                $id = $rows->id;
                $name = $rows->nama;
                $tmpJml = $rows->qty;
                $tmpJmlReturn = isset($rows->produk_ord_jml_return) ? $rows->produk_ord_jml_return : 0;

                $tmpJml_avail = $tmpJml - $tmpJmlReturn;
                $tmpDisabled = "0";
                if ($tmpJml_avail <= 0) {
                    $tmpJml = 0;
                    $tmpDisabled = "1";
                }
                else {
                    $tmpJml = $tmpJml_avail;
                    $tmpDisabled = "0";
                }
                if ($tmpJml > 0) {

                    $fieldSrcs = isset($configUiMasterModulJenis['shoppingCartFieldSrc']) ? $configUiMasterModulJenis['shoppingCartFieldSrc'] : array("nama" => "nama");
                    if (!isset($sessionData['items']) || (!array_key_exists($id, $sessionData['items']))) {
                        $tmp = array(
                            "handler" => "",
                            "id" => $id,
                            "name" => $name,
                            "nama" => $name,
                            "jml" => $tmpJml,
                            "harga" => 0,
                            "subtotal" => 0,
                            "disabled" => $tmpDisabled,
                        );

                        //region injector ke items, detail isi nota
                        if (isset($tmpDetailValues[$id]) && sizeof($tmpDetailValues[$id]) > 0) {
                            $arrDiff = array_diff_key($tmpDetailValues[$id], array_flip($this->blackList));
                            if (array_key_exists("sisa", $arrDiff)) {
                            }
                            else {
                                foreach ($fieldSrcs as $keySrc => $srcFields) {
                                    $arrDiff[$keySrc] = $arrDiff["harga"];
                                }
                            }
                            foreach ($arrDiff as $key => $val) {
                                $tmp[$key] = $val;
                            }
                        }
                        //endregion

                        if (isset($itemsInjectorConfig) && sizeof($itemsInjectorConfig) && $itemsInjectorConfig['enabled'] == true) {
                            foreach ($itemsInjectorConfig['kolom'] as $target => $source) {
                                $tmp[$target] = isset($itemsFields[$id][$source]) ? $itemsFields[$id][$source] : "";
                            }
                        }

                        foreach ($fieldSrcs as $key2 => $src2) {
                            $tmp[$key2] = makeValue($src2, $tmp, $tmp, $rows->$src2);
                        }

                        //===perhitungan subtotal
                        $cal = new FieldCalculator();
                        if ($subAmountConfig != null) {
//                            $subtotal = makeValue($subAmountConfig, $tmp, $sessionData['items'][$id], 0);
                            $subtotal = makeValue($subAmountConfig, $tmp, $tmp, 0);
                        }
                        else {
                            $subtotal = 0;
                            cekHijau("subtotal NOL");
                        }

                        $tmp["subtotal"] = $subtotal;

                        $sessionData['items'][$id] = $tmp;

                    }
                    else {
                        cekmerah("sudah ada di items, mau update subtotal");
                        if ($subAmountConfig != null) {
                            $subtotal = makeValue($subAmountConfig, $sessionData['items'][$id], $sessionData['items'][$id], 0);
                        }
                        else {
                            $subtotal = 0;
                            cekHijau("subtotal NOL");
                        }
                    }
                    if (isset($referenceConfig) && sizeof($referenceConfig) > 0) {
                        foreach ($referenceConfig as $key => $label) {
                            $sessionData['main'][$key] = $rows->$label;
                        }
                    }
                }
            }

            if (sizeof($itemsFields) > 0) {
                foreach ($itemsFields as $key => $val) {
                    $setVal = $sessionData["items"][$key];
                    foreach ($val as $keys => $val0) {
                        if (!isset($setVal[$keys])) {
                            $sessionData["items"][$key][$keys] = $val0;
                        }
                    }
                }
            }

            if (sizeof($masterAddFields) > 0) {
                foreach ($masterAddFields as $key => $value) {
                    $sessionData['main_add_fields'][$key] = $value;
                    $sessionData['main'][$key] = $value;

                }
            }
            if (sizeof($masterAddValues) > 0) {
                foreach ($masterAddValues as $key => $value) {
                    $sessionData['main_add_values'][$key] = $value;
                    $sessionData['main'][$key] = $value;

                }
            }
            if (sizeof($tmpMasterInValues) > 0) {
                foreach ($tmpMasterInValues as $key => $value) {
                    if (!isset($sessionData['main'][$key]) || ($sessionData['main'][$key] == NULL)) {
                        $sessionData['main'][$key] = $value;
                    }
                }
            }
            if (sizeof($masterMainFields) > 0) {
                foreach ($masterMainFields as $key => $value) {
                    if (!isset($sessionData['main'][$key])) {
                        switch ($tmpB[0]->jenis) {
                            case "999":
                                if (in_array($key, $arrAdjustmentRekening)) {
                                    cekUngu("::: $key => $value :::");
                                    if (is_numeric($value)) {
                                        $sessionData['main'][$key] = $value;
                                    }
                                    else {
                                        $new_value = isset($arrRekening_coa[$value]) ? $arrRekening_coa[$value] : NULL;
                                        $sessionData['main'][$key] = $new_value;
                                    }
                                }
                                else {
                                    $sessionData['main'][$key] = $value;
                                }
                                break;
                            default:
                                $sessionData['main'][$key] = $value;
                                // ada gerbang baru kas netto di penerimaan penjualan tunai. dipakai saat auto setor kas ke pusat.
                                // auto setor tidak masuk ke registry jurnal, jadi pakai config aktual.
                                // bila tidak ada kas_netto maka diberi nilai_entry (untuk transaksi lama).
                                if (!isset($sessionData['main']['kas_netto'])) {
                                    $sessionData['main']['kas_netto'] = $sessionData['main']['nilai_entry'];
                                }
                                break;
                        }
//                        $sessionData['main'][$key] = $value;
                    }

                }
            }

            if (sizeof($sessionData['items']) > 0) {
                $sessionData['main']['harga'] = 0;
                foreach ($sessionData['items'] as $id => $iSpec) {
                    $sessionData['main']['harga'] += ($iSpec['jml'] * $iSpec['harga']);

                }
            }
            if (sizeof($items2Fields) > 0) {
                foreach ($items2Fields as $key => $value) {
                    $sessionData['items2'][$key] = $value;
                }
            }
            if (sizeof($items2_sum) > 0) {
                foreach ($items2_sum as $key => $value) {
                    $sessionData['items2_sum'][$key] = $value;
                }
            }
            if (sizeof($items3) > 0) {
                foreach ($items3 as $key => $value) {
                    $sessionData['items3'][$key] = $value;
                }
            }
            if (sizeof($items3_sum) > 0) {
                foreach ($items3_sum as $key => $value) {
                    $sessionData['items3_sum'][$key] = $value;
                }
            }
            if (sizeof($items4) > 0) {
                foreach ($items4 as $key => $value) {
                    $sessionData['items4'][$key] = $value;
                }
            }
            if (sizeof($items4_sum) > 0) {
                foreach ($items4_sum as $key => $value) {
                    $sessionData['items4_sum'][$key] = $value;
                }
            }
            if (sizeof($items5) > 0) {
                foreach ($items5 as $key => $value) {
                    $sessionData['items5'][$key] = $value;
                }
            }
            if (sizeof($items5_sum) > 0) {
                foreach ($items5_sum as $key => $value) {
                    $sessionData['items5_sum'][$key] = $value;
                }
            }
            if (sizeof($items6) > 0) {
                foreach ($items6 as $key => $value) {
                    $sessionData['items6'][$key] = $value;
                }
            }
            if (sizeof($items6_sum) > 0) {
                foreach ($items6_sum as $key => $value) {
                    $sessionData['items6_sum'][$key] = $value;
                }
            }
            if (sizeof($items7) > 0) {
                foreach ($items7 as $key => $value) {
                    $sessionData['items7'][$key] = $value;
                }
            }
            if (sizeof($items7_sum) > 0) {
                foreach ($items7_sum as $key => $value) {
                    $sessionData['items7_sum'][$key] = $value;
                }
            }
            if (sizeof($items8) > 0) {
                foreach ($items8 as $key => $value) {
                    $sessionData['items8'][$key] = $value;
                }
            }
            if (sizeof($items8_sum) > 0) {
                foreach ($items8_sum as $key => $value) {
                    $sessionData['items8_sum'][$key] = $value;
                }
            }
            if (sizeof($items9_sum) > 0) {
                foreach ($items9_sum as $key => $value) {
                    $sessionData['items9_sum'][$key] = $value;
                }
            }
            if (sizeof($items10_sum) > 0) {
                foreach ($items10_sum as $key => $value) {
                    $sessionData['items10_sum'][$key] = $value;
                }
            }
            //---- registry resultItems dan resultItems2 dialihkan ke resultItems_revert dan resultItems2_revert
            if (sizeof($rsltItems) > 0) {
                if (isset($sessionData['rsltItems_revert'])) {
                    $sessionData['rsltItems_revert'] = NULL;
                    unset($sessionData['rsltItems_revert']);
                }
                foreach ($rsltItems as $key => $value) {
                    if (!isset($sessionData['rsltItems_revert'][$key])) {
                        $sessionData['rsltItems_revert'][$key] = $value;
                    }

                    //if (!isset($sessionData['rsltItems_revert'][$value['id']])) {
                    //    $sessionData['rsltItems_revert'][$value['id']] = $value;
                    //    $sessionData['rsltItems_revert'][$value['id']]['jml'] = 0;
                    //    $sessionData['rsltItems_revert'][$value['id']]['qty'] = 0;
                    //}
                    //$sessionData['rsltItems_revert'][$value['id']]['jml'] += $value['jml'];
                    //$sessionData['rsltItems_revert'][$value['id']]['qty'] += $value['qty'];
                }
            }
            else {
                $sessionData['rsltItems_revert'] = array();
            }
            if (sizeof($rsltItems2) > 0) {
                if (isset($sessionData['rsltItems2_revert'])) {
                    $sessionData['rsltItems2_revert'] = NULL;
                    unset($sessionData['rsltItems2_revert']);
                }
                foreach ($rsltItems2 as $key => $value) {
                    $sessionData['rsltItems2_revert'][$key] = $value;

                    //if (!isset($sessionData['rsltItems2_revert'][$value['id']])) {
                    //  $sessionData['rsltItems2_revert'][$value['id']] = $value;
//                        $sessionData['rsltItems2_revert'][$value['id']]['jml'] = 0;
//                        $sessionData['rsltItems2_revert'][$value['id']]['qty'] = 0;
//                    }
                    //                  $sessionData['rsltItems2_revert'][$value['id']]['jml'] += $value['jml'];
//                    $sessionData['rsltItems2_revert'][$value['id']]['qty'] += $value['qty'];
                }
            }
            else {
                $sessionData['rsltItems2_revert'] = array();
            }
            if (sizeof($main_elements) > 0) {
                foreach ($main_elements as $key => $value) {
                    $sessionData['main_elements'][$key] = $value;
                }
            }

            //-------------
            $componentAuto = loadConfigModulJenis_he_misc($jenisMasterRef, "coTransaksiCore")["componentsAuto"][$jenisMasterRef];
            $postProcessorAuto = loadConfigModulJenis_he_misc($jenisMasterRef, "coTransaksiCore")["postProcessorAuto"][$jenisMasterRef];
            $preProcessorAuto = loadConfigModulJenis_he_misc($jenisMasterRef, "coTransaksiCore")["preProcessorAuto"][$jenisMasterRef];
            //-------------


            $koreksi_serial = isset($configUiMasterModulJenis['koreksi_serial'][$jenisTr_reference]) ? $configUiMasterModulJenis['koreksi_serial'][$jenisTr_reference] : array();
            if (sizeof($koreksi_serial) > 0) {
//                if (isset($sessionData["items7_sum"]) && (sizeof($sessionData["items7_sum"]) > 0)) {
//                    // disini adalah GRN baru, generate serial saat PRE-GRN dan rekening serial saat GRN
//                }
//                else {
//                }
                if (isset($koreksi_serial["enabled"]) && ($koreksi_serial["enabled"] == true)) {
                    $list_ip_addr = $koreksi_serial["ipaddr"];
//                    if (in_array(ipadd(), $list_ip_addr)) {
                    if ($this->session->login['ghost'] == 1) {
                        // rebuild items2
                        $sessionData["items2"] = array();
                        $sessionData["items3_sum"] = array();
                        foreach ($mdlResultAllProduk as $pid => $pSpec) {
                            foreach ($pSpec as $sku => $skuSpec) {
                                foreach ($skuSpec as $sSpec) {
                                    $detail2 = array(
                                        "serial" => $sSpec->produk_serial_number_2,
                                        "sku" => $sSpec->produk_sku_part_nama,
                                        "sku_serial" => $sSpec->produk_sku_serial,
                                        "qty" => 1,
                                        "produk_sku_part_id" => $sSpec->produk_sku_part_id,
                                        "produk_sku_part_nama" => $sSpec->produk_sku_part_nama,
                                    );
                                    $sessionData["items2"][$pid][$sku][$sSpec->produk_serial_number_2] = $detail2;

                                    $detail3_sum = array(
                                        "id" => $sSpec->produk_id,
                                        "nama" => $sSpec->produk_nama,
                                        "name" => $sSpec->produk_nama,
                                        "kategori_id" => $sessionData["items"][$pid]["kategori_id"],
                                        "kategori_nama" => $sessionData["items"][$pid]["kategori_nama"],
                                        "jml" => 1,
                                        "qty" => 1,
                                        "barcode" => $sessionData["items"][$pid]["barcode"],
                                        "kode" => $sessionData["items"][$pid]["kode"],
                                        "produk_kode" => $sessionData["items"][$pid]["produk_kode"],
                                        "no_part" => $sessionData["items"][$pid]["no_part"],
                                        "label" => $sessionData["items"][$pid]["label"],
                                        "serial_number" => $sSpec->produk_serial_number_2,
                                        "produk_serial" => $sSpec->produk_serial_number_2,
                                        "produk_sku" => $sSpec->produk_sku_part_nama,
                                        "produk_sku_serial" => $sSpec->produk_sku_serial,
                                        "produk_sku_part_id" => $sSpec->produk_sku_part_id,
                                        "produk_sku_part_nama" => $sSpec->produk_sku_part_nama,
                                        "produk_sku_part_serial" => "",
                                    );
                                    $sessionData["items3_sum"][] = $detail3_sum;
                                }
                            }
                        }

                    }
                }
            }

            if (isset($sessionData['main']['pihakExternRevertRequest']) && ($sessionData['main']['pihakExternRevertRequest'] == true)) {
                $postProcessorRequestRevert = isset($configCoreMasterModulJenis['postProcessorRequestRevert'][$jenisTr_reference]) ? $configCoreMasterModulJenis['postProcessorRequestRevert'][$jenisTr_reference] : array();
            }

        }
        else {
//            cekMerah("tidak ada itemnya!");
            mati_disini("Transaksi yang akan dibatalkan tidak tersedia/tidak aktif/sudah dibatalkan.");
        }

        //region fetch jurnalconfig
        $postProccExternID = isset($sessionData['main']['pihakExternID']) ? $sessionData['main']['pihakExternID'] : "";
        $revertedJurnal = fetchRevertJurnal($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $component, $sessionData['main']['jenisTr_reference']);
        $revertPostProc = fetchRevertPostProc($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $postProcessor, $postProccExternID, $sessionData['main']['jenisTr_reference']);
        $revertPaymentSrc = fetchRevertPaymentSrc($masterMainFields["jenis"], $masterMainFields["step_number"], $masterMainFields["transaksi_id"]);
        $revertPaymentSrcUangMuka = fetchRevertPaymentSrcUangMuka($masterMainFields["jenis"], $masterMainFields["step_number"], $masterMainFields["transaksi_id"]);
        $swapcomFifo = fetchSwapComFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $postProcessor['detail'], $sessionData['main']['jenisTr_reference']); // ini untuk dimasukkan ke preprocc
        $swapPreFifo = fetchSwapPreFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['detail'], $sessionData['main']['jenisTr_reference']); // ini untuk masuk ke postprocc/component fifo
        $swapPreSubFifo = fetchSwapPreSubFifo($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['sub_detail'], $sessionData['main']['jenisTr_reference']); // ini untuk masuk ke postprocc/component fifo
        $swapPreFifoMain = fetchSwapPreFifoMain($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['master']);
        //--------------
        $swapPreProc = fetchSwapPreProc($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $preProcessor['detail'], $sessionData['main']['jenisTr_reference']);
        //--------------
        $revertedJurnalAuto = fetchRevertJurnalAuto($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $componentAuto, $sessionData['main']['jenisTr_reference']);
        $revertPostProcAuto = fetchRevertPostProcAuto($masterMainFields["jenisTrMaster"], $masterMainFields["step_number"], $postProcessorAuto, $postProccExternID);

        switch ($jenis_master) {
            case "967":
                $preProcc = array(
//                    "master" => array(
//                        // rekening koran
//                        array(
//                            "comName" => "RekeningKoranPembatalan",
//                            "loop" => array(),
//                            "static" => array(
//                                "cabang_id" => "placeID",
//                                "state" => ".active",
//                                "extern_id" => "cash_account",
//                                "extern_nama" => "cash_account__label",
//                                "nilai" => "nilai_entry",
//                                "method" => "cashMethode", // cash method yang dipilih
//                                "jenis" => ".hutang bank",
//
//                                "jenisTr" => "jenisTr",
//                            ),
//                            "resultParams" => array(
//                                "main" => array(
//                                    "nilai_cash" => "nilai_cash",
//                                    "nilai_koran" => "nilai_koran",
//                                    "nilai_cash_full" => "nilai_cash_full",
//                                    "nilai_koran_full" => "nilai_koran_full",
//                                ),
//                            ),
//                            "srcGateName" => "main",
//                            "srcRawGateName" => "main",
//                        ),
//                    ),
                );
                break;
            default:
                $preProcc = array(
                    "master" => array(
                        // rekening koran
                        array(
                            "comName" => "RekeningKoranPembatalan",
                            "loop" => array(),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "state" => ".active",
                                "extern_id" => "cash_account",
                                "extern_nama" => "cash_account__label",
                                "nilai" => "nilai_entry",
                                "method" => "cashMethode", // cash method yang dipilih
                                "jenis" => ".hutang bank",

                                "jenisTr" => "jenisTr",
                            ),
                            "resultParams" => array(
                                "main" => array(
                                    "nilai_cash" => "nilai_cash",
                                    "nilai_koran" => "nilai_koran",
                                    "nilai_cash_full" => "nilai_cash_full",
                                    "nilai_koran_full" => "nilai_koran_full",
                                ),
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                    ),
                );
                break;
        }

        $arrRekPPV = array(
            "alfabet" => "hutang lain ppv",
//            "coa" => "2010090010",//hutang lain ppv
            "coa" => "7010150",
        );
        $tgl_coa = "2022-11-14";
        if ($sessionData['main']['reference_fulldate'] > $tgl_coa) {
            $rekening_hutang_ppv = $arrRekPPV["coa"];
        }
        else {
            $rekening_hutang_ppv = $arrRekPPV["alfabet"];
        }

        $rekExeption2 = "2010090010";

        $jenisTr_reference = in_array($jenisTr_reference, $this->jenisTrException) ? $jenisTr_reference : $sessionData['main']['jenisTr_reference'];


        if (sizeof($swapcomFifo) > 0) {

            $jenisException = $this->CI->config->item('heTransaksi_revertJenisException') != null ? $this->CI->config->item('heTransaksi_revertJenisException') : array();
//            cekPink2(":: $jenisTr_reference ::");

            if (!in_array($jenisTr_reference, $jenisException)) {
//                arrPrint($revertedJurnal);
//matiHEre(__LINE__);
                if (isset($swapcomFifo['detail']) && sizeof($swapcomFifo['detail']) > 0) {
                    foreach ($revertedJurnal as $main => $mainVal) {
                        $loop = array();
                        foreach ($mainVal as $key => $ValDetails) {
                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Jurnal") {
//                                if (isset($ValDetails["loop"]) && !array_key_exists("2010090010", $ValDetails["loop"])) {
                                if (isset($ValDetails["loop"]) && !array_key_exists("$rekening_hutang_ppv", $ValDetails["loop"]) && !array_key_exists($rekExeption2, $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['7010190'])) {
                                        $revertedJurnal[$main][$key]['loop']['7010190'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['7010190']);
                                    }
                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;

                                        case "4466":
                                        case "3344":
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = ".0";//"(hpp+ppn)-nett"
                                            break;
                                        case "9822":
                                            if (isset($revertedJurnal[$main][$key]['loop']['2030060'])) {
                                                $revertedJurnal[$main][$key]['loop']['2030060'] = "grand_ppn";
                                            }
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }
                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Rekening") {
//                                if (isset($ValDetails["loop"]) && !array_key_exists("2010090010", $ValDetails["loop"])) {
                                if (isset($ValDetails["loop"]) && !array_key_exists("$rekening_hutang_ppv", $ValDetails["loop"]) && !array_key_exists($rekExeption2, $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['7010190'])) {
                                        $revertedJurnal[$main][$key]['loop']['7010190'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['7010190']);
                                    }
                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
//                                            arrPrintPink($ValDetails);
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        case "3344":
                                        case "4466":
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = ".0";//"(hpp+ppn)-nett"
                                            break;
                                        case "9822":
                                            if (isset($revertedJurnal[$main][$key]['loop']['2030060'])) {
                                                $revertedJurnal[$main][$key]['loop']['2030060'] = "grand_ppn";
                                            }
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            foreach ($swapcomFifo as $gate => $spec) {
                foreach ($spec as $ii => $subSpec) {
//                    arrPrintWebs($subSpec);
//                    matiHere(__LINE__);
                    if (isset($subSpec["comName"]) && $subSpec["comName"] == "FifoAverage") {
                        foreach ($subSpec["resultParams"] as $gateIx => $subSpec_0) {
                            if (!isset($subSpec["resultParams"][$gateIx]["harga"])) {
                                /*
                                 * ini masih ditembak untuk replace harga dari nota yang gerbang jurnalnya harga namun saat dibatalin sumber harga dari hpp
                                 */
                                $subSpec["resultParams"][$gateIx]["harga"] = "hpp";
                            }
                        }
                    }
                    $preProcc[$gate][$ii] = $subSpec;
                }
            }
        }


        if (sizeof($swapPreFifo) > 0) {
            foreach ($swapPreFifo as $gate => $swapPreFifoSpec) {
                foreach ($swapPreFifoSpec as $spec) {
                    $revertPostProc[$gate][] = $spec;
                }
            }
        }
        if (sizeof($swapPreSubFifo) > 0) {
            foreach ($swapPreSubFifo as $gate => $swapPreSubFifoSpec) {
                foreach ($swapPreSubFifoSpec as $spec) {
                    $revertPostProc[$gate][] = $spec;
                }
            }
        }
        //-------------------------------
        if (sizeof($swapPreProc) > 0) {
            foreach ($swapPreProc as $gate => $swapPreProcSpec) {
                foreach ($swapPreProcSpec as $spec) {
                    $revertPostProc[$gate][] = $spec;
                }
            }
        }
        //-------------------------------
        if (sizeof($swapPreFifoMain) > 0) {
            foreach ($swapPreFifoMain as $gate => $swapPreFifoMainSpec) {
                foreach ($swapPreFifoMainSpec as $spec) {
                    $revertPostProc[$gate][] = $spec;
                }
            }
        }

        $preProcNameReplacer = array(
            "FifoProdukJadi" => "FifoProdukJadi_reverse",
        );
        switch ($jenis_master) {
            case "460":
//                $replacerMaster = array(
//                    "persediaan produk riil" => "hpp_riil",
//                    "persediaan produk" => "hpp_nppv",
//                    "hutang lain ppv" => "ppv_riil",
//                );
//                $replacerDetail = array(
//
//                );
//                if (sizeof($revertedJurnal['master']) > 0) {
////                    arrPrintWebs($revertedJurnal['master']);
//                    foreach ($revertedJurnal['master'] as $spec){
//                        if(($spec['comName']=="Jurnal") || ($spec['comName']=="Rekening")){
//                            foreach ($spec['loop'] as $keys => $vals){
//
//                            }
//                        }
//                    }
//                }

                if (isset($preProcc['detail'])) {
                    foreach ($preProcc['detail'] as $ii => $spec) {
                        if (array_key_exists($spec['comName'], $preProcNameReplacer)) {
                            $spec['comName'] = $preProcNameReplacer[$spec['comName']];
                            $spec['static']['transaksi_id_ref'] = ".$transID_ref";
                            $preProcc['detail'][$ii] = $spec;
                        }
                    }
                }
//                arrPrintWebs($preProcc);


                break;
            default:
                break;
        }

        if (sizeof($revertedJurnal) > 0) {
            if (isset($revertedJurnal['master'])) {
                krsort($revertedJurnal['master']);
            }
            if (isset($revertedJurnal['detail'])) {
                krsort($revertedJurnal['detail']);
            }
            //------------------------
            if (isset($revertedJurnal['preProcc']) && (sizeof($revertedJurnal['preProcc']) > 0)) {
                $revPreProcc = $revertedJurnal['preProcc'];
                $revertedJurnal['preProcc'] = NULL;
                unset($revertedJurnal['preProcc']);

                foreach ($revPreProcc as $gate => $gateSpec) {
                    foreach ($gateSpec as $subSpec) {
                        $preProcc[$gate][] = $subSpec;
                    }
                }

                if (isset($revPreProcc['detail']) && (sizeof($revPreProcc['detail']) > 0)) {
                    foreach ($revertedJurnal as $main => $mainVal) {
                        $loop = array();
                        foreach ($mainVal as $key => $ValDetails) {

                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Jurnal") {
//                                if (isset($ValDetails["loop"]) && !array_key_exists("2010090010", $ValDetails["loop"])) {
                                if (isset($ValDetails["loop"]) && !array_key_exists("$rekening_hutang_ppv", $ValDetails["loop"]) && !array_key_exists($rekExeption2, $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['7010190'])) {
                                        $revertedJurnal[$main][$key]['loop']['7010190'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['7010190']);
                                    }

                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        case "3344":
                                        case "4466":
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = ".0";//"(hpp+ppn)-nett"
                                            break;
                                        case "9822":
                                            if (isset($revertedJurnal[$main][$key]['loop']['2030060'])) {
                                                $revertedJurnal[$main][$key]['loop']['2030060'] = "grand_ppn";
                                            }
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }

                            if (isset($ValDetails["comName"]) && $ValDetails["comName"] == "Rekening") {
//                                if (isset($ValDetails["loop"]) && !array_key_exists("2010090010", $ValDetails["loop"])) {
                                if (isset($ValDetails["loop"]) && !array_key_exists("$rekening_hutang_ppv", $ValDetails["loop"]) && !array_key_exists($rekExeption2, $ValDetails["loop"])) {

                                    if (isset($revertedJurnal[$main][$key]['loop']['7010190'])) {
                                        $revertedJurnal[$main][$key]['loop']['7010190'] = NULL;
                                        unset($revertedJurnal[$main][$key]['loop']['7010190']);
                                    }

                                    switch ($jenisTr_reference) {
                                        case "9911":
                                        case "9912":
                                            // detect placeID, bila pusat
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "585":
                                        case "1985":
                                        case "3685":
                                            // detect placeID, bila pusat
//                                            arrPrintPink($ValDetails);
                                            if ($ValDetails['static']['cabang_id'] == "placeID") {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                            }
                                            // detect placeID, bila cabang
                                            else {
//                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "hpp-nilai_cancel";//"(hpp+ppn)-nett"
                                                $revertedJurnal[$main][$key]['loop']['7010190'] = "nilai_cancel-hpp";//"(hpp+ppn)-nett"
                                            }
                                            break;
                                        case "334":
                                            break;
                                        case "1334":
                                            break;
                                        case "3344":
                                        case "4466":
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = ".0";//"(hpp+ppn)-nett"
                                            break;
                                        case "9822":
                                            if (isset($revertedJurnal[$main][$key]['loop']['2030060'])) {
                                                $revertedJurnal[$main][$key]['loop']['2030060'] = "grand_ppn";
                                            }
                                            break;
                                        default:
                                            $revertedJurnal[$main][$key]['loop']['7010190'] = "selisih";//"(hpp+ppn)-nett"
                                            break;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        }
        if (in_array($jenisTr_reference, $this->jenisTrException)) {
            if (sizeof($revertPostProc) > 0) {
                if (isset($revertPostProc['master'])) {
                    krsort($revertPostProc['master']);
                }
                if (isset($revertPostProc['detail'])) {
                    krsort($revertPostProc['detail']);
                }
            }
        }
        if (sizeof($postProcessorRequestRevert) > 0) {
            foreach ($postProcessorRequestRevert as $mdt => $mdtSpec) {
                foreach ($mdtSpec as $iimdt => $iimdtSpec) {
                    $revertPostProc[$mdt][] = $iimdtSpec;
                }
            }
        }

        //------------------------
        $revertedJurnal_awal = array();
        $componentsAwalRevert = isset($configCoreMasterModulJenis['componentsAwalGantiRekening'][$jenisTr_reference]) ? $configCoreMasterModulJenis['componentsAwalGantiRekening'][$jenisTr_reference] : array();
        if (sizeof($componentsAwalRevert) > 0) {
            $keyUangMuka = $componentsAwalRevert["key"];
            $tipeUangMuka = makeValue($componentsAwalRevert["tipeuangmuka"], $sessionData["main"], $sessionData["main"], 0);
            $batasTanggal = $componentsAwalRevert["batastanggal"];
            cekKuning("[$tipeUangMuka] [$keyUangMuka] [$batasTanggal] [$tr_fulldate_dibatalkan]");
            if (($tipeUangMuka == 1)
                && ($tr_fulldate_dibatalkan < $batasTanggal)) {
                $core = $componentsAwalRevert["core"];
                if (sizeof($core) > 0) {
                    foreach ($core as $masterGate => $mSpec) {
                        foreach ($mSpec as $ctr => $mmSpec) {
                            $revertedJurnal_awal[$masterGate][] = $mmSpec;
                        }
                    }
                }
            }
        }
        else {
            cekMerah("TIDAK ADA COMPONENT AWAL $jenisTr_reference...");
        }
        //------------------------
        $revertedJurnal_new = array();
        foreach ($revertedJurnal as $masterGate => $mSpec) {
            foreach ($mSpec as $ctr => $mmSpec) {
                switch ($masterGate) {
                    case "detail":
                    case "sub_detail":
                        $rek_replacer = array(
                            "8020" => "RekeningPembantuProdukRiil",// 8020 maka comName direplace RekeningPembantuProdukRiil
                        );
                        if (isset($mmSpec['loop'])) {
                            foreach ($mmSpec['loop'] as $rek_loop => $gate_loop) {
                                if (array_key_exists($rek_loop, $rek_replacer)) {
                                    $mmSpec["comName"] = $rek_replacer[$rek_loop];
                                }
                            }
                        }
                        break;
                }
                if (isset($mmSpec['loop'])) {
                    $old_loop = $mmSpec['loop'];
                    unset($mmSpec['loop']);

                    $new_loop = array();
                    foreach ($old_loop as $key_loop => $val_loop) {
                        // mendeteksi rekening yang sudah ganti label, kalau ada ikut label baru
                        if (isset($arrPenggantiRekening[$key_loop])) {
                            $key_loop = $arrPenggantiRekening[$key_loop];
                        }
                        // mendeteksi rekening masih dipakai atau tidak...
                        if (!in_array($key_loop, $arrDropRekening)) {
                            // mendeteksi rekening coa atau bukan, kalau bukan ganti ke coa
                            if (!is_numeric($key_loop)) {
//                                cekMerah("key rek loop: $key_loop, val rek loop: $val_loop");
                                if (substr($key_loop, 0, 1) == "{") {
//                                    $key_loop = trim($key_loop, "{");
//                                    $key_loop = trim($key_loop, "}");
//                                    $key_loop = str_replace($key_loop, $sessionData['main'][$key_loop], $key_loop);
//
                                    $new_key_loop = $key_loop;
                                }
                                else {

                                    $new_key_loop = isset($arrRekening_coa[$key_loop]) ? $arrRekening_coa[$key_loop] : NULL;
                                    if ($new_key_loop == NULL) {

                                        if ($key_loop == "exec_locker") {
                                            $new_key_loop = "exec_locker";
                                        }
                                        elseif ($key_loop == "exec") {
                                            $new_key_loop = "exec";
                                        }
                                        else {
                                            $msg = "Cart of Account belum lengkap, silahkan dikoordinasikan dengan web development. Rekening $key_loop :: " . $mmSpec["comName"];
                                            mati_disini($msg);
                                            die(lgShowAlert($msg));
                                        }

                                    }
                                }
                                $new_loop[$new_key_loop] = $val_loop;
                            }
                            else {
                                $new_loop[$key_loop] = $val_loop;
                            }
                        }
                    }

                    if (sizeof($new_loop) > 0) {
                        $revertedJurnal_new[$masterGate][$ctr] = $mmSpec;
                        $revertedJurnal_new[$masterGate][$ctr]['loop'] = $new_loop;
//                        $revertedJurnal_new[$masterGate][] = $mmSpec;
//                        $revertedJurnal_new[$masterGate][]['loop'] = $new_loop;
                    }
                }
                else {
                    $revertedJurnal_new[$masterGate][$ctr] = $mmSpec;
//                    $revertedJurnal_new[$masterGate][] = $mmSpec;
                }
            }

        }

        if (isset($revertPostProc["detail"]) && (sizeof($revertPostProc["detail"]) > 0)) {
            krsort($revertPostProc["detail"]);
            arrPrintHitam($revertPostProc["detail"]);
            $revertPostProcDetailSort = $revertPostProc["detail"];
            $revertPostProc["detail"] = $revertPostProcDetailSort;
        }

        $sessionData["revert"]["jurnal"] = $revertedJurnal_new;
        $sessionData["revert"]["postProc"] = $revertPostProc;
        $sessionData["revert"]["connectedPaymentsource"] = $revertPaymentSrc;
        $sessionData["revert"]["connectedPaymentsourceUangMuka"] = $revertPaymentSrcUangMuka;
        $sessionData["revert"]["preProc"] = $preProcc;

        if (sizeof($revertedJurnalAuto) > 0) {
            if (isset($revertedJurnalAuto['master'])) {
                krsort($revertedJurnalAuto['master']);
            }
            if (isset($revertedJurnalAuto['detail'])) {
                krsort($revertedJurnalAuto['detail']);
            }
            $sessionData["revertAuto"]["jurnalAuto"] = $revertedJurnalAuto;
        }
        if (sizeof($revertPostProcAuto) > 0) {
            if (isset($revertPostProcAuto['master'])) {
                krsort($revertPostProcAuto['master']);
            }
            if (isset($revertPostProcAuto['detail'])) {
                krsort($revertPostProcAuto['detail']);
            }
            $sessionData["revertAuto"]["postProcAuto"] = $revertPostProcAuto;
        }
        if (sizeof($preProcessorAuto) > 0) {
//            if (isset($revertPostProcAuto['master'])) {
//                krsort($revertPostProcAuto['master']);
//            }
//            if (isset($revertPostProcAuto['detail'])) {
//                krsort($revertPostProcAuto['detail']);
//            }
            $sessionData["revertAuto"]["preProcAuto"] = $preProcessorAuto;
        }

        if (sizeof($revertedJurnal_awal) > 0) {
            $sessionData["revertAwal"]["jurnalAwal"] = $revertedJurnal_awal;
        }
        //endregion

        if (in_array($tmpB[0]->jenis, $this->jenisTrException)) {
//            $sessionData['main']['nilai_cancel'] = ""; // nilai_cancel tidak diganti
//            cekPink2("disini");
        }
        else {
            if (isset($this->whitelistMain) && sizeof($this->whitelistMain) > 0) {
                foreach ($this->whitelistMain as $key => $val) {
                    if (isset($sessionData['main'][$val])) {
                        $sessionData['main']['nilai_cancel'] = $sessionData['main'][$val];
                    }
                }
            }
        }

        $nextProp = array();
        if (isset($sessionData['main']['pihakExternRevertStep']) && ($sessionData['main']['pihakExternRevertStep'] == true)) {
            $pihakExternMasterID = isset($sessionData['main']['pihakExternMasterID']) ? $sessionData['main']['pihakExternMasterID'] : 0;
            $configUiMasterModulJenis = loadConfigModulJenis_he_misc($pihakExternMasterID, "coTransaksiUi");
//            arrPrintHijau($configUiMasterModulJenis);
            $stepsConfig = isset($configUiMasterModulJenis['steps']) ? $configUiMasterModulJenis['steps'] : array();
            $maxStep = sizeof($stepsConfig);
//            cekHere("::: [$pihakExternMasterID] $maxStep :::");
            if ($maxStep > 0) {
                $stepNum = $sessionData['main']['referenceStepNumber'] - 1;
                $nextStepNum = $sessionData['main']['referenceStepNumber'];
                $idHist = blobDecode($tmpB[0]->ids_his);
                $trID_hist = $idHist[$stepNum]["trID"];
//                arrPrintKuning($idHist);
                switch ($jenis_master) {
                    case "111":
                        $t = new MdlTransaksi();
                        $t->setfilters(array());
                        $t->addFilter("id_master='$tr_id_master_dibatalkan'");
                        $t->addFilter("jenis='111r'");
                        $tTmp = $t->lookUpAll()->result();
                        $trID_hist = $tTmp[0]->id;
                        break;
                }
                $nextProp = array(
//                    "step_num" => $stepNum,
//                    "num" => $nextStepNum,
//                    "code" => $this->CI->configUi[$pihakExternMasterID]['steps'][$nextStepNum]['target'],
//                    "label" => $this->CI->configUi[$pihakExternMasterID]['steps'][$nextStepNum]['label'],
//                    "groupID" => $this->CI->configUi[$pihakExternMasterID]['steps'][$nextStepNum]['userGroup'],
//                    "trID" => isset($sessionData['main']['referenceID_top']) ? $sessionData['main']['referenceID_top'] : 0,
//                    "detailGate" => isset($sessionData['main']['pihakExternDetailGate']) ? $sessionData['main']['pihakExternDetailGate'] : "",

                    "step_num" => $stepNum,
                    "num" => $nextStepNum,
                    "code" => $configUiMasterModulJenis['steps'][$nextStepNum]['target'],
                    "label" => $configUiMasterModulJenis['steps'][$nextStepNum]['label'],
                    "groupID" => $configUiMasterModulJenis['steps'][$nextStepNum]['userGroup'],
//                    "trID" => isset($sessionData['main']['referenceID_top']) ? $sessionData['main']['referenceID_top'] : 0,
                    "trID" => $trID_hist,
                    "detailGate" => isset($sessionData['main']['pihakExternDetailGate']) ? $sessionData['main']['pihakExternDetailGate'] : "",
                );

            }
        }

        $sessionData['main']['referenceNextProp'] = $nextProp;

        //----------------------------------
        if (isset($arrdataslunas) && (sizeof($arrdataslunas) > 0)) {
            //replace gerbang nilai....
//            nilai_bayar_nocn_nolebih_bayar ----- dikurangi terbayar ()
//            deposit_konsumen ----- ditambah terbayar ()
            cekHijau("terbayar total lunas: $total_terbayar_lunas");

            $deposit_konsumen = isset($sessionData["main"]["deposit_konsumen"]) ? $sessionData["main"]["deposit_konsumen"] : 0;
            $nilai_bayar_nocn_nolebih_bayar = isset($sessionData["main"]["nilai_bayar_nocn_nolebih_bayar"]) ? $sessionData["main"]["nilai_bayar_nocn_nolebih_bayar"] : 0;
            $deposit_konsumen_new = $deposit_konsumen + $total_terbayar_lunas;
            $nilai_bayar_nocn_nolebih_bayar_new = $nilai_bayar_nocn_nolebih_bayar - $total_terbayar_lunas;
            cekKuning("deposit_konsumen_new: $deposit_konsumen_new");
            cekKuning("nilai_bayar_nocn_nolebih_bayar_new: $nilai_bayar_nocn_nolebih_bayar_new");
            $sessionData["main"]["deposit_konsumen"] = $deposit_konsumen_new;
            $sessionData["main"]["nilai_bayar_nocn_nolebih_bayar"] = $nilai_bayar_nocn_nolebih_bayar_new;
        }

        $sessionData["main"]["label_notif"] = NULL;
        switch ($jenis_master) {
            case "466":
                if ($status_grn == 1) {
                    $this->CI->load->model("Mdls/MdlLockerStockDiskonVendor");
                    $diskonIDs = array(1, 2, 3, 4, 5, 8);
                    $mm = New MdlLockerStockDiskonVendor();
                    $mm->addFilter("transaksi_id='$transID_ref'");
                    $mm->addFilter("extern_id in ('" . implode("','", $diskonIDs) . "')");
                    $mmTmp = $mm->lookupAll()->result();
                    showLast_query("biru");
                    $sessionData["items4_sum"] = array();
                    if (sizeof($mmTmp) > 0) {
                        foreach ($mmTmp as $mmSpec) {
                            $pid = $mmSpec->extern2_id;
                            $pnama = $mmSpec->extern2_nama;
                            $jml = $mmSpec->jumlah;
                            $diskon_id = $mmSpec->extern_id;
                            $diskon_nama = $mmSpec->extern_nama;
                            $diskon_nilai_unit = $mmSpec->nilai_unit;
                            $diskon_nilai = $mmSpec->nilai_unit;
                            $diskon_nilai_diklaim = $mmSpec->nilai_diklaim;
                            $diskon_nilai_total = $diskon_nilai + $diskon_nilai_diklaim;
                            $data4_sum = array(
                                "id" => $pid,
                                "nama" => $pnama,
                                "name" => $pnama,
                                "jml" => $jml,
                                "qty" => $jml,
                                "diskon_id" => $diskon_id,
                                "diskon_nama" => $diskon_nama,
                                "diskon_name" => $diskon_nama,
                                "diskon_persen" => 0,
                                "diskon_nilai" => $diskon_nilai_total,
                                "diskon_nilai_total" => $diskon_nilai_total,
                                "laba_lain_lain" => $diskon_nilai_total,
                            );
                            $sessionData["items4_sum"][] = $data4_sum;
                        }
                    }
                }
                break;
            case "588":
            case "588st":
//                if(!isset($sessionData['main']['harga_non_ppn'])){
//                    $harga_non_ppn = $sessionData['main']['nett1'];
//                    $ppn = (11/100) * $harga_non_ppn;
//                    $harga_nppn = $harga_non_ppn + $ppn;
//                    $sessionData['main']['harga_non_ppn'] = $harga_non_ppn;
//                }
//
                break;
            case "1771":
            case "771":
            case "111":
            case "498":
            case "487":
            case "462":
            case "1462":
            case "483":
                $addgate = array(
                    "referenceID" => "referenceID",
                    "referenceNomer" => "referenceNomer",
                    "rejection" => ".1",
                );
                foreach ($sessionData["revert"]["postProc"]["detail"] as $xx => $xxSpec) {
                    foreach ($addgate as $aa => $bb) {
                        $xxSpec["static"][$aa] = $bb;
                    }
                    $sessionData["revert"]["postProc"]["detail"][$xx] = $xxSpec;
                }
                break;
            case "16678":
                // mengambalikan locker transaksi komisi (invoice yang dipilih) dari 1 menjadi 0
                // supaya bisa dipilih lagi/request ulang.
                $addPostProccDetail = array(
                    array(
                        "comName" => "LockerProject",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".komisi",
                            "state" => ".hold",
                            "jumlah" => ".-1",
                            "produk_id" => "id",
                            "nama" => "name",
//                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "rejection" => ".1",
                            "reference_id" => "masterID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                );

                foreach ($addPostProccDetail as $xx => $addSpec) {
                    $sessionData["revert"]["postProc"]["detail"][] = $addSpec;
                }
                break;
            case "16677":
                // mengambalikan locker transaksi komisi (invoice yang dipilih) dari 1 menjadi 0
                // supaya bisa dipilih lagi/request ulang.
                $addPostProccDetail = array(
                    array(
                        "comName" => "LockerTransaksi",
                        "loop" => array(),
                        "static" => array(
                            "cabang_id" => "place2ID",
                            "jenis" => ".komisi",
                            "state" => ".hold",
                            "jumlah" => ".-1",
                            "produk_id" => "id",
                            "nama" => "name",
//                            "transaksi_id" => ".0",
                            "oleh_id" => ".0",
                            "gudang_id" => "gudang2ID",
                            "rejection" => ".1",
                            "reference_id" => "masterID",
                        ),
                        "reversable" => true,
                        "srcGateName" => "items",
                        "srcRawGateName" => "items",
                    ),
                );

                foreach ($addPostProccDetail as $xx => $addSpec) {
                    $sessionData["revert"]["postProc"]["detail"][] = $addSpec;
                }

                break;

            case "19467":
                if (isset($sessionData["main"]["referensi_so"]) && ($sessionData["main"]["referensi_so"] > 0)) {
                    $jurnal_tambahan = false;
                    $trs = new MdlTransaksi();
                    $trsTmp = $trs->lookupDetailTransaksiNoJenis($sessionData["main"]["referensi_so"])->result();
                    if (sizeof($trsTmp) > 0) {
                        foreach ($trsTmp as $trsSpec) {
                            $tr = new MdlTransaksi();
                            $tr->addFilter("id=" . $trsSpec->produk_id);
                            $trTmp = $tr->lookupMainTransaksi()->result();
                            if ($trTmp[0]->trash_4 == 1) {
                                $jurnal_tambahan = true;
                                break;
                            }
                        }
                    }
                    cekKuning("[jurnal_tambahan: $jurnal_tambahan]");
                    if ($jurnal_tambahan == true) {
                        $componentsRevertLabel = isset($configUiMasterModulJenis['componentsGantiRekening'][$jenisTr_reference]) ? $configUiMasterModulJenis['componentsGantiRekening'][$jenisTr_reference] : array();
                        $label_notif = str_replace("{customerName}", $sessionData["main"]["customerName"], $componentsRevertLabel["label"]);
                        $sessionData["main"]["label_notif"] = $label_notif;
                        //region component tambahan
                        $componentsRevert = isset($configCoreMasterModulJenis['componentsGantiRekening'][$jenisTr_reference]) ? $configCoreMasterModulJenis['componentsGantiRekening'][$jenisTr_reference] : array();
                        if (sizeof($componentsRevert) > 0) {
                            $key_cek_nilai = $componentsRevert["key"];
                            if (isset($sessionData["main"][$key_cek_nilai]) && ($sessionData["main"][$key_cek_nilai] > 0)) {
                                $coreMaster = $componentsRevert["core"]["master"];
                                $coreDetail = $componentsRevert["core"]["detail"];
                                if (sizeof($coreMaster) > 0) {
                                    foreach ($coreMaster as $mSpec) {
                                        $sessionData["revert"]["jurnal"]["master"][] = $mSpec;
                                    }
                                }
                                if (sizeof($coreDetail) > 0) {
                                    foreach ($coreDetail as $dSpec) {
                                        $sessionData["revert"]["jurnal"]["detail"][] = $dSpec;
                                    }
                                }
                            }
                        }
                        //endregion

                        //region postprocc tambahan
                        $postProccRevert = isset($configCoreMasterModulJenis['postProcessorGantiRekening'][$jenisTr_reference]) ? $configCoreMasterModulJenis['postProcessorGantiRekening'][$jenisTr_reference] : array();
                        if (sizeof($postProccRevert) > 0) {
                            $key_cek_nilai = $postProccRevert["key"];
                            if (isset($sessionData["main"][$key_cek_nilai]) && ($sessionData["main"][$key_cek_nilai] > 0)) {
                                $postMaster = $postProccRevert["core"]["master"];
                                $postDetail = $postProccRevert["core"]["detail"];
                                if (sizeof($postMaster) > 0) {
                                    foreach ($postMaster as $mSpec) {
                                        $sessionData["revert"]["postProc"]["master"][] = $mSpec;
                                    }
                                }
                                if (sizeof($postDetail) > 0) {
                                    foreach ($postDetail as $dSpec) {
                                        $sessionData["revert"]["postProc"]["detail"][] = $dSpec;
                                    }
                                }
                            }
                        }
                        //endregion
                    }

                }

                break;

            default:
                cekHitam("<h4>DEFAULT BOSS...</h4>");
                break;
        }


        //kloning ep_point transaksi yang dibatalkan----------------------------------
        $trs = new MdlTransaksi();
        $trs->setFilters(array());
        $trs->addFilter("link_id='$tr_id_dibatalkan'");
        $trsPoint = $trs->lookupEntryPoints($tr_id_master_dibatalkan)->result();
        if (sizeof($trsPoint) > 0) {
            $refEpPoint = (array)$trsPoint[0];
            $nomer_old = $refEpPoint["nomer"];
            $nomer_old_ex = explode("_", $nomer_old);
            $nomer_new_ep = $nomer_old_ex[0] . "_" . $nomer_old_ex[1] . "_" . date("YmdHis");
            $jenis_old = $refEpPoint["jenis"];
            $jenis_old_ex = explode("_", $jenis_old);
            $jenis_new_ep = $jenis_old_ex[0];
            unset($refEpPoint["id"]);
            $refEpPoint["nomer"] = $nomer_new_ep;
            $refEpPoint["jenis"] = $jenis_new_ep;
            $refEpPoint["jenis_label"] = "Pembatalan " . $refEpPoint["jenis_label"];

            $sessionData['tableIn_master_eppoint'] = $refEpPoint;
        }
        //----------------------------------

        $jenisTr_reference_trans = (isset($sessionData["main"]["pihakExternMasterID"])) ? $sessionData["main"]["pihakExternMasterID"] : $this->jenisTr;
        $configUiMasterModulJenisReference = loadConfigModulJenis_he_misc($jenisTr_reference_trans, "coTransaksiUi");
        $configCoreMasterModulJenisReference = loadConfigModulJenis_he_misc($jenisTr_reference_trans, "coTransaksiCore");
        $configLayoutMasterModulJenisReference = loadConfigModulJenis_he_misc($jenisTr_reference_trans, "coTransaksiLayout");
        $shoppingCartFieldSrc = isset($configUiMasterModulJenisReference['shoppingCartFieldSrc']) ? $configUiMasterModulJenisReference['shoppingCartFieldSrc'] : array();
        $itemLabels = isset($configUiMasterModulJenisReference['shoppingCartFields'][$stepNumber]) ? $configUiMasterModulJenisReference['shoppingCartFields'][$stepNumber] : array();
        $itemLabels2 = isset($configUiMasterModulJenisReference['shoppingCartFields2'][$stepNumber]) ? $configUiMasterModulJenisReference['shoppingCartFields2'][$stepNumber] : array();
        $itemsLabelReplacer = isset($configUiMasterModulJenisReference['shopingCartFieldsReplacer']) ? $configUiMasterModulJenisReference['shopingCartFieldsReplacer'] : array();
        if (isset($sessionData['main']['references']) || isset($sessionData['main']['singleReference'])) {
            $itemLabels = isset($configUiMasterModulJenisReference['shoppingCartFieldsExt'][$stepNumber]) ? $configUiMasterModulJenisReference['shoppingCartFieldsExt'][$stepNumber] : $itemLabels;
        }
        $shoppingCartFieldsSubItems = isset($configUiMasterModulJenisReference['shoppingCartFieldsSubItems'][$stepNumber]) ? $configUiMasterModulJenisReference['shoppingCartFieldsSubItems'][$stepNumber] : array();
        $itemLabels3 = isset($configUiMasterModulJenisReference['shoppingCartFields3'][$stepNumber]) ? $configUiMasterModulJenisReference['shoppingCartFields3'][$stepNumber] : array();
        $itemNumLabels = isset($configUiMasterModulJenisReference['shoppingCartNumFields'][$stepNumber]) ? $configUiMasterModulJenisReference['shoppingCartNumFields'][$stepNumber] : array();
        $itemNumLabels2 = isset($configUiMasterModulJenisReference['shoppingCartNumFields2'][$stepNumber]) ? $configUiMasterModulJenisReference['shoppingCartNumFields2'][$stepNumber] : array();
        $itemNumLabels3 = isset($configUiMasterModulJenisReference['shoppingCartNumFields3'][$stepNumber]) ? $configUiMasterModulJenisReference['shoppingCartNumFields3'][$stepNumber] : array();
        $relativeElementsAllowed = isset($this->configUi[$this->jenisTr]['relativeElementsAllowed'][$jenisTr_reference_trans]) ? $this->configUi[$this->jenisTr]['relativeElementsAllowed'][$jenisTr_reference_trans] : false;
        $elementConfigsOrig = isset($this->configUi[$this->jenisTr]['receiptElements']) ? $this->configUi[$this->jenisTr]['receiptElements'] : array();
        $elementConfigs = isset($configUiMasterModulJenisReference['receiptElements']) ? $configUiMasterModulJenisReference['receiptElements'] : array();
        if (sizeof($elementConfigsOrig) > 0) {
            $elementConfigs = $elementConfigs + $elementConfigsOrig;
            foreach ($elementConfigsOrig as $xx => $xxSpec) {
                $configUiMasterModulJenisReference['receiptElements'][$xx] = $xxSpec;
            }
        }
        $receiptElementsTambahan = isset($this->configUi[$this->jenisTr]['receiptElementsTambahan'][$sessionData["main"]["pihakExternMasterID"]]) ? $this->configUi[$this->jenisTr]['receiptElementsTambahan'][$sessionData["main"]["pihakExternMasterID"]] : array();
        if (sizeof($receiptElementsTambahan) > 0) {
            $elementConfigs = $elementConfigs + $receiptElementsTambahan["element"];
        }
        $elementItemsAutoConfigs = array();
        $arrHeaderElement = array();
        if ($relativeElementsAllowed == true) {
            $relElementConfigs = isset($configUiMasterModulJenisReference['relativeElements']) ? $configUiMasterModulJenisReference['relativeElements'] : array();
        }
        else {
            $relElementConfigs = array();
        }
        //region elements & inputs (if any)
        $elStr = array();
        $elements = array();
        $inputs = array();
        $addRows = array();
        $addRowLabels = array();
        $addRowHiddens = array();

        $currentValue = "";
        //==iterasi untuk memasukkan element relatif
        if (!isset($sessionData['main_inputs'])) {
            $sessionData['main_inputs'] = array();
        }
        if (isset($sessionData['main_elements']) && sizeof($sessionData['main_elements']) > 0) {
            foreach ($sessionData['main_elements'] as $eName => $eSpec) {
                if (array_key_exists($eName, $relElementConfigs)) {
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $eSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $eSpec['value'];
                            break;
                    }
                    if (sizeof($relElementConfigs[$eName]) > 0) {
                        foreach ($relElementConfigs[$eName] as $valID => $valSpec) {
                            if ($currentValue == $valID) {
                            }
                            else {
                            }
                        }
                    }
                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            foreach ($relElementConfigs[$eName][$currentValue] as $rKey => $rcSpec) {
                                $elKey = $rKey;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rKey];
                            }
                        }
                    }
                }
                if (array_key_exists($eName, $relOptionConfigs)) {
                    if (isset($relOptionConfigs[$eName][$currentValue])) {
                        if (sizeof($relOptionConfigs[$eName][$currentValue]) > 0) {
                            foreach ($relOptionConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
                                if (isset($oValSpec['addPoints']) && in_array(1, $oValSpec['addPoints'])) {
                                    $defVal = isset($sessionData['main_inputs'][$oValueName]) && $sessionData['main_inputs'][$oValueName] > 0 ? $sessionData['main_inputs'][$oValueName] : $origDefValue;
                                    $sessionData['main_inputs'][$oValueName] = $defVal;
                                }
                            }
                        }
                    }
                }
            }
        }

        // matiHere(__LINE__ . __METHOD__);
        //==memproses awal elemen2 yang terlibat, jika ada yang bisa dipre-process
        //==misalnya yang pilihannya cuma satu atau yang ada config nilai defaultnya
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
                $elementConfigs[$eName]['autoSelect'] = false;
                if (!isset($sessionData['main_elements'][$eName])) {
                    if (isset($eSpec['defaultValue'])) {//==cek apakah ada seting defaultValue
                        $defValueSrc = $eSpec['defaultValue'];
                        switch ($eSpec['elementType']) {
                            case "dataModel":
                                $configUiJenis = $configUiMasterModulJenisReference;
                                heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                break;
                            case "dataField":
                                $configUiJenis = $configUiMasterModulJenisReference;
                                heRecordElement_modul($this->jenisTr, $eName, $defValueSrc, $configUiJenis);
                                break;
                        }
                        $elementConfigs[$eName]['autoSelect'] = true;
                    }
                    else {//==cek apakah pilihannya cuma satu
                        if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {
                        }
                        else {
                            switch ($eSpec['elementType']) {
                                case "dataModel":
                                    $amdlName = $eSpec['mdlName'];
                                    $this->CI->load->model("Mdls/" . $amdlName);
                                    $labelSrc = $eSpec['labelSrc'];
                                    $keySrc = $eSpec['key'];
                                    $oo = new $amdlName();
                                    $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                    if (sizeof($aFilter) > 0) {
                                        $oo = makeFilter($aFilter, $sessionData['main'], $oo);
                                    }
                                    $tmpo = $oo->lookupAll()->result();
                                    if (sizeof($tmpo) == 1) {
                                        $usedKey = $eSpec['key'];
                                        $defValueSrc = $tmpo[0]->$usedKey;
                                        $configUiJenis = $configUiMasterModulJenisReference;
                                        heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                    }
                                    break;
                                case "dataField":
                                    break;
                            }
                        }
                    }
                }
                else {
                    if (isset($eSpec['noPrefetch']) && $eSpec['noPrefetch'] == true) {

                    }
                    else {
                        switch ($eSpec['elementType']) {
                            case "dataModel":
                                $amdlName = $eSpec['mdlName'];
                                $this->CI->load->model("Mdls/" . $amdlName);
                                $labelSrc = $eSpec['labelSrc'];
                                $keySrc = $eSpec['key'];
                                $oo = new $amdlName();
                                $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                                if (sizeof($aFilter) > 0) {
                                    $oo = makeFilter($aFilter, $sessionData['main'], $oo);
                                }
                                $tmpo = $oo->lookupAll()->result();
                                if (sizeof($tmpo) == 1) {
                                    $usedKey = $eSpec['key'];
                                    $defValueSrc = $tmpo[0]->$usedKey;
                                    $configUiJenis = $configUiMasterModulJenisReference;
                                    heFetchElement_modul($this->jenisTr, $eName, $eSpec['mdlName'], $defValueSrc, $configUiJenis);
                                }
                                break;
                            case "dataField":
                                break;
                        }
                    }
                }
            }
        }
        //==menciptakan selektor/pilihan berdasarkan jenis elemen
        if (sizeof($elementConfigs) > 0) {
            foreach ($elementConfigs as $eName => $eSpec) {
                //reset dulu kalau yg tidak ada
                if (array_key_exists($eName, $relElementConfigs)) {
                    switch ($eSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $eSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $eSpec['value'];
                            break;
                    }
                    if (sizeof($relElementConfigs[$eName]) > 0) {
                        foreach ($relElementConfigs[$eName] as $valID => $valSpec) {
                            if ($currentValue == $valID) {
                            }
                            else {
                            }
                        }
                    }
                    if (array_key_exists($currentValue, $relElementConfigs[$eName])) {
                        if (sizeof($relElementConfigs[$eName][$currentValue]) > 0) {
                            foreach ($relElementConfigs[$eName][$currentValue] as $rKey => $rcSpec) {
                                $elKey = $rKey;
                                $elementConfigs[$elKey] = $relElementConfigs[$eName][$currentValue][$rKey];
                            }
                        }
                    }
                }
                if (array_key_exists($eName, $addRowsConfigs)) {
                    switch ($elementConfigs[$eName]['elementType']) {
                        case "dataModel":
                            $currentValue = isset($sessionData['main_elements'][$eName]['key']) ? $sessionData['main_elements'][$eName]['key'] : "";
                            break;
                        case "dataField":
                            $currentValue = $sessionData['main_elements'][$eName]['value'];
                            break;
                    }
                    if (isset($addRowsConfigs[$eName][$currentValue])) {
                        if (sizeof($addRowsConfigs[$eName][$currentValue]) > 0) {
                            foreach ($addRowsConfigs[$eName][$currentValue] as $oValueName => $oValSpec) {
                                if (isset($oValSpec['addPoints']) && in_array(1, $oValSpec['addPoints'])) {
                                    $defVal = isset($sessionData['main'][$oValueName]) && $sessionData['main'][$oValueName] > 0 ? ($sessionData['main'][$oValueName] + 0) : $origDefValue;
                                    $sessionData['add_rows'][$oValueName] = $defVal;
                                }
                            }
                        }
                    }
                }
                switch ($eSpec['elementType']) {
                    case "dataModel":
                        $addStr = "";
                        $editStr = "";
                        $amdlName = $eSpec['mdlName'];
                        $aFilter = isset($eSpec['mdlFilter']) ? $eSpec['mdlFilter'] : array();
                        $disabledOption = (isset($eSpec['disabledOption']) && ($eSpec['disabledOption'] == false)) ? "" : "disabled";
                        $elStr[$eName] = "";
                        $this->CI->load->model("Mdls/" . $amdlName);
                        $labelSrc = $eSpec['labelSrc'];
                        $keySrc = $eSpec['key'];
                        $oo = new $amdlName();
                        $addLink = base_url() . "Data/add/" . str_replace("Mdl", "", $amdlName);
                        if (sizeof($aFilter) > 0) {
                            foreach ($aFilter as $filter) {
                                $exFilter = explode("=", $filter);
                                if (sizeof($exFilter) > 1) {
                                    if (substr($exFilter[1], 0, 1) == ".") {
                                        //                                        $oo->addFilter($exFilter[0] . "='" . ltrim($exFilter[1], ".") . "'");
                                    }
                                    else {
                                        if (isset($sessionData['main'][$exFilter[1]])) {
                                            $addLink .= "?reqField=" . $exFilter[0] . "&reqVal=" . $sessionData['main'][$exFilter[1]];
                                        }
                                    }
                                }
                            }
                            $oo = makeFilter($aFilter, $sessionData['main'], $oo);
                        }
                        $addClick = "";
                        $dataAccess = isset($this->CI->config->item('heDataBehaviour')[$amdlName]) ? $this->CI->config->item('heDataBehaviour')[$amdlName] : array("viewers" => array(),
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
                                        message: $('<div></div>').load('" . $addLink . "'),
                                        draggable:true,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";
                                $addStr = "<a href='javascript:void(0)' class='btn btn-tool' onclick=\"$addClick\"><span class='glyphicon glyphicon-plus'></span></a>";
                            }
                        }
                        $tmpo = $oo->lookupAll()->result();
                        $elPair[$amdlName] = array();
                        $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/fetchElement/" . $this->jenisTr . "/$eName/$amdlName/?key='+this.value";
                        $elStr[$eName] .= "<div class='box-body'>";
                        switch ($eSpec['inputType']) {
                            case "combo":
                                $elStr[$eName] .= "<select class='form-control' $disabledOption onchange=\"hiliteDiv(this);document.getElementById('result').src=$selectorTarget;\">";
                                $elStr[$eName] .= "<option value=''>-select-</option>";
                                if (sizeof($tmpo) > 0) {
                                    foreach ($tmpo as $row) {
                                        $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
                                        if (sizeof($ex) > 1) {
                                            $labelValue = "";
                                            foreach ($ex as $col) {

                                                $labelValue .= $row->$col . " / ";
                                            }
                                            $labelValue = rtrim($labelValue, " / ");
                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                            $selected = isset($sessionData['main_elements'][$eName]) && $sessionData['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                            $selected = isset($sessionData['main_elements'][$eName]) && $sessionData['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $labelValue . "</option>";
                                        }
                                        else {
                                            $elPair[$amdlName][$row->$keySrc] = $row->$labelSrc;
                                            $selected = isset($sessionData['main_elements'][$eName]) && $sessionData['main_elements'][$eName]['key'] == $row->$keySrc ? "selected" : "";
                                            $elStr[$eName] .= "<option value='" . $row->$keySrc . "' $selected>" . $row->$labelSrc . "</option>";
                                        }
                                    }
                                }
                                $elStr[$eName] .= "</select>";
                                break;
                            case "radio":
                                if (sizeof($tmpo) > 0) {
                                    foreach ($tmpo as $row) {
                                        $ex = explode("/", $elementConfigs[$eName]['labelSrc']);
                                        if (sizeof($ex) > 1) {
                                            $labelValue = "";
                                            foreach ($ex as $col) {
                                                $labelValue .= $row->$col . " / ";
                                            }
                                            $labelValue = rtrim($labelValue, " / ");
                                            $elPair[$amdlName][$row->$keySrc] = $labelValue;
                                            $selected = isset($sessionData['main_elements'][$eName]) && $sessionData['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                            $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                        <input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected $disabledOption onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . $labelValue . "</label>\n";
                                        }
                                        else {
                                            $elPair[$amdlName][$row->$keySrc] = isset($row->$labelSrc) ? $row->$labelSrc : "-";
                                            $selected = isset($sessionData['main_elements'][$eName]) && $sessionData['main_elements'][$eName]['key'] == $row->$keySrc ? "checked" : "";
                                            $elStr[$eName] .= "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                        <input type='radio' name='$eName' value='" . $row->$keySrc . "' $selected $disabledOption onclick=\"hiliteDiv(this);document.getElementById('result').src =$selectorTarget;\">" . (isset($row->$labelSrc) ? $row->$labelSrc : '-') . "</label>\n";
                                        }
                                    }
                                }
                                break;
                        }
                        $elStr[$eName] .= "</div class='box-header'>";
                        $defKey = isset($sessionData['main_elements'][$eName]['key']) ? $sessionData['main_elements'][$eName]['key'] : 0;
                        $showNull = isset($elementConfigs[$eName]['showNull']) ? $elementConfigs[$eName]['showNull'] : false;
                        $nullValue = isset($elementConfigs[$eName]['nullValue']) ? $elementConfigs[$eName]['nullValue'] : "";
                        $nullSrc = isset($elementConfigs[$eName]['nullSrc']) ? $elementConfigs[$eName]['nullSrc'] : "";
                        $defValue = "";
                        if (isset($sessionData['main_elements'][$eName]['key']) && $sessionData['main_elements'][$eName]['contents']) {
                            if (isset($elementConfigs[$eName]['usedFields']) && sizeof($elementConfigs[$eName]['usedFields']) > 0) {
                                $defValue .= "<div class='panel-body'>";
                                $defValue .= "<table cellspacing='0' cellpadding='0' border='0'>";
                                $contents[$eName] = unserialize(base64_decode($sessionData['main_elements'][$eName]['contents']));
                                $semicolonnbsp = "";
                                foreach ($elementConfigs[$eName]['usedFields'] as $src => $label) {
                                    $fieldLabel = isset($contents[$eName][$src]) ? $contents[$eName][$src] : "-";
                                    $defValue .= "<tr " . basename(__FILE__) . " " . __FUNCTION__ . " " . __LINE__ . "'>";
                                    if (strlen($fieldLabel) > 0 || $showNull == true) {
                                        if (strlen($label) > 0) {
                                            $defValue .= "<td class='text-capitalize' align='left'>$label";
                                            $defValue .= "&nbsp;</td>";
                                            $semicolonnbsp = ":&nbsp; ";
                                            $newValue_r = $fieldLabel == "" && $showNull == true && $nullValue != "" ? ($nullSrc == $label ? $nullValue : formatField($src, $fieldLabel)) : formatField($src, $fieldLabel);
                                            if ($src == 'saldo') {
                                                $arrNewValue_r = explode('+', $newValue_r);
                                                $newSaldo = 0;
                                                if (sizeof($arrNewValue_r) > 0) {
                                                    foreach ($arrNewValue_r as $k => $kVal) {
                                                        $newSaldo += $kVal;
                                                    }
                                                }
                                                $newValue_r = $newSaldo > 0 ? "<span class='text-bold text-green'>" . number_format($newSaldo) . "</span>" : "<span class='text-bold text-red'>SALDO KOSONG</span>";
                                            }
                                            $defValue .= "<td $label align='left' class='text-bold text-uppercase'>$semicolonnbsp" . $newValue_r;
                                            $defValue .= "</td>";
                                        }
                                        else {
                                            $defValue .= "<td align='left' colspan='2' class='text-bold text-uppercase'>$semicolonnbsp " . formatField($src, $fieldLabel);
                                            $defValue .= "</td>";
                                        }
                                    }
                                    $defValue .= "</tr>";
                                }
                                $defValue .= "</table>";
                                $defValue .= "</div class='panel-body'>";
                            }
                        }
                        if ($defKey > 0) {
                            if (sizeof($mems) > 0 && sizeof($dataAccess['updaters']) > 0) {
                                $editLink = base_url() . "Data/edit/" . str_replace("Mdl", "", $amdlName) . "/$defKey";
                                if (sizeof(array_intersect($mems, $dataAccess['updaters'])) > 0) {
                                    $editClick = "
                    BootstrapDialog.show(
                                   {
                                        title:'New " . $eSpec['label'] . "',
                                        message: $('<div></div>').load('" . $editLink . "'),
                                        draggable:true,
                                        size:BootstrapDialog.SIZE_WIDE,
                                        closable:true,
                                        type:top.BootstrapDialog.TYPE_SUCCESS,
                                        }
                                        );";

                                    $editStr = "<a href='javascript:void(0)' class='btn btn-tool' onclick=\"$editClick\"><span class='glyphicon glyphicon-pencil'></span></a>";
                                }
                            }
                        }
                        $elStr[$eName] .= "<div id='divel_$eName' style='padding:2px;font-size:smaller;'>$defValue";
                        $elStr[$eName] .= "</div id='el$amdlName'>";
                        $elements[$eName] = array("type" => $eSpec['inputType'],
                            "mdlName" => $eSpec['mdlName'],
                            "label" => $eSpec['label'],
                            "string" => $elStr[$eName],
                            "editStr" => $editStr,
                            "addStr" => $addStr,
                            "bgColor" => $defValue == "" ? "#fcfce0" : "#f5fff9",
                        );
                        break;
                    case "dataField":
                        $elStr[$eName] = "";
                        $initValue = isset($eSpec['defaultValue']) ? $eSpec['defaultValue'] : "";
                        $defaultValue = isset($sessionData['main'][$eName]) ? $sessionData['main'][$eName] : 0;
                        $selectorTarget = "'" . MODUL_PATH . get_class($this) . "/recordFieldElement/" . $this->jenisTr . "/$eName/$amdlName/?val='+this.value";
                        $maxValue = isset($eSpec['maxValue']) && isset($sessionData['main'][$eSpec['maxValue']]) ? $sessionData['main'][$eSpec['maxValue']] : "";
                        $elStr[$eName] .= "<div class='box-body'>";
                        switch ($eSpec['inputType']) {
                            case "text":
                                $elStr[$eName] .= "<input type=text class='form-control' value='$defaultValue' $disabledOption onfocus='this.select()' oonclick=\"this.value='$defaultValue';\" 
onblur=\"if(this.value!=this.defaultValue){if(this.value.length<1){this.value='$initValue'};hiliteDiv(this);document.getElementById('result').src=$selectorTarget;}\">";
                                break;
                            case "number":
                                $maxValStr = $maxValue != "" ? " max='" . $maxValue . "''" : "";
                                $maxValValidator = $maxValue != "" ? " onkeyup=\"if(this.value>$maxValue){this.value='$maxValue';}\" " : "";
                                $elStr[$eName] .= "<input type=number class='form-control' value='$defaultValue' $disabledOption onfocus='this.select()' $maxValStr $maxValValidator oonclick=\"this.value='$defaultValue';\" 
onblur=\"if(this.value!=this.defaultValue){if(this.value.length<1){this.value='$initValue'};hiliteDiv(this);document.getElementById('result').src=$selectorTarget;}\">";
                                break;
                            case "date":
                                $elStr[$eName] .= "<input type=date class='form-control' value='$defaultValue' $disabledOption onfocus='this.select()' oonclick=\"this.value='$defaultValue';\" 
onblur=\"if(this.value!=this.defaultValue){if(this.value.length<1){this.value='$initValue'};hiliteDiv(this);document.getElementById('result').src=$selectorTarget;}\">";
                                break;
                        }
                        $elStr[$eName] .= "</div class='box-body'>";
                        $elements[$eName] = array("mdlName" => null,
                            "label" => $eSpec['label'],
                            "string" => $elStr[$eName],
                            "editStr" => "",
                            "addStr" => "",
                            "bgColor" => $defaultValue == "" ? "#fcfce0" : "#fcfcff",
                        );
                        break;
                }
            }
        }

        //endregion

        $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor, $sessionData);


        cekMerah("start pre-processor...");

        //region auto pre-processors (item)
        $iterator = array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData["revertAuto"]["preProcAuto"]['detail']) ? $sessionData["revertAuto"]["preProcAuto"]['detail'] : array();
        }
        if (sizeof($iterator) > 0) {

            $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
            $this->tEcho("ITEM NUM LABELS");

            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $this->tEcho("sub-preproc: $comName, initializing values <br>");
                    foreach ($sessionData[$srcGateName] as $xid => $dSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $id = $xid;
                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                $subParams['static'][$key] = $realValue;

                            }
                            if (!isset($subParams['static']["transaksi_id"])) {
                                //									$subParams['static']["transaksi_id"] = $masterID;
                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $kiriman["oleh_nama"];
                        }
                        //mati_disini();
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                            $mdlName = "Pre" . ucfirst($comName);
                            $this->CI->load->model("Preprocs/" . $mdlName);
                            $m = new $mdlName($resultParams);
                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }

                            if ($tobeExecuted) {
                                $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $gotParams = $m->exec();
                                if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                    foreach ($gotParams as $gateName => $paramSpec) {
                                        cekBiru(":: getParams inject ke $gateName ::");
                                        if (!isset($sessionData[$gateName])) {
                                            $sessionData[$gateName] = array();
                                            //                                    cekhijau("building the session: $gateName");
                                        }
                                        else {
                                            //                                    cekhijau("NOT building the session: $gateName");
                                        }

                                        foreach ($paramSpec as $id => $gSpec) {
                                            //										$id=$gSpec['id'];


                                            if (!isset($sessionData[$gateName][$id])) {
                                                $sessionData[$gateName][$id] = array();
                                            }


                                            if (isset($sessionData[$gateName][$id])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                        $sessionData[$gateName][$id][$key] = $val;
                                                    }

                                                }
                                            }
                                            //==inject gotParams to child gate
                                            cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                            if (isset($sessionData[$srcGateName][$id])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $sessionData[$srcGateName][$id][$key] = $val;
                                                    }

                                                }
                                            }

                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    if (isset($sessionData[$gateName][$id][$key])) {
                                                        $sessionData[$gateName][$id]['sub_' . $key] = ($sessionData[$gateName][$id]['jml'] * $sessionData[$gateName][$id][$key]);
                                                    }
                                                }
                                            }
                                        }
                                        //                                    arrPrint($items);die();
                                    }
                                }
                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }
                        }
                    }
                }
            }
            else {
                //cekKuning("sub-preproc is not set");
            }

            $this->CI->load->helper("he_value_builder");
            $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor, $sessionData);

            //region injector gerbang value untuk pembatalan ppv dan selisih
            if (isset($sessionData["revert"]["preProc"]["replacer"])) {
                $replace = $sessionData["revert"]["preProc"]["replacer"];
                $jenisTrReference = $sessionData["main"]["jenisTr_reference"];
                switch ($jenisTrReference) {
                    case "460":
                        $tempCalculate = array(
                            //                                "selisih" => ($sessionData["main"]["hpp_riil"] + $sessionData["main"]["exchange__nilai_tambah_ppn_in"]) - ($sessionData["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                            //                                "exchange__harga" => $sessionData["main"]["hpp_riil"],//riil
                            //                                "exchange__hpp_nppv" => $sessionData["main"]["hpp_nppv"],//riil+ppv
                            //                                "exchange__ppv" => $sessionData["main"]["ppv_riil"],//riil+ppv
                        );
                        break;
                    default:
                        /*
                             * jika nilai selisih minus(-) dikalikan - supaya posisi masuk ke kredit (untung), jika rugi masuk ke kredit
                             */
                        $tempCalculate = array(
//                                "selisih" => ($sessionData["main"]["hpp"] + $sessionData["main"]["ppn"]) - ($sessionData["main"]["nett"] + $sessionData["main"]["ppv"]),
                            "selisih" => (($sessionData["main"]["hpp"] + $sessionData["main"]["ppn"]) - ($sessionData["main"]["nett"] + $sessionData["main"]["ppv"])) * -1,
                            "hpp_nppv" => $sessionData["main"]["hpp"],
                            "hpp_nppn" => $sessionData["main"]["hpp"] + $sessionData["main"]["ppn"],
                        );
                        break;
                }
                foreach ($replace['recalculate'] as $iKey => $gate) {
                    $sessionData["main"][$gate] = $tempCalculate[$gate];
                }
            }
            //endregion


        }
        else {
            $this->tEcho("no processor defined. skipping preprocessor..<br>");
        }
        //endregion

        //region auto pre-processors (master)
        $iterator = array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData["revertAuto"]["preProcAuto"]['master']) ? $sessionData["revertAuto"]["preProcAuto"]['master'] : array();
        }
        if (sizeof($iterator) > 0) {
            $tmpOutParams = array();
            $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                    $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;
                    $subParams = array();
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $sessionData['main'], $sessionData['main'], 0);
                            $subParams['static'][$key] = $realValue;

                            //                                cekPink2("$comName == $value || $realValue");
                            //                                cekPink2("valas_harga " . $sessionData['main']['valas_harga']);
                            //                                cekPink2("uang_muka_valas_harga " . $sessionData['main']['uang_muka_valas_harga']);
                        }
                        if (!isset($subParams['static']["transaksi_id"])) {
                            //									$subParams['static']["transaksi_id"] = $masterID;
                        }
                        $subParams['static']["fulldate"] = date("Y-m-d");
                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $kiriman["oleh_nama"];
                    }
                    $tmpOutParams[$cCtr] = $subParams;
                    $mdlName = "Pre" . ucfirst($comName);
                    $this->CI->load->model("Preprocs/" . $mdlName);
                    $m = new $mdlName($resultParams);
                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        $tobeExecuted = true;
                    }
                    else {
                        $tobeExecuted = false;
                    }
                    if ($tobeExecuted) {
                        $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $gotParams = $m->exec();
                        if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                            foreach ($gotParams as $gateName => $gSpec) {
                                //										$id=$gSpec['id'];

                                if ($switchResultParams == true) {

                                    foreach ($gSpec as $id => $ggSpec) {
                                        if (!isset($sessionData[$gateName][$id])) {
                                            $sessionData[$gateName][$id] = array();
                                        }

                                        if (isset($sessionData[$gateName][$id])) {
                                            if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                foreach ($ggSpec as $key => $val) {
                                                    $sessionData[$gateName][$id][$key] = $val;
                                                }
                                            }
                                        }

                                        //cekMerah("REBUILDING VALUES..");
                                        if (sizeof($itemNumLabels) > 0) {
                                            //cekHijau("REBUILDING SUBS FOR ITEMS");
                                            foreach ($itemNumLabels as $key => $label) {
                                                //cekHere("$id === $key => $label");
                                                if (isset($sessionData[$gateName][$id][$key])) {
                                                    $sessionData[$gateName][$id]['sub_' . $key] = ($sessionData[$gateName][$id]['jml'] * $sessionData[$gateName][$id][$key]);
                                                }
                                            }
                                        }

                                    }
                                }
                                else {
                                    if (isset($sessionData['main'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $sessionData['main'][$key] = $val;
                                            }
                                        }
                                    }
                                    //==inject gotParams to child gate
                                    if (isset($sessionData['main'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $sessionData['main'][$key] = $val;
                                            }
                                        }
                                    }
                                    //cekMerah("REBUILDING VALUES..");
                                    if (sizeof($itemNumLabels) > 0) {
                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                        foreach ($itemNumLabels as $key => $label) {

                                            if (isset($sessionData['main'][$key])) {
                                                $sessionData['main']['sub_' . $key] = ($sessionData['main']['jml'] * $sessionData['main'][$key]);
                                            }
                                        }
                                    }
                                }

                            }
                        }
                    }
                    else {
                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                    }
                    $this->CI->load->helper("he_value_builder");
                    $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor, $sessionData);
                }
            }
            else {
                //cekKuning("sub-preproc is not set");
            }

            $this->CI->load->helper("he_value_builder");
            $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor, $sessionData);
        }
        else {
            $this->tEcho("no processor defined. skipping preprocessor..<br>");
        }
        //endregion


        //region pre-processors (item)
        $iterator = array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revert']['preProc']['detail']) ? $sessionData['revert']['preProc']['detail'] : array();
            cekMerah(":: iterator preprocc dari gerbang revert ::");
            arrPrintWebs($iterator);
        }
        else {
            $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['detail'] : array();
        }
        if (sizeof($iterator) > 0) {
            $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $this->tEcho("sub-preproc: $comName, initializing values <br>");
                    foreach ($sessionData[$srcGateName] as $xid => $dSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $id = $xid;
                        $subParams = array();
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                $subParams['static'][$key] = $realValue;

                            }
                            if (!isset($subParams['static']["transaksi_id"])) {
                                //									$subParams['static']["transaksi_id"] = $masterID;
                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $kiriman["oleh_nama"];
                        }
                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                            $mdlName = "Pre" . ucfirst($comName);
                            $this->CI->load->model("Preprocs/" . $mdlName);
                            $m = new $mdlName($resultParams);
                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }
                            if ($tobeExecuted) {
                                $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $gotParams = $m->exec();
                                if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor

                                    foreach ($gotParams as $gateName => $paramSpec) {
                                        cekBiru(":: getParams inject ke $gateName ::");
                                        if (!isset($sessionData[$gateName])) {
                                            $sessionData[$gateName] = array();
                                            //                                    cekhijau("building the session: $gateName");
                                        }
                                        else {
                                            //                                    cekhijau("NOT building the session: $gateName");
                                        }

                                        foreach ($paramSpec as $id => $gSpec) {
                                            //										$id=$gSpec['id'];


                                            if (!isset($sessionData[$gateName][$id])) {
                                                $sessionData[$gateName][$id] = array();
                                            }


                                            if (isset($sessionData[$gateName][$id])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                        $sessionData[$gateName][$id][$key] = $val;
                                                    }

                                                }
                                            }
                                            //==inject gotParams to child gate
                                            cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                            if (isset($sessionData[$srcGateName][$id])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $sessionData[$srcGateName][$id][$key] = $val;
                                                    }

                                                }
                                            }

                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    if (isset($sessionData[$gateName][$id][$key])) {
                                                        $sessionData[$gateName][$id]['sub_' . $key] = ($sessionData[$gateName][$id]['jml'] * $sessionData[$gateName][$id][$key]);
                                                    }
                                                }
                                            }
                                        }
                                        //                                    arrPrint($items);die();
                                    }


                                }
                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }
                        }
                    }
                }
            }
            else {
                //cekKuning("sub-preproc is not set");
            }
            $referensi_dibatalkan = isset($sessionData["main"]["pihakExternMasterID"]) ? $sessionData["main"]["pihakExternMasterID"] : NULL;

            $this->CI->load->helper("he_value_builder");
            $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor, $sessionData);

            //region injector gerbang value untuk pembatalan ppv dan selisih
            if (isset($sessionData["revert"]["preProc"]["replacer"])) {
                $replace = $sessionData["revert"]["preProc"]["replacer"];
                $jenisTrReference = $sessionData["main"]["jenisTr_reference"];
                switch ($jenisTrReference) {
                    case "460":
                        $tempCalculate = array(
                            //                                "selisih" => ($sessionData["main"]["hpp_riil"] + $sessionData["main"]["exchange__nilai_tambah_ppn_in"]) - ($sessionData["main"]["exchange__nilai_tambah_piutang_pembelian"]),
                            //                                "exchange__harga" => $sessionData["main"]["hpp_riil"],//riil
                            //                                "exchange__hpp_nppv" => $sessionData["main"]["hpp_nppv"],//riil+ppv
                            //                                "exchange__ppv" => $sessionData["main"]["ppv_riil"],//riil+ppv
                        );
                        break;
                    case "461":
                        $tempCalculate = array(// selisih dari nilai nota pembelian dengan stok yang diambil pembatalan....
                            "selisih" => (($sessionData["main"]["hpp"] + $sessionData["main"]["ppn"]) - ($sessionData["main"]["nett"])) * -1,
                            "hpp_nppv" => $sessionData["main"]["hpp"],
                            "hpp_nppn" => $sessionData["main"]["hpp"] + $sessionData["main"]["ppn"],
                        );
                        break;
                    case "3333":
                        if ($sessionData["main"]["hpp"] > 0) {
                            $tempCalculate = array(
                                "selisih" => (($sessionData["main"]["hpp"] + $sessionData["main"]["ppn"]) - ($sessionData["main"]["nilai_cancel"] + $sessionData["main"]["ppv"])) * -1,
                                "hpp_nppv" => $sessionData["main"]["hpp"],
                                "hpp_nppn" => $sessionData["main"]["hpp"] + $sessionData["main"]["ppn"],
                                "nilai_persediaan" => $sessionData["main"]["hpp"],
                            );
                            $sessionData["main"]["nilai_persediaan"] = $sessionData["main"]["hpp"];
                        }
                        break;
                    default:
                        /*
                             * jika nilai selisih minus(-) dikalikan - supaya posisi masuk ke kredit (untung), jika rugi masuk ke kredit
                             */
                        $tempCalculate = array(
                            "selisih" => (($sessionData["main"]["hpp"] + $sessionData["main"]["ppn"]) - ($sessionData["main"]["nett"] + $sessionData["main"]["ppv"])) * -1,
                            "hpp_nppv" => $sessionData["main"]["hpp"],
                            "hpp_nppn" => $sessionData["main"]["hpp"] + $sessionData["main"]["ppn"],
                        );
                        break;
                }
//                    cekHitam($sessionData["main"]["hpp"]."+".$sessionData["main"]["ppn"]." - ". $sessionData["main"]["nett"]." + ".$sessionData["main"]["ppv"]);
//                    arrPrint($tempCalculate);
//                    matiHere();
                //                    $tempCalculate = array(
                //                        "selisih" => ($sessionData["main"]["hpp"] + $sessionData["main"]["ppn"]) - ($sessionData["main"]["nett"] + $sessionData["main"]["ppv"]),
                //                        "hpp_nppv" => $sessionData["main"]["hpp"],
                //                        "hpp_nppn" => $sessionData["main"]["hpp"] + $sessionData["main"]["ppn"],
                //                    );

//                    arrPrintWebs($tempCalculate);
                foreach ($replace['recalculate'] as $iKey => $gate) {
                    $sessionData["main"][$gate] = $tempCalculate[$gate];
                }

//                    cekLime($sessionData["main"]["hpp"] . "+" . $sessionData["main"]["ppn"] . "-" . $sessionData["main"]["nett"]);

            }

            //endregion

        }
        else {
            $this->tEcho("no processor defined. skipping preprocessor..<br>");
        }
        //endregion

        //region presub detail
        $iterator = array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revert']['preProc']['sub_detail']) ? $sessionData['revert']['preProc']['sub_detail'] : array();
//                cekMerah(":: iterator preprocc dari gerbang revert ::");
//                arrPrintWebs($iterator);
        }
        else {
            $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['sub_detail']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['sub_detail'] : array();
        }
        if (sizeof($iterator) > 0) {
            $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields'][$stepNum]) ? $configUiMasterModulJenis['shoppingCartNumFields'][$stepNum] : array();
            if (sizeof($iterator) > 0) {
                $this->tEcho("<script>top.writeProgress('PERSIAPAN PRE-PROCESSOR...', 'HEAD');</script>");
                $tmpOutParams = array();
                foreach ($iterator as $cCtrX => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (isset($sessionData[$srcGateName]) && count($sessionData[$srcGateName]) > 0) {
                        foreach ($sessionData[$srcGateName] as $xiID => $aDSpec) {
                            $cCtr = 0;
                            foreach ($aDSpec as $xid => $dSpec) {
                                $cCtr++;
                                $tmpOutParams[$cCtr] = array();
                                $id = $xid;
                                $subParams = array();
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $dSpec, $dSpec, 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                        $jenis = $sessionData['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                        }
                                    }
                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                                }
                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }

                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                $this->tEcho("sub preproc #: $comName, sending values <br>");
                                $mdlName = "Pre" . ucfirst($comName);
                                $this->CI->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);
                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }
                                if ($tobeExecuted) {
                                    $m->pair($masterID, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $gotParams = $m->exec();
                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                        foreach ($gotParams as $gateName => $paramSpec) {
                                            if (!isset($sessionData[$gateName])) {
                                                $sessionData[$gateName] = array();
                                            }
                                            foreach ($paramSpec as $idx => $gSpec) {
                                                if (!isset($sessionData[$gateName][$xiID][$idx])) {
                                                    $sessionData[$gateName][$xiID][$idx] = array();
                                                }
                                                if (isset($sessionData[$gateName][$xiID][$idx])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $sessionData[$gateName][$xiID][$idx][$key] = $val;
                                                        }
                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                if ($gateName == $srcGateName) {
                                                    if (isset($sessionData[$srcGateName][$xiID][$idx])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $sessionData[$srcGateName][$xiID][$idx][$key] = $val;
                                                            }
                                                        }
                                                    }
                                                }
                                                if (sizeof($itemNumLabels) > 0) {
                                                    matiHere(__LINE__);
                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        $sessionData[$gateName][$xiID][$idx]['sub_' . $key] = ($sessionData[$gateName][$xiID][$idx]['jml'] * $sessionData[$gateName][$xiID][$idx][$key]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }
                            }
                        }
                        $this->CI->load->helper("he_value_builder");
                        $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor, $sessionData);
                    }
                    else {
                        cekBiru("skip tidak memenuhi syaraty untuk ditulis");
                    }
                }
            }
            else {
                //cekKuning("sub-preproc is not set");
            }
        }
        //endregion

        //region pre-processors (master)
        $iterator = array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revert']['preProc']['master']) ? $sessionData['revert']['preProc']['master'] : array();
        }
        else {
            $iterator = isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]['master'] : array();
        }

        if (sizeof($iterator) > 0) {
            $tmpOutParams = array();
            $itemNumLabels = isset($configUiMasterModulJenis['shoppingCartNumFields']) ? $configUiMasterModulJenis['shoppingCartNumFields'] : array();
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                    $switchResultParams = isset($tComSpec['switchResultParams']) ? $tComSpec['switchResultParams'] : false;
                    $subParams = array();
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $sessionData['main'], $sessionData['main'], 0);
                            $subParams['static'][$key] = $realValue;

                            //                                cekPink2("$comName == $value || $realValue");
                            //                                cekPink2("valas_harga " . $sessionData['main']['valas_harga']);
                            //                                cekPink2("uang_muka_valas_harga " . $sessionData['main']['uang_muka_valas_harga']);
                        }
                        if (!isset($subParams['static']["transaksi_id"])) {
                            //									$subParams['static']["transaksi_id"] = $masterID;
                        }
                        $subParams['static']["fulldate"] = date("Y-m-d");
                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $kiriman["oleh_nama"];
                    }
                    $tmpOutParams[$cCtr] = $subParams;

                    $mdlName = "Pre" . ucfirst($comName);
                    $this->CI->load->model("Preprocs/" . $mdlName);
                    $m = new $mdlName($resultParams);

                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        $tobeExecuted = true;
                    }
                    else {
                        $tobeExecuted = false;
                    }

                    if ($tobeExecuted) {
                        $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $gotParams = $m->exec();
                        if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                            foreach ($gotParams as $gateName => $gSpec) {
                                if ($switchResultParams == true) {
                                    foreach ($gSpec as $id => $ggSpec) {
                                        if (!isset($sessionData[$gateName][$id])) {
                                            $sessionData[$gateName][$id] = array();
                                        }
                                        if (isset($sessionData[$gateName][$id])) {
                                            if (is_array($ggSpec) && sizeof($ggSpec) > 0) {
                                                foreach ($ggSpec as $key => $val) {
                                                    $sessionData[$gateName][$id][$key] = $val;
                                                }
                                            }
                                        }
                                        //cekMerah("REBUILDING VALUES..");
                                        if (sizeof($itemNumLabels) > 0) {
                                            //cekHijau("REBUILDING SUBS FOR ITEMS");
                                            foreach ($itemNumLabels as $key => $label) {
                                                if (isset($sessionData[$gateName][$id][$key])) {
                                                    $sessionData[$gateName][$id]['sub_' . $key] = ($sessionData[$gateName][$id]['jml'] * $sessionData[$gateName][$id][$key]);
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    if (isset($sessionData['main'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $sessionData['main'][$key] = $val;
                                            }
                                        }
                                    }
                                    //==inject gotParams to child gate
                                    if (isset($sessionData['main'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $sessionData['main'][$key] = $val;
                                            }
                                        }
                                    }
                                    //cekMerah("REBUILDING VALUES..");
                                    if (sizeof($itemNumLabels) > 0) {
                                        foreach ($itemNumLabels as $key => $label) {
                                            if (isset($sessionData['main'][$key])) {
                                                $sessionData['main']['sub_' . $key] = ($sessionData['main']['jml'] * $sessionData['main'][$key]);
                                            }
                                        }
                                    }
                                }

                            }
                        }
                    }
                    else {
                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                    }

                    cekPink2("fillvalue setelah $comName");
                    $this->CI->load->helper("he_value_builder");
                    $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor, $sessionData);


                }
            }
            else {
                //cekKuning("sub-preproc is not set");
            }
            $this->CI->load->helper("he_value_builder");
            $sessionData = fillValues_he_value_builder_ns($this->jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor, $sessionData);
        }
        else {
            $this->tEcho("no processor defined. skipping preprocessor..<br>");
        }
        //endregion


        $this->CI->load->library("Validator");
        $vd = new Validator();
        $vd->setCCode($this->cCode);
        $vd->setConfigUiJenis($configUiMasterModulJenis);
        $step = $sessionData['main']['step_number'];
        $vd->midValidate($step);
        $vd->unionValidate();


        //region penomoran receipt
        $this->CI->load->model("CustomCounter");
        $cn = new CustomCounter("transaksi");
        $cn->setType("transaksi");
        $cn->setModul($modul_transaksi);
        $cn->setStepCode($tCodeTargetJenisTransaksi);
        $counterForNumber = array($configCoreMasterModulJenis['formatNota']);
        arrPrintWebs($counterForNumber);
        if (!in_array($counterForNumber[0], $configCoreMasterModulJenis['counters'])) {
            die(__LINE__ . " Used number should be registered in 'counters' config as well");
        }
        $this->tEcho("<div style='background:#ff7766;'>");
        foreach ($counterForNumber as $i => $cRawParams) {
            $cParams = explode("|", $cRawParams);
            $cValues = array();
            foreach ($cParams as $param) {
                $cValues[$i][$param] = $sessionData['main'][$param];
            }
            $cRawValues = implode("|", $cValues[$i]);
            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);
        }
        $this->tEcho("</div style='background:#ff7766;'>");
        $stepNumber = 1;
        $tmpNomorNota = $paramSpec['paramString'];
        $tmpNomorNotaAlias = formatNota("nomer_nolink", $tmpNomorNota);
        cekHitam("[$tCodeTargetJenisTransaksi] [$tmpNomorNota]");
//        mati_disini(__LINE__);

        if (isset($configUiMasterModulJenis['steps'][2])) {
            $nextProp = array(
                "num" => 2,
                "code" => $configUiMasterModulJenis['steps'][2]['target'],
                "label" => $configUiMasterModulJenis['steps'][2]['label'],
                "groupID" => $configUiMasterModulJenis['steps'][2]['userGroup'],
            );
        }
        else {
            $nextProp = array(
                "num" => 0,
                "code" => "",
                "label" => "",
                "groupID" => "",
            );
        }
        //endregion

        //region dynamic counters
        $cn = new CustomCounter("transaksi");
        $cn->setType("transaksi");
        $cn->setModul($modul_transaksi);
        $cn->setStepCode($tCodeTargetJenisTransaksi);
        $configCustomParams = $configCoreMasterModulJenis['counters'];
        $configCustomParams[] = "stepCode";
        if (sizeof($configCustomParams) > 0) {
            $cContent = array();
            foreach ($configCustomParams as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $sessionData['main'][$param];
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
            }
        }
        $appliedCounters = base64_encode(serialize($cContent));
        $appliedCounters_inText = print_r($cContent, true);

        //region addition on master
        $addValues = array(
            'counters' => $appliedCounters,
            'counters_intext' => $appliedCounters_inText,
            'nomer' => $tmpNomorNota,
            'nomer2' => $tmpNomorNotaAlias,
            'dtime' => date("Y-m-d H:i:s"),
            'fulldate' => date("Y-m-d"),
            "step_avail" => sizeof($configUiMasterModulJenis['steps']),
            "step_number" => 1,
            "step_current" => 1,
            "next_step_num" => $nextProp['num'],
            "next_step_code" => $nextProp['code'],
            "next_step_label" => $nextProp['label'],
            "next_group_code" => $nextProp['groupID'],
            "tail_number" => 1,
            "tail_code" => $configUiMasterModulJenis['steps'][1]['target'],
        );
        foreach ($addValues as $key => $val) {
            $sessionData['tableIn_master'][$key] = $val;
        }
        //endregion

        //region addition on detail
        $addSubValues = array(
            "sub_step_number" => 1,
            "sub_step_current" => 1,
            "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
            "next_substep_num" => $nextProp['num'],
            "next_substep_code" => $nextProp['code'],
            "next_substep_label" => $nextProp['label'],
            "next_subgroup_code" => $nextProp['groupID'],
            "sub_tail_number" => 1,
            "sub_tail_code" => $configUiMasterModulJenis['steps'][1]['target'],
        );
        foreach ($sessionData['tableIn_detail'] as $id => $dSpec) {
            foreach ($addSubValues as $key => $val) {
                $sessionData['tableIn_detail'][$id][$key] = $val;
            }
        }
        //endregion

        //endregion

        //region numbering tambahan
        $this->CI->load->library("CounterNumber");
        $ccn = new CounterNumber();
        $ccn->setCCode($this->cCode);
        $ccn->setJenisTr($this->jenisTr);
        $ccn->setTransaksiGate($sessionData['tableIn_master']);
        $ccn->setMainGate($sessionData['main']);
        $ccn->setItemsGate($sessionData['items']);
        $ccn->setItems2SumGate($sessionData['items2_sum']);
        $new_counter = $ccn->getCounterNumber();
        cekHitam("jenistr yang disett dari create " . $this->jenisTr);
        if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
            foreach ($new_counter['main'] as $ckey => $cval) {
                $sessionData['tableIn_master'][$ckey] = $cval;
                $sessionData['main'][$ckey] = $cval;
            }
        }
        if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
            foreach ($new_counter['items'] as $ikey => $iSpec) {
                foreach ($iSpec as $iikey => $iival) {
                    $sessionData['items'][$ikey][$iikey] = $iival;
                }
            }
        }
        if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
            foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                foreach ($iSpec as $iikey => $iival) {
                    $sessionData['items2_sum'][$ikey][$iikey] = $iival;
                }
            }
        }
        //endregion

        $pakai_ini_cli = 1;
        //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
        if (isset($sessionData['tableIn_master']) && sizeof($sessionData['tableIn_master']) > 0) {
            $sessionData['tableIn_master']['status_4'] = 11;
            $sessionData['tableIn_master']['trash_4'] = 0;
            if ($pakai_ini_cli == 1) {
                $sessionData['tableIn_master']['cli'] = 1;
            }
            else {
                $sessionData['tableIn_master']['cli'] = 0;
            }
            //-------
            $sessionData['tableIn_master']['reference_id'] = $sessionData['main']['transaksi'];
            $sessionData['tableIn_master']['reference_nomer'] = $sessionData['main']['transaksi__nomer'];
            $sessionData['tableIn_master']['reference_jenis'] = $sessionData['main']['transaksi__jenis'];
            $sessionData['tableIn_master']['reference_id_top'] = "";
            $sessionData['tableIn_master']['reference_nomer_top'] = "";
            $sessionData['tableIn_master']['reference_jenis_top'] = "";
            //-------
            $sessionData['tableIn_master']['jenis'] = $this->jenisTr;
            //-------

            $tr = new MdlTransaksi();
            $insertID = $tr->writeMainEntries($sessionData['tableIn_master']);
            cekHitam($this->CI->db->last_query());
            $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $sessionData['tableIn_master']);
            $insertNum = $sessionData['tableIn_master']['nomer'];
            $sessionData['main']['nomer'] = $insertNum;
            if ($insertID < 1) {
                die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
            }
            $mongoList['main'] = array($insertID, $epID);
            //==transaksi_id dan nomor nota diinject kan ke gate utama
            $injectors = array(
                "transaksi_id" => $insertID,
                "nomer" => $tmpNomorNota,
                "nomer2" => $tmpNomorNotaAlias,
            );
            $arrInjectorsTarget = array(
                "items",
                "items2_sum",
                "rsltItems",
            );
            foreach ($injectors as $key => $val) {
                $sessionData['main'][$key] = $val;
                foreach ($arrInjectorsTarget as $target) {
                    if (isset($sessionData[$target])) {
                        foreach ($sessionData[$target] as $xid => $iSpec) {
                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                            if (isset($sessionData[$target][$id])) {
                                $sessionData[$target][$id][$key] = $val;
                            }
                        }
                    }
                }
            }

            $dwsign = $tr->writeSignature($insertID, array(
                "nomer" => $sessionData['main']['nomer'],
                "step_number" => 1,
                "step_code" => $this->jenisTr,
                "step_name" => $configUiMasterModulJenis['steps'][1]['label'],
                "group_code" => $configUiMasterModulJenis['steps'][1]['userGroup'],
                "oleh_id" => $kiriman["oleh_id"],
                "oleh_nama" => $kiriman["oleh_nama"],
                "keterangan" => $configUiMasterModulJenis['steps'][1]['label'] . " oleh " . $kiriman["oleh_nama"],
                "transaksi_id" => $insertID,
            )) or die("Failed to write signature");
            $mongoList['sign'][] = $dwsign;
            $idHis = array(
                $stepNumber => array(
                    "olehID" => $sessionData['main']['olehID'],
                    "olehName" => $sessionData['main']['olehName'],
                    "step" => $stepNumber,
                    "trID" => $insertID,
                    "nomer" => $tmpNomorNota,
                    "nomer2" => $tmpNomorNotaAlias,
                    "counters" => $appliedCounters,
                    "counters_intext" => $appliedCounters_inText,
                ),
            );
            $idHis_blob = blobEncode($idHis);
            $idHis_intext = print_r($idHis, true);
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
                "nomer_top" => $sessionData['main']['nomer'],
                "nomers_prev" => "",
                "nomers_prev_intext" => "",
                "jenises_prev" => "",
                "jenises_prev_intext" => "",
                "ids_his" => $idHis_blob,
                "ids_his_intext" => $idHis_intext,

            )) or die("Failed to update tr next-state!");
            cekHijau($this->CI->db->last_query());

            $addValues = array(
                //===references
                "id_master" => $insertID,
                "id_top" => $insertID,
                "ids_prev" => "",
                "ids_prev_intext" => "",
                "nomer_top" => $sessionData['main']['nomer'],
                "nomers_prev" => "",
                "nomers_prev_intext" => "",
                "jenises_prev" => "",
                "jenises_prev_intext" => "",
                "ids_his" => $idHis_blob,
                "ids_his_intext" => $idHis_intext,
            );
            foreach ($addValues as $key => $val) {
                $sessionData['tableIn_master'][$key] = $val;
            }

        }
        if (isset($sessionData['tableIn_master_values']) && sizeof($sessionData['tableIn_master_values']) > 0) {
            $inserMainValues = array();
            if (isset($configCoreMasterModulJenis['tableIn']['mainValues'])) {
                $inserMainValues = array();
                foreach ($configCoreMasterModulJenis['tableIn']['mainValues'] as $key => $src) {
                    if (isset($sessionData['tableIn_master_values'][$key])) {
                        $dd = $tr->writeMainValues($insertID, array(
                            "key" => $key,
                            "value" => $sessionData['tableIn_master_values'][$key],
                        ));
                        $inserMainValues[] = $dd;
                        $mongoList['mainValues'][] = $dd;
                    }
                }
            }

            if (sizeof($inserMainValues) > 0) {
                $arrBlob = blobEncode($inserMainValues);
                $this->CI->db->last_query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
            }

        }
        if (isset($sessionData['main_add_values']) && sizeof($sessionData['main_add_values']) > 0) {
            $inserMainValues = array();
            foreach ($sessionData['main_add_values'] as $key => $val) {
                $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                $inserMainValues[] = $dd;
                $mongoList['mainValues'][] = $dd;
            }
            if (sizeof($inserMainValues) > 0) {
                $arrBlob = blobEncode($inserMainValues);
                $this->CI->db->last_query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
            }
        }
        if (isset($sessionData['main_inputs']) && sizeof($sessionData['main_inputs']) > 0) {
            foreach ($sessionData['main_inputs'] as $key => $val) {
                $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
            }
        }
        if (isset($sessionData['main_add_fields']) && sizeof($sessionData['main_add_fields']) > 0) {
            foreach ($sessionData['main_add_fields'] as $key => $val) {
                $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
            }
        }
        if (isset($sessionData['main_applets']) && sizeof($sessionData['main_applets']) > 0) {
            foreach ($sessionData['main_applets'] as $amdl => $aSpec) {
                $tr->writeMainApplets($insertID, array(
                    "mdl_name" => $amdl,
                    "key" => $aSpec['key'],
                    "label" => $aSpec['labelValue'],
                    "description" => $aSpec['description'],
                ));
            }
        }
        if (isset($sessionData['main_elements']) && sizeof($sessionData['main_elements']) > 0) {
            foreach ($sessionData['main_elements'] as $elName => $aSpec) {
                $tr->writeMainElements($insertID, array(
                    "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                    "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                    "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                    "name" => $aSpec['name'],
                    "label" => $aSpec['label'],
                    "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                ));
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
                    }
                }
            }
        }
        if (isset($sessionData['tableIn_detail']) && sizeof($sessionData['tableIn_detail']) > 0) {
            $insertIDs = array();
            $insertDeIDs = array();
            foreach ($sessionData['tableIn_detail'] as $dSpec) {
                $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                if ($insertDetailID < 1) {
                    die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                }
                else {
                    $insertIDs[] = $insertDetailID;
                    $insertDeIDs[$insertID][] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                }
                if ($epID != 999) {
                    $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                    if ($insertEpID < 1) {
                        die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                    }
                    else {
                        $insertIDs[] = $insertEpID;
                        $insertDeIDs[$epID][] = $insertEpID;
                        $mongoList['detail'][] = $insertEpID;
                    }
                }
                cekUngu($this->CI->db->last_query());
            }
            if (sizeof($insertIDs) == 0) {
                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
            }
            else {
                $indexing_details = array();
                foreach ($insertDeIDs as $key => $numb) {
                    $indexing_details[$key] = $numb;
                }
                foreach ($indexing_details as $k => $arrID) {
                    $arrBlob = blobEncode($arrID);
                    $this->CI->db->last_query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                    cekOrange($this->CI->db->last_query());
                }
            }
        }
        else {
            die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
        }
        if (isset($sessionData['tableIn_detail2']) && sizeof($sessionData['tableIn_detail2']) > 0) {
            $insertIDs = array();
            foreach ($sessionData['tableIn_detail2'] as $dSpec) {
                $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                $mongoList['detail'] = $insertIDs;
                if ($epID != 999) {
                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                    $mongoList['detail'] = $insertIDs;
                }
                cekUngu($this->CI->db->last_query());
            }
        }
        if (isset($sessionData['tableIn_detail2_sum']) && sizeof($sessionData['tableIn_detail2_sum']) > 0) {
            $insertIDs = array();
            foreach ($sessionData['tableIn_detail2_sum'] as $dSpec) {
                $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                $insertIDs[] = $insertDetailID;
                $mongoList['detail'][] = $insertDetailID;
                if ($epID != 999) {
                    $dd = $tr->writeDetailEntries($epID, $dSpec);
                    $insertIDs[] = $dd;
                    $mongoList['detail'][] = $dd;
                }
            }
        }
        if (isset($sessionData['tableIn_detail_rsltItems']) && sizeof($sessionData['tableIn_detail_rsltItems']) > 0) {
            $insertIDs = array();
            foreach ($sessionData['tableIn_detail_rsltItems'] as $dSpec) {
                $dd = $tr->writeDetailEntries($insertID, $dSpec);
                $insertIDs[] = $dd;
                $mongoList['detil'][] = $dd;
                if ($epID != 999) {
                    $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                    $mongoList['detil'] = $insertIDs;
                }
                cekUngu($this->CI->db->last_query());
            }
        }
        if (isset($sessionData['tableIn_detail_values']) && sizeof($sessionData['tableIn_detail_values']) > 0) {
            $insertIDs = array();
            foreach ($sessionData['tableIn_detail_values'] as $pID => $dSpec) {
                if (isset($configCoreMasterModulJenis['tableIn']['detailValues'])) {
                    foreach ($configCoreMasterModulJenis['tableIn']['detailValues'] as $key => $src) {
                        if (isset($sessionData['tableIn_detail'][$pID])) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $sessionData['tableIn_detail'][$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => isset($dSpec[$src]) ? $dSpec[$src] : "0",
                            ));
                            $insertIDs[$pID][] = $dd;
                            $mongoList['detailValues'][] = $dd;
                        }
                    }
                }
            }
            if (sizeof($insertIDs) > 0) {
                $arrBlob = blobEncode($insertIDs);
                $this->CI->db->last_query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
            }
        }
        if (isset($sessionData['tableIn_detail_values2_sum']) && sizeof($sessionData['tableIn_detail_values2_sum']) > 0) {
            foreach ($sessionData['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                if (isset($configCoreMasterModulJenis['tableIn']['detailValues2_sum'])) {
                    $insertIDs = array();
                    foreach ($configCoreMasterModulJenis['tableIn']['detailValues2_sum'] as $key => $src) {
                        $dd = $tr->writeDetailValues($insertID, array(
                            "produk_jenis" => $sessionData['tableIn_detail2_sum'][$pID]['produk_jenis'],
                            "produk_id" => $pID,
                            "key" => $key,
                            "value" => $dSpec[$src],
                        ));
                        $insertIDs[] = $dd;
                        $mongoList['detailValues'][] = $dd;
                    }
                }
            }
        }
        //endregion

        if (isset($sessionData['tableIn_master_eppoint']) && sizeof($sessionData['tableIn_master_eppoint']) > 0) {
            cekHitam("CLONING EP POINT REFERENCE");
            $arrReplacerEP = array(
                "oleh_id" => $kiriman["oleh_id"],
                "oleh_nama" => $kiriman["oleh_nama"],
                "cancel_id" => $kiriman["oleh_id"],
                "cancel_name" => $kiriman["oleh_nama"],
                "cancel_dtime" => date("Y-m-d H:i:s"),
                "deskripsi" => "pembatalan transaksi",
            );
            foreach ($arrReplacerEP as $keyy => $vall) {
                $sessionData['tableIn_master_eppoint'][$keyy] = $vall;
            }
            $tr = new MdlTransaksi();
            $epID = $tr->writeMainEntries_entryPoint($insertID, $sessionData['main']['referenceID_master'], $sessionData['tableIn_master_eppoint']);
            cekHitam("[$epID]");
            showLast_query("hitam");

        }

        if (isset($sessionData['main']['referenceID']) && ($sessionData['main']['referenceID'] > 0)) {
            $arrReplacerEP = array(
                "cancel_id" => $kiriman["oleh_id"],
                "cancel_name" => $kiriman["oleh_nama"],
                "cancel_dtime" => date("Y-m-d H:i:s"),
                "cancel_transaksi_id" => $insertID,
                "cancel_transaksi_nomer" => $tmpNomorNota,
                "cancel_transaksi_jenis" => $this->jenisTr,
                "deskripsi" => "pembatalan transaksi",
            );
            $where_update = array(
                "id" => $sessionData['main']['referenceID'],
            );
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->updateData($where_update, $arrReplacerEP);
            showLast_query("orange");

        }


        //region awal processing sub-components, if in single step geser ke CLI
        $componentGate['detail'] = array();
        $componentConfig['detail'] = array();
        $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
        $filterNeeded = false;
        $iterator = array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revertAwal']['jurnalAwal']['detail']) ? $sessionData['revertAwal']['jurnalAwal']['detail'] : array();
            $revertedTarget = $sessionData['main']['pihakExternID'];
        }

        $tmpOutParams = array();
        $componentConfig['detail'] = $iterator;
        if ($pakai_ini_cli == 1) {
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $tmpOutParams[$cCtr] = array();
                    $gg = 0;
                    $srcGateName = $tComSpec['srcGateName'];
                    if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {

                        foreach ($sessionData[$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                //                            $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
                                cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
                                $comName = str_replace($comName, $sessionData[$srcGateName][$id][$comName], $comName);
                            }
                            cekHitam(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                //cekLime($mdlName. "line");
                                $filterNeeded = true;
                            }
                            else {
                                cekLime($mdlName . "like");
                                $filterNeeded = false;
                            }
                            $this->tEcho("sub-component: $comName, initializing values <br>");
                            //                        cekHitam(__LINE__);
                            //                        $tmpOutParams[$cCtr] = array();

                            //                        cekhitam("$comName filterneeded: $filterNeeded");
                            //                        cekhitam("mau mengiterasi $srcGateName");
                            //                        cekhitam("telah mengiterasi $srcGateName");
                            //
                            $subParams = array();
                            //arrPrint($tComSpec);
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    cekMerah(":: $key => $value ::");
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        //                                    $key = str_replace($key, $sessionData['main'][$key], $key);
                                        $key = str_replace($key, $sessionData[$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                                if (strlen($revertedTarget) > 1) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
                            //arrPrint($subParams);
                            if (sizeof($subParams) > 0) {
                                //                            arrprint($subParams);
                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                    //                                CekHijiau("asem" .$gg++);
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                }
                cekHitam("cetak tmpOutParams");
//                    $cCtr=0;

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    cekMerah($srcGateName);
//                        foreach ($sessionData[$srcGateName] as $id => $dSpec) {
//                            $srcRawGateName = $tComSpec['srcRawGateName'];
//                            $comName = $tComSpec['comName'];
//                            cekHitam($comName);
//                            if (substr($comName, 0, 1) == "{") {
//                                $comName = trim($comName, "{");
//                                $comName = trim($comName, "}");
//                                $comName = str_replace($comName, $sessionData[$srcGateName][$id][$comName], $comName);
//                                //                        $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
//                            }
//                        }
                    $this->tEcho("*sub component: $comName, sending values <br>");
//                        echo "*sub component: $srcGateName, sending values <br>";
                    if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {

                        $mdlName = "Com" . ucfirst($comName);
                        $this->CI->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        //arrPrint($tmpOutParams[$cCtr]);
                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            cekMerah("$comName dieksekusiii");
                            arrPrint($tmpOutParams[$cCtr]);
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->CI->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }
                    }

                }
            }
            else {
                //cekKuning("subcomponents is not set");
            }
        }
        //endregion

        //region awal processing main components, if in single step
        $componentGate['master'] = array();
        $componentConfig['master'] = array();
        $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revertAwal']['jurnalAwal']['master']) ? $sessionData['revertAwal']['jurnalAwal']['master'] : array();
        }
        if (sizeof($iterator) > 0) {
            $componentConfig['master'] = $iterator;
            $cCtr = 0;
            foreach ($iterator as $cCtr => $tComSpec) {
                $cCtr++;
                $comName = $tComSpec['comName'];
                if (substr($comName, 0, 1) == "{") {
                    $comName = trim($comName, "{");
                    $comName = trim($comName, "}");
                    $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
                }
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                $this->tEcho("component # $cCtr: $comName<br>");

                $dSpec = $sessionData[$srcGateName];
                $tmpOutParams = array();
                if (isset($tComSpec['loop'])) {
                    foreach ($tComSpec['loop'] as $key => $value) {

                        if (substr($key, 0, 1) == "{") {
                            $key = trim($key, "{");
                            $key = trim($key, "}");
                            $key = str_replace($key, $sessionData['main'][$key], $key);
                            if (!is_numeric($key)) {
                                $key = isset($arrRekening_coa[$key]) ? $arrRekening_coa[$key] : $key;
                            }
                        }

                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
                        $tmpOutParams['loop'][$key] = $realValue;
                        //                            cekKuning("LOOP $key diisi dengan $realValue");
                    }
                }
                if (isset($tComSpec['static'])) {
                    foreach ($tComSpec['static'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
                        $tmpOutParams['static'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static']["transaksi_id"])) {
                        $tmpOutParams['static']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static']["transaksi_no"])) {
                        $tmpOutParams['static']["transaksi_no"] = $insertNum;
                    }
                    $tmpOutParams['static']["urut"] = $cCtr;
                    $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                    //------
                    $tmpOutParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                    $tmpOutParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                    $tmpOutParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                    $tmpOutParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                    $tmpOutParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                    $tmpOutParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                    //------
                }

                if (isset($tComSpec['static2'])) {
                    //cekHere("DISINI OIII");
                    foreach ($tComSpec['static2'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName][$cCtr], $sessionData[$srcGateName][$cCtr], 0);
                        $tmpOutParams['static2'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                        $tmpOutParams['static2']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                        $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                    }

                    $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                    //------
                    $tmpOutParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                    $tmpOutParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                    $tmpOutParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                    $tmpOutParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                    $tmpOutParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                    $tmpOutParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                    //------
                }


                $mdlName = "Com" . ucfirst($comName);
                $this->CI->load->model("Coms/" . $mdlName);
                $m = new $mdlName();

                //===filter value nol, jika harus difilter
                $tobeExecuted = true;

                if (in_array($mdlName, $compValidators)) {

                    $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                    if (sizeof($loopParams) > 0) {
                        foreach ($loopParams as $key => $val) {
                            cekmerah("$comName : $key = $val ");
                            if ($val == 0) {
                                unset($tmpOutParams['loop'][$key]);
                            }
                        }
                    }
                    if (sizeof($tmpOutParams['loop']) < 1) {
                        $tobeExecuted = false;
                    }

                }

                if ($tobeExecuted) {

                    //cekBiru("kiriman komponem $comName");
                    //                        arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                }

                $componentGate['master'][$cCtr] = $tmpOutParams;
            }
        }
        else {
            //cekKuning("components is not set");
        }
        //endregion


//            mati_disini(__LINE__ . " SETOP... COMPONENT  TAMBAHAN AWAL");

        //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
        $steps = $configUiMasterModulJenis['steps'];


        //region auto processing sub-components, if in single step geser ke CLI
        $componentGate['detail'] = array();
        $componentConfig['detail'] = array();
        $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
        $filterNeeded = false;
        $iterator = array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revertAuto']['jurnalAuto']['detail']) ? $sessionData['revertAuto']['jurnalAuto']['detail'] : array();
            $revertedTarget = $sessionData['main']['pihakExternID'];
        }

        $tmpOutParams = array();
        $componentConfig['detail'] = $iterator;
        if ($pakai_ini_cli == 1) {
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $tmpOutParams[$cCtr] = array();
                    $gg = 0;
                    $srcGateName = $tComSpec['srcGateName'];
                    if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {

                        foreach ($sessionData[$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                //                            $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
                                cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
                                $comName = str_replace($comName, $sessionData[$srcGateName][$id][$comName], $comName);
                            }
                            cekHitam(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                //cekLime($mdlName. "line");
                                $filterNeeded = true;
                            }
                            else {
                                cekLime($mdlName . "like");
                                $filterNeeded = false;
                            }
                            $this->tEcho("sub-component: $comName, initializing values <br>");
                            //                        cekHitam(__LINE__);
                            //                        $tmpOutParams[$cCtr] = array();

                            //                        cekhitam("$comName filterneeded: $filterNeeded");
                            //                        cekhitam("mau mengiterasi $srcGateName");
                            //                        cekhitam("telah mengiterasi $srcGateName");
                            //
                            $subParams = array();
                            //arrPrint($tComSpec);
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    cekMerah(":: $key => $value ::");
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        //                                    $key = str_replace($key, $sessionData['main'][$key], $key);
                                        $key = str_replace($key, $sessionData[$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {

                                    $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;

                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                                if (strlen($revertedTarget) > 1) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }
                            //arrPrint($subParams);
                            if (sizeof($subParams) > 0) {
                                //                            arrprint($subParams);
                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                    //                                CekHijiau("asem" .$gg++);
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                }
                cekHitam("cetak tmpOutParams");
//                    $cCtr=0;

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    cekMerah($srcGateName);
//                        foreach ($sessionData[$srcGateName] as $id => $dSpec) {
//                            $srcRawGateName = $tComSpec['srcRawGateName'];
//                            $comName = $tComSpec['comName'];
//                            cekHitam($comName);
//                            if (substr($comName, 0, 1) == "{") {
//                                $comName = trim($comName, "{");
//                                $comName = trim($comName, "}");
//                                $comName = str_replace($comName, $sessionData[$srcGateName][$id][$comName], $comName);
//                                //                        $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
//                            }
//                        }
                    $this->tEcho("*sub component: $comName, sending values <br>");
//                        echo "*sub component: $srcGateName, sending values <br>";
                    if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {

                        $mdlName = "Com" . ucfirst($comName);
                        $this->CI->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        //arrPrint($tmpOutParams[$cCtr]);
                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            cekMerah("$comName dieksekusiii");
                            arrPrint($tmpOutParams[$cCtr]);
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->CI->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }
                    }

                }
            }
            else {
                //cekKuning("subcomponents is not set");
            }
        }
        //endregion

        //region auto processing main components, if in single step
        $componentGate['master'] = array();
        $componentConfig['master'] = array();
        $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revertAuto']['jurnalAuto']['master']) ? $sessionData['revertAuto']['jurnalAuto']['master'] : array();
        }
        if (sizeof($iterator) > 0) {
            $componentConfig['master'] = $iterator;
            $cCtr = 0;
            foreach ($iterator as $cCtr => $tComSpec) {
                $cCtr++;
                $comName = $tComSpec['comName'];
                if (substr($comName, 0, 1) == "{") {
                    $comName = trim($comName, "{");
                    $comName = trim($comName, "}");
                    $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
                }
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                $this->tEcho("component # $cCtr: $comName<br>");

                $dSpec = $sessionData[$srcGateName];
                $tmpOutParams = array();
                if (isset($tComSpec['loop'])) {
                    foreach ($tComSpec['loop'] as $key => $value) {

                        if (substr($key, 0, 1) == "{") {
                            $key = trim($key, "{");
                            $key = trim($key, "}");
                            $key = str_replace($key, $sessionData['main'][$key], $key);
                            if (!is_numeric($key)) {
                                $key = isset($arrRekening_coa[$key]) ? $arrRekening_coa[$key] : $key;
                            }
                        }

                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
                        $tmpOutParams['loop'][$key] = $realValue;
                        //                            cekKuning("LOOP $key diisi dengan $realValue");
                    }
                }
                if (isset($tComSpec['static'])) {
                    foreach ($tComSpec['static'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
                        $tmpOutParams['static'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static']["transaksi_id"])) {
                        $tmpOutParams['static']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static']["transaksi_no"])) {
                        $tmpOutParams['static']["transaksi_no"] = $insertNum;
                    }
                    $tmpOutParams['static']["urut"] = $cCtr;
                    $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                    //------
                    $tmpOutParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                    $tmpOutParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                    $tmpOutParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                    $tmpOutParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                    $tmpOutParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                    $tmpOutParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                    //------
                }

                if (isset($tComSpec['static2'])) {
                    //cekHere("DISINI OIII");
                    foreach ($tComSpec['static2'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName][$cCtr], $sessionData[$srcGateName][$cCtr], 0);
                        $tmpOutParams['static2'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                        $tmpOutParams['static2']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                        $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                    }

                    $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                    //------
                    $tmpOutParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                    $tmpOutParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                    $tmpOutParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                    $tmpOutParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                    $tmpOutParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                    $tmpOutParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                    //------
                }


                $mdlName = "Com" . ucfirst($comName);
                $this->CI->load->model("Coms/" . $mdlName);
                $m = new $mdlName();

                //===filter value nol, jika harus difilter
                $tobeExecuted = true;

                if (in_array($mdlName, $compValidators)) {

                    $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                    if (sizeof($loopParams) > 0) {
                        foreach ($loopParams as $key => $val) {
                            cekmerah("$comName : $key = $val ");
                            if ($val == 0) {
                                unset($tmpOutParams['loop'][$key]);
                            }
                        }
                    }
                    if (sizeof($tmpOutParams['loop']) < 1) {
                        $tobeExecuted = false;
                    }

                }

                if ($tobeExecuted) {

                    //cekBiru("kiriman komponem $comName");
                    //                        arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                }

                $componentGate['master'][$cCtr] = $tmpOutParams;
            }
        }
        else {
            //cekKuning("components is not set");
        }
        //endregion

        //region auto processing sub-post-processors, always
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revertAuto']['postProcAuto']['detail']) ? $sessionData['revertAuto']['postProcAuto']['detail'] : array();
        }
        if (sizeof($iterator) > 0) {
            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                $this->tEcho("[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>");
                $tmpOutParams[$cCtr] = array();
                if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {
//                        arrPrint($sessionData[$srcGateName]);
                    foreach ($sessionData[$srcGateName] as $xid => $dSpec) {
                        //                            $id = $dSpec['id'];
                        $id = $xid;
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                $subParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                cekHitam("gate: $srcGateName, dengan key $id");
                                $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                $subParams['static'][$key] = $realValue;

                            }
                            if (!isset($subParams['static']["transaksi_id"])) {
                                $subParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($subParams['static']["transaksi_no"])) {
                                $subParams['static']["transaksi_no"] = $insertNum;
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            if (isset($sessionData['revert']['postProc']['detail'])) {
                                $subParams['static']["reverted_target"] = $sessionData['main']['pihakExternID'];
                            }

                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor* " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                    }
                }
            }

            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                if (isset($sessionData[$srcGateName])) {
                    $this->tEcho("[$cCtr] sub-postProcessor: $comName, sending values <br>");

                    $mdlName = "Com" . ucfirst($comName);
                    $this->CI->load->model("Coms/" . $mdlName);
                    //arrPrint($tmpOutParams[$cCtr]);
                    $m = new $mdlName();
                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    cekPink($this->CI->db->last_query());
                }

            }
        }
        //endregion

        //region auto processing main-post-processors, always
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revertAuto']['postProcAuto']['detail']) ? $sessionData['revertAuto']['postProcAuto']['master'] : array();
        }
        if (sizeof($iterator) > 0) {
            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                $this->tEcho("post-processor: $comName<br>LINE: " . __LINE__);

                $dSpec = $sessionData[$srcGateName];
                $tmpOutParams = array();
                if (isset($tComSpec['loop'])) {
                    foreach ($tComSpec['loop'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
                        $tmpOutParams['loop'][$key] = $realValue;

                    }
                }
                if (isset($tComSpec['static'])) {
                    foreach ($tComSpec['static'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
                        $tmpOutParams['static'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static']["transaksi_id"])) {
                        $tmpOutParams['static']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static']["transaksi_no"])) {
                        $tmpOutParams['static']["transaksi_no"] = $insertNum;
                    }

                    $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];


                }
                if (isset($tComSpec['static2'])) {
                    //cekHere("DISINI OIII");
                    foreach ($tComSpec['static2'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName][$cCtr], $sessionData[$srcGateName][$cCtr], 0);
                        $tmpOutParams['static2'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                        $tmpOutParams['static2']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                        $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                    }

                    $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];


                }

                //lgShowError("Ada kesalahan",);
                $mdlName = "Com" . ucfirst($comName);
                $this->CI->load->model("Coms/" . $mdlName);
                $m = new $mdlName();

                cekBiru("kiriman komponem $comName");
                arrPrint($tmpOutParams);
                $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                //cekHitam($this->CI->db->last_query());

            }
        }
        else {

        }
        //endregion


        //region processing sub-components, if in single step geser ke CLI
        $paramPatchers = $this->CI->config->item('heTransaksi_paramPatchers') != null ? $this->CI->config->item('heTransaksi_paramPatchers') : array();
        $paramForceFillers = $this->CI->config->item('heTransaksi_paramForceFillers') != null ? $this->CI->config->item('heTransaksi_paramForceFillers') : array();
        $validateSubComponent = $this->CI->config->item('heTransaksi_validateComponentDetail') != null ? $this->CI->config->item('heTransaksi_validateComponentDetail') : array();
        $componentGate['detail'] = array();
        $componentConfig['detail'] = array();
        //            //==filter nilai, jika NOL tidak dikirim, sesuai config==
        $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
        $filterNeeded = false;
        $iterator = array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revert']['jurnal']['detail']) ? $sessionData['revert']['jurnal']['detail'] : array();
            $revertedTarget = $sessionData['main']['pihakExternID'];
        }
        else {
            $iterator = isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['components'][$jenisTrTarget]['detail'] : array();
            $revertedTarget = "";
        }
//            arrprintWebs($iterator);
//            matiHEre($pakai_ini_cli);
        $tmpOutParams = array();
        $componentConfig['detail'] = $iterator;
        if ($pakai_ini_cli == 1) {
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $tmpOutParams[$cCtr] = array();
                    $gg = 0;
                    $srcGateName = $tComSpec['srcGateName'];
                    if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {

                        foreach ($sessionData[$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                //                            $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
                                cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
                                $comName = str_replace($comName, $sessionData[$srcGateName][$id][$comName], $comName);
                            }
                            cekHitam(":: $comName ::");
                            $mdlName = "Com" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                //cekLime($mdlName. "line");
                                $filterNeeded = true;
                            }
                            else {
                                cekLime($mdlName . "like");
                                $filterNeeded = false;
                            }
                            $this->tEcho("sub-component: $comName, initializing values <br>");

                            $subParams = array();
                            //arrPrint($tComSpec);
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    cekMerah(":: $key => $value ::");
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        //                                    $key = str_replace($key, $sessionData['main'][$key], $key);
                                        $key = str_replace($key, $sessionData[$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                $pakai_ini = 0;
                                if ($pakai_ini == 1) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }
                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                                    if (strlen($revertedTarget) > 1) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }
                                else {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                        $subParams['static'][$key] = trim($realValue);
                                    }
                                    if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                        foreach ($paramPatchers[$comName] as $k => $v) {
                                            if (!isset($subParams['static'][$k])) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                cekOrange("fill :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                            }
                                        }
                                    }
                                    if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                        $jenis = $sessionData['main']['jenis'];
                                        foreach ($paramForceFillers[$comName] as $k => $v) {
                                            $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                            cekOrange("fillforce :: $comName :: $k ($v) => " . $subParams['static'][$k]);
                                        }
                                    }
                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                                    //------
                                    $subParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                                    $subParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                                    $subParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                                    $subParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                                    $subParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                                    $subParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                                    $subParams['static']["rejection"] = 1;
                                    //------
                                    if (strlen($revertedTarget) > 1) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }


                            }
                            //arrPrint($subParams);
                            if (sizeof($subParams) > 0) {
                                //                            arrprint($subParams);
                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                    //                                CekHijiau("asem" .$gg++);
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }
                }
                cekHitam("cetak tmpOutParams");
//                    $cCtr=0;

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {


                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            //                            $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
                            cekLime($cCode . " || " . $srcGateName . " || " . $id . " || " . $comName);
                            $comName = str_replace($comName, $sessionData[$srcGateName][$id][$comName], $comName);
                        }
                        cekMerah($srcGateName);
//                        foreach ($sessionData[$srcGateName] as $id => $dSpec) {
//                            $srcRawGateName = $tComSpec['srcRawGateName'];
//                            $comName = $tComSpec['comName'];
//                            cekHitam($comName);
//                            if (substr($comName, 0, 1) == "{") {
//                                $comName = trim($comName, "{");
//                                $comName = trim($comName, "}");
//                                $comName = str_replace($comName, $sessionData[$srcGateName][$id][$comName], $comName);
//                                //                        $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
//                            }
//                        }
                        $this->tEcho("*sub component: $comName, sending values <br>");
//                        echo "*sub component: $srcGateName, sending values <br>";

                        $mdlName = "Com" . ucfirst($comName);
                        $this->CI->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                    }
                    //===filter value nol, jika harus difilter
                    //arrPrint($tmpOutParams[$cCtr]);
                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        $tobeExecuted = true;
                    }
                    else {
                        $tobeExecuted = false;
                    }

                    if ($tobeExecuted) {
                        cekMerah("$comName dieksekusiii");
                        arrPrint($tmpOutParams[$cCtr]);
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekBiru($this->CI->db->last_query());
                    }
                    else {
                        cekMerah("$comName tidak eksekusi");
                    }

                }
            }
            else {
                //cekKuning("subcomponents is not set");
            }
        }


        //endregion

        //region SUBDETAIL postproc multi dimensional array /array 2 tinggkat
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revert']['jurnal']['sub_detail']) ? $sessionData['revert']['jurnal']['sub_detail'] : array();
            cekHitam("jurnal pakai revert");
        }
        else {
            $iterator = isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['sub_detail']) ? $configCoreMasterModulJenis['components'][$jenisTrTarget]['sub_detail'] : array();
            cekHitam("jurnal pakai config core");
        }
        if ($pakai_ini_cli == 1) {
            if (sizeof($iterator) > 0) {
                $tmpOutParams = array();
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $this->tEcho("sub-detail component: $comName, initializing values <br>");
                    $this->tEcho("<script>top.writeProgress('MENYIAPKAN DATA SUB-DETAIL COMPONENT UNTUK DIKIRIM...', 'head');</script>");

                    $tmpOutParams[$cCtr] = array();
                    if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {

                        foreach ($sessionData[$srcGateName] as $cnt => $dDSpec) {

                            foreach ($dDSpec as $idOP_spec => $dSpec) {
                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        $realValue = makeValue($value, $sessionData[$srcGateName][$cnt][$idOP_spec], $sessionData[$srcGateName][$cnt][$idOP_spec], 0);
                                        $subParams['loop'][$key] = $realValue;

                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    $pakai_ini = 0;
                                    if ($pakai_ini == 1) {
                                        foreach ($tComSpec['static'] as $key => $value) {

                                            $realValue = makeValue($value, $sessionData[$srcGateName][$cnt][$idOP_spec], $sessionData[$srcGateName][$cnt][$idOP_spec], 0);
                                            $subParams['static'][$key] = $realValue;
                                            cekBiru("$key diisi dengan $realValue");

                                        }
                                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                            foreach ($paramPatchers[$comName] as $k => $v) {
                                                if (!isset($subParams['static'][$k])) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                }
                                            }
                                        }
                                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                            $jenis = $sessionData['main']['jenis'];
                                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                            }
                                        }
                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                                    }
                                    else {
                                        foreach ($tComSpec['static'] as $key => $value) {

                                            $realValue = makeValue($value, $sessionData[$srcGateName][$cnt][$idOP_spec], $sessionData[$srcGateName][$cnt][$idOP_spec], 0);
                                            $subParams['static'][$key] = $realValue;
                                            cekBiru("$key diisi dengan $realValue");

                                        }
                                        if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                            foreach ($paramPatchers[$comName] as $k => $v) {
                                                if (!isset($subParams['static'][$k])) {
                                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                }
                                            }
                                        }
                                        if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                            $jenis = $sessionData['main']['jenis'];
                                            foreach ($paramForceFillers[$comName] as $k => $v) {
                                                $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                                cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                            }
                                        }
                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                                        //------
                                        $subParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                                        $subParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                                        $subParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                                        $subParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                                        $subParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                                        $subParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                                        //------
                                    }

                                }

                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }

                            $this->tEcho("<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>");
                        }
                    }
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    $this->tEcho("sub-detail component: $comName, sending values <br>");
                    $this->tEcho("<script>top.writeProgress('SENDING DATA SUB-DETAIL COMPONENT ($comName)...', 'head');</script>");
                    if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {

                        $mdlName = "Com" . ucfirst($comName);
                        $this->CI->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();

                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekBiru($this->CI->db->last_query());
                    }
                }
            }
        }

        //endregion

        //region processing main components, if in single step
        $componentGate['master'] = array();
        $componentConfig['master'] = array();
        //==filter nilai, jika NOL tidak dikirim, sesuai config==
        $compValidators = ($this->CI->config->item('transaksi_value_required_components') != null) ? $this->CI->config->item('transaksi_value_required_components') : array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revert']['jurnal']['master']) ? $sessionData['revert']['jurnal']['master'] : array();
        }
        else {
            $iterator = isset($configCoreMasterModulJenis['components'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['components'][$jenisTrTarget]['master'] : array();
        }

        if (sizeof($iterator) > 0) {
            $componentConfig['master'] = $iterator;
            $cCtr = 0;
            foreach ($iterator as $cCtr => $tComSpec) {
                $cCtr++;
                $comName = $tComSpec['comName'];
                if (substr($comName, 0, 1) == "{") {
                    $comName = trim($comName, "{");
                    $comName = trim($comName, "}");
                    $comName = str_replace($comName, $sessionData['main'][$comName], $comName);
                }
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                $this->tEcho("component # $cCtr: $comName<br>");

                $dSpec = $sessionData[$srcGateName];
                $tmpOutParams = array();
                if (isset($tComSpec['loop'])) {
                    foreach ($tComSpec['loop'] as $key => $value) {
                        if (substr($key, 0, 1) == "{") {
                            $key = trim($key, "{");
                            $key = trim($key, "}");
                            $key = str_replace($key, $sessionData['main'][$key], $key);
                            if (!is_numeric($key)) {
                                $key = isset($arrRekening_coa[$key]) ? $arrRekening_coa[$key] : $key;
                            }
                        }
                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
//
//                            if($key == "1010050010"){
//                                $key = "1010050040";
//                            }
//
                        $tmpOutParams['loop'][$key] = $realValue;
                        //                            cekKuning("LOOP $key diisi dengan $realValue");
                    }
                }
                if (isset($tComSpec['static'])) {
                    foreach ($tComSpec['static'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
                        $tmpOutParams['static'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static']["transaksi_id"])) {
                        $tmpOutParams['static']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static']["transaksi_no"])) {
                        $tmpOutParams['static']["transaksi_no"] = $insertNum;
                    }
                    $tmpOutParams['static']["urut"] = $cCtr;
                    $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                    //------
                    $tmpOutParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                    $tmpOutParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                    $tmpOutParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                    $tmpOutParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                    $tmpOutParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                    $tmpOutParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                    $tmpOutParams['static']["rejection"] = 1;
                    //------
                }

                if (isset($tComSpec['static2'])) {
                    //cekHere("DISINI OIII");
                    foreach ($tComSpec['static2'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName][$cCtr], $sessionData[$srcGateName][$cCtr], 0);
                        $tmpOutParams['static2'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                        $tmpOutParams['static2']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                        $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                    }

                    $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                    //------
                    $tmpOutParams['static']["reference_id"] = isset($dSpec["referenceID"]) ? $dSpec["referenceID"] : "";
                    $tmpOutParams['static']["reference_nomer"] = isset($dSpec["referenceNomer"]) ? $dSpec["referenceNomer"] : "";
                    $tmpOutParams['static']["reference_jenis"] = isset($dSpec["jenisTr_reference"]) ? $dSpec["jenisTr_reference"] : "";
                    $tmpOutParams['static']["reference_id_top"] = isset($dSpec["referenceID_top"]) ? $dSpec["referenceID_top"] : "";
                    $tmpOutParams['static']["reference_nomer_top"] = isset($dSpec["referenceNomer_top"]) ? $dSpec["referenceNomer_top"] : "";
                    $tmpOutParams['static']["reference_jenis_top"] = isset($dSpec["pihakExternMasterID"]) ? $dSpec["pihakExternMasterID"] : "";
                    $tmpOutParams['static']["rejection"] = 1;
                    //------
                }


                $mdlName = "Com" . ucfirst($comName);
                $this->CI->load->model("Coms/" . $mdlName);
                $m = new $mdlName();

                //===filter value nol, jika harus difilter
                $tobeExecuted = true;

                if (in_array($mdlName, $compValidators)) {

                    $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                    if (sizeof($loopParams) > 0) {
                        foreach ($loopParams as $key => $val) {
                            cekmerah("$comName : $key = $val ");
                            if ($val == 0) {
                                unset($tmpOutParams['loop'][$key]);
                            }
                        }
                    }
                    if (sizeof($tmpOutParams['loop']) < 1) {
                        $tobeExecuted = false;
                    }

                }

                if ($tobeExecuted) {

                    //cekBiru("kiriman komponem $comName");
                    //                        arrPrint($tmpOutParams);
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                }

                $componentGate['master'][$cCtr] = $tmpOutParams;
            }
        }
        else {
            //cekKuning("components is not set");
        }


        //endregion


        cekHitam(":: START POST PROCC DETAIL... ::");

        //region processing sub-post-processors, always
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revert']['postProc']['detail']) ? $sessionData['revert']['postProc']['detail'] : array();
            cekHitam("post procc pakai revert");
        }
        else {
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['detail'] : array();
            cekHitam("post procc pakai config core");
        }
        if (sizeof($iterator) > 0) {
            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                $this->tEcho("[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values<br>");
                $tmpOutParams[$cCtr] = array();
                if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {
                    foreach ($sessionData[$srcGateName] as $xid => $dSpec) {
                        $id = $xid;
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                $subParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                cekHitam("gate: $srcGateName, dengan key $id");
                                $realValue = makeValue($value, $sessionData[$srcGateName][$id], $sessionData[$srcGateName][$id], 0);
                                $subParams['static'][$key] = $realValue;
                                cekHitam("gate: $srcGateName, dengan key $id [$key => $value => $realValue]");
                            }
                            if (!isset($subParams['static']["transaksi_id"])) {
                                $subParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($subParams['static']["transaksi_no"])) {
                                $subParams['static']["transaksi_no"] = $insertNum;
                            }
                            if (!isset($subParams['static']["referenceID"])) {
                                $subParams['static']["referenceID"] = $dSpec["referenceID"];
                            }
                            if (!isset($subParams['static']["referenceID_top"])) {
                                $subParams['static']["referenceID_top"] = $dSpec["referenceID_top"];
                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            if (isset($sessionData['revert']['postProc']['detail'])) {
                                $subParams['static']["reverted_target"] = $sessionData['main']['pihakExternID'];
                            }
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                            $subParams['static']["rejection"] = 1;
                            cekHitam($dSpec["jenisTr_reference"]);
                            switch ($dSpec["jenisTr_reference"]) {
                                case "1676":
                                    if ($subParams['static']['state'] == "sold") {
                                        cekKuning("HAHAHA");
                                        $subParams['static']["referenceID"] = 0;
                                    }
                                    elseif (($subParams['static']['state'] == "hold") && ($subParams['static']["transaksi_id"] > 0)) {
                                        cekBiru("HAHAHA");
                                        $subParams['static']["transaksi_id"] = $dSpec["referenceID_top"];
                                    }
                                    break;
                            }
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                    }
                }
            }

            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                if (isset($sessionData[$srcGateName]) && (sizeof($sessionData[$srcGateName]) > 0)) {
                    $this->tEcho("[$cCtr] sub-postProcessor: $comName, sending values **p**<br>");

                    $mdlName = "Com" . ucfirst($comName);
                    $this->CI->load->model("Coms/" . $mdlName);
                    //arrPrint($tmpOutParams[$cCtr]);
                    $m = new $mdlName();
                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    cekPink($this->CI->db->last_query());
                }

            }
        }
        //endregion


        //region SUBDETAIL postproc multi dimensional array /array 2 tinggkat
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revert']['postProc']['sub_detail']) ? $sessionData['revert']['postProc']['sub_detail'] : array();
            cekHitam("post procc pakai revert");
        }
        else {
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['sub_detail']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['sub_detail'] : array();
            cekHitam("post procc pakai config core");
        }

        if (sizeof($iterator) > 0) {
            $tmpOutParams = array();
            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                $this->tEcho("sub-postProcessor: $comName, initializing values *<br>");
                $this->tEcho("<script>top.writeProgress('MENYIAPKAN DATA SUB-PROCESSORS UNTUK DIKIRIM...', 'head');</script>");

                $tmpOutParams[$cCtr] = array();
                foreach ($sessionData[$srcGateName] as $cnt => $dDSpec) {

                    foreach ($dDSpec as $idOP_spec => $dSpec) {
                        $subParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$srcGateName][$cnt][$idOP_spec], $sessionData[$srcGateName][$cnt][$idOP_spec], 0);
                                $subParams['loop'][$key] = $realValue;

                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {

                                $realValue = makeValue($value, $sessionData[$srcGateName][$cnt][$idOP_spec], $sessionData[$srcGateName][$cnt][$idOP_spec], 0);
                                $subParams['static'][$key] = $realValue;
                                cekBiru("$key diisi dengan $realValue");

                            }

                            if (isset($paramPatchers[$comName]) && sizeof($paramPatchers[$comName]) > 0) {
                                foreach ($paramPatchers[$comName] as $k => $v) {
                                    if (!isset($subParams['static'][$k])) {
                                        $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    }
                                }
                            }
                            if (isset($paramForceFillers[$comName]) && sizeof($paramForceFillers[$comName]) > 0) {
                                $jenis = $sessionData['main']['jenis'];
                                foreach ($paramForceFillers[$comName] as $k => $v) {
                                    $subParams['static'][$k] = isset($$v) ? $$v : "_v";
                                    cekorange(":: $k diisikan dengan " . $subParams['static'][$k]);
                                }
                            }

                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                        }

                        if (sizeof($subParams) > 0) {
                            $tmpOutParams[$cCtr][] = $subParams;
                        }
                    }

                    $this->tEcho("<script>top.writeProgress('" . isset($subParams['static']['name']) ? $subParams['static']['name'] : "" . " " . isset($subParams['static']['extern_nama']) ? $subParams['static']['extern_nama'] : "" . " " . isset($subParams['static']['nama']) ? $subParams['static']['nama'] : "" . "');</script>");
                }
            }

            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                $this->tEcho("sub-postProcessor: $comName, sending values **<br>");
                $this->tEcho("<script>top.writeProgress('SENDING SUB-PROCESSORS ($comName)...', 'head');</script>");
                $mdlName = "Com" . ucfirst($comName);
                $this->CI->load->model("Coms/" . $mdlName);
                $m = new $mdlName();

                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                cekBiru($this->CI->db->last_query());
            }
        }

        //endregion

        //region relesaese connected payment source dan marking trash transaksi
        if (isset($sessionData["revert"]["connectedPaymentsource"]) && $sessionData["revert"]["connectedPaymentsource"] == true) {
            $keyRel = $sessionData["main"]["referenceID"];
            $keyRelRef = $sessionData["main"]["pihakExternID"];
            $relPaymentSrc = isset($this->CI->config->item("payment_source")[$keyRelRef]) ? $this->CI->config->item("payment_source")[$keyRelRef] : array();
            if (sizeof($relPaymentSrc) > 0) {
                $this->CI->load->model("Mdls/MdlPaymentSource");
                $m = new MdlPaymentSource();
                $m->addFilter("transaksi_id='$keyRel'");
                $tmpRelPay = $m->lookupAll()->result();
                $paymentRelUsed = array(
                    "cabang_id" => "placeID",
                    "extern_id" => "id",
                    "extern_nama" => "name",
                    "label" => ".hutang biaya",
                    "target_jenis" => "jenisTr",
                    "transaksi_id" => "refID",
//                                        "terbayar" => "nilai_bayar",
                    "returned" => "nilai_bayar",
                    "sisa" => "new_sisa",
                    "ppn" => "valid_ppn",
                    "extern_nilai2" => "valid_dpp",
                );
                if (sizeof($tmpRelPay) > 0) {
                    $tmpOutParams = array();
                    $iterator = array();
                    foreach ($tmpRelPay as $indexKey => $relData) {
                        $tmp = array();
                        foreach ($paymentRelUsed as $key => $keyGate) {
                            if ($key == "terbayar") {
                                $val = $relData->sisa;
                            }
                            elseif ($key == "returned") {
                                $val = $relData->sisa;
                            }
                            else {
                                if ($key == "sisa") {
                                    $val = "-" . $relData->sisa;
                                }
                                else {
                                    $val = $relData->$key;
                                }
                            }
                            $tmp["static"][$key] = $val;
                            $tmp["static"]["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                            $tmp["static"]["jenisPembatalan"] = $this->jenisTr;
                        }
                        $iterator[$indexKey]["loop"] = array();
                        $iterator[$indexKey]["comName"] = "PaymentSrcItem";
                        if (sizeof($tmp) > 0) {
                            $tmpOutParams[$indexKey][] = $tmp;
                        }
                    }
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $this->tEcho("sub-postProcessor: $comName, sending values ***<br>");

                        $mdlName = "Com" . ucfirst($comName);
                        $this->CI->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        //                            cekHitam($this->CI->db->last_query());
                    }
                }
                cekLime("yuk direset paymentsource**");
            }


        }
        //endregion


        //region relesaese connected payment source uang muka
        if (isset($sessionData["revert"]["connectedPaymentsourceUangMuka"]) && $sessionData["revert"]["connectedPaymentsourceUangMuka"] == true) {
            cekBiru("mengembalikan payment source uang muka");
            $referenceStepNumber = $sessionData["main"]["referenceStepNumber"];// transaksi id yang dibatalkan
            $keyRel = $sessionData["main"]["referenceID"];// transaksi id yang dibatalkan
            $keyRelRef = $sessionData["main"]["pihakExternID"];// jenis transaksi yang dibatalkan
            $relPaymentSrc = isset($this->CI->config->item("uang_muka")[$keyRelRef][$referenceStepNumber]) ? $this->CI->config->item("uang_muka")[$keyRelRef][$referenceStepNumber] : array();
            if (sizeof($relPaymentSrc) > 0) {
                arrPrintPink($relPaymentSrc);
//                    $pym_uang_muka_src = $relPaymentSrc[0]["valueSrc"];
//                    $pym_uang_muka_label = $relPaymentSrc[0]["label"];
//                    $pym_uang_muka_ext_label = $relPaymentSrc[0]["externSrc"]["extLabel"];
//                    $pym_uang_muka_pihak_id_key = $relPaymentSrc[0]["externSrc"]["id"];
//                    $pym_uang_muka_pihak_id = $sessionData["main"][$pym_uang_muka_pihak_id_key];
//                    $pym_uang_muka_dibatalkan = $sessionData["main"][$pym_uang_muka_src];
//                    cekHitam("pihakid: $pym_uang_muka_pihak_id :: uangmuka batal: $pym_uang_muka_dibatalkan");

                if (count($relPaymentSrc) > 0) {
                    foreach ($relPaymentSrc as $i => $relPaymentSrc_0) {
                        $value_labelSrc = $relPaymentSrc_0["valueSrc"];
                        if (isset($sessionData["main"][$value_labelSrc]) && $sessionData["main"][$value_labelSrc] > 0) {
                            $pym_uang_muka_src = $relPaymentSrc_0["valueSrc"];
                            $pym_uang_muka_label = $relPaymentSrc_0["label"];
                            $pym_uang_muka_ext_label = $relPaymentSrc_0["externSrc"]["extLabel"];
                            $pym_uang_muka_pihak_id_key = $relPaymentSrc_0["externSrc"]["id"];

                            $pym_uang_muka_pihak_id = $sessionData["main"][$pym_uang_muka_pihak_id_key];
                            $pym_uang_muka_extern2_id_key = $relPaymentSrc_0["externSrc"]["extern2_id"];//

                            if (isset($sessionData["main"][$pym_uang_muka_extern2_id_key])) {
                                $pym_uang_muka_extern2_id = $sessionData["main"][$pym_uang_muka_extern2_id_key];
                            }
                            else {
                                if (isset($sessionData["main"]['referensi_so__id'])) {
                                    $pym_uang_muka_extern2_id = $sessionData["main"]['referensi_so__id'];
                                }
                                else {
                                    $pym_uang_muka_extern2_id = 0;
                                }
                            }


                            $pym_uang_muka_dibatalkan = $sessionData["main"][$pym_uang_muka_src];
                            cekHitam("pihakid: $pym_uang_muka_pihak_id :: uangmuka batal: $pym_uang_muka_dibatalkan");

                            $this->CI->load->model("Mdls/MdlPaymentUangMuka");
                            $m = new MdlPaymentUangMuka();
                            $m->addFilter("label='$pym_uang_muka_label'");
                            $m->addFilter("extern_id='$pym_uang_muka_pihak_id'");
                            $m->addFilter("extern2_id='$pym_uang_muka_extern2_id'");
                            $m->addFilter("extern_label2='$pym_uang_muka_ext_label'");
                            $m->addFilter("cabang_id=" . $sessionData["main"]["placeID"]);
                            $tmpRelPay = $m->lookupAll()->result();
//                                arrprint($tmpRelPay);
//                                showLast_query("biru");
//                                matiHEre($pym_uang_muka_ext_label);

                            if (sizeof($tmpRelPay) > 0) {
                                $paymentRelUsed = array(
                                    "cabang_id" => "placeID",
                                    "extern_id" => "id",
                                    "extern_nama" => "name",
                                    "extern2_id" => "extern2_id",
                                    "extern2_nama" => "extern2_nama",
                                    "label" => ".hutang biaya",
                                    "target_jenis" => "jenisTr",
                                    "transaksi_id" => "refID",
//                                        "terbayar" => "nilai_bayar",
                                    "returned" => "nilai_bayar",
                                    "sisa" => "new_sisa",
                                    "extern_label2" => "valid_dpp",
                                );
                                if (sizeof($tmpRelPay) > 0) {
                                    $tmpOutParams = array();
                                    $iterator = array();
                                    foreach ($tmpRelPay as $indexKey => $relData) {
                                        $tmp = array();
                                        foreach ($paymentRelUsed as $key => $keyGate) {
                                            if ($key == "terbayar") {
                                                $val = $pym_uang_muka_dibatalkan;
                                            }
                                            elseif ($key == "returned") {
                                                $val = $pym_uang_muka_dibatalkan;
                                            }
                                            else {
                                                if ($key == "sisa") {
                                                    $val = "-" . $pym_uang_muka_dibatalkan;
                                                }
                                                else {
                                                    $val = $relData->$key;
                                                }
                                            }
                                            $tmp["static"][$key] = $val;
                                            $tmp["static"]["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                                        }
                                        $iterator[$indexKey]["loop"] = array();
                                        $iterator[$indexKey]["comName"] = "PaymentUangMuka";
                                        if (sizeof($tmp) > 0) {
                                            $tmpOutParams[$indexKey] = $tmp;
                                        }
                                    }

                                    foreach ($iterator as $cCtr => $tComSpec) {
                                        $comName = $tComSpec['comName'];
                                        $this->tEcho("sub-postProcessor: $comName, sending values <br>");
                                        $mdlName = "Com" . ucfirst($comName);
                                        $this->CI->load->model("Coms/" . $mdlName);
                                        $m = new $mdlName();
                                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    }
                                }
                            }

//                                matiHEre("ada $value_labelSrc :: nilainya ::".$sessionData["main"][$value_labelSrc]);

                        }
                        else {
                            cekHitam("gak ada $value_labelSrc");
                        }
                    }
                }

//                    matiHEre(__LINE__);
//                    $this->CI->load->model("Mdls/MdlPaymentUangMuka");
//                    $m = new MdlPaymentUangMuka();
//                    $m->addFilter("label='$pym_uang_muka_label'");
//                    $m->addFilter("extern_id='$pym_uang_muka_pihak_id'");
//                    $m->addFilter("extern_label2='$pym_uang_muka_ext_label'");
//                    $m->addFilter("cabang_id=" . $sessionData["main"]["placeID"]);
//                    $tmpRelPay = $m->lookupAll()->result();
//                    showLast_query("biru");
//                    matiHEre($pym_uang_muka_ext_label);
//                    if (sizeof($tmpRelPay) > 0) {
////                        arrPrintKuning($tmpRelPay);
//                        $paymentRelUsed = array(
//                            "cabang_id" => "placeID",
//                            "extern_id" => "id",
//                            "extern_nama" => "name",
//                            "label" => ".hutang biaya",
//                            "target_jenis" => "jenisTr",
//                            "transaksi_id" => "refID",
//                            "terbayar" => "nilai_bayar",
//                            "sisa" => "new_sisa",
////                            "ppn" => "valid_ppn",
//                            "extern_label2" => "valid_dpp",
//                        );
//                        if (sizeof($tmpRelPay) > 0) {
//                            $tmpOutParams = array();
//                            $iterator = array();
//                            foreach ($tmpRelPay as $indexKey => $relData) {
//                                $tmp = array();
//                                foreach ($paymentRelUsed as $key => $keyGate) {
//                                    if ($key == "terbayar") {
////                                        $val = $relData->sisa;
//                                        $val = $pym_uang_muka_dibatalkan;
//                                    }
//                                    else {
//                                        if ($key == "sisa") {
////                                            $val = "-" . $relData->sisa;
//                                            $val = "-" . $pym_uang_muka_dibatalkan;
//                                        }
//                                        else {
//                                            $val = $relData->$key;
//                                        }
//                                    }
//                                    $tmp["static"][$key] = $val;
//                                    $tmp["static"]["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
//                                }
//                                $iterator[$indexKey]["loop"] = array();
//                                $iterator[$indexKey]["comName"] = "PaymentUangMuka";
//                                if (sizeof($tmp) > 0) {
//                                    $tmpOutParams[$indexKey] = $tmp;
//                                }
//                            }
////                            arrPrintWebs($iterator);
////                            arrPrintWebs($tmpOutParams);
//
//                            foreach ($iterator as $cCtr => $tComSpec) {
//                                $comName = $tComSpec['comName'];
//                                echo "sub-postProcessor: $comName, sending values <br>";
//                                $mdlName = "Com" . ucfirst($comName);
//                                $this->CI->load->model("Coms/" . $mdlName);
//                                $m = new $mdlName();
//                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
//                                $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
//                            }
//                        }
//                    }
            }
//                arrprint($sessionData["revert"]);
//                matiHere();
        }
        //endregion


        //region processing main-post-processors, always
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $iterator = isset($sessionData['revert']['postProc']['master']) ? $sessionData['revert']['postProc']['master'] : array();
        }
        else {
            $iterator = isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master']) ? $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]['master'] : array();
        }

        if (sizeof($iterator) > 0) {
            foreach ($iterator as $cCtr => $tComSpec) {
                $comName = $tComSpec['comName'];
                $srcGateName = $tComSpec['srcGateName'];
                $srcRawGateName = $tComSpec['srcRawGateName'];
                $this->tEcho("post-processor: $comName<br>LINE: " . __LINE__);

                $dSpec = $sessionData[$srcGateName];
                $tmpOutParams = array();
                if (isset($tComSpec['loop'])) {
                    foreach ($tComSpec['loop'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
                        $tmpOutParams['loop'][$key] = $realValue;

                    }
                }
                if (isset($tComSpec['static'])) {
                    foreach ($tComSpec['static'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName], $sessionData[$srcGateName], 0);
                        $tmpOutParams['static'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static']["transaksi_id"])) {
                        $tmpOutParams['static']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static']["transaksi_no"])) {
                        $tmpOutParams['static']["transaksi_no"] = $insertNum;
                    }

                    $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static']["keterangan"] = $configUiMasterModulJenis['steps'][1]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];
                    $tmpOutParams['static']["rejection"] = 1;
                    if (!isset($tmpOutParams['static']["referenceID"])) {
                        $tmpOutParams['static']["referenceID"] = $dSpec["referenceID"];
                    }
                    if (!isset($tmpOutParams['static']["referenceID_top"])) {
                        $tmpOutParams['static']["referenceID_top"] = $dSpec["referenceID_top"];
                    }
                }
                if (isset($tComSpec['static2'])) {
                    //cekHere("DISINI OIII");
                    foreach ($tComSpec['static2'] as $key => $value) {

                        $realValue = makeValue($value, $sessionData[$srcGateName][$cCtr], $sessionData[$srcGateName][$cCtr], 0);
                        $tmpOutParams['static2'][$key] = $realValue;

                    }
                    if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                        $tmpOutParams['static2']["transaksi_id"] = $insertID;
                    }
                    if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                        $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                    }

                    $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                    $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                    $tmpOutParams['static2']["keterangan"] = $configUiMasterModulJenis['steps'][$stepNum]['label'] . " nomor " . $tmpNomorNota . " oleh " . $kiriman["oleh_nama"];


                }

                //lgShowError("Ada kesalahan",);
                $mdlName = "Com" . ucfirst($comName);
                $this->CI->load->model("Coms/" . $mdlName);
                $m = new $mdlName();

                cekBiru("kiriman komponem $comName");
                arrPrint($tmpOutParams);
                $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                //cekHitam($this->CI->db->last_query());

            }
        }
        else {

        }
        //endregion

        //region updater main transaksi rejection jurnal next step exist
        $mongUpdateList = array();
        if (isset($configCoreMasterModulJenis['relativeComponets']) && $configCoreMasterModulJenis['relativeComponets'] == true) {
            $tr->setFilters(array());
            $tr->addFilter("id='" . $sessionData["main"]["referenceID"] . "'");
            $tempData = $tr->lookupAll()->result();
            showLast_query("hitam");
            $refMasterID = $tempData[0]->id_master;
            $refMasterJenis = $tempData[0]->jenis_master;
            $nextStepCode = $tempData[0]->next_step_code;
            $mainStepCode = $tempData[0]->jenis;
            $stepnum = $tempData[0]->step_number;
            $stepnumAvail = $tempData[0]->step_avail;

            $configUiMasterModulJenisRef = loadConfigModulJenis_he_misc($refMasterJenis, "coTransaksiUi");
            $configCoreMasterModulJenisRef = loadConfigModulJenis_he_misc($refMasterJenis, "coTransaksiCore");
            $configLayoutMasterModulJenisRef = loadConfigModulJenis_he_misc($refMasterJenis, "coTransaksiLayout");
            if (($stepnumAvail - $stepnum) > 0) {
                $this->CI->load->model("Coms/ComTransaksi_jurnal_revert");
                $r = new ComTransaksi_jurnal_revert();
                $outParams = array(
                    "refID" => $refMasterID,
                    "main_code" => $mainStepCode,
                    "next_code" => $nextStepCode,
                    "step_num" => $stepnum,
                );
                $r->pair($outParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                $r->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                //marking main transaksi trash4
                $udpate = array(
                    "trash_4" => "1",
                    "cancel_dtime" => date("Y-m-d H:i:s"),
                    "cancel_id" => $kiriman["oleh_id"],
                    "cancel_name" => $kiriman["oleh_nama"],
                    "cancel_transaksi_jenis" => $this->jenisTr,
                    "cancel_transaksi_id" => $insertID,
                    "cancel_transaksi_nomer" => $insertNum,
                );
                $tr->setFilters(array());
                $dupState = $tr->updateData(array(
                    //                "id" => $no,
                    "id" => $sessionData["main"]["referenceID"],
                ), $udpate) or die("Failed to update tr next-state!");
                $mongUpdateList['update']['main'][] = array(
                    "where" => array("id" => $sessionData["main"]["referenceID"]),
                    "value" => array(
                        "trash_4" => "1",
                    ),
                );
                cekHijau("UPDATE transaksi step sebelumnya...");
                cekHijau($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");

                $td = new MdlTransaksi();
                $td->setFilters(array());
                $rslt = $td->lookupJoinedByID($sessionData["main"]["referenceID"])->result();
                if (sizeof($rslt) > 0) {
                    foreach ($rslt as $rsltSpec) {
                        if (array_key_exists($rsltSpec->produk_id, $sessionData["items"])) {
                            $arrData_detail["valid_qty"] = 0;
                            $tr = new MdlTransaksi();
                            $tr->setFilters(array());
                            $tr->setTableName($tr->getTableNames()['detail']);
                            $dupState = $tr->updateData(array(
                                "transaksi_id" => $sessionData["main"]["referenceID"],
                                "produk_id" => $rsltSpec->produk_id,
                            ), $arrData_detail) or die("Failed to update tr next-state!");
                            $mongUpdateList['update']['detail'][] = array(
                                "where" => array(
                                    "transaksi_id" => $sessionData["main"]["referenceID"],
                                    "produk_id" => $rsltSpec->produk_id,
                                ),
                                "value" => $arrData_detail,
                            );
                            cekKuning("UPDATE transaksi data...");
                            cekKuning($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");
                        }
                    }
                }

                $dwsign = $tr->writeSignature($refMasterID, array(
                    "prev_id" => "",
                    "nomer" => "pembatalan jurnal",
                    "step_number" => "-" . $stepnum, // ini minus step number
                    "step_code" => $configUiMasterModulJenisRef['steps'][abs($stepnum)]['target'],
                    "step_name" => $configUiMasterModulJenisRef['steps'][abs($stepnum)]['label'],
                    "group_code" => $configUiMasterModulJenisRef['steps'][abs($stepnum)]['userGroup'],
                    "oleh_id" => $kiriman["oleh_id"],
                    "oleh_nama" => $kiriman["oleh_nama"],
                    "keterangan" => $configUiMasterModulJenisRef['steps'][abs($stepnum)]['label'] . " oleh " . $kiriman["oleh_nama"],
                )) or die("Failed to write signature");
                $mongoList['sign'][] = $dwsign;
                cekKuning($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");
                //                    matiHere();
            }
            else {
                //marking main transaksi trash4
                $udpate = array(
                    "trash_4" => "1",
                );
                $tr->setFilters(array());
                $dupState = $tr->updateData(array(
                    //                "id" => $no,
                    "id" => $sessionData["main"]["referenceID"],
                ), $udpate) or die("Failed to update tr next-state!");
                cekHijau("UPDATE transaksi step sebelumnya...");
                cekHijau($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");
                $mongUpdateList['update']['main'][] = array(
                    "where" => array("id" => $sessionData["main"]["referenceID"]),
                    "value" => array(
                        "trash_4" => "1",
                    ),
                );

                //update validqty 0 supaya gak bisa difollowup
                $arrData_detail["valid_qty"] = $new_valid_qty;
                $td = new MdlTransaksi();
                $td->setFilters(array());
                $rslt = $td->lookupJoinedByID($sessionData["main"]["referenceID"])->result();
                if (sizeof($rslt) > 0) {
                    foreach ($rslt as $rsltSpec) {
                        if (array_key_exists($rsltSpec->produk_id, $sessionData["items"])) {
                            //                                $new_valid_qty = $rsltSpec->valid_qty + $items[$rsltSpec->produk_id]['qty'];
                            //                                //                            cekHitam(":: $prevID :: $rsltSpec->valid_qty :: " . $items[$rsltSpec->produk_id]['qty'] . " :: $new_valid_qty ::");
                            //
                            //                                if ($new_valid_qty > $rsltSpec->produk_ord_jml) {
                            //                                    $new_valid_qty = $rsltSpec->valid_qty;
                            //                                    //                                mati_disini("undo/reject/delete gagal karena valid_qty melebihi produk_ord_jml");
                            //                                }
                            //                                else {
                            //                                    $new_valid_qty = $rsltSpec->valid_qty + $items[$rsltSpec->produk_id]['qty'];
                            //                                }
                            $arrData_detail["valid_qty"] = 0;
                            $tr = new MdlTransaksi();
                            $tr->setFilters(array());
                            $tr->setTableName($tr->getTableNames()['detail']);
                            $dupState = $tr->updateData(array(
                                "transaksi_id" => $sessionData["main"]["referenceID"],
                                "produk_id" => $rsltSpec->produk_id,
                            ), $arrData_detail) or die("Failed to update tr next-state!");
                            cekKuning("UPDATE transaksi data...");
                            cekKuning($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");
                            $mongUpdateList['update']['detail'][] = array(
                                "where" => array(
                                    "transaksi_id" => $sessionData["main"]["referenceID"],
                                    "produk_id" => $rsltSpec->produk_id,
                                ),
                                "value" => $arrData_detail,
                            );
                        }
                    }
                }

                $dwsign = $tr->writeSignature($refMasterID, array(
                    "prev_id" => "",
                    "nomer" => "pembatalan jurnal",
                    "step_number" => "-" . $stepnum, // ini minus step number
                    "step_code" => $configUiMasterModulJenisRef['steps'][abs($stepnum)]['target'],
                    "step_name" => $configUiMasterModulJenisRef['steps'][abs($stepnum)]['label'],
                    "group_code" => $configUiMasterModulJenisRef['steps'][abs($stepnum)]['userGroup'],
                    "oleh_id" => $kiriman["oleh_id"],
                    "oleh_nama" => $kiriman["oleh_nama"],
                    "keterangan" => $configUiMasterModulJenisRef['steps'][abs($stepnum)]['label'] . " oleh " . $kiriman["oleh_nama"],
                    //            "transaksi_id" => $no,
                )) or die("Failed to write signature");
                $mongoList['sign'][] = $dwsign;
                cekKuning($this->CI->db->last_query() . " [" . $this->CI->db->affected_rows() . "]");

                //                    matiHEre("uhuu");
            }
        }

        //endregion

        // region berlaku pembatalan transaksi bila ada config revertStep di model MdlRevertJurnal (true)
        if (isset($sessionData['main']['pihakExternRevertStep']) && ($sessionData['main']['pihakExternRevertStep'] == true)) {
            $referenceNextProp = (isset($sessionData['main']['referenceNextProp']) && (sizeof($sessionData['main']['referenceNextProp']) > 0)) ? $sessionData['main']['referenceNextProp'] : array();
            if (sizeof($referenceNextProp) > 0) {
                // update transaksi reference, step sebelumnya menjadi aktif lagi
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $dupState = $tr->updateData(array("id" => $referenceNextProp['trID']), array(
                    "next_step_code" => $referenceNextProp['code'],
                    "next_step_label" => $referenceNextProp['label'],
                    "next_group_code" => $referenceNextProp['groupID'],
                    "next_step_num" => $referenceNextProp['num'],
                    "step_current" => $referenceNextProp['step_num'],
                    "returns" => $sessionData["main"]["returns"],

                )) or die("Failed to update tr next-state!");
                cekHijau("BATAL :: " . $this->CI->db->last_query() . " -- " . $this->CI->db->affected_rows() . " ::: " . __LINE__);


                // update transaksi data reference, step sebelumnya menjadi aktif lagi
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("trash='0'");
                $tr->addFilter("transaksi_id='" . $referenceNextProp['trID'] . "'");
                $tr->setTableName($tr->getTableNames()['detail']);
                $detailTmp = $tr->lookupAll()->result();
                $detailData = array();
                foreach ($detailTmp as $dTmpSpec) {
                    $detailData[$dTmpSpec->produk_id] = array(
                        "valid_qty" => $dTmpSpec->valid_qty,
                    );
                }
                cekOrange($referenceNextProp['detailGate']);
                if (isset($sessionData[$referenceNextProp['detailGate']]) && ($sessionData[$referenceNextProp['detailGate']] != NULL)) {
                    foreach ($sessionData[$referenceNextProp['detailGate']] as $itemsSpec) {
                        //                        arrPrint($itemsSpec);
                        $valid_qty = isset($detailData[$itemsSpec['id']]['valid_qty']) ? $detailData[$itemsSpec['id']]['valid_qty'] : 0;
                        $valid_qty_new = $valid_qty + $itemsSpec['qty'];

                        $tr = new MdlTransaksi();
                        $tr->setFilters(array());
                        $tr->setTableName($tr->getTableNames()['detail']);
                        $ddupState = $tr->updateData(
                            array(
                                "transaksi_id" => $referenceNextProp['trID'],
                                "trash" => 0,
                                "produk_id" => $itemsSpec['id'],
                            ), array(
                            "next_substep_code" => $referenceNextProp['code'],
                            "next_substep_label" => $referenceNextProp['label'],
                            "next_subgroup_code" => $referenceNextProp['groupID'],
                            "next_substep_num" => $referenceNextProp['num'],
                            "sub_step_current" => $referenceNextProp['step_num'],
                            "valid_qty" => $valid_qty_new,

                        )) or die("Failed to update tr next-state!");
                        cekHijau("BATAL :: " . $this->CI->db->last_query() . " -- " . $this->CI->db->affected_rows());
                        $mongUpdateList['update']['detail'][] = array(
                            "where" => array(
                                "transaksi_id" => $referenceNextProp['trID'],
                                "trash" => 0,
                                "produk_id" => $itemsSpec['id'],
                            ),
                            "value" => array(
                                "next_substep_code" => $referenceNextProp['code'],
                                "next_substep_label" => $referenceNextProp['label'],
                                "next_subgroup_code" => $referenceNextProp['groupID'],
                                "next_substep_num" => $referenceNextProp['num'],
                                "sub_step_current" => $referenceNextProp['step_num'],
                                "valid_qty" => $valid_qty_new,
                            ),
                        );

                    }
                }
            }
        }
        // endregion


        //region nulis paymentSource
        $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
        cekHitam("[stepCode: $stepCode]");
        $paymentSources = $this->CI->config->item("payment_source");
        if (array_key_exists($stepCode, $paymentSources)) {
            $payConfigs = $paymentSources[$stepCode];
            if (sizeof($payConfigs) > 0) {
                foreach ($payConfigs[1] as $paymentSrcConfig) {
                    $valueSrc = $paymentSrcConfig['valueSrc'];
                    $externSrc = $paymentSrcConfig['externSrc'];
                    $paymentMethod = isset($paymentSrcConfig['method']) ? $paymentSrcConfig['method'] : "insert";

                    if ($paymentMethod == "update") {
                        $filters = array(
                            "extern_id" => ""
                        );
                        //                            arrPrint($externSrc);
                        $tr->setFilters(array());
                        $tmpData = $tr->lookupPaymentSrcByJenis($paymentSrcConfig['jenisTarget'])->result();
                        if (sizeof($tmpData) > 0) {
                            //sudah ada update aja gak perlu insert
                            $prevID = $tmpData[0]->id;
                            $preValue = $tmpData[0]->sisa;
                            $currValue = isset($sessionData['main'][$valueSrc]) ? $sessionData['main'][$valueSrc] : 0;
                            $newValue = $preValue + $currValue;
                            $where = array(
                                "id" => $prevID,
                                //                                    "transaksi_id" => $pSpec->transaksi_id,
                            );
                            $data = array(
                                "tagihan" => $newValue,
                                "sisa" => $newValue,
                            );
                            $tr->updatePaymentSrc($where, $data);
                            //                                cekHitam($this->CI->db->last_query());
                        }
                        else {
                            //di insert baru
                            $tr->writePaymentSrc($insertID, array(
                                "jenis" => $stepCode,
                                "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                "extern_id" => $sessionData['main'][$externSrc['id']],
                                "extern_nama" => $sessionData['main'][$externSrc['nama']],
                                "nomer" => $sessionData['main']['nomer'],
                                "label" => $paymentSrcConfig['label'],
                                "tagihan" => isset($sessionData['main'][$valueSrc]) ? $sessionData['main'][$valueSrc] : 0,
                                "terbayar" => 0,
                                "sisa" => isset($sessionData['main'][$valueSrc]) ? $sessionData['main'][$valueSrc] : 0,
                                "cabang_id" => $sessionData['main']['placeID'],
                                "cabang_nama" => $sessionData['main']['placeName'],
                                "oleh_id" => $kiriman["oleh_id"],
                                "oleh_nama" => $kiriman["oleh_nama"],
                                "dtime" => date("Y-m-d H:i:s"),
                                "fulldate" => date("Y-m-d"),
                                "valas_id" => (isset($externSrc['valasId']) && isset($sessionData['main'][$externSrc['valasId']])) ? $sessionData['main'][$externSrc['valasId']] : '',
                                "valas_nama" => (isset($externSrc['valasLabel']) && isset($sessionData['main'][$externSrc['valasLabel']])) ? $sessionData['main'][$externSrc['valasLabel']] : '',
                                "valas_nilai" => (isset($externSrc['valasValue']) && isset($sessionData['main'][$externSrc['valasValue']])) ? $sessionData['main'][$externSrc['valasValue']] : 0,
                                "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($sessionData['main'][$externSrc['valasTagihan']])) ? $sessionData['main'][$externSrc['valasTagihan']] : 0,
                                "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($sessionData['main'][$externSrc['valasTerbayar']])) ? $sessionData['main'][$externSrc['valasTerbayar']] : 0,
                                "sisa_valas" => (isset($externSrc['valasSisa']) && isset($sessionData['main'][$externSrc['valasSisa']])) ? $sessionData['main'][$externSrc['valasSisa']] : 0,
                                "extern_label2" => (isset($externSrc['extern_label2']) && ($sessionData['main'][$externSrc['extern_label2']])) ? $sessionData['main'][$externSrc['extern_label2']] : "",
                                "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData['main'][$externSrc['extern_nilai2']])) ? $sessionData['main'][$externSrc['extern_nilai2']] : 0,
                            ));
                        }


                    }
                    else {
                        $arrDataPym = array(
                            "jenis" => $stepCode,
                            "target_jenis" => $paymentSrcConfig['jenisTarget'],
                            "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                            "extern_id" => $sessionData['main'][$externSrc['id']],
                            "extern_nama" => $sessionData['main'][$externSrc['nama']],
                            "nomer" => $sessionData['main']['nomer'],
                            "label" => $paymentSrcConfig['label'],
                            "tagihan" => isset($sessionData['main'][$valueSrc]) ? $sessionData['main'][$valueSrc] : 0,
                            "terbayar" => 0,
                            "sisa" => isset($sessionData['main'][$valueSrc]) ? $sessionData['main'][$valueSrc] : 0,
                            "cabang_id" => $sessionData['main']['placeID'],
                            "cabang_nama" => $sessionData['main']['placeName'],
                            "oleh_id" => $kiriman["oleh_id"],
                            "oleh_nama" => $kiriman["oleh_nama"],
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                            "valas_id" => (isset($externSrc['valasId']) && isset($sessionData['main'][$externSrc['valasId']])) ? $sessionData['main'][$externSrc['valasId']] : '',
                            "valas_nama" => (isset($externSrc['valasLabel']) && isset($sessionData['main'][$externSrc['valasLabel']])) ? $sessionData['main'][$externSrc['valasLabel']] : '',
                            "valas_nilai" => (isset($externSrc['valasValue']) && isset($sessionData['main'][$externSrc['valasValue']])) ? $sessionData['main'][$externSrc['valasValue']] : 0,
                            "tagihan_valas" => (isset($externSrc['valasTagihan']) && isset($sessionData['main'][$externSrc['valasTagihan']])) ? $sessionData['main'][$externSrc['valasTagihan']] : 0,
                            "terbayar_valas" => (isset($externSrc['valasTerbayar']) && isset($sessionData['main'][$externSrc['valasTerbayar']])) ? $sessionData['main'][$externSrc['valasTerbayar']] : 0,
                            "sisa_valas" => (isset($externSrc['valasSisa']) && isset($sessionData['main'][$externSrc['valasSisa']])) ? $sessionData['main'][$externSrc['valasSisa']] : 0,
                            "extern_label2" => (isset($externSrc['extern_label2']) && ($sessionData['main'][$externSrc['extern_label2']])) ? $sessionData['main'][$externSrc['extern_label2']] : "",
                            "extern_nilai2" => (isset($externSrc['extern_nilai2']) && ($sessionData['main'][$externSrc['extern_nilai2']])) ? $sessionData['main'][$externSrc['extern_nilai2']] : 0,
                            "payment_locked" => (isset($externSrc['payment_locked']) && ($sessionData['main'][$externSrc['payment_locked']])) ? $sessionData['main'][$externSrc['payment_locked']] : 0,
                            "cash_account" => (isset($externSrc['cash_account']) && ($sessionData['main'][$externSrc['cash_account']])) ? $sessionData['main'][$externSrc['cash_account']] : 0,
                            "cash_account_nama" => (isset($externSrc['cash_account_nama']) && ($sessionData['main'][$externSrc['cash_account_nama']])) ? $sessionData['main'][$externSrc['cash_account_nama']] : 0,
                            "extern2_id" => (isset($externSrc['extern2_id']) && ($sessionData['main'][$externSrc['extern2_id']])) ? $sessionData['main'][$externSrc['extern2_id']] : 0,
                            "extern2_nama" => (isset($externSrc['extern2_nama']) && ($sessionData['main'][$externSrc['extern2_nama']])) ? $sessionData['main'][$externSrc['extern2_nama']] : 0,
                        );
                        arrPrintWebs($arrDataPym);
                        $tr->writePaymentSrc($insertID, $arrDataPym);
                    }

                    cekMerah($this->CI->db->last_query());
                }
            }
        }
        else {
            cekMerah("TIDAK ADA PEMBALIK paymentSrc");
        }
        //endregion

        //region nulis paymentAntiSource
        $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
        $paymentSources = $this->CI->config->item("payment_antiSource") != null ? $this->CI->config->item("payment_antiSource") : array();
        if (array_key_exists($stepCode, $paymentSources)) {
            cekHitam(":: starting PAYMENT ANTI SOURCE");
            $payConfigs = $paymentSources[$stepCode];
            if (sizeof($payConfigs) > 0) {
                foreach ($payConfigs as $paymentSrcConfig) {
                    //					$paymentSrcConfig = $paymentSources[$stepCode];
                    $valueSrc = $paymentSrcConfig['valueSrc'];
                    $externSrc = $paymentSrcConfig['externSrc'];
                    $tr->writePaymentAntiSrc($insertID, array(
                        "jenis" => $stepCode,
                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                        "extern_id" => $sessionData['main'][$externSrc['id']],
                        "extern_nama" => $sessionData['main'][$externSrc['nama']],
                        "nomer" => $sessionData['main']['nomer'],
                        "label" => $paymentSrcConfig['label'],
                        "tagihan" => $sessionData['main'][$valueSrc],
                        "terbayar" => 0,
                        "sisa" => $sessionData['main'][$valueSrc],
                        "cabang_id" => $sessionData['main']['placeID'],
                        "cabang_nama" => $sessionData['main']['placeName'],
                        "oleh_id" => $kiriman["oleh_id"],
                        "oleh_nama" => $kiriman["oleh_nama"],
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                        "valas_id" => isset($sessionData['main'][$externSrc['valasId']]) ? $sessionData['main'][$externSrc['valasId']] : '',
                        "valas_nama" => isset($sessionData['main'][$externSrc['valasLabel']]) ? $sessionData['main'][$externSrc['valasLabel']] : '',
                        "valas_nilai" => isset($sessionData['main'][$externSrc['valasValue']]) ? $sessionData['main'][$externSrc['valasValue']] : '',
                        "tagihan_valas" => isset($sessionData['main'][$externSrc['valasTagihan']]) ? $sessionData['main'][$externSrc['valasTagihan']] : '',
                        "terbayar_valas" => isset($sessionData['main'][$externSrc['valasTerbayar']]) ? $sessionData['main'][$externSrc['valasTerbayar']] : '',
                        "sisa_valas" => isset($sessionData['main'][$externSrc['valasSisa']]) ? $sessionData['main'][$externSrc['valasSisa']] : '',

                    ));
                    //cekMerah($this->CI->db->last_query());
                }
            }


        }
        else {
            //cekMerah("TIDAK nulis paymentSrc");
        }
        //endregion


        //====registri value-gate
        if (isset($configCoreMasterModulJenis['components'][$jenisTrTarget]) && sizeof($configCoreMasterModulJenis['components'][$jenisTrTarget])) {
            $jurnalIndex = $configCoreMasterModulJenis['components'][$jenisTrTarget];
        }
        else {
            if (isset($sessionData["revert"]["jurnal"]) && sizeof($sessionData["revert"]["jurnal"]) > 0) {
                $jurnalIndex = $sessionData["revert"]["jurnal"];
            }
            else {
                $jurnalIndex = array();
            }
        }
        //---------------------------------------------------
        if (isset($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget]) && sizeof($configCoreMasterModulJenis['postProcessor'][$jenisTrTarget])) {
            $jurnalPostProc = $configCoreMasterModulJenis['postProcessor'][$jenisTrTarget];
        }
        else {
            if (isset($sessionData["revert"]["postProc"]) && sizeof($sessionData["revert"]["postProc"]) > 0) {
                $jurnalPostProc = $sessionData["revert"]["postProc"];
            }
            else {
                $jurnalPostProc = array();
            }
        }
        //---------------------------------------------------
        if (isset($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget]) && sizeof($configCoreMasterModulJenis['preProcessor'][$jenisTrTarget])) {
            $jurnalPreProc = $configCoreMasterModulJenis['preProcessor'][$jenisTrTarget];
        }
        else {
            if (isset($sessionData["revert"]["preProc"]) && sizeof($sessionData["revert"]["preProc"]) > 0) {
                $jurnalPreProc = $sessionData["revert"]["preProc"];
            }
            else {
                $jurnalPreProc = array();
            }
        }
        //---------------------------------------------------


        $baseRegistries = array(
            'main' => isset($sessionData['main']) ? $sessionData['main'] : array(),
            'items' => isset($sessionData['items']) ? $sessionData['items'] : array(),
            'items2' => isset($sessionData['items2']) ? $sessionData['items2'] : array(),
            'items2_sum' => isset($sessionData['items2_sum']) ? $sessionData['items2_sum'] : array(),
            'itemSrc' => isset($sessionData['itemSrc']) ? $sessionData['itemSrc'] : array(),
            'itemSrc_sum' => isset($sessionData['itemSrc_sum']) ? $sessionData['itemSrc_sum'] : array(),
            'items3' => isset($sessionData['items3']) ? $sessionData['items3'] : array(),
            'items3_sum' => isset($sessionData['items3_sum']) ? $sessionData['items3_sum'] : array(),
            'items4' => isset($sessionData['items4']) ? $sessionData['items4'] : array(),
            'items4_sum' => isset($sessionData['items4_sum']) ? $sessionData['items4_sum'] : array(),
            'items5_sum' => isset($sessionData['items5_sum']) ? $sessionData['items5_sum'] : array(),
            'items6_sum' => isset($sessionData['items6_sum']) ? $sessionData['items6_sum'] : array(),
            'items7_sum' => isset($sessionData['items7_sum']) ? $sessionData['items7_sum'] : array(),
            'items8_sum' => isset($sessionData['items8_sum']) ? $sessionData['items8_sum'] : array(),
            'items9_sum' => isset($sessionData['items9_sum']) ? $sessionData['items9_sum'] : array(),
            'items10_sum' => isset($sessionData['items10_sum']) ? $sessionData['items10_sum'] : array(),
            'rsltItems' => isset($sessionData['rsltItems']) ? $sessionData['rsltItems'] : array(),
            'rsltItems_revert' => isset($sessionData['rsltItems_revert']) ? $sessionData['rsltItems_revert'] : array(),
            'rsltItems2_revert' => isset($sessionData['rsltItems2_revert']) ? $sessionData['rsltItems2_revert'] : array(),
            'rsltItems2' => isset($sessionData['rsltItems2']) ? $sessionData['rsltItems2'] : array(),
            'rsltItems3' => isset($sessionData['rsltItems3']) ? $sessionData['rsltItems3'] : array(),
            'tableIn_master' => isset($sessionData['tableIn_master']) ? $sessionData['tableIn_master'] : array(),
            'tableIn_detail' => isset($sessionData['tableIn_detail']) ? $sessionData['tableIn_detail'] : array(),
            'tableIn_detail2_sum' => isset($sessionData['tableIn_detail2_sum']) ? $sessionData['tableIn_detail2_sum'] : array(),
            'tableIn_detail_rsltItems' => isset($sessionData['tableIn_detail_rsltItems']) ? $sessionData['tableIn_detail_rsltItems'] : array(),
            'tableIn_detail_rsltItems2' => isset($sessionData['tableIn_detail_rsltItems2']) ? $sessionData['tableIn_detail_rsltItems2'] : array(),
            'tableIn_master_values' => isset($sessionData['tableIn_master_values']) ? $sessionData['tableIn_master_values'] : array(),
            'tableIn_detail_values' => isset($sessionData['tableIn_detail_values']) ? $sessionData['tableIn_detail_values'] : array(),
            'tableIn_detail_values_rsltItems' => isset($sessionData['tableIn_detail_values_rsltItems']) ? $sessionData['tableIn_detail_values_rsltItems'] : array(),
            'tableIn_detail_values_rsltItems2' => isset($sessionData['tableIn_detail_values_rsltItems2']) ? $sessionData['tableIn_detail_values_rsltItems2'] : array(),
            'tableIn_detail_values2_sum' => isset($sessionData['tableIn_detail_values2_sum']) ? $sessionData['tableIn_detail_values2_sum'] : array(),
            'main_add_values' => isset($sessionData['main_add_values']) ? $sessionData['main_add_values'] : array(),
            'main_add_fields' => isset($sessionData['main_add_fields']) ? $sessionData['main_add_fields'] : array(),
            'main_elements' => isset($sessionData['main_elements']) ? $sessionData['main_elements'] : array(),
            'main_inputs' => isset($sessionData['main_inputs']) ? $sessionData['main_inputs'] : array(),
            'main_inputs_orig' => isset($sessionData['main_inputs']) ? $sessionData['main_inputs'] : array(),
            "receiptDetailFields" => isset($this->configLayout[$this->jenisTr]['receiptDetailFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields'][1] : array(),
            "receiptSumFields" => isset($this->configLayout[$this->jenisTr]['receiptSumFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptSumFields'][1] : array(),
            "receiptDetailFields2" => isset($this->configLayout[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailFields2'][1] : array(),
            "receiptDetailSrcFields" => isset($this->configLayout[$this->jenisTr]['receiptDetailSrcFields'][1]) ? $this->configLayout[$this->jenisTr]['receiptDetailSrcFields'][1] : array(),
            "receiptSumFields2" => isset($this->configLayout[$this->jenisTr]['receiptSumFields2'][1]) ? $this->configLayout[$this->jenisTr]['receiptSumFields2'][1] : array(),
            "jurnal_index" => $jurnalIndex,
            "postProcessor" => $jurnalPostProc,
            "preProcessor" => $jurnalPreProc,
            "revert" => isset($sessionData['revert']) ? $sessionData['revert'] : array(),
            "items_komposisi" => isset($sessionData['items_komposisi']) ? $sessionData['items_komposisi'] : array(),
            "items_noapprove" => isset($sessionData['items_noapprove']) ? $sessionData['items_noapprove'] : array(),
            "jurnalItems" => isset($sessionData['jurnalItems']) ? $sessionData['jurnalItems'] : array(),
            "componentsBuilder" => isset($sessionData['componentsBuilder']) ? $sessionData['componentsBuilder'] : array(),
        );
        $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
        $mongRegID = $doWriteReg;
        //            cekHitam($this->CI->db->last_query());


    }


}



