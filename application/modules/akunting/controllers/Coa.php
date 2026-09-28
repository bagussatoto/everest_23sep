<?php


class Coa extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->masterConfigUi = $this->config->item("heTransaksi_ui");
        $this->load->model(array(
            'Mdls/MdlAccounts',
            // 'Web_settings'
        ));
    }

    function index()
    {
        $this->coa;
    }

    /* ------------------------------------
     * view coa
     * layout kanan kiri diatur di viewer, penpil detailnya di atur dr library layout::dfs_code
     * form editor adadi-coa_selectedform pengeraknya via jstre/custom
     * ------------------------------------*/
    function coa()
    {
        // $this->load
        $ss = rekening_coa_he_accounting();
        // cekBiru($ss);
        // $this->load->library("LibCoas");

        $this->load->model(array(
            'Mdls/MdlAccounts',
            // 'Web_settings'
        ));
        $this->load->config("heAccounting");
        $this->load->library('parser');
        $accStucture = $this->config->item('accountStructure');
        // cekBiru($accStucture);
        // $srcSatu = $this->MdlAccounts->get_coalist();
        $srcSatu = $this->MdlAccounts->get_userlist();
        // showLast_query("biru");
        // arrPrint($srcSatu);
        // $ncGlobal = sprintf("%05d", 5);
        // $ncGlobal = 5*1000000000;
        // cekHere("$ncGlobal");
        $data = array(
            "mode" => "coa",
            "title" => "coa",
            "subTitle" => "coa",
            "userList" => $srcSatu,
            "accStucture" => $accStucture,
            // 'userID'   => set_value('userID'),
        );

        $this->load->view("coa", $data);
        // $treeinfo = $this->parser->parse('newaccount/treeview',$data,true);
    }

    /* ---------------------------------------------
     * form data view maupun new
     * ---------------------------------------------*/
    public function coa_selectedform($id)
    {
        $CI = &get_instance();
        $CI->load->model('Mdls/MdlAccounts');
        $role_reult = $CI->MdlAccounts->treeview_selectform($id);
        $coaDatas = $CI->MdlAccounts->get_userlist();
        $baseurl = base_url() . 'accounts/insert_coa';
        $fields = $CI->MdlAccounts->getFields();
        $labels = array();
        foreach ($fields as $field => $fParams) {
            $$field = $role_reult->$field;

            $labels[$field] = isset($fParams['label']) ? $fParams['label'] : $field;
        }
        $showFilters = $CI->MdlAccounts->getShowFilters();
        // arrPrint($coaDatas);

        /* ------------------------------------------------------------------------------------------
         * config akunting nama rekening sebagai key utama
         * ------------------------------------------------------------------------------------------*/
        $cRekeningAll = array();
        foreach ($coaDatas as $coaData) {
            $cRekening = $coaData->rekening;
            $cHeadCode = $coaData->head_code;

            $cRekeningAll[$cHeadCode] = $cRekening;
        }
        // arrPrintWebs($cRekeningAll);

        $CI->load->config("heAccounting");
        $coRekenings = $CI->config->item("accountStructure");
        // arrPrintWebs($coRekenings);
        $allRekenings = array();
        foreach ($coRekenings as $rekParent => $coRekening) {
            foreach ($coRekening as $item) {
                $allRekenings[] = $item;
            }
        }
        // arrPrint($allRekenings);
        asort($allRekenings);
        $selector_rekening = "";
        $selector_rekening .= "<select name='txtRekening' id='txtRekening' class='form-control'>";
        $selector_rekening .= "<option value=''>pilih rekening</option>";
        foreach ($allRekenings as $keyRkening) {
            // -------------------------------------------------------------------------------------
            if ($keyRkening == $rekening) {
                $selected_rek = "selected ";
                $selected_rek .= " style='color: crimson;'";
            }
            else {
                $selected_rek = "";
                $selected_rek .= "";
            }
            if (in_array($keyRkening, $cRekeningAll)) {
                $selected_rek .= " style='color: #FF55FF;'";
            }
            else {
                $selected_rek .= "";
            }
            // -------------------------------------------------------------------------------------
            $selector_rekening .= "<option value='$keyRkening' $selected_rek>$keyRkening</option>";
        }
        $selector_rekening .= "</select>";
        // --------------------------------------------------------------------------------------------
        /*form editor*/
        if ($role_reult) {
            $html = "";
            $html .= form_open_multipart('akunting/Coa/coa_insert_coa', 'id="form" target="result"');
            $html .= "<div id=\"newData\" class='col-md-7'>";
            $html .= "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"10\">";
            // --------------------------------------------------------------------------------------------
            $html .= "<tr>";
            $html .= "<td width='70px' style='text-align: right;padding-right: 5px;'>rekening</td>";
            $html .= "<td>";
            $html .= $selector_rekening;
            $html .= "</td>";
            $html .= "</tr>";
            // --------------------------------------------------------------------------------------------
            $html .= "<tr>
                <td width='70px' style='text-align: right;padding-right: 5px;'>H Code</td>
                <td><input type=\"text\" name=\"txtHeadCode\" id=\"txtHeadCode\" class=\"form-control\"  value=\"" . $head_code . "\" rreadonly=\"readonly\" maxlength='13'/>
                <input type=\"hidden\" name=\"HeadCode\" id=\"HeadCode\" class=\"form_input\" value=\"" . $head_code . "\"/>
                </td>
              </tr>";
            $html .= "<tr>
                <td style='text-align: right;padding-right: 5px;'>H Name</td>
                <td><input type=\"text\" name=\"txtHeadName\" autocomplete='off' required autofocus id=\"txtHeadName\" class=\"form-control\" value=\"" . $head_name . "\"/>
                  <input type=\"hidden\" name=\"HeadName\" id=\"HeadName\" class=\"form_input\" value=\"" . $head_name . "\"/>
                </td>
              </tr>";
            $html .= "<tr>
                <td>Parent Head</td>
                <td><input type=\"text\" name=\"txtPHead\" id=\"txtPHead\" class=\"form-control\" readonly=\"readonly\" value=\"" . $p_head_name . "\"/></td>
              </tr>";
            $html .= "<tr>
                <td style='text-align: right;padding-right: 5px;'>H Level</td>
                <td><input type=\"text\" name=\"txtHeadLevel\" id=\"txtHeadLevel\" class=\"form-control\" readonly=\"readonly\" value=\"" . $head_level . "\"/></td>
              </tr>";
            $html .= "<tr>
                <td style='text-align: right;padding-right: 5px;'>H Type</td>
                <td><input type=\"text\" name=\"txtHeadType\" id=\"txtHeadType\" class=\"form-control\" readonly=\"readonly\" value=\"" . $head_type . "\"/></td>
              </tr>";

            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<td>";

            $html .= "<div style='padding: 10px;'>";

            $html .= "<input type=\"checkbox\" name=\"IsTransaction\" value=\"1\" id=\"IsTransaction\" size=\"28\" onchange=\"IsTransaction_change()\"";
            if ($role_reult->is_transaction == 1) {
                $html .= "checked";
            }
            $html .= "/><label for=\"IsTransaction\">&nbsp; Transaksional</label> &nbsp;
                     <input type=\"checkbox\" value=\"1\" name=\"IsActive\" id=\"IsActive\" size=\"28\"";
            if ($role_reult->is_active == 1) {
                $html .= "checked";
            }
            $html .= "/><label for=\"IsActive\">&nbsp; Active</label> &nbsp;";

            $html .= "<input type=\"checkbox\" value=\"1\" name=\"IsGL\" id=\"IsGL\" size=\"28\" onchange=\"IsGL_change();\"";
            if ($role_reult->is_gl == 1) {
                $html .= "checked";
            }
            $html .= "/><label for=\"IsGL\">&nbsp; General Ladger</label> &nbsp;";

            foreach ($showFilters as $kolom) {
                $html .= "<label for='$kolom'><input type='checkbox' value='1' name='$kolom' id='$kolom' size='28' onchange='IsGL_change();'";
                if ($role_reult->$kolom == 1) {
                    $html .= "checked";
                }
                $html .= "/>&nbsp; " . $labels[$kolom] . "</label> &nbsp;";
            }

            $html .= "</div>";

            $html .= "</td>";
            $html .= "</tr>";

            $html .= "<tr><td colspan='2'>&nbsp;</td></tr>";
            $html .= "<tr>
                    <td>&nbsp;</td>
                    <td>";
            $html .= "<input type=\"button\" class='btn btn-success' name=\"btnNew\" id=\"btnNew\" value=\"+ Data Baru\" onClick=\"newHeaddata('" . $head_code . "')\" />
                      &nbsp;<input type=\"submit\" class='btn btn-primary pull-right' name=\"btnSave\" id=\"btnSave\" value=\"Save\" disabled=\"disabled\"/>&nbsp;";
            $html .= "&nbsp;<input type=\"submit\" class='btn btn-danger pull-right' name=\"btnSavef5\" id=\"btnSavef5\" value=\"Save + F5\" disabled=\"disabled\"/>&nbsp;";

            $html .= " <input type=\"submit\" class='btn btn-primary pull-right' name=\"btnUpdate\" id=\"btnUpdate\" value=\"Update\" />";
            $html .= " <input type=\"submit\" class='btn btn-danger pull-right' name=\"btnUpdatef5\" id=\"btnUpdatef5\" value=\"Update + F5\" />";
            $html .= "</tr>";
            $html .= "</table>";
            $html .= "</div>";
            $html .= "</form>";


            /* ------------------------------------------------------
             * penampil anakan per induk yg dipilih
             * ------------------------------------------------------*/
            $condites = array(
                "p_head_name" => $head_code
            );
            $this->db->order_by("head_code", "asc");
            $role_result = $CI->MdlAccounts->lookupByCondition($condites)->result();
            // showLast_query("lime");
            // arrPrintPink($role_result);
            $html .= "<div class='list-group col-md-5'>";
            $html .= "<a href='#' class='list-group-item list-group-item-action active'><b class='text-light'>$head_code</b> - $head_name</a>";
            foreach ($role_result as $item) {
                $head_codes = $item->head_code;
                $head_names = $item->head_name;
                $rekening = $item->rekening;
                $rekening_g = isset($rekening) ? "<span style='color: coral;'>$rekening</span> - " : "";
                $html .= "<a href='#' class='list-group-item list-group-item-action' style='padding: 3px 10px;'><b class='text-red'>$head_codes</b> - $rekening_g $head_names</a>";
            }
            // $html .= "<div class='box-body'>999</div>";
            $html .= "</div>";
        }

        echo json_encode($html);
    }

    public function coa_newform($id)
    {
        // $id = substr($id,0,1) == 0 ? "0$id" : $id;
        // cekHere($id);
        $co = new MdlAccounts();
        $newdata = $this->db->select('*')
            ->from($co->getTableName())
            ->where('head_code', $id)
            ->get()
            ->row();
        $lat_level = $newdata->head_level;
        $new_level = $lat_level + 1;
        // arrPrint($newdata);
        $newidsinfo = $this->db->select('*,count(head_code) as hc')
            ->from($co->getTableName())
            ->where('p_head_name', $newdata->head_code)
            ->get()
            ->row();

        // $ncGlobal = sprintf("%05d", $num);
        $level_digids = array(
            1 => 1,
            2 => 10,
            3 => 10,
            4 => 10,
            5 => 10,
            6 => 10,
            7 => 1, // untuk pembantu
        );
        // $factor_level = 1;
        $factor_level = $level_digids[$new_level];
        $nid = $newidsinfo->hc * $factor_level;
        $n = $nid + 1 * $factor_level;
        // cekBiru("$nid");
        // cekHere($newdata->head_level + 1);
        // cekHere($id);
        if ($id == "0") {
            $HeadCode = $n;
        }
        else
            // if ($n / 10 < 1)
            //     if (($new_level) > 2) {
            //         $HeadCode = $id . digit_5($n);
            //     }
            //     else {
            //         $HeadCode = $id . "0" . $n;
            //     }
            // else
        {
            if ($factor_level == 1) {
                $HeadCode = $id . digit_5($n);
            }
            elseif (($n / $factor_level) < 10) {
                $HeadCode = $id . "0" . $n;
            }
            else {
                $HeadCode = $id . $n;
            }
        }


        $info['n'] = $n;
        $info['flevel'] = $factor_level;
        $info['headcode'] = $HeadCode;
        $info['rowdata'] = $newdata;
        $info['headlabel'] = $new_level;

        echo json_encode($info);
    }

    public function coa_insert_coa()
    {
        echo lgShowAlert("tunggu ya");
        $this->load->model('Mdls/MdlAccounts');
        $ac = new MdlAccounts();
        $showFilters = $ac->getShowFilters();
        // arrPrint($_POST);
        // arrPrintPink($_REQUEST);

        $rekening = $this->input->post('txtRekening', TRUE);
        $headcode = $this->input->post('txtHeadCode', TRUE);
        $HeadName = $this->input->post('txtHeadName', TRUE);
        $PHead = $this->input->post('txtPHead', TRUE);
        $HeadLevel = $this->input->post('txtHeadLevel', TRUE);
        $txtHeadType = $this->input->post('txtHeadType', TRUE);
        $isact = $this->input->post('IsActive', TRUE);
        $IsActive = (!empty($isact) ? $isact : 0);
        $trns = $this->input->post('IsTransaction', TRUE);
        $IsTransaction = (!empty($trns) ? $trns : 0);
        $isgl = $this->input->post('IsGL', TRUE);
        $IsGL = (!empty($isgl) ? $isgl : 0);
        $createby = my_id();
        $createdate = date('Y-m-d H:i:s');

        $postData = array(
            'rekening' => $rekening,
            'head_code' => $headcode,
            'head_name' => ucwords($HeadName),
            'p_head_name' => $PHead,
            'p_head_code' => $PHead,
            'head_level' => $HeadLevel,
            'head_type' => $txtHeadType,
            'is_active' => $IsActive,
            'is_transaction' => $IsTransaction,
            'is_gl' => $IsGL,
            'is_budget' => 0,
            'create_by' => $createby,
            'create_date' => $createdate,
        );
        foreach ($showFilters as $showFilter) {
//            cekBiru("$showFilter :: " . $this->input->post($showFilter, TRUE));
            $postData[$showFilter] = $this->input->post($showFilter, TRUE) == "" ? 0 : $this->input->post($showFilter, TRUE);
        }
        // $upinfo = $this->db->select('*')
        //     ->from('acc_coa')
        //     ->where('head_code', $headcode)
        //     ->get()
        //     ->row();
        //
        // arrPrint($upinfo);
//         arrPrint($postData);

        $this->db->trans_begin();

        $condites = array(
            "head_code" => $headcode
        );
        $upinfo = $ac->lookupByCondition($condites)->result();
        // showLast_query("kuning");
        // arrPrintPink($upinfo);
        if (empty($upinfo)) {
            // $this->db->insert('acc_coa', $postData);
            $ac->addData($postData);
            // showLast_query("lime");
        }
        else {

            $hname = $this->input->post('HeadName', TRUE);
            $hname_f = ucwords($hname);
            $updata = array(
                'p_head_name' => $HeadName,
            );

            /* --------------------------------------------
             * update parent
             * --------------------------------------------*/
            $this->db->where('head_code', $headcode)
                ->update($ac->getTableName(), $postData);
            showLast_query("lime");
            /* --------------------------------------------
             * update anak
             * --------------------------------------------*/
            $this->db->where('p_head_name', $hname_f)
                ->update($ac->getTableName(), $updata);
            showLast_query("merah");
        }

//         matiHere();
        // redirect($_SERVER['HTTP_REFERER']);
        echo lgShowSuccess("", "data berhasil disimpan <p style='font-size: .6em'>sorry ya .. refresh dewe</p>");

        // matiHere(__LINE__);
        $this->db->trans_commit();

        if (isset($_POST['btnSavef5']) || isset($_POST['btnUpdatef5'])) {
            echo lgShowSuccess("", "data berhasil disimpan <p style='font-size: .6em'></p>");
            topReload(700);
        }

        // topReload();
    }

    public function doSyncDataCoa()
    {
        $mdlNama = isset($_GET['mdl']) ? $_GET['mdl'] : matiHere("mdl silahkan di kirim via get (mdl=Mdl....)");

        $modelToCoa = array(
            "MdlProduk",
            "MdlSupplies",
        );

        $this->load->model("Mdls/$mdlNama");
        $mainObj = $sdt = new $mdlNama();

        // $this->db->limit(1);
        $this->db->select(array("id", "coa_code", "nama"));
        $sdtCondites = array(
            "coa_code" => null
        );
        $srcDatas = $sdt->lookupByCondition($sdtCondites)->result();
        showLast_query("kuning", "jml data :: " . sizeof($srcDatas));
        // arrPrint(sizeof($srcDatas));

        $this->db->trans_begin();
        if (method_exists($mainObj, "getConnectingData")) {

            $this->load->library("LibCoa");
            $lc = new LibCoa();

            foreach ($srcDatas as $srcData) {

                /**/
                $lc->setMainObject($mainObj);
                $lc->setMainDatas($srcData);
                $lc->setExternalId($srcData->id);
                $lc->addDataCoa();
            }
        }
        else {
            cekLime("belum ada methode getConnectingData");
        }
        // matiDisini(__LINE__);
        $this->db->trans_commit();

        echo json_encode($this->db->last_query());
    }

    public function doResetCoaData()
    {
        // $mdlNama = "MdlPettycashAccount";
        $mdlNama = isset($_GET['mdl']) ? $_GET['mdl'] : matiHere("mdl silahkan di kirim via get (mdl=Mdl....)");

        $this->load->model("Mdls/$mdlNama");
        $mainObj = $sdt = new $mdlNama();

        // $this->db->limit(1);
        $this->db->select(array("id", "coa_code", "nama"));
        $sdtCondites = array(
            "coa_code <>" => null
        );
        $srcDatas = $sdt->lookupByCondition($sdtCondites)->result();

        arrPrint(sizeof($srcDatas));

        $getConns = $mainObj->getConnectingData();

        $mdlAccount = "MdlAccounts";
        $mdlAccountParams = $getConns[$mdlAccount];
        $extern_jenis = $mdlAccountParams['fields']['extern_jenis']['str'];

        // matiHere(__LINE__);

        $this->db->trans_begin();
        foreach ($srcDatas as $srcData) {
            $udtCondites = array(
                "id" => $srcData->id
            );
            $updDatas = array(
                "coa_code" => null
            );

            $sdt->updateData($udtCondites, $updDatas);
            showLast_query("kuning");
            $this->load->model("Mdls/$mdlAccount");
            $ac = new MdlAccounts();

            $delCondites = array(
                "extern_id" => $srcData->id,
                "extern_jenis" => $extern_jenis,
            );
            $ac->deleteData($delCondites);
            showLast_query("hitam");
        }

        // matiDisini(__LINE__);
        $this->db->trans_commit();
    }
}