<?php


class ToolCoaConvert extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
    }

    function index()
    {

    }

    //CACHE--------------------------------------------------
    public function generateRekeningTabel()
    {
        cekMerah(date("Y-m-d H:i:s"));
        $this->load->helper("he_mass_table");

        $tabel_target = "z_tabel_generate";
        $tables = $this->db->list_tables();

        $arrTablesMutasi = array();
        foreach ($tables as $table) {
            // tabek mutasi rekening master
            if (substr($table, 0, 14) == "__rek_master__") {
                $arrTablesMutasi["master"][] = $table;
            }
            // tabek mutasi rekening pembantu
            if (substr($table, 0, 15) == "__rek_pembantu_") {
                $arrTablesMutasi["pembantu"][] = $table;
            }
        }
//arrPrintPink($arrTablesMutasi["pembantu"]);


        $this->db->trans_start();


        if (sizeof($arrTablesMutasi) > 0) {
            foreach ($arrTablesMutasi as $tabelSpec) {
                foreach ($tabelSpec as $tabel_mutasi) {
                    $tabel_mutasi_ex = explode("__", $tabel_mutasi);
//                    arrPrintPink($tabel_mutasi_ex);
//                    mati_disini(__LINE__);
                    if (($tabel_mutasi_ex[2] != NULL) && (!is_numeric($tabel_mutasi_ex[2]))) {
                        $data = array(
                            "nama" => $tabel_mutasi,
                            "status" => 0,
                            "trash" => 0,
                            "state" => "mutasi",
                        );
                        $this->db->select('id');
                        $this->db->where(
                            array(
                                "nama" => "$tabel_mutasi"
                            )
                        );
                        $tmp = $this->db->get($tabel_target)->result();
                        if (sizeof($tmp) == 0) {
                            $this->db->insert($tabel_target, $data);
                            showLast_query("hijau");
                        }
                    }
                }
            }
        }


        cekMerah(date("Y-m-d H:i:s"));
        mati_disini(" OHOOOO ");

        $this->db->trans_complete();
        cekHijau("<h3>-- DONE --</h3>");
    }

    public function generateRekeningTabelCache()
    {
        cekMerah(date("Y-m-d H:i:s"));
        $this->load->helper("he_mass_table");

        $tabel_target = "z_tabel_generate";
        $tables = $this->db->list_tables();

        $arrTablesMutasi = array();
        foreach ($tables as $table) {
            // tabek mutasi rekening master
            if (substr($table, 0, 11) == "_rek_master") {
                $arrTablesMutasi["master"][] = $table;
            }
            // tabek mutasi rekening pembantu
            if (substr($table, 0, 13) == "_rek_pembantu") {
                $arrTablesMutasi["pembantu"][] = $table;
            }
        }


        $this->db->trans_start();


        if (sizeof($arrTablesMutasi) > 0) {
            foreach ($arrTablesMutasi as $tabelSpec) {
                foreach ($tabelSpec as $tabel_mutasi) {
                    $data = array(
                        "nama" => $tabel_mutasi,
                        "status" => 0,
                        "trash" => 0,
                        "state" => "cache",
                    );
                    $this->db->select('id');
                    $this->db->where(
                        array(
                            "nama" => "$tabel_mutasi"
                        )
                    );
                    $tmp = $this->db->get($tabel_target)->result();
                    if (sizeof($tmp) == 0) {
                        $this->db->insert($tabel_target, $data);
                        showLast_query("hijau");
                    }
                }
            }
        }

        cekMerah(date("Y-m-d H:i:s"));
        mati_disini(" OHOOOO ");

        $this->db->trans_complete();
    }


    //MUTASI
    public function generateRekeningMutasi()
    {
        header("refresh:2");

        cekMerah(date("Y-m-d H:i:s"));
        $this->load->helper("he_mass_table");
        $this->load->model("Mdls/MdlAccounts");

        $ac = New MdlAccounts();
        $acTmp = $ac->lookUpTransactionStructureLabel_old();
        $acTmp_new = $ac->lookUpTransactionStructureLabel();
//        arrPrintPink($acTmp);
//        arrPrintHijau($acTmp_new);
//        mati_disini();
//        $coa_code_flip = array_flip($acTmp);
//        arrPrint($coa_code_flip);
//        arrPrint($coa_code_flip);
        foreach ($acTmp as $coa => $rek_lama) {
            if (!is_numeric($rek_lama)) {
                if (($rek_lama !== NULL) || ($rek_lama != "pilih rekening")) {
                    $coa_code_flip[$rek_lama] = $coa;
                }
            }
        }
//        arrPrint($coa_code_flip);
//        mati_disini();
//
//

        $tabel_source = "z_tabel_generate";
        $this->db->select(array("id", "nama"));
        $this->db->where(
            array(
                "status" => 0,
                "trash" => 0,
                "state" => "mutasi"
            )
        );
        $this->db->order_by("id", "ASC");
        $this->db->limit(1);
        $tmp = $this->db->get($tabel_source)->result();

        if (sizeof($tmp) > 0) {
            $tabel_id = $tmp[0]->id;
            $tabel_nama = $tmp[0]->nama;
            $tabel_nama_ex = explode("__", $tabel_nama);

            cekHitam("tabel: $tabel_nama, ### " . $tabel_nama_ex[2]);
            arrPrintKuning($tabel_nama_ex);

            $lanjutkan = true;
            if (($tabel_nama_ex[2] == NULL) OR is_numeric($tabel_nama_ex[2])) {
                $lanjutkan = false;
            }


            if ($lanjutkan == true) {
                $master_tabel = "_" . $tabel_nama_ex[1];

                // membaca isi tabel rekening lama
                $this->db->select('*');
                $tmp = $this->db->get($tabel_nama)->result();
                showLast_query("biru");
                cekBiru(sizeof($tmp));

                // build tabel baru sesuai coa
                if (sizeof($tmp) > 0) {
                    $rekening_lama = $tmp[0]->rekening;
                    $coa_code = isset($coa_code_flip[$rekening_lama]) ? $coa_code_flip[$rekening_lama] : NULL;
                    if ($coa_code != NULL) {
                        $tabel_new = "_" . $master_tabel . "__" . $coa_code;
                        $tabel_exists = tableExists($tabel_new);
                        $tabel_build = tableForceCheck($tabel_new, $master_tabel);
                    }
                    else {
                        $lanjutkan = false;
                        mati_disini("rekening $rekening_lama belum masuk ke COA.");
                    }

                    cekHere("[$tabel_nama] $rekening_lama, $master_tabel, $coa_code, $tabel_new, [$tabel_exists] [$tabel_build]");

                }
                else {
                    $lanjutkan = false;// karena kosong tidak ada dtaanya
                }
            }

            cekHitam("lanjutkan: $lanjutkan");
            $this->db->trans_start();

            if ($lanjutkan == true) {

                foreach ($tmp as $tmpSpec) {

                    $tmpSpecNew = (array)$tmpSpec;
                    $tmpSpecNew["rekening"] = $coa_code;
                    $tmpSpecNew["pembayaran"] = ($tmpSpec->pembayaran == NULL) ? 0 : $tmpSpec->pembayaran;
                    $tmpSpecNew["rekening_2"] = $tmpSpec->rekening; // berisi rekening alfabet lama
                    $tmpSpecNew["rekening_alias"] = isset($acTmp_new[$coa_code]) ? strtolower($acTmp_new[$coa_code]) : ""; // berisi rekening alias dari coa


                    // cek dahulu isi tabel sudah ada atau belum ada rekening coa
//                    $where = array(
//                        "rekening" => $coa_code
//                    );
//                    $isiTmp = $this->db->get_where($tabel_new, $where);
//                    if (sizeof($isiTmp) > 0) {
//                        $this->db->delete($tabel_new, $where);
//                        showLast_query("hitam");
//                    }
//                    else {

                    $this->db->insert($tabel_new, $tmpSpecNew);
                    showLast_query("hijau");
//                    }
//                    break;
                }


                $data = array(
                    "status" => 1,
                );
                $where = array(
                    "id" => $tabel_id
                );
                $this->db->where($where);
                $this->db->update($tabel_source, $data);
                showLast_query("ungu");
            }

            if ($lanjutkan == false) {
                $data = array(
                    "status" => 11,
                );
                $where = array(
                    "id" => $tabel_id
                );
                $this->db->where($where);
                $this->db->update($tabel_source, $data);
                showLast_query("hitam");
//                mati_disini("rekening $rekening_lama belum masuk ke COA.");
            }


//            mati_disini("SETOP... line " . __LINE__);

            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            cekLime("<h3>-- DONE --</h3>");
        }
        else {
            mati_disini("HABIS");
        }
    }

    public function generateRekeningCache()
    {
//        $stopCommit = true;
        $stopCommit = false;
        header("refresh:2");

        cekMerah(date("Y-m-d H:i:s"));
        $this->load->helper("he_mass_table");
        $this->load->model("Mdls/MdlAccounts");
        $accountStrukturAlias = fetchAccountStructureAlias();

        $ac = New MdlAccounts();
        $acTmp = $ac->lookUpTransactionStructureLabel_old();
        showLast_query("biru");
//        arrPrintWebs($acTmp);

        $coa_code_flip = array_flip($acTmp);
//        arrPrintHijau($coa_code_flip);
//        mati_disini();

        $tabel_source = "z_tabel_generate";
        $this->db->select(array("id", "nama"));
        $this->db->where(
            array(
                "status" => 0,
                "trash" => 0,
                "state" => "cache"
            )
        );
        $this->db->order_by("id", "ASC");
        $this->db->limit(1);
        $tmp = $this->db->get($tabel_source)->result();
        showLast_query("biru");

        if (sizeof($tmp) > 0) {
            $tabel_id = $tmp[0]->id;
            $tabel_nama = $tmp[0]->nama;

            $this->db->select('*');
            $tmp = $this->db->get($tabel_nama)->result();
            showLast_query("biru");

            $this->db->trans_start();

            $arrBlacklist = array(
                "transfer stok",
                "pph22",
//                "rugilaba lain lain",
//                "rugilaba",
                "aktiva tetap",
                "hpp projek",
                "penjualan projek",
            );
            if (sizeof($tmp) > 0) {
                $arrRekeningCache = array();
                $arrRekeningLama = array();
                $ctr = 0;
                foreach ($tmp as $tmpSpec) {
                    $ctr++;
                    $rekening_lama = $tmpSpec->rekening;
//                    $coa_code = isset($coa_code_flip[$rekening_lama]) ? $coa_code_flip[$rekening_lama] : mati_disini("COA CODE KOSONG [$rekening_lama] [$tabel_nama]");
//                    $coa_code = isset($coa_code_flip[$rekening_lama]) ? $coa_code_flip[$rekening_lama] : NULL;

                    if (!is_numeric($rekening_lama)) {

                        if (isset($coa_code_flip[$rekening_lama])) {
                            $coa_code = ($coa_code_flip[$rekening_lama]);
                        }
                        else {
                            if (in_array($rekening_lama, $arrBlacklist)) {
                                $coa_code = NULL;
                            }
                            else {
                                if (is_numeric($rekening_lama)) {
                                    $coa_code = NULL;
                                }
                                else {
                                    mati_disini("COA CODE KOSONG [$rekening_lama] [$tabel_nama]");
                                }
                            }
                        }

                        if ($coa_code != NULL) {
                            $rekening_alias = isset($accountStrukturAlias[$coa_code]) ? $accountStrukturAlias[$coa_code] : "";

//                    $tmpSpecNew = (array)$tmpSpec;
//                    $tmpSpecNew["rekening"] = $coa_code;
//                    $tmpSpecNew["pembayaran"] = ($tmpSpec->pembayaran == NULL) ? 0 : $tmpSpec->pembayaran;
////                    unset($tmpSpecNew["id"]);
//
//                    $arrRekeningCache[$tabel_nama]["data"] = $tmpSpecNew;
//                    $arrRekeningCache[$tabel_nama]["rekening"] = $coa_code;

//                        $arrRekeningCache[$tabel_nama]["rekening_lama"] = $rekening_lama;
                            $arrRekeningCache[$tabel_nama][$rekening_lama] = array(
                                "rekening" => "$coa_code",// kode coa
                                "rekening_2" => "$rekening_lama",// rekening alfabet, rekening lama sebelum coa
                                "rekening_alias" => "$rekening_alias",// rekening alias
                            );
                        }
                        else {
                            if (in_array($rekening_lama, $arrBlacklist)) {
                                $coa_code = NULL;
                            }
                            else {
                                mati_disini(":: rekening: $rekening_lama COA belum disetting ::");
                            }
                        }

                    }
                }
//                arrPrintPink($arrRekeningCache);
//                mati_disini();
                if (sizeof($arrRekeningCache) > 0) {
//                    foreach ($arrRekeningCache as $tabel_nama => $cacheSpec) {
//                        $where = array(
//                            "rekening" => $cacheSpec["rekening"]
//                        );
//                        $isiCacheTmp = $this->db->get_where($tabel_nama, $where);
//                        if (sizeof($isiCacheTmp) > 0) {
//                            $this->db->delete($tabel_nama, $where);
//                            showLast_query("hitam");
//                        }
////                        else {
////                            $this->db->insert($tabel_nama, $cacheSpec["data"]);
////                            showLast_query("hijau");
////                        }
//                    }
//                    foreach ($arrRekeningCache as $tabel_nama => $cacheSpec) {
//                        $where = array(
//                            "rekening" => $cacheSpec["rekening"]
//                        );
//                        $isiCacheTmp = $this->db->get_where($tabel_nama, $where);
////                        if (sizeof($isiCacheTmp) > 0) {
////                            $this->db->delete($tabel_nama, $where);
////                            showLast_query("hitam");
////                        }
////                        else {
//                            $this->db->insert($tabel_nama, $cacheSpec["data"]);
//                            showLast_query("hijau");
////                        }
//                    }

                    arrPrintPink($arrRekeningCache);
                    foreach ($arrRekeningCache as $tabel_nama => $cacheSpec) {
                        foreach ($cacheSpec as $rek => $spec) {
                            $rekening_lama = $rek;
                            $where = array(
                                "rekening" => $rekening_lama
                            );

                            $this->db->where($where);
                            $this->db->update($tabel_nama, $spec);
                            showLast_query("orange");

                        }
                    }
                }
//mati_disini(__LINE__);
                //--------------------------
                $data = array(
                    "status" => 1,
                );
                $where = array(
                    "id" => $tabel_id
                );
                $this->db->where($where);
                $this->db->update($tabel_source, $data);
                showLast_query("hijau");

            }
            else {
                $data = array(
                    "status" => 11,
                );
                $where = array(
                    "id" => $tabel_id
                );
                $this->db->where($where);
                $this->db->update($tabel_source, $data);
                showLast_query("hitam");
            }


            if ($stopCommit == true) {
                mati_disini(" OHOOOO belon comit @" . __LINE__);
            }

            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            cekLime("<h3>-- DONE --</h3>");

        }
        else {
            cekHitam("-- HABIS --");
        }
    }

    public function generateLocker()
    {
        $tbl_locker = "stock_locker_value";
        $tbl_locker_extern = "stock_locker_value_extern";

        $this->load->model("Mdls/MdlAccounts");

        $ac = New MdlAccounts();
        $acTmp = $ac->lookUpTransactionStructureLabel_old();
        $coa_code_flip = array_flip($acTmp);

        // stok locker value
        $this->db->select("*");
        $tmp = $this->db->get($tbl_locker)->result();

        // stok locker value
        $this->db->select("*");
        $tmpExtern = $this->db->get($tbl_locker_extern)->result();


        $this->db->trans_start();

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmpSpec) {
                $specNew = (array)$tmpSpec;
                if (!is_numeric($tmpSpec->jenis)) {

                    $jenisNew = isset($coa_code_flip[$tmpSpec->jenis]) ? $coa_code_flip[$tmpSpec->jenis] : mati_disini("COA " . $coa_code_flip[$tmpSpec->jenis] . " KOSONG");
                    $specNew["jenis"] = $jenisNew;
                    unset($specNew["id"]);

                    $this->db->insert($tbl_locker, $specNew);
                    showLast_query("hijau");
                }
            }
        }

        if (sizeof($tmpExtern) > 0) {
            foreach ($tmpExtern as $tmpExternSpec) {
                $specNew = (array)$tmpExternSpec;
                if (!is_numeric($tmpExternSpec->jenis)) {

                    $jenisNew = isset($coa_code_flip[$tmpExternSpec->jenis]) ? $coa_code_flip[$tmpExternSpec->jenis] : mati_disini("COA " . $coa_code_flip[$tmpSpec->jenis] . " KOSONG");
                    $specNew["jenis"] = $jenisNew;
                    unset($specNew["id"]);

                    $this->db->insert($tbl_locker_extern, $specNew);
                    showLast_query("kuning");
                }
            }
        }


        mati_disini("SETOP...");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekLime("<h3>-- DONE --</h3>");
    }
    //--------------------------------------------------

    // region PPH22 ----------------------------------------

    // menggabung 2 rekening ke 1 rekening dan urut tanggal
    public function generatepph22()
    {
        $this->load->model("Coms/ComRekening");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_1 = "pph22";
        $rek_2 = "pph22 dibayar dimuka";
        $tabel_1 = "__rek_master__pph22";
        $tabel_2 = "__rek_master__pph22_dibayar_dimuka";
        $tabel_baru = "__rek_master__pph22_dibayar_dimuka_bak";
        $cabangIDs = array("1", "-1");
        $cabangID = "-1";


        // region membaca data mutasi rekening 1
        $cr = New ComRekening();
//        $cr->addFilter("pindah=0");
        $cr->addFilter("cabang_id in ('" . implode("','", $cabangIDs) . "')");
        $crResult = $cr->fetchMoves($rek_1);
        showLast_query("biru");
        $addDataMutasi = array();
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $arrSpec = (array)$spec;
                $arrSpec['rekening'] = $rek_2;// diberi rekening ke-2
                $addDataMutasi[] = $arrSpec;
            }
        }
        // endregion

        // region membaca data mutasi rekening 2
        $cr = New ComRekening();
//        $cr->addFilter("pindah=0");
        $cr->addFilter("cabang_id=$cabangID");
        $crResult = $cr->fetchMoves($rek_2);
        showLast_query("biru");
//        $addDataMutasi = array();
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $arrSpec = (array)$spec;
                $arrSpec['rekening'] = $rek_2;
                $addDataMutasi[] = $arrSpec;
            }
        }
        // endregion

        // region build array baru urut tanggal
        $addDataMutasi_new = array();
//        $addDataMutasi_dtime = array();
        foreach ($addDataMutasi as $kk => $addDataMutasiSpec) {
            $dtime = $addDataMutasiSpec['dtime'];
            $addDataMutasi_new[$dtime][] = $addDataMutasiSpec;
//            $addDataMutasi_dtime[$dtime][] = $dtime;
        }
        // endregion

        ksort($addDataMutasi_new);
//arrPrintPink($addDataMutasi);
//arrPrintHijau($addDataMutasi_new);
//arrPrintHijau($addDataMutasi_dtime);
//mati_disini(__LINE__);
        $this->db->trans_start();

        $no = 0;
        foreach ($addDataMutasi_new as $dtimee => $addDataMutasi_new_spec) {
            foreach ($addDataMutasi_new_spec as $spec) {

                $no++;
                $tabel_id = $spec['id'];
                unset($spec['id']);
                $this->db->insert($tabel_baru, $spec);

                cekHijau($no);
                showLast_query("hijau");

            }


//            $updateData = array("pindah" => "1");
//            $where = array("id" => $tabel_id);
//            $this->db->where($where);
//            $this->db->update($tabel_2, $updateData);
//            showLast_query("ungu");
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();


        cekHijau("<h2>-- DONE --</h2>");

    }

    // memperbaiki mutasi, update debet_awal, debet_akhir
    public function generatePph22Mutasi()
    {
        $this->load->model("Coms/ComRekening");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_1 = "pph22";
        $rek_2 = "pph22 dibayar dimuka";
        $tabel_1 = "__rek_master__pph22";
        $tabel_2 = "__rek_master__pph22_dibayar_dimuka";
        $tabel_baru = "__rek_master__pph22_dibayar_dimuka_bak";
        $cabangIDs = array("-1");
        $cabangID = "-1";

        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            // region membaca data mutasi rekening 2
            $cr = New ComRekening();
//            $cr->addFilter("cabang_id in ('" . implode("','", $cabangIDs) . "')");
            $cr->addFilter("cabang_id=$cabangID");
            $crResult = $cr->fetchMoves($rek_1);
            showLast_query("biru");
            $addDataMutasi = array();
            if (sizeof($crResult) > 0) {
                foreach ($crResult as $ii => $spec) {
                    $arrSpec = (array)$spec;
                    $arrSpec['rekening'] = $rek_1;
                    $addDataMutasi[$ii] = $arrSpec;
                }
            }
            // endregion
        }
        else {
            $this->db->where("cabang_id", $cabangID);
            $crResult = $this->db->get($tabel_baru)->result();
            showLast_query("biru");
            $addDataMutasi = array();
            if (sizeof($crResult) > 0) {
                foreach ($crResult as $ii => $spec) {
                    $arrSpec = (array)$spec;
                    $arrSpec['rekening'] = $rek_2;
                    $addDataMutasi[$ii] = $arrSpec;
                }
            }
        }

//        arrPrintHijau($addDataMutasi);
//        mati_disini(__LINE__ . " OHOOOO ");

        $this->db->trans_start();


        // memperbaiki debet_awal dan debet_akhir di tabel mutasi
        foreach ($addDataMutasi as $ii => $spec) {
            $tabel_id = $spec['id'];
            if ($ii == 0) {
                $firs_debet_awal = $spec['debet_akhir'] * 1;
            }
            if ($ii > 0) {
                $debet['debet_awal'] = $firs_debet_awal;
                $debet_akhir = $firs_debet_awal + $spec['debet'] - $spec['kredit'];
                $debet['debet_akhir'] = $debet_akhir;
                $debet['id'] = $spec['id'];
                $debet['rekening'] = $spec['rekening'];
                $debet['cabang_id'] = $spec['cabang_id'];
                $debet['debet'] = $spec['debet'];
                $debet['kredit'] = $spec['kredit'];
                $contens[$ii] = $debet;
//                $contens[$ii] = $spec + $debet;
//                if($ii == 2){
//                    cekHere("baris: $ii, debet_awal: $firs_debet_awal, debet_akhir: $debet_akhir");
//                    mati_disini(__LINE__);
//                }
//
                $firs_debet_awal = $debet_akhir;
            }

        }
//        arrPrintHijau($contens);
//        mati_disini(__LINE__);

        foreach ($contens as $ii => $contensSpec) {
            $tabel_id = $contensSpec['id'];
            $debet_awal = $contensSpec['debet_awal'];
            $debet_akhir = $contensSpec['debet_akhir'];

            $updateData = array(
                "debet_awal" => $debet_awal,
                "debet_akhir" => $debet_akhir,
            );
            $where = array("id" => $tabel_id);
            $this->db->where($where);
            $this->db->update($tabel_baru, $updateData);
            showLast_query("ungu");

        }


//        mati_disini(" OHOOOO ");
        $this->db->trans_complete();


        cekHijau("<h2>-- DONE --</h2>");

    }

    // memperbaiki cache rekening
    public function generatePph22Cache()
    {
        $this->load->model("Coms/ComRekening");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
//        $rek_1 = "pph22";
        $rek_2 = "pph22 dibayar dimuka";
//        $tabel_1 = "__rek_master__pph22";
//        $tabel_2 = "__rek_master__pph22_dibayar_dimuka";
//        $tabel_baru = "__rek_master__pph22_dibayar_dimuka_bak";
        $tabel_baru = "__rek_master__pph22_dibayar_dimuka";
        $cabangIDs = array("-1");

        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
//        arrPrintHijau($crResult);
        $arrCacheRekening = array();
        foreach ($crResult as $crResultSpec) {
            $cabang_id = $crResultSpec->cabang_id;
            $fulldate = $crResultSpec->fulldate;
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];
            $date_harian = $fulldate;
            $date_bulanan = "$thn-$bln";
            $date_tahunan = "$thn";
            $date_forever = "1";

            // region forever
            if (!isset($arrCacheRekening['forever'][$cabang_id][$date_forever])) {
                $arrCacheRekening['forever'][$cabang_id][$date_forever] = array();
            }
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["debet"] = $crResultSpec->debet_akhir;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["kredit"] = $crResultSpec->kredit_akhir;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["tgl"] = $tgl;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["bln"] = $bln;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["thn"] = $thn;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["fulldate"] = $fulldate;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["periode"] = "forever";
            // endregion forever
            // region tahunan
            if (!isset($arrCacheRekening['tahunan'][$cabang_id][$date_tahunan])) {
                $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan] = array();
            }
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["debet"] = $crResultSpec->debet_akhir;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["kredit"] = $crResultSpec->kredit_akhir;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["tgl"] = $tgl;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["bln"] = $bln;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["thn"] = $thn;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["fulldate"] = $fulldate;
            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["periode"] = "tahunan";
            // endregion tahunan
            // region bulanan
            if (!isset($arrCacheRekening['bulanan'][$cabang_id][$date_bulanan])) {
                $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan] = array();
            }
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["debet"] = $crResultSpec->debet_akhir;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["kredit"] = $crResultSpec->kredit_akhir;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["tgl"] = $tgl;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["bln"] = $bln;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["thn"] = $thn;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["fulldate"] = $fulldate;
            $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["periode"] = "bulanan";
            // endregion bulanan
            // region harian
            if (!isset($arrCacheRekening['harian'][$cabang_id][$date_harian])) {
                $arrCacheRekening['harian'][$cabang_id][$date_harian] = array();
            }
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["debet"] = $crResultSpec->debet_akhir;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["kredit"] = $crResultSpec->kredit_akhir;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["tgl"] = $tgl;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["bln"] = $bln;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["thn"] = $thn;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["fulldate"] = $fulldate;
            $arrCacheRekening['harian'][$cabang_id][$date_harian]["periode"] = "harian";
            // endregion harian
        }


        $this->db->trans_start();


//        arrPrintHijau($arrCacheRekening);
        foreach ($arrCacheRekening as $periode => $cacheSpec) {
            foreach ($cacheSpec as $cabang_id => $sSpec) {
                foreach ($sSpec as $subSpec) {

//                    arrPrintPink($subSpec);
                    $tgl = $subSpec['tgl'];
                    $bln = $subSpec['bln'];
                    $thn = $subSpec['thn'];
                    $rekening = $subSpec['rekening'];

                    $rc = New ComRekening();
                    $rc->setFilters(array());
                    switch ($periode) {
                        case "harian":
                            $rc->addFilter("tgl='$tgl'");
                            $rc->addFilter("bln='$bln'");
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "bulanan":
                            $rc->addFilter("bln='$bln'");
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "tahunan":
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "forever":

                            break;
                    }
                    $rc->addFilter("rekening='$rekening'");
                    $rc->addFilter("cabang_id='$cabang_id'");
                    $rc->addFilter("periode='$periode'");
                    $result = $rc->lookUpAll()->result();
                    showLast_query("biru");
                    if (sizeof($result) == 0) {
                        // insert baru
                        $anu = $rc->addData($subSpec);
                        showLast_query("hijau");
                    }
                    else {
                        // update yang sudah ada
                        $tbl_id = $result[0]->id;
                        $where = array("id" => $tbl_id);
                        $anu = $rc->updateData($where, $subSpec);
                        showLast_query("orange");
                    }

                }
            }
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    // mengembalikan data mutasi ke tabel asalnya
    public function generatePph22MutasiAsal()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_1 = "pph22";
        $rek_2 = "pph22 dibayar dimuka";
        $tabel_1 = "__rek_master__pph22";
        $tabel_2 = "__rek_master__pph22_dibayar_dimuka";
        $tabel_baru = "__rek_master__pph22_dibayar_dimuka_bak";
        $cabangIDs = array("1", "-1");
        $cabangID = "-1";

        $this->db->where("cabang_id", $cabangID);
        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
        $addDataMutasi = array();
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $arrSpec = (array)$spec;
                $arrSpec['rekening'] = $rek_2;
                $addDataMutasi[$ii] = $arrSpec;
            }
        }


        $this->db->trans_start();


        foreach ($addDataMutasi as $ii => $spec) {
            unset($spec['id']);
            $anu = $this->db->insert($tabel_2, $spec);
            showLast_query("orange");
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();


        cekHijau("<h2>-- DONE --</h2>");

    }

    // endregion PPH22 ----------------------------------------

    // region PENJUALAN ----------------------------------------

    // RUN menggabung rekening penjualan dan penjualan project
    public function generatePenjualan()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_1 = "penjualan";
        $rek_2 = "penjualan projek";
        $tabel_1 = "__rek_master__penjualan";
        $tabel_2 = "__rek_master__penjualan_projek";
        $tabel_baru = "__rek_master__penjualan_bak";

        // region membaca data mutasi rekening 1
        $cr = New ComRekening();
//        $cr->addFilter("cabang_id in ('" . implode("','", $cabangIDs) . "')");
        $crResult = $cr->fetchMoves($rek_1);
        showLast_query("biru");
        $addDataMutasi = array();
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $arrSpec = (array)$spec;
                $sthn = $arrSpec['thn'];
                $sbln = $arrSpec['bln'];
                $stgl = $arrSpec['tgl'];
                if (($sthn != "0000") && ($arrSpec["jenis"] == "")) {
                    $dtime_new = "$sthn-$sbln-$stgl 00:00:00";
                    $arrSpec["dtime"] = $dtime_new;
                }
                $arrSpec['rekening'] = $rek_1;// diberi rekening ke-1
                $addDataMutasi[$spec->cabang_id][] = $arrSpec;
            }
        }
        // endregion

        // region membaca data mutasi rekening 2
        $cr = New ComRekening();
//        $cr->addFilter("cabang_id=$cabangID");
        $crResult = $cr->fetchMoves($rek_2);
        showLast_query("biru");
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $arrSpec = (array)$spec;
                $sthn = $arrSpec['thn'];
                $sbln = $arrSpec['bln'];
                $stgl = $arrSpec['tgl'];
                if (($sthn != "0000") && ($arrSpec["jenis"] == "")) {
                    $dtime_new = "$sthn-$sbln-$stgl 00:00:00";
                    $arrSpec["dtime"] = $dtime_new;
                }
                $arrSpec['rekening'] = $rek_1;
                $addDataMutasi[$spec->cabang_id][] = $arrSpec;
            }
        }
        // endregion

        // region build array baru urut tanggal
        $addDataMutasi_new = array();
//        $addDataMutasi_dtime = array();
        foreach ($addDataMutasi as $cabang_id => $addDataMutasiSpec) {
            foreach ($addDataMutasiSpec as $subAddDataMutasiSpec) {
                $dtime = $subAddDataMutasiSpec['dtime'];
                $addDataMutasi_new[$cabang_id][$dtime][] = $subAddDataMutasiSpec;
//            $addDataMutasi_dtime[$cabang_id][$dtime][] = $dtime;
            }
        }
        // endregion

//        arrPrintHijau($addDataMutasi);
//        arrPrintPink($addDataMutasi_new);
//        mati_disini(__LINE__);
//

        $this->db->trans_start();

        $no = 0;
        foreach ($addDataMutasi_new as $cabang_id => $cSpec) {
            ksort($cSpec);
            foreach ($cSpec as $dtimee => $cdSpec) {
//                arrPrintPink($cdSpec);
                foreach ($cdSpec as $cdiSpec) {
                    $no++;
                    $tabel_id = $cdiSpec['id'];
                    unset($cdiSpec['id']);
                    $this->db->insert($tabel_baru, $cdiSpec);

                    cekHijau($no);
                    showLast_query("hijau");
                }
            }
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();


        cekHijau("<h2>-- DONE --</h2>");

    }

    // RUN memperbaiki mutasi, update kredit_awal, kredit_akhir
    public function generatePenjualanMutasi()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_1 = "penjualan";
        $rek_2 = "penjualan projek";
        $tabel_1 = "__rek_master__penjualan";
        $tabel_2 = "__rek_master__penjualan_projek";
        $tabel_baru = "__rek_master__penjualan_bak";

        // region membaca tabel baru _bak
//        $this->db->where("cabang_id", $cabangID);
        $this->db->order_by('id', 'ASC');
        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
        $addDataMutasi = array();
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $cabang_id = $spec->cabang_id;
                $arrSpec = (array)$spec;
                $arrSpec['rekening'] = $rek_1;
                $addDataMutasi[$cabang_id][] = $arrSpec;
            }
        }
        // endregion
//        arrprintPink($addDataMutasi[30]);

        $this->db->trans_start();

        //region memperbaiki hitungan kredit_awal dan kredit_akhir mutasi
        foreach ($addDataMutasi as $cabang_id => $cSpec) {
            foreach ($cSpec as $ii => $spec) {
                $tabel_id = $spec['id'];
                if ($ii == 0) {
                    $firs_kredit_awal = $spec['kredit_akhir'] * 1;
                }
                if ($ii > 0) {
                    $kredit['kredit_awal'] = $firs_kredit_awal;
                    $kredit_akhir = $firs_kredit_awal - $spec['debet'] + $spec['kredit'];
                    $kredit['kredit_akhir'] = $kredit_akhir;
                    $kredit['id'] = $spec['id'];
                    $kredit['rekening'] = $spec['rekening'];
                    $kredit['cabang_id'] = $spec['cabang_id'];
                    $kredit['debet'] = $spec['debet'];
                    $kredit['kredit'] = $spec['kredit'];
                    $contens[$cabang_id][$ii] = $kredit;
//                $contens[$ii] = $spec + $debet;
//                if($ii == 2){
//                    cekHere("baris: $ii, debet_awal: $firs_debet_awal, debet_akhir: $debet_akhir");
//                    mati_disini(__LINE__);
//                }
//
                    $firs_kredit_awal = $kredit_akhir;
                }
            }
        }
        //endregion
//        arrPrintHijau($contens);


        //region mengupdate kredit_awal dan kredit_akhir mutasi
        foreach ($contens as $cabang_id => $cContensSpec) {
            foreach ($cContensSpec as $ii => $contensSpec) {

                $tabel_id = $contensSpec['id'];
                $kredit_awal = $contensSpec['kredit_awal'];
                $kredit_akhir = $contensSpec['kredit_akhir'];

                $updateData = array(
                    "kredit_awal" => $kredit_awal,
                    "kredit_akhir" => $kredit_akhir,
                );
                $where = array("id" => $tabel_id);
                $this->db->where($where);
                $this->db->update($tabel_baru, $updateData);
                showLast_query("ungu");

            }

        }
        //endregion


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();


        cekHijau("<h2>-- DONE --</h2>");

    }

    // RUN memperbaiki cache rekening
    public function generatePenjualanCache()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
//        $rek_1 = "pph22";
        $rek_2 = "penjualan";
//        $tabel_1 = "__rek_master__pph22";
//        $tabel_2 = "__rek_master__pph22_dibayar_dimuka";
//        $tabel_baru = "__rek_master__penjualan_bak";
        $tabel_baru = "__rek_master__penjualan";

        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
        $arrCacheRekening = array();
        foreach ($crResult as $crResultSpec) {
            $jenis = $crResultSpec->jenis;
            $cabang_id = $crResultSpec->cabang_id;
            $fulldate = $crResultSpec->fulldate;
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];
            $date_harian = $fulldate;
            $date_bulanan = "$thn-$bln";
            $date_tahunan = "$thn";
            $date_forever = "1";

            // region forever
            if (!isset($arrCacheRekening['forever'][$cabang_id][$date_forever])) {
                $arrCacheRekening['forever'][$cabang_id][$date_forever] = array();
            }
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["debet"] = $crResultSpec->debet_akhir;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["kredit"] = $crResultSpec->kredit_akhir;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["tgl"] = $tgl;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["bln"] = $bln;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["thn"] = $thn;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["fulldate"] = $fulldate;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["periode"] = "forever";
            // endregion forever

            if ($jenis != "") {

                // region tahunan
                if (!isset($arrCacheRekening['tahunan'][$cabang_id][$date_tahunan])) {
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan] = array();
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["cabang_id"] = $crResultSpec->cabang_id;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["debet"] = 0;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["kredit"] = 0;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["tgl"] = $tgl;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["bln"] = $bln;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["thn"] = $thn;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["fulldate"] = $fulldate;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["periode"] = "tahunan";
                }
//            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["debet"] += 0;
                $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["kredit"] += ($crResultSpec->kredit - $crResultSpec->debet);
                // endregion tahunan

                // region bulanan
                if (!isset($arrCacheRekening['bulanan'][$cabang_id][$date_bulanan])) {
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan] = array();
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["cabang_id"] = $crResultSpec->cabang_id;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["debet"] = 0;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["kredit"] = 0;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["tgl"] = $tgl;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["bln"] = $bln;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["thn"] = $thn;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["fulldate"] = $fulldate;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["periode"] = "bulanan";
                }
                $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["kredit"] += ($crResultSpec->kredit - $crResultSpec->debet);
                // endregion bulanan

                // region harian
                if (!isset($arrCacheRekening['harian'][$cabang_id][$date_harian])) {
                    $arrCacheRekening['harian'][$cabang_id][$date_harian] = array();
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["cabang_id"] = $crResultSpec->cabang_id;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["debet"] = 0;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["kredit"] = 0;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["tgl"] = $tgl;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["bln"] = $bln;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["thn"] = $thn;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["fulldate"] = $fulldate;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["periode"] = "harian";
                }
                $arrCacheRekening['harian'][$cabang_id][$date_harian]["kredit"] += ($crResultSpec->kredit - $crResultSpec->debet);
                // endregion harian

            }
        }

//        arrPrintHijau($arrCacheRekening['tahunan']);
//        mati_disini(__LINE__);
        $this->db->trans_start();


        foreach ($arrCacheRekening as $periode => $cacheSpec) {
            foreach ($cacheSpec as $cabang_id => $sSpec) {
                foreach ($sSpec as $subSpec) {

//                    arrPrintPink($subSpec);
                    $tgl = $subSpec['tgl'];
                    $bln = $subSpec['bln'];
                    $thn = $subSpec['thn'];
                    $rekening = $subSpec['rekening'];

                    $rc = New ComRekening();
                    $rc->setFilters(array());
                    switch ($periode) {
                        case "harian":
                            $rc->addFilter("tgl='$tgl'");
                            $rc->addFilter("bln='$bln'");
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "bulanan":
                            $rc->addFilter("bln='$bln'");
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "tahunan":
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "forever":

                            break;
                    }
                    $rc->addFilter("rekening='$rekening'");
                    $rc->addFilter("cabang_id='$cabang_id'");
                    $rc->addFilter("periode='$periode'");
                    $result = $rc->lookUpAll()->result();
                    showLast_query("biru");
                    if (sizeof($result) == 0) {
                        // insert baru
                        $anu = $rc->addData($subSpec);
                        showLast_query("hijau");
                    }
                    else {
                        // update yang sudah ada
                        $tbl_id = $result[0]->id;
                        $where = array("id" => $tbl_id);
                        $anu = $rc->updateData($where, $subSpec);
                        showLast_query("orange");
                    }

                }
            }
        }


//        mati_disini(" OHOOOO ");
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    // mengembalikan data mutasi ke tabel asalnya
    public function generatePenjualanMutasiAsal()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_1 = "penjualan";
        $rek_2 = "penjualan projek";
        $tabel_1 = "__rek_master__penjualan";
        $tabel_2 = "__rek_master__penjualan_projek";
        $tabel_baru = "__rek_master__penjualan_bak";

//        $this->db->where("cabang_id", $cabangID);
        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
        $addDataMutasi = array();
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $arrSpec = (array)$spec;
                $arrSpec['rekening'] = $rek_1;
                $addDataMutasi[$ii] = $arrSpec;
            }
        }


        $this->db->trans_start();


        foreach ($addDataMutasi as $ii => $spec) {
            unset($spec['id']);
            $anu = $this->db->insert($tabel_1, $spec);
            showLast_query("orange");
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();

        cekHijau("<h2>-- DONE --</h2>");

    }

    // RUN pembantu penjualan lokal, eksport, project
    public function generatePenjualanPembantu()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuPenjualan");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
//        $rek_1 = "pph22";
        $rek_2 = "penjualan";
//        $tabel_1 = "__rek_master__pph22";
//        $tabel_2 = "__rek_master__pph22_dibayar_dimuka";
//        $tabel_baru = "__rek_master__penjualan_bak";
        $tabel_baru = "__rek_master__penjualan";

        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");


        $this->db->trans_start();


        $arrCacheRekening = array();
        foreach ($crResult as $crResultSpec) {
            $jenis = $crResultSpec->jenis;
            $cabang_id = $crResultSpec->cabang_id;
            $transaksi_id = $crResultSpec->transaksi_id;
            $transaksi_no = $crResultSpec->transaksi_no;
            $debet = $crResultSpec->debet;
            $kredit = $crResultSpec->kredit;
            $dtime = $crResultSpec->dtime;
            $fulldate = $crResultSpec->fulldate;
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];

            $arrPembantu = array();
            switch ($jenis) {
                case "582spd":
                    $arrPembantu = array(
//                    "comName" => "RekeningPembantuPenjualan",// lokal
                        "loop" => array(
                            "4010" => $kredit,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "4010010",
                            "extern_nama" => "lokal",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $kredit,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
                case "382spd":
                    $arrPembantu = array(
                        "loop" => array(
                            "4010" => $kredit,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "4010020",
                            "extern_nama" => "export",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $kredit,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
                case "9912":
                    $tr = New MdlTransaksi();
                    $tr->setFilters(array());
                    $tr->setJointSelectFields("main,transaksi_id");
                    $tr->addFilter("transaksi_id=$transaksi_id");
                    $trReg = $tr->lookupDataRegistries()->result();
                    showLast_query("biru");
                    $regMain = blobDecode($trReg[0]->main);
                    if (!is_array($regMain)) {
                        $regMain = blobDecode($regMain);
                    }
                    $pihakExternMasterID = $regMain["pihakExternMasterID"];
                    switch ($pihakExternMasterID) {
                        case "582":
                            $externID = "4010010";
                            $externNama = "lokal";
                            break;
                        case "382":
                            $externID = "4010020";
                            $externNama = "export";
                            break;
                        case "588":
                            $externID = "4010030";
                            $externNama = "project";
                            break;
                        case "7499":
                            $externID = "4010030";
                            $externNama = "project";
                            break;
                    }
                    $arrPembantu = array(
                        "loop" => array(
                            "4010" => $debet * -1,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => $externID,
                            "extern_nama" => $externNama,
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $debet,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;

                case "588so":
                    $arrPembantu = array(
                        "loop" => array(
                            "4010" => $kredit,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "4010030",
                            "extern_nama" => "project",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $kredit,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
                case "7499":
                    $arrPembantu = array(
                        "loop" => array(
                            "4010" => $kredit,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "4010030",
                            "extern_nama" => "project",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $kredit,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
            }

            if (sizeof($arrPembantu) > 0) {
//                arrPrintPink($arrPembantu);
                $cpp = New ComRekeningPembantuPenjualan();
                $cpp->pair($arrPembantu) or die("Tidak berhasil memasang  values pada komponen");
                $cpp->exec() or die("Gagal saat berusaha  exec values pada komponen");
            }

//            if($jenis == "582spd"){
//                cekMerah(":: SETOP ::");
//                break;
//            }
        }


//        mati_disini(" OHOOOO ");
        $this->db->trans_complete();

        cekHijau("<h2>-- DONE --</h2>");

    }

    public function generateReturnPenjualanPembantu()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuReturnPenjualan");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_2 = "4020";
        $tabel_baru = "__rek_master__4020";

        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");


        $this->db->trans_start();


        $arrCacheRekening = array();
        foreach ($crResult as $crResultSpec) {
            $jenis = $crResultSpec->jenis;
            $cabang_id = $crResultSpec->cabang_id;
            $transaksi_id = $crResultSpec->transaksi_id;
            $transaksi_no = $crResultSpec->transaksi_no;
            $debet = $crResultSpec->debet;
            $kredit = $crResultSpec->kredit;
            $dtime = $crResultSpec->dtime;
            $fulldate = $crResultSpec->fulldate;
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];

            $nilai = $debet > 0 ? $debet : $kredit * -1;
            $arrPembantu = array();
            switch ($jenis) {
//                case "582spd":
//                    $arrPembantu = array(
////                    "comName" => "RekeningPembantuPenjualan",// lokal
//                        "loop" => array(
//                            "4010" => $kredit,// penjualan
//                        ),
//                        "static" => array(
//                            "cabang_id" => $cabang_id,
//                            "extern_id" => "4010010",
//                            "extern_nama" => "lokal",
//                            "extern2_id" => "0",
//                            "extern2_nama" => "",
//                            "jenis" => $jenis,
//                            "transaksi_id" => $transaksi_id,
//                            "transaksi_no" => $transaksi_no,
//                            "harga" => $kredit,
//                            "fulldate" => $fulldate,
//                            "dtime" => $dtime,
//                        ),
//                    );
//                    break;
//                case "382spd":
//                    $arrPembantu = array(
//                        "loop" => array(
//                            "4010" => $kredit,// penjualan
//                        ),
//                        "static" => array(
//                            "cabang_id" => $cabang_id,
//                            "extern_id" => "4010020",
//                            "extern_nama" => "export",
//                            "extern2_id" => "0",
//                            "extern2_nama" => "",
//                            "jenis" => $jenis,
//                            "transaksi_id" => $transaksi_id,
//                            "transaksi_no" => $transaksi_no,
//                            "harga" => $kredit,
//                            "fulldate" => $fulldate,
//                            "dtime" => $dtime,
//                        ),
//                    );
//                    break;
//                case "9912":
//                    $tr = New MdlTransaksi();
//                    $tr->setFilters(array());
//                    $tr->setJointSelectFields("main,transaksi_id");
//                    $tr->addFilter("transaksi_id=$transaksi_id");
//                    $trReg = $tr->lookupDataRegistries()->result();
//                    showLast_query("biru");
//                    $regMain = blobDecode($trReg[0]->main);
//                    if (!is_array($regMain)) {
//                        $regMain = blobDecode($regMain);
//                    }
//                    $pihakExternMasterID = $regMain["pihakExternMasterID"];
//                    switch ($pihakExternMasterID) {
//                        case "582":
//                            $externID = "4010010";
//                            $externNama = "lokal";
//                            break;
//                        case "382":
//                            $externID = "4010020";
//                            $externNama = "export";
//                            break;
//                        case "588":
//                            $externID = "4010030";
//                            $externNama = "project";
//                            break;
//                        case "7499":
//                            $externID = "4010030";
//                            $externNama = "project";
//                            break;
//                    }
//                    $arrPembantu = array(
//                        "loop" => array(
//                            "4010" => $debet * -1,// penjualan
//                        ),
//                        "static" => array(
//                            "cabang_id" => $cabang_id,
//                            "extern_id" => $externID,
//                            "extern_nama" => $externNama,
//                            "extern2_id" => "0",
//                            "extern2_nama" => "",
//                            "jenis" => $jenis,
//                            "transaksi_id" => $transaksi_id,
//                            "transaksi_no" => $transaksi_no,
//                            "harga" => $debet,
//                            "fulldate" => $fulldate,
//                            "dtime" => $dtime,
//                        ),
//                    );
//                    break;
//
//                case "588so":
//                    $arrPembantu = array(
//                        "loop" => array(
//                            "4010" => $kredit,// penjualan
//                        ),
//                        "static" => array(
//                            "cabang_id" => $cabang_id,
//                            "extern_id" => "4010030",
//                            "extern_nama" => "project",
//                            "extern2_id" => "0",
//                            "extern2_nama" => "",
//                            "jenis" => $jenis,
//                            "transaksi_id" => $transaksi_id,
//                            "transaksi_no" => $transaksi_no,
//                            "harga" => $kredit,
//                            "fulldate" => $fulldate,
//                            "dtime" => $dtime,
//                        ),
//                    );
//                    break;
//                case "7499":
//                    $arrPembantu = array(
//                        "loop" => array(
//                            "4010" => $kredit,// penjualan
//                        ),
//                        "static" => array(
//                            "cabang_id" => $cabang_id,
//                            "extern_id" => "4010030",
//                            "extern_nama" => "project",
//                            "extern2_id" => "0",
//                            "extern2_nama" => "",
//                            "jenis" => $jenis,
//                            "transaksi_id" => $transaksi_id,
//                            "transaksi_no" => $transaksi_no,
//                            "harga" => $kredit,
//                            "fulldate" => $fulldate,
//                            "dtime" => $dtime,
//                        ),
//                    );
//                    break;

                case "982":
                    $arrPembantu = array(
                        "loop" => array(
                            "4020" => $debet,// return penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "4020010",
                            "extern_nama" => "lokal",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $debet,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
                case "999_0":
                case "999_1":
                    $arrPembantu = array(
                        "loop" => array(
                            "4020" => $nilai,// return penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "4020010",
                            "extern_nama" => "lokal",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $debet + $kredit,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
            }

            if (sizeof($arrPembantu) > 0) {

                $cpp = New ComRekeningPembantuReturnPenjualan();
                $cpp->pair($arrPembantu) or die("Tidak berhasil memasang  values pada komponen");
                $cpp->exec() or die("Gagal saat berusaha  exec values pada komponen");
            }

        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();

        cekHijau("<h2>-- DONE --</h2>");

    }


    // endregion PENJUALAN ----------------------------------------

    // region HPP ----------------------------------------

    // RUN menggabung rekening penjualan dan penjualan project
    public function generateHpp()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_1 = "hpp";
        $rek_2 = "hpp projek";
        $tabel_1 = "__rek_master__hpp";
        $tabel_2 = "__rek_master__hpp_projek";
        $tabel_baru = "__rek_master__hpp_bak";

        // region membaca data mutasi rekening 1
        $cr = New ComRekening();
//        $cr->addFilter("cabang_id in ('" . implode("','", $cabangIDs) . "')");
        $crResult = $cr->fetchMoves($rek_1);
        showLast_query("biru");
        $addDataMutasi = array();
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $arrSpec = (array)$spec;
                $sthn = $arrSpec['thn'];
                $sbln = $arrSpec['bln'];
                $stgl = $arrSpec['tgl'];
                if (($sthn != "0000") && ($arrSpec["jenis"] == "")) {
                    $dtime_new = "$sthn-$sbln-$stgl 00:00:00";
                    $arrSpec["dtime"] = $dtime_new;
                }
                $arrSpec['rekening'] = $rek_1;// diberi rekening ke-1
                $addDataMutasi[$spec->cabang_id][] = $arrSpec;
            }
        }
        // endregion

        // region membaca data mutasi rekening 2
        $cr = New ComRekening();
//        $cr->addFilter("cabang_id=$cabangID");
        $crResult = $cr->fetchMoves($rek_2);
        showLast_query("biru");
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $arrSpec = (array)$spec;
                $sthn = $arrSpec['thn'];
                $sbln = $arrSpec['bln'];
                $stgl = $arrSpec['tgl'];
                if (($sthn != "0000") && ($arrSpec["jenis"] == "")) {
                    $dtime_new = "$sthn-$sbln-$stgl 00:00:00";
                    $arrSpec["dtime"] = $dtime_new;
                }
                $arrSpec['rekening'] = $rek_1;
                $addDataMutasi[$spec->cabang_id][] = $arrSpec;
            }
        }
        // endregion

        // region build array baru urut tanggal
        $addDataMutasi_new = array();
//        $addDataMutasi_dtime = array();
        foreach ($addDataMutasi as $cabang_id => $addDataMutasiSpec) {
            foreach ($addDataMutasiSpec as $subAddDataMutasiSpec) {
                $dtime = $subAddDataMutasiSpec['dtime'];
                $addDataMutasi_new[$cabang_id][$dtime][] = $subAddDataMutasiSpec;
//            $addDataMutasi_dtime[$cabang_id][$dtime][] = $dtime;
            }
        }
        // endregion

//        arrPrintHijau($addDataMutasi);
//        arrPrintPink($addDataMutasi_new);
//        mati_disini(__LINE__);
//

        $this->db->trans_start();

        $no = 0;
        foreach ($addDataMutasi_new as $cabang_id => $cSpec) {
            ksort($cSpec);
            foreach ($cSpec as $dtimee => $cdSpec) {
//                arrPrintPink($cdSpec);
                foreach ($cdSpec as $cdiSpec) {
                    $no++;
                    $tabel_id = $cdiSpec['id'];
                    unset($cdiSpec['id']);
                    $this->db->insert($tabel_baru, $cdiSpec);

                    cekHijau($no);
                    showLast_query("hijau");
                }
            }
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();


        cekHijau("<h2>-- DONE --</h2>");

    }

    // RUN memperbaiki mutasi, update kredit_awal, kredit_akhir
    public function generateHppMutasi()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_1 = "hpp";
        $rek_2 = "hpp projek";
        $tabel_1 = "__rek_master__hpp";
        $tabel_2 = "__rek_master__hpp_projek";
        $tabel_baru = "__rek_master__hpp_bak";

        // region membaca tabel baru _bak
//        $this->db->where("cabang_id", $cabangID);
        $this->db->order_by('id', 'ASC');
        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
        $addDataMutasi = array();
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $cabang_id = $spec->cabang_id;
                $arrSpec = (array)$spec;
                $arrSpec['rekening'] = $rek_1;
                $addDataMutasi[$cabang_id][] = $arrSpec;
            }
        }
        // endregion
//        arrprintPink($addDataMutasi[30]);

        $this->db->trans_start();

        //region memperbaiki hitungan debet_awal dan debet_akhir mutasi
        foreach ($addDataMutasi as $cabang_id => $cSpec) {
            foreach ($cSpec as $ii => $spec) {
                $tabel_id = $spec['id'];
                if ($ii == 0) {
                    $firs_debet_awal = $spec['debet_akhir'] * 1;
                }
                if ($ii > 0) {
                    $debet['debet_awal'] = $firs_debet_awal;
                    $debet_akhir = $firs_debet_awal + $spec['debet'] - $spec['kredit'];
                    $debet['debet_akhir'] = $debet_akhir;
                    $debet['id'] = $spec['id'];
                    $debet['rekening'] = $spec['rekening'];
                    $debet['cabang_id'] = $spec['cabang_id'];
                    $debet['debet'] = $spec['debet'];
                    $debet['kredit'] = $spec['kredit'];
                    $contens[$cabang_id][$ii] = $debet;
//                $contens[$ii] = $spec + $debet;
//                if($ii == 2){
//                    cekHere("baris: $ii, debet_awal: $firs_debet_awal, debet_akhir: $debet_akhir");
//                    mati_disini(__LINE__);
//                }
//
                    $firs_debet_awal = $debet_akhir;
                }
            }
        }
        //endregion
//        arrPrintHijau($contens);


        //region mengupdate debet_awal dan debet_akhir mutasi
        foreach ($contens as $cabang_id => $cContensSpec) {
            foreach ($cContensSpec as $ii => $contensSpec) {

                $tabel_id = $contensSpec['id'];
                $debet_awal = $contensSpec['debet_awal'];
                $debet_akhir = $contensSpec['debet_akhir'];

                $updateData = array(
                    "debet_awal" => $debet_awal,
                    "debet_akhir" => $debet_akhir,
                );
                $where = array("id" => $tabel_id);
                $this->db->where($where);
                $this->db->update($tabel_baru, $updateData);
                showLast_query("ungu");

            }

        }
        //endregion


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();


        cekHijau("<h2>-- DONE --</h2>");

    }

    // RUN memperbaiki cache rekening
    public function generateHppCache()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
//        $rek_1 = "pph22";
        $rek_2 = "hpp";
//        $tabel_1 = "__rek_master__pph22";
//        $tabel_2 = "__rek_master__pph22_dibayar_dimuka";
        $tabel_baru = "__rek_master__hpp_bak";

        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
        $arrCacheRekening = array();
        foreach ($crResult as $crResultSpec) {
            $jenis = $crResultSpec->jenis;
            $cabang_id = $crResultSpec->cabang_id;
            $fulldate = $crResultSpec->fulldate;
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];
            $date_harian = $fulldate;
            $date_bulanan = "$thn-$bln";
            $date_tahunan = "$thn";
            $date_forever = "1";

            // region forever
            if (!isset($arrCacheRekening['forever'][$cabang_id][$date_forever])) {
                $arrCacheRekening['forever'][$cabang_id][$date_forever] = array();
            }
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["rek_id"] = $crResultSpec->rek_id;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["rekening"] = $crResultSpec->rekening;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["cabang_id"] = $crResultSpec->cabang_id;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["debet"] = $crResultSpec->debet_akhir;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["kredit"] = $crResultSpec->kredit_akhir;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["tgl"] = $tgl;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["bln"] = $bln;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["thn"] = $thn;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["dtime"] = $crResultSpec->dtime;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["fulldate"] = $fulldate;
            $arrCacheRekening['forever'][$cabang_id][$date_forever]["periode"] = "forever";
            // endregion forever

            if ($jenis != "") {

                // region tahunan
                if (!isset($arrCacheRekening['tahunan'][$cabang_id][$date_tahunan])) {
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan] = array();
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["cabang_id"] = $crResultSpec->cabang_id;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["debet"] = 0;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["kredit"] = 0;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["tgl"] = $tgl;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["bln"] = $bln;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["thn"] = $thn;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["fulldate"] = $fulldate;
                    $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["periode"] = "tahunan";
                }
//            $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["debet"] += 0;
                $arrCacheRekening['tahunan'][$cabang_id][$date_tahunan]["debet"] += ($crResultSpec->debet - $crResultSpec->kredit);
                // endregion tahunan

                // region bulanan
                if (!isset($arrCacheRekening['bulanan'][$cabang_id][$date_bulanan])) {
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan] = array();
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["cabang_id"] = $crResultSpec->cabang_id;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["debet"] = 0;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["kredit"] = 0;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["tgl"] = $tgl;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["bln"] = $bln;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["thn"] = $thn;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["fulldate"] = $fulldate;
                    $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["periode"] = "bulanan";
                }
                $arrCacheRekening['bulanan'][$cabang_id][$date_bulanan]["debet"] += ($crResultSpec->debet - $crResultSpec->kredit);
                // endregion bulanan

                // region harian
                if (!isset($arrCacheRekening['harian'][$cabang_id][$date_harian])) {
                    $arrCacheRekening['harian'][$cabang_id][$date_harian] = array();
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["cabang_id"] = $crResultSpec->cabang_id;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["debet"] = 0;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["kredit"] = 0;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["tgl"] = $tgl;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["bln"] = $bln;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["thn"] = $thn;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["fulldate"] = $fulldate;
                    $arrCacheRekening['harian'][$cabang_id][$date_harian]["periode"] = "harian";
                }
                $arrCacheRekening['harian'][$cabang_id][$date_harian]["debet"] += ($crResultSpec->debet - $crResultSpec->kredit);
                // endregion harian

            }
        }

//        arrPrintHijau($arrCacheRekening['tahunan']);
//        mati_disini(__LINE__);

        $this->db->trans_start();


        foreach ($arrCacheRekening as $periode => $cacheSpec) {
            foreach ($cacheSpec as $cabang_id => $sSpec) {
                foreach ($sSpec as $subSpec) {

//                    arrPrintPink($subSpec);
                    $tgl = $subSpec['tgl'];
                    $bln = $subSpec['bln'];
                    $thn = $subSpec['thn'];
                    $rekening = $subSpec['rekening'];

                    $rc = New ComRekening();
                    $rc->setFilters(array());
                    switch ($periode) {
                        case "harian":
                            $rc->addFilter("tgl='$tgl'");
                            $rc->addFilter("bln='$bln'");
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "bulanan":
                            $rc->addFilter("bln='$bln'");
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "tahunan":
                            $rc->addFilter("thn='$thn'");
                            break;
                        case "forever":

                            break;
                    }
                    $rc->addFilter("rekening='$rekening'");
                    $rc->addFilter("cabang_id='$cabang_id'");
                    $rc->addFilter("periode='$periode'");
                    $result = $rc->lookUpAll()->result();
                    showLast_query("biru");
                    if (sizeof($result) == 0) {
                        // insert baru
                        $anu = $rc->addData($subSpec);
                        showLast_query("hijau");
                    }
                    else {
                        // update yang sudah ada
                        $tbl_id = $result[0]->id;
                        $where = array("id" => $tbl_id);
                        $anu = $rc->updateData($where, $subSpec);
                        showLast_query("orange");
                    }

                }
            }
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    // mengembalikan data mutasi ke tabel asalnya
    public function generateHppMutasiAsal()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $rek_1 = "hpp";
        $rek_2 = "hpp projek";
        $tabel_1 = "__rek_master__hpp";
        $tabel_2 = "__rek_master__hpp_projek";
        $tabel_baru = "__rek_master__hpp_bak";

//        $this->db->where("cabang_id", $cabangID);
        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");
        $addDataMutasi = array();
        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $arrSpec = (array)$spec;
                $arrSpec['rekening'] = $rek_1;
                $addDataMutasi[$ii] = $arrSpec;
            }
        }


        $this->db->trans_start();


        foreach ($addDataMutasi as $ii => $spec) {
            unset($spec['id']);
            $anu = $this->db->insert($tabel_1, $spec);
            showLast_query("orange");
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();


        cekHijau("<h2>-- DONE --</h2>");

    }

    // RUN pembantu hpp lokal, eksport, project
    public function generateHppPembantu()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuHpp");
        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
//        $rek_1 = "pph22";
        $rek_2 = "hpp";
//        $tabel_1 = "__rek_master__pph22";
//        $tabel_2 = "__rek_master__pph22_dibayar_dimuka";
        $tabel_baru = "__rek_master__hpp_bak";

        $crResult = $this->db->get($tabel_baru)->result();
        showLast_query("biru");


        $this->db->trans_start();


        $arrCacheRekening = array();
        foreach ($crResult as $crResultSpec) {
            $jenis = $crResultSpec->jenis;
            $cabang_id = $crResultSpec->cabang_id;
            $transaksi_id = $crResultSpec->transaksi_id;
            $transaksi_no = $crResultSpec->transaksi_no;
//            $debet = $crResultSpec->debet;
//            $kredit = $crResultSpec->kredit;
            $debet = $crResultSpec->kredit;
            $kredit = $crResultSpec->debet;
            $dtime = $crResultSpec->dtime;
            $fulldate = $crResultSpec->fulldate;
            $fulldate_ex = explode("-", $fulldate);
            $tgl = $fulldate_ex[2];
            $bln = $fulldate_ex[1];
            $thn = $fulldate_ex[0];

            $arrPembantu = array();
            switch ($jenis) {
                case "582spd":
                    $arrPembantu = array(
//                    "comName" => "RekeningPembantuPenjualan",// lokal
                        "loop" => array(
                            "5010" => $kredit,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "5010010",
                            "extern_nama" => "lokal",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $kredit,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
                case "382spd":
                    $arrPembantu = array(
                        "loop" => array(
                            "5010" => $kredit,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "5010020",
                            "extern_nama" => "export",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $kredit,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
                case "9912":
                    $tr = New MdlTransaksi();
                    $tr->setFilters(array());
                    $tr->setJointSelectFields("main,transaksi_id");
                    $tr->addFilter("transaksi_id=$transaksi_id");
                    $trReg = $tr->lookupDataRegistries()->result();
                    showLast_query("biru");
                    $regMain = blobDecode($trReg[0]->main);
                    if (!is_array($regMain)) {
                        $regMain = blobDecode($regMain);
                    }
                    $pihakExternMasterID = $regMain["pihakExternMasterID"];
                    switch ($pihakExternMasterID) {
                        case "582":
                            $externID = "5010010";
                            $externNama = "lokal";
                            break;
                        case "382":
                            $externID = "5010020";
                            $externNama = "export";
                            break;
                        case "588":
                            $externID = "5010030";
                            $externNama = "project";
                            break;
                        case "7499":
                            $externID = "5010030";
                            $externNama = "project";
                            break;
                    }
                    $arrPembantu = array(
                        "loop" => array(
                            "5010" => $debet * -1,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => $externID,
                            "extern_nama" => $externNama,
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $debet,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;

                case "588so":
                    $arrPembantu = array(
                        "loop" => array(
                            "5010" => $kredit,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "5010030",
                            "extern_nama" => "project",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $kredit,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
                case "7499":
                    $arrPembantu = array(
                        "loop" => array(
                            "5010" => $kredit,// penjualan
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "extern_id" => "5010030",
                            "extern_nama" => "project",
                            "extern2_id" => "0",
                            "extern2_nama" => "",
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "harga" => $kredit,
                            "fulldate" => $fulldate,
                            "dtime" => $dtime,
                        ),
                    );
                    break;
            }

            if (sizeof($arrPembantu) > 0) {
                arrPrintPink($arrPembantu);
                $cpp = New ComRekeningPembantuHpp();
                $cpp->pair($arrPembantu) or die("Tidak berhasil memasang  values pada komponen");
                $cpp->exec() or die("Gagal saat berusaha  exec values pada komponen");
            }

//            if($jenis == "582spd"){
//                cekMerah(":: SETOP ::");
//                break;
//            }
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();

        cekHijau("<h2>-- DONE --</h2>");

    }

    // endregion HPP ----------------------------------------

    // region PEMBANTU AKTIVA KE AKTIVA BESAR ----------------------------------------
    public function generatePembantuToAktivaTetap()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuAktivaTetapItem");
        $this->load->model("Mdls/MdlAccounts");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        $this->load->helper("he_angka");

        $tbl_master = "__rek_pembantu_aktivatetap__aktiva_tetap";
        $tbl_pembantu = array(
            "__rek_pembantu_aktiva_berwujud__kendaraan",
            "__rek_pembantu_aktiva_berwujud__mesin",
            "__rek_pembantu_aktiva_berwujud__mesin_produksi",
            "__rek_pembantu_aktiva_berwujud__peralatan_kantor",
            "__rek_pembantu_aktiva_berwujud__peralatan_produksi",
            "__rek_pembantu_aktiva_berwujud__tanah_dan_bangunan",
        );

        $ac = New MdlAccounts();
        $acTmp = $ac->lookUpTransactionStructureLabel_old();
        $acTmp_new = $ac->lookUpTransactionStructureLabel();
        $coa_code_flip = array();
        foreach ($acTmp as $coa => $rek_lama) {
            if (!is_numeric($rek_lama)) {
                if (($rek_lama !== NULL) || ($rek_lama != "pilih rekening")) {
                    $coa_code_flip[$rek_lama] = $coa;
                }
            }
        }
//arrPrintWebs($coa_code_flip);
//mati_disini(__LINE__);

        $this->db->trans_start();

        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            //region rekening master aktiva tetap
            $this->db->order_by("id", "ASC");
            $crResult = $this->db->get($tbl_master)->result();
            showLast_query("biru");
            $arrData = array();
            if (sizeof($crResult) > 0) {
                foreach ($crResult as $ii => $spec) {
                    $cabang_id = $spec->cabang_id;
                    $rekening = $spec->extern_nama;
                    $gudang_id = $spec->gudang_id;
                    $dtime = $spec->dtime;
                    $fulldate = $spec->fulldate;
                    $jenis = $spec->jenis;
                    $transaksi_id = $spec->transaksi_id;
                    $transaksi_no = $spec->transaksi_no;
                    $debet = $spec->debet;
                    $kredit = $spec->kredit;
                    $keterangan = $spec->keterangan;
                    $nilai_aktiva = ($kredit > 0) ? ($kredit * -1) : $debet;
                    $rekening_coa = isset($coa_code_flip[$rekening]) ? $coa_code_flip[$rekening] : mati_disini(__LINE__ . " :: kode coa [$rekening] belum masuk");

                    $arrData = array(
                        "loop" => array(
                            "$rekening_coa" => $nilai_aktiva,
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "dtime" => $dtime,
                            "fulldate" => $fulldate,
                            "keterangan" => $keterangan,
                            "balance" => false,
                        ),
                    );
                    arrPrintHijau($arrData);
                    $cr = New ComRekening();
                    $cr->pair($arrData);
                    $cr->exec();

                }
            }
            //endregion
        }
//        mati_disini("stop...");

        // region rekening pembantu aktiva tetap
        foreach ($tbl_pembantu as $tabel_pembantu) {

            $this->db->order_by("id", "ASC");
            $crResult_pembantu = $this->db->get($tabel_pembantu)->result();
            showLast_query("biru");
            $arrData = array();
            if (sizeof($crResult_pembantu) > 0) {
                foreach ($crResult_pembantu as $ii => $spec_pembantu) {
                    $cabang_id = $spec_pembantu->cabang_id;
                    $rekening = $spec_pembantu->rekening;
                    $extern_id = $spec_pembantu->extern_id;
                    $extern_nama = $spec_pembantu->extern_nama;
                    $gudang_id = $spec_pembantu->gudang_id;
                    $dtime = $spec_pembantu->dtime;
                    $fulldate = $spec_pembantu->fulldate;
                    $jenis = $spec_pembantu->jenis;
                    $transaksi_id = $spec_pembantu->transaksi_id;
                    $transaksi_no = $spec_pembantu->transaksi_no;
                    $debet = $spec_pembantu->debet;
                    $kredit = $spec_pembantu->kredit;
                    $qty_debet = $spec_pembantu->qty_debet;
                    $qty_kredit = $spec_pembantu->qty_kredit;
                    $keterangan = $spec_pembantu->keterangan;
                    $nilai_aktiva = ($kredit > 0) ? ($kredit * -1) : $debet;
                    $qty_aktiva = ($qty_kredit > 0) ? ($qty_kredit * -1) : $qty_debet;
                    $rekening_coa = isset($coa_code_flip[$rekening]) ? $coa_code_flip[$rekening] : mati_disini(__LINE__ . " :: kode coa [$rekening] belum masuk");

//                    if($rekening == "tanah dan bangunan"){
//                        cekungu(":: $rekening_coa :: $rekening_coa ::");
//                        mati_disini(__LINE__);
//                    }

                    $arrData[0] = array(
                        "loop" => array(
                            "$rekening_coa" => $nilai_aktiva,
                        ),
                        "static" => array(
                            "cabang_id" => $cabang_id,
                            "gudang_id" => $gudang_id,
                            "jenis" => $jenis,
                            "transaksi_id" => $transaksi_id,
                            "transaksi_no" => $transaksi_no,
                            "dtime" => $dtime,
                            "fulldate" => $fulldate,
                            "keterangan" => $keterangan,
                            "extern_id" => $extern_id,
                            "extern_nama" => $extern_nama,
                            "produk_nilai" => $debet + $kredit,
                            "produk_qty" => $qty_aktiva,
                        ),
                    );
                    arrPrintPink($arrData);
                    $crp = New ComRekeningPembantuAktivaTetapItem();
                    $crp->pair($arrData);
                    $crp->exec();
                }
            }

        }
        // endregion rekening pembantu aktiva tetap

        mati_disini(" OHOOOO ");
        $this->db->trans_complete();

        cekHijau("<h2>-- DONE --</h2>");

    }
    // endregion PEMBANTU AKTIVA KE AKTIVA BESAR ----------------------------------------

    // region GENERATOR STOK RIIL ----------------------------------------
    public function generatePembantuProduk()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Coms/ComRekeningPembantuSupplies");
        $this->load->model("Mdls/MdlFifoProdukJadi");
        $this->load->model("Mdls/MdlFifoSupplies");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $arrCabang = array(
            "-1",
//            "1",
//            "21",
//            "25",
//            "26",
//            "27",
//            "28",
//            "29",
//            "30",
//            "31",
        );
        $rekening = "1010030030";//persediaan produk

//        $crp = New ComRekeningPembantuProduk();
//        $crp->addFilter("cabang_id in ('" . implode("','", $arrCabang) . "')");
//        $crpTmp = $crp->fetchBalances($rekening);
//        showLast_query("biru");
//        arrPrintWebs($crpTmp);


        $cfp = New MdlFifoProdukJadi();
        $cfs = New MdlFifoSupplies();
        //-------------------------
        $cfp->addFilter("unit>0");
        $cfpResult = $cfp->lookupAll()->result();
        $total_ppv_produk = 0;
        $arrProdukCabang = array();
        $arrProdukPpvCabang = array();
        foreach ($cfpResult as $ii => $cfpResultSpec) {
//            arrPrintHijau($cfpResultSpec);
//            break;
            if (!isset($arrProdukPpvCabang[$cfpResultSpec->cabang_id]['ppv'])) {
                $arrProdukPpvCabang[$cfpResultSpec->cabang_id]['ppv'] = 0;
            }
            $arrProdukPpvCabang[$cfpResultSpec->cabang_id]['ppv'] += $cfpResultSpec->ppv_nilai_riil;


            $total_ppv_produk += $cfpResultSpec->ppv_nilai_riil;

            if ($cfpResultSpec->ppv_nilai_riil > 0) {
                $arrProdukCabang[$ii] = $cfpResultSpec;
            }

//            if(!isset($arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'])){
//                $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'] = 0;
//            }
//            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'] += $cfpResultSpec->ppv_nilai_riil;
//
//            if(!isset($arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'])){
//                $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'] = 0;
//            }
//            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'] += $cfpResultSpec->unit;
//            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['nama'] = $cfpResultSpec->produk_nama;
//            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['cabang_id'] = $cfpResultSpec->cabang_id;
//            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['gudang_id'] = $cfpResultSpec->gudang_id;

        }
        //-------------------------
        $cfs->addFilter("unit>0");
        $cfsResult = $cfs->lookupAll()->result();
        $total_ppv_supplies = 0;
        $arrSuppliesCabang = array();
        foreach ($cfsResult as $cfpResultSpec) {
//            arrPrintHijau($cfpResultSpec);
//            break;

            $total_ppv_supplies += $cfpResultSpec->ppv_nilai_riil;

//            if(!isset($arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'])){
//                $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'] = 0;
//            }
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'] += $cfpResultSpec->ppv_nilai_riil;
//
//            if(!isset($arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'])){
//                $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'] = 0;
//            }
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'] += $cfpResultSpec->unit;
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['nama'] = $cfpResultSpec->produk_nama;
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['cabang_id'] = $cfpResultSpec->cabang_id;
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['gudang_id'] = $cfpResultSpec->gudang_id;
        }
        //-------------------------
        $total_ppv = $total_ppv_produk + $total_ppv_supplies;


//        cekMerah("ppv produk: $total_ppv_produk");
//        cekMerah("ppv supplies: $total_ppv_supplies");
//        cekMerah("ppv total: $total_ppv");

//        arrPrintHijau($arrProdukPpvCabang);
//        arrPrintKuning($arrProdukCabang);

//        mati_disini(__LINE__);

        $this->db->trans_start();

        if (sizeof($arrProdukCabang) > 0) {
            foreach ($arrProdukCabang as $spec) {
                $tbl_id = $spec->id;
                $produk_id = $spec->produk_id;
                $produk_nama = $spec->produk_nama;
                $cabang_id = $spec->cabang_id;
                $gudang_id = $spec->gudang_id;
                $ppv = $spec->ppv_riil;
                $sub_ppv = $spec->ppv_nilai_riil;
                $hpp_riil = $spec->hpp_riil;
                $sub_hpp_riil = $spec->jml_nilai_riil;
                $hpp = $spec->hpp;
                $sub_hpp = $spec->jml_nilai;

                //region rekening pembantu produk
                $arrData[0] = array(
                    "loop" => array(
                        "$rekening" => $sub_ppv * -1,
                    ),
                    "static" => array(
                        "cabang_id" => $cabang_id,
                        "extern_id" => $produk_id,
                        "extern_nama" => $produk_nama,
                        "produk_qty" => 0,
                        "produk_nilai" => $sub_ppv,
                        "gudang_id" => $gudang_id,
                        "jenis" => 0,
                        "transaksi_no" => 0,
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    ),
                );
                $cc = New ComRekeningPembantuProduk();
                $cc->pair($arrData) or die("gagal pair....");
                $cc->exec() or die("gagal exec....");
                //endregion


                //region switch kolom fifo, hpp -> hpp_nppv dan hpp_riil -> hpp
                if ($sub_hpp_riil > 0) {
                    $dataUpdate = array(
                        "hpp" => $hpp_riil,
                        "jml_nilai" => $sub_hpp_riil,
                        "hpp_nppv" => $hpp,
                        "jml_nilai_nppv" => $sub_hpp,
                    );
                    $where = array(
                        "id" => $tbl_id
                    );
                    $cfp = New MdlFifoProdukJadi();
                    $cfp->updateData($where, $dataUpdate);
                    showLast_query("orange");
                }
                //endregion

//                mati_disini();
            }
        }


//        mati_disini(" OHOOOO ");
        $this->db->trans_complete();

        cekHijau("<h2>-- DONE --</h2>");

    }

    public function generatePembantuSupplies()
    {
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Coms/ComRekeningPembantuSupplies");
        $this->load->model("Mdls/MdlFifoProdukJadi");
        $this->load->model("Mdls/MdlFifoSupplies");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        //-----------------------------
        $arrCabang = array(
            "-1",
//            "1",
//            "21",
//            "25",
//            "26",
//            "27",
//            "28",
//            "29",
//            "30",
//            "31",
        );
        $rekening = "1010030010";//persediaan supplies

//        $crp = New ComRekeningPembantuProduk();
//        $crp->addFilter("cabang_id in ('" . implode("','", $arrCabang) . "')");
//        $crpTmp = $crp->fetchBalances($rekening);
//        showLast_query("biru");
//        arrPrintWebs($crpTmp);


        $cfp = New MdlFifoProdukJadi();
        $cfs = New MdlFifoSupplies();
        //-------------------------
//        $cfp->addFilter("unit>0");
//        $cfpResult = $cfp->lookupAll()->result();
//        $total_ppv_produk = 0;
//        $arrProdukCabang = array();
//        $arrProdukPpvCabang = array();
//        foreach ($cfpResult as $ii => $cfpResultSpec) {
////            arrPrintHijau($cfpResultSpec);
////            break;
//            if (!isset($arrProdukPpvCabang[$cfpResultSpec->cabang_id]['ppv'])) {
//                $arrProdukPpvCabang[$cfpResultSpec->cabang_id]['ppv'] = 0;
//            }
//            $arrProdukPpvCabang[$cfpResultSpec->cabang_id]['ppv'] += $cfpResultSpec->ppv_nilai_riil;
//
//
//            $total_ppv_produk += $cfpResultSpec->ppv_nilai_riil;
//
//            if ($cfpResultSpec->ppv_nilai_riil > 0) {
//                $arrProdukCabang[$ii] = $cfpResultSpec;
//            }
//
////            if(!isset($arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'])){
////                $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'] = 0;
////            }
////            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'] += $cfpResultSpec->ppv_nilai_riil;
////
////            if(!isset($arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'])){
////                $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'] = 0;
////            }
////            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'] += $cfpResultSpec->unit;
////            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['nama'] = $cfpResultSpec->produk_nama;
////            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['cabang_id'] = $cfpResultSpec->cabang_id;
////            $arrProdukCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['gudang_id'] = $cfpResultSpec->gudang_id;
//
//        }
        //-------------------------
        $cfs->addFilter("unit>0");
        $cfsResult = $cfs->lookupAll()->result();
        $total_ppv_supplies = 0;
        $arrSuppliesCabang = array();
        $arrSuppliesPpvCabang = array();
        foreach ($cfsResult as $ii => $cfpResultSpec) {
//            arrPrintHijau($cfpResultSpec);
//            break;
            if (!isset($arrSuppliesPpvCabang[$cfpResultSpec->cabang_id]['ppv'])) {
                $arrSuppliesPpvCabang[$cfpResultSpec->cabang_id]['ppv'] = 0;
            }
            $arrSuppliesPpvCabang[$cfpResultSpec->cabang_id]['ppv'] += $cfpResultSpec->ppv_nilai_riil;

            $total_ppv_supplies += $cfpResultSpec->ppv_nilai_riil;

            if ($cfpResultSpec->ppv_nilai_riil > 0) {
                $arrSuppliesCabang[$ii] = $cfpResultSpec;
            }

//            if(!isset($arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'])){
//                $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'] = 0;
//            }
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['ppv'] += $cfpResultSpec->ppv_nilai_riil;
//
//            if(!isset($arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'])){
//                $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'] = 0;
//            }
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['qty'] += $cfpResultSpec->unit;
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['nama'] = $cfpResultSpec->produk_nama;
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['cabang_id'] = $cfpResultSpec->cabang_id;
//            $arrSuppliesCabang[$cfpResultSpec->cabang_id][$cfpResultSpec->produk_id]['gudang_id'] = $cfpResultSpec->gudang_id;
        }
        //-------------------------
//        $total_ppv = $total_ppv_produk + $total_ppv_supplies;


//cekMerah("ppv produk: $total_ppv_produk");
//cekMerah("ppv supplies: $total_ppv_supplies");
//cekMerah("ppv total: $total_ppv");

//        arrPrintHijau($arrSuppliesPpvCabang);
//        arrPrintKuning($arrSuppliesCabang);

//mati_disini(__LINE__);

        $this->db->trans_start();

        if (sizeof($arrSuppliesCabang) > 0) {
            foreach ($arrSuppliesCabang as $spec) {
                $tbl_id = $spec->id;
                $produk_id = $spec->produk_id;
                $produk_nama = $spec->produk_nama;
                $cabang_id = $spec->cabang_id;
                $gudang_id = $spec->gudang_id;
                $ppv = $spec->ppv_riil;
                $sub_ppv = $spec->ppv_nilai_riil;
                $hpp_riil = $spec->hpp_riil;
                $sub_hpp_riil = $spec->jml_nilai_riil;
                $hpp = $spec->hpp;
                $sub_hpp = $spec->jml_nilai;

                //region rekening pembantu produk
                $arrData[0] = array(
                    "loop" => array(
                        "$rekening" => $sub_ppv * -1,
                    ),
                    "static" => array(
                        "cabang_id" => $cabang_id,
                        "extern_id" => $produk_id,
                        "extern_nama" => $produk_nama,
                        "produk_qty" => 0,
                        "produk_nilai" => $sub_ppv,
                        "gudang_id" => $gudang_id,
                        "jenis" => 0,
                        "transaksi_no" => 0,
                        "dtime" => date("Y-m-d H:i:s"),
                        "fulldate" => date("Y-m-d"),
                    ),
                );
                $cc = New ComRekeningPembantuSupplies();
                $cc->pair($arrData) or die("gagal pair....");
                $cc->exec() or die("gagal exec....");
                //endregion


                //region switch kolom fifo, hpp -> hpp_nppv dan hpp_riil -> hpp
                if ($sub_hpp_riil > 0) {
                    $dataUpdate = array(
                        "hpp" => $hpp_riil,
                        "jml_nilai" => $sub_hpp_riil,
                        "hpp_nppv" => $hpp,
                        "jml_nilai_nppv" => $sub_hpp,
                    );
                    $where = array(
                        "id" => $tbl_id
                    );
                    $cfp = New MdlFifoSupplies();
                    $cfp->updateData($where, $dataUpdate);
                    showLast_query("orange");
                }
                //endregion

//                mati_disini();
            }
        }


//        mati_disini(" OHOOOO ");
        $this->db->trans_complete();

        cekHijau("<h2>-- DONE --</h2>");

    }

    // endregion GENERATOR STOK RIIL ----------------------------------------

    public function generateCounterRekening()
    {
//        $stopCommit = true;
        $stopCommit = false;

        $this->load->model("Mdls/MdlAccounts");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");
        $this->load->helper("he_angka");

        $tbl = "counters_rekening_number";
        $tbl2 = "counters_number";

        $ac = New MdlAccounts();
        $acTmp = $ac->lookUpTransactionStructureLabel_old();
        $acTmp_new = $ac->lookUpTransactionStructureLabel();
        $coa_code_flip = array();
        foreach ($acTmp as $coa => $rek_lama) {
            if (!is_numeric($rek_lama)) {
                if (($rek_lama !== NULL) || ($rek_lama != "pilih rekening")) {
                    $coa_code_flip[$rek_lama] = $coa;
                }
            }
        }

        $this->db->order_by("id", "ASC");
        $crResult = $this->db->get($tbl)->result();
        showLast_query("biru");

        $this->db->order_by("id", "ASC");
        $crResult2 = $this->db->get($tbl2)->result();


        $this->db->trans_start();


        if (sizeof($crResult) > 0) {
            foreach ($crResult as $ii => $spec) {
                $tbl_id = $spec->id;
                $rekening = $spec->rekening;
                if (!is_numeric($rekening)) {
                    $rekening_coa = isset($coa_code_flip[$rekening]) ? $coa_code_flip[$rekening] : NULL;
                    if ($rekening_coa != NULL) {
                        $rekening_coa_alias = "";
                        $data = array(
                            "rekening" => $rekening_coa,
                            "rekening_2" => $spec->rekening,
                            "rekening_alias" => isset($acTmp_new[$rekening_coa]) ? strtolower($acTmp_new[$rekening_coa]) : "",
                        );
                        $where = array(
                            "id" => $tbl_id,
                        );
                        $this->db->where($where);
                        $this->db->update($tbl, $data);
                        showLast_query("ungu");
                    }
                }
            }
        }

        if (sizeof($crResult2) > 0) {
            foreach ($crResult2 as $ii => $spec2) {
                $tbl_id = $spec2->id;
                $pkeys = $spec2->p_keys;
                $pvalues = $spec2->p_values;
                $prefix = "company|rekening";
                if (substr($pkeys, 0, 16) == $prefix) {
                    $pvalues_ex = explode("|", $pvalues);
//                    arrPrintWebs($pvalues_ex);
                    $rekening = $pvalues_ex[1];

                    if (!is_numeric($rekening)) {
                        $rekening_coa = isset($coa_code_flip[$rekening]) ? $coa_code_flip[$rekening] : NULL;

                        if ($rekening_coa != NULL) {
                            $pvalues_new = str_replace($rekening, $rekening_coa, $pvalues);

                            cekMerah("cocok : [$pkeys] [$pvalues] [$pvalues_new] : $rekening :: $rekening_coa");

                            $arrData = array(
                                "p_values" => $pvalues_new,
                                "p_values_2" => $pvalues,
                            );
                            $where = array(
                                "id" => $tbl_id
                            );
                            $this->db->where($where);
                            $this->db->update($tbl2, $arrData);
                            showLast_query("ungu");
                        }
                    }
//                    break;
                }
            }
        }

        if ($stopCommit == true) {
            mati_disini(" OHOOOO belon comit @" . __LINE__);
        }

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekLime("<h3>-- DONE --</h3>");
    }

    public function generateSaldo()
    {
        $this->load->model("Coms/ComRekening");

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");

        $fdate = "2022-01-01";
//        $arrRekTbl = array(
//            "beban lain lain" => "__rek_master__beban_lain_lain",
//            "biaya" => "__rek_master__biaya",
//            "biaya bpjs" => "__rek_master__biaya_bpjs",
//            "biaya bunga" => "__rek_master__biaya_bunga",
//            "biaya gaji" => "__rek_master__biaya_gaji",
//            "biaya import" => "__rek_master__biaya_import",
//            "biaya lain_lain" => "__rek_master__biaya_lain_lain",
//            "biaya pph21" => "__rek_master__biaya_pph21",
//            "biaya produksi" => "__rek_master__biaya_produksi",
//            "biaya sewa" => "__rek_master__biaya_sewa",
//            "biaya supplies" => "__rek_master__biaya_supplies",
//            "biaya transfer" => "__rek_master__biaya_transfer",
//            "biaya umum" => "__rek_master__biaya_umum",
//            "biaya usaha" => "__rek_master__biaya_usaha",
//            "bunga dan jasa giro" => "__rek_master__bunga_dan_jasa_giro",
//            "delivery cost" => "__rek_master__delivery_cost",
//            "direct labor" => "__rek_master__direct_labor",
//            "hpp" => "__rek_master__hpp",
//            "hpp projek" => "__rek_master__hpp_projek",
//            "jasa kirim" => "__rek_master__jasa_kirim",
//            "kerugian" => "__rek_master__kerugian",
//            "kerugian kurs" => "__rek_master__kerugian_kurs",
//            "keutungan kurs" => "__rek_master__keutungan_kurs",
//            "laba lain lain" => "__rek_master__laba_lain_lain",
//            "laba(rugi) perubahan grade produk" => "__rek_master__laba_rugi__perubahan_grade_produk",
//            "laba(rugi) perubahan grade supplies" => "__rek_master__laba_rugi__perubahan_grade_supplies",
//            "laba(rugi) selisih adjustment" => "__rek_master__laba_rugi__selisih_adjustment",
//            "laba(rugi) selisih fifo return pembelian" => "__rek_master__laba_rugi__selisih_fifo_return_pembelian",
//            "laba(rugi) selisih kurs" => "__rek_master__laba_rugi__selisih_kurs",
//            "pendapatan" => "__rek_master__pendapatan",
//            "pendapatan lain lain" => "__rek_master__pendapatan_lain_lain",
//            "penjualan" => "__rek_master__penjualan",
//            "penjualan projek" => "__rek_master__penjualan_projek",
//            "penjualan valas" => "__rek_master__penjualan_valas",
//            "penyusutan kendaraan" => "__rek_master__penyusutan_kendaraan",
//            "penyusutan mesin" => "__rek_master__penyusutan_mesin",
//            "penyusutan mesin produksi" => "__rek_master__penyusutan_mesin_produksi",
//            "penyusutan peralatan kantor" => "__rek_master__penyusutan_peralatan_kantor",
//            "penyusutan peralatan produksi" => "__rek_master__penyusutan_peralatan_produksi",
//            "penyusutan tanah dan bangunan" => "__rek_master__penyusutan_tanah_dan_bangunan",
//            "quality" => "__rek_master__quality",
//            "return penjualan" => "__rek_master__return_penjualan",
//            "rl_lain_lain" => "__rek_master__rl_lain_lain",
//            "rugi laba pembulatan ganjil" => "__rek_master__rugi_laba_pembulatan_ganjil",
//            "selisih biaya produksi" => "__rek_master__selisih_biaya_produksi",
//            "selisih pembulatan" => "__rek_master__selisih_pembulatan",
//            "selisih persediaan karena fifo" => "__rek_master__selisih_persediaan_karena_fifo",
//        );
//        foreach ($arrRekTbl as $rek => $tbl) {
////            $this->db->where(array("fulldate>=" => "$fdate"));
//            $crResult[$rek] = $this->db->get($tbl)->result();
//            showLast_query("biru");
//            cekHitam(":: $rek => $tbl :: " . sizeof($crResult[$rek]));
//        }


        $arrRekTbl = array(
            "__rek_master__1010010010",
            "__rek_master__1010010020",
            "__rek_master__1010020010",
            "__rek_master__1010020030",
            "__rek_master__1010020040",
            "__rek_master__1010020070",
            "__rek_master__1010030010",
            "__rek_master__1010030020",
            "__rek_master__1010030030",
            "__rek_master__1010030040",
            "__rek_master__1010030050",
            "__rek_master__1010030060",
            "__rek_master__1010030070",
            "__rek_master__1010040020",
            "__rek_master__1010040040",
            "__rek_master__1010040050",
            "__rek_master__1010040060",
            "__rek_master__1010040070",
            "__rek_master__1010040080",
            "__rek_master__1010040090",
            "__rek_master__1010040110",
            "__rek_master__1010040120",
            "__rek_master__1010050010",
            "__rek_master__1010050020",
            "__rek_master__1010060010",
            "__rek_master__1010060020",
            "__rek_master__1010060030",
            "__rek_master__1010060040",
            "__rek_master__1020010010",
            "__rek_master__1020010020",
            "__rek_master__1020020010",
            "__rek_master__1020020020",
            "__rek_master__1020030010",
            "__rek_master__1020030020",
            "__rek_master__1020040010",
            "__rek_master__1020040020",
            "__rek_master__1020041010",
            "__rek_master__1020041020",
            "__rek_master__1020050010",
            "__rek_master__1020050020",
            "__rek_master__1020070010",
            "__rek_master__1020070020",
            "__rek_master__1020090010",
            "__rek_master__1020100010",
            "__rek_master__1020110010",
            "__rek_master__1030010",
            "__rek_master__2010010",
            "__rek_master__2010020",
            "__rek_master__2010030",
            "__rek_master__2010040",
            "__rek_master__2010050",
            "__rek_master__2010060",
            "__rek_master__2010070",
            "__rek_master__2010080",
            "__rek_master__2010090010",
            "__rek_master__2010090020",
            "__rek_master__2020010",
            "__rek_master__2020020",
            "__rek_master__2020030",
            "__rek_master__2030010",
            "__rek_master__2030030",
            "__rek_master__2030040",
            "__rek_master__2030050",
            "__rek_master__2030060",
            "__rek_master__2030080",
            "__rek_master__2040010",
            "__rek_master__2040020",
            "__rek_master__2040030",
            "__rek_master__2040040",
            "__rek_master__3010020",
            "__rek_master__3020010",
            "__rek_master__3020020",
            "__rek_master__3020030",
            "__rek_master__3020040",
            "__rek_master__3020050",
            "__rek_master__3020060",
            "__rek_master__4010",
            "__rek_master__4020",
            "__rek_master__5010",
            "__rek_master__5020020",
            "__rek_master__5020030",
            "__rek_master__5020040",
            "__rek_master__6010",
            "__rek_master__6020",
            "__rek_master__6030",
            "__rek_master__6040010",
            "__rek_master__6040020",
            "__rek_master__6040030",
            "__rek_master__6040040",
            "__rek_master__6040050",
            "__rek_master__6040060",
            "__rek_master__6050",
            "__rek_master__6060",
            "__rek_master__6070",
            "__rek_master__6080",
            "__rek_master__6100010",
            "__rek_master__6100020",
            "__rek_master__6100030",
            "__rek_master__7010050",
            "__rek_master__7010060",
            "__rek_master__7010070",
            "__rek_master__7010080",
            "__rek_master__7010090",
            "__rek_master__7010110",
            "__rek_master__7010120",
            "__rek_master__7010130",
            "__rek_master__7010140",
            "__rek_master__7010150",
            "__rek_master__7010160",
            "__rek_master__7010170",
            "__rek_master__7010180",
            "__rek_master__7010190",
            "__rek_master__7011010",
            "__rek_master__7020010",
            "__rek_master__7020020",
            "__rek_master__7020030",
            "__rek_master__710000",
            "__rek_master__9010",
            "__rek_master__9020",
        );
        foreach ($arrRekTbl as $tbl) {
//            $crResult[$rek] = $this->db->get($tbl)->result();
//            showLast_query("biru");
//            cekHitam(":: $rek => $tbl :: " . sizeof($crResult[$rek]));
//
//            cekMerah("tabel: $tbl");
            $tbl_ex = explode("__", $tbl);
//            arrPrintKuning($tbl_ex);
            $rek = $tbl_ex[2];
            $crResult[$rek] = $this->db->get($tbl)->result();
//            showLast_query("biru");
//            cekHitam(":: $rek => $tbl :: " . sizeof($crResult[$rek]));


//            break;
        }
//        mati_disini(__LINE__);


        $arrCacheRekening = array();
        foreach ($crResult as $rek => $spec) {
            if (sizeof($spec) > 0) {
                foreach ($spec as $crResultSpec) {
//                    arrPrintKuning($crResultSpec);
                    $rekening = $crResultSpec->rekening;
                    $cabang_id = $crResultSpec->cabang_id;
                    $fulldate = $crResultSpec->fulldate;
                    $fulldate_ex = explode("-", $fulldate);
                    $tgl = $fulldate_ex[2];
                    $bln = $fulldate_ex[1];
                    $thn = $fulldate_ex[0];
                    $date_harian = $fulldate;
                    $date_bulanan = "$thn-$bln";
                    $date_tahunan = "$thn";
                    $date_forever = "1";

                    // region forever
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever] = array();
                    }
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["periode"] = "forever";
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['forever'][$cabang_id][$date_forever]["saldo_kredit_periode"] += $crResultSpec->kredit;


                    // endregion forever

                    // region tahunan
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan] = array();
                    }
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["periode"] = "tahunan";
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['tahunan'][$cabang_id][$date_tahunan]["saldo_kredit_periode"] += $crResultSpec->kredit;
                    // endregion tahunan

                    // region bulanan
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan] = array();
                    }
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["periode"] = "bulanan";
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['bulanan'][$cabang_id][$date_bulanan]["saldo_kredit_periode"] += $crResultSpec->kredit;
                    // endregion bulanan

                    // region harian
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian] = array();
                    }
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["rek_id"] = $crResultSpec->rek_id;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["rekening"] = $crResultSpec->rekening;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["cabang_id"] = $crResultSpec->cabang_id;
//            $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["debet"] = $crResultSpec->debet_akhir;
//            $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["kredit"] = $crResultSpec->kredit_akhir;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["tgl"] = $tgl;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["bln"] = $bln;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["thn"] = $thn;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["dtime"] = $crResultSpec->dtime;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["fulldate"] = $fulldate;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["periode"] = "harian";
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] = 0;
                    }
                    if (!isset($arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"])) {
                        $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] = 0;
                    }
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit"] += $crResultSpec->kredit;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_debet_periode"] += $crResultSpec->debet;
                    $arrCacheRekening[$rekening]['harian'][$cabang_id][$date_harian]["saldo_kredit_periode"] += $crResultSpec->kredit;
                    // endregion harian
                }
            }
//            break;
        }
//arrPrintHijau($arrCacheRekening);
//mati_disini(__LINE__);

        $this->db->trans_start();

        foreach ($arrCacheRekening as $rekening => $cacheSpec) {
            foreach ($cacheSpec as $periode => $subCacheSpec) {
                foreach ($subCacheSpec as $cabang_id => $sSpec) {
                    foreach ($sSpec as $subSpec) {
                        $tgl = $subSpec['tgl'];
                        $bln = $subSpec['bln'];
                        $thn = $subSpec['thn'];

                        $rc = New ComRekening();
                        $rc->setFilters(array());
                        switch ($periode) {
                            case "harian":
                                $rc->addFilter("tgl='$tgl'");
                                $rc->addFilter("bln='$bln'");
                                $rc->addFilter("thn='$thn'");
                                break;
                            case "bulanan":
                                $rc->addFilter("bln='$bln'");
                                $rc->addFilter("thn='$thn'");
                                break;
                            case "tahunan":
                                $rc->addFilter("thn='$thn'");
                                break;
                            case "forever":

                                break;
                        }
//                        $rc->addFilter("rekening='$rekening'");
                        $this->db->where("rekening", $rekening);
                        $rc->addFilter("cabang_id='$cabang_id'");
                        $rc->addFilter("periode='$periode'");
                        $result = $rc->lookUpAll()->result();
                        showLast_query("biru");
                        if (sizeof($result) == 0) {
                            // insert baru
//                            $anu = $rc->addData($subSpec);
//                            showLast_query("hijau");
                        }
                        else {
                            // update yang sudah ada
                            $subSpecAdd = array(
                                "saldo_debet" => $subSpec['saldo_debet'],
                                "saldo_kredit" => $subSpec['saldo_kredit'],
                                "saldo_debet_periode" => $subSpec['saldo_debet_periode'],
                                "saldo_kredit_periode" => $subSpec['saldo_kredit_periode'],
                            );
                            $tbl_id = $result[0]->id;
                            $where = array("id" => $tbl_id);
                            $anu = $rc->updateData($where, $subSpecAdd);
                            showLast_query("orange");
                        }
                    }
                }
            }

        }


//        mati_disini(" OHOOOO ");
        $this->db->trans_complete();
        cekHijau("<h2>-- DONE --</h2>");

    }

    public function masterCache()
    {
        $this->load->model("Coms/ComRekening");
        $arrCabang = array(
            "-1",//belum
//            "1",//done
//            "21",//done
//            "25",//done
//            "26",//done
//            "27",//done
//            "28",//done
//            "29",//done
//            "30",//done
//            "31",//done
        );
        //region forever
        $periode = "forever";
        $cr = New ComRekening();
        $cr->addFilter("cabang_id in ('" . implode("','", $arrCabang) . "')");
        $cr->addFilter("periode='$periode'");
        $this->db->order_by("rekening", "ASC");
        $crTmp = $cr->lookUpAll()->result();
        showLast_query("biru");
        $arrRekening = array();
        $arrRekeningForever = array();
        foreach ($crTmp as $crSpec) {
//            arrPrintKuning($crSpec);
            if (is_numeric($crSpec->rekening)) {
                $arrRekening[$crSpec->rekening] = $crSpec->rekening_alias;
                $arrRekeningForever[$crSpec->rekening] = $crSpec;
            }
        }
        //endregion
        //region tahunan
        $periode = "tahunan";
        $tahun = "2022";
        $cr = New ComRekening();
        $cr->addFilter("cabang_id in ('" . implode("','", $arrCabang) . "')");
        $cr->addFilter("periode='$periode'");
        $cr->addFilter("thn='$tahun'");
        $this->db->order_by("rekening", "ASC");
        $crTmp = $cr->lookUpAll()->result();
        showLast_query("biru");
        $arrRekeningTahunan = array();
        foreach ($crTmp as $crSpec) {
            if (is_numeric($crSpec->rekening)) {
                $arrRekeningTahunan[$crSpec->rekening] = $crSpec;
            }
        }
        //endregion
        //region bulanan
        $periode = "bulanan";
        $tahun = "2022";
        $bulan = "11";
        $cr = New ComRekening();
        $cr->addFilter("cabang_id in ('" . implode("','", $arrCabang) . "')");
        $cr->addFilter("periode='$periode'");
        $cr->addFilter("thn='$tahun'");
        $cr->addFilter("bln='$bulan'");
        $this->db->order_by("rekening", "ASC");
        $crTmp = $cr->lookUpAll()->result();
        showLast_query("biru");
        $arrRekeningBulanan = array();
        foreach ($crTmp as $crSpec) {
            if (is_numeric($crSpec->rekening)) {
                $arrRekeningBulanan[$crSpec->rekening] = $crSpec;
            }
        }
        //endregion


        //--------------------------

        $str = "";
        $str .= "<table rules='all' style='border: 1px solid black;'>";

        $str .= "<tr>";
        $str .= "<td>no</td>";
        $str .= "<td>coa</td>";
        $str .= "<td>alias</td>";
        $str .= "<td>debet (f)</td>";
        $str .= "<td>kredit (f)</td>";
        $str .= "<td>debet (t)</td>";
        $str .= "<td>kredit (t)</td>";
        $str .= "<td>debet (b)</td>";
        $str .= "<td>kredit (b)</td>";


        $str .= "</tr>";

        $no = 0;
        $total_debet_forever = 0;
        $total_kredit_forever = 0;
        $total_debet_tahunan = 0;
        $total_kredit_tahunan = 0;
        $total_debet_bulanan = 0;
        $total_kredit_bulanan = 0;
        foreach ($arrRekening as $rek => $alias) {
            //region forever
            $debet_forever = isset($arrRekeningForever[$rek]->debet) ? $arrRekeningForever[$rek]->debet : 0;
            $kredit_forever = isset($arrRekeningForever[$rek]->kredit) ? $arrRekeningForever[$rek]->kredit : 0;
            $debet_forever_f = number_format($debet_forever, "2", ",", ".");
            $kredit_forever_f = number_format($kredit_forever, "2", ",", ".");
            //endregion
            //region tahunan
            $debet_tahunan = isset($arrRekeningTahunan[$rek]->debet) ? $arrRekeningTahunan[$rek]->debet : 0;
            $kredit_tahunan = isset($arrRekeningTahunan[$rek]->kredit) ? $arrRekeningTahunan[$rek]->kredit : 0;
            $debet_tahunan_f = number_format($debet_tahunan, "2", ",", ".");
            $kredit_tahunan_f = number_format($kredit_tahunan, "2", ",", ".");
            //endregion
            //region bulanan
            $debet_bulanan = isset($arrRekeningBulanan[$rek]->debet) ? $arrRekeningBulanan[$rek]->debet : 0;
            $kredit_bulanan = isset($arrRekeningBulanan[$rek]->kredit) ? $arrRekeningBulanan[$rek]->kredit : 0;
            $debet_bulanan_f = number_format($debet_bulanan, "2", ",", ".");
            $kredit_bulanan_f = number_format($kredit_bulanan, "2", ",", ".");
            //endregion

            $total_debet_forever += $debet_forever;
            $total_kredit_forever += $kredit_forever;
            $total_debet_tahunan += $debet_tahunan;
            $total_kredit_tahunan += $kredit_tahunan;
            $total_debet_bulanan += $debet_bulanan;
            $total_kredit_bulanan += $kredit_bulanan;


            $no++;
            $str .= "<tr>";
            $str .= "<td>$no</td>";
            $str .= "<td>$rek</td>";
            $str .= "<td>$alias</td>";
            $str .= "<td style='text-align: right;background-color:yellow;'>$debet_forever_f</td>";
            $str .= "<td style='text-align: right;background-color:yellow;'>$kredit_forever_f</td>";
            $str .= "<td style='text-align: right;background-color:pink;'>$debet_tahunan_f</td>";
            $str .= "<td style='text-align: right;background-color:pink;'>$kredit_tahunan_f</td>";
            $str .= "<td style='text-align: right;background-color:cyan;'>$debet_bulanan_f</td>";
            $str .= "<td style='text-align: right;background-color:cyan;'>$kredit_bulanan_f</td>";


            $str .= "</tr>";
        }

        $total_debet_forever_f = number_format($total_debet_forever, "2", ",", ".");
        $total_kredit_forever_f = number_format($total_kredit_forever, "2", ",", ".");
        $total_debet_tahunan_f = number_format($total_debet_tahunan, "2", ",", ".");
        $total_kredit_tahunan_f = number_format($total_kredit_tahunan, "2", ",", ".");
        $total_debet_bulanan_f = number_format($total_debet_bulanan, "2", ",", ".");
        $total_kredit_bulanan_f = number_format($total_kredit_bulanan, "2", ",", ".");

        $str .= "<tr>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td>-</td>";
        $str .= "<td style='text-align: right;background-color:yellow;'>$total_debet_forever_f</td>";
        $str .= "<td style='text-align: right;background-color:yellow;'>$total_kredit_forever_f</td>";
        $str .= "<td style='text-align: right;background-color:pink;'>$total_debet_tahunan_f</td>";
        $str .= "<td style='text-align: right;background-color:pink;'>$total_kredit_tahunan_f</td>";
        $str .= "<td style='text-align: right;background-color:cyan;'>$total_debet_bulanan_f</td>";
        $str .= "<td style='text-align: right;background-color:cyan;'>$total_kredit_bulanan_f</td>";

        $str .= "</table>";


        echo $str;
        //--------------------------
    }

    public function update()
    {
//        $stopCommit = true;
        $stopCommit = false;

        $this->db->trans_start();

//        $tabel_source = "__rek_pembantu_produk__1010030030";
        $tabel_source = "__rek_pembantu_supplies__1010030010";

        $jenis = "999";
        $transaksi_id = "171187";
        $transaksi_no = "999.-1.198";
        $cabang_id = "-1";//25
        $fulldate = "2022-11-14";

        $data = array(
            "jenis" => $jenis,
            "transaksi_id" => $transaksi_id,
            "transaksi_no" => $transaksi_no,
        );
        $where = array(
            "jenis" => 0,
            "transaksi_id" => 0,
            "cabang_id" => $cabang_id,
            "fulldate" => $fulldate,
        );
        $this->db->where($where);
        $this->db->update($tabel_source, $data);
        showLast_query("orange");

        if ($stopCommit == true) {
            mati_disini(" OHOOOO belon comit @" . __LINE__);
        }

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekLime("<h3>-- DONE --</h3>");

    }

    //-------------
    public function updateProduk()
    {
        $stopCommit = true;
//        $stopCommit = false;

        $this->load->helper("he_mass_table");
        $this->load->helper("he_misc");

        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlProdukJenis");
        $this->load->model("Coms/ComRekeningPembantuProduk");

        $arrProdukJenis = array();
        $arrProdukLastBeli = array();
        $arrJenisTr = array(
            "467", "460"
        );
        $rekening = "1010030030";
        $cabang_id = "-1";

        $p = New MdlProduk();
        $p->addFilter("produk_jenis_id=0");
        $pTmp = $p->lookupAll()->result();
        $pData = array();
        foreach ($pTmp as $spec) {
            $pData[$spec->id] = $spec->id;
        }

        // region produk jenis
        $pj = New MdlProdukJenis();
        $pjTmp = $pj->lookupAll()->result();
        foreach ($pjTmp as $spec) {
            $jenis = ($spec->kode == "import") ? "460" : "467";
            $arrProdukJenis[$jenis] = array(
                "produk_jenis_id" => $spec->id,
                "produk_jenis_nama" => $spec->kode,
                "produk_jenis_nilai" => $spec->nilai,
            );
        }
        // endregion produk jenis

        //region pembantu produk
        $rp = New ComRekeningPembantuProduk();
        $rp->addFilter("debet>0");
        $rp->addFilter("cabang_id='$cabang_id'");
        $rp->addFilter("jenis in ('" . implode("','", $arrJenisTr) . "')");
        $rpTmp = $rp->fetchMoves2($rekening);
        showLast_query("biru");
        foreach ($rpTmp as $rpSpec) {
//            arrPrint($rpSpec);
            $pid = $rpSpec->extern_id;
            $jenisTr = $rpSpec->jenis;
            $arrProdukLastBeli[$pid] = $jenisTr;

//            break;
        }
        //endregion

//        cekHere(sizeof($arrProdukLastBeli));
//        arrPrintWebs($arrProdukJenis);
//        arrPrintPink($arrProdukLastBeli);
//

        $this->db->trans_start();
//arrPrint($arrProdukJenis);
        foreach ($arrProdukLastBeli as $pid => $jenisTr) {
            $arrDef = array(
                "produk_jenis_id" => "39",
                "produk_jenis_nama" => "lokal",
                "produk_jenis_nilai" => "1.10",
            );
            $data = isset($arrProdukJenis[$jenisTr]) ? $arrProdukJenis[$jenisTr] : $arrDef;
            $where = array(
                "id" => $pid
            );

            if (sizeof($data) > 0) {
                $mp = New MdlProduk();
                $mp->setFilters(array());
                $mp->updateData($where, $data);
                showLast_query("orange");
            }

        }

        foreach ($pData as $pid) {
            $arrDef = array(
                "produk_jenis_id" => "39",
                "produk_jenis_nama" => "lokal",
                "produk_jenis_nilai" => "1.10",
            );
            $where = array(
                "id" => $pid
            );
            $mp = New MdlProduk();
            $mp->setFilters(array());
            $mp->updateData($where, $arrDef);
            showLast_query("pink");
        }

        if ($stopCommit == true) {
            mati_disini(" OHOOOO belon comit @" . __LINE__);
        }

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekLime("<h3>-- DONE --</h3>");

    }

    public function updateProdukRakitan()
    {
        $this->load->model("Mdls/MdlProdukRakitan");
        $this->load->model("Mdls/MdlFifoProdukJadi");

        $arrPIDs = array();
        $jenis = "item_rakitan";
        $ppv = "10";
        $produk_jenis_id = "42";
        $produk_jenis_nama = "produksi";

        $this->db->trans_start();

        $mpr = New MdlProdukRakitan();
        $mpr->setFilters(array());
        $mpr->addFilter("jenis=$jenis");
        $mprTmp = $mpr->lookupAll()->result();
        showLast_query("biru");
        foreach ($mprTmp as $mprSpec) {
            $id_tbl = $mprSpec->id;
            $arrPIDs[$id_tbl] = $id_tbl;
            $produk_jenis_nilai = (100 + $ppv) / 100;
            $pData = array(
                "produk_jenis_id" => $produk_jenis_id,
                "produk_jenis_nama" => $produk_jenis_nama,
                "produk_jenis_nilai" => $produk_jenis_nilai,
            );
            $pWhere = array(
                "id" => $id_tbl,
            );
            $mpru = New MdlProdukRakitan();
            $mpru->setFilters(array());
            $mpru->updateData($pWhere, $pData);
            showLast_query("kuning");
        }


        $fpj = New MdlFifoProdukJadi();
        $fpj->addFilter("unit>0");
        $fpj->addFilter("produk_id in ('" . implode("','", $arrPIDs) . "')");
        $fpjTmp = $fpj->lookupAll()->result();
        showlast_query("biru");
        if (sizeof($fpjTmp) > 0) {
            foreach ($fpjTmp as $fpjSpec) {
                $id = $fpjSpec->id;
                $qty = $fpjSpec->unit;
                $hpp = $fpjSpec->hpp;
                $ppv_riil = ($ppv / 100) * $hpp;
                $ppv_riil_nilai = $qty * $ppv_riil;
                $ppv_riil_persen = $ppv;
                $ppv_riil_factor = (100 + $ppv) / 100;

                $data = array(
                    "ppv_factor" => $ppv_riil_factor,
                    "ppv_factor_persen" => $ppv_riil_persen,
                    "ppv_riil" => $ppv_riil,
                    "ppv_nilai_riil" => $ppv_riil_nilai,
                );
                $where = array(
                    "id" => $id,
                );
                $fpju = New MdlFifoProdukJadi();
                $fpju->setFilters(array());
                $fpju->updateData($where, $data);
                showLast_query("orange");
            }
        }


        mati_disini(__LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekLime("<h3>-- DONE --</h3>");

    }

    //payemnt source uang muka customer---------------------
    public function cekPaymentSource()
    {
        $this->load->helper("he_mass_table_helper");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuCustomer");
        $this->load->model("Coms/ComPaymentUangMukaCustomer");
        $this->load->model("Coms/ComPaymentAntisourceCustomer");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("MdlTransaksi");
        $masterConfigUI = $this->config->item("heTransaksi_ui");

        $arrUangMukaSrc = array();
        $arrHutangKeKonsumen = array();
        $arrSaldo = array();
        $arrTotalBawah = array();
        $arrTransaksiIDs = array();

        $rekening = "2010050";
        $cabang_ids = array(
//            "-1",
//            "1",
            "21",
//            "25",
//            "26",
//            "27",
//            "28",
//            "29",
//            "30",
//            "31",
        );
        $ctrl = $this->uri->segment(1);
        $method = $this->uri->segment(2);
        $cabang_id = $branchID = null != $this->uri->segment(3) ? $this->uri->segment(3) : 21;

        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }

        //------------------------------------

        //region hutang ke konsumen
        $crpc = New ComRekeningPembantuCustomer();
        $crpc->addFilter("cabang_id=$cabang_id");
        $crpcTmp = $crpc->fetchBalances($rekening);
//        showLast_query("biru");
        if (sizeof($crpcTmp) > 0) {
            foreach ($crpcTmp as $crpcSpec) {
                $debet = $crpcSpec->debet;
                $kredit = $crpcSpec->kredit;
                $extern_id = $crpcSpec->extern_id;
                $extern_nama = $crpcSpec->extern_nama;
                $arrHutangKeKonsumen[$extern_id] = array(
                    "extern_id" => $extern_id,
                    "extern_nama" => $extern_nama,
                    "debet" => $debet,
                    "kredit" => $kredit,
                );
            }
        }
        //endregion

        // region pym uang muka
        $jenis = "customer";
        $trm = New MdlTransaksi();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trmTmp = $trm->lookupUangMukaSrc($jenis)->result();
//        arrPrint($trmTmp);
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrUangMukaSrc[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                );
                if (!isset($arrUangMukaSrc[$trmSpec->extern_id]['sisa'])) {
                    $arrUangMukaSrc[$trmSpec->extern_id]['sisa'] = 0;
                }
                $arrUangMukaSrc[$trmSpec->extern_id]['sisa'] += $trmSpec->sisa;
            }
        }
        // endregion pym uang muka

        // region return penjualan
        $jenis = "piutang dagang";
        $trm = New MdlTransaksi();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trmTmp = $trm->lookupPaymentAntiSrcByLabel($jenis)->result();
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrAntiSrc[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                );
                if (!isset($arrAntiSrc[$trmSpec->extern_id]['sisa'])) {
                    $arrAntiSrc[$trmSpec->extern_id]['sisa'] = 0;
                }
                $arrAntiSrc[$trmSpec->extern_id]['sisa'] += $trmSpec->sisa;
            }
        }
        // endregion return penjualan

        // region cache uang muka
        $trm = New ComPaymentUangMukaCustomer();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trm->addFilter("label='uang muka'");
        $trm->addFilter("extern_label2='customer'");
        $trmTmp = $trm->lookupAll()->result();
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrUangMukaCache[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                    "debet" => $trmSpec->debet,
                    "kredit" => $trmSpec->kredit,
                );
            }
        }
        // endregion cache uang muka

        // region cache antisource return
        $trm = New ComPaymentAntisourceCustomer();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trm->addFilter("label='piutang dagang'");
        $trm->addFilter("extern_label2='customer'");
        $trmTmp = $trm->lookupAll()->result();
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrAntiSourceReturn[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                    "debet" => $trmSpec->debet,
                    "kredit" => $trmSpec->kredit,
                );
            }
        }
        // endregion cache antisource return

        $stopCommit = true;
//        $stopCommit = false;

        $this->db->trans_start();

        //------------------------------------
        if (sizeof($arrHutangKeKonsumen) > 0) {
            foreach ($arrHutangKeKonsumen as $cusId => $cusSpec) {
                $hutang_ke_konsumen_netto = $cusSpec['kredit'] - $cusSpec['debet'];
                $pym_uang_muka = isset($arrUangMukaSrc[$cusId]['sisa']) ? $arrUangMukaSrc[$cusId]['sisa'] : 0;
                $pym_return = isset($arrAntiSrc[$cusId]['sisa']) ? $arrAntiSrc[$cusId]['sisa'] : 0;
                $pym_uangmuka_return = $pym_uang_muka + $pym_return;
                //--------
                $items_new[$cusId] = array(
                    "extern_id" => $cusId,
                    "extern_nama" => $cusSpec['extern_nama'],
                    "debet" => $cusSpec['debet'],
                    "kredit" => $cusSpec['kredit'],
                    "pym_uang_muka" => $pym_uang_muka,
                    "pym_return_penjualan" => $pym_return,
                    //---
                    "uangmuka_cache_debet" => isset($arrUangMukaCache[$cusId]['debet']) ? $arrUangMukaCache[$cusId]['debet'] : 0,
                    "uangmuka_cache_kredit" => isset($arrUangMukaCache[$cusId]['kredit']) ? $arrUangMukaCache[$cusId]['kredit'] : 0,
                    "antisrc_cache_debet" => isset($arrAntiSourceReturn[$cusId]['debet']) ? $arrAntiSourceReturn[$cusId]['debet'] : 0,
                    "antisrc_cache_kredit" => isset($arrAntiSourceReturn[$cusId]['kredit']) ? $arrAntiSourceReturn[$cusId]['kredit'] : 0,
                );
                //--------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if (isset($_GET['exe']) && ($_GET['exe'] == 1)) {

                        if ($hutang_ke_konsumen_netto == $pym_uangmuka_return) {
                            // region patch uang muka
                            $arrPatchUangMuka = array(
                                "loop" => array(
                                    "2010050" => $pym_uang_muka,// hutang ke konsumen
                                ),
                                "static" => array(
                                    "cabang_id" => $cabang_id,
                                    "cabang_nama" => $arrCabangs[$cabang_id],
                                    "gudang_id" => ".0",
                                    "extern_id" => $cusId,
                                    "extern_nama" => $cusSpec['extern_nama'],
                                    "nilai" => $pym_uang_muka,
                                    "label" => "uang muka",
                                    "extern_label2" => "customer",
                                    "keterangan" => "patch uang muka konsumen",
                                ),
                            );
                            $pumc = New ComPaymentUangMukaCustomer();
                            $pumc->pair($arrPatchUangMuka);
                            $pumc->exec();
                            // endregion patch uang muka

                            // region patch return penjualan
                            $arrPatchReturn = array(
                                "loop" => array(
                                    "2010050" => $pym_return,// hutang ke konsumen
                                ),
                                "static" => array(
                                    "cabang_id" => $cabang_id,
                                    "cabang_nama" => $arrCabangs[$cabang_id],
                                    "gudang_id" => ".0",
                                    "extern_id" => $cusId,
                                    "extern_nama" => $cusSpec['extern_nama'],
                                    "nilai" => $pym_return,
                                    "label" => "piutang dagang",
                                    "extern_label2" => "customer",
                                    "keterangan" => "patch antisource return penjualan konsumen",
                                ),
                            );
                            $pasc = New ComPaymentAntisourceCustomer();
                            $pasc->pair($arrPatchReturn);
                            $pasc->exec();
                            // endregion patch return penjualan
                        }


                    }
                }
            }
        }
        //------------------------------------

        if ($stopCommit == true) {
//            cekhitam(":: tidak commit ::");
        }
        else {
            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//            cekLime("<h3>-- DONE --</h3>");
        }


        $arrButton = array();
        foreach ($arrCabangs as $branchIDc => $branchNama) {
            $link = base_url() . "$ctrl/$method/$branchIDc";
            $selected = $branchIDc == $branchID ? "selected" : "";
            $btn = $branchIDc == $branchID ? "btn-success" : "btn-secondary";
            $arrButton[$branchIDc] = "<button class='btn $btn' $selected onclick=\"location.href='$link'\">($branchIDc) $branchNama</button>";
        }

        $headerFields = array(
            "extern_id" => "cid",
            "extern_nama" => "konsumen",
            "debet" => "debet",
            "kredit" => "kredit",
            "pym_uang_muka" => "pym uang muka",
            "pym_return_penjualan" => "pym return<br>penjualan",
            //----
            "uangmuka_cache_debet" => "UM cache<br>debet",
            "uangmuka_cache_kredit" => "UM cache<br>kredit",
            "antisrc_cache_debet" => "anti src<br>debet",
            "antisrc_cache_kredit" => "anti src<br>kredit",
        );
        $data = array(
            "mode" => "cekMasterDetail",
            "title" => "PAYMENT SOURCE UANG MUKA, ANTI SOURCE RETURN PENJUALAN, HUTANG KE KONSUMEN",
            "subTitle" => "",
            "headerFields" => isset($headerFields) ? $headerFields : array(),
            "headerFieldsSaldo" => isset($headerFieldsSaldo) ? $headerFieldsSaldo : array(),
            "items" => isset($items_new) ? $items_new : array(),
            "itemsGeseh" => isset($items_geseh) ? $items_geseh : array(),
            "warning" => isset($warning) ? $warning : array(),
            "marking" => isset($marking) ? $marking : array(),
            "markingColumn" => isset($markingColumn) ? $markingColumn : array(),
            "button" => isset($arrButton) ? $arrButton : array(),
            "arrTotalBawah" => isset($arrTotalBawah) ? $arrTotalBawah : array(),
            "arrTotalBawahGeseh" => isset($arrTotalBawahGeseh) ? $arrTotalBawahGeseh : array(),
            "arrSaldo" => isset($arrSaldoNew) ? $arrSaldoNew : array(),
        );
        $this->load->view("tool", $data);
    }

    public function cekPaymentSourceSupplier()
    {
        $this->load->helper("he_mass_table_helper");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuSupplier");
        $this->load->model("Coms/ComPaymentUangMukaSupplier");
        $this->load->model("Coms/ComPaymentAntisourceSupplier");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("MdlTransaksi");
        $masterConfigUI = $this->config->item("heTransaksi_ui");

        $arrUangMukaSrc = array();
        $arrHutangKeKonsumen = array();
        $arrSaldo = array();
        $arrTotalBawah = array();
        $arrTransaksiIDs = array();

        $rekening = "1010020030";
        $cabang_ids = array(
//            "-1",
//            "1",
            "21",
//            "25",
//            "26",
//            "27",
//            "28",
//            "29",
//            "30",
//            "31",
        );
        $ctrl = $this->uri->segment(1);
        $method = $this->uri->segment(2);
        $cabang_id = $branchID = null != $this->uri->segment(3) ? $this->uri->segment(3) : 21;

        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }

        //------------------------------------

        //region piutang pembelian
        $crpc = New ComRekeningPembantuSupplier();
        $crpc->addFilter("cabang_id=$cabang_id");
        $crpcTmp = $crpc->fetchBalances($rekening);
//        showLast_query("biru");
        if (sizeof($crpcTmp) > 0) {
            foreach ($crpcTmp as $crpcSpec) {
                $debet = $crpcSpec->debet;
                $kredit = $crpcSpec->kredit;
                $extern_id = $crpcSpec->extern_id;
                $extern_nama = $crpcSpec->extern_nama;
                $arrHutangKeKonsumen[$extern_id] = array(
                    "extern_id" => $extern_id,
                    "extern_nama" => $extern_nama,
                    "debet" => $debet,
                    "kredit" => $kredit,
                );
            }
        }
        //endregion

        // region pym uang muka
        $jenis = "vendor";
        $trm = New MdlTransaksi();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trmTmp = $trm->lookupUangMukaSrc($jenis)->result();
//        arrPrint($trmTmp);
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrUangMukaSrc[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                );
                if (!isset($arrUangMukaSrc[$trmSpec->extern_id]['sisa'])) {
                    $arrUangMukaSrc[$trmSpec->extern_id]['sisa'] = 0;
                }
                $arrUangMukaSrc[$trmSpec->extern_id]['sisa'] += $trmSpec->sisa;
            }
        }
        // endregion pym uang muka

        // region return pembelian
//        $jenis = "hutang dagang";
        $jenis = "piutang pembelian";
        $trm = New MdlTransaksi();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trmTmp = $trm->lookupPaymentAntiSrcByLabel($jenis)->result();
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrAntiSrc[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                );
                if (!isset($arrAntiSrc[$trmSpec->extern_id]['sisa'])) {
                    $arrAntiSrc[$trmSpec->extern_id]['sisa'] = 0;
                }
                $arrAntiSrc[$trmSpec->extern_id]['sisa'] += $trmSpec->sisa;
            }
        }
        // endregion return pembelian

        // region cache uang muka
        $trm = New ComPaymentUangMukaSupplier();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trm->addFilter("label='uang muka'");
        $trm->addFilter("extern_label2='vendor'");
        $trmTmp = $trm->lookupAll()->result();
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrUangMukaCache[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                    "debet" => $trmSpec->debet,
                    "kredit" => $trmSpec->kredit,
                );
            }
        }
        // endregion cache uang muka

        // region cache antisource return
        $trm = New ComPaymentAntisourceSupplier();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trm->addFilter("label='piutang pembelian'");
        $trm->addFilter("extern_label2='vendor'");
        $trmTmp = $trm->lookupAll()->result();
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrAntiSourceReturn[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                    "debet" => $trmSpec->debet,
                    "kredit" => $trmSpec->kredit,
                );
            }
        }
        // endregion cache antisource return

        $stopCommit = true;
//        $stopCommit = false;

        $this->db->trans_start();

        //------------------------------------
        if (sizeof($arrHutangKeKonsumen) > 0) {
            foreach ($arrHutangKeKonsumen as $cusId => $cusSpec) {
                $hutang_ke_konsumen_netto = $cusSpec['kredit'] - $cusSpec['debet'];
                $pym_uang_muka = isset($arrUangMukaSrc[$cusId]['sisa']) ? $arrUangMukaSrc[$cusId]['sisa'] : 0;
                $pym_return = isset($arrAntiSrc[$cusId]['sisa']) ? $arrAntiSrc[$cusId]['sisa'] : 0;
                $pym_uangmuka_return = $pym_uang_muka + $pym_return;
                //--------
                $items_new[$cusId] = array(
                    "extern_id" => $cusId,
                    "extern_nama" => $cusSpec['extern_nama'],
                    "debet" => $cusSpec['debet'],
                    "kredit" => $cusSpec['kredit'],
                    "pym_uang_muka" => $pym_uang_muka,
                    "pym_return_penjualan" => $pym_return,
                    //---
                    "uangmuka_cache_debet" => isset($arrUangMukaCache[$cusId]['debet']) ? $arrUangMukaCache[$cusId]['debet'] : 0,
                    "uangmuka_cache_kredit" => isset($arrUangMukaCache[$cusId]['kredit']) ? $arrUangMukaCache[$cusId]['kredit'] : 0,
                    "antisrc_cache_debet" => isset($arrAntiSourceReturn[$cusId]['debet']) ? $arrAntiSourceReturn[$cusId]['debet'] : 0,
                    "antisrc_cache_kredit" => isset($arrAntiSourceReturn[$cusId]['kredit']) ? $arrAntiSourceReturn[$cusId]['kredit'] : 0,
                );
                //--------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if (isset($_GET['exe']) && ($_GET['exe'] == 1)) {

                        if ($hutang_ke_konsumen_netto == $pym_uangmuka_return) {
                            // region patch uang muka
//                            $arrPatchUangMuka = array(
//                                "loop" => array(
//                                    "2010050" => $pym_uang_muka,// hutang ke konsumen
//                                ),
//                                "static" => array(
//                                    "cabang_id" => $cabang_id,
//                                    "cabang_nama" => $arrCabangs[$cabang_id],
//                                    "gudang_id" => ".0",
//                                    "extern_id" => $cusId,
//                                    "extern_nama" => $cusSpec['extern_nama'],
//                                    "nilai" => $pym_uang_muka,
//                                    "label" => "uang muka",
//                                    "extern_label2" => "customer",
//                                    "keterangan" => "patch uang muka konsumen",
//                                ),
//                            );
//                            $pumc = New ComPaymentUangMukaCustomer();
//                            $pumc->pair($arrPatchUangMuka);
//                            $pumc->exec();
                            // endregion patch uang muka

                            // region patch return penjualan
                            $arrPatchReturn = array(
                                "loop" => array(
                                    "1010020030" => $pym_return,// hutang ke konsumen
                                ),
                                "static" => array(
                                    "cabang_id" => $cabang_id,
                                    "cabang_nama" => $arrCabangs[$cabang_id],
                                    "gudang_id" => ".0",
                                    "extern_id" => $cusId,
                                    "extern_nama" => $cusSpec['extern_nama'],
                                    "nilai" => $pym_return,
                                    "label" => "piutang pembelian",
                                    "extern_label2" => "vendor",
                                    "keterangan" => "patch antisource return pembelian ke vendor",
                                ),
                            );
                            $pasc = New ComPaymentAntisourceSupplier();
                            $pasc->pair($arrPatchReturn);
                            $pasc->exec();
                            // endregion patch return penjualan
                        }


                    }
                }
            }
        }
        //------------------------------------

        if ($stopCommit == true) {
//            cekhitam(":: tidak commit ::");
        }
        else {
            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//            cekLime("<h3>-- DONE --</h3>");
        }


        $arrButton = array();
        foreach ($arrCabangs as $branchIDc => $branchNama) {
            $link = base_url() . "$ctrl/$method/$branchIDc";
            $selected = $branchIDc == $branchID ? "selected" : "";
            $btn = $branchIDc == $branchID ? "btn-success" : "btn-secondary";
            $arrButton[$branchIDc] = "<button class='btn $btn' $selected onclick=\"location.href='$link'\">($branchIDc) $branchNama</button>";
        }

        $headerFields = array(
            "extern_id" => "cid",
            "extern_nama" => "supplier",
            "debet" => "debet",
            "kredit" => "kredit",
//            "pym_uang_muka" => "pym uang muka",
            "pym_return_penjualan" => "pym return<br>pembelian",
            //----
//            "uangmuka_cache_debet" => "UM cache<br>debet",
//            "uangmuka_cache_kredit" => "UM cache<br>kredit",
            "antisrc_cache_debet" => "anti src<br>debet",
            "antisrc_cache_kredit" => "anti src<br>kredit",
        );
        $data = array(
            "mode" => "cekMasterDetail",
            "title" => "PAYMENT SOURCE UANG MUKA, ANTI SOURCE RETURN PEMBELIAN",
            "subTitle" => "",
            "headerFields" => isset($headerFields) ? $headerFields : array(),
            "headerFieldsSaldo" => isset($headerFieldsSaldo) ? $headerFieldsSaldo : array(),
            "items" => isset($items_new) ? $items_new : array(),
            "itemsGeseh" => isset($items_geseh) ? $items_geseh : array(),
            "warning" => isset($warning) ? $warning : array(),
            "marking" => isset($marking) ? $marking : array(),
            "markingColumn" => isset($markingColumn) ? $markingColumn : array(),
            "button" => isset($arrButton) ? $arrButton : array(),
            "arrTotalBawah" => isset($arrTotalBawah) ? $arrTotalBawah : array(),
            "arrTotalBawahGeseh" => isset($arrTotalBawahGeseh) ? $arrTotalBawahGeseh : array(),
            "arrSaldo" => isset($arrSaldoNew) ? $arrSaldoNew : array(),
        );
        $this->load->view("tool", $data);
    }

    public function cekPaymentSourceUMSupplier()
    {
        $this->load->helper("he_mass_table_helper");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPembantuUangMuka");
        $this->load->model("Coms/ComPaymentUangMukaSupplier");
        $this->load->model("Coms/ComPaymentAntisourceSupplier");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("MdlTransaksi");
        $masterConfigUI = $this->config->item("heTransaksi_ui");

        $arrUangMukaSrc = array();
        $arrHutangKeKonsumen = array();
        $arrSaldo = array();
        $arrTotalBawah = array();
        $arrTransaksiIDs = array();

        $rekening = "1010050010";
        $cabang_ids = array(
//            "-1",
//            "1",
            "21",
//            "25",
//            "26",
//            "27",
//            "28",
//            "29",
//            "30",
//            "31",
        );
        $ctrl = $this->uri->segment(1);
        $method = $this->uri->segment(2);
        $cabang_id = $branchID = null != $this->uri->segment(3) ? $this->uri->segment(3) : 21;

        $cb = new MdlCabang();
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->nama;
            }
        }

        //------------------------------------

        //region piutang pembelian
        $crpc = New ComRekeningPembantuUangMuka();
        $crpc->addFilter("cabang_id=$cabang_id");
        $crpcTmp = $crpc->fetchBalances($rekening);
//        showLast_query("biru");
        if (sizeof($crpcTmp) > 0) {
            foreach ($crpcTmp as $crpcSpec) {
                $debet = $crpcSpec->debet;
                $kredit = $crpcSpec->kredit;
                $extern_id = $crpcSpec->extern_id;
                $extern_nama = $crpcSpec->extern_nama;
                $arrHutangKeKonsumen[$extern_id] = array(
                    "extern_id" => $extern_id,
                    "extern_nama" => $extern_nama,
                    "debet" => $debet,
                    "kredit" => $kredit,
                );
            }
        }
        //endregion

        // region pym uang muka
        $jenis = "vendor";
        $trm = New MdlTransaksi();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trmTmp = $trm->lookupUangMukaSrc($jenis)->result();
//        arrPrint($trmTmp);
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrUangMukaSrc[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                );
                if (!isset($arrUangMukaSrc[$trmSpec->extern_id]['sisa'])) {
                    $arrUangMukaSrc[$trmSpec->extern_id]['sisa'] = 0;
                }
                $arrUangMukaSrc[$trmSpec->extern_id]['sisa'] += $trmSpec->sisa;
            }
        }
        // endregion pym uang muka

        // region return pembelian
        $jenis = "hutang dagang";
        $trm = New MdlTransaksi();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trmTmp = $trm->lookupPaymentAntiSrcByLabel($jenis)->result();
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrAntiSrc[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                );
                if (!isset($arrAntiSrc[$trmSpec->extern_id]['sisa'])) {
                    $arrAntiSrc[$trmSpec->extern_id]['sisa'] = 0;
                }
                $arrAntiSrc[$trmSpec->extern_id]['sisa'] += $trmSpec->sisa;
            }
        }
        // endregion return pembelian

        // region cache uang muka
        $trm = New ComPaymentUangMukaSupplier();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trm->addFilter("label='uang muka'");
        $trm->addFilter("extern_label2='vendor'");
        $trmTmp = $trm->lookupAll()->result();
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrUangMukaCache[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                    "debet" => $trmSpec->debet,
                    "kredit" => $trmSpec->kredit,
                );
            }
        }
        // endregion cache uang muka

        // region cache antisource return
        $trm = New ComPaymentAntisourceSupplier();
        $trm->setFilters(array());
        $trm->addFilter("cabang_id=$cabang_id");
        $trm->addFilter("label='hutang dagang'");
        $trm->addFilter("extern_label2='vendor'");
        $trmTmp = $trm->lookupAll()->result();
        if (sizeof($trmTmp) > 0) {
            foreach ($trmTmp as $trmSpec) {
                $arrAntiSourceReturn[$trmSpec->extern_id] = array(
                    "extern_id" => $trmSpec->extern_id,
                    "extern_nama" => $trmSpec->extern_nama,
                    "cabang_id" => $trmSpec->cabang_id,
                    "cabang_nama" => $trmSpec->cabang_nama,
                    "debet" => $trmSpec->debet,
                    "kredit" => $trmSpec->kredit,
                );
            }
        }
        // endregion cache antisource return

        $stopCommit = true;
//        $stopCommit = false;

        $this->db->trans_start();

        //------------------------------------
        if (sizeof($arrHutangKeKonsumen) > 0) {
            foreach ($arrHutangKeKonsumen as $cusId => $cusSpec) {
                $hutang_ke_konsumen_netto = $cusSpec['kredit'] - $cusSpec['debet'];
                $pym_uang_muka = isset($arrUangMukaSrc[$cusId]['sisa']) ? $arrUangMukaSrc[$cusId]['sisa'] : 0;
                $pym_return = isset($arrAntiSrc[$cusId]['sisa']) ? $arrAntiSrc[$cusId]['sisa'] : 0;
                $pym_uangmuka_return = $pym_uang_muka + $pym_return;
                //--------
                $items_new[$cusId] = array(
                    "extern_id" => $cusId,
                    "extern_nama" => $cusSpec['extern_nama'],
                    "debet" => $cusSpec['debet'],
                    "kredit" => $cusSpec['kredit'],
                    "pym_uang_muka" => $pym_uang_muka,
                    "pym_return_penjualan" => $pym_return,
                    //---
                    "uangmuka_cache_debet" => isset($arrUangMukaCache[$cusId]['debet']) ? $arrUangMukaCache[$cusId]['debet'] : 0,
                    "uangmuka_cache_kredit" => isset($arrUangMukaCache[$cusId]['kredit']) ? $arrUangMukaCache[$cusId]['kredit'] : 0,
                    "antisrc_cache_debet" => isset($arrAntiSourceReturn[$cusId]['debet']) ? $arrAntiSourceReturn[$cusId]['debet'] : 0,
                    "antisrc_cache_kredit" => isset($arrAntiSourceReturn[$cusId]['kredit']) ? $arrAntiSourceReturn[$cusId]['kredit'] : 0,
                );
                //--------
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    if (isset($_GET['exe']) && ($_GET['exe'] == 1)) {

                        if ($hutang_ke_konsumen_netto == $pym_uangmuka_return) {
                            // region patch uang muka
                            $arrPatchUangMuka = array(
                                "loop" => array(
                                    "1010050010" => $pym_uang_muka,// hutang ke konsumen
                                ),
                                "static" => array(
                                    "cabang_id" => $cabang_id,
                                    "cabang_nama" => $arrCabangs[$cabang_id],
                                    "gudang_id" => ".0",
                                    "extern_id" => $cusId,
                                    "extern_nama" => $cusSpec['extern_nama'],
                                    "nilai" => $pym_uang_muka,
                                    "label" => "uang muka",
                                    "extern_label2" => "vendor",
                                    "keterangan" => "patch uang muka vendor",
                                ),
                            );
                            $pumc = New ComPaymentUangMukaSupplier();
                            $pumc->pair($arrPatchUangMuka);
                            $pumc->exec();
                            // endregion patch uang muka

                            // region patch return penjualan
//                            $arrPatchReturn = array(
//                                "loop" => array(
//                                    "2010050" => $pym_return,// hutang ke konsumen
//                                ),
//                                "static" => array(
//                                    "cabang_id" => $cabang_id,
//                                    "cabang_nama" => $arrCabangs[$cabang_id],
//                                    "gudang_id" => ".0",
//                                    "extern_id" => $cusId,
//                                    "extern_nama" => $cusSpec['extern_nama'],
//                                    "nilai" => $pym_return,
//                                    "label" => "piutang dagang",
//                                    "extern_label2" => "customer",
//                                    "keterangan" => "patch antisource return penjualan konsumen",
//                                ),
//                            );
//                            $pasc = New ComPaymentAntisourceCustomer();
//                            $pasc->pair($arrPatchReturn);
//                            $pasc->exec();
                            // endregion patch return penjualan
                        }


                    }
                }
            }
        }
        //------------------------------------

        if ($stopCommit == true) {
//            cekhitam(":: tidak commit ::");
        }
        else {
            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
//            cekLime("<h3>-- DONE --</h3>");
        }


        $arrButton = array();
        foreach ($arrCabangs as $branchIDc => $branchNama) {
            $link = base_url() . "$ctrl/$method/$branchIDc";
            $selected = $branchIDc == $branchID ? "selected" : "";
            $btn = $branchIDc == $branchID ? "btn-success" : "btn-secondary";
            $arrButton[$branchIDc] = "<button class='btn $btn' $selected onclick=\"location.href='$link'\">($branchIDc) $branchNama</button>";
        }

        $headerFields = array(
            "extern_id" => "cid",
            "extern_nama" => "supplier",
            "debet" => "debet",
            "kredit" => "kredit",
            "pym_uang_muka" => "pym uang muka",
//            "pym_return_penjualan" => "pym return<br>pembelian",
            //----
            "uangmuka_cache_debet" => "UM cache<br>debet",
            "uangmuka_cache_kredit" => "UM cache<br>kredit",
//            "antisrc_cache_debet" => "anti src<br>debet",
//            "antisrc_cache_kredit" => "anti src<br>kredit",
        );
        $data = array(
            "mode" => "cekMasterDetail",
            "title" => "PAYMENT SOURCE UANG MUKA, UANG MUKA DIBAYAR",
            "subTitle" => "",
            "headerFields" => isset($headerFields) ? $headerFields : array(),
            "headerFieldsSaldo" => isset($headerFieldsSaldo) ? $headerFieldsSaldo : array(),
            "items" => isset($items_new) ? $items_new : array(),
            "itemsGeseh" => isset($items_geseh) ? $items_geseh : array(),
            "warning" => isset($warning) ? $warning : array(),
            "marking" => isset($marking) ? $marking : array(),
            "markingColumn" => isset($markingColumn) ? $markingColumn : array(),
            "button" => isset($arrButton) ? $arrButton : array(),
            "arrTotalBawah" => isset($arrTotalBawah) ? $arrTotalBawah : array(),
            "arrTotalBawahGeseh" => isset($arrTotalBawahGeseh) ? $arrTotalBawahGeseh : array(),
            "arrSaldo" => isset($arrSaldoNew) ? $arrSaldoNew : array(),
        );
        $this->load->view("tool", $data);
    }


    public function updateReferencePembatalan()
    {


    }


    public function patchPembantu()
    {
        $this->load->model("Mdls/MdlRugilaba");
        $this->load->model("Mdls/MdlCabang");
        $this->load->helper("he_mass_table");

        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();

        $arrBlacklist = array("9010", "9020");
        $arrTransaksi = array(
            "-1" => array(
                "trid" => "186301",
                "nomer" => "1000.-1.2",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
            "1" => array(
                "trid" => "186303",
                "nomer" => "1001.1.2",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
            "21" => array(
                "trid" => "186305",
                "nomer" => "1001.21.2",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
            "25" => array(
                "trid" => "186307",
                "nomer" => "1001.25.2",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
            "26" => array(
                "trid" => "186309",
                "nomer" => "1001.26.1",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
            "27" => array(
                "trid" => "186311",
                "nomer" => "1001.27.1",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
            "28" => array(
                "trid" => "186313",
                "nomer" => "1001.28.1",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
            "29" => array(
                "trid" => "186315",
                "nomer" => "1001.29.1",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
            "30" => array(
                "trid" => "186317",
                "nomer" => "1001.30.1",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
            "31" => array(
                "trid" => "186319",
                "nomer" => "1001.31.1",
                "dtime" => "2023-01-01 00:03:33",
                "fulldate" => "2023-01-01",
            ),
        );

        $cb = new MdlCabang();
//        $cb->addFilter("id=29");
        $cb->addFilter("trash=0");
        $arrCabangData = $cb->lookupAll()->result();
        $arrCabangs['-1'] = "Center";
        if (sizeof($arrCabangData) > 0) {
            foreach ($arrCabangData as $cabSpec) {
                $arrCabangs[$cabSpec->id] = $cabSpec->id;
            }
        }

        $rl = new MdlRugilaba();
        $rl->addFilter("cabang_id in ('" . implode("','", $arrCabangs) . "')");
        $rl->addFilter("trash='0'");
        $rl->addFilter("periode='tahunan'");
        $rl->addFilter("thn='2022'");
        $rlTmp = $rl->lookupAll()->result();
        $rekeningRL = array();
        $childs = array();
        foreach ($rlTmp as $rlSpec) {
            if (!in_array($rlSpec->rekening, $arrBlacklist)) {
                $rekeningRL[$rlSpec->cabang_id][$rlSpec->rekening] = $rlSpec->rekening;
                $childs[$rlSpec->rekening] = isset($accountChilds[$rlSpec->rekening]) ? $accountChilds[$rlSpec->rekening] : "none";
            }
        }
//        arrPrintWebs($childs);
//        arrPrintWebs($rekeningRL['1']);
//mati_disini(__LINE__);

        $this->db->trans_start();


        foreach ($rekeningRL as $cabang_id => $spec) {
            foreach ($spec as $rekening => $val) {
                if (isset($accountChilds[$rekening])) {
                    $comName = $accountChilds[$rekening];
                    $comName = "Com" . $comName;
//                    cekHere(":: $cabang_id :: $rekening :: $comName ::");
                    $this->load->model("Coms/$comName");
                    $mm = New $comName();
                    $mm->addFilter("cabang_id='$cabang_id'");
                    $mmTmp = $mm->fetchBalances($rekening);
//                    showLast_query("biru");
//                    arrPrintWebs($mmTmp);
                    $data = array();
                    foreach ($mmTmp as $ii => $mmSpec) {
                        $debet = $mmSpec->debet;
                        $kredit = $mmSpec->kredit;

                        $transaksi_id = $arrTransaksi[$cabang_id]["trid"];
                        $transaksi_nomer = $arrTransaksi[$cabang_id]["nomer"];
                        $dtime = $arrTransaksi[$cabang_id]["dtime"];
                        $fulldate = $arrTransaksi[$cabang_id]["fulldate"];

                        $defPosition = detectRekDefaultPosition($rekening);
                        switch ($defPosition) {
                            case "debet":
                                if ($debet > 0) {
                                    $value = $debet * -1;
                                }
                                else {
                                    $value = $kredit;
                                }
                                break;
                            case "kredit":
                                if ($debet > 0) {
                                    $value = $debet;
                                }
                                else {
                                    $value = $kredit * -1;
                                }
                                break;
                        }
                        $data[$ii] = array(
                            "loop" => array(
                                "$rekening" => $value,
                            ),
                            "static" => array(
                                "cabang_id" => $mmSpec->cabang_id,
                                "extern_id" => $mmSpec->extern_id,
                                "extern_nama" => $mmSpec->extern_nama,
                                "extern2_id" => $mmSpec->extern2_id,
                                "extern2_nama" => $mmSpec->extern2_nama,
//                                "jenis" => $mmSpec->jenis,
                                "jenis" => "",
                                "transaksi_id" => $transaksi_id,
                                "transaksi_no" => $transaksi_nomer,
                                "fulldate" => $fulldate,
                                "dtime" => $dtime,
                                "keterangan" => "pemindahan ke rugilaba dengan nomer $transaksi_nomer",
                            ),
                        );

                        $dataMain = array(
                            "loop" => array(
                                "$rekening" => $value,
                            ),
                            "static" => array(
                                "cabang_id" => $mmSpec->cabang_id,
                                "extern_id" => $mmSpec->extern_id,
                                "extern_nama" => $mmSpec->extern_nama,
                                "extern2_id" => $mmSpec->extern2_id,
                                "extern2_nama" => $mmSpec->extern2_nama,
//                                "jenis" => $mmSpec->jenis,
                                "jenis" => "",
                                "transaksi_id" => $transaksi_id,
                                "transaksi_no" => $transaksi_nomer,
                                "fulldate" => $fulldate,
                                "dtime" => $dtime,
                                "keterangan" => "pemindahan ke rugilaba dengan nomer $transaksi_nomer",
                            ),
                        );
                        cekUngu(":: main $rekening :: $comName ::");
                        $mm = New $comName();
                        $mm->setPeriode(array("forever"));
                        $mm->pair($dataMain);
                        $mm->exec();

                    }
//                    arrPrintKuning($data);


                    // execute yang biasanya dari gerbang detail
                    cekUngu(":: items $rekening :: $comName ::");
                    $mm = New $comName();
                    $mm->setPeriode(array("forever"));
                    $mm->pair($data);
                    $mm->exec();

                }


            }
        }


        mati_disini(__LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekLime("<h3>-- DONE --</h3>");

    }

    public function patchFifo()
    {
        $this->load->model("Mdls/MdlFifoProdukJadi");
        $this->load->model("Mdls/MdlProduk2");
        $this->load->model("Mdls/MdlProdukJenis");
        $this->load->model("MdlTransaksi");

        $pd = New MdlProduk2();
        $pdResult = $pd->lookupAll()->result();


        $cfp = New MdlFifoProdukJadi();
        $cfp->addFilter("unit>0");
//        $cfp->addFilter("ppv_riil=0");
        $cfpResult = $cfp->lookupAll()->result();

        $tr = New MdlTransaksi();

//        $pj = New MdlProdukJenis();
//        $pj->setFilters(array());
//        $pj->addFilter("jenis='ppv_index'");
//        $pj->addFilter("jenis2 in ('produk','produk rakitan')");
//        $pjTmp = $pj->lookupAll()->result();
////        arrPrint($pjTmp);
//        $pjResult = array();
//        foreach ($pjTmp as $pjSpec) {
////            if($pjSpec->jenis2 == "produk"){
////                $jenis = "item";
////            }
////            elseif($pjSpec->jenis2 == "produk rakitan"){
////                $jenis = "item";
////            }
//            $pjResult[$pjSpec->jenis2][$pjSpec->kode] = $pjSpec->nilai;
//        }
//        arrPrint($pjResult);
//        mati_disini(__LINE__);


        $this->db->trans_start();

        $arrProdukFactor = array();
        foreach ($pdResult as $pspec) {
            $produk_id = $pspec->id;
            $produk_jenis_nilai = $pspec->produk_jenis_nilai;
            $produk_jenis_id = $pspec->produk_jenis_id;

            $arrProdukFactor[$produk_id] = $produk_jenis_nilai;
            $arrProdukFactorID[$produk_id] = $produk_jenis_id;
        }

        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            foreach ($cfpResult as $ii => $cfpResultSpec) {
                $tblTrID = $cfpResultSpec->transaksi_id;
                $tblID = $cfpResultSpec->id;
                $qty = $cfpResultSpec->unit;
                $hpp = $cfpResultSpec->hpp;
                $ppvDB = $cfpResultSpec->ppv_riil;
                $pID = $cfpResultSpec->produk_id;
                $ppv_factor = isset($arrProdukFactor[$pID]) ? $arrProdukFactor[$pID] : 0;
                $ppv_factor_kali = ($ppv_factor > 1) ? ($ppv_factor - 1) : 0;

                $ppv = $hpp * $ppv_factor_kali;
                $ppv_nilai = $qty * $ppv;


                $tr->setFilters(array());
                $tr->addFilter("id='$tblTrID'");
                $trTmp = $tr->lookupAll()->result();
                $trJenis = $trTmp[0]->jenis;


//            if(($ppvDB == NULL) || ($ppvDB == 0)){
//                cekHere("$tblID, $ppv, $ppv_nilai");
                $cfp = New MdlFifoProdukJadi();
                $where = array(
                    "id" => $tblID
                );
                $data = array(
                    "ppv_riil" => $ppv,
                    "ppv_nilai_riil" => $ppv_nilai,
                    //---
//                    "ppv_factor" => $ppv_factor,
//                    "ppv_factor_persen" => ($ppv_factor_kali * 100),
                    //---
                    "transaksi_jenis" => $trJenis,
                );
                $cfp->updateData($where, $data);
                showLast_query("pink");
//            }


            }
        }

        foreach ($cfpResult as $ii => $cfpResultSpec) {
            $tblID = $cfpResultSpec->id;
            $pID = $cfpResultSpec->produk_id;
            $pNama = $cfpResultSpec->produk_nama;
            $qty = $cfpResultSpec->unit;
            $hpp = $cfpResultSpec->hpp;
            $hpp_nilai = $cfpResultSpec->jml_nilai;
            $hpp_nilai_hitung = $qty * $hpp;
            $selisih = $hpp_nilai_hitung - $hpp_nilai;
//            $selisih = ($selisih < 0) ? ($selisih *-1) : $selisih;
            if ($selisih > 1) {
                cekHere("tblID: $tblID, pID: $pID, jmlnilai: $hpp_nilai, hitung: $hpp_nilai_hitung, selisih: $selisih");
            }
            if ($selisih < 0) {
                cekHitam("tblID: $tblID, pID: $pID, jmlnilai: $hpp_nilai, hitung: $hpp_nilai_hitung, selisih: $selisih");
            }
        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();

        cekHijau("<h2>-- DONE --</h2>");

    }

    //-----------------
    public function patchSetupDepre()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlSetupDepresiasi");

        $rek_main_replace = array(
            "810000", "710000"
        );
        $arrTrDepre = array(
            174522, 173765, 173761, 173759, 173757, 173749, 173747, 173745, 173743, 173741,
            42490, 42492, 42494, 42496, 42498, 42500, 42705, 42707
        );
        $arrData = array();
        $arrModel = array(
            "1020010010" => "Coms/ComRekeningPembantuAktivaTetap",//kendaraan
            "1020020010" => "Coms/ComRekeningPembantuAktivaTetap",//peralatan kantor
            "1020030010" => "Coms/ComRekeningPembantuAktivaTetap",//mesin
            "1020040010" => "Coms/ComRekeningPembantuAktivaTetap",//mesin produksi
            "1020041010" => "Coms/ComRekeningPembantuAktivaTetap",//peralatan produksi
            "1020050010" => "Coms/ComRekeningPembantuAktivaTetap",//bangunan
            "1020060010" => "Coms/ComRekeningPembantuAktivaTetap",//tanah
        );
        foreach ($arrModel as $rek => $com) {
            $this->load->model("$com");
            $com_ex = explode("/", $com);
            $comName = $com_ex[1];

            $cm = New $comName();
            $cmTmp = $cm->fetchBalances($rek);
//            showLast_query("biru");
            foreach ($cmTmp as $spec) {
//                arrPrint($spec);
                $rekening = $spec->rekening;
                $cabang_id = $spec->cabang_id;
                $extern_id = $spec->extern_id;
                $arrData[$cabang_id][$extern_id] = array(
                    "rekening" => $rekening,
                    "cabang_id" => $cabang_id,
                    "extern_id" => $extern_id,
                    "extern_nama" => $spec->extern_nama,
                    "debet" => $spec->debet,
                    "kredit" => $spec->kredit,
                    "qty_debet" => $spec->qty_debet,
                    "qty_kredit" => $spec->qty_kredit,
                );
            }

//            break;
        }
//        cekHere(sizeof($arrData));
//        arrPrintPink($arrData[1][35]);
//        arrPrintPink($arrData);
//mati_disini(__LINE__);

        $sdp = New MdlSetupDepresiasi();
        $sdpTmp = $sdp->lookupAll()->result();


        $trreg = New MdlTransaksi();
        $trreg->setFilters(array());
        $trreg->setJointSelectFields("transaksi_id, items");
        $trreg->addFilter("transaksi_id in ('" . implode("','", $arrTrDepre) . "')");
        $regTmp = $trreg->lookupDataRegistries()->result();
        $dataItems = array();
        foreach ($regTmp as $regSpec) {
            $trid = $regSpec->transaksi_id;
            $items = blobDecode($regSpec->items);
//            arrPrint($items);


            foreach ($items as $ii => $spec) {
//                arrPrint($spec);
                $rekbiaya = $spec["rekName_2_child"];
                switch ($rekbiaya) {
                    case "biaya usaha":
                        $rekbiaya = "6010";
                        break;
                    case "biaya umum":
                        $rekbiaya = "6030";
                        break;
                    case "biaya produksi":
                        $rekbiaya = "6020";
                        break;
                }
                $rekbiaya_coa = isset($spec["rekName_2_child_coa"]) ? $spec["rekName_2_child_coa"] : NULL;

//                if($ii == 64){
//                    cekMerah(":: $rekbiaya :: $rekbiaya_coa :: " . $spec["rekName_2_child"]);
//                }

                $dataItems[$spec["placeID"]][$ii] = array(
                    "rekName_2_child_coa" => $rekbiaya_coa != NULL ? $rekbiaya_coa : $rekbiaya,
                );
            }
        }
//arrPrintKuning($arrData);
//arrPrintPink($dataItems);
//mati_disini(__LINE__);

        $this->db->trans_start();

        $data = array();
        foreach ($sdpTmp as $sdpSpec) {
            $idTbl = $sdpSpec->id;
            $asset_account = $sdpSpec->asset_account;
            $rekening_main = $sdpSpec->rekening_main;
            $rekening_detail = $sdpSpec->rekening_details;
            $extern_id = $sdpSpec->extern_id;
            $cabang_id = $sdpSpec->cabang_id;
            $account_rek = isset($arrData[$cabang_id][$extern_id]['rekening']) ? $arrData[$cabang_id][$extern_id]['rekening'] : $asset_account;
            $main_rek = isset($dataItems[$cabang_id][$extern_id]['rekName_2_child_coa']) ? $dataItems[$cabang_id][$extern_id]['rekName_2_child_coa'] : $rekening_main;

            if (strlen($asset_account) < 4) {
                $data["asset_account"] = $account_rek;
                $data["asset_account_old"] = $asset_account;
            }
            if (in_array($rekening_main, $rek_main_replace)) {
                $data["rekening_main"] = $main_rek;
                $data["rekening_main_old"] = $rekening_main;
            }
            if (strlen($rekening_detail) < 3) {
                $detail_rek = $main_rek . "000" . $rekening_detail;
                $data["rekening_details"] = $detail_rek;
                $data["rekening_details_old"] = $rekening_detail;
            }

            $where = array(
//                    "asset_account" => $asset_account,
                "cabang_id" => $cabang_id,
                "extern_id" => $extern_id,
                "jenis" => "assets",
            );
            $sdp = New MdlSetupDepresiasi();
            $sdp->updateData($where, $data);
            showLast_query("pink");


        }


        mati_disini(" OHOOOO ");
        $this->db->trans_complete();

        cekHijau("<h2>-- DONE --</h2>");

    }

}