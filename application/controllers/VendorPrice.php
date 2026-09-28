<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 3:20 PM
 */
class VendorPrice extends CI_Controller
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
    private $priceFilterConfig = array();

    private $existingValues = array();
    private $q;
    private $selectedID;

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

        $this->limit_per_page = 20;
//        $this->limit_per_page = 100;
        $this->pageOffset = ($this->uri->segment(6)) ? $this->limit_per_page * ($this->uri->segment(6) - 1) : 0;
//        $this->pageOffset = ($this->uri->segment(5)) ? $this->limit_per_page * ($this->uri->segment(5) - 1) : 0;

        if ($this->uri->segment(2) == "HargaHistory") {

        }
        else {
            //            cekHere("iki bro");
            $this->q = isset($_GET['q']) ? $_GET['q'] : null;
            $this->selectedID = isset($_GET['sID']) && $_GET['sID'] > 0 ? $_GET['sID'] : null;


            $this->ix = $this->uri->segment(4);//supplier
            $this->iy = $this->uri->segment(3);//produk
            $this->iz = $this->uri->segment(5);//relasi produk supplier
//            $this->iw = $this->uri->segment(6);
            //cekMErah($this->ix = $this->uri->segment(3));
            $this->priceConfig = null != ($this->config->item("hePrices")[$this->ix]) ? $this->config->item("hePrices")[$this->ix] : array();
            $this->priceFilterConfig = null != ($this->config->item("hePriceFilters")) ? $this->config->item("hePriceFilters") : array();


            $this->x = array(
                "mdlName" => "Mdl" . ucwords($this->ix),
                "label" => ucwords($this->ix),
                "entries" => array(),
            );
            $this->y = array(
                "mdlName" => "Mdl" . ucwords($this->iy),
                "label" => ucwords($this->iy),
                "entries" => array(),
                "total=" => 0,
            );
            $this->z = array(
                "mdlName" => "Mdl" . ucwords($this->iz),
                "label" => ucwords($this->iz),
                "entries" => array(),
                "rawEntries" => array(),
                "hisPrice" => array(),
                "listHistory" => array(),
                "availVendor" => array(),
            );

            //==== init x supplier
            $this->load->model("Mdls/" . $this->x['mdlName']);
            $xo = new $this->x['mdlName']();
            if (isset($this->priceFilterConfig[$this->iy]) && sizeof($this->priceFilterConfig[$this->iy]) > 0) {
//                cekHitam("kesini po");
                $aFilter = $this->priceFilterConfig[$this->iy];

                if (sizeof($aFilter) > 0) {
                    foreach ($aFilter as $filter) {
//                        cekkuning("filter: $filter");
                        $exFilter = explode("=", $filter);
                        if (sizeof($exFilter) > 1) {
//                            cekkuning("berupa samadengan");
                            if (substr($exFilter[1], 0, 1) == ".") {
                                $xo->addFilter($exFilter[0] . "='" . str_replace(".", "", $exFilter[1]) . "'");
                            }
                            else {

                                if (isset($this->session->login[$exFilter[1]])) {
                                    $xo->addFilter($exFilter[0] . "='" . $this->session->login[$exFilter[1]] . "'");
                                }
                                else {
                                    $xo->addFilter($exFilter[0] . "='none'");
                                }
                            }

                        }
                        else {
                            $exFilter = explode("<>", $filter);
                            if (sizeof($exFilter) > 1) {
//                                cekkuning("berupa TIDAK samadengan");
                                if (substr($exFilter[1], 0, 1) == ".") {
                                    $xo->addFilter($exFilter[0] . "<>'" . str_replace(".", "", $exFilter[1]) . "'");
                                }
                                else {

                                    if (isset($this->session->login[$exFilter[1]])) {
                                        $xo->addFilter($exFilter[0] . "<>'" . $this->session->login[$exFilter[1]] . "'");
                                    }
                                    else {
                                        $xo->addFilter($exFilter[0] . "<>'none'");
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else {
//                cekkuning($this->ix . " x TIDAK berfilter");
            }

            $tmpX = $xo->lookupAll()->result();
            if (sizeof($tmpX) > 0) {
                foreach ($tmpX as $row) {
                    $this->x['entries'][$row->id] = $row->nama;
                }


            }
            else {
                $this->x['entries'] = array();
            }


            //===init Y produk
            $this->load->model("Mdls/" . $this->y['mdlName']);
            $yo = new $this->y['mdlName']();
            if ($this->selectedID != null) {
                $yo->addFilter("id='" . $this->selectedID . "'");
            }

            $limit_per_page = $this->limit_per_page;
            $total_records = $yo->lookupDataCount($this->q);

            $pageNum = $this->uri->segment(6) > 0 ? $this->uri->segment(6) : 1;
            if (isset($_GET['sID']) && $_GET['sID'] > 0) {
                $yo->addFilter("id='" . $_GET['sID'] . "'");
                $tmpY = $yo->lookupAll()->result();
            }
            else {

                $tmpY = $yo->lookupLimitedData($limit_per_page, $this->pageOffset, $this->q);
//                cekmerah($this->db->last_query());
            }


            if (sizeof($tmpY) > 0) {
                foreach ($tmpY as $row) {
                    $this->y['entries'][$row->id] = str_replace(" ", "&nbsp;", $row->nama);
                    //                $this->y['entries'][$row->id]=$row->nama;
                }
                $this->y['total'] = $total_records;
                $this->y['limit'] = $limit_per_page;
                $this->y['jmlPage'] = ($this->y['total'] / $this->y['limit']);
            }
            else {
                $this->y['entries'] = array();
            }

            if (sizeof($this->y['entries']) < 1 || sizeof($this->x['entries']) < 1) {
                die("Unable to determine the members of X or Y axis");
            }


            //===initZ relasi produk supplier
            $this->load->model("Mdls/" . $this->z['mdlName']);
            $zo = new $this->z['mdlName']();

            $tmpZ = $zo->lookupAll()->result();

            if (sizeof($tmpZ) > 0) {
                foreach ($tmpZ as $row) {
                    $yPoint = $this->iy . "_id";
                    $xPoint = $this->ix . "s_id";
//                    cekHitam($xPoint);
                    $this->z['entries'][$row->produk_id][$row->$xPoint][$row->jenis_value] = $row->nilai;
                    $this->z['rawEntries'][$row->produk_id][$row->$xPoint][$row->jenis_value] = $row->nilai;
                    $this->z['hisPrice'][$row->produk_id][$row->$xPoint][] = $row->jenis_value;
                    $this->existingValues[$row->produk_id][$row->$xPoint][$row->jenis_value] = $row->nilai;
                }
            }
            else {
                $this->z['entries'] = array();
            }

//            arrPrint( $this->z['entries']);
//            die();

            //===normalize
            $arrListHistory = array();
            // y produk
            // x supplier
            // z relasi produk supplier
//            arrPrint($this->z['entries']);
//            die();
            foreach ($this->y['entries'] as $yID => $yName) {
                foreach ($this->x['entries'] as $xID => $xName) {
                    foreach ($this->priceConfig as $zID => $zSpec) {
                        $zName = $zSpec['label'];
                        //region historyprice
                        $linkHist = base_url() . get_class($this) . "/HargaHistory/$yID/$xID/$zID";
                        $historyClick = "BootstrapDialog.closeAll();

                    BootstrapDialog.show(
                                   {
                                        title:'\'$zID\' price histories',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,                                        
                                        }
                                        );";

                        //endregion

//                        if (!isset($this->z['entries'][$yID])) {
//                            $this->z['entries'][$yID] = array();
//                        }
//                        if (!isset($this->z['entries'][$yID][$xID])) {
//                            $this->z['entries'][$yID][$xID] = array();
//                        }
//                        if (!array_key_exists($zID, $this->z['entries'][$yID][$xID])) {
//                            $this->z['entries'][$yID][$xID][$zID] = 0;
//                        }


                        switch ($this->priceConfig[$zID]['srcType']) {
                            case "formula":
                                //cuekin aja, ntar tinggal ngitung
                                $savedVal = $this->z['entries'][$yID][$xID][$zID];
                                $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
                                if (isset($this->z['entries'][$yID][$xID][$zID])) {
                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number readonly class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value='" . $savedVal . "'>";

                                }
                                else {
                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number readonly class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value=''>";
                                }
                                break;
//                            case "instantFormula":
//
//                                if (isset($this->z['entries'][$yID][$xID][$zID])) {
//                                    //                        echo "replace";
//                                    $savedVal = $this->z['entries'][$yID][$xID][$zID];
//                                    //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
//                                    $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
//                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value='" . $savedVal . "'>";
//
//                                } else {
//                                    //                        echo "insert";
//                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value=''>";
//                                }
//                                break;
                            default:
                                $keyupEvent = "";
                                $keyUpStr = "";
//                                $nameLabel="value_" . $yID . "_" . $xID . "_" . $zID . ""; //==untuk nama/ID input
//                                cekmerah("y/x/z: $yID / $xID / $zID");
                                if (isset($this->priceConfig[$zID]['keyUpEvent'])) {
//                                    cekhijau("ada keyup event");
                                    $keyupEvent = $this->priceConfig[$zID]['keyUpEvent'];
//                                    cekmerah("==============================");
                                    foreach ($this->priceConfig as $k => $v) {
//                                        cekbiru("y/x/z: $yID / $xID / $k - $v");
                                        $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
                                        $keyupEvent = str_replace("{" . $k . "}", $nameLabel, $keyupEvent);
//                                        cekbiru("replacing {".$k."} with $nameLabel");
//                                        cekhijau("now isinya is $keyupEvent");
                                    }
                                }
                                else {
//                                    cekmerah("TIDAK ada keyup event");
                                }
                                if (strlen($keyupEvent) > 2) {
                                    $keyUpStr = " onkeyup=\"$keyupEvent\" ";
                                }


                                $nameLabel = "value_" . $yID . "_" . $xID . "_" . $zID . ""; //==untuk nama/ID input
                                $disabled = isset($this->z['entries'][$yID][$xID]) ? "" : "";
                                if (isset($this->z['entries'][$yID][$xID][$zID])) {
                                    //                        echo "replace";
                                    $savedVal = $this->z['entries'][$yID][$xID][$zID];
                                    //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
                                    $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
                                    $this->z['entries'][$yID][$xID][$zID] = "<input  type=number class='form-control text-right' name='$nameLabel' id='$nameLabel' value='" . $savedVal . "' $keyUpStr>";

                                }
                                else {
                                    //                        echo "insert";
                                    $this->z['entries'][$yID][$xID][$zID] = "<input  type=number class='form-control text-right' name='$nameLabel' id='$nameLabel' value='' $keyUpStr>";
                                }
                                break;
                        }

//                        if ($this->priceConfig[$zID]['srcType'] == "formula") {
//                            //cuekin aja, ntar tinggal ngitung
//                            $savedVal = $this->z['entries'][$yID][$xID][$zID];
//                            $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
//                            if (isset($this->z['entries'][$yID][$xID][$zID])) {
//                                $this->z['entries'][$yID][$xID][$zID] = "<input type=number readonly class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value='" . $savedVal . "'>";
//
//                            } else {
//                                $this->z['entries'][$yID][$xID][$zID] = "<input type=number readonly class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value=''>";
//                            }
//                        } else {
//
//                            if (isset($this->z['entries'][$yID][$xID][$zID])) {
//                                //                        echo "replace";
//                                $savedVal = $this->z['entries'][$yID][$xID][$zID];
//                                //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
//                                $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
//                                $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value='" . $savedVal . "'>";
//
//                            } else {
//                                //                        echo "insert";
//                                $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value=''>";
//                            }
//                        }

                        if (isset($this->z['rawEntries'][$yID][$xID][$zID])) {
                            //                        echo "replace";
                            $savedVal = $this->z['rawEntries'][$yID][$xID][$zID];
                            //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
                            $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
                            $this->z['rawEntries'][$yID][$xID][$zID] = $savedVal;

                        }
                        else {
                            //                        echo "insert";
                            $this->z['rawEntries'][$yID][$xID][$zID] = "";
                        }
                        $this->z['listHistory'][$yID][$xID][$zID] = "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view $zID update histories  ' onclick=\"$historyClick\"><i class='fa fa-clock-o'></i></a>";
                    }
                }
            }
        }
//        arrPrint($this->z['entries']);
//        die();
        //===init X
    }

    public function __Tconstruct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        validateUserSession($this->session->login['id']);//

        $this->limit_per_page = 20;
//        $this->limit_per_page = 100;
        $this->pageOffset = ($this->uri->segment(6)) ? $this->limit_per_page * ($this->uri->segment(6) - 1) : 0;
//        $this->pageOffset = ($this->uri->segment(5)) ? $this->limit_per_page * ($this->uri->segment(5) - 1) : 0;

        if ($this->uri->segment(2) == "HargaHistory") {

        }
        else {
            //            cekHere("iki bro");
            $this->q = isset($_GET['q']) ? $_GET['q'] : null;
            $this->selectedID = isset($_GET['sID']) && $_GET['sID'] > 0 ? $_GET['sID'] : null;


            $this->ix = $this->uri->segment(4);
            $this->iy = $this->uri->segment(3);
            $this->iz = $this->uri->segment(5);
            //cekMErah($this->ix = $this->uri->segment(3));
            $this->priceConfig = null != ($this->config->item("hePrices")[$this->iy]) ? $this->config->item("hePrices")[$this->iy] : array();
            $this->priceFilterConfig = null != ($this->config->item("hePriceFilters")) ? $this->config->item("hePriceFilters") : array();


            $this->x = array(
                "mdlName" => "Mdl" . ucwords($this->ix),
                "label" => ucwords($this->ix),
                "entries" => array(),
            );
            $this->y = array(
                "mdlName" => "Mdl" . ucwords($this->iy),
                "label" => ucwords($this->iy),
                "entries" => array(),
                "total=" => 0,
            );
            $this->z = array(
                "mdlName" => "Mdl" . ucwords($this->iz),
                "label" => ucwords($this->iz),
                "entries" => array(),
                "rawEntries" => array(),
                "hisPrice" => array(),
                "listHistory" => array(),
            );

            $this->load->model("Mdls/" . $this->x['mdlName']);
            $xo = new $this->x['mdlName']();
//            arrprint($this->priceFilterConfig);
            if (isset($this->priceFilterConfig[$this->iy]) && sizeof($this->priceFilterConfig[$this->iy]) > 0) {
//                cekkuning($this->ix." x berfilter");
                $aFilter = $this->priceFilterConfig[$this->iy];

                if (sizeof($aFilter) > 0) {
                    foreach ($aFilter as $filter) {
//                        cekkuning("filter: $filter");
                        $exFilter = explode("=", $filter);
                        if (sizeof($exFilter) > 1) {
//                            cekkuning("berupa samadengan");
                            if (substr($exFilter[1], 0, 1) == ".") {
                                $xo->addFilter($exFilter[0] . "='" . str_replace(".", "", $exFilter[1]) . "'");
                            }
                            else {

                                if (isset($this->session->login[$exFilter[1]])) {
                                    $xo->addFilter($exFilter[0] . "='" . $this->session->login[$exFilter[1]] . "'");
                                }
                                else {
                                    $xo->addFilter($exFilter[0] . "='none'");
                                }
                            }

                        }
                        else {
                            $exFilter = explode("<>", $filter);
                            if (sizeof($exFilter) > 1) {
//                                cekkuning("berupa TIDAK samadengan");
                                if (substr($exFilter[1], 0, 1) == ".") {
                                    $xo->addFilter($exFilter[0] . "<>'" . str_replace(".", "", $exFilter[1]) . "'");
                                }
                                else {

                                    if (isset($this->session->login[$exFilter[1]])) {
                                        $xo->addFilter($exFilter[0] . "<>'" . $this->session->login[$exFilter[1]] . "'");
                                    }
                                    else {
                                        $xo->addFilter($exFilter[0] . "<>'none'");
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else {
//                cekkuning($this->ix . " x TIDAK berfilter");
            }
//            arrprint($xo->getFilters());
            $tmpX = $xo->lookupAll()->result();
//                    cekMerah($this->db->last_query());
            //        arrPrint($tmpX);die();
            if (sizeof($tmpX) > 0) {
                foreach ($tmpX as $row) {
                    $this->x['entries'][$row->id] = $row->nama;
                }


            }
            else {
                $this->x['entries'] = array();
            }

            //===init Y
            $this->load->model("Mdls/" . $this->y['mdlName']);
            $yo = new $this->y['mdlName']();
            //        $tmpY=$yo->lookupAll()->result();

            if ($this->selectedID != null) {
                $yo->addFilter("id='" . $this->selectedID . "'");
            }

            $limit_per_page = $this->limit_per_page;
            // $page = $this->pageOffset;
            // $page = ($this->uri->segment(6)) ? ($this->uri->segment(6) - 1) : 0;
            // $page = ($this->uri->segment(6)) ? ($this->uri->segment(6)) : 0;
            $total_records = $yo->lookupDataCount($this->q);

            $pageNum = $this->uri->segment(5) > 0 ? $this->uri->segment(5) : 1;

//            cekkuning("offset: ".$this->pageOffset);
            if (isset($_GET['sID']) && $_GET['sID'] > 0) {
                $yo->addFilter("id='" . $_GET['sID'] . "'");
                $tmpY = $yo->lookupAll()->result();
            }
            else {

//                if ($this->q != null) {
//                    //                $tmpY = $yo->lookupByKeyword($this->q)->result();
//                    $tmpY = $yo->lookupLimitedData($limit_per_page, $this->pageOffset, $this->q);
//                }
//                else {
//                    //                $tmpY = $yo->lookupAll()->result();
////                    $tmpY = $yo->lookupLimitedData($limit_per_page, $this->pageOffset);
//                    $tmpY = $yo->lookupLimitedData($limit_per_page, $this->pageOffset, $this->q);
//                }
                $tmpY = $yo->lookupLimitedData($limit_per_page, $this->pageOffset, $this->q);
//                cekmerah($this->db->last_query());
            }

//            cekmerah($this->db->last_query());
//            arrprint($tmpY);

            if (sizeof($tmpY) > 0) {
                foreach ($tmpY as $row) {
                    $this->y['entries'][$row->id] = str_replace(" ", "&nbsp;", $row->nama);
                    //                $this->y['entries'][$row->id]=$row->nama;
                }
                $this->y['total'] = $total_records;
                $this->y['limit'] = $limit_per_page;
                $this->y['jmlPage'] = ($this->y['total'] / $this->y['limit']);
            }
            else {
                $this->y['entries'] = array();
            }

            if (sizeof($this->y['entries']) < 1 || sizeof($this->x['entries']) < 1) {
                die("Unable to determine the members of X or Y axis");
            }

            //===initZ
            $this->load->model("Mdls/" . $this->z['mdlName']);
            $zo = new $this->z['mdlName']();

            $tmpZ = $zo->lookupAll()->result();
            //        arrPrint($tmpZ);
            //        die();
            //        cekMerah($this->db->last_query());
            //        print_r($tmpZ);
            //        die();
            if (sizeof($tmpZ) > 0) {
                foreach ($tmpZ as $row) {
                    $yPoint = $this->iy . "_id";
                    $xPoint = $this->ix . "_id";
                    //                $this->z['entries'][$row->$yPoint][$row->$xPoint][$row->jenis_value]=$row->nilai;
                    //                $this->existingValues[$row->$yPoint][$row->$xPoint][$row->jenis_value]=$row->nilai;
                    $this->z['entries'][$row->produk_id][$row->$xPoint][$row->jenis_value] = $row->nilai;
                    $this->z['rawEntries'][$row->produk_id][$row->$xPoint][$row->jenis_value] = $row->nilai;
                    $this->z['hisPrice'][$row->produk_id][$row->$xPoint][] = $row->jenis_value;
                    $this->existingValues[$row->produk_id][$row->$xPoint][$row->jenis_value] = $row->nilai;
                }
            }
            else {
                $this->z['entries'] = array();
            }
            //===normalize
            $arrListHistory = array();
            foreach ($this->y['entries'] as $yID => $yName) {
                foreach ($this->x['entries'] as $xID => $xName) {
                    foreach ($this->priceConfig as $zID => $zSpec) {
                        $zName = $zSpec['label'];
                        //region historyprice
                        $linkHist = base_url() . get_class($this) . "/HargaHistory/$yID/$xID/$zID";
                        $historyClick = "BootstrapDialog.closeAll();

                    BootstrapDialog.show(
                                   {
                                        title:'\'$zID\' price histories',
                                        message: $('<div></div>').load('" . $linkHist . "'),
                                        size: BootstrapDialog.SIZE_WIDE,
                                        draggable:true,
                                        closable:true,                                        
                                        }
                                        );";

                        //endregion

                        if (!isset($this->z['entries'][$yID])) {
                            $this->z['entries'][$yID] = array();
                        }
                        if (!isset($this->z['entries'][$yID][$xID])) {
                            $this->z['entries'][$yID][$xID] = array();
                        }
                        if (!array_key_exists($zID, $this->z['entries'][$yID][$xID])) {
                            $this->z['entries'][$yID][$xID][$zID] = 0;
                        }


                        switch ($this->priceConfig[$zID]['srcType']) {
                            case "formula":
                                //cuekin aja, ntar tinggal ngitung
                                $savedVal = $this->z['entries'][$yID][$xID][$zID];
                                $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
                                if (isset($this->z['entries'][$yID][$xID][$zID])) {
                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number readonly class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value='" . $savedVal . "'>";

                                }
                                else {
                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number readonly class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value=''>";
                                }
                                break;
//                            case "instantFormula":
//
//                                if (isset($this->z['entries'][$yID][$xID][$zID])) {
//                                    //                        echo "replace";
//                                    $savedVal = $this->z['entries'][$yID][$xID][$zID];
//                                    //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
//                                    $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
//                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value='" . $savedVal . "'>";
//
//                                } else {
//                                    //                        echo "insert";
//                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value=''>";
//                                }
//                                break;
                            default:
                                $keyupEvent = "";
                                $keyUpStr = "";
//                                $nameLabel="value_" . $yID . "_" . $xID . "_" . $zID . ""; //==untuk nama/ID input
//                                cekmerah("y/x/z: $yID / $xID / $zID");
                                if (isset($this->priceConfig[$zID]['keyUpEvent'])) {
//                                    cekhijau("ada keyup event");
                                    $keyupEvent = $this->priceConfig[$zID]['keyUpEvent'];
//                                    cekmerah("==============================");
                                    foreach ($this->priceConfig as $k => $v) {
//                                        cekbiru("y/x/z: $yID / $xID / $k - $v");
                                        $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
                                        $keyupEvent = str_replace("{" . $k . "}", $nameLabel, $keyupEvent);
//                                        cekbiru("replacing {".$k."} with $nameLabel");
//                                        cekhijau("now isinya is $keyupEvent");
                                    }
                                }
                                else {
//                                    cekmerah("TIDAK ada keyup event");
                                }
                                if (strlen($keyupEvent) > 2) {
                                    $keyUpStr = " onkeyup=\"$keyupEvent\" ";
                                }


                                $nameLabel = "value_" . $yID . "_" . $xID . "_" . $zID . ""; //==untuk nama/ID input
                                if (isset($this->z['entries'][$yID][$xID][$zID])) {
                                    //                        echo "replace";
                                    $savedVal = $this->z['entries'][$yID][$xID][$zID];
                                    //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
                                    $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='$nameLabel' id='$nameLabel' value='" . $savedVal . "' $keyUpStr>";

                                }
                                else {
                                    //                        echo "insert";
                                    $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='$nameLabel' id='$nameLabel' value='' $keyUpStr>";
                                }
                                break;
                        }

//                        if ($this->priceConfig[$zID]['srcType'] == "formula") {
//                            //cuekin aja, ntar tinggal ngitung
//                            $savedVal = $this->z['entries'][$yID][$xID][$zID];
//                            $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
//                            if (isset($this->z['entries'][$yID][$xID][$zID])) {
//                                $this->z['entries'][$yID][$xID][$zID] = "<input type=number readonly class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value='" . $savedVal . "'>";
//
//                            } else {
//                                $this->z['entries'][$yID][$xID][$zID] = "<input type=number readonly class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value=''>";
//                            }
//                        } else {
//
//                            if (isset($this->z['entries'][$yID][$xID][$zID])) {
//                                //                        echo "replace";
//                                $savedVal = $this->z['entries'][$yID][$xID][$zID];
//                                //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
//                                $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
//                                $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value='" . $savedVal . "'>";
//
//                            } else {
//                                //                        echo "insert";
//                                $this->z['entries'][$yID][$xID][$zID] = "<input type=number class='form-control text-right' name='value_" . $yID . "_" . $xID . "_" . $zID . "' value=''>";
//                            }
//                        }

                        if (isset($this->z['rawEntries'][$yID][$xID][$zID])) {
                            //                        echo "replace";
                            $savedVal = $this->z['rawEntries'][$yID][$xID][$zID];
                            //                        $savedVal = $savedVal > 0 ? str_replace(".00", "", $savedVal) : "";
                            $savedVal = $savedVal > 0 ? ($savedVal + 0) : "";
                            $this->z['rawEntries'][$yID][$xID][$zID] = $savedVal;

                        }
                        else {
                            //                        echo "insert";
                            $this->z['rawEntries'][$yID][$xID][$zID] = "";
                        }
                        $this->z['listHistory'][$yID][$xID][$zID] = "<a class='btn btn-default' href='javascript:void(0)' data-toggle='tooltip' data-placement='left' title='view $zID update histories  ' onclick=\"$historyClick\"><i class='fa fa-clock-o'></i></a>";
                    }
                }
            }
        }
        //===init X
    }

    public function index()
    {
//        arrprint($this->priceConfig);
//        arrprint($this->z['entries']);
//         print_r($this->z['hisPrice']);die();
        $attached = isset($_GET['attached']) ? $_GET['attached'] : 0;
        if ($attached == '1') {
            $_SESSION['backLink'] = unserialize(base64_decode($_GET['backLink']));
            $formTarget = base_url() . get_class($this) . "/save/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?q=" . $this->q . "&attached=$attached&sID=" . $_GET['sID'];
        }
        else {
            $formTarget = base_url() . get_class($this) . "/save/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "/" . $this->uri->segment(6) . "?q=" . $this->q . "&attached=$attached";
        }

        $buttonLabel = "save entries";


        // region btn download
        $targetDownlaod = isset($this->config->item('dataToXlsx')['Spread']['target']) ? $this->config->item('dataToXlsx')['Spread']['target'] : "";

        $xlsxStr = "<li>";
        $xlsxStr .= form_button('download', "<i class='fa fa-download'></i>", "class='btn btn-info pull-left' onclick=\"location.href='" . base_url() . "$targetDownlaod/'\" title='export prices to Excel format'");
        $xlsxStr .= "</li>";
        // endregion btn download

        $pageStr = "";
        if ($this->y['total'] > 0) {

            $qs = isset($_GET['q']) ? "?q=$_GET[q]" : "";
            $jmlPage = ceil(($this->y['total'] / $this->y['limit']));
            $pages = $this->uri->segment(6) > 1 ? $this->uri->segment(6) : 1;
            $targetPages = base_url() . get_class($this) . "/index/" . $this->iy . "/" . $this->ix . "/" . $this->iz;

            $i = 0;
            $lastpage = $jmlPage;
            $lpm1 = $lastpage - 1;
            $counter = $i;
            $adjacents = 3;
            $prev = $pages - 1;
            $next = $pages + 1;

            if ($lastpage > 1) {
                $pageStr .= "<ul class='pagination no-margin'>";
                $pageStr .= $xlsxStr;
                // previous button
                if ($pages > 1) {
                    $pageStr .= "<li><a href='$targetPages/$prev$qs'>Prev</a></li>";
                }
                else {
                    $pageStr .= "<li class='disabled'><span>Prev</span></li>";
                }

                // pages button
                if ($lastpage < 7 + ($adjacents * 2)) //not enough pages to bother breaking it up
                {
                    for ($counter = 1; $counter <= $lastpage; $counter++) {
                        if ($counter == $pages) {
                            $pageStr .= "<li class='text-muted' style='background:#e0e0e0;'><span>$counter</span></li>";
                        }
                        else {
                            $pageStr .= "<li><a href='$targetPages/$counter$qs'>$counter</a></li>";
                        }
                    }
                }
                elseif ($lastpage > 5 + ($adjacents * 2)) {
                    //close to beginning; only hide later pages
                    if ($pages < 1 + ($adjacents * 2)) {
                        for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                            if ($counter == $pages) {
                                $pageStr .= "<li class='active'><span>$counter</span></li>";
                            }
                            else {
                                $pageStr .= "<li><a href='$targetPages/$counter$qs'>$counter</a></li>";
                            }
                        }
                        $pageStr .= "<li><span>...</span></li>";
                        $pageStr .= "<li><a href='$targetPages/$lpm1$qs'>$lpm1</a></li>";
                        $pageStr .= "<li><a href='$targetPages/$lastpage$qs'>$lastpage</a></li>";
                    } //in middle; hide some front and some back
                    elseif ($lastpage - ($adjacents * 2) > $pages && $pages > ($adjacents * 2)) {
                        $pageStr .= "<li><a href='$targetPages/1$qs'>1</a></li>";
                        $pageStr .= "<li><a href='$targetPages/2$qs'>2</a></li>";
                        $pageStr .= "<li><span>...</span></li>";

                        for ($counter = $pages - $adjacents; $counter <= $pages + $adjacents; $counter++) {
                            if ($counter == $pages) {
                                $pageStr .= "<li class='active'><span>$counter</span></li>";
                            }
                            else {
                                $pageStr .= "<li><a href='$targetPages/$counter$qs'>$counter</a></li>";
                            }
                        }
                        $pageStr .= "<li><span>...</span></li>";
                        $pageStr .= "<li><a href='$targetPages/$lpm1$qs'>$lpm1</a></li>";
                        $pageStr .= "<li><a href='$targetPages/$lastpage$qs'>$lastpage</a></li>";
                    } //close to end; only hide early pages
                    else {
                        $pageStr .= "<li><a href='$targetPages/1$qs'>1</a></li>";
                        $pageStr .= "<li><a href='$targetPages/2$qs'>2</a></li>";
                        $pageStr .= "<li><span>...</span></li>";

                        for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                            if ($counter == $pages) {
                                $pageStr .= "<li class='active'><span>$counter</span></li>";
                            }
                            else {
                                $pageStr .= "<li><a href='$targetPages/$counter$qs'>$counter</a></li>";
                            }
                        }
                    }
                }

                // nest button
                if ($pages < $counter - 1) {
                    $pageStr .= "<li><a href='$targetPages/$next$qs'>Next</a></li>";
                }
                else {
                    $pageStr .= "<li class='disabled'><span>Next</span></li>";
                }

                $pageStr .= "</ul>";

            }
            else {
                // kosong
            }
        }
        //         if($this->y['total']>0){
        //             $jmlPage=($this->y['total']/$this->y['limit']);
        // //            cekkuning($jmlPage);
        //             $pageStr.="<a class='btn btn-default' href='".base_url().get_class($this)."/"."index/".$this->iy."/".$this->ix."/".$this->iz."/1?q=".$this->q."'><span class='glyphicon glyphicon-home'></span></a>";
        //             for($i=2;$i<=$jmlPage;$i++){
        // //                echo "[$i]";
        //
        //                 if($i==$jmlPage){
        //                     $pageStr.="<a class='btn btn-default' href='".base_url().get_class($this)."/"."index/".$this->iy."/".$this->ix."/".$this->iz."/$i?q=".$this->q."'><span class='glyphicon glyphicon-tent'></span></a>";
        //                 }else{
        //                     $pageStr.="<a class='btn btn-default' href='".base_url().get_class($this)."/"."index/".$this->iy."/".$this->ix."/".$this->iz."/$i?q=".$this->q."'>".($i)."</a>";
        //                 }
        //
        //             }
        //         }


        //===filter tampilkan harga auto

        if (isset($_GET['viewHiddenPrice'])) {
            $_SESSION['ed']['viewHiddenPrice'] = $_GET['viewHiddenPrice'] == "true" ? true : false;
        }
        $viewHiddenPrice = isset($_SESSION['ed']['viewHiddenPrice']) ? $_SESSION['ed']['viewHiddenPrice'] : false;

        $strViewHidden = "";
        if ($this->selectedID == null) {

            $strViewHidden .= "<div class='panel-default'>";
            $vChecked = $viewHiddenPrice ? "checked" : "";
            $strViewHidden .= "<label>";
            $strViewHidden .= "<input type='checkbox' $vChecked onclick=\"location.href='" . base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "?q" . $this->q . "&viewHiddenPrice='+this.checked;\">";
            $strViewHidden .= "view hidden/auto prices";
            $strViewHidden .= "</label>";
            $strViewHidden .= "</div class='panel-default'>";
        }


        foreach ($this->priceConfig as $zID => $zSpec) {
            if ($this->priceConfig[$zID]['srcType'] == "formula") {
                //
                if ($viewHiddenPrice) {
                    $zLabels[$zID] = $zSpec['label'];
                }
            }
            else {
                $zLabels[$zID] = $zSpec['label'];

            }
        }


        // listing index
        //x ->supplier
        //y ->produk
        //z ->relasi produk supplier
        $data = array(
            "mode" => "Vendor",
            "errMsg" => $this->session->errMsg,
//            "title"         => "price list of '" . $this->iy . "'",
            "title" => "price list of 'produk vendor' ",
            "subTitle" => $this->q != "" ? $this->q : "type in box to search",
            "yLabels" => $this->y['entries'],
            "xLabels" => $this->x['entries'],
            "xLabels" => $this->x['entries'],
//            "xLabels"       => $temp,
            "zLabels" => $zLabels,
            "values" => $this->z['entries'],
            "history" => $this->z['listHistory'],
            "formTarget" => $formTarget,
            "buttonLabel" => $buttonLabel,
            "yHeader" => ucwords($this->iy),
            "self" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->uri->segment(3) . "/" . $this->uri->segment(4) . "/" . $this->uri->segment(5),
            "defaultKey" => $this->q != null ? $this->q : "type here to search " . $this->iy . "..",
            "startPage" => base_url() . get_class($this) . "/" . $this->uri->segment(2) . "/" . $this->iy . "/" . $this->ix . "/" . $this->iz,
            "attached" => $attached,
            "pageStr" => $pageStr,
            "pageOffside" => $this->pageOffset,
            "strViewHidden" => $strViewHidden,
            // "pageLimit"   => $this->limit_per_page,
        );
        //        arrPrint($data);
        $this->load->view('harga', $data);
        $this->session->errMsg = "";
    }

    public function save()
    {

        $insertList = array();
        $updateList = array();
        $oldUpdateList = array();

//        cekkuning("post");
//        arrPrint($_POST);
//
//        cekkuning("yEntries");
//        arrprint($this->y['entries']);
        $arrPostData = array();
        $tmpSaved = array();
        foreach ($this->y['entries'] as $yID => $yName) {
//            cekbiru("yID:" . $yID);
            foreach ($this->x['entries'] as $xID => $xName) {
//                cekbiru("xID:" . $xID);
                foreach ($this->priceConfig as $zID => $zSpec) {
                    $zName = $zSpec['label'];
//                    cekbiru("zID:" . $zID);
                    $pointName = "value_" . $yID . "_" . $xID . "_" . $zID;

//                    if (isset($_POST[$pointName])) {
                    $tmpSaved[$yID][$xID][$zID] = isset($_POST[$pointName]) ? $_POST[$pointName] : 0;
//                    }


//                    cekkuning("ada pointName $pointName");
                    if ($this->priceConfig[$zID]['srcType'] == "formula") {
                        $srcVal = $this->priceConfig[$zID]['srcSrc'];
//                        $varName = makeValue($srcVal, $this->existingValues[$yID][$xID], $this->existingValues[$yID][$xID], 0);
                        $varName = makeValue($srcVal, $tmpSaved[$yID][$xID], $tmpSaved[$yID][$xID], 0);
                        $arrPostData[$yID][$xID][$zID] = $varName;
//                        cekhitam("$zID berupa formula ($srcVal), menghasilkan $varName");
                    }
                    else {

                        if (isset($_POST[$pointName])) {
                            $varName = isset($_POST[$pointName]) ? $_POST[$pointName] : 0;
                            if (isset($this->z['rawEntries'][$yID][$xID])) {
                                $compareOldData = $this->z['rawEntries'][$yID][$xID];
                            }
                            else {
                                $compareOldData = array();
                            }

//                            cekkuning("mengisi postData bagian $yID, $xID, $zID dengan $varName");
                            $arrPostData[$yID][$xID][$zID] = $varName;
                            //                        arrPrint($compareOldData);
                            //                        echo "<br>";
                            //                        arrPrint($arrCompare);
                            //                        echo $pointName . "*$zID ||$zName*";
                            //                        if (isset($this->existingValues[$yID][$xID][$zID])) {
                            //                            echo "item existed";
                            //                            //==updateList ditambah
                            //                            $updateList[] = array(
                            //                                "where"  => array(
                            //                                    "jenis"       => $this->iy,
                            //                                    "jenis_value" => $zID,
                            //                                    "produk_id"   => $yID,
                            //                                    "cabang_id"   => $xID,
                            //                                    //                                "nilai"=>$varName,
                            //                                    //                                "dtime"=>date("Y-m-d H:i:s"),
                            //                                    //                                "oleh_id"=>$this->session->login['id'],
                            //                                    //                                "oleh_nama"=>$this->session->login['nama'],
                            //                                ),
                            //                                "update" => array(
                            //                                "nilai"     => $varName,
                            //                                "dtime"     => date("Y-m-d H:i:s"),
                            //                                "oleh_id"   => $this->session->login['id'],
                            //                                "oleh_nama" => $this->session->login['nama'],
                            //                                                                ),
                            //                            );
                            //                        }
                            //                        else {
                            //                            //==insertList ditambah
                            //                            $insertList[] = array(
                            //                                "jenis"       => $this->iy,
                            //                                "jenis_value" => $zID,
                            //                                "produk_id"   => $yID,
                            //                                "cabang_id"   => $xID,
                            //                                "nilai"       => $varName,
                            //                                "dtime"       => date("Y-m-d H:i:s"),
                            //                                "oleh_id"     => $this->session->login['id'],
                            //                                "oleh_nama"   => $this->session->login['nama'],
                            //                            );
                            //                        }
                            //                        echo "<br>";
                        }
                        else {
//                            cekkuning("TIDAK ada pointName $pointName");
                        }
                    }
                }

            }
        }


        foreach ($arrPostData as $yId => $yData) {
            foreach ($yData as $xId => $xData) {
                $oldData = $this->z['rawEntries'][$yId][$xId];
                $arrLast = array_diff_assoc($xData, $oldData);
//                arrprint($xData);
//                arrprint($oldData);
//                arrprint($arrLast);
                if (sizeof($arrLast) > 0) {
                    foreach ($arrLast as $zId => $varName) {
//                        cekbiru("cek mode");
                        if (isset($this->existingValues[$yId][$xId][$zId])) {
                            $oldUpdateList[] = array(
                                "old_content" => array(
                                    "jenis" => $this->iy,
                                    "jenis_value" => $zId,
                                    "nilai" => $this->z['rawEntries'][$yId][$xId][$zId],
                                    "cabang_id" => $xId,
                                ),

                            );
                            $updateList[] = array(
                                "where" => array(
                                    "jenis" => $this->iy,
                                    "jenis_value" => $zId,
                                    "produk_id" => $yId,
//                                    "cabang_id"   => $xId,
                                    "suppliers_id" => $xId,
                                    //                                "nilai"=>$varName,
                                    //                                "dtime"=>date("Y-m-d H:i:s"),
                                    //                                "oleh_id"=>$this->session->login['id'],
                                    //                                "oleh_nama"=>$this->session->login['nama'],
                                ),
                                "update" => array(
                                    "nilai" => $varName,
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                ),
                                "history" => array(
                                    "produk_id" => $yId,
                                    "suppliers_id" => $xId,
                                    "nilai" => $varName,
                                    "dtime" => date("Y-m-d H:i:s"),
                                    "oleh_id" => $this->session->login['id'],
                                    "oleh_nama" => $this->session->login['nama'],
                                    "jenis" => $this->iy,
                                    "jenis_value" => $zId,
//                                    "cabang_id"   => $xId,
                                ),
                            );
//                            cekmerah("updating");
                        }
                        else {
                            $insertList[] = array(
                                "jenis" => $this->iy,
                                "jenis_value" => $zId,
                                "produk_id" => $yId,
                                "suppliers_id" => $xId,
                                "nilai" => $varName,
                                "dtime" => date("Y-m-d H:i:s"),
                                "oleh_id" => $this->session->login['id'],
                                "oleh_nama" => $this->session->login['nama'],
                                "cabang_id" => $this->session->login['cabang_id'],
                            );
//                            cekmerah("inserting");
                        }
                    }

                }

            }
        }

        //        die("saving..");
        //        matiHere();
        //        arrPrint($updateList);
        //        arrPrint($oldUpdateList);


//        cekkuning("arrPostData");
//        arrprint($arrPostData);
//
//        die();


//        die("inininin");
//        cekHere($this->z['mdlName']);
//        matiHEre();
        $resultIds = array();
        if (sizeof($updateList) > 0 || sizeof($insertList) > 0) {

            $this->db->trans_start();
            $zo = new $this->z['mdlName']();

            if (sizeof($insertList) > 0) {
                foreach ($insertList as $iSpec) {
                    $resultIds[] = $zo->addData($iSpec) or die("failed to add new data");
                    cekMerah($this->db->last_query());
                }
            }
            if (sizeof($updateList) > 0) {
                //                cekHijau("iki");
                foreach ($updateList as $uKey => $uSpec) {

                    $insertID = $zo->updateData($uSpec['where'], $uSpec['update']) or die("failed to update data");
                    $tempOld = $oldUpdateList[$uKey]["old_content"];
                    $resultIds[] = $insertID;

                    cekMerah($this->db->last_query());

                    cekBiru($this->z["mdlName"]);
                    $data_id = $uSpec['where']['produk_id'];
                    $this->load->model("Mdls/" . "MdlDataHistory");
                    $hTmp = new MdlDataHistory();
                    $tmpHData = array(
                        "orig_id" => $insertID,
                        "mdl_name" => $this->z["mdlName"],
                        "mdl_label" => $this->z["label"],
                        "old_content" => base64_encode(serialize($tempOld)),
                        "old_content_intext" => print_r($tempOld, true),
                        "new_content" => base64_encode(serialize($uSpec["history"])),
                        "new_content_intext" => print_r($uSpec["history"], true),
                        "label" => "price",
                        "oleh_id" => $this->session->login['id'],
                        "oleh_name" => $this->session->login['nama'],
                        "data_id" => $data_id,
                        "cabang_id" => $uSpec["history"]["cabang_id"],

                    );
                    //                    arrPrint($tmpHData);
                    $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
                    cekBiru($this->db->last_query());
                }
            }
//            matiHEre("hooppp  comat comit");
            $this->db->trans_complete() or die("Gagal saat berusaha  commit data-update!");
//            echo lgShowSuccess("", "New setting successfully save");
        }
        else {
            cekHere($this->z['mdlName']);
//            matiHere();
            echo(lgShowAlert("You did not make any change. No data saved"));
            echo "<script>topReload(2500)</script>";
            echo topReload();
            echo "</script>";
            die();
        }
        if (sizeof($resultIds) > 0) {
            $this->session->errMsg = "posted data has been saved";
        }
        else {
            $this->session->errMsg = "";
        }

//                die();
        if (isset($_GET['attached']) && $_GET['attached'] == '1') {

            $actionTarget = "top.BootstrapDialog.closeAll();top.BootstrapDialog.show(                                   {
                                       title:'Modify entry..',
                                        message: " . '$' . "('<div></div>').load('" . $_SESSION['backLink'] . "'),
                                        draggable:false,
                                        size:top.BootstrapDialog.SIZE_WIDE,                                        
                                        closable:true,
                                        }
                                        );";

            echo "<html>";
            echo "<head>";
            echo "<script src=\"" . cdn_suport() . "AdminLTE-2.3.11/plugins/jQuery/jquery-2.2.3.min.js\"></script>";
            echo "</head>";
            echo "<body onload=\"$actionTarget\">";
            echo "</body>";

        }
        else {
            echo "<script>top.location.reload();</script>";
        }
    }

    public function HargaHistory()
    {
        $this->load->helper("he_date_time");
        $content = "";
        $className = "Mdl" . $this->uri->segment(2);
        $ctrlName = $this->uri->segment(1);
        $selectedID = $this->uri->segment(3);
        $cabang_id = $this->uri->segment(4);
        $label = $this->uri->segment(5);
        //        cekHijau("$className|| $ctrlName||$selectedID||$cabang_id||$label");
        $this->load->model("Mdls/" . $className);

        $o = new $className();
        $listedFields = $o->getListedFields();
        $fields = $o->getFields();

        $p = new Layout("", "", "application/template/lte/index.html");
        $this->load->model("Mdls/" . "MdlHargaHistory");
        $h = new MdlHargaHistory();
        //        $h->addFilter("label='price'");
        //        $h->addFilter("data_id='$selectedID'");
        $conditional = "data_id='$selectedID' and label='price' and cabang_id='$cabang_id' order by id desc";
        $tmpH = $h->lookupByCondition($conditional)->result();
//        cekkuning($this->db->last_query());
        $arrHistory = array();
        foreach ($tmpH as $tempContent) {
            $data_temp = array();
            foreach ($listedFields as $kolom => $alias) {
                if (array_key_exists($kolom, $tempContent)) {
                    $data_temp[$alias] = $tempContent->$kolom;
                }
            }

            if (isset($data_temp['lama'])) {
                $dataOld_decode = blobDecode($data_temp['lama']);
                $dataNew_decode = blobDecode($data_temp['baru']);
                //arrPrint($data_decode);
                if (in_array($label, $dataNew_decode)) {
                    $hargaOld = $dataOld_decode["nilai"] > 0 ? number_format($dataOld_decode["nilai"]) : "";
                    $hargaNew = $dataNew_decode["nilai"] > 0 ? number_format($dataNew_decode["nilai"]) : "";
                    $dtime = $data_temp["tanggal"];
                    $oleh = $data_temp["PIC"];

                    $arrHistory[] = array(
//                        "tanggal" => formatTanggal($dtime),
                        "tanggal" => ($dtime),
                        "PIC" => $oleh,
                        "lama" => $hargaOld,
                        "baru" => $hargaNew,
//                        "label" => $label,
                    );
                }


            }

        }

        if (sizeof($arrHistory) > 0) {
            $content .= ("<div class='table-responsive'>");
            $content .= ("<table class='table table-condensed table-bordered'>");
            $content .= ("<tr bgcolor='#dedede'>");
            $content .= ("<td >No</td>");
            foreach ($listedFields as $fName => $label) {
                //                $colsPan_x = $label == "harga" ? "colspan='2'": "rowspan='2'";
                $content .= ("<td >");
                $content .= ($label);
                $content .= ("</td>");
            }
            $content .= ("</tr>");
            $i = 0;
            foreach ($arrHistory as $key => $row) {
                $i++;
                $content .= ("<tr>");
                $content .= ("<td>$i</td>");
                foreach ($row as $alias => $value) {
                    if (($alias = "lama") or ($alias = "baru")) {
                        $cls_td = "class='text-right'";
                    }
                    else {
                        $cls_td = "";
                    }

                    $content .= ("<td >");
                    $content .= ($value);
                    $content .= ("</td>");
                }
                $content .= ("</tr>");

            }
            $content .= ("</table>");
            $content .= ("</div class='table-responsive'>");
        }
        else {
            $content .= ("<div class='alert alert-warning text-center'>");
            $content .= ("this item has no history entry");
            $content .= ("</div class='alert alert-warning'>");
        }
        echo $content;
    }

}