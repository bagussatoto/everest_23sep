<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 3:20 PM
 */

class Images extends CI_Controller
{
    private $y = array(//===sumbu y (baris)
        "mdlName" => "",
        "label"   => "",
        "entries" => "",
    );
    private $x = array(//===sumbu x (kolom)
        "mdlName" => "",
        "label"   => "",
        "entries" => "",
    );
    private $z = array(//===sumbu z (apa yang diedit)
        "mdlName" => "",
        "label"   => "",
        "entries" => "",
    );
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

    public function __construct(){
        parent::__construct();

        $direction = $this->uri->segment(2);

        $allowAccessWithoutLogin = array(
            'mbIndex', 'mbdata','mobileUpload', 'mobileUploadManual', 'checkQR', 'clearSessionCheckQR'
        );

//        if(!in_array($direction,$allowAccessWithoutLogin)){

            if (!isset($this->session->login['id'])) {
                $xxx = blobEncode(base_url() . "Images/$direction");
                redirect(base_url() . "Login?xxx=$xxx");
            }

            $backLink = isset($_GET['backLink']) ? blobDecode($_GET['backLink']) : "";
            $this->q = isset($_GET['q']) ? $_GET['q'] : null;
            $this->selectedID = isset($_GET['sID']) && $_GET['sID'] > 0 ? $_GET['sID'] : null;

            $this->iy = $this->uri->segment(1);//modelnya
            $this->iz = $this->uri->segment(3);//mode
            $this->ix = $this->uri->segment(5);//label

            $this->y = array(
                "mdlName"   => "Mdl" . ucwords($this->iy),
                "label"     => ucwords($this->iy),
                "images"    => array(),
                "parent_id" => $this->selectedID,
            );

            $this->load->library("Curl");
            $this->load->model("Mdls/" . $this->y['mdlName']);

            $yo = new $this->y['mdlName']();
            $parentID = 0;
            if ($this->selectedID != null) {
                $yo->addFilter("parent_id='" . $this->selectedID . "'");
                $parentID = $this->selectedID;
            }
            $yo->addFilter("jenis='" . $this->uri->segment(3) . "'");
            if ($this->q != null) {
                $tmpY = $yo->lookupByKeyword($this->q)->result();
            }
            else {
                $tmpY = $yo->lookupAll()->result();
            }

            $arrImages = array();
            if (sizeof($tmpY) > 0) {
                foreach ($tmpY as $row) {
                    $arrImages['avail_img']['files'][$row->id] = $row->files;
                }
            }
            else {
                $arrImages['default_img']['files'] = base_url() . "public/images/img_blank.gif";
            }

            $images_str = "<div class='row'>";
            $images_str .= "<div class='col-sm-12'>";
            $images_val = "";
            foreach ($arrImages as $key => $value) {
                if ($key == "default_img") {
                    $images_files = $value['files'];
                    $img_scr = " src='$images_files'";
                    $images_val .= "<div class='col-sm-6 col-xs-4'>";
                    $images_val .= "<div class='thumbnail'>";
                    $images_val .= "<img $img_scr class='img-responsive' width='130px'>";
                    $images_val .= "</div>";
                    $images_val .= "</div>";
                }
                else {
                    //sini ambil dari db bro
                    $img_list = "";
                    foreach ($value['files'] as $id => $value_img) {
                        $deleteLink = base_url() . "" . $this->uri->segment(1) . "/delete/" . $id;
                        $img_scr = "src='$value_img'";
                        $images_del = "<a href='javascript:void(0)' class='btn btn-link' onclick=\"top.confirm_alert_result('Hapus Foto?','foto untuk produk ini akan di hapus','$deleteLink')\" title='klik untuk hapus gambar produk'><i class='fa fa-trash-o'></i></a>";
                        $img_list .= "<div class='col-xs-6 col-sm-4 col-lg-3'>";
                        $img_list .= "<div class='thumbnail'>";
                        $img_list .= "<img $img_scr class='img-responsive' width='130px'>";
                        $img_list .= "<div class='caption'>";
                        $img_list .= "$images_del";
                        $img_list .= "</div>";
                        $img_list .= "</div>";
                        $img_list .= "</div>";
                    }
                    $images_val .= $img_list;
                }
            }
            $images_str .= $images_val;
            $images_str .= "<div class='col-sm-12 col-xs-12'>";
            $images_str .= "<input id='input-1a' type='file' name='files' id='_1' placeholder='only jpeg/jpg/gif allowed to upload with max size 2MB'  class='form-control' autocomplete='off' data-show-preview='TRUE'  multiple data-show-upload='false'>";
            $images_str .= "</div>";
            $images_str .= "</div>";
            $images_str .= "</div>";
            $images_str .= "<div class='clearfix'>&nbsp;</div>";

            $images_str .= "<div class='col-sm-12 col-xs-12 col-lg-12'> <span class='btn btn-sm btn-info' onclick=\"tutorialQrCode()\"> upload from smartphone </span> </div>";

            $images_str .= "\n<script>

                                function createQr(container, value, w='80',h='80'){
                                    var qrcode = new QRCode(container, {
                                        text: value, width: w, height: h,
                                        colorDark : '#000000',
                                        colorLight : '#ffffff',
                                        correctLevel : QRCode.CorrectLevel.H
                                    });
                                }

                                $(document).on('ready', function(){
                                    $('#input-1a').fileinput({
                                        showUpload: false,
                                        maxFileCount: 3,
                                        mainClass: 'input-group-lg'
                                    });
                                });

                                function tutorialQrCode(){
                                    Sweetalert2({
                                        title: 'CARA MENGGUNAKAN',
                                        html: `<div><img height='200' class='thumbnail' id='bc_tutorial'></div>`,
                                        confirmButtonText: 'Saya Mengerti',
                                        onOpen: ()=>{
                                            $('#bc_tutorial').attr('src', 'https://s27389.pcdn.co/wp-content/uploads/2019/10/retail-innovation-changing-tech-consumer-employee-demands-1024x440.jpeg');
                                        }
                                    }).then( (result) => {
                                        if(result){
                                            uploadFromSmartphone();
                                        }
                                    });
                                }

                                function uploadFromSmartphone(){
                                    var arr_specs = ".json_encode($this->y).";
                                    var dateGenerator = new Date();
                                    var validQrBarcode = btoa(dateGenerator)+'_sanQR';

                                    Sweetalert2({
                                        title: 'scan qrcode dibawah ini dengan smartphone anda',
                                        html: `<div class='image-container' id='qrcode_container'></div><div class='text-success text-center text-bold' id='connection'></div>`,
                                        showCancelButton: true,
                                        showConfirmButton: false,
                                        cancelButtonColor: '#d33',
                                        cancelButtonText: 'batalkan',
                                        onOpen: ()=>{
                                            var nama_produk = top.$('#_nama').val();
                                            createQr('qrcode_container',validQrBarcode,200,200);
                                            var callback = `doLoadImagesFromQR('`+validQrBarcode+`')`;
                                            registerNewQrCode(validQrBarcode,arr_specs, callback);
                                        },
                                        onClose: () => {
                                            stopQRChecker(validQrBarcode)
                                        }
                                    }).then( (result) => {
                                        if(result){
                                            stopQRChecker(validQrBarcode)
                                        }
                                    });
                                }

                                function removeSessionQR(code=''){
                                    $.ajax({
                                      url: \"".base_url()."Images/clearSessionCheckQR/\"+code,
                                      beforeSend: function( xhr ) {
                                        xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
                                      }
                                    })
                                      .done(function( data ) {
                                            var parse = JSON.parse(data);
                                            console.log(parse.description);
                                      });
                                }

                                function stopQRChecker(code=''){
                                    clearInterval(loadImagesFromQR);
                                    if(code!==''){
                                         removeSessionQR(code);
                                    }
                                }

                                function registerNewQrCode(code='', arr_specs, callback){
                                    var specs = arr_specs;
                                    $.ajax({
                                      url: \"".base_url()."Images/registerNewQrCode/$parentID/\"+code,
                                      method: 'post',
                                      data: specs,
                                    })
                                    .done( function(keluaran) {
                                        eval(callback)
                                    });
                                }

                                var loadImagesFromQR;
                                var reloadLimit=0;
                                var loadMS = 2000;

                                function doLoadImagesFromQR(code='') {
                                    clearInterval(loadImagesFromQR);
                                    loadImagesFromQR = setInterval( function(){
                                        console.log(loadMS + 'ms ' + code)
                                        $.ajax({
                                          url: \"".base_url()."Images/checkQR/\"+code,
                                          beforeSend: function( xhr ) {
                                            xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
                                          }
                                        })
                                          .done(function( data ) {
                                            if ( console && console.log ) {
                                                var parseData = JSON.parse(data);
                                                if(parseData.limit < 1){
                                                    stopQRChecker(code)
                                                    var append = '';
                                                        append += `<div class='after'>`;
                                                        append += `<span onclick='uploadFromSmartphone()'><i class='fa fa-refresh'></i><div style='font-size: 12px'>expired<br>click here to reload</div></span>`;
                                                        append += '</div>';
                                                    $('#qrcode_container').append(append);
                                                }
                                                else{
                                                    console.log( data );
                                                    console.log( parseData.limit );
                                                    if( parseData.image_url == 0 ){

                                                    }
                                                    else{
                                                        Sweetalert2({
                                                            title: 'image siap diupload',
                                                            html: `<img height='260' src='`+parseData.image_url+`'>`,
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#3085d6',
                                                            cancelButtonColor: '#d33',
                                                            confirmButtonText: 'Simpan Image'
                                                        }).then((result)=>{
                                                            if(result){
                                                                $.ajax({
                                                                  url: \"".base_url()."Images/saveMobile/\"+parseData.qrcode,
                                                                  beforeSend: function( xhr ) {
                                                                    xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
                                                                  }
                                                                })
                                                                .done(function( data ) {
                                                                    var ret = JSON.parse(data);
                                                                    if(ret.status == 'success'){
                                                                        Sweetalert2('sukses', 'Image berhasil disimpan', 'success');
                                                                        setTimeout( function(){ eval(ret.redirect) }, 1000);
                                                                    }
                                                                    else{
                                                                        Sweetalert2('error', 'Image gagal disimpan, silahkan ulangi', 'error');
                                                                        setTimeout( function(){ eval(ret.redirect) }, 1000);
                                                                    }
                                                                });
                                                            }
                                                        });

                                                        console.log(parseData.image_url);
                                                        stopQRChecker(code)
                                                    }
                                                }
                                            }
                                          });
                                    }, loadMS*1 );
                                }
                        </script>";

            $this->y['images'] = $images_str;
//        }


    }

    public function index()
    {
        $attached = isset($_GET['attached']) ? $_GET['attached'] : 0;
        if ($attached == '1') {
            $_SESSION['backLink'] = unserialize(base64_decode($_GET['backLink']));
        }
        $formTarget = base_url() . get_class($this) . "/save/" . $this->uri->segment(1);
        $buttonLabel = "save values";
        $data = array(
            "mode"        => $this->uri->segment(1),
            "errMsg"      => $this->session->errMsg,
            "title"       => "price list of " . $this->iy,
            "subTitle"    => "type in box below to search " . $this->iy . " name",
            "content"     => $this->y['images'],
            "formTarget"  => $formTarget,
            "buttonLabel" => $buttonLabel,
            "parentId"    => $this->y['parent_id']
        );
        $this->load->view('harga', $data);
        $this->session->errMsg = "";
    }

    public function save_ori()
    {
        $arrAlert = array(
            "html"              => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>processing upload data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,

        );

        $className = "Mdl" . $this->uri->segment(1);
        $ctrlName = $this->uri->segment(1);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $f = new MyForm($o, "editProcess");

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
                                $image["image"] = file_get_contents($_FILES[$fName]['tmp_name']);
                                $data[$fName] = base64_encode(serialize($image));
                            }
                            else {
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":
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
            $imgProcessor = "data:image/jpeg;base64,".$imagesBlob['files'];
            ob_start();
            $info = getimagesize($imgProcessor);
            if ($info['mime'] == 'image/jpeg'){
                $image = imagecreatefromjpeg($imgProcessor);
            }
            elseif ($info['mime'] == 'image/gif'){
                $image = imagecreatefromgif($imgProcessor);
            }
            elseif ($info['mime'] == 'image/png'){
                $image = imagecreatefrompng($imgProcessor);
            }

            imagejpeg($image, NULL, 10);

            $data_images = ob_get_contents();

            ob_end_clean();
            $image_data_base64 = base64_encode ($data_images);
            $imagesBlob["files"] = base64_encode ($data_images);
            $dataLast = array_replace($data, $imagesBlob);

            $insertID = $o->addData(array_filter($dataLast), $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
            $this->session->errMsg = "Data contents have been saved";

            $this->load->model("Mdls/" . "MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $dataLast['parent_id'],
                "mdl_name"           => $className,
                "mdl_label"          => get_class($this),
                "new_content"        => base64_encode(serialize($dataLast)),
                "new_content_intext" => print_r($data, true),
                "label"              => $data['jenis'],
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

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

    }

    public function save()
    {

        header('Content-Type: text/html; charset=utf-8');

        @ini_set('output_buffering','Off');
        @ini_set('zlib.output_compression',0);
        @ini_set('implicit_flush',1);
        @ob_end_clean();
        set_time_limit(0);
        ob_start();

        $arrAlert = array(
            "html"              => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Please wait ... ... ,<br>processing upload data<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );
        echo swalAlert($arrAlert);

        ob_flush();
        flush();


        $className = "Mdl" . $this->uri->segment(1);
        $ctrlName = $this->uri->segment(1);
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $f = new MyForm($o, "editProcess");

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
                                $image["image"] = file_get_contents($_FILES[$fName]['tmp_name']);
                                $data[$fName] = base64_encode(serialize($image));
//                                cekHere($fName);
                            }
                            else {
                                $data[$fName] = "";
                            }
                            break;
                        case "hidden":
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

//            $request = curl_init( cdn_upload_images() );
//            $realpath = realpath($_FILES['files']['tmp_name']);
//            curl_setopt($request, CURLOPT_POST, true);
//            $fields = [
//                'file' => new \CurlFile($realpath, $_FILES['files']['type'], $_FILES['files']['name']),
//                'server_source' => $_SERVER['HTTP_HOST']
//            ];
//            curl_setopt($request, CURLOPT_POSTFIELDS, $fields);
//            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
//            $cUrl_result= json_decode(curl_exec($request));
//
//            curl_close($request);


            $file = $_FILES['files'];
            $cUrl_result = upload_image($file);
//arrPrint($file);
//arrPrint($cUrl_result);
//arrPrint($file);
            if( isset($cUrl_result->status) && $cUrl_result->status == 'success'){
                $imagesBlob["files"] = $cUrl_result->full_url;
                $dataLast = array_replace($data, $imagesBlob);

                $insertID = $o->addData(array_filter($dataLast), $o->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                $this->session->errMsg = "Data contents have been saved";
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => $dataLast['parent_id'],
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "new_content"        => base64_encode(serialize($dataLast)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => $data['jenis'],
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );
                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                $this->db->trans_complete();
                echo "<script>top.swal('Berhasil Upload', 'akan reload', 'success');</script>";
                echo "<script>top.location.reload();</script>";
            }
            else{
//                $error = $cUrl_result['error'];
//                cekHere( $error );
                echo "<script>top.swal('error', 'image tidak valid, coba untuk ganti gambar yang akan di upload', 'error');</script>";
            }
        }
        else {
            $errMsg = "";
            foreach ($f->getValidationResults() as $err) {
                $errMsg .= "Error in $err[fieldLabel]:  $err[errMsg]";
            }
            echo "<script>top.document.getElementById('btnSave').disabled=false;</script>";
            die(lgShowAlert($errMsg));
        }
        ob_flush();
        ob_end_flush();
    }

//==================================================

    public function saveMobile(){

        $qrcode = $this->uri->segment(3);
        $produk = array();

        $this->load->model("Mdls/MdlQrUpload");
        $o = new MdlQrUpload();
        $o->addFilter("code='".$qrcode."'");
        $tmp = $o->lookupAll()->result();

        $imgJenis = array(
            "MdlImages" => "produk",
        );

        $redirect = "";
        $insertID=0;
        if( sizeof($tmp) > 0 ){

            $mdlName    = isset($tmp[0]->mdlName)   ? $tmp[0]->mdlName : "";
            $extern_id  = isset($tmp[0]->extern_id) ? $tmp[0]->extern_id : "";
            $image_url  = isset($tmp[0]->image_url) ? $tmp[0]->image_url : "";
            $jenis      = isset($tmp[0]->jenis)     ? $tmp[0]->jenis : "";

            $jenisJs = array(
                "MdlImages" => "top.$('iframe[id=result2]')[1].contentDocument.location.reload(true);",
                "MdlCustomer" => "top.$.each(BootstrapDialog.dialogs, function(id, dialog){dialog.close();});setTimeout( function(){ top.BootstrapDialog.show({title:'Modify Customer',cssClass:'edit-dialog',message: $('<div></div>').load('".base_url()."Data/edit/Customer/$extern_id'),draggable:false,closable:true}) }, 400);",
            );

            switch($mdlName){
                case "MdlImages":
                    $this->load->model("Mdls/$mdlName");
                    $i = new $mdlName();
                    $redirect = $jenisJs[$mdlName];
                    $insertID = $i->addData(
                                    array(
                                        'parent_id'=>$extern_id,
                                        'jenis'=>$imgJenis[$mdlName],
                                        'files'=>$image_url,
                                        'status'=>1
                                    )
                                );
                break;
                case "MdlCustomer":
                    $this->load->model("Mdls/$mdlName");
                    $i = new $mdlName();
                    $redirect = $jenisJs[$mdlName];
                    $insertID = $i->updateData(array("id"=>$extern_id),array("$jenis"=>$image_url));
                break;
            }
        }

        if($insertID){
            $update = $o->updateData(array("code"=>$qrcode), array('status'=>0));
            echo json_encode( array('status'=>'success','redirect'=>$redirect) );
        }
        else{
            echo json_encode( array('status'=>'error','redirect'=>"top.location.reload(true)") );
        }

    }

    public function mobileUpload(){

        $file = $_FILES['files'];
        $cUrl_result = upload_image($file);
        $blob = $this->uri->segment(3);
        $post = blobDecode($blob);

        $this->load->model("Mdls/MdlQrUpload");
        $o = new MdlQrUpload();
        $updateID=0;
        $this->db->trans_start();

        if( isset($cUrl_result->status) && $cUrl_result->status == 'success'){
            $fullImgUrl = $cUrl_result->full_url;
            $updateID = $o->updateData(array('code'=>$post['code'], 'extern_id' => $post['id']), array('image_url'=>$fullImgUrl)) or die(lgShowError("Gagal menulis data", __FILE__));
        }

        if($updateID>0){
            $this->db->trans_complete();
            echo json_encode(array());
//                echo "<script>top.swal.fire('Berhasil Upload', 'akan reload', 'success');</script>";
        }
        else{
            $err = json_encode($cUrl_result);
            echo "<script>top.swal.fire('Gagal Upload', '$err', 'error');</script>";
        }

//        echo "<script>setTimeout( function(){ top.location.href = '../Login'; }, 2000 )</script>";
    }

    public function mobileUploadManual(){

        $ctrlName = $this->uri->segment(3);
        $className = "Mdl".$ctrlName;
        $blob = $this->uri->segment(4);
        $post = blobDecode($blob);

        $this->load->model("Mdls/" . $className);
        $o = new $className;

        $this->db->trans_start();

        switch($ctrlName){
            default:
            case "Produk":
                $resultUpload=array();
                $data=array();
                $fullImgUrl="";
                if(sizeof($_FILES)>0){
                    foreach($_FILES as $key => $dataImg){
                        $cUrl_result = upload_image($dataImg);
                        if( isset($cUrl_result->status) && $cUrl_result->status == 'success'){
                            $fullImgUrl = $cUrl_result->full_url;
                            $resultUpload[$key] = $fullImgUrl;
                            $fullImgUrl = isset($resultUpload[$key]) ? $resultUpload[$key] : "";
                        }
                        else{
                            matiHere();
                        }
                    }
                }
                else{
                    matiHere("kosong image nya bro waduh apa-apaan sih kamu !!");
                }

                $imgContent = array(
                    "parent_id" => $post['extern_id'],
                    "jenis"     => "produk",
                    "files"     => $fullImgUrl,
                    "status"    => 1,
                    "trash"     => 0,
                );

                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $post['extern_id'],
                ))->result();

                foreach ($o->getFields() as $fName => $fSpec) {
                    $fColName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    if( !isset($data[$fColName]) ){
                        $data[$fColName] = array();
                    }
                    $data[$fColName] = isset($tmpOrig[0]->$fColName) ? $tmpOrig[0]->$fColName : "";
                }

                $data['images'] = "$fullImgUrl";
                $data['trash'] = 0;

                $this->load->model("Mdls/" . "MdlDataTmp");
                $dTmp = new MdlDataTmp();

                $tmpData = array(
                    "orig_id"          => $data['id'],
                    "mdl_name"         => $className,
                    "mdl_label"        => $ctrlName,
                    "proposed_by"      => $this->session->login['id'],
                    "proposed_by_name" => $this->session->login['nama'],
                    "proposed_date"    => date("Y-m-d H:i:s"),
                    "content"          => blobEncode($data),
                );

                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));

                $this->session->errMsg = "Data proposal has been saved and pending approval";
                $this->load->model("Mdls/" . "MdlDataHistory");
                $hTmp = new MdlDataHistory();
                $tmpHData = array(
                    "orig_id"            => $data['id'],
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "proposed",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );

                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

//                arrPrint($data);
//                matiHere();


            break;
            case "Customer":

                $resultUpload=array();
                if(sizeof($_FILES)>0){
                    foreach($_FILES as $key => $dataImg){
                        $cUrl_result = upload_image($dataImg);
                        if( isset($cUrl_result->status) && $cUrl_result->status == 'success'){
                            $fullImgUrl = $cUrl_result->full_url;
                            $resultUpload[$key] = $fullImgUrl;
                        }
                    }
                }

                $data = array_merge($post,$resultUpload);
                $data['trash'] = 0;

                $this->load->model("Mdls/" . "MdlDataTmp");
                $dTmp = new MdlDataTmp();

                $tmpData = array(
                    "orig_id"          => $data['id'],
                    "mdl_name"         => $className,
                    "mdl_label"        => $ctrlName,
                    "proposed_by"      => $this->session->login['id'],
                    "proposed_by_name" => $this->session->login['nama'],
                    "proposed_date"    => date("Y-m-d H:i:s"),
                    "content"          => blobEncode($data),
                );

                $insertID = $dTmp->addData($tmpData, $dTmp->getTableName()) or die(lgShowError("Gagal menulis pengajuan data", __FILE__));

                $this->session->errMsg = "Data proposal has been saved and pending approval";

                $tmpOrig = $o->lookupByCondition(array(
                    "id" => $data['id'],
                ))->result();

                $where = array(
                    "id" => $data['id'],
                );
                $o->setFilters(array());
                $o->updateData($where, array("status" => 0, "trash" => 1), $o->getTableName());

                $this->load->model("Mdls/" . "MdlDataHistory");

                $hTmp = new MdlDataHistory();

                $tmpHData = array(
                    "orig_id"            => $data['id'],
                    "mdl_name"           => $className,
                    "mdl_label"          => get_class($this),
                    "old_content"        => base64_encode(serialize((array)$tmpOrig)),
                    "old_content_intext" => print_r($tmpOrig, true),
                    "new_content"        => base64_encode(serialize($data)),
                    "new_content_intext" => print_r($data, true),
                    "label"              => "proposed",
                    "oleh_id"            => $this->session->login['id'],
                    "oleh_name"          => $this->session->login['nama'],
                );

                $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

            break;
        }

        $this->db->trans_complete();
        echo "<script>top.location.reload();</script>";


    }

    public function mbIndex(){

        $qrcode = $this->uri->segment(3);
        $produk = array();

        $this->load->model("Mdls/MdlQrUpload");
        $o = new MdlQrUpload();
        $o->addFilter("code='".$qrcode."'");
        $o->addFilter("status>0");
        $tmp = $o->lookupAll()->result();

        $imgSpecs = array();

        if(!sizeof($tmp)>0){
            arrPrintWebs($tmp);
            cekHere( strtotime( date('Y-m-d H:i:s') ) );
            cekHijau( date('YmdHis', strtotime('2020-09-05 12:27:47') ) );
        }
        else{
            //connection
            $o->updateData(array("code"=>$qrcode), array("connection"=>$_SERVER['HTTP_USER_AGENT']));
            $dtime_sekarang = strtotime(date('Y-m-d H:i:s'));
            $dtime_insert = strtotime($tmp[0]->dtime_insert);
            $diff  = $dtime_sekarang-$dtime_insert;
            if( $diff>300 ){
                gotoLogin();
            }
            else{

                $dtime_inserted = $tmp[0]->dtime_insert;
                $barcode_id = $tmp[0]->id;
                $extern_id = $tmp[0]->extern_id;
                $mdlName = $tmp[0]->mdlName;

            switch($mdlName){
                case "MdlCustomer":
                    $jenis = isset($tmp[0]->jenis) ? $tmp[0]->jenis : "";
                    $jenis_label = isset($tmp[0]->jenis_label) ? $tmp[0]->jenis_label : "";
                    $this->load->model("Mdls/".$mdlName);
                    $o = new $mdlName();
                    $o->addFilter("id='".$extern_id."'");
                    $o->addFilter("status>0");
                    $produk = $o->lookupAll()->result();
                    $imgSpecs = array(
                        "code" => $qrcode,
                        "id" => $extern_id,
                        "jenis" => $jenis_label,
                        "nama" => $produk[0]->nama,
                        "kode" => "",
                        "pic" => "",
                    );
                    break;
                case "MdlImages":
                    $mdlName = "MdlProduk";
                    $this->load->model("Mdls/".$mdlName);
                    $o2 = new $mdlName();
                    $o2->addFilter("id='".$extern_id."'");
                    $produk = $o2->lookupAll()->result();
                    if( !sizeof($produk)>0 ){
                        $mdlName = "MdlProdukRakitan";
                        $this->load->model("Mdls/".$mdlName);
                        $o2 = new $mdlName();
                        $o2->addFilter("id='".$extern_id."'");
                        $produk = $o2->lookupAll()->result();
                    }
                    $imgSpecs = array(
                        "code" => $qrcode,
                        "id" => $extern_id,
                        "jenis" => 'produk',
                        "nama" => $produk[0]->nama,
                        "kode" => $produk[0]->kode,
                        "pic" => $produk[0]->pic,
                    );
                    break;
                }
            }
        }

        $formTarget = base_url() . get_class($this) . "/mobileUpload/" . blobEncode($imgSpecs);

//        matiHere();

        $data = array(
            "mode"        => 'mobileUpload',
            "errMsg"      => $this->session->errMsg,
            "content"     => $produk,
            "imgSpecs"    => $imgSpecs,
            "formTarget"  => $formTarget,
        );

        $this->load->view('images', $data);
        $this->session->errMsg = "";

    }

    public function mbdata(){

        $strContent= "";
        $str= "";
        $jsbottom= "";
        $uri3Valid = $this->uri->segment(3) !='' ?  $this->uri->segment(3) : "Produk";

        $className = "Mdl" . $uri3Valid;
        $extern_id = ""!=$this->uri->segment(4) ? $this->uri->segment(4) : "";
        $key = isset($_GET['key']) ? $_GET['key'] : "";

        $ctrlName = $uri3Valid;
        $this->load->model("Mdls/" . $className);
        $o = new $className;
        $fields = $o->getFields();
        $postProcs = isset($this->config->item("dataPostProcessors")[$className]) ? $this->config->item("dataPostProcessors")[$className] : array();

        $arrResult=array();
        $o->addFilter("status>0");

        if( $uri3Valid!='Produk' || $uri3Valid!='ProdukPaket' || $uri3Valid!='ProdukRakitan' ){
            $o->addFilter("nama!=''");
        }

        $tmp = $o->lookupAll()->result();

        if(sizeof($tmp)>0){
            foreach($tmp as $k=>$tmpData){
                $arrResult[$tmpData->id] = $tmpData;
            }
        }

        if( strlen($extern_id) > 0 ){
            if( !sizeof($arrResult[$extern_id])>0 ){
                topRedirect(base_url() . "Images/mbdata");
                mati_disini("sudah ada session");
            }
        }

        $formUpload=array();
        $formCtrl=array();
        $imgSpecs = array();
        $arrMenuJenis = array(
            "Customer" => array(
                "label" => "Customer",
                "disabled" => false,
            ),
            "Produk" => array(
                "label" => "Produk Regular",
                "disabled" => false,
            ),
            "ProdukRakitan" => array(
                "label" => "Produk Rakitan",
                "disabled" => false,
            ),
            "ProdukPaket" => array(
                "label" => "Produk Paket",
                "disabled" => false,
            )
        );

        $menuHeader = "";
        $menuHeader .= "<label style='font-size: 10px;' class='text-danger' for='btnGroupHeader'>tentukan kategori image yang ingin Kamu upload</label>";
        $menuHeader .= "<div id='btnGroupHeader' class='btn-group btn-group-justified'>";

        foreach($arrMenuJenis as $cName => $arD){
            $on = $cName == $ctrlName ? "on" : "";
            $label = isset($arD['label']) ? $arD['label'] : "";
            if($arD['disabled']){
                $menuHeader .= "<a disabled class='btn btn-sm btn-default $on' href='javascript:void(0)'><span style='white-space: break-spaces;' class=''>$label</span></a>";
        }
        else{
                $menuHeader .= "<a class='btn btn-sm btn-default $on' href='javascript:void(0)' onclick=\"window.open('".base_url()."Images/mbdata/$cName', '_self')\"><span style='white-space: break-spaces;' class=''>$label</span></a>";
            }
        }

        $menuHeader .= "</div>";
        $defaultValue = isset($extern_id) && $extern_id!='' ? $extern_id : "";

        $hSelector  = "<label style='font-size: 10px;' class='text-danger' for='dropdownSearch'>tentukan nama customer/produk pilihan Kamu</label>";
        $hSelector .= "<div id='dropdownSearch' class='col-xs-12 no-padding'>";
        $hSelector .= "<select data-width='100%' data-show-subtext='false' data-style='btn-default' data-live-search='true' title='Ketik Nama/Kode ". $arrMenuJenis[$ctrlName]['label'] ."' data-headers='Ketik Nama/Kode' data-size='5' name='select' id='_select' class='_select selectpicker form-controls sini select2 show-tick'>";
        $hSelector .= "<option class='text-capitalize' disabled>== Silahkan Pilih ". $arrMenuJenis[$ctrlName]['label'] ." ==</option>";

        foreach($tmp as $ky=>$datas){
            if($ctrlName!='Customer'){
                $hSelector .= "<option data-subtext='" .$datas->kode . "' value='".$datas->id."' style='max-width: 92vw;' class='text-capitalize'>".$datas->nama."</option>";
            }
            else{
                $hSelector .= "<option data-subtext='" .$datas->tlp_1 . "' value='".$datas->id."' style='max-width: 92vw;' class='text-capitalize'>".$datas->nama."</option>";
            }
        }

        $hSelector .= "</select>";
        $hSelector .= "</div>";
        $hSelector .= "<div class='clearfix'> &nbsp; </div>";
        $hSelector .= "
                    <script>
                        setTimeout( function(){ $('._select.select2').selectpicker({ dropdownParent: $('.container') }).selectpicker('val', [$defaultValue]) }, 100 );
                            $(function() {
                                $('.selectpicker').on('change', function(){
                                var selected = $(this).val();
                                window.open('".base_url()."Images/mbdata/$ctrlName/'+selected,'_self');
                            });
                        });
                    </script>
                    ";

        switch($ctrlName){
            default:
            case "Produk":
                $formCtrl=array();
                $arrImg=array();
                if($extern_id!=""){

                    $imgSpecs['extern_id'] = $extern_id;

                    $formUpload= isset($arrResult[$extern_id]) ? $arrResult[$extern_id] : array();
                    $str .= "<div class='panel panel-info'>";
                    $str .= "<div class='panel-heading text-capitalize text-danger text-bold'>Image ".$formUpload->nama."</div>";
                    $str .= "<div class='panel-body no-padding'>";
                    $str .= "<div class='col-xs-12 no-padding'>";
                    $str .= "<input id='prd_".$extern_id."' name='prd_".$extern_id."' type='file' class='file' data-show-upload='false' data-browse-on-zone-click='true'>";
                    $str .= "</div>";
                    $str .= "</div>";
                    $str .= "</div>";
                    $str .= "<script>
                                $('#prd_".$extern_id."').change( function(){
                                    $('#footer').html(`<span onclick=\"$('#fImages').submit()\" class='btn btn-sm btn-info'>simpan</span>`);
                                    $('#footer').css('padding','6');
                                    $('#footer').removeClass('hidden');
                                });";
                    $str .= "$('#prd_".$extern_id."').fileinput({";
                    $this->load->model("Mdls/MdlImages");
                    $img = new MdlImages();
                    $img->addFilter("parent_id=$extern_id");
                    $imgTmp = $img->lookupAll()->result();
                    if(sizeof($imgTmp)>0){
                        $str .= "initialPreview: [";
                        foreach($imgTmp as $ky => $dataField){
                            $str .= "'".$dataField->files."',";
                        }
                        $str .= "],";
                    }
                    else{

                    }


                    $str .= "initialPreviewAsData: true,
                            initialPreviewFileType: 'image',
                            fileActionSettings: {
                                showDrag: false,
                                showZoom: true,
                                showUpload: false,
                                showDelete: false,
                                showRemove: false,
                            },
                        });";
                    $str .= "</script>";

                }
                else{
                    $str .= "<div class='panel panel-info'>";
                    $str .= "<div class='panel-heading text-capitalize text-danger text-bold'>SILAHKAN PILIH CUSTOMER / PRODUK </div>";
                    $str .= "</div>";
                }
                        break;
            case "Customer":
                if(sizeof($fields)>0){
                    $formCtrl=array();
                    if($extern_id!=""){
                        $formUpload= isset($arrResult[$extern_id]) ? $arrResult[$extern_id] : array();
                        foreach($fields as $ky => $dataField){
                            if( !isset($imgSpecs[$dataField['kolom']]) ){
                                $imgSpecs[$dataField['kolom']] = array();
                }
                            if($dataField['inputType']=='image'){
                                $str .= "<div class='panel panel-info'>";
                                $str .= "<div class='panel-heading text-capitalize text-danger text-bold'> ".$dataField['label']."</div>";
                                $str .= "<div class='panel-body no-padding'>";
                                $str .= "<input id='".$dataField['kolom']."' name='".$dataField['kolom']."' type='file' class='file' data-show-upload='false' data-browse-on-zone-click='true'>";
                                $str .= "</div>";
                                $str .= "</div>";
                                $str .= "<script>
                                            $('#".$dataField['kolom']."').change( function(){
                                                $('#footer').html(`<span onclick=\"$('#fImages').submit()\" class='btn btn-sm btn-info'>simpan</span>`);
                                                $('#footer').css('padding','6');
                                                $('#footer').removeClass('hidden');
                                            });
                                        </script>";
            }
                            $imgSpecs[$dataField['kolom']] = sizeof($formUpload) > 0 ? $formUpload->$dataField['kolom'] : array();
        }
                    }
                    else{
                        $str .= "<div class='panel panel-info'>";
                        $str .= "<div class='panel-heading text-capitalize text-danger text-bold'>SILAHKAN PILIH CUSTOMER / PRODUK </div>";
                        $str .= "</div>";
                    }
                }
            break;
        }

        $strContent = $str;
        $formTarget = base_url() . get_class($this) . "/mobileUploadManual/$ctrlName/" . blobEncode($imgSpecs);

        $data = array(
            "mode"        => 'mbdata',
            "errMsg"      => $this->session->errMsg,
            "menuHeader" => $menuHeader,
            "hSelector"  => $hSelector,
            "content"    => $strContent,
            "formTarget"  => $formTarget,
            "jsbottom"   => $jsbottom,
        );

        $this->load->view('images', $data);
        $this->session->errMsg = "";

    }

    public function clearSessionCheckQR(){
        $qrcode = $this->uri->segment(3);

        unset($_SESSION['qrIntervalLimit'][$qrcode]);



        $this->load->model("Mdls/MdlQrUpload");
        $o = new MdlQrUpload();
        $o->addFilter("code='".$qrcode."'");
        $o->addFilter("status>0");
        $tmp = $o->lookupAll()->result();

        if( sizeof($tmp) > 0){
            $updateID = $o->updateData( array("code"=>$qrcode,"status"=>1), array("status"=>0));
        }



        $qrData = array(
            "status" => 'ok',
            "qrcode" => $qrcode,
            "description" => $qrcode . " session was clear",
        );

        echo json_encode($qrData);
    }

    public function checkQR(){

        $qrcode = $this->uri->segment(3);
        $limitLoad = 100;
        $onSession = isset($_SESSION['qrIntervalLimit'][$qrcode]) ? $_SESSION['qrIntervalLimit'][$qrcode] : "null";

        if( $onSession == 'null' ){
            $_SESSION['qrIntervalLimit'][$qrcode] = 1;
        }

        $this->load->model("Mdls/MdlQrUpload");
        $o = new MdlQrUpload();
        $o->addFilter("code='".$qrcode."'");
//        $o->addFilter("status>0");
        $tmp = $o->lookupAll()->result();
        $imgUrl = $tmp[0]->image_url != '' ? $tmp[0]->image_url : "0";
        $conn = $tmp[0]->connection != '' ? $tmp[0]->connection : "0";
        $stat = $tmp[0]->status != '' ? $tmp[0]->status : "0";

        $limitation = $_SESSION['qrIntervalLimit'][$qrcode] = ($onSession*1 + 1*1);
        $remoteJS = $limitation>$limitLoad ? 'clearInterval(loadImagesFromQR)' : "false";
        $qrData = array(
            "status" => 'ok',
            "qrcode" => $qrcode,
            "image_url" => $imgUrl,
            "stat" => $stat,
            "connection" => $conn,
            "remoteJS" => $remoteJS,
            "limit" => ($limitLoad-$limitation),
            "onSession" => $onSession,
            "limitation" => $limitation,
        );

        echo json_encode($qrData);
    }

    public function registerNewQrCode(){

        $parentID = $this->uri->segment(3);
        $qrcode = $this->uri->segment(4);
        $specs = $this->input->post();

        if( sizeof($specs) > 0 ){
            foreach($specs as $k=>$value){
                $$k = $value;
            }
        }

//arrprint($specs);

        $this->load->model("Mdls/MdlQrUpload");
        $cek = new MdlQrUpload();
        $cek->addFilter("code='".$qrcode."'");
        $cek->addFilter("status>0");
        $tmp = $cek->lookupAll()->result();
        $insertID = 0;

//arrPrint($tmp);

        if( !sizeof($tmp)>0 ){

            switch($mdlName){
                case "MdlCustomer":
                    $in = new MdlQrUpload();
                    $insertID = $in->addData(
                        array(
                            'code'=>$qrcode,
                            'extern_id'=>$parentID,
                            'mdlName'=>$mdlName,
                            'label'=>$mainLabel,
                            'jenis'=>$key,
                            'jenis_label'=>$label,
                        )
                    );
                break;
                case "MdlImages":
                    $in = new MdlQrUpload();
                    $insertID = $in->addData(
                        array(
                            'code'=>$qrcode,
                            'extern_id'=>$parentID,
                            'mdlName'=>$mdlName,
                            'label'=>$label
                        )
                    );
                break;
            }

        }
//cekHere($this->db->last_query());
//arrPrint($mdlName);
//arrPrint($insertID);
//matiHere();

//        $clear = new MdlQrUpload();
//        $clear->addFilter("status>0");
//        $tmpAfter = $clear->lookupAll()->result();
//
//        if( sizeof($tmpAfter)>0){
//            foreach($tmpAfter as $dOld){
//
//            }
//        }

    }

    public function uploadQR(){

        $cari_image = "
        <script>
            var loadImagesFromQR;
            function doLoadImagesFromQR() {
                loadImagesFromQR = setInterval( function(){ console.log('500ms') }, 500);
            }

        </script>";


    }

    public function delete(){
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
        $where = array(
            "id" => $this->selectedID,
        );
        $this->db->trans_start();
        //region history data
        $this->load->model("Mdls/" . "MdlDataHistory");
        $hTmp = new MdlDataHistory();
        $tmpHData = array(
            "orig_id"            => $this->selectedID,
            "mdl_name"           => $className,
            "mdl_label"          => get_class($this),
            "data_id"            => $tmpY[0]->parent_id,
            "new_content"        => blobEncode($tmpY),
            "new_content_intext" => print_r($tmpY, true),
            "label"              => "images",
            "oleh_id"            => $this->session->login['id'],
            "oleh_name"          => $this->session->login['nama'],
            "trash"              => "1",
        );
        $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        cekHere($this->db->last_query());
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

    //
    //    public function save()
    //    {
    //        //        print_r($_POST);
    //        //        print_r($this->existingValues);die();
    //        $insertList = array();
    //        $updateList = array();
    //        $oldUpdateList = array();
    //
    //        //arrPrint($_POST);
    //        //arrPRint($this->y['entries']);
    //        //arrPRint($this->x['entries']);
    //        //arrPRint($this->z['rawEntries']);
    //        //arrPrint($this->priceConfig);
    ////        arrPrint($this->y['entries']);
    //        $arrPostData = array();
    //        foreach ($this->y['entries'] as $yID => $yName) {
    //            foreach ($this->x['entries'] as $xID => $xName) {
    //
    //                foreach ($this->priceConfig as $zID => $zName) {
    //                    $pointName = "value_" . $yID . "_" . $xID . "_" . $zID;
    //                    if (isset($_POST[$pointName])) {
    //                        $varName = isset($_POST[$pointName]) ? $_POST[$pointName] : 0;
    //                        if (isset($this->z['rawEntries'][$yID][$xID])) {
    //                            $compareOldData = $this->z['rawEntries'][$yID][$xID];
    //                        } else {
    //                            $compareOldData = array();
    //                        }
    //
    //                        $arrPostData[$yID][$xID][$zID] = $varName;
    //                        //                        arrPrint($compareOldData);
    //                        //                        echo "<br>";
    //                        //                        arrPrint($arrCompare);
    //                        //                        echo $pointName . "*$zID ||$zName*";
    //                        //                        if (isset($this->existingValues[$yID][$xID][$zID])) {
    //                        //                            echo "item existed";
    //                        //                            //==updateList ditambah
    //                        //                            $updateList[] = array(
    //                        //                                "where"  => array(
    //                        //                                    "jenis"       => $this->iy,
    //                        //                                    "jenis_value" => $zID,
    //                        //                                    "produk_id"   => $yID,
    //                        //                                    "cabang_id"   => $xID,
    //                        //                                    //                                "nilai"=>$varName,
    //                        //                                    //                                "dtime"=>date("Y-m-d H:i:s"),
    //                        //                                    //                                "oleh_id"=>$this->session->login['id'],
    //                        //                                    //                                "oleh_nama"=>$this->session->login['nama'],
    //                        //                                ),
    //                        //                                "update" => array(
    //                        //                                "nilai"     => $varName,
    //                        //                                "dtime"     => date("Y-m-d H:i:s"),
    //                        //                                "oleh_id"   => $this->session->login['id'],
    //                        //                                "oleh_nama" => $this->session->login['nama'],
    //                        //                                                                ),
    //                        //                            );
    //                        //                        }
    //                        //                        else {
    //                        //                            //==insertList ditambah
    //                        //                            $insertList[] = array(
    //                        //                                "jenis"       => $this->iy,
    //                        //                                "jenis_value" => $zID,
    //                        //                                "produk_id"   => $yID,
    //                        //                                "cabang_id"   => $xID,
    //                        //                                "nilai"       => $varName,
    //                        //                                "dtime"       => date("Y-m-d H:i:s"),
    //                        //                                "oleh_id"     => $this->session->login['id'],
    //                        //                                "oleh_nama"   => $this->session->login['nama'],
    //                        //                            );
    //                        //                        }
    //                        //                        echo "<br>";
    //                    }
    //                }
    //
    //            }
    //        }
    //        //        arrPrint($arrPostData);
    //        foreach ($arrPostData as $yId => $yData) {
    //            foreach ($yData as $xId => $xData) {
    //                $oldData = $this->z['rawEntries'][$yId][$xId];
    //                $arrLast = array_diff($xData, $oldData);
    //                if (sizeof($arrLast) > 0) {
    //                    foreach ($arrLast as $zId => $varName) {
    //                        if (isset($this->existingValues[$yId][$xId][$zId])) {
    //                            $oldUpdateList[] = array(
    //                                "old_content" => array(
    //                                    "jenis" => $this->iy,
    //                                    "jenis_value" => $zId,
    //                                    "nilai" => $this->z['rawEntries'][$yId][$xId][$zId],
    //                                    "cabang_id" =>$xId,
    //                                ),
    //
    //                            );
    //                            $updateList[] = array(
    //                                "where" => array(
    //                                    "jenis" => $this->iy,
    //                                    "jenis_value" => $zId,
    //                                    "produk_id" => $yId,
    //                                    "cabang_id" => $xId,
    //                                    //                                "nilai"=>$varName,
    //                                    //                                "dtime"=>date("Y-m-d H:i:s"),
    //                                    //                                "oleh_id"=>$this->session->login['id'],
    //                                    //                                "oleh_nama"=>$this->session->login['nama'],
    //                                ),
    //                                "update" => array(
    //                                    "nilai" => $varName,
    //                                    "dtime" => date("Y-m-d H:i:s"),
    //                                    "oleh_id" => $this->session->login['id'],
    //                                    "oleh_nama" => $this->session->login['nama'],
    //                                ),
    //                                "history" => array(
    //                                    "produk_id" => $yId,
    //                                    "nilai" => $varName,
    //                                    "dtime" => date("Y-m-d H:i:s"),
    //                                    "oleh_id" => $this->session->login['id'],
    //                                    "oleh_nama" => $this->session->login['nama'],
    //                                    "jenis" => $this->iy,
    //                                    "jenis_value" => $zId,
    //                                    "cabang_id" => $xId,
    //                                ),
    //                            );
    //                        } else {
    //                            $insertList[] = array(
    //                                "jenis" => $this->iy,
    //                                "jenis_value" => $zId,
    //                                "produk_id" => $yId,
    //                                "cabang_id" => $xId,
    //                                "nilai" => $varName,
    //                                "dtime" => date("Y-m-d H:i:s"),
    //                                "oleh_id" => $this->session->login['id'],
    //                                "oleh_nama" => $this->session->login['nama'],
    //                            );
    //                        }
    //                    }
    //
    //                }
    //
    //
    //                //                arrPrint($arrLast);
    //            }
    //        }
    //
    //        //        die("saving..");
    //        //        matiHere();
    //        //        arrPrint($updateList);
    //        //        arrPrint($oldUpdateList);
    //
    //        $resultIds = array();
    //        if (sizeof($updateList) > 0 || sizeof($insertList) > 0) {
    //
    //            $this->db->trans_start();
    //            $zo = new $this->z['mdlName']();
    //            if (sizeof($insertList) > 0) {
    //                foreach ($insertList as $iSpec) {
    //                    $resultIds[] = $zo->addData($iSpec) or die("failed to add new data");
    //                    cekMerah($this->db->last_query());
    //                }
    //            }
    //            if (sizeof($updateList) > 0) {
    ////                cekHijau("iki");
    //                foreach ($updateList as $uKey => $uSpec) {
    //
    //                    $insertID = $zo->updateData($uSpec['where'], $uSpec['update']) or die("failed to update data");
    //                    $tempOld = $oldUpdateList[$uKey]["old_content"];
    //                    $resultIds[] = $insertID;
    //
    //                    cekMerah($this->db->last_query());
    //
    //                    cekBiru($this->z["mdlName"]);
    //                    $data_id = $uSpec['where']['produk_id'];
    //                    $this->load->model("Mdls/" . "MdlDataHistory");
    //                    $hTmp = new MdlDataHistory();
    //                    $tmpHData = array(
    //                        "orig_id" => $insertID,
    //                        "mdl_name" => $this->z["mdlName"],
    //                        "mdl_label" => $this->z["label"],
    //                        "old_content" => base64_encode(serialize($tempOld)),
    //                        "old_content_intext" => print_r($tempOld, true),
    //                        "new_content" => base64_encode(serialize($uSpec["history"])),
    //                        "new_content_intext" => print_r($uSpec["history"], true),
    //                        "label" => "price",
    //                        "oleh_id" => $this->session->login['id'],
    //                        "oleh_name" => $this->session->login['nama'],
    //                        "data_id" => $data_id,
    //                        "cabang_id" => $uSpec["history"]["cabang_id"],
    //
    //                    );
    //                    //                    arrPrint($tmpHData);
    //                    $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
    //                    cekBiru($this->db->last_query());
    //                }
    //            }
    ////            matiHEre("hooppp  comat comit");
    //            $this->db->trans_complete() or die("Gagal saat berusaha  commit data-update!");
    //            echo lgShowSuccess("", "New setting successfully save");
    //        } else {
    //            die("No entry to insert/update");
    //        }
    //        if (sizeof($resultIds) > 0) {
    //            $this->session->errMsg = "posted data has been saved";
    //        } else {
    //            $this->session->errMsg = "";
    //        }
    //        if (isset($_GET['attached']) && $_GET['attached'] == '1') {
    //
    //            $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(                                   {
    //                                       title:'Modify entry..',
    //                                        message: " . '$' . "('<div></div>').load('" . $_SESSION['backLink'] . "'),
    //                                        draggable:false,
    //                                        size:top.BootstrapDialog.SIZE_WIDE,
    //                                        closable:true,
    //                                        }
    //                                        );";
    //
    //            echo "<html>";
    //            echo "<head>";
    //            echo "<script src=\"".base_url()."assets//AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
    //            echo "</head>";
    //            echo "<body onload=\"$actionTarget\">";
    //            echo "</body>";
    //
    //        } else {
    //            echo "<script>top.location.reload();</script>";
    //        }
    //
    //
    //    }
    //
    //    public function HargaHistory()
    //    {
    //        $this->load->helper("he_date_time");
    //        $content = "";
    //        $className = "Mdl" . $this->uri->segment(2);
    //        $ctrlName = $this->uri->segment(1);
    //        $selectedID = $this->uri->segment(3);
    //        $cabang_id = $this->uri->segment(4);
    //        $label = $this->uri->segment(5);
    ////        cekHijau("$className|| $ctrlName||$selectedID||$cabang_id||$label");
    //        $this->load->model("Mdls/" . $className);
    //
    //        $o = new $className();
    //        $listedFields = $o->getListedFields();
    //        $fields = $o->getFields();
    //
    //        $p = new Layout("", "", "application/template/lte/index.html");
    //        $this->load->model("Mdls/" . "MdlHargaHistory");
    //        $h = new MdlHargaHistory();
    ////        $h->addFilter("label='price'");
    ////        $h->addFilter("data_id='$selectedID'");
    //        $conditional = "data_id='$selectedID' and label='price' and cabang_id='$cabang_id' order by id desc";
    //        $tmpH = $h->lookupByCondition($conditional)->result();
    //        $arrHistory = array();
    //        foreach ($tmpH as $tempContent) {
    //            $data_temp = array();
    //            foreach ($listedFields as $kolom => $alias) {
    //                if (array_key_exists($kolom, $tempContent)) {
    //                    $data_temp[$alias] = $tempContent->$kolom;
    //                }
    //            }
    //
    //            if (isset($data_temp['lama'])) {
    //                $dataOld_decode = blobDecode($data_temp['lama']);
    //                $dataNew_decode = blobDecode($data_temp['baru']);
    ////arrPrint($data_decode);
    //                if (in_array($label, $dataNew_decode)) {
    //                    $hargaOld = $dataOld_decode["nilai"] > 0 ? number_format($dataOld_decode["nilai"]) : "";
    //                    $hargaNew = $dataNew_decode["nilai"] > 0 ? number_format($dataNew_decode["nilai"]) : "";
    //                    $dtime = $data_temp["tanggal"];
    //                    $oleh = $data_temp["PIC"];
    //
    //                    $arrHistory[] = array(
    //                        "tanggal" => formatTanggal($dtime),
    //                        "PIC" => $oleh,
    //                        "lama" => $hargaOld,
    //                        "baru" => $hargaNew,
    ////                        "label" => $label,
    //                    );
    //                }
    //
    //
    //            }
    //
    //        }
    //
    //        if (sizeof($arrHistory) > 0) {
    //            $content .= ("<div class='table-responsive'>");
    //            $content .= ("<table class='table table-condensed table-bordered'>");
    //            $content .= ("<tr bgcolor='#dedede'>");
    //            $content .= ("<td >No</td>");
    //            foreach ($listedFields as $fName => $label) {
    ////                $colsPan_x = $label == "harga" ? "colspan='2'": "rowspan='2'";
    //                $content .= ("<td >");
    //                $content .= ($label);
    //                $content .= ("</td>");
    //            }
    //            $content .= ("</tr>");
    //                $i=0;
    //                foreach ($arrHistory as $key => $row) {
    //                    $i++;
    //                    $content .= ("<tr>");
    //                    $content .= ("<td>$i</td>");
    //                    foreach($row as $alias=> $value){
    //                        if(($alias = "lama") or ($alias = "baru")){
    //                            $cls_td =  "class='text-right'";
    //                        }else{
    //                            $cls_td ="";
    //                        }
    //
    //                        $content .= ("<td >");
    //                        $content .= ($value);
    //                        $content .= ("</td>");
    //                    }
    //                    $content .= ("</tr>");
    //
    //                }
    //            $content .= ("</table>");
    //            $content .= ("</div class='table-responsive'>");
    //        } else {
    //            $content .= ("<div class='alert alert-warning text-center'>");
    //            $content .= ("this item has no history entry");
    //            $content .= ("</div class='alert alert-warning'>");
    //        }
    //        echo $content;
    //    }

}