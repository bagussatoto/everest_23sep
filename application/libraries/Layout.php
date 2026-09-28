<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */
//include_once "Bs_37.php";
include_once "ViewTemplate.php";

class Layout extends ViewTemplate
{

    private $tags = array();
    private $theme;
    private $content;
    private $rawContent;
    private $onClickTarget;
    private $formGroupLeftClass;
    private $formGroupRightClass;
    protected $tbl_properties;
    protected $dfs_map;
    protected $children_map;
    protected $dfs_cashflow_map;
    protected $dfs_tk_map;

    public function setOnClickTarget($onClickTarget)
    {
        $this->onClickTarget = $onClickTarget;
    }

// START OF COMPLETE REPEATED LOGIC
    public function __construct($title = "", $subtitle = "", $template = "", $showCabang = true)
    {
        $this->ci = $CI =& get_instance();
        //load dari config item pair pajak

        if (isset($CI->session->login)) {
            $masterPpnData = $CI->config->item("pairPajak");
            $jenisUsaha = $CI->session->login["jenis_usaha"];
            $masterPPN = $masterPpnData[$jenisUsaha]["value"]["default"];
            // arrPrint($CI->session->login);
            // arrPrint($masterPpnData);

            //            $this->tags['profile_name']=isset($CI->session->login['nama']) && isset($CI->session->login['cabang_nama'])?$CI->session->login['nama']."@".$CI->session->login['cabang_nama']:"noname";
            $this->tags['profile_name'] = isset($CI->session->login['nama']) ? $CI->session->login['nama'] : "noname";

            //            $this->tags['info_bottom']  = "<div class='col-sm-9 col-xs-9 col-md-9 col-lg-9 no-padding'>";


            $this->tags['info_ucd'] = "<div style='margin-left:2px;white-space:nowrap;overflow:auto;' class='text-left text-capitalize text-bold'>";
            $this->tags['info_ucd'] .= "<a style=\"margin-right: 10px; white-space: nowrap; overflow: hidden; width: 2rem; height: 2rem; font-size: 1.5rem; line-height: 1.4; font-weight: bold; text-align: center; border-radius: 64px;\" class=\"text-left text-capitalize text-bold timbul\">
                                             <span class=\"text-red text-bold\" onclick=\"confirmLogout('" . base_url() . "auth/Login/authLogout')\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Logout\">
                                                 <i class=\"fa fa-power-off\"></i>
                                             </span>
                                         </a>";
            $this->tags['info_ucd'] .= "<i class='glyphicon glyphicon-user'></i> ";
            //            $this->tags['info_ucd'] .= "<span class=''>".$CI->session->login['ghost']."</span> ";
            $this->tags['info_ucd'] .= isset($CI->session->login['nama']) && isset($CI->session->login['cabang_nama']) ? $CI->session->login['nama'] . ", @" . $CI->session->login['cabang_nama'] : "noname";
            $this->tags['info_ucd'] .= "</div>";


            $this->tags['info_ucd'] .= "<div style='margin-left:2px;white-space:nowrap;overflow:auto;' class='text-left text-capitalize text-bold'>";
            if (isset($CI->session->login['gudang_id']) && $CI->session->login['gudang_id'] == getPOSWarehouseID($CI->session->login['id'], $CI->session->login['cabang_id'], $CI->session->login['id'])['gudang_id']) {
                $this->tags['info_ucd'] .= "<i class='glyphicon glyphicon-phone'></i> ";
                $this->tags['info_ucd'] .= isset($CI->session->login['gudang_nama']) ? $CI->session->login['gudang_nama'] : "";
            }
            else {
                $this->tags['info_ucd'] .= "<i class='glyphicon glyphicon-phone'></i> ";
                $this->tags['info_ucd'] .= "--";
            }

            $this->tags['info_ucd'] .= "</div>";

            $ghost = isset($CI->session->login['ghost']) ? $CI->session->login['ghost'] : 0;
            $this->tags['info_ucd'] .= "<span id='_ghost' class='hidden'>" . $ghost . "</span> ";
            $this->tags['info_ucd'] .= "<span id='globalPPN' class='hidden'>" . $masterPPN["ppnFactor"] . "</span> ";

            if ($CI->agent->is_mobile()) {

                $this->tags['info_ucd'] .= "<div onclick='openSmartScan()' style='position: fixed; top: 10px; right: 100px;' class='btn btn-primary btn__trigger-bawah-mb btn-sm'><i class='fa fa-qrcode fa-2x'></i></div>";

                if ($CI->session->login['forceMobile'] == 1) {
                    $this->tags['info_ucd'] .= "<div id='tombolSwitchForceMobile' onclick='matikanForceMobile()' style='position: fixed; top: 10px; right: 50px;' class='btn btn-success btn__trigger-bawah-mb btn-sm'><i class='fa fa-exchange '></i> <i class='fa fa-mobile'></i></div>";
                }
                else {
                    $this->tags['info_ucd'] .= "<div id='tombolSwitchForceMobile' onclick='nyalakanForceMobile()' style='position: fixed; top: 10px; right: 50px;' class='btn btn-default btn__trigger-bawah-mb btn-sm'><i class='fa fa-exchange '></i> <i class='fa fa-mobile'></i></div>";
                }

                $this->tags['info_ucd'] .= "<script>
                    function matikanForceMobile(){
                        $.ajax({
                            url: '{base}auth/Login/forceMobile',
                            method: 'GET',
                            data: {forceMobile: 0},
                            success: function(){
                                window.location.reload();
                            }
                        })
                    }
                    function nyalakanForceMobile(){
                        $.ajax({
                            url: '{base}auth/Login/forceMobile',
                            method: 'GET',
                            data: {forceMobile: 1},
                            success: function(){
                                window.location.reload();
                            }
                        })
                    }
                    var changingHash = false;
                    function getScan(){
                        var href = window.location.href;
                        var ptr = href.lastIndexOf('#');
                        if(ptr>0){
                            href = href.substr(0, ptr);
                        }
                        window.addEventListener('storage', onbarcode, false);
                        setTimeout('window.removeEventListener(\"storage\", onbarcode, false)', 15000);
                        localStorage.removeItem('barcode');
                        //window.open  (href + '#zx' + new Date().toString());
                        if(navigator.userAgent.match(/Firefox/i)){
                            window.location.href = ('zxing://scan/?ret=' + encodeURIComponent(href + '#zx{CODE}'));
                        }
                        else{
                            window.open('zxing://scan/?ret=' + encodeURIComponent(href + '#zx{CODE}'));
                        }
                    }

                    function processBarcode(bc){
                        $('#itemKeyword').val(bc);
                        var keyboardEvent = new KeyboardEvent('keyup', {bubbles: true});
                        Object.defineProperty(keyboardEvent, 'charCode', {
                            get: function () {
                                return this.charCodeVal;
                            }
                        });
                        keyboardEvent.charCodeVal = [13];
                        document.getElementById('itemKeyword').dispatchEvent(keyboardEvent);
                    }

                    function onbarcode(event){
                        switch (event.type) {
                            case 'hashchange': {
                                if (changingHash == true) {
                                    return;
                                }
                                var hash = window.location.hash;
                                if (hash.substr(0, 3) == '#zx') {
                                    hash = window.location.hash.substr(3);
                                    changingHash = true;
                                    window.location.hash = event.oldURL.split('\#')[1] || ''
                                    changingHash = false;
                                    processBarcode(hash);
                                }
                                break;
                            }
                            case 'storage': {
                                window.focus();
                                if (event.key == 'barcode') {
                                    window.removeEventListener('storage', onbarcode, false);
                                    processBarcode(event.newValue);
                                }
                                break;
                            }
                            default: {
                                console.log(event)
                                break;
                            }
                        }
                    }

                    function openSmartScan(){
                        window.addEventListener('hashchange', onbarcode, false);
                    }
                </script>";

                if ($CI->session->login['forceDesktopView'] == 1) {
                    $this->tags['info_ucd'] .= "<div id='tombolSwitchDesktop' onclick='requestDesktopSite()' style='position: fixed; top: 10px; right: 9px;' class='btn btn-success btn__trigger-bawah-mb btn-sm'><i class='fa fa-desktop'></i></div>";

                    $this->tags['info_ucd'] .= "<script>
                    function requestDesktopSite(){
                        if(document.getElementsByTagName('meta')['viewport'].content=='width= 1440px'){
                            document.getElementsByTagName('meta')['viewport'].content='width= 400px';
                            $.ajax({
                                url: '{base}/Login/forceDesktopView',
                                method: 'GET',
                                data: {forceDesktopView: 0},
                                success: function(){
                                    window.location.reload();
                                }
                            })
                        }
                        else{
                            document.getElementsByTagName('meta')['viewport'].content='width= 1440px';
                            $.ajax({
                                url: '{base}/Login/forceDesktopView',
                                method: 'GET',
                                data: {forceDesktopView: 1},
                                success: function(){
                                    window.location.reload();
                                }
                            })
                        }
                    }
                </script>";

                    $this->tags['info_ucd'] .= "<script>
                            if(document.getElementsByTagName('meta')['viewport'].content=='width= 1440px'){
                                document.getElementsByTagName('meta')['viewport'].content='width= 400px';
                            }
                            else{
                                document.getElementsByTagName('meta')['viewport'].content='width= 1440px';
                            }
                        </script>";
                }
                else {
                    $this->tags['info_ucd'] .= "<div id='tombolSwitchDesktop' onclick='requestDesktopSite()' style='position: fixed; top: 10px; right: 9px;' class='btn btn-default btn__trigger-bawah-mb btn-sm'><i class='fa fa-desktop'></i></div>";

                    $this->tags['info_ucd'] .= "<script>
                function requestDesktopSite(){
                    if(document.getElementsByTagName('meta')['viewport'].content=='width= 1440px'){
                        document.getElementsByTagName('meta')['viewport'].content='width= 400px';
                                $.ajax({
                                    url: '{base}/Login/forceDesktopView',
                                    method: 'GET',
                                    data: {forceDesktopView: 0},
                                    success: function(){
                                        window.location.reload();
                    }
                                })
                            }
                    else{
                        document.getElementsByTagName('meta')['viewport'].content='width= 1440px';
                                $.ajax({
                                    url: '{base}/Login/forceDesktopView',
                                    method: 'GET',
                                    data: {forceDesktopView: 1},
                                    success: function(){
                                        window.location.reload();
                    }
                                })
                }
                        }
            </script>";
                }

            }

            $this->tags['info_bottom'] = "";

            if ($CI->agent->is_mobile()) {
                if ($CI->session->login['forceDesktopView'] == 1) {
                    $this->tags['info_bottom'] .= "<div id='tombolSwitchDesktop' onclick='requestDesktopSite()' style='position: fixed; top: 10px; right: 9px;' class='btn btn-success btn__trigger-bawah-mb btn-sm'><i class='fa fa-desktop'></i></div>";
                    $this->tags['info_bottom'] .= "<script>
                        function requestDesktopSite(){
                            if(document.getElementsByTagName('meta')['viewport'].content=='width= 1440px'){
                                document.getElementsByTagName('meta')['viewport'].content='width= 400px';
                                $.ajax({
                                    url: '{base}/Login/forceDesktopView',
                                    method: 'GET',
                                    data: {forceDesktopView: 0},
                                    success: function(){
                                        window.location.reload();
                                    }
                                })
                            }
                            else{
                                document.getElementsByTagName('meta')['viewport'].content='width= 1440px';
                                $.ajax({
                                    url: '{base}/Login/forceDesktopView',
                                    method: 'GET',
                                    data: {forceDesktopView: 1},
                                    success: function(){
                                        window.location.reload();
                                    }
                                })
                            }
                        }
                    </script>";
                }
            }

            $this->tags['info_bottom'] .= "<a style='margin-right: 10px; white-space: nowrap; overflow: hidden; width: 2rem; height: 2rem; font-size: 1.5rem; line-height: 1.4; font-weight: bold; text-align: center; border-radius: 64px;' class='text-left text-capitalize text-bold timbul'>";
            $this->tags['info_bottom'] .= "<span class='text-red text-bold' onclick=\"confirmLogout('" . base_url() . "auth/Login/authLogout')\" data-toggle='tooltip' data-placement='top' title='Logout'>";
            $this->tags['info_bottom'] .= "<i class='fa fa-power-off'></i>";
            $this->tags['info_bottom'] .= "</span>";
            $this->tags['info_bottom'] .= "</a>";

            //            $this->tags['info_bottom']  = "<span style='margin-left:-50px;white-space:nowrap;overflow:auto;' class='text-left text-capitalize text-bold timbul'>";
            $this->tags['info_bottom'] .= "<a style='margin-right:10px;white-space:nowrap;overflow:hidden;font-size:15px;font-weight: bolder;' class='text-left text-capitalize text-bold timbul'>";
            $this->tags['info_bottom'] .= "<i class='glyphicon glyphicon-user'></i> ";
            $this->tags['info_bottom'] .= isset($CI->session->login['nama']) && isset($CI->session->login['cabang_nama']) ? $CI->session->login['nama'] . ", @" . $CI->session->login['cabang_nama'] : "noname";
            $this->tags['info_bottom'] .= "</a>";


            //            $this->tags['info_bottom'] .= "<span style='margin-left:10px;white-space:nowrap;overflow:auto;' class='text-left text-capitalize text-bold timbul'>";
            $this->tags['info_bottom'] .= "<a style='margin-right:10px;white-space:nowrap;overflow:hidden;' class='text-left text-capitalize text-bold timbul'>";
            if (isset($CI->session->login['gudang_id']) && $CI->session->login['gudang_id'] == getPOSWarehouseID($CI->session->login['id'], $CI->session->login['cabang_id'], $CI->session->login['id'])['gudang_id']) {
                $this->tags['info_bottom'] .= "<i class='glyphicon glyphicon-phone'></i> ";
                $this->tags['info_bottom'] .= isset($CI->session->login['gudang_nama']) ? $CI->session->login['gudang_nama'] : "";
            }
            else {
                $this->tags['info_bottom'] .= "<i class='glyphicon glyphicon-phone'></i> ";
                $this->tags['info_bottom'] .= "--";
            }
            $this->tags['info_bottom'] .= "</a>";

//            $api_key = "key_client01_abc";
//            $vapid_public_key = "BOKj48XIf8rLPBrYIHWO3t91hsUiXJ4nMFercZaXKwdPDbwTG9p5hQ0fClWPevVzt7abbb4B8ns-aqT8rAX-5YY";

            $this->tags['info_bottom'] .= "<span class='nav-divider'></span>";

            $this->tags['info_bottom'] .= "
<span class='collapse-switch-container' style='display: inline-flex; align-items: center; gap: 5px; vertical-align: middle;'>
    <input type='radio' name='sidebar-collapse' id='collapse-on' value='on' style='display:none;'>
    <input type='radio' name='sidebar-collapse' id='collapse-off' value='off' style='display:none;'>
    <span class='collapse-label' style='display: inline-flex; align-items: center; gap: 3px; font-size: 11px; line-height: 1; cursor: pointer;' onclick=\"$('#sidebar-collapse-switch').click();\">
        <i class='fa fa-bars'></i> Auto Collapse
    </span>
    <span id='sidebar-collapse-switch' class='span-switch' title='Auto Collapse Sidebar'>
        <span class='slider'></span>
    </span>
</span>

<span class='nav-divider'></span>

<span style='display: inline-flex; align-items: center; gap: 4px; vertical-align: middle;'>
    <i class='fa fa-television' style='font-size: 11px;'></i> Resolusi:
    <select id='widthSelector' class='selectpickerx cpull-right form-selectx input-xs' data-width='fit'>
        <option value='1'>Auto</option>
        <option value='0.416'>3840px (4K UHD)</option>
        <option value='0.533'>3200px</option>
        <option value='0.666'>2560px (WQHD)</option>
        <option value='0.711'>1920px (Full HD)</option>
        <option value='0.75'>1680px</option>
        <option value='0.833'>1600px</option>
        <option value='0.875'>1440px</option>
        <option value='0.9'>1366px</option>
        <option value='1.333'>1280px</option>
        <option value='1.666'>1024px</option>
        <option value='2.0'>800px</option>
    </select>
</span>

<span class='nav-divider'></span>

<style>
/* CSS untuk Span Switch */
.span-switch {
    display: inline-block;
    width: 26px;
    height: 14px;
    background-color: #ccc; /* Warna saat OFF */
    border-radius: 14px;
    position: relative;
    cursor: pointer;
    vertical-align: middle;
    transition: background-color 0.3s;
}
.span-switch.active {
    background-color: #28a745; /* Warna hijau AdminLTE saat ON */
}
.span-switch .slider {
    display: block;
    width: 10px;
    height: 10px;
    background-color: white;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 2px;
    transition: transform 0.3s;
    box-shadow: 0 1px 2px rgba(0,0,0,0.25);
}
.span-switch.active .slider {
    transform: translateX(12px); /* Geser bulatan ke kanan saat ON */
}
.span-switch.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none; /* Cegah klik saat loading */
}
.push-notif-container {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    vertical-align: middle;
}
.notif-label {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 11px;
    color: #555;
    line-height: 1;
}
.notif-label i, .notif-label .fa-bell {
    font-size: 10.5px;
}
</style>

<span class=\"push-notif-container\" style=\"display: inline-flex; align-items: center; gap: 5px;\">
    <span class=\"notif-label\"><i class=\"fa fa-bell\"></i> PWA</span>
    <span id=\"push-notif-switch\" class=\"span-switch disabled\" title=\"Aktifkan Notifikasi\">
        <span class=\"slider\"></span>
    </span>
</span>

<script>
    function setZoom(zoomLevel) {
        $('.wrapper').css('zoom', zoomLevel);
    }
    function saveZoom(zoomLevel) {
        localStorage.setItem('selectedZoom', zoomLevel);
    }
    function loadZoom() {
        return localStorage.getItem('selectedZoom') || '1'; // Default ke '1' jika tidak ada
    }
    // Setel zoom saat halaman dimuat
    var savedZoom = loadZoom();
    setZoom(savedZoom);
    $('#widthSelector').val(savedZoom); // Setel nilai dropdown sesuai dengan zoom yang disimpan
    $('#widthSelector').on('change', function() {
        var selectedWidth = $(this).val();
        setZoom(selectedWidth); // Setel zoom
        saveZoom(selectedWidth); // Simpan zoom ke localStorage
    });
    if (typeof $.fn.selectpicker !== 'undefined') {
        $('.selectpicker').selectpicker();
    }
    else {
        console.warn(\"Plugin Selectpicker tidak tersedia.\");
    }
    var collapseState = localStorage.getItem('sidebar-collapse');
    if (collapseState === 'on') {
        $(\"#collapse-on\").prop(\"checked\", true);
        $(\"#sidebar-collapse-switch\").addClass(\"active\");
        $(\"body\").addClass(\"sidebar-collapse\");
    }
    else {
        $(\"#collapse-off\").prop(\"checked\", true);
        $(\"#sidebar-collapse-switch\").removeClass(\"active\");
        $(\"body\").removeClass(\"sidebar-collapse\");
    }
    $(\"#sidebar-collapse-switch\").on('click', function() {
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            $(\"#collapse-on\").prop(\"checked\", true);
            $(\"body\").addClass(\"sidebar-collapse\");
            localStorage.setItem('sidebar-collapse', 'on');
        } else {
            $(\"#collapse-off\").prop(\"checked\", true);
            $(\"body\").removeClass(\"sidebar-collapse\");
            localStorage.setItem('sidebar-collapse', 'off');
        }
    });

</script>
            ";

//            $this->tags['info_bottom'] .= "</div>";
//            $this->tags['info_bottom'] .= "<div class='col-sm-3 col-xs-3 col-md-3 col-lg-3 no-padding'>";
//            $this->tags['info_bottom'] .= "<a href=# style=\"background-color: rgb(55, 55, 55);\" class=\"btn btn__trigger-logoff btn__trigger--views-logoff pull-right\" onclick=\"top.document.getElementById('result').src='" . base_url() . "Login/authLogout';\"><span class='fa fa-power-off text-danger'></span></a>";
//            $this->tags['info_bottom'] .= "</div>";

        }

        if (show_debuger() == 1) {
            $strShow = "block";
            $this->tags['footer'] = info_debuger();
        }
        else {
            $strShow = "none";
            $this->tags['footer'] = "";
        }

        if ($showCabang == true) {
            $cabang_ff = " <span style='font-size:15px;'>(" . my_cabang_nama() . ")</span>";
        }
        else {
            $cabang_ff = "";
        }

        $this->tags['display_iframe'] = $strShow;

        $login_nama = "<span class='text-bold' style='font-size: 20px;'>" . my_name() . ", login di " . my_cabang_nama() . "</span>";

        $api_key = "key_client01_abc";
        $vapid_public_key = "BOKj48XIf8rLPBrYIHWO3t91hsUiXJ4nMFercZaXKwdPDbwTG9p5hQ0fClWPevVzt7abbb4B8ns-aqT8rAX-5YY";

        //$numpang_meta = "<meta name=\"push-api-key\" content=\"$api_key\"><meta name=\"push-vapid-public\" content=\"$vapid_public_key\">";

        // $this->tags['logo_header'] = "GAMBIR KUNING";

//        $this->tags['head'] = $numpang_meta;
        $this->tags['favicon'] = img_favicon();
        $this->tags['skin_header'] = "skin-blue";
        $this->tags['logo_header'] = "<img src='" . img_logo_header() . "' height='40px'>";
        $this->tags['logo_header_print_00'] = "<img src='" . img_logo_header_full() . "' class='img-thumbnail'>";
        // $this->tags['logo_header'] = "MAJUMAPAN";
        $this->tags['searching'] = callSearchingLeft();
        $this->tags['title'] = $title;
        //        $this->tags['title'] = my_cabang_nama() . " - ". $title;
        //        $this->tags['title'] = $title . $cabang_ff;
        $this->tags['sub_title'] = $subtitle;
        $this->tags['base'] = base_url();
        $this->tags['local_suport'] = local_suport();
        $this->tags['cdn_suport'] = cdn_suport();
        $this->tags['local_version'] = local_version();
        $this->tags['menu_top'] = callTutorialOnTop();
//        $this->tags['my_name'] = my_name() ."-". my_cabang_nama();
        $this->tags['my_name'] = $login_nama;
        $this->tags['modul'] = modul();
        // $this->tags['searching'] = callSearchingLeft();
        // cekHere("$template");
        $this->theme = $template;

        // help_button setup
        $jenisTr = isset($CI->jenisTr) ? $CI->jenisTr : $CI->uri->segment(4);
        $allowedTrs = array('3311', '3322', '3333', '3344');
        if (in_array($jenisTr, $allowedTrs)) {
            $this->tags['help_button'] = '<span style="margin-left: 10px; cursor: pointer; display: inline-block; vertical-align: middle; line-height: 1;" onclick=\'top.BootstrapDialog.show({title: "<i class=\"fa fa-book\"></i> Panduan Tambahan Diskon oleh Supplier", message: "<div style=\"font-size: 15px; line-height: 1.6; color: #333;\"><p><strong>Tambahan Diskon oleh Supplier (Rebate/Mendapat Barang)</strong></p><ul style=\"padding-left: 20px; margin-top: 10px;\"><li style=\"margin-bottom: 8px;\">Jika <strong>ada PPh</strong>: pasti dianggap sebagai <strong>Pendapatan</strong>.</li><li style=\"margin-bottom: 8px;\">Jika <strong>tidak ada PPh</strong>, tapi <strong>ada PPN</strong>: ini juga dianggap sebagai <strong>Pendapatan</strong> (sepanjang total PPN bertambah).</li><li style=\"margin-bottom: 8px;\">Jika <strong>tidak ada PPh dan tidak ada PPN</strong>: akan <strong>mengurangi nilai inventory</strong> (karena biasanya nilai inventory dan nilai PPN turun).</li></ul></div>", buttons: [{label: "Tutup", cssClass: "btn-default", action: function(dialogRef){dialogRef.close();}}]});\'><i class="fa fa-book" style="color: #ffffff;"></i></span>';
        } else {
            $this->tags['help_button'] = "";
        }
    }
// END OF COMPLETE REPEATED LOGIC

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
        //$this->tags['content'] = $content;
    }

    public function getFormGroupLeftClass()
    {
        return $this->formGroupLeftClass;
    }

    public function setFormGroupLeftClass($formGroupLeftClass)
    {
        $this->formGroupLeftClass = $formGroupLeftClass;
    }

    public function getFormGroupRightClass()
    {
        return $this->formGroupRightClass;
    }

    public function setFormGroupRightClass($formGroupRightClass)
    {
        $this->formGroupRightClass = $formGroupRightClass;
    }

    //endregion
    public function form_group($label, $nilai, $hidden = 0)
    {
        $classHidden = $hidden ? "hidden" : "";
        $var = "<div class='form-group overflow-h $classHidden'>
                    <label for='inputEmail3' class='control-label " . $this->formGroupLeftClass . "'>$label</label>
                    <div class='" . $this->formGroupRightClass . "'>
                        $nilai
                    </div>
                </div>";
        return $var;
    }

    public function layout_box($contens)
    {
        $header = isset($this->layout_box_heading) ? $this->layout_box_heading : "";
        $footer = isset($this->layout_box_footer) ? $this->layout_box_footer : "";

        $property = isset($this->layout_box_css) ? " " . $this->layout_box_css : "";
        $var = "<div class='box" . $property . "'>";

        if (isset($this->layout_box_heading)) {

            $var .= $header;
        }

        if (isset($this->layout_box_body)) {
            $layout_box_attribut = "";
            if (isset($this->layout_box_attribut)) {
                $layout_box_attribut = $this->layout_box_attribut;
            }

            $var .= "<div $layout_box_attribut class='box-body'>";
            $var .= $contens;
            $var .= "</div>";
        }
        else {
            $var .= $contens;
        }

        if (isset($this->layout_box_footer)) {
            $var .= $footer;
        }
        $var .= "</div>";

        return $var;
    }

    // region box
    public function setLayoutBoxBody($layout_box_body)
    {
        $this->layout_box_body = $layout_box_body;
    }

    public function setLayoutBoxBodyCss($layout_box_body_css)
    {
        $this->layout_box_body_css = $layout_box_body_css;
    }

    public function setLayoutBoxCss($layout_box_css)
    {
        $this->layout_box_css = $layout_box_css;
    }

    public function setLayoutBoxFooter($layout_box_footer)
    {
        $var = "<div class='box-footer'>";
        $var .= $layout_box_footer;
        //        $var .= "<button type='button' class='btn btn-default pull-right'><i class='fa fa-plus'></i> Add item</button>";
        $var .= "</div>";
        $this->layout_box_footer = $var;
    }

    public function setLayoutBoxHeadingProperty($layout_box_heading_property)
    {
        $this->layout_box_heading_property = $layout_box_heading_property;
    }

    public function setLayoutBoxHeadingCss($layout_box_heading_css)
    {
        $this->layout_box_heading_css = $layout_box_heading_css;
    }

    public function setLayoutBoxHeading($layout_box_heading, $button_sm = "")
    {
        $property = isset($this->layout_box_heading_property) ? $this->layout_box_heading_property : "";
        $add_class = isset($this->layout_box_heading_css) ? $this->layout_box_heading_css : "";

        if ($layout_box_heading == true) {
            $var = "<div class='box-header with-border $add_class' $property>";
            if (is_array($layout_box_heading)) {
                foreach ($layout_box_heading as $icon => $title) {
                    $var .= "<i class='fa $icon'></i> ";
                    $var .= "<h3 class='box-title'>$title</h3>";
                }
            }
            else {
                $var .= "<h3 class='box-title'>$layout_box_heading</h3>";
            }

            if ($button_sm == true) {
                //            $var .= "<div class='box-tools pull-right' data-toggle='tooltip' title='Status'>";
                $var .= "<div class='box-tools pull-right'>";
                //            $var .= "<div class='btn-group' data-toggle='btn-toggle'>";
                $var .= $button_sm;
                //            $var .= "<button type='button' class='btn btn-default btn-sm active'><i class='fa fa-square text-green'></i>
                //                        </button>
                //                        <button type='button' class='btn btn-default btn-sm'><i class='fa fa-square text-red'></i></button>";
                //            $var .= "</div>";
                $var .= "</div>";
            }

            $var .= "</div>";
        }
        else {
            $var = "";
        }

        $this->layout_box_heading = $var;
    }

    // endregion box

    public function layout_modal()
    {
        $strModal = "";
        $strModal .= "<style type='text/css'>
                    .kecil{padding:0 5px;height: auto;}
                    .layer-2{width: 90%;}
                    td.vcenter {vertical-align: middle !important; }
                    td.borderless-bottom{border-bottom: 1px solid #fff !important;}
                    </style>";
        // region header
        if (isset($this->layout_modal_header)) {
            $strModal .= $this->layout_modal_header;
        }
        // endregion header
        // region body
        if (isset($this->layout_modal_body)) {
            $strModal .= "<div class='modal-body'>";
            $strModal .= $this->layout_modal_body;
            $strModal .= "</div>";
        }
        // endregion body
        // region footer
        if (isset($this->layout_modal_footer)) {
            $strModal .= $this->layout_modal_footer;
        }

        // endregion footer

        return $strModal;
    }

    // region modal boys
    public function setLayoutModalBody($layout_modal_body)
    {
        $this->layout_modal_body = $layout_modal_body;
    }

    public function setLayoutModalFooter($arrFooter = array())
    {
        $strMain = "<div class='modal-footer'>";
        // $strMain .= "<ul class='pager' style='border: 0px solid red;margin: 0;'>";
        // if (isset($arrFooter['left'])) {
        //     $strMain .= "<li class='previous' style='float:left;'>";
        //     foreach ($arrFooter['left'] as $btn_link) {
        //         $strMain .= $btn_link;
        //     }
        //     $strMain .= "</li>";
        // }
        // if (isset($arrFooter['right'])) {
        //     $strMain .= "<li class='next' style='float:right;'>";
        //     foreach ($arrFooter['right'] as $btn_link) {
        //         $strMain .= $btn_link;
        //     }
        //     $strMain .= "</li>";
        // }
        // $strMain .= "</ul>";

        $strMain .= "$arrFooter";
        $strMain .= "</div>";
        $this->layout_modal_footer = $strMain;
    }

    public function setLayoutModalHeader($strHeading, $link_refresh_close = false)
    {
        if ($link_refresh_close == true) {
            if (strlen($link_refresh_close) > 10) {
                $link_refresh = "onclick=\"location.href='$link_refresh_close'\"";
            }
            else {
                $link_refresh = "";
            }
            $layout_modal_close = "<button type='button' class='close' data-dismiss='modal' aria-hidden='true' $link_refresh>&times;</button>";
        }
        else {
            $layout_modal_close = "";
        }

        $layout_modal_header = "<div class='modal-header'>";
        $layout_modal_header .= $layout_modal_close;
        $layout_modal_header .= "<h4 class='modal-title text-white'>$strHeading</h4>";
        //        $layout_modal_header .= "$strHeading";
        $layout_modal_header .= "</div>";

        $this->layout_modal_header = $layout_modal_header;
    }

    // endregion modal boys

    public function setTblProperties($tbl_properties)
    {
        $this->tbl_properties = $tbl_properties;
    }

    public function layout_table_vertical($labels, $values)
    {
        // $this->ci->load->helpers('he_angka');
        $var = "<table " . $this->tbl_properties . ">";

        // arrPrintPink($labels);
        // arrPrintPink($values);
        foreach ($labels as $conten_key => $conten_array) {

            $conten_label = $conten_array['label'];
            $label_attr = isset($conten_array['label_attr']) ? $conten_array['label_attr'] : "";
            $value_attr = isset($conten_array['value_attr']) ? $conten_array['value_attr'] : "";
            $value_format = isset($conten_array['value_format']) ? $conten_array['value_format'] : "";
            // $conten_value = isset($conten_array['value_format']) ? $value_format($values[$conten_key]) : $values[$conten_key];
            // $conten_value = formatField($conten_key,$values[$conten_key]);
            $conten_value = $values[$conten_key];
            // $conten_value = isset($conten_array['value_format']) ? $value_format($values[$conten_key]) : $values[$conten_key];
            // $var .= formatAngka(6000);
            $var .= "<tr>";
            // foreach ($conten_array as $item => $item_array) {

            $var .= "<td $label_attr>";
            $var .= "$conten_label";
            $var .= "</td>";

            $var .= "<td $value_attr>";
            $var .= "$conten_value";
            $var .= "</td>";
            // }

            $var .= "</tr>";
        }

        $var .= "</table>";

        return $var;
    }

    public function carousel($images)
    {
        // arrPrint($images);
        $jmlImage = sizeof($images);

        $var = "<div id='myCarousel' class='carousel slide' data-ride='carousel'>";
        $var .= "<ol class='carousel-indicators'>";
        $xx = -1;
        for ($i = 1; $i <= $jmlImage; $i++) {
            $xx++;
            $cActive = $i == 1 ? "class='active'" : "";
            $var .= "<li data-target='#myCarousel' data-slide-to='$xx' $cActive></li> ";
        }
        $var .= "</ol>";

        $var .= "<div class='carousel-inner'>";
        $nn = 0;
        foreach ($images as $image) {
            $nn++;
            $active = $nn == 1 ? "active" : "";
            $image = str_replace("192.168.11.150", "cdn.mayagrahakencana.com", $image);
            //            cekHere($image);
            //            $path = "$image";
            $image_ex = explode("/", $image);
            $image_ex_nama = end($image_ex);
            //            $image_ex_nama_ex = explode(".", $image_ex_nama);
            //            $image_ex_nama_file = $image_ex_nama_ex[0];

            $var .= "<div class='item $active text-center'>
                    <a download='download.jpeg' hhref='$path' onclick=\"downloadImage('$image', '$image_ex_nama')\">
                      <img src='$image' alt='image' class='img-responsive img-rounded' style=' width: 100%; height: auto;'>
                      </a>
                    </div>";
        }

        $var .= "</div>";
        if ($jmlImage > 1) {

            $var .= "<a class='left carousel-control' href='#myCarousel' data-slide='prev'>
            <span class='glyphicon glyphicon-chevron-left'></span>
            <span class='sr-only'>Previous</span>
          </a>
          <a class='right carousel-control' href='#myCarousel' data-slide='next'>
            <span class='glyphicon glyphicon-chevron-right'></span>
            <span class='sr-only'>Next</span>
          </a>";
        }

        $var .= "</div>";
        $var .= "<script>
            function downloadImage(url, name){
                  fetch(url)
                    .then(resp => resp.blob())
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        // the filename you want
                        a.download = name;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(() => alert('An error sorry'));
            }
</script>";

        return $var;

    }

    // ---------------------------------------------------------------------------

    protected $layout_tabs_header;
    protected $layout_box_attribut;

    public function getLayoutBoxAttribut()
    {
        return $this->layout_box_attribut;
    }

    public function setLayoutBoxAttribut($layout_box_attribut)
    {
        $this->layout_box_attribut = $layout_box_attribut;
    }

    public function getLayoutTabsHeader()
    {
        return $this->layout_tabs_header;
    }

    public function setLayoutTabsHeader($layout_tabs_header)
    {
        $this->layout_tabs_header = $layout_tabs_header;
    }

    protected $layout_tabs_css;

    public function getLayoutTabsCss()
    {
        return $this->layout_tabs_css;
    }

    public function setLayoutTabsCss($layout_tabs_css)
    {
        $this->layout_tabs_css = $layout_tabs_css;
    }

    protected $layout_tabs_btn_tool;
    protected $layout_tabs_position;

    public function getLayoutTabsBtnTool()
    {
        return $this->layout_tabs_btn_tool;
    }

    public function setLayoutTabsBtnTool($layout_tabs_btn_tool)
    {
        $this->layout_tabs_btn_tool = $layout_tabs_btn_tool;
    }

    public function getLayoutTabsPosition()
    {
        return $this->layout_tabs_position;
    }

    public function setLayoutTabsPosition($layout_tabs_position)
    {
        $this->layout_tabs_position = $layout_tabs_position;
    }

    public function layout_tabs($contents)
    {
        // $contents = array(
        //     "tab_1" => array(
        //         "label"  => "tab satu",
        //         "active" => true,
        //         "data"   => "<h1>hauahahah</h1>",
        //     ),
        //     "tab_2" => array(
        //         "label" => "tab dua",
        //         "data"  => "<h4>hihihi</h4>",
        //     ),
        //     // "tab_3" => array(
        //     //     "label" => "tab header",
        //     //     "header" => true,
        //     //     // "data" => "<h4>hihihi</h4>",
        //     // ),
        //
        // );

        $var = "";
        $var .= "<div class='nav-tabs-custom' style='cursor: move;'>";
        $var .= "<ul class='nav nav-tabs ui-sortable-handle'>";
        $pull_posisi = isset($this->layout_tabs_position) ? $this->layout_tabs_position : "";
        foreach ($contents as $tab_key => $params) {
            $tab_label = $params['label'];
            $tab_jml_data = isset($params['jml_data']) ? $params['jml_data'] : "";
            $active = isset($params['active']) && ($params['active'] == true) ? 'active' : '';
            $css = isset($params['css']) ? $params['css'] : '';
            // $var .= "<li class='$active $css text-uppercase $pull_posisi'><a href='#$tab_key' data-toggle='tab' aria-expanded='true' onclick=\"tabActive('$tab_key');\">$tab_label</a><span class='label label-warning pull-right'>9</span></li>";
            $var .= "<li class='$active $css text-uppercase $pull_posisi'><a href='#$tab_key' data-toggle='tab' aria-expanded='true' onclick=\"tabActive('$tab_key');\">$tab_label &nbsp;<span class='badge bg-red pull-right'>$tab_jml_data</span></a></li>";
        }
        if (isset($this->layout_tabs_header)) {

            $var .= "<li class='text-uppercase header' style='padding-top: 15px;'>" . $this->layout_tabs_header . "</li>";
        }
        $var .= "</ul>";


        $css_body = isset($this->layout_tabs_css) ? $this->layout_tabs_css : "";
        $var .= "<div class='tab-content $css_body'>";

        $no = 0;
        foreach ($contents as $tab_key => $params) {
            $no++;
            // $tab_label = $params['label'];
            $active = isset($params['active']) && ($params['active'] == true) ? 'active' : '';
            $data = isset($params['data']) ? $params['data'] : 'none #' . $no . tplNoData();

            $var .= "<div class='chart tab-pane $active' id='$tab_key' style='position: relative;'>";
            $var .= "$data";
            $var .= "</div>";
        }

        $var .= "</div>";
        $var .= "<script>
            function tabActive(x) {
              localStorage.setItem('tab_active',x);
            }
        </script>";

        return $var;
    }

    // ---------------------------------------------
    protected $layout_table_header_kolom;

    public function getLayoutTableHeaderKolom()
    {
        return $this->layout_table_header_kolom;
    }

    public function setLayoutTableHeaderKolom($layout_table_header_kolom)
    {
        $this->layout_table_header_kolom = $layout_table_header_kolom;
    }

    protected $layout_table_header_attribute;

    public function getLayoutTableHeaderAttribute()
    {
        return $this->layout_table_header_attribute;
    }

    public function setLayoutTableHeaderAttribute($layout_table_header_attribute)
    {
        $this->layout_table_header_attribute = $layout_table_header_attribute;
    }

    protected $layout_table_shorting;

    public function getLayoutTableShorting()
    {
        return $this->layout_table_shorting;
    }

    public function setLayoutTableShorting($layout_table_shorting)
    {
        $this->layout_table_shorting = $layout_table_shorting;
    }

    protected $layout_table_data_tbl;

    public function getLayoutTableDataTbl()
    {
        return $this->layout_table_data_tbl;
    }

    public function setLayoutTableDataTbl($layout_table_data_tbl)
    {
        $this->layout_table_data_tbl = $layout_table_data_tbl;
    }


    protected $layout_table_caption;

    public function getLayoutTableCaption()
    {
        return $this->layout_table_caption;
    }

    public function setLayoutTableCaption($layout_table_caption)
    {
        $var = "";
        $var .= "<div class='text-uppercase'>";
        $var .= $layout_table_caption;
        $var .= "</div>";

        $this->layout_table_caption = $var;
    }

    public function layout_table($contens)
    {
        if (is_object($contens)) {
            $contens = (array)$contens;
        }
        $var_tbl = "";
        if (isset($this->layout_table_header_kolom)) {
            !is_array($this->layout_table_header_kolom) ? matiHere("data kolom dlm format array") : "";

            $koloms = $this->layout_table_header_kolom;

            // prety_array($datas->main);

            $ksr_head = "";
            $ksr_head .= "<tr class='bg-info'>";

            $kolom_params = reset($koloms);
            $attr_head = isset($kolom_params['attr_head']) ? $kolom_params['attr_head'] : "";
            $ksr_head .= "<th $attr_head width='20px;'>no</th>";

            foreach ($koloms as $kolom => $attrs) {
                $label = $attrs['label'];
                $attr = isset($attrs['attr_head']) ? $attrs['attr_head'] : "";

                $ksr_head .= "<th $attr>";
                $ksr_head .= $label;
                $ksr_head .= "</th>";
            }
            $ksr_head .= "</tr>";
            // $anu = "222";
            // arrPrint($contens['222']);
            // arrPrint(array_keys($contens));
            // arrPrint($contens);
            // arrPrintPink($contens['378']);
            $totals = array();
            $ksr = "";
            if (sizeof($contens) > 0) {
                // foreach ($totalKasir as $dmain) {
                // asort($kasirs);
                // arrPrint($srcDatas);
                $no = 0;
                $totals = array();
                if (isset($this->layout_table_shorting)) {
                    // arrPrint($contens);
                    foreach ($this->layout_table_shorting as $ksr_id => $lsr_nama) {
                        // cekBiru($ksr_id);
                        // $dmain = isset($contens->$ksr_id) ? $contens->$ksr_id : array();
                        $dmain = isset($contens[$ksr_id]) ? $contens[$ksr_id] : array();
                        // arrPrint($dmain);
                        $no++;
                        $ksr .= "<tr>";
                        $ksr .= "<td class='text-right'>$no</td>";
                        foreach ($koloms as $kolom => $attrs) {
                            $nilai = isset($dmain->$kolom) ? $dmain->$kolom : 0;

                            $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                            $nilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $nilai) : $nilai;

                            $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                            $linkDetile = base_url() . $linking . "";
                            if (isset($attrs['linkType']) && $attrs['linkType'] == 'modal') {
                                $modalHeader = isset($attrs['modalHeader']) ? $dmain->$attrs['modalHeader'] : "none";
                                $linkModal = modalDialogBtn("'$modalHeader'", $linkDetile);
                            }
                            else {
                                $linkModal = "location.href='$linkDetile'";
                            }

                            $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat detail $ksr_id'>$nilai_f</a>" : $nilai_f;

                            $ksr .= "<td $attr data-order='$nilai'>";
                            // $ksr .= $dmain->$kolom;
                            $ksr .= $nilai_link;
                            $ksr .= "</td>";

                            if (isset($attrs['summary'])) {
                                if (!isset($totals[$kolom])) {
                                    $totals[$kolom] = 0;
                                }
                                $totals[$kolom] += $nilai;
                            }
                        }
                        $ksr .= "</tr>";

                        // $ksr .= "</tr>";
                    }
                }
                else {
                    foreach ($contens as $ksr_id => $lsr_nama) {
                        $dmain = (object)$lsr_nama;


                        // $nama = $dmain->nama;
                        // $nilai_rl = $dmain->margin_nilai;
                        // $status_rl = $dmain->status_rl;
                        // $komposisies = $dmain->komposisi;
                        // $jmlBahan = sizeof($komposisies);
                        //
                        // $warna_rl = "";
                        // if ($status_rl == "rugi") {
                        //     $warna_rl = "style='color:red;background:yellow;'";
                        // }

                        // $ksr .= "<tr $warna_rl>";

                        $no++;
                        $ksr .= "<tr>";
                        $ksr .= "<td class='text-right'>$no</td>";

                        foreach ($koloms as $kolom => $attrs) {
                            $nilai = isset($dmain->$kolom) ? $dmain->$kolom : 0;

                            $attr = isset($attrs['attr']) ? $attrs['attr'] : "";
                            $format_key = isset($attrs['format_key']) ? $attrs['format_key'] : $kolom;
                            $nilai_f = isset($attrs['format']) ? $attrs['format']($format_key, $nilai) : $nilai;

                            $linking = isset($attrs['link']) ? $attrs['link'] . "/$ksr_id" : "";
                            $linkDetile = base_url() . $linking . "";
                            // $linkModal = modalDialogBtn("'$nama'", $linkDetile);
                            $nilai_link = isset($attrs['link']) ? "<a href='javascript:void(0);' onclick=\"$linkModal\" title='lihat komposisi'>$nilai_f</a>" : $nilai_f;

                            $ksr .= "<td $attr data-order='$nilai'>";
                            // $ksr .= $dmain->$kolom;
                            $ksr .= $nilai_link;
                            $ksr .= "</td>";

                            if (isset($attrs['summary'])) {
                                if (!isset($totals[$kolom])) {
                                    $totals[$kolom] = 0;
                                }
                                $totals[$kolom] += $nilai;
                            }
                        }
                        $ksr .= "</tr>";

                        // $ksr .= "</tr>";
                    }
                }

            }
            else {
                $ksr .= tplTableNoData("2");
            }

            $ksr_foot = "";
            $ksr_foot .= "<tr class='bg-danger'>";
            $ksr_foot .= "<th></th>";
            foreach ($koloms as $kolom => $attrs) {
                $fNilai = isset($totals[$kolom]) ? $totals[$kolom] : "-";
                $fNilai_f = isset($attrs['format']) ? $attrs['format']($kolom, $fNilai) : $fNilai;
                // $label = $attrs['label'];

                $ksr_foot .= "<th>";
                $ksr_foot .= $fNilai_f;
                $ksr_foot .= "</th>";
            }
            // $ksr_foot .= "</tr>";
            // ---------------
            $str_tbl_id = isset($this->layout_table_data_tbl) && ($this->layout_table_data_tbl != false) ? "id='" . $this->layout_table_data_tbl . "'" : "";

            $var_tbl = "";
            $var_tbl .= isset($this->layout_table_caption) ? $this->layout_table_caption : "";
            $var_tbl .= "<div class='table-responsive'>";
            $var_tbl .= "<table class='table table-condensed table-striped' $str_tbl_id>";
            $var_tbl .= "<thead>";
            $var_tbl .= $ksr_head;
            $var_tbl .= "</thead>";

            $var_tbl .= "<tbody>";
            $var_tbl .= $ksr;
            $var_tbl .= "</tbody>";

            // cekBiru(sizeof($totals));
            if (is_array($totals) && sizeof($totals) > 0) {

                $var_tbl .= "<tfoot>";
                $var_tbl .= $ksr_foot;
                $var_tbl .= "</tfoot>";
            }

            $var_tbl .= "</table>";
            $var_tbl .= "</div>";

            /* ------------------------------------------------------------------------
             * data table option disini
             * ------------------------------------------------------------------------*/
            if (isset($this->layout_table_data_tbl) && ($this->layout_table_data_tbl != false)) {
                $tbl_id = $this->layout_table_data_tbl;
                $var_tbl .= "<script>
                
                $(document).ready( delay_v2( function(){
                    var datareview = $('table#$tbl_id').DataTable({
                                    dom: 'lBfrtip',
                                    fixedHeader: true,
                                    lengthMenu: [ [10, 20, 50, 100, -1], [10, 20, 50, 100, 'All'] ],
                                    pageLength: 20,
                                    buttons: [
                                            'copy',
                                            'csv',
                                            'excel',
                                            'pdf',
                                            'print',
                                            ]
 
                                        });
        
                                    }, 500));

                $('.table-responsive').floatingScroll();
                
                </script>";
            }

        }

        return $var_tbl;
    }

    // ------------------------

// START OF COMPLETE REPEATED LOGIC
    function render()
    {
        // Fallback otomatis untuk tags esensial template
        if (!isset($this->tags['content']) && !empty($this->content)) {
            $this->tags['content'] = $this->content;
        }
        if (!isset($this->tags['menu_left']) && function_exists('callMenuLeft')) {
            $this->tags['menu_left'] = callMenuLeft();
        }
        if (!isset($this->tags['menu_taskbar'])) {
            $this->tags['menu_taskbar'] = function_exists('callMenuTaskbar') ? callMenuTaskbar() : "";
        }
        if (!isset($this->tags['float_menu_bawah']) && function_exists('callFloatMenu')) {
            $this->tags['float_menu_bawah'] = callFloatMenu('bawah');
        }
        if (!isset($this->tags['float_menu_atas']) && function_exists('callFloatMenu')) {
            $this->tags['float_menu_atas'] = callFloatMenu('atas');
        }
        if (!isset($this->tags['btn_back']) && function_exists('callBackNav')) {
            $this->tags['btn_back'] = callBackNav();
        }
        if (!isset($this->tags['head'])) {
            $this->tags['head'] = "";
        }
        if (!isset($this->tags['isi_modal'])) {
            $this->tags['isi_modal'] = "";
        }
        if (!isset($this->tags['lebar_modal'])) {
            $this->tags['lebar_modal'] = "";
        }
        if (!isset($this->tags['bg_modal'])) {
            $this->tags['bg_modal'] = "";
        }

        $this->content = file($this->theme);
        $this->content = implode("", $this->content);

        foreach ($this->tags as $key => $val) {
            $this->content = str_replace("{" . $key . "}", $val, $this->content);
        }

        // Cleanup tag mentah yang belum terurai agar tidak bocor ke tampilan
        $this->content = str_replace(array('{menu_taskbar}', '{menu_left}', '{content}', '{base}'), array('', '', '', base_url()), $this->content);

        $CI =& get_instance();

        // Integrasi Deteksi Perangkat Mobile / Tablet via CI3 User Agent & Session Flag
        $isMobileDevice = false;
        if (isset($CI->agent) && $CI->agent->is_mobile()) {
            $isMobileDevice = true;
        }
        if (isset($CI->session->login['forceMobile']) && $CI->session->login['forceMobile'] == 1) {
            $isMobileDevice = true;
        }
        if (isset($CI->session->login['forceDesktopView']) && $CI->session->login['forceDesktopView'] == 1) {
            $isMobileDevice = false;
        }

        $deviceClass = $isMobileDevice ? 'is-device-mobile' : 'is-device-desktop';
        if (preg_match('/<body[^>]*class=["\']/i', $this->content)) {
            $this->content = preg_replace('/(<body[^>]*class=["\'])/i', '$1' . $deviceClass . ' ', $this->content);
        } else {
            $this->content = preg_replace('/<body/i', '<body class="' . $deviceClass . '"', $this->content);
        }

        if ($isMobileDevice) {
            // Sembunyikan bilah ganda navbar-3rd pada mobile/tablet sejak dari server
            $this->content = preg_replace('/<nav[^>]*navbar-3rd.*?<\/nav>/s', '', $this->content);
        }

        $cabangPusatId = defined('CB_ID_PUSAT') ? CB_ID_PUSAT : '-1';
        $isPusat = (function_exists('my_cabang_id') && my_cabang_id() == $cabangPusatId) || (isset($CI->session->login['cabang_id']) && $CI->session->login['cabang_id'] == $cabangPusatId);
        if ($isPusat) {
            if (preg_match('/<body[^>]*class=["\']/i', $this->content)) {
                $this->content = preg_replace('/(<body[^>]*class=["\'])/i', '$1is-pusat ', $this->content);
            } else {
                $this->content = preg_replace('/<body/i', '<body class="is-pusat"', $this->content);
            }
            $this->content = str_replace('navbar-2nd', 'navbar-2nd is-pusat', $this->content);
            $this->content = str_replace('navbar-3rd', 'navbar-3rd is-pusat', $this->content);
        }

        // Injeksi Messenger & AI jika fitur diaktifkan via Menu Setting, user login, dan bukan modal/AJAX
        if (isset($CI->session->login['id']) && !empty($CI->session->login['id'])) {
            $is_ajax = $CI->input->is_ajax_request();
            $is_subform = isset($_GET['pfid']) || isset($_GET['is_modal']) || isset($_REQUEST['ajax']);
            $is_partial_tpl = (strpos($this->theme, 'modal') !== false || strpos($this->theme, 'blank') !== false || strpos($this->theme, 'template/') !== false || (isset($CI->uri) && strtolower($CI->uri->segment(2)) === 'printing'));
            if (!$is_ajax && !$is_subform && !$is_partial_tpl) {
                $CI->load->model("Mdls/MdlSetting");
                $stSetting = new MdlSetting();
                if ($stSetting->get_messenger_ai_status()) {
                    $CI->load->helper("he_chat");
                    if (function_exists('render_chatbox_widget')) {
                        $chatWidget = render_chatbox_widget();
                        if (strpos($this->content, "</body>") !== false) {
                            $this->content = str_replace("</body>", $chatWidget . "\n</body>", $this->content);
                        } else {
                            $this->content .= $chatWidget;
                        }
                    }
                }
            }
        }

        // arrPrint($this->content);
        // matiHere(__LINE__);

        $tmpArr = explode(" ", $this->content);
        foreach ($tmpArr as $tmp) {
            echo $tmp . " ";
            flush();
            ob_flush();
        }
        //<editor-fold desc="data history / propose">
        //--------------
        $CI->load->model("Mdls/" . 'MdlActivityLog');  //<-------Load the Model first
        //        $CI->load->helper('url');

        //        $this->load->model("MdlDataHistory");
        $hTmp = new MdlActivityLog();
        $hTmp->setFilters(array());
        if (isset($CI->session->login['id'])) {
            $className = $CI->uri->segment(2);
            $ctrlName = $CI->uri->segment(1);
            $url = current_url();
            $devices = $_SERVER['HTTP_USER_AGENT'];
            $ipadd = $_SERVER['REMOTE_ADDR'];
            $title = $this->tags['title'];
            $subtitle = $this->tags['sub_title'];
            $tmpHData = array(
                "method" => "$className",
                "controller" => "$ctrlName",
                "deskripsi_old" => "",
                "deskripsi_new" => "",
                "uid" => isset($CI->session->login['id']) ? $CI->session->login['id'] : "0",
                "uname" => isset($CI->session->login['nama']) ? $CI->session->login['nama'] : "noname",
                "category" => "browse",
                "url" => "$url",
                "devices" => $devices,
                "ipadd" => "$ipadd",
                "title" => $title,
                "sub_title" => $subtitle,
                "ghost" => isset($CI->session->login['ghost']) ? $CI->session->login['ghost'] : "0",
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        }


        //</editor-fold>
    }
// END OF COMPLETE REPEATED LOGIC

    public function selectBulan($strBulan, $key = "date")
    {
        // <a class="btn btn-primary btn-block" href="javascript:void(0)" onclick="location.href='https://san.mayagrahakencana.com/Transaksi/viewOutstanding/463//?date1='+document.getElementById('date1').value+'&amp;date2='+document.getElementById('date2').value+'';">
        //                                             <span class="fa fa-arrow-right"></span>
        //                                         </a>
        $action = $this->onClickTarget;

        $var = "";
        $var .= "<div class='input-group'>";
        $var .= "<input type='month' class='form-control' id='bulan' value='$strBulan'>";
        $var .= "<span class='input-group-btn'>";
        $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='$action?$key='+document.getElementById('bulan').value\"><i class='fa fa-send-o'></i></button>";
        // $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='jjj?='+get.value\"><i class='fa fa-send-o'></i></button>";
        $var .= "</span>";
        $var .= "</div>";

        return $var;
    }

    public function selectTahun($strTahun, $key = "year", $tambahan = "", $tambahan_link = "")
    {
        // <a class="btn btn-primary btn-block" href="javascript:void(0)" onclick="location.href='https://san.mayagrahakencana.com/Transaksi/viewOutstanding/463//?date1='+document.getElementById('date1').value+'&amp;date2='+document.getElementById('date2').value+'';">
        //                                             <span class="fa fa-arrow-right"></span>
        //
        //                                       </a>
        $thini = dtimeNow('Y');
        $action = $this->onClickTarget;
        // cekBiru($action);
        // cekMerah(sizeof(explode("?", $action)));
        $tanda_sambung = sizeof(explode("?", $action)) > 1 ? "&" : "?";
        // cekHijau("$tanda_sambung");
        $tambahan_link_app = "";
        if ($tambahan_link != "") {
            foreach ($tambahan_link as $kk => $yy) {
                $tambahan_link_app .= "$kk=$yy&";
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            // echo $i;
            // echo "$thini  ";
            $thnya = $thini--;
            $arrTahun[] = $thnya;
        }
        // arrPrint($arrTahun);

        $var = "";
        $var .= "<div class='input-group'>";
        // $var .= "<input type='month' class='form-control' id='bulan' value='$strTahun'>";
        $var .= "<select id='tahun' class='form-control' onchange=\"location.href='$action" . $tanda_sambung . "$tambahan_link_app" . $key . "='+this.value\">";
        $var .= "<option value=''>--pilih tahun--</option>";
        foreach ($arrTahun as $th) {
            $selected = $th == $strTahun ? "selected" : "";
            $var .= "<option value='$th' $selected>$th</option>";
        }
        $var .= "</select>";
        // $var .= "<span class='input-group-btn'>";
        // $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='$action?year='+document.getElementById('bulan').value\"><i class='fa fa-send-o'></i></button>";
        // // $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='jjj?='+get.value\"><i class='fa fa-send-o'></i></button>";
        $var .= $tambahan;
        // $var .= "</span>";
        $var .= "</div>";

        return $var;
    }

    public function selectTahunPembantu($strTahun, $key = "year", $tambahan = "", $tambahan_link = "")
    {
        // <a class="btn btn-primary btn-block" href="javascript:void(0)" onclick="location.href='https://san.mayagrahakencana.com/Transaksi/viewOutstanding/463//?date1='+document.getElementById('date1').value+'&amp;date2='+document.getElementById('date2').value+'';">
        //                                             <span class="fa fa-arrow-right"></span>
        //
        //                                       </a>
        $thini = dtimeNow('Y');
        $action = $this->onClickTarget;
        // cekBiru($action);
        // cekMerah(sizeof(explode("?", $action)));
        $tanda_sambung = sizeof(explode("?", $action)) > 1 ? "&" : "?";
        // cekHijau("$tanda_sambung");
        $tambahan_link_app = "";
        if ($tambahan_link != "") {
            foreach ($tambahan_link as $kk => $yy) {
                $tambahan_link_app .= "$kk=$yy&";
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            // echo $i;
            // echo "$thini  ";
            $thnya = $thini--;
            $arrTahun[] = $thnya;
        }
        // arrPrint($arrTahun);

        $var = "";
        $var .= "<div class='input-group'>";
        // $var .= "<input type='month' class='form-control' id='bulan' value='$strTahun'>";
        $var .= "<select id='tahun' class='form-control' onchange=\"location.href='$action" . $tanda_sambung . "$tambahan_link_app" . $key . "='+this.value\">";
        $var .= "<option value=''>--pilih tahun--</option>";
        foreach ($arrTahun as $th) {
            $selected = $th == $strTahun ? "selected" : "";
            $var .= "<option value='$th' $selected>$th</option>";
        }
        $var .= "</select>";
        // $var .= "<span class='input-group-btn'>";
        // $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='$action?year='+document.getElementById('bulan').value\"><i class='fa fa-send-o'></i></button>";
        // // $var .= "<button type='button' class='btn btn-info' onclick=\"location.href='jjj?='+get.value\"><i class='fa fa-send-o'></i></button>";
        $var .= $tambahan;
        // $var .= "</span>";
        $var .= "</div>";

        return $var;
    }

    public function selectDateRange($date1, $date2, $minDate, $maxDate, $thisPage)
    {

        //region range tanggal
        $var = "<div class='row'>";
        $var .= "<div class='col-md-12'>";

        $var .= "<div class='panel panel-default'>";
        $var .= "<div class='panel-body no-padding'>";
        $var .= "<div class='table-responsive'>";
        $var .= "<table class='table table-condensed no-padding no-border no-margin'
                                       style='border:0px solid black;'>";
        $var .= "<tr>";
        $var .= "<td valign='middle' class='text-right'>";
        $var .= "<span class='glyphicon glyphicon-calendar'></span> start date";
        $var .= "</td>";
        $var .= "<td>";
        $var .= "<input id='date1' class='form-control' type='date' value='$date1'
                                                   min='$minDate' max='$maxDate'>";
        $var .= "</td>";


        $var .= "<td valign='middle' class='text-right'>";
        $var .= "<span class='glyphicon glyphicon-calendar'></span> to date";
        $var .= "</td>";
        $var .= "<td>";
        $var .= "<input id='date2' class='form-control' type='date' value='$date2'
                                                    min='$minDate' max='$maxDate'>";
        $var .= "</td>";

        $var .= "<td align='left' valign='middle'>";
        $var .= "<a class='btn btn-primary bbtn-block' href='javascript:void(0)'
                      onclick=\"location.href='" . $thisPage . "&date1='+document.getElementById('date1').value+'&date2='+document.getElementById('date2').value+'';\"
                                             >";
        $var .= "<span class='fa fa-arrow-right'></span>";
        $var .= "</a>";

        $var .= "</td>";
        $var .= "</tr>";


        $var .= "</table>";
        $var .= "</div>";
        $var .= "</div>";
        $var .= "</div>";

        $var .= "</div>";
        $var .= "</div>";
        //endregion
        return $var;
    }

    /* ------------------------------------------------
     * saat ini digunakan untuk penampil hirarki COA
     * ------------------------------------------------*/
    public function dfs($HeadName, $HeadCode, $oResult, $visit, $d)
    {
        // START OF COMPLETE REPEATED LOGIC
        if ($d == 0) {
            $this->dfs_map = array();
            for ($i = 0; $i < count($oResult); $i++) {
                $parent = $oResult[$i]->p_head_name;
                $this->dfs_map[$parent][] = $i;
            }
        }

        $HeadCode_f = "<b class='text-red'>$HeadCode</b>";
        $var = "";
        if ($d == 0) {
            // $var .= "<li class=\"jstree-open \"><b>$HeadName</b>";
            $var .= "<li id='node_" . $HeadCode . "' class=\"jstree-open\"><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadName</a>";
        }
        else if ($d == 1) {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        }
        //        else if ($d == 2) {
        //            $var .= "<li class=\"jstree-open\"><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        //        }
        else {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        }

        $p = 0;
        if (isset($this->dfs_map[$HeadName])) {
            foreach ($this->dfs_map[$HeadName] as $i) {
            if (!$visit[$i]) {
                    $visit[$i] = true;
                    if ($p == 0) {
                        $var .= "<ul>";
                    }
                    $p++;
                    $var .= $this->dfs($oResult[$i]->head_name, $oResult[$i]->head_code, $oResult, $visit, $d + 1);
                }
            }
        }

        if ($p == 0) {
            $var .= "</li>";
        }
        else {
            $var .= "</ul>";
        }

        return $var;
        // END OF COMPLETE REPEATED LOGIC
    }

    public function dfs_code($HeadName, $HeadCode, $oResult, $visit, $d)
    {
        // START OF COMPLETE REPEATED LOGIC
        if ($d == 0) {
            $this->children_map = array();
            for ($i = 0; $i < count($oResult); $i++) {
                $parent = $oResult[$i]->p_head_name;
                $this->children_map[$parent][] = $i;
            }
        }

        $rekening = isset($oResult[$d]) && isset($oResult[$d]->rekening) ? $oResult[$d]->rekening : "";
        $HeadCode_f = "<b class='text-red'>$HeadCode</b>";
        $var = "";
        if ($d == 0) {
            // $var .= "<li class=\"jstree-open \"><b>$HeadName</b>";
            $var .= "<li id='node_" . $HeadCode . "' class=\"jstree-open\"><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadName</a>";
        }
        else if ($d == 1) {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        }
        else if ($d == 2) {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        }
        else if ($d == 3) {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        }
        else {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        }
        // arrPrint($oResult);
        $p = 0;
        if (isset($this->children_map[$HeadCode])) {
            foreach ($this->children_map[$HeadCode] as $i) {
            if (!$visit[$i]) {
                    $visit[$i] = true;
                    if ($p == 0) {
                        $var .= "<ul>";
                    }
                    // cekLime($HeadName);
                    $rekening = $oResult[$i]->head_name;
                    $rekening .= isset($oResult[$i]->rekening) && (strlen($oResult[$i]->rekening) > 1) ? ' - <span style="color: #FF55FF;">' . $oResult[$i]->rekening . '</span>' : "";
                    $p++;
                    $var .= $this->dfs_code($rekening, $oResult[$i]->head_code, $oResult, $visit, $d + 1);
                }
            }
        }

        if ($p == 0) {
            $var .= "</li>";
        }
        else {
            $var .= "</ul>";
        }

        return $var;
        // END OF COMPLETE REPEATED LOGIC
    }

    public function dfs_code_cashflow($HeadName, $HeadCode, $oResult, $visit, $d)
    {
        // START OF COMPLETE REPEATED LOGIC
        if ($d == 0) {
            $this->dfs_cashflow_map = array();
            for ($i = 0; $i < count($oResult); $i++) {
                $parent = $oResult[$i]->p_head_name;
                $this->dfs_cashflow_map[$parent][] = $i;
            }
        }

        // cekMerah(__LINE__);
        $rekening = isset($oResult[$d]) && isset($oResult[$d]->rekening) ? $oResult[$d]->rekening : "";
        $HeadCode_f = "<b class='text-red'>$HeadCode</b>";
        $var = "";

        if ($d == 0) {
            // $var .= "<li class=\"jstree-open \"><b>$HeadName</b>";
            $var .= "<li id='node_" . $HeadCode . "' class=\"jstree-open\"><a href='javascript:' onclick=\"loadCoaDataCashFlow('" . $HeadCode . "')\">$HeadName</a>";
        }
        else if ($d == 1) {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaDataCashFlow('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        }
        else if ($d == 2) {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaDataCashFlow('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        }
        else {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaDataCashFlow('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        }
        // arrPrint($oResult);
        // cekPink($HeadCode);
        $p = 0;
        if (isset($this->dfs_cashflow_map[$HeadCode])) {
            foreach ($this->dfs_cashflow_map[$HeadCode] as $i) {
            if (!$visit[$i]) {
                    $visit[$i] = true;
                    if ($p == 0) {
                        $var .= "<ul>";
                    }
                    // cekLime($HeadName);
                    $rekening = $oResult[$i]->head_name;
                    $rekening .= isset($oResult[$i]->rekening) && (strlen($oResult[$i]->rekening) > 1) ? ' - <span style="color: #FF55FF;">' . $oResult[$i]->rekening . '</span>' : "";
                    $p++;
                    // cekLime($oResult[$i]->head_code);
                    $var .= $this->dfs_code_cashflow($rekening, $oResult[$i]->head_code, $oResult, $visit, $d + 1);
                }
            }
        }

        if ($p == 0) {
            $var .= "</li>";
        }
        else {
            $var .= "</ul>";
        }

        return $var;
        // END OF COMPLETE REPEATED LOGIC
    }

    public function dfs_code_tk($HeadName, $HeadCode, $tokoId, $oResult, $visit, $d)
    {
        // START OF COMPLETE REPEATED LOGIC
        if ($d == 0) {
            $this->dfs_tk_map = array();
            for ($i = 0; $i < count($oResult); $i++) {
                $parent = $oResult[$i]->p_head_name;
                $this->dfs_tk_map[$parent][] = $i;
            }
        }

        $HeadCode_f = "<b class='text-red'>$HeadCode</b>";
        $var = "";
        if ($d == 0) {
            // $var .= "<li class=\"jstree-open \"><b>$HeadName</b>";
            $var .= "<li id='node_" . $HeadCode . "' class=\"jstree-open\"><a href='javascript:' onclick=\"loadCoaDataTk('" . $HeadCode . "','" . $tokoId . "')\">$HeadName</a>";
        }
        else if ($d == 1) {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaDataTk('" . $HeadCode . "','" . $tokoId . "')\">$HeadCode_f - $HeadName</a>";
        }
        // else if ($d == 2) {
        //     $var .= "<li class=\"jstree-open\"><a href='javascript:' onclick=\"loadCoaData('" . $HeadCode . "')\">$HeadCode_f - $HeadName</a>";
        // }
        else {
            $var .= "<li id='node_" . $HeadCode . "'><a href='javascript:' onclick=\"loadCoaDataTk('" . $HeadCode . "','" . $tokoId . "')\">$HeadCode_f - $HeadName</a>";
        }

        $p = 0;
        if (isset($this->dfs_tk_map[$HeadCode])) {
            foreach ($this->dfs_tk_map[$HeadCode] as $i) {
            if (!$visit[$i]) {
                    $visit[$i] = true;
                    if ($p == 0) {
                        $var .= "<ul>";
                    }
                    // cekLime($HeadName);
                    $p++;
                    $var .= $this->dfs_code_tk($oResult[$i]->head_name, $oResult[$i]->head_code, $tokoId, $oResult, $visit, $d + 1);
                }
            }
        }

        if ($p == 0) {
            $var .= "</li>";
        }
        else {
            $var .= "</ul>";
        }

        return $var;
        // END OF COMPLETE REPEATED LOGIC
    }
}
