<?php
/**
 * View script for _tray_enchanced.php
 * Handles DOM injection and localStorage updates for notifications.
 */
?>
<script>
// --- MAINTENANCE / IDLE ALERT ---
<?php if (isset($idleData['is_maintenance']) && $idleData['is_maintenance']): ?>
    swalAlert({
        html: "Under Maintenace",
        showConfirmButton: false,
    });
<?php endif; ?>

<?php if (isset($idleData['is_idle']) && $idleData['is_idle']): ?>
    // Fitur Idle Auto-Logout
    var mesage = "<?= $idleData['jam_now'] ?> Terdeteksi idle selama <?= $idleData['idle_minutes'] ?> menit<br>silahkan login kembali untuk kembali beraktifitas";
    var mesage_e = encodeURIComponent(btoa(mesage)); 
    swalAlert({
        title: "Security Alert",
        html: "Hi. <span style='font-size: 1.5em;text-transform: capitalize;'><?= $idleData['anggota_nama'] ?></span><br>" + mesage + "<div class='text-red text-uppercase text-bold'>akan reload setelah 30 detik</div>",
    });
    var logout_url = "<?= base_url() ?>Login/authLogout?e=" + mesage_e;
    setTimeout(function() {
        top.location.href = logout_url;
    }, 30000);
<?php endif; ?>

// --- TRANSAKSI TODO (SubTodoTrans) ---
<?php if (!empty($transaksiData['resetterJenisTr'])): ?>
    <?php foreach ($transaksiData['resetterJenisTr'] as $jn): ?>
        localStorage.removeItem('<?= $jn ?>');
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($transaksiData['subTodoTrans'])): ?>
    <?php 
    $CI = &get_instance();
    $heTransaksiUiFallBack = (null != $CI->config->item("heTransaksi_ui")) ? $CI->config->item("heTransaksi_ui") : array();
    $strFloatMenuTodoTransMb = "";
    $strFloatMenuTodoTransDs = "";
    foreach ($transaksiData['subTodoTrans'] as $j => $jSpec): 
        $count = count($jSpec);
        $jenisNameArr = isset($transaksiData['subTodoTransName'][$j]) ? $transaksiData['subTodoTransName'][$j] : array('');
        $modulTarget = isset($masterConfigUi[$j]['modul']) ? $masterConfigUi[$j]['modul'] : (isset($heTransaksiUiFallBack[$j]['modul']) ? $heTransaksiUiFallBack[$j]['modul'] : '');
        $targetUrl = !empty($modulTarget) ? base_url() . "$modulTarget/Transaksi/index/$j" : base_url() . "Transaksi/index/$j";
        $labelTransMenu = isset($transaksiData['transMenus'][$j]) ? $transaksiData['transMenus'][$j] : "";
        
        $strFloatMenuTodoTransMb .= "<li id='bawah-f-$j-mb' class='my-nav__item-bawah-f-mb'><a class='my-nav__link-f-mb my-nav__link--template text-white' href='$targetUrl'>$labelTransMenu <sup><span class='badge bg-aqua'>$count</span></sup></a></li>";
        $strFloatMenuTodoTransDs .= "<li id='bawah-f-$j-ds' class='hidden-xs my-nav__item-bawah-f-ds'><a class='my-nav__link-f-ds my-nav__link--template text-white' href='$targetUrl'>$labelTransMenu <sup><span class='badge bg-aqua'>$count</span></sup></a></li>";
    ?>
        localStorage.setItem('<?= $j ?>', <?= $count ?>);
        if(top.document.getElementById('trb<?= $j ?>')){
            top.document.getElementById('trb<?= $j ?>').innerHTML = '<?= $count ?>';
            top.document.getElementById('trb<?= $j ?>').className = 'badge bg-red text-white';
            top.$('#trb<?= $j ?>').addClass('TodoTrans');
        }
        
        var menus_mb_<?= $j ?> = top.$('#bawah-f-<?= $j ?>-mb');
        if(menus_mb_<?= $j ?>.length === 0){
            top.$('#wrapper-templates-bawah-f-mb').append("<?= addslashes($strFloatMenuTodoTransMb) ?>");
        }
        var menus_ds_<?= $j ?> = top.$('#bawah-f-<?= $j ?>-ds');
        if(menus_ds_<?= $j ?>.length === 0){
            top.$('#wrapper-templates-bawah-f-ds').append("<?= addslashes($strFloatMenuTodoTransDs) ?>");
        }

        <?php if (isset($transaksiData['extraSrc'][$j])): ?>
            var menus_new_<?= $j ?> = top.$('li#<?= $j ?>');
            if(menus_new_<?= $j ?>.length > 0){
                top.$('a', menus_new_<?= $j ?>).css('background-color', 'rgb(0, 166, 90)');
                top.$('a', menus_new_<?= $j ?>).css('color', 'white');
                top.$('a', menus_new_<?= $j ?>).addClass('blink');
                top.$('a', menus_new_<?= $j ?>).addClass('text-bold');
                top.$('a', menus_new_<?= $j ?>).addClass('hidden');
            }
        <?php endif; ?>
    <?php 
        $strFloatMenuTodoTransMb = "";
        $strFloatMenuTodoTransDs = "";
    endforeach; ?>
    
    // Gethuk Notif Todo
    var gethuk_mb = top.$('#gethuk_mb');
    if(gethuk_mb.length > 0){
        gethuk_mb.html("<a class='btn btn__trigger-bawah-f-mb btn__trigger--views-bawah-f-mb' id='trigger-bawah-f-mb'><span class='count-badge badge' style='display:none;'></span></a>");
        var el = top.document.querySelector('.btn__trigger-bawah-f-mb');
        if(el) {
            var count = Number(el.getAttribute('data-count')) || 0;
            el.setAttribute('data-count', <?= $transaksiData['subTodoTransCountTotal'] ?>);
            el.classList.remove('notify');
            el.offsetWidth = el.offsetWidth;
            el.classList.add('notify');
            if(count === 0) el.classList.add('show-count');
        }
    }
    if (typeof top.displayListBawahF_mb === 'function') top.displayListBawahF_mb();

    var gethuk_ds = top.$('#gethuk_ds');
    if(gethuk_ds.length > 0){
        gethuk_ds.html("<a class='btn btn__trigger-bawah-f-ds btn__trigger--views-bawah-f-ds' id='trigger-bawah-f-ds'><span class='count-badge badge' style='display:none;'></span></a>");
        var el_ds = top.document.querySelector('.btn__trigger-bawah-f-ds');
        if(el_ds) {
            var count = Number(el_ds.getAttribute('data-count')) || 0;
            el_ds.setAttribute('data-count', <?= $transaksiData['subTodoTransCountTotal'] ?>);
            el_ds.classList.remove('notify');
            el_ds.offsetWidth = el_ds.offsetWidth;
            el_ds.classList.add('notify');
            if(count === 0) el_ds.classList.add('show-count');
        }
    }
    if (typeof top.displayListBawahF_ds === 'function') top.displayListBawahF_ds();
<?php endif; ?>

<?php if (!empty($transaksiData['resetterJenisTr'])): ?>
    <?php foreach ($transaksiData['resetterJenisTr'] as $jn): ?>
        if(top.document.getElementById('kiri_<?= $jn ?>')){
            var lsVal = localStorage.getItem('<?= $jn ?>') * 1;
            top.document.getElementById('kiri_<?= $jn ?>').innerHTML = (lsVal > 0) ? lsVal : '';
        }
    <?php endforeach; ?>
<?php endif; ?>


// --- TRANSAKSI UNDONE (SubUndoneTrans) ---
<?php if (!empty($transaksiData['subUndoneTrans'])): ?>
    <?php foreach ($transaksiData['subUndoneTrans'] as $j => $jSpec): ?>
        if(top.document.getElementById('tra<?= $j ?>')){
            top.document.getElementById('tra<?= $j ?>').innerHTML = '<?= count($jSpec) ?>';
            top.document.getElementById('tra<?= $j ?>').className = 'badge bg-yellow';
        }
    <?php endforeach; ?>

    <?php 
    $strFloatMenuUndoneTrans = "";
    foreach ($transaksiData['subUndoneTransName'] as $jenis => $jName): 
        $count = count($transaksiData['subUndoneTrans'][$jenis]);
        $modulTarget = isset($masterConfigUi[$jenis]['modul']) ? $masterConfigUi[$jenis]['modul'] : (isset($heTransaksiUiFallBack[$jenis]['modul']) ? $heTransaksiUiFallBack[$jenis]['modul'] : '');
        $targetUrl = !empty($modulTarget) ? base_url() . "$modulTarget/Transaksi/index/$jenis" : base_url() . "Transaksi/index/$jenis";
        $labelTransMenu = isset($transaksiData['transMenus'][$jenis]) ? $transaksiData['transMenus'][$jenis] : "";
        
        $strFloatMenuUndoneTrans .= "<li id='bawah-wt-$jenis' class='my-nav__item-bawah-WT'><a class='my-nav__link-WT my-nav__link--template text-white' href='$targetUrl'>$labelTransMenu <sup><span class='badge bg-yellow'>$count</span></sup></a></li>";
    ?>
        var menuswt_<?= $jenis ?> = top.$('#bawah-wt-<?= $jenis ?>');
        if(menuswt_<?= $jenis ?>.length === 0){
            top.$('#wrapper-templates-bawah-WT').append("<?= addslashes($strFloatMenuUndoneTrans) ?>");
        }
    <?php 
        $strFloatMenuUndoneTrans = "";
    endforeach; ?>
<?php endif; ?>

// --- TODOLINK ---
<?php if (isset($transaksiData['todoCtr']) && $transaksiData['todoCtr'] > 0): ?>
    if(top.document.getElementById('not_ctr')){
        top.document.getElementById('not_ctr').innerHTML = "<a href='<?= base_url() ?>Welcome/index' class='text-white'><?= $transaksiData['todoCtr'] ?></a>";
        top.document.getElementById('not_ctr').className = 'badge';
        top.document.getElementById('not_ctr').style.background = '#990000';
    }
<?php endif; ?>


// --- DATA PROPOSALS (SubTodoDatas) ---
<?php if (!empty($proposalData['subTodoDatas'])): ?>
    <?php foreach ($proposalData['subTodoDatas'] as $j => $jSpec): 
        $j_f = str_replace("Mdl", "", $j);
        $count = count($jSpec);
    ?>
        if(top.document.getElementById('crdtb<?= $j_f ?>')){
            top.document.getElementById('crdtb<?= $j_f ?>').innerHTML = '<?= $count ?>';
            top.document.getElementById('crdtb<?= $j_f ?>').className = 'badge bg-red text-white';
            top.$('#crdtb<?= $j_f ?>').addClass('TodoDatas');
        }
    <?php endforeach; ?>

    <?php 
    $strFloatMenuTodoDatasMb = "";
    $strFloatMenuTodoDatasDs = "";
    foreach ($proposalData['subTodoDatasName'] as $jenis => $jName): 
        $jenis_f = str_replace("Mdl", "", $jenis);
        $count = count($proposalData['subTodoDatas'][$jenis]);
        $targetUrl = base_url() . "data/view/$jenis_f";
        $menuLabel = isset($proposalData['dataMenus'][$jenis_f]) ? $proposalData['dataMenus'][$jenis_f] : $jenis_f;

        $strFloatMenuTodoDatasMb .= "<li id='bawah-WT-$jenis_f-mb' class='my-nav__item-bawah-WT-mb'><a class='my-nav__link-WT-mb my-nav__link--template text-white' href='$targetUrl'>$menuLabel <sup><span class='badge bg-aqua'>$count</span></sup></a></li>";
        $strFloatMenuTodoDatasDs .= "<li id='bawah-WT-$jenis_f-ds' class='my-nav__item-bawah-WT-ds'><a class='my-nav__link-WT-ds my-nav__link--template text-white' href='$targetUrl'>$menuLabel <sup><span class='badge bg-aqua'>$count</span></sup></a></li>";
    ?>
        var menus_WT_a<?= $jenis_f ?> = top.$('#bawah-WT-<?= $jenis_f ?>-mb');
        if(menus_WT_a<?= $jenis_f ?>.length === 0){
            top.$('#wrapper-templates-bawah-WT-mb').append("<?= addslashes($strFloatMenuTodoDatasMb) ?>");
        }
        var menus_WT_b<?= $jenis_f ?> = top.$('#bawah-WT-<?= $jenis_f ?>-ds');
        if(menus_WT_b<?= $jenis_f ?>.length === 0){
            top.$('#wrapper-templates-bawah-WT-ds').append("<?= addslashes($strFloatMenuTodoDatasDs) ?>");
        }
    <?php 
        $strFloatMenuTodoDatasMb = "";
        $strFloatMenuTodoDatasDs = "";
    endforeach; ?>
    
    var geplak_mb = top.$('#geplak_mb');
    if(geplak_mb.length > 0){
        geplak_mb.html("<a class='btn btn__trigger-bawah-WT-mb btn__trigger--views-bawah-WT-mb' id='trigger-bawah-WT-mb'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a>");
        var el = top.document.querySelector('.btn__trigger-bawah-WT-mb');
        if(el) {
            var count = Number(el.getAttribute('data-count')) || 0;
            el.setAttribute('data-count', <?= $proposalData['subTodoDatasCountTotal'] ?>);
            el.classList.remove('notify');
            el.offsetWidth = el.offsetWidth;
            el.classList.add('notify');
            if(count === 0) el.classList.add('show-count');
        }
    }
    if (typeof top.displayListBawahWT_mb === 'function') top.displayListBawahWT_mb();

    var geplak_ds = top.$('#geplak_ds');
    if(geplak_ds.length > 0){
        geplak_ds.html("<a class='btn btn__trigger-bawah-WT-ds btn__trigger--views-bawah-WT-ds' id='trigger-bawah-WT-ds'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a>");
        var el_ds = top.document.querySelector('.btn__trigger-bawah-WT-ds');
        if(el_ds) {
            var count = Number(el_ds.getAttribute('data-count')) || 0;
            el_ds.setAttribute('data-count', <?= $proposalData['subTodoDatasCountTotal'] ?>);
            el_ds.classList.remove('notify');
            el_ds.offsetWidth = el_ds.offsetWidth;
            el_ds.classList.add('notify');
            if(count === 0) el_ds.classList.add('show-count');
        }
    }
    if (typeof top.displayListBawahWT_ds === 'function') top.displayListBawahWT_ds();
<?php endif; ?>

// --- REKENING NOTIFICATIONS (SubOtherMenus) ---
<?php if (!empty($rekeningData['subOtherMenus'])): ?>
    <?php 
    $strFloatOtherMenusMb = "";
    $strFloatOtherMenusDs = "";
    foreach ($rekeningData['subOtherMenus'] as $jenis => $spec): 
        $targetUrlLabel = $spec['rekening'];
        $nilaiDebetFormated = number_format($spec['debet']);
        
        $strFloatOtherMenusMb .= "<li id='bawah-RK-$jenis-mb' class='my-nav__item-bawah-RK-mb'><span class='my-nav__lrekening_origink-RK-mb my-nav__link--template text-white'> $targetUrlLabel (Rp.$nilaiDebetFormated) </span></li>";
        $strFloatOtherMenusDs .= "<li id='bawah-RK-$jenis-ds' class='hidden-xs my-nav__item-bawah-RK-ds'><span class='my-nav__link-RK-ds my-nav__link--template text-white'> $targetUrlLabel (Rp.$nilaiDebetFormated) </span></li>";
    ?>
        var other_mb_<?= $jenis ?> = top.$('#bawah-RK-<?= $jenis ?>-mb');
        if(other_mb_<?= $jenis ?>.length === 0){
            top.$('#wrapper-templates-bawah-RK-mb').append("<?= addslashes($strFloatOtherMenusMb) ?>");
        }
        var other_ds_<?= $jenis ?> = top.$('#bawah-RK-<?= $jenis ?>-ds');
        if(other_ds_<?= $jenis ?>.length === 0){
            top.$('#wrapper-templates-bawah-RK-ds').append("<?= addslashes($strFloatOtherMenusDs) ?>");
        }
    <?php 
        $strFloatOtherMenusMb = "";
        $strFloatOtherMenusDs = "";
    endforeach; ?>
    
    var other_mb = top.$('#other_mb');
    if(other_mb.length > 0){
        other_mb.html("<a class='btn btn__trigger-bawah-RK-mb btn__trigger--views-bawah-RK-mb' id='trigger-bawah-RK-mb'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a>");
        var el = top.document.querySelector('.btn__trigger-bawah-RK-mb');
        if(el){
            var count = Number(el.getAttribute('data-count')) || 0;
            el.setAttribute('data-count', <?= count($rekeningData['subOtherMenus']) ?>);
            el.classList.remove('notify');
            el.offsetWidth = el.offsetWidth;
            el.classList.add('notify');
            if(count === 0) el.classList.add('show-count');
        }
    }
    if(typeof top.displayListBawahRK_mb === 'function') top.displayListBawahRK_mb();

    var other_ds = top.$('#other_ds');
    if(other_ds.length > 0){
        other_ds.html("<a class='btn btn__trigger-bawah-RK-ds btn__trigger--views-bawah-RK-ds' id='trigger-bawah-RK-ds'><span style='top:-15px;left:-45px;' class='count-badge badge'></span></a>");
        var el_ds = top.document.querySelector('.btn__trigger-bawah-RK-ds');
        if(el_ds){
            var count = Number(el_ds.getAttribute('data-count')) || 0;
            el_ds.setAttribute('data-count', <?= count($rekeningData['subOtherMenus']) ?>);
            el_ds.classList.remove('notify');
            el_ds.offsetWidth = el_ds.offsetWidth;
            el_ds.classList.add('notify');
            if(count === 0) el_ds.classList.add('show-count');
        }
    }
    if(typeof top.displayListBawahRK_ds === 'function') top.displayListBawahRK_ds();
<?php endif; ?>

// --- REQUEST ITEMS CHECK ---
<?php if (!empty($transaksiData['requestCheck'])): ?>
    <?php if ($transaksiData['requestCheck']['triggerUpdate']): ?>
        top.getData('<?= base_url() ?>Transaksi/viewUndoneItems/<?= $transaksiData['requestCheck']['jenisTr'] ?>/?ohyes=ohno', 'undoneList');
        setTimeout(function(){
            top.getData('<?= base_url() ?>Transaksi/viewRequestItems/<?= $transaksiData['requestCheck']['jenisTr'] ?>/?ohyes=ohno', 'requestItems');
        }, 1000);
    <?php endif; ?>
<?php endif; ?>

// --- TRAY CONTROL ---
var sesTrayCount = 0;
if(sessionStorage.getItem('sesTrayCount') == null){
    sessionStorage.setItem('sesTrayCount', '1');
    top.console.log('_tray null');
}
if(sessionStorage.getItem('sesTrayCount') !== null){
    if(parseFloat(sessionStorage.getItem('sesTrayCount')) > 99){
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
</script>
