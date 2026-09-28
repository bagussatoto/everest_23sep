<style>
    .highlighted { background-color: yellow; }
</style>

<div id="serialHeader" class="text-center text-red fa-2x" style="margin-bottom:10px;">
    double click pada serial untuk copy dan menandai
</div>

<table id="tblSerial" class="table dataTable compact table-bordered table-hover" style="width:100%">
    <thead>
    <tr>
        <th>No</th>
        <th>Tgl Masuk</th>
        <th>SKU</th>
        <th>Serial</th>
        <th>Jumlah</th>
        <th>QR</th>
    </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
    $(function(){

        var ajaxUrl = "<?= $ajax_url ?>";
        var produk_id = "<?= $produk_id ?>";
        var cabang_id = "<?= $cabang_id ?>";
        var gudang_id = "<?= $gudang_id ?>";

        var dt = $('#tblSerial').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 25,
            ajax: {
                url: ajaxUrl,
                type: "GET",
                data: function(d){
                    d.produk_id = produk_id;
                    d.cabang_id = cabang_id;
                    d.gudang_id = gudang_id;
                },
                dataSrc: function(json){
                    // header + tombol
                    if (json.extra && json.extra.headerHtml) {
                        $('#serialHeader').html(json.extra.headerHtml);
                    }
                    return json.data;
                }
            },
            columns: [
                { data: 'no',           orderable: false },
                { data: 'dtime' },
                { data: 'extern2_nama' },
                { data: 'extern_nama' },
                { data: 'qty_debet' },
                { data: 'qr',           orderable: false }
            ],
            drawCallback: function(){

                // default QR hidden
                $('.qrcode').hide();

                // dblclick copy + highlight
                $('#tblSerial .serial-span').off('dblclick').on('dblclick', function(e){
                    e.preventDefault();
                    $(this).addClass('highlighted');

                    const selection = window.getSelection();
                    selection.removeAllRanges();
                    const range = document.createRange();
                    range.selectNodeContents(this);
                    selection.addRange(range);

                    const temp = $('<textarea>');
                    $('body').append(temp);
                    temp.val($(this).text()).select();
                    document.execCommand('copy');
                    temp.remove();
                });

                // toggle QR (bind ulang karena header di-render ulang dari ajax)
                $('#toggle').off('click').on('click', function(){
                    $('.qrcode').slideToggle(200);
                    var icon = $(this).find('i');
                    if (icon.hasClass('fa-eye')) {
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });

                // copy_serial (ambil dari row yang sedang tampil)
                $('#copy_serial').off('click').on('click', function(){
                    const jumlah = parseInt($('#jumlah_serial').val(), 10);
                    const tipe = $('#jenis_serial').val();

                    if (isNaN(jumlah) || jumlah <= 0) {
                        alert("Jumlah serial tidak valid.");
                        return;
                    }

                    const spans = document.querySelectorAll('#tblSerial td > span.serial-span');
                    const hasil = [];

                    for (let i = 0; i < spans.length && hasil.length < jumlah; i++) {
                        const span = spans[i];
                        const teks = span.textContent.trim();
                        if (span.dataset.used === "1") continue;

                        if (tipe !== 'ANY') {
                            if (!teks.endsWith(':' + tipe)) continue;
                        }

                        hasil.push(teks);
                        span.dataset.used = "1";
                        span.style.backgroundColor = "yellow";
                    }

                    if (hasil.length === 0) {
                        alert('Tidak ada serial baru dengan tipe ' + tipe);
                        return;
                    }

                    const textToCopy = hasil.join(',');
                    navigator.clipboard.writeText(textToCopy)
                        .then(() => alert('Berhasil disalin ke clipboard.'))
                        .catch(() => alert('Gagal menyalin.'));
                });
            }
        });

    });
</script>
