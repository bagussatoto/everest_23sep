<?php

class History
{
    protected $my_id;
    protected $toko_id;
    protected $cabang_id;
    protected $gudang_id;
    protected $component_rekening;
    protected $rekening;
    protected $transaksi_id;
    protected $jenisTr;
    protected $date_start;
    protected $date_stop;
    protected $extern_id;
    protected $limit;
    protected $search;
    protected $page;


    public function getMyId()
    {
        return $this->my_id;
    }

    public function setMyId($my_id)
    {
        $this->my_id = $my_id;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getExternId()
    {
        return $this->extern_id;
    }

    public function setExternId($extern_id)
    {
        $this->extern_id = $extern_id;
    }

    public function getComponentRekening()
    {
        return $this->component_rekening;
    }

    public function setComponentRekening($component_rekening)
    {
        $this->component_rekening = $component_rekening;
    }

    //region getter and setter...

    public function getDateStart()
    {
        return $this->date_start;
    }

    public function setDateStart($date_start)
    {
        $this->date_start = $date_start;
    }

    public function getDateStop()
    {
        return $this->date_stop;
    }

    public function setDateStop($date_stop)
    {
        $this->date_stop = $date_stop;
    }

    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    public function getCabangId()
    {
        return $this->cabang_id;
    }

    public function setCabangId($cabang_id)
    {
        $this->cabang_id = $cabang_id;
    }

    public function getGudangId()
    {
        return $this->gudang_id;
    }

    public function setGudangId($gudang_id)
    {
        $this->gudang_id = $gudang_id;
    }

    public function getRekening()
    {
        return $this->rekening;
    }

    public function setRekening($rekening)
    {
        $this->rekening = $rekening;
    }

    public function getTransaksiId()
    {
        return $this->transaksi_id;
    }

    public function setTransaksiId($transaksi_id)
    {
        $this->transaksi_id = $transaksi_id;
    }

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    //endregion

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();
        $this->CI->load->model("MdlTransaksi");
//        $this->CI->load->model("Coms/ComJurnal");
//        $this->CI->load->helper("he_mass_table");

    }



    //--------------------------------------------------------
    //--------------------------------------------------------
    public function viewHistory()
    {
        $tr = new MdlTransaksi();
        if (($this->date_start != NULL) && ($this->date_stop != NULL)) {
            $tr->addFilter("date(dtime)>=" . $this->date_start);
            $tr->addFilter("date(dtime)<=" . $this->date_stop);
            $this->CI->db->order_by("id", "DESC");
            $limit = "";
        }
        else {
            $limit = 20;
//            $this->CI->db->limit("$limit");
            $this->CI->db->order_by("id", "DESC");
        }
        if ($this->search != NULL) {
            $tr->setKeyWord($this->search);
        }
        $tr->addFilter("transaksi.toko_id=" . $this->toko_id);
        $tr->addFilter("transaksi.cabang_id=" . $this->cabang_id);
        $tr->addFilter("transaksi.gudang_id=" . $this->gudang_id);
        $tr->addFilter("transaksi.jenis_master=" . $this->jenisTr);
        $jmlData = $tr->lookupDataCount();
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $this->page)->result();
        showLast_query("biru");
        cekHere(sizeof($tmpHist));
        $recentHistory = array();
        if (sizeof($tmpHist) > 0) {
            $arrTrIDs = array();
            foreach ($tmpHist as $h => $row) {
                $arrTrIDs[$row->id] = $row->id;
            }
            $trReg = new MdlTransaksi();
            $trReg->setFilters(array());
            $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $tmpReg = $trReg->lookupDataRegistries()->result();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $regRow) {
                    foreach ($regRow as $k_reg => $v_reg) {
                        switch ($k_reg) {
                            case "main":
                                $tmpReg_result[$regRow->transaksi_id][$k_reg] = ($k_reg != NULL) ? blobDecode($v_reg) : array();
                                break;
                            case "items":
                                $tmpReg_result[$regRow->transaksi_id][$k_reg] = ($k_reg != NULL) ? blobDecode($v_reg) : array();
                                break;
                        }
                    }
                }
            }
            foreach ($tmpHist as $h => $row) {
                if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                    foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                        switch ($param) {
                            case "main":
                                foreach ($eReg as $k => $v) {
                                    if (!isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                                break;
                            case "items":
                                $row->$param = $eReg;
                                break;
                        }

                    }
                }
                $recentHistory[$h] = $row;
            }
        }
        arrPrintWebs($recentHistory);
        return $recentHistory;

    }

    public function viewMyHistory()
    {
        $tr = new MdlTransaksi();
        if (($this->date_start != NULL) && ($this->date_stop != NULL)) {
            $tr->addFilter("date(dtime)>=" . $this->date_start);
            $tr->addFilter("date(dtime)<=" . $this->date_stop);
            $this->CI->db->order_by("id", "DESC");
            $limit = "";
        }
        else {
            $limit = 20;
//            $this->CI->db->limit("$limit");
            $this->CI->db->order_by("id", "DESC");
        }
        if ($this->search != NULL) {
            $tr->setKeyWord($this->search);
        }
        $tr->addFilter("transaksi.toko_id=" . $this->toko_id);
        $tr->addFilter("transaksi.cabang_id=" . $this->cabang_id);
        $tr->addFilter("transaksi.gudang_id=" . $this->gudang_id);
        $tr->addFilter("transaksi.jenis_master=" . $this->jenisTr);
        $tr->addFilter("transaksi.oleh_id=" . $this->my_id);

        $jmlData = $tr->lookupDataCount();
        $tmpHist = $tr->lookupHistories($jmlData, $limit, $this->page)->result();
        showLast_query("biru");
        cekHere(sizeof($tmpHist));
        $recentHistory = array();
        if (sizeof($tmpHist) > 0) {
            $arrTrIDs = array();
            foreach ($tmpHist as $h => $row) {
                $arrTrIDs[$row->id] = $row->id;
            }
            $trReg = new MdlTransaksi();
            $trReg->setFilters(array());
            $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $tmpReg = $trReg->lookupDataRegistries()->result();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $regRow) {
                    foreach ($regRow as $k_reg => $v_reg) {
                        switch ($k_reg) {
                            case "main":
                                $tmpReg_result[$regRow->transaksi_id][$k_reg] = ($k_reg != NULL) ? blobDecode($v_reg) : array();
                                break;
                            case "items":
                                $tmpReg_result[$regRow->transaksi_id][$k_reg] = ($k_reg != NULL) ? blobDecode($v_reg) : array();
                                break;
                        }
                    }
                }
            }
            foreach ($tmpHist as $h => $row) {
                if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                    foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                        switch ($param) {
                            case "main":
                                foreach ($eReg as $k => $v) {
                                    if (!isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                                break;
                            case "items":
                                $row->$param = $eReg;
                                break;
                        }

                    }
                }
                $recentHistory[$h] = $row;
            }
        }
        arrPrintWebs($recentHistory);
        return $recentHistory;


    }

    public function viewRecentHistory()
    {
        $tr = new MdlTransaksi();
        $tr->addFilter("transaksi.toko_id=" . $this->toko_id);
        $tr->addFilter("transaksi.cabang_id=" . $this->cabang_id);
        $tr->addFilter("transaksi.gudang_id=" . $this->gudang_id);
        $tr->addFilter("transaksi.jenis_master=" . $this->jenisTr);
//        if (isset($this->session->login['employee_type']) && ($this->session->login['employee_type'] == "employee_freelance")) {
//            $tr->addFilter("seller_id='" . $this->session->login['id'] . "'");
//        }
//

        $tmpHist = $tr->lookupRecentHistories($this->limit)->result();
        showLast_query("biru");
        cekKuning(sizeof($tmpHist));
        $recentHistory = array();
        if (sizeof($tmpHist) > 0) {
            $arrTrIDs = array();
            foreach ($tmpHist as $h => $row) {
                $arrTrIDs[$row->id] = $row->id;
            }
            $trReg = new MdlTransaksi();
            $trReg->setFilters(array());
            $trReg->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $tmpReg = $trReg->lookupDataRegistries()->result();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $regRow) {
                    foreach ($regRow as $k_reg => $v_reg) {
                        switch ($k_reg) {
                            case "main":
                                $tmpReg_result[$regRow->transaksi_id][$k_reg] = $k_reg != NULL ? blobDecode($v_reg) : array();
                                break;
                            case "items":
                                $tmpReg_result[$regRow->transaksi_id][$k_reg] = ($k_reg != NULL) ? blobDecode($v_reg) : array();
                                break;
                        }
                    }
                }
            }

            foreach ($tmpHist as $h => $row) {
                if ((sizeof($tmpReg_result) > 0) && (isset($tmpReg_result[$row->id]))) {
                    foreach ($tmpReg_result[$row->id] as $param => $eReg) {
                        switch ($param) {
                            case "main":
                                foreach ($eReg as $k => $v) {
                                    if (!isset($row->$k)) {
                                        $row->$k = $v;
                                    }
                                }
                                break;
                            case "items":
                                $row->$param = $eReg;
                                break;
                        }

                    }
                }
                $recentHistory[$h] = $row;
            }
        }
        arrPrintKuning($recentHistory);
        return $recentHistory;
    }
}
