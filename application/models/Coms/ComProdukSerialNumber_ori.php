<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */

class ComProdukSerialNumber extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
//        "jenis",
        "produk_serial_number",
        "produk_serial_number_2",
        "produk_sku",
        "produk_sku_label",
        "produk_sku_serial",
        "produk_id",
        "produk_nama",
        "produk_sku_part_id",
        "produk_sku_part_nama",
        "produk_sku_part_serial",
        "cabang_id",
        "cabang_nama",
        "gudang_id",
        "gudang_nama",
//        "nama",
//        "satuan",
//        "state",
//        "jumlah",
        "oleh_id",
        "oleh_nama",
        "supplier_id",
        "supplier_nama",
//        "transaksi_id",
//        "nomer",
//        "gudang_id",
//
        "status",
        "trash",
        "dtime",
        "fulldate",
        "transaksi_id",
        "transaksi_no",
        "transaksi_count",
        "transaksi_reference_id",
        "transaksi_reference_no",
        "transaksi_reference_dtime",
        "transaksi_reference_count",
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");

    }

    public function pair_ori($inParams)
    {
        arrPrintPink($inParams);
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
//                arrPrintWebs($array_params['static']);
                $lCounter = 0;

                foreach ($array_params['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = trim($value);
                    }
                }

                //// locker membutuhkan cabangID dan gudangID, bila tidak ada kiriman gerbang nilai maka dihentikan saja.
//                $msg_1 = "Transaksi gagal disimpan karena id cabang login tidak terdaftar. silahkan relogin atau hubungi admin.";
////                $msg_2 = "Transaksi gagal disimpan karena id cabang login tidak terdaftar. silahkan relogin atau hubungi admin.";
////                $msg_3 = "Transaksi gagal disimpan karena id cabang login tidak terdaftar. silahkan relogin atau hubungi admin.";
//                $msg_4 = "Transaksi gagal disimpan karena id gudang login tidak terdaftar. silahkan relogin atau hubungi admin.";
//                $msg_5 = "Transaksi gagal disimpan karena id produk tidak terdaftar. silahkan relogin atau hubungi admin.";
//
//                $cabangID = isset($paramAsli['static']['cabang_id']) ? $paramAsli['static']['cabang_id'] : mati_disini($msg_1);
//                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
//                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
//                $defaultGudangID = isset($paramAsli['static']['gudang_id']) ? $paramAsli['static']['gudang_id'] : mati_disini($msg_4);
//                $produkID = isset($paramAsli['static']['produk_id']) ? $paramAsli['static']['produk_id'] : mati_disini($msg_5);

                $_preValue = $this->cekPreValue(
                    $array_params['static']['produk_serial_number_generate'],
                    $array_params['static']['produk_serial_number'],
                    $array_params['static']['cabang_id'],
                    $array_params['static']['produk_id'],
                    $array_params['static']['gudang_id']
                );
                $count = $this->cekPreValueCount(
                    $array_params['static']['produk_serial_number_generate'],
                    $array_params['static']['produk_serial_number'],
                    $array_params['static']['cabang_id'],
                    $array_params['static']['produk_id'],
                    $array_params['static']['gudang_id']
                );

                if ($_preValue != null) {
                    $this->outParams[$lCounter]["mode"] = "skip";
                }
                else {
                    $count_new = $count + 1;
                    //-- gabungan menjadi serial generate
                    //-- thn_bln_tgl_po, nomer_po, nomer_grn, urut_grn_po, produk_id, count_produk_id
//                    $date_po = "20231101";
                    $date_po = isset($array_params['static']['transaksi_reference_fulldate']) ? $array_params['static']['transaksi_reference_fulldate'] : $array_params['static']['fulldate'];
                    $date_po = str_replace("-", "", $date_po);
                    $part_kode = strlen($array_params['static']['part_keterangan']) > 1 ? $array_params['static']['part_keterangan'] : "";

                    $count_po = digit_4($array_params['static']['transaksi_reference_count']);
                    $count_grn = digit_4($array_params['static']['transaksi_jenis_count']);
                    $count_grn_po = digit_4($array_params['static']['transaksi_count']);
                    $produk_id = digit_5($array_params['static']['produk_id']);
                    $count_new_f = digit_4($count_new);
                    // keterangan indoor, outdoor
                    cekHere(":: $date_po");
                    cekHere(":: $count_po");
                    cekHere(":: $count_grn");
                    cekHere(":: $count_grn_po");
                    cekHere(":: $produk_id");
                    cekHere(":: $count_new_f");
                    cekHere(":: $part_kode");

//arrprint($array_params['static']);
//                    $serial_generate = "$date_po" . "$count_po" . "$count_grn" . "$count_grn_po" . "$produk_id" . "$count_new_f" . "$part_kode";
                    $serial_to_generate = array(
                        $date_po, $count_po, $count_grn, $count_grn_po, $produk_id, $count_new_f, $part_kode
                    );
                    $serial_generate = implode(':', $serial_to_generate);
//                    matiHEre(implode(':',$serial_generate));
//matiHere($array_params['static']['transaksi_reference_fulldate'].">".$serial_generate);

                    $this->outParams[$lCounter]["mode"] = "new";
                    $this->outParams[$lCounter]["count"] = $count_new;
                    $this->outParams[$lCounter]["produk_serial_number_2"] = $serial_generate;
                    $this->outParams[$lCounter]["produk_sku_label"] = $array_params['static']['part_keterangan'];

                }

                $pakai_exec = 1;
                if ($pakai_exec == 1) {
                    if (sizeof($this->outParams) > 0) {
                        $insertIDs = array();
                        foreach ($this->outParams as $ctr => $params) {
                            if ($array_params['static']['jumlah'] > 0) {

                                $this->load->model("Mdls/MdlProdukPerSerialNumber");
                                $l = new MdlProdukPerSerialNumber();
                                $insertIDs = array();
                                $mode = $params['mode'];
                                unset($params['mode']);
                                switch ($mode) {
                                    case "new":
                                        $insertIDs[] = $l->addData($params);
                                        break;
//                                case "update":
//                                    $insertIDs[] = $l->updateData(array(
//                                        "cabang_id" => $params['cabang_id'],
//                                        "gudang_id" => $params['gudang_id'],
//                                        "produk_id" => $params['produk_id'],
//                                        "state" => $params['state'],
//                                        "oleh_id" => $params['oleh_id'],
//                                        "transaksi_id" => $params['transaksi_id'],
//                                    ), $params);
//                                    break;
                                    case "skip":

                                        break;
                                    default:
                                        die("unknown writemode!");
                                        break;
                                }
                                showLast_query("kuning");
                            }


                            // menulis ke tabel rek pembantu produk per-serial
                            $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");

                            $arrData = array();
                            $arrData[$ctr] = array(
                                "loop" => array(
                                    "1010030030" => $array_params['static']['jumlah'],//persediaan produk, sub_diskon_nilai_total
                                ),
                                "static" => array(
                                    "cabang_id" => $params["cabang_id"],
                                    "gudang_id" => $params["gudang_id"],
                                    "extern_id" => "0",
                                    "extern_nama" => $params["produk_serial_number_2"],// nomer serial
                                    "extern2_id" => "0",
                                    "extern2_nama" => $params["produk_sku_part_nama"],// sku indoor, outdoor
                                    "produk_id" => $params["produk_id"],// produk id
                                    "produk_nama" => $params["produk_nama"],// produk nama
                                    "produk_qty" => $array_params['static']['jumlah'],
                                    "produk_nilai" => $array_params['static']['jumlah'],
                                    "jenis" => $array_params['static']["jenis"],
                                    "transaksi_id" => $params["transaksi_id"],
                                    "transaksi_no" => $params["transaksi_no"],
                                    "supplierID" => $params["supplier_id"],
                                    "dtime" => $array_params['static']["dtime"],
                                    "fulldate" => $array_params['static']["fulldate"],
                                ),
                            );
//arrPrintKuning($arrData[$ctr]);
                            $l = new ComRekeningPembantuProdukPerSerial();
                            $l->pair($arrData);
                            $l->exec();


                        }

//mati_disini(__LINE__);


                        $this->outParams = array();

                        if (sizeof($insertIDs) == 0) {
//                            cekMerah("::: PERIODE : $periode :::");
//                            return true;
                        }
                    }
                    else {
//                        cekMerah("::: PERIODE : $periode :::");
//                        return true;
                    }

                }


            }

        }
//        mati_disini(__FUNCTION__ . " :::: " . __LINE__);
        return true;

    }

    public function pair($inParams)
    {
//        arrPrintPink($inParams);
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $ix => $array_params) {
                if (isset($array_params['static']["rejection"]) && $array_params['static']["rejection"] == "1") {
                    //langsung hajar saja
                    //ambil prevaluenya transaki ID
                    $_preValues = $this->cekPreValueTr($array_params['static']['transaksi_id']);
                    arrPrintKuning($_preValues);
                    if (count($_preValues) > 0) {
                        foreach ($_preValues as $_preValue) {

                            //update perserial trash 1
                            $this->load->model("Mdls/MdlProdukPerSerialNumber");
                            $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
                            $rc = new ComRekeningPembantuProdukPerSerial();
                            $l = new MdlProdukPerSerialNumber();
                            $where = array(
                                "id" => $_preValue["id"],
                            );
                            $update = array(
                                "trash" => "1",
                                "status" => "0",
                            );
                            $l->setFilters(array());
                            $insertID[] = $l->updateData($where, $update) or matiHere("gagal update");
                            cekHitam($this->db->last_query());

                            $pakai_ini = 0;
                            if ($pakai_ini == 1) {
                                //udpate cache serial
                                $wheres = array(
                                    "produk_id" => $array_params["static"]["produk_id"],
                                    "extern_nama" => $_preValue["produk_serial_number"],
                                    "cabang_id" => $array_params["static"]["cabang_id"],
                                );
                                $updateCache = array(
                                    "debet" => 0,
                                    "saldo_debet" => 0,
                                    "qty_debet" => 0,
                                    "saldo_qty_debet" => 0,
                                    "kredit" => 1,
                                    "saldo_kredit" => 1,
                                    "qty_kredit" => 1,
                                    "saldo_qty_kredit" => 1,
                                );
                                $rc->setFilters(array());
                                $insertID[] = $rc->updateData($wheres, $updateCache) or matiHere("gagal memperbahahui data ");
                                cekOrange($this->db->last_query());
                            }
                            else {
//                                mati_disini(__LINE__);
                                // menulis ke tabel rek pembantu produk per-serial
                                $arrData = array();
                                $arrData[0] = array(
                                    "loop" => array(
                                        "1010030030" => $array_params['static']['jumlah'],//persediaan produk, sub_diskon_nilai_total
                                    ),
                                    "static" => array(
                                        "cabang_id" => $array_params['static']["cabang_id"],
                                        "gudang_id" => $array_params['static']["gudang_id"],
                                        "extern_id" => "0",
                                        "extern_nama" => $_preValue["produk_serial_number"],// nomer serial
                                        "extern2_id" => "0",
                                        "extern2_nama" => $_preValue["produk_sku_part_nama"],// sku indoor, outdoor
                                        "produk_id" => $_preValue["produk_id"],// produk id
                                        "produk_nama" => $_preValue["produk_nama"],// produk nama
                                        "produk_qty" => $array_params['static']['jumlah'],
                                        "produk_nilai" => $array_params['static']['jumlah'],
                                        "jenis" => $array_params['static']["jenis"],
                                        "transaksi_id" => $array_params['static']["transaksi_id"],
                                        "transaksi_no" => $array_params['static']["transaksi_no"],
                                        "supplierID" => $array_params['static']["supplier_id"],
                                        "dtime" => $array_params['static']["dtime"],
                                        "fulldate" => $array_params['static']["fulldate"],
                                        "rejection" => 1,
                                    ),
                                );
                                $l = new ComRekeningPembantuProdukPerSerial();
                                $l->pair($arrData);
                                $l->exec();

                            }

                        }
                    }

                    break;// karena sudah diproses semuanya...
                }
                else {
                    $lCounter = 0;
                    foreach ($array_params['static'] as $key => $value) {
                        if (in_array($key, $this->outFields)) {
                            $this->outParams[$lCounter][$key] = trim($value);
                        }
                    }

                    $_preValue = $this->cekPreValue(
                        $array_params['static']['produk_serial_number_generate'],
                        $array_params['static']['produk_serial_number'],
                        $array_params['static']['cabang_id'],
                        $array_params['static']['produk_id'],
                        $array_params['static']['gudang_id']
                    );
                    $count = $this->cekPreValueCount(
                        $array_params['static']['produk_serial_number_generate'],
                        $array_params['static']['produk_serial_number'],
                        $array_params['static']['cabang_id'],
                        $array_params['static']['produk_id'],
                        $array_params['static']['gudang_id']
                    );

                    if ($_preValue != null) {
                        $this->outParams[$lCounter]["mode"] = "skip";
                    }
                    else {
                        cekUngu("last_count $count");
                        $count_new = $count + 1;
                        //-- gabungan menjadi serial generate
                        //-- thn_bln_tgl_po, nomer_po, nomer_grn, urut_grn_po, produk_id, count_produk_id
//                    $date_po = "20231101";
//                        $date_po = isset($array_params['static']['transaksi_reference_fulldate']) ? $array_params['static']['transaksi_reference_fulldate'] : $array_params['static']['fulldate'];
                        $date_po = isset($array_params['static']['transaksi_reference_fulldate']) && strlen($array_params['static']['transaksi_reference_fulldate']) > 5 ? $array_params['static']['transaksi_reference_fulldate'] : $array_params['static']['fulldate'];
                        $date_po = str_replace("-", "", $date_po);
                        $part_kode = strlen($array_params['static']['part_keterangan']) > 1 ? $array_params['static']['part_keterangan'] : "";

                        $count_po = digit_4($array_params['static']['transaksi_reference_count']);
                        $count_grn = digit_4($array_params['static']['transaksi_jenis_count']);
                        $count_grn_po = digit_4($array_params['static']['transaksi_count']);
                        $produk_id = digit_5($array_params['static']['produk_id']);
                        $count_new_f = digit_4($count_new);
                        // keterangan indoor, outdoor
                        cekHere(":: $date_po");
                        cekHere(":: $count_po");
                        cekHere(":: $count_grn");
                        cekHere(":: $count_grn_po");
                        cekHere(":: $produk_id");
                        cekHere(":: $count_new_f");
                        cekHere(":: $part_kode");

//arrprint($array_params['static']);
//                    $serial_generate = "$date_po" . "$count_po" . "$count_grn" . "$count_grn_po" . "$produk_id" . "$count_new_f" . "$part_kode";
                        $serial_to_generate = array(
                            $date_po, $count_po, $count_grn, $count_grn_po, $produk_id, $count_new_f, $part_kode
                        );
                        $serial_generate = implode(':', $serial_to_generate);
//                    matiHEre(implode(':',$serial_generate));
//matiHere($array_params['static']['transaksi_reference_fulldate'].">".$serial_generate);

                        $this->outParams[$lCounter]["mode"] = "new";
                        $this->outParams[$lCounter]["count"] = $count_new;
                        $this->outParams[$lCounter]["produk_serial_number_2"] = $serial_generate;
                        $this->outParams[$lCounter]["produk_sku_label"] = $array_params['static']['part_keterangan'];

                    }

                    $pakai_exec = 1;
                    if ($pakai_exec == 1) {
                        if (sizeof($this->outParams) > 0) {
                            $insertIDs = array();
                            foreach ($this->outParams as $ctr => $params) {
                                if ($array_params['static']['jumlah'] > 0) {

                                    $this->load->model("Mdls/MdlProdukPerSerialNumber");
                                    $l = new MdlProdukPerSerialNumber();
                                    $insertIDs = array();
                                    $mode = $params['mode'];
                                    unset($params['mode']);
                                    switch ($mode) {
                                        case "new":
                                            $insertIDs[] = $l->addData($params);
                                            break;
//                                case "update":
//                                    $insertIDs[] = $l->updateData(array(
//                                        "cabang_id" => $params['cabang_id'],
//                                        "gudang_id" => $params['gudang_id'],
//                                        "produk_id" => $params['produk_id'],
//                                        "state" => $params['state'],
//                                        "oleh_id" => $params['oleh_id'],
//                                        "transaksi_id" => $params['transaksi_id'],
//                                    ), $params);
//                                    break;
                                        case "skip":

                                            break;
                                        default:
                                            die("unknown writemode!");
                                            break;
                                    }
                                    showLast_query("kuning");
                                }


                                // menulis ke tabel rek pembantu produk per-serial
                                $arrData = array();
                                $arrData[$ctr] = array(
                                    "loop" => array(
                                        "1010030030" => $array_params['static']['jumlah'],//persediaan produk, sub_diskon_nilai_total
                                    ),
                                    "static" => array(
                                        "cabang_id" => $params["cabang_id"],
                                        "gudang_id" => $params["gudang_id"],
                                        "extern_id" => "0",
                                        "extern_nama" => $params["produk_serial_number_2"],// nomer serial
                                        "extern2_id" => "0",
                                        "extern2_nama" => $params["produk_sku_part_nama"],// sku indoor, outdoor
                                        "produk_id" => $params["produk_id"],// produk id
                                        "produk_nama" => $params["produk_nama"],// produk nama
                                        "produk_qty" => $array_params['static']['jumlah'],
                                        "produk_nilai" => $array_params['static']['jumlah'],
                                        "jenis" => $array_params['static']["jenis"],
                                        "transaksi_id" => $params["transaksi_id"],
                                        "transaksi_no" => $params["transaksi_no"],
                                        "supplierID" => $params["supplier_id"],
                                        "dtime" => $array_params['static']["dtime"],
                                        "fulldate" => $array_params['static']["fulldate"],
                                    ),
                                );
//arrPrintKuning($arrData[$ctr]);
                                $l = new ComRekeningPembantuProdukPerSerial();
                                $l->pair($arrData);
                                $l->exec();


                            }

//mati_disini(__LINE__);


                            $this->outParams = array();

                            if (sizeof($insertIDs) == 0) {
//                            cekMerah("::: PERIODE : $periode :::");
//                            return true;
                            }
                        }
                        else {
//                        cekMerah("::: PERIODE : $periode :::");
//                        return true;
                        }

                    }
                }


            }

        }
//        mati_disini(__FUNCTION__ . " :::: " . __LINE__);
        return true;

    }

    private function cekPreValueTr($transaksi_id)
    {

        $this->load->model("Mdls/MdlProdukPerSerialNumber");
        $l = new MdlProdukPerSerialNumber();
        $l->addFilter("transaksi_id='$transaksi_id'");
//        $l->addFilter("trash='0'");
//        $this->db->limit(1);
        $result = array();
        $localFilters = array();
        if (sizeof($l->getfilters()) > 0) {
            foreach ($l->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }
        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
//            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();
        showLast_query("biru");
//        arrPrint($tmp);
//        matiHere();
        $result = array();
        if (count($tmp) > 0) {
            foreach ($tmp as $ii => $spec) {
                $result[$ii] = array(
                    "id" => $spec->id,
                    "produk_serial_number" => $spec->produk_serial_number_2,//serial dari system bukan yg bawaan parik
                    "produk_id" => $spec->produk_id,
                    "produk_nama" => $spec->produk_nama,
                    "produk_sku_part_nama" => $spec->produk_sku_part_nama,
                );
            }
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    private function cekPreValue($serial_generate, $jenis, $cabang_id, $produk_id, $gudang_id)
    {

        $this->load->model("Mdls/MdlProdukPerSerialNumber");
        $l = new MdlProdukPerSerialNumber();
        $l->addFilter("produk_serial_number_2='$serial_generate'");
        $l->addFilter("produk_serial_number='$jenis'");
        $l->addFilter("produk_id='$produk_id'");
        $result = array();
        $localFilters = array();
        if (sizeof($l->getfilters()) > 0) {
            foreach ($l->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }
        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
//matiHere("ini $state");
//        $nilai = $tmp['qty_debet'];
        if (sizeof($tmp) > 0) {
            $result = $tmp['id'];
        }
        else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    private function cekPreValueCount($serial_generate, $jenis, $cabang_id, $produk_id, $gudang_id)
    {

        $this->load->model("Mdls/MdlProdukPerSerialNumber");
        $l = new MdlProdukPerSerialNumber();
//        $l->addFilter("produk_serial_number_2='$serial_generate'");
//        $l->addFilter("produk_serial_number='$jenis'");
        $l->addFilter("produk_id='$produk_id'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $result = array();
        $localFilters = array();
        if (sizeof($l->getfilters()) > 0) {
            foreach ($l->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }
        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->order_by("id", "desc")
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
//        showLast_query("pink");
//        arrPrintWebs($tmp);
//        matiHere("ini $state");
//        $nilai = $tmp['qty_debet'];
        if (sizeof($tmp) > 0) {
            $result = $tmp['count'];
        }
        else {
            $result = 0;
        }


        return $result;
    }

    public function exec()
    {
        return true;
    }

}