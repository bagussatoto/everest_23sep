<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 6/10/2018
 * Time: 3:28 PM
 */
class _productEditor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addItem()
    {
        $prodID = $_GET['sID'];
        $id = $_GET['bID'];
        $dock = isset($_GET['dock']) ? $_GET['dock'] : "";
        // arrPrint($_GET);
        $this->load->model("Mdls/" . "MdlSupplies");
        $o = new MdlSupplies();
        $tmp = $o->lookupByID($id)->result();

        if (!isset($_SESSION['PROED'][$prodID])) {
            $_SESSION['PROED'][$prodID] = array();
        }
        if (!isset($_SESSION['PROED'][$prodID]['component'])) {
            $_SESSION['PROED'][$prodID]['component'] = array();
        }

        if (!array_key_exists($id, $_SESSION['PROED'][$prodID]['component'])) {
            $_SESSION['PROED'][$prodID]['component'][$id] = array(
                "name" => $tmp[0]->nama,
                "satuan" => $tmp[0]->satuan,
                "jml" => 1,
            );
        }
        else {

            if (isset($_GET['jml'])) {
                $_SESSION['PROED'][$prodID]['component'][$id]['jml'] = $_GET['jml'];
            }
            if (isset($_GET['harga'])) {
                if (!isset($_SESSION['PROED'][$prodID]['component'][$id]['harga_old'])) {
                    $_SESSION['PROED'][$prodID]['component'][$id]['harga_old'] = $_SESSION['PROED'][$prodID]['component'][$id]['harga'];
                }
                $_SESSION['PROED'][$prodID]['component'][$id]['harga'] = $_GET['harga'];
            }
        }

        // region build session standart production cost
        if (!isset($_SESSION['PROED'][$prodID]['cost'])) {

            $this->load->model("Mdls/MdlProdukRakitanBiaya");

            $pr = new MdlProdukRakitanBiaya();

            $condite = "produk_id='$prodID'";
            $tmpPr = $pr->lookupByCondition($condite)->result();
//            showLast_query("lime");
            if (sizeof($tmpPr) > 0) {

                foreach ($tmpPr as $item) {

                    $_SESSION['PROED'][$prodID]['cost'][$item->biaya_id] = array(
                        "name" => $item->biaya_nama,
                        "value" => $item->nilai,
                    );
                }
            }
            else {
                unset($_SESSION['PROED'][$prodID]);

                $alert = array(
                    "type" => "warning",
                    "html" => "Standart Cost untuk produk ini belum disetUp, silahkan menghubungi entry data <b>(holding)</b><br>dari menu <span class='text-primary'>data/602 Standart Cost By Products</span>",
                );
                echo swalAlert($alert);
            }

        }

        // arrPrint($tmpPr);
        // endregion build session fix production cost

        $backLink = isset($_SESSION['PROED'][$prodID]['backLink']) ? $_SESSION['PROED'][$prodID]['backLink'] : "#";

        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "ProductEditor/edit?attached=1&sID=$prodID&backlink=$backLink');";

//        echo "<html>";
//        echo "<head>";
//        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
//        echo "</head>";
        /*---------------------------------------------------
         * saat yg dikirim jml nya modal gak perlu reload
         * -----------------------------------------------*/

        if ($dock != 1) {


            if (!isset($_GET['jml'])) {
//
//                echo "<body onload=\"$actionTarget\">";
//                echo "</body>";
//
                echo "<script>
                if($('#result2').length>0){
                    document.getElementById('result2').contentWindow.location.reload();
                    top.close_holdon()
                }
//                else{
//                    //location.reload()
//                }
//
            </script>";
            }
        }
        else {
            topReload();
        }


    }

    public function removeItem()
    {
        $prodID = $this->uri->segment(3);
        $id = $this->uri->segment(4);


        if (array_key_exists($id, $_SESSION['PROED'][$prodID]['component'])) {
            $_SESSION['PROED'][$prodID]['component'][$id] = null;
            unset($_SESSION['PROED'][$prodID]['component'][$id]);
        }


        $backLink = $_SESSION['PROED'][$prodID]['backLink'];
//        $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(
//                                   {
//                                        title:'Modify Product ',
////                                        size: BootstrapDialog.SIZE_WIDE,
//                                        cssClass: 'edit-dialog',
//                                        message: ".'$'."('<div></div>').load('".$backLink."'),
//                                        draggable:true,
//                                        closable:true,
//                                        });";

        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "ProductEditor/edit?attached=1&sID=$prodID&backlink=$backLink');";

        echo "<html>";
        echo "<head>";
        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        echo "</head>";
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
        echo "<script>
                if($('#result2').length>0){
                    document.getElementById('result2').contentWindow.location.reload();
                    top.close_holdon()
                }
                </script>";
    }

    public function search()
    {
//        $_GET['dock'] = 1;
//        $prodID = $this->uri->segment(3);
        $prodID = $_GET['sID'];
        $strDock = isset($_GET['dock']) ? "&dock=$_GET[dock]" : "";
//        cekLime($strDock);
        $key = isset($_GET['key']) ? $_GET['key'] : "";
        $this->load->model("Mdls/" . "MdlSupplies");
        $o = new MdlSupplies();
        $tmp = $o->lookupByKeyword($key)->result();
//        cekmerah($this->db->last_query());
        if (sizeof($tmp) > 0) {
            echo "<ul class='list-group'>";
            foreach ($tmp as $row) {
                echo "<li class='list-group-item'>";
                echo "<a href=# onClick =\"top.$('#result').load('" . base_url() . get_class($this) . "/addItem?sID=$prodID&bID=" . $row->id . "$strDock');\">";
                echo $row->nama;
                echo "</a>";
                echo "</li class='list-group-item'>";
            }
            echo "</ul class='list-group'>";
        }
    }


    public function viewCart()
    {
        $prodID = $this->uri->segment(3);
        if (isset($_SESSION['PROED'][$prodID]['entries']) && sizeof($_SESSION['PROED'][$prodID]['entries']) > 0) {
            echo("<ul class='list-group'>");
            $cnt = 0;
            $totalBiaya = 0;
            foreach ($_SESSION['PROED'][$prodID]['entries'] as $id => $row) {
                $cnt++;
                echo("<li class='list-group-item'>");
                echo("<div class='row'>");
                echo("<div class='col-sm-1'>");
                echo("<a class='text-center' href=# onclick=\"top.$('#result').load('" . base_url() . get_class($this) . "/removeItem/$prodID/" . $id . "');\"><span class='glyphicon glyphicon-remove'></span></a>");
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

    //-------------
    public function searchRakitan()
    {

        $prodID = $_GET['sID'];
        $strDock = isset($_GET['dock']) ? "&dock=$_GET[dock]" : "";

        $key = isset($_GET['key']) ? $_GET['key'] : "";
        $this->load->model("Mdls/" . "MdlProdukRakitan");
        $o = new MdlProdukRakitan();
        $o->addFilter("id<>$prodID");
        $tmp = $o->lookupByKeyword($key)->result();
//        cekmerah($this->db->last_query());
        if (sizeof($tmp) > 0) {
            echo "<ul class='list-group'>";
            foreach ($tmp as $row) {
                echo "<li class='list-group-item'>";
                echo "<a href=# onClick =\"top.$('#result').load('" . base_url() . get_class($this) . "/extrackItem?sID=$prodID&bID=" . $row->id . "$strDock');\">";
                echo $row->kode . " " . $row->nama;
                echo "</a>";
                echo "</li class='list-group-item'>";
            }
            echo "</ul class='list-group'>";
        }
    }

    public function extrackItem()
    {
        $prodID = $_GET['sID']; // produk rakitan ID
        $id = $_GET['bID']; // produk rakitan ID sebagai sumber copy....
        $dock = isset($_GET['dock']) ? $_GET['dock'] : "";
//        arrPrint($_GET);
        $this->load->model("Mdls/MdlProdukKomposisi_and_cost");
        $o = new MdlProdukKomposisi_and_cost();
        $o->addFilter("produk_id='$id'");
        $tmp = $o->lookupAll()->result();
//        showLast_query("biru");
//        arrPrintWebs($tmp);

        if (isset($_SESSION['PROED'])) {
            $_SESSION['PROED'] = NULL;
            unset($_SESSION['PROED']);
        }
        if (!isset($_SESSION['PROED'][$prodID])) {
            $_SESSION['PROED'][$prodID] = array();
        }
        if (!isset($_SESSION['PROED'][$prodID]['component'])) {
            $_SESSION['PROED'][$prodID]['component'] = array();
        }

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmpSpec) {
                if ($tmpSpec->jenis == "produk") {
                    $anu[$prodID]['component'][$tmpSpec->produk_dasar_id] = array(
                        "name" => $tmpSpec->produk_dasar_nama,
                        "satuan" => $tmpSpec->satuan_nama,
                        "jml" => $tmpSpec->jml,
                        "harga" => $tmpSpec->harga,
                    );
                }
                elseif ($tmpSpec->jenis == "biaya") {
                    $anu[$prodID]['cost'][$tmpSpec->produk_dasar_id] = array(
                        "name" => $tmpSpec->produk_dasar_nama,
                        "satuan" => $tmpSpec->satuan_nama,
                        "jml" => $tmpSpec->jml,
                        "value" => $tmpSpec->nilai,
                    );
                }
            }

            $_SESSION['PROED'] = sizeof($anu) > 0 ? $anu : array();
        }


        $pakai_ini = 0;
        if ($pakai_ini == 1) {

            if (!array_key_exists($id, $_SESSION['PROED'][$prodID]['component'])) {
                $_SESSION['PROED'][$prodID]['component'][$id] = array(
                    "name" => $tmp[0]->nama,
                    "satuan" => $tmp[0]->satuan,
                    "jml" => 1,
                );
            }
            else {
                if (isset($_GET['jml'])) {
                    $_SESSION['PROED'][$prodID]['component'][$id]['jml'] = $_GET['jml'];
                }
            }


            // region build session standart production cost
            if (!isset($_SESSION['PROED'][$prodID]['cost'])) {

                $this->load->model("Mdls/MdlProdukRakitanBiaya");

                $pr = new MdlProdukRakitanBiaya();

                $condite = "produk_id='$prodID'";
                $tmpPr = $pr->lookupByCondition($condite)->result();
//            showLast_query("lime");
                if (sizeof($tmpPr) > 0) {

                    foreach ($tmpPr as $item) {

                        $_SESSION['PROED'][$prodID]['cost'][$item->biaya_id] = array(
                            "name" => $item->biaya_nama,
                            "value" => $item->nilai,
                        );
                    }
                }
                else {
                    unset($_SESSION['PROED'][$prodID]);

                    $alert = array(
                        "type" => "warning",
                        "html" => "Standart Cost untuk produk ini belum disetUp, silahkan menghubungi entry data <b>(holding)</b><br>dari menu <span class='text-primary'>data/602 Standart Cost By Products</span>",
                    );
                    echo swalAlert($alert);
                }

            }


            // endregion build session fix production cost
        }


//        mati_disini(":: ==== ::");

        $backLink = isset($_SESSION['PROED'][$prodID]['backLink']) ? $_SESSION['PROED'][$prodID]['backLink'] : "#";
        $actionTarget = "top.$('#result2').attr('src', '" . base_url() . "ProductEditor/edit?attached=1&sID=$prodID&backlink=$backLink');";

        echo "<html>";
        echo "<head>";
        echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        echo "</head>";

        /*---------------------------------------------------
         * saat yg dikirim jml nya modal gak perlu reload
         * -----------------------------------------------*/
//        echo "<body onload=\"$actionTarget\">";
//        echo "</body>";
        echo "<script>
                if($('#result2').length>0){
                    document.getElementById('result2').contentWindow.location.reload();
                    top.close_holdon()
                }
//                else{
//                    //location.reload()
//                }
//
            </script>";
    }
}