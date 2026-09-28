<?php
$contens = "";
$p = New Layout("$title", "$subTitle", "application/template/default_ledger.html");

$list_data = "";
$list_data .= "<style>
    .pagination{ margin:unset; }
    a:hover{ color:red !important; }
</style>";

// =====================
// PANEL FILTER (q + date)
// =====================
$list_data .= "<div class='panel'>";
$list_data .= "<div class='row'>";

// search box (pakai datatable search, bukan reload halaman)
$list_data .= "<div class='col-md-4'>";
$list_data .= "  <div class='input-group'>";
$list_data .= "    <span class='input-group-btn'>
                    <a class='btn btn-default' href='javascript:void(0)' title='remove keyword' data-toggle='tooltip'
                       onclick=\"$('#q').val(''); if(window.dtTbl){ dtTbl.search('').draw(); }\">
                       <span class='glyphicon glyphicon-remove'></span>
                    </a>
                  </span>";
$list_data .= "    <input type='text' name='q' id='q' class='form-control' value='' placeholder='type to search..' onfocus='this.select()'>";
$list_data .= "    <span class='input-group-btn'>
                    <a class='btn btn-default' href='javascript:void(0)' title='search' data-toggle='tooltip'
                       onclick=\"if(window.dtTbl){ dtTbl.search($('#q').val()).draw(); }\">
                       <span class='glyphicon glyphicon-search'></span>
                    </a>
                  </span>";
$list_data .= "  </div>";
$list_data .= "</div>";

// date picker (reload datatable, bukan reload page)
$list_data .= "<div class='col-md-2'>";
$list_data .= "  <input type='date' name='date'
                    min='".htmlspecialchars($oldDate)."'
                    max='".htmlspecialchars($maxDate)."'
                    id='date'
                    class='form-control'
                    value='".htmlspecialchars($defaultDate)."'>";
$list_data .= "</div>";

// kanan kosong (dulu btnPage)
$list_data .= "  
   
    <div class=\"col-md-2\">
      <div class=\"input-group\">
        <span class=\"input-group-addon\">
          <i class=\"fa fa-filter\"></i>
        </span>
        <select id=\"kategoriPick\" class=\"form-control\">
          <option value=\"__all__\">Semua kategori</option>
        </select>
      </div>
    </div>

    <div class='col-md-4 text-right'>
        <div id='guardInfo'></div>
    </div>
    
    
    <div class='col-md-6 text-right hidden'>
        <a id=\"btnExportCsv\" class=\"btn btn-success\" href=\"javascript:void(0)\">
            <i class=\"fa fa-download\"></i> CSV
        </a>    
    </div>
  ";

$list_data .= "</div>"; // row
$list_data .= "</div>"; // panel

if (isset($warning_str)) $list_data .= $warning_str;

// =====================
// TABLE (header saja)
// =====================
$list_data .= "<div class='panel'>";

$list_data .= "<table id='myNewTable_2' width='100%' class='table table-bordered table-hover'>";
$list_data .= "<thead>";
$list_data .= "<tr bgcolor='#e5e5e5'>";
$list_data .= "<th class='text-right'>No.</th>";

foreach ($headerFields as $cName => $cValue) {
    $list_data .= "<th class='text-center text-uppercase' style='color:#555555;padding:3px;' title='".htmlspecialchars($cName)."'>"
        . htmlspecialchars($cValue)
        . "</th>";
}

$list_data .= "</tr>";
$list_data .= "</thead>";

$list_data .= "<tbody></tbody>";

$list_data .= "<tfoot>";
$list_data .= "<tr bgcolor='#e5e5e5'>";
$list_data .= "<th>&nbsp;</th>";
foreach ($headerFields as $cName => $cValue) {
    $list_data .= "<th></th>";
}
$list_data .= "</tr>";
$list_data .= "</tfoot>";

$list_data .= "</table>";
$list_data .= "</div>";

// =====================
// DATATABLES SERVER-SIDE
// =====================
/*
 * columns[] harus sama urutan $headerFields.
 * Di controller contoh saya: pid, kategori_nama, kode, barcode, extern_nama, qty_debet_akhir, debet_akhir
 */
$colsJs = array();
foreach ($headerFields as $k => $v) {
    $colsJs[] = "{ data: '".addslashes($k)."' }";
}
$colsJsStr = implode(",", $colsJs);

$list_data .= "<script>
$(document).ready(function(){

  // init datatable
  window.dtTbl = $('#myNewTable_2').DataTable({
    processing: true,
    serverSide: true,
    fixedHeader: true,
    deferRender: true,
    pageLength: -1,
    dom: 'lBfrtip',
    lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, 'semua']],
    order: [[7, 'desc']], // default sort: qty (sesuaikan index)
      buttons: [
            {
                extend: 'copyHtml5',
                text: 'Copy',
                exportOptions: { columns: ':visible:not(:first-child)' } // skip kolom No.
            },
            {
                // ini CSV/Excel bawaan hanya export \"current page\" saat serverSide
                // jadi kita arahkan user pakai tombol Export CSV custom (endpoint)
                extend: 'csvHtml5',
                text: 'CSV (page)',
                exportOptions: { columns: ':visible:not(:first-child)' }
            },
            {
                extend: 'excelHtml5',
                text: 'Excel (page)',
                exportOptions: { columns: ':visible:not(:first-child)' }
            },
            {
                extend: 'print',
                text: 'Print',
                exportOptions: { columns: ':visible:not(:first-child)' } // skip No.
            }
      ],
    ajax: {
      url: '".addslashes($dtAjaxUrl)."',
      type: 'GET',
      data: function(d){
        d.date = $('#date').val();
        d.kategori = $('#kategoriPick').val();
      },
//      dataSrc: function(json){
//        if(json.guardMessage){
//          $('#guardInfo').html('<div class=\"alert alert-warning\" style=\"margin:0; padding:6px 10px;\">'+json.guardMessage+'</div>');
//        } else {
//          $('#guardInfo').html('');
//        }
//        return json.data;
//      }
    },
    // tambah kolom nomor otomatis (kolom pertama)
    columns: [
      { data: null, orderable:false, searchable:false }, // No.
      { data: 'pid' },
      { data: 'kategori_nama' },
      { data: 'kode' },
      { data: 'barcode' },
      { data: 'extern_nama' },
    
      // QTY
      {
        data: 'qty_debet_akhir',
        className: 'text-right',
        render: function(data, type, row){
          var v = parseFloat(data || 0);
          if (type === 'display' || type === 'filter') {
            return addCommas(v.toFixed(0));
          }
          return v; // penting: sorting pakai angka asli
        }
      },
    
      // NILAI (IDR)
      {
        data: 'debet_akhir',
        className: 'text-right',
        render: function(data, type, row){
          var v = parseFloat(data || 0);
          if (type === 'display' || type === 'filter') {
            return addCommas(v.toFixed(0));
          }
          return v;
        }
      }
    ],

    columnDefs: [
      { targets: 0, className: 'text-right' }
    ],
    createdRow: function(row, data, dataIndex){
      // bikin kolom pertama clickable? tidak perlu; link ada di kolom lain jika kamu render di server.
    },
    drawCallback: function(settings){
      // update nomor urut
      var api = this.api();
      var start = api.page.info().start;
      api.column(0, {page:'current'}).nodes().each(function(cell, i){
        cell.innerHTML = (start + i + 1) + '.';
      });
    },
    footerCallback: function(row, data, start, end, display){
      var api = this.api();

      var intVal = function(i){
        if (typeof i === 'string') {
          // buang tag HTML + koma
          i = i.replace(/<[^>]*>/g,'').replace(/,/g,'');
        }
        var x = parseFloat(i);
        return isNaN(x) ? 0 : x;
      };

      // contoh: totalin kolom qty_debet_akhir & debet_akhir
      // sesuaikan index: karena ada kolom No. di depan
      // Index DataTables:
      // 0 No.
      // 1 pid
      // 2 kategori_nama
      // 3 kode
      // 4 barcode
      // 5 extern_nama
      // 6 qty_debet_akhir
      // 7 debet_akhir
      var idxQty = 6;
      var idxVal = 7;

      var pageQty = api.column(idxQty, { page: 'current' }).data()
        .reduce(function(a,b){ return intVal(a) + intVal(b); }, 0);

      var pageVal = api.column(idxVal, { page: 'current' }).data()
        .reduce(function(a,b){ return intVal(a) + intVal(b); }, 0);

      if(pageQty){
        $(api.column(idxQty).footer()).html(\"<div class='text-right text-primary text-bold'>\"+addCommas(pageQty.toFixed(0))+\"</div>\");
      } else {
        $(api.column(idxQty).footer()).html('');
      }

      if(pageVal){
        $(api.column(idxVal).footer()).html(\"<div class='text-right text-primary text-bold'>\"+addCommas(pageVal.toFixed(0) )+\"</div>\");
      } else {
        $(api.column(idxVal).footer()).html('');
      }
    }
  });

  // wiring search box ke datatable (enter)
  $('#q').on('keydown', function(e){
    if(e.keyCode === 13){
      dtTbl.search(this.value).draw();
      return false;
    }
  });

  $.getJSON('".base_url('LedgerV2/viewBalances_periode_2_categories')."', function(list){
      var k = $('#kategoriPick');
      $.each(list, function(i, it){
        $(k).append('<option value=\"'+it.value+'\">'+it.label+'</option>');
      });
    });

  
  // date change => reload dt
  $('#date').on('change', function(){
        var d = $(this).val();
    dtTbl.ajax.reload(null, true);
    
    updateJudulByDate(d);
  });
  
    $('#kategoriPick').on('change', function(){
      dtTbl.ajax.reload(null, true);
    });


  $('#btnExportCsv').on('click', function(){
      var q = dtTbl.search() || '';
      var date = $('#date').val();
      var kategori = $('#kategoriPick').val();
    
      // endpoint export
      var url = '".base_url('LedgerV2/viewBalances_periode_2_export_csv/'.$this->uri->segment(3).'/'.urlencode($this->uri->segment(4)))."';
      url += '?date='+encodeURIComponent(date)
          + '&q='+encodeURIComponent(q)
          + '&kategori='+encodeURIComponent(kategori);
    
      window.location = url;
      close_holdon();
    });

    function fmtIndoUpper(d){
        return moment(d, 'YYYY-MM-DD').format('D MMM YYYY').toUpperCase();
    }
    function fmtIndo(d){
        return moment(d, 'YYYY-MM-DD').format('D MMM YYYY');
    }

  function updateJudulByDate(d){
      var labelUpper = fmtIndoUpper(d);
      var label      = fmtIndo(d);
    
      // ===== title tab browser =====
      document.title = 'Persediaan per ' + label;
    
      // ===== sidebar / top title =====
      $('.sidebar-title b.text-uppercase')
        .text('Persediaan per ' + labelUpper);
    
      // ===== box header subtitle =====
      $('.box-header.with-border .box-title.text-uppercase')
        .html(
          'Posisi persediaan <r>per ' + labelUpper +
          '</r> gudang reguler di ". addslashes(my_cabang_nama()) . "' +
          '<small>projek belum termasuk</small>'
        );
    }


});
</script>";

// render layout
$p->addTags(array(
    "menu_left"        => callMenuLeft(),
    "float_menu_atas"  => callFloatMenu('atas'),
    "float_menu_bawah" => callFloatMenu(),
    "menu_taskbar"     => callMenuTaskbar(),
    "btn_back"         => callBackNav(),
    "content"          => $list_data,
    "content_free"     => isset($content_free) ? $content_free : "",
    "profile_name"     => $this->session->login['nama'],
));

$p->setContent($contens);
$p->render();
