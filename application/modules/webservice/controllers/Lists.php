<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(0);
ini_set('display_errors', 0);

class Lists extends CI_Controller
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
        $services = array(
          "all" => array(
              "url" => "eusvc/Products/seeItemAll",
              "deskripsi" => "seluruh data",
          ),
          "limit" => array(
              "url" => "eusvc/Products/seeItemAll/limit/10",
              "deskripsi" => "data dengan limit",
          ),
          "detil" => array(
              "url" => "eusvc/Products/seeItemAll/id/55",
              "deskripsi" => "detil data id tertentu",
          ),
          "search" => array(
              "url" => "eusvc/Products/seeItemAll/search/daikin",
              "deskripsi" => "keyword tertentu",
          ),
        );

        $var = "";
        $var .= "<style>
    .wadah{
        padding: 20px;
    }
    .service-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 10px;
        list-style: none;
        padding: 0;
    }
    .service-container li {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
        background: #f9f9f9;
        position: relative;
    }
    #searchInput {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .copy-btn {
        padding: 5px 10px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 3px;
        margin-top: 5px;
    }
    .copy-btn.copied {
        background-color: #28a745;
    }
</style>";

        $server = $_SERVER;
        $HTTP_HOST = $server["HTTP_HOST"];

        $var .= "<div class='wadah'>";
        $var .= "<h2 style='padding: 0;margin: 0;'>$HTTP_HOST</h2>";
        $var .= "<input type='text' id='searchInput' placeholder='Cari layanan...' onkeyup='filterServices()'>";

        $var .= "<ul class='service-container' id='serviceList'>";
        foreach ($services as $serviceParams) {
            $url = $serviceParams["url"];
            $deskripsi = $serviceParams["deskripsi"];
            $link = base_url() . "$url";

            $var .= "<li class='service-item'>";
            $var .= "<a href='$link' target='_blank'><b>$url</b></a>";
            $var .= "<div>$deskripsi</div>";
            $var .= "<button class='copy-btn' onclick='copyToClipboard(\"$link\", this)'>Copy</button>";
            $var .= "</li>";
        }
        $var .= "</ul>";
        $var .= "</div>";

        $var .= "<script>
    function filterServices() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let items = document.querySelectorAll('.service-item');
        
        items.forEach(item => {
            let text = item.innerText.toLowerCase();
            if (text.includes(input)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    function copyToClipboard(text, button) {
        navigator.clipboard.writeText(text).then(() => {
            button.classList.add('copied');
            button.innerText = 'Copied!';
            setTimeout(() => {
                button.classList.remove('copied');
                button.innerText = 'Copy';
            }, 2000);
        });
    }
</script>";

        echo $var;
    }

}