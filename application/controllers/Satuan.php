<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/8/2018
 * Time: 3:20 PM
 */
class Satuan extends CI_Controller
{

    private $priceConfig = array();

    private $existingValues = array();
    private $q;
    private $selectedID;
    private $requariedParam = array(
        "satuan_id", "qty",
    );

    public function getRequariedParam()
    {
        return $this->requariedParam;
    }

    public function setRequariedParam($requariedParam)
    {
        $this->requariedParam = $requariedParam;
    }

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

    }

    public function index()
    {
//        cekHitam(__LINE__);
        $attached = isset($_GET['attached']) ? $_GET['attached'] : 0;
        if ($attached == '1') {
            $_SESSION['backLink'] = blobDecode($_GET['backLink']);
        }
        // arrPrint($this->uri->segment_array());
        // model produk
        // arrPrint($_GET);
        // arrPrint(blobDecode($_GET['backLink']));

        $connectingdata = array(
            //fields =>model
            "satuan_nama"       => array(
                "model"      => "MdlSatuan",
                "field"      => array("id", "nama"),
                "default"    => false,
                "inputField" => array(
                    "id"   => "satuan_id",
                    "nama" => "satuan_nama",
                ),
                "optionKey"  => "satuan_id",
                "inputIndex" => array("satuan_id" => "satuan_nama"),
            ),
            "satuan_dasar_nama" => array(
                "model"      => "MdlProduk",
                "field"      => array("satuan_id", "satuan"),
                "parentKey"  => "id",
                "default"    => true,
                "inputField" => array(
                    "satuan_id" => "satuan_dasar_id",
                    "satuan"    => "satuan_dasar_nama",
                ),
                "optionKey"  => "satuan_dasar_id",
                "inputIndex" => array("satuan_dasar_id" => "satuan_dasar_nama"),

            ),
        );


        $tokoID = my_toko_id();
        $prods = "Mdl" . $this->uri->segment(3);
        $sat = "Mdl" . $this->uri->segment(1);
        $rel = "Mdl" . $this->uri->segment(4);
        $this->load->model("Mdls/" . $prods);//produk
        $this->load->model("Mdls/" . $sat);//satuan
        $this->load->model("Mdls/" . $rel);//relasi

        $p = new $prods();
        $src_p = $p->callSpecs($this->selectedID);
        $produk_speks = $src_p[$this->selectedID];
        // arrPrintKuning($produk_speks);

        $s = new $sat();
        $r = new $rel();
        $relKolom = $r->getListedFields();
//        cekHijau("$rel");
//        arrPrintHijau($relKolom);
//cekMErah($tokoID);
        $fields = $r->getFields();
//        $r->addFilter("toko_id='$tokoID'");
        $r->addFilter("produk_id='" . $this->selectedID . "'");
        $tmpRelData = $r->lookUpAll()->result();
        $usedSatuan = array();
        if (sizeof($tmpRelData) > 0) {
            foreach ($tmpRelData as $tmpRelData_0) {
                $usedSatuan[] = $tmpRelData_0->satuan_id;
            }
        }
        $paramData = array();
        foreach ($relKolom as $col => $label) {
            if (isset($connectingdata[$col])) {
                $mdlName = $connectingdata[$col]["model"];
                $selectField = $connectingdata[$col]["field"];
                $selectFieldAttr = $connectingdata[$col]["default"];
                $selectInputField = $connectingdata[$col]["inputField"];
                $selectInputIndex = $connectingdata[$col]["inputIndex"];
                $optionKey = $connectingdata[$col]["optionKey"];

                $this->load->model("Mdls/" . $mdlName);
                $x = new $mdlName();
                if (isset($connectingdata[$col]["parentKey"])) {
                    $keyParent = $connectingdata[$col]["parentKey"];
                    $x->addFilter("$keyParent='$this->selectedID'");
//                    $x->addFilter("toko_id='" . my_toko_id() . "'");

                }
                else {
//                    $x->addFilter("toko_id='" . my_toko_id() . "'");
                }
                $temp = $x->lookUpAll()->result();
                // ceklIme($this->db->last_query());
                // $paramData_0 = array();
                foreach ($temp as $i => $tempData) {
                    foreach ($selectField as $ck) {
                        if (isset($tempData->$ck)) {
                            // arrprint($selectInputField);
                            $newKey = $selectInputField[$ck];
                            // cekLime("oldkey ".$ck." newkey ".$newKey);
                            $paramData[$col]["data"][$i][$newKey] = $tempData->$ck;
                            $paramData[$col]["input"] = $selectInputIndex;
                            $paramData[$col]["attr"] = $selectFieldAttr ? "selected" : "";
                            $paramData[$col]["keyField"] = $optionKey;
                            // $paramData[$col]["disabled"] = true;
                        }
                    }
                }

            }
        }

         arrPrint($paramData);
        //endregion
        $data = array(
            "mode"         => "edit_satuan",
            "errMsg"       => $this->session->errMsg,
            "title"        => "conected  " . $this->uri->segment(1),
            "subTitle"     => "",
            "viewFields"   => $relKolom,
            "prevData"     => $tmpRelData,
            "relKolom"     => $fields,
            "selectedID"   => $this->selectedID,
            "formTarget"   => base_url() . get_class() . "/doRelasi",
            "buttonLabel"  => "",
            "parentId"     => "",
            "contens"      => "",
            "relData"      => $paramData,
            "submitBtn"    => "",
            "editTarget"   => base_url() . get_class() . "/doEditRelasi",
            "deleteTarget" => base_url() . get_class() . "/doDelete",
            "produk_speks" => $produk_speks,
        );
        $this->load->view('editor', $data);
        $this->session->errMsg = "";
    }

    public function doRelasi()
    {
        // arrPrint($_POST);
        // matiHEre();

        $this->load->model("Mdls/MdlProdukSatuanRelasi");
        $p = new MdlProdukSatuanRelasi();
        $this->db->trans_start();
        $preval = $this->preValues($_POST['toko_id'], $_POST['produk_id'], $_POST['satuan_id']);
        if ($preval <> null) {
            matiHEre("Satuan (" . $preval . ") sudah digunakan, silahkan pilih satuan yang lain untuk menghindari konflik data");
        }

        // arrPrintWebs($this->requariedParam);
        $postData = (array_filter($_POST));
        $validate = 0;
        foreach ($this->requariedParam as $k => $keys) {
            if (!isset($postData[$keys])) {
                $validate++;
            }
        }
        if ($validate == 0) {
            $p->addData($_POST);
            if (method_exists($p, "paramSyncNamaNama")) {
                $syncNamaNamaMdls = method_exists($p, "paramSyncNamaNama") ? $p->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                    // arrprint($syncNamaNamaParams);
                    $id_ygdisync = isset($_POST[$syncNamaNamaParams['id']]) ? $_POST[$syncNamaNamaParams['id']] : "";
                    $p->setTokoId(my_toko_id());
                    // matiHEre(my_toko_id());
                    if ($id_ygdisync > 0) {
                        cekHitam($id_ygdisync);
                        $p->syncNamaNama($id_ygdisync);
                        cekHitam($this->db->last_query());
                    }
                }
            }
        }
        // matiHEre($validate);

        // mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa ..");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit transaction!");

        $script = "<script>
                            var url_result2 = top.$('#result2').attr('src')
        console.log(url_result2);
        top.$('#result2').attr('src', url_result2)
                           </script>";
        echo $script;

    }

    public function doEditRelasi()
    {
        // arrPrint($_GET);
        // matiHEre(__LINE__);
        $key = $_GET['key'];
        $this->load->model("Mdls/MdlProdukSatuanRelasi");
        $this->db->trans_start();
        $preval = $this->prevalues($_GET['tokoID'], $_GET['pid'], $_GET['value']);
        // matiHEre($temp);
        if ($preval <> null) {
            matiHEre("Satuan $preval sudah direlasikan, silahkan hapus terlebih dahulu satuan $preval dari relasi atau gunakan satuan lainnya.");
        }
        else {
            $p = new MdlProdukSatuanRelasi();
            $p->addFilter("id='" . $_GET['id'] . "'");
            $temp = $p->lookUpAll()->result();
            // ceklIme($this->db->last_query());
            $updateList = array($_GET['key'] => $_GET['value']);
            $where = array("id" => $_GET['id']);
            $insertUpdate = $p->updateData($where, $updateList);

            if (method_exists($p, "paramSyncNamaNama")) {
                $syncNamaNamaMdls = method_exists($p, "paramSyncNamaNama") ? $p->paramSyncNamaNama() : mati_disini("paramSyncNamaNama belum terdifine");
                foreach ($syncNamaNamaMdls as $syncNamaNamaMdl => $syncNamaNamaParams) {
                    // arrprint($syncNamaNamaParams);
                    $id_ygdisync = isset($_POST[$syncNamaNamaParams['id']]) ? $_POST[$syncNamaNamaParams['id']] : "";
                    $p->setTokoId(my_toko_id());
                    if ($id_ygdisync > 0) {
                        // cekHitam($id_ygdisync);
                        $p->syncNamaNama($id_ygdisync);
                    }
                }
            }
            //region histroy
            $this->load->model("Mdls/MdlDataHistory");
            $hTmp = new MdlDataHistory();
            $tmpHData = array(
                "orig_id"            => $temp[0]->id,
                "mdl_name"           => "MdlProdukSatuanRelasi",
                "mdl_label"          => get_class($this),
                "old_content"        => base64_encode(serialize((array)$temp[0])),
                "old_content_intext" => print_r($temp[0], true),
                "new_content"        => base64_encode(serialize(array($_GET['key'] => $_GET['value']))),
                "new_content_intext" => print_r(array($_GET['key'] => $_GET['value']), true),
                "label"              => "applied",
                "oleh_id"            => $this->session->login['id'],
                "oleh_name"          => $this->session->login['nama'],
            );
            $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            cekHitam($this->db->last_query());
            //endregion
        }
        $this->db->trans_complete() or die("Gagal saat berusaha  commit data!");
        $script = "<script>
                            var url_result2 = top.$('#result2').attr('src')
        console.log(url_result2);
        top.$('#result2').attr('src', url_result2)
                           </script>";

        echo $script;

    }

    public function doDelete()
    {
        $key = $_GET['key'];
        $this->load->model("Mdls/MdlProdukSatuanRelasi");
        $p = new MdlProdukSatuanRelasi();
        $p->addFilter("id='" . $_GET['id'] . "'");
        $temp = $p->lookUpAll()->result();
        $this->db->trans_start();
        $where = array("id" => $_GET['id']);
        $updateList = array(
            "trash"  => "1",
            "status" => "0",
        );
        $insertUpdate = $p->updateData($where, $updateList);
        //region histroy
        $this->load->model("Mdls/MdlDataHistory");
        $hTmp = new MdlDataHistory();
        $tmpHData = array(
            "orig_id"            => $temp[0]->id,
            "mdl_name"           => "MdlProdukSatuanRelasi",
            "mdl_label"          => get_class($this),
            "old_content"        => base64_encode(serialize((array)$temp[0])),
            "old_content_intext" => print_r($temp[0], true),
            "new_content"        => base64_encode(serialize(array($_GET['key'] => $_GET['value']))),
            "new_content_intext" => print_r(array($_GET['key'] => $_GET['value']), true),
            "label"              => "applied",
            "oleh_id"            => $this->session->login['id'],
            "oleh_name"          => $this->session->login['nama'],
        );
        $insertID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

        //endregion

        // mati_disini("LINE: " . __LINE__ . " under maintenance, tunggu beberapa saat lagi yaa ..");
        $this->db->trans_complete() or die("Gagal saat berusaha  commit data!");
        $script = "<script>
                            var url_result2 = top.$('#result2').attr('src')
        console.log(url_result2);
        top.$('#result2').attr('src', url_result2)
                           </script>";
        echo $script;
    }

    public function prevalues($toko_id, $produk_id, $satuan_id)
    {


        $this->load->model("Mdls/MdlProdukSatuanRelasi");
        $p = new MdlProdukSatuanRelasi();
//        $p->addFilter("toko_id='$toko_id'");
        $p->addFilter("produk_id='$produk_id'");
        $p->addFilter("satuan_id='$satuan_id'");
        $temp = $p->lookUpAll()->result();
        if (sizeof($temp)) {
            $values = $temp[0]->satuan_nama;
            return "$values";
        }
        else {
            return null;
        }
        // cekBiru($this->db->last_query());
        // arrPrint($temp);
        // matiHEre();
    }


}