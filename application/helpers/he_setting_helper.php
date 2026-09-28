<?php


function satuan()
{

    $satuan = array(
        "g" => "gram",
        "ons" => "ons",
        "kg" => "kilogram",
        "kw" => "kwintal",
        "T" => "ton",
        "ml" => "mililiter",
        "l" => "liter",
        "unit" => "unit",
    );
    $satuan_object = array();
    foreach ($satuan as $k => $v) {
        $satuan_object[] = (object)array(
            "id" => $k,
            "nama" => $v,
        );
    }

    return $satuan_object;
}

function userJenisParent()
{

    $jenis = array(
        "superman" => "root",
        "owner" => "viewer",
        "admin" => "holding",
        "manager" => "manager",
        "spv" => "supervisor",
        "purcasing" => "purcasing pembelian",
        "gudang" => "gudang",
        "qc" => "quality control",
        "kasir" => "kasir",
        "seller" => "marketing",
        "supplier" => "supplier",
    );
    $jenis_object = array();
    foreach ($jenis as $k => $v) {
        $jenis_object[] = (object)array(
            "id" => $k,
            "nama" => $v,
        );
    }

    return $jenis_object;
}

/*-----------------------------
 * default passsword diambil dari confic heWebs
 * -----------------------*/
function createDefaultPassword()
{
    $ci = &get_instance();
    $ci->load->config("heWebs");
    $var = $ci->config->item('logins')['defaultPassword'];

    return $var;
}

function defaultPassword()
{
    return createDefaultPassword();
}

// ====================end============================

function createAccessData($arrayData, $id, $isInsert)
{
    $ci = &get_instance();
    $ci->load->helper("he_access_right");
    $trlName = $ci->uri->segment(4);
    $mdlName = "Mdl" . $trlName;
    $transaksiUI = $ci->config->item("heTransaksi_ui");
    $selectedPlace = $ci->config->item("dataExtended")[$mdlName]["aliasPlace"];
    $groupAlias = $ci->config->item($selectedPlace);
    $extData = $ci->config->item("dataExtended")[$mdlName]["access"];
    $ci->load->model("Mdls/$extData");
    $e = new $extData;
    $groupAcess = $e->callGroupAccess();
    $availTransaction = callAvailTransaction();
//    arrPrint($groupAcess);
    switch ($isInsert) {
        case "true":
//            arrPrint($arrayData);
            foreach ($arrayData as $grName) {
//                cekBiru($grName);
                if (isset($groupAcess[$grName])) {
                    foreach ($groupAcess[$grName] as $mnCat => $catData) {
                        foreach ($catData as $steps => $steps_label) {
                            $mnLabel = $transaksiUI[$mnCat]["label"];
                            $dataAccess = array(
                                "employee_id" => $id,
                                "menu_label" => "$mnLabel",
                                "menu_category" => $mnCat,
                                "author" => $ci->session->login['id'],
                                "cabang_id" => $ci->session->login['cabang_id'],
                                "steps" => $steps,
                                "steps_label" => $steps_label,
                                "group_label" => $groupAlias[$grName],
                                "group_name" => $grName,
                                "steps_code" => $availTransaction[$mnCat][$steps]['target'],
                            );
                            $e->addData($dataAccess, $e->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));

                            cekHitam($ci->db->last_query());
                        }
                    }
                }
            }
            break;
        case "false":
            $e->addFilter("employee_id='$id'");
            $existMenu = $e->lookupAll()->result();
            $temp = array();
            foreach ($existMenu as $data) {
                $temp[$data->group_name] = $data->group_label;
            }

            foreach ($arrayData as $grName) {
                if (!isset($temp[$grName])) {
                    cekMerah("insert $grName");
                    if (isset($groupAcess[$grName])) {
                        foreach ($groupAcess[$grName] as $mnCat => $catData) {
                            cekLime($mnCat);
                            foreach ($catData as $steps => $steps_label) {
                                $mnLabel = $transaksiUI[$mnCat]["label"];
                                $dataAccess = array(
                                    "employee_id" => $id,
                                    "menu_label" => "$mnLabel",
                                    "menu_category" => $mnCat,
                                    "author" => $ci->session->login['id'],
                                    "cabang_id" => $ci->session->login['cabang_id'],
                                    "steps" => $steps,
                                    "steps_label" => $steps_label,
                                    "group_label" => $groupAlias[$grName],
                                    "group_name" => $grName,
                                    "steps_code" => $availTransaction[$mnCat][$steps]['target'],
                                );
                                $e->addData($dataAccess, $e->getTableName()) or die(lgShowError("Gagal menulis data", __FILE__));
                                cekLime($ci->db->last_query());
                            }
                        }
                    }
                }
                else {
                    cekHitam("$grName allready exist");
                }
            }

            if (sizeof($temp) > sizeof($arrayData)) {
                foreach ($temp as $gName => $gLabel) {
                    if (in_array($gName, $arrayData)) {

                    }
                    else {
                        $where = "employee_id='$id' and group_name='$gName'";
                        $e->deleteData($where);
                        cekHijau($ci->db->last_query());
                    }
                }
            }
            //region cek existted or deleted
            //endregion

            break;
    }

}


?>