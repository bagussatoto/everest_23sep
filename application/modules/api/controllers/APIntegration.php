<?php
defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(0);
ini_set('display_errors', 0);

class APIntegration extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $log = base64_encode(base64_encode(json_encode(session())));
        $aplikasi = $_SERVER['HTTP_HOST'];

        $modul = array(
            "whatsapp" => array(
                "label" => "Whatsapp",
                "db_target" => "settings",
                "sub_label" => "integrasi Whatsapp Sender untuk mengirim INVOICE",
                "form" => array(
                    array(
                        "label" => "Unik ID",
                        "id" => "whatsapp_unik_id",
                        "type" => "input",
                        "placeholder" => "masukan Unik ID",
                    ),
                    array(
                        "label" => "Secret ID",
                        "id" => "whatsapp_secret_id",
                        "type" => "input",
                        "placeholder" => "masukan secret ID",
                    ),
                    array(
                        "label" => "Status Sender",
                        "id" => "whatsapp_status",
                        "type" => "input",
                        "readonly" => true,
                        "defaultValue" => 0,
                        "value_alias" => array(
                            0 => "NON ACTIVE",
                            1 => "ACTIVATED",
                        ),
                    ),
                    array(
                        "label" => "simpan",
                        "id" => "btn_simpan",
                        "type" => "button",
                        "btn_type" => "button",
                        "add_class" => "btn-info",
                        "sub_function_js" => "<script>
                                top.$('#btn_simpan').on('click', function() {
                                    var unik_id = top.$('#whatsapp_unik_id').val();
                                    var secret_id = top.$('#whatsapp_secret_id').val();
                                    var error = {};
                                    if (!unik_id) {
                                        error['unik_id'] = 'Unik ID harus diisi';
                                    }
                                    if (!secret_id) {
                                        error['secret_id'] = 'Secret ID harus diisi';
                                    }
                                    if (Object.keys(error).length === 0) {
                                        console.log('Validasi OK, bisa lanjut simpan.');
                                        top.swal({
                                            title: 'Sedang memproses...',
                                            text: 'Harap tunggu sebentar...',
                                            allowOutsideClick: false,
                                            showConfirmButton: false,
                                            onOpen: () => {
                                                top.swal.showLoading();
                                            }
                                        });
                                        $.ajax({
                                            url: 'preValidationWhatsapp',
                                            type: 'POST',
                                            data: {
                                                whatsapp_unik_id: unik_id,
                                                whatsapp_secret_id: secret_id
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                if (response.status) {
                                                    top.$.ajax({
                                                        url: 'simpanSetting',
                                                        type: 'POST',
                                                        data: {
                                                            whatsapp_unik_id: unik_id,
                                                            whatsapp_secret_id: secret_id,
                                                            whatsapp_status: top.$('#whatsapp_status').val()
                                                        },
                                                        success: function(result) {
                                                            dataResult = JSON.parse(result);
                                                            if(dataResult.status){
                                                                top.swal.close();
                                                                top.swal('Sukses', 'Data berhasil disimpan!', 'success');
                                                                setTimeout(function(){
                                                                    top.swal.close();
                                                                    top.window.location.reload();
                                                                }, 1500);
                                                            }
                                                            else{
                                                                top.swal.close();
                                                                top.swal('Gagal', 'Data gagal disimpan!', 'warning');
                                                                setTimeout(function(){
                                                                    top.swal.close();
                                                                    top.window.location.reload();
                                                                }, 1500);
                                                            }
                                                        },
                                                        error: function() {
                                                            top.swal('Error', 'Gagal menyimpan data!', 'error');
                                                        }
                                                    });
                                                }
                                                else {
                                                    top.swal('PERIKSA KEMBALI', 'Kombinasi Token tidak valid!', 'warning');
                                                }
                                            },
                                            error: function() {
                                                top.swal('Error', 'Terjadi kesalahan saat validasi', 'error');
                                            }
                                        });
                                    }
                                    else {
                                        var text_error = '';
                                        top.jQuery.each(error, function(a, b) {
                                            text_error += b + '<br>';
                                        });
                                        top.swal('PERIKSA KEMBALI', text_error, 'warning');
                                    }
                                });
                            </script>
                        ",
                    ),
                    array(
                        "label" => "Test Kirim",
                        "id" => "test_kirim_advanced",
                        "type" => "button",
                        "btn_type" => "button",
                        "add_class" => "btn-primary hidden",
                        "add_icon" => "<i class='fa fa-whatsapp'></i>",
                        "form_method" => "POST",
                        "form_url" => "simpanSetting?debuger=1", //tanpa .php ya
                        "remote_js" => array(),
                        "remote_css" => array(),
                        "sub_function_js" => "<script>
                            var upload_files = 'https://cdn.mayagrahakencana.com/images/upload/files';
                            var upload_doc = 'https://cdn.mayagrahakencana.com/images/upload/document';
                            var api_kirim_wa = 'https://cdn.mayagrahakencana.com/images/WASender/terima';

                            function checkerActive(){
                                const icon = document.querySelector('#whatsapp_status i');
                                if (icon && icon.classList.contains('fa-check-square')) {
                                    $('#test_kirim_advanced').removeClass('hidden');
                                }
                            }

                            checkerActive();

                            function uploadToCorrectAPI() {
                                let fileInput = document.getElementById('upload_file');
                                let file = fileInput.files[0];
                                let formData = new FormData();
                                formData.append('file', file);
                                formData.append('server_source', 'whatsapp');
                                let fileType = file.type;
                                let imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                                let docTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                                localStorage.nomor_tujuan = document.getElementById('nomor_tujuan').value
                                localStorage.message = document.getElementById('message').value
                                let url = '';
                                if (imageTypes.includes(fileType)) {
                                    url = upload_files;
                                }
                                else if (docTypes.includes(fileType)) {
                                    url = upload_doc;
                                }
                                else {
                                    alert('Tipe file tidak didukung');
                                    return;
                                }
                                fetch(url, {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.status === 'success') {
                                        document.getElementById('media_url').value = data.full_url_ori;
                                        localStorage.nomor_tujuan = ''
                                        localStorage.message = ''
                                    }
                                    else {
                                        top.swal('Gagal', data.error || 'Upload gagal', 'error');
                                        setTimeout(function(){
                                            top.$('#test_kirim_advanced').click();
                                        }, 1500)
                                    }
                                })
                                .catch(err => {
                                    top.swal('Error', 'Terjadi kesalahan saat upload', 'error');
                                });
                            }
                            function openSwalForm() {
                                top.swal({
                                    title: 'Kirim Pesan WA',
                                    html:
                                        `<input id=\"nomor_tujuan\" class=\"swal2-input\" placeholder=\"Nomor Tujuan 62/08(pisahkan dengan koma untuk multi nomer)\">
                                         <textarea id=\"message\" class=\"swal2-textarea\" placeholder=\"Pesan\"></textarea>
                                         <input type='file' id=\"upload_file\" class=\"swal2-file\" onchange=\"uploadToCorrectAPI()\">
                                         <input type='hidden' id='aplikasi' value='$aplikasi'>
                                         <input type='hidden' id='log' value='$log'>
                                         <input type='hidden' id='media_url'>`,
                                    //focusConfirm: false,
                                    showCancelButton: true,
                                    confirmButtonText: 'Kirim',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    onOpen: function(){
                                        document.getElementById('nomor_tujuan').value = localStorage.nomor_tujuan != undefined ? localStorage.nomor_tujuan : '';
                                        document.getElementById('message').value = localStorage.message != undefined ? localStorage.message : '';
                                    },
                                    preConfirm: () => {
                                        const pesan_test = '::ini adalah pesan testing::';
                                        const nomor = document.getElementById('nomor_tujuan').value;
                                        const pesan = document.getElementById('message').value + '\\n\\n\\n' + pesan_test;
                                        const media = document.getElementById('media_url').value;
                                        const unik_id = document.getElementById('whatsapp_unik_id').value;
                                        const secret_id = document.getElementById('whatsapp_secret_id').value;
                                        const log = document.getElementById('log').value;
                                        const aplikasi = document.getElementById('aplikasi').value;
                                        return send_test_advanced(nomor, pesan, media, unik_id, secret_id, aplikasi, log);
                                    }
                                });
                            }
                            function send_test_advanced(nomor_tujuan, message, media_url, unik_id, secret_id, aplikasi, log) {
                                top.swal({
                                    title: 'Sedang mengirim test...',
                                    text: 'Harap tunggu sebentar...',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    onOpen: () => {
                                        top.swal.showLoading();
                                    }
                                });
                                fetch(api_kirim_wa, {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/json'},
                                    body: JSON.stringify({
                                        nomor_tujuan: nomor_tujuan,
                                        message: message,
                                        media_url: media_url,
                                        unik_id: unik_id,
                                        secret_id: secret_id,
                                        log: log,
                                        aplikasi: aplikasi,
                                    })
                                })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('HTTP status bukan 200: ' + response.status);
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    console.log(data);
                                    if (data.status == 'ok') {
                                        top.setTimeout(function(){
                                            top.swal('Sukses', data.msg, 'success');
                                        }, 2500);
                                    }
                                    else {
                                        top.setTimeout(function(){
                                            top.swal('Gagal', 'Server balikin gagal', 'error');
                                        }, 2500);
                                    }
                                })
                                .catch(error => {
                                    top.setTimeout(function(){
                                        top.swal('Gagal', 'Gagal mengirim pesan: ' + error.message, 'error');
                                    }, 2500);
                                });
                            }
                            top.$('#test_kirim_advanced').on('click', function(){
                                top.openSwalForm();
                            });
                        </script>",
                    ),
                ),
            ),
        );

        $defaultModulValue = array();
        foreach($modul as $modul_id => $rowModul){
            $untuk = $modul_id;
            $db_target = $rowModul['db_target'];
            $this->db->where("untuk='$untuk'");
            $valContent = $this->db->get($db_target)->result();
            if(!empty($valContent)){
                foreach($valContent as $ky => $rowValue){
                    $defaultModulValue[$modul_id][$rowValue->jenis] = $rowValue->nilai;
                }
            }
        }
        $data = array(
            "mode" => "forms",
            "modul" => $modul,
            "default_modul_value" => $defaultModulValue,
        );
        $this->load->view('setup', $data);
    }

    public function simpanSetting() {
        if ($this->input->post()) {
            // Ambil data dari form
            $unik_id = $this->input->post('whatsapp_unik_id');
            $secret_id = $this->input->post('whatsapp_secret_id');
            $status_sender = $this->input->post('whatsapp_status');

            $status_whatsapp = $this->validasiNomorWhatsApp($unik_id, $secret_id);
            $data = array();
            $updated = array();
            if ($unik_id) {
                $existing = $this->db->get_where('settings', ['jenis' => 'whatsapp_unik_id'])->row_array();
                $nilai_lama = "";
                $nilai_baru = $unik_id;
                if ($existing) {
                    $nilai_lama = $existing[0]["nilai"];
                    $this->db->where('jenis', 'whatsapp_unik_id');
                    $update_data = array(
                        'nilai' => $unik_id,
                        'last_update' => date('Y-m-d H:i:s')
                    );
                    if($this->db->update('settings', $update_data)){
                        $updated[] = true;
                    }
                }
                else {
                    $data[] = array(
                        'jenis' => 'whatsapp_unik_id',
                        'untuk' => 'whatsapp',
                        'nilai' => $unik_id,
                        'trash' => 0,
                        'status' => 1,
                        'last_update' => date('Y-m-d H:i:s')
                    );
                }

                $this->db->insert('history_perubahan_setting', [
                    'jenis' => 'whatsapp_unik_id',
                    'nilai_lama' => $nilai_lama,
                    'nilai_baru' => $nilai_baru,
                    'admin_id' => $this->session->login['id'],
                ]);
            }

            if ($secret_id) {
                $existing = $this->db->get_where('settings', ['jenis' => 'whatsapp_secret_id'])->row_array();
                $nilai_lama = "";
                $nilai_baru = $secret_id;
                if ($existing) {
                    $nilai_lama = $existing[0]["nilai"];
                    $this->db->where('jenis', 'whatsapp_secret_id');
                    $update_data = array(
                        'nilai' => $secret_id,
                        'last_update' => date('Y-m-d H:i:s')
                    );
                    if($this->db->update('settings', $update_data)){
                        $updated[] = true;
                    }
                }
                else {
                    $data[] = array(
                        'jenis' => 'whatsapp_secret_id',
                        'untuk' => 'whatsapp',
                        'nilai' => $secret_id,
                        'trash' => 0,
                        'status' => 1,
                        'last_update' => date('Y-m-d H:i:s')
                    );
                }

                $this->db->insert('history_perubahan_setting', [
                    'jenis' => 'whatsapp_secret_id',
                    'nilai_lama' => $nilai_lama,
                    'nilai_baru' => $nilai_baru,
                    'admin_id' => $this->session->login['id'],
                ]);
            }

            $existing_status = $this->db->get_where('settings', ['jenis' => 'whatsapp_status'])->row_array();

            $status_data = array(
                'jenis' => 'whatsapp_status',
                'untuk' => 'whatsapp',
                'nilai' => $status_whatsapp ? 1 : 0,
                'trash' => 0,
                'status' => 1,
                'last_update' => date('Y-m-d H:i:s')
            );

            if ($existing_status) {
                $this->db->where('jenis', 'whatsapp_status');
                if($this->db->update('settings', $status_data)){
                    $updated[] = true;
                }
            }
            else {
                $data[] = $status_data;
            }

            if (!empty($data)) {
                $simpan = $this->db->insert_batch('settings', $data);
                if ($simpan) {
//                    echo "Data berhasil disimpan!";
//                    echo $this->db->last_query();

                    $result = array(
                        "status" => 1,
                    );
                    echo json_encode($result);
                }
                else {
//                    echo "Data gagal disimpan!";
//                    echo json_encode($data);
                    $result = array(
                        "status" => 0,
                    );
                    echo json_encode($result);
                }
            }
            else{

                if($updated){
                    $result = array(
                        "status" => 1,
                    );
                    echo json_encode($result);
                }
                else{
//                    echo "data kosong nih";
                    $result = array(
                        "status" => 0,
                    );
                    echo json_encode($result);
                }

            }
        }
        else {
//            echo "tak ada post, buka dari form aslinya brooooo";
            $result = array(
                "status" => 0,
            );
            echo json_encode($result);
        }
    }

    private function validasiNomorWhatsApp($unik_id, $secret_id, $nomor_wa="08985193131") {
        $url = "https://whapify.id/api/validate/whatsapp?secret=" . $secret_id . "&unique=" . $unik_id . "&phone=" . $nomor_wa;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($http_code === 200) {
            $response_data = json_decode($response, true);
            if (isset($response_data['status']) && $response_data['status'] === 200) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            echo "Error: HTTP Code " . $http_code . ", Response: " . $response;
            return false;
        }

        curl_close($ch);
    }

    public function preValidationWhatsapp(){
        if ($this->input->post()) {
            $unik_id = $this->input->post('whatsapp_unik_id');
            $secret_id = $this->input->post('whatsapp_secret_id');
            $status_whatsapp = $this->validasiNomorWhatsApp($unik_id, $secret_id);
            echo json_encode(array("status"=>$status_whatsapp));
        }
    }

    private function getWaSetting(){

    }

    public function challangeWhatsapp(){

        $unik_id = $this->input->post('whatsapp_unik_id');
        $secret_id = $this->input->post('whatsapp_secret_id');
        $status_sender = $this->input->post('whatsapp_status');

        $waSetting = $this->getWaSetting();

        $this->load->library("WaSender");
        $lt = New WaSender();

        $lt->createPDF("penjualan", "852.123456.184", $waSetting);
    }

    public function generate_pdf() {
        $encodedOption = $_GET['encodedOption'];
        $decoded = json_decode(base64_decode($encodedOption), true);

        $url = isset($decoded['url']) ? $decoded['url'] : '';
        $outputFile = isset($decoded['save_path']) ? $decoded['save_path'] : '';
        $publicUrl = isset($decoded['cdn_url']) ? $decoded['cdn_url'] : '';

        $output = '';
        $waited = 0;

        if (!file_exists($outputFile)) {
            $escapedUrl = escapeshellarg($url);
            $escapedOutput = escapeshellarg($outputFile);
            $command = "/usr/bin/node /var/www/cdn/images/application/controllers/singleGenPDF.js $escapedUrl $escapedOutput 2>&1";
            $output = shell_exec($command);
            $maxWait = 10;
            while (!file_exists($outputFile) && $waited < $maxWait) {
                sleep(1);
                $waited++;
            }
        }

        echo json_encode(array(
            'outputed' => $output,
            'command' => $command,
            'url' => $publicUrl,
            'output' => $output ? $output : 'Executed',
            'exists' => file_exists($outputFile),
            'time_waited' => $waited
        ));
    }

}