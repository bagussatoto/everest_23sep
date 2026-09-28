<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tray Helper
 * Contains business logic and UI data preparation for _tray_enchanced controller.
 */

if (!function_exists('tray_check_idle_time')) {
    function tray_check_idle_time()
    {
        $CI = &get_instance();
        $webLogin = $CI->config->item('logins');
        $webMaintenance = $CI->config->item('maintenance');
        $idle_allowed = isset($webLogin['idleTime']) ? $webLogin['idleTime'] : 60;
        $att = isset($CI->session->login['id']) ? $CI->session->login['id'] : null;

        if (!$att) return array();

        $CI->load->model("Mdls/MdlEmployee");
        $o = new MdlEmployee();
        $o->setFilters(array());

        $empKoloms = array("id", "ghost", "nama", "last_dtime_active");
        $CI->db->select($empKoloms);
        $tmpUser = $o->lookupByCondition(array("id" => $att))->result();

        if (empty($tmpUser)) return array();

        $last_dtime_active = $tmpUser[0]->last_dtime_active;
        $anggota_nama = $tmpUser[0]->nama;
        $ghost = $tmpUser[0]->ghost;

        $last_dtime_s = dtimeToSecond($last_dtime_active);
        $dtime_now = dtimeNow();
        $jam_now = dtimeNow('H:i');
        $dtimenow_s = dtimeToSecond($dtime_now);
        $idle_s = $dtimenow_s - $last_dtime_s;
        $idle_m = $idle_s / 60;
        $idle_m_f = round($idle_m);

        return array(
            'is_maintenance' => ($webMaintenance == 1),
            'is_idle' => ($ghost == 0 && isset($idle_allowed) && $idle_m > $idle_allowed),
            'idle_minutes' => $idle_m_f,
            'anggota_nama' => $anggota_nama,
            'jam_now' => $jam_now
        );
    }
}

if (!function_exists('tray_get_active_batch')) {
    function tray_get_active_batch()
    {
        $CI = &get_instance();
        static $cachedBatch = null;
        if ($cachedBatch !== null) {
            return $cachedBatch;
        }

        $cachedBatch = 0;
        $row = $CI->db->query("SELECT active_batch FROM sys_cache_tray_version WHERE id = 1")->row();
        if ($row && isset($row->active_batch)) {
            $cachedBatch = (int)$row->active_batch;
        }
        return $cachedBatch;
    }
}

if (!function_exists('tray_calculate_transaksi')) {
    function tray_calculate_transaksi($configUiAllModul)
    {
        $CI = &get_instance();
        $customRight = alowedAccess($CI->session->login['id']);
        $membership = is_array($CI->session->login['membership']) ? $CI->session->login['membership'] : array();

        $CI->load->model("MdlTransaksi");
        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='" . $CI->session->login['div_id'] . "'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");

        $tA = microtime(true);
        $sess = replaceSession();
        $activeBatch = tray_get_active_batch();
        $CI->db->select('*');
        if ($activeBatch > 0) {
            $CI->db->where('batch_id', $activeBatch);
        }
        if (sizeof($sess) > 0) {
            $cab = $sess['cabang_id'];
            $CI->db->group_start();
            $CI->db->where('cabang_id', $cab);
            $CI->db->or_where('cabang2_id', $cab);
            $CI->db->group_end();
        }
        $tmpTr = $CI->db->get('sys_cache_tray_transaksi')->result();
        $tB = microtime(true);

        $undoneTrans = array();
        $todoTrans = array();
        $subUndoneTrans = array();
        $subUndoneTransEx = array();
        $subUndoneTransName = array();
        $subUndoneTransNameEx = array();
        $subTodoTrans = array();
        $subTodoTransName = array();
        $transMenus = array();
        $resetterJenisTr = array();

        if (!empty($configUiAllModul)) {
            foreach ($configUiAllModul as $jenis => $jSpec) {
                if (isset($jSpec['steps']) && sizeof($jSpec['steps']) > 0) {
                    $transMenus[$jenis] = "<span class='" . (isset($jSpec['icon']) ? $jSpec['icon'] : '') . "'></span> " . ucwords(isset($jSpec['label']) ? $jSpec['label'] : '') . "";
                    if (!isset($resetterJenisTr[$jenis])) {
                        $resetterJenisTr[$jenis] = $jenis;
                    }
                }
            }
        }

        if (sizeof($tmpTr) > 0) {
            foreach ($tmpTr as $row) {
                $qty = isset($row->qty) ? (int)$row->qty : 1;
                $jenisTr = $row->jenis_master;
                $nextStepNum = $row->next_step_num;
                $nextStepCode = $row->next_step_code;
                $nextSubStepNum = $row->next_substep_num;
                $allowFollowup = false;

                $pairChild = isset($configUiAllModul[$jenisTr]['pairChild']) ? $configUiAllModul[$jenisTr]['pairChild'] : array();

                if (isset($configUiAllModul[$jenisTr]['steps'][$nextStepNum])) {
                    if (sizeof($customRight) > 0) {
                        if (isset($customRight[$jenisTr][$nextSubStepNum])) {
                            $allowFollowup = isset($customRight[$jenisTr][$nextSubStepNum][$nextStepCode]["allowFollowUp"]) ? $customRight[$jenisTr][$nextSubStepNum][$nextStepCode]["allowFollowUp"] : false;
                        }
                    }
                    else {
                        if (in_array($configUiAllModul[$jenisTr]['steps'][$nextStepNum]['userGroup'], $membership)) {
                            $allowFollowup = true;
                        }
                    }
                }

                if (!empty($pairChild)) {
                    foreach ($pairChild as $jnis) {
                        if (!isset($subUndoneTransEx[$jnis])) $subUndoneTransEx[$jnis] = array();
                        if (!isset($subUndoneTransNameEx[$jnis])) $subUndoneTransNameEx[$jnis] = array();

                        for ($it = 0; $it < $qty; $it++) {
                            $subUndoneTransEx[$jnis][] = 1;
                            $subUndoneTransNameEx[$jnis][] = $row->jenis_label;
                        }
                    }
                }

                if ($allowFollowup) {
                    if (!isset($subTodoTrans[$jenisTr])) $subTodoTrans[$jenisTr] = array();
                    if (!isset($subTodoTransName[$jenisTr])) $subTodoTransName[$jenisTr] = array();

                    for ($it = 0; $it < $qty; $it++) {
                        $todoTrans[] = 1;
                        $subTodoTrans[$jenisTr][] = 1;
                        $subTodoTransName[$jenisTr][] = $row->jenis_label;
                    }
                }
                else {
                    if ($row->oleh_id == $CI->session->login['id']) {
                        if (sizeof(replaceSession()) > 0) {
                            if (!isset($subUndoneTrans[$jenisTr])) $subUndoneTrans[$jenisTr] = array();
                            if (!isset($subUndoneTransName[$jenisTr])) $subUndoneTransName[$jenisTr] = array();

                            for ($it = 0; $it < $qty; $it++) {
                                $undoneTrans[] = 1;
                                $subUndoneTrans[$jenisTr][] = 1;
                                $subUndoneTransName[$jenisTr][] = $row->jenis_label;
                            }
                        }
                        else {
                            $subUndoneTrans = array();
                            $subUndoneTransName = array();
                        }
                    }
                    else {
                        if (!isset($subUndoneTrans[$jenisTr])) $subUndoneTrans[$jenisTr] = array();
                        if (!isset($subUndoneTransName[$jenisTr])) $subUndoneTransName[$jenisTr] = array();

                        for ($it = 0; $it < $qty; $it++) {
                            $subUndoneTrans[$jenisTr][] = 1;
                            $subUndoneTransName[$jenisTr][] = $row->jenis_label;
                        }
                    }
                }
            }
        }
        $tC = microtime(true);

        $subUndoneTrans = $subUndoneTrans + $subUndoneTransEx;
        $subUndoneTransName = $subUndoneTransName + $subUndoneTransNameEx;

        $transMenus = array();
        if (sizeof($configUiAllModul) > 0) {
            $transLabels = array();
            foreach ($configUiAllModul as $jenis => $jSpec) {
                $transLabels[$jenis] = strtolower(isset($jSpec['label']) ? $jSpec['label'] : '');
            }
            asort($transLabels);

            foreach ($transLabels as $jenis => $label) {
                $jSpec = $configUiAllModul[$jenis];
                if (sizeof($membership) > 0) {
                    if (isset($jSpec['steps']) && sizeof($jSpec['steps']) > 0) {
                        foreach ($jSpec['steps'] as $num => $sSpec) {
                            $place = isset($jSpec['place']) ? $jSpec['place'] : '';
                            if (($CI->session->login['cabang_id'] == "-1" && $place == "center") || ($CI->session->login['cabang_id'] != "-1" && $place != "center")) {
                                if (in_array($sSpec['userGroup'], $membership)) {
                                    $transMenus[$jenis] = "<sup><span id='bttra$jenis'></span><span id='bttrb$jenis'></span></sup> <span class='" . (isset($jSpec['icon']) ? $jSpec['icon'] : '') . "'></span> " . (isset($jSpec['label']) ? $jSpec['label'] : '') . " ";
                                }
                            }
                        }
                    }
                }
            }
        }

        // ToDo: Make this excluded array configurable instead of magic number
        $excluded = array("464");
        $extraSrc = array();
        $subTodoTransName2 = array();

        $globalDueEmployee = null;
        $globalPaymentSrcMap = null;

        if (sizeof($transMenus) > 0) {
            foreach ($transMenus as $targetJenis => $extras) {
                if (in_array($targetJenis, $excluded)) {
                    continue;
                }

                $readerDueDate = isset($configUiAllModul[$targetJenis]['dueDateReader']) ? $configUiAllModul[$targetJenis]['dueDateReader'] : false;
                $dueEmployee = array();

                if ($readerDueDate) {
                    if ($globalDueEmployee === null) {
                        $CI->db->select('*');
                        if ($activeBatch > 0) {
                            $CI->db->where('batch_id', $activeBatch);
                        }
                        $tmpSrcDue = $CI->db->get('sys_cache_tray_duedate')->result();
                        $tempDataDues = array();

                        foreach ($tmpSrcDue as $tmpSrcDue_tmp) {
                            $tempDataDues[$tmpSrcDue_tmp->customers_id][] = array(
                                "due_date" => $tmpSrcDue_tmp->due_date,
                                "aging_dtime" => $tmpSrcDue_tmp->dtime,
                            );
                        }
                        $dtime_now = strtotime(date("Y-m-d"));
                        $globalDueEmployee = array();

                        foreach ($tempDataDues as $cus_id => $tempDataDues_0) {
                            $dueVal = array();
                            $dtimeVal = array();
                            foreach ($tempDataDues_0 as $dtime_val) {
                                $keyIndex = strtotime($dtime_val['due_date']);
                                $dueVal[] = $keyIndex;
                                $dtimeVal[$keyIndex] = array(
                                    "due_date" => $dtime_val['due_date'],
                                    "aging" => $dtime_val['aging_dtime'],
                                );
                            }
                            asort($dueVal);
                            $key_index = reset($dueVal);
                            if ($key_index && $dtime_now > $key_index) {
                                $date_due = $dtimeVal[$key_index]['due_date'];
                                $aging = $dtimeVal[$key_index]['aging'];
                                $globalDueEmployee[$cus_id] = array(
                                    "due_date" => formatField("dtime", $date_due),
                                    "over_due" => umurDay($date_due) > 0 ? umurDay($date_due) : "0",
                                    "aging" => umurDay($aging) > 0 ? umurDay($aging) : "0",
                                );
                            }
                        }
                    }
                    $dueEmployee = $globalDueEmployee;
                }
                $tD = microtime(true);

                if ($globalPaymentSrcMap === null) {
                    $targetJenisList = array_keys($transMenus);
                    $inList = array();
                    foreach ($targetJenisList as $tj) {
                        if (!in_array($tj, $excluded)) {
                            $inList[] = $CI->db->escape($tj);
                        }
                    }
                    if (sizeof($inList) > 0) {
                        $batchSql = ($activeBatch > 0) ? " AND batch_id = " . $activeBatch : "";
                        $sql = "SELECT * FROM sys_cache_tray_payment WHERE target_jenis IN (" . implode(",", $inList) . ") AND cabang_id = " . $CI->db->escape($CI->session->login['cabang_id']) . " AND sisa > 1000" . $batchSql;
                        $allSrc = $CI->db->query($sql)->result();

                        $globalPaymentSrcMap = array();
                        foreach ($allSrc as $srcRow) {
                            $globalPaymentSrcMap[$srcRow->target_jenis][] = $srcRow;
                        }
                    }
                    else {
                        $globalPaymentSrcMap = array();
                    }
                }

                $tmpSrc = isset($globalPaymentSrcMap[$targetJenis]) ? $globalPaymentSrcMap[$targetJenis] : array();

                $items = array();
                $externs = array();

                if (sizeof($tmpSrc) > 0) {
                    foreach ($tmpSrc as $row) {
                        if (!in_array($row->extern_id, $externs)) {
                            $tmp = (array)$row;
                            $tmp["link"] = base_url() . $CI->router->fetch_class() . "/selectPaymentSrc/$targetJenis/" . $row->extern_id;
                            $tmp["due_date"] = isset($dueEmployee[$row->extern_id]['due_date']) ? formatField("dtime", $dueEmployee[$row->extern_id]['due_date']) : "-";
                            $tmp["aging"] = isset($dueEmployee[$row->extern_id]['aging']) ? $dueEmployee[$row->extern_id]['aging'] : "-";
                            $tmp["over_due"] = isset($dueEmployee[$row->extern_id]['over_due']) ? $dueEmployee[$row->extern_id]['over_due'] : "-";
                            $tmp["class_marking"] = isset($dueEmployee[$row->extern_id]) ? "bg-danger" : "";

                            $items[$row->extern_id] = $tmp;
                            $externs[] = $row->extern_id;

                            $items[$row->extern_id]['sisa'] = 0; // Initialize
                        }
                        $items[$row->extern_id]['sisa'] += isset($row->sisa) ? $row->sisa : 0;
                    }
                }

                if (sizeof($items) > 0) {
                    foreach ($items as $ids => $src) {
                        $extraSrc[$targetJenis][] = $src['sisa'];
                        $subTodoTransName2[$targetJenis][] = $extras;
                    }
                }
            }
        }

        $subTodoTrans = $subTodoTrans + $extraSrc;
        $subTodoTransName = $subTodoTransName + $subTodoTransName2;

        $subTodoTransCountTotal = 0;
        foreach ($subTodoTrans as $jSpec) {
            $subTodoTransCountTotal += sizeof($jSpec);
        }

        // Check active requests based on requestCode
        $requestCheck = array('triggerUpdate' => false);
        $jenisTr = $CI->uri->segment(3);
        if (isset($configUiAllModul[$jenisTr]['requestCode'])) {
            $masterRefCode = $configUiAllModul[$jenisTr]['requestCode']['masterCode'];
            $stateRefCode = $configUiAllModul[$jenisTr]['requestCode']['stateCode'];
            $stateRefNum = $configUiAllModul[$jenisTr]['requestCode']['stepNumber'];

            $trReq = new MdlTransaksi();
            $trReq->addFilter("cabang_id='" . $CI->session->login['cabang_id'] . "'");
            $trReq->addFilter("jenis_master='" . $masterRefCode . "'");
            $trReq->addFilter("jenis='" . $stateRefCode . "'");
            $trReq->addFilter("step_current='" . $stateRefNum . "'");
            $tmpByReq = $trReq->lookupRecentHistories()->result();

            if (!isset($_SESSION['undoneQty'])) $_SESSION['undoneQty'] = array();
            if (!isset($_SESSION['undoneQty'][$jenisTr])) $_SESSION['undoneQty'][$jenisTr] = 0;

            if (sizeof($tmpByReq) != $_SESSION['undoneQty'][$jenisTr]) {
                $requestCheck['triggerUpdate'] = true;
                $requestCheck['jenisTr'] = $jenisTr;
            }
        }

        $tE = microtime(true);
        $log = "Profiling tray_calculate_transaksi:\n";
        $log .= "  - lookupUndoneEntries: " . ($tB - $tA) . " s (rows: " . (isset($tmpTr) ? sizeof($tmpTr) : 0) . ")\n";
        $log .= "  - tmpTr Loop: " . ($tC - $tB) . " s\n";
        $log .= "  - Rest of function: " . ($tE - $tC) . " s\n";
        file_put_contents('/var/www/everest_13agus/logs/profile.txt', $log, FILE_APPEND);

        return array(
            'todoCtr' => count($todoTrans),
            'subTodoTrans' => $subTodoTrans,
            'subTodoTransName' => $subTodoTransName,
            'subTodoTransCountTotal' => $subTodoTransCountTotal,
            'subUndoneTrans' => $subUndoneTrans,
            'subUndoneTransName' => $subUndoneTransName,
            'resetterJenisTr' => $resetterJenisTr,
            'transMenus' => $transMenus,
            'extraSrc' => $extraSrc,
            'requestCheck' => $requestCheck
        );
    }
}

if (!function_exists('tray_calculate_proposals')) {
    function tray_calculate_proposals()
    {
        $CI = &get_instance();
        $dataConfig = $CI->config->item('heDataBehaviour');
        $dataRelConfig = $CI->config->item('dataRelation');
        $membership = is_array($CI->session->login['membership']) ? $CI->session->login['membership'] : array();

        $dataMenus = array();
        $dataExcludes = array();

        if (sizeof($dataRelConfig) > 0) {
            foreach ($dataRelConfig as $srcMdl => $sSpec) {
                foreach ($sSpec as $xmdlName => $xSpec) {
                    $dataExcludes[$xmdlName] = $xmdlName;
                }
            }
        }

        if (!empty($dataConfig)) {
            foreach ($dataConfig as $mdlName => $mSpec) {
                if (isset($mSpec['creators']) && sizeof($mSpec['creators']) > 0 && sizeof($membership) > 0) {
                    foreach ($membership as $gID) {
                        if (in_array($gID, $mSpec['creators'])) {
                            $tmpLabel = str_replace("Mdl", "", $mdlName);
                            $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                            if (!in_array($mdlName, $dataExcludes)) {
                                $dataMenus[$tmpLabel] = $label . (function_exists('createObjectSuffix') ? createObjectSuffix($label) : '');
                            }
                        }
                    }
                }
            }
        }

        $dataProposals = array();
        $arrDataMenus = array();
        if (sizeof($dataMenus) > 0) {
            $CI->load->model("Mdls/MdlDataTmp");
            $tData = new MdlDataTmp();

            // Optimization: Resolve N+1 query by doing WHERE IN instead of looping queries
            $classNames = array();
            foreach ($dataMenus as $gLabel => $gName) {
                $arrDataMenus[$gLabel] = "Mdl" . $gLabel;
                $classNames[] = "'Mdl" . $gLabel . "'";
            }

            if (sizeof($classNames) > 0) {
                $tData->setFilters(array());
                $tData->addFilter("mdl_name IN (" . implode(",", $classNames) . ")");
                $tmpTmp = $tData->lookupAll()->result();

                if (sizeof($tmpTmp) > 0) {
                    foreach ($tmpTmp as $row) {
                        $mdlName = $row->mdl_name;
                        $dataAccess = isset($CI->config->item('heDataBehaviour')[$mdlName]) ? $CI->config->item('heDataBehaviour')[$mdlName] : array(
                            "viewers" => array(), "creators" => array(), "updaters" => array(), "deleters" => array()
                        );

                        $allowView = false;
                        $allowCreate = false;
                        foreach ($membership as $mID) {
                            if (in_array($mID, $dataAccess['viewers'])) $allowView = true;
                            if (in_array($mID, $dataAccess['creators'])) $allowCreate = true;
                        }

                        if ($allowView || $allowCreate) {
                            if (!isset($dataProposals[$mdlName])) $dataProposals[$mdlName] = array();
                            $dataProposals[$mdlName][] = array(
                                "id" => $row->_id,
                                "label" => $row->mdl_label,
                                "origID" => $row->orig_id,
                                "proposer" => $row->proposed_by_name,
                                "date" => $row->proposed_date,
                                "content" => unserialize(base64_decode($row->content)),
                                "propose_type" => $row->propose_type,
                            );
                        }
                    }
                }
            }
        }

        $subTodoDatas = array();
        $subTodoDatasName = array();
        if (sizeof($dataProposals) > 0 && sizeof($arrDataMenus) > 0) {
            foreach ($dataProposals as $gLabel => $row) {
                foreach ($row as $x => $xConten) {
                    if (!isset($subTodoDatas[$gLabel])) $subTodoDatas[$gLabel] = array();
                    if (!isset($subTodoDatasName[$gLabel])) $subTodoDatasName[$gLabel] = array();

                    $subTodoDatas[$gLabel][] = $xConten['id'];
                    $subTodoDatasName[$gLabel][] = $xConten['label'];
                }
            }
        }

        $subTodoDatasCountTotal = 0;
        foreach ($subTodoDatas as $jSpec) {
            $subTodoDatasCountTotal += sizeof($jSpec);
        }

        return array(
            'dataMenus' => $dataMenus,
            'subTodoDatas' => $subTodoDatas,
            'subTodoDatasName' => $subTodoDatasName,
            'subTodoDatasCountTotal' => $subTodoDatasCountTotal
        );
    }
}

if (!function_exists('tray_calculate_rekening')) {
    function tray_calculate_rekening()
    {
        $CI = &get_instance();
        $accountChilds = $CI->config->item("accountChilds") != null ? $CI->config->item("accountChilds") : array();
        $accountAlias = $CI->config->item("accountAlias") != null ? $CI->config->item("accountAlias") : array();
        $blockRekenings = $CI->config->item("accountStructure") != null ? $CI->config->item("accountStructure") : array();

        $struktureRekening = array();
        foreach ($blockRekenings as $blockRekening) {
            foreach ($blockRekening as $itemRekening) {
                $struktureRekening[] = $itemRekening;
            }
        }

        $CI->load->model("Coms/ComRekening");
        $r = new ComRekening();
        $r->addFilter("cabang_id='" . $CI->session->login['cabang_id'] . "'");
        $tmp = $r->fetchAllBalances();

        $rekenings = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $rekening_name = isset($accountAlias[$row['rekening']]) ? $accountAlias[$row['rekening']] : $row['rekening'];
                $rekening_name_l = $rekening_name;

                $rekenings[] = array(
                    "rekening_orig" => $row['rekening'],
                    "rekening" => $rekening_name_l,
                    "debet" => $row['debet'] * 1,
                    "kredit" => $row['kredit'] * 1,
                    "link" => "",
                );
            }
        }

        $rekenings_sort = array();
        $no = -1;
        foreach ($struktureRekening as $rek) {
            foreach ($rekenings as $spec) {
                if ($rek == $spec['rekening_orig']) {
                    $no++;
                    $rekenings_sort[$no] = $spec;
                }
            }
        }

        $rekening_yang_harus_dinotif = array("biaya");
        $subOtherMenus = array();

        if (sizeof($rekenings_sort) > 0) {
            foreach ($rekenings_sort as $valueRekening) {
                if (in_array($valueRekening['rekening_orig'], $rekening_yang_harus_dinotif)) {
                    $keyRek = str_replace(" ", "", $valueRekening['rekening_orig']);
                    if ($valueRekening['debet'] > 0) {
                        $subOtherMenus[$keyRek] = $valueRekening;
                    }
                }
            }
        }

        return array(
            'subOtherMenus' => $subOtherMenus
        );
    }
}

if (!function_exists('tray_record_active_ip')) {
    function tray_record_active_ip()
    {
        $CI = &get_instance();
        $mems = isset($CI->session->login['membership']) ? $CI->session->login['membership'] : array();
        if (sizeof($mems) == 0) return;

        $CI->load->model("Mdls/MdlActiveIPAddr");
        // Gunakan fungsi input->ip_address() agar aman dari proxy, tidak menggunakan $_SERVER['REMOTE_ADDR']
        $current_ip = $CI->input->ip_address();

        // Optimization: Resolve N+1 query by batch checking 
        $gIDs = array();
        foreach ($mems as $gID) {
            $gIDs[] = "'" . $gID . "'";
        }

        $ip = new MdlActiveIPAddr();
        $ip->addFilter("cabang_id='" . $CI->session->login['cabang_id'] . "'");
        $ip->addFilter("gudang_id='" . $CI->session->login['gudang_id'] . "'");
        $ip->addFilter("jenis IN (" . implode(",", $gIDs) . ")");
        $existing_ips = $ip->lookupAll()->result();

        $existing_map = array();
        if (sizeof($existing_ips) > 0) {
            foreach ($existing_ips as $row) {
                $existing_map[$row->jenis] = $row;
            }
        }

        foreach ($mems as $gID) {
            $ipData = array(
                "cabang_id" => $CI->session->login['cabang_id'],
                "gudang_id" => $CI->session->login['gudang_id'],
                "cabang_nama" => isset($CI->session->login['cabang_nama']) ? $CI->session->login['cabang_nama'] : "",
                "gudang_nama" => isset($CI->session->login['gudang_nama']) ? $CI->session->login['gudang_nama'] : "",
                "jenis" => $gID,
                "ipaddr" => $current_ip,
                "person" => $CI->session->login['id'],
                "last_active" => date("Y-m-d H:i:s"),
            );

            if (isset($existing_map[$gID])) {
                // Technically MdlActiveIPAddr updateData updates all matched rows but here we just pass the keys
                $ip->updateData(
                    array(
                        "cabang_id" => $CI->session->login['cabang_id'],
                        "gudang_id" => $CI->session->login['gudang_id'],
                        "jenis" => $gID,
                    ),
                    array(
                        "ipaddr" => $current_ip,
                        "person" => $CI->session->login['id'],
                        "last_active" => date("Y-m-d H:i:s"),
                    )
                );
            }
            else {
                $ip->addData($ipData);
            }
        }
    }
}
