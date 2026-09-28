<?php

class PreJurnalAdjustmentTahunanItems extends CI_Model
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

    public function pair($master_id, $inParams, $srcGateData, $targetResult, $srcStaticParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }
        // $defPosition = detectRekDefaultPosition($rek);
        // $opPosition = $defPosition == "debet" ? "kredit" : "debet";
        // arrprintWebs($srcStaticParams);
        arrprintWebs($inParams);
        // arrprint($srcGateData);
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
            // cekMErah($targetResult);
            $params = array();
            if (sizeof($srcGateData) > 0) {
                // foreach ($srcGateData as $srcGateData_0) {
                $rekID = $srcGateData["rekening_id"];
                $position = $srcGateData["src_position"];
                $value = $srcGateData[$position];
                $defPosition = detectRekDefaultPosition($rekID);
                // arrPrint($srcGateData);
                // matiHEre();

                // cekHitam("bandingkan $defPosition =>$position");
                if ($defPosition == "debet" && $position == "kredit") {
                    $values = -$value;
                }
                else {
                    if ($defPosition == "kredit" && $position == "debet") {
                        $values = -$value;
                    }
                    else {
                        $values = $value;
                    }

                }
                $pre_index = $rekID."_".$srcGateData["extern_id"];
                $label = $rekID . "_" . $position;
                $params["src"][$label] = $values;
                $params["loop"][$rekID] = $values;

                // }
                if (sizeof($srcStaticParams) > 0) {
                    foreach ($srcStaticParams as $pre => $preKey) {
                        $params["static"][$pre] = $preKey;
                    }
                }
                if (sizeof($inParams["static"]) > 0) {
                    foreach ($inParams["static"] as $in_params_key => $keyValues) {
                        $params["src"][$in_params_key] = $keyValues;
                    }
                }
            }
            // arrprint($params);
            // arrprint($inParams);
            $builderParams = array(
                    array(
                        "comName"        => $srcGateData["comName"],
                        "loop"           => $params["loop"],
                        "static"         => $inParams["static"],
                        "srcGateName"    => "$targetResult",
                        "srcRawGateName" => "$targetResult",
                    ),
            );
            // arrPrint($builderParams);
            // matiHEre($targetResult);



            /*
             * build session untuk gerbang nilai jurnal
             *
             */
            $jurnalIndex = array();
            if (sizeof($params["src"]) > 0) {
                $cCode = "_TR_" . $params["src"]["jenis"];
                unset($_SESSION[$cCode][$targetResult][$pre_index]);
                $jurnalIndex["components"][$params["src"]["jenis"]]["detail"]["src"] = $targetResult;
                $_SESSION[$cCode][$targetResult][$pre_index] = $builderParams;
            }
            // arrPrint($jurnalIndex);
            // matiHEre();
            //
            $this->result = $jurnalIndex;
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