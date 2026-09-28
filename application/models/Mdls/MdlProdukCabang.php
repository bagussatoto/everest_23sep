<?php

//--include_once "MdlHistoriData.php";
class MdlProdukCabang extends MdlMother
{
    protected $tableName = "produk_cabang";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array("jenis='item'", "status='1'", "trash='0'");
    /* -----------------------------------------------------------------------------------------
     * "cabang_id"  => "session.my_cabang_id",
     * akan diexplode pada MdlMother diambil string setelah titik merupakan funsi dari helper
     * -----------------------------------------------------------------------------------------*/
    protected $ciFilters = array(
        "jenis"     => "item",
        "status"    => "1",
        "trash"     => "0",
        "cabang_id" => "session.my_cabang_id",
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "produk_nama"  => "nama",
        "kode"         => "kode",
        "keterangan"   => "keterangan",
        "label"        => "label",
        "no_part"      => "no_part",
        "folders_nama" => "folders_nama",
    );
    protected $validationRules = array(
        "nama"          => array("required", "singleOnly"),
        "lebar_gross"   => array("required", "singleOnly"),
        "panjang_gross" => array("required", "singleOnly"),
        "tinggi_gross"  => array("required", "singleOnly"),
        "berat_gross"   => array("required", "singleOnly"),
        //        "status" => array("required"),
    );

    protected $listedFieldsView = array("nama");
    protected $kolomAlt = true;
    protected $fields = array(
        "id"           => array(
            "label"     => "id",
            "type"      => "int", "length" => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "cabang_nama"  => array(
            "label"        => "cabang",
            "type"         => "int",
            "length"       => "24",
            "kolom"        => "cabang_id",
            "inputType"    => "text",
            "editable"     => false,
            "reference"    => "MdlCabang",
            "strField"     => "nama",
            "kolom_nama"   => "cabang_nama",
            "defaultValue" => ".my_cabang_id",
        ),
        "folders_nama" => array(
            "label"      => "kategori",
            "type"       => "int", "length" => "24",
            "kolom"      => "folders",
            // "kolom_alt" => "folders_nama",
            "inputType"  => "text",
            "editable"   => false,
            "reference"  => "MdlFolderProduk",
            "strField"   => "nama",
            "kolom_nama" => "folders_nama",
        ),
        "produk_nama"  => array(
            "label"      => "nama produk",
            "type"       => "int",
            "length"     => "24",
            "kolom"      => "produk_id",
            "inputType"  => "text",
            "editable"   => false,
            // "reference"  => "MdlProduk",
            // "strField"   => "nama",
            "kolom_nama" => "produk_nama",
            //--"inputName" => "nama",
        ),
        "kode"         => array(
            "label"     => "kode",
            "type"      => "int", "length" => "24", "kolom" => "kode",
            // "inputType" => "hidden",
            "inputType" => "combo",
            "editable"  => false,
            //--"inputName" => "kode",
        ),
        "no_part"      => array(
            "label"     => "no part",
            "type"      => "int", "length" => "24", "kolom" => "no_part",
            "inputType" => "combo",
            // "inputType" => "hidden",
            "editable"  => false,
            //--"inputName" => "satuan",
        ),
        // "label"          => array(
        //     "label"     => "label",
        //     "type"      => "int", "length" => "24", "kolom" => "label",
        //     "inputType" => "text",
        //     //--"inputName" => "label",
        // ),
        // "type_nama"      => array(
        //     "label"     => "type",
        //     "type"      => "text",
        //     "length"    => "32",
        //     "kolom"     => "type_nama",
        //     "inputType" => "text",
        // ),
        // "model_nama"     => array(
        //     "label"     => "model",
        //     "type"      => "text",
        //     "length"    => "32",
        //     "kolom"     => "model_nama",
        //     "inputType" => "text",
        // ),
        // "tahun"          => array(
        //     "label"     => "tahun",
        //     "type"      => "text",
        //     "length"    => "8",
        //     "kolom"     => "tahun",
        //     "inputType" => "text",
        // ),
        "barcode"      => array(
            "label"          => "barcode",
            "type"           => "int",
            "length"         => "24",
            "kolom"          => "barcode",
            "inputType"      => "hidden",
            "transformValue" => "JsBarcode",
        ),
        // "keterangan"     => array(
        //     "label"     => "keterangan",
        //     "type"      => "varchar",
        //     "length"    => "255",
        //     "kolom"     => "keterangan",
        //     "inputType" => "text",
        //     //--"inputName" => "",
        // ),
        // "deskripsi"      => array(
        //     "label"     => "deskripsi",
        //     "type"      => "int", "length" => "24", "kolom" => "deskripsi",
        //     "inputType" => "text",
        //     //--"inputName" => "",
        // ),
        "satuan"       => array(
            "label" => "satuan",

            "type"      => "int", "length" => "24", "kolom" => "satuan",
            "editable"  => false,
            "inputType" => "text",
            //            "dataSource" => array(
            //                "pcs" => "piece",
            //                "unit" => "unit"),
            //--"inputName" => "satuan",
            "reference" => "MdlSatuan",
            "attr"      => "class='text-center'",
        ),
        "merek_nama"   => array(
            "label"      => "merek",
            "type"       => "int",
            "length"     => "24",
            "kolom"      => "merek_id",
            "inputType"  => "text",
            "editable"   => false,
            "reference"  => "MdlMerek",
            "strField"   => "nama",
            "kolom_nama" => "merek_nama",
            // "inputType" => "hidden",
        ),
        // "kendaraan_nama" => array(
        //     "label"      => "kendaraan",
        //     "type"       => "int",
        //     "length"     => "24",
        //     "kolom"      => "kendaraan_id",
        //     "inputType"  => "combo",
        //     "editable"   => true,
        //     "reference"  => "MdlKendaraan",
        //     "strField"   => "nama",
        //     "kolom_nama" => "kendaraan_nama",
        // ),
        "lokasi_nama"  => array(
            "label"             => "lokasi/rak",
            "type"              => "int",
            "length"            => "24",
            "kolom"             => "lokasi",
            "inputType"         => "combo",
            "editable"          => true,
            "reference"         => "MdlRakCabang",
            "referenceFilter_2" => array(
                "cabang_id" => ".my_cabang_id"
            ),
            "strField"          => "nama",
            "kolom_nama"        => "lokasi_nama",
        ),
        // "lokasi"     => array(
        //     "label"     => "lokasi rak",
        //     "type"      => "text",
        //     "length"    => "24",
        //     "kolom"     => "",
        //     "inputType" => "text",
        //     "editable"  => false,
        // ),
        "lokasi_gang_nama"  => array(
            "label"             => "gang",
            "type"              => "int",
            "length"            => "24",
            "kolom"             => "lokasi_gang_id",
            "inputType"         => "combo",
            "editable"          => true,
            "reference"         => "MdlLokasiGang",
            "referenceFilter_2" => array(
                "cabang_id" => ".my_cabang_id"
            ),
            "strField"          => "nama",
            "kolom_nama"        => "lokasi_gang_nama",
        ),
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
        // "panjang_ckd" => array(
        //     "label"     => "CKD length (in millimeters)",
        //     "type"      => "int", "length" => "24", "kolom" => "panjang_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "lebar_ckd"   => array(
        //     "label"     => "CKD width (in millimeters)",
        //     "type"      => "int", "length" => "24", "kolom" => "lebar_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "tinggi_ckd"  => array(
        //     "label"     => "CKD height (in millimeters)",
        //     "type"      => "int", "length" => "24", "kolom" => "tinggi_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        // "berat_ckd"   => array(
        //     "label"     => "CKD weight (in grams)",
        //     "type"      => "int", "length" => "24", "kolom" => "berat_gross",
        //     "inputType" => "number",
        //     //--"inputName" => "berat",
        // ),
        "status"       => array(
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
        // "id"         => "pID",
        "folders_nama" => "kategori",
        "merek_nama"   => "merek",
        "kode"         => "kode produk",
        // "label"        => "label",
        "no_part"      => "part no",
        "produk_nama"  => "nama/deskripsi",
        // "keterangan"   => "keterangan",
        "satuan"       => "satuan",
        "lokasi_nama"  => "lokasi/rak",
        "lokasi_gang_nama"  => "lokasi/rak gang",
        "lokasi_gang_ruang_nama"  => "lokasi/rak gang",
        "barcode"      => "barcode",
        // "images"      => "image",
    );
    protected $autoFillFields = array(
        "volume_gross" => "lebar_gross*panjang_gross*tinggi_gross",
    );
    protected $order_column = array(
        "id",
        "folders_nama",
        "kode",
        "label",
        "no_part",
        "nama",
        "keterangan",
        "satuan",
        "barcode",
    );
    protected $pairedData = array(
        "MdlImages" => array(
            "kolom" => "images",
            "label" => "image",
            "link"  => "image"
        ),
    );
    protected $navFilters = array(
        "label"     => "lokasi/rak",
        "mdlFilter" => "MdlRakCabang",
        "kolomKey"  => "lokasi",
    );

    public function getNavFilters()
    {
        return $this->navFilters;
    }

    public function setNavFilters($navFilters)
    {
        $this->navFilters = $navFilters;
    }

    public function isKolomAlt()
    {
        return $this->kolomAlt;
    }

    public function setKolomAlt($kolomAlt)
    {
        $this->kolomAlt = $kolomAlt;
    }

    public function getPairedData()
    {
        return $this->pairedData;
    }

    public function setPairedData($pairedData)
    {
        $this->pairedData = $pairedData;
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

    public function callProdukStok($produk_id = "", $cabang_id = "")
    {
        $tbl = "_rek_pembantu_produk_cache";

        $condites_0 = array(
            "periode" => "forever",
        );
        $condites_produk = array();
        if ($produk_id > 0) {
            $condites_produk = array(
                "extern_id" => $produk_id,
            );
        }
        $condites_cabang = array();
        if ($cabang_id != 0) {
            $condites_cabang = array(
                "cabang_id" => $cabang_id,
            );
        }
        $kolom_qty = "qty_" . detectRekDefaultPosition("persediaan produk");
        // $this->setFilters(array());
        $condites = $condites_0 + $condites_produk + $condites_cabang;
        // $produks = $this->lookupByCondition($condites)->result();
        $this->db->where($condites);
        $produks = $this->db->get($tbl)->result();
        // arrPrintPink($produks);
        // cekHijau($kolom_qty);

        $nonFireProduks = array();
        $fireProduks = array();
        $stok_all = 0;
        foreach ($produks as $produkSrc) {
            $stok_all += $produkSrc->$kolom_qty;
            $stok_cabang[$produkSrc->cabang_id] = $produkSrc->$kolom_qty;
        }
        $vars['all'] = $stok_all;
        $vars['cabang'] = $stok_cabang;
        // arrPrintWebs($vars);

        return $vars;
    }

    public function callSpecs($produkIds = "")
    {
        $selecteds = array(
            "id",
            "kode",
            "produk_id",
            "produk_nama",
            "label",
            "folders_nama",
            "barcode",
            "no_part",
            "merek_nama",
            "model_nama",
            "type_nama",
            "tahun",
            "lokasi",
            "lokasi_nama",
            "satuan",
        );
        $this->db->select($selecteds);

        // if (isset($produkIds)) {
        if (is_array($produkIds)) {
            $this->db->where_in("produk_id", $produkIds);
        }
        else {
            $this->db->where("produk_id", $produkIds);
        }

        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->produk_id] = $item;
        }


        return $vars;
    }

    public function paramSyncNamaNama()
    {
        $mdls = array(
            "MdlProduk" => array(
                "id"         => "produk_id",
                // "str" => "folders_nama",
                "kolomDatas" => array(
                    "kode"         => "kode",
                    "nama"         => "produk_nama",
                    "merek_nama"   => "merek_nama",
                    "barcode"      => "barcode",
                    "folders"      => "folders",
                    "folders_nama" => "folders_nama",
                    "satuan"       => "satuan",
                    "no_part"      => "no_part",
                ),
            ),
            // "MdlMerek"        => array(
            //     "id"  => "merek_id",
            //     // "str" => "merek_nama",
            //     "kolomDatas" => array(
            //         "nama" => "merek_nama",
            //     ),
            // ),
            // "MdlKendaraan"    => array(
            //     "id"  => "kendaraan_id",
            //     // "str" => "kendaraan_nama",
            //     "kolomDatas" => array(
            //         "nama" => "kendaraan_nama",
            //     ),
            // ),
            // "MdlLokasiIndex"  => array(
            //     "id"  => "lokasi",
            //     // "str" => "lokasi_nama",
            //     "kolomDatas" => array(
            //         "nama" => "lokasi_nama",
            //     ),
            // ),
        );

        return $mdls;

    }

    public function callProdukDlmRak($cabang_id,$rak_id = "")
    {
        $condites = array(
            "cabang_id" => $cabang_id,
            // "cabang_id" => $cabang_id,
        );
        if($rak_id > 0){
            $condites["lokasi"] = $rak_id;
        }
        $srcs = $this->lookupByCondition($condites)->result();
        // showLast_query("kuning");
        // arrPrint($srcs);
        $rakes = array();
        foreach ($srcs as $src) {
            $lokasi = $src->lokasi;
            $produk_id = $src->produk_id;

            if($lokasi > 0){
                $rakes[$lokasi][] = $produk_id;
            }
        }
        // cekBiru($rakes);
        $isiRaks = array();
        foreach ($rakes as $rakId => $rake) {
            $isiRaks[$rakId] = sizeof($rake);
        }
        // cekHijau($isiRaks);

        $vars = array();
        $vars["sum"] = $isiRaks;
        $vars["datas"] = $srcs;

        return $vars;
    }
}