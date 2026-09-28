<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Modul_Controller.php";

class SyncTool extends Modul_Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function neracaSyncCoa_code()
    {
        $this->load->model("Mdls/MdlAccounts");
        $this->load->model("Mdls/MdlNeracaLajur");
        $this->load->model("Mdls/MdlNeraca");

        $c = new MdlAccounts();
        $nl = new MdlNeracaLajur();
        $n = new MdlNeraca();

        //region data COA
        $c->addFilter("is_active='1'");
        $c->addFilter("is_rekening_pembantu='0'");
//        $c->addFilter("is_gl='1'");
        $temp = $c->lookUpAll()->result();
//        showLast_query("biru");
//cekHere(sizeof($temp));
//mati_disini();
        $dataCoa = array();
        if (sizeof($temp) > 0) {
            foreach ($temp as $temp_0) {
                if (strlen($temp_0->rekening) > 0) {
                    $dataCoa[$temp_0->rekening] = array(
                        "head_code" => $temp_0->head_code,
                        "head_name" => $temp_0->head_name,
                    );
                }

            }
        }
        //endregion
        // arrPrint($dataCoa);
        // matiHere(sizeof($dataCoa));
        //region data neraca lajur/leger
        $tempLajur = array();
        $n->setFilters(array());
        $n->addFilter("cabang_id='-1'");
        $n->addFilter("periode='tahunan'");
        // $nl->addFilter("thn='2021'");
        $tempLajur = $n->fetchBalances('2021');
        $dataLajur = array();
        if (sizeof($tempLajur) > 0) {
            foreach ($tempLajur as $tempLajur_0) {
                $dataLajur[$tempLajur_0->rekening] = array(
                    "id" => $tempLajur_0->id,
                    "label" => $tempLajur_0->rekening,
                );
            }
        }
        // ceklIme($this->db->last_query());
        // arrPrint($dataLajur);

        //endregion
        // arrPrint($dataCoa);
        // matiHEre();

        //region compare untuk update kolom neraca lajur ganti rekening dengna coa
        $toUpdate = array();
        if (sizeof($dataLajur) > 0) {
            foreach ($dataLajur as $lajur_rekening => $dataLajur_0) {
                if (isset($dataCoa[$lajur_rekening])) {
                    $toUpdate[$lajur_rekening] = array(
                        "rekening" => $dataCoa[$lajur_rekening]["head_code"],
                        "rekening_label" => $lajur_rekening,
                    );
                }
                else {
                    // cekBiru($lajur_rekening);
                    cekMErah("$lajur_rekening belum ada dilist COA");
                }
            }
        }
        //endregion
        // arrPrint($toUpdate);
        // matiHEre();
        $this->db->trans_start();


        $n->setFilters(array());
        $i = 0;
        if (sizeof($toUpdate) > 0) {
            foreach ($toUpdate as $rekLabelOld => $udpateData) {
                $where = array("rekening" => $rekLabelOld);
                $insertID = $n->updateData($where, $udpateData) or matiHEre("gagal udpate");
                $i++;
                cekLime($this->db->last_query());
            }
        }

        // arrprint($toUpdate);
        // mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa.., TRID: " . $i);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>--DONE--</h3>");
    }


}