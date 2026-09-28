<?php

/**
 * Created by PhpStorm.
 * User: widi
 * Date: 16/11/18
 * Time: 16:08
 */
class ProductKompositEditor extends CI_Controller
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
        $this->load->model("Mdls/MdlProdukKomposit");
        $this->load->model("Mdls/MdlProdukKompositKomposisi");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlProdukMergerRakitan");
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlProdukKategori");
        $this->load->model("Mdls/MdlFifoProdukJadi");
        $this->load->model("Mdls/MdlFifoAverage");

//        $selectedPlace = ($_SESSION['login']['cabang_id']);
        $selectedPlace = -"-1";
        $o = new MdlProdukKomposit();
        $pk = new MdlProdukKompositKomposisi();
//        $o2 = new MdlProduk();
        $o2 = new MdlProdukMergerRakitan();
        $pp = new MdlHargaProduk();
        $pcat = new MdlProdukKategori();
        $ff = new MdlFifoProdukJadi();
        $fa = new MdlFifoAverage();
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
        $tmpBahan = $o2->lookupAll()->result();
        //------------------------------------------------------
        $pp->setFilters(array());
        $pp->addFilter("status='1'");
        $pp->addFilter("trash='0'");
//        $pp->addFilter("cabang_id='$selectedPlace'");
        $pp->addFilter("jenis_value in ('hpp','jual','jual_nppn')");
        $tmpPriceBahan = $pp->lookupAll()->result();
//        arrPrint($tmpPriceBahan);
//        cekHitam($this->db->last_query());

//        $fa->addFilter("cabang_id='$selectedPlace'");
//        $fa->addFilter("jenis='produk'");
//        $tmpPriceBahan = $fa->lookupAll()->result();
//showLast_query("biru");
        $tmpPrice = array();
        if (sizeof($tmpPriceBahan) > 0) {
            foreach ($tmpPriceBahan as $priceData) {
//                arrPrint($priceData);
//                matiHere();
//                $tmpPrice[$priceData->produk_id] = $priceData->nilai;
//                $tmpPrice[$priceData->produk_id][$priceData->jenis_value] = $priceData->nilai;
                $tmpPrice[$priceData->produk_id][$priceData->jenis_value] = $priceData->nilai;
            }
        }
        //------------------------------------------------------

//cekHere($tmpPrice);
//        matiHEre(__LINE__);
//        $ff->addFilter("cabang_id='$selectedPlace'");
//        $ffTmp = $ff->lookupAll()->result();
//
//        $ffHpp = array();
//        if (sizeof($ffTmp) > 0) {
//            foreach ($ffTmp as $ffSpec) {
//                $ffHpp[$ffSpec->produk_id] = $ffSpec->hpp;
//            }
//        }

        //------------------------------------------------------

        $cacheBahan = array();
        if (sizeof($tmpBahan) > 0) {
            foreach ($tmpBahan as $rowB) {
                $cacheBahan[$rowB->id] = array(
                    "name" => $rowB->nama,
                    "kode" => $rowB->kode,
                    "no_part" => $rowB->no_part,
                    "kategori_nama" => isset($produkCategori[$rowB->kategori_id]) ? $produkCategori[$rowB->kategori_id] : $rowB->kategori_nama,
                    "label" => $rowB->label,
                    "satuan" => $rowB->satuan,

                );
            }
        }

//        unset($_SESSION['PROPKGED'][$prodID]);//buat tembakan  reset dulu
        if (!isset($_SESSION['PROPKGED'][$prodID])) {
            $_SESSION['PROPKGED'][$prodID] = array();
        }
        if (!isset($_SESSION['PROPKGED'][$prodID]['component'])) {
            $_SESSION['PROPKGED'][$prodID]['component'] = array();
        }
//        $_SESSION['PROPKGED'][$prodID]['component'] = array();
//        arrPrintWebs($components);
        $_SESSION['PROPKGED'][$prodID]['backLink'] = isset($_GET['backLink']) ? unserialize(base64_decode($_GET['backLink'])) : "";
        if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) == 0) {
            if (sizeof($components) > 0) {
                foreach ($components as $row) {
                    if (isset($cacheBahan[$row->produk_dasar_id])) {
//                        $hpp = isset($ffHpp[$row->produk_dasar_id]) ? $ffHpp[$row->produk_dasar_id] : isset($tmpPrice[$row->produk_dasar_id]['hpp']) ? $tmpPrice[$row->produk_dasar_id]['hpp'] : 0;
                        $hpp = isset($tmpPrice[$row->produk_dasar_id]['hpp']) ? $tmpPrice[$row->produk_dasar_id]['hpp'] : 0;
                        $_SESSION['PROPKGED'][$prodID]['component'][$row->produk_dasar_id] = array(
                            "name" => $cacheBahan[$row->produk_dasar_id]['name'],
                            "satuan" => $cacheBahan[$row->produk_dasar_id]['satuan'],
                            "jml" => $row->jml,
                            "jual_paket" => isset($tmpPrice[$row->produk_dasar_id]['jual_paket']) ? $tmpPrice[$row->produk_dasar_id]['jual_paket']:$tmpPrice[$row->produk_dasar_id]['jual'],
                            "hpp" => $row->hpp,
                            "price" => $row->price*1,
                            "jual" => $row->jual*1,
                            "harga" => $row->harga*1,
                            "subharga" =>  ($row->jml*$row->harga)*1,
                            "subtotal" =>  ($row->jml*$row->harga)*1,
                            "subhpp" => ($row->jml * $hpp)*1,
                            //-----------------------------------------------------------------
                            "label" => $cacheBahan[$row->produk_dasar_id]['label'],
                            "kode" => $cacheBahan[$row->produk_dasar_id]['kode'],
                            "no_part" => $cacheBahan[$row->produk_dasar_id]['no_part'],
                            "kategori_nama" => $cacheBahan[$row->produk_dasar_id]['kategori_nama'],
                            //-----------------------------------------------------------------
                        );
                    }
                }
            }
        }

//                arrPrint($_SESSION['PROPKGED'][$prodID]['component']);
        $headers = array(
            "no" => "class='text-center bg-grey-2 text-uppercase'",
            "Produk" => "class='text-center bg-grey-2 text-uppercase'",
            "Code" => "class='text-center bg-grey-2 text-uppercase'",
            "Part Number" => "class='text-center bg-grey-2 text-uppercase'",
            "Category" => "class='text-center bg-grey-2 text-uppercase'",
            "uom" => "class='text-center bg-grey-2 text-uppercase'",
            "Qty" => "class='text-center bg-grey-2 text-uppercase'",
            "Harga jual STD" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",
            "Harga paket" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",

//            "hpp" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",
//            "price" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",
//            "subhpp" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",
            "subtotal" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",
            "rem" => "class='text-center bg-grey-2 text-uppercase'",
        );

//arrprintWEbs($_SESSION['PROPKGED'][$prodID]['component']);
        $content = "";
        if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            $btnAttr = "";
            $content .= "<table rrules='all' class='table table-striped' style='border: 0px solid red;background-color: transparent;'>";
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
//                arrPrint($eSpec);
                $no++;
                $cTab++;
                $harga= $eSpec['harga'];
                $jml= $eSpec['jml'];
                $totalValue += $eSpec['subtotal'];
                $totalHpp += $eSpec['subhpp'];
                $totalHarga += $eSpec['subharga'];
                $content .= "<tr>";
                $content .= "<td class='text-right valign-m' valign='middle'>$no</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= isset($eSpec['name']) ? $eSpec['name'] : "";
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= isset($eSpec['kode']) ? $eSpec['kode'] : "";
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= isset($eSpec['no_part']) ? $eSpec['no_part'] : "";
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= isset($eSpec['kategori_nama']) ? $eSpec['kategori_nama'] : "";
                $content .= "</td>";
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . $eSpec['satuan'] . "</span>";
                $content .= "</td>";
                $content .= "<td style='width: 70px;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<input type='number' onclick=\"this.select()\" tabindex='$cTab' name='jml[]' value='" . $eSpec['jml'] . "'
                    onblur =\"if(" . $eSpec['jml'] . "!=this.value){ top.$('#result').load('" . base_url() . "_productKompositEditor/addItem?sID=$prodID&bID=$id&key=harga&harga=$harga&jml='+this.value, null, function(){ $('#btnKalkulasi').removeClass('hidden');$('#btnProcess').attr('onclick','javascript:void(0)');$('#btnProcess').prop('disabled',true);$('#btnProcess').removeClass('btn-info'); }); } else {return false;}\"
                    class='form-control text-right'>";
                $content .= "</td>";
                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['jual']) ? formatField("harga", $eSpec['jual']) : 0 . "</span>";
                $content .= "</td>";

                $content .= "</td>";
                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
//                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['jual']) ? formatField("harga", $eSpec['harga']) : 0 . "</span>";
                $content .= "<input type='number' onclick=\"this.select()\" tabindex='$cTab' name='harga[]' value='" . $eSpec['harga'] . "'
                    onblur =\"if(" . $eSpec['harga'] . "!=this.value){ top.$('#result').load('" . base_url() . "_productKompositEditor/addItem?sID=$prodID&bID=$id&key=jml&jml=$jml&harga='+this.value, null, function(){ $('#btnKalkulasi').removeClass('hidden');$('#btnProcess').attr('onclick','javascript:void(0)');$('#btnProcess').prop('disabled',true);$('#btnProcess').removeClass('btn-info'); }); } else {return false;}\"
                    class='form-control text-right'>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                //-----------------------------------------------------------------
                //-----------------------------------------------------------------
                //-----------------------------------------------------------------
//                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
//                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['hpp']) ? formatField("harga", $eSpec['hpp']) : 0 . "</span>";
//                $content .= "</td>";

                //-----------------------------------------------------------------
//                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
//                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['price']) ? formatField("harga", $eSpec['price']) : 0 . "</span>";
//                $content .= "</td>";

                //-----------------------------------------------------------------
//                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
//                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['subhpp']) ? formatField("harga", $eSpec['subhpp']) : 0 . "</span>";
//                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['subharga']) ? formatField("harga", $eSpec['subharga']) : 0 . "</span>";
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td class='text-center valign-m'>";
                $content .= "<a class='text-red' href=\"javascript:void(0)\" onclick=\"top.$('#result').load('" . base_url() . "_productKompositEditor/removeItem/$prodID/" . $id . "');\"><span class='glyphicon glyphicon-remove'></span></a>";
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
            $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;' colspan='$colspan'>";
            $content .= "<span style='font-size: 18px;' class='text-uppercase text-renggang-5 text-bold pull-left'>total</span>";
            $content .= "</td>";
            //--------------------------------------
            $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
            $content .= "<span style='font-size: 15px;' class='text-uppercase text-bold pull-right'>" . formatField("harga", $totalHarga) . "</span>";
            $content .= "</td>";
            //--------------------------------------
//            $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
//            $content .= "<span style='font-size: 15px;' class='text-uppercase text-bold pull-right'>" . formatField("harga", $totalValue) . "</span>";
//            $content .= "</td>";
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

//            cekKuning($_SESSION['PROPKGED'][$prodID]['component']);
//            cekPink($components);

            if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) != sizeof($components)) {
                $content .= $contentDiff;
//                $content .= "<script> setTimeout( function(){ $('#msgAlert').html(\"<span class='blink text-renggang-5 text-center text-bold'>Perubahan Components belum disimpan.</span>\").removeClass('hidden'); }, 500 )</script>";
            }
            else{
                // cek qty masing-masing produk
                $different = false;
                foreach ($components as $cSpec){
                    $pID = $cSpec->produk_dasar_id;
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
            $content .= "<h2><small>Komposisi Paket </small><p class='text-red'>" . $oProp[0]->nama . "</p> <small>belum ditentukan</small></h2>";
            $content .= "<p class='text-danger'>Silahkan pilih produk yang diperlukan dari kolom sebelah kiri</p>";
            $content .= "</div>";

            $btnAttr = "disabled";
        }

//arrPrint($_SESSION['PROPKGED']);
        $anu = array(
            "mode" => "edit_komposit",
            "content" => $content,
            //            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2)
        );
        $data = array(
            "mode" => "edit_komposit",
            "content" => $content,
            "btnAttr" => $btnAttr,
            //            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2),
            "anu" => $anu,
        );
        $this->load->view("editor", $data);
    }


    public function save()
    {

        $prodID = $this->uri->segment(3);


        $this->load->model("Mdls/" . 'MdlProdukKompositKomposisi');
        $pk = New MdlProdukKompositKomposisi();


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
//                cekHere($this->db->last_query());
            }
        }


        $arrData = array();
        $total_price = 0;
        $insertIDS=array();
        if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            foreach ($_SESSION['PROPKGED'][$prodID]['component'] as $bahanID => $eSpec) {
                $arrData = array(
                    "produk_id" => $prodID,
                    "produk_nama" => "",
                    "produk_dasar_id" => $bahanID,
                    "produk_dasar_nama" => $eSpec['name'],
                    "satuan_nama" => $eSpec['satuan'],
                    "jml" => $eSpec['jml'],
                    "dtime" => date("Y-m-d H:i:s"),
                    "author" => $this->session->login['id'],
                    "harga"=>$eSpec['harga'],
                    "jual"=>$eSpec['jual'],
                );
                $insertIDS[]=$pk->addData($arrData);
                $total_price +=$eSpec['jml']*$eSpec['harga'];
                cekHere($this->db->last_query());
            }
        }


        if(count($insertIDS)>0){
            $this->load->model("Mdls/MdlHargaProduk");
            $p = new MdlHargaProduk();

            $dataUpdatePrice = array(
                "jual"=>$total_price/1.11,
                "jual_reseller"=>$total_price/1.11,
                "jual_online"=>$total_price/1.11,
                "jual_nppn"=>$total_price,
            );



            $p->addFilter("cabang_id='-1'");
            $p->addFilter("jenis_value in ('jual','jual_reseller','jual_online','jual_nppn')");
            $p->addFilter("produk_id='$prodID'");
            $tempPrice = $p->lookUpAll()->result();
//            cekMErah($this->db->last_query());
            if(count($tempPrice)>0){
//                arrPrintWebs($tempPrice);
                $idUpdate = array();
                $update = array(
                    "status"=>"0",
                    "trash"=>"1",
                    "keterangan"=>"update harga oleh ".$this->session->login["nama"],
                );
                foreach ($tempPrice as $prevPrice){
                    $p->setFilters(array());
                    $p->updateData(array("id"=>$prevPrice->id),$update) or matiHEre("gagal update harga paket");
                    cekHitam($this->db->last_query());
            }
//                matiHere(__LINE__);
        }

            foreach($dataUpdatePrice as $jenis_values =>$value_nilai){
                $dataInsert = array(
                    "jenis"=>"produk",
                    "produk_id"=>$prodID,
                    "cabang_id"=>"-1",
                    "oleh_id"=>$this->session->login['id'],
                    "oleh_nama"=>$this->session->login['nama'],
                    "dtime"=>date("Y-m-d H:i"),
                    "jenis_value"=>$jenis_values,
                    "nilai"=>$value_nilai,
                    "status"=>"1",
                    "trash"=>"0",
                );
                $p->addData($dataInsert);
//                cekHitam($this->db->last_query());
            }


        }
//        arrPrint($total_price);
//        arrPrint($dataUpdatePrice);


        $this->db->trans_complete();
//        matiHere();

        $backLink = $_SESSION['PROPKGED'][$prodID]['backLink'];


        $_SESSION['PROPKGED'][$prodID]['component'] = NULL;
        $_SESSION['PROPKGED'][$prodID]['backLink'] = NULL;

//        matihere();

        unset($_SESSION['PROPKGED']);


        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                        title:'Modify Product ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: " . '$' . "('<div></div>').load('" . $backLink . "'),
                                        draggable:true,
                                        closable:true,
                                        });";

        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "ProductKompositEditor/edit?attached=1&sID=$prodID&backlink=$backLink');";

        echo "<html>";
        echo "<head>";
        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        echo "</head>";
        echo "<body onload=\"$actionTarget\">";
        echo "</body>";
    }
}