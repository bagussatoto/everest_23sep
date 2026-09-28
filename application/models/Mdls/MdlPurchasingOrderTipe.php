<?php


class MdlPurchasingOrderTipe extends MdlMother_static
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
//        "jenis='gaji'",
//        "status='1'",
//        "trash='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama", "name");
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
            "label" => "name",
            "type" => "int", "length" => "24", "kolom" => "nama",
            "inputType" => "text",
        ),
        "kode" => array(
            "label" => "kode",
            "type" => "int", "length" => "24", "kolom" => "kode",
            "inputType" => "text",
        ),
    );
    // protected $staticData = array(
    //     array(
    //         "id" => 1,
    //         "name" => "gaji",
    //         "nama" => "gaji",
    //         "rekening" => "hutang gaji",
    //     ),
    //     array(
    //         "id" => 2,
    //         "name" => "pph ps21 ditanggung karyawan",
    //         "nama" => "pph ps21 ditanggung  karyawan",
    //         "rekening" => "hutang pph21",
    //     ),
    //     array(
    //         "id" => 5,
    //         "name" => "pph ps21 ditanggung  perusahaan",
    //         "nama" => "pph ps21 ditanggung  perusahaan",
    //         "rekening" => "biaya pph21 perusahaan",
    //     ),
    //     array(
    //         "id" => 3,
    //         "name" => "bpjs ditanggung  karyawan",
    //         "nama" => "bpjs ditanggung karyawan",
    //         "rekening" => "hutang bpjs karyawan",
    //     ),
    //     array(
    //         "id" => 4,
    //         "name" => "bpjs ditanggung  perusahaan",
    //         "nama" => "bpjs ditanggung  perusahaan",
    //         "rekening" => "biaya bpjs perusahaan",
    //     ),
    // );

    /**
     * @var array
     * copy dari san complit, semua tersedia.
     * everest semua ditanggung perusahaan, sehingga fitur ditanggung karyawan didisable
     * tetap tampil UI memberikan info komponen biaya ada tapi fitur masih off
     */
    protected $staticData = array(
        array(
            "id" => 1,
            "name" => "PO FG REGULER",
            "nama" => "PO FG REGULER",
            "kode" => "reguler",
        ),
        array(
            "id" => 2,
            "name" => "PO FG TARGET",
            "nama" => "PO FG TARGET",
            "kode" => "target",
        ),
    );
    protected $listedFields = array(
        "nama" => "name",
        "due_days" => "due days",
        "status" => "status",

    );

    public function __construct()
    {

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


    //@override with static data
    public function lookupAll__()
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {

            $iCtr = 0;
            $sql = "";

            foreach ($this->staticData as $iSpec) {
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {
                    $fCtr++;
                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $sql .= ",";
                    }
                }
                if ($iCtr < sizeof($this->staticData)) {
                    $sql .= " union ";
                }
            }

            return $this->db->query($sql);
        }
        else {

            return null;
        }

    }

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

    public function lookupByKeyword($key)
    {
        if (isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData) > 0) {

            $iCtr = 0;
            $sql = "";

            foreach ($this->staticData as $iSpec) {
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr = 0;
                foreach ($this->fields as $fID => $fSpec) {
                    $fCtr++;
                    $sql .= "'" . $iSpec[$fID] . "' as $fID";
                    if ($fCtr < sizeof($this->fields)) {
                        $sql .= ",";
                    }
                }
                if ($iCtr < sizeof($this->staticData)) {
                    $sql .= " union ";
                }
            }

            return $this->db->query($sql);
        }
        else {

            return null;
        }

    }
}