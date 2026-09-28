<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 12/3/2018
 * Time: 12:32 PM
 */

function makeValue($value, $sourceHitung, $sourceRaw, $static = 0)
{
//    arrPrint($sourceHitung);
//    arrPrint($sourceRaw);


// START OF COMPLETE REPEATED LOGIC
    $ci =& get_instance();
    $ci->load->library("FieldCalculator");
    $ci->load->helper("he_angka");
    $ci->load->helper("he_finansial");
    $cal = new FieldCalculator();
// END OF COMPLETE REPEATED LOGIC

    if (substr($value, 0, 1) == ".") {
//        cekKuning("APA ADANYA");

        $realCol = ltrim($value, ".");
        $realValue = $realCol;
    }
    else {
        $tmpEx = $cal->multiExplode($value);
//        arrPrint($tmpEx);
        if (sizeof($tmpEx) > 1) {//===pakai perhitungan
            $newSrc = $value;
//            cekmerah($value);
            foreach ($tmpEx as $key2 => $val2) {
//                cekBiru("$key2 => $val2 PAKAI PERHITUNGAN");

                if (strlen($val2) > 0) {
//cekHere("masuk disini");
//                     arrPrintKuning($sourceHitung[$val2]);
                    $newValues = isset($sourceHitung[$val2]) && $sourceHitung[$val2] != null ? $sourceHitung[$val2] : 0;
//                    cekHitam($newValues);
                    // if(!isset($sourceHitung[$val2]))
                    if (isset($sourceHitung[$val2])) {

//                        cekBiru("$key2 => $val2 AMBIL DARI SOURCE");
//                        cekBiru($sourceHitung[$val2]);
                        $newSrc = str_replace($val2, $newValues, $newSrc);
//                        cekOrange("$val2 :: BAWAH :: $newSrc ::" . __LINE__);
                    }
                    else {
//                        cekOrange("$key2 => $val2 AMBIL DARI STATIC");
                        if (isset($val2) && $val2 > 0) {
                            $newSrc = str_replace($val2, $val2, $newSrc);
//                            cekOrange("$val2 :: ATAS");
                        }
                        else {
                            $newSrc = str_replace($val2, $static, $newSrc);
//                            cekOrange("$val2 :: BAWAH :: $newSrc");
                        }
                    }
                }

            }
            // cekUngu("yang akan dihitung:" . $newSrc);
// START OF COMPLETE REPEATED LOGIC
            $newSrc = str_replace("--", "+", $newSrc);
//            cekHijau("setelah replace: $newSrc");
            $realValue = $cal->calculate($newSrc);
            $realValue = reformatExponent($realValue);
            if (function_exists('fin_round') && is_numeric($realValue)) {
                $realValue = fin_round($realValue, 4);
            }
//            cekmerah("---> hasilnya:".$realValue);
// END OF COMPLETE REPEATED LOGIC
        }
        else {
//            cekUngu("BUKAN PERHITUNGAN [$value]");
//            arrPrint($sourceRaw);
            $realCol = $value;
            $realValue = isset($sourceRaw[$realCol]) ? ($sourceRaw[$realCol]) : $static;
        }
    }


    return $realValue;
}


function makeFilter($configParams, $srcArray, $object)
{
//    arrPrint($configParams);
    if (is_array($configParams) && sizeof($configParams) > 0) {
        foreach ($configParams as $filter) {
            $exFilter = explode("=", $filter);
//            arrPrint($exFilter);
            if (sizeof($exFilter) > 1) {
                if (substr($exFilter[1], 0, 1) == ".") {
                    $object->addFilter($exFilter[0] . "='" . ltrim($exFilter[1], ".") . "'");
                }
                elseif (substr($exFilter[1], 0, 1) == "@") {
                    $key = ltrim($exFilter[1], "@");
                    if (isset($srcArray[$key]) && is_array($srcArray[$key]) && count($srcArray[$key]) > 0) {
                        $vals = array_map(function($v){ return "'".addslashes($v)."'"; }, $srcArray[$key]);
                        $object->db->where_in($exFilter[0], $srcArray[$key]);
                    }
                    else {
                        $object->addFilter("1=0");
                    }
                }
                else {
                    if (isset($srcArray[$exFilter[1]])) {
                        $object->addFilter($exFilter[0] . "='" . $srcArray[$exFilter[1]] . "'");
                    }
                    else {
                        // $object->addFilter($exFilter[0] . "='none'");
                        /* ---------------------------------------------------------
                         * fileter diganti angka (-555) karena
                         * katika ketemu kolom tipe data integer, filter string akan dianggap 0
                         * ---------------------------------------------------------*/
                        $object->addFilter($exFilter[0] . "='-555'");
                    }
                }
            }

            else {
                $exFilter = explode("<>", $filter);
                if (sizeof($exFilter) > 1) {
                    if (substr($exFilter[1], 0, 1) == ".") {
                        $object->addFilter($exFilter[0] . "<>'" . ltrim($exFilter[1], ".") . "'");
                    }
                    else {
                        if (isset($srcArray[$exFilter[1]])) {
                            $object->addFilter($exFilter[0] . "<>'" . $srcArray[$exFilter[1]] . "'");
                        }
                        else {
                            $object->addFilter($exFilter[0] . "<>'-555'");//none
                        }
                    }
                }
                else {
                    $exFilter = explode(">", $filter);
                    if (sizeof($exFilter) > 1) {
                        if (substr($exFilter[1], 0, 1) == ".") {
                            $object->addFilter($exFilter[0] . ">'" . ltrim($exFilter[1], ".") . "'");
                        }
                        else {
                            if (isset($srcArray[$exFilter[1]])) {
                                $object->addFilter($exFilter[0] . ">'" . $srcArray[$exFilter[1]] . "'");
                            }
                            else {
                                $object->addFilter($exFilter[0] . ">'-555'");//none
                            }
                        }
                    }
                    else {
                        $exFilter = explode("<", $filter);
                        if (sizeof($exFilter) > 1) {
                            if (substr($exFilter[1], 0, 1) == ".") {
                                $object->addFilter($exFilter[0] . "<'" . ltrim($exFilter[1], ".") . "'");
                            }
                            else {
                                if (isset($srcArray[$exFilter[1]])) {
                                    $object->addFilter($exFilter[0] . "<'" . $srcArray[$exFilter[1]] . "'");
                                }
                                else {
                                    $object->addFilter($exFilter[0] . "<'-555'");//none
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $object;
}

function makeFiltersss($configParams, $srcArray, $object)
{
    if (is_array($configParams) && sizeof($configParams) > 0) {
        foreach ($configParams as $filter) {
            $exFilter = explode("=", $filter);
            if (sizeof($exFilter) > 1) {
                // Mendukung IN Statement
                if (strpos($filter, 'IN') !== false) {

                    // Pecah filter berdasarkan IN
                    $parts = explode("IN", $filter);
                    arrprintWebs($parts);
                    $column = trim($parts[0]);  // kolom sebelum IN
//                    cekHitam($column);
                    $column = rtrim($parts[0],"=.");  // kolom sebelum IN
//                    $column = preg_replace('/=\.$/', '', $parts[0]);
                    cekMerah($column);
                    $values = trim($parts[1]);  // nilai yang ada dalam IN (misalnya: (value1,value2,value3))
                    // Hapus tanda kurung di sekitar nilai
                    $values = trim($values, '()');
                    $valuesArray = explode(",", $values);
                    arrPrint($valuesArray);
//                    $formattedValues = array_map('trim', $valuesArray);
                    // Format klausa IN
                    $inClause = $column . " IN ('" . implode("','", $valuesArray) . "')";
                    $object->addFilter($inClause);
                }
                elseif (substr($exFilter[1], 0, 1) == ".") {
                    $object->addFilter($exFilter[0] . "='" . ltrim($exFilter[1], ".") . "'");
                } else {
                    if (isset($srcArray[$exFilter[1]])) {
                        $object->addFilter($exFilter[0] . "='" . $srcArray[$exFilter[1]] . "'");
                    } else {
                        $object->addFilter($exFilter[0] . "='-555'");
                    }
                }
            }
            else {
                // Proses untuk operator lain seperti <> atau > atau <
                $exFilter = explode("<>", $filter);
                if (sizeof($exFilter) > 1) {
                    if (substr($exFilter[1], 0, 1) == ".") {
                        $object->addFilter($exFilter[0] . "<>'" . ltrim($exFilter[1], ".") . "'");
                    } else {
                        if (isset($srcArray[$exFilter[1]])) {
                            $object->addFilter($exFilter[0] . "<>'" . $srcArray[$exFilter[1]] . "'");
                        } else {
                            $object->addFilter($exFilter[0] . "<>'-555'");//none
                        }
                    }
                }
                else {
                    $exFilter = explode(">", $filter);
                    if (sizeof($exFilter) > 1) {
                        if (substr($exFilter[1], 0, 1) == ".") {
                            $object->addFilter($exFilter[0] . ">'" . ltrim($exFilter[1], ".") . "'");
                        } else {
                            if (isset($srcArray[$exFilter[1]])) {
                                $object->addFilter($exFilter[0] . ">'" . $srcArray[$exFilter[1]] . "'");
                            } else {
                                $object->addFilter($exFilter[0] . ">'-555'");//none
                            }
                        }
                    }
                    else {
                        $exFilter = explode("<", $filter);
                        if (sizeof($exFilter) > 1) {
                            if (substr($exFilter[1], 0, 1) == ".") {
                                $object->addFilter($exFilter[0] . "<'" . ltrim($exFilter[1], ".") . "'");
                            } else {
                                if (isset($srcArray[$exFilter[1]])) {
                                    $object->addFilter($exFilter[0] . "<'" . $srcArray[$exFilter[1]] . "'");
                                } else {
                                    $object->addFilter($exFilter[0] . "<'-555'");//none
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $object;
}
