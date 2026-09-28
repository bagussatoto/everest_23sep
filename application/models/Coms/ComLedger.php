<?php

/*
 * To change this license header; choose License Headers in Project Properties.
 * To change this template file; choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComJournal
 *
 * @author aziz
 */
//require_once "ComMaster.php";

class ComLedger extends MdlMother
{

    protected $filters = array();
    private $tableName;
    private $tableName__tmp;
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array(
        "transaksi_id",
        "tnumber",
        "oleh_id",
        "cabang_id",
        //"rekening",
        "debit",
        "kredit",
        "keterangan",
        "dtime",
    );

    private $tmpListedFields = array(
        "tnumber+dtime" => "transaksi",
        "rekening" => "rekening",
        "debit+kredit" => "I/O<br>debit - kredit",
        //"keterangan"=>"keterangan",

    );

    private $listedFields = array(
        "tnumber+dtime" => "transaksi",
        "rekening" => "rekening",
        "debit_pre+kredit_pre" => "awal<br>debit - kredit",
        "debit+kredit" => "I/O<br>debit - kredit",
        "debit_after+kredit_after" => "akhir<br>debit - kredit",
        //"keterangan"=>"keterangan",

    );
    private $detailTargetParams = array(
        "rekening",
    );

    public function __construct()
    {
        $this->tableName = "rek_cache";
//        $this->tableName__tmp = "com_ledger__tmp";
    }

    public function getTmpListedFields()
    {
        return $this->tmpListedFields;
    }

    public function setTmpListedFields($tmpListedFields)
    {
        $this->tmpListedFields = $tmpListedFields;
    }

    public function getListedFieldsAtGlance()
    {
        return $this->listedFieldsAtGlance;
    }

    public function setListedFieldsAtGlance($listedFieldsAtGlance)
    {
        $this->listedFieldsAtGlance = $listedFieldsAtGlance;
    }

    public function getDetailTargetParams()
    {
        return $this->detailTargetParams;
    }

    public function setDetailTargetParams($detailTargetParams)
    {
        $this->detailTargetParams = $detailTargetParams;
    }

    public function getListedFieldsCompact()
    {
        return $this->listedFields_compact;
    }

    public function setListedFieldsCompact($listedFields_compact)
    {
        $this->listedFields_compact = $listedFields_compact;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }

    // <editor-fold defaultstate="collapsed" desc="getter-setter">

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getInParams()
    {
        return $this->inParams;
    }

    public function setInParams($inParams)
    {
        $this->inParams = $inParams;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    /**
     * @return string
     */
    public function getTableNameTmp()
    {
        return $this->tableName__tmp;
    }


// </editor-fold>

    public function setTableNameTmp($tableName__tmp)
    {
        $this->tableName__tmp = $tableName__tmp;
    }

    public function getLastEntriesOLD($date = "")
    {

        $this->db->order_by("rekening", "asc");
        $this->db->order_by("id", "desc");

        $criteria = array();
        if (sizeof($this->filters) > 0) {
            $fCnt = 0;
            $criteria = array();
            foreach ($this->filters as $f) {
                $fCnt++;
                $tmp = explode("=", $f);
                if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                    $criteria[$tmp[0]] = trim($tmp[1], "'");
                }
                else {
                    $tmp = explode("<>", $f);
                    if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
                        //$criteriaNot[$tmp[0]] = trim($tmp[1], "'");
                        $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                    }
                }
            }
        }

        $this->db->where($criteria);
        if (strlen($date) > 8) {
            $this->db->where(array("fulldate<=" => "$date"));
        }

        $result = array();
        $lastID = "";
        $tmp = $this->db->get($this->tableName)->result();

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                if ($row->rekening != $lastID) {
                    $result[] = $row;
                }
                $lastID = $row->rekening;
            }
        }
        return $result;
    }

    public function getLastEntries($date = "")
    {
//        cekHere("HAHA HAHA");
        $ci =& get_instance();
        $ci->load->database();
        $tblName = "__rek_master_cache__";
        $result = $ci->db->query("SHOW TABLES LIKE '" . $tblName . "%'")->result();

        $arrPairRek = array();
        foreach ($result as $rSpec) {
            foreach ($rSpec as $e => $tbl) {
                $rek = str_replace($tblName, "", $tbl);
                $arrPairRek[$rek] = $tbl;
            }
        }

        $criteria = array();


        $result = array();
        $lastID = "";

        if (sizeof($arrPairRek) > 0) {
            foreach ($arrPairRek as $rek => $tableName) {
                if (sizeof($this->filters) > 0) {
                    $fCnt = 0;
                    $criteria = array();
                    foreach ($this->filters as $f) {
                        $fCnt++;
                        $tmp = explode("=", $f);
                        if (sizeof($tmp) > 1) { //==berarti pakai tanda samadengan =
                            $criteria[$tmp[0]] = trim($tmp[1], "'");
                        }
                        else {
                            $tmp = explode("<>", $f);
                            if (sizeof($tmp) > 1) { //==berarti pakai tanda tidak sama dengan <>
                                $criteria[$tmp[0] . "!="] = trim($tmp[1], "'");
                            }
                        }
                    }
                }

                $this->db->where($criteria);
                if (strlen($date) > 8) {
                    $this->db->where(array("fulldate<=" => "$date"));
                }

                $tmp = $this->db->get($tableName)->result();
//                cekHere($this->db->last_query());

                if (sizeof($tmp) > 0) {
                    foreach ($tmp as $row) {
                        if ($row->rekening != $lastID) {
                            $result[] = $row;
                        }
                        $lastID = $row->rekening;
                    }
                }
            }
        }

        return $result;
    }

    public function getLajurBalance($tmp)
    {
        if (sizeof($tmp) > 0) {
            $rekCatList = array("aktiva", "hutang", "modal", "biaya", "penghasilan", "lain-lain-kr");

            foreach ($tmp as $tmp_data) {
                $rek = $tmp_data->rekening;
                $value = $tmp_data->nilai_af;
                $position = detectRekPosition($rek, $value);
                $rekCategory = detectRekCategory($rek, $rekCatList);


                if (!isset($rekValues[$rek])) {
                    $rekValues[$rek] = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
                if (!isset($rekValuesPosition)) {
                    $rekValuesPosition = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
                if (!isset($rekCatValues[$rekCategory])) {
                    $rekCatValues[$rekCategory] = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }

                if (($position == "debet") && ($value >= 0)) {
                    $rekValues[$rek]['debet'] += $value;
                    $rekValuesPosition['debet'] += $value;
                    $rekCatValues[$rekCategory]['debet'] += $value;
                }
                elseif (($position == "debet") && ($value < 0)) {
                    $rekValues[$rek]['kredit'] += $value;
                    $rekValuesPosition['kredit'] += $value;
                    $rekCatValues[$rekCategory]['kredit'] += $value;
                }
                elseif (($position == "kredit") && ($value >= 0)) {
                    $rekValues[$rek]['kredit'] += $value;
                    $rekValuesPosition['kredit'] += $value;
                    $rekCatValues[$rekCategory]['kredit'] += $value;
                }
                elseif (($position == "kredit") && ($value < 0)) {
                    $rekValues[$rek]['debet'] += $value;
                    $rekValuesPosition['debet'] += $value;
                    $rekCatValues[$rekCategory]['debet'] += $value;
                }
            }

            if (round($rekValuesPosition['debet'], 2) != round($rekValuesPosition['kredit'], 2)) {
                cekHere($rekValuesPosition['debet'] . " -- " . $rekValuesPosition['kredit']);
                mati_disini("UN-BALANCE LAJUR.... <br>DEBET: " . round($rekValuesPosition['debet'], 2) . ", KREDIT: " . round($rekValuesPosition['kredit'], 2));
            }
            else {
                $arrResult = array(
                    "rekening" => $rekValues,
                    "category" => $rekCatValues,
                    "summary" => $rekValuesPosition,
                );
                return $arrResult;
            }
        }
    }

    public function getLabaRugi($tmp)
    {
        if (sizeof($tmp) > 0) {
//            $rekCatListNeraca = array("aktiva", "hutang", "modal", "lain-lain-deb", "lain-lain-kr");
            $rekCatListNeraca = array("biaya", "penghasilan");
//            $rekCatNonNeracaValues = array("biaya", "penghasilan");

            foreach ($tmp as $tmp_data) {

                $rek = $tmp_data->rekening;
                $value = $tmp_data->nilai_af;
                $position = detectRekPosition($rek, $value);
                $rekCategory = detectRekCategory($rek);

                if (in_array($rekCategory, $rekCatListNeraca)) {
                    if (!isset($rekValues[$rek])) {
                        $rekValues[$rek] = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    if (!isset($rekValuesPosition)) {
                        $rekValuesPosition = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    if (!isset($rekCatValues[$rekCategory])) {
                        $rekCatValues[$rekCategory] = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    if (($position == "debet") && ($value >= 0)) {
                        $rekValues[$rek]['debet'] += $value;
                        $rekValuesPosition['debet'] += $value;
                        $rekCatValues[$rekCategory]['debet'] += $value;
                    }
                    elseif (($position == "debet") && ($value < 0)) {
                        $rekValues[$rek]['kredit'] += $value;
                        $rekValuesPosition['kredit'] += $value;
                        $rekCatValues[$rekCategory]['kredit'] += $value;
                    }
                    elseif (($position == "kredit") && ($value >= 0)) {
                        $rekValues[$rek]['kredit'] += $value;
                        $rekValuesPosition['kredit'] += $value;
                        $rekCatValues[$rekCategory]['kredit'] += $value;
                    }
                    elseif (($position == "kredit") && ($value < 0)) {
                        $rekValues[$rek]['debet'] += $value;
                        $rekValuesPosition['debet'] += $value;
                        $rekCatValues[$rekCategory]['debet'] += $value;
                    }
                }
            }
//            arrPrint($rekValuesPosition);
            if (sizeof($rekCatListNeraca) > 0) {
                $rekCategory = "(rugi)laba";
                if (!isset($rekCatListNeraca['penghasilan'])) {
                    $rekCatNonNeracaValues['penghasilan'] = 0;
                }
                if (!isset($rekCatListNeraca['biaya'])) {
                    $rekCatNonNeracaValues['biaya'] = 0;
                }
                $rugi_laba = $rekCatListNeraca['penghasilan'] - $rekCatListNeraca['biaya'];
                if ($rugi_laba > 0) {
                    $rek = "laba";
                    $value = $rugi_laba;
                    $position = "kredit";
                }
                else {
                    $rek = "rugi";
                    $value = ($rugi_laba * -1);
                    $position = "debet";
                }
                if (!isset($rekValues[$rek])) {
                    $rekValues[$rek] = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
                if (!isset($rekValuesPosition)) {
                    $rekValuesPosition = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
                if (!isset($rekCatValues[$rekCategory])) {
                    $rekCatValues[$rekCategory] = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
                $rekValues[$rek][$position] = $value;
                $rekValuesPosition[$position] += $value;
                $rekCatValues[$rekCategory][$position] = $value;
            }

//            if (round($rekValuesPosition['debet'], 2) != round($rekValuesPosition['kredit'], 2)) {
//                return null;
//            }
//            else {
//                cekMerah("load hasil pairing..., D: " . $rekValuesPosition['debet'] . " || K: " . $rekValuesPosition['kredit'] . " || R/L: $rugi_laba");
//                $arrResult = array(
//                    "rekening" => $rekValues,
//                    "category" => $rekCatValues,
//                    "summary" => $rekValuesPosition,
//                );
//                return $arrResult;
//            }
        }
    }

    public function getNeracaBalance($tmp)
    {
        if (sizeof($tmp) > 0) {
            $rekCatListNeraca = array("aktiva", "hutang", "modal", "lain-lain-deb", "lain-lain-kr");
            $rekCatNonNeraca = array("biaya", "penghasilan");
//            $rekCatTax = array("lain-lain-deb", "lain-lain-kr");

            $rekCatNonNeracaValues = array();
            $rekCatTaxVal = array();
            $rekCatTaxValues = array();

            foreach ($tmp as $tmp_data) {
                $rek = $tmp_data->rekening;
                $value = $tmp_data->nilai_af;
                $position = detectRekPosition($rek, $value);
                $rekCategory = detectRekCategory($rek);

                if (in_array($rekCategory, $rekCatListNeraca)) {
                    if (!isset($rekValues[$rek])) {
                        $rekValues[$rek] = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    if (!isset($rekValuesPosition)) {
                        $rekValuesPosition = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    if (!isset($rekCatValues[$rekCategory])) {
                        $rekCatValues[$rekCategory] = array(
                            "debet" => 0,
                            "kredit" => 0,
                        );
                    }
                    if (($position == "debet") && ($value >= 0)) {
                        $rekValues[$rek]['debet'] += $value;
                        $rekValuesPosition['debet'] += $value;
                        $rekCatValues[$rekCategory]['debet'] += $value;
                    }
                    elseif (($position == "debet") && ($value < 0)) {
                        $rekValues[$rek]['kredit'] += $value;
                        $rekValuesPosition['kredit'] += $value;
                        $rekCatValues[$rekCategory]['kredit'] += $value;
                    }
                    elseif (($position == "kredit") && ($value >= 0)) {
                        $rekValues[$rek]['kredit'] += $value;
                        $rekValuesPosition['kredit'] += $value;
                        $rekCatValues[$rekCategory]['kredit'] += $value;
                    }
                    elseif (($position == "kredit") && ($value < 0)) {
                        $rekValues[$rek]['debet'] += $value;
                        $rekValuesPosition['debet'] += $value;
                        $rekCatValues[$rekCategory]['debet'] += $value;
                    }
                }
                if (in_array($rekCategory, $rekCatNonNeraca)) {
                    if (!isset($rekCatNonNeracaValues[$rekCategory])) {
                        $rekCatNonNeracaValues[$rekCategory] = 0;
                    }
                    $rekCatNonNeracaValues[$rekCategory] += $value;
                }
//                if (in_array($rekCategory, $rekCatTax)) {
//                    if(!isset($rekCatTaxVal[$rekCategory])){
//                        $rekCatTaxVal[$rekCategory] = 0;
//                    }
//                    $rekCatTaxVal[$rekCategory] += $value;
//                }
            }

//            if(sizeof($rekCatTaxVal)>0){
//                foreach ($rekCatTax as $val){
//                    if(!isset($rekCatTaxVal[$val])){
//                        $rekCatTaxValues[$val] = 0;
//                    }
//                    else{
//                        $rekCatTaxValues[$val] = $rekCatTaxVal[$val];
//                    }
//                }
//
//            }
//            arrPrint($rekCatTaxVal);
//            arrPrint($rekCatTaxValues);
            if (sizeof($rekCatNonNeracaValues) > 0) {

                $rekCategory = "(rugi)laba";
                if (!isset($rekCatNonNeracaValues['penghasilan'])) {
                    $rekCatNonNeracaValues['penghasilan'] = 0;
                }
                if (!isset($rekCatNonNeracaValues['biaya'])) {
                    $rekCatNonNeracaValues['biaya'] = 0;
                }
                $rugi_laba = $rekCatNonNeracaValues['penghasilan'] - $rekCatNonNeracaValues['biaya'];
                if ($rugi_laba > 0) {
                    $rek = "laba";
                    $value = $rugi_laba;
                    $position = "kredit";
                }
                else {
                    $rek = "rugi";
                    $value = ($rugi_laba * -1);
                    $position = "debet";
                }
                if (!isset($rekValues[$rek])) {
                    $rekValues[$rek] = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
                if (!isset($rekValuesPosition)) {
                    $rekValuesPosition = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
                if (!isset($rekCatValues[$rekCategory])) {
                    $rekCatValues[$rekCategory] = array(
                        "debet" => 0,
                        "kredit" => 0,
                    );
                }
                $rekValues[$rek][$position] = $value;
                $rekValuesPosition[$position] += $value;
                $rekCatValues[$rekCategory][$position] = $value;
            }
            else {
                $rugi_laba = 0;
            }

            if (round($rekValuesPosition['debet'], 2) != round($rekValuesPosition['kredit'], 2)) {
                cekHere($rekValuesPosition['debet'] . " -- " . $rekValuesPosition['kredit']);
                mati_disini("UN-BALANCE NERACA.... <br>DEBET: " . round($rekValuesPosition['debet'], 2) . ", KREDIT: " . round($rekValuesPosition['kredit'], 2));
            }
            else {
                cekMerah("load hasil pairing..., D: " . $rekValuesPosition['debet'] . " || K: " . $rekValuesPosition['kredit'] . " || R/L: $rugi_laba");
                $arrResult = array(
                    "rekening" => $rekValues,
                    "category" => $rekCatValues,
                    "summary" => $rekValuesPosition,
                );
                return $arrResult;
            }
        }
//        else {
//            return null;
//        }
//
    }
}
