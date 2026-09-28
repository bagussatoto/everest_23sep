<?php

//error_reporting(-1);
//ini_set('display_errors', 1);

class PendingSetingAset extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->helper("he_lib_route");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->helper('he_angka');
        $this->load->config("heAccounting");

        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlAsetDetail");
        $this->load->model("Mdls/MdlSewaDetail");
        $this->load->model("Mdls/MdlFolderAset");
        $this->load->model("Mdls/MdlMongoMother");
        $this->mongoTableList = array(
            "main" => "transaksi",
            "mainValues" => "transaksi_values",
            "detail" => "transaksi_data",
            "detailValues" => "transaksi_data_values",
            "sign" => "transaksi_sign",
            "extras" => "transaksi_extstep",
            "registry" => "transaksi_registry",
        );
//        $this->tableInConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn'] : array();
//        $this->tableInConfig_static = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static'] : array();
    }


    public function index()
    {
        $this->load->model("Mdls/MdlSetupDepresiasi");
        $this->load->model("Coms/ComRekeningPembantuAktivaBerwujud");
        $this->load->model("Coms/ComRekeningPembantuSewa");
        $t = new MdlSetupDepresiasi();
        $o = new ComRekeningPembantuAktivaBerwujud();
        $s = new ComRekeningPembantuSewa();
        $cabang_id = $this->session->login["cabang_id"];
        $t->addFilter("cabang_id='$cabang_id'");
        $tmpSeting = $t->lookUpAll()->result();
        $activeSeting = array();
        if (count($tmpSeting) > 0) {
            foreach ($tmpSeting as $tmpSeting_0) {
                $activeSeting[$tmpSeting_0->jenis][$tmpSeting_0->extern_id] = $tmpSeting_0->extern_nama;
            }
        }


        //region aktiva all
        $o->addFilter("cabang_id='" . $cabang_id . "'");
        $o->addFilter("periode='forever'");
        $o->addFilter("debet>'0'");
        $o->addFilter("");
        $tmp = $o->lookupAll()->result();
        showLast_query("merah");
//        arrPrint($tmp);
        $allAsets = array();
        if (count($tmp) > 0) {
            foreach ($tmp as $tmp_0) {
                $allAsets["assets"][$tmp_0->extern_id] = (array)$tmp_0;
            }
        }

        //endregion

        //region sewa
        $s->addFilter("cabang_id='" . $cabang_id . "'");
        $s->addFilter("debet>'1'");
        $s->addFilter("periode='forever'");
        $s->addFilter("");
        $tmpSewa = $s->lookupAll()->result();
//        cekBiru($this->db->last_query());

        if (count($tmpSewa) > 0) {
            foreach ($tmpSewa as $tmpSewa_0) {
                $allAsets["sewa"][$tmpSewa_0->extern_id] = (array)$tmpSewa_0;
            }
        }


        //endregion
        $header = array(
            "extern_id" => "PID",
            "dtime" => "Tgl pembelian",
            "extern_nama" => "Aset",
            "debet" => "Nilai",
            "note" => "Catatan",
        );
        $items = "";
        if (count($allAsets) > 0) {
//            $items .= "<div>";
            $items .= "<div class='box box-info collapsed-box box-solid'>";
            $items .= "<div class='box-header with-border text-red'><h3 class='box-title text-uppercase' >Aset belum diseting depresiasi <span id='badge1' class='badge bg-red fa-2x blink'></span></h3>";
//            $items .= "<div class='panel-body'>";
            $items .= "<div class='box-tools pull-right'>";
//            $items .= "<button type='button' class='btn btn-box-tool' onclick=\"$('#requestItems2').load('$link_undone');\"><i class='fa fa-refresh'></i></button>";
            $items .= "<button type='button' class='btn btn-success btn-sm' data-widget='collapse'><i class='fa fa-plus'></i></button>";
            $items .= "</div>";
            $items .= "</div>";

            $items .= "<div class=\"box-body\">";
            $items .= "<div class='table-responsive step'>";
            $items .= "<table class='table nowarp order-column table-condensed table-bordered no-padding' style='border:solid red 0px;'>";
            $items .= "<tr>";
            $items .= "<th>No</th>";
            foreach ($header as $hk => $h_label) {
                $items .= "<th>$h_label</th>";
            }
            $items .= "</tr>";
            $asetBelumSetting = array();
            foreach ($allAsets as $k => $tmpAssetAll) {
                $i = 0;
                foreach ($tmpAssetAll as $aset_id => $tmpAssetAll_0) {
                    if (!isset($activeSeting[$k][$aset_id])) {
                        $i++;
                        $items .= "<tr>";
                        $items .= "<td>$i</td>";
                        foreach ($header as $kk => $k_alias) {
                            $items .= "<td>";
                            $items .= formatField($kk, $tmpAssetAll_0[$kk]);
                            $items .= "</td>";
                        }
                        $items .= "</tr>";

                        $asetBelumSetting[$aset_id] = $i;
                    }
//                    $items[]=$datas;
                }

            }
            $countAsetBelumSetting = count($asetBelumSetting);
            $link = base_url() . "SetupDepresiasi/view/Assets";
            $items .= "</table>";
            $items .= "<script>
$('#badge1').text('$countAsetBelumSetting');
</script>";


            $items .= "<div>";
            $items .= "<button onclick=\"location.href='$link'\" class='btn btn-danger btn-block' title='klik disini untuk menuju ke halaman seting'>KE HALAMAN SETING</button>";
            $items .= "</div>";
            $items .= "</div>";
            $items .= "</div>";
            $items .= "</div class=\"box box-danger\">";
        }
//        cekMerah($i);
//        cekHere($items);
//        arrPrint($asetBelumSetting);
//        if ($i > 0) {
        if (sizeof($asetBelumSetting) > 0) {
            echo $items;
        }
        else {
//            $items="";
//            echo $items;

        }
    }

    public function jadwal_penyusutan()
    {

        $this->load->model("Mdls/MdlCabang");
        $c = new MdlCabang();
        $cabangData = array();

        $c->addFilter("id<>0");
        $temCabang = $c->lookupAll()->result();

        foreach ($temCabang as $cabData) {
            $cabangData[$cabData->id] = $cabData->nama;
        }

        $this->load->model("Mdls/MdlSetupDepresiasiAssetsProduction");
        $d = new MdlSetupDepresiasiAssetsProduction();
        $d->addFilter("depresiasi=1");
        $d->addFilter("cabang_id<>0");
        $depresiasi = $d->lookupAll()->result();

        $this->load->model("Mdls/MdlSetupDepresiasiSewaProduction");
        $d2 = new MdlSetupDepresiasiSewaProduction();
        $d2->addFilter("depresiasi=1");
        $d2->addFilter("cabang_id<>0");
        $amortisasi = $d2->lookupAll()->result();

        $result = array(
            "amortisasi" => array(),
            "depresiasi" => array(),
        );

        $header = array(
            "extern_nama" => "nama aset",
            "cabang_id" => "ID Cabang",
            "repeat" => "Tgl Penyusutan",
            "economic_life_time" => "Bln Penyusutan",
        );

        echo "
        <style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
        ";

        $dataDepre = array();
        if (count($depresiasi) > 0) {
            echo "<table style='font-family: arial, sans-serif; border-collapse: collapse; width: 100%;'>";
            echo "<thead>";
            echo "<tr>";
            echo "<td>cabang</td>";
            echo "<td>tgl depre</td>";
            echo "<td>jml asset</td>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            $tmpSetting = array();
            foreach ($depresiasi as $datas) {
                $tmpSetting[$datas->cabang_id][] = $datas->id;
                $dataDepre[$datas->cabang_id][$datas->repeat] = $datas->repeat;
            }


            foreach ($dataDepre as $idCab => $rep) {
                sort($rep);
                $totalSetting = count($tmpSetting[$idCab]);
                echo "<tr>";
                echo "<td>" . $cabangData[$idCab] . "</td>";
                echo "<td>" . implode(',', $rep) . "</td>";
                echo "<td>" . $totalSetting . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "<hr>";
        }

        $dataAmor = array();
        if (count($amortisasi) > 0) {
            echo "<table border=1>";
            echo "<thead>";
            echo "<tr>";
            echo "<td>cabang</td>";
            echo "<td>tgl depre</td>";
            echo "<td>jml sewa</td>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            $tmpSetting = array();
            foreach ($amortisasi as $datas) {
                $tmpSetting[$datas->cabang_id][] = $datas->id;
                $dataAmor[$datas->cabang_id][$datas->repeat] = $datas->repeat;
            }
            foreach ($dataAmor as $idCab => $rep) {
                sort($rep);
                $totalSetting = count($tmpSetting[$idCab]);
                echo "<tr>";
                echo "<td>" . $cabangData[$idCab] . "</td>";
                echo "<td>" . implode(',', $rep) . "</td>";
                echo "<td>" . $totalSetting . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "<hr>";
        }
    }
}

