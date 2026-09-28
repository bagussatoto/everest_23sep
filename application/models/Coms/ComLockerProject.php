<?php


class ComLockerProject extends MdlMother
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

        "jenis",
        "produk_id",
        "cabang_id",
        "nama",
        "satuan",
        "state",
        "jumlah",
        "oleh_id",
        "oleh_nama",
        "transaksi_id",
        "transaksi_no",
        "nomer",
        "gudang_id",
        "status",
        "trash",
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {

        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $lCtr => $paramAsli) {
                $lCounter++;
                foreach ($paramAsli['static'] as $key => $value) {
                    if (in_array($key, $this->outFields)) {
                        $this->outParams[$lCounter][$key] = $value;
                    }
                }
                $defaultOlehID = isset($paramAsli['static']['oleh_id']) ? $paramAsli['static']['oleh_id'] : 0;
                $defaultTransID = isset($paramAsli['static']['transaksi_id']) ? $paramAsli['static']['transaksi_id'] : 0;
                $defaultGudangID = isset($paramAsli['static']['gudang_id']) ? $paramAsli['static']['gudang_id'] : 0;
                $rejection = isset($paramAsli['static']['rejection']) ? $paramAsli['static']['rejection'] : 0;

                if ($rejection == 1) {
                    $defaultTransID = isset($paramAsli['static']['reference_id']) ? $paramAsli['static']['reference_id'] : 0;
                    $_preValue = $this->cekPreValue(
                        $paramAsli['static']['jenis'],
                        $paramAsli['static']['cabang_id'],
                        $paramAsli['static']['produk_id'],
                        $paramAsli['static']['state'],
                        $defaultOlehID,
                        $defaultTransID,
                        $defaultGudangID
                    );
                }
                else {

                    $_preValue = $this->cekPreValue(
                        $paramAsli['static']['jenis'],
                        $paramAsli['static']['cabang_id'],
                        $paramAsli['static']['produk_id'],
                        $paramAsli['static']['state'],
                        $defaultOlehID,
                        $defaultTransID,
                        $defaultGudangID
                    );

                }
                showLast_query("kuning");

                if (sizeof($_preValue) > 0) {

                    $this->outParams[$lCounter]["jumlah"] = ($paramAsli['static']['jumlah'] + $_preValue["jumlah"]);
                    $this->outParams[$lCounter]["mode"] = "update";
                    $this->outParams[$lCounter]["id_tbl"] = $_preValue["id"];

                    if ($this->outParams[$lCounter]["jumlah"] < 0) {
                        $msg = "Komisi project " . $paramAsli['static']['nama'] . " sudah diklaim ";

                        die(lgShowAlert($msg));
                    }
                }
                else {

                    $this->outParams[$lCounter]["mode"] = "new";
                }
            }
        }


        if (sizeof($this->outParams) > 0) {
            return true;
        }
        else {
            return false;
        }

    }

    private function cekPreValue($jenis, $cabang_id, $produk_id, $state = "active", $olehID = 0, $transaksiID = 0, $gudang_id = 0)
    {

        $this->load->model("Mdls/MdlLockerProject");
        $l = new MdlLockerProject();
        $l->setFilters(array());
        $l->addFilter("jenis='$jenis'");
        $l->addFilter("cabang_id='$cabang_id'");
        $l->addFilter("gudang_id='$gudang_id'");
        $l->addFilter("produk_id='$produk_id'");
        $l->addFilter("state='$state'");
        $l->addFilter("oleh_id='$olehID'");
        $l->addFilter("transaksi_id='$transaksiID'");

        $result = array();
        $localFilters = array();
        if (sizeof($l->getfilters()) > 0) {
            foreach ($l->getfilters() as $f) {
                $tmpArr = explode("=", $f);
                $localFilters[$tmpArr[0]] = trim($tmpArr[1], "'");
            }
        }

        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();


        if (sizeof($tmp) > 0) {
            $result = $tmp[0]->jumlah;
            $result = array(
                "id" => $tmp[0]->id,
                "jumlah" => $tmp[0]->jumlah,
            );
        }
        else {
//            $result = null;
            $result = array();
        }


        return $result;
    }

    public function exec()
    {

        if (sizeof($this->outParams) > 0) {
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlLockerProject");
                $l = new MdlLockerProject();
                $insertIDs = array();
                $mode = $params['mode'];
                $id_tbl = $params['id_tbl'];
                unset($params['mode']);
                unset($params['id_tbl']);
                switch ($mode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    case "update":
                        $l->setFilters(array());
                        $insertIDs[] = $l->updateData(
                            array(
//                                "cabang_id" => $params['cabang_id'],
//    //                            "gudang_id" => $params['gudang_id'],
//                                "produk_id" => $params['produk_id'],
//                                "state" => $params['state'],
//                                "oleh_id" => $params['oleh_id'],
//                                "transaksi_id" => $params['transaksi_id'],
                                "id" => $id_tbl,
                            ),
                            $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru("LOCKER PROJECT " . $this->db->last_query());

            }
            if (sizeof($insertIDs) > 0) {
                return true;
            }
            else {
                return false;
            }

        }
        else {
            die("nothing to write down here");
            return false;
        }

    }
}