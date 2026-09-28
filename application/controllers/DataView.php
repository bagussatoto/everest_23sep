<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 06/04/18
 * Time: 14:54
 */

include_once "ExecuteData.php";

class DataView extends ExecuteData
{
    private $model;
    private $configPath;

    function __construct()
    {
        parent::__construct();
        $this->load->model("MdlCabang");
        $this->path = base_url();
        $this->path_referer = base_url() . get_class($this) . "/view/1";
//        $this->url_segment = $this->uri->segment
        if (strlen($this->uri->segment(3)) > 2) {
            $this->model = "Mdl" . $this->uri->segment(3);
            $this->load->model($this->model);
        }


        $this->load->config('heDataBehaviour');
        $this->configPath = $this->config->item('heDataBehaviour');
    }

    public function index()
    {
        if (sizeof($this->configPath) > 0) {
            $availMenus = array();
            $availNewMenus = array();
            $loginType = $this->session->login['jenis'];
            foreach ($this->configPath as $mdlName => $mSpec) {
                if (isset($mSpec['viewers'])) {
                    if (sizeof($mSpec['viewers']) > 0) {
                        if (in_array($loginType, $mSpec['viewers'])) {
                            $availMenus[$mdlName] = str_replace("Mdl", "", $mdlName);
                        }
                    }
                    if (sizeof($mSpec['creators']) > 0) {
                        if (in_array($loginType, $mSpec['creators'])) {
                            $availNewMenus[$mdlName] = str_replace("Mdl", "", $mdlName);
                        }
                    }
                }
            }

        }
        else {
            die("No data config found!");
        }


        $data = array(
            "mode" => $this->uri->segment(2),
            "availMenus" => $availMenus,
            "availNewMenus" => $availNewMenus,
        );
        $this->load->view("data_index", $data);
    }

    function view()
    {
        $cb = New MdlCabang();
        $cu = New $this->model();


        $arrUserJenisParent = userJenisParent();
        $arrCabang = $cb->lookupLimitedActive()->result();
        $dropdown_data['cabang_id'] = $arrCabang;
        $dropdown_data['jenis'] = $arrUserJenisParent;

        $per_page = 10;
        $segment_page = $this->uri->total_segments();
        $page = ($this->uri->segment($segment_page)) ? $this->uri->segment($segment_page) - 1 : 0;
        $start_page = $page * $per_page;
        $stop_page = $per_page;
        $search = $cu->getSearch();

        $objName = str_replace("Mdl", "", $this->model);


        $action = array(
            "edit" => base_url() . get_class($this) . "/edit/" . $this->uri->segment(3),
            "delete" => base_url() . get_class($this) . "/deleteProses/" . $this->uri->segment(3),
            "history" => base_url() . get_class($this) . "/history/" . $this->uri->segment(3),
        );


        if ($this->uri->segment(4) == "active") {

            $result = $cu->lookupLimitedActive($start_page, $stop_page)->result();
//            print_r($result);die();
            $result_rows = $cu->lookupTotalActive();
            $my_title = "Data " . $objName . " " . $this->uri->segment(4);
        }
        elseif ($this->uri->segment(4) == "non_active") {
            $result = $cu->lookupLimitedNonActive($start_page, $stop_page)->result();
            $result_rows = $cu->lookupTotalNonActive();
            $my_title = "Data " . $objName . " " . $this->uri->segment(4);

            $action = array(
//                "edit" => base_url() . get_class($this) . "/edit",
                "undelete" => base_url() . get_class($this) . "/undeleteProses/" . $this->uri->segment(3),
            );
        }
        else {

            $result = $cu->lookupLimitedAll($start_page, $stop_page)->result();
            $result_rows = $cu->lookupTotalAll();
            $my_title = "Data " . $objName;
        }

        $listView = $cu->getListedFieldsView();
        $listFields = $cu->getFields();


        $data = array(
            "mode" => "viewData",
            "data" => $result,
            "header" => $listView,
            "mytitle" => $my_title,
            "total_rows" => $result_rows,
            "per_page" => $per_page,
            "position" => $start_page,
            "fields" => $listFields,
            "action" => $action,
            "search" => $search,
            "kategori" => $dropdown_data,
            "menuLeft" => callMenuLeft(),
        );
        $this->load->view('pages', $data);
    }

    function edit()
    {

        $cu = New $this->model();
        $result = $cu->lookupByID($this->uri->segment(4))->result();

        $listFields = $cu->getFields();
        $listFieldsForm = $cu->getListedFieldsForm();
        $listFieldsHidden = $cu->getListedFieldsHidden();

        $referer = base_url() . get_class($this) . "/view/1";
        $action = array(
            "submit" => "Simpan Perubahan",
            "button" => array("Cancel" => $referer),
        );
        $data = array(
            "mode" => "modalForm",
            "data" => $result,
            "header" => $listFieldsForm,
            "mytitle" => "Editor Data Cabang, " . $result[0]->nama,
            "fields" => $listFields,
            "action" => $action,
            "action_link" => base_url() . $this->uri->segment(1) . "/editProses/" . $this->uri->segment(3),
            "hidden" => $listFieldsHidden,
        );
        $this->load->view('pages', $data);
    }

    function add()
    {

        $cb = New MdlCabang();
        $cu = New $this->model();

        $arrUserJenisParent = userJenisParent();
        $arrCabang = $cb->lookupLimitedActive()->result();
        $dropdown_data['cabang_id'] = $arrCabang;
        $dropdown_data['jenis'] = $arrUserJenisParent;

        $result = array();

        $listFields = $cu->getFields();
        $listFieldsForm = $cu->getListedFieldsForm();

        $referer = base_url() . get_class($this) . "/view/1";
        $action = array(
            "submit" => "Simpan",
            "button" => array("Cancel" => $referer),
        );
        $data = array(
            "mode" => "modalForm",
            "data" => $result,
            "header" => $listFieldsForm,
            "mytitle" => "Tambah Data Cabang",
            "fields" => $listFields,
            "action" => $action,
            "action_link" => base_url() . get_class($this) . "/addProses/" . $this->uri->segment(3),
            "hidden" => '',
            "kategori" => $dropdown_data,
        );
        $this->load->view('pages', $data);
    }

    function history()
    {

        $cu = New $this->model();

        $per_page = 10;
//        $segment_page = $this->uri->total_segments();
//        $page = ($this->uri->segment($segment_page)) ? $this->uri->segment($segment_page)-1 : 0;
        $segment_page = $this->uri->segment(5);
        $page = null != $segment_page ? $segment_page - 1 : 0;
        $start_page = $page * $per_page;
        $stop_page = $per_page;
        $search = $cu->getSearch();


        $resultHistory = $cu->lookupHistory($start_page, $stop_page);
        $result_rows = $cu->lookupTotalHistoryAll();
        $listView = $cu->getListedFieldsView();
        $listFields = $cu->getFields();
        $listFieldsHidden = $cu->getListedFieldsHidden();
        $lookup_data = $cu->lookupByID($this->uri->segment(4))->result();

//echo ":: $start_page :: $stop_page :: $search :: $result_rows ::";

        $result_data_object = array();
        if (sizeof($resultHistory) > 0) {
            foreach ($resultHistory as $resultHistory_data) {

                $result_data = unserialize(base64_decode($resultHistory_data->new_content));
                foreach ($result_data as $result_data_1) {

                    $result_data_1_new = array_replace($result_data_1, (array)$resultHistory_data);
                    $result_data_object[] = (object)$result_data_1_new;
                }
            }
        }

        //  tambahan header
        $listView[] = "dtime";
        $listView[] = "oleh name";
        $listView[] = "keterangan";
        $data = array(
            "mode" => "modalTbl",
            "data" => $result_data_object,
            "header" => $listView,
            "mytitle" => 'History Data ' . get_class($this) . ", " . $lookup_data[0]->nama,
            "total_rows" => $result_rows,
            "per_page" => $per_page,
            "position" => $start_page,
            "fields" => $listFields,
            "action" => '',
            "search" => $search,
            "hidden" => $listFieldsHidden,
        );
        $this->load->view('pages', $data);
    }


    function undeleteProses()
    {

        $this->undeleteExecute();

        topRedirect($this->path_referer);
    }

    function deleteProses()
    {

        $this->deleteExecute();

        topRedirect($this->path_referer);
    }

    function editProses()
    {

        $this->editExecute();

        topRedirect($this->path_referer);
    }

    function addProses()
    {

        $this->addExecute();

        topRedirect($this->path_referer);
    }


} 