<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PrePpnBendaharaSync extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array(//        "hpp" => "hpp",
    );
    private $inParams;
    private $outParams;
    private $result;

    //<editor-fold desc="getter-setter">

    public function __construct()
    {
        parent::__construct();
    }

    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    public function setRequiredParams($requiredParams)
    {
        $this->requiredParams = $requiredParams;
    }

    public function getInParams()
    {
        return $this->inParams;
    }

    public function setInParams($inParams)
    {
        $this->inParams = $inParams;
    }

    public function getOutParams()
    {
        return $this->outParams;
    }

    public function setOutParams($outParams)
    {
        $this->outParams = $outParams;
    }

    public function getResultParams()
    {
        return $this->resultParams;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $inParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }
//cekUngu("cetak INPARAMS ");
// arrPrint($inParams);
// matiHere();
        if (sizeof($inParams) > 0) {
            $preval = $this->preValues($inParams["static"]["extern_id"],$inParams["static"]["extern2_id"],$inParams["static"]["jenis"],$inParams["static"]["target_jenis"],$inParams["static"]["cabang_id"]);
            // arrPrint($preval);
            // matiHEre();
            if(sizeof($preval)>0){
                //udpate session code main
                // arrPrint($this->resultParams);
                $cCode = "_TR_" . $inParams["static"]["jenisTr"];
                $targetSes = $inParams["static"]["targetSession"]["param"];
                // cekBiru($inParams["static"]["targetSession"]);
                // matiHEre($cCode."||".$targetSes);
                foreach ($preval as $ix =>$targetVal){
                    $_SESSION[$cCode]["main"]["faktur_".$ix]=$targetVal;
                }
                // $this->result = $preval;

            }


        }
        else {


        }

        return true;

    }

    public function preValues($customerID,$extern2_id,$jenis,$target_jenis){
        $this->load->model("Mdls/MdlPaymentSource");
        $l = new MdlPaymentSource();


        $l->addFilter("customers_id='$customerID'");
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("extern2_id='$extern2_id'");
        $l->addFilter("target_jenis='$target_jenis'");

        $result = array();
        $localFilters = array();
        if (sizeof($l->getFilters()) > 0) {
            foreach ($l->getFilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");

            }
        }

        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();

        $tmp = $this->db->query("{$query} FOR UPDATE")->result();
        // ceklIme($this->db->last_query());
        // matiHEre();

        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $result = array(
                    "extern2_id" => $row->extern2_id,
                    "extern2_nama" => $row->extern2_nama,
                    "extern_date2" => $row->extern_date2,
                    "extern_label2" => $row->extern_label2,
                );
            }
        }
        else {
            $result = array();
        }
        return $result;

    }
    public function exec()
    {

        return true;
    }

}