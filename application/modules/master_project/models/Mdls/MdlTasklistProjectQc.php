<?php

class MdlTasklistProjectQc extends CI_Model
{
    /**
     * Menghitung total data tasklist QC (gabungan project_tasklist dan project_tasklist_tambahan)
     * sebelum proses filtering pencarian.
     *
     * @param int|null $employeeId
     * @return int
     */
    public function getQcTotalCount($employeeId = null)
    {
        $binds = array();
        $whereEmp1 = "";
        $whereEmp2 = "";
        if (!empty($employeeId)) {
            $whereEmp1 = " AND (employee_id = ? OR FIND_IN_SET(?, employee_id))";
            $whereEmp2 = " AND (employee_id = ? OR FIND_IN_SET(?, employee_id))";
            $binds[] = $employeeId;
            $binds[] = $employeeId;
            $binds[] = $employeeId;
            $binds[] = $employeeId;
        }

        $sql = "SELECT COUNT(*) AS total FROM (
            SELECT id FROM project_tasklist WHERE status = '1' AND trash = '0' AND progress_id = '2' AND progress_percent > 99 {$whereEmp1}
            UNION ALL
            SELECT id FROM project_tasklist_tambahan WHERE status = '1' AND trash = '0' AND progress_id = '2' AND progress_percent > 99 {$whereEmp2}
        ) AS qc_count";

        $query = $this->db->query($sql, $binds);
        $row = $query->row();
        return isset($row->total) ? intval($row->total) : 0;
    }

    /**
     * Menghitung total data tasklist QC setelah difilter oleh pencarian DataTables.
     *
     * @param string $search
     * @param array $colView
     * @param int|null $employeeId
     * @return int
     */
    public function getQcFilteredCount($search = '', $colView = array(), $employeeId = null)
    {
        if (empty($search)) {
            return $this->getQcTotalCount($employeeId);
        }

        $binds = array();
        $whereEmp1 = "";
        $whereEmp2 = "";
        if (!empty($employeeId)) {
            $whereEmp1 = " AND (employee_id = ? OR FIND_IN_SET(?, employee_id))";
            $whereEmp2 = " AND (employee_id = ? OR FIND_IN_SET(?, employee_id))";
            $binds[] = $employeeId;
            $binds[] = $employeeId;
            $binds[] = $employeeId;
            $binds[] = $employeeId;
        }

        $searchCols = $colView;
        if (!in_array('nama', $searchCols)) {
            $searchCols[] = 'nama';
        }

        $searchClauses = array();
        foreach ($searchCols as $col) {
            $cleanCol = preg_replace('/[^a-zA-Z0-9_]/', '', $col);
            if (!empty($cleanCol)) {
                $searchClauses[] = "`{$cleanCol}` LIKE ?";
                $binds[] = '%' . $search . '%';
            }
        }

        $whereSearch = "";
        if (!empty($searchClauses)) {
            $whereSearch = " WHERE (" . implode(" OR ", $searchClauses) . ")";
        }

        $sql = "SELECT COUNT(*) AS total FROM (
            SELECT *, 'main' AS task_type FROM project_tasklist WHERE status = '1' AND trash = '0' AND progress_id = '2' AND progress_percent > 99 {$whereEmp1}
            UNION ALL
            SELECT *, 'tambahan' AS task_type FROM project_tasklist_tambahan WHERE status = '1' AND trash = '0' AND progress_id = '2' AND progress_percent > 99 {$whereEmp2}
        ) AS qc_filtered {$whereSearch}";

        $query = $this->db->query($sql, $binds);
        $row = $query->row();
        return isset($row->total) ? intval($row->total) : 0;
    }

    /**
     * Mengambil data tasklist QC secara server-side pagination dengan sorting dan search.
     *
     * @param int $length
     * @param int $start
     * @param string $search
     * @param array $colView
     * @param string $orderCol
     * @param string $orderDir
     * @param int|null $employeeId
     * @return array
     */
    public function getQcData($length = 10, $start = 0, $search = '', $colView = array(), $orderCol = 'id', $orderDir = 'desc', $employeeId = null)
    {
        $binds = array();
        $whereEmp1 = "";
        $whereEmp2 = "";
        if (!empty($employeeId)) {
            $whereEmp1 = " AND (employee_id = ? OR FIND_IN_SET(?, employee_id))";
            $whereEmp2 = " AND (employee_id = ? OR FIND_IN_SET(?, employee_id))";
            $binds[] = $employeeId;
            $binds[] = $employeeId;
            $binds[] = $employeeId;
            $binds[] = $employeeId;
        }

        $whereSearch = "";
        if (!empty($search)) {
            $searchCols = $colView;
            if (!in_array('nama', $searchCols)) {
                $searchCols[] = 'nama';
            }

            $searchClauses = array();
            foreach ($searchCols as $col) {
                $cleanCol = preg_replace('/[^a-zA-Z0-9_]/', '', $col);
                if (!empty($cleanCol)) {
                    $searchClauses[] = "`{$cleanCol}` LIKE ?";
                    $binds[] = '%' . $search . '%';
                }
            }
            if (!empty($searchClauses)) {
                $whereSearch = " WHERE (" . implode(" OR ", $searchClauses) . ")";
            }
        }

        $cleanOrderCol = preg_replace('/[^a-zA-Z0-9_]/', '', $orderCol);
        if (empty($cleanOrderCol)) {
            $cleanOrderCol = 'id';
        }
        $cleanOrderDir = (strtolower($orderDir) === 'asc') ? 'ASC' : 'DESC';

        $limitSql = "";
        $length = intval($length);
        $start = intval($start);
        if ($length > 0) {
            $limitSql = " LIMIT {$start}, {$length}";
        }

        $sql = "SELECT * FROM (
            SELECT *, 'main' AS task_type FROM project_tasklist WHERE status = '1' AND trash = '0' AND progress_id = '2' AND progress_percent > 99 {$whereEmp1}
            UNION ALL
            SELECT *, 'tambahan' AS task_type FROM project_tasklist_tambahan WHERE status = '1' AND trash = '0' AND progress_id = '2' AND progress_percent > 99 {$whereEmp2}
        ) AS qc_tasks {$whereSearch} ORDER BY `{$cleanOrderCol}` {$cleanOrderDir} {$limitSql}";

        $query = $this->db->query($sql, $binds);
        return $query->result();
    }
}
