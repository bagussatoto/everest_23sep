<?php


class ToolCekNew extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->load->helper("he_angka");
    }

    function index()
    {
//        $arrTools = array(
//            "kas" => "viewUnsyncedKas",
//            "produk" => "viewUnsyncedProduk",
//            "produk rakitan" => "viewUnsyncedProdukRakitan",
//            "supplies" => "viewUnsyncedSupplies",
//            "valas" => "viewUnsyncedValas",
//        );

//        foreach ($arrTools as $key => $value) {
//            echo "<div>";
//            echo "<h3>";
//            echo "<a href='" . base_url() . get_class($this) . "/$value' target='_blank'>:: $key ::</a>";
//            echo "</h3>";
//            echo "</div>";
//        }
    }

    public function cekRebateSupplier()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlSupplierDiskon");
        $this->load->model("Mdls/MdlLockerStockDiskonVendor");
        $this->load->model("Coms/ComRekeningPembantuPiutangSupplierDetailTransItem");

        //-----
        $tbl_mutasi = "__rek_pembantu_piutangsupplier__1010020030";
        $supplier_id = "4";
        $date1 = "2024-01-01";
        $date2 = "2026-12-31";
        $arrHeader = array(
            "id" => "trid",
            "dtime" => "dtime",
            "referenceNomer__2" => "nomer po",
            "nomer" => "nomer grn",
            "suppliers_id" => "ID supplier",
            "suppliers_nama" => "supplier",
//            "nilai_diskonpo" => array(
//                "label" => "PO rebate",
//                "format" => "debet",
//            ),
//            "nilai_diskonpo_freeproduk" => array(
//                "label" => "PO rebate<br>(freeproduk)",
//                "format" => "debet",
//            ),
            "nilai" => array(
                "label" => "GRN rebate",
                "format" => "debet",
            ),
            "nilai_freeproduk" => array(
                "label" => "GRN rebate<br>(freeproduk)",
                "format" => "debet",
            ),
//            "rek_diskon_masuk" => array(
//                "label" => "rek rebate",
//                "format" => "debet",
//            ),

//            "nilai_grn_batal" => array(
//                "label" => "GRN rebate<br>(BATAL)",
//                "format" => "debet",
//            ),
            "nilai_piutang" => array(
                "label" => "diklaim",
                "format" => "debet",
            ),
//            "nilai_piutang_batal" => array(
//                "label" => "diklaim<br>(BATAL)",
//                "format" => "debet",
//            ),
            "belum_diklaim" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "belum diklaim",
                "format" => "debet",
            ),
            "rek_realisasi_klaim" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "rek_realisasi_klaim",
                "format" => "debet",
            ),
//            "selisih_plus" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "selisih plus",
//                "format" => "debet",
//            ),
            //-------
//            "nilai_persediaan" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "persediaan",
//                "format" => "debet",
//            ),
//            "nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note",
//                "format" => "debet",
//            ),
//            "nilai_voucher" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "voucher",
//                "format" => "debet",
//            ),
//            "nilai_cash" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "kas",
//                "format" => "debet",
//            ),
//            "nilai_logam_mulia" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "logam mulia",
//                "format" => "debet",
//            ),
//            "nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka",
//                "format" => "debet",
//            ),
            //-------
//            "new_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note<br>REVISI",
//                "format" => "debet",
//            ),
//            "new_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka<br>REVISI",
//                "format" => "debet",
//            ),
            //-------
//            "adj_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note<br>ADJ",
//                "format" => "debet",
//            ),
//            "adj_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka<br>ADJ",
//                "format" => "debet",
//            ),
            "nilai_locker_diskon" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "locker_diskon",
                "format" => "debet",
            ),
            "nilai_locker_diskon_diklaim" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "locker_diskon_diklaim",
                "format" => "debet",
            ),
        );

        //region data diskon-----
        $ds = New MdlSupplierDiskon();
        $dsTmp = $ds->lookupAll()->result();
        foreach ($dsTmp as $dsSpec) {
            $arrDiskonData[$dsSpec->id] = $dsSpec->label;
        }
        //endregion-----

        // region loacker diskon
        $arrLockerDiskon = array();
        $ldd = New MdlLockerStockDiskonVendor();
        if ($supplier_id > 0) {
            $ldd->addFilter("supplier_id='$supplier_id'");
        }
        $ldd->addFilter("fulldate>='$date1'");
        $ldd->addFilter("fulldate<='$date2'");
        $ldd->addFilter("state='active'");
        $lddTmp = $ldd->lookupAll()->result();
        if (sizeof($lddTmp) > 0) {
            foreach ($lddTmp as $lddSpec) {
                if (!isset($arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon"])) {
                    $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon"] = 0;
                }
                if (!isset($arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon_diklaim"])) {
                    $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon_diklaim"] = 0;
                }
                $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon"] += $lddSpec->nilai;
                $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon_diklaim"] += $lddSpec->nilai_diklaim;
                $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon_total"] += ($lddSpec->nilai + $lddSpec->nilai_diklaim);
            }
        }
        // endregion loacker diskon

        // region transaksi grn 467,3344,4643
        $diskonKlaimMasuk = array();
        $arrJenisTrDiskon = array();
        $arrTrIDs = array();
        $jenisTr = array(
            "467",
            "4643",
            "3344",
        );
        $date = isset($_GET["date"]) ? $_GET["date"] : "2025-05";
        $date_ex = explode("-", $date);
        $month = isset($date_ex[1]) ? $date_ex[1] : date("m");
        $year = isset($date_ex[0]) ? $date_ex[0] : date("Y");
        $tr = New MdlTransaksi();
//        $tr->addFilter("jenis='$jenisTr'");
        if ($supplier_id > 0) {
            $tr->addFilter("suppliers_id='$supplier_id'");
        }
        $tr->addFilter("jenis in ('" . implode("','", $jenisTr) . "')");
//        $tr->addFilter("month(dtime)='$month'");
//        $tr->addFilter("year(dtime)='$year'");
        $tr->addFilter("date(dtime)>='$date1'");
        $tr->addFilter("date(dtime)<='$date2'");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trid = $trSpec->id;// id GRN
                $trash_4 = $trSpec->trash_4;
                $jenis = $trSpec->jenis;
                $idsHis = ($trSpec->ids_his != null) ? blobDecode($trSpec->ids_his) : array();
                if (sizeof($idsHis) > 0) {
                    foreach ($idsHis as $step_his => $data_his) {
                        if ($step_his == 1) {
                            $subCounters = blobDecode($data_his["counters"]);
                            $countStepCode = 0;
                            foreach ($subCounters["stepCode"] as $cc => $cct) {
                                $countStepCode = $cct;
                            }
                            $arrTransaksi[$trid]['referenceID'] = $data_his["trID"];
                            $arrTransaksi[$trid]['referenceNumber'] = $data_his["nomer"];
                            $arrTransaksi[$trid]['referenceNomer'] = $data_his["nomer"];
                            $arrTransaksi[$trid]['referenceDtime'] = $data_his["dtime"];
                            $arrTransaksi[$trid]['referenceFulldate'] = $data_his["fulldate"];
                            $arrTransaksi[$trid]['referenceCount'] = $countStepCode;
                        }
                        $arrTransaksi[$trid]['referenceID__' . $step_his] = $data_his["trID"];
                        $arrTransaksi[$trid]['referenceNumber__' . $step_his] = $data_his["nomer"];
                        $arrTransaksi[$trid]['referenceNomer__' . $step_his] = $data_his["nomer"];
                        $arrTransaksi[$trid]['referenceDtime__' . $step_his] = $data_his["dtime"];
                        $arrTransaksi[$trid]['referenceFulldate__' . $step_his] = $data_his["fulldate"];
                    }
                }

                $arrTrIDs[$trid] = $trid;
                foreach ($arrHeader as $key => $val) {
                    if (!isset($arrTransaksi[$trid][$key])) {
                        $arrTransaksi[$trid][$key] = isset($trSpec->$key) ? $trSpec->$key : "";
                    }
                }
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, items, items2_sum, main");
                $trreg->addFilter("transaksi_id='$trid'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $items = blobDecode($tmpReg[0]->items);
                $items2_sum = blobDecode($tmpReg[0]->items2_sum);
                $main = blobDecode($tmpReg[0]->main);
                switch ($jenis) {
                    case "467":
                        foreach ($items as $pid => $pSpec) {
                            foreach ($arrDiskonData as $diskon_id => $diskon_label) {
                                $new_key_id = "diskon_" . $diskon_id . "_id";
                                $new_key_nilai = "sub_diskon_" . $diskon_id . "_nilai";

                                $arrDataLocker[$trid][$diskon_id]["id"] = isset($pSpec[$new_key_id]) ? $pSpec[$new_key_id] : 0;
                                if (!isset($arrDataLocker[$trid][$diskon_id]["nilai"])) {
                                    $arrDataLocker[$trid][$diskon_id]["nilai"] = 0;
                                }
                                $arrDataLocker[$trid][$diskon_id]["nilai"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
                                if (!isset($arrDataLockerTotal[$trid]["nilai"])) {
                                    $arrDataLockerTotal[$trid]["nilai"] = 0;
                                }
                                $arrDataLockerTotal[$trid]["nilai"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;

                                if (!isset($arrJenisTrDiskon[$jenis])) {
                                    $arrJenisTrDiskon[$jenis] = 0;
                                }
                                $arrJenisTrDiskon[$jenis] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;

                                if ($trash_4 == 1) {
                                    $arrDataLockerBatal[$trid][$diskon_id]["id"] = isset($pSpec[$new_key_id]) ? $pSpec[$new_key_id] : 0;
                                    if (!isset($arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"])) {
                                        $arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"] = 0;
                                    }
                                    $arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
                                    if (!isset($arrDataLockerTotalBatal[$trid]["nilai_grn_batal"])) {
                                        $arrDataLockerTotalBatal[$trid]["nilai_grn_batal"] = 0;
                                    }
                                    $arrDataLockerTotalBatal[$trid]["nilai_grn_batal"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
//                            cekMerah("MASUK DISINI... [$trid]");
                                }

                            }

                        }
                        break;
                    case "4643":
                        $arrTransaksi[$trid]["referenceID__2"] = $main["referensi_so"];
                        $arrTransaksi[$trid]["referenceNomer__2"] = $main["referensi_so__nomer"];
                        foreach ($items2_sum as $pid => $pSpec) {
                            foreach ($arrDiskonData as $diskon_id => $diskon_label) {
                                if ($pSpec["diskon_id"] == $diskon_id) {
                                    $new_key_id = "diskon_id";
                                    $new_key_nilai = "sub_diskon_nilai";

                                    $arrDataLocker[$trid][$diskon_id]["id"] = isset($pSpec[$new_key_id]) ? $pSpec[$new_key_id] : 0;
                                    if (!isset($arrDataLocker[$trid][$diskon_id]["nilai"])) {
                                        $arrDataLocker[$trid][$diskon_id]["nilai"] = 0;
                                    }
                                    $arrDataLocker[$trid][$diskon_id]["nilai"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
                                    if (!isset($arrDataLockerTotal[$trid]["nilai"])) {
                                        $arrDataLockerTotal[$trid]["nilai"] = 0;
                                    }
                                    $arrDataLockerTotal[$trid]["nilai"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;

                                    if (!isset($arrJenisTrDiskon[$jenis])) {
                                        $arrJenisTrDiskon[$jenis] = 0;
                                    }
                                    $arrJenisTrDiskon[$jenis] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;


                                    if ($trash_4 == 1) {
                                        $arrDataLockerBatal[$trid][$diskon_id]["id"] = isset($pSpec[$new_key_id]) ? $pSpec[$new_key_id] : 0;
                                        if (!isset($arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"])) {
                                            $arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"] = 0;
                                        }
                                        $arrDataLockerBatal[$trid][$diskon_id]["nilai_grn_batal"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;
                                        if (!isset($arrDataLockerTotalBatal[$trid]["nilai_grn_batal"])) {
                                            $arrDataLockerTotalBatal[$trid]["nilai_grn_batal"] = 0;
                                        }
                                        $arrDataLockerTotalBatal[$trid]["nilai_grn_batal"] += isset($pSpec[$new_key_nilai]) ? $pSpec[$new_key_nilai] : 0;

                                    }
                                }

                            }

                        }
                        break;
                    case "3344":
                        $arrDataLockerTotal[$trid]["nilai"] = isset($main["nilai_piutang"]) ? $main["nilai_piutang"] : 0;
                        if (!isset($arrJenisTrDiskon[$jenis])) {
                            $arrJenisTrDiskon[$jenis] = 0;
                        }
                        $arrJenisTrDiskon[$jenis] += isset($main["nilai_piutang"]) ? $main["nilai_piutang"] : 0;
                        break;
                }


                $arrTransaksi[$trid]["nilai_freeproduk"] = isset($main["produk_rel_harga"]) ? $main["produk_rel_harga"] : 0;
//                break;

                $tridpo = $arrTransaksi[$trid]['referenceID__2'];
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, items, items2_sum, main");
                $trreg->addFilter("transaksi_id='$tridpo'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $mainpo = blobDecode($tmpReg[0]->main);
//                $arrTransaksi[$trid]["nilai_diskonpo"] = isset($mainpo["diskon_nilai"]) ? $mainpo["diskon_nilai"] : 0;
                $arrTransaksi[$trid]["nilai_diskonpo"] = isset($mainpo["diskon_nilai_total"]) ? $mainpo["diskon_nilai_total"] : 0;
                $arrTransaksi[$trid]["nilai_diskonpo_freeproduk"] = isset($mainpo["produk_rel_harga"]) ? $mainpo["produk_rel_harga"] : 0;

            }

            if (sizeof($arrTrIDs) > 0) {
                $this->db->from($tbl_mutasi);
                $this->db->where_in("transaksi_id", $arrTrIDs);
                $query = $this->db->get()->result();
//                showLast_query("biru");
                foreach ($query as $qspec) {
                    if (!isset($diskonKlaimMasuk[$qspec->transaksi_id])) {
                        $diskonKlaimMasuk[$qspec->transaksi_id] = 0;
                    }
                    $diskonKlaimMasuk[$qspec->transaksi_id] += $qspec->debet;
                }
            }

        }
        // endregion transaksi grn 467


        // region transaksi klaim 3333
        $arrIniJenisDiKlaim = array();
        $arrIniTridKlaim = array();
        $arrIniTridYangDiKlaim = array();
        $arrKlaim = array();
        $arrKlaimBatal = array();
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='3333'");
        if ($supplier_id > 0) {
            $tr->addFilter("suppliers_id='$supplier_id'");
        }
        $tr->addFilter("reference_id in ('" . implode("','", $arrTrIDs) . "')");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $jenis = $trSpec->jenis;
                $ini_trid = $trSpec->id;
                $trash_4 = $trSpec->trash_4;

                $reference_id = $trSpec->reference_id;
                $reference_jenis = $trSpec->reference_jenis;
                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, main");
                $trreg->addFilter("transaksi_id='$ini_trid'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);
                $arrKlaim[$reference_id] = array(
                    "nilai_persediaan" => $main["nilai_persediaan"],
                    "nilai_piutang" => $main["nilai_piutang"],
                    "nilai_credit_note" => $main["nilai_credit_note"],
                    "nilai_voucher" => $main["nilai_voucher"],
                    "nilai_cash" => $main["nilai_cash"],
                    "nilai_logam_mulia" => $main["nilai_logam_mulia"],
                    "nilai_pph23" => $main["nilai_pph23"],
                );
                if ($trash_4 == 1) {
                    $arrKlaimBatal[$reference_id] = array(
                        "nilai_persediaan_batal" => $main["nilai_persediaan"],
                        "nilai_piutang_batal" => $main["nilai_piutang"],
                        "nilai_credit_note_batal" => $main["nilai_credit_note"],
                        "nilai_voucher_batal" => $main["nilai_voucher"],
                        "nilai_cash_batal" => $main["nilai_cash"],
                        "nilai_logam_mulia_batal" => $main["nilai_logam_mulia"],
                        "nilai_pph23_batal" => $main["nilai_pph23"],
                    );
                }

                $arrIniTridKlaim[] = $ini_trid;// 3333
                $arrIniTridYangDiKlaim[$reference_id] = $ini_trid;
                if (!isset($arrIniJenisDiKlaim[$reference_jenis])) {
                    $arrIniJenisDiKlaim[$reference_jenis] = 0;
                }
                $arrIniJenisDiKlaim[$reference_jenis] += $main["nilai_piutang"];
            }

            if (sizeof($arrIniTridKlaim) > 0) {
                $this->db->from($tbl_mutasi);
                $this->db->where_in("transaksi_id", $arrIniTridKlaim);
                $query = $this->db->get()->result();
//                showLast_query("biru");
                foreach ($query as $qspec) {
                    if (!isset($realisasiKlaim[$qspec->transaksi_id])) {
                        $realisasiKlaim[$qspec->transaksi_id] = 0;
                    }
                    $realisasiKlaim[$qspec->transaksi_id] += $qspec->kredit;
                }
            }

        }
        // endregion transaksi klaim 3333


//        arrPrintWebs($arrJenisTrDiskon);
//        arrPrintPink($arrIniJenisDiKlaim);


        $this->db->trans_start();

        $arrSupplierCek = array();

        $str = "<table style='border:1px solid black;width:100%;' rules='all'>";

        $str .= "<tr>";
        $str .= "<th>1 | no.</th>";
        $noo = 1;
        foreach ($arrHeader as $key => $val) {
            $noo++;
            if (is_array($val)) {
                $str .= "<th>$noo | " . $val["label"] . "</th>";
            }
            else {
                $str .= "<th>$noo | $val</th>";
            }
        }
        $str .= "</tr>";

        if (sizeof($arrTransaksi) > 0) {
            $no = 0;
            foreach ($arrTransaksi as $trid => $trSpec) {
                $supplier_id = $trSpec["suppliers_id"];
                $supplier_nama = $trSpec["suppliers_nama"];
                $bgcolor = "";

                //----rebate dari items grn
                if (isset($arrDataLockerTotal[$trid])) {
                    foreach ($arrDataLockerTotal[$trid] as $aa => $bb) {
                        $trSpec[$aa] = $bb;
                    }
                }
                //----rebate dari items grn
                if (isset($arrDataLockerTotalBatal[$trid])) {
//                    $bgcolor = "#ff66ff";
                    foreach ($arrDataLockerTotalBatal[$trid] as $aa => $bb) {
                        $trSpec[$aa] = $bb;
                    }
                }
                //----rebate klaim
                if (isset($arrKlaim[$trid])) {
                    foreach ($arrKlaim[$trid] as $cc => $dd) {
                        $trSpec[$cc] = $dd;
                    }
                }
                //----rebate klaim
                if (isset($arrKlaimBatal[$trid])) {
                    foreach ($arrKlaimBatal[$trid] as $cc => $dd) {
                        $trSpec[$cc] = $dd;
                    }
                }
                //----nilai locker diskon $arrLockerDiskon
                if (isset($arrLockerDiskon[$trid])) {
                    foreach ($arrLockerDiskon[$trid] as $cc => $dd) {
                        $trSpec[$cc] = $dd;
                    }

                }
//                else {
//                    $bgcolor = "yellow";
//                }
//


                $selisih = $trSpec["nilai_piutang"] - ($trSpec["nilai"] + $trSpec["nilai_freeproduk"]);
                $trSpec["selisih_plus"] = ($selisih > 0) ? $selisih : 0;
                if ($trSpec["selisih_plus"] > 10) {
//                    $bgcolor = "#ff3300";
                    //---- nilai klaim seharusnya (creditnote, pph23 dibayar dimuka, voucher, kas, logam mulia)
                    if ($trSpec["nilai_pph23"] > 10) {
                        $new_nilai_pph23 = (15 / 100) * $trSpec["nilai"];
                        $new_nilai_credit_note = (85 / 100) * $trSpec["nilai"];
                        $trSpec["new_nilai_pph23"] = $new_nilai_pph23;
                        $trSpec["new_nilai_credit_note"] = $new_nilai_credit_note;

                        $adj_nilai_pph23 = (15 / 100) * $trSpec["selisih_plus"];
                        $adj_nilai_credit_note = (85 / 100) * $trSpec["selisih_plus"];
                        $trSpec["adj_nilai_pph23"] = $adj_nilai_pph23;
                        $trSpec["adj_nilai_credit_note"] = $adj_nilai_credit_note;
                        //------PER SUPPLIER
                        $arrSupplierCek[$supplier_id]["suppliers_id"] = $supplier_id;
                        $arrSupplierCek[$supplier_id]["suppliers_nama"] = $supplier_nama;
                        if (!isset($arrSupplierCek[$supplier_id]["new_nilai_pph23"])) {
                            $arrSupplierCek[$supplier_id]["new_nilai_pph23"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["new_nilai_pph23"] += $new_nilai_pph23;

                        if (!isset($arrSupplierCek[$supplier_id]["new_nilai_credit_note"])) {
                            $arrSupplierCek[$supplier_id]["new_nilai_credit_note"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["new_nilai_credit_note"] += $new_nilai_credit_note;

                        if (!isset($arrSupplierCek[$supplier_id]["adj_nilai_pph23"])) {
                            $arrSupplierCek[$supplier_id]["adj_nilai_pph23"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["adj_nilai_pph23"] += $adj_nilai_pph23;

                        if (!isset($arrSupplierCek[$supplier_id]["adj_nilai_credit_note"])) {
                            $arrSupplierCek[$supplier_id]["adj_nilai_credit_note"] = 0;
                        }
                        $arrSupplierCek[$supplier_id]["adj_nilai_credit_note"] += $adj_nilai_credit_note;
                        //------
                    }
                }

                // belum diklaim belum_diklaim
                $belum_diklaim = ($trSpec["nilai"] + $trSpec["nilai_freeproduk"]) - $trSpec["nilai_piutang"];
                $belum_diklaim = ($belum_diklaim > 0) ? $belum_diklaim : 0;
                $trSpec["belum_diklaim"] = $belum_diklaim;
                if ($belum_diklaim > 10) {
                    $bgcolor = "yellow";
                }
                if ($trSpec["nilai_locker_diskon_total"] != $trSpec["nilai_piutang"]) {
                    $bgcolor = "red";
                }

                // rekening realiasi klaim
                $trid__ = $arrIniTridYangDiKlaim[$trid];
                $trSpec["rek_realisasi_klaim"] = $realisasiKlaim[$trid__];
                $trSpec["rek_diskon_masuk"] = $diskonKlaimMasuk[$trid];


                $no++;
                $str .= "<tr style='background-color:$bgcolor;'>";
                $str .= "<td>$no</td>";
                foreach ($arrHeader as $key => $val) {
                    $val_data = isset($trSpec[$key]) ? $trSpec[$key] : "";
                    if (is_array($val)) {
                        $val_data_f = formatField_he_format($val["format"], $val_data);
                        $align = "right";
                    }
                    else {
                        $val_data_f = $val_data;
                        $align = "";
                    }
                    $str .= "<td style='text-align:$align;'>";
                    $str .= $val_data_f;
                    $str .= "</td>";

                    if (is_numeric($val_data)) {
                        if (!isset($totalBawah[$key])) {
                            $totalBawah[$key] = 0;
                        }
                        $totalBawah[$key] += $val_data;
                    }
                }
                $str .= "</tr>";
            }
        }

        $str .= "<tr>";
        $str .= "<th>-</th>";
        foreach ($arrHeader as $key => $val) {
            $val_data = isset($totalBawah[$key]) ? $totalBawah[$key] : "";
            if (is_array($val)) {
                $val_data_f = formatField_he_format($val["format"], $val_data);
                $align = "right";
            }
            else {
                $val_data_f = $val_data;
                $align = "";
            }
            $str .= "<th style='text-align:$align;'>";
            $str .= $val_data_f;
            $str .= "</th>";
        }
        $str .= "</tr>";

        $str .= "<tr>";
        $str .= "<th>-</th>";
        foreach ($arrHeader as $key => $val) {
            if (is_array($val)) {
                $val_data_f = $val["label"];
                $align = "right";
            }
            else {
                $val_data_f = $val;
                $align = "";
            }
            $str .= "<th style='text-align:$align;'>";
            $str .= $val_data_f;
            $str .= "</th>";
        }
        $str .= "</tr>";

        $str .= "</table>";
        //-------
        $str .= "<br><br><br>";
        //-------

//        arrPrintCyan($arrSupplierCek);

        echo $str;
        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    public function cekKlaimRebateSupplier()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlSupplierDiskon");
        $this->load->model("Mdls/MdlLockerStockDiskonVendor");
        $this->load->model("Coms/ComRekeningPembantuPiutangSupplierDetailTransItem");

        //-----
        $tbl_mutasi = "__rek_pembantu_piutangsupplier__1010020030";
        $supplier_id = "4";
        $date1 = "2024-01-01";
        $date2 = "2026-12-31";
        $arrHeader = array(
            "id" => "trid",
            "dtime" => "dtime",
            "referenceNomer__2" => "nomer po",
            "nomer" => "nomer klaim",
            "suppliers_id" => "ID supplier",
            "suppliers_nama" => "supplier",
//            "nilai_diskonpo" => array(
//                "label" => "PO rebate",
//                "format" => "debet",
//            ),
//            "nilai_diskonpo_freeproduk" => array(
//                "label" => "PO rebate<br>(freeproduk)",
//                "format" => "debet",
//            ),
//            "nilai" => array(
//                "label" => "GRN rebate",
//                "format" => "debet",
//            ),
//            "nilai_freeproduk" => array(
//                "label" => "GRN rebate<br>(freeproduk)",
//                "format" => "debet",
//            ),
//            "nilai_grn_batal" => array(
//                "label" => "GRN rebate<br>(BATAL)",
//                "format" => "debet",
//            ),
            "nilai_piutang" => array(
                "label" => "nilai klaim",
                "format" => "debet",
            ),
//            "nilai_piutang_batal" => array(
//                "label" => "diklaim<br>(BATAL)",
//                "format" => "debet",
//            ),
//            "belum_diklaim" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "belum diklaim",
//                "format" => "debet",
//            ),
            "rek_realisasi_klaim" => array(// klaim lebih besar dari diskon yang didapat
                "label" => "rek_realisasi_klaim",
                "format" => "debet",
            ),
//            "selisih_plus" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "selisih plus",
//                "format" => "debet",
//            ),
            //-------
//            "nilai_persediaan" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "persediaan",
//                "format" => "debet",
//            ),
//            "nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note",
//                "format" => "debet",
//            ),
//            "nilai_voucher" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "voucher",
//                "format" => "debet",
//            ),
//            "nilai_cash" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "kas",
//                "format" => "debet",
//            ),
//            "nilai_logam_mulia" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "logam mulia",
//                "format" => "debet",
//            ),
//            "nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka",
//                "format" => "debet",
//            ),
            //-------
//            "new_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note<br>REVISI",
//                "format" => "debet",
//            ),
//            "new_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka<br>REVISI",
//                "format" => "debet",
//            ),
            //-------
//            "adj_nilai_credit_note" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "credit note<br>ADJ",
//                "format" => "debet",
//            ),
//            "adj_nilai_pph23" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "pph23<br>dibayar dimuka<br>ADJ",
//                "format" => "debet",
//            ),
//            "nilai_locker_diskon" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "locker_diskon",
//                "format" => "debet",
//            ),
//            "nilai_locker_diskon_diklaim" => array(// klaim lebih besar dari diskon yang didapat
//                "label" => "locker_diskon_diklaim",
//                "format" => "debet",
//            ),
        );

        //region data diskon-----
        $ds = New MdlSupplierDiskon();
        $dsTmp = $ds->lookupAll()->result();
        foreach ($dsTmp as $dsSpec) {
            $arrDiskonData[$dsSpec->id] = $dsSpec->label;
        }
        //endregion-----

        // region loacker diskon
        $arrLockerDiskon = array();
        $ldd = New MdlLockerStockDiskonVendor();
        if ($supplier_id > 0) {
            $ldd->addFilter("supplier_id='$supplier_id'");
        }
        $ldd->addFilter("fulldate>='$date1'");
        $ldd->addFilter("fulldate<='$date2'");
        $ldd->addFilter("state='active'");
        $lddTmp = $ldd->lookupAll()->result();
        if (sizeof($lddTmp) > 0) {
            foreach ($lddTmp as $lddSpec) {
                if (!isset($arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon"])) {
                    $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon"] = 0;
                }
                if (!isset($arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon_diklaim"])) {
                    $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon_diklaim"] = 0;
                }
                $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon"] += $lddSpec->nilai;
                $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon_diklaim"] += $lddSpec->nilai_diklaim;
                $arrLockerDiskon[$lddSpec->transaksi_id]["nilai_locker_diskon_total"] += ($lddSpec->nilai + $lddSpec->nilai_diklaim);
            }
        }
        // endregion loacker diskon

        // region transaksi grn 467,3344,4643
        $arrJenisTrDiskon = array();
        $arrTrIDs = array();
        $jenisTr = array(
            "467",
            "4643",
            "3344",
        );
        $date = isset($_GET["date"]) ? $_GET["date"] : "2025-05";
        $date_ex = explode("-", $date);
        $month = isset($date_ex[1]) ? $date_ex[1] : date("m");
        $year = isset($date_ex[0]) ? $date_ex[0] : date("Y");

        // endregion transaksi grn 467


        // region transaksi klaim 3333
        $arrIniJenisDiKlaim = array();
        $arrIniTridKlaim = array();
        $arrIniTridYangDiKlaim = array();
        $arrKlaim = array();
        $arrKlaimBatal = array();
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='3333'");
        if ($supplier_id > 0) {
            $tr->addFilter("suppliers_id='$supplier_id'");
        }
//        $tr->addFilter("reference_id in ('" . implode("','", $arrTrIDs) . "')");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $jenis = $trSpec->jenis;
                $ini_trid = $trSpec->id;
                $trash_4 = $trSpec->trash_4;

                $reference_id = $trSpec->reference_id;
                $reference_jenis = $trSpec->reference_jenis;
                $arrIniTridKlaim[] = $ini_trid;// 3333
                $arrTrSpec = (array)$trSpec;

                $trreg = New MdlTransaksi();
                $trreg->setFilters(array());
                $trreg->setJointSelectFields("transaksi_id, main");
                $trreg->addFilter("transaksi_id='$ini_trid'");
                $tmpReg = $trreg->lookupDataRegistries()->result();
                $main = blobDecode($tmpReg[0]->main);
                $arrTrSpecc = array(
                    "nilai_persediaan" => $main["nilai_persediaan"],
                    "nilai_piutang" => $main["nilai_piutang"],
                    "nilai_credit_note" => $main["nilai_credit_note"],
                    "nilai_voucher" => $main["nilai_voucher"],
                    "nilai_cash" => $main["nilai_cash"],
                    "nilai_logam_mulia" => $main["nilai_logam_mulia"],
                    "nilai_pph23" => $main["nilai_pph23"],
                );
                if ($trash_4 == 1) {
                    $arrKlaimBatal[$reference_id] = array(
                        "nilai_persediaan_batal" => $main["nilai_persediaan"],
                        "nilai_piutang_batal" => $main["nilai_piutang"],
                        "nilai_credit_note_batal" => $main["nilai_credit_note"],
                        "nilai_voucher_batal" => $main["nilai_voucher"],
                        "nilai_cash_batal" => $main["nilai_cash"],
                        "nilai_logam_mulia_batal" => $main["nilai_logam_mulia"],
                        "nilai_pph23_batal" => $main["nilai_pph23"],
                    );
                }

                $arrTransaksiKlaim[$ini_trid] = $arrTrSpec + $arrTrSpecc;
            }

            $this->db->from($tbl_mutasi);
            $this->db->where_in("transaksi_id", $arrIniTridKlaim);
            $query = $this->db->get()->result();
            foreach ($query as $qspec) {
                if (!isset($realisasiKlaim[$qspec->transaksi_id]["rek_realisasi_klaim"])) {
                    $realisasiKlaim[$qspec->transaksi_id]["rek_realisasi_klaim"] = 0;
                }
                $realisasiKlaim[$qspec->transaksi_id]["rek_realisasi_klaim"] += $qspec->kredit;
            }

        }
        // endregion transaksi klaim 3333


        $this->db->trans_start();

        $arrSupplierCek = array();

        $str = "<table style='border:1px solid black;width:100%;' rules='all'>";

        $str .= "<tr>";
        $str .= "<th>1 | no.</th>";
        $noo = 1;
        foreach ($arrHeader as $key => $val) {
            $noo++;
            if (is_array($val)) {
                $str .= "<th>$noo | " . $val["label"] . "</th>";
            }
            else {
                $str .= "<th>$noo | $val</th>";
            }
        }
        $str .= "</tr>";

        if (sizeof($arrTransaksiKlaim) > 0) {
            $no = 0;
            foreach ($arrTransaksiKlaim as $trid => $trSpec) {
//                arrPrint($trSpec);

                $supplier_id = $trSpec["suppliers_id"];
                $supplier_nama = $trSpec["suppliers_nama"];

                if (isset($realisasiKlaim[$trid])) {
                    foreach ($realisasiKlaim[$trid] as $cc => $dd) {
                        $trSpec[$cc] = $dd;
                    }
                }

                $no++;
                $str .= "<tr style='background-color:$bgcolor;'>";
                $str .= "<td>$no</td>";
                foreach ($arrHeader as $key => $val) {
                    $val_data = isset($trSpec[$key]) ? $trSpec[$key] : "";
                    if (is_array($val)) {
                        $val_data_f = formatField_he_format($val["format"], $val_data);
                        $align = "right";
                    }
                    else {
                        $val_data_f = $val_data;
                        $align = "";
                    }
                    $str .= "<td style='text-align:$align;'>";
                    $str .= $val_data_f;
                    $str .= "</td>";

                    if (is_numeric($val_data)) {
                        if (!isset($totalBawah[$key])) {
                            $totalBawah[$key] = 0;
                        }
                        $totalBawah[$key] += $val_data;
                    }
                }
                $str .= "</tr>";

//                break;
            }
        }

        $str .= "<tr>";
        $str .= "<th>-</th>";
        foreach ($arrHeader as $key => $val) {
            $val_data = isset($totalBawah[$key]) ? $totalBawah[$key] : "";
            if (is_array($val)) {
                $val_data_f = formatField_he_format($val["format"], $val_data);
                $align = "right";
            }
            else {
                $val_data_f = $val_data;
                $align = "";
            }
            $str .= "<th style='text-align:$align;'>";
            $str .= $val_data_f;
            $str .= "</th>";
        }
        $str .= "</tr>";

        $str .= "<tr>";
        $str .= "<th>-</th>";
        foreach ($arrHeader as $key => $val) {
            if (is_array($val)) {
                $val_data_f = $val["label"];
                $align = "right";
            }
            else {
                $val_data_f = $val;
                $align = "";
            }
            $str .= "<th style='text-align:$align;'>";
            $str .= $val_data_f;
            $str .= "</th>";
        }
        $str .= "</tr>";

        $str .= "</table>";
        //-------
        $str .= "<br><br><br>";
        //-------

//        arrPrintCyan($arrSupplierCek);

        echo $str;
        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");


    }

    //-----
    public function cekPymLock()
    {
        $limit_time = "600";
        $arrTargetJenis = array("4464", "749");
        $this->load->model("MdlTransaksi");
        $tr = New MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("sisa>'100'");
        $tr->addFilter("target_jenis in ('" . implode("','", $arrTargetJenis) . "')");
        $tmp = $tr->lookUpAllPaymentSrc()->result();
        showLast_query("biru");
        foreach ($tmp as $tmpSpec) {
            $arrTrids[$tmpSpec->transaksi_id] = $tmpSpec->transaksi_id;
        }


        $array = array(
            'state=' => 'hold',
            'jumlah=' => 1,
        );
        $this->db->where($array);
        $this->db->where_in('produk_id', $arrTrids);
        $query = $this->db->get('stock_locker_transaksi')->result();
        showLast_query("biru");
        if(sizeof($query)>0){

            foreach ($query as $spec){
                $id_tbl = $spec->id;
                $produk_id = $spec->produk_id;
                $oleh_id = $spec->oleh_id;
                $oleh_nama = $spec->oleh_nama;
                $last_access = $spec->last_access;

                cekHitam("[$id_tbl] [$produk_id] [$oleh_id] [$oleh_nama] [$last_access]");

            }
        }
        else{
            cekHitam("<h3>TIDAK ADA YANG DI-LOCK</h3>");
        }


    }


    public function cekBooking()
    {
        $ci =& get_instance();
        $tbl_1 = "transaksi";
        $tbl_2 = "transaksi_data";
        $arrJenisTr = array("5822so", "5823so");
        $pIDs = array(
            "1276",
        );

        $selected = array(
            "sum(valid_qty) as 'sum_valid_qty'",
            "produk_id",
            "produk_nama",
            "$tbl_1.gudang_status_id",
            "$tbl_1.gudang_status_nama",
        );
        $ci->db->select($selected);
        $ci->db->from($tbl_1);
        $ci->db->join($tbl_2, "$tbl_1.id = $tbl_2.transaksi_id", 'inner');

        $condites = array(
            "$tbl_1.trash_4" => "0",
            "$tbl_2.valid_qty>" => 0,
            "$tbl_2.next_substep_code!=" => "",
        );
        $ci->db->where($condites);
        $ci->db->where_in("$tbl_1.jenis", $arrJenisTr);
        $ci->db->where_in("$tbl_2.produk_id", $pIDs);

        $ci->db->group_by("produk_id");
//    $ci->db->group_by("produk_id, gudang_status_id");
        $query = $ci->db->get()->result_array();
        showLast_query("biru");

        $queries = array();
        foreach ($query as $item) {
            $produk_id = $item["produk_id"];
            $gudang_id = $item["gudang_status_id"];

            $queries[$produk_id] = $item["sum_valid_qty"];
        }
        arrPrintCyan($queries);
    }

}


