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
        $allowBypass = $webLogin['allowedpasswordBypass'];
        $maintenance = $this->config->item('maintenance');
        $maintenanceOpt = $this->config->item('maintenanceOptions');

        cekHijau($_SERVER['REMOTE_ADDR']);


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

        $ipadd = $_SERVER['REMOTE_ADDR'];
        $str = "";
        // $str ="<form class='form-signin' method='post' action='{actions}' {attribute} data_toggle='validator'>
        $str .= "<h2 class='form-signin-heading text-muted'><span class='glyphicon glyphicon-lock'></span> Please sign in</h2>
        <input type='text' name='nama' id='uid' value='$defaultUserID' autocomplete='off' class='form-control'
               placeholder='User ID'
               required='' autofocus=''
               onfocus='this.select();'>

        <input data-toggle='password' data-placement='after' class='form-control' type='password' name='password'
               value='$defaultPwd' placeholder='password' required>
        
        <input type='hidden' name='goto' value='$goTo'>
        
        <div class='checkbox'><label><input type='checkbox' name='remember' $remember> remember me</label></div>";

        /* =============================================================================================================
         * ip yang diperkenankan membaiyabas password, diatur dlm config webs :: logins->allowedPasswordBypass
         * =============================================================================================================*/
        if (array_key_exists($ipadd, $allowBypass)) {
            $str .= "<div class='checkbox'><label><input type='checkbox' name='bypass'> bypass password</label></div>";
        }


        $str .= "<a href=# id='btnLogin' name='btnLogin' class='btn btn-lg btn-primary btn-block' type='button' onclick=\"swal('authenticating..');document.getElementById('fLogin').submit();\">Sign in <span class='glyphicon glyphicon-ok'></span> </a>";

        //        $str .= form_close();

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

        $p = New Layout("Login", "sub judul", "application/template/login.html");
        $p->addTags(array(
            "logo_login" => "<img src=\"" . base_url() . "public/images/profiles/logo_login.png\">",
            "content" => $str,
            "errMsg" => $errMsg,
            "stop_time" => "",
            // "footer" => "",
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