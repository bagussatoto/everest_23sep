<?php


class MasterCache
{
    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }

    public function CallSaldoPenjualanHarian()
    {
        // $this->CI->from("_rek_master_cache");
        // $this->CI->db->get();
        $main_condites = array(
            "_rek_master_cache.rekening" => "_rek_pembantu_penjualan_cache.rekening"
        );
        $this->CI->db->join("_rek_pembantu_penjualan_cache","_rek_master_cache.rekening = _rek_pembantu_penjualan_cache.rekening");
        $this->CI->db->get("_rek_master_cache");
    }
}