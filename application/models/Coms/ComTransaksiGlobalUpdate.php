<?php

/**
 * Class ComTransaksiUpdate
 * update tranaksi
 * yang akan diupdate letakan di loop dengan foramt [key => value] seperti loop yang lainnya
 * jalan di items karena butuh looping multi transaksi yang diupdate
 */
class ComTransaksiGlobalUpdate extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;

    public function __construct()
    {
        parent::__construct();
    }

    private $outFields = array( // dari tabel rek_cache
        "id",
        "cabang_id",
        "cabang_nama",
//        "extern_id",
//        "extern_nama",
//        "jenis",
//
    );

    public function pair($inParams)
    {
//        $this->inParams = $inParams;

//        if(count($inParams)>0){
//            arrPrint($inParams);
//            matiHEre(__LINE__);
//        }
        foreach ($inParams as $this->inParams){
//            arrprint($this->inParams);
            $update = $this->inParams["loop"];
            $_preValue = $this->cekPreValue($this->inParams['static']['cabang_id'], $this->inParams['static']['id']);
//            arrprint($_preValue);
            $this->load->model("MdlTransaksi");
            $l = new MdlTransaksi();
            if($_preValue!=null){
                $where = array(
                    "id"=>$_preValue,
                );
                $l->setFilters(array());
                $insertID[]=$l->updateData($where,$update) or die();
                cekLime($this->db->last_query());

            }
            else{
                matiHere("gagal memperbaharui transaksi. Silahkan hubungi admin untuk dilakukan investigasi");
            }

        }
//        matiHere();


        if (sizeof($insertID) > 0) {
            return true;
        }
        else {
            return false;
        }
    }


    private function cekPreValue($cabang_id, $transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();


        $l->addFilter("id='$transaksiID'");
//        $l->addFilter("jenis='$jenis'");
//        $l->addFilter("cabang_id='$cabang_id'");

        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->id;
            }
        }
        else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }

    public function exec()
    {
        return true;

    }
}