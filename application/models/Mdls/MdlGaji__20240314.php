<?php


class MdlGaji extends MdlMother_static
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "jenis='gaji'",
        "status='1'",
        "trash='0'",
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
        "rekening" => array(
            "label" => "name",
            "type" => "varchar", "length" => "255", "kolom" => "rekening",
            "inputType" => "text",
        ),
        "disabled" => array(
            "label" => "name",
            "type" => "varchar", "length" => "255", "kolom" => "disabled",
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
            "name" => "gaji",
            "nama" => "gaji",
            "rekening" => "hutang gaji",
            "disabled" => 0,
            "kategori_id"=>"1",
            "kategori_nama"=>"gaji",
        ),
        array(
            "id" => 2,
            "name" => "bpjs ditanggung  karyawan",
            "nama" => "bpjs ditanggung karyawan",
            "rekening" => "hutang bpjs karyawan",
            "disabled" => 0,
            "kategori_id"=>"2",
            "kategori_nama"=>"bpjs",
        ),
        array(
            "id" => 3,
            "name" => "pph ps21 ditanggung karyawan",
            "nama" => "pph ps21 ditanggung  karyawan",
            "rekening" => "hutang pph21 karyawan",
            "disabled" => 0,
            "kategori_id"=>"3",
            "kategori_nama"=>"pph21",
        ),
        array(
            "id" => 4,
            "name" => "bpjs ditanggung  perusahaan",
            "nama" => "bpjs ditanggung  perusahaan",
            "rekening" => "biaya bpjs perusahaan",
            "disabled" => 0,
            "kategori_id"=>"2",
            "kategori_nama"=>"bpjs",
        ),
        array(
            "id" => 5,
            "name" => "pph ps21 ditanggung  perusahaan",
            "nama" => "pph ps21 ditanggung  perusahaan",
            "rekening" => "biaya pph21 perusahaan",
            "disabled" => 0,
            "kategori_id"=>"3",
            "kategori_nama"=>"pph21",
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
    public function lookupAll()
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