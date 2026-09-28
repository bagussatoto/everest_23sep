<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ---------------------------------------------------------------------------------------------------------
 * yang tercait COA
 * modul akunting
 * -cvt=> Coa - coa - coa
 * assets/custom/jstree/*.*
 * assets/custom/style.min.css
 * assets/custom/32px.png | 40px.png
 * js template harus diinisisasi
 * ---------------------------------------------------------------------------------------------------------*/

class MdlCashFlow extends MdlMother
{
    protected $showFilters = array(
        // "is_rl",
        // "is_aktiva",
        // "is_hutang",
        // "is_modal",
        // "is_penghasilan",
        // "is_penghasilan_lain_lain",
        // "is_biaya_lain_lain",
        // "is_biaya",
        // "is_lain_lain_deb",
        // "is_lain_lain_kre",
        // "is_rekening_pembantu",
        // "is_active",
        // "is_transaction",
        // "is_depreciation",
        // "is_budget",
        // "is_gl",
    );
    protected $listedFieldsSelectItem = array(//===kolom2 yang dibaca saat searching. silahkan di-override di model masing2 jika kolomnya kurang
        "head_code" => "head_code",
        "head_name" => "head_name",

    );
    protected $sortBy = array(
        "kolom" => "head_code",
        "mode" => "ASC",
    );

    public function getShowFilters()
    {
        return $this->showFilters;
    }

    public function setShowFilters($showFilters)
    {
        $this->showFilters = $showFilters;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('Smsgateway');
        // $this->auth->check_admin_auth();
        $this->tableName = "setting_cashflow";
        $this->fields = array(
            "rekening" => array(
                "label" => "key rekening",
                "type" => "text",
                "length" => "24",
                "kolom" => "rekening",
                "inputType" => "text",// hidden
                //--"inputName" => "id",
            ),
            "head_code" => array(
                "label" => "coa",
                "type" => "text",
                "length" => "24",
                "kolom" => "head_code",
                "inputType" => "text",// hidden
                //--"inputName" => "id",
            ),
            "head_name" => array(
                "label" => "rekening",
                "type" => "text",
                "length" => "24",
                "kolom" => "head_name",
                "inputType" => "text",// hidden
            ),
            "p_head_name" => array(
                "label" => "parent",
                "type" => "text",
                "length" => "24",
                "kolom" => "p_head_name",
                "inputType" => "hidden",// text
            ),
            "head_level" => array(
                // "label"     => "parent",
                "type" => "text",
                "length" => "24",
                // "kolom"     => "head_level",
                "inputType" => "hidden",// text
            ),
            "is_active" => array(
                // "label"     => "parent",
                "type" => "int",
                "length" => "24",
                // "kolom"     => "head_level",
                "inputType" => "hidden",// text
            ),
            // "is_transaction" => array(
            //     // "label"     => "parent",
            //     "type" => "int",
            //     "length" => "24",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_gl" => array(
            //     "label" => "GL",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_rl" => array(
            //     "label" => "rugi laba",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_aktiva" => array(
            //     "label" => "aktiva",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_hutang" => array(
            //     "label" => "hutang",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_modal" => array(
            //     "label" => "modal",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_penghasilan" => array(
            //     "label" => "penghasilan",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_penghasilan_lain_lain" => array(
            //     "label" => "penghasilan lain lain",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_biaya_lain_lain" => array(
            //     "label" => "biaya lain lain",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_biaya" => array(
            //     "label" => "biaya",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_lain_lain_deb" => array(
            //     "label" => "debet lain lain",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_lain_lain_kre" => array(
            //     "label" => "kredit lain lain",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            // "is_rekening_pembantu" => array(
            //     "label" => "rekening pembantu",
            //     "type" => "tinyint",
            //     "length" => "1",
            //     // "kolom"     => "head_level",
            //     "inputType" => "hidden",// text
            // ),
            "head_type" => array(
                // "label"     => "parent",
                "type" => "int",
                "length" => "24",
                // "kolom"     => "head_level",
                "inputType" => "hidden",// text
            ),
            "is_budget" => array(
                // "label"     => "parent",
                "type" => "int",
                "length" => "24",
                // "kolom"     => "head_level",
                "inputType" => "hidden",// text
            ),
            "create_by" => array(
                // "label"     => "parent",
                "type" => "int",
                "length" => "24",
                // "kolom"     => "head_level",
                "inputType" => "hidden",// text
            ),
            "create_date" => array(
                // "label"     => "parent",
                "type" => "int",
                "length" => "24",
                // "kolom"     => "head_level",
                "inputType" => "hidden",// text
            ),
            "extern_jenis" => array(
                // "label"     => "parent",
                "type" => "int",
                "length" => "24",
                // "kolom"     => "head_level",
                "inputType" => "hidden",// text
            ),
            "extern_id" => array(
                // "label"     => "parent",
                "type" => "int",
                "length" => "24",
                // "kolom"     => "head_level",
                "inputType" => "hidden",// text
            ),
        );

    }

    //----------------------------------------------------
    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    //----------------------------------------------------
    //coa
    function get_coalist()
    {
        $this->db->select('*');
        $this->db->from('setting_cashflow');
        $this->db->where(array(
            'is_active' => 1,
            'p_head_name' => 'COA',
        ));
        $this->db->order_by('head_code');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        else {
            return false;
        }
    }

    //coa
    function get_userlist()
    {
        $this->db->select('*');
        $this->db->from('setting_cashflow');
        $this->db->where('is_active', 1);
        $this->db->order_by('head_code');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            return $query->result();
        }
        else {
            return false;
        }
    }

    public function treeview_selectform($id)
    {
        $data = $this->db->select('*')
            ->from('setting_cashflow')
            ->where('head_code', $id)
            ->get()
            ->row();
        return $data;
    }

    /* ----------------------------------------------------------------------
     * fungsi untuk nambahin coa dari mdl-data contoh pengunakan ada di
     * MdlProduk - ctr Data::addProccess::doApproveFrom
     * ----------------------------------------------------------------------*/
    public function addExtern_coa($head_code, $datas)
    {
        $this_data = $this->db->select('*')
            ->from($this->tableName)
            ->where('head_code', $head_code)
            ->get()
            ->row();
        sizeof($this_data) == 0 ? matiHere("data untuk $head_code tidak ditemukan " . __METHOD__) : "";
        // showLast_query("lime");
        // arrPrintPink($this_data);
        $newidsinfo = $this->db->select('count(head_code) as hc')
            ->from($this->tableName)
            ->where('p_head_name', $head_code)
            ->get()
            ->row();

        // showLast_query("orange");
        $nid = $newidsinfo->hc;
        $n = $nid + 1;
        $headlevel_new = $this_data->head_level + 1;

        if ($head_code == "0") {
            $headCode_new = $n;
        }
        else {
            if ($n / 10 < 1) {
                if (($this_data->head_level + 1) > 2) {
                    $headCode_new = $head_code . digit_5($n);
                }
                else {
                    $headCode_new = $head_code . "0" . $n;
                }
            }
            else {
                $headCode_new = $head_code . "" . digit_5($n);
            }
        }


        $datas['head_code'] = $headCode_new;
        $datas['p_head_code'] = $head_code;
        $datas['head_type'] = $this_data->head_type;
        $datas['head_level'] = $headlevel_new;

        $koloms = $this->filters;
        foreach ($koloms as $kolom => $kolom_params) {
            if (!array_key_exists($koloms, $datas)) {
                matiHere("$koloms wajib ada");
            }
        }

        !isset($datas['extern_id']) ? matiHere("extern_idnya dikasih lah") : "";
        !isset($datas['extern_jenis']) ? matiHere("extern_jenisnya dikasih lah") : "";


        /// menulis ke tabel
        $this->addData($datas);
        // showLast_query("merah");

        return $headCode_new;
        // return $head_code;
    }

    public function add_coa($head_code)
    {

    }

    // Accounts list
    public function Transacc()
    {
        return $data = $this->db->select("*")
            ->from('setting_cashflow')
            ->where('IsTransaction', 1)
            ->where('IsActive', 1)
            ->order_by('HeadName')
            ->get()
            ->result();
    }

    // Credit Account Head
    public function Cracc()
    {
        return $data = $this->db->select("*")
            ->from('setting_cashflow')
            ->like('HeadCode', 1020102, 'after')
            ->where('IsTransaction', 1)
            ->order_by('HeadName')
            ->get()
            ->result();
    }


    public function lookUpTransactionStructure()
    {
        $this->db->where(array("is_transaction" => "1"));
        $tmp = $this->db->get($this->tableName)->result();
        $arrStructure = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmp0) {
                //                $arrStructure[]
                foreach ($this->getShowFilters() as $ky) {
                    if ($tmp0->$ky == "1") {
                        $label = $this->fields[$ky]['label'];
                        $arrStructure[$label][] = $tmp0->head_code;
                    }
                }

            }

        }
        return $arrStructure;
    }

    public function lookUpTransactionStructureLabel()
    {
        $this->db->where(array("is_transaction" => "1"));
        $tmp = $this->db->get($this->tableName)->result();
        $arrStructure = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmp0) {
                //                $arrStructure[]
                foreach ($this->getShowFilters() as $ky) {
                    if ($tmp0->$ky == "1") {
                        $label = $this->fields[$ky]['label'];
                        $arrStructure[$tmp0->head_code] = $tmp0->head_name;
                    }
                }

            }

        }
        return $arrStructure;
    }

    public function lookUpTransactionStructureLabel_old()
    {
        $this->db->where(array("is_transaction" => "1"));
        $tmp = $this->db->get($this->tableName)->result();
        $arrStructure = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmp0) {
                //                $arrStructure[]
                foreach ($this->getShowFilters() as $ky) {
                    if ($tmp0->$ky == "1") {
                        $label = $this->fields[$ky]['label'];
                        $arrStructure[$tmp0->head_code] = $tmp0->rekening;
                    }
                }

            }

        }
        return $arrStructure;
    }

    //----------------------------------
    public function lookupByID($id)
    {
        if (is_array($id)) {
            if (sizeof($id) > 0) {
                $this->db->where_in("head_code", $id);
            }
            else {
                mati_disini("array yg dikirimkan kosong " . __METHOD__);
            }
        }
        else {
            $criteria = array("head_code" => $id);
            $criteria2 = "";
            if (sizeof($this->filters) > 0) {
                $this->fetchCriteria();
                $criteria = $criteria + $this->getCriteria();
                $criteria2 = $this->getCriteria2();
            }
            if (sizeof($criteria) > 0) {
                $this->db->where($criteria);
            }
            if ($criteria2 != "") {
                $this->db->where($criteria2);
            }
            $this->db->where($criteria);
        }

        return $this->db->get($this->tableName);
    }
}