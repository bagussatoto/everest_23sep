<?php

//--include_once "MdlHistoriData.php";
class MdlProdukKomposit extends MdlMother
{
    protected $tableName = "produk";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "produk.jenis='item_komposit'",
        "produk.status='1'",
        "produk.trash='0'"
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "nama" => "nama",
        "kode" => "kode",
        "keterangan" => "keterangan",
        "label" => "label",
        "no_part" => "no_part",
        "folders_nama" => "folders_nama",
    );
    protected $validationRules = array(
        "kategori_id" => array("required"),
        "nama" => array("required", "unique"),
//        "lebar_gross" => array("required", "singleOnly"),
//        "panjang_gross" => array("required", "singleOnly"),
//        "tinggi_gross" => array("required", "singleOnly"),
//        "berat_gross" => array("required", "singleOnly"),
        "kode" => array("required", "unique"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24",
            "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "jenis" => array(
            "label" => "id",
            "type" => "int",
            "length" => "24",
            "kolom" => "jenis",
            "inputType" => "hidden",// hidden
        ),
        "kategori" => array(
            "label" => "kategori",
            "type" => "int", "length" => "24", "kolom" => "kategori_id",
            "inputType" => "combo-hidden",
            "dataSource" => array(4 => "jasa"), "defaultValue" => 4,
            "editable" => false,
            //--"inputName" => "status",
        ),

        "allow_project" => array(
            "label" => "ijinkan project",
            "type" => "int", "length" => "24", "kolom" => "allow_project",
            "inputType" => "combo",
            "checbox" => true,
            // "checbox_disabled" => array("kategori_id" => 4),
            "checbox_fungsi" => "checkboxUpdProject",
            "dataSource" => array(0 => "no", 1 => "yes"),
            "defaultValue" => 0,
            //--"inputName" => "status",
        ),

//        "kategori" => array(
//            "label" => "category",
//            "type" => "int", "length" => "255", "kolom" => "kategori_id",
//            "inputType" => "combo",
//            "reference" => "MdlProdukKategori",
//
//            "strField" => "nama",
//            "editable" => true,
//            "kolom_nama" => "kategori_nama",
//        ),
//        "folder" => array(
//            "label" => "folder",
//            "type" => "int", "length" => "255", "kolom" => "folders",
//            "inputType" => "combo",
//            "reference" => "MdlFolderProduk",
//
//        ),
        "kode" => array(
            "label" => "kode",
            "type" => "int", "length" => "24", "kolom" => "kode",
            "inputType" => "text",
            //--"inputName" => "kode",
        ),
        "label" => array(
            "label" => "label",
            "type" => "int", "length" => "24", "kolom" => "label",
            "inputType" => "text",
            //--"inputName" => "label",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "varchar", "length" => "255", "kolom" => "nama",
            "inputType" => "text",
            //--"inputName" => "nama",
        ),
        "keterangan" => array(
            "label" => "keterangan",
            "type" => "varchar", "length" => "255", "kolom" => "keterangan",
            "inputType" => "text",
            //--"inputName" => "",
        ),
        "deskripsi" => array(
            "label" => "deskripsi",
            "type" => "int", "length" => "24", "kolom" => "deskripsi",
            "inputType" => "text",
            //--"inputName" => "",
        ),

        "satuan" => array(
            "label" => "satuan",

            "type" => "int", "length" => "24", "kolom" => "satuan",
            "inputType" => "combo",
            //            "dataSource" => array(
            //                "pcs" => "piece",
            //                "unit" => "unit"),
            //--"inputName" => "satuan",
            "reference" => "MdlSatuan",
            "attr" => "class='text-center'",
        ),
        "nopart" => array(
            "label" => "no part",
            "type" => "int", "length" => "24", "kolom" => "no_part",
            "inputType" => "text",
            //--"inputName" => "satuan",
        ),

        //        "disc_cat" => array(
        //            "label" => "discount cat",
        //            "type" => "int", "length" => "24", "kolom" => "diskon_kategori",
        //            "inputType" => "combo",
        //            "dataSource" => array("folder", "item"),
        //            //--"inputName" => "jenis",
        //        ),

        //        "berat" => array(
        //            "label" => "weight (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "berat",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "panjang" => array(
        //            "label" => "length (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "panjang",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "lebar" => array(
        //            "label" => "width (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "lebar",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "tinggi" => array(
        //            "label" => "height (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "tinggi",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),
        //        "volume" => array(
        //            "label" => "volume (CBU)",
        //            "type" => "int", "length" => "24", "kolom" => "volume",
        //            "inputType" => "number",
        //            //--"inputName" => "berat",
        //        ),

//        "panjang_ckd" => array(
//            "label" => "CKD length (in millimeters)",
//            "type" => "int", "length" => "24", "kolom" => "panjang_gross",
//            "inputType" => "number",
//            //--"inputName" => "berat",
//        ),
//        "lebar_ckd" => array(
//            "label" => "CKD width (in millimeters)",
//            "type" => "int", "length" => "24", "kolom" => "lebar_gross",
//            "inputType" => "number",
//            //--"inputName" => "berat",
//        ),
//        "tinggi_ckd" => array(
//            "label" => "CKD height (in millimeters)",
//            "type" => "int", "length" => "24", "kolom" => "tinggi_gross",
//            "inputType" => "number",
//            //--"inputName" => "berat",
//        ),
//        "berat_ckd" => array(
//            "label" => "CKD weight (in grams)",
//            "type" => "int", "length" => "24", "kolom" => "berat_gross",
//            "inputType" => "number",
//            //--"inputName" => "berat",
//        ),
//        "status" => array(
//            "label" => "status",
//            "type" => "int", "length" => "24", "kolom" => "status",
//            "inputType" => "combo",
//            "dataSource" => array(0 => "inactive", 1 => "active"), "defaultValue" => 1,
//            //--"inputName" => "status",
//        ),


        //        "harga" => array(
        //            "label" => "harga",
        //            "type" =>"int","length"=>"24","kolom" => "harga",
        //            "inputType" => "number",
        //            //--"inputName" => "harga",
        //        ),
        //        "dtime" => array(
        //            "label" => "dtime",
        //            "type" =>"int","length"=>"24","kolom" => "dtime",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "oleh name" => array(
        //            "label" => "pic",
        //            "type" =>"int","length"=>"24","kolom" => "oleh_name",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //
        //        "komposisi" => array(
        //            "label" => "komposisi",
        //            "type" =>"int","length"=>"24","kolom" => "komposisi",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "produk dasar" => array(
        //            "label" => "bahan",
        //            "type" =>"int","length"=>"24","kolom" => "produk_dasar_nama",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "jumlah" => array(
        //            "label" => "jumlah",
        //            "type" =>"int","length"=>"24","kolom" => "jml",
        //            "inputType" => "text",
        //            //--"inputName" => "",
        //        ),
        //        "bahan utama" => array(
        //            "label" => "bahan utama",
        //            "type" =>"int","length"=>"24","kolom" => "bahan_utama",
        //            "inputType" => "combo",
        //            //--"inputName" => "bahan_utama",
        //        ),
    );
    protected $listedFields = array(
        "id" => "pID",
        "allow_project" => "ijinkan project",//ijinkan project
        "kategori_id" => "kategori",
        "folders" => "folder",
        "kode" => "kode produk",
        "label" => "label",
        "no_part" => "no part",
        "nama" => "nama/deskripsi",
        "keterangan" => "keterangan",
        "satuan" => "satuan",
    );
    protected $autoFillFields = array(
        "volume_gross" => "lebar_gross*panjang_gross*tinggi_gross",
    );

    protected $excelFields = array(
        // "folders_nama" => array(
        //     "label" => "kategori",
        //     "type"  => "string",
        // ),
        "id" => array(
            "label" => "pID",
            "type" => "integer",
        ),
        "kode" => array(
            "label" => "kode",
            "type" => "string",
        ),
        "no_part" => array(
            "label" => "part",
            "type" => "string",
        ),
        "nama" => array(
            "label" => "produk",
            "type" => "string",
        ),
        "keterangan" => array(
            "label" => "note",
            "type" => "string",
        ),
        "berat_gross" => array(
            "label" => "berat",
            "type" => "integer",
        ),
        "lebar_gross" => array(
            "label" => "lebar",
            "type" => "integer",
        ),
        "panjang_gross" => array(
            "label" => "panjang",
            "type" => "integer",
        ),
        "tinggi_gross" => array(
            "label" => "tinggi",
            "type" => "integer",
        ),
        "pic" => array(
            "label" => "images",
            "type" => "string",
            "replacer" => "hahaha",
        ),
        /* -------------- ----------------------------------
         * key masih manual untuk pairing dr data tambahan
         * ----------------------- ------------------------*/
        "vendor" => array(
            "label" => "vendor",
            "type" => "string",
        ),
    );

    /* ----------------------------------------------------- -----------------------------
     * dengan adanya methode ini akan metriger munculnya tombol download xlsx di GUI
     * berpasangan dengan method excelFields diatasnya ini
     * ----------------------------------- ----------------------------------------------*/
    protected $excelWriters = array(
        "namaFile" => "data_produk",
        "dataTambahan" => "MdlProdukPerSupplier",
        "dataTambahanBase" => "MdlSupplier",
        "dataTambahanFields" => array(
            "produk_id",
            "suppliers_id"
        ),
        "dataImage" => "MdlImages",
        "dataImageFields" => array(
            "parent_id" => "files"
        ),
    );

    public function getExcelFields()
    {
        return $this->excelFields;
    }

    public function setExcelFields($excelFields)
    {
        $this->excelFields = $excelFields;
    }

    public function getExcelWriters()
    {
        return $this->excelWriters;
    }

    public function setExcelWriters($excelWriters)
    {
        $this->excelWriters = $excelWriters;
    }

    public function getAutoFillFields()
    {
        return $this->autoFillFields;
    }

    public function setAutoFillFields($autoFillFields)
    {
        $this->autoFillFields = $autoFillFields;
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

    public function updateLimit($produk_id, $limit)
    {
        $tbl = $this->tableName;
        $arrSet = array(
            "limit" => $limit,
        );
        $this->db->set($arrSet);
        $this->db->where("id", $produk_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function updateLeadTime($produk_id, $nilai)
    {
        $tbl = $this->tableName;
        $arrSet = array(
            "lead_time" => $nilai,
        );
        $this->db->set($arrSet);
        $this->db->where("id", $produk_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function updateIndeks($produk_id, $nilai)
    {
        $tbl = $this->tableName;
        $arrSet = array(
            "indeks" => $nilai,
        );
        $this->db->set($arrSet);
        $this->db->where("id", $produk_id);
        $var = $this->db->update($tbl);

        return $var;
    }

    public function callProdukFire()
    {
        $fireKategories = array(
            "143"
        );
        $produks = $this->lookupAll()->result();

        $nonFireProduks = array();
        $fireProduks = array();
        foreach ($produks as $produkSrc) {
            if (in_array($produkSrc->folders, $fireKategories)) {
                $fireProduks[] = $produkSrc;
            }
            else {
                $nonFireProduks[] = $produkSrc;
            }
        }
        $vars['fire'] = $fireProduks;
        $vars['nonFire'] = $nonFireProduks;

        return $vars;
    }

}