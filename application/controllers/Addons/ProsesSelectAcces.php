<?php


class prosesSelectAcces extends CI_Controller
{
    public function select()
    {
//        arrPrint($this->uri->segment_array());

        $this->load->helpers('he_access_right');
        $this->load->model("Mdls/MdlAccessRight");
        $m = new MdlAccessRight();
        $metode = $this->uri->segment(4);
        $data = blobdecode($_GET['el']);
        $availTrans = callAvailTransaction();
        $transaksiUI = $this->config->item("heTransaksi_ui");
        $selectedStep =$data['step'];
        $stepLabel = $data['stepLabel'];
        $mnCategory = $data['jenis'];
        $tr_label = $transaksiUI[$mnCategory]['label'];
        $selectedEmployee = $data['employeeID'];
        $m->setFilters(array());
        $m->addFilter("employee_id='$selectedEmployee'");
//        $m->addFilter("menu_category='$mnCategory'");
        $existMenu = $m->lookupAll();
        $hak_akses_lama = $existMenu->result();
        $this->db->trans_start();
        switch ($metode){
            case "add":
                $dataChild = array(
                    "menu_category" => $mnCategory,
                    "menu_label" => $tr_label,
                    "employee_id" => $selectedEmployee,
                    "author" => $this->session->login['id'],
//                        "cabang_id" => $this->session->login['cabang_id'],
                    "steps" => $selectedStep,
                    "steps_code" => $availTrans[$mnCategory][$selectedStep]['target'],
                    "steps_label" => $stepLabel,
//                        "group_name" => $group_name,
//                        "group_label" => $group_label,
                );
//                arrPrint($dataChild);
                $insertID = $m->addData($dataChild, $m->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                cekHitam($this->db->last_query());
                $this->session->errMsg = "Data contents have been saved";
//                matiHere();
                break;
            case "remove":
                $m->deleteData(array(
                    "employee_id"=>$selectedEmployee,
                    "menu_category" =>$mnCategory,
                    "steps" =>$selectedStep,
                    ));
//                matiHEre();
//                cekMerah($this->db->last_query());
                break;

        }
        // menulis ke tabel set_menu__history...
//        $m = new MdlAccessRight();
        $m->setFilters(array());
        $m->addFilter("employee_id='$selectedEmployee'");
        $existMenu = $m->lookupAll();
        showLast_query("hijau");
        $hak_akses_baru = $existMenu->result();

        $hak_akses_lama_blob = blobEncode($hak_akses_lama);
        $hak_akses_baru_blob = blobEncode($hak_akses_baru);


        $mdlHist = "MdlAccessRightHistory";
        $this->load->model("Mdls/$mdlHist");
        $m = new $mdlHist();
        $arrHistory = array(
            "orig_id" => $selectedEmployee,
            "oleh_id" => $this->session->login['id'],
            "oleh_name" => $this->session->login['nama'],
            "mdl_name" => $mdlHist,
            "old_content" => $hak_akses_lama_blob,
            "new_content" => $hak_akses_baru_blob,
        );
        $insertID = $m->addData($arrHistory, $m->getTableName()) or die(lgShowError("Gagal menulis history hak akses transaksi ", __FILE__));
//        showLast_query("hijau");
//        matiHEre();
        $this->db->trans_complete();
//        arrPrint($data);
//        matiHEre("uppdate broo langsung");


    }
}