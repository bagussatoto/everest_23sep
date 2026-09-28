<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/14/2018
 * Time: 11:09 AM
 */
class ComLockerStock_releaser extends CI_Model
{

    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $writeMode;
    private $outFields = array( // dari tabel rek_cache

//                                "jenis",
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
        $this->memenuhiSyarat=false;
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
//            print("inPArams milik releaser");
//            arrprint($this->inParams);
            if(isset($this->inParams['static']['singleReference']) && $this->inParams['static']['singleReference']>0){
                $this->memenuhiSyarat=true;
            }
        }

        //==kalau tidak berasal dari koneksi, cuekin saja (selalu true
//        cekbiru("memenuhi syarat?: ".$this->memenuhiSyarat);
        if($this->memenuhiSyarat){


            //region normalisasi loker stok
            //===bersihkan & kembalikan locker2 yang dikunci orang ini
            $this->load->model("Mdls/" . "MdlLockerStock");
            $this->load->model("Coms/ComLockerStock");




            //region locker finish goods
            $c = new MdlLockerStock();

            $c->addFilter("jenis='produk'");
            $c->addFilter("state='hold'");
            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);

            $c->addFilter("transaksi_id='".$this->inParams['static']['singleReference']."'");
            $tmpC = $c->lookupAll()->result();
            if (sizeof($tmpC) > 0) {

                $sentParams = array();
                $sentParams2 = array();
                foreach ($tmpC as $row) {
                    $pID = $row->produk_id;
                    $jml = $row->jumlah;

                    //==param untuk melepas stok HOLD
                    $subParams = array(
                        "static" => array(
                            "cabang_id" => $row->cabang_id,
                            "gudang_id" => $row->gudang_id,
                            "jenis" => $row->jenis,
                            "state" => "hold",
                            "jumlah" => -($jml),
                            "produk_id" => $pID,
                            "oleh_id" => "0",
                            "transaksi_id" => $this->inParams['static']['singleReference'],
                        ),
                    );
                    $sentParams[] = $subParams;

                    //==param untuk mengembalikan stok aktiv
                    $subParams2 = array(
                        "static" => array(
                            "cabang_id" => $row->cabang_id,
                            "gudang_id" => $row->gudang_id,
                            "jenis" => $row->jenis,
                            "state" => "active",
                            "jumlah" => $jml,
                            "produk_id" => $pID,
                            "oleh_id" => "0",
                            "transaksi_id" => "0",

                        ),
                    );
                    $sentParams2[] = $subParams2;

                }
                $cs = new ComLockerStock();
                $cs->pair($sentParams) or die("Unable to pair locker for releasing");
                $cs->exec();
                //
                $cs = new ComLockerStock();
                $cs->pair($sentParams2) or die("Unable to pair locker for putting back");
                $cs->exec();

            }
            //endregion


            //endregion


            return true;
        }else{
//            cekbiru("TIDAK memenuhi syarat");
            return true;
        }



    }


    public function exec()
    {
        return true;


    }
}