<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */


class CounterNumber
{

    protected $cCode;
    protected $jenisTr;
    protected $mainGate;
    protected $itemsGate;
    protected $items2_sumGate;
    protected $transaksiGate;
    protected $modulDefine;
    protected $rekening;


    //region getter and setter

    public function getRekening()
    {
        return $this->rekening;
    }

    public function setRekening($rekening)
    {
        $this->rekening = $rekening;
    }

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {

        $this->jenisTr = $jenisTr;


    }

    public function getCCode()
    {
        return $this->cCode;
    }

    public function setCCode($cCode)
    {
        $this->cCode = $cCode;
    }

    public function getMainGate()
    {
        return $this->mainGate;
    }

    public function setMainGate($mainGate)
    {
        $this->mainGate = $mainGate;
    }

    public function getItemsGate()
    {
        return $this->itemsGate;
    }

    public function setItemsGate($itemsGate)
    {
        $this->itemsGate = $itemsGate;
    }

    public function getTransaksiGate()
    {
        return $this->transaksiGate;
    }

    public function setTransaksiGate($transaksiGate)
    {
        $this->transaksiGate = $transaksiGate;
    }

    public function getModulDefine()
    {
        return $this->modulDefine;
    }

    public function setModulDefine($modulDefine)
    {
        $this->modulDefine = $modulDefine;
    }

    public function getItems2SumGate()
    {
        return $this->items2_sumGate;
    }

    public function setItems2SumGate($items2_sumGate)
    {
        $this->items2_sumGate = $items2_sumGate;
    }


    //endregion


    //----------------------
    public function __construct()
    {
//         parent::__construct();
        $this->CI =& get_instance();
        $this->CI->load->model("CustomCounter");
//        $masterConfigUi = $this->CI->config->item("heTransaksi_ui");

        $this->counterDefine = array(

//                "company",                           // 1
//                "company|jenis",                     // 2
//                "company|suppliers_id",              // 3
//                "company|customers_id",              // 4
//                "company|oleh_id",                   // 5
//                "company|seller_id",                   // 5
//                "company|cabang_id",                 // 6
//                "company|cabang2_id",                // 7
//                "company|gudang_id",                 // 8
//                "company|gudang2_id",                // 9
//                "company|jenis|suppliers_id",        // 10
//                "company|jenis|customers_id",        // 11
//                "company|jenis|oleh_id",             // 12
//                "company|jenis|seller_id",             // 13
//                "company|jenis|cabang_id",           // 14
//                "company|jenis|cabang2_id",          // 15
//                "company|jenis|gudang_id",           // 16
//                "company|jenis|gudang2_id",          // 17
//                "company|jenis|cabang_id|cabang2_id", // 18
//                "company|jenis|cabang_id|customers_id", // 18
//                "company|jenis|cabang_id|oleh_id",      // 19
//                "company|jenis|cabang_id|seller_id",    // 20
//                "company|jenis|cabang_id|gudang_id",    // 21
//                "company|jenis|cabang_id|gudang2_id",   // 22
//                "company|jenis|cabang_id|suppliers_id",   // 23
            //-----------------------

//                "company",                           // 1
//                "company|jenisTr",                     // 2
//                "company|jenisTrMaster",                     // 2
//                "company|stepCode",                     // 2
//                "company|supplierID",              // 3
//                "company|customerID",              // 4
//                "company|olehID",                   // 5
//                "company|sellerID",                   // 5
//                "company|cabangID",                 // 6
//                "company|cabang2ID",                // 7
//                "company|gudangID",                 // 8
//                "company|gudang2ID",                // 9
//                "company|modul",                // 9
//                "company|subModul",                // 9

//                "company|jenisTr|jenisTrMaster",        // 10
//                "company|jenisTr|stepCode",        // 10
//                "company|jenisTr|supplierID",        // 10
//                "company|jenisTr|customerID",        // 11
//                "company|jenisTr|olehID",             // 12
//                "company|jenisTr|sellerID",             // 13
//                "company|jenisTr|cabangID",           // 14
//                "company|jenisTr|cabang2ID",          // 15
//                "company|jenisTr|gudangID",           // 16
//                "company|jenisTr|gudang2ID",          // 17
//                "company|jenisTr|modul",          // 17
//                "company|jenisTr|subModul",          // 17
//
//                "company|jenisTr|cabangID|jenisTrMaster", // 18
//                "company|jenisTr|cabangID|stepCode", // 18
//                "company|jenisTr|cabangID|cabang2ID", // 18
//                "company|jenisTr|cabangID|customerID", // 18
//                "company|jenisTr|cabangID|olehID",      // 19
//                "company|jenisTr|cabangID|sellerID",    // 20
//                "company|jenisTr|cabangID|gudangID",    // 21
//                "company|jenisTr|cabangID|gudang2ID",   // 22
//                "company|jenisTr|cabangID|supplierID",   // 23
//                "company|jenisTr|cabangID|modul",   // 23
//                "company|jenisTr|cabangID|subModul",   // 23
//
//                "company|jenisTr|cabangID|stepCode|cabang2ID", // 18
//                "company|jenisTr|cabangID|stepCode|customerID", // 18
//                "company|jenisTr|cabangID|stepCode|olehID",      // 19
//                "company|jenisTr|cabangID|stepCode|sellerID",    // 20
//                "company|jenisTr|cabangID|stepCode|gudangID",    // 21
//                "company|jenisTr|cabangID|stepCode|gudang2ID",   // 22
//                "company|jenisTr|cabangID|stepCode|supplierID",   // 23
//                "company|jenisTr|cabangID|stepCode|modul",   // 23
//                "company|jenisTr|cabangID|stepCode|subModul",   // 23

            "company",                           // 1
            "company|jenisTr",                     // 2
            "company|jenisTrMaster",                     // 2
            "company|stepCode",                     // 2
            "company|supplierID",              // 3
            "company|customerID",              // 4
            "company|olehID",                   // 5
            "company|sellerID",                   // 5
            "company|cabangID",                 // 6
            "company|cabang2ID",                // 7
            "company|gudangID",                 // 8
            "company|gudang2ID",                // 9
            "company|modul",                // 9
            "company|subModul",                // 9

            "company|cabangID|jenisTr",                     // 2
            "company|cabangID|jenisTrMaster",                     // 2
            "company|cabangID|stepCode",                     // 2
            "company|cabangID|supplierID",              // 3
            "company|cabangID|customerID",              // 4
            "company|cabangID|olehID",                   // 5
            "company|cabangID|sellerID",                   // 5
            "company|cabangID|cabangID",                 // 6
            "company|cabangID|cabang2ID",                // 7
            "company|cabangID|gudangID",                 // 8
            "company|cabangID|gudang2ID",                // 9
            "company|cabangID|modul",                // 9
            "company|cabangID|subModul",                // 9

            "company|cabangID|modul|jenisTr",                     // 2
            "company|cabangID|modul|jenisTrMaster",                     // 2
            "company|cabangID|modul|stepCode",                     // 2
            "company|cabangID|modul|supplierID",              // 3
            "company|cabangID|modul|customerID",              // 4
            "company|cabangID|modul|olehID",                   // 5
            "company|cabangID|modul|sellerID",                   // 5
            "company|cabangID|modul|cabangID",                 // 6
            "company|cabangID|modul|cabang2ID",                // 7
            "company|cabangID|modul|gudangID",                 // 8
            "company|cabangID|modul|gudang2ID",                // 9
            "company|cabangID|modul|subModul",                // 9

            "company|cabangID|modul|subModul|jenisTr",                     // 2
            "company|cabangID|modul|subModul|jenisTrMaster",                     // 2
            "company|cabangID|modul|subModul|stepCode",                     // 2
            "company|cabangID|modul|subModul|supplierID",              // 3
            "company|cabangID|modul|subModul|customerID",              // 4
            "company|cabangID|modul|subModul|olehID",                   // 5
            "company|cabangID|modul|subModul|sellerID",                   // 5
            "company|cabangID|modul|subModul|cabangID",                 // 6
            "company|cabangID|modul|subModul|cabang2ID",                // 7
            "company|cabangID|modul|subModul|gudangID",                 // 8
            "company|cabangID|modul|subModul|gudang2ID",                // 9

            "company|cabangID|modul|subModul|jenisTr|jenisTrMaster",                     // 2
            "company|cabangID|modul|subModul|jenisTr|stepCode",                     // 2
            "company|cabangID|modul|subModul|jenisTr|supplierID",              // 3
            "company|cabangID|modul|subModul|jenisTr|customerID",              // 4
            "company|cabangID|modul|subModul|jenisTr|olehID",                   // 5
            "company|cabangID|modul|subModul|jenisTr|sellerID",                   // 5
            "company|cabangID|modul|subModul|jenisTr|cabangID",                 // 6
            "company|cabangID|modul|subModul|jenisTr|cabang2ID",                // 7
            "company|cabangID|modul|subModul|jenisTr|gudangID",                 // 8
            "company|cabangID|modul|subModul|jenisTr|gudang2ID",                // 9

            "company|cabangID|modul|subModul|jenisTr|stepCode|jenisTrMaster",                     // 2
            "company|cabangID|modul|subModul|jenisTr|stepCode|supplierID",              // 3
            "company|cabangID|modul|subModul|jenisTr|stepCode|customerID",              // 4
            "company|cabangID|modul|subModul|jenisTr|stepCode|olehID",                   // 5
            "company|cabangID|modul|subModul|jenisTr|stepCode|sellerID",                   // 5
            "company|cabangID|modul|subModul|jenisTr|stepCode|cabangID",                 // 6
            "company|cabangID|modul|subModul|jenisTr|stepCode|cabang2ID",                // 7
            "company|cabangID|modul|subModul|jenisTr|stepCode|gudangID",                 // 8
            "company|cabangID|modul|subModul|jenisTr|stepCode|gudang2ID",                // 9

        );
        $this->counterDefineItems = array(
//            "company|item_id",           // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|jenis",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
            "company|coa_code",                           // 1
            "company|coa_code|jenisTr",                     // 2
            "company|coa_code|jenisTrMaster",                     // 2
            "company|coa_code|stepCode",                     // 2
            "company|coa_code|supplierID",              // 3
            "company|coa_code|customerID",              // 4
            "company|coa_code|olehID",                   // 5
            "company|coa_code|sellerID",                   // 5
            "company|coa_code|cabangID",                 // 6
            "company|coa_code|cabang2ID",                // 7
            "company|coa_code|gudangID",                 // 8
            "company|coa_code|gudang2ID",                // 9
            "company|coa_code|modul",                // 9
            "company|coa_code|subModul",                // 9

            "company|coa_code|cabangID|jenisTr",                     // 2
            "company|coa_code|cabangID|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|stepCode",                     // 2
            "company|coa_code|cabangID|supplierID",              // 3
            "company|coa_code|cabangID|customerID",              // 4
            "company|coa_code|cabangID|olehID",                   // 5
            "company|coa_code|cabangID|sellerID",                   // 5
            "company|coa_code|cabangID|cabangID",                 // 6
            "company|coa_code|cabangID|cabang2ID",                // 7
            "company|coa_code|cabangID|gudangID",                 // 8
            "company|coa_code|cabangID|gudang2ID",                // 9
            "company|coa_code|cabangID|modul",                // 9
            "company|coa_code|cabangID|subModul",                // 9

            "company|coa_code|cabangID|modul|jenisTr",                     // 2
            "company|coa_code|cabangID|modul|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|modul|stepCode",                     // 2
            "company|coa_code|cabangID|modul|supplierID",              // 3
            "company|coa_code|cabangID|modul|customerID",              // 4
            "company|coa_code|cabangID|modul|olehID",                   // 5
            "company|coa_code|cabangID|modul|sellerID",                   // 5
            "company|coa_code|cabangID|modul|cabangID",                 // 6
            "company|coa_code|cabangID|modul|cabang2ID",                // 7
            "company|coa_code|cabangID|modul|gudangID",                 // 8
            "company|coa_code|cabangID|modul|gudang2ID",                // 9
            "company|coa_code|cabangID|modul|subModul",                // 9

            "company|coa_code|cabangID|modul|subModul|jenisTr",                     // 2
            "company|coa_code|cabangID|modul|subModul|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|modul|subModul|stepCode",                     // 2
            "company|coa_code|cabangID|modul|subModul|supplierID",              // 3
            "company|coa_code|cabangID|modul|subModul|customerID",              // 4
            "company|coa_code|cabangID|modul|subModul|olehID",                   // 5
            "company|coa_code|cabangID|modul|subModul|sellerID",                   // 5
            "company|coa_code|cabangID|modul|subModul|cabangID",                 // 6
            "company|coa_code|cabangID|modul|subModul|cabang2ID",                // 7
            "company|coa_code|cabangID|modul|subModul|gudangID",                 // 8
            "company|coa_code|cabangID|modul|subModul|gudang2ID",                // 9

            "company|coa_code|cabangID|modul|subModul|jenisTr|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode",                     // 2
            "company|coa_code|cabangID|modul|subModul|jenisTr|supplierID",              // 3
            "company|coa_code|cabangID|modul|subModul|jenisTr|customerID",              // 4
            "company|coa_code|cabangID|modul|subModul|jenisTr|olehID",                   // 5
            "company|coa_code|cabangID|modul|subModul|jenisTr|sellerID",                   // 5
            "company|coa_code|cabangID|modul|subModul|jenisTr|cabangID",                 // 6
            "company|coa_code|cabangID|modul|subModul|jenisTr|cabang2ID",                // 7
            "company|coa_code|cabangID|modul|subModul|jenisTr|gudangID",                 // 8
            "company|coa_code|cabangID|modul|subModul|jenisTr|gudang2ID",                // 9

            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|supplierID",              // 3
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|customerID",              // 4
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|olehID",                   // 5
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|sellerID",                   // 5
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|cabangID",                 // 6
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|cabang2ID",                // 7
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|gudangID",                 // 8
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|gudang2ID",                // 9

        );
        $this->counterDefineItems2_sum = array(
//            "company|item_id",           // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|jenis",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|cabang_id|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|gudang_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|gudang2_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|customers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|suppliers_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|seller_id",     // item bisa produk, rakitan, supplies, biaya, dll
//            "company|item_id|jenis|cabang_id|oleh_id",     // item bisa produk, rakitan, supplies, biaya, dll
            "company|coa_code",                           // 1
            "company|coa_code|jenisTr",                     // 2
            "company|coa_code|jenisTrMaster",                     // 2
            "company|coa_code|stepCode",                     // 2
            "company|coa_code|supplierID",              // 3
            "company|coa_code|customerID",              // 4
            "company|coa_code|olehID",                   // 5
            "company|coa_code|sellerID",                   // 5
            "company|coa_code|cabangID",                 // 6
            "company|coa_code|cabang2ID",                // 7
            "company|coa_code|gudangID",                 // 8
            "company|coa_code|gudang2ID",                // 9
            "company|coa_code|modul",                // 9
            "company|coa_code|subModul",                // 9

            "company|coa_code|cabangID|jenisTr",                     // 2
            "company|coa_code|cabangID|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|stepCode",                     // 2
            "company|coa_code|cabangID|supplierID",              // 3
            "company|coa_code|cabangID|customerID",              // 4
            "company|coa_code|cabangID|olehID",                   // 5
            "company|coa_code|cabangID|sellerID",                   // 5
            "company|coa_code|cabangID|cabangID",                 // 6
            "company|coa_code|cabangID|cabang2ID",                // 7
            "company|coa_code|cabangID|gudangID",                 // 8
            "company|coa_code|cabangID|gudang2ID",                // 9
            "company|coa_code|cabangID|modul",                // 9
            "company|coa_code|cabangID|subModul",                // 9

            "company|coa_code|cabangID|modul|jenisTr",                     // 2
            "company|coa_code|cabangID|modul|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|modul|stepCode",                     // 2
            "company|coa_code|cabangID|modul|supplierID",              // 3
            "company|coa_code|cabangID|modul|customerID",              // 4
            "company|coa_code|cabangID|modul|olehID",                   // 5
            "company|coa_code|cabangID|modul|sellerID",                   // 5
            "company|coa_code|cabangID|modul|cabangID",                 // 6
            "company|coa_code|cabangID|modul|cabang2ID",                // 7
            "company|coa_code|cabangID|modul|gudangID",                 // 8
            "company|coa_code|cabangID|modul|gudang2ID",                // 9
            "company|coa_code|cabangID|modul|subModul",                // 9

            "company|coa_code|cabangID|modul|subModul|jenisTr",                     // 2
            "company|coa_code|cabangID|modul|subModul|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|modul|subModul|stepCode",                     // 2
            "company|coa_code|cabangID|modul|subModul|supplierID",              // 3
            "company|coa_code|cabangID|modul|subModul|customerID",              // 4
            "company|coa_code|cabangID|modul|subModul|olehID",                   // 5
            "company|coa_code|cabangID|modul|subModul|sellerID",                   // 5
            "company|coa_code|cabangID|modul|subModul|cabangID",                 // 6
            "company|coa_code|cabangID|modul|subModul|cabang2ID",                // 7
            "company|coa_code|cabangID|modul|subModul|gudangID",                 // 8
            "company|coa_code|cabangID|modul|subModul|gudang2ID",                // 9

            "company|coa_code|cabangID|modul|subModul|jenisTr|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode",                     // 2
            "company|coa_code|cabangID|modul|subModul|jenisTr|supplierID",              // 3
            "company|coa_code|cabangID|modul|subModul|jenisTr|customerID",              // 4
            "company|coa_code|cabangID|modul|subModul|jenisTr|olehID",                   // 5
            "company|coa_code|cabangID|modul|subModul|jenisTr|sellerID",                   // 5
            "company|coa_code|cabangID|modul|subModul|jenisTr|cabangID",                 // 6
            "company|coa_code|cabangID|modul|subModul|jenisTr|cabang2ID",                // 7
            "company|coa_code|cabangID|modul|subModul|jenisTr|gudangID",                 // 8
            "company|coa_code|cabangID|modul|subModul|jenisTr|gudang2ID",                // 9

            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|jenisTrMaster",                     // 2
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|supplierID",              // 3
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|customerID",              // 4
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|olehID",                   // 5
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|sellerID",                   // 5
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|cabangID",                 // 6
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|cabang2ID",                // 7
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|gudangID",                 // 8
            "company|coa_code|cabangID|modul|subModul|jenisTr|stepCode|gudang2ID",                // 9

        );

        $this->counterDefineRekening = array(

            "company|rekening",                     // 2
            "company|rekening|jenisTr",                     // 2
            "company|rekening|jenisTrMaster",                     // 2
            "company|rekening|stepCode",                     // 2
            "company|rekening|supplierID",              // 3
            "company|rekening|customerID",              // 4
            "company|rekening|olehID",                   // 5
            "company|rekening|sellerID",                   // 5
            "company|rekening|cabangID",                 // 6
            "company|rekening|cabang2ID",                // 7
            "company|rekening|gudangID",                 // 8
            "company|rekening|gudang2ID",                // 9
            "company|rekening|modul",                // 9
            "company|rekening|subModul",                // 9

            "company|rekening|cabangID|jenisTr",                     // 2
            "company|rekening|cabangID|jenisTrMaster",                     // 2
            "company|rekening|cabangID|stepCode",                     // 2
            "company|rekening|cabangID|supplierID",              // 3
            "company|rekening|cabangID|customerID",              // 4
            "company|rekening|cabangID|olehID",                   // 5
            "company|rekening|cabangID|sellerID",                   // 5
            "company|rekening|cabangID|cabangID",                 // 6
            "company|rekening|cabangID|cabang2ID",                // 7
            "company|rekening|cabangID|gudangID",                 // 8
            "company|rekening|cabangID|gudang2ID",                // 9
            "company|rekening|cabangID|modul",                // 9
            "company|rekening|cabangID|subModul",                // 9
        );
    }
    //----------------------


    //-------------------
    public function getModulCounter()
    {
        // modul apa?? modul apa?? tembak dari script dulu,
        // mestinya pakai setting by database atau sudah ready di gerbang masing-masing
        $modulDefine = array();
        switch ($this->jenisTr) {

            //region folder pembelian
            case "461":
            case "961":
            case "1961":
            case "463":
            case "3463":
            case "466":
            case "967":
            case "1967":
            case "1466":
            case "9967":
            case "19967":
                $modulDefine = array(
                    "modul" => "110",// pembelian
                    "subModul" => "111",// lokal
                );
                break;
            case "460":
            case "960":
            case "1960":
            case "468":
            case "968":
            case "1463":
                $modulDefine = array(
                    "modul" => "110",// pembelian
                    "subModul" => "112",// import
                );
                break;
            //endregion
            //region folder penjualan
            case "582":
            case "1582":
            case "5822":
            case "5823":
            case "15822":
            case "66771":
            case "66773":
                $modulDefine = array(
                    "modul" => "120",// penjualan
                    "subModul" => "121",// lokal
                );
                break;
            case "982":
            case "9822":
            case "1982":
            case "19822":
                $modulDefine = array(
                    "modul" => "120",// penjualan
                    "subModul" => "121",// lokal
                );
                break;
            case "382":
                $modulDefine = array(
                    "modul" => "120",// penjualan
                    "subModul" => "122",// eksport
                );
                break;
            case "584":
                $modulDefine = array(
                    "modul" => "",
                    "subModul" => "",
                );
                break;
            case "580":
                $modulDefine = array(
                    "modul" => "120",// penjualan
                    "subModul" => "123",// reseller
                );
                break;
            //endregion
            //region folder adjustment
            case "999":
            case "999_0":
            case "999_1":
            case "9993":
            case "888_1":
            case "888_2":
                $modulDefine = array(
                    "modul" => "270",
                    "subModul" => "271",
                );
                break;
            case "666_1":
            case "666_2":
                $modulDefine = array(
                    "modul" => "270",
                    "subModul" => "272",
                );
                break;
            case "777_1":
            case "777_2":
            case "777_3":
                $modulDefine = array(
                    "modul" => "270",
                    "subModul" => "273",
                );
                break;
            //endregion
            //region folder banking
            case "756":
            case "757":
            case "1757":
                $modulDefine = array(
                    "modul" => "260",
                    "subModul" => "261",// kas
                );
                break;
            case "4470":
            case "4970":
                $modulDefine = array(
                    "modul" => "260",
                    "subModul" => "262",// rekening koran
                );
                break;
            case "444":
            case "446":
            case "447":
                $modulDefine = array(
                    "modul" => "260",
                    "subModul" => "263",// tambah hutang bank
                );
                break;
            case "445":
                $modulDefine = array(
                    "modul" => "260",
                    "subModul" => "264",// tambah modal pemegang saham
                );
                break;
            //endregion
            //region folder biaya
            case "7674":
            case "677":
            case "1677":
            case "2677":
            case "9982":
            case "9985":
            case "6677":
            case "66772":
            case "6678":
            case "16678":
            case "16677":
            case "1676":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "131",// biaya usaha
                );
                break;
            case "675":
            case "1675":
            case "2675":
            case "9983":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "132",// biaya umum
                );
                break;
            case "676":
            case "2676":
            case "9984":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "133",// biaya produksi
                );
                break;
            case "1674":
            case "11674":
            case "21674":
            case "3674":
            case "3675":
            case "2674":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "134",// biaya gaji
                );
                break;
            case "762":
            case "2762":
            case "7762":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "135",// supplies
                );
                break;
            case "4675":
            case "9922":
            case "673":
            case "4449":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "136",// biaya
                );
                break;
            case "119":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "137",// jasa
                );
                break;
            case "742":
            case "743":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "138",//
                );
                break;
            case "651":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "139",// import
                );
                break;
            //endregion
            //region folder distribusi
            case "583":
            case "585":
            case "5833":
            case "5834":
            case "5855":
            case "5856":
            case "983":
            case "985":
            case "9833":
            case "9834":
            case "9855":
            case "9856":
            case "1983":
            case "1985":
            case "5844":
                $modulDefine = array(
                    "modul" => "130",
                    "subModul" => "131",// biaya usaha
                );
            case "773":
                $modulDefine = array(
                    "modul" => "140",
                    "subModul" => "141",//produk
                );
                break;

            case "3583":
            case "3585":
            case "2983":
            case "2985":
                $modulDefine = array(
                    "modul" => "140",
                    "subModul" => "142",//supplies
                );
                break;

            case "3683":
            case "3685":
            case "9583":
            case "9585":
                $modulDefine = array(
                    "modul" => "140",
                    "subModul" => "143",//rakitan
                );
                break;
            case "3461":
            case "3465":
                $modulDefine = array(
                    "modul" => "140",
                    "subModul" => "144",//jasa, project
                );
                break;
            //endregion
            //region folder kas
            case "464":
            case "465":
            case "4643":
            case "4644":
            case "4656":
            case "488":
            case "486":
            case "7444":
            case "7445":
            case "485":
                $modulDefine = array(
                    "modul" => "150",
                    "subModul" => "151",//uang muka vendor
                );
                break;
            case "4466":
                $modulDefine = array(
                    "modul" => "150",
                    "subModul" => "152",//uang muka vendor, valas
                );
                break;
            case "4464":
            case "4467":
            case "4468":
            case "4469":
            case "700":
            case "7001":
                $modulDefine = array(
                    "modul" => "150",
                    "subModul" => "153",//uang muka konsumen
                );
                break;
            case "759":
            case "758":
                $modulDefine = array(
                    "modul" => "150",
                    "subModul" => "155",//setoran reguler
                );
                break;
            case "7759":
            case "7758":
                $modulDefine = array(
                    "modul" => "150",
                    "subModul" => "156",//setoran uang muka
                );
                break;
            case "9467":
            case "19467":
            case "453":
            case "454":
            case "7468":
            case "7467":
                $modulDefine = array(
                    "modul" => "150",
                    "subModul" => "157",//transfer uang ke cabang
                );
                break;
            //endregion
            //region folder konversi
            case "334":
            case "1334":
            case "1339":
            case "2336":
            case "2337":
            case "3355":
                $modulDefine = array(
                    "modul" => "160",
                    "subModul" => "161",//produk
                );
                break;
            case "1337":
            case "2334":
            case "2335":
            case "335":
            case "1336":
            case "336":
            case "7620":
            case "7622":
                $modulDefine = array(
                    "modul" => "160",
                    "subModul" => "161",//supplies
                );
                break;
            //endregion
            //region folder opname
            case "1119":
            case "4419":
            case "2229":
            case "3339":
            case "5559":
            case "7779":
                $modulDefine = array(
                    "modul" => "170",
                    "subModul" => "171",//produk
                );
                break;
            case "1118":
            case "4418":
            case "2227":
            case "2228":
                $modulDefine = array(
                    "modul" => "170",
                    "subModul" => "172",//supplies
                );
                break;
            //endregion
            //region folder pembatalan
            case "9911":
            case "9912":
                $modulDefine = array(
                    "modul" => "290",
                    "subModul" => "291",//
                );
                break;
            case "9749":
                $modulDefine = array(
                    "modul" => "290",
                    "subModul" => "292",//
                );
                break;
            //endregion
            //region folder pembayaran
            case "483":
            case "487":
            case "489":
            case "4891":
            case "4892":
            case "462":
            case "1462":
            case "477":
            case "1477":
            case "682":
            case "1483":
            case "1488":
            case "4447":
            case "6475":
            case "4448":
            case "1485":
            case "476":
            case "475":
            case "1475":
            case "1487":
            case "4411":
            case "2119":
            case "5684":
            case "115":
            case "114":
            case "1148":
            case "1120":
            case "5682":
            case "1424":
            case "652":
            case "4410":
            case "4440":
            case "4888":
            case "4811":
//            case "3675":
            case "4821":
                $modulDefine = array(
                    "modul" => "180",
                    "subModul" => "181",//
                );
                break;
            //endregion
            //region folder penerimaan
            case "749":
            case "1749":
            case "1748":
            case "2749":
            case "1784":
            case "7488":
            case "74677":
            case "7499":
                $modulDefine = array(
                    "modul" => "190",
                    "subModul" => "191",//
                );
                break;
            //endregion
            //region folder penjualan project
            case "588":
            case "5886":
            case "5887":
            case "5882":
                $modulDefine = array(
                    "modul" => "300",
                    "subModul" => "301",//
                );
                break;
            //endregion
            //region folder pettycast
            case "671":
            case "672":
            case "771":
            case "1671":
            case "1672":
            case "1771":
                $modulDefine = array(
                    "modul" => "200",
                    "subModul" => "201",//pettycast
                );
                break;
            case "770":
            case "970":
                $modulDefine = array(
                    "modul" => "200",
                    "subModul" => "202",//plafon
                );
                break;
            //endregion
            //region folder pindah gudang
            case "587":
            case "687":
            case "1587":
            case "1588":
            case "1687":
            case "2587": //project gudang reguler ke gudang project
            case "2687": //project gudang project ke gudang reguler
            case "5587":
            case "6687":
                $modulDefine = array(
                    "modul" => "210",
                    "subModul" => "211",//pindah gudang
                );
                break;
            //endregion
            //region folder produksi
            case "776":
            case "7778":
                $modulDefine = array(
                    "modul" => "220",
                    "subModul" => "221",//produk
                );
                break;
            //endregion
            //region folder request stok
            case "761":
            case "763":
            case "1763":
            case "11763":
            case "9763":
                $modulDefine = array(
                    "modul" => "230",
                    "subModul" => "231",//supplies
                );
                break;
            //endregion
            //region folder taxes
            case "681":
            case "5681":
            case "5683":
                $modulDefine = array(
                    "modul" => "240",
                    "subModul" => "241",//
                );
                break;
            case "110":
            case "116":
            case "117":
            case "111":
            case "118":
            case "1155":
                $modulDefine = array(
                    "modul" => "240",
                    "subModul" => "242",//
                );
                break;
            //endregion
            //region folder valas
            case "383":
            case "384":
            case "1759":
            case "1758":
                $modulDefine = array(
                    "modul" => "250",
                    "subModul" => "251",//
                );
                break;
            //endregion
            //region folder asset managemant
            case "421":
                $modulDefine = array(
                    "modul" => "280",
                    "subModul" => "281",// beli aset
                );
                break;
            case "422":
                $modulDefine = array(
                    "modul" => "280",
                    "subModul" => "282",// tambah aset dari modal
                );
                break;
            case "2483":
            case "2485":
                $modulDefine = array(
                    "modul" => "280",
                    "subModul" => "283",// tambah aset dari modal
                );
                break;
            case "8786":
            case "8787":
                $modulDefine = array(
                    "modul" => "280",
                    "subModul" => "284",// depresiasai aset berwujud
                );
                break;
            case "8788":
                $modulDefine = array(
                    "modul" => "280",
                    "subModul" => "285",// depresiasai aset tidak berwujud
                );
                break;
            case "8789":
                $modulDefine = array(
                    "modul" => "280",
                    "subModul" => "286",// penjualan aset
                );
                break;
            case "424":
                $modulDefine = array(
                    "modul" => "280",
                    "subModul" => "287",// sewa
                );
                break;
            //endregion
            case "1000":
            case "1001":
                $modulDefine = array(
                    "modul" => "310",
                    "subModul" => "311",// rugilaba
                );
                break;
            case "9996":
            case "9990":
            case "9994":// dipakai untuk adj uang muka+ppn pada modal
            case "9999":
            case "99999":
                $modulDefine = array(
                    "modul" => "320",
                    "subModul" => "321",// koreksi
                );
                break;
            case "3311":
            case "3322":
            case "3333":
            case "3344":
                $modulDefine = array(
                    "modul" => "330",
                    "subModul" => "331",// koreksi
                );
                break;
            case "6699":
                $modulDefine = array(
                    "modul" => "340",
                    "subModul" => "341",// koreksi
                );
                break;
            case "6698":
                $modulDefine = array(
                    "modul" => "350",
                    "subModul" => "351",// koreksi
                );
                break;
            case "4822":// invoicing
                $modulDefine = array(
                    "modul" => "360",
                    "subModul" => "361",// koreksi
                );
                break;
        }

        return $modulDefine;
    }

    //-------------------
    public function getCounterNumber()
    {
        $company = 100;
        $getModulCounter = $this->getModulCounter();
//arrPrintHijau($this->transaksiGate);


        // kiriman data dari transaksi
        if (isset($this->transaksiGate)) {
            foreach ($this->transaksiGate as $key => $val) {
                $$key = $val;
            }
        }
        else {
            mati_disini("Transaksi gagal disimpan karena counter transaksi salah.");
        }


        if (isset($getModulCounter) && (sizeof($getModulCounter) > 0)) {
            foreach ($getModulCounter as $ckey => $cval) {
//                cekUngu(":: $ckey => $cval ::");
                $$ckey = $cval;
            }
        }
        else {
            mati_disini("Transaksi gagal disimpan karena counter modul transaksi belu disett. Silahkan menghubungi admin.");
        }


        //region untuk counter master
        if (isset($this->mainGate)) {
            foreach ($this->mainGate as $mkey => $mval) {
                $$mkey = $mval;
            }
        }
        else {
            mati_disini("Transaksi gagal disimpan karena counter transaksi salah.");
        }

        $counterDefineValues = array();
        foreach ($this->counterDefine as $spec) {
            $spec_ex = explode("|", $spec);
            $hasil = "";
            foreach ($spec_ex as $skey) {
                $skey_val = isset($$skey) ? $$skey : 0;
                if ($hasil == "") {
                    $hasil = "$skey_val";
                }
                else {
                    $hasil .= "|$skey_val";
                }
            }

            // untuk mencari karakter |0 supaya tidak dicounter
            $ketemu = (strpos("$hasil, $hasil", "|0"));
            if ($ketemu > 0) {

            }
            else {
                $counterDefineValues[$spec] = $hasil;
            }

        }
        //endregion

        //region untuk counter items
        $counterDefineValuesItems = array();
        if (isset($this->itemsGate)) {
            foreach ($this->itemsGate as $ikey => $iSpec) {
                foreach ($iSpec as $iikey => $iival) {
                    $$iikey = $iival;
                }

                foreach ($this->counterDefineItems as $spec) {
                    $spec_ex = explode("|", $spec);
                    $ihasil = "";
                    foreach ($spec_ex as $skey) {
                        $skey_val = isset($$skey) ? $$skey : 0;
                        if ($ihasil == "") {
                            $ihasil = "$skey_val";
                        }
                        else {
                            $ihasil .= "|$skey_val";
                        }
                    }

                    // untuk mencari karakter |0 supaya tidak dicounter
                    $ketemu = (strpos("$ihasil, $ihasil", "|0"));
                    if ($ketemu > 0) {

                    }
                    else {
                        $counterDefineValuesItems[$ikey][$spec] = $ihasil;
                    }

                }
            }
        }
        //endregion

        //region untuk counter items2_sum
        $counterDefineValuesItems2_sum = array();
        if (isset($this->items2_sumGate)) {
            foreach ($this->items2_sumGate as $ikey => $iSpec) {
                foreach ($iSpec as $iikey => $iival) {
                    $$iikey = $iival;
                }

                foreach ($this->counterDefineItems2_sum as $spec) {
                    $spec_ex = explode("|", $spec);
                    $ihasil = "";
                    foreach ($spec_ex as $skey) {
                        $skey_val = isset($$skey) ? $$skey : 0;
                        if ($ihasil == "") {
                            $ihasil = "$skey_val";
                        }
                        else {
                            $ihasil .= "|$skey_val";
                        }
                    }

                    // untuk mencari karakter |0 supaya tidak dicounter
                    $ketemu = (strpos("$ihasil, $ihasil", "|0"));
                    if ($ketemu > 0) {

                    }
                    else {
                        $counterDefineValuesItems2_sum[$ikey][$spec] = $ihasil;
                    }

                }
            }
        }
        //endregion


        $counterHasil = array();
        $counterHasil_insert = array();
        if (sizeof($counterDefineValues) > 0) {
            foreach ($counterDefineValues as $key => $val) {

                $cc = New CustomCounter();
                $urut = $cc->setCounterNumber($key, $val, "master", "0");

                $counterHasil['main'][] = array(
                    "key" => $key,
                    "value" => $val,
                    "hasil" => $urut,
                );

                $kolom = str_replace("|", "_", $key);
                $counterHasil_insert['main']['_' . $kolom] = $urut;
            }
            if (isset($getModulCounter) && (sizeof($getModulCounter) > 0)) {
                foreach ($getModulCounter as $ckey => $cval) {
                    $counterHasil_insert['main'][$ckey] = $cval;
                }
            }
            $counterHasil_insert['main']['company_id'] = $company;
        }

        if (sizeof($counterDefineValuesItems) > 0) {
            foreach ($counterDefineValuesItems as $iID => $iSpec) {
                foreach ($iSpec as $key => $val) {

                    $cc = New CustomCounter();
                    $urut = $cc->setCounterNumber($key, $val, "detail", "0");// tembah produk dulu

                    $counterHasil['items'][$iID][] = array(
                        "key" => $key,
                        "value" => $val,
                        "hasil" => $urut,
                    );

                    $kolom = str_replace("|", "_", $key);
                    $counterHasil_insert['items'][$iID]['_' . $kolom] = $urut;
                }
            }
        }

        if (sizeof($counterDefineValuesItems2_sum) > 0) {
            foreach ($counterDefineValuesItems2_sum as $iID => $iSpec) {
                foreach ($iSpec as $key => $val) {

                    $cc = New CustomCounter();
                    $urut = $cc->setCounterNumber($key, $val, "detail", "0");// tembah produk dulu

                    $counterHasil['items2_sum'][$iID][] = array(
                        "key" => $key,
                        "value" => $val,
                        "hasil" => $urut,
                    );

                    $kolom = str_replace("|", "_", $key);
                    $counterHasil_insert['items2_sum'][$iID]['_' . $kolom] = $urut;
                }
            }
        }


//arrPrintWebs($counterDefineValues);
//arrPrintWebs($counterDefineValuesItems);
//arrPrintWebs($counterDefineValuesItems2_sum);
//arrPrintPink($counterHasil_insert);


        return $counterHasil_insert;
    }

    //counter susulan-------------------
    public function getCounterNumberRekening()
    {
        $company = 100;

        $getModulCounter = $this->getModulCounter();


        // kiriman data dari transaksi
        if (isset($this->transaksiGate)) {
            foreach ($this->transaksiGate as $key => $val) {
                $$key = $val;
            }
        }
        else {
            mati_disini("Transaksi gagal disimpan karena counter transaksi salah.");
        }


        if (isset($getModulCounter) && (sizeof($getModulCounter) > 0)) {
            foreach ($getModulCounter as $ckey => $cval) {
//                cekUngu(":: $ckey => $cval ::");
                $$ckey = $cval;
            }
        }
        else {
            mati_disini("Transaksi gagal disimpan karena counter modul transaksi belu disett. Silahkan menghubungi admin.");
        }


        //region untuk counter master
        if (isset($this->mainGate)) {
            foreach ($this->mainGate as $mkey => $mval) {
                $$mkey = $mval;
            }
        }
        else {
            mati_disini("Transaksi gagal disimpan karena counter transaksi salah.");
        }


        $rekening = $this->rekening;
        $counterDefineValues = array();
        foreach ($this->counterDefineRekening as $spec) {
            $spec_ex = explode("|", $spec);
            $hasil = "";
            foreach ($spec_ex as $skey) {
                $skey_val = isset($$skey) ? $$skey : 0;
                if ($hasil == "") {
                    $hasil = "$skey_val";
                }
                else {
                    $hasil .= "|$skey_val";
                }
            }

            // untuk mencari karakter |0 supaya tidak dicounter
//            $ketemu = (strpos("$hasil, $hasil", "|0"));
//            if ($ketemu > 0) {
//
//            }
//            else {
            $counterDefineValues[$spec] = $hasil;
//            }

        }
        //endregion


        $counterHasil = array();
        $counterHasil_insert = array();
        if (sizeof($counterDefineValues) > 0) {
            foreach ($counterDefineValues as $key => $val) {

                $cc = New CustomCounter();
                $urut = $cc->setCounterNumber($key, $val, "master", "0");

                $counterHasil['main'][] = array(
                    "key" => $key,
                    "value" => $val,
                    "hasil" => $urut,
                );

                $kolom = str_replace("|", "_", $key);
                $counterHasil_insert['main']['_' . $kolom] = $urut;
            }
//            if (isset($getModulCounter) && (sizeof($getModulCounter) > 0)) {
//                foreach ($getModulCounter as $ckey => $cval) {
//                    $counterHasil_insert['main'][$ckey] = $cval;
//                }
//            }
//            $counterHasil_insert['main']['company_id'] = $company;
        }

//        if (sizeof($counterDefineValuesItems) > 0) {
//            foreach ($counterDefineValuesItems as $iID => $iSpec) {
//                foreach ($iSpec as $key => $val) {
//
//                    $cc = New CustomCounter();
//                    $urut = $cc->setCounterNumber($key, $val, "detail", "0");// tembah produk dulu
//
//                    $counterHasil['items'][$iID][] = array(
//                        "key" => $key,
//                        "value" => $val,
//                        "hasil" => $urut,
//                    );
//
//                    $kolom = str_replace("|", "_", $key);
//                    $counterHasil_insert['items'][$iID]['_' . $kolom] = $urut;
//                }
//            }
//        }
//
//        if (sizeof($counterDefineValuesItems2_sum) > 0) {
//            foreach ($counterDefineValuesItems2_sum as $iID => $iSpec) {
//                foreach ($iSpec as $key => $val) {
//
//                    $cc = New CustomCounter();
//                    $urut = $cc->setCounterNumber($key, $val, "detail", "0");// tembah produk dulu
//
//                    $counterHasil['items2_sum'][$iID][] = array(
//                        "key" => $key,
//                        "value" => $val,
//                        "hasil" => $urut,
//                    );
//
//                    $kolom = str_replace("|", "_", $key);
//                    $counterHasil_insert['items2_sum'][$iID]['_' . $kolom] = $urut;
//                }
//            }
//        }

//arrPrintHijau($this->counterDefineRekening);
        arrPrintWebs($counterDefineValues);
//arrPrintWebs($counterDefineValuesItems);
//arrPrintWebs($counterDefineValuesItems2_sum);
        arrPrintPink($counterHasil_insert);


        return $counterHasil_insert;
    }
}
