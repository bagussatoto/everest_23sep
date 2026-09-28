<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/6/2019
 * Time: 8:39 PM
 */
class DataBase extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
    }

    public function TambahKolom(){

        $tables_0 = $this->db->list_tables();
        $tables = $filtered_tables = preg_grep('/^_/', $tables_0);

        cekBiru($tables);

        $this->load->dbforge();

        $field_name = "dtime_2";
        $fields = array(
            $field_name => array(
                'type' => 'datetime',
                // 'constraint' => 'default',
                'null' => TRUE,
                'after' => 'dtime',
            )
        );

        $no = 0;
        foreach ($tables as $table) {
            if (!$this->db->field_exists($field_name, $table)) {
                $no++;

                $this->dbforge->add_column($table, $fields);
            }
            showLast_query("merah");
        }
        matiHere(__LINE__ . "  DONE $no table");
    }

    public function RenameProduk(){

    }

    /*
     * UPDATE produk_per_serialnumber AS pp
JOIN produk AS p ON pp.produk_id = p.id
SET pp.produk_sku_part_nama = p.outdoor_sku
WHERE pp.produk_sku_label = 'OT' AND pp.produk_sku_part_nama != p.outdoor_sku;

UPDATE produk_per_serialnumber AS pp
JOIN produk AS p ON pp.produk_id = p.id
SET pp.produk_sku_part_nama = p.indoor_sku_1
WHERE pp.produk_sku_label = 'IN' AND pp.produk_sku_part_nama != p.indoor_sku_1 AND (p.indoor_sku_2 IS  NULL OR p.indoor_sku_2!='');

SELECT tbl_1.id,tbl_1.indoor_sku_1,tbl_2.produk_sku_part_nama, tbl_2.produk_sku_label,tbl_1.indoor_sku_2,tbl_1.indoor_sku_3
FROM produk AS tbl_1
JOIN produk_per_serialnumber AS tbl_2
ON tbl_1.id = tbl_2.produk_id
WHERE tbl_1.indoor_sku_1 != tbl_2.produk_sku_part_nama AND tbl_2.produk_sku_label = 'IN' AND tbl_1.trash=0;
     */

}