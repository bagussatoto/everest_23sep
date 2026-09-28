<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 5/6/2019
 * Time: 8:39 PM
 */
class Menu extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if (!isset($this->session->login['id'])) {
        //     gotoLogin();
        // }
    }

    public function test(){
        callMenuTopJson();
callMenuTop();
    }



}