<?php

/*
 * biar bisa load MdlTransaksi oleh receiptElement
 */
require APPPATH.'/models/MdlTransaksi.php';//just add this line and keep rest

class MdlTransaksi2supplier extends MdlTransaksi
{
    public function __construct()
    {
        parent::__construct();
    }

    public function lookupAll_()
    {
        $thisTableMain = $this->tableNames['main'];
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
        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
            $this->db->order_by($thisTableMain . "." . $this->sortBy['kolom'], $this->sortBy['mode']);
        }

        $selectColumns = [
            "transaksi.id AS id",
            "transaksi.nomer AS nomer",
            "transaksi.fulldate AS fulldate",
//            "_rek_pembantu_uang_muka_reference_cache.id AS refRekID",
//            "_rek_pembantu_uang_muka_reference_cache.debet AS um_debet",
            "transaksi_uang_muka_source.id AS refUMID",
            "transaksi_uang_muka_source.sisa AS um_debet",

            "transaksi_payment_source.id AS refPayID",
            "transaksi_payment_source.sisa AS po_sisa",
//            "transaksi_uang_muka_source.sisa AS po_sisa",
        ];
        $selectString = implode(', ', $selectColumns);

        $this->db->select($selectString);
//        $this->db->join('_rek_pembantu_uang_muka_reference_cache', "_rek_pembantu_uang_muka_reference_cache.extern2_id = $thisTableMain.id AND _rek_pembantu_uang_muka_reference_cache.periode = 'forever' AND _rek_pembantu_uang_muka_reference_cache.rekening='1010050010'");
        $this->db->join('transaksi_uang_muka_source', "transaksi_uang_muka_source.extern2_id = $thisTableMain.id");
        $this->db->join('transaksi_payment_source', "transaksi_payment_source.transaksi_id = $thisTableMain.id ");
        $res = $this->db->get( $thisTableMain );

         showLast_query("hitam");
        // cekHijau(count($res));

        return $res;

    }

    public function lookupAll()
    {
        $thisTableMain = $this->tableNames['main'];
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
        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
            $this->db->order_by($thisTableMain . "." . $this->sortBy['kolom'], $this->sortBy['mode']);
        }

        $selectColumns = [
            "transaksi.id AS id",
            "transaksi.nomer AS nomer",
            "transaksi.fulldate AS fulldate",
            "transaksi.id_master AS id_master",
        ];
        $selectString = implode(', ', $selectColumns);
        $this->db->select($selectString);
        $this->db->from($thisTableMain);
        $this->db->order_by('transaksi.dtime', 'ASC');
        $tmp = $this->db->get()->result();

        $jenis_selesai = array(
            "467", "1467", "461", "463", "3463"
        );

        $res = [];
        $arrMasterID = array();
        $trNilaiCheck = array();
        if(!empty($tmp)){
            foreach($tmp as $row){
                $arrMasterID[$row->id_master] = $row->id_master;
                $trNilaiCheck[$row->id_master] = $row->transaksi_nilai;
            }

            $selectColumns = [
                "transaksi.id AS id",
                "transaksi.nomer AS nomer",
                "transaksi.fulldate AS fulldate",
                "transaksi.id_master AS id_master",
                "transaksi.transaksi_nilai AS transaksi_nilai",
                "transaksi.jenis AS jenis",
            ];
            $selectString = implode(', ', $selectColumns);
            $this->db->select($selectString);
            $this->db->from($thisTableMain);
            $this->db->where_in("id_master", $arrMasterID);
            $this->db->where_in("jenis", $jenis_selesai);
            $this->db->order_by('transaksi.dtime', 'ASC');
            $trCek = $this->db->get()->result();

            $total_grn_all = 0;
            $total_grn = array();
            $trid_grn = array();
            if (sizeof($trCek) > 0) {
                foreach ($trCek as $trCekSpec) {
                    $jenis = $trCekSpec->jenis;
                    $mid = $trCekSpec->id_master;
                    $mainNilai = isset($trNilaiCheck[$mid]) ? $trNilaiCheck[$mid] : 0;
                    if(!isset($total_grn[$mid])){
                        $total_grn[$mid] = 0;
                    }
                    $total_grn[$mid] += $trCekSpec->transaksi_nilai*1;
                    $total_grn_all += $total_grn[$mid];
                    $trid_grn[$trCekSpec->id] = $trCekSpec->id;
                }
            }

            ksort($total_grn);

            cekMerah("trCek: " . count($trCek) );
            cekMerah("total_grn: " . count($total_grn) );
            cekMerah("trid_grn: " . count($trid_grn) );
            cekMerah("total_grn_all: " . $total_grn_all );
            arrPrint($total_grn);
//            arrPrint($trCek);
        }


        showLast_query("hitam");
        // cekHijau(count($res));

        return $res;

    }

//$criteria = array();
//$criteria2 = "";
//if (sizeof($this->filters) > 0) {
//$this->fetchCriteria();
//$criteria = $this->getCriteria();
//$criteria2 = $this->getCriteria2();
//}
//if (sizeof($criteria) > 0) {
//    $this->db->where($criteria);
//}
//if ($criteria2 != "") {
//    $this->db->where($criteria2);
//}
//
//$this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
//
//$this->db->group_start();
//$this->db->where(array("transaksi.cabang_id" => $this->session->login['cabang_id']));
//$this->db->or_where(array("transaksi.cabang2_id" => $this->session->login['cabang_id']));
//$this->db->group_end();
//
//$this->db->group_start();
//$this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
//$this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
//$this->db->group_end();
//
//$this->db->order_by("transaksi.id", "desc");
//$this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
//$result = $this->db->get($this->tableNames['main']);
//
//return $result;
}