<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */


class Harga
{
    protected $harga;
    protected $event_dis;
    protected $toko_id;
    protected $cabang_id;
    protected $customer_level_condite;

    public function getCustomerLevelCondite()
    {
        return $this->customer_level_condite;
    }

    public function setCustomerLevelCondite($customer_level_condite)
    {
        $this->customer_level_condite = $customer_level_condite;
    }

    public function getCabangId()
    {
        return $this->cabang_id;
    }

    public function setCabangId($cabang_id)
    {
        $this->cabang_id = $cabang_id;
    }

    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    public function getEventDis()
    {
        return $this->event_dis;
    }

    public function setEventDis($event_dis)
    {
        $this->event_dis = $event_dis;
    }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }

    public function HrgJual()
    {
        $prod_hargas = $this->HargaProduk();

        $prod_harga = array();
        foreach ($prod_hargas as $produk_id => $prod_harga_0) {
            foreach ($prod_harga_0 as $item) {
                $prod_harga[$produk_id] = $item->nilai * 1;
            }

        }
        // arrPrint($prod_harga);

        return $prod_harga;
    }

    public function HrgBeli()
    {
        $prod_hargas = $this->HargaProduk();
        arrPrint($prod_hargas);
    }

    private function HargaProduk()
    {
        $toko_id = isset($this->toko_id) ? $this->toko_id : matiHere("toko_id harap di set");
        $cabang_id = isset($this->cabang_id) ? $this->cabang_id : matiHere("cabang_id harap diset");
        /*-----------produk harga------------*/
        $this->CI->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId($toko_id);
        $hp->setCabangId($cabang_id);
        $condites = array(
          "jenis_value" => "harga_list"
        );
        $this->CI->db->where($condites);
        $prod_hargas = $hp->callSpecs();
        // showLast_query("kuning");

        return $prod_hargas;
    }
}