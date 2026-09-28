<?php

require_once APPPATH . 'models/MdlTransaksi.php';

class MdlTransaksiUndoneMasterProject extends MdlTransaksi
{
    public function lookupUndoneEntriesForViewUndoneItems($context, $customSelect = "transaksi.*,transaksi_data.*,")
    {
        $candidateIds = $this->lookupUndoneEntryCandidateIds($context);
        if (sizeof($candidateIds) == 0) {
            return $this->db->query(
                "SELECT "
                . $customSelect
                . "transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id,transaksi.dtime as dtime "
                . "FROM " . $this->tableNames['main'] . " "
                . "JOIN " . $this->tableNames['detail'] . " ON " . $this->tableNames['detail'] . ".transaksi_id = " . $this->tableNames['main'] . ".id "
                . "WHERE 1=0"
            );
        }

        $this->db->select($customSelect . "transaksi.oleh_id as oleh_id,transaksi.oleh_nama as oleh_nama,transaksi.cabang_id as cabang_id,transaksi.dtime as dtime");
        $this->db->from($this->tableNames['detail']);
        $this->db->join($this->tableNames['main'], $this->tableNames['main'] . ".id = " . $this->tableNames['detail'] . ".transaksi_id");
        $this->db->where_in($this->tableNames['detail'] . ".transaksi_id", $candidateIds);
        $this->db->where($this->tableNames['detail'] . ".trash", "0");
        $this->db->where($this->tableNames['detail'] . ".valid_qty >", 0);
        $this->db->where($this->tableNames['detail'] . ".sub_step_number >", 0);
        $this->db->where($this->tableNames['detail'] . ".next_substep_code <>", "");

        $groupCodes = isset($context['next_subgroup_codes']) && is_array($context['next_subgroup_codes']) ? array_values(array_unique($context['next_subgroup_codes'])) : array();
        if (sizeof($groupCodes) > 0) {
            $this->db->where_in($this->tableNames['detail'] . ".next_subgroup_code", $groupCodes);
        }

        if (isset($this->keyWord)) {
            $key = isset($this->keyWord) ? $this->keyWord : "";
            $this->createSmartSearch($key, array("transaksi.customers_nama", "transaksi.oleh_nama", "transaksi.suppliers_nama"));
        }

        $this->db->group_by(array(
            $this->tableNames['detail'] . ".transaksi_id",
            $this->tableNames['detail'] . ".next_substep_code",
        ));
        $this->db->order_by($this->tableNames['main'] . ".id", "desc");

        return $this->db->get();
    }

    protected function lookupUndoneEntryCandidateIds($context)
    {
        $cabangId = isset($context['cabang_id']) ? trim((string)$context['cabang_id']) : "";
        $gudangId = isset($context['gudang_id']) ? trim((string)$context['gudang_id']) : "";
        $divId = isset($context['div_id']) ? trim((string)$context['div_id']) : "";
        $jenisMaster = isset($context['jenis_master']) ? trim((string)$context['jenis_master']) : "";
        $olehId = isset($context['oleh_id']) ? trim((string)$context['oleh_id']) : "";
        $nextStepCodes = isset($context['next_step_codes']) && is_array($context['next_step_codes']) ? array_values(array_unique($context['next_step_codes'])) : array();

        $this->db->select($this->tableNames['main'] . ".id");
        $this->db->from($this->tableNames['main']);
        $this->db->where($this->tableNames['main'] . ".status", "1");
        $this->db->where($this->tableNames['main'] . ".trash", "0");
        $this->db->where($this->tableNames['main'] . ".link_id", "0");

        if ($jenisMaster !== "") {
            $this->db->where($this->tableNames['main'] . ".jenis_master", $jenisMaster);
        }
        if ($divId !== "") {
            $this->db->where($this->tableNames['main'] . ".div_id", $divId);
        }
        if ($cabangId !== "") {
            $this->db->group_start();
            $this->db->where($this->tableNames['main'] . ".cabang_id", $cabangId);
            $this->db->or_where($this->tableNames['main'] . ".cabang2_id", $cabangId);
            $this->db->group_end();
        }
        if ($gudangId !== "") {
            $this->db->group_start();
            $this->db->where($this->tableNames['main'] . ".gudang_id", $gudangId);
            $this->db->or_where($this->tableNames['main'] . ".gudang2_id", $gudangId);
            $this->db->group_end();
        }

        if (sizeof($nextStepCodes) > 0) {
            $this->db->where_in($this->tableNames['main'] . ".next_step_code", $nextStepCodes);
        }
        elseif ($olehId !== "") {
            $this->db->where($this->tableNames['main'] . ".oleh_id", $olehId);
        }

        $this->db->order_by($this->tableNames['main'] . ".id", "desc");
        $rows = $this->db->get()->result();
        $ids = array();
        if (sizeof($rows) > 0) {
            foreach ($rows as $row) {
                if (isset($row->id) && $row->id !== "") {
                    $ids[] = $row->id;
                }
            }
        }

        return $ids;
    }
}
