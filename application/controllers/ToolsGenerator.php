<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ToolsGenerator extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");


        $this->cabang_id = CB_ID_PUSAT;
        $this->harga_jenis = "jual_reseller";
        $this->pph23 = 15;


    }

    function satuan_nilai()
    {
        $this->db->select("
            id,
            nama,
            barcode,
            satuan_nilai,
            kategori_id,
            kategori_nama,
            sub_kategori_id,
            sub_kategori_nama,
            produk_part_kategori_id,
            produk_part_kategori_nama,
            produk_part_ukuran_id,
            produk_part_ukuran_nama
        ");
//        $this->db->where("kategori_id=3 and sub_kategori_id=5 and status=1 and trash=0 and satuan_nilai is null");
        $this->db->where("kategori_id=3 and sub_kategori_id=5 and status=1 and trash=0");
        $spare_part = $this->db->get("produk")->result();
        $wl = array("M", "m", "meter", "METER", "EMETER", "METETR");
        $this->db->trans_start();
        foreach ($spare_part as $k => $row) {
            $pid = $row->id;
            $sName = str_replace("  ", " ", $row->nama);
            $arr = explode(" ", $sName);
            $query = "UPDATE produk SET satuan_nilai={satuan_nilai} where id = $pid";
            switch (count($arr)) {
                case "3":
//                    arrPrint($arr);
                    $nilai_1 = 1;
                    $satuan_1 = 2;
                    $nilai_2 = 2;
                    $satuan_2 = 3;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "4":
//                    arrPrint($arr);
                    $nilai_1 = 2;
                    $satuan_1 = 3;
                    $nilai_2 = 3;
                    $satuan_2 = 4;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "5":
//                    arrPrint($arr);
                    $nilai_1 = 3;
                    $satuan_1 = 4;
                    $nilai_2 = 4;
                    $satuan_2 = 5;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "6":
//                    arrPrint($arr);
                    $nilai_1 = 4;
                    $satuan_1 = 5;
                    $nilai_2 = 5;
                    $satuan_2 = 6;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "7":
//                    arrPrint($arr);
                    echo $sName . "<br>";
                    $nilai_1 = 5;
                    $satuan_1 = 6;
                    $nilai_2 = 6;
                    $satuan_2 = 7;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "8":
//                    arrPrint($arr);
                    $nilai_1 = 6;
                    $satuan_1 = 7;
                    $nilai_2 = 7;
                    $satuan_2 = 8;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                case "9":
//                    arrPrint($arr);
                    $nilai_1 = 7;
                    $satuan_1 = 8;
                    $nilai_2 = 8;
                    $satuan_2 = 9;

                    if ($arr[$nilai_1] * 1 > 0) {
                        if (in_array($arr[$satuan_1], $wl)) {
//                            echo $pid . "-" . $sName . " <b>(".count($arr).") | (".$arr[1].")</b><br>";
                            $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_1], $query);
                            $thisQuery = str_replace(",", ".", $thisQuery);
                            echo $thisQuery . "<br>";
                            $this->db->query($thisQuery);
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    else {
                        if ($arr[$nilai_2] * 1 > 0) {
                            if (isset($arr[$satuan_2])) {
                                if (in_array($arr[$satuan_2], $wl)) {
                                    echo $pid . "-" . $sName . " <b>(" . count($arr) . ") | (" . $arr[$nilai_2] . ")</b><br>";
                                    $thisQuery = str_replace("{satuan_nilai}", $arr[$nilai_2], $query);
                                    $thisQuery = str_replace(",", ".", $thisQuery);
                                    echo $thisQuery . "<br>";
                                    $this->db->query($thisQuery);
                                }
                                else {
                                    echo "<div style='color: red'>";
                                    arrPrint($arr);
                                    echo $sName . " <b>(" . count($arr) . ")</b><br>";
                                    echo "</div>";
                                }
                            }
                        }
                        else {
                            echo "<div style='color: red'>";
                            arrPrint($arr);
                            echo $sName . " <b>(" . count($arr) . ")</b><br>";
                            echo "</div>";
                        }
                    }
                    break;
                default:
//                    echo "<div style='color: red'>";
//                    arrPrint($arr);
//                    echo $sName . " <b>(".count($arr).")</b><br>";
//                    echo "</div>";
                    break;
            }
        }

//        matiHere("DONE:: belum commit");
        $this->db->trans_commit();
    }

    function nama_produk()
    {

        $this->db->select("
            id,
            nama,
            barcode,
            satuan_nilai,
            kategori_id,
            kategori_nama,
            sub_kategori_id,
            sub_kategori_nama,
            produk_part_kategori_id,
            produk_part_kategori_nama,
            produk_part_ukuran_id,
            produk_part_ukuran_nama
        ");

        $this->db->where("status=1 and trash=0");
        $spare_part = $this->db->get("produk")->result();
        $this->db->trans_start();

        foreach ($spare_part as $k => $row) {
            $pid = $row->id;
            $sName = trim(str_replace("  ", " ", $row->nama));
            $query = "UPDATE produk SET nama='$sName' WHERE id=$pid";
            echo $query . "<br>";
            $this->db->query($query);
        }

//        matiHere("DONE:: belum commit");
        $this->db->trans_commit();
    }


    public function generatePiutangDagangVoid()
    {
        $this->load->helper("he_angka");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlPaymentSource");
        $this->load->model("Coms/ComRekeningPembantuCustomer");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComJurnal");

        $arrDataSource = array(
//            1 => array(
//                "extern_id" => 278,
//                "extern_nama" => "SETIA JAYA ELECTRONIC",
//                "label" => "koreksi piutang dagang pemindahbukuan SETIA JAYA ELECTRONIC request void by Everet",
//            ),
//            2 => array(
//                "extern_id" => 221,
//                "extern_nama" => "PT. SUKSES MAKMUR SOLUSI",
//                "label" => "koreksi piutang dagang pemindahbukuan PT. SUKSES MAKMUR SOLUSI request void by Everet",
//            ),
//            3 => array(
//                "extern_id" => 203,
//                "extern_nama" => "PT. KURNIAMITRA DUTA SENTOSA, Tbk",
//                "label" => "koreksi piutang dagang pemindahbukuan PT. KURNIAMITRA DUTA SENTOSA, Tbk request void by Everet",
//            ),
//            4 => array(
//                "extern_id" => 184,
//                "extern_nama" => "PT. DAPUR COKELAT INDONESIA",
//                "label" => "koreksi piutang dagang pemindahbukuan PT. DAPUR COKELAT INDONESIA request void by Everet",
//            ),
//            5 => array(
//                "extern_id" => 115,
//                "extern_nama" => "HERU (PAMULANG)",
//                "label" => "koreksi piutang dagang pemindahbukuan HERU (PAMULANG) request void by Everet",
//            ),
            6 => array(
                "extern_id" => 152,
                "extern_nama" => "NEW GLODOK ELECRONIC",
                "label" => "koreksi piutang dagang pemindahbukuan NEW GLODOK ELECRONIC request void by Everet",
            ),
            7 => array(
                "extern_id" => 87,
                "extern_nama" => "VICA AC",
                "label" => "koreksi piutang dagang pemindahbukuan VICA AC request void by Everet",
            ),
        );
        $selectKey = 7;

        // region transaksi
        $cabangID = "1";
        $cabangNama = "cabang 1";
        $gudangID = "-10";
        $gudangNama = "default warehouse at branch #1";
        $cabang2ID = "-1";
        $cabang2Nama = "pusat dc";
        $gudang2ID = "-1";
        $gudang2Nama = "default warehouse at branch #-1";
        $olehID = "100";
        $olehNama = "system";
        $tokoID = "0";
        $tokoNama = "";
        $pihakID = $arrDataSource[$selectKey]["extern_id"];
        $pihakNama = $arrDataSource[$selectKey]["extern_nama"];
        $jenis = "99999";
        $this->jenisTr = $jenisTr = "99999";
        $jenisTrMaster = "99999";
        $dtime = date("Y-m-d H:i:s");
        $fulldate = date("Y-m-d");
        $ppnFactor = 11;
        $divID = 18;
        $jenis_target = "749";
        $pym_label = "piutang dagang";
        $pym_jenis = "7778";
        $referencetransaksiID = "15896";
        // endregion transaksi

        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();

        $this->db->trans_start();

        $mainGate = array(
            "olehID" => $olehID,
            "olehName" => $olehNama,
            "sellerID" => "",
            "sellerName" => "",
            "pihakID" => $pihakID,
            "pihakName" => $pihakNama,
            "placeID" => $cabangID,
            "placeName" => $cabangNama,
            "cabangID" => $cabangID,
            "cabangName" => $cabangNama,
            "gudangID" => $gudangID,
            "gudangName" => $gudangNama,
            "place2ID" => $cabang2ID,
            "place2Name" => $cabang2Nama,
            "cabang2ID" => $cabang2ID,
            "cabang2Name" => $cabang2Nama,
            "gudang2ID" => $gudang2ID,
            "gudang2Name" => $gudang2Nama,
            "tokoEmail" => "",
            "jenisTr" => $jenis,
            "jenisTrMaster" => $jenisTrMaster,
            "jenisTrTop" => $jenis,
            "jenisTrName" => "",
            "stepNumber" => "",
            "stepCode" => $jenis,
            "dtime" => $dtime,
            "fulldate" => $fulldate,
            "ppnFactor" => $ppnFactor,
            "dummyElement" => "yes",
            "dummyElement__label" => "yes",
            "dummyElement__name" => "yes",
            "divID" => $divID,
            "jenis" => $jenis,
            "transaksi_jenis" => $jenis,
            "next_step_code" => $jenis,
            "next_group_code" => "o_holding",
            "step_number" => 1,
            "step_current" => 1,
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "description" => $arrDataSource[$selectKey]["extern_id"],
            "keterangan" => $arrDataSource[$selectKey]["extern_id"],
            "referenceTransaksiID" => $referencetransaksiID,
            "pymJenisTr" => $pym_jenis,
        );
        $detailGate = array();
//        arrPrintPink($mainGate);
        $components = array(
            "master" => array(
//                // JURNAL CABANG
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        //-------------------
                        "2040010" => "-sisa", // hutang ke pusat
                        "1010020010" => "-sisa", // piutang dagang
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
                        //-------------------
                        "2040010" => "-sisa", // hutang ke pusat
                        "1010020010" => "-sisa", // piutang dagang
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
//                //pembantu antarcabang (pusat)
                array(
                    "comName" => "RekeningPembantuAntarcabang",
                    "loop" => array(
                        "2040010" => "-sisa", // hutang ke pusat
                    ),
                    "static" => array(
                        "cabang_id" => "cabangID",
                        "cabang2_id" => "cabang2ID",
                        "cabang2_nama" => "cabang2Name",
                        "extern_id" => "cabang2ID",
                        "extern_nama" => "cabang2Name",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "RekeningPembantuCustomer",
                    "loop" => array(
                        "1010020010" => "-sisa", // piutang dagang
                    ),
                    "static" => array(
                        "cabang_id" => "cabangID",
                        "extern_id" => "pihakID",
                        "extern_nama" => "pihakName",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

                // JURNAL DC/PUSAT
                array(
                    "comName" => "Jurnal",
                    "loop" => array(
                        //-------------------
                        "1010060010" => "-sisa", // piutang cabang
                        "3010020" => "-sisa", // modal
                    ),
                    "static" => array(
                        "cabang_id" => "place2ID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                array(
                    "comName" => "Rekening",
                    "loop" => array(
                        //-------------------
                        "1010060010" => "-sisa", // piutang cabang
                        "3010020" => "-sisa", // modal
                    ),
                    "static" => array(
                        "cabang_id" => "place2ID",
                        "jenis" => "jenisTr",
                        "transaksi_no" => "nomer",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
                //pembantu antarcabang (caabng)
                array(
                    "comName" => "RekeningPembantuAntarcabang",
                    "loop" => array(
                        "1010060010" => "-sisa", // piutang cabang
                    ),
                    "static" => array(
                        "cabang_id" => "place2ID",
                        "cabang2_id" => "place2ID",
                        "cabang2_nama" => "place2Name",
                        "extern_id" => "cabangID",
                        "extern_nama" => "cabangName",
                        "jenis" => "jenisTr",
                    ),
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),

            ),
            "detail" => array(),
        );
        $postProcessor = array(
            "master" => array(
                array(
                    "comName" => "PaymentSource",
                    "loop" => array(),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "extern_id" => "pihakID",
                        "extern_nama" => "pihakName",
                        "label" => ".piutang dagang",
                        "jenis" => "pymJenisTr",
                        "transaksi_id" => "referenceTransaksiID",
                        "returned" => "sisa",
//                        "sisa" => ".0",
//                        "tabel_id" => "tabel_id",
                    ),
                    "reversable" => true,
                    "srcGateName" => "main",
                    "srcRawGateName" => "main",
                ),
            ),
            "detail" => array(),
        );


        // region payment source
        $pym = New MdlPaymentSource();
        $pym->addFilter("cabang_id=$cabangID");
        $pym->addFilter("target_jenis=$jenis_target");
        $pym->addFilter("label=$pym_label");
        $pym->addFilter("jenis=$pym_jenis");
        $pym->addFilter("extern_id=$pihakID");
        $pymTmp = $pym->lookupAll()->result();
        showLast_query("biru");
        if (sizeof($pymTmp) > 0) {
            foreach ($pymTmp as $spec) {
                $id = $spec->extern_id;
                $nama = $spec->extern_nama;
                $spec_new = (array)$spec;
                $spec_new["id"] = $id;
                $spec_new["nama"] = $nama;
                $spec_new["name"] = $nama;
                $detailGate[$spec->extern_id] = $spec_new;
                $mainGate["sisa"] = $spec_new["sisa"];
            }
        }
        // endregion payment source
//        arrPrintPink($detailGate);
//        arrPrintPink($mainGate);


        $tableIn = array(
            "master" => array(
                "jenis_master" => "jenisTrMaster",
                "jenis_top" => "jenisTrTop",
                "jenis" => "jenisTr",
                "jenis_label" => "jenisTrName",
                "div_id" => "divID",
                "div_nama" => "divName",
                "dtime" => "dtime",
                "fulldate" => "fulldate",
                "oleh_id" => "olehID",
                "oleh_nama" => "olehName",
                "customers_id" => "pihakID",
                "customers_nama" => "pihakName",
                "cabang_id" => "placeID",
                "cabang_nama" => "placeName",
                "transaksi_nilai" => "new_net2",
                "transaksi_jenis" => "jenisTr",
                "keterangan" => "description",
                "gudang_id" => "gudangID",
                "gudang_nama" => "gudangName",
                "toko_id" => "tokoID",
                "toko_nama" => "tokoName",

            ),
            "detail" => array(
                "dtime" => "dtime",
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "label",
                "produk_nama" => "name",
                "produk_ord_jml" => "qty",
                "produk_ord_hrg" => "sisa",
                "satuan" => "satuan",
            ),
        );
        foreach ($tableIn["master"] as $key => $val) {
            $tableIn_master[$key] = isset($mainGate[$val]) ? $mainGate[$val] : "";
        }
        foreach ($detailGate as $idd => $iddSpec) {
            foreach ($tableIn["detail"] as $key => $val) {
                $tableIn_detail[$idd][$key] = isset($iddSpec[$val]) ? $iddSpec[$val] : "";
            }
            foreach ($mainGate as $ii => $vv) {
                if (!isset($detailGate[$idd][$ii])) {
                    $detailGate[$idd][$ii] = $vv;
                }
            }
        }

        $this->cCode = $cCode = "_TR_" . $jenis;
        $this->cCodeData[$cCode] = array(
            "main" => $mainGate,
            "items" => isset($detailGate) ? $detailGate : array(),
            "tableIn_master" => $tableIn_master,
            "tableIn_detail" => isset($tableIn_detail) ? $tableIn_detail : array(),
        );
//        cekBiru($cCode);
//        arrPrint($this->cCodeData[$cCode]);
//        mati_disini(__LINE__);

        // MEMBUAT TRANSAKSI
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            //region dynamic counters

            $counters = array(
                "stepCode|placeID",
                "stepCode|tokoID",
                "stepCode|tokoID|placeID",
                "stepCode|tokoID|olehID",
                "stepCode|tokoID|placeID|olehID",
            );
            $formatNota = "stepCode,placeID,stepCode|tokoID|placeID,stepCode|tokoID";

            //region penomoran receipt
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $configCustomParams = $counters;
            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $this->cCodeData[$cCode]["main"][$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i], $tokoID);

                    $cContent[$cRawParams][$cRawValues] = $paramSpec["value"];
                    switch ($paramSpec["id"]) {
                        case 0: //===counter type is new
                            $addData = array(
//                                "toko_id" => $tokoID,
//                                "toko_nama" => $tokoNama,
                            );
                            $paramKeyRaw = print_r($cParams, true);
                            $paramValuesRaw = print_r($cValues[$i], true);
                            $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw, $addData);
                            break;
                        default: //===counter to be updated
                            $cn->updateCount($paramSpec["id"], $paramSpec["value"]);
                            break;
                    }
                }
            }

            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);

            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $counterForNumber = array($formatNota);
            foreach ($counterForNumber as $i => $c0RawParams) {
                $c0Params = explode(",", $c0RawParams);
                foreach ($c0Params as $k => $cRawParams) {
                    $dParams = explode("|", $cRawParams);
                    if (count($dParams) > 1) {
                        if (!in_array($cRawParams, $counters)) {
                            die(__LINE__ . "( $cRawParams ) Used number should be registered in counters config as well");
                        }
                    }
                }
            }

            $tmpNomorNota = "";
            $arrNomorNota = array();
            foreach ($counterForNumber as $i => $c0RawParams) {
                $c0Params = explode(",", $c0RawParams);
                $c0Values = array();
                foreach ($c0Params as $k => $cRawParams) {
                    $arrRawParams = explode("|", $cRawParams);
                    if (sizeof($arrRawParams) > 1) {
                        $cRawParamsValues = array();
                        foreach ($arrRawParams as $key) {
                            $cRawParamsValues[$key] = $this->cCodeData[$cCode]['main'][$key];
                        }
                        $cRawParamsValuesK = implode("|", array_keys($cRawParamsValues));
                        $cRawParamsValuesV = implode("|", $cRawParamsValues);
                        $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                    }
                    else {
                        $cRawParamsValuesK = $arrRawParams[0];
                        $cRawParamsValuesV = $this->cCodeData[$cCode]['main'][$arrRawParams[0]];
                        if ($arrRawParams[0] == "fulldate") {
                            $arrNomorNota[] = $arrRawParams[0] . "|" . date("mY", strtotime($cRawParamsValuesV));
                        }
                        elseif ($arrRawParams[0] == "stepCode") {
                            $arrNomorNota[] = $cRawParamsValuesV; //ini harus ori tidak boleh di masking/ diformat
//                            $arrNomorNota[] = digit_4($cContent[$cRawParamsValuesK][$cRawParamsValuesV]);
                        }
                        elseif ($arrRawParams[0] == "placeID") {
                            $arrNomorNota[] = digit_2($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "customerID") {
                            $arrNomorNota[] = digit_4($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "olehID") {
                            $arrNomorNota[] = digit_4($cRawParamsValuesV);
                        }
                        elseif ($arrRawParams[0] == "supplierID") {
                            $arrNomorNota[] = digit_4($cRawParamsValuesV);
                        }
                        else {
                            $arrNomorNota[] = $cRawParamsValuesV;
                        }
                    }
                }
            }

            $stepNumber = 1;
            $tmpNomorNota = implode("-", $arrNomorNota);
//            cekMerah(":: $tmpNomorNota ::");
            //endregion penomoran receipt

            //region addition on master
            $nextProp = array(
                "num" => 0,
                "code" => "",
                "label" => "",
                "groupID" => "",
            );
            $addValues = array(
                "counters" => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => 1,
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp["num"],
                "next_step_code" => $nextProp["code"],
                "next_step_label" => $nextProp["label"],
                "next_group_code" => $nextProp["groupID"],
                "tail_number" => 1,
                "tail_code" => "",
            );
            foreach ($addValues as $key => $val) {
                $this->cCodeData[$cCode]["tableIn_master"][$key] = $val;
            }
            //endregion

            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => 1,
                "next_substep_num" => $nextProp["num"],
                "next_substep_code" => $nextProp["code"],
                "next_substep_label" => $nextProp["label"],
                "next_subgroup_code" => $nextProp["groupID"],
                "sub_tail_number" => 1,
                "sub_tail_code" => "",
            );
            foreach ($this->cCodeData[$cCode]["tableIn_detail"] as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $this->cCodeData[$cCode]["tableIn_detail"][$id][$key] = $val;
                }
            }
            //endregion

            //endregion

            //region numbering tambahan
            $this->load->library("CounterNumber");
            $ccn = new CounterNumber();
            $ccn->setCCode($this->cCode);
            $ccn->setJenisTr($this->jenisTr);
            $ccn->setTransaksiGate($this->cCodeData[$cCode]["tableIn_master"]);
            $ccn->setMainGate($this->cCodeData[$cCode]["main"]);
            $ccn->setItemsGate($this->cCodeData[$cCode]["items"]);

            if (isset($this->cCodeData[$cCode]["items2_sum"])) {
                $ccn->setItems2SumGate($this->cCodeData[$cCode]["items2_sum"]);
            }

            $new_counter = $ccn->getCounterNumber();

            cekHitam("jenistr yang disett dari create " . $this->jenisTr);

            if (isset($new_counter["main"]) && sizeof($new_counter["main"]) > 0) {
                foreach ($new_counter["main"] as $ckey => $cval) {
                    $this->cCodeData[$cCode]["tableIn_master"][$ckey] = $cval;
                    $this->cCodeData[$cCode]["main"][$ckey] = $cval;
                }
            }
            if (isset($new_counter["items"]) && sizeof($new_counter["items"]) > 0) {
                foreach ($new_counter["items"] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $this->cCodeData[$cCode]["items"][$ikey][$iikey] = $iival;
                    }
                }
            }
            if (isset($new_counter["items2_sum"]) && sizeof($new_counter["items2_sum"]) > 0) {
                foreach ($new_counter["items2_sum"] as $ikey => $iSpec) {
                    foreach ($iSpec as $iikey => $iival) {
                        $this->cCodeData[$cCode]["items2_sum"][$ikey][$iikey] = $iival;
                    }
                }
            }
            //endregion

            //region MENULIS TRANSAKSIONAL
            if (isset($this->cCodeData[$cCode]["tableIn_master"]) && sizeof($this->cCodeData[$cCode]["tableIn_master"]) > 0) {

                $this->cCodeData[$cCode]["tableIn_master"]['status_4'] = 11;
                $this->cCodeData[$cCode]["tableIn_master"]['trash_4'] = 0;
                if ($runCliComponentDetail == false) {
                    $this->cCodeData[$cCode]["tableIn_master"]['cli'] = 1;
                }
                else {
                    $this->cCodeData[$cCode]["tableIn_master"]['cli'] = 0;
                }

                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $this->cCodeData[$cCode]["tableIn_master"]['cabang_id'] . "'");
                $insertID = $tr->writeMainEntries($this->cCodeData[$cCode]["tableIn_master"]);
                cekHitam($this->db->last_query());
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $this->cCodeData[$cCode]["tableIn_master"]);
                $insertNum = $this->cCodeData[$cCode]["tableIn_master"]['nomer'];
                $this->cCodeData[$cCode]["main"]['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }

                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                    "nomer2" => isset($tmpNomorNotaAlias) ? $tmpNomorNotaAlias : "",
                );
                $arrInjectorsTarget = array(
                    "items",
                    "items2_sum",
                    "rsltItems",
                );
                foreach ($injectors as $key => $val) {
                    $this->cCodeData[$cCode]["main"][$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        if (isset($this->cCodeData[$cCode][$target])) {
                            foreach ($this->cCodeData[$cCode][$target] as $xid => $iSpec) {
                                $id = isset($iSpec["id"]) && $iSpec["id"] > 0 ? $iSpec["id"] : $xid;
                                if (isset($this->cCodeData[$cCode][$target][$id])) {
                                    $this->cCodeData[$cCode][$target][$id][$key] = $val;
                                }
                            }
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $this->cCodeData[$cCode]["main"]['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
//                    "step_name" => $this->configUiModul[$this->jenisTr]["steps"][1]["label"],
//                    "group_code" => $this->configUiModul[$this->jenisTr]["steps"][1]['userGroup'],
//                    "oleh_id" => $this->cCodeData[$cCode]["main"]['olehID'],
//                    "oleh_nama" => $this->cCodeData[$cCode]["main"]['olehName'],
                    "step_name" => "",
                    "group_code" => "",
                    "oleh_id" => "",
                    "oleh_nama" => "",
                    "keterangan" => "",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");

                $idHis = array(
                    $stepNumber => array(
                        "olehID" => $this->cCodeData[$cCode]["main"]['olehID'],
                        "olehName" => $this->cCodeData[$cCode]["main"]['olehName'],
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "nomer2" => isset($tmpNomorNotaAlias) ? $tmpNomorNotaAlias : "",
                        "counters" => $appliedCounters,
                        // "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                    "next_step_num" => $nextProp["num"],
                    "next_step_code" => $nextProp["code"],
                    "next_step_label" => $nextProp["label"],
                    "next_group_code" => $nextProp["groupID"],

                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "nomer_top" => $this->cCodeData[$cCode]["main"]['nomer'],
                    "nomers_prev" => "",
                    "jenises_prev" => "",
                    "ids_his" => $idHis_blob,

                )) or die("Failed to update tr next-state!");
                cekHijau($this->db->last_query());
                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "nomer_top" => $this->cCodeData[$cCode]["main"]['nomer'],
                    "nomers_prev" => "",
                    "jenises_prev" => "",
                    "ids_his" => $idHis_blob,
                );
                foreach ($addValues as $key => $val) {
                    $this->cCodeData[$cCode]["tableIn_master"][$key] = $val;
                }

            }
            if (isset($this->cCodeData[$cCode]['tableIn_master_values']) && sizeof($this->cCodeData[$cCode]['tableIn_master_values']) > 0) {
                $inserMainValues = array();
                if (isset($this->configValuesModul[$this->jenisTr]["tableIn"]['mainValues'])) {
                    $inserMainValues = array();
                    foreach ($this->configValuesModul[$this->jenisTr]["tableIn"]['mainValues'] as $key => $src) {
                        if (isset($this->cCodeData[$cCode]['tableIn_master_values'][$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $this->cCodeData[$cCode]['tableIn_master_values'][$key],
                            ));
                            $inserMainValues[] = $dd;
                        }
                    }
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($this->cCodeData[$cCode]['main_add_values']) && sizeof($this->cCodeData[$cCode]['main_add_values']) > 0) {
                $inserMainValues = array();
                foreach ($this->cCodeData[$cCode]['main_add_values'] as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($this->cCodeData[$cCode]['main_inputs']) && sizeof($this->cCodeData[$cCode]['main_inputs']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_inputs'] as $key => $val) {
                    $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($this->cCodeData[$cCode]['main_add_fields']) && sizeof($this->cCodeData[$cCode]['main_add_fields']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_add_fields'] as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
            }
            if (isset($this->cCodeData[$cCode]['main_applets']) && sizeof($this->cCodeData[$cCode]['main_applets']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_applets'] as $amdl => $aSpec) {
                    $tr->writeMainApplets($insertID, array(
                        "mdl_name" => $amdl,
                        "key" => $aSpec['key'],
                        "label" => $aSpec['labelValue'],
                        "description" => $aSpec['description'],
                    ));
                }
            }
            if (isset($this->cCodeData[$cCode]['main_elements']) && sizeof($this->cCodeData[$cCode]['main_elements']) > 0) {
                foreach ($this->cCodeData[$cCode]['main_elements'] as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                        "value" => isset($aSpec["value"]) ? $aSpec["value"] : "",
                        "name" => $aSpec['name'],
                        "label" => $aSpec["label"],
                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",

                    ));
                    //==nebeng bikin inputLabels
                    $currentValue = "";
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $aSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $aSpec["value"];
                            break;
                    }
                    if (array_key_exists($elName, $relOptionConfigs)) {
                        if (isset($relOptionConfigs[$elName][$currentValue])) {
                            if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                    $inputLabels[$oValueName] = $oValSpec["label"];
                                    if (isset($oValSpec['auth'])) {
                                        if (isset($oValSpec['auth']["groupID"])) {
                                            $inputAuthConfigs[$oValueName] = $oValSpec['auth']["groupID"];
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }
                    }
                }
            }
            if (isset($this->cCodeData[$cCode]["tableIn_detail"]) && sizeof($this->cCodeData[$cCode]["tableIn_detail"]) > 0) {
                $insertIDs = array();
                $insertDeIDs = array();
                foreach ($this->cCodeData[$cCode]["tableIn_detail"] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    if ($insertDetailID < 1) {
                        die("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                    }
                    else {
                        $insertIDs[] = $insertDetailID;
                        $insertDeIDs[$insertID][] = $insertDetailID;
                    }
                    if ($epID != 999) {
                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                        if ($insertEpID < 1) {
                            die("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                        }
                        else {
                            $insertIDs[] = $insertEpID;
                            $insertDeIDs[$epID][] = $insertEpID;
                        }
                    }
                    cekUngu($this->db->last_query());
                }
                if (sizeof($insertIDs) == 0) {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }
                else {
                    $indexing_details = array();
                    foreach ($insertDeIDs as $key => $numb) {
                        $indexing_details[$key] = $numb;
                    }
                    foreach ($indexing_details as $k => $arrID) {
                        $arrBlob = blobEncode($arrID);
                        $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                        cekOrange($this->db->last_query());
                    }
                }
            }
//            else {
//                die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
//            }
//
            if (isset($this->cCodeData[$cCode]['tableIn_detail2']) && sizeof($this->cCodeData[$cCode]['tableIn_detail2']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail2'] as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail2_sum']) && sizeof($this->cCodeData[$cCode]['tableIn_detail2_sum']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    if ($epID != 999) {
                        $dd = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $dd;
                        $mongoList['detail'][] = $dd;
                    }
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail_rsltItems']) && sizeof($this->cCodeData[$cCode]['tableIn_detail_rsltItems']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail_rsltItems'] as $dSpec) {
                    $dd = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $dd;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                    }
                    cekUngu($this->db->last_query());
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail_values']) && sizeof($this->cCodeData[$cCode]['tableIn_detail_values']) > 0) {
                $insertIDs = array();
                foreach ($this->cCodeData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                    if (isset($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues'])) {
                        foreach ($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues'] as $key => $src) {
                            if (isset($this->cCodeData[$cCode]["tableIn_detail"][$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $this->cCodeData[$cCode]["tableIn_detail"][$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => isset($dSpec[$src]) ? $dSpec[$src] : "0",
                                ));
                                $insertIDs[$pID][] = $dd;
                            }
                        }
                    }
                }
                if (sizeof($insertIDs) > 0) {
                    $arrBlob = blobEncode($insertIDs);
                    $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                }
            }
            if (isset($this->cCodeData[$cCode]['tableIn_detail_values2_sum']) && sizeof($this->cCodeData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                foreach ($this->cCodeData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                    if (isset($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues2_sum'])) {
                        $insertIDs = array();
                        foreach ($this->configValuesModul[$this->jenisTr]["tableIn"]['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $this->cCodeData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => $dSpec[$src],
                            ));
                            $insertIDs[] = $dd;
                        }
                    }
                }
            }
//        $steps = $this->configUiModul[$this->jenisTr]["steps"];

            //endregion
        }
        else {
            $insertID = "1111111111111";
        }

        //region MENULIS KE REGISTRY
        $pakai_ini = 1;
        if ($pakai_ini == 1) {
            $baseRegistries = array(
                "main" => isset($this->cCodeData[$cCode]["main"]) ? $this->cCodeData[$cCode]["main"] : array(),
                "items" => isset($this->cCodeData[$cCode]["items"]) ? $this->cCodeData[$cCode]["items"] : array(),
                "items2" => isset($this->cCodeData[$cCode]["items2"]) ? $this->cCodeData[$cCode]["items2"] : array(),
                "items2_sum" => isset($this->cCodeData[$cCode]["items2_sum"]) ? $this->cCodeData[$cCode]["items2_sum"] : array(),
                "itemSrc" => isset($this->cCodeData[$cCode]["itemSrc"]) ? $this->cCodeData[$cCode]["itemSrc"] : array(),
                "itemSrc_sum" => isset($this->cCodeData[$cCode]["itemSrc_sum"]) ? $this->cCodeData[$cCode]["itemSrc_sum"] : array(),
                "items3" => isset($this->cCodeData[$cCode]["items3"]) ? $this->cCodeData[$cCode]["items3"] : array(),
                "items3_sum" => isset($this->cCodeData[$cCode]["items3_sum"]) ? $this->cCodeData[$cCode]["items3_sum"] : array(),
                "items4" => isset($this->cCodeData[$cCode]["items4"]) ? $this->cCodeData[$cCode]["items4"] : array(),
                "items4_sum" => isset($this->cCodeData[$cCode]["items4_sum"]) ? $this->cCodeData[$cCode]["items4_sum"] : array(),
                "items5_sum" => isset($this->cCodeData[$cCode]["items5_sum"]) ? $this->cCodeData[$cCode]["items5_sum"] : array(),
                'items6_sum' => isset($this->cCodeData[$cCode]['items6_sum']) ? $this->cCodeData[$cCode]['items6_sum'] : array(),
                'items7_sum' => isset($this->cCodeData[$cCode]['items7_sum']) ? $this->cCodeData[$cCode]['items7_sum'] : array(),
                'items8_sum' => isset($this->cCodeData[$cCode]['items8_sum']) ? $this->cCodeData[$cCode]['items8_sum'] : array(),
                'items9_sum' => isset($this->cCodeData[$cCode]['items9_sum']) ? $this->cCodeData[$cCode]['items9_sum'] : array(),
                'items10_sum' => isset($this->cCodeData[$cCode]['items10_sum']) ? $this->cCodeData[$cCode]['items10_sum'] : array(),
                'rsltItems' => isset($this->cCodeData[$cCode]['rsltItems']) ? $this->cCodeData[$cCode]['rsltItems'] : array(),
                'rsltItems2' => isset($this->cCodeData[$cCode]['rsltItems2']) ? $this->cCodeData[$cCode]['rsltItems2'] : array(),
                'rsltItems3' => isset($this->cCodeData[$cCode]['rsltItems3']) ? $this->cCodeData[$cCode]['rsltItems3'] : array(),
                "tableIn_master" => isset($this->cCodeData[$cCode]["tableIn_master"]) ? $this->cCodeData[$cCode]["tableIn_master"] : array(),
                "tableIn_detail" => isset($this->cCodeData[$cCode]["tableIn_detail"]) ? $this->cCodeData[$cCode]["tableIn_detail"] : array(),
                'tableIn_detail2_sum' => isset($this->cCodeData[$cCode]['tableIn_detail2_sum']) ? $this->cCodeData[$cCode]['tableIn_detail2_sum'] : array(),
                'tableIn_detail_rsltItems' => isset($this->cCodeData[$cCode]['tableIn_detail_rsltItems']) ? $this->cCodeData[$cCode]['tableIn_detail_rsltItems'] : array(),
                'tableIn_detail_rsltItems2' => isset($this->cCodeData[$cCode]['tableIn_detail_rsltItems2']) ? $this->cCodeData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                'tableIn_master_values' => isset($this->cCodeData[$cCode]['tableIn_master_values']) ? $this->cCodeData[$cCode]['tableIn_master_values'] : array(),
                'tableIn_detail_values' => isset($this->cCodeData[$cCode]['tableIn_detail_values']) ? $this->cCodeData[$cCode]['tableIn_detail_values'] : array(),
                'tableIn_detail_values_rsltItems' => isset($this->cCodeData[$cCode]['tableIn_detail_values_rsltItems']) ? $this->cCodeData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                'tableIn_detail_values_rsltItems2' => isset($this->cCodeData[$cCode]['tableIn_detail_values_rsltItems2']) ? $this->cCodeData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                'tableIn_detail_values2_sum' => isset($this->cCodeData[$cCode]['tableIn_detail_values2_sum']) ? $this->cCodeData[$cCode]['tableIn_detail_values2_sum'] : array(),
                'main_add_values' => isset($this->cCodeData[$cCode]['main_add_values']) ? $this->cCodeData[$cCode]['main_add_values'] : array(),
                'main_add_fields' => isset($this->cCodeData[$cCode]['main_add_fields']) ? $this->cCodeData[$cCode]['main_add_fields'] : array(),
                'main_elements' => isset($this->cCodeData[$cCode]['main_elements']) ? $this->cCodeData[$cCode]['main_elements'] : array(),
//                'items_elements' => isset($this->cCodeData[$cCode]['items_elements']) ? $this->cCodeData[$cCode]['items_elements'] : array(),
                'main_inputs' => isset($this->cCodeData[$cCode]['main_inputs']) ? $this->cCodeData[$cCode]['main_inputs'] : array(),
                'main_inputs_orig' => isset($this->cCodeData[$cCode]['main_inputs']) ? $this->cCodeData[$cCode]['main_inputs'] : array(),
                "receiptDetailFields" => isset($this->configLayoutModul[$this->jenisTr]['receiptDetailFields'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->configLayoutModul[$this->jenisTr]['receiptSumFields'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->configLayoutModul[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                "receiptDetailSrcFields" => isset($this->configLayoutModul[$this->jenisTr]['receiptDetailSrcFields'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptDetailSrcFields'][1] : array(),
                "receiptSumFields2" => isset($this->configLayoutModul[$this->jenisTr]['receiptSumFields2'][1]) ? $this->configLayoutModul[$this->jenisTr]['receiptSumFields2'][1] : array(),
                "jurnal_index" => $jurnalIndex,
                "postProcessor" => $jurnalPostProc,
                "preProcessor" => $jurnalPreProc,
                "revert" => isset($this->cCodeData[$cCode]['revert']) ? $this->cCodeData[$cCode]['revert'] : array(),
                "items_komposisi" => isset($this->cCodeData[$cCode]['items_komposisi']) ? $this->cCodeData[$cCode]['items_komposisi'] : array(),
                "items_noapprove" => isset($this->cCodeData[$cCode]['items_noapprove']) ? $this->cCodeData[$cCode]['items_noapprove'] : array(),
                "jurnalItems" => isset($this->cCodeData[$cCode]['jurnalItems']) ? $this->cCodeData[$cCode]['jurnalItems'] : array(),
                "componentsBuilder" => isset($this->cCodeData[$cCode]['componentsBuilder']) ? $this->cCodeData[$cCode]['componentsBuilder'] : array(),
//                "itemPrice" => isset($this->cCodeData[$cCode]['itemPrice']) ? $this->cCodeData[$cCode]['itemPrice'] : array(),
//                "itemPrice_sum" => isset($this->cCodeData[$cCode]['itemPrice_sum']) ? $this->cCodeData[$cCode]['itemPrice_sum'] : array(),
//                "requiredParam" => (isset($coreRequiredParam[$this->jenisTr]) && sizeof($coreRequiredParam[$this->jenisTr]) > 0) ? $coreRequiredParam[$this->jenisTr] : array(),
                //-----
//                "coreBuilder" => $coreBuilder,
//                'diskon_event' => isset($this->cCodeData[$cCode]['diskon_event']) ? $this->cCodeData[$cCode]['diskon_event'] : array(),
//                'cashback_event' => isset($this->cCodeData[$cCode]['cashback_event']) ? $this->cCodeData[$cCode]['cashback_event'] : array(),
                //-----
            );
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or mati_disini(("Ada kesalahan, Gagal saat berusaha  write base params into registries"));
            showLast_query("biru");
        }
        //endregion

//        mati_disini(__LINE__);

        // COMPONENT
        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            //region processing sub-components, if in single step geser ke CLI
            $componentGate['detail'] = array();
            $componentConfig['detail'] = array();
            $iterator = $components["detail"];
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $tmpOutParams[$cCtr] = array();
                    $gg = 0;
                    $srcGateName = $tComSpec['srcGateName'];
                    if ($componentsDetailLoop == true) {
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                            }

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                $filterNeeded = true;
                            }
                            else {
                                $filterNeeded = false;
                            }
                            cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                            $subParams = array();

                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                    }

                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;

                                    if ($filterNeeded) {
                                        if ($subParams['loop'][$key] == 0) {
                                            unset($subParams['loop'][$key]);
                                        }
                                    }
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }

                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = isset($this->cCodeData[$cCode][$srcGateName][$id]["keterangan"]) ? $this->cCodeData[$cCode][$srcGateName][$id]["keterangan"] : "";
                                if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                    $subParams['static']['reverted_target'] = $revertedTarget;
                                }
                            }

                            if (sizeof($subParams) > 0) {
//                                cekhitam("subparam ada isinya");
                                if ($filterNeeded) {
                                    if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                            else {
                                cekhitam("subparam TIDAK ada isinya");
                            }
                        }
                    }
                    else {
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                            if ($cCtr == $id) {
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");

                                    $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                                }

                                $mdlName = "$comsPrefix" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }
                                else {
                                    $filterNeeded = false;
                                }
                                cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                $subParams = array();

                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {

                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");

                                            $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                        }

                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['loop'][$key] = $realValue;

                                        if ($filterNeeded) {
                                            if ($subParams['loop'][$key] == 0) {
                                                unset($subParams['loop'][$key]);
                                            }
                                        }
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;

                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }

                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = "";
                                    if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }

                                if (sizeof($subParams) > 0) {

                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    cekhitam("subparam TIDAK ada isinya");
                                }
                            }
                        }
                    }

                    $componentGate['detail'][$cCtr] = $subParams;
                }

                foreach ($iterator as $cCtr => $tComSpec) {
                    $srcGateName = $tComSpec['srcGateName'];
                    foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                        }
                    }
                    cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                    $mdlName = "$comsPrefix" . ucfirst($comName);
                    $this->load->model("$comsLocation/" . $mdlName);
                    $m = new $mdlName();
                    //===filter value nol, jika harus difilter

                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                        $tobeExecuted = true;
                    }
                    else {
                        $tobeExecuted = false;
                    }

                    // matiHEre($tobeExecuted);
                    if ($tobeExecuted) {
                        //----- kiriman gerbang
                        if (method_exists($m, "setTableInMaster")) {
                            $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                        }
                        if (method_exists($m, "setDetail")) {
                            $m->setDetail($this->cCodeData[$cCode][$srcGateName]);
                        }
                        if (method_exists($m, "setJenisTr")) {
                            $m->setJenisTr($this->jenisTr);
                        }
                        //----- kiriman gerbang
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekBiru($this->db->last_query());
                    }
                    else {
                        cekMerah("$comName tidak eksekusi");
                    }

                }
            }
            else {
                cekKuning("subcomponents is not set");
            }
            //endregion

            //region processing main components, if in single step
            $componentGate['master'] = array();
            $componentConfig['master'] = array();
            $iterator = $components["master"];
            if (sizeof($iterator) > 0) {
                $componentConfig['master'] = $iterator;
                $cCtr = 0;
                foreach ($iterator as $cCtr => $tComSpec) {
                    $cCtr++;
                    $comName = $tComSpec['comName'];
                    if (substr($comName, 0, 1) == "{") {
                        $comName = trim($comName, "{");
                        $comName = trim($comName, "}");
                        $comName = str_replace($comName, $this->cCodeData[$cCode]["main"][$comName], $comName);
                    }
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    cekHere("component # $cCtr: $comName<br>");


                    // arrPrint($this->cCodeData[$cCode][$srcGateName]);
                    // matiHEre(__LINE__);
                    $dSpec = $this->cCodeData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            if (substr($key, 0, 1) == "{") {
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $this->cCodeData[$cCode]["main"][$key], $key);
                            }
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["urut"] = $cCtr;
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = isset($this->cCodeData[$cCode][$srcGateName]["keterangan"]) ? $this->cCodeData[$cCode][$srcGateName]["keterangan"] : "";
                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$cCtr], $this->cCodeData[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = $this->configUiModul[$this->jenisTr]["steps"][$stepNum]["label"] . " nomor " . $tmpNomorNota . " oleh " . $this->cCodeData[$cCode]["tableIn_master"]['oleh_nama'];
                    }

                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    //===filter value nol, jika harus difilter
                    $tobeExecuted = true;
                    if (in_array($mdlName, $compValidators)) {
                        $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                        if (sizeof($loopParams) > 0) {
                            foreach ($loopParams as $key => $val) {
                                cekmerah("$comName : $key = $val ");
                                if ($val == 0) {
                                    unset($tmpOutParams['loop'][$key]);
                                }
                            }
                        }
                        if (sizeof($tmpOutParams['loop']) < 1) {
                            $tobeExecuted = false;
                        }
                    }
                    if ($tobeExecuted) {
                        //----- kiriman gerbang untuk counter mutasi rekening
                        if (method_exists($m, "setTableInMaster")) {
                            $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                        }
                        if (method_exists($m, "setMain")) {
                            $m->setMain($this->cCodeData[$cCode]["main"]);
                        }
                        if (method_exists($m, "setJenisTr")) {
                            $m->setJenisTr($this->jenisTr);
                        }
                        //----- kiriman gerbang untuk counter mutasi rekening
                        $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    }
                    $componentGate['master'][$cCtr] = $tmpOutParams;
                }
            }
            else {
                cekKuning("components is not set");
            }
            //endregion
        }

        // POST-PROCC
        $pakai_ini = 1;
        if ($pakai_ini == 1) {

            //region processing sub-post-processors, always
            $iterator = $postProcessor["detail"];
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    cekHere("[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>");
                    $tmpOutParams[$cCtr] = array();
                    if (isset($this->cCodeData[$cCode][$srcGateName]) && (sizeof($this->cCodeData[$cCode][$srcGateName]) > 0)) {
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $xid => $dSpec) {
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['loop'][$key] = $realValue;
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {
                                    $subParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($subParams['static']["transaksi_no"])) {
                                    $subParams['static']["transaksi_no"] = $insertNum;
                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                if (isset($this->cCodeData[$cCode]['revert']['postProc']['detail'])) {
                                    $subParams['static']["reverted_target"] = $this->cCodeData[$cCode]["main"]['pihakExternID'];
                                }
                                $subParams['static']["keterangan"] = "";
                            }
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                            }
                        }
                    }
                }
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    if (isset($this->cCodeData[$cCode][$srcGateName])) {
                        cekHere("[$cCtr] sub-postProcessor: $comName, sending values " . __LINE__ . "<br>");
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                        cekHitam($this->db->last_query());
                    }
                }
            }
            else {
                cekHitam("TIDAK ADA SETUP SUB-POSTPROC");
            }
            //endregion

            //region processing main-post-processors, always
            $iterator = $postProcessor["master"];
            if (sizeof($iterator) > 0) {
                foreach ($iterator as $cCtr => $tComSpec) {
                    $comName = $tComSpec['comName'];
                    $srcGateName = $tComSpec['srcGateName'];
                    $srcRawGateName = $tComSpec['srcRawGateName'];
                    cekHere("post-processor: $comName<br>LINE: " . __LINE__);

                    $dSpec = $this->cCodeData[$cCode][$srcGateName];
                    $tmpOutParams = array();
                    if (isset($tComSpec['loop'])) {
                        foreach ($tComSpec['loop'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['loop'][$key] = $realValue;
                        }
                    }
                    if (isset($tComSpec['static'])) {
                        foreach ($tComSpec['static'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                            $tmpOutParams['static'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_id"])) {
                            $tmpOutParams['static']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static']["transaksi_no"])) {
                            $tmpOutParams['static']["transaksi_no"] = $insertNum;
                        }
                        $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static']["keterangan"] = "";
                    }
                    if (isset($tComSpec['static2'])) {
                        foreach ($tComSpec['static2'] as $key => $value) {
                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$cCtr], $this->cCodeData[$cCode][$srcGateName][$cCtr], 0);
                            $tmpOutParams['static2'][$key] = $realValue;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                            $tmpOutParams['static2']["transaksi_id"] = $insertID;
                        }
                        if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                            $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                        }

                        $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                        $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                        $tmpOutParams['static2']["keterangan"] = "";
                    }

                    //lgShowError("Ada kesalahan",);
                    $mdlName = "Com" . ucfirst($comName);
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();

                    cekBiru("kiriman komponem $comName");
                    $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                    $m->exec() or die("Gagal saat berusaha  exec values pada post-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                }
            }
            else {
                cekHitam("TIDAK ADA SETUP MAIN-POSTPROC");
            }
            //endregion
        }

        validateAllBalances($cabangID);


//        mati_disini(__LINE__ . " BERHASIL SETOP...");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>SELESAI...</h3>");


    }

    public function generateSerialIntransit()
    {

        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerialIntransit");

        $trIDs = array(
            101475
        );
        $arrTrDatas = array();
        $arrRegDatas = array();

        $tr = New MdlTransaksi();
        $tr->addFilter("id in ('" . implode("", $trIDs) . "')");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $arrTrDatas[$trSpec->id] = $trSpec;
            }

            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id in ('" . implode("", $trIDs) . "')");
            $trReg = $tr->lookupDataRegistries()->result();
            foreach ($trReg as $regSpec) {
                foreach ($regSpec as $key => $val) {
                    if ($key != "transaksi_id") {
                        if ($val == NULL) {
                            $val = blobEncode(array());
                        }
                        $arrRegDatas[$regSpec->transaksi_id][$key] = blobDecode($val);
                    }
                }
            }
//            arrPrintKuning($arrRegDatas);
        }

        $postProcessor = array(
            "master" => array(),
            "detail" => array(
                array(
                    "comName" => "RekeningPembantuProdukPerSerial",
                    "loop" => array(
                        "1010030030" => ".-1",//persediaan produk, sub_diskon_nilai_total
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "extern_id" => ".0",
                        "extern_nama" => "produk_serial",
                        "extern2_id" => ".0",
                        "extern2_nama" => "produk_sku_part_nama",
                        "produk_id" => "id",
                        "produk_nama" => "name",
                        "produk_qty" => "-jml",
                        "produk_nilai" => ".1",
//                        "transaksi_id" => "masterID",
                    ),
                    "srcGateName" => "items3_sum",
                    "srcRawGateName" => "items3_sum",
                ),
                array(
                    "comName" => "RekeningPembantuProdukPerSerialIntransit",
                    "loop" => array(
                        "1010030030" => ".1",//persediaan produk, sub_diskon_nilai_total
                    ),
                    "static" => array(
                        "cabang_id" => "placeID",
                        "gudang_id" => "gudangID",
                        "extern_id" => ".0",
                        "extern_nama" => "produk_serial",
                        "extern2_id" => ".0",
                        "extern2_nama" => "produk_sku_part_nama",
                        "produk_id" => "id",
                        "produk_nama" => "name",
                        "produk_qty" => "jml",
                        "produk_nilai" => ".1",
                        "transaksi_id" => "masterID",
                    ),
                    "srcGateName" => "items3_sum",
                    "srcRawGateName" => "items3_sum",
                ),
            ),

        );

        $this->db->trans_start();

        if (sizeof($arrTrDatas) > 0) {
            foreach ($arrTrDatas as $trid => $trSpec) {
                $insertID = $trSpec->id;
                $insertNum = $trSpec->nomer;
                $jenisTr_master = $trSpec->jenis_master;
                $fulldate = $trSpec->fulldate;
                $dtime = $trSpec->dtime;
                $cCode = "_TR_" . $jenisTr_master;
                $arrDatas = $arrRegDatas[$trid];
                $this->cCodeData[$cCode] = $arrDatas;

                //region processing sub-post-processors, always
                $iterator = $postProcessor["detail"];
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        cekHere("[$cCtr] sub-postProcessor: $comName, gate: $srcGateName, initializing values <br>");
                        $tmpOutParams[$cCtr] = array();
                        if (isset($this->cCodeData[$cCode][$srcGateName]) && (sizeof($this->cCodeData[$cCode][$srcGateName]) > 0)) {
                            foreach ($this->cCodeData[$cCode][$srcGateName] as $xid => $dSpec) {
                                $id = $xid;
                                $subParams = array();
                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['loop'][$key] = $realValue;
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }
                                    $subParams['static']["fulldate"] = $fulldate;
                                    $subParams['static']["dtime"] = $dtime;
                                    if (isset($this->cCodeData[$cCode]['revert']['postProc']['detail'])) {
                                        $subParams['static']["reverted_target"] = $this->cCodeData[$cCode]["main"]['pihakExternID'];
                                    }
                                    $subParams['static']["keterangan"] = "";
                                }
                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                }
                            }
                        }
                    }
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        if (isset($this->cCodeData[$cCode][$srcGateName])) {
                            cekHere("[$cCtr] sub-postProcessor: $comName, sending values " . __LINE__ . "<br>");
                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            $m->pair($tmpOutParams[$cCtr]) or mati_disini("Tidak berhasil memasang  values pada post-processor: ");
                            $m->exec() or mati_disini("Gagal saat berusaha  exec values pada post-processor: ");
                            cekHitam($this->db->last_query());
                        }
                    }
                }
                else {
                    cekHitam("TIDAK ADA SETUP SUB-POSTPROC");
                }
                //endregion


            }
        }


        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");
    }

    //----------------------------------------------
    public function generatePPNKeluaranPeriode()
    {

        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComRekeningPpnPeriode");
        $this->load->helper("he_mass_table");

        $rekening = "2030060";
        $cabangID = "1";
        $hasilPeriode = array();
        $this_bln = date("m");
        $this_thn = date("Y");

        $crp = New ComRekeningPpnPeriode();
        $cr = New ComRekening();
//        $cr->addFilter("bln='$this_bln'");
//        $cr->addFilter("thn='$this_thn'");
        $crTmp = $cr->fetchMoves($rekening);
        showLast_query("biru");
//        arrPrintWebs($crTmp);

        if (sizeof($crTmp) > 0) {
            foreach ($crTmp as $ii => $crSpec) {
                $cabang_id = $crSpec->cabang_id;
                $bln = $crSpec->bln;
                $thn = $crSpec->thn;
                $debet = $crSpec->debet;
                $kredit = $crSpec->kredit;
                $netto = $kredit - $debet;
                //-------------------
                if (!isset($hasilPeriode["bulanan"][$cabang_id][$thn][$bln])) {
                    $hasilPeriode["bulanan"][$cabang_id][$thn][$bln] = array(
                        "rekening" => "$rekening",
                        "cabang_id" => $cabang_id,
                        "debet" => 0,
                        "kredit" => 0,
                        "bln" => $bln,
                        "thn" => $thn,
                        "periode" => "bulanan",
                    );
                }
                $hasilPeriode["bulanan"][$cabang_id][$thn][$bln]["kredit"] += $netto;
                //-------------------
                if (!isset($hasilPeriode["tahunan"][$cabang_id][$thn])) {
                    $hasilPeriode["tahunan"][$cabang_id][$thn] = array(
                        "rekening" => "$rekening",
                        "cabang_id" => $cabang_id,
                        "debet" => 0,
                        "kredit" => 0,
                        "thn" => $thn,
                        "periode" => "tahunan",
                    );
                }
                $hasilPeriode["tahunan"][$cabang_id][$thn]["kredit"] += $netto;
                //-------------------

            }
        }
//        arrPrintPink($hasilPeriode);

        $this->db->trans_start();

        if (sizeof($hasilPeriode) > 0) {
            foreach ($hasilPeriode as $periode => $spec) {
                switch ($periode) {
                    case "bulanan":
                        foreach ($spec as $cabid => $specc) {
                            foreach ($specc as $thn => $speccc) {
                                foreach ($speccc as $bln => $specccc) {
//                            arrPrintWebs($specccc);
                                    $crp->setFilters(array());
                                    $crp->addFilter("rekening=" . $specccc["rekening"]);
                                    $crp->addFilter("cabang_id=" . $specccc["cabang_id"]);
                                    $crp->addFilter("periode=" . $specccc["periode"]);
                                    $crp->addFilter("thn=" . $specccc["thn"]);
                                    $crp->addFilter("bln=" . $specccc["bln"]);
                                    $crpTmp = $crp->lookupAll()->result();
                                    if (sizeof($crpTmp) > 0) {
                                        $idtbl = $crpTmp[0]->id;
                                        $where = array(
                                            "id" => $idtbl,
                                        );
                                        $data = array(
                                            "debet" => $specccc["debet"],
                                            "kredit" => $specccc["kredit"],
                                        );
                                        $crp->setFilters(array());
                                        $crp->addFilter($where);
                                        $crp->updateData($where, $data);
                                        showLast_query("orange");
                                    }
                                    else {
                                        $crp->setFilters(array());
                                        $crp->addData($specccc);
                                        showLast_query("hijau");
                                    }
                                }
                            }
                        }
                        break;
                    case "tahunan":
                        foreach ($spec as $cabid => $specc) {
                            foreach ($specc as $thn => $speccc) {
//                                foreach ($speccc as $bln => $specccc) {
                                $crp->setFilters(array());
                                $crp->addFilter("rekening=" . $speccc["rekening"]);
                                $crp->addFilter("cabang_id=" . $speccc["cabang_id"]);
                                $crp->addFilter("periode=" . $speccc["periode"]);
                                $crp->addFilter("thn=" . $speccc["thn"]);
                                $crpTmp = $crp->lookupAll()->result();
                                if (sizeof($crpTmp) > 0) {
                                    $idtbl = $crpTmp[0]->id;
                                    $where = array(
                                        "id" => $idtbl,
                                    );
                                    $data = array(
                                        "debet" => $speccc["debet"],
                                        "kredit" => $speccc["kredit"],
                                    );
                                    $crp->setFilters(array());
                                    $crp->addFilter($where);
                                    $crp->updateData($where, $data);
                                    showLast_query("pink");
                                }
                                else {
                                    $crp->setFilters(array());
                                    $crp->addData($speccc);
                                    showLast_query("kuning");
                                }
//                                }
                            }
                        }
                        break;
                }

            }
        }


//        mati_disini("---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        cekHijau("<h3>DONE...</h3>");
    }


    public function generateDiskonGRN()
    {
        header("refresh:2");
        $starttime = microtime(true);

        $this->load->helper("he_mass_table");
        $this->load->helper("he_value_builder");
        $this->load->model("MdlTransaksi");
        $this->load->model("CustomCounter");
        $this->load->model("Coms/ComRekeningPembantuPiutangSupplierDetailTransMain");

        $cts = New ComRekeningPembantuPiutangSupplierDetailTransMain();

        $supplierID = "11";
        $jenisTr = "467";
        $trID = "99819";
        $start_date = "2024-05-01";
        $diskonIDs = array(1, 2, 3, 4, 5, 8);
        $rekening = "1010020030";

        $this->db->trans_start();


        $tr = New MdlTransaksi();
//        $tr->addFilter("suppliers_id='$supplierID'");
        $tr->addFilter("date(dtime)>='$start_date'");
        $tr->addFilter("jenis='$jenisTr'");
//        $tr->addFilter("id='$trID'");
        $tr->addFilter("trash_4=0");
        $tr->addFilter("status_grn=0");
        $this->db->limit(1);
        $this->db->order_by("id", "ASC");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        if (sizeof($trTmp) > 0) {
            $insertID = $transaksi_id = $trid = $trTmp[0]->id;
            $insertNum = $transaksi_no = $trno = $trTmp[0]->nomer;
            $jenis = $trTmp[0]->jenis;
            $jenis_master = $trTmp[0]->jenis_master;
            $dtime = $trTmp[0]->dtime;
            $fulldate = $trTmp[0]->fulldate;
            $cabang_id = $trTmp[0]->cabang_id;
            //--------------------------------------------------------
            $cts->addFilter("extern_id='$insertID'");
            $cts->addFilter("extern2_id in ('" . implode("','", $diskonIDs) . "')");
            $tmp = $cts->fetchMovesAll($rekening);
            showLast_query("biru");
            cekBiru("diskon sudah masuk: " . count($tmp));
            if (sizeof($tmp) > 0) {
                $lanjut = false;
            }
            else {
                $lanjut = true;
            }
            //--------------------------------------------------------
            if ($lanjut == true) {

                $tr = New MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id='$trid'");
                $tmpReg = $tr->lookupDataRegistries()->result();
                showLast_query("biru");
                $registries = array();
                if (sizeof($tmpReg) > 0) {
                    foreach ($tmpReg as $row) {
                        foreach ($row as $key_reg => $val_reg) {
                            if (($key_reg != "transaksi_id")) {
                                if ($val_reg == NULL) {
                                    $val_reg = blobEncode(array());
                                }
                                $registries[$key_reg] = blobDecode($val_reg);
                            }
                        }
                    }
//                arrPrintPink($registries);
                }
                //--------------------------------------------------------
                $this->cCode = $cCode = "_TR_" . $jenis;
                $this->cCodeData[$cCode] = $registries;
                $componentsDetailLoop = true;
                $comsPrefix = "Com";
                $comsLocation = "Coms";
                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $runCliComponentDetail = true;
                $jenisTrTarget = $jenis;

                $new_items = $this->iterasiGerbangItem($this->cCodeData[$cCode], $cabang_id);
                if (sizeof($new_items) > 0) {
                    $this->cCodeData[$cCode]["items"] = $new_items;
                }


                $configUiJenis = $configUiMasterModulJenis = loadConfigModulJenis_he_misc($jenis_master, "coTransaksiUi");
                $configCoreJenis = $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($jenis_master, "coTransaksiCore");
                $configLayoutJenis = $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($jenis_master, "coTransaksiLayout");
                $configValuesJenis = $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($jenis_master, "coTransaksiValues");
                $fromStep = 3;
                $intoStep = 4;
                $this->cCodeData[$cCode] = fillValues_he_value_builder_ns($jenis_master, $fromStep = 0, $intoStep = 0, $configCoreJenis, $configUiJenis, $configValuesJenis, 11, $this->cCodeData[$cCode]);
//            arrPrintHijau($this->cCodeData[$cCode]["main"]);
//                arrPrintPink($this->cCodeData[$cCode]["items"]);
//            mati_disini(__LINE__);

                $preProcessor = array(
                    "master" => array(
                        // extract diskon items
                        array(
                            "comName" => "SyncDiskonPembelianNS",
                            "loop" => array(),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "source" => ".items",
                                "target" => ".items4_sum",
                                "jenisTr" => "jenisTr",
                                "jenisTrMaster" => "jenisTrMaster",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                    ),
                    "detail" => array(),
                );
                $components = array(
                    "master" => array(

                        // region mencatat piutang, diskon dari supplier
                        99 => array(
                            "comName" => "Jurnal",
                            "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                                "1010020030" => "diskon_nilai_total",// piutang supplier
                                "7010150" => "laba_lain_lain",// laba lain-lain
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),
                        98 => array(
                            "comName" => "Rekening",
                            "loop" => array(
//                            "1010030030" => "-diskon_npph_nilai_total",// persediaan, diskon_nilai_total*
                                "1010020030" => "diskon_nilai_total",// piutang supplier
                                "7010150" => "laba_lain_lain",// laba lain-lain
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                            ),
                            "srcGateName" => "main",
                            "srcRawGateName" => "main",
                        ),

                        // endregion mencatat piutang, diskon dari supplier


                    ),
                    "detail" => array(
                        // rekening pembantu piutang supplier, diskon supplier
                        array(
                            "comName" => "RekeningPembantuPiutangSupplierItem",
                            "loop" => array(
                                "1010020030" => "sub_diskon_nilai",// piutang supplier
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern_id" => "diskon_id",
//                            "extern_nama" => "diskon_nama",
                                "extern_id" => "pihakID",
                                "extern_nama" => "pihakName",
                            ),
                            "srcGateName" => "items4_sum",
                            "srcRawGateName" => "items4_sum",
                        ),
                        // rekening pembantu piutang supplier, diskon supplier, supplier
                        array(
                            "comName" => "RekeningPembantuPiutangSupplierDetailItem",
                            "loop" => array(
                                "1010020030" => "sub_diskon_nilai",// piutang supplier
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                                "extern2_id" => "pihakID",
                                "extern2_nama" => "pihakName",
                                "extern_id" => "diskon_id",
                                "extern_nama" => "diskon_nama",
                            ),
                            "srcGateName" => "items4_sum",
                            "srcRawGateName" => "items4_sum",
                        ),
                        // rekening pembantu piutang supplier, diskon supplier, supplier, transaksi_id
                        array(
                            "comName" => "RekeningPembantuPiutangSupplierDetailTransItem",
                            "loop" => array(
                                "1010020030" => "sub_diskon_nilai",// piutang supplier
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
//                            "extern2_id" => "pihakID",
//                            "extern2_nama" => "pihakName",
//                            "extern_id" => "diskon_id",
//                            "extern_nama" => "diskon_nama",
                                "extern3_id" => "pihakID",// supplier
                                "extern3_nama" => "pihakName",// supplier
                                "extern2_id" => "diskon_id",// jenis diskon
                                "extern2_nama" => "diskon_nama",// jenis diskon
                            ),
                            "srcGateName" => "items4_sum",
                            "srcRawGateName" => "items4_sum",
                        ),
                        array(
                            "comName" => "RekeningPembantuPiutangSupplierDetailTransProdukItem",
                            "loop" => array(
                                "1010020030" => "sub_diskon_nilai",// piutang supplier
                            ),
                            "static" => array(
                                //extern_id diinject di model untuk ambil transaksi_id
                                "cabang_id" => "placeID",
                                "jenis" => "jenisTr",
//                            "transaksi_no" => "nomer",
                                "extern_id" => "diskon_id",// jenis diskon
                                "extern_nama" => "diskon_nama",// jenis diskon
                                "extern2_id" => "pihakID",// supplier
                                "extern2_nama" => "pihakName",// supplier
                                "extern3_id" => "id",// produk yang dapet diskon (ac)
                                "extern3_nama" => "nama",
                                "extern4_id" => "diskon_id",// hadiahnya produknya(kabel,selang)
                                "extern4_nama" => "diskon_nama",// jenis diskon
                                "produk_qty" => ".1",// jenis diskon
                                "produk_nilai" => "diskon_nilai",// jenis diskon
                            ),
                            "srcGateName" => "items4_sum",
                            "srcRawGateName" => "items4_sum",
                        ),
                        // locker stok diskon mempertimbangkan nilai tidak hanya qty
                        array(
                            "comName" => "LockerDiskonValue",
                            "loop" => array(
                                "exec_locker" => "sub_diskon_nilai",//sengaja dipasang kalau kalau tidak punya biar tidak ditulis
                            ),
                            "static" => array(
                                "cabang_id" => "placeID",
                                "jenis" => ".diskon",
                                "jenis2" => ".diskon",
                                "jenis_locker" => ".stock",
                                "state" => ".active",
                                "jumlah" => ".1",
                                "nilai" => "sub_diskon_nilai",
                                "nilai2" => "sub_diskon_nilai",
                                "nilai_unit" => "sub_diskon_nilai",
                                "produk_id" => "diskon_id",//id diskon
                                "nama" => "diskon_nama",

                                "extern_id" => "diskon_id",//id produk hadiah/jika berupa diskon reguler diisi id diskon
                                "extern_nama" => "diskon_nama",
                                "extern2_id" => "id",//produk yang dibeli
                                "extern2_nama" => "nama",
                                "satuan" => "satuan",
//                            "transaksi_id" => "transaksi_id",
//                            "transaksi_no" => "nomer",
                                "nomer" => "nomer",
                                "oleh_id" => ".0",
                                "gudang_id" => "gudangID",
                                "supplier_id" => "pihakID",
                                "supplier_nama" => "pihakName",
                            ),
                            "srcGateName" => "items4_sum",
                            "srcRawGateName" => "items4_sum",
                        ),


                    ),
                );


                // PRE-PROCC
                $pakai_ini = 1;
                if ($pakai_ini == 1) {

                    //region pre-processors (item)
                    $iterator = $preProcessor["detail"];
                    if (sizeof($iterator) > 0) {
                        $itemNumLabels = array();
                        if (sizeof($iterator) > 0) {
                            foreach ($iterator as $cCtr => $tComSpec) {
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                cekHere("sub-preproc: $comName, initializing values <br>");
                                foreach ($this->cCodeData[$cCode][$srcGateName] as $xid => $dSpec) {
                                    $tmpOutParams[$cCtr] = array();
                                    $id = $xid;
                                    $subParams = array();
                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {
                                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                            $subParams['static'][$key] = $realValue;
                                        }
                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = "";
                                    }
                                    if (sizeof($subParams) > 0) {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                        $comName = $tComSpec['comName'];
                                        $srcGateName = $tComSpec['srcGateName'];
                                        $srcRawGateName = $tComSpec['srcRawGateName'];
                                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();

                                        cekHere("sub preproc #: $comName, sending values " . __LINE__ . "<br>");

                                        $mdlName = "Pre" . ucfirst($comName);
                                        $this->load->model("Preprocs/" . $mdlName);
                                        $m = new $mdlName($resultParams);

                                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                            $tobeExecuted = true;
                                        }
                                        else {
                                            $tobeExecuted = false;
                                        }

                                        if ($tobeExecuted) {
                                            $hasil = $m->pair(0, $tmpOutParams[$cCtr], $this->cCodeData[$cCode]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                            if (sizeof($hasil) > 0) {
                                                $this->cCodeData[$cCode] = $hasil;
                                            }
                                            $gotParams = $m->exec();
                                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                                foreach ($gotParams as $gateName => $paramSpec) {
                                                    if (!isset($this->cCodeData[$cCode][$gateName])) {
                                                        $this->cCodeData[$cCode][$gateName] = array();
                                                    }
                                                    else {
                                                        //                                    cekhijau("NOT building the session: $gateName");
                                                    }
                                                    foreach ($paramSpec as $id => $gSpec) {
                                                        if (!isset($this->cCodeData[$cCode][$gateName][$id])) {
                                                            $this->cCodeData[$cCode][$gateName][$id] = array();
                                                        }
                                                        if (isset($this->cCodeData[$cCode][$gateName][$id])) {
                                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                // matiHEre("ada");
                                                                foreach ($gSpec as $key => $val) {
                                                                    cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val " . __LINE__);
                                                                    $this->cCodeData[$cCode][$gateName][$id][$key] = $val;
                                                                    cekMerah($cCode . "[" . $gateName . "][" . $id . "][" . $key . "]=" . $val);
                                                                }
                                                            }
                                                            else {
                                                                cekMerah("bukan array");
                                                                matiHere(__LINE__);
                                                            }
                                                        }
                                                        //==inject gotParams to child gate
                                                        if (isset($this->cCodeData[$cCode][$srcGateName][$id])) {
                                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                                foreach ($gSpec as $key => $val) {
                                                                    $this->cCodeData[$cCode][$srcGateName][$id][$key] = $val;

                                                                }
                                                            }
                                                            else {
                                                                cekMerah("bukan array");
                                                                matiHere(__LINE__);
                                                            }
                                                        }
                                                        if (sizeof($itemNumLabels) > 0) {
                                                            foreach ($itemNumLabels as $key => $label) {
                                                                if (isset($this->cCodeData[$cCode][$gateName][$id][$key])) {
                                                                    $this->cCodeData[$cCode][$gateName][$id]['sub_' . $key] = ($this->cCodeData[$cCode][$gateName][$id]['jml'] * $this->cCodeData[$cCode][$gateName][$id][$key]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        else {
                                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            //cekKuning("sub-preproc is not set");
                        }

                        $this->load->helper("he_value_builder");
                        $this->cCodeData[$cCode] = fillValues_he_value_builder_ns($jenis_master, $fromStep = 0, $intoStep = 0, $configCoreJenis, $configUiJenis, $configValuesJenis, 11, $this->cCodeData[$cCode]);
                        //region injector gerbang value untuk pembatalan ppv dan selisih
                        if (isset($this->cCodeData[$cCode]["revert"]["preProc"]["replacer"])) {
                            $replace = $this->cCodeData[$cCode]["revert"]["preProc"]["replacer"];
                            $tempCalculate = array(
                                "selisih" => ($this->cCodeData[$cCode]["main"]["hpp"] + $this->cCodeData[$cCode]["main"]["ppn"]) - ($this->cCodeData[$cCode]["main"]["nett"] + $this->cCodeData[$cCode]["main"]["ppv"]),
                                "hpp_nppv" => $this->cCodeData[$cCode]["main"]["hpp"],
                                "hpp_nppn" => $this->cCodeData[$cCode]["main"]["hpp"] + $this->cCodeData[$cCode]["main"]["ppn"],
                            );
                            foreach ($replace['recalculate'] as $iKey => $gate) {
                                $this->cCodeData[$cCode]["main"][$gate] = $tempCalculate[$gate];
                            }
                        }
                        //endregion
                    }
                    else {
                        cekHitam("no sub-pre-processor defined. skipping preprocessor..<br>");
                    }
                    //endregion

                    //region pre-processors (master)
                    $iterator = $preProcessor["master"];
                    if (sizeof($iterator) > 0) {
                        $itemNumLabels = array();
                        if (sizeof($iterator) > 0) {
                            foreach ($iterator as $cCtr => $tComSpec) {
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                $subParams = array();
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $this->cCodeData[$cCode]["main"], $this->cCodeData[$cCode]["main"], 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = "";
                                }
                                $tmpOutParams[$cCtr] = $subParams;
                                $mdlName = "Pre" . ucfirst($comName);
                                $this->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);
                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }

                                if ($tobeExecuted) {
                                    $hasil = $m->pair(0, $tmpOutParams[$cCtr], $this->cCodeData[$cCode]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    if (sizeof($hasil) > 0) {
                                        $this->cCodeData[$cCode] = $hasil;
                                    }
                                    $gotParams = $m->exec();
                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                        foreach ($gotParams as $gateName => $gSpec) {
                                            if (isset($this->cCodeData[$cCode]["main"])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $this->cCodeData[$cCode]["main"][$key] = $val;
                                                    }
                                                }
                                            }
                                            //==inject gotParams to child gate
                                            if (isset($this->cCodeData[$cCode]["main"])) {
                                                if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                    foreach ($gSpec as $key => $val) {
                                                        $this->cCodeData[$cCode]["main"][$key] = $val;
                                                    }
                                                }
                                            }
                                            //cekMerah("REBUILDING VALUES..");
                                            if (sizeof($itemNumLabels) > 0) {
                                                //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                foreach ($itemNumLabels as $key => $label) {
                                                    //cekHere("$id === $key => $label");
                                                    if (isset($this->cCodeData[$cCode]["main"][$key])) {
                                                        $this->cCodeData[$cCode]["main"]['sub_' . $key] = ($this->cCodeData[$cCode]["main"]['jml'] * $this->cCodeData[$cCode]["main"][$key]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }
                            }
                        }
                        else {
                            //cekKuning("sub-preproc is not set");
                        }
                        $this->load->helper("he_value_builder");
                        $this->cCodeData[$cCode] = $this->cCodeData[$cCode] = fillValues_he_value_builder_ns($jenis_master, $fromStep = 0, $intoStep = 0, $configCoreJenis, $configUiJenis, $configValuesJenis, 11, $this->cCodeData[$cCode]);
                    }
                    else {
                        cekHitam("no main-pre-processor defined. skipping preprocessor..<br>");
                    }
                    //endregion
                }
//
//arrPrintCyan($this->cCodeData[$cCode]["items"]);
//arrPrintHitam($this->cCodeData[$cCode]["items4_sum"]);
//mati_disini(__LINE__);

                // COMPONENT
                $pakai_ini = 1;
                if (sizeof($this->cCodeData[$cCode]["items4_sum"]) == 0) {
                    $pakai_ini = 0;
                }
                if ($pakai_ini == 1) {

                    //region processing sub-components, if in single step geser ke CLI
                    $componentGate['detail'] = array();
                    $componentConfig['detail'] = array();
                    $iterator = $components["detail"];
                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $tmpOutParams[$cCtr] = array();
                            $gg = 0;
                            $srcGateName = $tComSpec['srcGateName'];
                            if ($componentsDetailLoop == true) {
                                foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $comName = $tComSpec['comName'];
                                    if (substr($comName, 0, 1) == "{") {
                                        $comName = trim($comName, "{");
                                        $comName = trim($comName, "}");
                                        $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                                    }

                                    $mdlName = "$comsPrefix" . ucfirst($comName);
                                    if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                        $filterNeeded = true;
                                    }
                                    else {
                                        $filterNeeded = false;
                                    }
                                    cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                    $subParams = array();

                                    if (isset($tComSpec['loop'])) {
                                        foreach ($tComSpec['loop'] as $key => $value) {
                                            if (substr($key, 0, 1) == "{") {
                                                $key = trim($key, "{");
                                                $key = trim($key, "}");
                                                $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                            }

                                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                            $subParams['loop'][$key] = $realValue;

                                            if ($filterNeeded) {
                                                if ($subParams['loop'][$key] == 0) {
                                                    unset($subParams['loop'][$key]);
                                                }
                                            }
                                        }
                                    }
                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {
                                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                            $subParams['static'][$key] = $realValue;
                                        }
                                        if (!isset($subParams['static']["transaksi_id"])) {
                                            $subParams['static']["transaksi_id"] = $insertID;
                                        }
                                        if (!isset($subParams['static']["transaksi_no"])) {
                                            $subParams['static']["transaksi_no"] = $insertNum;
                                        }

                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = "";
                                        if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                            $subParams['static']['reverted_target'] = $revertedTarget;
                                        }
                                    }

                                    if (sizeof($subParams) > 0) {
//                                cekhitam("subparam ada isinya");
                                        if ($filterNeeded) {
                                            if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                                $tmpOutParams[$cCtr][] = $subParams;
                                            }
                                        }
                                        else {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        cekhitam("subparam TIDAK ada isinya");
                                    }
                                }
                            }
                            else {
                                foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                                    if ($cCtr == $id) {
                                        $srcRawGateName = $tComSpec['srcRawGateName'];
                                        $comName = $tComSpec['comName'];
                                        if (substr($comName, 0, 1) == "{") {
                                            $comName = trim($comName, "{");
                                            $comName = trim($comName, "}");

                                            $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                                        }

                                        $mdlName = "$comsPrefix" . ucfirst($comName);
                                        if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                            $filterNeeded = true;
                                        }
                                        else {
                                            $filterNeeded = false;
                                        }
                                        cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                        $subParams = array();

                                        if (isset($tComSpec['loop'])) {
                                            foreach ($tComSpec['loop'] as $key => $value) {

                                                if (substr($key, 0, 1) == "{") {
                                                    $key = trim($key, "{");
                                                    $key = trim($key, "}");

                                                    $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                                }

                                                $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                                $subParams['loop'][$key] = $realValue;

                                                if ($filterNeeded) {
                                                    if ($subParams['loop'][$key] == 0) {
                                                        unset($subParams['loop'][$key]);
                                                    }
                                                }
                                            }
                                        }
                                        if (isset($tComSpec['static'])) {
                                            foreach ($tComSpec['static'] as $key => $value) {
                                                $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                                $subParams['static'][$key] = $realValue;

                                            }
                                            if (!isset($subParams['static']["transaksi_id"])) {
                                                $subParams['static']["transaksi_id"] = $insertID;
                                            }
                                            if (!isset($subParams['static']["transaksi_no"])) {
                                                $subParams['static']["transaksi_no"] = $insertNum;
                                            }

                                            $subParams['static']["fulldate"] = date("Y-m-d");
                                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                            $subParams['static']["keterangan"] = "";
                                            if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                                $subParams['static']['reverted_target'] = $revertedTarget;
                                            }
                                        }

                                        if (sizeof($subParams) > 0) {

                                            if ($filterNeeded) {
                                                if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                                    $tmpOutParams[$cCtr][] = $subParams;
                                                }
                                            }
                                            else {
                                                $tmpOutParams[$cCtr][] = $subParams;
                                            }
                                        }
                                        else {
                                            cekhitam("subparam TIDAK ada isinya");
                                        }
                                    }
                                }
                            }

//                        $componentGate['detail'][$cCtr] = $subParams;
                        }

                        foreach ($iterator as $cCtr => $tComSpec) {
//                            arrPrintHitam($tComSpec);
                            $srcGateName = $tComSpec['srcGateName'];
                            if (isset($this->cCodeData[$cCode][$srcGateName]) && (sizeof($this->cCodeData[$cCode][$srcGateName]) > 0)) {

                                foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $comName = $tComSpec['comName'];
                                    if (substr($comName, 0, 1) == "{") {
                                        $comName = trim($comName, "{");
                                        $comName = trim($comName, "}");
                                        $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                                    }
                                }
                                cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                                $mdlName = "$comsPrefix" . ucfirst($comName);
                                $this->load->model("$comsLocation/" . $mdlName);
                                $m = new $mdlName();
                                //===filter value nol, jika harus difilter

                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }

                                // matiHEre($tobeExecuted);
                                if ($tobeExecuted) {
                                    //----- kiriman gerbang
                                    if (method_exists($m, "setTableInMaster")) {
                                        $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                                    }
                                    if (method_exists($m, "setDetail")) {
                                        $m->setDetail($this->cCodeData[$cCode][$srcGateName]);
                                    }
                                    if (method_exists($m, "setJenisTr")) {
                                        $m->setJenisTr($this->jenisTr);
                                    }
                                    //----- kiriman gerbang
                                    $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    cekBiru($this->db->last_query());
                                }
                                else {
                                    cekMerah("$comName tidak eksekusi");
                                }
                            }

                        }
                    }
                    else {
                        cekKuning("subcomponents is not set");
                    }
                    //endregion

                    //region processing main components, if in single step
                    $componentGate['master'] = array();
                    $componentConfig['master'] = array();
                    $iterator = $components["master"];
                    if (sizeof($iterator) > 0) {
                        $componentConfig['master'] = $iterator;
                        $cCtr = 0;
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $cCtr++;
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $this->cCodeData[$cCode]["main"][$comName], $comName);
                            }
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            cekHere("component # $cCtr: $comName<br>");

                            // arrPrint($this->cCodeData[$cCode][$srcGateName]);
                            // matiHEre(__LINE__);
                            $dSpec = $this->cCodeData[$cCode][$srcGateName];
                            $tmpOutParams = array();
                            if (isset($tComSpec['loop'])) {
                                foreach ($tComSpec['loop'] as $key => $value) {
                                    if (substr($key, 0, 1) == "{") {
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $this->cCodeData[$cCode]["main"][$key], $key);
                                    }
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                                    $tmpOutParams['loop'][$key] = $realValue;
                                }
                            }
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                                    $tmpOutParams['static'][$key] = $realValue;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                    $tmpOutParams['static']["transaksi_id"] = $insertID;
                                }
                                if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                    $tmpOutParams['static']["transaksi_no"] = $insertNum;
                                }
                                $tmpOutParams['static']["urut"] = $cCtr;
                                $tmpOutParams['static']["fulldate"] = date("Y-m-d");
                                $tmpOutParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $tmpOutParams['static']["keterangan"] = "";
                            }
                            if (isset($tComSpec['static2'])) {
                                foreach ($tComSpec['static2'] as $key => $value) {
                                    $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$cCtr], $this->cCodeData[$cCode][$srcGateName][$cCtr], 0);
                                    $tmpOutParams['static2'][$key] = $realValue;
                                }
                                if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                    $tmpOutParams['static2']["transaksi_id"] = $insertID;
                                }
                                if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                    $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                                }
                                $tmpOutParams['static2']["fulldate"] = date("Y-m-d");
                                $tmpOutParams['static2']["dtime"] = date("Y-m-d H:i:s");
                                $tmpOutParams['static2']["keterangan"] = $this->configUiModul[$this->jenisTr]["steps"][$stepNum]["label"] . " nomor " . $tmpNomorNota . " oleh " . $this->cCodeData[$cCode]["tableIn_master"]['oleh_nama'];
                            }

                            $mdlName = "Com" . ucfirst($comName);
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();

                            //===filter value nol, jika harus difilter
                            $tobeExecuted = true;
                            if (in_array($mdlName, $compValidators)) {
                                $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                                if (sizeof($loopParams) > 0) {
                                    foreach ($loopParams as $key => $val) {
                                        cekmerah("$comName : $key = $val ");
                                        if ($val == 0) {
                                            unset($tmpOutParams['loop'][$key]);
                                        }
                                    }
                                }
                                if (sizeof($tmpOutParams['loop']) < 1) {
                                    $tobeExecuted = false;
                                }
                            }
                            if ($tobeExecuted) {
                                //----- kiriman gerbang untuk counter mutasi rekening
                                if (method_exists($m, "setTableInMaster")) {
                                    $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                                }
                                if (method_exists($m, "setMain")) {
                                    $m->setMain($this->cCodeData[$cCode]["main"]);
                                }
                                if (method_exists($m, "setJenisTr")) {
                                    $m->setJenisTr($this->jenisTr);
                                }
                                //----- kiriman gerbang untuk counter mutasi rekening
                                $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            }
                            $componentGate['master'][$cCtr] = $tmpOutParams;
                        }
                    }
                    else {
                        cekKuning("components is not set");
                    }
                    //endregion
                }
            }


            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "id" => $insertID,
            );
            $data = array(
                "status_grn" => "1",
            );
            $tr->updateData($where, $data);
            showLast_query("orange");
        }

        $endtime = microtime(true); // Bottom of page
        $val = $endtime - $starttime;

//        mati_disini("[$val] ---SETOP--- " . __LINE__);

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE... [$val]</h3>");
    }

    public function iterasiGerbangItem($cCodeData, $cabang_id)
    {
        // arrPrint($_GET);
        // cekHijau($cCode . " hei");
        $sesItems = $cCodeData['items'];

        /*-----------produk harga------------*/
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $hp->setTokoId(my_toko_id());
        // $hp->setCabangId(my_cabang_id());
        $hp->setCabangId($cabang_id);
        $prod_hargas = $hp->callSpecs();
        // arrPrint($prod_hargas);
        $prod_hrg_speks = array();
        foreach ($prod_hargas as $produk_id => $param_prod_hargas) {
            foreach ($param_prod_hargas as $param_prod_harga) {
                $jenis_value = $param_prod_harga->jenis_value;

                $prod_hrg_speks[$produk_id][$jenis_value] = $param_prod_harga;
            }
        }

        /*-------------diskon pembelian-----------------*/
        $this->load->model("Mdls/MdlDiskonPembelian");
        $dp = new MdlDiskonPembelian();
        $dp_srcs = $dp->lookupAll()->result();
        foreach ($dp_srcs as $dp_src) {
            $dp_prod_id = $dp_src->produk_id;
            $dp_jenis_id = $dp_src->per_supplier_diskon_id;
            $dp_jenis = $dp_src->per_supplier_diskon_nama;
            $dp_speks['persen'] = $dp_src->persen;
            $dp_speks['nilai'] = $dp_src->nilai;
            $dp_datas[$dp_prod_id][$dp_jenis] = $dp_speks + (array)$dp_src;
        }

        /* ----------------------------------------------------------
         * modif item dengan diskonPembelian (dp)
         * ----------------------------------------------------------*/
        foreach ($sesItems as $produk_id => $sesItem) {
            $item_jml = $sesItem['jml'];
            $dp_speks = $dp_datas[$produk_id];
            $hrg_speks = $prod_hrg_speks[$produk_id];
            //arrPrintPink($dp_speks);
            /*----pilih saja harga list supplier yg mau dipakai ----*/
            $hpp_supplier = $item_hpp = $sesItem['hpp'];
            // $hpp_supplier = $hrg_speks['hpp_supplier']->nilai * 1;
            // arrPrint($dp_speks);
            $sesItem['hpp_supplier'] = $hpp_supplier;
            $sesItem['hpp_supplier_nppn'] = $hpp_supplier + ((my_ppn_factor() / 100) * $hpp_supplier);
            $dp_nilai_total = 0;
            foreach ($dp_speks as $dp_jenis => $dp_spek) {
                $dp_persen = $dp_spek['persen'] * 1;
                $dp_nilai_db = $dp_spek['nilai'] * 1;
                $dp_nilai = $dp_persen / 100 * $hpp_supplier;

                $sesItem[$dp_jenis . "_id"] = $dp_spek['per_supplier_diskon_id'];
                $sesItem[$dp_jenis . "_nama"] = $dp_spek['per_supplier_diskon_nama'];
                $sesItem[$dp_jenis . "_alias"] = $dp_spek['per_supplier_diskon_alias'];
                $sesItem[$dp_jenis . "_persen"] = $dp_persen;
                $sesItem[$dp_jenis . "_nilai"] = $dp_nilai;
                $sesItem["sub_" . $dp_jenis . "_nilai"] = $dp_nilai * $item_jml;

                $dp_nilai_total += $dp_nilai;
            }
            $sesItem["diskon_nilai_total"] = $dp_nilai_total;
            $sesItem["sub_diskon_nilai_total"] = $dp_nilai_total * $item_jml;
            $diskon_pajak = $dp_nilai_total * ($this->pph23 / 100);
            $sesItem["diskon_pph23"] = $diskon_pajak;

            // cekHere("$hpp_supplier - ($dp_nilai_total + $diskon_pajak");
            $tandas = ($hpp_supplier - ($dp_nilai_total + $diskon_pajak));
            $sesItem["hrg_tandas"] = $tandas;
            $sesItem["hrg_tandas_npph23"] = $tandas + ($this->pph23 / 100 * $tandas);

            $newItems[$produk_id] = $sesItem;
        }
//arrPrintHijau($newItems);
//        $_SESSION[$cCode]["items"] = $newItems;
//        cekHitam("[$cCode]");
//        arrPrintKuning($_SESSION[$cCode]['items']);
//        arrPrintWebs($sesItems);
        // jejeran($sesItems);
//        jejeranPink($_SESSION[$cCode]['items']);
        // jejeran(arrPrint($sesItems));
        return $newItems;
    }

    public function patchLockerDiskon()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlLockerStockDiskonVendor");


        $tr = New MdlTransaksi();
        $mm = New MdlLockerStockDiskonVendor();
        $mm->addFilter("nilai_diklaim>0");
        $mmTmp = $mm->lookupAll()->result();


        $this->db->trans_start();


        if (sizeof($mmTmp) > 0) {
            $arrTrDatas = array();
            $arrTrIDs = array();
            $arrBaris = array();
            foreach ($mmTmp as $mmSpec) {
                $transaksi_id = $mmSpec->transaksi_id;
                $arrTrIDs[$transaksi_id] = $transaksi_id;
                $arrBaris[$mmSpec->id] = $transaksi_id;
            }
            $tr->addFilter("id in ('" . implode("','", $arrTrIDs) . "')");
            $trTmp = $tr->lookupAll()->result();
            if (sizeof($trTmp) > 0) {
                foreach ($trTmp as $trSpec) {
                    $arrTrDatas[$trSpec->id] = array(
                        "nomer" => $trSpec->nomer,
                    );
                }
            }

            if (sizeof($arrBaris) > 0) {
                foreach ($arrBaris as $id => $trid) {
                    $nomer = $arrTrDatas[$trid]["nomer"];
                    $where = array(
                        "id" => $id,
                    );
                    $data = array(
                        "nomer" => $nomer,
                    );
                    $mm = New MdlLockerStockDiskonVendor();
                    $mm->setFilters(array());
                    $mm->updateData($where, $data);
                    showLast_query("orange");
                }
            }


        }


//        mati_disini("[---] ---SETOP--- " . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE... [$val]</h3>");
    }


    //----------------------------------------------
    public function patch()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekeningPembantuBiayaUsaha");
        $this->load->helper("he_mass_table");

        $this->db->trans_start();

        $jenis = "1677r";
        $rekening = "6010";
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='$jenis'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        cekBiru(count($trTmp));
        if (sizeof($trTmp) > 0) {
            $trIDs = array();
            $trIDs_data = array();
            foreach ($trTmp as $trSpec) {
                $trIDs[$trSpec->id] = $trSpec->id;
                $trIDs_data[$trSpec->id] = array(
                    "id" => $trSpec->id,
                    "dtime" => $trSpec->dtime,
                    "fulldate" => $trSpec->fulldate,
                );
            }

            $bu = New ComRekeningPembantuBiayaUsaha();
            $bu->setFilters(array());
            $bu->addFilter("jenis='$jenis'");
            $buTmp = $bu->fetchMovesByTransIDs($rekening, $trIDs);
            showLast_query("biru");
            cekBiru(count($buTmp));
            $mTrIDs = array();
            foreach ($buTmp as $buSpec) {
                $mTrIDs[$buSpec->transaksi_id] = $buSpec->transaksi_id;
            }

            $trIDs_diff = array_diff($trIDs, $mTrIDs);
            arrPrintCyan($trIDs_diff);
            cekBiru(count($trIDs_diff));

            foreach ($trIDs_data as $id => $spec) {
                if (!array_key_exists($id, $mTrIDs)) {
                    arrPrintHitam($spec);
                    $tr = New MdlTransaksi();
                    $tr->setFilters(array());
                    $where = array(
                        "id" => $id
                    );
                    $data = array(
                        "cli" => 0
                    );
                    $tr->updateData($where, $data);
                    showLast_query("orange");
                }
            }
        }

        mati_disini(__LINE__ . " BERHASIL SETOP...");
        $this->db->trans_complete() or mati_disini("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>SELESAI...</h3>");

    }

    // generate pph23 belum input fakturnya...
    public function generatorConnecting()
    {
        $this->load->model("MdlTransaksi");

//        $trID = "167699";
//        $trID = "167761";
        $trID = "167775";
        $tr = new MdlTransaksi();
        $tr->addFilter("id in (" . implode(",", explode("-", $trID)) . ")");
        $tmpTr = $tr->lookupAll()->result();


        $this->db->trans_start();


        if (sizeof($tmpTr) > 0) {
            $transDatas = $tmpTr[0];
            $masterID = $tmpTr[0]->id_master;
            $topID = $tmpTr[0]->id_top;
            $tmpNomorNota = $tmpTr[0]->nomer;
            $origJenis = $jenisMaster = $tmpTr[0]->jenis_master;
            $currentStepNum = $tmpTr[0]->step_number;
            $cabangID = $tmpTr[0]->cabang_id;
            $gudangID = $tmpTr[0]->gudang_id;
            $cCode_old = "_TR_" . $origJenis;
            $sessionData = array();

            $trr = new MdlTransaksi();
            $trr->setFilters(array());
            $trr->addFilter("transaksi_id in (" . implode(",", explode("-", $trID)) . ")");
            $tmpReg = $trr->lookupDataRegistries()->result();
            cekKuning($this->db->last_query());
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $regSpec) {
                    foreach ($regSpec as $key_reg => $val_reg) {
                        if ($key_reg != "transaksi_id") {
                            if ($val_reg == NULL) {
                                $val_reg = blobEncode(array());
                            }
                            $sessionData[$cCode_old][$key_reg] = blobDecode($val_reg);
                        }
                    }
                }
            }
            $configUiMasterModulOrigJenis = loadConfigModulJenis_he_misc($origJenis, "coTransaksiUi");
            $steps = isset($configUiMasterModulOrigJenis['steps']) ? $configUiMasterModulOrigJenis['steps'] : array();
            $connector = isset($configUiMasterModulOrigJenis['connectTo']) ? $configUiMasterModulOrigJenis['connectTo'] : "";
            $preReplacer = isset($configUiMasterModulOrigJenis['replacerConnectTo']) ? $configUiMasterModulOrigJenis['replacerConnectTo'] : array();
            $validateValueConnector = isset($configUiMasterModulOrigJenis['connectoValidate'][1]) ? $configUiMasterModulOrigJenis['connectoValidate'][1] : array();
//            arrPrintOrange($configUiMasterModulOrigJenis);
            if (strlen($connector) > 0) {
                cekMerah(":: CONNECTING BEGIN... [$connector]");
                if (sizeof($steps) == 1) {
                    //cekMerah("now connecting to $connector");
                    $configUiMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiUi");
                    $configCoreMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiCore");
                    $configLayoutMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiLayout");
                    $configValuesMasterModulJenis = loadConfigModulJenis_he_misc($connector, "coTransaksiValues");
                    $modul_transaksi = $this->config->item("heTransaksi_ui")[$connector]["modul"];
                    $tCodeTargetJenisTransaksi = $configUiMasterModulJenis['steps'][1]['target'];

                    if (sizeof($configUiMasterModulJenis) == 0) {
                        die("kode connector tidak dikenali!");
                    }
                    if (sizeof($configUiMasterModulJenis['steps']) < 2) {
                        die("konfigurasi connector harus memiliki step lebih dari satu!");
                    }

                    $oldCode = $cCode_old;
                    $cCode = "_TR_" . $connector;
                    if (isset($sessionData[$cCode])) {
                        $sessionData[$cCode] = null;
                        unset($sessionData[$cCode]);
                        $sessionData[$cCode] = array();
                    }

                    //region detector cloner dalam satu cabang
                    $oldStep = $sessionData[$oldCode]['main']["step_number"];
                    $clonerTransaction = isset($configUiMasterModulOrigJenis['clonerTransaction'][$oldStep]) ? $configUiMasterModulOrigJenis['clonerTransaction'][$oldStep] : array();
                    $connectoSwitcherGate = isset($configUiMasterModulOrigJenis['connectoSwitcherGate']) ? $configUiMasterModulOrigJenis['connectoSwitcherGate'] : array();
                    //endregion

                    if (sizeof($clonerTransaction) && isset($clonerTransaction['main']['cloner'])) {
                        cekHere("i am here");
                        $replacerTableinDetail = array(
                            "dtime" => "dtime",
                            "produk_id" => "id",
                            "produk_kode" => "kode",
                            "produk_label" => "label",
                            "produk_nama" => "nama",
                            "produk_ord_jml" => "produk_ord_jml",
                            "produk_ord_hrg" => "harga1",
                            "satuan" => "satuan",
                            "produk_jenis" => "produk_jenis",
                            "harga" => "harga1",
                            "valid_qty" => "produk_ord_jml",
                        );
                        $replacerStepItems2 = array(
                            "sub_tail_number" => "",
                            "sub_tail_code" => "",
                            "sub_step_avail" => "",
                            "sub_step_current" => "",
                            "sub_step_number" => "",
                            "next_substep_num" => "",
                            "next_substep_code" => "",
                            "next_substep_label" => "",
                            "next_subgroup_code" => "",
                        );
//                        $itesmNewTmp = array();
//                        foreach ($sessionData[$oldCode]['items2'] as $indexItems2) {
//                            foreach ($indexItems2 as $itemsDetail) {
//
//                                $itesmNewTmp[$itemsDetail['id']] = array_merge($itemsDetail, $replacerStepItems2);
//                                foreach ($replacerTableinDetail as $selCol => $xAlias) {
//                                    $sessionData[$oldCode]['tableIn_detail2'][$itemsDetail['id']][$selCol] = $itemsDetail[$selCol];
//                                }
//                                foreach ($replacerStepItems2 as $stepItems2 => $tempItems2Val) {
//                                    $sessionData[$oldCode]['tableIn_detail2'][$itemsDetail['id']][$stepItems2] = $tempItems2Val;
//                                }
//
//                            }
//
//                            //                            arrPrint($itesmNew);
//                        }
//                        $itesmNew = array();
//                        foreach ($itesmNewTmp as $itemsID => $itemData) {
//                            if (isset($itemData['harga'])) {
//                                $itemData['harga'] = $itemData['produk_ord_hrg'];
//                            }
//                            if (!isset($itemData['pihakName'])) {
//                                $itemData['pihakName'] = $sessionData[$oldCode]['main']['pihakName'];
//                            }
//
//                            $itesmNew[$itemsID] = $itemData;
//                        }
//
//                        //region itemsToMaster
//                        $itemTomasterStatic = isset($clonerTransaction['staticItemToMaster']) ? $clonerTransaction['staticItemToMaster'] : array();
//                        $itemToMaster = isset($clonerTransaction['itemToMaster']) ? $clonerTransaction['itemToMaster'] : array();
//                        //                        arrPrint($itemToMaster);
//                        if (sizeof($itemToMaster) > 0) {
//                            foreach ($sessionData[$oldCode]['items'] as $itemsTemp) {
//                                foreach ($itemToMaster as $colItems => $aliasMaster) {
//                                    if (isset($itemsTemp[$colItems])) {
//                                        $sessionData[$oldCode]['main'][$aliasMaster] = $itemsTemp[$colItems];
//                                        $sessionData[$oldCode]['tableIn_master'][$aliasMaster] = $itemsTemp[$colItems];
//                                    }
//                                }
//                            }
//                            if (sizeof($itemTomasterStatic) > 0) {
//                                foreach ($itemTomasterStatic as $kolStatic => $valStatic) {
//                                    $sessionData[$oldCode]['main'][$kolStatic] = $valStatic;
//                                    $sessionData[$oldCode]['tableIn_master'][$kolStatic] = $valStatic;
//                                }
//                            }
//
//                        }
//
//
                        $sessionData[$cCode] = array(
                            "main" => $sessionData[$oldCode]['main'],
                            "items" => isset($sessionData[$oldCode]['items']) ? $sessionData[$oldCode]['items'] : array(),
                            'items2' => isset($sessionData[$oldCode]['items2']) ? $sessionData[$oldCode]['items2'] : array(),
                            'items2_sum' => isset($sessionData[$oldCode]['items2_sum']) ? $sessionData[$oldCode]['items2_sum'] : array(),
                            'items3' => isset($sessionData[$oldCode]['items3']) ? $sessionData[$oldCode]['items3'] : array(),
                            'items3_sum' => isset($sessionData[$oldCode]['items3_sum']) ? $sessionData[$oldCode]['items3_sum'] : array(),
                            'items4_sum' => isset($sessionData[$oldCode]['items4_sum']) ? $sessionData[$oldCode]['items4_sum'] : array(),
                            "tableIn_master" => $sessionData[$oldCode]['tableIn_master'],
                            "tableIn_detail" => $sessionData[$oldCode]['tableIn_detail'],
                            'tableIn_detail2_sum' => isset($sessionData[$oldCode]['tableIn_detail2_sum']) ? $sessionData[$oldCode]['tableIn_detail2_sum'] : array(),
                            "rsltItems" => isset($sessionData[$oldCode]['rsltItems']) ? $sessionData[$oldCode]['rsltItems'] : array(),
                            'rsltItems2' => isset($sessionData[$oldCode]['rsltItems2']) ? $sessionData[$oldCode]['rsltItems2'] : array(),
                            "tableIn_detail_rsltItems" => isset($sessionData[$oldCode]['tableIn_detail_rsltItems']) ? $sessionData[$oldCode]['tableIn_detail_rsltItems'] : array(),
                            'tableIn_detail_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_rsltItems2'] : array(),
                            "main_add_values" => $sessionData[$oldCode]['main_add_values'],
                            "main_add_fields" => $sessionData[$oldCode]['main_add_fields'],
                            "main_elements" => $sessionData[$oldCode]['main_elements'],
                            "tableIn_master_values" => $sessionData[$oldCode]['tableIn_master_values'],
                            "tableIn_detail_values" => $sessionData[$oldCode]['tableIn_detail_values2_sum'],
                            "tableIn_detail_values_rsltItems" => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems'] : array(),
                            'tableIn_detail_values_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems2'] : array(),
                            'tableIn_detail_values2_sum' => isset($sessionData[$oldCode]['tableIn_detail_values2_sum']) ? $sessionData[$oldCode]['tableIn_detail_values2_sum'] : array(),
                        );

                        //region unset sessio details2
                        //                        unset($sessionData[$cCode]['tableIn_detail_values2_sum']);
                        if (isset($clonerTransaction['resetGate']) && (sizeof($clonerTransaction['resetGate']) > 0)) {
                            foreach ($clonerTransaction['resetGate'] as $gate) {
                                unset($sessionData[$cCode][$gate]);
                            }
                        }
                        //endregion

                        $masterReplacers = array(
                            "inv" => $tmpNomorNota,
                            "jenis_master" => $connector,
                            "jenis_top" => $configUiMasterModulJenis['steps'][1]['target'],
                            "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                            "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                            "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                            "div_id" => "18",
                            "div_nama" => "default",
                            "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                            "step_current" => 1,
                            "step_number" => 1,
                            "next_step_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                            "next_step_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                            "next_group_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                            "next_step_num" => isset($configUiMasterModulJenis['steps'][2]) ? 2 : "0",
                        );
                        $masterReplacersO = array(
                            "jenisTr" => $connector,
                            "div_id" => "18",
                            "jenisTrMaster" => $connector,
                            "jenisTrTop" => $configUiMasterModulJenis['steps'][1]['target'],
                            "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                            "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                            "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                            "stepCode" => $configUiMasterModulJenis['steps'][1]['target'],
                        );
                    }
                    else {
                        $sessionData[$cCode] = array(
                            "main" => $sessionData[$oldCode]['main'],
                            "items" => $sessionData[$oldCode]['items'],
                            'items2' => isset($sessionData[$oldCode]['items2']) ? $sessionData[$oldCode]['items2'] : array(),
                            'items2_sum' => isset($sessionData[$oldCode]['items2_sum']) ? $sessionData[$oldCode]['items2_sum'] : array(),
                            'items3' => isset($sessionData[$oldCode]['items3']) ? $sessionData[$oldCode]['items3'] : array(),
                            'items3_sum' => isset($sessionData[$oldCode]['items3_sum']) ? $sessionData[$oldCode]['items3_sum'] : array(),
                            'items4_sum' => isset($sessionData[$oldCode]['items4_sum']) ? $sessionData[$oldCode]['items4_sum'] : array(),
                            "tableIn_master" => $sessionData[$oldCode]['tableIn_master'],
                            "tableIn_detail" => $sessionData[$oldCode]['tableIn_detail'],
                            'tableIn_detail2_sum' => isset($sessionData[$oldCode]['tableIn_detail2_sum']) ? $sessionData[$oldCode]['tableIn_detail2_sum'] : array(),
                            "rsltItems" => $sessionData[$oldCode]['rsltItems'],
                            'rsltItems2' => isset($sessionData[$oldCode]['rsltItems2']) ? $sessionData[$oldCode]['rsltItems2'] : array(),
                            "tableIn_detail_rsltItems" => $sessionData[$oldCode]['tableIn_detail_rsltItems'],
                            'tableIn_detail_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_rsltItems2'] : array(),
                            "tableIn_master_values" => $sessionData[$oldCode]['tableIn_master_values'],
                            "tableIn_detail_values" => $sessionData[$oldCode]['tableIn_detail_values'],
                            "tableIn_detail_values_rsltItems" => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems'] : array(),
                            'tableIn_detail_values_rsltItems2' => isset($sessionData[$oldCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$oldCode]['tableIn_detail_values_rsltItems2'] : array(),
                            'tableIn_detail_values2_sum' => isset($sessionData[$oldCode]['tableIn_detail_values2_sum']) ? $sessionData[$oldCode]['tableIn_detail_values2_sum'] : array(),
                        );
                        $masterReplacers = array(
                            "inv" => $tmpNomorNota,
                            "jenis_master" => $connector,
                            "jenis_top" => $configUiMasterModulJenis['steps'][1]['target'],
                            "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                            "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                            "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                            "cabang_id" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$oldCode]['tableIn_master']['cabang2_id'],
                            "cabang_nama" => isset($preReplacer['cabang2Name']) ? $preReplacer['cabang2Name'] : $sessionData[$oldCode]['tableIn_master']['cabang2_nama'],
                            "cabang2_id" => $sessionData[$oldCode]['tableIn_master']['cabang_id'],
                            "cabang2_nama" => $sessionData[$oldCode]['tableIn_master']['cabang_nama'],
                            "gudang_id" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$oldCode]['tableIn_master']['gudang2_id'],
                            "gudang_nama" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$oldCode]['tableIn_master']['gudang2_nama'],
                            "gudang2_id" => $sessionData[$oldCode]['tableIn_master']['gudang_id'],
                            "gudang2_nama" => $sessionData[$oldCode]['tableIn_master']['gudang_nama'],
                            "step_avail" => sizeof($configUiMasterModulJenis['steps']),
                            "step_current" => 1,
                            "step_number" => 1,
                            "next_step_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['target'] : "",
                            "next_step_label" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['label'] : "",
                            "next_group_code" => isset($configUiMasterModulJenis['steps'][2]) ? $configUiMasterModulJenis['steps'][2]['userGroup'] : "",
                            "next_step_num" => isset($configUiMasterModulJenis['steps'][2]) ? 2 : "0",
                        );
                        $masterReplacersO = array(
                            "jenisTr" => $connector,
                            "jenisTrMaster" => $connector,
                            "jenisTrTop" => $configUiMasterModulJenis['steps'][1]['target'],
                            "jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                            "jenis_label" => $configUiMasterModulJenis['steps'][1]['label'],
                            "transaksi_jenis" => $configUiMasterModulJenis['steps'][1]['target'],
                            "stepCode" => $configUiMasterModulJenis['steps'][1]['target'],
                            "placeID" => isset($preReplacer['place2ID']) ? $preReplacer['place2ID'] : $sessionData[$oldCode]['main']['place2ID'],
                            "placeName" => isset($preReplacer['place2Name']) ? $preReplacer['place2Name'] : $sessionData[$oldCode]['main']['place2Name'],
                            "place2ID" => $sessionData[$oldCode]['main']['placeID'],
                            "place2Name" => $sessionData[$oldCode]['main']['placeName'],
                            "cabangID" => isset($preReplacer['cabang2ID']) ? $preReplacer['cabang2ID'] : $sessionData[$oldCode]['main']['place2ID'],
                            "cabangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$oldCode]['main']['place2Name'],
                            "cabang2ID" => $sessionData[$oldCode]['main']['placeID'],
                            "cabang2Name" => $sessionData[$oldCode]['main']['placeName'],
                            "gudang2ID" => $sessionData[$cCode]['main']['gudangID'],
                            "gudang2Name" => $sessionData[$cCode]['main']['gudangName'],
                            "gudangID" => isset($preReplacer['gudang2ID']) ? $preReplacer['gudang2ID'] : $sessionData[$cCode]['main']['gudang2ID'],
                            "gudangName" => isset($preReplacer['gudang2Name']) ? $preReplacer['gudang2Name'] : $sessionData[$cCode]['main']['gudang2Name'],
                            "efaktur_source" => isset($preReplacer['efaktur_source']) ? $sessionData[$cCode]['main']['nomer'] : "",
                        );
                    }

                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        if (sizeof($connectoSwitcherGate) > 0) {
                            $gateSource = $connectoSwitcherGate["gateSource"];
                            $gateTarget = $connectoSwitcherGate["gateTarget"];
                            $sessionGateSource = isset($sessionData[$cCode][$gateSource]) ? $sessionData[$cCode][$gateSource] : array();
                            $sessionGateTarget = isset($sessionData[$cCode][$gateTarget]) ? $sessionData[$cCode][$gateTarget] : array();
                            $sessionData[$cCode][$gateSource] = $sessionGateTarget;
                            $sessionData[$cCode][$gateTarget] = $sessionGateSource;
                        }
                    }

                    //==replace pertama
                    foreach ($masterReplacersO as $key => $val) {
                        $sessionData[$cCode]['main'][$key] = $val;
                    }
                    foreach ($masterReplacers as $key => $val) {
                        $sessionData[$cCode]['tableIn_master'][$key] = $val;
                    }

                    $addItemFields = $configUiMasterModulJenis["shoppingCartNumFields"][1];
//                    arrPrintCyan($addItemFields);
                    if (sizeof($addItemFields) > 0) {
                        foreach ($sessionData[$cCode]['items'] as $ii => $iiSpec) {
                            foreach ($addItemFields as $add_key => $xxx) {
                                if (!isset($iiSpec[$add_key])) {
                                    $sessionData[$cCode]['items'][$ii][$add_key] = "";
                                }
                            }
                            $sessionData[$cCode]['items'][$ii]["extern_id"] = $iiSpec["pihakID"];
                            $sessionData[$cCode]['items'][$ii]["extern_nama"] = $iiSpec["pihakName"];
                        }
                    }

                    //region penomoran receipt #2
                    //<editor-fold desc="==========penomoran">
                    $this->load->model("CustomCounter");
                    $cn = new CustomCounter("transaksi");
                    $cn->setType("transaksi");
                    $cn->setModul($modul_transaksi);
                    $cn->setStepCode($tCodeTargetJenisTransaksi);

                    $counterForNumber = array($configCoreMasterModulJenis['formatNota']);
                    if (!in_array($counterForNumber[0], $configCoreMasterModulJenis['counters'])) {
                        mati_disini(__LINE__ . " Used number should be registered in 'counters' config as well");
                    }

                    foreach ($counterForNumber as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        $cValues = array();
                        foreach ($cParams as $param) {
                            $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    }

                    $tmpNomorNota2 = $paramSpec['paramString'];
                    $tmpNomorNota2Alias = formatNota("nomer_nolink", $tmpNomorNota2);


                    //</editor-fold>
                    //endregion

                    //region dynamic counters #2
                    // <editor-fold defaultstate="collapsed" desc="==========__init+update dynamic-counters ">
                    $cn = new CustomCounter("transaksi");
                    $cn->setType("transaksi");
                    $cn->setModul($modul_transaksi);
                    $cn->setStepCode($tCodeTargetJenisTransaksi);
                    $configCustomParams = $configCoreMasterModulJenis['counters'];
                    $configCustomParams[] = "stepCode";
                    if (sizeof($configCustomParams) > 0) {
                        $cContent = array();
                        foreach ($configCustomParams as $i => $cRawParams) {
                            $cParams = explode("|", $cRawParams);
                            $cValues = array();
                            foreach ($cParams as $param) {
                                $cValues[$i][$param] = $sessionData[$cCode]['main'][$param];
                            }
                            $cRawValues = implode("|", $cValues[$i]);
                            $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                            $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                            switch ($paramSpec['id']) {
                                case 0: //===counter type is new
                                    $paramKeyRaw = print_r($cParams, true);
                                    $paramValuesRaw = print_r($cValues[$i], true);
                                    $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                                    break;
                                default: //===counter to be updated
                                    $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                                    break;
                            }
                            //echo "<hr>";
                        }
                    }
                    $appliedCounters2 = base64_encode(serialize($cContent));
                    $appliedCounters_inText2 = print_r($cContent, true);
                    // </editor-fold>
                    //endregion

                    //region numbering tambahan
                    $this->load->library("CounterNumber");
                    $ccn = new CounterNumber();
                    $ccn->setCCode($cCode);
                    $ccn->setJenisTr($connector);
                    $ccn->setTransaksiGate($sessionData[$cCode]['tableIn_master']);
                    $ccn->setMainGate($sessionData[$cCode]['main']);
                    $ccn->setItemsGate($sessionData[$cCode]['items']);
                    $ccn->setItems2SumGate($sessionData[$cCode]['items2_sum']);
                    $new_counter = $ccn->getCounterNumber();
                    cekHitam("jenistr yang disett dari create " . $this->jenisTr);

                    if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                        foreach ($new_counter['main'] as $ckey => $cval) {
                            $sessionData[$cCode]['tableIn_master'][$ckey] = $cval;
                            $sessionData[$cCode]['main'][$ckey] = $cval;
                        }
                    }
                    if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
//                        matiHere(__LINE__);
                        foreach ($new_counter['items'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $sessionData[$cCode]['items'][$ikey][$iikey] = $iival;
                            }
                        }
                    }
                    if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                        foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $sessionData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                            }
                        }
                    }
                    //endregion

                    //replacer detail/items
                    if (isset($new_counter['main']) && sizeof($new_counter['main']) > 0) {
                        foreach ($new_counter['main'] as $ckey => $cval) {
                            $sessionData[$cCode]['tableIn_master'][$ckey] = $cval;
                            $sessionData[$cCode]['main'][$ckey] = $cval;
                        }
                    }
                    if (isset($new_counter['items']) && sizeof($new_counter['items']) > 0) {
                        cekBiru(__LINE__);
                        foreach ($new_counter['items'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $sessionData[$cCode]['items'][$ikey][$iikey] = $iival;
                            }
                        }
                    }
                    if (isset($new_counter['items2_sum']) && sizeof($new_counter['items2_sum']) > 0) {
                        foreach ($new_counter['items2_sum'] as $ikey => $iSpec) {
                            foreach ($iSpec as $iikey => $iival) {
                                $sessionData[$cCode]['items2_sum'][$ikey][$iikey] = $iival;
                            }
                        }
                    }

                    $addValues = array(
                        'counters' => $appliedCounters,
                        'counters_intext' => $appliedCounters_inText,
                        'nomer' => $tmpNomorNota2,
                        'nomer2' => $tmpNomorNota2Alias,
                        'dtime' => date("Y-m-d H:i:s"),
                        'fulldate' => date("Y-m-d"),
                    );
                    foreach ($addValues as $key => $val) {
                        $sessionData[$cCode]['tableIn_master'][$key] = $val;
                    }


                    $masterReplacers = array(
                        "nomer" => $tmpNomorNota2,
                        "nomer2" => $tmpNomorNota2Alias,
                        "counters" => $appliedCounters2,
                        "counters_intext" => $appliedCounters_inText2,
                    );
                    foreach ($masterReplacers as $key => $val) {
                        $sessionData[$cCode]['tableIn_master'][$key] = $val;
                    }

                    //===cloning detail/items cabang1 ke cabang2
                    //===yang direplace: sub_step_number, sub_step_current, sub_step_avail, next_substep_num, next_substep_code, next_substep_label, next_subgroup_code
                    $detailReplacers = array(
                        "sub_step_avail" => sizeof($configUiMasterModulJenis['steps']),
                        "sub_step_current" => 1,
                        "sub_step_number" => 1,
                        "next_substep_num" => $sessionData[$cCode]['tableIn_master']['next_step_num'],
                        "next_substep_code" => $sessionData[$cCode]['tableIn_master']['next_step_code'],
                        "next_substep_label" => $sessionData[$cCode]['tableIn_master']['next_step_label'],
                        "next_subgroup_code" => $sessionData[$cCode]['tableIn_master']['next_group_code'],
                    );
                    if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                        foreach ($sessionData[$cCode]['tableIn_detail'] as $k => $dSpec) {
                            foreach ($dSpec as $key => $val) {
                                $sessionData[$cCode]['tableIn_detail'][$k][$key] = isset($detailReplacers[$key]) ? $detailReplacers[$key] : $val;
                            }
                        }
                    }

                    //region ----------write transaksi & transaksi_data #2
                    if (isset($sessionData[$cCode]['tableIn_master']) && sizeof($sessionData[$cCode]['tableIn_master']) > 0) {
                        $tr = new MdlTransaksi();
                        $tr->addFilter("transaksi.cabang_id='" . $cabangID . "'");
                        $insertID = $tr->writeMainEntries($sessionData[$cCode]['tableIn_master']);
                        cekHitam($this->db->last_query());
                        $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $sessionData[$cCode]['tableIn_master']);
                        $insertNum = $sessionData[$cCode]['tableIn_master']['nomer'];
                        $sessionData[$cCode]['main']['nomer'] = $insertNum;
                        if ($insertID < 1) {
                            die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                        }
                        $mongoListConnect['main'] = array($insertID, $epID);
                    }
                    $inserMainValues = array();
                    if (isset($sessionData[$cCode]['tableIn_master_values']) && sizeof($sessionData[$cCode]['tableIn_master_values']) > 0) {
                        foreach ($sessionData[$cCode]['tableIn_master_values'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] = $dd;
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                    }

                    if (isset($sessionData[$cCode]['main_add_values']) && sizeof($sessionData[$cCode]['main_add_values']) > 0) {
                        foreach ($sessionData[$cCode]['main_add_values'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] = $dd;
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                    }

                    if (isset($sessionData[$cCode]['main_inputs']) && sizeof($sessionData[$cCode]['main_inputs']) > 0) {
                        cekkuning("main_inputs detected");
                        $inserMainValues = array();
                        foreach ($sessionData[$cCode]['main_inputs'] as $key => $val) {
                            $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                            $inserMainValues[] = $dd;
                            $mongoListConnect['mainValues'][] = $dd;
                        }
                    }

                    if (isset($sessionData[$cCode]['main_add_fields']) && sizeof($sessionData[$cCode]['main_add_fields']) > 0) {
                        foreach ($sessionData[$cCode]['main_add_fields'] as $key => $val) {
                            $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                        }
                    }

                    if (isset($sessionData[$cCode]['main_elements']) && sizeof($sessionData[$cCode]['main_elements']) > 0) {
                        foreach ($sessionData[$cCode]['main_elements'] as $elName => $aSpec) {
                            $tr->writeMainElements($insertID, array(
                                "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                                "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                                "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                                "name" => $aSpec['name'],
                                "label" => $aSpec['label'],
                                "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                                "contents_intext" => isset($aSpec['contents_intext']) ? print_r($aSpec['contents_intext'], true) : "",

                            ));
                        }
                    }

                    if (isset($sessionData[$cCode]['tableIn_detail']) && sizeof($sessionData[$cCode]['tableIn_detail']) > 0) {
                        $insertIDs = array();
                        $insertDeIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_detail'] as $dSpec) {
                            $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                            if ($insertDetailID < 1) {
                                mati_disini("Gagal saat berusaha write transaction detail entry pada " . __FILE__ . " baris " . __LINE__);
                            }
                            else {
                                $insertIDs[] = $insertDetailID;
                                $insertDeIDs[$insertID][] = $insertDetailID;
                                $mongoListConnect['detail'][] = $insertDetailID;
                            }
                            if ($epID != 999) {
                                $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                                if ($insertEpID < 1) {
                                    mati_disini("Gagal saat berusaha write transaction detail entry point pada " . __FILE__ . " baris " . __LINE__);
                                }
                                else {
                                    $insertIDs[] = $insertEpID;
                                    $insertDeIDs[$epID][] = $insertEpID;
                                    $mongoListConnect['detail'][] = $insertEpID;
                                }
                            }
                        }
                        if (sizeof($insertIDs) == 0) {
                            mati_disini(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                        }
                        else {
                            $indexing_details = array();
                            foreach ($insertDeIDs as $key => $numb) {
                                $indexing_details[$key] = $numb;
                            }

                            foreach ($indexing_details as $k => $arrID) {
                                $arrBlob = blobEncode($arrID);
                                $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                                cekOrange($this->db->last_query());
                            }
                        }
                    }

                    if (isset($sessionData[$cCode]['tableIn_detail2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail2_sum']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_detail2_sum'] as $dSpec) {
                            $insertIDDetail = $tr->writeDetailEntries($insertID, $dSpec);
                            $insertIDs[] = $insertIDDetail;
                            $mongoListConnect['detail'][] = $insertIDDetail;
                            if ($epID != 999) {
                                $insertIDDetail = $tr->writeDetailEntries($epID, $dSpec);
                                $insertIDs[] = $insertIDDetail;
                                $mongoListConnect['detail'][] = $insertIDDetail;
                            }
                        }
                    }

                    if (isset($sessionData[$cCode]['tableIn_detail_values']) && sizeof($sessionData[$cCode]['tableIn_detail_values']) > 0) {
                        $insertIDs = array();
                        foreach ($sessionData[$cCode]['tableIn_detail_values'] as $pID => $dSpec) {
                            if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                                foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                    if (isset($sessionData[$cCode]['tableIn_detail'][$pID])) {
                                        $dd = $tr->writeDetailValues($insertID, array(
                                            "produk_jenis" => $sessionData[$cCode]['tableIn_detail'][$pID]['produk_jenis'],
                                            "produk_id" => $pID,
                                            "key" => $key,
                                            "value" => $dSpec[$src],
                                        ));
                                        $insertIDs[$pID][] = $dd;
                                        $mongoListConnect['detailValues'][] = $dd;
                                    }
                                }
                            }
                        }
                        if (sizeof($insertIDs) > 0) {
                            $arrBlob = blobEncode($insertIDs);
                            $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }

                    if (isset($sessionData[$cCode]['tableIn_detail_values2_sum']) && sizeof($sessionData[$cCode]['tableIn_detail_values2_sum']) > 0) {
                        foreach ($sessionData[$cCode]['tableIn_detail_values2_sum'] as $pID => $dSpec) {
                            if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                                foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                    $dd = $tr->writeDetailValues($insertID, array(
                                        "produk_jenis" => $sessionData[$cCode]['tableIn_detail2_sum'][$pID]['produk_jenis'],
                                        "produk_id" => $pID,
                                        "key" => $key,
                                        "value" => $dSpec[$src],
                                    ));
                                    $insertIDs[] = $dd;
                                    $mongoListConnect['detailValues'][] = $dd;
                                }
                            }


                        }
                    }


                    $idHis = array(
                        $stepNumber => array(
                            "dtime" => date("Y-m-d H:i:s"),
                            "fulldate" => date("Y-m-d"),
                            "olehID" => $sessionData[$cCode]['main']['olehID'],
                            "olehName" => $sessionData[$cCode]['main']['olehName'],
                            "step" => $stepNumber,
                            "trID" => $insertID,
                            "nomer" => $tmpNomorNota2,
                            "nomer2" => $tmpNomorNota2Alias,
                            "counters" => $appliedCounters2,
                            "counters_intext" => $appliedCounters_inText2,
                        ),
                    );
                    $idHis_blob = blobEncode($idHis);
                    $idHis_intext = print_r($idHis, true);

                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $insertID), array(
                        "id_master" => $masterID,
                        "id_top" => $insertID,
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,

                    )) or mati_disini("Failed to update tr next-state!");
                    cekorange($this->db->last_query());
                    $baseRegistries = array(
                        'main' => isset($sessionData[$cCode]['main']) ? $sessionData[$cCode]['main'] : array(),
                        'items' => isset($sessionData[$cCode]['items']) ? $sessionData[$cCode]['items'] : array(),
                        'items2' => isset($sessionData[$cCode]['items2']) ? $sessionData[$cCode]['items2'] : array(),
                        'items2_sum' => isset($sessionData[$cCode]['items2_sum']) ? $sessionData[$cCode]['items2_sum'] : array(),
                        'items3' => isset($sessionData[$cCode]['items3']) ? $sessionData[$cCode]['items3'] : array(),
                        'items3_sum' => isset($sessionData[$cCode]['items3_sum']) ? $sessionData[$cCode]['items3_sum'] : array(),
                        'items4_sum' => isset($sessionData[$cCode]['items4_sum']) ? $sessionData[$cCode]['items4_sum'] : array(),

                        'rsltItems' => isset($sessionData[$cCode]['rsltItems']) ? $sessionData[$cCode]['rsltItems'] : array(),
                        'rsltItems2' => isset($sessionData[$cCode]['rsltItems2']) ? $sessionData[$cCode]['rsltItems2'] : array(),
                        'rsltItems3' => isset($sessionData[$cCode]['rsltItems3']) ? $sessionData[$cCode]['rsltItems3'] : array(),

                        'tableIn_master' => isset($sessionData[$cCode]['tableIn_master']) ? $sessionData[$cCode]['tableIn_master'] : array(),
                        'tableIn_detail' => isset($sessionData[$cCode]['tableIn_detail']) ? $sessionData[$cCode]['tableIn_detail'] : array(),
                        'tableIn_detail2_sum' => isset($sessionData[$cCode]['tableIn_detail2_sum']) ? $sessionData[$cCode]['tableIn_detail2_sum'] : array(),
                        'tableIn_detail_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_rsltItems'] : array(),
                        'tableIn_detail_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_rsltItems2'] : array(),
                        'tableIn_master_values' => isset($sessionData[$cCode]['tableIn_master_values']) ? $sessionData[$cCode]['tableIn_master_values'] : array(),
                        'tableIn_detail_values' => isset($sessionData[$cCode]['tableIn_detail_values']) ? $sessionData[$cCode]['tableIn_detail_values'] : array(),
                        'tableIn_detail_values_rsltItems' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems'] : array(),
                        'tableIn_detail_values_rsltItems2' => isset($sessionData[$cCode]['tableIn_detail_values_rsltItems2']) ? $sessionData[$cCode]['tableIn_detail_values_rsltItems2'] : array(),
                        'tableIn_detail_values2_sum' => isset($sessionData[$cCode]['tableIn_detail_values2_sum']) ? $sessionData[$cCode]['tableIn_detail_values2_sum'] : array(),
                        'main_add_values' => isset($sessionData[$cCode]['main_add_values']) ? $sessionData[$cCode]['main_add_values'] : array(),
                        'main_add_fields' => isset($sessionData[$cCode]['main_add_fields']) ? $sessionData[$cCode]['main_add_fields'] : array(),
                        'main_elements' => isset($sessionData[$cCode]['main_elements']) ? $sessionData[$cCode]['main_elements'] : array(),
                        'main_inputs' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                        'main_inputs_orig' => isset($sessionData[$cCode]['main_inputs']) ? $sessionData[$cCode]['main_inputs'] : array(),
                        "receiptDetailFields" => isset($configLayoutMasterModulJenis['receiptDetailFields'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields'][1] : array(),
                        "receiptSumFields" => isset($configLayoutMasterModulJenis['receiptSumFields'][1]) ? $configLayoutMasterModulJenis['receiptSumFields'][1] : array(),
                        "receiptDetailFields2" => isset($configLayoutMasterModulJenis['receiptDetailFields2'][1]) ? $configLayoutMasterModulJenis['receiptDetailFields2'][1] : array(),
                        "receiptSumFields2" => isset($configLayoutMasterModulJenis['receiptSumFields2'][1]) ? $configLayoutMasterModulJenis['receiptSumFields2'][1] : array(),
                        "items_komposisi" => isset($sessionData[$cCode]['items_komposisi']) ? $sessionData[$cCode]['items_komposisi'] : array(),
                    );
                    $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                    $mongRegIDConnect = $doWriteReg;
                    cekBiru($this->db->last_query());
                    //endregion

                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        //region nulis paymentSource
                        $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                        $paymentSources = $this->config->item("payment_source");
                        cekHitam(":: $stepCode ::");
                        if (array_key_exists($stepCode, $paymentSources)) {

                            $payConfigs = $paymentSources[$stepCode];
                            if (sizeof($payConfigs) > 0) {
                                foreach ($payConfigs as $paymentSrcConfig) {
                                    //					$paymentSrcConfig = $paymentSources[$stepCode];
                                    $valueSrc = $paymentSrcConfig['valueSrc'];
                                    $externSrc = $paymentSrcConfig['externSrc'];
                                    $tr->writePaymentSrc($insertID, array(
                                        "jenis" => $connector,
                                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                        "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                        "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                        "nomer" => $sessionData[$cCode]['main']['nomer'],
                                        "label" => $paymentSrcConfig['label'],
                                        "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                        "terbayar" => 0,
                                        "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                        "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                        "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                        "oleh_id" => $this->session->login['id'],
                                        "oleh_nama" => $this->session->login['nama'],
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),
                                        "valas_id" => isset($sessionData[$cCode]['main'][$externSrc['valasId']]) ? $sessionData[$cCode]['main'][$externSrc['valasId']] : '',
                                        "valas_nama" => isset($sessionData[$cCode]['main'][$externSrc['valasLabel']]) ? $sessionData[$cCode]['main'][$externSrc['valasLabel']] : '',
                                        "valas_nilai" => isset($sessionData[$cCode]['main'][$externSrc['valasValue']]) ? $sessionData[$cCode]['main'][$externSrc['valasValue']] : '',
                                        "tagihan_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasTagihan']]) ? $sessionData[$cCode]['main'][$externSrc['valasTagihan']] : '',
                                        "terbayar_valas" => 0,
                                        "sisa_valas" => isset($sessionData[$cCode]['main'][$externSrc['valasSisa']]) ? $sessionData[$cCode]['main'][$externSrc['valasSisa']] : '',
                                    ));
                                    //cekMerah($this->db->last_query());
                                }
                            }


                        }
                        else {
                            //cekMerah("TIDAK nulis paymentSrc");
                        }
                        //endregion

                        //region nulis paymentAntiSource
                        $stepCode = $configUiMasterModulJenis['steps'][1]['target'];
                        $paymentSources = $this->config->item("payment_antiSource");
                        if (array_key_exists($stepCode, $paymentSources)) {

                            $payConfigs = $paymentSources[$stepCode];
                            if (sizeof($payConfigs) > 0) {
                                foreach ($payConfigs as $paymentSrcConfig) {
                                    //					$paymentSrcConfig = $paymentSources[$stepCode];
                                    $valueSrc = $paymentSrcConfig['valueSrc'];
                                    $externSrc = $paymentSrcConfig['externSrc'];
                                    $tr->writePaymentAntiSrc($insertID, array(
                                        "jenis" => $connector,
                                        "target_jenis" => $paymentSrcConfig['jenisTarget'],
                                        "reference_jenis" => $paymentSrcConfig['jenisSrc'],
                                        "extern_id" => $sessionData[$cCode]['main'][$externSrc['id']],
                                        "extern_nama" => $sessionData[$cCode]['main'][$externSrc['nama']],
                                        "nomer" => $sessionData[$cCode]['main']['nomer'],
                                        "label" => $paymentSrcConfig['label'],
                                        "tagihan" => $sessionData[$cCode]['main'][$valueSrc],
                                        "terbayar" => 0,
                                        "sisa" => $sessionData[$cCode]['main'][$valueSrc],
                                        "cabang_id" => $sessionData[$cCode]['main']['placeID'],
                                        "cabang_nama" => $sessionData[$cCode]['main']['placeName'],
                                        "oleh_id" => $this->session->login['id'],
                                        "oleh_nama" => $this->session->login['nama'],
                                        "dtime" => date("Y-m-d H:i:s"),
                                        "fulldate" => date("Y-m-d"),
                                    ));
                                    //cekMerah($this->db->last_query());
                                }
                            }


                        }
                        else {
                            //cekMerah("TIDAK nulis paymentSrc");
                        }
                        //endregion
                    }


                    //==================================================================================================
                    //==MENULIS LOCKER TRANSAKSI ACTIVE=================================================================
                    // bila step lebih dari 1
                    $nextStepConnector = sizeof($configUiMasterModulJenis['steps']);
                    if ($nextStepConnector > 1) {
                        $this->load->model("Mdls/MdlLockerTransaksi");
                        $lt = New MdlLockerTransaksi();
                        $lt->execLocker($sessionData[$cCode]['main'], $nextStepConnector, NULL, $insertID);
                    }

//                    arrPrintOrange($sessionData[$cCode]['tableIn_master']);
//                    arrPrintHitam($sessionData[$cCode]['tableIn_detail']);
                    arrPrintCyan($sessionData[$cCode]['items']);

                }
                else {
                    cekMerah("to be delayed to connect to $connector");
                }
            }
            else {
                cekKuning("not connecting to any tCode");
            }


        }
        else {
            mati_disini("KOSONG....");
        }


        mati_disini("LINE: " . __LINE__ . " , sementara under maintenance, tunggu beberapa saat lagi yaa.., TRID: $insertID");

        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>DONE...</h3>");
    }


    public function generatorBiayaUsaha()
    {
        $start = microtime(true);

        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekeningPembantuBiayaUsaha");

        $tr = New MdlTransaksi();
        $rekening = "6010";
        $cabang_id = "-1";
        $gudang_id = "-1";
        $tbl_master = "__rek_master__6010";
        $tbl_pembantu = "__rek_pembantu_subbiayausaha__6010";
        //------------------------------------------------------
        $where = array(
            "cabang_id" => "$cabang_id",
            "rekening" => "$rekening",
//            "jenis" => "1677",
        );
        $this->db->where($where);
        $tmpMaster = $this->db->get($tbl_master)->result();
        showLast_query("biru");
        foreach ($tmpMaster as $tmpSpec) {
            $trID_master[$tmpSpec->transaksi_id] = $tmpSpec->transaksi_id;
        }
        //------------------------------------------------------
        $where = array(
            "cabang_id" => "$cabang_id",
            "rekening" => "$rekening",
//            "jenis" => "1677r",
        );
        $this->db->where($where);
        $tmpDetail = $this->db->get($tbl_pembantu)->result();
        showLast_query("kuning");
        foreach ($tmpDetail as $tmpSpec) {
            $trID_detail[$tmpSpec->transaksi_id] = $tmpSpec->transaksi_id;
        }

        $count_master = count($trID_master);
        $count_detail = count($trID_detail);
        $count_selisih = $count_master - $count_detail;
        $trIDs_diff = array_diff($trID_master, $trID_detail);
        $count_diff = count($trIDs_diff);
        cekHitam("master: $count_master, detail: $count_detail, selisih: $count_selisih, diff: $count_diff");
        arrPrintHitam($trIDs_diff);
//        mati_disini(__LINE__);

        $this->db->trans_start();

        if (sizeof($trIDs_diff) > 0) {

            foreach ($trIDs_diff as $trID) {
                $tr = New MdlTransaksi();
                $tr->setFilters(array());
                $tr->addFilter("id='$trID'");
                $trTmp = $tr->lookupAll()->result();
                if (sizeof($trTmp) > 0) {
                    $dtime = $trTmp[0]->dtime;
                    $fulldate = $trTmp[0]->fulldate;
                    $insertID = $trTmp[0]->id;
                    $insertNum = $trTmp[0]->nomer;
                    $this->jenisTr = $trTmp[0]->jenis_master;

                    $trreg = New MdlTransaksi();
                    $trreg->setFilters(array());
                    $trreg->addFilter("transaksi_id='$trID'");
                    $trReg = $trreg->lookupDataRegistries()->result();
                    showLast_query("orange");
                    $arrRegDatas = array();
                    foreach ($trReg as $regSpec) {
                        foreach ($regSpec as $key => $val) {
                            if ($key != "transaksi_id") {
                                if ($val == NULL) {
                                    $val = blobEncode(array());
                                }
                                $arrRegDatas[$regSpec->transaksi_id][$key] = blobDecode($val);
                            }
                        }
                    }
                    $arrRegDatasComponent = $arrRegDatas[$trID];
//mati_disini(__LINE__);

                    $components = array(
                        "master" => array(),
                        "detail" => array(
                            array(
                                "comName" => "RekeningPembantuBiayaUsaha",
                                "loop" => array(
                                    "6010" => "harga",//biaya usaha
                                ),
                                "static" => array(
                                    "cabang_id" => "placeID",
                                    "extern_id" => "id",
                                    "extern_nama" => "name",
                                    "jenis" => "jenisTr",
                                ),
                                "srcGateName" => "items",
                                "srcRawGateName" => "items",
                            ),
                        ),
                    );


                    //region processing sub-components, if in single step geser ke CLI
                    $componentsDetailLoop = true;
                    $componentGate['detail'] = array();
                    $componentConfig['detail'] = array();
                    $iterator = $components["detail"];
                    if (sizeof($iterator) > 0) {
                        $comsLocation = "Coms";
                        $comsPrefix = "Com";
                        $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $tmpOutParams[$cCtr] = array();
                            $gg = 0;
                            $srcGateName = $tComSpec['srcGateName'];
                            if ($componentsDetailLoop == true) {
                                foreach ($arrRegDatasComponent[$srcGateName] as $id => $dSpec) {
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $comName = $tComSpec['comName'];
                                    if (substr($comName, 0, 1) == "{") {
                                        $comName = trim($comName, "{");
                                        $comName = trim($comName, "}");
                                        $comName = str_replace($comName, $arrRegDatasComponent[$srcGateName][$id][$comName], $comName);
                                    }

                                    $mdlName = "$comsPrefix" . ucfirst($comName);
                                    if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                        $filterNeeded = true;
                                    }
                                    else {
                                        $filterNeeded = false;
                                    }
                                    cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                    $subParams = array();

                                    if (isset($tComSpec['loop'])) {
                                        foreach ($tComSpec['loop'] as $key => $value) {
                                            if (substr($key, 0, 1) == "{") {
                                                $key = trim($key, "{");
                                                $key = trim($key, "}");
                                                $key = str_replace($key, $arrRegDatasComponent[$srcGateName][$id][$key], $key);
                                            }

                                            $realValue = makeValue($value, $arrRegDatasComponent[$srcGateName][$id], $arrRegDatasComponent[$srcGateName][$id], 0);
                                            $subParams['loop'][$key] = $realValue;

                                            if ($filterNeeded) {
                                                if ($subParams['loop'][$key] == 0) {
                                                    unset($subParams['loop'][$key]);
                                                }
                                            }
                                        }
                                    }
                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {
                                            $realValue = makeValue($value, $arrRegDatasComponent[$srcGateName][$id], $arrRegDatasComponent[$srcGateName][$id], 0);
                                            $subParams['static'][$key] = $realValue;
                                        }
                                        if (!isset($subParams['static']["transaksi_id"])) {
                                            $subParams['static']["transaksi_id"] = $insertID;
                                        }
                                        if (!isset($subParams['static']["transaksi_no"])) {
                                            $subParams['static']["transaksi_no"] = $insertNum;
                                        }

                                        $subParams['static']["fulldate"] = $fulldate;
                                        $subParams['static']["dtime"] = $dtime;
                                        $subParams['static']["keterangan"] = isset($arrRegDatasComponent[$srcGateName][$id]["keterangan"]) ? $arrRegDatasComponent[$srcGateName][$id]["keterangan"] : "";
                                        if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                            $subParams['static']['reverted_target'] = $revertedTarget;
                                        }
                                    }

                                    if (sizeof($subParams) > 0) {
//                                cekhitam("subparam ada isinya");
                                        if ($filterNeeded) {
                                            if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                                $tmpOutParams[$cCtr][] = $subParams;
                                            }
                                        }
                                        else {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        cekhitam("subparam TIDAK ada isinya");
                                    }
                                }
                            }
                            else {
                                foreach ($arrRegDatasComponent[$srcGateName] as $id => $dSpec) {
                                    if ($cCtr == $id) {
                                        $srcRawGateName = $tComSpec['srcRawGateName'];
                                        $comName = $tComSpec['comName'];
                                        if (substr($comName, 0, 1) == "{") {
                                            $comName = trim($comName, "{");
                                            $comName = trim($comName, "}");

                                            $comName = str_replace($comName, $arrRegDatasComponent[$srcGateName][$id][$comName], $comName);
                                        }

                                        $mdlName = "$comsPrefix" . ucfirst($comName);
                                        if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                            $filterNeeded = true;
                                        }
                                        else {
                                            $filterNeeded = false;
                                        }
                                        cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                        $subParams = array();

                                        if (isset($tComSpec['loop'])) {
                                            foreach ($tComSpec['loop'] as $key => $value) {

                                                if (substr($key, 0, 1) == "{") {
                                                    $key = trim($key, "{");
                                                    $key = trim($key, "}");

                                                    $key = str_replace($key, $arrRegDatasComponent[$srcGateName][$id][$key], $key);
                                                }

                                                $realValue = makeValue($value, $arrRegDatasComponent[$srcGateName][$id], $arrRegDatasComponent[$srcGateName][$id], 0);
                                                $subParams['loop'][$key] = $realValue;

                                                if ($filterNeeded) {
                                                    if ($subParams['loop'][$key] == 0) {
                                                        unset($subParams['loop'][$key]);
                                                    }
                                                }
                                            }
                                        }
                                        if (isset($tComSpec['static'])) {
                                            foreach ($tComSpec['static'] as $key => $value) {
                                                $realValue = makeValue($value, $arrRegDatasComponent[$srcGateName][$id], $arrRegDatasComponent[$srcGateName][$id], 0);
                                                $subParams['static'][$key] = $realValue;

                                            }
                                            if (!isset($subParams['static']["transaksi_id"])) {
                                                $subParams['static']["transaksi_id"] = $insertID;
                                            }
                                            if (!isset($subParams['static']["transaksi_no"])) {
                                                $subParams['static']["transaksi_no"] = $insertNum;
                                            }

                                            $subParams['static']["fulldate"] = $fulldate;
                                            $subParams['static']["dtime"] = $dtime;
                                            $subParams['static']["keterangan"] = "";
                                            if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                                $subParams['static']['reverted_target'] = $revertedTarget;
                                            }
                                        }

                                        if (sizeof($subParams) > 0) {

                                            if ($filterNeeded) {
                                                if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                                    $tmpOutParams[$cCtr][] = $subParams;
                                                }
                                            }
                                            else {
                                                $tmpOutParams[$cCtr][] = $subParams;
                                            }
                                        }
                                        else {
                                            cekhitam("subparam TIDAK ada isinya");
                                        }
                                    }
                                }
                            }

                            $componentGate['detail'][$cCtr] = $subParams;
                        }
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $srcGateName = $tComSpec['srcGateName'];
                            foreach ($arrRegDatasComponent[$srcGateName] as $id => $dSpec) {
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $arrRegDatasComponent[$srcGateName][$id][$comName], $comName);
                                }
                            }
                            cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                            $mdlName = "$comsPrefix" . ucfirst($comName);
                            $this->load->model("$comsLocation/" . $mdlName);
                            $m = new $mdlName();
                            //===filter value nol, jika harus difilter

                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }

                            // matiHEre($tobeExecuted);
                            if ($tobeExecuted) {
                                //----- kiriman gerbang
                                if (method_exists($m, "setTableInMaster")) {
                                    $m->setTableInMaster($arrRegDatasComponent["tableIn_master"]);
                                }
                                if (method_exists($m, "setDetail")) {
                                    $m->setDetail($arrRegDatasComponent[$srcGateName]);
                                }
                                if (method_exists($m, "setJenisTr")) {
                                    $m->setJenisTr($this->jenisTr);
                                }
                                //----- kiriman gerbang
                                $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/");
                                $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/");
                                cekBiru($this->db->last_query());
                            }
                            else {
                                cekMerah("$comName tidak eksekusi");
                            }

                        }
                    }
                    else {
                        cekKuning("subcomponents is not set");
                    }
                    //endregion


                }

//            break;
            }

        }
        else {
            mati_disini("HABIS...");
        }
        $end = microtime(true);
        $selesai = $end - $start;

        mati_disini(__LINE__ . " BERHASIL SETOP... [$selesai]");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>SELESAI...</h3>");


    }

    public function rebuildBiayaUsaha()
    {
        $start = microtime(true);

        $rekening = "6010";
        $cabang_id = "-1";
        $gudang_id = "-1";
//        $tbl_pembantu = "__rek_pembantu_subbiayausaha__6010";
//        $tbl_pembantu_target = "__rek_pembantu_subbiayausaha__6010_target";
        $tbl_pembantu = "__rek_pembantu_subbiayausaha__6010_target";
        $tbl_pembantu_target = "__rek_pembantu_subbiayausaha__6010";
        //------------------------------------------------------
        $arrDatas = array();
        //------------------------------------------------------
        $where = array(
            "cabang_id" => "$cabang_id",
            "rekening" => "$rekening",
        );
        $this->db->where($where);
        $this->db->order_by("transaksi_id", "asc");
        $tmpDetail = $this->db->get($tbl_pembantu)->result();
        showLast_query("kuning");
        foreach ($tmpDetail as $ii => $tmpSpec) {
            unset($tmpSpec->id);
            $arrDatas[] = (array)$tmpSpec;
        }

        $this->db->trans_start();

        foreach ($arrDatas as $ii => $spec) {

            $this->db->insert($tbl_pembantu_target, $spec);
            showLast_query("hijau");
//            break;
        }


        $end = microtime(true);
        $selesai = $end - $start;

        mati_disini(__LINE__ . " BERHASIL SETOP... [$selesai]");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>SELESAI...</h3>");

    }

    public function rebuildMutasiDetailDebet()
    {
        // __rek_pembantu_antarcabang__piutang_cabang


        $this->db->trans_begin();


        $cabang_id = $cabangID = "1";
        $gudang_id = $gudangID = "-10";
        $periode = "forever";
        $tahun = "2023";
        $rekening = "1010030030";
        $extern_id = "1908";
        $tbl_pembantu_target = "__rek_pembantu_produk__1010030030";
        $where = array(
            "cabang_id" => "$cabang_id",
            "gudang_id" => "$gudang_id",
            "rekening" => "$rekening",
            "extern_id" => "$extern_id",
        );
        $this->db->where($where);
        $this->db->group_by("extern_id");
        $this->db->order_by("transaksi_id", "asc");
        $tmpDetail = $this->db->get($tbl_pembantu_target)->result();
        showLast_query("kuning");
        foreach ($tmpDetail as $ii => $tmpSpec) {
            $externIDs[$tmpSpec->extern_id] = $tmpSpec->extern_id;
        }
//        arrPrintHitam($externIDs);
        $var_tbl = "";
        $contensCache = array();
        foreach ($externIDs as $externID) {
            $condites = array(
                "cabang_id" => $cabangID,
                "gudang_id" => $gudangID,
                "extern_id" => $externID,
                "rekening" => "$rekening",
            );
            $this->db->where($condites);
            $this->db->order_by("transaksi_id", "asc");
            $contens_0 = $tmpDetail = $this->db->get($tbl_pembantu_target)->result();
//            showLast_query("lime");
//            mati_disini(__LINE__ . " === " . count($contens_0));
            $contens = array();
            if (sizeof($contens_0) > 0) {
                foreach ($contens_0 as $ix => $item) {
                    if ($ix == 0) {
                        $firs_debet_awal = $item->debet * 1;
                        $firs_qtydebet_awal = $item->qty_debet * 1;
                    }
                    /* ----------------------------
                     * menyusun data untuk koreksi
                     * ----------------------------*/
                    if ($ix > 0) {
                        $data_yg_bener["db_awal"] = $firs_debet_awal;
                        $db_akhir = $firs_debet_awal + $item->debet - $item->kredit;
                        $data_yg_bener["db_akhir"] = $db_akhir;
                        $contens[$ix] = (array)$item + $data_yg_bener;
                        $firs_debet_awal = $db_akhir;
                        $data_qtyyg_bener["db_qtyawal"] = $firs_qtydebet_awal;
                        $db_qtyakhir = $firs_qtydebet_awal + $item->qty_debet - $item->qty_kredit;
                        $data_qtyyg_bener["db_qtyakhir"] = $db_qtyakhir;
                        $contens[$ix] = $contens[$ix] + $data_qtyyg_bener;
                        $firs_qtydebet_awal = $db_qtyakhir;
                        $contensCache[$item->extern_id] = array(
                            "qty_debet" => $db_qtyakhir,
                            "debet" => $db_akhir,
                        );
                    }
                }
            }

            $koloms = array(
                "id" => array(
                    "label" => "id",
                ),
                "jenis" => array(
                    "label" => "jenis",
                ),
                "transaksi_no" => array(
                    "label" => "transaksi_no",
                ),
                "dtime" => array(
                    "label" => "dtime",
                ),
                "extern_id" => array(
                    "label" => "id biaya",
                ),
                "extern_nama" => array(
                    "label" => "nama biaya",
                ),

                "debet_awal" => array(
                    "label" => "debet_awal",
                    "format" => "formatField",
                    "attr" => "align='right'",
                    "attr_head" => "width='100px'",
                ),
                "db_awal" => array(
                    "label" => "db_awal",
                    "format" => "formatField",
                    "attr" => "align='right' style='color:red;'",
                    "attr_head" => "width='100px'",
                ),
                "debet" => array(
                    "label" => "debet",
                    "format" => "formatField",
                    "attr" => "align='right'",
                    "attr_head" => "width='100px'",
                ),
                "kredit" => array(
                    "label" => "kredit",
                    "format" => "formatField",
                    "attr" => "align='right'",
                    "attr_head" => "width='100px'",
                ),
                "debet_akhir" => array(
                    "label" => "debet_akhir",
                    "format" => "formatField",
                    "attr" => "align='right'",
                    "attr_head" => "width='100px'",
                ),
                "db_akhir" => array(
                    "label" => "db_akhir",
                    "format" => "formatField",
                    "attr" => "align='right' style='color:red;'",
                    "attr_head" => "width='100px'",
                ),

                "qty_debet_awal" => array(
                    "label" => "qty debet_awal",
                    "format" => "formatField",
                    "attr" => "align='right'",
                    "attr_head" => "width='100px'",
                ),
                "db_qtyawal" => array(
                    "label" => "qty db_awal",
                    "format" => "formatField",
                    "attr" => "align='right' style='color:red;'",
                    "attr_head" => "width='100px'",
                ),
                "qty_debet" => array(
                    "label" => "qty debet",
                    "format" => "formatField",
                    "attr" => "align='right'",
                    "attr_head" => "width='100px'",
                ),
                "qty_kredit" => array(
                    "label" => "qty kredit",
                    "format" => "formatField",
                    "attr" => "align='right'",
                    "attr_head" => "width='100px'",
                ),
                "qty_debet_akhir" => array(
                    "label" => "qty debet_akhir",
                    "format" => "formatField",
                    "attr" => "align='right'",
                    "attr_head" => "width='100px'",
                ),
                "db_qtyakhir" => array(
                    "label" => "qty db_akhir",
                    "format" => "formatField",
                    "attr" => "align='right' style='color:red;'",
                    "attr_head" => "width='100px'",
                ),
            );

            //region header table
            $var_head = "";
            $var_head .= "<tr class='bg-info'>";

            $kolom_params = reset($koloms);
            $attr_head = isset($kolom_params['attr_head']) ? $kolom_params['attr_head'] : "";
            $var_head .= "<th $attr_head width='20px;'>no</th>";

            foreach ($koloms as $kolom => $attrs) {
                $label = $attrs['label'];
                $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : "";

                $var_head .= "<th $attr>";
                $var_head .= $label;
                $var_head .= "</th>";
            }
            $var_head .= "</tr>";
            //endregion

            //region body table
            $var_body = "";
            if (sizeof($contens) > 0) {
                $no = 0;
                $totals = array();
                foreach ($contens as $var_body_id => $lsr_nama) {
                    $dmain = (object)$lsr_nama;
                    $no++;
                    $var_body .= "<tr>";
                    $var_body .= "<td class='text-right'>$no</td>";
                    foreach ($koloms as $kolom => $attrs) {
                        $nilai = isset($dmain->$kolom) ? $dmain->$kolom : 0;

                        $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                        $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                        $nilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $nilai) : $nilai;

                        $linking = isset($attrs['link']) ? $attrs['link'] . "/$var_body_id" : "";
                        $linkDetile = base_url() . $linking . "";
                        // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                        $nilai_link = isset($attrs['link']) ? "<a href='JavaScript:Void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;

                        $var_body .= "<td $attr>";
                        $var_body .= $nilai_link;
                        $var_body .= "</td>";

                        if (isset($attrs['summary'])) {
                            if (!isset($totals[$kolom])) {
                                $totals[$kolom] = 0;
                            }
                            $totals[$kolom] += $nilai;
                        }
                    }
                    $var_body .= "</tr>";
                    /* ----------------------------
                     * eksekutor data ke database
                     * ----------------------------*/
                    $updCondite = array(
                        "id" => $dmain->id,
                    );
                    $updData = array(
                        "debet_awal" => $dmain->db_awal,
                        "debet_akhir" => $dmain->db_akhir,
                        "qty_debet_awal" => $dmain->db_qtyawal,
                        "qty_debet_akhir" => $dmain->db_qtyakhir,
                    );
//                    $crd->updateData($updCondite, $updData);
                    $this->db->where('id', $dmain->id);
                    $this->db->update($tbl_pembantu_target, $updData);
                    showLast_query("orange");
                    // --------------------------------------------------
                }
            }
            else {
                $var_body .= "";
            }
            //endregion

            //region footer table
            $var_foot = "";
            $var_foot .= "<tr class='bg-danger'>";
            $var_foot .= "<th></th>";
            foreach ($koloms as $kolom => $attrs) {
                $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
                $fNilai_f = isset($attrs['format']) ? $attrs['format']($kolom, $fNilai) : $fNilai;
                // $label = $attrs['label'];

                $var_foot .= "<th>";
                $var_foot .= $fNilai_f;
                $var_foot .= "</th>";
            }
            //endregion

            // ---------------
            $str_tbl_id = "";
            $var_tbl .= "<br>";
            $var_tbl .= "<div class='table-responsive'>";
            $var_tbl .= "<table class='table table-condensed table-striped' $str_tbl_id border='1' rules='all'>";
            $var_tbl .= "<thead>";
            $var_tbl .= $var_head;
            $var_tbl .= "</thead>";

            $var_tbl .= "<tbody>";
            $var_tbl .= $var_body;
            $var_tbl .= "</tbody>";

            // cekBiru(sizeof($totals));
            if (isset($totals) && is_array($totals) && sizeof($totals) > 0) {

                $var_tbl .= "<tfoot>";
                $var_tbl .= $var_foot;
                $var_tbl .= "</tfoot>";
            }

            $var_tbl .= "</table>";
            $var_tbl .= "</div>";

//            break;

        }
        echo $var_tbl;

        //--------------
        $pakai_ini = 0;
        if ($pakai_ini == 1) {
            if (sizeof($contensCache) > 0) {
                foreach ($contensCache as $pid => $pSpec) {
                    $crd->setFilters(array());
                    $where = array(
                        "cabang_id" => $cabangID,
                        "gudang_id" => $gudangID,
                        "periode" => $periode,
                        "extern_id" => $pid,
                    );
                    $data = array(
                        "debet" => $pSpec["debet"],
                        "qty_debet" => $pSpec["qty_debet"],
                    );
                    $crd->setTableName($tbl_cache);
                    $crd->updateData($where, $data);
                    showLast_query("orange");

//                    $fmd->setFilters(array());
//                    $whereff = array(
//                        "cabang_id" => $cabangID,
//                        "gudang_id" => $gudangID,
//                        "produk_id" => $pid,
//                    );
//                    $dataff = array(
//                        "jml" => $pSpec["qty_debet"],
//                        "hpp" => $pSpec["debet"] / $pSpec["qty_debet"],
//                        "jml_nilai" => $pSpec["debet"],
//                    );
//                    $fmd->updateData($whereff, $dataff);
//                    showLast_query("orange");
                }
            }
        }
        //--------------

        mati_disini(__LINE__);
        $this->db->trans_complete() or mati_disini("gagal...");
        cekHitam(__FILE__ . " @" . __LINE__);
    }


    public function pacthPembatalan()
    {

        $this->load->model("MdlTransaksi");

        $jenis = "1477";
        $jenis2 = "9911";

        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='$jenis'");
        $tr->addFilter("trash_4='1'");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trIDs[$trSpec->id] = $trSpec->id;
            }
        }

        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='$jenis2'");
        $tr->addFilter("reference_id in ('" . implode("','", $trIDs) . "')");
        $trTmp = $tr->lookupAll()->result();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $trDatas2[$trSpec->reference_id] = $trSpec;
            }
        }

        $data_new = array();
        foreach ($trIDs as $trid) {
            $data_new[$trid] = array(
                "cancel_dtime" => $trDatas2[$trid]->dtime,
                "cancel_name" => $trDatas2[$trid]->oleh_nama,
                "cancel_id" => $trDatas2[$trid]->oleh_id,
                "cancel_transaksi_jenis" => $trDatas2[$trid]->jenis,
                "cancel_transaksi_id" => $trDatas2[$trid]->id,
                "cancel_transaksi_nomer" => $trDatas2[$trid]->nomer,
            );
        }

        $this->db->trans_start();
//        arrPrintHitam($data_new);

        foreach ($data_new as $trid => $data) {
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "id" => $trid,
            );
            $tr->updateData($where, $data);
            showLast_query("orange");
        }


//        mati_disini(__LINE__ . " BERHASIL SETOP... [----]");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>SELESAI...</h3>");
        mati_disini("<h3>SELESAI...</h3>");

    }


    //----------------------------------------------
    public function cekStartProject()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComJurnal");

        $jenis = "588st";
        $jenis2 = "9912";

        //region start project
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='$jenis'");
        $this->db->order_by("id", "asc");
        $trTmp = $tr->lookupAll()->result();
        $arrTrDatas = array();
        $arrTrIDs = array();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $insertID = $trID = $trSpec->id;
                $arrTrDatas[$insertID] = (array)$trSpec;
                $arrTrIDs[$insertID] = $insertID;
            }

            $cj = New ComJurnal();
            $cj->setFilters(array());
            $cj->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $cjTmp = $cj->lookupAll()->result();
            $arrJurnalCek = array();
            foreach ($cjTmp as $cjSpec) {
                $arrJurnalCek[$cjSpec->transaksi_id] = 1;
            }

        }
        //endregion

        //region pembatalan start project
        $tr = New MdlTransaksi();
        $tr->addFilter("jenis='$jenis2'");
        $tr->addFilter("reference_jenis='$jenis'");
        $this->db->order_by("id", "asc");
        $trTmp = $tr->lookupAll()->result();
        $arrTrDatasBatal = array();
        $arrTrIDs = array();
        if (sizeof($trTmp) > 0) {
            foreach ($trTmp as $trSpec) {
                $insertID = $trID = $trSpec->id;
                $arrTrDatasBatal[$insertID] = (array)$trSpec;
                $arrTrIDs[$insertID] = $insertID;
            }

            $cj = New ComJurnal();
            $cj->setFilters(array());
            $cj->addFilter("transaksi_id in ('" . implode("','", $arrTrIDs) . "')");
            $cjTmp = $cj->lookupAll()->result();
            $arrJurnalCekBatal = array();
            foreach ($cjTmp as $cjSpec) {
                $arrJurnalCekBatal[$cjSpec->transaksi_id] = 1;
            }

        }
        //endregion

        $str = "<h3>Start Project</h3>";
        $str .= "<table style='border:1px solid black;' rules='all' width='50%'>";
        $str .= "<tr>";
        $str .= "<td>no.</td>";
        $str .= "<td>dtime</td>";
        $str .= "<td>CID</td>";
        $str .= "<td>CNAME</td>";
        $str .= "<td>PROID</td>";
        $str .= "<td>PRONAME</td>";
        $str .= "<td>trID</td>";
        $str .= "<td>Nomer</td>";
        $str .= "<td>Jurnal Y/N</td>";
        $str .= "</tr>";
        $no = 0;
        foreach ($arrTrDatas as $spec) {
            $no++;
            $jurnal_cek = isset($arrJurnalCek[$spec["id"]]) ? $arrJurnalCek[$spec["id"]] : 0;
            $str .= "<tr>";
            $str .= "<td>$no</td>";
            $str .= "<td>" . $spec["dtime"] . "</td>";
            $str .= "<td>" . $spec["customers_id"] . "</td>";
            $str .= "<td>" . $spec["customers_nama"] . "</td>";
            $str .= "<td>" . $spec["project_id"] . "</td>";
            $str .= "<td>" . $spec["project_nama"] . "</td>";
            $str .= "<td>" . $spec["id"] . "</td>";
            $str .= "<td>" . $spec["nomer"] . "</td>";
            $str .= "<td style='text-align:center;'>$jurnal_cek</td>";
            $str .= "</tr>";
        }
        $str .= "</table>";
        $str .= "<br><br>";


        $str .= "<h3>Pembatalan Start Project</h3>";
        $str .= "<table style='border:1px solid black;' rules='all' width='50%'>";
        $str .= "<tr>";
        $str .= "<td>no.</td>";
        $str .= "<td>dtime</td>";
        $str .= "<td>CID</td>";
        $str .= "<td>CNAME</td>";
        $str .= "<td>trID</td>";
        $str .= "<td>Nomer</td>";
        $str .= "<td>referenceID</td>";
        $str .= "<td>referenceNomer</td>";
        $str .= "<td>Jurnal Y/N</td>";
        $str .= "</tr>";
        $no = 0;
        foreach ($arrTrDatasBatal as $spec) {
            $no++;
            $jurnal_cek = isset($arrJurnalCekBatal[$spec["id"]]) ? $arrJurnalCekBatal[$spec["id"]] : 0;
            $str .= "<tr>";
            $str .= "<td>$no</td>";
            $str .= "<td>" . $spec["dtime"] . "</td>";
            $str .= "<td>" . $spec["customers_id"] . "</td>";
            $str .= "<td>" . $spec["customers_nama"] . "</td>";
            $str .= "<td>" . $spec["id"] . "</td>";
            $str .= "<td>" . $spec["nomer"] . "</td>";
            $str .= "<td>" . $spec["reference_id"] . "</td>";
            $str .= "<td>" . $spec["reference_nomer"] . "</td>";
            $str .= "<td style='text-align:center;'>$jurnal_cek</td>";
            $str .= "</tr>";
        }
        $str .= "</table>";
        $str .= "<br><br>";

        echo $str;


    }

    public function generateProject()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComRekeningPembantuCustomer");
//        $this->load->model("Coms/ComRekeningPembantuCustomerProject");
        $this->load->model("Coms/ComRekening");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Mdls/MdlProdukProject");
        $this->load->helper("he_mass_table");
        $this->load->model("CustomCounter");

        $jenis = "588st";


//        $buildTablesMaster = $components["master"];
//        if (sizeof($buildTablesMaster) > 0) {
//            $bCtr = 0;
//            foreach ($buildTablesMaster as $buildTablesMaster_specs) {
//                $bCtr++;
//                $mdlName = $buildTablesMaster_specs['comName'];
//                if (substr($mdlName, 0, 1) == "{") {
//                    $mdlName = trim($mdlName, "{");
//                    $mdlName = trim($mdlName, "}");
//                    $mdlName = str_replace($mdlName, $_SESSION[$cCode]['main'][$mdlName], $mdlName);
//                }
//                else {
//                    cekkuning("TIDAK mengandung kurawal");
//                }
//                $mdlName = "Com" . $mdlName;
//                $this->load->model("Coms/" . $mdlName);
//                $m = new $mdlName();
//                if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
//                    foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
//                        if (substr($key, 0, 1) == "{") {
//                            $oldParam = $buildTablesMaster_specs['loop'][$key];
//                            unset($buildTablesMaster_specs['loop'][$key]);
//                            $key = trim($key, "{");
//                            $key = trim($key, "}");
//                            $key = str_replace($key, $_SESSION[$cCode]['main'][$key], $key);
//                            $buildTablesMaster_specs['loop'][$key] = $oldParam;
//                        }
//                    }
//                }
//                if (method_exists($m, "getTableNameMaster")) {
//                    if (sizeof($m->getTableNameMaster())) {
//                        $m->buildTables($buildTablesMaster_specs);
//                    }
//                }
//            }
//        }

        $this->db->trans_start();


        $tr = New MdlTransaksi();
        $tr->addFilter("id='221587'");
//        $tr->addFilter("jenis='$jenis'");
        $tr->addFilter("status_grn='0'");
        $this->db->limit(1);
        $this->db->order_by("id", "asc");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        cekBiru(sizeof($trTmp));
        if (sizeof($trTmp) > 0) {
            $insertID = $trID = $trTmp[0]->id;
            $dtime = $trTmp[0]->dtime;
            $fulldate = $trTmp[0]->fulldate;
            $jenis_master = $trTmp[0]->jenis_master;
            $insertNum = $nomer = $trTmp[0]->nomer;
            $this->jenisTr = $jenis_master;


            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id='$trID'");
            $trReg = $tr->lookupDataRegistries()->result();
            $arrRegDatas = array();
            foreach ($trReg as $regSpec) {
                foreach ($regSpec as $key => $val) {
                    if ($key != "transaksi_id") {
                        if ($val == NULL) {
                            $val = blobEncode(array());
                        }
                        $arrRegDatas[$regSpec->transaksi_id][$key] = blobDecode($val);
                    }
                }
            }
            //---------------------------------
            $trp = New MdlProdukProject();
            $trp->setFilters(array());
            $trp->addFilter("project_start_id='$trID'");
            $trpTmp = $trp->lookupAll()->result();
//            $projectID = $trpTmp[0]->id;
//            $arrRegDatas[$trID]["main"]["projectID"] = $projectID;
            //---------------------------------
//            cekHitam("[$trID]");
//            arrPrintWebs($arrRegDatas[$trID]["main"]);
            //---------------------------------
            cekHere("[$jenis_master]");
            switch ($jenis_master) {
                case "588":
                    if (!isset($arrRegDatas[$trID]["main"]["harga_non_ppn"])) {
                        $arrRegDatas[$trID]["main"]["harga_non_ppn"] = $arrRegDatas[$trID]["main"]["projectHarga"];
                    }
                    $arrRegDatas[$trID]["main"]["dpp_ppn"] = $arrRegDatas[$trID]["main"]["harga_non_ppn"];
                    $arrRegDatas[$trID]["main"]["new_grand_ppn"] = (11 / 100) * $arrRegDatas[$trID]["main"]["harga_non_ppn"];
                    $arrRegDatas[$trID]["main"]["grandTotal"] = 1.11 * $arrRegDatas[$trID]["main"]["harga_non_ppn"];

                    $components = array(
                        "master" => array(
                            // JURNAL CABANG
                            array(
                                "comName" => "Jurnal",
                                "loop" => array(
                                    "1010070030" => "grandTotal", // piutang usaha kontijensi
                                    "4030" => "grandTotal", // penjualan kontijensi
                                ),
                                "static" => array(
                                    "cabang_id" => "placeID",
                                    "jenis" => "jenisTr",
                                    "transaksi_no" => "nomer",
                                ),
                                "srcGateName" => "main",
                                "srcRawGateName" => "main",
                            ),
                            array(
                                "comName" => "Rekening",
                                "loop" => array(
                                    "1010070030" => "grandTotal", // piutang usaha kontijensi
                                    "4030" => "grandTotal", // penjualan kontijensi
                                ),
                                "static" => array(
                                    "cabang_id" => "placeID",
                                    "jenis" => "jenisTr",
                                    "transaksi_no" => "nomer",
                                ),
                                "srcGateName" => "main",
                                "srcRawGateName" => "main",
                            ),
                            array(
                                "comName" => "RekeningPembantuCustomer",
                                "loop" => array(
                                    "1010070030" => "grandTotal",// piutang dagang kontijensi
                                ),
                                "static" => array(
                                    "cabang_id" => "placeID",
                                    "extern_id" => "pihakID",
                                    "extern_nama" => "pihakName",
                                    "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                                ),
                                "srcGateName" => "main",
                                "srcRawGateName" => "main",
                            ),
                            array(
                                "comName" => "RekeningPembantuCustomerProject",
                                "loop" => array(
                                    "1010070030" => "grandTotal",// piutang dagang kontijensi
                                ),
                                "static" => array(
                                    "cabang_id" => "placeID",
                                    "extern_id" => "pihakID",
                                    "extern_nama" => "pihakName",
                                    "extern2_id" => "projectID",//project
                                    "extern2_nama" => "projectName",//project
                                    "extern3_id" => ".0",//kontrak
                                    "extern3_nama" => "note",//kontrak
                                    "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                                ),
                                "srcGateName" => "main",
                                "srcRawGateName" => "main",
                            ),
                            array(
                                "comName" => "RekeningPembantuPenjualan",
                                "loop" => array(
                                    "4030" => "grandTotal",// piutang dagang kontijensi
                                ),
                                "static" => array(
                                    "cabang_id" => "placeID",
                                    "extern_id" => ".4030030",
                                    "extern_nama" => ".penjualan kontijensi project",
                                    "extern2_id" => ".0",
                                    "extern2_nama" => "",
                                    "extern4_id" => "pihakID",
                                    "extern4_nama" => "pihakName",
                                    "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                                ),
                                "srcGateName" => "main",
                                "srcRawGateName" => "main",
                            ),
                            array(
                                "comName" => "RekeningPembantuPenjualanProject",
                                "loop" => array(
                                    "4030" => "grandTotal",// piutang dagang kontijensi
                                ),
                                "static" => array(
                                    "cabang_id" => "placeID",
                                    "extern_id" => "projectID",//project
                                    "extern_nama" => "projectName",//project
                                    "extern2_id" => ".4030030",
                                    "extern2_nama" => ".penjualan kontijensi project",
                                    "extern3_id" => ".0",//kontrak
                                    "extern3_nama" => "note",//kontrak
                                    "extern4_id" => "pihakID",
                                    "extern4_nama" => "pihakName",
                                    "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                                ),
                                "srcGateName" => "main",
                                "srcRawGateName" => "main",
                            ),
                        ),
                        "detail" => array(),
                    );

                    break;
                case "9912":
//                    cekOrange("[$projectID]");
//                    if(!isset($arrRegDatas[$trID]["main"]["projectID"]) || ($arrRegDatas[$trID]["main"]["projectID"] == 0)){
//                        $arrRegDatas[$trID]["main"]["projectID"] = 20;
//                    }
                    if (!isset($arrRegDatas[$trID]["main"]["harga_non_ppn"])) {
                        $arrRegDatas[$trID]["main"]["harga_non_ppn"] = $arrRegDatas[$trID]["main"]["projectHarga"];
                    }
                    $arrRegDatas[$trID]["main"]["dpp_ppn"] = $arrRegDatas[$trID]["main"]["harga_non_ppn"];
                    $arrRegDatas[$trID]["main"]["new_grand_ppn"] = (11 / 100) * $arrRegDatas[$trID]["main"]["harga_non_ppn"];
                    $arrRegDatas[$trID]["main"]["grandTotal"] = 1.11 * $arrRegDatas[$trID]["main"]["harga_non_ppn"];
                    $pakai_ini = 0;
                    if ($pakai_ini == 1) {
                        $components = array(
                            "master" => isset($arrRegDatas[$trID]["revert"]["jurnal"]["master"]) ? $arrRegDatas[$trID]["revert"]["jurnal"]["master"] : array(),
                            "detail" => isset($arrRegDatas[$trID]["revert"]["jurnal"]["detail"]) ? $arrRegDatas[$trID]["revert"]["jurnal"]["detail"] : array(),
                        );
                    }
                    else {
                        $components = array(
                            "master" => array(
                                // JURNAL CABANG
                                array(
                                    "comName" => "Jurnal",
                                    "loop" => array(
                                        "1010070030" => "-grandTotal", // piutang usaha kontijensi
                                        "4030" => "-grandTotal", // penjualan kontijensi
                                    ),
                                    "static" => array(
                                        "cabang_id" => "placeID",
                                        "jenis" => "jenisTr",
                                        "transaksi_no" => "nomer",
                                    ),
                                    "srcGateName" => "main",
                                    "srcRawGateName" => "main",
                                ),
                                array(
                                    "comName" => "Rekening",
                                    "loop" => array(
                                        "1010070030" => "-grandTotal", // piutang usaha kontijensi
                                        "4030" => "-grandTotal", // penjualan kontijensi
                                    ),
                                    "static" => array(
                                        "cabang_id" => "placeID",
                                        "jenis" => "jenisTr",
                                        "transaksi_no" => "nomer",
                                    ),
                                    "srcGateName" => "main",
                                    "srcRawGateName" => "main",
                                ),
                                array(
                                    "comName" => "RekeningPembantuCustomer",
                                    "loop" => array(
                                        "1010070030" => "-grandTotal",// piutang dagang kontijensi
                                    ),
                                    "static" => array(
                                        "cabang_id" => "placeID",
                                        "extern_id" => "pihakID",
                                        "extern_nama" => "pihakName",
                                        "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                                    ),
                                    "srcGateName" => "main",
                                    "srcRawGateName" => "main",
                                ),
                                array(
                                    "comName" => "RekeningPembantuCustomerProject",
                                    "loop" => array(
                                        "1010070030" => "-grandTotal",// piutang dagang kontijensi
                                    ),
                                    "static" => array(
                                        "cabang_id" => "placeID",
                                        "extern_id" => "pihakID",
                                        "extern_nama" => "pihakName",
                                        "extern2_id" => "projectID",//project
                                        "extern2_nama" => "projectName",//project
                                        "extern3_id" => ".0",//kontrak
                                        "extern3_nama" => "note",//kontrak
                                        "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                                    ),
                                    "srcGateName" => "main",
                                    "srcRawGateName" => "main",
                                ),
                                array(
                                    "comName" => "RekeningPembantuPenjualan",
                                    "loop" => array(
                                        "4030" => "-grandTotal",// piutang dagang kontijensi
                                    ),
                                    "static" => array(
                                        "cabang_id" => "placeID",
                                        "extern_id" => ".4030030",
                                        "extern_nama" => ".penjualan kontijensi project",
                                        "extern2_id" => ".0",
                                        "extern2_nama" => "",
                                        "extern4_id" => "pihakID",
                                        "extern4_nama" => "pihakName",
                                        "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                                    ),
                                    "srcGateName" => "main",
                                    "srcRawGateName" => "main",
                                ),
                                array(
                                    "comName" => "RekeningPembantuPenjualanProject",
                                    "loop" => array(
                                        "4030" => "-grandTotal",// piutang dagang kontijensi
                                    ),
                                    "static" => array(
                                        "cabang_id" => "placeID",
                                        "extern_id" => "projectID",//project
                                        "extern_nama" => "projectName",//project
                                        "extern2_id" => ".4030030",
                                        "extern2_nama" => ".penjualan kontijensi project",
                                        "extern3_id" => ".0",//kontrak
                                        "extern3_nama" => "note",//kontrak
                                        "extern4_id" => "pihakID",
                                        "extern4_nama" => "pihakName",
                                        "jenis" => "jenisTr",
//                        "transaksi_no" => "nomer",
                                    ),
                                    "srcGateName" => "main",
                                    "srcRawGateName" => "main",
                                ),
                            ),
                            "detail" => array(),
                        );
                    }

                    break;
                default:
                    mati_disini("SETOP.... " . __LINE__);
                    break;
            }
//            arrPrintPink($arrRegDatas[$trID]["main"]);
//            mati_disini("SETOP.... " . __LINE__);
            //---------------------------------
            $cCode = "_TR_" . $jenis_master;
            $this->cCodeData[$cCode] = $arrRegDatas[$trID];

            // COMPONENT
            $pakai_ini = 1;
            if ($pakai_ini == 1) {

                //region processing sub-components, if in single step geser ke CLI
                $componentGate['detail'] = array();
                $componentConfig['detail'] = array();
                $iterator = $components["detail"];
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $tmpOutParams[$cCtr] = array();
                        $gg = 0;
                        $srcGateName = $tComSpec['srcGateName'];
                        if ($componentsDetailLoop == true) {
                            foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $comName = $tComSpec['comName'];
                                if (substr($comName, 0, 1) == "{") {
                                    $comName = trim($comName, "{");
                                    $comName = trim($comName, "}");
                                    $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                                }

                                $mdlName = "$comsPrefix" . ucfirst($comName);
                                if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                    $filterNeeded = true;
                                }
                                else {
                                    $filterNeeded = false;
                                }
                                cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                $subParams = array();

                                if (isset($tComSpec['loop'])) {
                                    foreach ($tComSpec['loop'] as $key => $value) {
                                        if (substr($key, 0, 1) == "{") {
                                            $key = trim($key, "{");
                                            $key = trim($key, "}");
                                            $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                        }

                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['loop'][$key] = $realValue;

                                        if ($filterNeeded) {
                                            if ($subParams['loop'][$key] == 0) {
                                                unset($subParams['loop'][$key]);
                                            }
                                        }
                                    }
                                }
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {
                                        $subParams['static']["transaksi_id"] = $insertID;
                                    }
                                    if (!isset($subParams['static']["transaksi_no"])) {
                                        $subParams['static']["transaksi_no"] = $insertNum;
                                    }

                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = isset($this->cCodeData[$cCode][$srcGateName][$id]["keterangan"]) ? $this->cCodeData[$cCode][$srcGateName][$id]["keterangan"] : "";
                                    if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                        $subParams['static']['reverted_target'] = $revertedTarget;
                                    }
                                }

                                if (sizeof($subParams) > 0) {
//                                cekhitam("subparam ada isinya");
                                    if ($filterNeeded) {
                                        if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        $tmpOutParams[$cCtr][] = $subParams;
                                    }
                                }
                                else {
                                    cekhitam("subparam TIDAK ada isinya");
                                }
                            }
                        }
                        else {
                            foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                                if ($cCtr == $id) {
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $comName = $tComSpec['comName'];
                                    if (substr($comName, 0, 1) == "{") {
                                        $comName = trim($comName, "{");
                                        $comName = trim($comName, "}");

                                        $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                                    }

                                    $mdlName = "$comsPrefix" . ucfirst($comName);
                                    if (in_array($mdlName, $compValidators)) {//perlu validasi filter
                                        $filterNeeded = true;
                                    }
                                    else {
                                        $filterNeeded = false;
                                    }
                                    cekHere("sub-component: [$comsLocation] $comName, initializing values <br>");

                                    $subParams = array();

                                    if (isset($tComSpec['loop'])) {
                                        foreach ($tComSpec['loop'] as $key => $value) {

                                            if (substr($key, 0, 1) == "{") {
                                                $key = trim($key, "{");
                                                $key = trim($key, "}");

                                                $key = str_replace($key, $this->cCodeData[$cCode][$srcGateName][$id][$key], $key);
                                            }

                                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                            $subParams['loop'][$key] = $realValue;

                                            if ($filterNeeded) {
                                                if ($subParams['loop'][$key] == 0) {
                                                    unset($subParams['loop'][$key]);
                                                }
                                            }
                                        }
                                    }
                                    if (isset($tComSpec['static'])) {
                                        foreach ($tComSpec['static'] as $key => $value) {
                                            $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$id], $this->cCodeData[$cCode][$srcGateName][$id], 0);
                                            $subParams['static'][$key] = $realValue;

                                        }
                                        if (!isset($subParams['static']["transaksi_id"])) {
                                            $subParams['static']["transaksi_id"] = $insertID;
                                        }
                                        if (!isset($subParams['static']["transaksi_no"])) {
                                            $subParams['static']["transaksi_no"] = $insertNum;
                                        }

                                        $subParams['static']["fulldate"] = date("Y-m-d");
                                        $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                        $subParams['static']["keterangan"] = "";
                                        if (isset($revertedTarget) && (strlen($revertedTarget) > 1)) {
                                            $subParams['static']['reverted_target'] = $revertedTarget;
                                        }
                                    }

                                    if (sizeof($subParams) > 0) {

                                        if ($filterNeeded) {
                                            if (isset($subParams['loop']) && sizeof($subParams['loop']) > 0) {
                                                $tmpOutParams[$cCtr][] = $subParams;
                                            }
                                        }
                                        else {
                                            $tmpOutParams[$cCtr][] = $subParams;
                                        }
                                    }
                                    else {
                                        cekhitam("subparam TIDAK ada isinya");
                                    }
                                }
                            }
                        }

                        $componentGate['detail'][$cCtr] = $subParams;
                    }

                    foreach ($iterator as $cCtr => $tComSpec) {
                        $srcGateName = $tComSpec['srcGateName'];
                        foreach ($this->cCodeData[$cCode][$srcGateName] as $id => $dSpec) {
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $comName = $tComSpec['comName'];
                            if (substr($comName, 0, 1) == "{") {
                                $comName = trim($comName, "{");
                                $comName = trim($comName, "}");
                                $comName = str_replace($comName, $this->cCodeData[$cCode][$srcGateName][$id][$comName], $comName);
                            }
                        }
                        cekHere("sub component: [$comsLocation] $comName, sending values " . __LINE__ . "<br>");

                        $mdlName = "$comsPrefix" . ucfirst($comName);
                        $this->load->model("$comsLocation/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        // matiHEre($tobeExecuted);
                        if ($tobeExecuted) {
                            //----- kiriman gerbang
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                            }
                            if (method_exists($m, "setDetail")) {
                                $m->setDetail($this->cCodeData[$cCode][$srcGateName]);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang
                            $m->pair($tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            cekBiru($this->db->last_query());
                        }
                        else {
                            cekMerah("$comName tidak eksekusi");
                        }

                    }
                }
                else {
                    cekKuning("subcomponents is not set");
                }
                //endregion

                //region processing main components, if in single step
                $componentGate['master'] = array();
                $componentConfig['master'] = array();
                $iterator = $components["master"];
                if (sizeof($iterator) > 0) {
                    $componentConfig['master'] = $iterator;
                    $cCtr = 0;
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $cCtr++;
                        $comName = $tComSpec['comName'];
                        if (substr($comName, 0, 1) == "{") {
                            $comName = trim($comName, "{");
                            $comName = trim($comName, "}");
                            $comName = str_replace($comName, $this->cCodeData[$cCode]["main"][$comName], $comName);
                        }
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        cekHere("component # $cCtr: $comName<br>");
                        // arrPrint($this->cCodeData[$cCode][$srcGateName]);
                        // matiHEre(__LINE__);
                        $dSpec = $this->cCodeData[$cCode][$srcGateName];
                        $tmpOutParams = array();
                        if (isset($tComSpec['loop'])) {
                            foreach ($tComSpec['loop'] as $key => $value) {
                                if (substr($key, 0, 1) == "{") {
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $this->cCodeData[$cCode]["main"][$key], $key);
                                }
                                $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                                $tmpOutParams['loop'][$key] = $realValue;
                            }
                        }
                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName], $this->cCodeData[$cCode][$srcGateName], 0);
                                $tmpOutParams['static'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_id"])) {
                                $tmpOutParams['static']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static']["transaksi_no"])) {
                                $tmpOutParams['static']["transaksi_no"] = $insertNum;
                            }
                            $tmpOutParams['static']["urut"] = $cCtr;
                            $tmpOutParams['static']["fulldate"] = $fulldate;
                            $tmpOutParams['static']["dtime"] = $dtime;
                            $tmpOutParams['static']["keterangan"] = isset($this->cCodeData[$cCode][$srcGateName]["keterangan"]) ? $this->cCodeData[$cCode][$srcGateName]["keterangan"] : "";
                        }
                        if (isset($tComSpec['static2'])) {
                            foreach ($tComSpec['static2'] as $key => $value) {
                                $realValue = makeValue($value, $this->cCodeData[$cCode][$srcGateName][$cCtr], $this->cCodeData[$cCode][$srcGateName][$cCtr], 0);
                                $tmpOutParams['static2'][$key] = $realValue;
                            }
                            if (!isset($tmpOutParams['static2']["transaksi_id"])) {
                                $tmpOutParams['static2']["transaksi_id"] = $insertID;
                            }
                            if (!isset($tmpOutParams['static2']["transaksi_no"])) {
                                $tmpOutParams['static2']["transaksi_no"] = $insertNum;
                            }
                            $tmpOutParams['static2']["fulldate"] = $fulldate;
                            $tmpOutParams['static2']["dtime"] = $dtime;
                            $tmpOutParams['static2']["keterangan"] = isset($this->cCodeData[$cCode][$srcGateName]["keterangan"]) ? $this->cCodeData[$cCode][$srcGateName]["keterangan"] : "";
                        }
                        $mdlName = "Com" . ucfirst($comName);
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        //===filter value nol, jika harus difilter
                        $tobeExecuted = true;
                        if (in_array($mdlName, $compValidators)) {
                            $loopParams = isset($tmpOutParams['loop']) ? $tmpOutParams['loop'] : array();
                            if (sizeof($loopParams) > 0) {
                                foreach ($loopParams as $key => $val) {
                                    cekmerah("$comName : $key = $val ");
                                    if ($val == 0) {
                                        unset($tmpOutParams['loop'][$key]);
                                    }
                                }
                            }
                            if (sizeof($tmpOutParams['loop']) < 1) {
                                $tobeExecuted = false;
                            }
                        }
                        if ($tobeExecuted) {
                            //----- kiriman gerbang untuk counter mutasi rekening
                            if (method_exists($m, "setTableInMaster")) {
                                $m->setTableInMaster($this->cCodeData[$cCode]["tableIn_master"]);
                            }
                            if (method_exists($m, "setMain")) {
                                $m->setMain($this->cCodeData[$cCode]["main"]);
                            }
                            if (method_exists($m, "setJenisTr")) {
                                $m->setJenisTr($this->jenisTr);
                            }
                            //----- kiriman gerbang untuk counter mutasi rekening
                            cekHitam("HAHAHA");
//                            arrPrintCyan($tmpOutParams);
                            $m->pair($tmpOutParams) or die("Tidak berhasil memasang  values pada komponen: $comName");
                            $m->exec() or die("Gagal saat berusaha  exec values pada komponen: $comName");
                        }
                        $componentGate['master'][$cCtr] = $tmpOutParams;
                    }
                }
                else {
                    cekKuning("components is not set");
                }
                //endregion
            }


            //---------------------------------
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $where = array(
                "id" => $trID,
            );
            $data = array(
                "status_grn" => 1,
            );
            $tr->updateData($where, $data);
            showLast_query("orange");
        }
        else {
            cekMerah("HABIS");
        }

        mati_disini(__LINE__ . " BERHASIL SETOP...");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekHijau("<h3>SELESAI...</h3>");


    }

    //----------------------------------------------

    /**
     * untuk generate serial yang tidak terbit saat preGRN
     */
    public function generateSerialNumber()
    {
        $this->load->model("MdlTransaksi");
        $this->load->model("Coms/ComProdukSerialNumber");
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $this->load->model("Mdls/MdlProduk2");

        $pd = New MdlProduk2();

        $jenisTr = "466";
        $jenisStepCode = "467r";
        $trID = "543175";


        $tr = New MdlTransaksi();
        $tr->addFilter("id='$trID'");
        $trTmp = $tr->lookupAll()->result();
        showLast_query("biru");
        if (sizeof($trTmp) > 0) {
            $id_current = $trTmp[0]->id;
            $nomer_current = $trTmp[0]->nomer;
            $id_top = $trTmp[0]->id_top;
            $nomer_top = $trTmp[0]->nomer_top;
            $jenis = $trTmp[0]->jenis;
            $dtime = $trTmp[0]->dtime;
            $fulldate = $trTmp[0]->fulldate;
            $cabang_id = $trTmp[0]->cabang_id;
            $gudang_id = $trTmp[0]->gudang_id;
            $ids_his_decode = blobDecode($trTmp[0]->ids_his);
            $count_master = 0;
            $count_master2 = 0;
            foreach ($ids_his_decode as $step => $spec) {
                if ($step == 1) {
                    $counter_decode = blobDecode($spec["counters"]);
                    foreach ($counter_decode["stepCode"] as $kk => $vv) {
                        $count_master = $vv;
                    }
                }
                if ($step == 2) {
                    $counter_decode2 = blobDecode($spec["counters"]);
                    foreach ($counter_decode2["stepCode"] as $kk2 => $vv2) {
                        $count_master2 = $vv2;
                    }
                }
            }

            $main = array();
            $items = array();
            $items2 = array();
            $items3_sum = array();
            $items5_sum = array();
            $tr = New MdlTransaksi();
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id='$trID'");
            $tmpReg = $tr->lookupDataRegistries()->result();
            if (sizeof($tmpReg) > 0) {
                foreach ($tmpReg as $row) {
                    foreach ($row as $key_reg => $val_reg) {
                        switch ($key_reg) {
                            case "main"://
                                $main = $main + unserialize(base64_decode($val_reg));
                                break;
                            case "items"://
                                $items = $items + unserialize(base64_decode($val_reg));
                                break;
                            case "items2"://
                                $items2 = $items2 + unserialize(base64_decode($val_reg));
                                break;
                            case "items3_sum"://
                                $items3_sum = $items3_sum + unserialize(base64_decode($val_reg));
                                break;
                            case "items5_sum"://
                                $items5_sum = $items5_sum + unserialize(base64_decode($val_reg));
                                break;
                        }
                    }
                }
            }

            arrPrint($items3_sum);
            // jika items3_sum tidak ada
            if (sizeof($items3_sum) == 0) {
                $arrTambahan = array(
                    "olehID",
                    "olehName",
                    "sellerID",
                    "sellerName",
                    "pihakID",
                    "pihakName",
                    "supplierID",
                    "supplierName",
                    "placeID",
                    "placeName",
                    "cabangID",
                    "cabangName",
                    "gudangID",
                    "gudangName",
                    "jenisTr",
                    "jenisTrMaster",
                    "nomer",
                    "transaksi_id",
                    "masterID",
                    "referenceID",
                    "referenceNomer",
                    "referenceNumber",
                    "referenceDtime",
                    "referenceFulldate",
                    "referenceCount",
                    "ppv_index__nilai",
                    "ppnFactor",
                    "currentID",
                    "transaksi_count",
                    "transaksi_jenis_count",
                    "referenceID__1",
                    "referenceNumber__1",
                    "referenceNomer__1",
                    "referenceDtime__1",
                    "referenceFulldate__1",
                    "referenceID__2",
                    "referenceNumber__2",
                    "referenceNomer__2",
                    "referenceDtime__2",
                    "referenceFulldate__2",
                );
                if (isset($items2)) {
                    foreach ($items2 as $produk_id => $spec) {
                        foreach ($spec as $produk_sku => $subSpec) {
                            $jml_sku = $items[$produk_id][$produk_sku];
                            $jml_serial = $items[$produk_id]["jml_serial"];
                            $jml_serial = 2;
                            $itemFlip = array_flip($items[$produk_id]);

                            cekHitam("[$gateItems] [$produk_id] [$produk_sku] [$jml_serial] [$jml_sku] HAHAHA: " . $itemFlip[$produk_sku]);

                            if ($itemFlip[$produk_sku] == NULL) {
                                $pNama = $items[$produk_id]["nama"];
                                $msg = "SKU Indoor/Outdoor/Part dari produk $pNama tidak dikenali. Silahkan refresh halaman ini atau hubungi admin. code: " . __LINE__;
                                mati_disini($msg);
                            }

                            $key_data_arr = explode("_", $itemFlip[$produk_sku]);
                            $key = $key_data_arr[0] == "sub" ? "1" : "0";
                            switch ($key_data_arr[$key]) {
                                case "outdoor":
                                    $part_kode = "OT";
                                    break;
                                case "indoor":
                                    $part_kode = "IN";
                                    break;
                                default:
                                    $part_kode = "PART";
                                    break;
                            }
                            if ($jml_serial > 0) {
                                if ($produk_sku == NULL) {
                                    $msg = "SKU Barang/Produk tidak dikenali. Silahkan periksa Data Barang/Produk anda. code: " . __LINE__;
                                    mati_disini($msg);
                                }
                                for ($ii = 1; $ii <= $jml_sku; $ii++) {
                                    $data = array(
                                        "id" => $produk_id,
                                        "nama" => $items[$produk_id]["nama"],
                                        "name" => $items[$produk_id]["name"],
                                        "kategori_id" => $items[$produk_id]["kategori_id"],
                                        "kategori_nama" => $items[$produk_id]["kategori_nama"],
                                        "jml" => 1,
                                        "qty" => 1,
                                        "barcode" => $items[$produk_id]["barcode"],
                                        "kode" => $items[$produk_id]["kode"],
                                        "produk_kode" => $items[$produk_id]["produk_kode"],
                                        "no_part" => $items[$produk_id]["no_part"],
                                        "label" => $items[$produk_id]["label"],
                                        "serial_number" => "",
                                        "produk_serial" => "",
                                        "produk_sku" => trim($produk_sku),
                                        "produk_sku_serial" => "",
                                        "produk_sku_part_id" => "",
                                        "produk_sku_part_nama" => trim($produk_sku),
                                        "produk_sku_part_serial" => "",
                                        "part_keterangan" => $part_kode,
                                        //-----------
                                    );
                                    foreach ($arrTambahan as $tmb){
                                        $data[$tmb] = isset($main[$tmb]) ? $main[$tmb] : "";
                                    }
                                    $items3_sum[] = $data;

                                }
                            }
                        }
                    }
                }
            }

//            arrPrintCyan($items3_sum);
            $items3_sum_encode = blobEncode($items3_sum);
            cekHere($items3_sum_encode);
//            mati_disini("SETOP.... " . __LINE__);

            $this->db->trans_start();

            $data = array();
            $pakai_ini = 1;
            if ($pakai_ini == 1) {
                if (sizeof($items3_sum) > 0) {
                    foreach ($items3_sum as $ii => $spec) {
                        $itemFlip = array_flip($items[$spec["id"]]);
                        $key_data_arr = explode("_", $itemFlip[$spec["produk_sku_part_nama"]]);
                        switch ($key_data_arr[0]) {
                            case "outdoor":
                                $part_kode = "OT";
                                break;
                            case "indoor":
                                $part_kode = "IN";
                                break;
                            default:
                                $part_kode = "PART";
                                break;
                        }
                        $data[$ii] = array(
                            "static" => array(
                                "cabang_id" => $spec["placeID"],
                                "jumlah" => $spec["qty"],
                                "produk_id" => $spec["id"],
                                "produk_nama" => $spec["name"],
                                "produk_serial_number" => $spec["serial_number"],
                                "produk_sku" => $spec["produk_sku"],
                                "produk_sku_serial" => $spec["produk_sku_serial"],
                                "produk_sku_part_id" => $spec["produk_sku_part_id"],
                                "produk_sku_part_nama" => $spec["produk_sku_part_nama"],
                                "produk_sku_part_serial" => $spec["produk_sku_part_serial"],
                                "oleh_id" => $spec["olehID"],
                                "oleh_nama" => $spec["olehName"],
                                "supplier_id" => $spec["supplierID"],
                                "supplier_nama" => $spec["supplierName"],
                                "gudang_id" => $spec["gudangID"],
                                //---------------
                                "transaksi_reference_id" => $id_top,
                                "transaksi_reference_no" => $nomer_top,
                                "transaksi_reference_dtime" => $dtime,
                                "transaksi_reference_fulldate" => $fulldate,
                                "transaksi_reference_count" => $count_master,
                                "transaksi_count" => 1,
                                "transaksi_jenis_count" => $count_master2,
                                "part_keterangan" => $part_kode,
                                "transaksi_id" => $id_current,
                                "transaksi_no" => $nomer_current,
                                "dtime" => $dtime,
                                "fulldate" => $fulldate,
                                "jenis" => $jenis,
                            ),
                        );
                    }

                    $cm = New ComProdukSerialNumber();
                    $cm->pair($data);
                    $cm->exec();

                }
            }
            else {
                cekHere("custom...");
                $free_produk = 1;
                if ($free_produk == 0) {
                    foreach ($items as $pID => $iSpec) {
//                    arrPrintKuning($iSpec);
//                    $iSpec["gudangID"] = $iSpec["gudangProjectID"];

                        $pd->setFilters(array());
                        $pd->addFilter("id='$pID'");
                        $pdTmp = $pd->lookupAll()->result();

                        $part_kode = $produk_kode = $pdTmp[0]->kode;
                        $produk_nama = $pdTmp[0]->nama;
                        $supplier_id = $pdTmp[0]->supplier_id;
                        $supplier_nama = $pdTmp[0]->supplier_nama;
                        $kategori_id = $pdTmp[0]->kategori_id;
                        $kategori_nama = $pdTmp[0]->kategori_nama;
                        $outdoor_sku = $pdTmp[0]->outdoor_sku;
                        $indoor_sku_1 = $pdTmp[0]->indoor_sku_1;
                        $indoor_sku_2 = $pdTmp[0]->indoor_sku_2;
                        $indoor_sku_3 = $pdTmp[0]->indoor_sku_3;
                        $indoor_sku_4 = $pdTmp[0]->indoor_sku_4;
                        $outdoor_sku_label = $pdTmp[0]->outdoor_sku_label;
                        $indoor_sku_1_label = $pdTmp[0]->indoor_sku_1_label;
                        $indoor_sku_2_label = $pdTmp[0]->indoor_sku_2_label;
                        $indoor_sku_3_label = $pdTmp[0]->indoor_sku_3_label;
                        $indoor_sku_4_label = $pdTmp[0]->indoor_sku_4_label;

                        $jml = $iSpec["jml"];
                        for ($i = 1; $i <= $jml; $i++) {
                            if ($outdoor_sku != NULL) {
//                            cekHere("masuk disini");
                                $data[] = array(
                                    "static" => array(
                                        "cabang_id" => $iSpec["placeID"],
                                        "jumlah" => 1,
                                        "produk_id" => $pID,
                                        "produk_nama" => $produk_nama,
                                        "produk_serial_number" => "",
                                        "produk_sku" => $outdoor_sku,
                                        "produk_sku_serial" => "",
                                        "produk_sku_part_id" => "",
                                        "produk_sku_part_nama" => $outdoor_sku,
                                        "produk_sku_part_serial" => "",
                                        "oleh_id" => $iSpec["olehID"],
                                        "oleh_nama" => $iSpec["olehName"],
                                        "supplier_id" => $supplier_id,
                                        "supplier_nama" => $supplier_nama,
                                        "gudang_id" => $iSpec["gudangID"],
                                        "jenis" => $iSpec["jenisTr"],
                                        //---------------
                                        "transaksi_reference_id" => $id_top,
                                        "transaksi_reference_no" => $nomer_top,
                                        "transaksi_reference_dtime" => $dtime,
                                        "transaksi_reference_fulldate" => $fulldate,
                                        "transaksi_reference_count" => 1,
                                        "transaksi_count" => 1,
                                        "transaksi_jenis_count" => 1,
                                        "part_keterangan" => strtoupper($outdoor_sku_label),
                                        "transaksi_id" => $id_current,
                                        "transaksi_no" => $nomer_current,
                                        "dtime" => $dtime,
                                        "fulldate" => $fulldate,
                                    ),
                                );
                            }
                            if ($indoor_sku_1 != NULL) {
                                $data[] = array(
                                    "static" => array(
                                        "cabang_id" => $iSpec["placeID"],
                                        "jumlah" => 1,
                                        "produk_id" => $pID,
                                        "produk_nama" => $produk_nama,
                                        "produk_serial_number" => "",
                                        "produk_sku" => $indoor_sku_1,
                                        "produk_sku_serial" => "",
                                        "produk_sku_part_id" => "",
                                        "produk_sku_part_nama" => $indoor_sku_1,
                                        "produk_sku_part_serial" => "",
                                        "oleh_id" => $iSpec["olehID"],
                                        "oleh_nama" => $iSpec["olehName"],
                                        "supplier_id" => $supplier_id,
                                        "supplier_nama" => $supplier_nama,
                                        "gudang_id" => $iSpec["gudangID"],
                                        "jenis" => $iSpec["jenisTr"],
                                        //---------------
                                        "transaksi_reference_id" => $id_top,
                                        "transaksi_reference_no" => $nomer_top,
                                        "transaksi_reference_dtime" => $dtime,
                                        "transaksi_reference_fulldate" => $fulldate,
                                        "transaksi_reference_count" => 1,
                                        "transaksi_count" => 1,
                                        "transaksi_jenis_count" => 1,
                                        "part_keterangan" => strtoupper($indoor_sku_1_label),
                                        "transaksi_id" => $id_current,
                                        "transaksi_no" => $nomer_current,
                                        "dtime" => $dtime,
                                        "fulldate" => $fulldate,
                                    ),
                                );
                            }

                        }


                    }
                }
                else {
                    foreach ($items5_sum as $pID => $iSpec) {

                        $pd->setFilters(array());
                        $pd->addFilter("id='$pID'");
                        $pd->addFilter("kategori_id='3'");
                        $pdTmp = $pd->lookupAll()->result();
                        showLast_query("biru");
                        if (sizeof($pdTmp) > 0) {

                            $part_kode = $produk_kode = $pdTmp[0]->kode;
                            $produk_nama = $pdTmp[0]->nama;
                            $supplier_id = $pdTmp[0]->supplier_id;
                            $supplier_nama = $pdTmp[0]->supplier_nama;
                            $kategori_id = $pdTmp[0]->kategori_id;
                            $kategori_nama = $pdTmp[0]->kategori_nama;
                            $outdoor_sku = $pdTmp[0]->outdoor_sku;
                            $indoor_sku_1 = $pdTmp[0]->indoor_sku_1;
                            $indoor_sku_2 = $pdTmp[0]->indoor_sku_2;
                            $indoor_sku_3 = $pdTmp[0]->indoor_sku_3;
                            $indoor_sku_4 = $pdTmp[0]->indoor_sku_4;
                            $outdoor_sku_label = $pdTmp[0]->outdoor_sku_label;
                            $indoor_sku_1_label = $pdTmp[0]->indoor_sku_1_label;
                            $indoor_sku_2_label = $pdTmp[0]->indoor_sku_2_label;
                            $indoor_sku_3_label = $pdTmp[0]->indoor_sku_3_label;
                            $indoor_sku_4_label = $pdTmp[0]->indoor_sku_4_label;

                            $outdoor_sku_label = "PART";

                            $jml = $iSpec["jml"];
                            for ($i = 1; $i <= $jml; $i++) {
                                $data[] = array(
                                    "static" => array(
                                        "cabang_id" => $iSpec["placeID"],
                                        "jumlah" => 1,
                                        "produk_id" => $pID,
                                        "produk_nama" => $produk_nama,
                                        "produk_serial_number" => "",
                                        "produk_sku" => $produk_kode,
//                                        "produk_sku" => $outdoor_sku,
                                        "produk_sku_serial" => "",
                                        "produk_sku_part_id" => "",
                                        "produk_sku_part_nama" => $produk_kode,
//                                        "produk_sku_part_nama" => $outdoor_sku,
                                        "produk_sku_part_serial" => "",
                                        "oleh_id" => $iSpec["olehID"],
                                        "oleh_nama" => $iSpec["olehName"],
                                        "supplier_id" => $supplier_id,
                                        "supplier_nama" => $supplier_nama,
                                        "gudang_id" => $iSpec["gudangID"],
                                        "jenis" => $iSpec["jenisTr"],
                                        //---------------
                                        "transaksi_reference_id" => $id_top,
                                        "transaksi_reference_no" => $nomer_top,
                                        "transaksi_reference_dtime" => $dtime,
                                        "transaksi_reference_fulldate" => $fulldate,
                                        "transaksi_reference_count" => 1,
                                        "transaksi_count" => 1,
                                        "transaksi_jenis_count" => 1,
                                        "part_keterangan" => strtoupper($outdoor_sku_label),
                                        "transaksi_id" => $id_current,
                                        "transaksi_no" => $nomer_current,
                                        "dtime" => $dtime,
                                        "fulldate" => $fulldate,
                                    ),
                                );


                            }
                        }


                    }
                }
                arrPrintCyan($data);
//                mati_disini(__LINE__);

                if (sizeof($data) > 0) {
                    cekHitam("total serial akan digenerate: " . count($data));
                    $cm = New ComProdukSerialNumber();
                    $cm->pair($data);
                    $cm->exec();
                    cekBiru($this->db->last_query());
                }
                else {
                    cekMerah("KOSONG...");
                }
            }


            mati_disini("SETOP.... " . __LINE__);
            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            cekHijau("<h3>...DONE...</h3>");


        }
        else {
            cekHitam("kosong belum ada data");
        }

    }

    /** -----------------------------------------------------------------
     * memasukan hasil generator yang belum masuk ke mutasi dan cache
     * -----------------------------------------------------------------*/
    public function copySerialNumber()
    {
        // START OF COMPLETE REPEATED LOGIC
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $this->load->model("Mdls/MdlProdukPerSerialNumber");

        $this->db->trans_start();

        // Menggunakan query JOIN untuk mencari serial number yang sudah aktif dan tidak dihapus (trash=0)
        // namun belum ada di tabel cache (_rek_pembantu_produk_perserial_cache), serta mengecualikan cabang_id = -100.
        $sql = "
            SELECT p.* 
            FROM produk_per_serialnumber p
            LEFT JOIN _rek_pembantu_produk_perserial_cache c 
              ON p.produk_serial_number_2 = c.extern_nama 
              AND p.cabang_id = c.cabang_id
              AND p.gudang_id = c.gudang_id
            WHERE p.status = '1' 
              AND p.trash = '0' 
              AND p.cabang_id != -100
              AND p.transaksi_id = '846523'
              AND p.produk_serial_number_2 IS NOT NULL 
              AND p.produk_serial_number_2 != ''
              AND c.id IS NULL
        ";

        $missingSerials = $this->db->query($sql)->result();

        // arrPrintCyan($missingSerials);

        $count = 0;
        if (sizeof($missingSerials) > 0) {
            foreach ($missingSerials as $ctr => $psnSpec) {
                $params = (array)$psnSpec;

                $dtime = isset($params["dtime_last"]) && !empty($params["dtime_last"]) ? $params["dtime_last"] : (isset($params["dtime"]) ? $params["dtime"] : date("Y-m-d H:i:s"));
                $fulldate = isset($params["fulldate"]) && !empty($params["fulldate"]) ? $params["fulldate"] : date("Y-m-d", strtotime($dtime));

                $arrData = array();
                $arrData[$ctr] = array(
                    "loop" => array(
                        "1010030030" => "1", // persediaan produk
                    ),
                    "static" => array(
                        "cabang_id" => $params["cabang_id"],
                        "gudang_id" => $params["gudang_id"],
                        "extern_id" => "0",
                        "extern_nama" => $params["produk_serial_number_2"], // nomer serial
                        "extern2_id" => "0",
                        "extern2_nama" => $params["produk_sku_part_nama"], // sku part nama
                        "produk_id" => $params["produk_id"], // produk id
                        "produk_nama" => $params["produk_nama"], // produk nama
                        "produk_qty" => 1,
                        "produk_nilai" => 1,
                        "jenis" => isset($params["jenis"]) ? $params["jenis"] : "0000",
                        "transaksi_id" => $params["transaksi_id"],
                        "transaksi_no" => $params["transaksi_no"],
                        "supplierID" => isset($params["supplier_id"]) ? $params["supplier_id"] : "0",
                        "dtime" => $dtime,
                        "fulldate" => $fulldate,
                    ),
                );

                $l = new ComRekeningPembantuProdukPerSerial();
                $l->pair($arrData);
                $l->exec();
                $count++;
                // showLast_query("merah");
                // matiHere(__LINE__);
            }
        }

        // matiHere(__LINE__);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo "Gagal menkopi serial number!";
        } else {
            echo "Berhasil menkopi " . $count . " data serial number baru ke _rek_pembantu_produk_perserial_cache.";
        }
        // END OF COMPLETE REPEATED LOGIC
    }

}

?>