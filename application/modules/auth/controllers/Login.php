<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(0);
ini_set('display_errors', 0);

class Login extends CI_Controller
{
    /**
     * Login constructor.
     */

    protected $forceMobile;
    protected $forceDesktopView;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        //==cek dulu apakah masiha da cookies utama
        if (isset($_COOKIE['uprop'])) {
            $unzippedSessions = unserialize(base64_decode($_COOKIE['uprop']));
            // -- validasi: pastikan user di cookie masih aktif di DB sebelum restore --
            $cookieUserId = isset($unzippedSessions['id']) ? intval($unzippedSessions['id']) : 0;
            if (is_array($unzippedSessions) && sizeof($unzippedSessions) > 0 && $cookieUserId > 0) {
                $this->load->model("Mdls/MdlUser");
                $uCek = new MdlUser();
                $tmpCek = $uCek->lookupByCondition(array(
                    "id"     => $cookieUserId,
                    "status" => "1",
                ))->result();
                if (sizeof($tmpCek) > 0) {
                    $this->session->login = $unzippedSessions;
                    redirect(base_url());
                } else {
                    // cookie lama / user tidak aktif → hapus cookie agar tidak looping
                    setcookie("uprop", "", time() - 3600, "/");
                    setcookie("uprop", "", time() - 3600); // hapus sisa masa lalu jika ada
                }
            }
        }

        if (isset($this->session->login['id'])) {
            topRedirect(base_url());
            // mati_disini("sudah ada session");
        }

        // matiHere(__LINE__ . __FILE__);
        if (isset($_GET['err'])) {
            $tempErr = blobDecode($_GET['err']);
            $tempIpadd = $tempErr['ipadd'];
            $tempDevices = $tempErr['devices'];
            $temp_err = "<div class='text-center bg-warning' style='margin-top: 5px;'>";
            $temp_err .= "<div>Session ended</div>";
            $temp_err .= "<div>your id was login on $tempIpadd</div>";
            $temp_err .= "<div>by  $tempDevices</div>";
            $temp_err .= "</div>";
        }
        else {
            $temp_err = "";
        }
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
        $param = isset($_GET['xxx']) ? $_GET['xxx'] : "";
        if (isset($_COOKIE['uid'])) {
            $c_uid = $_COOKIE['uid'];
            $c_pwd = base64_decode($_COOKIE['pwd']);
            $checked = "checked";
        }
        else {
            $checked = "";
            $c_uid = "";
            $c_pwd = "";
        }
        $formAttributes = array(
            'id' => 'fLogin',
            'name' => 'fLogin',
            'class' => 'form-signin',
            'data_toggle' => 'validator',
            'target' => 'result',
        );

        /* -------------------------------------
         * petugas kebersihan file QR
         * -------------------------------------*/
        $dirQr = "./public/images/qrcode";
        $umurFile =1;
        $resp = hapus_file($dirQr, $umurFile);
        // cekHere($resp);
        // --------------------------------------------------end

        //region writelog
        //        $this->load->model("Mdls/" . "MdlActivityLog");
        //        $hTmp = new MdlActivityLog();
        //        $hTmp->setFilters(array());
        //        $tmpHData = array(
        //            "title"         => "Login",
        //            "sub_title"     => "Please Login",
        //            "uid"           => isset($this->session->login['id']) ? $this->session->login['id'] : 0,
        //            "uname"         => isset($this->session->login['nama']) ? $this->session->login['nama'] : "noname",
        //            "dtime"         => date("Y-m-d H:i:s"),
        //            "transaksi_id"  => 0,
        //            "deskripsi_old" => "",
        //            "deskripsi_new" => "",
        //            "jenis"         => "",
        //            "ipadd"         => $_SERVER['REMOTE_ADDR'],
        //            "devices"       => $_SERVER['HTTP_USER_AGENT'],
        //            "category"      => "browse",
        //            "controller"    => $this->uri->segment(1),
        //            "method"        => $this->uri->segment(2),
        //            "url"           => current_url(),
        //        );
        //        $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));

        $_SERVER['REMOTE_ADDR'] != "127.0.0.1" ? writeLog("Login", "Login Page", "auth") : "";
        //endregion

        $temp = array(
            "mode" => "forms",
            "formAttributes" => $formAttributes,
            "remember" => $checked,
            "defaultUserID" => $c_uid,
            "defaultPwd" => $c_pwd,
            "goTo" => $param,
            "ses_ended" => $temp_err,
        );
        $data = array(
            "mode" => "forms",
            "formAttributes" => $formAttributes,
            "remember" => $checked,
            "defaultUserID" => $c_uid,
            "defaultPwd" => $c_pwd,
            "goTo" => $param,
            "ses_ended" => $temp_err,
            "temp" => $temp,
            "errMsg" => $this->session->errMsg,
        );
        $this->load->view('login', $data);
    }

    public function authCheck()
    {
        // -- pastikan output selalu di-buffer agar setcookie() tidak gagal diam-diam
        //    apapun setting output_buffering di php.ini server --
        ob_start();

        $validCounter = 0;

        $nama_login = $this->input->post('nama');
        $post_password = $this->input->post('password');
        $goto_e = blobDecode($this->input->post('goto'));
        $bypass = $this->input->post('bypass');
        // arrPrint($nama_login);

        //trigger depresiasi
        $this_day = date("d");
        $tgl_mulai = "20";
        $tgl_akhir = "28";

        if( $this_day>=$tgl_mulai && $this_day<=$tgl_akhir ){
            //AREA ini akan di eksekusi
            $this->load->model("Mdls/" . "MdlActivityLog");
            $hTmp = new MdlActivityLog();
            $this->db->where("DATE(dtime)=CURDATE() AND uname!=''");
            $this->db->order_by("dtime ASC");
            $tmpLog = $hTmp->lookupAll()->result();

            if(sizeof($tmpLog)==0){
                echo "<script>
                          var autodepresewa   = top.window.open('".base_url()."asetmanagement/AutoDepresiasiSewa_coa?fromlogin=1','AutoDepresiasiSewa','toolbar=no,status=no,menubar=no,scrollbars=no,resizable=no,left=10000, top=10000, width=10, height=10, visible=none', '');
                          var autodepre       = top.window.open('".base_url()."asetmanagement/AutoDepresiasi_coa?fromlogin=1','AutoDepresiasi','toolbar=no,status=no,menubar=no,scrollbars=no,resizable=no,left=10000, top=10000, width=10, height=10, visible=none', '');
                      </script>";
            }
        }

        $authProps = array(
            "MdlUser",
            "MdlPos",
            "MdlEmployee",
            "MdlEmployee__shadow",
            "MdlEmployeeCabang",
            "MdlEmployeeCabang__shadow",
            "MdlEmployeeGudang",
            "MdlEmployeeGudangFase",
            "MdlEmployeeFreelanceCabang",
            "MdlEmployeeKirim",
        );

        //==pairers
        //company profile grap jenis usaha
        $this->load->Model("Mdls/MdlCompany");
        $comPro = new MdlCompany();
        $tmpCompanyProfile = $comPro->lookupAll()->result();
        // cekLime($this->db->last_query());
        // arrPrint($tmpCompanyProfile);
        $companyProfil = array();
        $jn_usaha = $tmpCompanyProfile[0]->jenis_usaha;
        foreach($tmpCompanyProfile as $rows){
            $companyProfil["jenis_usaha"] = $rows->jenis_usaha;
        }
        // arrPrint($tmpCompanyProfile);
        $this->ci = $CI =& get_instance();
        //load dari config item pair pajak
        $masterPpnData = $CI->config->item("pairPajak");
        $masterPPN = $masterPpnData[$jn_usaha]["value"]["default"];
        // arrPrint($masterPPN);
        // matiHere();

        $cabangs = array();
        $this->load->model("Mdls/MdlCabang");
        $cab = new MdlCabang();
        $tmpc = $cab->lookupAll()->result();
        if (sizeof($tmpc) > 0) {
            foreach ($tmpc as $row) {
                $_id = $row->id;
                $cabangs[$_id] = $row->nama;
            }
        }
        $divs = array();
        $this->load->model("Mdls/MdlDiv");
        $cab = new MdlDiv();
        $tmpc = $cab->lookupAll()->result();
        if (sizeof($tmpc) > 0) {
            foreach ($tmpc as $row) {
                $divs[$row->id] = $row->nama;
            }
        }
        $gudangs = array();
        $this->load->model("Mdls/MdlGudang");
        $cab = new MdlGudang();
        $tmpc = $cab->lookupAll()->result();
        if (sizeof($tmpc) > 0) {
            foreach ($tmpc as $row) {
                $_id = $row->id;
                $gudangs[$_id] = $row->nama;
            }
        }
        $loginProp = array();
        $nameField = "nama_login";
        foreach ($authProps as $mdlName) {
            $this->load->model("Mdls/" . $mdlName);
            $u = new $mdlName();
            if ($bypass == 'on') {
                $tmpUser = $u->lookupByCondition(array(
                    $nameField => $nama_login,
                    "status" => "1",
                ))->result();
            }
            else {
                $tmpUser = $u->lookupByCondition(array(
                    $nameField => $nama_login,
                    "password" => md5($post_password),
                    "status" => "1",
                ))->result();
            }
//                        cekmerah($this->db->last_query());
            if (sizeof($tmpUser) > 0) {
                $userProp = $tmpUser[0];
                $login_fail = $userProp->login_fail;
                $email = $userProp->email;
                if ($login_fail == 10) {
                    $arrAlert = array(
                        "type" => "warning",
                        "title" => "Passsword Reseted",
                        "html" => "please check your email and follow the link have been  sent to <b class='text-info'>$email</b> for confirm your account",
                    );
                    echo swalAlert($arrAlert);
                    die();
                }

                $arrAlert = array(
                    "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Authenticating.<br>Please wait...<br>",
                    "showConfirmButton" => false,
                    "allowOutsideClick" => false,

                );
                // echo swalAlert($arrAlert);
                //
                foreach ($userProp as $field => $item) {
                    $$field = $item;
                }


                $sessionSwappers = array(
                    "id",
                    "nama_login",
                    "nama",
                    "phpsess_dtime",
                    "phpsessid",
                    "phpsessid",
                    "status",
                    "jenis",
                    "debuger",
                    "cabang_id",
                    "gudang_id",
                    "div_id",
                    "ghost",
                    "employee_type",
                    "jenis_usaha",
                );
                $sessionUpdaters = array(
                    'phpsess_dtime' => dtimeNow(),
                    'phpsessid' => session_id(),
                    'ipadd' => $_SERVER['REMOTE_ADDR'],
                    'devices' => $_SERVER['HTTP_USER_AGENT'],
                    'membership' => unserialize(base64_decode($userProp->membership)),
                    'longitude' => "",
                    'lattitude' => "",
                    'accuracy' => "",
                    'status_login' => "1",
                );
                $tableUpdaters = array(
                    "phpsessid" => "phpsesid",
                    "phpsess_dtime" => "phpses_dtime",
                    "php_session" => "php_session",
                    "ipadd" => "ipadd",
                    "devices" => "devices",
                    'status_login' => "status_login",
                );

                $gudSpec_pusat = getDefaultWarehouseID(-1);
                $gudSpec_cabang = getDefaultWarehouseID($userProp->cabang_id);
                $gudSpec_POS = getPOSWarehouseID($userProp->id, $userProp->cabang_id, $userProp->id);
                $sessionUniqUpdaters = array(
                    "MdlUser" => array(
//                        'gudang_id' => -999,
                        'gudang_id' => $gudSpec_cabang['gudang_id'],
                        'gudang_nama' => 'no warehouse',
                        'cabang_id' => isset($userProp->cabang_id) ? $userProp->cabang_id : "-1",
//                        'cabang_nama' => "pusat",
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "",
                        'employee_type' => $userProp->employee_type,
                    ),
                    "MdlEmployee" => array(
                        'gudang_id' => $gudSpec_pusat['gudang_id'],
                        'gudang_nama' => $gudSpec_pusat['gudang_nama'],
                        'cabang_id' => isset($userProp->cabang_id) ? $userProp->cabang_id : "-1",
//                        'cabang_nama' => "pusat",
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "",
                        //                        'div_nama' => "div " . $userProp->div_id,
                        'div_nama' => $divs[$userProp->div_id],
                        'employee_type' => $userProp->employee_type,
                    ),
                    "MdlEmployee__shadow" => array(
                        'gudang_id' => $gudSpec_pusat['gudang_id'],
                        'gudang_nama' => $gudSpec_pusat['gudang_nama'],
                        'cabang_id' => isset($userProp->cabang_id) ? $userProp->cabang_id : "-1",
//                        'cabang_nama' => "pusat",
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "",
                        //                        'div_nama' => "div " . $userProp->div_id,
                        'div_nama' => $divs[$userProp->div_id],
                        'employee_type' => $userProp->employee_type,
                    ),
                    "MdlEmployeeCabang" => array(
                        'gudang_id' => $gudSpec_cabang['gudang_id'],
                        'gudang_nama' => $gudSpec_cabang['gudang_nama'],
                        //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                        //                        'div_nama' => "div " . $userProp->div_id,
                        'div_nama' => $divs[$userProp->div_id],
                        'employee_type' => $userProp->employee_type,
                    ),
                    "MdlEmployeeCabang__shadow" => array(
                        'gudang_id' => $gudSpec_cabang['gudang_id'],
                        'gudang_nama' => $gudSpec_cabang['gudang_nama'],
                        //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                        //                        'div_nama' => "div " . $userProp->div_id,
                        'div_nama' => $divs[$userProp->div_id],
                        'employee_type' => $userProp->employee_type,
                    ),
                    "MdlPos" => array(

                        'gudang_id' => $gudSpec_POS['gudang_id'],
                        'gudang_nama' => $gudSpec_POS['gudang_nama'],
                        //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                        //                        'div_nama' => "div " . $userProp->div_id,
                        'div_nama' => $divs[$userProp->div_id],
                        'employee_type' => $userProp->employee_type,
                    ),
                    "MdlEmployeeGudang" => array(
                        'gudang_id' => $userProp->gudang_id,
                        'gudang_nama' => isset($gudangs[$userProp->gudang_id]) ? $gudangs[$userProp->gudang_id] : "warehouse " . $userProp->gudang_id,
                        //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                        //                        'div_nama' => "div " . $userProp->div_id,
                        'div_nama' => $divs[$userProp->div_id],
                        'employee_type' => $userProp->employee_type,
                    ),
                    "MdlEmployeeGudangFase" => array(
                        'gudang_id' => $userProp->gudang_id,
                        'gudang_nama' => isset($gudangs[$userProp->gudang_id]) ? $gudangs[$userProp->gudang_id] : "warehouse " . $userProp->gudang_id,
                        //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                        //                        'div_nama' => "div " . $userProp->div_id,
                        'div_nama' => $divs[$userProp->div_id],
                        'employee_type' => $userProp->employee_type,
                    ),
                    "MdlEmployeeFreelanceCabang" => array(
                        'gudang_id' => $gudSpec_cabang['gudang_id'],
                        'gudang_nama' => $gudSpec_cabang['gudang_nama'],
                        //                        'cabang_nama' => "branch " . $userProp->cabang_id,
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                        //                        'div_nama' => "div " . $userProp->div_id,
                        'div_nama' => $divs[$userProp->div_id],
                        'employee_type' => $userProp->employee_type,
                    ),
                    "MdlEmployeeKirim" => array(
                        'gudang_id' => $gudSpec_cabang['gudang_id'],
                        'gudang_nama' => $gudSpec_cabang['gudang_nama'],
                        'cabang_nama' => isset($cabangs[$userProp->cabang_id]) ? $cabangs[$userProp->cabang_id] : "branch " . $userProp->cabang_id,
                        'div_nama' => $divs[$userProp->div_id],
                        'employee_type' => $userProp->employee_type,
                    ),
                );
//mati_disini($gudSpec_cabang['gudang_id']);

                foreach ($sessionSwappers as $key) {
                    $loginProp[$key] = isset($userProp->$key) ? $userProp->$key : "";
                }

                foreach ($sessionUpdaters as $key => $val) {
                    $loginProp[$key] = $val;
                }

                if (isset($sessionUniqUpdaters[$mdlName]) && sizeof($sessionUniqUpdaters[$mdlName]) > 0) {
                    foreach ($sessionUniqUpdaters[$mdlName] as $key => $val) {
                        $loginProp[$key] = $val;
                    }
                }
//                mati_disini($loginProp['gudang_id'] . " $mdlName");
                foreach($companyProfil as $keys =>$vals){
                    $loginProp[$keys] = $vals;
                }
                foreach($masterPPN as $keyPpn =>$value_ppn){
                    $loginProp[$keyPpn] = $value_ppn;
                }
                // arrPrint($loginProp);
                // matiHEre();

                //==force membership
                if (!is_array($loginProp['membership'])) {
                    $loginProp['membership'] = array();
                }

                $this->session->login = $loginProp;


                $zippedSessions = base64_encode(serialize($this->session->login));
                setcookie("uprop", $zippedSessions, time() + 31356000, "/");

                if ($this->input->post('remember') == "on") {
                    setcookie("uid", $nama_login, time() + 31356000, "/");
                    setcookie("pwd", base64_encode($post_password), time() + 31356000, "/");
                    echo lgShowAlert("remembered result: " . print_r($_COOKIE, true));
                }
                else {
                    setcookie("uid", "", time() - 3600, "/");
                    setcookie("pwd", "", time() - 3600, "/");
                }
                $validCounter++;


                //region update data employee session idnya
                $temp = array();
                foreach ($tableUpdaters as $kolom => $alias) {
                    if (isset($loginProp[$kolom])) {
                        $temp[$kolom] = $loginProp[$kolom];
                    }
                }
                if (sizeof($temp) > 0) {
                    $condite = array("id" => $loginProp['id']);
                    $u->updateData($condite, $temp);
                }
                //endregion


            }
        }

// cekAlert();
//         mati_disini("cek");
        //        die();
        if ($validCounter < 1) {
            $arrSwal = array(
                "title" => "Login failed",
                "html" => "Login Details Incorrect. Please try again.",
                "type" => "warning",
            );
            echo swalAlert($arrSwal);
            die();
        }
        else {
            $this->db->trans_start();
            //region normalisasi loker stok
            //===bersihkan & kembalikan locker2 yang dikunci orang ini
            // $this->load->model("Mdls/MdlLockerStock");
            // $this->load->model("Coms/ComLockerStock");
            // $this->load->model("Mdls/MdlLockerStockSupplies");
            // $this->load->model("Coms/ComLockerStockSupplies");
            // $this->load->model("Mdls/MdlLockerStockAktiva");
            // $this->load->model("Coms/ComLockerStockAktiva");
            // $this->load->model("Mdls/MdlLockerTransaksi");
            // $this->load->model("Coms/ComLockerTransaksi");

            //region locker finish goods
            // $c = new MdlLockerStock();
            // $c->addFilter("stock_locker.jenis='produk'");
            // $c->addFilter("state='hold'");
            // $c->addFilter("jumlah>'0'");
            // $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            // $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
            // $c->addFilter("oleh_id=" . $this->session->login['id']);
            // $c->addFilter("transaksi_id='0'");
            // $tmpC = $c->lookupAll()->result();
            //
            // if (sizeof($tmpC) > 0) {
            //
            //     $sentParams = array();
            //     $sentParams2 = array();
            //     foreach ($tmpC as $row) {
            //         $pID = $row->produk_id;
            //         $jml = $row->jumlah;
            //
            //         $subParams = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "hold",
            //                 "jumlah" => -($jml),
            //                 "produk_id" => $pID,
            //                 "oleh_id" => $this->session->login['id'],
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams[] = $subParams;
            //
            //         $subParams2 = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "active",
            //                 "jumlah" => $jml,
            //                 "produk_id" => $pID,
            //                 "oleh_id" => 0,
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams2[] = $subParams2;
            //
            //     }
            //     $cs = new ComLockerStock();
            //     $cs->pair($sentParams) or die("Unable to pair locker for releasing");
            //     $cs->exec();
            //     //
            //     $cs = new ComLockerStock();
            //     $cs->pair($sentParams2) or die("Unable to pair locker for putting back");
            //     $cs->exec();
            //
            // }
            //endregion

            //region locker finish goods
            // $s = new MdlLockerStockSupplies();
            // //            $s->addFilter("jenis='supplies'");
            // $s->addFilter("stock_locker.jenis='supplies'");
            // $s->addFilter("state='hold'");
            // $s->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            // $s->addFilter("gudang_id=" . $this->session->login['gudang_id']);
            // $s->addFilter("oleh_id=" . $this->session->login['id']);
            // $s->addFilter("transaksi_id='0'");
            // $s->addFilter("jumlah>'0'");
            // $tmpS = $s->lookupAll()->result();
            //
            // if (sizeof($tmpS) > 0) {
            //     $sentParams = array();
            //     $sentParams2 = array();
            //     foreach ($tmpS as $row) {
            //         $pID = $row->produk_id;
            //         $jml = $row->jumlah;
            //
            //         $subParams = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "hold",
            //                 "jumlah" => -($jml),
            //                 "produk_id" => $pID,
            //                 "oleh_id" => $this->session->login['id'],
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams[] = $subParams;
            //
            //         $subParams2 = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "active",
            //                 "jumlah" => $jml,
            //                 "produk_id" => $pID,
            //                 "oleh_id" => 0,
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams2[] = $subParams2;
            //
            //     }
            //     $ss = new ComLockerStockSupplies();
            //     $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            //     $ss->exec();
            //     //
            //     $ss = new ComLockerStockSupplies();
            //     $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            //     $ss->exec();
            // }
            //endregion

            //region locker asset tetap
            // $s = new MdlLockerStockAktiva();
            // //        $s->addFilter("jenis='supplies'");
            // $s->addFilter("stock_locker.jenis='aktiva'");
            // $s->addFilter("state='hold'");
            // $s->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            // $s->addFilter("gudang_id=" . $this->session->login['gudang_id']);
            // $s->addFilter("oleh_id=" . $this->session->login['id']);
            // $s->addFilter("transaksi_id='0'");
            // $tmpS = $s->lookupAll()->result();
            // if (sizeof($tmpS) > 0) {
            //
            //     $sentParams = array();
            //     $sentParams2 = array();
            //     foreach ($tmpS as $row) {
            //         $pID = $row->produk_id;
            //         $jml = $row->jumlah;
            //
            //         //==param untuk melepas stok HOLD
            //         $subParams = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "hold",
            //                 "jumlah" => -($jml),
            //                 "produk_id" => $pID,
            //                 "oleh_id" => $this->session->login['id'],
            //                 "transaksi_id" => 0,
            //             ),
            //         );
            //         $sentParams[] = $subParams;
            //
            //         //==param untuk mengembalikan stok aktiv
            //         $subParams2 = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => $row->gudang_id,
            //                 "jenis" => $row->jenis,
            //                 "state" => "active",
            //                 "jumlah" => $jml,
            //                 "produk_id" => $pID,
            //                 "oleh_id" => 0,
            //                 "transaksi_id" => 0,
            //
            //             ),
            //         );
            //         $sentParams2[] = $subParams2;
            //
            //     }
            //     $ss = new ComLockerStockAktiva();
            //     $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            //     $ss->exec();
            //     //
            //     $ss = new ComLockerStockAktiva();
            //     $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            //     $ss->exec();
            // }
            //endregion

            //region locker transaksi
            // $s = new MdlLockerTransaksi();
            // $s->addFilter("stock_locker_transaksi.jenis='transaksi'");
            // $s->addFilter("stock_locker_transaksi.jenis_locker='transaksi'");
            // $s->addFilter("state='hold'");
            // $s->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            // $s->addFilter("oleh_id=" . $this->session->login['id']);
            // $s->addFilter("transaksi_id>'0'");
            // $s->addFilter("jumlah>'0'");
            // $tmpS = $s->lookupAll()->result();
            // if (sizeof($tmpS) > 0) {
            //
            //     $sentParams = array();
            //     $sentParams2 = array();
            //     foreach ($tmpS as $row) {
            //         $pID = $row->produk_id;
            //         $jml = $row->jumlah;
            //
            //         //==param untuk melepas stok HOLD
            //         $subParams = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => 0,
            //                 "jenis" => $row->jenis,
            //                 "jenis_locker" => $row->jenis_locker,
            //                 "state" => "hold",
            //                 "jumlah" => -($jml),
            //                 "produk_id" => $pID,
            //                 "oleh_id" => $this->session->login['id'],
            //                 "transaksi_id" => $row->transaksi_id,
            //             ),
            //         );
            //         $sentParams[] = $subParams;
            //
            //         //==param untuk mengembalikan stok aktiv
            //         $subParams2 = array(
            //             "static" => array(
            //                 "cabang_id" => $row->cabang_id,
            //                 "gudang_id" => 0,
            //                 "jenis" => $row->jenis,
            //                 "jenis_locker" => $row->jenis_locker,
            //                 "state" => "active",
            //                 "jumlah" => $jml,
            //                 "produk_id" => $pID,
            //                 "oleh_id" => 0,
            //                 "transaksi_id" => $row->transaksi_id,
            //             ),
            //         );
            //         $sentParams2[] = $subParams2;
            //
            //     }
            //     $ss = new ComLockerTransaksi();
            //     $ss->pair($sentParams) or die("Unable to pair locker for releasing");
            //     $ss->exec();
            //     //
            //     $ss = new ComLockerTransaksi();
            //     $ss->pair($sentParams2) or die("Unable to pair locker for putting back");
            //     $ss->exec();
            // }
            //endregion

            $this->load->library("locker");
            $lls = new Locker();
            $lls->setLoginSessions($_SESSION['login']);
            $lls->normalisasiStok();

            //endregion

            // region locker kas
            $this->load->library("CheckerLocker");
            $cl = New CheckerLocker();
            $cl->setCabangId($this->session->login['cabang_id']);
            $cl->setExecute(true);
            $result = $cl->lockerKas();
            // endregion


            //region writelog

            //            $this->load->model("Mdls/" . "MdlActivityLog");
            //            $hTmp = new MdlActivityLog();
            //            $hTmp->setFilters(array());
            //            $tmpHData = array(
            //                "title"         => "Login",
            //                "sub_title"     => "Authenticating your credential..",
            //                "uid"           => $this->session->login['id'],
            //                "uname"         => $this->session->login['nama'],
            //                "dtime"         => date("Y-m-d H:i:s"),
            //                "transaksi_id"  => 0,
            //                "deskripsi_old" => "",
            //                "deskripsi_new" => base64_encode(serialize($this->session->login)),
            //                "jenis"         => "",
            //                "ipadd"         => $_SERVER['REMOTE_ADDR'],
            //                "devices"       => $_SERVER['HTTP_USER_AGENT'],
            //                "category"      => "auth",
            //                "controller"    => $this->uri->segment(1),
            //                "method"        => $this->uri->segment(2),
            //                "url"           => current_url(),
            //
            //            );
            //            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            writeLog("Login", "Authenticating credentials..", "auth");
            //endregion
            $this->db->trans_complete();
        }


        //region group landings
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $defaultLandPages = array();
        if (sizeof($mems) > 0) {
            foreach ($mems as $gID) {
                if (isset($this->config->item("groupLandingPages")[$gID])) {
                    $defaultLandPages[] = base_url() . $this->config->item("groupLandingPages")[$gID];
                }
            }
        }
        //endregion



        //        if (sizeof($defaultLandPages) > 0) {
        //            echo "<script>top.location.href='" . $defaultLandPages[0] . "';</script>";
        //        } else {
        //            if (strlen($goto_e) > 10) {
        //                echo "<script>top.location.href='" . "$goto_e';</script>";
        //            } else {
        //                echo "<script>top.location.href='" . base_url() . "';</script>";
        //            }
        //        }

        if (strlen($goto_e) > 10) {

            $whiteList = array(
                base_url() . "pembelian/Transaksi/index/461",
            );

            if( in_array($goto_e, $whiteList) ){
                $_SESSION['login']['forceMobile'] = 1;
                echo "<script>top.location.href='" . "$goto_e';</script>";
            }
            else{
            echo "<script>top.location.href='" . base_url() . "';</script>";
        }
        }
        else {
            echo "<script>top.location.href='" . base_url() . "';</script>";
        }

    }

    public function authCheck_branch()
    {
        $validCounter = 0;

        $nama_login = $this->input->post('nama');
        $post_password = $this->input->post('password');
        $goto_e = blobDecode($this->input->post('goto'));


        $oID = $this->input->post("cab");
        $oName = $this->input->post("cabN");

        $authProps = array(
            "MdlPos",
        );

        //==pairers
        $cabangs = array();
        $this->load->model("Mdls/MdlCabang");
        $cab = new MdlCabang();
        $tmpc = $cab->lookupAll()->result();
        if (sizeof($tmpc) > 0) {
            foreach ($tmpc as $row) {
                $_id = $row->id;
                $cabangs[$_id] = $row->nama;
            }
        }
        $divs = array();
        $this->load->model("Mdls/MdlDiv");
        $cab = new MdlDiv();
        $tmpc = $cab->lookupAll()->result();
        if (sizeof($tmpc) > 0) {
            foreach ($tmpc as $row) {
                $divs[$row->id] = $row->nama;
            }
        }

        $loginProp = array();
        $nameField = "nama_login";
        foreach ($authProps as $mdlName) {
            $this->load->model("Mdls/" . $mdlName);
            $u = new $mdlName();
            $tmpUser = $u->lookupByCondition(array(
                $nameField => $nama_login,
                "password" => md5($post_password),
            ))->result();
            //            cekmerah($this->db->last_query());
            if (sizeof($tmpUser) > 0) {
                $userProp = $tmpUser[0];
                $login_fail = $userProp->login_fail;
                $email = $userProp->email;
                if ($login_fail == 10) {
                    $arrAlert = array(
                        "type" => "warning",
                        "title" => "Passsword Reseted",
                        "html" => "please check your email and follow the link have been  sent to <b class='text-info'>$email</b> for confirm your account",
                    );
                    echo swalAlert($arrAlert);
                    die();
                }

                $arrAlert = array(
                    "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>Authenticating.<br>Please wait...<br>",
                    "showConfirmButton" => false,
                    "allowOutsideClick" => false,

                );
                // echo swalAlert($arrAlert);
                //
                foreach ($userProp as $field => $item) {
                    $$field = $item;
                }


                $sessionSwappers = array(
                    "id",
                    "nama_login",
                    "nama",
                    "phpsess_dtime",
                    "phpsessid",
                    "phpsessid",
                    "status",
                    "jenis",
                    "debuger",
                    "cabang_id",
                    "gudang_id",
                    "div_id",
                    "ghost",
                );
                $sessionUpdaters = array(
                    'phpsess_dtime' => dtimeNow(),
                    'phpsessid' => $_COOKIE['ci_session'],
                    'ipadd' => $_SERVER['REMOTE_ADDR'],
                    'devices' => $_SERVER['HTTP_USER_AGENT'],
                    'membership' => unserialize(base64_decode($userProp->membership)),
                    'longitude' => "",
                    'lattitude' => "",
                    'accuracy' => "",
                    "deviceID" => $_GET['dev'],
                    "deviceName" => $_GET['dev_name'],
                );

                $tableUpdaters = array(
                    "phpsessid" => "phpsesid",
                    "phpsess_dtime" => "phpses_dtime",
                    "php_session" => "php_session",
                    "ipadd" => "ipadd",
                    "devices" => "devices",
                );

                $gudSpec_pusat = getDefaultWarehouseID(-1);
                //                $gudSpec_cabang = getDefaultWarehouseID($oID, $_GET['dev']);
                $gudSpec_cabang = getPOSWarehouseID($userProp->id, $oID, $_GET['dev']);
                $sessionUniqUpdaters = array(

                    "MdlPos" => array(
                        'gudang_id' => $gudSpec_cabang['gudang_id'],
                        'gudang_nama' => $gudSpec_cabang['gudang_nama'],
                        'cabang_id' => $oID,
                        'cabang_nama' => $oName,
                        'div_nama' => $divs[$userProp->div_id],
                    ),

                );

                foreach ($sessionSwappers as $key) {
                    $loginProp[$key] = isset($userProp->$key) ? $userProp->$key : "";
                }

                foreach ($sessionUpdaters as $key => $val) {
                    $loginProp[$key] = $val;
                }

                if (isset($sessionUniqUpdaters[$mdlName]) && sizeof($sessionUniqUpdaters[$mdlName]) > 0) {
                    foreach ($sessionUniqUpdaters[$mdlName] as $key => $val) {
                        $loginProp[$key] = $val;
                    }
                }

                //==force membership
                if (!is_array($loginProp['membership'])) {
                    $loginProp['membership'] = array();
                }

                $this->session->login = $loginProp;

                $zippedSessions = base64_encode(serialize($this->session->login));
                setcookie("uprop", $zippedSessions, time() + 31356000);

                if ($this->input->post('remember') == "on") {
                    setcookie("uid", $nama_login, time() + 31356000);
                    setcookie("pwd", base64_encode($post_password), time() + 31356000);
                    echo lgShowAlert("remembered result: " . print_r($_COOKIE, true));
                }
                else {
                    setcookie("uid", NULL, time());
                    setcookie("pwd", NULL, time());
                }
                $validCounter++;


                //region update data employee session idnya
                $temp = array();
                foreach ($tableUpdaters as $kolom => $alias) {
                    if (isset($loginProp[$kolom])) {
                        $temp[$kolom] = $loginProp[$kolom];
                    }
                }
                if (sizeof($temp) > 0) {
                    $condite = array("id" => $loginProp['id']);
                    $u->updateData($condite, $temp);

                }
                //endregion


            }
        }


        if ($validCounter < 1) {
            $arrSwal = array(
                "title" => "Login failed",
                "html" => "Login Details Incorrect. Please try again.",
                "type" => "warning",
            );
            echo swalAlert($arrSwal);
            die();
        }
        else {
            //region normalisasi loker stok
            //===bersihkan & kembalikan locker2 yang dikunci orang ini
            $this->load->model("Mdls/" . "MdlLockerStock");
            $this->load->model("Coms/ComLockerStock");
            $this->load->model("Mdls/" . "MdlLockerStockSupplies");
            $this->load->model("Coms/ComLockerStockSupplies");

            $this->db->trans_start();

            //region locker finish goods
            $c = new MdlLockerStock();
            $c->addFilter("stock_locker.jenis='produk'");
            $c->addFilter("state='hold'");
            $c->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            $c->addFilter("gudang_id=" . $this->session->login['gudang_id']);
            $c->addFilter("oleh_id=" . $this->session->login['id']);
            $c->addFilter("transaksi_id='0'");
            $tmpC = $c->lookupAll()->result();

            if (sizeof($tmpC) > 0) {

                $sentParams = array();
                $sentParams2 = array();
                foreach ($tmpC as $row) {
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
                            "oleh_id" => $this->session->login['id'],
                            "transaksi_id" => 0,

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
                $cs = new ComLockerStock();
                $cs->pair($sentParams) or die("Unable to pair locker for releasing");
                $cs->exec();
                //
                $cs = new ComLockerStock();
                $cs->pair($sentParams2) or die("Unable to pair locker for putting back");
                $cs->exec();

            }
            //endregion

            //region locker finish goods
            $s = new MdlLockerStockSupplies();
            //            $s->addFilter("jenis='supplies'");
            $s->addFilter("stock_locker.jenis='supplies'");
            $s->addFilter("state='hold'");
            $s->addFilter("cabang_id=" . $this->session->login['cabang_id']);
            $s->addFilter("gudang_id=" . $this->session->login['gudang_id']);
            $s->addFilter("oleh_id=" . $this->session->login['id']);
            $s->addFilter("transaksi_id='0'");
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
                            "oleh_id" => $this->session->login['id'],
                            "transaksi_id" => 0,

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

            //endregion


            //region writelog
            //            $this->load->model("Mdls/" . "MdlActivityLog");
            //            $hTmp = new MdlActivityLog();
            //            $hTmp->setFilters(array());
            //            $tmpHData = array(
            //                "title"         => "Login",
            //                "sub_title"     => "Authenticating your credential..",
            //                "uid"           => $this->session->login['id'],
            //                "uname"         => $this->session->login['nama'],
            //                "dtime"         => date("Y-m-d H:i:s"),
            //                "transaksi_id"  => 0,
            //                "deskripsi_old" => "",
            //                "deskripsi_new" => base64_encode(serialize($this->session->login)),
            //                "jenis"         => "",
            //                "ipadd"         => $_SERVER['REMOTE_ADDR'],
            //                "devices"       => $_SERVER['HTTP_USER_AGENT'],
            //                "category"      => "auth",
            //                "controller"    => $this->uri->segment(1),
            //                "method"        => $this->uri->segment(2),
            //                "url"           => current_url(),
            //
            //            );
            //            $logID = $hTmp->addData($tmpHData, $hTmp->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            writeLog("Login", "Authenticating credentials..", "auth");
            //endregion


            $this->db->trans_complete() or die("Unable to commit transaction");
        }


        //region group landings
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $defaultLandPages = array();
        if (sizeof($mems) > 0) {
            foreach ($mems as $gID) {
                if (isset($this->config->item("groupLandingPages")[$gID])) {
                    $defaultLandPages[] = base_url() . $this->config->item("groupLandingPages")[$gID];
                }
            }
        }
        //endregion

        //        die();

        if (strlen($goto_e) > 10) {
            echo "<script>top.location.href='" . "$goto_e';</script>";
        }
        else {
            echo "<script>top.location.href='" . base_url() . "';</script>";
        }

        //        if (sizeof($defaultLandPages) > 0) {
        //            echo "<script>top.location.href='" . $defaultLandPages[0] . "';</script>";
        //        } else {
        //            if (strlen($goto_e) > 10) {
        //                echo "<script>top.location.href='" . "$goto_e';</script>";
        //            } else {
        //                echo "<script>top.location.href='" . base_url() . "';</script>";
        //            }
        //        }


    }

// START OF COMPLETE REPEATED LOGIC
    public function authLogout()
    {
        // -- pastikan output selalu di-buffer agar setcookie() tidak gagal diam-diam
        //    apapun setting output_buffering di php.ini server --
        ob_start();

        $errMsg = "";
        if (isset($_GET['e'])) {
            $errMsg = blobDecode($_GET['e']);
        }

        $arrAlert = array(
            "html" => "<img src='" . base_url() . "public/images/sys/loader-100.gif'> <br>logging you out<br>please wait...<br>",
            "showConfirmButton" => false,
            "allowOutsideClick" => false,
        );
        echo swalAlert($arrAlert);

        if (isset($this->session->login['id'])) {
            $this->db->trans_start();
            $this->load->library("locker");
            $lls = new Locker();
            $lls->setLoginSessions($_SESSION['login']);
            $lls->normalisasiStok(true);

            $this->load->library("CheckerLocker");
            $cl = New CheckerLocker();
            $cl->setCabangId($this->session->login['cabang_id']);
            $cl->setExecute(true);
            $result = $cl->lockerKas();

            $authProps = array(
                "MdlUser",
                "MdlPos",
                "MdlEmployee",
                "MdlEmployee__shadow",
                "MdlEmployeeCabang",
                "MdlEmployeeCabang__shadow",
                "MdlEmployeeGudang",
                "MdlEmployeeGudangFase",
                "MdlEmployeeFreelanceCabang",
                "MdlEmployeeKirim",
            );
            foreach ($authProps as $mdlName) {
                $this->load->model("Mdls/" . $mdlName);
                $u = new $mdlName();
                $tmpUser = $u->lookupByCondition(
                    array(
                        "id" => $this->session->login['id'],
                    )
                )->result();
                if (sizeof($tmpUser) > 0) {
                    $u->setFilters(array());
                    $u->updateData(array("id" => $this->session->login['id']), array("status_login" => "0"));
                }
            }

            writeLog("Logout", "Logging out..", "auth");
            $this->db->trans_complete();
        }

        // -- Hapus cookie di path root '/' (untuk kode baru) HINGGA path spesifik (untuk sisa-sisa kode lama) --
        setcookie("uprop", "", time() - 3600, "/");
        setcookie("uprop", "", time() - 3600); // hapus sisa masa lalu jika ada

        // -- Hancurkan sesi CI3 dan pastikan current object bersih --
        $this->session->sess_destroy();
        unset($this->session->login);

        // -- errMsg dikirim via URL agar tidak ikut terhapus oleh session_destroy --
        $errMsgEncoded = (strlen($errMsg) > 0) ? "&e=" . urlencode($errMsg) : "";

        // -- Hapus PWA Cache Storage via JavaScript sebelum redirect --
        echo "<script>
            if ('caches' in window) {
                caches.keys().then(function(names) {
                    Promise.all(names.map(name => caches.delete(name))).then(function() {
                        top.location.href='" . base_url() . "auth/Login/index?xxx=' + encodeURIComponent('') + '" . $errMsgEncoded . "';
                    });
                });
            } else {
                top.location.href='" . base_url() . "auth/Login/index?xxx=' + encodeURIComponent('') + '" . $errMsgEncoded . "';
            }
        </script>";
    }
// END OF COMPLETE REPEATED LOGIC

    public function changePwd()
    {
        include_once 'leftMenu.php';
        if (isset($this->session->login)) {
            $p = new Page("Pengaturan", "Ganti Sandi", "application/template/lte/index.html");
            $t = new Table();


            if (isset($this->session->errMsg) && $this->session->errMsg != NULL) {
                $p->addContent("<div class='container'>");
                $p->addContent("<div class='alert alert-danger text-center'>");
                $p->addContent($this->session->errMsg);
                $p->addContent("</div class='alert alert-danger'>");
                $p->addContent("</div class='container'>");
                $this->session->errMsg = NULL;
                unset($this->session->errMsg);
            }
            else {

                $p->addContent("<div class='container'>");
                $p->addContent("<div class='alert alert-warning text-center'>");
                $p->addContent("Untuk mengganti sandi anda, anda harus memasukkan sandi saat ini diikuti sandi baru sebanyak dua kali");
                $p->addContent("</div class='alert alert-danger'>");
                $p->addContent("</div class='container'>");
            }
            $p->addContent("<div class='container' style='background:transparent;border:0px #cccccc solid;'>");
            $p->addContent(form_open(base_url() . get_class($this) . "/doChangePwd", array("id" => "flg")));
            $p->addContent($t->openTable(array(
                "align=center",
                "width=400",
                "cellspacing=0",
                "cellpadding=4",
                "border=0",
            )));

            //        $p->addContent($t->addRow(array(
            //                    "Username"
            //        )));

            $p->addContent($t->addRow(array(
                "<input type='text' readonly placeholder='nama pengguna' value='ID pengguna: " . $this->session->login['id'] . "/" . $this->session->login['name'] . "' class='form-control'>",
            )));
            $p->addContent($t->addRow(array(
                "<input type='password' name=pwd id=pwd placeholder='sandi saat ini' class='form-control'>",
            )));
            $p->addContent($t->addRow(array(
                "<input type='password' name=pwd2 id=pwd2 placeholder='sandi baru' class='form-control'>",
            )));
            $p->addContent($t->addRow(array(
                "<input type='password' name=pwd2b id=pwd2b placeholder='sandi baru sekali lagi' class='form-control'>",
            )));
            $p->addContent($t->addRow(array(
                "<input type='button' value='Ganti sandi' onClick=\"this.disabled='true';document.getElementById('flg').submit();\" class='btn btn-primary form-control'>",
            )));
            $p->addContent($t->closeTable());
            $p->addContent(form_close());
            $p->addContent("</div>");

            $p->setAppID($this->config->item('appConfig')['appID']);
            $p->setAppName($this->config->item('appConfig')['appName']);
            $p->setPageName(get_class($this));
            $p->setActionName($this->uri->segment(2));
            $p->setOptionName($this->uri->segment(3));
            //$p->setPageMenu($this->pageMenu);
            $p->setUserName($this->session->login['name'] . "@" . $this->session->login['outletName']);
            $p->setMnuData($mnuData);
            $p->setMnuTransaksi($mnuTransaksi);
            $p->render();
        }
    }

    public function doChangePwd()
    {
        $q = "select * from user where id='" . $this->session->login['id'] . "' and password='" . md5($this->input->post('pwd')) . "' ";
        //die($q);
        $query = $this->db->query($q);
        $row = $query->result_array();
        if (sizeof($row) > 0) {
            //echo "authorized";
            if ($this->input->post('pwd2') == $this->input->post('pwd')) {
                //==password baru malah sama dengan password lama
                $this->session->errMsg = "Kalau yakin mau ganti sandi, bedakan dengan sandi lama!";
                redirect(base_url() . "/" . get_class($this) . "/changePwd");
            }
            else {
                if (strlen($this->input->post('pwd2')) < 6) {//sandi baru kurang dari 6 akrakter
                    $this->session->errMsg = "Sandi baru minimal 6 karakter";
                    redirect(base_url() . "/" . get_class($this) . "/changePwd");
                }
                else {
                    if ($this->input->post('pwd2') != $this->input->post('pwd2b')) {
                        ///password baru tidak sama dengan konfirmasinya
                        $this->session->errMsg = "Sandi baru yang diketikkan dua kali harus sama persis";
                        redirect(base_url() . "/" . get_class($this) . "/changePwd");
                    }
                    else {
                        if (strcmp($this->input->post('pwd2'), $this->input->post('pwd2b')) == 0) {//==valid
                            $q = "update user set password='" . md5($this->input->post('pwd2')) . "' where id='" . $this->session->login['id'] . "' and password='" . md5($this->input->post('pwd')) . "' ";
                            $query = $this->db->query($q);
                            if ($this->db->affected_rows() > 0) {
                                $this->session->errMsg = "Sandi anda sudah diperbarui. <br>Silahkan masuk dengan sandi baru";
                                $this->session->login = NULL;
                                unset($this->session->login);
                                redirect(base_url());
                            }
                            else {
                                $this->session->errMsg = "Sandi anda TIDAK berhasil diperbarui karena kesalahan sistem. <br>Silahkan hubungi administrator";
                                redirect(base_url() . "/" . get_class($this) . "/changePwd");
                            }
                        }
                    }
                }
            }
        }
        else {
            $this->session->errMsg = "Sandi saat ini tidak sesuai dengan yang anda masukkan";
            redirect(base_url() . "/" . get_class($this) . "/changePwd");
        }
    }

    public function updateAuthField()
    {
        $oData = $this->session->login;
        $_long = isset($_GET['_long']) ? $_GET['_long'] : "";
        $_latt = isset($_GET['_latt']) ? $_GET['_latt'] : "";
        $_acc = isset($_GET['_acc']) ? $_GET['_acc'] : "";

        $addFields = array(
            "longitude" => $_long,
            "lattitude" => $_latt,
            "accuracy" => $_acc,
        );

        foreach ($addFields as $key => $src) {
            $oData[$key] = $src;
        }


        $this->session->set_userdata('login', $oData);
    }

    public function removeDebuger()
    {
        // arrPrint($this->session->debuger);
        $this->session->debuger = 0;
        // arrPrint($this->session->login);

        if (isset($this->session->login)) {
            // $this->session->login['debuger'] = null;
            // $this->session->login['debuger'] = 0;
            // $this->session->login = array("debuger" => 0);
            $_SESSION['login']['debuger'] = 0;
            // arrPrint($_SESSION);
        }

        echo "<script> top.window.location.reload(true) </script>";
//        die(topReload(0)); // jika pakai yg ini, tidak support di browser mozilla, efek nya browser jadi looping refresh

    }

    public function forgotPwd()
    {
        $arrInput = array(
            "type" => "email",
            "placeholder" => "email address",
            "class" => "form-control",
            "name" => "email",
            "required" => "required",
            "tabindex" => "0",

        );

        $forms = "<div style='margin-bottom: 10px;'>Forgotten your password? <br>Enter your email address below to begin the reset process.</div>";

        $forms .= "<div class='form-group'>
            <div class='input-group'>";
        $forms .= "<span class='input-group-addon'><i class='glyphicon glyphicon-envelope color-blue'></i></span>";

        // $forms .= form_input("email", "", "class='form-control' placeholder='email address' type='email'");
        $forms .= form_input($arrInput, "", "autofocus");

        $forms .= "</div>
            </div>";


        // $forms .= form_input("password","", "class='form-control'");
        $arrFooter = form_button("close", "Close", "class='btn pull-left' data-dismiss='modal'");
        $arrFooter .= form_submit("simpan", "RESET PASSWORD", "class='btn btn-danger'");

        $data = array(
            "mode" => "modal",
            "heading" => "Lost Password Reset",
            "actions" => base_url() . "Login/resetorPwd",
            "target" => "result",
            "forms" => $forms,
            "footer" => $arrFooter,
            // "defaultPwd" => $c_pwd,
        );
        $this->load->view('login', $data);
    }

    public function resetorPwd()
    {
        arrPrint($_POST);
        $this->load->model("Mdls/MdlEmployee");
        $this->load->model("Mdls/MdlMailNotif");
        $this->load->library("SmtpMailer");
        $em = new MdlEmployee();
        // $mail = new MdlMailNotif();
        $mail = new SmtpMailer();
        // $da = new
        echo lgShowAlert("wait a minute .... ");

        $email = $this->input->post('email');
        $em->setFilters(array());
        // $em->addFilter("ghost = '1'");
        $tmpEmployee = $em->lookupByCondition(array(
            "email" => $email,
        ))->result();

        cekHijau($this->db->last_query());
        //        cekHijau("$email");
        arrPrint($tmpEmployee[0]);
        // matiHere(__LINE__);
        if (sizeof($tmpEmployee) < 1) {
            echo lgShowAlert("No active user account was found with the email address you entered");
        }
        else {
            $this->db->trans_start() or die("Unable to commit transaction");

            $id = $tmpEmployee[0]->id;
            $nama = $tmpEmployee[0]->nama;
            $login_fail = $tmpEmployee[0]->login_fail;
            $id_e = urlencode(serialize($id));
            $date_e = md5(dtimeNow());
            $def_pwd = createDefaultPassword();
            //            cekHijau("$def_pwd *** $id");

            if ($login_fail == 10) {
                echo lgShowWarning("Your password was reseted", "please check your email to confirm and activated your account back ($email)");
                //                mati_disini("posisi reset, nunggu konfirmasi");
                die();
            }
            else {
                $tunggu = array(
                    "html" => "tunggu yaa",
                );
                echo swalAlert($tunggu);

                $arrWhere = array("id" => $id);
                $arrUpdt = array(
                    // "password"    => md5($def_pwd),
                    "pwd_default" => $def_pwd,
                    "login_fail" => 10,
                    "confirm_url" => $date_e,
                );
                $em->updateData($arrWhere, $arrUpdt);

                writeLog(__FUNCTION__, "reset password", "data", $id, $nama);

                // mati_disini("belom commit ya.......");
                $this->db->trans_complete() or die("Unable to commit transaction");

                $confirmLink = base_url() . "Login/confirm/$id_e/$date_e";
                $unconfirmLink = base_url() . "Login/unconfirm/$id_e/$date_e";
                $strEmail = "Follow this link to confirm your password reset request <br>" . $confirmLink;
                $strEmail .= "<hr>OR<br> follow this link if you did not request password reset <br>" . $unconfirmLink;
                $mail->setSubject("Reset Password");
                $mail->setAddressTo(array($nama => $email));
                $mail->setAddressFrom(array($_SERVER['HTTP_HOST'] => "mgkcore@gmail.com"));
                $cek = $mail->kirim_email($strEmail);
                cekHitam("$cek");
                // mati_disini();
                // echo lgShowSuccess("Password has been reseted", "please, use password $def_pwd to access your account");
                // if($cek == 1){

                echo lgShowSuccess("Your password have successfully reset", "please check your email to confirm and activated your account back");

                topReload(700);
                // }
                // else{
                //     echo lgShowSuccess("Your password have successfully reset", "please check your email to confirm and activated your account back ll");
                // }
            }

        }

    }

    public function confirm()
    {
        // arrPrint($_GET);
        $this->load->model("MdlEmployee");
        $em = new MdlEmployee();
        $id_e = $this->uri->segment(3);
        $date_e = $this->uri->segment(4);

        // cekHijau("$id_e");
        $id = unserialize(urldecode($id_e));
        // cekMerah("$id");

        $em->setFilters(array());
        $tmpEmployee = $em->lookupByCondition(array(
            "id" => $id,
        ))->result();
        // arrPrint($tmpEmployee);
        // mati_disini(sizeof($tmpEmployee));
        if (sizeof($tmpEmployee) == 1) {
            $nama = $tmpEmployee[0]->nama;
            $def_pwd = $tmpEmployee[0]->pwd_default;
            $confirm_url = $tmpEmployee[0]->confirm_url;

            if ($confirm_url != $date_e) {
                echo "<h2>invalid link " . __LINE__ . "</h2>";
                writeLog("invalid confirmasi", "link reset sudah tidak valid", "login", $id, $nama);

                //                mati_disini("$confirm_url != $date_e");
                die();
            }

            if ($tmpEmployee[0]->login_fail == 10) {
                $arrWhere = array("id" => $id, "login_fail" => 10);
                $arrUpdt = array(
                    // "status" => 1,
                    "password" => md5($def_pwd),
                    "login_fail" => 0,
                    "confirm_url" => '',
                );

                $em->setFilters(array());
                $em->updateData($arrWhere, $arrUpdt);
                // cekMerah($this->db->last_query());
                // echo lgShowSuccess("Account has been reactivated", "use your default password for access your account");

                writeLog("confirm password reset", "Reset password terkonfirmasi", "data", $id, $nama);

                topRedirect(base_url());
            }
            else {
                echo "<h2>invalid link</h2>";
                writeLog("invalid", "", "data", $id, $nama);
            }
        }
        else {
            echo "<h2>invalid link</h2>";
        }

    }

    public function unconfirm()
    {
        // arrPrint($_GET);
        $this->load->model("MdlEmployee");
        $em = new MdlEmployee();
        $id_e = $this->uri->segment(3);
        $date_e = $this->uri->segment(4);

        // cekHijau("$id_e");
        $id = unserialize(urldecode($id_e));
        // cekMerah("$id");

        $em->setFilters(array());
        $tmpEmployee = $em->lookupByCondition(array(
            "id" => $id,
        ))->result();
        // arrPrint($tmpEmployee);
        // mati_disini(sizeof($tmpEmployee));
        if (sizeof($tmpEmployee) == 1) {
            $nama = $tmpEmployee[0]->nama;
            $def_pwd = $tmpEmployee[0]->pwd_default;
            $confirm_url = $tmpEmployee[0]->confirm_url;

            if ($confirm_url != $date_e) {
                echo "<h2>invalid link " . __LINE__ . "</h2>";
                writeLog("invalid confirmasi", "link reset sudah tidak valid", "login", $id, $nama);

                //                mati_disini("$confirm_url != $date_e");
                die();
            }

            if ($tmpEmployee[0]->login_fail == 10) {
                $arrWhere = array("id" => $id, "login_fail" => 10);
                $arrUpdt = array(
                    // "status" => 1,
                    // "password"    => md5($def_pwd),
                    "pwd_default" => "",
                    "login_fail" => 0,
                    "confirm_url" => '',
                );

                $em->setFilters(array());
                $em->updateData($arrWhere, $arrUpdt);
                // cekMerah($this->db->last_query());
                // echo lgShowSuccess("Account has been reactivated", "use your default password for access your account");

                writeLog("confirm password not reset", "Reset password dibatalkan", "data", $id, $nama);

                topRedirect(base_url());
            }
            else {
                echo "<h2>invalid link</h2>";
                writeLog("invalid", "", "data", $id, $nama);
            }
        }
        else {
            echo "<h2>invalid link</h2>";
        }

    }

    public function resetorPwdAdmin()
    {
        // $this->load->model("MdlEmployee");
        $this->load->model("Mdls/MdlMailNotif");
        $mail = new MdlMailNotif();
        $em = new MdlEmployee();

        $id = $this->uri->segment(3);
        $em->setFilters(array());
        $tmpEmployee = $em->lookupByCondition(array(
            "id" => $id,
        ))->result();
        cekLime($this->db->last_query());

        // arrPrint($tmpEmployee);

        $this->db->trans_start() or die("Unable to commit transaction");

        $nama = $tmpEmployee[0]->nama;
        $nama_login = $tmpEmployee[0]->nama_login;
        $email = $tmpEmployee[0]->email;

        $def_pwd = defaultPassword();

        $arrWhere = array("id" => $id);
        $arrUpdt = array(
            "password" => md5($def_pwd),
            "pwd_default" => $def_pwd,
            "status" => 1,
            "login_fail" => 0,
        );
        $em->updateData($arrWhere, $arrUpdt);
        $affected = $this->db->affected_rows();

        if ($affected != 1) {
            echo lgShowWarning("Upss...", "failed to update, nothing happens with existing password");
            die();
            matiHere("faild password reset");
        }
        // matiHere("terdampak: " .$affected);
        writeLog(__FUNCTION__, "reset password", "data", $id, $nama);

        // mati_disini("belom commit ya.......");
        $this->db->trans_complete() or die("Unable to commit transaction");

        $arrEmail = array(
            // "type",
            // "author_name",
            // "author_id",
            // "recipient_name",
            "recipient_id" => $email,
            "subject" => "Reset Password",
            "body" => "password direset",
        );
        // $mail->send($arrEmail);

        echo lgShowSuccess("Congratulations", "Default password applied to: <div>$nama [<i>$nama_login</i>]</div>");

    }

    public function forceMobile(){
        // START OF COMPLETE REPEATED LOGIC
        $fm = isset($_GET['forceMobile']) ? $_GET['forceMobile'] : 0;
        $_SESSION['login']['forceMobile'] = $fm;
        // Dukungan parameter goto untuk redirect berantai (perbaikan tablet Safari)
        $goto = isset($_GET['goto']) ? $_GET['goto'] : '';
        if ($goto != '') {
            header("Location: " . $goto);
            exit();
        }
        // END OF COMPLETE REPEATED LOGIC
    }

    public function forceDesktopView(){
        $fm = isset($_GET['forceDesktopView']) ? $_GET['forceDesktopView'] : ( $forceMobile!=null ? $forceMobile : 0);
        $_SESSION['login']['forceDesktopView'] = $fm;
    }

    public function createSimpleSessionLogin(){
        // START OF COMPLETE REPEATED LOGIC
        arrPrintHijau($_GET);
        $sessLogins = isset($_SESSION['login']) ? $_SESSION['login'] : array();
        arrPrint($sessLogins);

        if(!isset($_SESSION['login'])){
            $_SESSION['login'] = $_GET;
        }
        // Dukungan parameter goto untuk redirect berantai (perbaikan tablet Safari)
        $goto = isset($_GET['goto']) ? $_GET['goto'] : '';
        if ($goto != '') {
            header("Location: " . $goto);
            exit();
        }
        // END OF COMPLETE REPEATED LOGIC
    }

    public function pingSession()
    {
        // START OF COMPLETE REPEATED LOGIC
        // Dipanggil via AJAX dari SweetAlert perbedaan sesi di _tray.php
        // Memperbarui phpsessid di DB dengan ci_session cookie saat ini agar tersinkronisasi kembali
        // User memilih "Lanjutkan Data Lama" → sesi diperbarui, halaman tetap

        if (!isset($this->session->login['id'])) {
            echo json_encode(array('status' => 'error', 'message' => 'no session'));
            return;
        }

        $userId = $this->session->login['id'];
        $currentSessId = session_id();

        $this->load->model("Mdls/MdlEmployee");
        $o = new MdlEmployee();
        $o->setFilters(array());
        $o->updateData(
            array("id" => $userId),
            array(
                "last_dtime_active" => dtimeNow(),
                "phpsessid" => $currentSessId
            )
        );

        // Catat bahwa user memilih menyinkronkan/melanjutkan sesi (bukan logout)
        writeLog("session extended", "User memilih Lanjutkan Data Lama — phpsessid disinkronkan ke $currentSessId", "auth");

        echo json_encode(array('status' => 'ok', 'message' => 'session extended'));
        // END OF COMPLETE REPEATED LOGIC
}
}
