<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @role lead_architect_agent & software_engineer_agent
 * View Tampilan QR Code Mobile Scanner Desktop Mode (Rule 3.9 & Desktop Separation)
 */
$desktopUrl = isset($desktopUrl) ? $desktopUrl : str_replace("?ismob=1", "", $qrbase);
?>
<div class='text-center'>
    <img src='<?php echo $qrfile; ?>' title='<?php echo $xID; ?>' class='img-thumbnail'>
</div>
<div class='text-center text-bold text-uppercase text-red' style='font-size: 16px;margin-top: 10px'>
    scan QR mengunakan handphone untuk melakukan pemindaian barcode <br>dari barang yang baru datang
</div>
<div class='text-center' style='font-size: 12px;margin-top: 10px;color: #ddd;'>
    <?php echo $qrbase; ?>
</div>
<div class='text-center' style='font-size: 12px;margin-top: 10px;color: #ddd;'>
    <button type='button' onclick="location.href='<?php echo $desktopUrl; ?>'" class='btn btn-info text-capitalize'>buka dari desktop</button>
</div>
