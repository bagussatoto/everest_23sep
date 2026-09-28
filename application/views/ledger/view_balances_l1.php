<?php

//file view_balances_l1.php

$add_style = "font-size:20px;";
$contens = "";
$p = New Layout("$title", "$subTitle", "application/template/default_ledger.html");

$template = array(
    'table_open'        => '<table id="table" border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
    'thead_open'        => '<thead class="bg-info text-uppercase" style="text-align: center;">',
    'thead_close'       => '</thead>',
    'tfoot_open'        => '<tfoot class="ui-widget-header ui-priority-secondary">',
    'footer_row_start'  => '<tr>',
    'footer_row_end'    => '</tr>',
    'footer_cell_start' => '<th>',
    'footer_cell_end'   => '</th>',
    'tfoot_close'       => '</tfoot>',
    'table_close'       => '</table>',
);

$this->table->set_template($template);
$list_data = "";

$memberships = $_SESSION['login']['membership'];
// region Description searching by php
$list_data .= "<div style='margin-bottom: 15px;'>";
$list_data .= "<div style='display: flex; flex-wrap: wrap; gap: 10px; align-items: center;'>";

$link_excel = base_url() . "ExcelWriter/persediaan/$param_to_excel";

/*---allow to download-------------*/
$allowBtns = array(
    "c_gudang",
    "c_gudang_spv",
    "c_holding"
);

// Container untuk Action Buttons (Excel Download)
$list_data .= "<div style='flex-shrink: 0;'>";
if (isset($param_to_excel)) {
    $btnExcels = array();
    foreach ($memberships as $membership) {
        if (in_array($membership, $allowBtns)) {
            $btnExcels[] = $membership;
        }
    }
    if (isset($btnExcels) && sizeof($btnExcels) > 0) {
        $list_data .= "<button type='button' class='btn btn-success' style='white-space: nowrap;' data-toggle='tooltip' title='Download seluruh data ke Excel' onclick=\"btn_alert_result('Excell','Download data akan muncul setelah beberapa saat diklik','$link_excel');\"><i class='fa fa-file-excel-o'></i> Download Excel</button>";
    } else {
        $list_data .= "<button type='button' disabled class='btn btn-secondary' style='white-space: nowrap;' data-toggle='tooltip' title='Anda tidak memiliki akses untuk download'><i class='fa fa-file-excel-o'></i> Download Excel</button>";
    }
}
$list_data .= "</div>";

$showHutangHierarchy = isset($showHutangHierarchy) ? (bool)$showHutangHierarchy : false;
$hutangHierarchyCurrent = isset($hutangHierarchyCurrent) ? (string)$hutangHierarchyCurrent : "";
$hutangHierarchyLinks = isset($hutangHierarchyLinks) && is_array($hutangHierarchyLinks) ? $hutangHierarchyLinks : array();
$hutangHierarchyNote = isset($hutangHierarchyNote) ? (string)$hutangHierarchyNote : "";
$hutangHierarchyEntityLabel = isset($hutangHierarchyEntityLabel) ? trim((string)$hutangHierarchyEntityLabel) : "entitas";
if ($hutangHierarchyEntityLabel == "") {
    $hutangHierarchyEntityLabel = "entitas";
}
$hutangHierarchyTotals = isset($hutangHierarchyTotals) && is_array($hutangHierarchyTotals) ? $hutangHierarchyTotals : array();
if ($showHutangHierarchy && sizeof($hutangHierarchyLinks) > 0) {
    $list_data .= "<div style='flex: 1 1 100%; background: #fff7ec; border: 1px solid #f0d6ab; padding: 8px 12px; border-radius: 4px;'>";
    $list_data .= "<div style='display: flex; flex-wrap: wrap; align-items: center; gap: 8px;'>";
    $list_data .= "<span style='font-weight: 600; color: #8a5a20;'><i class='fa fa-credit-card'></i> Hutang:</span>";
    foreach ($hutangHierarchyLinks as $rekCode => $hierarchySpec) {
        $hierarchyLabel = isset($hierarchySpec['label']) ? $hierarchySpec['label'] : $rekCode;
        $hierarchyNote = isset($hierarchySpec['note']) ? $hierarchySpec['note'] : "";
        $hierarchyUrl = isset($hierarchySpec['url']) ? $hierarchySpec['url'] : "#";
        $hierarchySaldo = isset($hierarchySpec['saldo']) ? (float)$hierarchySpec['saldo'] : 0;
        $hierarchyCount = isset($hierarchySpec['count']) ? (int)$hierarchySpec['count'] : 0;
        $hierarchyCabangCount = isset($hierarchySpec['cabang_count']) ? (int)$hierarchySpec['cabang_count'] : 0;

        $isActiveHierarchy = ($hutangHierarchyCurrent == $rekCode);
        $hierarchyCardStyle = $isActiveHierarchy
            ? "display:block; min-width:240px; border:1px solid #dca457; background:#fff0d9; border-radius:6px; padding:8px 10px; text-decoration:none;"
            : "display:block; min-width:240px; border:1px solid #e0c9a6; background:#ffffff; border-radius:6px; padding:8px 10px; text-decoration:none;";
        $hierarchyLabelEsc = htmlspecialchars($hierarchyLabel, ENT_QUOTES);
        $hierarchyCodeEsc = htmlspecialchars($rekCode, ENT_QUOTES);
        $hierarchyNoteEsc = htmlspecialchars($hierarchyNote, ENT_QUOTES);
        $hierarchyUrlEsc = htmlspecialchars($hierarchyUrl, ENT_QUOTES);
        $hierarchySaldoEsc = htmlspecialchars("Rp " . number_format($hierarchySaldo, 0, ",", "."), ENT_QUOTES);
        $dimensiTxt = number_format($hierarchyCount, 0, ",", ".") . " " . $hutangHierarchyEntityLabel;
        if ($hierarchyCabangCount > 0) {
            $dimensiTxt .= " | " . number_format($hierarchyCabangCount, 0, ",", ".") . " cabang";
        }
        $dimensiTxtEsc = htmlspecialchars($dimensiTxt, ENT_QUOTES);

        $list_data .= "<a href='$hierarchyUrlEsc' data-toggle='tooltip' title='$hierarchyNoteEsc' style='$hierarchyCardStyle'>";
        $list_data .= "<div style='font-weight:600; color:#8a5a20;'>$hierarchyLabelEsc <small>(" . $hierarchyCodeEsc . ")</small></div>";
        $list_data .= "<div style='font-size:11px; color:#9a7a52; margin-top:2px;'>$hierarchyNoteEsc</div>";
        $list_data .= "<div style='font-size:17px; font-weight:700; color:#8a5a20; margin-top:3px;'>$hierarchySaldoEsc</div>";
        $list_data .= "<div style='font-size:11px; color:#9a7a52;'>$dimensiTxtEsc</div>";
        $list_data .= "</a>";
    }
    $totalHutang = isset($hutangHierarchyTotals['all']) ? (float)$hutangHierarchyTotals['all'] : 0;
    $totalHutangEsc = htmlspecialchars("Rp " . number_format($totalHutang, 0, ",", "."), ENT_QUOTES);
    $entityCountAll = isset($hutangHierarchyTotals['supplier_count']) ? (int)$hutangHierarchyTotals['supplier_count'] : 0;
    $cabangCountAll = isset($hutangHierarchyTotals['cabang_count']) ? (int)$hutangHierarchyTotals['cabang_count'] : 0;
    $dimensiAllTxt = number_format($entityCountAll, 0, ",", ".") . " " . $hutangHierarchyEntityLabel;
    if ($cabangCountAll > 0) {
        $dimensiAllTxt .= " | " . number_format($cabangCountAll, 0, ",", ".") . " cabang";
    }
    $dimensiAllTxtEsc = htmlspecialchars($dimensiAllTxt, ENT_QUOTES);

    $list_data .= "<div style='flex: 1 1 100%; display:flex; flex-wrap:wrap; gap:8px; margin-top:4px;'>";
    $list_data .= "<span class='label label-warning' style='padding:6px 10px; font-size:12px; color:#5f3d13;'>Total Hutang: $totalHutangEsc</span>";
    $list_data .= "<span class='label label-default' style='padding:6px 10px; font-size:12px;'>Dimensi Aktif: $dimensiAllTxtEsc</span>";
    $list_data .= "</div>";
    if (strlen($hutangHierarchyNote) > 0) {
        $hutangHierarchyNoteEsc = htmlspecialchars($hutangHierarchyNote, ENT_QUOTES);
        $list_data .= "<span style='color: #8a6d3b; font-size: 12px;'>" . $hutangHierarchyNoteEsc . "</span>";
    }
    $list_data .= "</div>";
}

// Container untuk Filter Date
if (isset($dateSelected) && ($dateSelected == true)) {
    $list_data .= "<div style='flex-shrink: 0; background: #f8f9fa; padding: 6px 12px; border-radius: 4px; border: 1px solid #ddd;'>";
    $list_data .= "<div style='display: flex; align-items: center; gap: 8px;'>";
    $list_data .= "<span style='font-weight: 500; color: #555;'><i class='fa fa-calendar'></i> Pilih Periode:</span>";
    $list_data .= "<input type='month' class='form-control' style='width: 180px;' value='$defaultDate' min='$oldDate' max='" . date("Y-m") . "' onchange=\"location.href='$thisPage&date='+this.value;\" title='Pilih bulan dan tahun'>";
    $list_data .= "</div>";
    $list_data .= "</div>";
}

// Container untuk Search (memakai sisa ruang) - DIPERBAIKI
$list_data .= "<div style='flex-grow: 1; min-width: 300px; max-width: 500px;'>";
$list_data .= "<div style='display: flex; width: 100%;'>";
$list_data .= "<input type='text' name='q' id='q' class='form-control' value='$q' placeholder='Cari produk...' onfocus='this.select()' onkeydown=\"if(event.key === 'Enter'){document.location.href='" . $thisPage . "&q='+this.value;}\" style='border-top-right-radius: 0; border-bottom-right-radius: 0;'>";
$list_data .= "<button class='btn btn-outline-secondary' type='button' title='Hapus kata kunci' onclick=\"document.location.href='" . $thisPage . "&q=';\" style='border-radius: 0; border-left: none;'>";
$list_data .= "<i class='fa fa-times'></i>";
$list_data .= "</button>";
$list_data .= "<button class='btn btn-primary' type='button' title='Cari' onclick=\"document.location.href='" . $thisPage . "&q='+document.getElementById('q').value;\" style='border-top-left-radius: 0; border-bottom-left-radius: 0;'>";
$list_data .= "<i class='fa fa-search'></i>";
$list_data .= "</button>";
$list_data .= "</div>";
$list_data .= "</div>";

$list_data .= "</div>";
$list_data .= "</div>";
//endregion

$data_total = "";

$i = 0;
$data_total .= "<div class='table-responsive myNewTable'>";
$data_total .= "<table id='myNewTable' class='table dataTable compact nowrap display'>";
$data_total .= "<thead>";
//========================
//========AREA HEADER LEVEL 1==========
$colspan = 0;
$data_total .= "<tr bgcolor='#e5e5e5'>";
$data_total .= "<th xcolspan='$colspan' align='right'>No.</th>";
foreach ($headerFields as $cName => $cValue) {
    if (is_array($cValue)) {
        $label = $cValue["label"];
        $bg_color = $cValue["bg-color"];
    }
    else {
        $label = $cValue;
        $bg_color = "";
    }
    $data_total .= "<th cName='$cName' xcolspan='$colspan' class='text-center text-uppercase' style='color:#555555;padding:3px;background-color:$bg_color;'>";
    $data_total .= "$label";
    $data_total .= "</th>";
}
$data_total .= "</tr>";
$data_total .= "</thead>";

$data_total .= "<tbody>";
$data_total .= "</tbody>";

$data_total .= "<tfoot>";
$data_total .= "<th xcolspan='$colspan' align='right'>-</th>";
foreach ($headerFields as $cName => $cValue) {
    $data_total .= "<th cName='$cName' class='text-uppercase' style=''>";
    $data_total .= "-";
    $data_total .= "</th>";
}
$data_total .= "</tfoot>";

$data_total .= "</table>";
$data_total .= "</div>";

$list_data .= $data_total;

$params = array(
    "fifo"      => "MdlFifoAverage",
    "cabang_id" => my_cabang_id(),
);
$headerFields_json = array_merge(array("no" => "no"), $headerFields);
$paramEs = blobEncode($params);
$linkExcell = base_url() . "ExcelWriter/persediaan/$paramEs";

$p->addTags(array(
    "menu_left"        => callMenuLeft(),
    //                "trans_menu" => callTransMenu(),
    "float_menu_atas"  => callFloatMenu('atas'),
    "float_menu_bawah" => callFloatMenu(),
    "menu_taskbar"     => callMenuTaskbar(),
    "btn_back"         => callBackNav(),
    "content"          => $list_data,
    "profile_name"     => $this->session->login['nama'],
    "link_excel"       => $linkExcell,
    "server"           => $server,
    "server_json"      => $server_json,
    "url_serverside"   => $url_serverside,
    "headerFields"     => json_encode($headerFields_json),
));

$p->setContent($contens);
$p->render();


