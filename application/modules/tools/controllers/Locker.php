<?php

/**
 * Created by PhpStorm.
 * User: none
 * Date: 5/6/2023
 * Time: 8:39 PM
 */
class Locker extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
        // $this->db2 = $this->load->database('testing', TRUE);
    }

    public function cleansingProduk(){
        $tbl_2 = "_rek_pembantu_produk_cache";

        // $this->db->where("trash", "0");
        $produkData = $this->db->get($tbl_2)->result_array();
        showLast_query("hijau", count($produkData));
        foreach ($produkData as $produkDatum) {
            $sxtern_id = $produkDatum['extern_id'];

            $ygsudahdipakai[$sxtern_id] = $sxtern_id;
        }
cekHijau(count($ygsudahdipakai));
//         matiHere(__LINE__);

        $tbl_1 = "produk";

        $this->db->where("trash", "0");
        $produkData = $this->db->get($tbl_1)->result_array();
        showLast_query("hijau", count($produkData));

        foreach ($produkData as $item) {
            $id = $item['id'];
            $nama = $item['nama'];


            $produkDouble[$nama][] = array(
                'id' => $item['id'],
                'kode' => $item['kode'],
                'barcode' => $item['barcode'],
                'kategori' => $item['kategori_nama'],
            );
            $produkId[$id]= $item;
        }

        // cekHere(count($produkDouble));
        cekHere(count($produkId));

        $cocoks = (array_intersect_key($ygsudahdipakai, $produkId));

        arrPrintKuning(count($cocoks));
        // arrPrintKuning($produkDouble);
matiHere();
        foreach ($produkDouble as $namaItem => $dataItem) {
            if(count($dataItem) > 1){
                $ygDouble[$namaItem] = $dataItem;
            }
        }

        $this->db->trans_start();
        cekHijau(count($ygDouble));
        arrPrintHijau($ygDouble);
        foreach ($ygDouble as $namaitem2 => $dataItem2) {
            foreach ($dataItem2 as $ix => $item3) {
                if($ix == 1){
                    // if($item3['kode'] == ''){
                    //
                    //     $arrSet = array(
                    //         "trash" => 1,
                    //     );
                    //     $conditeUpd = array(
                    //         "id" => $item3['id'],
                    //         // "kode" => '',  ?
                    //     );
                    //     $this->db->set($arrSet);
                    //     $this->db->where($conditeUpd);
                    //     $var = $this->db->update($tbl_1);
                    //     showLast_query("orange");
                    // }

                    if(!array_key_exists($item3['id'], $ygsudahdpakai)){

                            $arrSet = array(
                                "trash" => 1,
                            );
                            $conditeUpd = array(
                                "id" => $item3['id'],
                                // "kode" => '',  ?
                            );
                            $this->db->set($arrSet);
                            $this->db->where($conditeUpd);
                            $var = $this->db->update($tbl_1);
                            showLast_query("orange");

                    }
                }
            }
        }
        mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");


    }

    public function cleansingLocker(){
        $tbl_1 = "stock_locker";

        $condites = array(
          "state" => "active",
          "jenis" => "produk",
        );
        $this->db->where($condites);
        $produkData = $this->db->get($tbl_1)->result_array();
        showLast_query("hijau", count($produkData));

        foreach ($produkData as $item) {
            $id = $item['id'];
            $nama = $item['nama'];
// arrPrintHijau($item);

            $produkDouble[$item['produk_id']][] = array(
                'id' => $item['id'],
                'jumlah' => $item['jumlah'],
                'produk_id' => $item['produk_id'],
                // 'barcode' => $item['barcode'],
                // 'kategori' => $item['kategori_nama'],
            );
            $produkId[$id]= $item;

            // break;
        }

        // arrPrintKuning($produkDouble);

        foreach ($produkDouble as $namaItem => $dataItem) {
            if(count($dataItem) > 1){
                $ygDouble[$namaItem] = $dataItem;
            }
        }

        // arrPrintWebs($ygDouble);

        // $jml = array();
        foreach ($ygDouble as $produk_id => $item_1) {
            foreach ($item_1 as $item) {

                if(!isset($jml[$produk_id]['jumlah'])){
                    $jml[$produk_id]['jumlah'] = 0;
                }
                $jml[$produk_id]['jumlah'] += $item['jumlah'];
                $jml[$produk_id]['id'] = $item['id'];
                $jml[$produk_id]['produk_id'] = $produk_id;
            }
        }

        // arrPrintPink($jml);

        $this->db->trans_start();
        foreach ($jml as $pro_id => $item_2) {

            arrPrintWebs($item_2);

            $jml_new = $item_2['jumlah'];
            $jml_id = $item_2['id'];

            $arrSet = array(
                "jumlah" => $jml_new,
            );
            $conditeUpd = array(
                "id" => $jml_id,
                // "kode" => '',  ?
            );
            $this->db->set($arrSet);
            $this->db->where($conditeUpd);
            $var = $this->db->update($tbl_1);
            showLast_query("orange");

            $arrSet2 = array(
              "jenis" => "produk_delete",
            );
            $conditeUpd2 = array(
              "produk_id" => $pro_id,
              "id !=" => $jml_id,
              "state" => "active",
            );
            $this->db->set($arrSet2);
            $this->db->where($conditeUpd2);
            $var = $this->db->update($tbl_1);
            showLast_query("kuning");

            // break;
        }

        mati_disini(__LINE__);
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");
    }



    public function test()
    {

        // Fungsi untuk menghilangkan spasi pada kunci dan nilai
        // function trimArray($item)
        // {
        //     foreach ($item as $key => $value) {
        //         if (is_array($value)) {
        //             $key_1 = trim($key);
        //
        //             $array_2 = trimArray($value);
        //
        //             $temp[$key_1] = $array_2;
        //         }
        //         else {
        //             $temp[trim($key)] = trim($value);
        //         }
        //     }
        //
        //     return $temp;
        // }

        // Array yang ingin di-trim dari spasi di dalam kunci dan nilai
        $array['items'] = array(
            "5588 " => array(
                "anu"   => " sultan",
                " anus" => array(
                    "bapak " => " ibu"
                ),
            ),
        );

arrPrintHijau($array);
        arrPrintWebs(trimArray($array));


    }
}