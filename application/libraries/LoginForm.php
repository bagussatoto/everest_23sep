<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:22 AM
 * To change this template use File | Settings | File Templates.
 */
class LoginForm
{

    protected $title;
    protected $subTitle;
    protected $templateName;
    protected $content;
    protected $tutup;
    protected $actions;
    protected $logo_login;
    protected $attribute;
    protected $display_iframe;
    protected $footer;
    protected $stop_time;


    // <editor-fold defaultstate="collapsed" desc=" getter-setter ">
    public function getDisplayIframe()
    {
        return $this->display_iframe;
    }

    public function setDisplayIframe($display_iframe)
    {
        $this->display_iframe = $display_iframe;
    }

    public function getFooter()
    {
        return $this->footer;
    }

    public function setFooter($footer)
    {
        $this->footer = $footer;
    }

    public function getStopTime()
    {
        return $this->stop_time;
    }

    public function setStopTime($stop_time)
    {
        $this->stop_time = $stop_time;
    }
    //    protected $testing;

    //    public function setTesting($testing){
    //        $this->testing = $testing;
    //    }
    //
    //    public function getTesting(){
    //        return $this->testing;
    //    }


    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function setLogoLogin($logo_login)
    {
        $this->logo_login = $logo_login;
    }

    public function getLogoLogin()
    {
        return $this->logo_login;
    }

    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;
    }

    public function getSubTitle()
    {
        return $this->subTitle;
    }

    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function setTutup($tutup)
    {
        $this->tutup = $tutup;
    }

    public function getTutup()
    {
        return $this->tutup;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }


    // </editor-fold>

    function __construct($myTitle = null, $mySubTitle = null, $tmplt = null)
    {
        $this->setTitle($myTitle);
        $this->setSubTitle($mySubTitle);
        $this->templateName = $tmplt;

        if (show_debuger() == 1) {
            $strShow = "block";
            $this->setFooter(info_debuger());
        }
        else {
            $strShow = "none";
        }
        $this->setDisplayIframe($strShow);


    }

    public function createSigninForm($arrForms)
    {
        $strForms = "<div class='container'>";

        $attributs = isset($this->signinFormAtt) ? $this->signinFormAtt : "<script>'<(==  form harus di set  ==)'</script>";
        $strForms .= "<form class='form-signin' " . $attributs . ">";

        /*--judul form--*/
        $signinFormTitle = !isset($this->signinFormTitle) ? "Please sign in" : $this->signinFormTitle;
        $strForms .= "<h2 class='form-signin-heading'>$signinFormTitle</h2>";

        /*--komponen form--*/
        foreach ($arrForms as $labels => $inputs) {
            $strForms .= $inputs;
        }

        /*--remember me--*/
        $strForms .= "<div class='checkbox'>";
        if (isset($this->signinRememberMe)) {
            $checked = isset($_COOKIE['namaLogin']) ? "checked" : "";
            $strRemember = strlen($this->signinRememberMe) > 4 ? $this->signinRememberMe : "Remember me";
            $strForms .= "<label>";
            $strForms .= "<input type='checkbox' value='1' name='ingat' $checked> $strRemember";
            $strForms .= "</label>";
        }
        $strForms .= "</div>";

        /*--button--*/
        if (!isset($this->signinButton)) {

            $submit_value = isset($this->signinSubmit) ? $this->signinSubmit : "Sign in";

            $strForms .= "<button class='btn btn-lg btn-primary btn-block' type='submit'>$submit_value</button>";
        }
        else {
            $strForms .= $this->signinButton;
        }

        $strForms .= "</form>";

        $strForms .= "</div>";

        return $this->signinForm = $strForms;
    }

    //===addition===
    public function addContent($content)
    {
        $this->content .= $content;
    }

    public function getElements()
    {
        return $this->content;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function render()
    {
        $t = new ViewTemplate;
        $t->defineTheme($this->templateName);

        foreach (get_object_vars($this) as $varName => $varContent) {
            $t->defineTag("{" . $varName . "}", "$varContent");
        }
        $t->defineTag("{base}", base_url());
        $t->defineTag("{cdn_suport}", cdn_suport());
        $t->defineTag("{local_version}", local_version());
        $t->parse();
        $t->printTheme();
    }


}
