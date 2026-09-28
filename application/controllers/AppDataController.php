<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AppDataController extends CI_Controller
{

    protected $searchString;

    // <editor-fold defaultstate="collapsed" desc="getter-setter">

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $className = "Mdl" . get_class($this);
        $menus = isset($this->config->item('menuConfig')['data']) ? $this->config->item('menuConfig')['data'] : array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        if (isset($dataAccess['view'])) {
            if (sizeof($menus) > 0) {
                foreach ($menus as $m => $mx) {
                    if (!in_array($dataAccess['view'], $mems)) {
                        $this->pageMenu .= "<li><a href='" . base_url() . "$m'><span class='glyphicon glyphicon-hdd'></span>$mx</a> </li>";
                    }
                }
                $this->pageMenu .= "<li><a href='authLogout'><span class='glyphicon glyphicon-off'>Keluar</a></li>";
            }
        }

        $this->load->model($className);
        $o = new $className;

        //$tblName = strtolower(get_class($this));
        $tblName = $o->getTableName();
        $query = $this->db->query("SHOW TABLES LIKE '$tblName'");
        if (sizeof($query->result()) < 1) {
            echo "tabel $tblName belum disiapkan. Salin dan jalankan kode sql berikut untuk menyiapkannya.<br>";
            $tableString = createTableFromObject($o);
            $sqlString = str_replace($className, strtolower(get_class($this)), $tableString);
            die("<textarea rows='2' cols = '160' onClick=\"this.select();\">$sqlString</textarea>");
        }
    }

    public function getSearchString()
    {
        return $this->searchString;
    }

    // </editor-fold>

    public function setSearchString($searchString)
    {
        $this->searchString = $searchString;
    }

    public function add()
    {
        include_once 'leftMenu.php';
        if (!isset($this->session->loginName)) {
            redirect(base_url() . "Login/authLogin");
            die();
        }
        //==menampilkan form penambahan data berdasarkan datamodel (kelas data) yang bersesuaian
        $className = "Mdl" . get_class($this);
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (!in_array($this->config->item('dataConfig')[$className]['add'], $mems)) {
            $p = new Page(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $p->addContent("<div class='alert alert-danger'>");
            $p->addContent("Anda tidak punya wewenang pada halaman ini<br>");
            $p->addContent("<a href='" . base_url() . "'>Ke depan</a>");
            $p->addContent("</div>");
            $p->render();
            die();
        }
        $o = new $className;
        $f = new MyForm($o, "add", array(
            "id" => "f1",
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/addProcess",
            "target" => "_self",
            "class" => "form-inline",
        ));
        $f->openForm();
        $f->fillForm();
        $f->closeForm();

        $title = isset($this->config->item('menuLabel')[get_class($this)]) ? $this->config->item('menuLabel')[get_class($this)] : get_class($this);
        $p = new Page($title, "Penambahan Data $title", "application/template/lte/index.html");
        //$p->addContent("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;'>");
        $p->addContent("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;'>");
        $p->addContent($f->getContent());
        $p->addContent("<div>");
        $p->setAppID($this->config->item('appConfig')['appID']);
        $p->setAppName($this->config->item('appConfig')['appName']);
        $p->setPageName(get_class($this));
        $p->setActionName($this->uri->segment(2));
        $p->setOptionName($this->uri->segment(3));
        //$p->setPageMenu($this->pageMenu);
        $p->setUserName($this->session->login['name'] . "@" . $this->session->login['outletName']);
        $p->setMnuData($mnuData);
        $p->setMnuTransaksi($mnuTransaksi);
        $p->render();
    }

    public function edit()
    {
        include_once 'leftMenu.php';
        if (!isset($this->session->loginName)) {
            redirect(base_url() . "Login/authLogin");
            die();
        }
        //==menampilkan form pengubahan data berdasarkan datamodel (kelas data) dan id-nya yang bersesuaian
        $className = "Mdl" . get_class($this);
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (!in_array($this->config->item('dataConfig')[$className]['edit'], $mems)) {
            $p = new Page(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $p->addContent("<div class='alert alert-danger'>");
            $p->addContent("Anda tidak punya wewenang pada halaman ini<br>");
            $p->addContent("<a href='" . base_url() . "'>Ke depan</a>");
            $p->addContent("</div>");
            $p->render();
            die();
        }
        $o = new $className;
        $indexFieldName = $o->getIndexFieldName();
        $selectedID = $this->uri->segment(3);
        //$tmp = $o->lookupByCondition(array($indexFieldName=> "'" . $selectedID . "'"))->result();
        $tmp = $o->lookupByCondition(array($indexFieldName => $selectedID))->result();
        $f = new MyForm($o, "edit", array(
            "id" => "f1",
            "method" => "post",
            "enctype" => "multipart/form-data",
            "action" => base_url() . get_class($this) . "/editProcess/" . $selectedID,
            "target" => "_self",
            "class" => "form-horizontal",
        ));
        $f->openForm();
        $f->fillForm($tmp);
        $f->closeForm();

        $title = isset($this->config->item('menuLabel')[get_class($this)]) ? $this->config->item('menuLabel')[get_class($this)] : get_class($this);
        $p = new Page($title, "Ubah Data $title", "application/template/lte/index.html");
        //$p->addContent("<div class='panel panel-default'>");
        $p->addContent("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;'>");
        $p->addContent($f->getContent());
        $p->addContent("</div>");
        $p->setAppID($this->config->item('appConfig')['appID']);
        $p->setAppName($this->config->item('appConfig')['appName']);
        $p->setPageName(get_class($this));
        $p->setActionName($this->uri->segment(2));
        $p->setOptionName($this->uri->segment(3));
        //$p->setPageMenu($this->pageMenu);
        $p->setUserName($this->session->login['name'] . "@" . $this->session->login['outletName']);
        $p->setMnuData($mnuData);
        $p->setMnuTransaksi($mnuTransaksi);
        $p->render();
    }

    public function addProcess()
    {
        include_once 'leftMenu.php';
        //==menyimpan inputan data baru ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = "Mdl" . get_class($this);
        $o = new $className;
        $f = new MyForm($o, "addProcess");
        if ($f->isInputValid()) { //==jika validasi lengkap
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['fieldName']) ? $spec['fieldName'] : $fieldName;
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
                            $data[$fName] = base64_encode(file_get_contents($this->input->post($fName)));
                            break;
                        case "hidden":
                            switch ($spec['type']) {
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
            if (sizeof($o->getFilters()) > 0) {
                foreach ($o->getFilters() as $k => $v) {

                    $condPair = explode("=", $v);
                    if (sizeof($condPair) > 1) {
                        $data[$condPair[0]] = trim($condPair[1], "'");
                    }
                }
            }
            $insertID = $o->addData($data, $o->getTableName());
            //===writing history
            //===redirectnya harus diatur
            if ((null != $o->getCustomLink()) && is_array($o->getCustomLink())) {
                $strCustomDetail = "";
                $lSpec = $o->getCustomLink()['detail'];

                $key = $lSpec['key'];
                $targetKey = $lSpec['targetKey'];
                redirect(base_url() . $lSpec['link'] . "/index/1/$targetKey/" . $insertID);
            }
            else {

                redirect(base_url() . get_class($this) . "/view");
            }

        }
        else {
            //===jika tidak lolos validasi
            $title = isset($this->config->item('menuLabel')[get_class($this)]) ? $this->config->item('menuLabel')[get_class($this)] : get_class($this);
            $p = new Page($title, "Oops.. Kesalahan masukan..!", "application/template/lte/index.html");

            //region validasi inputan
            $tmpValidResults = $f->getValidationResults();
            $p->addContent("<div class='alert alert-danger'>");
            foreach ($f->getValidationResults() as $err) {
                $p->addContent("Kesalahan pada inputan <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>");
            }
            $p->addContent("</div>");
            //endregion validasi inputan
            //region render ulang form dengan hasil validasi
            $className = "Mdl" . get_class($this);
            $o = new $className;
            $f = new MyForm($o, "add", array(
                "id" => "f1",
                "method" => "post",
                "action" => base_url() . get_class($this) . "/addProcess",
                "target" => "_self",
            ));
            $f->setValidationResults($tmpValidResults);
            $f->openForm();
            $f->fillForm();
            $f->closeForm();
            //$p->addContent("<div class='panel panel-default'>");
            $p->addContent("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;'>");
            $p->addContent($f->getContent());
            $p->addContent("</div>");
            $p->setAppID($this->config->item('appConfig')['appID']);
            $p->setAppName($this->config->item('appConfig')['appName']);
            $p->setPageName(get_class($this));
            $p->setActionName($this->uri->segment(2));
            $p->setOptionName($this->uri->segment(3));
            $p->setMnuData($mnuData);
            $p->setMnuTransaksi($mnuTransaksi);
            //$p->setPageMenu($this->pageMenu);
            $p->render();
            //endregion render ulang form dengan hasil validasi
        }
    }

    public function editProcess()
    {
        include_once 'leftMenu.php';
        //==menyimpan inputan perubahan data ke dalam datamodel, lalu dari datamodel ke database (dilakukan oleh CI)
        $className = "Mdl" . get_class($this);
        $o = new $className;
        $indexFieldName = $o->getIndexFieldName();
        $f = new MyForm($o, "editProcess");
        if ($f->isInputValid()) { //==jika validasi lengkap
            foreach ($o->getFields() as $fieldName => $spec) {
                $fName = isset($spec['fieldName']) ? $spec['fieldName'] : $fieldName;
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
                            $data[$fName] = base64_encode(file_get_contents($this->input->post($fName)));
                            break;
                        case "hidden":
                            switch ($spec['type']) {
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
            $where = array(
                $indexFieldName => $data['id'],
            );
            $o->updateData($where, $data, $o->getTableName());
            //redirect(base_url() . get_class($this) . "/view");
            if ((null != $o->getCustomLink()) && is_array($o->getCustomLink())) {
                $strCustomDetail = "";
                $lSpec = $o->getCustomLink()['detail'];

                $key = $lSpec['key'];
                $targetKey = $lSpec['targetKey'];
                redirect(base_url() . $lSpec['link'] . "/index/1/$targetKey/" . $data[$targetKey]);
            }
            else {

                redirect(base_url() . get_class($this) . "/view");
            }
        }
        else {
            //===jika tidak lolos validasi
            $title = isset($this->config->item('menuLabel')[get_class($this)]) ? $this->config->item('menuLabel')[get_class($this)] : get_class($this);
            $p = new Page($title, "Oops.. Kesalahan masukan..!", "application/template/lte/index.html");

            //region validasi inputan
            $p->addContent("<div class='alert alert-danger'>");
            foreach ($f->getValidationResults() as $err) {
                $p->addContent("Kesalahan pada inputan <strong>$err[fieldLabel]</strong>:  $err[errMsg]<br>");
            }
            $p->addContent("</div>");
            //endregion validasi inputan
            //region render ulang form dengan hasil validasi
            $className = "Mdl" . get_class($this);
            $o = new $className;
            $f = new MyForm($o, "edit", array(
                "id" => "f1",
                "method" => "post",
                "action" => base_url() . get_class($this) . "/editProcess",
                "target" => "_self",
            ));
            $f->openForm();
            $f->fillForm();
            $f->closeForm();
            $p->addContent("<div class='panel panel-default'>");
            $p->addContent($f->getContent());
            $p->addContent("</div>");
            $p->setAppID($this->config->item('appConfig')['appID']);
            $p->setAppName($this->config->item('appConfig')['appName']);
            $p->setPageName(get_class($this));
            $p->setActionName($this->uri->segment(2));
            $p->setOptionName($this->uri->segment(3));
            $p->setMnuData($mnuData);
            $p->setMnuTransaksi($mnuTransaksi);
            //$p->setPageMenu($this->pageMenu);
            $p->render();
            //endregion render ulang form dengan hasil validasi
        }
    }

    public function delete()
    {
        //==menghapus (aslinya mendisable) data sesuai datamodel dan id-nya yang bersesuaian
        $className = "Mdl" . get_class($this);
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        if (!in_array($this->config->item('dataConfig')[$className]['delete'], $mems)) {
            $p = new Page(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $p->addContent("<div class='alert alert-danger'>");
            $p->addContent("Anda tidak punya wewenang pada halaman ini<br>");
            $p->addContent("<a href='" . base_url() . "'>Ke depan</a>");
            $p->addContent("</div>");
            $p->render();
            die();
        }
        $o = new $className;
        $indexFieldName = $o->getIndexFieldName();
        $selectedID = $this->uri->segment(3);
        $where = array($indexFieldName => $selectedID);
        //$o->lookupByCondition(array($indexFieldName=>"'" . $selectedID . "'"));
        $o->lookupByCondition(array($indexFieldName => $selectedID));
        $data['trash'] = "1";
        //$o->deleteData($where, $o->getTableName());
        $o->updateData($where, $data, $o->getTableName());
        redirect(base_url() . get_class($this) . "/view");
    }

    public function index()
    {
        //==aksi default, yaitu dibawa ke mode "view"
        //==sebelumnya dicek dulu, user buka halaman pakai slash atau enggak
        $splitStr = explode("/", __FILE__);
        if (get_class($this) . ".php" != $splitStr[sizeof($splitStr) - 1]) {
            redirect(base_url() . get_class($this) . "/view");
        }
        else {
            die("DiRECT access to this file is N.O.T. allowed!");
        }
    }

    public function view()
    {
        include_once 'leftMenu.php';
        if (!isset($this->session->login)) {
            redirect(base_url() . "Login/authLogin");
            die();
        }
        $className = "Mdl" . get_class($this);
        $dataAccess = isset($this->config->item('dataConfig')[$className]) ? $this->config->item('dataConfig')[$className] : array(
            "view" => "",
            "add" => "",
            "edit" => "",
            "delete" => "",
            "viewHistory" => "",
        );

        $jmlMatch = 0;
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

        foreach ($mems as $mID) {
            //print_r($this->config->item('menuConfig')[$mID]);

            if (isset($this->config->item('menuConfig')[$mID]) && sizeof($this->config->item('menuConfig')[$mID]) > 0) {
                foreach ($this->config->item('menuConfig')[$mID] as $subName => $mSpec) {
                    if (array_key_exists(get_class($this), $mSpec)) {
                        $jmlMatch++;
                    }
                }
            }
        }

        //echo "jmlMatch: $jmlMatch";
        if ($jmlMatch < 1) {
            $p = new Page(get_class($this), "Wewenang ditolak", "application/template/blank.html");
            $p->addContent("<div class='alert alert-danger'>");
            $p->addContent("Anda tidak punya wewenang pada halaman ini<br>");
            $p->addContent("<a href='" . base_url() . "'>Ke depan</a>");
            $p->addContent("</div>");
            $p->render();
            die();
        }


        $o = new $className;
        $indexFieldName = $o->getIndexFieldName();

        // <editor-fold defaultstate="collapsed" desc="pre-query relasi/dependensi tabel">
        $relationList = $o->getRelations();
        $usingCommonTable = false;
        if (sizeof($relationList) > 0) {

            foreach ($relationList as $mdl) {

                $_resultArray[$mdl] = array();
                $this->load->model($mdl);
                $ox = new $mdl;
                //echo "<hr>".$ox->getTableName()." - ".$o->getTableName()."<hr>";
                if ($ox->getTableName() == $o->getTableName()) {
                    //echo "there's a common table";
                    $usingCommonTable = true;
                }
                $_results = $ox->lookupAll()->result();
                foreach ($_results as $m => $mx) {
                    //echo $mx->$indexFieldName.$mx->name."m: $m <br>";
                    $_resultArray[$mdl][$mx->$indexFieldName] = isset($mx->name) ? $mx->name : $mx->nama;
                }
            }
            //die();
        }
        // </editor-fold>

        $menuLabel = ($this->config->item('menuLabel') != NULL) ? $this->config->item('menuLabel') : array();
        $title = isset($this->config->item('menuLabel')[get_class($this)]) ? $this->config->item('menuLabel')[get_class($this)] : get_class($this);
        if (isset($_GET['k']) && strlen($_GET['k']) > 1) {
            $key = $_GET['k'];
            $subtitle = "Pencarian dengan nama '$key'";
        }
        else {
            $key = "";
            $subtitle = "Daftar $title";
        }

        $p = new Page($title, $subtitle, "application/template/lte/index.html");
        $t = new Table();


        //$p->addContent("<div class='panel-body'>");
        $p->addContent("<div class='alert' style='background:#e5e5c5;border:1px #cccccc solid;color:#232323;position:relative;top:0;overflow:auto;'>");

        $addLink = base_url() . get_class($this) . "/add";


        // <editor-fold defaultstate="collapsed" desc="pagination">

        $params = array();
        $limit_per_page = 12;
        $page = ($this->uri->segment(3)) ? ($this->uri->segment(3) - 1) : 0;

        $p->setSubTitle($subtitle . " hal. " . ($page + 1));
        $total_records = $o->lookupDataCount($key);
        if ($total_records > 0) {
// get current page records
            if (isset($_GET['sort']) && strlen($_GET['sort']) > 0) {
                $o->setSortby($_GET['sort']);
            }
            $params["results"] = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);

            $config = array(
                'base_url' => base_url() . get_class($this) . '/' . __FUNCTION__,
                'total_rows' => $total_records,
                'per_page' => $limit_per_page,
                "uri_segment" => 3,
                // custom paging configuration
                'num_links' => 4,
                'use_page_numbers' => TRUE,
                'reuse_query_string' => TRUE,
                'full_tag_open' => '<div class="text-center">',
                'full_tag_close' => '</div>',
                'first_link' => "<span class='fa fa-home'></span>",
                'first_tag_open' => '<span style="padding:1px,">',
                'first_tag_close' => '</span>',
                'last_link' => "<span class='fa fa-gg'></span>",
                'last_tag_open' => '<span style="padding:1px,">',
                'last_tag_close' => '</span>',
                'next_link' => "<span class='fa fa-angle-right'></span>",
                'next_tag_open' => '<span style="padding:1px,">',
                'next_tag_close' => '</span>',
                'prev_link' => "<span class='fa fa-angle-left'></span>",
                'prev_tag_open' => '<span style="padding:1px,">',
                'prev_tag_close' => '</span>',
                'cur_tag_open' => '<span class="btn btn-primary disabled">',
                'cur_tag_close' => '</span>',
                'num_tag_open' => '<span style="padding:1px,">',
                'num_tag_close' => '</span>',
            );
            $this->pagination->initialize($config);

            // build paging links
            $params["links"] = $this->pagination->create_links();
        }
        // </editor-fold>

        $tmp = isset($params['results']) ? $params['results'] : array(); //===hasil data yang dibelokin ke hasil pagination


        $dataRow = array();
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

//        if (isset($dataAccess['add']) && in_array($dataAccess['add'], $mems)) {
//            $strAddLink = "";
//            if (isset($categorySpec) && sizeof($categorySpec) > 0) {
//                $strAddLink .= "<button onClick=\"document.location.href='$addFolderLink'\" data-toggle='tooltip' data-placement='top' title='Tambah kategori' class='btn btn-circle btn-primary bg-orange'><span class='glyphicon glyphicon-plus'></button>";
//            }
//            $strAddLink .= "<button onClick=\"document.location.href='$addLink'\" data-toggle='tooltip' data-placement='top' title='Tambah entri' class='btn btn-circle btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";
//            $dataRow[] = $strAddLink;
//        }
//        $p->addContent($t->addSpanRow($dataRow, sizeof($o->getListedFields()), "right"));
        // <editor-fold defaultstate="collapsed" desc="tampilkan nomor2 halaman">
        if (sizeof($tmp) > 0) {
            $p->addContent($t->addSpanRow(array($params['links']), sizeof($o->getListedFields()), "center"));
        }// </editor-fold>

        $defaultKey = $key != "" ? $key : "cari " . strtolower($title);
        $p->addContent($t->addSpanRow(array("<input type=text placeholder='$defaultKey' class='form-control text-center' onkeyup=\"if(detectEnter()==1){location.href='" . base_url() . get_class($this) . "/view?k='+this.value}\">")));
        if (sizeof($tmp) > 0) {//===ada data
            // <editor-fold defaultstate="collapsed" desc="header tabel">
            $headerString = array();
            $p->addContent($t->openTable(array("class='table table-bordered'", "style='background:#ffffff;color:#232323;'")));
            foreach ($o->getListedFields() as $n => $nx) {
                //glyphicon glyphicon-sort-by-alphabet-alt
                $colStr = "<a href='" . base_url() . get_class($this) . "/view?sort=$nx' style='color:#454545;'>";
                $colStr .= "<span class='glyphicon glyphicon-sort-by-alphabet-alt'>";

                $colStr .= "</a>";
                $colStr .= ucwords($o->getFields()[$nx]['label']);
                $headerString[] = $colStr;
            }

            $headerString[] = " ";
            $p->addContent($t->addHeaderRow($headerString));
            // </editor-fold>
            //region datacontent

            $fields = $o->getFields();

            // <editor-fold defaultstate="collapsed" desc="translate nama kolom datamodel vs. kolom tabel">
            $arrFieldPairs = array();
            foreach ($fields as $fName => $fSpec) {
                if (isset($fSpec['fieldName'])) {
                    $arrFieldPairs[$fName] = $fSpec['fieldName'];
                }
                else {
                    $arrFieldPairs[$fName] = $fName;
                }
            }
            // </editor-fold>
            // <editor-fold defaultstate="collapsed" desc="nomor baris di masing2 halaman">
            //$rowCounter = 0;
            if ($this->uri->segment(3) > 0) {
                $rowCounter = ($limit_per_page * ($this->uri->segment(3) - 1));
            }
            else {
                $rowCounter = 0;
            }// </editor-fold>


            // <editor-fold defaultstate="collapsed" desc="iterasi tampilan, jika bukan berupa selfCategory">
            foreach ($tmp as $m => $mx) {
                //$p->addContent("$m".gettype($mx)."<br>");
                if (isset($dataAccess['edit']) && in_array($dataAccess['edit'], $mems)) {
                    $updateLink = base_url() . get_class($this) . "/edit/" . $mx->$indexFieldName . "";
                    $updateCommentStr = "Klik untuk mengubah entri";
                }
                else {
                    $updateLink = "#";
                    //$updateLink = base_url() . get_class($this) . "/edit/" . $mx->$indexFieldName;
                    $updateCommentStr = "Anda tidak berhak mengubah entri";
                }
                $deleteLink = base_url() . get_class($this) . "/delete/" . $mx->$indexFieldName . "";

                $childName = $o->getChild();
                if (strlen($childName) > 0) {
                    $childLink = base_url() . ltrim($childName, "Mdl") . "/view/ChildOf-" . $mx->$indexFieldName . "";
                    $childLinkStr = "<a href='$childLink'><span class='glyphicon glyphicon-chevron-right'></span></a>";
                }
                else {
                    $childLink = "";
                    $childLinkStr = "";
                }

                $dataRow = array();
                $colCounter = 0;
                $rowCounter++;
                //$dataRow[] = $rowCounter;

                $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();

                if ((null != $o->getCustomLink()) && is_array($o->getCustomLink())) {
                    $strCustomDetail = "";
                    $lSpec = $o->getCustomLink()['detail'];
                    //echo $lSpec['link']."/".$lSpec['key'];

                    $key = $lSpec['key'];
                    $targetKey = $lSpec['targetKey'];
                    $strCustomDetail .= "<button onClick=\"document.location.href='" . base_url() . $lSpec['link'] . "/index/1/$targetKey/" . $mx->$key . "';\" data-toggle='tooltip' data-placement='top' title='" . $lSpec['label'] . "' class='btn btn-primary'>" . $lSpec['label'] . "</button>";
                }
                else {
                    $strCustomDetail = "";
                }

                //foreach ($mx as $n => $nx) {
                foreach ($o->getListedFields() as $ofName) {
                    $fName = $arrFieldPairs[$ofName];
                    //echo "fName: $fName, ofName:$ofName <br>";
                    if (isset($o->getFields()[$ofName]['reference'])) {//===berrelasi
                        $arrSource = array();
                        if (isset($o->getFields()[$ofName]['reference']) && isset($o->getFields()[$ofName]['dataSource'])) {//===berrelasi
                            if (isset($o->getFields()[$ofName]['reference'])) {
                                $className = $o->getFields()[$ofName]['reference'];
                                //$fieldLabel = $_resultArray[$className];
                                $arrSource = $_resultArray[$className];
                            }
                            if (isset($o->getFields()[$ofName]['dataSource'])) {
                                //$arrSource=array_merge($arrSource,$o->getFields()[$ofName]['dataSource']);
                                foreach ($o->getFields()[$ofName]['dataSource'] as $k => $v) {
                                    $arrSource[$k] = $v;
                                }
                            }
                            //$fieldLabel = $_resultArray[$className][$mx->$fName];
                            $fieldLabel = isset($arrSource[$mx->$fName]) ? $arrSource[$mx->$fName] : "(unknown)";
                        }
                        else {
                            if (isset($o->getFields()[$ofName]['reference'])) {
                                $className = $o->getFields()[$ofName]['reference'];
                                //$fieldLabel = $_resultArray[$className];
                                $arrSource = $_resultArray[$className];
                                //echo "fName: $fName, ofName:$ofName <br>";
                                $fieldLabel = isset($arrSource[$mx->$fName]) ? $arrSource[$mx->$fName] : "item_" . $mx->$fName;
                            }
                            else {
                                if (isset($o->getFields()[$ofName]['dataSource'])) {
                                    //$arrSource=array_merge($arrSource,$o->getFields()[$ofName]['dataSource']);
                                    foreach ($o->getFields()[$ofName]['dataSource'] as $k => $v) {
                                        $arrSource[$k] = $v;
                                    }
                                }
                            }
                        }
                    }
                    else {//===tidak berrelasi
                        $fieldType = isset($o->getFields()[$ofName]['type']) ? $o->getFields()[$ofName]['type'] : "text";
                        //echo "$fName/$ofName/$fieldType<br>";
                        if (isset($o->getFields()[$ofName]['inputType'])) {// && $o->getFields()[$ofName]['inputType'] == "checkbox") {
                            switch ($o->getFields()[$ofName]['inputType']) {
                                case "checkbox":
                                    $ds = $o->getFields()[$ofName]['dataSource'];
                                    $data = unserialize(base64_decode($mx->$fName));
                                    if (is_array($data)) {
                                        $fieldLabel = "<span class='label label-primary'>" . sizeof($data) . "</span> entri";
                                    }
                                    else {
                                        $fieldLabel = "(kosong)";
                                    }
                                    break;

                                case "texts":
                                    $dp = $o->getFields()[$ofName]['dataParams'];
                                    //$ds = $o->getFields()[$ofName]['dataSource'];
                                    $data = unserialize(base64_decode($mx->$fName));
                                    if (is_array($data)) {
                                        $fieldLabel = "";
                                        foreach ($data as $key => $val) {
                                            $fieldLabel .= "<span class='small'>$key:</span><strong>$val</strong>";
                                        }
                                    }
                                    else {
                                        $fieldLabel = "(kosong)";
                                    }
                                    break;
                                case "combo":
                                    $fieldLabel = $o->getFields()[$ofName]['dataSource'][$mx->$fName];
                                    break;
                                case "radio":
                                    $fieldLabel = $o->getFields()[$ofName]['dataSource'][$mx->$fName];
                                    break;
                                default:
                                    $fieldLabel = $mx->$fName;
                                    break;
                            }
                        }
                        else {
                            switch ($fieldType) {
                                case "varchar":
                                    $fieldLabel = $mx->$fName;
                                    break;

                                default:
                                    $fieldLabel = $mx->$fName;

                                    break;
                            }
                        }
                    }
                    if (in_array($this->config->item('dataConfig')[$className]['edit'], $mems)) {
                        $addNumber = $colCounter == 0 ? "<span class='badge' style='background:#abcdef;color:#323232;'>$rowCounter</span>" : "";
                        $dataRow[] = "$addNumber&nbsp;<a style='color:#343434;text-decoration:none;' href='$updateLink' data-toggle='tooltip' data-placement='right' rel='tooltip' title='$updateCommentStr'>" . str_replace(" ", "&nbsp;", $fieldLabel) . "</a>&nbsp;"
                            . $childLinkStr;
                    }
                    else {
                        //$dataRow[] = str_replace(" ", "&nbsp;", $fieldLabel) . "&nbsp;" . $childLinkStr;
                        $dataRow[] = str_replace(" ", "&nbsp;", $fieldLabel) . "&nbsp;" . $childLinkStr;
                    }
                    $colCounter++;
                }
                $dataRow[] = $strCustomDetail;
                if (isset($dataAccess['delete']) && in_array($dataAccess['delete'], $mems)) {
                    $dataRow[] = "<button class='btn btn-danger btn-circle bg-orange-gradient' data-toggle='tooltip' data-placement='left' title='Hapus entri' onClick=\"if(confirm('Hapus data?')==1){location.href='$deleteLink'}\"><span class='glyphicon glyphicon-remove'></button>";
                    //$dataRow[] = "<button class='btn btn-danger btn-circle bg-orange-gradient' data-toggle='modal' data-target='#mdl' data-href='$deleteLink' title='Hapus entri'><span class='glyphicon glyphicon-remove'></button>";
                }

                $p->addContent($t->addRow($dataRow));
            }//

            // </editor-fold>
            //endregion datacontent
        }
        else {//===tidak ada data
            $p->addContent($t->addRow(array("--tidak ada data--")));
        }
        $p->addContent("</div>");
        $p->addContent($t->closeTable());


        // <editor-fold defaultstate="collapsed" desc="tampilkan nomor2 halaman">
        if (sizeof($tmp) > 0) {
            //$p->addContent("<div class='panel panel-default'>");
            $p->addContent($t->addSpanRow(array($params['links']), sizeof($o->getListedFields()), "center"));
            //$p->addContent("</div>");
        }// </editor-fold>
        // <editor-fold defaultstate="collapsed" desc="tombol addItem">
        $dataRow = array();

        if ((null != $o->getCustomLink()) && is_array($o->getCustomLink())) {
            $strCustomGlobal = "";
            foreach ($o->getCustomLink()['global'] as $link => $label) {
                $strCustomGlobal .= "<button onClick=\"document.location.href='" . base_url() . "$link'\" data-toggle='tooltip' data-placement='top' title='$label' class='btn btn-primary bg-orange'>$label</button>";
            }
        }
        else {
            $strCustomGlobal = "";
        }

        if (isset($dataAccess['add']) && in_array($dataAccess['add'], $mems)) {
            $strAddLink = "";
            $strAddLink .= "<button onClick=\"document.location.href='$addLink'\" data-toggle='tooltip' data-placement='top' title='Tambah entri' class='btn btn-circle btn-xl btn-primary bg-blue-gradient'><span class='glyphicon glyphicon-plus'></button>";
            $dataRow[] = $strAddLink;

            $p->addContent("<div class='text-right'>");
            $p->addContent($strCustomGlobal);
            $p->addContent($strAddLink);
            $p->addContent("</div class='text-right'>");
        }
//$p->addContent($t->addSpanRow($dataRow, sizeof($this->o->getListedFields()), "right"));
// </editor-fold>


        if (isset($dataAccess)) {
            $tmp = "";
            foreach ($mems as $m) {
                $tmp .= ($m . " ");
            }

            $p->addContent("<div class='text-center bg-yellow-gradient'>");
            $p->addContent("Wewenang anda pada halaman ini: ");
            $accesses = "";
            foreach ($dataAccess as $accessType => $accountType) {
                //$tmp.= in_array($accountType, $mems) ? "diizinkan" : "tidak diizinkan";
                $accesses .= in_array($accountType, $mems) ? "<strong>" . $accessType . "</strong>, " : "";
                //$tmp .= "$accessType:$thisEnabled <br>";
            }
            $p->addContent(rtrim($accesses, ", ") . "</div>");
        }
        //$p->addContent("</form>");
        //$p->addContent(createTableFromObject($o));
        $p->setAppID($this->config->item('appConfig')['appID']);
        $p->setAppName($this->config->item('appConfig')['appName']);
        $p->setPageName(get_class($this));
        $p->setActionName($this->uri->segment(2));
        $p->setOptionName($this->uri->segment(3));
        //$p->setPageMenu($this->pageMenu);
        $p->setUserName($this->session->login['name'] . "@" . $this->session->login['outletName']);
        $p->setMnuData($mnuData);
        $p->setMnuTransaksi($mnuTransaksi);
        $p->render();
    }

    public function lookup()
    {//===entries, umumnya berupa produk
        //$className = "Mdl" . get_class($this);
        //$this->load->model($className);
        $trClassName = $this->uri->segment(8);

        //$num = ltrim($trClassName, "T");
        //$config = $this->config->item('transConfig')[$num];

        $divID = "iid" . $this->uri->segment(3);
        $txtID = "txt" . $this->uri->segment(3);
        $qtyID = "qty" . $this->uri->segment(3);
        $valID = "value" . $this->uri->segment(3);
        $parsID = "params" . $this->uri->segment(3);
        $valsID = "values" . $this->uri->segment(3);
        $txtIDNext = "txt" . ($this->uri->segment(3) + 1);
        $tabel = $this->uri->segment(4);
        $keyword = $this->uri->segment(5);
        $usedPriceColumn = $this->uri->segment(6);
        $usedPriceIndex = $this->uri->segment(7);
        $entryType = $this->uri->segment(1);
        $entryClassName = "Mdl" . $entryType;


        $o = new $entryClassName();


        $q = "select * from $tabel where " . createSmartSearch($keyword, array("name")) . "";
        $data = $this->db->query($q)->result();
        if (sizeof($data) > 0) {
            $priceParams = $o->getFields()[$usedPriceColumn]['dataParams'];
            echo "<div class='alert alert-warning'>Pilih $entryType berikut..</div>";
            echo "<div class='bg-gray'>";
            echo "<div class='list-group'>";
            $entries = array();
            $strIDs = "";
            foreach ($data as $spec) {
                $arrValue = unserialize(base64_decode($spec->$usedPriceColumn));
                $entries[] = $spec->$indexFieldName;
                $strIDs .= $spec->$indexFieldName . ",";
                echo "<div class='list-group-item text-black'>";
                echo
                    "<a href='javascript:void(0)' onClick=\""
                    . "document.getElementById('" . $divID . "').value='" . $spec->$indexFieldName . "';"
                    . "document.getElementById('" . $txtID . "').value='" . $spec->name . "';"
                    . "document.getElementById('" . $valID . "').value='" . $arrValue[$usedPriceIndex] . "';"
                    . "document.getElementById('" . $parsID . "').value='" . implode("|", $priceParams) . "';"
                    . "document.getElementById('" . $valsID . "').value='" . implode("|", $arrValue) . "';"
                    . "document.getElementById('eLookup').innerHTML='';"
                    . "document.getElementById('" . $qtyID . "').focus();"
                    //. "if(document.getElementById('" . $txtIDNext . "')){document.getElementById('" . $txtIDNext . "').select();}"
                    . "\">" .
                    "<span class='fa fa-hand-o-right'></span> " .
                    //$spec->$indexFieldName.
                    $spec->name .
                    $arrValue[$usedPriceIndex] .
                    "</a>";
                echo "</div>";
            }
            $strIDs = rtrim($strIDs, ",");
            echo "</div>";
            //echo "</div>";
            echo "</div>";
        }
        else {
            echo "<div class='alert alert-warning'>Tidak ada yang cocok..</div>";
        }
        //print_r($data);
    }

    public function lookupE()
    {//==supplier/customer
        $className = "Mdl" . get_class($this);
        $tabel = strtolower($this->uri->segment(1));
        $keyword = $this->uri->segment(3);

        $q = "select * from $tabel where name like '%$keyword%'";
        $data = $this->db->query($q)->result();
        if (sizeof($data) > 0) {
            echo "<div class='alert alert-warning'>Pilih $tabel berikut..</div>";
            echo "<ul class='list-group'>";
            foreach ($data as $spec) {
                echo "<li class='list-group-item'>";
                echo
                    "<a href='javascript:void(0)' onClick=\"document.getElementById('eID').value='" . $spec->$indexFieldName . "';document.getElementById('eName').value='" . $spec->name . "';document.getElementById('eLookup').innerHTML='';\">" .
                    "<span class='fa fa-female'></span> " .
                    //$spec->$indexFieldName.
                    $spec->name .
                    "</a>";
                echo "</li>";
            }
            echo "</ul>";
        }
        else {
            echo "<div class='alert alert-warning'>Tidak ada $tabel yang cocok..</div>";
        }
        //print_r($data);
    }

}
