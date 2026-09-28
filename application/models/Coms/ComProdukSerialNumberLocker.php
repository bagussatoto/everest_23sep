<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */

class ComProdukSerialNumberLocker extends CI_Model
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
    }

    public function pair($inParams)
    {
//        arrPrintPink($inParams);
//        matiHere();
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
                $lCounter = 0;
                foreach ($array_params['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = trim($value);
                    }
                }
//                arrprintWebs($this->outParam);

//
//                $_preValue = $this->cekPreValue(
//                    $array_params['static']['produk_serial_number_generate'],
//                    $array_params['static']['produk_serial_number'],
//                    $array_params['static']['cabang_id'],
//                    $array_params['static']['produk_id'],
//                    $array_params['static']['gudang_id']
//                );
//                $count = $this->cekPreValueCount(
//                    $array_params['static']['produk_serial_number_generate'],
//                    $array_params['static']['produk_serial_number'],
//                    $array_params['static']['cabang_id'],
//                    $array_params['static']['produk_id'],
//                    $array_params['static']['gudang_id']
//                );
//
//                if ($_preValue != null) {
//                    $this->outParams[$lCounter]["mode"] = "skip";
//                }
//                else {
//                    $count_new = $count + 1;
//                    //-- gabungan menjadi serial generate
//                    //-- thn_bln_tgl_po, nomer_po, nomer_grn, urut_grn_po, produk_id, count_produk_id
////                    $date_po = "20231101";
//                    $date_po = $array_params['static']['transaksi_reference_fulldate'];
//                    $date_po = str_replace("-", "", $date_po);
//                    $part_kode = $array_params['static']['part_keterangan'];
//
//                    $count_po = digit_4($array_params['static']['transaksi_reference_count']);
//                    $count_grn = digit_4($array_params['static']['transaksi_jenis_count']);
//                    $count_grn_po = digit_4($array_params['static']['transaksi_count']);
//                    $produk_id = digit_4($array_params['static']['produk_id']);
//                    $count_new_f = digit_4($count_new);
//                    // keterangan indoor, outdoor
//
//
//                    $serial_generate = "$date_po" . "$count_po" . "$count_grn" . "$count_grn_po" . "$produk_id" . "$count_new_f" . "$part_kode";
//
//                    $this->outParams[$lCounter]["mode"] = "new";
//                    $this->outParams[$lCounter]["count"] = $count_new;
//                    $this->outParams[$lCounter]["produk_serial_number_2"] = $serial_generate;
//
//                }

                $pakai_exec = 1;
                if ($pakai_exec == 1) {
                    if (sizeof($this->outParams) > 0) {
                        $insertIDs = array();
                        foreach ($this->outParams as $ctr => $params) {
//arrPrint($params);
                            $this->load->model("Mdls/MdlProdukPerSerialNumberLocker");
                            $l = new MdlProdukPerSerialNumberLocker();
                            $insertIDs = array();
//                            $mode = $params['mode'];
//                            unset($params['mode']);
//                            switch ($mode) {
//                                case "new":
//                                    $insertIDs[] = $l->addData($params);
//                                    break;
//                                case "skip":
//
//                                    break;
//                                default:
//                                    die("unknown writemode!");
//                                    break;
//                            }
//                            showLast_query("kuning");

                            // menulis ke tabel rek pembantu produk per-serial
//                            $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
//                            $arrData = array();
//                            $arrData[$ctr] = array(
//                                "loop" => array(
//                                    "1010030030" => "1",//persediaan produk, sub_diskon_nilai_total
//                                ),
//                                "static" => array(
//                                    "cabang_id" => $params["cabang_id"],
//                                    "gudang_id" => $params["gudang_id"],
//                                    "extern_id" => "0",
//                                    "extern_nama" => $params["produk_serial_number_2"],// nomer serial
//                                    "extern2_id" => "0",
//                                    "extern2_nama" => $params["produk_sku_part_nama"],// sku indoor, outdoor
//                                    "produk_id" => $params["produk_id"],// produk id
//                                    "produk_nama" => $params["produk_nama"],// produk nama
//                                    "produk_qty" => 1,
//                                    "produk_nilai" => 1,
//                                    "jenis" => $array_params['static']["jenis"],
//                                    "transaksi_id" => $params["transaksi_id"],
//                                    "transaksi_no" => $params["transaksi_no"],
//                                    "supplierID" => $params["supplier_id"],
//                                    "dtime" => $array_params['static']["dtime"],
//                                    "fulldate" => $array_params['static']["fulldate"],
//                                ),
//                            );
//                            $l = new ComRekeningPembantuProdukPerSerial();
//                            $l->pair($arrData);
//                            $l->exec();

                            $where = array(
                                "produk_serial_number" => $params["produk_serial_number"],
                                "cabang_id" => $params["cabang_id"],
                            );
                            $l->setFilters(array());
                            $insertIDs[] = $l->updateData($where, $params);
//                            showLast_query("orange");
//                            matiHere();

                        }


                        $this->outParams = array();

                        if (sizeof($insertIDs) == 0) {
                            cekMerah("::: PERIODE : $periode :::");
//                            return true;
                        }
                    }
                    else {
                        cekMerah("::: PERIODE : $periode :::");
//                        return true;
                    }

                }


            }

        }

        return true;

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
        showLast_query("pink");
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