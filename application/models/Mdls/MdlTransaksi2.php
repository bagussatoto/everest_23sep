<?php

/*
 * biar bisa load MdlTransaksi oleh receiptElement
 */
require APPPATH . '/models/MdlTransaksi.php';//just add this line and keep rest

class MdlTransaksi2 extends MdlTransaksi
{
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nomer" => "nomer",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
    }




}