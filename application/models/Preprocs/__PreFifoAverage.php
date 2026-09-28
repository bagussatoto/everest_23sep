<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreFifoAverage extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    //<editor-fold desc="getter-setter">

    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
    }

    public function getInParams()
    {
        return $this->inParams;
    }

    public function setInParams($inParams)
    {
        $this->inParams = $inParams;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function getResultParams()
    {
        return $this->resultParams;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $inParams)
    {
        $this->load->model("Mdls/MdlFifoAverage");
        arrPrint($inParams);
//mati_disini();
        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        $tmp = array();
        if (sizeof($inParams) > 0) {
            foreach ($inParams as $sentParams) {
                foreach ($sentParams as $pSpec) {
                    $needles[$pSpec['extern_id']] = $pSpec['produk_qty'];
                    $ids[] = $pSpec["extern_id"];

                    $b = new MdlFifoAverage();
                    $b->addFilter("jenis='produk'");
                    $b->addFilter("cabang_id='" . $sentParams['static']['cabang_id'] . "'");
                    $b->addFilter("gudang_id='" . $sentParams['static']['gudang_id'] . "'");
                    $b->addFilter("produk_id='" . $pSpec["extern_id"] . "'");
                    // ini diupdate ke => for update
//                    $tmp = $b->lookupAll()->result();

                    $localFilters = array();
                    if (sizeof($b->getfilters()) > 0) {
                        foreach ($b->getfilters() as $f) {
                            $tmpArr = explode("=", $f);
                            $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
                        }
                    }
                    $query = $this->db->select()
                        ->from($b->getTableName())
                        ->where($localFilters)
                        ->limit(1)
                        ->get_compiled_select();
                    $subtmp = $this->db->query("{$query} FOR UPDATE")->row_array();
                    cekMerah($subtmp);
                    if ((sizeof($subtmp) == 0) || ($subtmp == NULL)) {
                        cekMErah($pSpec["kategori_id"]);
//                        $subtmp=array();
                        if (isset($pSpec["kategori_id"]) && $pSpec["kategori_id"] == "4") {
                            $subtmp = array(
                                "id" => "0",
                                "last_update" => "",
                                "jenis" => $pSpec["kategori_id"],
                                "produk_id" => $pSpec["extern_id"],
                                "nama" => $pSpec["extern_nama"],
                                "jml" => $pSpec["produk_qty"],
                                "hpp" => 0,
                                "harga_jasa" => $pSpec["harga"],
                                "jml_nilai" => $pSpec["harga"] * $pSpec["produk_qty"],
                                "cabang_id" => $pSpec["cabang_id"],
                                "gudang_id" => $pSpec["gudang_id"],
                                "cache_id" => "0",
                                "trash" => "0",
                                "transaksi_id" => "0",
                                "transaksi_jenis" => "",
                                "dtime_last" => "",
                                "jml_ot" => "0",
                                "jml_nilai_ot" => "",
                                "unit_ot" => "",
                                "fulldate" => "",
                                "hpp_riil" => "0",
                                "jml_nilai_riil" => 0,
                                "ppv_riil" => 0,
                                "ppv_nilai_riil" => 0,
                                "ppn_in_nilai" => 0,
                                "ppn_in" => 0,
                                "suppliers_id" => "",
                                "suppliers_nama" => "",
                                "hpp_nppv" => 0,
                                "jml_nilai_nppv" => 0,
                                "produk_jenis_id" => 0,
                                "produk_jenis" => 0,
                            );
//                            matiHere("ada kategori harusnya lolos");
                        }
                        else {
                            cekHitam("kategori id :: ".$pSpec["kategori_id"]);
                        $msg = "Stok " . $pSpec['extern_nama'] . " tidak cukup. Silahkan diperiksa stok anda.".__LINE__." ".__FUNCTION__;
                            matiHere($msg);
                        die(lgShowAlertBiru($msg));
                    }
                    }
                    else{
                        cekOrange($this->db->last_query());

                        cekHitam("else preproc item");
//                        matiHere();
                    }
                    if(count($subtmp)>0){

                    }
                    else{
//                        arrPrint($pSpec);
//                        matiHere();
                    }
                    $tmp[] = (object)$subtmp;
                }
            }
//

            $updatePairs = array();
            if (sizeof($tmp) > 0) {
                $patchers = array();
                foreach ($tmp as $row) {

//                    arrPrintWebs($row);
                    if($row->jenis=="4"){
//                        matiHere();
                    }
//                    matiHere();
                    foreach ($this->resultParams as $gateName => $paramSpec) {

                        foreach ($paramSpec as $key => $val) {
                            $patchers[$gateName][$row->produk_id][$key] = $row->$val;
                        }
                    }

                    //==update yg sesuai
                    if (array_key_exists($row->produk_id, $needles)) {
                        $newJml = $row->jml - $needles[$row->produk_id];
                        if ($newJml < 0) {
                            $msg = "Stok " . $row->nama . " tidak cukup. Silahkan diperiksa stok anda.";
                            matiHere($msg);
//                            die(lgShowAlertBiru($msg));
                        }
//                        if ($row->jenis == "produk") {
                        $updatePairs[] = array(
                            "id" => $row->id,
                            "produk_id" => $row->produk_id,
                            "jml" => ($row->jml - $needles[$row->produk_id]),
                            "jml_nilai" => ($row->jml_nilai - ($row->hpp * $needles[$row->produk_id])),
                            "jml_nilai_riil" => ($row->jml_nilai_riil - ($row->hpp_riil * $needles[$row->produk_id])),
                            "ppv_nilai_riil" => ($row->ppv_nilai_riil - ($row->ppv_riil * $needles[$row->produk_id])),

                            "ppn_in_nilai" => ($row->ppn_in_nilai - ($row->ppn_in * $needles[$row->produk_id])),
                            "kategori_id"=>isset($row->jenis) ? $row->jenis:"produk",
                        );
//                        }
                    }
                }

                if (sizeof($updatePairs) > 0) {
                    foreach ($updatePairs as $upSpec) {
                        $katID = $upSpec["kategori_id"];
//                        arrprint($upSpec);
                        if($katID == "4"){
                            //tidak usah diupdate bro memang gak ada fifonya untuk jasa
//                            matiHere(__LINE__);
                        }
                        else{
                        $updateData = $upSpec;
                        unset($updateData["id"]);
                            unset($updateData["kategori_id"]);
                        $b = new MdlFifoAverage();
                        $b->updateData(array("id" => $upSpec['id']), $updateData);
                        cekMerah($this->db->last_query());
                    }
                    }
                }

                $this->result = $patchers;

            }
            else {
//                arrprintWebs($inParams);
//                matiHere();
                $this->result = array();
            }

            if (sizeof($updatePairs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
    }

    public function exec()
    {
        return $this->result;
    }
}