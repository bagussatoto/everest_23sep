<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 3:20 PM
 */
class SpreadSheet extends CI_Controller
{
    private $y = array(//===sumbu y (baris)
        "mdlName" => "",
        "label"   => "",
        "entries" => "",
    );
    private $x = array(//===sumbu x (kolom)
        "mdlName" => "",
        "label"   => "",
        "entries" => "",
    );
    private $z = array(//===sumbu z (apa yang diedit)
        "mdlName" => "",
        "label"   => "",
        "entries" => "",
    );
    //===
    private $iy;
    private $ix;
    private $iz;

    private $priceConfig = array();
    private $priceFilterConfig = array();

    private $existingValues = array();
    private $q;
    private $selectedID;
    private $selectedKey;

    private $pageOffset;

    //region gs
    public function getSelectedID()
    {
        return $this->selectedID;
    }

    public function setSelectedID($selectedID)
    {
        $this->selectedID = $selectedID;
    }


    public function getY()
    {
        return $this->y;
    }

    public function setY($y)
    {
        $this->y = $y;
    }

    public function getX()
    {
        return $this->x;
    }

    public function setX($x)
    {
        $this->x = $x;
    }

    public function getZ()
    {
        return $this->z;
    }

    public function setZ($z)
    {
        $this->z = $z;
    }

    public function getIy()
    {
        return $this->iy;
    }

    public function setIy($iy)
    {
        $this->iy = $iy;
    }

    public function getIx()
    {
        return $this->ix;
    }

    public function setIx($ix)
    {
        $this->ix = $ix;
    }

    public function getIz()
    {
        return $this->iz;
    }

    public function setIz($iz)
    {
        $this->iz = $iz;
    }

    public function getPriceConfig()
    {
        return $this->priceConfig;
    }

    public function setPriceConfig($priceConfig)
    {
        $this->priceConfig = $priceConfig;
    }

    public function getExistingValues()
    {
        return $this->existingValues;
    }

    public function setExistingValues($existingValues)
    {
        $this->existingValues = $existingValues;
    }

    public function getQ()
    {
        return $this->q;
    }


    public function setQ($q)
    {
        $this->q = $q;
    }

    //endregion


    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        validateUserSession($this->session->login['id']);//

    }

    public function doUpdateHarga()
    {
        arrPrintKuning($_GET);
        $this->load->helper("he_angka_helper");
        // arrPrintPink($this->session->login);
        $ppn = isset($_GET['ppn']) ? $_GET['ppn'] : matiHere('ppn-nya diset duong!!!');
        $ppn_persen = $_GET['ppn'] / 100;
        $this->load->model("Mdls/MdlHargaProduk");
        $hp = new MdlHargaProduk();

        $all = $hp->lookupAll()->result();
        showLast_query("kuning");

        // arrPrintKuning($all);
        foreach ($all as $items) {
            $jenis_value = $items->jenis_value;

            $datas[$jenis_value][] = $items;
        }
        $dtHargaJual = $datas['jual'];
        foreach ($dtHargaJual as $itemHgJual) {
            $cabId = $itemHgJual->cabang_id;
            $proId = $itemHgJual->produk_id;
            $hargaJual = $itemHgJual->nilai;
            $hargaJualBaru = ($hargaJual * $ppn_persen) + $hargaJual;

            // $dataBarus[$proId]['id'] = $itemHgJual->id;
            // $dataBarus[$proId]['jual'] = pembulatan_pajak($hargaJual) * 1;
            // $dataBarus[$proId]['jualnppn'] = $hargaJualBaru;

            /* ----------------------------------------------------------------
             *
             * ----------------------------------------------------------------*/
            $dataBarus = $hp->updateJualNppn($cabId,$proId,$hargaJual,$ppn);
            // arrPrintHijau($dataBarus);

            // break;
        }

        cekHitam("SELESAI");
    }

}