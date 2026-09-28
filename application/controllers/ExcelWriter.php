<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 3:20 PM
 */
class ExcelWriter extends CI_Controller
{

    public function harga()
    {
        $this->file = $this->uri->segment(2);

        $this->load->library('Excel');
        $ex = new Excel();

        $this->q = isset($_GET['q']) ? $_GET['q'] : null;
        $this->selectedID = isset($_GET['sID']) && $_GET['sID'] > 0 ? $_GET['sID'] : null;
        //$selectedProuct = $this->uri->segment(2);

        $this->ix = "cabang";
        $this->iy = "produk";
        //        $this->iy = "supplies";
        $this->iz = "hargaProduk";

        // region grab data

        // arrPrint($this->config->item("hePrices"));
        $this->priceConfig = null != ($this->config->item("hePrices")[$this->iy]) ? $this->config->item("hePrices")[$this->iy] : array();
        // cabang
        $this->x = array(
            "mdlName" => "Mdl" . ucwords($this->ix),
            "label" => ucwords($this->ix),
            "entries" => array(),
        );
        // produk
        $this->y = array(
            "mdlName" => "Mdl" . ucwords($this->iy),
            "label" => ucwords($this->iy),
            "entries" => array(),
            // "total="  => 0,
        );
        // hargaProduk
        $this->z = array(
            "mdlName" => "Mdl" . ucwords($this->iz),
            "label" => ucwords($this->iz),
            "entries" => array(),
            "rawEntries" => array(),
            "hisPrice" => array(),
            "listHistory" => array(),
        );

        //region initX == cabang
        $this->load->model("Mdls/" . $this->x['mdlName']);
        $xo = new $this->x['mdlName']();
        $tmpX = $xo->lookupAll()->result();
        //        cekMerah($this->db->last_query());
        //        arrPrint($tmpX);die();
        if (sizeof($tmpX) > 0) {
            foreach ($tmpX as $row) {
                $this->x['entries'][$row->id] = $row->nama;
            }


        }
        else {
            $this->x['entries'] = array();
        }
        //endregion

        //region init Y == produk
        $this->load->model("Mdls/" . $this->y['mdlName']);
        $yo = new $this->y['mdlName']();
        //        $tmpY=$yo->lookupAll()->result();

        if ($this->selectedID != null) {
            $yo->addFilter("id='" . $this->selectedID . "'");
        }

        $tmpY = $yo->lookupAll()->result();

        if (sizeof($tmpY) > 0) {
            foreach ($tmpY as $row) {
                $this->y['entries'][$row->id] = str_replace(" ", "&nbsp;", $row->kode . " " . $row->nama);
                //                $this->y['entries'][$row->id]=$row->nama;
            }
        }
        else {
            $this->y['entries'] = array();
        }

        if (sizeof($this->y['entries']) < 1 || sizeof($this->x['entries']) < 1) {
            die("Unable to determine the members of X or Y axis");
        }
        //endregion

        //region initZ == harga
        $this->load->model("Mdls/" . $this->z['mdlName']);
        $zo = new $this->z['mdlName']();

        $tmpZ = $zo->lookupAll()->result();
        // arrPrint($tmpZ);
        // die();
        //        cekMerah($this->db->last_query());
        //        print_r($tmpZ);
        //        die();
        if (sizeof($tmpZ) > 0) {
            foreach ($tmpZ as $row) {
                $yPoint = $this->iy . "_id";
                $xPoint = $this->ix . "_id";
                //                $this->z['entries'][$row->$yPoint][$row->$xPoint][$row->jenis_value]=$row->nilai;
                //                $this->existingValues[$row->$yPoint][$row->$xPoint][$row->jenis_value]=$row->nilai;
                $this->z['entries'][$row->produk_id][$row->$xPoint][$row->jenis_value] = $row->nilai;
                $this->z['rawEntries'][$row->produk_id][$row->$xPoint][$row->jenis_value] = $row->nilai;
                $this->z['hisPrice'][$row->produk_id][$row->$xPoint][] = $row->jenis_value;
                $this->existingValues[$row->produk_id][$row->$xPoint][$row->jenis_value] = $row->nilai;
            }
        }
        else {
            $this->z['entries'] = array();
        }
        //===normalize
        $arrListHistory = array();
        foreach ($this->y['entries'] as $yID => $yName) {
            foreach ($this->x['entries'] as $xID => $xName) {
                foreach ($this->priceConfig as $zID => $zName) {

                    //region historyprice
                    $linkHist = base_url() . get_class($this) . "/HargaHistory/$yID/$xID/$zID";
                    $historyClick = "BootstrapDialog.closeAll();
        
                    BootstrapDialog.show(
                                   {
                                        title:' histories $zID ',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        draggable:true,
                                        closable:true,
        
                                        }
                                        );";

                    //endregion

                    if (!isset($this->z['entries'][$yID])) {
                        $this->z['entries'][$yID] = array();
                    }
                    if (!isset($this->z['entries'][$yID][$xID])) {
                        $this->z['entries'][$yID][$xID] = array();
                    }
                    if (!array_key_exists($zID, $this->z['entries'][$yID][$xID])) {
                        $this->z['entries'][$yID][$xID][$zID] = 0;
                    }

                    // if (isset($this->z['entries'][$yID][$xID][$zID])) {
                    //     //                        echo "replace";
                    //     $savedVal = $this->z['entries'][$yID][$xID][$zID];
                    //     //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
                    //     $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
                    //     $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value='" . $savedVal . "'>";
                    //
                    // }
                    // else {
                    //     //                        echo "insert";
                    //     $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value=''>";
                    // }

                    if (isset($this->z['rawEntries'][$yID][$xID][$zID])) {
                        //                        echo "replace";
                        $savedVal = $this->z['rawEntries'][$yID][$xID][$zID];
                        //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
                        $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
                        $this->z['rawEntries'][$yID][$xID][$zID] = $savedVal;

                    }
                    else {
                        //                        echo "insert";
                        $this->z['rawEntries'][$yID][$xID][$zID] = "";
                    }
                    $this->z['listHistory'][$yID][$xID][$zID] = "<a class='btn btn-default' href='javascript:void(0)' data-toggle-tip='tooltip' data-placement='left' title='view $zID update histories  ' onclick=\"$historyClick\"><i class='fa fa-clock-o'></i></a>";
                }
            }
        }
        //endregion

        // endregion

        // region pairing data
        $xLabels = $this->x['entries'];
        $yLabels = $this->y['entries'];
        $zLabels = $this->z['entries'];
        // arrPrint($zLabels);
        $number = 0;
        $datas = array();
        // $this->ix = "cabang";
        // $this->iy = "produk";
        // $this->iz = "hargaProduk";
        // arrPrint($this->y);
        // arrPrint($yLabels);
        // matiHere();
        $dataSpec = array();
        foreach ($yLabels as $yId => $yNames) {
            $number++;
            // cekHijau("$yNames");
            $yNames_f = html_entity_decode($yNames);
            $dataSpec['id'] = "$yId";

            $dataSpec['nama'] = "$yNames_f";

            // matiHere();
            foreach ($xLabels as $xId => $xNames) {
                // cekHijau($xNames . " $xId pro: $yId");
                // arrPrint($zLabels[$yId]);
                // matiHere();
                // foreach ($zLabels as $zid => $zNames) {
                $hargas = $zLabels[$yId][$xId];
                // arrPrint($hargas);

                if (isset($hargas)) {
                    //
                    //     // arrPrint($zNames[$xId]);
                    //                    $dataSpec['qty_' . $xNames] = $hargas['hpp'];
                    $dataSpec['hpp_' . $xNames] = $hargas['hpp'];
                    $dataSpec['jual_' . $xNames] = $hargas['jual'];
                    //     cekHere("gg");
                }
                else {
                    $dataSpec['hpp_' . $xNames] = 0;
                    $dataSpec['jual_' . $xNames] = 0;
                }
                // $dataSpec['jual_'.$xNames] = $zNames[$xId]['jual'];
                // }
            }

            $datas[] = (object)$dataSpec;
        }
        // endregion pairing data

        // arrPrint($content);
        // arrPrint($this->z);
        // arrPrint($this->z);
        // arrPrint($datas);
        // matiHere(__LINE__);

        // $this->load->model('MdlEmployee');
        // $tm = new MdlEmployee();
        // $datasX = $tmpX = $tm->lookupAll()->result();
        // arrPrint($datasX);
        // matiHere(__LINE__);
        $headers = array(
            "id" => array(
                "label" => "id",
                "type" => "integer",
            ),
            "nama" => array(
                "label" => "Nama Produk",
                "type" => "string",
            ),
            "hpp_PUSAT" => array(
                "label" => "HPP Pusat",
                "type" => "integer",
            ),
            // "jual_PUSAT" => array(
            //     "label" => "Harga Jual Pusat",
            //     "type"  => "integer",
            // ),
            //            "hpp_jakarta"  => array(
            //                "label" => "HPP Markoni",
            //                "type"  => "integer",
            //            ),
            //            "jual_" => array(
            //                "label" => "Harga Jual Markoni",
            //                "type"  => "integer",
            //            ),
        );
        // arrPrint($datas);
        // matiHere();
        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();
    }

    public function exp()
    {
        $dataCabang = getCabangData();
        $dtime_now_1 = dtimeNow("Y-m") . "-01";
        $dtime_now = dtimeNow("Y-m-d");
        $jenis = $this->file = $this->uri->segment(3);
        $old = $this->uri->segment(4) ? $this->uri->segment(4) : "";
        $date_1 = isset($_GET['date1']) && (strlen($_GET['date1']) > 0) ? $_GET['date1'] : $dtime_now_1;
        $date_2 = isset($_GET['date2']) && (strlen($_GET['date2']) > 0) ? $_GET['date2'] : $dtime_now;
        $jenis_master = isset($_GET['jmaster']) ? $_GET['jmaster'] : "";
        $reqCode = isset($_GET['reqCode']) ? blobDecode($_GET['reqCode']) : "";

        // $jenis_master = 7762;
        switch ($jenis) {
            case "467":
                $jenis_master = "466";
                break;
        }
        // cekHere($jenis);
        // cekMerah($jenis_master);
        if ($jenis_master != "") {
            $configPaths = loadConfigPathModul();
            $configPath = $configPaths[$jenis_master];
            $this->load->config($configPath . "coTransaksiLayout");
            $configLayout = $this->config->item("coTransaksiLayout");
            if (!isset($configLayout[$jenis_master]['customHistoriExcel'])) {
                cekMerah("customHistoryExcel harap diset dulu " . __METHOD__ . " @" . __LINE__);
            }

            $history_header = $configLayout[$jenis_master]['customHistoriExcel'];
            // arrPrintKuning($history_header);
        }
        else {
            cekMerah("header akan mengunakan default value");
        }
        // matiHere(__LINE__);
        $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();

        $this->q = isset($_GET['q']) ? $_GET['q'] : null;
        $this->selectedID = isset($_GET['sID']) && $_GET['sID'] > 0 ? $_GET['sID'] : null;
        //$selectedProuct = $this->uri->segment(2);

        //region init Y == produk
        // $this->load->model("Mdls/MdlTransaksi");
        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();


        // $tr->addFilter("dtime>='" . $date_1 . "'");
        // $tr->addFilter("dtime<='" . $date_2 . "'");


        if (strlen($reqCode) > 4) {
            $tr->addFilter($reqCode);
            $wheres = "DATE(dtime) >= '$date_1' AND DATE(dtime) <= '$date_2'";
        }
        else {
            $wheres = "jenis='$jenis' AND DATE(dtime) >= '$date_1' AND DATE(dtime) <= '$date_2'";
        }
        $tmpA = $tr->lookupByCondition($wheres)->result();
        //        cekLime($this->db->last_query());
        // matiHere();
        $aKoloms = array(
            "nomer",
            "dtime",
            "time",
            "oleh_nama",
            "trash_4",
        );
        $trIds = array();
        if (sizeof($tmpA) > 0) {
            $trIds_0 = array();
            foreach ($tmpA as $item) {
                $params = array();
                foreach ($aKoloms as $aKolom) {
                    $$aKolom = $item->$aKolom;
                    if ($old == "old") {
                        if ($aKolom == "nomer") {
                            $params[$aKolom] = formatTransNomer($item->$aKolom, $jenis, -1);
                        }
                        else {
                            $params[$aKolom] = formatField2($aKolom, $item->$aKolom);
                        }
                    }
                    elseif ($aKolom == "time") {
                        $params[$aKolom] = date('H:i', strtotime($item->dtime));
                    }
                    else {

                        $params[$aKolom] = formatField2($aKolom, $item->$aKolom);
                    }
                }


                $trIds_0[$item->id] = 1;

                $trIdParams[$item->id] = $params;
            }
            $trIds = array_keys($trIds_0);
        }

        $trIdList = implode("','", $trIds);
        switch ($old) {
            default:
                /* ===================================================
                 * untuk menapilkan data tambahan::
                 * 1) tambahan pada array kolom
                 * 2) tambahakan padaa header excel
                 * ----------------------------------------*/

                $bKoloms = array(
                    // "transaksi_id",
                    "jml",
                    "sub_harga",
                    "sub_nett1",
                    "sub_ppn",
                    "sub_nett2",
                    "sub_hpp",
                    "sub_ppn",
                    "sub_hpp_nppn",
                    "sub_nett",
                    "harga",
                    "harga_pricelist",
                    "diskon_1_nilai",
                    "sub_diskon_nilai_total",
                    "sub_diskon_7_nilai",
                    "id",
                    "nama",
                    "produk_kode",
                    "pihakName",
                );
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id in ('$trIdList')");
                //                $tr->addFilter("param='items'");
                $tr->setJointSelectFields("items, transaksi_id");
                //                $tmpB = $tr->lookupRegistries()->result();
                $tmpB = $tr->lookupDataRegistries()->result();
                // cekMerah($this->db->last_query());

                // ---------------------------------------------main
                $cKoloms = array(
                    "customerDetails__kabupaten",
                    "salesmanDetails__nama",
                    // "billingDetails__propinsi",
                    "description",
                    "pihak3Name",
                    "category_expense__nama",
                    "jenisTrName",
                    // "transaksi_id",
                    "referenceNomer__1",
                    "referenceNomer__2",
                    "referenceNomer__3",
                    "description_main_followup",
                    // "sub_hpp",
                    // "sub_ppn",
                    // "sub_hpp_nppn",
                    // "sub_nett",
                    // "harga",
                    // "harga_pricelist",
                    // "diskon_1_nilai",
                    // "sub_diskon_nilai_total",
                    // "sub_diskon_7_nilai",
                    "subtotal",
                );
                //                $tr->addFilter("param='main'");
                $tr->setJointSelectFields("main, transaksi_id");
                //                $tmpC = $tr->lookupRegistries()->result();
                $tmpC = $tr->lookupDataRegistries()->result();

                foreach ($tmpC as $cItems) {
                    // arrPrint($cItems);
                    // matiHere(__LINE__);
                    $cTransaksi_id = $cItems->transaksi_id;
                    $cValues = blobDecode($cItems->main);
                    // arrPrint($cValues);
                    // matiHere();
                    foreach ($cKoloms as $cKolom) {
                        $$cKolom = $cValues[$cKolom];
                        switch ($cKolom) {
                            case "referenceNomer__1":
                            case "referenceNomer__2":
                            case "referenceNomer__3":
                                $cNilaiKolom = formatField2("nomer", $cValues[$cKolom]);
                                break;
                            default:
                                $cNilaiKolom = $cValues[$cKolom];
                                break;
                        }
                        $main_params[$cTransaksi_id][$cKolom] = $cNilaiKolom;
                    }
                    // $cValues = $cItems;
                }
                // ----------------------------------------items5_sum freeProduk-----
                $dKoloms = array(
                    "produk_id",
                    "produk_nama",
                    "produk_rel_id",
                    "produk_rel_nama",
                    "produk_rel_harga",
                    "sub_produk_rel_harga",
                    "qty_min",
                    "qty",
                    // "harga_pricelist",
                );
                $tr->setJointSelectFields("items5_sum, transaksi_id");
                $tmpD = $tr->lookupDataRegistries()->result();

                $items5_sum_params = array();
                foreach ($tmpD as $dItems) {
                    // arrPrint($dItems);
                    $dTransaksi_id = $dItems->transaksi_id;
                    $dValues = blobDecode($dItems->items5_sum);
                    // arrPrintCyan($dValues);
                    // matiHere();
                    foreach ($dValues as $dpid => $dValue) {
                        $djml = $dValue['jml'];
                        if ($djml > 0) {
                            foreach ($dKoloms as $dKolom) {
                                $$dKolom = $dValue[$dKolom];
                                $items5_sum_params[$dTransaksi_id][$dpid][$dKolom] = $dValue[$dKolom];
                            }
                        }
                    }
                    // $cValues = $cItems;
                }
                // arrPrintCyan($tmpD);
                // arrPrintCyan($items5_sum_params);
                // matiAlert(__LINE__);
                // ----------------------------------------------------------

                $trIdItems = array();
                foreach ($tmpB as $items) {
//                    arrPrint($items);
                    $transaksi_id = $items->transaksi_id;
                    $values = blobDecode($items->items);
                    // foreach ($tmpC as $cItems) {
                    // $cValues = $cItems
                    // cekBiru($values);
                    // matiHere(__LINE__);
                    // }
                    // $mains = blobDecode($items->values);
//arrPrint($value);
                    foreach ($values as $value) {
                        $cpid = $value['id'];
                        $params = array();
                        foreach ($dKoloms as $dKolom) {
                            $params[$dKolom] = $items5_sum_params[$transaksi_id][$cpid][$dKolom];
                        }
                        foreach ($cKoloms as $cKolom) {
                            $params[$cKolom] = $main_params[$transaksi_id][$cKolom];
                            $params["testc"] = "okc";
                        }
                        foreach ($bKoloms as $bKolom) {
                            $$bKolom = $value[$bKolom];
                            $params[$bKolom] = $value[$bKolom];
                            $params["testb"] = "okb";
                        }
                        // cekHijau($transaksi_id);
                        foreach ($aKoloms as $aKolom) {
                            // $params[$aKolom] = 0;
                            $params[$aKolom] = $trIdParams[$transaksi_id][$aKolom];
                        }
                        $params['m_kode_cabang'] = $dataCabang[$value["cabangID"]]["kode_cabang"];
                        $params['m_cabang_nama'] = $dataCabang[$value["cabangID"]]["nama"];

                        $trIdItems[] = $params;
                    }
                    //                     arrPrint($main_params);
                    //                    mati_disini();
                    $headers = array(
                        // "no"          => array(
                        //     "label" => "No",
                        //     "type"  => "integer",
                        // ),
                        "nomer" => array(
                            "label" => "INV",
                            "type" => "string",
                        ),
                        "dtime" => array(
                            "label" => "tanggal",
                            "type" => "string",
                        ),
                        "m_kode_cabang" => array(
                            "label" => "kode cabang",
                            "type" => "string",
                        ),
                        "m_cabang_nama" => array(
                            "label" => "cabang",
                            "type" => "string",
                        ),
                        "nama" => array(
                            "label" => "Model",
                            "type" => "string",
                        ),
                        "produk_kode" => array(
                            "label" => "type",
                            "type" => "string",
                        ),
                        "pihakName" => array(
                            "label" => "Customer",
                            "type" => "string",
                        ),
                        "customerDetails__kabupaten" => array(
                            "label" => "Kota",
                            "type" => "string",
                        ),
                        "oleh_nama" => array(
                            "label" => "Person",
                            "type" => "string",
                        ),
                        "salesmanDetails__nama" => array(
                            "label" => "salesman",
                            "type" => "string",
                        ),
                        // "billingDetails__propinsi"        => array(
                        //     "label" => "Prop",
                        //     "type"  => "string",
                        // ),
                        "jml" => array(
                            "label" => "Qty",
                            "type" => "integer",
                        ),
                        "sub_harga" => array(
                            "label" => "Price",
                            "type" => "integer",
                        ),
                        "sub_nett1" => array(
                            "label" => "DPP",
                            "type" => "integer",
                        ),
                        //                        "sub_ppn" => array(
                        //                            "label" => "PPN",
                        //                            "type" => "integer",
                        //                        ),
                        //                        "sub_nett2" => array(
                        //                            "label" => "TOTAL",
                        //                            "type" => "integer",
                        //                        ),
                        "trash_4" => array(
                            "label" => "STATUS",
                            "type" => "text",
                        ),
                    );

                    /* ---------------------------------------------------------
                     * header diatur dr cotransakiLayout//customHistoriExcel
                     * kalau tidak ada akan mengunakan defaultnya diats itu
                     * conto @biaya/7762
                     * ---------------------------------------------------------*/
                    if (isset($history_header)) {
                        $headers = $history_header;
                    }

                }
                break;
            case "old":
                $bKoloms = array(
                    "transaksi_id",
                    "produk_ord_jml",
                    "produk_hrg_ori",
                    "produk_ord_diskon",
                    "produk_ord_hrg",
                    "sub_nett1",
                    "ppn",
                    "sub_nett2",
                    "produk_nama",
                    "produk_kode",
                    // "pihakName",
                );
                $tr->setTableName("transaksi_data");
                $tr->setFilters(array());
                // $this->db->order_by('id');
                $tmpDatas = $tr->lookupByCondition("produk_jenis='produk' AND
                transaksi_id in ('" . $trIdList . "') AND trash='0' AND produk_ord_jml>'0'")->result();
                // cekMerah($this->db->last_query());
                // arrPrint($tmpDatas);
                foreach ($tmpDatas as $tmpData) {
                    $params = array();
                    foreach ($bKoloms as $bKolom) {
                        $$bKolom = isset($tmpData->$bKolom) ? $tmpData->$bKolom : 0;

                        $sParams[$bKolom] = isset($tmpData->$bKolom) ? $tmpData->$bKolom : 0;
                    }
                    $rParams['m_kode_cabang'] = $dataCabang[$tmpData->cabang_id]["kode_cabang"];
                    $rParams['m_cabang_nama'] = $dataCabang[$tmpData->cabang_id]["nama"];
                    $rParams['ppn'] = ($produk_ord_jml * $ppn);
                    $rParams['sub_nett1'] = ($produk_ord_jml * $produk_ord_hrg);
                    $rParams['sub_nett2'] = ($produk_ord_jml * $produk_ord_hrg) + ($produk_ord_jml * $ppn);
                    $params = array_replace($sParams, $rParams);
                    // $params[$bKolom] = isset($tmpData->$bKolom) ? $tmpData->$bKolom : 0;

                    foreach ($aKoloms as $aKolom) {

                        // $params[$aKolom] = 0;
                        $params[$aKolom] = $trIdParams[$transaksi_id][$aKolom];
                    }
                    $trIdItems[] = $params;
                }
                $headers = array(
                    // "no"          => array(
                    //     "label" => "No",
                    //     "type"  => "integer",
                    // ),
                    "nomer" => array(
                        "label" => "INV",
                        "type" => "string",
                    ),
                    "dtime" => array(
                        "label" => "tanggal",
                        "type" => "string",
                    ),
                    "produk_nama" => array(
                        "label" => "Model",
                        "type" => "string",
                    ),
                    "produk_kode" => array(
                        "label" => "type",
                        "type" => "string",
                    ),
                    // "pihakName"   => array(
                    //     "label" => "Customer",
                    //     "type"  => "string",
                    // ),
                    "produk_ord_jml" => array(
                        "label" => "Qty",
                        "type" => "integer",
                    ),
                    "produk_ord_hrg" => array(
                        "label" => "Price",
                        "type" => "integer",
                    ),
                    "sub_nett1" => array(
                        "label" => "DPP",
                        "type" => "integer",
                    ),
                    "ppn" => array(
                        "label" => "PPN",
                        "type" => "integer",
                    ),
                    "sub_nett2" => array(
                        "label" => "TOTAL",
                        "type" => "integer",
                    ),
                );
                // cekHitam($trIdList);
                break;
        }

//arrPrintKuning($trIdItems);
//matiHere();
        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();
        foreach ($trIdItems as $data) {
            // arrPrint($data);

            // if($data["referenceNomer__1"] != "PRE.PO.FG.-1.1840"){
            //     matiHere(__LINE__);
            // }

            $no++;
            // cekHijau("$yNames");
            // $yNames_f = html_entity_decode($yNames);
            foreach ($headers as $header => $aliasing) {
                if ($header == "trash_4") {
                    if ($data[$header] == 0) {
                        $dataSpec[$header] = "ACTIVE";
                    }
                    else {
                        $dataSpec[$header] = "CANCELED";
                    }
                }
                else {

                    $dataSpec[$header] = key_exists($header, $data) ? $data[$header] : $$header;
                }
            }


            $datas[] = (object)$dataSpec;
        }

        if (ipadd() == MGK_LIVE) {
            // arrPrintHijau($datas);
            // mati_disini("SUKSES");
        }
        // endregion pairing data

        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();
    }

    public function callTransaksiCounterJenis($jenis = "")
    {
        $tbl_1 = "transaksi";
        $coloms = array(
            "id",
            "_company_stepCode",
            "_company_jenisTr",
            "_company_cabangID_jenisTr",
        );
        $this->db->select($coloms);
        $wheres = array(
            // "jenis" => "4822",
            "jenis" => $jenis,
        );
        // $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['id'];
            // $sisa = $src['sisa'];

            $src_datas[$tr_id] = $src;
        }

        return $src_datas;
    }

    public function row()
    {
        $dtime_now_0 = dtimeNow();
        $dtime_now_1 = dtimeNow("Y-m") . "-01";
        $dtime_now = dtimeNow("Y-m-d");
        $jenis = $this->file = $this->uri->segment(3);
        $old = $this->uri->segment(4) ? $this->uri->segment(4) : "";
        $date_1 = isset($_GET['date1']) && (strlen($_GET['date1']) > 0) ? $_GET['date1'] : $dtime_now_1;
        $date_2 = isset($_GET['date2']) && (strlen($_GET['date2']) > 0) ? $_GET['date2'] : $dtime_now;
        $dataCabang = getCabangData();

        $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();

        $this->q = isset($_GET['q']) ? $_GET['q'] : null;
        $this->selectedID = isset($_GET['sID']) && $_GET['sID'] > 0 ? $_GET['sID'] : null;
        //$selectedProuct = $this->uri->segment(2);

        //region init Y == produk
        // $this->load->model("Mdls/MdlTransaksi");
        // $this->load->model("MdlTransaksi");
        // $tr = new MdlTransaksi();
        // /*----ambil nomer---*/
        // $this->db->select("_company_jenisTr");
        // // $trCondites = array(
        // //     "id" => $transaksi_id,
        // // );
        // $tr_global_coun = $tr->lookupByCondition($trCondites)->row_array();
        $tr_global_coun = $this->callTransaksiCounterJenis();

        //--------------------------
        $tbl_1 = "transaksi_payment_source";
        $wheres = array(// "sisa>" => 0,
        );
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();
        // showLast_query("biru");

        // arrPrintHijau($srcs);
        $tagihans = array();
        foreach ($srcs as $src) {
            $tr_id = $src['transaksi_id'];
            $sisa = $src['sisa'];

            $tagihans[$tr_id] = $src;
        }
        //--------------------------
        // $tr->addFilter("dtime>='" . $date_1 . "'");
        // $tr->addFilter("dtime<='" . $date_2 . "'");


        $tbl_0 = "transaksi";
        $tbl_1 = "__raw_rek_pembantu__4010";
        $cabang_id = my_cabang_id();
        if($cabang_id == CB_ID_PUSAT){
            $wheres = "$tbl_1.jenis='$jenis' AND DATE($tbl_1.dtime) >= '$date_1' AND DATE($tbl_1.dtime) <= '$date_2' ";
        }
        else{

            $wheres = "$tbl_1.jenis='$jenis' AND DATE($tbl_1.dtime) >= '$date_1' AND DATE($tbl_1.dtime) <= '$date_2' AND $tbl_1.cabang_id='$cabang_id'";
        }
        // $where_2 = array(
        //     "link_id" => "0",
        // );
        // $this->db->select("produk_id,dtime,fulldate");

        if (ipadd() == MGK_LIVE) {
//            cekBiru("MGKLIVE");
            // $wheres .= " and $tbl_0.id='417063'";
            $this->db->select("$tbl_1.*, $tbl_0.*"); // kalau mau pilih kolom spesifik tinggal sesuaikan
            $this->db->from($tbl_1);
            $this->db->join($tbl_0, "$tbl_1.transaksi_id = $tbl_0.id", "left");
            $this->db->where($wheres);
            $this->db->order_by("$tbl_1.dtime", "asc");
            $tmpA = $this->db->get()->result();
        }
        else {
//            cekBiru("BUKAN MGKLIVE");
            $this->db->where($wheres);
            $this->db->order_by("dtime", "asc");
            $tmpA = $this->db->get($tbl_1)->result();

        }
//        showLast_query("merah");
        // cekHere(count($tmpA));

        foreach ($tmpA as $item) {
            // arrPrintHijau($item);
            if (ipadd() == MGK_LIVE) {
                // matiHere(__LINE__);
            }

            $transaksi_jenis = $item->transaksi_jenis;
            $transaksi_id = $item->transaksi_id;
            $transaksi_id_so = $item->transaksi_id_2; // cash
            $pembayaran_nama = $item->pembayaran_nama;
            $cancel_name = $item->cancel_name;
            $cancel_dtime = $item->cancel_dtime;
            $produk_id = $item->produk_id;
            $dtime = formatTanggal($item->dtime, "H:i:s");
            $transaksi_kredit = $item->kredit;
            $transaksi_ppn = $transaksi_kredit * (11 / 100);
            $transaksi_inc_ppn = $transaksi_kredit + $transaksi_ppn;


            $counter = $tr_global_coun[$transaksi_id];
            $global_counter_1 = $tr_global_coun[$item->transaksi_id_1];
            $global_counter_2 = $tr_global_coun[$item->transaksi_id_2];
            $global_counter_3 = $tr_global_coun[$item->transaksi_id_3];
            $global_counter_4 = $tr_global_coun[$item->transaksi_id_4];
            // ------------------
            switch($transaksi_jenis){
                case "5823so":
                case "5823spd":
                    $tagihan = isset($tagihans[$transaksi_id_so]) ? $tagihans[$transaksi_id_so] : array();
                    // $itemtambahan['due_date'] = $transaksi_dtime;
                    // $itemtambahan['umur_now'] = umurDay($transaksi_dtime);
                    $umur_d = 0;
                    $itemtambahan['due_date'] = "-";
                    $itemtambahan['umur_now'] = "-";
                    break;
                default:
                    if ($pembayaran_nama == "cash") {
                        $tagihan = isset($tagihans[$transaksi_id_so]) ? $tagihans[$transaksi_id_so] : array();
                        // $itemtambahan['due_date'] = $transaksi_dtime;
                        // $itemtambahan['umur_now'] = umurDay($transaksi_dtime);
                        $umur_d = 0;
                        $itemtambahan['due_date'] = "-";
                        $itemtambahan['umur_now'] = "-";

                    }
                    else {
                        $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
                        // $tagihanDuedate = isset($tagihanDuedates[$transaksi_id]) ? $tagihanDuedates[$transaksi_id] : array();
                        // $dueDate = $tagihanDuedate['due_date'];
                        // $umur_d = umurDay($dueDate);
                        // $itemtambahan['due_date'] = isset($tagihanDuedate['due_date']) ? $dueDate : null;
                        // $itemtambahan['umur_now'] = $umur_d;
                    }
                    break;
            }



            // $tagihan = isset($tagihans[$transaksi_id]) ? $tagihans[$transaksi_id] : array();
            $tagNilai = isset($tagihan['sisa']) ? $tagihan['sisa'] : 0;
            $itemTambahan['m_kode_cabang'] = $dataCabang[$item->cabang_id]["kode_cabang"];
            $itemTambahan['m_cabang_nama'] = $dataCabang[$item->cabang_id]["nama"];
            $itemTambahan['sisa_tagihan'] = $tagNilai;
            $itemTambahan['total_tagihan'] = isset($tagihan['tagihan']) ? $tagihan['tagihan'] * 1 : 0;
            $itemTambahan['total_terbayar'] = isset($tagihan['terbayar']) ? $tagihan['terbayar'] * 1 : 0;
            // ------------------
            $itemTambahan['time'] = $dtime;
            $itemTambahan['c_ppn'] = $transaksi_ppn;
            $itemTambahan['c_sub_total'] = $transaksi_inc_ppn;
            $itemTambahan['keterangan_cancel'] = "$cancel_name $cancel_dtime";
            // --------------------
            // $itemTambahan['transaksi_no_1a'] = $item->transaksi_no_1 . "-" . digit_5($global_counter_1["_company_jenisTr"]);
            // $itemTambahan['transaksi_no_2a'] = $item->transaksi_no_2 . "-" . digit_5($global_counter_2["_company_jenisTr"]);
            // $itemTambahan['transaksi_no_3a'] = $item->transaksi_no_3 . "-" . digit_5($global_counter_3["_company_jenisTr"]);
            // $itemTambahan['transaksi_no_4a'] = $item->transaksi_no_4 . "-" . digit_5($global_counter_4["_company_jenisTr"]);
            $itemTambahan['transaksi_no_1a'] = $item->transaksi_no_1 . "-" . digit_5($global_counter_1["_company_cabangID_jenisTr"]);
            $itemTambahan['transaksi_no_2a'] = $item->transaksi_no_2 . "-" . digit_5($global_counter_2["_company_cabangID_jenisTr"]);
            $itemTambahan['transaksi_no_3a'] = $item->transaksi_no_3 . "-" . digit_5($global_counter_3["_company_cabangID_jenisTr"]);
            $itemTambahan['transaksi_no_4a'] = $item->transaksi_no_4 . "-" . digit_5($global_counter_4["_company_cabangID_jenisTr"]);

            $trIdItems[] = (array)$item + $itemTambahan;
            // break;
        }

        $headers = array(
            // "no"          => array(
            //     "label" => "No",
            //     "type"  => "integer",
            // ),
            // "dtime"          => array(
            //     "label" => "tanggal jam",
            //     "type"  => "string",
            // ),
            "fulldate" => array(
                "label" => "tanggal",
                "type" => "string",
            ),
            "time" => array(
                "label" => "jam",
                "type" => "string",
            ),
            "m_kode_cabang" => array(
                "label" => "kode cabang",
                "type" => "string",
            ),
            "m_cabang_nama" => array(
                "label" => "cabang",
                "type" => "string",
            ),
            "pihak_nama" => array(
                "label" => "konsumen",
                "type" => "string",
            ),
            // "transaksi_no_1"        => array(
            //     "label" => "no SPO",
            //     "type"  => "string",
            // ),
            // "transaksi_no_2"        => array(
            //     "label" => "no SO",
            //     "type"  => "string",
            // ),
            // "transaksi_no_3"        => array(
            //     "label" => "no PPL",
            //     "type"  => "string",
            // ),
            // "transaksi_no_4"        => array(
            //     "label" => "no PL",
            //     "type"  => "string",
            // ),

            "transaksi_no_1a" => array(
                "label" => "no SPO",
                "type" => "string",
            ),
            "transaksi_no_2a" => array(
                "label" => "no SO",
                "type" => "string",
            ),
            "transaksi_no_3a" => array(
                "label" => "no PPL",
                "type" => "string",
            ),
            "transaksi_no_4a" => array(
                "label" => "no PL",
                "type" => "string",
            ),
            "produk_kode" => array(
                "label" => "produk sku",
                "type" => "string",
            ),
            "produk_nama" => array(
                "label" => "produk",
                "type" => "string",
            ),
            "outdoor_nama" => array(
                "label" => "outdoor",
                "type" => "string",
            ),
            "indoor_nama_1" => array(
                "label" => "intdoor",
                "type" => "string",
            ),
            "qty_kredit" => array(
                "label" => "jumlah",
                "type" => "integer",
            ),
            "harga" => array(
                "label" => "harga per unit",
                "type" => "integer",
            ),
            "kredit" => array(
                "label" => "jumlah kena pajak",
                "type" => "integer",
            ),
            "c_ppn" => array(
                "label" => "pajak",
                "type" => "integer",
            ),
            "c_sub_total" => array(
                "label" => "sub penjualan",
                "type" => "integer",
            ),
            // "harga_include_ppn"     => array(
            //     "label" => "harga jual",
            //     "type"  => "integer",
            // ),
            // "sub_harga_include_ppn" => array(
            //     "label" => "sub harga jual",
            //     "type"  => "integer",
            // ),
            //----------------------------
            "total_tagihan" => array(
                "label" => "total inv.",
                "type" => "integer",
            ),
            "total_terbayar" => array(
                "label" => "inv. dibayar",
                "type" => "integer",
            ),
            "sisa_tagihan" => array(
                "label" => "sisa tagihan inv.",
                "type" => "integer",
            ),
            //----------------------------

            "salesman_nama" => array(
                "label" => "salesmas",
                "type" => "string",
            ),
            "oleh_nama" => array(
                "label" => "sales admin",
                "type" => "string",
            ),
            "gudang_nama_kirim" => array(
                "label" => "dikirim dari",
                "type" => "string",
            ),
            "delivery_nama" => array(
                "label" => "status",
                "type" => "string",
            ),
            "deskripsi" => array(
                "label" => "keterangan",
                "type" => "string",
            ),
            "keterangan_cancel" => array(
                "label" => "note",
                "type" => "string",
            ),
        );

        $dipakai = false;
        if ($dipakai) { // matiHere(__LINE__);
            $tmpA = $tr->lookupByCondition($wheres)->result();
            cekLime($this->db->last_query());
            matiHere();
            $aKoloms = array(
                "nomer",
                "dtime",
                "oleh_nama",
                "trash_4",
            );
            $trIds = array();
            if (sizeof($tmpA) > 0) {
                $trIds_0 = array();
                foreach ($tmpA as $item) {
                    $params = array();
                    foreach ($aKoloms as $aKolom) {
                        $$aKolom = $item->$aKolom;
                        if ($old == "old") {
                            if ($aKolom == "nomer") {
                                $params[$aKolom] = formatTransNomer($item->$aKolom, $jenis, -1);
                            }
                            else {
                                $params[$aKolom] = formatField2($aKolom, $item->$aKolom);
                            }
                        }
                        else {

                            $params[$aKolom] = formatField2($aKolom, $item->$aKolom);
                        }
                    }
                    $trIds_0[$item->id] = 1;

                    $trIdParams[$item->id] = $params;
                }
                $trIds = array_keys($trIds_0);
            }
            $trIdList = implode("','", $trIds);
            switch ($old) {
                default:
                    /* ===================================================
                     * untuk menapilkan data tambahan::
                     * 1) tambahan pada array kolom
                     * 2) tambahakan padaa header excel
                     * ----------------------------------------*/

                    $bKoloms = array(
                        // "transaksi_id",
                        "jml",
                        "sub_harga",
                        "sub_nett1",
                        "sub_ppn",
                        "sub_nett2",
                        "nama",
                        "produk_kode",
                        "pihakName",
                    );
                    $tr->setFilters(array());
                    $tr->addFilter("transaksi_id in ('$trIdList')");
                    //                $tr->addFilter("param='items'");
                    $tr->setJointSelectFields("items, transaksi_id");
                    //                $tmpB = $tr->lookupRegistries()->result();
                    $tmpB = $tr->lookupDataRegistries()->result();
                    // cekMerah($this->db->last_query());

                    $cKoloms = array(
                        "customerDetails__kabupaten",
                        "salesmanDetails__nama",
                        // "billingDetails__propinsi",
                    );
                    //                $tr->addFilter("param='main'");
                    $tr->setJointSelectFields("main, transaksi_id");
                    //                $tmpC = $tr->lookupRegistries()->result();
                    $tmpC = $tr->lookupDataRegistries()->result();

                    foreach ($tmpC as $cItems) {
                        // arrPrint($cItems);
                        $cTransaksi_id = $cItems->transaksi_id;
                        $cValues = blobDecode($cItems->main);
                        // arrPrint($cValues);
                        // matiHere();
                        foreach ($cKoloms as $cKolom) {
                            $$cKolom = $cValues[$cKolom];
                            $main_params[$cTransaksi_id][$cKolom] = $cValues[$cKolom];
                        }
                        // $cValues = $cItems;
                    }

                    $trIdItems = array();
                    foreach ($tmpB as $items) {
                        $transaksi_id = $items->transaksi_id;
                        $values = blobDecode($items->items);
                        // foreach ($tmpC as $cItems) {
                        // $cValues = $cItems
                        // }
                        // $mains = blobDecode($items->values);

                        foreach ($values as $value) {
                            $params = array();
                            foreach ($cKoloms as $cKolom) {
                                $params[$cKolom] = $main_params[$transaksi_id][$cKolom];
                            }
                            foreach ($bKoloms as $bKolom) {
                                $$bKolom = $value[$bKolom];
                                $params[$bKolom] = $value[$bKolom];
                            }
                            // cekHijau($transaksi_id);
                            foreach ($aKoloms as $aKolom) {

                                // $params[$aKolom] = 0;
                                $params[$aKolom] = $trIdParams[$transaksi_id][$aKolom];
                            }


                            $trIdItems[] = $params;
                        }
                        //                     arrPrint($main_params);
                        //                    mati_disini();
                        $headers = array(
                            // "no"          => array(
                            //     "label" => "No",
                            //     "type"  => "integer",
                            // ),
                            "nomer" => array(
                                "label" => "INV",
                                "type" => "string",
                            ),
                            "dtime" => array(
                                "label" => "tanggal",
                                "type" => "string",
                            ),
                            "nama" => array(
                                "label" => "Model",
                                "type" => "string",
                            ),
                            "produk_kode" => array(
                                "label" => "type",
                                "type" => "string",
                            ),
                            "pihakName" => array(
                                "label" => "Customer",
                                "type" => "string",
                            ),
                            "customerDetails__kabupaten" => array(
                                "label" => "Kota",
                                "type" => "string",
                            ),
                            "oleh_nama" => array(
                                "label" => "Person",
                                "type" => "string",
                            ),
                            "salesmanDetails__nama" => array(
                                "label" => "salesman",
                                "type" => "string",
                            ),
                            // "billingDetails__propinsi"        => array(
                            //     "label" => "Prop",
                            //     "type"  => "string",
                            // ),
                            "jml" => array(
                                "label" => "Qty",
                                "type" => "integer",
                            ),
                            "sub_harga" => array(
                                "label" => "Price",
                                "type" => "integer",
                            ),
                            "sub_nett1" => array(
                                "label" => "DPP",
                                "type" => "integer",
                            ),
                            //                        "sub_ppn" => array(
                            //                            "label" => "PPN",
                            //                            "type" => "integer",
                            //                        ),
                            //                        "sub_nett2" => array(
                            //                            "label" => "TOTAL",
                            //                            "type" => "integer",
                            //                        ),
                            "trash_4" => array(
                                "label" => "STATUS",
                                "type" => "text",
                            ),
                        );
                    }
                    break;
                case "old":
                    $bKoloms = array(
                        "transaksi_id",
                        "produk_ord_jml",
                        "produk_hrg_ori",
                        "produk_ord_diskon",
                        "produk_ord_hrg",
                        "sub_nett1",
                        "ppn",
                        "sub_nett2",
                        "produk_nama",
                        "produk_kode",
                        // "pihakName",
                    );
                    $tr->setTableName("transaksi_data");
                    $tr->setFilters(array());
                    // $this->db->order_by('id');
                    $tmpDatas = $tr->lookupByCondition("produk_jenis='produk' AND
                    transaksi_id in ('" . $trIdList . "') AND trash='0' AND produk_ord_jml>'0'")->result();
                    // cekMerah($this->db->last_query());
                    // arrPrint($tmpDatas);
                    foreach ($tmpDatas as $tmpData) {
                        $params = array();
                        foreach ($bKoloms as $bKolom) {
                            $$bKolom = isset($tmpData->$bKolom) ? $tmpData->$bKolom : 0;

                            $sParams[$bKolom] = isset($tmpData->$bKolom) ? $tmpData->$bKolom : 0;
                        }
                        $rParams['ppn'] = ($produk_ord_jml * $ppn);
                        $rParams['sub_nett1'] = ($produk_ord_jml * $produk_ord_hrg);
                        $rParams['sub_nett2'] = ($produk_ord_jml * $produk_ord_hrg) + ($produk_ord_jml * $ppn);
                        $params = array_replace($sParams, $rParams);
                        // $params[$bKolom] = isset($tmpData->$bKolom) ? $tmpData->$bKolom : 0;

                        foreach ($aKoloms as $aKolom) {

                            // $params[$aKolom] = 0;
                            $params[$aKolom] = $trIdParams[$transaksi_id][$aKolom];
                        }
                        $trIdItems[] = $params;
                    }
                    $headers = array(
                        // "no"          => array(
                        //     "label" => "No",
                        //     "type"  => "integer",
                        // ),
                        "nomer" => array(
                            "label" => "INV",
                            "type" => "string",
                        ),
                        "dtime" => array(
                            "label" => "tanggal",
                            "type" => "string",
                        ),
                        "produk_nama" => array(
                            "label" => "Model",
                            "type" => "string",
                        ),
                        "produk_kode" => array(
                            "label" => "type",
                            "type" => "string",
                        ),
                        // "pihakName"   => array(
                        //     "label" => "Customer",
                        //     "type"  => "string",
                        // ),
                        "produk_ord_jml" => array(
                            "label" => "Qty",
                            "type" => "integer",
                        ),
                        "produk_ord_hrg" => array(
                            "label" => "Price",
                            "type" => "integer",
                        ),
                        "sub_nett1" => array(
                            "label" => "DPP",
                            "type" => "integer",
                        ),
                        "ppn" => array(
                            "label" => "PPN",
                            "type" => "integer",
                        ),
                        "sub_nett2" => array(
                            "label" => "TOTAL",
                            "type" => "integer",
                        ),
                    );
                    // cekHitam($trIdList);
                    break;
            }
        }

        // arrPrintKuning(array_slice($trIdItems,2, 1));
        // matiHere();
        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();
        foreach ($trIdItems as $data) {
            $no++;
            if (ipadd() == MGK_LIVE) {

//                 cekKuning($dataCabang);
//                 matiHere(__LINE__);
            }
            // $yNames_f = html_entity_decode($yNames);
            foreach ($headers as $header => $aliasing) {
                if ($header == "trash_4") {
                    if ($data[$header] == 0) {
                        $dataSpec[$header] = "ACTIVE";
                    }
                    else {
                        $dataSpec[$header] = "CANCELED";
                    }
                }
                else {

                    $dataSpec[$header] = key_exists($header, $data) ? $data[$header] : $$header;
                }
            }


            $datas[] = (object)$dataSpec;
        }

        if (ipadd() == MGK_LIVE) {
            // arrPrintHijau($datas);
            // mati_disini("SUKSES");
        }
        // endregion pairing data

//        if($jenis == "5823spd"){
//            mati_disini("FITUR INI SEDANG MAINTENANCE, SILAHKAN DICOBA BEBERAPA SAAT LAGI.");
//        }

        $ex->setTitleFile($this->file . " $dtime_now_0");
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();
    }

    public function persediaan()
    {
        $params = blobDecode($this->uri->segment(3));
        // $mdlName = $params['mdl'];
        $mdlName = "MdlProduk";
        $mdlFifo = $params['fifo'];
        $cabang_id = $params['cabang_id'];
        // arrPrint($params);
        $mdlHarga = "MdlHargaProduk";
        // matiHere(__LINE__);

        $this->load->model("Mdls/$mdlName");
        // $this->load->model("Coms/$mdlName");
        $this->load->model("Mdls/$mdlFifo");
        $ff = new $mdlFifo();

        $this->load->model("Mdls/$mdlHarga");
        $md = new $mdlName();
        $ha = new $mdlHarga();

        /*
         * produk
         * */
        $where_prod = array(
            "kategori_id<>" => 4
        );
        $this->db->where($where_prod);
        $this->db->order_by("merek_id", "asc");
        $tmps_1 = $md->lookupAll();
        $dataSrcs_0 = $tmps_1->result();
        // showLast_query("lime");
        // cekHere("jml: " . count($dataSrcs_0));
        // arrPrint($dataSrcs);
        // matiHere(__LINE__);
        $dataSrcs = array();
        foreach ($dataSrcs_0 as $dataSrc) {
            $dataSrcs[$dataSrc->id] = $dataSrc;
        }
        // arrPrint($dataSrcs);

        /*
         * fifo
         * */
        $where_2 = array(
            "cabang_id" => $cabang_id
        );
        // $this->db->where($where_2);
        // $tmps_2 = $ff->lookupAll()->result();
        // // showLast_query("orange");
        // // arrPrint($tmps_2);
        // foreach ($tmps_2 as $item_2) {
        //
        //     // if (!isset($dataFifo[$item_2->produk_id])) {
        //     //     $dataFifo[$item_2->produk_id] = 0;
        //     // }
        //     // $dataFifo[$item_2->produk_id] += $item_2->unit;
        //
        //     // $dataFifo[$item_2->produk_id][] = array(
        //     //     "unit" => $item_2->unit,
        //     //     "hpp" => $item_2->hpp,
        //     // );
        //
        //     if (!isset($dataFifo[$item_2->produk_id][$item_2->hpp])) {
        //         $dataFifo[$item_2->produk_id][$item_2->hpp] = 0;
        //     }
        //     $dataFifo[$item_2->produk_id][$item_2->hpp] += $item_2->unit;
        //
        // }
        // arrPrintWebs($dataFifo);
        //         matiHere();
        $where_30 = array(
            "jenis_value" => "jual"
        );
        $where_3 = $where_2 + $where_30;
        $this->db->where($where_3);
        $hargas_0 = $ha->lookupAll()->result();
        //        showLast_query("lime");
        // arrPrint($hargas_0);
        foreach ($hargas_0 as $harga_0) {
            $hargas[$harga_0->produk_id] = $harga_0->nilai;
        }

        /*
         * stok produk
         * */
        $stoknya = $this->callStokCace();
        //        showLast_query("kuning");
        // arrPrintHijau($stoknya);
        //        if ($_SERVER['REMOTE_ADDR'] == "202.65.117.72") {
        //            arrPrintWebs($stoknya);
        //            matiHere(__LINE__);
        //        }

        /*
         * serial
         * */
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $psc = new ComRekeningPembantuProdukPerSerial();
        $where_serail = array(
            "qty_debet" => 1,
            // "cabang_id<" => 10,
            "cabang_id" => $cabang_id,
            "gudang_id<" => 100,
        );
        $this->db->where($where_serail);
        $serailCache = $psc->lookupAll()->result();
        // showLast_query("merah");
        // cekHere(count($serailCache));
        // arrPrintPink($serailCache);
        $serials = array();
        foreach ($serailCache as $item) {
            $serials[$item->produk_id][$item->cabang_id][$item->gudang_id][] = $item->extern_nama;
            $skuserials[$item->produk_id][$item->cabang_id][$item->gudang_id][$item->extern2_nama][] = $item->extern_nama;
        }
        // arrPrintKuning($serials);
        // arrPrintKuning($skuserials);
        // matiHere(__LINE__);

        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $where_cab = array(
            "id" => $cabang_id,
        );
        $this->db->where($where_cab);
        $cbs = $cb->callSpecs();
        // showLast_query("pink");
        // arrPrintKuning($cbs);

        $this->load->model("Mdls/MdlGudangDefault");
        $gdd = new MdlGudangDefault();
        $gdds = $gdd->lookupAll()->result();
        // arrPrintHijau($gdds);

        $this->load->model("Mdls/MdlGudang");
        $gd = new MdlGudang();
        $where_cab = array(
            "cabang_id" => $cabang_id,
        );
        $this->db->where($where_cab);
        $gds = $gd->callSpecs();
        // showLast_query("pink");
        // arrPrintHijau($gds);
        // matiHere();

        $this->file = $this->uri->segment(2) . dtimeNow('-Ynd-Hi');

        // $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();

        $headers_1 = array(
            // "no"          => array(
            //     "label" => "No",
            //     "type"  => "integer",
            // ),
            "kode" => array(
                "label" => "KODE",
                "type" => "string",
            ),
            "id" => array(
                "label" => "pID",
                "type" => "integer",
            ),
            "barcode" => array(
                "label" => "barcode",
                "type" => "string",
            ),
            "merek_nama" => array(
                "label" => "merek",
                "type" => "string",
            ),
            "kategori_nama" => array(
                "label" => "kategori",
                "type" => "string",
            ),
            // "sub_kategori_nama"         => array(
            //     "label" => "jenis",
            //     "type"  => "string",
            // ),
            // "produk_part_kategori_nama" => array(
            //     "label" => "part kategori",
            //     "type"  => "string",
            // ),
            // "produk_part_jenis_nama"    => array(
            //     "label" => "part jenis",
            //     "type"  => "string",
            // ),
            // "produk_part_ukuran_nama"   => array(
            //     "label" => "part ukuran",
            //     "type"  => "string",
            // ),


            "tipe" => array(
                "label" => "tipe produk",
                "type" => "string",
            ),

            // "supplier_nama" => array(
            //     "label" => "supplier",
            //     "type"  => "string",
            // ),
            "nama" => array(
                "label" => "Nama",
                "type" => "string",
            ),
            "size_nama" => array(
                "label" => "UOM",
                "type" => "string",
            ),
        );

        $headers_2 = array(
            // "hpp"  => array(
            //     "label" => "HPP satuan",
            //     "type"  => "integer",
            // ),
            // "harga" => array(
            //     "label" => "Harga Jual",
            //     "type"  => "integer",
            // ),
            // "stok" => array(
            //     "label" => "qty",
            //     "type"  => "integer",
            // ),

            // "billingDetails__propinsi"        => array(
            //     "label" => "Prop",
            //     "type"  => "string",
            // ),
            //     "jml"                        => array(
            //         "label" => "Qty",
            //         "type"  => "integer",
            //     ),
            //     "sub_harga"                  => array(
            //         "label" => "Price",
            //         "type"  => "integer",
            //     ),
            //     "sub_nett1"                  => array(
            //         "label" => "DPP",
            //         "type"  => "integer",
            //     ),
            //     "sub_ppn"                    => array(
            //         "label" => "PPN",
            //         "type"  => "integer",
            //     ),
            //     "sub_nett2"                  => array(
            //         "label" => "TOTAL",
            //         "type"  => "integer",
            //     ),
        );

        foreach ($cbs as $cb_id => $cb_speks) {
            foreach ($gds as $gd_id => $gd_speks) {
                $gnama = $gd_speks->nama;
                $headers_2['qty_' . $cb_id . "_" . $gd_id] = array(
                    "label" => "stok $gnama",
                    "type" => "integer",
                );
                $headers_2['serial_' . $cb_id . "_" . $gd_id] = array(
                    "label" => "jml serial per SKU $gnama",
                    "type" => "string",
                );
            }
        }

        $headers = $headers_1 + $headers_2;
        // arrPrintHijau($headers);
        // matiHere(__LINE__);
        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();
        // foreach ($dataSrcs as $data) {
        //     $no++;
        //     // cekHijau("$yNames");
        //     // $yNames_f = html_entity_decode($yNames);
        //     foreach ($headers as $header => $aliasing) {
        //
        //         $dataSpec[$header] = key_exists($header, $data) ? $data->$header : $$header;
        //     }
        //
        //
        //     $datas[] = (object)$dataSpec;
        // }
        // endregion pairing data
        // arrPrint($dataSrcs);
        foreach ($dataSrcs as $pId => $itemffs) {
            $no++;
            $spekDatas = $dataSrcs[$pId];
            $jml_serial = isset($itemffs->jml_serial) ? $itemffs->jml_serial : 0;
            $spekDatas->tipe = $jml_serial == 0 ? "non serial" : "serial";
            // $spekDatas->satuan = "uhui";

            foreach ($headers_1 as $header => $aliasing) {

                $dataSpec[$header] = isset($spekDatas->$header) ? $spekDatas->$header : "";
            }

            $stok_qty_0 = $stoknya[$pId];
            // arrPrintHijau($stok_qty_0);
            foreach ($cbs as $cb_id => $cb_speks) {

                foreach ($gds as $gd_id => $gd_speks) {

                    $gd_cb_id = $gd_speks->cabang_id;
                    // if($gd_cb_id == $cb_id){

                    // if (isset($stok_qty_0[$cb_id][$gd_id])) {

                    $stok_qty = isset($stok_qty_0[$cb_id][$gd_id]['qty_debet']) ? $stok_qty_0[$cb_id][$gd_id]['qty_debet'] : 0;

                    // cekHere("[$pId] $cb_id][$gd_id]");
                    // arrPrintHijau($stok_qty_0[$cb_id]);
                    // arrPrintKuning($stok_qty);
                    $dataSpec['qty_' . $cb_id . "_" . $gd_id] = $stok_qty;


                    // }
                    // }


                    $serialis = $serials[$pId][$cb_id][$gd_id];
                    $skuserialis = $skuserials[$pId][$cb_id][$gd_id];
                    // cekHere("jml serial pid: $pId: " . count($serialis));
                    $hasil = "";
                    foreach ($serialis as $seriali) {
                        $var = $seriali;
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil, $var";
                        }

                    }
                    // cekBiru("$hasil");
                    // $dataSpec["serial_" . $cb_id . "_" . $gd_id] = $jml_serial != 0 ? $hasil : "";

                    $hasil = "";
                    foreach ($skuserialis as $sku => $skuseriali) {
                        $jml_serialxx = count($skuseriali);

                        $var = "$sku : $jml_serialxx";
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil\n $var";
                        }
                    }

                    if ($jml_serial == 0) {
                        $dataSpec["serial_" . $cb_id . "_" . $gd_id] = 0;
                    }
                    else {

                        // $dataSpec["serial_" . $cb_id . "_" . $gd_id] = $jml_serial == 0 ? "" : "$hasil";
                        $dataSpec["serial_" . $cb_id . "_" . $gd_id] = "$hasil";
                    }
                }
            }
            $stoks = $stoknya[$pId];
            // // $dataSpec['harga'] = isset($hargas[$pId]) ? $hargas[$pId] : 0;
            // $dataSpec['stok'] = "";

            $datas[] = (object)$dataSpec;
        }

        //        if ($_SERVER['REMOTE_ADDR'] == "202.65.117.72") {
        //             arrPrintWebs($datas);
        //             matiHere(__LINE__);
        //        }

        // arrPrintWebs($datas);
        // matiHere(__LINE__);
        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();

    }

    public function callStokCace()
    {
        $tbl_1 = "_rek_pembantu_produk_cache";
        $coloms = array(
            "gudang_id",
            "cabang_id",
            "extern_id",
            "qty_debet",
            "debet",
            "harga",
            "harga_avg",
        );
        $this->db->select($coloms);
        $wheres = array(
            // "jenis" => "4822",
            "periode" => 'forever',
            "gudang_id<" => '100',
            "rekening" => "1010030030",// rekening persediaan produk
        );
        $this->db->where($wheres);
        // $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['extern_id'];
            $cb_id = $src['cabang_id'];
            $gd_id = $src['gudang_id'];
            // $sisa = $src['sisa'];

            // $src_datas[$cb_id][$tr_id] = $src;
            $src_datas[$tr_id][$cb_id][$gd_id] = $src;
        }

        return $src_datas;
    }

    public function data()
    {
        $mdlName = $params = blobDecode($this->uri->segment(3));
        // cekHere($params);
        // $mdlName = $params['mdl'];
        // $mdlFifo = $params['fifo'];
        // $cabang_id = $params['cabang_id'];
        // // arrPrint($params);
        // $mdlHarga = "MdlHargaProduk";
        //
        $this->load->model("Mdls/$mdlName");
        // $this->load->model("Mdls/$mdlFifo");
        // $this->load->model("Mdls/$mdlHarga");
        $md = new $mdlName();
        // $ff = new $mdlFifo();
        // $ha = new $mdlHarga();
        $souceFields = $md->getExcelFields();
        $paramXls = $md->getExcelWriters();
        $namaFile = $paramXls["namaFile"];

        if (isset($paramXls["dataTambahan"])) {

            $dataTambahanBase = $paramXls["dataTambahanBase"];
            $dataTambahan = $paramXls["dataTambahan"];
            $dataFields = $paramXls["dataTambahanFields"];

            $this->load->model("Mdls/$dataTambahan");
            $this->load->model("Mdls/$dataTambahanBase");

            $mdTambahan = new $dataTambahan();
            $mdTambahanBase = new $dataTambahanBase();
            $tmps_3 = $mdTambahanBase->lookupAll()->result();
            $datas_3 = array();
            foreach ($tmps_3 as $items) {
                $datas_3[$items->id] = $items;
            }
            // arrPrint($tmps_3);
            // arrPrint($datas_3);

            $mdTambahan->setConditional(array(
                "trash" => 0,
            ));
            $tmps_2 = $mdTambahan->lookupSelectedData($dataFields);
            $dataSrcs_2 = $tmps_2->result();
            // showLast_query("orange");
            $datas_20 = array();
            foreach ($dataSrcs_2 as $item_2) {


                $xId = $item_2->suppliers_id;
                $xNama = isset($datas_3[$xId]->nama) ? $datas_3[$xId]->nama : $xId;
                // cekHere($xNama);
                // $datas_2[$item_2->produk_id][] = $xId;
                $datas_20[$item_2->produk_id][] = $xNama;

            }

            $datas_2 = array();
            foreach ($datas_20 as $pId_2 => $sNamas_2) {
                $hasil = "";
                foreach ($sNamas_2 as $sNama_3) {
                    $var = "$sNama_3";
                    if ($hasil == "") {
                        $hasil .= "$var";
                    }
                    else {
                        $hasil = "$hasil, $var";
                    }
                }
                $datas_2[$pId_2] = $hasil;
            }
            // arrPrintWebs($dataSrcs_2);
            // arrPrintWebs($datas_2);


        }
        if (isset($paramXls["dataImage"])) {

            $dataImage = $paramXls["dataImage"];
            $dataImageFields = $paramXls["dataImageFields"];

            $this->load->model("Mdls/$dataImage");

            $mdImage = new $dataImage();

            $tmps_4 = $mdImage->lookupAll()->result();
            $datas_4 = array();
            foreach ($tmps_4 as $items) {
                foreach ($dataImageFields as $iKey => $iValue) {

                    $datas_4[$items->$iKey][] = $items->$iValue;
                }
            }
        }
        $produkPunyaImages = array_keys($datas_4);
        // arrPrintWebs($datas_4);
        // arrPrintWebs($produkPunyaImages);

        // matiHere(__LINE__);

        $tmps_1 = $md->lookupAll();
        $dataSrcs_0 = $tmps_1->result();
        // showLast_query("lime");
        // arrPrint($dataSrcs_0);
        // matiHere();
        $dataSrcs = array();
        foreach ($dataSrcs_0 as $dataSrc) {
            //isset($datas_2[$dataSrc->id]) ? $datas_2[$dataSrc->id] : $datas_2[$dataSrc->id]
            $idP_3 = $dataSrc->id;
            $vendor = isset($datas_2[$idP_3]) ? $datas_2[$idP_3] : "";

            $dataSrcPlus['vendor'] = $vendor;
            $dataSrc_plus = (array)$dataSrc + $dataSrcPlus;

            $dataSrcs[$dataSrc->id] = (object)$dataSrc_plus;
        }

        // arrPrint($souceFields);
        // arrPrint($dataSrcs);
        // matiHere();

        $this->file = $this->uri->segment(2);

        // $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();

        $headers = $souceFields;
        // $headers = array(
        //     // "no"          => array(
        //     //     "label" => "No",
        //     //     "type"  => "integer",
        //     // ),
        //     "pid"                      => array(
        //         "label" => "pID",
        //         "type"  => "integer",
        //     ),
        //
        //     "kode"    => array(
        //         "label" => "item no",
        //         "type"  => "string",
        //     ),
        //     "no_part" => array(
        //         "label" => "part no",
        //         "type"  => "string",
        //     ),
        //     "nama"    => array(
        //         "label" => "description",
        //         "type"  => "string",
        //     ),
        //
        //     "hpp"   => array(
        //         "label" => "HPP",
        //         "type"  => "integer",
        //     ),
        //     "harga" => array(
        //         "label" => "Harga Jual",
        //         "type"  => "integer",
        //     ),
        //     "stok"  => array(
        //         "label" => "Stock Today",
        //         "type"  => "integer",
        //     ),
        //
        //     // "billingDetails__propinsi"        => array(
        //     //     "label" => "Prop",
        //     //     "type"  => "string",
        //     // ),
        //     //     "jml"                        => array(
        //     //         "label" => "Qty",
        //     //         "type"  => "integer",
        //     //     ),
        //     //     "sub_harga"                  => array(
        //     //         "label" => "Price",
        //     //         "type"  => "integer",
        //     //     ),
        //     //     "sub_nett1"                  => array(
        //     //         "label" => "DPP",
        //     //         "type"  => "integer",
        //     //     ),
        //     //     "sub_ppn"                    => array(
        //     //         "label" => "PPN",
        //     //         "type"  => "integer",
        //     //     ),
        //     //     "sub_nett2"                  => array(
        //     //         "label" => "TOTAL",
        //     //         "type"  => "integer",
        //     //     ),
        // );

        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();

        // arrPrint($dataSrcs);
        foreach ($dataSrcs as $pId => $itemffs) {
            foreach ($souceFields as $kolom => $stokSpeks) {
                $no++;
                if (isset($stokSpeks["replacer"])) {
                    $value = in_array($pId, $produkPunyaImages) ? "yes" : "no image";
                    $dataSpec[$kolom] = $value;
                }
                else {
                    $dataSpec[$kolom] = $itemffs->$kolom;
                }
            }

            $datas[] = (object)$dataSpec;
        }
        // endregion pairing data

        // arrPrintWebs($headers);
        // arrPrint($datas);
        // matiHere(__LINE__ . " $namaFile");

        $ex->setTitleFile($namaFile);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();

    }

    public function mutasi()
    {

        // arrPrint( $this->input->post() );
        // arrPrint( $_POST );
        // arrPrint( $_GET );
        // $kiriman = $this->input->post();
        $kiriman = $_POST;
        $rekName = $this->uri->segment(3);
        $def_position = detectRekDefaultPosition(urldecode($rekName));
        $awal = $def_position . "_awal";
        $akhir = $def_position . "_akhir";
        //        $awal = "kredit_awal";
        //        $akhir = "kredit_akhir";


        $kiriman_data_e = str_ireplace(" ", "+", $kiriman['data']);
        $kiriman_data2_e = str_ireplace(" ", "+", $kiriman['item2']);


        $dataSrcs_0 = $datas = blobDecode($kiriman_data_e);
        $dataSrcs_2 = blobDecode($kiriman_data2_e);

        $namaFile = "mutasi-" . dtimeNow();
        $souceFields = array(
            // "id"            => array(
            //     "label" => "pID",
            //     "type"  => "integer",
            // ),
            "dtime" => array(
                "label" => "tanggal",
                "type" => "string",
            ),
            "jenis" => array(
                "label" => "jenis",
                "type" => "string",
            ),
            "cabang_nama" => array(
                "label" => "cabang",
                "type" => "string",
            ),
            "oleh_nama" => array(
                "label" => "oleh",
                "type" => "string",
            ),
            "suppliers_nama" => array(
                "label" => "vendor",
                "type" => "string",
            ),
            "customers_nama" => array(
                "label" => "customer",
                "type" => "string",
            ),
            "transaksi_no_f" => array(
                "label" => "nomer",
                "type" => "string",
            ),
            "referenceNomer" => array(
                "label" => "cancelled nomer",
                "type" => "string",
            ),
            "description_main_followup" => array(
                "label" => "vendor's number referral",
                "type" => "string",
            ),
            $awal => array(
                "label" => "awal",
                "type" => "integer",
            ),
            "input" => array(
                "label" => "masuk",
                "type" => "integer",
            ),
            "output" => array(
                "label" => "keluar",
                "type" => "integer",
            ),
            $akhir => array(
                "label" => "akhir",
                "type" => "integer",
            ),

        );
        $regDatas = array(
            "description",
            "description_additional",
            "description_main_followup",
            "eFaktur",
        );
        $rekeningGetDatas = array(
            "hutang dagang" => "description_main_followup",
            "ppn in realisasi" => "eFaktur",
        );

        // $produkPunyaImages = array_keys($datas_4);
        // arrPrintWebs($datas_4);
        // arrPrintWebs($produkPunyaImages);
        //         arrPrintWebs($dataSrcs_0);
        //         mati_disini();

        // matiHere(__LINE__);

        // $tmps_1 = $md->lookupAll();
        // $dataSrcs_0 = $tmps_1->result();
        // showLast_query("lime");
        // arrPrint($dataSrcs_0);
        // matiHere();
        //        arrPrintWebs($dataSrcs_0);
        $dataSrcs = array();
        $idP_3 = 0;
        foreach ($dataSrcs_0 as $idx => $dataSrc) {
            //isset($datas_2[$dataSrc->id]) ? $datas_2[$dataSrc->id] : $datas_2[$dataSrc->id]
            // $idP_3 = $dataSrc['dtime'];
            $idP_3++;
            $ins = $dataSrcs_2[$idx]['in'];
            $ots = $dataSrcs_2[$idx]['out'];
            $inSum = 0;
            $otSum = 0;
            foreach ($ins as $inJenis => $inValue) {
                $inSum .= $inValue;
            }
            foreach ($ots as $otJenis => $otValue) {
                $otSum .= $otValue;
            }
            // $vendor = isset($datas_2[$idP_3]) ? $datas_2[$idP_3] : "";
            $transaksi_no_3 = $dataSrc['transaksi_no'];
            // $input = $dataSrc['saldo_berjalan'] > 0 ? $dataSrc['saldo_berjalan'] : 0;
            // $output = $dataSrc['saldo_berjalan'] < 1 ? $dataSrc['saldo_berjalan'] * -1 : 0;
            // $dataSrcPlus['input'] = $input;
            // $dataSrcPlus['output'] = $output;
            $dataSrcPlus['input'] = $inSum;
            $dataSrcPlus['output'] = $otSum;
            // $dataSrcPlus['transaksi_no_f'] = formatField('nomer_nolink', $transaksi_no_3);
            $dataSrcPlus['transaksi_no_f'] = formatField2('nomer_nolink', $transaksi_no_3);

            $dataSrc_plus = (array)$dataSrc + $dataSrcPlus;

            // $dataSrcs[$idP_3] = (object)$dataSrc;
            $dataSrcs[$idP_3] = (object)$dataSrc_plus;
        }

        // arrPrint($souceFields);
        //         arrPrint($dataSrcs);
        //         matiHere();

        $this->file = $this->uri->segment(2);

        // $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();

        $headers = $souceFields;

        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();

        // arrPrint($dataSrcs);
        foreach ($dataSrcs as $pId => $itemffs) {
            foreach ($souceFields as $kolom => $stokSpeks) {
                $no++;
                if (isset($stokSpeks["replacer"])) {
                    $value = in_array($pId, $produkPunyaImages) ? "yes" : "no image";
                    $dataSpec[$kolom] = $value;
                }
                else {
                    $dataSpec[$kolom] = isset($itemffs->$kolom) ? $itemffs->$kolom : "";
                }
            }

            $datas[] = (object)$dataSpec;
        }
        // endregion pairing data

        // arrPrintWebs($headers);
        //         arrPrint($datas);
        //         matiHere(__LINE__ . " $namaFile");

        $ex->setTitleFile($namaFile);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        // return $ex->writer();
        return $ex->writer();
    }

    public function jurnal()
    {

        // arrPrint( $this->input->post() );
        // $kiriman = Array
        // (
        //
        //     'data' => 'YToyMDp7aTowO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDIgMTY6MTU6NTQiO3M6NToiamVuaXMiO3M6MzE6IlBlbWJheWFyYW4gSHV0YW5nIEphc2EgKGNlbnRlcikiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtzOjE5OiJTSElOVEEgSU5TRVJWRSwgUFQgIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDY5OTE7czo1OiJub21lciI7czoxNDoiMTQ2Mi4tMS4xODQuMTciO3M6ODoiY291bnRlcnMiO3M6NDI0OiJZVG8yT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pTVRRMk1ud3RNU0k3YVRvMU5UdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002T0RvaU1UUTJNbnd6TVRZaU8yazZOVEE3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1URTZJakUwTmpKOExURjhNekUySWp0cE9qVXdPMzF6T2pFNU9pSnpkR1Z3UTI5a1pYeHpkWEJ3YkdsbGNrbEVJanRoT2pFNmUzTTZPRG9pTVRRMk1ud3hPRFFpTzJrNk1UYzdmWE02TWpjNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGMzVndjR3hwWlhKSlJDSTdZVG94T250ek9qRXhPaUl4TkRZeWZDMHhmREU0TkNJN2FUb3hOenQ5Y3pvNE9pSnpkR1Z3UTI5a1pTSTdZVG94T250cE9qRTBOakk3YVRvMU5UdDlmUT09IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NTE1OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfC0xXSA9PiA1NQogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzE0NjJ8MzE2XSA9PiA1MAogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwtMXwzMTZdID0+IDUwCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzE0NjJ8MTg0XSA9PiAxNwogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzE0NjJ8LTF8MTg0XSA9PiAxNwogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2Ml0gPT4gNTUKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTQ6IjE0NjIuLTEuMTg0LjE3IjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NTIzOTA4NDUyLjk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NTIzNjM3NDAzLjk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOi0yNzEwNDk7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ2OTkxIjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0Njk5MSI7fWk6MTthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAyIDE2OjE3OjQ4IjtzOjU6ImplbmlzIjtzOjMxOiJQZW1iYXlhcmFuIEh1dGFuZyBKYXNhIChjZW50ZXIpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7czoyNjoiREVMQVBBTiBDQUhBWUEgU1VLU0VTLCBQVCAiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0Njk5MztzOjU6Im5vbWVyIjtzOjE0OiIxNDYyLi0xLjE3OC4xOSI7czo4OiJjb3VudGVycyI7czo0MjQ6IllUbzJPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lNVFEyTW53dE1TSTdhVG8xTmp0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZPRG9pTVRRMk1ud3pNVFlpTzJrNk5URTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRFNklqRTBOako4TFRGOE16RTJJanRwT2pVeE8zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk9Eb2lNVFEyTW53eE56Z2lPMms2TVRrN2ZYTTZNamM2SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YzNWd2NHeHBaWEpKUkNJN1lUb3hPbnR6T2pFeE9pSXhORFl5ZkMweGZERTNPQ0k3YVRveE9UdDljem80T2lKemRHVndRMjlrWlNJN1lUb3hPbnRwT2pFME5qSTdhVG8xTmp0OWZRPT0iO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MTU6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzE0NjJ8LTFdID0+IDU2CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwzMTZdID0+IDUxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfC0xfDMxNl0gPT4gNTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwxNzhdID0+IDE5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwtMXwxNzhdID0+IDE5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyXSA9PiA1NgogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxNDoiMTQ2Mi4tMS4xNzguMTkiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg1MjM2Mzc0MDMuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg1MTEyNDIxNTMuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6LTEyNjY2Mjk5O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0Njk5MyI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDY5OTMiO31pOjI7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMiAxNjoyNjo0MSI7czo1OiJqZW5pcyI7czoyMjoiUGVtYmF5YXJhbiBIdXRhbmcgSmFzYSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MjY6IlNISUJBIEhJRFJPTElLIFBSQVRBTUEsIFBUIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwMDU7czo1OiJub21lciI7czoxMjoiNDYyLi0xLjMwNi4zIjtzOjg6ImNvdW50ZXJzIjtzOjQxNjoiWVRvMk9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EWXlmQzB4SWp0cE9qRTFOVHQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk56b2lORFl5ZkRNeE5pSTdhVG94TlRFN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEE2SWpRMk1ud3RNWHd6TVRZaU8yazZNVFV4TzMxek9qRTVPaUp6ZEdWd1EyOWtaWHh6ZFhCd2JHbGxja2xFSWp0aE9qRTZlM002TnpvaU5EWXlmRE13TmlJN2FUb3pPMzF6T2pJM09pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRWZITjFjSEJzYVdWeVNVUWlPMkU2TVRwN2N6b3hNRG9pTkRZeWZDMHhmRE13TmlJN2FUb3pPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk5EWXlPMms2TVRVMU8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MTE6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2MnwtMV0gPT4gMTU1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDYyfDMxNl0gPT4gMTUxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjJ8LTF8MzE2XSA9PiAxNTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDYyfDMwNl0gPT4gMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2MnwtMXwzMDZdID0+IDMKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2Ml0gPT4gMTU1CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjEyOiI0NjIuLTEuMzA2LjMiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg1MTEyNDIxNTMuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg1MDk3NzIxNTMuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6LTE0MTM2Mjk5O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzAwNSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwMDUiO31pOjM7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMiAxNjozNTo0OCI7czo1OiJqZW5pcyI7czoyMjoiUGVtYmF5YXJhbiBIdXRhbmcgSmFzYSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MjQ6IkJJTlRBTkcgU0FVREFSQSBFWFBSRVNTICI7czoxNDoiY3VzdG9tZXJzX25hbWEiO047czo5OiJvbGVoX25hbWEiO3M6NToiRmVycnkiO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6InB1c2F0IjtzOjc6Imlkc19oaXMiO2E6MTp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjQ3MDExO3M6NToibm9tZXIiO3M6MTI6IjQ2Mi4tMS4xMjAuNSI7czo4OiJjb3VudGVycyI7czo0MTY6IllUbzJPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lORFl5ZkMweElqdHBPakUxTmp0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZOem9pTkRZeWZETXhOaUk3YVRveE5USTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRBNklqUTJNbnd0TVh3ek1UWWlPMms2TVRVeU8zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk56b2lORFl5ZkRFeU1DSTdhVG8xTzMxek9qSTNPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVmSE4xY0hCc2FXVnlTVVFpTzJFNk1UcDdjem94TURvaU5EWXlmQzB4ZkRFeU1DSTdhVG8xTzMxek9qZzZJbk4wWlhCRGIyUmxJanRoT2pFNmUyazZORFl5TzJrNk1UVTJPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NTExOiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjJ8LTFdID0+IDE1NgogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2MnwzMTZdID0+IDE1MgogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDYyfC0xfDMxNl0gPT4gMTUyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2MnwxMjBdID0+IDUKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjJ8LTF8MTIwXSA9PiA1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjJdID0+IDE1NgogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMjoiNDYyLi0xLjEyMC41IjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NTA5NzcyMTUzLjk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NTA2NTY3NDgxLjk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOi0xNzM0MDk3MTtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwMTEiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDExIjt9aTo0O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDIgMTY6NDE6MTMiO3M6NToiamVuaXMiO3M6MTk6Ik90b3Jpc2FzaSBVYW5nIE11a2EiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtzOjMxOiJQVC4gRmVkRXggRXhwcmVzcyBJbnRlcm5hdGlvbmFsIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToyOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDU2NDE7czo1OiJub21lciI7czoxMDoiNDY0ci4tMS41MSI7czo4OiJjb3VudGVycyI7czozNDA6IllUbzFPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lORFkwY253dE1TSTdhVG8xTVR0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZPRG9pTkRZMGNud3pNVFlpTzJrNk16UTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRFNklqUTJOSEo4TFRGOE16RTJJanRwT2pNME8zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk9Eb2lORFkwY253eE9ETWlPMms2TVR0OWN6bzRPaUp6ZEdWd1EyOWtaU0k3WVRveE9udHpPalE2SWpRMk5ISWlPMms2TlRFN2ZYMD0iO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo0MTg6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8LTFdID0+IDUxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwzMTZdID0+IDM0CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfC0xfDMxNl0gPT4gMzQKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwxODNdID0+IDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJdID0+IDUxCiAgICAgICAgKQoKKQoiO31pOjI7YTo1OntzOjQ6InN0ZXAiO3M6MToiMiI7czo0OiJ0cklEIjtpOjQ3MDE1O3M6NToibm9tZXIiO3M6OToiNDY0Li0xLjQxIjtzOjg6ImNvdW50ZXJzIjtzOjMyODoiWVRvMU9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EWTBmQzB4SWp0cE9qUXhPMzF6T2pFMU9pSnpkR1Z3UTI5a1pYeHZiR1ZvU1VRaU8yRTZNVHA3Y3pvM09pSTBOalI4TXpFMklqdHBPakl5TzMxek9qSXpPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVmRzlzWldoSlJDSTdZVG94T250ek9qRXdPaUkwTmpSOExURjhNekUySWp0cE9qSXlPMzF6T2pFNU9pSnpkR1Z3UTI5a1pYeHpkWEJ3YkdsbGNrbEVJanRoT2pFNmUzTTZOem9pTkRZMGZERTRNeUk3YVRveE8zMXpPamc2SW5OMFpYQkRiMlJsSWp0aE9qRTZlMms2TkRZME8yazZOREU3ZlgwPSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjQxMzoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fC0xXSA9PiA0MQogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHwzMTZdID0+IDIyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8LTF8MzE2XSA9PiAyMgogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8MTgzXSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRdID0+IDQxCiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjk6IjQ2NC4tMS40MSI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODUwNjU2NzQ4MS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODUwNTIyNTQ4MS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDotMTg2ODI5NzE7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ3MDE1IjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NzAxNSI7fWk6NTthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAyIDE2OjUxOjM3IjtzOjU6ImplbmlzIjtzOjMzOiJQZW1iYXlhcmFuIEh1dGFuZyBEYWdhbmcgKFByb2R1aykiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtzOjI1OiJDQUhBWUEgVEVLTklLIE1BTkRJUkksIENWIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwMTk7czo1OiJub21lciI7czoxMjoiNDg5Li0xLjU5Ny4xIjtzOjg6ImNvdW50ZXJzIjtzOjQxNjoiWVRvMk9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EZzVmQzB4SWp0cE9qRXdNanQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk56b2lORGc1ZkRNeE5pSTdhVG81TVR0OWN6b3lNem9pYzNSbGNFTnZaR1Y4Y0d4aFkyVkpSSHh2YkdWb1NVUWlPMkU2TVRwN2N6b3hNRG9pTkRnNWZDMHhmRE14TmlJN2FUbzVNVHQ5Y3pveE9Ub2ljM1JsY0VOdlpHVjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPamM2SWpRNE9YdzFPVGNpTzJrNk1UdDljem95TnpvaWMzUmxjRU52WkdWOGNHeGhZMlZKUkh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk1UQTZJalE0T1h3dE1YdzFPVGNpTzJrNk1UdDljem80T2lKemRHVndRMjlrWlNJN1lUb3hPbnRwT2pRNE9UdHBPakV3TWp0OWZRPT0iO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MDk6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXwtMV0gPT4gMTAyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg5fDMxNl0gPT4gOTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXwtMXwzMTZdID0+IDkxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXw1OTddID0+IDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODl8LTF8NTk3XSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODldID0+IDEwMgogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMjoiNDg5Li0xLjU5Ny4xIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NTA1MjI1NDgxLjk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NTA0MzY1NDgxLjk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOi0xOTU0Mjk3MTtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwMTkiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDE5Ijt9aTo2O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDIgMTc6MjA6MTMiO3M6NToiamVuaXMiO3M6MzU6IlBlbWJheWFyYW4gSHV0YW5nIERhZ2FuZyAoU3VwcGxpZXMpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7czoyMzoiUEVSQ0VUQUtBTiBDSVRSQSBEVU5JQSAiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NzAzNTtzOjU6Im5vbWVyIjtzOjEyOiI0ODcuLTEuOTIuMTEiO3M6ODoiY291bnRlcnMiO3M6NDE2OiJZVG8yT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTkRnM2ZDMHhJanRwT2pFeU16dDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002TnpvaU5EZzNmRE14TmlJN2FUb3hNVGM3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJalE0TjN3dE1Yd3pNVFlpTzJrNk1URTNPMzF6T2pFNU9pSnpkR1Z3UTI5a1pYeHpkWEJ3YkdsbGNrbEVJanRoT2pFNmUzTTZOam9pTkRnM2ZEa3lJanRwT2pFeE8zMXpPakkzT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFZkhOMWNIQnNhV1Z5U1VRaU8yRTZNVHA3Y3pvNU9pSTBPRGQ4TFRGOE9USWlPMms2TVRFN2ZYTTZPRG9pYzNSbGNFTnZaR1VpTzJFNk1UcDdhVG8wT0RjN2FUb3hNak03ZlgwPSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjUxMToiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fC0xXSA9PiAxMjMKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODd8MzE2XSA9PiAxMTcKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N3wtMXwzMTZdID0+IDExNwogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODd8OTJdID0+IDExCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fC0xfDkyXSA9PiAxMQogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3XSA9PiAxMjMKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTI6IjQ4Ny4tMS45Mi4xMSI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODUwNDM2NTQ4MS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQ3NzA2NTQ4MS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDotNDY4NDI5NzE7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ3MDM1IjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NzAzNSI7fWk6NzthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAyIDE3OjIyOjEwIjtzOjU6ImplbmlzIjtzOjM1OiJQZW1iYXlhcmFuIEh1dGFuZyBEYWdhbmcgKFN1cHBsaWVzKSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MTQ6IkFUTUkgU09MTywgUFQgIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwMzk7czo1OiJub21lciI7czoxMToiNDg3Li0xLjE5LjciO3M6ODoiY291bnRlcnMiO3M6NDEyOiJZVG8yT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTkRnM2ZDMHhJanRwT2pFeU5EdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002TnpvaU5EZzNmRE14TmlJN2FUb3hNVGc3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJalE0TjN3dE1Yd3pNVFlpTzJrNk1URTRPMzF6T2pFNU9pSnpkR1Z3UTI5a1pYeHpkWEJ3YkdsbGNrbEVJanRoT2pFNmUzTTZOam9pTkRnM2ZERTVJanRwT2pjN2ZYTTZNamM2SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YzNWd2NHeHBaWEpKUkNJN1lUb3hPbnR6T2prNklqUTROM3d0TVh3eE9TSTdhVG8zTzMxek9qZzZJbk4wWlhCRGIyUmxJanRoT2pFNmUyazZORGczTzJrNk1USTBPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NTA5OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODd8LTFdID0+IDEyNAogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N3wzMTZdID0+IDExOAogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fC0xfDMxNl0gPT4gMTE4CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N3wxOV0gPT4gNwogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N3wtMXwxOV0gPT4gNwogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3XSA9PiAxMjQKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTE6IjQ4Ny4tMS4xOS43IjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDc3MDY1NDgxLjk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDYzNjU1MzgxLjk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOi02MDI1MzA3MTtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwMzkiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDM5Ijt9aTo4O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDIgMTc6MjM6NTQiO3M6NToiamVuaXMiO3M6MzU6IlBlbWJheWFyYW4gSHV0YW5nIERhZ2FuZyAoU3VwcGxpZXMpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7czoxNDoiQVRNSSBTT0xPLCBQVCAiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NzA0MTtzOjU6Im5vbWVyIjtzOjExOiI0ODcuLTEuMTkuOCI7czo4OiJjb3VudGVycyI7czo0MTI6IllUbzJPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lORGczZkMweElqdHBPakV5TlR0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZOem9pTkRnM2ZETXhOaUk3YVRveE1UazdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRBNklqUTROM3d0TVh3ek1UWWlPMms2TVRFNU8zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk5qb2lORGczZkRFNUlqdHBPamc3ZlhNNk1qYzZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPams2SWpRNE4zd3RNWHd4T1NJN2FUbzRPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk5EZzNPMms2TVRJMU8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MDk6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N3wtMV0gPT4gMTI1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fDMxNl0gPT4gMTE5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODd8LTF8MzE2XSA9PiAxMTkKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fDE5XSA9PiA4CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fC0xfDE5XSA9PiA4CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODddID0+IDEyNQogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMToiNDg3Li0xLjE5LjgiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0NjM2NTUzODEuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0MjQ1MzkzODEuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6LTk5MzY5MDcxO3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA0MSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNDEiO31pOjk7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMiAxOToyODoxMyI7czo1OiJqZW5pcyI7czozMToiUGVtYmF5YXJhbiBIdXRhbmcgSmFzYSAoY2VudGVyKSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MzE6IlBULiBGZWRFeCBFeHByZXNzIEludGVybmF0aW9uYWwiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtiOjA7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwNTM7czo1OiJub21lciI7czoxMzoiMTQ2Mi4tMS4xODMuMiI7czo4OiJjb3VudGVycyI7czo0MTY6IllUbzJPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lNVFEyTW53dE1TSTdhVG8xTnp0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZPRG9pTVRRMk1ud3lNek1pTzJrNk1UdDljem95TXpvaWMzUmxjRU52WkdWOGNHeGhZMlZKUkh4dmJHVm9TVVFpTzJFNk1UcDdjem94TVRvaU1UUTJNbnd0TVh3eU16TWlPMms2TVR0OWN6b3hPVG9pYzNSbGNFTnZaR1Y4YzNWd2NHeHBaWEpKUkNJN1lUb3hPbnR6T2pnNklqRTBOako4TVRneklqdHBPakk3ZlhNNk1qYzZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPakV4T2lJeE5EWXlmQzB4ZkRFNE15STdhVG95TzMxek9qZzZJbk4wWlhCRGIyUmxJanRoT2pFNmUyazZNVFEyTWp0cE9qVTNPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NTExOiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfC0xXSA9PiA1NwogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzE0NjJ8MjMzXSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfC0xfDIzM10gPT4gMQogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfDE4M10gPT4gMgogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzE0NjJ8LTF8MTgzXSA9PiAyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyXSA9PiA1NwogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMzoiMTQ2Mi4tMS4xODMuMiI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQyNDUzOTM4MS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQyMzE4ODM4MS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDotMTAwNzIwMDcxO3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA1MyI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNTMiO31pOjEwO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDMgMTA6MzI6MzQiO3M6NToiamVuaXMiO3M6MzM6IlBlbWJheWFyYW4gSHV0YW5nIERhZ2FuZyAoUHJvZHVrKSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MTQ6IlRPS09QRURJQSwgUFQgIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo4OiJob2xkaW5nXyI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwNTU7czo1OiJub21lciI7czoxMjoiNDg5Li0xLjE0Mi4xIjtzOjg6ImNvdW50ZXJzIjtzOjQwODoiWVRvMk9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EZzVmQzB4SWp0cE9qRXdNenQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk5qb2lORGc1ZkRFM0lqdHBPakU3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk9Ub2lORGc1ZkMweGZERTNJanRwT2pFN2ZYTTZNVGs2SW5OMFpYQkRiMlJsZkhOMWNIQnNhV1Z5U1VRaU8yRTZNVHA3Y3pvM09pSTBPRGw4TVRReUlqdHBPakU3ZlhNNk1qYzZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPakV3T2lJME9EbDhMVEY4TVRReUlqdHBPakU3ZlhNNk9Eb2ljM1JsY0VOdlpHVWlPMkU2TVRwN2FUbzBPRGs3YVRveE1ETTdmWDA9IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NTA1OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODl8LTFdID0+IDEwMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXwxN10gPT4gMQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg5fC0xfDE3XSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXwxNDJdID0+IDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODl8LTF8MTQyXSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODldID0+IDEwMwogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMjoiNDg5Li0xLjE0Mi4xIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDIzMTg4MzgxLjk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDIyOTg4MzgxLjk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOi0xMDA5MjAwNzE7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ3MDU1IjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NzA1NSI7fWk6MTE7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMyAxMDo0NToyNiI7czo1OiJqZW5pcyI7czoyMDoiUGVtYmF0YWxhbiBUcmFuc2Frc2kiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtOO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjg6ImhvbGRpbmdfIjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NzA1NztzOjU6Im5vbWVyIjtzOjExOiI5OTExLi0xLjE2MiI7czo4OiJjb3VudGVycyI7czoyNjQ6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lPVGt4TVh3dE1TSTdhVG94TmpJN2ZYTTZNVFU2SW5OMFpYQkRiMlJsZkc5c1pXaEpSQ0k3WVRveE9udHpPamM2SWprNU1URjhNVGNpTzJrNk9UdDljem95TXpvaWMzUmxjRU52WkdWOGNHeGhZMlZKUkh4dmJHVm9TVVFpTzJFNk1UcDdjem94TURvaU9Ua3hNWHd0TVh3eE55STdhVG81TzMxek9qZzZJbk4wWlhCRGIyUmxJanRoT2pFNmUyazZPVGt4TVR0cE9qRTJNanQ5ZlE9PSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjMzMjoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwtMV0gPT4gMTYyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwxN10gPT4gOQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwtMXwxN10gPT4gOQogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMV0gPT4gMTYyCiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjExOiI5OTExLi0xLjE2MiI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQyMjk4ODM4MS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQyMzE4ODM4MS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDotMTAwNzIwMDcxO3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA1NyI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNTciO31pOjEyO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDMgMTE6MDY6MjUiO3M6NToiamVuaXMiO3M6MjA6IlBlbWJhdGFsYW4gVHJhbnNha3NpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7TjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo4OiJob2xkaW5nXyI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwNTk7czo1OiJub21lciI7czoxMToiOTkxMS4tMS4xNjMiO3M6ODoiY291bnRlcnMiO3M6MjY0OiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pT1RreE1Yd3RNU0k3YVRveE5qTTdmWE02TVRVNkluTjBaWEJEYjJSbGZHOXNaV2hKUkNJN1lUb3hPbnR6T2pjNklqazVNVEY4TVRjaU8yazZNVEE3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJams1TVRGOExURjhNVGNpTzJrNk1UQTdmWE02T0RvaWMzUmxjRU52WkdVaU8yRTZNVHA3YVRvNU9URXhPMms2TVRZek8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzQ6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8LTFdID0+IDE2MwogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8MTddID0+IDEwCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xfDE3XSA9PiAxMAogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMV0gPT4gMTYzCiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjExOiI5OTExLi0xLjE2MyI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQyMzE4ODM4MS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQyMzM4ODM4MS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDotMTAwNTIwMDcxO3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA1OSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNTkiO31pOjEzO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDMgMTE6MDk6NTYiO3M6NToiamVuaXMiO3M6MjA6IlBlbWJhdGFsYW4gVHJhbnNha3NpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7TjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo4OiJob2xkaW5nXyI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwNjE7czo1OiJub21lciI7czoxMToiOTkxMS4tMS4xNjQiO3M6ODoiY291bnRlcnMiO3M6MjY0OiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pT1RreE1Yd3RNU0k3YVRveE5qUTdmWE02TVRVNkluTjBaWEJEYjJSbGZHOXNaV2hKUkNJN1lUb3hPbnR6T2pjNklqazVNVEY4TVRjaU8yazZNVEU3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJams1TVRGOExURjhNVGNpTzJrNk1URTdmWE02T0RvaWMzUmxjRU52WkdVaU8yRTZNVHA3YVRvNU9URXhPMms2TVRZME8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzQ6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8LTFdID0+IDE2NAogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8MTddID0+IDExCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xfDE3XSA9PiAxMQogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMV0gPT4gMTY0CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjExOiI5OTExLi0xLjE2NCI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQyMzM4ODM4MS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQyNzQxMDEzOS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDotOTY0OTgzMTM7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ3MDYxIjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NzA2MSI7fWk6MTQ7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMyAxMToxMzoxOSI7czo1OiJqZW5pcyI7czoyMDoiUGVtYmF0YWxhbiBUcmFuc2Frc2kiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtOO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjg6ImhvbGRpbmdfIjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NzA2MztzOjU6Im5vbWVyIjtzOjExOiI5OTExLi0xLjE2NSI7czo4OiJjb3VudGVycyI7czoyNjQ6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lPVGt4TVh3dE1TSTdhVG94TmpVN2ZYTTZNVFU2SW5OMFpYQkRiMlJsZkc5c1pXaEpSQ0k3WVRveE9udHpPamM2SWprNU1URjhNVGNpTzJrNk1USTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRBNklqazVNVEY4TFRGOE1UY2lPMms2TVRJN2ZYTTZPRG9pYzNSbGNFTnZaR1VpTzJFNk1UcDdhVG81T1RFeE8yazZNVFkxTzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjMzNDoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwtMV0gPT4gMTY1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwxN10gPT4gMTIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8LTF8MTddID0+IDEyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExXSA9PiAxNjUKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTE6Ijk5MTEuLTEuMTY1IjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDI3NDEwMTM5Ljk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDI3NjU1MTM5Ljk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOi05NjI1MzMxMztzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwNjMiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDYzIjt9aToxNTthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAzIDExOjE2OjQ3IjtzOjU6ImplbmlzIjtzOjIwOiJQZW1iYXRhbGFuIFRyYW5zYWtzaSI7czoxNDoic3VwcGxpZXJzX25hbWEiO047czoxNDoiY3VzdG9tZXJzX25hbWEiO047czo5OiJvbGVoX25hbWEiO3M6ODoiaG9sZGluZ18iO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6InB1c2F0IjtzOjc6Imlkc19oaXMiO2E6MTp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjQ3MDY1O3M6NToibm9tZXIiO3M6MTE6Ijk5MTEuLTEuMTY2IjtzOjg6ImNvdW50ZXJzIjtzOjI2NDoiWVRvME9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TnpvaU9Ua3hNWHd0TVNJN2FUb3hOalk3ZlhNNk1UVTZJbk4wWlhCRGIyUmxmRzlzWldoSlJDSTdZVG94T250ek9qYzZJams1TVRGOE1UY2lPMms2TVRNN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEE2SWprNU1URjhMVEY4TVRjaU8yazZNVE03ZlhNNk9Eb2ljM1JsY0VOdlpHVWlPMkU2TVRwN2FUbzVPVEV4TzJrNk1UWTJPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM0OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xXSA9PiAxNjYKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfDE3XSA9PiAxMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwtMXwxN10gPT4gMTMKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTFdID0+IDE2NgogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMToiOTkxMS4tMS4xNjYiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0Mjc2NTUxMzkuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0Mjc2NzgzMzkuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6LTk2MjMwMTEzO3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA2NSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNjUiO31pOjE2O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDMgMTE6MTg6NDgiO3M6NToiamVuaXMiO3M6MjA6IlBlbWJhdGFsYW4gVHJhbnNha3NpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7TjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo4OiJob2xkaW5nXyI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwNjc7czo1OiJub21lciI7czoxMToiOTkxMS4tMS4xNjciO3M6ODoiY291bnRlcnMiO3M6MjY0OiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pT1RreE1Yd3RNU0k3YVRveE5qYzdmWE02TVRVNkluTjBaWEJEYjJSbGZHOXNaV2hKUkNJN1lUb3hPbnR6T2pjNklqazVNVEY4TVRjaU8yazZNVFE3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJams1TVRGOExURjhNVGNpTzJrNk1UUTdmWE02T0RvaWMzUmxjRU52WkdVaU8yRTZNVHA3YVRvNU9URXhPMms2TVRZM08zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzQ6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8LTFdID0+IDE2NwogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8MTddID0+IDE0CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xfDE3XSA9PiAxNAogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMV0gPT4gMTY3CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjExOiI5OTExLi0xLjE2NyI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQyNzY3ODMzOS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQzMDE1MzMzOS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDotOTM3NTUxMTM7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ3MDY3IjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NzA2NyI7fWk6MTc7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMyAxNjoyMzo0MSI7czo1OiJqZW5pcyI7czoxOToiT3RvcmlzYXNpIFVhbmcgTXVrYSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MTM6InRlYW0gRGVsaXZlcnkiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjg6ImhvbGRpbmdfIjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjI6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTozOTM3NDtzOjU6Im5vbWVyIjtzOjEwOiI0NjRyLi0xLjM3IjtzOjg6ImNvdW50ZXJzIjtzOjM0MDoiWVRvMU9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TnpvaU5EWTBjbnd0TVNJN2FUb3pOenQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk9Eb2lORFkwY253ek1UWWlPMms2TWpRN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEU2SWpRMk5ISjhMVEY4TXpFMklqdHBPakkwTzMxek9qRTVPaUp6ZEdWd1EyOWtaWHh6ZFhCd2JHbGxja2xFSWp0aE9qRTZlM002T0RvaU5EWTBjbncxTmpBaU8yazZNanQ5Y3pvNE9pSnpkR1Z3UTI5a1pTSTdZVG94T250ek9qUTZJalEyTkhJaU8yazZNemM3ZlgwPSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjQxODoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwtMV0gPT4gMzcKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfDMxNl0gPT4gMjQKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8LTF8MzE2XSA9PiAyNAogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfDU2MF0gPT4gMgogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cl0gPT4gMzcKICAgICAgICApCgopCiI7fWk6MjthOjU6e3M6NDoic3RlcCI7czoxOiIyIjtzOjQ6InRySUQiO2k6NDcwNjk7czo1OiJub21lciI7czo5OiI0NjQuLTEuNDIiO3M6ODoiY291bnRlcnMiO3M6MzIwOiJZVG8xT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTkRZMGZDMHhJanRwT2pReU8zMXpPakUxT2lKemRHVndRMjlrWlh4dmJHVm9TVVFpTzJFNk1UcDdjem8yT2lJME5qUjhNVGNpTzJrNk1UdDljem95TXpvaWMzUmxjRU52WkdWOGNHeGhZMlZKUkh4dmJHVm9TVVFpTzJFNk1UcDdjem81T2lJME5qUjhMVEY4TVRjaU8yazZNVHQ5Y3pveE9Ub2ljM1JsY0VOdlpHVjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPamM2SWpRMk5IdzFOakFpTzJrNk1UdDljem80T2lKemRHVndRMjlrWlNJN1lUb3hPbnRwT2pRMk5EdHBPalF5TzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjQwOToiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fC0xXSA9PiA0MgogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHwxN10gPT4gMQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fC0xfDE3XSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHw1NjBdID0+IDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NF0gPT4gNDIKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6OToiNDY0Li0xLjQyIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDMwMTUzMzM5Ljk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDI4NjUzMzM5Ljk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOi05NTI1NTExMztzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwNjkiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDY5Ijt9aToxODthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAzIDE2OjU0OjM2IjtzOjU6ImplbmlzIjtzOjE5OiJPdG9yaXNhc2kgVWFuZyBNdWthIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7czoxMzoidGVhbSBEZWxpdmVyeSI7czoxNDoiY3VzdG9tZXJzX25hbWEiO047czo5OiJvbGVoX25hbWEiO3M6ODoiaG9sZGluZ18iO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6InB1c2F0IjtzOjc6Imlkc19oaXMiO2E6Mjp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjI5NjE1O3M6NToibm9tZXIiO3M6MTA6IjQ2NHIuLTEuMTkiO3M6ODoiY291bnRlcnMiO3M6MzQwOiJZVG8xT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pTkRZMGNud3RNU0k3YVRveE9UdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002T0RvaU5EWTBjbnd6TVRZaU8yazZNVE03ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1URTZJalEyTkhKOExURjhNekUySWp0cE9qRXpPMzF6T2pFNU9pSnpkR1Z3UTI5a1pYeHpkWEJ3YkdsbGNrbEVJanRoT2pFNmUzTTZPRG9pTkRZMGNudzFOakFpTzJrNk1UdDljem80T2lKemRHVndRMjlrWlNJN1lUb3hPbnR6T2pRNklqUTJOSElpTzJrNk1UazdmWDA9IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NDE4OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfC0xXSA9PiAxOQogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8MzE2XSA9PiAxMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwtMXwzMTZdID0+IDEzCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8NTYwXSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyXSA9PiAxOQogICAgICAgICkKCikKIjt9aToyO2E6NTp7czo0OiJzdGVwIjtzOjE6IjIiO3M6NDoidHJJRCI7aTo0NzA3MTtzOjU6Im5vbWVyIjtzOjk6IjQ2NC4tMS40MyI7czo4OiJjb3VudGVycyI7czozMjA6IllUbzFPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lORFkwZkMweElqdHBPalF6TzMxek9qRTFPaUp6ZEdWd1EyOWtaWHh2YkdWb1NVUWlPMkU2TVRwN2N6bzJPaUkwTmpSOE1UY2lPMms2TWp0OWN6b3lNem9pYzNSbGNFTnZaR1Y4Y0d4aFkyVkpSSHh2YkdWb1NVUWlPMkU2TVRwN2N6bzVPaUkwTmpSOExURjhNVGNpTzJrNk1qdDljem94T1RvaWMzUmxjRU52WkdWOGMzVndjR3hwWlhKSlJDSTdZVG94T250ek9qYzZJalEyTkh3MU5qQWlPMms2TWp0OWN6bzRPaUp6ZEdWd1EyOWtaU0k3WVRveE9udHBPalEyTkR0cE9qUXpPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NDA5OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8LTFdID0+IDQzCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fDE3XSA9PiAyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8LTF8MTddID0+IDIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fDU2MF0gPT4gMgogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0XSA9PiA0MwogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czo5OiI0NjQuLTEuNDMiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0Mjg2NTMzMzkuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0MjY2NTMzMzkuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6LTk3MjU1MTEzO3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA3MSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNzEiO31pOjE5O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDMgMTg6MDE6MjEiO3M6NToiamVuaXMiO3M6MTk6Ik90b3Jpc2FzaSBVYW5nIE11a2EiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtzOjIyOiJDUlVaRSBJTlRFUklPUiBERVNJR04gIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo4OiJob2xkaW5nXyI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToyOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDUxMzg7czo1OiJub21lciI7czoxMDoiNDY0ci4tMS40OSI7czo4OiJjb3VudGVycyI7czozNDA6IllUbzFPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lORFkwY253dE1TSTdhVG8wT1R0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZPRG9pTkRZMGNud3pNVFlpTzJrNk16STdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRFNklqUTJOSEo4TFRGOE16RTJJanRwT2pNeU8zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk9Eb2lORFkwY253eE56Y2lPMms2TkR0OWN6bzRPaUp6ZEdWd1EyOWtaU0k3WVRveE9udHpPalE2SWpRMk5ISWlPMms2TkRrN2ZYMD0iO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo0MTg6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8LTFdID0+IDQ5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwzMTZdID0+IDMyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfC0xfDMxNl0gPT4gMzIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwxNzddID0+IDQKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJdID0+IDQ5CiAgICAgICAgKQoKKQoiO31pOjI7YTo1OntzOjQ6InN0ZXAiO3M6MToiMiI7czo0OiJ0cklEIjtpOjQ3MDczO3M6NToibm9tZXIiO3M6OToiNDY0Li0xLjQ0IjtzOjg6ImNvdW50ZXJzIjtzOjMyMDoiWVRvMU9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EWTBmQzB4SWp0cE9qUTBPMzF6T2pFMU9pSnpkR1Z3UTI5a1pYeHZiR1ZvU1VRaU8yRTZNVHA3Y3pvMk9pSTBOalI4TVRjaU8yazZNenQ5Y3pveU16b2ljM1JsY0VOdlpHVjhjR3hoWTJWSlJIeHZiR1ZvU1VRaU8yRTZNVHA3Y3pvNU9pSTBOalI4TFRGOE1UY2lPMms2TXp0OWN6b3hPVG9pYzNSbGNFTnZaR1Y4YzNWd2NHeHBaWEpKUkNJN1lUb3hPbnR6T2pjNklqUTJOSHd4TnpjaU8yazZNanQ5Y3pvNE9pSnpkR1Z3UTI5a1pTSTdZVG94T250cE9qUTJORHRwT2pRME8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo0MDk6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHwtMV0gPT4gNDQKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8MTddID0+IDMKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHwtMXwxN10gPT4gMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8MTc3XSA9PiAyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRdID0+IDQ0CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjk6IjQ2NC4tMS40NCI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQyNjY1MzMzOS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODM4NjY1MzMzOS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDotMTM3MjU1MTEzO3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA3MyI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNzMiO319'
        // );

        // $kiriman = Array
        // (
        //     "data" => "YTozMDp7aTowO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDEgMTU6MDQ6MzciO3M6NToiamVuaXMiO3M6Mzc6IlBlbWJheWFyYW4gSHV0YW5nIEJpYXlhIFVtdW0gKGNlbnRlcikiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtOO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0Njc4MztzOjU6Im5vbWVyIjtzOjExOiIxNDc1Li0xLjEyMCI7czo4OiJjb3VudGVycyI7czoyNzI6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lNVFEzTlh3dE1TSTdhVG94TWpBN2ZYTTZNVFU2SW5OMFpYQkRiMlJsZkc5c1pXaEpSQ0k3WVRveE9udHpPamc2SWpFME56VjhNekUySWp0cE9qRXhOanQ5Y3pveU16b2ljM1JsY0VOdlpHVjhjR3hoWTJWSlJIeHZiR1ZvU1VRaU8yRTZNVHA3Y3pveE1Ub2lNVFEzTlh3dE1Yd3pNVFlpTzJrNk1URTJPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk1UUTNOVHRwT2pFeU1EdDlmUT09IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM4OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDc1fC0xXSA9PiAxMjAKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDc1fDMxNl0gPT4gMTE2CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDc1fC0xfDMxNl0gPT4gMTE2CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDc1XSA9PiAxMjAKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTE6IjE0NzUuLTEuMTIwIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4Mzg3NTE3NTMzLjg1OTYwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4Mzg3NTAwNTMzLjg1OTYwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOi0xNzAwMDtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDY3ODMiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ2NzgzIjt9aToxO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDEgMTU6MDY6MjQiO3M6NToiamVuaXMiO3M6Mzc6IlBlbWJheWFyYW4gSHV0YW5nIEJpYXlhIFVtdW0gKGNlbnRlcikiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtOO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0Njc4NTtzOjU6Im5vbWVyIjtzOjExOiIxNDc1Li0xLjEyMSI7czo4OiJjb3VudGVycyI7czoyNzI6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lNVFEzTlh3dE1TSTdhVG94TWpFN2ZYTTZNVFU2SW5OMFpYQkRiMlJsZkc5c1pXaEpSQ0k3WVRveE9udHpPamc2SWpFME56VjhNekUySWp0cE9qRXhOenQ5Y3pveU16b2ljM1JsY0VOdlpHVjhjR3hoWTJWSlJIeHZiR1ZvU1VRaU8yRTZNVHA3Y3pveE1Ub2lNVFEzTlh3dE1Yd3pNVFlpTzJrNk1URTNPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk1UUTNOVHRwT2pFeU1UdDlmUT09IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM4OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDc1fC0xXSA9PiAxMjEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDc1fDMxNl0gPT4gMTE3CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDc1fC0xfDMxNl0gPT4gMTE3CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDc1XSA9PiAxMjEKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTE6IjE0NzUuLTEuMTIxIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4Mzg3NTAwNTMzLjg1OTYwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4Mzg3NDc3MzMzLjg1OTYwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOi00MDIwMDtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDY3ODUiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ2Nzg1Ijt9aToyO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDEgMTU6Mzg6MDAiO3M6NToiamVuaXMiO3M6MjI6IlBlbmVyaW1hYW4gU2V0b3JhbiBLYXMiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtOO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtzOjc6IlV1biBqa3QiO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJQVVNBVCI7czo3OiJpZHNfaGlzIjthOjI6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NjczNztzOjU6Im5vbWVyIjtzOjE0OiI3NTlyLjEuMjE2LjIxOSI7czo4OiJjb3VudGVycyI7czoyNzI6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lOelU1Y253eElqdHBPakkwT0R0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZPRG9pTnpVNWNud3lNVFlpTzJrNk1qRTVPMzF6T2pJek9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRWZHOXNaV2hKUkNJN1lUb3hPbnR6T2pFd09pSTNOVGx5ZkRGOE1qRTJJanRwT2pJeE9UdDljem80T2lKemRHVndRMjlrWlNJN1lUb3hPbnR6T2pRNklqYzFPWElpTzJrNk1qa3lPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM2OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyfDFdID0+IDI0OAogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJ8MjE2XSA9PiAyMTkKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJ8MXwyMTZdID0+IDIxOQogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cl0gPT4gMjkyCiAgICAgICAgKQoKKQoiO31pOjI7YTo1OntzOjQ6InN0ZXAiO3M6MToiMiI7czo0OiJ0cklEIjtpOjQ2Nzk1O3M6NToibm9tZXIiO3M6MTQ6Ijc1OC4tMS4zMTYuMTc5IjtzOjg6ImNvdW50ZXJzIjtzOjI2NDoiWVRvME9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU56VTRmQzB4SWp0cE9qSTRPRHQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk56b2lOelU0ZkRNeE5pSTdhVG94TnprN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEE2SWpjMU9Id3RNWHd6TVRZaU8yazZNVGM1TzMxek9qZzZJbk4wWlhCRGIyUmxJanRoT2pFNmUyazZOelU0TzJrNk1qZzRPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM0OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTh8LTFdID0+IDI4OAogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OHwzMTZdID0+IDE3OQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4fC0xfDMxNl0gPT4gMTc5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NThdID0+IDI4OAogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxNDoiNzU4Li0xLjMxNi4xNzkiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6IjgzODc0NzczMzMuODU5NjAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0MDk0NzczMzMuODU5NjAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6MjE5NTk4MDA7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ2Nzk1IjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0Njc5NSI7fWk6MzthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAxIDE1OjM5OjUzIjtzOjU6ImplbmlzIjtzOjIyOiJQZW5lcmltYWFuIFNldG9yYW4gS2FzIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7TjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7czo3OiJVdW4gamt0IjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToiUFVTQVQiO3M6NzoiaWRzX2hpcyI7YToyOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDY3NDE7czo1OiJub21lciI7czoxNDoiNzU5ci4xLjIxNi4yMjAiO3M6ODoiY291bnRlcnMiO3M6MjcyOiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTnpVNWNud3hJanRwT2pJME9UdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002T0RvaU56VTVjbnd5TVRZaU8yazZNakl3TzMxek9qSXpPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVmRzlzWldoSlJDSTdZVG94T250ek9qRXdPaUkzTlRseWZERjhNakUySWp0cE9qSXlNRHQ5Y3pvNE9pSnpkR1Z3UTI5a1pTSTdZVG94T250ek9qUTZJamMxT1hJaU8yazZNamt6TzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjMzNjoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cnwxXSA9PiAyNDkKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyfDIxNl0gPT4gMjIwCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyfDF8MjE2XSA9PiAyMjAKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJdID0+IDI5MwogICAgICAgICkKCikKIjt9aToyO2E6NTp7czo0OiJzdGVwIjtzOjE6IjIiO3M6NDoidHJJRCI7aTo0Njc5NztzOjU6Im5vbWVyIjtzOjE0OiI3NTguLTEuMzE2LjE4MCI7czo4OiJjb3VudGVycyI7czoyNjQ6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lOelU0ZkMweElqdHBPakk0T1R0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZOem9pTnpVNGZETXhOaUk3YVRveE9EQTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRBNklqYzFPSHd0TVh3ek1UWWlPMms2TVRnd08zMXpPamc2SW5OMFpYQkRiMlJsSWp0aE9qRTZlMms2TnpVNE8yazZNamc1TzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjMzNDoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4fC0xXSA9PiAyODkKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTh8MzE2XSA9PiAxODAKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OHwtMXwzMTZdID0+IDE4MAogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4XSA9PiAyODkKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTQ6Ijc1OC4tMS4zMTYuMTgwIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDA5NDc3MzMzLjg1OTYwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDEwMjk4NTgzLjg1OTYwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOjIyNzgxMDUwO3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0Njc5NyI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDY3OTciO31pOjQ7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMSAxNTo0MTowOSI7czo1OiJqZW5pcyI7czoyMjoiUGVuZXJpbWFhbiBTZXRvcmFuIEthcyI7czoxNDoic3VwcGxpZXJzX25hbWEiO047czoxNDoiY3VzdG9tZXJzX25hbWEiO3M6NzoiVXVuIGprdCI7czo5OiJvbGVoX25hbWEiO3M6NToiRmVycnkiO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6IlBVU0FUIjtzOjc6Imlkc19oaXMiO2E6Mjp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjQ2NzQ1O3M6NToibm9tZXIiO3M6MTQ6Ijc1OXIuMS4yMTYuMjIxIjtzOjg6ImNvdW50ZXJzIjtzOjI3MjoiWVRvME9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU56VTVjbnd4SWp0cE9qSTFNRHQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk9Eb2lOelU1Y253eU1UWWlPMms2TWpJeE8zMXpPakl6T2lKemRHVndRMjlrWlh4d2JHRmpaVWxFZkc5c1pXaEpSQ0k3WVRveE9udHpPakV3T2lJM05UbHlmREY4TWpFMklqdHBPakl5TVR0OWN6bzRPaUp6ZEdWd1EyOWtaU0k3WVRveE9udHpPalE2SWpjMU9YSWlPMms2TWprME8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzY6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJ8MV0gPT4gMjUwCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cnwyMTZdID0+IDIyMQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cnwxfDIxNl0gPT4gMjIxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyXSA9PiAyOTQKICAgICAgICApCgopCiI7fWk6MjthOjU6e3M6NDoic3RlcCI7czoxOiIyIjtzOjQ6InRySUQiO2k6NDY3OTk7czo1OiJub21lciI7czoxNDoiNzU4Li0xLjMxNi4xODEiO3M6ODoiY291bnRlcnMiO3M6MjY0OiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTnpVNGZDMHhJanRwT2pJNU1EdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002TnpvaU56VTRmRE14TmlJN2FUb3hPREU3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJamMxT0h3dE1Yd3pNVFlpTzJrNk1UZ3hPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk56VTRPMms2TWprd08zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzQ6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OHwtMV0gPT4gMjkwCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4fDMxNl0gPT4gMTgxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTh8LTF8MzE2XSA9PiAxODEKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OF0gPT4gMjkwCiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjE0OiI3NTguLTEuMzE2LjE4MSI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQxMDI5ODU4My44NTk2MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQxNDc3MzU4NC4wNTk2MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDoyNzI1NjA1MC4xOTk5OTk5OTk7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ2Nzk5IjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0Njc5OSI7fWk6NTthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAxIDE1OjQyOjA4IjtzOjU6ImplbmlzIjtzOjIyOiJQZW5lcmltYWFuIFNldG9yYW4gS2FzIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7TjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7czo3OiJVdW4gamt0IjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToiUFVTQVQiO3M6NzoiaWRzX2hpcyI7YToyOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDY3NDk7czo1OiJub21lciI7czoxNDoiNzU5ci4xLjIxNi4yMjIiO3M6ODoiY291bnRlcnMiO3M6MjcyOiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTnpVNWNud3hJanRwT2pJMU1UdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002T0RvaU56VTVjbnd5TVRZaU8yazZNakl5TzMxek9qSXpPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVmRzlzWldoSlJDSTdZVG94T250ek9qRXdPaUkzTlRseWZERjhNakUySWp0cE9qSXlNanQ5Y3pvNE9pSnpkR1Z3UTI5a1pTSTdZVG94T250ek9qUTZJamMxT1hJaU8yazZNamsxTzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjMzNjoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cnwxXSA9PiAyNTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyfDIxNl0gPT4gMjIyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyfDF8MjE2XSA9PiAyMjIKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJdID0+IDI5NQogICAgICAgICkKCikKIjt9aToyO2E6NTp7czo0OiJzdGVwIjtzOjE6IjIiO3M6NDoidHJJRCI7aTo0NjgwMTtzOjU6Im5vbWVyIjtzOjE0OiI3NTguLTEuMzE2LjE4MiI7czo4OiJjb3VudGVycyI7czoyNjQ6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lOelU0ZkMweElqdHBPakk1TVR0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZOem9pTnpVNGZETXhOaUk3YVRveE9ESTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRBNklqYzFPSHd0TVh3ek1UWWlPMms2TVRneU8zMXpPamc2SW5OMFpYQkRiMlJsSWp0aE9qRTZlMms2TnpVNE8yazZNamt4TzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjMzNDoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4fC0xXSA9PiAyOTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTh8MzE2XSA9PiAxODIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OHwtMXwzMTZdID0+IDE4MgogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4XSA9PiAyOTEKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTQ6Ijc1OC4tMS4zMTYuMTgyIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDE0NzczNTg0LjA1OTYwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDc1NDE4NTkwLjQ1OTYwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOjg3OTAxMDU2LjU5OTk5OTk5NDtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDY4MDEiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ2ODAxIjt9aTo2O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDEgMTU6NDM6MDUiO3M6NToiamVuaXMiO3M6MjI6IlBlbmVyaW1hYW4gU2V0b3JhbiBLYXMiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtOO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtzOjc6IlV1biBzYnkiO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJQVVNBVCI7czo3OiJpZHNfaGlzIjthOjI6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0Njc1NztzOjU6Im5vbWVyIjtzOjE0OiI3NTlyLjIxLjI3OS4zOSI7czo4OiJjb3VudGVycyI7czoyNzI6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lOelU1Y253eU1TSTdhVG8wTlR0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZPRG9pTnpVNWNud3lOemtpTzJrNk16azdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRFNklqYzFPWEo4TWpGOE1qYzVJanRwT2pNNU8zMXpPamc2SW5OMFpYQkRiMlJsSWp0aE9qRTZlM002TkRvaU56VTVjaUk3YVRveU9UWTdmWDA9IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM1OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyfDIxXSA9PiA0NQogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJ8Mjc5XSA9PiAzOQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cnwyMXwyNzldID0+IDM5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyXSA9PiAyOTYKICAgICAgICApCgopCiI7fWk6MjthOjU6e3M6NDoic3RlcCI7czoxOiIyIjtzOjQ6InRySUQiO2k6NDY4MDM7czo1OiJub21lciI7czoxNDoiNzU4Li0xLjMxNi4xODMiO3M6ODoiY291bnRlcnMiO3M6MjY0OiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTnpVNGZDMHhJanRwT2pJNU1qdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002TnpvaU56VTRmRE14TmlJN2FUb3hPRE03ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJamMxT0h3dE1Yd3pNVFlpTzJrNk1UZ3pPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk56VTRPMms2TWpreU8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzQ6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OHwtMV0gPT4gMjkyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4fDMxNl0gPT4gMTgzCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTh8LTF8MzE2XSA9PiAxODMKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OF0gPT4gMjkyCiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjE0OiI3NTguLTEuMzE2LjE4MyI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQ3NTQxODU5MC40NTk2MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQ4MTQxODU5MC4wNTk3MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDo5MzkwMTA1Ni4yMDAwODg5OTM7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ2ODAzIjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NjgwMyI7fWk6NzthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAxIDE1OjQzOjMyIjtzOjU6ImplbmlzIjtzOjIyOiJQZW5lcmltYWFuIFNldG9yYW4gS2FzIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7TjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7czo3OiJVdW4gc2J5IjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToiUFVTQVQiO3M6NzoiaWRzX2hpcyI7YToyOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDY3NjE7czo1OiJub21lciI7czoxNDoiNzU5ci4yMS4yNzkuNDAiO3M6ODoiY291bnRlcnMiO3M6MjcyOiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pTnpVNWNud3lNU0k3YVRvME5qdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002T0RvaU56VTVjbnd5TnpraU8yazZOREE3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1URTZJamMxT1hKOE1qRjhNamM1SWp0cE9qUXdPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTNNNk5Eb2lOelU1Y2lJN2FUb3lPVGM3ZlgwPSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjMzNToiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cnwyMV0gPT4gNDYKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyfDI3OV0gPT4gNDAKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJ8MjF8Mjc5XSA9PiA0MAogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cl0gPT4gMjk3CiAgICAgICAgKQoKKQoiO31pOjI7YTo1OntzOjQ6InN0ZXAiO3M6MToiMiI7czo0OiJ0cklEIjtpOjQ2ODA1O3M6NToibm9tZXIiO3M6MTQ6Ijc1OC4tMS4zMTYuMTg0IjtzOjg6ImNvdW50ZXJzIjtzOjI2NDoiWVRvME9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU56VTRmQzB4SWp0cE9qSTVNenQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk56b2lOelU0ZkRNeE5pSTdhVG94T0RRN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEE2SWpjMU9Id3RNWHd6TVRZaU8yazZNVGcwTzMxek9qZzZJbk4wWlhCRGIyUmxJanRoT2pFNmUyazZOelU0TzJrNk1qa3pPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM0OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTh8LTFdID0+IDI5MwogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OHwzMTZdID0+IDE4NAogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4fC0xfDMxNl0gPT4gMTg0CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NThdID0+IDI5MwogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxNDoiNzU4Li0xLjMxNi4xODQiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0ODE0MTg1OTAuMDU5NzAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg1MTcwNTg1OTAuMDU5NzAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6MTI5NTQxMDU2LjIwMDA4ODk5O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NjgwNSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDY4MDUiO31pOjg7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMSAxNTo0NDowMiI7czo1OiJqZW5pcyI7czoyMjoiUGVuZXJpbWFhbiBTZXRvcmFuIEthcyI7czoxNDoic3VwcGxpZXJzX25hbWEiO047czoxNDoiY3VzdG9tZXJzX25hbWEiO3M6NzoiVXVuIHNieSI7czo5OiJvbGVoX25hbWEiO3M6NToiRmVycnkiO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6IlBVU0FUIjtzOjc6Imlkc19oaXMiO2E6Mjp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjQ2NzY1O3M6NToibm9tZXIiO3M6MTQ6Ijc1OXIuMjEuMjc5LjQxIjtzOjg6ImNvdW50ZXJzIjtzOjI3MjoiWVRvME9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TnpvaU56VTVjbnd5TVNJN2FUbzBOenQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk9Eb2lOelU1Y253eU56a2lPMms2TkRFN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEU2SWpjMU9YSjhNakY4TWpjNUlqdHBPalF4TzMxek9qZzZJbk4wWlhCRGIyUmxJanRoT2pFNmUzTTZORG9pTnpVNWNpSTdhVG95T1RnN2ZYMD0iO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzU6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJ8MjFdID0+IDQ3CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cnwyNzldID0+IDQxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyfDIxfDI3OV0gPT4gNDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJdID0+IDI5OAogICAgICAgICkKCikKIjt9aToyO2E6NTp7czo0OiJzdGVwIjtzOjE6IjIiO3M6NDoidHJJRCI7aTo0NjgwNztzOjU6Im5vbWVyIjtzOjE0OiI3NTguLTEuMzE2LjE4NSI7czo4OiJjb3VudGVycyI7czoyNjQ6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lOelU0ZkMweElqdHBPakk1TkR0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZOem9pTnpVNGZETXhOaUk3YVRveE9EVTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRBNklqYzFPSHd0TVh3ek1UWWlPMms2TVRnMU8zMXpPamc2SW5OMFpYQkRiMlJsSWp0aE9qRTZlMms2TnpVNE8yazZNamswTzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjMzNDoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4fC0xXSA9PiAyOTQKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTh8MzE2XSA9PiAxODUKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OHwtMXwzMTZdID0+IDE4NQogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4XSA9PiAyOTQKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTQ6Ijc1OC4tMS4zMTYuMTg1IjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NTE3MDU4NTkwLjA1OTcwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NTIwMDU4NTkwLjM1OTcwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOjEzMjU0MTA1Ni41MDAwODg5OTtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDY4MDciO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ2ODA3Ijt9aTo5O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDEgMTU6NDU6MjAiO3M6NToiamVuaXMiO3M6MjI6IlBlbmVyaW1hYW4gU2V0b3JhbiBLYXMiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtOO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtzOjc6IlV1biBzYnkiO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJQVVNBVCI7czo3OiJpZHNfaGlzIjthOjI6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0Njc2OTtzOjU6Im5vbWVyIjtzOjE0OiI3NTlyLjIxLjI3OS40MiI7czo4OiJjb3VudGVycyI7czoyNzI6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lOelU1Y253eU1TSTdhVG8wT0R0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZPRG9pTnpVNWNud3lOemtpTzJrNk5ESTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRFNklqYzFPWEo4TWpGOE1qYzVJanRwT2pReU8zMXpPamc2SW5OMFpYQkRiMlJsSWp0aE9qRTZlM002TkRvaU56VTVjaUk3YVRveU9UazdmWDA9IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM1OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyfDIxXSA9PiA0OAogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OXJ8Mjc5XSA9PiA0MgogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU5cnwyMXwyNzldID0+IDQyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTlyXSA9PiAyOTkKICAgICAgICApCgopCiI7fWk6MjthOjU6e3M6NDoic3RlcCI7czoxOiIyIjtzOjQ6InRySUQiO2k6NDY4MDk7czo1OiJub21lciI7czoxNDoiNzU4Li0xLjMxNi4xODYiO3M6ODoiY291bnRlcnMiO3M6MjY0OiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTnpVNGZDMHhJanRwT2pJNU5UdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002TnpvaU56VTRmRE14TmlJN2FUb3hPRFk3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJamMxT0h3dE1Yd3pNVFlpTzJrNk1UZzJPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk56VTRPMms2TWprMU8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzQ6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OHwtMV0gPT4gMjk1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNzU4fDMxNl0gPT4gMTg2CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs3NTh8LTF8MzE2XSA9PiAxODYKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzc1OF0gPT4gMjk1CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjE0OiI3NTguLTEuMzE2LjE4NiI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODUyMDA1ODU5MC4zNTk3MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODUyMzkwODQ1Mi45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDoxMzYzOTA5MTkuMDg0MjM4OTg7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ2ODA5IjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NjgwOSI7fWk6MTA7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMiAxNjoxNTo1NCI7czo1OiJqZW5pcyI7czozMToiUGVtYmF5YXJhbiBIdXRhbmcgSmFzYSAoY2VudGVyKSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MTk6IlNISU5UQSBJTlNFUlZFLCBQVCAiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0Njk5MTtzOjU6Im5vbWVyIjtzOjE0OiIxNDYyLi0xLjE4NC4xNyI7czo4OiJjb3VudGVycyI7czo0MjQ6IllUbzJPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lNVFEyTW53dE1TSTdhVG8xTlR0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZPRG9pTVRRMk1ud3pNVFlpTzJrNk5UQTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRFNklqRTBOako4TFRGOE16RTJJanRwT2pVd08zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk9Eb2lNVFEyTW53eE9EUWlPMms2TVRjN2ZYTTZNamM2SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YzNWd2NHeHBaWEpKUkNJN1lUb3hPbnR6T2pFeE9pSXhORFl5ZkMweGZERTROQ0k3YVRveE56dDljem80T2lKemRHVndRMjlrWlNJN1lUb3hPbnRwT2pFME5qSTdhVG8xTlR0OWZRPT0iO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MTU6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzE0NjJ8LTFdID0+IDU1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwzMTZdID0+IDUwCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfC0xfDMxNl0gPT4gNTAKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwxODRdID0+IDE3CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwtMXwxODRdID0+IDE3CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyXSA9PiA1NQogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxNDoiMTQ2Mi4tMS4xODQuMTciO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg1MjM5MDg0NTIuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg1MjM2Mzc0MDMuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6MTM2MTE5ODcwLjA4NDIzODk4O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0Njk5MSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDY5OTEiO31pOjExO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDIgMTY6MTc6NDgiO3M6NToiamVuaXMiO3M6MzE6IlBlbWJheWFyYW4gSHV0YW5nIEphc2EgKGNlbnRlcikiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtzOjI2OiJERUxBUEFOIENBSEFZQSBTVUtTRVMsIFBUICI7czoxNDoiY3VzdG9tZXJzX25hbWEiO047czo5OiJvbGVoX25hbWEiO3M6NToiRmVycnkiO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6InB1c2F0IjtzOjc6Imlkc19oaXMiO2E6MTp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjQ2OTkzO3M6NToibm9tZXIiO3M6MTQ6IjE0NjIuLTEuMTc4LjE5IjtzOjg6ImNvdW50ZXJzIjtzOjQyNDoiWVRvMk9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TnpvaU1UUTJNbnd0TVNJN2FUbzFOanQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk9Eb2lNVFEyTW53ek1UWWlPMms2TlRFN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEU2SWpFME5qSjhMVEY4TXpFMklqdHBPalV4TzMxek9qRTVPaUp6ZEdWd1EyOWtaWHh6ZFhCd2JHbGxja2xFSWp0aE9qRTZlM002T0RvaU1UUTJNbnd4TnpnaU8yazZNVGs3ZlhNNk1qYzZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPakV4T2lJeE5EWXlmQzB4ZkRFM09DSTdhVG94T1R0OWN6bzRPaUp6ZEdWd1EyOWtaU0k3WVRveE9udHBPakUwTmpJN2FUbzFOanQ5ZlE9PSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjUxNToiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwtMV0gPT4gNTYKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfDMxNl0gPT4gNTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzE0NjJ8LTF8MzE2XSA9PiA1MQogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfDE3OF0gPT4gMTkKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfC0xfDE3OF0gPT4gMTkKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzE0NjJdID0+IDU2CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjE0OiIxNDYyLi0xLjE3OC4xOSI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODUyMzYzNzQwMy45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODUxMTI0MjE1My45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDoxMjM3MjQ2MjAuMDg0MjM4OTg7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ2OTkzIjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0Njk5MyI7fWk6MTI7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMiAxNjoyNjo0MSI7czo1OiJqZW5pcyI7czoyMjoiUGVtYmF5YXJhbiBIdXRhbmcgSmFzYSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MjY6IlNISUJBIEhJRFJPTElLIFBSQVRBTUEsIFBUIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwMDU7czo1OiJub21lciI7czoxMjoiNDYyLi0xLjMwNi4zIjtzOjg6ImNvdW50ZXJzIjtzOjQxNjoiWVRvMk9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EWXlmQzB4SWp0cE9qRTFOVHQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk56b2lORFl5ZkRNeE5pSTdhVG94TlRFN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEE2SWpRMk1ud3RNWHd6TVRZaU8yazZNVFV4TzMxek9qRTVPaUp6ZEdWd1EyOWtaWHh6ZFhCd2JHbGxja2xFSWp0aE9qRTZlM002TnpvaU5EWXlmRE13TmlJN2FUb3pPMzF6T2pJM09pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRWZITjFjSEJzYVdWeVNVUWlPMkU2TVRwN2N6b3hNRG9pTkRZeWZDMHhmRE13TmlJN2FUb3pPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk5EWXlPMms2TVRVMU8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MTE6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2MnwtMV0gPT4gMTU1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDYyfDMxNl0gPT4gMTUxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjJ8LTF8MzE2XSA9PiAxNTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDYyfDMwNl0gPT4gMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2MnwtMXwzMDZdID0+IDMKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2Ml0gPT4gMTU1CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjEyOiI0NjIuLTEuMzA2LjMiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg1MTEyNDIxNTMuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg1MDk3NzIxNTMuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6MTIyMjU0NjIwLjA4NDIzODk4O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzAwNSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwMDUiO31pOjEzO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDIgMTY6MzU6NDgiO3M6NToiamVuaXMiO3M6MjI6IlBlbWJheWFyYW4gSHV0YW5nIEphc2EiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtzOjI0OiJCSU5UQU5HIFNBVURBUkEgRVhQUkVTUyAiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NzAxMTtzOjU6Im5vbWVyIjtzOjEyOiI0NjIuLTEuMTIwLjUiO3M6ODoiY291bnRlcnMiO3M6NDE2OiJZVG8yT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTkRZeWZDMHhJanRwT2pFMU5qdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002TnpvaU5EWXlmRE14TmlJN2FUb3hOVEk3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJalEyTW53dE1Yd3pNVFlpTzJrNk1UVXlPMzF6T2pFNU9pSnpkR1Z3UTI5a1pYeHpkWEJ3YkdsbGNrbEVJanRoT2pFNmUzTTZOem9pTkRZeWZERXlNQ0k3YVRvMU8zMXpPakkzT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFZkhOMWNIQnNhV1Z5U1VRaU8yRTZNVHA3Y3pveE1Eb2lORFl5ZkMweGZERXlNQ0k3YVRvMU8zMXpPamc2SW5OMFpYQkRiMlJsSWp0aE9qRTZlMms2TkRZeU8yazZNVFUyTzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjUxMToiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDYyfC0xXSA9PiAxNTYKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjJ8MzE2XSA9PiAxNTIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2MnwtMXwzMTZdID0+IDE1MgogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjJ8MTIwXSA9PiA1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDYyfC0xfDEyMF0gPT4gNQogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDYyXSA9PiAxNTYKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTI6IjQ2Mi4tMS4xMjAuNSI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODUwOTc3MjE1My45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODUwNjU2NzQ4MS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDoxMTkwNDk5NDguMDg0MjM4OTg7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ3MDExIjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NzAxMSI7fWk6MTQ7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMiAxNjo0MToxMyI7czo1OiJqZW5pcyI7czoxOToiT3RvcmlzYXNpIFVhbmcgTXVrYSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MzE6IlBULiBGZWRFeCBFeHByZXNzIEludGVybmF0aW9uYWwiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjI6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NTY0MTtzOjU6Im5vbWVyIjtzOjEwOiI0NjRyLi0xLjUxIjtzOjg6ImNvdW50ZXJzIjtzOjM0MDoiWVRvMU9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TnpvaU5EWTBjbnd0TVNJN2FUbzFNVHQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk9Eb2lORFkwY253ek1UWWlPMms2TXpRN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEU2SWpRMk5ISjhMVEY4TXpFMklqdHBPak0wTzMxek9qRTVPaUp6ZEdWd1EyOWtaWHh6ZFhCd2JHbGxja2xFSWp0aE9qRTZlM002T0RvaU5EWTBjbnd4T0RNaU8yazZNVHQ5Y3pvNE9pSnpkR1Z3UTI5a1pTSTdZVG94T250ek9qUTZJalEyTkhJaU8yazZOVEU3ZlgwPSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjQxODoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwtMV0gPT4gNTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfDMxNl0gPT4gMzQKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8LTF8MzE2XSA9PiAzNAogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfDE4M10gPT4gMQogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cl0gPT4gNTEKICAgICAgICApCgopCiI7fWk6MjthOjU6e3M6NDoic3RlcCI7czoxOiIyIjtzOjQ6InRySUQiO2k6NDcwMTU7czo1OiJub21lciI7czo5OiI0NjQuLTEuNDEiO3M6ODoiY291bnRlcnMiO3M6MzI4OiJZVG8xT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTkRZMGZDMHhJanRwT2pReE8zMXpPakUxT2lKemRHVndRMjlrWlh4dmJHVm9TVVFpTzJFNk1UcDdjem8zT2lJME5qUjhNekUySWp0cE9qSXlPMzF6T2pJek9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRWZHOXNaV2hKUkNJN1lUb3hPbnR6T2pFd09pSTBOalI4TFRGOE16RTJJanRwT2pJeU8zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk56b2lORFkwZkRFNE15STdhVG94TzMxek9qZzZJbk4wWlhCRGIyUmxJanRoT2pFNmUyazZORFkwTzJrNk5ERTdmWDA9IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NDEzOiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8LTFdID0+IDQxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fDMxNl0gPT4gMjIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHwtMXwzMTZdID0+IDIyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHwxODNdID0+IDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NF0gPT4gNDEKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6OToiNDY0Li0xLjQxIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NTA2NTY3NDgxLjk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NTA1MjI1NDgxLjk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOjExNzcwNzk0OC4wODQyMzg5ODtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwMTUiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDE1Ijt9aToxNTthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAyIDE2OjUxOjM3IjtzOjU6ImplbmlzIjtzOjMzOiJQZW1iYXlhcmFuIEh1dGFuZyBEYWdhbmcgKFByb2R1aykiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtzOjI1OiJDQUhBWUEgVEVLTklLIE1BTkRJUkksIENWIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwMTk7czo1OiJub21lciI7czoxMjoiNDg5Li0xLjU5Ny4xIjtzOjg6ImNvdW50ZXJzIjtzOjQxNjoiWVRvMk9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EZzVmQzB4SWp0cE9qRXdNanQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk56b2lORGc1ZkRNeE5pSTdhVG81TVR0OWN6b3lNem9pYzNSbGNFTnZaR1Y4Y0d4aFkyVkpSSHh2YkdWb1NVUWlPMkU2TVRwN2N6b3hNRG9pTkRnNWZDMHhmRE14TmlJN2FUbzVNVHQ5Y3pveE9Ub2ljM1JsY0VOdlpHVjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPamM2SWpRNE9YdzFPVGNpTzJrNk1UdDljem95TnpvaWMzUmxjRU52WkdWOGNHeGhZMlZKUkh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk1UQTZJalE0T1h3dE1YdzFPVGNpTzJrNk1UdDljem80T2lKemRHVndRMjlrWlNJN1lUb3hPbnRwT2pRNE9UdHBPakV3TWp0OWZRPT0iO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MDk6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXwtMV0gPT4gMTAyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg5fDMxNl0gPT4gOTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXwtMXwzMTZdID0+IDkxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXw1OTddID0+IDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODl8LTF8NTk3XSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODldID0+IDEwMgogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMjoiNDg5Li0xLjU5Ny4xIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NTA1MjI1NDgxLjk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NTA0MzY1NDgxLjk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOjExNjg0Nzk0OC4wODQyMzg5ODtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwMTkiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDE5Ijt9aToxNjthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAyIDE3OjIwOjEzIjtzOjU6ImplbmlzIjtzOjM1OiJQZW1iYXlhcmFuIEh1dGFuZyBEYWdhbmcgKFN1cHBsaWVzKSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MjM6IlBFUkNFVEFLQU4gQ0lUUkEgRFVOSUEgIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo1OiJGZXJyeSI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwMzU7czo1OiJub21lciI7czoxMjoiNDg3Li0xLjkyLjExIjtzOjg6ImNvdW50ZXJzIjtzOjQxNjoiWVRvMk9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EZzNmQzB4SWp0cE9qRXlNenQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk56b2lORGczZkRNeE5pSTdhVG94TVRjN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEE2SWpRNE4zd3RNWHd6TVRZaU8yazZNVEUzTzMxek9qRTVPaUp6ZEdWd1EyOWtaWHh6ZFhCd2JHbGxja2xFSWp0aE9qRTZlM002TmpvaU5EZzNmRGt5SWp0cE9qRXhPMzF6T2pJM09pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRWZITjFjSEJzYVdWeVNVUWlPMkU2TVRwN2N6bzVPaUkwT0RkOExURjhPVElpTzJrNk1URTdmWE02T0RvaWMzUmxjRU52WkdVaU8yRTZNVHA3YVRvME9EYzdhVG94TWpNN2ZYMD0iO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MTE6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N3wtMV0gPT4gMTIzCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fDMxNl0gPT4gMTE3CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODd8LTF8MzE2XSA9PiAxMTcKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fDkyXSA9PiAxMQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N3wtMXw5Ml0gPT4gMTEKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N10gPT4gMTIzCiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjEyOiI0ODcuLTEuOTIuMTEiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg1MDQzNjU0ODEuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0NzcwNjU0ODEuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6ODk1NDc5NDguMDg0MjM4OTc2O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzAzNSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwMzUiO31pOjE3O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDIgMTc6MjI6MTAiO3M6NToiamVuaXMiO3M6MzU6IlBlbWJheWFyYW4gSHV0YW5nIERhZ2FuZyAoU3VwcGxpZXMpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7czoxNDoiQVRNSSBTT0xPLCBQVCAiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NzAzOTtzOjU6Im5vbWVyIjtzOjExOiI0ODcuLTEuMTkuNyI7czo4OiJjb3VudGVycyI7czo0MTI6IllUbzJPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lORGczZkMweElqdHBPakV5TkR0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZOem9pTkRnM2ZETXhOaUk3YVRveE1UZzdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRBNklqUTROM3d0TVh3ek1UWWlPMms2TVRFNE8zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk5qb2lORGczZkRFNUlqdHBPamM3ZlhNNk1qYzZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPams2SWpRNE4zd3RNWHd4T1NJN2FUbzNPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk5EZzNPMms2TVRJME8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MDk6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N3wtMV0gPT4gMTI0CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fDMxNl0gPT4gMTE4CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODd8LTF8MzE2XSA9PiAxMTgKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fDE5XSA9PiA3CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fC0xfDE5XSA9PiA3CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODddID0+IDEyNAogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMToiNDg3Li0xLjE5LjciO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0NzcwNjU0ODEuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0NjM2NTUzODEuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6NzYxMzc4NDguMDg0MjM4OTc2O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzAzOSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwMzkiO31pOjE4O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDIgMTc6MjM6NTQiO3M6NToiamVuaXMiO3M6MzU6IlBlbWJheWFyYW4gSHV0YW5nIERhZ2FuZyAoU3VwcGxpZXMpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7czoxNDoiQVRNSSBTT0xPLCBQVCAiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjU6IkZlcnJ5IjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NzA0MTtzOjU6Im5vbWVyIjtzOjExOiI0ODcuLTEuMTkuOCI7czo4OiJjb3VudGVycyI7czo0MTI6IllUbzJPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lORGczZkMweElqdHBPakV5TlR0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZOem9pTkRnM2ZETXhOaUk3YVRveE1UazdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRBNklqUTROM3d0TVh3ek1UWWlPMms2TVRFNU8zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk5qb2lORGczZkRFNUlqdHBPamc3ZlhNNk1qYzZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPams2SWpRNE4zd3RNWHd4T1NJN2FUbzRPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk5EZzNPMms2TVRJMU8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo1MDk6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4N3wtMV0gPT4gMTI1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fDMxNl0gPT4gMTE5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODd8LTF8MzE2XSA9PiAxMTkKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fDE5XSA9PiA4CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg3fC0xfDE5XSA9PiA4CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODddID0+IDEyNQogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMToiNDg3Li0xLjE5LjgiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0NjM2NTUzODEuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0MjQ1MzkzODEuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6MzcwMjE4NDguMDg0MjM4OTc2O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA0MSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNDEiO31pOjE5O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDIgMTk6Mjg6MTMiO3M6NToiamVuaXMiO3M6MzE6IlBlbWJheWFyYW4gSHV0YW5nIEphc2EgKGNlbnRlcikiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtzOjMxOiJQVC4gRmVkRXggRXhwcmVzcyBJbnRlcm5hdGlvbmFsIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7YjowO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6InB1c2F0IjtzOjc6Imlkc19oaXMiO2E6MTp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjQ3MDUzO3M6NToibm9tZXIiO3M6MTM6IjE0NjIuLTEuMTgzLjIiO3M6ODoiY291bnRlcnMiO3M6NDE2OiJZVG8yT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pTVRRMk1ud3RNU0k3YVRvMU56dDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002T0RvaU1UUTJNbnd5TXpNaU8yazZNVHQ5Y3pveU16b2ljM1JsY0VOdlpHVjhjR3hoWTJWSlJIeHZiR1ZvU1VRaU8yRTZNVHA3Y3pveE1Ub2lNVFEyTW53dE1Yd3lNek1pTzJrNk1UdDljem94T1RvaWMzUmxjRU52WkdWOGMzVndjR3hwWlhKSlJDSTdZVG94T250ek9qZzZJakUwTmpKOE1UZ3pJanRwT2pJN2ZYTTZNamM2SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YzNWd2NHeHBaWEpKUkNJN1lUb3hPbnR6T2pFeE9pSXhORFl5ZkMweGZERTRNeUk3YVRveU8zMXpPamc2SW5OMFpYQkRiMlJsSWp0aE9qRTZlMms2TVRRMk1qdHBPalUzTzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjUxMToiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwtMV0gPT4gNTcKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfDIzM10gPT4gMQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwtMXwyMzNdID0+IDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2MnwxODNdID0+IDIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFsxNDYyfC0xfDE4M10gPT4gMgogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbMTQ2Ml0gPT4gNTcKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTM6IjE0NjIuLTEuMTgzLjIiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0MjQ1MzkzODEuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0MjMxODgzODEuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6MzU2NzA4NDguMDg0MjM4OTc2O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA1MyI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNTMiO31pOjIwO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDMgMTA6MzI6MzQiO3M6NToiamVuaXMiO3M6MzM6IlBlbWJheWFyYW4gSHV0YW5nIERhZ2FuZyAoUHJvZHVrKSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MTQ6IlRPS09QRURJQSwgUFQgIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo4OiJob2xkaW5nXyI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwNTU7czo1OiJub21lciI7czoxMjoiNDg5Li0xLjE0Mi4xIjtzOjg6ImNvdW50ZXJzIjtzOjQwODoiWVRvMk9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EZzVmQzB4SWp0cE9qRXdNenQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk5qb2lORGc1ZkRFM0lqdHBPakU3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk9Ub2lORGc1ZkMweGZERTNJanRwT2pFN2ZYTTZNVGs2SW5OMFpYQkRiMlJsZkhOMWNIQnNhV1Z5U1VRaU8yRTZNVHA3Y3pvM09pSTBPRGw4TVRReUlqdHBPakU3ZlhNNk1qYzZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPakV3T2lJME9EbDhMVEY4TVRReUlqdHBPakU3ZlhNNk9Eb2ljM1JsY0VOdlpHVWlPMkU2TVRwN2FUbzBPRGs3YVRveE1ETTdmWDA9IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NTA1OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODl8LTFdID0+IDEwMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXwxN10gPT4gMQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDg5fC0xfDE3XSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ4OXwxNDJdID0+IDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODl8LTF8MTQyXSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0ODldID0+IDEwMwogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMjoiNDg5Li0xLjE0Mi4xIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDIzMTg4MzgxLjk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDIyOTg4MzgxLjk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOjM1NDcwODQ4LjA4NDIzODk3NjtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwNTUiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDU1Ijt9aToyMTthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAzIDEwOjQ1OjI2IjtzOjU6ImplbmlzIjtzOjIwOiJQZW1iYXRhbGFuIFRyYW5zYWtzaSI7czoxNDoic3VwcGxpZXJzX25hbWEiO047czoxNDoiY3VzdG9tZXJzX25hbWEiO047czo5OiJvbGVoX25hbWEiO3M6ODoiaG9sZGluZ18iO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6InB1c2F0IjtzOjc6Imlkc19oaXMiO2E6MTp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjQ3MDU3O3M6NToibm9tZXIiO3M6MTE6Ijk5MTEuLTEuMTYyIjtzOjg6ImNvdW50ZXJzIjtzOjI2NDoiWVRvME9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TnpvaU9Ua3hNWHd0TVNJN2FUb3hOakk3ZlhNNk1UVTZJbk4wWlhCRGIyUmxmRzlzWldoSlJDSTdZVG94T250ek9qYzZJams1TVRGOE1UY2lPMms2T1R0OWN6b3lNem9pYzNSbGNFTnZaR1Y4Y0d4aFkyVkpSSHh2YkdWb1NVUWlPMkU2TVRwN2N6b3hNRG9pT1RreE1Yd3RNWHd4TnlJN2FUbzVPMzF6T2pnNkluTjBaWEJEYjJSbElqdGhPakU2ZTJrNk9Ua3hNVHRwT2pFMk1qdDlmUT09IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzMyOiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xXSA9PiAxNjIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfDE3XSA9PiA5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xfDE3XSA9PiA5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExXSA9PiAxNjIKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTE6Ijk5MTEuLTEuMTYyIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDIyOTg4MzgxLjk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDIzMTg4MzgxLjk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOjM1NjcwODQ4LjA4NDIzODk3NjtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwNTciO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDU3Ijt9aToyMjthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAzIDExOjA2OjI1IjtzOjU6ImplbmlzIjtzOjIwOiJQZW1iYXRhbGFuIFRyYW5zYWtzaSI7czoxNDoic3VwcGxpZXJzX25hbWEiO047czoxNDoiY3VzdG9tZXJzX25hbWEiO047czo5OiJvbGVoX25hbWEiO3M6ODoiaG9sZGluZ18iO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6InB1c2F0IjtzOjc6Imlkc19oaXMiO2E6MTp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjQ3MDU5O3M6NToibm9tZXIiO3M6MTE6Ijk5MTEuLTEuMTYzIjtzOjg6ImNvdW50ZXJzIjtzOjI2NDoiWVRvME9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TnpvaU9Ua3hNWHd0TVNJN2FUb3hOak03ZlhNNk1UVTZJbk4wWlhCRGIyUmxmRzlzWldoSlJDSTdZVG94T250ek9qYzZJams1TVRGOE1UY2lPMms2TVRBN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEE2SWprNU1URjhMVEY4TVRjaU8yazZNVEE3ZlhNNk9Eb2ljM1JsY0VOdlpHVWlPMkU2TVRwN2FUbzVPVEV4TzJrNk1UWXpPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM0OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xXSA9PiAxNjMKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfDE3XSA9PiAxMAogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwtMXwxN10gPT4gMTAKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTFdID0+IDE2MwogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMToiOTkxMS4tMS4xNjMiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0MjMxODgzODEuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0MjMzODgzODEuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6MzU4NzA4NDguMDg0MjM4OTc2O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA1OSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNTkiO31pOjIzO2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDMgMTE6MDk6NTYiO3M6NToiamVuaXMiO3M6MjA6IlBlbWJhdGFsYW4gVHJhbnNha3NpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7TjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo4OiJob2xkaW5nXyI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwNjE7czo1OiJub21lciI7czoxMToiOTkxMS4tMS4xNjQiO3M6ODoiY291bnRlcnMiO3M6MjY0OiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pT1RreE1Yd3RNU0k3YVRveE5qUTdmWE02TVRVNkluTjBaWEJEYjJSbGZHOXNaV2hKUkNJN1lUb3hPbnR6T2pjNklqazVNVEY4TVRjaU8yazZNVEU3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJams1TVRGOExURjhNVGNpTzJrNk1URTdmWE02T0RvaWMzUmxjRU52WkdVaU8yRTZNVHA3YVRvNU9URXhPMms2TVRZME8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzQ6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8LTFdID0+IDE2NAogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8MTddID0+IDExCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xfDE3XSA9PiAxMQogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMV0gPT4gMTY0CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjExOiI5OTExLi0xLjE2NCI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQyMzM4ODM4MS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQyNzQxMDEzOS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDozOTg5MjYwNi4wODQyMzg5NzY7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ3MDYxIjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NzA2MSI7fWk6MjQ7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMyAxMToxMzoxOSI7czo1OiJqZW5pcyI7czoyMDoiUGVtYmF0YWxhbiBUcmFuc2Frc2kiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtOO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjg6ImhvbGRpbmdfIjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjE6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTo0NzA2MztzOjU6Im5vbWVyIjtzOjExOiI5OTExLi0xLjE2NSI7czo4OiJjb3VudGVycyI7czoyNjQ6IllUbzBPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lPVGt4TVh3dE1TSTdhVG94TmpVN2ZYTTZNVFU2SW5OMFpYQkRiMlJsZkc5c1pXaEpSQ0k3WVRveE9udHpPamM2SWprNU1URjhNVGNpTzJrNk1USTdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRBNklqazVNVEY4TFRGOE1UY2lPMms2TVRJN2ZYTTZPRG9pYzNSbGNFTnZaR1VpTzJFNk1UcDdhVG81T1RFeE8yazZNVFkxTzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjMzNDoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwtMV0gPT4gMTY1CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwxN10gPT4gMTIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8LTF8MTddID0+IDEyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExXSA9PiAxNjUKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6MTE6Ijk5MTEuLTEuMTY1IjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDI3NDEwMTM5Ljk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDI3NjU1MTM5Ljk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOjQwMTM3NjA2LjA4NDIzODk3NjtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwNjMiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDYzIjt9aToyNTthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAzIDExOjE2OjQ3IjtzOjU6ImplbmlzIjtzOjIwOiJQZW1iYXRhbGFuIFRyYW5zYWtzaSI7czoxNDoic3VwcGxpZXJzX25hbWEiO047czoxNDoiY3VzdG9tZXJzX25hbWEiO047czo5OiJvbGVoX25hbWEiO3M6ODoiaG9sZGluZ18iO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6InB1c2F0IjtzOjc6Imlkc19oaXMiO2E6MTp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjQ3MDY1O3M6NToibm9tZXIiO3M6MTE6Ijk5MTEuLTEuMTY2IjtzOjg6ImNvdW50ZXJzIjtzOjI2NDoiWVRvME9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TnpvaU9Ua3hNWHd0TVNJN2FUb3hOalk3ZlhNNk1UVTZJbk4wWlhCRGIyUmxmRzlzWldoSlJDSTdZVG94T250ek9qYzZJams1TVRGOE1UY2lPMms2TVRNN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEE2SWprNU1URjhMVEY4TVRjaU8yazZNVE03ZlhNNk9Eb2ljM1JsY0VOdlpHVWlPMkU2TVRwN2FUbzVPVEV4TzJrNk1UWTJPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6MzM0OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xXSA9PiAxNjYKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfDE3XSA9PiAxMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMXwtMXwxN10gPT4gMTMKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTFdID0+IDE2NgogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czoxMToiOTkxMS4tMS4xNjYiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0Mjc2NTUxMzkuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0Mjc2NzgzMzkuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6NDAxNjA4MDYuMDg0MjM4OTc2O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA2NSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNjUiO31pOjI2O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDMgMTE6MTg6NDgiO3M6NToiamVuaXMiO3M6MjA6IlBlbWJhdGFsYW4gVHJhbnNha3NpIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7TjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo4OiJob2xkaW5nXyI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToxOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDcwNjc7czo1OiJub21lciI7czoxMToiOTkxMS4tMS4xNjciO3M6ODoiY291bnRlcnMiO3M6MjY0OiJZVG8wT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pT1RreE1Yd3RNU0k3YVRveE5qYzdmWE02TVRVNkluTjBaWEJEYjJSbGZHOXNaV2hKUkNJN1lUb3hPbnR6T2pjNklqazVNVEY4TVRjaU8yazZNVFE3ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1UQTZJams1TVRGOExURjhNVGNpTzJrNk1UUTdmWE02T0RvaWMzUmxjRU52WkdVaU8yRTZNVHA3YVRvNU9URXhPMms2TVRZM08zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czozMzQ6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8LTFdID0+IDE2NwogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzk5MTF8MTddID0+IDE0CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs5OTExfC0xfDE3XSA9PiAxNAogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbOTkxMV0gPT4gMTY3CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjExOiI5OTExLi0xLjE2NyI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQyNzY3ODMzOS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODQzMDE1MzMzOS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDo0MjYzNTgwNi4wODQyMzg5NzY7czoxMjoidHJhbnNha3NpX2lkIjtzOjU6IjQ3MDY3IjtzOjE0OiJyZXZpZXdfZGV0YWlscyI7czo1OiI0NzA2NyI7fWk6Mjc7YToxNDp7czo1OiJkdGltZSI7czoxOToiMjAyMC0wOS0wMyAxNjoyMzo0MSI7czo1OiJqZW5pcyI7czoxOToiT3RvcmlzYXNpIFVhbmcgTXVrYSI7czoxNDoic3VwcGxpZXJzX25hbWEiO3M6MTM6InRlYW0gRGVsaXZlcnkiO3M6MTQ6ImN1c3RvbWVyc19uYW1hIjtOO3M6OToib2xlaF9uYW1hIjtzOjg6ImhvbGRpbmdfIjtzOjExOiJjYWJhbmdfbmFtYSI7czo1OiJwdXNhdCI7czo3OiJpZHNfaGlzIjthOjI6e2k6MTthOjU6e3M6NDoic3RlcCI7aToxO3M6NDoidHJJRCI7aTozOTM3NDtzOjU6Im5vbWVyIjtzOjEwOiI0NjRyLi0xLjM3IjtzOjg6ImNvdW50ZXJzIjtzOjM0MDoiWVRvMU9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TnpvaU5EWTBjbnd0TVNJN2FUb3pOenQ5Y3pveE5Ub2ljM1JsY0VOdlpHVjhiMnhsYUVsRUlqdGhPakU2ZTNNNk9Eb2lORFkwY253ek1UWWlPMms2TWpRN2ZYTTZNak02SW5OMFpYQkRiMlJsZkhCc1lXTmxTVVI4YjJ4bGFFbEVJanRoT2pFNmUzTTZNVEU2SWpRMk5ISjhMVEY4TXpFMklqdHBPakkwTzMxek9qRTVPaUp6ZEdWd1EyOWtaWHh6ZFhCd2JHbGxja2xFSWp0aE9qRTZlM002T0RvaU5EWTBjbncxTmpBaU8yazZNanQ5Y3pvNE9pSnpkR1Z3UTI5a1pTSTdZVG94T250ek9qUTZJalEyTkhJaU8yazZNemM3ZlgwPSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjQxODoiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwtMV0gPT4gMzcKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfDMxNl0gPT4gMjQKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8LTF8MzE2XSA9PiAyNAogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfDU2MF0gPT4gMgogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cl0gPT4gMzcKICAgICAgICApCgopCiI7fWk6MjthOjU6e3M6NDoic3RlcCI7czoxOiIyIjtzOjQ6InRySUQiO2k6NDcwNjk7czo1OiJub21lciI7czo5OiI0NjQuLTEuNDIiO3M6ODoiY291bnRlcnMiO3M6MzIwOiJZVG8xT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOam9pTkRZMGZDMHhJanRwT2pReU8zMXpPakUxT2lKemRHVndRMjlrWlh4dmJHVm9TVVFpTzJFNk1UcDdjem8yT2lJME5qUjhNVGNpTzJrNk1UdDljem95TXpvaWMzUmxjRU52WkdWOGNHeGhZMlZKUkh4dmJHVm9TVVFpTzJFNk1UcDdjem81T2lJME5qUjhMVEY4TVRjaU8yazZNVHQ5Y3pveE9Ub2ljM1JsY0VOdlpHVjhjM1Z3Y0d4cFpYSkpSQ0k3WVRveE9udHpPamM2SWpRMk5IdzFOakFpTzJrNk1UdDljem80T2lKemRHVndRMjlrWlNJN1lUb3hPbnRwT2pRMk5EdHBPalF5TzMxOSI7czoxNToiY291bnRlcnNfaW50ZXh0IjtzOjQwOToiQXJyYXkKKAogICAgW3N0ZXBDb2RlfHBsYWNlSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fC0xXSA9PiA0MgogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHwxN10gPT4gMQogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fC0xfDE3XSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHw1NjBdID0+IDEKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NF0gPT4gNDIKICAgICAgICApCgopCiI7fX1zOjEyOiJ0cmFuc2Frc2lfbm8iO3M6OToiNDY0Li0xLjQyIjtzOjEwOiJkZWJldF9hd2FsIjtzOjIxOiI4NDMwMTUzMzM5Ljk0MzkwMDAwMDAiO3M6MTE6ImRlYmV0X2FraGlyIjtzOjIxOiI4NDI4NjUzMzM5Ljk0MzkwMDAwMDAiO3M6MTg6InNhbGRvX3F0eV9iZXJqYWxhbiI7aTowO3M6MTQ6InNhbGRvX2JlcmphbGFuIjtkOjQxMTM1ODA2LjA4NDIzODk3NjtzOjEyOiJ0cmFuc2Frc2lfaWQiO3M6NToiNDcwNjkiO3M6MTQ6InJldmlld19kZXRhaWxzIjtzOjU6IjQ3MDY5Ijt9aToyODthOjE0OntzOjU6ImR0aW1lIjtzOjE5OiIyMDIwLTA5LTAzIDE2OjU0OjM2IjtzOjU6ImplbmlzIjtzOjE5OiJPdG9yaXNhc2kgVWFuZyBNdWthIjtzOjE0OiJzdXBwbGllcnNfbmFtYSI7czoxMzoidGVhbSBEZWxpdmVyeSI7czoxNDoiY3VzdG9tZXJzX25hbWEiO047czo5OiJvbGVoX25hbWEiO3M6ODoiaG9sZGluZ18iO3M6MTE6ImNhYmFuZ19uYW1hIjtzOjU6InB1c2F0IjtzOjc6Imlkc19oaXMiO2E6Mjp7aToxO2E6NTp7czo0OiJzdGVwIjtpOjE7czo0OiJ0cklEIjtpOjI5NjE1O3M6NToibm9tZXIiO3M6MTA6IjQ2NHIuLTEuMTkiO3M6ODoiY291bnRlcnMiO3M6MzQwOiJZVG8xT250ek9qRTJPaUp6ZEdWd1EyOWtaWHh3YkdGalpVbEVJanRoT2pFNmUzTTZOem9pTkRZMGNud3RNU0k3YVRveE9UdDljem94TlRvaWMzUmxjRU52WkdWOGIyeGxhRWxFSWp0aE9qRTZlM002T0RvaU5EWTBjbnd6TVRZaU8yazZNVE03ZlhNNk1qTTZJbk4wWlhCRGIyUmxmSEJzWVdObFNVUjhiMnhsYUVsRUlqdGhPakU2ZTNNNk1URTZJalEyTkhKOExURjhNekUySWp0cE9qRXpPMzF6T2pFNU9pSnpkR1Z3UTI5a1pYeHpkWEJ3YkdsbGNrbEVJanRoT2pFNmUzTTZPRG9pTkRZMGNudzFOakFpTzJrNk1UdDljem80T2lKemRHVndRMjlrWlNJN1lUb3hPbnR6T2pRNklqUTJOSElpTzJrNk1UazdmWDA9IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NDE4OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfC0xXSA9PiAxOQogICAgICAgICkKCiAgICBbc3RlcENvZGV8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8MzE2XSA9PiAxMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8cGxhY2VJRHxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwtMXwzMTZdID0+IDEzCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxzdXBwbGllcklEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8NTYwXSA9PiAxCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyXSA9PiAxOQogICAgICAgICkKCikKIjt9aToyO2E6NTp7czo0OiJzdGVwIjtzOjE6IjIiO3M6NDoidHJJRCI7aTo0NzA3MTtzOjU6Im5vbWVyIjtzOjk6IjQ2NC4tMS40MyI7czo4OiJjb3VudGVycyI7czozMjA6IllUbzFPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk5qb2lORFkwZkMweElqdHBPalF6TzMxek9qRTFPaUp6ZEdWd1EyOWtaWHh2YkdWb1NVUWlPMkU2TVRwN2N6bzJPaUkwTmpSOE1UY2lPMms2TWp0OWN6b3lNem9pYzNSbGNFTnZaR1Y4Y0d4aFkyVkpSSHh2YkdWb1NVUWlPMkU2TVRwN2N6bzVPaUkwTmpSOExURjhNVGNpTzJrNk1qdDljem94T1RvaWMzUmxjRU52WkdWOGMzVndjR3hwWlhKSlJDSTdZVG94T250ek9qYzZJalEyTkh3MU5qQWlPMms2TWp0OWN6bzRPaUp6ZEdWd1EyOWtaU0k3WVRveE9udHBPalEyTkR0cE9qUXpPMzE5IjtzOjE1OiJjb3VudGVyc19pbnRleHQiO3M6NDA5OiJBcnJheQooCiAgICBbc3RlcENvZGV8cGxhY2VJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8LTFdID0+IDQzCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fDE3XSA9PiAyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8LTF8MTddID0+IDIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0fDU2MF0gPT4gMgogICAgICAgICkKCiAgICBbc3RlcENvZGVdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0XSA9PiA0MwogICAgICAgICkKCikKIjt9fXM6MTI6InRyYW5zYWtzaV9ubyI7czo5OiI0NjQuLTEuNDMiO3M6MTA6ImRlYmV0X2F3YWwiO3M6MjE6Ijg0Mjg2NTMzMzkuOTQzOTAwMDAwMCI7czoxMToiZGViZXRfYWtoaXIiO3M6MjE6Ijg0MjY2NTMzMzkuOTQzOTAwMDAwMCI7czoxODoic2FsZG9fcXR5X2JlcmphbGFuIjtpOjA7czoxNDoic2FsZG9fYmVyamFsYW4iO2Q6MzkxMzU4MDYuMDg0MjM4OTc2O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA3MSI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNzEiO31pOjI5O2E6MTQ6e3M6NToiZHRpbWUiO3M6MTk6IjIwMjAtMDktMDMgMTg6MDE6MjEiO3M6NToiamVuaXMiO3M6MTk6Ik90b3Jpc2FzaSBVYW5nIE11a2EiO3M6MTQ6InN1cHBsaWVyc19uYW1hIjtzOjIyOiJDUlVaRSBJTlRFUklPUiBERVNJR04gIjtzOjE0OiJjdXN0b21lcnNfbmFtYSI7TjtzOjk6Im9sZWhfbmFtYSI7czo4OiJob2xkaW5nXyI7czoxMToiY2FiYW5nX25hbWEiO3M6NToicHVzYXQiO3M6NzoiaWRzX2hpcyI7YToyOntpOjE7YTo1OntzOjQ6InN0ZXAiO2k6MTtzOjQ6InRySUQiO2k6NDUxMzg7czo1OiJub21lciI7czoxMDoiNDY0ci4tMS40OSI7czo4OiJjb3VudGVycyI7czozNDA6IllUbzFPbnR6T2pFMk9pSnpkR1Z3UTI5a1pYeHdiR0ZqWlVsRUlqdGhPakU2ZTNNNk56b2lORFkwY253dE1TSTdhVG8wT1R0OWN6b3hOVG9pYzNSbGNFTnZaR1Y4YjJ4bGFFbEVJanRoT2pFNmUzTTZPRG9pTkRZMGNud3pNVFlpTzJrNk16STdmWE02TWpNNkluTjBaWEJEYjJSbGZIQnNZV05sU1VSOGIyeGxhRWxFSWp0aE9qRTZlM002TVRFNklqUTJOSEo4TFRGOE16RTJJanRwT2pNeU8zMXpPakU1T2lKemRHVndRMjlrWlh4emRYQndiR2xsY2tsRUlqdGhPakU2ZTNNNk9Eb2lORFkwY253eE56Y2lPMms2TkR0OWN6bzRPaUp6ZEdWd1EyOWtaU0k3WVRveE9udHpPalE2SWpRMk5ISWlPMms2TkRrN2ZYMD0iO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo0MTg6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJ8LTFdID0+IDQ5CiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxvbGVoSURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwzMTZdID0+IDMyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZXxwbGFjZUlEfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRyfC0xfDMxNl0gPT4gMzIKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHN1cHBsaWVySURdID0+IEFycmF5CiAgICAgICAgKAogICAgICAgICAgICBbNDY0cnwxNzddID0+IDQKICAgICAgICApCgogICAgW3N0ZXBDb2RlXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHJdID0+IDQ5CiAgICAgICAgKQoKKQoiO31pOjI7YTo1OntzOjQ6InN0ZXAiO3M6MToiMiI7czo0OiJ0cklEIjtpOjQ3MDczO3M6NToibm9tZXIiO3M6OToiNDY0Li0xLjQ0IjtzOjg6ImNvdW50ZXJzIjtzOjMyMDoiWVRvMU9udHpPakUyT2lKemRHVndRMjlrWlh4d2JHRmpaVWxFSWp0aE9qRTZlM002TmpvaU5EWTBmQzB4SWp0cE9qUTBPMzF6T2pFMU9pSnpkR1Z3UTI5a1pYeHZiR1ZvU1VRaU8yRTZNVHA3Y3pvMk9pSTBOalI4TVRjaU8yazZNenQ5Y3pveU16b2ljM1JsY0VOdlpHVjhjR3hoWTJWSlJIeHZiR1ZvU1VRaU8yRTZNVHA3Y3pvNU9pSTBOalI4TFRGOE1UY2lPMms2TXp0OWN6b3hPVG9pYzNSbGNFTnZaR1Y4YzNWd2NHeHBaWEpKUkNJN1lUb3hPbnR6T2pjNklqUTJOSHd4TnpjaU8yazZNanQ5Y3pvNE9pSnpkR1Z3UTI5a1pTSTdZVG94T250cE9qUTJORHRwT2pRME8zMTkiO3M6MTU6ImNvdW50ZXJzX2ludGV4dCI7czo0MDk6IkFycmF5CigKICAgIFtzdGVwQ29kZXxwbGFjZUlEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHwtMV0gPT4gNDQKICAgICAgICApCgogICAgW3N0ZXBDb2RlfG9sZWhJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8MTddID0+IDMKICAgICAgICApCgogICAgW3N0ZXBDb2RlfHBsYWNlSUR8b2xlaElEXSA9PiBBcnJheQogICAgICAgICgKICAgICAgICAgICAgWzQ2NHwtMXwxN10gPT4gMwogICAgICAgICkKCiAgICBbc3RlcENvZGV8c3VwcGxpZXJJRF0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjR8MTc3XSA9PiAyCiAgICAgICAgKQoKICAgIFtzdGVwQ29kZV0gPT4gQXJyYXkKICAgICAgICAoCiAgICAgICAgICAgIFs0NjRdID0+IDQ0CiAgICAgICAgKQoKKQoiO319czoxMjoidHJhbnNha3NpX25vIjtzOjk6IjQ2NC4tMS40NCI7czoxMDoiZGViZXRfYXdhbCI7czoyMToiODQyNjY1MzMzOS45NDM5MDAwMDAwIjtzOjExOiJkZWJldF9ha2hpciI7czoyMToiODM4NjY1MzMzOS45NDM5MDAwMDAwIjtzOjE4OiJzYWxkb19xdHlfYmVyamFsYW4iO2k6MDtzOjE0OiJzYWxkb19iZXJqYWxhbiI7ZDotODY0MTkzLjkxNTc2MTAyMzc2O3M6MTI6InRyYW5zYWtzaV9pZCI7czo1OiI0NzA3MyI7czoxNDoicmV2aWV3X2RldGFpbHMiO3M6NToiNDcwNzMiO319"
        // );
        $cabang_id = isset($_GET['cb']) ? $_GET['cb'] : CB_ID_PUSAT;
        $jenisAliases = arrCodeAliasing($cabang_id);
        // $kiriman_ju = $this->uri->segment(3);
        $kiriman_tr = $this->uri->segment(3);
        // $dataSrcs_0 = $datas = blobDecode($kiriman_ju)->result();
        // $transaksi_ids = blobDecode($kiriman_tr);
        $this->load->model("Coms/ComJurnal");
        $this->load->model("MdlTransaksi");
        $ju = new ComJurnal();
        $tr = new MdlTransaksi();
        $begin_date = $vd_start = isset($_GET['d_start']) ? $_GET['d_start'] : "";
        $end_date = $vd_stop = isset($_GET['d_stop']) ? $_GET['d_stop'] : dtimeNow("Y-m-d");
        $jenis_jurnal = $vfx = isset($_GET['fx']) && ($_GET['fx'] != 'semua') ? $_GET['fx'] : "";
        // $begin_date = "2020-04-25";
        // $end_date = dtimeNow("Y-m-d");
        $cabang_id = my_cabang_id();

        if (isset($_GET['fx']) && ($_GET['fx'] != 'semua')) {
            $condites = array(
                "jenis" => $jenis_jurnal,
                "cabang_id" => $cabang_id,
            );
        }
        else {
            $condites = array(
                "jenis !=" => "",
                "cabang_id" => $cabang_id,
            );
        }

        if (isset($_GET['d_stop'])) {
            $this->db->where(
                array(
                    "DATE(dtime)>=" => $begin_date,
                    "DATE(dtime)<=" => $end_date,
                )
            );
        }
        $this->db->order_by("id", "DESC");
        $juTmps = $ju->lookupByCondition($condites);
        $juJml = sizeof($juTmps->result());
        $dataSrcs_0 = $juTmps->result();

        /* --------------------------------------
        * pengelompokan jurnal by jenis
        * ------------------------------------*/
        $regDatas = array(
            "description",
            "description_additional",
            "description_main_followup",
            "eFaktur",
        );
        $rekeningGetDatas = array(
            "hutang dagang" => "description_main_followup",
            "ppn in realisasi" => "eFaktur",
        );
        $jurnals = array();
        $djurnals = array();
        $kjurnals = array();
        $mainDatas = array();
        $addDatas = array();
        $trIDs = array();
        $regIDs = array();
        if (sizeof($juTmps->result()) > 0) {
            foreach ($juTmps->result() as $item) {
                // $jurnals[$item->jenis][$item->transaksi_id][] = $item;
                $values['debet'] = $item->debet;
                $values['kredit'] = $item->kredit;
                $transaksi_id = $item->transaksi_id;
                $urut = $item->urut;
                $cabangID = $item->cabang_id;
                $rekening = $item->rekening;

                $jurnals[$item->jenis][$transaksi_id][$urut][$item->rekening] = $values;
                if ($item->debet > 0) {
                    $djurnals[$item->jenis][$transaksi_id][$urut][$item->rekening]['debet'] = $item->debet;
                    //-----------------------
                    $addDatas[$item->jenis][$transaksi_id][$item->rekening] = array(
                        "link" => base_url() . "Ledger/viewMoveDetails_1/Rekening/$rekening/?o=$cabangID&date1=$begin_date&date2=$end_date&trID=$transaksi_id",

                    );
                }

                if ($item->kredit > 0) {
                    $kjurnals[$item->jenis][$transaksi_id][$urut][$item->rekening]['kredit'] = $item->kredit;
                    //-----------------------
                    $addDatas[$item->jenis][$transaksi_id][$item->rekening] = array(
                        "link" => base_url() . "Ledger/viewMoveDetails_1/Rekening/$rekening/?o=$cabangID&date1=$begin_date&date2=$end_date&trID=$transaksi_id",

                    );
                }

                $mainDatas[$transaksi_id] = $item;
            }
        }
        $transaksi_ids = array_keys($mainDatas);

        $trDatas = array();
        if (sizeof($transaksi_ids) > 0) {

            $selectedFields = array(
                "id",
                "dtime",
                "nomer",
                "oleh_id",
                "oleh_nama",
                "customers_id",
                "customers_nama",
                "suppliers_id",
                "suppliers_nama",
                "cabang_id",
                "cabang_nama",
                "gudang_id",
                "gudang_nama",
                "counters",
                "ids_his",
                "jenis",
                "indexing_registry",
            );
            $tr->setFilters(array());
            $this->db->select($selectedFields);
            $this->db->where_in("id", $transaksi_ids);
            $trTmps_0 = $tr->lookupAll();
            $trTmps = $trTmps_0->result();
            // showLast_query("orange");
            $trDatas = array();
            $transaksiID = array();
            if (sizeof($trTmps) > 0) {
                foreach ($trTmps as $trTmp) {
                    $transaksiID[$trTmp->id] = $trTmp->id;
                    $trDatas[$trTmp->id] = $trTmp;
                    if ($trTmp->indexing_registry != NULL) {
                        $index_regDecode = blobDecode($trTmp->indexing_registry);
                        $regIDs[$index_regDecode['main']] = $index_regDecode['main'];
                    }
                }
            }

            // membaca registry MAIN, sesuai transaksiID
            $tr = new MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id in ('" . implode("','", $transaksiID) . "')");
            $tr->setJointSelectFields("main, transaksi_id");
            $regTmp = $tr->lookupDataRegistries()->result();

            if (sizeof($regTmp) > 0) {
                foreach ($regTmp as $regSpec) {
                    foreach ($regSpec as $key_reg => $val_reg) {
                        if ($key_reg != "transaksi_id") {
                            $regValues = blobDecode($val_reg);
                            foreach ($regDatas as $kolom) {
                                $trRegDatas[$regSpec->transaksi_id][$kolom] = isset($regValues[$kolom]) ? $regValues[$kolom] : "";
                            }
                        }
                    }
                }
            }

        }

        $namaFile = "jurnal-" . dtimeNow();
        $souceFields = array(
            // "id"            => array(
            //     "label" => "pID",
            //     "type"  => "integer",
            // ),
            "jenis_nama" => array(
                "label" => "jenis",
                "type" => "string",
            ),
            "dtime" => array(
                "label" => "tanggal",
                "type" => "string",
            ),
            "cabang_nama" => array(
                "label" => "cabang",
                "type" => "string",
            ),
            "oleh_nama" => array(
                "label" => "oleh",
                "type" => "string",
            ),
            "transaksi_no_f" => array(
                "label" => "nomer",
                "type" => "string",
            ),
            "customers_nama" => array(
                "label" => "konsumen",
                "type" => "string",
            ),
            "suppliers_nama" => array(
                "label" => "vendor",
                "type" => "string",
            ),
            "rekening" => array(
                "label" => "rekening",
                "type" => "string",
            ),
            "referensi" => array(
                "label" => "vendor's number referral",
                "type" => "string",
            ),
            "debet" => array(
                "label" => "debet",
                "type" => "integer",
            ),
            "kredit" => array(
                "label" => "kredit",
                "type" => "integer",
            ),
            // "keterangan"     => array(
            //     "label" => "catatan",
            //     "type"  => "string",
            // ),
        );


        $dataSrcs = array();
        // $idP_3 = 0;
        foreach ($dataSrcs_0 as $dataSrc) {


            $idP_3 = $dataSrc->transaksi_id;
            $rekening_3 = $dataSrc->rekening;
            $jenis_3 = $dataSrc->jenis;
            $transaksi_no_3 = $dataSrc->transaksi_no;
            // cekHitam($idP_3);

            if (array_key_exists($dataSrc->rekening, $rekeningGetDatas)) {
                $kolom = $rekeningGetDatas[$dataSrc->rekening];
                $add_data = isset($trRegDatas[$dataSrc->transaksi_id][$kolom]) ? $trRegDatas[$dataSrc->transaksi_id][$kolom] : "";
                $dataSrcPlus['referensi'] = $add_data;
            }
            else {
                $dataSrcPlus['referensi'] = "-";
            }


            $dataSrcPlus['cabang_nama'] = $trDatas[$idP_3]->cabang_nama;
            $dataSrcPlus['oleh_nama'] = $trDatas[$idP_3]->oleh_nama;
            $dataSrcPlus['customers_nama'] = $trDatas[$idP_3]->customers_nama;
            $dataSrcPlus['suppliers_nama'] = $trDatas[$idP_3]->suppliers_nama;
            $dataSrcPlus['jenis_nama'] = isset($jenisAliases[$jenis_3]) ? $jenisAliases[$jenis_3] : $jenis_3;
            $dataSrcPlus['transaksi_no_f'] = formatField('nomer_download', $transaksi_no_3);

            $dataSrc_plus = (array)$dataSrc + $dataSrcPlus;

            // $dataSrcs[$idP_3] = (object)$dataSrc;
            $dataSrcs[$idP_3][$rekening_3] = (object)$dataSrc_plus;
        }
        //arrPrintPink($dataSrcs);
        //mati_disini();


        $this->file = $this->uri->segment(2);

        // $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();

        $headers = $souceFields;


        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();

        // arrPrintWebs($dataSrcs);
        foreach ($dataSrcs as $pId => $itemffs) {
            foreach ($itemffs as $rekeningnya => $itemff) {

                foreach ($souceFields as $kolom => $stokSpeks) {
                    $no++;
                    // if (isset($stokSpeks["replacer"])) {
                    //     $value = in_array($pId, $produkPunyaImages) ? "yes" : "no image";
                    //     $dataSpec[$kolom] = $value;
                    // }
                    // else {
                    $dataSpec[$kolom] = $itemff->$kolom;
                    // }
                }

                $datas[] = (object)$dataSpec;
            }
        }
        // endregion pairing data

        // arrPrintWebs($headers);
        //         arrPrint($datas);
        //        matiHere(__LINE__ . " $namaFile");

        $ex->setTitleFile($namaFile);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        // return $ex->writer();
        return $ex->writer();
    }

    public function neraca()
    {
        // cekHere();
        // arrPrint($_POST);
        $this->load->model("Mdls/" . "MdlNeraca");
        $this->load->model("Mdls/" . "MdlFinanceConfig");
        $ner = new MdlNeraca();
        $previousMonth = previousMonth();
        $periode = "bulanan";

        $defaultDate = isset($_POST['date']) ? $_POST['date'] : $previousMonth;
        $defaultDate_ex = explode("-", $defaultDate);
        $tahun = $defaultDate_ex[0];
        $bulan = $defaultDate_ex[1];

        $fc = New MdlFinanceConfig();
        $fc->addFilter("periode='$periode'");
        $fc->addFilter("bln='$bulan'");
        $fc->addFilter("thn='$tahun'");
        $fcTmp = $fc->lookupAll()->result();
        $fcResult = array();
        if (sizeof($fcTmp) > 0) {
            foreach ($fcTmp as $fcSpec) {
                $fcResult[$fcSpec->param] = strlen($fcSpec->values) > 5 ? blobDecode($fcSpec->values) : NULL;
            }
        }


        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $accountException = $this->config->item("accountRekOppositeExceptions") != null ? $this->config->item("accountRekOppositeExceptions") : array();
        $accountCatException = $this->config->item("accountCatOppositeExceptions") != null ? $this->config->item("accountCatOppositeExceptions") : array();
        $accountRekeningSort = (sizeof($fcResult) > 0 && isset($fcResult['accountRekeningSort']) && ($fcResult['accountRekeningSort'] != NULL)) ? $fcResult['accountRekeningSort'] : $this->config->item("accountRekeningSort");


        $ner->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $ner->addFilter("periode='$periode'");
        $tmp = $ner->fetchBalances($defaultDate);
        // showLast_query("lime");
        // arrPrint($tmp);
        $dates = $ner->fetchDates();
        // matiHere();

        $oldDate = "";
        $last_date = $defaultDate;

        $categories = array();
        $rekenings = array();
        $rekeningsName = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $defPos = detectRekDefaultPosition($row->rekening);
                if (strlen($row->kategori) > 1) {
                    if ((round($row->debet, 2) > 0) || (round($row->kredit, 2) > 0)) {

                        if (!in_array($row->kategori, $categories)) {
                            $categories[] = $row->kategori;
                        }
                        if (!isset($rekenings[$row->kategori])) {
                            $rekenings[$row->kategori] = array();
                        }
                        if (in_array($row->rekening, $accountException)) {
                            $tmpCol = array(
                                "rek_id" => "",
                                //                                "rekening" => isset($accountAlias[$row->rekening]) ? $accountAlias[$row->rekening] : $row->rekening,
                                "rekening" => $row->rekening,
                                "debet" => ($row->kredit * -1),
                                "kredit" => ($row->debet * -1),
                                "link" => "",
                            );

                        }
                        else {
                            switch ($defPos) {
                                case "debet":
                                    if ($row->kredit > 0) {
                                        $debet = $row->kredit * -1;
                                        $kredit = 0;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                case "kredit":
                                    if ($row->debet > 0) {
                                        $debet = 0;
                                        $kredit = $row->debet * -1;
                                    }
                                    else {
                                        $debet = $row->debet;
                                        $kredit = $row->kredit;
                                    }
                                    break;
                                default:
                                    $debet = $row->debet;
                                    $kredit = $row->kredit;
                                    break;
                            }
                            $tmpCol = array(
                                //                                "rek_id" => isset($row->rek_id) ? $row->rek_id : "",
                                "rek_id" => "",
                                "rekening" => $row->rekening,
                                "debet" => $debet,
                                "kredit" => $kredit,
                                "link" => "",
                            );

                        }
                        if (isset($accountChilds[$row->rekening])) {
                            $tmpCol['link'] .= "<a href='" . base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row->rekening] . "/" . $row->rekening . "/" . $row->periode . "?date=$oldDate'><span class='fa fa-clone'></span></a>";
                        }
                        $tmpCol['link'] .= "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoves_l1/Rekening/" . $row->rekening . "'><span class='glyphicon glyphicon-time'></span></a></span>";

                        if (sizeof($accountCatException) > 0) {
                            foreach ($accountCatException as $cat => $c_rekName) {
                                if (in_array($row->rekening, $c_rekName)) {
                                    $rekenings[$cat][] = $tmpCol;
                                    $rekeningsName[$cat][$row->rekening] = $row->rekening;
                                }
                                else {
                                    $rekenings[$row->kategori][] = $tmpCol;
                                    $rekeningsName[$row->kategori][$row->rekening] = $row->rekening;
                                }
                            }
                        }
                        else {
                            $rekenings[$row->kategori][] = $tmpCol;
                        }
                    }
                }

                $last_date = "$row->thn-$row->bln";
            }
        }


        $arrCat = array("aktiva", "hutang", "modal", "lain-lain-kr");
        $arrCatView = array("aktiva", "hutang", "modal");

        $rekeningsNew = array();
        foreach ($rekenings as $cat => $c_Rekdata) {
            if (sizeof($c_Rekdata) == 0) {
                unset($rekenings[$cat]);
            }
            //            arrPrint($c_Rekdata);
            if (sizeof($c_Rekdata) > 0) {
                foreach ($c_Rekdata as $ii => $arrData) {
                    foreach ($arrData as $key => $val) {
                        if (is_numeric($val)) {
                            if (!isset($rekeningsNew[$cat][$arrData['rekening']][$key])) {
                                $rekeningsNew[$cat][$arrData['rekening']][$key] = 0;
                            }
                            $rekeningsNew[$cat][$arrData['rekening']][$key] += $val;
                        }
                        else {
                            $rekeningsNew[$cat][$arrData['rekening']][$key] = $val;
                        }
                    }
                }
            }
        }

        foreach ($rekeningsNew as $rekeningItems_0) {
            foreach ($rekeningItems_0 as $rekeningKey => $rekeningValues) {
                $rekeningItems[$rekeningKey] = $rekeningValues;
            }
        }
        // arrPrintWebs($rekeningItems);

        $rekeningsNameNew = array();
        foreach ($arrCatView as $cat) {
            foreach ($accountRekeningSort[$cat] as $rekName) {
                if (isset($rekeningsName[$cat])) {
                    if (in_array($rekName, $rekeningsName[$cat])) {
                        $rekeningsNameNew[$cat][$rekName] = $rekName;
                    }
                }
            }
        }

        // arrPrint($rekeningsNameNew);
        // matiHere();
        $dataFifo = $rekeningsNameNew;

        $this->file = $this->uri->segment(2) . " " . $defaultDate;

        // $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();

        $headers = array(
            // "no"          => array(
            //     "label" => "No",
            //     "type"  => "integer",
            // ),
            // "pid" => array(
            //     "label" => "pID",
            //     "type"  => "integer",
            // ),
            //
            // "kode"    => array(
            //     "label" => "item no",
            //     "type"  => "string",
            // ),
            // "no_part" => array(
            //     "label" => "part no",
            //     "type"  => "string",
            // ),
            // "nama"    => array(
            //     "label" => "description",
            //     "type"  => "string",
            // ),
            //
            // "hpp"   => array(
            //     "label" => "HPP",
            //     "type"  => "integer",
            // ),

            "rekening" => array(
                "label" => "rekening",
                "type" => "string",
            ),
            "debet" => array(
                "label" => "debet",
                "type" => "integer",
            ),
            "kredit" => array(
                "label" => "kredit",
                "type" => "integer",
            ),

            // "billingDetails__propinsi"        => array(
            //     "label" => "Prop",
            //     "type"  => "string",
            // ),
            //     "jml"                        => array(
            //         "label" => "Qty",
            //         "type"  => "integer",
            //     ),
            //     "sub_harga"                  => array(
            //         "label" => "Price",
            //         "type"  => "integer",
            //     ),
            //     "sub_nett1"                  => array(
            //         "label" => "DPP",
            //         "type"  => "integer",
            //     ),
            //     "sub_ppn"                    => array(
            //         "label" => "PPN",
            //         "type"  => "integer",
            //     ),
            //     "sub_nett2"                  => array(
            //         "label" => "TOTAL",
            //         "type"  => "integer",
            //     ),
        );

        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();
        // foreach ($dataSrcs as $data) {
        //     $no++;
        //     // cekHijau("$yNames");
        //     // $yNames_f = html_entity_decode($yNames);
        //     foreach ($headers as $header => $aliasing) {
        //
        //         $dataSpec[$header] = key_exists($header, $data) ? $data->$header : $$header;
        //     }
        //
        //
        //     $datas[] = (object)$dataSpec;
        // }
        // endregion pairing data
        // arrPrint($dataSrcs);
        // arrPrint($rekeningsNew);
        foreach ($dataFifo as $pId => $itemffs) {
            foreach ($itemffs as $hpp => $stok) {
                $no++;

                $dataSpec['rekening'] = $stok;
                $dataSpec['debet'] = $rekeningItems[$hpp]['debet'];
                $dataSpec['kredit'] = $rekeningItems[$hpp]['kredit'];;

                $datas[] = (object)$dataSpec;
            }
        }

        // arrPrintWebs($datas);
        // matiHere(__LINE__);
        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();

    } // neraca

    public function rugiLaba()
    {
        // arrPrint($_POST);
        $kiriman = $_POST;
        $rekenings = blobDecode($kiriman['rekening']);
        $rekeningAliasings = blobDecode($kiriman['alias']);
        // $rekeningNilais = $kiriman['nilai'];
        $kiriman_nilai_e = str_ireplace(" ", "+", $kiriman['nilai']);
        $rekeningNilais = blobDecode($kiriman_nilai_e);
        $date = blobDecode($kiriman['date']);

        // arrPrint($rekenings);
        // arrPrint($rekeningAliasings);
        // arrPrint($rekeningNilais);
        foreach ($rekeningNilais as $rekeningItems_0) {
            foreach ($rekeningItems_0 as $rekKey => $rekNilai) {
                $rekeningItems[$rekKey] = $rekNilai;
            }
        }


        $dataFifo = $rekenings;

        $previousMonth = previousMonth();
        $periode = "bulanan";

        $defaultDate = isset($_POST['date']) ? $_POST['date'] : $previousMonth;

        $this->file = $this->uri->segment(2) . " " . $defaultDate;

        // $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();

        $headers = array(
            "rekening" => array(
                "label" => "rekening",
                "type" => "string",
            ),
            "balance" => array(
                "label" => "balance",
                "type" => "integer",
            ),
        );

        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();
        // foreach ($dataSrcs as $data) {
        //     $no++;
        //     // cekHijau("$yNames");
        //     // $yNames_f = html_entity_decode($yNames);
        //     foreach ($headers as $header => $aliasing) {
        //
        //         $dataSpec[$header] = key_exists($header, $data) ? $data->$header : $$header;
        //     }
        //
        //
        //     $datas[] = (object)$dataSpec;
        // }
        // endregion pairing data
        // arrPrint($dataSrcs);
        // arrPrint($rekeningsNew);
        foreach ($dataFifo as $pId => $itemffs) {
            foreach ($itemffs as $hpp => $stok) {
                $no++;
                $rekeningNama = isset($rekeningAliasings[$stok]) ? $rekeningAliasings[$stok] : $hpp;
                $dataSpec['rekening'] = $rekeningNama;
                $dataSpec['balance'] = $rekeningItems[$hpp]['values'];

                $datas[] = (object)$dataSpec;
            }
        }

        // arrPrintWebs($datas);
        // matiHere(__LINE__);
        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();
    }

    public function katalogprodukaktif()
    {
        $params = blobDecode($this->uri->segment(3));
        $mdlName = $params['mdl'];
        $mdlFifo = $params['fifo'];
        $cabangID = $cabangid = $params['cabang_id'];
        // arrPrint($params);
        $mdlHarga = "MdlHargaProduk";

        $this->load->model("Mdls/$mdlName"); // MdlProduk2
        // $this->load->model("Coms/$mdlName");
        $this->load->model("Mdls/$mdlFifo");
        $this->load->model("Mdls/$mdlHarga");
        $md = new $mdlName();
        $ff = new $mdlFifo();
        $ha = new $mdlHarga();

        /* ------------------------------
         * data kunci produk
         * --------------------------*/
        // $md->addFilter("kategori_nama='unit'");
        /* ----------------------------------------------------
         * filter unit dimatikan permintaan everest 23/02/2024
         * update selanjutnya
         *          bisa multy tab masing masing per kategori unit-non unit
         * ----------------------------------------------------*/
        // $this->db->limit(5);
        $this->db->order_by('nama', 'asc');
        $tmps_1 = $md->lookupAll();
        showLast_query("lime");
        $dataSrcs_0 = $tmps_1->result();
        // arrPrint($dataSrcs);
        $dataSrcs = array();
        foreach ($dataSrcs_0 as $dataSrc) {
            $dataSrcs[$dataSrc->id] = $dataSrc;
        }

        if (MGK_LIVE == ipadd()) {
            // matiDisini(__LINE__);
        }
        $where_2 = array(
            "cabang_id" => $cabangid
        );
        $this->db->where($where_2);
        $tmps_2 = $ff->lookupAll()->result();
        // showLast_query("orange");
        // arrPrint($tmps_2);
        foreach ($tmps_2 as $item_2) {

            // if (!isset($dataFifo[$item_2->produk_id])) {
            //     $dataFifo[$item_2->produk_id] = 0;
            // }
            // $dataFifo[$item_2->produk_id] += $item_2->unit;

            // $dataFifo[$item_2->produk_id][] = array(
            //     "unit" => $item_2->unit,
            //     "hpp" => $item_2->hpp,
            // );

            if (!isset($dataFifo[$item_2->produk_id][$item_2->hpp])) {
                $dataFifo[$item_2->produk_id][$item_2->hpp] = 0;
            }
            $dataFifo[$item_2->produk_id][$item_2->hpp] += $item_2->unit;

        }
        // arrPrintWebs($dataFifo);
        //         matiHere();
        $where_30 = array(
            "jenis_value" => "jual"
        );
        $where_3 = $where_2 + $where_30;
        $this->db->where($where_3);
        $hargas_0 = $ha->lookupAll()->result();
        // showLast_query("lime");
        // arrPrint($hargas_0);
        foreach ($hargas_0 as $harga_0) {
            $hargas[$harga_0->produk_id] = $harga_0->nilai;
        }

        // matiHere();
        // locker
        // $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlLockerStockBooking");
        $ls = new MdlLockerStockBooking();
        $st = new MdlLockerStock();
        $sthold = new MdlLockerStock();

        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );
        $inCabId = array();
        //region locker aktive
        $inGid = array("-250", "-210", "-10", "-1", "-260", "-270", "-280", "-290", "-300", "-310");//ini ditembak dulu sambil nyari cara auto get gudang default

        if ($cabangID > 0) {
            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }
        else {
            $inCabId = array("25", "21", "1", "-1", "26", "27", "28", "29", "30", "31");
            $inGid = array("-250", "-210", "-10", "-1", "-260", "-270", "-280", "-290", "-300", "-310");//ini ditembak dulu sambil nyari cara auto get gudang default
            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            //            $st->addFilter("jenis in ('produk', 'produk rakitan')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }
        $tmp_2s = $st->lookupAll()->result();
        // showLast_query("kuning");
        // cekKuning(sizeof($tmp_2s));
        // arrPrintPink($tmp_2s);
        $stocks = array();
        foreach ($tmp_2s as $temps) {
            //            arrprint($temps);
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;

                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }
        //endregion
        // cekPink($stocks);

        //region locket hold
        $tmp_2shold = $sthold->lookupAll()->result();
        // showLast_query("biru");
        // cekBiru(sizeof($tmp_2shold));
        // arrPrintPink($tmp_2shold);
        $stocksHold = array();
        $holdTrace = array();
        $totalHold = array();
        foreach ($tmp_2shold as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocksHold[$cabang_id][$produk_id] = $tempDatas;
            $totalHold[$cabang_id][$produk_id] += $temps->jumlah;

            $holdTrace[$cabang_id][$produk_id] = array();
        }
        //endregion
        // arrPrintKuning($totalHold);
        // matiHere(__LINE__);

        //booking
        // $ls->setFilters(array());
        // $tmp_3s = $ls->lookupAll()->result();
        // showLast_query("biru");
        // matiHere(__LINE__);

        $stocksBooking = array();
        // $totalBooking = array();
        // foreach ($tmp_3s as $temps) {
        //     $tempDatas = array();
        //     foreach ($kolom_2s as $kolom) {
        //         $$kolom = $temps->$kolom;
        //         $tempDatas[$kolom] = $temps->$kolom;
        //     }
        //     $stocksBooking[$cabang_id][$produk_id] = $tempDatas;
        //     // $totalBooking[$cabang_id][$produk_id] += $temps->jumlah;
        //     $totalBooking[$produk_id] += $temps->jumlah;
        // }
        /** -----------------------------------------------------------------------------------------
         * MdlLockerStockBooking belum aktif @22/05/2024 confirmasi dari widi
         *
         * -----------------------------------------------------------------------------------------*/
        $totalBooking = array();
        //  showLast_query("lime");
        //  arrPrint($totalBooking);
        // matiHere();
        //endregion booking
        /** --------------------------------------------------
         * cabang
         * --------------------------------------------------*/
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $kolom_4s = array(
            "id",
            "nama",
        );
        $tmp_4s = $cb->lookupAll()->result();
        //region cabang
        $cabangs = array();
        foreach ($tmp_4s as $temps) {
            $tempDatas = array();
            foreach ($kolom_4s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $cabangs[$id] = $nama;
        }
        //endregion

        /** --------------------------------------------------
         * harga
         * --------------------------------------------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlHargaProdukRakitan");
        $this->load->model("Mdls/MdlHargaProdukKomposit");
        $hg = new MdlHargaProduk();
        $hgr = new MdlHargaProdukRakitan();
        $hgk = new MdlHargaProdukKomposit();
        $kolom_1s = array(
            "cabang_id",
            "produk_id",
            "jenis_value",
            "nilai",
        );
        $tmp_ss = $hg->lookupAll()->result();
        // cekHitam($this->db->last_query());
        // arrPrint($tmp_ss);
        $tmp_rr = $hgr->lookupAll()->result();
        $tmp_tt = $hgk->lookupAll()->result();
        //        $tmp_1s = $tmp_ss;
        $tmp_1s = array_merge($tmp_ss, $tmp_rr, $tmp_tt);
        //region hargas produks
        $hargas = array();
        // $cabangIdsflip = array();
        foreach ($tmp_1s as $temps) {
            $tempDatas = array();
            foreach ($kolom_1s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = (isset($temps->$kolom) || ($temps->$kolom == "")) ? $temps->$kolom : "-";
            }
            // $cabangIdsflip[$cabang_id] =1;
            $hargas[$cabang_id][$produk_id][$jenis_value] = $tempDatas;
        }
        // $cabangIds = array_keys($cabangIdsflip);
        // arrPrintHijau($hargas);
        //endregion

        $this->load->model("Mdls/MdlGudang");
        $gd = new MdlGudang();
        $srcGudang = $gd->lookupAll()->result();

        foreach ($srcGudang as $item) {
            $gudang_nama[$item->id] = $item->nama;
        }
        // arrPrintHijau($gudang_nama);
        // matiHere(__LINE__);
        /** ----------------------------------------------------
         * stok rekening
         * ----------------------------------------------------*/
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $sbk = new ComRekeningPembantuProduk();
        // $this->db->wh
        $sbk->addFilter("qty_debet>0");
        $sbk->addFilter("gudang_id<10");
        $dataSrcs_0 = $sbk->fetchBalances('1010030030');
        // showLast_query("merah");
        // cekHere(count($dataSrcs_0));
        // arrPrint($dataSrcs_0);
        foreach ($dataSrcs_0 as $item_0) {
            $sbk_produk_id = $item_0->extern_id;
            $sbk_cabang_id = $item_0->cabang_id;
            $sbk_gudang_id = trim($item_0->gudang_id) * 1;
            $sbk_qty_debet = isset($item_0->qty_debet) ? $item_0->qty_debet : 0;
            // cekHere($sbk_gudang_id);
            // $gudang_nama = isset($gudang_nama[trim($sbk_gudang_id)]) ? $gudang_nama[trim($sbk_gudang_id)] : $sbk_gudang_id;
            $gudang_nama = isset($gudang_nama[$item_0->gudang_id]) ? $gudang_nama[$item_0->gudang_id] : $sbk_gudang_id;
            // cekMerah("$gudang_nama");

            // $stokReadies[$sbk_produk_id][$sbk_cabang_id][$sbk_gudang_id]['qty'] = $sbk_qty_debet;
            $stokReadies[$sbk_produk_id][$sbk_cabang_id . "|" . $sbk_gudang_id] = $sbk_qty_debet;

            $cbg_nama[$sbk_cabang_id . "|" . $sbk_gudang_id] = $cabangs[$sbk_cabang_id] . $item_0->gudang_id;
            // $cabangkey[]=
        }
        // arrPrintKuning($cbg_nama);
        // // arrPrintHijau($stokReadies);
        // matiHere();
        // -------------------------------------------------------

        /** -------------------------------------------------------
         * booking
         * -------------------------------------------------------*/
        // $srcBookings = $ls->getStokBooking();
        $srcBookings = $this->getStokBooking();
        // cekHere(count($srcBookings));
        // showLast_query("biru");
        // // arrPrint(array_slice($srcBookings,1,1));
        // arrPrint($srcBookings);
        if (ipadd() == MGK_LIVE) {
            // matiHere(__LINE__);
        }

        $cabangNama = $cabangs[$cabangID];
        $this->file = $this->uri->segment(2) . $cabangNama . " " . dtimeNow();
        // $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();
        $header_mains = array(
            // "no"          => array(
            //     "label" => "No",
            //     "type"  => "integer",
            // ),
            "pid" => array(
                "label" => "pID",
                "type" => "integer",
            ),

            // "kode"     => array(
            //     "label" => "SKU",
            //     "type"  => "string",
            // ),
            "barcode" => array(
                "label" => "barcode",
                "type" => "string",
            ),
            "nama" => array(
                "label" => "Nama produk",
                "type" => "string",
            ),
            "kategori" => array(
                "label" => "satuan",
                "type" => "string",
            ),
            //--------------
            // "size" => array(
            //     "label" => "size",
            //     "type"  => "string",
            // ),
            // "kapasitas" => array(
            //     "label" => "kapasitas",
            //     "type"  => "string",
            // ),
            // "tipe" => array(
            //     "label" => "tipe",
            //     "type"  => "string",
            // ),
            // "series" => array(
            //     "label" => "series",
            //     "type"  => "string",
            // ),
            // "merek" => array(
            //     "label" => "merek",
            //     "type"  => "string",
            // ),
            // "satuan"   => array(
            //     "label" => "stuan",
            //     "type"  => "string",
            // ),
        );
        $header_pusat = array();
        if ($cabangID == '-1') {
            $header_pusat = array(
                "hpp" => array(
                    "label" => "HPP",
                    "type" => "integer",
                ),
            );
        }
        $header_umum = array(
            "jual" => array(
                "label" => "Harga Jual",
                "type" => "integer",
            ),
            "jual_nppn" => array(
                "label" => "Harga Jual + ppn",
                "type" => "integer",
            ),

            "stok_active" => array(
                "label" => "Stock aktive",
                "type" => "integer",
            ),
            "stok_hold" => array(
                "label" => "Stock intransit",
                "type" => "integer",
            ),
            "stok_booking" => array(
                "label" => "Stock booking",
                "type" => "integer",
            ),
            "stok_total" => array(
                "label" => "Stock total",
                "type" => "integer",
            ),
        );

        /*---stok cabang dan holding--------*/
        // arrPrintKuning($cbg_nama);
        foreach ($cbg_nama as $ky_cabang => $label_cabang) {
            $stok_cb[$ky_cabang] = array(
                "label" => $label_cabang,
                "type" => "integer",
            );
        }
        $stok_cb['hd'] = array(
            "label" => "kuantitas total",
            "type" => "integer",
        );
        $booking = array(
            "stok_booking_so" => array(
                "label" => "Stock booking",
                "type" => "integer",
            ),
            // "stok_booking"     => array(
            "stok_hold" => array(
                "label" => "Stock intransit",
                "type" => "integer",
            ),
            "stok_bisa_dijual" => array(
                "label" => "Stock tersedia",
                "type" => "integer",
            ),
        );
        // $headers = $header_mains + $header_pusat + $header_umum + $stok_cb;
        $headers = $header_mains + $stok_cb + $booking;
        // if (ipadd() == MGK_LIVE) {
        //     arrPrintHijau($headers);
        //     mati_disini();
        // }

        $produkSetahun = $this->getStokAyear();
        $stokSetahun = [];
        foreach ($produkSetahun as $item) {
            $extern_id = $item['extern_id'];
            // $extern_nama = $item['extern_nama'];

            $stokSetahun[$extern_id] = $item;
        }
        // showLast_query("biru");

        // region pairing data yg akan diexcelkan
        $no = 0;
        $datas = array();
        $dataSpec = array();

        $mee = $stokReadies + $stokSetahun;
        // $dataBersihSrcs = array_intersect_key($stokReadies, $dataSrcs);
        // $dataBersihSrcs = array_intersect_key($dataSrcs, $stokReadies);
        $dataBersihSrcs = array_intersect_key($dataSrcs, ($stokSetahun + $stokReadies));
        $databedaSrcs = array_diff_key($stokReadies, $dataSrcs);
        // $dataBersihSrcs = $dataSrcs;

        if (MGK_LIVE == ipadd()) {
            // arrPrintKuning(array_slice($stokReadies,0,1));
            // arrPrintKuning(array_slice($dataBersihSrcs,0,1));
            //
            // cekMerah(count($dataSrcs) . " " . count($stokReadies) . " " . count($dataBersihSrcs));
            // cekLime(count($produkSetahun) . " " . count($mee));
            // matiHere(__LINE__);
        }

        // cekMerah("jml_produk " . count($dataSrcs));
        // cekMerah("jml_produk_ada_stok " . count($dataBersihSrcs));
        // cekMerah(count($databedaSrcs));
        // arrPrintPink($databedaSrcs);
        // arrPrintHijau(array_slice($dataBersihSrcs, 2,4));
        // arrPrintHijau(array_slice($dataBersihSrcs, 2));

        // cekPink($cabangID);
        foreach ($dataBersihSrcs as $pId => $itemffs) {
            // foreach ($itemffs as $hpp => $stok) {
            $no++;
            $stok_active = isset($stocks[$cabangID][$pId]['jumlah']) ? $stocks[$cabangID][$pId]['jumlah'] : 0;
            $stok_hold = isset($stocksHold[$cabangID][$pId]['jumlah']) ? $stocksHold[$cabangID][$pId]['jumlah'] : 0;
            // $stok_booking = isset($totalBooking[$cabangID][$pId]) ? $totalBooking[$cabangID][$pId] : 0;
            $stok_booking_so = isset($srcBookings[$pId]) ? $srcBookings[$pId]["sum_valid_qty"] : 0;
            // $stok_booking = isset($totalBooking[$pId]) ? $totalBooking[$pId] : 0;
            $stok_booking = $stok_hold;
            $stok_total = $stok_hold + $stok_active + $stok_booking;

            // if($pId == "954"){
            // arrPrintHijau($srcBookings);
            //     cekMerah("$stok_booking_so");
            // }

            $harga_hpp = isset($hargas[$cabangID][$pId]['hpp']['nilai']) ? $hargas[$cabangID][$pId]['hpp']['nilai'] : 0;
            $harga_jual = isset($hargas[$cabangID][$pId]['jual']['nilai']) ? $hargas[$cabangID][$pId]['jual']['nilai'] : 0;
            $harga_jual_nppn = isset($hargas[$cabangID][$pId]['jual_nppn']['nilai']) ? $hargas[$cabangID][$pId]['jual_nppn']['nilai'] : 0;

            $dataSpec['pid'] = $pId;
            $dataSpec['kode'] = isset($itemffs->kode) ? $itemffs->kode : "-";
            $dataSpec['barcode'] = isset($itemffs->barcode) ? $itemffs->barcode : "-";
            $dataSpec['nama'] = isset($itemffs->nama) ? $itemffs->nama : "";
            $dataSpec['kategori'] = isset($itemffs->kategori_nama) ? $itemffs->kategori_nama : "";

            //size,kapasitas,tipe,series,merek
            $dataSpec['size'] = isset($itemffs->size_nama) ? $itemffs->size_nama : "";
            $dataSpec['kapasitas'] = isset($itemffs->kapasitas_nama) ? $itemffs->kapasitas_nama : "";
            $dataSpec['tipe'] = isset($itemffs->tipe_nama) ? $itemffs->tipe_nama : "";
            $dataSpec['series'] = isset($itemffs->series_nama) ? $itemffs->series_nama : "";
            $dataSpec['merek'] = isset($itemffs->merek_nama) ? $itemffs->merek_nama : "";

            $dataSpec['label'] = isset($itemffs->label) ? $itemffs->label : "";
            $dataSpec['folder'] = isset($itemffs->folders_nama) ? $itemffs->folders_nama : "";
            $dataSpec['jenis'] = isset($itemffs->jenis) ? $itemffs->jenis : "";
            $dataSpec['satuan'] = isset($itemffs->satuan) ? $itemffs->satuan : "";

            $dataSpec['stok_active'] = $stok_active;
            $dataSpec['stok_booking_so'] = $stok_booking_so;
            $dataSpec['stok_booking'] = $stok_booking;
            $dataSpec['stok_hold'] = $stok_hold;
            $dataSpec['stok_total'] = $stok_total;
            $dataSpec['hpp'] = $harga_hpp * 1;
            $dataSpec['jual'] = $harga_jual * 1;
            $dataSpec['jual_nppn'] = $harga_jual_nppn * 1;

            //stok
            $item_stok_holding = 0;

            $stokCabang = isset($stokReadies[$pId]) ? $stokReadies[$pId] : array();
            // cekHere($pId);
            // arrPrintKuning($stokCabang);
            foreach ($cbg_nama as $cid => $item) {
                $dataSpec[$cid] = isset($stokCabang[$cid]) ? $stokCabang[$cid] : 0;
                $item_stok_holding += isset($stokCabang[$cid]) ? $stokCabang[$cid] : 0;
            }

            if (!isset($dataSpec['hd'])) {
                $dataSpec['hd'] = 0;
            }
            $dataSpec['hd'] = $item_stok_holding;
            $stok_bisa_dijual = $item_stok_holding - $stok_booking - $stok_booking_so;
            $dataSpec['stok_bisa_dijual'] = $stok_bisa_dijual < 0 ? 0 : $stok_bisa_dijual;

            $datas[] = (object)$dataSpec;
            // }
        }
        // endregion
        if (ipadd() == "202.65.117.72") {

            // arrPrintHijau($headers);
            // arrPrintWebs($datas);
            // arrPrint(count($datas));
            // mati_disini();
        }

        $rowData = array(
            "CV. Everest Jaya Elektronik",
            "Kuantitas Stok Gudang",
            dtimeNow(),
        );
        $ex->setRowContent($rowData);
        // $ex->setRowContent("dadadad");
        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();

    }

    public function katalogproduk()
    {
        $params = blobDecode($this->uri->segment(3));
        $mdlName = $params['mdl'];
        $mdlFifo = $params['fifo'];
        $cabangID = $cabangid = $params['cabang_id'];
        // arrPrint($params);
        $mdlHarga = "MdlHargaProduk";

        $this->load->model("Mdls/$mdlName"); // MdlProduk2
        // $this->load->model("Coms/$mdlName");
        $this->load->model("Mdls/$mdlFifo");
        $this->load->model("Mdls/$mdlHarga");
        $md = new $mdlName();
        $ff = new $mdlFifo();
        $ha = new $mdlHarga();

        /* ------------------------------
         * data kunci produk
         * --------------------------*/
        // $md->addFilter("kategori_nama='unit'");
        /* ----------------------------------------------------
         * filter unit dimatikan permintaan everest 23/02/2024
         * update selanjutnya
         *          bisa multy tab masing masing per kategori unit-non unit
         * ----------------------------------------------------*/
        // $this->db->limit(5);
        $this->db->order_by('nama', 'asc');
        $tmps_1 = $md->lookupAll();
        showLast_query("lime");
        $dataSrcs_0 = $tmps_1->result();
        // arrPrint($dataSrcs);
        $dataSrcs = array();
        foreach ($dataSrcs_0 as $dataSrc) {
            $dataSrcs[$dataSrc->id] = $dataSrc;
        }

        if (MGK_LIVE == ipadd()) {
            // matiDisini(__LINE__);
        }
        $where_2 = array(
            "cabang_id" => $cabangid
        );
        $this->db->where($where_2);
        $tmps_2 = $ff->lookupAll()->result();
        // showLast_query("orange");
        // arrPrint($tmps_2);
        foreach ($tmps_2 as $item_2) {

            // if (!isset($dataFifo[$item_2->produk_id])) {
            //     $dataFifo[$item_2->produk_id] = 0;
            // }
            // $dataFifo[$item_2->produk_id] += $item_2->unit;

            // $dataFifo[$item_2->produk_id][] = array(
            //     "unit" => $item_2->unit,
            //     "hpp" => $item_2->hpp,
            // );

            if (!isset($dataFifo[$item_2->produk_id][$item_2->hpp])) {
                $dataFifo[$item_2->produk_id][$item_2->hpp] = 0;
            }
            $dataFifo[$item_2->produk_id][$item_2->hpp] += $item_2->unit;

        }
        // arrPrintWebs($dataFifo);
        //         matiHere();
        $where_30 = array(
            "jenis_value" => "jual"
        );
        $where_3 = $where_2 + $where_30;
        $this->db->where($where_3);
        $hargas_0 = $ha->lookupAll()->result();
        // showLast_query("lime");
        // arrPrint($hargas_0);
        foreach ($hargas_0 as $harga_0) {
            $hargas[$harga_0->produk_id] = $harga_0->nilai;
        }

        // matiHere();
        // locker
        // $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlLockerStockBooking");
        $ls = new MdlLockerStockBooking();
        $st = new MdlLockerStock();
        $sthold = new MdlLockerStock();

        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );
        $inCabId = array();
        //region locker aktive
        $inGid = array("-250", "-210", "-10", "-1", "-260", "-270", "-280", "-290", "-300", "-310");//ini ditembak dulu sambil nyari cara auto get gudang default

        if ($cabangID > 0) {
            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }
        else {
            $inCabId = array("25", "21", "1", "-1", "26", "27", "28", "29", "30", "31");
            $inGid = array("-250", "-210", "-10", "-1", "-260", "-270", "-280", "-290", "-300", "-310");//ini ditembak dulu sambil nyari cara auto get gudang default
            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            //            $st->addFilter("jenis in ('produk', 'produk rakitan')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }
        $tmp_2s = $st->lookupAll()->result();
        // showLast_query("kuning");
        // cekKuning(sizeof($tmp_2s));
        // arrPrintPink($tmp_2s);
        $stocks = array();
        foreach ($tmp_2s as $temps) {
            //            arrprint($temps);
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;

                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }
        //endregion
        // cekPink($stocks);

        //region locket hold
        $tmp_2shold = $sthold->lookupAll()->result();
        // showLast_query("biru");
        // cekBiru(sizeof($tmp_2shold));
        // arrPrintPink($tmp_2shold);
        $stocksHold = array();
        $holdTrace = array();
        $totalHold = array();
        foreach ($tmp_2shold as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocksHold[$cabang_id][$produk_id] = $tempDatas;
            $totalHold[$cabang_id][$produk_id] += $temps->jumlah;

            $holdTrace[$cabang_id][$produk_id] = array();
        }
        //endregion
        // arrPrintKuning($totalHold);
        // matiHere(__LINE__);

        //booking
        // $ls->setFilters(array());
        // $tmp_3s = $ls->lookupAll()->result();
        // showLast_query("biru");
        // matiHere(__LINE__);

        $stocksBooking = array();
        // $totalBooking = array();
        // foreach ($tmp_3s as $temps) {
        //     $tempDatas = array();
        //     foreach ($kolom_2s as $kolom) {
        //         $$kolom = $temps->$kolom;
        //         $tempDatas[$kolom] = $temps->$kolom;
        //     }
        //     $stocksBooking[$cabang_id][$produk_id] = $tempDatas;
        //     // $totalBooking[$cabang_id][$produk_id] += $temps->jumlah;
        //     $totalBooking[$produk_id] += $temps->jumlah;
        // }
        /* -----------------------------------------------------------------------------------------
         * MdlLockerStockBooking belum aktif @22/05/2024 confirmasi dari widi
         *
         * -----------------------------------------------------------------------------------------*/
        $totalBooking = array();
        //  showLast_query("lime");
        //  arrPrint($totalBooking);
        // matiHere();
        //endregion booking
        /* --------------------------------------------------
         * cabang
         * --------------------------------------------------*/
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $kolom_4s = array(
            "id",
            "nama",
        );
        $tmp_4s = $cb->lookupAll()->result();
        //region cabang
        $cabangs = array();
        foreach ($tmp_4s as $temps) {
            $tempDatas = array();
            foreach ($kolom_4s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $cabangs[$id] = $nama;
        }
        //endregion

        /* --------------------------------------------------
         * harga
         * --------------------------------------------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlHargaProdukRakitan");
        $this->load->model("Mdls/MdlHargaProdukKomposit");
        $hg = new MdlHargaProduk();
        $hgr = new MdlHargaProdukRakitan();
        $hgk = new MdlHargaProdukKomposit();
        $kolom_1s = array(
            "cabang_id",
            "produk_id",
            "jenis_value",
            "nilai",
        );
        $tmp_ss = $hg->lookupAll()->result();
        // cekHitam($this->db->last_query());
        // arrPrint($tmp_ss);
        $tmp_rr = $hgr->lookupAll()->result();
        $tmp_tt = $hgk->lookupAll()->result();
        //        $tmp_1s = $tmp_ss;
        $tmp_1s = array_merge($tmp_ss, $tmp_rr, $tmp_tt);
        //region hargas produks
        $hargas = array();
        // $cabangIdsflip = array();
        foreach ($tmp_1s as $temps) {
            $tempDatas = array();
            foreach ($kolom_1s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = (isset($temps->$kolom) || ($temps->$kolom == "")) ? $temps->$kolom : "-";
            }
            // $cabangIdsflip[$cabang_id] =1;
            $hargas[$cabang_id][$produk_id][$jenis_value] = $tempDatas;
        }
        // $cabangIds = array_keys($cabangIdsflip);
        // arrPrintHijau($hargas);
        //endregion

        $this->load->model("Mdls/MdlGudang");
        $gd = new MdlGudang();
        $srcGudang = $gd->lookupAll()->result();

        foreach ($srcGudang as $item) {
            $gudang_nama[$item->id] = $item->nama;
        }
        // arrPrintHijau($gudang_nama);
        // matiHere(__LINE__);
        /* ----------------------------------------------------
         * stok rekening
         * ----------------------------------------------------*/
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $sbk = new ComRekeningPembantuProduk();
        // $this->db->wh
        $sbk->addFilter("qty_debet>0");
        $sbk->addFilter("gudang_id<10");
        $dataSrcs_0 = $sbk->fetchBalances('1010030030');
        // showLast_query("merah");
        // cekHere(count($dataSrcs_0));
        // arrPrint($dataSrcs_0);
        foreach ($dataSrcs_0 as $item_0) {
            $sbk_produk_id = $item_0->extern_id;
            $sbk_cabang_id = $item_0->cabang_id;
            $sbk_gudang_id = trim($item_0->gudang_id) * 1;
            $sbk_qty_debet = isset($item_0->qty_debet) ? $item_0->qty_debet : 0;
            // cekHere($sbk_gudang_id);
            // $gudang_nama = isset($gudang_nama[trim($sbk_gudang_id)]) ? $gudang_nama[trim($sbk_gudang_id)] : $sbk_gudang_id;
            $gudang_nama = isset($gudang_nama[$item_0->gudang_id]) ? $gudang_nama[$item_0->gudang_id] : $sbk_gudang_id;
            // cekMerah("$gudang_nama");

            // $stokReadies[$sbk_produk_id][$sbk_cabang_id][$sbk_gudang_id]['qty'] = $sbk_qty_debet;
            $stokReadies[$sbk_produk_id][$sbk_cabang_id . "|" . $sbk_gudang_id] = $sbk_qty_debet;

            $cbg_nama[$sbk_cabang_id . "|" . $sbk_gudang_id] = $cabangs[$sbk_cabang_id] . $item_0->gudang_id;
            // $cabangkey[]=
        }
        // arrPrintKuning($cbg_nama);
        // // arrPrintHijau($stokReadies);
        // matiHere();
        // -------------------------------------------------------

        /* -------------------------------------------------------
         * booking
         * -------------------------------------------------------*/
        // $srcBookings = $ls->getStokBooking();
        $srcBookings = $this->getStokBooking();
        // cekHere(count($srcBookings));
        // showLast_query("biru");
        // // arrPrint(array_slice($srcBookings,1,1));
        // arrPrint($srcBookings);
        if (ipadd() == MGK_LIVE) {
            // matiHere(__LINE__);
        }

        $cabangNama = $cabangs[$cabangID];
        $this->file = $this->uri->segment(2) . $cabangNama . " " . dtimeNow();
        // $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();
        $header_mains = array(
            // "no"          => array(
            //     "label" => "No",
            //     "type"  => "integer",
            // ),
            "pid" => array(
                "label" => "pID",
                "type" => "integer",
            ),

            // "kode"     => array(
            //     "label" => "SKU",
            //     "type"  => "string",
            // ),
            "barcode" => array(
                "label" => "barcode",
                "type" => "string",
            ),
            "nama" => array(
                "label" => "Nama produk",
                "type" => "string",
            ),
            "kategori" => array(
                "label" => "satuan",
                "type" => "string",
            ),
            //--------------
            // "size" => array(
            //     "label" => "size",
            //     "type"  => "string",
            // ),
            // "kapasitas" => array(
            //     "label" => "kapasitas",
            //     "type"  => "string",
            // ),
            // "tipe" => array(
            //     "label" => "tipe",
            //     "type"  => "string",
            // ),
            // "series" => array(
            //     "label" => "series",
            //     "type"  => "string",
            // ),
            // "merek" => array(
            //     "label" => "merek",
            //     "type"  => "string",
            // ),
            // "satuan"   => array(
            //     "label" => "stuan",
            //     "type"  => "string",
            // ),
        );
        $header_pusat = array();
        if ($cabangID == '-1') {
            $header_pusat = array(
                "hpp" => array(
                    "label" => "HPP",
                    "type" => "integer",
                ),
            );
        }
        $header_umum = array(
            "jual" => array(
                "label" => "Harga Jual",
                "type" => "integer",
            ),
            "jual_nppn" => array(
                "label" => "Harga Jual + ppn",
                "type" => "integer",
            ),

            "stok_active" => array(
                "label" => "Stock aktive",
                "type" => "integer",
            ),
            "stok_hold" => array(
                "label" => "Stock intransit",
                "type" => "integer",
            ),
            "stok_booking" => array(
                "label" => "Stock booking",
                "type" => "integer",
            ),
            "stok_total" => array(
                "label" => "Stock total",
                "type" => "integer",
            ),
        );

        /*---stok cabang dan holding--------*/
        // arrPrintKuning($cbg_nama);
        foreach ($cbg_nama as $ky_cabang => $label_cabang) {
            $stok_cb[$ky_cabang] = array(
                "label" => $label_cabang,
                "type" => "integer",
            );
        }
        $stok_cb['hd'] = array(
            "label" => "kuantitas total",
            "type" => "integer",
        );
        $booking = array(
            "stok_booking_so" => array(
                "label" => "Stock booking",
                "type" => "integer",
            ),
            // "stok_booking"     => array(
            "stok_hold" => array(
                "label" => "Stock intransit",
                "type" => "integer",
            ),
            "stok_bisa_dijual" => array(
                "label" => "Stock tersedia",
                "type" => "integer",
            ),
        );
        // $headers = $header_mains + $header_pusat + $header_umum + $stok_cb;
        $headers = $header_mains + $stok_cb + $booking;
        // if (ipadd() == MGK_LIVE) {
        //     arrPrintHijau($headers);
        //     mati_disini();
        // }


        // region pairing data yg akan diexcelkan
        $no = 0;
        $datas = array();
        $dataSpec = array();

        // $dataBersihSrcs = array_intersect_key($stokReadies, $dataSrcs);
        // $dataBersihSrcs = array_intersect_key($dataSrcs, $stokReadies);
        $databedaSrcs = array_diff_key($stokReadies, $dataSrcs);
        $dataBersihSrcs = $dataSrcs;
        // cekMerah("jml_produk " . count($dataSrcs));
        // cekMerah("jml_produk_ada_stok " . count($dataBersihSrcs));
        // cekMerah(count($databedaSrcs));
        // arrPrintPink($databedaSrcs);
        // arrPrintHijau(array_slice($dataBersihSrcs, 2,4));
        // arrPrintHijau(array_slice($dataBersihSrcs, 2));

        // cekPink($cabangID);
        foreach ($dataBersihSrcs as $pId => $itemffs) {
            // foreach ($itemffs as $hpp => $stok) {
            $no++;
            $stok_active = isset($stocks[$cabangID][$pId]['jumlah']) ? $stocks[$cabangID][$pId]['jumlah'] : 0;
            $stok_hold = isset($stocksHold[$cabangID][$pId]['jumlah']) ? $stocksHold[$cabangID][$pId]['jumlah'] : 0;
            // $stok_booking = isset($totalBooking[$cabangID][$pId]) ? $totalBooking[$cabangID][$pId] : 0;
            $stok_booking_so = isset($srcBookings[$pId]) ? $srcBookings[$pId]["sum_valid_qty"] : 0;
            // $stok_booking = isset($totalBooking[$pId]) ? $totalBooking[$pId] : 0;
            $stok_booking = $stok_hold;
            $stok_total = $stok_hold + $stok_active + $stok_booking;

            // if($pId == "954"){
            // arrPrintHijau($srcBookings);
            //     cekMerah("$stok_booking_so");
            // }

            $harga_hpp = isset($hargas[$cabangID][$pId]['hpp']['nilai']) ? $hargas[$cabangID][$pId]['hpp']['nilai'] : 0;
            $harga_jual = isset($hargas[$cabangID][$pId]['jual']['nilai']) ? $hargas[$cabangID][$pId]['jual']['nilai'] : 0;
            $harga_jual_nppn = isset($hargas[$cabangID][$pId]['jual_nppn']['nilai']) ? $hargas[$cabangID][$pId]['jual_nppn']['nilai'] : 0;

            $dataSpec['pid'] = $pId;
            $dataSpec['kode'] = isset($itemffs->kode) ? $itemffs->kode : "-";
            $dataSpec['barcode'] = isset($itemffs->barcode) ? $itemffs->barcode : "-";
            $dataSpec['nama'] = isset($itemffs->nama) ? $itemffs->nama : "";
            $dataSpec['kategori'] = isset($itemffs->kategori_nama) ? $itemffs->kategori_nama : "";

            //size,kapasitas,tipe,series,merek
            $dataSpec['size'] = isset($itemffs->size_nama) ? $itemffs->size_nama : "";
            $dataSpec['kapasitas'] = isset($itemffs->kapasitas_nama) ? $itemffs->kapasitas_nama : "";
            $dataSpec['tipe'] = isset($itemffs->tipe_nama) ? $itemffs->tipe_nama : "";
            $dataSpec['series'] = isset($itemffs->series_nama) ? $itemffs->series_nama : "";
            $dataSpec['merek'] = isset($itemffs->merek_nama) ? $itemffs->merek_nama : "";

            $dataSpec['label'] = isset($itemffs->label) ? $itemffs->label : "";
            $dataSpec['folder'] = isset($itemffs->folders_nama) ? $itemffs->folders_nama : "";
            $dataSpec['jenis'] = isset($itemffs->jenis) ? $itemffs->jenis : "";
            $dataSpec['satuan'] = isset($itemffs->satuan) ? $itemffs->satuan : "";

            $dataSpec['stok_active'] = $stok_active;
            $dataSpec['stok_booking_so'] = $stok_booking_so;
            $dataSpec['stok_booking'] = $stok_booking;
            $dataSpec['stok_hold'] = $stok_hold;
            $dataSpec['stok_total'] = $stok_total;
            $dataSpec['hpp'] = $harga_hpp * 1;
            $dataSpec['jual'] = $harga_jual * 1;
            $dataSpec['jual_nppn'] = $harga_jual_nppn * 1;

            //stok
            $item_stok_holding = 0;

            $stokCabang = isset($stokReadies[$pId]) ? $stokReadies[$pId] : array();
            // cekHere($pId);
            // arrPrintKuning($stokCabang);
            foreach ($cbg_nama as $cid => $item) {
                $dataSpec[$cid] = isset($stokCabang[$cid]) ? $stokCabang[$cid] : 0;
                $item_stok_holding += isset($stokCabang[$cid]) ? $stokCabang[$cid] : 0;
            }

            if (!isset($dataSpec['hd'])) {
                $dataSpec['hd'] = 0;
            }
            $dataSpec['hd'] = $item_stok_holding;
            $stok_bisa_dijual = $item_stok_holding - $stok_booking - $stok_booking_so;
            $dataSpec['stok_bisa_dijual'] = $stok_bisa_dijual < 0 ? 0 : $stok_bisa_dijual;

            $datas[] = (object)$dataSpec;
            // }
        }
        // endregion
        if (ipadd() == "202.65.117.72") {

            // arrPrintHijau($headers);
            // arrPrintWebs($datas);
            // arrPrint(count($datas));
            // mati_disini();
        }

        $rowData = array(
            "CV. Everest Jaya Elektronik",
            "Kuantitas Stok Gudang",
            dtimeNow(),
        );
        $ex->setRowContent($rowData);
        // $ex->setRowContent("dadadad");
        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();

    }

    public function getStokBooking()
    {
        $tbl_1 = "transaksi";
        $tbl_2 = "transaksi_data";

        $selected = array(
            "sum(valid_qty) as 'sum_valid_qty'",
            "produk_id",
            "produk_nama",
            "transaksi_id",
        );
        $this->db->select($selected);
        $this->db->from($tbl_1);
        $this->db->join($tbl_2, "$tbl_1.id = $tbl_2.transaksi_id", 'inner');
        // Ubah 'id_transaksi' sesuai dengan kolom yang digunakan untuk mengaitkan kedua tabel
        $condites = array(
            "$tbl_1.trash_4" => "0",
            "$tbl_1.jenis" => "5822so",
            "$tbl_2.valid_qty>" => 0,
            "$tbl_2.next_substep_code!=" => "",
        );
        $this->db->where($condites);
        $this->db->group_by("produk_id");
        $query = $this->db->get()->result_array();

        foreach ($query as $item) {
            $produk_id = $item["produk_id"];

            $queries[$produk_id] = $item;
        }

        return $queries;
    }

    public function getStokAyear()
    {
        $tbl_1 = "__rek_pembantu_produk__1010030030";
        // $tbl_2 = "transaksi_data";

        $date2 = dtimeNow('Y-m-d');
        $date1 = date('Y-m-d', strtotime('-1 year', strtotime($date2)));
        $selected = array(
            // "sum(valid_qty) as 'sum_valid_qty'",
            // "produk_id",
            "extern_nama",
            "extern_id",
        );
        $this->db->select($selected);
        $this->db->from($tbl_1);

        // Ubah 'id_transaksi' sesuai dengan kolom yang digunakan untuk mengaitkan kedua tabel
        $condites = array(
            "$tbl_1.dtime >=" => $date1,
            "$tbl_1.dtime <=" => $date2,
            // "$tbl_1.jenis" => "5822so",
            // "$tbl_2.valid_qty>" => 0,
            // "$tbl_2.next_substep_code!=" => "",
        );
        $this->db->where($condites);
        $this->db->group_by("extern_id");
        $query = $this->db->get()->result_array();

        return $query;
    }

    //generate ke tabel produk_katalog
    public function katalogToTable()
    {

        $this->writeKatalog();

        $params = blobDecode($this->uri->segment(3));
        $mdlName = $params['mdl'];
        $mdlFifo = $params['fifo'];
        $cabangID = $cabangid = $params['cabang_id'];
        // arrPrint($params);
        $mdlHarga = "MdlHargaProduk";

        $this->load->model("Mdls/$mdlName"); // MdlProduk2
        // $this->load->model("Coms/$mdlName");
        $this->load->model("Mdls/$mdlFifo");
        $this->load->model("Mdls/$mdlHarga");
        $md = new $mdlName();
        $ff = new $mdlFifo();
        $ha = new $mdlHarga();

        /* ------------------------------
         * data kunci produk
         * --------------------------*/
        $md->addFilter("kategori_nama='unit'");
        // $this->db->limit(5);
        $this->db->order_by('nama', 'asc');
        $tmps_1 = $md->lookupAll();
        // showLast_query("lime");
        $dataSrcs_0 = $tmps_1->result();
        // arrPrint($dataSrcs);
        $dataSrcs = array();
        foreach ($dataSrcs_0 as $dataSrc) {
            $dataSrcs[$dataSrc->id] = $dataSrc;
        }

        $where_2 = array(
            "cabang_id" => $cabangid
        );
        $this->db->where($where_2);
        $tmps_2 = $ff->lookupAll()->result();
        // showLast_query("orange");
        // arrPrint($tmps_2);
        foreach ($tmps_2 as $item_2) {

            // if (!isset($dataFifo[$item_2->produk_id])) {
            //     $dataFifo[$item_2->produk_id] = 0;
            // }
            // $dataFifo[$item_2->produk_id] += $item_2->unit;

            // $dataFifo[$item_2->produk_id][] = array(
            //     "unit" => $item_2->unit,
            //     "hpp" => $item_2->hpp,
            // );

            if (!isset($dataFifo[$item_2->produk_id][$item_2->hpp])) {
                $dataFifo[$item_2->produk_id][$item_2->hpp] = 0;
            }
            $dataFifo[$item_2->produk_id][$item_2->hpp] += $item_2->unit;

        }
        // arrPrintWebs($dataFifo);
        //         matiHere();
        $where_30 = array(
            "jenis_value" => "jual"
        );
        $where_3 = $where_2 + $where_30;
        $this->db->where($where_3);
        $hargas_0 = $ha->lookupAll()->result();
        // showLast_query("lime");
        // arrPrint($hargas_0);
        foreach ($hargas_0 as $harga_0) {
            $hargas[$harga_0->produk_id] = $harga_0->nilai;
        }

        // matiHere();
        // locker
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlLockerStock");
        $this->load->model("Mdls/MdlLockerStockBooking");
        $ls = new MdlLockerStockBooking();
        $st = new MdlLockerStock();
        $sthold = new MdlLockerStock();

        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );
        $inCabId = array();
        //region locker aktive
        $inGid = array("-250", "-210", "-10", "-1", "-260", "-270", "-280", "-290", "-300", "-310");//ini ditembak dulu sambil nyari cara auto get gudang default

        if ($cabangID > 0) {
            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }
        else {
            $inCabId = array("25", "21", "1", "-1", "26", "27", "28", "29", "30", "31");
            $inGid = array("-250", "-210", "-10", "-1", "-260", "-270", "-280", "-290", "-300", "-310");//ini ditembak dulu sambil nyari cara auto get gudang default
            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            //            $st->addFilter("jenis in ('produk', 'produk rakitan')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }
        $tmp_2s = $st->lookupAll()->result();
        // showLast_query("kuning");
        // cekKuning(sizeof($tmp_2s));
        // arrPrintPink($tmp_2s);
        $stocks = array();
        foreach ($tmp_2s as $temps) {
            //            arrprint($temps);
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;

                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }
        //endregion
        // cekPink($stocks);

        //region locket hold
        $tmp_2shold = $sthold->lookupAll()->result();
        showLast_query("biru");
        // cekBiru(sizeof($tmp_2shold));
        // arrPrintPink($tmp_2shold);
        $stocksHold = array();
        $holdTrace = array();
        $totalHold = array();
        foreach ($tmp_2shold as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocksHold[$cabang_id][$produk_id] = $tempDatas;
            $totalHold[$cabang_id][$produk_id] += $temps->jumlah;

            $holdTrace[$cabang_id][$produk_id] = array();
        }
        //endregion

        //booking
        $ls->setFilters(array());
        $tmp_3s = $ls->lookupAll()->result();
        $stocksBooking = array();
        $totalBooking = array();
        foreach ($tmp_3s as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            if (!isset($totalBooking[$cabang_id][$produk_id])) {
                $totalBooking[$cabang_id][$produk_id] = 0;
            }
            $stocksBooking[$cabang_id][$produk_id] = $tempDatas;
            $totalBooking[$cabang_id][$produk_id] += $temps->jumlah;
        }

        /* --------------------------------------------------
         * cabang
         * --------------------------------------------------*/
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $kolom_4s = array(
            "id",
            "nama",
        );
        $tmp_4s = $cb->lookupAll()->result();
        //region cabang
        $cabangs = array();
        foreach ($tmp_4s as $temps) {
            $tempDatas = array();
            foreach ($kolom_4s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $cabangs[$id] = $nama;
        }
        //endregion

        /* --------------------------------------------------
         * harga
         * --------------------------------------------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlHargaProdukRakitan");
        $this->load->model("Mdls/MdlHargaProdukKomposit");
        $hg = new MdlHargaProduk();
        $hgr = new MdlHargaProdukRakitan();
        $hgk = new MdlHargaProdukKomposit();
        $kolom_1s = array(
            "cabang_id",
            "produk_id",
            "jenis_value",
            "nilai",
        );
        $tmp_ss = $hg->lookupAll()->result();
        $tmp_rr = $hgr->lookupAll()->result();
        $tmp_tt = $hgk->lookupAll()->result();
        $tmp_1s = array_merge($tmp_ss, $tmp_rr, $tmp_tt);
        //region hargas produks
        $hargas = array();
        foreach ($tmp_1s as $temps) {
            $tempDatas = array();
            foreach ($kolom_1s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = (isset($temps->$kolom) || ($temps->$kolom == "")) ? $temps->$kolom : "-";
            }
            $hargas[$cabang_id][$produk_id][$jenis_value] = $tempDatas;
        }
        //endregion

        /* ----------------------------------------------------
         * stok rekening
         * ----------------------------------------------------*/
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $sbk = new ComRekeningPembantuProduk();
        $sbk->addFilter("qty_debet>0");
        $dataSrcs_0 = $sbk->fetchBalances('1010030030');

        foreach ($dataSrcs_0 as $item_0) {
            $sbk_produk_id = $item_0->extern_id;
            $sbk_cabang_id = $item_0->cabang_id;
            $sbk_gudang_id = $item_0->gudang_id;
            $sbk_qty_debet = isset($item_0->qty_debet) ? $item_0->qty_debet : 0;
            $stokReadies[$sbk_produk_id][$sbk_cabang_id . "|" . $sbk_gudang_id] = $sbk_qty_debet;
            $cbg_nama[$sbk_cabang_id . "|" . $sbk_gudang_id] = $cabangs[$sbk_cabang_id];
        }
        // -------------------------------------------------------

        $cabangNama = $cabangs[$cabangID];
        $this->file = $this->uri->segment(2) . $cabangNama . " " . dtimeNow();
        $this->load->library('Excel');
        $ex = new Excel();
        $header_mains = array(
            "pid" => array(
                "label" => "pID",
                "type" => "integer",
            ),
            "nama" => array(
                "label" => "Nama produk",
                "type" => "string",
            ),
            "kategori" => array(
                "label" => "satuan",
                "type" => "string",
            ),
            "size" => array(
                "label" => "size",
                "type" => "string",
            ),
            "kapasitas" => array(
                "label" => "kapasitas",
                "type" => "string",
            ),
            "tipe" => array(
                "label" => "tipe",
                "type" => "string",
            ),
            "series" => array(
                "label" => "series",
                "type" => "string",
            ),
            "merek" => array(
                "label" => "merek",
                "type" => "string",
            ),
        );
        $header_pusat = array();
        if ($cabangID == '-1') {
            $header_pusat = array(
                "hpp" => array(
                    "label" => "HPP",
                    "type" => "integer",
                ),
            );
        }
        $header_umum = array(
            "jual" => array(
                "label" => "Harga Jual",
                "type" => "integer",
            ),
            "jual_nppn" => array(
                "label" => "Harga Jual + ppn",
                "type" => "integer",
            ),
            "stok_active" => array(
                "label" => "Stock aktive",
                "type" => "integer",
            ),
            "stok_hold" => array(
                "label" => "Stock intransit",
                "type" => "integer",
            ),
            "stok_booking" => array(
                "label" => "Stock booking",
                "type" => "integer",
            ),
            "stok_total" => array(
                "label" => "Stock total",
                "type" => "integer",
            ),
        );

        foreach ($cbg_nama as $ky_cabang => $label_cabang) {
            $stok_cb[$ky_cabang] = array(
                "label" => $label_cabang,
                "type" => "integer",
            );
        }
        $stok_cb['hd'] = array(
            "label" => "kuantitas total",
            "type" => "integer",
        );
        $headers = $header_mains + $stok_cb;

        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();
        $dataBersihSrcs = array_intersect_key($dataSrcs, $stokReadies);
        $databedaSrcs = array_diff_key($stokReadies, $dataSrcs);

        foreach ($dataBersihSrcs as $pId => $itemffs) {
            $no++;
            $stok_active = isset($stocks[$cabangID][$pId]['jumlah']) ? $stocks[$cabangID][$pId]['jumlah'] : 0;
            $stok_hold = isset($stocksHold[$cabangID][$pId]['jumlah']) ? $stocksHold[$cabangID][$pId]['jumlah'] : 0;
            $stok_booking = isset($totalBooking[$cabangID][$pId]) ? $totalBooking[$cabangID][$pId] : 0;
            $stok_total = $stok_hold + $stok_active + $stok_booking;

            $harga_hpp = isset($hargas[$cabangID][$pId]['hpp']['nilai']) ? $hargas[$cabangID][$pId]['hpp']['nilai'] : 0;
            $harga_jual = isset($hargas[$cabangID][$pId]['jual']['nilai']) ? $hargas[$cabangID][$pId]['jual']['nilai'] : 0;
            $harga_jual_nppn = isset($hargas[$cabangID][$pId]['jual_nppn']['nilai']) ? $hargas[$cabangID][$pId]['jual_nppn']['nilai'] : 0;

            $dataSpec['pid'] = $pId;
            $dataSpec['kode'] = isset($itemffs->kode) ? $itemffs->kode : "-";
            $dataSpec['barcode'] = isset($itemffs->barcode) ? $itemffs->barcode : "-";
            $dataSpec['nama'] = isset($itemffs->nama) ? $itemffs->nama : "";
            $dataSpec['kategori'] = isset($itemffs->kategori_nama) ? $itemffs->kategori_nama : "";

            //size,kapasitas,tipe,series,merek
            $dataSpec['size'] = isset($itemffs->size_nama) ? $itemffs->size_nama : "";
            $dataSpec['kapasitas'] = isset($itemffs->kapasitas_nama) ? $itemffs->kapasitas_nama : "";
            $dataSpec['tipe'] = isset($itemffs->tipe_nama) ? $itemffs->tipe_nama : "";
            $dataSpec['series'] = isset($itemffs->series_nama) ? $itemffs->series_nama : "";
            $dataSpec['merek'] = isset($itemffs->merek_nama) ? $itemffs->merek_nama : "";

            $dataSpec['label'] = isset($itemffs->label) ? $itemffs->label : "";
            $dataSpec['folder'] = isset($itemffs->folders_nama) ? $itemffs->folders_nama : "";
            $dataSpec['jenis'] = isset($itemffs->jenis) ? $itemffs->jenis : "";
            $dataSpec['satuan'] = isset($itemffs->satuan) ? $itemffs->satuan : "";

            $dataSpec['stok_active'] = $stok_active;
            $dataSpec['stok_booking'] = $stok_booking;
            $dataSpec['stok_hold'] = $stok_hold;
            $dataSpec['stok_total'] = $stok_total;
            $dataSpec['hpp'] = $harga_hpp * 1;
            $dataSpec['jual'] = $harga_jual * 1;
            $dataSpec['jual_nppn'] = $harga_jual_nppn * 1;

            //stok
            $item_stok_holding = 0;

            $stokCabang = isset($stokReadies[$pId]) ? $stokReadies[$pId] : array();
            foreach ($cbg_nama as $cid => $item) {
                $dataSpec[$cid] = isset($stokCabang[$cid]) ? $stokCabang[$cid] : 0;
                $item_stok_holding += isset($stokCabang[$cid]) ? $stokCabang[$cid] : 0;
            }
            if (!isset($dataSpec['hd'])) {
                $dataSpec['hd'] = 0;
            }
            $dataSpec['hd'] = $item_stok_holding;
            $datas[] = (object)$dataSpec;
        }
        // endregion

        //check header
        foreach ($dataSpec as $k => $aaaa) {
            if (!$this->db->field_exists($k, 'produk_katalog')) {
                $alterTmp = $this->db->query("ALTER TABLE produk_katalog ADD COLUMN `$k` VARCHAR(255)");
            }
        }
        $this->db->insert_batch("produk_katalog", $datas);

    }

    public function writeKatalog()
    {
        $this->db->trans_start();
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlLockerStockBooking");
        $ls = new MdlLockerStockBooking();
        $tr = new MdlTransaksi();
        $tr->addFilter("jenis='5822so'");
        $tr->addFilter("link_id='0'");
        $tr->addFilter("transaksi_data.valid_qty>0");
        $tmpHist = $tr->lookupJoined_OLD()->result();
        $dtaProduks = array();
        $trID = array();
        $this->db->truncate($ls->getTableName());
        if (count($tmpHist) > 0) {
            foreach ($tmpHist as $tmpHist_0) {
                $dtaProduks[$tmpHist_0->transaksi_id][$tmpHist_0->produk_id] = array(
                    "qty" => $tmpHist_0->valid_qty,
                    "nama" => $tmpHist_0->produk_nama,
                );
                $trID[$tmpHist_0->transaksi_id] = $tmpHist_0->nomer;
            }
        }
        if (count($dtaProduks) > 0) {
            foreach ($dtaProduks as $trIDs => $produksQty) {
                foreach ($produksQty as $produk_id => $produk_qty) {
                    $insert = array(
                        "transaksi_id" => "$trIDs",
                        "state" => "hold",
                        "jumlah" => $produk_qty["qty"],
                        "produk_id" => $produk_id,
                        "nama" => $produk_qty["nama"],
                        "jenis" => "booking",
                        "jenis_locker" => "stock",
                        "cabang_id" => "1",
                        "gudang_id" => "-10",
                    );
                    $ls->setFilters(array());
                    $ls->addData($insert) or matiHEre("gagal insert");
                    cekMErah($this->db->last_query());
                }
            }
        }
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
    }

    public function katalogproduksales()
    {
        $params = blobDecode($this->uri->segment(3));
        $mdlName = $params['mdl'];
        $mdlFifo = $params['fifo'];
        $cabangID = $cabangid = $params['cabang_id'];
        // arrPrint($params);
        $mdlHarga = "MdlHargaProduk";

        $this->load->model("Mdls/$mdlName");
        // $this->load->model("Coms/$mdlName");
        $this->load->model("Mdls/$mdlFifo");
        $this->load->model("Mdls/$mdlHarga");
        $md = new $mdlName();
        $ff = new $mdlFifo();
        $ha = new $mdlHarga();

        $tmps_1 = $md->lookupAll();
        // showLast_query("lime");
        $dataSrcs_0 = $tmps_1->result();
        // arrPrint($dataSrcs);
        $dataSrcs = array();
        foreach ($dataSrcs_0 as $dataSrc) {
            $dataSrcs[$dataSrc->id] = $dataSrc;
        }

        $where_2 = array(
            "cabang_id" => $cabangid
        );
        $this->db->where($where_2);
        $tmps_2 = $ff->lookupAll()->result();
        // showLast_query("orange");
        // arrPrint($tmps_2);
        foreach ($tmps_2 as $item_2) {

            // if (!isset($dataFifo[$item_2->produk_id])) {
            //     $dataFifo[$item_2->produk_id] = 0;
            // }
            // $dataFifo[$item_2->produk_id] += $item_2->unit;

            // $dataFifo[$item_2->produk_id][] = array(
            //     "unit" => $item_2->unit,
            //     "hpp" => $item_2->hpp,
            // );

            if (!isset($dataFifo[$item_2->produk_id][$item_2->hpp])) {
                $dataFifo[$item_2->produk_id][$item_2->hpp] = 0;
            }
            $dataFifo[$item_2->produk_id][$item_2->hpp] += $item_2->unit;

        }
        // arrPrintWebs($dataFifo);
        //         matiHere();
        $where_30 = array(
            "jenis_value" => "jual"
        );
        $where_3 = $where_2 + $where_30;
        $this->db->where($where_3);
        $hargas_0 = $ha->lookupAll()->result();
        // showLast_query("lime");
        // arrPrint($hargas_0);
        foreach ($hargas_0 as $harga_0) {
            $hargas[$harga_0->produk_id] = $harga_0->nilai;
        }

        // matiHere();
        // locker
        $this->load->model("Mdls/MdlLockerStock");
        $st = new MdlLockerStock();
        $sthold = new MdlLockerStock();

        $kolom_2s = array(
            "cabang_id",
            "produk_id",
            "jumlah",
            "gudang_id",
        );
        $inCabId = array();
        //region locker aktive
        $inGid = array("-250", "-210", "-10", "-1", "-260", "-270", "-280", "-290", "-300", "-310");//ini ditembak dulu sambil nyari cara auto get gudang default

        if ($cabangID > 0) {
            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }
        else {
            $inCabId = array("25", "21", "1", "-1", "26", "27", "28", "29", "30", "31");
            $inGid = array("-250", "-210", "-10", "-1", "-260", "-270", "-280", "-290", "-300", "-310");//ini ditembak dulu sambil nyari cara auto get gudang default
            $st->setFilters(array());
            $st->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            //            $st->addFilter("jenis in ('produk', 'produk rakitan')");
            $st->addFilter("jenis='produk'");
            $st->addFilter("jenis_locker='stock'");
            $st->addFilter("state='active'");

            $sthold->setFilters(array());
            $sthold->addFilter("gudang_id in ('" . implode("','", $inGid) . "')");
            $sthold->addFilter("jenis='produk'");
            $sthold->addFilter("jenis_locker='stock'");
            $sthold->addFilter("state='hold'");
            $sthold->addFilter("jumlah>0");
            $sthold->addFilter("transaksi_id>0");
        }
        $tmp_2s = $st->lookupAll()->result();
        // showLast_query("kuning");
        // cekKuning(sizeof($tmp_2s));
        // arrPrintPink($tmp_2s);
        $stocks = array();
        foreach ($tmp_2s as $temps) {
            //            arrprint($temps);
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;

                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocks[$cabang_id][$produk_id] = $tempDatas;
        }
        //endregion
        // cekPink($stocks);

        //region locket hold
        $tmp_2shold = $sthold->lookupAll()->result();
        // showLast_query("biru");
        // cekBiru(sizeof($tmp_2shold));
        // arrPrintPink($tmp_2shold);
        $stocksHold = array();
        $holdTrace = array();
        $totalHold = array();
        foreach ($tmp_2shold as $temps) {
            $tempDatas = array();
            foreach ($kolom_2s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $stocksHold[$cabang_id][$produk_id] = $tempDatas;
            $totalHold[$cabang_id][$produk_id] += $temps->jumlah;

            $holdTrace[$cabang_id][$produk_id] = array();
        }
        //endregion

        /* --------------------------------------------------
         * cabang
         * --------------------------------------------------*/
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $kolom_4s = array(
            "id",
            "nama",
        );
        $tmp_4s = $cb->lookupAll()->result();
        //region cabang
        $cabangs = array();
        foreach ($tmp_4s as $temps) {
            $tempDatas = array();
            foreach ($kolom_4s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $cabangs[$id] = $nama;
        }
        //endregion

        /* --------------------------------------------------
         * harga
         * --------------------------------------------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlHargaProdukRakitan");
        $this->load->model("Mdls/MdlHargaProdukKomposit");
        $hg = new MdlHargaProduk();
        $hgr = new MdlHargaProdukRakitan();
        $hgk = new MdlHargaProdukKomposit();
        $kolom_1s = array(
            "cabang_id",
            "produk_id",
            "jenis_value",
            "nilai",
        );
        $tmp_ss = $hg->lookupAll()->result();
        // cekHitam($this->db->last_query());
        // arrPrint($tmp_ss);
        $tmp_rr = $hgr->lookupAll()->result();
        $tmp_tt = $hgk->lookupAll()->result();
        //        $tmp_1s = $tmp_ss;
        $tmp_1s = array_merge($tmp_ss, $tmp_rr, $tmp_tt);
        //region hargas produks
        $hargas = array();
        // $cabangIdsflip = array();
        foreach ($tmp_1s as $temps) {
            $tempDatas = array();
            foreach ($kolom_1s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = (isset($temps->$kolom) || ($temps->$kolom == "")) ? $temps->$kolom : "-";
            }
            // $cabangIdsflip[$cabang_id] =1;
            $hargas[$cabang_id][$produk_id][$jenis_value] = $tempDatas;
        }
        // $cabangIds = array_keys($cabangIdsflip);
        // arrPrintHijau($hargas);
        //endregion
        // -------------------------------------------------------
        $cabangNama = $cabangs[$cabangID];
        $this->file = $this->uri->segment(2) . $cabangNama . " " . dtimeNow();
        // $this->load->helper('he_versi_history_old');
        $this->load->library('Excel');
        $ex = new Excel();
        $header_mains = array(
            // "no"          => array(
            //     "label" => "No",
            //     "type"  => "integer",
            // ),
            "pid" => array(
                "label" => "pID",
                "type" => "integer",
            ),

            "kode" => array(
                "label" => "item no",
                "type" => "string",
            ),
            "no_part" => array(
                "label" => "part no",
                "type" => "string",
            ),
            "nama" => array(
                "label" => "description",
                "type" => "string",
            ),
            "kategori" => array(
                "label" => "kategori",
                "type" => "string",
            ),
            "satuan" => array(
                "label" => "stuan",
                "type" => "string",
            ),
        );
        $header_pusat = array();
        if ($cabangID == '-1') {
            $header_pusat = array(
                "hpp" => array(
                    "label" => "HPP",
                    "type" => "integer",
                ),

            );
        }
        $header_umum = array(
            "jual" => array(
                "label" => "Harga Jual",
                "type" => "integer",
            ),
            "jual_nppn" => array(
                "label" => "Harga Jual + ppn",
                "type" => "integer",
            ),

            "stok_active" => array(
                "label" => "Stock aktive",
                "type" => "integer",
            ),
            "stok_hold" => array(
                "label" => "Stock intransit",
                "type" => "integer",
            ),
            "stok_total" => array(
                "label" => "Stock total",
                "type" => "integer",
            ),
        );

        $headers = $header_mains + $header_pusat + $header_umum;
        // if (ipadd() == MGK_LIVE) {
        //     arrPrintHijau($headers);
        //     mati_disini();
        // }


        // region pairing data
        $no = 0;
        $datas = array();
        $dataSpec = array();

        // cekPink($cabangID);
        foreach ($dataSrcs as $pId => $itemffs) {
            // foreach ($itemffs as $hpp => $stok) {
            $no++;
            $stok_active = isset($stocks[$cabangID][$pId]['jumlah']) ? $stocks[$cabangID][$pId]['jumlah'] : 0;
            $stok_hold = isset($stocksHold[$cabangID][$pId]['jumlah']) ? $stocksHold[$cabangID][$pId]['jumlah'] : 0;
            $stok_total = $stok_hold + $stok_active;

            $harga_hpp = isset($hargas[$cabangID][$pId]['hpp']['nilai']) ? $hargas[$cabangID][$pId]['hpp']['nilai'] : 0;
            $harga_jual = isset($hargas[$cabangID][$pId]['jual']['nilai']) ? $hargas[$cabangID][$pId]['jual']['nilai'] : 0;
            $harga_jual_nppn = isset($hargas[$cabangID][$pId]['jual_nppn']['nilai']) ? $hargas[$cabangID][$pId]['jual_nppn']['nilai'] : 0;

            $dataSpec['pid'] = $pId;
            $dataSpec['kode'] = isset($itemffs->kode) ? $itemffs->kode : "-";
            $dataSpec['no_part'] = isset($itemffs->no_part) ? $itemffs->no_part : "-";
            $dataSpec['nama'] = isset($itemffs->nama) ? $itemffs->nama : "";
            $dataSpec['kategori'] = isset($itemffs->kategori_nama) ? $itemffs->kategori_nama : "";
            $dataSpec['label'] = isset($itemffs->label) ? $itemffs->label : "";
            $dataSpec['folder'] = isset($itemffs->folders_nama) ? $itemffs->folders_nama : "";
            $dataSpec['jenis'] = isset($itemffs->jenis) ? $itemffs->jenis : "";
            $dataSpec['satuan'] = isset($itemffs->satuan) ? $itemffs->satuan : "";

            $dataSpec['stok_active'] = $stok_active;
            $dataSpec['stok_hold'] = $stok_hold;
            $dataSpec['stok_total'] = $stok_total;
            $dataSpec['hpp'] = $harga_hpp * 1;
            $dataSpec['jual'] = $harga_jual * 1;
            $dataSpec['jual_nppn'] = $harga_jual_nppn * 1;

            $datas[] = (object)$dataSpec;
            // }
        }
        // endregion

        // arrPrintWebs($datas);
        // mati_disini();

        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();

    }

    public function katalogFilter()
    {

        $data = $this->db->get("produk_katalog")->result();
        $arrKategori = array();
        $arrSize = array();
        $arrKapasitas = array();
        $arrTipe = array();
        $arrRefrigerant = array();
        $arrMerek = array();

        if (!empty($data)) {
            foreach ($data as $l => $dt) {
                $arrKategori[$dt->kategori] = array(
                    "text" => $dt->kategori,
                    "value" => $dt->kategori
                );
                $arrSize[$dt->size] = array(
                    "text" => $dt->size,
                    "value" => $dt->size
                );
                $arrKapasitas[$dt->kapasitas] = array(
                    "text" => $dt->kapasitas,
                    "value" => $dt->kapasitas
                );
                $arrTipe[$dt->tipe] = array(
                    "text" => $dt->tipe,
                    "value" => $dt->tipe
                );
                $arrRefrigerant[$dt->folder] = array(
                    "text" => $dt->folder,
                    "value" => $dt->folder
                );
                $arrMerek[$dt->merek] = array(
                    "text" => $dt->merek,
                    "value" => $dt->merek
                );
            }
            $listFilter = array(
                "kategori" => $arrKategori,
                "size" => $arrSize,
                "kapasitas" => $arrKapasitas,
                "tipe" => $arrTipe,
                "refrigerant" => $arrRefrigerant,
                "merek" => $arrMerek,
            );
        }

        $view = "";
        $view .= "<div class='container-fluid'>";
        $view .= "<div style='margin-bottom: 4px;' class='col-md-6'>";
        foreach ($listFilter as $label => $list) {
            $view .= "<div class='row'>";
            $view .= "<div style='margin-bottom: 4px;' class='col-md-3'><label>$label</label>";
            $view .= "</div>";
            $view .= "<div style='margin-bottom: 4px;' class='col-md-3'>";
            $view .= "<select
                data-style='btn-primary btn-xs'
                data-live-search='true'
                title='$label'
                data-headers='$label'
                data-size='10'
                data-container='body'
                type='int'
                name='$label' id='$label' class='$label db_filter selectpicker select2 show-tick'
                onchange=\"terapFilter()\" tabindex='-1'>";

            foreach ($list as $a => $rr) {
                $view .= "<option value='" . $rr['value'] . "'>" . $rr['value'] . "</option>";
            }
            $view .= "</select>";
            $view .= "</div>";
            $view .= "</div>";

            $view .= "<script>
                        top.$('#$label').selectpicker({
                            placeholder: 'pilih $label',
                        });
                    </script>";
        }

        $view .= "<div onclick=\"clearSelect()\" class='btn btn-sm btn-warning pull-right'><i class='fa fa-clear'></i>reset pilihan</div>";

        $view .= "</div>";

        $view .= "<div style='margin-bottom: 4px;' class='col-md-6'>";
        $view .= "<div onclick=\"download_json()\" class='btn btn-md btn-success'><i class='fa fa-download'></i>DOWNLOAD</div>";
        $view .= "<div onclick=\"refreshData()\" class='btn btn-md btn-warning pull-right'><i class='fa fa-reload'></i>PERBARUI DATA</div>";
        $view .= "</div>";

        $dtimeNow = dtimeNow();

        $view .= "<div class='row'>";
        $view .= "<div class='col-md-12'>
            <table id='katalogKategori' class='display compact' style='width:100%'>
                <caption class='fa-2x bg-yellow text-center'> STOK LEBIH RINCI SILAHKAN <span onclick=\"download_json()\" class='text-link'>DOWNLOAD</span> EXCEL </caption>
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>Nama</th>
                        <th>Merek</th>
                        <th>Kategori</th>
                        <th>Size</th>
                        <th>Kapasitas</th>
                        <th>Tipe</th>
                        <th>Refrigerant</th>
                        <th>AvailStok</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No. </th>
                        <th>Nama</th>
                        <th>Merek</th>
                        <th>Kategori</th>
                        <th>Size</th>
                        <th>Kapasitas</th>
                        <th>Tipe</th>
                        <th>Refrigerant</th>
                        <th>AvailStok</th>
                    </tr>
                </tfoot>
            </table>
        </div>";
        $view .= "</div>";

        $view .= "</div>";
        $view .= "<script>

            var tables = $('#katalogKategori').DataTable({
                ajax: {
                    url: '" . base_url() . "ExcelWriter/tableKatalog',
                    type: 'POST'
                },
                processing: true,
                serverSide: true
            });

            function refreshData(){
                top.$('#result').load('" . base_url() . "ExcelWriter/katalogToTable', function(){
                    top.swal('data berhasil di perbaharui, silahkan download');
                });
            }
            
            function clearSelect(){
                $('#kategori').selectpicker('val', '');
                $('#size').selectpicker('val', '');
                $('#kapasitas').selectpicker('val', '');
                $('#tipe').selectpicker('val', '');
                $('#refrigerant').selectpicker('val', '');
                $('#merek').selectpicker('val', '');

                terapFilter()
            }

            function terapFilter(){
                var listingFilter = $('select.db_filter :checked');
                var send = {}
                jQuery.each(listingFilter, function(a, b){
                    var keys = $(b).parent().attr('id');
                    var val = $(b).val();
                    if(val!=''){
                        send[keys] = val
                    }
                });
                tables.ajax.url( '" . base_url() . "ExcelWriter/tableKatalog?q='+btoa(JSON.stringify(send))).load();
            }

            function download_json(){
                var listingFilter = $('select.db_filter :checked');
                var send = {}
                jQuery.each(listingFilter, function(a, b){
                    var keys = $(b).parent().attr('id');
                        keys = keys == 'refrigerant' ? 'folder' : keys;
                    var val = $(b).val();
                    if(val!=''){
                        send[keys] = val
                    }
                })

                var fileName = 'stokgudang-'+''+JSON.stringify('" . dtimeNow() . "');
                var request = new XMLHttpRequest();
                request.open('POST', '" . base_url() . "ExcelWriter/download_json', true);
                request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                request.responseType = 'blob';

                request.onload = function(e) {
                if (this.status === 200) {
                    var blob = this.response;
                    if(window.navigator.msSaveOrOpenBlob) {
                        window.navigator.msSaveBlob(blob, fileName);
                    }
                    else{
                        var downloadLink = window.document.createElement('a');
                        var contentTypeHeader = request.getResponseHeader(\"Content-Type\");
                        downloadLink.href = window.URL.createObjectURL(new Blob([blob], { type: contentTypeHeader }));
                        downloadLink.download = fileName;
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                       }
                   }
               };

               let urlEncodedData = \"\", urlEncodedDataPairs = [], name;
                for( name in send ) {
                 urlEncodedDataPairs.push(encodeURIComponent(name)+'='+encodeURIComponent(send[name]));
                }

                request.setRequestHeader(\"Content-Type\", \"application/json;charset=UTF-8\");
                request.send(['q='+btoa(JSON.stringify(send))])

            }

        </script>";

        echo $view;
    }

    public function tableKatalog()
    {

        $arrQuery = isset($_GET['q']) ? json_decode(base64_decode($_GET['q']), 1) : array();
        $this->db->select("id,nama,merek,kategori,size,kapasitas,tipe,folder,hd");

        $this->db->limit($_POST['length'], $_POST['start'], $_POST['length']);

        if (!empty($arrQuery)) {
            foreach ($arrQuery as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }

        $this->db->order_by("hd", "desc");

        $data = $this->db->get("produk_katalog")->result();
        $last_q = $this->db->last_query();
        $this->db->select('id');
        $query = $this->db->get('produk_katalog');
        $num = $query->num_rows();
        $tables = array();
        foreach ($data as $k => $arr) {
            $tables[] = array_values((array)$arr);
        }
        echo json_encode(array(
            //            "draw"=>1,
            "recordsTotal" => $num,
            "recordsFiltered" => $num,
            "data" => $tables,
            "post" => $_POST,
            "last_q" => $last_q,
        ));
    }

    public function download_json()
    {

        $arrQuery = isset($_POST['q']) ? json_decode(base64_decode($_POST['q']), 1) : array();

        /* --------------------------------------------------
         * cabang
         * --------------------------------------------------*/
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $kolom_4s = array(
            "id",
            "nama",
        );
        $tmp_4s = $cb->lookupAll()->result();
        //region cabang
        $cabangs = array();
        foreach ($tmp_4s as $temps) {
            $tempDatas = array();
            foreach ($kolom_4s as $kolom) {
                $$kolom = $temps->$kolom;
                $tempDatas[$kolom] = $temps->$kolom;
            }
            $cabangs[$id] = $nama;
        }
        //endregion

        /* ----------------------------------------------------
         * stok rekening
         * ----------------------------------------------------*/
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $sbk = new ComRekeningPembantuProduk();
        $sbk->addFilter("qty_debet>0");
        $dataSrcs_0 = $sbk->fetchBalances('1010030030');
        foreach ($dataSrcs_0 as $item_0) {
            $sbk_produk_id = $item_0->extern_id;
            $sbk_cabang_id = $item_0->cabang_id;
            $sbk_gudang_id = $item_0->gudang_id;
            $sbk_qty_debet = isset($item_0->qty_debet) ? $item_0->qty_debet : 0;
            // $stokReadies[$sbk_produk_id][$sbk_cabang_id][$sbk_gudang_id]['qty'] = $sbk_qty_debet;
            $stokReadies[$sbk_produk_id][$sbk_cabang_id . "|" . $sbk_gudang_id] = $sbk_qty_debet;
            $cbg_nama[$sbk_cabang_id . "|" . $sbk_gudang_id] = $cabangs[$sbk_cabang_id];
        }

        $cabangNama = $cabangs[$cabangID];
        $this->file = $this->uri->segment(2) . $cabangNama . " " . dtimeNow();

        $this->load->library('Excel');
        $ex = new Excel();
        $header_mains = array(
            // "no"          => array(
            //     "label" => "No",
            //     "type"  => "integer",
            // ),
            "pid" => array(
                "label" => "pID",
                "type" => "integer",
            ),

            // "kode"     => array(
            //     "label" => "SKU",
            //     "type"  => "string",
            // ),
            // "barcode"  => array(
            //     "label" => "barcode",
            //     "type"  => "string",
            // ),
            "nama" => array(
                "label" => "Nama produk",
                "type" => "string",
            ),
            "kategori" => array(
                "label" => "satuan",
                "type" => "string",
            ),
            "size" => array(
                "label" => "size",
                "type" => "string",
            ),
            "kapasitas" => array(
                "label" => "kapasitas",
                "type" => "string",
            ),
            "tipe" => array(
                "label" => "tipe",
                "type" => "string",
            ),
            "series" => array(
                "label" => "series",
                "type" => "string",
            ),
            "merek" => array(
                "label" => "merek",
                "type" => "string",
            ),
            // "satuan"   => array(
            //     "label" => "stuan",
            //     "type"  => "string",
            // ),
        );
        $header_pusat = array();
        if ($cabangID == '-1') {
            $header_pusat = array(
                "hpp" => array(
                    "label" => "HPP",
                    "type" => "integer",
                ),
            );
        }
        $header_umum = array(
            "jual" => array(
                "label" => "Harga Jual",
                "type" => "integer",
            ),
            "jual_nppn" => array(
                "label" => "Harga Jual + ppn",
                "type" => "integer",
            ),
            "stok_active" => array(
                "label" => "Stock aktive",
                "type" => "integer",
            ),
            "stok_hold" => array(
                "label" => "Stock intransit",
                "type" => "integer",
            ),
            "stok_booking" => array(
                "label" => "Stock booking",
                "type" => "integer",
            ),
            "stok_total" => array(
                "label" => "Stock total",
                "type" => "integer",
            ),
        );
        foreach ($cbg_nama as $ky_cabang => $label_cabang) {
            $stok_cb[$ky_cabang] = array(
                "label" => $label_cabang,
                "type" => "integer",
            );
        }
        $stok_cb['hd'] = array(
            "label" => "kuantitas total",
            "type" => "integer",
        );
        // $headers = $header_mains + $header_pusat + $header_umum + $stok_cb;
        $headers = $header_mains + $stok_cb;

        $this->db->select(implode(",", array_keys($headers)));

        if (!empty($arrQuery)) {
            foreach ($arrQuery as $key => $val) {
                $this->db->where_in($key, $val);
            }
        }

        $dataBersihSrcs = $this->db->get("produk_katalog")->result();
        $datas = array();
        foreach ($dataBersihSrcs as $k => $dataSpec) {
            $datas[] = $dataSpec;
        }
        // endregion

        $rowData = array(
            "CV. Everest Jaya Elektronik",
            "Kuantitas Stok Gudang",
            dtimeNow(),
        );
        $ex->setRowContent($rowData);
        // $ex->setRowContent("dadadad");
        $ex->setTitleFile($this->file);
        $ex->setDatas($datas);
        $ex->setHeaders($headers);

        return $ex->writer();
    }
}