<?php

class GeneratePpn extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
//        $this->selectedField = array(
//            "jumlah", "nama"
//        );
    }

    public function generate_data()
    {
        $this->load->model("MdlTransaksi");
        $t = new MdlTransaksi();
        $this->db->limit(1);
//        $this->db->order_by("id","asc");
        $t->addFilter("sinkron='0'");
        $t->addFilter("jenis in (111,112,113)");
        $trTmp = $t->lookupAll()->result();
        cekHere($this->db->last_query());
        $this->db->trans_start();
//        arrPrint($trTmp);
        if(sizeof($trTmp)>0){
            $trID = $trTmp[0]->id;
            $t->setFilters(array());
            $t->addFilter("param='main'");
            $t->addFilter("transaksi_id='$trID'");
            $tmpReg =$t->lookupRegistries()->result();
            $arrValues = blobDecode($tmpReg[0]->values);
            arrPrint($arrValues);
            $arrData = array();
            if($arrValues['nilai_tambah_ppn_in'] > 0){
                $arrData = array(
                    "jenis" => $arrValues['stepCode'],
                    "target_jenis" => "0000",
                    "reference_jenis" => $arrValues['jenis'],
                    "extern_id" => $arrValues['pihakID'],
                    "extern_nama" =>$arrValues['pihakName'],
                    "nomer" => $arrValues['nomer'],
                    "label" =>"ppn realisasi",
                    "tagihan" => $arrValues['nilai_tambah_ppn_in'],
                    "terbayar" => 0,
                    "sisa" => isset($arrValues['nilai_tambah_ppn_in']) ? $arrValues['nilai_tambah_ppn_in'] : 0,
                    "cabang_id" =>$arrValues['placeID'],
                    "cabang_nama" => $arrValues['placeName'],
                    "oleh_id" => $arrValues['olehID'],
                    "oleh_nama" => $arrValues['olehName'],
                    "dtime" => $arrValues['dtime'],
                    "fulldate" => $arrValues['fulldate'],
//                    "extern_date2" => isset($arrValues['dateFaktur']) ? $arrValues['dateFaktur'] :"",
                    "extern_nama2" => isset($arrValues['efakturSource']) ? $arrValues['efakturSource']:"",
                    "valas_id" => "0",
                    "valas_nama" => "0",
                    "valas_nilai" => "0",
                    "tagihan_valas" => "0",
                    "terbayar_valas" => "0",
                    "sisa_valas" => "0",
                    "ppn" => isset($arrValues['ppn']) ? $arrValues['ppn']:"0",
                    "ppn_status" => 0,
                    "extern_nilai2" => isset($arrValues['nilai_dpp_ppn']) ? $arrValues['nilai_dpp_ppn']: $arrValues['nilai_tambah_ppn_in']*10,
                    "extern_date2" => isset($arrValues['dateFaktur']) ? $arrValues['dateFaktur'] :"",
                    "pph_23" => 0,
                    "npwp" => isset($arrValues['vendorDetails__npwp']) ? $arrValues['vendorDetails__npwp']:"",
                    "extern_label2" => isset($arrValues['eFaktur']) ? $arrValues['eFaktur']:"",

                );
            }

            if(sizeof($arrData)>0){
                //insert payment source data ppn ada
                $t = new MdlTransaksi();
                $inserID = $t->writePaymentSrc($trID,$arrData);
                cekHitam($this->db->last_query());
                if($inserID>0){
                    //marking transksi
                    $marking = array(
                        "sinkron"=>"1",
                    );
                    $t = New MdlTransaksi();
                    $t->setFilters(array());
                    $where = array(
                        "id" => $trID,
                    );
                    $updateData = array(
                        "sinkron" => 1,
                    );
                    $t->updateData($where, $updateData);
                    cekHere($this->db->last_query());
                }
            }else{
                //marking transaksi langsung marking trasnki done karena tidak ada data ppn
                $marking = array(
                    "sinkron"=>"1",
                );
                $t = New MdlTransaksi();
                $t->setFilters(array());
                $where = array(
                    "id" => $trID,
                );
                $updateData = array(
                    "sinkron" => 1,
                );
                $t->updateData($where, $updateData);
                cekHere($this->db->last_query());
            }

//arrPrint($arrData);
//matiHEre($tmpReg[0]->hpp);


//            arrPrint($arrData);
        }
        else{
            cekHitam("data habis brooo");
            cekMerah("insert selesai horeee ");
        }
//        matiHEre("hoopppp comatcomit total row data ditulis " );
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

    }
}

?>