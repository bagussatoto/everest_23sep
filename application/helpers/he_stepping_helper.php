<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 4/24/2019
 * Time: 2:08 PM
 */
function heCountPreProcs($tr, $step)
{
    $result = array(
        "reversable" => 0,
        "irreversable" => 0,
    );
    $ci =& get_instance();
    $mPreProcs = isset($ci->config->item('heTransaksi_core')[$tr]['preProcessor'][$step]['master']) ? $ci->config->item('heTransaksi_core')[$tr]['preProcessor'][$step]['master'] : array();
    $cPreProcs = isset($ci->config->item('heTransaksi_core')[$tr]['preProcessor'][$step]['detail']) ? $ci->config->item('heTransaksi_core')[$tr]['preProcessor'][$step]['detail'] : array();

    if (sizeof($mPreProcs) > 0) {
        foreach ($mPreProcs as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    if (sizeof($cPreProcs) > 0) {
        foreach ($cPreProcs as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }

    return $result;
}

function heCountPostProcs($tr, $step)
{
    $result = array(
        "reversable" => 0,
        "irreversable" => 0,
    );
    $ci =& get_instance();
    $mPostProcs = isset($ci->config->item('heTransaksi_core')[$tr]['postProcessor'][$step]['master']) ? $ci->config->item('heTransaksi_core')[$tr]['postProcessor'][$step]['master'] : array();
    $cPostProcs = isset($ci->config->item('heTransaksi_core')[$tr]['postProcessor'][$step]['detail']) ? $ci->config->item('heTransaksi_core')[$tr]['postProcessor'][$step]['detail'] : array();
    if (sizeof($mPostProcs) > 0) {
        foreach ($mPostProcs as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    if (sizeof($cPostProcs) > 0) {
        foreach ($cPostProcs as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    return $result;
}

function heCountComponents($tr, $step)
{
    $result = array(
        "reversable" => 0,
        "irreversable" => 0,
    );
    $ci =& get_instance();
    $mComponents = isset($ci->config->item('heTransaksi_core')[$tr]['components'][$step]['master']) ? $ci->config->item('heTransaksi_core')[$tr]['components'][$step]['master'] : array();
    $cComponents = isset($ci->config->item('heTransaksi_core')[$tr]['components'][$step]['detail']) ? $ci->config->item('heTransaksi_core')[$tr]['components'][$step]['detail'] : array();
    if (sizeof($mComponents) > 0) {
        foreach ($mComponents as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    if (sizeof($cComponents) > 0) {
        foreach ($cComponents as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    return $result;
}

function heJournalExists($tr, $step)
{
    $mComponents = isset($this->config->item('heTransaksi_core')[$tr]['components'][$step]['master']) ? $this->config->item('heTransaksi_core')[$tr]['components'][$step]['master'] : array();
    if (sizeof($mComponents) > 0) {
        foreach ($mComponents as $cSpec) {
            $comNames[] = $cSpec['comName'];
        }
        if (in_array("Jurnal", $comNames)) {
            return true;
        }
        else {
            return false;
        }
    }
    else {
        return false;
    }

}

//---getting original tCode in a connected tCode
//===contohnya, saat 585, fungsi ini menghasilkan 583 sebagai sumber awal tCode/jenisnya
function heGetOriginTCode($tCode)
{
    $ci =& get_instance();
    $uiConfigs = $ci->config->item('heTransaksi_ui');

    switch ($tCode){
        case "110":
        case "582":
            $revertException = true;
            break;
        default:
            $revertException = isset($ci->config->item('heTransaksi_ui')[$tCode]['revertException']) ? $ci->config->item('heTransaksi_ui')[$tCode]['revertException'] : false;
            break;
    }

    $result = null;
    if (sizeof($uiConfigs) > 0) {
        foreach ($uiConfigs as $j => $jSpec) {
            if (isset($jSpec['connectTo']) && $jSpec['connectTo'] == $tCode) {
                if ($revertException == true) {
                    $result = null;
                }
                else {
                    $result = $j;
                }
                break;
            }
        }
    }
    return $result;
}

function heGetOriginTCode_orig($tCode)
{
    $ci =& get_instance();
    $uiConfigs = $ci->config->item('heTransaksi_ui');

    $result = null;
    if (sizeof($uiConfigs) > 0) {
        foreach ($uiConfigs as $j => $jSpec) {

            if (isset($jSpec['connectTo']) && $jSpec['connectTo'] == $tCode) {
                $result = $j;

                break;
            }
        }
    }
    return $result;
}

// untuk mendapatkan code ConnectTo (kode transaksi ini konek ke mana...), begitu...
function heGetOriginConnectTCode($tCode)
{
    $ci =& get_instance();
    $uiConfigs = $ci->config->item('heTransaksi_ui');

    $result = null;
    if (sizeof($uiConfigs) > 0) {
        foreach ($uiConfigs as $j => $jSpec) {
            if ($j == $tCode) {

                if (isset($jSpec['connectTo'])) {
                    $result = $jSpec['connectTo'];
                    break;
                }
            }
        }
    }
    return $result;
}




//-VERSI MODUL----------------------------------------------------------
function heCountPreProcs_he_stepping($tr, $step, $configCore)
{
    $result = array(
        "reversable" => 0,
        "irreversable" => 0,
    );
    $ci =& get_instance();
    $mPreProcs = isset($configCore[$tr]['preProcessor'][$step]['master']) ? $configCore[$tr]['preProcessor'][$step]['master'] : array();
    $cPreProcs = isset($configCore[$tr]['preProcessor'][$step]['detail']) ? $configCore[$tr]['preProcessor'][$step]['detail'] : array();

    if (sizeof($mPreProcs) > 0) {
        foreach ($mPreProcs as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    if (sizeof($cPreProcs) > 0) {
        foreach ($cPreProcs as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }

    return $result;
}

function heCountPostProcs_he_stepping($tr, $step, $configCore)
{
    $result = array(
        "reversable" => 0,
        "irreversable" => 0,
    );
    $ci =& get_instance();
    $mPostProcs = isset($configCore[$tr]['postProcessor'][$step]['master']) ? $configCore[$tr]['postProcessor'][$step]['master'] : array();
    $cPostProcs = isset($configCore[$tr]['postProcessor'][$step]['detail']) ? $configCore[$tr]['postProcessor'][$step]['detail'] : array();
    if (sizeof($mPostProcs) > 0) {
        foreach ($mPostProcs as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    if (sizeof($cPostProcs) > 0) {
        foreach ($cPostProcs as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    return $result;
}

function heCountComponents_he_stepping($tr, $step, $configCore)
{
    $result = array(
        "reversable" => 0,
        "irreversable" => 0,
    );
    $ci =& get_instance();
    $mComponents = isset($configCore[$tr]['components'][$step]['master']) ? $configCore[$tr]['components'][$step]['master'] : array();
    $cComponents = isset($configCore[$tr]['components'][$step]['detail']) ? $configCore[$tr]['components'][$step]['detail'] : array();
    if (sizeof($mComponents) > 0) {
        foreach ($mComponents as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    if (sizeof($cComponents) > 0) {
        foreach ($cComponents as $mSpec) {
            if (isset($mSpec['reversable']) && $mSpec['reversable'] == true) {
                $result['reversable']++;
            }
            else {
                $result['irreversable']++;
            }
        }
    }
    return $result;
}

function heJournalExists_he_stepping($tr, $step, $configCore)
{
    $mComponents = isset($configCore[$tr]['components'][$step]['master']) ? $configCore[$tr]['components'][$step]['master'] : array();
    if (sizeof($mComponents) > 0) {
        foreach ($mComponents as $cSpec) {
            $comNames[] = $cSpec['comName'];
        }
        if (in_array("Jurnal", $comNames)) {
            return true;
        }
        else {
            return false;
        }
    }
    else {
        return false;
    }

}
//---getting original tCode in a connected tCode
//===contohnya, saat 585, fungsi ini menghasilkan 583 sebagai sumber awal tCode/jenisnya
function heGetOriginTCode_he_stepping($tCode, $configUi)
{
    $ci =& get_instance();
    $uiConfigs = $configUi;

    switch ($tCode){
        case "110":
        case "582":
            $revertException = true;
            break;
        default:
            $revertException = isset($uiConfigs[$tCode]['revertException']) ? $uiConfigs[$tCode]['revertException'] : false;
            break;
    }

    $result = null;
    if (sizeof($uiConfigs) > 0) {
        foreach ($uiConfigs as $j => $jSpec) {
            if (isset($jSpec['connectTo']) && $jSpec['connectTo'] == $tCode) {
                if ($revertException == true) {
                    $result = null;
                }
                else {
                    $result = $j;
                }
                break;
            }
        }
    }
    return $result;
}

function heGetOriginTCode_orig_he_stepping($tCode, $configUi)
{
    $ci =& get_instance();
    $uiConfigs = $configUi;

    $result = null;
    if (sizeof($uiConfigs) > 0) {
        foreach ($uiConfigs as $j => $jSpec) {

            if (isset($jSpec['connectTo']) && $jSpec['connectTo'] == $tCode) {
                $result = $j;

                break;
            }
        }
    }
    return $result;
}

// untuk mendapatkan code ConnectTo (kode transaksi ini konek ke mana...), begitu...
function heGetOriginConnectTCode_he_stepping($tCode, $configUi)
{
    $ci =& get_instance();
    $uiConfigs = $configUi;

    $result = null;
    if (sizeof($uiConfigs) > 0) {
        foreach ($uiConfigs as $j => $jSpec) {
            if ($j == $tCode) {

                if (isset($jSpec['connectTo'])) {
                    $result = $jSpec['connectTo'];
                    break;
                }
            }
        }
    }
    return $result;
}
