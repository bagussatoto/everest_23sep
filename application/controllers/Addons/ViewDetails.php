<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ViewDetails extends CI_Controller
{

    private $selectedId;

    public function getSelectedId()
    {
        return $this->selectedId;
    }

    public function setSelectedId($selectedId)
    {
        $this->selectedId = $selectedId;
    }

    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);


        $this->load->helper("he_stepping");
        $this->load->helper("he_access_right");
        $this->load->library("MobileDetect");
        $this->load->helper("he_session_replacer");
        $this->load->model("Mdls/MdlCurrency");
        $this->load->helper('he_angka');
        $this->load->model("Mdls/MdlMongoMother");
        // matiHere(__LINE__);
        $tmpJenis = $this->uri->segment(3);
//        $tmpJenis = isset($this->config->item("heTransaksi_ui")[$this->uri->segment(3)]['aliasMainTrans']) ? $this->config->item("heTransaksi_ui")[$this->uri->segment(3)]['aliasMainTrans'] : $this->uri->segment(3);
        if (strlen($tmpJenis) > 0) {
            $this->jenisTr = $tmpJenis;


            //            $membership = is_array($this->session->login['membership'])?$this->session->login['membership']:array();
            //            $steps=$this->config->item('heTransaksi_ui')[$this->jenisTr]['steps'];
            //            $jmlAllowed=0;
            //            if(sizeof($steps)>0){
            //                foreach($steps as $num=>$sSpec){
            //                    if(in_array($sSpec['userGroup'],$membership)){
            //                        $jmlAllowed++;
            //                    }
            //                }
            //            }
            //            if($jmlAllowed<1){
            //                //cekMerah("__ILLEGAL ACCESS ATTEMPT__");die();
            //            }

            //            //cekMerah("bikin jenisTR ". $this->jenisTr);
            //            $this->jenisTr = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['target'] : $tmpJenis;
            $this->jenisTrName = isset($this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label']) ? $this->config->item("heTransaksi_ui")[$this->jenisTr]['steps'][1]['label'] : "unnamed";

            $heTransaksi_ui = (null != $this->config->item("heTransaksi_ui")) ? $this->config->item("heTransaksi_ui") : array();
            if (sizeof($heTransaksi_ui) > 0) {
                $this->template = isset($heTransaksi_ui[$this->jenisTr]) ? base_url() . "template/" . $heTransaksi_ui[$this->jenisTr]['template'] . ".html" : "";
            }
            else {
                die("konfigurasi transaksi belum ditentukan");
            }
            //            $this->trConfig = (null != $this->config->item("heTransaksi_ui")[$this->jenisTr]) ? $this->config->item("heTransaksi_ui")[$this->jenisTr] : array();
            $this->trConfig = (isset($this->config->item("heTransaksi_ui")[$this->jenisTr])) ? $this->config->item("heTransaksi_ui")[$this->jenisTr] : array();
        }
        else {
            // die("trJenis required!");

        }
        // matiHere(__LINE__);
        // loadConfigUiModul();
        $this->load->model("CustomCounter");
        $this->load->model("MdlTransaksi");
        $this->tableInConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn'] : array();
        $this->tableInConfig_static = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['tableIn_static'] : array();
        $this->arrButtonAction = $this->config->item("button");

        $trd = new MdlTransaksi();
        $trd->addFilter("jenis_top='" . $this->jenisTr . "'");
        // matiHere(__LINE__);
        $this->dates = $trd->lookupDates();
        $this->dates['entries'][date("y-m-d")] = date("y-m-d");
        //        arrPrint($this->session->login);
        $this->accessList = alowedAccess($this->session->login['id']);
        // arrPrint($this->session->login['cabang_id']);
        $this->placeId = $this->session->login['cabang_id'];

    }

    public function index()
    {
        $ctrlName = $this->uri->segment(2);
        $no = $this->uri->segment(4);

        $tr = new MdlTransaksi();

        $tr->setFilters(array());
        $tr->addFilterJoin("transaksi_id=" . $no);
        $trTr = $tr->lookupJoined()->result();

        $purch = array();
        $nomer = array();
        $nomer_top = array();
        if (sizeof($trTr) > 0) {
            foreach ($trTr as $rows) {
                $purch[$rows->produk_id] = $rows->valid_qty;
                $nomer[$rows->id_master] = $rows->nomer;
                $nomer_top[$rows->id_master] = $rows->nomer_top;
            }
        }

        $request = array();

        if (sizeof($trTr) > 0) {
            $idMaster = $trTr[0]->id_master;
            $tr->setFilters(array());
            $tr->addFilter("transaksi_id=" . $idMaster);
            $trTrOrig = $tr->lookupJoined()->result();
            if (sizeof($trTrOrig) > 0) {
                foreach ($trTrOrig as $rows) {
                    $request[$rows->produk_id] = $rows->produk_ord_jml;
                }
            }
        }

        $items = array();
        $trIDs = array();
        $lastTRID = 0;
        $cabang2_id = array();
        $gudang2_id = array();
        foreach ($trTr as $row) {
            $this->jenisTr = $row->jenis_master;
            $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheckAppr']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheckAppr'] : array();
            $qtips = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['qtips']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['qtips'] : array();
            $cabang2_id[$row->produk_id] = $row->cabang2_id;
            $gudang2_id[$row->produk_id] = $row->gudang2_id;
            if (!array_key_exists($row->produk_id, $items)) {
                $items[$row->produk_id] = 0;
            }
            $items[$row->produk_id] += $row->valid_qty;
            if (!in_array($row->id_master, $trIDs)) {
                $trIDs[] = $row->id_master;
            }
            $lastTRID = $row->transaksi_id;
        }

        $arrProduk = array();
        $stockActive = array();
        $tmp = array();

//        foreach($trTr as $ky=>$rows){
//
//            $produk_id = $rows->produk_id;
//            $cabang2_nama = $rows->cabang2_nama;
//            $mdlName = $lockerConfig['mdlName'];
//            $this->load->model("Mdls/" . $mdlName);
//            $c = new $mdlName();
//            $c->addFilter("produk_id='$produk_id'");
//            $c->addFilter("state='active'");
//            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
//            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
//            $tmpC = $c->lookupAll($produk_id)->result();
//
//            if(!isset($stockActive[$produk_id])){
//                $stockActive[$produk_id]=0;
//            }
//
//            if (sizeof($tmpC) > 0) {
//                foreach ($tmpC as $row) {
//                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
//                    $nama = $row->nama;
//                    $jml_now = $row->jumlah;//jumlah dari stocklocker
//                    $stockActive[$produk_id] = $row->jumlah;
//                }
//            }
//
//            $header['cabang2_nama'] = $cabang2_nama;
//            $header['cabang_nama'] = $this->session->login['cabang_nama'];
//            $header['nomer'] = $rows->nomer;
//            $header['nomer_top'] = $rows->nomer_top;
//
//            $tmp['nama'] = htmlspecialchars_decode($rows->produk_nama);
//            $tmp['valid_qty'] = $rows->valid_qty;
//            $tmp['stock'] = $stockActive[$produk_id];
//            $tmp['stockNeed'] = $request[$produk_id];
//
//            $arrProduk[$produk_id] = $tmp;
//        }

        foreach ($trTrOrig as $ky => $rows) {

            $produk_id = $rows->produk_id;
            $cabang2_nama = $rows->cabang_nama;
            $mdlName = $lockerConfig['mdlName'];
            $this->load->model("Mdls/" . $mdlName);
            $c = new $mdlName();
            $c->addFilter("produk_id='$produk_id'");
            $c->addFilter("state='active'");
            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
            $tmpC = $c->lookupAll($produk_id)->result();

            if (!isset($stockActive[$produk_id])) {
                $stockActive[$produk_id] = 0;
            }

            if (sizeof($tmpC) > 0) {
                foreach ($tmpC as $row) {
                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
                    $nama = $row->nama;
                    $jml_now = $row->jumlah;//jumlah dari stocklocker
                    $stockActive[$produk_id] = $row->jumlah;
                }
            }

            $header['cabang2_nama'] = $cabang2_nama;
            $header['cabang_nama'] = $this->session->login['cabang_nama'];
            $header['nomer'] = isset($nomer[$rows->id_master]) ? $nomer[$rows->id_master] : "";
            $header['nomer_top'] = isset($nomer_top[$rows->id_master]) ? $nomer_top[$rows->id_master] : "";

            $tmp['nama'] = htmlspecialchars_decode($rows->produk_nama);
            $tmp['valid_qty'] = isset($purch[$produk_id]) ? $purch[$produk_id] : 0;
            $tmp['stock'] = $stockActive[$produk_id];
            $tmp['stockNeed'] = $request[$produk_id];

            $arrProduk[$produk_id] = $tmp;
        }

        $strContent = "";

        if (sizeof($arrProduk) > 0) {
            $strContent .= "<div class='card'>";

            $strContent .= "<span class='text-bold text-uppercase'>REQUEST STOCK FROM</span>";
            $strContent .= "<span class='clearfix'>&nbsp;</span>";
            $strContent .= "<table class=''>";
            $strContent .= "<thead>";
            $strContent .= "<tr>";
            $strContent .= "<th>";
            $strContent .= "Cabang";
            $strContent .= "</th>";
            $strContent .= "<th>";
            $strContent .= " : ";
            $strContent .= "</th>";
            $strContent .= "<th>";
            $strContent .= $header['cabang2_nama'];
            $strContent .= "</th>";
            $strContent .= "</tr>";

            $strContent .= "<tr>";
            $strContent .= "<th>";
            $strContent .= "Supplies Request No";
            $strContent .= "</th>";
            $strContent .= "<th>";
            $strContent .= " : ";
            $strContent .= "</th>";
            $strContent .= "<th>";
            $strContent .= formatField('nomer_top', $header['nomer_top']);
            $strContent .= "</th>";
            $strContent .= "</tr>";

            $strContent .= "<tr>";
            $strContent .= "<th>";
            $strContent .= "PRE PO Number";
            $strContent .= "</th>";
            $strContent .= "<th>";
            $strContent .= " : ";
            $strContent .= "</th>";
            $strContent .= "<th>";
            $strContent .= formatField('nomer', $header['nomer']);
            $strContent .= "</th>";
            $strContent .= "</tr>";

            $strContent .= "</thead>";
            $strContent .= "</table>";

            $strContent .= "<table class='table table-hover'>";
            $strContent .= "<thead>";
            $strContent .= "<tr>";
            foreach ($qtips as $nm => $lable) {
                $strContent .= "<th>";
                $strContent .= "<span class='text-bold text-capitalize'>$lable</span>";
                $strContent .= "</th>";
            }
            $strContent .= "</tr>";
            $strContent .= "</thead>";

            $strContent .= "<tbody>";

            foreach ($arrProduk as $pid => $row) {
                $bgColor = "text-bold text-red";
                if ($row['valid_qty'] == 0) {
                    $bgColor = "bg-gray text-white";
                }
                $strContent .= "<tr class='$bgColor'>";
                foreach ($qtips as $nm => $val) {
                    $strContent .= "<td>";
                    $strContent .= $row[$nm];
                    $strContent .= "</td>";
                }
                $strContent .= "</tr>";
            }

            $strContent .= "</tbody>";

            $strContent .= "</table>";
            $strContent .= "</div>";
            $strContent .= "<div>NOTE:<br><span class='meta'>produk yang berwarna merah adalah produk yang akan dipurchase.</span></div>";
        }else{

        }


        $data = array(
            "mode" => $this->uri->segment(3),
            "content" => $strContent,
            "arrTags" => array(),
        );
        $this->load->view('viewdetails', $data);

    }

    public function nomer()
    {

cekHere("LINE: " . __LINE__ . " | " . __FUNCTION__);
die("maintenance....Plese wait");
        $ctrlName = $this->uri->segment(2);
        // $tr = new MdlMongoMother();
        $no = $this->uri->segment(4);

        $tr = new MdlTransaksi();
        
        $tr->setFilters(array());
        $tr->addFilter("nomer=".$no);

        $trTr = $tr->lookupJoined();

        $purch=array();
        $nomer=array();
        $nomer_top=array();
        if(sizeof($trTr)>0){
            foreach($trTr as $rows){
                $purch[$rows->produk_id] = $rows->valid_qty;
                $nomer[$rows->id_master] = $rows->nomer;
                $nomer_top[$rows->id_master] = $rows->nomer_top;
            }
        }

        $request=array();

//        cekMerah( $this->db->last_query() );
//        arrPrintWebs($trTr);
//        cekOrange( $idMaster=$trTr[0]->id_master );
//        matiHere(__LINE__);


        if(sizeof($trTr)>0){
            $idMaster=$trTr[0]->id_master;

            $tr->setFilters(array());
            $tr->setJoinedFilter(array());
            $tr->addFilterJoin("transaksi_id=".$idMaster);
            $tr->addFilterJoin("valid_qty >= 0");

            $trTrOrig = $tr->lookupJoined();

            showLast_query("merah");
            cekMerah("idmaster: $idMaster");
            arrPrint($trTrOrig);
            matiHere(__LINE__);

            if(sizeof($trTrOrig)>0){
                foreach($trTrOrig as $rows){
                    $request[$rows->produk_id] = $rows;
                }
            }
        }

        showLast_query("kuning");
        matiHere(__LINE__);

//        $items = array();
//        $trIDs = array();
//        $lastTRID = 0;
//        $cabang2_id=array();
//        $gudang2_id=array();
//        foreach ($trTr as $row) {
//            $this->jenisTr = $row->jenis_master;
//            $lockerConfig = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheckAppr']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['lockerCheckAppr'] : array();
//            $qtips = isset($this->config->item('heTransaksi_ui')[$this->jenisTr]['qtips']) ? $this->config->item('heTransaksi_ui')[$this->jenisTr]['qtips'] : array();
//            $cabang2_id[$row->produk_id]=$row->cabang2_id;
//            $gudang2_id[$row->produk_id]=$row->gudang2_id;
//            if (!array_key_exists($row->produk_id, $items)) {
//                $items[$row->produk_id] = 0;
//            }
//            $items[$row->produk_id] += $row->valid_qty;
//            if (!in_array($row->id_master, $trIDs)) {
//                $trIDs[] = $row->id_master;
//            }
//            $lastTRID = $row->transaksi_id;
//        }

        $arrProduk = array();
        $stockActive=array();
        $tmp=array();

//        foreach($trTr as $ky=>$rows){
//
//            $produk_id = $rows->produk_id;
//            $cabang2_nama = $rows->cabang2_nama;
//            $mdlName = $lockerConfig['mdlName'];
//            $this->load->model("Mdls/" . $mdlName);
//            $c = new $mdlName();
//            $c->addFilter("produk_id='$produk_id'");
//            $c->addFilter("state='active'");
//            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
//            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
//            $tmpC = $c->lookupAll($produk_id)->result();
//
//            if(!isset($stockActive[$produk_id])){
//                $stockActive[$produk_id]=0;
//            }
//
//            if (sizeof($tmpC) > 0) {
//                foreach ($tmpC as $row) {
//                    $satuan = strlen($row->satuan) > 0 ? $row->satuan : "n/a";
//                    $nama = $row->nama;
//                    $jml_now = $row->jumlah;//jumlah dari stocklocker
//                    $stockActive[$produk_id] = $row->jumlah;
//                }
//            }
//
//            $header['cabang2_nama'] = $cabang2_nama;
//            $header['cabang_nama'] = $this->session->login['cabang_nama'];
//            $header['nomer'] = $rows->nomer;
//            $header['nomer_top'] = $rows->nomer_top;
//
//            $tmp['nama'] = htmlspecialchars_decode($rows->produk_nama);
//            $tmp['valid_qty'] = $rows->valid_qty;
//            $tmp['stock'] = $stockActive[$produk_id];
//            $tmp['stockNeed'] = $request[$produk_id];
//
//            $arrProduk[$produk_id] = $tmp;
//        }

        foreach($trTr as $ky=>$rows){
            $produk_id = $rows->produk_id;
            $cabang2_nama = $rows->cabang_nama;
            $tmp['nama'] = htmlspecialchars_decode($rows->produk_nama);
            $tmp['valid_qty'] = $rows->valid_qty;
            $tmp['produk_ord_jml'] = $rows->produk_ord_jml;
            $tmp['produk_ord_hrg'] = $rows->produk_ord_hrg;
            $tmp['produk_hrg_ori'] = $rows->produk_hrg_ori;
            $tmp['hpp'] = $rows->hpp;
            $tmp['sub_total'] = ($rows->produk_ord_jml*1)*($rows->produk_ord_hrg*1);
            $arrProduk[$produk_id] = $tmp;
        }

        $qtips = array(
            "nama" => 'NAMA',
            "produk_ord_jml" => 'QTY',
            "produk_ord_hrg" => 'HRG',
            "sub_total" => 'SUBTOTAL',
        );

        $strContent = "";

        if(sizeof($arrProduk)>0){
            $strContent .= "<div class='card'>";
//            $strContent .= "<span class='text-bold text-uppercase'>{label mau dikasi nama apa}</span>";
//            $strContent .= "<span class='clearfix'>&nbsp;</span>";
            $strContent .= "<table class='table table-hover'>";
            $strContent .= "<thead>";
            $strContent .= "<tr>";
            $strContent .= "<th>NO.</th>";
            foreach($qtips as $nm=>$val){
                $strContent .= "<th>";
                $strContent .= $val;
                $strContent .= "</th>";
            }

            $strContent .= "</tr>";
            $strContent .= "</thead>";

            $strContent .= "<tbody>";

            $grandTotal = 0;
            $no = 0;
            foreach($arrProduk as $pid=>$row){
                $grandTotal += $row['sub_total']*1;

                if($row['valid_qty']==0){
                    $bgColor="bg-success";
                }
                else{
                    $bgColor="bg-warning text-bold text-red";
                }

                $no++;
                $strContent .= "<tr class='$bgColor'>";
                $strContent .= "<td>$no</td>";
                foreach($qtips as $nm=>$val){
                    $strContent .= "<td>";
                    $strContent .= formatField($nm, $row[$nm]);
                    $strContent .= "</td>";
                }
                $strContent .= "</tr>";
            }
            $strContent .= "</tbody>";

            $strContent .= "<tfoot>";
            $strContent .= "<tr class=''>";
            $strContent .= "<td>-</td>";
            foreach($qtips as $nm=>$val){

                if($nm!='sub_total'){
                    $strContent .= "<td>";
                    $strContent .= "-";
                    $strContent .= "</td>";
                }
                else{
                    $strContent .= "<td style='font-size: 12px' class='text-bold'>";
                    $strContent .= formatField($nm, $grandTotal);
                    $strContent .= "</td>";
                }

            }

            $strContent .= "</tr>";
            $strContent .= "</tfoot>";

            $strContent .= "</table>";
            $strContent .= "</div>";

//            $strContent .= "<div>NOTE:<br><span class='meta'>{mau di kasi notes apa}</span></div>";

        }
        else{

        }

        $data = array(
            "mode" => $this->uri->segment(3),
            "content" => $strContent,
            "arrTags" => array(),
        );
        $this->load->view('viewdetails', $data);

    }

    public function nomer_NEW()
    {
        // arrPrint($this->uri->segment_array());
        $ctrlName = $this->uri->segment(2);
        $tr = new MdlMongoMother();
        $no = $this->uri->segment(4);

        $tr->addFilter(array("nomer"=>$no));
        $trTr = $tr->lookUpMainTransaksi();
        $idTrans = $trTr[0]->id;
        $jenis = $trTr[0]->jenis;
        $jenisMaster = $trTr[0]->jenis_master;
        $step = $trTr[0]->step_number;

//        $configLayout = $this->config->item("heTransaksi_layout")[$jenis];
        $configLayout = $this->config->item("heTransaksi_layout")[$jenisMaster];

        $srcFieldsValues = $configLayout["receiptNumFields"][$step]+array("subtotal"=>"subtotal");
        $headerFields = $configLayout["receiptDetailFields"][$step]+$srcFieldsValues;

        $reg = $tr->lookupRegistriesByMasterID($idTrans);

        $detilTrans = $tr->lookUpDetilTransaksi($idTrans);
        $regData =array();
        foreach($reg as $data){
            $param = $data->param;
            $values = blobDecode($data->values);
            $regData[$param]=$values;
        }
        // arrPrint($regData["items"]);
        $prodsValues=array();
        foreach($regData["items"] as $iData){
            // arrPrint($iData);
            $prodsValues_tmp = array();
            foreach($srcFieldsValues as $fKey =>$fLabel){
                $prodsValues_tmp[$fKey]=$iData[$fKey];
            }

            $prodsValues[$iData["id"]]=$prodsValues_tmp;
        }
        $produkItems=array();
        foreach($detilTrans as $dataItems){
            $tmp = array();
            foreach($configLayout['receiptDetailFields'][$step] as $fKey =>$fLabel){
                $tmp[$fKey]=$dataItems->$fKey;
            }
            $produkItems[$dataItems->produk_id]=$tmp;
        }

        $finalPoduk = array();
        foreach($produkItems as $ky=>$rows) {
            $finalPoduk[$ky]=$rows+$prodsValues[$ky];
        }

        $strContent = "";

        if(sizeof($finalPoduk)>0){
            $strContent .= "<div class='card'>";
            //            $strContent .= "<span class='text-bold text-uppercase'>{label mau dikasi nama apa}</span>";
            //            $strContent .= "<span class='clearfix'>&nbsp;</span>";
            $strContent .= "<table class='table table-hover'>";
            $strContent .= "<thead>";
            $strContent .= "<tr>";
            $strContent .= "<th>No</th>";
            foreach($headerFields as $nm=>$val){
                $strContent .= "<th>";
                $strContent .= $val;
                $strContent .= "</th>";
            }

            $strContent .= "</tr>";
            $strContent .= "</thead>";

            $strContent .= "<tbody>";

            $grandTotal = 0;
            $ii=0;
            foreach($finalPoduk as $pid=>$row){
                $ii++;
                $grandTotal += $row['subtotal']*1;

                // if($row['valid_qty']==0){
                //     $bgColor="bg-success";
                // }
                // else{
                    $bgColor="bg-warning text-bold text-red";
                // }

                $strContent .= "<tr class='$bgColor'>";
                $strContent .= "<td>$ii</td>";
                foreach($headerFields as $nm=>$val){
                    $strContent .= "<td>";
                    $strContent .= formatField($nm, $row[$nm]);
                    $strContent .= "</td>";
                }
                $strContent .= "</tr>";
            }
            $strContent .= "</tbody>";

            $strContent .= "<tfoot>";
            $strContent .= "<tr class=''>";
            $strContent .= "<td>";
            $strContent .= "-";//untuk nomer urut
            $strContent .= "</td>";
            foreach($headerFields as $nm=>$val){

                if($nm!='subtotal'){
                    $strContent .= "<td>";
                    $strContent .= "-";
                    $strContent .= "</td>";
                }
                else{
                    $strContent .= "<td style='font-size: 12px' class='text-bold'>";
                    $strContent .= formatField($nm, $grandTotal);
                    $strContent .= "</td>";
                }

            }

            $strContent .= "</tr>";
            $strContent .= "</tfoot>";

            $strContent .= "</table>";
            $strContent .= "</div>";

            //            $strContent .= "<div>NOTE:<br><span class='meta'>{mau di kasi notes apa}</span></div>";

        }
        else{

        }

        $data = array(
            "mode" => $this->uri->segment(3),
            "content" => $strContent,
            "arrTags" => array(),
        );
        $this->load->view('viewdetails', $data);

    }

    public function item_report(){
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlMongoMother");
        $tr = new MdlTransaksi();
        $addParam = blobDecode($_GET['params']);
        $cID = $addParam['customers_id'];
        // arrPRint($addParam);
        $jn = $this->uri->segment(6);

        $rel = $this->uri->segment(4);
        foreach ($addParam as $k => $v) {
            if ($k == "dtime") {
                $listed = explode("-", $v);
                if (sizeof($listed) > 2) {
                    $this->db->where("year(dtime)=$listed[0]");
                    $this->db->where("month(dtime)=$listed[1]");
                }
                else {
                    $this->db->where("year(dtime)=$listed[0]");
                }
                // arrPrint($listed);
            }
            else {
                $tr->addFilter("jenis='$jn'");
                $tr->addFilter("$k='$v'");
            }

        }
        // matiHere();
        // $title = $this->config->item("heTransaksi_ui")[$jn]['label'];
        //all SO termasuk trash4
        $tmp = $tr->lookupMainTransaksi()->result();
        // cekLime($this->db->last_query());
        // matiHere();
        $ids = array();
        $arrNoIndexReg = array();
        $customer = "";
        //auto re index registry
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $tmp_0) {
                $customer = $tmp_0->customers_nama;
                $regIndex = blobDecode($tmp_0->indexing_registry);
                if (isset($regIndex['main'])) {
                    $ids[] = $regIndex['main'];
                }
                else {
                    $arrNoIndexReg[] = $tmp_0->id;
                }

                // arrPrint($regIndex);
            }
        }
        if (sizeof($arrNoIndexReg) > 0) {
            $mong = new MdlMongoMother();
            $mong->setFilters(array());
            $mong->setFilters(array());
            $mong->setParam("transaksi_id");
            $mong->setInParam($arrNoIndexReg);
            $mong->setFields(array("id", "transaksi_id", "param", "values"));
            $mong->setTableName("transaksi_registry");
            $tReg = $mong->lookUpAll();
            $tRegID = array();
            if (sizeof($tReg) > 0) {
                foreach ($tReg as $tReg_0) {
                    $tRegID[$tReg_0['transaksi_id']][$tReg_0['param']] = $tReg_0['id'];
                    // arrPrint($regEntries);

                }
            }
            foreach ($arrNoIndexReg as $ii => $updID) {
                // $mongListUpadte['update']['main'][] = array(
                //     "where" => array("id" => $no,),
                //     "value" => $arrData,
                // );
                $mong->setTableName("transaksi");
                $valueRe = blobencode($tRegID[$updID]);
                $mong->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
                $tr->updateData(array("id" => "$updID"), array("indexing_registry" => "$valueRe"));
            }
        }

        //region lihat gerbang value dari registry
        if (sizeof($ids) > 0) {
            $m = new MdlMongoMother();
            $m->setFilters(array());
            $m->setParam("id");
            $m->setInParam($ids);
            $m->setFields(array("transaksi_id", "param", "values"));
            $m->setTableName("transaksi_registry");
            $reg = $m->lookUpAll();
            $regEntries = array();
            if (sizeof($reg) > 0) {
                foreach ($reg as $paramReg) {
                    $regEntries[$paramReg['transaksi_id']] = blobdecode($paramReg['values']);
                }
            }

        }
        //endregion

        //region pecah SO cancelled trash4='1'
        $arrAll = array();
        $data = array();

        foreach ($tmp as $tmp0) {
            $val = isset($regEntries[$tmp0->id]['nett1']) ? $regEntries[$tmp0->id]['nett1'] : 0;

            //reseter
            if (!isset($arrAll['harga'])) {
                $arrAll['harga'] = 0;
            }
            if (!isset($arrAll['nett'])) {
                $arrAll['nett'] = 0;
            }
            if (!isset($arrAll['total'])) {
                $arrAll['total'] = 0;
            }



            if ($tmp0->trash_4 == "0") {
                // $arrAll['harga'] += $val;
                $harga = $val;
                $harga_rej = 0;
            }
            else {
                $arrAll['total'] += $val;
                $harga = 0;
                $harga_rej = $val;
            }

            $data[$tmp0->id] = array(
                "dtime"=>$tmp0->fulldate,
                // "nomer_top"=>$tmp0->nomer_top,
                // "id"=>$tmp0->id,
                "nomer"=>$tmp0->nomer,
                "harga" =>$val,
                // "valid" =>$harga,
                "total" =>$harga_rej,
                "nett" =>$val-$harga_rej,
            );
            $sum = $val-$harga_rej;
            $arrAll['harga'] += $val;
            $arrAll['total'] += $harga_rej;
            $arrAll['nett'] += $sum;
        }
        $header = array("dtime"=>"date","nomer"=>"receipt","harga"=>"amount","total"=>" amount reject","nett"=>"netto");
        $dataTmp = array(
            "mode"             => "itemReport",
            "title"            =>"",
            "sub_title" =>"",
            "thisPage"         => "",
            "subTitle"         => "",
            "items" =>$data,
            "subtotal"=>$arrAll,
            "headerFields"=>$header,
        );
        $this->load->view("activityReports",$dataTmp);
    }

}