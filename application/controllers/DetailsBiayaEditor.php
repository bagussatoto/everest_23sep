<?php

/**
 * Created by PhpStorm.
 * User: widi
 * Date: 16/11/18
 * Time: 16:08
 */
class DetailsBiayaEditor extends CI_Controller
{
    protected $koloms;

    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->login['id'])) {
            gotoLogin();
        }
        validateUserSession($this->session->login['id']);
    }

    // START OF COMPLETE REPEATED LOGIC
    public function edit()
    {

        $prodID = $_GET['sID'];
        $this->load->model("Mdls/MdlProjectKomponenBiayaDetails");
        $this->load->model("Mdls/MdlDtaBiayaProject");
        $this->load->model("Mdls/MdlProdukRakitanPreBiaya");
        $this->load->model("Mdls/MdlDtaBiayaProduksi");
        $this->load->model("Mdls/MdlSupplies");

//        $this->load->model("Mdls/MdlProdukKompositKomposisi");
//        $this->load->model("Mdls/MdlProduk");
//        $this->load->model("Mdls/MdlProdukMergerRakitan");
//        $this->load->model("Mdls/MdlHargaProduk");
//        $this->load->model("Mdls/MdlProdukKategori");
//        $this->load->model("Mdls/MdlFifoProdukJadi");
//        $this->load->model("Mdls/MdlFifoAverage");

//        $selectedPlace = ($_SESSION['login']['cabang_id']);
        $selectedPlace = "-1";
        $o  = new MdlDtaBiayaProduksi();
        $pk = new MdlProjectKomponenBiayaDetails();
        $o2 = new MdlDtaBiayaProject();
        $pcat = new MdlProdukRakitanPreBiaya();
        $sup  = new MdlSupplies();

//        $pp = new MdlHargaProduk();
//        $ff = new MdlFifoProdukJadi();
//        $fa = new MdlFifoAverage();
        //------------------------------------------------------

        $tmpCategori = $pcat->lookupAll()->result();
        $produkCategori = array();
        if (sizeof($tmpCategori) > 0) {
            foreach ($tmpCategori as $catSpec) {
                $produkCategori[$catSpec->id] = $catSpec->nama;
            }
        }

        //------------------------------------------------------
        $oProp = $o->lookupByID($prodID)->result();
        $components = $pk->lookupByPID($prodID)->result();


        //------------------------------------------------------
        $tmpDtaBiaya = $o2->lookupAll()->result();
        $tmpSupp = $sup->lookupAll()->result();
        //------------------------------------------------------

        $tmpBahan = array_merge($tmpSupp, $tmpDtaBiaya);

//        $pp->setFilters(array());
//        $pp->addFilter("status='1'");
//        $pp->addFilter("trash='0'");
//        $pp->addFilter("jenis_value in ('hpp','jual','jual_nppn')");
//        $tmpPriceBahan = $pp->lookupAll()->result();

//        $tmpPrice = array();
//        if (sizeof($tmpPriceBahan) > 0) {
//            foreach ($tmpPriceBahan as $priceData) {
////                arrPrint($priceData);
////                matiHere();
////                $tmpPrice[$priceData->produk_id] = $priceData->nilai;
////                $tmpPrice[$priceData->produk_id][$priceData->jenis_value] = $priceData->nilai;
//                $tmpPrice[$priceData->produk_id][$priceData->jenis_value] = $priceData->nilai;
//            }
//        }

        //------------------------------------------------------

        $cacheBahan = array();
        if (sizeof($tmpBahan) > 0) {
            foreach ($tmpBahan as $rowB) {
                if (isset($rowB->jenis) && $rowB->jenis == "supplies") {
                    $cacheBahan[$rowB->id] = array(
                        "name" => $rowB->nama,
                        "kategori_id" => "",
                        "kategori_nama" => "",
                        "jenis" => $rowB->jenis,
                        "satuan_id" => $rowB->satuan_id,
                        "satuan" => $rowB->satuan,
                    );
                }
                else {
                    $cacheBahan[$rowB->id] = array(
                        "name" => $rowB->nama,
                        "kategori_id" => $rowB->cat_id,
                        "kategori_nama" => isset($produkCategori[$rowB->cat_id]) ? $produkCategori[$rowB->cat_id] : $rowB->cat_nama,
                        "jenis" => "biaya",
                        "satuan_id" => 1,
                        "satuan" => "unit",
                    );
                }
            }
        }

        if (!isset($_SESSION['PROPKGED'][$prodID])) {
            unset($_SESSION['PROPKGED']);
            $_SESSION['PROPKGED'][$prodID] = array();
        }
        if (!isset($_SESSION['PROPKGED'][$prodID]['component'])) {
            $_SESSION['PROPKGED'][$prodID]['component'] = array();
        }

//        $_SESSION['PROPKGED'][$prodID]['component'] = array();

        $_SESSION['PROPKGED'][$prodID]['backLink'] = isset($_GET['backLink']) ? unserialize(base64_decode($_GET['backLink'])) : "";
        if (count($_SESSION['PROPKGED'][$prodID]['component']) == 0) {
            if (count($components) > 0) {
                foreach ($components as $row) {
                    $namaBahan = isset($cacheBahan[$row->biaya_dasar_id]['name']) ? $cacheBahan[$row->biaya_dasar_id]['name'] : (isset($row->biaya_dasar_nama) && $row->biaya_dasar_nama != '' ? $row->biaya_dasar_nama : "item #" . $row->biaya_dasar_id);
                    $catNama = isset($cacheBahan[$row->biaya_dasar_id]['kategori_nama']) ? $cacheBahan[$row->biaya_dasar_id]['kategori_nama'] : (isset($row->cat_nama) ? $row->cat_nama : "");
                    $catId = isset($row->cat_id) ? $row->cat_id : (isset($cacheBahan[$row->biaya_dasar_id]['kategori_id']) ? $cacheBahan[$row->biaya_dasar_id]['kategori_id'] : 0);
                    $jenisBahan = isset($cacheBahan[$row->biaya_dasar_id]['jenis']) ? $cacheBahan[$row->biaya_dasar_id]['jenis'] : (isset($row->jenis) && $row->jenis != '' ? $row->jenis : "biaya");
                    $satuanId = isset($cacheBahan[$row->biaya_dasar_id]['satuan_id']) ? $cacheBahan[$row->biaya_dasar_id]['satuan_id'] : 1;
                    $satuanNama = isset($cacheBahan[$row->biaya_dasar_id]['satuan']) ? $cacheBahan[$row->biaya_dasar_id]['satuan'] : "unit";

                    $_SESSION['PROPKGED'][$prodID]['component'][$row->biaya_dasar_id] = array(
                        "name" => $namaBahan,
                        "jml" => $row->jml,
                        "harga" => $row->harga * 1,
                        "subtotal" => ($row->jml * $row->harga) * 1,
                        "subharga" => ($row->jml * $row->harga) * 1,
                        "cat_nama" => $catNama,
                        "cat_id" => $catId,
                        "biaya_id" => isset($oProp[0]->id) ? $oProp[0]->id : $prodID,
                        "biaya_nama" => isset($oProp[0]->nama) ? $oProp[0]->nama : "",
                        "jenis" => $jenisBahan,
                        "satuan_id" => $satuanId,
                        "satuan" => $satuanNama,
                    );
                }
            }
        }

        $headers = array(
            "no"        => "class='text-center bg-grey-2 text-uppercase'",
            "nama"      => "class='text-center bg-grey-2 text-uppercase'",
            "jenis"     => "class='text-center bg-grey-2 text-uppercase'",
            "COA biaya" => "class='text-center bg-grey-2 text-uppercase'",
            "harga"     => "class='text-center bg-grey-2 text-uppercase'",
            "jml"       => "class='text-center bg-grey-2 text-uppercase'",
            "satuan"    => "class='text-center bg-grey-2 text-uppercase'",
            "subtotal"  => "class='text-center bg-grey-2 text-uppercase'",
            "action"    => "class='text-center bg-grey-2 text-uppercase'",
        );

        $content = "";
        if (count($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            $btnAttr = "";
            $content .= "<table class='table dataTable compact display table-bordered table-striped'>";
            $content .= "<thead>";
            $content .= "<tr>";
            foreach ($headers as $header => $hAttr) {
                $content .= "<th $hAttr>$header</th>";
            }
            $content .= "</tr>";
            $content .= "</thead>";
            $content .= "<tbody>";
            $no = 0;
            $cTab = 100;
            $totalValue = 0;
            $totalHpp = 0;
            $totalHarga = 0;
            foreach ($_SESSION['PROPKGED'][$prodID]['component'] as $id => $eSpec) {
                $no++;
                $cTab++;
                $harga = $eSpec['harga'];
                $jml = $eSpec['jml'];
                $catid = $eSpec['cat_id'];
                $produk_type = $eSpec['jenis'];

                $totalValue += $eSpec['subtotal'];
                $totalHarga += $eSpec['subharga'];

                $content .= "<tr class='item-row' id='row_$id' data-id='$id'>";
                $content .= "<td class='text-right valign-m' valign='middle'>$no</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= isset($eSpec['name']) ? $eSpec['name'] : "undefined";
                $content .= "</td>";

                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= isset($eSpec['jenis']) ? $eSpec['jenis'] : "undefined";
                $content .= "</td>";

                $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<select class='form-control' onchange=\"updateItemCatId('$produk_type', '$prodID', '$id', this.value);\" style='height: 26px;padding: 2px 2px !important;'>";
                $content .= "<option value='0'>==PILIH==</option>";
                foreach ($produkCategori as $cat_id => $cat_nama) {
                    $selected = $cat_id == $catid ? "selected" : "";
                    $content .= "<option $selected value='$cat_id'>$cat_nama</option>";
                }
                $content .= "</select>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td style='width: 120px;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<input type='text' onclick=\"this.select()\" tabindex='$cTab' name='harga[]' value='" . number_format($eSpec['harga']) . "'
                    oninput=\"formatHargaRealtime(this); syncHargaDebounced('$produk_type', '$prodID', '$id', this);\"
                    onkeyup=\"formatHargaRealtime(this);\"
                    onkeydown=\"handleHargaKeydown(event, this);\"
                    onpaste=\"var el = this; setTimeout(function(){ formatHargaRealtime(el); syncHargaDebounced('$produk_type', '$prodID', '$id', el); }, 10);\"
                    onblur=\"updateItemHarga('$produk_type', '$prodID', '$id', this);\"
                    class='form-control text-right row-harga' style='height: 26px;padding: 2px 2px !important;'>";
                $content .= "</td>";

                $content .= "<td style='width: 70px;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<input type='number' onclick=\"this.select()\" tabindex='$cTab' name='jml[]' value='" . number_format($eSpec['jml']) . "'
                    oninput=\"recalculateTotals(); syncJmlDebounced('$produk_type', '$prodID', '$id', this);\"
                    onkeyup=\"recalculateTotals();\"
                    onblur=\"updateItemJml('$produk_type', '$prodID', '$id', this);\"
                    class='form-control text-right row-jml' style='height: 26px;padding: 2px 2px !important;'>";
                $content .= "</td>";

                //-----------------------------------------------------------------
                $content .= "<td class='text-center text-uppercase text-bold' style='text-align:right;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span class='' style='text-transform: uppercase;'>" . ($eSpec['satuan'] != '' ? $eSpec['satuan'] : "<span class='text-red text-bold'>none</span>")  . "</span>";
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td style='text-align:right;vertical-align: middle;padding: 2px;margin: 0px;'>";
                $content .= "<span id='subtotal_$id' class='row-subtotal' style='text-transform: uppercase;'>" . (isset($eSpec['subharga']) ? number_format($eSpec['subharga']) : 0) . "</span>";
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "<td class='text-center valign-m'>";
                $content .= "<a class='text-red' href=\"javascript:void(0)\" onclick=\"removeItemRow('$prodID', '$id');\"><span class='glyphicon glyphicon-remove'></span></a>";
                $content .= "</td>";
                //-----------------------------------------------------------------
                $content .= "</tr>";
            }

            $content .= "</tbody>";
            //region subtotal bawah
            $colspan = sizeof($headers) - 2;
            $content .= "<tfoot>";
            $content .= "<tr>";
            //--------------------------------------
            $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;' colspan='$colspan'>";
            $content .= "<span style='font-size: 18px;' class='text-uppercase text-renggang-5 text-bold pull-left'>total biaya</span>";
            $content .= "</td>";
            //--------------------------------------
            $content .= "<td style='vertical-align: middle;padding: 2px;margin: 0px;'>";
            $content .= "<span id='totalBiayaValue' style='font-size: 15px;' class='text-uppercase text-bold pull-right'>" . number_format($totalHarga) . "</span>";
            $content .= "</td>";
            //--------------------------------------
            $content .= "</tr>";
            $content .= "</tfoot>";
            //endregion

            $content .= "</table class='table'>";

            // Helper script untuk kalkulasi real-time dan AJAX update session
            $content .= "<script>
                function cleanNumber(val) {
                    if (typeof val === 'number') return isNaN(val) ? 0 : val;
                    if (!val) return 0;
                    var s = String(val).trim();
                    var clean = s.replace(/[^0-9]/g, '');
                    return clean ? parseInt(clean, 10) : 0;
                }

                function formatHargaRealtime(el) {
                    var val = el.value;
                    var selectionStart = el.selectionStart;
                    
                    var digitsBefore = 0;
                    for (var i = 0; i < selectionStart; i++) {
                        if (/\\d/.test(val[i])) {
                            digitsBefore++;
                        }
                    }
                    
                    var clean = val.replace(/\\D/g, '');
                    clean = clean.replace(/^0+(?=\\d)/, '');
                    
                    if (!clean) {
                        el.value = '';
                        recalculateTotals();
                        return;
                    }
                    
                    var formatted = clean.replace(/\\B(?=(\\d{3})+(?!\\d))/g, ',');
                    el.value = formatted;
                    
                    var newPos = 0;
                    var digitsCount = 0;
                    for (var j = 0; j < formatted.length; j++) {
                        if (/\\d/.test(formatted[j])) {
                            digitsCount++;
                        }
                        if (digitsCount === digitsBefore) {
                            newPos = j + 1;
                            break;
                        }
                    }
                    if (digitsCount < digitsBefore || (digitsBefore === 0 && clean.length > 0)) {
                        if (digitsBefore === 0) {
                            newPos = 0;
                        } else {
                            newPos = formatted.length;
                        }
                    }
                    
                    if (typeof el.setSelectionRange === 'function') {
                        el.setSelectionRange(newPos, newPos);
                    }
                    
                    recalculateTotals();
                }

                function handleHargaKeydown(e, el) {
                    if (e.key === 'Backspace' || e.keyCode === 8) {
                        var start = el.selectionStart;
                        var end = el.selectionEnd;
                        if (start === end && start > 0) {
                            if (el.value.charAt(start - 1) === ',') {
                                e.preventDefault();
                                var val = el.value;
                                el.value = val.slice(0, start - 2) + val.slice(start - 1);
                                el.setSelectionRange(start - 2, start - 2);
                                formatHargaRealtime(el);
                            }
                        }
                    }
                }

                var syncHargaTimers = {};
                function syncHargaDebounced(tpID, sID, bID, inputEl) {
                    var timerKey = tpID + '_' + sID + '_' + bID;
                    if (syncHargaTimers[timerKey]) {
                        clearTimeout(syncHargaTimers[timerKey]);
                    }
                    var numVal = cleanNumber(inputEl.value);
                    syncHargaTimers[timerKey] = setTimeout(function() {
                        var url = '" . base_url() . "_detailsBiayaEditor/addItem?tpID=' + encodeURIComponent(tpID) + '&sID=' + encodeURIComponent(sID) + '&bID=' + encodeURIComponent(bID) + '&key=harga&harga=' + numVal;
                        $.get(url, function(res) {
                            recalculateTotals();
                        });
                    }, 500);
                }

                var syncJmlTimers = {};
                function syncJmlDebounced(tpID, sID, bID, inputEl) {
                    var timerKey = tpID + '_' + sID + '_' + bID;
                    if (syncJmlTimers[timerKey]) {
                        clearTimeout(syncJmlTimers[timerKey]);
                    }
                    var numVal = cleanNumber(inputEl.value);
                    syncJmlTimers[timerKey] = setTimeout(function() {
                        var url = '" . base_url() . "_detailsBiayaEditor/addItem?tpID=' + encodeURIComponent(tpID) + '&sID=' + encodeURIComponent(sID) + '&bID=' + encodeURIComponent(bID) + '&key=jml&jml=' + numVal;
                        $.get(url, function(res) {
                            recalculateTotals();
                        });
                    }, 500);
                }

                function updateItemHarga(tpID, sID, bID, inputEl) {
                    var timerKey = tpID + '_' + sID + '_' + bID;
                    if (syncHargaTimers[timerKey]) {
                        clearTimeout(syncHargaTimers[timerKey]);
                    }
                    var numVal = cleanNumber(inputEl.value);
                    var formatted = (typeof addCommas === 'function' ? addCommas(numVal) : numVal.toLocaleString('en-US'));
                    inputEl.value = (numVal > 0 ? formatted : (inputEl.value === '' ? '' : '0'));
                    var url = '" . base_url() . "_detailsBiayaEditor/addItem?tpID=' + encodeURIComponent(tpID) + '&sID=' + encodeURIComponent(sID) + '&bID=' + encodeURIComponent(bID) + '&key=harga&harga=' + numVal;
                    $.get(url, function(res) {
                        recalculateTotals();
                    });
                }

                function updateItemJml(tpID, sID, bID, inputEl) {
                    var timerKey = tpID + '_' + sID + '_' + bID;
                    if (syncJmlTimers[timerKey]) {
                        clearTimeout(syncJmlTimers[timerKey]);
                    }
                    var numVal = cleanNumber(inputEl.value);
                    inputEl.value = numVal;
                    var url = '" . base_url() . "_detailsBiayaEditor/addItem?tpID=' + encodeURIComponent(tpID) + '&sID=' + encodeURIComponent(sID) + '&bID=' + encodeURIComponent(bID) + '&key=jml&jml=' + numVal;
                    $.get(url, function(res) {
                        recalculateTotals();
                    });
                }

                function updateItemCatId(tpID, sID, bID, catId) {
                    var url = '" . base_url() . "_detailsBiayaEditor/addItem?tpID=' + encodeURIComponent(tpID) + '&sID=' + encodeURIComponent(sID) + '&bID=' + encodeURIComponent(bID) + '&key=cat_id&cat_id=' + encodeURIComponent(catId);
                    $.get(url, function(res) {
                        recalculateTotals();
                    });
                }

                function removeItemRow(sID, bID) {
                    if (typeof showIframeLoader === 'function') {
                        showIframeLoader('Menghapus Komponen...', 'Memperbarui daftar biaya...');
                    }
                    var url = '" . base_url() . "_detailsBiayaEditor/removeItem/' + encodeURIComponent(sID) + '/' + encodeURIComponent(bID);
                    $.get(url, function(res) {
                        location.reload();
                    });
                }

                function addItemFromList(tpID, sID, bID) {
                    if (typeof showIframeLoader === 'function') {
                        showIframeLoader('Menambahkan Komponen...', 'Memperbarui daftar biaya...');
                    }
                    var url = '" . base_url() . "_detailsBiayaEditor/addItem?tpID=' + encodeURIComponent(tpID) + '&sID=' + encodeURIComponent(sID) + '&bID=' + encodeURIComponent(bID);
                    $.get(url, function(res) {
                        location.reload();
                    });
                }

                function recalculateTotals() {
                    var grandTotal = 0;
                    $('tr.item-row').each(function() {
                        var row = $(this);
                        var harga = cleanNumber(row.find('input[name=\"harga[]\"]').val());
                        var jml = cleanNumber(row.find('input[name=\"jml[]\"]').val());
                        var subtotal = harga * jml;
                        grandTotal += subtotal;
                        var formattedSub = (typeof addCommas === 'function' ? addCommas(subtotal) : subtotal.toLocaleString('en-US'));
                        row.find('.row-subtotal').text(formattedSub);
                    });
                    var formattedGrand = (typeof addCommas === 'function' ? addCommas(grandTotal) : grandTotal.toLocaleString('en-US'));
                    $('#totalBiayaValue').text(formattedGrand);

                    $('#btnProcess').prop('disabled', false).addClass('btn-info').removeClass('disabled');
                    $('#btnKalkulasi').addClass('hidden');
                    try {
                        localStorage.removeItem('btnKalkulasi');
                        localStorage.btnKalkulasi = null;
                    } catch(e) {}
                }

                $(document).ready(function() {
                    try {
                        localStorage.removeItem('btnKalkulasi');
                        localStorage.btnKalkulasi = null;
                    } catch(e) {}
                    $('#btnProcess').prop('disabled', false).addClass('btn-info').removeClass('disabled');
                    $('#btnKalkulasi').addClass('hidden');
                });
            </script>";

        }
        else {
            $content .= "<div class='row text-center' style='border: 0px solid green;'>";
            $content .= "<h2><small>Komponen Biaya Project </small><p class='text-red'>" . (isset($oProp[0]->nama) ? $oProp[0]->nama : "") . "</p> <small>belum ditentukan</small></h2>";
            $content .= "<p class='text-danger'>Silahkan pilih biaya yang diperlukan dari kolom sebelah kiri</p>";
            $content .= "</div>";
            $btnAttr = "disabled";
        }

        $data = array(
            "mode" => "edit_details_biaya",
            "content" => $content,
            "btnAttr" => $btnAttr,
        );
        $this->load->view("editor", $data);
    }
    // END OF COMPLETE REPEATED LOGIC

    // START OF COMPLETE REPEATED LOGIC
    public function save()
    {

        $prodID = $this->uri->segment(3);
        if (!$prodID) {
            $prodID = isset($_POST['sID']) ? $_POST['sID'] : (isset($_GET['sID']) ? $_GET['sID'] : 0);
        }
        $prodID = intval($prodID);

        if (isset($_POST['items'])) {
            $postedItems = json_decode($_POST['items'], true);
            if (is_array($postedItems)) {
                if (!isset($_SESSION['PROPKGED'][$prodID])) {
                    $_SESSION['PROPKGED'][$prodID] = array();
                }
                if (!isset($_SESSION['PROPKGED'][$prodID]['component'])) {
                    $_SESSION['PROPKGED'][$prodID]['component'] = array();
                }
                foreach ($postedItems as $pItem) {
                    $bID = $pItem['id'];
                    $rawHarga = isset($pItem['harga']) ? $pItem['harga'] : 0;
                    $rawJml = isset($pItem['jml']) ? $pItem['jml'] : 1;
                    $newHarga = floatval(str_replace(array(',', '.', ' '), '', $rawHarga));
                    $newJml = floatval(str_replace(array(',', ' '), '', $rawJml));
                    $newCatId = isset($pItem['cat_id']) ? intval($pItem['cat_id']) : 0;

                    if (!isset($_SESSION['PROPKGED'][$prodID]['component'][$bID])) {
                        $_SESSION['PROPKGED'][$prodID]['component'][$bID] = array(
                            "biaya_nama" => "",
                            "name" => isset($pItem['name']) ? $pItem['name'] : "",
                            "cat_id" => $newCatId,
                            "cat_nama" => "",
                            "jml" => $newJml,
                            "harga" => $newHarga,
                            "subtotal" => $newJml * $newHarga,
                            "subharga" => $newJml * $newHarga,
                            "produk_type" => "biaya",
                            "jenis" => "biaya",
                            "satuan" => "unit",
                            "satuan_id" => 1,
                        );
                    } else {
                        $_SESSION['PROPKGED'][$prodID]['component'][$bID]['harga'] = $newHarga;
                        $_SESSION['PROPKGED'][$prodID]['component'][$bID]['jml'] = $newJml;
                        if ($newCatId > 0) {
                            $_SESSION['PROPKGED'][$prodID]['component'][$bID]['cat_id'] = $newCatId;
                        }
                        $_SESSION['PROPKGED'][$prodID]['component'][$bID]['subtotal'] = $newJml * $newHarga;
                        $_SESSION['PROPKGED'][$prodID]['component'][$bID]['subharga'] = $newJml * $newHarga;
                    }
                }
            }
        }

        $this->db->trans_start();

        $activeBahanIDs = array();
        if (isset($_SESSION['PROPKGED'][$prodID]['component']) && sizeof($_SESSION['PROPKGED'][$prodID]['component']) > 0) {
            foreach ($_SESSION['PROPKGED'][$prodID]['component'] as $bahanID => $eSpec) {
                $activeBahanIDs[] = $bahanID;
                $hargaItem = isset($eSpec['harga']) ? floatval($eSpec['harga']) : 0;
                $jmlItem = isset($eSpec['jml']) ? floatval($eSpec['jml']) : 1;
                $catIdItem = isset($eSpec['cat_id']) ? intval($eSpec['cat_id']) : 0;
                $catNamaItem = isset($eSpec['cat_nama']) ? $eSpec['cat_nama'] : "";
                $biayaNamaItem = isset($eSpec['biaya_nama']) ? $eSpec['biaya_nama'] : "";
                $biayaDasarNamaItem = (isset($eSpec['name']) && $eSpec['name'] != '') ? $eSpec['name'] : (isset($eSpec['biaya_dasar_nama']) ? $eSpec['biaya_dasar_nama'] : "");

                // Cek apakah item komponen biaya sudah ada di tabel database
                $this->db->where('biaya_id', $prodID);
                $this->db->where('biaya_dasar_id', $bahanID);
                $existingRow = $this->db->get('project_komponen_biaya_details')->row();

                if ($existingRow) {
                    // Update langsung baris yang sudah ada
                    $updateFields = array(
                        "harga"  => $hargaItem,
                        "jml"    => $jmlItem,
                        "dtime"  => date("Y-m-d H:i:s"),
                        "author" => isset($this->session->login['id']) ? $this->session->login['id'] : 0,
                        "status" => 1,
                        "trash"  => 0,
                    );
                    if ($catIdItem > 0) {
                        $updateFields["cat_id"] = $catIdItem;
                    }
                    if ($catNamaItem != "") {
                        $updateFields["cat_nama"] = $catNamaItem;
                    }
                    if ($biayaDasarNamaItem != "") {
                        $updateFields["biaya_dasar_nama"] = $biayaDasarNamaItem;
                    }
                    if ($biayaNamaItem != "") {
                        $updateFields["biaya_nama"] = $biayaNamaItem;
                    }

                    $this->db->where('id', $existingRow->id);
                    $this->db->update('project_komponen_biaya_details', $updateFields);
                } else {
                    // Insert baris baru jika belum ada
                    $insertData = array(
                        "biaya_id"          => $prodID,
                        "biaya_nama"        => $biayaNamaItem,
                        "biaya_dasar_id"    => $bahanID,
                        "biaya_dasar_nama"  => $biayaDasarNamaItem,
                        "cat_id"            => $catIdItem,
                        "cat_nama"          => $catNamaItem,
                        "jml"               => $jmlItem,
                        "harga"             => $hargaItem,
                        "jenis"             => isset($eSpec['jenis']) ? $eSpec['jenis'] : "biaya",
                        "dtime"             => date("Y-m-d H:i:s"),
                        "author"            => isset($this->session->login['id']) ? $this->session->login['id'] : 0,
                        "status"            => 1,
                        "trash"             => 0,
                    );
                    $this->db->insert('project_komponen_biaya_details', $insertData);
                }
            }
        }

        // Tandai trash=1 untuk item yang sudah tidak ada di komponen aktif
        if (sizeof($activeBahanIDs) > 0) {
            $this->db->where('biaya_id', $prodID);
            $this->db->where_not_in('biaya_dasar_id', $activeBahanIDs);
            $this->db->update('project_komponen_biaya_details', array("trash" => 1));
        } else {
            $this->db->where('biaya_id', $prodID);
            $this->db->update('project_komponen_biaya_details', array("trash" => 1));
        }

        $this->db->trans_complete();

        $transSuccess = $this->db->trans_status();

        // Bersihkan session agar saat reload membaca ulang data terbaru dari database
        $backLink = isset($_SESSION['PROPKGED'][$prodID]['backLink']) ? $_SESSION['PROPKGED'][$prodID]['backLink'] : "";
        unset($_SESSION['PROPKGED'][$prodID]);

        if ($this->input->is_ajax_request() || isset($_POST['items'])) {
            if ($transSuccess === FALSE) {
                $err = $this->db->error();
                echo json_encode(array(
                    "status" => "error",
                    "message" => "Gagal menyimpan database: " . (isset($err['message']) ? $err['message'] : "DB Error"),
                    "prodID" => $prodID
                ));
            } else {
                echo json_encode(array(
                    "status" => "success",
                    "message" => "Data berhasil disimpan",
                    "prodID" => $prodID
                ));
            }
            return;
        }

        $reloadUrl = base_url() . "DetailsBiayaEditor/edit?attached=1&sID=$prodID&backlink=" . urlencode($backLink);
        $actionTarget = "
        <script>
            try { localStorage.removeItem('btnKalkulasi'); localStorage.btnKalkulasi = null; } catch(e){}
            if (typeof top !== 'undefined' && top.$ && top.$('#result2').length > 0) {
                top.$('#result2').attr('src', '$reloadUrl');
            } else if (parent && parent !== window) {
                parent.location.href = '$reloadUrl';
            } else {
                window.location.href = '$reloadUrl';
            }
        </script>";

        echo $actionTarget;
    }
    // END OF COMPLETE REPEATED LOGIC

    public function archive()
    {
        if (!$this->db->table_exists('project_komponen_biaya_details_archive')) {
            return;
        }

        $this->db->select("*");
        $this->db->where("trash=1");
        $existing = $this->db->get("project_komponen_biaya_details")->result();

        $delete = array();
        if (!empty($existing)) {
            foreach ($existing as $exist) {
                $ins = $this->db->insert("project_komponen_biaya_details_archive", $exist);
                if ($ins) {
                    $delete[] = $exist->id;
                }
            }

            if (!empty($delete)) {
                foreach ($delete as $del) {
                    $this->db->delete('project_komponen_biaya_details', array('id' => $del));
                }
            }
        }
    }
}