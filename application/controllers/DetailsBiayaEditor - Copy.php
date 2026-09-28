<?php

/**
 * Created by PhpStorm.
 * User: widi
 * Date: 16/11/18
 * Time: 16:08
 */
class DetailsBiayaEditor extends CI_Controller
{
    protected $koloms;

    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
    }

    public function edit()
    {

        $prodID = $_GET['sID'];
        $this->load->model("Mdls/MdlProjectKomponenBiayaDetails");
        $this->load->model("Mdls/MdlDtaBiayaProject");
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
        $this->load->model("Mdls/MdlDtaBiayaProduksi");
        $this->load->model("Mdls/MdlSupplies");

//        $this->load->model("Mdls/MdlProdukKompositKomposisi");
//        $this->load->model("Mdls/MdlProduk");
//        $this->load->model("Mdls/MdlProdukMergerRakitan");
//        $this->load->model("Mdls/MdlHargaProduk");
//        $this->load->model("Mdls/MdlProdukKategori");
//        $this->load->model("Mdls/MdlFifoProdukJadi");
//        $this->load->model("Mdls/MdlFifoAverage");

//        $selectedPlace = ($_SESSION['login']['cabang_id']);
        $selectedPlace = "-1";
        $o  = new MdlDtaBiayaProduksi();
        $pk = new MdlProjectKomponenBiayaDetails();
        $o2 = new MdlDtaBiayaProject();
        $pcat = new MdlProdukRakitanPreBiaya();
        $sup  = new MdlSupplies();

//        $pp = new MdlHargaProduk();
//        $ff = new MdlFifoProdukJadi();
//        $fa = new MdlFifoAverage();
        //------------------------------------------------------

        $tmpCategori = $pcat->lookupAll()->result();
        $produkCategori = array();
        if (sizeof($tmpCategori) > 0) {
            foreach ($tmpCategori as $catSpec) {
                $produkCategori[$catSpec->id] = $catSpec->nama;
            }
        }

        //------------------------------------------------------
        $oProp = $o->lookupByID($prodID)->result();
        $components = $pk->lookupByPID($prodID)->result();


        //------------------------------------------------------
        $tmpDtaBiaya = $o2->lookupAll()->result();
        $tmpSupp = $sup->lookupAll()->result();
        //------------------------------------------------------

        $tmpBahan = array_merge($tmpSupp,$tmpDtaBiaya);

//        $pp->setFilters(array());
//        $pp->addFilter("status='1'");
//        $pp->addFilter("trash='0'");
//        $pp->addFilter("jenis_value in ('hpp','jual','jual_nppn')");
//        $tmpPriceBahan = $pp->lookupAll()->result();

//        $tmpPrice = array();
//        if (sizeof($tmpPriceBahan) > 0) {
//            foreach ($tmpPriceBahan as $priceData) {
////                arrPrint($priceData);
////                matiHere();
////                $tmpPrice[$priceData->produk_id] = $priceData->nilai;
////                $tmpPrice[$priceData->produk_id][$priceData->jenis_value] = $priceData->nilai;
//                $tmpPrice[$priceData->produk_id][$priceData->jenis_value] = $priceData->nilai;
//            }
//        }

        //------------------------------------------------------

        $cacheBahan = array();
        if (sizeof($tmpBahan) > 0) {
            foreach ($tmpBahan as $rowB) {
                if(isset($rowB->jenis) && $rowB->jenis == "supplies"){
                $cacheBahan[$rowB->id] = array(
                    "name" => $rowB->nama,
                        "kategori_id" => "",
                        "kategori_nama" => "",
                        "jenis" => $rowB->jenis,
                        "satuan_id" => $rowB->satuan_id,
                        "satuan" => $rowB->satuan,
                    );
                }
                else{
                    $cacheBahan[$rowB->id] = array(
                        "name" => $rowB->nama,
                    "kategori_id" => $rowB->cat_id,
                    "kategori_nama" => isset($produkCategori[$rowB->cat_id]) ? $produkCategori[$rowB->cat_id] : $rowB->cat_nama,
                        "jenis" => "biaya",
                        "satuan_id" => 1,
                        "satuan" => "unit",
                );
            }
        }
        }

        if (!isset($_SESSION['PROPKGED'][$prodID])) {
            unset($_SESSION['PROPKGED']);
            $_SESSION['PROPKGED'][$prodID] = array();
        }
        if (!isset($_SESSION['PROPKGED'][$prodID]['component'])) {
            $_SESSION['PROPKGED'][$prodID]['component'] = array();
        }

//        $_SESSION['PROPKGED'][$prodID]['component'] = array();

        $_SESSION['PROPKGED'][$prodID]['backLink'] = isset($_GET['backLink']) ? unserialize(base64_decode($_GET['backLink'])) : "";
        if (count($_SESSION['PROPKGED'][$prodID]['component']) == 0) {
            if (count($components) > 0) {
                foreach ($components as $row) {
                    if (isset($cacheBahan[$row->biaya_dasar_id])) {
                        $_SESSION['PROPKGED'][$prodID]['component'][$row->biaya_dasar_id] = array(
                            "name" => $cacheBahan[$row->biaya_dasar_id]['name'],
                            "jml" => $row->jml,
                            "harga" => $row->harga*1,
                            "subtotal" =>  ($row->jml*$row->harga)*1,
                            "subharga" =>  ($row->jml*$row->harga)*1,
                            "cat_nama" => $cacheBahan[$row->biaya_dasar_id]['kategori_nama'],
                            "cat_id" => $row->cat_id,
                            "biaya_id" => $oProp[0]->id,
                            "biaya_nama" => $oProp[0]->nama,
                            "jenis" => $cacheBahan[$row->biaya_dasar_id]['jenis'],
                            "satuan_id" => $cacheBahan[$row->biaya_dasar_id]['satuan_id'],
                            "satuan" => $cacheBahan[$row->biaya_dasar_id]['satuan'],
                        );
                    }
                }
            }
        }

        arrprint($_SESSION['PROPKGED'][$prodID]['component']);

        $headers = array(
            "no"        => "class='text-center bg-grey-2 text-uppercase'",
            "nama"      => "class='text-center bg-grey-2 text-uppercase'",
            "jenis"     => "class='text-center bg-grey-2 text-uppercase'",
            "COA biaya" => "class='text-center bg-grey-2 text-uppercase'",
            "harga"     => "class='text-center bg-grey-2 text-uppercase'",
            "jml"       => "class='text-center bg-grey-2 text-uppercase'",
            "satuan"    => "class='text-center bg-grey-2 text-uppercase'",
            "subtotal"  => "class='text-center bg-grey-2 text-uppercase'",
            "action"    => "class='text-center bg-grey-2 text-uppercase'",
        );

        $content = "";
        if (count($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            $btnAttr = "";
            $content .= "<table class='table dataTable compact display table-bordered table-striped'>";
            $content .= "<thead>";
            $content .= "<tr>";
            foreach ($headers as $header => $hAttr) {
                $content .= "<th $hAttr>$header</th>";
            }
            $content .= "</tr>";
            $content .= "</thead>";
            $content .= "<tbody>";
            $no = 0;
            $cTab = 100;
            $totalValue = 0;
            $totalHpp = 0;
            $totalHarga = 0;
            foreach ($_SESSION['PROPKGED'][$prodID]['component'] as $id => $eSpec) {
                $no++;
                $cTab++;
                $harga = $eSpec['harga'];
                $jml = $eSpec['jml'];
                $catid = $eSpec['cat_id'];
                $produk_type = $eSpec['jenis'];

                $totalValue += $eSpec['subtotal'];
                $totalHarga += $eSpec['subharga'];

                $content .= "<tr>";
                $content .= "<td class='text-right valign-m' valign='middle'>$no</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= isset($eSpec['name']) ? $eSpec['name'] : "undefined";
                $content .= "</td>";

                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= isset($eSpec['jenis']) ? $eSpec['jenis'] : "undefined";
                $content .= "</td>";

                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<select class='form-control' onchange=\"top.$('#result').load('" . base_url() . "_detailsBiayaEditor/addItem?tpID=$produk_type&sID=$prodID&bID=$id&key=cat_id&jml=$jml&cat_id='+this.value, null, function(){
                            $('#btnKalkulasi').removeClass('hidden');
                            $('#btnProcess').attr('onclick','javascript:void(0)');
                            $('#btnProcess').prop('disabled',true);
                            $('#btnProcess').removeClass('btn-info');
                        }); \" style='height: 26px;padding: 2px 2px !important;'>";
                $content .= "<option value='0'>==PILIH==</option>";
                foreach($produkCategori as $cat_id => $cat_nama){
                    $selected = $cat_id == $catid ? "selected" : "";
                    $content .= "<option $selected value='$cat_id'>$cat_nama</option>";
                }
                $content .= "</select>";
                $content .= "</td>";

                //-----------------------------------------------------------------
//                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
//                $content .= isset($eSpec['kode']) ? $eSpec['kode'] : "";
//                $content .= "</td>";
                //-----------------------------------------------------------------
//                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
//                $content .= isset($eSpec['no_part']) ? $eSpec['no_part'] : "";
//                $content .= "</td>";
                //-----------------------------------------------------------------
//                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
//                $content .= isset($eSpec['kategori_nama']) ? $eSpec['kategori_nama'] : "";
//                $content .= "</td>";
//                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
//                $content .= "<span style='text-transform: uppercase;'>" . $eSpec['satuan'] . "</span>";
//                $content .= "</td>";

                $content .= "<td style='width: 70px;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<input type='text' onclick=\"this.select()\" tabindex='$cTab' name='harga[]' value='" . number_format($eSpec['harga']) . "'
                    onblur =\"
                    if(" . $eSpec['harga'] . "!=this.value){
                        top.$('#result').load('" . base_url() . "_detailsBiayaEditor/addItem?tpID=$produk_type&sID=$prodID&bID=$id&key=harga&harga='+removeCommas(this.value), null, function(){
                            $('#btnKalkulasi').removeClass('hidden');
                            $('#btnProcess').attr('onclick','javascript:void(0)');
                            $('#btnProcess').prop('disabled',true);
                            $('#btnProcess').removeClass('btn-info');
                        });
                    }
                    else{
                        return false;
                    }\"
                    class='form-control text-right' style='height: 26px;padding: 2px 2px !important;'>";
                $content .= "</td>";

                $content .= "<td style='width: 70px;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<input type='number' onclick=\"this.select()\" tabindex='$cTab' name='jml[]' value='" . number_format($eSpec['jml']) . "'
                    onblur =\"if(" . $eSpec['jml'] . "!=this.value){ top.$('#result').load('" . base_url() . "_detailsBiayaEditor/addItem?tpID=$produk_type&sID=$prodID&bID=$id&key=jml&jml='+removeCommas(this.value), null, function(){
                            $('#btnKalkulasi').removeClass('hidden');
                             $('#btnProcess').attr('onclick','javascript:void(0)');
                            $('#btnProcess').prop('disabled',true);
                            $('#btnProcess').removeClass('btn-info');
                        });
                    }
                    else {
                        return false;
                    }\"

                    class='form-control text-right' style='height: 26px;padding: 2px 2px !important;'>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td class='text-center text-uppercase text-bold' style='text-align:right;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span class='' style='text-transform: uppercase;'>" . $eSpec['satuan']!='' ? $eSpec['satuan'] : "<span class='text-red text-bold'>none</span>"  . "</span>";
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='text-align:right;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['subharga']) ? number_format($eSpec['subharga']) : 0 . "</span>";
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td class='text-center valign-m'>";
                $content .= "<a class='text-red' href=\"javascript:void(0)\" onclick=\"top.$('#result').load('" . base_url() . "_detailsBiayaEditor/removeItem/$prodID/" . $id . "');\"><span class='glyphicon glyphicon-remove'></span></a>";
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "</tr>";
            }

            $content .= "</tbody>";
            //region subtotal bawah
            $colspan = sizeof($headers) - 2;
            $content .= "<tfoot>";
            $content .= "<tr>";
            //--------------------------------------
            $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;' colspan='$colspan'>";
            $content .= "<span style='font-size: 18px;' class='text-uppercase text-renggang-5 text-bold pull-left'>total biaya</span>";
            $content .= "</td>";
            //--------------------------------------
            $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
            $content .= "<span style='font-size: 15px;' class='text-uppercase text-bold pull-right'>" . number_format($totalHarga) . "</span>";
            $content .= "</td>";
            //--------------------------------------
            $content .= "</tr>";
            $content .= "</tfoot>";
            //endregion

            $contentDiff = "";
            $contentDiff .= "<tr class='text-center text-bold'>";
            $contentDiff .= "<td id='tdWarning' colspan='10' style='background-color: yellow;'>";
            $contentDiff .= "<span class='blink text-renggang-5'>Perubahan Components belum disimpan.<br>Untuk menyimpan klik Save Components.</span>";
            $contentDiff .= "</td>";
            $contentDiff .= "</tr>";

            if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) != sizeof($components)) {
                $content .= $contentDiff;
            }
            else{
                // cek qty masing-masing produk
                $different = false;
                foreach ($components as $cSpec){
                    $pID = $cSpec->biaya_dasar_id;
                    $qty_db = $cSpec->jml;
                    $qty_ses = isset($_SESSION['PROPKGED'][$prodID]['component'][$pID]['jml']) ? $_SESSION['PROPKGED'][$prodID]['component'][$pID]['jml'] : 0;
                    if($qty_db != $qty_ses){
                        $different = true;
                        break;
                    }
                }
                if($different == true){
                    $content .= $contentDiff;
                }
            }

            $content .= "</table class='table'>";

        }
        else {
            $content .= "<div class='row text-center' style='border: 0px solid green;'>";
            $content .= "<h2><small>Komponen Biaya Project </small><p class='text-red'>" . $oProp[0]->nama . "</p> <small>belum ditentukan</small></h2>";
            $content .= "<p class='text-danger'>Silahkan pilih biaya yang diperlukan dari kolom sebelah kiri</p>";
            $content .= "</div>";
            $btnAttr = "disabled";
        }

        $data = array(
            "mode" => "edit_details_biaya",
            "content" => $content,
            "btnAttr" => $btnAttr,
        );
        $this->load->view("editor", $data);
    }

    public function save()
    {

        $prodID = $this->uri->segment(3);
        $this->load->model("Mdls/" . 'MdlProjectKomponenBiayaDetails');
        $pk = New MdlProjectKomponenBiayaDetails();
        $this->db->trans_start();
        $preTmp = $pk->lookupByPID($prodID)->result();
        if (sizeof($preTmp) > 0) {
            foreach ($preTmp as $eSpec) {
                $arrUpdate = array(
                    "trash" => 1,
                );
                $where = array(
                    "id" => $eSpec->id,
                );
                $pk->updateData($where, $arrUpdate, $pk->getTableName());
            }
        }

        $arrData = array();
        $total_price = 0;
        $insertIDS=array();
        if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            foreach ($_SESSION['PROPKGED'][$prodID]['component'] as $bahanID => $eSpec) {
                $arrData = array(
                    "biaya_id" => $prodID,
                    "biaya_nama" => $eSpec['biaya_nama'],
                    "biaya_dasar_id" => $bahanID,
                    "biaya_dasar_nama" => $eSpec['name'],
                    "cat_id" => $eSpec['cat_id'],
                    "cat_nama" => $eSpec['cat_nama'],
                    "jml" => $eSpec['jml'],
                    "dtime" => date("Y-m-d H:i:s"),
                    "author" => $this->session->login['id'],
                    "harga"=>$eSpec['harga'],
                    "jenis"=> $eSpec['jenis'],
                );
                $insertIDS[]=$pk->addData($arrData);
                $total_price +=$eSpec['jml']*$eSpec['harga'];
                cekHere($this->db->last_query());
            }
        }

        $this->archive(); //mindah trash 1 ke tabel archive

        $this->db->trans_complete();
        $backLink = $_SESSION['PROPKGED'][$prodID]['backLink'];
        $_SESSION['PROPKGED'][$prodID]['component'] = NULL;
        $_SESSION['PROPKGED'][$prodID]['backLink'] = NULL;

        unset($_SESSION['PROPKGED']);

        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "DetailsBiayaEditor/edit?attached=1&sID=$prodID&backlink=$backLink');";

        echo "<script>$actionTarget</script>";
    }

    public function archive(){

        $this->db->select("*");
        $this->db->where("trash=1");
        $existing = $this->db->get("project_komponen_biaya_details")->result();

        $delete=array();
        if(!empty($existing)){
            foreach($existing as $exist){
                $ins = $this->db->insert("project_komponen_biaya_details_archive", $exist);
                if($ins){
                    $delete[] = $exist->id;
                }
            }

            if(!empty($delete)){
                foreach($delete as $del){
                    $this->db->delete('project_komponen_biaya_details', array('id' => $del));
                }
            }
        }
    }
}