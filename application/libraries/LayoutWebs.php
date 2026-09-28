<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */

// include_once "Bs_37.php";
require_once dirname(__FILE__) . '/Bs_41.php';

class LayoutWebs extends Bs_41
{

    private $tags = array();
    private $theme;
    private $content;
    private $rawContent;

    public function __construct($title = "", $template = "")
    {

        $this->tags['searching'] = $this->produkSearchingForm();

        if (isset(login_webs()->nama)) {
            $this->tags['profile_name'] = login_webs()->nama;
            $this->tags['online_status'] = "<a href='".base_url()."Login/authLogoutWebs' class=\"d-block\" title='logout'>" . login_webs()->nama . "</a>";
            $this->tags["profile_img"] = base_url() . "public/images/profiles/profile-default.png";
        }
        else {
            $this->tags["profile_img"] = base_url() . "public/images/profiles/profile-default.png";
            // $this->tags['profile_name']="yyy";
            $this->tags['online_status'] = "<a href='" . base_url() . "Login/webs'  class=\"d-block\">You Are Off Line</a>";
        }

        if (show_debuger() == 1) {
            $strShow = "block";
            $this->tags['footer'] = info_debuger();

        }
        else {
            $strShow = "none";
            $this->tags['footer'] = "";
        }

        $this->tags['display_iframe'] = $strShow;
        $this->tags['title'] = $title;
        $strScript = "<script>
                fetch(\"" . base_url() . "Cli/show_notifikasi\").then(function (n) {
                    if (200 === n.status) {
                        n.json().then(function (n) {
                            jQuery.each(n, function (n, a) {
                                $(\"span#\" + n + \"_n\").length > 0 && a.nilai > 0 && $(\"span#\" + n + \"_n\").html(a.nilai)
                                // jQuery.each(a.childs, function (n_, a_) {
                                //     $(\"badge#\" + n_ + \"_\" + n).length > 0 && a_.nilai > 0 && $(\"badge#\" + n_ + \"_\"+ n).html(a_.nilai)
                                // })
                    console.log(n);
                    console.log(a);
                            })
                        })
                    }
                }).catch(function (n) {
                    console.log(\"Fetch Error :-S\", n)
                });
                </script>";

        $this->tags['script_bottom'] = $strScript;

        // $this->tags['sub_title']=$subtitle;
        $this->tags['base'] = base_url();
        $this->tags['local_suport'] = local_suport();
        $this->tags['cdn_suport'] = cdn_suport();

        $this->theme = $template;

    }

    //region gs
    public function getRawContent()
    {
        return $this->rawContent;
    }

    public function setRawContent($rawContent)
    {
        $this->rawContent = $rawContent;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function addTags($tags)
    {
        foreach ($tags as $key => $val) {
            $this->tags[$key] = $val;
        }

    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    //endregion


    function render()
    {
        $this->content = file($this->theme);
        $this->content = implode("", $this->content);

        foreach ($this->tags as $key => $val) {
            $this->content = str_replace("{" . $key . "}", $val, $this->content);
        }
        //        print_r($this->content);die();
        //
        $tmpArr = explode(" ", $this->content);
        foreach ($tmpArr as $tmp) {
            echo $tmp . " ";
            flush();
            ob_flush();
        }
        //<editor-fold desc="data history / propose">
        $CI =& get_instance();
        //--------------
        $CI->load->model("Mdls/" . 'MdlActivityLog');  //<-------Load the Model first
        //        $CI->load->helper('url');

        //        $this->load->model("MdlDataHistory");
        $hTmp = new MdlActivityLog();
        if (isset($CI->session->login['id'])) {
            $className = $CI->uri->segment(2);
            $ctrlName = $CI->uri->segment(1);
            $url = current_url();
            $devices = $_SERVER['HTTP_USER_AGENT'];
            $ipadd = $_SERVER['REMOTE_ADDR'];
            $title = $this->tags['title'];
            $subtitle = $this->tags['sub_title'];
            $tmpHData = array(
                "method"        => "$className",
                "controller"    => "$ctrlName",
                "deskripsi_old" => "",
                "deskripsi_new" => "",
                "uid"           => $CI->session->login['id'],
                "uname"         => $CI->session->login['nama'],
                "category"      => "browse",
                "url"           => "$url",
                "devices"       => $devices,
                "ipadd"         => "$ipadd",
                "title"         => $title,
                "sub_title"     => $subtitle,

            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        }


        //</editor-fold>
    }


}
