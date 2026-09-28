<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 10/20/2018
 * Time: 8:35 PM
 */


class OverDue_releaser extends CI_Controller
{
    protected $jenis;
    protected $cabang_id;
    private $arrayKoloms;

    public function __construct()
    {
        parent::__construct();
        /*------------------------
         * tak pindah baca config duedate:: metode::View()
         * ----------------*/
        // $this->mainField = array(
        //     "customers_id" => "Customer",
        //     "data_field"   => "Transaction ",
        //     "btn_action"   => "Action",
        // );
        // $this->field = array(
        //     "data_field" => array(
        //         "nomer_top"       => "sales order",
        //         "nomer"           => "packing list",
        //         "dtime"           => "shipment date",
        //         "duedate_value"   => "du date",
        //         "aging"           => "aging(days)",
        //         "over_due"        => "over due(days)",
        //         "transaksi_nilai" => "amount",
        //         "status"          => "status",
        //     ),
        // );
        $this->load->config("heOverdue");
        $confOverdues = $this->config->item("heOverdue");
        $confMainOverdues = $confOverdues['overdue'];
        //region baca config overdues
        foreach ($confMainOverdues['mainFields'] as $mField => $mFChilds) {
            $mFields[] = $mField;
            isset($mFChilds['label']) ? $mFieldToshows[$mField] = $mFChilds['label'] : "";
            isset($mFChilds['attr']) ? $mFieldAttr[$mField] = $mFChilds['attr'] : "";
            isset($mFChilds['attrHeader']) ? $mFieldAttrHeader[$mField] = $mFChilds['attrHeader'] : "";
            isset($mFChilds['link']) ? $mFieldLink[$mField] = $mFChilds['link'] : "";
            isset($mFChilds['format']) ? $mFieldFormat[$mField] = $mFChilds['format'] : "";
        }
        // arrPrint($confMainOverdues['dataFields']['data_field']);
        foreach ($confMainOverdues['dataFields']['data_field'] as $dField => $dFChilds) {
            $dFields[] = $dField;
            isset($dFChilds['label']) ? $dFieldToshows['data_field'][$dField] = $dFChilds['label'] : "";
            isset($dFChilds['attr']) ? $dFieldAttr[$dField] = $dFChilds['attr'] : "";
            isset($dFChilds['attrHeader']) ? $dFieldAttrHeader[$dField] = $dFChilds['attrHeader'] : "";
            isset($dFChilds['link']) ? $dFieldLink[$dField] = $dFChilds['link'] : "";
            isset($dFChilds['format']) ? $dFieldFormat[$dField] = $dFChilds['format'] : "";
        }
        //endregion
        $this->mainField = $mFieldToshows;
        $this->field = $dFieldToshows;
    }

    public function View()
    {
        $this->load->config("heOverdue");
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlOverDuePass");
        $confOverdues = $this->config->item("heOverdue");
        $hs = new MdlOverDuePass();
        $tr = new MdlTransaksi();
        // $confMainOverdues = $confOverdues['overdue'];
        // //region baca config overdues
        // foreach ($confMainOverdues['mainFields'] as $mField => $mFChilds) {
        //     $mFields[] = $mField;
        //     isset($mFChilds['label']) ? $mFieldToshows[$mField] = $mFChilds['label'] : "";
        //     isset($mFChilds['attr']) ? $mFieldAttr[$mField] = $mFChilds['attr'] : "";
        //     isset($mFChilds['attrHeader']) ? $mFieldAttrHeader[$mField] = $mFChilds['attrHeader'] : "";
        //     isset($mFChilds['link']) ? $mFieldLink[$mField] = $mFChilds['link'] : "";
        //     isset($mFChilds['format']) ? $mFieldFormat[$mField] = $mFChilds['format'] : "";
        // }
        // // arrPrint($confMainOverdues['dataFields']['data_field']);
        // foreach ($confMainOverdues['dataFields']['data_field'] as $dField => $dFChilds) {
        //     $dFields[] = $dField;
        //     isset($dFChilds['label']) ? $dFieldToshows['data_field'][$dField] = $dFChilds['label'] : "";
        //     isset($dFChilds['attr']) ? $dFieldAttr[$dField] = $dFChilds['attr'] : "";
        //     isset($dFChilds['attrHeader']) ? $dFieldAttrHeader[$dField] = $dFChilds['attrHeader'] : "";
        //     isset($dFChilds['link']) ? $dFieldLink[$dField] = $dFChilds['link'] : "";
        //     isset($dFChilds['format']) ? $dFieldFormat[$dField] = $dFChilds['format'] : "";
        // }
        // //endregion
        // $this->mainField = $mFieldToshows;
        // $this->field = $dFieldToshows;
        // arrPrint($mFieldToshows);
        // arrPrint($dFieldToshows);

        //region main data
        $tr->setFilters(array());
        $tr->addFilter("status='1'");
        $temp = $tr->lookupAllDueDate()->result();
        // cekLime($this->db->last_query());
        // arrPrint($temp);
        $dataTemp = array();
        $subTotal = array();
        $mainTransaction = array();
        $mainTopNum = array();
        $pihakData = array();
        $mainTransaction_btn = array();
        $subtotal = 0;
        if (sizeof($temp) > 0) {
            $tr->setFilters(array());
            $ids_tmp = array();
            foreach ($temp as $temp_0) {
                $aging = umurDay($temp_0->dtime);
                $over_due = umurDay($temp_0->due_date);

                if ($over_due > 0) {
                    $ids_tmp[] = $temp_0->transaksi_id;
                    $dataTemp[$temp_0->customers_id][$temp_0->transaksi_id] = array(
                        //                        "ids" =>$temp_0->id,
                        "over_due" => $over_due,
                        "aging" => $aging,
                        "nomer" => $temp_0->nomer,
                        "dtime" => $temp_0->dtime,
                        "duedate_value" => $temp_0->due_date,
                        "transaksi_nilai" => $temp_0->transaksi_nilai,
                    );
                    $subtotal += $temp_0->transaksi_nilai;
                    $subTotal[$temp_0->customers_id] = $subtotal;
                    $pihakData[$temp_0->customers_id] = $temp_0->customers_nama;
                }
            }

            if (sizeof($ids_tmp) > 0) {
                $tr->addFilter("id in (" . implode(",", $ids_tmp) . ")");
                $mainResult = $tr->lookupMainTransaksi()->result();
                //                arrPrint($mainResult);

                foreach ($mainResult as $main) {
                    $mainTransaction[$main->customers_id][$main->id] = $main->nomer_top;
                    $mainTransaction_btn[$main->customers_id]['btn_action'] = $main->customers_id;

                    $mainTopNum[$main->id_top] = $main->nomer_top;
                }
            }

        }
        $buildMain = array();
        foreach ($this->mainField as $kolMain => $mainAlias) {
            if (isset($mainTransaction[$kolMain])) {
                $buidMain[$kolMain] = $mainTransaction[$kolMain];
            }
            else {
                $buildMain[$kolMain] = array();
            }
        }
        $finalData = array();
        if (sizeof($mainTransaction) > 0) {
            foreach ($mainTransaction as $custID => $tempMain) {
                //region cek status unlocker
                $isAllow = validateOverDue($custID);
                // arrPrint($isAllow);
                //endregion
                foreach ($tempMain as $idPL => $topNum) {
                    if (isset($dataTemp[$custID][$idPL])) {
                        $finalData[$custID][] = $dataTemp[$custID][$idPL] + array(
                                "nomer_top" => $topNum,
                                "status" => $isAllow['status'],
                            );
                    }
                }
            }
        }
        //endregion

        // region history
        //region baca dari config
        foreach ($confOverdues['historyBypass']['mdlFields'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $fieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $fieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $fieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $fieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $fieldFormat[$field] = $fChilds['format'] : "";
        }
        // mdlFieldChilds
        foreach ($confOverdues['historyBypass']['mdlFieldChilds'] as $field => $fChilds) {
            $fields[] = $field;
            isset($fChilds['label']) ? $cFieldToshows[$field] = $fChilds['label'] : "";
            isset($fChilds['attr']) ? $cFieldAttr[$field] = $fChilds['attr'] : "";
            isset($fChilds['attrHeader']) ? $cFieldAttrHeader[$field] = $fChilds['attrHeader'] : "";
            isset($fChilds['link']) ? $cFieldLink[$field] = $fChilds['link'] : "";
            isset($fChilds['format']) ? $cFieldFormat[$field] = $fChilds['format'] : "";
        }
        //endregion
        // region headers
        $specs_0 = array();
        $mainHeaders_00 = array();
        $specs_0["no"] = 0;
        foreach ($fieldToshows as $field => $fieldToshow) {
            $fAttr = isset($fieldAttrHeader[$field]) ? $fieldAttrHeader[$field] : "-";
            $specs_0[$fieldToshow] = $fAttr;

            $mainHeaders_00 = $specs_0;
            $rNo["no"] = $fAttr;
        }
        foreach ($cFieldToshows as $cField => $cFieldToshow) {
            $fAttr = isset($cFieldAttrHeader[$cField]) ? $cFieldAttrHeader[$cField] : "-";
            $specs_0[$cFieldToshow] = $fAttr;
            $mainHeaders_00 = $specs_0;
        }
        $mainHeaders_0 = array_replace($mainHeaders_00, $rNo);
        // endregion headers
        // arrPrint($mainHeaders_0);

        $hs->addFilter("jenis='history'");
        $scrHs = $hs->lookupAll()->result();
        // cekKuning($this->db->last_query());
        // arrPrint($scrHs);
        // region hdBodies
        $no = 0;
        if (sizeof($scrHs) > 0) {
            foreach ($scrHs as $scrs) {
                $no++;

                $specs["no"]["value"] = $no;
                $specs["no"]["attr"] = "class='text-right'";
                $mainDatas = (object)(blobDecode($scrs->main_data)[0]);
                // $scrs0 = (array)$scrs + $mainDatas;
                // arrPrint($scrs);
                // arrPrintWebs($mainDatas);
                // arrPrint($scrs0);
                foreach ($fieldToshows as $field => $fieldToshow) {
                    $specs[$field]['value'] = $scrs->$field;
                    $specs[$field]['attr'] = isset($fieldAttr[$field]) ? $fieldAttr[$field] : "class='text-left'";
                    isset($fieldLink[$field]) ? $specs[$field]['link'] = $fieldLink[$field] : "";
                    isset($fieldFormat[$field]) ? $specs[$field]['format'] = $fieldFormat[$field] : "";
                }
                foreach ($cFieldToshows as $cField => $cFieldToshow) {
                    $specs[$cField]['value'] = isset($mainDatas->$cField) ? $mainDatas->$cField : "";
                    $specs[$cField]['attr'] = isset($cFieldAttr[$cField]) ? $cFieldAttr[$cField] : "class='text-left'";
                    isset($cFieldLink[$cField]) ? $specs[$cField]['link'] = $cFieldLink[$cField] : "";
                    isset($cFieldFormat[$cField]) ? $specs[$cField]['format'] = $cFieldFormat[$cField] : "";
                }
                $hsBodies[] = $specs;

            }
        }
        else {
            $jmlHeader = sizeof($fieldToshows);
            $specs['no']['value'] = "nothing to show(s)";
            $specs['no']['attr'] = "class='text-center text-grey font-size-1-2' colpasn='$jmlHeader'";
            $hsBodies[] = $specs;

        }
        // endregion hdBodies
        // arrPrint($hsBodies);
        // endregion history

        // region test
        $test = "";
        $test .= "<div class='postList'>cek</div>";
        $test .= "<div id='show_more_main'>more</div>";
        $hsUrl = base_url() . "OverDue_releaser/history";
        $test .= "<script type='text/javascript'>
                     var hsUrl = $hsUrl;
                     function load_data(limit, start)
                     {
                          $.ajax({
                               url:hsUrl,
                               method:'POST',
                               // data:{limit:limit, start:start, cari:cari, ly:ly, fe:fe, urut:urut, aktif:aktif, supplier:supplier, kategori:kategori},
                               cache:false,
            
                               success:function(data)
                               {
                                   $('.postList').append(html);
                               }
                               });
                      };

                    // $(document).ready(function(){
                    //     $(document).on('click','.show_more',function(){
                    //         var ID = $(this).attr('id');
                    //         $('.show_more').hide();
                    //         $('.loding').show();
                    //         $.ajax({
                    //             type:'POST',
                    //             url:'OverDue_releaser/test',
                    //             data:'id='+ID,
                    //             success:function(html){
                    //                 $('#show_more_main'+ID).remove();
                    //                 $('.postList').append(html);
                    //             }
                    //         });
                    //     });
                    // });
        </script>";
        // endregion test

        $data = array(
            "mode" => $this->uri->segment(2),
            //            "left_menu" => callMenuleft(),
            "title" => "Overdue Transactions",
            "subTitle" => "",
            "mainData" => $mainTransaction,
            "detailsData" => $finalData,
            "mainLabels" => $this->mainField,
            "detilFields" => $this->field,
            "sumData" => $subTotal,
            "pihakData" => $pihakData,

            "hsTitle" => $confOverdues['historyBypass']['title'],
            "hsHeaders" => $mainHeaders_0,
            "hsBodies" => $hsBodies,
            // "hsFootes" => $hsFooters,

            "test" => $test,
        );
        $this->load->view("over_due", $data);

    }

    public function history()
    {
        echo __METHOD__ . "<hr>" . __FILE__ . " thomas";
    }

    public function preview()
    {
        //        arrPrint($this->uri->segment_array());
        //arrPrint(base_url());
        $rawPrev = base_url() . "" . $this->uri->segment(1) . "/View";
        $raw = blobEncode($rawPrev);
        $custID = $this->uri->segment(3);
        $this->load->model("Mdls/MdlCustomer");
        $this->load->model("MdlTransaksi");
        $c = new MdlCustomer();
        $custData = $c->lookupByID($custID)->result();
        $name = $custData[0]->nama;

        $tr = new MdlTransaksi();
        $tr->setFilters(array());
        $tr->addFilter("status='1'");
        $tr->addFilter("customers_id='$custID'");
        $temp = $tr->lookupAllDueDate()->result();
        $overStatus = validateOverDue($custID);


        if (sizeof($temp) > 0) {
            $tr->setFilters(array());
            $ids_tmp = array();
            $dataTemp = array();
            $subtotal = 0;
            foreach ($temp as $temp_0) {
                $aging = umurDay($temp_0->dtime);
                $over_due = umurDay($temp_0->due_date);
                if ($over_due > 0) {
                    $ids_tmp[] = $temp_0->transaksi_id;
                    $dataTemp[$temp_0->transaksi_id] = array(
                        //                        "ids" =>$temp_0->id,
                        "over_due" => $over_due,
                        "aging" => $aging,
                        "nomer" => $temp_0->nomer,
                        "dtime" => $temp_0->dtime,
                        "duedate_value" => $temp_0->due_date,
                        "transaksi_nilai" => $temp_0->transaksi_nilai,
                    );
                    $subtotal += $temp_0->transaksi_nilai;
                    $subTotal[$temp_0->customers_id] = $subtotal;

                }
            }
            $mainTransaction = array();
            if (sizeof($ids_tmp) > 0) {
                $tr->addFilter("id in (" . implode(",", $ids_tmp) . ")");
                $mainResult = $tr->lookupMainTransaksi()->result();
                foreach ($mainResult as $main) {
                    $mainTransaction[$main->id] = $main->nomer_top;
                }
            }

            $items = array();
            foreach ($dataTemp as $ids => $dataTemp_0) {

                if (isset($mainTransaction[$ids])) {
                    $valData['nomer_top'] = $mainTransaction[$ids];
                }
                $items[] = $valData + $dataTemp_0 + $overStatus;
            }

        }
        if ($overStatus['status'] == 'allowed') {
            $btnLabel = "Lock";
            $formTarget = base_url() . "" . $this->uri->segment(1) . "/doLock/" . $this->uri->segment(3);
        }
        else {
            $formTarget = base_url() . "" . $this->uri->segment(1) . "/doUnlock/" . $this->uri->segment(3);
            $btnLabel = "unlock";
        }
        $btnValue = array(
            "$btnLabel" => array(
                "label" => " $btnLabel",
                "type" => "submit",
                "class" => "btn btn-danger pull-right",
                "style" => "",
            ),
        );

        $data = array(
            "mode" => $this->uri->segment(2),
            "title" => $name,
            "items" => $items,
            "itemLabels" => $this->field,
            "sumValue" => $subtotal,
            "btnVal" => $btnValue,
            "target" => $formTarget,
            "rawPrev" => $raw,
        );
        $this->load->view("over_due", $data);
    }

    public function doUnlock()
    {
        //        arrPrint($_POST);
        $custID = $this->uri->segment(3);

        $this->load->model("Mdls/MdlOverDuePass");
        $o = new MdlOverDuePass();
        $this->load->model("Mdls/MdlCustomer");
        $c = new MdlCustomer();
        $tempCust = $c->lookupByID($custID)->result();
        $nama = $tempCust[0]->nama;

        $o->addFilter("jenis='forever'");
        $o->addFilter("customers_id='$custID'");
        $temp = $o->lookupAll()->result();
        cekHere($this->db->last_query());
        // arrPrint($temp);
        $this->db->trans_start();
        if (sizeof($temp)) {
            //update aja
            $where = array(
                "customers_id" => "$custID",
                "jenis" => "forever",
            );
            $arrUpdate = array(
                "status" => "1",
                "oleh_id" => $_SESSION['login']['id'],
                "oleh_nama" => $_SESSION['login']['nama'],
                "auth_dtime" => date("Y-m-d H:i"),

            );
            $update = $o->updateData($where, $arrUpdate, $o->getTableName());
            if ($update) {
                $markUpdate = true;
            }
            //            matiHere("nyok update");
        }
        else {
            //insert baru

            $arrTemp = array(
                "customers_id" => $custID,
                "customers_nama" => $nama,
                "oleh_id" => $_SESSION['login']['id'],
                "oleh_nama" => $_SESSION['login']['nama'],
                "status" => "1",
                "auth_dtime" => date("Y-m-d H:i"),
                "jenis" => "forever",
            );

            $insertID = $o->addData($arrTemp, $o->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
            cekHijau($this->db->last_query());
        }

        //ini history
        $arrTempH = array(
            "customers_id" => $custID,
            "customers_nama" => $nama,
            "oleh_id" => $_SESSION['login']['id'],
            "oleh_nama" => $_SESSION['login']['nama'],
            "status" => "0",
            "auth_dtime" => date("Y-m-d H:i"),
            "jenis" => "history",
            "main_data" => $_POST['data'],
        );

        $o->addData($arrTempH, $o->getTableName()) or die(lgShowError("Gagal menulis riwayat data", __FILE__));
        cekHere($this->db->last_query());
        $raw = blobDecode($_POST['rawPrev']);

        // matiHere("belum commit");
        $this->db->trans_complete();
        //matiHere();
        topRedirect(base_url() . get_class($this) . "/View");
        //        topRedirect("https://google.com");
        //        arrPrint($temp);


    }

    public function doLock()
    {
        arrPrint($this->uri->segment_array());
        $this->load->model("Mdls/MdlOverDuePass");
        $o = new MdlOverDuePass();
        $custID = $this->uri->segment(3);
        $where = array(
            "customers_id" => $custID,
            "jenis" => "forever",
        );
        $update = array(
            "status" => "0",
            "oleh_id" => $_SESSION['login']['id'],
            "oleh_nama" => $_SESSION['login']['nama'],
            "auth_dtime" => date("Y-m-d H:i"),
        );
        $this->db->trans_start();
        if ($custID > 0) {
            $o->updateData($where, $update, $o->getTableName());
            //           cekHere($this->db->last_query());
        }

        //       matiHere("belum commit");
        $this->db->trans_complete();
        topRedirect(base_url() . get_class($this) . "/View");
    }

    public function viewIncomplete()
    {

    }

    public function viewShortHistory()
    {
        $this->load->model("Mdls/MdlOverDuePass");
        $od = new MdlOverDuePass();
        $od->addFilter("jenis='history'");
        $temp = $od->lookupRecentHistories()->result();
        arrPrint($temp);
    }

    public function viewHistory()
    {

    }


}