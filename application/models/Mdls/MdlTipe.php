<?php

class MdlTipe extends MdlMother
{
    protected $tableName = "tipe";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("status='1'", "trash='0'");

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"     => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "nama"   => array(
            "label"     => "nama",
            "type"      => "int",
            "length"    => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
            // "reference"  => false,
            "strField"  => "nama",
            // "editable"        => false,
            // "kolom_nama"      => "cabang_nama",
        ),
        "skala"             => array(
            "label"      => "size/skala",
            "type"       => "int",
            "length"     => "255",
            "kolom"      => "size_id",
            "inputType"  => "combo",
            "reference"  => "MdlProdukSize",
            "referenceFilter"  => array(
                "id" => array(
                    "var" => "size_id"
                )
            ),
            "strField"   => "nama",
            "editable"   => true,
            "kolom_nama" => "size_nama",
            "add_btn"    => true,
        ),
        "skala_nama"        => array(
            "label"     => "skala",
            "type"      => "int",
            "length"    => "255",
            "kolom"     => "size_nama",
            "inputType" => "hidden",
            // "kolom_nama" => "kategori_nama",
        ),
        "status" => array(
            "label"        => "status",
            "type"         => "int", "length" => "24", "kolom" => "status",
            "inputType"    => "combo",
            "dataSource"   => array(0 => "inactive", 1 => "active"),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "nama" => "nama",
        "size_nama" => "size/skala",


    );

    //region gs
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
    //endregion

    protected $pairValidate = array("nama");

    public function getPairValidate()
    {
        return $this->pairValidate;
    }

    public function setPairValidate($pairValidate)
    {
        $this->pairValidate = $pairValidate;
    }

    public function paramSyncNamaNama()
    {
        $mdls = array(
            // "MdlSatuan" => array(
            //     "id"         => "satuan_id",    // kolom_src => kolom_target (berisi id src)
            //     // "str" => "folders_nama",
            //     "kolomDatas" => array(
            //         "satuan" => "satuan",       // kolom_data => kolom_target (berisi nama)
            //     ),
            // ),
            // "MdlFolderProduk"   => array(
            //     "id"         => "folders",
            //     // "str" => "merek_nama",
            //     "kolomDatas" => array(
            //         "nama" => "folders_nama",
            //     ),
            // ),
            // "MdlProdukKategori" => array(
            //     "id"         => "kategori_id",
            //     // "str" => "merek_nama",
            //     "kolomDatas" => array(
            //         "nama" => "kategori_nama",
            //     ),
            // ),
            // "MdlMerek"          => array(
            //     "id"         => "merek_id",
            //     // "str" => "kendaraan_nama",
            //     "kolomDatas" => array(
            //         "nama" => "merek_nama",
            //     ),
            // ),
            // // "MdlLokasiIndex"  => array(
            // //     "id"  => "lokasi",
            // //     // "str" => "lokasi_nama",
            // //     "kolomDatas" => array(
            // //         "nama" => "lokasi_nama",
            // //     ),
            // // ),
            // "MdlProdukJenis"    => array(
            //     "id"         => "produk_jenis_id",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "kode"  => "produk_jenis_nama",
            //         "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            // "MdlSatuan"         => array(
            //     "id"         => "satuan_id",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "kode" => "satuan",
            //         // "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            // "MdlPhase"          => array(
            //     "id"         => "phase_id",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "phase_nama",
            //         // "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            // "MdlModelOutdoor"   => array(
            //     "id"         => "outdoor_id",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "outdoor_nama",
            //         // "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            // "MdlModelIndoor_1"  => array(
            //     "id"         => "indoor_id_1",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "indoor_nama_1",
            //         // "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            // "MdlModelIndoor_2"  => array(
            //     "id"         => "indoor_id_1",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "indoor_nama_2",
            //         // "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            // "MdlModelIndoor_3"  => array(
            //     "id"         => "indoor_id_1",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "indoor_nama_3",
            //         // "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            // "MdlSupplier"       => array(
            //     "id"         => "supplier_id",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "supplier_nama",
            //         // "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            "MdlProdukSize"     => array(
                "id"         => "size_id",
                // "str" => "lokasi_nama",
                "kolomDatas" => array(
                    "nama" => "size_nama",
                    // "nilai" => "produk_jenis_nilai",
                ),
            ),
            // "MdlKapasitas"      => array(
            //     "id"         => "kapasitas_id",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "kapasitas_nama",
            //         // "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            // "MdlTipe"           => array(
            //     "id"         => "tipe_id",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "tipe_nama",
            //         // "nilai" => "produk_jenis_nilai",
            //     ),
            // ),
            // "MdlProdukSeries"   => array(
            //     "id"         => "series_id",
            //     "kolomDatas" => array(
            //         "nama" => "series_nama",
            //     ),
            // ),
            // "MdlProdukSubKategori"   => array(
            //     "id" => "sub_kategori_id",
            //     "kolomDatas" => array(
            //         "label" => "sub_kategori_nama",
            //     ),
            // ),
        );

        return $mdls;

    }
}