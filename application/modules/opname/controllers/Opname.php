<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 3:20 PM
 */

class Opname extends CI_Controller
{
    private $y = array(//===sumbu y (folder)
        "mdlName" => "",
        "label" => "",
        "entries" => "",
    );
    private $x = array(//===sumbu x (produk id)
        "mdlName" => "",
        "label" => "",
        "entries" => "",
    );
    private $z = array(//===sumbu z (produk id)
        "mdlName" => "",
        "label" => "",
        "entries" => "",
    );
    private $h = array(
        "label" => "",
        "entries" => "",
    );

    public function getH()
    {
        return $this->h;
    }

    public function setH($h)
    {
        $this->h = $h;
    }

    private $iy;
    private $ix;
    private $iz;

    private $priceConfig = array();

    private $existingValues = array();
    private $q;
    private $selectedID;

    public function getSelectedID()
    {
        return $this->selectedID;
    }

    public function setSelectedID($selectedID)
    {
        $this->selectedID = $selectedID;
    }

    //region gs


    public function getY()
    {
        return $this->y;
    }

    public function setY($y)
    {
        $this->y = $y;
    }

    public function getX()
    {
        return $this->x;
    }

    public function setX($x)
    {
        $this->x = $x;
    }

    public function getZ()
    {
        return $this->z;
    }

    public function setZ($z)
    {
        $this->z = $z;
    }

    public function getIy()
    {
        return $this->iy;
    }

    public function setIy($iy)
    {
        $this->iy = $iy;
    }

    public function getIx()
    {
        return $this->ix;
    }

    public function setIx($ix)
    {
        $this->ix = $ix;
    }

    public function getIz()
    {
        return $this->iz;
    }

    public function setIz($iz)
    {
        $this->iz = $iz;
    }

    public function getPriceConfig()
    {
        return $this->priceConfig;
    }

    public function setPriceConfig($priceConfig)
    {
        $this->priceConfig = $priceConfig;
    }

    public function getExistingValues()
    {
        return $this->existingValues;
    }

    public function setExistingValues($existingValues)
    {
        $this->existingValues = $existingValues;
    }

    public function getQ()
    {
        return $this->q;
    }

    //endregion

    public function setQ($q)
    {
        $this->q = $q;
    }

    public function __construct()
    {
        parent::__construct();
        //        arrPrint($this->uri->segment_array());
        //die();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        $backLink = isset($_GET['backLink']) ? blobDecode($_GET['backLink']) : "";
        $this->q = isset($_GET['q']) ? $_GET['q'] : null;
        $this->selectedID = isset($_GET['sID']) && $_GET['sID'] > 0 ? $_GET['sID'] : null;

        /* ---------------------------------------------------------------------------------
         * untuk ngeload config pada modul
         * ---------------------------------------------------------------------------------*/
        $this->configPath = $configPath = MODUL_CONFIG_PATH;
        // cekHitam($this->configPath);
        $this->load->config($configPath . "coTransaksiUi");
        $this->configUi = $this->config->item("coTransaksiUi");

        $this->load->config($configPath . "coTransaksiCore");
        $this->configCore = $this->config->item("coTransaksiCore");

        $this->divId = "18";
    }

    public function index()
    {
        /* -----------------------------------------------------------------------
         * transaksi gantung
         * -----------------------------------------------------------------------*/
        $this->load->library("Transaksional");
        $ts = new Transaksional();
        $src_ts = $ts->callTransaksiBeforeOpname();

        // $this->load->model("MdlTransaksi");
        // $tr = new MdlTransaksi();

        // $jenis_gantung = $tr->callGantunganTransaksi(true);
        $jml_gantungan = $src_ts['jml'];
        // $jml_gantungan = 0;
        $jenis_gantung = $src_ts['datas'];
        $link_gantungan = $src_ts['link'];

        $modal = "";
        if ($jml_gantungan > 0) {
            // $jenis_gantung = array();

            // matiHere(__LINE__);
            // $src_gantung = $this->cekTransaksiGantung();
            // $link_gantung = MODUL_PATH . get_class($this) . "/cekTransaksiGantung";
            $link_gantung = base_url() . $link_gantungan;
            // cekHere("$link_gantung");
            // $modal = modalDialogBtn("Transaksi yang harus selesai", "$link_gantung");
            $linkAction_2 = base_url();
            $modal = modalDialogKhusus("Transaksi yang harus diselesaikan sebelum stok opname", "$link_gantung", "$linkAction_2");

        }
        // $modal = "";

        // arrPrintHijau($src_gantung);
        // arrPrint(get_class($this));
        // cekBiru($src_gantung);
        $param = $this->uri->segment(4);
        $className = "Mdl" . $this->uri->segment(2);
        $clsDir = "MdlFolder" . $param;

        //        $clsDir = "MdlFolderProduk";
        //cekHere(":: $className :: $clsDir ::");
        // arrPrintKuning(url_segment());
        //region load mdl rpoduk untuk ambil direktory
        $this->load->model("Mdls/" . $clsDir);
        $this->load->model("Mdls/" . ucfirst($className));
        // print_r($this->z['hisPrice']);die();
        $scriptLoad = "<script>$(document).on('ready', function(){
                     // $('#myModal').modal('show');
                     $modal
                     });</script>";

        $p = New Layout("", "", "application/template/default.html");
        $rekName = str_replace(" ", "_", "persediaan " . strtolower($param));
        //        $rekName = str_replace(" ", "_", "persediaan produk");
        //cekHere($rekName);
        $title = "stok opname";
        $attached = isset($_GET['attached']) ? $_GET['attached'] : 0;
        if ($attached == '1') {
            $_SESSION['backLink'] = unserialize(base64_decode($_GET['backLink']));
        }
        $formTarget = base_url() . get_class($this) . "/save/" . $this->uri->segment(1);
        $segment_3 = $this->uri->segment(4);
        //        $formTarget = base_url() . get_class($this) . "/save/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?q=" . $this->q . "&attached=$attached";

        // $fupdateLink = base_url() . get_class($this) . "/view/$segment_3/Folder$segment_3/$rekName/kategori";
        $fupdateLink = MODUL_PATH . "view/$segment_3/Folder$segment_3/$rekName/kategori";
        $btnClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $title per kategori',
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $fupdateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

        $buttonLabel = "save values";
        // $segment_3 = "ProdukCabang";
        $fupdateLink = MODUL_PATH . "view/$segment_3/RakCabang/$rekName/rak";
        $btnClick_2 = "BootstrapDialog.show(
                                   {
                                        title:'Modify $title per rak',
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $fupdateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";
        // arrPrintHijau(url_segment());

        $param = url_segment(4);
        $cabang_id = my_cabang_id();
        if ($cabang_id == "-1") {
            $jenis_trs = array(
                "Produk" => "1119",
                "Supplies" => "1118",
                "ProdukRakitan" => "5559",
            );
        }
        elseif ($cabang_id == "25") {
            $jenis_trs = array(
                // "Produk"        => "3339", // solo
                "Produk" => "2229", // solo
                "Supplies" => "2228",
                "ProdukRakitan" => "5559",
            );
        }
        else {
            $jenis_trs = array(
                "Produk" => "2229",
            );
        }
        $jenis_tr = $jenis_trs[$param];

        $link_undoneList = MODUL_PATH . "Transaksi/viewUndoneItemsIndex/$jenis_tr/?gr=cGVtYmVsaWFu&ohyes=ohno&step=1";
        $data = array(
            "mode" => "index",
            "title" => "stok opname $param",
            "sub_title" => "$param",
            "items" => $this->y['entries'],
            "arrayHeader" => $this->h['entries'],
            "scriptLoad" => $scriptLoad,
            "btnClick" => $btnClick,
            "btnClick_2" => $btnClick_2,
            "link_option" => MODUL_PATH . get_class($this) . "/view/$param/Folder$param/$rekName/kategori",
            "link_undoneList" => $link_undoneList,
        );

        // cekBiru();
        $this->load->view('opname', $data);
        $this->session->errMsg = "";
    }

    public function view()
    {
        // arrPrint($this->uri->segment_Array());
        // $className = "Mdl" . $this->uri->segment(2);
        // $clsDir = "MdlFolder" . $this->uri->segment(4);
        //
        // //        $clsDir = "MdlFolderProduk";
        // //cekHere(":: $className :: $clsDir ::");
        // // arrPrintKuning(url_segment());
        // //region load mdl rpoduk untuk ambil direktory
        // $this->load->model("Mdls/" . $clsDir);
        // $this->load->model("Mdls/" . ucfirst($className));
        // $className = "Mdl" . $this->uri->segment(1);
        // https://san.mayagrahakencana.com/Opname/doPrint/Produk/persediaan_produk
        $param = $this->uri->segment(4);
        $rekening = $this->uri->segment(6);
        $className = "Mdl" . "Opname";
        $actionForm = MODUL_PATH . get_class($this) . "/doPrint" . "/" . $param . "/Folder" . $param . "/" . $rekening;
        // $p = New Layout("", "", "application/template/default.html");
        // cekUngu("$actionForm");
        $p = New Layout("", "", MODUL_TEMPLATE_PATH . "template/default.html");
        //region load mdl prpoduk untuk ambil directory
        $clsName = "Mdl" . $param;
        $clsFolder = "MdlFolder" . $param;
        $this->load->model("Mdls/" . $clsName);
        $this->load->model("Mdls/MdlCabang");
        $this->load->model("Mdls/" . $className);
        $this->load->model("Mdls/" . $clsFolder);
        $pr = new $clsName();
        $cb = new MdlCabang();
        $opn = new $className;
        $fo = new $clsFolder;
        $indexFieldName = "id";
        $selectedFolders = $opn->getFolderListed();
// arrPrint($param);


        /* -----------------------------------------------------------------------------------------
         * opname
         * -----------------------------------------------------------------------------------------*/
        $op_condites = array(
            "jenis" => "$param"
        );
        $op_datas = $this->cekOpnameAktive($op_condites);
        // arrPrintWebs($op_datas);
        $dataOleh = array();
        foreach ($op_datas as $tempOleh) {
            $dataOleh[$tempOleh->cabang_id][$tempOleh->gudang_id] = array(
                "oleh_nama" => $tempOleh->oleh_nama,
                "dtime_start" => $tempOleh->dtime_start,
            );
        }
        // showLast_query("kuning");
        // arrPrintHijau($op_datas);
        $op_gudang_ids = array();
        $op_jenis = array();
        foreach ($op_datas as $op_data) {
            $op_gudang_ids[] = $op_data->gudang_id;
            $op_jenis[$op_data->gudang_id] = $op_data->jenis;
        }
        /*-----------------------------------------------------------------------------------------*/
        // arrPrint($op_jenis);
        // cekHijau("$clsName || $clsFolder");
        //        $pr->addFilter("jenis='folder'");
        //         $this->db->limit(10);
        if ($clsFolder == "MdlRakCabang") {
            $this->load->model("Mdls/MdlProdukCabang");
            $pc = new MdlProdukCabang();
            $srcProdukCabangs = $pc->callProdukDlmRak(my_cabang_id());
            // showLast_query("lime");
            $sum_produkCb = $srcProdukCabangs["sum"];
            // arrPrint($sum_produkCb);

            $this->db->where("cabang_id", my_cabang_id());
        }
        $this->db->order_by("nama", "asc");
        $dataValue = $fo->lookupAll()->result();
        // showLast_query("biru");
        // if ((my_cabang_id() == 25) && ($param == "Produk")) {
        if (($param == "Produk")) {

            $this->load->model("Mdls/MdlFolderProdukRakitan");
            $fr = new MdlFolderProdukRakitan();
            $dataValuer = $fr->lookupAll()->result();
            // showLast_query("merah");
            // arrPrint($dataValuer);

            $dataValue = array_merge($dataValuer, $dataValue);
        }
        $jml_data = sizeof($dataValue);
        // showLast_query("lime", $jml_data);
        $result = array();
        foreach ($dataValue as $i => $dataTemp) {
            $temp = array();
            foreach ($selectedFolders as $kolom => $alias) {
                $temp[$kolom] = $dataTemp->$kolom;
            }
            $result[] = $temp;

        }
        // yang tidak punya kategory
        $result[] = array(
            "id" => 0,
            "nama" => "NON CATEGORY",
        );
        //mati_disini("cek model");
        //region cabang
        $cb->addFilter("id='" . $this->session->login['cabang_id'] . "'");
        $arrCabang = $cb->lookupAll()->result();
        //        arrPRint($arrCabang);
        // $tik_cabang = "<div class=''>Pilih Cabang</div>";
        $tik_cabang = "<div class='funkyradio'>";
        if (sizeof($arrCabang) > 0) {
            foreach ($arrCabang as $i => $d_cabang) {
                $c_id = $d_cabang->id;
                $c_nama = $d_cabang->nama;


                $tik_cabang .= "<div class='funkyradio-success'>
            <input type='hidden' name='c_nama[$c_id]' value='$c_nama'>
            <input type='checkbox' name='cabang[]' id='checkbox_$c_id' checked value='$c_id'>
            <label for='checkbox_$c_id' class='no-margin no-padding'>$c_nama</label>
        </div>";

            }
        }
        $tik_cabang .= "</div>";
        //endregion
        $cabang_id = my_cabang_id();
        /* -------------gudang default-----------         */
        if ($cabang_id == "-1") {
            $this->load->model("Mdls/MdlGudangDefault_center");
            $gdg = new MdlGudangDefault_center();

            $gd_condites = array(
                "cabang_id" => $cabang_id,
            );
            $this->db->where($gd_condites);
            $src_gudang = $gdg->lookupAll()->result();

            // arrPrint($src_gudang);
            $defGd = array();
            foreach ($src_gudang as $item) {
                $defGd["id"] = $item->id;
                $defGd["nama"] = $item->nama;
            }
        }
        else {
            $this->load->model("Mdls/MdlGudangDefault");
            $gdg = new MdlGudangDefault();

            $gd_condites = array(
                "cabang_id" => $cabang_id,
            );
            $this->db->where($gd_condites);
            $src_gudang = $gdg->lookupAll()->result();
            // showLast_query("pink");
            // arrPrint($src_gudang);
            $defGd = array();
            foreach ($src_gudang as $item) {

                $arrGdDefault[$item->cabang_id] = $item->id;
                if ($cabang_id == $item->cabang_id) {
                    $defGd["id"] = $item->id;
                    $defGd["nama"] = $item->nama;
                }
            }
        }
        $gdDefault[0] = (object)$defGd;
        // arrPrintHijau($gdDefault);
        /*
         * gudang rusak
         */
        $this->load->model("Mdls/MdlGudang");
        $gd = new MdlGudang();
        $gd_condites = array(
            "cabang_id" => $cabang_id,
        );
        $this->db->where($gd_condites);
        $src_gudang = $gd->lookupAll()->result();
        // showLast_query("kuning");
        foreach ($src_gudang as $item) {
            // $cbId = $item->cabang_id;
            if ($cabang_id == $item->cabang_id) {
                $defGd["id"] = $item->id;
                $defGd["nama"] = $item->nama;
            }
            // $gdDefault[] = (object)$defGd;
        }
        if (sizeof($src_gudang) > 0) {
            $gdDefault[1] = (object)$defGd;
        }

        // $campur = ($src_gudang + $gdDefault);
        // arrPrintPink($gdDefault);
        $src_gudang_all = $gdDefault;
        $jml_gudang = sizeof($src_gudang_all);
        $jml_download = sizeof($op_gudang_ids);

        // cekHere("$jml_gudang == $jml_download");
        /*----------disable download------------*/
        $btn_download_disabled = "";
        $str_download_disabled = "";
        if ($jml_gudang == $jml_download) {
            $btn_download_disabled = "disabled";
            $str_download_disabled = "<span style='font-size:15px;'>Sesi opname sedang berlangsung, sudah tidak bisa download. Silahkan lihat History download jika akan download ulang</span>";
        }

        // arrPrintHijau($src_gudang_all);
        $tik_gudang = "";
        $tik_gudang .= "<div class='raw'>";
        $tik_gudang .= "<div class='funkyradio'>";
        foreach ($src_gudang_all as $d_gudang) {
            $g_id = $d_gudang->id;
            $g_nama = $d_gudang->nama;

            $daftarGudang[$g_id] = $g_nama;

            if (in_array($g_id, $op_gudang_ids) && ($op_jenis[$g_id] == $param)) {
                $preTitle = $dataOleh[$cabang_id][$g_id]["oleh_nama"];
                $preTitleDate = $dataOleh[$cabang_id][$g_id]["dtime_start"];
                $disabled_btn = "disabled";
                $disabled_bg_color = "style='background-color: #f9c9d8;'";
                $disabled_title = "data opname sudah didownload oleh $preTitle jam $preTitleDate";
            }

            else {
                $disabled_btn = "";
                $disabled_bg_color = "";
                $disabled_title = "";
            }


            $tik_gudang .= "<div class='col-md-4'>";
            $tik_gudang .= "<div class='funkyradio-success'>
                    <input type='hidden' name='g_nama[$g_id]' value='$g_nama'>
                    <input type='radio' name='gudang[]' required id='checkgud_$g_id' $disabled_btn value='$g_id'>
                    <label for='checkgud_$g_id' class='no-margin no-padding' title='$disabled_title' $disabled_bg_color>$g_nama</label>
                </div>";
            $tik_gudang .= "</div>";
            // $tik_gudang .= "</div>";
        }
        $tik_gudang .= "</div>";
        $tik_gudang .= "</div>";
        // ------------------------------supplies
        // $tik_gudang .= "<div>";
        // $tik_gudang .= "supplies";
        // $tik_gudang .= "</div>";

        // region pilih kategori folder
        $jml_folder = count($result);
        // cekBiru($result);
        // $tik_folder = "<div class=''>Pilih kategori</div>";

        // $tik_folder .= "<div class='funkyradio'>";
        $jml_kolom_tampilan = 4;
        $jml_per_kolom = (int)($jml_folder / $jml_kolom_tampilan);
        $lebar_kolom = floor(12 / $jml_kolom_tampilan);

        $tik_folder_ganjil = "";
        $tik_folders = array();
        $filterSelect = array();
        $no = 0;
        foreach ($result as $i => $dataTemp) {
            $no++;
            $f_id = $dataTemp['id'];
            $f_nama = $dataTemp['nama'];
            $c_sum = isset($sum_produkCb[$f_id]) ? $sum_produkCb[$f_id] : "-";
            $f_sum = $c_sum > 0 ? "<i class='badge badge-info pull-right' style='margin-top: 6px;'>$c_sum</i>" : "";
            // for ($i = 1; $i <= $jml_kolom_tampilan; $i++) {

            $tik_folders[] = "<div class='filterbucks $f_nama funkyradio-success'>
                <input type='checkbox' name='folder[]' f_nama='$f_nama' id='checkbox_$f_id' checked value='$f_id'/>
                <label for='checkbox_$f_id' class='no-margin no-padding'>$f_nama $f_sum</label>
            </div>";
            // }
            $filterSelect[] = $f_nama;

        }
        // arsort($rowDatas);
        // cekBiru(sizeof($tik_folders) . " $jml_per_kolom");
        // cekBiru($tik_folders);
        if ($jml_per_kolom > 0) {

            $rowDatas = array_chunk($tik_folders, $jml_per_kolom);
        }
        else {
            $rowDatas[] = $tik_folders;
            $jml_kolom_tampilan = 1;
        }
        // $tik_folder .= "</div>";
        // endregion pilih kategori folder

        // arrPrintPink($tik_folder_ganjils);
        // arrPrintPink($xxx);
        // arrPrintPink($rowDatas);
        // matiHere();
        //region uploader file
        $hsl2Action = MODUL_PATH . get_class($this) . "/doUpload/" . $param;
        $linkUploader = base_url() . get_class($this) . "/formFileUploader";
        // ceklime($hsl2Action);
        $upl = "<input type='file' name='userfile' class='form-control'>";
        $modal_isi2 = "";
        $modal_isi2 .= "<hr>";
        $modal_isi2 .= "<div class='row margin-top-10'>";
        $modal_isi2 .= "<div class='col-md-6'><button type='button' class='btn btn-default' data-dismiss='modal'>&times; Close</button></div>";

        $modal_isi2 .= "<div class='col-md-6'>";
        $modal_isi2 .= "<form method='post' id='upload_file' enctype='multipart/form-data' action='$hsl2Action' target='result'>";
        //        $modal_isi2 .= form_open_multipart('Opname/doUpload');
        $modal_isi2 .= "<div class='input-group input-group-sm' sstyle='margin-top:15px;'>";
        $modal_isi2 .= "<span class='input-group-addon'>Upload file Excel</span>";
        $modal_isi2 .= "</span>";
        $modal_isi2 .= $upl;

        $modal_isi2 .= "<span class='input-group-btn'>";
        $modal_isi2 .= "<button type='button' id='btnSubmitUploadExcel' name='upload' value='upload' class='btn btn-success'><i class='fa fa-upload'>&nbsp;</i>Upload</button>&nbsp;";
        $modal_isi2 .= "</span>";

        $modal_isi2 .= "</div>";
        $modal_isi2 .= "</form>";
        $modal_isi2 .= "<script>
            $('#btnSubmitUploadExcel').off('click').on('click', function(e) {
                var fileInput = $('input[name=\"userfile\"]');
                if (!fileInput.val()) {
                    top.swal('Pilih File', 'Silakan pilih file Excel terlebih dahulu sebelum menekan tombol upload.', 'warning');
                    return false;
                }
                top.swal({
                    title: 'Sedang Mengunggah File...',
                    text: 'Sistem sedang mengunggah file ke server. Mohon tunggu...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });
                if (top.swal.showLoading) { top.swal.showLoading(); }
                else if (top.swal.enableLoading) { top.swal.enableLoading(); }
                document.getElementById('upload_file').submit();
            });
        </script>";
        $modal_isi2 .= "</div>";
        $modal_isi2 .= "</div>";
        //endregion

        $modal_isi = "<style type='text/css'>
                        .funkyradio {
                            margin-bottom: 3px !important;
                        }
                    </style>";
        $modal_isi .= "<div class='row'>";

        //        $modal_isi .= "<div class='col-md-12'><h3>Pilih kategori produk</h3> <span class='float-right'> <input list='browsers' type='text' class='form-control' id='form_search' placeholder='filter '>  </span></div>";
        $modal_isi .= "<div class='col-md-12'><h4 class='no-padding no-margin'>Pilih kategori $param</h4> <span class='float-right'>";
        // $modal_isi .= "<input list='browserss' type='text' class='form-control' id='form_search2' placeholder='filter '>  </span></div>";
        $modal_isi .= "<div class='clearfix'>&nbsp;</div>";

        $modal_isi .= "<div id='data_kosong' class='col-md-12 text-center text-bold text-red hidden'>================ TIDAK ADA DATA ================</div>";

        $modal_isi .= "<datalist id='browsers'>";
        foreach ($filterSelect as $k => $val) {
            $modal_isi .= "<option value='$val'>";
        }


        $modal_isi .= "</datalist>";

        $modal_isi .= "
        
        <select class=\"hidden filter1\" data-fbdeep=\"0\">
	<option data-fbflush=\"true\" value=\"none\">
		Select
	</option>";

        if (sizeof($filterSelect) > 0) {
            foreach ($filterSelect as $k => $val) {
                $modal_isi .= "
        <option value='$val'>$val</option>
        ";
            }
        }


        $modal_isi .= "</select>
<!-- <select class=\"hidden filter1\" data-fbdeep=\"1\">
	<option data-fbflush=\"true\" value=\"none\">
		Select
	</option>
	<option value=\"Speed\">
		Speed
	</option>
	<option value=\"Comfort\">
		Comfort
	</option>
</select> --> 

        ";
        for ($i = 0; $i < $jml_kolom_tampilan; $i++) {
            // cekKuning($i);

            if ((is_array($rowDatas[$i]) > 0)) {
                // cekBiru($rowDatas[$i]);

                foreach ($rowDatas[$i] as $data) {

                    $modal_isi .= "<div class='col-md-$lebar_kolom'>";
                    $modal_isi .= "<div class='funkyradio'>";
                    // $modal_isi .= "$tik_folder_ganjil";
                    $modal_isi .= $data;
                    $modal_isi .= "</div>";
                    $modal_isi .= "</div>";
                }
            }
        }
        $modal_isi = "";
        // ---------------------------------------------------------------------

        $modal_isi .= "<div class='col-md-12'>";
        // $modal_isi .= "<h4 class='margin-top-10'>Tuliskan nama $param untuk menerapkan filter</h4>";
        // $modal_isi .= "<input type='text' name='cari' class='form-control' placeholder='key words'>";

        // $modal_isi .= "<hr>";
        $modal_isi .= "<h4 class='margin-top-10'>Lokasi stok opname</h4>";
        $modal_isi .= "$tik_cabang";
        // $modal_isi .= "</div>";

        $modal_isi .= "<h4 class='margin-top-10'>Lokasi gudang <small class='text-red'>wajib dipilih</small></h4>";
        $modal_isi .= "$tik_gudang";
        $modal_isi .= "<div style='font-size: 2em;color: red;'><span style='font-size: 14pt;color: darkgreen;'>Untuk bisa download excel</span> lokasi gudang WAJIB dipilih</div>";
        $modal_isi .= "</div>";


        //-------------------------------------
        $opname_condites = array(
            "cabang_id=" . my_cabang_id(),
            "jenis=$param",
        );
        $historyOpname = $this->historyDownloadOpname($opname_condites);
        $hisOpname = "<div class='ccol-md-12'>";
        $hisOpname .= "<h4 class='margin-top-10'>History Download <small class='text-red'>Opname " . my_cabang_nama() . "</small></h4>";
        $hisOpname .= "<table class='table table-bordered table-hover' style='width:50%;margin-top:-5px;'>";
        $hisOpname .= "<tr >";
        $hisOpname .= "<th>No.</th>";
        $hisOpname .= "<th>Tanggal</th>";
        $hisOpname .= "<th>Gudang</th>";
        $hisOpname .= "<th>Oleh</th>";
        $hisOpname .= "<th>Download ulang excel</th>";
        $hisOpname .= "</tr>";
        if (sizeof($historyOpname) > 0) {
            $no = 0;
            foreach ($historyOpname as $hisSpec) {
                $id_tabel = $hisSpec->id;
                $dtime = $hisSpec->dtime_start;
                $oleh_nama = $hisSpec->oleh_nama;
                $gudang_id = $hisSpec->gudang_id;
                $gudang_nama = isset($daftarGudang[$gudang_id]) ? $daftarGudang[$gudang_id] : "-";
                $cabang_id = $hisSpec->cabang_id;
                //                $link = MODUL_PATH;
                $link = MODUL_PATH . get_class() . "/reDownloadXls/" . $id_tabel . "/" . $this->uri->segment(4);
                $link_redownload = "<button type='button' class='btn btn-small' 
                    onclick=\"window . open('$link', '_blank')\"><span class='fa fa-download'></span>
                </button>";

                $no++;
                $hisOpname .= "<tr>";
                $hisOpname .= "<td>$no</td>";
                $hisOpname .= "<td>" . formatField_he_format("dtime", $dtime) . "</td>";
                $hisOpname .= "<td>" . formatField_he_format("gudang_nama", $gudang_nama) . "</td>";
                $hisOpname .= "<td>" . formatField_he_format("nama", $oleh_nama) . "</td>";
                $hisOpname .= "<td>$link_redownload</td>";
                $hisOpname .= "</tr>";
            }
        }
        $hisOpname .= "</table>";
        $hisOpname .= "</div>";


        /* -----------------------------------------
         * button ada disini ya.......................
         * -------------------------------------*/

        $modal_isi .= "<div class='col-md-12' style='margin-top:10px;'>";


        // $modal_isi .= "<span class='pull-left'><button type='button' class='btn btn-default' data-dismiss='modal'>&times; Close</button></span>";
        $modal_isi .= "<span class='pull-right'>";
        if ($btn_download_disabled) {
            $modal_isi .= "<button type='button' class='btn btn-link text-red'>$str_download_disabled</button> ";
        }
        $modal_isi .= "<button type='submit' name='excel' id='btnSubmitDownloadExcel' title='download data stok opname' $btn_download_disabled value='download' class='btn btn-success'><i class='fa  fa-file-excel-o'>&nbsp;</i>Download Excel</button>&nbsp;";
        // $modal_isi .= "</span>";
        $modal_isi .= "<button type='submit' class='btn btn-info'><i class='fa fa-print'>&nbsp;</i>Print</button>&nbsp;";
        $modal_isi .= "</span>";
        $modal_isi .= "</div>";

        // $modal_isi_2 = "<div class='col-md-6' style='margin-top:10px;'>";
        // $modal_isi_2 .= "<div class='input-group input-group-sm'>";
        // $modal_isi_2 .= $modal_isi2;
        // $modal_isi_2 .= "</div>";
        // $modal_isi_2 .= "</div>";

        $modal_isi .= "</div>";

        $modal_isi .= "<div class='clearfix'></div>";
        $strMain = "";
        $strMain .= "<div id='progress-bar' style='width: 100%; height: 20px; background-color: #f3f3f3; display: none; overflow: hidden; margin-top: 10px; border-radius: 4px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);'>
            <div id='progress-fill' style='width: 0%; height: 20px; background-color: #22c55e; transition: width 0.2s ease; text-align: center; color: #fff; font-size: 11px; font-weight: bold; line-height: 20px;' class='pull-left'>DOWNLOAD DATA</div>
        </div>";
        // $strMain .= "<div class='border-cek overflow-h'>";
        $strMain .= "<form id='download-form' method='post' action='$actionForm' target='result'>";
        $strMain .= $modal_isi;
        // $strMain .= "$modal_isi_2";
        $strMain .= "</form>";
        $strMain .= $modal_isi2;
        $strMain .= $hisOpname;

        // $strMain .= "</div>";
        $strMain .= "<script>
            function releaseDownloadHoldon() {
                if (typeof top.close_holdon === 'function') { top.close_holdon(); }
                if (typeof close_holdon === 'function') { close_holdon(); }
                if (typeof top.HoldOn !== 'undefined' && typeof top.HoldOn.close === 'function') { top.HoldOn.close(); }
                if (typeof HoldOn !== 'undefined' && typeof HoldOn.close === 'function') { HoldOn.close(); }
            }

            $('#btnSubmitDownloadExcel').off('click').on('click', function() {
                // Tutup HoldOn secara proaktif setelah browser mulai menerima file stream
                setTimeout(releaseDownloadHoldon, 800);
                setTimeout(releaseDownloadHoldon, 1500);
                setTimeout(releaseDownloadHoldon, 3000);
            });

            document.getElementById(\"download-form\").addEventListener(\"submit\", function () {
                // Tampilkan progress bar lokal
                const progressBar = document.getElementById(\"progress-bar\");
                const progressFill = document.getElementById(\"progress-fill\");
            
                if (progressBar && progressFill) {
                    progressBar.style.display = \"block\";
                    progressFill.style.width = \"0%\";
                    let progress = 0;
                    let interval = setInterval(function () {
                        progress += 20;
                        progressFill.style.width = progress + \"%\";
                        progressFill.innerText = 'MEMBUAT FILE ' + progress + '%';
                        if (progress >= 100) {
                            clearInterval(interval);
                            setTimeout(function () {
                                progressBar.style.display = \"none\";
                                releaseDownloadHoldon();
                            }, 500);
                        }
                    }, 150);
                }

                setTimeout(releaseDownloadHoldon, 1000);
                setTimeout(releaseDownloadHoldon, 2000);
                setTimeout(releaseDownloadHoldon, 4000);
            });
        </script>";
        $strMain .= "<script>
//jQuery(document).ready(function(){
// 	setTimeout( function(){
// 	    top.$('.filter1').filterbucks();
// 	}, 1000)
//	
//     $('#form_search').on('keyup', delay_v2(function(){
//        
//         if( this.value == '' ){
//             $('.filter1').val('none').trigger('change');
//         }
//         else{
//             var search = this.value.toUpperCase();
//             $('.filter1').val( search ).trigger('change');
//         }
//         console.log('kamu telah mengetik: ' + this.value);
//     },200))
// 	top.console.log('inisiasi filterbuck sukses');
// //});
//
// initFormSearch();

</script>";


        echo "$strMain";
        die();
    }

    public function save()
    {
        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>processing upload data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,

        );
        echo swalAlert($arrAlert);
        //        $segments = $this->uri->segment_array();
        //        arrPrint($segments);
        //        cekMerah("sampe sini");

        $className = "Mdl" . $this->uri->segment(1);
        $ctrlName = $this->uri->segment(1);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $f = new MyForm($o, "editProcess");

        //        arrPrint($f->isInputValid());
        if ($f->isInputValid()) {
            $this->db->trans_start();
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (isset($spec['inputType'])) {
                    switch ($spec['inputType']) {
                        case "checkbox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            //$data[$fName] = date("Y-m-d H:i:s");
                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "password":
                            $data[$fName] = md5($this->input->post($fName));
                            break;
                        case "file":
                            //                            arrPrint($_FILES);
                            if ($_FILES[$fName]['size'] > 0) {
                                //                                cekBiru($fName);
                                $image["image"] = file_get_contents($_FILES[$fName]['tmp_name']);
                                $data[$fName] = base64_encode(serialize($image));
                            }
                            else {
                                //                                cekHEre("no image");
                                $data[$fName] = "";
                            }

                            //                            $filesss = file_get_contents($_FILES[$fName]['tmp_name']);
                            //                            echo base64_decode($filesss);
                            break;
                        case "hidden":
                            //                            switch ($spec['type']) {
                            //                                case "date":
                            //                                    $data[$fName] = date("Y-m-d");
                            //                                    break;
                            //                                case "datetime":
                            //                                    $data[$fName] = date("Y-m-d H:i:s");
                            //                                    break;
                            //                                case "timestamp":
                            //                                    $data[$fName] = date("Y-m-d H:i:s");
                            //                                    break;
                            //                                default:
                            //                                    $data[$fName] = $this->input->post($fName);
                            //                                    break;
                            //                            }

                            $data[$fName] = $this->input->post($fName);
                            break;

                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "int":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");
                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }
            }
            $newImages = blobDecode($data['files']);
            $imagesBlob["files"] = base64_encode($newImages['image']);
            $dataLast = array_replace($data, $imagesBlob);

            $insertID = $o->addData(array_filter($dataLast), $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            $this->session->errMsg = "Data contents have been saved";
            //            cekMerah($this->db->last_query());


            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => $dataLast['parent_id'],
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                //                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                //                "old_content_intext" => print_r($tmpOrig, true),
                "new_content" => base64_encode(serialize($dataLast)),
                "new_content_intext" => print_r($data, true),
                "label" => $data['jenis'],
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));


            matiHere("comat camit " . __LINE__);

            $this->db->trans_complete();
            echo "<script>top.location.reload();</script>";
        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in $err[fieldLabel]:  $err[errMsg]";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
        //arrPrint($_POST);
        //        arrPrint($_FILES);
        //        die();
    }

    public function delete()
    {

        $className = "Mdl" . $this->uri->segment(1);
        $ctrlName = $this->uri->segment(1);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $this->selectedID = $this->uri->segment(3);
        if ($this->selectedID != null) {
            $o->addFilter("id='" . $this->selectedID . "'");
        }
        if ($this->q != null) {
            $tmpY = $o->lookupByKeyword($this->q)->result();
        }
        else {
            $tmpY = $o->lookupAll()->result();
        }
        //        cekHere($this->db->last_query());
        // arrPrint($this->uri->segment_array());
        //        arrPrint($tmpY);
        $where = array(
            "id" => $this->selectedID,
        );
        $this->db->trans_start();
        //region history data
        $this->load->model("Mdls/" . "MdlDataHistory");
        $hTmp = new MdlDataHistory();
        $tmpHData = array(
            "orig_id" => $this->selectedID,
            "mdl_name" => $className,
            "mdl_label" => get_class($this),
            "data_id" => $tmpY[0]->parent_id,
            //                "old_content"        => base64_encode(serialize((array)$tmpOrig)),
            //                "old_content_intext" => print_r($tmpOrig, true),
            "new_content" => blobEncode($tmpY),
            "new_content_intext" => print_r($tmpY, true),
            "label" => "images",
            "oleh_id" => $this->session->login['id'],
            "oleh_name" => $this->session->login['nama'],
            "trash" => "1",
        );
        $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        //        cekHere($this->db->last_query());
        //endregion

        //region hapus dari db
        $delData = $o->deleteData($where) or die(lgShowError("Gagal delete images", __FILE__));
        $this->session->errMsg = "Data contents have been deleted";
        //cekBiru($this->db->last_query());
        //region cek daata sukses hapus atau tidak
        if ($delData) {
            $o->addFilter("id='" . $this->selectedID . "'");
            $tmpX = $o->lookupAll()->result();
            //            cekHijau($this->db->last_query());


            //        die();
            if (sizeof($tmpX) > 0) {
                //gagal dihapus brooo
                $errMsg = "Error on delete images ";
                //                matiHEre("gagalll");
                echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                die(lgShowAlert($errMsg));

            }
            else {
                //cekHijau(base_url() . get_class($this) . "/view/".$ctrlName);

                //                echo lgShowSuccess("ok");
                //                die("<script>
                //                // location.href='https://google.com';
                //                // alert('masuk');
                //                    window.document.getElementById('result2').src +='';
                //                //     document.getElementById('id').src += '';
                //                </script>");


                // die (redirecResult("http://google.com"));
                // die (refreshResult());

                //                matiHEre("ayo direload broo");
                $this->db->trans_complete();
                topReload();//pikir keri broo

                //                $key = isset($_GET['k']) ? $_GET['k'] : "";
                //                redirect(base_url() . get_class($this) . "/view/$ctrlName/?k=$key");
                //                die();
            }
        }


    }

    /* --------------------------------------------------------------------------------
     * download excel
     * --------------------------------------------------------------------------------*/
    public function doPrint()
    {
        // arrPrint($this->uri->segment_array());
        // arrPrintKuning($_POST);
        $param = $this->uri->segment(4);
        $clsName = "Mdl" . $this->uri->segment(2);
        $jnProdMdl = "Mdl" . $param;
        $conv_rekening = array(
            "ProdukRakitan" => "1010030070",
            "Produk" => "1010030030",
            "Supplies" => "1010030010",
        );
        //        $rekName = urldecode($this->uri->segment(4));
        // $rekName = str_replace("_", " ", $this->uri->segment(6));
        $rekName = $conv_rekening[$param];
        $mdlCabang = "MdlCabang";

        $cb_id = isset($_POST['cabang']) ? $_POST['cabang'] : array();
        // arrPrintHijau($_POST);
        if (sizeof($cb_id) > 0) {
            $cid_list = "(";
            foreach ($cb_id as $c_id) {
                $cid_list .= "'$c_id',";
            }
            $cid_list = rtrim($cid_list, ",");
            $cid_list .= ")";

            $cabang_id = $_POST["cabang"][0];
        }
        if (isset($_POST['folder']) && (sizeof($_POST['folder']) > 0)) {
            $folder_list = "(";
            //            $_POST['folder'][] = 0;
            foreach ($_POST['folder'] as $i => $folder) {
                $folder_list .= "'$folder',";
            }
            $folder_list = rtrim($folder_list, ",");
            $folder_list .= ")";
        }

        $gd_id = isset($_POST['gudang']) ? $_POST['gudang'][0] : matiHere("gudang WAJIB ditentukan");

        switch ($param) {
            case "Produk":
                $jnProdMdl = "Mdl" . $param . "2";
                $addFilter = "jenis in ('item', 'item_rakitan')";
                $arrWhere2 = array(
                    "jenis" => "item",
                    "kategori_nama<>" => "jasa",
                );
                break;
            case "Supplies":
                $jnProdMdl = "Mdl" . $param;
                $addFilter = "";
                $arrWhere2 = array(
                    // "jenis"           => "item",
                    // "kategori_nama<>" => "jasa",
                );
                break;
            default:
                $jnProdMdl = $jnProdMdl;
                $arrWhere2 = array(
                    // "jenis"           => "item",
                    // "kategori_nama<>" => "jasa",
                );
                break;
        }
        //cekHere($this->uri->segment(3));
        //cekHere($jnProdMdl);
        //mati_disini();
        //        $this->load->helper("heOpname");
        $this->load->model("Mdls/" . $clsName);
        $this->load->model("Mdls/" . $jnProdMdl);
        $this->load->model("Mdls/" . $mdlCabang);
        $needList = isset($this->config->item('heOpname')['colom']['listNeed']) ? $this->config->item('heOpname')['colom']['listNeed'] : array();
        //        arrPrint($needList);
        $cb = new $mdlCabang;
        $o = new $clsName;
        $pr = new $jnProdMdl;
        $elementData = $o->getElementsData();
        $arrCabang = $cb->lookupAll()->result();
        // showLast_query("merah");
        $arrCabangName = array();
        foreach ($arrCabang as $cabData) {
            $arrCabangName[$cabData->id] = $cabData->nama;
        }

        // START OF COMPLETE REPEATED LOGIC
        if (isset($_POST['cari']) && ($_POST['cari'])) {
            $pr->addFilter("$addFilter");
            $pr->setSearch($_POST['cari']);
            $produkList = $pr->lookupLimitedBySelected()->result();
        }
        else {
            $this->db->where($arrWhere2);
            $this->db->where_not_in($arrWhere2);
            $this->db->order_by("supplier_nama,kategori_id", "asc");
            $produkList = $pr->lookupAll()->result();
        }

        //region cari stok dari leger
        if ($param == "ProdukRakitan") {
            $getName = "Produk";
            $rekName = "1010030070";
        }
        elseif ($param == "Supplies") {
            $getName = "Supplies";
            $rekName = "1010030010";
        }
        else {
            $getName = "Produk";
            $rekName = "1010030030";
        }

        $tempPersediaan = array();
        $cacheTable = ($param == "Supplies") ? "_rek_pembantu_supplies_cache" : "_rek_pembantu_produk_cache";
        $this->db->select("extern_id, cabang_id, qty_debet");
        $this->db->where(array(
            "periode"   => "forever",
            "rekening"  => $rekName,
            "gudang_id" => $gd_id,
        ));
        if (sizeof($cb_id) > 0) {
            $this->db->where_in("cabang_id", $cb_id);
        }
        $tmpBalances = $this->db->get($cacheTable)->result();
        if (sizeof($tmpBalances) > 0) {
            foreach ($tmpBalances as $valueX) {
                $tempPersediaan[$valueX->extern_id][$valueX->cabang_id] = $valueX->qty_debet;
            }
        }

        /*--DOWNLOAD EXCEL----*/
        if (isset($_POST['excel'])) {
            $dateNow = dtimeNow("Y-m-d-H-s");
            $this->load->library('Excel');
            $ex = new Excel();
            $urut = 0;

            $headers_0 = array(
                "id" => array(
                    "label" => "pID",
                    "type" => "integer",
                ),
                "kode" => array(
                    "label" => "sku",
                    "type" => "string",
                ),
                "nama" => array(
                    "label" => "produk",
                    "type" => "string",
                ),
                "outdoor_nama" => array(
                    "label" => "indoor",
                    "type" => "string",
                ),
                "indoor_nama_1" => array(
                    "label" => "outdoor",
                    "type" => "string",
                ),
                "kategori_nama" => array(
                    "label" => "kategori",
                    "type" => "string",
                ),
                "supplier_nama" => array(
                    "label" => "supplier",
                    "type" => "string",
                ),
            );
            foreach ($cb_id as $item) {
                $cabNama = isset($arrCabangName[$item]) ? $arrCabangName[$item] : "Cabang $item";
                $headers_1['stok_' . $item] = array(
                    "label" => "stok (buku) $cabNama",
                    "type" => "integer",
                );
            }
            $headers_2 = array(
                "riil" => array(
                    "label" => "stok riil",
                    "type" => "integer",
                ),
                "serial" => array(
                    "label" => "serial number",
                    "type" => "string",
                ),
            );
            $headers_3 = array(
                "cid" => array(
                    "label" => "cID",
                    "type" => "integer",
                ),
                "gid" => array(
                    "label" => "gID",
                    "type" => "integer",
                ),
                "jproduk" => array(
                    "label" => "jenis",
                    "type" => "string",
                ),
            );

            $headers = $headers_3 + $headers_0 + $headers_1 + $headers_2;
            $datas = array();
            foreach ($produkList as $index_0 => $xDetails) {
                $urut++;
                $code = array();
                foreach ($headers_0 as $kolom => $header) {
                    $code[$kolom] = isset($xDetails->$kolom) ? $xDetails->$kolom : "";
                }

                foreach ($cb_id as $cabId) {
                    $stok = isset($tempPersediaan[$xDetails->id][$cabId]) ? $tempPersediaan[$xDetails->id][$cabId] : 0;
                    $serialProduk = "-";

                    $code["stok_" . $cabId] = $stok;
                    $code["riil"] = 0;
                    $code["cid"] = $cabId;
                    $code["gid"] = $gd_id;
                    $code["jproduk"] = $param;
                    $code["serial"] = $serialProduk;
                }

                $datas[] = (object)$code;
            }

            $ex->setTitleFile("Inventory $param $dateNow");
            $ex->setDatas($datas);
            $ex->setHeaders($headers);

            /* ---------------------------------------------------------------
             * log sesi opname & data awal
             ---------------------------------------------------------------*/
            $op_condites = array(
                "gudang_id" => $gd_id,
                "jenis" => $param,
            );
            $op_aktives = $this->cekOpnameAktive($op_condites);

            $this->load->model("Mdls/MdlDashboardOpname");
            $dop = new MdlDashboardOpname();

            $opname_session = $this->cekOpnameAktivSession();
            if ($opname_session == 0) {
                $opname_session = dtimeNow("Ymd");
            }

            if (sizeof($op_aktives) == 0) {
                $op_datas = array(
                    "session_opname" => $opname_session,
                    "dtime_start"    => dtimeNow(),
                    "cabang_id"      => my_cabang_id(),
                    "gudang_id"      => $gd_id,
                    "oleh_id"        => my_id(),
                    "oleh_nama"      => my_name(),
                    "status"         => 1,
                    "jenis"          => $param,
                );
                $op_last_id = $dop->addData($op_datas);

                $batchDataLogs = array();
                foreach ($datas as $data) {
                    $k_stok = "stok_" . $data->cid;
                    $batchDataLogs[] = array(
                        "session_opname"      => $opname_session,
                        "jenis"               => $param,
                        "produk_id"           => $data->id,
                        "produk_nama"         => isset($data->nama) ? $data->nama : "",
                        "cabang_id"           => $data->cid,
                        "gudang_id"           => $data->gid,
                        "jml_stok_buku"       => isset($data->$k_stok) ? $data->$k_stok : 0,
                        "serial"              => $data->serial,
                        "kategori_nama"       => isset($data->kategori_nama) ? $data->kategori_nama : "",
                        "supplier_nama"       => isset($data->supplier_nama) ? $data->supplier_nama : "",
                        "dashboard_opname_id" => $op_last_id,
                    );

                    if (sizeof($batchDataLogs) >= 500) {
                        $this->db->insert_batch('dashboard_opname_data', $batchDataLogs);
                        $batchDataLogs = array();
                    }
                }
                if (sizeof($batchDataLogs) > 0) {
                    $this->db->insert_batch('dashboard_opname_data', $batchDataLogs);
                }
            }

            return $ex->writer();
        }
        // END OF COMPLETE REPEATED LOGIC

        $contens = "<table class='table table-bordered table-hover'>";
        $contens .= "<tr>";
        $contens .= "<td rowspan='2' class='text-center'>No</td>";
        $contens .= "<td rowspan='2' class='text-center'>Kode</td>";
        $contens .= "<td rowspan='2' class='text-center'>Produk</td>";
        foreach ($cb_id as $cabang) {
            $cabang_nama = $arrCabangName[$cabang];
            $contens .= "<td colspan='4' class='text-center'>$cabang_nama</td>";
        }
        $contens .= "<tr>";
        foreach ($cb_id as $cabang) {
            foreach ($needList as $list) {
                $contens .= "<td class='text-center'>$list</td>";
            }

        }
        $contens .= "</tr>";
        $contens .= "</tr>";
        $urut = 0;
        foreach ($produkList as $index_0 => $xDetails) {
            $urut++;
            $x_id = $xDetails->id;
            $x_name = $xDetails->nama;
            $x_code = $xDetails->kode;
            $contens .= "<tr>";
            $contens .= "<td>$urut</td>";
            $contens .= "<td>$x_code</td>";
            $contens .= "<td>$x_name</td>";
            foreach ($cb_id as $cabID) {
                $val = isset($tempPersediaan[$x_id][$cabID]) ? $tempPersediaan[$x_id][$cabID] : "0";
                $contens .= "<td class='text-right'>$val</td>";
                $contens .= "<td></td>";
                $contens .= "<td></td>";
                $contens .= "<td width='200px;' ></td>";
            }

            $contens .= "</tr>";

        }
        $contens .= "</table>";

        //  region company profile
        $this->load->model("Mdls/MdlCompany");
        $mc = New MdlCompany();
        $arrTmpCompany = $mc->lookupAll()->result();
        $arrCompanyProfile = array();
        if (sizeof($arrTmpCompany) > 0) {
            foreach ($arrTmpCompany as $cSpec) {
                foreach ($cSpec as $key => $val) {
                    $arrCompanyProfile['companyProfile_' . $key] = $val;
                }
            }
        }
        //  endregion
        $globalVars = $arrCompanyProfile;
        $receiptGlobalConfig = $this->config->item('receiptGlobal_config') != null ? $this->config->item('receiptGlobal_config') : array();
        $companyProfile = array();
        if (sizeof($receiptGlobalConfig) > 0) {
            $companyStr = $receiptGlobalConfig['companyProfile'];
            foreach ($globalVars as $key => $val) {
                $companyStr = str_replace("{" . $key . "}", $val, $companyStr);
            }
            $companyProfile['companyProfile']['contents'][] = $companyStr;
        }

        //arrPrint($elementData);
        $fixedElements = "<div class='col-md-6'>";
        $fixedElements .= "<div>" . $elementData['dtime'] . "</div>";
        $fixedElements .= "<div>" . $elementData['oleh'] . "</div>";
        $fixedElements .= "</div>";

        // arrPrintKuning(url_segment());
        // matiHere();
        $data = array(
            "mode" => $this->uri->segment(2),
            "content" => $contens,
            //            "title" => "",
            "companyProfile" => $companyProfile,
            //            "fixedElements"=> $fixedElements,
        );

        $this->load->view('opname', $data);
        $this->session->errMsg = "";
    }

    public function doUpload__()
    {
        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>saving data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );

        echo swalAlert($arrAlert);

        $content = "";
        //==menyimpan inputan data baru ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)

        $className = "Mdl" . $this->uri->segment(1) . "_xls";
        $dcomConf = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className][0] : array();//cek ada Dcomnya tidak
        $ctrlName = $this->uri->segment(3);
        $this->load->model("Mdls/" . $className);
        cekHijau($className);
        // matiHere($className);

        $o = new $className();
        $f = new MyForm($o, "doUpload");
        //matiHEre();
        $this->db->trans_start();
        $inserted = array();
        if ($f->isInputValid()) { //==jika validasi lengkap
            if (sizeof($o->getUnionPairs()) > 0) {
                if ($f->isUnionValid()) {
                }
                else {
                    $errMsg = "";
                    foreach ($f->getValidationResults() as $err) {
                        $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
                    }
                    echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                    die(lgShowAlert($errMsg));
                }
            }
            arrPrint($_FILES);
            //matIhere();
            foreach ($o->getFields() as $fieldName => $spec) {

                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (isset($spec['inputType'])) {
                    cekMerah($spec['inputType']);
                    switch ($spec['inputType']) {
                        case "checkbox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "qtyFillBox":
                            $data[$fName] = base64_encode(serialize($this->input->post($fName)));
                            break;
                        case "texts":
                            if (isset($spec['dataParams'])) {
                                $tmp = array();
                                foreach ($spec['dataParams'] as $param) {
                                    $tmp[$param] = $this->input->post($fName . "_" . $param);
                                }
                                $data[$fName] = base64_encode(serialize($tmp));
                            }
                            break;
                        case "password":
                            $data[$fName] = md5($this->input->post($fName));
                            break;
                        case "file":
                            if ($_FILES[$fName]['size'] > 0) {
                                //                                $image["image"] = file_get_contents($_FILES[$fName]['tmp_name']);
                                //                                $data[$fName] = blobEncode($image);
                                //
                                //                                                                    arrPrint($data);
                                //                                    die();

                                $config['upload_path'] = './uploads';
                                $config['allowed_types'] = 'xls|xlsx';
                                $config['overwrite'] = TRUE;

                                $this->load->library('upload', $config);
                                $this->upload->initialize($config);
                                $m = new CI_Upload();
                                //                                $m->data($_FILES);
                                $upload_data = $m->do_upload($_FILES);

                                matiHEre("tt");
                                if (do_upload()) {
                                    echo "sukses";
                                }
                                else {
                                    echo $this->upload->display_errors();
                                }

                            }
                            else {
                                cekHEre("$fName no image");
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":

                            break;
                        case "textarea":
                            //                            $data[$fName] = nl2br($this->input->post($fName));
                            $data[$fName] = $this->input->post($fName);
                            //                            print_r($data);
                            //                            matiHere("hiksss");
                            break;
                        default:
                            $data[$fName] = heTrimAvoidedChars($this->input->post($fName));
                            break;
                    }
                }
                else {
                    switch ($spec['type']) {
                        case "varchar":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "int":
                            $data[$fName] = $this->input->post($fName);
                            break;
                        case "date":
                            $data[$fName] = date("Y-m-d");
                            break;
                        case "datetime":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;
                        case "timestamp":
                            $data[$fName] = date("Y-m-d H:i:s");
                            break;

                        default:
                            $data[$fName] = $this->input->post($fName);
                            break;
                    }
                }

                /* ----------------------------
                 * untuk mengisi kolom_nama
                 * ---------------------------------*/
                if (isset($spec['strField'])) {
                    if (isset($spec["reference"])) {


                        $this->load->model("Mdls/" . $spec["reference"]);

                        $idnya = $this->input->post($spec["kolom"]);

                        $tmpRe = new $spec["reference"]();

                        // arrPrint($idnya . " " . $spec["reference"]);
                        // cekHitam();
                        $tmpFields = $tmpRe->lookupByID($idnya)->result();
                        $strField = $tmpFields[0]->$spec["strField"];
                        // showLast_query("lime");
                        arrPrint($tmpFields);
                        // arrPrint($spec);
                        arrPrint($spec['strField']);
                        // cekHere();
                        $data[$spec["kolom_nama"]] = $strField;
                    }
                }
            }

            //            cekHere(__LINE__);
            //            arrPrintWebs($data);
            //            matiHEre();
            if (sizeof($o->getAutoFillFields()) > 0) {
                foreach ($o->getAutoFillFields() as $mainCol => $autoFieldsCal) {
                    $data[$mainCol] = makeValue($autoFieldsCal, $this->input->post(), $this->input->post(), 0);
                }
            }
            if (sizeof($o->getFilters()) > 0) {
                foreach ($o->getFilters() as $k => $v) {

                    $condPair = explode("=", $v);
                    if (sizeof($condPair) > 1) {
                        $data[$condPair[0]] = trim($condPair[1], "'");
                    }
                }
            }
            $this->load->model("Mdls/" . "MdlDataTmp");
            $dTmp = new MdlDataTmp();
            $tmpData = array(
                "mdl_name" => $className,
                "mdl_label" => $ctrlName,
                "proposed_by" => $this->session->login['id'],
                "proposed_by_name" => $this->session->login['nama'],
                "proposed_date" => dtimeNow(),
                "content" => blobEncode($data),
            );

            $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
            arrPrint($validateDataFields);
            matiHEre();
            $tmpOrig = array();
            if (sizeof($validateDataFields) > 0) {
                $where = array();
                foreach ($validateDataFields as $fieldsValidate) {
                    $where[$fieldsValidate] = $data[$fieldsValidate];
                }
                $tmpOrig = $o->lookupByCondition($where)->result();
                showLast_query("lime");
                arrPrint($tmpOrig);
                $bNama = $tmpOrig[0]->biaya_nama;
                $bProduk = $tmpOrig[0]->produk_nama;
                $bProdukId = $tmpOrig[0]->produk_id;
            }


            if (sizeof($tmpOrig) > 0) {
                cekHere(":: HAHAHA ");
                if ($bProdukId > 0) {
                    $where2 = array("produk_id" => $bProdukId);
                }
                else {
                    $where2 = array();
                }
                $tmpOrig2 = $o->lookupByCondition($where2)->result();
                showLast_query("biru");
                arrPrint($tmpOrig2);

                $hasil = "";
                $hasil .= "$bNama  already set up<br>";
                foreach ($tmpOrig2 as $itemOrigs) {
                    $bNama2 = $itemOrigs->biaya_nama;
                    $bNilai2 = formatField("harga", $itemOrigs->nilai);

                    foreach ($o->getListedFieldsView() as $val) {
                        $bNama2 = $itemOrigs->$val;
                        $bNilai2 = isset($itemOrigs->nilai) ? formatField("harga", $itemOrigs->nilai) : "";
                        $var = "$bNama2 <span>$bNilai2</span>";
                        if ($hasil == "") {
                            $hasil .= "$var";
                        }
                        else {
                            $hasil = "$hasil<br>$var";
                        }
                    }


                }

                $bJudul = "$bProduk";
                $alerts = array(
                    "type" => "warning",
                    "title" => $bJudul,
                    "html" => $hasil,
                );
                echo swalAlert($alerts);
                echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
                die();
                matiHere("data $bNama  already exist on $bProduk, no data change<hr>");
                //udah ada data ngapain ditambah lagi dengan id sama.....
            }

            //            if ($this->creatorUsingApproval) {
            //                cekHere("approval");
            //                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));
            //                $this->session->errMsg = "Data proposal has been saved and pending approval";
            //                $this->load->model("Mdls/" . "MdlDataHistory");
            //                $hTmp = new MdlDataHistory();
            //                $tmpHData = array(
            //                    "orig_id"            => 0,
            //                    "mdl_name"           => $className,
            //                    "mdl_label"          => get_class($this),
            //                    "old_content"        => "",
            //                    "new_content"        => base64_encode(serialize($data)),
            //                    "new_content_intext" => print_r($data, true),
            //                    "label"              => "proposed",
            //                    "oleh_id"            => $this->session->login['id'],
            //                    "oleh_name"          => $this->session->login['nama'],
            //                );
            //                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //                cekHitam($this->db->last_query());
            //            }
            //            else {

            $validateDataFields = sizeof($o->getValidateData()) > 0 ? $o->getValidateData() : array();
            arrPrint($validateDataFields);
            cekHijau("validasi");
            $tmpOrig = array();
            if (sizeof($validateDataFields) > 0) {
                $where = array();
                foreach ($validateDataFields as $fieldsValidate) {
                    $where[$fieldsValidate] = $data[$fieldsValidate];
                }
                $tmpOrig = $o->lookupByCondition($where)->result();
            }


            if (sizeof($tmpOrig) > 0) {
                matiHere("data already exist, no data change");
                //udah ada data ngapain ditambah lagi dengan id sama.....
            }
            $insertID = $o->addData($data, $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            $this->session->errMsg = "Data contents have been saved";
            $inserted["id"] = $insertID;
            cekHitam($this->db->last_query());
            //                cekHitam($insertID);
            $updateLink = base_url() . get_class($this) . "/edit/$ctrlName/" . $insertID . "";
            $editClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $ctrlName ',
                                            size: BootstrapDialog.SIZE_WIDE,
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $updateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";

            $this->session->errMsg .= "<br><a href='JavaScript:void(0)' onclick=\"$editClick\">view entry</a>";

            if (isset($this->config->item("dataExtended")[$className])) {
                createAccessData($this->input->post('membership'), $insertID);
            }


            //region takbahan Dcom
            if (sizeof($dcomConf) > 0) {
                $inParam = array_merge($inserted, $data);
                $className = "DCom" . $dcomConf;
                $this->load->Model("DComs/" . $className);
                $d = new $className();
                $d->setWriteMode("insert");
                //                $d->pair($inParam);
                $d->pair($inParam) or die("Tidak berhasil memasang  values pada dcom-processor: $className/" . __FUNCTION__ . "/" . __LINE__);
                $gotParams = $d->exec();
                //                cekMerah("ayok dcom");
            }
            //endregion

            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id" => 0,
                "mdl_name" => $className,
                "mdl_label" => get_class($this),
                "old_content" => "",
                "new_content" => base64_encode(serialize($data)),
                "new_content_intext" => print_r($data, true),
                "label" => "applied",
                "oleh_id" => $this->session->login['id'],
                "oleh_name" => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //            }

            matiHere("hoop ----DONE---- belom commit " . __LINE__);
            $this->db->trans_complete();
            echo "<script>top.location.reload();</script>";

        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
    }

    public function doUpload()
    {
        $config['upload_path'] = './uploads/';
        // $config['allowed_types']        = array('xlsx','jpg');
        // $config['allowed_types']        = '*';
        $config['allowed_types'] = 'xlsx|csv|xls|ods';
        // $config['allowed_filetype']        = 'xlsx';
        // $config['max_size'] = 100;
        $config['max_size'] = 1024;
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        // arrPrintPink(url_segment());
        // arrPrint($_FILES["userfile"]);
        $files = $_FILES["userfile"];
        $param = $jenis_str = url_segment(4);
        $cabang_id = my_cabang_id();

        if ($cabang_id == "-1") {
            $jenis_trs = array(
                "Produk" => "1119",
                "Supplies" => "1118",
                "ProdukRakitan" => "5559",
            );
        }
        elseif ($cabang_id == "1") {
            $jenis_trs = array(
                // "Produk"        => "3339", // solo
                "Produk" => "2229", // solo
                "Supplies" => "2228",
                "ProdukRakitan" => "5559",
            );
        }
        else {
            $jenis_trs = array(
                "Produk" => "2229",
                "Supplies" => "2228",
                "ProdukRakitan" => "5559",
            );
        }

        $jenis_tr = isset($jenis_trs[$jenis_str]) ? $jenis_trs[$jenis_str] : matiHere("belum ada di konversi jenis tr | Line: " . __LINE__);

        // matiHere(__LINE__);
        $file_name = $files['name'];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            $rr = print_r($this->upload->display_errors(), true);
            // $this->upload->file_name();
            $keterangan = "<br>format file yang diupload harus berextensi <b>xlsx</b> $rr";
            echo lgShowWarning('salah file', "file anda:: $file_name $keterangan");
            return $error;
        }
        else {
            $data = array('upload_data' => $this->upload->data());

            arrPrint($_POST);
            // arrPrint($data);
            // matiHere(__LINE__);
            $this->load->model("Mdls/MdlOpname_xls");
            $d = new MdlOpname_xls();
            $InsertData = array(
                "file_name" => $data['upload_data']['file_name'],
                "file_type" => $data['upload_data']['file_type'],
                "full_path" => $data['upload_data']['full_path'],
                "file_ext" => $data['upload_data']['file_ext'],
                "dtime" => date("Y-m-d H:i"),
                "fulldate" => date("Y-m-d"),
                "oleh_id" => $this->session->login['id'],
                "cabang_id" => $this->session->login['cabang_id'],
                // identitas upload excell gudang id belum disimpan
                // "gudang_id" => $this->session->login['cabang_id'],
                // "gudang_nama" => $this->session->login['cabang_id'],
                "jenis_tr" => $jenis_tr,
                "jenis_produk" => $param,
            );
            $insertID = $d->addData($InsertData, $d->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            $this->session->errMsg = "Data berhasil disimpan";
            $inserted["id"] = $insertID;
            $date_now = dtimeNow();
            // echo lgShowSuccess("Berhasil", "File_stok opname $file_name sudah berhasil disimpan");
            $swalTitle = "Membaca & Memproses Data...";
            $swalText = "File " . $file_name . " berhasil diunggah. Sistem sedang membaca data opname dan mencocokkan stok ke database. Mohon tunggu...";
            echo "<script>";
            echo "  top.swal({";
            echo "    title: " . json_encode($swalTitle) . ",";
            echo "    text: " . json_encode($swalText) . ",";
            echo "    showConfirmButton: false,";
            echo "    allowOutsideClick: false,";
            echo "    allowEscapeKey: false";
            echo "  });";
            echo "  if(top.swal.showLoading){top.swal.showLoading();}else if(top.swal.enableLoading){top.swal.enableLoading();}";
            echo "  top.$('#result').load('" . MODUL_PATH . get_class($this) . "/executeOpname/$param?jenis_tr=$jenis_tr&file_id=$insertID');";
            echo "</script>";
        }


    }

    //region executor opname
    public function executeOpname()
    {


        $this->load->model("Mdls/MdlOpname_xls");
        $this->load->model("MdlTransaksi");
        $this->load->library('PHPExcel');

        $param = $this->uri->segment(4);
        switch ($param) {
            case "Produk":
                //                if(my_cabang_id() == "25"){
                //                    $masterModel = MdlProduk;
                //                }
                //                else{
                //                    $masterModel = MdlProduk2;
                //                }
                $masterModel = "MdlProduk2";
                $comStok = "ComRekeningPembantuProduk";
                $comRekeningCoa = "1010030030";
                break;
            case "Supplies":
                $masterModel = "MdlSupplies";
                $comStok = "ComRekeningPembantuSupplies";
                $comRekeningCoa = "1010030010";
                break;
            case "ProdukRakitan":
                $masterModel = "MdlProdukRakitan";
                $comStok = "ComRekeningPembantuProduk";
                $comRekeningCoa = "1010030070";
                break;
        }
        $this->load->model("Coms/$comStok");
        $this->load->model("Mdls/" . $masterModel);
        // matiHEre();
        $file_id = isset($_GET['file_id']) ? $_GET['file_id'] : "0";
        // $this->jenisTr = "1119";//ditembak untuk auto generate
        $jenis_tr = $this->jenisTr = isset($_GET['jenis_tr']) ? $_GET['jenis_tr'] : matiHere("tidak mendapatkan kiriman jenis_tr (get)");
        $tCodeTargetJenisTransaksi = $this->configUi[$this->jenisTr]['steps'][1]['target'];
        // $jenis_tr = $this->jenisTr = isset($_GET['jenis_tr']) ? $_GET['jenis_tr'] : 1119;
        $this->tableInConfig = isset($this->configUi[$this->jenisTr]['tableIn']) ? $this->configUi[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->configUi[$this->jenisTr]['tableIn_static']) ? $this->configUi[$this->jenisTr]['tableIn_static'] : array();
        cekHere($tCodeTargetJenisTransaksi);
        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $o = new MdlOpname_xls();
        $o->setFilters(array());
        $o->addFilter("cli='0'");
        $o->addFilter("trash='0'");
        if ($file_id > 0) {
            $o->addFilter("id='$file_id'");
        }
        else {
            $querystring = ($_SERVER['REDIRECT_QUERY_STRING']);
            cekBiru("tidak ada kiriman file_id dr " . uri_string() . "?$querystring");
        }

        // filter belum membaca cabang_id dan gudang_id
        //        $o->shortBy("id","asc");
        $this->db->limit(1);
        $tmp = $o->lookUpAll()->result();
        // showLast_query("lime");
        // arrPrint($tmp);

        $filePath = $tmp[0]->full_path;
        $oleh_id = $tmp[0]->oleh_id;
        $oleh_nama = $tmp[0]->oleh_nama;
        $cabang_id = $tmp[0]->cabang_id;
        $fileID = $tmp[0]->id;
        cekBiru("$file_id:: $filePath");

        $ext = str_replace(".", "", $tmp[0]->file_ext);
        //        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";
        $loadexcel = $this->xlsx->load($filePath);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;

        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $kolom = $headers[$data_header];
        $koloms = array_filter($kolom);

        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        // arrPrint($koloms);
        // sizeof($sheet);
        // matiHere(__LINE__);
        $datas = array();
        $produk_id = array();
        $dt_gdid_0 = array();
        $items5_sum = array();
        $items5 = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {

                foreach ($koloms as $kolom => $kalias) {
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }

                $pid = isset($rows['pid']) ? $rows['pid'] : (isset($rows['id']) ? $rows['id'] : '');
                if ($pid != '') {
                    $datas[$pid]['qty'] = isset($rows['stok riil']) ? $rows['stok riil'] : (isset($rows['qty']) ? $rows['qty'] : 0);
                    $datas[$pid]['serial'] = isset($rows['serial number']) ? $rows['serial number'] : (isset($rows['serial']) ? $rows['serial'] : '');
                    $produk_id[] = $pid;

                    // Tangkap nomor seri dari baris Excel (Mendukung pemisah koma, titik koma, enter, maupun pipe)
                    $snRaw = isset($rows['serial number']) ? trim($rows['serial number']) : (isset($rows['serial']) ? trim($rows['serial']) : '');
                    if ($snRaw !== "") {
                        $snList = preg_split('/[\r\n,;|]+/', $snRaw);
                        if (is_array($snList) && sizeof($snList) > 0) {
                            foreach ($snList as $snItem) {
                                $sn = trim($snItem);
                                if ($sn !== "") {
                                    $items5_sum[] = array(
                                        'produk_id' => $pid,
                                        'serial_number' => $sn,
                                        'sku' => isset($rows['sku']) ? $rows['sku'] : '',
                                    );
                                    $items5[$pid][$sn] = 1;
                                }
                            }
                        }
                    }
                }
                if (sizeof(array_filter($rows))) {
                    $dt_cbid = isset($rows['cid']) ? $rows['cid'] : '';
                    if (isset($rows['gid'])) {
                        $dt_gdid_0[$rows['gid']] = $rows['gid'];
                    }
                    $dt_jenis = isset($rows['jenis']) ? $rows['jenis'] : '';
                }
            }
            $numrow++;
        }
        $gd_ids = array_filter(array_keys($dt_gdid_0));
        $dt_gdid = $gd_ids['0'];
        //         arrPrintHijau($dt_gdid);
        //         // arrPrintPink([0]);
        //         cekPink(sizeof($datas));
        // matiHere(__LINE__);
        /* -----------------------------------------------------------------------------------------
       * dashboard_opname
       * -----------------------------------------------------------------------------------------*/
        $op_condites = array(
            "jenis" => "$param",
            "gudang_id" => $dt_gdid
        );
        $op_datas = $this->cekOpnameAktive($op_condites);
        $jml_upload = sizeof($op_datas);
        // cekOrange("jml opname_data: $jml_upload jn: $dt_jenis did:: $dt_gdid cid: $dt_cbid");
        // showLast_query("kuning");
        // mati_disini(__LINE__);
        if ($jml_upload == 1) {
            $db_opnames = $op_datas[0];
            // arrPrintHijau($db_opnames);
            $db_id = $db_opnames->id;
            $db_gudang_id = $db_opnames->gudang_id;
            $db_cabang_id = $db_opnames->cabang_id;
            $db_jenis = $db_opnames->jenis;
            $db_opname = "dashboard_opname";

            /*---validasi data yg diupload---*/
            if ($db_cabang_id != $dt_gdid) {

            }
            elseif ($db_jenis != $dt_jenis) {

            }
            elseif ($db_cabang_id != $dt_cbid) {

            }

            /* update ke data opname */
            $this->load->model("Mdls/MdlDashboardOpnameData");
            $dopd = new MdlDashboardOpnameData();

            foreach ($datas as $produk_exc_id => $data_exc) {
                $jml_exc = $data_exc['qty'];
                $db_condites = array(
                    "dashboard_opname_id" => $db_id,
                    "produk_id" => $produk_exc_id,
                );
                $db_datas = array(
                    "jml_stok_opname" => $jml_exc,
                );
                $dopd->updateData($db_condites, $db_datas);
                // showLast_query("merah");
                // break;
            }
            cekMerah("dashboard_opname_data berhasil diupdate");

            /*--membuat log opname--*/
            $this->load->model("Mdls/MdlDashboardOpname");
            $dop = new MdlDashboardOpname();

            $mdb_condites = array(
                "id" => $db_id,
            );
            $mdb_datas = array(
                "done_id" => my_id(),
                "done_nama" => my_name(),
                "dtime_done" => dtimeNow(),
            );
            $dop->updateData($mdb_condites, $mdb_datas);
            showLast_query("biru");
        }
        elseif (($jml_upload > 1)) {
            matiHere("ada problem di server upload opname harap konfirmasikan ke web suport " . __LINE__);
        }
        else {
            matiHere("identitas file tidak ditemukan, harap konfirmasikan ke web suport " . __LINE__);
        }
        // arrPrintPink($datas); //xls
        //        arrPrint($dataRekening);// rekening

        // matiHere(__LINE__);
        /*---------------------------------------------------------------------------------uploader*/

        // cekOrange("jn: $dt_jenis did:: $dt_gdid cid: $dt_cbid");
        $this->load->model("Mdls/MdlGudangDefault_center");
        $this->load->model("Mdls/MdlCabang");
        $c = new MdlCabang();
        $g = new MdlGudangDefault_center();

        $c->addFilter("id='$cabang_id'");
        $cabang_data = $c->lookupAll()->result();

        $cabangData = array();
        $branchData = array();
        foreach ($cabang_data as $cabData) {
            $cabangData[$cabData->id] = $cabData->nama;
        }

        if ($dt_gdid == "-1") {
            $g->addFilter("cabang_id='$cabang_id'");
            $tempBranch = $g->lookupAll()->result();
            // showLast_query("kuning");
            foreach ($tempBranch as $tempBranchData) {
                $branchData[$cabang_id] = array(
                    "gudang_id" => $tempBranchData->id,
                    "gudang_nama" => $tempBranchData->name,
                );
            }
        }
        else {
            $branchData[$cabang_id] = array(
                "gudang_id" => $dt_gdid,
                "gudang_nama" => "",
            );
        }

        // arrPrintPink($branchData);// rekening

        //region builder session data trasnksional


        //builder data rekening persdiaan dan data produk
        $selectFields = array("extern_id", "qty_debet", "harga_avg");
        //        $itemFields = $this->configUi['1119'][''];
        $p = new $comStok();
        $p->addFilter("extern_id in ('" . implode("','", $produk_id) . "')'");
        $p->addFilter("periode='forever'");
        $p->addFilter("cabang_id='$cabang_id'");
        $p->addFilter("gudang_id='$dt_gdid'");
        $p->addFilter("rekening='$comRekeningCoa'");
        $temPersedian = $p->lookUpall()->result();
        //        cekLime($this->db->last_query());
        $dataRekening = array();
        foreach ($temPersedian as $temPersedian0) {
            $tmpData = array();
            foreach ($selectFields as $fields) {
                $tmpData[$fields] = $temPersedian0->$fields;
            }
            $dataRekening[$temPersedian0->extern_id] = $tmpData;
        }
        //        arrPrint($dataRekening);
        //endregion


        //region price list
        $this->load->model("Mdls/MdlHargaProduk");
        $h = new MdlHargaProduk();
        $h->addFilter("jenis='produk'");
        $h->addFilter("jenis_value='hpp'");
        $h->addFilter("status='1'");
        $h->addFilter("cabang_id='$cabang_id'");
        $h->addFilter("produk_id in ('" . implode("','", $produk_id) . "')'");
        $tmpPrice = $h->lookUpAll()->result();
        // cekHitam($this->db->last_query());
        // arrPrint($tmpPrice);
        $arrPrice = array();
        if (sizeof($tmpPrice) > 0) {
            foreach ($tmpPrice as $priceData) {
                $arrPrice[$priceData->cabang_id][$priceData->produk_id] = $priceData->nilai;
            }
        }
        $priceList = $arrPrice[$cabang_id];
        //        cekHitam($this->db->last_query());
        //        arrPrint($arrPrice);
        //        arrPrint($tmpPrice);
        //endregion
        //matiHere();
        //region build data produk
        $selectFields = array(
            "id" => "id",
            "nama" => "nama",
            "kode" => "kode",
            "produk_kode" => "kode",
            "no_part" => "no_part",
            "satuan" => "satuan",
            "outdoor_id" => "outdoor_id",
            "jml_serial" => "jml_serial",
            "kategori_id" => "kategori_id",
            "kategori_nama" => "kategori_nama",
            "outdoor_nama" => "outdoor_nama",
            "outdoor_barcode" => "outdoor_barcode",
            "indoor_id_1" => "indoor_id_1",
            "indoor_nama_1" => "indoor_nama_1",
            "indoor_barcode_1" => "indoor_barcode_1",
            "indoor_id_2" => "indoor_id_2",
            "indoor_nama_2" => "indoor_nama_2",
            "indoor_barcode_2" => "indoor_barcode_2",
            "indoor_id_3" => "indoor_id_3",
            "indoor_nama_3" => "indoor_nama_3",
            "indoor_barcode_3" => "indoor_barcode_3",
            "indoor_id_4" => "indoor_id_4",
            "indoor_nama_4" => "indoor_nama_4",
            "indoor_barcode_4" => "indoor_barcode_4",
            "produk_part_kategori_id" => "produk_part_kategori_id",
            "produk_part_kategori_nama" => "produk_part_kategori_nama",
            "produk_part_jenis_id" => "produk_part_jenis_id",
            "produk_part_jenis_nama" => "produk_part_jenis_nama",
            "produk_part_ukuran_id" => "produk_part_ukuran_id",
            "produk_part_ukuran_nama" => "produk_part_ukuran_nama",
        );
        // $this->load->model("Mdls/$masterModel");
        $pr = new $masterModel();
        // $pr->addFilter("id in ('" . implode("','", $produk_id) . "')'");
        //        $p->addFilter("periode='forever'");
        //        $p->addFilter("cabang_id='$cabang_id'");
        $temProduk = $pr->lookUpall()->result();
        // cekLime($this->db->last_query());

        // matiHere(__LINE__);
        $dataProduk = array();
        foreach ($temProduk as $temProduk_0) {
            // arrPrintHijau($temProduk_0);
            $tmpData = array();
            foreach ($selectFields as $k => $fields) {
                $tmpData[$k] = $temProduk_0->$fields;
            }
            $dataProduk[$temProduk_0->id] = $tmpData;
        }
        //arrPrint($dataProduk);
        //endregion
        // matiHere(__LINE__);
        //region array builder transaction
        $mainTmp = array(
            "dummyElement" => "yes",
            "dummyElement__label" => "yes",
            "dummyElement__name" => "yes",
            "olehID" => "-100",
            "olehName" => "sys",
            "placeID" => "-1",
            "placeName" => "pusat",
            "divID" => $this->divId,
            "divName" => "default",
            "cabangID" => "-1",
            "cabangName" => "pusat",
            "gudangID" => "-1",
            "gudangName" => "default center warehouse",
            "jenisTr" => "$jenis_tr",
            "jenisTrMaster" => "$jenis_tr",
            "jenisTrTop" => $jenis_tr . "r",
            "jenisTrName" => "STOCK OPNAME",
            "stepNumber" => "1",
            "stepCode" => $jenis_tr . "r",
            "dtime" => dtimeNow("Y-m-d H:i"),
            "fulldate" => dtimeNow("Y-m-d"),
            "harga" => "0",
            "subtotal" => "0",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "no_part" => "0",
            "ppn" => "0",
            "stok" => "1",
            "debet" => "0",
            "kredit" => "0",
            "hpp" => "0",
            "qty_selisih" => "0",
            "jenis" => $jenis_tr . "r",
            "transaksi_jenis" => $jenis_tr . "r",
            "next_step_code" => $jenis_tr . "ro",
            "next_group_code" => "c_holding",
            "step_number" => "1",
            "step_current" => "1",
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "new_sisa" => "0",
            "qty_opname" => "0",
            "qty_debet" => "0",
            "qty_kredit" => "0",
        );
        $itemsTmp = array(
            "handler" => "Selectors/_processSelectProduct",
            "id" => "id",
            "jml" => "jml",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "satuan" => "satuan",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "nama" => "nama",
            "produk_kode" => "produk_kode",
            "no_part" => "no_part",
            "label" => "0",
            "ppn" => "0",
            "stok" => "stok",
            "debet" => "debet",
            "kredit" => "kredit",
            "hpp" => "hpp",
            "qty_selisih" => "qty_selisih",
            "qty" => "qty",
            "name" => "name",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "sub_subtotal",
            "sub_discount_persen" => "sub_discount_persen",
            "sub_discount_qty" => "0",
            "sub_ppn" => "0",
            "sub_stok" => "sub_stok",
            "sub_debet" => "sub_debet",
            "sub_kredit" => "sub_kredit",
            "sub_hpp" => "sub_hpp",
            "sub_qty_selisih" => "sub_qty_selisih",
            "olehID" => "-100",
            "olehName" => "sys",
            "placeID" => "cabang_id",
            "placeName" => 'placeName',
            "cabangID" => "cabangID",
            "cabangName" => "cabangName",
            "gudangID" => "gudangID",
            "gudangName" => "gudangName",
            "jenisTr" => $jenis_tr,
            "next_substep_code" => $jenis_tr . "ro",
            "next_subgroup_code" => "c_holding",
            "sub_step_number" => "1",
            "sub_step_current" => "1",
            "nilai_bayar" => "",
            "new_sisa" => "0",
            "sub_new_sisa" => "0",
            "qty_opname" => "qty_opname",
            "qty_debet" => "qty_debet",
            "qty_kredit" => "qty_kredit",
            "sub_qty_opname" => "sub_qty_opname",
            "sub_qty_debet" => "sub_qty_debet",
            "sub_qty_kredit" => "sub_qty_kredit",
            "referensi_id" => "referensi_id",
            "referensi_jenis" => "referensi_jenis",


        );
        $items2 = array();
        $items2_sum = array();
        $rsltItems = array();
        $rsltItems2 = array();
        $tableIn_master = array(
            "trash" => "0",
            "jenis_master" => $jenis_tr,
            "jenis_top" => $jenis_tr . "r",
            "jenis" => $jenis_tr . "r",
            "jenis_label" => "STOCK OPNAME",
            "div_id" => $this->divId,
            "div_nama" => "default",
            "dtime" => dtimeNow("Y-m-d H:i"),
            "fulldate" => dtimeNow("Y-m-d"),
            "oleh_id" => "-100",
            "oleh_nama" => "sys",
            "cabang_id" => $cabang_id,
            "cabang_nama" => my_cabang_nama(),
            "transaksi_jenis" => $jenis_tr . "r",
            "gudangID" => $branchData[$cabang_id]['gudang_id'],
            "gudangName" => $branchData[$cabang_id]['gudang_nama'],
            "gudang_id" => $branchData[$cabang_id]['gudang_id'],
            "gudang_nama" => $branchData[$cabang_id]['gudang_nama'],
            "next_step_code" => $jenis_tr . "ro",
            "next_group_code" => "c_holding",
            "step_number" => "1",
            "step_current" => "1",
        );
        $tableIn_detailTmp = array(
            "produk_id" => "id",
            "produk_kode" => "produk_kode",
            "produk_label" => "",
            "produk_nama" => "nama",
            "produk_ord_jml" => "1",
            "produk_ord_hrg" => "harga",
            "hpp" => "hpp",
            "satuan" => "satuan",
            "note" => "",
            "reference" => "",
            "trash" => "0",
            "produk_jenis" => "produk",
            "valid_qty" => "1",
        );
        $tableIn_detail2_sum = array();
        $tableIn_detail_rsltItems = array();
        $tableIn_detail_rsltItems2 = array();
        $tableIn_master_valuesTmp = array(
            "divID" => "4",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "hpp" => "hpp",
            "no_part" => "",
            "ppn" => "ppn",
            "stok" => "1",
            "debet" => "debet",
            "kredit" => "kredit",
            "qty_selisih" => "qty_selisih",
            "qty_opname" => "qty_opname",
            "qty_debet" => "qty_debet",
            "qty_kredit" => "qty_kredit",
        );
        $tableIn_detail_valuesTmp = array(
            "jml" => "jml",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "ppn" => "0",
            "stok" => "stok",
            "debet" => "debet",
            "kredit" => "kredit",
            "hpp" => "hpp",
            "qty_selisih" => "qty_selisih",
            "qty" => "qty",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "sub_subtotal",
            "sub_discount_persen" => "0",
            "sub_discount_qty" => "0",
            "sub_ppn" => "0",
            "sub_stok" => "sub_stok",
            "sub_debet" => "sub_debet",
            "sub_kredit" => "sub_kredit",
            "sub_hpp" => "sub_hpp",
            "sub_qty_selisih" => "sub_qty_selisih",
            "sub_new_sisa" => "0",
            "qty_opname" => "qty_opname",
            "qty_debet" => "qty_debet",
            "qty_kredit" => "qty_kredit",
            "sub_qty_opname" => "sub_qty_opname",
            "sub_qty_debet" => "sub_qty_debet",
            "sub_qty_kredit" => "sub_qty_kredit",
        );
        $tableIn_detail_values_rsltItemsTmp = array();
        $tableIn_detail_values_rsltItems2Tmp = array();
        $tableIn_detail_values2_sumTmp = array();
        $tableIn_detail2 = array();
        $main_add_values = array();
        $main_add_fields = array();
        $main_elements = array(
            "dummyElement" => Array(
                "elementType" => "dataModel",
                "name" => "dummyElement",
                "label" => "auto-validation",
                "key" => "yes",
                "labelSrc" => "name",
                "labelValue" => "yes",
                "mdl_name" => "MdlDummyElement",
                "contents" => "YToxOntzOjQ6Im5hbWUiO3M6MzoieWVzIjt9",
                "contents_intext" => "",
            ),
        );
        $main_inputs = array();
        $main_inputs_orig = array();
        $receiptDetailFieldsTmp = array(
            "produk_nama" => "name",
        );
        $receiptSumFieldsTmp = array(
            "harga" => "total amount",
        );
        $receiptDetailFields2 = array();
        $receiptSumFields2 = array();
        $tableIn_detail_values2_sum = array();
        $items3 = array();
        $items3_sum = array();
        $tableIn_detail_values_rsltItems = array();
        $tableIn_detail_values_rsltItems2 = array();
        //endregion

        // cekHitam(sizeof($dataProduk));
        //         matiHEre(__LINE__);
        $subtotal_nilai_debet = 0;
        $subtotal_nilai_kredit = 0;
        $subtotal_qty_debet = 0;
        $subtotal_qty_kredit = 0;
        $subtotal_opname = 0;
        $subharga = 0;
        $itemsData = array();
        foreach ($datas as $pID => $pDatasss) {
            $pData = $dataProduk[$pID];
            //
            $harga = isset($priceList[$pID]) && $priceList[$pID] > 0 ? $priceList[$pID] : 1;
            $ppn = $harga * 10 / 100;
            $sub_harga = $ppn + $harga;
            $qty_riil = $qty_opname = $datas[$pID]["qty"];
            $qty_syst = isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0;

            $selisih = isset($dataRekening[$pID]["qty_debet"]) ? ($dataRekening[$pID]["qty_debet"] - $datas[$pID]["qty"]) : (0 - $datas[$pID]["qty"]);

            if ($selisih < 0) {
                $qty_debet = $selisih * -1;
                $qty_kredit = 0;
                $selisih_qty = $qty_debet;
                $debet_nilai = $qty_debet * $harga;
                $kredit_nilai = 0;
            }
            else {
                $qty_debet = 0;
                $qty_kredit = $selisih;
                $selisih_qty = $qty_kredit;
                $debet_nilai = 0;
                $kredit_nilai = $qty_debet * $harga;
            }

            // cekHijau("$pID :: $selisih ||sys: $qty_syst ||rii: $qty_riil || $qty_debet || $qty_kredit");
            // if($qty_kredit == $qty_debet){
            //
            //     break;
            // }
            $subharga = $debet_nilai + $kredit_nilai;
            $subtotal_nilai_debet += $debet_nilai;
            $subtotal_nilai_kredit += $kredit_nilai;
            $subtotal_qty_debet += $qty_debet;
            $subtotal_qty_kredit += $qty_kredit;
            $subtotal_opname += $qty_riil;

            $itemsData[$pID] = array(
                "id" => "$pID",
                "jml" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                "harga" => $harga,
                "subtotal" => $sub_harga,
                "satuan" => $pData["satuan"],
                "discount_persen" => 0,
                "discount_qty" => 0,
                "hpp" => $harga,
                "nama" => $pData["nama"],
                "produk_kode" => $pData["produk_kode"],
                "no_part" => $pData["no_part"],
                "label" => 0,
                "ppn" => $ppn,
                "stok" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                "debet" => $debet_nilai,
                "kredit" => $kredit_nilai,
                "qty_selisih" => $selisih,
                "qty" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                "name" => $pData["nama"],
                "sub_harga" => $harga,
                "sub_subtotal" => $harga,
                "sub_discount_persen" => 0,
                "sub_discount_qty" => 0,
                "sub_hpp" => $harga,
                "sub_ppn" => $ppn,
                "sub_stok" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                "sub_debet" => $debet_nilai,
                "sub_kredit" => "0",
                "sub_qty_selisih" => $selisih,
                "olehID" => "-100",
                "olehName" => "sys",
                "pihakID" => $cabang_id,
                "pihakName" => $cabangData[$cabang_id],
                "placeID" => $cabang_id,
                "placeName" => $cabangData[$cabang_id],
                "cabangID" => $cabang_id,
                "cabangName" => $cabangData[$cabang_id],
                "gudangID" => $branchData[$cabang_id]['gudang_id'],
                "gudangName" => $branchData[$cabang_id]['gudang_nama'],
                "jenisTr" => $jenis_tr,
                "next_substep_code" => $jenis_tr . "ro",
                "next_subgroup_code" => "c_holding",
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "nilai_bayar" => "",
                "new_sisa" => 0,
                "sub_new_sisa" => 0,
                "qty_opname" => $qty_riil,
                // ------------------
                // "qty_debet"           => $selisih_qty,
                "qty_debet" => $qty_debet,
                "qty_kredit" => $qty_kredit,
                // ------------------
                "sub_qty_opname" => $qty_riil,
                // "sub_qty_debet"       => $selisih_qty,
                "sub_qty_debet" => $qty_debet,
                "sub_qty_kredit" => $qty_kredit,
                "referensi_id" => $db_id,
                "referensi_jenis" => $db_opname,
            );
            // arrPrint($pData);
        }
        // foreach ($dataProduk as $pID => $pData) {
        //
        //     $harga = isset($priceList[$pID]) && $priceList[$pID] > 0 ? $priceList[$pID] : 1;
        //     $ppn = $harga * 10 / 100;
        //     $sub_harga = $ppn + $harga;
        //     $qty_riil = $qty_opname = $datas[$pID]["qty"];
        //     $qty_syst = isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0;
        //
        //     $selisih = isset($dataRekening[$pID]["qty_debet"]) ? ($dataRekening[$pID]["qty_debet"] - $datas[$pID]["qty"]) : (0 - $datas[$pID]["qty"]);
        //
        //     if ($selisih < 0) {
        //         $qty_debet = $selisih * -1;
        //         $qty_kredit = 0;
        //         $selisih_qty = $qty_debet;
        //         $debet_nilai = $qty_debet * $harga;
        //         $kredit_nilai = 0;
        //     }
        //     else {
        //         $qty_debet = 0;
        //         $qty_kredit = $selisih;
        //         $selisih_qty = $qty_kredit;
        //         $debet_nilai = 0;
        //         $kredit_nilai = $qty_debet * $harga;
        //     }
        //
        //     // cekHijau("$pID :: $selisih ||sys: $qty_syst ||rii: $qty_riil || $qty_debet || $qty_kredit");
        //     // if($qty_kredit == $qty_debet){
        //     //
        //     //     break;
        //     // }
        //     $subharga = $debet_nilai + $kredit_nilai;
        //     $subtotal_nilai_debet += $debet_nilai;
        //     $subtotal_nilai_kredit += $kredit_nilai;
        //     $subtotal_qty_debet += $qty_debet;
        //     $subtotal_qty_kredit += $qty_kredit;
        //     $subtotal_opname += $qty_riil;
        //
        //     $itemsData[$pID] = array(
        //         "id"                  => "$pID",
        //         "jml"                 => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
        //         "harga"               => $harga,
        //         "subtotal"            => $sub_harga,
        //         "satuan"              => $pData["satuan"],
        //         "discount_persen"     => 0,
        //         "discount_qty"        => 0,
        //         "hpp"                 => $harga,
        //         "nama"                => $pData["nama"],
        //         "produk_kode"         => $pData["produk_kode"],
        //         "no_part"             => $pData["no_part"],
        //         "label"               => 0,
        //         "ppn"                 => $ppn,
        //         "stok"                => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
        //         "debet"               => $debet_nilai,
        //         "kredit"              => $kredit_nilai,
        //         "qty_selisih"         => $selisih,
        //         "qty"                 => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
        //         "name"                => $pData["nama"],
        //         "sub_harga"           => $harga,
        //         "sub_subtotal"        => $harga,
        //         "sub_discount_persen" => 0,
        //         "sub_discount_qty"    => 0,
        //         "sub_hpp"             => $harga,
        //         "sub_ppn"             => $ppn,
        //         "sub_stok"            => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
        //         "sub_debet"           => $debet_nilai,
        //         "sub_kredit"          => "0",
        //         "sub_qty_selisih"     => $selisih,
        //         "olehID"              => "-100",
        //         "olehName"            => "sys",
        //         "pihakID"             => $cabang_id,
        //         "pihakName"           => $cabangData[$cabang_id],
        //         "placeID"             => $cabang_id,
        //         "placeName"           => $cabangData[$cabang_id],
        //         "cabangID"            => $cabang_id,
        //         "cabangName"          => $cabangData[$cabang_id],
        //         "gudangID"            => $branchData[$cabang_id]['gudang_id'],
        //         "gudangName"          => $branchData[$cabang_id]['gudang_nama'],
        //         "jenisTr"             => $jenis_tr,
        //         "next_substep_code"   => $jenis_tr . "ro",
        //         "next_subgroup_code"  => "c_holding",
        //         "sub_step_number"     => 1,
        //         "sub_step_current"    => 1,
        //         "nilai_bayar"         => "",
        //         "new_sisa"            => 0,
        //         "sub_new_sisa"        => 0,
        //         "qty_opname"          => $qty_riil,
        //         // ------------------
        //         // "qty_debet"           => $selisih_qty,
        //         "qty_debet"           => $qty_debet,
        //         "qty_kredit"          => $qty_kredit,
        //         // ------------------
        //         "sub_qty_opname"      => $qty_riil,
        //         // "sub_qty_debet"       => $selisih_qty,
        //         "sub_qty_debet"       => $qty_debet,
        //         "sub_qty_kredit"      => $qty_kredit,
        //         "referensi_id"        => $db_id,
        //         "referensi_jenis"     => $db_opname,
        //     );
        //     // arrPrint($pData);
        // }

        // cekMErah(sizeof($itemsData));
        // matiHere(__LINE__);
        //region builder items

        //region builder main
        // main untuk mode gabungan
        $main = array(
            "dummyElement" => "yes",
            "dummyElement__label" => "yes",
            "dummyElement__name" => "yes",
            "olehID" => "-100",
            "olehName" => "sys",
            "placeID" => $cabang_id,
            "placeName" => $cabangData[$cabang_id],
            "divID" => $this->divId,
            "divName" => "default",
            "cabangID" => $cabang_id,
            "cabangName" => $cabangData[$cabang_id],
            "gudangID" => $branchData[$cabang_id]['gudang_id'],
            "gudangName" => $branchData[$cabang_id]['gudang_nama'],
            "jenisTr" => "$jenis_tr",
            "jenisTrMaster" => "$jenis_tr",
            "jenisTrTop" => $jenis_tr . "r",
            "jenisTrName" => "STOCK OPNAME",
            "stepNumber" => "1",
            "stepCode" => $jenis_tr . "r",
            "dtime" => dtimeNow("Y-m-d H:i"),
            "fulldate" => dtimeNow("Y-m-d"),
            "harga" => $subharga,
            "subtotal" => $subharga,
            "discount_persen" => "0",
            "discount_qty" => "0",
            "no_part" => "0",
            "ppn" => "$ppn",
            "stok" => "1",
            "debet" => "$subtotal_nilai_debet",
            "kredit" => "$subtotal_nilai_kredit",
            "hpp" => $subharga,
            "qty_selisih" => "",
            "jenis" => $jenis_tr . "r",
            "transaksi_jenis" => $jenis_tr . "r",
            "next_step_code" => $jenis_tr . "ro",
            "next_group_code" => "c_holding",
            "step_number" => "1",
            "step_current" => "1",
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            //            "new_sisa" => "0",
            "qty_opname" => $subtotal_opname,
            "qty_debet" => $subtotal_qty_debet,
            "qty_kredit" => $subtotal_qty_kredit,
            "referensi_id" => $db_id,
            "referensi_jenis" => $db_opname,
        );
        //endregion builder main
        // arrPrint($main);
        // matiHere(__LINE__);
        //region builder items
        $items = array();
        $items4 = array();
        $items5 = array();
        $items5_sum = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($itemsTmp as $col => $selectedRow) {
                $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }

            // Ekstrak serial number yang dilampirkan dari excel jika ada
            if (isset($datas[$itsID]['serial']) && trim($datas[$itsID]['serial']) !== "" && trim($datas[$itsID]['serial']) !== "-") {
                $rawSerials = preg_split('/[\r\n,;|]+/', trim($datas[$itsID]['serial']));
                $pKode = isset($dataProduk[$itsID]['kode']) && $dataProduk[$itsID]['kode'] != "" ? $dataProduk[$itsID]['kode'] : (isset($dataProduk[$itsID]['nama']) ? $dataProduk[$itsID]['nama'] : "SERIAL");
                foreach ($rawSerials as $sRaw) {
                    $sClean = trim($sRaw);
                    if ($sClean !== "") {
                        if (!isset($items5[$itsID][$pKode])) {
                            $items5[$itsID][$pKode] = array();
                        }
                        $items5[$itsID][$pKode][$sClean] = 1;
                        if (!isset($items4[$itsID][$pKode])) {
                            $items4[$itsID][$pKode] = 0;
                        }
                        $items4[$itsID][$pKode]++;

                        $items5_sum[] = array(
                            "id" => $itsID,
                            "nama" => isset($dataProduk[$itsID]['nama']) ? $dataProduk[$itsID]['nama'] : "",
                            "name" => isset($dataProduk[$itsID]['nama']) ? $dataProduk[$itsID]['nama'] : "",
                            "produk_nama" => isset($dataProduk[$itsID]['nama']) ? $dataProduk[$itsID]['nama'] : "",
                            "placeID" => $cabang_id,
                            "gudangID" => $branchData[$cabang_id]['gudang_id'],
                            "produk_id" => $itsID,
                            "produk_kode" => isset($dataProduk[$itsID]['kode']) ? $dataProduk[$itsID]['kode'] : "",
                            "serial" => $sClean,
                            "jml" => 1,
                            "qty" => 1,
                            "produk_sku_part_nama" => $pKode,
                        );
                    }
                }
            }
        }
        //endregion builder items


        //region builder table in detil
        $tableIn_detail = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($tableIn_detailTmp as $col => $selectedRow) {
                $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }

        //                cekUngu('$tableIn_detail');
        //                arrPrintWebs($tableIn_detail);
        //endregion builder table in detil

        //region table in master values
        $tableIn_master_values = array(
            "harga" => "$subharga",
            "subtotal" => "$subtotal_opname",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "hpp" => "$subharga",
            "no_part" => "",
            "ppn" => "$ppn",
            "stok" => "1",
            "debet" => "$subtotal_nilai_debet",
            "kredit" => "$subtotal_nilai_kredit",
            "qty_selisih" => "",
            "qty_opname" => "$subtotal_opname",
            "qty_debet" => "$subtotal_qty_debet",
            "qty_kredit" => "$subtotal_qty_kredit",
        );

        //endregion table in master values

        //region build table in detil values
        $tableIn_detail_values = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($tableIn_detail_valuesTmp as $col => $selectedRow) {
                $tableIn_detail_values[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }

        //endregion build table in detil values

        //region build table receipDetailFields
        $receiptDetailFields = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($receiptDetailFieldsTmp as $col => $selectedRow) {
                $receiptDetailFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //        arrPrint($receiptDetailFields);
        //        matiHere();
        //endregion

        //region receiptSumFields
        $receiptSumFields = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($receiptSumFieldsTmp as $col => $selectedRow) {
                $receiptSumFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion

        $this->db->trans_start();

        if (sizeof($itemsData) > 0) {
            //region transaksional
            $modul_transaksi = "opname";
            //            matiHEre($this->modul);
            $buildTablesMaster = isset($this->configCore[$this->jenisTr]['components'][1]['master']) ? $this->configCore[$this->jenisTr]['components'][1]['master'] : array();
            $buildTablesDetail = isset($this->configCore[$this->jenisTr]['components'][1]['detail']) ? $this->configCore[$this->jenisTr]['components'][1]['detail'] : array();
            $addMasterTables = array(
                "rugilaba",
                "laba ditahan",
                "rugilaba lain lain",
            );
            foreach ($addMasterTables as $trek) {
                $buildTablesMaster[] = array(
                    "comName" => "RugiLaba",
                    "loop" => array(
                        "$trek" => .0,
                    ),
                );
            }
            if (sizeof($buildTablesMaster) > 0) {
                $bCtr = 0;
                foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                    $bCtr++;
                    $mdlName = $buildTablesMaster_specs['comName'];
                    if (substr($mdlName, 0, 1) == "{") {
                        $mdlName = trim($mdlName, "{");
                        $mdlName = trim($mdlName, "}");
                        $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                    }
                    else {
                        //                        cekkuning("TIDAK mengandung kurawal");
                    }

                    $mdlName = "Com" . $mdlName;
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                        foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                            if (substr($key, 0, 1) == "{") {
                                $oldParam = $buildTablesMaster_specs['loop'][$key];
                                unset($buildTablesMaster_specs['loop'][$key]);
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $main[$key], $key);
                                $buildTablesMaster_specs['loop'][$key] = $oldParam;
                            }
                        }
                    }
                    if (method_exists($m, "getTableNameMaster")) {
                        if (sizeof($m->getTableNameMaster())) {
                            $m->buildTables($buildTablesMaster_specs);
                        }
                    }
                }
            }
            if (sizeof($buildTablesDetail) > 0) {
                foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                    foreach ($items as $itemSpec) {
                        $mdlName = $buildTablesDetail_specs['comName'];
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                        }
                        $mdlName = "Com" . $mdlName;
                        cekbiru("model: $mdlName");
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                            foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesDetail_specs['loop'][$key];
                                    unset($buildTablesDetail_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $itemSpec[$key], $key);
                                    $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesDetail_specs);
                            }
                        }
                    }
                }
            }

            //region pre-processors (master)
            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['master'])) {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['master'] : array();
                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $subParams = array();

                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                $subParams['static'][$key] = $realValue;
                            }
                            if (!isset($subParams['static']["transaksi_id"])) {

                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                        }
                        $tmpOutParams[$cCtr] = $subParams;

                        $mdlName = "Pre" . ucfirst($comName);
                        $this->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
                            cekbiru("gotparams dari pre-proc $comName");
                            //                                            arrprint($gotParams);
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                foreach ($gotParams as $gateName => $gSpec) {
                                    if (isset($_SESSION[$cCode]['main'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode]['main'][$key] = $val;
                                            }
                                        }
                                    }
                                    //==inject gotParams to child gate
                                    if (isset($_SESSION[$cCode]['main'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode]['main'][$key] = $val;
                                            }
                                        }
                                    }
                                    //cekMerah("REBUILDING VALUES..");
                                    if (sizeof($itemNumLabels) > 0) {
                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                        foreach ($itemNumLabels as $key => $label) {
                                            //cekHere("$id === $key => $label");
                                            if (isset($_SESSION[$cCode]['main'][$key])) {
                                                $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }
                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);
            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion


            //region pre-processors (item)
            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail'])) {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "sub-preproc: $comName, initializing values <br>";
                        foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {

                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                            }
                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                            //                                            arrPrint($subParams);
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                $mdlName = "Pre" . ucfirst($comName);
                                $this->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);
                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }

                                if ($tobeExecuted) {
                                    $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $gotParams = $m->exec();
                                    cekmerah("gotparams dari pre-proc $comName");
                                    //                                                    arrprint($gotParams);
                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                        foreach ($gotParams as $gateName => $paramSpec) {
                                            cekBiru(":: getParams inject ke $gateName ::");
                                            if (!isset($_SESSION[$cCode][$gateName])) {
                                                $_SESSION[$cCode][$gateName] = array();
                                            }
                                            else {

                                            }

                                            foreach ($paramSpec as $id => $gSpec) {
                                                if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                    $_SESSION[$cCode][$gateName][$id] = array();
                                                }
                                                if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                            $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }

                                                //cekMerah("REBUILDING VALUES..");
                                                if (sizeof($itemNumLabels) > 0) {
                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                            $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }
                            }
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);

            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

            $this->midValidate();
            $this->unionValidate();
            //===finalisasi sebelum masuk tabel beneran

            //===isinya ada pembentukan nomor nota dll
            //region penomoran receipt
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);

            $counterForNumber = array($this->configCore[$this->jenisTr]['formatNota']);
            if (!in_array($counterForNumber[0], $this->configCore[$this->jenisTr]['counters'])) {
                die("Used number should be registered in 'counters' config as well");
            }
            echo "<div style='background:#ff7766;'>";
            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $main[$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                //                arrPrint($cParams);
                //                arrPrint($cValues[$i]);
                //                matiHere(__LINE__);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

            }
            echo "</div style='background:#ff7766;'>";
            //            matiHere(__LINE__);
            $stepNumber = 1;
            $tmpNomorNota = $paramSpec['paramString'];

            if (isset($this->configUi[$this->jenisTr]['steps'][2])) {
                $nextProp = array(
                    "num" => 2,
                    "code" => $this->configUi[$this->jenisTr]['steps'][2]['target'],
                    "label" => $this->configUi[$this->jenisTr]['steps'][2]['label'],
                    "groupID" => $this->configUi[$this->jenisTr]['steps'][2]['userGroup'],
                );
            }
            else {
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion
            //            matiHere(__LINE__);
            //region dynamic counters
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $cn->setModul($modul_transaksi);
            $cn->setStepCode($tCodeTargetJenisTransaksi);
            //            matiHere(__LINE__);
            $configCustomParams = $this->configCore[$this->jenisTr]['counters'];
            $configCustomParams[] = "stepCode";

            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $main[$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                    switch ($paramSpec['id']) {
                        case 0: //===counter type is new
                            $paramKeyRaw = print_r($cParams, true);
                            $paramValuesRaw = print_r($cValues[$i], true);
                            $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                            break;
                        default: //===counter to be updated
                            $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                            break;
                    }
                }
            }
            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);

            //region addition on master
            $addValues = array(
                'counters' => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "tail_number" => 1,
                "tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            );
            foreach ($addValues as $key => $val) {
                $tableIn_master[$key] = $val;
            }
            //endregion

            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
                "sub_tail_number" => 1,
                "sub_tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            );
            foreach ($tableIn_detail as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $tableIn_detail[$id][$key] = $val;
                }
            }
            //endregion

            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
            if (sizeof($tableIn_master) > 0) {
                $tableIn_master['status_4'] = 11;
                $tableIn_master['trash_4'] = 0;

                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $tableIn_master['cabang_id'] . "'");
                $insertID = $tr->writeMainEntries($tableIn_master);
                //cekHitam("nulis transaksi ".$this->db->last_query());
                // arrPrintHijau($tableIn_master);
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $tableIn_master);
                cekHitam("nulis entry point " . $this->db->last_query());
                $mongoList['main'] = array($insertID, $epID);

                $insertNum = $tableIn_master['nomer'];
                $main['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }

                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                );
                $arrInjectorsTarget = array(
                    "items",
                );
                foreach ($injectors as $key => $val) {
                    $main[$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        foreach ($items as $xis => $iSpec) {
                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                            if (isset($items[$id])) {
                                $items[$id][$key] = $val;
                            }
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $main['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
                    "step_name" => $this->configUi[$this->jenisTr]['steps'][1]['label'],
                    "group_code" => $this->configUi[$this->jenisTr]['steps'][1]['userGroup'],
                    "oleh_id" => "-100",
                    "oleh_nama" => "sys",
                    "keterangan" => $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh sys",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");
                //                cekHitam("nulis transaksi sign ".$this->db->last_query());
                $mongoList['sign'][] = $dwsign;
                $idHis = array(
                    $stepNumber => array(
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "counters" => $appliedCounters,
                        "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                    "next_step_num" => $nextProp['num'],
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],

                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                )) or die("Failed to update tr next-state!");

                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                );
                foreach ($addValues as $key => $val) {
                    $tableIn_master[$key] = $val;
                }

            }
            if (sizeof($tableIn_master_values) > 0) {
                if (isset($this->configCore[$this->jenisTr]['tableIn']['mainValues'])) {
                    $inserMainValues = array();
                    foreach ($this->configCore[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                        if (isset($tableIn_master_values[$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $tableIn_master_values[$key],
                            ));
                            $inserMainValues[] = $dd;
                            $mongoList['mainValues'][] = $dd;
                        }

                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
            }
            if (sizeof($main_add_values) > 0) {
                $inserMainValues = array();
                foreach ($main_add_values as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }

                //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_inputs) > 0) {
                foreach ($main_inputs as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
                //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_add_fields) > 0) {
                foreach ($main_add_fields as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
                //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_elements) > 0) {
                foreach ($main_elements as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                        "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                        "name" => $aSpec['name'],
                        "label" => isset($aSpec['label']) ? $aSpec['label'] : "",
                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",
                    ));
                    cekHitam("nulis transaksi element" . $this->db->last_query());
                    //==nebeng bikin inputLabels
                    $currentValue = "";
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $aSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $aSpec['value'];
                            break;
                    }
                    if (array_key_exists($elName, $relOptionConfigs)) {
                        if (isset($relOptionConfigs[$elName][$currentValue])) {
                            if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                    $inputLabels[$oValueName] = $oValSpec['label'];
                                    if (isset($oValSpec['auth'])) {
                                        if (isset($oValSpec['auth']['groupID'])) {
                                            $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }
                    }
                    //                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
            }
            if (sizeof($tableIn_detail) > 0) {
                $insertIDs = array();
                $insertDeIDs = array();
                foreach ($tableIn_detail as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $insertDeIDs[$insertID][] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertEpID;
                        $insertDeIDs[$epID][] = $insertEpID;
                        $mongoList['detail'][] = $insertEpID;
                    }
                    cekHitam("nulis transaksi data " . $this->db->last_query());
                    //                                cekUngu("LINE: " . __LINE__ . " <br> " . $this->db->last_query());
                }
                if (sizeof($insertIDs) == 0) {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }
                else {
                    $indexing_details = array();
                    foreach ($insertDeIDs as $key => $numb) {
                        $indexing_details[$key] = $numb;
                    }
                    foreach ($indexing_details as $k => $arrID) {
                        $arrBlob = blobEncode($arrID);
                        $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                        cekOrange($this->db->last_query());
                    }
                }
            }
            if (sizeof($tableIn_detail2) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail2 as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    $mongoList['detail'] = $insertIDs;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
                    }
                    //                                cekUngu($this->db->last_query());
                }
            }
            if (sizeof($tableIn_detail2_sum) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail2_sum as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;

                    if ($epID != 999) {
                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                }
                //                            cekOrange($this->db->last_query());
            }
            if (sizeof($tableIn_detail_rsltItems) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail_rsltItems as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                    //                                cekUngu($this->db->last_query());
                }
            }
            if (sizeof($tableIn_detail_values) > 0) {
                foreach ($tableIn_detail_values as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                        $insertIDs = array();
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                            if (isset($tableIn_detail[$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $tableIn_detail[$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                            //                                        cekLime($this->db->last_query());
                        }
                        if (sizeof($insertIDs) > 0) {
                            $arrBlob = blobEncode($insertIDs);
                            $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                }
            }
            if (sizeof($tableIn_detail_values2_sum) > 0) {
                foreach ($tableIn_detail_values2_sum as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $tableIn_detail2_sum[$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => $dSpec[$src],
                            ));
                            $insertIDs[] = $dd;
                            $mongoList['detailValues'][] = $dd;
                        }
                    }
                }
            }
            //endregion

            //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
            $steps = $this->configUi[$this->jenisTr]['steps'];

            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            $filterNeeded = false;
            //arrPrint($items);
            // matiHere(__LINE__);
            //====registri value-gate
            $baseRegistries = array(
                'main' => sizeof($main) > 0 ? $main : array(),
                'items' => sizeof($items) > 0 ? $items : array(),
                'items2' => sizeof($items2) > 0 ? $items2 : array(),
                'items2_sum' => sizeof($items2_sum) > 0 ? $items2_sum : array(),
                'items3' => sizeof($items3) > 0 ? $items3 : array(),
                'items3_sum' => sizeof($items3_sum) > 0 ? $items3_sum : array(),
                'items4' => sizeof($items4) > 0 ? $items4 : array(),
                'items5' => sizeof($items5) > 0 ? $items5 : array(),
                'items5_sum' => sizeof($items5_sum) > 0 ? $items5_sum : array(),
                'rsltItems' => sizeof($rsltItems) > 0 ? $rsltItems : array(),
                'rsltItems2' => sizeof($rsltItems2) > 0 ? $rsltItems2 : array(),
                'tableIn_master' => sizeof($tableIn_master) > 0 ? $tableIn_master : array(),
                'tableIn_detail' => sizeof($tableIn_detail) > 0 ? $tableIn_detail : array(),
                'tableIn_detail2_sum' => sizeof($tableIn_detail2_sum) > 0 ? $tableIn_detail2_sum : array(),
                'tableIn_detail_rsltItems' => sizeof($tableIn_detail_rsltItems) > 0 ? $tableIn_detail_rsltItems : array(),
                'tableIn_detail_rsltItems2' => sizeof($tableIn_detail_rsltItems2) > 0 ? $tableIn_detail_rsltItems2 : array(),
                'tableIn_master_values' => sizeof($tableIn_master_values) > 0 ? $tableIn_master_values : array(),
                'tableIn_detail_values' => sizeof($tableIn_detail_values) > 0 ? $tableIn_detail_values : array(),
                'tableIn_detail_values_rsltItems' => sizeof($tableIn_detail_values_rsltItems) > 0 ? $tableIn_detail_values_rsltItems : array(),
                'tableIn_detail_values_rsltItems2' => sizeof($tableIn_detail_values_rsltItems2) > 0 ? $tableIn_detail_values_rsltItems2 : array(),
                'tableIn_detail_values2_sum' => sizeof($tableIn_detail_values2_sum) > 0 ? $tableIn_detail_values2_sum : array(),
                'main_add_values' => sizeof($main_add_values) > 0 ? $main_add_values : array(),
                'main_add_fields' => sizeof($main_add_fields) > 0 ? $main_add_fields : array(),
                'main_elements' => sizeof($main_elements) > 0 ? $main_elements : array(),
                'main_inputs' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                'main_inputs_orig' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                "receiptDetailFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                "receiptSumFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1] : array(),
            );

            //===
            // arrPrint($baseRegistries);
            // $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            $mongRegID = $doWriteReg;
            // matiHere(__LINE__);
            //endregion
            validateAllBalances($cabang_id);
            //region writelog
            $this->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $tmpHData = array(
                "title" => $main['jenisTrName'],
                "sub_title" => "auto new transaction",
                "uid" => "-100",
                "uname" => "sys",
                "dtime" => date("Y-m-d H:i:s"),
                "transaksi_id" => $insertID,
                "deskripsi_old" => "",
                "deskripsi_new" => "",
                "jenis" => $this->jenisTr,
                "ipadd" => $_SERVER['REMOTE_ADDR'],
                "devices" => $_SERVER['HTTP_USER_AGENT'],
                "category" => "transaksi",
                "controller" => $this->uri->segment(1),
                "method" => $this->uri->segment(2),
                "url" => current_url(),
                "keterangan" => "File: " . __FILE__ . " | Line: " . __LINE__,
            );
            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //endregion
            //                            $a->updateData("id")

            //region update data yang sudah diambil
            $o->setFilters(array());
            $where = array(
                "id" => $fileID,
            );
            $updateOpname = array(
                "cli" => "1",
                "sync_cli_time" => dtimeNow("Y-m-d H:i"),
                "transaksi_id" => $insertID,
                "transaksi_no" => $insertNum,
            );

            $cekId = $o->updateData($where, $updateOpname) or die("Failed to update tr next-state!");
            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());

            //endregion

            //                            arrPrint($cekId);

        }
        else {
            cekOrange('tidak ada data untuk di eksekusi');
        }


        // arrPRint($branchData);
        // arrPRint($datas);
        // matiHere("belum commit -------------------- @" . __LINE__);
        // START OF COMPLETE REPEATED LOGIC
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekBiru("complit");

        echo "<script>";
        echo "  top.swal({";
        echo "    title: 'Upload & Validasi Selesai!',";
        echo "    text: 'Seluruh data stok opname dari file Excel berhasil diproses dan disimpan.',";
        echo "    type: 'success',";
        echo "    timer: 2000,";
        echo "    showConfirmButton: false,";
        echo "    allowOutsideClick: false,";
        echo "    allowEscapeKey: false";
        echo "  });";
        echo "  setTimeout(function(){ top.location.reload(); }, 2000);";
        echo "</script>";


    }

    public function executeOpname__()
    {
        $this->load->model("Coms/ComRekeningPembantuProduk");
        $this->load->model("Mdls/MdlOpname_xls");
        $this->load->model("MdlTransaksi");
        $this->load->library('PHPExcel');
        $date_now = dtimeNow();
        $alerts = array(
            "title" => "Sedang Memproses Data Opname...",
            "html" => "<div style='text-align:center;padding:15px 0;'><i class='fa fa-spinner fa-spin fa-3x' style='margin-bottom:15px;color:#337ab7;'></i><p style='font-size:14px;color:#333;margin-top:10px;line-height:1.6;'>Sistem sedang membaca dan memvalidasi file Excel ke database.<br><b style='color:#d9534f;'><i class='fa fa-lock'></i> Mohon jangan menutup atau me-refresh halaman ini sampai proses selesai.</b></p><div class='text-muted' style='font-size:11px;margin-top:8px;'>Waktu mulai: $date_now</div></div>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
            "allowEscapeKey" => false,
        );
        echo swalAlert($alerts);
        // END OF COMPLETE REPEATED LOGIC

        $param = $this->uri->segment(4);

        $file_id = isset($_GET['file_id']) ? $_GET['file_id'] : "0";
        // $this->jenisTr = "1119";//ditembak untuk auto generate
        $jenis_tr = $this->jenisTr = isset($_GET['jenis_tr']) ? $_GET['jenis_tr'] : matiHere("tidak mendapatkan kiriman jenis_tr (get)");
        // $jenis_tr = $this->jenisTr = isset($_GET['jenis_tr']) ? $_GET['jenis_tr'] : 1119;
        $this->tableInConfig = isset($this->configUi[$this->jenisTr]['tableIn']) ? $this->configUi[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->configUi[$this->jenisTr]['tableIn_static']) ? $this->configUi[$this->jenisTr]['tableIn_static'] : array();

        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $o = new MdlOpname_xls();
        $o->setFilters(array());
        $o->addFilter("cli='0'");
        $o->addFilter("trash='0'");
        if ($file_id > 0) {
            $o->addFilter("id='$file_id'");
        }
        else {
            $querystring = ($_SERVER['REDIRECT_QUERY_STRING']);
            cekBiru("tidak ada kiriman file_id dr " . uri_string() . "?$querystring");
        }
        // filter belum membaca cabang_id dan gudang_id
        //        $o->shortBy("id","asc");
        $this->db->limit(1);
        $tmp = $o->lookUpAll()->result();
        showLast_query("lime");
        // arrPrint($tmp);
        $filePath = $tmp[0]->full_path;
        $oleh_id = $tmp[0]->oleh_id;
        $oleh_nama = $tmp[0]->oleh_nama;
        $cabang_id = $tmp[0]->cabang_id;
        $fileID = $tmp[0]->id;
        cekBiru("$file_id:: $filePath");

        $ext = str_replace(".", "", $tmp[0]->file_ext);
        //        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";
        $loadexcel = $this->xlsx->load($filePath);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;

        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $yourArray = array_map('nestedLowercase', $row);
                $headers[$num] = $yourArray;
            }
            $num++;
        }
        $kolom = $headers[$data_header];
        $koloms = array_filter($kolom);

        /* ---------------------------------
         * arange adta excel per row menjadi key => value
         * ---------------------------*/
        arrPrint($koloms);
        // arrPrint($sheet);
        $datas = array();
        $produk_id = array();
        $dt_gdid_0 = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {

                foreach ($koloms as $kolom => $kalias) {
                    // $xl_value = strval($row[$kolom]);
                    $xl_value = str_replace("'", "", $row[$kolom]);
                    $xlsValue = $xl_value;
                    // cekBiru("$kalias: $xlsValue");

                    if (strlen($kalias) > 0) {
                        $rows[$kalias] = (string)$xlsValue;
                    }
                }

                $datas[$rows['pid']]['qty'] = $rows['stok riil'];
                $datas[$rows['pid']]['serial'] = isset($rows['serial number']) ? $rows['serial number'] : (isset($rows['serial']) ? $rows['serial'] : "");
                $produk_id[] = $rows['pid'];
                if (sizeof(array_filter($rows))) {
                    // arrPrintKuning($rows);
                    // matiHere(__LINE__);
                    $dt_cbid = $rows['cid'];
                    $dt_gdid_0[$rows['gid']] = $rows['gid'];
                    $dt_jenis = $rows['jenis'];
                }
            }
            $numrow++;
        }
        $gd_ids = array_filter(array_keys($dt_gdid_0));
        $dt_gdid = $gd_ids['0'];
        //         arrPrintHijau($dt_gdid);
        //         // arrPrintPink([0]);
        // matiHere(__LINE__);
        /* -----------------------------------------------------------------------------------------
       * dashboard_opname
       * -----------------------------------------------------------------------------------------*/
        $op_condites = array(
            "jenis" => "$param",
            "gudang_id" => $dt_gdid
        );
        $op_datas = $this->cekOpnameAktive($op_condites);
        $jml_upload = sizeof($op_datas);
        cekOrange("jml opname_data: $jml_upload jn: $dt_jenis did:: $dt_gdid cid: $dt_cbid");
        showLast_query("kuning");
        // mati_disini(__LINE__);
        if ($jml_upload == 1) {
            $db_opnames = $op_datas[0];
            // arrPrintHijau($db_opnames);
            $db_id = $db_opnames->id;
            $db_gudang_id = $db_opnames->gudang_id;
            $db_cabang_id = $db_opnames->cabang_id;
            $db_jenis = $db_opnames->jenis;
            $db_opname = "dashboard_opname";

            /*---validasi data yg diupload---*/
            if ($db_cabang_id != $dt_gdid) {

            }
            elseif ($db_jenis != $dt_jenis) {

            }
            elseif ($db_cabang_id != $dt_cbid) {

            }

            /* update ke data opname */
            $this->load->model("Mdls/MdlDashboardOpnameData");
            $dopd = new MdlDashboardOpnameData();

            foreach ($datas as $produk_exc_id => $data_exc) {
                $jml_exc = $data_exc['qty'];
                $db_condites = array(
                    "dashboard_opname_id" => $db_id,
                    "produk_id" => $produk_exc_id,
                );
                $db_datas = array(
                    "jml_stok_opname" => $jml_exc,
                );
                $dopd->updateData($db_condites, $db_datas);
                // showLast_query("merah");
                // break;
            }
            cekMerah("dashboard_opname_data berhasil diupdate");

            /*--membuat log opname--*/
            $this->load->model("Mdls/MdlDashboardOpname");
            $dop = new MdlDashboardOpname();

            $mdb_condites = array(
                "id" => $db_id,
            );
            $mdb_datas = array(
                "done_id" => my_id(),
                "done_nama" => my_name(),
                "dtime_done" => dtimeNow(),
            );
            $dop->updateData($mdb_condites, $mdb_datas);
            showLast_query("biru");
        }
        elseif (($jml_upload > 1)) {
            matiHere("ada problem di server upload opname harap konfirmasikan ke web suport " . __LINE__);
        }
        else {
            matiHere("identitas file tidak ditemukan, harap konfirmasikan ke web suport " . __LINE__);
        }
        // arrPrintPink($datas); //xls
        //        arrPrint($dataRekening);// rekening

        // matiHere(__LINE__);
        /*---------------------------------------------------------------------------------uploader*/

        cekOrange("jn: $dt_jenis did:: $dt_gdid cid: $dt_cbid");
        $this->load->model("Mdls/MdlGudangDefault_center");
        $this->load->model("Mdls/MdlCabang");
        $c = new MdlCabang();
        $g = new MdlGudangDefault_center();

        $c->addFilter("id='$cabang_id'");
        $cabang_data = $c->lookupAll()->result();

        $cabangData = array();
        $branchData = array();
        foreach ($cabang_data as $cabData) {
            $cabangData[$cabData->id] = $cabData->nama;
        }

        if ($dt_gdid == "-1") {
            $g->addFilter("cabang_id='$cabang_id'");
            $tempBranch = $g->lookupAll()->result();
            // showLast_query("kuning");
            foreach ($tempBranch as $tempBranchData) {
                $branchData[$cabang_id] = array(
                    "gudang_id" => $tempBranchData->id,
                    "gudang_nama" => $tempBranchData->name,
                );
            }
        }
        else {
            $branchData[$cabang_id] = array(
                "gudang_id" => $dt_gdid,
                "gudang_nama" => "",
            );
        }

        arrPrintPink($branchData);// rekening

        //region builder session data trasnksional


        //builder data rekening persdiaan dan data produk
        $selectFields = array("extern_id", "qty_debet", "harga_avg");
        //        $itemFields = $this->configUi['1119'][''];
        $p = new ComRekeningPembantuProduk();
        $p->addFilter("extern_id in ('" . implode("','", $produk_id) . "')'");
        $p->addFilter("periode='forever'");
        $p->addFilter("cabang_id='$cabang_id'");
        $p->addFilter("gudang_id='$dt_gdid'");
        $temPersedian = $p->lookUpall()->result();
        //        cekLime($this->db->last_query());
        $dataRekening = array();
        foreach ($temPersedian as $temPersedian0) {
            $tmpData = array();
            foreach ($selectFields as $fields) {
                $tmpData[$fields] = $temPersedian0->$fields;
            }
            $dataRekening[$temPersedian0->extern_id] = $tmpData;
        }
        //        arrPrint($dataRekening);
        //endregion


        //region price list
        $this->load->model("Mdls/MdlHargaProduk");
        $h = new MdlHargaProduk();
        $h->addFilter("jenis='produk'");
        $h->addFilter("jenis_value='hpp'");
        $h->addFilter("status='1'");
        $h->addFilter("cabang_id='$cabang_id'");
        $h->addFilter("produk_id in ('" . implode("','", $produk_id) . "')'");
        $tmpPrice = $h->lookUpAll()->result();
        // cekHitam($this->db->last_query());
        // arrPrint($tmpPrice);
        $arrPrice = array();
        if (sizeof($tmpPrice) > 0) {
            foreach ($tmpPrice as $priceData) {
                $arrPrice[$priceData->cabang_id][$priceData->produk_id] = $priceData->nilai;
            }
        }
        $priceList = $arrPrice[$cabang_id];
        //        cekHitam($this->db->last_query());
        //        arrPrint($arrPrice);
        //        arrPrint($tmpPrice);
        //endregion
        //matiHere();
        //region build data produk
        $selectFields = array("id" => "id", "nama" => "nama", "kode" => "kode", "produk_kode" => "kode", "no_part" => "no_part", "satuan" => "satuan");
        $this->load->model("Mdls/MdlProduk");
        $pr = new MdlProduk();
        $pr->addFilter("id in ('" . implode("','", $produk_id) . "')'");
        //        $p->addFilter("periode='forever'");
        //        $p->addFilter("cabang_id='$cabang_id'");
        $temProduk = $pr->lookUpall()->result();
        //        cekLime($this->db->last_query());
        $dataProduk = array();
        foreach ($temProduk as $temProduk_0) {
            $tmpData = array();
            foreach ($selectFields as $k => $fields) {
                $tmpData[$k] = $temProduk_0->$fields;
            }
            $dataProduk[$temProduk_0->id] = $tmpData;
        }
        //arrPrint($dataProduk);
        //endregion

        //region array builder transaction
        $mainTmp = array(
            "dummyElement" => "yes",
            "dummyElement__label" => "yes",
            "dummyElement__name" => "yes",
            "olehID" => "-100",
            "olehName" => "sys",
            "placeID" => "-1",
            "placeName" => "pusat",
            "divID" => $this->divId,
            "divName" => "default",
            "cabangID" => "-1",
            "cabangName" => "pusat",
            "gudangID" => "-1",
            "gudangName" => "default center warehouse",
            "jenisTr" => "$jenis_tr",
            "jenisTrMaster" => "$jenis_tr",
            "jenisTrTop" => $jenis_tr . "r",
            "jenisTrName" => "STOCK OPNAME",
            "stepNumber" => "1",
            "stepCode" => $jenis_tr . "r",
            "dtime" => dtimeNow("Y-m-d H:i"),
            "fulldate" => dtimeNow("Y-m-d"),
            "harga" => "0",
            "subtotal" => "0",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "no_part" => "0",
            "ppn" => "0",
            "stok" => "1",
            "debet" => "0",
            "kredit" => "0",
            "hpp" => "0",
            "qty_selisih" => "0",
            "jenis" => $jenis_tr . "r",
            "transaksi_jenis" => $jenis_tr . "r",
            "next_step_code" => $jenis_tr . "ro",
            "next_group_code" => "c_holding",
            "step_number" => "1",
            "step_current" => "1",
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            "new_sisa" => "0",
            "qty_opname" => "0",
            "qty_debet" => "0",
            "qty_kredit" => "0",
        );
        $itemsTmp = array(
            "handler" => "Selectors/_processSelectProduct",
            "id" => "id",
            "jml" => "jml",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "satuan" => "satuan",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "nama" => "nama",
            "produk_kode" => "produk_kode",
            "no_part" => "no_part",
            "label" => "0",
            "ppn" => "0",
            "stok" => "stok",
            "debet" => "debet",
            "kredit" => "kredit",
            "hpp" => "hpp",
            "qty_selisih" => "qty_selisih",
            "qty" => "qty",
            "name" => "name",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "sub_subtotal",
            "sub_discount_persen" => "sub_discount_persen",
            "sub_discount_qty" => "0",
            "sub_ppn" => "0",
            "sub_stok" => "sub_stok",
            "sub_debet" => "sub_debet",
            "sub_kredit" => "sub_kredit",
            "sub_hpp" => "sub_hpp",
            "sub_qty_selisih" => "sub_qty_selisih",
            "olehID" => "-100",
            "olehName" => "sys",
            "placeID" => "cabang_id",
            "placeName" => 'placeName',
            "cabangID" => "cabangID",
            "cabangName" => "cabangName",
            "gudangID" => "gudangID",
            "gudangName" => "gudangName",
            "jenisTr" => $jenis_tr,
            "next_substep_code" => $jenis_tr . "ro",
            "next_subgroup_code" => "c_holding",
            "sub_step_number" => "1",
            "sub_step_current" => "1",
            "nilai_bayar" => "",
            "new_sisa" => "0",
            "sub_new_sisa" => "0",
            "qty_opname" => "qty_opname",
            "qty_debet" => "qty_debet",
            "qty_kredit" => "qty_kredit",
            "sub_qty_opname" => "sub_qty_opname",
            "sub_qty_debet" => "sub_qty_debet",
            "sub_qty_kredit" => "sub_qty_kredit",
            "referensi_id" => "referensi_id",
            "referensi_jenis" => "referensi_jenis",


        );
        $items2 = array();
        $items2_sum = array();
        $rsltItems = array();
        $rsltItems2 = array();
        $tableIn_master = array(
            "trash" => "0",
            "jenis_master" => $jenis_tr,
            "jenis_top" => $jenis_tr . "r",
            "jenis" => $jenis_tr . "r",
            "jenis_label" => "STOCK OPNAME",
            "div_id" => $this->divId,
            "div_nama" => "default",
            "dtime" => dtimeNow("Y-m-d H:i"),
            "fulldate" => dtimeNow("Y-m-d"),
            "oleh_id" => "-100",
            "oleh_nama" => "sys",
            "cabang_id" => $cabang_id,
            "cabang_nama" => my_cabang_nama(),
            "transaksi_jenis" => $jenis_tr . "r",
            "gudangID" => $branchData[$cabang_id]['gudang_id'],
            "gudangName" => $branchData[$cabang_id]['gudang_nama'],
            "gudang_id" => $branchData[$cabang_id]['gudang_id'],
            "gudang_nama" => $branchData[$cabang_id]['gudang_nama'],
            "next_step_code" => $jenis_tr . "ro",
            "next_group_code" => "c_holding",
            "step_number" => "1",
            "step_current" => "1",
        );
        $tableIn_detailTmp = array(
            "produk_id" => "id",
            "produk_kode" => "produk_kode",
            "produk_label" => "",
            "produk_nama" => "nama",
            "produk_ord_jml" => "1",
            "produk_ord_hrg" => "harga",
            "hpp" => "hpp",
            "satuan" => "satuan",
            "note" => "",
            "reference" => "",
            "trash" => "0",
            "produk_jenis" => "produk",
            "valid_qty" => "1",
        );
        $tableIn_detail2_sum = array();
        $tableIn_detail_rsltItems = array();
        $tableIn_detail_rsltItems2 = array();
        $tableIn_master_valuesTmp = array(
            "divID" => "4",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "hpp" => "hpp",
            "no_part" => "",
            "ppn" => "ppn",
            "stok" => "1",
            "debet" => "debet",
            "kredit" => "kredit",
            "qty_selisih" => "qty_selisih",
            "qty_opname" => "qty_opname",
            "qty_debet" => "qty_debet",
            "qty_kredit" => "qty_kredit",
        );
        $tableIn_detail_valuesTmp = array(
            "jml" => "jml",
            "harga" => "harga",
            "subtotal" => "subtotal",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "ppn" => "0",
            "stok" => "stok",
            "debet" => "debet",
            "kredit" => "kredit",
            "hpp" => "hpp",
            "qty_selisih" => "qty_selisih",
            "qty" => "qty",
            "sub_harga" => "sub_harga",
            "sub_subtotal" => "sub_subtotal",
            "sub_discount_persen" => "0",
            "sub_discount_qty" => "0",
            "sub_ppn" => "0",
            "sub_stok" => "sub_stok",
            "sub_debet" => "sub_debet",
            "sub_kredit" => "sub_kredit",
            "sub_hpp" => "sub_hpp",
            "sub_qty_selisih" => "sub_qty_selisih",
            "sub_new_sisa" => "0",
            "qty_opname" => "qty_opname",
            "qty_debet" => "qty_debet",
            "qty_kredit" => "qty_kredit",
            "sub_qty_opname" => "sub_qty_opname",
            "sub_qty_debet" => "sub_qty_debet",
            "sub_qty_kredit" => "sub_qty_kredit",
        );
        $tableIn_detail_values_rsltItemsTmp = array();
        $tableIn_detail_values_rsltItems2Tmp = array();
        $tableIn_detail_values2_sumTmp = array();
        $tableIn_detail2 = array();
        $main_add_values = array();
        $main_add_fields = array();
        $main_elements = array(
            "dummyElement" => Array(
                "elementType" => "dataModel",
                "name" => "dummyElement",
                "label" => "auto-validation",
                "key" => "yes",
                "labelSrc" => "name",
                "labelValue" => "yes",
                "mdl_name" => "MdlDummyElement",
                "contents" => "YToxOntzOjQ6Im5hbWUiO3M6MzoieWVzIjt9",
                "contents_intext" => "",
            ),
        );
        $main_inputs = array();
        $main_inputs_orig = array();
        $receiptDetailFieldsTmp = array(
            "produk_nama" => "name",
        );
        $receiptSumFieldsTmp = array(
            "harga" => "total amount",
        );
        $receiptDetailFields2 = array();
        $receiptSumFields2 = array();
        $tableIn_detail_values2_sum = array();
        $items3 = array();
        $items3_sum = array();
        $tableIn_detail_values_rsltItems = array();
        $tableIn_detail_values_rsltItems2 = array();
        //endregion


        $subtotal_nilai_debet = 0;
        $subtotal_nilai_kredit = 0;
        $subtotal_qty_debet = 0;
        $subtotal_qty_kredit = 0;
        $subtotal_opname = 0;
        $subharga = 0;
        $itemsData = array();
        foreach ($dataProduk as $pID => $pData) {

            $harga = isset($priceList[$pID]) && $priceList[$pID] > 0 ? $priceList[$pID] : 1;
            $ppn = $harga * 10 / 100;
            $sub_harga = $ppn + $harga;
            $qty_riil = $qty_opname = $datas[$pID]["qty"];
            $qty_syst = isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0;

            $selisih = isset($dataRekening[$pID]["qty_debet"]) ? ($dataRekening[$pID]["qty_debet"] - $datas[$pID]["qty"]) : (0 - $datas[$pID]["qty"]);

            if ($selisih < 0) {
                $qty_debet = $selisih * -1;
                $qty_kredit = 0;
                $selisih_qty = $qty_debet;
                $debet_nilai = $qty_debet * $harga;
                $kredit_nilai = 0;
            }
            else {
                $qty_debet = 0;
                $qty_kredit = $selisih;
                $selisih_qty = $qty_kredit;
                $debet_nilai = 0;
                $kredit_nilai = $qty_debet * $harga;
            }

            // cekHijau("$pID :: $selisih ||sys: $qty_syst ||rii: $qty_riil || $qty_debet || $qty_kredit");
            // if($qty_kredit == $qty_debet){
            //
            //     break;
            // }
            $subharga = $debet_nilai + $kredit_nilai;
            $subtotal_nilai_debet += $debet_nilai;
            $subtotal_nilai_kredit += $kredit_nilai;
            $subtotal_qty_debet += $qty_debet;
            $subtotal_qty_kredit += $qty_kredit;
            $subtotal_opname += $qty_riil;

            $itemsData[$pID] = array(
                "id" => "$pID",
                "jml" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                "harga" => $harga,
                "subtotal" => $sub_harga,
                "satuan" => $pData["satuan"],
                "discount_persen" => 0,
                "discount_qty" => 0,
                "hpp" => $harga,
                "nama" => $pData["nama"],
                "produk_kode" => $pData["produk_kode"],
                "no_part" => $pData["no_part"],
                "label" => 0,
                "ppn" => $ppn,
                "stok" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                "debet" => $debet_nilai,
                "kredit" => $kredit_nilai,
                "qty_selisih" => $selisih,
                "qty" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                "name" => $pData["nama"],
                "sub_harga" => $harga,
                "sub_subtotal" => $harga,
                "sub_discount_persen" => 0,
                "sub_discount_qty" => 0,
                "sub_hpp" => $harga,
                "sub_ppn" => $ppn,
                "sub_stok" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                "sub_debet" => $debet_nilai,
                "sub_kredit" => "0",
                "sub_qty_selisih" => $selisih,
                "olehID" => "-100",
                "olehName" => "sys",
                "pihakID" => $cabang_id,
                "pihakName" => $cabangData[$cabang_id],
                "placeID" => $cabang_id,
                "placeName" => $cabangData[$cabang_id],
                "cabangID" => $cabang_id,
                "cabangName" => $cabangData[$cabang_id],
                "gudangID" => $branchData[$cabang_id]['gudang_id'],
                "gudangName" => $branchData[$cabang_id]['gudang_nama'],
                "jenisTr" => $jenis_tr,
                "next_substep_code" => $jenis_tr . "ro",
                "next_subgroup_code" => "c_holding",
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "nilai_bayar" => "",
                "new_sisa" => 0,
                "sub_new_sisa" => 0,
                "qty_opname" => $qty_riil,
                // ------------------
                // "qty_debet"           => $selisih_qty,
                "qty_debet" => $qty_debet,
                "qty_kredit" => $qty_kredit,
                // ------------------
                "sub_qty_opname" => $qty_riil,
                // "sub_qty_debet"       => $selisih_qty,
                "sub_qty_debet" => $qty_debet,
                "sub_qty_kredit" => $qty_kredit,
                "referensi_id" => $db_id,
                "referensi_jenis" => $db_opname,
            );
            // arrPrint($pData);
        }

        // arrPrint($itemsData);
        // matiHere(__LINE__);
        //region builder items

        //region builder main
        // main untuk mode gabungan
        $main = array(
            "dummyElement" => "yes",
            "dummyElement__label" => "yes",
            "dummyElement__name" => "yes",
            "olehID" => "-100",
            "olehName" => "sys",
            "placeID" => $cabang_id,
            "placeName" => $cabangData[$cabang_id],
            "divID" => $this->divId,
            "divName" => "default",
            "cabangID" => $cabang_id,
            "cabangName" => $cabangData[$cabang_id],
            "gudangID" => $branchData[$cabang_id]['gudang_id'],
            "gudangName" => $branchData[$cabang_id]['gudang_nama'],
            "jenisTr" => "$jenis_tr",
            "jenisTrMaster" => "$jenis_tr",
            "jenisTrTop" => $jenis_tr . "r",
            "jenisTrName" => "STOCK OPNAME",
            "stepNumber" => "1",
            "stepCode" => $jenis_tr . "r",
            "dtime" => dtimeNow("Y-m-d H:i"),
            "fulldate" => dtimeNow("Y-m-d"),
            "harga" => $subharga,
            "subtotal" => $subharga,
            "discount_persen" => "0",
            "discount_qty" => "0",
            "no_part" => "0",
            "ppn" => "$ppn",
            "stok" => "1",
            "debet" => "$subtotal_nilai_debet",
            "kredit" => "$subtotal_nilai_kredit",
            "hpp" => $subharga,
            "qty_selisih" => "",
            "jenis" => $jenis_tr . "r",
            "transaksi_jenis" => $jenis_tr . "r",
            "next_step_code" => $jenis_tr . "ro",
            "next_group_code" => "c_holding",
            "step_number" => "1",
            "step_current" => "1",
            "longitude" => "",
            "lattitude" => "",
            "accuracy" => "",
            //            "new_sisa" => "0",
            "qty_opname" => $subtotal_opname,
            "qty_debet" => $subtotal_qty_debet,
            "qty_kredit" => $subtotal_qty_kredit,
            "referensi_id" => $db_id,
            "referensi_jenis" => $db_opname,
        );
        //endregion builder main
        // arrPrint($main);
        // matiHere(__LINE__);
        //region builder items
        $items = array();
        $items4 = array();
        $items5 = array();
        $items5_sum = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($itemsTmp as $col => $selectedRow) {
                $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }

            // Ekstrak serial number yang dilampirkan dari excel jika ada
            if (isset($datas[$itsID]['serial']) && trim($datas[$itsID]['serial']) !== "" && trim($datas[$itsID]['serial']) !== "-") {
                $rawSerials = preg_split('/[\r\n,;|]+/', trim($datas[$itsID]['serial']));
                $pKode = isset($dataProduk[$itsID]['kode']) && $dataProduk[$itsID]['kode'] != "" ? $dataProduk[$itsID]['kode'] : (isset($dataProduk[$itsID]['nama']) ? $dataProduk[$itsID]['nama'] : "SERIAL");
                foreach ($rawSerials as $sRaw) {
                    $sClean = trim($sRaw);
                    if ($sClean !== "") {
                        if (!isset($items5[$itsID][$pKode])) {
                            $items5[$itsID][$pKode] = array();
                        }
                        $items5[$itsID][$pKode][$sClean] = 1;
                        if (!isset($items4[$itsID][$pKode])) {
                            $items4[$itsID][$pKode] = 0;
                        }
                        $items4[$itsID][$pKode]++;

                        $items5_sum[] = array(
                            "id" => $itsID,
                            "nama" => isset($dataProduk[$itsID]['nama']) ? $dataProduk[$itsID]['nama'] : "",
                            "name" => isset($dataProduk[$itsID]['nama']) ? $dataProduk[$itsID]['nama'] : "",
                            "produk_nama" => isset($dataProduk[$itsID]['nama']) ? $dataProduk[$itsID]['nama'] : "",
                            "placeID" => $cabang_id,
                            "gudangID" => $branchData[$cabang_id]['gudang_id'],
                            "produk_id" => $itsID,
                            "produk_kode" => isset($dataProduk[$itsID]['kode']) ? $dataProduk[$itsID]['kode'] : "",
                            "serial" => $sClean,
                            "jml" => 1,
                            "qty" => 1,
                            "produk_sku_part_nama" => $pKode,
                        );
                    }
                }
            }
        }
        //endregion builder items


        //region builder table in detil
        $tableIn_detail = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($tableIn_detailTmp as $col => $selectedRow) {
                $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }

        //                cekUngu('$tableIn_detail');
        //                arrPrintWebs($tableIn_detail);
        //endregion builder table in detil

        //region table in master values
        $tableIn_master_values = array(
            "harga" => "$subharga",
            "subtotal" => "$subtotal_opname",
            "discount_persen" => "0",
            "discount_qty" => "0",
            "hpp" => "$subharga",
            "no_part" => "",
            "ppn" => "$ppn",
            "stok" => "1",
            "debet" => "$subtotal_nilai_debet",
            "kredit" => "$subtotal_nilai_kredit",
            "qty_selisih" => "",
            "qty_opname" => "$subtotal_opname",
            "qty_debet" => "$subtotal_qty_debet",
            "qty_kredit" => "$subtotal_qty_kredit",
        );

        //endregion table in master values

        //region build table in detil values
        $tableIn_detail_values = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($tableIn_detail_valuesTmp as $col => $selectedRow) {
                $tableIn_detail_values[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }

        //endregion build table in detil values

        //region build table receipDetailFields
        $receiptDetailFields = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($receiptDetailFieldsTmp as $col => $selectedRow) {
                $receiptDetailFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //        arrPrint($receiptDetailFields);
        //        matiHere();
        //endregion

        //region receiptSumFields
        $receiptSumFields = array();
        foreach ($itemsData as $itsID => $itsData) {
            foreach ($receiptSumFieldsTmp as $col => $selectedRow) {
                $receiptSumFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
            }
        }
        //endregion

        $this->db->trans_start();

        if (sizeof($itemsData) > 0) {
            //region transaksional
            $buildTablesMaster = isset($this->configCore[$this->jenisTr]['components'][1]['master']) ? $this->configCore[$this->jenisTr]['components'][1]['master'] : array();
            $buildTablesDetail = isset($this->configCore[$this->jenisTr]['components'][1]['detail']) ? $this->configCore[$this->jenisTr]['components'][1]['detail'] : array();
            $addMasterTables = array(
                "rugilaba",
                "laba ditahan",
                "rugilaba lain lain",
            );
            foreach ($addMasterTables as $trek) {
                $buildTablesMaster[] = array(
                    "comName" => "RugiLaba",
                    "loop" => array(
                        "$trek" => .0,
                    ),
                );
            }
            if (sizeof($buildTablesMaster) > 0) {
                $bCtr = 0;
                foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                    $bCtr++;
                    $mdlName = $buildTablesMaster_specs['comName'];
                    if (substr($mdlName, 0, 1) == "{") {
                        $mdlName = trim($mdlName, "{");
                        $mdlName = trim($mdlName, "}");
                        $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                    }
                    else {
                        //                        cekkuning("TIDAK mengandung kurawal");
                    }

                    $mdlName = "Com" . $mdlName;
                    $this->load->model("Coms/" . $mdlName);
                    $m = new $mdlName();
                    if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                        foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                            if (substr($key, 0, 1) == "{") {
                                $oldParam = $buildTablesMaster_specs['loop'][$key];
                                unset($buildTablesMaster_specs['loop'][$key]);
                                $key = trim($key, "{");
                                $key = trim($key, "}");
                                $key = str_replace($key, $main[$key], $key);
                                $buildTablesMaster_specs['loop'][$key] = $oldParam;
                            }
                        }
                    }
                    if (method_exists($m, "getTableNameMaster")) {
                        if (sizeof($m->getTableNameMaster())) {
                            $m->buildTables($buildTablesMaster_specs);
                        }
                    }
                }
            }
            if (sizeof($buildTablesDetail) > 0) {
                foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                    foreach ($items as $itemSpec) {
                        $mdlName = $buildTablesDetail_specs['comName'];
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                        }
                        $mdlName = "Com" . $mdlName;
                        cekbiru("model: $mdlName");
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                            foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesDetail_specs['loop'][$key];
                                    unset($buildTablesDetail_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $itemSpec[$key], $key);
                                    $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesDetail_specs);
                            }
                        }
                    }
                }
            }

            //region pre-processors (master)
            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['master'])) {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['master'] : array();
                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                        $subParams = array();

                        if (isset($tComSpec['static'])) {
                            foreach ($tComSpec['static'] as $key => $value) {
                                $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                $subParams['static'][$key] = $realValue;
                            }
                            if (!isset($subParams['static']["transaksi_id"])) {

                            }
                            $subParams['static']["fulldate"] = date("Y-m-d");
                            $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                            $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                        }
                        $tmpOutParams[$cCtr] = $subParams;

                        $mdlName = "Pre" . ucfirst($comName);
                        $this->load->model("Preprocs/" . $mdlName);
                        $m = new $mdlName($resultParams);

                        if (sizeof($tmpOutParams[$cCtr]) > 0) {
                            $tobeExecuted = true;
                        }
                        else {
                            $tobeExecuted = false;
                        }

                        if ($tobeExecuted) {
                            $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                            $gotParams = $m->exec();
                            cekbiru("gotparams dari pre-proc $comName");
                            //                                            arrprint($gotParams);
                            if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                foreach ($gotParams as $gateName => $gSpec) {
                                    if (isset($_SESSION[$cCode]['main'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode]['main'][$key] = $val;
                                            }
                                        }
                                    }
                                    //==inject gotParams to child gate
                                    if (isset($_SESSION[$cCode]['main'])) {
                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                            foreach ($gSpec as $key => $val) {
                                                $_SESSION[$cCode]['main'][$key] = $val;
                                            }
                                        }
                                    }
                                    //cekMerah("REBUILDING VALUES..");
                                    if (sizeof($itemNumLabels) > 0) {
                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                        foreach ($itemNumLabels as $key => $label) {
                                            //cekHere("$id === $key => $label");
                                            if (isset($_SESSION[$cCode]['main'][$key])) {
                                                $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }
                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);
            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion


            //region pre-processors (item)
            if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail'])) {
                $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                echo "ITEM NUM LABELS";

                if (sizeof($iterator) > 0) {
                    foreach ($iterator as $cCtr => $tComSpec) {
                        $comName = $tComSpec['comName'];
                        $srcGateName = $tComSpec['srcGateName'];
                        $srcRawGateName = $tComSpec['srcRawGateName'];
                        echo "sub-preproc: $comName, initializing values <br>";
                        foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                            $tmpOutParams[$cCtr] = array();
                            $id = $xid;
                            $subParams = array();
                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {

                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                            }
                            cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                            //                                            arrPrint($subParams);
                            if (sizeof($subParams) > 0) {
                                $tmpOutParams[$cCtr][] = $subParams;
                                $comName = $tComSpec['comName'];
                                $srcGateName = $tComSpec['srcGateName'];
                                $srcRawGateName = $tComSpec['srcRawGateName'];
                                $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                $mdlName = "Pre" . ucfirst($comName);
                                $this->load->model("Preprocs/" . $mdlName);
                                $m = new $mdlName($resultParams);
                                if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                    $tobeExecuted = true;
                                }
                                else {
                                    $tobeExecuted = false;
                                }

                                if ($tobeExecuted) {
                                    $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                    $gotParams = $m->exec();
                                    cekmerah("gotparams dari pre-proc $comName");
                                    //                                                    arrprint($gotParams);
                                    if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                        foreach ($gotParams as $gateName => $paramSpec) {
                                            cekBiru(":: getParams inject ke $gateName ::");
                                            if (!isset($_SESSION[$cCode][$gateName])) {
                                                $_SESSION[$cCode][$gateName] = array();
                                            }
                                            else {

                                            }

                                            foreach ($paramSpec as $id => $gSpec) {
                                                if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                    $_SESSION[$cCode][$gateName][$id] = array();
                                                }
                                                if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                            $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }
                                                //==inject gotParams to child gate
                                                cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                                    if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                        foreach ($gSpec as $key => $val) {
                                                            $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
                                                        }
                                                    }
                                                }

                                                //cekMerah("REBUILDING VALUES..");
                                                if (sizeof($itemNumLabels) > 0) {
                                                    //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                    foreach ($itemNumLabels as $key => $label) {
                                                        if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                            $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else {
                                    cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                }
                            }
                        }
                    }
                }
                else {
                    //cekKuning("sub-preproc is not set");
                }

                $this->load->helper("he_value_builder");
                fillValues($this->jenisTr, 1, 1);

            }
            else {
                echo("no processor defined. skipping preprocessor..<br>");
            }
            //endregion

            $this->midValidate();
            $this->unionValidate();
            //===finalisasi sebelum masuk tabel beneran

            //===isinya ada pembentukan nomor nota dll
            //region penomoran receipt
            $this->load->model("CustomCounter");
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");

            $counterForNumber = array($this->configCore[$this->jenisTr]['formatNota']);
            if (!in_array($counterForNumber[0], $this->configCore[$this->jenisTr]['counters'])) {
                die("Used number should be registered in 'counters' config as well");
            }
            echo "<div style='background:#ff7766;'>";
            foreach ($counterForNumber as $i => $cRawParams) {
                $cParams = explode("|", $cRawParams);
                $cValues = array();
                foreach ($cParams as $param) {
                    $cValues[$i][$param] = $main[$param];
                }
                $cRawValues = implode("|", $cValues[$i]);
                $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

            }
            echo "</div style='background:#ff7766;'>";

            $stepNumber = 1;
            $tmpNomorNota = $paramSpec['paramString'];

            if (isset($this->configUi[$this->jenisTr]['steps'][2])) {
                $nextProp = array(
                    "num" => 2,
                    "code" => $this->configUi[$this->jenisTr]['steps'][2]['target'],
                    "label" => $this->configUi[$this->jenisTr]['steps'][2]['label'],
                    "groupID" => $this->configUi[$this->jenisTr]['steps'][2]['userGroup'],
                );
            }
            else {
                $nextProp = array(
                    "num" => 0,
                    "code" => "",
                    "label" => "",
                    "groupID" => "",
                );
            }
            //endregion

            //region dynamic counters
            $cn = new CustomCounter("transaksi");
            $cn->setType("transaksi");
            $configCustomParams = $this->configCore[$this->jenisTr]['counters'];
            $configCustomParams[] = "stepCode";

            if (sizeof($configCustomParams) > 0) {
                $cContent = array();
                foreach ($configCustomParams as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $main[$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                    $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                    switch ($paramSpec['id']) {
                        case 0: //===counter type is new
                            $paramKeyRaw = print_r($cParams, true);
                            $paramValuesRaw = print_r($cValues[$i], true);
                            $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                            break;
                        default: //===counter to be updated
                            $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                            break;
                    }
                }
            }
            $appliedCounters = base64_encode(serialize($cContent));
            $appliedCounters_inText = print_r($cContent, true);

            //region addition on master
            $addValues = array(
                'counters' => $appliedCounters,
                'counters_intext' => $appliedCounters_inText,
                'nomer' => $tmpNomorNota,
                'dtime' => date("Y-m-d H:i:s"),
                'fulldate' => date("Y-m-d"),
                "step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "step_number" => 1,
                "step_current" => 1,
                "next_step_num" => $nextProp['num'],
                "next_step_code" => $nextProp['code'],
                "next_step_label" => $nextProp['label'],
                "next_group_code" => $nextProp['groupID'],
                "tail_number" => 1,
                "tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            );
            foreach ($addValues as $key => $val) {
                $tableIn_master[$key] = $val;
            }
            //endregion

            //region addition on detail
            $addSubValues = array(
                "sub_step_number" => 1,
                "sub_step_current" => 1,
                "sub_step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                "next_substep_num" => $nextProp['num'],
                "next_substep_code" => $nextProp['code'],
                "next_substep_label" => $nextProp['label'],
                "next_subgroup_code" => $nextProp['groupID'],
                "sub_tail_number" => 1,
                "sub_tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
            );
            foreach ($tableIn_detail as $id => $dSpec) {
                foreach ($addSubValues as $key => $val) {
                    $tableIn_detail[$id][$key] = $val;
                }
            }
            //endregion

            //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
            if (sizeof($tableIn_master) > 0) {
                $tableIn_master['status_4'] = 11;
                $tableIn_master['trash_4'] = 0;

                $tr = new MdlTransaksi();
                $tr->addFilter("transaksi.cabang_id='" . $tableIn_master['cabang_id'] . "'");
                $insertID = $tr->writeMainEntries($tableIn_master);
                //cekHitam("nulis transaksi ".$this->db->last_query());
                // arrPrintHijau($tableIn_master);
                $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $tableIn_master);
                cekHitam("nulis entry point " . $this->db->last_query());
                $mongoList['main'] = array($insertID, $epID);

                $insertNum = $tableIn_master['nomer'];
                $main['nomer'] = $insertNum;
                if ($insertID < 1) {
                    die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                }

                //==transaksi_id dan nomor nota diinject kan ke gate utama
                $injectors = array(
                    "transaksi_id" => $insertID,
                    "nomer" => $tmpNomorNota,
                );
                $arrInjectorsTarget = array(
                    "items",
                );
                foreach ($injectors as $key => $val) {
                    $main[$key] = $val;
                    foreach ($arrInjectorsTarget as $target) {
                        foreach ($items as $xis => $iSpec) {
                            $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                            if (isset($items[$id])) {
                                $items[$id][$key] = $val;
                            }
                        }
                    }
                }

                //===signature
                $dwsign = $tr->writeSignature($insertID, array(
                    "nomer" => $main['nomer'],
                    "step_number" => 1,
                    "step_code" => $this->jenisTr,
                    "step_name" => $this->configUi[$this->jenisTr]['steps'][1]['label'],
                    "group_code" => $this->configUi[$this->jenisTr]['steps'][1]['userGroup'],
                    "oleh_id" => "-100",
                    "oleh_nama" => "sys",
                    "keterangan" => $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh sys",
                    "transaksi_id" => $insertID,
                )) or die("Failed to write signature");
                //                cekHitam("nulis transaksi sign ".$this->db->last_query());
                $mongoList['sign'][] = $dwsign;
                $idHis = array(
                    $stepNumber => array(
                        "step" => $stepNumber,
                        "trID" => $insertID,
                        "nomer" => $tmpNomorNota,
                        "counters" => $appliedCounters,
                        "counters_intext" => $appliedCounters_inText,
                    ),
                );
                $idHis_blob = blobEncode($idHis);
                $idHis_intext = print_r($idHis, true);
                $tr = new MdlTransaksi();
                $dupState = $tr->updateData(array("id" => $insertID), array(
                    "next_step_num" => $nextProp['num'],
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],

                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                )) or die("Failed to update tr next-state!");

                $addValues = array(
                    //===references
                    "id_master" => $insertID,
                    "id_top" => $insertID,
                    "ids_prev" => "",
                    "ids_prev_intext" => "",
                    "nomer_top" => $main['nomer'],
                    "nomers_prev" => "",
                    "nomers_prev_intext" => "",
                    "jenises_prev" => "",
                    "jenises_prev_intext" => "",
                    "ids_his" => $idHis_blob,
                    "ids_his_intext" => $idHis_intext,
                );
                foreach ($addValues as $key => $val) {
                    $tableIn_master[$key] = $val;
                }

            }
            if (sizeof($tableIn_master_values) > 0) {
                if (isset($this->configCore[$this->jenisTr]['tableIn']['mainValues'])) {
                    $inserMainValues = array();
                    foreach ($this->configCore[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                        if (isset($tableIn_master_values[$key])) {
                            $dd = $tr->writeMainValues($insertID, array(
                                "key" => $key,
                                "value" => $tableIn_master_values[$key],
                            ));
                            $inserMainValues[] = $dd;
                            $mongoList['mainValues'][] = $dd;
                        }

                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                }
            }
            if (sizeof($main_add_values) > 0) {
                $inserMainValues = array();
                foreach ($main_add_values as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }

                //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_inputs) > 0) {
                foreach ($main_inputs as $key => $val) {
                    $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                    $inserMainValues[] = $dd;
                    $mongoList['mainValues'][] = $dd;
                }
                if (sizeof($inserMainValues) > 0) {
                    $arrBlob = blobEncode($inserMainValues);
                    $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                }
                //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_add_fields) > 0) {
                foreach ($main_add_fields as $key => $val) {
                    $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                }
                //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
            }
            if (sizeof($main_elements) > 0) {
                foreach ($main_elements as $elName => $aSpec) {
                    $tr->writeMainElements($insertID, array(
                        "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                        "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                        "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                        "name" => $aSpec['name'],
                        "label" => isset($aSpec['label']) ? $aSpec['label'] : "",
                        "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                        "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",
                    ));
                    cekHitam("nulis transaksi element" . $this->db->last_query());
                    //==nebeng bikin inputLabels
                    $currentValue = "";
                    switch ($aSpec['elementType']) {
                        case "dataModel":
                            $currentValue = $aSpec['key'];
                            break;
                        case "dataField":
                            $currentValue = $aSpec['value'];
                            break;
                    }
                    if (array_key_exists($elName, $relOptionConfigs)) {
                        if (isset($relOptionConfigs[$elName][$currentValue])) {
                            if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                    $inputLabels[$oValueName] = $oValSpec['label'];
                                    if (isset($oValSpec['auth'])) {
                                        if (isset($oValSpec['auth']['groupID'])) {
                                            $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
                                        }
                                    }
                                }
                            }
                        }
                        else {
                            //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                        }
                    }
                    //                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
            }
            if (sizeof($tableIn_detail) > 0) {
                $insertIDs = array();
                $insertDeIDs = array();
                foreach ($tableIn_detail as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $insertDeIDs[$insertID][] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertEpID;
                        $insertDeIDs[$epID][] = $insertEpID;
                        $mongoList['detail'][] = $insertEpID;
                    }
                    cekHitam("nulis transaksi data " . $this->db->last_query());
                    //                                cekUngu("LINE: " . __LINE__ . " <br> " . $this->db->last_query());
                }
                if (sizeof($insertIDs) == 0) {
                    die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                }
                else {
                    $indexing_details = array();
                    foreach ($insertDeIDs as $key => $numb) {
                        $indexing_details[$key] = $numb;
                    }
                    foreach ($indexing_details as $k => $arrID) {
                        $arrBlob = blobEncode($arrID);
                        $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                        cekOrange($this->db->last_query());
                    }
                }
            }
            if (sizeof($tableIn_detail2) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail2 as $dSpec) {
                    $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                    $mongoList['detail'] = $insertIDs;
                    if ($epID != 999) {
                        $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
                    }
                    //                                cekUngu($this->db->last_query());
                }
            }
            if (sizeof($tableIn_detail2_sum) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail2_sum as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;

                    if ($epID != 999) {
                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                }
                //                            cekOrange($this->db->last_query());
            }
            if (sizeof($tableIn_detail_rsltItems) > 0) {
                $insertIDs = array();
                foreach ($tableIn_detail_rsltItems as $dSpec) {
                    $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                    $insertIDs[] = $insertDetailID;
                    $mongoList['detail'][] = $insertDetailID;
                    if ($epID != 999) {
                        $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                    }
                    //                                cekUngu($this->db->last_query());
                }
            }
            if (sizeof($tableIn_detail_values) > 0) {
                foreach ($tableIn_detail_values as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                        $insertIDs = array();
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                            if (isset($tableIn_detail[$pID])) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $tableIn_detail[$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[$pID][] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                            //                                        cekLime($this->db->last_query());
                        }
                        if (sizeof($insertIDs) > 0) {
                            $arrBlob = blobEncode($insertIDs);
                            $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                }
            }
            if (sizeof($tableIn_detail_values2_sum) > 0) {
                foreach ($tableIn_detail_values2_sum as $pID => $dSpec) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                            $dd = $tr->writeDetailValues($insertID, array(
                                "produk_jenis" => $tableIn_detail2_sum[$pID]['produk_jenis'],
                                "produk_id" => $pID,
                                "key" => $key,
                                "value" => $dSpec[$src],
                            ));
                            $insertIDs[] = $dd;
                            $mongoList['detailValues'][] = $dd;
                        }
                    }
                }
            }
            //endregion

            //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
            $steps = $this->configUi[$this->jenisTr]['steps'];

            $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
            $filterNeeded = false;
            //arrPrint($items);
            // matiHere(__LINE__);
            //====registri value-gate
            $baseRegistries = array(
                'main' => sizeof($main) > 0 ? $main : array(),
                'items' => sizeof($items) > 0 ? $items : array(),
                'items2' => sizeof($items2) > 0 ? $items2 : array(),
                'items2_sum' => sizeof($items2_sum) > 0 ? $items2_sum : array(),
                'items3' => sizeof($items3) > 0 ? $items3 : array(),
                'items3_sum' => sizeof($items3_sum) > 0 ? $items3_sum : array(),
                'items4' => sizeof($items4) > 0 ? $items4 : array(),
                'items5' => sizeof($items5) > 0 ? $items5 : array(),
                'items5_sum' => sizeof($items5_sum) > 0 ? $items5_sum : array(),
                'rsltItems' => sizeof($rsltItems) > 0 ? $rsltItems : array(),
                'rsltItems2' => sizeof($rsltItems2) > 0 ? $rsltItems2 : array(),
                'tableIn_master' => sizeof($tableIn_master) > 0 ? $tableIn_master : array(),
                'tableIn_detail' => sizeof($tableIn_detail) > 0 ? $tableIn_detail : array(),
                'tableIn_detail2_sum' => sizeof($tableIn_detail2_sum) > 0 ? $tableIn_detail2_sum : array(),
                'tableIn_detail_rsltItems' => sizeof($tableIn_detail_rsltItems) > 0 ? $tableIn_detail_rsltItems : array(),
                'tableIn_detail_rsltItems2' => sizeof($tableIn_detail_rsltItems2) > 0 ? $tableIn_detail_rsltItems2 : array(),
                'tableIn_master_values' => sizeof($tableIn_master_values) > 0 ? $tableIn_master_values : array(),
                'tableIn_detail_values' => sizeof($tableIn_detail_values) > 0 ? $tableIn_detail_values : array(),
                'tableIn_detail_values_rsltItems' => sizeof($tableIn_detail_values_rsltItems) > 0 ? $tableIn_detail_values_rsltItems : array(),
                'tableIn_detail_values_rsltItems2' => sizeof($tableIn_detail_values_rsltItems2) > 0 ? $tableIn_detail_values_rsltItems2 : array(),
                'tableIn_detail_values2_sum' => sizeof($tableIn_detail_values2_sum) > 0 ? $tableIn_detail_values2_sum : array(),
                'main_add_values' => sizeof($main_add_values) > 0 ? $main_add_values : array(),
                'main_add_fields' => sizeof($main_add_fields) > 0 ? $main_add_fields : array(),
                'main_elements' => sizeof($main_elements) > 0 ? $main_elements : array(),
                'main_inputs' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                'main_inputs_orig' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                "receiptDetailFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1] : array(),
                "receiptSumFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1] : array(),
                "receiptDetailFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                "receiptSumFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1] : array(),
            );

            //===
            // arrPrint($baseRegistries);
            // $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            $doWriteReg = $tr->writeDataRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
            $mongRegID = $doWriteReg;
            // matiHere(__LINE__);
            //endregion
            validateAllBalances($cabang_id);
            //region writelog
            $this->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $tmpHData = array(
                "title" => $main['jenisTrName'],
                "sub_title" => "auto new transaction",
                "uid" => "-100",
                "uname" => "sys",
                "dtime" => date("Y-m-d H:i:s"),
                "transaksi_id" => $insertID,
                "deskripsi_old" => "",
                "deskripsi_new" => "",
                "jenis" => $this->jenisTr,
                "ipadd" => $_SERVER['REMOTE_ADDR'],
                "devices" => $_SERVER['HTTP_USER_AGENT'],
                "category" => "transaksi",
                "controller" => $this->uri->segment(1),
                "method" => $this->uri->segment(2),
                "url" => current_url(),
                "keterangan" => "File: " . __FILE__ . " | Line: " . __LINE__,
            );
            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            //endregion
            //                            $a->updateData("id")

            //region update data yang sudah diambil
            $o->setFilters(array());
            $where = array(
                "id" => $fileID,
            );
            $updateOpname = array(
                "cli" => "1",
                "sync_cli_time" => dtimeNow("Y-m-d H:i"),
                "transaksi_id" => $insertID,
                "transaksi_no" => $insertNum,
            );

            $cekId = $o->updateData($where, $updateOpname) or die("Failed to update tr next-state!");
            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());

            //endregion

            //                            arrPrint($cekId);

        }
        else {
            cekOrange('tidak ada data untuk di eksekusi');
        }


        // arrPRint($branchData);
        // arrPRint($datas);
        // START OF COMPLETE REPEATED LOGIC
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
        cekBiru("complit");

        $alerts = array(
            "type" => "success",
            "title" => "Upload & Validasi Selesai!",
            "html" => "<div style='text-align:center;padding:10px;'><p style='font-size:14px;color:#333;line-height:1.6;'>Seluruh data stok opname dari file Excel berhasil diproses dan disimpan.<br><span class='text-muted' style='font-size:12px;'>Halaman akan dimuat ulang secara otomatis...</span></p><div class='text-muted' style='font-size:11px;margin-top:8px;'>Waktu selesai: " . dtimeNow() . "</div></div>",
            "timer" => 2000,
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
            "allowEscapeKey" => false,
        );
        echo swalAlert($alerts);
        echo "<script>setTimeout(function(){ top.location.reload(); }, 2000);</script>";


    }
    // END OF COMPLETE REPEATED LOGIC

    public function executeOpnameSupplies()
    {
        $this->mongoTableList = array(
            "main" => "transaksi",
            "mainValues" => "transaksi_values",
            "detail" => "transaksi_data",
            "detailValues" => "transaksi_data_values",
            "sign" => "transaksi_sign",
            "extras" => "transaksi_extstep",
            "registry" => "transaksi_registry",
        );
        $this->load->model("Coms/ComRekeningPembantuSupplies");
        $this->load->model("Mdls/MdlOpname_xls");
        $this->load->model("Mdls/MdlMongoMother");
        $this->load->model("MdlTransaksi");
        $this->load->library('PHPExcel');
        // arrPrint($this->session->login);

        $this->jenisTr = "1118";//ditembak untuk auto generate supplies opname
        $configMaster = isset($this->configUi[$this->jenisTr]) ? $this->configUi[$this->jenisTr] : array();
        $jenisTrName = $configMaster['label'];
        $jenisTrTop = $configMaster['steps']['1']['target'];
        $nextStepCode = $configMaster['steps']['2']['target'];
        $nextStepCodeGroup = $configMaster['steps']['2']['userGroup'];
        $this->tableInConfig = isset($this->configUi[$this->jenisTr]['tableIn']) ? $this->configUi[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->configUi[$this->jenisTr]['tableIn_static']) ? $this->configUi[$this->jenisTr]['tableIn_static'] : array();

        $this->xlsx = new PHPExcel_Reader_Excel2007();

        $o = new MdlOpname_xls();
        $o->setFilters(array());
        $o->addFilter("cli='0'");
        $o->addFilter("trash='0'");
        // $o->shortBy("id","asc");
        $this->db->limit(1);
        $tmp = $o->lookUpAll()->result();
        if (sizeof($tmp) > 0) {
            showLast_query("lime");
            $filePath = $tmp[0]->full_path;
            $oleh_id = $tmp[0]->oleh_id;
            $oleh_nama = $tmp[0]->oleh_nama;
            $cabang_id = $tmp[0]->cabang_id;
            $toko_id = $tmp[0]->toko_id;
            $fileID = $tmp[0]->id;

            $fileType = $tmp[0]->file_type;
            $fileJSON = $tmp[0]->file_json;

            if ($fileType == "JSON") {
                $tmpSheet = json_decode($fileJSON, true);
                $sheet = $tmpSheet;
                arrPrintWebs($sheet);
            }
            else {
                $ext = str_replace(".", "", $tmp[0]->file_ext);
                $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";
                $loadexcel = $this->xlsx->load($filePath);
                $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);
            }

            $num = 1;
            $numrow = 1;
            $data_header = 1;
            $data_start = 2;
            //region menjadikan header data excell mejadi key
            $headers = array();
            foreach ($sheet as $row) {
                if ($num == $data_header) {
                    $yourArray = array_map('nestedLowercase', $row);
                    $arr = array();
                    if (!empty($yourArray)) {
                        foreach ($yourArray as $kk => $valK) {

                        }
                    }
                    $headers[$num] = $yourArray;

                    //                    arrPrint( $yourArray );
                }
                $num++;
            }
            $koloms = $headers[$data_header];


            /* ---------------------------------
             * arange data excel per row menjadi key => value
             * ---------------------------*/
            $datas = array();
            $produk_id = array();
            foreach ($sheet as $row) {
                if ($numrow >= $data_start) {
                    foreach ($koloms as $kolom => $kalias) {
                        $xl_value = str_replace("'", "", $row[$kolom]);
                        $xlsValue = $xl_value;
                        if (strlen($kalias) > 0) {
                            $rows[$kalias] = (string)$xlsValue;
                        }
                    }
                    $datas[$rows['pid']]['qty'] = $rows['stok riil'];
                    $produk_id[] = $rows['pid'];
                }
                $numrow++;
            }
            //region builder session data trasnksional

            //builder data rekening persdiaan dan data produk
            $selectFields = array("extern_id", "qty_debet", "harga_avg");
            //        $itemFields = $this->configUi['1119'][''];
            $p = new ComRekeningPembantuSupplies();
            $p->addFilter("extern_id in ('" . implode("','", $produk_id) . "')'");
            $p->addFilter("periode='forever'");
            // $p->addFilter("cabang_id='$cabang_id'");
            $p->addFilter("toko_id='$toko_id'");
            $temPersedian = $p->lookUpall()->result();
            //        cekLime($this->db->last_query());
            $dataRekening = array();
            foreach ($temPersedian as $temPersedian0) {
                $tmpData = array();
                foreach ($selectFields as $fields) {
                    $tmpData[$fields] = $temPersedian0->$fields;
                }
                $dataRekening[$temPersedian0->extern_id] = $tmpData;
            }
            // arrPrint($dataRekening);
            //endregion

            //region price list
            $this->load->model("Mdls/MdlHargaSupplies");
            $h = new MdlHargaSupplies();
            $h->addFilter("jenis='supplies'");
            $h->addFilter("jenis_value='hpp'");
            $h->addFilter("status='1'");
            $h->addFilter("toko_id='$toko_id'");
            $h->addFilter("produk_id in ('" . implode("','", $produk_id) . "')'");

            $tmpPrice = $h->lookUpAll()->result();
            // cekHitam($this->db->last_query());
            //        arrPrint($tmpPrice);
            // matiHEre($toko_id);
            $arrPrice = array();
            if (sizeof($tmpPrice) > 0) {
                foreach ($tmpPrice as $priceData) {
                    $arrPrice[$priceData->toko_id][$priceData->produk_id] = $priceData->nilai;
                }
            }
            // arrprint($arrPrice);
            $priceList = $arrPrice[$toko_id];
            //        cekHitam($this->db->last_query());
            //        arrPrint($arrPrice);
            //        arrPrint($tmpPrice);
            //endregion
            //matiHere();
            //region build data produk
            $selectFields = array("id" => "id", "nama" => "nama", "kode" => "kode", "produk_kode" => "kode", "no_part" => "no_part", "satuan" => "satuan");
            $this->load->model("Mdls/MdlSupplies");
            $pr = new MdlSupplies();
            $pr->addFilter("id in ('" . implode("','", $produk_id) . "')'");
            //        $p->addFilter("periode='forever'");
            //        $p->addFilter("cabang_id='$cabang_id'");
            $temProduk = $pr->lookUpall()->result();
            //        cekLime($this->db->last_query());
            $dataProduk = array();
            foreach ($temProduk as $temProduk_0) {
                $tmpData = array();
                foreach ($selectFields as $k => $fields) {
                    $tmpData[$k] = $temProduk_0->$fields;
                }
                $dataProduk[$temProduk_0->id] = $tmpData;
            }
            // arrPrint($dataProduk);
            //endregion
            //region builder data cabang/toko
            $this->load->model("Mdls/MdlGudangDefault");
            $this->load->model("Mdls/MdlCompany");
            $c = new MdlCompany();
            $g = new MdlGudangDefault();

            $c->setFilters(array());
            $c->addFilter("toko_id='$toko_id'");
            $cabang_data = $c->lookupAll()->result();
            // arrPrint($cabang_data);
            // cekLime($this->db->last_query());
            $cabangData = array();
            $branchData = array();
            foreach ($cabang_data as $cabData) {
                $cabangData[$cabData->toko_id] = $cabData->nama;
            }
            $g->addFilter("cabang_id='$cabang_id'");
            $tempBranch = $g->lookupAll()->result();
            foreach ($tempBranch as $tempBranchData) {
                $branchData[$cabang_id] = array(
                    "gudang_id" => $tempBranchData->id,
                    "gudang_nama" => $tempBranchData->name,
                );
            }
            //endregion
            //region array builder transaction
            $mainTmp = array(
                "dummyElement" => "yes",
                "dummyElement__label" => "yes",
                "dummyElement__name" => "yes",
                "olehID" => "-100",
                "olehName" => "sys",
                "placeID" => "-1",
                "placeName" => "pusat",
                "divID" => $this->divId,
                "divName" => "default",
                "cabangID" => "-1",
                "cabangName" => "pusat",
                "gudangID" => "-1",
                "gudangName" => "default center warehouse",
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $jenisTrTop,
                "jenisTrName" => $jenisTrName,
                "stepNumber" => "1",
                "stepCode" => $jenisTrTop,
                "dtime" => dtimeNow("Y-m-d H:i"),
                "fulldate" => dtimeNow("Y-m-d"),
                "harga" => "0",
                "subtotal" => "0",
                "discount_persen" => "0",
                "discount_qty" => "0",
                "no_part" => "0",
                "ppn" => "0",
                "stok" => "1",
                "debet" => "0",
                "kredit" => "0",
                "hpp" => "0",
                "qty_selisih" => "0",
                "jenis" => $jenisTrTop,
                "transaksi_jenis" => $jenisTrTop,
                "next_step_code" => $nextStepCode,
                "next_group_code" => $nextStepCodeGroup,
                "step_number" => "1",
                "step_current" => "1",
                "longitude" => "",
                "lattitude" => "",
                "accuracy" => "",
                "new_sisa" => "0",
                "qty_opname" => "0",
                "qty_debet" => "0",
                "qty_kredit" => "0",
            );
            $itemsTmp = array(
                "handler" => "Selectors/_processSelectProduct",
                "id" => "id",
                "jml" => "jml",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "satuan" => "satuan",
                "discount_persen" => "0",
                "discount_qty" => "0",
                "nama" => "nama",
                "produk_kode" => "produk_kode",
                "no_part" => "no_part",
                "label" => "0",
                "ppn" => "0",
                "stok" => "stok",
                "debet" => "debet",
                "kredit" => "kredit",
                "hpp" => "hpp",
                "qty_selisih" => "qty_selisih",
                "qty" => "qty",
                "name" => "name",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_discount_persen" => "sub_discount_persen",
                "sub_discount_qty" => "0",
                "sub_ppn" => "0",
                "sub_stok" => "sub_stok",
                "sub_debet" => "sub_debet",
                "sub_kredit" => "sub_kredit",
                "sub_hpp" => "sub_hpp",
                "sub_qty_selisih" => "sub_qty_selisih",
                "olehID" => "-100",
                "olehName" => "sys",
                "placeID" => "cabang_id",
                "placeName" => 'placeName',
                "cabangID" => "cabangID",
                "cabangName" => "cabangName",
                "gudangID" => "gudangID",
                "gudangName" => "gudangName",
                "jenisTr" => $this->jenisTr,
                "next_substep_code" => $nextStepCode,
                "next_subgroup_code" => $nextStepCodeGroup,
                "sub_step_number" => "1",
                "sub_step_current" => "1",
                "nilai_bayar" => "",
                "new_sisa" => "0",
                "sub_new_sisa" => "0",
                "qty_opname" => "qty_opname",
                "qty_debet" => "qty_debet",
                "qty_kredit" => "qty_kredit",
                "sub_qty_opname" => "sub_qty_opname",
                "sub_qty_debet" => "sub_qty_debet",
                "sub_qty_kredit" => "sub_qty_kredit",


            );
            $items2 = array();
            $items2_sum = array();
            $rsltItems = array();
            $rsltItems2 = array();
            $tableIn_master = array(
                "trash" => "0",
                "jenis_master" => $this->jenisTr,
                "jenis_top" => $jenisTrTop,
                "jenis" => $jenisTrTop,
                "jenis_label" => $jenisTrName,
                "div_id" => $this->divId,
                "div_nama" => "default",
                "dtime" => dtimeNow("Y-m-d H:i"),
                "fulldate" => dtimeNow("Y-m-d"),
                "oleh_id" => "-100",
                "oleh_nama" => "sys",
                "cabang_id" => $toko_id,
                "cabang_nama" => $cabangData[$toko_id],
                "transaksi_jenis" => $jenisTrTop,
                "gudang_id" => "-1",
                "gudang_nama" => "default center warehouse",
                "next_step_code" => $nextStepCode,
                "next_group_code" => $nextStepCodeGroup,
                "step_number" => "1",
                "step_current" => "1",
                "toko_id" => $toko_id,
                "toko_nama" => $cabangData[$toko_id],
            );
            $tableIn_detailTmp = array(
                "produk_id" => "id",
                "produk_kode" => "produk_kode",
                "produk_label" => "",
                "produk_nama" => "nama",
                "produk_ord_jml" => "1",
                "produk_ord_hrg" => "harga",
                "hpp" => "hpp",
                "satuan" => "satuan",
                "note" => "",
                "reference" => "",
                "trash" => "0",
                "produk_jenis" => "produk",
                "valid_qty" => "1",
            );
            $tableIn_detail2_sum = array();
            $tableIn_detail_rsltItems = array();
            $tableIn_detail_rsltItems2 = array();
            $tableIn_master_valuesTmp = array(
                "divID" => "4",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "discount_persen" => "0",
                "discount_qty" => "0",
                "hpp" => "hpp",
                "no_part" => "",
                "ppn" => "ppn",
                "stok" => "1",
                "debet" => "debet",
                "kredit" => "kredit",
                "qty_selisih" => "qty_selisih",
                "qty_opname" => "qty_opname",
                "qty_debet" => "qty_debet",
                "qty_kredit" => "qty_kredit",
            );
            $tableIn_detail_valuesTmp = array(
                "jml" => "jml",
                "harga" => "harga",
                "subtotal" => "subtotal",
                "discount_persen" => "0",
                "discount_qty" => "0",
                "ppn" => "0",
                "stok" => "stok",
                "debet" => "debet",
                "kredit" => "kredit",
                "hpp" => "hpp",
                "qty_selisih" => "qty_selisih",
                "qty" => "qty",
                "sub_harga" => "sub_harga",
                "sub_subtotal" => "sub_subtotal",
                "sub_discount_persen" => "0",
                "sub_discount_qty" => "0",
                "sub_ppn" => "0",
                "sub_stok" => "sub_stok",
                "sub_debet" => "sub_debet",
                "sub_kredit" => "sub_kredit",
                "sub_hpp" => "sub_hpp",
                "sub_qty_selisih" => "sub_qty_selisih",
                "sub_new_sisa" => "0",
                "qty_opname" => "qty_opname",
                "qty_debet" => "qty_debet",
                "qty_kredit" => "qty_kredit",
                "sub_qty_opname" => "sub_qty_opname",
                "sub_qty_debet" => "sub_qty_debet",
                "sub_qty_kredit" => "sub_qty_kredit",
            );
            $tableIn_detail_values_rsltItemsTmp = array();
            $tableIn_detail_values_rsltItems2Tmp = array();
            $tableIn_detail_values2_sumTmp = array();
            $tableIn_detail2 = array();
            $main_add_values = array();
            $main_add_fields = array();
            $main_elements = array(
                "dummyElement" => Array(
                    "elementType" => "dataModel",
                    "name" => "dummyElement",
                    "label" => "auto-validation",
                    "key" => "yes",
                    "labelSrc" => "name",
                    "labelValue" => "yes",
                    "mdl_name" => "MdlDummyElement",
                    "contents" => "YToxOntzOjQ6Im5hbWUiO3M6MzoieWVzIjt9",
                    "contents_intext" => "",
                ),
            );
            $main_inputs = array();
            $main_inputs_orig = array();
            $receiptDetailFieldsTmp = array(
                "produk_nama" => "name",
            );
            $receiptSumFieldsTmp = array(
                "harga" => "total amount",
            );
            $receiptDetailFields2 = array();
            $receiptSumFields2 = array();
            $tableIn_detail_values2_sum = array();
            $items3 = array();
            $items3_sum = array();
            $tableIn_detail_values_rsltItems = array();
            $tableIn_detail_values_rsltItems2 = array();
            //endregion


            $subtotal_nilai_debet = 0;
            $subtotal_nilai_kredit = 0;
            $subtotal_qty_debet = 0;
            $subtotal_qty_kredit = 0;
            $subtotal_opname = 0;
            $subharga = 0;
            $itemsData = array();
            foreach ($dataProduk as $pID => $pData) {

                $harga = isset($priceList[$pID]) && $priceList[$pID] > 0 ? $priceList[$pID] : 1;
                // $ppn = $harga * 10 / 100;
                $ppn = 0;
                $sub_harga = $ppn + $harga;
                $selisih = isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] - $datas[$pID]["qty"] : 0 - $datas[$pID]["qty"];

                // if ($selisih < 0) {
                //     $qty_debet = $selisih * -1;
                //     $qty_kredit = 0;
                //     $selisih_qty = $qty_debet;
                //     $debet_nilai = $qty_debet * $harga;
                //     $kredit_nilai = 0;
                // }
                // else {
                // $qty_debet = 0;
                // $qty_kredit = $selisih;
                // $selisih_qty = $qty_kredit;
                // $debet_nilai = 0;
                // $kredit_nilai = $qty_debet * $harga;
                //jika stok riil lebih besar dari stok sistem ada qty debet
                if ($datas[$pID]["qty"] > $dataRekening[$pID]["qty_debet"]) {
                    $qty_debet = $selisih < 0 ? $selisih * -1 : $selisih;
                    $qty_kredit = 0;
                    $selisih_qty = $qty_debet;
                    $debet_nilai = $qty_debet * $harga;
                    $kredit_nilai = 0;
                }
                else {
                    $qty_debet = 0;
                    $qty_kredit = $selisih < 0 ? $selisih * -1 : $selisih;
                    $selisih_qty = $qty_kredit;
                    $debet_nilai = 0;
                    $kredit_nilai = $qty_debet * $harga;
                }

                // }

                $subharga = $debet_nilai + $kredit_nilai;
                $subtotal_nilai_debet += $debet_nilai;
                $subtotal_nilai_kredit += $kredit_nilai;
                $subtotal_qty_debet += $qty_debet;
                $subtotal_qty_kredit += $qty_kredit;
                $subtotal_opname += $datas[$pID]["qty"];
                // if($pID=="3109"){
                //     cekMerah($qty_debet." ".$qty_kredit." ".$selisih_qty);
                //     matiHEre();
                // }
                $itemsData[$pID] = array(
                    "id" => "$pID",
                    "jml" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                    "harga" => $harga,
                    "subtotal" => $sub_harga,
                    "satuan" => $pData["satuan"],
                    "discount_persen" => 0,
                    "discount_qty" => 0,
                    "hpp" => $harga,
                    "nama" => $pData["nama"],
                    "produk_kode" => $pData["produk_kode"],
                    "no_part" => $pData["no_part"],
                    "label" => 0,
                    "ppn" => $ppn,
                    "stok" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                    "debet" => $debet_nilai,
                    "kredit" => $kredit_nilai,
                    "qty_selisih" => $selisih,
                    "qty" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                    "name" => $pData["nama"],
                    "sub_harga" => $harga,
                    "sub_subtotal" => $harga,
                    "sub_discount_persen" => 0,
                    "sub_discount_qty" => 0,
                    "sub_hpp" => $harga,
                    "sub_ppn" => $ppn,
                    "sub_stok" => isset($dataRekening[$pID]["qty_debet"]) ? $dataRekening[$pID]["qty_debet"] : 0,
                    "sub_debet" => $debet_nilai,
                    "sub_kredit" => "0",
                    "sub_qty_selisih" => $selisih,
                    "olehID" => "-100",
                    "olehName" => "sys",
                    "pihakID" => $cabang_id,
                    "pihakName" => isset($cabangData[$cabang_id]) ? $cabangData[$cabang_id] : "",
                    "placeID" => $cabang_id,
                    "placeName" => $cabangData[$cabang_id],
                    "cabangID" => $cabang_id,
                    "cabangName" => $cabangData[$cabang_id],
                    "gudangID" => isset($branchData[$cabang_id]['gudang_id']) ? $branchData[$cabang_id]['gudang_id'] : "",
                    "gudangName" => isset($branchData[$cabang_id]['gudang_nama']) ? $branchData[$cabang_id]['gudang_nama'] : "",
                    "jenisTr" => $this->jenisTr,
                    "next_substep_code" => $nextStepCode,
                    "next_subgroup_code" => $nextStepCodeGroup,
                    "sub_step_number" => 1,
                    "sub_step_current" => 1,
                    "nilai_bayar" => "",
                    "new_sisa" => 0,
                    "sub_new_sisa" => 0,
                    "qty_opname" => $datas[$pID]["qty"],
                    "qty_debet" => $qty_debet,
                    "qty_kredit" => $qty_kredit,
                    "sub_qty_opname" => $datas[$pID]["qty"],
                    "sub_qty_debet" => $selisih_qty,
                    "sub_qty_kredit" => $qty_kredit,

                );
                //            arrPrint($pData);
            }

            //        arrPrint($itemsData);
            // matiHere();
            //region builder items

            //region builder main
            // main untuk mode gabungan
            $main = array(
                "dummyElement" => "yes",
                "dummyElement__label" => "yes",
                "dummyElement__name" => "yes",
                "olehID" => "-100",
                "olehName" => "sys",
                "placeID" => $cabang_id,
                "placeName" => $cabangData[$cabang_id],
                "divID" => $this->divId,
                "divName" => "default",
                "cabangID" => $cabang_id,
                "tokoID" => $toko_id,
                "cabangName" => $cabangData[$cabang_id],
                "gudangID" => isset($branchData[$cabang_id]['gudang_id']) ? $branchData[$cabang_id]['gudang_id'] : "",
                "gudangName" => isset($branchData[$cabang_id]['gudang_nama']) ? $branchData[$cabang_id]['gudang_nama'] : "",
                "jenisTr" => $this->jenisTr,
                "jenisTrMaster" => $this->jenisTr,
                "jenisTrTop" => $jenisTrTop,
                "jenisTrName" => $jenisTrName,
                "stepNumber" => "1",
                "stepCode" => $jenisTrTop,
                "dtime" => dtimeNow("Y-m-d H:i"),
                "fulldate" => dtimeNow("Y-m-d"),
                "harga" => $subharga,
                "subtotal" => $subharga,
                "discount_persen" => "0",
                "discount_qty" => "0",
                "no_part" => "0",
                "ppn" => "$ppn",
                "stok" => "1",
                "debet" => "$subtotal_nilai_debet",
                "kredit" => "$subtotal_nilai_kredit",
                "hpp" => $subharga,
                "qty_selisih" => "",
                "jenis" => $jenisTrTop,
                "transaksi_jenis" => $jenisTrTop,
                "next_step_code" => $nextStepCode,
                "next_group_code" => $nextStepCodeGroup,
                "step_number" => "1",
                "step_current" => "1",
                "longitude" => "",
                "lattitude" => "",
                "accuracy" => "",
                //            "new_sisa" => "0",
                "qty_opname" => $subtotal_opname,
                "qty_debet" => $subtotal_qty_debet,
                "qty_kredit" => $subtotal_qty_kredit,
                "toko_id" => $toko_id,
                "toko_nama" => $cabangData[$toko_id],
            );
            //endregion builder main

            //region builder items
            $items = array();
            foreach ($itemsData as $itsID => $itsData) {
                foreach ($itemsTmp as $col => $selectedRow) {
                    $items[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }
            //endregion builder items


            //region builder table in detil
            $tableIn_detail = array();
            foreach ($itemsData as $itsID => $itsData) {
                foreach ($tableIn_detailTmp as $col => $selectedRow) {
                    $tableIn_detail[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }

            //                cekUngu('$tableIn_detail');
            //                arrPrintWebs($tableIn_detail);
            //endregion builder table in detil

            //region table in master values
            $tableIn_master_values = array(
                "harga" => "$subharga",
                "subtotal" => "$subtotal_opname",
                "discount_persen" => "0",
                "discount_qty" => "0",
                "hpp" => "$subharga",
                "no_part" => "",
                "ppn" => "$ppn",
                "stok" => "1",
                "debet" => "$subtotal_nilai_debet",
                "kredit" => "$subtotal_nilai_kredit",
                "qty_selisih" => "",
                "qty_opname" => "$subtotal_opname",
                "qty_debet" => "$subtotal_qty_debet",
                "qty_kredit" => "$subtotal_qty_kredit",
            );

            //endregion table in master values

            //region build table in detil values
            $tableIn_detail_values = array();
            foreach ($itemsData as $itsID => $itsData) {
                foreach ($tableIn_detail_valuesTmp as $col => $selectedRow) {
                    $tableIn_detail_values[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }

            //endregion build table in detil values

            //region build table receipDetailFields
            $receiptDetailFields = array();
            foreach ($itemsData as $itsID => $itsData) {
                foreach ($receiptDetailFieldsTmp as $col => $selectedRow) {
                    $receiptDetailFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }
            //        arrPrint($receiptDetailFields);
            //        matiHere();
            //endregion

            //region receiptSumFields
            $receiptSumFields = array();
            foreach ($itemsData as $itsID => $itsData) {
                foreach ($receiptSumFieldsTmp as $col => $selectedRow) {
                    $receiptSumFields[$itsID][$col] = isset($itsData[$selectedRow]) ? $itsData[$selectedRow] : $selectedRow;
                }
            }
            //endregion

            $this->db->trans_start();
            $mongoList = array();
            // $mongRegID = array();
            if (sizeof($itemsData) > 0) {
                //region transaksional
                $buildTablesMaster = isset($this->configCore[$this->jenisTr]['components'][1]['master']) ? $this->configCore[$this->jenisTr]['components'][1]['master'] : array();
                $buildTablesDetail = isset($this->configCore[$this->jenisTr]['components'][1]['detail']) ? $this->configCore[$this->jenisTr]['components'][1]['detail'] : array();
                $addMasterTables = array(
                    "rugilaba",
                    "laba ditahan",
                    "rugilaba lain lain",
                );
                foreach ($addMasterTables as $trek) {
                    $buildTablesMaster[] = array(
                        "comName" => "RugiLaba",
                        "loop" => array(
                            "$trek" => .0,
                        ),
                    );
                }
                if (sizeof($buildTablesMaster) > 0) {
                    $bCtr = 0;
                    foreach ($buildTablesMaster as $buildTablesMaster_specs) {
                        $bCtr++;
                        $mdlName = $buildTablesMaster_specs['comName'];
                        if (substr($mdlName, 0, 1) == "{") {
                            $mdlName = trim($mdlName, "{");
                            $mdlName = trim($mdlName, "}");
                            $mdlName = str_replace($mdlName, $main[$mdlName], $mdlName);
                        }
                        else {
                            //                        cekkuning("TIDAK mengandung kurawal");
                        }

                        $mdlName = "Com" . $mdlName;
                        $this->load->model("Coms/" . $mdlName);
                        $m = new $mdlName();
                        if (isset($buildTablesMaster_specs['loop']) && sizeof($buildTablesMaster_specs['loop']) > 0) {
                            foreach ($buildTablesMaster_specs['loop'] as $key => $val) {
                                if (substr($key, 0, 1) == "{") {
                                    $oldParam = $buildTablesMaster_specs['loop'][$key];
                                    unset($buildTablesMaster_specs['loop'][$key]);
                                    $key = trim($key, "{");
                                    $key = trim($key, "}");
                                    $key = str_replace($key, $main[$key], $key);
                                    $buildTablesMaster_specs['loop'][$key] = $oldParam;
                                }
                            }
                        }
                        if (method_exists($m, "getTableNameMaster")) {
                            if (sizeof($m->getTableNameMaster())) {
                                $m->buildTables($buildTablesMaster_specs);
                            }
                        }
                    }
                }
                if (sizeof($buildTablesDetail) > 0) {
                    foreach ($buildTablesDetail as $buildTablesDetail_specs) {
                        foreach ($items as $itemSpec) {
                            $mdlName = $buildTablesDetail_specs['comName'];
                            if (substr($mdlName, 0, 1) == "{") {
                                $mdlName = trim($mdlName, "{");
                                $mdlName = trim($mdlName, "}");
                                $mdlName = str_replace($mdlName, $itemSpec[$mdlName], $mdlName);
                            }
                            $mdlName = "Com" . $mdlName;
                            cekbiru("model: $mdlName");
                            $this->load->model("Coms/" . $mdlName);
                            $m = new $mdlName();
                            if (isset($buildTablesDetail_specs['loop']) && sizeof($buildTablesDetail_specs['loop']) > 0) {
                                foreach ($buildTablesDetail_specs['loop'] as $key => $val) {
                                    if (substr($key, 0, 1) == "{") {
                                        $oldParam = $buildTablesDetail_specs['loop'][$key];
                                        unset($buildTablesDetail_specs['loop'][$key]);
                                        $key = trim($key, "{");
                                        $key = trim($key, "}");
                                        $key = str_replace($key, $itemSpec[$key], $key);
                                        $buildTablesDetail_specs['loop'][$key] = $oldParam;
                                    }
                                }
                            }
                            if (method_exists($m, "getTableNameMaster")) {
                                if (sizeof($m->getTableNameMaster())) {
                                    $m->buildTables($buildTablesDetail_specs);
                                }
                            }
                        }
                    }
                }

                //region pre-processors (master)
                if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['master'])) {
                    $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['master'] : array();
                    $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                            $subParams = array();

                            if (isset($tComSpec['static'])) {
                                foreach ($tComSpec['static'] as $key => $value) {
                                    $realValue = makeValue($value, $_SESSION[$cCode]['main'], $_SESSION[$cCode]['main'], 0);
                                    $subParams['static'][$key] = $realValue;
                                }
                                if (!isset($subParams['static']["transaksi_id"])) {

                                }
                                $subParams['static']["fulldate"] = date("Y-m-d");
                                $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                            }
                            $tmpOutParams[$cCtr] = $subParams;

                            $mdlName = "Pre" . ucfirst($comName);
                            $this->load->model("Preprocs/" . $mdlName);
                            $m = new $mdlName($resultParams);

                            if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                $tobeExecuted = true;
                            }
                            else {
                                $tobeExecuted = false;
                            }

                            if ($tobeExecuted) {
                                $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                $gotParams = $m->exec();
                                cekbiru("gotparams dari pre-proc $comName");
                                //                                            arrprint($gotParams);
                                if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                    foreach ($gotParams as $gateName => $gSpec) {
                                        if (isset($_SESSION[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $_SESSION[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                        //==inject gotParams to child gate
                                        if (isset($_SESSION[$cCode]['main'])) {
                                            if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                foreach ($gSpec as $key => $val) {
                                                    $_SESSION[$cCode]['main'][$key] = $val;
                                                }
                                            }
                                        }
                                        //cekMerah("REBUILDING VALUES..");
                                        if (sizeof($itemNumLabels) > 0) {
                                            //cekHijau("REBUILDING SUBS FOR ITEMS");
                                            foreach ($itemNumLabels as $key => $label) {
                                                //cekHere("$id === $key => $label");
                                                if (isset($_SESSION[$cCode]['main'][$key])) {
                                                    $_SESSION[$cCode]['main']['sub_' . $key] = ($_SESSION[$cCode]['main']['jml'] * $_SESSION[$cCode]['main'][$key]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else {
                                cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                            }
                        }
                    }
                    else {
                        //cekKuning("sub-preproc is not set");
                    }
                    $this->load->helper("he_value_builder");
                    fillValues($this->jenisTr, 1, 1);
                }
                else {
                    echo("no processor defined. skipping preprocessor..<br>");
                }
                //endregion


                //region pre-processors (item)
                if (isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail'])) {
                    $iterator = isset($this->configCore[$this->jenisTr]['preProcessor'][1]['detail']) ? $this->configCore[$this->jenisTr]['preProcessor'][1]['detail'] : array();
                    $itemNumLabels = isset($this->configUi[$this->jenisTr]['shoppingCartNumFields']) ? $this->configUi[$this->jenisTr]['shoppingCartNumFields'] : array();
                    echo "ITEM NUM LABELS";

                    if (sizeof($iterator) > 0) {
                        foreach ($iterator as $cCtr => $tComSpec) {
                            $comName = $tComSpec['comName'];
                            $srcGateName = $tComSpec['srcGateName'];
                            $srcRawGateName = $tComSpec['srcRawGateName'];
                            echo "sub-preproc: $comName, initializing values <br>";
                            foreach ($_SESSION[$cCode][$srcGateName] as $xid => $dSpec) {
                                $tmpOutParams[$cCtr] = array();
                                $id = $xid;
                                $subParams = array();
                                if (isset($tComSpec['static'])) {
                                    foreach ($tComSpec['static'] as $key => $value) {
                                        $realValue = makeValue($value, $_SESSION[$cCode][$srcGateName][$id], $_SESSION[$cCode][$srcGateName][$id], 0);
                                        $subParams['static'][$key] = $realValue;
                                    }
                                    if (!isset($subParams['static']["transaksi_id"])) {

                                    }
                                    $subParams['static']["fulldate"] = date("Y-m-d");
                                    $subParams['static']["dtime"] = date("Y-m-d H:i:s");
                                    $subParams['static']["keterangan"] = $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh " . $this->session->login['nama'];
                                }
                                cekLime(":: cetak preprocc... $comName :: $srcGateName ::");
                                //                                            arrPrint($subParams);
                                if (sizeof($subParams) > 0) {
                                    $tmpOutParams[$cCtr][] = $subParams;
                                    $comName = $tComSpec['comName'];
                                    $srcGateName = $tComSpec['srcGateName'];
                                    $srcRawGateName = $tComSpec['srcRawGateName'];
                                    $resultParams = isset($tComSpec['resultParams']) ? $tComSpec['resultParams'] : array();
                                    $mdlName = "Pre" . ucfirst($comName);
                                    $this->load->model("Preprocs/" . $mdlName);
                                    $m = new $mdlName($resultParams);
                                    if (sizeof($tmpOutParams[$cCtr]) > 0) {
                                        $tobeExecuted = true;
                                    }
                                    else {
                                        $tobeExecuted = false;
                                    }

                                    if ($tobeExecuted) {
                                        $m->pair(0, $tmpOutParams[$cCtr]) or die("Tidak berhasil memasang  values pada pre-processor: $comName/" . $this->jenisTr . "/" . __FUNCTION__ . "/" . __LINE__);
                                        $gotParams = $m->exec();
                                        cekmerah("gotparams dari pre-proc $comName");
                                        //                                                    arrprint($gotParams);
                                        if (sizeof($gotParams) > 0) {//==gotParams means result from preprocessor
                                            foreach ($gotParams as $gateName => $paramSpec) {
                                                cekBiru(":: getParams inject ke $gateName ::");
                                                if (!isset($_SESSION[$cCode][$gateName])) {
                                                    $_SESSION[$cCode][$gateName] = array();
                                                }
                                                else {

                                                }

                                                foreach ($paramSpec as $id => $gSpec) {
                                                    if (!isset($_SESSION[$cCode][$gateName][$id])) {
                                                        $_SESSION[$cCode][$gateName][$id] = array();
                                                    }
                                                    if (isset($_SESSION[$cCode][$gateName][$id])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                cekHere(":: injecte ke $gateName, ::: $key diisi dengan $val");
                                                                $_SESSION[$cCode][$gateName][$id][$key] = $val;
                                                            }
                                                        }
                                                    }
                                                    //==inject gotParams to child gate
                                                    cekHitam("srcGateName = $srcGateName :: " . __LINE__);
                                                    if (isset($_SESSION[$cCode][$srcGateName][$id])) {
                                                        if (is_array($gSpec) && sizeof($gSpec) > 0) {
                                                            foreach ($gSpec as $key => $val) {
                                                                $_SESSION[$cCode][$srcGateName][$id][$key] = $val;
                                                            }
                                                        }
                                                    }

                                                    //cekMerah("REBUILDING VALUES..");
                                                    if (sizeof($itemNumLabels) > 0) {
                                                        //cekHijau("REBUILDING SUBS FOR ITEMS");
                                                        foreach ($itemNumLabels as $key => $label) {
                                                            if (isset($_SESSION[$cCode][$gateName][$id][$key])) {
                                                                $_SESSION[$cCode][$gateName][$id]['sub_' . $key] = ($_SESSION[$cCode][$gateName][$id]['jml'] * $_SESSION[$cCode][$gateName][$id][$key]);
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        cekBiru("sub-komponem $comName tidak memenuhi syarat untuk ditulis");
                                    }
                                }
                            }
                        }
                    }
                    else {
                        //cekKuning("sub-preproc is not set");
                    }

                    $this->load->helper("he_value_builder");
                    fillValues($this->jenisTr, 1, 1);

                }
                else {
                    echo("no processor defined. skipping preprocessor..<br>");
                }
                //endregion

                $this->midValidate();
                $this->unionValidate();
                //===finalisasi sebelum masuk tabel beneran

                //===isinya ada pembentukan nomor nota dll
                //region penomoran receipt
                $this->load->model("CustomCounter");
                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");

                $counterForNumber = array($this->configCore[$this->jenisTr]['formatNota']);
                if (!in_array($counterForNumber[0], $this->configCore[$this->jenisTr]['counters'])) {
                    die("Used number should be registered in 'counters' config as well");
                }
                echo "<div style='background:#ff7766;'>";
                foreach ($counterForNumber as $i => $cRawParams) {
                    $cParams = explode("|", $cRawParams);
                    $cValues = array();
                    foreach ($cParams as $param) {
                        $cValues[$i][$param] = $main[$param];
                    }
                    $cRawValues = implode("|", $cValues[$i]);
                    $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                }
                echo "</div style='background:#ff7766;'>";

                $stepNumber = 1;
                $tmpNomorNota = $paramSpec['paramString'];

                if (isset($this->configUi[$this->jenisTr]['steps'][2])) {
                    $nextProp = array(
                        "num" => 2,
                        "code" => $this->configUi[$this->jenisTr]['steps'][2]['target'],
                        "label" => $this->configUi[$this->jenisTr]['steps'][2]['label'],
                        "groupID" => $this->configUi[$this->jenisTr]['steps'][2]['userGroup'],
                    );
                }
                else {
                    $nextProp = array(
                        "num" => 0,
                        "code" => "",
                        "label" => "",
                        "groupID" => "",
                    );
                }
                //endregion

                //region dynamic counters
                $cn = new CustomCounter("transaksi");
                $cn->setType("transaksi");
                $configCustomParams = $this->configCore[$this->jenisTr]['counters'];
                $configCustomParams[] = "stepCode";

                if (sizeof($configCustomParams) > 0) {
                    $cContent = array();
                    foreach ($configCustomParams as $i => $cRawParams) {
                        $cParams = explode("|", $cRawParams);
                        $cValues = array();
                        foreach ($cParams as $param) {
                            $cValues[$i][$param] = $main[$param];
                        }
                        $cRawValues = implode("|", $cValues[$i]);
                        $paramSpec = $cn->getNewCount($cParams, $cValues[$i]);

                        $cContent[$cRawParams][$cRawValues] = $paramSpec['value'];
                        switch ($paramSpec['id']) {
                            case 0: //===counter type is new
                                $paramKeyRaw = print_r($cParams, true);
                                $paramValuesRaw = print_r($cValues[$i], true);
                                $cn->writeNewCount($cParams, $cValues[$i], $paramKeyRaw, $paramValuesRaw);
                                break;
                            default: //===counter to be updated
                                $cn->updateCount($paramSpec['id'], $paramSpec['value']);
                                break;
                        }
                    }
                }
                $appliedCounters = base64_encode(serialize($cContent));
                $appliedCounters_inText = print_r($cContent, true);
                //endregion

                //region addition on master
                $addValues = array(
                    'counters' => $appliedCounters,
                    'counters_intext' => $appliedCounters_inText,
                    'nomer' => $tmpNomorNota,
                    'dtime' => date("Y-m-d H:i:s"),
                    'fulldate' => date("Y-m-d"),
                    "step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                    "step_number" => 1,
                    "step_current" => 1,
                    "next_step_num" => $nextProp['num'],
                    "next_step_code" => $nextProp['code'],
                    "next_step_label" => $nextProp['label'],
                    "next_group_code" => $nextProp['groupID'],
                    "tail_number" => 1,
                    "tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
                );
                foreach ($addValues as $key => $val) {
                    $tableIn_master[$key] = $val;
                }
                //endregion

                //region addition on detail
                $addSubValues = array(
                    "sub_step_number" => 1,
                    "sub_step_current" => 1,
                    "sub_step_avail" => sizeof($this->configUi[$this->jenisTr]['steps']),
                    "next_substep_num" => $nextProp['num'],
                    "next_substep_code" => $nextProp['code'],
                    "next_substep_label" => $nextProp['label'],
                    "next_subgroup_code" => $nextProp['groupID'],
                    "sub_tail_number" => 1,
                    "sub_tail_code" => $this->configUi[$this->jenisTr]['steps'][1]['target'],
                );
                foreach ($tableIn_detail as $id => $dSpec) {
                    foreach ($addSubValues as $key => $val) {
                        $tableIn_detail[$id][$key] = $val;
                    }
                }
                //endregion

                //region ----------write transaksi, transaksi_data, main_fields, main_values, main_applets, etc
                if (sizeof($tableIn_master) > 0) {
                    $tableIn_master['status_4'] = 11;
                    $tableIn_master['trash_4'] = 0;
                    $tr = new MdlTransaksi();
                    $tr->addFilter("transaksi.cabang_id='" . $tableIn_master['cabang_id'] . "'");
                    $insertID = $tr->writeMainEntries($tableIn_master);
                    cekHitam("nulis transaksi " . $this->db->last_query());
                    $epID = $tr->writeMainEntries_entryPoint($insertID, $insertID, $tableIn_master);
                    // cekHitam("nulis entry point " . $this->db->last_query());
                    $mongoList['main'] = array($insertID, $epID);

                    $insertNum = $tableIn_master['nomer'];
                    $main['nomer'] = $insertNum;
                    if ($insertID < 1) {
                        die("Gagal saat berusaha  write transaction entry pada " . __FILE__ . " baris " . __LINE__);
                    }

                    //==transaksi_id dan nomor nota diinject kan ke gate utama
                    $injectors = array(
                        "transaksi_id" => $insertID,
                        "nomer" => $tmpNomorNota,
                    );
                    $arrInjectorsTarget = array(
                        "items",
                    );
                    foreach ($injectors as $key => $val) {
                        $main[$key] = $val;
                        foreach ($arrInjectorsTarget as $target) {
                            foreach ($items as $xis => $iSpec) {
                                $id = isset($iSpec['id']) && $iSpec['id'] > 0 ? $iSpec['id'] : $xid;
                                if (isset($items[$id])) {
                                    $items[$id][$key] = $val;
                                }
                            }
                        }
                    }

                    //===signature
                    $dwsign = $tr->writeSignature($insertID, array(
                        "nomer" => $main['nomer'],
                        "step_number" => 1,
                        "step_code" => $this->jenisTr,
                        "step_name" => $this->configUi[$this->jenisTr]['steps'][1]['label'],
                        "group_code" => $this->configUi[$this->jenisTr]['steps'][1]['userGroup'],
                        "oleh_id" => "-100",
                        "oleh_nama" => "sys",
                        "keterangan" => $this->configUi[$this->jenisTr]['steps'][1]['label'] . " oleh sys",
                        "transaksi_id" => $insertID,
                    )) or die("Failed to write signature");
                    //                cekHitam("nulis transaksi sign ".$this->db->last_query());
                    $mongoList['sign'][] = $dwsign;
                    $idHis = array(
                        $stepNumber => array(
                            "step" => $stepNumber,
                            "trID" => $insertID,
                            "nomer" => $tmpNomorNota,
                            "counters" => $appliedCounters,
                            "counters_intext" => $appliedCounters_inText,
                        ),
                    );
                    $idHis_blob = blobEncode($idHis);
                    $idHis_intext = print_r($idHis, true);
                    $tr = new MdlTransaksi();
                    $dupState = $tr->updateData(array("id" => $insertID), array(
                        "next_step_num" => $nextProp['num'],
                        "next_step_code" => $nextProp['code'],
                        "next_step_label" => $nextProp['label'],
                        "next_group_code" => $nextProp['groupID'],

                        //===references
                        "id_master" => $insertID,
                        "id_top" => $insertID,
                        "ids_prev" => "",
                        "ids_prev_intext" => "",
                        "nomer_top" => $main['nomer'],
                        "nomers_prev" => "",
                        "nomers_prev_intext" => "",
                        "jenises_prev" => "",
                        "jenises_prev_intext" => "",
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,
                    )) or die("Failed to update tr next-state!");

                    $addValues = array(
                        //===references
                        "id_master" => $insertID,
                        "id_top" => $insertID,
                        "ids_prev" => "",
                        "ids_prev_intext" => "",
                        "nomer_top" => $main['nomer'],
                        "nomers_prev" => "",
                        "nomers_prev_intext" => "",
                        "jenises_prev" => "",
                        "jenises_prev_intext" => "",
                        "ids_his" => $idHis_blob,
                        "ids_his_intext" => $idHis_intext,
                    );
                    foreach ($addValues as $key => $val) {
                        $tableIn_master[$key] = $val;
                    }

                }
                if (sizeof($tableIn_master_values) > 0) {
                    if (isset($this->configCore[$this->jenisTr]['tableIn']['mainValues'])) {
                        $inserMainValues = array();
                        foreach ($this->configCore[$this->jenisTr]['tableIn']['mainValues'] as $key => $src) {
                            if (isset($tableIn_master_values[$key])) {
                                $dd = $tr->writeMainValues($insertID, array(
                                    "key" => $key,
                                    "value" => $tableIn_master_values[$key],
                                ));
                                $inserMainValues[] = $dd;
                                $mongoList['mainValues'][] = $dd;
                            }

                        }
                        if (sizeof($inserMainValues) > 0) {
                            $arrBlob = blobEncode($inserMainValues);
                            $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                        }
                    }
                }
                if (sizeof($main_add_values) > 0) {
                    $inserMainValues = array();
                    foreach ($main_add_values as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $inserMainValues[] = $dd;
                        $mongoList['mainValues'][] = $dd;
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }

                    //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
                if (sizeof($main_inputs) > 0) {
                    foreach ($main_inputs as $key => $val) {
                        $dd = $tr->writeMainValues($insertID, array("key" => $key, "value" => $val));
                        $inserMainValues[] = $dd;
                        $mongoList['mainValues'][] = $dd;
                    }
                    if (sizeof($inserMainValues) > 0) {
                        $arrBlob = blobEncode($inserMainValues);
                        $this->db->query("UPDATE transaksi SET indexing_main_values = '$arrBlob' WHERE id=$insertID");
                    }
                    //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
                if (sizeof($main_add_fields) > 0) {
                    foreach ($main_add_fields as $key => $val) {
                        $tr->writeMainFields($insertID, array("key" => $key, "value" => $val));
                    }
                    //                            cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                }
                if (sizeof($main_elements) > 0) {
                    foreach ($main_elements as $elName => $aSpec) {
                        $tr->writeMainElements($insertID, array(
                            "mdl_name" => isset($aSpec['mdl_name']) ? $aSpec['mdl_name'] : "",
                            "key" => isset($aSpec['key']) ? $aSpec['key'] : 0,
                            "value" => isset($aSpec['value']) ? $aSpec['value'] : "",
                            "name" => $aSpec['name'],
                            "label" => isset($aSpec['label']) ? $aSpec['label'] : "",
                            "contents" => isset($aSpec['contents']) ? $aSpec['contents'] : "",
                            "contents_intext" => isset($aSpec['contents_intext']) ? $aSpec['contents_intext'] : "",
                        ));
                        cekHitam("nulis transaksi element" . $this->db->last_query());
                        //==nebeng bikin inputLabels
                        $currentValue = "";
                        switch ($aSpec['elementType']) {
                            case "dataModel":
                                $currentValue = $aSpec['key'];
                                break;
                            case "dataField":
                                $currentValue = $aSpec['value'];
                                break;
                        }
                        if (array_key_exists($elName, $relOptionConfigs)) {
                            if (isset($relOptionConfigs[$elName][$currentValue])) {
                                if (sizeof($relOptionConfigs[$elName][$currentValue]) > 0) {
                                    foreach ($relOptionConfigs[$elName][$currentValue] as $oValueName => $oValSpec) {
                                        $inputLabels[$oValueName] = $oValSpec['label'];
                                        if (isset($oValSpec['auth'])) {
                                            if (isset($oValSpec['auth']['groupID'])) {
                                                $inputAuthConfigs[$oValueName] = $oValSpec['auth']['groupID'];
                                            }
                                        }
                                    }
                                }
                            }
                            else {
                                //						cekKuning("option $currentValue pada $eName TIDAK ada pilihannya");
                            }
                        }
                        //                                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());
                    }
                }
                if (sizeof($tableIn_detail) > 0) {
                    $insertIDs = array();
                    $insertDeIDs = array();
                    foreach ($tableIn_detail as $dSpec) {
                        $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $insertDeIDs[$insertID][] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                        if ($epID != 999) {
                            $insertEpID = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $insertEpID;
                            $insertDeIDs[$epID][] = $insertEpID;
                            $mongoList['detail'][] = $insertEpID;
                        }
                        // cekHitam("nulis transaksi data " . $this->db->last_query());
                        //                                cekUngu("LINE: " . __LINE__ . " <br> " . $this->db->last_query());
                    }
                    if (sizeof($insertIDs) == 0) {
                        die(lgShowAlert("Transaksi gagal disimpan karena rincian transaksi kosong."));
                    }
                    else {
                        $indexing_details = array();
                        foreach ($insertDeIDs as $key => $numb) {
                            $indexing_details[$key] = $numb;
                        }
                        foreach ($indexing_details as $k => $arrID) {
                            $arrBlob = blobEncode($arrID);
                            $this->db->query("UPDATE transaksi SET indexing_details = '$arrBlob' WHERE id=$k");
                            cekOrange($this->db->last_query());
                        }
                    }
                }
                if (sizeof($tableIn_detail2) > 0) {
                    $insertIDs = array();
                    foreach ($tableIn_detail2 as $dSpec) {
                        $insertIDs[] = $tr->writeDetailEntries($insertID, $dSpec);
                        $mongoList['detail'] = $insertIDs;
                        if ($epID != 999) {
                            $insertIDs[] = $tr->writeDetailEntries($epID, $dSpec);
                            $mongoList['detail'] = $insertIDs;
                        }
                        //                                cekUngu($this->db->last_query());
                    }
                }
                if (sizeof($tableIn_detail2_sum) > 0) {
                    $insertIDs = array();
                    foreach ($tableIn_detail2_sum as $dSpec) {
                        $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;

                        if ($epID != 999) {
                            $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $insertDetailID;
                            $mongoList['detail'][] = $insertDetailID;
                        }
                    }
                    //                            cekOrange($this->db->last_query());
                }
                if (sizeof($tableIn_detail_rsltItems) > 0) {
                    $insertIDs = array();
                    foreach ($tableIn_detail_rsltItems as $dSpec) {
                        $insertDetailID = $tr->writeDetailEntries($insertID, $dSpec);
                        $insertIDs[] = $insertDetailID;
                        $mongoList['detail'][] = $insertDetailID;
                        if ($epID != 999) {
                            $insertDetailID = $tr->writeDetailEntries($epID, $dSpec);
                            $insertIDs[] = $insertDetailID;
                            $mongoList['detail'][] = $insertDetailID;
                        }
                        //                                cekUngu($this->db->last_query());
                    }
                }
                if (sizeof($tableIn_detail_values) > 0) {
                    foreach ($tableIn_detail_values as $pID => $dSpec) {
                        if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues'])) {
                            $insertIDs = array();
                            foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues'] as $key => $src) {
                                if (isset($tableIn_detail[$pID])) {
                                    $dd = $tr->writeDetailValues($insertID, array(
                                        "produk_jenis" => $tableIn_detail[$pID]['produk_jenis'],
                                        "produk_id" => $pID,
                                        "key" => $key,
                                        "value" => $dSpec[$src],
                                    ));
                                    $insertIDs[$pID][] = $dd;
                                    $mongoList['detailValues'][] = $dd;
                                }
                                //                                        cekLime($this->db->last_query());
                            }
                            if (sizeof($insertIDs) > 0) {
                                $arrBlob = blobEncode($insertIDs);
                                $this->db->query("UPDATE transaksi SET indexing_detail_values = '$arrBlob' WHERE id=$insertID");
                            }
                        }
                    }
                }
                if (sizeof($tableIn_detail_values2_sum) > 0) {
                    foreach ($tableIn_detail_values2_sum as $pID => $dSpec) {
                        if (isset($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'])) {
                            foreach ($this->configCore[$this->jenisTr]['tableIn']['detailValues2_sum'] as $key => $src) {
                                $dd = $tr->writeDetailValues($insertID, array(
                                    "produk_jenis" => $tableIn_detail2_sum[$pID]['produk_jenis'],
                                    "produk_id" => $pID,
                                    "key" => $key,
                                    "value" => $dSpec[$src],
                                ));
                                $insertIDs[] = $dd;
                                $mongoList['detailValues'][] = $dd;
                            }
                        }
                    }
                }
                //endregion

                //===components akan langsung dieksekusi jika steps-nya tidak pakai approval
                $steps = $this->configUi[$this->jenisTr]['steps'];

                $compValidators = ($this->config->item('transaksi_value_required_components') != null) ? $this->config->item('transaksi_value_required_components') : array();
                $filterNeeded = false;
                //====registri value-gate
                $baseRegistries = array(
                    'main' => sizeof($main) > 0 ? $main : array(),
                    'items' => sizeof($items) > 0 ? $items : array(),
                    'items2' => sizeof($items2) > 0 ? $items2 : array(),
                    'items2_sum' => sizeof($items2_sum) > 0 ? $items2_sum : array(),
                    'items3' => sizeof($items3) > 0 ? $items3 : array(),
                    'items3_sum' => sizeof($items3_sum) > 0 ? $items3_sum : array(),
                    'rsltItems' => sizeof($rsltItems) > 0 ? $rsltItems : array(),
                    'rsltItems2' => sizeof($rsltItems2) > 0 ? $rsltItems2 : array(),
                    'tableIn_master' => sizeof($tableIn_master) > 0 ? $tableIn_master : array(),
                    'tableIn_detail' => sizeof($tableIn_detail) > 0 ? $tableIn_detail : array(),
                    'tableIn_detail2_sum' => sizeof($tableIn_detail2_sum) > 0 ? $tableIn_detail2_sum : array(),
                    'tableIn_detail_rsltItems' => sizeof($tableIn_detail_rsltItems) > 0 ? $tableIn_detail_rsltItems : array(),
                    'tableIn_detail_rsltItems2' => sizeof($tableIn_detail_rsltItems2) > 0 ? $tableIn_detail_rsltItems2 : array(),
                    'tableIn_master_values' => sizeof($tableIn_master_values) > 0 ? $tableIn_master_values : array(),
                    'tableIn_detail_values' => sizeof($tableIn_detail_values) > 0 ? $tableIn_detail_values : array(),
                    'tableIn_detail_values_rsltItems' => sizeof($tableIn_detail_values_rsltItems) > 0 ? $tableIn_detail_values_rsltItems : array(),
                    'tableIn_detail_values_rsltItems2' => sizeof($tableIn_detail_values_rsltItems2) > 0 ? $tableIn_detail_values_rsltItems2 : array(),
                    'tableIn_detail_values2_sum' => sizeof($tableIn_detail_values2_sum) > 0 ? $tableIn_detail_values2_sum : array(),
                    'main_add_values' => sizeof($main_add_values) > 0 ? $main_add_values : array(),
                    'main_add_fields' => sizeof($main_add_fields) > 0 ? $main_add_fields : array(),
                    'main_elements' => sizeof($main_elements) > 0 ? $main_elements : array(),
                    'main_inputs' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                    'main_inputs_orig' => sizeof($main_inputs) > 0 ? $main_inputs : array(),
                    "receiptDetailFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields'][1] : array(),
                    "receiptSumFields" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields'][1] : array(),
                    "receiptDetailFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptDetailFields2'][1] : array(),
                    "receiptSumFields2" => isset($this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1]) ? $this->config->item("heTransaksi_layout")[$this->jenisTr]['receiptSumFields2'][1] : array(),
                );
                // arrPrint($baseRegistries);
                //===
                $doWriteReg = $tr->writeRegistries($insertID, $baseRegistries) or die(lgShowError("Ada kesalahan", "Gagal saat berusaha  write base params into registries"));
                // $mongRegID = $doWriteReg;
                //                            arrPrint($baseRegistries);

                //endregion
                validateAllBalances($cabang_id);
                //region writelog
                $this->load->model("Mdls/" . "MdlActivityLog");
                $hTmp = new MdlActivityLog();
                $tmpHData = array(
                    "title" => $main['jenisTrName'],
                    "sub_title" => "auto new transaction",
                    "uid" => "-100",
                    "uname" => "sys",
                    "dtime" => date("Y-m-d H:i:s"),
                    "transaksi_id" => $insertID,
                    "deskripsi_old" => "",
                    "deskripsi_new" => "",
                    "jenis" => $this->jenisTr,
                    "ipadd" => $_SERVER['REMOTE_ADDR'],
                    "devices" => $_SERVER['HTTP_USER_AGENT'],
                    "category" => "transaksi",
                    "controller" => $this->uri->segment(1),
                    "method" => $this->uri->segment(2),
                    "url" => current_url(),
                    "keterangan" => "File: " . __FILE__ . " | Line: " . __LINE__,
                );
                $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                //endregion
                //$a->updateData("id")

                //region update data yang sudah diambil
                $o->setFilters(array());
                $where = array(
                    "id" => $fileID,
                );
                $updateOpname = array(
                    "cli" => "1",
                    "sync_cli_time" => dtimeNow("Y-m-d H:i"),
                    "transaksi_id" => $insertID,
                    "transaksi_no" => $insertNum,
                );

                $cekId = $o->updateData($where, $updateOpname) or die("Failed to update tr next-state!");
                cekHitam("LINE: " . __LINE__ . " || " . $this->db->last_query());

                //endregion

                //                            arrPrint($cekId);

            }
            else {
                cekOrange('tidak ada data untuk di eksekusi');
            }

            // arrPRint($mongoList);
            // arrPRint($mongoList);
            // arrPRint($datas);
            //             matiHere("belum commit -------------------- @" . __LINE__);
            $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
            if (sizeof($mongoList) > 0) {
                $mong = new MdlMongoMother();
                $tr = new MdlTransaksi();
                foreach ($mongoList as $gateList => $listIDSData) {
                    $tr->setFilters(array());
                    $tr->setSortBy(array());
                    $tr->setTableName($this->mongoTableList[$gateList]);
                    $tr->addFilter("id in (" . implode(",", $listIDSData) . ")");
                    $tmpTrm = $tr->lookUpAll()->result();

                    if (sizeof($tmpTrm) > 0) {
                        $mong->setTableName($this->mongoTableList[$gateList]);
                        foreach ($tmpTrm as $tmpMain) {
                            $transksi_main = json_decode(json_encode($tmpMain), true);
                            //                            arrPrint($transksi_main);
                            $mong->addData($transksi_main);
                        }
                    }
                    //                    cekHitam($listedTable[$gateList]);
                    //                    cekLime($gateList);

                }
            }

            matiHere("FILE excel sukses dieksekusi ");
        }
        else {
            cekHere("no data executed to perform opname");
        }
        // cekBiru("complit");


    }

    public function midValidate()
    {
        $fieldMidValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidators"] : array();
        $fieldMidPairedItemValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsPairedItem"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsPairedItem"] : array();
        $rowMidValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartRowMidValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartRowMidValidators"] : array();
        $fieldMidComparisonValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsComparison"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartFieldMidValidatorsComparison"] : array();

        $cCode = "_TR_" . $this->jenisTr;
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";

        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();
        if (sizeof($rowMidValidatorRules) > 0) {
            foreach ($rowMidValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$label is required";
                    $errRows[] = $field;
                }
            }
        }

        if (sizeof($fieldMidPairedItemValidatorRules) > 0) {
            $result = array();
            foreach ($fieldMidPairedItemValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                $result[$field] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }

            if (($result['hpp_sumber'] - $result['hpp_target']) > 0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];
                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
            elseif (($result['hpp_sumber'] - $result['hpp_target']) < -0.0000000100) {
                $selisih = $result['hpp_sumber'] - $result['hpp_target'];
                $errMsgs[] = "Nilai konversi tidak sama, silahkan cek harga per-unitnya.<br>$selisih";
                $errRows[] = "test";
            }
        }

        //        if (sizeof($rowOptValidatorRules) > 0) {
        //            foreach ($rowOptValidatorRules as $srcName => $srcSpec) {
        //                foreach ($srcSpec as $value => $pair) {
        //                    if (isset($_SESSION[$cCode]['main'][$srcName]) && $_SESSION[$cCode]['main'][$srcName] == $value) {
        //                        foreach ($pair as $k => $v) {
        //                            if (!isset($_SESSION[$cCode]['main'][$k])) {
        //                                $errMsgs[] = "$k is required";
        //                                $errRows[] = $k;
        //                            }
        //                        }
        //                    }
        //                }
        //            }
        //        }

        if (sizeof($fieldMidValidatorRules) > 0) {
            if (isset($_SESSION[$cCode]['items']) && sizeof($_SESSION[$cCode]['items']) > 0) {
                foreach ($_SESSION[$cCode]['items'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }
                }
            }

            if (isset($_SESSION[$cCode]['rsltItems']) && sizeof($_SESSION[$cCode]['rsltItems']) > 0) {
                foreach ($_SESSION[$cCode]['rsltItems'] as $xid => $iSpec) {
                    $id = $iSpec['id'];
                    $name = $iSpec['nama'];
                    if ((isset($iSpec['disabled']) && $iSpec['disabled'] == "0") || !isset($iSpec['disabled'])) {
                        if (!isset($errFields[$id])) {
                            $errFields[$id] = array();
                        }
                        foreach ($fieldMidValidatorRules as $field => $label) {
                            if (!isset($iSpec[$field])) {
                                $errMsgs[] = "item $label $name is required";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if (!is_numeric($iSpec[$field])) {
                                $errMsgs[] = "item $label $name must be a valid number";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                            if ($iSpec[$field] < 0.5) {
                                $errMsgs[] = "item $label $name must be > 0";
                                $errLines[] = $id;
                                $errFields[$id][] = $field;
                            }
                        }
                    }
                }
            }
        }

        if (sizeof($fieldMidComparisonValidatorRules) > 0) {
            $result = array();
            $labels = array();
            foreach ($fieldMidComparisonValidatorRules as $field => $label) {
                if (!isset($_SESSION[$cCode]['main'][$field])) {
                    $errMsgs[] = "$field value is required";
                    $errRows[] = $field;
                }
                if ($_SESSION[$cCode]['main'][$field] < 0) {
                    $errMsgs[] = "$field must be >= 0";
                    $errRows[] = $field;
                }
                $labels[$label] = $field;
                $result[$label] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }
            if ($result["sumber"] > $result["target"]) {
                $labelSrc = isset($labels["sumber"]) ? $labels["sumber"] : "";
                $labelTarget = isset($labels["target"]) ? $labels["target"] : "";
                $errMsgs[] = "Nilai $labelSrc lebih besar dari nilai $labelTarget.";
                $errRows[] = "test";
            }
        }
        //arrPrint($result);
        //arrPrint($labels);
        //mati_disini();
        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);
            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }
            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
        //        else {
        //
        //            $actionTarget = "top.BootstrapDialog.show(                                   {
        //                                       title:'preview',
        //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
        //                                        draggable:false,
        //                                        size:top.BootstrapDialog.SIZE_WIDE,
        //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
        //                                        closable:true,
        //                                        }
        //                                        );";
        //
        //            echo "<html>";
        //            echo "<head>";
        //            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //            echo "</head>";
        //            echo "<body onload=\"$actionTarget\">";
        //            echo "</body>";
        //
        //        }
    }

    public function unionValidate()
    {
        $unionValidatorRules = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartUnionValidators"]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]["shoppingCartUnionValidators"] : array();
        $cCode = "_TR_" . $this->jenisTr;
        $rawPrevURL = isset($_GET['rawPrev']) ? $_GET['rawPrev'] : "";
        $errMsgs = array();
        $errLines = array();
        $errFields = array();
        $errRows = array();

        if (sizeof($unionValidatorRules) > 0) {
            $result = array();
            foreach ($unionValidatorRules as $uSpec) {
                foreach ($uSpec as $field => $label) {
                    if (!isset($_SESSION[$cCode]['main'][$field])) {
                        //                        $errMsgs[] = "$field value is required";
                        //                        $errRows[] = $field;
                        $result[$field] = "$label value is required";
                    }
                }
                //                $result[$field] = isset($_SESSION[$cCode]['main'][$field]) ? $_SESSION[$cCode]['main'][$field] : 0;
            }
            if (sizeof($result) > 1) {
                foreach ($result as $field => $label) {
                    $errMsgs[] = $label;
                    $errRows[] = $field;
                }
            }

        }


        if (sizeof($errMsgs) > 0) {
            $_SESSION['errMsg'] = implode("<br>", $errMsgs);
            //            print_r($_SESSION['errMsg']);
            //            echo "<script>";
            //            echo "top.getData('" . base_url() . "_shoppingCart/viewCart/" . $this->jenisTr . "?selID=$id','shopping_cart');";
            //            echo "</script>";
            if (sizeof($errLines) > 0) {
                $_SESSION['errLines'] = $errLines;
            }
            if (sizeof($errFields) > 0) {
                $_SESSION['errFields'] = $errFields;
            }

            echo lgShowAlert($_SESSION['errMsg']);
            die();
        }
        //        else {
        //
        //            $actionTarget = "top.BootstrapDialog.show(                                   {
        //                                       title:'preview',
        //                                        message: " . '$' . "('<div></div>').load('" . base_url() . "Transaksi/preview/" . $this->jenisTr . "?rawPrev=$rawPrevURL'),
        //                                        draggable:false,
        //                                        size:top.BootstrapDialog.SIZE_WIDE,
        //                                        type:top.BootstrapDialog.TYPE_SUCCESS,
        //                                        closable:true,
        //                                        }
        //                                        );";
        //
        //            echo "<html>";
        //            echo "<head>";
        //            echo "<script src=\"" . cdn_suport()."AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
        //            echo "</head>";
        //            echo "<body onload=\"$actionTarget\">";
        //            echo "</body>";
        //
        //        }
    }

    public function syncSatuan()
    {
        $tokoID = "20211042";//pandega
        // $tokoID = "20211041";//pogong
        $this->load->model("Mdls/MdlSatuan");
        $this->load->model("Mdls/MdlSupplies");
        $s = new MdlSatuan();
        $su = new MdlSupplies();
        $s->addFilter("toko_id='$tokoID'");
        $tmp = $s->lookupAll()->result();
        $satuan = array();
        foreach ($tmp as $tmp0) {
            $satuan[$tmp0->id] = $tmp0->nama;
        }
        $su->addFilter("toko_id='$tokoID'");
        $tmpSupplies = $su->lookupAll()->result();
        foreach ($tmpSupplies as $tmpSupplies_0) {
            $supplies[$tmpSupplies_0->id] = $tmpSupplies_0->satuan;
        }
        foreach ($supplies as $sID => $satuanLabel) {
            $this->db->trans_start();
            $su->setFilters(array());
            if (in_array($satuanLabel, $satuan)) {
                $keyID = array_search($satuanLabel, $satuan);
                ceklime($sID . " " . $satuan[$keyID]);
                $su->updateData(array("id" => $sID), array("satuan_id" => $keyID));
                cekHitam($this->db->last_query());
            }
        }
        // arrPrint($supplies);
        // arrPrint($tmp);
        matiHere("belum commit -------------------- @" . __LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
    }

    //endregion

    public function cekTransaksiGantung()
    {
        // link:: opname/Opname/cekTransaksiGantung/Produk
        /* ---------------------------------------------------------------------------
         * dikaitkan hak akses untuk memberikan link yg bisa diexsekusi oleh audien
         * ---------------------------------------------------------------------------*/

        // $myId = my_id();
        // // $myId = 17;
        // // cekPink(my_id() . " :: $myId");
        // $this->load->helper("he_access_right");
        // $this->load->config("heTransaksi_ui");
        $configUi = $this->config->item("heTransaksi_ui");
        // $src_access = alowedAccess_he_access_right($myId, $configUi);
        // arrPrintKuning($src_access);
        // arrPrintKuning($configUi);
        // ---------------------------------------------------------------------------
        $masterLabel = array();
        $stepMaster = array();
        $stepModul = array();
        $stepLabel = array();
        $arrNextAction = array();
        foreach ($configUi as $master_jenis => $params) {
            $modul = isset($params["modul"]) ? $params["modul"] : "";
            $mLabel = $params["label"];
            $steps = $params["steps"];

            $masterLabel[$master_jenis] = $mLabel;
            foreach ($steps as $step => $step_params) {
                $target = isset($step_params["target"]) ? $step_params["target"] : "";
                $tLabel = $step_params["label"];

                $stepMaster[$target] = $master_jenis;
                $stepModul[$target] = $modul;
                $stepLabel[$target] = $tLabel;

            }
        }

        // $hak_jenis = array();
        // foreach ($src_access as $mJenis => $src_accesies) {
        //     foreach ($src_accesies as $src_accesy) {
        //         // arrPrintPink($src_accesy);
        //         foreach ($src_accesy as $ac_jenis => $item) {
        //             $hak_jenis[] = $ac_jenis;
        //         }
        //     }
        // }
        // asort($hak_jenis);
        // arrPrintWebs($hak_jenis);

        $this->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();

        $jenis_gantung = $tr->callGantunganTransaksi(true);
        // arrPrint($jenis_gantung);
        // ceklIme($this->db->last_query());
        // arrPrintPink($jenis_gantung);

        $var = "";
        $kelompokMaster = array();
        $preMaster = array();
        foreach ($jenis_gantung as $jenis_tr => $item) {
            $jenis_master = $stepMaster[$jenis_tr];

            $var .= "";
            // arrPrint($item);
            $kelompokMaster[$jenis_master][$jenis_tr] = $item;
            $arrNextAction[$jenis_tr] = array(
                "next_step_num" => $item[0]->next_step_num,
                "next_step_code" => $item[0]->next_step_code,
            );
            foreach ($item as $preITemsData) {
                // arrPrintPink($preITemsData);
                $preMaster[$jenis_tr][$preITemsData->jenis_master][$preITemsData->next_step_code] = $preITemsData->next_step_num;
            }
            // $kelompokMaster[$jenis_master] = $item;
        }
        // arrprint($preMaster);
        // $arrNextPIC = callNextPIC($arrNextAction);
        // foreach($kelompokMaster as $masterTr => $tmTR){
        //     if(isset($preMaster[$masterTr])){
        //         arrPrintPink($preMaster[$masterTr]);
        //     }
        //     else{
        //         arrPrintPink($preMaster[$masterTr]);
        //     }
        //     // arrprint($tmTR);
        // }
        // arrPrint($arrNextPIC);
        // if (sizeof($arrNextPIC) > 0) {
        //     if (isset($arrNextPIC[$tmpSpec->next_substep_code][$tmpSpec->next_substep_num])) {
        //         $next_pic = "";
        //         $nob = 1;
        //         foreach ($arrNextPIC[$tmpSpec->next_substep_code][$tmpSpec->next_substep_num] as $spec) {
        //             if ($tmpSpec->cabang_id == $spec['cabang_id']) {
        //
        //                 if ($next_pic == "") {
        //                     $next_pic = "$nob. " . $spec['nama'];
        //                 }
        //                 else {
        //                     $nob++;
        //                     $next_pic = $next_pic . "<br>" . "$nob. " . $spec['nama'];
        //                 }
        //
        //             }
        //         }
        //         $tmp2Datas['next_pic'] = $next_pic;
        //
        //     }
        // }

        $data = array(
            "mode" => "cekTransaksiGantung",
            "title" => "gantungan",
            "sub_title" => "",
            "items" => $this->y['entries'],
            "kelompokMaster" => $kelompokMaster,
            "masterLabel" => $masterLabel,
            "stepLabel" => $stepLabel,
            "stepModul" => $stepModul,
        );

        // cekBiru();
        $this->load->view('opname', $data);
    }

    public function cekOpnameAktive($opname_condites = array())
    {

        $this->load->model("Mdls/MdlDashboardOpname");
        $dop = new MdlDashboardOpname();

        $dop->setCabangId(my_cabang_id());
        $srcOpname = $dop->cekOpnameAktive($opname_condites);
        // showLast_query("hijau");
        // cekHijau(sizeof($srcOpname));

        return $srcOpname;
    }

    public function cekOpnameAktivSession($opname_condites = array())
    {

        $this->load->model("Mdls/MdlDashboardOpname");
        $dop = new MdlDashboardOpname();
        $op_condites = array(
                "status" => "1",
                "confirm_id" => "0",
                "cabang_id" => my_cabang_id(),
            ) + $opname_condites;
        $this->db->limit(1);
        $this->db->order_by("id", "ASC");
        $srcOpname = $dop->lookupByCondition($op_condites)->row();
        // showLast_query("hijau");
        // cekHijau(sizeof($srcOpname));
        $var = isset($srcOpname->session_opname) ? $srcOpname->session_opname : 0;

        return $var;
    }

    public function viewOpnameAktive()
    {
        header("refresh:60");
        $this->load->model("Mdls/MdlDashboardOpname");
        $dop = new MdlDashboardOpname();

        $dop->setCabangId(false);
        // $dop->setCabangId(my_cabang_id());
        $srcDatas = $dop->cekOpnameAktive();

        $condites = array(// "cabang_id" => my_cabang_id(),

        );
        // $srcDatas = $this->cekOpnameAktive($condites);
        $dt_opname = array();
        foreach ($srcDatas as $srcData) {
            $download = $srcData->oleh_id;
            $upload = $srcData->done_id;
            $acc1 = $srcData->acc_id_1;
            $acc2 = $srcData->acc_id_2;
            $cb_id = $srcData->cabang_id;
            $jenis = $srcData->jenis;
            $gudang_id = $srcData->gudang_id;
            // $g_jenis = $gudang_id < 0 ? "good" : "not_good";
            $g_jenis = $gudang_id < 0 ? "good" : "project";
            // switch ($gudang_id){
            //     case
            // }

            if ($download > 0) {
                $perbuatan = "download";

                $ky_posisi = $cb_id . "_" . $jenis . "_" . $g_jenis . "_" . $perbuatan;
                // $dt_opname[$ky_posisi] = "ok";
                $dt_opname[$cb_id][$jenis][$g_jenis][$perbuatan] = "ok";
            }
            if ($upload > 0) {
                $perbuatan = "upload";

                $dt_opname[$cb_id][$jenis][$g_jenis][$perbuatan] = "ok";
            }
            if ($acc1 > 0) {
                $perbuatan = "acc1";

                $dt_opname[$cb_id][$jenis][$g_jenis][$perbuatan] = "ok";
            }
            if ($acc2 > 0) {
                $perbuatan = "acc2";

                $dt_opname[$cb_id][$jenis][$g_jenis][$perbuatan] = "ok";
            }
        }

        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $srcBrands = $cb->lookupAll()->result();
        // arrPrintHijau($srcBrands);
        $dataCb = array();
        foreach ($srcBrands as $srcBrand) {
            $dataCb[$srcBrand->id] = $srcBrand->nama;
        }
        // arrPrintHijau($dataCb);
        // arrPrint($srcDatas);
        // arrPrintWebs($dt_opname);


        $header_000 = array(
            "Produk" => array(
                "label" => "produk"
            ),
            "Supplies" => array(
                "label" => "supplies"
            ),
            // "ProdukRakitan" => array(
            //     "label"  => "produk rakitan",
            //     "cabang" => "25",
            // ),
        );
        $header_00 = array(
            "good" => array(
                "label" => "gudang good",

            ),
            // "not_good" => array(
            //     "label" => "gudang not good",
            //
            // ),
            "project" => array(
                "label" => "gudang project",

            ),
        );
        $header_0 = array(

            "download" => array(
                "label" => "download",
            ),
            "upload" => array(
                "label" => "upload",
            ),
            "acc1" => array(
                "label" => "acc I",
            ),
            "acc2" => array(
                "label" => "acc II",
            ),

        );


        $data = array(
            "mode" => "viewOpnameAktive",
            "title" => "opname",
            "subtitle" => "opname",
            // "content"        => $contens,
            "header_0" => $header_0,
            "header_00" => $header_00,
            "header_000" => $header_000,
            "dataCb" => $dataCb,

            "dt_opname" => $dt_opname,
            "link_opname_data" => MODUL_PATH . "Opname/viewOpnameData/",
            "link_opname_confirm" => MODUL_PATH . "Opname/doConfirmOpname/",
            // "companyProfile" => $companyProfile,
            //            "fixedElements"=> $fixedElements,
        );

        $this->load->view('opname', $data);
    }

    public function viewOpnameData()
    {

        // arrPrintHijau(url_segment());
        $op_jenis = url_segment(3);
        $this->load->model("Mdls/MdlDashboardOpnameData");
        $dop = new MdlDashboardOpnameData();

        $dop->setCabangId(false);
        // $dop->setCabangId(my_cabang_id());
        // $srcDatas = $dop->cekOpnameAktive();
        $srcDatas = $dop->lookupAktiveOpname();

        $condites = array(// "cabang_id" => my_cabang_id(),

        );
        // $srcDatas = $this->cekOpnameAktive($condites);
        $dt_opname = array();
        foreach ($srcDatas as $srcData) {

        }

        $this->load->model("Mdls/MdlCabang");
        $cb = new MdlCabang();
        $srcBrands = $cb->lookupAll()->result();
        // arrPrintHijau($srcBrands);
        $dataCb = array();
        foreach ($srcBrands as $srcBrand) {
            $dataCb[$srcBrand->id]["label"] = $srcBrand->nama;
        }
        $header_000 = $dataCb;
        $header_00 = array(
            "good" => array(
                "label" => "gudang good",

            ),
            "not_good" => array(
                "label" => "gudang not good",

            ),
        );
        $header_0 = array(

            "download" => array(
                "label" => "download",
            ),
            "upload" => array(
                "label" => "upload",
            ),
            "acc1" => array(
                "label" => "acc I",
            ),
            "acc2" => array(
                "label" => "acc II",
            ),

        );

        switch ($op_jenis) {
            case "Produk":
                $srtMdl = "Mdl" . $op_jenis;
                $this->load->model("Mdls/$srtMdl");
                $obj = new $srtMdl();
                $src_pro = $obj->lookupAll()->result();
                // arrPrintHijau($src_pro);
                $data_produk = array();
                foreach ($src_pro as $item) {
                    $data_produk[$item->id] = $item->nama;
                }
                break;
            case "Supplies":
                break;
            case "ProdukRakitan";
                break;
        }

        $data = array(
            "mode" => "viewOpnameData",
            "title" => "opname",
            "subtitle" => "opname",
            // "content"        => $contens,
            "header_0" => $header_0,
            "header_00" => $header_00,
            "header_000" => $header_000,
            "data_produk" => $data_produk,
            "dt_opname" => $dt_opname,
            "link_opname_data" => MODUL_PATH . "viewOpnameAktive/",
            // "companyProfile" => $companyProfile,
            // "fixedElements"=> $fixedElements,
        );

        $this->load->view('opname', $data);
    }

    public function doConfirmOpname()
    {
        $this->load->model("Mdls/MdlDashboardOpname");
        $dop = new MdlDashboardOpname();
        $condites = array(
            "confirm_id" => 0,
        );
        $datas = array(
            "dtime_confirm" => dtimeNow(),
            "confirm_id" => my_id(),
            "confirm_nama" => my_name(),
        );
        $this->db->trans_start();
        $ggg = $dop->updateData($condites, $datas);
        showLast_query("merah");

        // matiHere();
        $this->db->trans_complete();
        $alerts = array(
            "type" => "success",
            "title" => "Berhasil",
            "html" => "seluruh stok opname dinyatakan telah selesai ",
        );
        echo swalAlert($alerts);
        echo topReload(3000);

        // echo "<script>top.location.reload();</script>";
    }

    public function test()
    {
        $this->load->model("Mdls/MdlDashboardOpnameData");
        $dop = new MdlDashboardOpnameData();

        $dop->setCabangId(false);
        // $dop->setCabangId(my_cabang_id());
        // $srcDatas = $dop->cekOpnameAktive();
        $dop->setCabangId("-1");
        $srcDatas = $dop->callSumJmlStokOpname();
        arrPrintPink($srcDatas);
        showLast_query("kuning");
        cekHere();
    }

    public function historyDownloadOpname($opname_condites = array())
    {

        $this->load->model("Mdls/MdlDashboardOpname");
        $dop = new MdlDashboardOpname();
        //        $dop->setCabangId(my_cabang_id());
        if (sizeof($opname_condites) > 0) {
            foreach ($opname_condites as $f) {
                $dop->addFilter($f);
            }
        }
        $this->db->order_by("id", "desc");
        $srcOpname = $dop->lookupAll()->result();
        // showLast_query("hijau");
        // cekHijau(sizeof($srcOpname));

        return $srcOpname;
    }

    public function reDownloadXls()
    {

        //        arrPrint($this->uri->segment_array());
        //        matiHere(__LINE__);
        //        $mdlProduk = "Mdl".$this->uri->segment(5);
        $param = $this->uri->segment(5);
        $mdlProduk = "MdlProduk2";
        $this->load->model("Mdls/" . $mdlProduk);
        $p = new $mdlProduk();
        $dasboard_id = $this->uri->segment(4);
        $this->load->model("Mdls/MdlDashboardOpnameData");
        $dopd = new MdlDashboardOpnameData();
        $dopd->addFilter("dashboard_opname_id='$dasboard_id'");
        $tmp = $dopd->lookUpAll()->result();

        $produksOpname = array();
        foreach ($tmp as $pOpname) {

        }
        $cabangID = $tmp[0]->cabang_id;
        $this->load->model("Mdls/MdlCabang");
        $c = new MdlCabang();
        $c->addFilter("id='$cabangID'");
        $tCabang = $c->lookUpAll()->result();
        $cabang_nama = $tCabang[0]->nama;


        //dataproduk =
        $tmpProduk = $p->lookUpAll()->result();
        $produks = array();
        foreach ($tmpProduk as $aa) {
            $produks[$aa->id] = (array)$aa;
        }
//        arrPrint($produks);
//        matiHere($cabang_nama);
//        $cabang_nama =
        $headers = array(
            "cabang_id" => array(
                "label" => "cID",
                "type" => "integer",
            ),
            "gudang_id" => array(
                "label" => "gID",
                "type" => "integer",
            ),

            "jenis" => array(
                "label" => "jenis",
                "type" => "string",
            ),
            "produk_id" => array(
                "label" => "pID",
                "type" => "integer",
            ),

            "kode" => array(
                "label" => "kode",
                // "type"  => "integer",
                "type" => "string",
            ),

            "no_part" => array(
                "label" => "nomer part",
                "type" => "string",
            ),

            "produk_nama" => array(
                "label" => "produk",
                "type" => "string",
            ),
            "kategori_nama" => array(
                "label" => "kategori",
                "type" => "string",
            ),
            "supplier_nama" => array(
                "label" => "supplier",
                "type" => "string",
            ),
            "jml_stok_buku" => array(
                "label" => "stok (buku) " . $cabang_nama,
                "type" => "integer",
            ),

            "jml_stok_opname" => array(
                "label" => "stok riil",
                "type" => "integer",
            ),

            "serial" => array(
                "label" => "serial number",
                "type" => "string",
            ),
        );

        $produksOpname = array();
        foreach ($tmp as $i => $pOpname) {
            $cb_id = $pOpname->cabang_id;
            $addArray = array(
                "kode" => isset($produks[$pOpname->produk_id]["kode"]) ? $produks[$pOpname->produk_id]["kode"] : "",
                "no_part" => $produks[$pOpname->produk_id]["no_part"],
                "jproduk" => $param,
            );
            $produksOpname[$i] = (array)$pOpname + $addArray;
        }

        $datas = array();
        foreach ($produksOpname as $produksOpname_0) {
            $datas[] = (object)$produksOpname_0;
        }

        $this->load->model("Mdls/MdlDashboardOpname");
        $dp = new MdlDashboardOpname();
        $dp->addFilter("id='$dasboard_id'");
        $tempDasboard = $dp->lookUpAll()->result();
        $dateDownload = $tempDasboard[0]->dtime_start;

        // if(ipadd() == MGK_LIVE){
        //     arrprint($headers);
        //     // arrprint($datas);
        //     matiHere(__LINE__);
        // }

        $this->load->library('Excel');
        $ex = new Excel();
        $ex->setTitleFile("Inventory $param $dateDownload");
        $ex->setDatas($datas);
        $ex->setHeaders($headers);
//        matiHere();
        $ex->writer();


//        arrPrint($produksOpname);
//        cekLime($this->db->last_query());


    }
}