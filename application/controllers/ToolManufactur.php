<?php


class ToolManufactur extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
    }

    function index()
    {
        $arrTools = array(
            "kas" => "viewUnsyncedKas",
            "produk" => "viewUnsyncedProduk",
            "produk rakitan" => "viewUnsyncedProdukRakitan",
            "supplies" => "viewUnsyncedSupplies",
            "valas" => "viewUnsyncedValas",
        );

//        foreach ($arrTools as $key => $value) {
//            echo "<div>";
//            echo "<h3>";
//            echo "<a href='" . base_url() . get_class($this) . "/$value' target='_blank'>:: $key ::</a>";
//            echo "</h3>";
//            echo "</div>";
//        }
    }

    function manufacture()
    {
        $this->load->model("Mdls/MdlProdukRakitan");
        $this->load->model("Mdls/MdlProdukFase");
        $this->load->model("Mdls/MdlLockerStockSupplies");
        $cabangID = "25";//tembak dulu... 25

        $pr = New MdlProdukRakitan();
        $prTmp = $pr->lookupAll()->result();

        $prf = New MdlProdukFase();
        $prfTmp = $prf->lookupAll()->result();


        $items = array();
        if (sizeof($prTmp) > 0) {
            $produk_ids = array();
            $status_manufactur = array();
            $status_manufactur_produk = array();
            foreach ($prTmp as $prSpec) {
                if ($prSpec->status_manufactur == 1) {
                    $status_manufactur[$prSpec->id] = $prSpec->id;
                }
                $status_manufactur_produk[$prSpec->id] = $prSpec->status_manufactur;
                $produk_ids[$prSpec->id] = $prSpec->id;
            }
//            arrPrint($status_manufactur);
            // daftar gudang
            if (sizeof($prfTmp) > 0) {
                $gudangIDs = array();
                $gudangIDStok = array();
                $gudangIDsByProduk = array();
                $ProdukGudangBahan = array();
                foreach ($prfTmp as $prfSpec) {
                    $gudangIDs[$prfSpec->gudang_id] = $prfSpec->gudang_id;
                    $gudangIDsByProduk[$prfSpec->produk_id][$prfSpec->gudang_id] = $prfSpec->gudang_id;
                }
                // stok produk locker stok bahan baku masing-masing gudang
                $ls = New MdlLockerStockSupplies();
                $ls->addFilter("cabang_id=$cabangID");
                $ls->addFilter("state='active'");
                $ls->addFilter("gudang_id in ('" . implode("','", $gudangIDs) . "')");
                $lsTmp = $ls->lookupAll()->result();
//                showLast_query("biru");
                if (sizeof($lsTmp) > 0) {
                    foreach ($lsTmp as $spec) {
                        if ($spec->jumlah > 0) {
                            $gudangIDStok[$spec->gudang_id][$spec->produk_id] = $spec->jumlah;
                        }
                    }
                }
                foreach ($gudangIDsByProduk as $pID => $pspec) {
                    foreach ($pspec as $gudang_id) {
                        if (isset($gudangIDStok[$gudang_id])) {
                            $ProdukGudangBahan[$pID][$gudang_id] = $gudangIDStok[$gudang_id];
                        }
                    }
                }
            }

            foreach ($prTmp as $prSpec) {
                $pid = $prSpec->id;
                $pnama = $prSpec->nama;
                $status_manufacturing = $status_manufactur_produk[$pid];

                $keterangan = "";
                $stokGudangBahanBaku = isset($ProdukGudangBahan[$pid]) ? $ProdukGudangBahan[$pid] : array();
                //---------------------
                if ((sizeof($status_manufactur) > 0) && (!in_array($pid, $status_manufactur))) {
                    $disabled_tombol = "disabled";
                    if (sizeof($stokGudangBahanBaku) > 0) {
                        $disabled_tombol = "disabled";
                    }
//                    else {
//                        $disabled_tombol = "";
//                    }
//
                }
                else {
                    $disabled_tombol = "";
                    if (sizeof($stokGudangBahanBaku) > 0) {
                        $disabled_tombol = "disabled";
                    }
                }
                //---------------------
                if ($status_manufacturing == 1) {
                    $label_tombol = "nonaktifkan proses produksi";
                    $btn_class = "btn-danger";
                    $new_st = 0;
                    $msg = "Anda akan menonaktifkan proses produksi $pnama.";
                }
                else {
                    $label_tombol = "aktifkan proses produksi";
                    $btn_class = "btn-success";
                    $new_st = 1;
                    $msg = "Anda akan mengaktifkan proses produksi $pnama.";
                }
                //---------------------

                $link = base_url() . get_class($this) . "/manufactureExec/MdlProdukRakitan/$pid?st=$new_st";
//                $onclick = "location.href='$link'";
                $status_produksi = "<button class='btn btn-sm $btn_class' $disabled_tombol 
                    onclick=\"if(confirm('$msg')==1){getElementById('result').src='$link';}\">$label_tombol</button>";

                $items[] = array(
                    "pid" => $prSpec->id,
                    "kode" => $prSpec->kode,
                    "jenis" => $prSpec->produk_jenis_nama,
                    "folder" => $prSpec->folders_nama,
                    "nama" => $prSpec->nama,
                    "label" => $prSpec->label,
                    "kategori_nama" => $prSpec->kategori_nama,
                    "status_produksi" => $status_produksi,
                    "keterangan" => $keterangan,
                );
            }
        }

        $headers = array(
            "pid" => "PID",
            "kategori_nama" => "KATEGORI",
            "jenis" => "JENIS PRODUK",
            "folder" => "FOLDER",
            "kode" => "KODE",
            "nama" => "NAMA",
            "label" => "LABEL",
//            "kategori_nama" => "KATEGORI",
            "status_produksi" => "STATUS PRODUKSI",// proses aktif atau nonaktif
            "keterangan" => "KETERANGAN",
        );
        $data = array(
            "mode" => "indexManufactur",
            "title" => "SETTING PRODUKSI",
            "subTitle" => "",
            "headerFields" => $headers,
            "items" => isset($items) ? $items : array(),
            "warning" => isset($warning) ? $warning : array(),
            "marking" => isset($marking) ? $marking : array(),
            "markingColumn" => isset($markingColumn) ? $markingColumn : array(),
            "button" => array(),
            "content" => isset($str) ? $str : "",
        );
        $this->load->view("tool", $data);
    }

    function manufactureExec()
    {
        $mdlName = $this->uri->segment(3);
        $pid = $this->uri->segment(4);
        $new_status = $_GET["st"];
//cekHere(":: $pid :: $new_status :: $mdlName ::");
        $this->db->trans_start();

        $this->load->model("Mdls/$mdlName");
        $m = New $mdlName();
        $m->addFilter("id='$pid'");
        $mTmp = $m->lookupAll()->result();
        if (sizeof($mTmp) > 0) {
            $produk_id = $id_tbl = $mTmp[0]->id;
            $produk_nama = $mTmp[0]->nama;
            $produk_cabang_id = $mTmp[0]->cabang_id;
            $produk_cabang_nama = $mTmp[0]->cabang_nama;

            // update status proses manufactur
            $data = array(
                "status_manufactur" => $new_status
            );
            $where = array(
                "id" => $id_tbl,
            );
            $m->updateData($where, $data);

            // buat history perubahan status proses manufactur
            if ($new_status == 1) {
                $label = "mengaktifkan proses produksi";
            }
            else {
                $label = "menonaktifkan proses produksi";
            }
            $arrData = array(
                "status_manufactur" => $new_status,
                "label" => $label,
                "produk_id" => $produk_id,
                "produk_nama" => $produk_nama,
                "cabang_id" => $produk_cabang_id,
                "cabang_nama" => $produk_cabang_nama,
                "fulldate" => date("Y-m-d"),
                "dtime" => date("Y-m-d H:i:s"),
                "oleh_id" => my_id(),
                "oleh_nama" => my_name(),
            );
            $this->load->model("Mdls/MdlProdukManufacturHistory");
            $hh = New MdlProdukManufacturHistory();
            $hh->addData($arrData);
//            showLast_query("biru");

        }
        else {
            // produk tidak ditemukan......
            mati_disini("perubahan gagal disimpan karena id produk tidak ditemukan. Silahkan menghubungi admin.");
        }


//        mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


        $arrAlert = array(
            "type" => "success",
            "title" => "SUCCESS",
            "html" => "Perubahan berhasil disimpan...",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );
        echo swalAlert($arrAlert);
//        echo "<script>topReload(500)</script>";
        echo topReload();
//        echo "</script>";
    }
}