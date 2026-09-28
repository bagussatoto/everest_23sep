<?php

/**
 * Created by PhpStorm.
 * User: none
 * Date: 5/6/2023
 * Time: 8:39 PM
 */
class DataProduk2 extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
        // $this->db2 = $this->load->database('testing', TRUE);
    }

    public function RevisiBarcodeUnit()
    {

        $this->load->model("Mdls/MdlModelIndoor_1");
        $pro = new MdlModelIndoor_1();
        $src_od = $pro->lookupAll()->result();
        foreach ($src_od as $item) {
            $id_od = $item->id;
            $sku_od = $item->sku;
            $sku_ut = substr($sku_od, 0, -2);
            // cekHere("$id_od :: $sku_od :: $sku_ut");
            $ins[$id_od] = "$sku_od :: $sku_ut";
            // $ins[$id_od] = "$sku_od";
        }

        $this->load->model("Mdls/MdlModelOutdoor");
        $pro = new MdlModelOutdoor();
        $src_od = $pro->lookupAll()->result();
        // arrPrintPink($src_od);
        foreach ($src_od as $item) {
            $id_od = $item->id;
            $sku_od = $item->sku;
            $sku_ut = substr($sku_od, 0, -2);
            // cekBiru("$id_od :: $sku_od :: $sku_ut");
            $ots[$id_od] = "$sku_od :: $sku_ut";
            // $ots[$id_od] = "$sku_od";
        }

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        // $pr->addFilter("merek_id='1'");
        $pr->addFilter("kategori_nama='unit'");
        $src_pr = $pr->lookupAll()->result();
        showLast_query("here");
        $count = 0;
        foreach ($src_pr as $item) {
            $id_pr = $item->id;
            $outdoor_id = $item->outdoor_id;
            $indoor_id = $item->indoor_id_1;
            $barcode = $item->barcode;
            $barcode_new = $barcode . digit_4($id_pr);
            $dtOtdoor = $ots[$outdoor_id];
            $dtIndoor = $ins[$indoor_id];

            // cekHere("$id_pr :: $barcode::$barcode_new | $outdoor_id : $dtOtdoor| $indoor_id : $dtIndoor");
            cekHere("$id_pr :: $barcode :: $barcode_new");

            /*
             * update barcode produk + 4digit id
             * */
            // $pr->setFilters(array());
            // $pr->updateData(array("id" => $id_pr, "barcode !=" => $barcode_new), array("barcode" => $barcode_new));
            // showLast_query("kuning");
            $count++;
        }
        cekHijau($count);

        // $field_name = "dtime_2";
        // $fields = array(
        //     $field_name => array(
        //         'type' => 'datetime',
        //         // 'constraint' => 'default',
        //         'null' => TRUE,
        //         'after' => 'dtime',
        //     )
        // );

        $no = 0;
        // foreach ($tables as $table) {
        //     if (!$this->db->field_exists($field_name, $table)) {
        //         $no++;
        //
        //         $this->dbforge->add_column($table, $fields);
        //     }
        //     showLast_query("merah");
        // }
        // matiHere(__LINE__ . "  DONE $no table");
    }

    public function UpdateBarcodePendukung()
    {
        $this->load->model("Mdls/MdlModelOutdoor");
        $mo = new MdlModelOutdoor();
        $kolom_target_id = "outdoor_id";
        $kolom_target = "outdoor_barcode";

        // $this->load->model("Mdls/MdlModelIndoor_1");
        // $mo = new MdlModelIndoor_1();
        // $kolom_target_id = "indoor_id_1";
        // $kolom_target = "indoor_barcode_1";

        $mo_src = $mo->lookupAll()->result();
        // arrPrintKuing($mo_src);
        $dt_mo = array();
        foreach ($mo_src as $item) {
            $id = $item->id;
            $barcode = $item->barcode;

            $dt_mo[$id] = $barcode;
        }

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $pr_src = $pr->lookupAll()->result();
        $fields = $pr->getFields();

        foreach ($pr_src as $itemParams) {
            // arrPrintPink($itemParams);
            $produk_id = $itemParams->id;
            // $target_id = $outdoor_id = $itemParams->outdoor_id;
            $target_id = $outdoor_id = $itemParams->$kolom_target_id;
            $kolom_target_db = $itemParams->$kolom_target;

            // cekHere("$produk_id $outdoor_id");
            $value_target = $dt_mo[$target_id];
            if ($target_id > 0 && $kolom_target_db == null) {

                $pr->updateData(array("$kolom_target_id" => $target_id, "$kolom_target" => null), array("$kolom_target" => $value_target));
                showLast_query("kuning");
            }
        }


    }

    public function index()
    {
        matiHere("typo");
    }

    public function SyncProduk()
    {
        cekHere(__LINE__);
        // $this->load->model("Mdls/MdlProdukEx");
        // $prex = new MdlProdukEx();
        // $prex_src = $prex->lookupAll()->result();
        // $fields = $prex->getFields();
        // cekHijau($this->db2->last_query());
        // cekHere(count($prex_src));
        foreach ($prex_src as $item) {
            $produkex[$item->id] = (array)$item;
        }
        cekHijau(count($produkex));
        // arrPrintHijau(array_slice($produkex,0,1));
        // -------------------------------------------------------
        // $this->db->insert_batch('produk_ex', $produkex);

        showLast_query("orange");

        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        // $pr_src = $pr->lookupAll()->result();
        // $fields = $pr->getFields();

        // cekHijau(count($pr_src));
        // foreach ($pr_src as $item) {
        //     $produk[$item->id] = (array)$item;
        //     $kode = $item->kode;
        //     $produk_kode[$item->id] = $item->kode;
        // }
        // cekHijau(count($produk));

        // -------------------------------------------------------
        // $pr_dif = array_diff_key($prex_src, $pr_src);
        // $pr_inter = array_intersect_key($prex_src, $pr_src);

        // cekBiru(count($pr_dif));
        // cekBiru(count($pr_inter));
        // cekBiru(count($produk_kode));
    }

    public function syncDataToProduk()
    {

        $tblSlaves = array(
            // "produk" => array(
            //     "slave"  => "produk_copy",
            //     "master" => "produk",
            // ),
            // "outdoor" => array(
            //     "slave"  => "produk_outdoor_copy",
            //     "master" => "produk_outdoor",
            // ),
            "indoor" => array(
                "slave"  => "produk_indoor_1_copy",
                "master" => "produk_indoor_1",
            ),
        );

        $this->db->trans_start();
        foreach ($tblSlaves as $tbl_mode => $tblParams) {
            $tbl_sumber = $tblParams["slave"];
            $tbl_target = $tblParams["master"];
            // $kolom_filters = $tblParams["filter"];

            cekHere("$tbl_mode");
            // $tbl_sumber = "produk";
            // $tbl_target = "produk_copy"; // existing

            // $tbl_sumber = "produk_outdoor_old";
            // $tbl_target = "produk_outdoor";

            // $tbl_sumber = "produk_indoor_1_old";
            // $tbl_target = "produk_indoor_1";

            // Ambil data dari tabel produk_copy
            $this->db->where("trash", "0");
            $produkCopyData = $this->db->get($tbl_sumber)->result_array();
            showLast_query("hijau");
            // $this->load->model("Mdls/MdlProdukEx");
            // $prex = new MdlProdukEx();
            // $produkCopyData = $prex->lookupAll()->result_array();


            foreach ($produkCopyData as $existingDatum) {
                unset($existingDatum['id']);
                $produkCopyData_0[] = $existingDatum;
            }

            if (!empty($produkCopyData)) {
                // Ambil data yang sudah ada di tabel produk_ex
                $this->db->where("trash", "0");
                $existingData = $this->db->get($tbl_target)->result_array();
                $existingData_0 = array();
                foreach ($existingData as $existingDatum) {
                    unset($existingDatum['id']);
                    $existingData_0[] = $existingDatum;
                }
                // arrPrintKuning(array_slice($existingData_0,0,1));
                // matiHere(__LINE__);
                cekHijau("jml data: " . $old = count($existingData_0));

                // Filter data yang belum ada di tabel produk_ex
                switch ($tbl_mode) {
                    case "produk":
                    case "outdoor":
                    case "indoor":
                        $newData = array_filter($produkCopyData_0, function ($item) use ($existingData_0) {
                            return !$this->isDataExistInProdukEx($item, $existingData_0);
                        });
                        break;
                    default:
                        matiHere("tbl mode <h3>$tbl_mode</h3> belum disetting");
                        break;
                }
                // arrPrintWebs($newData);

                // Jika ada data baru, masukkan ke tabel produk_existing
                cekBiru("jml data yg anu mau dinsertkan: " . $add = count($newData));
                cekKuning("total data yg baru :$old + $add = " . ($old + $add));

                if (!empty($newData)) {
                    $this->db->insert_batch($tbl_target, $newData);
                    showLast_query("biru");
                }
                else {
                    cekBiru("data sudah sinkron");
                }
            }

        }
        // mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
    }

    // Fungsi untuk memeriksa apakah data sudah ada di tabel produk_existing
    private function isDataExistInProdukEx($data, $existingData)
    {
        foreach ($existingData as $existing) {
            if ($existing['nama'] == $data['nama'] && $existing['barcode'] == $data['barcode'] && $existing['sku'] == $data['sku']) {
                return true;
            }
        }
        return false;
    }

    public function cleansingData()
    {
        $this->load->model("Mdls/MdlModelIndoor_1");
        $this->load->model("Mdls/MdlModelOutdoor");


        $this->db->trans_start();

        // DATA INDOOR
        $indoorDatas = array();
        $pin = New MdlModelIndoor_1();
        $pinTmp = $pin->lookupAll()->result();
        foreach ($pinTmp as $pinSpec) {
            //            arrPrintPink($pinSpec);
            $indoorDatas[$pinSpec->sku][$pinSpec->nama][] = $pinSpec->id;
        }
        //        arrPrintKuning($indoorDatas);
        foreach ($indoorDatas as $sku => $xx) {
            foreach ($xx as $nama => $idTbls) {
                if (sizeof($idTbls) > 1) {
                    //                    arrPrintKuning($idTbls);
                    $count = count($idTbls);
                    $count_min = $count - 1;
                    foreach ($idTbls as $key => $idTbl) {
                        if ($key < $count_min) {
                            $where = array(
                                "id" => $idTbl,
                            );
                            $data = array(
                                "trash" => 1
                            );
                            $pin = New MdlModelIndoor_1();
                            $pin->setFilters(array());
                            $pin->updateData($where, $data);
                            showLast_query("orange");
                        }
                    }

                }
            }
        }


        // DATA OUTDOOR
        $indoorDatas = array();
        $pin = New MdlModelOutdoor();
        $pinTmp = $pin->lookupAll()->result();
        foreach ($pinTmp as $pinSpec) {
            //            arrPrintPink($pinSpec);
            $indoorDatas[$pinSpec->sku][$pinSpec->nama][] = $pinSpec->id;
        }
        //        arrPrintKuning($indoorDatas);
        foreach ($indoorDatas as $sku => $xx) {
            foreach ($xx as $nama => $idTbls) {
                if (sizeof($idTbls) > 1) {
                    //                    arrPrintKuning($idTbls);
                    $count = count($idTbls);
                    $count_min = $count - 1;
                    foreach ($idTbls as $key => $idTbl) {
                        if ($key < $count_min) {
                            $where = array(
                                "id" => $idTbl,
                            );
                            $data = array(
                                "trash" => 1
                            );
                            $pin = New MdlModelOutdoor();
                            $pin->setFilters(array());
                            $pin->updateData($where, $data);
                            showLast_query("pink");
                        }
                    }

                }
            }
        }


        mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>...DONE...</h3>");


    }

    public function cleansingProduk(){
        $tbl_2 = "_rek_pembantu_produk_cache";

        // $this->db->where("trash", "0");
        $produkData = $this->db->get($tbl_2)->result_array();
        showLast_query("hijau", count($produkData));
        foreach ($produkData as $produkDatum) {
            $sxtern_id = $produkDatum['extern_id'];

            $ygsudahdipakai[$sxtern_id] = $sxtern_id;
        }
cekHijau(count($ygsudahdipakai));
//         matiHere(__LINE__);

        $tbl_1 = "produk";

        $this->db->where("trash", "0");
        $produkData = $this->db->get($tbl_1)->result_array();
        showLast_query("hijau", count($produkData));

        foreach ($produkData as $item) {
            $id = $item['id'];
            $nama = $item['nama'];


            $produkDouble[$nama][] = array(
                'id' => $item['id'],
                'nama' => $item['nama'],
                'kode' => $item['kode'],
                'barcode' => $item['barcode'],
                'kategori' => $item['kategori_nama'],
            );
            $produkId[$id]= $item;
        }

        // cekHere(count($produkDouble));
        cekHere(count($produkId));

        $cocoks = (array_intersect_key($ygsudahdipakai, $produkId));

        arrPrintKuning(count($cocoks));
        // arrPrintKuning($produkDouble);
// matiHere();
        foreach ($produkDouble as $namaItem => $dataItem) {
            if(count($dataItem) > 1){
                $ygDouble[$namaItem] = $dataItem;
            }
        }

        $this->db->trans_start();
        cekHijau(count($ygDouble));
        arrPrintHijau($ygDouble);
        $count=0;
        foreach ($ygDouble as $namaitem2 => $dataItem2) {
            foreach ($dataItem2 as $ix => $item3) {
                if($ix >= 1){
                    // if($item3['kode'] == ''){
                    //
                    //     $arrSet = array(
                    //         "trash" => 1,
                    //     );
                    //     $conditeUpd = array(
                    //         "id" => $item3['id'],
                    //         // "kode" => '',  ?
                    //     );
                    //     $this->db->set($arrSet);
                    //     $this->db->where($conditeUpd);
                    //     $var = $this->db->update($tbl_1);
                    //     showLast_query("orange");
                    // }

                    if(!array_key_exists($item3['id'], $ygsudahdipakai)){

                            $arrSet = array(
                                "trash" => 2,
                            );
                            $conditeUpd = array(
                                "id" => $item3['id'],
                                // "kode" => '',  ?
                            );
                            $this->db->set($arrSet);
                            $this->db->where($conditeUpd);
                            $var = $this->db->update($tbl_1);
                            showLast_query("orange");

                            $count++;
                    }
                    else{
                        arrPrintWebs($item3);
                    }
                }
            }
        }
        cekMerah("counter $count");
        mati_disini("belum dicommit " .__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


    }
}