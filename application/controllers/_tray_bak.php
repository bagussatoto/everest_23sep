<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 11/6/2018
 * Time: 4:19 PM
 */
class _tray extends CI_Controller
{
    private $jenisTr;

    public function __construct()
    {
        parent::__construct();
        $this->config->load('heWebs');
        $this->jenisTr = $this->uri->segment(3);
        $webLogin = $this->config->item('logins');
        $webMaintenance = $this->config->item('maintenance');
        // cekHitam($this->session->login['id']);
//        cekHitam($webMaintenance);

        $idle_allowed = $webLogin['idleTime'];
        $att = $this->session->login['id'];

        $this->load->model("Mdls/" . "MdlEmployee");
        $o = new MdlEmployee();
        $o->setFilters(array());
        // $tmpUser[0]->ghost

        $empKoloms = array(
            "id",
            "ghost",
            "nama",
            "last_dtime_active",
        );
        $this->db->select($empKoloms);
        $tmpUser = $o->lookupByCondition(array(
            "id" => $att,
        ))->result();

        $last_dtime_active = $tmpUser[0]->last_dtime_active;
        $anggota_nama = $tmpUser[0]->nama;
        $ghost = $tmpUser[0]->ghost;

        // cekHitam($detik);

        $last_dtime_s = dtimeToSecond($last_dtime_active);
        $dtime_now = dtimeNow();
        $jam_now = dtimeNow('H:i');
        $dtimenow_s = dtimeToSecond($dtime_now);
        $idle_s = $dtimenow_s - $last_dtime_s;
        $idle_m = $idle_s / 60;
        $idle_m_f = round($idle_m);
        // echo isset($idle_allowed);
        if ($webMaintenance == 1) {
            //     cekMerah("***");
            //     $mesage_e = "under maintenance";
            //     $logout = base_url() . "Login/authLogout?e=$mesage_e";
            //     echo "tanpa debuger $logout";
            //     echo "<meta http-equiv='refresh' content=\"30;URL='$logout'\">";
            $arrAlert = array(
                "html" => "Under Maintenace",
                // "html"  => "Please, Logout manualy",
                "showConfirmButton" => false,
                // "showConfirmButton" => "false",
            );
            echo swalAlert($arrAlert);
        }
        if (isset($idle_allowed) == 1) {
            echo("$idle_m > $idle_allowed");
            if ($ghost == 0) {

                if ($idle_m > $idle_allowed) {
                    // <br> Untuk keamanan, system telah melogout account Anda
//                    $mesage = "$jam_now Terdeteksi idle selama $idle_m_f menit<br>silahkan login kembali untuk kembali beraktifitas";
//                    $mesage_e = urlencode(blobEncode($mesage));
//                    $arrAlert = array(
//                        "title" => "Security Alert",
//                        "html" => "Hi. <span style='font-size: 1.5em;text-transform: capitalize;'>$anggota_nama</span><br>$mesage<div class='text-red text-uppercase text-bold'>akan reload setelah 30 detik</div>",
//                    );
//                    echo swalAlert($arrAlert);
//                    writeLog("expired", "Logging out ...", "auth");
//
//                     $logout = base_url() . "Login/authLogout?e=$mesage_e";
//
//                     echo "<meta http-equiv='refresh' content=\"30;URL='$logout'\">";
                }


            }
        }

    }

    public function index()
    {
        //region firstSteps untuk common histories
        //===ngumpulin daftar kode step pertama, untuk keperluan ngindeks history
        $firstSteps = array();
        if (null != $this->config->item("heTransaksi_ui") && sizeof($this->config->item("heTransaksi_ui")) > 0) {
            foreach ($this->config->item("heTransaksi_ui") as $jenis => $jSpec) {
                if (isset($jSpec['steps'][1])) {
                    $firstSteps[] = "'" . $jSpec['steps'][1]['target'] . "'";
                }
            }
        }
        $script_bottom = "";
        //endregion

        $todoCtr = 0;
        $this->load->model("MdlTransaksi");
        $this->load->config("heTransaksi_ui");
        $this->load->config("heMenu");
        $this->load->helper("he_access_right");
        $this->load->helper("he_session_replacer");

        //region logic tambahan hak akses
        $customRight = alowedAccess($this->session->login['id']);
        $indsteps = "(";
        $arrFilters = array();
        foreach ($customRight as $masterJenis => $masterData) {
            foreach ($masterData as $stepNumber => $stepSpec) {
                foreach ($stepSpec as $targetCode => $filters) {
                    $indsteps .= "'$targetCode',";
                    $stepCodes[] = $targetCode;
                    if ($filters['allowFollowUp'] == "true") {
                        $arrFilters["allowFollowUp"][$masterJenis][$stepNumber] = $targetCode;
                    }
                }
            }
        }
        $indsteps = rtrim($indsteps, ",");
        $indsteps .= ")";
        //endregion

        $ci =& get_instance();
        $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();

        $tr = new MdlTransaksi();
        $tr->addFilter("div_id='" . $this->session->login['div_id'] . "'");
        $tr->addFilter("next_substep_code<>''");
        $tr->addFilter("sub_step_number>0");
        $tr->addFilter("valid_qty>0");

        $heTransaksi_ui = (null != $this->config->item("heTransaksi_ui")) ? $this->config->item("heTransaksi_ui") : array();
        $tmpTr = $tr->lookupUndoneEntries_joined(replaceSession())->result();

        $undoneTrans = array();
        $todoTrans = array();
        $subUndoneTrans = array();
        $subUndoneTransEx = array();
        $subUndoneTransName = array();
        $subUndoneTransNameEx = array();
        $subTodoTrans = array();
        $subTodoTransName = array();
        $transMenus = array();

        if (sizeof($heTransaksi_ui) > 0) {
            foreach ($heTransaksi_ui as $jenis => $jSpec) {
                if (isset($jSpec['steps']) && sizeof($jSpec['steps']) > 0) {
                    foreach ($jSpec['steps'] as $num => $sSpec) {
                        $transMenus[$jenis] = "<span class='" . $jSpec['icon'] . "'></span> " . ucwords($jSpec['label']) . "";
                    }
                }
            }
        }

//        echo json_encode($tmpTr);

        if (sizeof($tmpTr) > 0) {
            foreach ($tmpTr as $row) {
                $jenisTr = $row->jenis_master;

//                cekHijau($jenisTr);

                $targetJenis = $jenisTr;
                $nextStepNum = $row->next_step_num;
                $nextStepCode = $row->next_step_code;
                $nextSubStepNum = $row->next_substep_num;
                $allowFollowup = false;

                $pairChild = isset($this->config->item("heTransaksi_ui")[$jenisTr]['pairChild']) ? $this->config->item("heTransaksi_ui")[$jenisTr]['pairChild'] : array();

                $joinMainTrans = array();
                if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['aliasMainTrans'])) {
                    $aliasMainTrans = $this->config->item("heTransaksi_ui")[$jenisTr]['aliasMainTrans'];
                    $joinMainTrans[$jenisTr] = $aliasMainTrans;
                }

                if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum])) {
                    if (sizeof($customRight) > 0) {
                        if (isset($customRight[$jenisTr][$nextSubStepNum])) {
                            $allowFollowup = $customRight[$jenisTr][$nextSubStepNum][$nextStepCode]["allowFollowUp"];
                        }
                    }
                    else {
                        if (in_array($this->config->item("heTransaksi_ui")[$jenisTr]['steps'][$nextStepNum]['userGroup'], $this->session->login['membership'])) {
                            $allowFollowup = true;
                        }
                    }
                }

                if (isset($pairChild)) {
                    foreach ($pairChild as $jnis) {

                        if (!isset($subUndoneTransEx[$jnis])) {
                            $subUndoneTransEx[$jnis] = array();
                        }
                        if (!isset($subUndoneTransNameEx[$jnis])) {
                            $subUndoneTransNameEx[$jnis] = array();
                        }

                        $subUndoneTransEx[$jnis][] = $row->id_master;
                        $subUndoneTransNameEx[$jnis][] = $row->jenis_label;

                    }
                }

                if ($allowFollowup) {
                    $todoTrans[] = $row->id_master;
                    if (!isset($subTodoTrans[$jenisTr])) {
                        $subTodoTrans[$jenisTr] = array();
                    }
                    if (!isset($subTodoTransName[$jenisTr])) {
                        $subTodoTransName[$jenisTr] = array();
                    }
                    $subTodoTrans[$jenisTr][] = $row->id_master;
                    $subTodoTransName[$jenisTr][] = $row->jenis_label;
                    $todoCtr++;
                }
                else {
                    if ($row->oleh_id == $this->session->login['id']) {
                        if (sizeof(replaceSession()) > 0) {
                            $undoneTrans[] = $row->id_master;
                            if (!isset($subUndoneTrans[$jenisTr])) {
                                $subUndoneTrans[$jenisTr] = array();
                            }
                            if (!isset($subUndoneTransName[$jenisTr])) {
                                $subUndoneTransName[$jenisTr] = array();
                            }
                            $subUndoneTrans[$jenisTr][] = $row->id_master;
                            $subUndoneTransName[$jenisTr][] = $row->jenis_label;
                        }
                        else {
                            $subUndoneTrans = array();
                            $subUndoneTransName = array();
                        }
                    }
                    else {
                        $subUndoneTrans[$jenisTr][] = $row->id_master;
                        $subUndoneTransName[$jenisTr][] = $row->jenis_label;
                    }
                }
            }
        }


        $subUndoneTrans = $subUndoneTrans + $subUndoneTransEx;
        $subUndoneTransName = $subUndoneTransName + $subUndoneTransNameEx;
//        cekOrange("subTodoTrans");
//        arrPrint($subTodoTrans);
//        arrPrint($subTodoTransName);
//        cekOrange("subUndoneTrans");
//        arrPrint($subUndoneTrans);
//        arrPrint($subUndoneTransName);

        $transMenus = array();
        if (sizeof($heTransaksi_ui) > 0) {
            $transLabels = array();
            foreach ($heTransaksi_ui as $jenis => $jSpec) {
                $transLabels[$jenis] = strtolower($jSpec['label']);
            }
            asort($transLabels);
            foreach ($transLabels as $jenis => $label) {
                $jSpec = $heTransaksi_ui[$jenis];
                if (isset($allowedCustom[$jenis]) && sizeof($allowedCustom) > 0) {
                    $transMenus[$jenis] = "<sup><span id='bttra$jenis'></span><span id='bttrb$jenis'></span></sup> <span class='" . $jSpec['icon'] . "'></span> " . $jSpec['label'] . " ";
                }
                if (sizeof($membership) > 0) {
                    if (isset($jSpec['steps']) && sizeof($jSpec['steps']) > 0) {
                        foreach ($jSpec['steps'] as $num => $sSpec) {
                            if (($ci->session->login['cabang_id'] == "-1" && $jSpec['place'] == "center") || ($ci->session->login['cabang_id'] != "-1" && $jSpec['place'] != "center")) {
                                if (in_array($sSpec['userGroup'], $membership)) {
                                    $transMenus[$jenis] = "<sup><span id='bttra$jenis'></span><span id='bttrb$jenis'></span></sup> <span class='" . $jSpec['icon'] . "'></span> " . $jSpec['label'] . " ";
                                }
                                else {
//                                    $transMenus[$jenis] = "<sup><span id='bttra$jenis'></span><span id='bttrb$jenis'></span></sup> <span class='" . $jSpec['icon'] . "'></span> <span class='text-red'>" . $jSpec['label'] . "</span> ";
                                }
                            }
                        }
                    }
                }
            }
        }

//arrPrint($transMenus);

        $extraSrc = array();
        $subTodoTransName2 = array();
        if (sizeof($transMenus) > 0) {

            foreach ($transMenus as $targetJenis => $extras) {

                $readerDueDate = isset($this->config->item("heTransaksi_ui")[$targetJenis]['dueDateReader']) ? $this->config->item("heTransaksi_ui")[$targetJenis]['dueDateReader'] : false;
                $tmpSrcDue = array();
                $dueEmployee = array();
                if ($readerDueDate) {
                    $tr->setFilters(array());
                    $tr->addFilter("status='1'");
                    $tmpSrcDue = $tr->lookupAllDueDate()->result();

                    $tempDataDues = array();
                    $validDate = array();
                    foreach ($tmpSrcDue as $tmpSrcDue_tmp) {
                        $tempDataDues[$tmpSrcDue_tmp->customers_id][] = array(
                            "due_date" => $tmpSrcDue_tmp->due_date,
                            "aging_dtime" => $tmpSrcDue_tmp->dtime,
                        );
                    }
                    $dtime_now = strtotime(date("Y-m-d"));
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
                        $key_index = $dueVal['0'];
                        $date_due = $dtimeVal[$key_index]['due_date'];
                        $aging = $dtimeVal[$key_index]['aging'];
                        if ($dtime_now > $key_index) {
                            $dueEmployee[$cus_id] = array(
                                "due_date" => formatField("dtime", $date_due),
                                "over_due" => umurDay($date_due) > 0 ? umurDay($date_due) : "0",
                                "aging" => umurDay($aging) > 0 ? umurDay($aging) : "0",
                            );
                        }
                    }
                }


                $tr->setFilters(array());
                $tr->addFilter("transaksi_payment_source.cabang_id='" . $this->session->login['cabang_id'] . "'");
                $tr->addFilter("sisa>0");
                $tmpSrc = $tr->lookupPaymentSrcByJenis($targetJenis)->result();

                $items = array();
                $externs = array();
                $tagihans = array();
                $terbayars = array();
                $sisas = array();
                $diskons = array();
                $tagihans_valas = array();
                $terbayars_valas = array();
                $sisas_valas = array();
                $diskons_valas = array();
                $srcLabel = "";

                if (sizeof($tmpSrc) > 0) {
                    foreach ($tmpSrc as $row) {
                        $tmp = array();
                        $classMarking = "";
                        if (isset($dueEmployee[$row->extern_id])) {
                            $classMarking = "bg-danger";
                        }

                        if (!in_array($row->extern_id, $externs)) {
                            if (!isset($tagihans[$row->extern_id])) {
                                $tagihans[$row->extern_id] = 0;
                                $terbayars[$row->extern_id] = 0;
                                $diskons[$row->extern_id] = 0;
                                $sisas[$row->extern_id] = 0;
                                $tagihans_valas[$row->extern_id] = 0;
                                $terbayars_valas[$row->extern_id] = 0;
                                $sisas_valas[$row->extern_id] = 0;
                                $diskons_valas[$row->extern_id] = 0;
                            }

                            $tmp = (array)$row;
                            $tmp["link"] = base_url() . get_class($this) . "/selectPaymentSrc/$targetJenis/" . $row->extern_id;
                            $tmp["due_date"] = isset($dueEmployee[$row->extern_id]['due_date']) ? formatField("dtime", $dueEmployee[$row->extern_id]['due_date']) : "-";
                            $tmp["aging"] = isset($dueEmployee[$row->extern_id]['aging']) ? $dueEmployee[$row->extern_id]['aging'] : "-";
                            $tmp["over_due"] = isset($dueEmployee[$row->extern_id]['over_due']) ? $dueEmployee[$row->extern_id]['over_due'] : "-";
                            $tmp["class_marking"] = $classMarking;

                            $items[$row->extern_id] = $tmp;
                            $externs[] = $row->extern_id;
                            $externName = $row->extern_nama;
                        }
                        $tagihans[$row->extern_id] += isset($row->tagihan) ? $row->tagihan : 0;
                        $terbayars[$row->extern_id] += isset($row->terbayar) ? $row->terbayar : 0;
                        $sisas[$row->extern_id] += isset($row->sisa) ? $row->sisa : 0;
                        $diskons[$row->extern_id] += isset($row->diskon) ? $row->diskon : 0;
                        $tagihans_valas[$row->extern_id] += isset($row->tagihan_valas) ? $row->tagihan_valas : 0;
                        $terbayars_valas[$row->extern_id] += isset($row->terbayar_valas) ? $row->terbayar_valas : 0;
                        $sisas_valas[$row->extern_id] += isset($row->sisa_valas) ? $row->sisa_valas : 0;
                        $diskons_valas[$row->extern_id] += isset($row->diskon_valas) ? $row->diskon_valas : 0;
                        $srcLabel = $row->label;
                    }

                    foreach ($items as $externID => $iSpec) {
                        $items[$externID]['tagihan'] = $tagihans[$externID];
                        $items[$externID]['terbayar'] = $terbayars[$externID];
                        $items[$externID]['diskon'] = $diskons[$externID];
                        $items[$externID]['sisa'] = $sisas[$externID];
                        $items[$externID]['tagihan_valas'] = $tagihans_valas[$externID];
                        $items[$externID]['terbayar_valas'] = $terbayars_valas[$externID];
                        $items[$externID]['diskon_valas'] = $diskons_valas[$externID];
                        $items[$externID]['sisa_valas'] = $sisas_valas[$externID];
                    }
                }

                if (sizeof($items) > 0) {
                    foreach ($items as $ids => $src) {
//                        $extraSrc[$targetJenis][] = "kedip";
                        $extraSrc[$targetJenis][] = $src['sisa'];
                        $subTodoTransName2[$targetJenis][] = $extras;
                    }

                }

            }
        }


//arrPrint( $extraSrc );
        $subTodoTrans = $subTodoTrans + $extraSrc;
        $subTodoTransName = $subTodoTransName + $subTodoTransName2;

        $jsonTodo = json_encode($subTodoTrans);
        $strFloatMenuTodoTransMb = "";
        $strFloatMenuTodoTransDs = "";
        $subTodoTransCountTotal = 0;
        $subTodoTransCount = array();

        if (sizeof($subTodoTrans) > 0) {
            echo "\n<script>\n";

            echo "\n
            //jQuery.each(localStorage, function(i, k){
                //console.log('key: '+i+' || val: '+k);
            //})
            ";

            foreach ($subTodoTrans as $j => $jSpec) {

                echo "\nlocalStorage.setItem('$j'," . sizeof($jSpec) . ")";
//                echo "\nconsole.log(\" $j \" +  localStorage.getItem('$j') )";

                echo "\nif(top.document.getElementById('trb$j')){\n";
                echo "top.document.getElementById('trb$j').innerHTML='" . sizeof($jSpec) . "';\n";
                echo "top.document.getElementById('trb$j').className='badge bg-red text-white';\n";
                echo "top.$('trb$j').addClass('TodoTrans');\n";
                echo "}\n";
                $subTodoTransCount[$j] = sizeof($jSpec);
                $subTodoTransCountTotal += sizeof($jSpec);
            }

            foreach ($subTodoTransName as $jenis => $jName) {
                $label_nama = $jName[0];
                $targetUrl = base_url() . "Transaksi/index/$jenis";
                $labelTransMenu = isset($transMenus[$jenis]) ? $transMenus[$jenis] : "";
                $strFloatMenuTodoTransMb .= "<li id='bawah-f-$jenis-mb' class='my-nav__item-bawah-f-mb'>";
                $strFloatMenuTodoTransMb .= "<a class='my-nav__link-f-mb my-nav__link--template text-white' href='$targetUrl'>$labelTransMenu <sup><span class='badge bg-aqua'>$subTodoTransCount[$jenis]</span></sup></a>";
                $strFloatMenuTodoTransMb .= "</li>";

                $strFloatMenuTodoTransDs .= "<li id='bawah-f-$jenis-ds' class='hidden-xs my-nav__item-bawah-f-ds'>";
                $strFloatMenuTodoTransDs .= "<a class='my-nav__link-f-ds my-nav__link--template text-white' href='$targetUrl'>$labelTransMenu <sup><span class='badge bg-aqua'>$subTodoTransCount[$jenis]</span></sup></a>";
                $strFloatMenuTodoTransDs .= "</li>";

                echo "\nvar menus_mb_$jenis = top.$('#bawah-f-$jenis-mb')
                      \nif(menus_mb_$jenis.length===0){
                      \ntop.$('#wrapper-templates-bawah-f-mb').html(\"$strFloatMenuTodoTransMb\")
                      \n}
                     ";

                echo "\nvar menus_ds_$jenis = top.$('#bawah-f-$jenis-ds')
                      \nif(menus_ds_$jenis.length===0){
                      \ntop.$('#wrapper-templates-bawah-f-ds').html(\"$strFloatMenuTodoTransDs\")
                      \n}
                     ";

                if (isset($extraSrc[$jenis])) {
                    echo "\nvar menus_new_$jenis = top.$('li#$jenis');
                              \nif(menus_new_$jenis){
                                  \ntop.$('a', top.$('li#$jenis') ).css('background-color', 'rgb(0, 166, 90)');
                                  \ntop.$('a', top.$('li#$jenis') ).css('color', 'white');
                                  \ntop.$('a', top.$('li#$jenis') ).addClass('blink');
                                  \ntop.$('a', top.$('li#$jenis') ).addClass('text-bold');
                                  \ntop.$('a', top.$('li#$jenis') ).addClass('hidden');
                              \n}
                     ";
                }
            }

//--ini ga perlu.. hanya undone tapi ga masuk wewenang
            echo "\nvar gethuk_mb = top.$('#gethuk_mb');
                   \n if(gethuk_mb.length>0){
                   \n     top.$(gethuk_mb).html(\"<a class='btn btn__trigger-bawah-f-mb btn__trigger--views-bawah-f-mb' id='trigger-bawah-f-mb'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a>\");
                   \n     var el = top.document.querySelector('.btn__trigger-bawah-f-mb');
                   \n     var count = Number(el.getAttribute('data-count')) || 0;
                   \n     el.setAttribute('data-count', $subTodoTransCountTotal );
                   \n     el.classList.remove('notify');
                   \n     el.offsetWidth = el.offsetWidth;
                   \n     el.classList.add('notify');
                   \n     if(count === 0){
                   \n         el.classList.add('show-count');
                   \n     }
                   \n }
                   \n top.displayListBawahF_mb();
                   \n var gethuk_ds = top.$('#gethuk_ds');
                   \n if(gethuk_ds.length>0){
                   \n     top.$(gethuk_ds).html(\"<a class='btn btn__trigger-bawah-f-ds btn__trigger--views-bawah-f-ds' id='trigger-bawah-f-ds'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a> \");
                   \n     var el = top.document.querySelector('.btn__trigger-bawah-f-ds');
                   \n     var count = Number(el.getAttribute('data-count')) || 0;
                   \n     el.setAttribute('data-count', $subTodoTransCountTotal );
                   \n     el.classList.remove('notify');
                   \n     el.offsetWidth = el.offsetWidth;
                   \n     el.classList.add('notify');
                   \n     if(count === 0){
                   \n         el.classList.add('show-count');
                   \n     }
                   \n }
                   \n top.displayListBawahF_ds();
                 ";

            echo "</script>\n";
        }
        //end region ToDo Transaksi

        //region UnDone Transaksi
        $jsonUndone = json_encode($subUndoneTrans);
        $strFloatMenuUndoneTrans = "";
        $subUndoneTransCountTotal = 0;
        $subUndoneTransCount = array();

//        cekOrange("subTodoTrans");
        echo json_encode($subUndoneTrans);

        if (sizeof($subUndoneTrans) > 0) {
            echo "<script>\n";
            foreach ($subUndoneTrans as $j => $jSpec) {

//dimatikan dulu by chepy
                echo "\nif(top.document.getElementById('tra$j')){\n";
                echo "\ntop.document.getElementById('tra$j').innerHTML='" . sizeof($jSpec) . "';\n";
                echo "\ntop.document.getElementById('tra$j').className='badge bg-yellow';\n";
                echo "}\n";
                $subUndoneTransCount[$j]   = sizeof($jSpec);
                $subUndoneTransCountTotal += sizeof($jSpec);
            }

            foreach ($subUndoneTransName as $jenis => $jName) {
                $swapFrom = isset($this->config->item("heTransaksi_ui")[$jenis]['requestCode']['swapFrom']) ? $this->config->item("heTransaksi_ui")[$jenis]['requestCode']['swapFrom'] : "";
                $swapBadge = isset($this->config->item("heTransaksi_ui")[$jenis]['requestCode']['swapBadge']) ? $this->config->item("heTransaksi_ui")[$jenis]['requestCode']['swapBadge'] : array();

//dimatikan dulu by chepy
                $label_nama = $jName[0];
                $targetUrl = base_url() . "Transaksi/index/$jenis";
                $labelTransMenu = isset($transMenus[$jenis]) ? $transMenus[$jenis] : "";
                $strFloatMenuUndoneTrans .= "<li id='bawah-wt-$jenis' class='my-nav__item-bawah-WT'>";
                $strFloatMenuUndoneTrans .= "<a class='my-nav__link-WT my-nav__link--template text-white' href='$targetUrl'>$labelTransMenu <sup><span class='badge bg-yellow'>$subUndoneTransCount[$jenis]</span></sup></a>";
                $strFloatMenuUndoneTrans .= "</li>";
                echo "\nvar menuswt = top.$('#bawah-wt-$jenis');
                      \nif(menuswt.length===0){
                          \ntop.$('#wrapper-templates-bawah-WT').html(\"$strFloatMenuUndoneTrans\")
                      \n}";
            }

//            echo "\nvar geplak_mb = top.$('#geplak_mb');
//                   \n if(geplak_mb.length>0){
//                   \n     top.$(geplak_mb).html(\"<a class='btn btn__trigger-bawah-f-mb btn__trigger--views-bawah-f-mb' id='trigger-bawah-f-mb'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a>\");
//                   \n     var el = top.document.querySelector('.btn__trigger-bawah-f-mb');
//                   \n     var count = Number(el.getAttribute('data-count')) || 0;
//                   \n     el.setAttribute('data-count', $subUndoneTransCountTotal );
//                   \n     el.classList.remove('notify');
//                   \n     el.offsetWidth = el.offsetWidth;
//                   \n     el.classList.add('notify');
//                   \n     if(count === 0){
//                   \n         el.classList.add('show-count');
//                   \n     }
//                   \n }
//                   \n top.displayListBawahF_mb();

//                   \n var geplak_ds = top.$('#geplak_ds');
//                   \n if(geplak_ds.length>0){
//                   \n     top.$(geplak_ds).html(\"<a class='btn btn__trigger-bawah-f-ds btn__trigger--views-bawah-f-ds' id='trigger-bawah-f-ds'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a> \");
//                   \n     var el = top.document.querySelector('.btn__trigger-bawah-f-ds');
//                   \n     var count = Number(el.getAttribute('data-count')) || 0;
//                   \n     el.setAttribute('data-count', $subUndoneTransCountTotal );
//                   \n     el.classList.remove('notify');
//                   \n     el.offsetWidth = el.offsetWidth;
//                   \n     el.classList.add('notify');
//                   \n     if(count === 0){
//                   \n         el.classList.add('show-count');
//                   \n     }
//                   \n }
//                   \n top.displayListBawahF_ds();
//                 ";

//            echo "  var geplak = top.$('#geplak');
//                    if(geplak.length>0){
//                        top.$(geplak).html(\"<a class='btn btn__trigger-bawah-WT btn__trigger--views-bawah-WT' id='trigger-bawah-WT'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a> \");
//                        var el = top.document.querySelector('.btn__trigger-bawah-WT');
//                        var count = Number(el.getAttribute('data-count')) || 0;
//                        el.setAttribute('data-count', $subUndoneTransCountTotal );
//                        el.classList.remove('notify');
//                        el.offsetWidth = el.offsetWidth;
//                        el.classList.add('notify');
//                        if(count === 0){
//                            el.classList.add('show-count');
//                        }
//                    }
//                    top.displayListBawahWT();
//                 ";

            echo "</script>\n";
        }
        //endregion UnDone Transaksi

        if ($todoCtr > 0) {
            $todoLink = base_url() . "Transaksi/viewCompactUndoneItems/";
            $todoClick = "top.BootstrapDialog.show(
                              {
                                  title:'todo items ',
                                  message: " . '$' . "('<div></div>').load('" . $todoLink . "'),
                                  draggable:true,
                                  closable:true,
                                  size:top.BootstrapDialog.SIZE_WIDE,
                              }
                          );";
            echo "\n<script>\n";
            echo "\nif(top.document.getElementById('not_ctr')){\n";
            echo "\ntop.document.getElementById('not_ctr').innerHTML=\"<a href='" . base_url() . "Welcome/index' class='text-white'>" . $todoCtr . "</a>\";\n";
            echo "\ntop.document.getElementById('not_ctr').className='badge';\n";
            echo "\ntop.document.getElementById('not_ctr').style.background='#990000';\n";
            echo "\n}\n";
            echo "\n</script>\n";
        }

        $ci =& get_instance();
        $dataConfig = $ci->config->item('heDataBehaviour');
        $dataRelConfig = $ci->config->item('dataRelation');
        $settingConfig = $ci->config->item('heSettingAdmin');
        $otherMenuConfig = $ci->config->item('menu');
        $availMenuConfig = $ci->config->item('availMenu');
        $membership = is_array($ci->session->login['membership']) ? $ci->session->login['membership'] : array();
        $dataMenus = array();
        $dataExcludes = array();

        if (sizeof($dataRelConfig) > 0) {
            foreach ($dataRelConfig as $srcMdl => $sSpec) {
                foreach ($sSpec as $xmdlName => $xSpec) {
                    $dataExcludes[$xmdlName] = $xmdlName;
                }
            }
        }
        if (sizeof($ci->load->config("heDataBehaviour")) > 0) {
            foreach ($dataConfig as $mdlName => $mSpec) {
                if (isset($mSpec['creators'])) {
                    if (sizeof($mSpec['creators']) > 0) {
                        if (sizeof($membership) > 0) {
                            foreach ($membership as $gID) {
                                if (in_array($gID, $mSpec['creators'])) {
                                    $tmpLabel = str_replace("Mdl", "", $mdlName);
                                    $label = isset($dataConfig[$mdlName]['label']) ? $dataConfig[$mdlName]['label'] : $tmpLabel;
                                    if (!in_array($mdlName, $dataExcludes)) {
                                        $dataMenus[$tmpLabel] = $label . createObjectSuffix($label);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $dataProposals = array();
        $arrDataMenus = array();
        if (sizeof($dataMenus) > 0) {
            $this->load->model("Mdls/" . "MdlDataTmp");
            $tData = new MdlDataTmp();
            foreach ($dataMenus as $gLabel => $gName) {
                $arrDataMenus[$gLabel] = "Mdl" . "" . $gLabel;
                $className = "Mdl" . "" . $gLabel;
                $tData->addFilter("mdl_name='$className'");
                $tmpTmp = $tData->lookupAll()->result();

                if (sizeof($tmpTmp) > 0) {
                    foreach ($tmpTmp as $row) {
                        $mdlName = $row->mdl_name;
                        $dataAccess = isset($this->config->item('heDataBehaviour')[$mdlName]) ? $this->config->item('heDataBehaviour')[$mdlName] : array(
                            "viewers" => array(),
                            "creators" => array(),
                            "creatorAdmins" => array(),
                            "updaters" => array(),
                            "updaterAdmins" => array(),
                            "deleters" => array(),
                            "deleterAdmins" => array(),
                        );
                        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
                        $allowView = false;
                        $allowCreate = false;
                        $allowEdit = false;
                        $allowDelete = false;
                        foreach ($mems as $mID) {
                            if (in_array($mID, $dataAccess['viewers'])) {
                                $allowView = true;
                            }
                            if (in_array($mID, $dataAccess['creators'])) {
                                $allowCreate = true;
                            }
                            if (in_array($mID, $dataAccess['updaters'])) {
                                $allowEdit = true;
                            }
                            if (in_array($mID, $dataAccess['deleters'])) {
                                $allowDelete = true;
                            }
                        }

                        if ($allowView || $allowCreate) {
                            if (!isset($dataProposals[$mdlName])) {
                                $dataProposals[$mdlName] = array();
                            }
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

        $todoCdt = 0;
        $todoDatas = array();
        $subTodoDatas = array();
        $subTodoDatasName = array();

        if (sizeof($dataProposals) > 0) {
            if (sizeof($arrDataMenus) > 0) {
                foreach ($dataProposals as $gLabel => $row) {
                    foreach ($row as $x => $xConten) {
                        if (!isset($subTodoDatas[$gLabel])) {
                            $subTodoDatas[$gLabel] = array();
                        }
                        if (!isset($subTodoDatasName[$gLabel])) {
                            $subTodoDatasName[$gLabel] = array();
                        }
                        $subTodoDatas[$gLabel][] = $xConten['id'];
                        $subTodoDatasName[$gLabel][] = $xConten['label'];
                        $todoCdt++;
                    }
                }
            }
        }

        $strFloatMenuTodoDatasMb = "";
        $strFloatMenuTodoDatasDs = "";
        $subTodoDatasCountTotal = 0;
        $subTodoDatasCount = array();

        //region tambah hak akses disini untuk foolowup data
        if (sizeof($subTodoDatas) > 0) {
            echo "\n<script>\n";
            foreach ($subTodoDatas as $j => $jSpec) {
                $j_f = str_replace("Mdl", "", $j);
                echo "\nif(top.document.getElementById('crdtb$j_f')){\n";
                echo "\ntop.document.getElementById('crdtb$j_f').innerHTML='" . sizeof($jSpec) . "';\n";
                echo "\ntop.document.getElementById('crdtb$j_f').className='badge bg-red text-white';\n";
                echo "\ntop.$('crdtb$j_f').addClass('TodoDatas');\n";
                echo "\n}\n";
                $subTodoDatasCount[$j] = sizeof($jSpec);
                $subTodoDatasCountTotal += sizeof($jSpec);
            }

            foreach ($subTodoDatasName as $jenis => $jName) {
                $label_nama = $jName[0];
                $jenis_f = str_replace("Mdl", "", $jenis);
                $targetUrl = base_url() . "data/view/$jenis_f";

                $strFloatMenuTodoDatasMb .= "<li id='bawah-WT-$jenis_f-mb' class='my-nav__item-bawah-WT-mb'>";
                $strFloatMenuTodoDatasMb .= "<a class='my-nav__link-WT-mb my-nav__link--template text-white' href='$targetUrl'>$dataMenus[$jenis_f] <sup><span class='badge bg-aqua'>$subTodoDatasCount[$jenis]</span></sup></a>";
                $strFloatMenuTodoDatasMb .= "</li>";

                $strFloatMenuTodoDatasDs .= "<li id='bawah-WT-$jenis_f-ds' class='my-nav__item-bawah-WT-ds'>";
                $strFloatMenuTodoDatasDs .= "<a class='my-nav__link-WT-ds my-nav__link--template text-white' href='$targetUrl'>$dataMenus[$jenis_f] <sup><span class='badge bg-aqua'>$subTodoDatasCount[$jenis]</span></sup></a>";
                $strFloatMenuTodoDatasDs .= "</li>";

                echo "\nvar menus_WT_a$jenis_f = top.$('#bawah-WT-$jenis_f-mb')
                      \nif(menus_WT_a$jenis_f.length===0){
                      \n    top.$('#wrapper-templates-bawah-WT-mb').html(\"$strFloatMenuTodoDatasMb\")
                      \n}
                     ";

                echo "\nvar menus_WT_b$jenis_f = top.$('#bawah-WT-$jenis_f-ds')
                      \nif(menus_WT_b$jenis_f.length===0){
                      \n    top.$('#wrapper-templates-bawah-WT-ds').html(\"$strFloatMenuTodoDatasDs\")
                      \n}
                     ";

            }

            echo "\nvar geplak_mb = top.$('#geplak_mb');\n
                    \nif(geplak_mb.length>0){
                        \ntop.$(geplak_mb).html(\"<a class='btn btn__trigger-bawah-WT-mb btn__trigger--views-bawah-WT-mb' id='trigger-bawah-WT-mb'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a> \");
                        \nvar el = top.document.querySelector('.btn__trigger-bawah-WT-mb');
                        \nvar count = Number(el.getAttribute('data-count')) || 0;
                        \nel.setAttribute('data-count', $subTodoDatasCountTotal );
                        \nel.classList.remove('notify');
                        \nel.offsetWidth = el.offsetWidth;
                        \nel.classList.add('notify');
                        \nif(count === 0){
                        \n    el.classList.add('show-count');
                        \n}
                    \n}
                    \ntop.displayListBawahWT_mb();

                    \nvar geplak_ds = top.$('#geplak_ds');\n
                    \nif(geplak_ds.length>0){
                        \ntop.$(geplak_ds).html(\"<a class='btn btn__trigger-bawah-WT-ds btn__trigger--views-bawah-WT-ds' id='trigger-bawah-WT-ds'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a> \");
                        \nvar el = top.document.querySelector('.btn__trigger-bawah-WT-ds');
                        \nvar count = Number(el.getAttribute('data-count')) || 0;
                        \nel.setAttribute('data-count', $subTodoDatasCountTotal );
                        \nel.classList.remove('notify');
                        \nel.offsetWidth = el.offsetWidth;
                        \nel.classList.add('notify');
                        \nif(count === 0){
                        \n    el.classList.add('show-count');
                        \n}
                    \n}
                    \ntop.displayListBawahWT_ds();
                    \n</script>\n";

        }
        //endregion tambah hak alses disini untuk foolowup data

        //region other data
        $showOtherMenu = array(
            "bls"
        );

        $otherMenus = array();
        if (sizeof($membership) > 0) {
            foreach ($membership as $gID) {
                if (isset($otherMenuConfig[$gID]) && sizeof($otherMenuConfig[$gID]) > 0) {
                    foreach ($otherMenuConfig[$gID] as $kode) {
                        if (isset($availMenuConfig[$kode])) {
                            $otherMenus[$kode] = array(
                                "label" => $availMenuConfig[$kode]['label'],
                                "icon" => $availMenuConfig[$kode]['icon'],
                                "target" => $availMenuConfig[$kode]['target'],
                            );
                        }
                    }
                }
                if (isset($otherMenuConfig["*"]) && sizeof($otherMenuConfig["*"]) > 0) {
                    foreach ($otherMenuConfig["*"] as $kode) {
                        if (isset($availMenuConfig[$kode])) {
                            $otherMenus[$kode] = array(
                                "label" => $availMenuConfig[$kode]['label'],
                                "icon" => $availMenuConfig[$kode]['icon'],
                                "target" => $availMenuConfig[$kode]['target'],
                            );
                        }
                    }
                }
            }
        }


        //region notif rekening
        $accountChilds = $this->config->item("accountChilds") != null ? $this->config->item("accountChilds") : array();
        $accountAlias = $this->config->item("accountAlias") != null ? $this->config->item("accountAlias") : array();
        $blockRekenings = $this->config->item("accountStructure");
        $struktureRekening = array();
        foreach ($blockRekenings as $blockRekening) {
            foreach ($blockRekening as $itemRekening) {
                $struktureRekening[] = $itemRekening;
            }
        }
        $this->load->model("Coms/ComRekening");
        $r = new ComRekening();
        $r->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
        $tmp = $r->fetchAllBalances();
        $rekenings = array();
        $totals = array(
            "rekening" => "total",
            "debet" => 0,
            "kredit" => 0,
        );
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $rekening_name = isset($accountAlias[$row['rekening']]) ? $accountAlias[$row['rekening']] : $row['rekening'];
                $linkH = "<span class='pull-right'><a href='" . base_url() . "Ledger/viewMoveDetails_1/Rekening/" . $row['rekening'] . "'  data-toggle='tooltip' title='mutasi $rekening_name' target='_blank'><span class='glyphicon glyphicon-time'></span></a></span>";
                if (isset($accountChilds[$row['rekening']])) {
                    $link = base_url() . "Ledger/viewBalances_l1/" . $accountChilds[$row['rekening']] . "/" . $row['rekening'];
                    $rekening_name_l = "<a href='$link' title='mutasi pembantu $rekening_name' data-toggle='tooltip' target='_blank'>$rekening_name</a>";
                }
                else {
                    $rekening_name_l = $rekening_name;
                }
                $rekening_name_l .= $linkH;
                $tmpCol = array(
                    "rekening_orig" => $row['rekening'],
                    "rekening" => $rekening_name_l,
                    "debet" => $row['debet'] * 1,
                    "kredit" => $row['kredit'] * 1,
                    "link" => "",
                );
                $rekenings[] = $tmpCol;
                $totals['debet'] += $row['debet'];
                $totals['kredit'] += $row['kredit'];
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

        $rekening_yang_harus_dinotif = array(
            "biaya",
        );

        $subOtherMenus = array();

        if (sizeof($showOtherMenu) > 0) {
            foreach ($showOtherMenu as $ky) {
                if (isset($otherMenus[$ky])) {
                    if (sizeOf($rekenings_sort) > 0) {
                        foreach ($rekenings_sort as $valueRekening) {
                            if (in_array($valueRekening['rekening_orig'], $rekening_yang_harus_dinotif)) {
                                $keyRek = str_replace(" ", "", $valueRekening['rekening_orig']);
                                if ($valueRekening['debet'] > 0) {
                                    cekOrange("<span class='text-capitalize'>" . $valueRekening['rekening'] . "</span> (Rp." . number_format($valueRekening['debet']) . ")");
                                    if (!isset($subOtherMenus[$keyRek])) {
                                        $subOtherMenus[$keyRek] = array();
                                    }
                                    $subOtherMenus[$keyRek] = $valueRekening;
                                }
                                else {

                                }
                            }
                        }
                    }
                }
            }
        }


//arrPrint($subOtherMenus);
//        $subOtherMenus = array();
        $strFloatOtherMenusMb = "";
        $strFloatOtherMenusDs = "";

        if (sizeof($subOtherMenus) > 0) {
            echo "\n<script>\n";

            foreach ($subOtherMenus as $jenis => $jName) {

                $targetUrl = base_url() . "Transaksi/index/$jenis";
                $targetUrlLabel = $subOtherMenus[$jenis]['rekening'];
                $nilaiDebet = $subOtherMenus[$jenis]['debet'];
                $labelOtherMenu = isset($subOtherMenus[$jenis]['rekening_orig']) ? $subOtherMenus[$jenis]['rekening_orig'] : "";

                $strFloatOtherMenusMb .= "<li id='bawah-RK-$jenis-mb' class='my-nav__item-bawah-RK-mb'>";
                $strFloatOtherMenusMb .= "<span class='my-nav__lrekening_origink-RK-mb my-nav__link--template text-white'> $targetUrlLabel (Rp." . number_format($subOtherMenus[$jenis]['debet']) . ") </span>";
                $strFloatOtherMenusMb .= "</li>";

                $strFloatOtherMenusDs .= "<li id='bawah-RK-$jenis-ds' class='hidden-xs my-nav__item-bawah-RK-ds'>";
                $strFloatOtherMenusDs .= "<span class='my-nav__link-RK-ds my-nav__link--template text-white'> $targetUrlLabel (Rp." . number_format($subOtherMenus[$jenis]['debet']) . ") </span>";
                $strFloatOtherMenusDs .= "</li>";

                echo "\nvar other_mb_$jenis = top.$('#bawah-RK-$jenis-mb')
                            \nif(other_mb_$jenis.length===0){
                            \ntop.$('#wrapper-templates-bawah-RK-mb').html(\"$strFloatOtherMenusMb\")
                            \n}
                     ";

                echo "\nvar other_ds_$jenis = top.$('#bawah-RK-$jenis-ds')
                            \nif(other_ds_$jenis.length===0){
                            \ntop.$('#wrapper-templates-bawah-RK-ds').html(\"$strFloatOtherMenusDs\")
                            \n}
                     ";

            }

            $addTooltip = true;


//--ini ga perlu.. hanya undone tapi ga masuk wewenang
            echo "\nvar other_mb = top.$('#other_mb');
                   \n if(other_mb.length>0){
                   \n     top.$(other_mb).html(\"<a class='btn btn__trigger-bawah-RK-mb btn__trigger--views-bawah-RK-mb' id='trigger-bawah-RK-mb'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a>\");
                   \n     var el = top.document.querySelector('.btn__trigger-bawah-RK-mb');
                   \n     var count = Number(el.getAttribute('data-count')) || 0;
                   \n     el.setAttribute('data-count', '" . sizeof($subOtherMenus) . "' );
                   \n     el.classList.remove('notify');
                   \n     el.offsetWidth = el.offsetWidth;
                   \n     el.classList.add('notify');
                   \n     if(count === 0){
                   \n         el.classList.add('show-count');
                   \n     }
                   \n }
                   \n top.displayListBawahRK_mb();
                   \n var other_ds = top.$('#other_ds');
                   \n if(other_ds.length>0){
                   \n     top.$(other_ds).html(\"<a class='btn btn__trigger-bawah-RK-ds btn__trigger--views-bawah-RK-ds' id='trigger-bawah-RK-ds'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a> \");
                   \n     var el = top.document.querySelector('.btn__trigger-bawah-RK-ds');
                   \n     var count = Number(el.getAttribute('data-count')) || 0;
                   \n     el.setAttribute('data-count', '" . sizeof($subOtherMenus) . "' );
                   \n     el.classList.remove('notify');
                   \n     el.offsetWidth = el.offsetWidth;
                   \n     el.classList.add('notify');
                   \n     if(count === 0){
                   \n         el.classList.add('show-count');
                   \n     }
                   \n }
                   \n top.displayListBawahRK_ds();
                 ";

            echo "</script>\n";
        }

        arrPrint($addTooltip);

//endregion notif rekening

        //region lookup on-going from connected requests
        $jenisTr = $this->uri->segment(3);
        if (isset($this->config->item("heTransaksi_ui")[$jenisTr]['requestCode'])) {
            $masterRefCode = $this->config->item("heTransaksi_ui")[$jenisTr]['requestCode']['masterCode'];
            $stateRefCode = $this->config->item("heTransaksi_ui")[$jenisTr]['requestCode']['stateCode'];
            $stateRefNum = $this->config->item("heTransaksi_ui")[$jenisTr]['requestCode']['stepNumber'];

            $this->load->model("MdlTransaksi");
            $tr = new MdlTransaksi();
            $tr->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
            $tr->addFilter("jenis_master='" . $masterRefCode . "'");
            $tr->addFilter("jenis='" . $stateRefCode . "'");
            $tr->addFilter("step_current='" . $stateRefNum . "'");
            $tmpByReq = $tr->lookupRecentHistories()->result();

            if (!isset($_SESSION['undoneQty'])) {
                $_SESSION['undoneQty'] = array();
            }

            if (!isset($_SESSION['undoneQty'][$jenisTr])) {
                $_SESSION['undoneQty'][$jenisTr] = 0;
            }

            if (sizeof($tmpByReq) != $_SESSION['undoneQty'][$jenisTr]) {

                echo "<script>";
                echo "top.getData('" . base_url() . "Transaksi/viewUndoneItems/" . $jenisTr . "/?ohyes=ohno','undoneList');";
                echo "</script>";

                echo "<script>";
                echo "setTimeout( function(){ top.getData('" . base_url() . "Transaksi/viewRequestItems/" . $jenisTr . "/?ohyes=ohno','requestItems') }, 1000);";
                echo "</script>";

            }
            else {

            }


        }
        else {
        }
        //endregion lookup on-going from connected requests

        //region penulis ipaddress aktif
        $mems = isset($this->session->login['membership']) ? $this->session->login['membership'] : array();
        $this->load->model("Mdls/MdlActiveIPAddr");
        if (sizeof($mems) > 0) {
            foreach ($mems as $gID) {
                $ip = new MdlActiveIPAddr();
                $ip->addFilter("cabang_id='" . $this->session->login['cabang_id'] . "'");
                $ip->addFilter("gudang_id='" . $this->session->login['gudang_id'] . "'");
                $ip->addFilter("jenis='" . $gID . "'");
                $tmpi = $ip->lookupAll()->result();
                if (sizeof($tmpi) > 0) {
                    $ip->updateData(array(
                        "cabang_id" => $this->session->login['cabang_id'],
                        "gudang_id" => $this->session->login['gudang_id'],
                        "cabang_nama" => $this->session->login['cabang_nama'],
                        "gudang_nama" => $this->session->login['gudang_nama'],
                        "jenis" => $gID,
                    ), array(
                        "ipaddr" => $_SERVER['REMOTE_ADDR'],
                        //                    "ipaddr"=>"192.168.5.5",
                        "person" => $this->session->login['id'],
                        "last_active" => date("Y-m-d H:i:s"),
                    ));

                }
                else {
                    $ip->addData(array(
                        "ipaddr" => $_SERVER['REMOTE_ADDR'],
                        "cabang_id" => $this->session->login['cabang_id'],
                        "gudang_id" => $this->session->login['gudang_id'],
                        "cabang_nama" => $this->session->login['cabang_nama'],
                        "gudang_nama" => $this->session->login['gudang_nama'],
                        "jenis" => $gID,
                        "person" => $this->session->login['id'],
                        "last_active" => date("Y-m-d H:i:s"),
                    ));
                }
            }
        }
        //endregion penulis ipaddress aktif

        $trayControl = "<script>
                            var sesTrayCount=0;
                            if(sessionStorage.getItem('sesTrayCount')==null){
                                sessionStorage.setItem('sesTrayCount','1');
                                top.console.log('_tray null');
                            }
                            if(sessionStorage.getItem('sesTrayCount')!==null){
                                if(parseFloat(sessionStorage.getItem('sesTrayCount'))>99){
                                    if( top.$('.modal.in').length ){
                                        sesTrayCount = parseFloat(1) + parseFloat( sessionStorage.getItem('sesTrayCount') );
                                        sessionStorage.setItem('sesTrayCount', sesTrayCount);
                                        top.console.log('%c pull _tray ' + sesTrayCount, 'color:#dd4b39');
                                        top.console.log('%c pull _tray reload menunggu modal di tutup/followup. || count: ' + sesTrayCount, 'color:#dd4b39');
                                    }
                                    else{
                                        sessionStorage.setItem('sesTrayCount', 0);
                                        top.location.reload();
                                    }
                                }
                                else{
                                    sesTrayCount = parseFloat(1) + parseFloat( sessionStorage.getItem('sesTrayCount') );
                                    sessionStorage.setItem('sesTrayCount', sesTrayCount);
                                    top.console.log('%c pull _tray ' + sesTrayCount, 'color:#eee');
                                }
                            }
                        </script>";

    }
}

