<?php
/**
 * Created by thomas Maya Graha Kencana.
 * Date: 29/06/18
 * Time: 15:34
 * ------------------------
 * form dibuat didalam tempalte login.html
 * ------------------------
 */
// include_once
switch ($mode) {
    case "forms":
        //arrPrint($temp);
        //        $str = form_open(base_url() . 'Login/authCheck', $formAttributes);
        $this->config->load('heWebs');
        $webLogin = $this->config->item('logins');
        $allowBypass = $webLogin['allowedPasswordBypass'];
        $maintenance = $this->config->item('maintenance');
        $maintenanceOpt = $this->config->item('maintenanceOptions');
        $ipadd = $_SERVER['REMOTE_ADDR'];
        $ipadds = $_SERVER['REMOTE_ADDR'];
        // cekHijau($_SERVER['REMOTE_ADDR'] . " $ipadd");


        /* =================================================================================================================
         * mode maintenace diatur dari config webs -> maintenace
         * false            : untuk normal operation
         * true / (1~ ...)  : untuk memunculkan option type maintenace
         * =================================================================================================================*/
        if (show_debuger() != 1) {
            if ($maintenance != false) {
                die($maintenanceOpt[$maintenance]["status"]($maintenanceOpt[$maintenance]["mesage"], $maintenanceOpt[$maintenance]["reload"]));
            }
        }

        // $defaultUserID ="everest";
        // $defaultPwd = "123456";
        // $remember = "checked";
        $str = "";
        // $str ="<form class='form-signin' method='post' action='{actions}' {attribute} data_toggle='validator'>
        $str .= "<h2 class='form-signin-heading text-muted' style='margin-bottom: 20px;'><small><span class='glyphicon glyphicon-lock'></span></small> Sign in</h2>
        <input type='text' name='nama' id='uid' value='$defaultUserID' autocomplete='off' class='form-control'
               placeholder='User ID'
               required='' autofocus=''
               onfocus='this.select();'>

        <input data-toggle='password' data-placement='after' class='form-control' type='password' name='password'
               value='$defaultPwd' placeholder='password' required>
        
        <input type='hidden' name='goto' value='$goTo'>
        
        <div class='checkbox'><label><input type='checkbox' name='remember' $remember> remember me</label></div>";

        /* =============================================================================================================
         * ip yang diperkenankan mem-byapass password, diatur dlm config webs :: logins->allowedPasswordBypass
         * =============================================================================================================*/
        if (array_key_exists($ipadd, $allowBypass)) {
            $str .= "<div class='checkbox'><label><input type='checkbox' name='bypass'> bypass password</label></div>";
        }

        $str .= "<a ipadd='$ipadd' href=# id='btnLogin' name='btnLogin' class='btn btn-lg btn-primary btn-block' type='button' onclick=\"swal('authenticating..');document.getElementById('fLogin').submit();\">Sign in <span class='glyphicon glyphicon-ok'></span> </a>";

        //region lupa password
        $arrAtt = array(
            'title' => 'Reset your password',
            // 'class' => '',
            // "style" => "color:red;",
            "data-toggle" => "modal",
            "data-target" => "#myModal",
        );

        $forgot_link = anchor(base_url() . "Login/forgotPwd", "Username / Password?", $arrAtt);
        $str .= "<div class='text-center bborder-cek'>";
        $str .= "Forgot $forgot_link";
        $str .= "</div>";
        //endregion

        if (sizeof($ses_ended) > 0) {
            $str .= $ses_ended;
        }

        $str .= "<script>
            document.getElementById('btnLogin').onclick = function(){
                swal({
                    // title: \"Sweet!\",
                    html: \"<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>authenticting your account,<br>please wait<br>\",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                disabled = true;
                document.getElementById('fLogin').submit();
            };
            

            
        </script>";

//        if($ipadd=="202.65.117.72"){

        $str .= "
            <script>
                $(document).ready( function(){
                    $('#uid').attr('readonly', true);
                    $('input[name=password]').attr('readonly', true);
                    var newWin = window.open('" . base_url() . "PopupUnblocker/checkPopUp','test','toolbar=no,status=no,menubar=no,scrollbars=no,resizable=no,left=10000, top=10000, width=10, height=10, visible=none', '');
                    if(!newWin || newWin.closed || typeof newWin.closed=='undefined'){ 
                        swal({
                            title: 'PopUp Terblokir..!!',
                            html: \"Demi kelancaran aktifitas Anda, <br>silahkan <b>Allow Pop Up</b> pada kanan atas layar Anda... <BR> <BR> <img width='100%' src='https://cdn.mayagrahakencana.com/images/uploads/default/allowpopup2.png'> <BR> <BR> <div class='text-bold text-lg text-red'>SETELAH ITU REFRESH BROWSER ANDA...</div> <div class='btn btn-xs btn-flat btn-success' onclick='window.location.reload()'>REFRESH</div>\",
                            type: 'info',
                            allowOutsideClick: false,
                            onOpen: function(){
                                swal.showLoading()
                            }
                        })
                    }
                });
            </script>";

//        }

        if (isset($_GET['xxx']) && $_GET['xxx'] == "czo0NjoiaHR0cHM6Ly9zYW4ubWF5YWdyYWhha2VuY2FuYS5jb20vSW1hZ2VzL21iZGF0YSI7") {
            $show_upload = "hidden";
        }

        $p = New Layout("Login", "sub judul", MODUL_TEMPLATE_PATH . "template/login.html");

        $p->addTags(array(
            "logo_login" => "<img src=\"" . base_url() . "public/images/profiles/logo_login.png\">",
            "content" => $str,
            "errMsg" => $errMsg,
            "stop_time" => "",
            "show_upload" => isset($show_upload) ? $show_upload : "",
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