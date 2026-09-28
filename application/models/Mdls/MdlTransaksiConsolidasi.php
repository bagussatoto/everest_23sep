<?php
// require_once "../Coms/ComRekeningPembantuRaw.php";
require_once APPPATH . "models/Coms/ComRekeningPembantuRaw.php";

class MdlTransaksiConsolidasi extends ComRekeningPembantuRaw
// class MdlTransakiDataConsolidasi extends MdlMother
{

    /*
     * turunan dari Com
     * custom query dioverwrite dari model untuk bisa dibawa ke manapun sehingga ada satu komando data
     * */
    public function __construct()
    {
        parent::__construct();
    }
    // protected $tableName = "transaksi_data_consolidasi";
    protected $tableName = "transaksi_consolidasi";
    // public function callSummaryHarian()
    // {
    //     $coa_kode = 4;
    //     $koloms = array(
    //         "sum(kredit) as 'sum_kredit'",
    //         "sum(debet) as 'sum_debet'",
    //         // "(qty_kredit*hpp) as 'mul_hpp'",
    //         "sum((qty_kredit*hpp)) as 'sum_hpp'",
    //         // "sum(mul_hpp) as 'sum_hpp'",
    //         "thn",
    //         "bln",
    //         "tgl",
    //
    //         // "kredit",
    //         // "qty_kredit",
    //         // "hpp",
    //     );
    //     $this->db->select($koloms);
    //     $this->db->group_by("thn,bln,tgl");
    //     $srcs = $this->fetchMovesAllExtern($coa_kode);
    //     // cekHere(count($srcs));
    //
    //     return $srcs;
    // }

    protected $jointSelectFields;

    public function getJointSelectFields()
    {
        return $this->jointSelectFields;
    }

    public function setJointSelectFields($jointSelectFields)
    {
        $this->jointSelectFields = $jointSelectFields;
    }

    public function lookupDataRegistries()
    {
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

        if (isset($this->jointSelectFields)) {

            $this->db->select($this->jointSelectFields);
        }
//        else {
//            $tmpExpl = implode(",", $this->getFields()["dataRegistry"]);
//            $this->db->select($tmpExpl);
//        }
        // $this->db->select("*");

        return $this->db->get("transaksi_data_registry_consolidasi");
    }

    public function lookupDataRegistriesByMasterID($machineID,$id)
    {
        return $this->db->get_where("transaksi_data_registry_consolidasi", array("machine_id"=> $machineID, "transaksi_id" => $id));
    }
    // ----------------------------------------------------start-----------
    public function lookupPenjualan()
    {
        $jenis = "582";
        /* ---------------------------------------------------------------------------
         * kalau mau nambah yg di select bisa dari pengguna pakai this->db->select()
         * ---------------------------------------------------------------------------*/
        $koloms = array(
            // "sum(kredit) as 'sum_kredit'",
            // "sum(debet) as 'sum_debet'",
            // // "(qty_kredit*hpp) as 'mul_hpp'",
            // "sum((qty_kredit*hpp)) as 'sum_hpp'",
            // "sum(mul_hpp) as 'sum_hpp'",
            // "count(id) as 'baris'",
            // "count(transaksi_id) as 'baris'",
            "id",
            "transaksi_nilai",
            "thn",
            "bln",
            "tgl",
            "dtime",
            "fulldate",
            "nomer",
            "nomer2",
            "customers_id",
            "customers_nama",
            "oleh_nama",
            "machine_id",
            "cabang_id",
            "cabang_nama",
            "pembayaran_sys",
        );
        $this->db->select($koloms);
        $condites = array(
            "jenis" => $jenis,
        );
        $this->db->where($condites);
        // $this->db->group_by("thn,bln");
        $srcs = $this->lookupAll()->result();
        // cekHere(count($srcs));
        // arrPrintHijau($srcs);

        return $srcs;
    }

    public function callSummaryJmlNotaBulanan()
    {

        $srcs = $this->lookupPenjualan();
        // cekHere(count($srcs));
        // arrPrintHijau($srcs);

        return $srcs;
    }

    public function callSummaryPenjualanHarian()
    {
        $consoli_kolom = array(
            "count(id) as 'jml_nota'",
            "sum(transaksi_nilai) as 'nilai_nota'",
            "DATE_FORMAT(dtime, '%Y-%m-%d') AS 'year_month_day'",
            // "DATE_FORMAT(dtime, '%Y-%m') AS 'year_month'"
        );
        $this->db->select($consoli_kolom);
        $this->db->group_by("cabang_id,thn,bln,tgl");
        // $this->db->group_by("thn,bln");

        $srcs = $this->lookupPenjualan();

        return $srcs;
    }
    // -----------------------------------------------------end-----------

    // public function callSummarySellerHarian(){
    //     $coa_kode = 4;
    //     $koloms = array(
    //         "sum(kredit) as 'sum_kredit'",
    //         "sum(debet) as 'sum_debet'",
    //         // "(qty_kredit*hpp) as 'mul_hpp'",
    //         "sum((qty_kredit*hpp)) as 'sum_hpp'",
    //         // "sum(mul_hpp) as 'sum_hpp'",
    //         "thn",
    //         "bln",
    //         "tgl",
    //         "produk_id",
    //         "produk_nama",
    //         // "id",
    //         // "kredit",
    //         // "qty_kredit",
    //         // "hpp",
    //     );
    //     $this->db->select($koloms);
    //     // $this->db->group_by("thn,bln,tgl,produk_id");
    //     $this->db->group_by("produk_id");
    //     $srcs = $this->fetchMovesAllExtern($coa_kode);
    //     // cekHere(count($srcs));
    //
    //     return $srcs;
    // }
    //
    // public function callSummaryProdukBulanan(){
    //     $coa_kode = 4;
    //     $koloms = array(
    //         "sum(qty_kredit) as 'sum_qty_kredit'",
    //         "sum(kredit) as 'sum_kredit'",
    //         "sum(debet) as 'sum_debet'",
    //         // "(qty_kredit*hpp) as 'mul_hpp'",
    //         "sum((qty_kredit*hpp)) as 'sum_hpp'",
    //         // "sum(mul_hpp) as 'sum_hpp'",
    //         "thn",
    //         "bln",
    //         "tgl",
    //         "produk_id",
    //         "produk_nama",
    //         // "id",
    //         // "kredit",
    //         // "qty_kredit",
    //         // "hpp",
    //     );
    //     $this->db->select($koloms);
    //     // $this->db->group_by("thn,bln,tgl,produk_id");
    //     // $this->db->group_by("produk_id,thn,bln,tgl");
    //     $this->db->group_by("produk_id,thn,bln");
    //     // $this->db->group_by("produk_id");
    //     $srcs = $this->fetchMovesAllExtern($coa_kode);
    //     // cekHere(count($srcs));
    //
    //     return $srcs;
    // }
}