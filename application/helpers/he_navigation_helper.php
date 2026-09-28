<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/9/2018
 * Time: 8:17 PM
 */

function translateNavigation($param1, $param2, $param3)
{

    $config = config_item('navigation');

    if (isset($config[$param1]["$param2/$param3"])) {
        $tmpTarget = base_url() . $config[$param1]["$param2/$param3"];
    }
    else {
        if (isset($config[$param1][$param2])) {
            $tmpTarget = base_url() . $config[$param1][$param2];
        }
        else {
            $tmpTarget = base_url() . "Welcome/index/";
        }
    }


    return $tmpTarget;
}


function callBackNav()
{
    $CI =& get_instance();

    $CI->load->helper('url');

    $ctrlName = $CI->uri->segment(1);
    $replacers = array(
        "{segment3}" => $CI->uri->segment(3),
        "{segment4}" => $CI->uri->segment(4),
        "{segment5}" => $CI->uri->segment(5),
    );

    $param3 = $CI->uri->segment(3);
    foreach ($replacers as $from => $to) {
        $param3 = str_replace($to, $from, $param3);
    }

    $tmpTarget = translateNavigation(get_class($CI), $CI->uri->segment(2), $param3);
    foreach ($replacers as $from => $to) {
        $tmpTarget = str_replace($from, $to, $tmpTarget);
    }
    return $tmpTarget;
}