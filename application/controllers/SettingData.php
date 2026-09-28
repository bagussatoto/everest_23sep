<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 06/04/18
 * Time: 14:54
 */

include_once "ExecuteData.php";

class SettingData extends ExecuteData
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("MdlEmployee");
        $this->load->model("MdlCabang");
        $this->load->model("MdlSettingData");
        $this->path = base_url();
        $this->path_referer = base_url() . get_class($this) . "/view/1";
    }

    function view()
    {
        $cu = New MdlSettingData();
        $cb = New MdlCabang();


        $per_page = 10;
        $segment_page = $this->uri->total_segments();
        $page = ($this->uri->segment($segment_page)) ? $this->uri->segment($segment_page) - 1 : 0;
        $start_page = $page * $per_page;
        $stop_page = $per_page;
        $search = $cu->getSearch();

        $arrUserJenisParent = userJenisParent();
        $arrCabang = $cb->lookupLimitedActive()->result();

        $dropdown_data['cabang_id'] = $arrCabang;
        $dropdown_data['jenis'] = $arrUserJenisParent;

//        $action = array(
//            "edit" => base_url() . get_class($this) . "/edit",
//            "delete" => base_url() . get_class($this) . "/deleteProses",
//            "history" => base_url() . get_class($this) . "/history",
//        );
        $action = array();

        if ($this->uri->segment(3) == "active") {
            $result = $cu->lookupLimitedActive($start_page, $stop_page)->result();
            $result_rows = $cu->lookupTotalActive();
            $my_title = "Pengaturan Employee " . $this->uri->segment(3);
        }
        elseif ($this->uri->segment(3) == "non_active") {
            $result = $cu->lookupLimitedNonActive($start_page, $stop_page)->result();
            $result_rows = $cu->lookupTotalNonActive();
            $my_title = "Pengaturan Employee " . $this->uri->segment(3);

            $action = array(
//                "edit" => base_url() . get_class($this) . "/edit",
                "undelete" => base_url() . get_class($this) . "/undeleteProses",
            );
        }
        else {
            $result = $cu->lookupLimitedAll($start_page, $stop_page)->result();
            $result_rows = $cu->lookupTotalAll();
            $my_title = "Pengaturan Employee";
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
            // "menu_utama" => array()
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