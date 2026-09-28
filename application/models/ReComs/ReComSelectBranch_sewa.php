<?php

class ReComSelectBranch_sewa extends MdlMother
{
    private $jenisTr;


    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }


    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);

    }

    public function pair()
    {
        $cCode = "_TR_" . $this->jenisTr;
        $key = isset($_GET['key']) ? $_GET['key'] : 0;
        $gudSpec = getDefaultWarehouseID($key);
        if (sizeof($_SESSION[$cCode]['main']) > 0) {
            if($key > 0){
                    $_SESSION[$cCode]['main']['branchTarget__nilai_persediaan'] = isset($_SESSION[$cCode]['main']['nilai_persediaan']) ? $_SESSION[$cCode]['main']['nilai_persediaan'] : 0;
                    $_SESSION[$cCode]['main']['branchTarget__placeID'] = $key;
                    $_SESSION[$cCode]['main']['branchTarget__gudangID'] = $gudSpec['gudang_id'];
                if( isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items'])>0 ){
                    foreach($_SESSION[$cCode]['items'] as $id_produk => $produkData){
                            $_SESSION[$cCode]['items'][$id_produk]['branchTarget__harga_dipakai'] =$_SESSION[$cCode]['items'][$id_produk]['harga_dipakai'];
                            $_SESSION[$cCode]['items'][$id_produk]['branchTarget__placeID'] = $key;
                            $_SESSION[$cCode]['items'][$id_produk]['branchTarget__gudangID'] = $gudSpec['gudang_id'];
                    }
                }
                if( isset($_SESSION[$cCode]['items2_sum']) && sizeof($_SESSION[$cCode]['items'])>0 ){
//                    foreach($_SESSION[$cCode]['items'] as $id_produk => $produkData){
//                        $_SESSION[$cCode]['items'][$id_produk]['branchTarget__harga_dipakai'] =$_SESSION[$cCode]['main']['nilai_persediaan'];
//                        $_SESSION[$cCode]['items'][$id_produk]['branchTarget__placeID'] = $key;
//                        $_SESSION[$cCode]['items'][$id_produk]['branchTarget__gudangID'] = $gudSpec['gudang_id'];
//                    }
                }
            }
            else{
                unset($_SESSION[$cCode]['main']['branchTarget__nilai_persediaan']);
                unset($_SESSION[$cCode]['main']['branchTarget__placeID']);
                unset($_SESSION[$cCode]['main']['branchTarget__gudangID']);

                if( isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items'])>0 ){
                    foreach($_SESSION[$cCode]['items'] as $id_produk => $produkData){
                        unset($_SESSION[$cCode]['items'][$id_produk]['branchTarget__harga_dipakai']);
                        unset($_SESSION[$cCode]['items'][$id_produk]['branchTarget__placeID']);
                        unset($_SESSION[$cCode]['items'][$id_produk]['branchTarget__gudangID']);
                    }
                }
            }
        }
        return true;
    }

    public function exec()
    {
        return true;
    }
}