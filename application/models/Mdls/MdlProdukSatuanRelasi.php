<?php

//--include_once "MdlHistoriData.php";
class MdlProdukSatuanRelasi extends MdlMother
{
    protected $tableName = "satuan_produk_relasi";
    protected $tableName2 = "produk";
    protected $indexFields = "id";
    protected $indexFields2 = "satuan_id";

    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
       "nama"       => "produk.nama",
        "kode"       => "produk.kode",
       "keterangan" => "produk.keterangan",
    );
    protected $search;
    protected $filters = array(
        "satuan_produk_relasi.status='1'",
        "satuan_produk_relasi.trash='0'",        // "satuan_produk_relasi.toko_id='-1'",
        //        "produk_per_supplier.suppliers='item'",
    );
    protected $sortBy = array(
        "kolom" => "produk_nama",
        "mode"  => "ASC",
    );
    protected $validationRules = array(
        "produk_id" => array("required", "singleOnly"),
        "no_urut" => array("required"),
    );
    protected $validateData = array(
        "produk_id", "suppliers_id"
    );
    protected $conditional;
    protected $fields = array(
        "id"          => array(
            "label"     => "id",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),

        "produk id"   => array(
            "label"     => "produk",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "produk_id",
            "inputType" => "combo",
            "reference" => "MdlProduk",
            "name2"     => "keterangan",
            //--"inputName" => "nama",

            "referenceFilter" => array("toko_id=toko_id"),
            "referenceSrc" => "id",
        ),


        "satuan_nama" =>array(
            "label"        => "satuan_nama",
            "type"         => "int", "length" => "24",
            "kolom"        => "satuan_id",
            "inputType"    => "combo",
            "reference"    => "Mdlsatuan",
            "defaultValue" => "id",
        ),

        "satuan_dasar" =>array(//ini baca dari satuan di data produk
            "label"        => "satuan dasar",
            "type"         => "int", "length" => "24",
            "kolom"        => "satuan_dasar_id",
            "inputType"    => "combo",
            "reference"    => "MdlProduk",
            "defaultValue" => "satuan_id",
        ),

        "qty" =>array(
            "label"        => "isi/pcs",
            "type"         => "int", "length" => "24",
            "kolom"        => "qty",
            "inputType"    => "text",
            // "reference"    => "Mdlsatuan",
            // "defaultValue" => "id",
        ),


        "barcode" =>array(
            "label"        => "barcode/SKU",
            "type"         => "int", "length" => "24",
            "kolom"        => "barcode",
            "inputType"    => "text",
        ),

        //        "produk nama"       => array(
        //            "label"     => "produk nama",
        //            "type"      => "int", "length" => "24",
        //            "kolom" => "produk_nama",
        //            "inputType" => "text",
        //            //--"inputName" => "nama",
        //        ),
        // "supplier id" => array(
        //     "label"        => "supplier",
        //     "type"         => "int", "length" => "24",
        //     "kolom"        => "suppliers_id",
        //     "inputType"    => "combo",
        //     "reference"    => "MdlSupplier",
        //     "defaultValue" => "id",
        //     //--"inputName" => "nama",
        // ),
        // "toko_id" => array(
        //     "label" => "toko",
        //     "type" => "varchar", "length" => "200",
        //     "kolom" => "toko_id",
        //     // "inputType" => "radio",
        //     "inputType" => "hidden_ref",
        //     "reference" => "MdlToko",
        //     "referenceFilter" => array("toko_id=toko_id"),
        //     "referenceSrc" => "id",
        //     // "strField"        => "toko_nama",
        //     // "editable"        => false,
        //     // "kolom_nama"      => "toko_nama",
        //     //     //--"inputName" => "folders",
        // ),
        //        "supplier nama"       => array(
        //            "label"     => "supplier nama",
        //            "type"      => "int", "length" => "24",
        //            "kolom" => "suppliers_nama",
        //            "inputType" => "text",
        //            //--"inputName" => "nama",
        //        ),
        "status"      => array(
            "label"        => "status",
            "type"         => "int", "length" => "24", "kolom" => "status",
            "inputType"    => "combo",
            "dataSource"   => array(
                0 => "inactive",
                1 => "active"
            ),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),
    );
    // protected $toko_id;


    /* ----------------------------
 * digunakan pada produk per supplier (conected produk to supplier)
 * ------------------------*/
    protected $listedFields = array(
        //        "folders"    => "folder",
        "satuan_nama"       => "satuan",
        "barcode"       => "barcode/SKU",
        //        "label"      => "label",
        "qty"  => "isi/pcs",

        "satuan_dasar_nama"  => "satuan dasar",
        // "no_urut"  => "urut",
        // "part_no"  => "part",
        //        "keterangan" => "keterangan",
    );

    //
    // public function getTokoId()
    // {
    //     return $this->toko_id;
    // }
    //
    // public function setTokoId($toko_id)
    // {
    //     $this->toko_id = $toko_id;
    // }

    public function getConditional()
    {
        return $this->conditional;
    }

    public function setConditional($conditional)
    {
        $this->conditional = $conditional;
    }

    public function getValidateData()
    {
        return $this->validateData;
    }

    public function setValidateData($validateData)
    {
        $this->validateData = $validateData;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    protected $listedFieldsView = array("produk_nama");
    //    protected $listedFieldsView = array("kode");

    //<editor-fold desc="getter and setter">
    public function getTableName2()
    {
        return $this->tableName2;
    }

    public function setTableName2($tableName2)
    {
        $this->tableName2 = $tableName2;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getIndexFields()
    {
        return $this->indexFields;
    }

    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    public function getListedFieldsForm()
    {
        return $this->listedFieldsForm;
    }

    public function setListedFieldsForm($listedFieldsForm)
    {
        $this->listedFieldsForm = $listedFieldsForm;
    }

    public function getListedFieldsHidden()
    {
        return $this->listedFieldsHidden;
    }

    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function getListedFieldsView()
    {
        return $this->listedFieldsView;
    }

    public function setListedFieldsView($listedFieldsView)
    {
        $this->listedFieldsView = $listedFieldsView;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }

    //</editor-fold>

    public function lookupByKeyword($key)
    {
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
        }
        $colCtr = 0;
        $this->db->where($criteria);

        $this->db->group_start();

        foreach ($this->listedFieldsSelectItem as $fieldName) {
            $colCtr++;
            if ($colCtr == 1) {
                $this->db->like($fieldName, $key);
            }
            else {
                $this->db->or_like($fieldName, $key);
            }
        }
        $this->db->group_end();
        $this->db->order_by($this->sortBy['kolom'], $this->sortBy['mode']);
        $this->db->join($this->tableName, "$this->tableName.produk_id = $this->tableName2.id");
        $result = $this->db->get($this->tableName2);


        return $result;
    }

    // public function lookupAll()
    // {
    //
    //     $this->db->select('*', "produk.kode as kode");
    //
    //     $criteria = array();
    //     $criteria2 = "";
    //     if (sizeof($this->filters) > 0) {
    //         $this->fetchCriteria();
    //         $criteria = $this->getCriteria();
    //         $criteria2 = $this->getCriteria2();
    //     }
    //     //        arrPrint($criteria);
    //     $this->db->from($this->tableName);
    //     $this->db->join($this->tableName2, 'produk.id = produk_per_supplier.produk_id');
    //     if (sizeof($criteria) > 0) {
    //         $this->db->where($criteria);
    //     }
    //     if ($criteria2 != "") {
    //         $this->db->where($criteria2);
    //     }
    //     if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
    //         $this->db->order_by($this->tableName . "." . $this->sortBy['kolom'], $this->sortBy['mode']);
    //
    //     }
    //     $val = $this->db->get();
    //     //        arrPrint($val);
    //     //        cekHitam($this->db->last_query());
    //     //        die();
    //     return $val;
    // }

    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlSatuan"       => array(
                "id"         => "satuan_id",
                "kolomDatas" => array(
                    "nama" => "satuan_nama",
                ),
            ),
            "MdlProduk"        => array(
                "id"         => "produk_id",
                // "str" => "merek_nama",
                "kolomDatas" => array(
                    "nama" => "produk_nama",
                    "satuan" => "satuan_dasar_nama",
                ),
            ),

        );

        return $mdls;

    }

    public function lookUpRelasiSatuan($produk_id){
        // $produk_id = array("50","21","13");
        if(!isset($this->toko_id)){
            matiHEre("Error unknown toko id on function ".__FUNCTION__);
        }
        $transformKey = array(
            //target=>src
            "produk_id"=>"id",
            "produk_nama"=>"nama",
            // "toko_id"=>"toko_id",
            "satuan_id"=>"satuan_id",
            "satuan_nama"=>"satuan",
            "satuan_dasar_id"=>"satuan_id",
            "satuan_dasar_nama"=>"satuan",

        );

        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlProdukSatuanRelasi");
        $p = new MdlProduk();
        $r = new MdlProdukSatuanRelasi();
        // cekMerah($produk_id);
        //panggil data produk dulu untuk ambil satuan terkecil
        if(is_array($produk_id)){
            $p->addFilter("id in ('".implode("','",$produk_id)."')");
            $p->addFilter("toko_id='".$this->toko_id."'");

            $r->addFilter("produk_id in ('".implode("','",$produk_id)."')");
            // $r->addFilter("toko_id='".$this->toko_id."'");
        }
        elseif($produk_id==""){

            // $p->addFilter("toko_id='".$this->toko_id."'");

        }
        else{
            $p->addFilter("id='$produk_id'");
            // $p->addFilter("toko_id='".$this->toko_id."'");

            $r->addFilter("produk_id='$produk_id'");
            // $r->addFilter("toko_id='".$this->toko_id."'");
        }
        $preProduk = $p->lookUpAll()->result();
        $defaultSatuan = array();
        foreach($preProduk as $temp){
            $prevTem = array();
            foreach($transformKey as $target =>$src){
                $prevTem[$target]=$temp->$src;
            }
            if(!isset($defaultSatuan["qty"])){
                $prevTem["qty"]=1;
            }
            $defaultSatuan[$temp->id][]=$prevTem;
        }

        //region relasi satuan
        $this->db->order_by("qty","asc");
        $relTmp = $r->lookUpAll()->result();
        $tempRealation= array();
        if(sizeof($relTmp)>0){
            foreach($relTmp as $relTmp_0){
                $prevRel = array();
                foreach($transformKey as $oriKey =>$target){
                    $prevRel[$oriKey]=$relTmp_0->$oriKey;
                }
                if(!isset($prevRel["qty"])){
                    $prevRel["qty"]=$relTmp_0->qty;
                }
                $tempRealation[$relTmp_0->produk_id][]=$prevRel;
            }
        }
        // arrprint($tempRealation);
        //endregion
        // arrPrint($defaultSatuan);
        $data = array();
        foreach($defaultSatuan as $PID =>$defaultData){
            if(isset($tempRealation[$PID])){
                $data[$PID] = array_merge($defaultData,$tempRealation[$PID]);
            }
            else{
                $data[$PID]=$defaultData;
            }
        }

        return $data;
    }

}