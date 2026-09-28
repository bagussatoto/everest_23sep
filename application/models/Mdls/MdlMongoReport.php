<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/27/2019
 * Time: 1:55 PM
 */
class MdlMongoReport extends MdlMongoMother
{

    protected $dbName;
    protected $client;
    protected $tableName;
    protected $filters;
    protected $colection = array(
        "cabang"   => array(
            "penjualan"                 => "penjualan",
            "penjualan_cabang"          => "penjualan cabang",
            "penjualan_cabang_customer" => "detail customer cabang",
            "penjualan_cabang_produk"   => "detail produk cabang",
            "penjualan_cabang_seller"   => "detail seller cabang",
        ),
        "customer" => array(
            "penjualan_customer"               => "penjualan customer",
            "penjualan_customer_cabang"        => "detail customer cabang",
            "penjualan_customer_produk"        => "detail produk customer",
            "penjualan_customer_produk_cabang" => "detail produk customer by produk cabang",
            "penjualan_customer_seller"        => "detail seller customer",
            "penjualan_customer_seller_cabang" => "detail seller customer seller by cabang",
        ),

        "produk" => array(
            "penjualan_produk"                 => "penjualan produk",
            "penjualan_produk_cabang"          => "detail produk cabang",
            "penjualan_produk_customer"        => "detail customer produk",
            "penjualan_produk_customer_cabang" => "detail customer produk",
            "penjualan_produk_seller"          => "detail seller produk by cabang",
            "penjualan_produk_seller_cabang"   => "detail seller produk by cabang",
        ),
        "seller" => array(
            "penjualan_seller"                 => "penjualan seller",
            "penjualan_seller_cabang"          => "detail seller cabang",
            "penjualan_seller_produk"          => "detail produk seller",
            "penjualan_seller_produk_cabang"   => "detail produk seller",
            "penjualan_seller_customer"        => "detail customer seller",
            "penjualan_seller_customer_cabang" => "detail customer seller by cabang",

        ),


    );
    protected $option;
    protected $param;
    protected $inParam;
    protected $limit;
    protected $range;
    protected $criteria;
    protected $periode;
    protected $pipeline;

    public function getPipeline()
    {
        return $this->pipeline;
    }

    public function setPipeline($pipeline)
    {
        $this->pipeline = $pipeline;
    }

    public function getPeriode()
    {
        return $this->periode;
    }

    public function setPeriode($periode)
    {
        $this->periode = $periode;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }

    public function addRange($a, $b)
    {
        //        $range = "$a,$b";
        return $this->range = array($a, $b);
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getInParam()
    {
        return $this->inParam;
    }

    public function setInParam($inParam)
    {
        $this->inParam = $inParam;
    }

    public function getParam()
    {
        return $this->param;
    }

    public function setParam($param)
    {
        $this->param = $param;
    }

    public function addFilter($f)
    {
        $this->filters = $f;
    }

    /**
     * @return mixed
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * @param mixed $option
     */
    public function setOption($option)
    {
        $this->option = $option;
    }

    public function getColection()
    {
        return $this->colection;
    }


    public function setColection($colection)
    {
        $this->colection = $colection;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    //region gs
    public function getDbName()
    {
        return $this->dbName;
    }

    public function setDbName($dbName)
    {
        $this->dbName = $dbName;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    //endregion

    public function __construct()
    {
        parent::__construct();
        //        require 'vendor/autoload.php';
        //        $this->client = new MongoDB\Client("mongodb://" . $this->config->item('heMongo')['server'], ['username' => $this->config->item('heMongo')['username'], 'password' => $this->config->item('heMongo')['password']]);
        //        $this->dbName=$this->config->item('heMongo')['database'];
    }

    //    /**
    //     * --------------------------------------------------------------------------------
    //     * function lookupAll
    //     * --------------------------------------------------------------------------------
    //     * $m= new MdlMongoMother
    //     * required $m->setTableName("table_name")
    //
    //     * jika ada tambahan lebih besar atau lebih kecil gunakan string $gt untuk > , $lt untuk lebih kecil
    //     * contoh
    //     *         $m->addFilter(array(
    //    *                         'periode' => "tahunan",
    //    *                         'th' => "2019",
    //    *                          'cabang_id' => array('$gt' => "0"),//ini untuk > greather than
    //                                    )
    //                               );
    //     *      * $m->lookUpall();
    //     */
    //    public function lookupAll(){
    //
    //        $tableName = $this->tableName;
    //        $filters = !isset($this->filters)? array():$this->filters;
    //        if(isset($this->param)){
    //            if(!isset($this->inParam)){
    //                matiHEre("in param belum diset array('foo','bar')");
    //            }
    //            $this->mongo_db->where_in($this->param,$this->inParam);
    //        }
    ////        arrPrint($filters);
    ////        arrPrint($this->inParam);
    ////matiHEre($tableName);
    ////        cekhitam($tableName);
    //        if(sizeof($filters)> 0){
    //            $result = $this->mongo_db->get_where($tableName,$filters);
    //        }else{
    //            $result = $this->mongo_db->get($tableName);
    //        }
    //        if(sizeof($result)>0){
    //            return $result;
    //        }
    //        else{
    //            return array();
    //        }
    ////        arrPrint($result);
    //
    ////        matiHEre($tableName);
    //    }

    public function lookUpSalesCabang()
    {

    }


}