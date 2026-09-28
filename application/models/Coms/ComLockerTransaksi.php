<?php
// START OF COMPLETE REPEATED LOGIC

class ComLockerTransaksi extends MdlMother
{

    private $inParams = array();
    private $outParams = array();
    private $writeMode;
    private $outFields = array(
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
                        $msg = "Insufficient stock for " . $paramAsli['static']['nama'] . " with state: " . $paramAsli['static']['state'] . ", needed: " . $paramAsli['static']['jumlah'] . ", avail: " . $_preValue["jumlah"];
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
        $this->load->model("Mdls/MdlLockerTransaksi");
        $l = new MdlLockerTransaksi();

        $localFilters = array(
            "jenis" => $jenis,
            "cabang_id" => $cabang_id,
            "gudang_id" => $gudang_id,
            "produk_id" => $produk_id,
            "state" => $state,
            "oleh_id" => $olehID,
            "transaksi_id" => $transaksiID,
        );

        $query = $this->db->select()
            ->from($l->getTableName())
            ->where($localFilters)
            ->limit(1)
            ->get_compiled_select();
        $tmp = $this->db->query("{$query} FOR UPDATE")->result();

        $result = array();
        if (sizeof($tmp) > 0) {
            $result = array(
                "id" => $tmp[0]->id,
                "jumlah" => $tmp[0]->jumlah,
            );
        }
        else {
            $result = array();
        }

        return $result;
    }

    public function exec()
    {
        if (sizeof($this->outParams) > 0) {
            $insertIDs = array();
            foreach ($this->outParams as $ctr => $params) {
                $this->load->model("Mdls/MdlLockerTransaksi");
                $l = new MdlLockerTransaksi();
                $mode = $params['mode'];
                $id_tbl = isset($params['id_tbl']) ? $params['id_tbl'] : 0;
                unset($params['mode']);
                unset($params['id_tbl']);
                switch ($mode) {
                    case "new":
                        $insertIDs[] = $l->addData($params);
                        break;
                    case "update":
                        $l->setFilters(array());
                        $whereCondition = array(
                            "id" => $id_tbl,
                            "cabang_id" => $params['cabang_id'],
                            "gudang_id" => $params['gudang_id'],
                        );
                        $insertIDs[] = $l->updateData($whereCondition, $params);
                        break;
                    default:
                        die("unknown writemode!");
                        break;
                }
                cekBiru("LOCKER TRANSAKSI " . $this->db->last_query());
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
// END OF COMPLETE REPEATED LOGIC
