<?php
// START OF COMPLETE REPEATED LOGIC

/**
 * Model Pengaturan Sistem (settings)
 * Mendukung penyimpanan konfigurasi on/off modul, integrasi AI, dan fitur sistem lainnya.
 */
class MdlSetting extends MdlMother
{
    protected $tableName = "settings";

    public function __construct()
    {
        parent::__construct();
    }

    public function settingInv()
    {

    }

    public function callInvoicing()
    {
        $condites = array(
            "jenis" => "onoff",
            "untuk" => "history_invoice",
        );
        $existingData = $this->lookupByCondition($condites)->row();

        return $existingData;
    }

    public function updateInvoicing($by_nama = "system")
    {
        $existingData = $this->callInvoicing();
        $userName = function_exists('my_name') ? my_name() : $by_nama;

        if ($existingData) {
            $nilai = $existingData->nilai;
            $nilai_new = $nilai == 0 ? 1 : 0;

            $updCondites = array(
                "id" => $existingData->id,
            );
            $updDatas = array(
                "nilai" => $nilai_new,
                "oleh_nama" => $userName,
                "last_update" => date('Y-m-d H:i:s')
            );

            $this->updateData($updCondites, $updDatas);
        } else {
            $condites = array(
                "jenis" => "onoff",
                "untuk" => "history_invoice",
            );
            $datas = $condites;
            $datas['nilai'] = 1;
            $datas['status'] = 1;
            $datas['trash'] = 0;
            $datas['oleh_nama'] = $userName;
            $datas['last_update'] = date('Y-m-d H:i:s');
            $this->addData($datas);
            $nilai_new = 1;
        }

        return $nilai_new;
    }

    // =========================================================================
    // PENGATURAN ENTERPRISE MESSENGER & UNIVERSAL AI HUB
    // =========================================================================

    /**
     * Memeriksa apakah fitur Messenger & AI diaktifkan secara global
     * @return bool
     */
    public function get_messenger_ai_status()
    {
        $cond = array(
            "jenis" => "onoff",
            "untuk" => "messenger_ai"
        );
        $row = $this->lookupByCondition($cond)->row();
        if ($row) {
            return ($row->nilai == 1 || $row->nilai === '1');
        }
        return false; // Default: Nonaktif (OFF)
    }

    /**
     * Mengatur status on/off Messenger & AI
     * @param int|bool $status 1 untuk aktif, 0 untuk nonaktif
     * @param string $by_nama Nama user pengubah
     * @return int
     */
    public function set_messenger_ai_status($status, $by_nama = "system")
    {
        $nilai = ($status == 1 || $status === true || $status === '1') ? 1 : 0;
        $userName = function_exists('my_name') ? my_name() : $by_nama;
        $cond = array(
            "jenis" => "onoff",
            "untuk" => "messenger_ai"
        );
        $existing = $this->lookupByCondition($cond)->row();

        if ($existing) {
            $updCond = array("id" => $existing->id);
            $updData = array(
                "nilai" => $nilai,
                "oleh_nama" => $userName,
                "last_update" => date('Y-m-d H:i:s')
            );
            $this->updateData($updCond, $updData);
        } else {
            $insertData = array(
                "jenis" => "onoff",
                "untuk" => "messenger_ai",
                "nilai" => $nilai,
                "status" => 1,
                "trash" => 0,
                "oleh_nama" => $userName,
                "last_update" => date('Y-m-d H:i:s')
            );
            $this->addData($insertData);
        }

        return $nilai;
    }

    /**
     * Membalik status aktif/nonaktif Messenger & AI
     * @param string $by_nama
     * @return int Status baru (1 atau 0)
     */
    public function toggle_messenger_ai($by_nama = "system")
    {
        $current = $this->get_messenger_ai_status();
        $newStatus = $current ? 0 : 1;
        return $this->set_messenger_ai_status($newStatus, $by_nama);
    }

    /**
     * Mengambil nilai pengaturan AI tertentu dari database
     * @param string $key Contoh: 'ai_provider', 'ai_api_key', 'ai_model', 'ai_endpoint'
     * @param string $default Nilai default jika belum tersimpan
     * @return string
     */
    public function get_ai_setting($key, $default = '')
    {
        $cond = array(
            "jenis" => "ai_config",
            "untuk" => $key
        );
        $row = $this->lookupByCondition($cond)->row();
        if ($row && isset($row->nilai) && $row->nilai !== '') {
            return $row->nilai;
        }
        return $default;
    }

    /**
     * Menyimpan nilai pengaturan AI ke database
     * @param string $key
     * @param string $value
     * @param string $by_nama
     */
    public function set_ai_setting($key, $value, $by_nama = "system")
    {
        $userName = function_exists('my_name') ? my_name() : $by_nama;
        $cond = array(
            "jenis" => "ai_config",
            "untuk" => $key
        );
        $existing = $this->lookupByCondition($cond)->row();

        if ($existing) {
            $updCond = array("id" => $existing->id);
            $updData = array(
                "nilai" => $value,
                "oleh_nama" => $userName,
                "last_update" => date('Y-m-d H:i:s')
            );
            $this->updateData($updCond, $updData);
        } else {
            $insertData = array(
                "jenis" => "ai_config",
                "untuk" => $key,
                "nilai" => $value,
                "status" => 1,
                "trash" => 0,
                "oleh_nama" => $userName,
                "last_update" => date('Y-m-d H:i:s')
            );
            $this->addData($insertData);
        }
    }

    /**
     * Mengambil seluruh pengaturan AI sekaligus
     * @return array
     */
    public function get_all_ai_settings()
    {
        return array(
            'ai_provider' => $this->get_ai_setting('ai_provider', ''),
            'ai_api_key'  => $this->get_ai_setting('ai_api_key', ''),
            'ai_model'    => $this->get_ai_setting('ai_model', ''),
            'ai_endpoint' => $this->get_ai_setting('ai_endpoint', '')
        );
    }

    // =========================================================================
    // PENGATURAN MASTER PROYEK (SWITCH PELAKSANA INTERNAL / EKSTERNAL)
    // =========================================================================

    /**
     * Mengambil nilai pengaturan Master Proyek tertentu dari database
     * @param string $key Contoh: 'switch_pelaksana_enabled', 'allow_internal_to_external', 'allow_external_to_internal'
     * @param int $default Nilai default (1 = aktif/diizinkan, 0 = nonaktif/dilarang)
     * @return int
     */
    public function get_project_setting($key, $default = 1)
    {
        $cond = array(
            "jenis" => "master_project",
            "untuk" => $key
        );
        $row = $this->lookupByCondition($cond)->row();
        if ($row && isset($row->nilai) && $row->nilai !== '') {
            return (int)$row->nilai;
        }
        return (int)$default;
    }

    /**
     * Menyimpan nilai pengaturan Master Proyek ke database
     * @param string $key
     * @param int $value
     * @param string $by_nama
     * @return int
     */
    public function set_project_setting($key, $value, $by_nama = "system")
    {
        $nilai = ($value == 1 || $value === true || $value === '1') ? 1 : 0;
        $userName = function_exists('my_name') ? my_name() : $by_nama;
        $cond = array(
            "jenis" => "master_project",
            "untuk" => $key
        );
        $existing = $this->lookupByCondition($cond)->row();

        if ($existing) {
            $updCond = array("id" => $existing->id);
            $updData = array(
                "nilai" => $nilai,
                "oleh_nama" => $userName,
                "last_update" => date('Y-m-d H:i:s')
            );
            $this->updateData($updCond, $updData);
        } else {
            $insertData = array(
                "jenis" => "master_project",
                "untuk" => $key,
                "nilai" => $nilai,
                "status" => 1,
                "trash" => 0,
                "oleh_nama" => $userName,
                "last_update" => date('Y-m-d H:i:s')
            );
            $this->addData($insertData);
        }

        return $nilai;
    }

    /**
     * Mengambil seluruh pengaturan Master Proyek sekaligus
     * @return array
     */
    public function get_all_project_settings()
    {
        return array(
            'switch_pelaksana_enabled'   => $this->get_project_setting('switch_pelaksana_enabled', 1),
            'allow_internal_to_external' => $this->get_project_setting('allow_internal_to_external', 1),
            'allow_external_to_internal' => $this->get_project_setting('allow_external_to_internal', 1)
        );
    }
}

// END OF COMPLETE REPEATED LOGIC