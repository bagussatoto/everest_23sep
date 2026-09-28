<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/3/2018
 * Time: 3:41 PM
 */
class ComPlafonData extends MdlMother
{
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array(
        // dari tabel rek_cache
        "extern_id",
        "extern_nama",
        "cabang_id",
        "nilai",
        "status",
        "trash",
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Mdls/MdlPlafonHutangBank");

    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            $_preValue = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $_preValue = $this->cekPreValue($paramAsli['static']['cabang_id'], $paramAsli['static']['extern_id']);
//                matiHEre($_preValue);
                if ($_preValue != null) {
                    $where = array(
                        "cabang_id" => $paramAsli['static']['cabang_id'],
                        "extern_id" => $paramAsli['static']['extern_id'],
                    );
                    $tmpUpdateCache = array(
                        "nilai" => ($paramAsli['static']['nilai'] + $_preValue),
                    );

                    $c = new MdlPlafonHutangBank();
                    $insertIDs[] = $c->updateData($where, $tmpUpdateCache);
                }
                else {
                    //region insert baru
                    $this->outParams[$lCounter]["cache"]["debet"] = $paramAsli['static']['debet'];
                    $this->outParams[$lCounter]["cache"]["mode"] = "new";
                    $tmpUpdateCache = array(
                        "cabang_id" => $paramAsli['static']['cabang_id'],
                        "extern_id" => $paramAsli['static']['extern_id'],
                        "extern_nama" => $paramAsli['static']['extern_nama'],
                        "nilai" => ($paramAsli['static']['nilai']),
                    );
                    $c = new MdlPlafonHutangBank();
                    $insertIDs[] = $c->addData($tmpUpdateCache);
                    cekBiru($this->db->last_query());
                    //endregion
                }
            }

            if (sizeof($insertIDs) > 0) {
                return true;
            } else {
                return false;
            }

        }
    }

    private function cekPreValue($cabang_id, $produk_id)
    {

        $this->load->model("Mdls/MdlPlafonHutangBank");
        $l = new MdlPlafonHutangBank();
        $this->addFilter("cabang_id='$cabang_id'");
        $this->addFilter("extern_id='$produk_id'");
        $result = array();
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
//arrPrint($this->filters);
        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();

        $tmp = $this->db->query("{$query} FOR UPDATE")->row_array();
        cekHitam($this->db->last_query());
        if (sizeof($tmp) > 0) {
            $nilai = $tmp['nilai'];
        } else {
            $nilai = null;
        }

//        matiHEre($nilai);
        return $nilai;
    }

    public function exec()
    {

        return true;

    }
}