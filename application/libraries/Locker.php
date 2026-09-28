<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */

//include_once "Bs_37.php";


class Locker
{
    protected $loginSessions;

    public function getLoginSessions()
    {
        return $this->loginSessions;
    }

    public function setLoginSessions($loginSessions)
    {
        $this->loginSessions = $loginSessions;
    }

    // protected $toko_id;
    //
    // public function getTokoId()
    // {
    //     return $this->toko_id;
    // }
    //
    // public function setTokoId($toko_id)
    // {
    //     $this->toko_id = $toko_id;
    // }

    public function __construct()
    {
        // parent::__construct();
        $this->CI =& get_instance();

    }

// START OF COMPLETE REPEATED LOGIC
    public function normalisasiStok($isAutoClean = false)
    {
        $login_session = isset($this->loginSessions) ? $this->loginSessions : matiDisini("prameter session login harap diset dulu");

        $this->CI->load->model("Mdls/MdlLockerStock");
        $this->CI->load->model("Coms/ComLockerStock");
        //region locker finish goods
        $c = new MdlLockerStock();
        $c->addFilter("stock_locker.jenis='produk'");
        $c->addFilter("state='hold'");
        $c->addFilter("jumlah>'0'");
        $c->addFilter("cabang_id=" . $login_session['cabang_id']);
        $c->addFilter("gudang_id=" . $login_session['gudang_id']);
        $c->addFilter("oleh_id=" . $login_session['id']);
        $c->addFilter("transaksi_id='0'");
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
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => 0,

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
                        "oleh_id" => 0,
                        "transaksi_id" => 0,

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

        $this->CI->load->model("Mdls/MdlLockerStockSupplies");
        $this->CI->load->model("Coms/ComLockerStockSupplies");
        //region locker supplies
        $s = new MdlLockerStockSupplies();
        //            $s->addFilter("jenis='supplies'");
        $s->addFilter("stock_locker.jenis='supplies'");
//        $s->addFilter("biaya_id is null");
        $s->addFilter("state='hold'");
        $s->addFilter("cabang_id=" . $login_session['cabang_id']);
        $s->addFilter("gudang_id=" . $login_session['gudang_id']);
        $s->addFilter("oleh_id=" . $login_session['id']);
        $s->addFilter("transaksi_id='0'");
        $s->addFilter("jumlah>'0'");
        $tmpS = $s->lookupAll()->result();

        if (sizeof($tmpS) > 0) {
            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpS as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;

                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => 0,
                        "biaya_id" => $row->biaya_id,
                        "nama" => $row->nama,

                    ),
                );
                $sentParams[] = $subParams;

                $subParams2 = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => $row->gudang_id,
                        "jenis" => $row->jenis,
                        "state" => "active",
                        "jumlah" => $jml,
                        "produk_id" => $pID,
                        "oleh_id" => 0,
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams2[] = $subParams2;

            }
            $ss = new ComLockerStockSupplies();
            $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            $ss->exec();
            //
            $ss = new ComLockerStockSupplies();
            $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            $ss->exec();
        }
        //endregion

        $this->CI->load->model("Mdls/MdlLockerStockAktiva");
        $this->CI->load->model("Coms/ComLockerStockAktiva");
        //region locker asset tetap
        $s = new MdlLockerStockAktiva();
        //        $s->addFilter("jenis='supplies'");
        $s->addFilter("stock_locker.jenis='aktiva'");
        $s->addFilter("state='hold'");
        $s->addFilter("cabang_id=" . $login_session['cabang_id']);
        $s->addFilter("gudang_id=" . $login_session['gudang_id']);
        $s->addFilter("oleh_id=" . $login_session['id']);
        $s->addFilter("transaksi_id='0'");
        $tmpS = $s->lookupAll()->result();
        if (sizeof($tmpS) > 0) {

            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpS as $row) {
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
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => 0,
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
                        "oleh_id" => 0,
                        "transaksi_id" => 0,

                    ),
                );
                $sentParams2[] = $subParams2;

            }
            $ss = new ComLockerStockAktiva();
            $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            $ss->exec();
            //
            $ss = new ComLockerStockAktiva();
            $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            $ss->exec();
        }
        //endregion

        if ($isAutoClean) {
        $this->CI->load->model("Mdls/MdlLockerTransaksi");
        $this->CI->load->model("Coms/ComLockerTransaksi");
        //region locker transaksi
        $s = new MdlLockerTransaksi();
        $s->addFilter("stock_locker_transaksi.jenis='transaksi'");
        $s->addFilter("stock_locker_transaksi.jenis_locker='transaksi'");
        $s->addFilter("state='hold'");
        $s->addFilter("cabang_id=" . $login_session['cabang_id']);
        $s->addFilter("oleh_id=" . $login_session['id']);
        $s->addFilter("transaksi_id>'0'");
        $s->addFilter("jumlah>'0'");
        $tmpS = $s->lookupAll()->result();
        if (sizeof($tmpS) > 0) {

            $sentParams = array();
            $sentParams2 = array();
            foreach ($tmpS as $row) {
                $pID = $row->produk_id;
                $jml = $row->jumlah;

                //==param untuk melepas stok HOLD
                $subParams = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => 0,
                        "jenis" => $row->jenis,
                        "jenis_locker" => $row->jenis_locker,
                        "state" => "hold",
                        "jumlah" => -($jml),
                        "produk_id" => $pID,
                        "oleh_id" => $login_session['id'],
                        "transaksi_id" => $row->transaksi_id,
                    ),
                );
                $sentParams[] = $subParams;

                //==param untuk mengembalikan stok aktiv
                $subParams2 = array(
                    "static" => array(
                        "cabang_id" => $row->cabang_id,
                        "gudang_id" => 0,
                        "jenis" => $row->jenis,
                        "jenis_locker" => $row->jenis_locker,
                        "state" => "active",
                        "jumlah" => $jml,
                        "produk_id" => $pID,
                        "oleh_id" => 0,
                        "transaksi_id" => $row->transaksi_id,
                    ),
                );
                $sentParams2[] = $subParams2;

            }
            $ss = new ComLockerTransaksi();
            $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            $ss->exec();
            //
            $ss = new ComLockerTransaksi();
            $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            $ss->exec();
        }
        //endregion
        }


        $this->CI->load->model("Mdls/MdlProdukPerSerialNumberLocker");
        $sl = New MdlProdukPerSerialNumberLocker();
        $sl->addFilter("oleh_id=" . $login_session['id']);
        $sl->addFilter("jumlah>'0'");
        $tmpSl = $sl->lookupAll()->result();
        if (sizeof($tmpSl) > 0) {
            foreach ($tmpSl as $row) {
                $id_tbl = $row->id;
                $data = array(
                    "jumlah" => 0,
                );
                $where = array(
                    "id" => $id_tbl,
                );
                $sl->setFilters(array());
                $sl->updateData($where, $data);
            }
        }


    }

    public function autoNormalisasiStok()
    {
        // matiDisini("testing");
        // $this->CI->load->helper("heWebs");
        $this->CI->load->config("heWebs");
        $coLogins = $this->CI->config->item('logins');
        // arrPrint($coLogins);
        $idleTime = $coLogins['idleTime'];
        $holdTimeLocker = $idleTime * 1.5;
        // cekBiru($holdTimeLocker);

        $this->CI->load->model("Mdls/MdlLockerStock");
        $ls = new MdlLockerStock();

        // $lockers = $ls->cekLoker(my_cabang_id(),)
        // arrPrint($_SESSION['login']);

        $paramLogins = array(
            "id",
            "cabang_id",
            "gudang_id",
            "nama",
        );

        $this->CI->load->model("Mdls/MdlEmployee");
        $em = new MdlEmployee();

        $Srcs = $em->callLastActive();
        $cou = 0;
        foreach ($Srcs as $src) {
            // arrPrintWebs($src);
            $gudang_id = getDefaultWarehouseID($src->cabang_id);
            // arrPrint($gudang_id);
            $cou++;
            foreach ($paramLogins as $kolom) {
                $nilai_kolom = $kolom == "gudang_id" ? $gudang_id["gudang_id"] : $src->$kolom;

                $paramLogin[$kolom] = $nilai_kolom;
            }
            $id = $src->id;
            $nama = $src->nama;
            $last_dtime_active = $src->last_dtime_active;
            $umur_last_active = umurHour($last_dtime_active, 'i');
            if ($umur_last_active > $holdTimeLocker) {
                $str = ("reset locker dan ditendang");
                // $this->CI->load->library("locker");
                // $lls = new Locker();
                $this->setLoginSessions($paramLogin);
                $this->normalisasiStok(true);

                /* ---------------------------------------------
                 * login dilogoutkan
                 * ---------------------------------------------*/
                // if (!isset($this->CI->session->login['id'])) {
                $em->forceLogout($id);
//                showLast_query("biru");
                // }
            }
            else {
                $str = ("masih aktif");
            }


//            cekHitam("$id $nama $last_dtime_active :: $umur_last_active > $holdTimeLocker $str");
            // break;

        }
    }
}
// END OF COMPLETE REPEATED LOGIC
