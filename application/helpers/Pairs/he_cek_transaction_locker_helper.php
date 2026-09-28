<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// START OF COMPLETE REPEATED LOGIC
if (!function_exists('he_check_and_lock_transaction')) {
    /**
     * Memeriksa dan mengelola lock transaksi pada MdlLockerTransaksi.
     * Sesuai Aturan 3.10.3 (Mekanisme Ambil Alih Transaksi - Petugas Idle).
     *
     * @param int|string $transaksiID ID Transaksi
     * @param string $jenisTr Jenis Transaksi (e.g., 583, 584)
     * @param int|string $myId ID User aktif saat ini
     * @param int $forceRetakeLock Parameter paksa ambil alih (1 jika diklik tombol)
     * @return array Status penguncian transaksi
     */
    function he_check_and_lock_transaction($transaksiID, $jenisTr, $myId, $forceRetakeLock = 0)
    {
        $ci =& get_instance();
        $ci->load->model("Mdls/MdlLockerTransaksi");
        $ci->load->model("Mdls/MdlUser");

        $transaksiID = intval($transaksiID);
        $myId = intval($myId);
        $forceRetakeLock = intval($forceRetakeLock);

        if ($transaksiID <= 0 || $myId <= 0) {
            return array(
                "status" => "ok",
                "message" => "ID transaksi atau User ID tidak valid untuk penguncian"
            );
        }

        // 1. Cari lock aktif di MdlLockerTransaksi (state='hold', jumlah='1')
        $l = new MdlLockerTransaksi();
        $l->setFilters(array());
        $l->addFilter("transaksi_id = " . $transaksiID);
        $l->addFilter("state = 'hold'");
        $l->addFilter("jumlah = '1'");
        $tmpLock = $l->lookupAll()->result();

        if (sizeof($tmpLock) < 1) {
            // Belum ada lock aktif pada transaksi ini
            return array(
                "status" => "ok",
                "is_locked" => false
            );
        }

        $lockRow = $tmpLock[0];
        $ownerId = intval($lockRow->oleh_id);

        // 2. Jika lock dimiliki oleh user yang sama
        if ($ownerId === $myId || $ownerId === 0) {
            return array(
                "status" => "ok",
                "is_locked" => true,
                "is_owner" => true
            );
        }

        // 3. Lock dimiliki oleh user lain -> Cari info petugas aktif tersebut
        $uMdl = new MdlUser();
        $uMdl->setFilters(array());
        $tmpUser = $uMdl->lookupByCondition(array("id" => $ownerId))->result();

        $ownerNama = "Petugas ID " . $ownerId;
        $ownerIp = "Unknown IP";
        $ownerDevice = "Unknown Device";
        $lastActiveDtime = isset($lockRow->dtime) ? $lockRow->dtime : date("Y-m-d H:i:s");

        if (sizeof($tmpUser) > 0) {
            $uRow = $tmpUser[0];
            $ownerNama = isset($uRow->nama) ? $uRow->nama : (isset($uRow->nama_login) ? $uRow->nama_login : $ownerNama);
            $ownerIp = isset($uRow->ipadd) ? $uRow->ipadd : $ownerIp;
            $ownerDevice = isset($uRow->devices) ? $uRow->devices : $ownerDevice;
            if (isset($uRow->phpsess_dtime) && $uRow->phpsess_dtime != '') {
                $lastActiveDtime = $uRow->phpsess_dtime;
            }
        }

        // Hitung selisih waktu tidak aktif (idle) dalam detik
        $lastActiveTs = strtotime($lastActiveDtime);
        $nowTs = time();
        $idleSeconds = $nowTs - $lastActiveTs;
        if ($idleSeconds < 0) {
            $idleSeconds = 0;
        }

        $minIdleForRetake = 300; // Minimal 5 menit (300 detik)
        $canRetake = ($idleSeconds >= $minIdleForRetake);

        // 4. Jika user menekan tombol "Ambil Alih Transaksi" DAN sudah idle >= 5 menit
        if ($forceRetakeLock === 1 && $canRetake) {
            // Melepaskan lock petugas lama (jumlah = 0)
            $l->setFilters(array());
            $l->updateData(
                array("id" => $lockRow->id),
                array("jumlah" => 0, "state" => "released")
            );

            // Log audit pengambilalihan kunci
            if (function_exists('writeLog')) {
                writeLog("retake_lock", "Ambil alih lock transaksi ID " . $transaksiID . " dari user " . $ownerId . " oleh user " . $myId, "auth");
            }

            return array(
                "status" => "ok",
                "retaken" => true,
                "previous_owner_id" => $ownerId
            );
        }

        $trNomer = $transaksiID;
        $ci->load->model("Mdls/MdlTransaksi");
        $tMdl = new MdlTransaksi();
        $tMdl->setFilters(array());
        $tMdl->addFilter("id = " . $transaksiID);
        $tmpTr = $tMdl->lookupAll()->result();
        if (sizeof($tmpTr) > 0 && !empty($tmpTr[0]->nomer)) {
            $trNomer = $tmpTr[0]->nomer;
        }

        // 5. Jika kurang dari 5 menit atau tidak diklik ambil alih -> Kembalikan status terkunci
        return array(
            "status" => "locked",
            "is_locked" => true,
            "is_owner" => false,
            "lock_id" => $lockRow->id,
            "transaksi_id" => $transaksiID,
            "nomer" => $trNomer,
            "idle_seconds" => $idleSeconds,
            "can_retake" => $canRetake,
            "min_idle_seconds" => $minIdleForRetake,
            "owner_info" => array(
                "id" => $ownerId,
                "nama" => $ownerNama,
                "ipadd" => $ownerIp,
                "devices" => $ownerDevice,
                "last_active" => $lastActiveDtime
            )
        );
    }
}

if (!function_exists('he_render_transaction_locked_page')) {
    /**
     * Menampilkan halaman/alert interaktif "Transaksi Terkunci".
     * Sesuai Aturan 3.10.3.
     *
     * @param array $lockResult Result dari he_check_and_lock_transaction
     * @param string $backUrl URL kembali jika dibatalkan
     */
    function he_render_transaction_locked_page($lockResult, $backUrl = '')
    {
        if ($backUrl == '') {
            $backUrl = base_url();
        }

        $owner = isset($lockResult['owner_info']) ? $lockResult['owner_info'] : array();
        $ownerNama = isset($owner['nama']) ? htmlspecialchars($owner['nama'], ENT_QUOTES, 'UTF-8') : 'Petugas Lain';
        $ownerId = isset($owner['id']) ? intval($owner['id']) : 0;
        $ownerIp = isset($owner['ipadd']) ? htmlspecialchars($owner['ipadd'], ENT_QUOTES, 'UTF-8') : '-';
        $ownerDevice = isset($owner['devices']) ? htmlspecialchars($owner['devices'], ENT_QUOTES, 'UTF-8') : '-';
        $lastActive = isset($owner['last_active']) ? htmlspecialchars($owner['last_active'], ENT_QUOTES, 'UTF-8') : '-';

        $trNomer = isset($lockResult['nomer']) ? htmlspecialchars($lockResult['nomer'], ENT_QUOTES, 'UTF-8') : (isset($lockResult['transaksi_id']) ? $lockResult['transaksi_id'] : '');

        $idleSeconds = isset($lockResult['idle_seconds']) ? intval($lockResult['idle_seconds']) : 0;
        $idleMinutes = floor($idleSeconds / 60);
        $canRetake = isset($lockResult['can_retake']) ? (bool)$lockResult['can_retake'] : false;

        $currentUrl = current_url();
        $retakeUrl = $currentUrl . "?forceRetakeLock=1";

        $retakeBtnHtml = ""; // Fitur ambil alih disembunyikan sementara atas permintaan user

        $html = "<div id='locked_card_container' style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; text-align: center; padding: 15px 10px; color: #1f2937; box-sizing: border-box; max-width: 480px; margin: 0 auto;'>
    <div style='width: 60px; height: 60px; background-color: #fef3c7; border: 2px solid #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 28px;'>
        🔒
    </div>
    <h3 style='margin: 0 0 8px 0; font-size: 22px; font-weight: 700; color: #92400e; letter-spacing: -0.5px;'>
        Transaksi Terkunci
    </h3>
    <p style='margin: 0 0 20px 0; font-size: 14px; color: #4b5563; line-height: 1.5;'>
        Transaksi dengan nomor <b style='color: #111827;'>\"{$trNomer}\"</b> sedang diproses oleh petugas lain. Untuk mencegah bentrok data, Anda tidak dapat memprosesnya secara bersamaan.
    </p>

    <table style='width: 100%; border-collapse: separate; border-spacing: 0; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; margin-bottom: 20px; text-align: left; font-size: 13px; background-color: #ffffff;'>
        <tr style='background-color: #f9fafb;'>
            <td style='padding: 10px 14px; font-weight: 600; color: #6b7280; width: 38%; border-bottom: 1px solid #e5e7eb;'>Nama Petugas</td>
            <td style='padding: 10px 14px; font-weight: 700; color: #111827; border-bottom: 1px solid #e5e7eb;'>{$ownerNama} <span style='font-weight: 400; color: #6b7280;'>(ID: {$ownerId})</span></td>
        </tr>
        <tr>
            <td style='padding: 10px 14px; font-weight: 600; color: #6b7280; border-bottom: 1px solid #e5e7eb;'>IP Address</td>
            <td style='padding: 10px 14px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;'>{$ownerIp}</td>
        </tr>
        <tr style='background-color: #f9fafb;'>
            <td style='padding: 10px 14px; font-weight: 600; color: #6b7280; border-bottom: 1px solid #e5e7eb;'>Perangkat</td>
            <td style='padding: 10px 14px; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb;'>{$ownerDevice}</td>
        </tr>
        <tr>
            <td style='padding: 10px 14px; font-weight: 600; color: #6b7280;'>Aktif Terakhir</td>
            <td style='padding: 10px 14px; font-weight: 600; color: #374151;'>{$lastActive} <span style='font-weight: 400; color: #6b7280;'>({$idleMinutes}m lalu)</span></td>
        </tr>
    </table>
<!--
    <div style='display: flex; flex-direction: column; gap: 8px;'>
        <button type='button' onclick='closeLockedModalNow()' style='display: block; width: 100%; padding: 12px 16px; background-color: #374151; color: #ffffff; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; text-align: center; box-sizing: border-box; cursor: pointer;'>
            ↺ Kembali
        </button>
    </div>
    -->
</div>
<script>
    function closeLockedModalNow() {
        if (typeof top !== 'undefined' && top.swal && typeof top.swal.close === 'function') {
            try { top.swal.close(); } catch(e){}
        }
        if (typeof top !== 'undefined' && top.BootstrapDialog && typeof top.BootstrapDialog.closeAll === 'function') {
            try { top.BootstrapDialog.closeAll(); } catch(e){}
        }
        var targets = [window.top, window.parent, window];
        for (var i = 0; i < targets.length; i++) {
            if (targets[i] && targets[i].$) {
                targets[i].$('.sweet-alert, .sweet-overlay, .swal2-container, .modal-backdrop').hide().remove();
                targets[i].$('body').removeClass('stop-scrolling modal-open swal2-shown');
            }
        }
    }

    (function() {
        function showLockedModal() {
            try {
                if (window.top && typeof window.top.close_holdon === 'function') { window.top.close_holdon(); }
                if (typeof top !== 'undefined' && top && top.HoldOn && typeof top.HoldOn.close === 'function') { top.HoldOn.close(); }
                var targets = [window.top, window.parent, window];
                for (var i = 0; i < targets.length; i++) {
                    if (targets[i] && targets[i].$) {
                        targets[i].$('.holdon-overlay, .holdon, .loading-overlay, .modal-backdrop, #holdon-overlay').remove();
                        targets[i].$('body').removeClass('holdon-loading');
                    }
                }
            } catch (e) {}

            var cardElem = document.getElementById('locked_card_container');
            if (cardElem && typeof top !== 'undefined') {
                var content = cardElem.outerHTML;

                var resElem = document.getElementById('result');
                if (resElem) {
                    resElem.innerHTML = '';
                }

                if (top.swal) {
                    top.swal({
                        title: '',
                        html: content,
                        showConfirmButton: false,
                        width: '500px'
                    });
                } else if (top.BootstrapDialog) {
                    top.BootstrapDialog.show({
                        title: '⚠️ Transaksi Terkunci',
                        message: $(content),
                        type: top.BootstrapDialog.TYPE_WARNING
                    });
                }
            }
        }
        showLockedModal();
        setTimeout(showLockedModal, 100);
    })();
</script>";

        echo $html;
        exit();
    }
}
// END OF COMPLETE REPEATED LOGIC

