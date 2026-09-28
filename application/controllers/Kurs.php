<?php
error_reporting(0);
class Kurs extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        session_write_close();
    }

    public function bank_bi(){

        $bank = "bi";
        $data = $this->kurs($bank);

        $showKurs = array(
            "USD",
            "CNH",
        );

        $html = "";

        $html .= "<section class='hero is-black is-small hide-scrollbar' id='ticker' style='overflow-y: hidden;'>";
        $html .= "<div line='".__LINE__."' class='hero-body'>";
        $html .= "<div class='container-fluid'>";
        $html .= "<div class='columns is-mobile'>";

        $html .= "<div class='column tick'>";
        $html .= "<div class='text-bold text-lg'>";
        $html .= "<div class='row '>";
        $html .= "<span class='col-md-12 text-bold text-center'>BTC/USDT</span>";
        $html .= "</div>";

        $html .= "<div class='row'>";
        $html .= "<span style='font-size: 0.7em;' class='col-md-6 text-bold text-green text-left text-uppercase'>beli</span>";
        $html .= "<span style='font-size: 0.7em;' class='col-md-6 text-bold text-red text-left text-uppercase'>jual</span>";
        $html .= "</div>";

        $html .= "<div class='row'>";
        $html .= "<span class='col-md-6 text-bold text-green text-center text-uppercase'>12.521.02</span>";
        $html .= "<span class='col-md-6 text-bold text-red text-center text-uppercase'>12.321.02</span>";
        $html .= "</div>";

        $html .= "</div>";
        $html .= "</div>";

        //===========END==========

        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</section>";

        echo $html;

    }

    public function index(){

        $bank = "bi";
        $data = $this->kurs($bank);

        $showKurs = array(
            "USD",
            "SGD",
            "AUD",
            "CNY",
            "CAD",
            "EUR",
            "GBP",
        );


        $aliasKurs = array(
            "USD"=>"US Dollar",
            "SGD"=>"Dollar Singapura",
            "AUD"=>"Dollar Australia",
            "CNY"=>"Chinese Yuan Renminbi",
            "CAD"=>"Canada Dollar",
            "EUR"=>"EURO",
            "GBP"=>"British Pound",
        );


        $html = "";
        if($data['data']["status"]=="online"){

            $html .= "<style type='text/css'>
                    .bounce {
                        height: 50px;
                        overflow: hidden;
                        position: relative;
                        background: #fefefe;
                        color: #333;
                        border: 1px solid #4a4a4a;
                    }
                    
                    .bounce p {
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        margin: 0;
                        line-height: 50px;
                        text-align: center;
                        -moz-transform: translateX(50%);
                        -webkit-transform: translateX(50%);
                        transform: translateX(50%);
                        -moz-animation: bouncing-text 5s linear infinite alternate;
                        -webkit-animation: bouncing-text 5s linear infinite alternate;
                        animation: bouncing-text 10s linear infinite alternate;
                    }
                    
                    @-moz-keyframes bouncing-text {
                        0% {
                            -moz-transform: translateX(50%);
                        }
                        100% {
                            -moz-transform: translateX(-50%);
                        }
                    }
                    
                    @-webkit-keyframes bouncing-text {
                        0% {
                            -webkit-transform: translateX(50%);
                        }
                        100% {
                            -webkit-transform: translateX(-50%);
                        }
                    }
                    
                    @keyframes bouncing-text {
                        0% {
                            -moz-transform: translateX(50%);
                            -webkit-transform: translateX(50%);
                            transform: translateX(50%);
                        }
                        100% {
                            -moz-transform: translateX(-50%);
                            -webkit-transform: translateX(-50%);
                            transform: translateX(-50%);
                        }
                    }
                </style>";

            $html .= "<section class='hero is-black is-small hide-scrollbar' id='ticker' style='overflow-y: hidden;'>";
            $html .= "<div line='".__LINE__."' class='hero-body'>";
            $html .= "<div class='container-fluid'>";
            $html .= "<div class='row'>";

            $html .= "<div class='col-md-12 no-padding'>";
            $html .= "<div class='columns is-mobile'>";
            $html .= "<marquee width='99%' behavior='scroll' direction='left' scrollamount='1' onmouseover='this.stop()' onmouseout='this.start()'>";
            $html .= "<span class='text-bold'>Legend: <span class='text-green'>HIJAU= BELI</span> | <span class='text-red'>MERAH= JUAL</span> </span> &nbsp;&nbsp;&nbsp;";

            $arrData = $data['data']["kurs"];
            foreach($arrData as $kode => $dtKurs){
                if(in_array($kode, $showKurs)){
                    $jual_ = number_format($dtKurs['jual'],2);
                    $beli_ = number_format($dtKurs['beli'],2);
                    $html .= "
                    <span class='text-bold'>IDR/$kode:&nbsp;</span>
                    <span class='text-bold text-green'>$beli_</span>
                    <span class='text-bold text-red'>$jual_</span>&nbsp; || &nbsp;";
                }
            }
            $html .= "<span class='text-bold'>DATA KURS PER TANGGAL ".$data['date']."&nbsp;&nbsp; || &nbsp;&nbsp;&nbsp;sumber: <a target='_BLANK' href='".$data['data']['source']."'>Bank Indonesia</a></span>&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; ";
            $html .= "</marquee>";

            $html .= "</div>";
            $html .= "</div>";

            $html .= "</div>";
            $html .= "</div>";
            $html .= "</div>";
            $html .= "</section>";
        }

        echo "<script>var isiData = '".json_encode($data)."'; console.log(isiData);</script>";

        echo $html;
    }

    public function index_bouncing(){

        $bank = "bi";
        $data = $this->kurs($bank);

        $showKurs = array(
            "USD",
            "SGD",
            "AUD",
            "CNY",
            "CAD",
            "EUR",
            "GBP",
        );

        $aliasKurs = array(
            "USD"=>"US Dollar",
            "SGD"=>"Dollar Singapura",
            "AUD"=>"Dollar Australia",
            "CNY"=>"Chinese Yuan Renminbi",
            "CAD"=>"Canada Dollar",
            "EUR"=>"EURO",
            "GBP"=>"British Pound",
        );



        $html = "";
        if($data['data']["status"]=="online"){

            $html .= "<style type='text/css'>
                    .bounce {
                        height: 25px;
                        // overflow: hidden;
                        position: relative;
                        // background: #fefefe;
                        // color: #333;
                        border: 1px solid #4a4a4a;
                    }
                    
                    .bounce p {
                        position: absolute;
                        width: 100%;
                        height: 100%;
                        margin: 0;
                        line-height: 20px;
                        text-align: center;
                        -moz-transform: translateX(50%);
                        -webkit-transform: translateX(50%);
                        transform: translateX(50%);
                        /*---kecepatan bouncing kanan kiri---*/
                        -moz-animation: bouncing-text 20s linear infinite alternate;
                        -webkit-animation: bouncing-text 20s linear infinite alternate;
                        animation: bouncing-text 20s linear infinite alternate;
                        white-space: nowrap;
                    }
                    .bounce span {
                        white-space: nowrap;
                    }
                    
                    /*---jarak bouncing kanan(plus) dan kiri(min)---*/
                    @-moz-keyframes bouncing-text {
                        0% {
                            -moz-transform: translateX(1%);
                        }
                        100% {
                            -moz-transform: translateX(-10%);
                        }
                    }
                    
                    @-webkit-keyframes bouncing-text {
                        0% {
                            -webkit-transform: translateX(1%);
                        }
                        100% {
                            -webkit-transform: translateX(-10%);
                        }
                    }
                    
                    @keyframes bouncing-text {
                        0% {
                            -moz-transform: translateX(1%);
                            -webkit-transform: translateX(1%);
                            transform: translateX(5%);
                        }
                        100% {
                            -moz-transform: translateX(-10%);
                            -webkit-transform: translateX(-10%);
                            transform: translateX(-10%);
                        }
                    }
                </style>";
            // arrPrint($data);

            $html .= "<span style=\"font-weight: bold; padding: 5px; background: #1a1a1a; color: white; border-radius: 4px;\">
                      Legend: 
                      <span style=\"color: #00ff00;\">HIJAU=BUY</span> | 
                      <span style=\"color: #ff4d4d;\">MERAH=SELL</span> | 
                      <span style=\"color: #ffff00;\">KUNING=MIDDLE</span>
                    </span>
                    ";

            $html .= "<section class='hero is-black is-small hide-scrollbar' id='ticker' style='overflow-y: hidden;'>";
            $html .= "<div line='".__LINE__."' class='hero-body'>";
            $html .= "<div class='container-fluid'>";
            $html .= "<div class='row'>";

            $html .= "<div style='sfont-family: math;' class='bounce'>";
            $html .= "<p>";
            $html_txt = "";
            $arrData = $data['data']["kurs"];
            foreach($arrData as $kode => $dtKurs){
                if(in_array($kode, $showKurs)){
                    $jual_ = number_format($dtKurs['jual'],2);
                    $beli_ = number_format($dtKurs['beli'],2);
                    $tengah = number_format((($dtKurs['jual']+$dtKurs['beli'])/2),2);
                    $html_txt .= "
                        <span class='text-bold'>IDR/$kode:&nbsp;</span>
                        <span class='text-bold text-green'>$beli_</span>&nbsp;
                        <span class='text-bold text-red'>$jual_</span>&nbsp;
                        <span style='font-size: 16px;color: #ffff00!important;' class='text-bold'>$tengah</span>&nbsp;||&nbsp;";
                }
            }

            $time_last = date("H:i:s");

            $html_txt .= "";
            $html .= $html_txt;
            $html .= "</p>";

            $html .= "</div>";

            $html .= "</div>";
            $html .= "</div>";
            $html .= "</div>";



            $html .= "</section>";
            $html .= "<div class='text-center'><b>TERAKHIR UPDATE KURS:</b>&nbsp;&nbsp;".$data['date']."&nbsp;&nbsp;".$time_last."&nbsp;&nbsp;<span class='btn-perbaharui text-link'><u>PERBAHARUI</u></span><span class='text-bold'>&nbsp;&nbsp;&nbsp;sumber:&nbsp;<a target='_BLANK' href='".$data['data']['source']."'>Bank&nbsp;Indonesia</a></span></div>";

            $dataToJson = json_encode($arrData, true);
            $html .= "<script>\n
                        top.$('.btn-perbaharui').off();\n
                        top.$('.btn-perbaharui').on('click', function(){
                            console.log('dataKurs');
                            top.kurs_bank_indonesia();\n
                            top.$(this).html(\"<i class='fa fa-refresh fa-spin'></i> Reloading...\");
                        });\n
                           
                        var dataKurs = $dataToJson;
                            console.log(dataKurs);
                     </script>";

        }


        echo $html;
    }

    public function ayoCurl($bank, $url, $td, $field1, $field2)
    {

        $user_agent = "Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1";

        $url = $url;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");

        // jika website yd di curl sedang offline
        if( !$content = curl_exec($ch) ){
            $arrai = array(
                "bank" => $bank,
                "status" => "offline",
                "url" => "$url",
                "kurs" => array()
            );
            return $arrai;
        }

        // ternyata website yd di curl sedang online
        else{

            curl_close($ch);
            $dom = new DOMDocument;
            $dom->loadHTML($content);

            $rows = $dom->getElementsByTagName('tr');
            $counter = 0;

            $rows = array();
            $arrKurs = array();
            $arrKurs2 = array();
            $listPair = array();
            foreach ($dom->getElementsByTagName('tr') as $tr) {
                $cells = array();
                foreach ($tr->getElementsByTagName('td') as $r) {
                    $cells[] = $r->nodeValue;
                }
                $rows[] = $cells;

                if(isset($cells[0])){
                    $listPair[trim($cells[0])] = $cells;
                }

            }

            foreach($listPair as $pair => $pairData){
                $jual = $pairData[2];
                $beli = $pairData[3];

                if(substr($jual, -3) == ".00"){
                    $jual = str_replace(".00", "", $jual);
                }
                else if(substr($jual, -3) == ",00"){
                    $jual = str_replace(",00", "", $jual);
                }

                if(substr($beli, -3) == ".00"){
                    $beli = str_replace(".00", "", $beli);
                }
                else if(substr($beli, -3) == ",00"){
                    $beli = str_replace(",00", "", $beli);
                }

                $search  = array(".",",");
                $replace = array("",".");

                $jual = str_replace($search, $replace, $jual);
                $beli = str_replace($search, $replace, $beli);

                $arrKurs2[$pair] = array(
                    "mata_uang" => $pair,
                    "jual" => $jual,
                    "beli" => $beli
                );
            }

            // $listKurs = range(27,51,1);
            // $listKurs = range(28,53,1); //UI BI GESER KESINI
            // foreach($listKurs as $k => $td){
            //     $kurs_nama = preg_replace('/\s+/', '', $rows[$td][0]);
            //     $jual = preg_replace('/\s+/', '', $rows[$td][$field1]);
            //     $beli = preg_replace('/\s+/', '', $rows[$td][$field2]);
            //     if(substr($jual, -3) == ".00"){
            //         $jual = str_replace(".00", "", $jual);
            //     }
            //     else if(substr($jual, -3) == ",00"){
            //         $jual = str_replace(",00", "", $jual);
            //     }
            //     if(substr($beli, -3) == ".00"){
            //         $beli = str_replace(".00", "", $beli);
            //     }
            //     else if(substr($beli, -3) == ",00"){
            //         $beli = str_replace(",00", "", $beli);
            //     }
            //     $search  = array(".",",");
            //     $replace = array("",".");
            //     $jual = str_replace($search, $replace, $jual);
            //     $beli = str_replace($search, $replace, $beli);
            //     $arrKurs[$kurs_nama] = array(
            //         "mata_uang" => $kurs_nama,
            //         "jual" => $jual*1,
            //         "beli" => $beli*1
            //     );
            // }

            $arrai = array(
                "bank" => $bank,
                "status" => "online",
                "source" => $url,
                "kurs" => $arrKurs2
            );

            return $arrai;
        }
    }
//
    public function curl(){

        $data = array();
//        $data[] = ["bank" => "bi", "url" => "http://www.bi.go.id/id/moneter/informasi-kurs/transaksi-bi/Default.aspx",	"td" => "31", "field1" => "2", "field2" => "3"];

        $data[] = array(
            "bank" => "bi",
            "url" => "https://www.bi.go.id/id/statistik/informasi-kurs/transaksi-bi/default.aspx",
            "td" => "50",
            "field1" => "2",
            "field2" => "3"
        );

//        $data[] = ["bank" => "bi", "url" => "https://www.bi.go.id/biwebservice/wskursbi.asmx/getSubKursLokal3?mts=USD&startdate=2022-10-18&enddate=2022-10-18",	"td" => "31", "field1" => "2", "field2" => "3"];
//        $data[] = ["bank" => "bca", "url" => "http://www.bca.co.id/id/Individu/Sarana/Kurs-dan-Suku-Bunga/Kurs-dan-Kalkulator",	"td" => "2", "field1" => "1", "field2" => "2"];
//        $data[] = ["bank" => "permata", "url" => "https://www.permatabank.com/kurs/", "td" => "1", "field1" => "4", "field2" => "3"];
//        $data[] = ["bank" => "bni", "url" => "http://www.bni.co.id/informasivalas.aspx", "td" => "14", "field1" => "1", "field2" => "2"];
//        $data[] = ["bank" => "bri", "url" => "http://www.bri.co.id/rates", "td" => "22", "field1" => "2", "field2" => "1"];
//        $data[] = ["bank" => "bukopin", "url" => "http://www.bukopin.co.id/", "td" => "3", "field1" => "2", "field2" => "1"];
//        $data[] = ["bank" => "danamon", "url" => "http://www.danamon.co.id/Home/AboutDanamon/FXRates/tabid/272/language/id-ID/Default.aspx", "td" => "1", "field1" => "2", "field2" => "1"];
//        $data[] = ["bank" => "mandiri", "url" => "http://www.bankmandiri.co.id", "td" => "3", "field1" => "3", "field2" => "2"];
//        $data[] = ["bank" => "ekonomi", "url" => "https://www.bankekonomi.co.id/1/2/home/kurs-mata-uang", "td" => "2", "field1" => "2", "field2" => "1"];
//        $data[] = ["bank" => "mega", "url" => "https://www.bankmega.com/treasury.php", "td" => "3", "field1" => "2", "field2" => "1"];
//        $data[] = ["bank" => "mybank", "url" => "http://www.maybank.co.id/kurs/pages/kurs.aspx", "td" => "11", "field1" => "2", "field2" => "1"];
//        $data[] = ["bank" => "bankjatim", "url" => "http://www.bankjatim.co.id/id/informasi/informasi-lainnya/kurs", "td" => "3", "field1" => "3", "field2" => "2"];
//        $data[] = ["bank" => "btn", "url" => "http://www.btn.co.id/id/content/BTN-Info/Info/Kurs-Valuta-Asing", "td" => "1", "field1" => "1", "field2" => "2"];
//        $data[] = ["bank" => "bjb", "url" => "http://www.bankbjb.co.id/id/corporate-website/rate-dan-biaya/kurs-valas.html", "td" => "8", "field1" => "2", "field2" => "1"];
//        $data[] = ["bank" => "bankmuamalat", "url" => "http://www.bankmuamalat.co.id/kurs", "td" => "2", "field1" => "3", "field2" => "4"];
//        $data[] = ["bank" => "banksinarmas", "url" => "http://banksinarmas.com/id/i.php?id=charges-atm", "td" => "11", "field1" => "2", "field2" => "1"];
//        $data[] = ["bank" => "bankaltim", "url" => "http://www.bankaltim.co.id/kurs", "td" => "7", "field1" => "1", "field2" => "2"];

        /* Sobat bisa menambahkan lagi daftar bank yang akan di cURL ^^
        $data[] = ["bank" => "nama bank", "url" => "url bank yang akan di cURL", "td" => "array td ke", "field1" => "array field ke di dalam td (jual)", "field2" => "array field ke di dalam td (beli)"];
        */

        return $data;

    }
//
    function bank(){

        $curl = $this->curl();

        $data = array();

        foreach($curl as $cr){
            $url_kurs = 'http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI].'?bank='.$cr['bank'];
            $data['data'][] = array(
                "bank" => $cr['bank'],
                "url_kurs" => $url_kurs
            );
        }

        $data['data'][] = array(
            "bank" => "all",
            "url_kurs" => 'http://'.$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI].'?bank=all'
        );

        return $data;

    }
//
    public function kurs($bank = ""){

        $curl = $this->curl();

        $data = array(
            "date" => date("Y-m-d")
        );

        foreach($curl as $cr){
            if($cr['bank'] == $bank){
                $data['data'] = $this->ayoCurl($cr['bank'], $cr['url'], $cr['td'], $cr['field1'], $cr['field2']);
            }
            else if($bank == "all"){
                $data['data'] = $this->ayoCurl($cr['bank'], $cr['url'], $cr['td'], $cr['field1'], $cr['field2']);
            }
        }

        return $data;

    }

}