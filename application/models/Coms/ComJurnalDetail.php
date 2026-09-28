<?php

//require_once "ComMaster.php";

class ComJurnalDetail extends MdlMother
{

    protected $tableName = "jurnal_detail";
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
        "extern_id",
        "extern_nama",
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
        $lCounter = 0;
//        arrPrintPink($inParams);
        foreach ($inParams as $inParamsss) {
            $this->inParams = $inParamsss;
            if (sizeof($this->inParams['loop']) > 0) {
                $accountStrukturAlias = fetchAccountStructureAlias();
                $accountStrukturAlias_old = fetchAccountStructureAlias_old();

                //  region define dulu awalnya debet vs kredit = 0
                $akumJml = array(
                    "kredit" => 0,
                    "debet" => 0,
                );
                //  endregion define dulu awalnya debet vs kredit = 0

                $cabang_id = isset($this->inParams['static']['cabang_id']) ? $this->inParams['static']['cabang_id'] : NULL;
                if (($cabang_id == NULL) || ($cabang_id == 0)) {
                    $msg = "Sesi transaksi atau sesi login anda tidak valid/kadaluarsa. Silahkan refresh browser anda atau lakukan login ulang. code: " . __LINE__;
                    mati_disini($msg);
                }


                $this->outParams = array();
                foreach ($this->inParams['loop'] as $key => $value) {
//                cekUngu("JURNAL :: $key => $value ::");
                    $rekName = $accountStrukturAlias[$key];
                    $lCounter++;
                    if (!isset($this->outParams[$lCounter])) {
                        $this->outParams[$lCounter] = array();
                    }

                    //  region deteksi position rekening debet vs kredit
                    $position = detectRekPosition($key, $value);
                    cekUngu("POSITION: $position :: JURNAL :: [$key] [$rekName] => $value ::");
                    if (strlen($position) < 3) {
                        mati_disini(__LINE__ . " gagal menentukan posisi rekening DEBET / KREDIT dari rekening $key " . __FUNCTION__ . " " . __FILE__);
                    }

                    $this->outParams[$lCounter][$position] = abs($value);
                    $this->outParams[$lCounter]['rekening'] = $key;

                    $this->outParams[$lCounter]["rekening_2"] = isset($accountStrukturAlias_old[$key]) ? $accountStrukturAlias_old[$key] : "";
                    $this->outParams[$lCounter]["rekening_alias"] = isset($accountStrukturAlias[$key]) ? $accountStrukturAlias[$key] : "";

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
//                arrPrint($this->outParams);

//                $strImg = "";
//                foreach ($this->outParams as $lCounter => $pSpec) {
//                    $strImg .= "<table border='1' cellspacing='0' cellpadding='0'>";
//                    $strImg .= "<tr>";
//                    foreach ($pSpec as $k => $v) {
//                        $strImg .= "<td>$k</td>";
//                    }
//                    $strImg .= "</tr>";
//
//                    $strImg .= "<tr>";
//                    foreach ($pSpec as $k => $v) {
//                        $strImg .= "<td>$v</td>";
//                    }
//                    $strImg .= "</tr>";
//                    $strImg .= "</table>";
//                }


                //  region balancing debet vs kredit
//                $balance = 1;
//                if (isset($this->inParams['static']['balance'])) {
//                    $balance = $this->inParams['static']['balance'];
//                }
//                if ($balance == 1) {
////                $akumJmlDebet = pembulatanDiskon($akumJml["debet"]);
////                $akumJmlKredit = pembulatanDiskon($akumJml["kredit"]);
////                cekMErah($akumJmlDebet . " vs $akumJmlKredit");
////                if ($akumJmlDebet != $akumJmlKredit) {
//                    $selisih = $akumJml["debet"] - $akumJml["kredit"];
//                    $selisih = reformatExponent($selisih);
//                    $selisih = ($selisih < 0) ? ($selisih * -1) : $selisih;
//                    if ($selisih > 5) {
//                        //                cekHere("selisih: " . $akumJml["debet"] - $akumJml["kredit"]);
//                        //                matiHEre($akumJml["debet"] . " KREDIT: " . $akumJml["kredit"]);
//                        $msg = "Transaksi gagal disimpan kode error: J" . "-line:" . __LINE__;
//                        $msg .= "<br>Silahkan diperiksa kembali atau hubungi admin.";
//                        cekOrange("Function: <b>" . __FUNCTION__ . "</b><br> File: <b>" . __CLASS__ . "</b><br> == mati disini == <br> PESAN ERR: <b>$msg</b>");
//                        die(lgShowAlert($msg));
//                        return false;
//                    }
//
//                    // if (round($akumJml["debet"], 2) != round($akumJml["kredit"], 2)) {
//                    //     if (round($akumJml["debet"], 1) != round($akumJml["kredit"], 1)) {
//                    //         if (floor($akumJml["debet"]) != floor($akumJml["kredit"])) {
//                    //
//                    //             $msg = "Transaksi gagal karena kesalahan dijurnal, DEBET: " . floor($akumJml["debet"]) . " != KREDIT: " . floor($akumJml["kredit"]);
//                    //             cekOrange("Function: <b>" . __FUNCTION__ . "</b><br> File: <b>" . __CLASS__ . "</b><br> == mati disini == <br> PESAN ERR: <b>$msg</b>");
//                    //             die(lgShowAlert($msg));
//                    //             return false;
//                    //         }
//                    //         else {
//                    //             cekHere(":: loloos floor, tanpa digit desimal ::");
//                    //         }
//                    //     }
//                    //     else {
//                    //         cekHere(":: loloos round 1 digit desimal ::");
//                    //     }
//                    // }
//                    // else {
//                    //     cekHere(":: loloos round 2 digit desimal ::");
//                    // }
//                }
                //  endregion balancing debet vs kredit


//                return true;
            }
//            else {
//                echo "loop params are empty<br>";
//                return false;
//            }

        }

        return true;
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
