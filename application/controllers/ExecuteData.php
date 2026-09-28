<?php

class ExecuteData extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    function undeleteExecute()
    {

        $controller = $this->uri->segment(3);
        $classModel = "Mdl" . $controller;
        $ob = New $classModel;
        $indexFields = $ob->getIndexFields();
//        $listFields = $cu->getFields();


        $arrData = array("trash" => "0");
        if (sizeof($arrData) > 0) {

            $result = $ob->lookupByID($this->uri->segment(4))->result();
            $customers_nama = $result[0]->nama;
            if (sizeof($result) == 0) {
                die("<script>alert('Data $controller $customers_nama sudah tidak valid.')</script>");
            } elseif ($result[0]->trash == 0) {
                die("<script>alert('Data $controller $customers_nama sudah tidak valid.')</script>");
            } else {
                $this->db->trans_begin();


                $selectedId = $this->uri->segment(4);
                $where = array($indexFields => $selectedId);
                $ob->updateData($where, $arrData);


                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                // echo "<script>alert('Data $controller $customers_nama berhasil diaktifkan.')</script>";
                $arrSwal = array(
                    "type" => "success",
                    "html" => "data $customers_nama berhasil direstore",
                );
                echo swalAlert($arrSwal);
                topReload(10);
            }
        }
    }

    function deleteExecute()
    {

        $controller = $this->uri->segment(3);
        $classModel = "Mdl" . $controller;
        $ob = New $classModel;
        $indexFields = $ob->getIndexFields();
//        $listFields = $cu->getFields();


        $arrData = array("trash" => "1");
        if (sizeof($arrData) > 0) {

            $result = $ob->lookupByID($this->uri->segment(4))->result();
            $customers_nama = $result[0]->nama;
            if (sizeof($result) == 0) {
                die("<script>alert('Data $controller $customers_nama sudah tidak valid.')</script>");
            } elseif ($result[0]->trash == 1) {
                die("<script>alert('Data $controller $customers_nama sudah tidak valid.')</script>");
            } else {
                $this->db->trans_begin();

                $selectedId = $this->uri->segment(4);
                $where = array($indexFields => $selectedId);
                $ob->updateData($where, $arrData);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                // echo "<script>alert('Data $controller $customers_nama berhasil dinonaktifkan.')</script>";
                $arrSwal = array(
                    "type" => "success",
                    "html" => "data $customers_nama berhasil dimasukan trash",
                );
                echo swalAlert($arrSwal);
                topReload(10);
            }
        }
    }

    function editExecute()
    {
        $controller = $this->uri->segment(3);
        $classModel = "Mdl" . $controller;
        $hi = New MdlHistoriData();
        $ob = New $classModel;
        $indexFields = $ob->getIndexFields();
        $listFields = $ob->getFields();
        $listFieldsForm = $ob->getListedFieldsForm();
        $listValidationsForm = $ob->getValidationRules();

        $result_old = $ob->lookupByID($this->input->post('id'))->result();

        $arrDataHistoryOld = array();
        $arrDataHistoryNew = array();
        if (sizeof($listFieldsForm) > 0) {
            $arrData = array();
            foreach ($listFieldsForm as $arrList) {
                if ($arrList != $indexFields) {
                    $kolom_label = $listFields[$arrList]['label'];
                    $kolom_tbl = $listFields[$arrList]['kolom'];
                    $kolom_isi = $this->input->post($listFields[$arrList]['kolom']);
                    if (array_key_exists($kolom_tbl, $listValidationsForm)) {

                        if (in_array("required", $listValidationsForm[$kolom_tbl])) {
                            if (strlen($kolom_isi) < 1) {
                                die("<script>alert('$kolom_label harus diisi.')</script>");
                            }
                        }
                        if (in_array("numberOnly", $listValidationsForm[$kolom_tbl])) {
                            if (!is_numeric($kolom_isi)) {
                                die("<script>alert('$kolom_label hanya bisa diisi angka.')</script>");
                            }
                        }
                    }

                    $arrData[$kolom_tbl] = $kolom_isi;
//                    $arrDataHistoryOld[$kolom_tbl] = $result_old[0]->$kolom_tbl;
                }
            }

            foreach ($result_old as $result_old_data) {
                $arrDataHistoryOld[] = ((array)$result_old_data);
                $arrDataHistoryNew[] = array_replace((array)$result_old_data, $arrData);
            }


            if (sizeof($arrData) > 0) {
//                print_r($arrData);
                $this->db->trans_begin();

                //  region insert ke tabel data asli-nya
                $selectedId = $this->input->post($indexFields);
                $where = array($indexFields => $selectedId);
                $ob->updateData($where, $arrData);
                //  endregion insert ke tabel data asli-nya

                //  region insert tabel history-nya
                $data_encode_old = base64_encode(serialize($arrDataHistoryOld)); //data baru hasil perubahan
                $data_encode_new = base64_encode(serialize($arrDataHistoryNew)); //data baru hasil perubahan
                $arrDataHistory = array(
                    "mdl_name"    => "Mdl" . $this->uri->segment(1),
                    "mdl_label"   => $this->uri->segment(1),
                    "old_content" => $data_encode_old,
                    "new_content" => $data_encode_new,
                    "oleh_id"     => "",
                    "oleh_name"   => "",
                    "label"       => "perubahan",
                    "orig_id"     => $this->input->post('id'),
                    "dtime"       => date(DATE_ATOM),
                );
                $hi->addData($arrDataHistory);
                //  endregion insert tabel history-nya

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                // echo "<script>alert('Perubahan data $controller berhasil dilakukan.')</script>";
                $arrSwal = array(
                    "type" => "success",
                    "html" => "perubahan data berhasil disimpan",
                );
                echo swalAlert($arrSwal);
                topReload(10);
            }
        }
    }

    function addExecute()
    {
        $controller = $this->uri->segment(3);
        $classModel = "Mdl" . $controller;
        $hi = New MdlHistoriData();
        $ob = New $classModel;
        $indexFields = $ob->getIndexFields();
        $listFields = $ob->getFields();
        $listFieldsForm = $ob->getListedFieldsForm();
        $listValidationsForm = $ob->getValidationRules();


        if (sizeof($listFieldsForm) > 0) {
            $arrData = array();
            foreach ($listFieldsForm as $arrList) {
                if ($arrList != $indexFields) {
                    $kolom_label = $listFields[$arrList]['label'];
                    $kolom_tbl = $listFields[$arrList]['kolom'];
                    $kolom_isi = $this->input->post($listFields[$arrList]['kolom']);
                    if (array_key_exists($kolom_tbl, $listValidationsForm)) {

                        if (in_array("required", $listValidationsForm[$kolom_tbl])) {
                            if (strlen($kolom_isi) < 1) {
                                die("<script>alert('$kolom_label harus diisi.')</script>");
                            }
                        }
                        if (in_array("numberOnly", $listValidationsForm[$kolom_tbl])) {
                            if (!is_numeric($kolom_isi)) {
                                die("<script>alert('$kolom_label hanya bisa diisi angka.')</script>");
                            }
                        }
                        //  yang diisi hanya single sementara nama
                        if (in_array("singleOnly", $listValidationsForm[$kolom_tbl])) {
                            $post_nama = $this->input->post('nama');
                            $result = $ob->lookupByCondition("nama='$post_nama'")->result();
                            if (sizeof($result) > 0) {
                                die("<script>alert('Duplikasi nama $post_nama. Silahkan diisi dengan nama lainnya.')</script>");
                            }
                        }
                    }
                    $arrData[$kolom_tbl] = $kolom_isi;
                }
            }

            if (sizeof($arrData) > 0) {
                $this->db->trans_begin();

                //  region insert ke tabel data asli-nya
                $ob->addData($arrData);
                $last_id = $this->db->insert_id();
//                echo $this->db->last_query() . "<br>" . $last_id;
                //  endregion insert ke tabel data asli-nya

                //  region insert tabel history-nya
                $data_encode_new = base64_encode(serialize($arrData)); //data baru hasil perubahan
                $arrDataHistory = array(
                    "mdl_name"    => "Mdl" . $this->uri->segment(1),
                    "mdl_label"   => $this->uri->segment(1),
                    "old_content" => "",
                    "new_content" => $data_encode_new,
                    "oleh_id"     => "",
                    "oleh_name"   => "",
                    "label"       => "penambahan",
                    "orig_id"     => $last_id,
                    "dtime"       => date(DATE_ATOM),
                );
                $hi->addData($arrDataHistory);
                //  endregion insert tabel history-nya

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                // echo "<script>alert('Penambahan data $controller berhasil dilakukan.')</script>";
                $arrSwal = array(
                    "type" => "success",
                    "html" => "Penambahan data $controller berhasil",
                );
                echo swalAlert($arrSwal);
                topReload(10);
            }
        }
    }

    function settingExecute()
    {

        $controller = $this->uri->segment(1);
        $classModel = "Mdl" . $controller;
        $hi = New MdlHistoriData();
        $ob = New $classModel;
        $indexFields = $ob->getIndexFields();
//        $listFields = $ob->getFields();
////        $listFieldsForm = $ob->getListedFieldsForm();
////        $listValidationsForm = $ob->getValidationRules();
//
        $segment_uri_array = $this->uri->segment_array();
        $segment_total = $this->uri->total_segments();
        $kolom = $this->input->get('j');
        $arrData = array(
            $kolom => $this->input->get('v'),
        );


        $this->db->trans_begin();


        //  region insert ke tabel data asli-nya
        $selectedId = $segment_uri_array[$segment_total];
        $result_old = $ob->lookupByID($selectedId)->result();

        foreach ($result_old as $result_old_data) {
            $arrDataHistoryOld[] = ((array)$result_old_data);
            $arrDataHistoryNew[] = array_replace((array)$result_old_data, $arrData);
        }


        $where = array($indexFields => $selectedId);
        $ob->updateData($where, $arrData);

        //  region insert tabel history-nya
        $data_encode_old = base64_encode(serialize($arrDataHistoryOld)); //data baru hasil perubahan
        $data_encode_new = base64_encode(serialize($arrDataHistoryNew)); //data baru hasil perubahan
        $arrDataHistory = array(
            "mdl_name"    => "Mdl" . $this->uri->segment(1),
            "mdl_label"   => $this->uri->segment(1),
            "old_content" => $data_encode_old,
            "new_content" => $data_encode_new,
            "oleh_id"     => "",
            "oleh_name"   => "",
            "label"       => "perubahan",
            "orig_id"     => $selectedId,
            "dtime"       => date(DATE_ATOM),
        );
        $hi->addData($arrDataHistory);

        //  endregion insert tabel history-nya

        //  endregion insert ke tabel data asli-nya

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

    }

    function aksesExecute()
    {

        $employee_id = $this->uri->segment_array()[$this->uri->total_segments()];

        $this->load->model('MdlMenu');
        $mn = New MdlMenu();

        $main_menu = $this->input->get('main');
        $sub_menu = $this->input->get('sub');


        $this->db->trans_begin();

        $arrCondition = array(
            "per_employee_id" => $employee_id,
            "menu_category"   => $main_menu,
            "menu_label"      => $sub_menu,
        );
        $result_lookup = $mn->lookupByCondition($arrCondition)->num_rows();

        if ($result_lookup > 0) {
            // hapus secara fisik
            $mn->deleteData($arrCondition);
        } else {
            // insert ke tbl
            $mn->addData($arrCondition);
        }


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function landingExecute()
    {

        $employee_id = $this->uri->segment_array()[$this->uri->total_segments()];

        $this->load->model('MdlMenu');
        $mn = New MdlMenu();

        $main_menu = $this->input->get('main');
        $sub_menu = $this->input->get('sub');


        $this->db->trans_begin();

        $arrCondition_cek = array(
            "per_employee_id" => $employee_id,
            "menu_category"   => $main_menu,
            //            "menu_label" => $sub_menu,
        );
        $arrCondition = array(
            "per_employee_id" => $employee_id,
            "menu_category"   => $main_menu,
            "menu_label"      => $sub_menu,
        );

        $tableName = $mn->getTableName1();
        $mn->setTableName($tableName);
        $result_lookup = $mn->lookupByCondition($arrCondition_cek)->num_rows();

        if ($result_lookup > 0) {

            $mn->deleteData($arrCondition_cek);
        }
        $mn->addData($arrCondition);


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function resetAksesExecute()
    {

    }

    function suspendExecute()
    {

        $segment_uri_array = $this->uri->segment_array();
        $selectedId = $segment_uri_array[$this->uri->total_segments()];


        $controller = $this->uri->segment(1);
        $classModel = "Mdl" . $controller;
        $ob = New $classModel;
        $indexFields = $ob->getIndexFields();
        $result_old = $ob->lookupByID($selectedId)->result();
        $suspend_value_new = $result_old[0]->suspend == 0 ? "1" : "0";

        $this->db->trans_begin();

        $arrData = array(
            "suspend" => $suspend_value_new,
        );
        $where = array($indexFields => $selectedId);
        $ob->updateData($where, $arrData);

//        cekHere("$suspend_value_new");
//mati_disini();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

    }
}

?>