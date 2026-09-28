<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/27/2019
 * Time: 1:55 PM
 */
class MdlMongoMother extends CI_Model
{

    protected $dbName;
    protected $client;
    protected $tableName;

    protected $filters = array(
        "status" => "1",
        "trash" => "0",
        "link_id" => "0",
    );
    protected $colection = array(
        "main" => "transaksi",
        "detil" => "transaksi_data",
        "registry" => "transaksi_registry",
        "sign" => "transaksi_sign",
        "mainValues" => "transaksi_values",
        "detailValues" => "transaksi_data_values",
        "extras" => "transaksi_extstep",
        "dataRegistry"=>"transaksi_data_registry",

    );
    private $fields = array(
        "main" => array(
            "id",
            "id_master",
            "id_top",
            "link_id",
            "ids_prev",
            "ids_prev_intext",
            "ids_ref",
            "ids_ref_intext",
            "jenis_master",
            "jenis_top",
            "jenises_prev",
            "jenises_prev_intext",
            "jenis",
            "jenis_label",
            "counters",
            "counters_intext",
            "nomer_top",
            "nomers_prev",
            "nomers_prev_intext",
            "nomer",
            // "nomer2",
            // "inv",
            "dtime",
            "fulldate",
            "oleh_id",
            "oleh_nama",
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "dtime_kirim",
            "cabang_id",
            "cabang_nama",
            "gudang_id",
            "gudang_nama",
            "pembayaran",
            "pembayaran_sys",
            "keterangan",
            "status",
            //            "orders_id",
            //            "orders_no",
            "bank_id",
            "bank_nama",
            "bank_rekening_id",
            "bank_rekening_nama",
            "bank_from",

            "jatuh_tempo",
            "dtime_jatuh_tempo",
            "transaksi_nilai",
            "bank_id_from",
            "bank_nama_from",
            "bank_rekening_id_from",
            "bank_rekening_nama_from",
            "transaksi_jenis",

            "cabang2_id",
            "cabang2_nama",

            "gudang2_id",
            "gudang2_nama",

            "nomer_surat_jalan",
            "tpl_alamat_id",
            "step_avail",
            "step_current",
            "step_number",
            "next_step_num",
            "next_step_code",
            "next_step_label",
            "next_group_code",

            "tail_number",
            "tail_code",

            "transaksi_nilai_tagihan",
            "transaksi_nilai_terbayar",
            "transaksi_nilai_sisa",

            "div_id",
            "div_nama",
            "status_4",
            "trash_4",
            "deskripsi",
            "transaksi_jenis2_label",
            "transaksi_jenis2_kode",
            "transaksi_jenis2_value",
            "transaksi_jenis2_value_ppn",
            "transaksi_jenis2",

            "cancel_dtime",
            "cancel_name",
            "cancel_id",
        ),
        "detil" => array(
            "id",
            "produk_jenis",
            "produk_id",
            "produk_nama",
            "produk_label",
            "produk_kode",
            "satuan",
            "produk_ord_jml",
            "produk_ord_hrg",
            "produk_ord_diskon",
            "produk_ord_diskon_persen",
            "produk_ord_diterima",
            "produk_ord_kurang",
            //            "produk_berat",
            "produk_berat_gross",
            //            "produk_volume",
            "produk_volume_gross",

            "transaksi_id",

            "trash",
            "status",
            "sub_step_number",
            "sub_step_current",
            "sub_step_avail",
            "next_substep_num",
            "next_substep_code",
            "next_substep_label",
            "next_subgroup_code",
            "valid_qty",

            "sub_tail_number",
            "sub_tail_code",

        ),
        "sign" => array(
            "id",
            "dtime",
            "step_number",
            "step_name",
            "group_code",
            "oleh_id",
            "oleh_nama",
            "keterangan",
        ),
        "detailFields" => array(
            "transaksi_id",
            "produk_id",
            "key",
            "value",
        ),
        "mainValues" => array(
            "transaksi_id",
            "key",
            "value",
        ),
        "detailValues" => array(
            "transaksi_id",
            "produk_jenis",
            "produk_id",
            "key",
            "value",
        ),
        "elements" => array(
            "transaksi_id",
            "mdl_name",
            "key",
            "value",
            "name",
            "label",
            "contents",
            "contents_intext",


        ),
        "extras" => array(
            "id",
            "master_id",
            "transaksi_id",
            "_key",
            "_label",
            "_value",
            "group_id",
            "state",
            "proposed_by",
            "proposed_dtime",
            "done_by",
            "done_dtime",
        ),
        "dataRegistry" => array(
            "transaksi_id",
            "main",
            "items",
            "items2",
            "items2_sum",
            "itemSrc",
            "itemSrc_sum",
            "items3",
            "items3_sum",
            "items4",
            "items4_sum",
            "items_noapprove",
            "rsltItems",
            "rsltItems2",
            "rsltItems3",
            "tableIn_master",
            "tableIn_detail",
            "tableIn_detail2_sum",
            "tableIn_detail_rsltItems",
            "tableIn_detail_rsltItems2",
            "tableIn_master_values",
            "tableIn_detail_values",
            "tableIn_detail_values_rsltItems",
            "tableIn_detail_values_rsltItems2",
            "tableIn_detail_values2_sum",
            "main_add_values",
            "main_add_fields",
            "main_elements",
            "main_inputs",
            "main_inputs_orig",
            "receiptDetailFields",
            "receiptSumFields",
            "receiptDetailFields2",
            "receiptDetailSrcFields",
            "receiptSumFields2",
            "jurnal_index",
            "postProcessor",
            "preProcessor",
            "revert",
            "items_komposisi",
            "jurnalItems",
            "componentsBuilder",
            "items5_sum",
            "items6_sum",
            "items7_sum",
            "items8_sum",
            "items9_sum",
            "items10_sum",
        ),

    );
    protected $option;
    protected $param;
    protected $inParam;
    protected $limit;
    protected $range;
    protected $criteria;
    protected $shortBy;
    protected $aliasName = array(
        "tmp" => array(
            "id" => "id_tmp",
            "jenis" => "jenis_tmp",
        ),
        "main" => array(),
        "detail" => array(
            "id" => "id_detail",
            "trash" => "trash_detail",
            "status" => "status_detail",
        ),

    );
    protected $joinedFilter = array();
    protected $jointSelectFields;

    public function getJointSelectFields()
    {
        return $this->jointSelectFields;
    }

    public function setJointSelectFields($jointSelectFields)
    {
        //string setJointSelectFields("main,detail") untuk memlilih kolom yang diselect, jika tidak diset akan diambil dari field yang ada lihat array fields
        $this->jointSelectFields = $jointSelectFields;
    }


    public function addFilterJoin($f)
    {
        $this->joinedFilter[] = $f;
    }

    public function getJoinedFilter()
    {
        return $this->joinedFilter;
    }

    public function setJoinedFilter($joinedFilter)
    {
        $this->joinedFilter = $joinedFilter;
    }

    public function getAliasName()
    {
        return $this->aliasName;
    }

    public function setAliasName($aliasName)
    {
        $this->aliasName = $aliasName;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getShortBy()
    {
        return $this->shortBy;
    }

    public function setShortBy($shortBy)
    {
        $this->shortBy = $shortBy;
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

    public function lookUpMainTransaksi()
    {
        $tblName = $this->colection['main'];
        $filter = isset($this->filters) ? $this->filters : array();
        $options = isset($this->option) ? $this->option : array();
        if (isset($this->param)) {
            if (!isset($this->inParam)) {
                matiHEre("in param belum diset array('foo','bar')");
            }
            $this->mongo_db->where_in($this->param, $this->inParam);
        }
//        $this->mongo_db->select($this->fields['main']);
        $this->mongo_db->select($this->fields['main']);
        $result = $this->mongo_db->get_where($tblName, $filter);
        if (sizeof($result) > 0) {
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }
//            return (object)$result;
            return $return;
        }
        else {
            return array();
        }
    }

    public function lookUpDetilTransaksi($tID)
    {
        $tblName = $this->colection['detil'];
        $filter = array("transaksi_id" => "$tID", "trash" => "0");
        $options = isset($this->option) ? $this->option : array();
        $result = $this->mongo_db->get_where($tblName, $filter);
//        cekHitam($tID);
//        $result = $this->mongo_db->get_where("transaksi_data",array("id"=>"1"));
        if (sizeof($result) > 0) {
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }
            return $return;
        }
        else {
            return array();
        }
    }

    public function lookUpJoined($t, $main)
    {
        $tblName = $this->colection['detil'];
        $filter = array("transaksi_id" => "$t", "trash" => "0");
        $options = isset($this->option) ? $this->option : array();
        $this->mongo_db->select($this->fields['detil']);
        // $this->mongo_db->where($filter);
        // $result = $this->mongo_db->get_where($tblName, $filter);
        $result = $this->mongo_db->get_where($tblName, $filter);
        $return = array();
        foreach ($result as $i => $tmp) {
            $return[$i] = (object)array_merge((array)$main, (array)$tmp);
        }
        return $return;
    }

    public function lookupSignaturesByMasterID($id)
    {
        //order by id
        $tblName = $this->colection['sign'];
        $filter = array("transaksi_id" => "$id");
        $options = isset($this->option) ? $this->option : array();
        $this->mongo_db->order_by(array("id" => "asc"));
        $result = $this->mongo_db->get_where($tblName, $filter);
        if (sizeof($result) > 0) {
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }
            return $return;

        }
        else {
            return array();
        }
    }//--baca signature berdasarkan ID master

    public function lookupMainValuesByTransID($id)
    {

        $tblName = $this->colection['mainValues'];
        $filter = array("transaksi_id" => "$id");
        $options = isset($this->option) ? $this->option : array();
        $result = $this->mongo_db->get_where($tblName, $filter);
        if (sizeof($result) > 0) {
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }
            return $return;
        }
        else {
            return array();
        }
    }

    public function lookupDetailValuesByTransID($id)
    {
        $tblName = $this->colection['detailValues'];
        $filter = array("transaksi_id" => "$id");
        $options = isset($this->option) ? $this->option : array();
        $result = $this->mongo_db->get_where($tblName, $filter);
        if (sizeof($result) > 0) {
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }
            return $return;
        }
        else {
            return array();
        }

    }

    public function lookupRegistriesByMasterID($id)
    {
        $tblName = $this->colection['dataRegistry'];
        $filter = array("transaksi_id" => "$id");
//        arrPrint($filter);
        $options = isset($this->option) ? $this->option : array();
        if (isset($this->param)) {
            //untuk kiriman berupa in jenis in ('','')//sql


        }
        else {

        }
        $result = $this->mongo_db->get_where($tblName, $filter);
//        arrPrint($result);
//        matiHEre();
        if (sizeof($result) > 0) {
//            $return = array();
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }

            return $return;
        }
        else {
            return array();
        }
    }

    /**
     * --------------------------------------------------------------------------------
     * function lookupHistories
     * --------------------------------------------------------------------------------
     * $m= new MdlMongoMother
     * $m->setLimit("string");//jika ada limit
     * $m->setRange(array("start","end"))//jika range aktive wajib set key(set->param)
     * $m->setParam("string kolom yang dirange");
     * $m->setInParam(array())// berisi param yang ingin dicari dengan metode in("1","35"),misal transksi id
     * $m->addFilter(array())//tambahan filter optional
     * usage $m->lookupHistories();
     */
    public function lookupHistories()
    {
        // cekHitam($filters);
        $filters = $this->filters;
        $tblName = $this->colection['main'];
        if (isset($this->limit)) {
            $this->mongo_db->limit($this->limit);
        }
        if (isset($this->range)) {
            $a = $this->range[0];
            $b = $this->range[1];
            $this->mongo_db->where_between($this->param, "$a", "$b");
        }
        // arrPrint($filters);
        // $this->mongo_db->where($filters);
        $this->mongo_db->order_by(array("fulldate" => "desc"));
        $result = $this->mongo_db->get_where($tblName, $filters);
        // $result = $this->mongo_db->get($tblName);
        if (sizeof($result) > 0) {
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }
//            return (object)$result;
            return $return;
        }
        else {
            return array();
        }
    }

    /**
     * --------------------------------------------------------------------------------
     * function lookupRegistryAll
     * --------------------------------------------------------------------------------
     * $m= new MdlMongoMother
     * $m->setInParam(array())// berisi param yang ingin dicari dengan metode in("1","35"),misal transksi id
     * $m->setParam("string key")//berisi key sebagai acuan misal transaksi_id,nama yang akan dicari mengunakan inParam
     * $m->addfilter(array("fo"=>"bar,)), filtering yang digunakan
     * $this->field(array("main","items));
     */
    public function lookupRegistryAll()
    {
        $filters = !isset($this->filters) ? array() : $this->filters;
        if (isset($this->fields) && sizeof($this->fields) > 0) {
            // matiHEre($this->fields);
            $this->mongo_db->select($this->fields);
        }
        if (isset($this->param)) {
            if (!isset($this->inParam)) {
                matiHEre("in param belum diset array('foo','bar')");
            }
            $this->mongo_db->where_in($this->param, $this->inParam);
        }
//        arrPrint($filters);
//        arrPrint($this->inParam);
        $tblName = $this->colection['dataRegistry'];
        // if (sizeof($filters) > 0) {
        //     cekHitam();
        //     $result = $this->mongo_db->get_where($tblName, $filters);
        // }
        // else {
        //     // matiHEre("koq kasini");
        //     cekMErah($tblName);
        // arrPrint($this->fields);
            $result = $this->mongo_db->get($tblName);
        // }
        // $result = $this->mongo_db->get_where($tblName, $filters);
        // matiHEre();
//        matiHEre();


        if (sizeof($result) > 0) {
//            $return = array();
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }

            return $return;
        }
        else {
            return array();
        }

    }



    /**
     * registry model baru baca dari tabel transaksi_data_registry
     * $this->field(array("main","items));
     */
    public function lookupDataRegistries(){
        $tblName = $this->colection['dataRegistry'];
        $filters = !isset($this->filters) ? array() : $this->filters;
        if (isset($this->fields) && sizeof($this->fields) > 0) {
            // matiHEre($this->fields);
            $this->mongo_db->select($this->fields);
        }
        if (sizeof($filters) > 0) {
            $result = $this->mongo_db->get_where($tblName, $filters);
        }
        else {
            // matiHEre("2");
            $result = $this->mongo_db->get($tblName);
        }
        // arrPrint($filters);
        // arrPrint($this->fields);
        // matiHEre($tblName);
        if (sizeof($result) > 0) {
            // arrPrint($result);
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }

            return $return;
        }
        else {
            return array();
        }

    }

    /**
     * --------------------------------------------------------------------------------
     * function add data
     * --------------------------------------------------------------------------------
     * $m= new MdlMongoMother
     * required $m->setTableName("table_name")
     * usage $m->addData(array());
     */
    public function addData($params)
    {
        $tableName = $this->tableName;
        $result = $this->mongo_db->insert($this->tableName, $params);
        if ($result) {
            return true;
        }
        else {
            matiHEre("gagal menulis data");
        }
    }

    /**
     * @param $where array("id"=>"9")
     * @param $arrData array("nilai"=>"10)
     * @return bool
     */
    public function updateData($wheres, $arrDatas)
    {
        $arrData = array();
        $where = array();
        if (sizeof($wheres) > 0) {
            foreach ($wheres as $key => $val) {
                $where[$key] = "$val";
            }
        }
        if (sizeof($arrDatas) > 0) {
            foreach ($arrDatas as $key => $val) {
                $arrData[$key] = "$val";
            }
        }

//
//        arrPrint($where);
//        arrPrint($arrData);
//mati_disini();
        $tableName = $this->tableName;
        $this->mongo_db->where($where);
        $this->mongo_db->set($arrData);
        $result = $this->mongo_db->update($this->tableName);

        if ($result) {
            return true;
        }
        else {
            show_error("gagal update data collection $tableName", 500);
            return false;
        }
    }

    public function updateDataMany($arrDatas)
    {
        $arrData = array();
        if (sizeof($arrDatas) > 0) {
            foreach ($arrDatas as $key => $val) {
                $arrData[$key] = "$val";
            }
        }

        $tableName = $this->tableName;
        if (!isset($this->filters) || sizeof($this->filters) == 0) {
            if (!isset($this->param)) {
                matiHere("error on delete data operation not set!");
            }
        }

        $inParams = array();
        if (isset($this->param)) {
            if (!isset($this->inParam)) {
                matiHere("error on delete data in param  not set!");
            }
            foreach ($this->inParam as $val) {
                $inParams[] = "$val";
            }

            $this->mongo_db->where_in($this->param, $inParams);
        }
        else {
            $this->mongo_db->where($this->filters);
        }
//        $this->mongo_db->where_in($where);
        $this->mongo_db->set($arrData);
        $result = $this->mongo_db->update($tableName);

        if ($result) {
            return true;
        }
        else {
            show_error("gagal update data collection $tableName", 500);
            return false;
        }
    }

    /**
     *required set->tableName("tableName)
     *required $m->addfilter(array("fo"=>"bar))
     *required $m->setparam("_id") ------- belum suport
     *required $m->setInParam(array("456","69582))//milsal id --belum suport
     * gunakan salah satu filter atau param
     */
    public function deleteData()
    {
        if (!isset($this->tableName)) {
            matiHere("error on delete data collection not set!");
        }
        // ada filter atau param tidak untuk dieksekusi
        if (!isset($this->filters) || sizeof($this->filters) == 0) {
            if (!isset($this->param)) {
                matiHere("error on delete data operation not set!");
            }
        }

        $inParams = array();
        if (isset($this->param)) {
            if (!isset($this->inParam)) {
                matiHere("error on delete data in param  not set!");
            }
            foreach ($this->inParam as $val) {
                $inParams[] = "$val";
            }
            matiHere("belum suport");
            $this->mongo_db->where_in($this->param, $inParams);
        }
        else {
            $this->mongo_db->where($this->filters);
        }
        $result = $this->mongo_db->delete($this->tableName);
        if ($result) {
            return true;
        }
        else {
            return false;
        }


//    matiHere();


    }

    public function deleteObjectId()
    {
        if (!isset($this->tableName)) {
            matiHere("error on delete data collection not set!");
        }
        // ada filter atau param tidak untuk dieksekusi
        if (!isset($this->filters) || sizeof($this->filters) == 0) {
            if (!isset($this->param)) {
                matiHere("error on delete data operation not set!");
            }
        }
//        $this->mongo_db->where
        $result = $this->mongo_db->deleteObjectId($this->tableName, $this->filters);
        if ($result) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * --------------------------------------------------------------------------------
     * function lookupAll
     * --------------------------------------------------------------------------------
     * $m= new MdlMongoMother
     * required $m->setTableName("table_name")
     * jika ada tambahan lebih besar atau lebih kecil gunakan string $gt untuk > , $lt untuk lebih kecil,!= atau <> gunakan  $ne
     * contoh
     * $m->setParam("string");
     * $m->setInParam(array("2018","2019));//menggunakan pareamenter in/ or
     *         $m->addFilter(array(
     *                         'periode' => "tahunan",
     *                         'th' => "2019",
     *                          'cabang_id' => array('$gt' => "0"),//ini untuk > greather than
     * )
     * );
     *      * $m->lookUpall();
     */
    public function lookupAll()
    {

        $tableName = $this->tableName;
        $filters = !isset($this->filters) ? array() : $this->filters;
        if (isset($this->limit)) {
            $this->mongo_db->limit($this->limit);
        }
        if (isset($this->param)) {


            if (isset($this->range)) {
                if (!isset($this->param)) {
                    matiHEre("param belum diset");
                }
                $a = $this->range[0];
                $b = $this->range[1];
                $this->mongo_db->where_between($this->param, $a, $b);
            }
            else {
                if (!isset($this->inParam)) {
                    matiHEre("in param belum diset array('foo','bar')");
                }

                $inParams = array();
                foreach ($this->inParam as $val) {
                    $inParams[] = "$val";
                }
                $this->mongo_db->where_in($this->param, $inParams);
            }

        }
// cekKuning($tableName);
// arrPrintWebs($this->wheres);
        if (isset($this->fields) && sizeof($this->fields) > 0) {
//            cekHitam("masuk");
            $this->mongo_db->select($this->fields);
        }
        if (isset($this->shortBy)) {
            $this->mongo_db->order_by($this->shortBy);
        }
        if (sizeof($filters) > 0) {
            $this->mongo_db->where($filters);
//            $result = $this->mongo_db->get_where($tableName, $filters);//tak matiin widi
        }
//        else {
        $result = $this->mongo_db->get($tableName);
//        }
// arrPrint($filters);
        if (sizeof($result) > 0) {
            return $result;
        }
        else {
            return array();
        }


    }

    public function lookupOne($tableName)
    {
        $filters = !isset($this->filters) ? array() : $this->filters;

        $this->mongo_db->where($filters);
        if (isset($this->shortBy)) {
            $this->mongo_db->order_by($this->shortBy);
        }

        $result = $this->mongo_db->find_one($tableName);

        if (sizeof($result) > 0) {
            return (object)$result;
        }
        else {
            return array();
        }
    }

    public function lookupRecentHistories()
    {
        $tblName = $this->colection['main'];

        $filter = isset($this->criteria) ? array_merge($this->filters, $this->criteria) : $this->filters;
        $options = isset($this->option) ? $this->option : array();
        if (isset($this->param)) {
            if (!isset($this->inParam)) {
                matiHEre("in param belum diset array('foo','bar')");
            }
            $this->mongo_db->where_in($this->param, $this->inParam);
        }
        $this->mongo_db->order_by(array("id" => "desc"));
        $this->mongo_db->limit(5);
        $result = $this->mongo_db->get_where($tblName, $filter);
        if (sizeof($result) > 0) {
            $return = array();
            foreach ($result as $i => $val) {
                $return[$i] = (object)$val;
            }
//            return (object)$result;
            return $return;
        }
        else {
            return array();
        }
    }

    //of dulu masih gagal
    /*public function lookupEntryPoints_joined($id){
        $tblName = $this->colection['main'];
        if(!isset($id)){
            die('id belum diset');
        }else{
            $filters = array(
                "id_master"=>"$id",
                'link_id'=>array(
                    '$gt'=>'0',
                ),
                'grup'=>'trasaksi_id'
            );
        }

        $result = $this->mongo_db->get_where($tblName, $filters);
        if(sizeof($result)>0){
            return (object)$result;
        }
        else{
            return array();
        }
    }*/

    //writer
    //  insert transaksi main
    public function writeMainEntries($params)
    {
        if (is_array($params)) {
            if (sizeof($params) > 0) {

                $data = array();
//                foreach ($params as $fName => $fValue) {
//                    if (in_array($fName, $this->fields['main'])) {
//                        $data[$fName] = $fValue;
//                    }
//                }
//                $this->db->insert($this->tableNames['main'], $data);
//                $insertID = $this->db->insert_id();
//                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeMainEntries_entryPoint($insertID, $masterID, $params)
    {
        if (is_array($params)) {
            if (sizeof($params) > 0) {
//                $data = array();
//                foreach ($params as $fName => $fValue) {
//                    if (in_array($fName, $this->fields['main'])) {
//                        $data[$fName] = $fValue;
//                    }
//                }
//                //===nulis entry-point
//                if (strpos($params['jenis'], '_') == false) {
//                    $epData = $data;
//                    $replacers = array(
//                        "id_top"    => 0,
//                        "id_master" => $masterID,
//                        "link_id"   => $insertID,
//                        "jenis"     => $data['jenis'] . "_" . $data['step_number'],
//                        "nomer"     => $data['nomer'] . "_" . $data['step_number'] . "_" . date("YmdHis"),
//                    );
//                    foreach ($replacers as $key => $newVal) {
//                        $epData[$key] = $newVal;
//                    }
//                    //                    $this->writeMainEntries($epData);
//                    //                    $insertID2=$this->db->insert_id();
//                    //remove filter
//                    $this->db->insert($this->tableNames['main'], $epData);
//                    $insertID2 = $this->db->insert_id();
//                }
//                else {
//                    $insertID2 = 999;
//                }
//
//                return $insertID2;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //  insert transaksi childs
    public function writeDetailEntries($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

//                $data = array();
//                foreach ($this->fields['detail'] as $kolom) {
//                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
//
//                    $data[$kolom] = $isi;
//                }
//                $data['transaksi_id'] = $transaksi_id;
//
//                $this->db->insert($this->tableNames['detail'], $data);
//                $insertID = $this->db->insert_id();
//
//
//
//                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis nilai2 utama tapi dipisah tabel
    public function writeMainValues($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                foreach ($this->fields['mainValues'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['mainValues'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis nilai2 rincian tapi dipisah tabel
    public function writeDetailValues($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['detailValues'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['detailValues'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis kolom2 rincian tapi dipisah tabel
    public function writeDetailFields($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['detailFields'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['detailFields'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeMainElements($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                foreach ($this->fields['elements'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['elements'], $data);

                //cekKuning($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }


}