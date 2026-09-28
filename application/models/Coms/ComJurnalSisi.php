<?php

//require_once "ComMaster.php";

class ComJurnalSisi extends MdlMother
{

    protected $tableName = "jurnal";
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi
    );
    private $outParams = array( //===output ke tabel
    );
    private $outFields = array(
        "jenis",
        "j_jenis",
        "rekening",
        "debet",
        "kredit",
        "transaksi_id",
        "transaksi_no",
        "cabang_id",
        "dtime",
        "keterangan",
        "fulldate",
        "urut",
    );
    protected $sortBy = array(
        "kolom" => "debet",
        "mode" => "DESC",
    );

    public function __construct()
    {


        $this->tableName_master = array();
    }


    public function getTableName()
    {
        return $this->tableName;
    }


    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
    }

    public function getTableNameMaster()
    {
        return $this->tableName_master;
    }

    public function setTableNameMaster($tableName_master)
    {
        $this->tableName_master = $tableName_master;
    }

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams['loop']) > 0) {
            $lCounter = 0;

            //  region define dulu awalnya debet vs kredit = 0
            $akumJml = array(
                "kredit" => 0,
                "debet" => 0,
            );
            //  endregion define dulu awalnya debet vs kredit = 0

            $this->outParams = array();
            foreach ($this->inParams['loop'] as $key => $value) {
//                cekUngu("JURNAL :: $key => $value ::");
                $lCounter++;
                if (!isset($this->outParams[$lCounter])) {
                    $this->outParams[$lCounter] = array();
                }

                //  region deteksi position rekening debet vs kredit
                $position = detectRekPosition($key, $value);
                cekUngu("POSITION: $position :: JURNAL :: $key => $value ::");
                if (strlen($position) < 3) {
//                    mati_disini(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT dari rekening $key " . __FUNCTION__ . " " . __FILE__);
                    die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT dari rekening ($key) " . __FUNCTION__ . " " . __FILE__));
                }

                $this->outParams[$lCounter][$position] = abs($value);
                $this->outParams[$lCounter]['rekening'] = $key;
                $akumJml[$position] += abs($value);
                //  endregion deteksi position rekening debet vs kredit

                //  region pair utuh menjadi outParams
                foreach ($this->inParams['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                //  endregion pair utuh menjadi outParams
            }

            $strImg = "";
            foreach ($this->outParams as $lCounter => $pSpec) {
                $strImg .= "<table border='1' cellspacing='0' cellpadding='0'>";
                $strImg .= "<tr>";
                foreach ($pSpec as $k => $v) {
                    $strImg .= "<td>$k</td>";
                }
                $strImg .= "</tr>";

                $strImg .= "<tr>";
                foreach ($pSpec as $k => $v) {
                    $strImg .= "<td>$v</td>";
                }
                $strImg .= "</tr>";
                $strImg .= "</table>";
            }


            //  region balancing debet vs kredit
            $balance = 0;
            if ($balance == 1) {

                if (round($akumJml["debet"], 2) != round($akumJml["kredit"], 2)) {
                    if (round($akumJml["debet"], 1) != round($akumJml["kredit"], 1)) {
                        if (floor($akumJml["debet"]) != floor($akumJml["kredit"])) {

                            $msg = "Transaksi gagal karena kesalahan dijurnal, DEBET: " . floor($akumJml["debet"]) . " != KREDIT: " . floor($akumJml["kredit"]);
                            cekOrange("Function: <b>" . __FUNCTION__ . "</b><br> File: <b>" . __CLASS__ . "</b><br> == mati disini == <br> PESAN ERR: <b>$msg</b>");
                            die(lgShowAlert($msg));
                            return false;
                        }
                        else {
                            cekHere(":: loloos floor, tanpa digit desimal ::");
                        }
                    }
                    else {
                        cekHere(":: loloos round 1 digit desimal ::");
                    }
                }
                else {
                    cekHere(":: loloos round 2 digit desimal ::");
                }
            }
            //  endregion balancing debet vs kredit

//mati_disini();
            return true;
        }
        else {
            echo "loop params are empty<br>";
            return false;
        }

    }

    public function exec()
    {
        $tableName = $this->tableName;

        $insertIDs = array();

        if (sizeof($this->outParams) > 0) {
            $strImg = "";
            foreach ($this->outParams as $lCounter => $pSpec) {


                $this->db->insert($tableName, $pSpec);
                $insertIDs[] = $this->db->insert_id();

//                cekUngu(":: JURNAL ::");
//                cekUngu($this->db->last_query());
//                mati_disini();
            }


            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

    }


    public function fetchMoves($trID)
    {//==memanggil jurnal berdasarkan trID
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $result = $this->db->get($this->tableName);
//        cekkuning($this->db->last_query());

        return $result->result();
    }

    public function _resetorReport($jenis_array)
    {

        if (is_array($jenis_array)) {
            $jenis = implode("','", $jenis_array);
        }
        else {
            $jenis = $jenis_array;
            strlen($jenis_array) > 0 ? $jenis_array : matiHere(__METHOD__ . " isikan <b>jenis</b> dalam format array lebih dulu");
        }


        $condite = array(
            "r_jenis" => 1,
        );
        $this->db->where("jenis in('" . $jenis . "')");
        $datas = array(
            "r_jenis" => 0,
        );
        $this->updateData($condite, $datas);
    }
}
