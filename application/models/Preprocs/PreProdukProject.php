<?php


class PreProdukProject extends CI_Model
{
    private $requiredParams = array(
        "id",
        "qty",
    );
    private $resultParams = array();
    private $result;
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "id",
        "nama",
        "kode",
        "transaksi_id",
        "transaksi_no",
        "oleh_id",
        "oleh_nama",
        "customer_id",
        "customer_nama",
        "closing_status",
        "closing_oleh_id",
        "closing_oleh_nama",
        "closing_dtime",
        "closing_transaksi_id",
        "closing_transksi_no",
        "cabang_id",
        "cabang_nama",
        "dtime",
        "start_dtime",
        "end_dtime",
        "harga",
        "spek",
    );


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

    public function pair($master_id, $inParams)
    {
        $this->load->model("Mdls/MdlProdukProject");
        if (!is_array($inParams)) {
            mati_disini("params/kiriman data required!");
        }

        if (sizeof($inParams) > 0) {
            foreach ($inParams as $cnt => $sentParams) {
                $cCode = "_TR_" . $sentParams["static"]["jenis"];
                $produk_id = isset($sentParams["static"]["projectID"]) && $sentParams["static"]["projectID"] !="" ? $sentParams["static"]["projectID"]:0;
                if($produk_id > 0){
                    $prev = $this->cekPreValue($produk_id);
                    if($prev["allow_create"]){
                        $this->load->model("Mdls/MdlProdukProject");
                        $p = new MdlProdukProject();
                        $dataBaru = array();
                        foreach ($sentParams['static'] as $keyy => $vall) {
                            if (in_array($keyy, $this->outFields)) {
                                $dataBaru[$keyy] = $vall;
                            }
                        }
                        $returnID = $p->addData($dataBaru) or mati_disini("gagal menulis data project. Segera hubungi admin.");
                        $_SESSION[$cCode]["main"]["projectID"] = $returnID;
                        $_SESSION[$cCode]["main"]["current_projectID"] = $returnID;
                        $_SESSION[$cCode]["items"][0]["id"] = $returnID;
                    }
                    else{

                    }
                }
                else{
                    //data baru bukan EDIT langsung insert
                    $this->load->model("Mdls/MdlProdukProject");
                    $p = new MdlProdukProject();
                    $dataBaru = array();
                    foreach ($sentParams['static'] as $keyy => $vall) {
                        if (in_array($keyy, $this->outFields)) {
                            $dataBaru[$keyy] = $vall;
                        }
                    }
                    $returnID = $p->addData($dataBaru) or mati_disini("gagal menulis data project. Segera hubungi admin.");
                    $_SESSION[$cCode]["main"]["projectID"] = $returnID;
                    $_SESSION[$cCode]["main"]["current_projectID"] = $returnID;
                    $_SESSION[$cCode]["items"][0]["id"] = $returnID;
                }

            }


        }
        return true;
    }

    public function exec()
    {
        return true;
    }

    public function cekPreValue($produk_id){

        $this->load->model("Mdls/MdlProdukProject");
        $tr = new MdlProdukProject();
        $tr->setFilters(array());
        $tr->addFilter("id='" .$produk_id . "'");
        $tmp = $tr->lookUpAll()->result();
        $data = array();
        if(count($tmp)>0){
            $data = array(
                "allow_create"=>0,
            );
        }
        else{
            $data = array(
                "allow_create"=>0,
            );
        }

        return $data;
    }


}

?>