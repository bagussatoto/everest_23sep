<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loader extends CI_Controller
{
    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

    public function menuTop()
    {

        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }

        $var = callMenuTop();
        if(isset($_GET['dropdown'])){
            echo "<ul>".$var."</ul>";
        }
        else{
            echo $var;
        }
    }

    public function menuLeft()
    {
        $var = callMenuleft();
        // $var = "tst";
        echo $var;

        // $ss = 0;
        // while ($ss <= 100){
        //     echo "$ss ";
        //     $ss++;
        // }
        //
        // $ss = true;
        // $xx = 0;
        // while ($ss == true){
        //     $xx++;
        //     echo "$xx ";
        //
        //     if($xx == 100){
        //         break;
        //     }
        // }
        //
        // $ss = "hitung";
        // $xx = 0;
        // while ($ss == "hitung"){
        //     $xx++;
        //     echo "$xx ";
        //
        //     if($xx == 101){
        //         break;
        //     }
        // }
    }

    public function viewTransaksi_ui()
    {
        $confSources = $this->config->item("heTransaksi_ui");
        $confGroups = $this->config->item("heTransaksiGroup_ui");
        $jeniseGroup = array();
        foreach ($confGroups as $group => $confGroup) {
            // $xx++;
            $jenises = $confGroup['heTransaksi_ui'];
            $label = $confGroup['label'];
            foreach ($jenises as $jenise) {
                $jeniseGroup[$jenise][] = $group;
            }
        }

        $jml_groupJenis = sizeof($jeniseGroup);
        $jml_jenis = sizeof($confSources);
        // arrPrint($confSources);
        // arrPrint($jeniseGroup);
        // matiHere();
        $jml_kolom = 4;
        $jml_perkolom = (int)($jml_jenis / $jml_kolom);
        // cekHere("UJ=$jml_jenis /// GJ=$jml_groupJenis");

        ksort($confSources);
        $xx = 0;
        foreach ($confSources as $jenis => $confSource) {
            $xx++;
            $label = $confSource['label'];
            $groupJenies = isset($jeniseGroup[$jenis]) ? $jeniseGroup[$jenis] : array();
            $hasil = "";
            foreach ($groupJenies as $groupJeny) {
                $var = $groupJeny;
                if ($hasil == "") {
                    $hasil .= "<i style='color: red;'>$var</i>";
                }
                else {
                    $hasil = "<b>$hasil</b>" . "-<i style='color: deepskyblue;'>$var</i>";
                }
            }
            $strGjeny = $hasil;

            $groupJ = isset($jeniseGroup[$jenis]) ? "-" . $strGjeny : "";
            // echo "<b>$jenis</b>-$label$groupJ<br>";
            // echo "<div class='col-md-3 border-cek'>**</div>";
        }

        $data = array(
            "mode"        => "viewMenu",
            "title"       => "control group menu transaksi",
            "subTitle"    => "cicicici",
            "confSources" => $confSources,
            "jeniseGroup" => $jeniseGroup,
            // "groupJenies" => $groupJenies,
            // "confReportObject"  => $confReportObject,
            // "sumCabang"         => $sumCabang,
            // "sumSubject"        => $sumSubject,
            // "sumObject"         => $sumObject,
            // "tblHeadings"       => $header,
            // "tblBodies"         => $bodies,
            // "tblFooters"        => $footers,
            "names"       => isset($names) ? $names : array(),
            // "jenisTr"           => $this->jenisTr,
            "trName"      => "",
        );
        $this->load->view("tool", $data);
    }

    public function viewTransaksiGroup_ui()
    {
        $confSources = $this->config->item("heTransaksi_ui");
        $confGroups = $this->config->item("heTransaksiGroup_ui");
        $jml_jenis = sizeof($confSources);
        // arrPrint($confGroups);
        $jml_kolom = 4;
        $jml_perkolom = (int)($jml_jenis / $jml_kolom);
        // cekHere("$jml_jenis === $jml_perkolom");
        ksort($confSources);
        $sumJmlAnakan = $xx = 0;
        $jenises = array();
        foreach ($confGroups as $jenis => $confSource) {
            $xx++;
            $jenises = $confSource['heTransaksi_ui'];
            $label = $confSource['label'];
            $jmlAnakan = sizeof($jenises);
            $sumJmlAnakan += $jmlAnakan;
            echo "<b style='color: red;'>$jenis</b>-$label<br>";
            // arrPrint($jenises);
            foreach ($jenises as $jenise) {
                $uiLabel = $confSources[$jenise]['label'];
                echo "<b>$jenise</b>-$uiLabel<br>";
            }
            // echo "<div class='col-md-3 border-cek'>**</div>";
        }
        arrPrint($sumJmlAnakan);

    }

    public function viewTransaksi_ui2()
    {
        $this->load->model("Mdls/MdlMenuGroup");
        $this->load->model("Mdls/MdlMenuGroupUi");
        $gr = new MdlMenuGroup();
        $gu = new MdlMenuGroupUi();

        // $gDatas = $gr->lookupAll()->result();
        $gDatas = $gr->callGroupMenuTransaksi();
        $uDatas = $gu->callGroupMenuTransaksiUi();
        // showLast_query("orange");

        // arrPrint($gDatas);
        // arrPrint($uDatas);

        $confSources = $this->config->item("heTransaksi_ui");
        $jeniseGroup = $gu->callJenisGroup();

        $jml_groupJenis = sizeof($jeniseGroup);
        $jml_jenis = sizeof($confSources);
        // arrPrint($confSources);
        // arrPrint($jeniseGroup);
        // matiHere();
        $jml_kolom = 4;
        $jml_perkolom = (int)($jml_jenis / $jml_kolom);
        // cekHere("UJ=$jml_jenis /// GJ=$jml_groupJenis");

        ksort($confSources);
        $xx = 0;
        foreach ($confSources as $jenis => $confSource) {
            $xx++;
            $label = $confSource['label'];
            $groupJenies = isset($jeniseGroup[$jenis]) ? $jeniseGroup[$jenis] : array();
            $hasil = "";
            foreach ($groupJenies as $groupJeny) {
                $var = $groupJeny;
                if ($hasil == "") {
                    $hasil .= "<i style='color: red;'>$var</i>";
                }
                else {
                    $hasil = "<b>$hasil</b>" . "-<i style='color: deepskyblue;'>$var</i>";
                }
            }
            $strGjeny = $hasil;

            $groupJ = isset($jeniseGroup[$jenis]) ? "-" . $strGjeny : "";
            // echo "<b>$jenis</b>-$label$groupJ<br>";
            // echo "<div class='col-md-3 border-cek'>**</div>";
        }

        $data = array(
            "mode"        => "viewMenu",
            "title"       => "control group menu transaksi",
            "subTitle"    => "cicicici",
            "confSources" => $confSources,
            "jeniseGroup" => $jeniseGroup,
            // "groupJenies" => $groupJenies,
            // "confReportObject"  => $confReportObject,
            // "sumCabang"         => $sumCabang,
            // "sumSubject"        => $sumSubject,
            // "sumObject"         => $sumObject,
            // "tblHeadings"       => $header,
            // "tblBodies"         => $bodies,
            "linkEditor" => base_url() . get_class($this) . "/formMenuGroup",
            "names"       => isset($names) ? $names : array(),
            // "jenisTr"           => $this->jenisTr,
            "trName"      => "",
        );
        $this->load->view("tool", $data);
    }

    public function formmenuGroup()
    {
        $ss = $this->uri->segment_array();
        $trJenis = $this->uri->segment(3);
        $trLabel_e = $this->uri->segment(4);
        $trLabel = base64_decode($trLabel_e);
        $this->load->model("Mdls/MdlMenuGroup");
        $this->load->model("Mdls/MdlMenuGroupUi");
        $gr = new MdlMenuGroup();
        $gu = new MdlMenuGroupUi();
        // $gDatas = $gr->lookupAll()->result();
        $gDatas = $gr->callGroupMenuTransaksi();
        // $uDatas = $gu->callGroupMenuTransaksiUi();
        $uJenisGroups = $gu->callJenisGroup();


        // arrPrint($uJenisGroups);
        $onGroups = isset($uJenisGroups[$trJenis]) ? $uJenisGroups[$trJenis] : array();
        // arrPrint($onGroups);
        // arrPrintWebs($uDatas);
        // arrPrintWebs($gDatas);
        // arrPrint($ss);
        // cekBiru("$trJenis --- $trLabel");
        $fields = array(
            "nama" => array(
                "label" => "group",
            )
        );

        foreach ($gDatas as $gData) {
            $gNama = $gData->nama;
        }

        $data = array(
            "mode"     => "modalCheck",
            "title"    => "control group menu transaksi",
            "forms"    => $gDatas,
            "onGroups" => $onGroups,
            "actions"  => "",
            "trjenis"  => $trJenis,
            "field"    => $fields,
            "heading"  => $trLabel,
            "linkSave"  => base_url(). get_class($this),
            "footer"   => form_button('reload', 'close & reload', "class='btn btn-warning pull-right' onclick=\"top.window.location.reload();\""),
            "target"   => "result",
        );
        $this->load->view("data", $data);

    }

    public function saveGroup()
    {
        $this->load->model("Mdls/MdlMenuGroupUi");
        $gu = new MdlMenuGroupUi();

        $ss = $this->uri->segment_array();
        $arrDatas_e = $this->uri->segment(3);
        $arrDatas = blobDecode($arrDatas_e);

        $cekData = $gu->lookupByCondition($arrDatas);
        $jmlData = $cekData->num_rows();
        showLast_query("lime", "data:$jmlData");

        if ($jmlData == 0) {
            // insert
            $insertDatas = $arrDatas;
            $insertDatas["author_id"] = my_id();
            $gu->addData($insertDatas);
            showLast_query("merah");
        }
        else {
            $datas = $cekData->result();
            arrPrint($datas);
            $data_id = $datas[0]->id;
            $condites = array(
                "id" => $data_id
            );
            $update_datas = array(
                "trash" => 1,
            );
            $gu->updateData($condites, $update_datas);
            showLast_query("orange");
            // delete
        }

        // arrPrint();
        // arrPrintWebs($ss);
        // arrPrint($arrDatas);
        // matiHere();
    }

}