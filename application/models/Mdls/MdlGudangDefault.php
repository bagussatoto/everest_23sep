<?php

//--include_once "MdlHistoriData.php";

//class MdlGudangDefault extends MdlMother
class MdlGudangDefault extends MdlMother_static
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "status='1'",
        "trash='0'",
    );
    protected $rawFilters = array();

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id" => array(
            "label" => "id",
            "type" => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "name" => array(
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "name",
            "inputType" => "text",
        ),
        "nama" => array(
            "label" => "nama",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "status" => array(
            "label" => "trash",
            "type" => "int", "length" => "24", "kolom" => "status",
            "inputType" => "text",
        ),
        "trash" => array(
            "label" => "trash",
            "type" => "int", "length" => "24", "kolom" => "trash",
            "inputType" => "text",
        ),
        "cabang_id" => array(
            "label" => "cabang",
            "type" => "int", "length" => "24", "kolom" => "cabang_id",
            "inputType" => "number",
        ),
    );
    protected $staticData = array();


    protected $listedFields = array(
        "nama" => "name",
        "due_days" => "due days",
        "status" => "status",

    );

    public function __construct()
    {
        $this->load->model("Mdls/MdlCabang");
        $o = new MdlCabang();
        $o->addFilter("id<>'-1'");
        $tmp = $o->lookupAll()->result();

        $filterSplitters = array("=", "<>");
        $this->rawFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                foreach ($filterSplitters as $spl) {
                    if (strpos($f, $spl) !== false) {
                        $ex = explode($spl, $f);
                        if (sizeof($ex) > 1) {
                            $this->rawFilters[$ex[0]] = trim($ex[1], "'");
                        }
                    }
                }
            }
        }

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $gudSpec = getDefaultWarehouseID($row->id);
                $tmpArr = array(
                    "id" => $gudSpec['gudang_id'],
                    "name" => $gudSpec['gudang_nama'],
                    "nama" => $gudSpec['gudang_nama'],
                    "cabang_id" => $row->id,
                );
                $this->staticData[$row->id] = $tmpArr;

                if (sizeof($this->rawFilters) > 0) {
                    foreach ($this->rawFilters as $k => $v) {
                        $this->staticData[$row->id][$k] = isset($row->$k) ? $row->$k : "";
                    }
                }
            }
        }

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


    public function getRawFilters()
    {
        return $this->rawFilters;
    }

    public function setRawFilters(array $rawFilters)
    {
        $this->rawFilters = $rawFilters;
    }



    //endregion


    //@override with static data
//    public function lookupAll()
//    {
//        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
////            cekkuning("ada isinya");
//            $iCtr = 0;
//            $sql = "";
////			arrprint($this->filters);
//            foreach ($this->staticData as $iSpec) {
//                $iCtr++;
//                $sql .= 'SELECT ';
//                $fCtr = 0;
//                foreach ($this->fields as $fID => $fSpec) {
//                    $fCtr++;
//                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
//                    if ($fCtr < sizeof($this->fields)) {
//                        $sql .= ",";
//                    }
//                }
//                if ($iCtr < sizeof($this->staticData)) {
//                    $sql .= " union ";
//                }
//            }
////            cekkuning($sql);
//            return $this->db->query($sql);
//        }
//        else {
////            cekkuning("TIDAK ada isinya");
//            return null;
//        }
//
//    }


    public function lookupAll()
    {
        $filterSplitters = array("=", "<>");
        $rawFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                foreach ($filterSplitters as $spl) {
                    if (strpos($f, $spl) !== false) {
                        $ex = explode($spl, $f);
                        if (sizeof($ex) > 1) {
                            $rawFilters[$ex[0]] = trim($ex[1], "'");
                        }
                    }
                }
            }

        }

        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {
//            cekkuning("STATIC ada isinya");

            $iCtr = 0;
            $sql = "";
            foreach ($this->staticData as $iSpec) {
//                arrPrint($iSpec);
                $included = sizeof($rawFilters) > 0 ? false : true;
                $iCtr++;
                $subSql = 'SELECT ';
                $fCtr = 0;
                $inclCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {
//                    cekbiru("checking $fID");
                    $fCtr++;
                    $subSql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $subSql .= ",";
                    }

                    if (sizeof($rawFilters) > 0) {
//                        cekOrange("$fID");
                        if (array_key_exists($fID, $rawFilters) && $iSpec[$fID] == $rawFilters[$fID]) {
                            $included = true;
                            $inclCtr++;
//                            cekbiru($iSpec[$fID]."/$fID included");
                        }
                        else {
//                            cekmerah($iSpec[$fID]."/$fID NOT included");
                        }
                    }
                    else {
//                        cekbiru("NO RAW FILTER");
                    }
                }

//                if ($iCtr < sizeof($this->staticData)) {
//                    $subSql .= " union ";
//                }

                $subSql .= " union ";
//cekHitam(":: $subSql ::");

                if (sizeof($rawFilters) > 0) {
//                    cekHitam("$inclCtr :: " . sizeof($rawFilters));
                    if ($inclCtr >= sizeof($rawFilters)) {

                        $sql .= $subSql;
                    }
//                    else{
//                        cekMerah("$inclCtr :: ". sizeof($rawFilters));
//                    }
                }
                else {
                    $sql .= $subSql;
                }

            }
//            cekUngu(":: $sql ::");

            $sql = rtrim($sql, " union ");


            if ($sql == "") {
                return $this->db->query("select * from data__tmp where 999='-9999' limit 0,1");//==akal2an
            }
            else {
                return $this->db->query($sql);
            }
//showLast_query("biru");
        }
        else {
//            cekkuning("STATIC TIDAK ada isinya");
            return null;
        }

    }

    public function callSpecs($produkIds = "")
    {
        $selecteds = array(
            "id",
            // "kode",
            "nama",
            "cabang_id",

            // "folders_nama",
            // "barcode",
            // "no_part",
            // // "merek_nama",
            // // "model_nama",
            // // "type_nama",
            // // "tahun",
            // // "lokasi_nama",
            // "satuan",
            // "diskon_persen",
            // "premi_beli",
            // "diskon_beli",
            // "biaya_beli",
            // "premi_jual",
            // "harga_jual",
            // "biaya_jual",
            // "limit",
            // "limit_time",
            // "lead_time",
            // "indeks",
            // "moq",
            // "moq_time",
        );
        $this->db->select($selecteds);

        // if (isset($produkIds)) {
        if (is_array($produkIds)) {
            $this->db->where_in("id", $produkIds);
        }
        else {
            if($produkIds > 0){
                $this->db->where("id", $produkIds);
            }
        }
        // $this->db->where("jenis",'cabang');
        $vars_0 = $this->lookupAll()->result();
        // showLast_query("orange");
        $vars = array();
        if (sizeof($vars_0) > 0) {
            foreach ($vars_0 as $item) {
                $vars[$item->id] = $item;
            }
        }


        return $vars;
    }
}