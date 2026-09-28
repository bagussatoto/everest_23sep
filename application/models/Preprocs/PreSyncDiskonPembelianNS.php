<?php


class PreSyncDiskonPembelianNS extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
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

    public function pair($master_id, $inParams, $sessionData = array())
    {
        arrPrint($inParams);
        if (!is_array($inParams)) {
            die("params required!");
        }
        $needles = array();
        $ids = array();
        if (sizeof($inParams) > 0) {

            $arrDataDiskon = array();
            $this->load->model("Mdls/MdlSupplierDiskon");
            $sd = New MdlSupplierDiskon();
            $sd->addFilter("jenis='reguler'");
            $sdTmp = $sd->lookupAll()->result();
            foreach ($sdTmp as $sdSpec) {
                $arrDataDiskon[$sdSpec->id] = array(
                    "id" => $sdSpec->id,
                    "nama" => $sdSpec->nama,
                    "coa_code" => $sdSpec->coa_code,
                );
            }


            $cCode = "_TR_" . $inParams["static"]["jenisTrMaster"];

            $sessionData[$inParams["static"]["target"]] = array();

            $items = $sessionData["items"];
            foreach ($items as $pID => $iSpec) {
                foreach ($arrDataDiskon as $iii => $iiiSpec) {
                    $key_nama = $iiiSpec["nama"];
                    $key_nama_cek = $iiiSpec["nama"] . "_id";
                    cekHere("cek dulu $key_nama $key_nama_cek ");
                    if (array_key_exists($key_nama_cek, $iSpec)) {
                        cekmerah("ada $key_nama_cek dibuatkan items4_sum");
                        $data4_sum = array(
                            "id" => $iSpec["id"],
                            "nama" => $iSpec["nama"],
                            "name" => $iSpec["name"],
                            "jml" => $iSpec["jml"],
                            "qty" => $iSpec["qty"],
                            "diskon_id" => $iSpec[$key_nama . "_id"],
                            "diskon_nama" => $iSpec[$key_nama . "_nama"],
                            "diskon_name" => $iSpec[$key_nama . "_nama"],
                            "diskon_persen" => $iSpec[$key_nama . "_persen"],
                            "diskon_nilai" => $iSpec[$key_nama . "_nilai"],
//                            "diskon_nilai" => "",
                        );

                        $sessionData[$inParams["static"]["target"]][] = $data4_sum;
                    }
                    else {
//                        cekhitam("tidak ada $key_nama_cek dibuatkan items4_sum");
                    }
                }
            }

//mati_disini(__LINE__);
            return $sessionData;


        }
    }

    public function exec()
    {
        return $this->result;
    }
}