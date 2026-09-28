<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class PreKompositValues extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $result;
    private $outFields = array( // dari tabel rek_cache
        "tagihan",
        "terbayar",
        "returned",
        "sisa",
        "cabang_id",
        "cabang_nama",
        "extern_id",
        "extern_nama",
        "transaksi_id",
        "jenis",
        "label",
    );
    private $resultParams = array();

    //<editor-fold desc="getter and setter">
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

    public function getWriteMode()
    {
        return $this->writeMode;
    }

    public function setWriteMode($writeMode)
    {
        $this->writeMode = $writeMode;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }

    public function getOutFields()
    {
        return $this->outFields;
    }

    public function setOutFields($outFields)
    {
        $this->outFields = $outFields;
    }

    //</editor-fold>

    public function setResultParams($resultParams)
    {
        $this->resultParams = $resultParams;
    }


    public function __construct($resultParams = array())
    {
        parent::__construct();
        $this->resultParams = $resultParams;
    }

    public function pair($master_id, $inParams)
    {
        if (!is_array($inParams)) {
            die("params required!");
        }
        arrPrintPink($inParams);

        $result = array();
        if (sizeof($inParams) > 0) {
            if(sizeof($inParams['static'])>0){
                $jenisTr = isset($inParams['static']['jenisTr']) ? $inParams['static']['jenisTr'] : "";
                $cCode = "_TR_" . $jenisTr;

                $gateTarget = "rsltItems2";
                $gateSource = "rsltItems";
                $gateResult = "jurnalItems";
                $targetSum = array();
                $sourceSum = array();
                $loop = array();
                $static = array();
                $static_new = array();
                $staticReplacer = array(
                    "jenisTr" => "jenisTr",
                    "jenisTrMaster" => "jenisTrMaster",
                    "jenisTrTop" => "jenisTrTop",
                    "jenisTrName" => "jenisTrName",
                    "olehID" => "olehID",
                    "olehName" => "olehName",
                    "stepNumber" => "stepNumber",
                    "srcGateName" => "srcGateName",
                    "srcRawGateName" => "srcRawGateName",
                    "comName" => "comName",
                    "rekening" => "rekening",
                    "mdlName" => "mdlName",

                );


                foreach ($inParams['static'] as $key => $val){
                    $result[$key] = $val;
                    $static[$key] = $val;
                    $static_new[$key] = $val;
                }
                foreach ($staticReplacer as $key => $val){
                    $static_new[$key] = $val;
                }




                // mengambil data target repack
                if(sizeof($_SESSION[$cCode][$gateTarget]) > 0){
                    foreach($_SESSION[$cCode][$gateTarget] as $tSpec){
                        if(!isset($targetSum[$tSpec['id']])){
                            $targetSum[$tSpec['id']]['id'] = $tSpec['id'];
                            $targetSum[$tSpec['id']]['nama'] = $tSpec['nama'];
                            $targetSum[$tSpec['id']]['name'] = $tSpec['name'];
                            $targetSum[$tSpec['id']]['subhpp'] = 0;
                        }
                        $targetSum[$tSpec['id']]['subhpp'] += ($tSpec['hpp'] * $tSpec['qty']);
                    }
                }

cekBiru($_SESSION[$cCode][$gateSource]);
                // mengambil data source repack
                if(sizeof($_SESSION[$cCode][$gateSource]) > 0){
                    foreach($_SESSION[$cCode][$gateSource] as $tSpec){
                        if(!isset($sourceSum[$tSpec['id']])){
                            $sourceSum[$tSpec['id']]['id'] = $tSpec['id'];
                            $sourceSum[$tSpec['id']]['nama'] = $tSpec['nama'];
                            $sourceSum[$tSpec['id']]['name'] = $tSpec['name'];
                            $sourceSum[$tSpec['id']]['subhpp'] = 0;
                        }
                        $sourceSum[$tSpec['id']]['subhpp'] += ($tSpec['hpp'] * $tSpec['qty']);
                    }
                }
cekKuning($sourceSum);
                if(sizeof($sourceSum)>0){
                    $no = 0;
                    foreach ($sourceSum as $pID => $sSpec){
                        $no++;
                        $result["sourceID_" . $no] = $sSpec["id"];
                        $result["sourceNama_" . $no] = $sSpec["nama"];
                        $result["sourceName_" . $no] = $sSpec["name"];
                        $result["sourceValue_" . $no] = $sSpec["subhpp"];

//                        $loop["{sourceNama_" . $no . "}"] = "-sourceValue_" . $no . "";
                        $loop["{sourceID_" . $no . "}"] = "-sourceValue_" . $no . "";
                    }
                }
//cekKuning($targetSum);
                if(sizeof($targetSum)>0){
                    $no = 0;
                    foreach ($targetSum as $pID => $sSpec){
                        $no++;
                        $result["targetID_" . $no] = $sSpec["id"];
                        $result["targetNama_" . $no] = $sSpec["nama"];
                        $result["targetName_" . $no] = $sSpec["name"];
                        $result["targetValue_" . $no] = $sSpec["subhpp"];

//                        $loop["{targetNama_" . $no . "}"] = "targetValue_" . $no . "";
                        $loop["{targetID_" . $no . "}"] = "targetValue_" . $no . "";
                    }
                }
cekHijau($loop);
cekHijau($static);
//mati_disini();
                //--membuat gerbng JurnalItems
                $_SESSION[$cCode][$gateResult] = $result;
                //--membuat session config componentBuilder
                if(isset($_SESSION[$cCode]['componentsBuilder'])){
                    $_SESSION[$cCode]['componentsBuilder'] = NULL;
                    unset($_SESSION[$cCode]['componentsBuilder']);
                }
                $_SESSION[$cCode]['componentsBuilder'][$static['stepNumber']]['master'] = array(
                    array(
                        "comName" => $static['comName'],
                        "loop" => $loop,
                        "static" => $static_new,
                        "srcGateName" => $static['srcGateName'],
                        "srcRawGateName" => $static['srcRawGateName'],
                    ),
                );

            }


        }

//        cekPink($_SESSION[$cCode]['componentsBuilder']);
//        cekKuning($_SESSION[$cCode]['jurnalItems']);
//        mati_disini("test preprocc");

        return true;

    }


    public function exec()
    {
        return $this->result;
    }
}