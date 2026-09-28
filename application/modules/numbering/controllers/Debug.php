<?php


class Debug extends MX_Controller
{
    public function index()
    {
        $var = "";
        echo "<div>";
        if(isset($_SESSION['data'])){

            arrPrint($_SESSION['data']);
        }
        else{
            echo "<h1>tidak ada session data</h1>";
        }
        echo "</div>";
    }

    public function viewdt(){
        return $this->index();
    }
}