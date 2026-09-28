<?php
$showPersediaanBrandSummary = isset($showPersediaanBrandSummary) ? (bool)$showPersediaanBrandSummary : false;
$persediaanBrandSummary = isset($persediaanBrandSummary) && is_array($persediaanBrandSummary) ? $persediaanBrandSummary : array();
$persediaanBrandCurrent = isset($persediaanBrandCurrent) ? trim((string)$persediaanBrandCurrent) : "";
$persediaanBrandClearUrl = isset($persediaanBrandClearUrl) ? trim((string)$persediaanBrandClearUrl) : "";
$persediaanBrandViewMode = isset($persediaanBrandViewMode) ? strtolower(trim((string)$persediaanBrandViewMode)) : "chip";
if ($persediaanBrandViewMode != "dropdown") {
    $persediaanBrandViewMode = "chip";
}
$persediaanBrandViewOptions = isset($persediaanBrandViewOptions) && is_array($persediaanBrandViewOptions) ? $persediaanBrandViewOptions : array(
    "chip" => "Chip",
    "dropdown" => "Dropdown",
);
$persediaanBrandViewUrls = isset($persediaanBrandViewUrls) && is_array($persediaanBrandViewUrls) ? $persediaanBrandViewUrls : array();

if (!$showPersediaanBrandSummary || sizeof($persediaanBrandSummary) == 0) {
    return;
}

$brandViewModeEsc = htmlspecialchars($persediaanBrandViewMode, ENT_QUOTES);
$isBrandFilterActive = ($persediaanBrandCurrent != "");
$noteChip = $isBrandFilterActive
    ? "Filter merek aktif: klik merek lain untuk mengganti fokus detail produk."
    : "Klik merek untuk memfilter tabel detail produk tanpa mengubah data transaksi.";
$noteDropdown = $isBrandFilterActive
    ? "Filter merek aktif: pilih merek lain dari dropdown untuk mengganti fokus detail produk."
    : "Pilih merek dari dropdown untuk memfilter tabel detail produk tanpa mengubah data transaksi.";

$showChipView = ($persediaanBrandViewMode == "chip");
$chipContainerStyle = $showChipView
    ? "display:flex; flex-wrap:wrap; gap:7px;"
    : "display:none; flex-wrap:wrap; gap:7px;";
$dropdownContainerStyle = $showChipView
    ? "display:none; flex-wrap:wrap; align-items:center; gap:8px;"
    : "display:flex; flex-wrap:wrap; align-items:center; gap:8px;";
$noteChipStyle = $showChipView
    ? "margin-top:6px; color:#4f7563; font-size:12px; display:block;"
    : "margin-top:6px; color:#4f7563; font-size:12px; display:none;";
$noteDropdownStyle = $showChipView
    ? "margin-top:6px; color:#4f7563; font-size:12px; display:none;"
    : "margin-top:6px; color:#4f7563; font-size:12px; display:block;";
?>
<div class='js-brand-panel-body' data-brand-view-mode='<?php echo $brandViewModeEsc; ?>'>
    <div style='display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin-bottom:6px;'>
        <span style='font-weight:600; color:#2b6f53;'><i class='fa fa-tag'></i> Filter Merek:</span>
        <?php foreach ($persediaanBrandViewOptions as $brandViewModeKey => $brandViewModeLabel): ?>
            <?php
            $brandViewModeKeyNorm = strtolower(trim((string)$brandViewModeKey));
            if ($brandViewModeKeyNorm != "chip" && $brandViewModeKeyNorm != "dropdown") {
                continue;
            }
            $brandViewLabel = trim((string)$brandViewModeLabel);
            if ($brandViewLabel == "") {
                $brandViewLabel = ucwords(str_replace("_", " ", $brandViewModeKeyNorm));
            }
            $brandViewUrl = isset($persediaanBrandViewUrls[$brandViewModeKey]) ? trim((string)$persediaanBrandViewUrls[$brandViewModeKey]) : "";
            if ($brandViewUrl == "") {
                continue;
            }
            $brandViewUrlEsc = htmlspecialchars($brandViewUrl, ENT_QUOTES);
            $brandViewLabelEsc = htmlspecialchars($brandViewLabel, ENT_QUOTES);
            $brandViewModeKeyEsc = htmlspecialchars($brandViewModeKeyNorm, ENT_QUOTES);
            $isBrandViewActive = ($persediaanBrandViewMode == $brandViewModeKeyNorm);
            $brandViewClass = $isBrandViewActive ? "label label-success" : "label label-default";
            ?>
            <a href='<?php echo $brandViewUrlEsc; ?>' class='<?php echo $brandViewClass; ?> js-brand-ajax-link js-brand-view-toggle' data-brand-action='view' data-brand-view='<?php echo $brandViewModeKeyEsc; ?>' style='padding:5px 8px; text-decoration:none;'><?php echo $brandViewLabelEsc; ?></a>
        <?php endforeach; ?>
        <?php if ($persediaanBrandCurrent != "" && $persediaanBrandClearUrl != ""): ?>
            <?php $persediaanBrandClearUrlEsc = htmlspecialchars($persediaanBrandClearUrl, ENT_QUOTES); ?>
            <a href='<?php echo $persediaanBrandClearUrlEsc; ?>' class='label label-default js-brand-ajax-link' data-brand-action='clear' style='padding:5px 8px; text-decoration:none;'>Tampilkan Semua</a>
        <?php endif; ?>
    </div>

    <div class='js-brand-view-wrap js-brand-view-dropdown' style='<?php echo $dropdownContainerStyle; ?>'>
        <select class='form-control js-brand-dropdown selectpicker show-tick' data-live-search='true' data-live-search-normalize='true' data-size='9' data-width='100%' style='min-width:350px; max-width:100%;'>
            <option value=''>Pilih merek untuk membuka detail...</option>
            <?php foreach ($persediaanBrandSummary as $brandSpec): ?>
                <?php
                $brandLabel = isset($brandSpec['merek']) ? trim((string)$brandSpec['merek']) : "";
                if ($brandLabel == "") {
                    continue;
                }
                $brandUrl = isset($brandSpec['url']) ? (string)$brandSpec['url'] : "#";
                $brandCount = isset($brandSpec['count']) ? (int)$brandSpec['count'] : 0;
                $brandQty = isset($brandSpec['qty']) ? (float)$brandSpec['qty'] : 0;
                $brandSaldo = isset($brandSpec['saldo']) ? (float)$brandSpec['saldo'] : 0;
                $brandActive = isset($brandSpec['active']) ? (bool)$brandSpec['active'] : false;

                $brandUrlEsc = htmlspecialchars($brandUrl, ENT_QUOTES);
                $brandOptionTxt = $brandLabel
                    . " | " . number_format($brandCount, 0, ",", ".") . " item"
                    . " | Qty " . number_format($brandQty, 2, ",", ".")
                    . " | Rp " . number_format($brandSaldo, 0, ",", ".");
                $brandOptionEsc = htmlspecialchars($brandOptionTxt, ENT_QUOTES);
                $selectedAttr = $brandActive ? " selected='selected'" : "";
                ?>
                <option value='<?php echo $brandUrlEsc; ?>'<?php echo $selectedAttr; ?>><?php echo $brandOptionEsc; ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class='js-brand-view-wrap js-brand-view-chip' style='<?php echo $chipContainerStyle; ?>'>
        <?php foreach ($persediaanBrandSummary as $brandSpec): ?>
            <?php
            $brandLabel = isset($brandSpec['merek']) ? trim((string)$brandSpec['merek']) : "";
            if ($brandLabel == "") {
                continue;
            }
            $brandUrl = isset($brandSpec['url']) ? (string)$brandSpec['url'] : "#";
            $brandCount = isset($brandSpec['count']) ? (int)$brandSpec['count'] : 0;
            $brandQty = isset($brandSpec['qty']) ? (float)$brandSpec['qty'] : 0;
            $brandSaldo = isset($brandSpec['saldo']) ? (float)$brandSpec['saldo'] : 0;
            $brandActive = isset($brandSpec['active']) ? (bool)$brandSpec['active'] : false;

            $brandStyle = $brandActive
                ? "display:block; min-width:170px; border:1px solid #4ea97f; background:#dff3e8; border-radius:5px; padding:6px 8px; text-decoration:none;"
                : "display:block; min-width:170px; border:1px solid #c8dacf; background:#ffffff; border-radius:5px; padding:6px 8px; text-decoration:none;";

            $brandLabelEsc = htmlspecialchars($brandLabel, ENT_QUOTES);
            $brandUrlEsc = htmlspecialchars($brandUrl, ENT_QUOTES);
            $brandMetaTxt = number_format($brandCount, 0, ",", ".") . " item | Qty " . number_format($brandQty, 2, ",", ".") . " | Rp " . number_format($brandSaldo, 0, ",", ".");
            $brandMetaEsc = htmlspecialchars($brandMetaTxt, ENT_QUOTES);
            ?>
            <a href='<?php echo $brandUrlEsc; ?>' class='js-brand-ajax-link' data-brand-action='select' style='<?php echo $brandStyle; ?>'>
                <div style='font-weight:600; color:#2b6f53; line-height:1.2;'><?php echo $brandLabelEsc; ?></div>
                <div style='font-size:11px; color:#5c7f6c; margin-top:2px;'><?php echo $brandMetaEsc; ?></div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class='js-brand-note-chip' style='<?php echo $noteChipStyle; ?>'><?php echo htmlspecialchars($noteChip, ENT_QUOTES); ?></div>
    <div class='js-brand-note-dropdown' style='<?php echo $noteDropdownStyle; ?>'><?php echo htmlspecialchars($noteDropdown, ENT_QUOTES); ?></div>
</div>
