<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/10/2018
 * Time: 3:16 PM
 */
class DataIndex extends CI_Controller
{
    private $configPath;

    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            redirect(base_url() . "Login");
        }
        $this->load->config('heDataBehaviour');
        $this->configPath = $this->config->item('heDataBehaviour');
    }

    public function index()
    {
//        print_r($this->session->login);die();
        if (sizeof($this->configPath) > 0) {
            $availMenus = array();
            $availNewMenus = array();
            $loginType = $this->session->login['jenis'];
            foreach ($this->configPath as $mdlName => $mSpec) {
                if (isset($mSpec['viewers'])) {
                    if (sizeof($mSpec['viewers']) > 0) {
                        if (in_array($loginType, $mSpec['viewers'])) {
                            $availMenus[$mdlName] = str_replace("Mdl", "", $mdlName);
                        }
                    }
                    if (sizeof($mSpec['creators']) > 0) {
                        if (in_array($loginType, $mSpec['creators'])) {
                            $availNewMenus[$mdlName] = str_replace("Mdl", "", $mdlName);
                        }
                    }
                }
            }

        }
        else {
            die("No data config found!");
        }

        $data = array(
            "mode" => $this->uri->segment(2),
            "availMenus" => $availMenus,
            "availNewMenus" => $availNewMenus,
        );
        $this->load->view("data_index", $data);
    }
}