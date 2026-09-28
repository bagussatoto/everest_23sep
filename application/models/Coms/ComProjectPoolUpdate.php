<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ComProjectPoolUpdate extends MdlMother
{
    protected $filters   = array();
    protected $tableName = "project_purchase_pool";

    private $inParams  = array();
    private $outParams = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Pair: Menangani seluruh logika kalkulasi matematika & penentuan state data.
     * Hasil kalkulasi disimpan ke dalam $this->outParams.
     */
    public function pair($inParams)
    {
        $this->inParams  = $inParams;
        $this->outParams = array();

        if (sizeof($this->inParams) > 0) {
            foreach ($this->inParams as $array_params) {
                $poolId = isset($array_params['static']['project_purchase_pool_id']) ? (int)$array_params['static']['project_purchase_pool_id'] : 0;
                $qty    = isset($array_params['static']['produk_qty']) ? (float)$array_params['static']['produk_qty'] : 0;

                if ($poolId > 0 && $qty != 0) {
                    $pool = $this->db->get_where($this->tableName, array("id" => $poolId))->row();
                    if (!empty($pool)) {
                        // Kalkulasi matematika pada method pair()
                        $newQtyPo   = max(0, (float)$pool->qty_po + $qty);
                        $newQtySisa = max(0, (float)$pool->qty_kebutuhan - $newQtyPo);

                        if ($newQtyPo <= 0) {
                            $newStatus = 'OPEN';
                        } elseif ($newQtySisa <= 0) {
                            $newStatus = 'CLOSED';
                        } else {
                            $newStatus = 'PARTIAL';
                        }

                        $this->outParams[] = array(
                            "id"   => $poolId,
                            "data" => array(
                                "qty_po"             => $newQtyPo,
                                "qty_sisa"           => $newQtySisa,
                                "status"             => $newStatus,
                                "last_updated_dtime" => date("Y-m-d H:i:s")
                            )
                        );
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Exec: Murni mengeksekusi penulisan ke database berdasarkan $this->outParams.
     * Tanpa ada kalkulasi matematika di dalamnya.
     */
    public function exec()
    {
        if (sizeof($this->outParams) == 0) {
            return true;
        }

        foreach ($this->outParams as $item) {
            $this->db->where("id", $item['id']);
            $this->db->update($this->tableName, $item['data']);
        }
        return true;
    }
}
