<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComTransaksiStepUpdater extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel
    );
    private $outParams2 = array( //===output ke tabel rinci
    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache
                                //        "id",
                                //        "cabang_id",
                                //        "cabang_nama",
                                //        "extern_id",
                                //        "extern_nama",
                                //        "jenis",
                                "next_step_code",
                                "next_step_label",
                                "next_group_code",
                                "next_step_num",
                                "step_current",
    );

    private $outFields2 = array( // dari tabel rek_cache
        //        "id",
        //        "cabang_id",
        //        "cabang_nama",
        //        "extern_id",
        //        "extern_nama",
        //        "jenis",
        "next_substep_code",
        "next_substep_label",
        "next_subgroup_code",
        "next_substep_num",
        "sub_step_current",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->writeMode = "update";
        $this->inParams = $inParams;

        cekbiru("inparams milik stepupdater");
        arrprint($this->inParams);

        if (sizeof($this->inParams['loop']) > 0) {

            $lCounter = 0;
            foreach ($this->inParams['loop'] as $key => $arrValue) {
                foreach ($arrValue as $value) {
                    $lCounter++;
                    $this->outParams[$lCounter]["id"] = $value;

                    //==induk transaksi
                    foreach ($this->inParams['static'] as $key_static => $value_static) {
                        if (in_array($key_static, $this->outFields)) {
                            $this->outParams[$lCounter][$key_static] = $value_static;
                        }
                    }
                    //==rincian transaksi
                    foreach ($this->inParams['static2'] as $key_static => $value_static) {
                        if (in_array($key_static, $this->outFields2)) {
                            $this->outParams2[$lCounter][$key_static] = $value_static;
                        }
                    }
                }


            }
        }

//        if (sizeof($this->outParams) > 0) {
//            return true;
//        }
//        else {
//            return false;
//        }
        return true;
    }

    public function exec()
    {
        if (sizeof($this->outParams) > 0) {
            arrPrint($this->outParams);
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("MdlTransaksi");
                //==update pokok transaksi
                $l = new MdlTransaksi();
                $insertIDs = array();
                switch ($this->writeMode) {
                    case "update":
                        $insertIDs[] = $l->updateData(array(
                            "id" => $params['id'],
                        ), $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekhijau($this->db->last_query());


            }

            if (sizeof($this->outParams2) > 0) {
                arrPrint($this->outParams2);
                foreach ($this->outParams2 as $ctr => $params) {
                    //==update rincian transaksi
                    $l = new MdlTransaksi();
                    $l->setFilters(array());
                    $l->setTableName($l->getTableNames()['detail']);
                    $insertIDs = array();
                    switch ($this->writeMode) {
                        case "update":
                            $insertIDs[] = $l->updateData(array(
                                "transaksi_id" => $this->outParams[1]['id'],
                            ), $params);
                            break;
                        default:
                            die("unknown writemode!");
                            break;
                    }
                    cekBiru($this->db->last_query());
                }
            }

            if (sizeof($insertIDs) > 0) {
                return true;
            } else {
                return false;
            }

        } else {
//            die("nothing to write down here");
//            return false;
            return true;
        }

    }

    private function cekPreValue($jenis, $cabang_id, $transaksiID = 0)
    {

        $this->load->model("MdlTransaksi");
        $l = new MdlTransaksi();


        $l->addFilter("id='$transaksiID'");
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");

        $tmp = $l->lookupAll()->result();
        cekMerah($this->db->last_query() . " # " . count($tmp));

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = $row->id;
            }
        } else {
            $result = null;
        }
        //  endregion mengambil saldo dari rek_cache

        return $result;
    }
}