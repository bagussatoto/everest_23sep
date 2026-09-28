<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 6/10/2018
 * Time: 3:28 PM
 */
class _productPkgEditor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addItem()
    {
        $prodID = $_GET['sID'];
        $id = $_GET['bID'];
        $selectedPlace = ($_SESSION['login']['cabang_id']);
        $this->load->model("Mdls/" . "MdlProduk");
        $this->load->model("Mdls/" . "MdlHargaProduk");
        $this->load->model("Mdls/MdlFifoProdukJadi");

        $o = new MdlProduk();
        $pp = new MdlHargaProduk();
        $ff = new MdlFifoProdukJadi();

        $tmp = $o->lookupByID($id)->result();

        $pp->addFilter("cabang_id='$selectedPlace'");
//        $pp->addFilter("jenis_value='jual'");
        $pp->addFilter("jenis_value in ('jual', 'hpp')");
        $tmpPriceBahan = $pp->lookupAll()->result();
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

        if (!isset($_SESSION['PROPKGED'][$prodID])) {
            $_SESSION['PROPKGED'][$prodID] = array();
        }
        if (!isset($_SESSION['PROPKGED'][$prodID]['component'])) {
            $_SESSION['PROPKGED'][$prodID]['component'] = array();
        }

        if (!array_key_exists($id, $_SESSION['PROPKGED'][$prodID]['component'])) {

            $hpp = isset($ffHpp[$id]) ? $ffHpp[$id] : isset($tmpPrice[$id]['hpp']) ? $tmpPrice[$id]['hpp'] : 0;
            $_SESSION['PROPKGED'][$prodID]['component'][$id] = array(
                "name" => $tmp[0]->nama,
                "satuan" => $tmp[0]->satuan,
                "kode" => $tmp[0]->kode,
                "price" => $tmpPrice[$id]['jual'],
                "hpp" => $hpp,
                "jml" => 1,
                "subtotal" => 1 * $tmpPrice[$id]['jual'],
                "subhpp" => 1 * $hpp,

            );
//            cekHitam("masuk sini");
        }
        else {
            if (isset($_GET['jml'])) {
                $_SESSION['PROPKGED'][$prodID]['component'][$id]['jml'] = $_GET['jml'];
                $_SESSION['PROPKGED'][$prodID]['component'][$id]['subtotal'] = $_GET['jml'] * $tmpPrice[$id];
            }
        }
//arrPrint($_SESSION['PROPKGED'][$prodID]['component']);
//        matiHEre("debuging");
        $backLink = isset($_SESSION['PROPKGED'][$prodID]['backLink']) ? $_SESSION['PROPKGED'][$prodID]['backLink'] : "#";
//        matiHere($backLink);
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                        title:'Modify Product ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: " . 'top.$' . "('<div></div>').load('" . $backLink . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "<script>$actionTarget</script>";
        if (isset($_GET['jml'])) {

            echo "<script>
                localStorage.btnKalkulasi = 99999999999999;
            </script>";

        }
        else{
            echo "<script>
                if($('#result2').length>0){
                    document.getElementById('result2').contentWindow.location.reload();
                    top.close_holdon()
                }
                else{
                    //location.reload()
                }
            </script>";
        }


    }

    public function removeItem()
    {
        $prodID = $this->uri->segment(3);
        $id = $this->uri->segment(4);


        if (array_key_exists($id, $_SESSION['PROPKGED'][$prodID]['component'])) {
            $_SESSION['PROPKGED'][$prodID]['component'][$id] = null;
            unset($_SESSION['PROPKGED'][$prodID]['component'][$id]);
        }


        $backLink = $_SESSION['PROPKGED'][$prodID]['backLink'];
        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
                                   {
                                        title:'Modify Product ',
//                                        size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: " . 'top.$' . "('<div></div>').load('" . $backLink . "'),
                                        draggable:true,
                                        closable:true,
                                        });";
//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
//        echo "<script>$actionTarget</script>";
        echo "<script>
        if($('#result2').length>0){
            document.getElementById('result2').contentWindow.location.reload();
            top.close_holdon()
        }
        else{
            location.reload()
        }


        </script>";
    }

    public function search()
    {
//        $prodID = $this->uri->segment(3);
        $prodID = $_GET['sID'];
//arrPrint($_SESSION);
//arrPrint($_SESSION['PROPKGED'][$prodID]['component']);
        $key = isset($_GET['key']) ? $_GET['key'] : "";
        $this->load->model("Mdls/" . "MdlProduk");
//        cekHere($key);

        $lsPrdSes=array();
        if(isset($_SESSION['PROPKGED'][$prodID]['component'])&&sizeof($_SESSION['PROPKGED'][$prodID]['component'])>0){
            foreach($_SESSION['PROPKGED'][$prodID]['component'] as $idPrdSes => $dataPrdSes){

                $lsPrdSes[$idPrdSes] = $dataPrdSes;
            }
        }

        $o = new MdlProduk();
        $tmp = $o->lookupByKeyword($key)->result();
//        cekBiru($this->db->last_query());
        if (sizeof($tmp) > 0) {
            echo "<ul class='list-group'>";
            foreach ($tmp as $row) {
                $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";
                echo "<li class='list-group-item'>";
                echo "<a href=\"javascript:void(0)\" onclick =\"top.$('#result').load('" . base_url() . get_class($this) . "/addItem?sID=$prodID&bID=" . $row->id . "');\">";
                echo $row->nama;
                echo "</a> $check";
                echo "</li class='list-group-item'>";
            }
            echo "</ul class='list-group'>";
        }

        echo "<script>localStorage.lastSearch='$key'</script>";
    }


    public function viewCart()
    {
        $prodID = $this->uri->segment(3);
        if (isset($_SESSION['PROPKGED'][$prodID]['entries']) && sizeof($_SESSION['PROPKGED'][$prodID]['entries']) > 0) {
            echo("<ul class='list-group'>");
            $cnt = 0;
            $totalBiaya = 0;
            foreach ($_SESSION['PROPKGED'][$prodID]['entries'] as $id => $row) {
                $cnt++;
                echo("<li class='list-group-item'>");
                echo("<div class='row'>");
                echo("<div class='col-sm-1'>");
                echo("<a class='text-center' href=\"javascript:void(0)\" onclick=\"top.$('#result').load('" . base_url() . get_class($this) . "/removeItem/$prodID/" . $id . "');\"><span class='glyphicon glyphicon-remove'></span></a>");
                echo("</div class='col-sm-1'>");

                echo("<div class='col-sm-5'>");
                echo("<a href='" . base_url() . "BahanEditor/index/1/id/" . $id . "'>");
                echo($row['name']);
                echo("</a>");
                echo("</div class='col-sm-8'>");
                echo("<div class='col-sm-3'>");
                echo("<div class='input-group'>");
                echo("<input type='hidden' name='counter[]' value='$cnt'>");
                echo("<input type='hidden' name='id[]' value='$id'>");
                echo("<input type='hidden' name='hpp[]' value='" . $row['hpp'] . "' id='hpp_$id'>");
                echo("<input type='text' class='form-control text-right' name='jml[]' value='" . $row['jml'] . "' onkeyup =\"document.getElementById('subtotal_$id').innerHTML=(this.value*document.getElementById('hpp_$id').value);\">");
                echo("<span class='input-group-addon' style='background:#f0f0f0;'>" . $row['satuan'] . "</span>");
                echo("</div class='input-group'>");
                echo("</div class='col-sm-3'>");

                echo("<div class='col-sm-3'>");
                echo("<div class='form-control text-right' style='background:#ffddaa;' id='subtotal_$id'>" . ($row['jml'] * $row['hpp']) . "</div>");
                echo("</div class='col-sm-3'>");

                echo("</div class='row'>");
                echo("</li class='list-group-item'>");
                $totalBiaya += lgBulatkan($row['hpp'] * $row['jml']);
            }
            echo("<li class='list-group-item' style='background:#e5e5e5;'>");
            echo("<div class='row'>");
            echo("<div class='col-sm-8'>total biaya bahan");
            echo("</div class='col-sm-8'>");

            echo("<div class='col-sm-4'>");
            echo("<input type='text' class='form-control text-right' style='color:#dd3300;' value='RP. $totalBiaya' readonly>");

            echo("</div class='col-sm-4'>");
            echo("</li class='list-group-item'>");
            echo("</ul class='list-group'>");
        }
    }
}