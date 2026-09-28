<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 20/09/2019
 * Time: 17:55
 */

function replaceSession()
{
    $ci = &get_instance();
    $member = $ci->session->login['membership'];

    if (in_array("c_special", $member)) {
        $array = array();
        return $array;
    }
    else {
        $array = array(
            "cabang_id" => $ci->session->login['cabang_id'],
            "gudang_id" => $ci->session->login['gudang_id'],
        );
        return $array;
    }

}

function selectedTransactionSession()
{
    $ci = &get_instance();
    $membership = $ci->session->login['membership'];
    if (in_array("c_special", $membership)) {
        return true;
    }
    else {
        return false;
    }
}