<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ResetorTransaksi extends CI_Controller
{

    protected $selectTable = array(

        "fifo_avg",
        "fifo_valas_avg",
        "jurnal",
        "rugilaba",
        "neraca",
//ganti pakai relative
//        "rek_cache_persediaan_produk_fifo",
//        "rek_cache_persediaan_produk_fifo_trash",
//        "rek_cache_persediaan_produk_produksi_fifo",
//        "rek_cache_persediaan_produk_produksi_fifo_trash",
//        "rek_cache_persediaan_produk_supplies_fifo",
//        "rek_cache_persediaan_produk_supplies_fifo_trash",
//        "rek_cache_persediaan_produk_supplies_produksi_fifo",
//        "rek_cache_persediaan_produk_supplies_produksi_fifo_trash",
        //        "__rek_master__biaya",
//        "__rek_master__biaya_supplies",
//        "__rek_master__efisiensi_operasional",
//        "__rek_master__hpp",
//        "__rek_master__hutang_biaya",
//        "__rek_master__hutang_dagang",
//        "__rek_master__hutang_install",
//        "__rek_master__hutang_ke_konsumen",
//        "__rek_master__hutang_ongkir",
//        "__rek_master__kas",
//        "__rek_master__laba_ditahan",
//        "__rek_master__laba_rugi__perubahan_grade_produk",
//        "__rek_master__laba_rugi__return_produksi",
//        "__rek_master__laba_rugi__selisih_fifo_return_pembelian",
//        "__rek_master__ongkir_dibayar_konsumen",
//        "__rek_master__ongkos_install",
//        "__rek_master__penjualan",
//        "__rek_master__persediaan_produk",
//        "__rek_master__persediaan_supplies",
//        "__rek_master__piutang_dagang",
//        "__rek_master__ppn_in",
//        "__rek_master__ppn_in_jasa",
//        "__rek_master__ppn_out",
//        "__rek_master__return_penjualan",
//        "__rek_master__rugilaba",
//        "__rek_pembantu_customer__hutang_ke_konsumen",
//        "__rek_pembantu_customer__piutang_dagang",
//        "__rek_pembantu_customer__ppn_out",
//        "__rek_pembantu_ekspedisi__hutang_install",
//        "__rek_pembantu_ekspedisi__hutang_ongkir",
//        "__rek_pembantu_ekspedisi__ongkir_dibayar_konsumen",
//        "__rek_pembantu_produk__efisiensi_operasional",
//        "__rek_pembantu_produk__persediaan_produk",
//        "__rek_pembantu_subkas__kas",
//        "__rek_pembantu_supplier__hutang_biaya",
//        "__rek_pembantu_supplier__hutang_dagang",
//        "__rek_pembantu_supplier__ppn_in",
//        "__rek_pembantu_supplier__ppn_in_jasa",
//        "__rek_pembantu_supplies__persediaan_supplies",
//        "_rek_master",
//        "_rek_master_cache",
//        "_rek_pembantu_antarcabang",
//        "_rek_pembantu_antarcabang_cache",
//        "_rek_pembantu_customer",
//        "_rek_pembantu_customer_cache",
//        "_rek_pembantu_ekspedisi",
//        "_rek_pembantu_ekspedisi_cache",
//        "_rek_pembantu_produk",
//        "_rek_pembantu_produk_cache",
//        "_rek_pembantu_subkas",
//        "_rek_pembantu_subkas_cache",
//        "_rek_pembantu_supplier",
//        "_rek_pembantu_supplier_cache",
//        "_rek_pembantu_supplies",
//        "_rek_pembantu_supplies_cache",

        "stock_locker",
        "stock_locker_value",
        "stock_locker_cache",
        "stock_locker_mutasi",
        "stock_locker_transaksi",

        "aset_detail",
        "setup_depresiasi",

        "transaksi",
        "transaksi_credit_note",
        "transaksi_data",
        "transaksi_data_fields",
        "transaksi_data_garansi",
        "transaksi_data_items",
        "transaksi_data_items3_sum",
        "transaksi_data_registry",
        "transaksi_data_values",
        "transaksi_due_date",
        "transaksi_efaktur",
        "transaksi_element",
        "transaksi_extstep",
        "transaksi_fields",
        "transaksi_payment_antisource",
        "transaksi_payment_source",
        "transaksi_registry",
        "transaksi_shipment",
        "transaksi_sign",
        "transaksi_values",
        "transaksi_due_date",
        "transaksi_tmpcart",
        "transaksi_uang_muka_source",
        "transaksi_uang_muka_valas_source",
        "transaksi_values",

        "counters_custom_number",
        "counters_number",
        "dta_plafon_hutang_bank",

//        "project_komponen_biaya_details",
        "project_komponen_biaya_details_archive",
        "project_komponen_biaya_details_rab",
        "project_komponen_biaya_details_rab_sub",

        "project_komposisi",
        "project_komposisi_sub_workoder",
        "project_komposisi_workoder",
        "project_komposisi_workoder_mutasi",
        "project_produk",
        "project_sub_tasklist",
        "project_sub_tasklist_komposisi",
        "project_tasklist",
        "project_tasklist_log",
        "project_tim_work",
        "project_workorder_sub",
        "project_workorder",

        "stock_locker_diskon",
        "stock_locker_projek",
        "stock_locker_work_oder",
        "stock_locker_work_order_cache",
        "stock_locker_work_order_mutasi",
        "tmp_injector",
        "transaksi_payment_antisource_cache",
        "transaksi_payment_antisource_mutasi",
        "transaksi_uang_muka_source_cache",
        "transaksi_uang_muka_source_mutasi",
        "z_transaksi_kreditlimit_cache",
        "z_transaksi_kreditlimit_mutasi",
        "z_transaksi_laporan",
        "z_transaksi_pinjambarang_cache",
        "z_transaksi_pinjambarang_mutasi",
        "z_transaksi_produk_cache",
        "z_transaksi_produk_mutasi",
        "z_transaksi_project_cache",
        "z_transaksi_project_mutasi",
        "project_komposisi_paket",
        "project_access_member",
//        "project_komposisi_paket",

    );

    protected $filterSearch = "_rek";


    //region geter & setter
    public function getFilterSearch()
    {
        return $this->filterSearch;
    }


    public function setFilterSearch($filterSearch)
    {
        $this->filterSearch = $filterSearch;
    }

    public function getSelectTable()
    {
        return $this->selectTable;
    }

    public function setSelectTable($selectTable)
    {
        $this->selectTable = $selectTable;
    }
    //endregion

    public function __construct()
    {
        parent::__construct();

    }


    public function view(){
        $searchFilter = $this->getFilterSearch();
        $dbName = $this->db->database;
        $tableNameSearch=$this->db->query("SELECT t.TABLE_NAME AS myTables FROM INFORMATION_SCHEMA.TABLES AS t WHERE t.TABLE_SCHEMA = '$dbName' AND t.TABLE_NAME LIKE '%$searchFilter%' ")->result_array();
//        arrPrint($tableNameSearch);
        $arrTblNames = array();
        foreach ($tableNameSearch as $inx =>$tblnames){
            $arrTblNames[] = $tblnames["myTables"];
        }
        $tableDefault = $this->getSelectTable();
        $tableNames = array_merge($arrTblNames,$tableDefault);
        $hdnTablles = blobEncode($tableNames);
//        cekBiru($hdnTablles);
        $jmlCol = 5;
        $max_baris_perkolom = floor(sizeof($tableNames)/$jmlCol);
        $row = 0;
        $arrRow = array();
        $ppp = 0;
        foreach ( $tableNames as $x =>$item) {

            $row++;
            if (($row >= 1) && ($row <= $max_baris_perkolom)){
                $arrRow[0][$x] = $item;
            }
            elseif (($row >= $max_baris_perkolom) && ($row <=  $max_baris_perkolom * 2)){
                $arrRow[1][$x] = $item;
            }
            elseif (($row >= $max_baris_perkolom) && ($row<= $max_baris_perkolom * 3)){
                $arrRow[2][$x] = $item;
            }
            elseif (($row >= $max_baris_perkolom) && ($row <= $max_baris_perkolom * 4)){
                $arrRow[3][$x] = $item;
            }
            elseif (($row >= $max_baris_perkolom) && ($row <= $max_baris_perkolom * 5)){
                $arrRow[4][$x] = $item;
            }
            else{
                $arrRow[5][$x] = $item;
            }
        }

        $contensT = "<table>";
        $contensT .="<tr>";
        foreach ($arrRow as $y => $yData){
            $contensT .="<td style='padding: 2px;'>";
//            $contens .= "<div class=''>Pilih kategori</div>";
            $contensT .= "<div class='funkyradio'>";
            foreach ($yData as $yID => $yTable){
                $ytableStr = substr($yTable,0,20);
                $contensT .= "<div class='funkyradio-success'>
            <input type='checkbox' name='table_name[]' id='checkbox_$yID' value='$yID' checked/>
            <label for='checkbox_$yID' class='no-margin no-padding' title='$yTable'>$ytableStr</label>
        </div>";
            }
            $contensT .= "</div>";
            $contensT .="</td>";
        }

        $contensT .="</tr>";
        $contensT .= "</table>";
        $contensT .= "<input type='hidden' name='tables_names' value='$hdnTablles'> ";

        $btnSubmit = "<button  type='submit' class='btn btn-danger btn-block' id='btn-submit'>Reset transaction tables!</button>";
        $action = base_url().$this->uri->segment(1)."/doReset";

        $contens = "<form id='myForm' name='form' method='post' action='$action' target='result'>";
        $contens .=$contensT;
        $contens .="<div class='' style='margin-top: 10px;'>$btnSubmit</div>";
        $contens .= "</form>";
        $showPogress = base_url() ."public/images/sys/loader-100.gif";
        $contens .="<script>
                    $(document).on('click', '#btn-submit', function(e) {
                        e.preventDefault();
                        swal({
                            type:'warning',
                            title: 'Reset transactional tables',
                            input: 'checkbox',
                            inputValue: 0,
                            inputPlaceholder: ' YES, Reset anyway',
                            html :'This action will empty transaction tables and can not be undone. Are you sure?',
                            confirmButtonText: 'Continue',
                            inputValidator: function (result) {
                                return new Promise(function (resolve, reject) {
                                    if (result) {
                                        resolve();
                                    } else {
                                        reject('Tic untuk melanjutkan');
                                    }
                                })
                            }
                        }).then(function (result) {
                            swal({
//                            type:'warning',
                            imageUrl:'$showPogress',
                            html: ' <br>Truncating transactional tables<br>Please wait ... ... ,<br>',
                            showConfirmButton : false,
                             allowOutsideClick : false,
                            });
                            $('#myForm').submit();
                        });
                    });
                    </script>";

        $data = array(
            "mode"                => $this->uri->segment(2),
            "contens" => $contens,
            "title" => "RESET TRANSAKSIONAL"
        );
        $this->load->view('reset_transaksi', $data);
        $this->session->errMsg = "";
    }

    public function doReset(){

//        $tableDef = $this->getSelectTable();
        $tableDef = isset($_POST["tables_names"])?blobDecode($_POST["tables_names"]):"";

        $tableSelect = isset($_POST['table_name'])?$_POST['table_name']:array();
//        arrPrint($tableDef);
//        die();
        if(sizeof($tableSelect)>0){

            foreach ($tableSelect as $x => $yID){
                if(array_key_exists($yID,$tableDef)){
                    $tableName = $tableDef[$yID];
                    $this->db->truncate($tableName);
//                cekMerah($this->db->last_query());
                }
            }
//        arrPrint($tableDef);
//        die();
            echo "<script>top.location.reload();</script>";
        }else{
            echo "<script>alert('undefined table names');</script>";
            echo "<script>top.location.reload();</script>";
        }
    }

    public function cekBelumReset(){

        $dbName = $this->db->database;
        $tableNameSearch=$this->db->query("SELECT t.TABLE_NAME AS myTables FROM INFORMATION_SCHEMA.TABLES AS t WHERE t.TABLE_SCHEMA = '$dbName'")->result_array();
            $arrTableName = array();
        foreach($tableNameSearch as $ky => $datas){
            $table = $datas['myTables'];
            $size_MB = $this->db->query("SELECT * FROM $table")->result();

            $arrTableName[$table]  = !empty($size_MB) ? count($size_MB) : 0;
        }
        arrPrint($arrTableName);

    }
}
