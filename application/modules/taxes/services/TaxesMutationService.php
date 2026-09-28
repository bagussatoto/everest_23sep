<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TaxesMutationService
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function updateIndexingColumn($id, $column, $arrBlob)
    {
        $safeColumn = preg_replace('/[^a-z0-9_]/i', '', (string)$column);
        if ($safeColumn === '' || strpos($safeColumn, 'indexing_') !== 0) {
            return false;
        }

        $this->db->where('id', (int)$id);
        $this->db->set($safeColumn, $arrBlob);
        return $this->db->update('transaksi');
    }

    public function updateIndexingMap($map, $column)
    {
        if (!is_array($map)) {
            return false;
        }

        foreach ($map as $id => $arrBlob) {
            $this->updateIndexingColumn($id, $column, $arrBlob);
        }

        return true;
    }
}

