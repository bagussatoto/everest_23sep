<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 3:20 PM
 */
class ExcelReader extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('PHPExcel');
        $this->xlsx = new PHPExcel_Reader_Excel2007();
    }

    public function form()
    {
        echo "<form method='post' enctype='multipart/form-data' action='" . base_url() . "ExcelReader/produkReader/'> ";
        echo "<input type='file' name='fileExcel'>";
        echo "<input type='submit' name='save' value='save'>";
        echo "</form>";
        echo "reader xlsx";
        echo "<p>row pertama dibaca sebagai nama kolom, data dimulai row ke 2</p>";
    }

    public function produkReader()
    {
        // arrPrint($_FILES);
        $files = $_FILES['fileExcel'];
        $name = $files['name'];
        $pecahan = explode(".", $name);
        $ext = end($pecahan);
        $tmp = $files['tmp_name'];
        $ext != "xlsx" ? mati_disini(cekHijau("hanya menghandel file XLSX") . "file mu " . $ext) : "";
        // cekHijau($tmp);

        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, true, true);

        // region config
        $num = 1;
        $numrow = 1;
        $data_header = 1;
        $data_start = 2;
        //region menjadikan header data excell mejadi key
        $headers = array();
        foreach ($sheet as $row) {
            if ($num == $data_header) {
                $headers[$num] = $row;
            }
            $num++;
        }
        $koloms = $headers[$data_header];
        //endregion

        // arrPrint($headers[$data_header]);
        // $koloms = array(
        //   "A" => "produk_id",
        //   "M" => "coa",
        //   "C" => "kode",
        //   "D" => "no_part",
        //   "E" => "kategory",
        //   "F" => "nama",
        //   "L" => "nama_2",
        //   "H" => "sys_hbeli",
        //   "G" => "sys_hpp",
        //   "O" => "san_hpp",
        //   "K" => "sys_qty",
        //   "N" => "san_qty",
        //   "P" => "san_amount",
        //   "I" => "sys_harga",
        //   "J" => "sys_hargappn",
        // );

        // arrPrint($koloms);
        // endregion config
        // cekHijau($ext);

        //region mengaraykan data dari excel
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    $rows[$kalias] = $row[$kolom];
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }
        //endregion
        // arrPrint($data);
        arrPrint($datas);

        return $datas;
    }
}