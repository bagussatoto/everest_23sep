<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zip extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->helper('url');

        // Load zip library
        $this->load->library('zip');

    }

    public function index()
    {
        // Load view
        $this->load->view('zip_view');
    }

    // Create zip
    public function createzip()
    {
        $segmen_3 = $this->uri->segment(3);
        // cekBiru($segmen_3 . " " .strlen($segmen_3));
        // Read file from path
        if ($this->input->post('but_createzip1') != NULL) {

            // File path
            $filepath1 = FCPATH . '/public/image1.jpg';
            $filepath2 = FCPATH . '/public/document/users.csv';

            // Add file
            $this->zip->read_file($filepath1);
            $this->zip->read_file($filepath2);

            // Download
            $FCPATH = FCPATH;
            $exlp_FCPATH = explode("/", $FCPATH);
            $folder_app = $exlp_FCPATH[4];
            $filename = $folder_app . "-" . dtimeNow('Y-m-d-H-i');
            // $filename = "backup.zip";
            $this->zip->download($filename);

        }

        // Read files from directory
        if (($this->input->post('but_createzip2') != NULL) || (strlen($segmen_3) > 0)) {

            $FCPATH = FCPATH;
            $exlp_FCPATH = explode("/", $FCPATH);
            $folder_app = $exlp_FCPATH[3];
             // cekHijau($FCPATH);
             // arrPrint($exlp_FCPATH[3]);
            // File name
            $filename = $folder_app . "-" . dtimeNow('Y-m-d-H-i');

            // Directory path (uploads directory stored in project root)
            // $path = 'public';
            // $path = 'application';
            // $path = dirname(__FILE__) . '/../../';
            $path = "./";
            //             cekHijau($path);
             // matiHere(__LINE__);

            // penambahan file htaccess
            $htaccess = "RewriteEngine On
                        RewriteCond %{REQUEST_FILENAME} !-f
                        RewriteCond %{REQUEST_FILENAME} !-d
                        RewriteRule ^(.*)$ index.php/$1 [L]
                                                
                        # yg dipakai yg ini
                        RewriteCond %{HTTP_HOST} ^www\.(.*)$ [NC]
                        RewriteRule ^(.*)$ https://%1/$1 [R=301,L]
                        RewriteCond %{HTTPS} !on
                        RewriteRule ^(.*) https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]
                        
                        Header set Access-Control-Allow-Origin \"https://cdn.mayagrahakencana.com\"
                        Header set Access-Control-Allow-Origin \"https://demo.mayagrahakencana.com\"
                        ";
            $this->zip->add_data(".htaccess", $htaccess);
            // Add directory to zip
            $this->zip->read_dir($path);

            // Save the zip file to archivefiles directory
            $this->zip->archive(FCPATH . '/archivefiles/' . $filename);

            // Download
            $this->zip->download($filename);

        }

        // Load view
        $this->load->view('zip_view');
    }

}