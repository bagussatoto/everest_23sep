<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Chepy
 * Date: 12/12/22
 * Time: 11:56 AM
 */


class AndroidConnect {

    protected $url = array(
        "writeDataLogistik"     => ADM_DOMAIN . "/run_sakura_apk/eusvc/Entries/writeDataLogistik",
        "writeDataCollector"    => ADM_DOMAIN . "/run_sakura_apk/eusvc/Entries/writeDataCollector",
    );

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    public function createLog($param){
        $this->CI->load->model("Mdls/MdlAndroidConnect");
        $log = new MdlAndroidConnect();

    }

    public function writeDataCollector($param,$tmpTransaksiID,$stat=999){

        $arrProduk = $param['sessionData']['items'];
        $this->CI->load->library("Curl");
        $curl = New Curl();

        $response = array(
            "status"            => $stat,
            "toko_id"           => $param['sessionData']['main']['tokoID'],
            "validGrandTotal"   => $param['sessionData']['main']['piutang_dagang'],
            "nomor_referensi"   => $param['sessionData']['main']['nomer'],
            "referensi_id"      => $tmpTransaksiID,
            "oleh_id"           => $param['sessionData']['main']['olehID'],
            "oleh_nama"         => $param['sessionData']['main']['olehName'],
            "datetime"          => date("Y-m-d H:i:s"),
            "customer_id"       => $param['sessionData']['main']['customerDetails'],
            "customer_nama"     => $param['sessionData']['main']['customerDetails__nama'],
            "customer_alamat"   => $param['sessionData']['main']['customerDetails__alamat_1'],
            "customer_telp"     => $param['sessionData']['main']['customerDetails__tlp_1'],
            "produk_blob"       => base64_encode(json_encode($arrProduk)), //jadikan blob JS ( pake base64_encode(json_encode($produk)) ) agar bisa di urai di javascript
        );

        $verb = "POST";
        $result = $curl->_simple_call($verb, $this->url['writeDataCollector'], $response);

    }

    public function writeDataLogistik($param,$tmpTransaksiID,$stat=999){

        $arrProduk = $param['sessionData']['items'];
        $this->CI->load->library("Curl");
        $curl = New Curl();

        $response = array(
            "status" => $stat,
            "toko_id" => $this->CI->session->login['toko_id'],
            "nomor_referensi" => $param["sessionData"]['tableIn_master']['nomer'],
            "nomer" => $param["sessionData"]['main']['nomer'],
            "oleh_id" => $this->CI->session->login['id'],
            "oleh_nama" => $this->CI->session->login['nama'],
            "seller_id" => $param["sessionData"]['main']['logistikInternal'],
            "datetime" => date("Y-m-d H:i:s"),
            "seller_nama" => $param["sessionData"]['main']['logistikInternal__nama'],
            "customer_id" => $param["sessionData"]['main']['customerDetails'],
            "customer_nama" => $param["sessionData"]['main']['customerDetails__nama'],
            "customer_alamat" => $param["sessionData"]['main']['customerDetails__alamat_1'],
            "customer_telp" => $param["sessionData"]['main']['customerDetails__tlp_1'],
            "produk_blob" => base64_encode(json_encode($arrProduk)), //jadikan blob JS ( pake base64_encode(json_encode($produk)) ) agar bisa di urai di javascript
        );

        $verb = "POST";
        $result = $curl->_simple_call($verb, $this->url['writeDataLogistik'], $response);

    }



}