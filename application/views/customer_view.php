<?php

/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */

function FormatCreditCard($cc)
{
    // Clean out extra data that might be in the cc
    $cc = str_replace(array('-', ' '), '', $cc);
    // Get the CC Length
    $cc_length = strlen($cc);
    // Initialize the new credit card to contian the last four digits
    $newCreditCard = substr($cc, -4);
    // Walk backwards through the credit card number and add a dash after every fourth digit
    for ($i = $cc_length - 5; $i >= 0; $i--) {
        // If on the fourth character add a dash
        if ((($i + 1) - $cc_length) % 4 == 0) {
            $newCreditCard = '-' . $newCreditCard;
        }
        // Add the current character to the new credit card
        $newCreditCard = $cc[$i] . $newCreditCard;
    }
    // Return the formatted credit card number
    return $newCreditCard;
}

switch ($mode) {
    case "shoppingCart":

        $p = New Layout("$title", "$subTitle", "application/template/customers_view_json.html");
        $content = "";
        $contentx = "";
        $content .= "<table style='margin-top: 0; width:100%;' class='table table-bordered table-condensed'>
                        <thead><tr>
                                <th style='font-size:1.5em;' class='bg-info text-center col-md-1 text-uppercase'>no</th>
                                <th style='font-size:1.5em;' class='bg-info text-center col-md-5 text-uppercase'>description</th>
                                <th style='font-size:1.5em;' class='bg-info text-center col-md-2 text-uppercase'>hrg</th>
                                <th style='font-size:1.5em;' class='bg-info text-center col-md-1 text-uppercase'>jml</th>
                                <th style='font-size:1.5em;' class='bg-info text-center col-md-1 text-uppercase'>disc</th>
                                <th style='font-size:1.5em;' class='bg-info text-center col-md-2 text-uppercase'>sub</th>
                            </tr></thead>
                    <tbody>";

        $total_unit_produk = 0;
        $grand_total = 0;
        $total_diskon_produk = 0;
        $sub_total_diskon_produk = 0;
        $grand_total_tmp = 0;
        $total_item_produk = 0;

        $nama_produk = "--";
        $harga_produk = 0;
        $satuan_produk = "--";
        $jml_produk = 0;
        $diskon_produk = 0;
        $diskon_add = 0;
        $subTotal = 0;
        $additional_diskon = 0;
        $no = 0;


        if (isset($_SESSION['_TR_582']['main'])) {
            $arrMainValue = $_SESSION['_TR_582']['main'];
            if (isset($arrMainValue['add_disc'])) {
                $additional_diskon = $arrMainValue['add_disc'];
            }
        }

//        arrPrint($_SESSION['_TR_582']['items']);

        if (isset($_SESSION['_TR_582']['items']) && count($_SESSION['_TR_582']['items']) > 0) {
            $arrItems = $_SESSION['_TR_582']['items'];
            $arrItemsTampil = array_slice($arrItems, -8);
            //arrPrint($arrItemsTampil);

//            arrPrint($arrItems);

            foreach ($arrItems as $id_produk => $datas) {
                $no++;
                $id_produk = $datas['id'];
                $nama_produk = $datas['nama'];
                $ppn = $datas['ppn'];
                $harga_produk = isset($datas['jual_nppn']) ? ($datas['jual_nppn']) : 0;
                $satuan_produk = $datas['satuan'];
                $jml_produk = $datas['jml'];

                $diskon_produk = isset($datas['disc']) ? $datas['disc'] : 0;
                $diskon_produk_r = isset($datas['disc']) ? ($diskon_produk) : 0;
                //$diskon_add     = isset($datas['add_disc']) ? $datas['add_disc'] : 0;
                $subTotal = isset($datas['disc']) ? (($harga_produk - $diskon_produk_r) * $jml_produk) : ($harga_produk * $jml_produk);
//                $subTotal       = isset($datas['disc'])  ? (($harga_produk-$diskon_produk_r)*$jml_produk) : ($harga_produk*$jml_produk);


                $total_unit_produk += $jml_produk;
                $total_item_produk = count($arrItems);
                $total_diskon_produk = ($diskon_produk_r * $jml_produk);
                $sub_total_diskon_produk += $total_diskon_produk;
                $grand_total_tmp += $subTotal;
                $grand_total = $grand_total_tmp - $additional_diskon;

                $diskon_text = "";
                if ($diskon_produk > 0) {
                    $diskon_text = "<div class='text-red bold-d font-size-0-7'><span class='faa faa-user faa-spin'></span> DISKON $diskon_produk @$satuan_produk </div>";
                }

                foreach ($arrItemsTampil as $k => $vvD) {
                    $viewID = $vvD['id'];
                    if ($viewID == $id_produk) {
                        $content .= "<tr>
                                        <td class='text-center vcenter'>$no</td>
                                        <td class='text-left vcenter'>
                                            <span style='font-size:1.5em;' class=''>$nama_produk </span> <span class='font-size-0-7'>($satuan_produk)</span>
                                            $diskon_text
                                        </td>
                                        <td style='font-size:1.5em;' class='text-center vcenter'>" . number_format($harga_produk) . "</td>
                                        <td style='font-size:1.5em;' class='text-center vcenter'>$jml_produk</td>
                                        <td style='font-size:1.5em;' class='text-red text-center vcenter'>" . number_format($total_diskon_produk) . "</td>
                                        <td style='font-size:1.5em;' class='text-center vcenter'>" . number_format($subTotal) . "</td>
                                    </tr>";
                    }
                }
            }
        }
        else {
            for ($i = 1; $i < 11; $i++) {
                $content .= "<tr>
                                <td class='text-right vcenter'>$i</td>
                                <td class='text-left vcenter'>
                                    <span style='font-size:1.5em;' class=''></span>
                                    <div class='text-red bold-d font-size-0-7'></div>
                                </td>
                                <td class='text-right vcenter'></td>
                                <td class='text-center vcenter'></td>
                                <td class='text-right vcenter'></td>
                                <td class='text-right vcenter'></td>
                            </tr>";
            }
        }

        $content .= "<tr>
                        <td style='font-size:1.5em;' class='text-right borderless-bottom' colspan='2'>Sub Total</td>
                        <td style='font-size:1.5em;' class='text-gray text-center bg-active'>-</td>
                        <td style='font-size:1.5em;' class='text-gray text-center bg-active'>$total_unit_produk</td>
                        <td style='font-size:1.5em;' class='text-gray text-center bg-active'>" . number_format($sub_total_diskon_produk) . "</td>
                        <td style='font-size:1.5em;' class='text-gray text-center bg-active'>" . number_format($grand_total_tmp) . "</td>
                    </tr>";

        if ($additional_diskon > 0) {
            $content .= "<tr>
                            <td colspan='2' disabled='' class='text-right vcenter borderless-bottom'>Diskon Tambahan</td>
                            <td colspan='4' class='text-right vcenter text-red bg-active'>" . number_format($additional_diskon) . "</td>
                        </tr>";
        }

        $content .= "<tr>
                        <td colspan='2' class='text-right' style='font-size:2em;'>Grand Total
                                <div class='meta'>Total Yang Harus Dibayar</div>
                        </td>
                        <td colspan='4' class='text-right vcenter bg-info' style='font-size:2.2em;'>
                            <div style='font-size:1.2em;' class='text-right vcenter text-bold' id='grand_total_harga'>" . number_format($grand_total) . "</div>
                        </td>
                    </tr>";


        if (isset($_SESSION['_TR_582']['main']['bayar']) && $_SESSION['_TR_582']['main']['bayar'] > 0 && $_SESSION['_TR_582']['main']['paymentMethod'] == 'cash') {

            $tunai = $_SESSION['_TR_582']['main']['bayar'];

            $content .= "<tr>
                            <td colspan='2' style='font-size:1.2em;' class='text-right vcenter'> Tunai</td>
                            <td colspan='4' style='font-size:1.2em;' class='text-right vcenter bg-info'> " . number_format($tunai) . " </td>
                      </tr>";
        }

//        if( isset($_SESSION['_TR_582']['main']['bayar']) && $_SESSION['_TR_582']['main']['bayar'] > 0 && $_SESSION['_TR_582']['main']['paymentMethod']=='credit_card'){
//
//            $content .= "<tr>
//                            <td colspan='2' style='font-size:1.2em;' class='text-right vcenter'>
//                                <span>Credit Card</span>
//                            </td>
//                            <td colspan='4' style='font-size:1.2em;' class='text-right vcenter bg-info'>
//                                <div class='col-lg-8 text-left text-bold'>{info_cc}</div>
//                                <div class='col-lg-4'>".number_format($tunai)."</div>
//                            </td>
//                      </tr>";
//
//        }

        $selectedCardNumber = 0;
        $paymentMethodText = "Jenis Pembayaran";
        $cardNumber = 0;
        $cardName = "";
        $type = "";

        if (isset($_SESSION['_TR_582']['main']['paymentMethod']) && $_SESSION['_TR_582']['main']['paymentMethod'] != 'cash') {
            $paymentMethod = $_SESSION['_TR_582']['main']['paymentMethod'];
            if ($paymentMethod == 'credit_card') {

                $type = isset($_SESSION['_TR_582']['main']['paymentMethod_' . $paymentMethod . '_credit_account']) ? $_SESSION['_TR_582']['main']['paymentMethod_' . $paymentMethod . '_credit_account'] : "";
                $cardNumber = isset($_SESSION['_TR_582']['main']['paymentMethod_' . $paymentMethod . '_credit_account_' . $type . '_card_number']) ? $_SESSION['_TR_582']['main']['paymentMethod_' . $paymentMethod . '_credit_account_' . $type . '_card_number'] : "";
                $cardName = isset($_SESSION['_TR_582']['main']['paymentMethod_' . $paymentMethod . '_credit_account_' . $type . '_card_name']) ? $_SESSION['_TR_582']['main']['paymentMethod_' . $paymentMethod . '_credit_account_' . $type . '_card_name'] : "";
                $paymentMethodText = "Kartu Kredit";
                $type = str_replace('_', ' ', $type);

            }
            elseif ($paymentMethod == 'debit_card') {

                $type = $_SESSION['_TR_582']['main']['paymentMethod_' . $paymentMethod . '_debit_account'];
                $cardNumber = $_SESSION['_TR_582']['main']['paymentMethod_' . $paymentMethod . '_debit_account_' . $type . '_card_number'];
                $cardName = $_SESSION['_TR_582']['main']['paymentMethod_' . $paymentMethod . '_debit_account_' . $type . '_card_name'];
                $paymentMethodText = "Kartu Debit";
                $type = str_replace('_', ' ', $type);

            }
            else {

            }

            $content .= "<tr>
                            <td colspan='2' class='text-right vcenter'>
                                <span style='font-size:1.2em;' class='text-capitalize'>$paymentMethodText $type</span>
                            </td>
                            <td colspan='4' style='font-size:1.2em;' class='text-right vcenter bg-info'>
                                <div style='padding: 0' class='col-lg-8 text-left text-bold'>" . FormatCreditCard($cardNumber) . "</div>
                                <div style='padding: 0' class='col-lg-4 text-right text-bold'>" . number_format($grand_total) . "</div>
                            </td>
                      </tr>";

        }


        $contentx .= "<tr>
                        <td colspan='2' style='font-size:1.2em;' class='text-right vcenter'> Non Tunai</td>
                        <td colspan='4' class='text-right vcenter bg-info'>
                            <div style='padding: 0' class='text-left col-lg-7'>
                                1234-5678-9012-3456
                                <div style='padding: 0' class='col-lg-6 text-left'>{CC-DC}</div>
                                <div style='padding: 0' class='col-lg-6 text-left'>{EDC-#123}</div>
                            </div>
                            <div style='padding: 0; font-size:1.2em;' class='col-lg-5 bold-d'>{non_tunai}</div>
                        </td>
                    </tr>";

        $contentx .= "<tr>
                            <td colspan='2' style='font-size:1.2em;' class='text-right vcenter'> Voucher</td>
                            <td colspan='4' style='font-size:1.2em;' class='text-right vcenter bg-info'>
                                <div style='padding: 0' class='col-lg-6 text-center'>{jenis_v}</div>
                                <div style='padding: 0' class='col-lg-6 text-right'>{nilai_pot}</div>
                            </td>
                        </tr>";

        if (isset($_SESSION['_TR_582']['main']['bayar']) && $_SESSION['_TR_582']['main']['bayar'] > 0 && $_SESSION['_TR_582']['main']['paymentMethod'] == 'cash') {
            $tunai = $_SESSION['_TR_582']['main']['bayar'];
            $kembalian = ($tunai - $grand_total);

            $content .= "<tr>
                          <td colspan='2' class='text-right vcenter' style='font-size:1.2em;'> Kembalian</td>
                          <td colspan='4' class='text-right bg-info bold-d'> " . number_format($kembalian) . " </td>
                      </tr>";
        }


        $text_diskon_produk = "";
        $text_diskon_tambahan = "";
        $text_items_produk = "";
        $text_units_produk = "";

        if ($sub_total_diskon_produk > 0) {
            $text_diskon_produk = "<div>DISKON = <b>" . number_format($sub_total_diskon_produk) . "</b></div>";
        }

        $content .= "<tr>
                        <td colspan='7' class='text-right vcenter' style='font-size:1em;'>
                            <div>ITEM's = <b>$total_item_produk</b></div>
                            <div>UNIT's = <b>$total_unit_produk</b></div>
                            $text_diskon_produk
                        </td>
                    </tr>";

        $content .= "</tbody></table>";

        if ($sub_total_diskon_produk > 0) {
            $content .= "<div style='font-size:1.5em;' class='panel panel-success bg-black text-center text-bold'> ANDA HEMAT <br> " . number_format($sub_total_diskon_produk) . " </div>";
        }


        echo $content;

//        $p->addTags(
//            array(
//                "content"           => $content
//            )
//        );
//
//        $p->render();

        break;
    case "bannerAds":
        $p = New Layout("$title", "", "application/template/customers_view_json.html");

//        $content  = "";
//        $content .= "<div class='' style=''>
//                        <img class='mySlides' src='http://demo.mayagrahakencana.com/grosir2/assets/images/iklan/iklan_2.jpg' style='border-radius:14px;width:100%'>
//                        <img class='mySlides' src='http://demo.mayagrahakencana.com/grosir2/assets/images/iklan/iklan_3.jpg' style='border-radius:14px;width:100%'>
//                        <img class='mySlides' src='http://demo.mayagrahakencana.com/grosir2/assets/images/iklan/iklan_2.jpg' style='border-radius:14px;width:100%'>
//                        <img class='mySlides' src='http://demo.mayagrahakencana.com/grosir2/assets/images/iklan/iklan_4.jpg' style='border-radius:14px;width:100%'>
//                        <img class='mySlides' src='http://demo.mayagrahakencana.com/grosir2/assets/images/iklan/iklan_2.jpg' style='border-radius:14px;width:100%'>
//                        <img class='mySlides' src='http://demo.mayagrahakencana.com/grosir2/assets/images/iklan/iklan_5.jpg' style='border-radius:14px;width:100%'>
//                    </div>";
//        $content .= "";

        $p->addTags(
            array(
                "content" => $content
            )
        );

        $p->render();

        break;
    case "customer_view":
        $p = New Layout("$title", "", "application/template/customer_view.html");
        $content = "";
        $advertise = "";

        $content .= "<div id='shopping_cart' class='col-lg-5'></div>";

        $p->addTags(
            array(
                "content" => $content,
                "advertise" => $advertise,
                "login_name" => $login_name
            )
        );

        $p->render();

        break;
    case "customers_view_json":
        $p = New Layout("$title", "", "application/template/customers_view_json.html");

        $content = "";

        if (isset($_SESSION['_TR_582']['items']) && count($_SESSION['_TR_582']['items']) > 0) {

            $arrItems = $_SESSION['_TR_582']['items'];
            $arrMain = $_SESSION['_TR_582']['main'];


            $total_unit_produk = 0;
            $grand_total = 0;
            $total_diskon_produk = 0;
            $grand_total_tmp = 0;

            $no = 0;

            $jsonTmp = [];
            $arrProduk = [];
            foreach ($arrItems as $id_produk => $datas) {
                $no++;
                $nama_produk = $datas['nama'];
                $harga_produk = $datas['harga'];
                $satuan_produk = $datas['satuan'];
                $jml_produk = $datas['jml'];
                $diskon_produk = isset($datas['diskon']) ? $datas['diskon'] : 0;
                $diskon_add = isset($datas['add_diskon']) ? $datas['add_diskon'] : 0;
                $subTotal = ($harga_produk * $jml_produk);

                $total_unit_produk += $jml_produk;
                $total_item_produk = count($arrItems);
                $total_diskon_produk += $diskon_produk;
                $grand_total_tmp += $subTotal;
                $grand_total = $grand_total_tmp - $diskon_add;


                $jsonTmp = array(
                    "proposal" => $arrMain,
                    "produk_details" => $arrItems,
                );
            }

        }


        $p->addTags(
            array(
                "content" => json_encode($jsonTmp)
            )
        );

        $p->render();

        break;
}