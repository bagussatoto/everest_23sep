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
            "npwp" =>"npwp",
            "email" =>"email",
            "no_ktp" =>"no_ktp"
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
            "npwp" =>"npwp",
            "no_ktp" =>"no_ktp",
            "email" =>"email",
        );
arrPrint($selectedFields);
        $this->load->model("Mdls/".$mdlParent);
       $this->load->model("Mdls/".$mdlChildAdd);
        $this->load->model("Mdls/".$mdlChildBill);
        $cus = new $mdlParent();
        $cus->addFilter("trash='0'");
        // $cus->addFilter("status='0'");
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
//arrPrint($dataCustomer);

        $bil = new $mdlChildBill();
        $addr = new $mdlChildAdd();
        $cus->addFilter("trash='0'");
        $tempBill = $bil->lookupAll()->result();
        $tempAddr = $addr->lookupAll()->result();

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

        $tempAdd = array();
        foreach($tempAddr as $tempBill){
            $temp1 = array();
            if(strlen($tempBill->alamat) > 5){
                foreach ($selectedFields2 as $kolom =>$alias){
                    $temp1[$tempBill->jenis][$kolom] =$tempBill->$kolom;
                }
            }

            $tempAdd[$tempBill->extern_id]=$temp1;
        }

        $this->db->trans_start();
        foreach($dataCustomer as $custID =>$temp){
// arrPrint($temp);
           if(isset($tempBilling[$custID])){
               cekHere("sudah ada alamat bill $custID " . $temp["alias"]);
           }else{
               cekLime("belumada $custID " . $temp["alias"]);
               $bil->addData($temp, $bil->getTableName());
               cekHijau($this->db->last_query());
           }

            if(isset($tempAdd[$custID])){
                cekHere("sudah ada alamat bill $custID");
            }else{
                cekHere("belumada $custID");
                $addr->addData($temp, $addr->getTableName());
                cekBiru($this->db->last_query());
            }

        }



        matiHere("DONE syncrone data customer " . __METHOD__ );
        $this->db->trans_complete();

//arrPrint($finalData);
    }


}