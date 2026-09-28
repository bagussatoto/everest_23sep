<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 21/03/2019
 * Time: 19.11
 */
require_once dirname(__FILE__) . '/xlsxwriter.class.php';
require_once dirname(__FILE__) . '/PHPExcel.php';

class Excel extends XLSXWriter
{
    protected $titleFile;
    protected $datas;
    protected $headers;
    protected $rowContent;

    //region geter setter
    public function getRowContent()
    {
        return $this->rowContent;
    }

    public function setRowContent($rowContent)
    {
        $this->rowContent = $rowContent;
    }

    public function getTitleFile()
    {
        return $this->titleFile;
    }

    public function setTitleFile($titleFile)
    {
        $this->titleFile = $titleFile;
    }

    public function getDatas()
    {
        return $this->datas;
    }

    public function setDatas($datas)
    {
        $this->datas = $datas;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    //endregion

    function __construct()
    {
        parent::__construct();
        // $aa=
        // $this->load->library('PHPExcel');
        $this->xlsx = new PHPExcel_Reader_Excel2007();
    }

    public function writer()
    {
        // $this->load->library('Excel');

        $judul = isset($this->titleFile) ? $this->titleFile : matiHere("title harap diset");
        $datas = isset($this->datas) ? $this->datas : matiHere("set datas dalam format array object");
        // $arrKolom = isset($this->headers) ? $this->headers : matiHere("format array");
        $arrKolom = isset($this->headers) ? $this->headers : matiHere("format array (field => array(label => strlabel, type => sting/integer))))");

        $rowsData = isset($this->rowContent) ? $this->rowContent : "";

        // arrPrintKuning($rowsData);
        // matiHere(__LINE__);

        // $headers['anu'] = 'string';
        $headers['No'] = 'integer';
        foreach ($arrKolom as $itemKolom => $arrKolomSpec) {
            $headers[$arrKolomSpec['label']] = $arrKolomSpec['type'];
        }

        $no = 0;
        $rows = array();
        foreach ($datas as $ordData) {
            $no++;
            $specs = array();
            $specs[] = $no;
            foreach ($arrKolom as $kolom => $data_specs) {
                $$kolom = $ordData->$kolom;

                $specs[] = $ordData->$kolom;
            }
            $rows[] = $specs;
        }

        //create writer object
        $writer = new Excel();

        //region meta data info
        $keywords = array('xlsx', 'MySQL', 'Codeigniter');
        $writer->setTitle($judul);
        $writer->setSubject('Report generated using Codeigniter and XLSXWriter');
        $writer->setAuthor(base_url());
        $writer->setCompany('');
        $writer->setKeywords($keywords);
        $writer->setDescription('');
        $writer->setTempDir(sys_get_temp_dir());
        //endregion

        //write headers
        // $headerss[]['ttt'] = "hahahaah";
        if (isset($this->rowContent)) {
            foreach ($this->rowContent as $item) {
                $headerss[] = $item;
            }
            $writer->writeSheetRow('Sheet1', $headerss);

            /*---header data diganti ini----*/
            $writer->writeSheetRow('Sheet1', array_keys($headers));
        }
        else {
            $writer->writeSheetHeader('Sheet1', $headers);
        }
        // matiHere(__LINE__);
        //write rows to sheet1
        foreach ($rows as $row):
            $writer->writeSheetRow('Sheet1', $row);
        endforeach;

        // START OF COMPLETE REPEATED LOGIC
        $fileName = $judul . '.xlsx';

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($fileName) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        //write to xlsx file
        $writer->writeToStdOut();

        exit(0);
        // END OF COMPLETE REPEATED LOGIC
        // cekHere(__LINE__);
    }

    public function reader($tmp)
    {
        $loadexcel = $this->xlsx->load($tmp);
        $sheet = $loadexcel->getSheet(0)->toArray(null, true, false, true);

        $num = 1;
        $numrow = 1;
        $data_header = 1;   // header data excel ada di row ini
        $data_start = 2;    // data yg diambil mulai row ini

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

        // arrPrint($koloms);
        // cekHijau($ext);

        //region mengaraykan data dari excel
        $datas = array();
        foreach ($sheet as $row) {
            if ($numrow >= $data_start) {
                foreach ($koloms as $kolom => $kalias) {
                    $kalias_f = strlen($kalias) < 1 ? $kolom : $kalias;
                    $rows[$kalias_f] = $row[$kolom];
                }
                $datas[$numrow] = $rows;
            }
            $numrow++;
        }
        //endregion

        // arrPrint($data);
        return $datas;
    }

    public function writermultysheet($jml_data = null)
    {
        // $this->load->library('Excel');

        //region prepare data yg akan diexcelkan
        $judul = isset($this->titleFile) ? $this->titleFile : matiHere("title harap diset");
        $datas = isset($this->datas) ? $this->datas : matiHere("set datas dalam format array object");
        // $arrKolom = isset($this->headers) ? $this->headers : matiHere("format array");
        $arrKolom = isset($this->headers) ? $this->headers : matiHere("format array (field => array(label => strlabel, type => sting/integer))))");

        $headers['No'] = 'integer';
        foreach ($arrKolom as $itemKolom => $arrKolomSpec) {
            $headers[$arrKolomSpec['label']] = $arrKolomSpec['type'];
        }

        $no = 0;
        $rows = array();
        foreach ($datas as $ordData) {
            $no++;
            $specs = array();
            $specs[] = $no;
            foreach ($arrKolom as $kolom => $data_specs) {
                $$kolom = $ordData->$kolom;

                $specs[] = $ordData->$kolom;
            }
            $rows[] = $specs;
        }

        //create writer object
        $writer = new Excel();

        //region meta data info
        $keywords = array('xlsx', 'MySQL', 'Codeigniter');
        $writer->setTitle($judul);
        $writer->setSubject('Report generated using Codeigniter and XLSXWriter');
        $writer->setAuthor(base_url());
        $writer->setCompany('');
        $writer->setKeywords($keywords);
        $writer->setDescription('');
        $writer->setTempDir(sys_get_temp_dir());
        //endregion

        $jml_data_dml_sheet = $jml_data == null ? sizeof($rows) : $jml_data;
        $multy_rows = array_chunk($rows, $jml_data_dml_sheet);
        // cekBiru(sizeof($multy_rows));
        // matiHere(__LINE__ . __METHOD__);
        $sheet_no = 0;
        foreach ($multy_rows as $sheet => $row_sheet) {
            $sheet_no++;
            $sheet_name = "Sheet_$sheet_no";
            //write headers
            $writer->writeSheetHeader($sheet_name, $headers);

            //write rows to sheet1
            foreach ($row_sheet as $row):
                $writer->writeSheetRow($sheet_name, $row);
            endforeach;
        }


        $fileName = $judul . '.xlsx';
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($fileName) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        //write to xlsx file
        $writer->writeToStdOut();

        ob_clean();
        flush();

        exit(0);
        // cekHere(__LINE__);
    }
}