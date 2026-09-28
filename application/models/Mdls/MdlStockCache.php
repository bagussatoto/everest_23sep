<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/3/2018
 * Time: 3:41 PM
 */
class MdlStockCache extends MdlMother
{
    protected $tableName = "produk_stok_cache";
    protected $tableName_data = "produk";
    protected $filters = array();
    protected $listedFieldsSelectItem = array();
    protected $fields = array();

    //  region getter setter

    public function __construct()
    {
        parent::__construct();
        $this->fields = array(
            "id"         => array(
                "label"     => "id",
                "type"      => "int", "length" => "24", "kolom" => "id",
                "inputType" => "text",// hidden
                //--"inputName" => "id",
            ),
            "produk_id"  => array(
                "label"     => "produk_id",
                "type"      => "int", "length" => "24", "kolom" => "produk_id",
                "inputType" => "text",// hidden
                //--"inputName" => "produk_id",
            ),
            "nama"       => array(
                "label"     => "nama",
                "type"      => "int", "length" => "24", "kolom" => "produk_nama",
                "inputType" => "text",
                //--"inputName" => "produk_nama",
            ),
            "nama_2"     => array(
                "label"     => "nama_2",
                "type"      => "int", "length" => "24", "kolom" => "produk_nama_2",
                "inputType" => "text",
                //--"inputName" => "produk_nama_2",
            ),
            "persediaan" => array(
                "label"     => "persediaan",
                "type"      => "int", "length" => "24", "kolom" => "persediaan",
                "inputType" => "int",
                //--"inputName" => "persediaan",
            ),
        );
        $this->listedFieldsSelectItem = array("produk_id", "nama", "persediaan");
//        $this->db->join("produk", "produk" . ".id = " . "produk_stok_cache" . ".produk_id ");
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getListedFieldsSelectItem()
    {
        return $this->listedFieldsSelectItem;
    }

    public function setListedFieldsSelectItem($listedFieldsSelectItem)
    {
        $this->listedFieldsSelectItem = $listedFieldsSelectItem;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getTableNameData()
    {
        return $this->tableName_data;
    }


    //  endregion getter setter

    public function setTableNameData($tableName_data)
    {
        $this->tableName_data = $tableName_data;
    }

    public function removeFilter($f)
    {
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $cnt => $fx) {
                if ($fx == $f) {
                    unset($this->filters[$cnt]);
                }
            }
        }
    }

    public function updateLocker($jenis, $cabang, $barang, $state, $qty)
    {
        $jmlCurrent = $this->checkQty($jenis, $cabang, $barang, $state);
        if ($jmlCurrent != NULL) {//===sudah ada loker dengan state ini
            $this->db->where(array(
                "jenis"     => $jenis,
                "cabang_id" => $cabang,
                "barang_id" => $barang,
                "state"     => $state,
            ));
            if ($jmlCurrent + $qty >= 0) {
                $this->db->update($this->tableName, array(
                    "qty" => ($jmlCurrent + $qty),
                ));
                return true;
            } else {
                return false;
            }

        } else {//===belum ada loker dengan state ini
            $this->db->insert($this->tableName, array(
                "jenis"     => $jenis,
                "cabang_id" => $cabang,
                "barang_id" => $barang,
                "state"     => $state,
                "qty"       => $qty,
            ));
            return $this->db->insert_id();
        }
    }

    public function checkQty($jenis, $cabang, $barang, $state = "active")
    {
//        $this->addFilter("jenis='$jenis'");
        $this->addFilter("cabang_id='$cabang'");
        $this->addFilter("produk_id='$barang'");
//        $this->addFilter("state='$state'");
        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $fCnt = 0;
            $criteria = array();
            foreach ($this->filters as $f) {
                $fCnt++;
                $tmp = explode("=", $f);
                if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                    $criteria[$tmp[0]] = trim($tmp[1], "'");
                } else {
                    $tmp = explode("<>", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
                        //$criteriaNot[$tmp[0]] = trim($tmp[1], "'");
                        $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                    }
                }
            }
        }
        //return $this->db->get($this->tableName);
        $result = $this->db->get_where($this->tableName, $criteria)->result();
        if (sizeof($result) > 0) {
            return $result[0]->qty;
        } else {
            return NULL;
        }
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

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

        foreach ($this->listedFieldsSelectItem as $fName => $fSpec) {
            $colCtr++;
            $kolomMentah = $this->fields[$fSpec];
            $fieldName = isset($kolomMentah['kolom']) ? $kolomMentah['kolom'] : $fSpec;

            if ($colCtr == 1) {
                $this->db->like($fieldName, $key);
            } else {
                $this->db->or_like($fieldName, $key);
            }
        }
        $this->db->group_end();
        $this->db->join($this->tableName_data, $this->tableName_data . ".id = " . $this->tableName . ".produk_id ");
        $result = $this->db->get_where($this->tableName, $criteria);

        return $result;
    }

    public function lookupByID($id)
    {
        $criteria = array("produk_id" => $id);
        $this->db->where($criteria);
        return $this->db->get($this->tableName);
    }
}