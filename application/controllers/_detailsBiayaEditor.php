<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 6/10/2018
 * Time: 3:28 PM
 */

class _detailsBiayaEditor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // START OF COMPLETE REPEATED LOGIC
    public function addItem()
    {

        $prodID = isset($_GET['sID']) ? $_GET['sID'] : 0;
        $id = isset($_GET['bID']) ? $_GET['bID'] : 0;
        $produk_type = isset($_GET['tpID']) ? $_GET['tpID'] : "biaya";
        $selectedPlace = isset($_SESSION['login']['cabang_id']) ? $_SESSION['login']['cabang_id'] : "-1";

        $this->load->model("Mdls/" . "MdlDtaBiayaProject");
        $this->load->model("Mdls/MdlDtaBiayaProduksi");
        $this->load->model("Mdls/MdlSupplies");
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");

        $pcat = new MdlProdukRakitanPreBiaya();

        $tmpCategori = $pcat->lookupAll()->result();
        $produkCategori = array();
        if (sizeof($tmpCategori) > 0) {
            foreach ($tmpCategori as $catSpec) {
                $produkCategori[$catSpec->id] = $catSpec->nama;
            }
        }

        $bp = new MdlDtaBiayaProduksi();
        $oProp = $bp->lookupByID($prodID)->result();
        $jasa_nama = isset($oProp[0]->nama) ? $oProp[0]->nama : "";

        if ($produk_type == "supplies") {
            $o = new MdlSupplies();
            $tmp = $o->lookupByID($id)->result();
        } else if ($produk_type == "biaya") {
            $o = new MdlDtaBiayaProject();
            $tmp = $o->lookupByID($id)->result();
        } else {
            $o = new MdlDtaBiayaProject();
            $tmp = $o->lookupByID($id)->result();
            if (sizeof($tmp) == 0) {
                $o2 = new MdlSupplies();
                $tmp = $o2->lookupByID($id)->result();
                if (sizeof($tmp) > 0) {
                    $produk_type = "supplies";
                }
            }
        }

        if (!isset($_SESSION['PROPKGED'][$prodID])) {
            $_SESSION['PROPKGED'][$prodID] = array();
        }

        if (!isset($_SESSION['PROPKGED'][$prodID]['component'])) {
            $_SESSION['PROPKGED'][$prodID]['component'] = array();
        }

        if (!array_key_exists($id, $_SESSION['PROPKGED'][$prodID]['component'])) {
            $_SESSION['PROPKGED'][$prodID]['component'][$id] = array(
                "biaya_nama" => $jasa_nama,
                "name" => isset($tmp[0]->nama) ? $tmp[0]->nama : "",
                "cat_id" => isset($tmp[0]->cat_id) ? $tmp[0]->cat_id : 0,
                "cat_nama" => isset($tmp[0]->cat_nama) ? $tmp[0]->cat_nama : "",
                "jml" => 1,
                "harga" => 0,
                "subtotal" => 0,
                "subharga" => 0,
                "produk_type" => $produk_type,
                "jenis" => $produk_type,
                "satuan" => isset($tmp[0]->satuan) ? $tmp[0]->satuan : "",
                "satuan_id" => isset($tmp[0]->satuan_id) ? $tmp[0]->satuan_id : 0,
            );
        }

        $key = isset($_GET["key"]) ? $_GET["key"] : "";
        if ($key == "jml" || isset($_GET['jml'])) {
            $rawJml = isset($_GET['jml']) ? $_GET['jml'] : $_SESSION['PROPKGED'][$prodID]['component'][$id]['jml'];
            $cleanJml = floatval(str_replace(array(',', ' '), '', $rawJml));
            $_SESSION['PROPKGED'][$prodID]['component'][$id]['jml'] = $cleanJml;
        }
        if ($key == "harga" || isset($_GET['harga'])) {
            $rawHarga = isset($_GET['harga']) ? $_GET['harga'] : $_SESSION['PROPKGED'][$prodID]['component'][$id]['harga'];
            $cleanHarga = floatval(str_replace(array(',', '.', ' '), '', $rawHarga));
            $_SESSION['PROPKGED'][$prodID]['component'][$id]['harga'] = $cleanHarga;
        }
        if ($key == "cat_id" || isset($_GET['cat_id'])) {
            $catId = isset($_GET['cat_id']) ? $_GET['cat_id'] : $_SESSION['PROPKGED'][$prodID]['component'][$id]['cat_id'];
            $_SESSION['PROPKGED'][$prodID]['component'][$id]['cat_id'] = $catId;
            $_SESSION['PROPKGED'][$prodID]['component'][$id]['cat_nama'] = isset($produkCategori[$catId]) ? $produkCategori[$catId] : "";
        }

        $curJml = $_SESSION['PROPKGED'][$prodID]['component'][$id]['jml'];
        $curHarga = $_SESSION['PROPKGED'][$prodID]['component'][$id]['harga'];
        $_SESSION['PROPKGED'][$prodID]['component'][$id]['subtotal'] = $curJml * $curHarga;
        $_SESSION['PROPKGED'][$prodID]['component'][$id]['subharga'] = $curJml * $curHarga;

        $json = json_encode($_SESSION['PROPKGED'][$prodID]['component']);
        echo $json;
    }
    // END OF COMPLETE REPEATED LOGIC

    public function removeItem()
    {
        $prodID = $this->uri->segment(3);
        $id = $this->uri->segment(4);

        if (isset($_SESSION['PROPKGED'][$prodID]['component']) && array_key_exists($id, $_SESSION['PROPKGED'][$prodID]['component'])) {
            unset($_SESSION['PROPKGED'][$prodID]['component'][$id]);
        }

        echo json_encode(array("status" => "success"));
    }

    // START OF COMPLETE REPEATED LOGIC
    public function search_old()
    {

        $prodID = $_GET['sID'];
        $key = isset($_GET['key']) ? $_GET['key'] : "";

        $this->load->model("Mdls/" . "MdlDtaBiayaProject");
        $this->load->model("Mdls/" . "MdlSupplies");

        $lsPrdSes = array();
        if (isset($_SESSION['PROPKGED'][$prodID]['component']) && sizeof($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            foreach ($_SESSION['PROPKGED'][$prodID]['component'] as $idPrdSes => $dataPrdSes) {
                $lsPrdSes[$idPrdSes] = $dataPrdSes;
            }
        }

        $backLink = isset($_SESSION['PROPKGED'][$prodID]['backLink']) ? $_SESSION['PROPKGED'][$prodID]['backLink'] : "#";

        $o = new MdlDtaBiayaProject();
        $tmp = $o->lookupByKeyword($key)->result();

        $o1 = new MdlSupplies();
        $tmp1 = $o1->lookupByKeyword($key)->result();

        $countAll = count($tmp) + count($tmp1);
        $countBiaya = count($tmp);
        $countSupplies = count($tmp1);

        $addBiaya = "
                        top.BootstrapDialog.show(
                           {
                                title:'New Biaya Project',
                                message: $('<div></div>').load('" . base_url() . "statik/Data/add/DtaBiayaProject?frame=result2&backlink=$backLink'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );
            ";

        $addSupplies = "
                        top.BootstrapDialog.show(
                           {
                                title:'New Supplies',
                                message: $('<div></div>').load('" . base_url() . "statik/Data/add/Supplies?frame=result2&backlink=$backLink'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );
            ";


        if (count($tmp) > 0 || count($tmp1) > 0) {
            echo "<ul class='list-group'>";
            echo "
                <div style='margin-bottom: 2px;' class='nav-tabs-custom'>
                    <ul class='nav nav-tabs' style='text-align: left;'>
                        <li class='active'> <a class='text-center text-bold' style='padding: 2px;' href='#all'        data-toggle='tab'>ALL       <span class='badge bg-red'>$countAll</span></a>     </li>
                        <li>                <a class='text-center text-bold' style='padding: 2px;' href='#biaya'      data-toggle='tab'>BIAYA     <span class='badge bg-red'>$countBiaya</span> <span style='border-radius: 10px;' class='btn btn-xs bg-olive btn-blockx' onclick=\"$addBiaya\"><i class='fa fa-plus'></i></span> </a>   </li>
                        <li>                <a class='text-center text-bold' style='padding: 2px;' href='#supplies'   data-toggle='tab'>SUPPLIES  <span class='badge bg-red'>$countSupplies</span><span style='border-radius: 10px;' class='btn btn-xs bg-yellow btn-blockx' onclick=\"$addSupplies\"><i class='fa fa-plus'></i></span></a></li>
                    </ul>
                </div>
            ";

            echo "<div class='tab-content bg-gray'>";

            echo "<div id='all'         class='tab-pane fade in active'>";

            if (count($tmp) > 0) {
                echo "<li style='' class=''>BIAYA</li>";
                foreach ($tmp as $row) {
                    $label_nama = strlen($row->nama) > 28 ? substr($row->nama, 0, 25) . "..." : $row->nama;
                    $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";
                    echo "<li style='padding: 4px;' class='list-group-item text-left text-link text-uppercase' title='" . $row->nama . "' onclick =\"addItemFromList('biaya', '$prodID', '" . $row->id . "');\">";
                    echo $label_nama;
                    echo "$check </li>";
                }
            }

            if (count($tmp1) > 0) {
                echo "<li style='' class=''>SUPPLIES</li>";
                foreach ($tmp1 as $row) {
                    $label_nama = strlen($row->nama) > 28 ? substr($row->nama, 0, 25) . "..." : $row->nama;
                    $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";
                    echo "<li style='padding: 4px;' class='list-group-item text-left text-link text-uppercase' title='" . $row->nama . "' onclick =\"addItemFromList('supplies', '$prodID', '" . $row->id . "');\">";
                    echo $label_nama;
                    echo "$check </li>";
                }
            }

            echo "</div>";

            echo "<div id='biaya'       class='tab-pane fade in'>";
            foreach ($tmp as $row) {
                $label_nama = strlen($row->nama) > 28 ? substr($row->nama, 0, 25) . "..." : $row->nama;
                $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";
                echo "<li style='padding: 4px;' class='list-group-item text-left text-link text-uppercase' title='" . $row->nama . "' onclick =\"addItemFromList('biaya', '$prodID', '" . $row->id . "');\">";
                echo $label_nama;
                echo "$check </li>";
            }

            echo "<li style='background: lightyellow;' class='list-group-item text-uppercase' title='' onclick =\"$addBiaya\">";
            echo "<span class='btn btn-xs bg-olive btn-block'><i class='fa fa-plus'></i> tambah biaya</span>";
            echo "</li>";

            echo "</div>";

            echo "<div id='supplies'    class='tab-pane fade in'>";
            foreach ($tmp1 as $row) {
                $label_nama = strlen($row->nama) > 28 ? substr($row->nama, 0, 25) . "..." : $row->nama;
                $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";
                echo "<li style='padding: 4px;' class='list-group-item text-left text-link text-uppercase' title='" . $row->nama . "' onclick =\"addItemFromList('supplies', '$prodID', '" . $row->id . "');\">";
                echo $label_nama;
                echo "$check </li>";
            }

            echo "<li style='background: lightyellow;' class='list-group-item text-uppercase' title='' onclick =\"$addSupplies\">";
            echo "<span class='btn btn-xs bg-yellow btn-block'><i class='fa fa-plus'></i> tambah supplies</span>";
            echo "</li>";

            echo "</div>";

            echo "</div>";

            echo "</ul class='list-group'>";
        } else {

            echo "<ul class='list-group'>";

            $addBiaya = "
                        top.BootstrapDialog.show(
                           {
                                title:'New Biaya Project',
                                message: $('<div></div>').load('" . base_url() . "statik/Data/add/DtaBiayaProject?frame=result2&backlink=$backLink'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );
            ";

            echo "<li style='background: lightyellow;' class='list-group-item text-uppercase' title='' onclick =\"$addBiaya\">";
            echo "<span class='btn btn-xs btn-success btn-block'><i class='fa fa-plus'></i> tambah</span>";
            echo "</li>";

            echo "</ul class='list-group'>";

        }

        echo "<script>
            try { localStorage.lastSearch='$key'; } catch(e){}
            if (typeof window.addItemFromList !== 'function') {
                window.addItemFromList = function(tpID, sID, bID) {
                    var url = '" . base_url() . "_detailsBiayaEditor/addItem?tpID=' + encodeURIComponent(tpID) + '&sID=' + encodeURIComponent(sID) + '&bID=' + encodeURIComponent(bID);
                    $.get(url, function(res) {
                        location.reload();
                    });
                };
            }
        </script>";
    }
    // END OF COMPLETE REPEATED LOGIC

    // START OF COMPLETE REPEATED LOGIC
    public function search()
    {

        if (!function_exists('modal_editor')) {
            function modal_editor($model, $label, $id, $pid)
            {
                $ddd = "<a class=\"btn bg-gradient-success btn-xs btn-editor\" href=\"JavaScript:void(0)\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Edit Data\" onclick=\"top.BootstrapDialog.show(
                                       {
                                            title:'Modify " . strtoupper($label) . "',
                                            cssClass: 'edit-dialog',
                                            message: $('<div></div>').load('" . base_url() . "statik/Data/edit/$model/$id?1=1&pfid=$pid'),
                                            draggable:true,
                                            closable:true,
                                            onhidden: function(dialogRef){
                                                // window.location.reload();
                                            }
                                            });\" data-original-title=\"Edit data\"><span class=\"glyphicon glyphicon-pencil\"></span></a>";

                return $ddd;
            }
        }

        $prodID = $_GET['sID'];
        $key = isset($_GET['key']) ? $_GET['key'] : "";

        $this->load->model("Mdls/" . "MdlDtaBiayaProject");
        $this->load->model("Mdls/" . "MdlSupplies");

        $lsPrdSes = array();
        if (isset($_SESSION['PROPKGED'][$prodID]['component']) && sizeof($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            foreach ($_SESSION['PROPKGED'][$prodID]['component'] as $idPrdSes => $dataPrdSes) {
                $lsPrdSes[$idPrdSes] = $dataPrdSes;
            }
        }

        $backLink = isset($_SESSION['PROPKGED'][$prodID]['backLink']) ? $_SESSION['PROPKGED'][$prodID]['backLink'] : "#";

        $o = new MdlDtaBiayaProject();
        $tmp = $o->lookupByKeyword($key)->result();

        $o1 = new MdlSupplies();
        $tmp1 = $o1->lookupByKeyword($key)->result();

        $countAll = count($tmp) + count($tmp1);
        $countBiaya = count($tmp);
        $countSupplies = count($tmp1);

        $addBiaya = "
                        top.BootstrapDialog.show(
                           {
                                title:'New Biaya Project',
                                message: $('<div></div>').load('" . base_url() . "statik/Data/add/DtaBiayaProject?frame=result2&backlink=$backLink'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );
            ";

        $addSupplies = "
                        top.BootstrapDialog.show(
                           {
                                title:'New Supplies',
                                message: $('<div></div>').load('" . base_url() . "statik/Data/add/Supplies?frame=result2&backlink=$backLink'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );
            ";

        echo "<style>
                .btn-editor {
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    position: absolute;
                    right: 30px;
                    top: 50%;
                    transform: translateY(-50%);
                    background-color: #28a745;
                    color: white;
                    border: none;
                    border-radius: 3px;
                    padding: 5px 5px;
                    font-size: 12px;
                    line-height: 1.5;
                }
                
                .list-group-item:hover .btn-editor,
                .main-link:hover ~ .btn-editor {
                    opacity: 1;
                }
            </style>";
        if (count($tmp) > 0 || count($tmp1) > 0) {
            echo "<ul class='list-group'>";
            echo "
                <div style='margin-bottom: 2px;' class='nav-tabs-custom'>
                    <ul class='nav nav-tabs' style='text-align: left;'>
                        <li class='active'> <a class='text-center text-bold' style='padding: 2px;' href='#all'        data-toggle='tab'>ALL       <span class='badge bg-red'>$countAll</span></a>     </li>
                        <li>                <a class='text-center text-bold' style='padding: 2px;' href='#biaya'      data-toggle='tab'>BIAYA     <span class='badge bg-red'>$countBiaya</span> <span style='border-radius: 10px;' class='btn btn-xs bg-olive btn-blockx' onclick=\"$addBiaya\"><i class='fa fa-plus'></i></span> </a>   </li>
                        <li>                <a class='text-center text-bold' style='padding: 2px;' href='#supplies'   data-toggle='tab'>SUPPLIES  <span class='badge bg-red'>$countSupplies</span><span style='border-radius: 10px;' class='btn btn-xs bg-yellow btn-blockx' onclick=\"$addSupplies\"><i class='fa fa-plus'></i></span></a></li>
                    </ul>
                </div>
            ";

            echo "<div class='tab-content bg-gray'>";

            $btn_editor = "bbb";
            echo "<div id='all' class='tab-pane fade in active'>";

            if (count($tmp) > 0) {
                echo "<li style='' class=''>BIAYA</li>";
                foreach ($tmp as $row) {
                    $rid = $row->id;
                    $label_nama_asli = $row->nama;
                    $label_nama = strlen($row->nama) > 28 ? substr($row->nama, 0, 25) . "..." : $row->nama;

                    $btn_editor = modal_editor("DtaBiayaProject", $label_nama_asli, $rid, $prodID);
                    $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";
                    echo "<li style='padding: 4px;' class='list-group-item text-left text-link text-uppercase' title='" . $row->nama . "' >";
                    echo "<a class='main-link' href='javascript:void(0);' onclick =\"addItemFromList('biaya', '$prodID', '" . $row->id . "');\">$label_nama</a>";
                    echo " $btn_editor $check </li>";
                }
            }

            if (count($tmp1) > 0) {
                echo "<li style='' class=''>SUPPLIES</li>";
                foreach ($tmp1 as $row) {
                    $rid = $row->id;
                    $label_nama_asli = $row->nama;
                    $label_nama = strlen($row->nama) > 28 ? substr($row->nama, 0, 25) . "..." : $row->nama;
                    $btn_editor = modal_editor("Supplies", $label_nama_asli, $rid, $prodID);
                    $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";

                    echo "<li style='padding: 4px;' class='list-group-item text-left text-link text-uppercase' title='" . $row->nama . "' >";
                    echo "<a class='main-link' href='javascript:void(0);' onclick =\"addItemFromList('supplies', '$prodID', '" . $row->id . "');\">$label_nama </a>";
                    echo " $btn_editor $check </li>";
                }
            }

            echo "</div>";

            /** ----------------------------------------
             * biaya
             * ----------------------------------------*/
            echo "<div id='biaya' class='tab-pane fade in'>";
            foreach ($tmp as $row) {
                $rid = $row->id;
                $label_nama_asli = $row->nama;
                $label_nama = strlen($row->nama) > 28 ? substr($row->nama, 0, 25) . "..." : $row->nama;
                $btn_editor = modal_editor("DtaBiayaProject", $label_nama_asli, $rid, $prodID);

                $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";
                echo "<li style='padding: 4px;' class='list-group-item text-left text-link text-uppercase' title='" . $row->nama . "'>";
                echo "<a class='main-link' href='javascript:void(0);' onclick =\"addItemFromList('biaya', '$prodID', '" . $row->id . "');\">$label_nama</a>";
                echo " $btn_editor $check </li>";
            }

            echo "<li style='background: lightyellow;' class='list-group-item text-uppercase' title='' onclick =\"$addBiaya\">";
            echo "<span class='btn btn-xs bg-olive btn-block'><i class='fa fa-plus'></i> tambah biaya</span>";
            echo "</li>";

            echo "</div>";

            /** ----------------------------------------
             * supplies
             * ----------------------------------------*/
            echo "<div id='supplies'    class='tab-pane fade in'>";
            foreach ($tmp1 as $row) {
                $rid = $row->id;
                $label_nama_asli = $row->nama;
                $label_nama = strlen($row->nama) > 28 ? substr($row->nama, 0, 25) . "..." : $row->nama;
                $btn_editor = modal_editor("Supplies", $label_nama_asli, $rid, $prodID);

                $check = isset($lsPrdSes[$row->id]) ? "<span class='pull-right text-green'><i class='glyphicon glyphicon-ok'></i></span>" : "";
                echo "<li style='padding: 4px;' class='list-group-item text-left text-link text-uppercase' title='" . $row->nama . "'>";
                echo "<a class='main-link' href='javascript:void(0);' onclick =\"addItemFromList('supplies', '$prodID', '" . $row->id . "');\">$label_nama</a>";
                echo " $btn_editor $check </li>";
            }

            echo "<li style='background: lightyellow;' class='list-group-item text-uppercase' title='' onclick =\"$addSupplies\">";
            echo "<span class='btn btn-xs bg-yellow btn-block'><i class='fa fa-plus'></i> tambah supplies</span>";
            echo "</li>";

            echo "</div>";

            echo "</div>";

            echo "</ul class='list-group'>";
        } else {

            echo "<ul class='list-group'>";

            $addBiaya = "
                        top.BootstrapDialog.show(
                           {
                                title:'New Biaya Project',
                                message: $('<div></div>').load('" . base_url() . "statik/Data/add/DtaBiayaProject?frame=result2&backlink=$backLink'),
                                size: BootstrapDialog.SIZE_WIDE,
                                draggable:false,
                                closable:true,
                            }
                        );
            ";

            echo "<li style='background: lightyellow;' class='list-group-item text-uppercase' title='' onclick =\"$addBiaya\">";
            echo "<span class='btn btn-xs btn-success btn-block'><i class='fa fa-plus'></i> tambah</span>";
            echo "</li>";

            echo "</ul class='list-group'>";

        }

        echo "<script>
            try { localStorage.lastSearch='$key'; } catch(e){}
            if (typeof window.addItemFromList !== 'function') {
                window.addItemFromList = function(tpID, sID, bID) {
                    var url = '" . base_url() . "_detailsBiayaEditor/addItem?tpID=' + encodeURIComponent(tpID) + '&sID=' + encodeURIComponent(sID) + '&bID=' + encodeURIComponent(bID);
                    $.get(url, function(res) {
                        location.reload();
                    });
                };
            }
            $(function () {
                const observer = new MutationObserver(function (mutationsList, observer) {
                    mutationsList.forEach(function (mutation) {
                        mutation.addedNodes.forEach(function (node) {
                            if ($(node).find('.modal-dialog').length > 0 || $(node).hasClass('modal-dialog')) {
                                setTimeout(function () {
                                    $('.selectpicker').selectpicker('refresh');
                                    $('.select2').select2({
                                        dropdownParent: $('.modal-dialog').last()
                                    });
                                }, 300);
                            }
                        });
                    });
                });
            
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            });

        </script>";

        echo "<script>top.$('.select2').selectpicker({ dropdownParent: $('body') })</script>";
    }
    // END OF COMPLETE REPEATED LOGIC

    // START OF COMPLETE REPEATED LOGIC
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
                echo("<a class='text-center' href=\"javascript:void(0)\" onclick=\"$.get('" . base_url() . get_class($this) . "/removeItem/$prodID/$id', function(){ location.reload(); });\"><span class='glyphicon glyphicon-remove'></span></a>");
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
    // END OF COMPLETE REPEATED LOGIC
}