<?php

class MdlDiskonDraft extends MdlMother
{
    protected $tableName = "diskon_draft";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        // "untuk='diskon_jual'",
        // "jenis='diskon'",
        // "status='1'",
        // "trash='0'"
    );
    protected $ciFilters = array(
        // "jenis" => "bank",
        "status" => "1",
        "trash"  => "0",
    );

    protected $validationRules = array(
        "nilai_1" => array("required", "singleOnly"),
    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"      => array(
            "label"     => "id",
            "type"      => "int",
            "length"    => "24",
            "kolom"     => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "jenis"   => array(
            "label"        => "jenis",
            "type"         => "varchar",
            "length"       => "255",
            "kolom"        => "jenis",
            "inputType"    => "hidden",// hidden
            // "strField"  => "nama",
            // "editable"  => false,
            "defaultValue" => "premi",
        ),
        "nilai_1" => array(
            "label"     => "premi 1",
            "type"      => "int",
            "length"    => "11",
            "kolom"     => "nilai_1",
            "inputType" => "number",// hidden
            // "strField"  => "nama",
            // "editable"  => false,
        ),
        "nilai_2" => array(
            "label"     => "premi 2",
            "type"      => "int",
            "length"    => "11",
            "kolom"     => "nilai_2",
            "inputType" => "number",// hidden
            // "strField"  => "nama",
            // "editable"  => false,
        ),
        "nilai_3" => array(
            "label"     => "premi 3",
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
        // "id" => "id",
        "nama" => "name",


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
}