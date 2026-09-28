<?php

//require_once "ComMaster.php";

class ComJurnalValuesItem extends MdlMother
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
        "rekening_main",
        "extern_id",
        "debet",
        "kredit",
        "transaksi_id",
        "transaksi_no",
        "cabang_id",
        "dtime",
        "keterangan",
        "fulldate",
    );
    protected $sortBy = array(
        "kolom" => "debet",
        "mode" => "DESC",
    );

    public function __construct()
    {


        $this->tableName_master = array();
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
        arrprint($this->inParams);
//mati_disini(get_class($this));

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;

            $akumJml = array(
                "kredit" => 0,
                "debet" => 0,
            );
            $arrayParams = $this->inParams;
            $this->outParams = array();
//            foreach ($this->inParams as $arrayParams) {
            //--------------
            $mdlName = $arrayParams['static']['mdlName'];
            $rekeningMain = $arrayParams['static']['rekening'];
            $this->load->model("Mdls/$mdlName");
            $m = New $mdlName();
            $mTmp = $m->lookupAll()->result();
            $mItems = array();
            foreach ($mTmp as $mSpec){
                $mItems[$mSpec->id] = $mSpec->nama;
            }
            //--------------
//cekHijau($mItems);


            foreach ($arrayParams['loop'] as $key => $value) {
                $keyID = $key;

                $lCounter++;
                if (!isset($this->outParams[$lCounter])) {
                    $this->outParams[$lCounter] = array();
                }


//                    $position = detectRekPosition($key, $value);
                $position = ($value < 0) ? "kredit" : "debet";


                cekUngu("POSITION: $position :: JURNAL :: $key => $value ::");

                if (strlen($position) < 3) {

                    die(lgShowAlert(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT dari rekening ($key) " . __FUNCTION__ . " " . __FILE__));
                }

                $this->outParams[$lCounter][$position] = abs($value);

                $akumJml[$position] += abs($value);


                foreach ($arrayParams['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }

                $this->outParams[$lCounter]['extern_id'] = $keyID;
                $this->outParams[$lCounter]['rekening'] = $mItems[$keyID];
                $this->outParams[$lCounter]['rekening_main'] = $rekeningMain;
                $this->outParams[$lCounter]['j_jenis'] = "items";
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
//            echo $strImg;

            //  region balancing debet vs kredit
            $balance = 1;
            if ($balance == 1) {

                if (round($akumJml["debet"], 2) != round($akumJml["kredit"], 2)) {
                    $msg = "Transaksi gagal karena kesalahan dijurnal, DEBET: " . $akumJml["debet"] . " KREDIT: " . $akumJml["kredit"];
                    die(lgShowAlert($msg));
                    return false;
                }
                else {
                    cekHitam("JURNAL ITEMS BALANCE...");
                }
            }

            //  endregion balancing debet vs kredit

//                mati_disini();
//            }


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
//            $strImg = "";
            foreach ($this->outParams as $lCounter => $pSpec) {


                $this->db->insert($tableName, $pSpec);
                $insertIDs[] = $this->db->insert_id();

                cekUngu(":: JURNAL ::");
                cekUngu($this->db->last_query());
            }

//            mati_disini(get_class($this));

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
