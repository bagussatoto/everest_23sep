<?php


class PopupUnblocker extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

//    public function _remap($method)
//    {
//        if ($method == 'some_method')
//        {
//            $this->$method();
//        }
//        else
//        {
//            $this->default_method();
//        }
//    }

    public function index(){

    }

    public function checkPopUp(){

    echo "
        <script>
            window.opener.$('#uid').attr('readonly', false);
            window.opener.$('input[name=password]').attr('readonly', false);
            window.close();
        </script>
    ";

    }
}