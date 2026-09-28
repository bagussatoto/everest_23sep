<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/7/2018
 * Time: 10:31 AM
 */
class SynCustomer extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function validateData(){
        $mdlParent = "MdlCustomer";
        $mdlChildAdd = "MdlCustomerAddress";
        $mdlChildBill = "MdlCustomerBillAddress";

        $defType = array(
            "jenis"=>"shipment",
            ""
        );
        $selectedFields = array(
            "id" =>"extern_id",
            "nama"=>"alias",
            "alamat_1" =>"alamat",
            "tlp_1"=>"tlp",
            "tlp_2" =>"tlp_2",
            "kelurahan"=>"kelurahan",
            "kecamatan"=>"kecamatan",
            "kabupaten"=>"kabupaten",
            "propinsi"=>"propinsi",
            "kode_pos"=>"kodepos",
        );
        $selectedFields2 = array(
            "extern_id" =>"id",
            "extern_type" =>"extern_type",
            "alias"=>"nama",
            "alamat" =>"alamat",
            "tlp"=>"tlp",
            "tlp_2" =>"tlp_2",
            "kelurahan"=>"kelurahan",
            "kecamatan"=>"kecamatan",
            "kabupaten"=>"kabupaten",
            "propinsi"=>"propinsi",
            "kodepos"=>"kodepos",
        );
//arrPrint($selectedFields);
        $this->load->model("Mdls/".$mdlParent);
        $this->load->model("Mdls/".$mdlChildAdd);
        $this->load->model("Mdls/".$mdlChildBill);
        $cus = new $mdlParent();
        $cus->addFilter("trash='0'");
        $tempCustomer = $cus->lookupAll()->result();
cekBiru($this->db->last_query());
        $dataCustomer = array();
        foreach($tempCustomer as $tempCust){
            $temp = array();
            foreach ($selectedFields as $kolom =>$alias){
                    $temp[$alias] =$tempCust->$kolom;
            }
            $dataCustomer[$tempCust->id]=$temp;
        }
arrPrint($dataCustomer);
        $add = new $mdlChildAdd();
        $cus->addFilter("trash='0'");
        $tempAdd = $add->lookupAll()->result();
//        cekHitam($this->db->last_query());
        $tempAddress = array();
        foreach($tempAdd as $tempCust){
            $temp1 = array();
            if(strlen($tempCust->alamat) > 5){
                foreach ($selectedFields2 as $kolom =>$alias){
                    $temp1[$tempCust->jenis][$kolom] =$tempCust->$kolom;
                }
            }

            $tempAddress[$tempCust->extern_id]=$temp1;
        }
        $customerAddress =array_filter($tempAddress);

        $bil = new $mdlChildBill();
        $cus->addFilter("trash='0'");
        $tempBill = $bil->lookupAll()->result();
        $tempBilling = array();
        foreach($tempBill as $tempBill){
            $temp1 = array();
            if(strlen($tempBill->alamat) > 5){
                foreach ($selectedFields2 as $kolom =>$alias){
                    $temp1[$tempBill->jenis][$kolom] =$tempBill->$kolom;
                }
            }

            $tempBilling[$tempBill->extern_id]=$temp1;
        }
        $this->db->trans_start();
        foreach($dataCustomer as $custID =>$temp){
           if(isset($customerAddress[$custID])){
//               cekHere("sudah ada alamat bro");
           }else{
               $add->addData($temp, $add->getTableName());
               CekKuning($this->db->last_query());
           }

           if(isset($tempBilling[$custID])){
               cekHere("sudah ada alamat bill $custID");
           }else{
               cekHere("belumada $custID");
               $bil->addData($temp, $bil->getTableName());
               cekHijau($this->db->last_query());
           }

        }
        matiHere("DONE syncrone data customer");
        $this->db->trans_complete();

//arrPrint($finalData);
    }


}