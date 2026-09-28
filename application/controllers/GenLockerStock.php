<?php

class GenLockerStock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->selectedField = array(
            "jumlah", "nama"
        );
    }

    public function generate_data()
    {

//        $mode = "1";//FG
//        $mode="2";//supplies
//        $mode="3";//aset

        $mode = "2";//default biar gak dieksekusi
        $mode_data = "";
        switch ($mode) {
            case "1" :
                $this->load->model("Mdls/MdlLockerStock");
                $this->load->model("Mdls/MdlLockerStockCache");
                $mdlCache = "MdlLockerStock";
                $l = new MdlLockerStock();
                $m = new MdlLockerStockCache();
                $mode_data = "produk";
                break;
            case "2":
                $this->load->model("Mdls/MdlLockerStockSupplies");
                $this->load->model("Mdls/MdlLockerStockSuppliesCache");
                $mdlCache = "MdlLockerStockSupplies";
                $l = new MdlLockerStockSupplies();
                $m = new MdlLockerStockSuppliesCache();
                $mode_data = "supplies";
                break;
            case"3":
                matiHEre("belum di apa apain");
                break;
            default:
                matiHere("mode not found on line :: " . __LINE__ . " :: " . __FILE__);
                break;

        }
//        $this->load->model("Mdls/MdlLockerStockSupplies");
//        $l = new MdlLockerStockSupplies();


        switch ($mode) {
            case "1":
                $l->addFilter("jenis='produk'");
                break;
            case "2":
                $l->addFilter("jenis='supplies'");
                break;
            case "3":
                $l->addFilter("jenis='aktiva'");
                break;
        }
        $l->addFilter("jenis_locker='stock'");
        $l->addFilter("state='active'");
        $tmp = $l->lookUpAll()->result();
        cekLime($this->db->last_query());


        $h = new $mdlCache();
        $h->setfilters(array());
//        $h->addFilter("jenis in('produk','supplies','aktiva','produk_rakitan')");
//        $h->addFilter("jenis='produk'");
        switch ($mode) {
            case "1":
                $h->addFilter("jenis='produk'");
                break;
            case "2":
                $h->addFilter("jenis='supplies'");
                break;
            case "3":
                $h->addFilter("jenis='aktiva'");
                break;
        }
//        $h->addFilter("jenis='supplies'");
        $h->addFilter("jenis_locker='stock'");
        $h->addFilter("state='hold'");
        $h->addFilter("jumlah > 0");
        $h->addFilter("oleh_id > 0");
//        $h->addFilter("trash ='0'");
        $tmp2 = $h->lookUpAll()->result();
        cekLime($this->db->last_query());

        if (sizeof($tmp) > 0) {
            $tmpProds = array();
            foreach ($tmp as $tmp0) {
                $tmpData = array();
                foreach ($this->selectedField as $key) {
                    $tmpData[$key] = $tmp0->$key;
                }
                $tmpProds[$tmp0->cabang_id][$tmp0->gudang_id][$tmp0->produk_id] = $tmpData;
            }
        }
        $tmpProds2 = array();
        if (sizeof($tmp2) > 0) {

            foreach ($tmp2 as $tmp20) {
                $tmpData2 = array();
                foreach ($this->selectedField as $key) {
                    $tmpData2[$key] = $tmp20->$key;
                }
                $tmpProds2[$tmp20->cabang_id][$tmp20->gudang_id][$tmp20->produk_id] = $tmpData2;
            }
        }

        $this->db->trans_start();
        $total_data = 0;
        if (sizeof($tmpProds) > 0) {
//            $this->load->model("Mdls/MdlLockerStockCache");
//
//            $m = new MdlLockerStockCache();
//            $this->load->model("Mdls/MdlLockerStockSuppliesCache");
//            $m = new MdlLockerStockSuppliesCache();

            foreach ($tmpProds as $cID => $tempDataCID) {
                foreach ($tempDataCID as $gID => $gidData) {
                    foreach ($gidData as $pID => $pidData) {
                        $qty = isset($tmpProds2[$cID][$gID][$pID]['jumlah']) ? $tmpProds2[$cID][$gID][$pID]['jumlah'] + $pidData['jumlah'] : $pidData['jumlah'];
                        $nama = $pidData['nama'];
                        $inserData = array(
                            "extern_id"   => $pID,
                            "extern_nama" => $nama,
                            "cabang_id"   => $cID,
                            "gudang_id"   => $gID,
                            "qty_debet"   => $qty,
//                            "jenis" =>"produk",
                            "jenis"       => $mode_data,
                        );
                        $insertID = $m->addData($inserData, $m->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                        if ($insertID) {
                            $total_data++;
                        }
                        cekHitam($this->db->last_query());

                    }
                }
            }
        }

//arrPrint($tmpProds2);
        matiHEre("hoopppp comatcomit total row data ditulis " . $total_data);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekMerah("insert selesai total row " . $total_data);
    }
}

?>