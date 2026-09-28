<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComTransaksi_releaser extends CI_Model
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
        "nomer",
        "gudang_id",
    );

    private $memenuhiSyarat;

    public function __construct()
    {
        parent::__construct();
    }

    public function pair($inParams)
    {
        $this->memenuhiSyarat = false;
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
//            print("inPArams milik releaser");
//            arrprint($this->inParams);
            if (isset($this->inParams['static']['singleReference']) && $this->inParams['static']['singleReference'] > 0) {
                $this->memenuhiSyarat = true;
            }
        }

        //==kalau tidak berasal dari koneksi, cuekin saja (selalu true
//        cekbiru("memenuhi syarat?: ".$this->memenuhiSyarat);

        $code = $inParams['static']['cCode'];
        $items = $_SESSION[$code]['items'];

        $validateData = $this->cekPreValue($items, $this->inParams['static']['singleReference']);
//arrPrint($validateData);
//        matiHere($code);
        if ($this->memenuhiSyarat) {
            if ($validateData['main']['update'] == true) {
                $tr = new MdlTransaksi();
                $tr->setTableName($tr->getTableNames()['main']);
                $tr->setFilters(array());
                $tr->updateData(
                    array(
                        "id" => $this->inParams['static']['singleReference']
                    ),
                    array(
                        "next_step_code" => "",
                        "next_step_label" => "",
                        "next_group_code" => "",
                        "next_step_num" => "",
                        "step_current" => $this->inParams['static']['intoStep'],
                    )
                );
                cekbiru($this->db->last_query());
            }
            $tr = new MdlTransaksi();
            $tr->setTableName($tr->getTableNames()['detail']);
            $tr->setFilters(array());
            foreach ($validateData['details'] as $id_produk => $new_valid_qty) {
                if ($new_valid_qty == 0) {
                    $tr->updateData(
                        array(
                            "transaksi_id" => $this->inParams['static']['singleReference'],
                            "produk_id" => $id_produk,
                        ),
                        array(
                            "next_substep_code" => "",
                            "next_substep_label" => "",
                            "next_subgroup_code" => "",
                            "next_substep_num" => "",
                            "valid_qty" => $new_valid_qty,
                            "sub_step_current" => $this->inParams['static']['intoStep'],
                        )
                    );
                    cekbiru($this->db->last_query());
                }
                else {
                    $tr->updateData(
                        array(
                            "transaksi_id" => $this->inParams['static']['singleReference'],
                            "produk_id" => $id_produk,
                        ),
                        array(
                            "valid_qty" => $new_valid_qty,

                        )
                    );
                    cekbiru($this->db->last_query());
                }
            }

//            cekbiru($this->db->last_query());

            return true;
        }
        else {
//            cekbiru("TIDAK memenuhi syarat");
            return true;
        }


    }

    public function cekPreValue($temp, $trID)
    {
        cekHitam($trID);
//arrPrint($temp);
        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->setTableName($tr->getTableNames()['detail']);
        $tr->addFilter("transaksi_id='$trID'");
        $tr->addFilter("valid_qty>0");
        $tempData = $tr->lookupAll()->result();
        $origItem = array();
        foreach ($tempData as $origData) {
            $origItem[$origData->produk_id] = $origData->valid_qty;
        }

        $i = 0;
        $newVal = array();
        foreach ($origItem as $pID => $temp0) {
            $reqQty = isset($temp[$pID]['jml']) ? $temp[$pID]['jml'] : 0;
            $valid_qty = $temp0 - $reqQty;
            if ($valid_qty > 0) {
                $i++;
            }
            $newVal['details'][$pID] = $valid_qty;
        }
        if ($i > 0) {
            $newVal['main'] = array(
                "update" => false,
            );
        }
        else {
            $newVal['main'] = array(
                "update" => true,
            );
        }
        return $newVal;
    }

    public function exec()
    {
        return true;


    }
}