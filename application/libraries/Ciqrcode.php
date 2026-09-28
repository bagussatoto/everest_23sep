<?php
/**
 * PHP QR Code porting for Codeigniter
 *
 * @package            CodeIgniter
 * @subpackage        Libraries
 * @category        Libraries
 * @porting author    dwi.setiyadi@gmail.com
 * @original author    http://phpqrcode.sourceforge.net/
 *
 * @version        1.0
 */

include APPPATH . "libraries/qrcode/qrconst.php";
include APPPATH . "libraries/qrcode/qrtools.php";
include APPPATH . "libraries/qrcode/qrspec.php";
include APPPATH . "libraries/qrcode/qrimage.php";
include APPPATH . "libraries/qrcode/qrinput.php";
include APPPATH . "libraries/qrcode/qrbitstream.php";
include APPPATH . "libraries/qrcode/qrsplit.php";
include APPPATH . "libraries/qrcode/qrrscode.php";
include APPPATH . "libraries/qrcode/qrmask.php";
include APPPATH . "libraries/qrcode/qrencode.php";

class Ciqrcode
{
    var $cacheable = true;
    var $cachedir = 'application/cache/';
    var $errorlog = 'application/logs/';
    var $quality = true;
    var $size = 1024;

    function __construct($config = array())
    {
        /*dipindah keluar clas karena akan timbul error redeclare*/
        // include APPPATH. "/third_party/qrcode/qrconst.php";
        // include APPPATH. "/third_party/qrcode/qrtools.php";
        // include APPPATH. "/third_party/qrcode/qrspec.php";
        // include APPPATH. "/third_party/qrcode/qrimage.php";
        // include APPPATH. "/third_party/qrcode/qrinput.php";
        // include APPPATH. "/third_party/qrcode/qrbitstream.php";
        // include APPPATH. "/third_party/qrcode/qrsplit.php";
        // include APPPATH. "/third_party/qrcode/qrrscode.php";
        // include APPPATH. "/third_party/qrcode/qrmask.php";
        // include APPPATH. "/third_party/qrcode/qrencode.php";

        $this->initialize($config);
    }

    public function initialize($config = array())
    {
        $this->cacheable = (isset($config['cacheable'])) ? $config['cacheable'] : $this->cacheable;
        $this->cachedir = (isset($config['cachedir'])) ? $config['cachedir'] : FCPATH . $this->cachedir;
        $this->errorlog = (isset($config['errorlog'])) ? $config['errorlog'] : FCPATH . $this->errorlog;
        $this->quality = (isset($config['quality'])) ? $config['quality'] : $this->quality;
        $this->size = (isset($config['size'])) ? $config['size'] : $this->size;

        // use cache - more disk reads but less CPU power, masks and format templates are stored there
        if (!defined('QR_CACHEABLE')) {
            define('QR_CACHEABLE', $this->cacheable);
        }

        // used when QR_CACHEABLE === true
        if (!defined('QR_CACHE_DIR')) {
            define('QR_CACHE_DIR', $this->cachedir);
        }

        // default error logs dir
        if (!defined('QR_LOG_DIR')) {
            define('QR_LOG_DIR', $this->errorlog);
        }

        // if true, estimates best mask (spec. default, but extremally slow; set to false to significant performance boost but (propably) worst quality code
        if ($this->quality) {
            if (!defined('QR_FIND_BEST_MASK')) {
                define('QR_FIND_BEST_MASK', true);
            }
        }
        else {
            if (!defined('QR_FIND_BEST_MASK')) {
                define('QR_FIND_BEST_MASK', false);
            }
            if (!defined('QR_DEFAULT_MASK')) {
                define('QR_DEFAULT_MASK', $this->quality);
            }
        }

        // if false, checks all masks available, otherwise value tells count of masks need to be checked, mask id are got randomly
        if (!defined('QR_FIND_FROM_RANDOM')) {
            define('QR_FIND_FROM_RANDOM', false);
        }

        // maximum allowed png image width (in pixels), tune to make sure GD and PHP can handle such big images
        if (!defined('QR_PNG_MAXIMUM_SIZE')) {
            define('QR_PNG_MAXIMUM_SIZE', $this->size);
        }
    }

    public function generate($params = array())
    {
        if (isset($params['black'])
            && is_array($params['black'])
            && count($params['black']) == 3
            && array_filter($params['black'], 'is_int') === $params['black']) {
            QRimage::$black = $params['black'];
        }

        if (isset($params['white'])
            && is_array($params['white'])
            && count($params['white']) == 3
            && array_filter($params['white'], 'is_int') === $params['white']) {
            QRimage::$white = $params['white'];
        }

        $params['data'] = (isset($params['data'])) ? $params['data'] : 'QR Code Library';
        if (isset($params['savename'])) {
            $level = 'L';
            if (isset($params['level']) && in_array($params['level'], array('L', 'M', 'Q', 'H'))) {
                $level = $params['level'];
            }

            $size = 4;
            if (isset($params['size'])) {
                $size = min(max((int)$params['size'], 1), 10);
            }

            QRcode::png($params['data'], $params['savename'], $level, $size, 2);
            return $params['savename'];
        }
        else {
            $level = 'L';
            if (isset($params['level']) && in_array($params['level'], array('L', 'M', 'Q', 'H'))) {
                $level = $params['level'];
            }

            $size = 4;
            if (isset($params['size'])) {
                $size = min(max((int)$params['size'], 1), 10);
            }

            QRcode::png($params['data'], NULL, $level, $size, 2);
        }
    }

    /*vv kebawah sini adalah customisasi*/
    public function get_qrcode($data_isi)
    {
        $url_katalog = "https://everest.com/";
        // $url_katalog = base_url()."ndosan.com/";
        $key_wajib = $this->get_qr_key_patern();
        $minim_input = array_slice($data_isi, 0, sizeof($key_wajib));
        $key_input = array_keys($minim_input);
        /* -----------------------------------------------
         * format data yg akan dijadikan QR harus persis spt ini
         * ------------------------------*/
        // arrPrintHijau($key_input);
        // arrPrintHijau($key_wajib);
        // $ok = $key_wajib === $key_input ? true : matiHere("format konten tidak standart, referensi lihat dimari " . __FILE__ . " " . __FUNCTION__);

        $tempVar = urlencode(json_encode($data_isi));
        // $tempVar = "test";
        unset($data_isi["data"]);
        $data = implode("/", $data_isi);

        // $nama_data = str_replace("/", "_", $data);
        $nama_data = str_replace("/", "_", $data);
        // arrPrintWebs($nama_data);
        $hex_data = $nama_data;

        /* Load QR Code Library */
        // $this->load->library('ciqrcode');

        /* Data */
        // $str_ok = $this->deteksiKarakter($data);
        // if ($str_ok == true) {
        //     $nama_data = str_replace("|", "_", $data);
        //     if (strlen($data) <= 10) {
        //         $hex_data = $nama_data;
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, 16);
        //     }
        // }
        // else {
        //     $nama_data = bin2hex($data);
        //     if (strlen($data) <= 10) {
        //         $hex_data = $nama_data;
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, -16);
        //     }
        // }

        $save_name = $hex_data . '.png';
        // $data = $url_katalog.blobEncode($nama_data);
        $data = $url_katalog . urlencode($nama_data);

        /* QR Code File Directory Initialize */
        // $dir = 'assets/media/qrcode/';
        $dir = 'public/images/qrcode/';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable'] = true;
        $config['imagedir'] = $dir;
        $config['quality'] = true;
        $config['size'] = '1024';
        $config['black'] = array(255, 255, 255);
        $config['white'] = array(255, 255, 255);
        $this->initialize($config);
// arrPrintWebs($tempVar);
        /* QR Data  */
        // $params['data'] = $data;
        $params['data'] = $tempVar;
        $params['level'] = 'L';
        $params['size'] = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $this->generate($params);

        /* Return Data */
        $return = array(
            'content' => $data,
            'file' => $dir . $save_name
        );
        return $return;
    }

    public function get_qrcode_barcode($data_isi)
    {
        $url_katalog = "https://everest.com/";
        // $url_katalog = base_url()."ndosan.com/";
        // $key_wajib = $this->get_qr_key_patern();
        // $minim_input = array_slice($data_isi, 0, sizeof($key_wajib));
        // $key_input = array_keys($minim_input);
        /* -----------------------------------------------
         * format data yg akan dijadikan QR harus persis spt ini
         * ------------------------------*/
        // arrPrintHijau($key_input);
        // arrPrintHijau($key_wajib);
        // $ok = $key_wajib === $key_input ? true : matiHere("format konten tidak standart, referensi lihat dimari " . __FILE__ . " " . __FUNCTION__);

        // $tempVar = urlencode(json_encode($data_isi));
        $tempVar = $data_isi;
        // unset($data_isi["data"]);
        // $data = implode("/", $data_isi);

        // $nama_data = str_replace("/", "_", $data);
        // $nama_data = str_replace("/", "_", $data);
        // arrPrintWebs($nama_data);
        $hex_data = $data_isi;

        /* Load QR Code Library */
        // $this->load->library('ciqrcode');

        /* Data */
        // $str_ok = $this->deteksiKarakter($data);
        // if ($str_ok == true) {
        //     $nama_data = str_replace("|", "_", $data);
        //     if (strlen($data) <= 10) {
        //         $hex_data = $nama_data;
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, 16);
        //     }
        // }
        // else {
        //     $nama_data = bin2hex($data);
        //     if (strlen($data) <= 10) {
        //         $hex_data = $nama_data;
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, -16);
        //     }
        // }

        $save_name = $hex_data . '.png';
        // $data = $url_katalog.blobEncode($nama_data);
        // $data = $url_katalog.urlencode($nama_data);
        $data = $data_isi;

        /* QR Code File Directory Initialize */
        // $dir = 'assets/media/qrcode/';
        $dir = 'public/images/qrcode/';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable'] = true;
        $config['imagedir'] = $dir;
        $config['quality'] = true;
        $config['size'] = '1024';
        $config['black'] = array(255, 255, 255);
        $config['white'] = array(255, 255, 255);
        $this->initialize($config);
        // arrPrintWebs($tempVar);
        /* QR Data  */
        // $params['data'] = $data;
        $params['data'] = $tempVar;
        $params['level'] = 'L';
        $params['size'] = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $this->generate($params);

        /* Return Data */
        $return = array(
            'content' => $data,
            'file' => $dir . $save_name
        );
        return $return;
    }

    function deteksiKarakter($input)
    {
        $regex = "/#/";
        $regex = "/#[a-zA-Z0-9_]+/";
        $regex = "/[^a-zA-Z0-9_-]+/";
        $regex = "/[^A-Za-z0-9_\s-|]/"; // (alfanumerik)+(space)+(_)+(-)+(|)
        if (preg_match_all($regex, $input)) {
            return false;
        }
        else {
            return true;
        }
        // $hasil = [];
        // $hasil = "";

        // preg_match_all($regex, $input, $hasil);

        # kembalikan data dalam bentuk json
        // return json_encode($hasil);
        // return $hasil;
    }

    public function get_qrcode_umum($data_isi)
    {
        // $key_wajib = array(
        //     "produk_id",
        //     "produk_kode",
        //     "jenis_tr",
        //     "transaksi_id",
        // );
        // $minim_input = array_slice($data_isi, 0, sizeof($key_wajib));
        // $key_input = array_keys($minim_input);
        /* -----------------------------------------------
         * format data yg akan dijadikan QR harus persis spt ini
         * ------------------------------*/
        // arrPrintHijau($key_input);
        // arrPrintHijau($key_wajib);
        // $ok = $key_wajib === $key_input ? true : matiHere("format konten tidak standart, referensi lihat dimari " . __FILE__ . " " . __FUNCTION__);

        // $data = implode("|", $data_isi);

        // $nama_data = str_replace("|", "_", $data);
        // $hex_data = $nama_data;

        /* Load QR Code Library */
        // $this->load->library('ciqrcode');

        /* Data */
        // $str_ok = $this->deteksiKarakter($data);
        // if ($str_ok == true) {
        //     $nama_data = str_replace("|", "_", $data);
        //     if (strlen($data) <= 10) {
        //         $hex_data = $nama_data;
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, 16);
        //     }
        // }
        // else {
        $nama_data = bin2hex($data_isi);
        //     if (strlen($data) <= 10) {
        $hex_data = $nama_data;
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, 16);
        //     }
        // }

        $save_name = $hex_data . '.png';

        /* QR Code File Directory Initialize */
        // $dir = 'assets/media/qrcode/';
        $dir = 'public/images/qrcode/';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable'] = true;
        $config['imagedir'] = $dir;
        $config['quality'] = true;
        $config['size'] = '1024';
        $config['black'] = array(255, 255, 255);
        $config['white'] = array(255, 255, 255);
        $this->initialize($config);

        /* QR Data  */
        $params['data'] = $data_isi;
        $params['level'] = 'L';
        $params['size'] = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $this->generate($params);

        /* Return Data */
        $return = array(
            'content' => $data_isi,
            'file' => $dir . $save_name
        );
        return $return;
    }

    public function get_qr_key_patern()
    {
        $key_wajib = array(
            "produk_id",
            "produk_kode",
            // "jenis_tr",
            // "master_id",
            // "transaksi_id",
        );

        return $key_wajib;
    }

    public function qrLanding()
    {
        $url_katalog = "https://indosan.com/";

        return $url_katalog;
    }

    public function qrExtractor($qr_str)
    {
        $str = urldecode($qr_str);
        $str_expl = explode("/", $str);
        $params = explode("_", $str_expl[3]);
        // arrPrintHijau($_REQUEST);
        // cekMerah("$str");

        // $this->load->library("Ciqrcode");
        // $qrc = new Ciqrcode();

        $qrKeys = $this->get_qr_key_patern();
        // arrPrintHijau($qrKeys);
        // arrPrintHijau($params);
        // foreach ($qrKeys as $ky => $qrKey) {
        // $$qrKey = $params[$ky];
        foreach ($params as $ky => $qrVal) {
            $qrKey = isset($qrKeys[$ky]) ? $qrKeys[$ky] : $ky;

            $$qrKey = $qrVal;

            // cekBiru("$qrKey :: $qrVal");

            $hasils[$qrKey] = $qrVal;
        }

        return $hasils;
    }

    public function get_qrcode_produksi($data_isi)
    {
        // $key_wajib = array(
        //     "produk_id",
        //     "produk_kode",
        //     "jenis_tr",
        //     "transaksi_id",
        // );
        // $minim_input = array_slice($data_isi, 0, sizeof($key_wajib));
        // $key_input = array_keys($minim_input);
        /* -----------------------------------------------
         * format data yg akan dijadikan QR harus persis spt ini
         * ------------------------------*/
        // arrPrintHijau($key_input);
        // arrPrintHijau($key_wajib);
        // $ok = $key_wajib === $key_input ? true : matiHere("format konten tidak standart, referensi lihat dimari " . __FILE__ . " " . __FUNCTION__);

        // $data = implode("|", $data_isi);

        // $nama_data = str_replace("|", "_", $data);
        // $hex_data = $nama_data;

        /* Load QR Code Library */
        // $this->load->library('ciqrcode');

        /* Data */
        // $str_ok = $this->deteksiKarakter($data);
        // if ($str_ok == true) {
        //     $nama_data = str_replace("|", "_", $data);
        //     if (strlen($data) <= 10) {
        //         $hex_data = $nama_data;
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, 16);
        //     }
        // }
        // else {
//        arrPrint($data_isi);
//        matiHere(__LINE__);
        $nama_data = $data_isi;
//        $tempVar = urlencode(json_encode($data_isi));
//        unset($data_isi["data"]);
//        $data = implode("/", $data_isi);
//        $nama_data = str_replace("/", "_", $data);
        // arrPrintWebs($nama_data);
        $hex_data = $nama_data;
        //     if (strlen($data) <= 10) {
        $hex_data = $nama_data;
//        cekMErah($hex_data);
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, 16);
        //     }
        // }

        $save_name = $hex_data . '.png';

        /* QR Code File Directory Initialize */
        // $dir = 'assets/media/qrcode/';
        $dir = 'public/images/qrcode/';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable'] = true;
        $config['imagedir'] = $dir;
        $config['quality'] = true;
        $config['size'] = '1024';
        $config['black'] = array(255, 255, 255);
        $config['white'] = array(255, 255, 255);
        $this->initialize($config);

        /* QR Data  */
        $params['data'] = $data_isi;
        $params['level'] = 'L';
        $params['size'] = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $this->generate($params);

        /* Return Data */
        $return = array(
            'content' => $data_isi,
            'file' => $dir . $save_name
        );
//        arrPrint($return);
//        matiHEre();
        return $return;
    }

    public function get_qrcode_pembelian($data_isi)
    {
        // $key_wajib = array(
        //     "produk_id",
        //     "produk_kode",
        //     "jenis_tr",
        //     "transaksi_id",
        // );
        // $minim_input = array_slice($data_isi, 0, sizeof($key_wajib));
        // $key_input = array_keys($minim_input);
        /* -----------------------------------------------
         * format data yg akan dijadikan QR harus persis spt ini
         * ------------------------------*/
        // arrPrintHijau($key_input);
        // arrPrintHijau($key_wajib);
        // $ok = $key_wajib === $key_input ? true : matiHere("format konten tidak standart, referensi lihat dimari " . __FILE__ . " " . __FUNCTION__);

        // $data = implode("|", $data_isi);

        // $nama_data = str_replace("|", "_", $data);
        // $hex_data = $nama_data;

        /* Load QR Code Library */
        // $this->load->library('ciqrcode');

        /* Data */
        // $str_ok = $this->deteksiKarakter($data);
        // if ($str_ok == true) {
        //     $nama_data = str_replace("|", "_", $data);
        //     if (strlen($data) <= 10) {
        //         $hex_data = $nama_data;
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, 16);
        //     }
        // }
        // else {
//        arrPrint($data_isi);
//        matiHere(__LINE__);
        $nama_data = $data_isi;
//        $tempVar = urlencode(json_encode($data_isi));
//        unset($data_isi["data"]);
//        $data = implode("/", $data_isi);
//        $nama_data = str_replace("/", "_", $data);
        // arrPrintWebs($nama_data);
        $hex_data = $nama_data;
        //     if (strlen($data) <= 10) {
        $hex_data = $nama_data;
//        cekMErah($hex_data);
        //     }
        //     else {
        //         $hex_data = substr($nama_data, 0, 16);
        //     }
        // }

        $save_name = $hex_data . '.png';

        /* QR Code File Directory Initialize */
        // $dir = 'assets/media/qrcode/';
        $dir = 'public/images/qrcode/';
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */
        $config['cacheable'] = true;
        $config['imagedir'] = $dir;
        $config['quality'] = true;
        $config['size'] = '1024';
        $config['black'] = array(255, 255, 255);
        $config['white'] = array(255, 255, 255);
        $this->initialize($config);

        /* QR Data  */
        $params['data'] = $data_isi;
        $params['level'] = 'L';
        $params['size'] = 10;
        $params['savename'] = FCPATH . $config['imagedir'] . $save_name;

        $this->generate($params);

        /* Return Data */
        $return = array(
            'content' => $data_isi,
            'file' => $dir . $save_name
        );
//        arrPrint($return);
//        matiHEre();
        return $return;
    }
}