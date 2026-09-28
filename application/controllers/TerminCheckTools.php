<?php

class TerminCheckTools extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("MdlTransaksi");
        $this->load->model("Mdls/MdlCliLogTime");
        $this->load->model("Coms/ComJurnal");
        $this->load->model("Coms/ComRekeningPembantuRaw");
        $this->load->model("Coms/ComRekeningPembantuRawMain");
    }
    /**
     * Mendapatkan daftar proyek yang memiliki payment source bermasalah.
     *
     * Fungsi ini melakukan perhitungan nilai termin yang seharusnya (DPP) berdasarkan
     * data registry (termin, DP, dan retensi) dan membandingkannya dengan nilai
     * tagihan, terbayar, serta sisa dari payment source. Setiap proyek yang
     * ditemukan bermasalah akan dikembalikan dalam bentuk array dengan satu
     * entri per proyek. Semua kolom utama (tagihan, termin_seharusnya,
     * terbayar, sisa, sisa_seharusnya) akan selalu terisi agar mudah ditampilkan
     * pada view. Kode ini kompatibel dengan PHP 5.6.
     *
     * @return array
     */
    public function getInvalidPaymentProjects($issueFilter = null)
    {
        // muat model transaksi
        $this->load->model('MdlTransaksi');
        $tr = new MdlTransaksi();

        $invalid = array();

        // Normalisasi filter isu: jadikan array atau null jika tidak ada filter
        $filterIssues = null;
        if ($issueFilter !== null) {
            if (is_array($issueFilter)) {
                $filterIssues = $issueFilter;
            } else {
                $filterIssues = array($issueFilter);
            }
        }

        // ambil semua proyek aktif
        $this->db->where('status', 1);
        $this->db->order_by('persen_progress', 'desc');
        $projects = $this->db->get('project_produk')->result();

        foreach ($projects as $p) {
            // Tentukan ID transaksi registry (mengutamakan quot_id jika ada)
            $transId = 0;
            if (isset($p->quot_id) && $p->quot_id > 0) {
                $transId = $p->quot_id;
            }
            elseif (isset($p->transaksi_id)) {
                $transId = $p->transaksi_id;
            }

            // Ambil registry data (items3 untuk termin, items4 untuk DP, items5 untuk retensi)
            $this->db->where('transaksi_id', $transId);
            $regs = $this->db->get('transaksi_data_registry')->result();
            if (empty($regs)) {
                continue;
            }

            $items3 = blobDecode($regs[0]->items3);
            $items4 = blobDecode($regs[0]->items4);
            $items5 = blobDecode($regs[0]->items5);

            // Hitung total termin (sudah termasuk PPN)
            $totalTermin = 0;
            foreach ($items3 as $item) {
                if (isset($item['harga'])) {
                    $totalTermin += (float) $item['harga'];
                }
            }

            // Hitung nilai termin seharusnya (DPP)
            $terminSeharusnya = 0.0;
            if ($totalTermin > 0) {
                // Jika total termin sudah termasuk PPN, buang PPN 11%
                $terminSeharusnya = $totalTermin / 1.11;
            }
            else {
                // Jika termin belum diset, gunakan harga project apa adanya
                $terminSeharusnya = (float) $p->harga;
            }

            // Ambil payment source (jenis 588so target_jenis 7499)
            $tr->setFilters(array());
            $tr->addFilter("project_id='" . $p->id . "'");
            $tr->addFilter("jenis='588so'");
            $tr->addFilter("target_jenis='7499'");
            $pymRows = $tr->lookUpPayment()->result();

            // Siapkan container issue untuk proyek ini
            $issues = array();
            // Siapkan nilai default untuk setiap kolom (null jika tidak ada)
            $tagihan = null;
            $terbayar = null;
            $sisa = null;
            $sisaSeharusnya = null;
            $paymentId = null;
            $returned = null; // kolom tambahan untuk nilai pengembalian

            if (empty($pymRows)) {
                // Jika tidak ada payment source sama sekali
                $issues[] = 'Tidak ada payment source (NO PAYMENT / BELUM QUOT)';
            }
            else {
                $pym = $pymRows[0];
                $tagihan  = isset($pym->tagihan)  ? (float) $pym->tagihan  : null;
                $terbayar = isset($pym->terbayar) ? (float) $pym->terbayar : null;
                $sisa     = isset($pym->sisa)     ? (float) $pym->sisa     : null;
                // simpan id payment untuk referensi
                $paymentId = isset($pym->id) ? $pym->id : null;

                // tangkap nilai returned jika ada (misal pengembalian dana)
                if (isset($pym->returned)) {
                    $returned = (float) $pym->returned;
                }

                // Hitung effective terbayar: terbayar dikurangi returned jika ada
                $effectiveTerbayar = $terbayar;
                if ($returned !== null && $terbayar !== null) {
                    $effectiveTerbayar = $terbayar - $returned;
                }

                // Hitung sisa termin seharusnya sesuai logika checker
                if ($effectiveTerbayar !== null) {
                    // Jika ada nilai returned (pengembalian), sisa seharusnya dihitung sebagai termin minus terbayar plus returned.
                    // Ini mencerminkan bahwa jumlah returned menambah kewajiban tagihan.
                    if ($returned !== null && $returned > 0) {
                        $sisaSeharusnya = $terminSeharusnya - ($terbayar !== null ? $terbayar : 0) + $returned;
                    }
                    else {
                        // Jika tidak ada return, gunakan logika standar: jika terbayar netto melebihi termin seharusnya,
                        // hitung sisa sebagai selisih harga project dengan terbayar netto; jika tidak, sisa adalah
                        // selisih termin seharusnya dengan terbayar netto.
                        if ($effectiveTerbayar >= $terminSeharusnya) {
                            $sisaSeharusnya = (float) $p->harga - $effectiveTerbayar;
                        }
                        else {
                            $sisaSeharusnya = $terminSeharusnya - $effectiveTerbayar;
                        }
                    }
                }

                // Cek tagihan vs termin seharusnya
                if ($tagihan !== null) {
                    $roundTagihan  = round($tagihan, 2);
                    $roundTerminSeharusnya = round($terminSeharusnya, 2);
                    if ($roundTagihan > $roundTerminSeharusnya) {
                        $issues[] = 'Tagihan lebih besar dari termin seharusnya';
                    }
                    elseif ($roundTagihan < $roundTerminSeharusnya) {
                        $issues[] = 'Tagihan lebih kecil dari termin seharusnya';
                    }
                }

                // Cek terbayar vs termin seharusnya (gunakan effective terbayar jika ada return)
                if ($effectiveTerbayar !== null && $terminSeharusnya > 0) {
                    $roundTerbayarEff = round($effectiveTerbayar, 2);
                    $roundTerminSeharusnya = round($terminSeharusnya, 2);
                    if ($roundTerbayarEff > $roundTerminSeharusnya) {
                        $issues[] = 'Terbayar lebih besar dari termin seharusnya';
                    }
                }

                // Cek terbayar melebihi nilai proyek termasuk PPN (project price * 1.11)
                $projectPricePPN = (float) $p->harga * 1.11;
                if ($terbayar !== null && $terbayar > $projectPricePPN) {
                    $issues[] = 'Terbayar melebihi nilai proyek (termasuk PPN)';
                }

                // Cek total terbayar + sisa melebihi nilai project (tanpa PPN)
                if ($effectiveTerbayar !== null && $sisa !== null) {
                    if (($effectiveTerbayar + $sisa) > (float) $p->harga) {
                        $issues[] = 'Terbayar + sisa melebihi nilai proyek';
                    }
                }

                // Cek sisa vs sisa seharusnya
                if ($sisa !== null && $sisaSeharusnya !== null) {
                    if (round($sisa, 2) != round($sisaSeharusnya, 2)) {
                        $issues[] = 'Sisa termin tidak sesuai dengan sisa seharusnya';
                    }
                }
            }

            // Jika ada isu maka tambahkan ke daftar invalid (dengan filter isu jika diperlukan).
            if (!empty($issues)) {
                // Jika ada filter isu, pastikan setidaknya satu isu sesuai filter
                $isMatch = true;
                if ($filterIssues !== null) {
                    $isMatch = false;
                    foreach ($issues as $is) {
                        if (in_array($is, $filterIssues)) {
                            $isMatch = true;
                            break;
                        }
                    }
                }

                if ($isMatch) {
                    if (isset($invalid[$p->id])) {
                        // gabungkan isu dan perbarui kolom yang masih kosong jika ada
                        $invalid[$p->id]['issues'] = array_unique(array_merge($invalid[$p->id]['issues'], $issues));
                        // hanya perbarui kolom jika masih null
                        if ($invalid[$p->id]['tagihan'] === null && $tagihan !== null) {
                            $invalid[$p->id]['tagihan'] = $tagihan;
                        }
                        if ($invalid[$p->id]['termin_seharusnya'] === null && $terminSeharusnya !== null) {
                            $invalid[$p->id]['termin_seharusnya'] = $terminSeharusnya;
                        }
                        if ($invalid[$p->id]['terbayar'] === null && $terbayar !== null) {
                            $invalid[$p->id]['terbayar'] = $terbayar;
                        }
                        if ($invalid[$p->id]['sisa'] === null && $sisa !== null) {
                            $invalid[$p->id]['sisa'] = $sisa;
                        }
                        if ($invalid[$p->id]['sisa_seharusnya'] === null && $sisaSeharusnya !== null) {
                            $invalid[$p->id]['sisa_seharusnya'] = $sisaSeharusnya;
                        }
                        if (!isset($invalid[$p->id]['payment_id']) || $invalid[$p->id]['payment_id'] === null) {
                            $invalid[$p->id]['payment_id'] = $paymentId;
                        }
                        if ($invalid[$p->id]['returned'] === null && $returned !== null) {
                            $invalid[$p->id]['returned'] = $returned;
                        }
                    }
                    else {
                        $invalid[$p->id] = array(
                            'project_id'        => $p->id,
                            'nama'              => $p->nama,
                            'tagihan'           => $tagihan,
                            'termin_seharusnya' => $terminSeharusnya,
                            'terbayar'          => $terbayar,
                            'sisa'              => $sisa,
                            'sisa_seharusnya'   => $sisaSeharusnya,
                            'payment_id'        => $paymentId,
                            'returned'          => $returned,
                            'issues'            => $issues,
                        );
                    }
                }
            }
        }

        // Kembalikan array yang sudah di-reindex (tanpa indeks berdasarkan project_id)
        return array_values($invalid);
    }

    public function fixInvalidPaymentProjects()
    {
        $this->load->model('MdlTransaksi');
        $tr = new MdlTransaksi();

        $invalidEntries = $this->getInvalidPaymentProjects(array(
            "Tagihan lebih besar dari termin seharusnya",
            "Sisa termin tidak sesuai dengan sisa seharusnya",
        ));

        if (empty($invalidEntries)) {
            echo "Tidak ada data invalid ditemukan.";
            return;
        }

        $headers = $invalidEntries[0];

        echo '<table width="100%" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;">';
        echo '<tr>';
        echo '<th>urut</th>';
        foreach ($headers as $key => $value) {
            if ($key === 'urut') continue;
            echo '<th>' . htmlspecialchars($key) . '</th>';
        }
        echo '<th width="15%">Aksi</th>';
        echo '</tr>';

        $this->db->trans_start();
        $urut = 0;
        foreach ($invalidEntries as $row) {
            if (!isset($row['payment_id']) || !$row['payment_id']) {
                continue;
            }

            $urut++;
            $row['urut'] = $urut;

            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['urut']) . '</td>';
            foreach ($row as $key => $value) {
                if ($key === 'urut') continue;
                echo '<td>';
                if (is_array($value)) {
                    echo '<ul>';
                    foreach ($value as $v) echo '<li>' . htmlspecialchars($v) . '</li>';
                    echo '</ul>';
                }
                else {
                    echo is_numeric($value) ? number_format($value, 2, ',', '.') : htmlspecialchars($value);
                }
                echo '</td>';
            }

            // Ambil nilai dasar
            $termin   = isset($row['termin_seharusnya']) ? (float)$row['termin_seharusnya'] : 0.0;
            $terbayar = isset($row['terbayar']) ? (float)$row['terbayar'] : 0.0;
            $sisaSeharusnya = isset($row['sisa_seharusnya']) ? (float)$row['sisa_seharusnya'] : 0.0;
            $returned = (isset($row['returned']) && $row['returned'] !== null) ? (float)$row['returned'] : 0.0;
            $tagihan  = isset($row['tagihan']) ? (float)$row['tagihan'] : 0.0;

            // --- Nilai baru yang akan diperbarui
            $tagihanNew = null;
            $sisaNew = null;
            $ppnNew = null;

            // Logika utama perbaikan
            if ($returned > 0) {
                // Jika returned >= termin, sisa nol
                if ($returned >= $termin) {
                    $sisaNew = 0;
                }
                else {
                    // returned sebagian, hitung ulang
                    $sisaNew = max(0, $termin - $terbayar - $returned);
                }
            }
            else {
                $effectivePaid = max(0, $terbayar - $returned);

                if ($effectivePaid == 0) {
                    $tagihanNew = $termin;
                    $sisaNew = $termin;
                    $ppnNew = $termin * 0.11;
                }
                elseif ($effectivePaid > 0 && $effectivePaid < $termin) {
                    $tagihanNew = $termin;
                    $sisaNew = $termin - $effectivePaid;
                    $ppnNew = $termin * 0.11;
                }
                else {
                    // Terbayar sama atau melebihi termin
                    $sisaNew = max(0, $termin - $effectivePaid);
                }
            }

            // --- Tambahan logika pembatas tagihan maksimal = termin ---
            if ($tagihan > $termin && $terbayar <= $termin) {
                $tagihanNew = $termin;
            }

            // --- Susun array untuk update
            $data = array();
            if ($tagihanNew !== null) $data['tagihan'] = $tagihanNew;
            if ($sisaNew !== null) $data['sisa'] = $sisaNew;
            if ($ppnNew !== null) $data['ppn'] = $ppnNew;

            echo '<td style="text-align:center;">';
            $jsonData = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');

            if (!empty($data)) {
                $where = array('id' => $row['payment_id']);
                $tr->updatePaymentSrc($where, $data);
                $query = $this->db->last_query();

                echo <<<HTML
                    <button type="button" onclick="updateRow({$row['payment_id']}, '{$jsonData}')">Update</button><br>$query
HTML;
            }
            else {
                echo "Tidak perlu update";
            }

            echo '</td></tr>';
        }

        echo '</table>';

        echo "
            <script>
                function updateRow(paymentId, jsonString) {
                    const data = JSON.parse(jsonString);
                    const url = '".base_url()."TerminCheckTools/updateRow?paymentId='+paymentId;
                    fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    })
                    .then(res => res.text())
                    .then(t => { alert('Update sukses!'); window.location.reload(); })
                    .catch(err => alert('Gagal: ' + err));
                }
            </script>
        ";
    }

    public function updateRow()
    {
        $payment_id = isset($_GET['paymentId']) ? $_GET['paymentId'] : null;
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        $this->load->model('MdlTransaksi');
        $tr = new MdlTransaksi();
        $this->db->trans_start();

        if (!empty($payment_id) && !empty($data)) {
            $where = array('id' => $payment_id);
            $tr->updatePaymentSrc($where, $data);
            $query = $this->db->last_query();
            $updated = $this->db->trans_commit();

            echo $updated ? "berhasil update<br>$query" : "gagal update<br>$query";
        }
        else {
            http_response_code(400);
            echo "Gagal: payment_id atau data kosong";
        }
    }

    public function viewProject(){

        $this->load->model('MdlTransaksi');
        $tr = new MdlTransaksi();

        $pairPayment = array();
        // Ambil payment source (jenis 588so target_jenis 7499)
        $tr->setFilters(array());
//        $tr->addFilter("project_id='" . $p->id . "'");
        $this->db->select("id,jenis,tagihan,terbayar,sisa,ppn,returned,project_id,transaksi_id");
        $tr->addFilter("jenis IN('588so','588st')");
        $tr->addFilter("target_jenis='7499'");
        $pymRows = $tr->lookUpPayment()->result();

        $this->db->trans_start();

        if(!empty($pymRows)){
            foreach($pymRows as $pymRow){
                $pairPayment[$pymRow->project_id] = (array)$pymRow;
            }
        }

        $arrProjectData = array();
        // ambil semua proyek aktif
        $this->db->select('id,dtime,cabang_id,lock,quot_status,project_start,project_start_id,quot_id,nama,transaksi_id,transaksi_no,harga_project_so,harga,closing_status,keterangan,status,trash');
        $this->db->where('status', 1);
//        $this->db->order_by('persen_progress', 'desc');
        $projects = $this->db->get('project_produk')->result();

        $spoList = [];
        $qoutList=[];
        if(!empty($projects)) {
            foreach ($projects as $quotRow) {
                if($quotRow->project_start_id*1 > 0){
                    $qoutList[] = $quotRow->project_start_id;
                }
                else{
                    if($quotRow->quot_id*1>0){
                        $qoutList[] = $quotRow->quot_id;
                    }
                    else{
                        $qoutList[] = $quotRow->transaksi_id;
                    }
                }
            }
        }

        $arrSettingTermin = [];
        $dataItems3 = $this->db->get('project_produk_items3')->result();
        foreach($dataItems3 as $i3Row){
            $arrSettingTermin[$i3Row->transaksi_id][] = (array)$i3Row;
        }

        $arrSettingDP = [];
        $dataItems4 = $this->db->get('project_produk_items4')->result();
        foreach($dataItems4 as $i4Row){
            $arrSettingDP[$i4Row->transaksi_id][] = (array)$i4Row;
        }

        $arrSettingRetensi = [];
        $dataItems5 = $this->db->get('project_produk_items5')->result();
        foreach($dataItems5 as $i5Row){
            $arrSettingRetensi[$i5Row->transaksi_id][] = (array)$i5Row;
        }

        $arrDataUpdated = [];
        $this->db->select('id,pym_id');
        $updateds = $this->db->get('transaksi_updated')->result();

        if(!empty($updateds)){
            foreach($updateds as $row){
                $arrDataUpdated[] = $row->pym_id;
            }
        }

        arrPrint($arrDataUpdated);
        matiHere(__LINE__);

        $arrReg = [];
//        $this->db->select('transaksi_id,main,items3,items4,items5,items7');
        $this->db->select('transaksi_id,main');
        $this->db->where_in('transaksi_id', $qoutList);
        $regs = $this->db->get('transaksi_data_registry')->result();
        if(!empty($regs)){
            foreach($regs as $regRow){
//                $tmpReg = array(
//                    "items3" => [],
//                    "items4" => [],
//                    "items5" => [],
//                    "items7" => [],
//                );
                if(!empty($regRow)){
                    foreach($regRow as $k => $vReg){
                        $tmpReg[$k] = blobDecode($vReg);
                    }
                }
                $arrReg[$regRow->transaksi_id] = (array)$tmpReg;
            }
        }

        if(!empty($projects)){
            foreach($projects as $prjRow){
                $arrProjectData[$prjRow->id] = (array)$prjRow;
                if(isset($pairPayment[$prjRow->id])){
                    $arrProjectData[$prjRow->id]['payment'] = $pairPayment[$prjRow->id];
                }
                if($prjRow->project_start_id*1 > 0 && isset($arrReg[$prjRow->project_start_id])){
                    $arrProjectData[$prjRow->id]['reg'] = $arrReg[$prjRow->project_start_id];
                }
                else{
                    if($prjRow->quot_id*1>0 && isset($arrReg[$prjRow->quot_id])){
                        $arrProjectData[$prjRow->id]['reg'] = $arrReg[$prjRow->quot_id];
                    }
                    else{
                        if(isset($arrReg[$prjRow->transaksi_id])){
                            $arrProjectData[$prjRow->id]['reg'] = $arrReg[$prjRow->transaksi_id];
                        }
                        else{
                            $arrProjectData[$prjRow->id]['reg'] = [];
                        }
                    }
                }
            }
        }

        cekMerah("TOTAL PROJECT = " . count($arrProjectData));
//        arrPrint($arrProjectData);

        $viewData = [];
        if(!empty($arrProjectData)){
            foreach($arrProjectData as $viewRow){

                $projectHarga_0 = isset($viewRow['harga']) ? $viewRow['harga'] : 0;
                $projectHarga_1 = isset($viewRow['reg']['main']['grand_total_ui']) ? $viewRow['reg']['main']['grand_total_ui'] : $projectHarga_0;
                $projectHarga = isset($viewRow['reg']['main']['projectHarga']) ? $viewRow['reg']['main']['projectHarga'] : $projectHarga_1;

                $project_nama = isset($viewRow['nama']) ? $viewRow['nama'] : "--";
                $project_cabang = isset($viewRow['cabang_id']) ? $viewRow['cabang_id'] : "--";
                $project_dtime = isset($viewRow['dtime']) ? $viewRow['dtime'] : "--";

                $termin_id          = isset($viewRow['payment']['id']) ? $viewRow['payment']['id'] : 0;
                $termin_trx_id      = isset($viewRow['payment']['transaksi_id']) ? $viewRow['payment']['transaksi_id'] : 0;
                $termin_tagihan     = isset($viewRow['payment']['tagihan']) ? $viewRow['payment']['tagihan'] : 0;
                $termin_terbayar    = isset($viewRow['payment']['terbayar']) ? $viewRow['payment']['terbayar'] : 0;
                $termin_sisa        = isset($viewRow['payment']['sisa']) ? $viewRow['payment']['sisa'] : 0;
                $termin_returned    = isset($viewRow['payment']['returned']) ? $viewRow['payment']['returned'] : 0;
                $termin_ppn         = isset($viewRow['payment']['ppn']) ? $viewRow['payment']['ppn'] : 0;
                $termin_ppn_sisa    = isset($viewRow['payment']['ppn_sisa']) ? $viewRow['payment']['ppn_sisa'] : 0;

                //TERMIN FROM SETTING
                $nilai_termin = 0;
                $nilai_persen_termin = 0;
                $nilai_downpayment = 0;
                $nilai_persen_downpayment = 0;
                $nilai_retensi = 0;
                $nilai_persen_retensi = 0;
                $arrTermin = isset($arrSettingTermin[$viewRow['transaksi_id']]) && count($arrSettingTermin[$viewRow['transaksi_id']]) > 0 ? $arrSettingTermin[$viewRow['transaksi_id']] : [];
                $arrDownPayment = isset($arrSettingDP[$viewRow['transaksi_id']]) && count($arrSettingDP[$viewRow['transaksi_id']]) > 0 ? $arrSettingDP[$viewRow['transaksi_id']] : [];
                $arrRetensi = isset($arrSettingRetensi[$viewRow['transaksi_id']]) && count($arrSettingRetensi[$viewRow['transaksi_id']]) > 0 ? $arrSettingRetensi[$viewRow['transaksi_id']] : [];

                if( !empty($arrTermin) ){
                    foreach($arrTermin as $setRow){
                        $nilai_termin += (float) $setRow['jumlah'];
                        $nilai_persen_termin += (float) $setRow['persen'];
                    }
                }

                if( !empty($arrDownPayment) ){
                    foreach($arrDownPayment as $setDp){
                        $nilai_downpayment += (float) $setDp['jumlah'];
                        $nilai_persen_downpayment += (float) $setDp['persen'];
                    }
                }

                if( !empty($arrRetensi) ){
                    foreach($arrRetensi as $setRetensi){
                        $nilai_retensi += (float) $setRetensi['jumlah'];
                        $nilai_persen_retensi += (float) $setRetensi['persen'];
                    }
                }

//                arrPrint($nilai_termin);
//                matiHere(__LINE__);
//                if( $projectHarga>0 ){

//                if($termin_id > 0 && $termin_terbayar == 0 && $termin_returned == 0 && $termin_tagihan > $projectHarga){
//                if($termin_id>0 && $nilai_termin>0 && $termin_terbayar > 0 && $termin_terbayar > $nilai_termin){
//                if($termin_id>0 && $nilai_termin>0 && $termin_terbayar > 0 && $termin_terbayar < $nilai_termin){
//                if($termin_id > 0 && $termin_terbayar > 0 && $termin_returned == 0 && $termin_tagihan > $projectHarga){
//                if($termin_id > 0 && $termin_tagihan > $projectHarga){
//                if($nilai_termin > 0 && $termin_id > 0 && $termin_tagihan == $projectHarga){
//                if($nilai_termin == 0 && $termin_id > 0 && $termin_tagihan > $projectHarga){
                if($nilai_termin > 0 && $termin_id > 0 && $termin_tagihan > $projectHarga){

                    $termin_exclusive   = $nilai_termin / (1 + (11 / 100));
                    $termin_ppn         = $nilai_termin-$termin_exclusive;
                    $project_exclusive  = $projectHarga / (1 + (11 / 100));
                    $project_ppn        = $projectHarga-$project_exclusive;

                    $ids_ref_enc = blobEncode(array("$termin_trx_id"=>"$termin_trx_id"));

                    $this->db->select('id,jenis, jenis_label, nomer_top, nomer, transaksi_nilai');
                    $this->db->where('ids_ref', $ids_ref_enc);
                    $this->db->where('jenis', "7499");
                    $this->db->where('trash_4', 0);
                    $arrTerminBayar = $this->db->get('transaksi')->result();

                    $total_pym_termin = 0;
                    if(!empty($arrTerminBayar)){
                        foreach($arrTerminBayar as $trmPym){
                            $total_pym_termin += $trmPym->transaksi_nilai;
                        }
                    }

                    $viewData[$viewRow['id']]["arrTerminBayar"] = $arrTerminBayar;
                    $viewData[$viewRow['id']]["total_pym_termin"] = $total_pym_termin;

                    $data = [];
                    #tahap 1 (tagihan lebih besar dari nilai project (tanpa ppn))
                    if($termin_id > 0 && $termin_terbayar == 0 && $termin_returned == 0 && $termin_tagihan > $projectHarga){
                       $data["tagihan"] = $nilai_termin > 0 ? $termin_exclusive : $project_exclusive;
                       $data["sisa"]    = $nilai_termin > 0 ? $termin_exclusive : $project_exclusive;
                       $data["ppn"]     = $nilai_termin > 0 ? $termin_ppn : $project_ppn;
                    }

                    #tahap 2 (nilai terbayar lebih besar dari nilai termin seharusnya)
                    if($termin_id>0 && $nilai_termin>0 && $termin_terbayar > 0 && $termin_terbayar > $nilai_termin){

                        $ids_ref_enc = blobEncode(array("$termin_trx_id"=>"$termin_trx_id"));

                        $this->db->select('id,jenis,jenis_label,nomer_top,nomer,transaksi_nilai,ids_ref');
                        $this->db->where('ids_ref', $ids_ref_enc);
                        $this->db->where('jenis', "7499");
                        $this->db->where('trash_4', 0);
                        $arrTerminBayar = $this->db->get('transaksi')->result();

                        $total_pym_termin = 0;
                        if(!empty($arrTerminBayar)){
                            foreach($arrTerminBayar as $trmPym){
                                $total_pym_termin += $trmPym->transaksi_nilai;
                            }
                        }

                        $viewData[$viewRow['id']]["arrTerminBayar"] = $arrTerminBayar;
                        $viewData[$viewRow['id']]["total_pym_termin"] = $total_pym_termin;

                        $data["tagihan"] = $projectHarga;
                        $data["sisa"]    = $projectHarga - $termin_terbayar;
                        if( $projectHarga - $termin_terbayar < 0 ){
                            $data["terbayar"] = $termin_terbayar/1.11;
                            $data["sisa"] = bcsub($projectHarga,($termin_terbayar/1.11),6);
                        }
                        $data["ppn"]     = $projectHarga*1.11 - $projectHarga;
                    }

                    #tahap 3 (nilai terbayar lebih kecil dari nilai termin seharusnya)
                    if($termin_id>0 && $nilai_termin>0 && $termin_terbayar > 0 && $termin_terbayar < $nilai_termin){
                        $data["tagihan"] = round($nilai_termin/1.11);
                        $data["sisa"]    = bcsub(round($nilai_termin/1.11),$termin_terbayar,6);
                        $data["ppn"]     = $termin_ppn;
                    }

                    #tahap 4 (nilai terbayar lebih besar dari nilai project)
                    if($termin_id > 0 && $termin_terbayar > 0 && $termin_returned == 0 && $termin_tagihan > $projectHarga){
                        $data["tagihan"] = $nilai_termin > 0 ? $termin_exclusive : $project_exclusive;
                        $data["sisa"]    = $nilai_termin > 0 ? $termin_exclusive : $project_exclusive;
                        $data["ppn"]     = $nilai_termin > 0 ? $termin_ppn : $project_ppn;
                    }

                    #tahap 5 (nilai tagihan lebih besar dari nilai project)
                    if($termin_id > 0 && $termin_tagihan > $projectHarga){
                        $data["tagihan"] = $nilai_termin > 0 ? $termin_exclusive : $project_exclusive;
                        $data["sisa"]    = $nilai_termin > 0 ? $termin_exclusive : $project_exclusive;
                        $data["ppn"]     = $nilai_termin > 0 ? $termin_ppn : $project_ppn;
                    }

                    #tahap 6 (nilai tagihan sama nilai project)
                    if($nilai_termin > 0 && $termin_id > 0 && $termin_tagihan == $projectHarga){
                        $data["tagihan"] = $nilai_termin > 0 ? $termin_exclusive : $project_exclusive;
                        $data["sisa"]    = $termin_terbayar > $nilai_termin ? 0 : $termin_exclusive-$termin_terbayar;
                        $data["ppn"]     = $termin_ppn;
                    }

                    #tahap 7 (nilai tagihan lebih besar nilai project)
                    if($nilai_termin == 0 && $termin_id > 0 && $termin_tagihan > $projectHarga){
                        $data["tagihan"] = $projectHarga;
                        $data["sisa"]    = $projectHarga-$termin_terbayar;
                        if( ($projectHarga-$termin_terbayar) < 0 ){
                            $data["sisa"]    = 0;
                            $data["terbayar"]    = $termin_terbayar/1.11;
                        }
                        $data["ppn"]     = $project_ppn;
                    }

                    #tahap 8 (nilai tagihan sama nilai project)
                    if($nilai_termin > 0 && $termin_id > 0 && $termin_tagihan > $projectHarga){
                        $data["tagihan"] = $projectHarga;
                        $data["sisa"]    = $projectHarga-$termin_terbayar;
                        if( ($projectHarga-$termin_terbayar) < 0 ){
                            $data["sisa"]    = 0;
                            $data["terbayar"]    = $termin_terbayar/1.11;
                        }
                        $data["ppn"]     = $project_ppn;
                    }

                    $viewData[$viewRow['id']]['id'] = $termin_id;
                    $viewData[$viewRow['id']]['nama'] = $project_nama;
                    $viewData[$viewRow['id']]['cabang'] = $project_cabang;
                    $viewData[$viewRow['id']]['dtime'] = $project_dtime;

                    // termin + dp + retensi dari settingan
                    $viewData[$viewRow['id']]['nilai_setting_termin'] = $nilai_termin;
                    $viewData[$viewRow['id']]['nilai_setting_downpayment'] = $nilai_downpayment;
                    $viewData[$viewRow['id']]['nilai_setting_retensi'] = $nilai_retensi;
                    $viewData[$viewRow['id']]['nilai_setting_termin_persen'] = $nilai_persen_termin;
                    $viewData[$viewRow['id']]['nilai_setting_downpayment_persen'] = $nilai_persen_downpayment;
                    $viewData[$viewRow['id']]['nilai_setting_retensi_persen'] = $nilai_persen_retensi;

                    //nilai project dari SO
                    $viewData[$viewRow['id']]['nilai_project'] = $projectHarga; //ini tanpa ppn
                    $viewData[$viewRow['id']]['nilai_project_ppn'] = $projectHarga*1.11 - $projectHarga;
                    $viewData[$viewRow['id']]['nilai_project_nppn'] = $projectHarga*1.11;

                    //termin dari payment source
                    $viewData[$viewRow['id']]['termin_tagihan'] = $termin_tagihan;
                    $viewData[$viewRow['id']]['termin_terbayar'] = $termin_terbayar;
                    $viewData[$viewRow['id']]['termin_sisa'] = $termin_sisa;
                    $viewData[$viewRow['id']]['termin_returned'] = $termin_returned;
                    $viewData[$viewRow['id']]['termin_ppn'] = $termin_ppn;
                    $viewData[$viewRow['id']]['termin_ppn_sisa'] = $termin_ppn_sisa;

                    //builder data untuk update ke payment source
                    $viewData[$viewRow['id']]['data_update'] = $data;

                    if(!empty($data)){
                        $where = array('id' => $termin_id);
                        $oldData = $this->db->get_where('transaksi_payment_source', $where)->row_array();
                        $fieldsToCheck = ['tagihan', 'terbayar', 'sisa', 'ppn'];
                        $isDifferent = false;
                        $epsilon = 0.02; // toleransi perbedaan nilai, ubah sesuai kebutuhan
                        $viewData[$viewRow['id']]['isDifferent'] = 0;
                        foreach ($fieldsToCheck as $key) {
                            $oldVal = isset($oldData[$key]) ? (float)$oldData[$key] : null;
                            $newVal = isset($data[$key]) ? (float)$data[$key] : null;
                            // Skip jika newVal kosong
                            if ($newVal === null) continue;
                            // Cek apakah numeric
                            if (is_numeric($oldVal) && is_numeric($newVal)) {
                                // Bandingkan dengan toleransi epsilon
                                if (abs($oldVal - $newVal) > $epsilon) {
                                    $isDifferent = true;
                                    $viewData[$viewRow['id']]['isDifferent'] = 1;
                                    $viewData[$viewRow['id']]['isDifferent_oldVal_'.$key] = $oldVal;
                                    $viewData[$viewRow['id']]['isDifferent_newVal_'.$key] = $newVal;
                                    break;
                                }
                            }
                            else {
                                // Untuk nilai non-numeric (jaga-jaga)
                                if ($oldVal != $newVal) {
                                    $isDifferent = true;
                                    $viewData[$viewRow['id']]['isDifferent'] = 1;
                                    $viewData[$viewRow['id']]['isDifferent_oldVal_'.$key] = $oldVal;
                                    $viewData[$viewRow['id']]['isDifferent_newVal_'.$key] = $newVal;
                                    break;
                                }
                            }
                        }
                        if($isDifferent){
                            $where = array('id' => $termin_id);
                            $tr->updatePaymentSrc($where, $data);
                            $query = $this->db->last_query();
                            cekOrange($query);
                            $dataLog = array(
                                "new" => $data,
                                "old" => array(
                                    "tagihan" => $termin_tagihan,
                                    "terbayar" => $termin_terbayar,
                                    "sisa" => $termin_sisa,
                                    "ppn" => $termin_ppn,
                                ),
                            );
                            $insert_data = array(
                                'pym_id' => $termin_id,
                                'isi'    => json_encode($dataLog)
                            );
                            $this->db->insert('transaksi_updated', $insert_data);
                            $query_log = $this->db->last_query();
                            cekBiru($query_log);
                        }
                        else{
                            cekHijau("data sudah sama, tidak perlu update ($termin_id)");
                        }
                    }
                }
            }
        }

//        cekHijau("FILTERED: " . count($viewData));
//        arrPrint($viewData);

        matiHere("MATI DULU BELUM COMMIT");
        $commit = $this->db->trans_commit();

        if($commit){
            cekHijau("TERSIMPAN");
        }
        else{
            cekMerah("GAGAL");
        }


    }
}