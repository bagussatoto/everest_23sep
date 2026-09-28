<?php
// START OF COMPLETE REPEATED LOGIC

class MdlLockerTransaksi extends MdlMother
{

    protected $tableName;
    protected $fields = array();
    protected $indexFields;
    protected $filters = array(
        "stock_locker_transaksi.jenis_locker='transaksi'",
        "stock_locker_transaksi.jenis='transaksi'",
    );
    protected $sortBy = array(
        "kolom" => "jumlah",
        "mode" => "DESC",
    );
    protected $listedFieldsSelectItem = array(
        "stock_locker_transaksi.nama",
        "stock_locker_transaksi.satuan",
    );

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    function __construct()
    {
        parent::__construct();
        $this->tableName = "stock_locker_transaksi";
        $this->indexFields = "id";
        $this->fields = array(
            "id" => array(
                "label" => "id",
                "type" => "int", "length" => "24", "kolom" => "id",
                "inputType" => "text",
            ),
            "produk_id" => array(
                "label" => "produk_id",
                "type" => "int", "length" => "24", "kolom" => "produk_id",
                "inputType" => "text",
            ),
            "nama" => array(
                "label" => "nama",
                "type" => "int", "length" => "24", "kolom" => "nama",
                "inputType" => "text",
            ),
            "jumlah" => array(
                "label" => "jumlah",
                "type" => "int", "length" => "24", "kolom" => "jumlah",
                "inputType" => "varchar",
            ),
            "satuan" => array(
                "label" => "satuan",
                "type" => "int", "length" => "24", "kolom" => "satuan",
                "inputType" => "varchar",
            ),
            "barcode" => array(
                "label" => "satuan",
                "type" => "int", "length" => "24", "kolom" => "barcode",
                "inputType" => "varchar",
            ),
        );
        $this->listedFieldsView = array();
        $this->listedFieldsForm = array();
        $this->validationRules = array();
        $this->listedFieldsHidden = array();
    }

    public function cekLoker($cab, $prod, $state, $oleh = 0, $transaksi_id = 0, $gudang_id = 0)
    {
        $this->addFilter("cabang_id='$cab'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("produk_id='$prod'");
        $this->addFilter("state='$state'");
        if ($oleh != 0) {
            $this->addFilter("oleh_id='$oleh'");
        }
        if ($transaksi_id > 0) {
            $this->addFilter("transaksi_id='$transaksi_id'");
        }
        $tmp = $this->lookupAll()->result();
        if (sizeof($tmp) > 0) {
            return array(
                "id" => $tmp[0]->id,
                "jumlah" => $tmp[0]->jumlah,
            );
        }
        else {
            return array();
        }
    }

    public function fetchStates($cab, $gudang_id, $ids = 0)
    {
        $this->addFilter("cabang_id='$cab'");
        $this->addFilter("gudang_id='$gudang_id'");
        $this->addFilter("transaksi_id='0'");
        if (is_array($ids) && sizeof($ids) > 0) {
            $this->addFilter("produk_id in (" . implode(",", $ids) . ")");
        }

        $tmp = $this->lookupAll()->result();

        $results = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $pID = $row->produk_id;

                if (!isset($results[$pID][$row->state])) {
                    $results[$pID][$row->state] = 0;
                }

                $results[$pID][$row->state] += $row->jumlah;
            }
        }
        else {
            $results = array();
        }
        return $results;
    }

    public function fetchStates2($cab, $gudang_id, $ids = 0)
    {
        $this->addFilter("cabang_id='$cab'");
        $this->addFilter("gudang_id='$gudang_id'");
        if (is_array($ids) && sizeof($ids) > 0) {
            $this->addFilter("produk_id in (" . implode(",", $ids) . ")");
        }
        $tmp = $this->lookupAll()->result();

        $results = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $pID = $row->produk_id;

                if ($row->transaksi_id > 0) {
                    if (!isset($results[$pID][$row->state . "_trID"])) {
                        $results[$pID][$row->state . "_trID"] = 0;
                    }
                    $results[$pID][$row->state . "_trID"] += $row->jumlah;
                }
                else {
                    if (!isset($results[$pID][$row->state])) {
                        $results[$pID][$row->state] = 0;
                    }
                    $results[$pID][$row->state] += $row->jumlah;
                }
            }
        }
        else {
            $results = array();
        }
        return $results;
    }

    public function execLocker($mainGate, $nextStepNum, $refID, $newID)
    {
        $userSession = isset($this->session->login) ? $this->session->login : array();
        $olehID = isset($userSession['id']) ? $userSession['id'] : 0;
        $cabangID = isset($userSession['cabang_id']) ? $userSession['cabang_id'] : (function_exists('my_cabang_id') ? my_cabang_id() : 0);

        if ($refID != NULL) {
            if (is_array($refID)) {
                $arrFilter = array(
                    "jenis='transaksi'",
                    "jenis_locker='transaksi'",
                    "state='hold'",
                    "cabang_id='$cabangID'",
                    "oleh_id='$olehID'",
                    "jumlah>'0'",
                );
                $this->setFilters(array());
                foreach ($arrFilter as $f) {
                    $this->addFilter($f);
                }
                $this->addFilter("transaksi_id in ('" . implode("','", $refID) . "')");
            }
            else {
                $arrFilter = array(
                    "jenis='transaksi'",
                    "jenis_locker='transaksi'",
                    "state='hold'",
                    "cabang_id='$cabangID'",
                    "oleh_id='$olehID'",
                    "transaksi_id='$refID'",
                    "jumlah>'0'",
                );
                $this->setFilters(array());
                foreach ($arrFilter as $f) {
                    $this->addFilter($f);
                }
            }
            $tmpS = $this->lookupAll()->result();

            if (sizeof($tmpS) > 0) {
                $where = array("id" => $tmpS[0]->id);
                $data = array("jumlah" => "0");
                $this->setFilters(array());
                $this->updateData($where, $data);
            }
        }

        if ($newID != NULL) {
            if ($nextStepNum != 0) {
                $ltActive = array(
                    "state" => "active",
                    "produk_id" => $newID,
                    "transaksi_id" => $newID,
                    "oleh_id" => 0,
                    "oleh_nama" => "",
                    "jenis" => "transaksi",
                    "jenis_locker" => "transaksi",
                    "jumlah" => "1",
                );
                $this->addData($ltActive);
            }
        }
    }

    // Helper khusus untuk penguncian transaksi kelompok finance
    public function checkLockFinance($cabang_id, $jenis_tr, $booking_number)
    {
        $this->db->select('*');
        $this->db->from('stock_locker_transaksi');
        $this->db->where('cabang_id', $cabang_id);
        $this->db->where('transaksi_id', $jenis_tr);
        $this->db->where('nama', $booking_number);
        $this->db->where('state', 'hold');
        $this->db->where('jumlah', '1');
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query && $query->num_rows() > 0) {
            $row = $query->row_array();
            $userSession = isset($this->session->login) ? $this->session->login : array();
            $my_id = isset($userSession['id']) ? $userSession['id'] : 0;
            $row['is_my_lock'] = ($row['oleh_id'] == $my_id);
            return $row;
        }
        return array();
    }

    public function holdLockFinance($cabang_id, $jenis_tr, $booking_number, $oleh_id = 0, $oleh_nama = '')
    {
        $existing = $this->checkLockFinance($cabang_id, $jenis_tr, $booking_number);
        $now = date('Y-m-d H:i:s');
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Browser';

        if (sizeof($existing) > 0) {
            if ($existing['oleh_id'] == $oleh_id) {
                // Update timestamp keaktifan
                $this->db->where('id', $existing['id']);
                $this->db->update('stock_locker_transaksi', array(
                    'dtime' => $now,
                    'satuan' => $agent
                ));
            }
            return $existing['id'];
        }

        $data = array(
            'cabang_id' => $cabang_id,
            'gudang_id' => 0,
            'produk_id' => 0,
            'nama' => $booking_number, // booking_number disimpan di kolom nama
            'transaksi_id' => $jenis_tr, // jenis_tr disimpan di kolom transaksi_id
            'jumlah' => '1',
            'satuan' => $agent,
            'state' => 'hold',
            'jenis' => 'transaksi',
            'jenis_locker' => 'transaksi',
            'oleh_id' => $oleh_id,
            'oleh_nama' => $oleh_nama,
            'dtime' => $now
        );
        $this->db->insert('stock_locker_transaksi', $data);
        return $this->db->insert_id();
    }

    public function retakeLockFinance($cabang_id, $jenis_tr, $booking_number, $new_oleh_id, $new_oleh_nama)
    {
        $existing = $this->checkLockFinance($cabang_id, $jenis_tr, $booking_number);
        if (sizeof($existing) > 0) {
            // Lepas lock lama
            $this->db->where('id', $existing['id']);
            $this->db->update('stock_locker_transaksi', array('jumlah' => '0'));
        }
        // Buat lock baru untuk user baru
        return $this->holdLockFinance($cabang_id, $jenis_tr, $booking_number, $new_oleh_id, $new_oleh_nama);
    }

    public function releaseLockFinance($cabang_id, $jenis_tr, $booking_number)
    {
        $this->db->where('cabang_id', $cabang_id);
        $this->db->where('transaksi_id', $jenis_tr);
        $this->db->where('nama', $booking_number);
        $this->db->where('state', 'hold');
        $this->db->update('stock_locker_transaksi', array('jumlah' => '0'));
    }
}
// END OF COMPLETE REPEATED LOGIC
