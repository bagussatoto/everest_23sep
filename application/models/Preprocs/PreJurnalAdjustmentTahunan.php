<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/24/2018
 * Time: 9:31 PM
 */
class PreJurnalAdjustmentTahunan extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
        "nama",

    );
    private $resultParams = array();
    private $inParams;
    private $outParams;
    private $result;


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    //<editor-fold desc="getter-setter">

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

    public function pair($master_id, $inParams, $srcGateData, $targetResult,$srcStaticParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }
        // $defPosition = detectRekDefaultPosition($rek);
        // $opPosition = $defPosition == "debet" ? "kredit" : "debet";
// arrprintWebs($srcStaticParams);
// arrprintKuning($inParams);
        // matiHEre(__LINE__);
        $this->load->helper("he_accounting");
        if (sizeof($inParams) > 0) {
            $patchers = array();
            $needles = array();
            $needlesNama = array();
            $fullfills = array();
            $kekurangans = array();
            $updatePairs = array();
            $ids = array();
            $params = array();
            if (sizeof($srcGateData) > 0) {
                foreach ($srcGateData as $rekID =>$srcGateData_0) {
                    unset($srcGateData_0["sub_debet"]);
                    unset($srcGateData_0["sub_kredit"]);
                    $defPosition = detectRekDefaultPosition($rekID);
                    $label="";
                    foreach($srcGateData_0 as $position =>$value){
                        // cekHitam("bandingkan $defPosition =>$position" );
                        switch ($position){
                            case "debet":
                            case "kredit":
                            if($defPosition=="debet" && $position=="kredit"){
                                $values = "-".$value;
                            }
                            else{
                                if($defPosition=="kredit" && $position=="debet"){
                                    $values = "-".$value;
                                }
                                else{
                                    $values = $value;
                                }

                            }
//                            cekMErah("rekening ".$rekID."|| def posision ".$defPosition. "||posision = $position "."||dengan  nilai ".$values);

                            $label =$rekID."_".$position;
//                            cekhitam($label);
                            $params["src"][$label]=$values;
                            $params["loop"][$rekID]=$label;
                                break;
                            default:
                                break;

                        }

                    }
                    
                }
                if(sizeof($srcStaticParams)>0){
                    foreach ($srcStaticParams as $pre =>$preKey){
                        $params["static"][$pre]=$preKey;
                    }
                }
                if(sizeof($inParams["static"])>0){
                    foreach($inParams["static"] as $in_params_key =>$keyValues){
                        $params["src"][$in_params_key]=$keyValues;
                    }
                }
            }
//             arrprintWebs($params);
            // arrprint($inParams);
//             matiHEre();
            $builderParams = array(
                "master"=>array(
                    array(
                        "comName"=>"Jurnal",
                        "loop" => $params["loop"],
                        "static" => $params["static"],
                        "srcGateName" => "$targetResult",
                        "srcRawGateName" => "$targetResult",
                    ),
                    array(
                        "comName"=>"Rekening",
                        "loop" => $params["loop"],
                        "static" => $params["static"],
                        "srcGateName" => "$targetResult",
                        "srcRawGateName" => "$targetResult",
                    ),
                ),

            );
            // arrprint($params["src"]);
            // matiHere(__LINE__);
            $jurnalIndex["components"][$params["src"]["jenisTr"]] = $builderParams;

            /*
             * build session untuk gerbang nilai jurnal
             *
             */

            if(sizeof($params["src"])>0){
                $cCode = "_TR_".$params["src"]["jenisTr"];
                $_SESSION[$cCode][$targetResult]=$params["src"];
            }

            // arrPrint($jurnalIndex);
            // arrPrintKuning( $_SESSION[$cCode][$targetResult]);
            // matiHEre();

            $this->result =  $jurnalIndex;
            return true;
            // arrPrint($jurnalIndex);

            // arrprint($inParams);
            // arrprintWebs($srcGateData);

            //            cekHitam("total kebutuhan: $total_kebutuhan");
            //            arrPrintWebs($ids);
            // mati_disini("====");


        }


    }

    public function exec()
    {
        return $this->result;
    }
}