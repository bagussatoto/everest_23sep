<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */


class Persediaan
{
    protected $configUiJenis;
    // protected $configLayout;
    protected $configCoreJenis;
    protected $cCode;
    protected $sessionData;
    protected $jenisTr;
    protected $cabang_id;
    protected $gudang_id;
    protected $toko_id;
    protected $produk_id;


    //region getter dan setter

    public function getProdukId()
    {
        return $this->produk_id;
    }

    public function setProdukId($produk_id)
    {
        $this->produk_id = $produk_id;
    }

    public function getCabangId()
    {
        return $this->cabang_id;
    }

    public function setCabangId($cabang_id)
    {
        $this->cabang_id = $cabang_id;
    }

    public function getGudangId()
    {
        return $this->gudang_id;
    }

    public function setGudangId($gudang_id)
    {
        $this->gudang_id = $gudang_id;
    }

    public function getTokoId()
    {
        return $this->toko_id;
    }

    public function setTokoId($toko_id)
    {
        $this->toko_id = $toko_id;
    }

    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }

    public function getSessionData()
    {
        return $this->sessionData;
    }

    public function setSessionData($sessionData)
    {
        $this->sessionData = $sessionData;
    }

    public function getConfigUiJenis()
    {
        return $this->configUiJenis;
    }

    public function setConfigUiJenis($configUiJenis)
    {
        $this->configUiJenis = $configUiJenis;
    }

    public function getConfigCoreJenis()
    {
        return $this->configCoreJenis;
    }

    public function setConfigCoreJenis($configCoreJenis)
    {
        $this->configCoreJenis = $configCoreJenis;
    }

    public function getCCode()
    {
        return $this->cCode;
    }

    public function setCCode($cCode)
    {
        $this->cCode = $cCode;
    }

    //endregion

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }


    public function cekPersediaan()
    {
        $CI =& get_instance();
        $CI->load->model("Coms/ComRekeningPembantuProduk");
        $cp = New ComRekeningPembantuProduk();
        $cp->setTableName("__rek_pembantu_produk__1010030");
        $cp->addFilter("cabang_id=" . $this->cabang_id);
        $cp->addFilter("gudang_id=" . $this->gudang_id);
        $cp->addFilter("toko_id=" . $this->toko_id);
        if (isset($this->produk_id) && is_array($this->produk_id)) {
            $cp->addFilter("produk_id in ('" . implode("','", $this->produk_id) . "')");
        }
//        else {
//            $cp->addFilter("produk_id=" . $this->produk_id);
//        }
//        $CI->db->limit(10000);
        $CI->db->order_by("id", "asc");
        $cpTmp = $cp->lookupAll()->result();
        showLast_query("biru");
        $arrDatas = array();
        $arrResult = array();
        if (sizeof($cpTmp) > 0) {
            foreach ($cpTmp as $cpSpec) {
                $debet = $cpSpec->debet;
                $kredit = $cpSpec->kredit;
                $qty_debet = $cpSpec->qty_debet;
                $qty_kredit = $cpSpec->qty_kredit;
                $jenis = $cpSpec->jenis;
                $produk_id = $cpSpec->produk_id;
                $produk_nama = $cpSpec->produk_nama;

                $arrDatas[$produk_id]["produk_nama"] = $produk_nama;
                $arrDatas[$produk_id][$jenis]["produk_id"] = $produk_id;
                $arrDatas[$produk_id][$jenis]["produk_nama"] = $produk_nama;
                if (!isset($arrDatas[$produk_id][$jenis]["debet"])) {
                    $arrDatas[$produk_id][$jenis]["debet"] = 0;
                }
                if (!isset($arrDatas[$produk_id][$jenis]["kredit"])) {
                    $arrDatas[$produk_id][$jenis]["kredit"] = 0;
                }
                if (!isset($arrDatas[$produk_id][$jenis]["qty_debet"])) {
                    $arrDatas[$produk_id][$jenis]["qty_debet"] = 0;
                }
                if (!isset($arrDatas[$produk_id][$jenis]["qty_kredit"])) {
                    $arrDatas[$produk_id][$jenis]["qty_kredit"] = 0;
                }
                $arrDatas[$produk_id][$jenis]["debet"] += $debet;
                $arrDatas[$produk_id][$jenis]["kredit"] += $kredit;
                $arrDatas[$produk_id][$jenis]["qty_debet"] += $qty_debet;
                $arrDatas[$produk_id][$jenis]["qty_kredit"] += $qty_kredit;
//                break;
            }
//            arrPrint($arrDatas[56009]);

            foreach ($arrDatas as $pid => $spec) {
                $qty_awal_debet = isset($spec["7778"]["qty_debet"]) ? $spec["7778"]["qty_debet"] : 0;
                $nilai_awal_debet = isset($spec["7778"]["debet"]) ? $spec["7778"]["debet"] : 0;
                $qty_beli_debet = isset($spec["585"]["qty_debet"]) ? $spec["585"]["qty_debet"] : 0;
                $nilai_beli_debet = isset($spec["585"]["debet"]) ? $spec["585"]["debet"] : 0;
                $qty_jual_debet = isset($spec["759"]["qty_debet"]) ? $spec["759"]["qty_debet"] : 0;
                $nilai_jual_debet = isset($spec["759"]["debet"]) ? $spec["759"]["debet"] : 0;

                $qty_awal_kredit = isset($spec["7778"]["qty_kredit"]) ? $spec["7778"]["qty_kredit"] : 0;
                $nilai_awal_kredit = isset($spec["7778"]["kredit"]) ? $spec["7778"]["kredit"] : 0;
                $qty_beli_kredit = isset($spec["585"]["qty_kredit"]) ? $spec["585"]["qty_kredit"] : 0;
                $nilai_beli_kredit = isset($spec["585"]["kredit"]) ? $spec["585"]["kredit"] : 0;
                $qty_jual_kredit = isset($spec["759"]["qty_kredit"]) ? $spec["759"]["qty_kredit"] : 0;
                $nilai_jual_kredit = isset($spec["759"]["kredit"]) ? $spec["759"]["kredit"] : 0;

                $qty_awal_netto = $qty_awal_debet - $qty_awal_kredit;
                $nilai_awal_netto = $nilai_awal_debet - $nilai_awal_kredit;
                $qty_beli_netto = $qty_beli_debet - $qty_beli_kredit;
                $nilai_beli_netto = $nilai_beli_debet - $nilai_beli_kredit;
                $qty_jual_netto = $qty_jual_kredit - $qty_jual_debet;
                $nilai_jual_netto = $nilai_jual_kredit - $nilai_jual_debet;

                $qty_teoritis = $qty_awal_netto + $qty_beli_netto - $qty_jual_netto;
                $nilai_teoritis = $nilai_awal_netto + $nilai_beli_netto - $nilai_jual_netto;

                $arrResult[$pid]["produk_id"] = $pid;
                $arrResult[$pid]["produk_nama"] = $spec["produk_nama"];
                $arrResult[$pid]["awal"] = $nilai_awal_netto;
                $arrResult[$pid]["beli"] = $nilai_beli_netto;
                $arrResult[$pid]["jual"] = $nilai_jual_netto;
                $arrResult[$pid]["akhir"] = $nilai_teoritis;
                $arrResult[$pid]["qty_awal"] = $qty_awal_netto;
                $arrResult[$pid]["qty_beli"] = $qty_beli_netto;
                $arrResult[$pid]["qty_jual"] = $qty_jual_netto;
                $arrResult[$pid]["qty_akhir"] = $qty_teoritis;
            }
        }

//arrPrintKuning($arrResult);
        if (sizeof($arrResult) > 0) {
            $str = "<div>";
//            $str .= "<div>";
//            $str .= "<h3>perbandingan hpp rata-rata dengan harga jual</h3>";
//            $str .= "</div>";
//
            $str .= "<table rules='all' width='100%' style='border:1px solid black;'>";
            $str .= "<tr>";
            $str .= "<td rowspan='2'>no.</td>";
            $str .= "<td rowspan='2'>pid</td>";
            $str .= "<td rowspan='2'>produk nama</td>";
            $str .= "<td colspan='2'>saldo awal</td>";
            $str .= "<td colspan='2'>pembelian</td>";
            $str .= "<td colspan='2'>penjualan</td>";
            $str .= "<td colspan='2'>saldo akhir</td>";
            $str .= "</tr>";
            $str .= "<tr>";
            $str .= "<td>(QTY)</td>";
            $str .= "<td>(RP)</td>";
            $str .= "<td>(QTY)</td>";
            $str .= "<td>(RP)</td>";
            $str .= "<td>(QTY)</td>";
            $str .= "<td>(RP)</td>";
            $str .= "<td>(QTY)</td>";
            $str .= "<td>(RP)</td>";
            $str .= "</tr>";

            $no = 0;
            foreach ($arrResult as $pid => $pdSpec) {
                $pid = $pdSpec['produk_id'];
                $nama = $pdSpec['produk_nama'];
                $awal = $pdSpec['awal'];
                $beli = $pdSpec['beli'];
                $jual = $pdSpec['jual'];
                $akhir = $pdSpec['akhir'];
                $qty_awal = $pdSpec['qty_awal'];
                $qty_beli = $pdSpec['qty_beli'];
                $qty_jual = $pdSpec['qty_jual'];
                $qty_akhir = $pdSpec['qty_akhir'];

//                if ($hpp_avg > $hpp_jual) {
                $no++;
                $str .= "<tr>";
                $str .= "<td>$no</td>";
                $str .= "<td>$pid</td>";
                $str .= "<td>$nama</td>";
                $str .= "<td style='text-align: right;'>" . formatField_he_format("debet", $qty_awal) . "</td>";
                $str .= "<td style='text-align: right;'>" . formatField_he_format("debet", $awal) . "</td>";
                $str .= "<td style='text-align: right;'>" . formatField_he_format("debet", $qty_beli) . "</td>";
                $str .= "<td style='text-align: right;'>" . formatField_he_format("debet", $beli) . "</td>";
                $str .= "<td style='text-align: right;'>" . formatField_he_format("debet", $qty_jual) . "</td>";
                $str .= "<td style='text-align: right;'>" . formatField_he_format("debet", $jual) . "</td>";
                $str .= "<td style='text-align: right;'>" . formatField_he_format("debet", $qty_akhir) . "</td>";
                $str .= "<td style='text-align: right;'>" . formatField_he_format("debet", $akhir) . "</td>";
                $str .= "</tr>";
//                }
            }

            $str .= "</table>";
            $str .= "<div>";
            echo $str;
        }

        $arrReturn = array(
            "hasil" => $arrResult,
            "tabel" => $str,
        );
    }
}
