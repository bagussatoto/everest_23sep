<?php

/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 14/04/18
 * Time: 11:19
 */
class Form extends CI_Controller
{

    public function index()
    {
        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('myform');
        } else {
            $this->load->view('formsuccess');
        }
    }
}