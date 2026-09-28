<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 9/17/2018
 * Time: 4:37 PM
 */
class MdlTransaksi extends MdlMother
{
    protected $tableName = "transaksi";
    protected $filters = array(
        "transaksi.status='1'",
        "transaksi.trash='0'",
        "transaksi.link_id='0'",
    );
    protected $tableNames = array(
        "tmp" => "transaksi_tmpcart",
        "main" => "transaksi",
        "detail" => "transaksi_data",
        "sub_detail" => "transaksi_data_items",
        "items3_sum" => "transaksi_data_items3_sum",
        "mainFields" => "transaksi_fields",
        "detailFields" => "transaksi_data_fields",
        "mainValues" => "transaksi_values",
        "detailValues" => "transaksi_data_values",
        "applets" => "transaksi_applets",
        "sign" => "transaksi_sign",
        "paymentSrc" => "transaksi_payment_source",
        "paymentPembantuSrc" => "transaksi_payment_source_pembantu",
        "paymentAntiSrc" => "transaksi_payment_antisource",
        "registry" => "transaksi_registry",
        "dataRegistry" => "transaksi_data_registry",
        "elements" => "transaksi_element",
        "extras" => "transaksi_extstep",
        "dueDate" => "transaksi_due_date",
        "uangMuka" => "transaksi_uang_muka_source",
        "efaktur" => "transaksi_efaktur",
        "garansi" => "transaksi_data_garansi",
    );
    private $fields = array(
        "tmp" => array(
            "id",
            "jenis",
            "content",
//            "content_intext",
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
//            "ids_ref_intext",
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
            "nomer_new",
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
            "ppn_nilai",
            "transaksi_net",

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
            "deskripsi",

            "seller_id",
            "seller_nama",
            "top",
            "top_nama",
            "tos",
            "tos_nama",
            "referensi_id",
            "referensi_nomer",
            "extern_nomer",
            "referensi_jenis",
            "indexing_details",
            "indexing_sub_details",
            "indexing_items3_sum",
            "indexing_registry",
            "cancel_packing_source_id",
            //-----------
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
            "company_id",
            "project_id",
            "project_nama",
            "modul",
            "subModul",
            //----
            "reference_jenis",
            "reference_id",
            "reference_nomer",
            "reference_id_top",
            "reference_nomer_top",
            "reference_jenis_top",
            "reference_jenis_master",
            //----
            "pengirim_id",
            "pengirim_nama",
            "kirim_metode_id",
            "kirim_metode_nama",
            "cli",
            //--------------------
            "salesman_id",
            "salesman_nama",
            "gudang_status_id",
            "gudang_status_nama",
            "gudang_status_jenis",
            //--------------------
            "reference_cabang_id",
            "reference_cabang_nama",
            "reference_gudang_id",
            "reference_gudang_nama",
            "reference_terima_barang",

            "returns",
            "gunggungan_mode",
            "efaktur",
            "efaktur_dtime",

            "reference_id",
            "reference_nomer",
            "status_diskon",

            "date_transaksi_bank",
            "nomer_referensi_bank",
            "nomer_rekening_asal",
            "nama_rekening_asal",

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
            "produk_hrg_ori",
            "ppn",
            "produk_ord_diskon",
            "produk_ord_diskon_persen",
            "produk_ord_diterima",
            "produk_ord_kurang",
            "produk_ord_dibeli",
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

            "sub_pihak_id",
            "sub_pihak_nama",
            "sub_cabang_id",
            "sub_cabang_nama",
            "sub_referensi_id_1",
            "sub_referensi_nama_1",
            "sub_referensi_id_2",
            "sub_referensi_nama_2",
            "sub_referensi_id_3",
            "sub_referensi_nama_3",
            "sub_referensi_id_4",
            "sub_referensi_nama_4",
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
        "applets" => array(
            "transaksi_id",
            "mdl_name",
            "key",
            "label",
            "description",

        ),
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
            "nomer_top",
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
            "extern3_id",
            "extern3_nama",
            "extern4_id",
            "extern4_nama",
            "extern5_id",
            "extern5_nama",
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
            //-----
            "biaya_rekening",
            "biaya_rekening_label",
            "biaya_rekening_id",
            "biaya_rekening_id_label",
            "biaya_rekening2_id",
            "biaya_rekening2_id_label",
            "cabang2_id",
            "cabang2_nama",
            "payment_source_keterangan",
        ),
        "paymentPembantuSrc" => array(
            "id",
            "_key",
            "jenis",
            "target_jenis",
            "reference_jenis",
            "transaksi_id",
            "extern_id",
            "extern_nama",
            "nomer",
            "nomer_top",
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
            "produk_id",
            "produk_nama",
            "extern2_id",
            "extern2_nama",
            "extern3_id",
            "extern3_nama",
            "extern4_id",
            "extern4_nama",
            "extern5_id",
            "extern5_nama",
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
            //-----
            "biaya_rekening",
            "biaya_rekening_label",
            "biaya_rekening_id",
            "biaya_rekening_id_label",
            "biaya_rekening2_id",
            "biaya_rekening2_id_label",
            "cabang2_id",
            "cabang2_nama",
            "payment_source_keterangan",
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
            "extern2_id",
            "extern2_nama",
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
            "project_id",
            "project_nama",
        ),
        "dataRegistry" => array(
            "transaksi_id",
            "main",
            "items",
            "items2",
            "items2_sum",
            "itemSrc",
            "itemSrc_sum",
            "itemsSrc1",
            "itemsSrc1_sum",
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
            "items5",
            "items5_sum",
            "items6_sum",
            "items6",
            "items7_sum",
            "items7",
            "items8_sum",
            "items9_sum",
            "items10_sum",
            "itemsTarget1",
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
    protected $cekPrevalue;
    protected $registryFields = array(
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
        "main_add_values",
        "main_add_fields",
        "main_elements",
        "main_inputs",
        "main_inputs_orig",
        "receiptDetailFields",
        "receiptSumFields",
        "receiptDetailFields2",
        "receiptDetailSrcFields",
        "receiptDetailFields2",
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
        "items6",
        "items7",
        "items7_sum",
        "items8_sum",
        "items9_sum",
        "items10_sum",
        "rsltItems3_sub",
        "rsltItems_revert",
        "rsltItems2_revert",
        "requiredParam",
        "componentsBuilder",
        "items_elements",
        "itemPrice_sum",
        "mainOriginal",
        "itemsOriginal",
        "coreBuilder",
        "diskon_event",
        "cashback_event",
    );

    public function getRegistryFields()
    {
        return $this->registryFields;
    }

    public function setRegistryFields($registryFields)
    {
        $this->registryFields = $registryFields;
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

    private $keyWord;

    //region getter-setter
    public function getKeyWord()
    {
        return $this->keyWord;
    }

    public function setKeyWord($keyWord)
    {
        $this->keyWord = $keyWord;
    }

    public function __construct()
    {
        parent::__construct();
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

    public function getCekPrevalue()
    {
        $this->addFilter("id=" . $this->cekPrevalue);
        $result = array();
        $localFilters = array();
        if (sizeof($this->filters) > 0) {
            foreach ($this->filters as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
        $query = $this->db->select()
            ->from($this->tableName)
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();

        return $tmp;
    }

    public function setCekPrevalue($cekPrevalue)
    {
        $this->cekPrevalue = $cekPrevalue;
    }

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
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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
            $cab = isset($array['cabang_id']) ? $array['cabang_id'] : NULL;
            $gud = isset($array['gudang_id']) ? $array['gudang_id'] : NULL;
            if ($cab != NULL) {

                $this->db->group_start();
                $this->db->where(array("transaksi.cabang_id" => $cab));
                $this->db->or_where(array("transaksi.cabang2_id" => $cab));
                $this->db->group_end();
            }
            if ($gud != NULL) {

                $this->db->group_start();
                $this->db->where(array("gudang_id" => $gud));
                $this->db->or_where(array("gudang2_id" => $gud));
                $this->db->group_end();
            }
        }

        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array(
                "transaksi.suppliers_nama",
                "transaksi.customers_nama",
                "transaksi.oleh_nama",
                "transaksi.nomer_top",
                "transaksi.nomer2",
                "transaksi.nomer"
            ));
        }

        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id, transaksi.dtime as dtime");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(100);
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id " and $this->tableNames['detail'] . ".trash =0 ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        $result = $this->db->get($this->tableNames['main']);
        //        echo($this->db->last_query());
        return $result;
    }

    public function lookupRecentUndoneEntries_joinedCount($array)
    {
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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
        if (sizeof($array) > 0) {
            $cab = isset($array['cabang_id']) ? $array['cabang_id'] : null;
            $gud = isset($array['gudang_id']) ? $array['gudang_id'] : null;
            if ($cab != null) {
                $this->db->group_start();
                $this->db->where(array("transaksi.cabang_id" => $cab));
                $this->db->or_where(array("transaksi.cabang2_id" => $cab));
                $this->db->group_end();
            }
            if ($gud != null) {
                $this->db->group_start();
                $this->db->where(array("gudang_id" => $gud));
                $this->db->or_where(array("gudang2_id" => $gud));
                $this->db->group_end();
            }
        }
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array(
                "transaksi.suppliers_nama",
                "transaksi.customers_nama",
                "transaksi.oleh_nama",
                "transaksi.nomer_top",
                "transaksi.nomer2",
                "transaksi.nomer"
            ));
        }
        $detailTable = $this->tableNames['detail'];
        $countExpr = "COUNT(DISTINCT CONCAT(IFNULL($detailTable.transaksi_id,''),'|',IFNULL($detailTable.next_substep_code,''))) as totalnum";
        $this->db->select($countExpr, false);
        $this->db->join($detailTable, $detailTable . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main'])->result();
        return (sizeof($result) > 0) ? intval($result[0]->totalnum) : 0;
    }

    public function lookupRecentUndoneEntries_joinedCountByStep($array)
    {
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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
        if (sizeof($array) > 0) {
            $cab = isset($array['cabang_id']) ? $array['cabang_id'] : null;
            $gud = isset($array['gudang_id']) ? $array['gudang_id'] : null;
            if ($cab != null) {
                $this->db->group_start();
                $this->db->where(array("transaksi.cabang_id" => $cab));
                $this->db->or_where(array("transaksi.cabang2_id" => $cab));
                $this->db->group_end();
            }
            if ($gud != null) {
                $this->db->group_start();
                $this->db->where(array("gudang_id" => $gud));
                $this->db->or_where(array("gudang2_id" => $gud));
                $this->db->group_end();
            }
        }
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array(
                "transaksi.suppliers_nama",
                "transaksi.customers_nama",
                "transaksi.oleh_nama",
                "transaksi.nomer_top",
                "transaksi.nomer2",
                "transaksi.nomer"
            ));
        }
        $detailTable = $this->tableNames['detail'];
        $countExpr = "COUNT(DISTINCT CONCAT(IFNULL($detailTable.transaksi_id,''),'|',IFNULL($detailTable.next_substep_code,''))) as totalnum";
        $this->db->select("$detailTable.sub_step_number as sub_step_number, $countExpr", false);
        $this->db->join($detailTable, $detailTable . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $this->db->group_by(array("$detailTable.sub_step_number"));
        return $this->db->get($this->tableNames['main'])->result();
    }

    public function lookupRecentUndoneEntries_joinedPaged($array, $limit = 200, $offset = 0, $sortMode = "desc")
    {
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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
        if (sizeof($array) > 0) {
            $cab = isset($array['cabang_id']) ? $array['cabang_id'] : null;
            $gud = isset($array['gudang_id']) ? $array['gudang_id'] : null;
            if ($cab != null) {
                $this->db->group_start();
                $this->db->where(array("transaksi.cabang_id" => $cab));
                $this->db->or_where(array("transaksi.cabang2_id" => $cab));
                $this->db->group_end();
            }
            if ($gud != null) {
                $this->db->group_start();
                $this->db->where(array("gudang_id" => $gud));
                $this->db->or_where(array("gudang2_id" => $gud));
                $this->db->group_end();
            }
        }
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array(
                "transaksi.suppliers_nama",
                "transaksi.customers_nama",
                "transaksi.oleh_nama",
                "transaksi.nomer_top",
                "transaksi.nomer2",
                "transaksi.nomer"
            ));
        }
        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id, transaksi.dtime as dtime");
        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $sortMode = strtolower($sortMode) == "asc" ? "asc" : "desc";
        $this->db->order_by($this->tableNames['main'] . ".id", $sortMode);
        if (intval($limit) > 0) {
            $this->db->limit(intval($limit), intval($offset));
        }
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupUndoneItemAll_joined($array)
    {//fungsi join limited hanya mengambil kolom yang diperlukan karena hanya untuk validasi produk amsih aktif di transaksi
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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


        $this->db->select("transaksi.id as trid,transaksi.nomer as nomer,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id, transaksi.dtime as dtime,transaksi_data.transaksi_id as transaksi_id,transaksi_data.produk_id as produk_id,transaksi_data.produk_nama as produk_nama,transaksi_data.produk_ord_jml as produk_ord_jml,transaksi_data.valid_qty as valid_qty");
        // $this->db->from($this->tableNames['main']);
        //        $this->db->limit(100);
//        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->group_by(array("produk_id"));
//        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id " and $this->tableNames['detail'] . ".trash =0 ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        $result = $this->db->get($this->tableNames['main']);
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

    public function lookupUndoneEntries_joined($array, $custom_select = "*,")
    {
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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
            $this->db->where(array("transaksi.cabang_id" => $cab));
            $this->db->or_where(array("transaksi.cabang2_id" => $cab));
            $this->db->group_end();

            $this->db->group_start();
            $this->db->where(array("transaksi.gudang_id" => $gud));
            $this->db->or_where(array("transaksi.gudang2_id" => $gud));
            $this->db->group_end();
        }

        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array("transaksi.customers_nama", "transaksi.oleh_nama", "transaksi.suppliers_nama"));
        }

        $this->db->select($custom_select . "transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id,transaksi.dtime as dtime");

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

    public function lookupRecentUndoneEntriesNoGroup_joined($array)
    {
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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
        if (sizeof($array) > 0) {
            $cab = isset($array['cabang_id']) ? $array['cabang_id'] : NULL;
            $gud = isset($array['gudang_id']) ? $array['gudang_id'] : NULL;
            if ($cab != NULL) {

                $this->db->group_start();
                $this->db->where(array("transaksi.cabang_id" => $cab));
                $this->db->or_where(array("transaksi.cabang2_id" => $cab));
                $this->db->group_end();
            }
            if ($gud != NULL) {

                $this->db->group_start();
                $this->db->where(array("gudang_id" => $gud));
                $this->db->or_where(array("gudang2_id" => $gud));
                $this->db->group_end();
            }
        }
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array(
                "transaksi.customers_nama",
                "transaksi.oleh_nama",
                "transaksi.nomer_top",
                "transaksi.nomer2"
            ));
        }

        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id, transaksi.dtime as dtime");
//        $this->db->group_by(array("transaksi_id", "next_substep_code"));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);
        return $result;
    }


    public function lookupRecentUndoneEntries_joinedAsset($array)
    {
        $criteria = array();
        $criteria2 = "";
        //        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");


        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id " and $this->tableNames['detail'] . ".trash =0 ");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and ((gudang_id<>gudang2_id and gudang2_id='$gud') or (gudang_id=gudang2_id and gudang_id='$gud'))");
        $result = $this->db->get($this->tableNames['main']);
        //        echo($this->db->last_query());
        return $result;
    }

    //--menghasilkan beberapa baris entri  sesuai jumlah, limit, dan nomor halaman
    public function lookupHistories($jmlData, $limit, $page)
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

        // $jmlData=$this->lookupHistoryCount();
        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";

            $this->createSmartSearch($key, array("customers_nama", "oleh_nama", "suppliers_nama", "nomer"));
        }

        $numPages = ceil($jmlData / $limit);
        // $page = $_GET[page] ? $_GET[page] : 1;
        $offset = ($page - 1) * $limit;

        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id,transaksi.id as tid");
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");

        return $this->db->get($this->tableNames['main']);
    }

    public function lookupHistories4Api($jmlData, $limit, $page)
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
        $numPages = ceil($jmlData / $limit);
        $offset = ($page - 1) * $limit;
        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.id as tid");
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupHistoriesDtAll()
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
        $this->db->order_by("id", "desc");
        $rslt = $this->db->get($this->tableNames['main'])->num_rows();
        return ($rslt);
    }

    public function lookupHistoriesDtFil($jmlData, $limit, $length, $historyFields)
    {
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
        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.id as tid");
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");
        return $this->db->get($this->tableNames['main'])->num_rows();
    }

    public function lookupHistoriesDt($jmlData, $limit, $length, $historyFields)
    {
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
        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.id as tid");
        $this->db->limit($length, $limit);
        //jika ada perintah sorting
        if (isset($_GET['order'][0]['column']) && $_GET['order'][0]['column'] != '') {
            $column = isset($arrAvailColumn[$_GET['order'][0]['column']]) ? $arrAvailColumn[$_GET['order'][0]['column']] : "id";
            $sortMode = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : "desc";
            $this->db->order_by($this->tableNames['main'] . ".$column", "$sortMode");
        }
        else {
            $this->db->order_by($this->tableNames['main'] . ".id", "desc");
        }
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupHistories_joined($jmlData, $limit, $page)
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

        $numPages = ceil($jmlData / $limit);
        $offset = ($page - 1) * $limit;

        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");

        $this->db->group_start();
        $this->db->where(array("transaksi.cabang_id" => $this->session->login['cabang_id']));
        $this->db->or_where(array("transaksi.cabang2_id" => $this->session->login['cabang_id']));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
        $this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
        $this->db->group_end();


        $this->db->limit($limit, $offset);
        $this->db->order_by("transaksi.id", "desc");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupHistories_joined_all()
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
        //        $this->db->limit($limit, $offset);

        $this->db->group_start();
        $this->db->where(array("transaksi.cabang_id" => $this->session->login['cabang_id']));
        $this->db->or_where(array("transaksi.cabang2_id" => $this->session->login['cabang_id']));
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
        $this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
        $this->db->group_end();

        $this->db->order_by("transaksi.id", "desc");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupHistoryCount()
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
        $this->db->order_by("id", "desc");
        $rslt = $this->db->get($this->tableNames['main'])->num_rows();
        return ($rslt);
    }

    public function lookupHistoryCount_joined()
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
        $this->db->order_by("transaksi.id", "desc");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $rslt = $this->db->get($this->tableNames['main'])->num_rows();
        return ($rslt);
    }

    public function lookupMainTransaksi()
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
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupDetailTransaksi($transaksi_id)
    {
        if (is_array($transaksi_id)) {
            $this->addFilter("transaksi_id in ('" . implode("','", $transaksi_id) . "')");
        }
        else {
            $this->addFilter("transaksi_id='$transaksi_id'");
//            $criteria = array(
//                "transaksi_id" => $transaksi_id,
//                "produk_jenis" => "produk",
//                "trash" => "0",
//            );
        }
        $this->addFilter("trash='0'");
        $this->addFilter("valid_qty >'0'");
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
        $temp = $this->db->get($this->tableNames['detail'])->result();
        $hasil = array();
        if (count($temp) > 0) {
            foreach ($temp as $temp_0) {
                $hasil[$temp_0->transaksi_id][] = $temp_0;
            }
        }
        return $hasil;
    }

    public function lookupDetailTransaksiAll($transaksi_id)
    {
        if (is_array($transaksi_id)) {
            $this->addFilter("transaksi_id in ('" . implode("','", $transaksi_id) . "')");
        }
        else {
            $this->addFilter("transaksi_id='$transaksi_id'");
        }
        $this->addFilter("trash='0'");
//        $this->addFilter("valid_qty >'0'");
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
        $temp = $this->db->get($this->tableNames['detail'])->result();
        $hasil = array();
        if (count($temp) > 0) {
            foreach ($temp as $temp_0) {
                $hasil[$temp_0->transaksi_id][] = $temp_0;
            }
        }
        return $hasil;
    }

    public function lookupDetailTransaksiNoJenis($transaksi_id)
    {
        $criteria = array(
            "transaksi_id" => $transaksi_id,
            //            "produk_jenis" => "produk",
            "trash" => "0",
        );

        return $this->db->get_where($this->tableNames['detail'], $criteria);


        die();
    }

    public function lookupJoinedByID($id)
    {
        $this->db->select("*,transaksi.id as tid");

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

        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and " . $this->tableNames['main'] . ".id='$id'");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupJoinedByID__($id)
    {
        $this->db->select("*,transaksi.id as tid");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and " . $this->tableNames['main'] . ".id='$id'");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupJoinedInspectionByMasterID($id)
    {
        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and " . $this->tableNames['main'] . ".id_master='$id'");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupJoinedByReceiptNO($id)
    {
        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and nomer='$id'");
        //        $this->db->join($this->tableNames['mainValues'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and nomer='$id'");
        //        $this->db->join($this->tableNames['detailValues'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id and nomer='$id'");
        $result = $this->db->get($this->tableNames['main']);
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupJoined_OLD()
    {
        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.seller_id as seller_id,transaksi.seller_nama as seller_nama,transaksi.cabang_id as cabang_id,transaksi.dtime as dtime");
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";

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

        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id");
        return $this->db->get($this->tableNames['main']);
    }

    public function lookupJoined()
    {
        // $this->load->model("Mdls/MdlCountry");
        // $mc = new MdlCountry();
        // arrPrint($mc->getStaticData());
        // mati_disini();
        // $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama");
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

        // $this->db->limit(1000);
//        arrPrint($criteria);
//        arrPrint($criteria2);
//        if (ipadd() == "202.65.117.72") {
//            cekMerah(__LINE__);
//            mati_disini(__FUNCTION__ . "  TRANSAKSI BERHASIL (ON MODE UNDER MAINTENANCE)  <br> silahkan tutup browser terlebih dahulu. <br>execute in " . $val . " ms. code: " . __LINE__);
//        }
        $main = $this->db->get($this->tableNames['main'])->result();
        // arrPrint($main);
//                cekBiru($this->db->last_query());
        //        arrPrintPink($this->getFields()["main"]);
        //         arrprint($main);
//        cekHitam(sizeof($main));
//        matiHere();
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

                $itemTmp = $this->db->get($this->tableNames['detail'])->result();

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

        $main = $this->db->get($this->tableNames['main'])->result();
        //cekLime($this->db->last_query());
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
                arrPrint($idexingDetail);
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
                $itemTmp = $this->db->get($this->tableNames['sub_detail'])->result();
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
//        else{
//            cekHitam(":: KOSONG ::");
//        }

    }

    public function lookupJoinedSubItems2()
    {

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

        $main = $this->db->get($this->tableNames['main'])->result();
        //cekLime($this->db->last_query());
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
                $itemTmp = $this->db->get($this->tableNames['items3_sum'])->result();
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
        $this->db->select("*");
        $result = $this->db->get_where($this->tableNames['mainValues'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupMainValues()
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
        $result = $this->db->get($this->tableNames['mainValues']);
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupDetailValuesByTransID($id)
    {
        $this->db->select("*");
        $result = $this->db->get_where($this->tableNames['detailValues'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupDates()
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

        // $jmlData=$this->lookupHistoryCount();


        $this->db->select("fulldate");
        $this->db->group_by("fulldate");
        $this->db->order_by("fulldate");
        // $this->db->from($this->tableNames['main']);

        $tmp = $this->db->get($this->tableNames['main'])->result();
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


        $this->removeFilter("transaksi.link_id='0'");
        $this->addFilter("transaksi.link_id>'0'");
        //        $this->addFilter("transaksi.link_id='$id'");
        $this->addFilter("transaksi.id_master='$id'");
        $criteria = array();
        $criteria2 = "";
        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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

        //        $numPages = ceil($jmlData / $limit);
        //        $offset = ($page - 1) * $limit;

        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id,transaksi.dtime as dtime");

//        $this->db->group_start();
//        $this->db->where(array("transaksi.cabang_id" => $this->session->login['cabang_id']));
//        $this->db->or_where(array("transaksi.cabang2_id" => $this->session->login['cabang_id']));
//        $this->db->group_end();
//
//        $this->db->group_start();
//        $this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
//        $this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
//        $this->db->group_end();


        //        $this->db->limit($limit, $offset);
        $this->db->order_by("transaksi.id", "asc");
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);


        //        arrPrint($result->result());
        return $result;
    }

    public function lookupEntryPoints($id)
    {


        $this->removeFilter("transaksi.link_id='0'");
        $this->addFilter("transaksi.link_id>'0'");
        //        $this->addFilter("transaksi.link_id='$id'");
        $this->addFilter("transaksi.id_master='$id'");
        $this->addFilter("transaksi.trash='0'");
        $criteria = array();
        $criteria2 = "";
        //        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
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

        //        $numPages = ceil($jmlData / $limit);
        //        $offset = ($page - 1) * $limit;

        $this->db->select("*,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id");

//        $this->db->group_start();
//        $this->db->where(array("transaksi.cabang_id" => $this->session->login['cabang_id']));
//        $this->db->or_where(array("transaksi.cabang2_id" => $this->session->login['cabang_id']));
//        $this->db->group_end();
//
//        $this->db->group_start();
//        $this->db->where(array("gudang_id" => $this->session->login['gudang_id']));
//        $this->db->or_where(array("gudang2_id" => $this->session->login['gudang_id']));
//        $this->db->group_end();


        //        $this->db->limit($limit, $offset);
        $this->db->order_by("transaksi.id", "asc");
        //        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);


        //        arrPrint($result->result());
        return $result;
    }

    //  write temporary entries
    public function writeTmpEntries($params)
    {
        if (is_array($params)) {
            if (sizeof($params) > 0) {

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
                $this->db->insert($this->tableNames['tmp'], $data);

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

                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['main'])) {
                        $data[$fName] = $fValue;
                    }
                }
                $this->db->insert($this->tableNames['main'], $data);
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

                //  region transaksi main
                $data = array();
                foreach ($params as $fName => $fValue) {
                    if (in_array($fName, $this->fields['main'])) {
                        $data[$fName] = $fValue;
                    }
                }

                //                $this->db->insert($this->tableNames['main'], $data);
                //                $insertID=$this->db->insert_id();

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
                    //                    $this->writeMainEntries($epData);
                    //                    $insertID2=$this->db->insert_id();
                    //remove filter
                    $this->db->insert($this->tableNames['main'], $epData);
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

                $data = array();
                foreach ($this->fields['detail'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['detail'], $data);

                ////cekLime($this->db->last_query());

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

                $data = array();
                foreach ($this->fields['sub_detail'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['sub_detail'], $data);
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

                $data = array();
                foreach ($this->fields['items3_sum'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['items3_sum'], $data);
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
                $data = array();
                foreach ($this->fields['mainValues'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = "$isi";
//                    $data[$kolom] = ($isi == "INF") ? "": $isi;
                }
                $data['transaksi_id'] = $transaksi_id;
//arrPrintHijau($data);
                $this->db->insert($this->tableNames['mainValues'], $data);

                ////cekLime($this->db->last_query());

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

                $data = array();
                foreach ($this->fields['detailValues'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['detailValues'], $data);

                ////cekLime($this->db->last_query());

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
                $data = array();
                foreach ($this->fields['mainFields'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['mainFields'], $data);

                ////cekLime($this->db->last_query());

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

                $data = array();
                foreach ($this->fields['detailFields'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['detailFields'], $data);

                ////cekLime($this->db->last_query());

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
                $data = array();
                foreach ($this->fields['applets'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['applets'], $data);

                ////cekLime($this->db->last_query());

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
                $data = array();
                foreach ($this->fields['elements'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['elements'], $data);

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

    //
    //region extended steps
    public function writeExtStep($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {
                $data = array();
                foreach ($this->fields['extras'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";
                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['extras'], $data);

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
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames['extras'],
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
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames['extras'],
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
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames['extras'],
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
        $this->setTableName($this->getTableNames()['extras']);
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
        $this->setTableName($this->getTableNames()['extras']);
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
        $this->setTableName($this->getTableNames()['extras']);
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
        $this->db->select("*");

        $tmp = $this->db->get_where(
            $this->tableNames['extras'],
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

                $data = array();
                foreach ($this->fields['paymentSrc'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['paymentSrc'], $data);

                ////cekLime($this->db->last_query());

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
        $this->db->where($where);
        $this->db->update($this->tableNames['paymentSrc'], $data);
        return true;

    }

    public function writePaymentPembantuSrc($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['paymentSrc'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;
                unset($data['id']);
                $this->db->insert($this->tableNames['paymentPembantuSrc'], $data);

                ////cekLime($this->db->last_query());

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

    public function updatePaymentPembantuSrc($where, $data)
    {
        $this->db->where($where);
        $this->db->update($this->tableNames['paymentPembantuSrc'], $data);
        return true;

    }

    public function lookupPaymentSrcs($masterID, $jenis, $key = "")
    {
        $this->db->select("*");
        $where = array(
            "transaksi_id" => $masterID,
            "jenis" => $jenis,

        );
        if ($key != "") {
            $where["_key"] = $key;
        }
        $tmp = $this->db->get_where(
            $this->tableNames['paymentSrc'],
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
        $this->db->select("*");

        $tmp = $this->db->get_where($this->tableNames['paymentSrc'], array("transaksi_id" => $masterID, "target_jenis" => $iCode, "label" => $iLabel))->result();
        if (sizeof($tmp) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    public function paymentSrcRelasiMaster()
    {
        /*
         * lihat cara pakai di he_misc_helper (validateRelasiPaymentSrc)
         */
//        $this->db->select("*");
        $this->db->select("
        transaksi.id as id,
        transaksi_payment_source.transaksi_id as transksi_id,
        transaksi_payment_source.nomer as nomer,
        transaksi_payment_source.extern_id as extern_id,
        transaksi_payment_source.extern_nama as extern_nama,
        transaksi_payment_source.jenis as jenis,
        transaksi_payment_source.target_jenis as jenis_target,
        transaksi_payment_source.reference_jenis as reference_jenis,
        transaksi_payment_source.tagihan as tagihan,
        transaksi_payment_source.terbayar as terbayar,
        transaksi_payment_source.dihapus as dihapus,
        transaksi_payment_source.sisa as sisa,
        transaksi_payment_source.nomer_top as nomer_top,
        transaksi_payment_source.label as label,
        transaksi_payment_source.cabang_id as cabang_id,
        transaksi_payment_source.oleh_id as oleh_id,
        transaksi_payment_source.oleh_nama as oleh_nama,
        transaksi_payment_source.extern2_id as extern2_id,
        transaksi_payment_source.extern2_nama as extern2_nama,
        transaksi_payment_source.extern_label2 as extern_label2,
        transaksi_payment_source.pph_23 as pph_23,
        transaksi_payment_source.terbayar_pph23 as terbayar_pph23,
        transaksi_payment_source.valas_nilai as valas_nilai,
        transaksi_payment_source.id as id,
        transaksi_payment_source.id as tabel_id,
        transaksi_payment_source.project_id as project_id,
        transaksi_payment_source.project_nama as project_nama");
        //lookup join transaksi dulu buat dapetin GRN NYA
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
        $this->db->join($this->tableNames['paymentSrc'], $this->tableNames['paymentSrc'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main']);
//        $result = $this->db->get_where($this->tableNames['paymentSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
//        $tmp = $this->db->get_where($this->tableNames['paymentSrc'], array("transaksi_id" => $masterID, "target_jenis" => $iCode, "label" => $iLabel))->result();
        return $result;


    }

    public function lookupPaymentSrcByID($id)
    {
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
        $result = $this->db->get_where($this->tableNames['paymentSrc'], array("id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentSrcByTransID($id)
    {
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
        $result = $this->db->get_where($this->tableNames['paymentSrc'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentSrcByJenis($jenis)
    {

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
        $result = $this->db->get_where($this->tableNames['paymentSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }


    public function lookupPaymentSrcByJenis_joined($jenis, $project = false)
    {
        //        $this->db->select("*");
        $this->db->select("
        transaksi.*,
        transaksi_payment_source.*,
        transaksi_payment_source.valas_id as valas_id,
        transaksi_payment_source.valas_nama as valas_nama,
        transaksi_payment_source.extern_label2 as extern_label2,
        transaksi_payment_source.pph_23 as pph_23,
        transaksi_payment_source.terbayar_pph23 as terbayar_pph23,
        transaksi_payment_source.valas_nilai as valas_nilai,
        transaksi_payment_source.id as id,
        transaksi_payment_source.id as tabel_id,
        transaksi_payment_source.project_id as project_id,
        transaksi_payment_source.customers_id as payment_customer_id,
        transaksi_payment_source.customers_nama as payment_customer_nama,
        transaksi_payment_source.project_nama as project_nama,
        transaksi_payment_source.returned as returned");
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

        $this->db->join($this->tableNames['main'], $this->tableNames['paymentSrc'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");

        // Penambahan Join dan Filter Kondisional untuk Project
        if ($project == 1) {
            $joinCondition = "(" . 'project_produk' . ".quot_id = " . $this->tableNames['main'] . ".id ";
            $joinCondition .= "OR " . 'project_produk' . ".project_start_id = " . $this->tableNames['main'] . ".id)";
            $this->db->join('project_produk', $joinCondition);
            $this->db->where('project_produk.closing_status <', 99);
        }

        $result = $this->db->get_where($this->tableNames['paymentSrc'], array("target_jenis" => $jenis));
                cekMerah($this->db->last_query());
        return $result;
    }

    public function lookUpAllPaymentSrc()
    {
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
        $result = $this->db->get_where($this->tableNames['paymentSrc']);
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookUpPayment()
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
        $res = $this->db->get($this->tableNames['paymentSrc']);
        return $res;
    }

    //endregion

    //region payment-antisource
    public function writePaymentAntiSrc($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['paymentAntiSrc'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['paymentAntiSrc'], $data);

                //                //cekLime($this->db->last_query());

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
        $this->db->where($where);
        $this->db->update($this->tableNames['paymentAntiSrc'], $data);
        return true;

    }

    public function lookupPaymentAntiSrcs($masterID, $jenis, $key = "")
    {
        $this->db->select("*");
        $where = array(
            "transaksi_id" => $masterID,
            "jenis" => $jenis,

        );
        if ($key != "") {
            $where["_key"] = $key;
        }
        $tmp = $this->db->get_where(
            $this->tableNames['paymentAntiSrc'],
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
        $this->db->select("*");

        $tmp = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("transaksi_id" => $masterID, "target_jenis" => $iCode, "label" => $iLabel))->result();
        if (sizeof($tmp) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    public function lookupPaymentAntiSrcByTransID($id)
    {
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
        $result = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("transaksi_id" => $id));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentAntiSrcByJenis($jenis)
    {
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
        $result = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentAntiSrcByJenis_joined($jenis)
    {
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
        $this->db->join($this->tableNames['main'], $this->tableNames['paymentAntiSrc'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("target_jenis" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupPaymentAntiSrcByLabel($jenis)
    {
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
        $result = $this->db->get_where($this->tableNames['paymentAntiSrc'], array("label" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    //endregion

    //region uang muka-source
    public function writeUangMukaSrc($transaksi_id, $detailParams)
    {
        if (is_array($detailParams)) {
            if (sizeof($detailParams) > 0) {

                $data = array();
                foreach ($this->fields['uangMuka'] as $kolom) {
                    $isi = isset($detailParams[$kolom]) ? $detailParams[$kolom] : "";

                    $data[$kolom] = $isi;
                }
                $data['transaksi_id'] = $transaksi_id;

                $this->db->insert($this->tableNames['uangMuka'], $data);

                ////cekLime($this->db->last_query());

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
        $this->db->where($where);
        $this->db->update($this->tableNames['uangMuka'], $data);
        return true;

    }

    public function lookupUangMukaSrc($jenis)
    {// $jenis = customer/supplier/vendor
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
        $result = $this->db->get_where($this->tableNames['uangMuka'], array("extern_label2" => $jenis));
        //        cekMerah($this->db->last_query());
        return $result;
    }

    public function lookupAllUangMukaSrc($jenis)
    {// $jenis = customer/supplier/vendor
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
        $result = $this->db->get_where($this->tableNames['uangMuka']);
        //        cekMerah($this->db->last_query());
        return $result;
    }
    //endregion

    //
    //region signatures
    public function writeSignature($transaksi_id, $params)
    {
        if (is_array($params)) {
            $data = array();
            foreach ($params as $key => $value) {
                $data[$key] = $value;
            }
            $data['transaksi_id'] = $transaksi_id;
            $this->db->insert($this->tableNames['sign'], $data);
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
        return $this->db->get_where($this->tableNames['sign'], array("transaksi_id" => $id));
    }//--baca signature berdasarkan ID master

    public function lookupSignatureResumeByMasterID()
    {
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
//        return $this->db->get_where($this->tableNames['sign'], array("transaksi_id" => $id));
        return $this->db->get($this->tableNames['sign']);
    }//--baca signature berdasarkan ID master

    public function lookupSignatures($id_master)
    {
        // arrPrint($this->fields['main']);
        $this->db->select($this->fields['main']);
        $criteria = array(
            "link_id >" => 0,
            // "id_master" => $id_master
        );


        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        // $this->tableName$this->tableNames['main'];
        // $this->tableName('transaksi');
        // $this->setTableName('transakai');
        $this->db->order_by("id", "asc");
        $var = $this->db->get_where($this->tableNames['main'], array("id_master" => $id_master));

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
            foreach ($batchParams as $kolom => $values) {
                $batchParamsEncode[$kolom] = blobEncode($values);
            }
            $batchParamsEncode['transaksi_id'] = $insertID;
            $batchParamsEncode['dtime'] = date("Y-m-d H:i:s");
            $this->db->insert($this->tableNames['dataRegistry'], $batchParamsEncode);
            return $insertID;
        }
        else {
            return false;
        }

    }//--nulis registri transaksi

    public function writeDataRegistriesHistory($batchParams)
    {

        if (is_array($batchParams)) {
            //            foreach ($batchParams as $kolom => $values) {
            //                $batchParamsEncode[$kolom] = blobEncode($values);
            //            }
            //            $batchParamsEncode['transaksi_id'] = $insertID;
            $insertID = $this->db->insert($this->tableNames['registry'], $batchParams);

            return $insertID;
        }
        else {
            return false;
        }
    }//--nulis registri transaksi

    public function updateDataRegistry($where, $data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $dataEncode[$key] = blobEncode($val);
            }

            $this->db->where($where);
            $this->db->update($this->tableNames['dataRegistry'], $dataEncode);

            return true;
        }
        else {
            return false;
        }
    }

    public function lookupBaseDataRegistries($ids = "")
    {
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


        return $this->db->get($this->tableNames['dataRegistry']);
    }//--baca registri berdasarkan ID registri

    public function lookupDataRegistries()
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

        if (isset($this->jointSelectFields)) {

            $this->db->select($this->jointSelectFields);
        }
        else {
            $tmpExpl = implode(",", $this->getFields()["dataRegistry"]);
            $this->db->select($tmpExpl);
        }
        // $this->db->select("*");

        return $this->db->get($this->tableNames['dataRegistry']);
    }//--baca registri berdasarkan ID master

    public function lookupDataRegistriesByMasterID($id)
    {
        //        $criteria2 = array("trash" => "0");
        //        $this->db->where($criteria2);

        return $this->db->get_where($this->tableNames['dataRegistry'], array("transaksi_id" => $id));
    }//--baca registri berdasarkan ID master

    public function lookupDataRegistries_joined()
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
        $tmpExpl = implode(",", $this->getFields()["main"]);
        $this->db->select($tmpExpl);
        $main = $this->db->get($this->tableNames['main'])->result();
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
                $itemTmp = $this->db->get($this->tableNames['dataRegistry'])->result();
                // arrPrintPink($itemTmp);
                // //cekLime($this->db->last_query());
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
        $field_main = $this->fields['main'];
        $field_slave = $this->fields['dataRegistry'];

        $selectedFields = $allFields = array_merge($field_main, $field_slave);

        if (isset($this->blockFields)) {
            // cekBiru($this->blockFields);
            $selectedFields = array_diff($allFields, $this->blockFields);
        }
        // cekHijau(sizeof($selectedFields));
        $this->db->select($selectedFields);

        $this->filters[99] = $this->tableNames['detail'] . ".trash='0'";
        $tbl_main = $this->tableNames['main'];
        $tbl_slave = $this->tableNames['dataRegistry'];

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
            $data = array();
            foreach ($params as $key => $value) {
                $data[$key] = $value;
            }
            $data['transaksi_id'] = $insertID;
            $this->db->insert($this->tableNames['dueDate'], $data);
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
        $this->db->where($where);
        $this->db->update($this->tableNames['dueDate'], $data);
        return true;
    }

    public function lookupDueDate($id)
    {
        $this->db->select("due_date,transaksi_nilai,nomer,transaksi_id");
        $result = $this->db->get_where($this->tableNames['dueDate'], array("customers_id" => $id, "status" => "1", "trash" => "0"))->result();
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
        return $this->db->get($this->tableNames['dueDate']);
    }

    protected function lookupDueDateByField($fieldName, $values)
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

        $cleanValues = array();
        if (sizeof($values) > 0) {
            foreach ($values as $value) {
                $value = trim((string)$value);
                if ($value !== "") {
                    $cleanValues[$value] = $value;
                }
            }
        }

        $this->db->select("*");
        if (sizeof($cleanValues) == 0) {
            $this->db->where("id", "-99999");
            return $this->db->get($this->tableNames['dueDate']);
        }

        $this->db->where_in($fieldName, array_values($cleanValues));
        return $this->db->get($this->tableNames['dueDate']);
    }

    public function lookupDueDateByCustomerIds($customerIds)
    {
        return $this->lookupDueDateByField("customers_id", $customerIds);
    }

    public function lookupDueDateByTransaksiIds($transaksiIds)
    {
        return $this->lookupDueDateByField("transaksi_id", $transaksiIds);
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


        $condite = array(
            "r_jenis" => 1,
        );
        $this->db->where("jenis in('" . $jenis . "')");
        $datas = array(
            "r_jenis" => 0,
        );
        $this->updateData($condite, $datas);
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
        $result = $this->db->get_where($this->tableNames['efaktur'], array("id_master" => $id));

        return $result;
    }

    public function callSpecs($transaksiIds = "")
    {
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

        $vars_0 = $this->lookupAll()->result();
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

        $vars_0 = $this->lookupAll()->result();
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

        $tb_transaksi = "transaksi";
        $tb_data = "transaksi_data";
        /* ------------------------------------------------------------------------
         * $blacklist_jenis ::
         * untuk memblacklist berdasar kolom jenis, sehingga tidak akan masuk query
         * true :: untuk mengunakan default blaklist untuk opname
         * false :: seluruh jenis transaksi dipanggil
         * ------------------------------------------------------------------------*/
        if ($blacklist_jenis == true) {
            /*-----untuk bloking transaksi saat stok opname
             * yg masuk dlm array ini diperbolekan gantung
             * ----*/
            $unjenis = array(
                "759r",
                "758r",
                "758",
                "489",
                "487",
                "483",
                "462",
                "463o",
                "464",
                "464a",
                "4464",
                "4467",
                "4643",
                "4644",
                "465",
                "749",
                "7499",
                "3674r",
                "7761",
                "1424",
                "757r",
                "757",
                "1757r",
                "1757",
                "9467",
                "19467r",
                "19467",
                "7467",
                "1458",
                "1487",
                "1483",
                "742",
                "677r",
                "2677r",
                "2677",
                "1677r",
                "1674r",
                "8786r",
                "3344r",
                "675r",
                "2675r",
                "2675",
                "672r",
                "463ro",
                "588spo",
                "588st",
                "588so",

                "467", // grn
                "5822spo", // spo
                "5822so", // diskon test
                // "7762r", // supplier test
                "7762",
                "3465r",
                "8788r",
                "3463o",
                "466", // po
                "466r", // po
                "1466",
                "1466r",
                "461r",
                //--------opname
                "2228r",
                "2228ro",
                "2229ro",
                "2229r",
                "1119ro",
                "1119r",
                "1118r",
                "1118ro",
                //--------------^
                "110r",
                "110e",
                "111r",
                "115",
                "117r",
                "117",
                "1155r",
                "1155",
                "463",
                "3463",
                "1961", // tidak diketahui
                // "960r", // return import
                "424",// sewa
                "8789r",// aset sales
                "1674",// biaya gaji
                "11674",// biaya gaji cabang
                "21674r",// otorisasi biaya gaji cabang
                "21674",// otorisasi biaya gaji cabang
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
            "$tb_transaksi.status_4" => 11,
            "$tb_transaksi.trash" => 0,
            "$tb_transaksi.trash2" => 0,
            "$tb_transaksi.trash_4" => 0,
            "$tb_transaksi.div_id" => 18,
            "$tb_transaksi.next_step_num >" => 0,
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
        // //cekLime(sizeof($src_tr_gantung));
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

    public function lookupTransaksiData()
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

        return $this->db->get($this->tableNames['detail']);

    }

    public function callTransaksiCounterJenis($jenis)
    {

        $tbl_1 = "transaksi";
        $coloms = array(
            "id",
            "_company_stepCode",
            "_company_jenisTr",
        );
        $this->db->select($coloms);
        $wheres = array(
            // "jenis" => "4822",
            "jenis" => $jenis,
        );
        $this->db->where($wheres);
        $this->db->order_by("dtime", "asc");
        $srcs = $this->db->get($tbl_1)->result_array();

        foreach ($srcs as $src) {
            $tr_id = $src['id'];
            $sisa = $src['sisa'];

            $src_datas[$tr_id] = $src;
        }

        return $src_datas;
    }

    public function lookUpValidQty($trid){
        //cek status transksi dan trasnaksi data mengantisipasi doble exec
        $this->db->where('transaksi.id', $trid);
        $this->db->where('transaksi.status', '1');
        $this->db->where('transaksi.trash', '0');
        $this->db->where('transaksi.link_id', '0');
        $this->db->where('transaksi.div_id', '18');
        $this->db->where('transaksi_data.next_substep_code !=', '');
        $this->db->where('transaksi_data.sub_step_number >', '0');
        $this->db->where('transaksi_data.valid_qty >', '0');
        $this->db->where('transaksi_data.trash', '0');
        $this->db->select("transaksi.id as trid,transaksi.nomer as nomer,transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id, transaksi.dtime as dtime,transaksi_data.transaksi_id as transaksi_id,transaksi_data.produk_id as produk_id,transaksi_data.produk_nama as produk_nama,transaksi_data.produk_ord_jml as produk_ord_jml,transaksi_data.valid_qty as valid_qty");
        $this->db->group_by(array("produk_id"));
        $this->db->join($this->tableNames['detail'], $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id ");
        $result = $this->db->get($this->tableNames['main'])->result();
//        matiHere(__LINE__);
        $allow = "";
        if(count($result)>0){
            $allow=1;
        }
        else{
            $allow=0;
        }
        return $allow;
//        cekLime($this->db->last_query());
//        matiHere(__LINE__);
    }

    /**
     *untuk tambahan handling um project,termin akan memungkinkan yang lain juga menggunakan ini
     * untuk menghindari query n+1
     *
     */
    public function getProjectPaymentSummaryCI3($produkProjectPid) {
        if (!is_numeric($produkProjectPid)) {
            throw new InvalidArgumentException('Project ID must be numeric');
        }

        $tableName = $this->tableNames['paymentSrc'];

        $sql = "SELECT * FROM {$tableName} 
            WHERE project_id = ? 
            AND (
                (jenis = ? AND target_jenis = ?) 
                OR (jenis = ? AND target_jenis = ?) 
                OR (label = ? AND target_jenis = ?)
                OR (jenis IN (?, ?) AND target_jenis = ?)  -- TERMIN
            )
            ORDER BY id DESC";

        $params = [
            $produkProjectPid,                    // project_id
            '7499', '749',                       // terima bayar
            '588st', '7488',                    // retensi
            'uang muka konsumen', '04467',       // uang muka
            '588so', '588st', '7499'             // termin
        ];

        $query = $this->db->query($sql, $params);
//        cekHitam($this->db->last_query());

        if (!$query) {
            log_message('error', 'Database error: ' . $this->db->error()['message']);
            return [];
        }

        $results = $query->result_array();
        return $this->groupPaymentResults($results);
    }

    /**
     * HELPER METHOD UNTUK GROUPING RESULTS
     */
    private function groupPaymentResults($results) {
        $grouped = [
            'terimabayarproject' => [],
            'retensiproject' => [],
            'uangmukaproject' => [],
            'terminproject' => []  // TAMBAHKAN INI
        ];

        foreach ($results as $row) {
            // Logic grouping berdasarkan field yang ada
            if ($row['jenis'] === '7499' && $row['target_jenis'] === '749') {
                $grouped['terimabayarproject'][] = (object)$row;
            }
            elseif ($row['jenis'] === '588st' && $row['target_jenis'] === '7488') {
                $grouped['retensiproject'][] = (object)$row;
            }
            elseif ($row['label'] === 'uang muka konsumen' && $row['target_jenis'] === '04467') {
                $grouped['uangmukaproject'][] = (object)$row;
            }
            // TAMBAHKAN KONDISI UNTUK TERMIN
            elseif (in_array($row['jenis'], ['588so', '588st']) && $row['target_jenis'] === '7499') {
                $grouped['terminproject'][] = (object)$row;
            }
        }

        return $grouped;
    }
}

