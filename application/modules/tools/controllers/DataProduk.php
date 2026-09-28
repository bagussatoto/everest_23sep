<?php

/**
 * Created by PhpStorm.
 * User: none
 * Date: 5/6/2023
 * Time: 8:39 PM
 */
class DataProduk extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
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

    public function cleansingData()
    {
        $this->load->model("Mdls/MdlModelIndoor_1");
        $this->load->model("Mdls/MdlModelOutdoor");
        $this->load->model("Mdls/MdlProdukPartUkuran");
        $this->load->model("Mdls/MdlProduk2");


        $this->db->trans_start();

        // region DATA INDOOR
        $indoorNamaKode = array();
        $indoorData = array();
        $indoorDatas = array();
        $pin = New MdlModelIndoor_1();
        $pinTmp = $pin->lookupAll()->result();
        foreach ($pinTmp as $pinSpec){
            $sku = trim($pinSpec->sku);
            $nama = trim($pinSpec->nama);
            $indoorData[$pinSpec->id] = (array)$pinSpec;
            $indoorDatas[$sku][$nama][] = $pinSpec->id;
            $indoorNamaKode[$nama] = $sku;
        }
        foreach ($indoorDatas as $sku => $xx){
            foreach ($xx as $nama => $idTbls){
                if(sizeof($idTbls)>1){
//                    arrPrintKuning($idTbls);
                    $count = count($idTbls);
                    $count_min = $count - 1;
                    foreach ($idTbls as $key => $idTbl){
                        if($key < $count_min){
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
        // endregion

        // region DATA OUTDOOR
        $outdoorNamaKode = array();
        $outdoorData = array();
        $indoorDatas = array();
        $pin = New MdlModelOutdoor();
        $pinTmp = $pin->lookupAll()->result();
        foreach ($pinTmp as $pinSpec){
            $sku = trim($pinSpec->sku);
            $nama = trim($pinSpec->nama);
            $outdoorData[$pinSpec->id] = (array)$pinSpec;
            $indoorDatas[$sku][$nama][] = $pinSpec->id;
            $outdoorNamaKode[$nama] = $sku;
        }
        foreach ($indoorDatas as $sku => $xx){
            foreach ($xx as $nama => $idTbls){
                if(sizeof($idTbls)>1){
//                    arrPrintKuning($idTbls);
                    $count = count($idTbls);
                    $count_min = $count - 1;
                    foreach ($idTbls as $key => $idTbl){
                        if($key < $count_min){
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
        // endregion

        // region DATA UKURAN
        $ukuranDatas = array();
        $pin = New MdlProdukPartUkuran();
        $pinTmp = $pin->lookupAll()->result();
        foreach ($pinTmp as $pinSpec){
            $nama = trim($pinSpec->nama);
            $ukuranDatas[$pinSpec->kategori_id][$nama][] = $pinSpec->id;
        }
        foreach ($ukuranDatas as $kategoriID => $xx){
            foreach ($xx as $nama => $idTbls){
                if(sizeof($idTbls)>1){
//                    arrPrintKuning($idTbls);
                    $count = count($idTbls);
                    $count_min = $count - 1;
                    foreach ($idTbls as $key => $idTbl){
                        if($key < $count_min){
                            $where = array(
                                "id" => $idTbl,
                            );
                            $data = array(
                                "trash" => 1
                            );
                            $pin = New MdlProdukPartUkuran();
                            $pin->setFilters(array());
                            $pin->updateData($where, $data);
                            showLast_query("pink");
                        }
                    }

                }
            }
        }
        // endregion

        $pakai_ini = 1;
        if($pakai_ini == 1){

            // region DATA PRODUK UNIT
            $produkDataUnit = array();
            $produkDatas = array();
            $p = New MdlProduk2();
            $p->addFilter("kategori_id='1'");
            $pTmp = $p->lookupAll()->result();
//        showLast_query("biru");
//        mati_disini(__LINE__);
            foreach ($pTmp as $pSpec){
                $kode = trim($pSpec->kode);
                $nama = trim($pSpec->nama);
                $produkDataUnit[$pSpec->id] = $pSpec;
                $produkDatas[$kode][$nama][] = $pSpec->id;
            }
            foreach ($produkDatas as $kode => $xx){
                foreach ($xx as $nama => $idTbls){
                    if(sizeof($idTbls)>1){
//                    arrPrintKuning($idTbls);
                        $count = count($idTbls);
                        $count_min = $count - 1;
                        foreach ($idTbls as $key => $idTbl){
                            if($key < $count_min){
                                $where = array(
                                    "id" => $idTbl,
                                );
                                $data = array(
                                    "trash" => 1
                                );
                                $pin = New MdlProduk2();
                                $pin->setFilters(array());
                                $pin->updateData($where, $data);
                                showLast_query("orange");
                            }
                        }
                    }
                }
            }
            // endregion

            // region DATA PRODUK NON UNIT
            $produkDataNonUnit = array();
            $produkDatas = array();
            $p = New MdlProduk2();
            $p->addFilter("kategori_id='3'");
            $pTmp = $p->lookupAll()->result();
            foreach ($pTmp as $pSpec){
                $kode = trim($pSpec->kode);
                $nama = trim($pSpec->nama);
                $produkDataNonUnit[$pSpec->id] = $pSpec;
                $produkDatas[$kode][$nama][] = $pSpec->id;
            }
            foreach ($produkDatas as $kode => $xx){
                foreach ($xx as $nama => $idTbls){
                    if(sizeof($idTbls)>1){
                        $count = count($idTbls);
                        $count_min = $count - 1;
                        foreach ($idTbls as $key => $idTbl){
                            if($key < $count_min){
                                $where = array(
                                    "id" => $idTbl,
                                );
                                $data = array(
                                    "trash" => 1
                                );
                                $pin = New MdlProduk2();
                                $pin->setFilters(array());
                                $pin->updateData($where, $data);
                                showLast_query("orange");
                            }
                        }
                    }
                }
            }
            // endregion
        }

        $pakai_ini = 0;// tabel produk_ex
        if($pakai_ini == 1){
            // region DATA PRODUK UNIT EVEREST
            $produkDatas = array();
            $p = New MdlProduk2();
            $p->setTableName("produk_ex");
            $p->addFilter("kategori_id='1'");
            $pTmp = $p->lookupAll()->result();
//        showLast_query("biru");
//        mati_disini(__LINE__);
            foreach ($pTmp as $pSpec){
                $kode = trim($pSpec->kode);
                $nama = trim($pSpec->nama);
                $produkDatas[$kode][$nama][] = $pSpec->id;
            }
            foreach ($produkDatas as $kode => $xx){
                foreach ($xx as $nama => $idTbls){
                    if(sizeof($idTbls)>1){
//                    arrPrintKuning($idTbls);
                        $count = count($idTbls);
                        $count_min = $count - 1;
                        foreach ($idTbls as $key => $idTbl){
                            if($key < $count_min){
                                $where = array(
                                    "id" => $idTbl,
                                );
                                $data = array(
                                    "trash" => 1
                                );
//                            $pin = New MdlProduk2();
//                            $pin->setTableName("produk_ex");
                                $p->setFilters(array());
                                $p->updateData($where, $data);
                                showLast_query("orange");
                            }
                        }
                    }
                }
            }
            // endregion

            // region DATA PRODUK NON UNIT EVEREST
            $produkDataNonUnit = array();
            $produkDatas = array();
            $p = New MdlProduk2();
            $p->setTableName("produk_ex");
            $p->addFilter("kategori_id='3'");
            $pTmp = $p->lookupAll()->result();
            foreach ($pTmp as $pSpec){
                $kode = trim($pSpec->kode);
                $nama = trim($pSpec->nama);
                $produkDataNonUnit[$pSpec->id] = $pSpec;
                $produkDatas[$kode][$nama][] = $pSpec->id;
            }
            foreach ($produkDatas as $kode => $xx){
                foreach ($xx as $nama => $idTbls){
                    if(sizeof($idTbls)>1){
                        $count = count($idTbls);
                        $count_min = $count - 1;
                        foreach ($idTbls as $key => $idTbl){
                            if($key < $count_min){
                                $where = array(
                                    "id" => $idTbl,
                                );
                                $data = array(
                                    "trash" => 1
                                );
//                            $pin = New MdlProduk2();
                                $p->setFilters(array());
                                $p->updateData($where, $data);
                                showLast_query("orange");
                            }
                        }
                    }
                }
            }
            // endregion
        }

        //-----------------------------------------------------
        $pakai_ini = 0;
        if($pakai_ini == 1){
            foreach ($produkDataUnit as $pID => $pSpec){
                $outdoorID = $pSpec->outdoor_id;
                $indoorID_1 = $pSpec->indoor_id_1;
                $indoorID_2 = $pSpec->indoor_id_2;
                $indoorID_3 = $pSpec->indoor_id_3;
                $indoorID_4 = $pSpec->indoor_id_4;
                $outdoorBarcode = isset($outdoorData[$outdoorID]["barcode"]) ? $outdoorData[$outdoorID]["barcode"] : "";
                $indoorBarcode_1 = isset($indoorData[$indoorID_1]["barcode"]) ? $indoorData[$indoorID_1]["barcode"] : "";
                $indoorBarcode_2 = isset($indoorData[$indoorID_2]["barcode"]) ? $indoorData[$indoorID_2]["barcode"] : "";
                $indoorBarcode_3 = isset($indoorData[$indoorID_3]["barcode"]) ? $indoorData[$indoorID_3]["barcode"] : "";
                $indoorBarcode_4 = isset($indoorData[$indoorID_4]["barcode"]) ? $indoorData[$indoorID_4]["barcode"] : "";
                $outdoorSku = isset($outdoorData[$outdoorID]["sku"]) ? $outdoorData[$outdoorID]["sku"] : "";
                $indoorSku_1 = isset($indoorData[$indoorID_1]["sku"]) ? $indoorData[$indoorID_1]["sku"] : "";
                $indoorSku_2 = isset($indoorData[$indoorID_2]["sku"]) ? $indoorData[$indoorID_2]["sku"] : "";
                $indoorSku_3 = isset($indoorData[$indoorID_3]["sku"]) ? $indoorData[$indoorID_3]["sku"] : "";
                $indoorSku_4 = isset($indoorData[$indoorID_4]["sku"]) ? $indoorData[$indoorID_4]["sku"] : "";
//            cekHere("outdoor: [$pID] [$outdoorBarcode] [$outdoorSku]");
//            cekHere("indoor 1: [$pID] [$indoorBarcode_1] [$indoorSku_1]");
//            cekHere("indoor 2: [$pID] [$indoorBarcode_2] [$indoorSku_2]");
//            cekHere("indoor 3: [$pID] [$indoorBarcode_3] [$indoorSku_3]");
//            cekHere("indoor 4: [$pID] [$indoorBarcode_4] [$indoorSku_4]");

                $where = array(
                    "id" => "$pID",
                );
                $data = array(
                    "outdoor_barcode" => "$outdoorBarcode",
                    "indoor_barcode_1" => "$indoorBarcode_1",
                    "indoor_barcode_2" => "$indoorBarcode_2",
                    "indoor_barcode_3" => "$indoorBarcode_3",
                    "indoor_barcode_4" => "$indoorBarcode_4",
                    "outdoor_sku" => "$outdoorSku",
                    "indoor_sku_1" => "$indoorSku_1",
                    "indoor_sku_2" => "$indoorSku_2",
                    "indoor_sku_3" => "$indoorSku_3",
                    "indoor_sku_4" => "$indoorSku_4",
                );
                $p = New MdlProduk2();
                $p->updateData($where, $data);
                showLast_query("orange");
            }
        }
        //-----------------------------------------------------

        $pakai_ini = 0;
        if($pakai_ini == 1){
            $p = New MdlProduk2();
            $p->addFilter("kategori_id='3'");
            $pTmp = $p->lookupAll()->result();
            foreach ($pTmp as $pSpec) {
                $id_tbl = $pSpec->id;
                $nama = trim($pSpec->nama);
                $where = array(
                    "id" => $id_tbl,
                );
                if(isset($indoorNamaKode[$nama]) && ($indoorNamaKode[$nama] != null)){
                    $data = array(
                        "kode" => $indoorNamaKode[$nama],
                    );
                    $p->setFilters(array());
                    $p->updateData($where, $data);
                    showLast_query("orange");
                }
                elseif(isset($outdoorNamaKode[$nama]) && ($outdoorNamaKode[$nama] != null)){
                    $data = array(
                        "kode" => $outdoorNamaKode[$nama],
                    );
                    $p->setFilters(array());
                    $p->updateData($where, $data);
                    showLast_query("orange");
                }
            }
        }


        mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>...DONE...</h3>");


    }

    public function GenSkuProdukperSerial(){
        $tbl_1 = "produk";
        $tbl_2 = "produk_per_serialnumber";
        $condites = array(
            "produk_sku" => '0',
        );
        $this->db->where($condites);
        $produkData = $this->db->get($tbl_2)->result_array();
        showLast_query("hijau", count($produkData));
        foreach ($produkData as $produkDatum) {
            $produkIds[$produkDatum['produk_id']] = $produkDatum;
        }
// arrPrintKuning($produkIds);


        $this->db->where_in('id', array_keys($produkIds));
        $produkDataSrcs = $this->db->get($tbl_1)->result_array();
        showLast_query("merah", count($produkDataSrcs));
// arrPrintPink($produkDataSrcs);

        $this->db->trans_start();
        foreach ($produkDataSrcs as $produkDataSrc) {
            $kode = $produkDataSrc['kode'];
            $pid = $produkDataSrc['id'];

            $arrSet = array(
                "produk_sku" => $kode,
                "produk_sku_part_nama" => $kode,
            );
            $conditeUpd = array(
                "produk_id" => $pid,
                // "kode" => '',  ?
            );
            $this->db->set($arrSet);
            $this->db->where($conditeUpd);
            $var = $this->db->update($tbl_2);
            showLast_query("orange");
        }

        matiHere("belum comit " . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
    }

    public function perSupplier(){
        $tbl_1 = "produk";

        $this->db->where("trash", "0");
        $produkData = $this->db->get($tbl_1)->result_array();
        showLast_query("hijau", count($produkData));

        foreach ($produkData as $item) {
            $id = $item['id'];
            $nama = $item['nama'];
            $supplier_id = $item['supplier_id'];
            $supplier_nama = $item['supplier_nama'];


            $produkDouble[$nama][] = array(
                'id' => $item['id'],
                'nama' => $item['nama'],
                'kode' => $item['kode'],
                'barcode' => $item['barcode'],
                'kategori' => $item['kategori_nama'],
            );
            if($supplier_id > 0){
                $produkId[$id][$supplier_id]= $item;
                $supplierproduk1[$supplier_id][$id] = $item;
            }
        }

        // arrPrintHijau($produkId);

        $tbl_2 = "produk_per_supplier";
        $this->db->where("trash", "0");
        $produkSupplierData = $this->db->get($tbl_2)->result_array();
        showLast_query("hijau", count($produkData));

        foreach ($produkSupplierData as $produkSupplierDatum) {
            $supplier_id = $produkSupplierDatum['suppliers_id'];
            $produkId = $produkSupplierDatum['produk_id'];


            // $supplierproduk[$supplier_id] = $produkId;
            $supplierproduk[$supplier_id][$produkId] = $produkSupplierDatum;
        }

        // cekHere(count($supplierproduk1));
        // cekHere(count($supplierproduk));
        // arrPrintKuning($supplierproduk);
        foreach ($supplierproduk1 as $supp_id => $pro_id_speks) {
            cekHere("supplier_id=" . $supp_id);
            cekHijau("data " .count($pro_id_speks));

            $terelsai = $supplierproduk[$supp_id];
            cekKuning("terelsai " .count($terelsai));

            $belum = array_diff_key($pro_id_speks, $terelsai);
            cekBiru("belumterelasi " . count($belum));

            $supplierProdukNew[$supp_id] = $belum;
        }
        $this->db->trans_start();
        // arrPrintPink($supplierProdukNew);
        foreach ($supplierProdukNew as $spId => $items) {

            foreach ($items as $prId => $itemSpeks) {

                $newDatas = array(
                    "produk_id" => $prId,
                    "produk_nama" => $itemSpeks['nama'],
                    "suppliers_id" => $itemSpeks['supplier_id'],
                    "suppliers_nama" => $itemSpeks['supplier_nama'],
                    "cabang_id" => "-1",
                );

                $this->db->insert($tbl_2, $newDatas);
                // $this->db->insert_batch($tbl_2, $newDatas);
                showLast_query("biru");
            }

        }
        mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHitam("selesai");
    }



}