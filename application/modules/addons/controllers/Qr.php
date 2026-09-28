<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qr extends CI_Controller
{
    /*PENTING*/
    /* -----------------------------------------------------------------------------
     * folder public\images\qrcode harus full akses 777 -> akan digunakan membuat image QR
     * library yg terlibat  : application\libraries\qrcode
     *                      : application\libraries\qrcode\Ciqrcode.php
     * ----------------------------------------------------------------------------*/
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');

    }

    //    public function validate_EAN13Barcode($barcode)
    //    {
    //        // check to see if barcode is 13 digits long
    //        if (!preg_match("/^[0-9]{13}$/", $barcode)) {
    //            return false;
    //        }
    //
    //        $digits = $barcode;
    //
    //        // 1. Add the values of the digits in the
    //        // even-numbered positions: 2, 4, 6, etc.
    //        $even_sum = $digits[1] + $digits[3] + $digits[5] +
    //            $digits[7] + $digits[9] + $digits[11];
    //
    //        // 2. Multiply this result by 3.
    //        $even_sum_three = $even_sum * 3;
    //
    //        // 3. Add the values of the digits in the
    //        // odd-numbered positions: 1, 3, 5, etc.
    //        $odd_sum = $digits[0] + $digits[2] + $digits[4] +
    //            $digits[6] + $digits[8] + $digits[10];
    //
    //        // 4. Sum the results of steps 2 and 3.
    //        $total_sum = $even_sum_three + $odd_sum;
    //
    //        // 5. The check character is the smallest number which,
    //        // when added to the result in step 4, produces a multiple of 10.
    //        $next_ten = (ceil($total_sum / 10)) * 10;
    //        $check_digit = $next_ten - $total_sum;
    //
    //        // if the check digit and the last digit of the
    //        // barcode are OK return true;
    //        if ($check_digit == $digits[12]) {
    //            return true;
    //        }
    //
    //        return false;
    //    }

    public function index()
    {
        $className = "MdlProduk2";
        $this->load->model("Mdls/MdlProduk2");
        $o = new MdlProduk2();

        $objState = "0";
        $alternateLink = "";
        $title = "Barcode";
        $o->addFilter("trash='$objState'");
        $getCid = isset($_GET['cb']) ? $_GET['cb'] : "5";
        $getF_str = "";
        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $o->addFilter("folders='" . $_GET['fID'] . "'");
            //            $title.=" on ".$_GET['fName'];
            // $get
            $getF_str = "&fID=" . $_GET['fID'];
        }
        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }
        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";
        }
        else {
            $key = "";
            $subtitle = "Daftar $title";
        }

        $params = array();
        $limit_per_page = 30;
        $page = ($this->uri->segment(4)) ? ($this->uri->segment(4) - 1) : 0;

        $subitle = $subtitle . " hal. " . ($page + 1);
        $total_records = $o->lookupDataCount($key);

        if ($total_records > 0) {
            // get current page records
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }
            // $this->db->where("nama LIKE '%bea%'");

            $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
            // showLast_query("merah");

            $config = array(
                'base_url'           => base_url() . "addons/" . get_class($this) . '/' . __FUNCTION__ . "/",
                'total_rows'         => $total_records,
                'per_page'           => $limit_per_page,
                "uri_segment"        => 4,
                // custom paging configuration
                'num_links'          => 5,
                'use_page_numbers'   => TRUE,
                'reuse_query_string' => TRUE,
                'full_tag_open'      => '<div class="text-center">',
                'full_tag_close'     => '</div>',
                'first_link'         => "<span class='fa fa-home'></span>",
                'first_tag_open'     => '<span style="padding:1px;">',
                'first_tag_close'    => '</span>',
                'last_link'          => "<span class='fa fa-gg'></span>",
                'last_tag_open'      => '<span style="padding:1px;">',
                'last_tag_close'     => '</span>',
                'next_link'          => "<span class='fa fa-angle-right'></span>",
                'next_tag_open'      => '<span style="padding:1px;">',
                'next_tag_close'     => '</span>',
                'prev_link'          => "<span class='fa fa-angle-left'></span>",
                'prev_tag_open'      => '<span style="padding:1px;">',
                'prev_tag_close'     => '</span>',
                'cur_tag_open'       => '<span class="btn btn-primary disabled">',
                'cur_tag_close'      => '</span>',
                'num_tag_open'       => '<span style="padding:1px;">',
                'num_tag_close'      => '</span>',
            );
            $this->pagination->initialize($config);

            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination
        // $tmp = isset($_GET['k']) && $_GET['k'] != "" ? $tmp_0 : array();
        // cekBiru(sizeof($tmp));
        $generate = base_url() . "addons/Qr/generateCode";
        // arrPrintPink($tmp);
        $arrRow = array();
        if (sizeof($tmp) > 0) {
            $jml_kolom = "3";
            $isi_array = sizeof($tmp);
            $max_baris_perkolom = floor($isi_array / $jml_kolom);
            $sisa_baris_ = $isi_array % $jml_kolom;
            $row = 0;
            $arrRow = array();
            $idProduks = array();
            foreach ($tmp as $indx => $tmp0) {
                $idProduks[] = $tmp0->id;
                $row++;
                if (($row >= 1) && ($row <= $max_baris_perkolom)) {
                    $arrRow[0][] = array(
                        "id"      => $tmp0->id,
                        "nama"    => $tmp0->nama,
                        "barcode" => $tmp0->barcode,
                        "kode"    => $tmp0->kode,
                    );
                }
                elseif (($row >= $max_baris_perkolom) && ($row <= ($rowke_3 = $max_baris_perkolom * 2))) {
                    $arrRow[1][] = array(
                        "id"      => $tmp0->id,
                        "nama"    => $tmp0->nama,
                        "barcode" => $tmp0->barcode,
                        "kode"    => $tmp0->kode,
                    );
                }
                else {
                    $arrRow[3][] = array(
                        "id"      => $tmp0->id,
                        "nama"    => $tmp0->nama,
                        "barcode" => $tmp0->barcode,
                        "kode"    => $tmp0->kode,
                        // "barcode" => $tmp0->kode,
                    );
                }
                //        cekHitam("$isi_array|| $max_baris_perkolom||$sisa_baris_");
            }
        }

        /* ----------------------------------------------------------
             * navigasi filter
             * ----------------------------------------------------------*/

        $getfID = isset($_GET['fID']) ? $_GET['fID'] : "";
        $getGr = isset($_GET['gr']) ? $_GET['gr'] : "";
        $get_gr = isset($_GET['gr']) ? 'gr=' . $getGr : "";
        $get_key = isset($_GET['k']) ? '&k=' . $key : "";
        $get_k = $key;

        $i = 0;
        $navigasi_filter = "";
        // -----------------------------------------------------------------------------------------------------
        $navigasi_filter .= "<div class='row'>";

        $navigasi_filter .= "<div class='col-md-8'>";
        $navigasi_filter .= "<form method='get'>";
        $navigasi_filter .= "<div class='input-group'>";
        $navigasi_filter .= "<input type='text' class='form-control' name='k' value='$get_k'>";
        $navigasi_filter .= "<input type='hidden' name='gr' value='$getGr' >";
        if (isset($_GET['fID'])) {
            $navigasi_filter .= "<input type='hidden' name='fID' value='$getfID' >";
        }
        if (isset($_GET['cb'])) {
            $navigasi_filter .= "<input type='hidden' name='cb' value='$getCid' >";
        }
        $navigasi_filter .= "<span class='input-group-btn'><button type='submit' class='btn btn-primary'>CARI</button></span>";
        $navigasi_filter .= "</div>";
        $navigasi_filter .= "</form>";
        $navigasi_filter .= "</div>";
        // -----------------------------------------------------------------------------------------------------
        $link_halaman_ini = base_url(uri_string()) . "?$get_gr" . $getF_str;
        //region cabang id
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $srcCabangs = $cb->lookupAll()->result();
        // arrPrint($srcCabangs);
        $cabangs = array();
        foreach ($srcCabangs as $srcCabang) {
            $cabangs[$srcCabang->id] = $srcCabang->nama;
        }

        $navigasi_filter .= "<div class='col-md-2'>";
        $navigasi_filter .= "<select class='btn btn-info btn-block' name='cb' onchange=\"location.href='$link_halaman_ini&cb='+ this.value;\">";
        $navigasi_filter .= "<option>-pilih cabang-</option>";
        foreach ($cabangs as $cabang_id => $cabang_nama) {
            $cDipilih = $getCid == $cabang_id ? "selected" : "";
            $navigasi_filter .= "<option $cDipilih value='$cabang_id'>$cabang_nama</option>";
        }
        $navigasi_filter .= "</select>";
        $navigasi_filter .= "</div>";
        //endregion
        // -----------------------------------------------------------------------------------------------------
        //region kategori produk
        $this->load->model("Mdls/MdlFolderProduk");
        $fp = new MdlFolderProduk();
        $srcFolders = $fp->lookupAll()->result();
        // arrPrint($srcFolders);
        $folders = array();
        foreach ($srcFolders as $srcFolder) {
            $fol_id = $srcFolder->id;
            $fol_nama = $srcFolder->nama;

            $folders[$fol_id] = $fol_nama;
        }

        $navigasi_filter .= "<div class='col-md-2'>";
        $navigasi_filter .= "<select class='btn btn-warning btn-block' name='fID' onchange=\"location.href='$link_halaman_ini&fID='+ this.value;\">";
        $navigasi_filter .= "<option value=''>-pilih kategori-</option>";
        foreach ($folders as $folder_id => $folder_nama) {
            $fDipilih = $getfID == $folder_id ? "selected" : "";
            $navigasi_filter .= "<option $fDipilih value='$folder_id'>$folder_nama</option>";
        }
        $navigasi_filter .= "</select>";
        $navigasi_filter .= "</div>";
        //endregion

        $navigasi_filter .= "</div>";
        $navigasi_filter .= "<div id='preprint' style='margin: 10px 0;'></div>";
        /* -----------------------------------------------------------------------------
         * loader untuk produk yg dipilih
         * -----------------------------------------------------------------------------*/
        $produk_pilihans = array();
        if (isset($_SESSION['barcode_print'])) {
            $link_preprint_save = base_url() . "addons/" . $this->uri->segment(2) . "/prePrintSave";
            $navigasi_filter .= "<script>$('#preprint').load('$link_preprint_save');</script>";

            $barcode_pilihans = array_filter($_SESSION['barcode_print']);
            foreach ($barcode_pilihans as $item_id => $item_jml) {
                $produk_pilihans[$item_id]['id'] = $item_id;
                $produk_pilihans[$item_id]['jml'] = $item_jml;
            }
        }
        // $navigasi_filter .= "</div>";
        // arrPrint($produk_pilihans);
        // -----------------------------------------------------------------------------------------------------
        $testData = "";
        if (sizeof($arrRow) > 0) {
            // arrPrintHijau($idProduks);
            /* ---------------------------------------------
             * harga produk yg dipilih
             * ---------------------------------------------*/
            $cabang_pilihan = $getCid;
            $harga_produks = array();
            $this->load->model("Mdls/MdlHargaProduk");
            $hp = new MdlHargaProduk();
            $condite_hargas = array(
                "cabang_id" => "$cabang_pilihan",
            );
            $this->db->where($condite_hargas);
            $hrgs = $hp->callProdukHarga($idProduks);
            // showLast_query("merah");
            // arrPrintKuning($hrgs);
            foreach ($hrgs as $hrg_datas) {
                foreach ($hrg_datas as $cb_id => $hrg_data) {
                    $pro_id = $hrg_data['jual_nppn']['produk_id'];
                    $pro_harga = $hrg_data['jual_nppn']['nilai'];

                    $harga_produks[$pro_id] = $pro_harga;
                }
            }
            // ---------------------------------------------
            $testData .= "<div class='row oveflow-h'>";

            $action = base_url() . "addons/" . $this->uri->segment(2) . "/doPrint";
            $testData .= "<form action='$action' method='post' target='result'>";
            foreach ($arrRow as $k => $allData) {
                $i++;
                $testData .= "<div class='col-md-4 col-xs-4' style='border: #0c0c0c;1px'>";
                $testData .= "<table class='table-condensed table-bordered'>";
                $testData .= "<tr>";
                $testData .= "<th>select<input type='checkbox' id='all' value='on' title='pilih semua yang tersedia' onClick=\"togglecheckboxes(this,'id_produk[]')\"></th>";
                $testData .= "<th>barcode</th>";
                $testData .= "<th>copies</th>";
                $testData .= "<th>action</th>";
                $testData .= "</tr>";

                foreach ($allData as $l => $valData) {
                    // $code = isset($valData['barcode']) ? $valData['barcode'] : "BELUM PUNYA";
                    $code = isset($valData['kode']) ? $valData['kode'] : "BELUM PUNYA";
                    $name = $valData['nama'];
                    $ids = $valData['id'];
                    $imgbarcode = "<div class='no-padding text-center'>
                                        <div style='' class='uploaded font-size-0-7'><span>$name</span>
                                                <svg class='thumbnail' id='c_$ids' style='width:150px;height: 60px;margin-bottom: 1px;padding:0;margin:0;'></svg>
                                        </div>
                                    </div>";

                    if (validate_EAN13Barcode($code)) {
                        $imgbarcode .= "<script>JsBarcode('#c_$ids', '$code', {format: 'ean13', lineColor: '#0d1720'});</script>";
                    }
                    else {
                        if ($code == 'BELUM PUNYA') {
                            $imgbarcode .= "<script>JsBarcode('#c_$ids', '$code', {format: 'code39', lineColor: '#e02907'});</script>";
                        }
                        else {
                            $imgbarcode .= "<script>JsBarcode('#c_$ids', '$code', {format: 'code39', lineColor: '#0d1720'});</script>";
                        }
                    }
                    $harga = isset($harga_produks[$ids]) ? $harga_produks[$ids] : 0;
                    $harga_f = formatField_he_format('curency_print', $harga) . ",-";
                    $harga_str = "<div style='margin: -69px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;background-color: #ffffff;z-index: 5000;'>$harga_f</div>";
                    $harga_str .= "<input type='hidden' name='harga[$ids]' value='$harga_f'>";
                    $checked_ses = isset($produk_pilihans[$ids]['id']) && ($produk_pilihans[$ids]['id'] == $ids) ? "checked" : "";
                    $jml_print_ses = isset($produk_pilihans[$ids]['jml']) ? $produk_pilihans[$ids]['jml'] : "";
                    $link_preprint_save = base_url() . "addons/" . $this->uri->segment(2) . "/prePrintSave?id=$ids";

                    $testData .= "<tr>";
                    $testData .= "<td><input type='checkbox' id='p_$ids' name='id_produk[]' value='$ids' class='form-hcontrol pull-right' $checked_ses onchange=\"fillValue_$ids()\"></td>";
                    $testData .= "<td align='center'><div style='height: 90px; width: 170px;'>$imgbarcode $harga_str</div></td>";
                    $testData .= "<td><input type='text' id='jml_print_$ids' name='jml_print[$ids]' class='form-control' onkeyup='' value='$jml_print_ses' onblur=\"$('#preprint').load('$link_preprint_save&jml='+this.value)\"></td>";
                    $testData .= "<td><button class='btn btn-default' type='submit'><span class='fa fa-print'></span></td>";

                    $testData .= "</tr>";
                    $testData .= "<script>$('input[name=\\'jml_print\\[$ids\\]\\']').on('keyup',function(){
                                
                                
                                $('input#p_$ids').prop('checked',true);
                                })</script>";
                    $testData .= "<script> 
                            function fillValue_$ids() {
                                var cheked_id = document.getElementById('p_$ids');
                                if (cheked_id.checked) {
                                document.getElementById(\"jml_print_$ids\").value = 1;
                                $('#preprint').load('$link_preprint_save&jml=1')
                              } else {
                               document.getElementById(\"jml_print_$ids\").value = ''
                               $('#preprint').load('$link_preprint_save&jml=0')
                              }
                              
                            }
                            </script>";

                }
                $testData .= "<script>
                            function togglecheckboxes(master,group){
                                var cbarray = document.getElementsByName(group);
                                for(var i = 0; i < cbarray.length; i++){
                                    console.log(cbarray[i]);
                                    cbarray[i].checked = master.checked;
                                    $(cbarray[i]).trigger('change');
                                }
                            }
//                            function fillAll(valDef,id_all) {
//                                var dbarray = document.getElementsByName('jml_print[]');
//                                var master_checked = document.getElementById('all');
//                                console.log(master_checked);
//                                if(master_checked.cheked){
//                                    for(var x = 0; x < dbarray.length;i++){
//                                        
//                                    }
//                                }
//                                else{
//                                    
//                                }
//
//                                for(var i = 0; i < cbarray.length; i++){
//                                    cbarray[i].checked = master.checked;
//                                }
//                              
//                            }
                           

                            </script>";
                $testData .= "</table>";

                $testData .= "</div>";

                //        cekHitam("hitam ".$i);
            }
            $testData .= "<div class='row' style='margin-bottom: 5px;'></div>";
            $testData .= "<button type='submit' class='btn btn-block btn-warning'>Print All Selected</button>";
            $testData .= "</form>";

            $testData .= "</div>";
        }
        else {
            $testData = "<div class='font-size-2'>tidak ditemukan data berkaitan kata kunci <b>$get_k</b> <p class='text-danger'>perbaiki kata kunci dengan mengurangi karakter yang dituliskan</p></div>";
        }
        // arrPrintKuning(url_segment());
        $data = array(
            "mode"                => $this->uri->segment(3), // index
            "errMsg"              => $this->session->errMsg,
            "title"               => "$subtitle" . "",
            "subTitle"            => "Registered barcode",
            "strActiveDataTitle"  => "<span class='glyphicon glyphicon-th-list'></span> List of $title",
            "linkStr"             => isset($params['links']) ? $params['links'] : "",
            "arrayHistory"        => $testData,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "alternateLink"       => $alternateLink,
            "thisPage"            => base_url() . "addons/" . get_class($this) . "/" . $this->uri->segment(3) . "/" . "?trashed=$objState",
            "faddLink"            => isset($faddLink) ? $faddLink : "",
            "feditLink"           => isset($fupdateLink) ? $fupdateLink : "",
            "fdeleteLink"         => isset($fdeleteLink) ? $fdeleteLink : "",
            "fmdlName"            => isset($fmdlName) ? $fmdlName : "",
            "fmdlTarget"          => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
            "btn_gen"             => $generate,
            "navigasi_top"        => $navigasi_filter,
        );
        $this->load->view('qr', $data);

    }

    public function doPrint_default()
    {
        if (isset($_POST['id_produk'])) {
            $idData = $_POST['id_produk'];
            $jmlPrint = $_POST['jml_print'];

            $listedxId = "(" . implode(",", $idData) . ")";
            $className = "MdlProduk2";
            $this->load->model("Mdls/MdlProduk2");
            $o = new MdlProduk2();
            //            $o->addFilter(array("id in"=>"$listedxId"));
            $cindition = "id in $listedxId";
            $tmpX = $o->lookupByCondition($cindition)->result();
            $tempValue = array();
            $tempIdx = array();
            if (sizeof($tmpX) > 0) {
                foreach ($tmpX as $tempData) {
                    $id = $tempData->id;
                    $name = $tempData->nama;
                    $code = $tempData->barcode;

                    $tempValue[$id] = $code;
                    $tempIdx[$id] = $name;
                }
            }

            $data_show = "";
            foreach ($idData as $xId) {
                $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                $data_show .= "<div>";
                //echo $maxPrint;
                if ($maxPrint > 0) {
                    //                    cekHitam("masukk");
                    $barcode = $tempValue[$xId];
                    $name_data = $tempIdx[$xId];
                    //                    cekLime("$name_data||$barcode");
                    for ($i = 0; $i < $maxPrint; $i++) {
                        //                        cekLime("$name_data||$barcode");
                        //                        $data_show .= "<div class='col-sm-6 col-xs-6 no-padding'><div style='' class='uploaded'>
                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                        //                    </div>
                        //                    </div>";
                    }

                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                }
                else {
                    $arrAlert = array(
                        "type"              => "warning",
                        "title"             => "No data selected",
                        "html"              => "No data selected/copies empty to print!",
                        "timer"             => "1500",
                        "showConfirmButton" => false,
                        "allowOutsideClick" => false,
                    );
                    echo swalAlert($arrAlert);

                    echo "<script>topReload(1500)</script>";
                    echo topReload();
                    echo "</script>";
                    die();
                }
                $data_show .= "<div>";
            }

            //arrPrint($tempRR);
            $data = array(
                "template" => "application/template/barcode_print.html",
                "mode"     => "viewPrint",
                "tmp"      => $data_show
            );
            $this->load->view('barcode', $data);
        }
        else {
            $arrAlert = array(
                "type"              => "warning",
                "title"             => "No data selected",
                "html"              => "No data selected to print!",
                "timer"             => "1500",
                "showConfirmButton" => false,
                "allowOutsideClick" => false,
            );
            echo swalAlert($arrAlert);
            die();
            echo "<script>topReload(1500)</script>";
            echo topReload();
            echo "</script>";
        }
    }

    public function doPrint()
    {
        // arrPrint($_GET);
        // $this->load->libraries("phpqrcode/phpqrcode");
        // $this->load->libraries("phpqrcode/qrlib");
        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();

        if (isset($_POST['id_produk']) || isset($_GET['id_produk'])) {
            if (isset($_POST['id_produk'])) {
                $idData = $_POST['id_produk'];
                $jmlPrint = $_POST['jml_print'];
                $hargas = $_POST['harga'];
            }
            else {

                $idData = array($_GET['id_produk']);
                $jmlPrint = array($_GET['id_produk'] => $_GET['jml']);
                // $hargas = $_POST['harga'];
            }


            // arrPrint($_POST);

            $listedxId = "(" . implode(",", $idData) . ")";
            $className = "MdlProduk";
            $this->load->model("Mdls/MdlProduk");
            $o = new MdlProduk();
            //            $o->addFilter(array("id in"=>"$listedxId"));
            $cindition = "id in $listedxId";
            $tmpX = $o->lookupByCondition($cindition)->result();
            // showLast_query("hijau");
            $tempValue = array();
            $tempIdx = array();
            if (sizeof($tmpX) > 0) {
                foreach ($tmpX as $tempData) {
                    $id = $tempData->id;
                    $name = $tempData->nama;
                    $barcode = $tempData->barcode;
                    $code = $tempData->kode;

                    $tempValue[$id] = $barcode;
                    $tempIdx[$id] = $name;
                    $tempCode[$id] = $code;
                }
            }
            $indexID = array();
            //            $data_show = "";
            foreach ($idData as $xId) {
                $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                //                $data_show .= "<div>";
                if ($maxPrint > 0) {
                    $barcode = $tempValue[$xId];
                    $name_data = $tempIdx[$xId];
                    for ($i = 0; $i < $maxPrint; $i++) {
                        $indexID[] = $xId;
                        //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                        //                    </div>
                        //                    </div>";
                    }

                    //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                }
                //                $data_show .= "<div>";
            }
            // arrPrint($tempCode);
            $jml_kolom = "5";
            $isi_array = sizeof($indexID);
            $max_baris_perkolom = floor($isi_array / $jml_kolom);
            $sisa_baris_ = $isi_array % $jml_kolom;
            // $ekstra = $jml_kolom / $sisa_baris_;
            $row = 0;
            $addKolom_1 = $addKolom_2 = $addKolom_3 = $addKolom_4 = 0;
            if ($sisa_baris_ == 1) {
                $addKolom_1 = 1;
                $addKolom_2 = 1;
                $addKolom_3 = 1;
                $addKolom_4 = 1;
            }
            elseif ($sisa_baris_ == 2) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 2;
                $addKolom_4 = 2;
            }
            elseif ($sisa_baris_ == 3) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 3;
                $addKolom_4 = 4;
            }

            foreach ($indexID as $key => $id) {
                $row++;
                if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                    $arrRow[0][] = array(
                        "id" => $id,

                    );
                }
                elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                    $arrRow[1][] = array(
                        "id" => $id,
                    );
                }
                elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3 + $addKolom_4)) {
                    // cekPink("$row >= $rowke_1");
                    $arrRow[3][] = array(
                        "id" => $id,

                    );
                }
                else {
                    $arrRow[4][] = array(
                        "id" => $id,

                    );
                }
            }


            $listed = "<div class='row'>";
            $listed .= "<style>.thumbnail text{font-size: 2.5em !important;}</style>";
            $listed .= "<div class='container'>";
            foreach ($arrRow as $k => $tmpX) {
                $listed .= "<div class='col-md-3 col-lg-2 col-xs-3' style='padding: 2px; color: #0d1720;'>";
                $listed .= "<div class='text-center'>";
                foreach ($tmpX as $y => $x) {
                    // arrPrint($x);
                    $xID = $x['id'];
                    // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                    $barcode = $tempValue[$xID] ? $tempValue[$xID] : "";
                    $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                    $name_data = $tempIdx[$xID];
                    $harga_f = isset($hargas[$xID]) ? $hargas[$xID] : 0;

                    $qr_dta = array(
                        // "produk_id" => "https://demo.",
                        "produk_id"   => $xID,
                        "produk_kode" => $code . "_",
                        // "barcode" => $barcode,
                        // "jenis_tr"     => "0",
                        // "master_id"    => "0",
                        // "transaksi_id" => "0",
                        // "lain-lain" => "1563265",
                        // "ur" => "https://san.mayagrahakencana.com",
                    );
                    // $qr_dta = array("fff" => $barcode);
                    // $barcode = implode("|",$qr_dta);
                    // $barcode = json_encode($qr_dta);
                    // cekHere($barcode);
                    $qr_dta = $barcode;
                    // $qr_dta = "cek";
                    // $qrcode = $this->generate_qrcode($barcode);
                    // $qrcode = $qr->get_qrcode($qr_dta);
                    $qrcode = $qr->get_qrcode_barcode($qr_dta);
                    // $qrcode = $qr->get_qrcode_umum($qr_dta);
                    $qrfile = base_url() . $qrcode['file'];

                    //                    cekHitam($xID);
                    $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                    // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                    $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";

                    // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none;'></svg>";
                    //                    $listed .= "uuuu";
                    // $listed .= "<span>$code</span>";
                    $listed .= "</div>";
                    $listed .= "</div>";


                    // arrPrintKuning($qrcode);
                    // cekKuning( base_url() . $qrcode['file']);
                    // $listed .= "<img src='" . base_url() . $qrcode['file'] . "'>";
                    $listed .= "<img src='$qrfile' title='$xID'>";
                    //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                    // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                    $listed .= "</div>";
                }
                $listed .= "</div>";
                $listed .= "</div>";
            }
            $listed .= "</div>";
            $listed .= "</div>";

            $data = array(
                "mode" => "viewPrint",
                //                "tmp" => $data_show
                "tmp"  => $listed
            );
            $this->load->view('qr', $data);
        }
        else {
            if (isset($_GET['FromTransaksi'])) {
                // arrPrint($_GET);
                $custom_produk_print = array();
                if (isset($_SESSION['barcode_print']['items'])) {
                    $custom_produk_print = $_SESSION['barcode_print']['items'];
                }
                // arrPrint($custom_produk_print);

                $trid = $_GET['FromTransaksi'];
                // cekHere($trid);
                $jenisTr = isset($_GET['jn']) ? $_GET['jn'] : 0;
                $masterId = isset($_GET['mid']) ? $_GET['mid'] : 0;
                $this->load->model("MdlTransaksi");
                $this->load->model("Mdls/MdlProduk2");
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $items = $tr->lookupDetailTransaksi($trid);
                // showLast_query("kuning");
                // arrPrintHijau($items);
                $ids = array();
                $jmlPrint = array();
                $proidTransaksi = array();
                if (sizeof($items) > 0) {
                    foreach ($items[$trid] as $iID => $iData) {
                        $jmlPrint[$iData->produk_id] = isset($custom_produk_print[$iData->produk_id]['jml_print']) ? $custom_produk_print[$iData->produk_id]['jml_print'] : $iData->valid_qty; // belokan ambil jml print dr session
                        $proidTransaksi[$iData->produk_id] = $iData->transaksi_id;
                        $ids[] = $iData->produk_id;
                        // arrPrint($iData);
                    }
                }
                // arrPrintHijau($ids);
                /* ---------------------------------------------
             * harga produk yg dipilih
             * ---------------------------------------------*/
                $getCid = 5;
                $cabang_pilihan = $getCid;
                $harga_produks = array();
                // $this->load->model("Mdls/MdlHargaProduk");
                // $hp = new MdlHargaProduk();
                // $condite_hargas = array(
                //     "cabang_id" => "$cabang_pilihan",
                // );
                // $this->db->where($condite_hargas);
                // $hrgs = $hp->callProdukHarga($ids);
                // // showLast_query("merah");
                // // arrPrintKuning($hrgs);
                // foreach ($hrgs as $hrg_datas) {
                //     foreach ($hrg_datas as $cb_id => $hrg_data) {
                //         $pro_id = $hrg_data['jual_nppn']['produk_id'];
                //         $pro_harga = $hrg_data['jual_nppn']['nilai'];
                //
                //         $harga_produks[$pro_id] = $pro_harga;
                //     }
                // }
                // ---------------------------------------------


                // arrPrint($jmlPrint);
                $listedxId = "(" . implode(",", $ids) . ")";
                $className = "MdlProduk2";
                $this->load->model("Mdls/MdlProduk2");
                $o = new MdlProduk2();
                //            $o->addFilter(array("id in"=>"$listedxId"));
                $cindition = "id in $listedxId";
                $tmpX = $o->lookupByCondition($cindition)->result();
                // arrPrint($tmpX);
                // matiHEre();
                $tempValue = array();
                $tempIdx = array();
                $tempCode = array();
                if (sizeof($tmpX) > 0) {
                    foreach ($tmpX as $tempData) {
                        $id = $tempData->id;
                        $name = $tempData->nama;
                        $barcode = $tempData->barcode;
                        $code = $tempData->kode;

                        $tempValue[$id] = $barcode;
                        $tempIdx[$id] = $name;
                        $tempCode[$id] = $code;
                    }
                }
                $indexID = array();
                //            $data_show = "";
                foreach ($ids as $xId) {
                    $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                    //                $data_show .= "<div>";
                    if ($maxPrint > 0) {
                        $barcode = $tempValue[$xId];
                        $name_data = $tempIdx[$xId];
                        for ($i = 0; $i < $maxPrint; $i++) {
                            $indexID[] = $xId;
                            //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                            //                    </div>
                            //                    </div>";
                        }

                        //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                    }
                    //                $data_show .= "<div>";
                }
                // arrPrintHijau($maxPrint);

                // $filters = array(
                //     "produk_id" => $ids
                // );
                // $perSerials = $this->getProdukperSerialNumber($filters);
                // showLast_query("biru");

                $jml_kolom = "1";
                $isi_array = sizeof($indexID);
                $max_baris_perkolom = floor($isi_array / $jml_kolom);
                $sisa_baris_ = $isi_array % $jml_kolom;
                $row = 0;
                $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
                if ($sisa_baris_ == 1) {
                    $addKolom_1 = 1;
                    $addKolom_2 = 1;
                    $addKolom_3 = 1;
                }
                elseif ($sisa_baris_ == 2) {
                    $addKolom_1 = 1;
                    $addKolom_2 = 2;
                    $addKolom_3 = 2;
                }
                elseif ($sisa_baris_ == 3) {
                    $addKolom_1 = 1;
                    $addKolom_2 = 2;
                    $addKolom_3 = 3;
                }

                foreach ($indexID as $key => $id) {
                    $row++;
                    if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                        $arrRow[0][] = array(
                            "id" => $id,

                        );
                    }
                    elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                        $arrRow[1][] = array(
                            "id" => $id,
                        );
                    }
                    elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                        // cekPink("$row >= $rowke_1");
                        $arrRow[3][] = array(
                            "id" => $id,

                        );
                    }
                    else {
                        $arrRow[4][] = array(
                            "id" => $id,

                        );
                    }
                }
                $class_col = "col-md-3 col-lg-2 col-xs-3";
                if ($jml_kolom == 1) {
                    $class_col = "col-md-12 col-lg-12 col-xs-12";
                }
                //                arrPrint($arrRow);
                //                matiHere();
                $listed = "<div class='row'>";
                $listed .= "<div class='container'>";
                foreach ($arrRow as $k => $tmpX) {
                    $listed .= "<div class='$class_col' style='padding: 2px; color: #0d1720;'>";
                    $listed .= "<div class='text-center'>";
                    foreach ($tmpX as $y => $x) {
                        // arrPrint($x);
                        $xID = $x['id'];
                        // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                        $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                        //                        $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                        //                        $code = $tempCode[$xID] ? $tempCode[$xID] : $x["serial"];
                        $serialcode = $tempCode[$xID]["serial"] ? $tempCode[$xID]["serial"] : "--";
                        $podukID = $tempCode[$xID]["produk_id"] ? $tempCode[$xID]["produk_id"] : "--";
                        $sku = isset($tempCode[$xID]["sku"]) ? $tempCode[$xID]["sku"] : "--";
                        $sku = isset($tempCode[$xID]) ? $tempCode[$xID] : "--";
                        $name_data = $tempIdx[$xID];
                        // $skuLabel = $proidlabels[$xID];
                        // $harga_f = $hargas[$xID];
                        // $harga_f = formatField_he_format('curency_print', $harga_produks[$xID]);
                        // $name =
                        $qr_dta = array(
                            // "produk_id" => "https://demo.",
                            "produk_id"    => $xID,
                            "produk_kode"  => $code,
                            // "pn" => $name_data,
                            "jenis_tr"     => $jenisTr,
                            "master_id"    => $masterId,
                            "transaksi_id" => $proidTransaksi[$xID],
                            // "lain-lain" => "1563265",
                            // "ur" => "https://san.mayagrahakencana.com",
                        );
                        // $barcode = implode("|",$qr_dta);
                        // $barcode = json_encode($qr_dta);

                        // $qrcode = $this->generate_qrcode($barcode);
                        // $qrcode = $qr->get_qrcode($qr_dta);
                        $qrcode = $qr->get_qrcode_pembelian($sku);
                        $qrfile = base_url() . $qrcode['file'];
                        //                    cekHitam($xID);
                        // -----------------------------------------------------------------------------------
                        $listed .= "<div class='bottom-borders' style='mmin-height: 245px;'>";
                        // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                        $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                        // $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data [ $sku ]</span>";
                        // $listed .= "<span style='color: #0d6aad;' class='text-bold'>  </span>";
                        // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                        //                    $listed .= "uuuu";
                        // $listed .= "<span>$code</span>";
                        // $listed .= "</div>";
                        $listed .= "</div>";

                        // if (strtolower($skuLabel) == "in") {
                        //     $bg_ot = "";
                        //     $bg_ot = "bg-hitam";
                        //     $listed .= "<div style='' class='uploaded '>$name_data / $sku</div>";
                        //     $listed .= "<div style='' class='uploaded '>$serialcode</div>";
                        //     $border_xx = "border: #4e5158 dashed 2px;";
                        // }
                        // elseif (strtolower($skuLabel) == "ot") {
                        //     $border_xx = "";
                        // }
                        // else {
                        $border_xx = "";
                        // }


                        // if (validate_EAN13Barcode($barcode)) {
                        //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                        // }
                        // else if ($barcode == 'BELUM PUNYA') {
                        //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                        // }
                        // else {
                        //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                        // }
                        //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                        $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail' style='$border_xx margin: 1px 0;'>";
                        // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                        $bg_ot = "bg-hitam";
                        // if (strtolower($skuLabel) == "ot") {
                        //     $bg_ot = "bg-hitam";
                        //     $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                        //     $listed .= "<div style='' class='uploaded $bg_ot'>$name_data / $sku</div>";
                        // }
                        // elseif (strtolower($skuLabel) == "in") {
                        //     $bg_ot = "";
                        // }
                        // else {
                        //
                        //     $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                        //     $listed .= "<div style='' class='uploaded $bg_ot'>$name_data / $sku</div>";
                        // }
                        $listed .= "<div style='' class='uploaded $bg_ot'>$name_data / $sku</div>";

                        $listed .= "</div>";
                    }
                    $listed .= "</div>";
                    $listed .= "</div>";
                }
                $listed .= "</div>";
                $listed .= "</div>";

                $data = array(
                    "mode" => "viewPrint",
                    //                "tmp" => $data_show
                    "tmp"  => $listed
                );
                $this->load->view('barcode', $data);
                //                 arrPRint($ids);
                // matiHere("masukk");
            }
            else {
                cekHijau(__LINE__);
                $arrAlert = array(
                    "type"              => "warning",
                    "title"             => "No data selected",
                    "html"              => "No data selected to print!",
                    "timer"             => "1500",
                    "showConfirmButton" => false,
                    "allowOutsideClick" => false,
                );
                echo swalAlert($arrAlert);
                die(__LINE__);
                echo "<script>topReload(1500)</script>";
                echo topReload();
                echo "</script>";
            }

        }
    }

    public function doPrintModul()
    {
        // $this->load->libraries("phpqrcode/phpqrcode");
        // $this->load->libraries("phpqrcode/qrlib");
        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();

        if (isset($_POST['id_produk'])) {
            $idData = $_POST['id_produk'];
            $jmlPrint = $_POST['jml_print'];
            $hargas = $_POST['harga'];

            // arrPrint($_POST);

            $listedxId = "(" . implode(",", $idData) . ")";
            $className = "MdlProduk2";
            $this->load->model("Mdls/MdlProduk2");
            $o = new MdlProduk2();
            //            $o->addFilter(array("id in"=>"$listedxId"));
            $cindition = "id in $listedxId";
            $tmpX = $o->lookupByCondition($cindition)->result();
            $tempValue = array();
            $tempIdx = array();
            if (sizeof($tmpX) > 0) {
                foreach ($tmpX as $tempData) {
                    $id = $tempData->id;
                    $name = $tempData->nama;
                    $barcode = $tempData->barcode;
                    $code = $tempData->kode;

                    $tempValue[$id] = $barcode;
                    $tempIdx[$id] = $name;
                    $tempCode[$id] = $code;
                }
            }
            $indexID = array();
            //            $data_show = "";
            foreach ($idData as $xId) {
                $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                //                $data_show .= "<div>";
                if ($maxPrint > 0) {
                    $barcode = $tempValue[$xId];
                    $name_data = $tempIdx[$xId];
                    for ($i = 0; $i < $maxPrint; $i++) {
                        $indexID[] = $xId;
                        //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                        //                    </div>
                        //                    </div>";
                    }

                    //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                }
                //                $data_show .= "<div>";
            }

            $jml_kolom = "4";
            $isi_array = sizeof($indexID);
            $max_baris_perkolom = floor($isi_array / $jml_kolom);
            $sisa_baris_ = $isi_array % $jml_kolom;
            // $ekstra = $jml_kolom / $sisa_baris_;
            $row = 0;
            $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
            if ($sisa_baris_ == 1) {
                $addKolom_1 = 1;
                $addKolom_2 = 1;
                $addKolom_3 = 1;
            }
            elseif ($sisa_baris_ == 2) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 2;
            }
            elseif ($sisa_baris_ == 3) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 3;
            }

            foreach ($indexID as $key => $id) {
                $row++;
                if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                    $arrRow[0][] = array(
                        "id" => $id,

                    );
                }
                elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                    $arrRow[1][] = array(
                        "id" => $id,
                    );
                }
                elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                    // cekPink("$row >= $rowke_1");
                    $arrRow[3][] = array(
                        "id" => $id,

                    );
                }
                else {
                    $arrRow[4][] = array(
                        "id" => $id,

                    );
                }
            }


            $listed = "<div class='row'>";
            $listed .= "<style>.thumbnail text{font-size: 2.5em !important;}</style>";
            $listed .= "<div class='container'>";
            foreach ($arrRow as $k => $tmpX) {
                $listed .= "<div class='col-md-3 col-lg-2 col-xs-3' style='padding: 2px; color: #0d1720;'>";
                $listed .= "<div class='text-center'>";
                foreach ($tmpX as $y => $x) {
                    //                    arrPrint($x);
                    $xID = $x['id'];
                    // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                    $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                    $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                    $name_data = $tempIdx[$xID];
                    $harga_f = $hargas[$xID];

                    $qr_dta = array(
                        // "produk_id" => "https://demo.",
                        "produk_id"    => $xID,
                        "produk_kode"  => $code,
                        // "pn" => $name_data,
                        "jenis_tr"     => "0",
                        "master_id"    => "0",
                        "transaksi_id" => "0",
                        // "lain-lain" => "1563265",
                        // "ur" => "https://san.mayagrahakencana.com",
                    );
                    // $barcode = implode("|",$qr_dta);
                    // $barcode = json_encode($qr_dta);

                    // $qrcode = $this->generate_qrcode($barcode);
                    $qrcode = $qr->get_qrcode($qr_dta);
                    $qrfile = base_url() . $qrcode['file'];

                    //                    cekHitam($xID);
                    $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                    // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                    $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";

                    // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none;'></svg>";
                    //                    $listed .= "uuuu";
                    // $listed .= "<span>$code</span>";
                    $listed .= "</div>";
                    $listed .= "</div>";


                    // arrPrintKuning($qrcode);
                    // cekKuning( base_url() . $qrcode['file']);
                    // $listed .= "<img src='" . base_url() . $qrcode['file'] . "'>";
                    $listed .= "<img src='$qrfile' title='$xID'>";
                    //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                    // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                    $listed .= "</div>";
                }
                $listed .= "</div>";
                $listed .= "</div>";
            }
            $listed .= "</div>";
            $listed .= "</div>";

            $data = array(
                "mode" => "viewPrint",
                //                "tmp" => $data_show
                "tmp"  => $listed
            );
            $this->load->view('qr', $data);
        }
        else {
            if (isset($_GET['FromTransaksi'])) {
                /*
                 * masih khusus untuk produksi yang lain nanti cutom dulu ya 09-05-2023 widi
                 */


                // arrPrint($_GET);
                // arrPrint($_GET);
                $prefixTransaksi = "manufactur";
                $custom_produk_print = array();
                if (isset($_SESSION['barcode_print']['items'])) {
                    $custom_produk_print = $_SESSION['barcode_print']['items'];
                }
                // arrPrint($custom_produk_print);

                $trid = $_GET['FromTransaksi'];
                $jenisTr = isset($_GET['jn']) ? $_GET['jn'] : 0;
                $masterId = isset($_GET['mid']) ? $_GET['mid'] : 0;
                $this->load->model("Mdls/MdlManufacturIdentity");
                $this->load->model("Mdls/MdlProduk2");
                $tr = new MdlManufacturIdentity();
                $tr->setFilters(array());
                $tr->addFilter("transaksi_id='$trid'");
                $items = $tr->lookUpAll()->result();
                // matiHere($this->db->last_query());
                $ids = array();
                $jmlPrint = array();
                $proidTransaksi = array();
                if (sizeof($items) > 0) {
                    foreach ($items as $iID => $iData) {
                        $jmlPrint[$iData->produk_id] = isset($custom_produk_print[$iData->produk_id]['jml_print']) ? $custom_produk_print[$iData->produk_id]['jml_print'] : $iData->qty_fase; // belokan ambil jml print dr session
                        $proidTransaksi[$iData->produk_id] = $iData->transaksi_id;
                        $ids[] = $iData->produk_id;
                        // arrPrint($iData);
                    }
                }

                /* ---------------------------------------------
             * harga produk yg dipilih
             * ---------------------------------------------*/

                // arrPrint($jmlPrint);
                // $listedxId = "(" . implode(",", $ids) . ")";
                // $className = "MdlProduk2";
                // $this->load->model("Mdls/MdlProduk2");
                // $o = new MdlProduk2();
                // //            $o->addFilter(array("id in"=>"$listedxId"));
                // $cindition = "id in $listedxId";
                $tmpX = $items;
                // arrPrint($tmpX);
                // matiHEre();
                $tempValue = array();
                $tempIdx = array();
                $tempCode = array();
                $prevData = array();
                if (sizeof($tmpX) > 0) {
                    foreach ($tmpX as $tempData) {
                        $id = $tempData->produk_id;
                        $name = $tempData->produk_nama;
                        // $barcode = $tempData->barcode;
                        // $code = $tempData->kode;

                        // $tempValue[$id] = $barcode;
                        $tempIdx[$id] = $name;
                        $prevData[$id] = $tempData->blobData;
                        // arrprint(blobDecode($tempData->blobData));
                        // $tempCode[$id] = $code;
                    }
                }
                // matiHere();
                $indexID = array();
                //            $data_show = "";
                foreach ($ids as $xId) {
                    $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                    //                $data_show .= "<div>";
                    if ($maxPrint > 0) {
                        $barcode = $tempValue[$xId];
                        $name_data = $tempIdx[$xId];
                        for ($i = 0; $i < $maxPrint; $i++) {
                            $indexID[] = $xId;
                            //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                            //                    </div>
                            //                    </div>";
                        }

                        //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                    }
                    //                $data_show .= "<div>";
                }

                $jml_kolom = "4";
                $isi_array = sizeof($indexID);
                $max_baris_perkolom = floor($isi_array / $jml_kolom);
                $sisa_baris_ = $isi_array % $jml_kolom;
                $row = 0;
                $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
                if ($sisa_baris_ == 1) {
                    $addKolom_1 = 1;
                    $addKolom_2 = 1;
                    $addKolom_3 = 1;
                }
                elseif ($sisa_baris_ == 2) {
                    $addKolom_1 = 1;
                    $addKolom_2 = 2;
                    $addKolom_3 = 2;
                }
                elseif ($sisa_baris_ == 3) {
                    $addKolom_1 = 1;
                    $addKolom_2 = 2;
                    $addKolom_3 = 3;
                }

                foreach ($indexID as $key => $id) {
                    $row++;
                    if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                        $arrRow[0][] = array(
                            "id" => $id,

                        );
                    }
                    elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                        $arrRow[1][] = array(
                            "id" => $id,
                        );
                    }
                    elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                        // cekPink("$row >= $rowke_1");
                        $arrRow[3][] = array(
                            "id" => $id,

                        );
                    }
                    else {
                        $arrRow[4][] = array(
                            "id" => $id,

                        );
                    }
                }

                $listed = "<div class='row'>";
                $listed .= "<div class='container'>";
                foreach ($arrRow as $k => $tmpX) {
                    $listed .= "<div class='col-md-3 col-lg-2 col-xs-3' style='padding: 2px; color: #0d1720;'>";
                    $listed .= "<div class='text-center'>";
                    foreach ($tmpX as $y => $x) {
                        // arrPrintWebs($x);
                        $xID = $x['id'];
                        // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                        $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                        $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                        $name_data = $tempIdx[$xID];
                        // $harga_f = $hargas[$xID];
                        // $harga_f = formatField_he_format('curency_print', $harga_produks[$xID]);
                        // $name =
                        $qr_dta = array(
                            // "produk_id" => "https://demo.",
                            "produk_id"    => $xID,
                            "produk_kode"  => $code,
                            // "pn" => $name_data,
                            "jenis_tr"     => $jenisTr,
                            "master_id"    => $masterId,
                            "transaksi_id" => $proidTransaksi[$xID],
                            "data"         => json_encode(blobDeCode($prevData[$xID])),
                            // "data"=>"sunnnnn",
                            // "lain-lain" => "1563265",
                            // "ur" => "https://san.mayagrahakencana.com",
                        );
                        // $barcode = implode("|",$qr_dta);
                        // $barcode = json_encode($qr_dta);

                        // $qrcode = $this->generate_qrcode($barcode);
                        // arrprint($qr_dta);
                        $qrcode = $qr->get_qrcode($qr_dta);
                        $qrfile = base_url() . $qrcode['file'];
                        //                    cekHitam($xID);
                        $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                        // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                        $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                        $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";

                        // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                        //                    $listed .= "uuuu";
                        // $listed .= "<span>$code</span>";
                        $listed .= "</div>";
                        $listed .= "</div>";


                        // if (validate_EAN13Barcode($barcode)) {
                        //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                        // }
                        // else if ($barcode == 'BELUM PUNYA') {
                        //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                        // }
                        // else {
                        //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                        // }
                        //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                        $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail'>";
                        // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                        $listed .= "</div>";
                    }
                    $listed .= "</div>";
                    $listed .= "</div>";
                }
                $listed .= "</div>";
                $listed .= "</div>";
                // matiHEre(__LINE__);
                $data = array(
                    "mode" => "viewPrint",
                    //                "tmp" => $data_show
                    "tmp"  => $listed
                );
                $this->load->view('barcode', $data);
                //                 arrPRint($ids);
                // matiHere("masukk");
            }
            else {
                $arrAlert = array(
                    "type"              => "warning",
                    "title"             => "No data selected",
                    "html"              => "No data selected to print!",
                    "timer"             => "1500",
                    "showConfirmButton" => false,
                    "allowOutsideClick" => false,
                );
                echo swalAlert($arrAlert);
                die();
                echo "<script>topReload(1500)</script>";
                echo topReload();
                echo "</script>";
            }

        }
    }

    private function set_barcode($code)
    {
        //load library
        $this->load->library('zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');

        //generate barcode
        $rendererOptions = array();
        $arrayReturn = array();
        for ($i = 0; $i <= 1; $i++) {
            $bar_code = Zend_Barcode::factory(
                'code39', 'image', $code[$i], $rendererOptions
            );

            array_push($arrayReturn, $bar_code);

        }
        return $arrayReturn;
        //        Zend_Barcode('code128', 'image', array('text'=>$code), array();
    }

    public function generateCode()
    {
        $arrAlert = array(
            "html"              => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>generate barcode, please wait..<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,

        );
        echo swalAlert($arrAlert);
        $selectedfield = array("id", "nama");
        $className = "MdlProduk2";
        $this->load->model("Mdls/" . $className);
        $o = new MdlProduk2();
        $o->addFilter("barcode =''");

        $tnp = $o->lookupAll()->result();
        $this->db->trans_start();
        if (sizeof($tnp) > 0) {
            $selectData = array();
            foreach ($tnp as $y => $tmp) {
                $tempData = array();
                foreach ($selectedfield as $k => $kolom) {
                    $tempData[$kolom] = $tmp->$kolom;
                }
                $selectData[] = $tempData;
                //            arrPrint($tmp);
            }
            //        arrPrint($selectData);
            $maxLength = "12";
            $output = rand(1, 9);
            $i = 0;
            foreach ($selectData as $data) {
                $xd = $data["id"];
                $lengt = strlen($xd);

                $lengActual = $maxLength - $lengt;
                $x = randomNumber($lengActual);
                $new = "P" . $x . "$xd";
                $update = array(
                    "barcode" => $new,
                );
                $condite = array("id" => $xd);
                $o->updateData($condite, $update);
                cekHitam($this->db->last_query());
            }
        }

        //        matiHere("complate mas bro");
        $this->db->trans_complete();
        $returnURl = base_url() . "addons/BarcodePrinter/index";
        //        matiHere($returnURl);
        echo "<script>top.location.href = '$returnURl';</script>";


    }

    public function dinamic_code()
    {
        $no_transaksi = $this->uri->segment(4);

        $className = "MdlProduk2";
        $this->load->model("Mdls/MdlProduk2");
        $o = new MdlProduk2();
        $objState = "0";
        $alternateLink = "";
        $title = "Barcode";
        $o->addFilter("trash='$objState'");
        $tnp = $o->lookupAll()->result();

        $rebuildProduk = array();
        if (!empty($tnp)) {
            foreach ($tnp as $k => $rows) {
                $rebuildProduk[$rows->id] = array(
                    "nama"    => $rows->nama,
                    "barcode" => $rows->barcode,
                    "kode"    => $rows->kode,
                );
            }
        }

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("nomer='" . $no_transaksi . "'");
        $tr->addFilter("transaksi_data.trash='0'");
        $tmpTr = $tr->lookupJoined()->result();


        foreach ($tmpTr as $row) {
            $id = $row->produk_id;
            $produk_nama = $row->produk_nama;
            $produk_ord_jml = $row->produk_ord_jml;
            $produk_barcode = !empty($rebuildProduk[$id]['barcode']) ? $rebuildProduk[$id]['barcode'] : "";
            $produk_kode = $rebuildProduk[$id]['kode'];

            cekKuning($id . " | " . $produk_nama . " | " . $produk_ord_jml);

            arrPrintWebs($rebuildProduk[$id]);
        }

        //        arrPrint($tmp1);


        //        if (sizeof($arrRow) > 0) {
        //            $i = 0;
        //            $testData = "<div>";
        //            $action = base_url() . "Addons/" . $this->uri->segment(2) . "/doPrint";
        //            $testData .= "<form action='$action' method='post' target='result'>";
        //            foreach ($arrRow as $k => $allData) {
        //                $i++;
        //                $testData .= "<div class='col-md-4 col-xs-4' style='border: #0c0c0c;1px'>";
        //                $testData .= "<table class='table-condensed table-bordered'>";
        //                $testData .= "<tr>";
        //                $testData .= "<th>select<input type='checkbox' id='all' value='on' title='pilih semua yang tersedia' onClick=\"togglecheckboxes(this,'id_produk[]')\"></th>";
        //                $testData .= "<th>barcode</th>";
        //                $testData .= "<th>copies</th>";
        //                $testData .= "<th>action</th>";
        //                foreach ($allData as $l => $valData) {
        //                    $code = isset($valData['barcode']) ? $valData['barcode'] : "BELUM PUNYA";
        //                    $name = $valData['nama'];
        //                    $ids = $valData['id'];
        //                    $imgbarcode = "<div class='no-padding text-center'>
        //                                        <div style='' class='uploaded'><span>$name</span>
        //                                            <svg class='thumbnail' id='c_$ids' style='width:200px;height: 60px;margin-bottom: 1px;padding:0;margin:0;'></svg>
        //                                        </div>
        //                                    </div>";
        //
        //                    if (validate_EAN13Barcode($code)) {
        //                        $imgbarcode .= "<script>JsBarcode('#c_$ids', '$code', {format: 'ean13'});</script>";
        //                    }
        //                    else if ($code == 'BELUM PUNYA') {
        //                        $imgbarcode .= "<script>JsBarcode('#c_$ids', '$code', {format: 'code39', lineColor: '#e02907'});</script>";
        //                    }
        //                    else {
        //                        $imgbarcode .= "<script>JsBarcode('#c_$ids', '$code', {format: 'code39'});</script>";
        //                    }
        //
        //                    $testData .= "<tr>";
        //                    $testData .= "<td><input type='checkbox' id='p_$ids' name='id_produk[]' value='$ids' class='form-hcontrol pull-right' onchange=\"fillValue_$ids()\"></td>";
        //                    $testData .= "<td>$imgbarcode</td>";
        //                    $testData .= "<td><input type='text' id='jml_print_$ids' name='jml_print[$ids]' class='form-control' onkeyup=''></td>";
        //                    $testData .= "<td><button class='btn btn-default' type='submit'><span class='fa fa-print'></span></td>";
        //
        //                    $testData .= "</tr>";
        //                    $testData .= "
        //                                <script>
        //                                    $('input[name=\\'jml_print\\[$ids\\]\\']').on('keyup',function(){
        //                                        $('input#p_$ids').prop('checked',true);
        //                                    })
        //                                </script>";
        //
        //                    $testData .= "
        //                                <script>
        //                                    function fillValue_$ids() {
        //                                        var cheked_id = document.getElementById('p_$ids');
        //                                        if (cheked_id.checked) {
        //                                            document.getElementById(\"jml_print_$ids\").value = 1
        //                                        }
        //                                        else{
        //                                            document.getElementById(\"jml_print_$ids\").value = ''
        //                                        }
        //                                    }
        //                                </script>";
        //                }
        //                $testData .= "<script>
        //                            function togglecheckboxes(master,group){
        //                                var cbarray = document.getElementsByName(group);
        //                                for(var i = 0; i < cbarray.length; i++){
        //                                    console.log(cbarray[i]);
        //                                    cbarray[i].checked = master.checked;
        //                                    $(cbarray[i]).trigger('change');
        //                                }
        //                            }
        //                            </script>";
        //                $testData .= "</table>";
        //                $testData .= "</div>";
        //            }
        //
        //            $testData .= "<div class='row' style='margin-bottom: 5px;'></div>";
        //            $testData .= "<button type='submit' class='btn btn-block btn-warning'>Print All Selected</button>";
        //            $testData .= "</form>";
        //            $testData .= "</div>";
        //        }

        $arrProduk[111] = array(
            "nama"    => "nama produk 111",
            "barcode" => "1345289634",
        );

        $arrProduk[444] = array(
            "nama"    => "nama produk 444",
            "barcode" => "1345289634",
        );

        $testData = "";
        $subtitle = "";

        $data = array(
            "mode"         => $this->uri->segment(3),
            "errMsg"       => $this->session->errMsg,
            "title"        => "$subtitle" . "",
            "subTitle"     => "Registered barcode",
            //            "strActiveDataTitle" => "<span class='glyphicon glyphicon-th-list'></span> List of $title",
            //            "linkStr" => isset($params['links']) ? $params['links'] : "",
            "arrayHistory" => $testData,
            //            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            //            "alternateLink" => $alternateLink,
            "thisPage"     => base_url() . "addons/" . get_class($this) . "/" . $this->uri->segment(3) . "/" . "?trashed=$objState",
            //            "faddLink" => isset($faddLink) ? $faddLink : "",
            //            "feditLink" => isset($fupdateLink) ? $fupdateLink : "",
            //            "fdeleteLink" => isset($fdeleteLink) ? $fdeleteLink : "",
            //            "fmdlName" => isset($fmdlName) ? $fmdlName : "",
            //            "fmdlTarget" => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
            "btn_gen"      => "",
        );
        $this->load->view('barcode', $data);
    }

    public function prePrintSave()
    {
        // cekBiru(url_segment());
        // arrPrint($_GET);
        if (isset($_GET['transaksi'])) {
            unset($_SESSION['barcode_print']);
            $tr_jenis = url_segment(4);
            $tr_id = url_segment(5);

            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
            $detail = $tr->lookupDetailTransaksi($tr_id)->result();

            // arrPrint($detail);
            foreach ($detail as $item) {
                $pr_id = $item->produk_id;
                $pr_jml = $item->produk_ord_jml;

                $_SESSION['barcode_print'][$pr_id] = $pr_jml;
            }

            redirect(base_url() . "addons/BarcodePrinter/searching?gr=czoyMToidXRpbGl0eS1iYXJjb2RlX3ByaW50Ijs=");
        }

        if (isset($_GET['id'])) {

            $_SESSION['barcode_print'][$_GET['id']] = $_GET['jml'];
        }
        // unset($_SESSION['barcode_print']);
        $jml_cetak = array_filter($_SESSION['barcode_print']);

        $produk_ids = isset($_SESSION['barcode_print']) ? array_filter(array_keys($_SESSION['barcode_print'])) : array();
        // arrPrint($produk_ids);
        // arrPrint($jml_cetak);

        $getCid = 5;
        // $produk_ids = isset($_SESSION['barcode_print'][$_GET['id']]) ? $_SESSION['barcode_print'][$_GET['id']] : array();
        $this->load->model("Mdls/MdlProduk2");
        $pr = new MdlProduk2();
        $srcDatas = $pr->callSpecs($produk_ids);
        // showLast_query("lime");
        /* ---------------------------------------------
        * harga produk yg dipilih
        * ---------------------------------------------*/
        $cabang_pilihan = $getCid;
        $harga_produks = array();
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();
        $condite_hargas = array(
            "cabang_id" => "$cabang_pilihan",
        );
        $this->db->where($condite_hargas);
        $hrgs = $hp->callProdukHarga($produk_ids);
        // showLast_query("merah");
        // arrPrintKuning($hrgs);
        foreach ($hrgs as $hrg_datas) {
            foreach ($hrg_datas as $cb_id => $hrg_data) {
                $pro_id = $hrg_data['jual_nppn']['produk_id'];
                $pro_harga = $hrg_data['jual_nppn']['nilai'];

                $harga_produks[$pro_id] = $pro_harga;
            }
        }
        // ---------------------------------------------
        $link_clear = base_url() . "addons/Qr/prePrintClear";
        $action = base_url() . "addons/Qr/doPrint";
        echo "<form action='$action' method='post' target='result'>";
        // echo " <button type='button' class='btn btn-link' onclick=\"location.href='$link_clear'\"><i class='fa fa-times text-danger'></i></button>";
        echo " <button type='button' title='klik untuk mereset pilihan barcode' data-toggle='tooltip' class='btn btn-link' onclick=\"confirm_alert_result('Perhatian','barcode yg sudah dipilih akan dibuang','$link_clear')\"><i class='fa fa-times text-danger'></i></button>";
        foreach ($srcDatas as $srcData) {
            // arrPrint($srcData);
            $id = $srcData->id;
            $nama = $srcData->nama;
            $kode = $srcData->kode;
            $jml = isset($jml_cetak[$id]) ? $jml_cetak[$id] : "";
            $harga = isset($harga_produks[$id]) ? $harga_produks[$id] : "0";
            $harga_f = formatField_he_format('curency_print', $harga) . ",-";
            $vieweVal = "$kode [$jml]";
            if ($jml > 0) {
                // $link_barcode = base_url() . "Addons/BarcodePrinter/hasilSearching?k='+encodeURI(this.value)"
                $link_barcode = base_url() . "addons/Qr/hasilSearching";
                echo "<button type='button' class='btn btn-success' style='margin-bottom: 1px;' title='klik untuk mengedit jumlah yang akan dicetak pada $nama' data-toggle='tooltip' readonly onclick=\"$('#main_page').load('$link_barcode?k='+encodeURI('$nama'));\">$nama [$jml]</button> ";
                echo "<input type='hidden' name='id_produk[]'  value='$id'> ";
                echo "<input type='hidden' name='jml_print[$id]' value='$jml'> ";
                echo "<input type='hidden' name='harga[$id]' value='$harga_f'> ";
            }
        }
        // echo " <button type='button' title='klik untuk mengedit jumlah yang akan dicetak seluruh barcode' data-toggle='tooltip' class='btn btn-link' onclick=\"\"><i class='fa fa-pencil text-warning'></i></button>";
        echo " <button type='submit' title='klik untuk mencetak barcode yang sudah dipilih' data-toggle='tooltip' class='btn btn-link'><i class='fa fa-print'></i></button>";
        echo "</form>";

    }

    public function prePrintClear()
    {
        unset($_SESSION['barcode_print']);

        topReload("200");
    }

    public function searching()
    {
        $allowed_access = array(
            "c_holdingg",
            "c_purchasing_admg",
            // "o_seller"
        );
        // cekBiru(my_memberships());
        $allowed = 0;
        foreach (my_memberships() as $my_membership) {
            if (in_array($my_membership, $allowed_access)) {
                $allowed = 1;
                break;
            }
        }

        $className = "MdlProduk2";
        $this->load->model("Mdls/MdlProduk2");
        $o = new MdlProduk2();

        $navigasi_filter = "";
        $objState = "0";
        $alternateLink = "";
        $title = "QR Code";
        $o->addFilter("trash='$objState'");
        $getCid = isset($_GET['cb']) ? $_GET['cb'] : (my_cabang_id() > 1 ? my_cabang_id() : 5);
        $getF_str = "";
        $link_searching = base_url() . "addons/Qr/hasilSearching";
        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $o->addFilter("folders='" . $_GET['fID'] . "'");
            //            $title.=" on ".$_GET['fName'];
            // $get
            $getF_str = "&fID=" . $_GET['fID'];
        }
        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }
        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";

            // $navigasi_filter_bottom = "<script>
            //     window.setTimeout(function(){
            //         $('#main_page').load('$link_searching?k='+encodeURI(+'$key'+));
            //     } ,2000);</script>";

            // cekKuning(__LINE__);
            // $navigasi_filter_bottom = "<script>$('#main_page').load('$link_searching?k=$key');</script>";
            $navigasi_filter_bottom = "<script>$('#main_page').load('$link_searching?k='+encodeURI($key)+');</script>";
        }
        else {
            $key = "";
            $subtitle = "Daftar $title";
            $navigasi_filter_bottom = "";
        }

        /* ----------------------------------------------------------
             * navigasi filter
             * ----------------------------------------------------------*/

        $getfID = isset($_GET['fID']) ? $_GET['fID'] : "";
        $getGr = isset($_GET['gr']) ? $_GET['gr'] : "";
        $get_gr = isset($_GET['gr']) ? 'gr=' . $getGr : "";
        $get_key = isset($_GET['k']) ? '&k=' . $key : "";
        $get_k = $key;

        $i = 0;

        $lebar_searching = $allowed == 1 ? 6 : 8;
        // -----------------------------------------------------------------------------------------------------
        $navigasi_filter .= "<div class='row'>";

        $navigasi_filter .= "<div class='col-md-$lebar_searching'>";
        $navigasi_filter .= "<form method='get'>";
        $navigasi_filter .= "<div class='input-group'>";
        // $link_searching = base_url() . "Addons/BarcodePrinter/hasilSearching";
        $navigasi_filter .= "<input type='text' autocomplete='off' autofocus='on' placeholder='Pencarian Produk' class='form-control' name='k' value='$get_k' onkeyup=\"$('#main_page').load('$link_searching?k='+encodeURI(this.value));\" onclick=\"$('#main_page').load('$link_searching?k='+encodeURI(this.value));\">";
        $navigasi_filter .= "<input type='hidden' name='gr' value='$getGr' >";
        if (isset($_GET['fID'])) {
            $navigasi_filter .= "<input type='hidden' name='fID' value='$getfID' >";
        }
        if (isset($_GET['cb'])) {
            $navigasi_filter .= "<input type='hidden' name='cb' value='$getCid' >";
        }
        $navigasi_filter .= "<span class='input-group-btn'><button type='submit' class='btn btn-primary'>CARI</button></span>";
        $navigasi_filter .= "</div>";
        $navigasi_filter .= "</form>";
        $navigasi_filter .= "</div>";
        // -----------------------------------------------------------------------------------------------------
        $link_halaman_ini = base_url(uri_string()) . "?$get_gr" . $getF_str;
        //region cabang id
        if ($allowed == 1) {
            $this->load->model("Mdls/MdlCabang");
            $cb = new MdlCabang();
            $srcCabangs = $cb->lookupAll()->result();
            // arrPrint($srcCabangs);
            $cabangs = array();
            foreach ($srcCabangs as $srcCabang) {
                $cabangs[$srcCabang->id] = $srcCabang->nama;
            }

            $navigasi_filter .= "<div class='col-md-2'>";
            $navigasi_filter .= "<select class='btn btn-info btn-block' name='cb' onchange=\"location.href='$link_halaman_ini&cb='+ encodeURI(this.value);\">";
            $navigasi_filter .= "<option>-pilih cabang-</option>";
            foreach ($cabangs as $cabang_id => $cabang_nama) {
                $cDipilih = $getCid == $cabang_id ? "selected" : "";
                $navigasi_filter .= "<option $cDipilih value='$cabang_id'>$cabang_nama</option>";
            }
            $navigasi_filter .= "</select>";
            $navigasi_filter .= "</div>";
        }
        //endregion
        // -----------------------------------------------------------------------------------------------------
        //region kategori produk
        if ($allowed == 1) {
            $this->load->model("Mdls/MdlFolderProduk");
            $fp = new MdlFolderProduk();
            $srcFolders = $fp->lookupAll()->result();
            // arrPrint($srcFolders);
            $folders = array();
            foreach ($srcFolders as $srcFolder) {
                $fol_id = $srcFolder->id;
                $fol_nama = $srcFolder->nama;

                $folders[$fol_id] = $fol_nama;
            }

            $navigasi_filter .= "<div class='col-md-2'>";
            $navigasi_filter .= "<select class='btn btn-warning btn-block' name='fID' onchange=\"location.href='$link_halaman_ini&fID='+ this.value;\">";
            $navigasi_filter .= "<option value=''>-pilih kategori-</option>";
            foreach ($folders as $folder_id => $folder_nama) {
                $fDipilih = $getfID == $folder_id ? "selected" : "";
                $navigasi_filter .= "<option $fDipilih value='$folder_id'>$folder_nama</option>";
            }
            $navigasi_filter .= "</select>";
            $navigasi_filter .= "</div>";
        }
        //endregion

        if ($allowed == 1) {
            $navigasi_filter .= "<div class='col-md-1'>";
            $link_purchasing = base_url() . "pembelian/History/viewHistory/466?gr=cGVtYmVsaWFu";
            $navigasi_filter .= "<button type='button' class='btn btn-danger' onclick=\"location.href='$link_purchasing'\">History Purchasing</button>";
            $navigasi_filter .= "</div>";
        }

        $navigasi_filter .= "</div>";
        $navigasi_filter .= "<div id='preprint' style='margin: 10px 0;'></div>";
        /* -----------------------------------------------------------------------------
         * loader untuk produk yg dipilih
         * -----------------------------------------------------------------------------*/
        $produk_pilihans = array();
        if (isset($_SESSION['barcode_print'])) {
            $link_preprint_save = base_url() . "addons/" . $this->uri->segment(2) . "/prePrintSave";
            $navigasi_filter .= "<script>$('#preprint').load('$link_preprint_save');</script>";

            $barcode_pilihans = array_filter($_SESSION['barcode_print']);
            foreach ($barcode_pilihans as $item_id => $item_jml) {
                $produk_pilihans[$item_id]['id'] = $item_id;
                $produk_pilihans[$item_id]['jml'] = $item_jml;
            }
        }
        // $navigasi_filter .= $navigasi_filter_bottom;
        // $generate = $navigasi_filter_bottom;
        // arrPrint($produk_pilihans);
        // -----------------------------------------------------------------------------------------------------
        $generate = "";

        $testData = "";
        $testData .= "<div id='hasil_pencarian'>ketikan kata kunci dari produk yang akan dicari</div>";
        $testData .= $navigasi_filter_bottom;

        $data = array(
            "mode"                => "index", // index
            "errMsg"              => $this->session->errMsg,
            "title"               => "$subtitle" . "",
            "subTitle"            => "Registered barcode",
            "strActiveDataTitle"  => "<span class='glyphicon glyphicon-th-list'></span> List of $title",
            "linkStr"             => isset($params['links']) ? $params['links'] : "",
            "arrayHistory"        => $testData,
            "strDataProposeTitle" => "<span class='glyphicon glyphicon-alert blink'></span>&nbsp; <span class='tebal'>approval needed</span>",
            "alternateLink"       => $alternateLink,
            "thisPage"            => base_url() . "addons/" . get_class($this) . "/" . $this->uri->segment(3) . "/" . "?trashed=$objState",
            "faddLink"            => isset($faddLink) ? $faddLink : "",
            "feditLink"           => isset($fupdateLink) ? $fupdateLink : "",
            "fdeleteLink"         => isset($fdeleteLink) ? $fdeleteLink : "",
            "fmdlName"            => isset($fmdlName) ? $fmdlName : "",
            "fmdlTarget"          => isset($fmdlName) ? base_url() . get_class($this) . "/view/" . str_replace("Mdl", "", $fmdlName) : "",
            "btn_gen"             => $generate,
            "navigasi_top"        => $navigasi_filter,
            // "navigasi_top"        => "",
        );
        $this->load->view('qr', $data);

    }

    public function hasilSearching()
    {
        $allowed_access = array(
            "c_holding",
            // "o_seller",
        );
        // cekBiru(my_memberships());
        $allowed = 0;
        foreach (my_memberships() as $my_membership) {
            if (in_array($my_membership, $allowed_access)) {
                $allowed = 1;
                break;
            }
        }
        // cekMerah($allowed);
        arrPrint($_REQUEST);
        $this->load->model("Mdls/MdlProduk2");
        $o = new MdlProduk2();

        $getCid = isset($_GET['cb']) ? $_GET['cb'] : (my_cabang_id() > 1 ? my_cabang_id() : 5);
        // cekKuning("$getCid");

        $getF_str = "";
        if (isset($_GET['fID']) && strlen($_GET['fID']) > 0) {
            $o->addFilter("folders='" . $_GET['fID'] . "'");
            //            $title.=" on ".$_GET['fName'];
            // $get
            $getF_str = "&fID=" . $_GET['fID'];
        }
        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
            $o->addFilter($_GET['reqField'] . "='" . $_GET['reqVal'] . "'");
        }
        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";
            // cekBiru();
        }
        else {
            $key = "";
            // $subtitle = "Daftar $title";
        }

        $var = "";
        if (strlen($key) >= 3) {

            $limit_per_page = $total_records = $o->lookupDataCount($key);
            $page = "";

            $this->load->model("Mdls/MdlProdukPerSerialNumber");
            $pps = new MdlProdukPerSerialNumber();
            $arrRow = $tmps = $srcs = $pps->lookupJoinProduk($key)->result_array();
            showLast_query("kuning");
            $jml_srcs = count($srcs);

            cekHere("$jml_srcs || ");
            arrPrint($srcs);

            // $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
            // $arrRow = $tmps = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination
            // showLast_query("biru");
            // // cekMerah($total_records);
            arrPrintPink($tmps);
            $produk_pilihans = array();
            if (isset($_SESSION['barcode_print'])) {
                // $link_preprint_save = base_url() . "Addons/" . $this->uri->segment(2) . "/prePrintSave";
                // $navigasi_filter .= "<script>$('#preprint').load('$link_preprint_save');</script>";

                $barcode_pilihans = array_filter($_SESSION['barcode_print']);
                foreach ($barcode_pilihans as $item_id => $item_jml) {
                    $produk_pilihans[$item_id]['id'] = $item_id;
                    $produk_pilihans[$item_id]['jml'] = $item_jml;
                }
            }


            if ($total_records > 0) {


                /* ---------------------------------------------
                 * last purchase
                 * ---------------------------------------------*/
                $idProduks = array();
                foreach ($tmps as $indx => $tmp0) {
                    $idProduks[] = $tmp0->id;
                }
                $this->load->model("Mdls/MdlBi");
                $bb = new MdlBi();
                // $lastPurchase = $bb->getLastPurchase($idProduks);
                $lastPurchase = array();
                $lpDatas = isset($lastPurchase['datas']) ? $lastPurchase['datas'] : array();
                // arrPrintPink($lpDatas);
                $supplierProduks = array();
                $allSuppliers = array();
                foreach ($lpDatas as $lpProd_id => $srcPps) {
                    $arrSpeks = array();
                    foreach ($srcPps as $lpSupp_id => $lpParams) {

                        $arrSpeks[$lpSupp_id] = $lpParams;
                        $allSuppliers[$lpSupp_id] = $lpSupp_id;
                    }

                    $lastPurchases[$lpProd_id] = reset($arrSpeks);
                    $supplierProduks[$lpProd_id] = array_keys($arrSpeks);

                }
                // -------------------------------------
                $stokNows = $bb->getStokNow($idProduks);
                $stokCabangNows = isset($stokNows['sums'][$getCid]) ? $stokNows['sums'][$getCid] : 0;
                // arrPrintWebs($stokCabangNows);
                // arrPrintWebs($allSuppliers);
                /* ---------------------------------------------
                 * data supplier terpilih
                 * ---------------------------------------------*/
                $spSpeks = array();
                if (sizeof($allSuppliers) > 0) {

                    $this->load->model("Mdls/MdlSupplier");
                    $sp = new MdlSupplier();
                    $spSpeks = $sp->callSpecs($allSuppliers);
                }
                // arrPrintWebs($spSpeks);

                /* ---------------------------------------------
                 * harga produk yg dipilih
                 * ---------------------------------------------*/
                $cabang_pilihan = $getCid;
                $harga_produks = array();
                $this->load->model("Mdls/MdlHargaProduk");
                $hp = new MdlHargaProduk();
                $condite_hargas = array(
                    "cabang_id" => "$cabang_pilihan",
                );
                $this->db->where($condite_hargas);
                $hrgs = $hp->callProdukHarga($idProduks);
                // showLast_query("merah");
                // arrPrintKuning($hrgs);

                foreach ($hrgs as $hrg_datas) {
                    foreach ($hrg_datas as $cb_id => $hrg_data) {
                        $pro_id = isset($hrg_data['jual_nppn']) ? $hrg_data['jual_nppn']['produk_id'] : 0;
                        $pro_harga = isset($hrg_data['jual_nppn']) ? $hrg_data['jual_nppn']['nilai'] : 0;

                        $harga_produks[$pro_id] = $pro_harga;
                    }
                }
                // ---------------------------------------------
                $var .= "<style>.control-label{padding-top: 0 !important;}</style>";
                $prod_koloms = array(
                    "nama"         => array(
                        "label" => "nama produk"
                    ),
                    "folders_nama" => array(
                        "label" => "kategori"
                    ),
                    "barcode"      => array(
                        "label" => "barcode"
                    ),
                    "merek_nama"   => array(
                        "label" => "merek"
                    ),
                );

                $var .= "<div class='row oveflow-h'>";
                $action = base_url() . "addons/" . $this->uri->segment(2) . "/doPrint";
                $var .= "<form action='$action' method='post' target='result'>";
                // arrPrintPink($arrRow);
                /*------------------------------------------------------------------*/
                $tbl_id = "hasil_pencarian_barcode";
                $tabl = "<div class='col-md-12'>";
                $tabl .= "<table class='table table-hover table-condensed' id='$tbl_id'>";
                $tabl .= "<thead>";
                $tabl .= "<tr class='text-uppercase bg-primary'>";
                $tabl .= "<th>kode</th>";
                foreach ($prod_koloms as $prod_kolom => $prod_params) {
                    $kolom_alisa = $prod_params['label'];
                    $tabl .= "<th>$kolom_alisa</th>";
                }
                $tabl .= "<th>persediaan</th>";
                $tabl .= "<th>HPP terakhir</th>";
                // $tabl .= "<th>tgl pembelian</th>";
                // $tabl .= "<th>vendor lainnya</th>";
                // $tabl .= "<th>Jml dicetak</th>";
                $tabl .= "</tr>";
                $tabl .= "</thead>";
                $tabl .= "<tbody>";
                // $tabl .= "</tbody>";
                /*------------------------------------------------------------------*/
                $i = 0;
                foreach ($arrRow as $k => $allData) {
                    $valData = $allData;
                    $i++;
                    $code = isset($valData->kode) ? $valData->kode : "BELUM PUNYA";
                    $name = $valData->nama;
                    $kategori = isset($valData->folder_nama) ? $valData->folder_nama : "";
                    $ids = $valData->id;
                    $imgbarcode = "<div class='no-padding text-center'>
                                        <div style='' class='uploaded font-size-0-7'><span>$name</span>
                                                <svg class='thumbnail' id='c_$ids' style='width:150px;height: 60px;margin-bottom: 1px;padding:0;margin:0;'></svg>
                                        </div>
                                    </div>";

                    if (validate_EAN13Barcode($code)) {
                        $imgbarcode .= "<script>JsBarcode('#c_$ids', '$code', {format: 'ean13', lineColor: '#0d1720'});</script>";
                    }
                    else {
                        if ($code == 'BELUM PUNYA') {
                            $imgbarcode .= "<script>JsBarcode('#c_$ids', '$code', {format: 'code39', lineColor: '#e02907'});</script>";
                        }
                        else {
                            $imgbarcode .= "<script>JsBarcode('#c_$ids', '$code', {format: 'code39', lineColor: '#0d1720'});</script>";
                        }
                    }
                    $harga = isset($harga_produks[$ids]) ? $harga_produks[$ids] : 0;
                    $harga_f = formatField_he_format('curency_print', $harga) . ",-";
                    // $harga_str = "<div style='margin: -69px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;background-color: #ffffff;z-index: 5000;'>$harga_f</div>";
                    // $harga_str .= "<input type='hidden' name='harga[$ids]' value='$harga_f'>";
                    $checked_ses = isset($produk_pilihans[$ids]['id']) && ($produk_pilihans[$ids]['id'] == $ids) ? "checked" : "";
                    $jml_print_ses = isset($produk_pilihans[$ids]['jml']) ? $produk_pilihans[$ids]['jml'] : "";
                    $link_preprint_save = base_url() . "addons/" . $this->uri->segment(2) . "/prePrintSave?id=$ids";
                    $height_val = $allowed == true ? "320" : "210";

                    $cekbox = "<input type='checkbox' id='p_$ids' name='id_produk[]' value='$ids' class='form-hcontrol' $checked_ses onchange=\"fillValue_$ids()\">";
                    $jml_stok = isset($stokCabangNows[$ids]) ? $stokCabangNows[$ids]['qty_debet_sum'] : 0;
                    $inputbox = "<input type='text' id='jml_print_$ids' name='jml_print[$ids]' class='form-control' onkeyup='' value='$jml_print_ses' onblur=\"$('#preprint').load('$link_preprint_save&jml='+this.value)\">";
                    $jsSuport = "<script>$('input[name=\\'jml_print\\[$ids\\]\\']').on('keyup',function(){                                                               
                                $('input#p_$ids').prop('checked',true);
                                })</script>";
                    $jsSuport_2 = "<script> 
                            function fillValue_$ids() {
                                var cheked_id = document.getElementById('p_$ids');
                                if (cheked_id.checked) {
                                document.getElementById(\"jml_print_$ids\").value = 1;
                                $('#preprint').load('$link_preprint_save&jml=1')
                              } else {
                               document.getElementById(\"jml_print_$ids\").value = ''
                               $('#preprint').load('$link_preprint_save&jml=0')
                              }
                              
                            }
                            </script>";

                    if ($allowed == true) {

                        $hpp = isset($lastPurchases[$ids]) ? $lastPurchases[$ids]['nilai'] : 0;
                        $hpp_f = formatField_he_format("curency", $hpp);
                        if (isset($lastPurchases[$ids]['dtime'])) {
                            $last_dtime = $lastPurchases[$ids]['dtime'];
                            $last_dtime_f = formatField_he_format("dtime", $last_dtime);
                            $supplier_id_last = $lastPurchases[$ids]['suppliers_id'];
                            $supplier_nama_last = $spSpeks[$supplier_id_last]->nama;
                            $last_purchase_data = "$last_dtime_f <br>-$supplier_nama_last";

                        }
                        else {
                            $last_dtime_f = "--";
                            $last_purchase_data = "--";
                        }

                        $hasil = '';
                        if (isset($supplierProduks[$ids])) {
                            foreach ($supplierProduks[$ids] as $supplierid) {
                                // $var = "'$nilai'";
                                if ($supplierid != $supplier_id_last) {
                                    $supplier_nama = $spSpeks[$supplierid]->nama;
                                    if ($hasil == "") {
                                        $hasil .= "-$supplier_nama";
                                    }
                                    else {
                                        $hasil = "$hasil" . "<br>-$supplier_nama";
                                    }
                                }
                                // else{
                                //     $hasil = "--";
                                // }
                            }
                        }
                    }

                    $dipakai = "baris";
                    if ($dipakai == "kotak") {
                        $var .= "<div class='col-md-4 col-xs-4' style='borderr: #0c0c0c 1px solid;height: " . $height_val . "px;'>";

                        $var .= "<div class='box box-info box-solid'>";
                        $var .= "<div class='box-header with-border'>";

                        $var .= "<div class='form-group'>";
                        $var .= "<div class='checkbox'>";
                        $var .= "<label>";
                        // $var .= "<input type='checkbox' id='p_$ids' name='id_produk[]' value='$ids' class='form-hcontrol' $checked_ses onchange=\"fillValue_$ids()\">";
                        $var .= "$cekbox";
                        // $var .= "$imgbarcode";
                        $var .= " $code";
                        $var .= "</label>";
                        $var .= "</div>";
                        $var .= "</div>";
                        $var .= "</div>";

                        $var .= "<div class='form-horizontal'>";
                        $var .= "<div class='box-body'>";
                        /* --------------------------------------------------------
                         * penampil data produk diatus di-$prod_koloms
                         * --------------------------------------------------------*/
                        foreach ($prod_koloms as $prod_kolom => $prod_params) {
                            $kolom_alisa = $prod_params['label'];
                            $kolom_nilai = $valData->$prod_kolom;
                            $var .= "<div class='form-group'>";
                            $var .= "<label for='inputPassword3' class='col-sm-4 control-label'>$kolom_alisa</label>";
                            $var .= "<div class='col-sm-8'>$kolom_nilai</div>";
                            $var .= "</div>";
                        }
                        // -----------------------------------------------------------
                        $var .= "<div class='form-group'>";
                        $var .= "<label for='inputPassword3' class='col-sm-4 control-label'>Harga</label>";
                        $var .= "<div class='col-sm-8'>$harga_f</div>";
                        $var .= "</div>";
                        // ---
                        // $jml_stok = isset($stokCabangNows[$ids]) ? $stokCabangNows[$ids]['qty_debet_sum'] : 0;
                        $var .= "<div class='form-group'>";
                        $var .= "<label for='inputPassword3' class='col-sm-4 control-label'>persediaan</label>";
                        $var .= "<div class='col-sm-8'>$jml_stok</div>";
                        $var .= "</div>";
                        /* --------------------------------------------------------
                         * penampil data last purchas dibatasi group akses
                         * --------------------------------------------------------*/
                        if ($allowed == true) {
                            $var .= "<div style='color: #ff1493;'>";
                            $var .= "<div class='form-group'>";
                            $var .= "<label for='inputPassword3' class='col-sm-4 control-label'>HPP terakhir</label>";
                            $var .= "<div class='col-sm-8'>$hpp_f</div>";
                            $var .= "</div>";

                            $var .= "<div class='form-group'>";
                            $var .= "<label for='inputPassword3' class='col-sm-4 control-label'>pembelian teakhir</label>";
                            $var .= "<div class='col-sm-8'>$last_purchase_data</div>";
                            $var .= "</div>";

                            $var .= "<div class='form-group'>";
                            $var .= "<label for='inputPassword3' class='col-sm-4 control-label'>supplier</label>";
                            $var .= "<div class='col-sm-8'>$hasil</div>";
                            $var .= "</div>";
                            $var .= "</div>";
                        }
                        /* --------------------------------------------------------
                         * jml cetak
                         * --------------------------------------------------------*/
                        $var .= "<div class='form-group'>";
                        $var .= "<label for='inputPassword3' class='col-sm-4 control-label'>jml cetak</label>";
                        // $var .= "<div class='col-sm-8'><input type='text' id='jml_print_$ids' name='jml_print[$ids]' class='form-control' onkeyup='' value='$jml_print_ses' onblur=\"$('#preprint').load('$link_preprint_save&jml='+this.value)\"></div>";
                        $var .= "<div class='col-sm-8'>$inputbox</div>";
                        $var .= "</div>";
                        // $var .= "<input type='text' id='jml_print_$ids' name='jml_print[$ids]' class='form-control' onkeyup='' value='$jml_print_ses' onblur=\"$('#preprint').load('$link_preprint_save&jml='+this.value)\">";

                        $var .= "</div>"; // body
                        $var .= "</div>"; // form-horizontal

                        $var .= "</div>";

                        $var .= "</div>";

                        $var .= $jsSuport;
                        $var .= $jsSuport_2;
                    }
                    else {
                        $tabl .= "<tr>";
                        $tabl .= "<td>$cekbox $code</td>";
                        foreach ($prod_koloms as $prod_kolom => $prod_params) {
                            $kolom_alisa = $prod_params['label'];
                            $kolom_nilai = isset($valData->$prod_kolom) ? $valData->$prod_kolom : "-";
                            $tabl .= "<td>$kolom_nilai</td>";
                        }
                        $tabl .= "<td>$jml_stok</td>";
                        /* --------------------------------------------------------
                         * penampil data last purchas dibatasi group akses
                         * --------------------------------------------------------*/
                        if ($allowed == true) {
                            // $tabl .= "<td>$hpp_f</td>";
                            // $tabl .= "<td>$last_purchase_data</td>";
                            // $tabl .= "<td>$hasil</td>";
                        }
                        $tabl .= "<td>$inputbox</td>";
                        $tabl .= "</tr>";
                        $tabl .= $jsSuport;
                        $tabl .= $jsSuport_2;
                    }
                    /* ------------------------
                     * pengatur fungsi bacground
                     * ------------------------*/

                    // $var .= "<script>
                    //         function togglecheckboxes(master,group){
                    //             var cbarray = document.getElementsByName(group);
                    //             for(var i = 0; i < cbarray.length; i++){
                    //                 console.log(cbarray[i]);
                    //                 cbarray[i].checked = master.checked;
                    //                 $(cbarray[i]).trigger('change');
                    //             }
                    //         }
                    //
                    //
                    //         </script>";
                }
                $tabl .= "</tbody>";
                $tabl .= "</table>";
                $tabl .= "</div>";
                $tabl .= "<script>
                
                // $(document).ready( setTimeout( function(){                                       
                    var datareview = $('table#$tbl_id').DataTable({
                                       
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    stateSave: false,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    paging: false,
                                    // processing: true,
                                    buttons: [],
                                    // buttons: [
                                            // 'copy',
                                            // {
                                            //     extend: 'csvHtml5',
                                            //     text: 'CSV',
                                            //     exportOptions: {
                                            //         modifier: {
                                            //             search: 'none'
                                            //         },
                                            //         format: {
                                            //             body: function ( data, row, column, node ) {
                                            //                 if( /<\/?[a-z][\s\S]*>/i.test(data) ){
                                            //                     var indexnya = $(data).text().indexOf('>')
                                            //                     if(indexnya>0){
                                            //                         var result = $(data).text().substring(indexnya + 1);
                                            //                         return result;
                                            //                         //console.error( result );
                                            //                     }
                                            //
                                            //                     return $(data).text()
                                            //                 }
                                            //                 else{   
                                            //                     return data;
                                            //                     //console.log(data);
                                            //                 }
                                            //             }
                                            //         }
                                            //     },
                                            // },
                                            // 'excel',
                                            // 'pdf',
                                            // 'print',
                                            // ]
                                    
                                        });
                    
                    
                                    // $('.table-responsive.tblid_$tbl_id').floatingScroll();
                                    //     $('.table-responsive.tblid_$tbl_id').scroll(
                                    //         delay_v2(function () {
                                    //         $('table#$tbl_id').DataTable().fixedHeader.adjust();
                                    //         }, 200)
                                    //     );
                                    //
                                    // }, 500));

                
                </script>";

                $var .= $tabl;
                $var .= "</form>";
                $var .= "</div>";
                // $var .= "anu";
                // matiDisini(__LINE__);
            }
            else {
                $var .= tplNoData("cobalah dengan mengurangi karakter dari kata kunci yang diketikan <br>#$key<hr>");
            }
        }
        else {
            $var .= tplNoData("minimal keyword 3 karakter");
        }

        echo "<style type='text/css'>
                .form-group{ margin-bottom: 0 !important;}
                .checkbox{ margin: 0 !important};
</style>";
        echo $var;
    }

    function scaner()
    {
        // $this->load->model("Mdls/MdlProdukCabang");
        // $td = new MdlProdukCabang();
        // $condites = array(
        //     "cabang_id" => my_cabang_id(),
        //     "last_updater >" => '0',
        // );
        // $this->db->where($condites);
        // $this->db->order_by('last_dtime', 'desc');
        // $this->db->limit(20);
        // $tmps = $td->lookupAll()->result();
        // showLast_query("lime");
        $tmps = array();
        $items = array();
        foreach ($tmps as $tmp) {

            // $datas = blobDecode($tmp->datas);
            // // arrPrint($datas);
            // $items[] = (array)$tmp + $datas['main'];
        }
        // arrPrintPink($items);
        // cekHijau();
        $headerFields = array(
            "kode"        => array(
                "link" => "Searching/viewProduk",
            ),
            // <a href="https://teguh.mayagrahakencana.com/Searching/viewProduk/3333/5" data-toggle="modal" data-target="#myModal">024.020 | ROTOR BLOWER RINOSAURUS | RBD-125</a>
            // "oleh_nama"   => "oleh",
            // "customer_nama" => "konsumen",
            // "otp" => "PIN",
            "produk_nama" => "diskon produk",
            "lokasi_nama" => array(
                "link" => "Searching/viewRak",
            ),
            // "last_updater"    => "tambahan diskon",
            "last_dtime"  => array(
                "format" => "tglDurasi"
            ),
        );
        $arrButton = array();
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $data = array(
            "mode"          => "scaner",
            "title"         => "scaner produk",
            "subTitle"      => "pengaturan",
            "headerFields"  => $headerFields,
            "items"         => $tmps,
            "warning"       => isset($warning) ? $warning : array(),
            "marking"       => isset($marking) ? $marking : array(),
            "markingColumn" => isset($markingColumn) ? $markingColumn : array(),
            "button"        => $arrButton,
            "do_scane"      => MODUL_PATH . get_class($this) . "/doScan",
            "isMobile"      => $isMob,
        );

        $this->load->view("qr", $data);
    }

    public function doScan()
    {
        // arrPrint($_GET);

        $str = $_GET['str'];
        $var = "";
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlProdukPerSerialNumber");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/MdlGudangDefault");
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        //fetchBalances
        //        $this->load->library("Ciqrcode");
        //        $qrc = new Ciqrcode();
        $tr = new MdlTransaksi();
        $r = new ComRekeningPembantuProdukPerSerial();
        $s = new MdlProdukPerSerialNumber();
        $c = new MdlCabang();
        $g = new MdlGudangDefault();

        // $qrKeys = $qrc->get_qr_key_patern();
        //        $qrDatas = $qrc->qrExtractor($str);
        //         arrPrintHijau($qrDatas);
        // arrPrintHijau($params);
        // foreach ($qrKeys as $ky => $qrKey) {
        // $$qrKey = $params[$ky];
        //        foreach ($qrDatas as $ky => $qrVal) {
        //            $$ky = $qrVal;
        //
        //            // cekBiru("$ky :: " . $qrVal);
        //        }
        //        $c->addFilter("jenis=''");
        $dataCabangTmp = $c->lookUpAll()->result();
        if (count($dataCabangTmp) > 0) {
            foreach ($dataCabangTmp as $dataCabangTmp_0) {
                $dataCabang[$dataCabangTmp_0->id] = $dataCabangTmp_0->nama;
            }
        }
        $tempGudang = $g->lookUpAll()->result();
        //        arrPrintWebs($tempGudang);
        $s->addFilter("produk_serial_number_2=$str");
        $tmpSerial = $s->lookUpAll()->result();
        //cekLime($this->db->last_query());
        $produk_id = $tmpSerial[0]->produk_id;

        $r->addFilter("extern_nama='$str'");
        $r->addFilter("qty_debet='1'");
        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $balance_serial = $r->fetchBalances("1010030030");
        showLast_query("hijau");
        $infoSerial = "";
        $lastMove = array();
        if (count($balance_serial) > 0) {
            $infoSerial["avail"] = array(
                "str"    => $str,
                "status" => "tersedia"
            );
            //stok tersedia
        }
        else {

            // $this->load->helper("he_mass_table");
            // $r->setFilters(array());
            // $r->addFilter("extern_nama='$str'");
            // // $movements = $r->fetchMovement("1010030030");
            // showLast_query("merah");

            /** ------------------------------------------
             * Mutasi serial -- join dengan transaksi
             * ------------------------------------------*/
            $this->db->select('*'); // atau spesifik kolom misalnya: 'tbl1.*, tbl2.nama_transaksi'
            $this->db->from('__rek_pembantu_produk_perserial__1010030030 as tbl1');
            $this->db->join('transaksi as tbl2', 'tbl1.transaksi_id = tbl2.id');
            $this->db->where('tbl1.extern_nama', $str);
            $this->db->group_by('tbl1.transaksi_id'); // hanya ambil 1 per transaksi_id
            $query = $this->db->get();

            $movements = $query->result(); // atau ->result_array() jika ingin array
            showLast_query("kuning");


            // arrPrintHijau($ss);
            // ----------------------------------------------------

            //ada dicabang lain
            //sudah terjual
            $lastPoisitionFields = array(
                "cabang_id"    => "cabang_id",
                "gudang_id"    => "gudang_id",
                "jenis"        => "jenis",
                "transaksi_id" => "transaksi_id",
                "transaksi_no" => "transaksi_no",
                "qty_debet"    => "qty_debet",
                "qty_kredit"   => "qty_kredit",
                "keterangan"   => "keterangan",
                "dtime"        => "dtime",
            );
            $r->setFilters(array());
            $r->addFilter("extern_nama='$str'");
            //            $r->addFilter("produk_id='$produk_id'");
            $this->db->order_by("id", "desc");
            $this->db->limit(1);
            $tempLastPOsision = $r->fetchLastMoves()->result();
            showLast_query("biru");
            if (count($tempLastPOsision) > 0) {
                foreach ($tempLastPOsision as $tempLastPOsision_0) {
                    foreach ($lastPoisitionFields as $key => $alias) {
                        $lastMove[$key] = $tempLastPOsision_0->$key;
                    }
                    cekMerah($tempLastPOsision_0->jenis);
                    switch ($tempLastPOsision_0->jenis) {
                        case "582spd":
                        case "5822pkd":
                        case "5822spd":
                        case "5823pkd":
                        case "5823spd":
                            $status = "dikirim";
                            break;
                        default :
                            $status = "stok ada di " . $dataCabang[$tempLastPOsision_0->cabang_id];
                            break;
                    }

                    $lastMove["status"] = $status;
                    $lastMove["cabang_nama"] = isset($dataCabang[$tempLastPOsision_0->cabang_id]) ? $dataCabang[$tempLastPOsision_0->cabang_id] : "";
                    $infoSerial["avail"] = array(
                        "str"    => $str,
                        //                        "status" => $status == "dikirim" ? "serial sudah tidak tersedia" : "stok ada di " . $dataCabang[$tempLastPOsision_0->cabang_id],
                        "status" => $status == "dikirim" ? "serial sudah tidak tersedia" : "serial ada di " . $dataCabang[$tempLastPOsision_0->cabang_id],
                    );
                }
            }
            else {
                $infoSerial["avail"] = array(
                    "str"    => "<r class='font-size-1-2''>$str</r>",
                    "status" => "serial tidak dikenal/ tidak terdaftar"
                );
            }

            //            arrPrint($tempLastPOsision);
            //            arrPrint($lastMove);
            //            cekMErah($this->db->last_query());
        }
        //cekBiru($this->db->last_query());
        //        matiHere(__LINE__." :: ".$produk_id);
        // cekPink($produk_id);
        /*---spek produk------*/
        $this->load->model("Mdls/MdlProduk2");
        $pr = new MdlProduk2();
        $speks = $pr->callSpecs($produk_id);
        // showLast_query("biru");
        // arrPrintHijau($speks);
        $produk_jenis = $speks[$produk_id]->jenis;
        // arrPrint($produk_jenis);

        /*----komposisi---*/
        $str_kom = "<hr><h3 class='text-uppercase'>:: bukan produk rakitan ::</h3>";
        if ($produk_jenis == "item_rakitan") {
            $this->load->model("Mdls/MdlProdukKomposisi");
            $pk = new MdlProdukKomposisi();
            // $pKompos = $pk->lookupByPID($produk_id)->result();
            $pKompos = $pk->callSpecs($produk_id);
            // showLast_query("hijau");
            // arrPrintPink($pKompos);


            $keyKompos = array(
                "produk_dasar_nama" => array(),
                "satuan_nama"       => array(),
                "jml"               => array(
                    "attr" => "class='text-right'",
                ),
                "nilai"             => array(
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "attr"       => "class='text-right'",
                ),
            );
            $str_kom = "<hr><h3 class='text-uppercase'>produk BOM</h3>";
            $str_kom .= "<table>";
            foreach ($pKompos[$produk_id] as $pKompo) {

                $str_kom .= "<tr>";
                foreach ($keyKompos as $keyKompo => $parKompo) {
                    $knilai = isset($pKompo->$keyKompo) ? $pKompo->$keyKompo : 0;
                    // $nilai = isset($datas[$ky]) ? $datas[$ky] : "--";

                    $label = isset($parKompo['label']) ? $parKompo['label'] : $ky;
                    $attr = isset($parKompo['attr']) ? $parKompo['attr'] : "";

                    $format_key = isset($parKompo['format_key']) ? $parKompo['format_key'] : $ky;
                    $knilai_f = isset($parKompo['format']) ? $parKompo['format']($format_key, $knilai) : $knilai;

                    $str_kom .= "<td $attr>$knilai_f</td>";
                }
                $str_kom .= "</tr>";
            }
            $str_kom .= "</table>";
        }


        /*---stok locker produk------*/
        $this->load->model("Mdls/MdlLockerStock");
        $loc = new MdlLockerStock();
        $loc->setFilters(array());
        $condites = array(
            "state"      => "active",
            "produk_id"  => $produk_id,
            "gudang_id<" => 0,
        );
        $this->db->where($condites);
        $condite_ins = array(
            "produk",
            "produk_rakitan",
        );
        $this->db->where_in("jenis", $condite_ins);
        $locker = $loc->lookupAll()->result();
        showLast_query("kuning");
        // arrPrintKuning($locker);

        $spekLocker = array();
        foreach ($locker as $lockerdata) {
            $lc_prod_id = $lockerdata->produk_id;
            $lc_cabang_id = $lockerdata->cabang_id;
            $lc_jml = $lockerdata->jumlah;

            if (!isset($spekLocker[$lc_prod_id]["jml_0"])) {
                $spekLocker[$lc_prod_id]["jml_0"] = 0;
            }
            $spekLocker[$lc_prod_id]["jml_0"] += $lc_jml;

            $spekLocker[$lc_prod_id]["jml_" . $lc_cabang_id] = $lc_jml;
        }
        //        arrPrintCyan($spekLocker);
        /*---harga---*/
        // cekMerah("$produk_id");
        $this->load->model("Mdls/MdlHargaProduk");
        $hr = new MdlHargaProduk();
        $grhs = $hr->callProdukHarga($produk_id);
        // arrPrint($grhs);
        $harga_speks = array();
        $arrCabang = array();
        //        $arrCabang = $dataCabang;
        foreach ($grhs as $hProdId => $cb_hargas) {
            foreach ($cb_hargas as $cb_id => $spek_hargas) {

                $harga_speks[$hProdId]['hpp_' . $cb_id] = isset($spek_hargas['hpp']) ? $spek_hargas['hpp']['nilai'] : 0;
                $harga_speks[$hProdId]['jual_' . $cb_id] = isset($spek_hargas['jual']) ? $spek_hargas['jual']['nilai'] : 0;
                $harga_speks[$hProdId]['jual_nppn_' . $cb_id] = isset($spek_hargas['jual_nppn']) ? $spek_hargas['jual_nppn']['nilai'] : 0;

                $arrCabang[$cb_id] = 1;
            }
        }

        /*---last GRN produk------*/
        // $this->load->model("MdlTransaksi");
        // $tr = new MdlTransaksi();
        // if ($master_id == 0) {
        //     $var . "<div class='alert alert-warning'> qrcode tidak suport detile transaksi terakhir</div>";
        // }
        // else {
        //
        //     $trs = $tr->lookupDataRegistriesByMasterID($master_id)->result();
        //     showLast_query("pink");
        //     arrPrintPink($trs);
        // }

        /*--------cabang----------*/
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $cbs = $cb->lookupAll()->result();
        // arrPrint($cbs);
        $cabangs = array();
        foreach ($cbs as $cb) {
            $cabangs[$cb->id] = $cb->nama;
        }

        /*-------mutasi serial-----------*/
        $movement_keis = array(
            "dtime"        => [
                "label"  => "tanggal",
                "format" => "formatField_he_format",
                // "format_key" => "tanggal",
            ],
            "jenis"        => [],
            // "jenis_master" => [],
            "nomer"        => [
                "label"  => "nomer",
                "format" => "formatField_he_format",
                "attr"   => "class='text-blue'"
            ],
            "cabang_id"    => [
                "label" => "Lokasi"
            ],
            "oleh_nama"    => [
                "label" => "PIC"
            ],
            "trash_4"      => [
                "label" => "status"
            ],
            "cancel_name"  => [
                "label" => "dicancel oleh"
            ],
            "cancel_dtime" => [
                "label"      => "tanggal cancel",
                "format"     => "formatField_he_format",
                "format_key" => "dtime",
            ],
        );
        $var_mutasi = "";
        if (count($movements) > 0) {
            /*mengambil configUi untuk memberikan label*/
            $this->load->config("heTransaksi_ui");
            $confUis = $this->config->item("heTransaksi_ui");
            foreach ($confUis as $conf_ui) {
                foreach ($conf_ui["steps"] as $steps) {
                    // arrPrintKuning($steps);
                    $source = $steps["source"];
                    $target = $steps["target"];
                    $label = $steps["label"];
                    $targetUI[$target] = $label;
                    $targetUI[$source] = $label;
                    // break;
                }
            }

            /*penampl mutasi serial*/
            $var_mutasi = "";
            $var_mutasi .= "<style>
                    table {
             
                        border-collapse: collapse;
                        font-family: Arial, sans-serif;
                        font-size: 13px;
                    }
                
                    th, td {
                        padding: 6px 10px;
                        border: 1px solid #ccc;
                        text-align: left;
                    }
                
                    th {
                        background-color: #f0f0f0;
                        font-weight: bold;
                        color: #333;
                    }
                
                    tr:nth-child(even) {
                        background-color: #fafafa;
                    }
                
                    tr:hover {
                        background-color: #f1f9ff;
                    }
                    a{
                        text-decoration: underline;
                        color: blue;
                    }
                    .qtips-blue {
                        color: blue !important;
                    }
                </style>";
            $var_mutasi .= "<div style='margin-top: 10px;'>";
            $var_mutasi .= "<h3 class='text-uppercase'>mutasi serial</h3>";
            $var_mutasi .= "<table>";
            $var_mutasi .= "<tr class='text-uppercase'>";
            foreach ($movement_keis as $key => $params) {
                $label = isset($params['label']) ? $params['label'] : $key;
                $var_mutasi .= "<th>$label</th>";
            }
            $var_mutasi .= "</tr>";

            // Isi tabel
            foreach ($movements as $movement) {
                // arrPrint($movement);
                // break;
                $jenis_master = $movement->jenis_master;
                $modul_path = base_url() . $confUis[$jenis_master]["modul"] . "/";
                $var_mutasi .= "<tr>";
                foreach ($movement_keis as $key => $params) {
                    $knilai = isset($movement->$key) ? $movement->$key : "-";
                    // Optional: jika butuh format khusus bisa tambah di sini
                    if ($key == "cabang_id") {
                        $knilai = $cabangs[$knilai];
                    }
                    if ($key == "jenis") {
                        $knilai = $targetUI[$knilai];
                    }
                    if ($key == "trash_4") {
                        $knilai = $knilai == 1 ? "dicancel" : "";
                    }
                    $attr = isset($params['attr']) ? $params['attr'] : "";

                    $format_key = isset($params['format_key']) ? $params['format_key'] : $key;
                    $knilai_f = isset($params['format']) ? $params['format']($format_key, $knilai, $jenis_master, $modul_path) : $knilai;

                    $var_mutasi .= "<td $attr>$knilai_f</td>";
                }
                $var_mutasi .= "</tr>";
            }

            $var_mutasi .= "</table>";
            $var_mutasi .= "</div>";

            // echo $var_mutasi;
        }

        /*----spek harga-----*/
        $data_gabungan = array();
        foreach ($speks as $prod_id => $spek) {

            $data_gabungan[$prod_id] = (array)$spek + (isset($spekLocker[$prod_id]) ? $spekLocker[$prod_id] : array()) + (isset($harga_speks[$prod_id]) ? $harga_speks[$prod_id] : array());
        }
        //         arrPrintPink($data_gabungan);

        /*-----*/
        $spek_key = array(
            "id"         => array(),
            "jenis"      => array(),
            "kode"       => array(),
            "nama"       => array(),
            "label"      => array(),
            "satuan"     => array(),
            "status"     => array(),
            "keterangan" => array(),
        );

        $member = my_memberships();
        // arrPrintKuning($member);
        $seller_key = array();
        $holding_key = array();
        if (in_array("o_seller", $member)) {

            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );

            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("o_seller_spv", $member)) {

            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );

            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("o_gudang", $member)) {

            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );

            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("o_gudang_spv", $member)) {

            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );

            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("o_gudang_out", $member)) {

            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );

            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("c_holding", $member)) {
            $holding_key = array();
            foreach ($arrCabang as $cb_id => $item) {
                $holding_key["hpp_-1"] = array(
                    "label"      => "HPP",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                );
                // $holding_key["jual_$cb_id"] = array();
                $holding_key["jual_nppn_$cb_id"] = array(
                    // "label" => "harga jual ".$cabangs[$cb_id],
                    "label"      => "harga jual ",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                );
                //                $holding_key["jml_0"] = array(
                //                    "label" => "Jumlah seluruh stok",
                //                    "format" => "formatField_he_format",
                //                    "format_key" => "harga",
                //                );
                //                $holding_key["jml_$cb_id"] = array(
                //                    "label" => "jml stok @" . $cabangs[$cb_id],
                //                    "format" => "formatField_he_format",
                //                    "format_key" => "harga",
                //                );
            }
            foreach ($cabangs as $cb_id => $item) {
                $holding_key["jml_0"] = array(
                    "label"      => "Jumlah seluruh stok",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
                $holding_key["jml_$cb_id"] = array(
                    "label"      => "Jumlah stok @" . $cabangs[$cb_id],
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("c_gudang", $member)) {
            $holding_key = array();
            //            foreach ($arrCabang as $cb_id => $item) {
            //                $holding_key["hpp_-1"] = array(
            //                    "label" => "HPP",
            //                    "format" => "formatField_he_format",
            //                    "format_key" => "harga",
            //                );
            //                // $holding_key["jual_$cb_id"] = array();
            //                $holding_key["jual_nppn_$cb_id"] = array(
            //                    // "label" => "harga jual ".$cabangs[$cb_id],
            //                    "label" => "harga jual ",
            //                    "format" => "formatField_he_format",
            //                    "format_key" => "harga",
            //                );
            ////                $holding_key["jml_0"] = array(
            ////                    "label" => "Jumlah seluruh stok",
            ////                    "format" => "formatField_he_format",
            ////                    "format_key" => "harga",
            ////                );
            ////                $holding_key["jml_$cb_id"] = array(
            ////                    "label" => "jml stok @" . $cabangs[$cb_id],
            ////                    "format" => "formatField_he_format",
            ////                    "format_key" => "harga",
            ////                );
            //            }
            foreach ($cabangs as $cb_id => $item) {
                $holding_key["jml_0"] = array(
                    "label"      => "Jumlah seluruh stok",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
                $holding_key["jml_$cb_id"] = array(
                    "label"      => "Jumlah stok @" . $cabangs[$cb_id],
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("c_gudang_spv", $member)) {
            $holding_key = array();
            //            foreach ($arrCabang as $cb_id => $item) {
            //                $holding_key["hpp_-1"] = array(
            //                    "label" => "HPP",
            //                    "format" => "formatField_he_format",
            //                    "format_key" => "harga",
            //                );
            //                // $holding_key["jual_$cb_id"] = array();
            //                $holding_key["jual_nppn_$cb_id"] = array(
            //                    // "label" => "harga jual ".$cabangs[$cb_id],
            //                    "label" => "harga jual ",
            //                    "format" => "formatField_he_format",
            //                    "format_key" => "harga",
            //                );
            ////                $holding_key["jml_0"] = array(
            ////                    "label" => "Jumlah seluruh stok",
            ////                    "format" => "formatField_he_format",
            ////                    "format_key" => "harga",
            ////                );
            ////                $holding_key["jml_$cb_id"] = array(
            ////                    "label" => "jml stok @" . $cabangs[$cb_id],
            ////                    "format" => "formatField_he_format",
            ////                    "format_key" => "harga",
            ////                );
            //            }
            foreach ($cabangs as $cb_id => $item) {
                $holding_key["jml_0"] = array(
                    "label"      => "Jumlah seluruh stok",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
                $holding_key["jml_$cb_id"] = array(
                    "label"      => "Jumlah stok @" . $cabangs[$cb_id],
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
            }
        }
        //        arrprintPink($holding_key);

        $all_key = $spek_key + $seller_key + $holding_key;
        // arrPrint($all_key);
        $data_gabungan_2 = isset($data_gabungan[$produk_id]) ? $data_gabungan[$produk_id] : array();
        $datas = $data_gabungan_2 + $lastMove;
        $var_show = "";
        //        arrPrintHitam($datas);
        //        $infoSerial
        $var_show .= "<div class='alert alert-info text-center'>";
        // $var_show .= "<div>";
        foreach ($infoSerial as $x => $serial) {
            $var_show .= "<span><b>SN</b>:" . $serial["str"] . "</span>";
            $var_show .= " <div class='text-uppercase text-center font-size-2'>" . $serial["status"] . "</div>";
        }
        // $var_show .= "</div>";
        $var_show .= "</div>";

        $var_show .= "<h3 class='text-uppercase'>produk spek</h3>";
        $var_show .= "<table>";
        foreach ($all_key as $ky => $ky_param) {
            $label = isset($ky_param['label']) ? $ky_param['label'] : $ky;

            $nilai = isset($datas[$ky]) ? $datas[$ky] : "--";
            $format_key = isset($ky_param['format_key']) ? $ky_param['format_key'] : $ky;
            $nilai_f = isset($ky_param['format']) ? $ky_param['format']($format_key, $nilai) : $nilai;
            $style = isset($ky_param['style']) ? $ky_param['style'] : "";

            $var_show .= "<tr style='$style'>";
            $var_show .= "<td>$label </td>";
            $var_show .= "<td> : $nilai_f</td>";
            $var_show .= "</tr>";
        }
        $var_show .= "</table>";

        $var_show .= $var_mutasi;


        echo $var_show;
        if (in_array("p_produksi_spv", $member)) {
            echo $str_kom;
        }
        echo "<script>$('span').removeClass('pull-right');
                $('span[name=\"qtips\"]').addClass('qtips-blue');
            </script>";

        // writeLog("scaner QRcode", "$produk_id", "qrcode", "scaner");
        // echo $var;
    }

    public function doScan_()
    {
        // arrPrint($_GET);

        $str = $_GET['str'];
        $var = "";
        $this->load->library("Ciqrcode");
        $qrc = new Ciqrcode();

        // $qrKeys = $qrc->get_qr_key_patern();
        $qrDatas = $qrc->qrExtractor($str);
        // arrPrintHijau($qrDatas);
        // arrPrintHijau($params);
        // foreach ($qrKeys as $ky => $qrKey) {
        // $$qrKey = $params[$ky];
        foreach ($qrDatas as $ky => $qrVal) {
            $$ky = $qrVal;

            // cekBiru("$ky :: " . $qrVal);
        }

        // cekPink($produk_id);
        /*---spek produk------*/
        $this->load->model("Mdls/MdlProduk2");
        $pr = new MdlProduk2();
        $speks = $pr->callSpecs($produk_id);
        // showLast_query("biru");
        // arrPrintHijau($speks);
        $produk_jenis = $speks[$produk_id]->jenis;
        // arrPrint($produk_jenis);

        /*----komposisi---*/
        $str_kom = "<hr><h3 class='text-uppercase'>:: bukan produk rakitan ::</h3>";
        if ($produk_jenis == "item_rakitan") {
            $this->load->model("Mdls/MdlProdukKomposisi");
            $pk = new MdlProdukKomposisi();
            // $pKompos = $pk->lookupByPID($produk_id)->result();
            $pKompos = $pk->callSpecs($produk_id);
            // showLast_query("hijau");
            // arrPrintPink($pKompos);


            $keyKompos = array(
                "produk_dasar_nama" => array(),
                "satuan_nama"       => array(),
                "jml"               => array(
                    "attr" => "class='text-right'",
                ),
                "nilai"             => array(
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "attr"       => "class='text-right'",
                ),
            );
            $str_kom = "<hr><h3 class='text-uppercase'>produk BOM</h3>";
            $str_kom .= "<table>";
            foreach ($pKompos[$produk_id] as $pKompo) {

                $str_kom .= "<tr>";
                foreach ($keyKompos as $keyKompo => $parKompo) {
                    $knilai = isset($pKompo->$keyKompo) ? $pKompo->$keyKompo : 0;
                    // $nilai = isset($datas[$ky]) ? $datas[$ky] : "--";

                    $label = isset($parKompo['label']) ? $parKompo['label'] : $ky;
                    $attr = isset($parKompo['attr']) ? $parKompo['attr'] : "";

                    $format_key = isset($parKompo['format_key']) ? $parKompo['format_key'] : $ky;
                    $knilai_f = isset($parKompo['format']) ? $parKompo['format']($format_key, $knilai) : $knilai;

                    $str_kom .= "<td $attr>$knilai_f</td>";
                }
                $str_kom .= "</tr>";
            }
            $str_kom .= "</table>";
        }


        /*---stok locker produk------*/
        $this->load->model("Mdls/MdlLockerStock");
        $loc = new MdlLockerStock();
        $loc->setFilters(array());
        $condites = array(
            "state"      => "active",
            "produk_id"  => $produk_id,
            "gudang_id<" => 0,
        );
        $this->db->where($condites);
        $condite_ins = array(
            "produk",
            "produk_rakitan",
        );
        $this->db->where_in("jenis", $condite_ins);
        $locker = $loc->lookupAll()->result();
        // showLast_query("kuning");
        // arrPrintKuning($locker);

        $spekLocker = array();
        foreach ($locker as $lockerdata) {
            $lc_prod_id = $lockerdata->produk_id;
            $lc_cabang_id = $lockerdata->cabang_id;
            $lc_jml = $lockerdata->jumlah;

            if (!isset($spekLocker[$lc_prod_id]["jml_0"])) {
                $spekLocker[$lc_prod_id]["jml_0"] = 0;
            }
            $spekLocker[$lc_prod_id]["jml_0"] += $lc_jml;

            $spekLocker[$lc_prod_id]["jml_" . $lc_cabang_id] = $lc_jml;
        }
        // arrPrint($spekLocker);
        /*---harga---*/
        // cekMerah("$produk_id");
        $this->load->model("Mdls/MdlHargaProduk");
        $hr = new MdlHargaProduk();
        $grhs = $hr->callProdukHarga($produk_id);
        // arrPrint($grhs);
        $harga_speks = array();
        $arrCabang = array();
        foreach ($grhs as $hProdId => $cb_hargas) {
            foreach ($cb_hargas as $cb_id => $spek_hargas) {

                $harga_speks[$hProdId]['hpp_' . $cb_id] = isset($spek_hargas['hpp']) ? $spek_hargas['hpp']['nilai'] : 0;
                $harga_speks[$hProdId]['jual_' . $cb_id] = isset($spek_hargas['jual']) ? $spek_hargas['jual']['nilai'] : 0;
                $harga_speks[$hProdId]['jual_nppn_' . $cb_id] = isset($spek_hargas['jual_nppn']) ? $spek_hargas['jual_nppn']['nilai'] : 0;

                $arrCabang[$cb_id] = 1;
            }
        }

        /*---last GRN produk------*/
        // $this->load->model("MdlTransaksi");
        // $tr = new MdlTransaksi();
        // if ($master_id == 0) {
        //     $var . "<div class='alert alert-warning'> qrcode tidak suport detile transaksi terakhir</div>";
        // }
        // else {
        //
        //     $trs = $tr->lookupDataRegistriesByMasterID($master_id)->result();
        //     showLast_query("pink");
        //     arrPrintPink($trs);
        // }

        /*--------cabang----------*/
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $cbs = $cb->lookupAll()->result();
        // arrPrint($cbs);
        $cabangs = array();
        foreach ($cbs as $cb) {
            $cabangs[$cb->id] = $cb->nama;
        }


        /*----spek harga-----*/
        $data_gabungan = array();
        foreach ($speks as $prod_id => $spek) {

            $data_gabungan[$prod_id] = (array)$spek + (isset($spekLocker[$prod_id]) ? $spekLocker[$prod_id] : array()) + (isset($harga_speks[$prod_id]) ? $harga_speks[$prod_id] : array());
        }
        // arrPrintPink($data_gabungan);

        /*-----*/
        $spek_key = array(
            "id"     => array(),
            "jenis"  => array(),
            "kode"   => array(),
            "nama"   => array(),
            "label"  => array(),
            "satuan" => array(),
        );

        $member = my_memberships();
        // arrPrintKuning($member);
        $seller_key = array();
        $holding_key = array();
        if (in_array("o_seller", $member)) {

            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                ),
                // "jual_nppn_$cabang_id" => array(),
            );

            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                );
            }
        }
        elseif (in_array("c_holding", $member)) {
            // arrPrintPink($arrCabang);
            $holding_key = array();
            foreach ($arrCabang as $cb_id => $item) {
                $holding_key["hpp_-1"] = array(
                    "label"      => "HPP",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                );
                $holding_key["jml_0"] = array
                (
                    "label"      => "Jumlah seluruh stok",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                );
                $holding_key["jml_$cb_id"] = array(
                    "label"      => "jml stok @" . $cabangs[$cb_id],
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                );
                // $holding_key["jual_$cb_id"] = array();
                $holding_key["jual_nppn_$cb_id"] = array(
                    // "label" => "harga jual ".$cabangs[$cb_id],
                    "label"      => "harga jual ",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                );
            }
        }


        $all_key = $spek_key + $seller_key + $holding_key;
        // arrPrint($all_key);

        $datas = $data_gabungan[$produk_id];
        $var_show = "";

        $var_show .= "<h3 class='text-uppercase'>produk spek</h3>";
        $var_show .= "<table>";
        foreach ($all_key as $ky => $ky_param) {
            $label = isset($ky_param['label']) ? $ky_param['label'] : $ky;

            $nilai = isset($datas[$ky]) ? $datas[$ky] : "--";
            $format_key = isset($ky_param['format_key']) ? $ky_param['format_key'] : $ky;
            $nilai_f = isset($ky_param['format']) ? $ky_param['format']($format_key, $nilai) : $nilai;

            $var_show .= "<tr>";
            $var_show .= "<td>$label </td>";
            $var_show .= "<td> : $nilai_f</td>";
            $var_show .= "</tr>";
        }
        $var_show .= "</table>";

        echo $var_show;
        if (in_array("p_produksi_spv", $member)) {
            echo $str_kom;
        }
        echo "<script>$('span').removeClass('pull-right');</script>";

        writeLog("scaner QRcode", "$produk_id", "qrcode", "scaner");
        // echo $var;
    }

    public function doPrintModulPembelian()
    {
        // $this->load->libraries("phpqrcode/phpqrcode");
        // $this->load->libraries("phpqrcode/qrlib");
        //        arrPrint($_GET);

        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();

        if (isset($_POST['id_produk'])) {
            $idData = $_POST['id_produk'];
            $jmlPrint = $_POST['jml_print'];
            $hargas = $_POST['harga'];

            //             arrPrint($_POST);
            //matiHEre();
            $listedxId = "(" . implode(",", $idData) . ")";
            $className = "MdlProduk2";
            $this->load->model("Mdls/MdlProduk2");
            $o = new MdlProduk2();
            //            $o->addFilter(array("id in"=>"$listedxId"));
            $cindition = "id in $listedxId";
            $tmpX = $o->lookupByCondition($cindition)->result();
            $tempValue = array();
            $tempIdx = array();
            if (sizeof($tmpX) > 0) {
                foreach ($tmpX as $tempData) {
                    $id = $tempData->id;
                    $name = $tempData->nama;
                    $barcode = $tempData->barcode;
                    $code = $tempData->kode;
                    $sku = $tempData->sku;

                    $tempValue[$id] = $barcode;
                    $tempIdx[$id] = $name;
                    $tempCode[$id] = $code;
                    //                    $tempSku[$id] = $sku;
                }
            }
            $indexID = array();
            //            $data_show = "";
            foreach ($idData as $xId) {
                $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                //                $data_show .= "<div>";
                if ($maxPrint > 0) {
                    $barcode = $tempValue[$xId];
                    $name_data = $tempIdx[$xId];
                    for ($i = 0; $i < $maxPrint; $i++) {
                        $indexID[] = $xId;
                        //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                        //                    </div>
                        //                    </div>";
                    }

                    //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                }
                //                $data_show .= "<div>";
            }

            $jml_kolom = "4";
            $isi_array = sizeof($indexID);
            $max_baris_perkolom = floor($isi_array / $jml_kolom);
            $sisa_baris_ = $isi_array % $jml_kolom;
            // $ekstra = $jml_kolom / $sisa_baris_;
            $row = 0;
            $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
            if ($sisa_baris_ == 1) {
                $addKolom_1 = 1;
                $addKolom_2 = 1;
                $addKolom_3 = 1;
            }
            elseif ($sisa_baris_ == 2) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 2;
            }
            elseif ($sisa_baris_ == 3) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 3;
            }

            foreach ($indexID as $key => $id) {
                $row++;
                if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                    $arrRow[0][] = array(
                        "id" => $id,

                    );
                }
                elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                    $arrRow[1][] = array(
                        "id" => $id,
                    );
                }
                elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                    // cekPink("$row >= $rowke_1");
                    $arrRow[3][] = array(
                        "id" => $id,

                    );
                }
                else {
                    $arrRow[4][] = array(
                        "id" => $id,

                    );
                }
            }


            $listed = "<div class='row'>";
            $listed .= "<style>.thumbnail text{font-size: 2.5em !important;}</style>";
            $listed .= "<div class='container'>";
            foreach ($arrRow as $k => $tmpX) {
                $listed .= "<div class='col-md-3 col-lg-2 col-xs-3' style='padding: 2px; color: #0d1720;'>";
                $listed .= "<div class='text-center'>";
                foreach ($tmpX as $y => $x) {
                    //                    arrPrint($x);
                    $xID = $x['id'];
                    // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                    $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                    $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                    $name_data = $tempIdx[$xID];
                    //                    $name_sku = $tempSku[$xID];
                    $harga_f = $hargas[$xID];

                    $qr_dta = array(
                        // "produk_id" => "https://demo.",
                        "produk_id"    => $xID,
                        "produk_kode"  => $code,
                        // "pn" => $name_data,
                        "jenis_tr"     => "0",
                        "master_id"    => "0",
                        "transaksi_id" => "0",
                        // "lain-lain" => "1563265",
                        // "ur" => "https://san.mayagrahakencana.com",
                    );
                    // $barcode = implode("|",$qr_dta);
                    // $barcode = json_encode($qr_dta);

                    // $qrcode = $this->generate_qrcode($barcode);
                    $qrcode = $qr->get_qrcode($qr_dta);
                    $qrfile = base_url() . $qrcode['file'];

                    //                    cekHitam($xID);
                    $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                    // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                    $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";
                    //                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$code</span>";

                    // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none;'></svg>";
                    //                    $listed .= "uuuu";
                    // $listed .= "<span>$code</span>";
                    $listed .= "</div>";
                    $listed .= "</div>";


                    // arrPrintKuning($qrcode);
                    // cekKuning( base_url() . $qrcode['file']);
                    // $listed .= "<img src='" . base_url() . $qrcode['file'] . "'>";
                    $listed .= "<img src='$qrfile' title='$xID'>";
                    //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                    // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                    $listed .= "</div>";
                }
                $listed .= "</div>";
                $listed .= "</div>";
            }
            $listed .= "</div>";
            $listed .= "</div>";

            $data = array(
                "mode" => "viewPrint",
                //                "tmp" => $data_show
                "tmp"  => $listed
            );
            $this->load->view('qr', $data);
        }
        else {
            if (isset($_GET['FromTransaksi'])) {
                /*
                 * masih khusus untuk produksi yang lain nanti cutom dulu ya 09-05-2023 widi
                 */


                // arrPrint($_GET);
                // arrPrint($_GET);
                $prefixTransaksi = "manufactur";
                $custom_produk_print = array();
                if (isset($_SESSION['barcode_print']['items'])) {
                    $custom_produk_print = $_SESSION['barcode_print']['items'];
                }
                // arrPrint($custom_produk_print);

                $trid = $_GET['FromTransaksi'];
                $jenisTr = isset($_GET['jn']) ? $_GET['jn'] : 0;
                $masterId = isset($_GET['mid']) ? $_GET['mid'] : 0;
                $this->load->model("Mdls/MdlProdukPerSerialNumber");
                $this->load->model("Mdls/MdlProduk2");
                $tr = new MdlProdukPerSerialNumber();

                $tr->setFilters(array());
                $tr->addFilter("transaksi_id='$trid'");
                $tr->addFilter("trash='0'");
                $items = $tr->lookUpAll()->result();
                // showLast_query("biru");
                // cekHere(count($items));
                //                 arrPrint($items);
                //                matiHere(__LINE__);
                // matiHere($this->db->last_query());
                $ids = array();
                $jmlPrint = array();
                $proidTransaksi = array();
                //                $jmlSerialAll = array();
                if (sizeof($items) > 0) {
                    foreach ($items as $iID => $iData) {
                        if (!isset($jmlSerialAll[$iData->produk_id][$iData->produk_sku_part_nama])) {
                            $jmlSerialAll[$iData->produk_id][$iData->produk_sku_part_nama] = 0;
                        }
                        $jmlSerialAll[$iData->produk_id][$iData->produk_sku_part_nama] += 1;
                        $jmlPrint[$iData->id] = isset($custom_produk_print[$iData->id]['jml_print']) ? $custom_produk_print[$iData->id]['jml_print'] : 1; // belokan ambil jml print dr session
                        $proidTransaksi[$iData->id] = $iData->transaksi_id;
                        $proidlabels[$iData->id] = $iData->produk_sku_label;
                        $ids[] = $iData->id;
                        // arrPrint($iData);
                    }
                }
                //                arrPrint($jmlSerialAll);

                /* ---------------------------------------------
             * harga produk yg dipilih
             * ---------------------------------------------*/
                // arrPrintWebs($items);
                // arrPrint($jmlPrint);
                // $listedxId = "(" . implode(",", $ids) . ")";
                // $className = "MdlProduk2";
                // $this->load->model("Mdls/MdlProduk2");
                // $o = new MdlProduk2();
                // //            $o->addFilter(array("id in"=>"$listedxId"));
                // $cindition = "id in $listedxId";
                $tmpX = $items;
                $mode_dipakai = 1;
                // -----------------------------------------------------------------------------------
                if ($mode_dipakai == 1) {   //                 arrPrint($tmpX);
                    //                 matiHEre();
                    $tempValue = array();
                    $tempIdx = array();
                    $tempCode = array();
                    $prevData = array();
                    if (sizeof($tmpX) > 0) {
                        foreach ($tmpX as $tempData) {
                            $id = $tempData->id;
                            //                        $name = $tempData->extern_nama;
                            $name = $tempData->produk_nama;
                            // $barcode = $tempData->barcode;
                            // $code = $tempData->kode;
                            //                        $serial = $tempData->serial;
                            $serial = $tempData->produk_serial_number_2;
                            $sku = $tempData->produk_sku;

                            // $tempValue[$id] = $barcode;
                            $tempIdx[$id] = $name;
                            //                        $prevData[$id] = blobEncode(array("serial"=>$tempData->serial));
                            // arrprint(blobDecode($tempData->blobData));
                            $tempCode[$id] = array(
                                "serial"    => $serial,
                                "sku"       => $sku,
                                //                            "produk_id" => $tempData->extern_id,
                                "produk_id" => $tempData->produk_id,
                                "id"        => $tempData->id,
                            );
                        }
                    }
                    //                arrprint($prevData);
                    //                 matiHere();
                    $indexID = array();
                    //            $data_show = "";
                    foreach ($ids as $xId) {
                        $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                        //                $data_show .= "<div>";
                        if ($maxPrint > 0) {
                            //                        $barcode = $tempValue[$xId];
                            $name_data = $tempIdx[$xId];
                            for ($i = 0; $i < $maxPrint; $i++) {
                                $indexID[] = $xId;
                                //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                                //                    </div>
                                //                    </div>";
                            }

                            //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                        }
                        //                $data_show .= "<div>";
                    }

                    $jml_kolom = 1;
                    $isi_array = sizeof($indexID);
                    $max_baris_perkolom = floor($isi_array / $jml_kolom);
                    $sisa_baris_ = $isi_array % $jml_kolom;
                    $row = 0;
                    $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
                    if ($sisa_baris_ == 1) {
                        $addKolom_1 = 1;
                        $addKolom_2 = 1;
                        $addKolom_3 = 1;
                    }
                    elseif ($sisa_baris_ == 2) {
                        $addKolom_1 = 1;
                        $addKolom_2 = 2;
                        $addKolom_3 = 2;
                    }
                    elseif ($sisa_baris_ == 3) {
                        $addKolom_1 = 1;
                        $addKolom_2 = 2;
                        $addKolom_3 = 3;
                    }

                    foreach ($indexID as $key => $id) {
                        $row++;
                        if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                            $arrRow[0][] = array(
                                "id" => $id,

                            );
                        }
                        elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                            $arrRow[1][] = array(
                                "id" => $id,
                            );
                        }
                        elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                            // cekPink("$row >= $rowke_1");
                            $arrRow[3][] = array(
                                "id" => $id,

                            );
                        }
                        else {
                            $arrRow[4][] = array(
                                "id" => $id,

                            );
                        }
                    }
                    $class_col = "col-md-3 col-lg-2 col-xs-3";
                    if ($jml_kolom == 1) {
                        $class_col = "col-md-12 col-lg-12 col-xs-12";
                    }
                    // cekHitam(count($indexID));
                    // arrPrint($arrRow);
                    // cekHitam(count($arrRow));
                    //                matiHere();
                    $listed = "<div class='row'>";
                    $listed .= "<div class='container'>";
                    foreach ($arrRow as $k => $tmpX) {
                        // cekHere(count($tmpX));
                        $listed .= "<div class='$class_col' style='padding: 2px; color: #0d1720;'>";
                        $listed .= "<div class='text-center'>";
                        $nom = 0;
                        foreach ($tmpX as $y => $x) {
                            $nom++;
                            // arrPrintWebs($x);
                            $xID = $x['id'];
                            // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                            $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                            //                        $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                            //                        $code = $tempCode[$xID] ? $tempCode[$xID] : $x["serial"];
                            $serialcode = $tempCode[$xID]["serial"] ? $tempCode[$xID]["serial"] : "--";
                            $podukID = $tempCode[$xID]["produk_id"] ? $tempCode[$xID]["produk_id"] : "--";
                            $sku = isset($tempCode[$xID]["sku"]) ? $tempCode[$xID]["sku"] : "--";
                            $name_data = $tempIdx[$xID];
                            $skuLabel = $proidlabels[$xID];
                            // $harga_f = $hargas[$xID];
                            // $harga_f = formatField_he_format('curency_print', $harga_produks[$xID]);
                            // $name =
                            $qr_dta = array(
                                // "produk_id" => "https://demo.",
                                //                            "serial" => $serialcode,
                                //                            "produk_id" => $podukID,
                                //                            "produk_kode" => $code,
                                //                            // "pn" => $name_data,
                                //                            "jenis_tr" => $jenisTr,
                                //                            "master_id" => $masterId,
                                //                            "transaksi_id" => $proidTransaksi[$xID],
                                //                            "data" => json_encode($tempCode[$xID]),
                                // "data"=>"sunnnnn",
                                // "lain-lain" => "1563265",
                                // "ur" => "https://san.mayagrahakencana.com",
                            );
                            // $barcode = implode("|",$qr_dta);
                            // $barcode = json_encode($qr_dta);

                            // $qrcode = $this->generate_qrcode($barcode);
                            // arrprint($qr_dta);
                            //                        $qrcode = $qr->get_qrcode_produksi($qr_dta);
                            $qrcode = $qr->get_qrcode_pembelian($serialcode);
                            //                        $qrcode = $qr->get_qrcode($qr_dta);
                            $qrfile = base_url() . $qrcode['file'];
                            //
                            //                    cekHitam($xID);
                            $dipakai = 2;
                            if ($dipakai == 1) {
                                $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                                // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                                $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                                $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";
                                $listed .= "<span style='color: #0d6aad;' class='text-bold'> [ $sku ] </span>";
                                // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                                //                    $listed .= "uuuu";
                                // $listed .= "<span>$code</span>";
                                $listed .= "</div>";
                                $listed .= "</div>";


                                // if (validate_EAN13Barcode($barcode)) {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                                // }
                                // else if ($barcode == 'BELUM PUNYA') {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                                // }
                                // else {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                                // }
                                //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                                $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail'>";
                                // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                                $listed .= "</div>";
                                $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$serialcode</span></div>";
                            }
                            else {
                                $listed .= "<div class='bottom-borders' style='mmin-height: 245px;'>";
                                // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                                $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                                // $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data [ $sku ]</span>";
                                // $listed .= "<span style='color: #0d6aad;' class='text-bold'>  </span>";
                                // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                                //                    $listed .= "uuuu";
                                // $listed .= "<span>$code</span>";
                                // $listed .= "</div>";
                                $listed .= "</div>";

                                if (strtolower($skuLabel) == "in") {
                                    $bg_ot = "";
                                    $bg_ot = "bg-hitam";
                                    $listed .= "<div style='white-space: nowrap;font-size: 0.9em;' class='uploaded '>$name_data / $sku</div>";
                                    $listed .= "<div style='' class='uploaded '>$serialcode</div>";
                                    $border_xx = "border: #4e5158 dashed 2px;";
                                }
                                elseif (strtolower($skuLabel) == "ot") {
                                    $border_xx = "";
                                }
                                else {
                                    $border_xx = "";
                                }

                                // if (validate_EAN13Barcode($barcode)) {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                                // }
                                // else if ($barcode == 'BELUM PUNYA') {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                                // }
                                // else {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                                // }
                                //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                                $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail' style='$border_xx margin: 1px 0;'>";
                                // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                                $bg_ot = "bg-hitam";
                                if (strtolower($skuLabel) == "ot") {
                                    $bg_ot = "bg-hitam";
                                    $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                                    $listed .= "<div style='white-space: nowrap;font-size: 0.9em;' class='uploaded $bg_ot'>$name_data / $sku</div>";
                                }
                                elseif (strtolower($skuLabel) == "in") {
                                    $bg_ot = "";
                                }
                                else {

                                    $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                                    $listed .= "<div style='white-space: nowrap;font-size: 0.9em;' class='uploaded $bg_ot'>$name_data / $sku</div>";
                                }

                                // $listed .= "<div>#$nom</div>";
                                $listed .= "</div>";
                            }
                        }
                        $listed .= "</div>";
                        $listed .= "</div>";
                    }
                    $listed .= "</div>";
                    $listed .= "</div>";
                }
                else {

                    // $listed = $this->LayoutPrint($items);
                }
                // -----------------------------------------------------------------------------------
                // matiHEre(__LINE__);
                $data = array(
                    "mode" => "viewPrint",
                    //                "tmp" => $data_show
                    "tmp"  => $listed
                );
                $this->load->view('barcode', $data);
                //                 arrPRint($ids);
                // matiHere("masukk");
            }
            else {
                $arrAlert = array(
                    "type"              => "warning",
                    "title"             => "No data selected",
                    "html"              => "No data selected to print!",
                    "timer"             => "1500",
                    "showConfirmButton" => false,
                    "allowOutsideClick" => false,
                );
                echo swalAlert($arrAlert);
                die();
                echo "<script>topReload(1500)</script>";
                echo topReload();
                echo "</script>";
            }

        }
    }

    public function doPrintVoucher()
    {
        // $this->load->libraries("phpqrcode/phpqrcode");
        // $this->load->libraries("phpqrcode/qrlib");
        //                arrPrint($_GET);

        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();

        if (isset($_POST['id_produk'])) {
            $idData = $_POST['id_produk'];
            $jmlPrint = $_POST['jml_print'];
            $hargas = $_POST['harga'];

            //             arrPrint($_POST);
            //matiHEre();
            $listedxId = "(" . implode(",", $idData) . ")";
            $className = "MdlProduk2";
            $this->load->model("Mdls/MdlProduk2");
            $o = new MdlProduk2();
            //            $o->addFilter(array("id in"=>"$listedxId"));
            $cindition = "id in $listedxId";
            $tmpX = $o->lookupByCondition($cindition)->result();
            $tempValue = array();
            $tempIdx = array();
            if (sizeof($tmpX) > 0) {
                foreach ($tmpX as $tempData) {
                    $id = $tempData->id;
                    $name = $tempData->nama;
                    $barcode = $tempData->barcode;
                    $code = $tempData->kode;
                    $sku = $tempData->sku;

                    $tempValue[$id] = $barcode;
                    $tempIdx[$id] = $name;
                    $tempCode[$id] = $code;
                    //                    $tempSku[$id] = $sku;
                }
            }
            $indexID = array();
            //            $data_show = "";
            foreach ($idData as $xId) {
                $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                //                $data_show .= "<div>";
                if ($maxPrint > 0) {
                    $barcode = $tempValue[$xId];
                    $name_data = $tempIdx[$xId];
                    for ($i = 0; $i < $maxPrint; $i++) {
                        $indexID[] = $xId;
                        //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                        //                    </div>
                        //                    </div>";
                    }

                    //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                }
                //                $data_show .= "<div>";
            }

            $jml_kolom = "4";
            $isi_array = sizeof($indexID);
            $max_baris_perkolom = floor($isi_array / $jml_kolom);
            $sisa_baris_ = $isi_array % $jml_kolom;
            // $ekstra = $jml_kolom / $sisa_baris_;
            $row = 0;
            $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
            if ($sisa_baris_ == 1) {
                $addKolom_1 = 1;
                $addKolom_2 = 1;
                $addKolom_3 = 1;
            }
            elseif ($sisa_baris_ == 2) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 2;
            }
            elseif ($sisa_baris_ == 3) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 3;
            }

            foreach ($indexID as $key => $id) {
                $row++;
                if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                    $arrRow[0][] = array(
                        "id" => $id,

                    );
                }
                elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                    $arrRow[1][] = array(
                        "id" => $id,
                    );
                }
                elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                    // cekPink("$row >= $rowke_1");
                    $arrRow[3][] = array(
                        "id" => $id,

                    );
                }
                else {
                    $arrRow[4][] = array(
                        "id" => $id,

                    );
                }
            }


            $listed = "<div class='row'>";
            $listed .= "<style>.thumbnail text{font-size: 2.5em !important;}</style>";
            $listed .= "<div class='container'>";
            foreach ($arrRow as $k => $tmpX) {
                $listed .= "<div class='col-md-3 col-lg-2 col-xs-3' style='padding: 2px; color: #0d1720;'>";
                $listed .= "<div class='text-center'>";
                foreach ($tmpX as $y => $x) {
                    //                    arrPrint($x);
                    $xID = $x['id'];
                    // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                    $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                    $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                    $name_data = $tempIdx[$xID];
                    //                    $name_sku = $tempSku[$xID];
                    $harga_f = $hargas[$xID];

                    $qr_dta = array(
                        // "produk_id" => "https://demo.",
                        "produk_id"    => $xID,
                        "produk_kode"  => $code,
                        // "pn" => $name_data,
                        "jenis_tr"     => "0",
                        "master_id"    => "0",
                        "transaksi_id" => "0",
                        // "lain-lain" => "1563265",
                        // "ur" => "https://san.mayagrahakencana.com",
                    );
                    // $barcode = implode("|",$qr_dta);
                    // $barcode = json_encode($qr_dta);

                    // $qrcode = $this->generate_qrcode($barcode);
                    $qrcode = $qr->get_qrcode($qr_dta);
                    $qrfile = base_url() . $qrcode['file'];

                    //                    cekHitam($xID);
                    $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                    // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                    $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";
                    //                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$code</span>";

                    // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none;'></svg>";
                    //                    $listed .= "uuuu";
                    // $listed .= "<span>$code</span>";
                    $listed .= "</div>";
                    $listed .= "</div>";


                    // arrPrintKuning($qrcode);
                    // cekKuning( base_url() . $qrcode['file']);
                    // $listed .= "<img src='" . base_url() . $qrcode['file'] . "'>";
                    $listed .= "<img src='$qrfile' title='$xID'>";
                    //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                    // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                    $listed .= "</div>";
                }
                $listed .= "</div>";
                $listed .= "</div>";
            }
            $listed .= "</div>";
            $listed .= "</div>";

            $data = array(
                "mode" => "viewPrint",
                //                "tmp" => $data_show
                "tmp"  => $listed
            );
            $this->load->view('qr', $data);
        }
        else {
            if (isset($_GET['FromTransaksi'])) {
                $prefixTransaksi = "manufactur";
                $custom_produk_print = array();
                if (isset($_SESSION['barcode_print']['items'])) {
                    $custom_produk_print = $_SESSION['barcode_print']['items'];
                }
                // arrPrint($custom_produk_print);

                $trid = $_GET['FromTransaksi'];
                $jenisTr = isset($_GET['jn']) ? $_GET['jn'] : 0;
                $masterId = isset($_GET['mid']) ? $_GET['mid'] : 0;
                //                $this->load->model("Mdls/MdlProdukPerSerialNumber");
                $this->load->model("MdlTransaksi");
                $tr = new MdlTransaksi();

                $tr->setFilters(array());
                $tr->addFilter("transaksi_id='$trid'");
                $items = $tr->lookupDataRegistries()->result();
                $mainData = blobDecode($items[0]->main);
                //                arrPrint($mainData);
                //                matiHEre(__LINE__);
                /* ---------------------------------------------
             * harga produk yg dipilih
             * ---------------------------------------------*/

                $tmpX = $items;
                $mode_dipakai = 1;
                // -----------------------------------------------------------------------------------
                if ($mode_dipakai == 1) {   //                 arrPrint($tmpX);
                    $serialcode = $mainData["paramVoucher"];
                    $qrcode = $qr->get_qrcode_pembelian($serialcode);
                    $qrfile = base_url() . $qrcode['file'];

                    $dipakai = 1;
                    if ($dipakai == 1) {
                        $listed = "";
                        $listed .= "<style type='text/css'>
                                    .voucher-body {
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                        text-align: center; /* Optional: If you want to center-align text as well */
                                    }
                                        .col-sm-8{
                                            /* width: 50% !important;*/
                                        }
                                    .col-shm-4 {
                                        /* width: 200px;*/
                                        margin-left: 0; /* Resetting the margin-left */
                                    }
                                    @media print {
                                        .voucher-container {
                                            padding: unset !important;
                                            border: unset !important;
                                            // background: unset;
                                        }
                                        .voucher-header h2{
                                            margin-top: 2px !important;
                                            margin-bottom: 2px !important;
                                        } 
                                    }
                                </style>";
                        $listed .= "<div class='row'>";
                        $listed .= "<div class='col-sm-8 col-xs-8'>";
                        $listed .= "<div class='col-sm-4'>";
                        $listed .= "<div class='voucher-container'>";
                        $listed .= "<div  class='voucher-header'>";
                        $listed .= "<h2>Voucher Tukar Tambah</h2>";
                        $listed .= "</div>";
                        $listed .= "<div class='voucher-body'>";
                        $listed .= "<p>Selamat! Anda mendapatkan voucher.</p>";
                        $listed .= "<small>KODE VOUCHER :</small>";
                        $listed .= "<div class='voucher-code'>$serialcode</div>";
                        $listed .= "<p>Gunakan kode ini saat berbelanja.</p>";

                        $listed .= "<div class='col-shm-4' style='width:85px;'>";
                        $listed .= "<img src='$qrfile' title='' class='img-thumbnail'>";
                        $listed .= "</div>";

                        $listed .= "</div>";


                        $listed .= "<div class='voucher-footer'>";
                        $listed .= "<p class=\"terms\">Syarat dan ketentuan berlaku.</p>";
                        $listed .= "</div>";
                        $listed .= "</div>";


                        $listed .= "</div>";
                        $listed .= "</div>";
                        $listed .= "</div>";


                        //                                $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                        //                                $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                        //                                $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'></span>";
                        //                                $listed .= "<span style='color: #0d6aad;' class='text-bold'> [  ] </span>";
                        //                                $listed .= "</div>";
                        //                                $listed .= "</div>";
                        //                                $listed .= "<img src='$qrfile' title='' class='img-thumbnail'>";
                        //                                $listed .= "</div>";
                        //                                $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$serialcode</span></div>";
                    }

                }
                else {

                    // $listed = $this->LayoutPrint($items);
                }
                // -----------------------------------------------------------------------------------
                // matiHEre(__LINE__);
                $data = array(
                    "mode" => "viewPrint",
                    //                "tmp" => $data_show
                    "tmp"  => $listed
                );
                $this->load->view('barcode', $data);
                //                 arrPRint($ids);
                // matiHere("masukk");
            }
            else {
                $arrAlert = array(
                    "type"              => "warning",
                    "title"             => "No data selected",
                    "html"              => "No data selected to print!",
                    "timer"             => "1500",
                    "showConfirmButton" => false,
                    "allowOutsideClick" => false,
                );
                echo swalAlert($arrAlert);
                die();
                echo "<script>topReload(1500)</script>";
                echo topReload();
                echo "</script>";
            }

        }
    }

    public function doPrintModulReg()
    {
        // $this->load->libraries("phpqrcode/phpqrcode");
        // $this->load->libraries("phpqrcode/qrlib");
        //        arrPrint($_GET);

        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();

        if (isset($_POST['id_produk'])) {
            $idData = $_POST['id_produk'];
            $jmlPrint = $_POST['jml_print'];
            $hargas = $_POST['harga'];

            //             arrPrint($_POST);
            //matiHEre();
            $listedxId = "(" . implode(",", $idData) . ")";
            $className = "MdlProduk2";
            $this->load->model("Mdls/MdlProduk2");
            $o = new MdlProduk2();
            //            $o->addFilter(array("id in"=>"$listedxId"));
            $cindition = "id in $listedxId";
            $tmpX = $o->lookupByCondition($cindition)->result();
            $tempValue = array();
            $tempIdx = array();
            if (sizeof($tmpX) > 0) {
                foreach ($tmpX as $tempData) {
                    $id = $tempData->id;
                    $name = $tempData->nama;
                    $barcode = $tempData->barcode;
                    $code = $tempData->kode;
                    $sku = $tempData->sku;

                    $tempValue[$id] = $barcode;
                    $tempIdx[$id] = $name;
                    $tempCode[$id] = $code;
                    //                    $tempSku[$id] = $sku;
                }
            }
            $indexID = array();
            //            $data_show = "";
            foreach ($idData as $xId) {
                $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                //                $data_show .= "<div>";
                if ($maxPrint > 0) {
                    $barcode = $tempValue[$xId];
                    $name_data = $tempIdx[$xId];
                    for ($i = 0; $i < $maxPrint; $i++) {
                        $indexID[] = $xId;
                        //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                        //                    </div>
                        //                    </div>";
                    }

                    //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                }
                //                $data_show .= "<div>";
            }

            $jml_kolom = "4";
            $isi_array = sizeof($indexID);
            $max_baris_perkolom = floor($isi_array / $jml_kolom);
            $sisa_baris_ = $isi_array % $jml_kolom;
            // $ekstra = $jml_kolom / $sisa_baris_;
            $row = 0;
            $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
            if ($sisa_baris_ == 1) {
                $addKolom_1 = 1;
                $addKolom_2 = 1;
                $addKolom_3 = 1;
            }
            elseif ($sisa_baris_ == 2) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 2;
            }
            elseif ($sisa_baris_ == 3) {
                $addKolom_1 = 1;
                $addKolom_2 = 2;
                $addKolom_3 = 3;
            }

            foreach ($indexID as $key => $id) {
                $row++;
                if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                    $arrRow[0][] = array(
                        "id" => $id,

                    );
                }
                elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                    $arrRow[1][] = array(
                        "id" => $id,
                    );
                }
                elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                    // cekPink("$row >= $rowke_1");
                    $arrRow[3][] = array(
                        "id" => $id,

                    );
                }
                else {
                    $arrRow[4][] = array(
                        "id" => $id,

                    );
                }
            }


            $listed = "<div class='row'>";
            $listed .= "<style>.thumbnail text{font-size: 2.5em !important;}</style>";
            $listed .= "<div class='container'>";
            foreach ($arrRow as $k => $tmpX) {
                $listed .= "<div class='col-md-3 col-lg-2 col-xs-3' style='padding: 2px; color: #0d1720;'>";
                $listed .= "<div class='text-center'>";
                foreach ($tmpX as $y => $x) {
                    //                    arrPrint($x);
                    $xID = $x['id'];
                    // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                    $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                    $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                    $name_data = $tempIdx[$xID];
                    //                    $name_sku = $tempSku[$xID];
                    $harga_f = $hargas[$xID];

                    $qr_dta = array(
                        // "produk_id" => "https://demo.",
                        "produk_id"    => $xID,
                        "produk_kode"  => $code,
                        // "pn" => $name_data,
                        "jenis_tr"     => "0",
                        "master_id"    => "0",
                        "transaksi_id" => "0",
                        // "lain-lain" => "1563265",
                        // "ur" => "https://san.mayagrahakencana.com",
                    );
                    // $barcode = implode("|",$qr_dta);
                    // $barcode = json_encode($qr_dta);

                    // $qrcode = $this->generate_qrcode($barcode);
                    $qrcode = $qr->get_qrcode($qr_dta);
                    $qrfile = base_url() . $qrcode['file'];

                    //                    cekHitam($xID);
                    $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                    // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                    $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";
                    //                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$code</span>";

                    // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none;'></svg>";
                    //                    $listed .= "uuuu";
                    // $listed .= "<span>$code</span>";
                    $listed .= "</div>";
                    $listed .= "</div>";


                    // arrPrintKuning($qrcode);
                    // cekKuning( base_url() . $qrcode['file']);
                    // $listed .= "<img src='" . base_url() . $qrcode['file'] . "'>";
                    $listed .= "<img src='$qrfile' title='$xID'>";
                    //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                    // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                    $listed .= "</div>";
                }
                $listed .= "</div>";
                $listed .= "</div>";
            }
            $listed .= "</div>";
            $listed .= "</div>";

            $data = array(
                "mode" => "viewPrint",
                //                "tmp" => $data_show
                "tmp"  => $listed
            );
            $this->load->view('qr', $data);
        }
        else {
            if (isset($_GET['FromTransaksi'])) {
                /*
                 * masih khusus untuk produksi yang lain nanti cutom dulu ya 09-05-2023 widi
                 */
                // arrPrint($_GET);
                // arrPrint($_GET);
                $prefixTransaksi = "manufactur";
                $custom_produk_print = array();
                if (isset($_SESSION['barcode_print']['items'])) {
                    $custom_produk_print = $_SESSION['barcode_print']['items'];
                }
                // arrPrint($custom_produk_print);

                $trid = $_GET['FromTransaksi'];
                $jenisTr = isset($_GET['jn']) ? $_GET['jn'] : 0;
                $masterId = isset($_GET['mid']) ? $_GET['mid'] : 0;
                $this->load->model("MdlTransaksi");
                $this->load->model("Mdls/MdlProduk2");
                $tr = new MdlTransaksi();
                $tr->setFilters(array());
                $tr->setJointSelectFields("transaksi_id, items3_sum");
                $tr->addFilter("transaksi_id='$trid'");
                $trTmp = $tr->lookupDataRegistries()->result();
                //                showLast_query("biru");
                // cekHere(count($items));
                //                arrPrint($trTmp);
                //                matiHere(__LINE__);
                // matiHere($this->db->last_query());
                $items = isset($trTmp[0]->items3_sum) ? blobDecode($trTmp[0]->items3_sum) : array();
                $transaksi_id = $trTmp[0]->transaksi_id;
                //                arrPrintWebs($items);
                $ids = array();
                $jmlPrint = array();
                $proidTransaksi = array();
                if (sizeof($items) > 0) {
                    foreach ($items as $iID => $iData) {
                        $jmlPrint[$iData->id] = isset($custom_produk_print[$iData->id]['jml_print']) ? $custom_produk_print[$iData->id]['jml_print'] : 1; // belokan ambil jml print dr session
                        //                        $proidTransaksi[$iData->id] = $iData->transaksi_id;
                        //                        $proidlabels[$iData->id] = $iData->produk_sku_label;
                        $proidTransaksi[$iData->id] = $transaksi_id;
                        $proidlabels[$iData->id] = $iData->part_keterangan;
                        $ids[] = $iData->id;
                        // arrPrint($iData);
                    }
                }

                /* ---------------------------------------------
             * harga produk yg dipilih
             * ---------------------------------------------*/
                // arrPrintWebs($items);
                // arrPrint($jmlPrint);
                // $listedxId = "(" . implode(",", $ids) . ")";
                // $className = "MdlProduk2";
                // $this->load->model("Mdls/MdlProduk2");
                // $o = new MdlProduk2();
                // //            $o->addFilter(array("id in"=>"$listedxId"));
                // $cindition = "id in $listedxId";
                $tmpX = $items;
                $mode_dipakai = 1;
                // -----------------------------------------------------------------------------------
                if ($mode_dipakai == 1) {
                    $tempValue = array();
                    $tempIdx = array();
                    $tempCode = array();
                    $prevData = array();
                    if (sizeof($tmpX) > 0) {
                        foreach ($tmpX as $id => $tempDatas) {
                            $tempData = (object)$tempDatas;
                            //                            arrPrintWebs($tempData);
                            //                            $id = $tempData->id;
                            //                        $name = $tempData->extern_nama;
                            $name = $tempData->nama;
                            // $barcode = $tempData->barcode;
                            // $code = $tempData->kode;
                            //                        $serial = $tempData->serial;
                            $serial = $tempData->produk_serial;
                            $sku = $tempData->produk_sku_part_nama;

                            // $tempValue[$id] = $barcode;
                            $tempIdx[$id] = $name;
                            //                        $prevData[$id] = blobEncode(array("serial"=>$tempData->serial));
                            // arrprint(blobDecode($tempData->blobData));
                            $tempCode[$id] = array(
                                "serial"    => $serial,
                                "sku"       => $sku,
                                //                            "produk_id" => $tempData->extern_id,
                                "produk_id" => $tempData->id,
                                "id"        => $id,
                            );
                        }
                    }
                    //arrPrintPink($tempCode);
                    $indexID = array();
                    //            $data_show = "";
                    foreach ($ids as $xId) {
                        $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
                        //                $data_show .= "<div>";
                        if ($maxPrint > 0) {
                            //                        $barcode = $tempValue[$xId];
                            $name_data = $tempIdx[$xId];
                            for ($i = 0; $i < $maxPrint; $i++) {
                                $indexID[] = $xId;
                                //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                                //                    </div>
                                //                    </div>";
                            }

                            //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
                        }
                        //                $data_show .= "<div>";
                    }

                    $jml_kolom = 1;
                    $isi_array = sizeof($indexID);
                    $max_baris_perkolom = floor($isi_array / $jml_kolom);
                    $sisa_baris_ = $isi_array % $jml_kolom;
                    $row = 0;
                    $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
                    if ($sisa_baris_ == 1) {
                        $addKolom_1 = 1;
                        $addKolom_2 = 1;
                        $addKolom_3 = 1;
                    }
                    elseif ($sisa_baris_ == 2) {
                        $addKolom_1 = 1;
                        $addKolom_2 = 2;
                        $addKolom_3 = 2;
                    }
                    elseif ($sisa_baris_ == 3) {
                        $addKolom_1 = 1;
                        $addKolom_2 = 2;
                        $addKolom_3 = 3;
                    }

                    foreach ($indexID as $key => $id) {
                        $row++;
                        if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                            $arrRow[0][] = array(
                                "id" => $id,

                            );
                        }
                        elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                            $arrRow[1][] = array(
                                "id" => $id,
                            );
                        }
                        elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                            // cekPink("$row >= $rowke_1");
                            $arrRow[3][] = array(
                                "id" => $id,

                            );
                        }
                        else {
                            $arrRow[4][] = array(
                                "id" => $id,

                            );
                        }
                    }
                    $class_col = "col-md-3 col-lg-2 col-xs-3";
                    if ($jml_kolom == 1) {
                        $class_col = "col-md-12 col-lg-12 col-xs-12";
                    }
                    // cekHitam(count($indexID));
                    // arrPrint($arrRow);
                    // cekHitam(count($arrRow));
                    //                matiHere();
                    $listed = "<div class='row'>";
                    $listed .= "<div class='container'>";
                    foreach ($arrRow as $k => $tmpX) {
                        // cekHere(count($tmpX));
                        $listed .= "<div class='$class_col' style='padding: 2px; color: #0d1720;'>";
                        $listed .= "<div class='text-center'>";
                        $nom = 0;
                        foreach ($tmpX as $y => $x) {
                            $nom++;
                            // arrPrintWebs($x);
                            //                            $xID = $x['id'];
                            $xID = $y;
                            // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                            $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                            //                        $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                            //                        $code = $tempCode[$xID] ? $tempCode[$xID] : $x["serial"];
                            $serialcode = $tempCode[$xID]["serial"] ? $tempCode[$xID]["serial"] : "--";
                            $podukID = $tempCode[$xID]["produk_id"] ? $tempCode[$xID]["produk_id"] : "--";
                            $sku = isset($tempCode[$xID]["sku"]) ? $tempCode[$xID]["sku"] : "--";
                            $name_data = $tempIdx[$xID];
                            $skuLabel = $proidlabels[$xID];
                            // $harga_f = $hargas[$xID];
                            // $harga_f = formatField_he_format('curency_print', $harga_produks[$xID]);
                            // $name =
                            $qr_dta = array(
                                // "produk_id" => "https://demo.",
                                //                            "serial" => $serialcode,
                                //                            "produk_id" => $podukID,
                                //                            "produk_kode" => $code,
                                //                            // "pn" => $name_data,
                                //                            "jenis_tr" => $jenisTr,
                                //                            "master_id" => $masterId,
                                //                            "transaksi_id" => $proidTransaksi[$xID],
                                //                            "data" => json_encode($tempCode[$xID]),
                                // "data"=>"sunnnnn",
                                // "lain-lain" => "1563265",
                                // "ur" => "https://san.mayagrahakencana.com",
                            );
                            // $barcode = implode("|",$qr_dta);
                            // $barcode = json_encode($qr_dta);

                            // $qrcode = $this->generate_qrcode($barcode);
                            // arrprint($qr_dta);
                            //                        $qrcode = $qr->get_qrcode_produksi($qr_dta);
                            //                            cekHere("$serialcode");
                            $qrcode = $qr->get_qrcode_pembelian($serialcode);
                            //                        $qrcode = $qr->get_qrcode($qr_dta);
                            $qrfile = base_url() . $qrcode['file'];
                            //
                            //                    cekHitam($xID);
                            $dipakai = 2;
                            if ($dipakai == 1) {
                                $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                                // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                                $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                                $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";
                                $listed .= "<span style='color: #0d6aad;' class='text-bold'> [ $sku ] </span>";
                                // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                                //                    $listed .= "uuuu";
                                // $listed .= "<span>$code</span>";
                                $listed .= "</div>";
                                $listed .= "</div>";


                                // if (validate_EAN13Barcode($barcode)) {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                                // }
                                // else if ($barcode == 'BELUM PUNYA') {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                                // }
                                // else {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                                // }
                                //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                                $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail'>";
                                // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                                $listed .= "</div>";
                                $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$serialcode</span></div>";
                            }
                            else {
                                $listed .= "<div class='bottom-borders' style='mmin-height: 245px;'>";
                                // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                                $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                                // $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data [ $sku ]</span>";
                                // $listed .= "<span style='color: #0d6aad;' class='text-bold'>  </span>";
                                // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                                //                    $listed .= "uuuu";
                                // $listed .= "<span>$code</span>";
                                // $listed .= "</div>";
                                $listed .= "</div>";

                                if (strtolower($skuLabel) == "in") {
                                    $bg_ot = "";
                                    $bg_ot = "bg-hitam";
                                    $listed .= "<div style='white-space: nowrap;font-size: 0.9em;' class='uploaded '>$name_data / $sku</div>";
                                    $listed .= "<div style='' class='uploaded '>$serialcode</div>";
                                    $border_xx = "border: #4e5158 dashed 2px;";
                                }
                                elseif (strtolower($skuLabel) == "ot") {
                                    $border_xx = "";
                                }
                                else {
                                    $border_xx = "";
                                }

                                // if (validate_EAN13Barcode($barcode)) {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                                // }
                                // else if ($barcode == 'BELUM PUNYA') {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                                // }
                                // else {
                                //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                                // }
                                //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                                $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail' style='$border_xx margin: 1px 0;'>";
                                // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                                $bg_ot = "bg-hitam";
                                if (strtolower($skuLabel) == "ot") {
                                    $bg_ot = "bg-hitam";
                                    $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                                    $listed .= "<div style='white-space: nowrap;font-size: 0.9em;' class='uploaded $bg_ot'>$name_data / $sku</div>";
                                }
                                elseif (strtolower($skuLabel) == "in") {
                                    $bg_ot = "";
                                }
                                else {

                                    $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                                    $listed .= "<div style='white-space: nowrap;font-size: 0.9em;' class='uploaded $bg_ot'>$name_data / $sku</div>";
                                }

                                // $listed .= "<div>#$nom</div>";
                                $listed .= "</div>";
                            }
                        }
                        $listed .= "</div>";
                        $listed .= "</div>";
                    }
                    $listed .= "</div>";
                    $listed .= "</div>";
                }
                else {

                    // $listed = $this->LayoutPrint($items);
                }
                // -----------------------------------------------------------------------------------
                // matiHEre(__LINE__);
                $data = array(
                    "mode" => "viewPrint",
                    //                "tmp" => $data_show
                    "tmp"  => $listed
                );
                $this->load->view('barcode', $data);
                //                 arrPRint($ids);
                // matiHere("masukk");
            }
            else {
                $arrAlert = array(
                    "type"              => "warning",
                    "title"             => "No data selected",
                    "html"              => "No data selected to print!",
                    "timer"             => "1500",
                    "showConfirmButton" => false,
                    "allowOutsideClick" => false,
                );
                echo swalAlert($arrAlert);
                die();
                echo "<script>topReload(1500)</script>";
                echo topReload();
                echo "</script>";
            }

        }
    }

    public function viewSerialTransit()
    {

        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();
        $custom_produk_print = array();
        if (isset($_SESSION['barcode_print']['items'])) {
            $custom_produk_print = $_SESSION['barcode_print']['items'];
        }
        // arrPrint($custom_produk_print);
        //arrPrint($_GET);
        //        matiHEre();
        $produk_id = $_GET["produk_id"];
        $cabang_id = $_GET["cabang_id"];
        $gudang_id = $_GET["gudang_id"];
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerialIntransit");
        $this->load->model("Mdls/MdlProduk2");
        $tr = new ComRekeningPembantuProdukPerSerialIntransit();

        $tr->setFilters(array());
        $tr->addFilter("produk_id='$produk_id'");
        $tr->addFilter("cabang_id='$cabang_id'");
        if (isset($_GET["gudang_id"])) {
            $tr->addFilter("gudang_id='$gudang_id'");
        }
        //        $items = $tr->fetchBalances("1010030");
        $tr->addFilter("qty_debet>0");
        $items = $tr->fetchBalances("1010030030");

        $ids = array();
        $jmlPrint = array();
        $proidTransaksi = array();
        if (sizeof($items) > 0) {
            foreach ($items as $iID => $iData) {
                $jmlPrint[$iData->id] = isset($custom_produk_print[$iData->id]['jml_print']) ? $custom_produk_print[$iData->id]['jml_print'] : 1; // belokan ambil jml print dr session
                //                $proidTransaksi[$iData->id] = $iData->transaksi_id;

                $proidlabels[$iData->id] = substr($iData->extern_nama, -2);
                $ids[] = $iData->id;
                // arrPrint($iData);
            }
        }

        /* ---------------------------------------------
     * harga produk yg dipilih
     * ---------------------------------------------*/

        $tmpX = $items;
        // -----------------------------------------------------------------------------------
        //                 arrPrint($tmpX);
        //                 matiHEre();
        $tempValue = array();
        $tempIdx = array();
        $tempCode = array();
        $prevData = array();
        if (sizeof($tmpX) > 0) {
            foreach ($tmpX as $tempData) {
                $id = $tempData->id;
                //                        $name = $tempData->extern_nama;
                $name = $tempData->produk_nama;
                // $barcode = $tempData->barcode;
                // $code = $tempData->kode;
                //                        $serial = $tempData->serial;
                $serial = $tempData->extern_nama;
                $sku = $tempData->extern2_nama;

                // $tempValue[$id] = $barcode;
                $tempIdx[$id] = $name;
                //                        $prevData[$id] = blobEncode(array("serial"=>$tempData->serial));
                // arrprint(blobDecode($tempData->blobData));
                $tempCode[$id] = array(
                    "serial"    => $serial,
                    "sku"       => $sku,
                    "produk_id" => $tempData->produk_id,
                    "id"        => $tempData->id,
                );
            }
        }
        //                arrprint($prevData);
        //                 matiHere();
        $indexID = array();
        //            $data_show = "";
        foreach ($ids as $xId) {
            $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
            //                $data_show .= "<div>";
            if ($maxPrint > 0) {
                //                        $barcode = $tempValue[$xId];
                $name_data = $tempIdx[$xId];
                for ($i = 0; $i < $maxPrint; $i++) {
                    $indexID[] = $xId;
                    //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                    //                    </div>
                    //                    </div>";
                }

                //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
            }
            //                $data_show .= "<div>";
        }

        $jml_kolom = 1;
        $isi_array = sizeof($indexID);
        $max_baris_perkolom = floor($isi_array / $jml_kolom);
        $sisa_baris_ = $isi_array % $jml_kolom;
        $row = 0;
        $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
        if ($sisa_baris_ == 1) {
            $addKolom_1 = 1;
            $addKolom_2 = 1;
            $addKolom_3 = 1;
        }
        elseif ($sisa_baris_ == 2) {
            $addKolom_1 = 1;
            $addKolom_2 = 2;
            $addKolom_3 = 2;
        }
        elseif ($sisa_baris_ == 3) {
            $addKolom_1 = 1;
            $addKolom_2 = 2;
            $addKolom_3 = 3;
        }

        foreach ($indexID as $key => $id) {
            $row++;
            if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                $arrRow[0][] = array(
                    "id" => $id,

                );
            }
            elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                $arrRow[1][] = array(
                    "id" => $id,
                );
            }
            elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                // cekPink("$row >= $rowke_1");
                $arrRow[3][] = array(
                    "id" => $id,

                );
            }
            else {
                $arrRow[4][] = array(
                    "id" => $id,

                );
            }
        }

        $class_col = "col-md-3 col-lg-2 col-xs-3";
        if ($jml_kolom == 1) {
            $class_col = "col-md-12 col-lg-12 col-xs-12";
        }

        //                arrPrint($arrRow);
        //                matiHere();
        $listed = "<div class='row'>";
        $listed .= "<div class='container'>";
        foreach ($arrRow as $k => $tmpX) {
            $listed .= "<div class='$class_col' style='padding: 2px; color: #0d1720;'>";
            $listed .= "<div class='text-center'>";
            foreach ($tmpX as $y => $x) {
                // arrPrintWebs($x);
                $xID = $x['id'];
                // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                //                        $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                //                        $code = $tempCode[$xID] ? $tempCode[$xID] : $x["serial"];
                $serialcode = $tempCode[$xID]["serial"] ? $tempCode[$xID]["serial"] : "--";
                $sku = $tempCode[$xID]["sku"] ? $tempCode[$xID]["sku"] : "--";
                $podukID = $tempCode[$xID]["produk_id"] ? $tempCode[$xID]["produk_id"] : "--";
                $name_data = $tempIdx[$xID];
                $skuLabel = $proidlabels[$xID];
                $qrcode = $qr->get_qrcode_pembelian($serialcode);
                //                        $qrcode = $qr->get_qrcode($qr_dta);
                $qrfile = base_url() . $qrcode['file'];
                //                    cekHitam($xID);
                $dipakai = 2;
                if ($dipakai == 1) {
                    $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                    // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                    $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";
                    $listed .= "<span style='color: #0d6aad;' class='text-bold'>[ $sku ]</span>";

                    // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                    //                    $listed .= "uuuu";
                    // $listed .= "<span>$code</span>";
                    $listed .= "</div>";
                    $listed .= "</div>";


                    // if (validate_EAN13Barcode($barcode)) {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                    // }
                    // else if ($barcode == 'BELUM PUNYA') {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                    // }
                    // else {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                    // }
                    //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                    $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail'>";
                    // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                    $listed .= "</div>";
                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$serialcode</span></div>";
                }
                else {
                    $listed .= "<div class='bottom-borders' style='hheight: 272px;'>";
                    // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                    $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    // $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data [ $sku ]</span>";
                    // $listed .= "<span style='color: #0d6aad;' class='text-bold'>  </span>";
                    // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                    //                    $listed .= "uuuu";
                    // $listed .= "<span>$code</span>";
                    // $listed .= "</div>";
                    $listed .= "</div>";

                    if (strtolower($skuLabel) == "in") {
                        $bg_ot = "";
                        $bg_ot = "bg-hitam";
                        $listed .= "<div style='' class='uploaded '>$name_data / $sku</div>";
                        $listed .= "<div style='' class='uploaded '>$serialcode</div>";
                        $border_xx = "border: #4e5158 dashed 2px;";
                    }
                    elseif (strtolower($skuLabel) == "ot") {
                        $border_xx = "";
                    }
                    else {
                        $border_xx = "";
                    }

                    // if (validate_EAN13Barcode($barcode)) {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                    // }
                    // else if ($barcode == 'BELUM PUNYA') {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                    // }
                    // else {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                    // }
                    //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                    $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail' style='$border_xx margin: 1px 0;'>";
                    // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                    $bg_ot = "bg-hitam";
                    if (strtolower($skuLabel) == "ot") {
                        $bg_ot = "bg-hitam";
                        $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                        $listed .= "<div style='' class='uploaded $bg_ot'>$name_data / $sku</div>";
                    }
                    elseif (strtolower($skuLabel) == "in") {
                        $bg_ot = "";
                    }
                    else {

                        $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                        $listed .= "<div style='' class='uploaded $bg_ot'>$name_data / $sku</div>";
                    }

                    $listed .= "</div>";
                }

            }
            $listed .= "</div>";
            $listed .= "</div>";
        }
        $listed .= "</div>";
        $listed .= "</div>";
        //         matiHEre(__LINE__);
        $data = array(
            "mode" => "viewPrint",
            "tmp"  => $listed
        );
        $this->load->view('barcode', $data);
    }

    public function viewSerial()
    {

        $this->load->library("Ciqrcode");
        $qr = new Ciqrcode();
        $custom_produk_print = array();
        if (isset($_SESSION['barcode_print']['items'])) {
            $custom_produk_print = $_SESSION['barcode_print']['items'];
        }
        // arrPrint($custom_produk_print);
        //arrPrint($_GET);
        //        matiHEre();
        $produk_id = $_GET["produk_id"];
        $cabang_id = $_GET["cabang_id"];
        $gudang_id = $_GET["gudang_id"];
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        $this->load->model("Mdls/MdlProduk2");
        $tr = new ComRekeningPembantuProdukPerSerial();

        $tr->setFilters(array());
        $tr->addFilter("produk_id='$produk_id'");
        $tr->addFilter("cabang_id='$cabang_id'");
        if (isset($_GET["gudang_id"])) {
            $tr->addFilter("gudang_id='$gudang_id'");
        }
        //        $items = $tr->fetchBalances("1010030");
        $tr->addFilter("qty_debet>0");
        $items = $tr->fetchBalances("1010030030");

        $ids = array();
        $jmlPrint = array();
        $proidTransaksi = array();
        if (sizeof($items) > 0) {
            foreach ($items as $iID => $iData) {
                $jmlPrint[$iData->id] = isset($custom_produk_print[$iData->id]['jml_print']) ? $custom_produk_print[$iData->id]['jml_print'] : 1; // belokan ambil jml print dr session
                //                $proidTransaksi[$iData->id] = $iData->transaksi_id;

                $proidlabels[$iData->id] = substr($iData->extern_nama, -2);
                $ids[] = $iData->id;
                // arrPrint($iData);
            }
        }

        /* ---------------------------------------------
     * harga produk yg dipilih
     * ---------------------------------------------*/

        $tmpX = $items;
        // -----------------------------------------------------------------------------------
        //                 arrPrint($tmpX);
        //                 matiHEre();
        $tempValue = array();
        $tempIdx = array();
        $tempCode = array();
        $prevData = array();
        if (sizeof($tmpX) > 0) {
            foreach ($tmpX as $tempData) {
                $id = $tempData->id;
                //                        $name = $tempData->extern_nama;
                $name = $tempData->produk_nama;
                // $barcode = $tempData->barcode;
                // $code = $tempData->kode;
                //                        $serial = $tempData->serial;
                $serial = $tempData->extern_nama;
                $sku = $tempData->extern2_nama;

                // $tempValue[$id] = $barcode;
                $tempIdx[$id] = $name;
                //                        $prevData[$id] = blobEncode(array("serial"=>$tempData->serial));
                // arrprint(blobDecode($tempData->blobData));
                $tempCode[$id] = array(
                    "serial"    => $serial,
                    "sku"       => $sku,
                    "produk_id" => $tempData->produk_id,
                    "id"        => $tempData->id,
                );
            }
        }
        //                arrprint($prevData);
        //                 matiHere();
        $indexID = array();
        //            $data_show = "";
        foreach ($ids as $xId) {
            $maxPrint = $jmlPrint[$xId] > 0 ? $jmlPrint[$xId] : 0;
            //                $data_show .= "<div>";
            if ($maxPrint > 0) {
                //                        $barcode = $tempValue[$xId];
                $name_data = $tempIdx[$xId];
                for ($i = 0; $i < $maxPrint; $i++) {
                    $indexID[] = $xId;
                    //                        $data_show .= "<svg class='thumbnail' id='p_$xId' style='width:200px;'></svg>";
                    //                    </div>
                    //                    </div>";
                }

                //                    $data_show .= "<script>JsBarcode('#p_$xId', '$barcode', {format: 'code39'});</script>";
            }
            //                $data_show .= "<div>";
        }

        $jml_kolom = 1;
        $isi_array = sizeof($indexID);
        $max_baris_perkolom = floor($isi_array / $jml_kolom);
        $sisa_baris_ = $isi_array % $jml_kolom;
        $row = 0;
        $addKolom_1 = $addKolom_2 = $addKolom_3 = 0;
        if ($sisa_baris_ == 1) {
            $addKolom_1 = 1;
            $addKolom_2 = 1;
            $addKolom_3 = 1;
        }
        elseif ($sisa_baris_ == 2) {
            $addKolom_1 = 1;
            $addKolom_2 = 2;
            $addKolom_3 = 2;
        }
        elseif ($sisa_baris_ == 3) {
            $addKolom_1 = 1;
            $addKolom_2 = 2;
            $addKolom_3 = 3;
        }

        foreach ($indexID as $key => $id) {
            $row++;
            if (($row >= 1) && ($row <= ($max_baris_perkolom + $addKolom_1))) {
                $arrRow[0][] = array(
                    "id" => $id,

                );
            }
            elseif (($row >= ($max_baris_perkolom + $addKolom_2)) && ($row <= ($rowke_1 = ($max_baris_perkolom) * 2) + $addKolom_2)) {
                $arrRow[1][] = array(
                    "id" => $id,
                );
            }
            elseif (($row >= $rowke_1) && ($row <= ($rowke_2 = ($max_baris_perkolom) * 3) + $addKolom_3)) {
                // cekPink("$row >= $rowke_1");
                $arrRow[3][] = array(
                    "id" => $id,

                );
            }
            else {
                $arrRow[4][] = array(
                    "id" => $id,

                );
            }
        }

        $class_col = "col-md-3 col-lg-2 col-xs-3";
        if ($jml_kolom == 1) {
            $class_col = "col-md-12 col-lg-12 col-xs-12";
        }

        //                arrPrint($arrRow);
        //                matiHere();
        $listed = "<div class='row'>";
        $listed .= "<div class='container'>";
        foreach ($arrRow as $k => $tmpX) {
            $listed .= "<div class='$class_col' style='padding: 2px; color: #0d1720;'>";
            $listed .= "<div class='text-center'>";
            foreach ($tmpX as $y => $x) {
                // arrPrintWebs($x);
                $xID = $x['id'];
                // $barcode = $tempValue[$xID] ? $tempValue[$xID] : "BELUM PUNYA";
                $barcode = $tempCode[$xID] ? $tempCode[$xID] : "BELUM PUNYA";
                //                        $code = $tempCode[$xID] ? $tempCode[$xID] : "--";
                //                        $code = $tempCode[$xID] ? $tempCode[$xID] : $x["serial"];
                $serialcode = $tempCode[$xID]["serial"] ? $tempCode[$xID]["serial"] : "--";
                $sku = $tempCode[$xID]["sku"] ? $tempCode[$xID]["sku"] : "--";
                $podukID = $tempCode[$xID]["produk_id"] ? $tempCode[$xID]["produk_id"] : "--";
                $name_data = $tempIdx[$xID];
                $skuLabel = $proidlabels[$xID];
                $qrcode = $qr->get_qrcode_pembelian($serialcode);
                //                        $qrcode = $qr->get_qrcode($qr_dta);
                $qrfile = base_url() . $qrcode['file'];
                //                    cekHitam($xID);
                $dipakai = 2;
                if ($dipakai == 1) {
                    $listed .= "<div class='bottom-borders' style='hheight: 90px;'>";
                    // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                    $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data</span>";
                    $listed .= "<span style='color: #0d6aad;' class='text-bold'>[ $sku ]</span>";

                    // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                    //                    $listed .= "uuuu";
                    // $listed .= "<span>$code</span>";
                    $listed .= "</div>";
                    $listed .= "</div>";


                    // if (validate_EAN13Barcode($barcode)) {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                    // }
                    // else if ($barcode == 'BELUM PUNYA') {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                    // }
                    // else {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                    // }
                    //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                    $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail'>";
                    // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                    $listed .= "</div>";
                    $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$serialcode</span></div>";
                }
                else {
                    $listed .= "<div class='bottom-borders' style='hheight: 272px;'>";
                    // $listed .= "<div class='text-center bottom-borders' style='margin-bottom: 4px;'>";
                    $listed .= "<div class='text-center no-padding' style='margin-bottom: 0px;'>";
                    // $listed .= "<div style='' class='uploaded'><span style='color: #0d6aad;'>$name_data [ $sku ]</span>";
                    // $listed .= "<span style='color: #0d6aad;' class='text-bold'>  </span>";
                    // $listed .= "<svg class='thumbnail' id='r_$xID' style='width:171px;height:60px;padding: 0px;margin-bottom: 0px;border: none'></svg>";
                    //                    $listed .= "uuuu";
                    // $listed .= "<span>$code</span>";
                    // $listed .= "</div>";
                    $listed .= "</div>";

                    if (strtolower($skuLabel) == "in") {
                        $bg_ot = "";
                        $bg_ot = "bg-hitam";
                        $listed .= "<div style='' class='uploaded '>$name_data / $sku</div>";
                        $listed .= "<div style='' class='uploaded '>$serialcode</div>";
                        $border_xx = "border: #4e5158 dashed 2px;";
                    }
                    elseif (strtolower($skuLabel) == "ot") {
                        $border_xx = "";
                    }
                    else {
                        $border_xx = "";
                    }

                    // if (validate_EAN13Barcode($barcode)) {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'ean13', lineColor: '#0d1720'});</script>";
                    // }
                    // else if ($barcode == 'BELUM PUNYA') {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#e02907'});</script>";
                    // }
                    // else {
                    //     $listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39', lineColor: '#0d1720'});</script>";
                    // }
                    //$listed .= "<script>JsBarcode('#r_$xID', '$barcode', {format: 'code39'});</script>";
                    $listed .= "<img src='$qrfile' title='$xID' class='img-thumbnail' style='$border_xx margin: 1px 0;'>";
                    // $listed .= "<div style='margin: -70px 0 0 auto; color: #0c0c0c;writing-mode: vertical-lr;'>$harga_f</div>";

                    $bg_ot = "bg-hitam";
                    if (strtolower($skuLabel) == "ot") {
                        $bg_ot = "bg-hitam";
                        $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                        $listed .= "<div style='' class='uploaded $bg_ot'>$name_data / $sku</div>";
                    }
                    elseif (strtolower($skuLabel) == "in") {
                        $bg_ot = "";
                    }
                    else {

                        $listed .= "<div style='' class='uploaded $bg_ot'>$serialcode</div>";
                        $listed .= "<div style='' class='uploaded $bg_ot'>$name_data / $sku</div>";
                    }

                    $listed .= "</div>";
                }

            }
            $listed .= "</div>";
            $listed .= "</div>";
        }
        $listed .= "</div>";
        $listed .= "</div>";
        //         matiHEre(__LINE__);
        $data = array(
            "mode" => "viewPrint",
            "tmp"  => $listed
        );
        $this->load->view('barcode', $data);
    }

    private function getProdukperSerialNumber($filters)
    {
        $tr = new MdlProdukPerSerialNumber();

        $tr->setFilters(array());
        // $tr->addFilter("transaksi_id='$trid'");
        $this->db->where($filters);
        $items = $tr->lookUpAll()->result();
        //                showLast_query("biru");
        //                arrPrint($items);
        //                matiHere(__LINE__);
        // matiHere($this->db->last_query());
        $ids = array();
        $jmlPrint = array();
        $proidTransaksi = array();
        if (sizeof($items) > 0) {
            foreach ($items as $iID => $iData) {
                // $jmlPrint[$iData->id] = isset($custom_produk_print[$iData->id]['jml_print']) ? $custom_produk_print[$iData->id]['jml_print'] : 1; // belokan ambil jml print dr session
                $proidTransaksi[$iData->id] = $iData->transaksi_id;
                $proidlabels[$iData->id] = $iData->produk_sku_label;
                $ids[] = $iData->id;
                // arrPrint($iData);
                $produk_id = $iData->produk_id;
                $serialn = $iData->produk_serial_number_2;
                $serialNumbers[$produk_id][] = $serialn;
            }
        }
        $arrData['row'] = $items;
        $arrData['serial'] = $serialNumbers;

        return $arrData;
    }

    function sntracking()
    {
        // $this->load->model("Mdls/MdlProdukCabang");
        // $td = new MdlProdukCabang();
        // $condites = array(
        //     "cabang_id" => my_cabang_id(),
        //     "last_updater >" => '0',
        // );
        // $this->db->where($condites);
        // $this->db->order_by('last_dtime', 'desc');
        // $this->db->limit(20);
        // $tmps = $td->lookupAll()->result();
        // showLast_query("lime");
        $tmps = array();
        $items = array();
        foreach ($tmps as $tmp) {

            // $datas = blobDecode($tmp->datas);
            // // arrPrint($datas);
            // $items[] = (array)$tmp + $datas['main'];
        }
        // arrPrintPink($items);
        // cekHijau();
        $headerFields = array(
            "kode"        => array(
                "link" => "Searching/viewProduk",
            ),
            // <a href="https://teguh.mayagrahakencana.com/Searching/viewProduk/3333/5" data-toggle="modal" data-target="#myModal">024.020 | ROTOR BLOWER RINOSAURUS | RBD-125</a>
            // "oleh_nama"   => "oleh",
            // "customer_nama" => "konsumen",
            // "otp" => "PIN",
            "produk_nama" => "diskon produk",
            "lokasi_nama" => array(
                "link" => "Searching/viewRak",
            ),
            // "last_updater"    => "tambahan diskon",
            "last_dtime"  => array(
                "format" => "tglDurasi"
            ),
        );
        $arrButton = array();
        $mb = New MobileDetect();
        $isMob = $mb->isMobile();
        $data = array(
            "mode"          => "scaner",
            "title"         => "scaner produk",
            "subTitle"      => "pengaturan",
            "headerFields"  => $headerFields,
            "items"         => $tmps,
            "warning"       => isset($warning) ? $warning : array(),
            "marking"       => isset($marking) ? $marking : array(),
            "markingColumn" => isset($markingColumn) ? $markingColumn : array(),
            "button"        => $arrButton,
            "do_scane"      => MODUL_PATH . get_class($this) . "/doTrackSn",
            "isMobile"      => $isMob,
        );

        $this->load->view("qr", $data);
    }

    public function doTrackSn()
    {
        // arrPrint($_GET);

        $str = $_GET['str'];
        $var = "";
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlProdukPerSerialNumber");
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/MdlGudangDefault");
        $this->load->model("Coms/ComRekeningPembantuProdukPerSerial");
        //fetchBalances
        //        $this->load->library("Ciqrcode");
        //        $qrc = new Ciqrcode();
        $tr = new MdlTransaksi();
        $r = new ComRekeningPembantuProdukPerSerial();
        $s = new MdlProdukPerSerialNumber();
        $c = new MdlCabang();
        $g = new MdlGudangDefault();

        // $qrKeys = $qrc->get_qr_key_patern();
        //        $qrDatas = $qrc->qrExtractor($str);
        //         arrPrintHijau($qrDatas);
        // arrPrintHijau($params);
        // foreach ($qrKeys as $ky => $qrKey) {
        // $$qrKey = $params[$ky];
        //        foreach ($qrDatas as $ky => $qrVal) {
        //            $$ky = $qrVal;
        //
        //            // cekBiru("$ky :: " . $qrVal);
        //        }
        //        $c->addFilter("jenis=''");
        $dataCabangTmp = $c->lookUpAll()->result();
        if (count($dataCabangTmp) > 0) {
            foreach ($dataCabangTmp as $dataCabangTmp_0) {
                $dataCabang[$dataCabangTmp_0->id] = $dataCabangTmp_0->nama;
            }
        }
        $tempGudang = $g->lookUpAll()->result();
        //        arrPrintWebs($tempGudang);
        $s->addFilter("produk_serial_number_2=$str");
        $tmpSerial = $s->lookUpAll()->result();
        cekMerah($this->db->last_query());
        $produk_id = $tmpSerial[0]->produk_id;

        $r->addFilter("extern_nama='$str'");
        $r->addFilter("qty_debet='1'");
        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $r->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
        $balance_serial = $r->fetchBalances("1010030030");
        showLast_query("lime", "jml data " . count($balance_serial));
        $infoSerial = "";
        $lastMove = array();
        if (count($balance_serial) > 0) {
            $infoSerial["avail"] = array(
                "str"    => $str,
                "status" => "tersedia"
            );
            //stok tersedia
        }
        else {
            $infoSerial["avail"] = array(
                "str"    => $str,
                "status" => "tidak tersedia"
            );
            //ada dicabang lain
            //sudah terjual
            $lastPoisitionFields = array(
                "cabang_id"    => "cabang_id",
                "gudang_id"    => "gudang_id",
                "jenis"        => "jenis",
                "transaksi_id" => "transaksi_id",
                "transaksi_no" => "transaksi_no",
                "qty_debet"    => "qty_debet",
                "qty_kredit"   => "qty_kredit",
                "keterangan"   => "keterangan",
                "dtime"        => "dtime",
            );
            $r->setFilters(array());
            $r->addFilter("extern_nama='$str'");
            //            $r->addFilter("produk_id='$produk_id'");
            $this->db->order_by("id", "desc");
            $this->db->limit(1);
            $tempLastPOsision = $r->fetchLastMoves()->result();
            showLast_query('biru');
            // arrPrintHijau($tempLastPOsision);
            if (count($tempLastPOsision) > 0) {
                foreach ($tempLastPOsision as $tempLastPOsision_0) {

                    foreach ($lastPoisitionFields as $key => $alias) {
                        $lastMove[$key] = $tempLastPOsision_0->$key;
                    }
                    $_debet_qty = $tempLastPOsision_0->qty_debet;
                    $_kredit_qty = $tempLastPOsision_0->qty_kredit;
                    switch ($tempLastPOsision_0->jenis) {
                        case "582spd":
                        case "5822spd":
                            $status = "dikirim";
                            break;
                        case "7778":
                            $status = "pindah buku";
                            break;
                        default :

                            //                            $status = "stok ada di " . $dataCabang[$tempLastPOsision_0->cabang_id];
                            //                             $status = $_debet_qty == "1" ? "Tersedia": "Tidak Tersedia";
                            $status = $tempLastPOsision_0->jenis;
                            $status_cabamg = $_debet_qty == "1" ? "stok ada di " . $dataCabang[$tempLastPOsision_0->cabang_id] : "Tidak Tersedia";
                            break;
                    }

                    $lastMove["status"] = $status;
                    $lastMove["cabang_nama"] = isset($dataCabang[$tempLastPOsision_0->cabang_id]) ? $dataCabang[$tempLastPOsision_0->cabang_id] : "";
                    // $infoSerial["avail"] = array(
                    //     "str"    => $str,
                    //     "status" => ($status == "dikirim") || ($status == "tidak tersedia") ? "tidak tersedia" : $status_cabamg
                    // );
                }
            }
            else {
                $infoSerial["avail"] = array(
                    "str"    => "<r class='font-size-1-2''>$str</r>",
                    "status" => "serial tidak dikenal/ tidak terdaftar"
                );
            }

            $transaksi_id = $lastMove['transaksi_id'];
            if ($transaksi_id > 0) {
                $kolom_transaksi = array(
                    "customers_nama",
                    "salesman_nama",
                    "oleh_nama",
                    "dtime",
                    // "id",
                );
                $trDatas = $tr->lookupByID($transaksi_id)->row();
                // showLast_query("lime");
                // arrPrintKuning($trDatas);
                foreach ($kolom_transaksi as $item) {
                    $lastMove[$item] = $trDatas->$item;
                }
            }

            //            arrPrint($tempLastPOsision);
            arrPrint($lastMove);
            //            cekMErah($this->db->last_query());

        }
        //cekBiru($this->db->last_query());
        //        matiHere(__LINE__." :: ".$produk_id);
        // cekPink($produk_id);
        /*---spek produk------*/
        $this->load->model("Mdls/MdlProduk2");
        $pr = new MdlProduk2();
        $speks = $pr->callSpecs($produk_id);
        // showLast_query("biru");
        // arrPrintHijau($speks);
        $produk_jenis = $speks[$produk_id]->jenis;
        // arrPrint($produk_jenis);

        /*----komposisi---*/
        $str_kom = "<hr><h3 class='text-uppercase'>:: bukan produk rakitan ::</h3>";
        if ($produk_jenis == "item_rakitan") {
            $this->load->model("Mdls/MdlProdukKomposisi");
            $pk = new MdlProdukKomposisi();
            // $pKompos = $pk->lookupByPID($produk_id)->result();
            $pKompos = $pk->callSpecs($produk_id);
            // showLast_query("hijau");
            // arrPrintPink($pKompos);


            $keyKompos = array(
                "produk_dasar_nama" => array(),
                "satuan_nama"       => array(),
                "jml"               => array(
                    "attr" => "class='text-right'",
                ),
                "nilai"             => array(
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "attr"       => "class='text-right'",
                ),
            );
            $str_kom = "<hr><h3 class='text-uppercase'>produk BOM</h3>";
            $str_kom .= "<table>";
            foreach ($pKompos[$produk_id] as $pKompo) {

                $str_kom .= "<tr>";
                foreach ($keyKompos as $keyKompo => $parKompo) {
                    $knilai = isset($pKompo->$keyKompo) ? $pKompo->$keyKompo : 0;
                    // $nilai = isset($datas[$ky]) ? $datas[$ky] : "--";

                    $label = isset($parKompo['label']) ? $parKompo['label'] : $ky;
                    $attr = isset($parKompo['attr']) ? $parKompo['attr'] : "";

                    $format_key = isset($parKompo['format_key']) ? $parKompo['format_key'] : $ky;
                    $knilai_f = isset($parKompo['format']) ? $parKompo['format']($format_key, $knilai) : $knilai;

                    $str_kom .= "<td $attr>$knilai_f</td>";
                }
                $str_kom .= "</tr>";
            }
            $str_kom .= "</table>";
        }

        /*---stok locker produk active------*/
        $this->load->model("Mdls/MdlLockerStock");
        $loc = new MdlLockerStock();
        $loc->setFilters(array());
        $condites = array(
            "state"      => "hold",
            "produk_id"  => $produk_id,
            "gudang_id<" => 0,
            "jumlah >"   => 0,
        );
        $this->db->where($condites);
        $condite_ins = array(
            "produk",
            "produk_rakitan",
        );
        $this->db->where_in("jenis", $condite_ins);
        $locker_hold = $loc->lookupAll()->result();
        showLast_query("hijau");
        arrPrintHijau($locker_hold);

        foreach ($locker_hold as $lockerdata) {
            $lc_prod_id = $lockerdata->produk_id;
            $lc_cabang_id = $lockerdata->cabang_id;
            $lc_jml = $lockerdata->jumlah;
            $lc_transaksi_id = $lockerdata->transaksi_id;
            $lc_oleh_id = $lockerdata->oleh_id;
            $lc_oleh_nama = $lockerdata->oleh_nama;

            if ($lc_transaksi_id > 0) {
                if (!isset($spekLockerHold[$lc_prod_id]["jml_0"])) {
                    $spekLockerHold[$lc_prod_id]["jml_0"] = 0;
                }
                $spekLockerHold[$lc_prod_id]["jml_0"] += $lc_jml;

                $spekLockerHold[$lc_prod_id]["jml_" . $lc_cabang_id] = $lc_jml;
            }

            if ($lc_oleh_id > 0) {
                if (!isset($spekLockerBooking[$lc_prod_id]["jml_0"])) {
                    $spekLockerBooking[$lc_prod_id]["jml_0"] = 0;
                }
                $spekLockerBooking[$lc_prod_id]["jml_0"] += $lc_jml;

                $spekLockerBooking[$lc_prod_id]["jml_" . $lc_cabang_id] = $lc_jml;
            }

        }

        arrPrintKuning($spekLockerHold);

        /*---stok locker produk active------*/
        $this->load->model("Mdls/MdlLockerStock");
        $loc = new MdlLockerStock();
        $loc->setFilters(array());
        $condites = array(
            "state"      => "active",
            "produk_id"  => $produk_id,
            "gudang_id<" => 0,
        );
        $this->db->where($condites);
        $condite_ins = array(
            "produk",
            "produk_rakitan",
        );
        $this->db->where_in("jenis", $condite_ins);
        $locker = $loc->lookupAll()->result();
        showLast_query("kuning");
        // arrPrintKuning($locker);

        $spekLocker = array();
        foreach ($locker as $lockerdata) {
            $lc_prod_id = $lockerdata->produk_id;
            $lc_cabang_id = $lockerdata->cabang_id;
            $lc_jml = $lockerdata->jumlah;

            if (!isset($spekLocker[$lc_prod_id]["jml_0"])) {
                $spekLocker[$lc_prod_id]["jml_0"] = 0;
            }
            $spekLocker[$lc_prod_id]["jml_0"] += $lc_jml;

            $spekLocker[$lc_prod_id]["jml_" . $lc_cabang_id] = $lc_jml;
        }
        // arrPrint($spekLocker);
        /*---harga---*/
        // cekMerah("$produk_id");
        $this->load->model("Mdls/MdlHargaProduk");
        $hr = new MdlHargaProduk();
        $grhs = $hr->callProdukHarga($produk_id);
        // arrPrint($grhs);
        $harga_speks = array();
        $arrCabang = array();
        foreach ($grhs as $hProdId => $cb_hargas) {
            foreach ($cb_hargas as $cb_id => $spek_hargas) {

                $harga_speks[$hProdId]['hpp_' . $cb_id] = isset($spek_hargas['hpp']) ? $spek_hargas['hpp']['nilai'] : 0;
                $harga_speks[$hProdId]['jual_' . $cb_id] = isset($spek_hargas['jual']) ? $spek_hargas['jual']['nilai'] : 0;
                $harga_speks[$hProdId]['jual_nppn_' . $cb_id] = isset($spek_hargas['jual_nppn']) ? $spek_hargas['jual_nppn']['nilai'] : 0;

                $arrCabang[$cb_id] = 1;
            }
        }

        /*---last GRN produk------*/
        // $this->load->model("MdlTransaksi");
        // $tr = new MdlTransaksi();
        // if ($master_id == 0) {
        //     $var . "<div class='alert alert-warning'> qrcode tidak suport detile transaksi terakhir</div>";
        // }
        // else {
        //
        //     $trs = $tr->lookupDataRegistriesByMasterID($master_id)->result();
        //     showLast_query("pink");
        //     arrPrintPink($trs);
        // }

        /*--------cabang----------*/
        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $cbs = $cb->lookupAll()->result();
        // arrPrint($cbs);
        $cabangs = array();
        foreach ($cbs as $cb) {
            $cabangs[$cb->id] = $cb->nama;
        }


        /*----spek harga-----*/
        $data_gabungan = array();
        foreach ($speks as $prod_id => $spek) {

            $data_gabungan[$prod_id] = (array)$spek + (isset($spekLocker[$prod_id]) ? $spekLocker[$prod_id] : array()) + (isset($harga_speks[$prod_id]) ? $harga_speks[$prod_id] : array());
        }
        // arrPrintPink($data_gabungan);

        /*-----*/
        $spek_key = array(
            "id"         => array(),
            "jenis"      => array(),
            "kode"       => array(),
            "nama"       => array(),
            "label"      => array(),
            "satuan"     => array(),
            "status"     => array(),
            "keterangan" => array(),
        );
        if ($status == "dikirim") {

            $spek_key_2 = array(
                "salesman_nama"  => array(
                    "label" => "salesman"
                ),
                "customers_nama" => array(
                    "label" => "Konsumen"
                ),
                "dtime"          => array(
                    "label"  => "tanggal",
                    "format" => "formatField_he_format",
                ),
                "transaksi_no"   => array(
                    "label"  => "nomer",
                    "format" => "formatField_he_format",
                ),
            );

            $spek_key = $spek_key + $spek_key_2;
        }

        $member = my_memberships();
        // arrPrintKuning($member);
        $seller_key = array();
        $holding_key = array();
        if (in_array("o_seller", $member)) {
            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );
            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("o_seller_spv", $member)) {
            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );
            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("o_gudang", $member)) {
            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );
            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("o_gudang_spv", $member)) {
            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );
            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("o_gudang_out", $member)) {
            $cabang_id = my_cabang_id();
            $cabang_nama = $cabangs[$cabang_id];
            $seller_key = array(
                "jml_$cabang_id" => array(
                    "label" => $cabang_nama,
                    "style" => "font-weight:bold;font-size:15px;",
                ),
                // "jual_nppn_$cabang_id" => array(),
            );
            foreach ($arrCabang as $cb_id => $item) {
                $seller_key["jml_$cb_id"] = array(
                    "label" => $cabangs[$cb_id],
                    "style" => "font-weight:bold;font-size:15px;",
                );
            }
        }

        elseif (in_array("c_holding", $member)) {
            // arrPrintPink($arrCabang);
            $holding_key = array();
            foreach ($arrCabang as $cb_id => $item) {
                $holding_key["hpp_-1"] = array(
                    "label"      => "HPP",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                );
                // $holding_key["jual_$cb_id"] = array();
                $holding_key["jual_nppn_$cb_id"] = array(
                    // "label" => "harga jual ".$cabangs[$cb_id],
                    "label"      => "harga jual ",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                );
                //                $holding_key["jml_0"] = array(
                //                    "label" => "Jumlah seluruh stok",
                //                    "format" => "formatField_he_format",
                //                    "format_key" => "harga",
                //                    "style" => "font-weight:bold;font-size:15px;",
                //                );
                //                $holding_key["jml_$cb_id"] = array(
                //                    "label" => "Jumlah stok @" . $cabangs[$cb_id],
                //                    "format" => "formatField_he_format",
                //                    "format_key" => "harga",
                //                    "style" => "font-weight:bold;font-size:15px;",
                //                );
            }
            foreach ($cabangs as $cb_id => $item) {
                $holding_key["jml_0"] = array(
                    "label"      => "Jumlah seluruh stok",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
                $holding_key["jml_$cb_id"] = array(
                    "label"      => "Jumlah stok @" . $cabangs[$cb_id],
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("c_gudang", $member)) {
            $holding_key = array();
            //            foreach ($arrCabang as $cb_id => $item) {
            //                $holding_key["hpp_-1"] = array(
            //                    "label" => "HPP",
            //                    "format" => "formatField_he_format",
            //                    "format_key" => "harga",
            //                );
            //                // $holding_key["jual_$cb_id"] = array();
            //                $holding_key["jual_nppn_$cb_id"] = array(
            //                    // "label" => "harga jual ".$cabangs[$cb_id],
            //                    "label" => "harga jual ",
            //                    "format" => "formatField_he_format",
            //                    "format_key" => "harga",
            //                );
            ////                $holding_key["jml_0"] = array(
            ////                    "label" => "Jumlah seluruh stok",
            ////                    "format" => "formatField_he_format",
            ////                    "format_key" => "harga",
            ////                );
            ////                $holding_key["jml_$cb_id"] = array(
            ////                    "label" => "jml stok @" . $cabangs[$cb_id],
            ////                    "format" => "formatField_he_format",
            ////                    "format_key" => "harga",
            ////                );
            //            }
            foreach ($cabangs as $cb_id => $item) {
                $holding_key["jml_0"] = array(
                    "label"      => "Jumlah seluruh stok",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
                $holding_key["jml_$cb_id"] = array(
                    "label"      => "Jumlah stok @" . $cabangs[$cb_id],
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
            }
        }
        elseif (in_array("c_gudang_spv", $member)) {
            $holding_key = array();
            //            foreach ($arrCabang as $cb_id => $item) {
            //                $holding_key["hpp_-1"] = array(
            //                    "label" => "HPP",
            //                    "format" => "formatField_he_format",
            //                    "format_key" => "harga",
            //                );
            //                // $holding_key["jual_$cb_id"] = array();
            //                $holding_key["jual_nppn_$cb_id"] = array(
            //                    // "label" => "harga jual ".$cabangs[$cb_id],
            //                    "label" => "harga jual ",
            //                    "format" => "formatField_he_format",
            //                    "format_key" => "harga",
            //                );
            ////                $holding_key["jml_0"] = array(
            ////                    "label" => "Jumlah seluruh stok",
            ////                    "format" => "formatField_he_format",
            ////                    "format_key" => "harga",
            ////                );
            ////                $holding_key["jml_$cb_id"] = array(
            ////                    "label" => "jml stok @" . $cabangs[$cb_id],
            ////                    "format" => "formatField_he_format",
            ////                    "format_key" => "harga",
            ////                );
            //            }
            foreach ($cabangs as $cb_id => $item) {
                $holding_key["jml_0"] = array(
                    "label"      => "Jumlah seluruh stok",
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
                $holding_key["jml_$cb_id"] = array(
                    "label"      => "Jumlah stok @" . $cabangs[$cb_id],
                    "format"     => "formatField_he_format",
                    "format_key" => "harga",
                    "style"      => "font-weight:bold;font-size:15px;",
                );
            }
        }

        $all_key = $spek_key + $seller_key + $holding_key;
        // arrPrint($all_key);
        $data_gabungan_2 = isset($data_gabungan[$produk_id]) ? $data_gabungan[$produk_id] : array();
        $datas = $data_gabungan_2 + $lastMove;
        $var_show = "";
        $var_show .= "<style type='text/css'>
                .stok-locked{
                    color: #d2d2d2;
                }
            </style>";

        //        $infoSerial
        $var_show .= "<div class='alert alert-info text-center'>";
        // $var_show .= "<div>";
        foreach ($infoSerial as $x => $serial) {
            $var_show .= "<span><b>SN</b>:" . $serial["str"] . "</span>";
            $var_show .= " <div class='text-uppercase text-center font-size-2'>" . $serial["status"] . "</div>";
        }
        // $var_show .= "</div>";
        $var_show .= "</div>";

        // arrPrintHijau($datas);
        // arrPrintHijau($produk_id);
        $spekLockerHold_toshow = $spekLockerHold[$produk_id];
        $spekLockerBooking_toshow = $spekLockerBooking[$produk_id];

        $link_modul = base_url() . "penjualan/";
        $var_show .= "<h3 class='text-uppercase'>produk info</h3>";
        $var_show .= "<table>";
        foreach ($all_key as $ky => $ky_param) {
            $label = isset($ky_param['label']) ? $ky_param['label'] : $ky;

            $nilai = isset($datas[$ky]) ? $datas[$ky] : "--";
            $format_key = isset($ky_param['format_key']) ? $ky_param['format_key'] : $ky;
            $nilai_f = isset($ky_param['format']) ? $ky_param['format']($format_key, $nilai, "5822", "$link_modul") : $nilai;
            $jml_hold_f = "";
            if (isset($spekLockerHold_toshow[$ky])) {
                $jml_hold = $spekLockerHold_toshow[$ky];
                $jml_hold_f .= "<span class='stok-locked'>/ $jml_hold (terkunci oleh transaksi)</span>";
            }
            else {
                $jml_hold = "";
                $jml_hold_f .= "";
            }

            if(isset($spekLockerBooking_toshow[$ky])){
                $jml_hold = $spekLockerHold_toshow[$ky];
                $jml_hold_f .= "<span class='stok-locked'>/ $jml_hold (dibooking)</span>";
            }
            else{
                $jml_hold = "";
                $jml_hold_f .= "";
            }

            $var_show .= "<tr>";
            $var_show .= "<td title='$ky'>$label </td>";
            $var_show .= "<td title='$ky'> : $nilai_f $jml_hold_f</td>";
            $var_show .= "</tr>";
        }
        $var_show .= "</table>";


        echo $var_show;
        if (in_array("p_produksi_spv", $member)) {
            echo $str_kom;
        }
        echo "<script>$('span').removeClass('pull-right');</script>";

        // writeLog("scaner QRcode", "$produk_id", "qrcode", "scaner");
        // echo $var;
    }
}