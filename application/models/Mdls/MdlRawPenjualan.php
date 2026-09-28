<?php
// require_once "../Coms/ComRekeningPembantuRaw.php";
require_once APPPATH . "models/Coms/ComRekeningPembantuRaw.php";

class MdlRawPenjualan extends ComRekeningPembantuRaw
{
protected $tbl_1;

    public function getTbl1()
    {
        return $this->tbl_1;
    }

    public function getTbl2()
    {
        return $this->tbl_2;
    }



    /*
     * turunan dari Com
     * custom query dioverwrite dari model untuk bisa dibawa ke manapun sehingga ada satu komando data
     * */
    public function __construct()
    {
        parent::__construct();
        $this->coaCode = "4010";
        $this->tbl_1 = "__raw_rek_pembantu__" . $this->coaCode;
        $this->tbl_2 = "produk";
    }

    //------------------------
    public function callSummarySo()
    {
        $coa_kode = $this->coaCode;
        $koloms = array(
            "sum(qty_kredit) as 'sum_qty_kredit'",
            "sum(qty_debet) as 'sum_qty_debet'",
            "sum(kredit) as 'sum_kredit'",
            "sum(debet) as 'sum_debet'",
            "sum((qty_kredit*hpp)) as 'sum_hpp'",
            //----------
            "year(dtime) as 'thn'",
            "LPAD(month(dtime),2, '0') as 'bln'",
            "LPAD(date(dtime),2, '0') as 'tgl'",
            "oleh_id",
            "oleh_nama",
            "seller_id",
            "seller_nama",
            "produk_id",
            "produk_nama",
            "pihak_id",
            "pihak_nama",
            "cabang_id",
            "cabang_nama",
            "jenis",
            // "hpp",
        );
        $this->db->select($koloms);
        $jenies = array(
            "582so",
            "382so",
            "588so",
            "582sorj",
            "382sorj",
            "588sorj",
        );
        $this->db->where_in("jenis", $jenies);
        // $this->db->group_by("produk_id,year(dtime),month(dtime)");
        $srcs = $this->fetchMovesAllExtern($coa_kode);
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryProdukSoBulanan()
    {

        $this->db->group_by("produk_id,year(dtime),month(dtime)");
        // // $this->db->group_by("produk_id");
        // $srcs = $this->fetchMovesAllExtern($coa_kode);
        $srcs = $this->callSummarySo();
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryCustomerSoBulanan()
    {

        $this->db->group_by("pihak_id,year(dtime),month(dtime)");
        // // $this->db->group_by("produk_id");
        // $srcs = $this->fetchMovesAllExtern($coa_kode);
        $srcs = $this->callSummarySo();
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummarySellerSoBulanan()
    {

        $this->db->group_by("seller_id,year(dtime),month(dtime)");
        // // $this->db->group_by("produk_id");
        // $srcs = $this->fetchMovesAllExtern($coa_kode);
        $srcs = $this->callSummarySo();
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryCabangSoBulanan()
    {

        $this->db->group_by("cabang_id,year(dtime),month(dtime)");
        // // $this->db->group_by("produk_id");
        // $srcs = $this->fetchMovesAllExtern($coa_kode);
        $srcs = $this->callSummarySo();
        // cekHere(count($srcs));

        return $srcs;
    }

    //------------
    public function callJoinSummary()
    {
        $coa_kode = $this->coaCode;
        $tbl_1 = $this->tbl_1;
        $tbl_2 = $this->tbl_2;

        $koloms = array(
            "sum(qty_kredit) as 'sum_qty_kredit'",
            "sum(qty_debet) as 'sum_qty_debet'",
            "sum(kredit) as 'sum_kredit'",
            "sum(debet) as 'sum_debet'",
            "sum((qty_kredit*hpp)) as 'sum_hpp'",
            //----------
            "year($tbl_1.dtime) as 'thn'",
            "LPAD(month($tbl_1.dtime),2, '0') as 'bln'",
            "LPAD(date($tbl_1.dtime),2, '0') as 'tgl'",
            "oleh_id",
            "oleh_nama",
            "merek_id",
            "merek_nama",
            "produk_id",
            "produk_nama",
            "pihak_id",
            "pihak_nama",
            "$tbl_1.tipe_id",
            "$tbl_1.tipe_nama",
            "$tbl_1.cabang_id",
            "$tbl_1.cabang_nama",
            "$tbl_1.jenis",
            "$tbl_2.kategori_id",
            "$tbl_2.kategori_nama",
            // "hpp",
        );
        $this->db->select($koloms);
        $jenies = array(
            "5822spd",
            // "382spd",
            // "7499",
            // "9912",
            "9822",
        );
        $this->db->where_in("$tbl_1.jenis", $jenies);
        // $this->db->group_by("produk_id,year(dtime),month(dtime)");
        // $srcs = $this->fetchMovesAllExtern($coa_kode);
        // cekHere(count($srcs));

        // $this->db->select('orders.order_id, SUM(order_items.quantity) AS total_quantity');
        $this->db->from($tbl_1);
        $this->db->join($tbl_2, "produk.id = $tbl_1.produk_id");
        // $this->db->group_by("$tbl_2.kategori_id,year($tbl_1.dtime),month($tbl_1.dtime)");
        $query = $this->db->get();
        $srcs = $query->result();

        return $srcs;
    }

    public function callSummary()
    {
        $coa_kode = $this->coaCode;
        $koloms = array(
            "sum(qty_kredit) as 'sum_qty_kredit'",
            "sum(qty_debet) as 'sum_qty_debet'",
            "sum(kredit) as 'sum_kredit'",
            "sum(debet) as 'sum_debet'",
            "sum((qty_kredit*hpp)) as 'sum_hpp'",
            //----------
            "year(dtime) as 'thn'",
            "LPAD(month(dtime),2, '0') as 'bln'",
            "LPAD(date(dtime),2, '0') as 'tgl'",
            "oleh_id",
            "oleh_nama",
            "seller_id",
            "seller_nama",
            "produk_id",
            "produk_nama",
            "pihak_id",
            "pihak_nama",
            "cabang_id",
            "cabang_nama",
            "jenis",
            // "hpp",
        );
        $this->db->select($koloms);
        $jenies = array(
            "5822spd",
            // "3822spd",
            // "7499",
            // "9912",
            "9822",
        );
        $this->db->where_in("jenis", $jenies);
        // $this->db->group_by("produk_id,year(dtime),month(dtime)");
        $srcs = $this->fetchMovesAllExtern($coa_kode);
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryKategoriProdukBulanan()
    {
        $tbl_1 = $this->tbl_1;
        $tbl_2 = $this->tbl_2;

        $this->db->group_by("$tbl_2.merek_id,year($tbl_1.dtime),month($tbl_1.dtime)");
        $srcs = $this->callJoinSummary();
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryProdukBulanan()
    {

        $this->db->group_by("produk_id,year(dtime),month(dtime)");
        $srcs = $this->callSummary();
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryCustomerBulanan()
    {

        $this->db->group_by("pihak_id,year(dtime),month(dtime)");
        $srcs = $this->callSummary();
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryCabangBulanan()
    {

        $this->db->group_by("cabang_id,year(dtime),month(dtime)");
        $srcs = $this->callSummary();
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummarySellerBulanan()
    {
        // $coa_kode = 4010;
        // $coa_kode = $this->coaCode;
        // $koloms = array(
        //     "sum(qty_kredit) as 'sum_qty_kredit'",
        //     "sum(qty_debet) as 'sum_qty_debet'",
        //     "sum(kredit) as 'sum_kredit'",
        //     "sum(debet) as 'sum_debet'",
        //     "sum((qty_kredit*hpp)) as 'sum_hpp'",
        //     //----------
        //     "year(dtime) as 'thn'",
        //     "LPAD(month(dtime),2, '0') as 'bln'",
        //     "LPAD(date(dtime),2, '0') as 'tgl'",
        //     "oleh_id",
        //     "oleh_nama",
        //     "seller_id",
        //     "seller_nama",
        //     "jenis",
        //     // "hpp",
        // );
        // $this->db->select($koloms);
        // $extern_ids = array(
        //     "582spd",
        //     "382spd",
        //     "7499",
        //     "9912",
        //     "982",
        // );
        // $this->db->where_in("jenis", $extern_ids);
        // // $this->db->group_by("thn,bln,tgl,produk_id");
        // // $this->db->group_by("produk_id,thn,bln,tgl");
        $this->db->group_by("oleh_id,year(dtime),month(dtime)");
        // // $this->db->group_by("produk_id");
        // $srcs = $this->fetchMovesAllExtern($coa_kode);
        $srcs = $this->callSummary();
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryTipePejualanBulanan()
    {
        $tbl_1 = $this->tbl_1;
        $tbl_2 = $this->tbl_2;

        $this->db->group_by("$tbl_1.tipe_id,year($tbl_1.dtime),month($tbl_1.dtime)");
        $srcs = $this->callJoinSummary();
        // cekHere(count($srcs));

        return $srcs;
    }

    // ----------------------------
    public function callSummaryHarian()
    {
        $coa_kode = 4;
        $koloms = array(
            "sum(kredit) as 'sum_kredit'",
            "sum(debet) as 'sum_debet'",
            // "(qty_kredit*hpp) as 'mul_hpp'",
            "sum((qty_kredit*hpp)) as 'sum_hpp'",
            // "sum(mul_hpp) as 'sum_hpp'",
            "thn",
            "bln",
            "tgl",

            // "kredit",
            // "qty_kredit",
            // "hpp",
        );
        $this->db->select($koloms);
        $this->db->group_by("thn,bln,tgl");
        $srcs = $this->fetchMovesAllExtern($coa_kode);
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryJmlNotaHariann()
    {
        $coa_kode = 4;
        $koloms = array(
            "sum(kredit) as 'sum_kredit'",
            "sum(debet) as 'sum_debet'",
            // "(qty_kredit*hpp) as 'mul_hpp'",
            "sum((qty_kredit*hpp)) as 'sum_hpp'",
            // "sum(mul_hpp) as 'sum_hpp'",
            "thn",
            "bln",
            "tgl",

            // "kredit",
            // "qty_kredit",
            // "hpp",
        );
        $this->db->select($koloms);
        $this->db->group_by("thn,bln,tgl");
        $srcs = $this->fetchMovesAllExtern($coa_kode);
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummaryProdukHarian()
    {
        $coa_kode = 4;
        $koloms = array(
            "sum(qty_kredit) as 'sum_qty_kredit'",
            "sum(kredit) as 'sum_kredit'",
            "sum(debet) as 'sum_debet'",
            // "(qty_kredit*hpp) as 'mul_hpp'",
            "sum((qty_kredit*hpp)) as 'sum_hpp'",
            // "sum(mul_hpp) as 'sum_hpp'",
            "thn",
            "bln",
            "tgl",
            "produk_id",
            "produk_nama",
            "extern2_id",
            // "kredit",
            // "qty_kredit",
            // "hpp",
        );
        $this->db->select($koloms);
        $this->db->group_by("thn,bln,tgl,produk_id");
        // $this->db->group_by("produk_id");
        $srcs = $this->fetchMovesAllExtern($coa_kode);
        // cekHere(count($srcs));

        return $srcs;
    }

    public function callSummarySellerHarian()
    {
        $coa_kode = 4;
        $koloms = array(
            "sum(kredit) as 'sum_kredit'",
            "sum(debet) as 'sum_debet'",
            // "(qty_kredit*hpp) as 'mul_hpp'",
            "sum((qty_kredit*hpp)) as 'sum_hpp'",
            // "sum(mul_hpp) as 'sum_hpp'",
            "thn",
            "bln",
            "tgl",
            "produk_id",
            "produk_nama",
            // "id",
            // "kredit",
            // "qty_kredit",
            // "hpp",
        );
        $this->db->select($koloms);
        // $this->db->group_by("thn,bln,tgl,produk_id");
        $this->db->group_by("produk_id");
        $srcs = $this->fetchMovesAllExtern($coa_kode);
        // cekHere(count($srcs));

        return $srcs;
    }

    //    ---------------keperluan testing---------------
    public function callHarian()
    {
        $coa_kode = 4;
        $koloms = array(
            "transaksi_no",
            "cabang_id",
            "oleh_top_nama",
            "sum(kredit) as 'sum_kredit'",
            "sum(debet) as 'sum_debet'",
            // "(qty_kredit*hpp) as 'mul_hpp'",
            "sum((qty_kredit*hpp)) as 'sum_hpp'",
            // "sum(mul_hpp) as 'sum_hpp'",
            "thn",
            "bln",
            "tgl",

            // "kredit",
            // "qty_kredit",
            // "hpp",
        );
        $this->db->select($koloms);
        $this->db->group_by("oleh_top_nama");
        // $this->db->group_by("transaksi_no");
        // $this->db->group_by("cabang_id");
        $srcs = $this->fetchMovesAllExtern($coa_kode);
        // cekHere(count($srcs));

        return $srcs;
    }
}