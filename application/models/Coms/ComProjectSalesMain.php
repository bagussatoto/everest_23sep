<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComProjectSalesMain extends MdlMother
{
    protected $filters = array();
    private $tableName;
    private $tableName_mutasi;
    private $tableName_fifoAvg;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "id",
        "nama",
        "transaksi_id",
        "transaksi_id_app",
        "transaksi_no",
        "transaksi_no_app",
        "oleh_id",
        "oleh_nama",
        "customer_id",
        "customer_nama",
        "closing_status",
        "closing_oleh_id",
        "closing_oleh_nama",
        "closing_dtime",
        "closing_transaksi_id",
        "closing_transaksi_nomer",
        "cabang_id",
        "cabang_nama",
        "dtime",
        // "fulldate",
    );

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }
    private $koloms = array(
        "id",
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
        "tagihan_valas",
        "terbayar_valas",
        "sisa_valas",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
    );

    public function __construct()
    {

    }


    public function pair($inParams)
    {

        $this->inParams = $inParams;

        // arrPrint($inParams);
        // matiHEre(__LINE__." ".__FUNCTION__);

        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            // foreach ($this->inParams as $cnt => $inSpec) {
                if (isset($this->inParams['static']) && sizeof($this->inParams['static']) > 0) {
                    $lCounter++;
                    // $jenis = isset($inSpec['static']['reverted_target']) ? $inSpec['static']['reverted_target'] : $inSpec['static']['target_jenis'];
                    //                    $prev = $this->cekPreValue($inSpec['static']['target_jenis'], $inSpec['static']['transaksi_id'], $inSpec['static']['label'], $inSpec['static']['cabang_id']);
                    $prev = $this->cekPreValue($this->inParams['static']);
                    // ceklIme($this->db->last_query());
                    arrprintWebs($prev);
                    // matiHEre();
                    if($prev!=null){
                        $toUpdate = array();
                        foreach($this->outFields as $kolom){
                            if(isset($prev[$kolom])){
                                $toUpdate[$kolom] = $prev[$kolom];
                            }
                        }
                        $this->load->model("Mdls/MdlProdukProject");
                        $p = new MdlProdukProject();
                        $where = array(
                            "id"=>$this->inParams['static']['id'],
                        );
                        // arrPrint($toUpdate);
                        $p->updateData($where,$toUpdate) or die("Failed to update project data");
                        cekLime($this->db->last_query());
                        arrPrint($where);

                        // matiHere();

                    }
                    else{
                        matiHEre("no execution data");
                    }



                }
            // }
        }
// matiHEre();
        return true;

    }

    private function cekPreValue($array)
    {

// arrPrint($array);
        $this->load->model("Mdls/MdlProdukProject");
        $tr = new MdlProdukProject();
        $tr->setFilters(array());
        $tr->addFilter("id='" . $array['id'] . "'");
        switch ($array['methode']) {
            case"open":
                $tr->addFilter("transaksi_id='0'");
                break;
            case "update":
                $tr->addFilter("id='" . $array['id'] . "'");
                break;
            case "close":
                $tr->addFilter("id='" . $array['id'] . "'");
                break;
            case "revert":
                $tr->addFilter("transaksi_id='" . $array['transaksi_id'] . "'");
                break;
            default:
                matiHere("Gagal menyimpan transaksi (" . __CLASS__ . ") <br> ERROR CODE " . __LINE__ . "<br> ON " . date("Y-m-d H:i"));
                break;
        }
        $result = array();
        $localFilters = array();
        if (sizeof($tr->getFilters()) > 0) {
            foreach ($tr->getFilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }
        //arrPrintWebs($localFilters);
        $query = $this->db->select()
            ->from($tr->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();

        $tmpR = $this->db->query("{$query} FOR UPDATE")->result();
        // cekHijau($this->db->last_query());
        // matiHEre();
        switch ($array['methode']) {
            case"open":
                if (sizeof($tmpR) > 0) {
                    unset($array["id"]);
                    $data = $array;
                }
                else {
                    $data = null;
                }
                break;
            case "update":
                if (sizeof($tmpR) ==1) {
                    // unset($array["id"]);
                    // unset($array["transaksi_no"]);
                    // unset($array["transaksi_id"]);
                    $data = array("transaksi_no_app"=>$array['transaksi_no'],"transaksi_id_app"=>$array['transaksi_id']);
                    // "transaksi_id_app"=>"transaksi_id",
                    //         "transaksi_nomer_app"=>"transaksi_nomer",
                    // arrprint($data);
                    // matiHEre();
                }
                else {
                    $data = null;
                }
                break;
            case "close":

                if (sizeof($tmpR) == 1) {
                    unset($array["id"]);
                    $temData = array(
                        "closing_dtime"=>$array["dtime"],
                        "closing_transaksi_id"=>$array["transaksi_id"],
                        "closing_transaksi_nomer"=>$array["transaksi_no"],
                        "closing_status"=>"1",
                    );
                    $data = $array+$temData;

                    unset($data["transaksi_id"]);
                    unset($data["transaksi_no"]);
                    unset($data["dtime"]);
                }
                else {
                    $data = null;
                }
                break;
            case "revert":
                if (sizeof($tmpR) > 0) {
                    unset($array["id"]);
                    $data = $array;
                }
                else {
                    $data = null;
                }
                break;
            default:
                $data = null;
                break;
        }
// arrPrint($data);
//         matiHEre();

        return $data;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {
        return true;



    }
}