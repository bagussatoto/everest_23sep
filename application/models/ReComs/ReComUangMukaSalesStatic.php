<?php

class ReComUangMukaSalesStatic extends MdlMother
{
    private $jenisTr;


    public function getJenisTr()
    {
        return $this->jenisTr;
    }

    public function setJenisTr($jenisTr)
    {
        $this->jenisTr = $jenisTr;
    }


    public function __construct()
    {
        parent::__construct();
        $this->jenisTr = $this->uri->segment(4);

    }

    public function pair($gate, $source)
    {
        $cCode = "_TR_" . $this->jenisTr;

        cekHere("$source");
        arrPrintPink($gate);
        $source_cek = $gate["source"];
        $validate = $gate["validate"];
        if(isset($validate[$source])){

            $arrAlert = array(
                "html" => $validate[$source],
            );
            $link_to = "";
            die(swalAlertGoToOption($arrAlert, $link_to, $btn_label = "TETAP LANJUTKAN", $btn_cancel = "CANCEL"));
//            die(lgShowAlertBiru($validate[$source]));
        }

        // cekHitam($this->db->last_query());
        // matiHere(__LINE__." ".__FILE__."src id ".$source." @@@ ".$srcValue);
        return true;
    }

    public function exec()
    {

//        mati_disini();
        return true;
    }
}