<?php

switch ($mode) {
    case "mbdata":

        $p = New Layout("MOBILE UPLOAD", "sub judul", "application/template/mobileUpload.html");

        $p->addTags(array(
            "content" => $content,
            "hSelector" => $hSelector,
            "errMsg" => $errMsg,
            "menuHeader" => $menuHeader,
            "jsbottom" => $jsbottom,
            "formTarget" => $formTarget,
        ));

        $p->render();

        break;
    case "mobileUpload":
        $jsBottom = '';
        $strContent = '';
        $str = '';

        if (sizeof($imgSpecs) > 0) {
            $str .= "<div class='panel panel-success'>";
            $id = isset($imgSpecs[id]) ? $imgSpecs[id] : "";
            $code = isset($imgSpecs[code]) ? $imgSpecs[code] : "";
            $jenis = isset($imgSpecs[jenis]) ? $imgSpecs[jenis] : "";
            $nama = isset($imgSpecs[nama]) ? $imgSpecs[nama] : "";
            $kode = isset($imgSpecs[kode]) ? $imgSpecs[kode] : "";
            $pic = isset($imgSpecs[pic]) ? $imgSpecs[pic] : "";
//            $str .= "<div id='console' class='text-center text-red'>3333333333333</div>";

            $kode = $kode != '' ? " ($kode)" : "";

            $str .= "<div style='font-size: 18px;' class='text-center text-danger'>
            silahkan upload " . $jenis . " <br><b> " . $nama . "$kode</b>
            </div>";
            $str .= "</div>";
        }

        $str .= "<div class='clearfix'>&nbsp;</div>";

//        $str .= "<input id='code' name='code' value='$code' type='text' class='hidden'>";
//        $str .= "<input id='id' name='id' value='$id' type='text' class='hidden'>";
//        $str .= "<input id='jenis' name='jenis' value='$jenis' type='text' class='hidden'>";
//        $str .= "<input id='nama' name='nama' value='$nama' type='text' class='hidden'>";
//        $str .= "<input id='kode' name='kode' value='$kode' type='text' class='hidden'>";

        $str .= "<input id='files' name='files' type='file' class='file' data-browse-on-zone-click='true'>";
        $str .= "<div class='clearfix'>&nbsp;</div>";
        $str .= "<div id='input-crop' class='text-center hidden'><input id='crop-image' value='crop image' type='button' class='crop-image btn btn-sm btn-default'></div>";

        $str .= "<div class='clearfix'>&nbsp;</div>";
        $str .= "
                    <script>

                        $('#files').fileinput({
                            theme: 'fa',
                            uploadUrl: '$formTarget',
                        });

                        setInterval( function(){
                            $.ajax({
                              url: \"" . base_url() . "Images/checkQR/$code\",
                              beforeSend: function( xhr ) {
                                xhr.overrideMimeType( \"text/plain; charset=x-user-defined\" );
                              }
                            })
                            .done(function(data){
                                console.log( JSON.parse(data).stat );
                                if(JSON.parse(data).stat==1){

                                }
                                else{
                                    top.location.href = \"" . base_url() . "Login\"
                                }
                            });
                        }, 2000);

                    </script>
                ";


        if (sizeof($content) > 0) {
            foreach ($content as $k => $value) {

            }
        }
        else {

            $jsBottom = "

            Swal.fire({
                title: 'Opsss....',
                html: 'QrCode Tidak Ditemukan',
                icon: 'error',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it',
                backdrop: `
                    rgba(0,0,123,0.4)
                    url(\"/images/nyan-cat.gif\")
                    left top
                    no-repeat`
            }).then( (result) => {

                if(result){

                }

            });
            ";

        }

        $strContent .= $str;

        $p = New Layout("MOBILE UPLOAD", "sub judul", "application/template/mobileUpload.html");
        $p->addTags(array(
            "content" => $strContent,
            "errMsg" => $errMsg,
            "jsbottom" => $jsBottom,
//            "formTarget"   => $formTarget,
        ));
        $p->render();
        break;
    case "modal":
        $ly = new Layout();

        $ly->setLayoutModalHeader("<span class='text-primary'>$heading</span>", true);
        $ly->setLayoutModalBody("$forms");
        $ly->setLayoutModalFooter("$footer");
        $att = array(
            "target" => $target,
        );
        $mdl = form_open($actions, $att);
        $mdl .= $ly->layout_modal();
        $mdl .= form_close();
        $mdl .= "<script>
                $('.modal').on('shown.bs.modal', function() {
                  $(this).find('[autofocus]').focus();
                });
            </script>";
        echo $mdl;
        break;
    default:
        cekHere();
        break;
}

?>