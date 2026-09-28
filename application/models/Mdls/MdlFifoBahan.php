<?php

//--include_once "MdlHistoriData.php";
class MdlFifoBahan extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $validationRules = array();
    protected $listedFieldsSelectFolder = array();
    protected $listedFieldsViewFolder = array();
    protected $listedFieldsSelectItem = array();
    protected $listedFieldsViewItem = array();
    protected $listedFieldsFormFolder = array();
    protected $listedFieldsFormItem = array();
    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $kategori;
    protected $kategori_id;
    protected $search;

    //<editor-fold desc="getter-setter">

    function __construct()
    {
        parent::__construct();
        $this->tableName = "rek_cache_persediaan_bahan_fifo";
        $this->indexFields = "id";
        $this->fields = array();
        $this->listedFieldsSelectFolder = array();
        $this->listedFieldsSelectItem = array();
        $this->listedFieldsViewFolder = array();
        $this->listedFieldsViewItem = array();
        $this->listedFieldsFormFolder = array();
        $this->listedFieldsFormItem = array();
        $this->validationRules = array();
        $this->kategori_id = null != $this->uri->segment(4) ? $this->uri->segment(4) : "";
        $this->listedFieldsHidden = array("id", "jenis");

        if (null != $this->uri->segment(3) && (!is_numeric($this->uri->segment(3)))) {

            $this->kategori = $this->uri->segment(3);
            $this->listedFieldsForm = $this->listedFieldsFormItem;
        } else {
            $this->kategori = "folder";
            $this->listedFieldsForm = $this->listedFieldsFormFolder;
        }


        $this->session->unset_userdata('search');
        if (null != $this->input->post('search')) {
            $this->search = $this->input->post('search');
            $this->session->set_userdata('search', $this->search);
        } else {
            $this->search = $this->session->search;
        }
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getIndexFields()
    {
        return $this->indexFields;
    }

    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function getListedFieldsSelectFolder()
    {
        return $this->listedFieldsSelectFolder;
    }

    public function setListedFieldsSelectFolder($listedFieldsSelectFolder)
    {
        $this->listedFieldsSelectFolder = $listedFieldsSelectFolder;
    }

    public function getListedFieldsViewFolder()
    {
        return $this->listedFieldsViewFolder;
    }

    public function setListedFieldsViewFolder($listedFieldsViewFolder)
    {
        $this->listedFieldsViewFolder = $listedFieldsViewFolder;
    }

    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
    }

    public function getListedFieldsViewItem()
    {
        return $this->listedFieldsViewItem;
    }

    public function setListedFieldsViewItem($listedFieldsViewItem)
    {
        $this->listedFieldsViewItem = $listedFieldsViewItem;
    }

    public function getListedFieldsFormFolder()
    {
        return $this->listedFieldsFormFolder;
    }

    public function setListedFieldsFormFolder($listedFieldsFormFolder)
    {
        $this->listedFieldsFormFolder = $listedFieldsFormFolder;
    }

    public function getListedFieldsFormItem()
    {
        return $this->listedFieldsFormItem;
    }

    public function setListedFieldsFormItem($listedFieldsFormItem)
    {
        $this->listedFieldsFormItem = $listedFieldsFormItem;
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

    public function getKategori()
    {
        return $this->kategori;
    }

    public function setKategori($kategori)
    {
        $this->kategori = $kategori;
    }

    public function getKategoriId()
    {
        return $this->kategori_id;
    }

    public function setKategoriId($kategori_id)
    {
        $this->kategori_id = $kategori_id;
    }

    public function getSearch()
    {
        return $this->search;
    }

    //</editor-fold>

    public function setSearch($search)
    {
        $this->search = $search;
    }

    function lookupTotal()
    {
        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        $this->db->select('id');
        $arrWhere = array(
            "trash"   => 0,
            "jenis =" => $this->kategori,
        );
        $this->kategori_id > 0 ? $arrWhere['folders='] = "$this->kategori_id" : "";
        $this->db->where($arrWhere);
        $q = $this->db->get($this->tableName)->num_rows();

        return $q;
    }

    function lookupLimited($position, $batas)
    {
        $segment_uri_array = $this->uri->segment_array();
        if (in_array('item', $segment_uri_array)) {
            $arrFieldsSelected = $this->listedFieldsSelectItem;
        } else {
            $arrFieldsSelected = $this->listedFieldsSelectFolder;
        }


        $list = array();
        foreach ($arrFieldsSelected as $kolom) {
            $list[] = $this->fields[$kolom]['kolom'];
        }


        $arrWhere = array(
            "trash"   => 0,
            "jenis =" => $this->kategori,
        );
        $this->kategori_id > 0 ? $arrWhere['folders='] = "$this->kategori_id" : "";
        $this->db->where($arrWhere);
        strlen($this->search) > 3 ? $this->db->like('nama', $this->search) : "";
        $this->db->order_by('nama', 'asc');
        $this->db->limit($batas, $position);
        $q = $this->db->get($this->tableName);

        return $q;
    }

    function lookupTotalHistoryAll()
    {
        $h = New MdlHistoriData();
        $rows = $h->lookupTotalActive();

        return $rows;
    }

    function lookupHistory($position, $batas)
    {

        $h = New MdlHistoriData();
        $result = $h->lookupLimitedActive($position, $batas)->result();


        return $result;
    }

    function lookupLimitedBySelected($conditional = "")
    {

        $arrWhere = array(
            "trash"   => 0,
            "jenis =" => "item",
        );
        $this->db->where($arrWhere);
        $this->db->order_by('nama', 'asc');
        if (strlen($this->search) >= 3) {
            $this->db->like('nama', $this->search);

            $q = $this->db->get($this->tableName);
//            cekHere("QUERY");
        } else {
            $q = (object)array();
//            cekHere("NO QUERY");
        }
        return $q;
    }

    function lookupBahanData($bahan_id)
    {
        $arrWhere = array(
            "id" => $bahan_id,
        );
//        $this->kategori_id > 0 ? $arrWhere['folders=']="$this->kategori_id" : "";
        $this->db->where($arrWhere);
        $q = $this->db->get($this->tableName)->row();
        return $q;
    }


    function lookupKategory()
    {

        $arrWhere = array(
            "trash"   => 0,
            "jenis =" => 'folder',
        );
//        $this->kategori_id > 0 ? $arrWhere['folders=']="$this->kategori_id" : "";
        $this->db->where($arrWhere);
        $this->db->order_by('nama', 'asc');
        $q = $this->db->get($this->tableName);

        return $q;
    }


}