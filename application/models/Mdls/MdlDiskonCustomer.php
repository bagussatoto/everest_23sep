<?php

class MdlDiskonCustomer extends MdlMother
{
    protected $tableName = "diskon_customer";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        // "untuk='jual'",
        // "jenis='grosir'",
        // "toko_id=''",
        // "status='1'",
        // "trash='0'"
    );
    protected $ciFilters = array(
        // "jenis" => "bank",
        "status" => "1",
        "trash"  => "0",
    );

    public function getCiFilters()
    {
        return $this->ciFilters;
    }

    public function setCiFilters($ciFilters)
    {
        $this->ciFilters = $ciFilters;
    }
    protected $validationRules = array(
        "nilai_1" => array("required", "singleOnly"),
    );
    protected $listedFieldsView = array("nilai_1");
    protected $fields = array(
        "id"      => array(
            "label"     => "id",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "toko_id" => array(
            "label"           => "toko",
            "type"            => "varchar", "length" => "200",
            "kolom"           => "toko_id",
            // "inputType" => "radio",
            "inputType"       => "hidden_ref",
            "reference"       => "MdlToko",
            "referenceFilter" => array("toko_id=toko_id"),
            "referenceSrc"    => "id",
            // "strField"        => "toko_nama",
            // "editable"        => false,
            // "kolom_nama"      => "toko_nama",
            //     //--"inputName" => "folders",
        ),
        "jenis"   => array(
            "label"        => "jenis",
            "type"         => "varchar",
            "length"       => "255",
            "kolom"        => "jenis",
            "inputType"    => "hidden",// hidden
            // "strField"  => "nama",
            // "editable"  => false,
            "defaultValue" => "grosir",
        ),
        "nilai_1" => array(
            "label"     => "Label/Nama Grosir (eg. harga beli > 6)",
            "type"      => "int",
            "length"    => "99",
            "kolom"     => "nilai_1",
            "inputType" => "text",// hidden
            // "strField"  => "nama",
            // "editable"  => false,
        ),
        "nilai_2" => array(
            "label"     => "Qty Min",
            "type"      => "int",
            "length"    => "11",
            "kolom"     => "nilai_2",
            "inputType" => "number",// hidden
            // "strField"  => "nama",
            // "editable"  => false,
        ),
        "nilai_3" => array(
            "label"     => "Qty Max (isi 0 tidak terbatas)",
            "type"      => "int",
            "length"    => "11",
            "kolom"     => "nilai_3",
            "inputType" => "number",// hidden
            // "strField"  => "nama",
            // "editable"  => false,
        ),
        "cabang"  => array(
            "label"      => "cabang",
            "type"       => "int",
            "length"     => "11",
            "kolom"      => "cabang_id",
            "inputType"  => "combo",
            "reference"  => "MdlCabang",
            "strField"   => "nama",
            "kolom_nama" => "cabang_nama",
            // "editable"  => false,
        ),
        "status"  => array(
            "label"        => "status",
            "type"         => "int",
            "length"       => "24",
            "kolom"        => "status",
            "inputType"    => "combo",
            "dataSource"   => array(
                0 => "inactive",
                1 => "active"
            ),
            "defaultValue" => 1,
            //--"inputName" => "status",
        ),


    );
    protected $listedFields = array(
        "nilai_1" => "nama",
    );
    protected $toko_id;
    protected $diskonJenis = array(
        "diskon" => array(
          "birthday",
          "transaksi",
        ),
        "point" => array(
            "point" => array(
                "label" => "point",
            ),
            "point_baru" => array(
                "label" => "point baru",
            ),
        ),
        "cashback" => array(
            "cashback",
        ),
    );
    public function getDiskonJenis()
    {
        return $this->diskonJenis;
    }
    public function setDiskonJenis($diskonJenis)
    {
        $this->diskonJenis = $diskonJenis;
    }


    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

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
    public function __construct()
    {
        // !isset($this->toko_id) ? MatiDisini(__LINE__ . " toko_id harap diset dulu") : "";
        // arrPrint($this->toko_id);
        $this->tableNames = array(
            "grosir" => "diskon",
        );

        $this->filters = array(
            "grosir" => array(
                "jenis" => "produk_grosir",
            )
        );
    }

    public function callHargaJual($produk_ids)
    {
        $premiKoloms = array(
            "nilai_1", "nilai_2", "nilai_3"
        );
        $this->db->where(array("cabang_id !=" => "0"));
        $premies = $this->lookupAll()->result();
        // showLast_query("hijau");
        // arrPrint($premies);

        $premiJuals = array();
        foreach ($premies as $premy) {
            foreach ($premiKoloms as $premiKolom) {

                // $premiJuals[$premy->cabang_id][][$premiKolom] = $premy->$premiKolom;
                $premiJuals[$premy->cabang_id][] = $premy->$premiKolom;
                // $premiJuals[$premy->cabang_id][][$premiKolom."_"] = $premy->$premiKolom / 100;
            }
        }

        // arrPrintPink($premiJuals);

        //-------------------------------------
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();

        if (isset($produk_ids)) {
            $this->db->where_in("produk_id", $produk_ids);
        }

        $src_0 = $hp->lookupAll()->result();
        $srcKoloms = array(
            "jual_nppn", "jual", "hpp",
        );
        // $srcKoloms = array(
        //     "jenis_value",
        // );
        foreach ($src_0 as $speks) {
            $cabang_id = $speks->cabang_id;
            foreach ($srcKoloms as $srcKolom) {

                if ($speks->jenis_value == $srcKolom) {

                    if ($srcKolom == "hpp") {
                        $src_hpp = isset($speks->nilai) ? ($speks->nilai * 1) : 0;

                        $premiCabangs = $premiJuals[$cabang_id];


                        $premi_cacah_1 = $premiCabangs[0] / 100;
                        $premi_cacah_2 = $premiCabangs[1] / 100;
                        $premi_cacah_3 = $premiCabangs[2] / 100;


                        $hrg_1 = $src_hpp * (1 + $premi_cacah_1) * (1 + $premi_cacah_2) * (1 + $premi_cacah_3);


                        // foreach ($premiCabangs as $premi) {
                        //
                        // }
                    }

                    $src[$speks->produk_id][$cabang_id][$srcKolom] = isset($speks->nilai) ? ($speks->nilai * 1) : 0;
                    $src[$speks->produk_id][$cabang_id]["jual_ideal"] = $hrg_1;
                }
            }
        }


        return $src;

    }

    public function callProdukGrosir($produk_ids = "")
    {
        // arrPrint($this->toko_id);
        !isset($this->toko_id) ? MatiDisini(__LINE__ . " toko_id harap diset dulu") : "";
        $table_name = $this->tableNames['grosir'];
        $condites = $this->filters['grosir'] + array(
                "toko_id" => $this->toko_id,
                "trash"   => 0,
                "status"  => 1,
                "minim>"  => 0,
            );
        // arrPrint($condites);
        $this->db->where($condites);
        $src = $this->db->get($table_name)->result();
        // showLast_query("kuning");

        return $src;
    }

    public function saveProdukGrosir($datas)
    {
        $this->tableName = $table_name = $this->tableNames['grosir'];
        $condites = $this->filters['grosir'];
        $condites = array(
            "produk_id" => $datas["produk_id"],
            "minim"     => $datas["minim"],
            "persen"    => $datas["persen"],
            "trash"     => 0,
            "status"    => 1,
        );
        $this->db->where($condites);
        $srcs = $this->db->get($table_name)->result();
        showLast_query("hijau");

        if (sizeof($srcs) == 0) {
            // $data_new = array(
            //         "jenis" => "produk_grosir"
            //     ) + $datas;
            //
            // $this->db->insert($table_name, $data_new);
        }
        else {
            cekBiru("update grosir");
            $datas_upd = array(
                "trash" => 1,
                "trash_dtime" => dtimeNow(),
                "trash_author" => my_id(),
            );
            $wheres = array(
                "produk_id" => $datas["produk_id"],
                // "minim"     => $datas["minim"],
                // "persen"     => $datas["persen"],
            );
            $this->db->where($wheres);
            $this->db->update($this->tableName, $datas_upd);

            // $this->deleteProdukGrosir($datas["produk_id"]);
            showLast_query("merah");
        }

        $data_new = array(
                "jenis" => "produk_grosir"
            ) + $datas;

        $this->db->insert($table_name, $data_new);


        // $this->updateData($condites, $data_upd);
    }

    public function deleteProdukGrosir($id_data)
    {
        $this->tableName = $table_name = $this->tableNames['grosir'];
        $datas = array(
            "trash"        => 1,
            "trash_dtime"  => dtimeNow(),
            "trash_author" => my_id(),
        );
        $wheres = array(
            "id"    => $id_data,
            "trash" => 0,
        );
        $this->db->where($wheres);
        $this->db->update($this->tableName, $datas);

        return 1;
    }

    public function callDiskonPoint(){
        $condites = array(
          "tipe" => "point",
        );
        $this->db->where($condites);
        // $tmp = $this->lookupAll()->result();
        $tmp = $this->db->get($this->tableName)->result();

        return $tmp;
    }

    //---------------------------------------start
    public function callDiskon(){
        $condites = array(
            "tipe" => "diskon_kategori",
        );
        $this->db->where($condites);
        // $tmp = $this->lookupAll()->result();
        $tmp = $this->db->get($this->tableName)->result();

        return $tmp;
    }

    public function callDiskonAktive(){
        $array = array(
          "trash" => 0
        );
        $this->db->where($array);
        $tmp = $this->callDiskon();
        $dc_params = [];
        foreach ($tmp as $dcu_src) {
            $dc_urutan = $dcu_src->urutan;
            $dc_tipe = $dcu_src->tipe;
            $dc_jenis = $dcu_src->jenis;
            $dc_nilai = $dcu_src->nilai;
            $dc_minim = $dcu_src->minim;

            $dc_params[$dc_jenis][$dc_urutan]['id'] = $dcu_src->id;
            $dc_params[$dc_jenis][$dc_urutan]['minim'] = $dc_minim;
            $dc_params[$dc_jenis][$dc_urutan]['maxim'] = $dcu_src->maxim;
            $dc_params[$dc_jenis][$dc_urutan]['nilai'] = $dc_nilai;
        }

        return $dc_params;
    }
    //---------------------------------------stop-------
}