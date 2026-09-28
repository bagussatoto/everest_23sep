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

    //===
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
        $className = "Mdl" . $this->uri->segment(1);
        $clsDir = "MdlFolder" . $this->uri->segment(3);

        //        $clsDir = "MdlFolderProduk";
        //cekHere(":: $className :: $clsDir ::");

        //region load mdl rpoduk untuk ambil direktory
        $this->load->model("Mdls/" . $clsDir);
        $this->load->model("Mdls/" . $className);

        $pr = new $clsDir;
        $opn = new $className;
        $indexFieldName = "id";
        $selectedFolders = $opn->getFolderListed();
        $pr->setFilters(array());
        $pr->addFilter("jenis='folder'");
        $dataValue = $pr->lookupAll()->result();

        //        cekhitam($this->db->last_query());
        //        arrPrint($dataValue);
        //        arrPrint($selectedFolders);
        //        cekHijau($this->db->last_query());
        $result = array();
        foreach ($dataValue as $i => $dataTemp) {
            $temp = array();
            foreach ($selectedFolders as $kolom => $alias) {
                $temp[$kolom] = $dataTemp->$kolom;
            }
            $result[] = $temp;

        }


        $this->y['entries'] = $result;
        $this->h['entries'] = $selectedFolders;

    }

    public function index()
    {
        // print_r($this->z['hisPrice']);die();
        $scriptLoad = "<script>$(document).on('ready', function(){
                     $('#myModal').modal('show');
                     });</script>";

        $p = New Layout("", "", "application/template/default.html");
        $rekName = str_replace(" ", "_", "persediaan " . strtolower($this->uri->segment(3)));
        //        $rekName = str_replace(" ", "_", "persediaan produk");
        cekHere($rekName);

        $attached = isset($_GET['attached']) ? $_GET['attached'] : 0;
        if ($attached == '1') {
            $_SESSION['backLink'] = unserialize(base64_decode($_GET['backLink']));
        }
        $formTarget = base_url() . get_class($this) . "/save/" . $this->uri->segment(1);
        //        $formTarget = base_url() . get_class($this) . "/save/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?q=" . $this->q . "&attached=$attached";
        $fupdateLink = base_url() . get_class($this) . "/view/" . $this->uri->segment(3) . "/$rekName";
        $title = "";
        $btnClick = "BootstrapDialog.show(
                                   {
                                        title:'Modify $title',
                                        cssClass: 'edit-dialog',
                                        message: $('<div></div>').load('" . $fupdateLink . "'),
                                        draggable:false,
                                        closable:true,
                                        });";
        $buttonLabel = "save values";
        $data = array(
            "mode" => $this->uri->segment(2),
            "items" => $this->y['entries'],
            "arrayHeader" => $this->h['entries'],
            "scriptLoad" => $scriptLoad,
            "btnClick" => $btnClick,
        );


        $this->load->view('opname', $data);
        $this->session->errMsg = "";
    }

    public function view()
    {
        //        arrPrint($this->uri->segment_Array());
        $className = "Mdl" . $this->uri->segment(1);
        $actionForm = base_url() . $this->uri->segment(1) . "/doPrint" . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4);
        $p = New Layout("", "", "application/template/default.html");
        //region load mdl prpoduk untuk ambil directory
        $clsName = "Mdl" . $this->uri->segment(3);
        $clsFolder = "MdlFolder" . $this->uri->segment(3);
        if ($this->uri->segment(3) == "ProdukRakitan") {
            $getName = "Produk";
        }
        else {
            $getName = $this->uri->segment(3);
        }
        $clsFolderRakitan = "MdlFolder" . $getName . "Rakitan";
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
        //        $pr->addFilter("jenis='folder'");
        //---------------------------------
        $cb->addFilter("id='" . $this->session->login['cabang_id'] . "'");
        $arrCabang = $cb->lookupAll()->result();
//        arrPrintWebs($arrCabang);
        //---------------------------------
//cekHitam($clsFolderRakitan);
        $fo->setFilters(array());
        $dataValue = $fo->lookupAll()->result();
        $resultReguler = array();
        foreach ($dataValue as $i => $dataTemp) {
            $temp = array();
            foreach ($selectedFolders as $kolom => $alias) {
                $temp[$kolom] = $dataTemp->$kolom;
            }
            $resultReguler[] = $temp;
        }

        if (file_exists(APPPATH . "models/Mdls/$clsFolderRakitan.php")) {

            $this->load->model("Mdls/" . $clsFolderRakitan);
            $forkt = new $clsFolderRakitan;
            $forkt->setFilters(array());
            $dataValueRakitan = $forkt->lookupAll()->result();
            $resultRakitan = array();
            foreach ($dataValueRakitan as $i => $dataTemp) {
                $temp = array();
                foreach ($selectedFolders as $kolom => $alias) {
                    $temp[$kolom] = $dataTemp->$kolom;
                }
                $resultRakitan[] = $temp;
            }
        }
        $resultReguler[] = array(
            "id" => 0,
            "nama" => "NON CATEGORY",
        );
        //dioffkan dulu 07-11-2022-----------------------------
//        $tipeCabang = "produksi";
//        if ($arrCabang[0]->tipe == $tipeCabang) {
//            switch ($this->uri->segment(3)) {
//                case "Produk":
//                    $result = $resultReguler;
//                    break;
//                case "ProdukRakitan":
//                    $result = $resultRakitan;
//                    break;
//                case "Supplies":
//                    $result = $resultReguler;
//                    break;
//            }
//        }
//        else {
//            $result = array_merge($resultReguler, $resultRakitan);
//        }
        // semua data didownload
        $result = array_merge($resultReguler, $resultRakitan);

        //region cabang
        //        arrPRint($arrCabang);
        $tik_cabang = "<div class=''>Pilih Cabang</div>";
        $tik_cabang .= "<div class='funkyradio'>";
        if (sizeof($arrCabang) > 0) {
            foreach ($arrCabang as $i => $d_cabang) {
                $c_id = $d_cabang->id;
                $c_nama = $d_cabang->nama;

                $tik_cabang .= "<div class='funkyradio-success'>
            <input type='hidden' name='c_nama[$c_id]' value='$c_nama'>
            <input type='checkbox' name='cabang[]' id='checkbox_$c_id' value='$c_id' checked/>
            <label for='checkbox_$c_id' class='no-margin no-padding'>$c_nama</label>
        </div>";
            }
        }
        $tik_cabang .= "</div>";
        //endregion


        // region pilih kategori folder
        $tik_folder = "<div class=''>Pilih kategori</div>";
        $tik_folder .= "<div class='funkyradio'>";
        foreach ($result as $i => $dataTemp) {
            $f_id = $dataTemp['id'];
            $f_nama = $dataTemp['nama'];
            $tik_folder .= "<div class='funkyradio-success'>
            <input type='checkbox' name='folder[]' id='checkbox_$f_id' value='$f_id' checked/>
            <label for='checkbox_$f_id' class='no-margin no-padding'>$f_nama</label>
        </div>";
        }
        $tik_folder .= "</div>";
        // endregion pilih kategori folder

        $modal_isi = "<div class='row'>";
        $modal_isi .= "<div class='col-md-6'>";
        $modal_isi .= "$tik_folder";
        $modal_isi .= "</div>";

        $modal_isi .= "<div class='col-md-6'>";
        $modal_isi .= "Tuliskan nama produk";
        $modal_isi .= "<input type='text' name='cari' class='form-control' placeholder='key words'>";

        $modal_isi .= "<hr>";
        $modal_isi .= "$tik_cabang";
        $modal_isi .= "</div>";
        $modal_isi .= "<div class='col-md-6' style='margin-top:10px;'>";
        $modal_isi .= "<span class='pull-left'><button type='button' class='btn btn-default' data-dismiss='modal'>&times; Close</button></span>";
        $modal_isi .= "<span class='pull-right'>";
        $modal_isi .= "<button type='submit' name='excel' value='download' class='btn btn-success'><i class='fa  fa-file-excel-o'>&nbsp;</i>Excel</button>&nbsp;";
        // $modal_isi .= "</span>";
        $modal_isi .= "<button type='submit' class='btn btn-info'><i class='fa fa-print'>&nbsp;</i>Print</button>&nbsp;";
        $modal_isi .= "</span>";
        $modal_isi .= "</div>";
        $modal_isi .= "</div>";

        $modal_isi .= "<div class='clearfix'></div>";
        $strMain = "<form method='post' action='$actionForm' target='_blank'>";
        $strMain .= $modal_isi;
        $strMain .= "</form>";

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


            //            matiHere("comat camit " . __LINE__);

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

    public function doPrint()
    {
        //        arrPrint($this->uri->segment_array());
        $clsName = "Mdl" . $this->uri->segment(1);
        $jnProdMdl = "Mdl" . $this->uri->segment(3);
        //        $rekName = urldecode($this->uri->segment(4));
        $rekName = str_replace("_", " ", $this->uri->segment(4));
        $mdlCabang = "MdlCabang";

        $cb_id = $_POST['cabang'];
        //        arrPrint($_POST);
        if (sizeof($cb_id) > 0) {
            $cid_list = "(";
            foreach ($cb_id as $c_id) {
                $cid_list .= "'$c_id',";
            }
            $cid_list = rtrim($cid_list, ",");
            $cid_list .= ")";
        }
        if (sizeof($_POST['folder']) > 0) {
            $folder_list = "(";
//            $_POST['folder'][] = 0;
            foreach ($_POST['folder'] as $i => $folder) {
                $folder_list .= "'$folder',";
            }
            $folder_list = rtrim($folder_list, ",");
            $folder_list .= ")";
        }

        switch ($this->uri->segment(3)) {
            case "Produk":
                $jnProdMdl = "Mdl" . $this->uri->segment(3) . "2";
                break;
            default:
                $jnProdMdl = $jnProdMdl;
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
        $arrCabangName = array();
        foreach ($arrCabang as $cabData) {
            $arrCabangName[$cabData->id] = $cabData->nama;
        }
        if ($_POST['cari']) {
            $pr->addFilter("jenis in ('item', 'item_rakitan')");
            $pr->setSearch($_POST['cari']);
            $produkList = $pr->lookupLimitedBySelected()->result();
            //            cekHijau($this->db->last_query());
        }
        else {
//            $pr->addFilter("jenis!='folder'");
//            $pr->addFilter("jenis!='paket'");
            $arrWhere = array(
                "jenis!=" => "folder",
            );
            $arrWhere2 = "(jenis!='folder' and jenis!='paket')";
            $this->db->where($arrWhere2);
            $pr->addFilter("folders in $folder_list'");

            //            $pr->addFilter("cabang_id in $cid_list");
            $produkList = $pr->lookupAll()->result();

//            showLast_query("hijau");
        }
//        mati_disini("$jnProdMdl :: " . sizeof($produkList));

        //region cari stok dari leger
        if ($this->uri->segment(3) == "ProdukRakitan") {
            $getName = "Produk";
            $rekName = "persediaan produk rakitan";
        }
        else {
            $getName = $this->uri->segment(3);
        }
        $mdlName = "ComRekeningPembantu" . $getName;
        //        cekHere($mdlName);
        $this->load->model("Coms/" . $mdlName);
        $com = new $mdlName();
        $com->addFilter("cabang_id in $cid_list");
        $tmp = $com->fetchBalances($rekName);
        //        cekBiru($this->db->last_query());
        $tempPersediaan = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $valueX) {
                $tempPersediaan[$valueX->extern_id][$valueX->cabang_id] = $valueX->qty_debet;
            }
        }
        //arrPrint($tmp);

        if (isset($_POST['excel'])) {
            // arrPrint($_POST);
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
                    "label" => "kode",
                    "type" => "string",
                ),
                "no_part" => array(
                    "label" => "nomer part",
                    "type" => "string",
                ),
                "nama" => array(
                    "label" => "produk",
                    "type" => "string",
                ),
            );
            foreach ($cb_id as $item) {
                $cabNama = $arrCabangName[$item];
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
            );


            $headers = $headers_0 + $headers_1 + $headers_2;
            foreach ($produkList as $index_0 => $xDetails) {

                $urut++;
                foreach ($headers_0 as $kolom => $header) {
                    $code[$kolom] = $xDetails->$kolom;
                }

                foreach ($cb_id as $cabId) {
                    $stok = isset($tempPersediaan[$xDetails->id][$cabId]) ? $tempPersediaan[$xDetails->id][$cabId] : 0;

                    $code["stok_" . $cabId] = $stok;
                    $code["riil"] = 0;
                }

                $datas[] = (object)$code;
            }


            $ex->setTitleFile("Inventory $dateNow");
            $ex->setDatas($datas);
            $ex->setHeaders($headers);
            // $linkExcel = base_url()."ExcelWriter/proInventory";
            // echo "<script>onLoad($linkExcel);</script>";

            // arrPrint($tempPersediaan);
            // arrPrint($produkList);
            // arrPrint($datas);
            return $ex->writer();
            // matiHere(__FILE__ . __LINE__);
        }

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


}