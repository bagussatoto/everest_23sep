<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 3:20 PM
 */
class Home extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
        $this->cabang_id = CB_ID_PUSAT;
        // unset($_SESSION['webs']);
        // $this->harga="http://demo.mayagrahakencana.com/debug/ci_san/eusvc/products/whatisprice/59/-1/jual";
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->library('MobileDetect');
        $this->load->helper('url');

        $this->load->config('heApi');
        $heApi = $this->config->item('heApi');
        $apiWebs = $heApi['webs'];
        // arrPrint($apiWebs);
        $this->produk = $apiWebs['produk_datas'];
        $this->total = $apiWebs['produk_jml_total'];
        $this->folder = $apiWebs['produk_folders'];
        $this->totalFolder = $apiWebs['produk_folder_jml_total'];
        $this->produkFolder = $apiWebs['produk_folder_datas'];
        $this->produkHarga = $apiWebs['produk_hargas'];
        $this->postScart = $apiWebs['post_shopingcart'];
        $this->ipaddShope = $apiWebs['ipadd_showrom'];

        $this->ipadd = $_SERVER['REMOTE_ADDR'];
        // mati_disini(__LINE__);
        $ipaddShope = json_decode($this->curl->simple_get($this->ipaddShope));
        $logins = login_webs();
        // arrPrintWebs($_SESSION['webs']);
        if (sizeof($logins) > 0) {
            // arrPrintWebs($logins);
            // cekHitam("login");
        }
        else {
            // cekHitam("polos");
            // arrPrintWebs($logins);
            if ($ipaddShope != $this->ipadd) {
                echo lgShowWarning("silahkan login", "Toko belum buka");

                topRedirect(base_url() . "Login/webs");

                mati_disini($this->ipadd . " $ipaddShope **** ($this->ipaddShope");
            }
        }
        // cekHitam("$ipaddShope == " . $this->ipadd);
        $this->load->model("Mdls/MdlProdukWebs");
        $pr = new MdlProdukWebs();
        $this->produks = $pr->lookupAllWebs();

        if (!isset($_SESSION['webs']['produk_folders'])) {
            $data['folder'] = json_decode($this->curl->simple_get($this->folder));
            $_SESSION['webs']['produk_folders'] = $data['folder'];
        }
        else {
            $data['folder'] = $this->session->webs['produk_folders'];
        }

        if (!isset($_SESSION['webs']['produk_hargas'])) {
            // cekHere(__LINE__);
            $data['harga'] = json_decode($this->curl->simple_get($this->produkHarga . "/" . $this->cabang_id . ""));
            $_SESSION['webs']['produk_hargas'] = $data['harga'];
        }
        else {
            // cekMerah(__LINE__);
            $data['harga'] = $this->session->webs['produk_hargas'];
        }
    }

    // function index(){
    //     $data['datakontak'] = json_decode($this->curl->simple_get($this->API.'/kontak'));
    //     $this->load->view('kontak/list',$data);
    // }
public function index(){

        mati_disini();
}
    public function home()
    {
        $md = new MobileDetect();

        $mbl = $md->isMobile();
        // if (isset($_GET['q'])) {
        //
        //     $data_limit = 12;
        //     $pages = 1;
        // }
        $cabang_id = $this->cabang_id;
        $qstring = isset($_GET['q']) ? "/" . $_GET['q'] : "";
        $folder_id = $this->uri->segment(3) == 'f' ? $this->uri->segment(4) : "";
        $filter = $folder_id > 0 ? "folder" : "all";
        // unset($_SESSION['webs']);
        if (!isset($_SESSION['webs']['produk_folders'])) {
            $data['folder'] = json_decode($this->curl->simple_get($this->folder));
            $_SESSION['webs']['produk_folders'] = $data['folder'];
        }
        else {
            $data['folder'] = $this->session->webs['produk_folders'];
        }

        if (!isset($_SESSION['webs']['produk_hargas'])) {
            // cekHere(__LINE__);
            $data['harga'] = json_decode($this->curl->simple_get($this->produkHarga . "/$cabang_id"));
            $_SESSION['webs']['produk_hargas'] = $data['harga'];
        }
        else {
            // cekMerah(__LINE__);
            $data['harga'] = $this->session->webs['produk_hargas'];
        }

        // arrPrint($this->session->webs);
        // arrPrint($_SESSION);
        // mati_disini();
        // cekHijau("$folder_id " . $this->uri->segment(3));
        switch ($filter) {
            case "folder":
                // cekHijau("$mbl");
                $data['total'] = json_decode($this->curl->simple_get($this->totalFolder . $folder_id . $qstring));

                $folder_nama = $data['folder']->$folder_id;
                $data_total = $data['total'];
                $data_limit = 12;
                $jmlPage = ceil(($data_total / $data_limit));
                $pages = $this->uri->segment(5) > 1 ? $this->uri->segment(5) : 1;

                $data['produk'] = json_decode($this->curl->simple_get($this->produkFolder . $folder_id . "/" . $data_limit . "/$pages" . $qstring));

                $targetPages = base_url() . get_class($this) . "/index/f/$folder_id";
                break;
            default:
                // cekHijau("$mbl");
                $data['total'] = json_decode($this->curl->simple_get($this->total . $qstring));
                $folder_nama = "";
                $data_total = $data['total'];
                $data_limit = 12;
                $jmlPage = ceil(($data_total / $data_limit));
                $pages = $this->uri->segment(3) > 1 ? $this->uri->segment(3) : 1;
                // cekHitam($this->produk . $data_limit . "/$pages" . $qstring);

                $data['produk'] = json_decode($this->curl->simple_get($this->produk . $data_limit . "/$pages" . $qstring));

                $targetPages = base_url() . get_class($this) . "/index";
                break;
        }


        // region paging
        $pageStr = "";
        if ($data_total > 0) {

            $qs = isset($_GET['q']) ? "?q=$_GET[q]" : "";
            // $jmlPage = ceil(($data_total / $data_limit));
            // $pages = $this->uri->segment(3) > 1 ? $this->uri->segment(3) : 1;
            // $targetPages = base_url() . get_class($this) . "/index";

            if ($mbl == 1) {
                $i = 0;
                $lastpage = $jmlPage;
                $lpm1 = $lastpage - 1;
                $counter = $i;
                $adjacents = 2;

                $prev = $pages - 1;
                $next = $pages + 1;
                $prevStr = "Prev";
                $nextStr = "Next";
                $tampil_0 = 7;
                $tampil_1 = 5;
                $tampil_3 = 2;
                $psize = "pagination-sm";
            }
            else {

                $i = 0;
                $lastpage = $jmlPage;
                $lpm1 = $lastpage - 1;
                $counter = $i;
                $adjacents = 3;

                $prev = $pages - 1;
                $next = $pages + 1;
                $prevStr = "Previous";
                $nextStr = "Next";
                $tampil_0 = 7;
                $tampil_1 = 5;
                $tampil_3 = 4;
                $psize = "";
            }

            // cekHitam("$pages $counter lpm $lpm1 // $lastpage");

            if ($lastpage > 1) {
                $pageStr .= "<ul class='pagination no-margin $psize'>";
                // $pageStr .= $xlsxStr;
                // previous button
                if ($pages > 1) {
                    $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$prev$qs'>$prevStr</a></li>";
                }
                else {
                    $pageStr .= "<li class='page-item disabled'><span class='page-link'>$prevStr</span></li>";
                }

                // pages button
                if ($lastpage < $tampil_0 + ($adjacents * 2)) //not enough pages to bother breaking it up
                {
                    // cekHijau(__LINE__);
                    for ($counter = 1; $counter <= $lastpage; $counter++) {
                        if ($counter == $pages) {
                            $pageStr .= "<li class='page-item active'><span class='page-link'>$counter</span></li>";
                        }
                        else {
                            $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$counter$qs'>$counter</a></li>";
                        }
                    }
                }
                elseif ($lastpage > $tampil_1 + ($adjacents * 2)) {
                    // cekHijau(__LINE__);
                    //close to beginning; only hide later pages
                    if ($pages < 1 + ($adjacents * 2)) {
                        for ($counter = 1; $counter < $tampil_3 + ($adjacents * 2); $counter++) {
                            if ($counter == $pages) {
                                $pageStr .= "<li class='page-item active'><span class='page-link'>$counter</span></li>";
                            }
                            else {
                                $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$counter$qs'>$counter</a></li>";
                            }
                        }
                        $pageStr .= "<li class='page-item'><span class='page-link'>...</span></li>";
                        $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$lpm1$qs'>$lpm1</a></li>";
                        $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$lastpage$qs'>$lastpage</a></li>";
                    }
                    //in middle; hide some front and some back
                    elseif ($lastpage - ($adjacents * 2) > $pages && $pages > ($adjacents * 2)) {
                        $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/1$qs'>1</a></li>";
                        $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/2$qs'>2</a></li>";
                        $pageStr .= "<li class='page-item'><span class='page-link'>...</span></li>";

                        for ($counter = $pages - $adjacents; $counter <= $pages + $adjacents; $counter++) {
                            if ($counter == $pages) {
                                $pageStr .= "<li class='page-item active'><span class='page-link'>$counter</span></li>";
                            }
                            else {
                                $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$counter$qs'>$counter</a></li>";
                            }
                        }
                        $pageStr .= "<li class='page-item'><span class='page-link'>...</span></li>";
                        $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$lpm1$qs'>$lpm1</a></li>";
                        $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$lastpage$qs'>$lastpage</a></li>";
                    }
                    //close to end; only hide early pages
                    else {
                        $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/1$qs'>1</a></li>";
                        $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/2$qs'>2</a></li>";
                        $pageStr .= "<li class='page-item'><span class='page-link'>...</span></li>";

                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                            if ($counter == $pages) {
                                $pageStr .= "<li class='page-item active'><span class='page-link'>$counter</span></li>";
                            }
                            else {
                                $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$counter$qs'>$counter</a></li>";
                            }
                        }
                    }
                }

                // nest button
                if ($pages < $counter - 1) {
                    $pageStr .= "<li class='page-item'><a class='page-link' href='$targetPages/$next$qs'>$nextStr</a></li>";
                }
                else {
                    $pageStr .= "<li class='page-item disabled'><span class='page-link'>$nextStr</span></li>";
                }

                $pageStr .= "</ul>";

            }
            else {
                // kosong
            }
        }
        // endregion paging
        // arrPrintWebs($data);
        // mati_disini();
        $data = array(
            "mode"     => "index",
            // "errMsg"      => $this->session->errMsg,
            "title"    => "Katalog Produk",
            "subTitle" => $folder_nama,
            "content"  => $data,
            // "yLabels"     => $this->y['entries'],
            // "xLabels"     => $this->x['entries'],
            // "zLabels"     => $this->priceConfig,
            // "values"      => $this->z['entries'],
            // "history"     => $this->z['listHistory'],
            // "formTarget"  => $formTarget,
            // "buttonLabel" => $buttonLabel,
            // "yHeader"     => ucwords($this->iy),
            // "self"        => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "defaultKey"  => $this->q != null ? $this->q : "type here to search " . $this->iy . "..",
            // "startPage"   => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->iy . "/" . $this->ix . "/" . $this->iz,
            // "attached"    => $attached,
            "pageStr"  => $pageStr,
            // "pageOffside" => $this->page_offside,
            // "pageLimit"   => $this->limit_per_page,
        );
        //        arrPrint($data);
        $this->load->view('home', $data);

    }

    public function save()
    {
        //        print_r($_POST);
        //        print_r($this->existingValues);die();
        $insertList = array();
        $updateList = array();
        $oldUpdateList = array();

        //arrPrint($_POST);
        //arrPRint($this->y['entries']);
        //arrPRint($this->x['entries']);
        //arrPRint($this->z['rawEntries']);
        //arrPrint($this->priceConfig);
        //        arrPrint($this->y['entries']);
        $arrPostData = array();
        foreach ($this->y['entries'] as $yID => $yName) {
            foreach ($this->x['entries'] as $xID => $xName) {

                foreach ($this->priceConfig as $zID => $zName) {
                    $pointName = "value_" . $yID . "_" . $xID . "_" . $zID;
                    if (isset($_POST[$pointName])) {
                        $varName = isset($_POST[$pointName]) ? $_POST[$pointName] : 0;
                        if (isset($this->z['rawEntries'][$yID][$xID])) {
                            $compareOldData = $this->z['rawEntries'][$yID][$xID];
                        }
                        else {
                            $compareOldData = array();
                        }

                        $arrPostData[$yID][$xID][$zID] = $varName;
                        //                        arrPrint($compareOldData);
                        //                        echo "<br>";
                        //                        arrPrint($arrCompare);
                        //                        echo $pointName . "*$zID ||$zName*";
                        //                        if (isset($this->existingValues[$yID][$xID][$zID])) {
                        //                            echo "item existed";
                        //                            //==updateList ditambah
                        //                            $updateList[] = array(
                        //                                "where"  => array(
                        //                                    "jenis"       => $this->iy,
                        //                                    "jenis_value" => $zID,
                        //                                    "produk_id"   => $yID,
                        //                                    "cabang_id"   => $xID,
                        //                                    //                                "nilai"=>$varName,
                        //                                    //                                "dtime"=>date("Y-m-d H:i:s"),
                        //                                    //                                "oleh_id"=>$this->session->login['id'],
                        //                                    //                                "oleh_nama"=>$this->session->login['nama'],
                        //                                ),
                        //                                "update" => array(
                        //                                "nilai"     => $varName,
                        //                                "dtime"     => date("Y-m-d H:i:s"),
                        //                                "oleh_id"   => $this->session->login['id'],
                        //                                "oleh_nama" => $this->session->login['nama'],
                        //                                                                ),
                        //                            );
                        //                        }
                        //                        else {
                        //                            //==insertList ditambah
                        //                            $insertList[] = array(
                        //                                "jenis"       => $this->iy,
                        //                                "jenis_value" => $zID,
                        //                                "produk_id"   => $yID,
                        //                                "cabang_id"   => $xID,
                        //                                "nilai"       => $varName,
                        //                                "dtime"       => date("Y-m-d H:i:s"),
                        //                                "oleh_id"     => $this->session->login['id'],
                        //                                "oleh_nama"   => $this->session->login['nama'],
                        //                            );
                        //                        }
                        //                        echo "<br>";
                    }
                }

            }
        }
        //        arrPrint($arrPostData);
        foreach ($arrPostData as $yId => $yData) {
            foreach ($yData as $xId => $xData) {
                $oldData = $this->z['rawEntries'][$yId][$xId];
                $arrLast = array_diff($xData, $oldData);
                if (sizeof($arrLast) > 0) {
                    foreach ($arrLast as $zId => $varName) {
                        if (isset($this->existingValues[$yId][$xId][$zId])) {
                            $oldUpdateList[] = array(
                                "old_content" => array(
                                    "jenis"       => $this->iy,
                                    "jenis_value" => $zId,
                                    "nilai"       => $this->z['rawEntries'][$yId][$xId][$zId],
                                    "cabang_id"   => $xId,
                                ),

                            );
                            $updateList[] = array(
                                "where"   => array(
                                    "jenis"       => $this->iy,
                                    "jenis_value" => $zId,
                                    "produk_id"   => $yId,
                                    "cabang_id"   => $xId,
                                    //                                "nilai"=>$varName,
                                    //                                "dtime"=>date("Y-m-d H:i:s"),
                                    //                                "oleh_id"=>$this->session->login['id'],
                                    //                                "oleh_nama"=>$this->session->login['nama'],
                                ),
                                "update"  => array(
                                    "nilai"     => $varName,
                                    "dtime"     => date("Y-m-d H:i:s"),
                                    "oleh_id"   => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                ),
                                "history" => array(
                                    "produk_id"   => $yId,
                                    "nilai"       => $varName,
                                    "dtime"       => date("Y-m-d H:i:s"),
                                    "oleh_id"     => $this->session->login['id'],
                                    "oleh_nama"   => $this->session->login['nama'],
                                    "jenis"       => $this->iy,
                                    "jenis_value" => $zId,
                                    "cabang_id"   => $xId,
                                ),
                            );
                        }
                        else {
                            $insertList[] = array(
                                "jenis"       => $this->iy,
                                "jenis_value" => $zId,
                                "produk_id"   => $yId,
                                "cabang_id"   => $xId,
                                "nilai"       => $varName,
                                "dtime"       => date("Y-m-d H:i:s"),
                                "oleh_id"     => $this->session->login['id'],
                                "oleh_nama"   => $this->session->login['nama'],
                            );
                        }
                    }

                }


                //                arrPrint($arrLast);
            }
        }

        //        die("saving..");
        //        matiHere();
        //        arrPrint($updateList);
        //        arrPrint($oldUpdateList);

        $resultIds = array();
        if (sizeof($updateList) > 0 || sizeof($insertList) > 0) {

            $this->db->trans_start();
            $zo = new $this->z['mdlName']();
            if (sizeof($insertList) > 0) {
                foreach ($insertList as $iSpec) {
                    $resultIds[] = $zo->addData($iSpec) or die("failed to add new data");
                    cekMerah($this->db->last_query());
                }
            }
            if (sizeof($updateList) > 0) {
                //                cekHijau("iki");
                foreach ($updateList as $uKey => $uSpec) {

                    $insertID = $zo->updateData($uSpec['where'], $uSpec['update']) or die("failed to update data");
                    $tempOld = $oldUpdateList[$uKey]["old_content"];
                    $resultIds[] = $insertID;

                    cekMerah($this->db->last_query());

                    cekBiru($this->z["mdlName"]);
                    $data_id = $uSpec['where']['produk_id'];
                    $this->load->model("Mdls/" . "MdlDataHistory");
                    $hTmp = new MdlDataHistory();
                    $tmpHData = array(
                        "orig_id"            => $insertID,
                        "mdl_name"           => $this->z["mdlName"],
                        "mdl_label"          => $this->z["label"],
                        "old_content"        => base64_encode(serialize($tempOld)),
                        "old_content_intext" => print_r($tempOld, true),
                        "new_content"        => base64_encode(serialize($uSpec["history"])),
                        "new_content_intext" => print_r($uSpec["history"], true),
                        "label"              => "price",
                        "oleh_id"            => $this->session->login['id'],
                        "oleh_name"          => $this->session->login['nama'],
                        "data_id"            => $data_id,
                        "cabang_id"          => $uSpec["history"]["cabang_id"],

                    );
                    //                    arrPrint($tmpHData);
                    $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                    cekBiru($this->db->last_query());
                }
            }
            //            matiHEre("hooppp  comat comit");
            $this->db->trans_complete() or die("Gagal saat berusaha  commit data-update!");
            echo lgShowSuccess("", "New setting successfully save");
        }
        else {
            die("No entry to insert/update");
        }
        if (sizeof($resultIds) > 0) {
            $this->session->errMsg = "posted data has been saved";
        }
        else {
            $this->session->errMsg = "";
        }
        if (isset($_GET['attached']) && $_GET['attached'] == '1') {

            $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(                                   {
                                       title:'Modify entry..',
                                        message: " . '$' . "('<div></div>').load('" . $_SESSION['backLink'] . "'),
                                        draggable:false,
                                        size:top.BootstrapDialog.SIZE_WIDE,                                        
                                        closable:true,
                                        }
                                        );";

            echo "<html>";
            echo "<head>";
            echo "<script src=\"".cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
            echo "</head>";
            echo "<body onload=\"$actionTarget\">";
            echo "</body>";

        }
        else {
            echo "<script>top.location.reload();</script>";
        }


    }

    public function scaner()
    {

        $data = array(
            "mode"     => "index",
            // "errMsg"      => $this->session->errMsg,
            "title"    => "Scaner Produk",
            "subTitle" => "Produk",
            // "breadcrumb"    => "<li class=\"breadcrumb-item active d-block d-sm-none\">{subTitle}</li>",
            // "yLabels"     => $this->y['entries'],
            // "xLabels"     => $this->x['entries'],
            // "zLabels"     => $this->priceConfig,
            // "values"      => $this->z['entries'],
            // "history"     => $this->z['listHistory'],
            // "formTarget"  => $formTarget,
            // "buttonLabel" => $buttonLabel,
            // "yHeader"     => ucwords($this->iy),
            // "self"        => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "defaultKey"  => $this->q != null ? $this->q : "type here to search " . $this->iy . "..",
            // "startPage"   => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->iy . "/" . $this->ix . "/" . $this->iz,
            // "attached"    => $attached,
            "pageStr"  => "",
            // "pageOffside" => $this->page_offside,
            // "pageLimit"   => $this->limit_per_page,
        );
        //        arrPrint($data);
        $this->load->view('home', $data);
    }

    public function keranjang()
    {
        // $this->load->model("Mdls/MdlProdukWebs");
        // $pr = new MdlProdukWebs();
        $produks = $this->produks;


        // arrPrint($produks);
        // mati_disini();
        $dataCarts = isset($_SESSION['webs']['cart']) ? $_SESSION['webs']['cart'] : array();
        // arrPrint($dataCarts);

        foreach ($dataCarts as $produk_id => $items) {

        }


        $data = array(
            "mode"     => "keranjang",
            // "errMsg"      => $this->session->errMsg,
            "title"    => "Keranjang Belanjaan",
            "subTitle" => "Keranjang Belanjaan",
            "content"  => $dataCarts,
            "produks"  => $produks,
            // "yLabels"     => $this->y['entries'],
            // "xLabels"     => $this->x['entries'],
            // "zLabels"     => $this->priceConfig,
            // "values"      => $this->z['entries'],
            // "history"     => $this->z['listHistory'],
            // "formTarget"  => $formTarget,
            // "buttonLabel" => $buttonLabel,
            // "yHeader"     => ucwords($this->iy),
            // "self"        => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "defaultKey"  => $this->q != null ? $this->q : "type here to search " . $this->iy . "..",
            // "startPage"   => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->iy . "/" . $this->ix . "/" . $this->iz,
            // "attached"    => $attached,
            "pageStr"  => "",
            // "pageOffside" => $this->page_offside,
            // "pageLimit"   => $this->limit_per_page,
        );
        //        arrPrint($data);
        $this->load->view('home', $data);
    }

    public function addToCart()
    {
        $p = new Bs_41();
        // arrPrint($this->uri->segment_array());
        // $this->load->helper()
        $produk_id = $this->uri->segment(3);

        if (isset($_SESSION['webs']['cart'][$produk_id])) {
            $produks['jml'] = $_SESSION['webs']['cart'][$produk_id]['jml'] += 1;

            $_SESSION['webs']['cart'][$produk_id] = $produks;
        }
        else {
            $produks['jml'] = 1;
            $_SESSION['webs']['cart'][$produk_id] = $produks;
        }
        $arrAlert = array(
            "type"              => "success",
            "html"              => "Produk berhasil dimasukan shoping cart",
            "timer"             => 1000,
            "showConfirmButton" => false,
        );
        echo swalAlert($arrAlert);

        $carts = cart_webs();
        $jml_item = sizeof($carts);
        $qty_item = 0;
        $arrOpen = array();
        if (sizeof($carts) > 0) {
            foreach ($carts as $pid => $items) {
                $jml = $items['jml'];
                $qty_item += $jml;
            }
        }
        $dataCarts = is_array(cart_webs()) ? cart_webs() : array();
        $sc_mini_0 = $p->shopingCartMini($dataCarts);
        $sc_mini_1 = str_ireplace("'", "\'", $sc_mini_0);
        $sc_mini = str_ireplace("\"", "\\'", $sc_mini_1);

        echo "<script>
                a = $qty_item;
                b = \"$sc_mini\";
                top.document.getElementById('cart_item_n').innerHTML=a;
                top.document.getElementById('shopingcart_mini').innerHTML=b;
            </script>";

        arrPrint($_SESSION['webs']['cart']);
    }

    public function removeItemCart()
    {
        // arrPrintWebs($_REQUEST);
        $produk_id = $this->uri->segment(3);
        $sesCart = cart_webs();
        arrPrintWebs($sesCart);
        arrPrintWebs($_SESSION['webs']['cart']);
        // unset($sesCart[$produk_id]);
        unset($_SESSION['webs']['cart'][$produk_id]);
        cekHitam("$produk_id");
        topReload(700);
    }

    public function clearShopingcart()
    {

        unset($_SESSION['webs']['cart']);
        $arrAlert = array(
            "type"              => "success",
            "html"              => "Keranjang belanja berhasil dibersihkan",
            // "timer"             => 1500,
            "showConfirmButton" => false,
        );
        echo swalAlert($arrAlert);
        mati_disini(topReload());
        arrPrint($_SESSION['webs']['cart']);
    }

    public function clearSessionWebs()
    {
        unset($_SESSION['webs']);

        $arrAlert = array(
            "type"              => "success",
            "html"              => "seluruh session dibuang",
            // "timer"             => 1500,
            "showConfirmButton" => false,
        );
        echo swalAlert($arrAlert);
        mati_disini(topReload());
    }

    public function saveShopingcart()
    {

        // arrPrintWebs($_POST);

        $mainSpec['olehID'] = 55;
        $mainSpec['olehName'] = 'dodol';

        $ids = $_POST['id'];
        foreach ($ids as $id) {
            $itemSpec[$id]['id'] = $id;
        }
        $items['main'] = $mainSpec;
        $items['items'] = $itemSpec;

        arrPrintWebs($items);
        mati_disini();

        //region posting pakai APi
        $data = $items;
        $insert = $this->curl->simple_post($this->postScart, $data, array(CURLOPT_BUFFERSIZE => 10));
        if ($insert) {
            echo "<div style='margin-left: 100px'>";
            arrPrint($insert);
            echo "</div>";
        }
        else {
            mati_disini("gagal");
            // $this->session->set_flashdata('hasil', 'Insert Data Gagal');
        }
        //endregion
    }

    public function register()
    {
        $data = array(
            "mode"     => "register",
            // "errMsg"      => $this->session->errMsg,
            "title"    => "Registrasi",
            "subTitle" => "Produk",
            // "breadcrumb"    => "<li class=\"breadcrumb-item active d-block d-sm-none\">{subTitle}</li>",
            // "yLabels"     => $this->y['entries'],
            // "xLabels"     => $this->x['entries'],
            // "zLabels"     => $this->priceConfig,
            // "values"      => $this->z['entries'],
            // "history"     => $this->z['listHistory'],
            // "formTarget"  => $formTarget,
            // "buttonLabel" => $buttonLabel,
            // "yHeader"     => ucwords($this->iy),
            // "self"        => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            // "defaultKey"  => $this->q != null ? $this->q : "type here to search " . $this->iy . "..",
            // "startPage"   => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->iy . "/" . $this->ix . "/" . $this->iz,
            // "attached"    => $attached,
            "pageStr"  => "",
            // "pageOffside" => $this->page_offside,
            // "pageLimit"   => $this->limit_per_page,
        );
        //        arrPrint($data);
        $this->load->view('home', $data);
    }
}