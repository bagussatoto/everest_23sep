<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 4:37 PM
 */
class MdlTransaksiModul extends MdlMother
{
//    protected $tableName = "transaksi";
    protected $tableName;
    protected $filters = array(
//        "transaksi.status='1'",
//        "transaksi.trash='0'",
//        "transaksi.link_id='0'",
//        "status='1'",
//        "trash='0'",
//        "link_id='0'",
    );
    protected $tableNames = array(
        "purchasing" => array(
            "tmp" => "purchasing_transaksi_tmpcart",
            "main" => "purchasing_transaksi",
            "detail" => "purchasing_transaksi_data",
            "mainEntryPoint" => "purchasing_transaksi_entry_point",
            "detailEntryPoint" => "purchasing_transaksi_data_entry_point",
            "sub_detail" => "purchasing_transaksi_data_items",
            "items3_sum" => "purchasing_transaksi_data_items3_sum",
            "mainFields" => "purchasing_transaksi_fields",
            "detailFields" => "purchasing_transaksi_data_fields",
            "mainValues" => "purchasing_transaksi_values",
            "detailValues" => "purchasing_transaksi_data_values",
//            "applets" => "purchasing_transaksi_applets",
            "sign" => "purchasing_transaksi_sign",
            "paymentSrc" => "purchasing_transaksi_payment_source",
            "paymentAntiSrc" => "purchasing_transaksi_payment_antisource",
            "registry" => "purchasing_transaksi_registry",
            "dataRegistry" => "purchasing_transaksi_data_registry",
            "elements" => "purchasing_transaksi_element",
            "extras" => "purchasing_transaksi_extstep",
            "dueDate" => "purchasing_transaksi_due_date",
            "uangMuka" => "purchasing_transaksi_uang_muka_source",
            "efaktur" => "purchasing_transaksi_efaktur",
            "garansi" => "purchasing_transaksi_data_garansi",
            "counter" => "purchasing_transaksi_counters",
            "mainMutasi" => "purchasing_transaksi_mutasi",
            "detailMutasi" => "purchasing_transaksi_data_mutasi",
        ),
        "sales" => array(
            "tmp" => "sales_transaksi_tmpcart",
            "main" => "sales_transaksi",
            "detail" => "sales_transaksi_data",
            "mainEntryPoint" => "sales_transaksi_entry_point",
            "detailEntryPoint" => "sales_transaksi_data_entry_point",
            "sub_detail" => "sales_transaksi_data_items",
            "items3_sum" => "sales_transaksi_data_items3_sum",
            "mainFields" => "sales_transaksi_fields",
            "detailFields" => "sales_transaksi_data_fields",
            "mainValues" => "sales_transaksi_values",
            "detailValues" => "sales_transaksi_data_values",
//            "applets" => "sales_transaksi_applets",
            "sign" => "sales_transaksi_sign",
            "paymentSrc" => "sales_transaksi_payment_source",
            "paymentAntiSrc" => "sales_transaksi_payment_antisource",
            "registry" => "sales_transaksi_registry",
            "dataRegistry" => "sales_transaksi_data_registry",
            "elements" => "sales_transaksi_element",
            "extras" => "sales_transaksi_extstep",
            "dueDate" => "sales_transaksi_due_date",
            "uangMuka" => "sales_transaksi_uang_muka_source",
            "efaktur" => "sales_transaksi_efaktur",
            "garansi" => "sales_transaksi_data_garansi",
            "counter" => "sales_transaksi_counters",
            "mainMutasi" => "sales_transaksi_mutasi",
            "detailMutasi" => "sales_transaksi_data_mutasi",
        ),
        "tax" => array(
            "tmp" => "tax_transaksi_tmpcart",
            "main" => "tax_transaksi",
            "detail" => "tax_transaksi_data",
            "mainEntryPoint" => "tax_transaksi_entry_point",
            "detailEntryPoint" => "tax_transaksi_data_entry_point",
            "sub_detail" => "tax_transaksi_data_items",
            "items3_sum" => "tax_transaksi_data_items3_sum",
            "mainFields" => "tax_transaksi_fields",
            "detailFields" => "tax_transaksi_data_fields",
            "mainValues" => "tax_transaksi_values",
            "detailValues" => "tax_transaksi_data_values",
//            "applets" => "tax_transaksi_applets",
            "sign" => "tax_transaksi_sign",
            "paymentSrc" => "tax_transaksi_payment_source",
            "paymentAntiSrc" => "tax_transaksi_payment_antisource",
            "registry" => "tax_transaksi_registry",
            "dataRegistry" => "tax_transaksi_data_registry",
            "elements" => "tax_transaksi_element",
            "extras" => "tax_transaksi_extstep",
            "dueDate" => "tax_transaksi_due_date",
            "uangMuka" => "tax_transaksi_uang_muka_source",
            "efaktur" => "tax_transaksi_efaktur",
            "garansi" => "tax_transaksi_data_garansi",
            "counter" => "tax_transaksi_counters",
            "mainMutasi" => "tax_transaksi_mutasi",
            "detailMutasi" => "tax_transaksi_data_mutasi",
        ),
        "project" => array(
            "tmp" => "project_transaksi_tmpcart",
            "main" => "project_transaksi",
            "detail" => "project_transaksi_data",
            "mainEntryPoint" => "project_transaksi_entry_point",
            "detailEntryPoint" => "project_transaksi_data_entry_point",
            "sub_detail" => "project_transaksi_data_items",
            "items3_sum" => "project_transaksi_data_items3_sum",
            "mainFields" => "project_transaksi_fields",
            "detailFields" => "project_transaksi_data_fields",
            "mainValues" => "project_transaksi_values",
            "detailValues" => "project_transaksi_data_values",
//            "applets" => "project_transaksi_applets",
            "sign" => "project_transaksi_sign",
            "paymentSrc" => "project_transaksi_payment_source",
            "paymentAntiSrc" => "project_transaksi_payment_antisource",
            "registry" => "project_transaksi_registry",
            "dataRegistry" => "project_transaksi_data_registry",
            "elements" => "project_transaksi_element",
            "extras" => "project_transaksi_extstep",
            "dueDate" => "project_transaksi_due_date",
            "uangMuka" => "project_transaksi_uang_muka_source",
            "efaktur" => "project_transaksi_efaktur",
            "garansi" => "project_transaksi_data_garansi",
            "counter" => "project_transaksi_counters",
            "mainMutasi" => "project_transaksi_mutasi",
            "detailMutasi" => "project_transaksi_data_mutasi",
        ),
        "pettycash" => array(
            "tmp" => "pettycash_transaksi_tmpcart",
            "main" => "pettycash_transaksi",
            "detail" => "pettycash_transaksi_data",
            "mainEntryPoint" => "pettycash_transaksi_entry_point",
            "detailEntryPoint" => "pettycash_transaksi_data_entry_point",
            "sub_detail" => "pettycash_transaksi_data_items",
            "items3_sum" => "pettycash_transaksi_data_items3_sum",
            "mainFields" => "pettycash_transaksi_fields",
            "detailFields" => "pettycash_transaksi_data_fields",
            "mainValues" => "pettycash_transaksi_values",
            "detailValues" => "pettycash_transaksi_data_values",
//            "applets" => "pettycash_transaksi_applets",
            "sign" => "pettycash_transaksi_sign",
            "paymentSrc" => "pettycash_transaksi_payment_source",
            "paymentAntiSrc" => "pettycash_transaksi_payment_antisource",
            "registry" => "pettycash_transaksi_registry",
            "dataRegistry" => "pettycash_transaksi_data_registry",
            "elements" => "pettycash_transaksi_element",
            "extras" => "pettycash_transaksi_extstep",
            "dueDate" => "pettycash_transaksi_due_date",
            "uangMuka" => "pettycash_transaksi_uang_muka_source",
            "efaktur" => "pettycash_transaksi_efaktur",
            "garansi" => "pettycash_transaksi_data_garansi",
            "counter" => "pettycash_transaksi_counters",
            "mainMutasi" => "pettycash_transaksi_mutasi",
            "detailMutasi" => "pettycash_transaksi_data_mutasi",
        ),
        "payment" => array(
            "tmp" => "payment_transaksi_tmpcart",
            "main" => "payment_transaksi",
            "detail" => "payment_transaksi_data",
            "mainEntryPoint" => "payment_transaksi_entry_point",
            "detailEntryPoint" => "payment_transaksi_data_entry_point",
            "sub_detail" => "payment_transaksi_data_items",
            "items3_sum" => "payment_transaksi_data_items3_sum",
            "mainFields" => "payment_transaksi_fields",
            "detailFields" => "payment_transaksi_data_fields",
            "mainValues" => "payment_transaksi_values",
            "detailValues" => "payment_transaksi_data_values",
//            "applets" => "payment_transaksi_applets",
            "sign" => "payment_transaksi_sign",
            "paymentSrc" => "payment_transaksi_payment_source",
            "paymentAntiSrc" => "payment_transaksi_payment_antisource",
            "registry" => "payment_transaksi_registry",
            "dataRegistry" => "payment_transaksi_data_registry",
            "elements" => "payment_transaksi_element",
            "extras" => "payment_transaksi_extstep",
            "dueDate" => "payment_transaksi_due_date",
            "uangMuka" => "payment_transaksi_uang_muka_source",
            "efaktur" => "payment_transaksi_efaktur",
            "garansi" => "payment_transaksi_data_garansi",
            "counter" => "payment_transaksi_counters",
            "mainMutasi" => "payment_transaksi_mutasi",
            "detailMutasi" => "payment_transaksi_data_mutasi",
        ),
        "opname" => array(
            "tmp" => "opname_transaksi_tmpcart",
            "main" => "opname_transaksi",
            "detail" => "opname_transaksi_data",
            "mainEntryPoint" => "opname_transaksi_entry_point",
            "detailEntryPoint" => "opname_transaksi_data_entry_point",
            "sub_detail" => "opname_transaksi_data_items",
            "items3_sum" => "opname_transaksi_data_items3_sum",
            "mainFields" => "opname_transaksi_fields",
            "detailFields" => "opname_transaksi_data_fields",
            "mainValues" => "opname_transaksi_values",
            "detailValues" => "opname_transaksi_data_values",
//            "applets" => "opname_transaksi_applets",
            "sign" => "opname_transaksi_sign",
            "paymentSrc" => "opname_transaksi_payment_source",
            "paymentAntiSrc" => "opname_transaksi_payment_antisource",
            "registry" => "opname_transaksi_registry",
            "dataRegistry" => "opname_transaksi_data_registry",
            "elements" => "opname_transaksi_element",
            "extras" => "opname_transaksi_extstep",
            "dueDate" => "opname_transaksi_due_date",
            "uangMuka" => "opname_transaksi_uang_muka_source",
            "efaktur" => "opname_transaksi_efaktur",
            "garansi" => "opname_transaksi_data_garansi",
            "counter" => "opname_transaksi_counters",
            "mainMutasi" => "opname_transaksi_mutasi",
            "detailMutasi" => "opname_transaksi_data_mutasi",
        ),
        "manufactur" => array(
            "tmp" => "manufactur_transaksi_tmpcart",
            "main" => "manufactur_transaksi",
            "detail" => "manufactur_transaksi_data",
            "mainEntryPoint" => "manufactur_transaksi_entry_point",
            "detailEntryPoint" => "manufactur_transaksi_data_entry_point",
            "sub_detail" => "manufactur_transaksi_data_items",
            "items3_sum" => "manufactur_transaksi_data_items3_sum",
            "mainFields" => "manufactur_transaksi_fields",
            "detailFields" => "manufactur_transaksi_data_fields",
            "mainValues" => "manufactur_transaksi_values",
            "detailValues" => "manufactur_transaksi_data_values",
//            "applets" => "manufactur_transaksi_applets",
            "sign" => "manufactur_transaksi_sign",
            "paymentSrc" => "manufactur_transaksi_payment_source",
            "paymentAntiSrc" => "manufactur_transaksi_payment_antisource",
            "registry" => "manufactur_transaksi_registry",
            "dataRegistry" => "manufactur_transaksi_data_registry",
            "elements" => "manufactur_transaksi_element",
            "extras" => "manufactur_transaksi_extstep",
            "dueDate" => "manufactur_transaksi_due_date",
            "uangMuka" => "manufactur_transaksi_uang_muka_source",
            "efaktur" => "manufactur_transaksi_efaktur",
            "garansi" => "manufactur_transaksi_data_garansi",
            "counter" => "manufactur_transaksi_counters",
            "mainMutasi" => "manufactur_transaksi_mutasi",
            "detailMutasi" => "manufactur_transaksi_data_mutasi",
        ),
        "expense" => array(
            "tmp" => "expense_transaksi_tmpcart",
            "main" => "expense_transaksi",
            "detail" => "expense_transaksi_data",
            "mainEntryPoint" => "expense_transaksi_entry_point",
            "detailEntryPoint" => "expense_transaksi_data_entry_point",
            "sub_detail" => "expense_transaksi_data_items",
            "items3_sum" => "expense_transaksi_data_items3_sum",
            "mainFields" => "expense_transaksi_fields",
            "detailFields" => "expense_transaksi_data_fields",
            "mainValues" => "expense_transaksi_values",
            "detailValues" => "expense_transaksi_data_values",
//            "applets" => "expense_transaksi_applets",
            "sign" => "expense_transaksi_sign",
            "paymentSrc" => "expense_transaksi_payment_source",
            "paymentAntiSrc" => "expense_transaksi_payment_antisource",
            "registry" => "expense_transaksi_registry",
            "dataRegistry" => "expense_transaksi_data_registry",
            "elements" => "expense_transaksi_element",
            "extras" => "expense_transaksi_extstep",
            "dueDate" => "expense_transaksi_due_date",
            "uangMuka" => "expense_transaksi_uang_muka_source",
            "efaktur" => "expense_transaksi_efaktur",
            "garansi" => "expense_transaksi_data_garansi",
            "counter" => "expense_transaksi_counters",
            "mainMutasi" => "expense_transaksi_mutasi",
            "detailMutasi" => "expense_transaksi_data_mutasi",
        ),
        "distribution" => array(
            "tmp" => "distribution_transaksi_tmpcart",
            "main" => "distribution_transaksi",
            "detail" => "distribution_transaksi_data",
            "mainEntryPoint" => "distribution_transaksi_entry_point",
            "detailEntryPoint" => "distribution_transaksi_data_entry_point",
            "sub_detail" => "distribution_transaksi_data_items",
            "items3_sum" => "distribution_transaksi_data_items3_sum",
            "mainFields" => "distribution_transaksi_fields",
            "detailFields" => "distribution_transaksi_data_fields",
            "mainValues" => "distribution_transaksi_values",
            "detailValues" => "distribution_transaksi_data_values",
//            "applets" => "distribution_transaksi_applets",
            "sign" => "distribution_transaksi_sign",
            "paymentSrc" => "distribution_transaksi_payment_source",
            "paymentAntiSrc" => "distribution_transaksi_payment_antisource",
            "registry" => "distribution_transaksi_registry",
            "dataRegistry" => "distribution_transaksi_data_registry",
            "elements" => "distribution_transaksi_element",
            "extras" => "distribution_transaksi_extstep",
            "dueDate" => "distribution_transaksi_due_date",
            "uangMuka" => "distribution_transaksi_uang_muka_source",
            "efaktur" => "distribution_transaksi_efaktur",
            "garansi" => "distribution_transaksi_data_garansi",
            "counter" => "distribution_transaksi_counters",
            "mainMutasi" => "distribution_transaksi_mutasi",
            "detailMutasi" => "distribution_transaksi_data_mutasi",
        ),
        "convert" => array(
            "tmp" => "convert_transaksi_tmpcart",
            "main" => "convert_transaksi",
            "detail" => "convert_transaksi_data",
            "mainEntryPoint" => "convert_transaksi_entry_point",
            "detailEntryPoint" => "convert_transaksi_data_entry_point",
            "sub_detail" => "convert_transaksi_data_items",
            "items3_sum" => "convert_transaksi_data_items3_sum",
            "mainFields" => "convert_transaksi_fields",
            "detailFields" => "convert_transaksi_data_fields",
            "mainValues" => "convert_transaksi_values",
            "detailValues" => "convert_transaksi_data_values",
//            "applets" => "convert_transaksi_applets",
            "sign" => "convert_transaksi_sign",
            "paymentSrc" => "convert_transaksi_payment_source",
            "paymentAntiSrc" => "convert_transaksi_payment_antisource",
            "registry" => "convert_transaksi_registry",
            "dataRegistry" => "convert_transaksi_data_registry",
            "elements" => "convert_transaksi_element",
            "extras" => "convert_transaksi_extstep",
            "dueDate" => "convert_transaksi_due_date",
            "uangMuka" => "convert_transaksi_uang_muka_source",
            "efaktur" => "convert_transaksi_efaktur",
            "garansi" => "convert_transaksi_data_garansi",
            "counter" => "convert_transaksi_counters",
            "mainMutasi" => "convert_transaksi_mutasi",
            "detailMutasi" => "convert_transaksi_data_mutasi",
        ),
        "cash" => array(
            "tmp" => "cash_transaksi_tmpcart",
            "main" => "cash_transaksi",
            "detail" => "cash_transaksi_data",
            "mainEntryPoint" => "cash_transaksi_entry_point",
            "detailEntryPoint" => "cash_transaksi_data_entry_point",
            "sub_detail" => "cash_transaksi_data_items",
            "items3_sum" => "cash_transaksi_data_items3_sum",
            "mainFields" => "cash_transaksi_fields",
            "detailFields" => "cash_transaksi_data_fields",
            "mainValues" => "cash_transaksi_values",
            "detailValues" => "cash_transaksi_data_values",
//            "applets" => "cash_transaksi_applets",
            "sign" => "cash_transaksi_sign",
            "paymentSrc" => "cash_transaksi_payment_source",
            "paymentAntiSrc" => "cash_transaksi_payment_antisource",
            "registry" => "cash_transaksi_registry",
            "dataRegistry" => "cash_transaksi_data_registry",
            "elements" => "cash_transaksi_element",
            "extras" => "cash_transaksi_extstep",
            "dueDate" => "cash_transaksi_due_date",
            "uangMuka" => "cash_transaksi_uang_muka_source",
            "efaktur" => "cash_transaksi_efaktur",
            "garansi" => "cash_transaksi_data_garansi",
            "counter" => "cash_transaksi_counters",
            "mainMutasi" => "cash_transaksi_mutasi",
            "detailMutasi" => "cash_transaksi_data_mutasi",
        ),
        "banking" => array(
            "tmp" => "banking_transaksi_tmpcart",
            "main" => "banking_transaksi",
            "detail" => "banking_transaksi_data",
            "mainEntryPoint" => "banking_transaksi_entry_point",
            "detailEntryPoint" => "banking_transaksi_data_entry_point",
            "sub_detail" => "banking_transaksi_data_items",
            "items3_sum" => "banking_transaksi_data_items3_sum",
            "mainFields" => "banking_transaksi_fields",
            "detailFields" => "banking_transaksi_data_fields",
            "mainValues" => "banking_transaksi_values",
            "detailValues" => "banking_transaksi_data_values",
//            "applets" => "banking_transaksi_applets",
            "sign" => "banking_transaksi_sign",
            "paymentSrc" => "banking_transaksi_payment_source",
            "paymentAntiSrc" => "banking_transaksi_payment_antisource",
            "registry" => "banking_transaksi_registry",
            "dataRegistry" => "banking_transaksi_data_registry",
            "elements" => "banking_transaksi_element",
            "extras" => "banking_transaksi_extstep",
            "dueDate" => "banking_transaksi_due_date",
            "uangMuka" => "banking_transaksi_uang_muka_source",
            "efaktur" => "banking_transaksi_efaktur",
            "garansi" => "banking_transaksi_data_garansi",
            "counter" => "banking_transaksi_counters",
            "mainMutasi" => "banking_transaksi_mutasi",
            "detailMutasi" => "banking_transaksi_data_mutasi",
        ),
        "asetmanagement" => array(
            "tmp" => "asetmanagement_transaksi_tmpcart",
            "main" => "asetmanagement_transaksi",
            "detail" => "asetmanagement_transaksi_data",
            "mainEntryPoint" => "asetmanagement_transaksi_entry_point",
            "detailEntryPoint" => "asetmanagement_transaksi_data_entry_point",
            "sub_detail" => "asetmanagement_transaksi_data_items",
            "items3_sum" => "asetmanagement_transaksi_data_items3_sum",
            "mainFields" => "asetmanagement_transaksi_fields",
            "detailFields" => "asetmanagement_transaksi_data_fields",
            "mainValues" => "asetmanagement_transaksi_values",
            "detailValues" => "asetmanagement_transaksi_data_values",
//            "applets" => "asetmanagement_transaksi_applets",
            "sign" => "asetmanagement_transaksi_sign",
            "paymentSrc" => "asetmanagement_transaksi_payment_source",
            "paymentAntiSrc" => "asetmanagement_transaksi_payment_antisource",
            "registry" => "asetmanagement_transaksi_registry",
            "dataRegistry" => "asetmanagement_transaksi_data_registry",
            "elements" => "asetmanagement_transaksi_element",
            "extras" => "asetmanagement_transaksi_extstep",
            "dueDate" => "asetmanagement_transaksi_due_date",
            "uangMuka" => "asetmanagement_transaksi_uang_muka_source",
            "efaktur" => "asetmanagement_transaksi_efaktur",
            "garansi" => "asetmanagement_transaksi_data_garansi",
            "counter" => "asetmanagement_transaksi_counters",
            "mainMutasi" => "asetmanagement_transaksi_mutasi",
            "detailMutasi" => "asetmanagement_transaksi_data_mutasi",
        ),
        "adjustment" => array(
            "tmp" => "adjustment_transaksi_tmpcart",
            "main" => "adjustment_transaksi",
            "detail" => "adjustment_transaksi_data",
            "mainEntryPoint" => "adjustment_transaksi_entry_point",
            "detailEntryPoint" => "adjustment_transaksi_data_entry_point",
            "sub_detail" => "adjustment_transaksi_data_items",
            "items3_sum" => "adjustment_transaksi_data_items3_sum",
            "mainFields" => "adjustment_transaksi_fields",
            "detailFields" => "adjustment_transaksi_data_fields",
            "mainValues" => "adjustment_transaksi_values",
            "detailValues" => "adjustment_transaksi_data_values",
//            "applets" => "adjustment_transaksi_applets",
            "sign" => "adjustment_transaksi_sign",
            "paymentSrc" => "adjustment_transaksi_payment_source",
            "paymentAntiSrc" => "adjustment_transaksi_payment_antisource",
            "registry" => "adjustment_transaksi_registry",
            "dataRegistry" => "adjustment_transaksi_data_registry",
            "elements" => "adjustment_transaksi_element",
            "extras" => "adjustment_transaksi_extstep",
            "dueDate" => "adjustment_transaksi_due_date",
            "uangMuka" => "adjustment_transaksi_uang_muka_source",
            "efaktur" => "adjustment_transaksi_efaktur",
            "garansi" => "adjustment_transaksi_data_garansi",
            "counter" => "adjustment_transaksi_counters",
            "mainMutasi" => "adjustment_transaksi_mutasi",
            "detailMutasi" => "adjustment_transaksi_data_mutasi",
        ),
    );
    protected $fields = array(
        "tmp" => array(
            "id",
            "jenis",
            "content",
            "content_intext",
            "date_created",
            "created_by",
            "date_modified",
            "modified_by",
            "cabang_id",
            "gudang_id",

        ),
        "main" => array(
            "id",
            "id_master",
            "id_top",
            "link_id",
            "ids_prev",
            // "ids_prev_intext",
            "ids_ref",
            "ids_ref_intext",
            "ids_his",
            "jenis_master",
            "jenis_top",
            "jenises_prev",
            // "jenises_prev_intext",
            "jenis",
            "jenis_label",
            "counters",
            // "counters_intext",
            "nomer_top",
            "nomers_prev",
            // "nomers_prev_intext",
            "nomer",
            "nomer2",
            "dtime",
            "fulldate",
            "oleh_id",
            "oleh_nama",
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "dtime_kirim",
            "cabang_id",
            "cabang_nama",
            "gudang_id",
            "gudang_nama",
            "pembayaran",
            "pembayaran_sys",
            "keterangan",
            "status",
            //            "orders_id",
            //            "orders_no",
            "bank_id",
            "bank_nama",
            "bank_rekening_id",
            "bank_rekening_nama",
            "bank_from",

            "jatuh_tempo",
            "dtime_jatuh_tempo",
            "transaksi_nilai",

//            "bank_id_from",
//            "bank_nama_from",
//            "bank_rekening_id_from",
//            "bank_rekening_nama_from",
            "transaksi_jenis",

            "cabang2_id",
            "cabang2_nama",

            "gudang2_id",
            "gudang2_nama",

            "nomer_surat_jalan",
            "tpl_alamat_id",
            "step_avail",
            "step_current",
            "step_number",
            "next_step_num",
            "next_step_code",
            "next_step_label",
            "next_group_code",

            "tail_number",
            "tail_code",

            //            "transaksi_nilai_tagihan",
            //            "transaksi_nilai_terbayar",
            //            "transaksi_nilai_sisa",

            "div_id",
            "div_nama",
            "status_4",
            "trash_4",
            "deskripsi",
            "transaksi_jenis2_label",
            "transaksi_jenis2_kode",
            "transaksi_jenis2_value",
            "transaksi_jenis2_value_ppn",
            "transaksi_jenis2",

            "cancel_dtime",
            "cancel_name",
            "cancel_id",

            "seller_id",
            "seller_nama",
            "top",
            "top_nama",
            "tos",
            "tos_nama",
            "referensi_id",
            "referensi_nomer",
            "referensi_jenis",
//            "indexing_details",
//            "indexing_sub_details",
            "indexing_items3_sum",
//            "indexing_registry",
            "cancel_packing_source_id",
            //-----------
//            "_company",
//            "_company_jenisTr",
//            "_company_jenisTrMaster",
//            "_company_stepCode",
//            "_company_supplierID",
//            "_company_customerID",
//            "_company_olehID",
//            "_company_sellerID",
//            "_company_cabangID",
//            "_company_cabang2ID",
//            "_company_gudangID",
//            "_company_gudang2ID",
//            "_company_modul",
//            "_company_subModul",
//            "_company_cabangID_jenisTr",
//            "_company_cabangID_jenisTrMaster",
//            "_company_cabangID_stepCode",
//            "_company_cabangID_supplierID",
//            "_company_cabangID_customerID",
//            "_company_cabangID_olehID",
//            "_company_cabangID_sellerID",
//            "_company_cabangID_cabangID",
//            "_company_cabangID_cabang2ID",
//            "_company_cabangID_gudangID",
//            "_company_cabangID_gudang2ID",
//            "_company_cabangID_modul",
//            "_company_cabangID_subModul",
//            "_company_cabangID_modul_jenisTr",
//            "_company_cabangID_modul_jenisTrMaster",
//            "_company_cabangID_modul_stepCode",
//            "_company_cabangID_modul_supplierID",
//            "_company_cabangID_modul_customerID",
//            "_company_cabangID_modul_olehID",
//            "_company_cabangID_modul_sellerID",
//            "_company_cabangID_modul_cabangID",
//            "_company_cabangID_modul_cabang2ID",
//            "_company_cabangID_modul_gudangID",
//            "_company_cabangID_modul_gudang2ID",
//            "_company_cabangID_modul_subModul",
//            "_company_cabangID_modul_subModul_jenisTr",
//            "_company_cabangID_modul_subModul_jenisTrMaster",
//            "_company_cabangID_modul_subModul_stepCode",
//            "_company_cabangID_modul_subModul_supplierID",
//            "_company_cabangID_modul_subModul_customerID",
//            "_company_cabangID_modul_subModul_olehID",
//            "_company_cabangID_modul_subModul_sellerID",
//            "_company_cabangID_modul_subModul_cabangID",
//            "_company_cabangID_modul_subModul_cabang2ID",
//            "_company_cabangID_modul_subModul_gudangID",
//            "_company_cabangID_modul_subModul_gudang2ID",
//            "_company_cabangID_modul_subModul_jenisTr_jenisTrMaster",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode",
//            "_company_cabangID_modul_subModul_jenisTr_supplierID",
//            "_company_cabangID_modul_subModul_jenisTr_customerID",
//            "_company_cabangID_modul_subModul_jenisTr_olehID",
//            "_company_cabangID_modul_subModul_jenisTr_sellerID",
//            "_company_cabangID_modul_subModul_jenisTr_cabangID",
//            "_company_cabangID_modul_subModul_jenisTr_cabang2ID",
//            "_company_cabangID_modul_subModul_jenisTr_gudangID",
//            "_company_cabangID_modul_subModul_jenisTr_gudang2ID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_jenisTrMaster",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_supplierID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_customerID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_olehID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_cabangID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_cabang2ID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_gudangID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_gudang2ID",
            "company_id",
            "modul",
            "subModul",
            //----
            "reference_jenis",
            "reference_id",
            "reference_nomer",
            "reference_id_top",
            "reference_nomer_top",
            "reference_jenis_top",
            //----
            "bom_id",
            "bom_nama",
            "fase_id",
            "fase_nama",
            "cli",
            //----
            "kode_produksi",
        ),
        "detail" => array(
            "id",
            "produk_jenis",
            "produk_id",
            "produk_nama",
            "produk_label",
            "produk_kode",
            "satuan",
            "produk_ord_jml",
            "produk_ord_hrg",
            "produk_ord_diskon",
            "produk_ord_diskon_persen",
//            "produk_ord_diterima",
//            "produk_ord_kurang",
            //            "produk_berat",
            "produk_berat_gross",
            //            "produk_volume",
            "produk_volume_gross",
            "transaksi_id",
            "trash",
            "status",
            "sub_step_number",
            "sub_step_current",
            "sub_step_avail",
            "next_substep_num",
            "next_substep_code",
            "next_substep_label",
            "next_subgroup_code",
            "valid_qty",
            "sub_tail_number",
            "sub_tail_code",
            "transaksi_master_id",
        ),
        "mainEntryPoint" => array(
            "id",
            "id_master",
            "id_top",
            "link_id",
            "ids_prev",
            // "ids_prev_intext",
            "ids_ref",
            "ids_ref_intext",
            "ids_his",
            "jenis_master",
            "jenis_top",
            "jenises_prev",
            // "jenises_prev_intext",
            "jenis",
            "jenis_label",
            "counters",
            // "counters_intext",
            "nomer_top",
            "nomers_prev",
            // "nomers_prev_intext",
            "nomer",
            "nomer2",
            "dtime",
            "fulldate",
            "oleh_id",
            "oleh_nama",
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "dtime_kirim",
            "cabang_id",
            "cabang_nama",
            "gudang_id",
            "gudang_nama",
            "pembayaran",
            "pembayaran_sys",
            "keterangan",
            "status",
            //            "orders_id",
            //            "orders_no",
            "bank_id",
            "bank_nama",
            "bank_rekening_id",
            "bank_rekening_nama",
            "bank_from",

            "jatuh_tempo",
            "dtime_jatuh_tempo",
            "transaksi_nilai",

            "bank_id_from",
            "bank_nama_from",
            "bank_rekening_id_from",
            "bank_rekening_nama_from",
            "transaksi_jenis",

            "cabang2_id",
            "cabang2_nama",

            "gudang2_id",
            "gudang2_nama",

            "nomer_surat_jalan",
            "tpl_alamat_id",
            "step_avail",
            "step_current",
            "step_number",
            "next_step_num",
            "next_step_code",
            "next_step_label",
            "next_group_code",

            "tail_number",
            "tail_code",

            //            "transaksi_nilai_tagihan",
            //            "transaksi_nilai_terbayar",
            //            "transaksi_nilai_sisa",

            "div_id",
            "div_nama",
            "status_4",
            "trash_4",
            "deskripsi",
            "transaksi_jenis2_label",
            "transaksi_jenis2_kode",
            "transaksi_jenis2_value",
            "transaksi_jenis2_value_ppn",
            "transaksi_jenis2",

            "cancel_dtime",
            "cancel_name",
            "cancel_id",

            "seller_id",
            "seller_nama",
            "top",
            "top_nama",
            "tos",
            "tos_nama",
            "referensi_id",
            "referensi_nomer",
            "referensi_jenis",
            "indexing_details",
            "indexing_sub_details",
            "indexing_items3_sum",
            "indexing_registry",
            "cancel_packing_source_id",
            //-----------
//            "_company",
//            "_company_jenisTr",
//            "_company_jenisTrMaster",
//            "_company_stepCode",
//            "_company_supplierID",
//            "_company_customerID",
//            "_company_olehID",
//            "_company_sellerID",
//            "_company_cabangID",
//            "_company_cabang2ID",
//            "_company_gudangID",
//            "_company_gudang2ID",
//            "_company_modul",
//            "_company_subModul",
//            "_company_cabangID_jenisTr",
//            "_company_cabangID_jenisTrMaster",
//            "_company_cabangID_stepCode",
//            "_company_cabangID_supplierID",
//            "_company_cabangID_customerID",
//            "_company_cabangID_olehID",
//            "_company_cabangID_sellerID",
//            "_company_cabangID_cabangID",
//            "_company_cabangID_cabang2ID",
//            "_company_cabangID_gudangID",
//            "_company_cabangID_gudang2ID",
//            "_company_cabangID_modul",
//            "_company_cabangID_subModul",
//            "_company_cabangID_modul_jenisTr",
//            "_company_cabangID_modul_jenisTrMaster",
//            "_company_cabangID_modul_stepCode",
//            "_company_cabangID_modul_supplierID",
//            "_company_cabangID_modul_customerID",
//            "_company_cabangID_modul_olehID",
//            "_company_cabangID_modul_sellerID",
//            "_company_cabangID_modul_cabangID",
//            "_company_cabangID_modul_cabang2ID",
//            "_company_cabangID_modul_gudangID",
//            "_company_cabangID_modul_gudang2ID",
//            "_company_cabangID_modul_subModul",
//            "_company_cabangID_modul_subModul_jenisTr",
//            "_company_cabangID_modul_subModul_jenisTrMaster",
//            "_company_cabangID_modul_subModul_stepCode",
//            "_company_cabangID_modul_subModul_supplierID",
//            "_company_cabangID_modul_subModul_customerID",
//            "_company_cabangID_modul_subModul_olehID",
//            "_company_cabangID_modul_subModul_sellerID",
//            "_company_cabangID_modul_subModul_cabangID",
//            "_company_cabangID_modul_subModul_cabang2ID",
//            "_company_cabangID_modul_subModul_gudangID",
//            "_company_cabangID_modul_subModul_gudang2ID",
//            "_company_cabangID_modul_subModul_jenisTr_jenisTrMaster",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode",
//            "_company_cabangID_modul_subModul_jenisTr_supplierID",
//            "_company_cabangID_modul_subModul_jenisTr_customerID",
//            "_company_cabangID_modul_subModul_jenisTr_olehID",
//            "_company_cabangID_modul_subModul_jenisTr_sellerID",
//            "_company_cabangID_modul_subModul_jenisTr_cabangID",
//            "_company_cabangID_modul_subModul_jenisTr_cabang2ID",
//            "_company_cabangID_modul_subModul_jenisTr_gudangID",
//            "_company_cabangID_modul_subModul_jenisTr_gudang2ID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_jenisTrMaster",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_supplierID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_customerID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_olehID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_cabangID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_cabang2ID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_gudangID",
//            "_company_cabangID_modul_subModul_jenisTr_stepCode_gudang2ID",
            "company_id",
            "modul",
            "subModul",
            //----
            "reference_jenis",
            "reference_id",
            "reference_nomer",
            "reference_id_top",
            "reference_nomer_top",
            "reference_jenis_top",
            //----
            "bom_id",
            "bom_nama",
            "fase_id",
            "fase_nama",
            "cli",
        ),
        "detailEntryPoint" => array(
            "id",

            "produk_jenis",
            "produk_id",
            "produk_nama",
            "produk_label",
            "produk_kode",
            "satuan",
            "produk_ord_jml",
            "produk_ord_hrg",
            "produk_ord_diskon",
            "produk_ord_diskon_persen",
            "produk_ord_diterima",
            "produk_ord_kurang",
            //            "produk_berat",
            "produk_berat_gross",
            //            "produk_volume",
            "produk_volume_gross",

            "transaksi_id",

            "trash",
            "status",
            "sub_step_number",
            "sub_step_current",
            "sub_step_avail",
            "next_substep_num",
            "next_substep_code",
            "next_substep_label",
            "next_subgroup_code",
            "valid_qty",

            "sub_tail_number",
            "sub_tail_code",

        ),
        "mainMutasi" => array(
            "id",
            "rek_id",
            "rekening",
            "extern_id",
            "extern_nama",
            "cabang_id",
            "cabang_nama",
            "gudang_id",
            "gudang_nama",
            "tgl",
            "bln",
            "thn",
            "dtime",
            "pembayaran",
            "periode",
            "jenis",
            "fulldate",
            "transaksi_id",
            "transaksi_no",
            "debet_awal",
            "debet",
            "debet_akhir",
            "kredit_awal",
            "kredit",
            "kredit_akhir",
            "qty_debet_awal",
            "qty_debet",
            "qty_debet_akhir",
            "qty_kredit_awal",
            "qty_kredit",
            "qty_kredit_akhir",
            "keterangan",
            "harga",
            "harga_avg",
            "harga_awal",
            "note",
            "harian_debet",
            "bulanan_debet",
            "tahunan_debet",
            "forever_debet",
            "harian_kredit",
            "bulanan_kredit",
            "tahunan_kredit",
            "forever_kredit",
            "urut",
            "urut_debet",
            "urut_kredit",
            "transaksi_jenis",
            "rekening_2",
            "rekening_alias",
            "reference_jenis",
            "reference_id",
            "reference_nomer",
            "reference_id_top",
            "reference_nomer_top",
            "reference_jenis_top",
            "company_id",
            "modul",
            "subModul",
            "jenisTr",
            "jenisTrMaster",
            "stepCode",
            "supplierID",
            "customerID",
            "olehID",
            "sellerID",
            "gen_counter",
            "extern2_id",
            "extern2_nama",
            "extern3_id",
            "extern3_nama",
            "extern4_id",
            "extern4_nama",
            "extern5_id",
            "extern5_nama",
            "saldo_debet",
            "saldo_kredit",
            "saldo_debet_periode",
            "saldo_kredit_periode",
            "saldo_qty_debet",
            "saldo_qty_kredit",
            "saldo_qty_debet_periode",
            "saldo_qty_kredit_periode",
            "r_move",
        ),
        "detailMutasi" => array(
            "id",
            "rek_id",
            "rekening",
            "extern_id",
            "extern_nama",
            "cabang_id",
            "cabang_nama",
            "gudang_id",
            "gudang_nama",
            "tgl",
            "bln",
            "thn",
            "dtime",
            "pembayaran",
            "periode",
            "jenis",
            "fulldate",
            "transaksi_id",
            "transaksi_no",
            "debet_awal",
            "debet",
            "debet_akhir",
            "kredit_awal",
            "kredit",
            "kredit_akhir",
            "qty_debet_awal",
            "qty_debet",
            "qty_debet_akhir",
            "qty_kredit_awal",
            "qty_kredit",
            "qty_kredit_akhir",
            "keterangan",
            "harga",
            "harga_avg",
            "harga_awal",
            "note",
            "harian_debet",
            "bulanan_debet",
            "tahunan_debet",
            "forever_debet",
            "harian_kredit",
            "bulanan_kredit",
            "tahunan_kredit",
            "forever_kredit",
            "urut",
            "urut_debet",
            "urut_kredit",
            "transaksi_jenis",
            "rekening_2",
            "rekening_alias",
            "reference_jenis",
            "reference_id",
            "reference_nomer",
            "reference_id_top",
            "reference_nomer_top",
            "reference_jenis_top",
            "company_id",
            "modul",
            "subModul",
            "jenisTr",
            "jenisTrMaster",
            "stepCode",
            "supplierID",
            "customerID",
            "olehID",
            "sellerID",
            "gen_counter",
            "extern2_id",
            "extern2_nama",
            "extern3_id",
            "extern3_nama",
            "extern4_id",
            "extern4_nama",
            "extern5_id",
            "extern5_nama",
            "saldo_debet",
            "saldo_kredit",
            "saldo_debet_periode",
            "saldo_kredit_periode",
            "saldo_qty_debet",
            "saldo_qty_kredit",
            "saldo_qty_debet_periode",
            "saldo_qty_kredit_periode",
            "r_move",
        ),
        "sub_detail" => array(
            "id",
            "produk_jenis",
            "produk_id",
            "produk_nama",
            "produk_label",
            "produk_kode",
            "satuan",
            "produk_ord_jml",
            "produk_ord_hrg",
            "produk_ord_diskon",
            "produk_ord_diskon_persen",
            "produk_ord_diterima",
            "produk_ord_kurang",
            "produk_berat_gross",
            "produk_volume_gross",
            "transaksi_id",
            "trash",
            "status",
            "sub_step_number",
            "sub_step_current",
            "sub_step_avail",
            "next_substep_num",
            "next_substep_code",
            "next_substep_label",
            "next_subgroup_code",
            "valid_qty",
            "sub_tail_number",
            "sub_tail_code",
            "cancel_id",
            "cancel_name",
            "cancel_qty",
            //            "batal_id",
            //            "batal_name",
            //            "batal_dtime",
        ),
        "items3_sum" => array(
            "id",
            "produk_jenis",
            "produk_id",
            "produk_nama",
            "produk_label",
            "produk_kode",
            "satuan",
            "produk_ord_jml",
            "produk_ord_hrg",
            "produk_ord_diskon",
            "produk_ord_diskon_persen",
            "produk_ord_diterima",
            "produk_ord_kurang",
            "produk_ord_stok",
            "produk_berat_gross",
            "produk_volume_gross",
            "transaksi_id",
            "trash",
            "status",
            "sub_step_number",
            "sub_step_current",
            "sub_step_avail",
            "next_substep_num",
            "next_substep_code",
            "next_substep_label",
            "next_subgroup_code",
            "valid_qty",
            "sub_tail_number",
            "sub_tail_code",
        ),
        "sign" => array(
            "id",
            "dtime",
            "step_number",
            "step_name",
            "group_code",
            "oleh_id",
            "oleh_nama",
            "keterangan",
        ),
        "mainFields" => array(
            "transaksi_id",
            "key",
            "value",
        ),
        "detailFields" => array(
            "transaksi_id",
            "produk_id",
            "key",
            "value",
        ),
        "mainValues" => array(
            "transaksi_id",
            "key",
            "value",
        ),
        "detailValues" => array(
            "transaksi_id",
            "produk_jenis",
            "produk_id",
            "key",
            "value",
        ),
//        "applets" => array(
//            "transaksi_id",
//            "mdl_name",
//            "key",
//            "label",
//            "description",
//
//        ),
        "elements" => array(
            "transaksi_id",
            "mdl_name",
            "key",
            "value",
            "name",
            "label",
            "contents",
            "contents_intext",


        ),
        "paymentSrc" => array(
            "id",
            "_key",
            "jenis",
            "target_jenis",
            "reference_jenis",
            "transaksi_id",
            "extern_id",
            "extern_nama",
            "nomer",
            "label",
            "tagihan",
            "terbayar",
            "sisa",
            "cabang_id",
            "cabang_nama",
            "oleh_id",
            "oleh_nama",
            "dtime",
            "fulldate",
            "valas_id",
            "valas_nama",
            "valas_nilai",
            "tagihan_valas",
            "terbayar_valas",
            "sisa_valas",
            "pph_23",
            "terbayar_pph23",
            "extern_label2",

            "dpp_ppn",
            "ppn",
            "ppn_approved",
            "ppn_sisa",
            "ppn_status",
            "extern_nilai2",
            "extern_date2",
            "extern2_id",
            "extern2_nama",
            "extern_jenis",
            "ppn_pph_faktor",
            "extern_nilai2",
            "extern_nilai3",
            "extern_nilai4",
            "extern_nilai5",
            "npwp",

            "payment_locked",
            "cash_account",
            "cash_account_nama",
            "project_id",
            "project_nama",
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
        ),
        "paymentAntiSrc" => array(
            "id",
            "_key",
            "jenis",
            "target_jenis",
            "reference_jenis",
            "transaksi_id",
            "extern_id",
            "extern_nama",
            "nomer",
            "label",
            "tagihan",
            "terbayar",
            "sisa",
            "cabang_id",
            "cabang_nama",
            "oleh_id",
            "oleh_nama",
            "dtime",
            "fulldate",
            "valas_id",
            "valas_nama",
            "valas_nilai",
            "tagihan_valas",
            "terbayar_valas",
            "sisa_valas",
            "pph_23",
            "terbayar_pph23",
            "extern_label2",
            "extern2_id",
            "extern2_nama",
        ),
        "extras" => array(
            "id",
            "master_id",
            "transaksi_id",
            "_key",
            "_label",
            "_value",
            "group_id",
            "state",
            "proposed_by",
            "proposed_dtime",
            "done_by",
            "done_dtime",
        ),
        "dueDate" => array(
            "id",
            "transaksi_id",
            "customers_id",
            "customers_nama",
            "cabang_id",
            "cabang_nama",
            "nomer",
            "dtime",
            "due_date",
            "oleh_nama",
            "oleh_id",
            "transaksi_nilai",
            "release_id",//id payment
            "keterangan",
            "status",//1 active
            "trash",
        ),
        "report" => array(
            "id",
            "id_master",
            "id_top",
            "nomer_top",
            "nomer",
            "dtime",
            "cabang_id",
            "cabang_nama",
            "cabang2_id",
            "cabang2_nama",
            "gudang_id",
            "gudang_nama",
            "oleh_id",
            "oleh_nama",
            "customers_id",
            "customers_nama",
            "suppliers_id",
            "suppliers_nama",
            "jenis",
            "jenis_master",
            "trash",
            "trash_4",
            "counters",
        ),
        "uangMuka" => array(
            "id",
            "_key",
            "jenis",
            "target_jenis",
            "reference_jenis",
            "transaksi_id",
            "extern_id",
            "extern_nama",
            "nomer",
            "note",
            "label",
            "tagihan",
            "terbayar",
            "sisa",
            "cabang_id",
            "cabang_nama",
            "oleh_id",
            "oleh_nama",
            "dtime",
            "fulldate",
            "valas_id",
            "valas_nama",
            "valas_nilai",
            "tagihan_valas",
            "terbayar_valas",
            "sisa_valas",
            "pph_23",
            "terbayar_pph23",
            "extern_label2",
            "ppn",
            "ppn_approved",
            "ppn_sisa",
            "ppn_status",
            "extern_nilai2",
            "extern_date2",
            //----
            "tagihan_ppn",
            "diskon_ppn",
            "dihapus_ppn",
            "returned_ppn",
            "terbayar_ppn",
            "sisa_ppn",
        ),
        "dataRegistry" => array(
            "transaksi_id",
            "main",
            "items",
            "items2",
            "items2_sum",
            "itemSrc",
            "itemSrc_sum",
            "items3",
            "items3_sum",
            "items4",
            "items4_sum",
            "items_noapprove",
            "rsltItems",
            "rsltItems2",
            "rsltItems3",
            "rsltItems3_sub",
            "tableIn_master",
            "tableIn_detail",
            "tableIn_detail2_sum",
            "tableIn_detail_rsltItems",
            "tableIn_detail_rsltItems2",
            "tableIn_master_values",
            "tableIn_detail_values",
            "tableIn_detail_values_rsltItems",
            "tableIn_detail_values_rsltItems2",
            "tableIn_detail_values2_sum",
            "rsltItems3_sub",
            "main_add_values",
            "main_add_fields",
            "main_elements",
            "main_inputs",
            "main_inputs_orig",
            "receiptDetailFields",
            "receiptSumFields",
            "receiptDetailFields2",
            "receiptDetailSrcFields",
            "receiptSumFields2",
            "jurnal_index",
            "postProcessor",
            "preProcessor",
            "revert",
            "items_komposisi",
            "jurnalItems",
            "componentsBuilder",
            "items5_sum",
            "items6_sum",
            "items7_sum",
            "items8_sum",
            "items9_sum",
            "items10_sum",
            "rsltItems3_sub",
            "rsltItems_revert",
            "rsltItems2_revert",
            "mainOriginal",
            "itemsOriginal",
        ),
        "garansi" => array(
            "transaksi_id",
            "produk_jenis",
            "produk_id",
            "produk_nama",
            "valid_qty",
            "produk_label",
            "produk_keterangan",
            "produk_folders",
            "produk_kode",
            "satuan",
            "trash",
            "status",
            "keterangan",
            "oleh_id",
            "oleh_nama",
            "cabang_id",
            "cabang_nama",
            "garansi_tarif",
            "garansi_nilai",
            "garansi_dtime",
            "customers_id",
            "customers_nama",
            "produk_ord_hrg",
            "produk_ord_ppn",
            "produk_ord_netto",
        ),
        "counter" => array(

            "_company",
            "_company_jenisTr",
            "_company_jenisTrMaster",
            "_company_stepCode",
            "_company_supplierID",
            "_company_customerID",
            "_company_olehID",
            "_company_sellerID",
            "_company_cabangID",
            "_company_cabang2ID",
            "_company_gudangID",
            "_company_gudang2ID",
            "_company_modul",
            "_company_subModul",
            "_company_cabangID_jenisTr",
            "_company_cabangID_jenisTrMaster",
            "_company_cabangID_stepCode",
            "_company_cabangID_supplierID",
            "_company_cabangID_customerID",
            "_company_cabangID_olehID",
            "_company_cabangID_sellerID",
            "_company_cabangID_cabangID",
            "_company_cabangID_cabang2ID",
            "_company_cabangID_gudangID",
            "_company_cabangID_gudang2ID",
            "_company_cabangID_modul",
            "_company_cabangID_subModul",
            "_company_cabangID_modul_jenisTr",
            "_company_cabangID_modul_jenisTrMaster",
            "_company_cabangID_modul_stepCode",
            "_company_cabangID_modul_supplierID",
            "_company_cabangID_modul_customerID",
            "_company_cabangID_modul_olehID",
            "_company_cabangID_modul_sellerID",
            "_company_cabangID_modul_cabangID",
            "_company_cabangID_modul_cabang2ID",
            "_company_cabangID_modul_gudangID",
            "_company_cabangID_modul_gudang2ID",
            "_company_cabangID_modul_subModul",
            "_company_cabangID_modul_subModul_jenisTr",
            "_company_cabangID_modul_subModul_jenisTrMaster",
            "_company_cabangID_modul_subModul_stepCode",
            "_company_cabangID_modul_subModul_supplierID",
            "_company_cabangID_modul_subModul_customerID",
            "_company_cabangID_modul_subModul_olehID",
            "_company_cabangID_modul_subModul_sellerID",
            "_company_cabangID_modul_subModul_cabangID",
            "_company_cabangID_modul_subModul_cabang2ID",
            "_company_cabangID_modul_subModul_gudangID",
            "_company_cabangID_modul_subModul_gudang2ID",
            "_company_cabangID_modul_subModul_jenisTr_jenisTrMaster",
            "_company_cabangID_modul_subModul_jenisTr_stepCode",
            "_company_cabangID_modul_subModul_jenisTr_supplierID",
            "_company_cabangID_modul_subModul_jenisTr_customerID",
            "_company_cabangID_modul_subModul_jenisTr_olehID",
            "_company_cabangID_modul_subModul_jenisTr_sellerID",
            "_company_cabangID_modul_subModul_jenisTr_cabangID",
            "_company_cabangID_modul_subModul_jenisTr_cabang2ID",
            "_company_cabangID_modul_subModul_jenisTr_gudangID",
            "_company_cabangID_modul_subModul_jenisTr_gudang2ID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_jenisTrMaster",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_supplierID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_customerID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_olehID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_sellerID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_cabangID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_cabang2ID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_gudangID",
            "_company_cabangID_modul_subModul_jenisTr_stepCode_gudang2ID",
            "_company_rekening",
            "_company_rekening_jenisTr",
            "_company_rekening_jenisTrMaster",
            "_company_rekening_stepCode",
            "_company_rekening_supplierID",
            "_company_rekening_customerID",
            "_company_rekening_olehID",
            "_company_rekening_sellerID",
            "_company_rekening_cabangID",
            "_company_rekening_cabang2ID",
            "_company_rekening_gudangID",
            "_company_rekening_gudang2ID",
            "_company_rekening_modul",
            "_company_rekening_subModul",
            "_company_rekening_cabangID_jenisTr",
            "_company_rekening_cabangID_jenisTrMaster",
            "_company_rekening_cabangID_stepCode",
            "_company_rekening_cabangID_supplierID",
            "_company_rekening_cabangID_customerID",
            "_company_rekening_cabangID_olehID",
            "_company_rekening_cabangID_sellerID",
            "_company_rekening_cabangID_cabangID",
            "_company_rekening_cabangID_cabang2ID",
            "_company_rekening_cabangID_gudangID",
            "_company_rekening_cabangID_gudang2ID",
            "_company_rekening_cabangID_modul",
            "_company_rekening_cabangID_subModul",
//            "status",
//            "trash",
//            "company_id",
//            "modul",
//            "subModul",
//            "transaksi_id",
        ),
    );
    protected $sortBy = array(
        "kolom" => "dtime",
        "mode" => "ASC",
    );
    protected $aliasName = array(
        "tmp" => array(
            "id" => "id_tmp",
            "jenis" => "jenis_tmp",
        ),
        "main" => array(),
        "detail" => array(
            "id" => "id_detail",
            "trash" => "trash_detail",
            "status" => "status_detail",
        ),
        "sub_detail" => array(
            "id" => "id_sub_detail",
            "trash" => "trash_sub_detail",
            "status" => "status_sub_detail",
        ),
        "items3_sum" => array(
            "id" => "id_sub_detail",
            "trash" => "trash_sub_detail",
            "status" => "status_sub_detail",
        ),

    );
    protected $joinedFilter = array();
    protected $jointSelectFields;
    protected $blockFields;
    protected $prefix;
    private $keyWord;


    //region getter-setter
    public function getPrefix()
    {
        return $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }


    public function __construct($prefix = NULL)
    {
        parent::__construct();
        $this->tableName = (($prefix != NULL) && isset($this->tableNames[$prefix])) ? $this->tableNames[$prefix]["main"] : NULL;
        if ($this->tableName != NULL) {
            $tbl_status = $this->tableName . ".status='1'";
            $tbl_trash = $this->tableName . ".trash='0'";
            $tbl_link_id = $this->tableName . ".link_id='0'";
        }
        else {
            $tbl_status = "status='1'";
            $tbl_trash = "trash='0'";
            $tbl_link_id = "link_id='0'";
        }
        $this->filters = array(
            "$tbl_status",
            "$tbl_trash",
            "$tbl_link_id",
        );
//        cekHere($prefix);
//        cekKuning($this->tableName);
//        arrprintPink($this->filters);
    }


    public function getJointSelectFields()
    {
        return $this->jointSelectFields;
    }

    public function setJointSelectFields($jointSelectFields)
    {
        //string setJointSelectFields("main,detail") untuk memlilih kolom yang diselect, jika tidak diset akan diambil dari field yang ada lihat array fields
        $this->jointSelectFields = $jointSelectFields;
    }

    public function getBlockFields()
    {
        return $this->blockFields;
    }

    public function setBlockFields($blockFields)
    {
        $this->blockFields = $blockFields;
    }

    public function addFilterJoin($f)
    {
        $this->joinedFilter[] = $f;
    }

    public function getJoinedFilter()
    {
        return $this->joinedFilter;
    }

    public function setJoinedFilter($joinedFilter)
    {
        $this->joinedFilter = $joinedFilter;
    }

    public function getAliasName()
    {
        return $this->aliasName;
    }

    public function setAliasName($aliasName)
    {
        $this->aliasName = $aliasName;
    }

    public function getKeyWord()
    {
        return $this->keyWord;
    }

    public function setKeyWord($keyWord)
    {
        $this->keyWord = $keyWord;
    }

    public function getTableNames()
    {
        return $this->tableNames;
    }

    public function setTableNames($tableNames)
    {
        $this->tableNames = $tableNames;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function lookupRecentHistories($limit = 0)
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        // $this->db->from($this->tableNames['main']);
        if ($limit > 0) {
            $this->db->limit((int)$limit);
        }

        $this->db->order_by("id", "desc");
        $result = $this->db->get($this->tableNames['main']);
        //        cekBiru($this->db->last_query());
        return $result;
    }
    //endregion


    //--menambahkan filter kolom

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getAvailTable($historyFields)
    {
        $columnFilter = array();
        if (sizeof($historyFields) > 0) {
            $noKey = 0;
            foreach ($historyFields as $key => $data) {
                if (in_array($key, $this->fields['main'])) {
                    $columnFilter[$noKey] = $key;
                }
                $noKey++;
            }
        }
        return $columnFilter;
    }

    //--menghasilkan beberapa baris entri terbaru

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }


    //region tabel transaksi
    public function lookupRecentUndoneEntries_joined__($cab, $gud)
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->db->group_start();
        $this->db->where(array("transaksi.cabang_id" => $cab));
        $this->db->or_where(array("transaksi.cabang2_id" => $cab));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("gudang_id" => $gud));
        $this->db->or_where(array("gudang2_id" => $gud));
        $this->db->group_end();

        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(10);
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        $result = $this->db->get($this->tableNames['main']);
        //        echo($this->db->last_query());
        return $result;
    }

    public function lookupRecentUndoneEntries_joined($array)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];
// arrPrintWebs($this->filters);
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $tabelNamaDetail . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        // arrPrint($this->filters);

        if (sizeof($array) > 0) {
            $cab = $array['cabang_id'];
            $gud = $array['gudang_id'];

            $this->db->group_start();
            $this->db->where(array("$tabelNamaMain.cabang_id" => $cab));
            $this->db->or_where(array("$tabelNamaMain.cabang2_id" => $cab));
            $this->db->group_end();

            $this->db->group_start();
            $this->db->where(array("$tabelNamaMain.gudang_id" => $gud));
            $this->db->or_where(array("$tabelNamaMain.gudang2_id" => $gud));
            $this->db->group_end();
        }


        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id, $tabelNamaMain.dtime as dtime");
        // $this->db->from($this->tableNames[$this->prefix]['main']);
        //        $this->db->limit(100);
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($tabelNamaMain . ".id", "desc");


        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $this->tableNames[$this->prefix]['main'] . ".id ");
        $result = $this->db->get($tabelNamaMain);
        //        echo($this->db->last_query());
        return $result;
    }

    public function lookupUndoneEntries_joined__($cab, $gud)
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->db->group_start();
        $this->db->where(array("transaksi.cabang_id" => $cab));
        $this->db->or_where(array("transaksi.cabang2_id" => $cab));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("transaksi.gudang_id" => $gud));
        $this->db->or_where(array("transaksi.gudang2_id" => $gud));
        $this->db->group_end();

        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(20);
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id))");
        $result = $this->db->get($this->tableNames['main']);
        //        cekmerah($this->db->last_query());
        return $result;
    }

    public function lookupUndoneEntries_joined($array)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $tabelNamaDetail . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        //cekHitam(sizeof($array));
        if (sizeof($array) > 0) {
            $cab = $array['cabang_id'];
            $gud = $array['gudang_id'];


            $this->db->group_start();
            $this->db->where(array("$tabelNamaMain.cabang_id" => $cab));
            $this->db->or_where(array("$tabelNamaMain.cabang2_id" => $cab));
            $this->db->group_end();

            $this->db->group_start();
            $this->db->where(array("$tabelNamaMain.gudang_id" => $gud));
            $this->db->or_where(array("$tabelNamaMain.gudang2_id" => $gud));
            $this->db->group_end();
        }

        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array("$tabelNamaMain.customers_nama", "$tabelNamaMain.oleh_nama", "$tabelNamaMain.suppliers_nama"));
        }

        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id,$tabelNamaMain.dtime as dtime");
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($tabelNamaMain . ".id", "desc");


        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $tabelNamaMain . ".id ");
        $result = $this->db->get($tabelNamaMain);
        //        cekmerah($this->db->last_query());
        return $result;
    }


    public function lookupRecentUndoneEntries_joinedAsset($array)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        //        $this->filters[99] = $this->tableNames[$this->prefix]['detail'] . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        //        arrPrint(sizeof($array));

        if (sizeof($array) > 0) {
            $cab = $array['cabang_id'];
            $gud = $array['gudang_id'];

            $this->db->group_start();
            $this->db->where(array("transaksi.cabang_id" => $cab));
            $this->db->or_where(array("transaksi.cabang2_id" => $cab));
            $this->db->group_end();

            $this->db->group_start();
            $this->db->where(array("gudang_id" => $gud));
            $this->db->or_where(array("gudang2_id" => $gud));
            $this->db->group_end();
        }


        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(100);
        //        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames[$this->prefix]['main'] . ".id", "desc");


        $this->db->join($this->tableNames[$this->prefix]['detail'], $this->tableNames[$this->prefix]['detail'] . ".transaksi_id = " . $this->tableNames[$this->prefix]['main'] . ".id ");
        $result = $this->db->get($this->tableNames[$this->prefix]['main']);
        //        echo($this->db->last_query());
        return $result;
    }

    //--menghasilkan beberapa baris entri  sesuai jumlah, limit, dan nomor halaman
    public function lookupHistories($jmlData, $limit, $page)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        // $jmlData=$this->lookupHistoryCount();
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";

            $this->createSmartSearch($key, array("customers_nama", "oleh_nama", "suppliers_nama"));
        }

        $numPages = ceil($jmlData / $limit);
        // $page = $_GET[page] ? $_GET[page] : 1;
        $offset = ($page - 1) * $limit;

//        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id,transaksi.id as tid");
//        $this->db->limit($limit, $offset);
//        $this->db->order_by($this->tableNames[$this->prefix]['main'] . ".id", "desc");
//
        $tabelNama = $this->tableNames[$this->prefix]['main'];
        $this->db->select("*,$tabelNama.oleh_id as oleh_id,$tabelNama.oleh_nama as oleh_nama,$tabelNama.cabang_id as cabang_id,$tabelNama.id as tid");
        $this->db->limit($limit, $offset);
        $this->db->order_by($tabelNama . ".id", "desc");

        return $this->db->get($tabelNama);
    }

    public function lookupHistories4Api($jmlData, $limit, $page)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $numPages = ceil($jmlData / $limit);
        $offset = ($page - 1) * $limit;

        $tabelNama = $this->tableNames[$this->prefix]['main'];
        $this->db->select("*,$tabelNama.oleh_id as oleh_id,$tabelNama.oleh_nama as oleh_nama,$tabelNama.id as tid");
        $this->db->limit($limit, $offset);
        $this->db->order_by($tabelNama . ".id", "desc");
        return $this->db->get($tabelNama);
    }

    public function lookupHistoriesDtAll()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $tabelNama = $this->tableNames[$this->prefix]['main'];
        $this->db->select("*,$tabelNama.oleh_id as oleh_id,$tabelNama.oleh_nama as oleh_nama,$tabelNama.cabang_id as cabang_id");
        $this->db->order_by("id", "desc");
        $rslt = $this->db->get($tabelNama)->num_rows();
        return ($rslt);
    }

    public function lookupHistoriesDtFil($jmlData, $limit, $length, $historyFields)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $arrAvailColumn = $this->getAvailTable($historyFields);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, $arrAvailColumn);
        }

        $tabelNama = $this->tableNames[$this->prefix]['main'];
        $this->db->select("*,$tabelNama.oleh_id as oleh_id,$tabelNama.oleh_nama as oleh_nama,$tabelNama.id as tid");
        $this->db->order_by($tabelNama . ".id", "desc");
        return $this->db->get($tabelNama)->num_rows();
    }

    public function lookupHistoriesDt($jmlData, $limit, $length, $historyFields)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $arrAvailColumn = $this->getAvailTable($historyFields);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, $arrAvailColumn);
        }
        if (isset($_GET['startDate']) && isset($_GET['startDate'])) {

        }

        $tabelNama = $this->tableNames[$this->prefix]['main'];
        $this->db->select("*,$tabelNama.oleh_id as oleh_id,$tabelNama.oleh_nama as oleh_nama,$tabelNama.id as tid");
        $this->db->limit($length, $limit);
        //jika ada perintah sorting
        if (isset($_GET['order'][0]['column']) && $_GET['order'][0]['column'] != '') {
            $column = isset($arrAvailColumn[$_GET['order'][0]['column']]) ? $arrAvailColumn[$_GET['order'][0]['column']] : "id";
            $sortMode = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : "desc";
            $this->db->order_by($this->tableNames[$this->prefix]['main'] . ".$column", "$sortMode");
        }
        else {
            $this->db->order_by($this->tableNames[$this->prefix]['main'] . ".id", "desc");
        }
        return $this->db->get($tabelNama);
    }

    public function lookupHistories_joined($jmlData, $limit, $page)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $numPages = ceil($jmlData / $limit);
        $offset = ($page - 1) * $limit;

        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id");

        $this->db->group_start();
        $this->db->where(array("$tabelNamaMain.cabang_id" => $this->session->login['cabang_id']));
        $this->db->or_where(array("$tabelNamaMain.cabang2_id" => $this->session->login['cabang_id']));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
        $this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
        $this->db->group_end();


        $this->db->limit($limit, $offset);
        $this->db->order_by("$tabelNamaMain.id", "desc");
        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $tabelNamaMain . ".id ");
        $result = $this->db->get($tabelNamaMain);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupHistories_joined_all()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];
        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id");
        //        $this->db->limit($limit, $offset);

        $this->db->group_start();
        $this->db->where(array("$tabelNamaMain.cabang_id" => $this->session->login['cabang_id']));
        $this->db->or_where(array("$tabelNamaMain.cabang2_id" => $this->session->login['cabang_id']));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
        $this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
        $this->db->group_end();

        $this->db->order_by("$tabelNamaMain.id", "desc");
        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $tabelNamaMain . ".id ");
        $result = $this->db->get($tabelNamaMain);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupHistoryCount()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id");
        $this->db->order_by("id", "desc");
        $rslt = $this->db->get($tabelNamaMain)->num_rows();
        return ($rslt);
    }

    public function lookupHistoryCount_joined()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id");
        $this->db->order_by("$tabelNamaMain.id", "desc");
        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $tabelNamaMain . ".id ");
        $rslt = $this->db->get($tabelNamaMain)->num_rows();
        return ($rslt);
    }

    public function lookupMainTransaksi()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];

        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id");
        return $this->db->get($tabelNamaMain);
    }

    public function lookupDetailTransaksi($transaksi_id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

        $criteria = array(
            "transaksi_id" => $transaksi_id,
            "produk_jenis" => "produk",
            "trash" => "0",
        );
        //        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        return $this->db->get_where($tabelNamaDetail, $criteria);
        //        $this->db->get_where($this->tableNames['detail'],$criteria);
        //        cekHEre($this->db->last_query());

        die();
    }

    public function lookupDetailTransaksional()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

//        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        return $this->db->get($tabelNamaDetail);
    }

    public function lookupDetailTransaksiNoJenis($transaksi_id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

        $criteria = array(
            "transaksi_id" => $transaksi_id,
            //            "produk_jenis" => "produk",
            "trash" => "0",
        );

        return $this->db->get_where($tabelNamaDetail, $criteria);


        die();
    }

    public function lookupJoinedByID($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

        $this->db->select("*,$tabelNamaMain.id as tid");

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        //        $criteria2 ="transaksi_data.trash='0'";
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $tabelNamaMain . ".id and " . $tabelNamaMain . ".id='$id'");
        return $this->db->get($tabelNamaMain);
    }

    public function lookupJoinedByID__($id)
    {
        $this->db->select("*,transaksi.id as tid");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and " . $this->tableNames['main'] . ".id='$id'");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupJoinedInspectionByMasterID($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id");
        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $tabelNamaMain . ".id and " . $tabelNamaMain . ".id_master='$id'");
        return $this->db->get($tabelNamaMain);
    }

    public function lookupJoinedByReceiptNO($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id");
        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $tabelNamaMain . ".id and nomer='$id'");
        $result = $this->db->get($tabelNamaMain);
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupJoined_OLD()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['main'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detail'];

        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.seller_id as seller_id,$tabelNamaMain.seller_nama as seller_nama,$tabelNamaMain.cabang_id as cabang_id,$tabelNamaMain.dtime as dtime");
        $this->filters[99] = $tabelNamaDetail . ".trash='0'";

        $criteria = array();
        $criteria2 = "";
        $criteriaJoin = array();
        $criteria2Join = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        //                $criteria2 ="transaksi_data.trash='0'";
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        if (sizeof($this->joinedFilter) > 0) {
            $this->fetchCriteriaJoined();
            $criteriaJoin = $this->getCriteria();
        }
        if (sizeof($criteriaJoin) > 0) {
            $this->db->where($criteriaJoin);
        }

        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $tabelNamaMain . ".id");
        return $this->db->get($tabelNamaMain);
    }

    public function lookupJoined()
    {
//arrPrintPink($this->getFields()["main"]);
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $starttime = microtime(true);
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
//        arrPrint($tmpExpl);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        // $this->db->limit(1000);
//        arrPrintPink($this->tableNames);
//        arrPrintPink($this->tableNames[$this->prefix]['main']);
        $main = $this->db->get($this->tableNames[$this->prefix]['main'])->result();
//        arrPrint($main);
//        cekBiru($this->db->last_query());
        //        arrPrintPink($this->getFields()["main"]);
        //         arrprint($main);
//        cekHitam(sizeof($main));
//        mati_disini(__LINE__);
        $retunTrans = array();
        if (sizeof($main) > 0) {
            $selectedAlias = $this->getAliasName()["detail"];
            $allDetailFields = $this->getFields()["detail"];
            $tmpAlias = array();
            $aliasFields = array();
            foreach ($allDetailFields as $fileds) {
                if (isset($selectedAlias[$fileds])) {
                    $alias_fields = $selectedAlias[$fileds];
                    $fileds = "$fileds as '" . $selectedAlias[$fileds] . "'";

                }
                else {
                    $alias_fields = $fileds;
                }
                $tmpAlias[] = $fileds;
                $aliasFields[] = $alias_fields;

            }

            $dataTranss = array();
            $fieldsSelected = array_merge($this->getFields()["main"], $aliasFields);

            //            arrPrint($main);

            foreach ($main as $i => $mainData_0) {
//                arrPrint($mainData_0);
                $idexingDetail = blobDecode($mainData_0->indexing_details);
                $this->db->select($tmpAlias);
                $criteria = array();
                $criteria2 = "";
                if (sizeof($this->joinedFilter) > 0) {
                    $this->fetchCriteriaJoined();
                    $criteria = $this->getCriteria();
                }
                if (sizeof($criteria) > 0) {
                    $this->db->where($criteria);
                }
                //                arrPrint($idexingDetail);
                //                $this->db->where("trash='0'");
                if (!empty($idexingDetail)) {
                    $this->db->where_in("id", $idexingDetail);
                }
                else {
                    $this->db->where("transaksi_id", $mainData_0->id);
                }
                $itemTmp = $this->db->get($this->tableNames[$this->prefix]['detail'])->result();
//                cekMerah($this->db->last_query());
                foreach ($itemTmp as $detailItemsTmp) {
                    $dataTranss[] = (array)$mainData_0 + (array)$detailItemsTmp;
                }
            }
            //arrPrintWebs($itemTmp);
            //            matiHere("mati dulu");
            $dataFinal = array();
            if (sizeof($dataTranss) > 0) {
                foreach ($dataTranss as $ix => $dataTranss) {
                    $dataFinal[$ix] = (object)$dataTranss;
                }
            }

            return $dataFinal;

        }

    }

    public function lookupJoinedSubItems()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $starttime = microtime(true);
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
        // arrPrint($tmpExpl);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $main = $this->db->get($this->tableNames[$this->prefix]['main'])->result();
        // ceklIme($this->db->last_query());
        $retunTrans = array();
        if (sizeof($main) > 0) {
            $selectedAlias = $this->getAliasName()["sub_detail"];
            $allDetailFields = $this->getFields()["sub_detail"];
            $tmpAlias = array();
            $aliasFields = array();
            foreach ($allDetailFields as $fileds) {
                if (isset($selectedAlias[$fileds])) {
                    $alias_fields = $selectedAlias[$fileds];
                    $fileds = "$fileds as '" . $selectedAlias[$fileds] . "'";

                }
                else {
                    $alias_fields = $fileds;
                }
                $tmpAlias[] = $fileds;
                $aliasFields[] = $alias_fields;
            }

            // arrPrint(blobDecode($main[0]->indexing_sub_details));
            // matiHEre();
            $dataTranss = array();
            $fieldsSelected = array_merge($this->getFields()["main"], $aliasFields);
            foreach ($main as $i => $mainData_0) {
                $idexingDetail = blobDecode($mainData_0->indexing_sub_details);
                // arrPrint($idexingDetail);
                // matiHEre(sizeof($idexingDetail));
//                if (sizeof($idexingDetail) == 0) {
//                    matiHere("indexing sub detil tidak terbaca! " . __LINE__);
//                }
                $this->db->select($tmpAlias);

                $criteria = array();
                $criteria2 = "";
                if (sizeof($this->joinedFilter) > 0) {
                    $this->fetchCriteriaJoined();
                    $criteria = $this->getCriteria();

                }
                if (sizeof($criteria) > 0) {
                    $this->db->where($criteria);
                }
                // matiHEre();

                //                $this->db->where("trash='0'");
                $this->db->where_in("id", $idexingDetail);
                $itemTmp = $this->db->get($this->tableNames[$this->prefix]['sub_detail'])->result();
                // cekKuning($this->db->last_query());
                //                arrPrintPink($itemTmp);
                foreach ($itemTmp as $detailItemsTmp) {
                    $dataTranss[] = (array)$mainData_0 + (array)$detailItemsTmp;

                }
            }
            $dataFinal = array();
            if (sizeof($dataTranss) > 0) {
                foreach ($dataTranss as $ix => $dataTranss) {
                    $dataFinal[$ix] = (object)$dataTranss;
                }
            }
            // arrPrint($dataFinal);
            // matiHEre();
            return $dataFinal;
            //
            // $sql ="";
            // $iCtr=0;
            // if(sizeof($dataTranss)>0){
            //     cekMerah(sizeof($dataTranss));
            //     // cekBiru($fieldsSelected);
            //     // cekHitam(sizeof($fieldsSelected));
            //     foreach($dataTranss as $iSpec){
            //         $iCtr++;
            //         $subSql = 'SELECT ';
            //         $fCtr = 0;
            //         $inclCtr = 0;
            //         foreach($fieldsSelected as $keyID){
            //             $fCtr++;
            //             $subSql .= "'" . $iSpec[$keyID] . "' as $keyID";
            //             if ($fCtr < sizeof($fieldsSelected)) {
            //                 $subSql .= ",";
            //             }
            //         }
            //         $subSql .= " union ";
            //         $sql .= $subSql;
            //     }
            //
            //     // cekBiru($sql);
            //     $sql = rtrim($sql, " union ");
            //     return $this->db->query($sql);
            // }

        }


    }

    public function lookupJoinedSubItems2()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $starttime = microtime(true);
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
        // arrPrint($tmpExpl);
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $main = $this->db->get($this->tableNames[$this->prefix]['main'])->result();
        // ceklIme($this->db->last_query());
        $retunTrans = array();
        if (sizeof($main) > 0) {
            $selectedAlias = $this->getAliasName()["items3_sum"];
            $allDetailFields = $this->getFields()["items3_sum"];
            $tmpAlias = array();
            $aliasFields = array();
            foreach ($allDetailFields as $fileds) {
                if (isset($selectedAlias[$fileds])) {
                    $alias_fields = $selectedAlias[$fileds];
                    $fileds = "$fileds as '" . $selectedAlias[$fileds] . "'";

                }
                else {
                    $alias_fields = $fileds;
                }
                $tmpAlias[] = $fileds;
                $aliasFields[] = $alias_fields;
            }

            // arrPrint(blobDecode($main[0]->indexing_sub_details));
            // matiHEre();
            $dataTranss = array();
            $fieldsSelected = array_merge($this->getFields()["main"], $aliasFields);
            foreach ($main as $i => $mainData_0) {
                $idexingDetail = blobDecode($mainData_0->indexing_items3_sum);
                // arrPrint($idexingDetail);
                // matiHEre(sizeof($idexingDetail));
                if (sizeof($idexingDetail) == 0) {
                    matiHere("indexing sub detil tidak terbaca! " . __LINE__);
                }
                $this->db->select($tmpAlias);

                $criteria = array();
                $criteria2 = "";
                if (sizeof($this->joinedFilter) > 0) {
                    $this->fetchCriteriaJoined();
                    $criteria = $this->getCriteria();

                }
                if (sizeof($criteria) > 0) {
                    $this->db->where($criteria);
                }
                // matiHEre();

                //                $this->db->where("trash='0'");
                $this->db->where_in("id", $idexingDetail);
                $itemTmp = $this->db->get($this->tableNames[$this->prefix]['items3_sum'])->result();
                cekKuning($this->db->last_query());
                //                arrPrintPink($itemTmp);
                foreach ($itemTmp as $detailItemsTmp) {
                    $dataTranss[] = (array)$mainData_0 + (array)$detailItemsTmp;

                }
            }
            $dataFinal = array();
            if (sizeof($dataTranss) > 0) {
                foreach ($dataTranss as $ix => $dataTranss) {
                    $dataFinal[$ix] = (object)$dataTranss;
                }
            }
            // arrPrint($dataFinal);
            // matiHEre();
            return $dataFinal;
            //
            // $sql ="";
            // $iCtr=0;
            // if(sizeof($dataTranss)>0){
            //     cekMerah(sizeof($dataTranss));
            //     // cekBiru($fieldsSelected);
            //     // cekHitam(sizeof($fieldsSelected));
            //     foreach($dataTranss as $iSpec){
            //         $iCtr++;
            //         $subSql = 'SELECT ';
            //         $fCtr = 0;
            //         $inclCtr = 0;
            //         foreach($fieldsSelected as $keyID){
            //             $fCtr++;
            //             $subSql .= "'" . $iSpec[$keyID] . "' as $keyID";
            //             if ($fCtr < sizeof($fieldsSelected)) {
            //                 $subSql .= ",";
            //             }
            //         }
            //         $subSql .= " union ";
            //         $sql .= $subSql;
            //     }
            //
            //     // cekBiru($sql);
            //     $sql = rtrim($sql, " union ");
            //     return $this->db->query($sql);
            // }

        }


    }

    public function lookupMainValuesByTransID($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $result = $this->db->get_where($this->tableNames[$this->prefix]['mainValues'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupMainValues()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*");
        $result = $this->db->get($this->tableNames[$this->prefix]['mainValues']);
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupDetailValuesByTransID($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $result = $this->db->get_where($this->tableNames[$this->prefix]['detailValues'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupDates()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        // $jmlData=$this->lookupHistoryCount();


        $this->db->select("fulldate");
        $this->db->group_by("fulldate");
        $this->db->order_by("fulldate");
        // $this->db->from($this->tableNames[$this->prefix]['main']);

        $tmp = $this->db->get($this->tableNames[$this->prefix]['main'])->result();
        $results = array(
            "start" => "0000-00-00",
            "end" => "0000-00-00",
            "entries" => array(),
        );
        if (sizeof($tmp) > 0) {
            $cnt = 0;
            foreach ($tmp as $row) {
                $cnt++;
                if ($cnt == 1) {
                    $results['start'] = $row->fulldate;
                }
                $results['entries'][$row->fulldate] = $row->fulldate;
                $results['end'] = $row->fulldate;
            }
        }
        return $results;
    }

    public function lookupEntryPoints_joined($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['mainEntryPoint'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detailEntryPoint'];

        $this->removeFilter("$tabelNamaMain.link_id='0'");
        $this->addFilter("$tabelNamaMain.link_id>'0'");
        //        $this->addFilter("$tabelNamaMain.link_id='$id'");
        $this->addFilter("$tabelNamaMain.id_master='$id'");
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $tabelNamaDetail . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id,$tabelNamaMain.dtime as dtime");
        $this->db->order_by("$tabelNamaMain.id", "asc");
        $this->db->join($tabelNamaDetail, $tabelNamaDetail . ".transaksi_id = " . $tabelNamaMain . ".id ");
        $result = $this->db->get($tabelNamaMain);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupEntryPoints($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['mainEntryPoint'];
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detailEntryPoint'];

        $this->removeFilter("$tabelNamaMain.link_id='0'");
        $this->addFilter("$tabelNamaMain.link_id>'0'");
        //        $this->addFilter("$tabelNamaMain.link_id='$id'");
        $this->addFilter("$tabelNamaMain.id_master='$id'");
        $criteria = array();
        $criteria2 = "";
        //        $this->filters[99] = $this->tableNames[$this->prefix]['detail'] . ".trash='0'";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id");
        $this->db->order_by("$tabelNamaMain.id", "asc");
        $result = $this->db->get($tabelNamaMain);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupMainTransaksiMutasi()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $tabelNamaMain = $this->tableNames[$this->prefix]['mainMutasi'];

        $this->db->select("*,$tabelNamaMain.oleh_id as oleh_id,$tabelNamaMain.oleh_nama as oleh_nama,$tabelNamaMain.cabang_id as cabang_id");
        return $this->db->get($tabelNamaMain);
    }

    public function lookupDetailTransaksiMutasi($transaksi_id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detailMutasi'];

        $criteria = array(
            "transaksi_id" => $transaksi_id,
            "produk_jenis" => "produk",
            "trash" => "0",
        );
        //        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        return $this->db->get_where($tabelNamaDetail, $criteria);
        //        $this->db->get_where($this->tableNames['detail'],$criteria);
        //        cekHEre($this->db->last_query());

        die();
    }

    public function lookupDetailTransaksionalMutasi()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $tabelNamaDetail = $this->tableNames[$this->prefix]['detailMutasi'];

//        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        return $this->db->get($tabelNamaDetail);
    }


    //  write temporary entries
    public function writeTmpEntries($params)
    {
        if (is_array($params)) {
            if (sizeof($params) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                //  region transaksi main
                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['tmp'])) {
                        $data[$fName] = $fValue;
                    }
                }
                //                foreach ($this->fields['main'] as $kolom) {
                //                    $isi = isset($params[$kolom]) ? $params[$kolom] : "";
                //
                //                    $data[$kolom] = $isi;
                //                }
                $this->db->insert($this->tableNames[$this->prefix]['tmp'], $data);

                //cekKuning($this->db->last_query());
                //  endregion transaksi main

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //  insert transaksi main
    public function writeMainEntries($params)
    {
        if (is_array($params)) {
            if (sizeof($params) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['main'])) {
                        $data[$fName] = $fValue;
                    }
                }
                $this->db->insert($this->tableNames[$this->prefix]['main'], $data);
                $insertID = $this->db->insert_id();
                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeMainEntries_entryPoint($insertID, $masterID, $params)
    {
        //transaksi.link_id='0'
        if (is_array($params)) {
            if (sizeof($params) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $tabelNamaMain = $this->tableNames[$this->prefix]['mainEntryPoint'];
                $tabelNamaDetail = $this->tableNames[$this->prefix]['detailEntryPoint'];
                //  region transaksi main
                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['mainEntryPoint'])) {
                        $data[$fName] = $fValue;
                    }
                }
                //===nulis entry-point
                if (strpos($params['jenis'], '_') == false) {
                    $epData = $data;
                    $replacers = array(
                        "id_top" => 0,
                        "id_master" => $masterID,
                        "link_id" => $insertID,
                        "jenis" => $data['jenis'] . "_" . $data['step_number'],
                        "nomer" => $data['nomer'] . "_" . $data['step_number'] . "_" . date("YmdHis"),
                    );
                    foreach ($replacers as $key => $newVal) {
                        $epData[$key] = $newVal;
                    }

                    //remove filter
                    $this->db->insert($tabelNamaMain, $epData);
                    $insertID2 = $this->db->insert_id();
                }
                else {
                    $insertID2 = 999;
                }

                return $insertID2;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //  insert transaksi childs
    public function writeDetailEntries($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['detail'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['detail'], $data);

                //cekLime($this->db->last_query());

                //                return $this->db->insert_id();
                $insertID = $this->db->insert_id();

                //                if( $detailParams['link_id']<1){
                //                    $epData=$detailParams;
                //                    $replacers=array(
                //                        "transaksi_id"=>$detailParams['link_id'],
                //
                //                    );
                //                    foreach($replacers as $key=>$newVal){
                //                        $epData[$key]=$newVal;
                //                    }
                //                    $this->writeDetailEntries($transaksi_id,$epData);
                //                }

                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //insert sub transaksi data untuk items2 yang ditabelkan contoh item produk project
    public function writeDetailSubEntries($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['sub_detail'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['sub_detail'], $data);
                $insertID = $this->db->insert_id();

                //                if( $detailParams['link_id']<1){
                //                    $epData=$detailParams;
                //                    $replacers=array(
                //                        "transaksi_id"=>$detailParams['link_id'],
                //
                //                    );
                //                    foreach($replacers as $key=>$newVal){
                //                        $epData[$key]=$newVal;
                //                    }
                //                    $this->writeDetailEntries($transaksi_id,$epData);
                //                }

                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeDetailSubEntries_items($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['items3_sum'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['items3_sum'], $data);
                $insertID = $this->db->insert_id();

                //                if( $detailParams['link_id']<1){
                //                    $epData=$detailParams;
                //                    $replacers=array(
                //                        "transaksi_id"=>$detailParams['link_id'],
                //
                //                    );
                //                    foreach($replacers as $key=>$newVal){
                //                        $epData[$key]=$newVal;
                //                    }
                //                    $this->writeDetailEntries($transaksi_id,$epData);
                //                }

                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis nilai2 utama tapi dipisah tabel
    public function writeMainValues($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['mainValues'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['mainValues'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis nilai2 rincian tapi dipisah tabel
    public function writeDetailValues($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['detailValues'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['detailValues'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis kolom2 utama tapi dipisah tabel
    public function writeMainFields($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['mainFields'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['mainFields'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis kolom2 rincian tapi dipisah tabel
    public function writeDetailFields($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['detailFields'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['detailFields'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--menulis kolom2 utama tapi dipisah tabel
    public function writeMainApplets($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['applets'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['applets'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function writeMainElements($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['elements'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['elements'], $data);

                //cekKuning($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //--update tabel transaksi
    public function updateMainEntries($where, $data)
    {
        if (is_array($data)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }

            $this->db->where($where);
            $this->db->update($this->tableNames[$this->prefix]['main'], $data);

            return true;
        }
        else {
            return false;
        }


    }

    public function updateDetailEntries($where, $data)
    {
        if (is_array($data)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }

            $this->db->where($where);
            $this->db->update($this->tableNames[$this->prefix]['detail'], $data);

            return true;
        }
        else {
            return false;
        }


    }

    public function updateMainValuesEntries($where, $data)
    {
        if (is_array($data)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }

            $this->db->where($where);
            $this->db->update($this->tableNames[$this->prefix]['mainValues'], $data);

            return true;
        }
        else {
            return false;
        }


    }

    public function updateDetailValuesEntries($where, $data)
    {
        if (is_array($data)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }

            $this->db->where($where);
            $this->db->update($this->tableNames[$this->prefix]['detailValues'], $data);

            return true;
        }
        else {
            return false;
        }


    }

    public function updateMainAppletsEntries($where, $data)
    {
        if (is_array($data)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }

            $this->db->where($where);
            $this->db->update($this->tableNames[$this->prefix]['applets'], $data);

            return true;
        }
        else {
            return false;
        }


    }

    public function updateMainElementEntries($where, $data)
    {
        if (is_array($data)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }

            $this->db->where($where);
            $this->db->update($this->tableNames[$this->prefix]['elements'], $data);

            return true;
        }
        else {
            return false;
        }


    }

    //  insert transaksi main
    public function writeCounterEntries($insertID, $params)
    {
        if (is_array($params)) {
            if (sizeof($params) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['counter'])) {
                        $data[$fName] = $fValue;
                    }
                }
                $data['transaksi_id'] = $insertID;
                $this->db->insert($this->tableNames[$this->prefix]['counter'], $data);
                $insertID = $this->db->insert_id();
                return $insertID;
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    //endregion


    //
    //region extended steps
    public function writeExtStep($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['extras'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['extras'], $data);

                //cekKuning($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function lookupExtSteps($masterID)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $tmp = $this->db->get_where(
            $this->tableNames[$this->prefix]['extras'],
            array(
                "master_id" => $masterID,
                "state" => "0",
            )
        )->result();
        if (sizeof($tmp) > 0) {
            $results = array();
            foreach ($tmp as $row) {
                $results[] = array(
                    "id" => $row->id,
                    "key" => $row->_key,
                    "label" => $row->_label,
                    "value" => $row->_value,
                    "groupID" => $row->group_id,
                );
            }
            return $results;
        }
        else {
            return array();
        }

    }

    public function lookupExtStepByID($valID)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames[$this->prefix]['extras'],
            array(
                "id" => $valID,
                "state" => "0",
            )
        )->result();
        if (sizeof($tmp) > 0) {
            $results = array();
            foreach ($tmp as $row) {
                $results[] = array(
                    "id" => $row->id,
                    "key" => $row->_key,
                    "label" => $row->_label,
                    "value" => $row->_value,
                    "groupID" => $row->group_id,
                    "proposed" => array(
                        "personID" => $row->proposed_by,
                        "time" => $row->proposed_dtime,
                    ),
                );
            }
            return $results;
        }
        else {
            return array();
        }

    }

    public function lookupExtStepByTrID($valID)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames[$this->prefix]['extras'],
            array(
                "transaksi_id" => $valID,
                "state" => "0",
            )
        )->result();
        if (sizeof($tmp) > 0) {
            $results = array();
            foreach ($tmp as $row) {
                $results[] = array(
                    "id" => $row->id,
                    "key" => $row->_key,
                    "label" => $row->_label,
                    "value" => $row->_value,
                    "groupID" => $row->group_id,
                    "proposed" => array(
                        "personID" => $row->proposed_by,
                        "time" => $row->proposed_dtime,
                    ),
                );
            }
            return $results;
        }
        else {
            return array();
        }

    }

    public function approveExtStepByID($valID)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->setTableName($this->getTableNames()[$this->prefix]['extras']);
        $this->setFilters(array());
        $this->updateData(
            array(
                "id" => $valID,
            ),
            array(
                "state" => "1",
                "done_by" => $this->session->login['id'],
                "done_dtime" => date("Y-m-d H:i:s"),
            )
        );
        return true;

    }

    public function rejectExtStepByID($valID)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->setTableName($this->getTableNames()[$this->prefix]['extras']);
        $this->setFilters(array());
        $this->updateData(
            array(
                "id" => $valID,
            ),
            array(
                "state" => "-1",
            )
        );
        return true;

    }

    public function resetExtStepByID($valID)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->setTableName($this->getTableNames()[$this->prefix]['extras']);
        $this->setFilters(array());
        $this->updateData(
            array(
                "id" => $valID,
            ),
            array(
                "state" => "0",
            )
        );
        return true;

    }

    public function extStepExistsInMaster($masterID, $iKey)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames[$this->prefix]['extras'],
            array(
                "master_id" => $masterID,
                "_key" => $iKey,
                "state" => 0,
            )
        )->result();
        if (sizeof($tmp) > 0) {
            return true;
        }
        else {
            return false;
        }

    }
    //endregion

    //
    //region payment-source
    public function writePaymentSrc($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['paymentSrc'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['paymentSrc'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function updatePaymentSrc($where, $data)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->where($where);
        $this->db->update($this->tableNames[$this->prefix]['paymentSrc'], $data);
        return true;

    }

    public function lookupPaymentSrcs($masterID, $jenis, $key = "")
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $where = array(
            "transaksi_id" => $masterID,
            "jenis" => $jenis,

        );
        if ($key != "") {
            $where["_key"] = $key;
        }
        $tmp = $this->db->get_where(
            $this->tableNames[$this->prefix]['paymentSrc'],
            $where
        )->result();
        if (sizeof($tmp) > 0) {
            $results = array();
            foreach ($tmp as $row) {
                $results[$row->target_jenis] = array(
                    "id" => $row->id,
                    "targetJenis" => $row->target_jenis,
                    "label" => $row->label,
                    "tagihan" => $row->tagihan,
                    "sisa" => $row->sisa,
                    "extID" => $row->extern_id,
                    "extName" => $row->extern_nama,
                );
            }
            return $results;

        }
        else {
            return array();
        }
    }

    public function paymentSrcExistsInMaster($masterID, $iCode, $iLabel)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");

        $tmp = $this->db->get_where($this->tableNames[$this->prefix]['paymentSrc'], array("transaksi_id" => $masterID, "target_jenis" => $iCode, "label" => $iLabel))->result();
        if (sizeof($tmp) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    public function lookupPaymentSrcByID($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames[$this->prefix]['paymentSrc'], array("id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentSrcByTransID($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames[$this->prefix]['paymentSrc'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentSrcByJenis($jenis)
    {

        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames[$this->prefix]['paymentSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentSrcByJenis_joined($jenis)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        //        $this->db->select("*");
        $this->db->select("*,transaksi_payment_source.valas_id as valas_id,transaksi_payment_source.valas_nama as valas_nama,transaksi_payment_source.extern_label2 as extern_label2,transaksi_payment_source.pph_23 as pph_23,transaksi_payment_source.terbayar_pph23 as terbayar_pph23,transaksi_payment_source.valas_nilai as valas_nilai,transaksi_payment_source.id as id");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        $this->db->join($this->tableNames[$this->prefix]['main'], $this->tableNames[$this->prefix]['paymentSrc'] . ".transaksi_id = " . $this->tableNames[$this->prefix]['main'] . ".id ");
        $result = $this->db->get_where($this->tableNames[$this->prefix]['paymentSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookUpAllPaymentSrc()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames[$this->prefix]['paymentSrc']);
        //        cekMerah($this->db->last_query());
        return $result;
    }

    //endregion

    //region payment-antisource
    public function writePaymentAntiSrc($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }

                $data = array();
                foreach ($this->fields['paymentAntiSrc'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['paymentAntiSrc'], $data);

                //                cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function updatePaymentAntiSrc($where, $data)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->where($where);
        $this->db->update($this->tableNames[$this->prefix]['paymentAntiSrc'], $data);
        return true;

    }

    public function lookupPaymentAntiSrcs($masterID, $jenis, $key = "")
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $where = array(
            "transaksi_id" => $masterID,
            "jenis" => $jenis,

        );
        if ($key != "") {
            $where["_key"] = $key;
        }
        $tmp = $this->db->get_where(
            $this->tableNames[$this->prefix]['paymentAntiSrc'],
            $where
        )->result();
        if (sizeof($tmp) > 0) {
            $results = array();
            foreach ($tmp as $row) {
                $results[$row->target_jenis] = array(
                    "id" => $row->id,
                    "targetJenis" => $row->target_jenis,
                    "label" => $row->label,
                    "tagihan" => $row->tagihan,
                    "sisa" => $row->sisa,
                    "extID" => $row->extern_id,
                    "extName" => $row->extern_nama,
                );
            }
            return $results;

        }
        else {
            return array();
        }
    }

    public function paymentAntiSrcExistsInMaster($masterID, $iCode, $iLabel)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");

        $tmp = $this->db->get_where($this->tableNames[$this->prefix]['paymentAntiSrc'], array("transaksi_id" => $masterID, "target_jenis" => $iCode, "label" => $iLabel))->result();
        if (sizeof($tmp) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    public function lookupPaymentAntiSrcByTransID($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames[$this->prefix]['paymentAntiSrc'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentAntiSrcByJenis($jenis)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames[$this->prefix]['paymentAntiSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentAntiSrcByJenis_joined($jenis)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->join($this->tableNames[$this->prefix]['main'], $this->tableNames[$this->prefix]['paymentAntiSrc'] . ".transaksi_id = " . $this->tableNames[$this->prefix]['main'] . ".id ");
        $result = $this->db->get_where($this->tableNames[$this->prefix]['paymentAntiSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentAntiSrcByLabel($jenis)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames[$this->prefix]['paymentAntiSrc'], array("label" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    //endregion

    //region uang muka-source
    public function writeUangMukaSrc($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                if (!isset($this->prefix) || ($this->prefix == NULL)) {
                    $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                    mati_disini($msg);
                }
                $data = array();
                foreach ($this->fields['uangMuka'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames[$this->prefix]['uangMuka'], $data);

                //cekLime($this->db->last_query());

                return $this->db->insert_id();
            }
            else {
                return null;
            }
        }
        else {
            return null;
        }
    }

    public function updateUangMukaSrc($where, $data)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->where($where);
        $this->db->update($this->tableNames[$this->prefix]['uangMuka'], $data);
        return true;

    }

    public function lookupUangMukaSrc($jenis)
    {// $jenis = customer/supplier/vendor

        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames[$this->prefix]['uangMuka'], array("extern_label2" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }
    //endregion

    //
    //region signatures
    public function writeSignature($transaksi_id, $params)
    {
        if (is_array($params)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }
            $data = array();
            foreach ($params as $key => $value) {
                $data[$key] = $value;
            }
            $data['transaksi_id'] = $transaksi_id;
            $this->db->insert($this->tableNames[$this->prefix]['sign'], $data);
            $insertID = $this->db->insert_id();
            //cekHijau($this->db->last_query());


            if ($insertID > 0) {
                return $insertID;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }//--nulis signature ERP setiap step

    public function lookupSignaturesByMasterID($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->order_by("id", "asc");
        return $this->db->get_where($this->tableNames[$this->prefix]['sign'], array("transaksi_id" => $id));
    }//--baca signature berdasarkan ID master

    public function lookupSignatures($id_master)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        // arrPrint($this->fields['main']);
        $this->db->select($this->fields['main']);
        $criteria = array(
            "link_id >" => 0,
            // "id_master" => $id_master
        );


        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        // $this->tableName$this->tableNames[$this->prefix]['main'];
        // $this->tableName('transaksi');
        // $this->setTableName('transakai');
        $this->db->order_by("id", "asc");
        $var = $this->db->get_where($this->tableNames[$this->prefix]['main'], array("id_master" => $id_master));

        return $var;
    }//--baca signature berdasarkan ID master
    //endregion

    //
    //region registries
    public function writeRegistries($insertID, $batchParams)
    {
        //    print_r($batchParams);die();
        if (is_array($batchParams)) {

            $data = array();
            $insertIDs = array();
            $indexing_registry = array();
            foreach ($batchParams as $param => $value) {
                //                echo "param: $param<br>";
                //                echo "value: $value<br>";
                $data['param'] = $param;
                $data['values'] = base64_encode(serialize($value));
                //                $data['values'] = $value;
                //                $data['values_intext'] = print_r($value, true);

                $data['transaksi_id'] = $insertID;
                $this->db->insert($this->tableNames['registry'], $data);
                $insertIDs[] = $this->db->insert_id();
                $indexing_registry[$param] = $this->db->insert_id();

                //                cekUngu($this->db->last_query());

            }
            if (sizeof($insertIDs) > 0) {
                $arrBlob = blobEncode($indexing_registry);
                $this->db->query("UPDATE transaksi SET indexing_registry = '$arrBlob' WHERE id=$insertID");
                return $indexing_registry;
                //                return implode(",", explode("-", $insertIDs));
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }//--nulis registri transaksi

    public function lookupBaseRegistries($ids = "")
    {
        if (is_array($ids)) {
            if (sizeof($ids) == 0) {
                matiHere("undefine iDs " . __LINE__);
            }
            $this->db->where_in("id", $ids);
        }
        elseif ($ids > 0) {
            $this->db->where("id", $ids);
        }

        // }
        return $this->db->get($this->tableNames['registry']);
    }//--baca registri berdasarkan ID registri

    public function lookupRegistries()
    {
        $this->filters[] = "trash=0";

        $criteria = array();
        $criteria2 = "";

        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*");
        return $this->db->get($this->tableNames['registry']);
    }//--baca registri berdasarkan ID master

    public function lookupRegistries_joined()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*");
        //        $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id  and " . $this->tableNames['registry'] . ".trash = 0");
        return $this->db->get($this->tableNames['registry']);
    }//--baca registri berdasarkan ID master

    public function lookupRegistriesByMasterID($id)
    {
        $criteria2 = array("trash" => "0");
        $this->db->where($criteria2);

        return $this->db->get_where($this->tableNames['registry'], array("transaksi_id" => $id));
    }//--baca registri berdasarkan ID master

    public function lookupRegistriesByNumber($id)
    {
        //        $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and transaksi.nomer='$id'");
        $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and transaksi.nomer='$id' and " . $this->tableNames['registry'] . ".trash = 0");
        $res = $this->db->get($this->tableNames['registry']);
        return $res;
    }//--baca registri berdasarkan ID master

    public function updateRegistry($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->tableNames['registry'], $data);
        return true;
    }
    //endregion

    //region registry metode baru untuk transaksi berjalan
    public function writeDataRegistries($insertID, $batchParams)
    {

        if (is_array($batchParams)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }
            foreach ($batchParams as $kolom => $values) {
                $batchParamsEncode[$kolom] = blobEncode($values);

            }
            $batchParamsEncode['transaksi_id'] = $insertID;
            $this->db->insert($this->tableNames[$this->prefix]['dataRegistry'], $batchParamsEncode);

            return $insertID;
        }
        else {
            return false;
        }

    }//--nulis registri transaksi

    public function writeDataRegistriesHistory($batchParams)
    {

        if (is_array($batchParams)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }
            //            foreach ($batchParams as $kolom => $values) {
            //                $batchParamsEncode[$kolom] = blobEncode($values);
            //            }
            //            $batchParamsEncode['transaksi_id'] = $insertID;
            $insertID = $this->db->insert($this->tableNames[$this->prefix]['registry'], $batchParams);

            return $insertID;
        }
        else {
            return false;
        }
    }//--nulis registri transaksi

    public function updateDataRegistry($where, $data)
    {
        if (is_array($data)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }
            foreach ($data as $key => $val) {
                $dataEncode[$key] = blobEncode($val);
            }

            $this->db->where($where);
            $this->db->update($this->tableNames[$this->prefix]['dataRegistry'], $dataEncode);

            return true;
        }
        else {
            return false;
        }
    }

    public function lookupBaseDataRegistries($ids = "")
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }

        if (isset($this->jointSelectFields)) {
            $this->db->select($this->jointSelectFields);
        }
        else {
            $tmpExpl = implode(",", $this->getFields()["dataRegistry"]);
            $this->db->select($tmpExpl);
        }

        if (is_array($ids)) {
            $this->db->where_in("transaksi_id", $ids);
        }
        elseif ($ids > 0) {
            $this->db->where("transaksi_id", $ids);
        }


        return $this->db->get($this->tableNames[$this->prefix]['dataRegistry']);
    }//--baca registri berdasarkan ID registri

    public function lookupDataRegistries()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";

        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }

        if (isset($this->jointSelectFields)) {

            $this->db->select($this->jointSelectFields);
        }
        else {
            $tmpExpl = implode(",", $this->getFields()["dataRegistry"]);
            $this->db->select($tmpExpl);
        }
        // $this->db->select("*");

        return $this->db->get($this->tableNames[$this->prefix]['dataRegistry']);
    }//--baca registri berdasarkan ID master

    public function lookupDataRegistriesByMasterID($id)
    {
        //        $criteria2 = array("trash" => "0");
        //        $this->db->where($criteria2);

        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        return $this->db->get_where($this->tableNames[$this->prefix]['dataRegistry'], array("transaksi_id" => $id));
    }//--baca registri berdasarkan ID master

    public function lookupDataRegistries_joined()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
        $main = $this->db->get($this->tableNames[$this->prefix]['main'])->result();
        // cekHitam($this->db->last_query());
        // arrPrint($main);
        // matiHEre();
        $dataTrans = array();
        $dataFinal = array();
        if (sizeof($main) > 0) {
            foreach ($main as $i => $mainData_0) {
                if (isset($this->jointSelectFields)) {
                    $this->db->select($this->jointSelectFields);
                }
                else {
                    $tmpExpl = implode(",", $this->getFields()["dataRegistry"]);
                    $this->db->select($tmpExpl);
                }

                $criteria = array();
                $criteria2 = "";
                if (sizeof($this->joinedFilter) > 0) {
                    $this->fetchCriteriaJoined();
                    $criteria = $this->getCriteria();

                }
                //                arrPrintPink($criteria);
                if (sizeof($criteria) > 0) {
                    $this->db->where($criteria);
                }
                // matiHEre();

                //                $this->db->where("trash='0'");
                $this->db->where("transaksi_id", $mainData_0->id);
                $itemTmp = $this->db->get($this->tableNames[$this->prefix]['dataRegistry'])->result();
                // arrPrintPink($itemTmp);
                // cekLime($this->db->last_query());
                // matiHEre();
                foreach ($itemTmp as $detailItemsTmp) {
                    $dataTrans[] = (array)$mainData_0 + (array)$detailItemsTmp;
                }
            }
            // arrPrint($dataTrans);
            $dataFinal = array();
            if (sizeof($dataTrans) > 0) {
                foreach ($dataTrans as $ix => $dataTrans) {
                    $dataFinal[$ix] = (object)$dataTrans;
                }
            }
        }

        return $dataFinal;
        // cekHitam($this->db->last_query());
        // arrPrint($main);
        // matiHEre();
        // $this->db->join($this->tableNames['main'], $this->tableNames['registry'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        // return $this->db->get($this->tableNames['registry']);
    }//--baca registri berdasarkan ID master

    public function lookupTransaksiDataRegistries($transaksi_id = "")
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $field_main = $this->fields['main'];
        $field_slave = $this->fields['dataRegistry'];

        $selectedFields = $allFields = array_merge($field_main, $field_slave);

        if (isset($this->blockFields)) {
            // cekBiru($this->blockFields);
            $selectedFields = array_diff($allFields, $this->blockFields);
        }
        // cekHijau(sizeof($selectedFields));
        $this->db->select($selectedFields);

        $this->filters[99] = $this->tableNames[$this->prefix]['detail'] . ".trash='0'";
        $tbl_main = $this->tableNames[$this->prefix]['main'];
        $tbl_slave = $this->tableNames[$this->prefix]['dataRegistry'];

        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            // $this->db->where($criteria);
        }
        //                $criteria2 ="transaksi_data.trash='0'";
        if ($criteria2 != "") {
            // $this->db->where($criteria2);
        }

        if ($transaksi_id != "") {
            if (is_array($transaksi_id)) {
                $this->db->where_in("$tbl_main.id", $transaksi_id);
            }
            else {
                $this->db->where("$tbl_main.id", $transaksi_id);
            }
        }

        $this->db->join($tbl_slave, $tbl_slave . ".transaksi_id = " . $tbl_main . ".id");
        return $this->db->get($tbl_main);
    }
    //endregion

    //region duedate
    public function writeDueDate($insertID, $params)
    {
        if (is_array($params)) {
            if (!isset($this->prefix) || ($this->prefix == NULL)) {
                $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
                mati_disini($msg);
            }
            $data = array();
            foreach ($params as $key => $value) {
                $data[$key] = $value;
            }
            $data['transaksi_id'] = $insertID;
            $this->db->insert($this->tableNames[$this->prefix]['dueDate'], $data);
            $insertID = $this->db->insert_id();
            //cekHijau($this->db->last_query());
            if ($insertID > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function updateDueDate($where, $data)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->where($where);
        $this->db->update($this->tableNames[$this->prefix]['dueDate'], $data);
        return true;
    }

    public function lookupDueDate($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $this->db->select("due_date,transaksi_nilai,nomer,transaksi_id");
        $result = $this->db->get_where($this->tableNames[$this->prefix]['dueDate'], array("customers_id" => $id, "status" => "1", "trash" => "0"))->result();
        $temp = array();
        $tempDate = array();
        $total = 0;
        foreach ($result as $result_0) {
            //            arrPrint($result_0);
            $date = $result_0->due_date;
            $nilai = $result_0->transaksi_nilai;
            $nomer = $result_0->nomer;
            $total += $nilai;
            $dateSecond = strtotime($date);
            $tempDate[] = $dateSecond;
            $temp[$dateSecond] = array(
                "date" => $date,
                "nomer" => $nomer,
                "transaksi_id" => $result_0->transaksi_id,
            );
            //           cekHere("$date || $nilai||$dateSecond");
        }

        if (sizeof($tempDate) > 0) {
            sort($tempDate);
        }
        //arrPrint($tempDate);
        $due = isset($tempDate['0']) ? $tempDate['0'] : "0";
        $data['due_date'] = isset($temp[$due]['date']) ? $temp[$due]['date'] : "0";
        $data['nomer'] = isset($temp[$due]['nomer']) ? $temp[$due]['nomer'] : "0";
        $data['transaksi_id'] = isset($temp[$due]['transaksi_id']) ? $temp[$due]['transaksi_id'] : "0";
        $data['total'] = $total;

        return $data;
    }

    public function lookupAllDueDate()
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $this->db->select("*");
        return $this->db->get($this->tableNames[$this->prefix]['dueDate']);
    }

    //endregion

    //region ABR-like recap
    public function fetchIdentifiers()
    {
        $resultFields = array();
        $mainFields = $this->db->list_fields($this->tableNames['main']);

        if (sizeof($mainFields) > 0) {
            foreach ($mainFields as $f) {
                $lastChar = substr($f, -3);
                if ($lastChar == "_id") {
                    $tmpKey = $f;
                    $tmpPairName = str_replace("_id", "_nama", $f);
                    if (in_array($tmpPairName, $mainFields)) {
                        $resultFields[$tmpKey] = $tmpPairName;
                    }
                    else {
                        //                        $resultFields[$tmpKey] = "unknown";
                    }
                }

            }
        }
        $childFields = $this->db->list_fields($this->tableNames['detail']);
        if (sizeof($childFields) > 0) {
            foreach ($childFields as $f) {
                $lastChar = substr($f, -3);
                if ($lastChar == "_id") {
                    $tmpKey = $f;
                    $tmpPairName = str_replace("_id", "_nama", $f);
                    if (in_array($tmpPairName, $childFields)) {
                        $resultFields[$tmpKey] = $tmpPairName;
                    }
                    else {
                        //                        $resultFields[$tmpKey] = "unknown";
                    }
                }

            }
        }
        return $resultFields;

    }

    public function fetchMasterIdentifiers()
    {
        $resultFields = array();
        $childFields = $this->db->list_fields($this->tableNames['main']);
        if (sizeof($childFields) > 0) {
            foreach ($childFields as $f) {
                $lastChar = substr($f, -3);
                if ($lastChar == "_id") {
                    $tmpKey = $f;
                    $tmpPairName = str_replace("_id", "_nama", $f);
                    if (in_array($tmpPairName, $childFields)) {
                        $resultFields[$tmpKey] = $tmpPairName;
                    }
                    else {
                        //                        $resultFields[$tmpKey] = "unknown";
                    }
                }

            }
        }
        return $resultFields;

    }

    public function fetchChildIdentifiers()
    {
        $resultFields = array();
        $childFields = $this->db->list_fields($this->tableNames['detail']);
        if (sizeof($childFields) > 0) {
            foreach ($childFields as $f) {
                $lastChar = substr($f, -3);
                if ($lastChar == "_id") {
                    $tmpKey = $f;
                    $tmpPairName = str_replace("_id", "_nama", $f);
                    if (in_array($tmpPairName, $childFields)) {
                        $resultFields[$tmpKey] = $tmpPairName;
                    }
                    else {
                        //                        $resultFields[$tmpKey] = "unknown";
                    }
                }

            }
        }
        return $resultFields;

    }

    //endregion

    public function _resetorReport($jenis_array)
    {

        if (is_array($jenis_array)) {
            $jenis = implode("','", $jenis_array);
        }
        else {
            $jenis = $jenis_array;
            strlen($jenis_array) > 0 ? $jenis_array : matiHere(__METHOD__ . " isikan <b>jenis</b> dalam format array lebih dulu");
        }

        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }

        $condite = array(
            "r_jenis" => 1,
        );
        $this->db->where("jenis in('" . $jenis . "')");
        $datas = array(
            "r_jenis" => 0,
        );
        $this->updateMainEntries($condite, $datas);
    }

    public function lookupOutstandingStocks()
    {
        $fields = array(
            "valid_qty",
            "transaksi.cabang_id as place_id",
            "produk_id",
            "produk_nama",
            "produk_kode",
            "customers_id",
            "customers_nama",
            "transaksi.oleh_id as seller_id",
        );
        $wheres = array(
            "jenis" => "582so",
            "valid_qty >" => "0",
            "sub_step_number >" => "0",
        );
        $where_spos = array(
            "jenis" => "582spo",
            "valid_qty >" => "0",
            "sub_step_number" => "0",
        );

        $this->db->select($fields);
        $this->db->where($wheres);
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id");
        $tmpso = $this->db->get($this->tableNames['main'])->result();
        // showLast_query("lime");

        $this->db->select($fields);
        $this->db->where($where_spos);
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id");
        $tmpspo = $this->db->get($this->tableNames['main'])->result();
        // showLast_query("kuning");
        $tmpspo = array();
        // arrPrint($tmpso);
        // arrPrintWebs($tmpspo);
        $tmp = array_merge($tmpso, $tmpspo);

        // arrPrint($tmp);
        foreach ($tmp as $item) {

            if (!isset($byCabangId[$item->place_id][$item->produk_id]["valid_qty"])) {
                $byCabangId[$item->place_id][$item->produk_id]["valid_qty"] = 0;
            }
            $byCabangId[$item->place_id][$item->produk_id]["valid_qty"] += $item->valid_qty;
            $byCabangId[$item->place_id][$item->produk_id]["produk_nama"] = $item->produk_nama;
            $byCabangId[$item->place_id][$item->produk_id]["produk_kode"] = $item->produk_kode;
        }
        // arrPrint($byCabangId);
        $vars['row'] = $tmp;
        $vars['byCabang'] = $byCabangId;

        return $vars;
    }


    /* =====================================================================================
     * transaksi sign
     * =====================================================================================*/
    public function lookupCanceledSo()
    {
        $wheres = array(
            // "step_code" => "582spo",
            "step_number" => "-1",
        );
        // $this->db->table_exists("transaksi_sign");
        // $vars = parent::lookupAll(); // TODO: Change the autogenerated stub
        // $this->db->select($fields);
        $this->db->where($wheres);
        // $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id");
        $vars = $this->db->get($this->tableNames['sign']);

        return $vars;
    }

    public function markingCanceledSo($id, $datas)
    {

        $this->db->where("id = '$id'");
        $this->db->update($this->tableNames['sign'], $datas);
    }

    public function _resetorReportSign($jenis_array)
    {

        if (is_array($jenis_array)) {
            $jenis = implode("','", $jenis_array);
        }
        else {
            $jenis = $jenis_array;
            strlen($jenis_array) > 0 ? $jenis_array : matiHere(__METHOD__ . " isikan <b>jenis</b> dalam format array lebih dulu");
        }


        $condites = array(
            "r_jenis" => 1,
        );
        $this->db->where($condites);
        $this->db->where("step_code in('" . $jenis . "')");
        $datas = array(
            "r_jenis" => 0,
        );

        $this->db->update($this->tableNames['sign'], $datas);
    }

    //---------------------------
    public function lookupHistoryEfakturByMasterID($id)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }

        $this->db->select("*");
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        $result = $this->db->get_where($this->tableNames[$this->prefix]['efaktur'], array("id_master" => $id));

        return $result;
    }

    public function callSpecs($transaksiIds = "")
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }

        $selecteds = array(
            "id",
            "oleh_nama",
            "jenis_label",
            "customers_nama",
            "cabang_nama",
            "suppliers_id",
            "suppliers_nama",
            "nomer",
            "nomer_top",
            "seller_nama",
            "oleh_nama",
            // "ids_his",
        );
        $this->db->select($selecteds);

        // if (isset($transaksiIds)) {
        if (is_array($transaksiIds)) {
            $this->db->where_in("id", $transaksiIds);
        }
        else {
            if ($transaksiIds > 0) {
                $this->db->where("id", $transaksiIds);
            }
        }

        $vars_0 = $this->lookupMainTransaksi()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }


        return $vars;
    }

    /* ---------------------------------------
* untuk setelment
* ---------------------------------------*/
    public function callMyTransaksi($myId, $cabangId)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }

        $selecteds = array(
            "id",
            "oleh_nama",
            "jenis_label",
            "customers_nama",
            "jenis",
            "jenis_master",
            "indexing_registry",
            // "merek_nama",
            // "model_nama",
            // "type_nama",
            // "tahun",
            // "lokasi_nama",
            // "satuan",
        );
        $this->db->select($selecteds);
        $condites = array(
            "oleh_id" => $myId,
            "cabang_id" => $cabangId,
            //            "settlement_id " => "0",
        );
        $this->db->where($condites);

        $vars_0 = $this->lookupMainTransaksi()->result();
        // showLast_query("orange");
        $vars = array();
        foreach ($vars_0 as $item) {
            $vars[$item->id] = $item;
        }


        return $vars;
    }

    /*---design for opname--*/
    public function callGantunganTransaksi($blacklist_jenis = false)
    {
        if (!isset($this->prefix) || ($this->prefix == NULL)) {
            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
            mati_disini($msg);
        }

        $tb_transaksi = $this->tableNames[$this->prefix]['main'];
        $tb_data = $this->tableNames[$this->prefix]['detail'];
        /* ------------------------------------------------------------------------
         * $blacklist_jenis ::
         * untuk memblacklist berdasar kolom jenis, sehingga tidak akan masuk query
         * true :: untuk mengunakan default blaklist untuk opname
         * false :: seluruh jenis transaksi dipanggil
         * ------------------------------------------------------------------------*/
        if ($blacklist_jenis == true) {
            /*-----untuk bloking transaksi saat stok opname----*/
            $unjenis = array(
                // "461",
                "110e",
                "110r",
                "1337r",
                "1463",
                "1463o",
                "1463r",
                "1674r",
                "1675r",
                "1677r",
                "1757r",
                "1763r",
                "1960",
                "2675r",
                "2676r",
                "2677r",
                "3461r",
                "3463",
                "3463o",
                "3463ro",
                "3465r",
                "4449r",
                "4464r",
                "446r",
                "460a",
                "460r",
                "461",
                "461r",
                "461ro",
                "463o",
                "463ro",
                "464r",
                "466",
                "466r",
                "467",
                "582so",
                "582spo",
                "588so",
                "588spd",
                "671r",
                "672r",
                "675r",
                "676r",
                "677r",
                "681r",
                "758r",
                "763r",
                "7758r",
                "8786r",
                "8787r",
                "8788r",
                //--
                "1119r",
                "2229r",
                "1118r",
                "2228r",
                "2227r",
                "3339r",
                "5559r",
                "1119ro",
                "2229ro",
                "1118ro",
                "2228ro",
                "2227ro",
                "3339ro",
                "5559ro",
                "117r",
                "4466r",
                "5681r",
                // "960r", // return import
            );
            $this->db->where_not_in("jenis", $unjenis);
        }
        elseif (is_array($blacklist_jenis)) {
            $this->db->where_not_in("jenis", $blacklist_jenis);
        }

        $koloms = array(
            "$tb_transaksi.id",
            "$tb_transaksi.jenis_master",
            "$tb_transaksi.jenis",
            "$tb_transaksi.jenis_label",
            "$tb_transaksi.nomer",
            "$tb_transaksi.dtime",
            "$tb_transaksi.oleh_nama",
            "$tb_transaksi.cabang_nama",
            "$tb_transaksi.next_step_num",
            "$tb_transaksi.next_step_code",
            // "$tb_transaksi.trash2",
            // "$tb_transaksi.trash_4",
            // "$tb_data.valid_qty",
        );

        $this->db->select($koloms);
        // $this->db->limit(5);
        $join_condites = array(
            // $tb_data.".transaksi_id" => $tb_transaksi.".id",
            "$tb_transaksi.link_id" => 0,
            "$tb_transaksi.status" => 1,
            "$tb_transaksi.trash" => 0,
            "$tb_transaksi.trash2" => 0,
            "$tb_transaksi.trash_4" => 0,
            "$tb_transaksi.div_id" => 18,
            "$tb_data.valid_qty >" => 0,
            "$tb_data.next_substep_code !=" => "",
            "$tb_data.sub_step_number >" => "0",
        );
        $this->db->where($join_condites);
        // $this->db->group_by("$tb_transaksi.id");
        $this->db->group_by("$tb_transaksi.id,$tb_data.next_substep_code");
        $this->db->join($tb_data, $tb_data . ".transaksi_id = " . $tb_transaksi . ".id");
        // $this->db->join($tb_data, $join_condites);
        $src_tr_gantung = $this->db->get($tb_transaksi)->result();
        // showLast_query("hijau");
        // cekLime(sizeof($src_tr_gantung));
        // arrPrintHijau($src_tr_gantung);

        $jenis_gantung = array();
        foreach ($src_tr_gantung as $src_item) {
            $jenis = $src_item->jenis;
            $tr_id = $src_item->id;
            // $jenis_gantung[$jenis][] = $tr_id;
            $jenis_gantung[$jenis][] = $src_item;
        }

        return $jenis_gantung;
    }

//    public function lookupAll()
//    {
//        if (!isset($this->prefix) || ($this->prefix == NULL)) {
//            $msg = "prefix tabel harus disett dahulu dengan setPrefix(). code: " . __LINE__;
//            mati_disini($msg);
//        }
//        $criteria = array();
//        $criteria2 = "";
//        if (sizeof($this->filters) > 0) {
//            $this->fetchCriteria();
//            $criteria = $this->getCriteria();
//            $criteria2 = $this->getCriteria2();
//        }
//        if (sizeof($criteria) > 0) {
//            $this->db->where($criteria);
//        }
//        if ($criteria2 != "") {
//            $this->db->where($criteria2);
//        }
//        $this->db->select("*");
//        return $this->db->get($this->tableNames[$this->prefix]);
//    }
}