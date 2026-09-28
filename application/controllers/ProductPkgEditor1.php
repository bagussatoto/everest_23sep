<?php

/**
 * Created by PhpStorm.
 * User: widi
 * Date: 16/11/18
 * Time: 16:08
 */
class ProductPkgEditor extends CI_Controller
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
        //        $_SESSION['PROPKGED'] = NULL;
        //        unset($_SESSION['PROPKGED']);
        $prodID = $_GET['sID'];

        $this->load->model("Mdls/MdlProdukPaket");
        $this->load->model("Mdls/MdlProdukPkgKomposisi");
        $this->load->model("Mdls/MdlProduk");
        $this->load->model("Mdls/MdlHargaProduk");
        $this->load->model("Mdls/MdlProdukKategori");
        $this->load->model("Mdls/MdlFifoProdukJadi");


        $selectedPlace = ($_SESSION['login']['cabang_id']);
        $o = new MdlProdukPaket();
        $pk = new MdlProdukPkgKomposisi();
        $o2 = new MdlProduk();
        $pp = new MdlHargaProduk();
        $pcat = new MdlProdukKategori();
        $ff = new MdlFifoProdukJadi();
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
        $pp->addFilter("cabang_id='$selectedPlace'");
//        $pp->addFilter("jenis_value='jual'");
        $pp->addFilter("jenis_value in ('jual', 'hpp')");
        $tmpPriceBahan = $pp->lookupAll()->result();
//        arrPrint($tmpPriceBahan);
        $tmpPrice = array();
        if (sizeof($tmpPriceBahan) > 0) {
            foreach ($tmpPriceBahan as $priceData) {
//                $tmpPrice[$priceData->produk_id] = $priceData->nilai;
                $tmpPrice[$priceData->produk_id][$priceData->jenis_value] = $priceData->nilai;
            }
        }
        //------------------------------------------------------

        $ff->addFilter("cabang_id='$selectedPlace'");
        $ffTmp = $ff->lookupAll()->result();
        $ffHpp = array();
        if (sizeof($ffTmp) > 0) {
            foreach ($ffTmp as $ffSpec) {
                $ffHpp[$ffSpec->produk_id] = $ffSpec->hpp;
            }
        }

        //------------------------------------------------------

        $cacheBahan = array();
        if (sizeof($tmpBahan) > 0) {
            foreach ($tmpBahan as $rowB) {
                $cacheBahan[$rowB->id] = array(
                    "name" => $rowB->nama,
                    "kode" => $rowB->kode,
                    "no_part" => $rowB->no_part,
//                    "kategori_nama" => $rowB->kategori_nama,
                    "kategori_nama" => isset($produkCategori[$rowB->kategori_id]) ? $produkCategori[$rowB->kategori_id] : $rowB->kategori_nama,
                    "label" => $rowB->label,
                    "satuan" => $rowB->satuan,

                );
            }
        }

        if (!isset($_SESSION['PROPKGED'][$prodID])) {
            $_SESSION['PROPKGED'][$prodID] = array();
        }
        if (!isset($_SESSION['PROPKGED'][$prodID]['component'])) {
            $_SESSION['PROPKGED'][$prodID]['component'] = array();
        }
        $_SESSION['PROPKGED'][$prodID]['backLink'] = isset($_GET['backLink']) ? unserialize(base64_decode($_GET['backLink'])) : "";
        if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) == 0) {
            if (sizeof($components) > 0) {
                foreach ($components as $row) {
                    if (isset($cacheBahan[$row->produk_dasar_id])) {
                        $hpp = isset($ffHpp[$row->produk_dasar_id]) ? $ffHpp[$row->produk_dasar_id] : isset($tmpPrice[$row->produk_dasar_id]['hpp']) ? $tmpPrice[$row->produk_dasar_id]['hpp'] : 0;
                        $_SESSION['PROPKGED'][$prodID]['component'][$row->produk_dasar_id] = array(
                            "name" => $cacheBahan[$row->produk_dasar_id]['name'],
                            "satuan" => $cacheBahan[$row->produk_dasar_id]['satuan'],
                            "jml" => $row->jml,
                            "price" => $tmpPrice[$row->produk_dasar_id]['jual'],
                            "hpp" => $hpp,
                            "subtotal" => $row->jml * $tmpPrice[$row->produk_dasar_id]['jual'],
                            "subhpp" => $row->jml * $hpp,
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

        //        arrPrint($_SESSION['PROPKGED'][$prodID]['component']);
        $headers = array(
            "no" => "class='text-center bg-grey-2 text-uppercase'",
            "Produk" => "class='text-center bg-grey-2 text-uppercase'",
            "Code" => "class='text-center bg-grey-2 text-uppercase'",
            "Part Number" => "class='text-center bg-grey-2 text-uppercase'",
            "Category" => "class='text-center bg-grey-2 text-uppercase'",
            "Qty" => "class='text-center bg-grey-2 text-uppercase'",
            "uom" => "class='text-center bg-grey-2 text-uppercase'",
            "hpp" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",
            "price" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",
            "subhpp" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",
            "subtotal" => "chid='k2hsby3[]' class='hidden text-center bg-grey-2 text-uppercase'",
            "rem" => "class='text-center bg-grey-2 text-uppercase'",
        );
//arrPrint($_SESSION['PROPKGED']);

        $content = "";
        if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            $btnAttr = "";
            $content .= "<table class='table table-striped' style='border: 0px solid red;background-color: transparent;'>";
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
            foreach ($_SESSION['PROPKGED'][$prodID]['component'] as $id => $eSpec) {
//                arrPrint($eSpec);
                $no++;
                $cTab++;
                $totalValue += $eSpec['subtotal'];
                $totalHpp += $eSpec['hpp'];

                $content .= "<tr>";
                $content .= "<td class='text-right valign-m' valign='middle'>$no</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= $eSpec['name'];
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= $eSpec['kode'];
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= $eSpec['no_part'];
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= $eSpec['kategori_nama'];
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='width: 70px;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<input type='number' onclick=\"this.select()\" tabindex='$cTab' name='jml[]' value='" . $eSpec['jml'] . "'
                    onblur =\"if(" . $eSpec['jml'] . "!=this.value){ top.$('#result').load('" . base_url() . "_productPkgEditor/addItem?sID=$prodID&bID=$id&jml='+this.value, null, function(){ $('#btnKalkulasi').removeClass('hidden');$('#btnProcess').attr('onclick','javascript:void(0)');$('#btnProcess').prop('disabled',true);$('#btnProcess').removeClass('btn-info'); }); } else {return false;}\"
                    class='form-control text-right'>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . $eSpec['satuan'] . "</span>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                //-----------------------------------------------------------------
                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['hpp']) ? formatField("harga", $eSpec['hpp']) : 0 . "</span>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['price']) ? formatField("harga", $eSpec['price']) : 0 . "</span>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['subhpp']) ? formatField("harga", $eSpec['subhpp']) : 0 . "</span>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span style='text-transform: uppercase;'>" . isset($eSpec['subtotal']) ? formatField("harga", $eSpec['subtotal']) : 0 . "</span>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td class='text-center valign-m'>";
                $content .= "<a class='text-red' href=\"javascript:void(0)\" onclick=\"top.$('#result').load('" . base_url() . "_productPkgEditor/removeItem/$prodID/" . $id . "');\"><span class='glyphicon glyphicon-remove'></span></a>";
                $content .= "</td>";

                //-----------------------------------------------------------------

                $content .= "</tr>";

            }

            //region subtotal bawah
            $colspan = sizeof($headers) - 3;
            $content .= "<tr>";
            //--------------------------------------
            $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;' colspan='$colspan'>";
            $content .= "<span style='text-transform: uppercase;'>total</span>";
            $content .= "</td>";
            //--------------------------------------
            $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
            $content .= "<span style='text-transform: uppercase;'>" . formatField("harga", $totalHpp) . "</span>";
            $content .= "</td>";
            //--------------------------------------
            $content .= "<td chid='k2hsby3[]' class='hidden' style='vertical-align: middle;padding: 2px;margin: 0px;'>";
            $content .= "<span style='text-transform: uppercase;'>" . formatField("harga", $totalValue) . "</span>";
            $content .= "</td>";
            //--------------------------------------
            $content .= "</tr>";

            //endregion

            if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) != sizeof($components)) {
                $content .= "<tr class='text-center text-bold'>";
                $content .= "<td id='tdWarning' colspan='5' style='background-color: yellow;'>";
                $content .= "<span class='blink text-renggang-5'>Perubahan Components belum disimpan.</span>";
                $content .= "</td>";
                $content .= "</tr>";
            }


            $content .= "</table class='table'>";
        }
        else {
            $content .= "<div class='row text-center' style='border: 0px solid green;'>";
            $content .= "<h2><small>Komposisi paket </small><p class='text-red'>" . $oProp[0]->nama . "</p> <small>belum ditentukan</small></h2>";
            $content .= "<p class='text-danger'>Silahkan pilih produk yang diperlukan dari kolom sebelah kiri</p>";
            $content .= "</div>";

            $btnAttr = "disabled";
        }


        $anu = array(
            "mode" => "edit_paket",
            "content" => $content,
            //            "thisPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2)
        );
        $data = array(
            "mode" => "edit_paket",
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


        $this->load->model("Mdls/" . 'MdlProdukPkgKomposisi');
        $pk = New MdlProdukPkgKomposisi();


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
        if (sizeof($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            foreach ($_SESSION['PROPKGED'][$prodID]['component'] as $bahanID => $eSpec) {
                $arrData = array(
                    "produk_id" => $prodID,
                    "produk_nama" => "",
                    "produk_dasar_id" => $bahanID,
                    "produk_dasar_nama" => $eSpec['name'],
                    "satuan_nama" => $eSpec['satuan'],
                    "jml" => $eSpec['jml'],
                );
                $pk->addData($arrData);
//                cekHere($this->db->last_query());
            }
        }


        $this->db->trans_complete();


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

        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "ProductPkgEditor/edit?attached=1&sID=$prodID&backlink=$backLink');";

        echo "<html>";
        echo "<head>";
        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        echo "</head>";
        echo "<body onload=\"$actionTarget\">";
        echo "</body>";
    }
}