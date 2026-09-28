<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 3:20 PM
 */
class ProdukVendor extends CI_Controller
{
    private $y = array(//===sumbu y (baris)
        "mdlName" => "",
        "label" => "",
        "entries" => "",
    );
    private $x = array(//===sumbu x (kolom)
        "mdlName" => "",
        "label" => "",
        "entries" => "",
    );
    private $z = array(//===sumbu z (apa yang diedit)
        "mdlName" => "",
        "label" => "",
        "entries" => "",
    );
    //===
    private $iy;
    private $ix;
    private $iz;

    private $priceConfig = array();

    private $existingValues = array();
    private $q;
    private $selectedID;

    public function getSelectedID()
    {
        return $this->selectedID;
    }

    public function setSelectedID($selectedID)
    {
        $this->selectedID = $selectedID;
    }

    //region gs


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

    //endregion

    public function setQ($q)
    {
        $this->q = $q;
    }


    public function __construct()
    {
        parent::__construct();

        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }

        $backLink = isset($_GET['backLink']) ? blobDecode($_GET['backLink']) : "";
        $this->q = isset($_GET['q']) ? $_GET['q'] : null;
        $this->selectedID = isset($_GET['sID']) && $_GET['sID'] > 0 ? $_GET['sID'] : null;
//        arrPrint($_GET);
//        arrPrint($this->uri->segment_array());
//        die();
        $this->iy = $this->uri->segment(5);//modelnya produk
        $this->iz = $this->uri->segment(2);//mode
        $this->ix = $this->uri->segment(3);//model supplier


        $this->y = array(
            "mdlName" => "Mdl" . ucwords($this->iy),
            "label" => ucwords($this->iy),
            "data" => array(),
            "parent_id" => $this->selectedID,
        );

//region relasi supplier produk
        $this->load->model("Mdls/" . $this->y['mdlName']);
        $yo = new $this->y['mdlName']();


        if ($this->selectedID != null) {
            $yo->addFilter("produk_id='" . $this->selectedID . "'");
        }
//        $yo->addFilter("='" . $this->uri->segment(3) . "'");

        if ($this->q != null) {
            $tmpY = $yo->lookupByKeyword($this->q)->result();
        }
        else {
            $tmpY = $yo->lookupAll()->result();
        }

        $arrConnected = array();
        if (sizeof($tmpY) > 0) {
            foreach ($tmpY as $row) {
                $arrConnected[$this->selectedID][] = $row->suppliers_id;
            }
        }
        else {
            $arrConnected = "none";
        }

        $this->y['data'] = $arrConnected;
//endregion

        //region call supplier
        $this->x = array(
            "mdlName" => "Mdl" . ucwords($this->ix),
            "label" => ucwords($this->iy),
            "vendor" => array(),
            "parent_id" => $this->selectedID,
        );

//region relasi supplier produk
        $this->load->model("Mdls/" . $this->x['mdlName']);
        $xo = new $this->x['mdlName']();

        if ($this->q != null) {
            $tmpX = $xo->lookupByKeyword($this->q)->result();
        }
        else {
            $tmpX = $xo->lookupAll()->result();
        }

        $vendors = array();
        if (sizeof($tmpX) > 0) {
            foreach ($tmpX as $row) {
                $vendors[$row->id] = $row->nama;
            }
        }


        $this->x['vendor'] = $vendors;

        //endregion

    }

    public function index()
    {
        $attached = isset($_GET['attached']) ? $_GET['attached'] : 0;
        if ($attached == '1') {
            $_SESSION['backLink'] = unserialize(base64_decode($_GET['backLink']));
        }

        $contens = "";
        foreach ($this->y['data'] as $id => $listedVendors) {
            foreach ($listedVendors as $vID) {
                $contens .= $this->x['vendor'][$vID] . "<br>";
            }
        }

        $data = array(
            "mode" => $this->uri->segment(3),
            "errMsg" => $this->session->errMsg,
            "title" => "conected  " . $this->iy,
            "subTitle" => "type in box below to search " . $this->iy . " name",
            "content" => $contens,
            "formTarget" => "",
            "buttonLabel" => "",
            "parentId" => $this->y['parent_id']
        );
        $this->load->view('harga', $data);
        $this->session->errMsg = "";
    }


}