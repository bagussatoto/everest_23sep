<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 14/11/18
 * Time: 19:19
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function callMenuLeftWebs()
{
    // $folders = "http://demo.mayagrahakencana.com/debug/ci_san/eusvc/products/seefolders";

    $ci =& get_instance();
    $ci->load->library("LayoutWebs");
    $p = new LayoutWebs();
    // arrPrint($_SESSION);
    $halaman = $ci->uri->segment(2) === false ? "index" : $ci->uri->segment(2);
    // cekHitam("$halaman");
    switch ($halaman) {
        default:
            $sesWebs_produkFolders = $_SESSION['webs']['produk_folders'];
            if (isset($sesWebs_produkFolders)) {
                $data['folder'] = $sesWebs_produkFolders;
                // cekHijau();
            }
            else {
                // cekMerah();
                $ci->load->library('curl');
                $ci->load->config('heApi');
                $folders = $ci->config->item('heApi')['webs']['produk_folders'];
                $data['folder'] = json_decode($ci->curl->simple_get($folders));
            }


            $var = "<li class='nav-item has-treeview menu-open'>";
            $var .= "<a href='#' class='nav-link active'>";
            $var .= "<i class='nav-icon fa fa-dashboard'></i>";
            $var .= "<p>";
            $var .= "Kategori";
            $var .= "<i class='right fa fa-angle-left'></i>";
            $var .= "</p>";
            $var .= "</a>";
            $var .= "<ul class='nav nav-treeview'>";
            foreach ($data['folder'] as $id => $folder) {

                $var .= "<li class='nav-item'>";
                $var .= "<a href='" . base_url() . "Home/index/f/$id' class='nav-link'>";
                $var .= "<i class='fa fa-circle-o nav-icon'></i>";
                $var .= "<p class='text-capitalize'>" . strtolower($folder) . "</p>";
                $var .= "</a>";
                $var .= "</li>";
            }
            $var .= "</ul>";
            $var .= "</li>";
            break;
        case "scaner":
            $var = "";
            break;
    }
    $arrMenus = array(
        "xx" => array(
            "label" => "Destroy session",
            "icon" => "fa-start",
            "link" => "Home/clearSessionWebs",
        ),
        "xxx" => array(
            "label" => "reload session",
            "icon" => "start",
            "link" => "Cli/reloadSessionWebs",
        ),
    );
    $var .= $p->menuLeft($arrMenus);
    return $var;
}

function callMenuTopWebs()
{
    $ci =& get_instance();
    $ci->load->library("LayoutWebs");
    $p = new LayoutWebs();
    $dataCarts = isset($_SESSION['webs']['cart']) ? $_SESSION['webs']['cart'] : array();

    $var = "<li class='nav-item dropdown'>";
    $var .= "<a class='nav-link' data-toggle='dropdown' href='#'>";
    $var .= "<i class='fa fa-shopping-cart'></i>";
    $var .= "<span class='badge badge-danger navbar-badge' id='cart_item_n'></span>";
    $var .= "</a>";

    $var .= "<div class='dropdown-menu dropdown-menu-lg dropdown-menu-right'>";

    $var .= "<span id='shopingcart_mini'>";
    $var .= $p->shopingCartMini($dataCarts);
    $var .= "</span>";


    $var .= "<a href='" . base_url() . "Home/keranjang' class='dropdown-item dropdown-footer'>Lihat Keranjang</a>";
    // $var .= "<a href='" . base_url() . "Home/clearShopingcart' class='dropdown-item dropdown-footer'>xxxxx</a>";
    $var .= "</div>";
    $var .= "</li>";

    return $var;
}

